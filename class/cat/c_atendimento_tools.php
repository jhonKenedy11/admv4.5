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
Class c_atendimentoTools extends c_atendimento {

/*=====================SERVIÇOS===============================*/ 

/**
 * <b> Rotina que inclui um Servico do atendimento</b>
 * @name incluiServicoAtendimentoControle
 * @param INT cc
 * @param INT idAtendimento
 * @param INT idItem
 * @param Float itemValor
 * @param Float itensQtde
 * @param String tipoMsg
 * @param INT cliente
 * @param INT situacao
 * @param INT catTipoId
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */
public function incluiServicoAtendimentoControle($obj, &$idAtendimento, $itemId, $itemValor, $itensQtde, $tipoMsg=null){
    try{
        if (empty($idAtendimento)){
            $this->setCliente($obj->getCliente());
            $this->setContato($obj->getContato());
            //$this->setAtendimento($obj->NUMATENDIMENTO);
            $this->setDataAberturaEnd(date("d/m/Y H:m:i"));
            //$this->setDataFechamentoEnd($obj->DATAFECHATEND);
            $this->setUsrAbertura($this->m_userid);
            $this->setPrioridade($obj->getPrioridade());
            $this->setPrazoEntrega($obj->getPrazoEntrega());
            $this->setDescEquipamento($obj->getDescEquipamento());
            $this->setKmEntrada($obj->getKmEntrada());
            $this->setObs($obj->getObs());
            $this->setObsServicos($obj->getObsServicos());
            $this->setSolucao($obj->getSolucao());
            $this->setTipoCobranca($obj->getTipoCobranca());
            $this->setCondPgto($obj->getCondPgto());
    
            $this->setConta($obj->getConta());
            $this->setGenero($obj->getGenero());
            $this->setCentroCusto($obj->getCentroCusto());
            $this->setSituacao($obj->getSituacao());
    
            $this->setCatEquipamentoId($obj->getCatEquipamentoId());
            $this->setCatTipoId($obj->getCatTipoId());

            // $this->setCliente($cliente);
            // if($this->getDataAberturaEnd() == ''){
            //     $this->setDataAberturaEnd(date("d/m/Y"));
            // }
            // if($this->getDataFechamentoEnd() == ''){
            //     $this->setDataFechamentoEnd(date("d/m/Y"));
            // }
            // $this->setCentroCusto($cc);
            // $this->setCatTipoId($catTipoId);
            // $this->setSituacao($situacao);
            $idAtendimento = $this->incluiAtendimento();
        }

        $msg = "";
        $this->setIdAtendimentoServico($idAtendimento);
        $this->setCatServicoId($itemId);

        $arrServicoAtendimento = $this->select_atendimento_item_id_servico();
        if (is_array($arrServicoAtendimento)):
            $this->setIdServico($arrServicoAtendimento[0]['ID']);
            $quantServicoAtendimento = $arrServicoAtendimento[0]['QUANTIDADE']; // quant já cadastrada
            $itensQtde += $quantServicoAtendimento;
        endif;

        $arrServicos = $this->select_atendimento_cat_servico();

        $this->setDescricaoServico($arrServicos[0]['DESCRICAO']);
        $this->setUnidadeServico($arrServicos[0]['UNIDADE']);

        $this->setQuantidadeServico(str_replace('.', ',', $itensQtde));

        $itemValor = $itemValor;

        $this->setVlrUnitarioServico(str_replace('.', ',', $itemValor));
        $this->setTotalServico(str_replace('.', ',', ($itensQtde * $itemValor)));


        if (is_array($arrServicoAtendimento)):
            $this->alteraServicos();
        else:            
            $this->incluiServicos();
        endif;
        // atualiza total
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, 'B');
        //$this->setCliente($cliente);
        $this->setId($idAtendimento);
        $this->setSituacao($obj->getSituacao());

        $this->alteraServicoTotalAtendimento();
        $tipoMsg = "sucesso";

        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não cadastrado " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não cadastrado " );

    }
                    
}
/**
 * <b> Rotina que altera um Servico do atendimento </b>
 * @name alteraServicoAtendimentoControle
 * @param  INT idAtendimento
 * @param  Array arrServico
 * @param  INT situacao
 * @return String Mensagem de sucesso ou falha.
 * @author Tony
 * @date   17/03/2021
 */

public function alteraServicoAtendimentoControle($idAtendimento, $arrServico, $situacao){
    try{
        $m_item = explode("|", $arrServico);

        $msg = "";
        $this->setIdAtendimentoPecas($idAtendimento);
        $this->setCodProduto($m_item[0]);      

        $this->setDescricaoServico($m_item[1]);
        $this->setUnidadeServico($m_item[2]);
        $this->setQuantidadeServico($m_item[3]);
        $this->setVlrUnitarioServico($m_item[4]);
        $totalServico = (($this->getQuantidadeServico('B') * $this->getVlrUnitarioServico('B')));
        $this->setTotalServico($totalServico, true);

        // $arrServicoAtendimento = $this->select_atendimento_item_id_servico();
        
        $this->setIdServico($m_item[0]);
        
        $this->alteraServicos();
        
        $this->setId($idAtendimento);
        $this->setIdAtendimentoServico($idAtendimento);
        $this->setSituacao($situacao);
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, true);

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
 * @param INT centroCusto
 * @param INT idAtendimento
 * @param INT idItem
 * @param String tipoMsg
 * @param INT situacao
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */
public function excluiServicoAtendimento($cc, $idAtendimento, $idItem, $tipoMsg=null, $situacao ){
    try{
        $tipoMsg = "sucesso";
        $msg ="";

        $this->setId($idAtendimento);
        $this->setIdServico($idItem);        
        
        $msg = $this->excluiServicosItemAtendimento();
        
        // atualiza total
        $this->setIdAtendimentoServico($idAtendimento);
        $totalServicos = $this->select_servicos_total();
        $this->setValorServicos($totalServicos, 'B');
        //$this->setCliente($cliente);
        $this->setId($idAtendimento);
        $this->setSituacao($situacao);

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
 * @param INT cc
 * @param INT idAtendimento
 * @param INT idItem
 * @param Float itemValor
 * @param Float itensQtde
 * @param String tipoMsg
 * @param INT cliente
 * @param INT situacao
 * @param INT catTipoId
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */

/*===================== PEÇAS ===============================*/ 
// CLIENTE, CONTATO, USRABERTURA,  VALORPECAS, VALORSERVICOS, VALORVISITA, VALORDESCONTO, DATAABERATEND, DATAFECHATEND, 
// PRAZOENTREGA,  OBS, OBSSERVICO, CAT_EQUIPAMENTO_ID, DESCEQUIPAMENTO, CONDPGTO, CAT_TIPO_ID, CAT_SITUACAO_ID
public function incluiPecasAtendimentoControle($obj, &$idAtendimento, $itemId, $itemValor, $itensQtde, $tipoMsg=null, $descontoPecas, $percDescontoPecas, $descProduto, $uniProduto, $codProdutoNota=null){
    try{
        if (empty($idAtendimento)){
            $this->setCliente($obj->getCliente());
            $this->setContato($obj->getContato());
            //$this->setAtendimento($obj->NUMATENDIMENTO);
            $this->setDataAberturaEnd(date("d/m/Y H:m:i"));
            //$this->setDataFechamentoEnd($obj->DATAFECHATEND);
            $this->setUsrAbertura($this->m_userid);
            $this->setPrioridade($obj->getPrioridade());
            $this->setPrazoEntrega($obj->getPrazoEntrega());
            $this->setDescEquipamento($obj->getDescEquipamento());
            $this->setKmEntrada($obj->getKmEntrada());
            $this->setObs($obj->getObs());
            $this->setObsServicos($obj->getObsServicos());
            $this->setSolucao($obj->getSolucao());
            $this->setTipoCobranca($obj->getTipoCobranca());
            $this->setCondPgto($obj->getCondPgto());
    
            $this->setConta($obj->getConta());
            $this->setGenero($obj->getGenero());
            $this->setCentroCusto($obj->getCentroCusto());
            $this->setSituacao($obj->getSituacao());
    
            $this->setCatEquipamentoId($obj->getCatEquipamentoId());
            $this->setCatTipoId($obj->getCatTipoId());
    
// ===============
            // $this->setCliente($obj->cliente);
            // if($this->getDataAberturaEnd() == ''){
            //     $this->setDataAberturaEnd(date("d/m/Y"));
            // }
            // $this->setUsrAbertura($this->m_userid);
            // $this->setCentroCusto($cc);
            // $this->setCatTipoId($catTipoId);
            // $this->setSituacao($situacao);
            $idAtendimento = $this->incluiAtendimento();
        }

        $msg = "";
        $this->setIdAtendimentoPecas($idAtendimento);
        $this->setCodProduto($itemId);
        $this->setCodProdutoNota($codProdutoNota);
        $this->setDescricaoPecas($descProduto);
        $this->setUnidadePecas($uniProduto);
        $this->setQuantidadePecas($itensQtde, true);
       
        $itemValor = $itemValor;

        $this->setVlrUnitarioPecas($itemValor, true);
        $this->setDescontoPecas($descontoPecas, true);
        $this->setPercDescontoPecas($percDescontoPecas, true);
        $this->setTotalPecas($itensQtde * $itemValor, true);
                 
        $this->incluiPecas();
        
        // atualiza total
        $totalPecas = $this->select_pecas_total();
        $this->setValorPecas($totalPecas, 'B');
        $this->setId($idAtendimento);
        $this->setSituacao($obj->getSituacao());

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
 * @param  INT idAtendimento
 * @param  Array arrPeca
 * @param  INT situacao
 * @return String Mensagem de sucesso ou falha.
 * @author Tony
 * @date   17/03/2021
 */

public function alteraPecasAtendimentoControle($idAtendimento, $arrPeca, $situacao){
    try{
        $m_item = explode("|", $arrPeca);

        $msg = "";
        $this->setIdAtendimentoPecas($idAtendimento);
        $this->setCodProdutoNota($m_item[1]);      

        $this->setDescricaoPecas($m_item[2]);
        $this->setUnidadePecas($m_item[3]);
        $this->setQuantidadePecas($m_item[4]);
        $this->setVlrUnitarioPecas($m_item[5]);
        $this->setDescontoPecas($m_item[7]);
        $this->setPercDescontoPecas($m_item[6]);
        $totalPeca = ($this->getQuantidadePecas('B') * $this->getVlrUnitarioPecas('B'));
        $this->setTotalPecas($totalPeca, true);

        $this->setIdPecas($m_item[0]);
        
        $msg = $this->alteraPecas();
        
        // atualiza total
        $totalPecas = $this->select_pecas_total();
        $this->setValorPecas($totalPecas, 'B');
        $this->setId($idAtendimento);
        $this->setSituacao($situacao);

        $this->setIdAtendimentoPecas($idAtendimento);
        
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
 * @param INT centroCusto
 * @param INT idAtendimento
 * @param INT idItem
 * @param String tipoMsg
 * @param INT situacao
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */
public function excluiPecasAtendimento($cc, $idAtendimento, $idItem, $tipoMsg=null, $situacao ){
    try{
        $tipoMsg = "sucesso";
        $msg ="";

        $this->setId($idAtendimento);
        $this->setIdPecas($idItem);        

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
        $this->setIdAtendimentoPecas($idAtendimento);
        $totalPecas = $this->select_pecas_total();
        $this->setValorPecas($totalPecas, 'B');
        $this->setId($idAtendimento);
        $this->setSituacao($situacao);

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
