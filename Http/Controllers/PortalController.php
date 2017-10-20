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
}