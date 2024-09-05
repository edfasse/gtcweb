<?php

namespace App\Http\Middleware;

use \App\Session\User\Login as SessionUserLogin;

class RequireUserLogin{
    
    /**
     * Metodo responsavel por executar o proximo nivel da fila de middlewares
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next)  {
        // VERIFICA SE O UTILIZADOR ESTA LOGADO
        if (!SessionUserLogin::isLogged()) {
            $request->getRouter()->redirect('/');
        }

        // CONTINUA A EXECUCAO
        return $next($request);
    }
}