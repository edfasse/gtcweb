<?php

namespace App\Http\Middleware;

use \App\Session\User\Login as SessionUserLogin;

class RequireUserLogout{
    
    /**
     * Metodo responsavel por executar o proximo nivel da fila de middlewares
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next)  {
        // VERIFICA SE O UTILIZADOR ESTA LOGADO
        if (SessionUserLogin::isLogged()) {
            if (SessionUserLogin::isAdmin()) {
                $request->getRouter()->redirect('/admin');
            }
            $request->getRouter()->redirect('/guest');
        }

        // CONTINUA A EXECUCAO
        return $next($request);
    }
}