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
include_once($dir . "/../../class/ped/c_pedido_ps.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class 
Class c_pedido_ps_tools extends c_pedido_ps {

/*=====================SERVIÇOS===============================*/ 

/**
 * <b> Rotina que inclui um Servico do atendimento</b>
 * @name incluiServicoAtendimentoControle
 * @param ARRAY arrServico
 * @param INT idAtendimento
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */
public function incluiServicoAtendimentoControle($arrServico, $idAtendimento){
    try{
        $servico = explode("|", $arrServico);
        
        $this->setIdPedidoServico($idAtendimento);

        $this->setCatServicoId($servico[2]);
        $this->setDescricaoServico($servico[3]);
        $this->setUnidadeServico($servico[4]);
        $this->setQuantidadeServico($servico[5]);
        $this->setVlrUnitarioServico($servico[6]);
        $this->setObsItemServico($servico[10]);

        $totalServico = ($this->getQuantidadeServico('B') * $this->getVlrUnitarioServico('B'));
        $this->setTotalServico($totalServico, true);

        $this->incluiServicos();
       
        // atualiza total
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, true);
        $this->setId($idAtendimento);
        $this->setSituacao($servico[8]);

        $this->alteraServicoTotalPedido();
        $tipoMsg = "sucesso";

        return '';
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não cadastrado " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não cadastrado " );

    }
                    
}
/**
 * <b> Rotina que altera um Servico do atendimento </b>
 * @name alteraServicoAtendimentoControle
 * @param  Array arrServico
 * @return String Mensagem de sucesso ou falha.
 * @author Tony
 * @date   17/03/2021
 */

public function alteraServicoAtendimentoControle($arrServico){
    try{
        $m_item = explode("|", $arrServico);

        $msg = "";
        $this->setIdPedidoServico($m_item[0]);
        $this->setIdServico($m_item[9]);      

        $this->setDescricaoServico($m_item[3]);
        $this->setUnidadeServico($m_item[4]);
        $this->setQuantidadeServico($m_item[5]);
        $this->setVlrUnitarioServico($m_item[6]);
        $this->setObsItemServico($m_item[10]);
        $totalServico = ($this->getQuantidadeServico('B') * $this->getVlrUnitarioServico('B'));
        $this->setTotalServico($totalServico, true);
        
        $this->alteraServicos();
        
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, true);
        $this->setId($m_item[0]);
        $this->setSituacao($m_item[8]);

        $this->alteraServicoTotalPedido();
        $tipoMsg = "sucesso";

        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não alterado " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não alterado " );

    }
} 

/**
 * <b> Rotina que exclui um Servico do atendimento</b>
 * @name excluiServicoAtendimento
 * @param ARRAY arrServico
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */
public function excluiServicoAtendimento($arrServico){
    try{
        $m_item = explode("|", $arrServico);
        $tipoMsg = "sucesso";
        $msg ="";

        $this->setId($m_item[0]);
        $this->setIdServico($m_item[1]);        
        
        $msg = $this->excluiServicosItemAtendimento();
        
        // atualiza total
        $this->setIdPedidoServico($m_item[0]);
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, true);
        $this->setId($m_item[0]);
        $this->setSituacao($m_item[2]);

        $this->alteraServicoTotalPedido();
        
        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não excluido " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não excluido " );

    }
                    
}

/**
 * <b> Rotina que inclui uma peça do atendimento</b>
 * @name incluiPecasAtendimentoControle
 * @param ARRAY arrPeca
 * @param INT idAtendimento
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */

/*===================== PEDIDO_ITEM ===============================*/ 
public function incluiPecasAtendimentoControle($arrPeca, $idAtendimento){
    try{
        $peca = explode("|", $arrPeca);
      
        $msg = "";
        $this->setIdPedidoItem($idAtendimento);
        $codProduto = $peca[2] == '' ? 0 : $peca[2];
        $this->setCodProduto($codProduto);
        $this->setCodFabricante($peca[13]); 
        $this->setCodProdutoNota($peca[3]);     
        $this->setDescricaoProduto($peca[4]);
        $this->setUnidadeProduto($peca[5]);
        $this->setQuantidadeProduto($peca[6]);
        $this->setVlrUnitarioProduto($peca[7]);
        $this->setDescontoProduto($peca[9]);
        $this->setPercDescontoProduto($peca[8]);
        $totalPeca = ($this->getQuantidadeProduto('B') * $this->getVlrUnitarioProduto('B') - $this->getDescontoProduto('B'));
        $this->setTotalProduto($totalPeca, true);

        $result = $this->select_pedido_item_nrItem($idAtendimento);

        if($result[0]['NRITEM'] == 0){
            $nrItem = 1;
        }else{
            $nrItem = $result[0]['NRITEM'];
            $nrItem += 1;
        }

        $this->setNrItem($nrItem);
        $this->incluiProduto();
        
        // atualiza total
        $totalPecas = $this->select_produto_total();
        $this->setValorProduto($totalPecas, 'B');
        $this->setId($idAtendimento);
        $this->setSituacao($peca[11]);

        if (!is_array($arrPecasAtendimento)):
            $consulta = new c_banco;
            $consulta->setTab("FAT_PEDIDO");
            $vlrDesconto = $consulta->getField("DESCONTO", "ID=".$this->getId());
            $consulta->close_connection();

            $vlrDesconto += $this->getDescontoProduto('B');

            $this->updateField("DESCONTO", $vlrDesconto, "FAT_PEDIDO");
        endif;
        $this->alteraProdutoTotalPedido();
        $tipoMsg = "sucesso";

        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não cadastrado " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não cadastrado " );
    }
                    
}

/**
 * <b> Rotina que altera uma Peça do atendimento </b>
 * @name alteraPecasAtendimentoControle
 * @param  Array arrPeca
 * @return String Mensagem de sucesso ou falha.
 * @author Tony
 * @date   17/03/2021
 */

public function alteraPecasAtendimentoControle($arrPeca){
    try{
        $m_item = explode("|", $arrPeca);

        $msg = "";
        $this->setIdPedidoItem($m_item[0]);
        $this->setCodProduto($m_item[2]);  
        $this->setCodProdutoNota($m_item[3]);     
        $this->setCodFabricante($m_item [13]); 
        $this->setDescricaoProduto($m_item[4]);
        $this->setUnidadeProduto($m_item[5]);
        $this->setQuantidadeProduto($m_item[6]);

        
        $this->setVlrUnitarioProduto($m_item[7]);
        $this->setDescontoProduto($m_item[9]);
        $this->setPercDescontoProduto($m_item[8]);
        $totalPeca = ($this->getQuantidadeProduto('B') * $this->getVlrUnitarioProduto('B') - $this->getDescontoProduto('B'));
        $this->setTotalProduto($totalPeca, true);

        $this->setNrItem($m_item[12]);
        
        $msg = $this->alteraProduto();
        
        // atualiza total
        $totalPecas = $this->select_produto_total();
        $this->setValorProduto($totalPecas, 'B');
        $this->setId($m_item[0]);
        $this->setSituacao($m_item[11]);

        $this->setIdPedidoItem($m_item[0]);
        
        $vlrDesconto = $this->select_desconto_produto_total();

        $this->updateField("DESCONTO", $vlrDesconto, "FAT_PEDIDO");
        $this->alteraProdutoTotalPedido();

        if ($msg == '')
            $tipoMsg = "sucesso";
        else    
            $tipoMsg = "erro";

        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não alterado " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não alterado " );

    }
                    
}
/**
 * <b> Rotina que exclui uma peça do atendimento</b>
 * @name excluiPecasAtendimento
 * @param ARRAY arrPeca
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */
public function excluiPecasAtendimento($arrPeca){
    try{
        $m_item = explode("|", $arrPeca);
        $msg ="";

        $this->setId($m_item[0]);
        $this->setNrItem($m_item[1]);
        $this->setIdPedidoItem($m_item[0]);        

        $consulta = new c_banco;
        $consulta->setTab("FAT_PEDIDO_ITEM");
        $descontoPeca = $consulta->getField("DESCONTO", "ID=".$this->getId()." AND NRITEM =".$this->getNrItem());
        $consulta->close_connection();
        
        $msg = $this->excluiPedidoItemProduto();

        $consulta = new c_banco;
        $consulta->setTab("FAT_PEDIDO");
        $vlrDesconto = $consulta->getField("DESCONTO", "ID=".$this->getId());
        $consulta->close_connection();

        $vlrDesconto -= $descontoPeca;

        $this->updateField("DESCONTO", $vlrDesconto, "FAT_PEDIDO");
        
        // atualiza total
        $this->setIdPedidoItem($m_item[0]);
        $totalPecas = $this->select_produto_total();
        $this->setValorProduto($totalPecas, 'B');
        $this->setId($m_item[0]);
        $this->setSituacao($m_item[2]);

        $this->alteraProdutoTotalPedido();
        
        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não excluido " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não excluido " );

    }
                    
}



public function recalcularDescontoPecas($idAtendimento, $descontoAtendimento){
    try{
        $this->setId($idAtendimento);


    }catch(Error $e){
        throw new Exception($e->getMessage()."Problemas ao fazer o recalculo do Desconto." );
    }
}

}	//	END OF THE CLASS

?>
