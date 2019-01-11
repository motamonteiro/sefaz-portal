<div id="top-bar">
    <div class="logotipo-sefaz pull-left">
        <p class="logo pull-left sistema-portal-url"><strong>Portal</strong> de <span>Sistemas</span></p>
        <a href="#menu-toggle" id="menu-toggle" class="pull-right"><i class="fa fa-bars"></i></a>
    </div>
    <div class="nome-sistema-barra pull-left">
        @include('Portal::layout.includes.header-barra-sistema')
    </div>
    <div class="dados-usuario-barra pull-right hidden-xs">
        <span class="img-usuario" id="id-usuario">{{ substr($portal->usuarioLogado->nmeUsuario, 0 ,1) }}</span>
        {{--<img src="{{config('sistema.portal.url')}}Avatar.ashx?token={{$portal->usuarioLogado->tokenValue}}" class="foto-usuario" id="id-usuario" alt="Nome do Usuário">--}}
    </div>
    <div id="detalhamento-usuario-barra">
        <div class="informacoes-usuario">
            <span class="img-usuario">{{ substr($portal->usuarioLogado->nmeUsuario, 0 ,1) }}</span>
            {{--<img src="{{config('sistema.portal.url')}}Avatar.ashx?token={{$portal->usuarioLogado->tokenValue}}" class="foto-usuario" alt="Nome do Usuário">--}}
            <p class="nome-usuario">{{ $portal->usuarioLogado->nmeUsuario }}</p>
            <p class="email-usuario">{{ $portal->usuarioLogado->nmeEmail }}</p>
            <p>{{ $portal->usuarioLogado->nmeOrgao }}</p>
        </div>
        <div class="barra-botoes">
            <a target="_blank" href="https://css.sefaz.es.gov.br" class="btn btn-default btn-sm">Reportar Problema</a>
            <a href="{{ route('logout') }}" class="btn btn-default btn-sm pull-right">Sair</a>
        </div>
    </div>
    <div class="notificacoes pull-right hidden-sm hidden-xs">
        <ul>
            <li><a href="#" id="menu-funcoes-sit"><i class="fa fa-search"></i></a></li>
        </ul>
    </div>
</div>
