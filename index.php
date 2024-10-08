<?php

require __DIR__.'/includes/app.php';

use \App\Http\Router;

// INICIA O ROUTER
$obRouter = new Router(URL);

// INCLUIR AS ROTAS DE PAGINAS
include __DIR__.'/routes/pages.php';

//IMPRIME O RESPONSE DA ROTA
$obRouter->run()
         ->sendResponse();