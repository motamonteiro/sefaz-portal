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
            $statusCode = ($portal->usuarioLogado->statusCode != '') ? $portal->usuarioLogado->statusCode : '200';
            $msgErro = ($portal->usuarioLogado->msgErro != '') ? $portal->usuarioLogado->msgErro : config('sistema.portal_api.token_key').'_invalid_user';
            $resposta = ['error' => true, 'message' => $msgErro];
            return response()->json($resposta, $statusCode);
        }

        if (empty($portal->usuarioLogado->permissoesPortal)) {
            $resposta = ['error' => true, 'message' => 'O usuário não tem permissão no módulo '.config('sistema.codigo') .' - '. config('sistema.modulo.codigo')];
            return response()->json($resposta, 401);
        }

        if (!$portal->usuarioLogado->helper->checarValorArrayMultidimensional('codFuncao', $funcao, $portal->usuarioLogado->permissoesPortal )) {
            $resposta = ['error' => true, 'message' => 'O usuário não tem permissão na função '.$funcao];
            return response()->json($resposta, 401);
        }

        app()->instance('usuarioLogado', $portal->usuarioLogado);

        return $next($request);
    }
}
