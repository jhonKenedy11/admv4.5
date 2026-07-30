<?php
/**
 * @package   admv4.5
 * @name      p_contas_dashboard
 * @version   4.5.0
 * @copyright 2026
 * @link      http://www.admsistema.com.br/
 */

if (!defined('ADMpath')):
    exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . '/../../../smarty/libs/Smarty.class.php');
require_once($dir . '/../../class/crm/c_contas_dashboard.php');

class p_contas_dashboard extends c_contas_dashboard
{
    private $m_submenu = null;
    public $smarty = null;

    public function setSubmenu($submenu)
    {
        $this->m_submenu = $submenu;
    }

    public function getSubmenu()
    {
        return $this->m_submenu;
    }

    public function __construct()
    {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;
        $this->smarty->template_dir = ADMraizFonte . '/template/crm';
        $this->smarty->compile_dir = ADMraizCliente . '/smarty/templates_c/';
        $this->smarty->config_dir = ADMraizCliente . '/smarty/configs/';
        $this->smarty->cache_dir = ADMraizCliente . '/smarty/cache/';

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');

        $this->smarty->assign('titulo', 'Dashboard de contas');
        $this->smarty->assign('colVis', '[ 0,1,2,3 ]');
        $this->smarty->assign('disableSort', '[ 3 ]');
        $this->smarty->assign('numLine', '25');

        $this->setSubmenu(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->setDataIni(isset($parmPost['dataIni']) ? $parmPost['dataIni'] : (isset($parmGet['dataIni']) ? $parmGet['dataIni'] : ''));
        $this->setDataFim(isset($parmPost['dataFim']) ? $parmPost['dataFim'] : (isset($parmGet['dataFim']) ? $parmGet['dataFim'] : ''));
        $this->setCentroCustoFiltro(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : (isset($parmGet['centroCusto']) ? $parmGet['centroCusto'] : ''));
        $this->setResponsavelFiltro(isset($parmPost['responsavel']) ? $parmPost['responsavel'] : (isset($parmGet['responsavel']) ? $parmGet['responsavel'] : ''));
    }

    public function controle()
    {
        switch ($this->getSubmenu()) {
            default:
                $this->mostraDashboard('');
        }
    }

    public function mostraDashboard($mensagem = null)
    {
        $this->comboCentroCustoDashboard();
        $this->comboResponsavelDashboard();

        $this->smarty->assign('centroCusto_ids', $this->getCentroCustoIdsCombo());
        $this->smarty->assign('centroCusto_names', $this->getCentroCustoNamesCombo());
        $this->smarty->assign('responsavel_ids', $this->getResponsavelIdsCombo());
        $this->smarty->assign('responsavel_names', $this->getResponsavelNamesCombo());

        $csvCc = trim($this->getCentroCustoFiltro());
        if ($csvCc === '') {
            $idsSelecionados = array_values(array_filter($this->getCentroCustoIdsCombo(), fn($v) => $v !== 'ALL'));
            $this->smarty->assign('centroCusto_id', $idsSelecionados);
        } else {
            $this->smarty->assign('centroCusto_id', explode(',', $csvCc));
        }

        $csvResp = trim($this->getResponsavelFiltro());
        $this->smarty->assign('responsavel_id', $csvResp !== '' ? explode(',', $csvResp) : ['']);

        foreach ($this->dadosDashboardMostra() as $chave => $valor) {
            $this->smarty->assign($chave, $valor);
        }

        $this->smarty->assign('mensagem', $mensagem !== null ? $mensagem : '');
        $this->smarty->assign('opcao', '');
        $this->smarty->assign('SCRIPT_NAME', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '');
        $this->smarty->assign('subMenu', $this->getSubmenu());
        $this->smarty->display('contas_dashboard_mostra.tpl');
    }
}

$contasDashboard = new p_contas_dashboard();
$contasDashboard->controle();

