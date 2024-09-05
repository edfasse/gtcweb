<?php

namespace App\Controller\Pages;

use \App\Controller\Alert;
use \App\Model\Entity\User as EntityUser;
use \App\Session\User\Recover;
use \App\Support\Email;
use \App\Utils\View;

Class Forgot extends Page{

    /**
     * Metedo responsavel por retornar o conteudo da view forgot-password
     * @param Request
     * @param string $errorMessage
     * @return string
     */
    public static function get($request) {

    // VIEW FORGOT-PASSWORD
        $content = View::render('pages/forgot-password',[
                'status' => self::getStatus($request),
        ]);

        return parent::getPage('MolaCash - Esqueceu Senha', $content);

    }

    /**
     * Metedo responsavel por enviar um link de recuperacao de senha ao email do utilizador
     * @param Request
     */
    public static function set($request) {

        // DADOS DO POST
        $postVars = $request->getPostVars();

        // EFECTUA A VALIDACAO DOS CAMPOS
        $request->Validate([
            'email' => ['exists:users','email','required',],
           ]);
        $obUser = EntityUser::getUserByEmail($postVars['email'], 'id, email');

        $obUser->forgot = (md5(uniqid(rand(), true)));
        $obUser->email = $postVars['email'];

        $obUser->actualizar_forgot();
        $email = new Email;
        $email->add(
            'Recupere a sua senha | '.$request->getAppName(),
            View::render('email/email',[
                'title' => 'recuperando o email',
                'firstname' => $obUser->firstname,
                'link'  => URL.'senha/nova/'.$postVars['email'].'/'.$obUser->forgot
            ]),
            $obUser->firstname. ' ' .$obUser->lastname,
            $postVars['email']
        )->send();

        Recover::enableRecover($obUser);

        $request->getRouter()->redirect('/senha/recuperar?status=send');
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
        $msg = 'Informe seu email para recuperar a senha';
        // MENSAGENS DE STATUS
        switch ($queryParams['status']) {
            case 'required':
                return Alert::getWarning($msg);
                break;
            case 'email':
                return Alert::getWarning($msg);
                break;
            case 'notfound':
                return Alert::getError($msg);
                break;
            case 'send':
                return Alert::getSuccess('Enviamos um link de recuperacao no seu email');
                break;
            
        }
    }
}