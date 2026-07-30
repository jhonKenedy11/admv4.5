<?php

/**
 * Listagem de manuais por módulo (índice JSON em admv4.5/manual/manifest.json).
 *
 * @package admv4.5
 */
if (!defined('ADMpath')) {
    exit;
}

$dir = __DIR__;
require_once($dir . '/../../../smarty/libs/Smarty.class.php');
require_once($dir . '/../../bib/c_user.php');

/**
 * Class p_manual
 */
class p_manual extends c_user
{
    private $m_submenu = '';
    public $smarty = null;

    public function __construct()
    {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        session_start();
        if (!isset($_SESSION['user_array'])) {
            exit;
        }
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty();
        $this->smarty->template_dir = ADMraizFonte . '/template/util';
        $this->smarty->compile_dir = ADMraizCliente . '/smarty/templates_c/';
        $this->smarty->config_dir = ADMraizCliente . '/smarty/configs/';
        $this->smarty->cache_dir = ADMraizCliente . '/smarty/cache/';

        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        if ($this->m_submenu === '' && isset($parmGet['submenu'])) {
            $this->m_submenu = $parmGet['submenu'];
        }

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('SCRIPT_NAME', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '');
    }

    /**
     * Caminho físico da pasta manual (raiz admv4.5).
     *
     * @return string
     */
    private function manualRootFs()
    {
        return ADMraizFonte . '/manual';
    }

    /**
     * Valida segmentos do caminho relativo (sem .. ou absoluto).
     *
     * @param string $arquivo
     * @return bool
     */
    private function isSafeManualRelativePath($arquivo)
    {
        if ($arquivo === '' || $arquivo === null) {
            return false;
        }
        if ($arquivo[0] === '/' || $arquivo[0] === '\\') {
            return false;
        }
        $normalized = str_replace('\\', '/', $arquivo);
        $parts = explode('/', $normalized);
        foreach ($parts as $p) {
            if ($p === '..') {
                return false;
            }
        }

        return true;
    }

    /**
     * Monta URL pública do arquivo sob ADMhttpBib.
     *
     * @param string $baseUrlPath ex.: manual
     * @param string $arquivo     ex.: ped/pedidops.pdf
     * @return string
     */
    private function buildPublicUrl($baseUrlPath, $arquivo)
    {
        $base = rtrim(ADMhttpBib, '/');
        $path = trim(str_replace('\\', '/', $baseUrlPath), '/');
        $file = trim(str_replace('\\', '/', $arquivo), '/');

        return $base . '/' . $path . '/' . $file;
    }

    /**
     * Confirma que o arquivo existe e permanece dentro de manual/.
     *
     * @param string $arquivo caminho relativo declarado no JSON
     * @return bool
     */
    private function fileIsInsideManualRoot($arquivo)
    {
        $root = $this->manualRootFs();
        $full = $root . '/' . str_replace('\\', '/', $arquivo);
        $rpFile = realpath($full);
        $rpRoot = realpath($root);
        if ($rpRoot === false || $rpFile === false) {
            return false;
        }
        if ($rpFile === $rpRoot) {
            return false;
        }

        $prefix = $rpRoot . DIRECTORY_SEPARATOR;

        return strpos($rpFile, $prefix) === 0;
    }

    /**
     * Carrega manifest.json e devolve módulos com urlPdf só onde válido.
     *
     * @return array{modulos: array, erro: string|null}
     */
    private function loadAndEnrichManifest()
    {
        $path = $this->manualRootFs() . '/manifest.json';
        if (!is_readable($path)) {
            return array('modulos' => array(), 'erro' => 'Arquivo manifest.json não encontrado ou sem permissão de leitura.');
        }
        $json = file_get_contents($path);
        if ($json === false) {
            return array('modulos' => array(), 'erro' => 'Não foi possível ler manifest.json.');
        }
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['modulos']) || !is_array($data['modulos'])) {
            return array('modulos' => array(), 'erro' => 'manifest.json inválido ou sem a chave "modulos".');
        }

        $baseUrlPath = isset($data['baseUrlPath']) ? $data['baseUrlPath'] : 'manual';

        usort($data['modulos'], function ($a, $b) {
            $oa = isset($a['ordem']) ? (int) $a['ordem'] : 0;
            $ob = isset($b['ordem']) ? (int) $b['ordem'] : 0;

            return $oa <=> $ob;
        });

        $outModulos = array();

        foreach ($data['modulos'] as $mod) {
            if (!isset($mod['manuais']) || !is_array($mod['manuais'])) {
                $mod['manuais'] = array();
            }
            $lista = $mod['manuais'];
            usort($lista, function ($a, $b) {
                $oa = isset($a['ordem']) ? (int) $a['ordem'] : 0;
                $ob = isset($b['ordem']) ? (int) $b['ordem'] : 0;

                return $oa <=> $ob;
            });

            $manuaisOk = array();
            foreach ($lista as $manual) {
                if (!isset($manual['arquivo']) || !isset($manual['titulo'])) {
                    continue;
                }
                $arq = $manual['arquivo'];
                if (!$this->isSafeManualRelativePath($arq) || !$this->fileIsInsideManualRoot($arq)) {
                    continue;
                }
                $manual['urlPdf'] = $this->buildPublicUrl($baseUrlPath, $arq);
                $manuaisOk[] = $manual;
            }

            $mod['manuais'] = $manuaisOk;
            // Não exibe módulo sem nenhum PDF válido (manifest vazio ou arquivos ausentes).
            if (count($manuaisOk) > 0) {
                $outModulos[] = $mod;
            }
        }

        return array('modulos' => $outModulos, 'erro' => null);
    }

    /**
     * Desenha listagem.
     *
     * @return void
     */
    private function desenhaLista()
    {
        $result = $this->loadAndEnrichManifest();

        $this->smarty->assign('tituloPagina', 'Manuais do sistema');
        $this->smarty->assign('modulos', $result['modulos']);
        $this->smarty->assign('erroManifest', $result['erro']);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', '');
        $this->smarty->assign('opcao', '');
        $this->smarty->assign('mensagem', '');
        $this->smarty->assign('tipoMsg', '');

        $this->smarty->display('manual_lista.tpl');
    }

    /**
     * Controle de submenu.
     *
     * @return void
     */
    public function controle()
    {
        switch ($this->m_submenu) {
            default:
                $this->desenhaLista();
                break;
        }
    }
}

$m = new p_manual();
$m->controle();
