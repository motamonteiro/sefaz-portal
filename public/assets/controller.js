/**
 * Created by alemonteiro on 13/07/2017.
 */
function chamaController(verbo, url, nmeFormulario, flgAguarde) {
    formulario = '';
    aguarde = 1;
    if (typeof(nmeFormulario) === 'string') {
        formulario = nmeFormulario;
    }
    if (typeof(flgAguarde) === 'number') {
        aguarde = flgAguarde;
    }
    ajaxConsulta(verbo, url, formulario, aguarde);
}