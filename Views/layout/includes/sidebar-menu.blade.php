@foreach($menus as $menu)

    @if(!empty($menu['menus']))

        <li>
            <a href="#" class="sidebar-submenu {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu['menus'])) ? 'ativo' : '' }}" data-toggle="collapse" data-target="#menu-alterar-{{ $menu['idMenu'] }}">
                <i class="{{ !empty($menu['nmeEstiloMenu']) ? $menu['nmeEstiloMenu'] : 'fa fa-edit' }}"></i> {{$menu['nmeMenu']}}
            </a>
            <ul class="collapse submenu-itens {{ ($portal->existeCodMenu($portal->codMenuAtual, $menu['menus'])) ? 'in' : '' }}" id="menu-alterar-{{ $menu['idMenu'] }}">

                @include('Portal::layout.includes.sidebar-menu', ['urlmodulo'=> $urlModulo, 'menus' => $menu['menus']])

            </ul>
        </li>

    @else

        <li>
            <a href="{{ $urlModulo . $menu['nmeUrlMenu'] }}" {{ ($menu['codMenu'] == $portal->codMenuAtual) ? 'class=ativo' : '' }}>
                <i class="{{ !empty($menu['nmeEstiloMenu']) ? $menu['nmeEstiloMenu'] : 'fa fa-circle-o' }}"></i> {{ $menu['nmeMenu'] }}
            </a>
        </li>

    @endif

@endforeach
