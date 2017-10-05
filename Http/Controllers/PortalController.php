<?php

namespace MotaMonteiro\Sefaz\Portal\Http\Controllers;


use App\Http\Controllers\Controller;
use MotaMonteiro\Sefaz\Portal\Helpers\PortalHelper;

class PortalController extends Controller
{
    public function login()
    {
        return redirect(config('sistema.portal.url'));
    }

    public function logout()
    {
        $portal = new PortalHelper();
        $portal->usuarioLogado->limparUsuarioLogadoSessao();
        return redirect(config('sistema.portal.url') . 'Autenticacao/Logoff.aspx');
    }
}