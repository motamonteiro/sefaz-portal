<?php

namespace MotaMonteiro\Sefaz\Portal\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MotaMonteiro\Sefaz\Portal\Helpers\ApiHelper;

class ServidorApiRespostaInvalidaMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var ApiHelper
     */
    public $api;
    /**
     * @var array
     */
    public $respostaInvalida;
    /**
     * @var string
     */
    public $nmeSistema;
    /**
     * @var string
     */
    public $urlSistema;
    /**
     * @var string
     */
    protected $nmeFrom;
    /**
     * @var string
     */
    protected $nmeEmailFrom;
    /**
     * @var string
     */
    protected $nmeEmailTo;
    /**
     * @var string
     */
    protected $nmeSubject;
    /**
     * @var string
     */
    protected $nmeMarkdown;


    /**
     * Create a new message instance.
     *
     * @param ApiHelper $api
     */
    public function __construct(ApiHelper $api, array $respostaInvalida)
    {
        $this->api = $api;
        $this->respostaInvalida = $respostaInvalida;
        $this->nmeSistema = config('sistema.nome');
        $this->urlSistema = request()->fullUrl();
        $this->nmeFrom = $this->nmeSistema;
        $this->nmeEmailFrom = config('sistema.email.frontend');
        $this->nmeEmailTo = config('sistema.email.backend');
        $this->nmeSubject = $this->nmeSistema . ' - Servidor Api Resposta Inválida';
        $this->nmeMarkdown = 'Portal::emails.servidor_api_resposta_invalida';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->nmeEmailFrom, $this->nmeFrom)
            ->to($this->nmeEmailTo)
            ->subject($this->nmeSubject)
            ->markdown($this->nmeMarkdown);
    }
}
