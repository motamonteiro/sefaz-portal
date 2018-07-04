try{$(document);}catch(err){alert("Favor incluir uma library do jquery");}

/*
 -----------------------------------------------------------------------------
 Nome da Função: ajaxConsulta(nomeForm, pag_ajax)
 Objetivo: Funcao que chama uma pagina PHP e ao concluir chama a funcao
 "ajaxResultado(resposta)" que está em javascript
 Parâmetros: nomeForm    -> id do form
 pag_ajax    -> url da pagina php que irá ser chamada "/nomedapasta/ajax.php"
 Chamada:
 <form method="post" name="formulario" id="formulario" action="javascript: ajaxConsulta('formulario', 'teste_ajax.php');" >
 -----------------------------------------------------------------------------
 */
esconde_aguarde = 0; //variavel global para esconder o aguarde no ajaxResultado(resposta)
funcao_fim = null;
confirmFunction = null;
refuseFunction = null;
/**
 *
 * @param {type} verbo
 * @param {type} nomeForm
 * @param {type} pag_ajax
 * @param {type} flg_aguarde = Caso seja 1 executa o aguarde, caso contrario espera-se uma função por parametro.
 *               A função é executado ao fim do ajaxResultado
 * @returns {undefined}
 */
function ajaxConsulta(verbo, pag_ajax, nomeForm, flg_aguarde) {
    if (flg_aguarde === 1) {
        formataMsgPopup("aguarde");
        esconde_aguarde = 1;
    }else if(flg_aguarde){
        funcao_fim = flg_aguarde;
    }
    var url = pag_ajax;
    var parametro = '';
    if(nomeForm != ''){
        parametro = $("#" + nomeForm).serialize();
    }
    $.ajax({
        type: verbo,
        url: url,
        data: parametro,
        success: ajaxResultado,
        error: erroInterno
    });
}

function ajaxConsultaComAnexo(verbo, pag_ajax, parametro, flg_aguarde) {
    if (flg_aguarde === 1) {
        formataMsgPopup("aguarde");
        esconde_aguarde = 1;
    }else if(flg_aguarde){
        funcao_fim = flg_aguarde;
    }
    var url = pag_ajax;

    $.ajax({
        type: verbo,
        url: url,
        data: parametro,
        cache: false,
        contentType: false,
        processData: false,
        success: ajaxResultado,
        error: erroInterno
    });
}

/*
-----------------------------------------------------------------------------
Nome da Função: erroInterno()
Objetivo: Informar um erro interno de sistema.
  -----------------------------------------------------------------------------
*/
function erroInterno(){
    fecharMsgPopup();
    swal({title: 'Erro', html: 'Ocorreu um erro interno. Tente recarregar a página.', type: 'error', confirmButtonText: 'Ok'});
}

/*
-----------------------------------------------------------------------------
Nome da Função: erroFirewall()
Objetivo: Informar um erro interno de sistema.
  -----------------------------------------------------------------------------
*/
function erroFirewall(supportId){
    fecharMsgPopup();
    swal({title: 'Bloqueio no Firewall', html: 'O Firewall da SEFAZ está bloqueando algum recurso desse sistema. Favor abrir um chamado no CSS (Central de Solicitações da SEFAZ) com a informação abaixo:<br><strong>Bloqueio de firewall: '+supportId+'</strong>', type: 'error', confirmButtonText: 'Ok'});
}

/*
 -----------------------------------------------------------------------------
 Nome da Função: ajaxResultado(resposta)
 Objetivo: Preparar as respostas que vem das funcoes "ajaxConsulta(nomeForm)"
 Parâmetros: resposta -> array no formato json
 -----------------------------------------------------------------------------
 */
function ajaxResultado(resposta) {

    if (esconde_aguarde === 1) {
        esconde_aguarde = 0;
        fecharMsgPopup();
    }

    var resp;
    try {resp = jQuery.parseJSON(resposta);} catch (err) { resp = "null";}

    if (resp !== "null") {
        array_valor = resp.array_valor;
        array_msg_popup = resp.array_msg_popup;
        array_msg_campo = resp.array_msg_campo;
        array_parametro_popup = resp.array_parametro_popup;

        //Limpa valores com class='msg' das mensagens do formulario ja exibidas
        $(document).find(".msg").html("");

        if (typeof array_valor === 'object') {
            //Se é um objeto mas o array está vazio nao precisa chamar o MostraResultado(vlr) na page
            if (array_valor != ""){
                mostraResultado(array_valor);
            }
        } else {
            alert("erro ao receber json da pagina ajax no array_valor");
        }

        if (typeof array_msg_popup === 'object') {

            if(array_msg_popup["msg_array"].length >0){
                //exibeMsgPopup(array_msg_popup);
                exibeMsgSweetAlert(array_msg_popup, array_parametro_popup);
            }
        } else {
            alert("erro ao receber json da pagina ajax no array_msg_popup");
        }

        if (typeof array_msg_campo === 'object') {
            exibeMsgCampo(array_msg_campo);
        } else {
            alert("erro ao receber json da pagina ajax no array_msg_campo");
        }
    } else {
        //Se cair na página de redirecionsar portal, busca a tag <url> para redirecionar.
        if (resposta.indexOf("<title>RedirecionarPortal</title>") >= 0){
            var match, result = "";
            var regex = /<url>(.*?)<\/url>/ig;
            while (match = regex.exec(resposta)) { result += match[1]; }
            return window.location = result;
        }
		if (resposta.indexOf("<title>Request Rejected</title>") >= 0){
            var match, result = "";
            var regex = /Your support ID is: (.*?)<br>/ig;
            while (match = regex.exec(resposta)) { result += match[1]; }
            erroFirewall(result);
        }else{
			erroInterno();
		}

		//erroInterno();
        //alert("ERRO:"+resposta);
        //alert("ERRO: Recarregue a página.");
    }

    if(funcao_fim != null){
        funcao_fim.call();
        funcao_fim = null;
    }
}

/*
 -----------------------------------------------------------------------------
 Nome da Função: exibeMsgCampo(array_msg_campo)
 Objetivo: Exibir mensagens padrão do sistema
 Parâmetros:
 array_msg_popup -> array("cod_tipo_msg" => 0/1/2, "msg_array" => array["teste1","teste2"]);
 Chamada: exibeMensagem(array_msg_popup);
 -----------------------------------------------------------------------------
 */
function exibeMsgCampo(array_msg_campo) {

    for (var i=0;i<array_msg_campo.length;i++){
        campo = array_msg_campo[i]["campo"];
        msg   = array_msg_campo[i]["msg"];
        cod_tipo_msg = array_msg_campo[i]["cod_tipo_msg"];
        //Trata a mensagem de acordo com o tipo
        switch (cod_tipo_msg) {
            case 0:
                if($("#" + campo).is("input")){
                    //Se for um input só pinto o campo com msgErro de vermelho
                    //$("#" + campo).css("background","RGBA(245,0,0,0.2)");
                    $("#" + campo).effect("highlight", {}, 7000);
                }else if($("#" + campo).is("img")){
                    //Se for uma imagem temos que tratar...
                }else{
                    //Se o campo tiver um innerHtml coloca a msg e muda a cor do texto para vermelho
                    $("#" + campo).html(msg);
                    $("#" + campo).css("color","red");
                }
                break;
            case 1:
                if($("#" + campo).is("input")){
                    //Se for um input só pinto o campo com msgSucesso de verde
                    $("#" + campo).css("background","#90ee90");
                }else if($("#" + campo).is("img")){
                    //Se for uma imagem temos que tratar...
                }else{
                    //Se o campo tiver um innerHtml coloca a msg e muda a cor do texto para verde
                    $("#" + campo).html(msg);
                    $("#" + campo).css("color","green");
                }
                break;
            case 2:

                if($("#" + campo).attr('disputa')){
                    exibeCanvasDisputa(msg);
                }else if($("#" + campo).is('select')){
                    $("#" + campo).val(msg);
                }else if($("#" + campo).is('input')){
                    $("#" + campo).val(msg);
                }else if($("#" + campo).is('textarea')){
                    $("#" + campo).val(msg);
                }else if($("#" + campo).is("img")){
                    $("#" + campo).attr('src',msg);
                }else if($('#' + campo).is('table')){
                    var dados = JSON.parse(msg);
                    var tabela = $('#' + campo);
                    tabela.DataTable().destroy();
                    tabela.DataTable({data: dados});
                }else{
                    $("#" + campo).html(msg);
                }
                break;

            case 3:
                if(msg === "hide"){
                    $("#" + campo).hide();
                }else if(msg === "funcao"){
                    window[campo].call();
                    //campo;
                }else if(msg === "show"){
                    $("#" + campo).show();
                }else if(msg === "desabilitar"){
                    $("#" + campo).prop('disabled', 'disabled');
                }else if(msg === "leitura"){
                    $("#" + campo).prop('readonly', true);
                }else if(msg === "habilitar"){
                    $("#" + campo).removeAttr("disabled");
                }else if(msg === "remove"){
                    $("#" + campo).remove();
                }else if(msg === "clique"){
                    $("#" + campo).click();
                }else if(msg === "check"){
                    $("#" + campo).prop('checked', true);
                }else if(msg === "uncheck"){
                    $("#" + campo).prop('checked', false);
                }else if(msg === "redirecionar"){
                    window.location = campo;
                }else if(msg === "destacar"){
                    $("#" + campo).effect("highlight", {}, 3000);
                }else if(msg === "editorTexto"){
                    if(CKEDITOR.instances[campo]){
                        CKEDITOR.instances[campo].destroy(true);
                    }
                    CKEDITOR.replace(campo);
                }else if(msg === "placeHolder"){
                    $('input[placeholder], textarea[placeholder]').placeholder();
                }else if(msg === "atualizarJs"){

                    AtualizarJs();

                }else if(msg === "comboBox"){
                    // var config = {'.chosen-select': {},'.chosen-select': {no_results_text: 'Texto não encontrado!'}}
                    // for (var selector in config) {
                    //     $(selector).chosen(config[selector]);
                    // }
                    $('.select2').select2({theme:"bootstrap",placeholder:"Selecione", allowClear: true});
                }else if(msg === "shadowbox"){
                    Shadowbox.init({ handleOversize: 'drag', modal: true, displayCounter:true, displayNav:true, enableKeys:true });Shadowbox.clearCache();Shadowbox.setup('');
                }else{
                    msg = msg.split("::");
                    if(msg[0] === "largura"){
                        $("#" + campo).css("width",msg[1]);
                    }else if(msg[0] === "redirecionar"){
                        setTimeout(function() {window.location.href = campo;}, msg[1]);
                    }else if(msg[0] === "title"){
                        $("#" + campo).attr("title",msg[1]);
                    }else if(msg[0] === "colspan"){
                        $("#" + campo).attr("colspan",msg[1]);
                    }else{
                        $("#" + campo).css(msg[0],msg[1]);
                    }
                }
                break;

            case 4:

                if($("#" + campo).attr('disputa')){
                    exibeCanvasDisputa(msg);
                }else if($("#" + campo).is('select')){
                    $("#" + campo).val(msg);
                }else if($("#" + campo).is('input')){
                    $("#" + campo).val(msg);
                }else if($("#" + campo).is('textarea')){
                    $("#" + campo).val(msg);
                }else if($("#" + campo).is("img")){
                    $("#" + campo).attr('src',msg);
                }else{
                    $("#" + campo).html(msg);
                }
                break;
        }
    }
}

/*
 -----------------------------------------------------------------------------
 Nome da Função: exibeMensagem(array_msg_popup)
 Objetivo: Exibir mensagens padrão do sistema
 Parâmetros:
 array_msg_popup -> array("cod_tipo_msg" => 0, "msg_array" => array["teste1","teste2"]);
 Chamada: exibeMensagem(array_msg_popup);
 -----------------------------------------------------------------------------
 */
function exibeMsgPopup(array_msg_popup) {

    if(array_msg_popup["cod_tipo_msg"] == "1"){
        tipo_mensagem = "sucesso";
        funcaoConfirmar = array_msg_popup["nme_funcao_confirmar_msg_popup"];
        if(funcaoConfirmar == ""){ funcaoConfirmar = "fecharMsgPopup"; }
        confirmFunction = function(){if(funcaoConfirmar)fecharMsgPopup(); window[funcaoConfirmar].call(); confirmFunction = null; refuseFunction = null;};
    } else if (array_msg_popup["cod_tipo_msg"] == "0") {
        tipo_mensagem = "erro";
        funcaoConfirmar = array_msg_popup["nme_funcao_confirmar_msg_popup"];
        if(funcaoConfirmar == ""){ funcaoConfirmar = "fecharMsgPopup"; }
        confirmFunction = function(){if(funcaoConfirmar)fecharMsgPopup(); window[funcaoConfirmar].call(); confirmFunction = null; refuseFunction = null;};
    } else if (array_msg_popup["cod_tipo_msg"] == "2") {
        tipo_mensagem = "confirmacao";
        funcaoConfirmar = array_msg_popup["nme_funcao_confirmar_msg_popup"];
        funcaoNegar = array_msg_popup["nme_funcao_negar_msg_popup"];
        if(funcaoNegar == ""){ funcaoNegar = "fecharMsgPopup"; }
        confirmFunction = function(){if(funcaoConfirmar)fecharMsgPopup(); window[funcaoConfirmar].call(); confirmFunction = null; refuseFunction = null; };
        refuseFunction = function(){if(funcaoNegar)fecharMsgPopup(); window[funcaoNegar].call(); confirmFunction = null; refuseFunction = null; };
    } else if (array_msg_popup["cod_tipo_msg"] == "4") {
        tipo_mensagem = "aviso";
        funcaoConfirmar = array_msg_popup["nme_funcao_confirmar_msg_popup"];
        if(funcaoConfirmar == ""){ funcaoConfirmar = "fecharMsgPopup"; }
        confirmFunction = function(){if(funcaoConfirmar)fecharMsgPopup(); window[funcaoConfirmar].call(); confirmFunction = null; refuseFunction = null;};
    }


    mensagem ="";
    for (var i=0;i<array_msg_popup["msg_array"].length;i++){
        mensagem += array_msg_popup["msg_array"][i]+"<br>";
    }

    //alert(tipo_mensagem + ": "+mensagem);
    formataMsgPopup(tipo_mensagem, mensagem);

}

function exibeMsgSweetAlert(array_msg_popup, array_parametro_popup) {

    title = '';
    confirmButtonText = 'Ok';
    cancelButtonText = 'Cancelar';
    //Verifica se existem parametros para o sweetAlert
    if(!jQuery.isEmptyObject(array_parametro_popup)){
        if(array_parametro_popup["titulo"] != "") title = array_parametro_popup["titulo"];
        if(array_parametro_popup["textoBotaoConfirma"] != "") confirmButtonText = array_parametro_popup["textoBotaoConfirma"];
        if(array_parametro_popup["textoBotaoCancela"] != "") cancelButtonText = array_parametro_popup["textoBotaoCancela"];
    }

    mensagem ="";
    for (var i=0;i<array_msg_popup["msg_array"].length;i++){
        mensagem += array_msg_popup["msg_array"][i]+"<br>";
    }

    funcaoConfirmar = array_msg_popup["nme_funcao_confirmar_msg_popup"];
    funcaoNegar = array_msg_popup["nme_funcao_negar_msg_popup"];
    confirmFunction = function(){if(funcaoConfirmar)fecharMsgPopup(); window[funcaoConfirmar].call(); confirmFunction = null; refuseFunction = null; };
    refuseFunction = function(){if(funcaoNegar)fecharMsgPopup(); window[funcaoNegar].call(); confirmFunction = null; refuseFunction = null; };

    //cod_tipo_msg:
    //0 - erro
    //1 - sucesso
    //2 - confirmacao
    //3 - alerta

    if(array_msg_popup["cod_tipo_msg"] == "1"){
        type = "success";
    } else if (array_msg_popup["cod_tipo_msg"] == "0") {
        type = "error";
    } else if (array_msg_popup["cod_tipo_msg"] == "2") {
        type = "question";
    } else if (array_msg_popup["cod_tipo_msg"] == "3") {
        type = "warning";
    }

    if(array_msg_popup["cod_tipo_msg"] != "2"){
        if(funcaoConfirmar == ""){
            swal({title: title, html: mensagem, type: type, confirmButtonText: confirmButtonText});
        }else{
            swal({title: title, html: mensagem, type: type, confirmButtonText: confirmButtonText}).then(function () { confirmFunction.call(); });
        }
    }else{
        swal({
            title: title, html: mensagem, type: type,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText
        }).then(function () {
            confirmFunction.call();
        }, function (dismiss) {
            refuseFunction.call();
        })
    }

}

function formataMsgPopup(tipo_mensagem, mensagem, w, h) {
    fecharMsgPopup(); //Fechar popup para garantir que só um popup seja mostrado. 20/05/2014 - José Guilherme

    //html da mensagem
    var html =  "<div id='loading-page'>";
    html += "<p><span><img src='http://cdn.sefaz.es.gov.br/layout/img/loading.gif' alt=''></span></p>";
    html +=  "</div>";

    //Coloca o html no final do body
    if ($("#mensagem_fundo").length === 0) {
        $(html).prependTo('body');
    }

    if(tipo_mensagem === "aguarde"){
        $('#loading-page').show();
    } else {
        $('#loading-page').remove();
    }
}

function fecharMsgPopup(){

    $(document).unbind('scroll');
    $('body').css({'overflow':'visible'});
    $("#loading-page").remove();
    //$("#mensagem_aguarde").remove();

}

function mensagemAguardeAnimacao(){
    var retorno = '<div id="floatingCirclesG">';
    /*
     retorno += '<div class="f_circleG" id="frotateG_01">';
     retorno += '</div>';
     retorno += '<div class="f_circleG" id="frotateG_02">';
     retorno += '</div>';
     retorno += '<div class="f_circleG" id="frotateG_03">';
     retorno += '</div>';
     retorno += '<div class="f_circleG" id="frotateG_04">';
     retorno += '</div>';
     retorno += '<div class="f_circleG" id="frotateG_05">';
     retorno += '</div>';
     retorno += '<div class="f_circleG" id="frotateG_06">';
     retorno += '</div>';
     retorno += '<div class="f_circleG" id="frotateG_07">';
     retorno += '</div>';
     retorno += '<div class="f_circleG" id="frotateG_08">';
     */
    retorno += '</div>';
    return retorno;
}