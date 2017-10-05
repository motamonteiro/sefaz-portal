@component('mail::message')
    # Sistema não consegue acessar url da api

    O servidor da api está indisponível.

    Url do sistema {{ $nmeSistema }}: {{ $urlSistema }}

    Url da Api: {{ $api->getBaseUrl() }}

    Token Key: {{ $api->getTokenKey() }}

    Token Value: {{ $api->getTokenValue() }}

    Rota da Api: {{ $api->getRota() }}

    Método da Api: {{ $api->getMetodo() }}

    @if(!empty($api->getDados()))
    Dados da rota:
    @foreach ($api->getDados() as $dado => $value)
    {{$dado . " : " . $value}}
    @endforeach
    @endif

    Aguardo retorno,
    Obrigado,
    {{ $nmeSistema }}
@endcomponent