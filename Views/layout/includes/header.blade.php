<div id="top-bar">
    <div class="logotipo-sefaz pull-left">
        <p class="logo pull-left"><strong>Portal</strong> de <span>Sistemas</span></p>
        <a href="#menu-toggle" id="menu-toggle" class="pull-right"><i class="fa fa-bars"></i></a>
    </div>
    <div class="nome-sistema-barra pull-left">
        @include('Portal::layout.includes.header-barra-sistema')
    </div>
    <div class="dados-usuario-barra pull-right hidden-xs">
        <img src="{{config('sistema.portal.url')}}Avatar.ashx?token={{$_COOKIE[config('sistema.portal.nome_cookie')]}}" class="foto-usuario" id="id-usuario" alt="Nome do Usuário">
    </div>
    <div id="detalhamento-usuario-barra">
        <div class="informacoes-usuario">
            <!--<span class="img-usuario">W</span>-->
            <img src="{{config('sistema.portal.url')}}Avatar.ashx?token={{$_COOKIE[config('sistema.portal.nome_cookie')]}}" class="foto-usuario" alt="Nome do Usuário">
            <p class="nome-usuario">{{ $portal->usuarioLogado->nmeUsuario }}</p>
            <p class="email-usuario">{{ $portal->usuarioLogado->nmeEmail }}</p>
            <p>{{ $portal->usuarioLogado->nmeOrgao }}</p>
        </div>
        <div class="barra-botoes">
            <a href="{{ config('sistema.portal.url') }}../Identificacao/" class="btn btn-default btn-sm">Minha conta</a>
            <a href="{{ route('logout') }}" class="btn btn-default btn-sm pull-right">Sair</a>
        </div>
    </div>
    <div class="notificacoes pull-right hidden-sm hidden-xs">
        <ul>
            <li><a href="#" id="menu-funcoes-sit"><i class="fa fa-search"></i></a></li>
        </ul>
    </div>
</div>
