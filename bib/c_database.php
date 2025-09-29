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
	public $row = NULL;	// linhas afetadas pelo insert ou delete
	public $insertReg = NULL;// id insert 

	/* variaveis privadas */
    private $sqlStrtoupper = true;
	private $mostrarSqlComentado = false;
	private $sql;
	private $resource;
	private $arrayName;
	private $arrayType;
	private $tabela;
	public $id_connection;
	public $conn;
	public $id_db;
	
	/* news variables to replace PDO */
	private $mysqli;
	private $stmt;

//construtor----------------------------------------------------------
//	function c_bancoMysql(){				
	function __construct(){				
		$this->open_connection();
	}

//public-------------------------------------------------------------
	function open_connection() {
		try {
			$this->id_connection = @mysqli_connect (HOSTNAME, DB_USER, DB_PASSWORD, DB_NAME, PORT);

			$this->mysqli = $this->id_connection;

			if(mysqli_connect_errno()){
				throw new Exception( "Não foi possivel conectar ao servidor! ");
			}

			mysqli_set_charset($this->id_connection, 'utf8');

		} catch (Exception $e) {
			//$this->incluiLog($operacao, $this->sql);
			return $this->resultado = 'Exceção capturada: '.  $e-> $e->getMessage(). "\n";
		}
		   		   
    }

//public-------------------------------------------------------------
	function close_connection($conn=null) {
        if (!isset($conn)):
            $conn = $this->id_connection;
        endif;
    	@mysqli_close($conn);
}



//public-------------------------------------------------------------
	function inicioTransacao($conn){
            mysqli_autocommit($conn, FALSE);
            //mysqli_query($conn, "START TRANSACTION");            
	}

//public-------------------------------------------------------------
	function commit($conn){
            mysqli_commit($conn);
            mysqli_autocommit($conn, TRUE);
            //mysqli_query($conn, "COMMIT");
            //mysqli_query($conn, "SET AUTOCOMMIT=1");
            
	}

//public-------------------------------------------------------------
	function rollback($conn){
            mysqli_rollback($conn);
            mysqli_autocommit($conn, TRUE);

//            mysqli_query($conn, "ROLLBACK");
//            mysqli_query($conn, "SET AUTOCOMMIT=1");
            
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
public function incluiLog($id, $operacao, $sqlSave, $conn){

	$pos = strpos($sqlSave, 'FAT_PEDIDO');
	if (!($pos === false)) {
		$pos = strpos($sqlSave, 'FAT_PEDIDO_ITEM');
		if (!($pos === false))
			$this->tabela = 'FAT_PEDIDO_ITEM';
		else {
			$this->tabela = 'FAT_PEDIDO';
			$logSession = json_decode($_SESSION['user_array'], true);
			$sqlSave = str_replace("'","",$sqlSave);
			$sqlSave = str_replace("\n","",$sqlSave);
			$sqlSave = str_replace("\r","",$sqlSave);

			$sql  = "INSERT INTO AMB_LOG (ID_TABLE, USERINSERT, OPERACAO, TABELA, SQLSAVE) ";
			$sql .= "VALUES (".$id.", ".$logSession[0].", '".$operacao."', '".$this->tabela."', '".$sqlSave."') ";
			//echo $sql;
			$resultLog = mysqli_query($conn, $sql);
		}
	}

	//throw new Exception( "Query inválida! ".mysqli_error($conn)."<br>".$sql);

} // fim incluiLog



function exec_sql_lower_case($sql, $conn=null) {
	
	if (!isset($conn)):
		$conn = $this->id_connection;
	endif;
	
	try {
		if($this->mostrarSqlComentado){
		   echo "\n<-- \n\tClasse: ".$this->eClasse." \n\tTabela: ".$this->tabela." \n\tSql: ".strtoupper($sql)." \n-->\n\n";
		}

		$this->result = true;
		$operacao = strtoupper(substr(trim($sql), 0, 6));
		//if($this->sqlStrtoupper){
		//	$this->sql = strtr($sql ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ");}
		//else {
		//	$this->sql = $sql;
		//}
		$this->sql = $sql;
		if ($conn){
			if ($operacao == 'SELECT'){
				//echo "passou ".$this->sql;
				//   $result = mysql_query($this->sql) or die(mysql_error());
				$result = mysqli_query($conn, $this->sql);
				if(!$result) {
					throw new Exception( "Query inválida! ".mysqli_error($conn)."<br>".$this->sql);
				}
				$i = 0;
				while ($row = mysqli_fetch_assoc($result)) {
					$pesquisa[$i] = $row;
					$i++;
				}
				$this->resultado = $pesquisa;
				$this->result = $result;
				$this->intNumTuplas =  mysqli_num_rows($result);
			}
			else {
					//echo "passou ".$this->sql;
					$result = mysqli_query($conn, $this->sql); //or die("Erro: ".mysql_errno()." ".mysql_error());
					if(!$result) {
						throw new Exception( "Query inválida! ".mysqli_error($conn)."<br>".$this->sql);
					}
					$this->row = mysqli_affected_rows($conn);
					$this->insertReg = mysqli_insert_id($conn);
					$this->resultado = $result;
					$this->result = $result;
					$this->incluiLog($this->insertReg, $operacao, $this->sql, $conn);
			}
		  }
		  else{
				   $this->resultado = false;  // erro na conexao com o banco
		  }
	 }catch (Exception $e) {
			//$this->incluiLog($operacao, $this->sql);
			$error = mysqli_error($conn);
			$this->resultado = 'Exceção capturada: '.  $e->getMessage(). "\n";
			$this->result = false;
			throw new Exception($e->getMessage());
			
		}
	 return  $this->resultado;

	}//exec_sql

//public-------------------------------------------------------------
// $operacao define o tipo da operacao a ser efetuada no banco;
function exec_sql($sql, $conn=null, $tabela=null) {
	
        if (!isset($conn)):
            $conn = $this->id_connection;
        endif;
        
        try {
            if($this->mostrarSqlComentado){
               echo "\n<-- \n\tClasse: ".$this->eClasse." \n\tTabela: ".$this->tabela." \n\tSql: ".strtoupper($sql)." \n-->\n\n";
            }

            $this->result = true;

            $operacao = strtoupper(substr(trim($sql), 0, 6));

            if($this->sqlStrtoupper){
                $this->sql = strtoupper(strtr($sql ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));
				// if($tabela == "showSql"){
				// 	echo $this->sql;
				// 	die;
				// }
			} else {
                $this->sql = $sql;
			}

            if ($conn){
				// $this->sql = htmlspecialchars($this->sql, ENT_COMPAT, 'UTF-8');				
                if ($operacao == 'SELECT'){
                    //echo "passou ".$this->sql;
                    //   $result = mysql_query($this->sql) or die(mysql_error());
                    $result = mysqli_query($conn, $this->sql);
                    if(!$result) {
                        throw new Exception( "Erro na consulta do registro! <br>".$this->sql);
                    }
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $pesquisa[$i] = $row;
                        $i++;
                    }
                    $this->resultado = $pesquisa;
                    $this->result = $result;
                    $this->intNumTuplas =  mysqli_num_rows($result);
                }
                else {
					//echo "passou ".$this->sql;
					$result = mysqli_query($conn, $this->sql); //or die("Erro: ".mysql_errno()." ".mysql_error());
					if(!$result) {
						throw new Exception( $conn->error . " <br>Erro na gravação do registro! <br>".$this->sql);
					}
					$this->row = mysqli_affected_rows($conn);
					$this->insertReg = mysqli_insert_id($conn);
					$this->resultado = $result;
					$this->result = $result;
					$this->incluiLog($this->insertReg, $operacao, $this->sql, $conn);
                }
              }
              else{
                       $this->resultado = false;  // erro na conexao com o banco
              }
         }catch (Exception $e) {
				// $this->incluiLog($operacao, $this->sql, $conn);
				$error = mysqli_error($conn);
				$this->resultado = $e->getMessage(). "\n";
				// $this->resultado = false;
                $this->result = false;
                // throw new Exception($e->getMessage()). "\n";
                
            }
         return  $this->resultado;

        }//exec_sql

//public-------------------------------------------------------------
// $operacao define o tipo da operacao a ser efetuada no banco;
	function info_campos($sql) {

		if ($this->id_connection){
      		$result = mysqli_query($this->id_connection, $sql);
      		$coln = mysqli_num_fields($result);
      		return $coln;

   		}
   		else{
     		return  FALSE;  // erro na conexao com o banco
   		}

	}//info_campos



//public-------------------------------------------------------------
	function getParametros($campo, $where = NULL){
		$sql = "select ".$campo." from ".$this->tabela;
//		echo "passou ".$sql;
		if ($where != NULL) {
			$sql .= $where;
		}

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

	function getRecord($condicoes = false, $order = false, $limit = false){
		if($condicoes == false ){
			$condicoes = " 1 = 1";			
		}
	
		if($order !== false){
			$order = " order by ".$order;
		}
	
		if($limit !== false){
			$limit = " limit ".$limit;
		}
	
		$sql = "select * from ".$this->tabela. " where ".$condicoes;
//		echo "passou ".$sql;
   		$row = $this->exec_sql($sql);
		$teste_array = is_array($row);
		if ($teste_array){
			return $row;	}
		else { return null;}	


	} // get

//---------------------------------------------------------------------	
	//public
	function setTab($tab){
		$this->tabela = $tab;
	}

	//public
	function setField($id, $field, $value){
		$sql = "update ".$this->tabela. " set ".$field."=".$value." where id= ".$id;
			$result = $this->exec_sql($sql);
	}


	// News functions to replace PDO
	public function prepare($query) {

        $this->stmt = $this->mysqli->prepare($query);

        if (!$this->stmt) {
            die("Erro no prepare: " . $this->mysqli->error);
        }
    }

	public function bind($types, ...$params) {
        if (!$this->stmt) {
            die("Nenhum statement preparado.");
        }

        $this->stmt->bind_param($types, ...$params);
    }

	public function execute() {
        if (!$this->stmt) {
            die("Nenhum statement preparado.");
        }

        return $this->stmt->execute();
    }

	public function fetchAllAssoc() {

        $result = $this->stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchOneAssoc() {
		
        $result = $this->stmt->get_result();
        return $result->fetch_assoc();
    }

    public function close() {

        if ($this->stmt) {
            $this->stmt->close();
        }

        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

	
	
// END OF THE CLASS
}
?>