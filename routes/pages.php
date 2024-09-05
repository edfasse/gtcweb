<?php

use \App\Controller\Pages;
use App\Http\Response;

//ROTA LOGIN

$obRouter->get('/',[
    'middlewares' =>[
        'required-user-logout',
    ],
    function ($request){
        return new Response(200,Pages\Home::get($request));
    }
]);

//ROTA LOGIN ALTERNATIVO

$obRouter->get('/home',[
    'middlewares' =>[
        'required-user-logout'
    ],
    function ($request){
        return new Response(200,Pages\Home::get($request));
    }
]);

// ROTA LOGIN POST ALTERNATIVO
$obRouter->post('/home',[
    'middlewares' =>[
        'required-user-logout'
    ],
    function ($request){
        return new Response(200,Pages\Home::set($request));
    }
]);


// ROTA LOGIN POST
$obRouter->post('/',[
    'middlewares' =>[
        'required-user-logout'
    ],
    function ($request){
        return new Response(200,Pages\Home::set($request));
    }
]);
