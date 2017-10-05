<?php

namespace MotaMonteiro\Sefaz\Portal\Helpers;

class PortalHelper
{
    /**
     * @var UsuarioLogadoHelper
     */
    public $usuarioLogado;
    public $codSistemaAtual;
    public $nmeSistemaAtual;
    public $dscSistemaAtual;
    public $nmeIconeSistemaAtual;
    public $codModuloAtual;
    public $nmeModuloAtual;
    public $codMenuAtual;
    public $nmeMenuAtual;
    public $nmeEstiloMenuAtual;

    function __construct($numValidadeEmMinutos = 5)
    {
        $this->usuarioLogado = new UsuarioLogadoHelper($numValidadeEmMinutos);
        $this->codSistemaAtual = config('sistema.sigla');
        $this->nmeSistemaAtual = config('sistema.nome');
        $this->dscSistemaAtual = config('sistema.desc');
        $this->nmeIconeSistemaAtual = '';
        $this->codModuloAtual = '';
        $this->nmeModuloAtual = '';
        $this->codMenuAtual = '';
        $this->nmeMenuAtual = '';
        $this->nmeEstiloMenuAtual = '';

    }

    public function validarPermissao($codFuncao)
    {
        if($codFuncao == 'INDEX'){
            return $this->validarPermissaoIndex();
        }

        $codFuncoes = $this->getFuncoes($codFuncao);
        return (in_array($codFuncao, $codFuncoes));

    }

    private function validarPermissaoIndex()
    {
        foreach ($this->usuarioLogado->sistemasPortal as $sistema) {
            if ($sistema['codSistema'] == $this->codSistemaAtual) {
                $this->codSistemaAtual = $sistema['codSistema'];
                $this->nmeSistemaAtual = $sistema['nmeSistema'];
                $this->dscSistemaAtual = $sistema['dscSistema'];
                $this->nmeIconeSistemaAtual = $sistema['nmeUrlIcone'];

                $this->codModuloAtual = '';
                $this->nmeModuloAtual = '';

                $this->codMenuAtual = '';
                $this->nmeMenuAtual = '';
                $this->nmeEstiloMenuAtual = '';

                return true;
            }
        }
        return false;
    }

    private function getFuncoes($codFuncao)
    {
        $funcoesDisponiveis = [];

        foreach ($this->usuarioLogado->sistemasPortal as $sistema) {
            foreach ($sistema['modulos'] as $modulo) {
                if (!empty($modulo['menuRaiz'])) {
                    $this->percorrerMenus($modulo['menuRaiz']['menus'], $funcoesDisponiveis, $codFuncao, $modulo, $sistema);
                }
            }
        }
        return $funcoesDisponiveis;
    }

    protected function percorrerMenus($menus, &$arrFuncao, $codFuncaoAtual, $modulo, $sistema)
    {
        foreach ($menus as $menu) {
            if (!empty($menu['menus'])) {
                $this->percorrerMenus($menu['menus'], $arrFuncao, $codFuncaoAtual, $modulo, $sistema);
            }
            if (!empty($menu['funcoes'])) {
                foreach ($menu['funcoes'] as $funcao) {
                    array_push($arrFuncao, strtoupper($funcao['codFuncao']));

                    if ($funcao['codFuncao'] == $codFuncaoAtual) {

                        $this->codSistemaAtual = $sistema['codSistema'];
                        $this->nmeSistemaAtual = $sistema['nmeSistema'];
                        $this->dscSistemaAtual = $sistema['dscSistema'];
                        $this->nmeIconeSistemaAtual = $sistema['nmeUrlIcone'];

                        $this->codModuloAtual = $modulo['codModulo'];
                        $this->nmeModuloAtual = $modulo['nmeModulo'];

                        $this->codMenuAtual = $menu['codMenu'];
                        $this->nmeMenuAtual = $menu['nmeMenu'];
                        $this->nmeEstiloMenuAtual = $menu['nmeEstiloMenu'];
                    }
                }
            }
        }
    }

    public function existeCodMenu($codmenu, $menus)
    {
        foreach ($menus as $menu) {
            if (!empty($menu['menus'])) {
                $this->existeCodMenu($codmenu, $menu['menus']);
            }

            if ($menu['codMenu'] == $codmenu) {
                return true;
            }
        }

        return false;
    }
}