<?php

/**
 * @package   admv4.5
 * @name      c_cotacao
 * @version   4.5.0
 * @copyright 2025
 * @link      http://www.admsistema.com.br/
 * @author    Joshua Silva
 * @date      2025
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/c_pedido_ps.php");
require_once($dir . "/../../bib/c_database.php");
require_once($dir . "/../../bib/c_database_pdo.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");

//Class c_cotacao
class c_cotacao extends c_pedido_ps {
    
    public $smarty = NULL;
    private $m_percentualAplicar = NULL;
    
    /**
     * <b> Função magica construct </b>
     */
    function __construct()
    {
        c_user::from_array($_SESSION['user_array']);
        
        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;
        
        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";
        
    }

    ## inicio get e set ##

    public function setPercentualAplicar($percentualAplicar) {
        $this->m_percentualAplicar = $percentualAplicar;
    }

    public function getPercentualAplicar() {
        return $this->m_percentualAplicar;
    }

    public function setItensPedidoCC($itensPedidoCC) {
        $this->m_itensPedidoCC = $itensPedidoCC;
    }

    public function getItensPedidoCC() {
        return $this->m_itensPedidoCC;
    }

    public function setPesqCc($pesq_cc) {
        $this->m_pesq_cc = $pesq_cc;
    }

    public function getPesqCc() {
        return $this->m_pesq_cc;
    }

    public function setDescCc($desc_cc) {
        $this->m_desc_cc = $desc_cc;
    }

    public function getDescCc() {
        return $this->m_desc_cc;
    }
    ## fim get e set cotação ##


    ## inicio cotação ##

    /**
     * Busca dados da cotação
     * 
     * @return array Dados da cotação
     */
    public function select_pedido_parametros()
    {
        $sql = "SELECT P.ID, P.PEDIDO, P.EMISSAO, P.CLIENTE, P.DESCONTO, P.TOTALPRODUTOS, P.TOTAL, P.MARKUP, P.CONDPG, CP.DESCRICAO AS DESCCONDPGTO, C.NOME  
                FROM FAT_PEDIDO P 
                LEFT JOIN FAT_COND_PGTO CP ON (CP.ID = P.CONDPG) 
                LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = P.CLIENTE) ";
        
        $params = [];
        $whereConditions = [];
        
        // Sempre filtra por situação 5 (Cotação)
        $whereConditions[] = "P.SITUACAO = '5'";
        
        if ($this->getId() != ''){
            $whereConditions[] = "P.ID = :id";
            $params[':id'] = $this->getId();
        } else {
            if ($this->getCliente() != ''){
                $whereConditions[] = "P.CLIENTE = :cliente";
                $params[':cliente'] = $this->getCliente();
            }
            
            if ($this->getDataIni() != ''){
                $whereConditions[] = "P.EMISSAO >= :dataIni";
                $params[':dataIni'] = c_date::convertDateBd($this->getDataIni());
            }
            
            if ($this->getDataFim() != ''){
                $whereConditions[] = "P.EMISSAO <= :dataFim";
                $params[':dataFim'] = c_date::convertDateBd($this->getDataFim());
            }
            }
            
            if (!empty($whereConditions)){
                $sql .= "WHERE " . implode(" AND ", $whereConditions) . " ";
        }
        
        $sql .= "ORDER BY P.EMISSAO ASC";
        
        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->execute($params);
        
        return $banco->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cancela uma cotação
     * 
     * @return bool true se cancelou com sucesso, false caso contrário
     */
    public function cancelaCotacao() {
        $sql = "UPDATE FAT_PEDIDO SET SITUACAO = '9' WHERE ID = :id";
        $params = [
            ':id' => $this->getId()
        ];
        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->execute($params);
        if ($banco->rowCount() > 0) {
            return true;
        } else {
            return 'Erro ao cancelar cotação!';
        }
    }
    

    ## fim cotação ##

    ## inicio itens da cotação ##

    /**
     * Inclui um item na cotação e retorna HTML atualizado via AJAX.
     * 
     * Usa os valores já setados no formulário.
     * Calcula automaticamente o próximo nrItem (contador sequencial) se não estiver setado.
     * Retorna o HTML atualizado da tabela de itens e totais.
     * 
     * @return void Retorna JSON com HTML atualizado
     */
    public function incluiItemCotacaoAjax() {
        $idCotacao = $this->getId();
        
        if (empty($idCotacao)) {
            if ($this->getEmissao() == '') {
                $this->setEmissao(date("d/m/Y"));
            }
            $this->setCentroCusto($this->m_empresacentrocusto);
            $this->setCentroCustoEntrega($this->m_empresacentrocusto);
            $this->setEspecie("D");
            $this->setIdNatop("1");
            $this->setSituacao('5');
            $idCotacao = $this->incluiPedido();
            $this->setId($idCotacao);
        }
        
        $this->setIdPedidoItem($idCotacao);
        
        $this->calculaESetaNrItem($idCotacao);
        
        $resultado = $this->incluiProduto();
        if ($resultado >= 0) {
            $this->retornaHtmlAjax($idCotacao);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => $resultado ? $resultado : 'Erro ao incluir item'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

     /**
     * Calcula e seta o próximo nrItem para o pedido.
     * 
     * Busca o último NRITEM do pedido e incrementa em 1.
     * Se não houver itens, começa do 1.
     * Só seta se o nrItem ainda não estiver definido.
     * 
     * @param int $idPedido ID do pedido/cotação
     * @return void
     */
    public function calculaESetaNrItem($idPedido) {
        if (empty($this->getNrItem())) {
            $result = $this->select_pedido_item_nrItem($idPedido);
            
            if(isset($result[0]['NRITEM']) && $result[0]['NRITEM'] > 0){
                $nrItem = $result[0]['NRITEM'];
                $nrItem += 1;
            } else {
                $nrItem = 1;
            }
            
            $this->setNrItem($nrItem);
        }
    }

    /**
     * Altera um item da cotação e retorna HTML atualizado via AJAX.
     * 
     * Usa os valores já setados no formulário.
     * Mantém o nrItem original do item.
     * Retorna o HTML atualizado da tabela de itens e totais.
     * 
     * @return void Retorna JSON com HTML atualizado
     */
    public function alteraItemCotacaoAjax() {
        $idCotacao = $this->getId();
        $nrItem = $this->getNrItem();
        
        if (empty($idCotacao) || empty($nrItem)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => 'ID da cotação ou número do item não informado'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $this->setIdPedidoItem($idCotacao);
        
        $resultado = $this->alteraProduto();
        
        if ($resultado === '') {
            $this->retornaHtmlAjax($idCotacao);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => $resultado
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Exclui um item da cotação e retorna HTML atualizado via AJAX.
     * 
     * Usa os valores já setados no formulário (ID e nrItem).
     * Retorna o HTML atualizado da tabela de itens e totais.
     * 
     * @return void Retorna JSON com HTML atualizado
     */
    public function excluiItemCotacaoAjax() {
        $idCotacao = $this->getId();
        $nrItem = $this->getNrItem();
        
        if (empty($idCotacao) || empty($nrItem)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => 'ID da cotação ou número do item não informado'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $banco = new c_banco_pdo();
        $sql = "DELETE FROM FAT_PEDIDO_ITEM WHERE ID = :idCotacao AND NRITEM = :nrItem";
        $banco->prepare($sql);
        $banco->execute([
            ':idCotacao' => $idCotacao,
            ':nrItem' => $nrItem
        ]);
        
        $resultado = '';
        if ($banco->rowCount() <= 0) {
            $resultado = 'Item não excluído!!!';
        }
        
        if ($resultado === '') {
            $this->retornaHtmlAjax($idCotacao);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'error' => $resultado
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Busca dados atualizados da cotação, renderiza templates e retorna JSON.
     * 
     * Função auxiliar reutilizável para retornar HTML atualizado após operações AJAX.
     * Busca itens, totais, atualiza no banco e renderiza templates.
     * 
     * @param int $idCotacao ID da cotação
     * @return void Retorna JSON com HTML atualizado
     */
    private function retornaHtmlAjax($idCotacao) {
        $this->setId($idCotacao);
        $this->setIdPedidoItem($idCotacao);
        
        $lancPesq = $this->select_pedido_item_id('1');
        
        $totalPecas = $this->select_produto_total();
        if (!is_numeric($totalPecas)) {
            $totalPecas = 0;
        }
        $this->setValorProduto($totalPecas, true);
        $this->alteraProdutoTotalPedido();
        
        $vlrDesconto = $this->select_desconto_produto_total();
        if (!is_numeric($vlrDesconto)) {
            $vlrDesconto = 0;
        }
        $this->setDesconto($vlrDesconto, true);
        $this->updateField("DESCONTO", $this->getDesconto(), "FAT_PEDIDO");
        
        $result = $this->select_pedido_total_geral();
        if (!is_numeric($result)) {
            $result = 0;
        }
        $this->setValorTotal($result, true);
        $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");
        
        $lanc = $this->select_pedido_parametros();

        $valorProduto = number_format($lanc[0]['TOTALPRODUTOS'], 2, ',', '.');
        $this->smarty->assign('valorProduto', $valorProduto);
        $valorDesconto = number_format($lanc[0]['DESCONTO'], 2, ',', '.');
        $this->smarty->assign('valorDesconto', $valorDesconto);
        $valorTotal = number_format($lanc[0]['TOTAL'], 2, ',', '.');
        $this->smarty->assign('valorTotal', $valorTotal);
        $vlrTotal =  $lanc[0]['TOTAL'];
        $vlrTotal = number_format($vlrTotal, 2, ',', '.');
        $this->smarty->assign('valorTotal', $vlrTotal);
        
        $this->smarty->assign('lancPesq', $lancPesq);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        
        $htmlTabela = $this->smarty->fetch('cotacao_tabela_itens.tpl');
        
        $htmlTotais = $this->smarty->fetch('cotacao_totais.tpl');
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'html' => $htmlTabela,
            'totais' => $htmlTotais,
            'id' => $idCotacao
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    ## inicio calculo markup ##

    /**
     * Atualiza o markup geral da cotação e recalcula o markup de cada item individualmente
     * 
     * Considera desconto, valor unitário, quantidade e custo de compra para calcular
     * o markup real de cada item.
     * 
     * @return string JSON com resultado da operação
     */
    public function atualizarMarkup() {
        
        if (empty($this->getId())) {
            return json_encode([
                'success' => false,
                'error' => 'ID da cotação não informado'
            ]);
        }
        
        // Atualiza o markup geral do pedido (cotação)
        $markupGeral = $this->getMarkup();
        $markupGeral = floatval($markupGeral);
        if ($markupGeral < 0) {
            $markupGeral = 0;
        }
        $this->updateField("MARKUP", $markupGeral, "FAT_PEDIDO");
        
        // Busca todos os itens da cotação
        $this->setIdPedidoItem($this->getId());
        $itens = $this->select_pedido_item_id('1');
        
        if (empty($itens)) {
            return json_encode([
                'success' => true,
                'message' => 'Nenhum item encontrado na cotação',
                'itensAtualizados' => 0
            ]);
        }
        
        $banco = new c_banco_pdo();
        $itensAtualizados = 0;
        
        // Processa cada item individualmente
        foreach ($itens as $item) {
            $nrItem = $item['NRITEM'];
            $custoCompra = floatval($item['CUSTOCOMPRA'] ?? 0);
            $quantidade = floatval($item['QTSOLICITADA'] ?? 0);
            $percDesconto = floatval($item['PERCDESCONTO'] ?? 0);
            $desconto = floatval($item['DESCONTO'] ?? 0);
            
            // Se não houver markup geral ou custo, pula o item
            if ($markupGeral <= 0 || $custoCompra <= 0 || $quantidade <= 0) {
                continue;
            }
            
            if ($markupGeral >= 100) {
                continue;
            }
            
            $markupDecimal = $markupGeral / 100;
            $divisor = 1 - $markupDecimal;
            
            if ($divisor <= 0) {
                continue;
            }
            //calculos 
            $novoValorUnitario = $custoCompra / $divisor;
            
            $totalItem = $novoValorUnitario * $quantidade;
            
            $novoDesconto = 0;
            if ($percDesconto > 0) {
                $novoDesconto = ($totalItem * $percDesconto) / 100;
            } else if ($desconto > 0) {
                $novoDesconto = $desconto;
            }
            
            $novoTotal = $totalItem - $novoDesconto;
            
            $markupItem = $markupGeral;
            
            $linhasAfetadas = $this->atualizarItemCotacao(
                $this->getId(),
                $nrItem,
                $novoValorUnitario,
                $novoTotal,
                $novoDesconto,
                $percDesconto,
                $markupItem
            );
            
            if ($linhasAfetadas > 0) {
                $itensAtualizados++;
            }
        }
        
        // Atualiza os totais da cotação após atualizar os itens
        if ($itensAtualizados > 0) {
            $this->setIdPedidoItem($this->getId());
            
            $totalPecas = $this->select_produto_total();
            if (!is_numeric($totalPecas)) {
                $totalPecas = 0;
            }
            $this->setValorProduto($totalPecas, true);
            $this->alteraProdutoTotalPedido();
            
            $vlrDesconto = $this->select_desconto_produto_total();
            if (!is_numeric($vlrDesconto)) {
                $vlrDesconto = 0;
            }
            $this->setDesconto($vlrDesconto, true);
            $this->updateField("DESCONTO", $this->getDesconto(), "FAT_PEDIDO");
            
            $result = $this->select_pedido_total_geral();
            if (!is_numeric($result)) {
                $result = 0;
            }
            $this->setValorTotal($result, true);
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");
        }
        
        return json_encode([
            'success' => true,
            'message' => 'Markup atualizado com sucesso',
            'markupGeral' => $markupGeral,
            'itensAtualizados' => $itensAtualizados,
            'totalItens' => count($itens)
        ]);
        exit;
    }
    
    /**
     * Atualiza os valores do markup de um item da cotação no banco de dados
     * 
     * @param int $idCotacao ID da cotação
     * @param int $nrItem Número do item
     * @param float $unitario Valor unitário do item
     * @param float $total Total do item
     * @param float $desconto Valor do desconto
     * @param float $percDesconto Percentual de desconto
     * @param float $markup Markup do item
     * @return int Número de linhas afetadas
     */
    private function atualizarItemCotacao($idCotacao, $nrItem, $unitario, $total, $desconto, $percDesconto, $markup) {
        $banco = new c_banco_pdo();
        $sql = "UPDATE FAT_PEDIDO_ITEM SET 
                UNITARIO = :unitario,
                TOTAL = :total,
                DESCONTO = :desconto,
                PERCDESCONTO = :percDesconto,
                MARKUP = :markup 
                WHERE ID = :idCotacao AND NRITEM = :nrItem";
        
        $banco->prepare($sql);
        $banco->execute([
            ':unitario' => $unitario,
            ':total' => $total,
            ':desconto' => $desconto,
            ':percDesconto' => $percDesconto,
            ':markup' => $markup,
            ':idCotacao' => $idCotacao,
            ':nrItem' => $nrItem
        ]);
        
        return $banco->rowCount();
    }

    
    ## fim calculo markup ##

    ## inicio aplicar percentual ##

    /**
     * Aplica percentual em todos os itens da cotação
     * 
     * @param float $percentual Percentual a ser aplicado (ex: 10 para 10%, -5 para reduzir 5%)
     * @return string Mensagem de sucesso ou erro
     */
    public function aplicarPercentualItens() {
        $idCotacao = $this->getId();
        
        if (empty($this->getId())) {
            return 'ID da cotação não informado';
        }
        
        if (empty($this->getPercentualAplicar())) {
            return 'Percentual não informado';
        }
        
        $this->setIdPedidoItem($this->getId());
        $itens = $this->select_pedido_todos_itens_id();
        
        if (empty($itens)) {
            return 'Nenhum item encontrado na cotação';
        }
        
        $banco = new c_banco_pdo();
        $itensAtualizados = 0;
        
        foreach ($itens as $item) {
            $nrItem = $item['NRITEM'];
            $valorUnitarioAtual = floatval($item['UNITARIO']);
            $quantidade = floatval($item['QTSOLICITADA']);
            $descontoAtual = floatval($item['DESCONTO']);
            
            // Calcula novo valor unitário com percentual
            $novoValorUnitario = $valorUnitarioAtual * (1 + ($this->getPercentualAplicar() / 100));
            
            $novoTotalItem = $novoValorUnitario * $quantidade;
            
            $novoDesconto = 0;
            $novoPercDesconto = 0;
            
            if ($descontoAtual > 0 && $item['TOTAL'] > 0) {
                // Mantém a proporção do desconto
                $percDescontoAtual = ($descontoAtual / $item['TOTAL']) * 100;
                $novoPercDesconto = $percDescontoAtual;
                $novoDesconto = ($novoTotalItem * $percDescontoAtual) / 100;
            }
            
            // Total final com desconto
            $totalFinal = $novoTotalItem - $novoDesconto;
            
            // Atualiza o item no banco
            $sql = "UPDATE FAT_PEDIDO_ITEM SET 
                    UNITARIO = :unitario,
                    TOTAL = :total,
                    DESCONTO = :desconto,
                    PERCDESCONTO = :percDesconto
                    WHERE ID = :idCotacao AND NRITEM = :nrItem";
            
            $banco->prepare($sql);
            $banco->execute([
                ':unitario' => $novoValorUnitario,
                ':total' => $totalFinal,
                ':desconto' => $novoDesconto,
                ':percDesconto' => $novoPercDesconto,
                ':idCotacao' => $idCotacao,
                ':nrItem' => $nrItem
            ]);
            
            if ($banco->rowCount() > 0) {
                $itensAtualizados++;
            }
        }
        
        $this->setId($this->getId());
        $this->setIdPedidoItem($this->getId());
        
        $totalPecas = $this->select_produto_total();
        if (!is_numeric($totalPecas)) {
            $totalPecas = 0;
        }
        $this->setValorProduto($totalPecas, true);
        $this->alteraProdutoTotalPedido();
        
        $vlrDesconto = $this->select_desconto_produto_total();
        if (!is_numeric($vlrDesconto)) {
            $vlrDesconto = 0;
        }
        $this->setDesconto($vlrDesconto, true);
        $this->updateField("DESCONTO", $this->getDesconto(), "FAT_PEDIDO");
        
        $result = $this->select_pedido_total_geral();
        if (!is_numeric($result)) {
            $result = 0;
        }
        $this->setValorTotal($result, true);
        $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");
        
        return true;
    }

    ## fim aplicar percentual ##

    ## inicio cadastrar itens copiar e colar ##
    /**
     * Processa e adiciona itens em lote através da funcionalidade copiar e colar
     * 
     * Cria a cotação se não existir, processa a lista de itens e adiciona todos os produtos.
     * Atualiza os totais da cotação após adicionar os itens.
     * 
     * @return bool|string Retorna true em caso de sucesso ou mensagem de erro
     */
    public function cadastrarItensCopiarColar() {
        if (empty($this->getItensPedidoCC())) {
            return 'Nenhum item informado para adicionar';
        }

        // Cria a cotação se não existir
        if (empty($this->getId())){
            if ($this->getEmissao() == '') {
                $this->setEmissao(date("d/m/Y"));
            }
            $this->setCentroCusto($this->m_empresacentrocusto);
            $this->setCentroCustoEntrega($this->m_empresacentrocusto);
            $this->setEspecie("D");
            $this->setIdNatop("1");
            $this->setSituacao('5');
            $id = $this->incluiPedido();
            if($id > 0){
                $this->setId($id);
            } else {
                return 'Erro ao criar a cotação';
            }
        }
        
        $item = explode("|", $this->getItensPedidoCC());
        $objProduto = new c_produto();
        $itensAdicionados = 0;
        
        for ($i=0; $i<count($item); $i++){
            $itemQuant = explode("*", $item[$i]);
            $codProduto = isset($itemQuant[0]) ? $itemQuant[0] : '';
            
            if (empty($codProduto)) {
                continue;
            }
            
            $codNota = $codProduto;
            
            if(isset($itemQuant[5]) && strlen($itemQuant[5]) > 6){
                $number = explode(",", ($itemQuant[5]));
                $newNumber = str_replace('.', '', $number[0]);
                $quant = $newNumber.".".$number[1];
            }else{
                $quant = isset($itemQuant[5]) ? str_replace(',', '.', $itemQuant[5]) : '1';
            }
            
            $quantDigitada = floatval($quant);
            
            if ($quantDigitada <= 0) {
                continue;
            }
            
            $objProduto->setId($codProduto);
            $arrProduto = $objProduto->select_produto();
            
            if (!empty($arrProduto) && isset($arrProduto[0])){
                $this->setIdPedidoItem($this->getId());
                $this->setNrItem('');
                $this->calculaESetaNrItem($this->getId());
                
                $this->setCodFabricante($arrProduto[0]['CODFABRICANTE']);
                $this->setCodProduto($codProduto);
                $this->setCodProdutoNota($codNota);
                $this->setQuantidadeProduto($quantDigitada, true);
                $this->setUnidadeProduto($arrProduto[0]['UNIDADE']);
                $this->setDescricaoProduto($arrProduto[0]['DESCRICAO']);
                
                $valorUnitario = isset($itemQuant[4]) ? str_replace(',', '.', $itemQuant[4]) : $arrProduto[0]['VENDA'];
                if(empty($valorUnitario) || $valorUnitario == 0){
                    $valorUnitario = $arrProduto[0]['VENDA'];
                }
                $this->setVlrUnitarioProduto($valorUnitario, true);
                
                $this->setVlrCustoProduto($arrProduto[0]['CUSTOCOMPRA'], true);
                $this->setDescontoProduto('0', true);
                $this->setPercDescontoProduto('0', true);
                
                $totalItem = floatval($valorUnitario) * $quantDigitada;
                $this->setTotalProduto($totalItem, true);
                
                $resultado = $this->incluiProduto();
                if ($resultado >= 0) {
                    $itensAdicionados++;
                }
            }
        }
        
        if ($itensAdicionados == 0) {
            return 'Nenhum item foi adicionado. Verifique os códigos dos produtos.';
        }
        
        $this->setIdPedidoItem($this->getId());
        $totalPecas = $this->select_produto_total();
        if (!is_numeric($totalPecas)) { $totalPecas = 0; }
        $this->setValorProduto($totalPecas, true);
        $this->alteraProdutoTotalPedido();
        
        $vlrDesconto = $this->select_desconto_produto_total();
        if (!is_numeric($vlrDesconto)) { $vlrDesconto = 0; }
        $this->setDesconto($vlrDesconto, true);
        $this->updateField("DESCONTO", $this->getDesconto(), "FAT_PEDIDO");
        
        $result = $this->select_pedido_total_geral();
        if (!is_numeric($result)) { $result = 0; }
        $this->setValorTotal($result, true);
        $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");
        
        return true;
    }

    /**
     * Pesquisa produto por código ou código fabricante
     * @param string $desc Código do produto
     * @return array Resultado da pesquisa
     */
    public function pesquisaProdCod($desc){
        $banco = new c_banco_pdo();
        $sql = "SELECT * FROM EST_PRODUTO WHERE (CODIGO = :codigo) OR (CODFABRICANTE = :codigo)";
        $banco->prepare($sql);
        $banco->execute([':codigo' => $desc]);
        $result = $banco->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Pesquisa produto por descrição
     * @param string $desc Descrição do produto
     * @return array Resultado da pesquisa
     */
    public function pesquisaProdDesc($desc){
        $banco = new c_banco_pdo();
        $sql = "SELECT * FROM EST_PRODUTO WHERE DESCRICAO LIKE :descricao LIMIT 10";
        $banco->prepare($sql);
        $banco->execute([':descricao' => '%' . $desc . '%']);
        $result = $banco->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Processa a funcionalidade de copiar e colar produtos
     * 
     * Processa a lista de produtos colada pelo usuário e retorna os produtos encontrados
     * para exibição no modal. Suporta diferentes formatos de entrada (código, código+quantidade, código+descrição).
     */
    public function copiarEcolar(){
        $desc = $this->getDescCc();
        $desc = !empty($desc) ? preg_split('/\r\n|\r|\n/', $desc) : array();

        $listaProd = NULL;
        $msg_cc_modal = '';
        
        for($i=0; $i < count($desc); $i++){
            $linha = trim($desc[$i]);
            
            if (empty($linha)) {
                continue;
            }
            
            $busca = '';
            $qtde = '1'; // Quantidade padrão é 1
            
            // Separa por " - " (espaço, hífen, espaço)
            $partes = explode(' - ', $linha);
            
            if (count($partes) >= 2) {
                // Tem quantidade informada
                $busca = trim($partes[0]);
                $qtde = trim($partes[1]);
            } else {
                // Tenta separar por hífen simples
                $partes = explode('-', $linha);
                if (count($partes) >= 2) {
                    $busca = trim($partes[0]);
                    $qtde = trim($partes[1]);
                } else {
                    // Não tem hífen, assume que é só código/descrição
                    $busca = trim($linha);
                    $qtde = '1'; // Quantidade padrão
                }
            }
            
            // Valida se tem código/descrição
            if (empty($busca)) {
                if ($msg_cc_modal != ''){
                    $msg_cc_modal = "Linha: " . $linha . " | " . $msg_cc_modal;
                } else {
                    $msg_cc_modal = "Linha: " . $linha;
                }
                continue;
            }
            
            if (empty($qtde)) {
                $qtde = '1';
            }
            
            $r = $this->pesquisaProdCod($busca);
            
            if (empty($r) || !isset($r[0])) {
                $r = $this->pesquisaProdDesc($busca);
            }
            
            if(!empty($r) && isset($r[0])){
                $produto = array(
                    'CODIGO'    => $r[0]['CODIGO'],
                    'DESCRICAO' => $r[0]['DESCRICAO'],
                    'GRUPO'     => isset($r[0]['GRUPO']) ? $r[0]['GRUPO'] : '',
                    'UNIDADE'   => isset($r[0]['UNIDADE']) ? $r[0]['UNIDADE'] : '',
                    'VENDA'     => isset($r[0]['VENDA']) ? $r[0]['VENDA'] : '0,00',
                    'QUANT'     => $qtde
                );
                
                if($listaProd != NULL && isset($listaProd[0])){
                    array_push($listaProd, $produto);
                }else{
                    $listaProd = array();
                    $listaProd[0] = $produto;
                }
            }else{
                if ($msg_cc_modal != ''){
                    $msg_cc_modal = $busca . " | " . $msg_cc_modal;
                } else {
                    $msg_cc_modal = $busca;
                }
            }
        }

        $this->smarty->assign('msg_cc_modal', $msg_cc_modal); 
        $this->smarty->assign('lancCCModal', $listaProd); 
        
    }


    ## fim cadastrar itens copiar e colar ##

    ## inicio  email ##
    
    /**
     * Busca email do cliente da cotação
     * 
     * @param int $idCliente ID do cliente
     * @return string|false Email do cliente ou false se não encontrado
     */
    public function buscaEmailCliente($idCliente) {
        if (empty($idCliente)) {
            return false;
        }
        
        $banco = new c_banco_pdo();
        $sql = "SELECT EMAIL FROM FIN_CLIENTE WHERE CLIENTE = :cliente";
        $banco->prepare($sql);
        $banco->execute([':cliente' => $idCliente]);
        $clienteData = $banco->fetch(PDO::FETCH_ASSOC);
        
        if (!empty($clienteData['EMAIL'])) {
            return $clienteData['EMAIL'];
        }
        
        return false;
    }

    /**
     * Prepara dados da cotação para impressão/PDF
     * 
     * Busca todos os dados necessários (cotação, itens, empresa, condição de pagamento)
     * e retorna em um array organizado.
     * 
     * @return array|false Array com dados preparados ou false em caso de erro
     */
    public function preparaDadosImpressao() {
        if (empty($this->getId())) {
            return false;
        }

        $banco = new c_banco_pdo();
        $sql = "SELECT P.*, 
                       C.NOME, C.CNPJCPF, C.PESSOA, C.ENDERECO, C.NUMERO, C.COMPLEMENTO, 
                       C.BAIRRO, C.CIDADE, C.UF, C.CEP, C.FONE, C.EMAIL, C.INSCESTRG,
                       CP.DESCRICAO AS DESCCONDPGTO
                FROM FAT_PEDIDO P 
                LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = P.CLIENTE)
                LEFT JOIN FAT_COND_PGTO CP ON (CP.ID = P.CONDPG)
                WHERE P.ID = :id AND P.SITUACAO = '5'";
        
        $banco->prepare($sql);
        $banco->execute([':id' => $this->getId()]);
        $lanc = $banco->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($lanc)) {
            return false;
        }

        // Busca itens da cotação
        $this->setIdPedidoItem($this->getId());
        $lancItem = $this->select_pedido_item_id('1');
        
        // Busca dados da empresa
        $ccusto = !empty($lanc[0]['CCUSTO']) ? $lanc[0]['CCUSTO'] : $this->m_empresacentrocusto;
        $sqlEmpresa = "SELECT * FROM AMB_EMPRESA WHERE CENTROCUSTO = :ccusto";
        $banco->prepare($sqlEmpresa);
        $banco->execute([':ccusto' => $ccusto]);
        $empresa = $banco->fetchAll(PDO::FETCH_ASSOC);
        
        // Se não encontrar, usa dados básicos da empresa
        if (empty($empresa)) {
            $empresa = array(
                array(
                    'NOME' => $this->m_empresanome,
                    'FANTASIA' => $this->m_empresafantasia,
                    'NOMEEMPRESA' => $this->m_empresanome,
                    'NOMEFANTASIA' => $this->m_empresafantasia,
                    'CNPJ' => '',
                    'ENDERECO' => '',
                    'CIDADE' => '',
                    'UF' => '',
                    'CEP' => '',
                    'TELEFONE' => ''
                )
            );
        }
        
        $descCondPgto = !empty($lanc[0]['DESCCONDPGTO']);
        if (empty($descCondPgto)) {
            $condPgto = new c_cond_pgto();
            $condPgto->setId($lanc[0]['CONDPG']);
            $descPgto = $condPgto->selectCondPgto();
            if (!empty($descPgto[0]['DESCRICAO'])) {
                $descCondPgto = $descPgto[0]['DESCRICAO'];
            }
        }

        return array(
            'lanc' => $lanc,
            'lancItem' => $lancItem,
            'empresa' => $empresa,
            'descCondPgto' => $descCondPgto,
            'idCliente' => $lanc[0]['CLIENTE']
        );
    }

    /**
     * Gera HTML do template de impressão da cotação
     * 
     * Prepara o Smarty com todos os dados e retorna o HTML renderizado
     * do template de impressão.
     * 
     * @param array $dados Dados preparados pelo método preparaDadosImpressao()
     * @return string|false HTML renderizado ou false em caso de erro
     */
    public function geraHtmlImpressao($dados) {
        if (empty($dados)) {
            return false;
        }

        // Configura Smarty para template de impressão
        // Usa ADMraizFonte onde estão os templates do sistema
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // Prepara dados para o template simples de email
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        $this->smarty->assign('pathImagem', "images");
        $this->smarty->assign('prazoEntrega', $dados['lanc'][0]['PRAZOENTREGA']);
        $this->smarty->assign('descCondPgto', $dados['descCondPgto']);
        $this->smarty->assign('empresa', $dados['empresa']);
        $this->smarty->assign('pedido', $dados['lanc']);
        $this->smarty->assign('pedidoItem', $dados['lancItem']);

        try {
            $html = $this->smarty->fetch('cotacao_email_pdf.tpl');
            if (empty($html)) {
                error_log("Erro ao gerar HTML: Template retornou vazio");
                return false;
            }
            return $html;
        } catch (Exception $e) {
            error_log("Erro ao gerar HTML de impressão: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Gera PDF da cotação a partir do HTML
     * 
     * @param string $html HTML renderizado do template
     * @param string $filename Caminho completo onde salvar o PDF
     * @return bool True se gerado com sucesso, false caso contrário
     */
    public function geraPdfCotacao($html, $filename) {
        if (empty($html) || empty($filename)) {
            return false;
        }

        try {
            // Cria diretório se não existir
            $dirPdf = dirname($filename);
            if (!is_dir($dirPdf)) {
                mkdir($dirPdf, 0777, true);
            }

            // Gera PDF usando Dompdf
            $dir = dirname(__FILE__);
            require_once($dir . "/../../bib/dompdf/lib/html5lib/Parser.php");
            require_once($dir . "/../../bib/dompdf/lib/php-font-lib-master/src/FontLib/Autoloader.php");
            require_once($dir . "/../../bib/dompdf/lib/php-svg-lib-master/src/autoload.php");
            require_once($dir . "/../../bib/dompdf/src/Autoloader.php");
            
            Dompdf\Autoloader::register();

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', false);
            $options->set('chroot', ADMraizCliente);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            file_put_contents($filename, $dompdf->output());
            chmod($filename, 0777);
            
            return true;
        } catch (Exception $e) {
            error_log("Erro ao gerar PDF: " . $e->getMessage());
            return false;
        }
    }


     /**
     * Gera e faz download do PDF da cotação
     * 
     * Utiliza os métodos da classe c_cotacao para preparar dados e gerar PDF.
     * 
     * @return void Retorna PDF para download
     */
    function downloadPdfCotacao()
    {
        try {
            $idCotacao = $this->getId();
            
            if (empty($idCotacao)) {
                echo "Erro: ID da cotação não informado.";
                exit;
            }

            // Prepara dados usando método da classe
            $dados = $this->preparaDadosImpressao();
            
            if ($dados === false) {
                echo "Erro: Cotação não encontrada ou erro ao preparar dados.";
                exit;
            }

            // Gera HTML usando método da classe
            $html = $this->geraHtmlImpressao($dados);
            
            if (empty($html)) {
                echo "Erro: Erro ao gerar HTML da cotação.";
                exit;
            }

            // Prepara caminho do PDF
            $dirPdf = ADMraizCliente."/images/doc";
            if (!is_dir($dirPdf)) {
                mkdir($dirPdf, 0777, true);
            }
            $filename = $dirPdf."/cotacao".$idCotacao.".pdf";
            
            // Gera PDF usando método da classe
            if (!$this->geraPdfCotacao($html, $filename)) {
                echo "Erro: Erro ao gerar PDF da cotação.";
                exit;
            }

            // Verifica se o arquivo foi criado
            if (!file_exists($filename)) {
                echo "Erro: Arquivo PDF não foi criado.";
                exit;
            }

            // Define headers para download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="Cotacao_'.$idCotacao.'.pdf"');
            header('Content-Length: ' . filesize($filename));
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            // Envia o arquivo
            readfile($filename);

            // Remove arquivo PDF temporário após o download
            if (file_exists($filename)) {
                unlink($filename);
            }

            exit;

        } catch (Exception $e) {
            echo "Erro ao gerar PDF: " . $e->getMessage();
            exit;
        }
    }

    /**
     * Envia email com PDF da cotação para o cliente
     * 
     * Utiliza os métodos da classe c_cotacao para preparar dados,
     * gerar PDF e enviar por email.
     * 
     * @return void Retorna JSON com resultado do envio
     */
    function enviarEmailCotacao()
    {
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $idCotacao = $this->getId();
            
            if (empty($idCotacao)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID da cotação não informado.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Prepara dados usando método da classe
            $dados = $this->preparaDadosImpressao();
            
            if ($dados === false) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Cotação não encontrada ou erro ao preparar dados.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Busca email do cliente usando método da classe
            $clienteEmail = $this->buscaEmailCliente($dados['idCliente']);
            
            if (empty($clienteEmail)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Email do cliente não cadastrado ou inválido.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Gera HTML usando método da classe
            $html = $this->geraHtmlImpressao($dados);
            
            if (empty($html)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao gerar HTML da cotação.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Prepara caminho do PDF
            $dirPdf = ADMraizCliente."/images/doc";
            $filename = $dirPdf."/cotacao".$idCotacao.".pdf";
            
            // Gera PDF usando método da classe
            if (!$this->geraPdfCotacao($html, $filename)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao gerar PDF da cotação.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Prepara corpo do email
            $assunto = $this->m_empresafantasia." - Cotação Nº ".$idCotacao;
            // Codifica o assunto para MIME header (necessário para caracteres especiais como ç, ã, etc)
            // Usa encoding Q (quoted-printable) que é mais compatível
            $assunto = mb_encode_mimeheader($assunto, 'UTF-8', 'Q');
            
            $emailCorpo = "Prezado(a) Cliente, \n \n".
                "Estamos encaminhando a Cotação Nº ".$idCotacao." no formato PDF.\n \n".
                "Agradecemos, \n".
                $this->m_usernome ." - ".$this->m_empresanome;

            // Verifica configurações de email
            if (empty($this->m_configsmtp) || empty($this->m_configemail) || empty($this->m_configemailsenha)) {
                if (file_exists($filename)) {
                    unlink($filename);
                }
                echo json_encode([
                    'success' => false,
                    'message' => 'Configurações de email do usuário não estão completas.'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Envia email
            $mail = new admMail();
            // Codifica o nome do remetente também
            $nomeRemetente = mb_encode_mimeheader("Email Cotação PDF", 'UTF-8', 'Q');
            $resp = $mail->SendMail(
                $this->m_configsmtp, 
                $this->m_configemail, 
                $nomeRemetente, 
                $this->m_configemailsenha, 
                $emailCorpo, 
                $assunto, 
                $clienteEmail, 
                "", 
                $this->m_configemail, 
                "", 
                $filename, 
                $filename
            );

            // Remove arquivo PDF temporário
            if (file_exists($filename)) {
                unlink($filename);
            }

            // Verifica resultado do envio
            if (strstr($resp, 'não') || strstr($resp, 'erro')) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erro ao enviar email: ' . $resp
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode([
                'success' => true,
                'message' => 'Email enviado com sucesso!'
            ], JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao enviar email: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    ## fim email ##

} // END OF THE CLASS

?>

