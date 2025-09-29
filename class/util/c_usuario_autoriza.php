<?php

/****************************************************************************
 *Cliente...........:
 *Contratada........: ADM
 *Desenvolvedor.....: Lucas Tortola da Silva Bucko
 *Sistema...........: Sistema de Informacao Gerencial
 *Classe............: C_USUARIO_AUTORIZA - cadastro de direitos - BUSINESS CLASS
 *Ultima Atualizacao: 14/09/2012
 ****************************************************************************/

include_once("../../bib/c_user.php");
include_once("../../class/pss/c_pessoa.php");
include_once("../../class/pss/c_usuario.php");

//Class C_USUARIO_AUTORIZA
class c_usuario_autoriza extends c_user
{

	// Campos tabela
	private $usuario = NULL;
	private $nome = NULL;
	private $programa = NULL;
	private $direitos = NULL;

	//---------------------------------------------------------------
	//---------------------------------------------------------------

	public function setUsuario($usuario)
	{
		$this->usuario = $usuario;
	}

	public function getUsuario()
	{
		return $this->usuario;
	}

	public function setNome()
	{
		$cliente = new c_usuario();
		$cliente->setUsuario($this->getUsuario());
		$reg_nome = $cliente->select_usuario_matricula();
		$this->nome = $reg_nome[0]['NOMEREDUZIDO'];
	}

	public function getNome()
	{
		return $this->nome;
	}
	public function setPrograma($programa)
	{
		$this->programa = $programa;
	}

	public function getPrograma()
	{
		return $this->programa;
	}

	public function setDireitos($direitos)
	{
		$this->direitos = $direitos;
	}
	public function getDireitos()
	{
		return $this->direitos;
	}

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function existeAutorizacao()
	{

		$sql  = "SELECT * ";
		$sql .= "FROM AMB_USUARIO_AUTORIZA ";
		$sql .= "WHERE (usuario = " . $this->getUsuario() . " and programa = '" . $this->getPrograma() . "')";
		//ECHO $sql;

		$banco = new c_banco();
		$banco->exec_sql($sql);
		$banco->close_connection();
		return is_array($banco->resultado);
	} //fim existeAutorizacao

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_autorizacao()
	{

		$sql  = "SELECT  * ";
		$sql .= "FROM AMB_USUARIO_AUTORIZA ";
		$sql .= "WHERE (usuario = " . $this->getUsuario() . " and programa = '" . $this->getPrograma() . "') ";


		//echo $sql;
		$banco = new c_banco();
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_autorizacao

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_autorizacao_geral()
	{
		$sql = "SELECT u.*, AU.NOME, f.descricao ";
     	$sql .= "FROM amb_usuario_autoriza u ";
		$sql .= "LEFT JOIN amb_usuario AU ON u.usuario = AU.usuario ";
        $sql .= "INNER JOIN amb_form f ON u.programa = f.nomeform ";
		//$sql .= "WHERE u.usuario = ".$this->getUsuario();
        $sql .= "ORDER BY u.PROGRAMA;";

		$banco = new c_banco();
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_autorizacao_geral




	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function incluiAutorizacao()
	{


		// $direitos = implode('', $this->getDireitos());

		$sql  = "INSERT INTO AMB_USUARIO_AUTORIZA (USUARIO, PROGRAMA, DIREITOS) ";
		$sql .= "VALUES (" . $this->getUsuario() . ", '" . $this->getPrograma() . "', '" . $this->getDireitos() . "'); ";

		//echo strtoupper($sql)."<br>";
		$banco = new c_banco;
		$res_acessorio =  $banco->exec_sql($sql);
		$banco->close_connection();

		if ($res_acessorio > 0) {
			return '';
		} else {
			return 'Os dados da Autoriza&ccedil;&atilde;o ' . $this->getPrograma() . ' n&atilde;o foram cadastrados!';
		}
	} // fim incluiAutorizacao

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function alteraAutorizacao()
	{

		// $direitos = implode('', $this->getDireitos());
		$direitos = $this->getDireitos();

		$sql  = "UPDATE AMB_USUARIO_AUTORIZA ";
		$sql .= "SET  direitos = '" . $direitos . "' ";
		$sql .= "WHERE (usuario = " . $this->getUsuario() . ") AND (programa = '" . $this->getPrograma() . "') ";

		//echo $sql;
		$banco = new c_banco;
		$res_acessorio =  $banco->exec_sql($sql);
		$banco->close_connection();

		if ($res_acessorio > 0) {
			return '';
		} else {
			return 'Os dados da Autoriza&ccedil;&atilde;o ' . $this->getPrograma() . ' n&atilde;o foram alterados!';
		}
	}  // fim alteraAutorizacao

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function excluiAutorizacao()
	{

		$sql  = "DELETE FROM AMB_USUARIO_AUTORIZA ";
		$sql .= "WHERE (usuario = '" . $this->getUsuario() . "') AND (programa = '" . $this->getPrograma() . "') ;";
		//echo strtoupper($sql);
		$banco = new c_banco;
		$res_acessorio =  $banco->exec_sql($sql);
		$banco->close_connection();

		if ($res_acessorio > 0) {
			return '';
		} else {
			return 'Os dados da Autoriza&ccedil;&atilde;o ' . $this->getUsuario() . ' n&atilde;o foram excluidos!';
		}
	}  // fim excluiAutorizacao

}	//	END OF THE CLASS
