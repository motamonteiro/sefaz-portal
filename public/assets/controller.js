function chamaController(verbo, url, nmeFormulario, flgAguarde) {
    var formulario = '';
    var aguarde = 1;
    if (typeof(nmeFormulario) === 'string') {
        formulario = nmeFormulario;
    }
    if (typeof(flgAguarde) === 'number') {
        aguarde = flgAguarde;
    }

    ajaxConsulta(verbo, url, formulario, aguarde);

}

/**
 *
 * @param verbo
 * @param url
 * @param parametro
 * @param flgAguarde
 * @example $("#idFormulario").submit(function () { var formData = new FormData(this); chamaControllerComAnexo('POST', 'rota', formData, 1); });
 */
function chamaControllerComAnexo(verbo, url, parametro, flgAguarde) {
    var aguarde = 1;
    if (typeof(flgAguarde) === 'number') {
        aguarde = flgAguarde;
    }
    ajaxConsultaComAnexo(verbo, url, parametro, aguarde);
}
