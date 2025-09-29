<?php
/**
 * @package   astecv3
 * @name      c_boleto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      12/12/2016
 */

$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class C_FIN_BANCO
Class c_boleto extends c_user {

     /*
     * TABLE NAME FIN_BANCO
     */     

//construtor
function __construct(){

}


 /**
 * @name selectLancBoleto
 * @description seleciona lancamentos para impressao de boletos
 */
public function selectLancBoleto($id= null, $num=null, $serie=null, $par=null){

    $sql  = "SELECT * FROM FIN_LANCAMENTO ";
    if (!is_null($id) and ($id!='')){
            $sql .= "WHERE (id=".$id.") ";
    }
    else{    
        $sql .= "where (MODOPGTO='B') and (sitpgto='A') and (TIPOLANCAMENTO='R') and (TIPODOCTO='B')  ";
        if ($num != null){
            $sql .= "and (numlcto=".$num.") ";
            if ($serie != null){
                $sql .= "and (origem='".$serie."') ";
            }
        }
    }    
/*        if ($num != null){
            $sql .= "and (docto=".$num.") ";
            if ($serie != null){
                $sql .= "and (serie='".$serie."') ";
                if ($par != null){
                    $sql .= "and (parcela=".$par.")";
                }
            }
        }
    }*/
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
} //fim existeBanco


}	//	END OF THE CLASS
?>
