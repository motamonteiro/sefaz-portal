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
        $portal = new PortalHelper(10);
        $portal->usuarioLogado->getUsuariologado();

        if ($portal->usuarioLogado->numCpf == '') {
            $uri = (request()->getRequestUri() != null && request()->getRequestUri()[0] == '/') ? substr(request()->getRequestUri(), 1) : request()->getRequestUri();
            $url = config('sistema.portal.url') . '?redirect=' . config('app.url'). '/'. $uri;
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