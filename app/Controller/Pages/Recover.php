<?php

namespace App\Controller\Pages;

use \App\Controller\Alert;
use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;

Class Recover extends Page{

    /**
     * Metedo responsavel por retornar o conteudo da view forgot-password
     * @param Request
     * @param string $errorMessage
     * @return string
     */
    public static function get($email, $forgot, $request) {

        // VALIDACOES
        if (empty($email) || empty($forgot)) {
            $request->getRouter()->redirect('/senha/recuperar?status=required');
        }
        // VALIDACOES
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $request->getRouter()->redirect('/senha/recuperar?status=required');
        }

        // Busca o utilizador na base de dados
        $obUser = EntityUser::getUsers('email ="'.$email.'" AND forgot = "'.$forgot.'"')
                                        ->fetchObject(EntityUser::class);

        // VALIDACOES
        if (!$obUser instanceof EntityUser) {
            $request->getRouter()->redirect('/senha/recuperar?status=required');
        }
    // VIEW FORGOT-PASSWORD
        $content = View::render('pages/recover-password',[
                'status' => self::getStatus($request),
        ]);

        return parent::getPage('MolaCash - Recuperando a sua Senha', $content);

    }

    /**
     * Metedo responsavel por enviar um link de recuperacao de senha ao email do utilizador
     * @param Request
     */
    public static function set($email, $forgot, $request) {

        // DADOS DO POST
        $postVars = $request->getPostVars();

        // EFECTUA A VALIDACAO DOS CAMPOS
        $request->Validate([
            'new_password' => ['confirmed','required'],
           ]);
           
        // Busca o utilizador na base de dados
        $obUser = EntityUser::getUsers('email ="'.$email.'" AND forgot = "'.$forgot.'"')
        ->fetchObject(EntityUser::class);

           $obUser->password = password_hash($postVars['new_password'],PASSWORD_BCRYPT) ;
   
           $obUser->change_password();
   
           $request->getRouter()->redirect('/login?status=changed');
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
            
        }
    }
}