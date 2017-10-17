<?php

namespace MotaMonteiro\Sefaz\Portal\Listeners;


use MotaMonteiro\Sefaz\Portal\Events\ServidorApiNaoRespondeuCorretamenteEvent;
use MotaMonteiro\Sefaz\Portal\Mail\ServidorApiRespostaInvalidaMail;
use Illuminate\Support\Facades\Mail;

class ServidorApiRespostaInvalidaListener
{

    /**
     * Handle the event.
     *
     * @param ServidorApiNaoRespondeuCorretamenteEvent $event
     * @return void
     * @internal param $
     */
    public function handle(ServidorApiNaoRespondeuCorretamenteEvent $event)
    {
        $api = $event->api;
        $respostaInvalida = $event->respostaInvalida;
        Mail::send(new ServidorApiRespostaInvalidaMail($api, $respostaInvalida));
    }
}
