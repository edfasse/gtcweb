<?php

namespace App\Session\User;

class Recover{
    
    /**
     * Metedo responsavel por iniciar a sessao
     */
    private static function init(){
        // VERIFICA SE A SESSAO NAO ESTA ACTIVA
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    

    /**
     * Metedo responsavel por criar a sessao de reset de senha do utilizador
     * @param User $obUser
     * @return boolean
     */
    public static function enableRecover($obUser) {
        // INICIA A SESSAO
        self::init();
        $_SESSION['forgot'] = $obUser->id;
        return true;
    }

    /**
     * Metedo responsavel por verificar se o utilizador oode ou nao resetar a sua senha
     * @return boolean
     */
    public static function recoverIsEnable() {
        // INICIA A SESSAO
        self::init();

        // RETORNA A VERIFICACAO
        return isset($_SESSION["forgot"]);
        
    }

    /**
     * Metedo responsavel por criar a sessao de reset de senha do utilizador
     * @param User $obUser
     * @return boolean
     */
    public static function desableRecover() {
        // INICIA A SESSAO
        self::init();
        unset($_SESSION['forgot']);
        return true;
    }
}