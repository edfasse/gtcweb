<?php

namespace App\Http\Middleware;

use \App\Session\User\Login as SessionUserLogin;

class RequireAdminPermissions{
    
    /**
     * Metodo responsavel por executar o proximo nivel da fila de middlewares
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next)  {
        // VERIFICA O NIVEL DE ACESSO DO UTILIZADOR
        if (!SessionUserLogin::isAdmin()) {
            $request->getRouter()->redirect('/guest');
        }

        // CONTINUA A EXECUCAO
        return $next($request);
    }
}