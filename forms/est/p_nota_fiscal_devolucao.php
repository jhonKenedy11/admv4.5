<?php
/**
 * @package   astec
 * @name      p_nota_fiscal_devolucao
 * @version   4.5.0
 * @copyright 2026
 */

if (!defined('ADMpath')) {
    exit;
}

$dir = dirname(__FILE__);
require_once($dir . '/../../class/est/c_nota_fiscal_devolucao.php');
require_once($dir . '/../../../smarty/libs/Smarty.class.php');

class p_nota_fiscal_devolucao extends c_nota_fiscal_devolucao
{
    protected $m_submenu = '';
    protected $m_opcao = '';
    protected $m_letra = '';
    protected $m_par = [];
    protected $parmPost = [];
    public $smarty;

    public function __construct()
    {
        $get = filter_input_array(INPUT_GET, FILTER_DEFAULT) ?: [];
        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];
        $this->parmPost = array_merge($get, $post);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!empty($_SESSION['user_array'])) {
            c_user::from_array($_SESSION['user_array']);
        }

        $this->smarty = new Smarty();
        $this->smarty->template_dir = ADMraizFonte . '/template/est';
        $this->smarty->compile_dir = ADMraizCliente . '/smarty/templates_c/';
        $this->smarty->config_dir = ADMraizCliente . '/smarty/configs/';
        $this->smarty->cache_dir = ADMraizCliente . '/smarty/cache/';

        $this->m_submenu = $this->parmPost['submenu'] ?? '';
        $this->m_opcao = $this->parmPost['opcao'] ?? '';
        $this->m_letra = $this->parmPost['letra'] ?? '';
        $this->m_par = explode('|', $this->m_letra);

        $this->setIdNfOrigem($this->parmPost['idNfOrigem'] ?? 0);
        $this->setIdNfDev($this->parmPost['idNfDev'] ?? 0);
        $this->setIdNatop($this->parmPost['idNatop'] ?? 0);
        $this->setCodProduto($this->parmPost['codProduto'] ?? 0);
        $this->setIdPessoa($this->parmPost['idPessoa'] ?? 0);
        $this->setCenarioCodigo($this->parmPost['cenarioCodigo'] ?? null);
        $this->setOrigem($this->parmPost['origem'] ?? 'nota_fiscal_devolucao');
        $this->setSubmenuTela($this->parmPost['submenuTela'] ?? '');
        $this->setIdNfpOrigem($this->parmPost['idNfpOrigem'] ?? 0);
        $this->setQtdeDevolver($this->parmPost['qtdeDevolver'] ?? '');
        $this->setUnitario($this->parmPost['unitario'] ?? '');
        $this->setCfop($this->parmPost['cfop'] ?? '');
        $this->setItens($this->parmPost['itens'] ?? '[]');
        $this->setCabecalho($this->parmPost['cabecalho'] ?? '{}');

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME'] ?? 'index.php');
        $this->smarty->assign('titulo', 'Devolução de Nota Fiscal');
        $this->smarty->assign('colVis', '[ 0,1,2,3,4,5,6,7,8,9,10,11 ]');
        $this->smarty->assign('disableSort', '[ 12 ]');
        $this->smarty->assign('numLine', 25);
    }

    public function controle()
    {
        switch ($this->m_submenu) {
            case 'buscarContexto':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'C', false)
                    && !$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'I', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                echo json_encode($this->buscarContexto(
                    $this->getIdNfOrigem(),
                    $this->getIdNfDev() ?: null,
                    $this->getSubmenuTela() === 'cadastrar',
                    $this->getCenarioCodigo()
                ), JSON_UNESCAPED_UNICODE);
                die;

            case 'buscarItens':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'C', false)
                    && !$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'I', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                echo json_encode($this->buscarItensOrigemAjax(
                    $this->getIdNfOrigem(),
                    $this->getIdNfDev() ?: null,
                    $this->getIdNatop() ?: null,
                    $this->getSubmenuTela() === 'cadastrar'
                ), JSON_UNESCAPED_UNICODE);
                die;

            case 'buscarProduto':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'C', false)
                    && !$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'I', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                echo json_encode($this->buscarProdutoManual(
                    $this->getCodProduto(),
                    $this->getIdNatop(),
                    $this->getCenarioCodigo() ?? 'DEVOLUCAO_VENDA',
                    $this->getIdPessoa()
                ), JSON_UNESCAPED_UNICODE);
                die;

            case 'previewItem':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'C', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                echo json_encode($this->previewItem(
                    $this->getIdNfpOrigem(),
                    $this->getQtdeDevolver(),
                    $this->getUnitario(),
                    $this->getCfop(),
                    $this->getIdNatop(),
                    $this->getIdNfOrigem(),
                    $this->getIdNfpOrigem() > 0 ? 0 : $this->getCodProduto(),
                    $this->getCenarioCodigo(),
                    $this->getIdPessoa(),
                    $this->getIdNfDev() ?: null
                ), JSON_UNESCAPED_UNICODE);
                die;

            case 'previewTotais':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'C', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                $itens = json_decode($this->getItens(), true) ?: [];
                echo json_encode($this->previewTotaisComItens(
                    $itens,
                    $this->getIdNfOrigem(),
                    $this->getIdNatop(),
                    $this->getCenarioCodigo(),
                    $this->getIdPessoa(),
                    $this->getIdNfDev() ?: null
                ), JSON_UNESCAPED_UNICODE);
                die;

            case 'salvarRascunho':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'I', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                $cabecalho = json_decode($this->getCabecalho(), true) ?: [];
                $itens = json_decode($this->getItens(), true) ?: [];
                echo json_encode($this->criarOuAtualizarRascunho(
                    $cabecalho,
                    $itens,
                    $this->getIdNfDev() ?: null
                ), JSON_UNESCAPED_UNICODE);
                die;

            case 'gerarEspelhoAjax':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'C', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                echo json_encode($this->gerarEspelhoDevolucao($this->getIdNfDev()), JSON_UNESCAPED_UNICODE);
                die;

            case 'confirmar':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'A', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                $result = $this->confirmarDevolucao($this->getIdNfDev());
                if (!empty($result['ok'])) {
                    $result['redirectUrl'] = ($this->getOrigem() === 'nota_fiscal_devolucao')
                        ? 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=mostra'
                        : 'index.php?mod=est&form=nota_fiscal&submenu=mostra';
                }
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                die;

            case 'emitir':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'E', false)) {
                    echo json_encode(['ok' => false, 'erro' => 'Sem permissão.'], JSON_UNESCAPED_UNICODE);
                    die;
                }
                echo json_encode($this->emitirNfeDevolucao($this->getIdNfDev(), $this->getOrigem()), JSON_UNESCAPED_UNICODE);
                die;

            case 'cadastrar':
            case 'wizard':
                if (!$this->verificaDireitoUsuario('NotaFiscalDevolucao', 'I')) {
                    break;
                }
                if ($this->m_submenu === 'wizard' && $this->getIdNfDev() <= 0 && $this->getIdNfOrigem() <= 0) {
                    echo '<script>alert("Parâmetros inválidos para o wizard de devolução."); history.back();</script>';
                    break;
                }
                $this->smarty->assign('idNfOrigem', $this->getIdNfOrigem());
                $this->smarty->assign('idNfDev', $this->getIdNfDev());
                $this->smarty->assign('origem', $this->getOrigem());
                $this->smarty->assign('submenuTela', $this->m_submenu);
                $this->smarty->assign('manual', $this->m_submenu === 'cadastrar' ? 1 : 0);
                $modFrete = $this->getComboModFrete();
                $this->smarty->assign('modFrete_ids', $modFrete['ids']);
                $this->smarty->assign('modFrete_names', $modFrete['names']);
                $this->smarty->assign('modFrete_id', '9');

                $finalidade = $this->getComboFinalidadeEmissao();
                $this->smarty->assign('finalidadeEmissao_ids', $finalidade['ids']);
                $this->smarty->assign('finalidadeEmissao_names', $finalidade['names']);
                $this->smarty->assign('finalidadeEmissao_id', $finalidade['ids'][0] ?? '');
                $this->smarty->assign('combosTributacaoJson', json_encode($this->listarCombosTributacao(), JSON_UNESCAPED_UNICODE));
                $this->smarty->assign('opcoesTPNFJson', json_encode($this->getOpcoesTPNF(), JSON_UNESCAPED_UNICODE));
                $this->smarty->display('nota_fiscal_devolucao_wizard.tpl');
                break;

            case 'voltar':
                $urlMostra = ($this->getOrigem() === 'nota_fiscal_devolucao')
                    ? 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=mostra'
                    : 'index.php?mod=est&form=nota_fiscal&submenu=mostra';
                if (!headers_sent()) {
                    header('Location: ' . $urlMostra);
                    exit;
                }
                echo '<script>window.location.href="' . htmlspecialchars($urlMostra, ENT_QUOTES, 'UTF-8') . '";</script>';
                exit;

            case 'cancelar':
                if ($this->getIdNfDev() > 0 && $this->verificaDireitoUsuario('NotaFiscalDevolucao', 'S', false)) {
                    $this->cancelarRascunho($this->getIdNfDev());
                }
                $urlMostra = ($this->getOrigem() === 'nota_fiscal_devolucao')
                    ? 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=mostra'
                    : 'index.php?mod=est&form=nota_fiscal&submenu=mostra';
                if (in_array($this->m_opcao, ['ajax', 'blank'], true)) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(['ok' => true, 'redirectUrl' => $urlMostra], JSON_UNESCAPED_UNICODE);
                    exit;
                }
                if (!headers_sent()) {
                    header('Location: ' . $urlMostra);
                    exit;
                }
                echo '<script>window.location.href="' . htmlspecialchars($urlMostra, ENT_QUOTES, 'UTF-8') . '";</script>';
                exit;

            case 'mostra':
            default:
                if ($this->verificaDireitoUsuario('NotaFiscalDevolucao', 'C')) {
                    $this->mostraDevolucoes();
                }
                break;
        }
    }

    private function mostraDevolucoes()
    {
        foreach ($this->montarDadosMostra($this->m_letra, $this->m_par) as $chave => $valor) {
            $this->smarty->assign($chave, $valor);
        }
        $this->smarty->display('nota_fiscal_devolucao_mostra.tpl');
    }
}

$devolucao = new p_nota_fiscal_devolucao();
$devolucao->controle();
