<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    @include('Portal::layout.includes.head')
</head>
<body onload="AtualizarJs()">
@include('Portal::layout.includes.header')
<div class="modal fade" id="myModalFull" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog full-modal" role="document">
        <div id="conteudo-modal-full"></div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div id="conteudo-modal"></div>
    </div>
</div>
<div id="wrapper" class="">
    @include('Portal::layout.includes.sidebar')
    @include('Portal::layout.includes.searchbar')
    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12" id="content">
                    <h1 class="dados-modulo">
                        @if($portal->codMenuAtual)
                            <span class="codigo-funcao ">{{ $portal->codMenuAtual }}</span>
                            <span class="nome-funcao">{{ $portal->nmeMenuAtual }}</span>
                        @endif
                        @if(config('sistema.ambiente.sigla') != 'producao')
                            <span class="ambiente">{{config('sistema.ambiente.nome')}}</span>
                        @endif
                    </h1>
                    <div class="row">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Portal::layout.includes.print')
@include('Portal::layout.includes.footer')
@include('Portal::layout.includes.scripts')

<script>
    @if(config('sistema.ambiente.sigla') == 'local' || config('sistema.ambiente.sigla') == 'desenvolvimento' || config('sistema.ambiente.sigla') == 'homologacao')
    $('.ambiente').css('cursor', 'pointer');
    $('.ambiente').click(function () {
        swal({
            title: 'Token de Sessão ', html: '{{$portal->usuarioLogado->tokenValue}}', type: 'info',
            confirmButtonText: 'OK'
        });
    });
    @endif

    @if(config('sistema.portal.url') != '')
    $(document).ready(function(){
        $("p[class*='sistema-portal-url']").css('cursor', 'pointer');
        $("p[class*='sistema-portal-url']").click(function () {
            window.location.href = "{{config('sistema.portal.url')}}";
        });
    });
    @endif
</script>

</body>
</html>
