<?php

namespace App\Http\Middleware;

use \App\Session\User\Recover as SessionUserRecover;

class RequireRecoverOn{
    
    /**
     * Metodo responsavel por executar o proximo nivel da fila de middlewares
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next)  {
        // VERIFICA SE O UTILIZADOR PODE OU NAO RESETAR A SUA SENHA
        if (!SessionUserRecover::recoverIsEnable()) {
            $request->getRouter()->redirect('/senha/recuperar?status=required');
        }

        // CONTINUA A EXECUCAO
        return $next($request);
    }
}