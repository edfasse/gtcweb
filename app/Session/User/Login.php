<?php

namespace App\Session\User;

class Login{
    
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
     * Metedo responsavel por criar o Login do utilizador
     * @param User $obUser
     * @return boolean
     */
    public static function login($obUser) {
        // INICIA A SESSAO
        self::init();

        // DEFINE A SESSAO DO USUARIO
        if ($obUser->permissions_id == 1) {
            $_SESSION['admin']['usuario'] = [
                'id' => $obUser->id,
                'name' => $obUser->firstname." ".$obUser->lastname,
                'username' => $obUser->username,
                'permissions_id' => $obUser->permissions_id
            ]; 
        }else{
            $_SESSION['guest']['usuario'] = [
                'id' => $obUser->id,
                'name' => $obUser->firstname." ".$obUser->lastname,
                'username' => $obUser->username,
                'permissions_id' => $obUser->permissions_id
            ];
        }
        // echo "<pre>";
        // print_r($obUser);echo "</pre>";exit;

        // SUCCESS
        return true;
    }

    /**
     * Metedo responsavel por verificar se o utilizador esta logado
     * @return boolean
     */
    public static function isLogged() {
        // INICIA A SESSAO
        self::init();

        // RETORNA A VERIFICACAO
        return isset($_SESSION['admin']['usuario']['id']) || 
                                isset($_SESSION['guest']['usuario']['id']);
        
    }

    /**
     * Metedo responsavel por verificar se o utilizador esta logado
     * @return boolean
     */
    public static function isAdmin() {
        // INICIA A SESSAO
        self::init();

        // RETORNA A VERIFICACAO
        return isset($_SESSION['admin']['usuario']['id']);
        
    }

    /**
     * Metedo responsavel por executar o logout do utilizador
     * @return boolean
     */
    public static function logout() {
        // INICIA A SESSAO
        self::init();

        // DESLOGA O UTILIZADOR
        if (isset($_SESSION['guest']['usuario']['id'])) {
            // DESLOGA O UTILIZADOR NORMAL
            unset($_SESSION['guest']['usuario']);

        }elseif (isset($_SESSION['admin']['usuario']['id'])) {
            // DESLOGA O UTILIZADOR ADMIN
            unset($_SESSION['admin']['usuario']);

        }
        
        // SUCCESS
        return true;
    }
}