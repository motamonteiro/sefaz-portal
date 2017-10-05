<div id="overlay-imprimir" onclick="cancelarImpressao()"></div>
<div id="printable">
    <div class="formulario">
        <div class="topForm">
            <div class="brasao"><img src="{{ config('sistema.cdn.img') }}brasao_sefaz_color.png" alt="Governo do Estado do Espírito Santo"></div>
            <div class="receita"><img src="{{ config('sistema.cdn.img') }}logo_receita_color.png" alt="Receita Estadual"></div>
        </div>
        <div class="topic" id="infoprint"></div>
    </div>
    <div id="campo-imprimir">
        <span class="btn btn-primary" onclick="cancelarImpressao()">Cancelar</span>
        <span class="btn btn-success" onclick="window.print()">Imprimir</span>
    </div>
</div>

<script>
    function imprimir(label, tipo){
        var printable = $('#printable'),
            outerHeight = 0;

        if(tipo === 'detalhe'){
            $('#showPrint > .content-box').each(function(){
                var marginTop = 1200 - outerHeight,
                    thisHeight = $(this).outerHeight();
                outerHeight = outerHeight + thisHeight;
                if(outerHeight > 900){
                    outerHeight = 0;
                    $(this).css('margin-top', marginTop);
                    printable.prepend('<div class="sect-page"></div>');
                }
            });
            $('#infoprint').html($(label).html());
        }else{
            $('#infoprint').html($(label).html());
            var lastTd = $('thead tr th:last-child').html();
            if(lastTd == ''){
                $('#infoprint thead tr th:last-child').remove();
                $('#infoprint tbody tr td:last-child').remove();
            }
        }

        printable.addClass('print').fadeIn();
        $('#overlay-imprimir').fadeIn('fast');
    }
    function cancelarImpressao(){
        $('#printable').removeClass('print').hide();
        $('#overlay-imprimir').hide();
    }
</script>