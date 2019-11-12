<div id="top-bar">
    <div class="logotipo-sefaz pull-left">
        <p class="logo pull-left sistema-portal-url"><strong>Portal</strong> de <span>Sistemas</span></p>
        <a href="#menu-toggle" id="menu-toggle" class="pull-right"><i class="fa fa-bars"></i></a>
    </div>
    <div class="nome-sistema-barra pull-left">
        @include('Portal::layout.includes.header-barra-sistema')
    </div>
    @if($portal->usuarioLogado->numCpf != '')
        <div class="dados-usuario-barra pull-right hidden-xs">
            <span class="img-usuario" id="id-usuario">{{ substr($portal->usuarioLogado->nmeUsuario, 0 ,1) }}</span>
        </div>
        <div id="detalhamento-usuario-barra">
            <div class="informacoes-usuario">
                <span class="img-usuario">{{ substr($portal->usuarioLogado->nmeUsuario, 0 ,1) }}</span>
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
    @else
        <div class="dados-usuario-barra pull-right">
            @php
                $uri = (request()->getRequestUri() != null && request()->getRequestUri()[0] == '/') ? substr(request()->getRequestUri(), 1) : request()->getRequestUri();
                $url = config('sistema.portal.url') . '?redirect=' . config('app.url') . '/' . $uri;
            @endphp
            <a href="{{ $url }}"><button class="btn btn-primary btn-cidadao login-social">Entrar</button></a>
        </div>
    @endif
</div>
