@component('mail::message')
    # Sistema não consegue acessar a resposta da api

    O sistema espera um json mas a Api respondeu incorretamente.

    Url do sistema {{ $nmeSistema }}: {{ $urlSistema }}

    Url da Api: {{ $api->getBaseUrl() . $api->getRota() }}

    Método da Api: {{ $api->getMetodo() }}

    Token Key: {{ $api->getTokenKey() }}

    Token Value: {{ $api->getTokenValue() }}

    @if(!empty($api->getDados()))
    Dados da rota:
    @foreach ($api->getDados() as $dado => $value)
    @if($dado == 'nmeSenha' || $dado == 'nme_senha' || $dado == 'password')
    {{$dado . " : *****"}}
    @else
    {{$dado . " : " . $value}}
    @endif
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