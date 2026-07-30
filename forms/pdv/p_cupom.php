<?php
/**
 * @package   astec
 * @name      p_cupom
 * @version   4.5.00
 * @copyright 2016
 */
if (!defined('ADMpath')) {
    exit;
}

$dir = dirname(__FILE__);
require_once($dir . '/../../../smarty/libs/Smarty.class.php');
require_once($dir . '/../../class/pdv/c_cupom.php');
require_once($dir . '/../../class/ped/c_pedido_venda_nf.php');
require_once($dir . '/../../class/est/c_produto.php');
require_once($dir . '/../../class/est/c_produto_estoque.php');
require_once($dir . '/../../class/est/c_nota_fiscal.php');
require_once($dir . '/../../class/est/c_nota_fiscal_produto.php');
require_once($dir . '/../../class/fin/c_lancamento.php');
require_once($dir . '/../../class/crm/c_conta.php');
require_once($dir . '/../../forms/est/p_nfephp_40.php');

class p_cupom extends c_cupom
{
    public $smarty = null;

    /** @var array<string,mixed> */
    private $m_parmPost = [];

    private $m_submenu = '';
    private $m_opcao = '';
    private $m_pesProduto = '';
    private $m_grupo = '';
    private $m_itensPedido = '';
    private $m_itensQtde = '1';
    private $m_controlaEstoque = 'N';
    private $m_valorDigitado = '';
    private $m_grupoPadrao = '';

    public function __construct()
    {
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT) ?: [];
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];
        // GET (submenu/opcao na URL do AJAX) + POST (dados do formulário)
        $this->m_parmPost = array_merge($parmGet, $parmPost);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty();
        $this->smarty->template_dir = ADMraizFonte . '/template/pdv';
        $this->smarty->compile_dir = ADMraizCliente . '/smarty/templates_c/';
        $this->smarty->config_dir = ADMraizCliente . '/smarty/configs/';
        $this->smarty->cache_dir = ADMraizCliente . '/smarty/cache/';

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME'] ?? 'index.php');

        $p = $this->m_parmPost;
        $this->m_submenu = isset($p['submenu']) ? (string) $p['submenu'] : '';
        if ($this->m_submenu === 'novo') {
            $this->m_submenu = 'cadastro';
        }
        $this->m_opcao = isset($p['opcao']) ? (string) $p['opcao'] : '';
        $this->m_pesProduto = isset($p['pesProduto']) ? (string) $p['pesProduto'] : '';
        $this->m_grupo = isset($p['grupo']) ? (string) $p['grupo'] : '';
        $this->m_itensPedido = isset($p['itensPedido']) ? (string) $p['itensPedido'] : '';
        $this->m_itensQtde = isset($p['itensQtde']) && $p['itensQtde'] !== '' ? (string) $p['itensQtde'] : '1';
        $this->m_valorDigitado = isset($p['valor']) ? (string) $p['valor'] : '';

        $this->setId(isset($p['id']) ? (string) $p['id'] : '');
        $this->setNrItem(isset($p['nrItem']) ? (string) $p['nrItem'] : '');
        $this->setCliente(isset($p['cliente']) ? (string) $p['cliente'] : '');
        $this->setObs(isset($p['obs']) ? (string) $p['obs'] : '');

        $dataIni = trim((string) ($p['dataIni'] ?? ''));
        $dataFim = trim((string) ($p['dataFim'] ?? ''));
        if ($dataIni === '') {
            $dataIni = date('d/m/Y', strtotime('-30 days'));
        }
        if ($dataFim === '') {
            $dataFim = date('d/m/Y');
        }
        $this->setDataIni($dataIni);
        $this->setDataFim($dataFim);
        $this->setIdFiltro(isset($p['idFiltro']) ? (string) $p['idFiltro'] : '');

        $parametros = new c_banco();
        $parametros->setTab('EST_PARAMETRO');
        $this->m_controlaEstoque = (string) $parametros->getField(
            'CONTROLAESTOQUE',
            'FILIAL=' . $this->m_empresacentrocusto
        );
        $parametros->close_connection();
        if ($this->m_controlaEstoque !== 'S') {
            $this->m_controlaEstoque = 'N';
        }
    }

    public function controle(): void
    {
        switch ($this->m_submenu) {
            case 'cadastro':
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->desenhaCupomPdv();
                }
                break;

            case 'excluirPdv':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $msg = '';
                    $tipo = 'sucesso';
                    if ((int) $this->getId() > 0) {
                        $this->setPedidoVenda();
                        $this->excluiPedidoItemGeral();
                        $this->excluiPedido();
                        $msg = 'Cupom PDV excluído.';
                    } else {
                        $msg = 'Informe o cupom a excluir.';
                        $tipo = 'erro';
                    }
                    $this->desenhaMostraCupom($msg, $tipo);
                }
                break;

            case 'pesquisaClienteAjax':
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->ajaxPesquisaCliente();
                }
                break;

            case 'salvaCliente':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->ajaxSalvaCliente();
                }
                break;

            case 'busca_produto':
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->ajaxBuscaProduto();
                }
                break;

            case 'inclui_item_ajax':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $this->ajaxIncluiItem();
                }
                break;

            case 'exclui_item_ajax':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $this->ajaxExcluiItem();
                }
                break;

            case 'altera_item_ajax':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $this->ajaxAlteraItem();
                }
                break;

            case 'salvaDescontoFrete':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $this->ajaxSalvaDescontoFrete();
                }
                break;

            case 'novoCupom':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $this->ajaxNovoCupom();
                }
                break;

            case 'cadastraNf':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $emit = $this->processaCadastraNfceCupom();
                    $msg = $emit['msg'];
                    $resultNfe = $emit['resultNfe'];
                    if ($this->respondeJsonCadastraNfceCupom($msg, $resultNfe)) {
                        break;
                    }
                    $this->respondeAjaxGerenteSePedido($msg, $msg !== '' ? 'erro' : null, $resultNfe);
                }
                break;

            case 'resumoCupomGerente':
                if (
                    $this->verificaDireitoUsuario('PedGeraNf', 'C')
                    || $this->verificaDireitoUsuario('EstGrupo', 'A')
                ) {
                    $msg = $this->carregaPedidoParaCupomFiscal(true);
                    $this->desenhaResumoCupomGerente($msg, $msg !== null ? 'alerta' : null, 'gerente');
                }
                break;

            case 'resumoCupomPdv':
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $msg = $this->carregaPedidoParaCupomFiscal(true);
                    $this->desenhaResumoCupomGerente($msg, $msg !== null ? 'alerta' : null, '');
                }
                break;

            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->desenhaMostraCupom();
                }
        }
    }

    private function desenhaBlocoResumoPdv(): void
    {
        $pedido = $this->montaViewPedidoCupom(false);
        if ($pedido === []) {
            $pedido = c_cupom::pedidoViewVazio(['id' => $this->getId()]);
        }
        $idPed = (int) ($pedido['id'] ?? 0);
        $jaExisteCpm = $idPed > 0 && $this->existeNfceCupomAutorizada($idPed);
        $this->smarty->assign('pedido', $pedido);
        $this->smarty->assign('jaExisteCpm', $jaExisteCpm);
        $this->smarty->display('cupom_pdv_resumo.tpl');
        exit;
    }

    /**
     * @param array<string,mixed> $payload
     */
    private function responderJson(array $payload, int $httpCode = 200): void
    {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function respondeResumoPdvErro(string $erro, bool $sincronizarPedido = false): void
    {
        if ($this->m_opcao === 'blank') {
            http_response_code(400);
            $html = '';
            if ($sincronizarPedido && (int) $this->getId() > 0) {
                $html .= '<span id="pdvResumoSyncId" style="display:none">' . (int) $this->getId() . '</span>';
                $html .= '<span id="pdvResumoSyncCliente" style="display:none">'
                    . htmlspecialchars((string) $this->getCliente(), ENT_QUOTES, 'UTF-8') . '</span>';
            }
            $html .= '<div class="alert alert-danger" style="margin:0">' . htmlspecialchars($erro) . '</div>';
            echo $html;
            exit;
        }
        $payload = ['success' => false, 'message' => $erro];
        if ($sincronizarPedido && (int) $this->getId() > 0) {
            $payload['pedidoId'] = (int) $this->getId();
        }
        $this->responderJson($payload, 400);
    }

    /**
     * @param array{success:bool,message:string,tipo:string} $res
     */
    private function respondeItemCupomAjax(array $res): void
    {
        if (!$res['success']) {
            $this->respondeResumoPdvErro($res['message'], (int) $this->getId() > 0);
        }
        if ($this->m_opcao === 'blank') {
            $this->desenhaBlocoResumoPdv();
        }
    }

    /**
     * @return array{msg:string,resultNfe:array<string,mixed>|null}
     */
    private function processaCadastraNfceCupom(): array
    {
        $msg = '';
        $resultNfe = null;
        $modoPgto = trim((string) ($this->m_parmPost['modo'] ?? 'D'));
        if ($modoPgto === '') {
            $modoPgto = 'D';
        }

        try {
            $docNota = trim((string) ($this->m_parmPost['cpf'] ?? ''));

            $this->preparaTotaisCupomParaEmissao($this->m_parmPost);
            $totalPedido = $this->getTotal('F');
            if (c_cupom::parseMoedaValor($totalPedido) <= 0) {
                throw new Exception('Pedido sem valor total para emitir cupom.');
            }

            if (!empty($this->m_parmPost['condPg'])) {
                $this->setCondPg((string) $this->m_parmPost['condPg']);
            }
            $temTroco = isset($this->m_parmPost['temTroco'])
                && (string) $this->m_parmPost['temTroco'] === '1';
            $valorRecebido = trim((string) ($this->m_parmPost['valorPago'] ?? ''));
            if ($valorRecebido === '') {
                $valorRecebido = trim((string) ($this->m_parmPost['totalPedidoFixo'] ?? ''));
            }
            if ($temTroco && $valorRecebido !== '') {
                $recebidoNum = c_cupom::parseMoedaValor($valorRecebido);
                $this->setTotalRecebido(number_format($recebidoNum, 2, '.', ''), false);
            } else {
                $this->setTotalRecebido($totalPedido);
            }
            $this->alteraPedidoRecebimentoCupom();
            $this->aplicaParametrosNfceCupom();

            $idPedidoCupom = (int) $this->getId();
            $idGerado = $this->getIdCupomFiscalPedidoPorOrigem($idPedidoCupom, 'CPM');
            $transaction = null;

            if ($idGerado !== null) {
                $objNfSit = new c_nota_fiscal();
                $objNfSit->setId($idGerado);
                $nfSit = $objNfSit->select_nota_fiscal();
                if (!is_array($nfSit) || !isset($nfSit[0])) {
                    $idGerado = null;
                } else {
                    $sitNf = strtoupper((string) ($nfSit[0]['SITUACAO'] ?? ''));
                    if ($sitNf === 'B') {
                        throw new Exception('Já existe NFC-e autorizada (CPM) para este pedido.');
                    }
                    if ($sitNf !== 'A') {
                        throw new Exception(
                            'Existe NFC-e vinculada em situação ' . $sitNf
                            . '. Cancele a nota antes de tentar emitir novamente.'
                        );
                    }
                }
            }

            $arrItemPedido = $this->select_pedido_item_id();
            if (!is_array($arrItemPedido)) {
                throw new Exception('Não existem produtos no pedido: ' . $this->getId());
            }

            if ($idGerado === null) {
                $transaction = new c_banco();
                $transaction->inicioTransacao($transaction->id_connection);

                $objNotaFiscal = new c_nota_fiscal();
            $objNotaFiscal->setModelo('65');
            $objNotaFiscal->setSerie($this->getSerie());
            $objNotaFiscal->setPessoa($this->getCliente());
            $objNotaFiscal->setNomePessoa();
            $objNotaFiscal->setEmissao(date('d/m/Y H:i:s'));
            $objNotaFiscal->setIdNatop($this->getIdNatop());
            $objNotaFiscal->setTipo('1');
            $objNotaFiscal->setSituacao('A');
            $objNotaFiscal->setVendaPresencial('S');
            $objNotaFiscal->setFormaPgto($this->getFormaPgto());
            $objNotaFiscal->setDataSaidaEntrada(date('d/m/Y H:i:s'));
            $objNotaFiscal->setFormaEmissao('N');
            $objNotaFiscal->setFinalidadeEmissao('1');
            $objNotaFiscal->setCentroCusto($this->m_empresacentrocusto);
            $objNotaFiscal->setGenero($this->getGenero());
            $totalNfCupom = $this->getTotal('F');
            if ($totalNfCupom === '0,00' || $totalNfCupom === '' || c_cupom::parseMoedaValor($totalNfCupom) <= 0) {
                $totalNfCupom = $totalPedido;
            }
            $objNotaFiscal->setTotalnf($totalNfCupom);
            $objNotaFiscal->setFrete($this->getFrete('F'), true);
            $objNotaFiscal->setDescontoGeral($this->getDesconto('F'), true);
            $objNotaFiscal->setModFrete('9');
            $objNotaFiscal->setTransportador('0');
            $objNotaFiscal->setObs($this->getObs());
            $objNotaFiscal->setCpfNota($docNota);
            $objNotaFiscal->setOrigem('CPM');
            $objNotaFiscal->setDoc((int) $this->getId());

            $numNf = $objNotaFiscal->geraNumNf(
                $objNotaFiscal->getModelo(),
                $objNotaFiscal->getSerie(),
                $this->m_empresacentrocusto
            );
            if ((int) $numNf === 0) {
                throw new Exception($numNf . ' >>>Numero NF');
            }
            $objNotaFiscal->setNumero($numNf);

            $idGerado = $objNotaFiscal->incluiNotaFiscal($transaction->id_connection);
            if ((int) $idGerado === 0) {
                throw new Exception((string) $idGerado);
            }

            $objCalcImposto = new c_pedidoVendaNf();
            $objNfProduto = new c_nota_fiscal_produto();
            $qtdItens = count($arrItemPedido);
            for ($r = 0; $r < $qtdItens; $r++) {
                $objNfProduto->setIdNf($idGerado);
                $objNfProduto->setCodProduto($arrItemPedido[$r]['ITEMESTOQUE']);
                $objNfProduto->setDescricao($arrItemPedido[$r]['DESCRICAO']);
                $objNfProduto->setUnidade($arrItemPedido[$r]['UNIDADE']);
                $objNfProduto->setQuant($arrItemPedido[$r]['QTSOLICITADA'], true);
                $objNfProduto->setUnitario($arrItemPedido[$r]['UNITARIO'], true);
                $objNfProduto->setDesconto($arrItemPedido[$r]['DESCONTO'], true);
                $objNfProduto->setTotal($arrItemPedido[$r]['TOTAL'], true);
                $objNfProduto->setOrigem($arrItemPedido[$r]['ORIGEM']);
                $objNfProduto->setTribIcms($arrItemPedido[$r]['TRIBICMS']);
                $objNfProduto->setNcm($arrItemPedido[$r]['NCM']);
                $objNfProduto->setCest($arrItemPedido[$r]['CEST']);

                $result = $objCalcImposto->calculaImpostosNfe(
                    $objNfProduto,
                    $objNotaFiscal->getIdNatop(),
                    $objNotaFiscal->getUfPessoa(),
                    $objNotaFiscal->getTipoPessoa(),
                    (string) $this->m_empresacentrocusto
                );
                if (!$result) {
                    throw new Exception(
                        'Tributos não localizado ' . $objNfProduto->getDescricao()
                        . ' Nat. Operação:' . $objNotaFiscal->getIdNatop()
                    );
                }
                $objNfProduto->setCustoProduto($arrItemPedido[$r]['CUSTOPRODUTO']);
                $objNfProduto->setDataConferencia($arrItemPedido[$r]['DATACONFERENCIA']);

                $result = $objNfProduto->incluiNotaFiscalProduto($transaction->id_connection);
                if (is_string($result)) {
                    throw new Exception($result);
                }
            }

                $this->cadastraParcelaFinanceiraCupom($transaction->id_connection, $objNotaFiscal, $modoPgto);

                $transaction->commit($transaction->id_connection);
            } else {
                $objNfFin = new c_nota_fiscal();
                $objNfFin->setId($idGerado);
                $nfFin = $objNfFin->select_nota_fiscal();
                if (!is_array($nfFin) || !isset($nfFin[0])) {
                    throw new Exception('Nota fiscal do cupom não encontrada.');
                }
                $objNfFin->setNotaFiscal();
                if ($docNota !== '') {
                    $objNfFin->setCpfNota($docNota);
                    $objNfFin->alteraNotaFiscal();
                }
                $this->cadastraParcelaFinanceiraCupom(null, $objNfFin, $modoPgto);
            }

            $this->sincronizaTotaisPedidoNaNotaFiscal((int) $idGerado);

            $exporta = new p_nfe_40();
            $resultNfe = $exporta->gera_XML($idGerado, $this->m_empresacentrocusto, 1);
            if (is_array($resultNfe)) {
                $resultNfe['idNf'] = $idGerado;
            }

            if (!c_cupom::nfeAutorizadaPorResultado($resultNfe)) {
                throw new Exception(c_cupom::mensagemErroNfceResultado($resultNfe));
            }

            $objNfNum = new c_nota_fiscal();
            $objNfNum->setId($idGerado);
            $nfNum = $objNfNum->select_nota_fiscal();
            $numNfCupom = (is_array($nfNum) && isset($nfNum[0]['NUMERO']))
                ? $nfNum[0]['NUMERO']
                : 0;
            if ((int) $numNfCupom > 0) {
                $objFinanceiro = new c_lancamento();
                $objFinanceiro->alteraParcelaPedidoNf(
                    (int) $this->getId(),
                    (int) $numNfCupom,
                    null,
                    'CPM'
                );
            }

            $this->setSituacao(9);
            $this->alteraPedidoSituacao();
            if ($this->m_controlaEstoque === 'S') {
                $this->baixaEstoqueCupomAposNfce((int) $idGerado);
            }
        } catch (Throwable $e) {
            if ($transaction !== null && isset($transaction->id_connection)) {
                $transaction->rollback($transaction->id_connection);
            }
            $msg = $e->getMessage() . ' — Nf/CUPOM não foi gerado';
        }

        return ['msg' => $msg, 'resultNfe' => $resultNfe];
    }

    /**
     * @param array<string,mixed>|null $resultNfe
     */
    private function respondeJsonCadastraNfceCupom(string $msg, $resultNfe = null): bool
    {
        if ($this->m_opcao !== 'blank') {
            return false;
        }

        $resposta = c_cupom::montarRespostaEmissaoNfce($msg, $resultNfe, (int) $this->getId(), false);
        $this->responderJson($resposta['payload'], $resposta['httpCode']);

        return true;
    }

    private function ajaxPesquisaCliente(): void
    {
        $term = trim((string) ($this->m_parmPost['term'] ?? ''));
        $objConta = new c_conta();
        $resultPesq = $objConta->select_pessoa_letra($term);
        $clienteResult = [];
        if (is_array($resultPesq)) {
            foreach ($resultPesq as $i => $row) {
                $clienteResult[] = [
                    'id' => trim((string) ($row['CLIENTE'] ?? '')),
                    'text' => trim((string) ($row['NOME'] ?? '')),
                ];
            }
        }
        $this->responderJson($clienteResult);
    }

    private function ajaxSalvaCliente(): void
    {
        $erro = $this->salvaClientePedido((int) ($this->m_parmPost['cliente'] ?? 0));
        if ($erro !== null) {
            $this->respondeResumoPdvErro($erro);
        }
        $this->desenhaBlocoResumoPdv();
    }

    private function ajaxBuscaProduto(): void
    {
        $termo = trim((string) ($this->m_parmPost['termo'] ?? $this->m_parmPost['codFabricante'] ?? ''));
        try {
            $this->responderJson($this->buscaProdutoPdv($termo));
        } catch (Throwable $e) {
            $this->responderJson([
                'success' => false,
                'total' => 0,
                'autoIncluir' => false,
                'itens' => [],
                'message' => 'Erro na busca: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function ajaxIncluiItem(): void
    {
        $codigo = trim((string) ($this->m_parmPost['codigo'] ?? ''));
        $qtd = trim((string) ($this->m_parmPost['quantidade'] ?? $this->m_itensQtde ?? '1'));
        if ($this->getId() !== '') {
            $this->setId((string) $this->getId());
        }
        $unitario = trim((string) ($this->m_parmPost['unitario'] ?? ''));
        $res = $this->incluirItemCupomPorCodigo($codigo, $qtd, $this->m_controlaEstoque, $unitario !== '' ? $unitario : null);
        $this->respondeItemCupomAjax($res);
    }

    private function ajaxAlteraItem(): void
    {
        $nrItem = (int) ($this->m_parmPost['nrItem'] ?? 0);
        $qtd = trim((string) ($this->m_parmPost['quantidade'] ?? '1'));
        if ($this->getId() !== '') {
            $this->setId((string) $this->getId());
        }
        $unitario = trim((string) ($this->m_parmPost['unitario'] ?? ''));
        $res = $this->alterarItemCupomPorNrItem(
            $nrItem,
            $qtd,
            $this->m_controlaEstoque,
            $unitario !== '' ? $unitario : null
        );
        $this->respondeItemCupomAjax($res);
    }

    private function ajaxSalvaDescontoFrete(): void
    {
        if ($this->getId() !== '') {
            $this->setId((string) $this->getId());
        }
        $desconto = trim((string) ($this->m_parmPost['desconto'] ?? ''));
        $frete = trim((string) ($this->m_parmPost['frete'] ?? ''));
        $erro = $this->salvaDescontoFreteCupom($desconto, $frete);
        if ($erro !== null) {
            $this->respondeResumoPdvErro($erro);
        }
        $this->desenhaBlocoResumoPdv();
    }

    private function ajaxExcluiItem(): void
    {
        $nrItem = (int) ($this->m_parmPost['nrItem'] ?? 0);
        if ($this->getId() !== '') {
            $this->setId((string) $this->getId());
        }
        $res = $this->excluirItemCupomPorNrItem($nrItem, $this->m_controlaEstoque);
        $this->respondeItemCupomAjax($res);
    }

    private function ajaxNovoCupom(): void
    {
        if ($this->getId() !== '' && (int) $this->getId() > 0) {
            $sit = 0;
            $this->setPedidoVenda();
            $ped = $this->select_pedidoVenda();
            if (is_array($ped) && isset($ped[0]['SITUACAO'])) {
                $sit = (int) $ped[0]['SITUACAO'];
            }
            if ($sit === 6) {
                $this->excluiPedidoItemGeral();
                $this->excluiPedido();
            }
        }
        if ($this->m_opcao === 'blank') {
            $this->responderJson(['success' => true, 'redirect' => 'index.php?mod=pdv&form=cupom&submenu=cadastro']);
        }
        header('Location: index.php?mod=pdv&form=cupom&submenu=cadastro');
        exit;
    }

    /**
     * @param array<string,mixed>|null $resultNfe
     * @return array<string,mixed>
     */
    private function preparaViewEncerraCupom(?string $mensagem, ?string $tipoMsg, $resultNfe = null): array
    {
        $danfe = '';
        $idNf = 0;
        $msgOut = $mensagem;
        $tipoOut = $tipoMsg;

        if (is_array($resultNfe)) {
            $cStatus = (string) ($resultNfe['cStatus'] ?? $resultNfe['cstat'] ?? '');
            if ($cStatus === '100' || $cStatus === '105') {
                $danfe = $resultNfe['cDanfe'] ?? '';
                if (isset($resultNfe['idNf'])) {
                    $idNf = (int) $resultNfe['idNf'];
                }
            } else {
                $msgOut = trim(
                    ($mensagem ?? '') . ' '
                    . ($resultNfe['motivo'] ?? 'Rejeição SEFAZ')
                    . ' (cStat: ' . $cStatus . ')'
                );
                $tipoOut = 'erro';
            }
        }

        $pedido = $this->montaViewPedidoCupom(false);
        if ($pedido === []) {
            $pedido = c_cupom::pedidoViewVazio(['id' => $this->getId()]);
        }

        $idPedidoTela = (int) ($pedido['id'] ?? 0);
        if ($idNf <= 0 && $idPedidoTela > 0) {
            $idCupom = $this->getIdCupomFiscalPedidoPorOrigem($idPedidoTela, 'CPM');
            if ($idCupom !== null) {
                $idNf = $idCupom;
            }
        }

        $totalCupom = (string) ($pedido['totalCupom'] ?? '0,00');
        $valorPago = trim((string) ($this->m_parmPost['valorPago'] ?? ''));
        if ($valorPago === '') {
            $valorPago = $totalCupom;
        }
        $modo = trim((string) ($this->m_parmPost['modo'] ?? 'D'));
        if ($modo === '') {
            $modo = 'D';
        }

        $modos = $this->listModoPagamento();
        $modo_ids = [];
        $modo_names = [];
        foreach ($modos as $i => $row) {
            $modo_ids[$i] = $row['id'];
            $modo_names[$i] = $row['descricao'];
        }

        $condPgs = $this->listCondPagamento();
        $condPg_ids = [];
        $condPg_names = [];
        foreach ($condPgs as $i => $row) {
            $condPg_ids[$i] = $row['id'];
            $condPg_names[$i] = $row['descricao'];
        }
        $condPg = trim((string) ($this->m_parmPost['condPg'] ?? ''));
        if ($condPg === '') {
            $condPg = (string) $this->getCondPg();
        }
        $trocoFmt = c_cupom::formataTroco($valorPago, $totalCupom);
        $temTroco = c_cupom::parseMoedaValor($trocoFmt) > 0
            || (isset($this->m_parmPost['temTroco']) && (string) $this->m_parmPost['temTroco'] === '1');

        return [
            'pedido' => $pedido,
            'pagamento' => [
                'valorPago' => $valorPago,
                'modo' => $modo,
                'condPg' => $condPg,
                'troco' => $trocoFmt,
                'temTroco' => $temTroco,
                'cpf' => trim((string) ($this->m_parmPost['cpf'] ?? '')),
            ],
            'modo_ids' => $modo_ids,
            'modo_names' => $modo_names,
            'condPg_ids' => $condPg_ids,
            'condPg_names' => $condPg_names,
            'idNf' => $idNf,
            'danfe' => $danfe,
            'mensagem' => $msgOut,
            'tipoMsg' => $tipoOut,
            'jaExisteCpm' => $idPedidoTela > 0 && $this->existeNfceCupomAutorizada($idPedidoTela),
        ];
    }

    private function assignViewEncerraSmarty(array $view): void
    {
        $this->smarty->assign('pedido', $view['pedido']);
        $this->smarty->assign('pagamento', $view['pagamento']);
        $this->smarty->assign('modo_ids', $view['modo_ids']);
        $this->smarty->assign('modo_names', $view['modo_names']);
        $this->smarty->assign('condPg_ids', $view['condPg_ids']);
        $this->smarty->assign('condPg_names', $view['condPg_names']);
        $this->smarty->assign('idNf', $view['idNf']);
        $this->smarty->assign('danfe', $view['danfe']);
        $this->smarty->assign('mensagem', $view['mensagem']);
        $this->smarty->assign('tipoMsg', $view['tipoMsg']);
        $this->smarty->assign('jaExisteCpm', $view['jaExisteCpm']);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('origemGerente', $this->m_opcao === 'gerente');
    }

    public function desenhaResumoCupomGerente(
        ?string $mensagem = null,
        ?string $tipoMsg = null,
        $resultNfe = null,
        string $opcaoResumo = 'gerente'
    ): void {
        $view = $this->preparaViewEncerraCupom($mensagem, $tipoMsg, $resultNfe);
        $this->assignViewEncerraSmarty($view);
        $this->smarty->assign('opcaoResumo', $opcaoResumo);
        $this->smarty->display('cupom_resumo_gerente.tpl');
    }

    /**
     * Resposta JSON para emissão via modal da gerência.
     *
     * @param array<string,mixed>|null $resultNfe
     */
    private function respondeAjaxGerenteSePedido(
        string $msg,
        ?string $tipoMsg,
        $resultNfe = null
    ): bool {
        if ($this->m_opcao !== 'gerente') {
            return false;
        }

        if ($msg !== '') {
            $this->responderJson([
                'success' => false,
                'message' => $msg,
                'tipo' => $tipoMsg ?? 'erro',
            ], 400);
        }

        $resposta = c_cupom::montarRespostaEmissaoNfce($msg, $resultNfe, (int) $this->getId(), true);
        $this->responderJson($resposta['payload'], $resposta['httpCode']);

        return true;
    }

    public function desenhaMostraCupom(?string $mensagem = null, ?string $tipoMsg = null): void
    {
        $lanc = $this->selectCuponsPdvAbertos();

        $nomeCliente = '';
        $pessoa = (int) $this->getCliente();
        if ($pessoa > 0) {
            $this->setClienteNome();
            $nomeCliente = (string) $this->getClienteNome();
        }

        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('dataIni', $this->getDataIni());
        $this->smarty->assign('dataFim', $this->getDataFim());
        $this->smarty->assign('idFiltro', $this->getIdFiltro());
        $this->smarty->assign('nomeCliente', $nomeCliente);
        $this->smarty->assign('pessoa', $pessoa > 0 ? (string) $pessoa : '');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->display('cupom_mostra.tpl');
    }

    public function desenhaCupomPdv(?string $mensagem = null, ?string $tipoMsg = null): void
    {
        $idInformado = (int) $this->getId();
        if ($idInformado > 0) {
            $this->carregarPedidoCupomPorId($idInformado);
        } else {
            $this->setId('');
            $param = $this->getParametroNfce((int) $this->m_empresacentrocusto);
            if ($param) {
                $this->setCliente((string) ($param['CLIENTEPADRAO'] ?? ''));
            }
        }

        $pedido = $this->montaViewPedidoCupom(true);
        if ($pedido === []) {
            $nomeCliente = '';
            if ($this->getCliente() !== '') {
                $this->setClienteNome();
                $nomeCliente = (string) $this->getClienteNome();
            }
            $pedido = c_cupom::pedidoViewVazio([
                'id' => $this->getId(),
                'cliente' => $this->getCliente(),
                'nomeCliente' => $nomeCliente,
            ]);
        }

        $condPgSel = (string) $this->getCondPg();
        if ($condPgSel === '' || $condPgSel === null) {
            $param = $this->getParametroNfce((int) $this->m_empresacentrocusto);
            if ($param) {
                $condPgSel = (string) ($param['CONDPGTO'] ?? '');
            }
        }

        $jaExisteCpm = (int) ($pedido['id'] ?? 0) > 0
            && $this->existeNfceCupomAutorizada((int) $pedido['id']);

        $this->smarty->assign('pedido', $pedido);
        $this->smarty->assign('pagamento', [
            'modo' => 'D',
            'condPg' => $condPgSel,
            'valorPago' => $pedido['totalCupom'] ?? '0,00',
            'troco' => '0,00',
            'temTroco' => false,
            'cpf' => trim((string) ($this->m_parmPost['cpf'] ?? '')),
        ]);
        $this->smarty->assign('pathCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('jaExisteCpm', $jaExisteCpm);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('subMenu', (int) $this->getId() > 0 ? 'alterar' : 'cadastro');
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('urlMostra', 'index.php?mod=pdv&form=cupom');

        $this->smarty->display('cupom_pdv.tpl');
    }
}

$pedido = new p_cupom();
$pedido->controle();
