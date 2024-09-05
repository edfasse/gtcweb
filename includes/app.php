<?php

require __DIR__.'/../vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;
use \App\Support\Email;

// CARREGA VARIAVEIS DE AMBIENTE
Environment::load(__DIR__.'/../');

// DEFINE AS CONFIGURACOES DE BASE DE DADOS
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

// DEFINE AS CONFIGURACOES DE EMAIL

Email::config(
    getenv('EMAIL_HOST'),
    getenv('EMAIL_PORT'),
    getenv('EMAIL_USER'),
    getenv('EMAIL_PASS'),
);

// DEFINE A CONSTANTE URL DO PROJECTO
define('URL', getenv('URL'));

View::init([
    'URL' => URL,
]);

// DEFINE O MAPEAMENTO DE MIDDLEWARES

MiddlewareQueue::setMap([
    'maintenance'                => \App\Http\Middleware\Maintenance::class,
    'required-user-logout'       => \App\Http\Middleware\RequireUserLogout::class,
    'required-user-login'        => \App\Http\Middleware\RequireUserLogin::class,
    'required-admin-permissions' => \App\Http\Middleware\RequireAdminPermissions::class,
    'required-recover-on'        => \App\Http\Middleware\RequireRecoverOn::class,
]);

// DEFINE O MAPEAMENTO DE MIDDLEWARES PADROES (EXECUTADOS EM TODAS AS ROTAS)

MiddlewareQueue::setDefault([
    'maintenance'
]);
