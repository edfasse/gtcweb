<?php

namespace App\Http;

class Response{

    /**
     * Codigo de Status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * cabecalho do Response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de Conteudo que esta sendo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteudo do Response
     * @var mixed
     */
    private $content;

    /**
     * Metodo responsavel por iniciar a classe e definir os valores
     * @param string $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html'){
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }
    
    /**
     * Metodo responsavel por alterar o content type do response
     * @param string $contentType
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;  
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Metodo responsavel por adicionar um registo no cabecalho do response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value){
        $this->headers[$key] = $value;
    }

    /**
     * Metodo responsavel por enviar a headers para o navegadores
     */
    public function sendHeaders(){
        // STATUS
        http_response_code($this->httpCode);

        // ENVIAR HEADERS
        foreach ($this->headers as $key => $value) {
            header($key.': '.$value);
        }
    }

    /**
     * Metodo responsavel por enviar a resposta para o usuario
     */
    public function sendResponse(){
        // ENVIA OS HEADERS
        $this->sendHeaders();

        // IMPRIME O CONTEUDO
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
            case 'aplication/pdf':
                echo $this->content;
                exit;
        }
    }
}