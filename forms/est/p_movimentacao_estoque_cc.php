<?php
/**
 * @package   astec
 * @name      movimentacao_estoque_cc
 * @version   3.0.00
 * @copyright 2020
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto 
 * @date      13/05/2020
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_produto.php");
require_once($dir."/../../class/est/c_produto_estoque.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir."/../../class/crm/c_conta.php");
require_once($dir."/../../class/ped/c_pedido_venda.php");
require_once($dir."/../../class/ped/c_pedido_venda_tools.php");
require_once($dir."/../../class/ped/c_pedido_venda_nf.php");
require_once($dir."/../../class/ped/c_pedido_ps.php");
//require_once($dir."/../../forms/est/p_movimentacao_estoque_cc_imprime.php");

//Class movimentacao_estoque_cc
Class movimentacao_estoque_cc extends c_produto{

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;
    
    //VARIÁVEIS PARA IMPRESSÃO
    private $idEntrada   = null;
    private $idSaida     = null;
    private $modeloNota  = null;
    private $serieNota   = null;
    private $idCCEntrada = null;
    private $idCCSaida   = null;
    private $produto     = null;
    private $quantidade  = null;
    private $conta       = null;
    private $genero      = null;
    private $obsNf       = null;
    
    

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  
        
       // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->id_produto=(isset($parmGet['codProduto']) ? $parmGet['codProduto'] : (isset($parmPost['codProduto']) ? $parmPost['codProduto'] : ''));
        $this->desc_prod=(isset($parmGet['descProduto']) ? $parmGet['descProduto'] : (isset($parmPost['descProduto']) ? $parmPost['descProduto'] : ''));
        $this->unidade_prod=(isset($parmGet['unidade']) ? $parmGet['unidade'] : (isset($parmPost['unidade']) ? $parmPost['unidade'] : ''));
        $this->valorVenda=(isset($parmGet['valorVenda']) ? $parmGet['valorVenda'] : (isset($parmPost['valorVenda']) ? $parmPost['valorVenda'] : ''));
        $this->uniFracionada=(isset($parmGet['uniFracionada']) ? $parmGet['uniFracionada'] : (isset($parmPost['uniFracionada']) ? $parmPost['uniFracionada'] : ''));
        $this->id_pessoa=(isset($parmGet['pessoa']) ? $parmGet['pessoa'] : (isset($parmPost['pessoa']) ? $parmPost['pessoa'] : ''));
        $this->m_quantNova=(isset($parmGet['qtdeEntrada']) ? $parmGet['qtdeEntrada'] : (isset($parmPost['qtdeEntrada']) ? $parmPost['qtdeEntrada'] : 0));
        $this->m_modelo=(isset($parmGet['modelo']) ? $parmGet['modelo'] : (isset($parmPost['modelo']) ? $parmPost['modelo'] : ''));
        $this->m_serieDocto=(isset($parmGet['serieNf']) ? $parmGet['serieNf'] : (isset($parmPost['serieNf']) ? $parmPost['serieNf'] : 'TFF'));
        $this->m_numDocto=(isset($parmGet['numDocto']) ? $parmGet['numDocto'] : (isset($parmPost['numDocto']) ? $parmPost['numDocto'] : ''));
        $this->m_genero=(isset($parmGet['genero']) ? $parmGet['genero'] : (isset($parmPost['genero']) ? $parmPost['genero'] : ''));
        $this->m_descGenero=(isset($parmGet['descGenero']) ? $parmGet['descGenero'] : (isset($parmPost['descGenero']) ? $parmPost['descGenero'] : ''));
        $this->m_obsNf=(isset($parmGet['obs']) ? $parmGet['obs'] : (isset($parmPost['obs']) ? $parmPost['obs'] : ''));
        $this->m_idPed=(isset($parmGet['idPedido']) ? $parmGet['idPedido'] : (isset($parmPost['idPedido']) ? $parmPost['idPedido'] : ''));
        //Dados origem modal
        $this->m_modalDataEntrega  = (isset($parmPost['mDataEntrega']) ? $parmPost['mDataEntrega'] : null);
        $this->m_modalCCEntrega = (isset($parmPost['mCentroCusto']) ? $parmPost['mCentroCusto'] : null);
        $this->m_modalIdPedido = (isset($parmPost['idPedido']) ? $parmPost['idPedido'] : null);
        
        $this->ccustoOrigem=(isset($parmGet['centroCustoOrigem']) ? $parmGet['centroCustoOrigem'] : (isset($parmPost['centroCustoOrigem']) ? $parmPost['centroCustoOrigem'] : ''));
        $this->ccustoDestino=(isset($parmGet['centroCustoDestino']) ? $parmGet['centroCustoDestino'] : (isset($parmPost['centroCustoDestino']) ? $parmPost['centroCustoDestino'] : ''));
		        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"):
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        else:
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]"); 
            $this->smarty->assign('disableSort', "[ 0 ]"); 
            $this->smarty->assign('numLine', "25"); 
        endif;
    
            
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'ajax_entrada':
                $this->responderJson($this->executarEntradaCc());
                break;
            case 'ajax_liberar_encomenda':
                $this->responderJson($this->liberarEncomendaAjax());
                break;
            case 'buscar_produtos':
                $this->responderJson($this->buscarProdutosAjax());
                break;
            case 'detalhe_produto':
                $this->responderJson($this->detalheProdutoAjax());
                break;
            default:
                $this->mostraBaixaEstoque('');
        }
    }

    private function buscarProdutosAjax(): array
    {
        $termo = trim((string) (filter_input(INPUT_POST, 'termo', FILTER_DEFAULT) ?? ''));
        if (strlen($termo) < 2) {
            return [];
        }

        try {
            $rows = $this->buscarProdutosPorTermo($termo);
        } catch (Exception $e) {
            error_log('[movimentacao_estoque_cc] buscar_produtos: ' . $e->getMessage());
            return [];
        }

        $results = [];
        foreach ($rows as $row) {
            if (count($results) >= 20) {
                break;
            }
            $codigo = trim((string) ($row['CODIGO'] ?? $row['codigo'] ?? ''));
            $descricao = trim((string) ($row['DESCRICAO'] ?? $row['descricao'] ?? ''));
            if ($codigo === '' || $descricao === '') {
                continue;
            }
            $results[] = [
                'id' => $codigo,
                'text' => $codigo . ' - ' . $descricao,
            ];
        }
        return $results;
    }

    private function detalheProdutoAjax(): array
    {
        $codigo = (int) (filter_input(INPUT_POST, 'codigo', FILTER_DEFAULT) ?? 0);
        if ($codigo <= 0) {
            return ['ok' => false, 'mensagem' => 'Código de produto inválido.'];
        }

        $this->setId($codigo);
        $rows = $this->select_produto();
        if (!is_array($rows) || count($rows) === 0) {
            return ['ok' => false, 'mensagem' => 'Produto não encontrado.'];
        }

        $produto = $rows[0];
        $uniFracionada = $produto['UNIFRACIONADA'] ?? 'N';
        $estoque = '—';

        if ($uniFracionada !== 'S') {
            $cc = (int) $this->m_empresacentrocusto;
            $banco = new c_banco_pdo();
            $sql = 'SELECT COUNT(*) AS QTD FROM EST_PRODUTO_ESTOQUE
                    WHERE CODPRODUTO = :cod AND CENTROCUSTO = :cc AND STATUS = 0';
            $banco->prepare($sql);
            $banco->bindValue(':cod', $codigo);
            $banco->bindValue(':cc', $cc);
            $banco->execute();
            $rowEst = $banco->fetch();
            $qtd = isset($rowEst['QTD']) ? (int) $rowEst['QTD'] : 0;
            $estoque = number_format($qtd, 2, ',', '.');
        }

        $venda = isset($produto['VENDA']) ? (float) $produto['VENDA'] : 0;

        return [
            'ok' => true,
            'codigo' => $codigo,
            'descricao' => $produto['DESCRICAO'] ?? '',
            'unidade' => $produto['UNIDADE'] ?? '',
            'venda' => number_format($venda, 2, ',', '.'),
            'uniFracionada' => $uniFracionada,
            'estoque' => $estoque,
        ];
    }

    private function responderJson(array $payload): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function formatEncomendaLista(array $rows): array
    {
        $lista = [];
        foreach ($rows as $row) {
            $lista[] = [
                'pedido' => (int) $row['PEDIDO'],
                'cliente' => $row['NOMEREDUZIDO'],
                'qtde' => (float) ($row['QTD_FALTA'] ?? $row['QTSOLICITADA']),
                'qtdeSolicitada' => (float) $row['QTSOLICITADA'],
                'descricao' => $row['DESCRICAO'],
                'ccusto' => $row['CCUSTO'],
                'prazoEntrega' => $row['PRAZOENTREGA'] ?? '',
                'centroCustoEntrega' => $row['CENTROCUSTOENTREGA'],
            ];
        }
        return $lista;
    }

    private function executarEntradaCc(): array
    {
        $quant = str_replace('.', '', (string) $this->m_quantNova);
        $quant = str_replace(',', '.', $quant);

        if (abs((float) $quant) <= 0) {
            return [
                'ok' => false,
                'mensagem' => 'Quantidade inválida!',
                'tipo' => 'warning',
            ];
        }

        $this->idSaida = null;
        $msgParts = [];

        if ($this->ccustoOrigem != $this->ccustoDestino) {
            $this->idSaida = $this->insereQuant($this->m_quantNova, $this->ccustoOrigem, '1');
            $msgParts[] = 'N&deg; Docto <b>SA&Iacute;DA</b> ' . $this->idSaida;
        }

        $this->idEntrada = $this->insereQuant($this->m_quantNova, $this->ccustoDestino, '0');
        $msgParts[] = 'N&deg; Docto <b>ENTRADA</b> ' . $this->idEntrada;

        $updateDoc = new c_banco;
        $updateDoc->setTab('EST_NOTA_FISCAL');
        if (!empty($this->idSaida)) {
            $updateDoc->setField($this->idSaida, 'DOC', $this->idSaida);
            $updateDoc->setField($this->idEntrada, 'DOC', $this->idSaida);
        } else {
            $updateDoc->setField($this->idEntrada, 'DOC', $this->idEntrada);
        }

        $encomendas = $this->formatEncomendaLista(
            $this->select_produto_encomenda((int) $this->id_produto)
        );

        return [
            'ok' => true,
            'mensagem' => implode('<br>', $msgParts),
            'tipo' => 'success',
            'idEntrada' => $this->idEntrada,
            'idSaida' => $this->idSaida ?? '',
            'idCCEntrada' => $this->ccustoDestino,
            'idCCSaida' => $this->ccustoOrigem,
            'codProduto' => $this->id_produto,
            'produto' => $this->desc_prod,
            'quantidade' => $this->m_quantNova,
            'conta' => $this->id_pessoa,
            'genero' => $this->m_genero,
            'obsNf' => $this->m_obsNf,
            'encomendas' => $encomendas,
        ];
    }

    private function liberarEncomendaAjax(): array
    {
        $idPedido = (int) $this->m_modalIdPedido;
        if ($idPedido <= 0) {
            return [
                'ok' => false,
                'status' => 'erro',
                'titulo' => 'Erro',
                'mensagem' => 'Pedido inválido.',
            ];
        }

        $ccEntrega = ($this->m_modalCCEntrega !== null && $this->m_modalCCEntrega !== '')
            ? (int) $this->m_modalCCEntrega
            : null;

        if (!$this->atualizaDadosPedidoEncomenda($idPedido, $this->m_modalDataEntrega, $ccEntrega)) {
            return [
                'ok' => false,
                'status' => 'erro',
                'titulo' => 'Erro',
                'mensagem' => 'Não foi possível atualizar os dados do pedido.',
            ];
        }

        $cce = $ccEntrega ?? 0;
        if ($cce <= 0) {
            $bancoCc = new c_banco();
            $bancoCc->setTab('FAT_PEDIDO');
            $cce = (int) $bancoCc->getField('CENTROCUSTOENTREGA', 'ID=' . $idPedido);
            if ($cce <= 0) {
                $cce = (int) $bancoCc->getField('CCUSTO', 'ID=' . $idPedido);
            }
            $bancoCc->close_connection();
        }

        $objPs = new c_pedido_ps();
        $msgValidacao = $objPs->validaEstoquePedidoPs($idPedido, $cce);
        if ($msgValidacao !== null && $msgValidacao !== '') {
            return [
                'ok' => false,
                'status' => 'encomenda',
                'titulo' => 'Pedido permanece em encomenda',
                'mensagem' => 'Ainda há divergência de estoque nos itens do pedido.',
                'detalhe' => strip_tags(str_ireplace(['<br>', '<br/>', '<BR>'], "\n", $msgValidacao)),
                'encomendas' => [],
            ];
        }

        if ((new c_produto())->select_lancamento($idPedido) === null) {
            return [
                'ok' => false,
                'status' => 'sem_financeiro',
                'titulo' => 'Sem financeiro',
                'mensagem' => 'Pedido sem financeiro! Deve ser alterado manualmente através do menu de pedidos.',
                'encomendas' => [],
            ];
        }

        $objPs->setId($idPedido);
        $objPs->buscaPedido();

        $transaction = new c_banco();
        $transaction->inicioTransacao($transaction->id_connection);
        try {
            $msgReserva = $objPs->pedidoPsExecutarReservaEstoqueFarma($transaction->id_connection, 13);
            if ($msgReserva !== '') {
                throw new Exception($msgReserva);
            }
            $transaction->commit($transaction->id_connection);
        } catch (Exception $e) {
            $transaction->rollback($transaction->id_connection);
            return [
                'ok' => false,
                'status' => 'encomenda',
                'titulo' => 'Pedido permanece em encomenda',
                'mensagem' => $e->getMessage(),
                'encomendas' => [],
            ];
        }

        $objPed = new c_pedidoVenda();
        $objPed->setId($idPedido);
        $objPed->atualizarField('SITUACAO', '6');

        $objNfPs = new c_pedidoVendaNf();
        $objNfPs->setId($idPedido);
        $objNfPs->pedidoPsPosFinanceiroBaixaEstoque(null);

        $resultado = [
            'ok' => true,
            'status' => 'liberado',
            'titulo' => 'Sucesso',
            'mensagem' => 'Pedido liberado para conferência e baixa de estoque!',
        ];

        $codProduto = (int) $this->id_produto;
        $resultado['encomendas'] = $codProduto > 0
            ? $this->formatEncomendaLista($this->select_produto_encomenda($codProduto))
            : [];

        return $resultado;
    }

    private function atualizaDadosPedidoEncomenda(int $idPedido, ?string $dataEntrega, ?int $ccEntrega): bool
    {
        $sets = [];
        if ($dataEntrega !== null) {
            $sets[] = ($dataEntrega !== '')
                ? "PRAZOENTREGA = '" . addslashes($dataEntrega) . "'"
                : 'PRAZOENTREGA = NULL';
        }
        if ($ccEntrega !== null) {
            $sets[] = 'CENTROCUSTOENTREGA = ' . (int) $ccEntrega;
        }
        if ($sets === []) {
            return true;
        }

        $sql = 'UPDATE FAT_PEDIDO SET ' . implode(', ', $sets) . ' WHERE ID = ' . $idPedido;
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();

        return (bool) $banco->result;
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------

    function mostraBaixaEstoque($msg, $tipoMsg = NULL) {
  
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('msg', $msg);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('idEntrada', $this->idEntrada);
        $this->smarty->assign('idSaida', $this->idSaida);
        $this->smarty->assign('idCCEntrada', $this->ccustoDestino);
        $this->smarty->assign('idCCSaida', $this->ccustoOrigem);
        $this->smarty->assign('modeloNota', $this->m_modelo);
        $this->smarty->assign('serieNota', $this->m_serieDocto);
        $this->smarty->assign('codProduto', $this->id_produto);
        $this->smarty->assign('produto',  "'".$this->desc_prod."'");
        $this->smarty->assign('quantidade', $this->m_quantNova);
        $this->smarty->assign('conta', $this->id_pessoa);
        $this->smarty->assign('genero', $this->m_genero);
        $this->smarty->assign('obsNf', "'".$this->m_obsNf."'");

        // CENTRO DE CUSTO
        $sql = "select CENTROCUSTO AS id, descricao from FIN_CENTRO_CUSTO";
        $this->comboSql($sql, $this->m_empresacentrocusto, $ccusto_id, $ccusto_ids, $ccusto_names);
        $this->smarty->assign('centroCusto_ids',   $ccusto_ids);
        $this->smarty->assign('centroCusto_names', $ccusto_names);

        $this->smarty->assign('centroCustoOrigem',  $ccusto_id);
        $this->smarty->assign('centroCustoDestino', $ccusto_id);
        $this->smarty->assign('modalDataEntrega', date('d/m/Y'));

        $ccList = [];
        for ($i = 1; $i < count($ccusto_ids); $i++) {
            $ccList[] = ['id' => $ccusto_ids[$i], 'nome' => $ccusto_names[$i]];
        }
        $this->smarty->assign('centroCustoJson', json_encode($ccList, JSON_UNESCAPED_UNICODE));

        $this->smarty->display('movimentacao_estoque_cc.tpl');
        
    }
    

    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $ids[0] = '';
        $names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i+1] = $result[$i]['ID'];
            $names[$i+1] = $result[$i]['DESCRICAO'];
        }
        
        $param = explode(",", $par);
        $i=0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }    
    }

    function insereQuant($quant, $centroCusto, $tipoNf = '0') {
        $objEstProduto = new c_produto_estoque();
        $classNF = new c_nota_fiscal();
        $classNFProduto = new c_nota_fiscal_produto();
        //$tipoNf = '0';
    
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
        $clientePadrao = $parametros->getField("CLIENTEPADRAO", "FILIAL=".$this->m_empresacentrocusto);
        $parametros->close_connection();                        

        $classNFProduto->setQuant($quant);
        $qtde = $classNFProduto->getQuant('B');
        if ($qtde < 0){
            $qtde = $qtde * -1;
            $tipoNf = '1';
        } 

        $classNFProduto->setUnitario($this->valorVenda);
        $vlrVenda = $classNFProduto->getUnitario('B');
        $totalProd = ($qtde * $vlrVenda);

       //EST_NOTA_FISCAL
        $classNF->setModelo($this->m_modelo);
        $classNF->setSerie('TFF');
        $classNF->setNumero(0);
        $classNF->setPessoa($this->id_pessoa);
        $classNF->setEmissao(date('d/m/Y H:i'));
        //nat operacao
        $classNF->setIdNatop(99);
        $classNF->setNatOperacao('AJUSTE QUANTIDADE DE ESTOQUE');
        $classNF->setTipo($tipoNf); // 0=Entrada; 1=Saída; 
        $classNF->setSituacao('B');
        $classNF->setFormaPgto('0');
        $classNF->setDataSaidaEntrada(date('d/m/Y H:i'));
        $classNF->setFinalidadeEmissao(9);
        $classNF->setTransportador(0);
        $classNF->setCentroCusto($centroCusto);
        $classNF->setGenero($this->m_genero);
        $classNF->setOrigem('TFF');
        $classNF->setDoc(0);
        $classNF->setModFrete(0); // verificar outras opção de frete no XML
        $classNF->setTotalnf($totalProd);
        $classNF->setObs($this->m_obsNf);
        $classNF->setParam('noFormat');	
        // insere nf
        $lastNF = $classNF->incluiNotaFiscal();

        $classNF->setId($lastNF);
        $classNF->setNumero($lastNF);
        $classNF->alteraNfNumero();
        
       //EST_NOTA_FISCAL_ESTOQUE
        

        $total = 1;
        $classNFProduto->setIdNf($lastNF);
        $classNFProduto->setCodProduto($this->id_produto);
        $classNFProduto->setDescricao($this->desc_prod);
        $classNFProduto->setUnidade($this->unidade_prod);
        $classNFProduto->setQuant($qtde, true);
        $classNFProduto->setUnitario($vlrVenda, true);
        $classNFProduto->setTotal($totalProd, true);
        $classNFProduto->setOrigem('0');
        $classNFProduto->setTribIcms('00');
        $classNFProduto->setCfop('9999');
        $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));
        $classNFProduto->incluiNotaFiscalProduto();
        
        // QUANTIDADE PRODUTO_ESTOQUE 
        
        $ifControlaEstoque = (($controlaEstoque == 'S') && ($this->uniFracionada == 'N'));
        if ($ifControlaEstoque):
            $objEstProduto = new c_produto_estoque();
            if ($tipoNf == '0'):
                for ($i = 0; $i < $qtde; $i++) {
                    $objEstProduto->setIdNfEntrada($lastNF);
                    $objEstProduto->setCodProduto($this->id_produto);
                    $objEstProduto->setStatus('0');
                    $objEstProduto->setAplicado('0');
                    $objEstProduto->setCentroCusto($centroCusto);
                    $objEstProduto->setUserProduto($this->m_userid);
                    $objEstProduto->setLocalizacao('');
                    //$objEstProduto->setNsEntrada($this->getNumSerie());
                    $objEstProduto->setFabLote('');
                    $objEstProduto->setDataFabricacao('');
                    $objEstProduto->setDataValidade('');
                    $objEstProduto->incluiProdutoEstoque();
                }//for
                
            else:
                $objEstProduto->produtoBaixa($this->ccustoOrigem, $this->id_produto, $qtde, $lastNF);
            endif;
        endif;

        return $lastNF;
    }

    public function select_nota_fiscal(){

        $sql  = "SELECT * ";
        $sql .= "FROM EST_NOTA_FISCAL ";
        $sql .= "WHERE (ID = ".$this->m_id ." AND CENTROCUSTO = ".$this->ccustoOrigem.")";
        //	ECHO $sql;
    
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();

    }

//fim mostraBaixaEstoques
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$consultas = new movimentacao_estoque_cc();

$consultas->controle();
?>
