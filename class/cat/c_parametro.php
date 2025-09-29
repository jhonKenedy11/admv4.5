<?php
/**
 * @package   admv4.3.2
 * @name      c_paramentro
 * @version   4.3.20
 * @copyright 2021
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      04/06/2021
 */

$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");

//Class C_PARAMENTOS
Class c_parametros extends c_user {

     /*
     * TABLE NAME CAT_PARAMETROS
     */     
// Campos tabela
// public $id = NULL; //smallint
// public $nome = NULL;  //varchar(60)
/*
`ID`,
`SITUACAOINCLUSAO`,
`SITAGATENDIMENTO`,
`SITEMATENDIMENTO`,
`SITSOLICITARPECA`,
`SITAGPECA`,
`SITPECARECEBIDA`,
`SITAPORCAMENTO`,
`SITFINALIZADO`,
`LOCALATENDIMENTO`,
`TIPOINTERVENCAO`,
`MSGATENDIMENTO`,
`MSGORCAMENTO`,
`CONTROLEESTOQUE`,
`TIPODOCCOBRANCA`,
`CONDPGTO`,
`CONTA`,
`GENERO`,
`CENTROCUSTO`,
`CREATED_USER`,
`UPDATED_USER`,
`CREATED_AT`,
`UPDATED_AT`
*/

//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    //session_start();
    c_user::from_array($_SESSION['user_array']);

}


 /**
 * @name existeParametro
 * @description pesquisa se já existe código do banco
 */
public function existeParametros(){

	$sql  = "SELECT * ";
	$sql .= "FROM cat_parametros ";
	$sql .= "WHERE (id = ".$this->__get('id').")";
//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);	
} //fim existeParametro

 /**
 * @name select_parametros
 * @description pesquisa que retorna os campos do id pesquisado tabela cat_parametros
 */
public function select_parametros(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_parametros ";
   	$sql .= "WHERE (id = ".$this->__get('id').") ";
   	
   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_parametro

 /**
 * @name select_parametro_geral
 * @description pesquisa que retorna todos os registros cadastrado cat_parametros
 */
public function select_parametros_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_parametros ";
   	$sql .= "ORDER BY id ";
   	
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

} //fim select_parametros_geral

 /**
 * @name incluiParametros
 * @description faz a inclusão do registro cadastrado
 */
public function incluiParametros(){

	$sql  = "INSERT INTO cat_parametros (ID, SITUACAOINCLUSAO, SITAGATENDIMENTO, SITEMATENDIMENTO, SITSOLICITARPECA,
                SITAGPECA, SITPECARECEBIDA, SITAPORCAMENTO, SITFINALIZADO, LOCALATENDIMENTO, TIPOINTERVENCAO, MSGATENDIMENTO,
                MSGORCAMENTO, CONTROLEESTOQUE, TIPODOCCOBRANCA, CONDPGTO, CONTA, GENERO, CENTROCUSTO, CREATED_USER, CREATED_AT) ";
	$sql .= "VALUES (NULL,";
        $sql .= $this->__get('situacaoinclusao').",".
                $this->__get('sitagatendimento').",".
                $this->__get('sitematendimento').",".
                $this->__get('sitsolicitarpeca').",".
                $this->__get('sitagpeca').",".
                $this->__get('sitpecarecebida').",".
                $this->__get('sitaporcamento').",".
                $this->__get('sitfinalizado').",";
        $sql .= "NULL, NULL, '".
                //$this->__get('localatendimento')."','".
                //$this->__get('tipointervencao')."','".
                $this->__get('msgatendimento')."','".
                $this->__get('msgorcamento')."',";
        $sql .= "NULL, NULL,".
                //$this->__get('controleestoque').",".
                //$this->__get('tipodoccobranca').",'".
                $this->__get('condpgto').",".
                $this->__get('conta').",'".
                $this->__get('genero')."',".
                $this->__get('centrocusto').",".
                $this->m_userid.",'".date("Y-m-d H:i:s"). "' )";
					
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->close_connection();
        //echo strtoupper($sql)."<BR>";

	return $banco->result;
} // fim incluiParametros

 /**
 * @name alteraParametros
 * @description altera registro existente
 */
public function alteraParametros(){

        $sql = "UPDATE cat_parametros ";
        $sql .= "SET ";
        $sql .= "situacaoinclusao = ".$this->__get('situacaoinclusao').", ";
        $sql .= "sitagatendimento = ".$this->__get('sitagatendimento').", ";
        $sql .= "sitematendimento = ".$this->__get('sitematendimento').", ";
        $sql .= "sitsolicitarpeca = ".$this->__get('sitsolicitarpeca').", ";
        $sql .= "sitagpeca = ".$this->__get('sitagpeca').", ";
        $sql .= "sitpecarecebida = ".$this->__get('sitpecarecebida').", ";
        $sql .= "sitaporcamento = ".$this->__get('sitaporcamento').", ";
        $sql .= "sitfinalizado = ".$this->__get('sitfinalizado').", ";
        $sql .= "localatendimento = '".$this->__get('localatendimento')."', ";
        $sql .= "tipointervencao = '".$this->__get('tipointervencao')."', ";
        $sql .= "msgatendimento = '".$this->__get('msgatendimento')."', ";
        $sql .= "msgorcamento = '".$this->__get('msgorcamento')."',";
        //$sql .= "controleestoque = '".$this->__get('controleestoque')."', ";
        //$sql .= "tipodoccobranca = '".$this->__get('tipodoccobranca')."', ";
        $sql .= "condpgto = '".$this->__get('condpgto')."',";
        $sql .= "conta = ".$this->__get('conta').",";
        $sql .= "genero = '".$this->__get('genero')."',";
        $sql .= "centrocusto = ".$this->__get('centrocusto').",";
        $sql .= "updated_user = '".$this->m_userid."',";
        $sql .= "updated_at = '".date("Y-m-d H:i:s")."' ";
	$sql .= "WHERE id = ".$this->__get('id').";";
       
	$banco = new c_banco;
	$result = $banco->exec_sql($sql);
	$banco->close_connection();
        //echo strtoupper($sql)."<BR>";

        return $banco->result;
}  // fim alteraParametros

 /**
 * @name exlcuiBanco
 * @description esclui resgistro existe
 */
public function excluiParametros(){

	$sql  = "DELETE FROM cat_parametros ";
	$sql .= "WHERE id = ".$this->__get('id');
	$banco = new c_banco;
	$res_acessorio =  $banco->exec_sql($sql);
	$banco->close_connection();

	return $banco->result;
	
}  // fim excluiParametros

}	//	END OF THE CLASS
?>
