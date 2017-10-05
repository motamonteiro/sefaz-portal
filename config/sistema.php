<?php

return [

    'versao' => env('SISTEMA_VERSAO'),
    'sigla' => env('SISTEMA_SIGLA'),
    'nome' => env('SISTEMA_NOME'),
    'desc' => env('SISTEMA_DESC'),
    'url' => env('SISTEMA_URL'),
    'url_backend' => env('SISTEMA_URL_BACKEND'),

    'ambiente' => [
        'sigla' => env('AMBIENTE_SIGLA'),
        'nome' => env('AMBIENTE_NOME'),
    ],

    'cdn' => [
        'css' => env('CDN_CSS'),
        'js' => env('CDN_JS'),
        'img' => env('CDN_IMG'),
    ],

    'email' => [
        'backend' => env('EMAIL_BACKEND'),
        'backend_servidor' => env('EMAIL_BACKEND_SERVIDOR'),
        'frontend' => env('EMAIL_FRONTEND'),
        'portal_api' => env('EMAIL_PORTAL_API'),
    ],

    'portal' => [
        'url' => env('PORTAL_URL'),
        'nome_cookie' => env('PORTAL_NOME_COOKIE'),
    ],

    'portal_api' => [
        'url' => env('PORTAL_API_URL'),
        'token_key' => env('PORTAL_API_TOKEN_KEY'),
    ],
];
