<?php

    namespace App\Helper;

    use \WilliamCosta\DatabaseManager\Database;
    use \App\Http\Request;
    use \App\Http\Router;

    class Validate {

        private $postVars;
        private $validators = [];
        private $messages = [];
        private $message;

        private function init() {
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }
        }

        public function __construct($validators, $postVars) {
            $this->postVars   = $postVars;
            $this->validators = $validators;
        }

        public function confirmed($field) {

            if ($this->postVars[$field] === $this->postVars['password_confirmation']) return true;
            $this->message = 'unconfirmed';
            return false;
        }

        public function number($field) {
            if (empty($this->postVars[$field])) return true;
            if (is_numeric($this->postVars[$field])) return true;

            $this->message = 'isnotnumber';
            return false;
        }

        public function required($field) {

            if (empty($this->postVars[$field])) {
                $this->message = 'required';
                return false;
            }
            
            if (empty($field)) {
                $this->message = 'required';
                return false;
            }
            return true;
        }

        public function unique($field, $table) {
            $obDatabase = new Database($table);
            $data = $obDatabase->where($field, $this->postVars[$field])->first();

            if (!empty($data)) {
                $this->message = 'duplicated';
                return false;
            }

            return true;
        }

        public function exists($field, $table) {
            $obDatabase = new Database($table);
            $data = $obDatabase->where($field, $this->postVars[$field])->first();

            if (empty($data)) {
                $this->message = 'notfound';
                return false;
            }

            return true;
        }
        public function old_confirmed($field, $password_hash) {
            if (password_verify($this->postVars[$field], $password_hash)) {
                return true;
            }
            $this->message = 'notfound';
            return false;
        }

        public function mimes($field, $allowedExtensions) {

            if (empty($_FILES[$field]['name'])) return true;

            $source = $_FILES[$field]['name'];
            $pattern = '/['.$allowedExtensions.','.strtoupper($allowedExtensions).']$/';
            if (preg_match($pattern, $source)) return true;
            $this->messages[$field] = 'the field '.str_replace('_', ' ', $field).' must to be type of '.$allowedExtensions;
            return false;
        }

        public function max($field, $qtd) {
            $value = $this->postVars[$field];

            if (strlen($value) > $qtd) {
                $this->message = 'maximum';
                return false;
            }

            return true;
        }

        public function min($field, $qtd) {
            $value = $this->postVars[$field];

            if (strlen($value) < $qtd) {
                $this->message = 'minimum';
                return false;
            }

            return true;
        }


        public function verifyIfExistIndex($field) {
            $keys = array_keys($this->postVars);
            if (in_array($field, $keys)) return true;
            return false;
        }

        public function email($field) {
            if (empty($this->postVars[$field])) return true;

            if (!filter_var($this->postVars[$field], FILTER_VALIDATE_EMAIL)) {
                $this->message = 'email';
                return false;
            }
        }

        public function makeValidation($field, $validations) {
            foreach ($validations as $validation) {
                if ($this->verifyIfExistIndex($field) || isset($_FILES[$field])) {
                    $params = explode(':', $validation);
                    if (call_user_func_array([$this, $params[0]], [$field, $params[1] ?? null])) continue;
                }
            }
            if (!empty($this->message)) return false;
            return true;
        }

        public function make() {

            $router = new Router(URL);
            $request = new Request($router);
            $this->init();

            foreach ($this->validators as $field => $validations) $this->makeValidation($field, $validations);
            if (!empty($this->message)) {

                $url = explode(URL,$_SERVER['HTTP_REFERER'])[1].'?status=';
                $url = preg_replace('/(\?|&)status=[^&]*/', '$1status='.$this->message, $url); 

                return $request->getRouter()->redirect($url);
            } 
            return true;
        }

        public function getMessages() {
            return $this->messages;
        }

        public function getMessage() {
            return $this->message;
        }
    }