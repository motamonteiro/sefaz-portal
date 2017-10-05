@component('mail::message')
    # Sistema não consegue acessar a resposta da api

    O sistema espera um json mas a Api respondeu incorretamente.

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

    StatusCode: {{ $respostaInvalida['status_code'] }}

    Mensagem de erro: {{ $respostaInvalida['messages'][0] }}

    Erro:
    {!! $respostaInvalida['messages'][1] !!}

    Aguardo retorno,
    Obrigado,
    {{ $nmeSistema }}
@endcomponent