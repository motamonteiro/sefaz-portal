<div id="sidebar-funcoes">
    <div class="busca-funcao-menu">
        <input type="text" id="buscaMenuLateral" placeholder="Buscar função">
        <p>Você pode buscar por código, nome da função ou nome do módulo.</p>
    </div>
    <p class="msg-busca" style="display: none; padding: 0 20px;">Nenhuma função encontrada!</p>

    <ul id="menu-funcoes">
        @foreach($portal->usuarioLogado->sistemasPortal as $sistema)
            @foreach($sistema['modulos'] as $modulo)
                @foreach($modulo['menuRaiz']['menus'] as $menu)
                    @if(!empty($menu['menus']))
                        @foreach($menu['menus'] as $menu2)
                            @if(!empty($menu2['menus']))
                                @foreach($menu2['menus'] as $menu3)
                                    @if(!empty($menu3['menus']))
                                        @foreach($menu3['menus'] as $menu4)
                                            <li class="{{ strtolower($modulo['codModulo']) }}">
                                                <a href="{{ $modulo['nmeUrlModulo'].$menu4['nmeUrlMenu'] }}">
                                                    <p class="codigo-funcao-menu">
                                                        <span>{{ $menu4['codMenu'] }}</span></p>
                                                    <p class="nome-modulo-menu">{{ $sistema['nmeSistema'] }} - {{ $modulo['nmeModulo'] }}</p>
                                                    <p class="nome-funcao-menu">{{ $menu4['nmeMenu'] }}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="{{ strtolower($modulo['codModulo']) }}">
                                            <a href="{{ $modulo['nmeUrlModulo'].$menu3['nmeUrlMenu'] }}">
                                                <p class="codigo-funcao-menu"><span>{{ $menu3['codMenu'] }}</span></p>
                                                <p class="nome-modulo-menu">{{ $sistema['nmeSistema'] }} - {{ $modulo['nmeModulo'] }}</p>
                                                <p class="nome-funcao-menu">{{ $menu3['nmeMenu'] }}</p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            @else
                                <li class="{{ strtolower($modulo['codModulo']) }}">
                                    <a href="{{ $modulo['nmeUrlModulo'].$menu2['nmeUrlMenu'] }}">
                                        <p class="codigo-funcao-menu"><span>{{ $menu2['codMenu'] }}</span></p>
                                        <p class="nome-modulo-menu">{{ $sistema['nmeSistema'] }} - {{ $modulo['nmeModulo'] }}</p>
                                        <p class="nome-funcao-menu">{{ $menu2['nmeMenu'] }}</p>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @else
                        <li class="{{ strtolower($modulo['codModulo']) }}">
                            <a href="{{ $modulo['nmeUrlModulo'].$menu['nmeUrlMenu'] }}">
                                <p class="codigo-funcao-menu"><span>{{ $menu['codMenu'] }}</span></p>
                                <p class="nome-modulo-menu">{{ $sistema['nmeSistema'] }} - {{ $modulo['nmeModulo'] }}</p>
                                <p class="nome-funcao-menu">{{ $menu['nmeMenu'] }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            @endforeach
        @endforeach
    </ul>
</div>
