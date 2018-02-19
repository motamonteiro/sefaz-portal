<div id="sidebar-wrapper">
    @if(config('sistema.modulo.codigo') == '')
    <ul class="sidebar-nav">
        <li>
            <a href="/">
                <i class="fa fa-home"></i> Início
            </a>
        </li>
    </ul>
    @endif
    @foreach($portal->usuarioLogado->sistemasPortal as $sistema)
        @if($sistema['codSistema'] == $portal->codSistemaAtual)
            @foreach($sistema['modulos'] as $modulo)

                @php
                    if (config('sistema.ambiente.sigla') == 'local') {
                        if (config('sistema.modulo.url') != '') {
                            $urlModulo = config('sistema.modulo.url');
                        } else {
                            $urlModulo =  '/'. strtolower($modulo['codModulo']);
                        }
                    } else {
                        $urlModulo = strtolower($modulo['nmeUrlModulo']);
                    }
                @endphp

                @if(config('sistema.modulo.codigo') == '' || (config('sistema.modulo.codigo') != '' && config('sistema.modulo.codigo') == $modulo['codModulo']))

                    <ul class="sidebar-nav">
                        <li>
                            <a href="#" class="sidebar-submenu {{ ($modulo['codModulo'] == $portal->codModuloAtual) ? 'ativo' : '' }}" data-toggle="collapse" data-target="#menu-alterar{{ $modulo['codModulo'] }}">
                                <i class="{{ !empty($modulo['menuRaiz']['nmeEstiloMenu']) ? $modulo['menuRaiz']['nmeEstiloMenu'] : 'fa fa-edit' }}"></i> {{ $modulo['nmeModulo'] }}
                            </a>
                            <ul class="collapse submenu-itens {{ ($modulo['codModulo'] == $portal->codModuloAtual) ? 'in' : '' }}" id="menu-alterar{{ $modulo['codModulo'] }}">

                                @include('Portal::layout.includes.sidebar-menu', ['urlmodulo'=> $urlModulo, 'menus' => $modulo['menuRaiz']['menus']])

                            </ul>
                        </li>
                    </ul>
                @endif
            @endforeach
        @endif
    @endforeach
</div>