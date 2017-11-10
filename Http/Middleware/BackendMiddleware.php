<?php

namespace MotaMonteiro\Sefaz\Portal\Http\Middleware;


use Closure;
use MotaMonteiro\Sefaz\Portal\Helpers\PortalHelper;

class BackendMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $funcao)
    {
        $portal = new PortalHelper(10);
        $portal->usuarioLogado->getUsuarioLogado(config('sistema.codigo'), config('sistema.modulo.codigo'));

        if (!$portal->usuarioLogado->validarUsuarioLogado()) {
            $resposta = ['error' => true, 'message' => config('sistema.portal_api.token_key').'_invalid_user'];
            return response()->json($resposta, 400);
        }

        if (empty($portal->usuarioLogado->permissoesPortal)) {
            return response()->json('O usuário não tem permissão no módulo '.config('sistema.codigo') .' - '. config('sistema.modulo.codigo'), 401);
        }

        if (!$portal->usuarioLogado->helper->checarValorArrayMultidimensional('codFuncao', $funcao, $portal->usuarioLogado->permissoesPortal )) {
            return response()->json('O usuário não tem permissão na função '.$funcao, 401);
        }

        app()->instance('usuarioLogado', $portal->usuarioLogado);

        return $next($request);
    }
}
