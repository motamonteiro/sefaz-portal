# Sefaz/Portal
Pacote que configura automaticamente o frontend das aplicações.

Instale uma nova versão do Laravel
``` bash
laravel new novoSistema
```

Crie a chave da aplicação
``` bash
php artisan key:generate
```

 Altere o nome da aplicação com o comando:

``` bash
 php artisan app:name NovoSistema
```

Adicione a dependência do Sefaz/Portal no novoSistema:
``` bash
composer require motamonteiro/sefaz-portal
```

Publique os arquivos necessários na pasta public
``` bash
php artisan vendor:publish
```

Escolha a opção do PortalServiceProvider
``` bash
[5 ] Provider: MotaMonteiro\Sefaz\Portal\Providers\PortalServiceProvider
```

Abra o aqrquivo `app\Http\Kernel.php` e adicione o `PortalMiddleware` para controlar a autenticacao e a permissao dos usuários
``` php
/**
 * The application's route middleware groups.
 *
 * @var array
 */
protected $middlewareGroups = [
    'web' => [
        ...
        //\App\Http\Middleware\VerifyCsrfToken::class, (se quiser, comente a verificação do CsrfToken)
        ...
    ],
    ...
];

/**
 * The application's route middleware.
 *
 * These middleware may be assigned to groups or used individually.
 *
 * @var array
 */
protected $routeMiddleware = [
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
    'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'can' => \Illuminate\Auth\Middleware\Authorize::class,
    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'portal' => \MotaMonteiro\Sefaz\Portal\Http\Middleware\FrontendMiddleware::class,
];
```

Abra o aqrquivo `app\Providers\EventServiceProvider.php` e substitua a variavel `$listen` de acordo com o trecho abaixo
``` php
/**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'MotaMonteiro\Sefaz\Portal\Events\ServidorApiNaoRespondeuEvent' => [
            'MotaMonteiro\Sefaz\Portal\Listeners\ServidorApiIndisponivelListener',
        ],
        'MotaMonteiro\Sefaz\Portal\Events\ServidorApiNaoRespondeuCorretamenteEvent' => [
            'MotaMonteiro\Sefaz\Portal\Listeners\ServidorApiRespostaInvalidaListener',
        ],
    ];
```

Copie trecho abaixo e cole no final do arquivo `.env` e altere de acordo com o seu projeto
``` php
#-----------------------------------------------------------------------------------------------------------------------
#    CONFIGURAÇÕES PARA USO DO PACOTE SEFAZ-PORTAL
#-----------------------------------------------------------------------------------------------------------------------

SISTEMA_VERSAO='0.1.0'
SISTEMA_CODIGO= ${APP_NAME}
SISTEMA_NOME=${APP_NAME}
SISTEMA_DESC='Sistema de Exemplo'
SISTEMA_URL= ${APP_URL}
SISTEMA_URL_BACKEND='http://api-cod_sistema_sistema-metro-des.com.br/v1/'
SISTEMA_TOKEN_KEY_BACKEND='portaltokendev'

MODULO_CODIGO= ''
MODULO_NOME=''
MODULO_URL= ''

AMBIENTE_SIGLA=${APP_ENV}
AMBIENTE_NOME='Ambiente Local'

CDN_CSS='http://cdn-des.sefaz.es.gov.br/layout/css/'
CDN_JS='http://cdn-des.sefaz.es.gov.br/layout/js/'
CDN_IMG='http://cdn-des.sefaz.es.gov.br/layout/img/'

EMAIL_BACKEND='test@test.com'
EMAIL_BACKEND_SERVIDOR='test@test.com'
EMAIL_FRONTEND='test@test.com'
EMAIL_PORTAL_API='test@test.com'

PORTAL_URL='http://desenvintranet.sefaz.es.gov.br/Portal/'
PORTAL_NOME_COOKIE='PORTAL_TOKEN_DEV'

PORTAL_API_URL='http://s2-intranet-des.sefaz.es.gov.br/api/portal/'
PORTAL_API_TOKEN_KEY=${SISTEMA_TOKEN_KEY_BACKEND}

```

Crie uma rota de exemplo dentro de `routes\web.php`
``` php
<?php

Route::get('/', ['as' => 'exemplo', 'middleware' => 'portal:COD_FUNCAO', 'uses' => 'ExemploController@index']);
```

Crie um controller de exemplo dentro de `app\Http\Controllers`
``` php
<?php

namespace App\Http\Controllers;

class ExemploController extends Controller
{
    public function index()
    {
        return view('exemplo');
    }
}
```

Crie uma view de exemplo dentro de `resources\views\exemplo.blade.php`
``` php
@extends('Portal::layout.default')
@section('content')
    Exemplo
@endsection
```

Inicie o servidor do php
``` bash
php -S 0.0.0.0:8000 -t public
```

Inicie o browser
``` bash
http://ESTACAO.net.sefaz.es.gov.br:8000
```
