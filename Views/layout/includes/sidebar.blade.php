
<div id="sidebar-wrapper">
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

    @if($portal->usuarioLogado->numCpf == '')
        @php
            if (config('sistema.ambiente.sigla') == 'local') {
                if (config('sistema.modulo.url') != '') {
                    $urlModulo = config('sistema.modulo.url');
                } else {
                    $urlModulo =  '/'. strtolower($portal->codModuloAtual);
                }
            } else {
                $urlModulo = strtolower($portal->nmeUrlModuloAtual);
            }
        @endphp

        <ul class="sidebar-nav">
            <li>
                <a href="#" class="sidebar-submenu ativo" data-toggle="collapse" data-target="#menu-alterar{{ $portal->codModuloAtual }}">
                    <i class="{{ !empty($portal->menuPublico['menuRaiz']['nmeEstiloMenu']) ? $portal->menuPublico['menuRaiz']['nmeEstiloMenu'] : 'fa fa-edit' }}"></i> {{ $portal->nmeModuloAtual }}
                </a>
                <ul class="collapse submenu-itens in" id="menu-alterar{{ $portal->codModuloAtual }}">

                    @include('Portal::layout.includes.sidebar-menu', ['urlmodulo'=> $urlModulo, 'menus' => $portal->menuPublico['menuRaiz']['menus']])

                </ul>
            </li>
        </ul>

        <button class="btn-logar-portal-menu" style="margin-left:8px; white-space: nowrap;" onclick="clicarLogarMenuLateral()"> <i class="fa fa-users"></i><span style="margin-left:15px;"> Logar no Portal </span> </button>

        <script>
            function clicarLogarMenuLateral(){
                let href = $('#btnLogarPortalTrigger').attr('href');
                window.location = href;
            }
        </script>

    @endif
</div>
