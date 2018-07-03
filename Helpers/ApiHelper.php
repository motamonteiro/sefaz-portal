<?php

namespace MotaMonteiro\Sefaz\Portal\Helpers;


use MotaMonteiro\Helpers\ApiHelper as BaseApiHelper;
use MotaMonteiro\Sefaz\Portal\Events\ServidorApiNaoRespondeuEvent;
use MotaMonteiro\Sefaz\Portal\Events\ServidorApiNaoRespondeuCorretamenteEvent;

class ApiHelper extends BaseApiHelper
{
    const STATUS_CODE = 'status_code';
    const ERROR = 'error';
    const MESSAGES = 'message';

    /**
     * @var string
     */
    private $rota;
    /**
     * @var string
     */
    private $metodo;
    /**
     * @var array
     */
    private $dados;


    /**
     * Api constructor.
     * @param string $baseUrl
     * @param string $tokenKey
     * @param string $tokenValue
     */
    public function __construct($baseUrl = '', $tokenKey = '', $tokenValue = '')
    {
        $this->rota = '';
        $this->metodo = '';
        $this->dados = '';

        $baseUrl = ($baseUrl) ? $baseUrl : config('sistema.url_backend');
        $tokenKey = ($tokenKey) ? $tokenKey : config('sistema.token_key_backend');
        $tokenValue = (!$tokenValue && isset($_COOKIE[config('sistema.portal.nome_cookie')])) ? $_COOKIE[config('sistema.portal.nome_cookie')] : $tokenValue;

        parent::__construct($baseUrl, $tokenKey, $tokenValue);
    }

    public function chamaApi($rota, $metodo = 'GET', array $dados = [])
    {
        $this->rota = $rota;
        $this->metodo = $metodo;
        $this->dados = $dados;

        $resposta = $this->request($rota, $metodo, $dados);

        if ($this->existsRequestError()) {

            $error = $this->getRequestErrorArray();

            if ($error[self::STATUS_CODE] == 500) {

                event(new ServidorApiNaoRespondeuEvent($this));
                $message = 'Erro ao acessar o servidor da api.';

            } else {
                /**
                 * Se for um erro de FIREWALL da SEFAZ
                 * Exemplo
                 *  $error['messages'][0]: server_error_converting_to_json
                 *  $error['messages'][1]: <html><head><title>Request Rejected</title></head><body>The requested URL was rejected. Please consult with your administrator.<br><br>Your support ID is: 9999999999999999999<br><br><a href='javascript:history.back();'>[Go Back]</a></body></html>
                 */
                if ($error['messages'][0] == 'server_error_converting_to_json' && strpos($error['messages'][1], '<title>Request Rejected</title>') !== false) {
                    $supportId = explode('<br><br>', $error['messages'][1]);
                    $supportId = explode(':', $supportId[1] ?? '')[1] ?? '';
                    $message = 'O Firewall da SEFAZ está bloqueando algum recurso desse sistema. Favor abrir um chamado no CSS (Central de Solicitações da SEFAZ) com a informação abaixo:<br> <strong>Bloqueio de firewall: '.$supportId.'</strong>';
                } else {
                    $message = 'Erro ao acessar a resposta da api.';
                }

                event(new ServidorApiNaoRespondeuCorretamenteEvent($this,$error));
            }

            return [self::ERROR => 'true', self::STATUS_CODE => $error[self::STATUS_CODE], self::MESSAGES => $message];

        }

        if (isset($resposta[self::JSON]['Message'])) {
            $statusCode = (isset($resposta['header_code'])) ? $resposta['header_code'] : '200';
            return [self::ERROR => 'true', self::STATUS_CODE => $statusCode, self::MESSAGES => 'Erro ao tratar a resposta da api: '.$resposta[self::JSON]['Message']];
        }

        $resposta[self::JSON][self::STATUS_CODE] = $resposta['header_code'];
        return $resposta[self::JSON];

    }

    public function existeMsgErroApi($resposta)
    {
        return (isset($resposta[self::ERROR]));
    }

    /**
     * @return string
     */
    public function getRota(): string
    {
        return $this->rota;
    }

    /**
     * @return string
     */
    public function getMetodo(): string
    {
        return $this->metodo;
    }

    /**
     * @return array
     */
    public function getDados(): array
    {
        return $this->dados;
    }

}
