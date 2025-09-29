<?php
/**
 * @package   astecv3
 * @name      c_data_entrega_bloqueio
 * @version   3.0.00
 * @copyright 2023
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy<jhon.kened11@gmail.com>
 * @date      15/05/2023
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

Class c_data_entrega_bloqueio extends c_user {

	/*
	* TABLE NAME C_FIN_CLIENTE_ACOMP
	*/     
	public $id = NULL; //smallint


	//construtor
	function __construct(){

	}

	/**
	* METODOS DE SETS E GETS
	*/
	public function setId($id){
			$this->id = $id;
	}

	public function getId(){
	         return $this->id;
	}

	//############### FIM SETS E GETS ###############

	/**
	 * @name existeDataEntregaBloqueio
	 * @description pesquisa se já existe código da data
	 */
	public function existeDataEntregaBloqueio(){

		$sql  = "SELECT * ";
		$sql .= "FROM fin_cliente_acomp ";
		$sql .= "WHERE ATIVIDADE = 999 AND RESULTADO = 'ENTREGA-BLOQUEADA';";
	//	ECHO $sql;

		$banco = new c_banco();
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;	
	} //fim existeDataEntregaBloqueio

    /**
     * blocked data search
     * @name blockedDataSearch
     * @return ARRAY true and false
     * @version 02052023
     * @author Jhon Kenedy <jhon.kened11@gmail.com>
     * @param date and parameters
     */
    function blockedDataSearch($date, $param=null) {

        $date = c_date::convertDateBd($date, $this->m_banco);

        $new_date = explode(' ', $date);
        $date_ini = $new_date[0] . ' 00:00:00';
        $date_end = $new_date[0] . ' 23:59:59';

        $sql = "SELECT DATA FROM fin_cliente_acomp ";
        $sql .= "WHERE ATIVIDADE = 999 and RESULTADO = 'ENTREGA-BLOQUEADA' and ";
        $sql .= "DATA BETWEEN '" . $date_ini . "' and '" . $date_end . "';";

        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        return $consulta->resultado;
        
    }

        /**
     * blocked data delete
     * @name blockedDataSearch
     * @return ARRAY true and false
     * @version 02052023
     * @author Jhon Kenedy <jhon.kened11@gmail.com>
     * @param date and parameters
     */
    function blockedDataDelete($date, $param=null) {

        $date = c_date::convertDateBd($date, $this->m_banco);
        
        $sql = "DELETE FROM fin_cliente_acomp ";
        $sql .= "WHERE ATIVIDADE = 999 and RESULTADO = 'ENTREGA-BLOQUEADA' and ";
        $sql .= "DATA = '" . $date . "';";

        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        return $consulta->resultado;
        
    }

}	//	END OF THE CLASS
?>
