<?php

namespace MotaMonteiro\Sefaz\Portal\Http\Middleware;

use Closure;
use MotaMonteiro\Sefaz\Portal\Helpers\ApiHelper;
use MotaMonteiro\Sefaz\Portal\Helpers\UsuarioLogadoHelper;

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
        $tokenKey = config('sistema.portal_api.token_key');
        $tokenValue = $request->header($tokenKey);

        if (empty($tokenKey) || empty($tokenValue)) {
            $resposta = ['error' => true, 'message' => $tokenKey.'_not_provided'];
            return response()->json($resposta, 400);
        }

        $api = new ApiHelper(config('sistema.portal_api.url'), $tokenKey, $tokenValue);

        $resposta = $api->chamaApi('permissao/sistema/'.config('sistema.codigo').'/modulo/'.config('sistema.modulo.codigo').'/funcao/'.$funcao, 'GET');

        if ($api->existeMsgErroApi($resposta)) {
            return response()->json($resposta, 401);
        }

        if (empty($resposta['permissao'])) {
            return response()->json('O usuário não tem permissão na função '.$funcao, 401);
        }

        $usuarioAutenticado = new UsuarioLogadoHelper();
        $usuarioAutenticado->tokenKey = $tokenKey;
        $usuarioAutenticado->tokenValue = $tokenValue;
        $usuarioAutenticado->numCpf = $resposta['usuario']['numCpf'];
        $usuarioAutenticado->nmeUsuario = $resposta['usuario']['nmeUsuario'];
        $usuarioAutenticado->nmeEmail = $resposta['usuario']['nmeEmail'];
        $usuarioAutenticado->nmeSetor = $resposta['usuario']['nmeSetor'];
        $usuarioAutenticado->numCnpjOrgao = $resposta['usuario']['numCnpjOrgao'];

        app()->instance('usuarioAutenticado', $usuarioAutenticado);
        return $next($request);
    }
}
