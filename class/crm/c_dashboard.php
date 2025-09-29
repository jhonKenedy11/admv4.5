<?php
/**
 * @package   astecv3
 * @name      c_dashboard
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kenedy11@gmail.com.br>
 * @date      20/06/2022
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class C_DASHBOARD
Class c_dashboard extends c_user {
    
// Campos tabela FAT_METAS_MENSAL

private $id       = NULL; // int(11)
private $vendedor = NULL; // int(11)
private $ano      = NULL; // int(4)
private $mes      = NULL; // int(2)
private $meta     = NULL; // decimal(11,2)
private $ccusto   = NULL; // int(11)

// Campos tabela FAT_META_MENSAL_VENDEDOR

private $v_id       = NULL; // int(11)                         	
private $v_metaid   = NULL; // int(11)                     
private $v_vendedor = NULL; // int(11)	                    
private $v_meta     = NULL; // decimal(11,2)

// Campos tabela TEMPLATE
private $idAcomp = NULL;
private $idCotacao = NULL;
/**
* METODOS DE SETS E GETS
*/
public function setCotOntem($cotOntem){
         $this->cotOntem = c_tools::LimpaCamposGeral($cotOntem);
}

public function getCotOntem(){
         return $this->cotOntem;
}

public function setCotHoje($cotHoje){
         $this->cotHoje = c_tools::LimpaCamposGeral($cotHoje);
}

public function getCotHoje(){
         return $this->cotHoje;
}

public function setConversao($conversao){
    $this->conversao = c_tools::LimpaCamposGeral($conversao);
}

public function getConversao(){
    return $this->conversao;
}

public function setPerdido($perdido){
    $this->perdido = c_tools::LimpaCamposGeral($perdido);
}

public function getPerdido(){
    return $this->perdido;
}

public function setPedMes($pedMes){
    $this->pedMes = c_tools::LimpaCamposGeral($pedMes);
}

public function getPedMes(){
    return $this->pedMes;
}

public function setPedMesValor($pedMesValor){
    $this->pedMes = c_tools::LimpaCamposGeral($pedMesValor);
}

public function getPedMesValor(){
    return $this->pedMesValor;
}

public function setCentroCusto($centroCusto){
    $this->centroCusto = c_tools::LimpaCamposGeral($centroCusto);
}

public function getCentroCusto(){
    return $this->centroCusto;
}

public function setBloqueado($bloqueado){
        if ($bloqueado == ''){
            $this->bloqueado = 'N';
        }else{
            $this->bloqueado = strtoupper($bloqueado);
        }
         
}

public function getBloqueado(){
         return $this->bloqueado;
}

public function getIdAcompanhamento(){
    return $this->idAcomp;
}

public function setIdAcompanhamento($idAcomp){
    $this->idAcomp = c_tools::LimpaCamposGeral($idAcomp);
}

public function getIdCotacao(){
    return $this->idCotacao;
}

public function setIdCotacao($idCotacao){
    $this->idCotacao = c_tools::LimpaCamposGeral($idCotacao);
}
//############### FIM SETS E GETS ###############

    public function busca_classe() {
        $classe = $this->select_classe();
        $this->setClasse($classe[0]['CLASSE']);
        $this->setDescricao($classe[0]['DESCRICAO']);
        $this->setBloqueado($classe[0]['BLOQUEADO']);
    }// busca_classe


    /**
 * 
 * @name existeClasse
 */
public function buscaCotacaoPedidos($dataIni=null, $dataFim=null, $vendedor=null, $centroCusto=null, $ccLogado, $vertodoslancamentos=null){
    //Ajuste de datas - data inicial
    if(($dataIni == '') || ($dataIni == null)){
        $dataIni = date("Y-m-01");
    }else{
        if(strpos($dataIni, "/")){
            $dataIni = c_date::convertDateBdSh($dataIni, $this->m_banco);
        }
    }
    //Ajuste de datas - data final
    if(($dataFim == '') || ($dataFim == null)){
        $dataFim = date("Y-m-t");
    }else{
        if(strpos($dataFim, "/")){
            $dataFim = c_date::convertDateBdSh($dataFim, $this->m_banco);
        }
    }

    $dataAtual = date("Y-m-d");

    $sql = "SELECT DISTINCT ";
    //Busca COTACAO ONTEM
    $sql .= "(SELECT COUNT(CO.ID) FROM FAT_PEDIDO CO WHERE CO.EMISSAO = DATE_SUB('".$dataAtual."', INTERVAL 1 DAY) and CO.SITUACAO = 5 ";
            //Verifica se existe vendedor, se não existir não gera esse where e tras todos
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .= "and CO.USRFATURA IN ($vendedor) ";
            }
            //Verifica se existe centro de custo, se não traz o logado
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and CO.CCUSTO IN ($centroCusto)) AS COTACAO_ONTEM, ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and CO.CCUSTO IN ($ccLogado) ";
                }
                $sql .= ") AS COTACAO_ONTEM, ";
            }
    //Busca COTACAO HOJE
    $sql .= "(SELECT COUNT(CH.ID) FROM FAT_PEDIDO CH WHERE CH.EMISSAO = '".$dataAtual."' and CH.SITUACAO = 5 ";
            //Verifica se existe vendedor, se não existir não gera esse where e tras todos
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .="and CH.USRFATURA IN ($vendedor) ";
            }
            //Verifica se existe centro de custo, se não traz o logado
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and CH.CCUSTO IN ($centroCusto)) AS COTACAO_HOJE, ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and CH.CCUSTO IN ($ccLogado)";
                }
                $sql .= ") AS COTACAO_HOJE, ";
            }
    //Busca CONVERSAO
    $sql .= "(SELECT COUNT(CVH.ID) FROM FAT_PEDIDO CVH WHERE CVH.EMISSAO = '".$dataAtual."' and CVH.SITUACAO = 6 ";
            //Verifica se existe vendedor, se não existir não gera esse where e tras todos
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .= "and CVH.USRFATURA IN ($vendedor) "; 
            }
            //Verifica se existe centro de custo, se não traz o logado
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and CVH.CCUSTO IN ($centroCusto)) AS CONVERSAO, ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and CVH.CCUSTO IN ($ccLogado) ";
                }
                $sql .= ") AS CONVERSAO, ";
            }
    //Busca vendas PERDIDAS
    $sql .= "(SELECT COUNT(PER.ID) FROM FAT_PEDIDO PER WHERE PER.SITUACAO = 7 
            and PER.EMISSAO >= '$dataIni' and PER.EMISSAO <= '$dataFim' ";
            //Verifica se existe vendedor, se não existir não gera esse where e tras todos
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .="and PER.USRFATURA IN ($vendedor) ";
            }
            //Verifica se existe centro de custo, se não traz o logado
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and PER.CCUSTO IN ($centroCusto)) AS PERDIDOS ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and PER.CCUSTO IN ($ccLogado) ";
                }
                $sql .= ") AS PERDIDOS ";
                
            }
    $sql .= "FROM FAT_PEDIDO P ";

    //echo strtoupper($sql);
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim existeClasse


public function verifica_vendedor() {

	$sql = "SELECT USUARIO, NOME, TIPO FROM AMB_USUARIO  ";
	$sql .= "WHERE (USUARIO = ". $this->m_userid.")";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

function comboSql($sql, $par, &$id, &$ids, &$names) {
    $result = [];
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    if ($consulta->resultado != null) {
        $result = $consulta->resultado;
    }
    for ($i = 0; $i < count($result); $i++) {
        $ids[$i] = $result[$i]['ID'];
        $names[$i] = $result[$i]['DESCRICAO'];
    }
    
    $param = explode(",", $par);
    $i=0;
    $id[$i] = "0";
    while ($param[$i] != '') {
        $id[$i] = $param[$i];
        $i++;
    }    
}

//function buscaoMeta($vendedor=null, $ano=null, $mes=null, $centroCusto=null){
//    //Consulta sem vendedor e sem centro de custo
//    if(($vendedor =='') || ($vendedor ==null) and ($centroCusto == '') || ($centroCusto == null)){
//        $sql = "SELECT SUM(MM.META) AS META FROM FAT_META_MENSAL MM ";
//        $sql .= "where MM.ANO = '$ano' and MM.MES = '$mes' ;";
//    //Se existir centro de custo sem vendedor
//    }elseif(($vendedor == '') || ($vendedor == null) and ($centroCusto !== '') || ($centroCusto !== null)){
//        $sql = "SELECT SUM(MM.META) AS META FROM FAT_META_MENSAL MM ";
//        $sql .= "where MM.ANO = '$ano' and MM.MES = '$mes' and MM.CCUSTO IN ($centroCusto);";
//    }else{
//        $sql = "SELECT FMMV.ID, FMMV.METAID, FMMV.META, FMMV.VENDEDOR ";
//        $sql .= "FROM FAT_META_MENSAL_VENDEDOR FMMV ";
//        $sql .= "INNER JOIN FAT_META_MENSAL FMM ON FMMV.METAID = FMM.ID ";
//        //se não existir vendedr não gera essa condicao
//        if(($vendedor !== '') and ($vendedor !== null)){
//            $sql .= "where FMMV.VENDEDOR = '$vendedor' ";
//        }
//        //verifica condicao e insere 'where' ou 'and'
//        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
//        $sql .= " $cond FMM.ANO = '$ano' and FMM.MES = '$mes' ";
//    
//        if(($centroCusto !== '') and ($centroCusto !== null)){
//            //verificar cond
//            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
//            $sql .= " $cond FMM.CCUSTO IN ($centroCusto); ";
//        }
//    }
//
//    //echo strtoupper($sql);
//	$banco = new c_banco;
//	$banco->exec_sql($sql);
//	$banco->close_connection();
//	return $banco->resultado;
//}


}	//	END OF THE CLASS
?>
