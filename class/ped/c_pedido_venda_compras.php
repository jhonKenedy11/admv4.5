<?php

/****************************************************************************
*Cliente...........:
*Contratada........: admService
*Desenvolvedor.....: Marcio Sergio da Silva
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: C_pedido_venda_compras - cadastro de pedido_venda_compras - BUSINESS CLASS
*Ultima Atualiza��o: 23/08/17
****************************************************************************/

include_once("../bib/c_user.php");

//Class C_pedido_venda_comp
Class c_pedido_venda_compras extends c_user {

// Campos tabela
private $id = NULL;
private $itemEstoque = null; //  "ITEMESTOQUE"	 VARCHAR(25) NOT NULL,
private $qtPadrao = null; //  "QTPADRAO"	 NUMERIC(9,4) NOT NULL,
private $qtPedido = null; //  "QTPEDIDO"	 NUMERIC(9,4),
private $qtEstoque = null; //  "QTESTOQUE"	 NUMERIC(9,4),
private $custoUnitario = null; //  "CUSTOUNITARIO"	 NUMERIC(11,2),
private $despesas = null; //  "DESPESAS"	 NUMERIC(11,2),
private $custoTotal = null; //  "CUSTOTOTAL"	 NUMERIC(11,2),
private $vendaUnitario = null; //  "VENDAUNITARIO"	 NUMERIC(11,2),
private $vendaTotal = null; //  "VENDATOTAL"	 NUMERIC(11,2),
private $rentabilidade = null; //  "RENTABILIDADE"	 NUMERIC(5,2),
private $comissao = null; //  "COMISSAO"	 NUMERIC(5,2),
private $valorComissao = null; //  "VALORCOMISSAO"
private $itemPedido = null; //  "ITEMPEDIDO"	 VARCHAR(25) NOT NULL,


//construtor
function c_pedido_venda_comp(){

}

//---------------------------------------------------------------
//---------------------------------------------------------------

// Campos Tabela
public function setId($cliente){
         $this->id = $cliente;
}
public function getId(){
         return $this->id;
}

public function setItemEstoque($item){
         $this->itemEstoque = strtoupper($Item);
}
public function getItemEstoque(){
         return $this->itemEstoque;
}

public function setQtPadrao($qtPadrao){
         $this->qtPadrao = $qtPadrao;
}
public function getQtPadrao(){
         return $this->qtPadrao;
}

public function setQtPedido($QtPedido){
         $this->QtPedido = $QtPedido;
}
public function getQtPedido(){
         return $this->qtPedido;
}

public function setQtEstoque($qtEstoque){
         $this->qtEstoque = $qtEstoque;
}
public function getQtEstoque(){
         return $this->qtEstoque;
}


public function setQtCustoUnitario($custoUni){
         $this->qtcustoUnitario = $custoUni;
}

public function getQtCustoUnitario(){
         return $this->qtcustoUnitario;
}

public function setDespesas($despesas){
         $this->despesas = $despesas;
}

public function getDespesas(){
         return $this->despesas;
}

public function setCustoTotal($custoTotal){
         $this->custoTotal = $custoTotal;
}

public function getCustoTotal(){
         return $this->custoTotal;
}

public function setVendaUnitario($vendaUni){
         $this->vendaUnitario = $vendaUni;
}

public function getVendaUnitario(){
         return $this->vendaUnitario;
}

public function setVendaTotal($vendaTotal){
         $this->VendaTotal = $vendaTotal;
}

public function getVendaTotal(){
         return $this->VendaTotal;
}

public function setRentabilidade($rent){
         $this->rentabilidade = $rent;
}

public function getRentabilidade(){
         return $this->rentabilidade;
}

public function setComissao($comissao){
         $this->comissao = $comissao;
}

public function getComissao(){
         return $this->comissao;
}

public function setValorComissao($valorComissao){
         $this->valorComissao = $valorComissao;
}

public function getValorComissao(){
         return $this->valorComissao;
}

public function setItemPedido($itemPedido){
         $this->itemPedido = strtoupper($ItemPedido);
}
public function getItemPedido(){
         return $this->itemPedido;

}
//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_pedido_venda_comp(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fat_pedido_item_comp ";
   	$sql .= "WHERE (id = ".$this->getId().") ";
   	$sql .= "ORDER BY ItemEstoque";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_pedido_venda_comp

//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_pedido_venda_comp_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM fat_pedido_item_comp ";
   	$sql .= "ORDER BY ItemEstoque ";
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_pedido_venda_comp_geral

//---------------------------------------------------------------
// procedures Pedido Composicao
//---------------------------------------------------------------

//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_pedidoCompraItens($letra){


        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql  = "SELECT e.descricao, f.nomereduzido, sum(i.qtpedido) FROM fat_pedido_item_comp i ";
        $sql .= "inner join est_estoque e on e.itemestoque = i.itemestoque ";
        $sql .= "inner join fat_pedido p on p.id = i.id ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "inner join fin_fornecedor f on f.fornecedor = i.fornecedor ";
        $sql .= "inner join amb_usuario u on u.usuario = p.usrpedido ";
        $sql .= " ";
        if ($letra != '||||'){
                $sql .= "WHERE ";}
                if ($par[0] != ''){
                        $sql .= "(p.emissao >= '".$dataIni."') ";}
                        if ($par[1] != ''){
                                if ($par[0] != ''){
                                        $sql .= "AND (p.emissao <= '".$dataFim."') ";}
                        }
                        if ($par[2] != ''){
                                if (($par[0] != '') or ($par[1] != '')){
                                        $sql .= "AND (p.cliente = ".$par[2].") ";}
                                        else{
                                                $sql .= "(p.cliente = ".$par[2].") ";}
                        }
                        if ($par[4] != ''){
                                if (($par[0] != '') or ($par[1] != '') or ($par[2] != '')){
                                        $sql .= "AND (p.situacao = '".$par[4]."') ";}
                                        else{
                                                $sql .= "(p.situacao = '".$par[4]."') ";}
                        }
                        if ($par[5] != ''){
                                if (($par[0] != '') or ($par[1] != '') or ($par[2] != '') or ($par[4] != '')){
                                        $sql .= "AND (p.usrpedido = '".$par[5]."') ";}
                                        else{
                                                $sql .= "(p.usrpedido = '".$par[5]."') ";}
                        }
                        $sql .= "GROUP BY f.nomereduzido, e.descricao ";
                        //  ECHO $sql;

                        $banco = new c_banco;
                        $banco->exec_sql($sql);
                        $banco->close_connection();
                        return $banco->resultado;
}// fim select_pedidoCompraItens

public function select_pedidoCustoTotal(){

        $sql  = "SELECT sum(custototal) ";
        $sql .= "FROM fat_pedido_item_comp ";
        $sql .= "WHERE (id = ".$this->getId().") ";
//		ECHO $sql;

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
} //fim select_pedidoCustoTotal

//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_pedidoVendaItemComp(){
        $sql  = "SELECT * ";
        $sql .= "FROM fat_pedido_item_comp ";
        $sql .= "WHERE (id = ".$this->getId().") and (nritem = ".$this->getNumItemComp().")";
        //	ECHO $sql;
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
} //fim select_pedidoVendaComp

//---------------------------------------------------------------
//---------------------------------------------------------------
public function select_pedidoVendaItemComp_geral(){
        $sql  = "SELECT c.*, e.descricao, f.nomereduzido ";
        $sql .= "FROM fat_pedido_item_comp c ";
        $sql .= "inner join fin_cliente f on f.cliente = c.fornecedor ";
        $sql .= "inner join est_produto e on e.codigo = c.itempedido ";
        $sql .= "WHERE (c.id = ".$this->getId().") ";
        $sql .= "ORDER BY c.itempedido, e.codigo ";
        //ECHO $sql;
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
} //fim select_pedidoVendaComp_geral

//---------------------------------------------------------------
//---------------------------------------------------------------
public function incluiPedidoItemComp(){

        $this->geraNumItemComp();

        $sql  = "INSERT INTO fat_pedido_item_comp (id, nritem, itemestoque, qtpedido, custounitario, despesas, custototal, itempedido, fornecedor, ordemcompra) ";
        $sql .= "VALUES (".$this->getId().", "
        .$this->getNumItemComp().", '"
        .$this->getItemEstoque()."', "
        .$this->getQtPedido().", "
        .$this->getCustoUnitario().", "
        .$this->getDespesas().", "
        .$this->getCustoTotal().", '"
        .$this->getItemPedido()."', '"
        .$this->getFornecedor()."', '"
        .$this->getOrdemCompra()."') ";
        //echo $sql;
        $banco = new c_banco;
        $res_pedidoVenda =  $banco->exec_sql($sql);
        $banco->close_connection();

        if($res_pedidoVenda > 0){
                return '';
        }
        else{
                return 'Os dados do Pedido Composi��o '.$this->getId().' n&atilde;o foi cadastrado!';
        }
} // fim incluiPedidoItemComp

//---------------------------------------------------------------
//---------------------------------------------------------------
public function alteraPedidoItemComp(){

        $sql  = "UPDATE fat_pedido_item_comp ";
        $sql .= "SET itemestoque = '".$this->getItemEstoque()."', " ;
        $sql .= "qtpedido = ".$this->getQtPedido().", " ;
        $sql .= "custounitario = ".$this->getCustoUnitario().", " ;
        $sql .= "despesas = ".$this->getDespesas().", " ;
        $sql .= "custototal = ".$this->getCustoTotal().", " ;
        $sql .= "itempedido = '".$this->getItemPedido()."', " ;
        $sql .= "fornecedor = '".$this->getFornecedor()."', " ;
        $sql .= "ordemcompra = '".$this->getOrdemCompra()."' " ;
        $sql .= "WHERE (id = ".$this->getId().") and (nritem = ".$this->getNumitemComp().")";
        //ECHO $sql;
        $banco = new c_banco;
        $res_pedidoVenda =  $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if($res_pedidoVenda > 0){
                return '';
        }
        else{
                return 'O Produto '.$this->getProduto().' n&atilde;o foi alterado!';
        }

}  // fim alteraPedidoItemComp

//---------------------------------------------------------------
//---------------------------------------------------------------
public function excluiPedidoItemComp(){

        $sql  = "DELETE FROM fat_pedido_item_comp ";
        $sql .= "WHERE (id = ".$this->getId().") and (nritem = ".$this->getNumItemComp().")";
        //echo $sql;
        $banco = new c_banco;
        $res_pedidoVendaItem =  $banco->exec_sql($sql);
        $banco->close_connection();

        if($res_pedidoVendaItem > 0){
                return '';
        }
        else{
                return 'O Produto '.$this->getProduto().' n&atilde;o foi excluido!';
        }
}  // fim excluiPedidoItemComp


}	//	END OF THE CLASS
?>
