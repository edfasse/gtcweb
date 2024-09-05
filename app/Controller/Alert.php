<?php

namespace App\Controller;

use \App\Utils\View;

class Alert{

    /**
     * Metedo responsavel por retornar uma mensagem de sucessp
     * @param string $message
     * @return string
     */
    public static function getSuccess($message){
        return View::render('alert/status',[
            'tipo' => 'success',
            'mensagem' => $message
        ]);
    }

    /**
     * Metedo responsavel por retornar uma mensagem de erro
     * @param string $message
     * @return string
     */
    public static function getError($message){
        return View::render('alert/status',[
            'tipo' => 'danger',
            'mensagem' => $message
        ]);
    }

    /**
     * Metedo responsavel por retornar uma mensagem de alerta
     * @param string $message
     * @return string
     */
    public static function getWarning($message){
        return View::render('alert/status',[
            'tipo' => 'warning',
            'mensagem' => $message
        ]);
    }
}