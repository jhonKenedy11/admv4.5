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
include_once($dir . "/../../class/coc/c_ordem_compra.php");
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
public function incluiItemOrdemCompra(&$idOrdemCompra, $itemOc){
    
    $this->setId($idOrdemCompra);
    $item = explode("|", $itemOc);

    $this->setId($idOrdemCompra);
    $this->setOc($idOrdemCompra);
    $nrItem = $this->select_ordem_compra_item_max_nritem();
    $this->setNrItem($nrItem[0]['MAXNRITEM']+1);
    $codProduto = $item[2] == '' ? 0 : $item[2];
    $this->setItemEstoque($codProduto);
    $this->setItemFabricante($item[3]);
    $this->setCodigoNota($item[4]);
    $this->setDescricaoItem($item[5]);
    $this->setUnidade($item[6]);
    $this->setQtSolicitada($item[7]);
    $this->setUnitario($item[8]);
    $this->setPercDescontoItem($item[9]);
    $this->setDescontoItem($item[10]);
    $this->setTotalItem($item[11]);

    $this->incluiOrdemCompraItem();
       
    return '';
                    
} // fim incluiItemOrdemCompra

/**
 * <b> Rotina que gera novo pedido e inclui itens selecionados no pedido </b>
 * @name incluiItensPedidoControle
 * @param VARCHAR condPgto
 * @param int total
 * @return Matriz com as datas de vencimento e valores de cada parcela.
 */
public function alteraItemOrdemCompra(&$idOrdemCompra, $itemOc){
    
    $this->setId($idOrdemCompra);
    $item = explode("|", $itemOc);

    $this->setId($idOrdemCompra);
    $this->setOc($idOrdemCompra);
    $this->setNrItem($item[13]);
    $this->setItemEstoque($item[2]);
    $this->setItemFabricante($item[3]);
    $this->setCodigoNota($item[4]);
    $this->setDescricaoItem($item[5]);
    $this->setUnidade($item[6]);
    $this->setQtSolicitada($item[7]);
    $this->setUnitario($item[8]);
    $this->setPercDescontoItem($item[9]);
    $this->setDescontoItem($item[10]);
    $this->setTotalItem($item[11]);

    $this->alteraOrdemCompraItem();
       
    return '';
                    
} // fim incluiItemOrdemCompra

/**
 * <b> Rotina que gera novo pedido e inclui itens selecionados no pedido </b>
 * @name excluiItensOrdemCompraControle
 * @param INT idOrdemCompra
 * @param INT nrItem
 * @param int total
 * @return vazio .
 */
public function excluiItensOrdemCompraControle($idOrdemCompra, $nrItem){
        $this->setId($idOrdemCompra);
        $this->setNrItem($nrItem);
        $this->excluiOrdemCompraItem();
        return '';
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

public function calculaParcelasNfe($condPgto = NULL, $total = 0, $acrescentarParcela = 0, $bonus = 0, $dataemissao = null){
    if (empty($dataemissao)){
        $database = date("Y-m-d");
    } else {
        $database = date("Y-m-d", strtotime(str_replace('/','-',$dataemissao)));
    }
    
    $consulta = new c_banco();
    $sql = "select PARCELA, VENCIMENTO, TOTAL as VALOR, SITPGTO ,IF (SITPGTO = 'B', 'BAIXADO', '') AS SITPAG FROM FIN_LANCAMENTO WHERE DOCTO = '".$this->getId()."' AND ORIGEM = 'COC' AND SITPGTO = 'B'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $parcsBaixada = $consulta->resultado ?? [];
    $totalParcs = 0;
    foreach($parcsBaixada as $key => $value){
        $totalParcs += $value['VALOR'];
    }
    $totalNumParcelas = 0;

    //setlocale(LC_MONETARY, 'en_US');
    $descCondPgto = str_replace('DIAS', '', $condPgto);
    $parcelas = explode("/", $condPgto);
    $numParcelas = count($parcelas);
    $total = str_replace('.', '', $total);
    $total = str_replace(',', '.', $total);
    $totalGeral = $total - $bonus;
    //diminui o valor das parcelas pagas
    if ($totalParcs > 0){
        $totalGeral -= $totalParcs;
    }
    if ($totalGeral > 0 ) {
    //$valorParcela = money_format('%i', $totalGeral / $numParcelas);
    //$valorParcela =  str_replace(number_format(($totalGeral / $numParcelas),2),',','');
    $valorParcela =  round($totalGeral / $numParcelas, 2, PHP_ROUND_HALF_DOWN); 
    if ($acrescentarParcela > 0 ){
        $totalNumParcelas += $acrescentarParcela;
    }
    if ($bonus > 0){
        $totalNumParcelas += 1;        
    }
    $totalNumParcelas += $numParcelas;
    if ($totalGeral == 0){
        $totalNumParcelas = 1;        
    }
    
    for ($i = 0; $i < $totalNumParcelas; $i++) {
        if ( ($i == 0) and ($bonus > 0) ) {
            $lanc[$i]['PARCELA'] = $i + 1;
            $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime( $database . "  + ".intval(0)." day"));
            $lanc[$i]['VALOR'] = $bonus;  
            $lanc[$i]['TIPODOCTO_ID'] = 'N';
        } else if ($i <= $numParcelas) {
            $lanc[$i]['PARCELA'] = $i + 1;
            $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime( $database . "  + ".intval($parcelas[$i])." day"));
            $lanc[$i]['VALOR'] = $valorParcela; 
            $lanc[$i]['TIPODOCTO_ID'] = '';   
        } else {
            $lanc[$i]['PARCELA'] = $i + 1;
            $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime( $database . "  + ".intval($parcelas[$numParcelas - 1])." day"));
            $lanc[$i]['VALOR'] = 0;    
            $lanc[$i]['TIPODOCTO_ID'] = '';
        }
    }

    //$lanc[0]['VALOR'] = $valorParcela - (($valorParcela * $numParcelas) - doubleval($totalGeral));
    if (($valorParcela * $numParcelas) < doubleval($totalGeral)){
        $dif = (doubleval($totalGeral) - ($valorParcela * $numParcelas)) ;
        $lanc[$totalNumParcelas - 1]['VALOR'] +=  $dif;    
    }else if (($valorParcela * $numParcelas) > doubleval($totalGeral)){
        $dif = (($valorParcela * $numParcelas) - doubleval($totalGeral)) ;
        $lanc[$totalNumParcelas - 1]['VALOR'] -=  $dif;    
    }    
    //$lanc[0]['VALOR'] = str_replace(".", ",",$lanc[0]['VALOR']);
    //return $lanc;
    } else if ($bonus > 0) {
        $i = 0;
        $lanc[$i]['PARCELA'] = $i + 1;
        $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime( $database . "  + ".intval(0)." day"));
        $lanc[$i]['VALOR'] = $bonus;  
        $lanc[$i]['TIPODOCTO_ID'] = 'N';
    }
    
    $newLanc[] = '';
    $count = 0;
   
    for($k=0; $k < count($parcsBaixada); $k++){
        if($newLanc[0] == ''){
            $newLanc[$k] = $parcsBaixada[$k];
        }else{
            array_push($newLanc, $parcsBaixada[$k]);
        }
        $count += 1;
    }

    if ($count > 0) {
        for($l = 0; $l < count($lanc); $l++){
            $newLanc[$count] = $lanc[$l];
            $count += 1;
            //array_push($newLanc[$count+=1], $lanc[$l]);
        }
        return $newLanc;
    } else {
        return $lanc;
    }

    
    
}

}	//	END OF THE CLASS
?>
