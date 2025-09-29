<?php


Class c_banco {

	/* variaveis publicas */
	public $intNumTuplas;
	public $strErros;
	public $arrayExcluidoBusca;
	public $arrayVals;
	public $msg;
	public $resultado = NULL;	// resultado caso seja uma operacao "SELECT" mysql_fetch_assoc
	public $result = NULL;	// resultado caso seja uma operacao "SELECT"

	/* variaveis privadas */
        private $sqlStrtoupper = true;
	private $mostrarSqlComentado = false;
	private $sql;
	private $resource;
	private $arrayName;
	private $arrayType;
	private $tabela;
	public $id_connection;
	public $db;
	public $id_db;
	

//construtor----------------------------------------------------------
//	function c_bancoMysql(){				
	function c_banco(){				
		$this->open_connection();
	}

//public-------------------------------------------------------------
	function open_connection() {
            try {
            
                $this->id_connection =
                            mysql_pconnect (HOSTNAME, DB_USER, DB_PASSWORD);

                if(!$this->id_connection):
                    throw new Exception( "Não foi possivel conectar ao servidor! ");
                    //   return $this->confErro("Não foi possivel conectar ao servidor");
                else:
                    mysql_set_charset(DB_CHARSET, $this->id_connection);                
                    $this->id_db = @mysql_select_db(DB_NAME, $this->id_connection); // select db
                    if (!$this->id_db):
                        throw new Exception( "Não foi possivel conectar ao bando de dados! ");
                        //return $this->confErro("Não foi possivel conectar ao bando de dados");
                    endif;
                endif;
            }catch (Exception $e) {
                //$this->incluiLog($operacao, $this->sql);
                return $this->resultado = 'Exceção capturada: '.  $e->getMessage(). "\n";
            }
            //return $this->id_db;
		   		   
     }

//public-------------------------------------------------------------
	function close_connection() {
    	@mysql_close($this->id_connection);
}



//public-------------------------------------------------------------
	function inicioTransacao($db){
            mysql_query($db, "SET AUTOCOMMIT=0");
            mysql_query($db, "START TRANSACTION");            
	}

//public-------------------------------------------------------------
	function commit($db){
            mysql_query($db, "COMMIT");
            mysql_query($db, "SET AUTOCOMMIT=1");
            
	}

//public-------------------------------------------------------------
	function rollback(){
            mysql_query($db, "ROLLBACK");
            mysql_query($db,"SET AUTOCOMMIT=1");
            
	}

//public-------------------------------------------------------------
function geraID($gen) {

}

//public-------------------------------------------------------------
function geraMax($tabela, $campo, $id, $idValue) {
	$sql = "select max(".$campo.") as ultimo from ".$tabela." where (".$id."=".$idValue.")";
//	echo $sql;
	$this->exec_sql($sql);
}
//---------------------------------------------------------------
//---------------------------------------------------------------
public function incluiLog($operacao, $sqlSave){
        $log = '';

        $log = $_SESSION['user_array'];
        $sql  = "INSERT INTO AMB_LOG (USUARIO, DATA, OPERACAO, FORM, SQL) ";
	$sql .= "VALUES (".$log[0].", '".date("Y-m-d H:i:s")."', '".$operacao."', '".$log[7]."', '".$sqlSave."') ";
        //echo $sql;
        $this->exec_sql($sql);
} // fim incluiLog

//public-------------------------------------------------------------
// $operacao define o tipo da operacao a ser efetuada no banco;
function exec_sql($sql) {
		
         try {
            if($this->mostrarSqlComentado){
                               echo "\n<-- \n\tClasse: ".$this->eClasse." \n\tTabela: ".$this->tabela." \n\tSql: ".strtoupper($sql)." \n-->\n\n";
            }

            $this->result = true;
            $operacao = strtoupper(substr(trim($sql), 0, 6));
            if($this->sqlStrtoupper){
                       $this->sql = strtoupper($sql);}
            else {
                       $this->sql = $sql;}

            if ($this->id_db){
                if ($operacao == 'SELECT'){
                    //echo "passou ".$this->sql;
                    //   $result = mysql_query($this->sql) or die(mysql_error());
                    $result = mysql_query($this->sql);
                    if(!$result) {
                        throw new Exception( "Query inválida!" );
                    }
                    $i = 0;
                    while ($row = mysql_fetch_assoc($result)) {
                        $pesquisa[$i] = $row;
                        $i++;
                    }
                    $this->resultado = $pesquisa;
                    $this->result = $result;
                    $this->intNumTuplas =  mysql_num_rows($result);
                }
                else {
                        //echo "passou ".$this->sql;
                        $result = mysql_query($this->sql); //or die("Erro: ".mysql_errno()." ".mysql_error());
                        if(!$result) {
                            throw new Exception( "Query inválida! ".$this->sql);
                        }
                        $this->resultado = $result;
                        $this->result = $result;
                }
              }
              else{
                       $this->resultado = false;  // erro na conexao com o banco
              }
         }catch (Exception $e) {
                //$this->incluiLog($operacao, $this->sql);
                $this->resultado = 'Exceção capturada: '.  $e->getMessage(). "\n";
                $this->result = false;
                throw new Exception($e->getMessage());
                
            }
         return  $this->resultado;

        }//exec_sql

//public-------------------------------------------------------------
// $operacao define o tipo da operacao a ser efetuada no banco;
	function info_campos($sql) {

		if ($this->id_connection){
      		$result = mysql_query($this->id_db, $sql);
      		$coln = mysql_num_fields($result);
      		return $coln;

   		}
   		else{
     		return  FALSE;  // erro na conexao com o banco
   		}

	}//info_campos



//public-------------------------------------------------------------
	function getParametros($campo){
		$sql = "select ".$campo." from ".$this->tabela;
//		echo "passou ".$sql;
   		$row = $this->exec_sql($sql);
		$teste_array = is_array($row);
//		echo  "passou ".$row[0][$campo];
		if ($teste_array){
			return $row[0][$campo];	}
		else { return null;}	

	} // getParametros

//public-------------------------------------------------------------
	function getField($campos, $condicoes = false, $order = false, $limit = false){
		if($condicoes == false ){
			$condicoes = " 1 = 1";			
		}
	
		if($order !== false){
			$order = " order by ".$order;
		}
	
		if($limit !== false){
			$limit = " limit ".$limit;
		}
	
		$sql = "select $campos from ".$this->tabela. " where $condicoes $order ";
//		echo "passou ".$sql;
   		$row = $this->exec_sql($sql);
		$teste_array = is_array($row);
		if ($teste_array){
			return $row[0][$campos];	}
		else { return null;}	


	} // get

//---------------------------------------------------------------------	
	//public
	function setTab($tab){
		$this->tabela = $tab;
	}
	
// END OF THE CLASS
} // C_BANCO
?>
