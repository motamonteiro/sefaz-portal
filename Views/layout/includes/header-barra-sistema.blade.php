<img src="{{ $portal->nmeIconeSistemaAtual }}" alt="" width="37" class="pull-left">
@if($portal->codModuloAtual)
    <p class="sigla-sistema">{{ $portal->codModuloAtual .' - '.$portal->nmeModuloAtual }}</p>
    <p class="nome-sistema">{{ $portal->nmeSistemaAtual }}</p>
@else
    <p class="sigla-sistema">{{ $portal->nmeSistemaAtual .' - '.$portal->dscSistemaAtual }}</p>
@endif