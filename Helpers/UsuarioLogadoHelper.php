<?php

namespace MotaMonteiro\Sefaz\Portal\Helpers;


use Illuminate\Support\Facades\Cache;
use MotaMonteiro\Helpers\UtilHelper;

class UsuarioLogadoHelper
{
    public $helper;
    public $tokenKey;
    public $tokenValue;
    public $numCpf;
    public $nmeLogin;
    public $nmeUsuario;
    public $nmeEmail;
    public $nmeSetor;
    public $numCnpjOrgao;
    public $nmeOrgao;
    public $numValidadeEmMinutos;
    public $datValidade;
    public $sistemasPortal;
    public $permissoesPortal;
    public $statusCode;
    public $msgErro;
    public $redeAcessoAtual; //intranet, metro ou internet

    function __construct($numValidadeEmMinutos = 5)
    {
        $this->helper = new UtilHelper();
        $this->tokenKey = $this->getTokenKey();
        $this->tokenValue = $this->getTokenValue();
        $this->numCpf = '';
        $this->nmeLogin = '';
        $this->nmeUsuario = '';
        $this->nmeEmail = '';
        $this->nmeSetor = '';
        $this->numCnpjOrgao = '';
        $this->nmeOrgao = '';
        $this->numValidadeEmMinutos = $numValidadeEmMinutos;
        $this->datValidade = '';
        $this->sistemasPortal = [];
        $this->permissoesPortal = [];
        $this->statusCode = '200';
        $this->msgErro = '';
        $this->redeAcessoAtual = $this->getRedeAcessoAtual();

    }

    private function getTokenKey()
    {
        return (\request()->header('Authorization') != '') ? 'Authorization' : config('sistema.portal_api.token_key');
    }

    private function getTokenValue()
    {
        $token = \request()->header('Authorization') ?? '';
        if ($token == '') {
            $token = \request()->header($this->tokenKey) ?? '';
            if ($token == '') {
                $token = $_COOKIE[config('sistema.portal.nome_cookie')] ?? '';
            }
        }
        return $token;
    }

    private function getRedeAcessoAtual()
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];


        $vetIP = explode(",", $ip);
        $posVetIP = count($vetIP);

        if ($posVetIP > 1) {
            $ip = trim($vetIP[$posVetIP - 1]);
        }

        if (substr($ip,0,6) == "10.160" || substr($ip,0,7) == "192.168" || substr($ip,0,4) == "172.") {
            $redeAcesso = 'intranet';
        } elseif (substr($ip,0,6) != "10.160" && substr($ip,0,3) == "10.") {
            $redeAcesso = 'metro';
        } else {
            $redeAcesso = 'internet';
        }

        return $redeAcesso;
    }

    public function validarUsuarioLogado()
    {
        if (
            (!$this->tokenValue)
            || (!$this->helper->validarCpf($this->numCpf))
            || ($this->helper->compararDataHoraFormatoBr($this->datValidade, '<', date('d/m/Y H:i:s')))
        ) {
            return false;
        }
        return true;
    }

    /**
     * @return $this
     */
    public function getUsuarioLogado($codSistema = '', $codModulo = '')
    {

        if ($this->tokenValue != '') {

            $cacheKey = 'usuario_'.$this->tokenValue;
            $cacheKey .= ($codSistema != '') ? '_cod_sistema_' . strtolower($codSistema) : '';
            $cacheKey .= ($codModulo != '') ? '_cod_modulo_' . strtolower($codModulo) : '';

            $this->getUsuarioLogadoDoCache($cacheKey);

            if (!$this->validarUsuarioLogado()) {

                $resultado = Cache::remember($cacheKey, $this->numValidadeEmMinutos, function () use ($codSistema, $codModulo) {

                    if ($codSistema != '') {
                        $nmeRota = 'permissao/sistema/'.strtolower($codSistema);
                        $nmeRota .= ($codModulo != '') ? '/modulo/' . strtolower($codModulo) : '';
                    } else {
                        $nmeRota = 'permissao/raiz/sistema';
                    }
                    $this->getUsuarioLogadoDaApi($nmeRota);
                    return $this;
                });

                $this->preencherUsuarioLogadoDoCache($resultado);
                if (!$this->validarUsuarioLogado()) {
                    Cache::forget($cacheKey);
                }
                return $resultado;
            }
        }

        return $this;
    }

    private function getUsuarioLogadoDoCache($cacheKey)
    {
        $usuarioLogadoCache = Cache::get($cacheKey);

        if ($usuarioLogadoCache instanceof $this) {
            $this->preencherUsuarioLogadoDoCache($usuarioLogadoCache);
        }

        return $this;
    }

    private function getUsuarioLogadoDaApi($nmeRota)
    {
        if (!empty($this->tokenKey) && !empty($this->tokenValue)) {

            $api = new ApiHelper(config('sistema.portal_api.url'), $this->tokenKey, $this->tokenValue);

            $usuarioLogadoApi = $api->chamaApi($nmeRota, 'GET');

            if ($api->existeMsgErroApi($usuarioLogadoApi)) {
                $this->statusCode = $api->getStatusCode();
                $this->msgErro = $usuarioLogadoApi['message'];
            } else {
                $this->preencherUsuarioLogadoDaApi($usuarioLogadoApi);
            }
        }

        return $this;
    }

    private function preencherUsuarioLogadoDaApi($usuarioLogadoApi)
    {
        if (!isset($usuarioLogadoApi['permissao'])) {

            $this->numCpf = $usuarioLogadoApi['numCpf'];
            $this->nmeLogin = $usuarioLogadoApi['nmeLogin'];
            $this->nmeUsuario = $usuarioLogadoApi['nmeUsuario'];
            $this->nmeEmail = $usuarioLogadoApi['nmeEmail'];
            $this->nmeSetor = $usuarioLogadoApi['nmeSetor'];
            $this->numCnpjOrgao = $usuarioLogadoApi['numCnpjOrgao'];
            $this->nmeOrgao = '';
            $this->sistemasPortal = $this->filtrarPorRedeAcesso($usuarioLogadoApi['sistemas'] ?? []);
            $this->datValidade = $this->helper->somarDataHoraFormatoBr(date('d/m/Y H:i:s'), 0, 0, 0, 0, $this->numValidadeEmMinutos, 0);

        } else {

            $this->numCpf = $usuarioLogadoApi['usuario']['numCpf'];
            $this->nmeLogin = $usuarioLogadoApi['usuario']['nmeLogin'];
            $this->nmeUsuario = $usuarioLogadoApi['usuario']['nmeUsuario'];
            $this->nmeEmail = $usuarioLogadoApi['usuario']['nmeEmail'];
            $this->nmeSetor = $usuarioLogadoApi['usuario']['nmeSetor'];
            $this->numCnpjOrgao = $usuarioLogadoApi['usuario']['numCnpjOrgao'];
            $this->nmeOrgao = '';
            $this->sistemasPortal = [];
            $this->permissoesPortal = $usuarioLogadoApi['permissao'] ?? [];
            $this->datValidade = $this->helper->somarDataHoraFormatoBr(date('d/m/Y H:i:s'), 0, 0, 0, 0, $this->numValidadeEmMinutos, 0);
        }
    }

    private function filtrarPorRedeAcesso($sistemas = [])
    {
        $sistemasPorRedeAcesso = [];
        if ($sistemas[0]['modulos'] ?? false) {
            foreach ($sistemas as $sistema) {
                $modulos = collect($sistema['modulos']);
                switch ($this->redeAcessoAtual) {
                    case 'internet':
                        $modulos = $modulos->where('nmeRedeAcesso',$this->redeAcessoAtual);
                        break;
                    case 'metro':
                        $modulos = $modulos->whereIn('nmeRedeAcesso', [$this->redeAcessoAtual, 'internet']);
                        break;
                    case 'intranet':
                        $modulos = $modulos->whereIn('nmeRedeAcesso', [$this->redeAcessoAtual, 'internet', 'metro']);
                        break;
                }
                if (!$modulos->isEmpty()) {
                    $sistema['modulos'] = $modulos->toArray();
                    array_push($sistemasPorRedeAcesso, $sistema);
                }
            }
        }
        return $sistemasPorRedeAcesso;
    }

    private function preencherUsuarioLogadoDoCache($usuarioLogadoCache)
    {
        $this->tokenKey = $usuarioLogadoCache->tokenKey;
        $this->tokenValue = $usuarioLogadoCache->tokenValue;
        $this->numCpf = $usuarioLogadoCache->numCpf;
        $this->nmeLogin = $usuarioLogadoCache->nmeLogin;
        $this->nmeUsuario = $usuarioLogadoCache->nmeUsuario;
        $this->nmeEmail = $usuarioLogadoCache->nmeEmail;
        $this->nmeSetor = $usuarioLogadoCache->nmeSetor;
        $this->numCnpjOrgao = $usuarioLogadoCache->numCnpjOrgao;
        $this->nmeOrgao = $usuarioLogadoCache->nmeOrgao;
        $this->sistemasPortal = $usuarioLogadoCache->sistemasPortal;
        $this->permissoesPortal = $usuarioLogadoCache->permissoesPortal;
        $this->datValidade = $usuarioLogadoCache->datValidade;
    }

}