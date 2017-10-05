<div id="sidebar-wrapper">

    <ul class="sidebar-nav">
        <li>
            <a href="/">
                <i class="fa fa-home"></i> Início
            </a>
        </li>
    </ul>
    @foreach($portal->usuarioLogado->sistemasPortal as $sistema)
        @if($sistema['codSistema'] == $portal->codSistemaAtual)
            @foreach($sistema['modulos'] as $modulo)
                <ul class="sidebar-nav">
                    <li>
                        <a href="#" class="sidebar-submenu {{ ($modulo['codModulo'] == $portal->codModuloAtual) ? 'ativo' : '' }}" data-toggle="collapse" data-target="#menu-alterar{{ $modulo['codModulo'] }}">
                            <i class="{{ !empty($modulo['menuRaiz']['nmeEstiloMenu']) ? $modulo['menuRaiz']['nmeEstiloMenu'] : 'fa fa-edit' }}"></i> {{ $modulo['nmeModulo'] }}
                        </a>
                        <ul class="collapse submenu-itens {{ ($modulo['codModulo'] == $portal->codModuloAtual) ? 'in' : '' }}" id="menu-alterar{{ $modulo['codModulo'] }}">

                            @foreach($modulo['menuRaiz']['menus'] as $menu)

                                @if(!empty($menu['menus']))

                                    <li>
                                        <a href="#" class="sidebar-submenu {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu['menus'])) ? 'ativo' : '' }}" data-toggle="collapse" data-target="#menu-alterar{{ $menu['codMenu'] }}2">
                                            <i class="{{ !empty($menu['nmeEstiloMenu']) ? $menu['nmeEstiloMenu'] : 'fa fa-edit' }}"></i> {{$menu['nmeMenu']}}
                                        </a>
                                        <ul class="collapse submenu-itens {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu['menus'])) ? 'in' : '' }}" id="menu-alterar{{ $menu['codMenu'] }}2">

                                            @foreach($menu['menus'] as $menu2)

                                                @if(!empty($menu2['menus']))

                                                    <li>
                                                        <a href="#" class="sidebar-submenu {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu2['menus'])) ? 'ativo' : '' }}" data-toggle="collapse" data-target="#menu-alterar{{ $menu2['codMenu'] }}3">
                                                            <i class="{{ !empty($menu2['nmeEstiloMenu']) ? $menu2['nmeEstiloMenu'] : 'fa fa-edit' }}"></i> {{$menu2['nmeMenu']}}
                                                        </a>
                                                        <ul class="collapse submenu-itens {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu2['menus'])) ? 'in' : '' }}" id="menu-alterar{{ $menu2['codMenu'] }}3">

                                                            @foreach($menu2['menus'] as $menu3)

                                                                @if(!empty($menu3['menus']))

                                                                    <li>
                                                                        <a href="#" class="sidebar-submenu {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu3['menus'])) ? 'ativo' : '' }}" data-toggle="collapse" data-target="#menu-alterar{{ $menu3['codMenu'] }}4">
                                                                            <i class="{{ !empty($menu3['nmeEstiloMenu']) ? $menu3['nmeEstiloMenu'] : 'fa fa-edit' }}"></i> {{$menu3['nmeMenu']}}
                                                                        </a>
                                                                        <ul class="collapse submenu-itens {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu3['menus'])) ? 'in' : '' }}" id="menu-alterar{{ $menu3['codMenu'] }}4">

                                                                            @foreach($menu3['menus'] as $menu4)

                                                                                @if(!empty($menu4['menus']))

                                                                                    <li>
                                                                                        <a href="#" class="sidebar-submenu {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu4['menus'])) ? 'ativo' : '' }}" data-toggle="collapse" data-target="#menu-alterar{{ $menu4['codMenu'] }}5">
                                                                                            <i class="{{ !empty($menu4['nmeEstiloMenu']) ? $menu4['nmeEstiloMenu'] : 'fa fa-edit' }}"></i> {{$menu4['nmeMenu']}}
                                                                                        </a>
                                                                                        <ul class="collapse submenu-itens {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu4['menus'])) ? 'in' : '' }}" id="menu-alterar{{ $menu4['codMenu'] }}5">

                                                                                            <li>
                                                                                                <a href="#">
                                                                                                    <i class="fa fa-circle-o"></i> -
                                                                                                </a>
                                                                                            </li>

                                                                                        </ul>
                                                                                    </li>

                                                                                @else

                                                                                    <li>
                                                                                        <a href="{{ '/'. strtolower($modulo['codModulo']) . $menu4['nmeUrlMenu'] }}" {{ ($menu4['codMenu'] == $portal->codMenuAtual) ? 'class=ativo' : '' }}>
                                                                                            <i class="{{ !empty($menu4['nmeEstiloMenu']) ? $menu4['nmeEstiloMenu'] : 'fa fa-circle-o' }}"></i> {{ $menu4['nmeMenu'] }}
                                                                                        </a>
                                                                                    </li>

                                                                                @endif

                                                                            @endforeach
                                                                        </ul>
                                                                    </li>

                                                                @else

                                                                    <li>
                                                                        <a href="{{ '/'. strtolower($modulo['codModulo']) . $menu3['nmeUrlMenu'] }}" {{ ($menu3['codMenu'] == $portal->codMenuAtual) ? 'class=ativo' : '' }}>
                                                                            <i class="{{ !empty($menu3['nmeEstiloMenu']) ? $menu3['nmeEstiloMenu'] : 'fa fa-circle-o' }}"></i> {{ $menu3['nmeMenu'] }}
                                                                        </a>
                                                                    </li>

                                                                @endif

                                                            @endforeach
                                                        </ul>
                                                    </li>

                                                @else

                                                    <li>
                                                        <a href="{{ '/'. strtolower($modulo['codModulo']) . $menu2['nmeUrlMenu'] }}" {{ ($menu2['codMenu'] == $portal->codMenuAtual) ? 'class=ativo' : '' }}>
                                                            <i class="{{ !empty($menu2['nmeEstiloMenu']) ? $menu2['nmeEstiloMenu'] : 'fa fa-circle-o' }}"></i> {{ $menu2['nmeMenu'] }}
                                                        </a>
                                                    </li>

                                                @endif

                                            @endforeach
                                        </ul>
                                    </li>

                                @else

                                    <li>
                                        <a href="{{ '/'. strtolower($modulo['codModulo']) . $menu['nmeUrlMenu'] }}" {{ ($menu['codMenu'] == $portal->codMenuAtual) ? 'class=ativo' : '' }}>
                                            <i class="{{ !empty($menu['nmeEstiloMenu']) ? $menu['nmeEstiloMenu'] : 'fa fa-circle-o' }}"></i> {{ $menu['nmeMenu'] }}
                                        </a>
                                    </li>

                                @endif

                            @endforeach

                        </ul>
                    </li>
                </ul>
                {{--@endif--}}
            @endforeach
        @endif
    @endforeach
</div>