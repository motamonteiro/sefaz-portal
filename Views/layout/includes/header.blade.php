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
        <style>
            .btn-logar-portal{
                margin-top: 12px; margin-right:10px; overflow: hidden; background-color: #344860; border-color: white; transition-duration: 0.4s; border-radius: 5px;
            }
            .btn-logar-portal:hover{
                background-color: white; /* Green */
                color: #344860;
            }
            @media (max-width: 991px) {
                .btn-logar-portal-minimo{
                    display: inline;
                }
            }
            @media (min-width: 992px) {
                .btn-logar-portal-minimo{
                    display: none;
                }
            }
            @media (max-width: 460px) {
                .btn-logar-portal-minimo{
                    display: none;
                }
            }
        </style>
        @php
            $uri = (request()->getRequestUri() != null && request()->getRequestUri()[0] == '/') ? substr(request()->getRequestUri(), 1) : request()->getRequestUri();
            $url = config('sistema.portal.url') . '?redirect=' . config('app.url') . '/' . $uri;
        @endphp
        ​
        <div class="pull-right hidden-xs hidden-sm">
            <a href="{{ $url }}"><button class="btn btn-primary btn-logar-portal"> <i class="fa fa-users"></i> &nbsp; Logar no Portal </button></a>
        </div>
        ​
        <div class="btn-logar-portal-minimo pull-right">
            <a href="{{ $url }}"><button class="btn btn-primary btn-logar-portal"> <i class="fa fa-users"></i></button></a>
        </div>
        ​
    @endif
</div>
