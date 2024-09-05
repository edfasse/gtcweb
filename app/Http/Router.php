<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;
use App\Utils\View;

class Router{

    /**
     * URL completo do projecto ($raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * indice de rotas
     * @var array
     */
    private $routes = [

    ];

    /**
     * Instancia de Request
     * @var Request
     */
    private $request;

    /**
     * Metodo responsavel por iniciar a classe
     * @param string 
     */
    public function __construct($url){
        $this->request = new Request($this);
        $this->url     = $url;
        $this->setPrefix();
    }

    /**
     * Metodo responsavel por definir o prefixo das rotas
     */

    private function setPrefix(){
        // INFORMACOES DA URL ACTUAL
        $parseUrl = parse_url($this->url);

        // DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Metodo responsavel por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params = []){

        // VALIDACAO DOS PARAMETROS
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // MIDDLEWARE DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        // VARIAVEIS DA ROTA
        $params['variables'] = [];

        // PADRAO DE VALIDACAO DAS VARIAVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        // PADRAO DE VALIDACAO DA URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        // ADICIONA A ROTA DENTRO DA CLASSE
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Metodo responsavel por definir uma rota de GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = []){
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Metodo responsavel por definir uma rota de POST
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = []){
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Metodo responsavel por definir uma rota de PUT
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = []){
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Metodo responsavel por definir uma rota de DELETE
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = []){
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Metodo responsavel por retornar a URI desconsiderando o prefixo
     * @return string
     */
    private function getUri(){
        // URI DA REQUEST
        $uri = $this->request->getUri();

        // FATIA A URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix,$uri) : [$uri];

        // RETORNA A URI SEM O PREFIXO
        return end($xUri);
    }

    /**
     * Metodo responsavel por retornar os dados da rota actual
     * @return array
     */
    private function getRoute(){
        //URI
        $uri = $this->getUri();

        // METHOD
        $httpMethod = $this->request->getHttpMethod();
        
        // VALIDA AS ROTAS
        foreach ($this->routes as $patternRoute => $methods) {
           // VERIFICA SE A URI BATE COM PADRAO
           if (preg_match($patternRoute, $uri, $matches)) {
                // VERIFICA O METODO
                if (isset($methods[$httpMethod])) {

                    // REMOVE A PRIMEIRA POSICAO
                    unset($matches[0]);

                    // VARIAVEIS PROCESSADAS
                    $key = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($key,$matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;
                   
                    //RETORNO DOS PARAMETROS DA ROTA
                    return $methods[$httpMethod];
                }
                // METODO NAO PERMITIDO/DEFINIDO
                throw new Exception(View::render('error/error',
                    [
                        'code' => 405,
                        'error_message' => 'Metodo não permitido',
                        'error_motivo' => 'Isso acontece quando tentas fazer uma requisição a um metodo inexistente ou invalido...'
                    ]), 405);
           }
        }

        //URL NAO ENCONTRADA
        throw new Exception(View::render('error/error',
                    [
                        'code' => 404,
                        'error_message' => 'Pagina não encontrada',
                        'error_motivo' => 'Isso acontece quando tentas aceder a um link inexistente...'
                    ]), 404);
        
        
    }

    /**
     * Metodo responsavel por executar a rota actual
     * @return Response
     */
    public function run(){
        try{
            // OBTEM A ROTA ACTUAL
            $route = $this->getRoute();
            
            // VERIFICA O CONTROLADOR
            if (!isset($route['controller'])) {
                throw new Exception(View::render('error/error',
                [
                    'code' => 500,
                    'error_message' => 'A URL não pode ser processada',
                    'error_motivo' => '......'
                ]), 500);
            }

            // ARGUMENTOS DA FUNCAO
            $args = [];

            // REFLACTION
            $reflaction = new ReflectionFunction($route['controller']);
            foreach ($reflaction->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
                
            }
            // RETORNA A EXECUCAO DA FILA DE MIDDLEWARES
            return (new MiddlewareQueue($route['middlewares'],$route['controller'],$args))->next($this->request);

            // RETORNA A EXECUCAO DA FUNCAO
            // return call_user_func_array($route['controller'], $args);

        }catch(Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Metodo responsavel por retornar a URL actual
     * @return string
     */
    public function getCurrentUrl(){
        return $this->url.$this->getUri();
    }

    /**
     * Metodo responsavel por redirecionar a URL
     * @param string $route
     */
    public function redirect($route){
        // URL
        $url = $this->url.$route;
        
        // EXECURA O REDIRECT
        header('location: '. $url);
        exit;
    }
}