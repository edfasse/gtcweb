<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{
    /**
     * ID do Utilizador
     * @var string
     */
    public $id;

    /**
     * Primeiros Nomes do Utilizador
     * @var string
     */
    public $firstname;

    /**
     * Apelido/Ultimo nome do Utilizador
     * @var string
     */
    public $lastname;

    /**
     * Email do Utilizador
     * @var string
     */
    public $email;
    
    /**
     * Nivel de Acesso do Utilizador
     * @var integer
     */
    public $permissions_id;

    /**
     * Nome de Utilizador
     * @var string
     */
    public $username;

    /**
     * Palavra-passe do Utilizador
     * @var string
     */
    public $password;

    /**
     * Valor Recolhido pelos cobradores (usuario guest)
     * @var double
     */
    public $recolhas;

    /**
     * hash usando na recuperacao de uma senha
     * @var string
     */
    public $forgot;

    /**
     * Metedo responsavel por cadastrar a instancia actual na base de dados
     * @return boolean
     */
    public function cadastrar(){

        // INSERE DADOS DO UTILIZADOR NA BASE DE DADOS
        $this->id = (new Database('users'))->insert([
            'id'             => $this->id,
            'firstname'      => $this->firstname,
            'lastname'       => $this->lastname,
            'email'          => $this->email,
            'username'       => $this->username,
            'password'       => $this->password,
        ]);
        
        return true;
    }

    /**
     * Metedo responsavel por actualizar os dados da base com a instancia actual
     * @return boolean
     */
    public function actualizar(){

        // ACTUALIZA OS DADOS DO UTILIZADOR NA BASE DE DADOS
        return (new Database('users'))->update(' id = "'.$this->id.'"', [
            'firstname'      => $this->firstname,
            'lastname'       => $this->lastname,
            'email'          => $this->email,
            'username'       => $this->username,
        ]);
    }


    /**
     * Metedo responsavel por actualizar os dados da base com a instancia actual
     * @return boolean
     */
    public function actualizar_recolhas(){

        // ACTUALIZA OS DADOS DO UTILIZADOR NA BASE DE DADOS
        return (new Database('users'))->update(' id = "'.$this->id.'"', [
            'recolhas'      => $this->recolhas,
        ]);
    }


    /**
     * Metedo responsavel por actualizar os dados da base com a instancia actual
     * @return boolean
     */
    public function actualizar_forgot(){

        // ACTUALIZA OS DADOS DO UTILIZADOR NA BASE DE DADOS
        return (new Database('users'))->update(' email = "'.$this->email.'"', [
            'forgot'      => $this->forgot,
        ]);
    }

    /**
     * Metedo responsavel por actualizar os dados da base com a instancia actual
     * @return boolean
     */
    public function change_password(){

        // MUDA A SENHA DO UTILIZADOR NA BASE DE DADOS
        return (new Database('users'))->update(' id = "'.$this->id.'"', [
            'password'       => $this->password,
        ]);
    }

    /**
     * Metedo responsavel por actualizar os dados da base com a instancia actual
     * @return boolean
     */
    public function change($fields){

        // MUDA A SENHA DO UTILIZADOR NA BASE DE DADOS
        return (new Database('users'))->update(' id = "'.$this->id.'"', $fields);
    }
    /**
     * Metedo responsavel por actualizar os dados da base com a instancia actual
     * @return boolean
     */
    public function excluir(){

        // ACTUALIZA OS DADOS DO UTILIZADOR NA BASE DE DADOS
        return (new Database('users'))->delete(' id = "'.$this->id.'"');
    }

    /**
     * Metedo responsavel por retornar dados de utilizadores na base de dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getUsers($where = '', $order = '', $limit = '', $fields = '*',$inner = ''){
        return (new Database('users'))->select($where, $order, $limit, $fields,$inner);
    }

    /**
     * Metedo responsavel por retornar o utilizador com base em seu username
     * @param string $username
     * @return User
     */
    public static function getUserByUsername($username){
        return (new Database('users'))->select('username = "'.$username.'"')->fetchObject(self::class);
    }

    /**
     * Metedo responsavel por retornar o utilizador com base em seu ID
     * @param string $username
     * @return User
     */
    public static function getUserById($id, $fields = null){
        return (new Database('users'))->select('id = "'.$id.'"', '', '', $fields)->fetchObject(self::class);
    }

    /**
     * Metedo responsavel por retornar o utilizador com base em seu Email
     * @param string $username
     * @return User
     */
    public static function getUserByEmail($email, $fields = null){
        return (new Database('users'))->select('email = "'.$email.'"', '', '', $fields)->fetchObject(self::class);
    }

    public static function getDate(){
        return (new Database('users'))->getDate()->fetchObject(self::class);
    }

}