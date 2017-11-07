<?php

namespace MotaMonteiro\Sefaz\Portal\Helpers;


use MotaMonteiro\Helpers\UtilHelper;

class UsuarioLogadoHelper
{
    const SESSAO_USUARIO = 'usuarioLogado';

    public $helper;
    public $tokenKey;
    public $tokenValue;
    public $numCpf;
    public $nmeUsuario;
    public $nmeEmail;
    public $nmeSetor;
    public $numCnpjOrgao;
    public $nmeOrgao;
    public $numValidadeEmMinutos;
    public $datValidade;
    public $sistemasPortal;

    function __construct($numValidadeEmMinutos = 5)
    {
        $this->helper = new UtilHelper();
        $this->tokenKey = config('sistema.portal_api.token_key');
        $this->tokenValue = $this->getTokenValue();
        $this->numCpf = '';
        $this->nmeUsuario = '';
        $this->nmeEmail = '';
        $this->nmeSetor = '';
        $this->numCnpjOrgao = '';
        $this->nmeOrgao = '';
        $this->numValidadeEmMinutos = $numValidadeEmMinutos;
        $this->datValidade = '';
        $this->sistemasPortal = [];

    }

    private function getTokenValue()
    {
        $nomeCookie = config('sistema.portal.nome_cookie');
        $token = isset($_COOKIE[$nomeCookie]) ? $_COOKIE[$nomeCookie] : '';

        if ($this->tokenKey == 'Authorization' && $token != '') {
            $token = 'Bearer ' . $token;
        }

        return $token;
    }


    /**
     * @return $this
     */
    public function getUsuarioLogado()
    {
        $usuarioLogado = session(self::SESSAO_USUARIO);

        if (!$this->tokenValue ||
            ($this->tokenValue != $_COOKIE[config('sistema.portal.nome_cookie')]) ||
            !$usuarioLogado ||
            !$this->helper->validarCpf($usuarioLogado->numCpf) ||
            $this->helper->compararDataHoraFormatoBr($usuarioLogado->datValidade, '<', date('d/m/Y H:i:s'))
        ) {

            $this->getUsuarioLogadoDaApi();
        } else {
            $this->getUsuarioLogadoSessao();
        }

        return $this;
    }

    public function limparUsuarioLogadoSessao()
    {
        session([self::SESSAO_USUARIO => null]);
    }

    private function setUsuarioLogadoSessao()
    {
        session([self::SESSAO_USUARIO => $this]);
    }

    private function getUsuarioLogadoSessao()
    {
        $usuarioLogadoSessao = session(self::SESSAO_USUARIO);

        $this->numCpf = $usuarioLogadoSessao->numCpf;
        $this->nmeUsuario = $usuarioLogadoSessao->nmeUsuario;
        $this->nmeEmail = $usuarioLogadoSessao->nmeEmail;
        $this->nmeSetor = $usuarioLogadoSessao->nmeSetor;
        $this->numCnpjOrgao = $usuarioLogadoSessao->numCnpjOrgao;
        $this->nmeOrgao = $usuarioLogadoSessao->nmeOrgao;
        $this->sistemasPortal = $usuarioLogadoSessao->sistemasPortal;
        $this->datValidade = $usuarioLogadoSessao->datValidade;

        return $this;
    }

    private function getUsuarioLogadoDaApi()
    {
        $api = new ApiHelper(config('sistema.portal_api.url'), $this->tokenKey, $this->tokenValue);

        $usuarioLogadoApi = $api->chamaApi('permissao/raiz/sistema', 'GET');

        if (!$api->existeMsgErroApi($usuarioLogadoApi)) {

            $this->preencherUsuarioLogadoDaApi($usuarioLogadoApi);
            $this->setUsuarioLogadoSessao();

        } elseif ($usuarioLogadoApi['message'] == 'token_expired') {

            $token = $api->chamaApi('autenticacao/refreshToken', 'GET');

            if (!$api->existeMsgErroApi($token)) {
                $api->setTokenValue($token['token']);

                $usuarioLogadoApi = $api->chamaApi('permissao/raiz/sistema', 'GET');

                if (!$api->existeMsgErroApi($usuarioLogadoApi)) {

                    $this->preencherUsuarioLogadoDaApi($usuarioLogadoApi);
                    $this->setUsuarioLogadoSessao();
                }
            }
        }

        return $this;
    }

    private function preencherUsuarioLogadoDaApi($usuarioLogadoApi)
    {
        $this->numCpf = $usuarioLogadoApi['numCpf'];
        $this->nmeUsuario = $usuarioLogadoApi['nmeUsuario'];
        $this->nmeEmail = $usuarioLogadoApi['nmeEmail'];
        $this->nmeSetor = $usuarioLogadoApi['nmeSetor'];
        $this->numCnpjOrgao = $usuarioLogadoApi['numCnpjOrgao'];
        $this->nmeOrgao = '';
        $this->sistemasPortal = $usuarioLogadoApi['sistemas'];
        $this->datValidade = $this->helper->somarDataHoraFormatoBr(date('d/m/Y H:i:s'), 0, 0, 0, 0, $this->numValidadeEmMinutos, 0);
    }

}