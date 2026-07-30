<?php
/**
 * @package   admv4.5
 * @name      p_rel_compra_encomenda
 * @version   4.5
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 */
if (!defined('ADMpath')) {
    exit;
}

$dir = dirname(__FILE__);

require_once($dir . '/../../../smarty/libs/Smarty.class.php');
require_once($dir . '/../../class/ped/c_pedido_venda_relatorios.php');

class p_rel_compra_encomenda extends c_user
{
    private $m_submenu = null;
    private $m_opcao = null;

    public function __construct()
    {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;
        $this->smarty->template_dir = ADMraizFonte . '/template/ped';
        $this->smarty->compile_dir = ADMraizCliente . '/smarty/templates_c/';
        $this->smarty->config_dir = ADMraizCliente . '/smarty/configs/';
        $this->smarty->cache_dir = ADMraizCliente . '/smarty/cache/';

        $this->m_submenu = $parmGet['submenu'] ?? ($parmPost['submenu'] ?? '');
        $this->m_opcao = $parmGet['opcao'] ?? ($parmPost['opcao'] ?? '');

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
    }

    public function controle()
    {
        switch ($this->m_submenu) {
            case 'relatorio':
                $this->imprimeRelatorio();
                break;
            default:
                header('Location: index.php?mod=ped&form=pedido_relatorios');
                exit;
        }
    }

    private function imprimeRelatorio()
    {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];

        $idGrupo = trim((string) ($parmPost['idGrupo'] ?? ''));
        $codProduto = trim((string) ($parmPost['codProduto'] ?? ''));

        $rel = new c_pedido_venda_relatorios();
        $pedidos = $rel->selectRelatorioCompraEncomenda(
            (int) $this->m_empresacentrocusto,
            $idGrupo !== '' ? $idGrupo : null,
            $codProduto !== '' ? $codProduto : null
        );

        $this->smarty->assign('pedidos', $pedidos);
        $this->smarty->assign('dataImp', date('d/m/Y H:i:s'));
        $this->smarty->display('rel_compra_encomenda.tpl');
    }
}

$relatorio = new p_rel_compra_encomenda();
$relatorio->controle();
