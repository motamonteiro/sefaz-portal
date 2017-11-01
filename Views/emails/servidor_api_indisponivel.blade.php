@component('mail::message')
    # Sistema não consegue acessar url da api

    O servidor da api está indisponível.

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

    Aguardo retorno,
    Obrigado,
    {{ $nmeSistema }}
@endcomponent