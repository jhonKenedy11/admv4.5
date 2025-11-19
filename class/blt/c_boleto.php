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
include_once($dir . "/../../bib/c_database_pdo.php");

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

/**
 * @name selectAllBoletos
 * @description seleciona lançamentos para impressão de boletos por número do documento e pessoa
 * @param int $numDoc Número do documento
 * @param int $pessoa ID da pessoa/cliente
 * @param string $origem Origem do documento (PED ou outro)
 * @return array Resultado da consulta
 */
public function selectAllBoletos(int $numDoc, int $pessoa, string $origem): array
{
    try {
        // Monta a consulta SQL com placeholders
        if($origem == 'PED'){
            $sql = "SELECT * FROM FIN_LANCAMENTO 
                    WHERE MODOPGTO = :modopgto 
                    AND SITPGTO = :sitpgto 
                    AND TIPOLANCAMENTO = :tipolancamento 
                    AND TIPODOCTO = :tipodocto 
                    AND NUMLCTO = :numdoc 
                    AND PESSOA = :pessoa";
        } else {
            $sql = "SELECT * FROM FIN_LANCAMENTO 
                    WHERE MODOPGTO = :modopgto 
                    AND SITPGTO = :sitpgto 
                    AND TIPOLANCAMENTO = :tipolancamento 
                    AND TIPODOCTO = :tipodocto 
                    AND DOCTO = :numdoc 
                    AND PESSOA = :pessoa";
        }
        
        // Define os parâmetros para bind
        $binds = [
            ':modopgto' => 'B',
            ':sitpgto' => 'A', 
            ':tipolancamento' => 'R',
            ':tipodocto' => 'B',
            ':numdoc' => $numDoc,
            ':pessoa' => $pessoa
        ];
        
        // Salva SQL e binds para debug
        $debugInfo = [
            'sql' => preg_replace('/\s+/', ' ', trim($sql)),
            'binds' => $binds,
            'origem' => $origem
        ];
        
        // Executa a consulta usando PDO
        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        
        // Faz o bind dos parâmetros
        foreach($binds as $param => $value) {
            $banco->bindValue($param, $value);
        }
        
        $banco->execute();
        $resultado = $banco->fetchAll();
        
        // Retorna resultado ou informações de debug se não encontrar registros
        if (!empty($resultado)) {
            return $resultado;
        } else {
            return [
                'status' => false,
                'message' => 'Nenhum registro encontrado',
                'debug' => $debugInfo,
                'rowCount' => $banco->rowCount()
            ];
        }
        
    } catch (Exception $e) {
        return [
            'status' => false,
            'message' => $e->getMessage(),
            'debug' => isset($debugInfo) ? $debugInfo : ['sql' => preg_replace('/\s+/', ' ', trim($sql ?? '')), 'binds' => $binds ?? []]
        ];
    }
} //fim selectAllBoletos








}	//	END OF THE CLASS
?>
