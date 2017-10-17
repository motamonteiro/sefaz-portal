<img src="{{ $portal->nmeIconeSistemaAtual }}" alt="" width="37" class="pull-left">
@if(config('sistema.modulo.codigo') != '')
    <p class="sigla-sistema">{{ $portal->codSistemaAtual .' - '.$portal->nmeSistemaAtual }}</p>
    <p class="nome-sistema">{{ $portal->nmeModuloAtual }}</p>
@else
    @if($portal->codModuloAtual)
        <p class="sigla-sistema">{{ $portal->codModuloAtual .' - '.$portal->nmeModuloAtual }}</p>
        <p class="nome-sistema">{{ $portal->nmeSistemaAtual }}</p>
    @else
        <p class="sigla-sistema">{{ $portal->nmeSistemaAtual .' - '.$portal->dscSistemaAtual }}</p>
    @endif
@endif
