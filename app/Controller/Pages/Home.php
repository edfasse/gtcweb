<?php

namespace App\Controller\Pages;

use \App\Controller\Alert;
use \App\Support\Email;
use \App\Utils\View;

Class Home{

    /**
     * Metedo responsavel por retornar o conteudo da view home
     * @param Request
     * @param string $errorMessage
     * @return string
     */
    public static function get($request) {

    // VIEW HOME
        return View::render('pages/home',[
                'status' => self::getStatus($request),
        ]);

    }

    /**
     * Metedo responsavel por enviar um email aos donos do site
     * @param Request
     */
    public static function set($request) {

        // DADOS DO POST
        $postVars = $request->getPostVars();

        // EFECTUA A VALIDACAO DOS CAMPOS
        $request->Validate([
            'name'  => ['required'],
            'email' => ['email','required'],
            'subject' => ['required'],
            'message' => ['required']
           ]);

        // INSTANCIA DO EMAIL
        $email = new Email;

        // CRIA A MENSAGEM DE ENVIO E ENVIA A MENSAGEM
        $email->add(
            $postVars['subject'],
            $postVars['message'],
            getenv('EMAIL_RECEIVE_NAME'),
            getenv('EMAIL_RECEIVE'),
        )->send($postVars['name'], $postVars['email']);

        $request->getRouter()->redirect('?status=send#contato01');
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
        // MENSAGENS DE STATUS
        switch ($queryParams['status']) {
            case 'required':
                return Alert::getError('alguns campos estao vazio, por favor preencha todos');
                break;
            case 'email':
                return Alert::getError('Informe um email valido por favor');
                break;
            case 'send':
                return Alert::getSuccess('Email Enviado com sucesso');
                break;
            
        }
    }
}