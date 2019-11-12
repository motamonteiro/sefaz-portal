<?php

namespace MotaMonteiro\Sefaz\Portal\Http\Middleware;


use Closure;
use MotaMonteiro\Sefaz\Portal\Helpers\PortalHelper;

class FrontendMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $funcao)
    {
        $portal = new PortalHelper(10);

        $portal->usuarioLogado->getUsuarioLogado();

        $permissaoPublica = false;
        if (!$portal->usuarioLogado->validarUsuarioLogado()) {

            if (!empty($portal->menuPublico)) {
                if ($portal->validarPermissao($funcao, true)) {
                    $permissaoPublica = true;
                }
            }

            if (!$permissaoPublica) {
                if (request()->ajax()) {
                    $uri = '';
                } else {
                    $uri = (request()->getRequestUri() != null && request()->getRequestUri()[0] == '/') ? substr(request()->getRequestUri(),
                        1) : request()->getRequestUri();
                }

                $url = config('sistema.portal.url') . '?redirect=' . config('app.url') . '/' . $uri;
                //Redirecionar para o portal
                return response(view('Portal::errors.401', compact('url')));
            }

        }

        if (!$permissaoPublica && !$portal->validarPermissao($funcao)) {
            $msg = 'Código da função: ' . $funcao;
            return response(view('Portal::errors.403', compact('msg')));
        }


        app()->instance('portal', $portal);
        view()->share(['portal' => $portal]);

        return $next($request);
    }
}
