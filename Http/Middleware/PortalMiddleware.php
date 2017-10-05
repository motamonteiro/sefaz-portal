<?php

namespace MotaMonteiro\Sefaz\Portal\Http\Middleware;


use Closure;
use MotaMonteiro\Sefaz\Portal\Helpers\PortalHelper;

class PortalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $funcao)
    {
        $portal = new PortalHelper();
        $portal->usuarioLogado->getUsuariologado();

        if ($portal->usuarioLogado->numCpf == '') {
            $url = config('sistema.portal.url') . 'Autenticacao/Login.aspx?ReturnUrl=' . request()->fullUrl();
            //Redirecionar para o portal
            return response(view('Portal::errors.401', compact('url')));
        }

        if (!$portal->validarPermissao($funcao)) {
            $msg = 'Código da função: '. $funcao;
            return response(view('Portal::errors.403', compact('msg')));
        }

        $request->request->add(['portal' => $portal]);
        view()->share(['portal' => $portal]);

        return $next($request);
    }
}