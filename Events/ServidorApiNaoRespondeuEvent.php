<?php

namespace MotaMonteiro\Sefaz\Portal\Events;


use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use MotaMonteiro\Sefaz\Portal\Helpers\ApiHelper;

class ServidorApiNaoRespondeuEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var ApiHelper
     */
    public $api;

    /**
     * Create a new event instance.
     *
     * @param ApiHelper $api
     */
    public function __construct(ApiHelper $api)
    {
        $this->api = $api;
    }
}
