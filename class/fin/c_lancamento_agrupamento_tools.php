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
include_once($dir . "/../../class/fin/c_lancamento.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class 
Class c_lancamento_agrupamento_tools extends c_lancamento {


    
/**
 * <b> Rotina que cancela pedido do array $pedidoAgrupado </b>
 * @name cancelaPedidoAgrupado
 * @param ARRAY pedidoAgrupado
 * @param  OBJ conn
 * @return vazio se for sucesso
 */
public function alteraSituacaoLancAgrupado($pedidoAgrupado, $idGerado, $conn=null ){
    
    $arrPedido = explode("|", ($pedidoAgrupado)); 
    $banco = new c_banco();
    for ($i=0;$i<count($arrPedido);$i++){
        if ($arrPedido[$i] > 0) {           
            $sql  = "UPDATE fin_lancamento ";
            $sql .= "SET " ;
            $sql .= "SITPGTO = 'G', " ;
            $sql .= "AGRUPAMENTO = ".$idGerado;
            $sql .= " WHERE (ID = ".$arrPedido[$i].");";

            $banco->exec_sql($sql, $conn);
        }                      
    }
    $banco->close_connection();
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
public function incluiLancamentoAgrupado($lancAgrupado, $arrayLanc, $conn=null ){
    
    $arrLancAgrupado = explode("|", ($lancAgrupado)); 

    $this->setId($arrLancAgrupado[1]);
    $lanc = $this->select_lancamento();

    $arrLanc = explode("|", ($arrayLanc)); 

    $dataVencimento = c_date::convertDateTxt($arrLanc[1]);
     //novo lancamento
    
    $this->setPessoa($arrLanc[0]);
    $this->setGenero($lanc[0]['GENERO']);
    $this->setParcela(1);
    $this->setSerie(1);
    $this->setCentroCusto($this->m_empresacentrocusto);
    $this->setUsrsitpgto($this->m_userid);
    $this->setUsraprovacao($this->m_userid);
    $this->setOrigem('FIN');
    $this->setDocto($arrLanc[7]);

    $this->setTipolancamento($lanc[0]['TIPOLANCAMENTO']);
	$this->setTipodocto($lanc[0]['TIPODOCTO']);
	$this->setSitdocto($lanc[0]['SITDOCTO']);
	$this->setSitpgto($lanc[0]['SITPGTO']);
    $this->setConta($lanc[0]['CONTA']);
    $this->setMoeda($lanc[0]['MOEDA']);

    $this->setEmissao(date('Y-m-d'));
    $this->setLancamento(date('Y-m-d H:i:s'));
    $this->setMovimento($dataVencimento);
    $this->setVencimento($dataVencimento);
    $this->setMulta($arrLanc[2]);
    $this->setJuros($arrLanc[3]);
    $this->setDesconto($arrLanc[4]);
    $this->setOriginal($arrLanc[6]);
    $this->setTotal($arrLanc[5]);

    $obs = '';
    $consulta = new c_banco();
    for($i=1; $i < count($arrLancAgrupado); $i++){
        // $consulta->setTab("FIN_LANCAMENTO");
        // $lancObs = $consulta->getField("OBS", "ID=".$arrLancAgrupado[$i]);
        $this->setId($arrLancAgrupado[$i]);
        $lancObs = $this->select_lancamento();        
        $obs == '' ? $obs = "Titulo: ". $lancObs[0]['DOCTO'] . " - ".$lancObs[0]['OBS']."\n" :
                     $obs .= "Titulo: ". $lancObs[0]['DOCTO'] . " - ".$lancObs[0]['OBS']."\n" ;
    }
    $consulta->close_connection();

    $this->setObs($obs);

    $idGerado = $this->incluiLancamento($conn);

                 
    return $idGerado;
}





}	//	END OF THE CLASS
?>
