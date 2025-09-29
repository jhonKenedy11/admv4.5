<?php
/**
 * @package   astecv3
 * @name      c_atendimento_financeiro_tools
 * @version   3.0.00
 * @copyright 2021
 * @link      http://www.admservice.com.br/
 * @author   Tony
 * @date  17/03/2021
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/cat/c_atendimento.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");


//Class c_atendimento_financeiro_tools
Class c_atendimento_financeiro_tools extends c_atendimento {

    /**
     * <b> Rotina que faz o lançamento das parcelas no financeiro </b>
     * @name lancaParcelasFinanceiroAtendimento
     * @param ARRAY arrAtendimento 
     * @param ARRAY arrayParcelas
     * @author    Tony
     * @date      17/03/2021
     */
    public function lancaParcelasFinanceiroAtendimento($arrAtendimento, $arrayParcelas){
        $atendimento = explode("|", $arrAtendimento);
        
        //$atendimento[0] = id 
        //$atendimento[1] = serieDocto 
        //$atendimento[2] = numDocto 
        //$atendimento[3] = dataFechamento
        //$atendimento[4] = situacao 
        //$atendimento[5] = cliente  
        //$atendimento[6] = genero 
        //$atendimento[7] = descCondPgto 
        //$atendimento[8] = total 
        //$atendimento[9] = centroCusto
        $lanc = $this->verifica_lancamento_financeiro($atendimento[0], 'OS');
        if(!is_array($lanc)){
            $objFinanceiro = new c_lancamento();
            
            $arrParcelas = $this->adicionaParcelasNfeFinanceiro($arrayParcelas, $atendimento[8]);
                
            $arrParamFin['PESSOA'] = $atendimento[5];
            $arrParamFin['DOCTO'] = $atendimento[0];
            $arrParamFin['SERIE'] = $atendimento[1];
            $arrParamFin['GENERO'] = $atendimento[6];
            $arrParamFin['CENTROCUSTO'] = $atendimento[9];
            $arrParamFin['USER'] = $this->m_userid;
            $arrParamFin['ORIGEM'] = "OS";
            $arrParamFin['NUMLCTO'] = $atendimento[0];
            $arrParamFin['TIPOLANCAMENTO'] = "R";
        
            $objFinanceiro->setCheque('0');
            $objFinanceiro->setDocbancario('0');
        
            $objFinanceiro->addParcelas($arrParamFin, $arrParcelas);   
            return true;
        }else{
            return false;
        }

            
        
    }

    /**
     * <b> Rotina que gera um array de parcelas baseado na condição de pagamento </b>
     * @name geraParcelasFinanceiro
     * @param String condPgto
     * @param Float total
     * @return Matriz com as datas de vencimento e valores de cada parcela.
     * @author Tony
     * @date  17/03/2021
     */

    public function geraParcelasFinanceiro($condPgto = NULL, $total = 0, $acrescentarParcela = 0, $bonus = 0){

        $consulta = new c_banco();
        $sql = "select PARCELA, VENCIMENTO, TOTAL as VALOR, SITPGTO ,IF (SITPGTO = 'B', 'BAIXADO', '') AS SITPAG FROM FIN_LANCAMENTO WHERE DOCTO = '".$this->getId()."' AND ORIGEM = 'OS' AND SITPGTO = 'B'";
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
                $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval(0)." day"));
                $lanc[$i]['VALOR'] = $bonus;  
                $lanc[$i]['TIPODOCTO_ID'] = 'N';
            } else if ($i <= $numParcelas) {
                $lanc[$i]['PARCELA'] = $i + 1;
                $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval($parcelas[$i])." day"));
                $lanc[$i]['VALOR'] = $valorParcela; 
                $lanc[$i]['TIPODOCTO_ID'] = '';   
            } else {
                $lanc[$i]['PARCELA'] = $i + 1;
                $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval($parcelas[$numParcelas - 1])." day"));
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
            $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval(0)." day"));
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

    /**
     * <b> Rotina que gera um array de parcelas p/ adicionar ao lançamento no financeiro </b>
     * @name adicionaParcelasNfeFinanceiro
     * @param ARRAY arrParcelas
     * @param FLOAT total
     * @return Matriz com as parcelas para lançar.
     */

    public function adicionaParcelasNfeFinanceiro($arrParcelas = NULL, $total = 0, $condicao = 0){
    $parcelas = explode("|", $arrParcelas);
    $numParcelas = count($parcelas) - 1;
    $totalGeral = doubleval($total);
    $totalCalc = 0;
    for ($i = 0; $i < $numParcelas; $i++) {
        $parcela = explode("*", $parcelas[$i + 1]);
        $lanc[$i]['PARCELA'] = trim($parcela[0]);
        $lanc[$i]['VENCIMENTO'] = $parcela[1];
        $lanc[$i]['VALOR'] = $parcela[2];
        $lanc[$i]['VALOR'] = c_tools::moedaBd($lanc[$i]['VALOR']);
        $lanc[$i]['TIPO'] = $parcela[3];
        $lanc[$i]['CONTA'] = $parcela[4];            
        $lanc[$i]['SITUACAO'] = $parcela[5];
        $lanc[$i]['OBS'] = $parcela[6];
        if ($condicao > 0){
            $lanc[$i]['DESCONTO'] = $this->cobrarTaxa($lanc[$i]['CONTA'], $condicao, $lanc[$i]['VALOR']);            
        }
        
        $totalCalc += $lanc[$i]['VALOR'];
    }
    $epsilon = 0.00001;
    $totalAbs = abs($totalCalc - $totalGeral);
    if($totalAbs < $epsilon):
        return $lanc;
    else:
        return $lanc;
      //uol
      //  return "Valor total parcelas: R$ ".$totalCalc." não confere com TOTAL NF";
    endif;
    }

    function modulo_11($num, $base=9) {
        $soma = 0;
        $fator = 2;
        for ($i = strlen($num); $i > 0; $i--) {
            $numeros[$i] = substr($num,$i-1,1);
            $parcial[$i] = $numeros[$i] * $fator;
            $soma += $parcial[$i];
            if ($fator == $base) {
                $fator = 1;
            }
            $fator++;
        }        
        
        $resto = $soma % 11;
        if ($resto == 0){
            return $resto;
        } else {
            return (11 - $resto);
        }            
        
    }
    

    public function cobrarTaxa($conta, $condpgto,$total){
                   
        $sql  = "SELECT TAXA FROM fin_conta_taxa ";
        $sql .= "WHERE (conta = '".$conta."' ) and ";
        $sql .= "(condpgto = '".$condpgto."' ) ";

        $banco = new c_banco();
	    $banco->exec_sql($sql);
	    $banco->close_connection();
        $result = $banco->resultado;
        
        if($result > 0){
            return ($total * ($result[0]['TAXA'] / 100) );
        }
        else{
            return '0';
        }
    }

    public function verifica_lancamento_financeiro($numDocto, $origem) {
        $sql  = "SELECT *  ";
        $sql .= "FROM FIN_LANCAMENTO ";
        $sql .= "WHERE (DOCTO = ".$numDocto." AND ORIGEM = '".$origem."') ";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    
    }

}	//	END OF THE CLASS
?>
