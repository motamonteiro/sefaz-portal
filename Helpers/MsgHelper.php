<?php

namespace MotaMonteiro\Sefaz\Portal\Helpers;

/**
 *Classe que trata das mensagens enviadas do controller para a view (pages).
 * @author by Alexandre Mota Monteiro
 * @version 1.0
 */
class MsgHelper
{

    private $valor_array;
    private $msg_popup_array;
    private $msg_campo_array;
    private $parametro_popup;
    private $cod_tipo_msg_popup;    //(0)erro, (1)sucesso, (2)confirmacao, (3)alerta
    private $nme_funcao_confirmar_msg_popup;
    private $nme_funcao_negar_msg_popup;
    private $flg_erro;
    private static $msgInstanciada; //Variavel que permite utilizar a classe em qualquer lugar

    /**
     * Contrutor da classe Mensagem
     * @param null Não precisa de passar parametro.
     * @return AtributosInstanciados Retorna os arrays instanciados e o codigo da mensagem do popup como sucesso.
     */
    function __construct()
    {

        $this->flg_erro = false;
        $this->valor_array = array();
        $this->msg_popup_array = array();
        $this->msg_campo_array = array();
        $this->parametro_popup = array();
        $this->nme_funcao_confirmar_msg_popup = array();
        $this->nme_funcao_negar_msg_popup = array();
        $this->cod_tipo_msg_popup = 1;
        MsgHelper::$msgInstanciada = $this;

    }

    /**
     * Adiciona um valor qualquer a um campo que será utilizado em uma variavel javascript definida na view(page).
     * Caso a função seja chamada mais de uma vez com o mesmo campo, os valores do campo serão transformados em um array.
     * @param string /arrayString $vlr Informação que será atribuída a um campo.
     * @param string $campo Nome do campo que será utilizado na view(page).
     * @return null Preenche o atributo Msg::$msgInstanciada->valor_array.
     */
    public static function addResultado($vlr, $campo)
    {

        if (array_key_exists($campo, MsgHelper::$msgInstanciada->valor_array)) {
            if (is_array(MsgHelper::$msgInstanciada->valor_array[$campo])) {
                array_push(MsgHelper::$msgInstanciada->valor_array[$campo], $vlr);
            } else {
                $array_aux = array(MsgHelper::$msgInstanciada->valor_array[$campo], $vlr);
                MsgHelper::$msgInstanciada->valor_array[$campo] = $array_aux;
            }
        } else {
            $array_retorno = array($campo => $vlr);
            MsgHelper::$msgInstanciada->valor_array += $array_retorno;
        }
    }

    /**
     * Adiciona uma msg a um array de mensagens que será em um popup exibido pela view(page) e o tipo de popup (0)erro, (1)sucesso.
     * Caso a função seja chamada mais de uma vez ela será exibida na ordem que foi chamada, separada no popup.
     * Se alguma mensagem tiver o tipo de erro ela será considerada na sua totalidade como erro.
     * @param string $msg Informação que será mostrada no popup.
     * @param int $cod_tipo_msg Nome do campo que será utilizado na view(page).
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_popup_array com a msg eo Msg::$msgInstanciada->cod_tipo_msg_popup de acordo com o $cod_tipo_msg.
     */
    private static function addMsgPopUp($msg, $cod_tipo_msg = 1)
    {
        if (MsgHelper::$msgInstanciada->cod_tipo_msg_popup != 0) {
            MsgHelper::$msgInstanciada->cod_tipo_msg_popup = $cod_tipo_msg;
        }
        array_push(MsgHelper::$msgInstanciada->msg_popup_array, $msg);

    }

    /**
     * Adiciona uma msg a um array de mensagens que será em um popup exibido pela view(page) e o tipo de popup como ERRO.
     * Caso a função seja chamada mais de uma vez ela será exibida na ordem que foi chamada, separada no popup.
     * Se alguma mensagem tiver o tipo de erro ela será considerada na sua totalidade como erro.
     * @param string $msg Informação que será mostrada no popup.
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_popup_array com a msg e o Msg::$msgInstanciada->cod_tipo_msg_popup como (0)Erro.
     */
    private static function addMsgErroPopUp($msg, $funcaoConfirmar)
    {
        MsgHelper::$msgInstanciada->nme_funcao_confirmar_msg_popup = $funcaoConfirmar;
        MsgHelper::$msgInstanciada->addMsgPopUp($msg, 0);
    }

    /**
     * Adiciona uma msg a um array de mensagens que será em um popup exibido pela view(page) e o tipo de popup como AVISO.
     * Caso a função seja chamada mais de uma vez ela será exibida na ordem que foi chamada, separada no popup.
     * Se alguma mensagem tiver o tipo de erro ela será considerada na sua totalidade como erro.
     * @param string $msg Informação que será mostrada no popup.
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_popup_array com a msg e o Msg::$msgInstanciada->cod_tipo_msg_popup como (0)Erro.
     */
    private static function addMsgAvisoPopUp($msg, $funcaoConfirmar)
    {
        MsgHelper::$msgInstanciada->nme_funcao_confirmar_msg_popup = $funcaoConfirmar;
        MsgHelper::$msgInstanciada->addMsgPopUp($msg, 3);
    }

    /**
     * Adiciona uma msg a um array de mensagens que será em um popup exibido pela view(page) e o tipo de popup como CONFIRMAÇÃO.
     * Caso a função seja chamada mais de uma vez ela será exibida na ordem que foi chamada, separada no popup.
     * Se alguma mensagem tiver o tipo de erro ela será considerada na sua totalidade como erro.
     * @param string $msg Informação que será mostrada no popup.
     * @param string $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @param string $funcaoNegar String com o nome da função receberá a negação.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_popup_array com a msg e o Msg::$msgInstanciada->cod_tipo_msg_popup como (0)Erro.
     */
    private static function addMsgConfirmacaoPopUp($msg, $funcaoConfirmar, $funcaoNegar)
    {
        MsgHelper::$msgInstanciada->nme_funcao_confirmar_msg_popup = $funcaoConfirmar;
        MsgHelper::$msgInstanciada->nme_funcao_negar_msg_popup = $funcaoNegar;
        MsgHelper::$msgInstanciada->addMsgPopUp($msg, 2);
    }

    /**
     * Adiciona uma msg a um campo do formulario e o tipo de mensagem desse campo.
     * Só poderá ser atribuida uma mensagem a um campo de formulario.
     * @param string $campo Nome do campo do formulário atribuido com o id na view(page).
     * @param string $msg Informação que será mostrada no campo do formulario.
     * @param int $cod_tipo_msg Tipo da mensagem que será exibida no campo do formulário (0)erro, (1)sucesso, (2) default, (3) acaoNoCampo(show, hide ...)
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array com o campo do formulario, a msg e o cod_tipo_msg de acordo com $cod_tipo_msg.
     */
    private static function addMsgCampo($campo, $msg, $cod_tipo_msg = 1)
    {
        $array_retorno = array("campo" => $campo, "msg" => $msg, "cod_tipo_msg" => $cod_tipo_msg);
        array_push(MsgHelper::$msgInstanciada->msg_campo_array, $array_retorno);
    }

    /**
     * Adiciona uma msg a um campo do formulario e o tipo de mensagem desse campo como ERRO(0).
     * Só poderá ser atribuida uma mensagem a um campo de formulario.
     * @param string $campo Nome do campo do formulário atribuido com o id na view(page).
     * @param string $msg Informação que será mostrada no campo do formulario.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array com o campo do formulario, a msg e o cod_tipo_msg como (0)Erro.
     */
    private static function addMsgErroCampo($campo, $msg)
    {
        MsgHelper::$msgInstanciada->addMsgCampo($campo, $msg, 0);
    }

    /**
     * Adiciona uma msg a um campo do formulario e o tipo de mensagem desse campo como Aviso.
     * Só poderá ser atribuida uma mensagem a um campo de formulario.
     * @param string $campo Nome do campo do formulário atribuido com o id na view(page).
     * @param string $msg Informação que será mostrada no campo do formulario.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array com o campo do formulario, a msg e o cod_tipo_msg como (0)Erro.
     */
    private static function addMsgAvisoCampo($campo, $msg)
    {
        MsgHelper::$msgInstanciada->addMsgCampo($campo, $msg, 4);
    }

    /**
     * Adiciona uma msg a um campo do formulario e o tipo de mensagem desse campo como DEFAULT(2).
     * Só poderá ser atribuida uma mensagem a um campo de formulario.
     * @param string $campo Nome do campo do formulário atribuido com o id na view(page).
     * @param string $msg Informação que será mostrada no campo do formulario.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array com o campo do formulario, a msg e o cod_tipo_msg como (2)Default.
     */
    private static function addMsgDefaultCampo($campo, $msg)
    {
        MsgHelper::$msgInstanciada->addMsgCampo($campo, $msg, 2);
    }

    /**
     * Adiciona uma ação para o jquery fazer no campo.
     * Ações possiveis: show,hide,remove.
     * @param string $campo Nome do campo do formulário atribuido com o id na view(page).
     * @param string $msg Nome da acao que será executada pelo Jquery: show,hide ou remove.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array com o campo do formulario, a msg e o cod_tipo_msg como (3)AcaoNoCampo(show, hide ou remove).
     */
    private static function addMsgAcaoCampo($campo, $msg)
    {
        MsgHelper::$msgInstanciada->addMsgCampo($campo, $msg, 3);
    }

    /**
     * Adiciona uma mesagem de popup ou uma mesangem em um campo do formulario.
     * Se os dois parametros forem passados coloca a mensagem no campo do formulário, se não passar o campo a msg vai para o popup.
     * @param string $msg Informação que será mostrada no campo do formulario.
     * @param string $campo Nome do campo do formulário atribuido com o id na view(page).
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array com o campo do formulario, a msg e o cod_tipo_msg como (0)Erro.
     */
    private static function addMensagem($msg, $campo, $funcaoConfirmar = "")
    {
        if ($campo == "") {
            MsgHelper::$msgInstanciada->nme_funcao_confirmar_msg_popup = $funcaoConfirmar;
            MsgHelper::$msgInstanciada->addMsgPopUp($msg);
        } else {
            MsgHelper::$msgInstanciada->addMsgCampo($campo, $msg);
        }
    }

    /**
     * Adiciona uma mesagem de erro de popup ou uma mesangem de erro em um campo do formulario.
     * Se os dois parametros forem passados coloca a mensagem de erro no campo do formulário, se não passar o campo a msg vai para o popup.
     * @param string $msg Informação que será mostrada no popup ou campo do formulario.
     * @param stringOpcional $campo Nome do campo do formulário atribuido com o id na view(page).
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array ou $this->msg_popup_array.
     */
    private static function addMensagemErro($msg, $campo, $funcaoConfirmar = "")
    {
        MsgHelper::$msgInstanciada->flg_erro = true;
        if ($campo == "") {
            MsgHelper::$msgInstanciada->addMsgErroPopUp($msg, $funcaoConfirmar);
        } else {
            MsgHelper::$msgInstanciada->addMsgErroCampo($campo, $msg);
        }
    }

    /**
     * Adiciona uma mesagem de aviso de popup ou uma mesangem de erro em um campo do formulario.
     * Se os dois parametros forem passados coloca a mensagem de erro no campo do formulário, se não passar o campo a msg vai para o popup.
     * @param string $msg Informação que será mostrada no popup ou campo do formulario.
     * @param stringOpcional $campo Nome do campo do formulário atribuido com o id na view(page).
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array ou $this->msg_popup_array.
     */
    private static function addMensagemAviso($msg, $campo, $funcaoConfirmar = "")
    {
        if ($campo == "") {
            MsgHelper::$msgInstanciada->addMsgAvisoPopUp($msg, $funcaoConfirmar);
        } else {
            MsgHelper::$msgInstanciada->addMsgAvisoCampo($campo, $msg);
        }
    }

    /**
     * Adiciona uma mesagem confirmação de popup.
     * Se o parametro $funcaoConfirmar não for passados coloca a mensagem de erro para o popup.
     * @param string $msg Informação que será mostrada no popup ou campo do formulario.
     * @param string $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @param string $funcaoNegar String com o nome da função receberá a negação.
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array ou $this->msg_popup_array.
     */
    private static function addMensagemConfirmacao($msg, $funcaoConfirmar, $funcaoNegar)
    {
        $flgErro = false;
        if ($funcaoConfirmar == "") {
            $flgErro = true;
            MsgHelper::$msgInstanciada->addMsgErroPopUp("Necessário especificar função de confirmar: " . $msg);
        }
        if (!$flgErro) {
            MsgHelper::$msgInstanciada->addMsgConfirmacaoPopUp($msg, $funcaoConfirmar, $funcaoNegar);
        }
    }

    /**
     * Verifica se ja foi usado algum addMensagemErro($msg, $campo);
     * @param null Não precisa de passar parametro.
     * @return boolean True se encontrou alguma mensagem de erro. Caso contrario retorna false.
     */
    public static function existeErro()
    {
        if (MsgHelper::$msgInstanciada->flg_erro) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adiciona uma mensagem default em um determinado campo.
     * Se o campo não for passado aparecerá uma erro no popup
     * @param string $msg Informação que será mostrada no popup ou campo do formulario.
     * @param stringOpcional $campo Nome do campo do formulário atribuido com o id na view(page).
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array ou $this->msg_popup_array.
     */
    private static function addMensagemDefault($msg, $campo)
    {
        if ($campo == "") {
            MsgHelper::$msgInstanciada->addMsgErroPopUp("Campo não definido para a mensagem: " . $msg);
        } else {
            MsgHelper::$msgInstanciada->addMsgDefaultCampo($campo, $msg);
        }
    }

    /**
     * Adiciona uma acão a um determinado campo
     * Se o campo não for passado aparecerá uma erro no popup
     * @param string $msg Informação que será mostrada no popup ou campo do formulario.
     * @param stringOpcional $campo Nome do campo do formulário atribuido com o id na view(page).
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array ou $this->msg_popup_array.
     */
    private static function addMensagemAcao($msg, $campo)
    {
        if ($campo == "") {
            MsgHelper::$msgInstanciada->addMsgErroPopUp("Campo não definido para a mensagem de ação: " . $msg);
        } else {
            MsgHelper::$msgInstanciada->addMsgAcaoCampo($campo, $msg);
        }
    }

    /**
     * Função para auxiliar o retorno dos valores para ser usado na função retornaMsg().
     * @param null Não precisa de passar parametro.
     * @return array Retorna o array de valores.
     */
    public static function retornaValor()
    {
        return MsgHelper::$msgInstanciada->valor_array;
    }

    /**
     * Função para auxiliar o retorno da mensagem de popup para ser usado na função retornaMsg().
     * @param null Não precisa de passar parametro.
     * @return array Retorna um array contendo o tipo do popup + array com as mensagens do popup.
     */
    public static function retornaMsgPopUp()
    {

        $array_retorno = array("cod_tipo_msg" => MsgHelper::$msgInstanciada->cod_tipo_msg_popup, "msg_array" => MsgHelper::$msgInstanciada->msg_popup_array, "nme_funcao_confirmar_msg_popup" => MsgHelper::$msgInstanciada->nme_funcao_confirmar_msg_popup, "nme_funcao_negar_msg_popup" => MsgHelper::$msgInstanciada->nme_funcao_negar_msg_popup);
        return $array_retorno;
    }

    /**
     * Função para auxiliar o retorno da mensagem de popup para ser usado na função retornaMsg().
     * @param null Não precisa de passar parametro.
     * @return array Retorna um array contendo o tipo do popup + array com as mensagens do popup.
     */
    public static function retornaParametroPopUp()
    {

        $array_retorno = MsgHelper::$msgInstanciada->parametro_popup;
        return $array_retorno;
    }

    /**
     * Função para auxiliar o retorno da mensagem de campo de formulario para ser usado na função retornaMsg().
     * @param null Não precisa de passar parametro.
     * @return array Retorna um array contendo o array com as mensagens do campo de formulario.
     */
    public static function retornaMsgCampo()
    {
        return MsgHelper::$msgInstanciada->msg_campo_array;
    }

    /**
     * Utilizada pelo controller para imprimir os arrays já no formato json.
     * @param null Não precisa de passar parametro.
     * @return echo(array) Retorna um array contendo array_valor+array_msg_popup+array_msg_campo.
     */
    public static function retornaMsg()
    {
        $array_valor = MsgHelper::$msgInstanciada->retornaValor();
        $array_msg_popup = MsgHelper::$msgInstanciada->retornaMsgPopUp();
        $array_msg_campo = MsgHelper::$msgInstanciada->retornaMsgCampo();
        $array_parametro_popup = MsgHelper::$msgInstanciada->retornaParametroPopUp();

        $array_retorno = array("array_valor" => $array_valor, "array_msg_popup" => $array_msg_popup, "array_msg_campo" => $array_msg_campo, "array_parametro_popup" => $array_parametro_popup);
        return MsgHelper::$msgInstanciada->json_formatado($array_retorno);
    }

    public static function montaHtmlMsg()
    {
        $html = "<div id='mensagem_fundo' style='display:none'></div>";
        $html .= "<div id='mensagem_div' style='display:none'>
                      <div id='mensagem_fechar' align=right><a id='mensagem_fechar_a' href='#'>FECHAR</a></div>
                      <div id='mensagem_descricao'></div>
                  </div>";
        echo $html;
    }

    /**
     * Utilizada para formatar valores de um array para htmlEntities para não dar pau no json
     * @param string /objeto/array $dat Valor que será formatado com o json_encode de acordo o o tipo do parametro informado
     * @param boolean $html Nesse sistema sempre está sendo false;
     * @return array Retorna array com html entities.
     */
    private static function json_encode_formatado($dat, $html = false)
    {
        if (is_string($dat)) {
            if ($html) {
                return htmlspecialchars($dat, ENT_QUOTES);
            } else {
                //return utf8_encode($dat); //Para projetos em ISO-8859-1
                //return mb_convert_encoding($dat, "UTF-8"); //Para projetos em ISO-8859-1
                //return mb_convert_encoding($dat, "ISO-8859-1"); //Para projetos em UTF-8
            }
        }
        if (is_object($dat)) {
            $ovs = get_object_vars($dat);
            $new = $dat;
            foreach ($ovs as $k => $v) {
                $new->$k = MsgHelper::$msgInstanciada->json_encode_formatado($new->$k, $html);
            }
            return $new;
        }

        if (!is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d)
            $ret[$i] = MsgHelper::$msgInstanciada->json_encode_formatado($d, $html);
        return $ret;
    }

    /**
     * Utilizada retornar o json dos controllers do sistema.
     * @param string /objeto/array $val Valor que será formatado com o json_encode_formatado de acordo o o tipo do parametro $val informado
     * @param boolean $html Nesse sistema sempre está sendo false;
     * @return json Retorna uma string json, resultado da funcao json_encode do php.
     */
    private static function json_formatado($val, $html = false)
    {
        $json = json_encode(MsgHelper::$msgInstanciada->json_encode_formatado($val, $html));
        return $json;
    }

//==========================================================================================================
    //Métodos Auxiliares

    /**
     * Mensagem generica de sucesso do sistema, se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $msg String com a mensagem que será exibida.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgSucesso($msg, $campo = "", $funcaoConfirmar = "")
    {
        MsgHelper::$msgInstanciada->addMensagem($msg, $campo, $funcaoConfirmar);
    }

    /**
     * Mensagem generica de sucesso do sistema, se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $msg String com a mensagem que será exibida.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addParametroPopup($titulo = "", $textoBotaoConfirma = "", $textoBotaoCancela = "")
    {
        $array_parametro = array("titulo" => $titulo, "textoBotaoConfirma" => $textoBotaoConfirma, "textoBotaoCancela" => $textoBotaoCancela);
        MsgHelper::$msgInstanciada->parametro_popup = $array_parametro;
    }

    /**
     * Mensagem "Dado(s) incluido(s) com exito" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgIncluir($campo = "")
    {
        $msg = "Dado(s) incluido(s) com exito";

        MsgHelper::$msgInstanciada->addMensagem($msg, $campo);
    }

    /**
     * Mensagem "Dado(s) alterados(s) com exito" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgAlterar($campo = "")
    {
        $msg = "Dado(s) alterados(s) com exito";

        MsgHelper::$msgInstanciada->addMensagem($msg, $campo);
    }

    /**
     * Mensagem "Dado(s) excluido(s) com exito" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgExcluir($campo = "")
    {
        $msg = "Dado(s) excluido(s) com exito";

        MsgHelper::$msgInstanciada->addMensagem($msg, $campo);
    }

    /**
     * Mensagem "E-mail enviado com exito para $para" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $para String com o email que pretende exibir na mensagem.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgEmailEnviado($para, $campo = "")
    {
        $msg = "E-mail enviado com exito para " . $para;
        MsgHelper::$msgInstanciada->addMensagem($msg, $campo);
    }

    /**
     * Mensagem generica de erro do sistema, se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $msg String com a mensagem que será exibida.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErro($msg, $campo = "", $funcaoConfirmar = "")
    {

        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo, $funcaoConfirmar);
    }

    /**
     * Mensagem generica de aviso do sistema, se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $msg String com a mensagem que será exibida.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @param stringOpcional $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgAviso($msg, $campo = "", $funcaoConfirmar = "")
    {

        MsgHelper::$msgInstanciada->addMensagemAviso($msg, $campo, $funcaoConfirmar);
    }

    /**
     * Adiciona uma mensagem default em um determinado campo.
     * Se o campo não for passado aparecerá uma erro no popup
     * @param string $msg Informação que será mostrada no popup ou campo do formulario.
     * @param stringOpcional $campo Nome do campo do formulário atribuido com o id na view(page).
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array ou $this->msg_popup_array.
     */
    static function addMsgDefault($msg, $campo = "")
    {

        MsgHelper::$msgInstanciada->addMensagemDefault($msg, $campo);
    }

    /**
     * Adiciona uma acão a um determinado campo
     * Se o campo não for passado aparecerá uma erro no popup
     * @param string $msg Informação que será mostrada no popup ou campo do formulario. (show, hide ou remove)
     * @param stringOpcional $campo Nome do campo do formulário atribuido com o id na view(page).
     * @return null Preenche o atributo Msg::$msgInstanciada->msg_campo_array ou $this->msg_popup_array.
     */
    static function addMsgAcao($msg, $campo = "")
    {
        MsgHelper::$msgInstanciada->addMensagemAcao($msg, $campo);
    }

    /**
     * Mensagem que solicita confirmação de uma ação no sistema.
     * @param string $msg String com a mensagem que será exibida.
     * @param string $funcaoConfirmar String com o nome da função receberá a confirmação.
     * @param string $funcaoNegar String com o nome da função receberá a negação.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgConfirmacao($msg, $funcaoConfirmar, $funcaoNegar = "")
    {
        MsgHelper::$msgInstanciada->addMensagemConfirmacao($msg, $funcaoConfirmar, $funcaoNegar);
    }

    /**
     * Mensagem "Erro ao incluir o(s) dado(s)" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroIncluir($campo = "")
    {
        $msg = "Erro ao incluir o(s) dado(s)";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "Erro ao alterar o(s) dado(s)" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroAlterar($campo = "")
    {
        $msg = "Erro ao alterar o(s) dado(s)";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "Erro ao excluir o(s) dado(s)" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroExcluir($campo = "")
    {
        $msg = "Erro ao excluir o(s) dado(s)";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "$nme_campo ja existe" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $nme_campo String com o nome de exibição da informação.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroJaExiste($nme_campo, $campo = "")
    {
        $msg = $nme_campo . " já existe";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "$nme_campo tamanho invalido" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $nme_campo String com o nome de exibição da informação.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroTamanho($nme_campo, $campo = "")
    {
        $msg = $nme_campo . " tamanho inválido";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "$nme_campo é invalido" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $nme_campo String com o nome de exibição da informação.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroCampoInvalido($nme_campo, $campo = "")
    {
        $msg = $nme_campo . " campo inválido";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "$nme_campo é obrigatorio" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $nme_campo String com o nome de exibição da informação.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroCampoObrigatorio($nme_campo, $campo = "")
    {
        $msg = $nme_campo . " é obrigatorio";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "Login ou senha invalido(s)!" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroLogin($campo = "")
    {
        $msg = "Login ou senha invalido(s)!";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "Sua sessão expirou, faca o login novamente!" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroLoginExpirado($campo = "")
    {
        $msg = "Sua sessão expirou, faça o login novamente!";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "Voce nao tem permissao para acessar essa pagina" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroPermissao($campo = "")
    {
        $msg = "Você não tem permissão para acessar essa página";
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Mensagem "Erro ao enviar email para $para" se o $campo for null a mensagem vai aparecer no popup, senao vai aparecer no id do campo.
     * @param string $para String com o email que pretende exibir na mensagem.
     * @param stringOpcional $campo String com o nome do id do campo que receberá a mensagem.
     * @return null Atribui a mensagem no atributo da classe Mensagem.
     */
    static function addMsgErroEmailEnviado($para, $campo = "")
    {
        $msg = "Erro ao enviar email para " . $para;
        MsgHelper::$msgInstanciada->addMensagemErro($msg, $campo);
    }

    /**
     * Adiciona mensagens de erro de acordo com o retorno da função chamaApi da classe ApiHelper
     * @param array $dadosApi
     * @return bool
     */
    public function addMsgErroApi($dadosApi = [])
    {
        if (isset($dadosApi['error'])) {

            if (isset($dadosApi['message'])) {

                if (is_array($dadosApi['message'])) {
                    foreach ($dadosApi['message'] as $linha) {
                        if(!isset($linha['key'])){
                            MsgHelper::addMsgErro($linha);
                        }else{
                            if(!is_null($linha['key']) && !empty($linha['key'])){
                                MsgHelper::addMsgErro('Erro no campo '. $linha['key'].': '.$linha['value']);
                            }else{
                                MsgHelper::addMsgErro($linha['value']);
                            }
                        }
                    }
                } else {
                    MsgHelper::addMsgErro($dadosApi['message']);
                }

            } else {
                MsgHelper::addMsgErro($dadosApi['error']);
            }
            return true;
        }
        return false;
    }
}
