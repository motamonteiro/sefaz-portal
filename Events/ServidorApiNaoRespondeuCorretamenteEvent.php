<?php

namespace MotaMonteiro\Sefaz\Portal\Events;


use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use MotaMonteiro\Sefaz\Portal\Helpers\ApiHelper;

class ServidorApiNaoRespondeuCorretamenteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var ApiHelper
     */
    public $api;
    /**
     * @var array
     */
    public $respostaInvalida;

    /**
     * Create a new event instance.
     *
     * @param ApiHelper $api
     * @param array|string $respostaInvalida
     */
    public function __construct(ApiHelper $api, array $respostaInvalida)
    {
        $this->api = $api;
        $this->respostaInvalida = $respostaInvalida;
    }
}
