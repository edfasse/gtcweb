<?php

namespace App\Controller\Pages;

use App\Controller\Alert;
use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\User\Login as SessionUserLogin;
use App\Session\User\Recover;

Class Login extends Page{

    /**
     * Metedo responsavel por retornar o conteudo da view Login
     * @param Request
     * @param string $errorMessage
     * @return string
     */
    public static function get($request, $errorMessage = null) {

    // STATUS
    $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

    // VIEW DE LOGIN
        $content =  View::render('pages/login',[
                'status' => $status,
                'status' => self::getStatus($request)
        ]);

        return parent::getPage('MolaCash - Login', $content);

    }

    /**
     * Metedo responsavel por definir o Login do utilizador
     * @param Request
     */
    public static function set($request) {

        // POST VARS
        $postVars = $request->getPostVars();

        // EFECTUA A VALIDACAO DOS CAMPOS
        $request->Validate([
            'username' => ['required'],
            'password' => ['required'],
           ]);

        $username = $postVars['username'] ?? '';
        $password = $postVars['password'] ?? '';
 
        // BUSCA O UTILIZADOR PELO USERNAME
        $obUser = User::getUserByUsername($username);
        if (!$obUser instanceof User) {
            $request->getRouter()->redirect('?status=dained');
        }

        // VERIFICA A SENHA DO UTILIZADOR
        if (!password_verify($password, $obUser->password)) {
            $request->getRouter()->redirect('?status=dained');
        }

        // CRIA A SESSAO DE LOGIN
        SessionUserLogin::login($obUser);

        // REDIRECIONA O UTILIZADOR PARA O DASHBOARD DO ADMIN
        $request->getRouter()->redirect('/admin');
    
    }

    /**
     * Metedo responsavel por deslogar o utilizador
     * @param Request
     */
    public static function setLogout($request) {
    
        // DESTROI A SESSAO DE LOGIN
        SessionUserLogin::logout();

        // REDIRECIONA O UTILIZADOR PARA A TELA DE LOGIN
        $request->getRouter()->redirect('/login');
    }

    /**
     * Metedo responsavel por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
    public static function getStatus($request) {
        // QUERY PARAMS
        $queryParams = $request->getQueryParams();

        // STATUS
        if (!isset($queryParams['status'])) return '';
        $msg = 'Alguns Campos estao vazios, por favor preencha todos campos';
        // MENSAGENS DE STATUS
        switch ($queryParams['status']) {
            case 'required':
                return Alert::getWarning($msg);
                break;
            case 'dained':
                return Alert::getError('Nome de Utilizador ou Palavra-Passe invalidos');
                break;
            case 'changed':
                return Alert::getSuccess('Senha Recuperada com sucesso, por favor o login com a nova senha');
                break;
            
        }
    }
}