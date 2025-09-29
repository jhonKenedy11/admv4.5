<?php
/**
 * @package   astecv3
 * @name      c_pedido_venda_tools
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      28/06/2017
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../class/ped/c_ordem_compra.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class 
Class c_ordemCompraTools extends c_ordemCompra {

   


/**
* METODOS DE SETS E GETS
*/

//############### FIM SETS E GETS ###############


/**
 * <b> Rotina que gera novo pedido e inclui itens selecionados no pedido </b>
 * @name incluiItensPedidoControle
 * @param VARCHAR condPgto
 * @param int total
 * @return Matriz com as datas de vencimento e valores de cada parcela.
 */
public function incluiItensPedidoControle($cc, &$idPedido, $itensPedido, $itensQtde, $desconto, $tipoMsg=null, $cliente=null, $natOp = null ){
    try{
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$cc);
        $parametros->close_connection();
        
        // Caso não existir numero de id de pedidos, cadastro de pedido e setar no id
        if (empty($idPedido)){
            $this->setCliente($cliente);
            $this->setSituacao(0);
            $this->setEmissao(date("d/m/Y"));
            $this->setAtendimento(date("d/m/Y"));
            $this->setHoraEmissao(date("H:i:s"));
            $this->setEspecie("D");
            $this->setCentroCusto($cc);
            $this->setIdNatop($natOp);
            $idPedido = $this->incluiPedido();
        }
        // cadastra itens selecionados.
        // m_itensPedido -> contem todos os itens checados
        $msg = "";
        $this->setId($idPedido);
        if ($itensPedido != ""){
            $item = explode("|", $itensPedido);
            $objProduto = new c_produto();
            $objProdutoQtde = new c_produto_estoque();
            for ($i=0;$i<count($item);$i++){
                $quantDigitada = $itensQtde; // quant em digitacao
                $quantPedido = 0;
                $quantTotal = $quantDigitada;
                // verifica se produto existe na tabela pedido item.
                // verificar se existe o item no pedido
                $this->setItemEstoque($item[$i]);
                $arrItemPedido = $this->select_pedido_item_id_itemestoque();
                if (is_array($arrItemPedido)):
                    $quantPedido = $arrItemPedido[0]['QTSOLICITADA']; // quant já cadastrada
                    $quantTotal = $quantDigitada + $quantPedido;
                    $this->pedido_venda_item(false, $arrItemPedido);
                endif;
                // Consluta na table de produtos para pegar os dados
                $objProduto->setId($item[$i]); // CODIGO PRODUTO
                $arrProduto = $objProdutoQtde->produtoQtdePreco(NULL, $cc, $objProduto->getId());
                $uniFrac = $arrProduto[0]['UNIFRACIONADA'];
                $ifControlaEstoque = (($controlaEstoque == 'S') && ($uniFrac == 'N'));

                //if (($controlaEstoque == 'N') or (($quantDigitada <= $arrProduto[0]['QUANTIDADE']) AND
                if ((!$ifControlaEstoque) or (($quantDigitada <= $arrProduto[0]['QUANTIDADE']) AND
                    (floatval($arrProduto[0]['VENDA']) > floatval(0)))): // TESTA PRECO E QUANT DISPONIVEL
                    if ((floatval($arrProduto[0]['PROMOCAO']) >floatval(0)) and 
                        ($quantTotal > $arrProduto[0]['QUANTLIMITE'])): // TESTA MAXIMO VENDA PROMOCAO
                        $msg .= $arrProduto[0]['DESCRICAO']." Quantidade acima limite promoção - Quant:".$arrProduto[0]['QUANTLIMITE']."<br>";
                    else:
                        //$this->setItemEstoque($item[$i]);
                        $this->setItemFabricante($arrProduto[0]['CODFABRICANTE']);
                        $this->setQtSolicitada($quantTotal);
                        if (floatval($arrProduto[0]['PROMOCAO']) >floatval(0)):
                            $this->setUnitario(str_replace('.', ',', $arrProduto[0]['PROMOCAO']));
                        else:
                            $this->setUnitario(str_replace('.', ',', $arrProduto[0]['VENDA']));
                        endif;    
                        $this->setPrecoPromocao(str_replace('.', ',', $arrProduto[0]['PROMOCAO']));
                        $this->setVlrTabela(str_replace('.', ',', $arrProduto[0]['VENDA']));
                        $this->setDesconto($desconto);
                        $this->setTotalItem();
                        $this->setGrupoEstoque($arrProduto[0]['GRUPO']);
                        $this->setDescricaoItem($arrProduto[0]['DESCRICAO']);
                        if (is_array($arrItemPedido)):
                            $this->alteraPedidoItem();
                        else:
                            //pegar o ultimo NrItem do pedido
                            $ultimoNrItem = $this->select_pedidoVenda_item_max_nritem();
                            $this->setNrItem($ultimoNrItem[0]['MAXNRITEM']+1);
                            $this->IncluiPedidoItem();
                        endif;
                        // reserva produto
                        if ($ifControlaEstoque) :
                            $objProdutoQtde->produtoReserva($cc, "PED", 
                                    $this->getId(), $this->getItemEstoque(), $quantDigitada);
                            
                        endif;
                    endif;  
                else:
                    $msg .= $arrProduto[0]['DESCRICAO']." Preço ou Quantidade não disponivel<br>";
                endif;

                // atualiza total
                $this->setTotal($this->select_totalPedido());
                $this->setCliente($cliente);
                $this->setPedido(0);
                $this->setSituacao(0);

                $this->alteraPedidoTotal();

            }
            $tipoMsg = "sucesso";
        }
        else{
            $msg = "Selecione um Produto para compra"; 
            $tipoMsg = "erro";
        }
        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não cadastrado " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não cadastrado " );

    }
                    
} // fim incluiItensPedidoControle

/**
 * <b> Rotina que gera novo pedido e inclui itens selecionados no pedido </b>
 * @name incluiItensPedidoControle
 * @param VARCHAR condPgto
 * @param int total
 * @return Matriz com as datas de vencimento e valores de cada parcela.
 */
public function excluiItensOrdemCompraControle($cc, $idPedido, $idItem, $tipoMsg=null ){
    try{
        $tipoMsg = "sucesso";
        $msg ="";

        $this->setId($idPedido);
        $this->setNrItem($idItem);
        $arrOrdemCompraItem = $this->select_ordem_compra_item_id_nritem();
        $this->setId($arrOrdemCompraItem[0]['ID']);
        $this->setItemEstoque($arrOrdemCompraItem[0]['ITEMESTOQUE']);
        $this->setQtSolicitada($arrOrdemCompraItem[0]['QTSOLICITADA']);
        
        $msg = $this->excluiOrdemCompraItem();
        
        // atualiza total
        $this->setTotal($this->select_ordem_compra_total());
        $this->setCliente($arrOrdemCompraItem[0]['CLIENTE']);
        $this->setPedido(0);
        $this->setSituacao(0);

        $this->alteraOrdemCompraTotal();
        
        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não excluido " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não excluido " );

    }
                    
}

public function incluiItensOrdemCompraControle($cc, &$idPedido, $itensPedido, $itensQtde, $desconto, $tipoMsg=null, $cliente=null, $natOp = null ){
    try{
        if (empty($idPedido)){
            $this->setCliente($cliente);
            $this->setSituacao(0);
            $this->setEmissao(date("d/m/Y"));
            $this->setCentroCusto($cc);
            $idPedido = $this->incluiOrdemCompra();
        }

        $msg = "";
        $this->setId($idPedido);
        $this->setOc($idPedido);
        if ($itensPedido != ""){
            $itens = explode("|", $itensPedido);
            $objProduto = new c_produto();
            $objProdutoQtde = new c_produto_estoque();
            for ($i=0;$i<count($itens);$i++){
                $item= explode("*", $itens[$i]);
                if (floatval($item[1]) > 0) {

                    $this->setItemEstoque($item[0]);
                    //formatação quantidade 
                    if(strlen($item[1]) > 6){
                        $number = explode(",", ($item[1]));
                        $newNumber = str_replace('.', '', $number[0]);
                        $quantTotal = $newNumber.".".$number[1];
                    }else{
                        $quantTotal = str_replace(',', '.',$item[1]);
                    }
                    $arrItemOrdemCompra = $this->select_ordem_compra_item_id_itemestoque();
                    if (is_array($arrItemOrdemCompra)):
                        $quantOrdemCompra = $arrItemOrdemCompra[0]['QTSOLICITADA']; // quant já cadastrada
                        //$quantTotal = str_replace('.', ',',$item[1]) + $quantOrdemCompra;
                        $quantTotal += $quantOrdemCompra;
                        $this->setNrItem($arrItemOrdemCompra[0]['NRITEM']);
                    endif;

                    $this->setQtSolicitada($quantTotal);
                    $this->setUnitario(str_replace('.', ',', $item[2]));
                    $this->setDesconto(0);
                    $this->setTotalItem(str_replace('.', ',', ($quantTotal * $item[2])));
                    
                    $arrItemEstoque = $this->select_ordem_compra_produto();
                    $this->setItemFabricante($arrItemEstoque[0]['ITEMFABRICANTE']);
                    $this->setDescricaoItem($arrItemEstoque[0]['DESCRICAO']);
                    //$this->setUnidade($arrItemEstoque[0]['UNIDADE']);            
                    if (is_array($arrItemOrdemCompra)):
                        $this->alteraOrdemCompraItem();
                    else:
                        //pegar o ultimo NrItem do pedido
                        $ultimoNrItem = $this->select_ordem_compra_item_max_nritem();
                        $this->setNrItem($ultimoNrItem[0]['MAXNRITEM']+1);
                        $this->IncluiOrdemCompraItem();
                    endif;                
                
                // atualiza total
                $this->setTotal($this->select_ordem_compra_total(), 'B');
                $this->setCliente($cliente);
                $this->setPedido(0);
                $this->setSituacao(0);

                $this->alteraOrdemCompraTotal();
                }    
                $tipoMsg = "sucesso";
            }
        }
        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não cadastrado " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não cadastrado " );

    }
                    
} 

public function produtoOrdemCompraQtdePreco($letra, $filial=NULL, $produto=NULL, $tipoConsulta = 'S') {
    $par = explode("|", $letra);
    $data = date("Y-m-d");
    $isWhere = false;

    switch ($tipoConsulta){
        case 'S': // PESQUISA UNIFICADA PRODUTO QUANTIDADE, SOMENTE PRODUTOS COM QUANTIDADE DE ESTOQUE
            $sql = "SELECT P.CODFABRICANTE, P.TIPOPROMOCAO, P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.UNIFRACIONADA, E.STATUS, P.QUANTLIMITE, P.VENDA, ";
            $sql .= "IF((P.UNIFRACIONADA = 'S'), ";
            $sql .= "((SELECT COALESCE(sum(quant),0) as quant FROM est_nota_fiscal_produto x INNER JOIN est_nota_fiscal y ON (y.id = x.idnf) WHERE (x.codproduto = E.CODPRODUTO) AND  (y.tipo='0') AND (y.centrocusto = ".$filial.")) - ";
            $sql .= "(SELECT COALESCE(sum(quant),0) as quant FROM est_nota_fiscal_produto x INNER JOIN est_nota_fiscal y ON (y.id = x.idnf) WHERE (x.codproduto = E.CODPRODUTO) AND  (y.tipo='1') AND (y.centrocusto = ".$filial.")) - ";
            $sql .= "(SELECT COALESCE(sum(qtsolicitada),0) as quant FROM FAT_PEDIDO_ITEM s INNER JOIN FAT_PEDIDO t ON (s.id = t.id) WHERE (s.ITEMESTOQUE = E.CODPRODUTO) AND  (t.situacao<>'9') AND (t.ccusto = ".$filial."))) ";
            $sql .= ", count(E.CODPRODUTO)) AS 'Quantidade', ";
            $sql .= "P.QUANTLIMITE, P.VENDA, ";
            $sql .= "IF(((P.INICIOPROMOCAO <= CURDATE()) and (P.FIMPROMOCAO >= CURDATE())), P.PRECOPROMOCAO, 0) as PROMOCAO ";
            $sql .= "FROM EST_PRODUTO_ESTOQUE E ";
            $sql .= "inner join EST_PRODUTO P ON (P.CODIGO = E.CODPRODUTO) ";
            $where = "WHERE (DATAFORALINHA is null) and (status=0) ";
            break;
        case 'N': // PESQUISA SEPARADA PRODUTO QUANTIDADE
            $sql = "SELECT P.CODFABRICANTE, P.TIPOPROMOCAO, P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.UNIFRACIONADA, 0 as STATUS, 0 AS 'Quantidade', P.QUANTLIMITE, P.VENDA, ";
            $sql .= "IF(((P.INICIOPROMOCAO <= CURDATE()) and (P.FIMPROMOCAO >= CURDATE())), P.PRECOPROMOCAO, 0) as PROMOCAO ";
            $sql .= "FROM EST_PRODUTO P ";
            $where = "WHERE (DATAFORALINHA is null) ";
            break;
        case 'P': // PRODUTOS SEM QUANTIDADE - PESQUISA TODOS OS PRODUTOS E BUSCA QUANTIDADE
            $sql = "SELECT P.CODFABRICANTE, P.TIPOPROMOCAO, P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.UNIFRACIONADA, 0 as STATUS, ";
            $sql .= "(SELECT count(E.CODPRODUTO) AS 'Quantidade' ";
            $sql .= "FROM EST_PRODUTO_ESTOQUE E WHERE (E.CODPRODUTO=P.CODIGO) AND (E.STATUS=0) ";
            $sql .= "GROUP BY E.CODPRODUTO) AS 'Quantidade', P.QUANTLIMITE, P.VENDA, ";
            $sql .= "IF(((P.INICIOPROMOCAO <= CURDATE()) and (P.FIMPROMOCAO >= CURDATE())), P.PRECOPROMOCAO, 0) as PROMOCAO ";
            $sql .= "FROM EST_PRODUTO P ";
            $where = "WHERE (DATAFORALINHA is null) ";
            break;
        default :
            $sql = "SELECT P.CODFABRICANTE, P.TIPOPROMOCAO, P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.UNIFRACIONADA, E.STATUS, count(E.CODPRODUTO) AS 'Quantidade', P.QUANTLIMITE, P.VENDA, ";
            $sql .= "IF(((P.INICIOPROMOCAO <= CURDATE()) and (P.FIMPROMOCAO >= CURDATE())), P.PRECOPROMOCAO, 0) as PROMOCAO ";
            $sql .= "FROM EST_PRODUTO_ESTOQUE E ";
            $sql .= "inner join EST_PRODUTO P ON (P.CODIGO = E.CODPRODUTO) ";
            $where = "WHERE (DATAFORALINHA is null) ";
            
    }//   endswitch;
    if (!empty($produto)){ // consulta somente um produto
        $where .= "AND (P.CODIGO=". $produto.") ";
    }
    else{
        $isWhere = true;
        if (!empty($par[0])){
            $where .= "and ((descricao like '" . $par[0] . "%') or (codfabricante like '" . $par[0] . "%')) ";
        }
        if (!empty($par[1])){
            $where .= "and (grupo = '".$par[1]."') ";
        }

        if (!empty($par[3])){
            $where .= "and (p.localizacao like '".$par[3]."%') ";
        }

        switch ($par[2]):
            case '0':
                $where .= "and ((iniciopromocao <= '".$data."') and (fimpromocao >= '".$data."') and (P.TIPOPROMOCAO='0'))";
                break;
            case '1':
                $where .= "and ((iniciopromocao <= '".$data."') and (fimpromocao >= '".$data."') and (P.TIPOPROMOCAO='1'))";
                break;
            case 'T':
                $where .= "and ((iniciopromocao <= '".$data."') and (fimpromocao >= '".$data."'))";
                break;
        endswitch;

        if ($tipoConsulta=='S'):// PESQUISA UNIFICADA PRODUTO QUANTIDADE, SOMENTE PRODUTOS COM QUANTIDADE DE ESTOQUE
            $where .= "GROUP BY E.CODPRODUTO";
        endif;
        $where .= " ORDER BY DESCRICAO";
    }

    $sql .= $where;
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

}	//	END OF THE CLASS
?>
