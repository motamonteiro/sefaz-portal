<?php

namespace MotaMonteiro\Sefaz\Portal\Listeners;

use MotaMonteiro\Sefaz\Portal\Events\ServidorApiNaoRespondeuEvent;
use MotaMonteiro\Sefaz\Portal\Mail\ServidorApiIndisponivelMail;
use Illuminate\Support\Facades\Mail;

class ServidorApiIndisponivelListener
{

    /**
     * Handle the event.
     *
     * @param  ServidorApiNaoRespondeuEvent  $event
     * @return void
     */
    public function handle(ServidorApiNaoRespondeuEvent $event)
    {
        $api = $event->api;
        Mail::send(new ServidorApiIndisponivelMail($api));
    }
}
