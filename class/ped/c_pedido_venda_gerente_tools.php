<?php
/**
 * @package   astecv3
 * @name      c_pedido_venda_gerente_tools
 * @version   3.0.00
 * @copyright 2021
 * @link      http://www.admservice.com.br/
 * @author    Tony
 * @date      14/04/2021
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../class/ped/c_pedido_venda.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir . "/../../class/fin/c_lancamento.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class 
Class c_pedido_venda_gerente_tools extends c_pedidoVenda {


    
/**
 * <b> Rotina que cancela pedido do array $pedidoAgrupado </b>
 * @name cancelaPedidoAgrupado
 * @param ARRAY pedidoAgrupado
 * @param  OBJ conn
 * @return vazio se for sucesso
 */
public function cancelaPedidoAgrupado($pedidoAgrupado, $conn=null ){
    //cancela pedidos
    $arrPedido = explode("|", ($pedidoAgrupado)); 
    $objPedido = new c_pedidoVenda();
    for ($i=0;$i<count($arrPedido);$i++){
        if ($arrPedido[$i] > 0) {           
            $objPedido->setId($arrPedido[$i]);
            
            // Carrega os dados do pedido antes de alterar a situação
            $dadosPedido = $objPedido->select_pedidoVenda();
            if (isset($dadosPedido[0])) {
                // Preenche os dados necessários para evitar valores inválidos
                $objPedido->setEmissao($dadosPedido[0]['EMISSAO']);
                $objPedido->setObs($dadosPedido[0]['OBS']);
                $objPedido->setCredito($dadosPedido[0]['CREDITO']);
                $objPedido->setCliente($dadosPedido[0]['CLIENTE']);
            }
            
            c_produto_estoque::liberaEstoquePedidoCancelamento(
                $this->m_empresacentrocusto,
                (int) $arrPedido[$i],
                $conn
            );

            c_lancamento::cancelaLancamentosAbertosPedido(
                (int) $arrPedido[$i],
                $this->m_userid,
                $conn
            );

            $objPedido->setSituacao(8);
            $objPedido->alteraPedidoSituacao(null, $conn);
        }                      
    }
    return '';

}

/**
 * <b> Rotina que gera novo pedido baseado nos dados do array $arrayPed </b>
 * @name incluiPedidoAgrupado
 * @param ARRAY pedidoAgrupado
 * @param ARRAY arrayPed
 * @param OBJ conn
 * @return INT idPedido gerado.
 */
public function incluiPedidoAgrupado($pedidoAgrupado, $arrayPed, $conn=null ){
    
    $arrPedidoAgrupado = explode("|", ($pedidoAgrupado)); 

    $arrPedido = explode("|", ($arrayPed)); 
     //novo pedido
    $this->setSituacao($arrPedido[1]);
    $this->setCliente($arrPedido[0]);
    $this->setEmissao(date("d/m/Y"));
    $this->setAtendimento(date("d/m/Y"));
    $this->setHoraEmissao(date("H:i:s"));
    $this->setEspecie("D");
    $this->setIdNatop("1");
    $this->setCondPg($arrPedido[6]);
    $this->setCentroCusto($this->m_empresacentrocusto);
    $this->setDesconto($arrPedido[4]);
    $this->setTotal($arrPedido[5]);

    $obs = '';
    $ped = new c_banco;
    for ($i = 0; $i < count($arrPedidoAgrupado); $i++) {
        if ($arrPedidoAgrupado[$i] <= 0) {
            continue;
        }
        $ped->setTab("FAT_PEDIDO");
        $pedObs = $ped->getField("OBS", "ID=".$arrPedidoAgrupado[$i]);
        if ($obs == '') {
            $obs = "Pedido: ". $arrPedidoAgrupado[$i] . " - ".$pedObs."\n";
        } else {
            $obs .= "Pedido: ". $arrPedidoAgrupado[$i] . " - ".$pedObs."\n";
        }
    }
    $ped->close_connection();

    $this->setObs($obs);

    $idGerado = $this->incluiPedido($conn);

    $this->setId($idGerado);

    $this->atualizarField('FRETE', $arrPedido[2], $conn);
    $this->atualizarField('DESPACESSORIAS', $arrPedido[3], $conn);
    $this->atualizarField('PEDIDO', $idGerado, $conn);

                 
    return $idGerado;
}

/**
 * <b> Rotina que inclui itens selecionados no pedido </b>
 * @name incluiItensPedidoAgrupado
 * @param ARRAY pedidoAgrupado
 * @param int idPedido
 * @param OBJ conn
 * @return vazio se for sucesso
 */
public function incluiItensPedidoAgrupado($pedidoAgrupado, $idPedido, $conn=null ){
     //busca itens dos pedidos
     $arrItensPedidos = $this->agruparPedidos($pedidoAgrupado);

     if (!is_array($arrItensPedidos) || count($arrItensPedidos) === 0) {
         throw new Exception('Nenhum item encontrado nos pedidos selecionados para agrupamento.');
     }
     
     // Define o ID do novo pedido agrupado antes de incluir os itens
     $this->setId($idPedido);
                           
     $objProduto = new c_produto();
     $objProdutoQtde = new c_produto_estoque();
     $nrItem = 1;
     for ($i=0;$i<count($arrItensPedidos);$i++){

        $codProduto = $arrItensPedidos[$i]['ITEMESTOQUE'];
        $quantDigitada = $arrItensPedidos[$i]['QTSOLICITADA'];
        $quantTotal = $quantDigitada;

        $this->setItemEstoque($codProduto);

        // Agrupa quantidade quando o mesmo produto existe em mais de um pedido
        $arrItemPedido = $this->select_pedido_item_id_itemestoque($conn);
        if (is_array($arrItemPedido)) {
            $quantTotal = $quantDigitada + $arrItemPedido[0]['QTSOLICITADA'];
            $this->pedido_venda_item(false, $arrItemPedido);
        }
         
        $objProduto->setId($codProduto); // CODIGO PRODUTO
        //busca dados do produto                    
        $arrProduto = $objProdutoQtde->produtoQtdePreco(NULL, 
                             $this->m_empresacentrocusto, $objProduto->getId());
         
        $this->setItemFabricante($arrItensPedidos[$i]['ITEMFABRICANTE']);
        $this->setDesconto($arrItensPedidos[$i]['DESCONTO'], 'F');
        $this->setQtSolicitada($quantTotal, 'F');
        $this->setUnitario($arrItensPedidos[$i]['UNITARIO'], 'F');
        $this->setPrecoPromocao($arrItensPedidos[$i]['PRECOPROMOCAO']);
        $this->setVlrTabela($arrItensPedidos[$i]['VLRTABELA']);
        $this->setTotalItem();
        $this->setGrupoEstoque($arrItensPedidos[$i]['GRUPOESTOQUE']);
        $this->setDescricaoItem($arrItensPedidos[$i]['DESCRICAO']);

        if (is_array($arrItemPedido)) {
            $this->alteraPedidoItem($conn);
        } else {
            $this->setNrItem($nrItem);
            $this->IncluiPedidoItem($conn);
            $nrItem += 1;
        }
         
        // reserva produto
        if (is_array($arrProduto) && isset($arrProduto[0]['UNIFRACIONADA']) && $arrProduto[0]['UNIFRACIONADA'] == "N"){
            //remove reserva
            $objProdutoQtde->produtoReservaExclui($this->m_empresacentrocusto, "PED", 
                         $arrItensPedidos[$i]['ID'], $arrItensPedidos[$i]['ITEMESTOQUE'], 
                         abs($arrItensPedidos[$i]['QTSOLICITADA']), $conn);
                 
            //adiciona reserva
            $objProdutoQtde->produtoReserva($this->m_empresacentrocusto, "PED", 
            $this->getId(), $this->getItemEstoque(), (int) $quantDigitada, $conn);
        } else {
           $objProdutoQtde->produtoReserva=null;
        }
    }
    return '';
}




}	//	END OF THE CLASS
?>
