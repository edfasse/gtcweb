<?php

namespace App\Http\Middleware;

use App\Utils\View;

class Maintenance{

    /**
     * Metodo responsavel por executar o proximo nivel da fila de middlewares
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next)  {
        // VERIFICA O ESTADO DE MANUTENCAO DA PAGINA
        if (getenv('MAINTENANCE') == 'true') {
            throw new \Exception(View::render('error/error',
            [
                'code' => 200,
                'error_message' => 'Pagina em manuntenção, por favor tente aceder mais tarde',
                'error_motivo' => ''
            ]), 200);
        }
        // echo '<pre>';
        // print_r(getenv('MAINTENANCE'));
        // echo '</pre>';exit;

        // EXECUTA O PROXIMO NIVEL DE MIDDLEWARE
        return $next($request);
    }
}