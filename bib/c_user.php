<?php

/****************************************************************************
*Cliente...........:
*Contratada........: Infosystem
*Desenvolvedor.....: Marcio Sergio da Silva
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: C_USER - Dados usuario e Atualiza dados comum ao sistema
*Ultima Atualizacao: 26/08/05
****************************************************************************/

define( "msgAdd", "Dados adicionados com SUCESSO!!!");
define( "msgNotAdd", "Dados NÃO foram adicionados!!!");
define( "msgUpdate", "Dados alterados com SUCESSO!!!");
define( "msgNotUpdate", "Dados NÂO foram alterados!!!");
define( "msgDelete", "Dados excluídos com SUCESSO!!!");
define( "msgNotDelete", "Dados NÂO foram escluídos!!!");

define( "typSuccess", "sucesso");
define( "typAlert", "alerta");
define( "typError", "error");

Class c_user {

public $m_userid = 0;
public $m_usernome = "";
public $m_usersenha = "";
public $m_usergrupo = "";
public $m_useremail = "";
public $m_usercusto = "";
public $m_empresaid = "";
public $m_empresanome = "";
public $m_empresafantasia = "";
public $m_empresacentrocusto = "";
public $m_programa = "";
public $m_pesquisa = NULL;
public $m_empresacliente = ""; 
public $m_banco = "mysql"; 
public $m_dircliente = ""; 
public $m_configsmtp = ""; 
public $m_configemail = ""; 
public $m_configemailsenha = ""; 


public $cat = false;


//=======================================================================
// get set default
public function __set($property, $value) {
	// if (property_exists($this, $property)) {
	  $this->$property = $value;
	// }
}

public function __get($property) {
	// if (property_exists($this, $property)) {
	  return $this->$property;
	// }
}

public function __setNumber($property, $value, $decimal, $format=false) {
	if (property_exists($this, $property)) {
		$this->$property = ($format ? number_format($value, $decimal, ',', '.') : $value);
	}    
}

public function __getNumber($property, $decimal, $format = null) {
	if (property_exists($this, $property)) {
		return ($format == null ? $this->$property : 
			($format == 'F' ? number_format($this->$property, $decimal, ',', '.') : c_tools::moedaBd($this->$property)));
	} else { return null; }
}

public function __setDate($property, $value, $format=false) {
	if (property_exists($this, $property)) {
		$this->$property = ($format ? date('d/m/Y', strtotime($value)) : $value);
	}    
}

public function __getDate($property, $format = null) {
	if (property_exists($this, $property)) {
		$this->$property = strtr($this->$property, "/","-");
		return ($format == null ? $this->$property : 
			($format == 'F' ? date('d/m/Y', strtotime($this->$property)) : c_date::convertDateBdSh($this->$property, $this->m_banco)));
	} else { return null; }
}

public function __setDateTime($property, $value, $format=false) {
	if (property_exists($this, $property)) {
		$this->$property = ($format ? date('d/m/Y H:i:s', strtotime($value)) : $value);
	}    
}

/**
 * <b> Funcao para retornar Data formatada para banco, apresentação ou null Usado: em todas classes GET </b>
 * @param datetime $property  valor original a ser formatado
 * @param char $format tipo do formato de retorno, F - formatação para form / B - formatação para banco / NULL - retorna conteudo original
 * @return datetime
 */
public function __getDateTime($property, $format = null) {
	if (property_exists($this, $property)) {
		$this->$property = strtr($this->$property, "/","-");
		return ($format == null ? $this->$property : 
			($format == 'F' ? date('d/m/Y H:i:s', strtotime($this->$property)) : c_date::convertDateBd($this->$property, $this->m_banco)));
	} else { return null; }
}


//====================================    


//-------------------------------------------------------------
public function c_user(){
//        echo "user=".$_COOKIE['dircliente'];
//        include $_SESSION['dircliente']."/parSistema.php";
    
}


public function exception_handler($e) {
    if (is_a($e, 'httpException')) {
        $e->header();
        $title = $e->getCode() . ' Error';
    } else {
        $title = get_class($e);
    }

	$this->m_tipoMsg = 'error';
	switch ($this->m_submenu) {
		case 'inclui':
			$this->m_submenu = 'cadastrar';
			break;
		case 'cadastraNf':
			$this->m_submenu = 'cadastrar';
			break;
		case 'altera':
			$this->m_submenu = 'alterar';
			break;
		case 'geraXML':
			$this->m_submenu = 'geraXML';
			break;
		default:
			$this->m_submenu = '';
	}

	$this->$m_msg = $e->getMessage();
	// $this->$m_msg = $e->xdebug_message;
    $this->controle();

}


//-------------------------------------------------------------

function setPesquisa($pesquisa) {
	$this->m_pesquisa = $pesquisa;
}

function getPesquisa() {
	return $this->m_pesquisa;
}

public function getUserId() {
	return $this->m_userid;
}
public function setUserId() {
	return $this->m_userid;
}


//-------------------------------------------------------------
function set_dados($usernome, $empresa) {
	$usernome = trim($usernome);
	$this->m_usernome = strtoupper($usernome);
	$this->m_empresaid = $empresa;
	$this->busca_dados();
}

//-------------------------------------------------------------
function to_array() {
	//echo ("<br>to array");
	$array[0] = $this->m_userid;
	$array[1] = $this->m_usernome;
	$array[2] = $this->m_usersenha;
	$array[3] = $this->m_useremail;
	$array[4] = $this->m_empresaid;
	$array[5] = $this->m_empresanome;
	$array[6] = $this->m_empresafantasia;
	$array[7] = $this->m_programa;
	$array[8] = $this->m_pesquisa;
	$array[9] = $this->m_empresacliente;
	$array[10] = $this->m_usercusto;
	$array[11] = $this->m_empresacentrocusto;
	$array[12] = $this->m_dircliente;
	$array[13] = $this->m_usergrupo;
	$array[14] = $this->m_configsmtp;
	$array[15] = $this->m_configemail;
	$array[16] = $this->m_configemailsenha;
	//	print_r($array);
	$json = json_encode($array);
	return $json;
	// return $array;
}

//-------------------------------------------------------------
function from_array($json) {
	$array = json_decode($json, true);
	// $array = $json;
//	echo ("<br>from array");
	$this->m_userid = $array[0];
	$this->m_usernome = $array[1];
	$this->m_usersenha = $array[2];
	$this->m_usergrupo = $array[13];
	$this->m_useremail = $array[3];
	$this->m_empresaid = $array[4];
	$this->m_empresanome = $array[5];
	$this->m_empresafantasia = $array[6];
	$this->m_programa = $array[7];
	$this->m_pesquisa = $array[8];
	$this->m_empresacliente = $array[9];
	$this->m_usercusto = $array[10];
	$this->m_empresacentrocusto = $array[11];
	$this->m_dircliente = $array[12];
	$this->m_configsmtp = $array[14];
	$this->m_configemail = $array[15];
	$this->m_configemailsenha = $array[16];

        //	print_r($array);
//	print_r($this->m_usernome);
}

//-------------------------------------------------------------
function busca_dados(){
	
//	echo	"<br>empresa selecionado -> $this->m_empresa<br>";
	$sql  = "SELECT amb_usuario.usuario, amb_usuario.senha, amb_usuario.salario, amb_usuario.grupo, ";
	$sql .= "amb_empresa.empresa, amb_empresa.nomeempresa, amb_empresa.nomefantasia, amb_empresa.cliente, ";
    $sql .= "amb_empresa.centrocusto, amb_usuario.smtp, amb_usuario.email, amb_usuario.emailsenha ";
	$sql .= "FROM amb_usuario, amb_empresa ";
	$sql .= "WHERE ((amb_empresa.empresa = ".$this->m_empresaid.")  AND (amb_usuario.nome = '".$this->m_usernome."')) ";
	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);

	$teste_array = is_array($banco->resultado);
	if (isset($teste_array)){
            $this->m_userid = $banco->resultado[0]['USUARIO'];
            $this->m_usersenha = $banco->resultado[0]['SENHA'];
            $this->m_usergrupo = $banco->resultado[0]['GRUPO'];
            $this->m_usercusto = $banco->resultado[0]['SALARIO'];
            $this->m_empresanome = $banco->resultado[0]['NOMEEMPRESA'];
            $this->m_empresafantasia = $banco->resultado[0]['NOMEFANTASIA'];
            $this->m_configsmtp = $banco->resultado[0]['SMTP'];
            $this->m_configemail = $banco->resultado[0]['EMAIL'];
            $this->m_configemailsenha = $banco->resultado[0]['EMAILSENHA'];
			//admv4.0
			$this->m_empresacentrocusto = $banco->resultado[0]['CENTROCUSTO'];
			$banco->close_connection();
			return true;
            // if (($banco->resultado[0]['CCUSTOPGTO'] == 0) OR ($banco->resultado[0]['CCUSTOPGTO'] == $banco->resultado[0]['CENTROCUSTO'])){
            //     $this->m_empresacentrocusto = $banco->resultado[0]['CENTROCUSTO'];
            //     return true;
            //     }
            // else{
            //     return false;
            // }

	}
	else
		$banco->close_connection();
        return false;
}
//-------------------------------------------------------------
//---busca dados do usuario
function busca_dados_user($user){
	
//	echo	"<br>empresa selecionado -> $this->m_empresa<br>";
	$sql  = "SELECT * ";
	$sql .= "FROM amb_usuario ";
	$sql .= "WHERE (usuario = ".$user.") ";
	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	
	$teste_array = is_array($banco->resultado);
	if (isset($teste_array))
		return $banco->resultado;
	else
		return '';
	
	return $banco->intNumTuplas;
}

//-------------------------------------------------------------
function busca_dadosCliente(){
	
//	echo	"<br>empresa selecionado -> $this->m_empresa<br>";
	$sql  = "SELECT amb_usuario.usuario, amb_usuario.senha, fin_cliente.cliente as empresa, fin_cliente.nome as nomeempresa, fin_cliente.nomereduzido as nomefantasia ";
	$sql .= "FROM amb_usuario, fin_cliente ";
	$sql .= "WHERE ((fin_cliente.cliente = ".$this->m_empresaid.")  AND (amb_usuario.nome = '".$this->m_usernome."')) ";
	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	
	$teste_array = is_array($banco->resultado);
	if (isset($teste_array)){
      	$this->m_userid = $banco->resultado[0]['USUARIO'];
      	$this->m_usersenha = $banco->resultado[0]['SENHA'];
        $this->m_empresanome = $banco->resultado[0]['NOMEEMPRESA'];
        $this->m_empresafantasia = $banco->resultado[0]['NOMEFANTASIA'];
        $this->m_empresacliente = $banco->resultado[0]['EMPRESA'];
	}
		
	return $banco->intNumTuplas;
}

//-------------------------------------------------------------
function busca_dadosEmpresaCC($cc){
	
//	echo	"<br>empresa selecionado -> $this->m_empresa<br>";
	$sql  = "SELECT * ";
	$sql .= "FROM amb_empresa ";
	$sql .= "WHERE (centrocusto = ".$cc.") ";
//	echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	
	return $banco->resultado;
	} //busca_dadosEmpresa
//-------------------------------------------------------------
//-------------------------------------------------------------
function busca_dadosEmpresa(){
	
	$sql  = "SELECT f.userlogin, f.password, e.nomeempresa, f.nomereduzido, F.NOME, f.centrocusto, f.email, F.CLIENTE, ";
	$sql .= "e.empresa, E.NOMEEMPRESA, E.NOMEFANTASIA FROM fin_cliente f ";
	$sql .= "inner join  amb_empresa e on (e.centrocusto=f.centrocusto) ";
	$sql .= "WHERE (f.userlogin = '".$this->m_usernome."') and ";
	$sql .= "(f.password = '".$this->m_usersenha."')";
//	echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	
	$teste_array = is_array($banco->resultado);
	if (isset($teste_array)){
            $this->m_userid = $banco->resultado[0]['USERLOGIN'];
            $this->m_usernome = $banco->resultado[0]['NOME'];
            $this->m_usersenha = $banco->resultado[0]['PASSWORD'];
            $this->m_usergrupo = '';
            $this->m_useremail = $banco->resultado[0]['EMAIL'];
            $this->m_empresaid = $banco->resultado[0]['EMPRESA'];
            $this->m_empresanome = $banco->resultado[0]['NOMEEMPRESA'];
            $this->m_empresafantasia = $banco->resultado[0]['NOMEFANTASIA'];
            $this->m_empresacliente = $banco->resultado[0]['CLIENTE'];
            $this->m_usercusto = 0;
            $this->m_empresacentrocusto = $banco->resultado[0]['CENTROCUSTO'];
            return true;
	}
	else
            return false;
		
} //busca_dadosEmpresa
//-------------------------------------------------------------
//-------------------------------------------------------------
function busca_dadosUserPessoa(){
	
	$sql  = "SELECT amb_usuario.usuario, amb_usuario.senha, fin_cliente.cliente as empresa, fin_cliente.nome as nomeempresa, fin_cliente.nomereduzido as nomefantasia ";
	$sql .= "FROM amb_usuario, fin_cliente ";
	$sql .= "WHERE ((fin_cliente.cliente = amb_usuario.cliente)  AND (amb_usuario.nome = '".$this->m_usernome."')) ";
//	echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	
	$teste_array = is_array($banco->resultado);
	if (isset($teste_array)){
      	$this->m_userid = $banco->resultado[0]['USUARIO'];
      	$this->m_usersenha = $banco->resultado[0]['SENHA'];
      	$this->m_empresaid = $banco->resultado[0]['EMPRESA'];
        $this->m_empresanome = $banco->resultado[0]['NOMEEMPRESA'];
        $this->m_empresafantasia = $banco->resultado[0]['NOMEFANTASIA'];
	}
		
	return $banco->intNumTuplas;
} //busca_dadosUserPessoa
//-------------------------------------------------------------
function verificaUsuario(){
		
	$sql  = "SELECT * FROM amb_usuario ";
	$sql .= "WHERE (nome = '".$this->m_usernome."') and ";
	$sql .= "(senha = '".$this->m_usersenha."') and";
	$sql .= "(situacao = 'A')";
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	if ($banco->intNumTuplas>0) {
		return $usuario = true;}
	else{
		return $usuario = false;}
		
}

function verificaUsuarioEmpresa(){
		
	$sql  = "SELECT * FROM amb_usuario ";
	$sql .= "WHERE (nome = '".$this->m_usernome."') and ";
	$sql .= "(senha = '".$this->m_usersenha."')";
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	if ($banco->intNumTuplas>0) {
		$this->m_empresaid = $banco->resultado[0]['EMPRESA'];
		return $usuario = true;}
	else{
		return $usuario = false;}
		
}

function verificaUsuarioId(){
		
	$sql  = "SELECT * FROM amb_usuario ";
	$sql .= "WHERE (usuario = '".$this->m_userid."') and ";
	$sql .= "(senha = '".$this->m_usersenha."')";
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	if ($banco->intNumTuplas>0) {
		$this->m_empresaid = $banco->resultado[0]['EMPRESA'];
		return $usuario = true;}
	else{
		return $usuario = false;}
		
}

//-------------------------------------------------------------
function verificaEmpresa(){
		
	$sql  = "SELECT f.NOME, c.bloqueado FROM fin_cliente f ";
	$sql  .= "inner join  amb_empresa e on (e.centrocusto=f.centrocusto) ";
	$sql  .= "inner join  fin_classe c on (c.classe=f.classe) ";
	$sql .= "WHERE (userlogin = '".$this->m_usernome."') and ";
	$sql .= "(password = '".$this->m_usersenha."')";
	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	if ($banco->intNumTuplas>0) {
                if (($banco->resultado[0]['BLOQUEADO'] == 'N') OR ($banco->resultado[0]['BLOQUEADO'] == 'P')):
                    $this->m_empresaid = $banco->resultado[0]['EMPRESA'];
                    return $usuario = 'T';
                else:
                    return $usuario = 'B';
                endif;
                
        }
	else{
		return $usuario = 'F';}
		
} //VerificaEmpresa

//-------------------------------------------------------------
function verificaDireitoUsuario($programa, $direito, $exibirMsg = NULL){
	if ($this->m_userid == ''){
            echo "<BR> <BR><table align=\"center\">";
            echo "<tr>";
            echo "<td align=\"center\"><h4>";
            echo "<font color=white><br> <b>Sistema de Informa&ccedil;&atilde;o Ger&ecirc;ncial</b> <BR><BR>";
            echo "<font color=red> <wrong>Aviso:</wrong> Sua sess&atilde;o esta expirada. </font> <BR> <BR>"; 
            echo "<font color=white>Por favor ATUALIZE a pagina do navegador ou fa&ccedil;a LOGOFF.<p><br>";
            echo "</h4></td>";
            echo "</tr>";
            echo "</table>";
            die();
        }	
        // busca direito usuario
	$pos = false;
	$acesso = false;
	$sql  = "SELECT * FROM amb_usuario_autoriza ";
	$sql .= "WHERE ((usuario = ".$this->m_userid.") and (programa = '".$programa."'))";
        //echo strtoupper($sql)."<BR>";
	$banco = new c_banco();
	$banco->exec_sql($sql);

	if ($banco->intNumTuplas <= 0){ //nao achou registro usuario
		if ($this->m_usergrupo > 0){ //grupo cadastrado no usuario
			// busca direito grupo
			$sql  = "SELECT * FROM amb_usuario_autoriza ";
			$sql .= "WHERE ((usuario = ".$this->m_usergrupo.") and (programa = '".$programa."'))";
			//echo strtoupper($sql);
			$banco->exec_sql($sql);
			$pos = strpos($banco->resultado[0]['DIREITOS'], $direito);
			if ($pos !== false){
				$acesso = true;}        
		}
	}
	else{
		$pos = strpos($banco->resultado[0]['DIREITOS'], $direito);
		if ($pos !== false){
			$acesso = true;}        
	}
            
	if ((($acesso === false) and ($exibirMsg == '')) or ($exibirMsg == 'S')){
//		if ($direito != 'S'){
			echo "<BR> <BR><table align=\"center\">";
			echo "<tr>";
			echo "<td align=\"center\"><h3>";
			echo "<font color=white><br> <b>Sistema de Informa&ccedil;&atilde;o Ger&ecirc;ncial</b> <BR><BR>";
			echo "<font color=red> USU&Aacute;RIO N&Atilde;O AUTORIZADO ! </font> <BR> <BR>"; 
			echo "<font color=white>Usu&aacute;rio: ".$this->m_usernome." <BR>";		 
			echo "Form:: ".$programa." <BR><BR>"; 			 
			echo "<font color=white>Selecione uma nova op&ccedil;&atilde;o.   <BR><BR>";
			echo "<font color=red> ATEN&Ccedil;&Atilde;O: </font><font color=white> clique no FINALIZAR OU LOGOFF para sair do sistema.<p><br>";
			echo "</h3></td>";
			echo "</tr>";
			echo "</table>";
//		}
		
	}

    $banco->close_connection();
    return $acesso;
} // fim verificaDireitoUsuario

//-------------------------------------------------------------
function verificaDireitoPrograma($programa, $direito){
		
	$pos = false;	
	$acesso = false;
	$sql  = "SELECT * FROM amb_usuario_autoriza ";
	$sql .= "WHERE ((usuario = ".$this->m_userid.") and (programa = '".$programa."'))";
//    echo ("String exec_sql: ".$sql."<br>");
	$banco = new c_banco();
	$banco->exec_sql($sql);

	if ($banco->intNumTuplas <= 0){ //nao achou registro usuario
		if ($this->m_usergrupo > 0){ //grupo cadastrado no usuario
			// busca direito grupo
			$sql  = "SELECT * FROM amb_usuario_autoriza ";
			$sql .= "WHERE ((usuario = ".$this->m_usergrupo.") and (programa = '".$programa."'))";
			//echo strtoupper($sql);
			$banco->exec_sql($sql);
			$pos = strpos($banco->resultado[0]['DIREITOS'], $direito);
			if ($pos !== false){
				$acesso = $direito;}        
		}
	}
	else{
		$pos = strpos($banco->resultado[0]['DIREITOS'], $direito);
		if ($pos !== false){
			$acesso = $direito;}
	}
    $banco->close_connection();
    return $acesso;
}


//-------------------------------------------------------------
function turno() { 
	$today = getdate();
        $hora = $today['hours'];    
	if ($hora < 12){
		$msg = "Bom dia"; 
	}
	else if (($hora >= 12) AND ($hora < 18)){
	    	$msg = "Boa Tarde";
	}
	else $msg = "Boa Noite";
	
	return $msg;
}

//-------------------------------------------------------------
function data() {
	$today = getdate();
        $month = $today['mon']; 
	$mday = $today['mday']; 
	$year = $today['year'];
	$data = "$mday/$month/$year";
	return $data;	
}

//------------------------------------------------------------
function saudacao() {

  echo "<table width=\"80%\" font=\"1\" border=\"1\" bgcolor=\"#ffffff\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\">";
	echo "<tr>";
	echo "<td>	<br><br><br>";

	echo "<BR> <BR><table align=\"center\">";
	echo "<tr>";
	echo "<td align=\"center\"><h3>";
	echo $this->turno()."  ".$this->m_usernome." !<BR> <BR>";
	echo "Data: ".$this->data()." <BR><BR>";
	echo "<br> <b>Sistema de Informa&ccedil;&atilde;o Ger&ecirc;ncial</b> <BR><BR <BR><BR>";
	echo "Selecione uma op&ccedil;&atilde;o acima.   <BR><BR>";
	echo "<font color=red> ATEN&Ccedil;&Atilde;O: </font> clique no bot&atilde;o SAIR para sair do sistema.<p><br>";
	echo "</h3></td>";
	echo "</tr>";
	echo "</table>";
}

}	//	END OF THE CLASS

?>
