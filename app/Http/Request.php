<?php

namespace App\Http;

use App\Helper\Validate;

class Request{
    
    /**
     * Instancia do Router
     * @var Router
     */
     private $router;
    
    /**
     * Metodo HTTP da requisicao
     * @var string
     */
     private $httpMethod;

     /**
      * URI da pagina
      * @var string
      */
     private $uri;

     
    /**
     * Parametros da URL
     * @var array
     */
    private $queryParams = [];

    
    /**
     * Variaveis recebidas no POST da pagina ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * Cabecalho da requisicao
     * @var array
     */
    private $headers = [];

    /**
     * Mensagem de erro
     * @var string
     */
	private $errorMessages;

    /**
     * Nome do Aplicacao
     */
    private $app_name;

    /**
     * Construtor da classe
     */
    public function __construct($router){
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->postVars    = $_POST ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setAppName();
    }

    /**
     * Metedo responsavel por definit a URI
     */
    public function setUri(){
        // URI COMPLETE (COM GETS)
        $this->uri         = $_SERVER['REQUEST_URI'] ?? '';

        // REMOVE GETS DA URI
        $xURI = explode('?', $this->uri);
        $this->uri = $xURI[0];
        
    }

    /**
     * Metedo responsavel por definit a URI
     */
    public function setAppName(){
        // URL COMPLETE (COM GETS)
        $this->app_name         = URL ?? '';

        // DIVIDE A URL
        $xURL = explode('/', $this->app_name);
        $this->app_name = $xURL[2];
        
    }

    /**
     * Metedo responsavel por retornar a instancia de Router
     * @return Router
     */
    public function getRouter(){
        return $this->router;
    }

    /**
     * Metedo responsavel por retornar o metodo HTTP da requisicao
     * @return string
     */
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    /**
     * Metedo responsavel por retornar a URI da requisicao
     * @return string
     */
    public function getUri(){
        return $this->uri;
    }

    /**
     * Metedo responsavel por retornar o nome da Aplicacao
     * @return string
     */
    public function getAppName(){
        return $this->app_name;
    }

    /**
     * Metedo responsavel por retornar os Parametros da URL da requisicao
     * @return array
     */
    public function getQueryParams(){
        return $this->queryParams;
    }

    /**
     * Metedo responsavel por retornar as Variaveis recebidas no POST da requisicao
     * @return array
     */
    public function getPostVars() {
        return $this->postVars;
    }
    
    /**
     * Metedo responsavel por retornar o Cabecalho da requisicao
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }

    public function validate($fields = []) {
        // INICIAR O CONSTRUTOR DA CLASSE VALIDATE
        $validation = new Validate($fields, $this->postVars);

        if (!$validation->make()) {
            $this->errorMessages = $validation->getMessages();
            return false;
        }
        
        return true;
    }

	public function getErrorMessages() {
		return $this->errorMessages;
	}
}