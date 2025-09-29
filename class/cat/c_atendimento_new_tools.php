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
include_once($dir . "/../../class/cat/c_atendimento.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class 
Class c_atendimento_new_tools extends c_atendimento {

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
        
        $this->setIdAtendimentoServico($idAtendimento);

        $this->setCatServicoId($servico[2]);
        $this->setDescricaoServico($servico[3]);
        $this->setUnidadeServico($servico[4]);
        $this->setQuantidadeServico($servico[5]);
        $this->setVlrUnitarioServico($servico[6]);
        $totalServico = ($this->getQuantidadeServico('B') * $this->getVlrUnitarioServico('B'));
        // $this->setTotalServico($totalServico, true);
        $this->setTotalServico($servico[7]);

        $this->incluiServicos();
       
        // atualiza total
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, 'B');
        $this->setId($idAtendimento);
        $this->setSituacao($servico[8]);

        $this->alteraServicoTotalAtendimento();
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
        $this->setIdAtendimentoServico($m_item[0]);
        $this->setIdServico($m_item[10]);      

        $this->setDescricaoServico($m_item[3]);
        $this->setUnidadeServico($m_item[4]);
        $this->setQuantidadeServico($m_item[5]);
        $this->setVlrUnitarioServico($m_item[6]);
        $totalServico = ($this->getQuantidadeServico('B') * $this->getVlrUnitarioServico('B'));
        $this->setTotalServico($totalServico, true);
        
        $this->alteraServicos();
        
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, 'B');
        $this->setId($m_item[0]);
        $this->setSituacao($m_item[8]);

        $this->alteraServicoTotalAtendimento();
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
        $this->setIdAtendimentoServico($m_item[0]);
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, 'B');
        $this->setId($m_item[0]);
        $this->setSituacao($m_item[2]);

        $this->alteraServicoTotalAtendimento();
        
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

/*===================== PEÇAS ===============================*/ 
public function incluiPecasAtendimentoControle($arrPeca, $idAtendimento){
    try{
        $peca = explode("|", $arrPeca);
      
        $msg = "";
        $this->setIdAtendimentoPecas($idAtendimento);
        $codProduto = $peca[2] == '' ? 0 : $peca[2];
        $this->setCodProduto($codProduto);
        $this->setCodFabricante($peca[14]);   
        $this->setCodProdutoNota($peca[3]);     
        $this->setDescricaoPecas($peca[4]);
        $this->setUnidadePecas($peca[5]);
        $this->setQuantidadePecas($peca[6]);
        $this->setVlrUnitarioPecas($peca[7]);
        $this->setDescontoPecas($peca[9]);
        $this->setPercDescontoPecas($peca[8]);
        $descontoPecas = $this->getDescontoPecas('B');
        $this->setTotalPecas($peca[10]);
        
        $this->incluiPecas();
        
        // atualiza total
        $totalPecas = $this->select_pecas_total();
        $this->setValorPecas($totalPecas, 'B');
        $this->setId($idAtendimento);
        $this->setSituacao($peca[11]);

        if (!is_array($arrPecasAtendimento)):
            $consulta = new c_banco;
            $consulta->setTab("CAT_ATENDIMENTO");
            $vlrDesconto = $consulta->getField("VALORDESCONTO", "ID=".$this->getId());
            $consulta->close_connection();

            $vlrDesconto += $descontoPecas;

            $this->updateField("VALORDESCONTO", $vlrDesconto, "CAT_ATENDIMENTO");
        endif;
        $this->alteraPecasTotalAtendimento();
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
        $this->setIdAtendimentoPecas($m_item[0]);
        $this->setCodProdutoNota($m_item[3]);   
        $this->setCodProduto($m_item[2]); 
        $this->setCodFabricante($m_item[14]);  
        $this->setDescricaoPecas($m_item[4]);
        $this->setUnidadePecas($m_item[5]);
        $this->setQuantidadePecas($m_item[6]);

        
        $this->setVlrUnitarioPecas($m_item[7]);
        $this->setDescontoPecas($m_item[9]);
        $this->setPercDescontoPecas($m_item[8]);
        $this->setTotalPecas($m_item[10]);

        $this->setIdPecas($m_item[13]);
        
        $msg = $this->alteraPecas();
        
        // atualiza total
        $totalPecas = $this->select_pecas_total();
        $this->setValorPecas($totalPecas, 'B');
        $this->setId($m_item[0]);
        $this->setSituacao($m_item[11]);

        $this->setIdAtendimentoPecas($m_item[0]);
        
        $vlrDesconto = $this->select_desconto_pecas_total();

        $this->updateField("VALORDESCONTO", $vlrDesconto, "CAT_ATENDIMENTO");
        $this->alteraPecasTotalAtendimento();

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
        $this->setIdPecas($m_item[1]);        

        $consulta = new c_banco;
        $consulta->setTab("CAT_AT_PECAS");
        $descontoPeca = $consulta->getField("DESCONTO", "ID=".$this->getIdPecas());
        $consulta->close_connection();
        
        $msg = $this->excluiPecasItemAtendimento();

        $consulta = new c_banco;
        $consulta->setTab("CAT_ATENDIMENTO");
        $vlrDesconto = $consulta->getField("VALORDESCONTO", "ID=".$this->getId());
        $consulta->close_connection();

        $vlrDesconto -= $descontoPeca;

        $this->updateField("VALORDESCONTO", $vlrDesconto, "CAT_ATENDIMENTO");
        
        // atualiza total
        $this->setIdAtendimentoPecas($m_item[0]);
        $totalPecas = $this->select_pecas_total();
        $this->setValorPecas($totalPecas, 'B');
        $this->setId($m_item[0]);
        $this->setSituacao($m_item[2]);

        $this->alteraPecasTotalAtendimento();
        
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
        $arrPecasAtendimento = $this->select_pecas_atendimento();


    }catch(Error $e){
        throw new Exception($e->getMessage()."Problemas ao fazer o recalculo do Desconto." );
    }
}

}	//	END OF THE CLASS

?>
