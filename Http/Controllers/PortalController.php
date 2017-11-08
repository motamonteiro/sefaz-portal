<?php

namespace MotaMonteiro\Sefaz\Portal\Http\Controllers;


class PortalController
{
    public function login()
    {
        return redirect(config('sistema.portal.url'));
    }

    public function logout()
    {
        return redirect(config('sistema.portal.url').'/efetuarLogout');
    }

    public function ping()
    {
        return response()->json(["Bem vindo ao ".config('app.name').' ('.config('app.env').') em '.date('d/m/Y H:i:s')]);
    }
}