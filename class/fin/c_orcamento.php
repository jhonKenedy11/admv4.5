<?php

$dir = dirname(__FILE__);

include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class C_ORCAMENTO
class c_orcamento extends c_user
{

	// Campos tabela | Objetos da classe
	private $mes = NULL; //int
	private $ano = NULL; //int
	private $centroCusto = NULL; // integer
	private $genero = NULL;  //varchar(4)
	private $valor = NULL;   // numeric(11,2)




	//construtor
	function c_orcamento() {}

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function setMes($mes)
	{
		$this->mes = $mes;
	}
	public function getMes()
	{
		return $this->mes;
	}
	public function setAno($ano)
	{
		$this->ano = $ano;
	}
	public function getAno()
	{
		return $this->ano;
	}

	public function setCentroCusto($centroCusto)
	{
		$this->centroCusto = $centroCusto;
	}
	public function getCentroCusto()
	{
		return $this->centroCusto;
	}

	public function setGenero($genero)
	{
		$this->genero = $genero;
	}
	public function getGenero()
	{
		return trim($this->genero);
	}

	public function setValor($valor)
	{
		$this->valor = $valor;
	}
	// public function getValor($format = NULL) {
	// 	if ($format=='F') {
	// 		return number_format($this->valor, 2, ',', '.'); }
	// 	else {
	// 		if ($this->valor != null){

	// 			$num = str_replace(',', '.', $this->valor);
	// 			return $num; }
	// 		else{
	// 			return 0; }
	// 	}	
	// }

	public function getValor($format = null)
	{
		if (isset($this->valor)) {
			switch ($format) {
				case 'B':
					return c_tools::moedaBd($this->valor);
					break;
				case 'F':
					return number_format((double) $this->valor, 2, ',', '.');
					break;
				default:
					return $this->valor;
			}
		} else {
			return 0;
		}
	}
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function existeOrcamentoGenero()
	{

		$sql  = "SELECT * ";
		$sql .= "FROM fin_orcamento ";
		$sql .= "WHERE (mes = " . $this->getMes() . ") AND (ano = " . $this->getAno() . ") AND (genero = '" . $this->getGenero() . "') AND (centrocusto = " . $this->getCentroCusto() . ")";
		//	ECHO $sql;

		$banco = new c_banco();
		$banco->exec_sql($sql);
		$banco->close_connection();
		return is_array($banco->resultado);
	} //fim existeOrcamentoGenero

	//---------------------------------------------------------------
	//---------------------------------------------------------------

	public function select_data_genero($letra)
	{
		$par = explode("|", $letra);

		$sql = "SELECT e.mes, e.ano, e.nomefantasia as filial, o.centrocusto, g.descricao, o.genero, o.valor ";
		$sql .= "FROM fin_orcamento o INNER JOIN amb_empresa e on (o.centrocusto = e.centrocusto) ";
		$sql .= "INNER JOIN fin_genero g on (o.genero = g.genero) ";
		$sql .= "WHERE (mes = " . $par[0] . ") AND (ano = " . $par[1] . ")";

		//ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_data_genero
	//---------------------------------------------------------------
	//---------------------------------------------------------------

	public function gera_previsao_media($letra, $meses)
	{
		$par = explode("|", $letra);
		$dataIni = date("Y-m-d", strtotime("-" . $par[3] . " month", strtotime(date($par[1] . "-" . $par[0] . "-01"))));
		$dataFim = date("Y-m-d", strtotime("-1 month", strtotime(date($par[1] . "-" . $par[0] . "-01"))));
		list($ano, $mes, $dia) = explode("-", $dataFim);
		$ultimo_dia = date("t", $mes);
		$dataFim = $ano . "-" . $mes . "-" . $ultimo_dia;

		// exclui lancamentos anteriores
		$this->excluiOrcamentoMes($letra);

		$sql = "INSERT INTO FIN_ORCAMENTO (MES, ANO, GENERO, CENTROCUSTO, VALOR) ";
		$sql .= "SELECT " . $par[0] . " AS MES, " . $par[1] . " AS ANO, GENERO, ";
		if ($par[2] != '')
			$sql .= "CENTROCUSTO, ";
		else
			$sql .= "0 AS CC, ";
		$sql .= "SUM(TOTAL)/" . $par[3];
		$sql .= " FROM FIN_LANCAMENTO WHERE (PAGAMENTO >= '" . $dataIni . "') AND (PAGAMENTO <= '" . $dataFim . "') ";
		if ($par[2] != '') {
			$sql .= "AND (CENTROCUSTO =  '" . $par[2] . "') ";
		}
		$sql .= "GROUP BY GENERO ";

		//ECHO $sql;
		$banco = new c_banco;
		$res_orc =  $banco->exec_sql($sql);
		$banco->close_connection();

		if ($res_orc > 0) {
			return "Orcamento Gerado com sucesso!";
		} else {
			return 'Os dados ' . $this->getGenero() . ' não foram cadastrados!';
		}
	} //fim select_data_genero
	//---------------------------------------------------------------
	//---------------------------------------------------------------

	public function select_orcamento_genero()
	{

		$sql  = "SELECT  * ";
		$sql .= "FROM fin_orcamento ";
		$sql .= "WHERE (mes = " . $this->getMes() . ") AND (ano = " . $this->getAno() . ") and (genero = '" . $this->getGenero() . "') AND (CENTROCUSTO = '" . $this->getCentroCusto() . "')";

		//	ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_data_genero

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_financeiro_Total($letra, $tipo = NULL)
	{

		$par = explode("|", $letra);
		$dataIni = date("Y-m-d", strtotime(date($par[1] . "-" . $par[0] . "-01")));
		$dataFim = date("Y-m-d", strtotime(date($par[1] . "-" . $par[0] . "-t")));

		$sql = "SELECT SUM(p.total) AS TOTAL ";
		$sql .= "FROM fin_lancamento p  ";
		$sql .= "LEFT JOIN amb_empresa e on (p.centrocusto = e.centrocusto) ";
		$sql .= "INNER JOIN fin_genero g on (p.genero = g.genero) ";
		$iswhere = true;
		if ($tipo = 'CPG')
			$sql .= "where ((p.genero LIKE '2%') OR (p.genero LIKE '4%')) ";
		else
			$sql .= "where (p.genero LIKE '1%') ";
		if ($par[0] != '') {
			$sql .= "and (p.pagamento >= '" . $dataIni . " 00:00:00') and (p.pagamento <= '" . $dataFim . "') ";
			$iswhere = true;
		}
		if ($par[2] != '') {
			if ($iswhere) {
				$sql .= "AND (p.centrocusto =  '" . $par[2] . "') ";
			} else {
				$iswhere = true;
				$sql .= "WHERE (p.centrocusto =  '" . $par[2] . "') ";
			}
		}
		$sql .= "ORDER BY p.genero ";

		//ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_financeiro_total

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_orcamento_Total($letra, $tipo = NULL)
	{

		$par = explode("|", $letra);
		$sql = "SELECT SUM(o.valor) AS TOTAL ";
		$sql .= "FROM fin_orcamento o  ";
		$sql .= "LEFT JOIN amb_empresa e on (o.centrocusto = e.centrocusto) ";
		$sql .= "INNER JOIN fin_genero g on (o.genero = g.genero) ";
		$iswhere = true;
		if ($tipo = 'CPG')
			$sql .= "where ((o.genero LIKE '2%') OR (o.genero LIKE '4%')) ";
		else
			$sql .= "where (o.genero LIKE '1%') ";
		if ($par[0] != '') {
			$sql .= "and (o.mes = " . $par[0] . ")";
			$sql .= "and (o.ano =  '" . $par[1] . "')";
			$iswhere = true;
		}
		if ($par[2] != '') {
			if ($iswhere) {
				$sql .= "AND (o.centrocusto =  '" . $par[2] . "') ";
			} else {
				$iswhere = true;
				$sql .= "WHERE (o.centrocusto =  '" . $par[2] . "') ";
			}
		} else {
			if ($iswhere) {
				$sql .= "AND (o.centrocusto =  'NULL') ";
			} else {
				$iswhere = true;
				$sql .= "WHERE (o.centrocusto =  'NULL') ";
			}
		}
		$sql .= "ORDER BY o.genero ";

		//ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_orcamento_total


	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_orcamento_letra($letra)
	{

		$par = explode("|", $letra);

		//inicio e fim do mês 
		$dataIni = date("Y-m-d", strtotime(date($par[1] . "-" . $par[0] . "-01")));
		$dataFim = date("Y-m-d", strtotime(date($par[1] . "-" . $par[0] . "-t")));

		$sql = "SELECT mes, ano, e.nomefantasia as filial, o.centrocusto, ";
		$sql .= "g.descricao, o.genero, o.valor, ";
		$sql .= "(SELECT SUM(P.TOTAL) FROM FIN_LANCAMENTO P ";
		$sql .= "WHERE (P.GENERO = O.GENERO) AND ((P.PAGAMENTO >= '" . $dataIni . " 00:00:00') AND (P.PAGAMENTO <= '" . $dataFim . "'))) AS TOTAL ";
		$sql .= "FROM fin_orcamento o  ";
		$sql .= "LEFT JOIN amb_empresa e on (o.centrocusto = e.centrocusto) ";
		$sql .= "LEFT JOIN fin_genero g on (o.genero = g.genero) ";

		$iswhere = false;
		if (($par[0] != '') and ($par[0] != 0)) {
			$sql .= "where (o.mes = " . $par[0] . ")";
			$iswhere = true;
		}
		if (($par[1] != '') and ($par[1] != 0)) {
			if ($iswhere)
				$sql .= "and (o.ano =  '" . $par[1] . "')";
			else
				$sql .= "where (o.ano =  '" . $par[1] . "')";
			$iswhere = true;
		}
		if ($par[2] != '') {
			if ($iswhere) {
				$sql .= "AND (o.centrocusto =  '" . $par[2] . "') ";
			} else {
				$iswhere = true;
				$sql .= "WHERE (o.centrocusto =  '" . $par[2] . "') ";
			}
		}
		$sql .= "ORDER BY o.genero ";

		//ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_saldo_geral
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function incluiOrcamento()
	{

		$sql  = "INSERT INTO fin_orcamento (MES, ANO, CENTROCUSTO, GENERO, VALOR) ";
		$sql .= "VALUES (" . $this->getMes() . ", " . $this->getAno() . "," . $this->getCentroCusto() . ", '" . $this->getGenero() . "', " . $this->getValor('B') . "); ";

		//echo $sql;
		$banco = new c_banco;
		$res_orc =  $banco->exec_sql($sql);
		$banco->close_connection();

		if ($res_orc > 0) {
			return '';
		} else {
			return 'Os dados ' . $this->getGenero() . ' não foram cadastrados!';
		}
	} // fim incluiOrcamento

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function alteraOrcamento()
	{

		$sql  = "UPDATE fin_orcamento ";
		$sql .= "SET  mes = " . $this->getMes() . ", ";
		$sql .= "ano = " . $this->getAno() . ", ";
		if ($this->getCentroCusto() != '')
			$sql .= "centrocusto = " . $this->getCentroCusto() . ", ";
		$sql .= "genero = '" . $this->getGenero() . "', ";
		$sql .= "valor = " . $this->getValor('B') . " ";
		$sql .= "WHERE (mes = " . $this->getMes() . ") AND (ano = " . $this->getAno() . ") ";
		$sql .= "AND (genero = '" . $this->getGenero() . "') ";
		if ($this->getCentroCusto() != '') {
			$sql .= "AND (centrocusto =  '" . $this->getCentroCusto() . "') ";
		}
		//echo $sql;
		$banco = new c_banco;
		$res_orc =  $banco->exec_sql($sql);
		$banco->close_connection();
		if ($res_orc > 0) {
			return '';
		} else {
			return 'Os dados ' . $this->getGenero() . ' não foram alterados!';
		}
	}  // fim alteraOrcamento

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function excluiOrcamentoMes($letra)
	{

		$par = explode("|", $letra);

		$sql  = "DELETE FROM FIN_ORCAMENTO ";
		$sql .= "WHERE (mes = " . $par[0] . ") AND (ano = " . $par[1] . ")  ";
		if ($par[2] != '') {
			$sql .= "AND (centrocusto =  '" . $par[2] . "') ";
		}
		$banco = new c_banco;
		$res_orc =  $banco->exec_sql($sql);
		$banco->close_connection();

		if ($res_orc > 0) {
			return '';
		} else {
			return 'Os dados ' . $this->getGenero() . ' n�o foram excluidos!';
		}
	}  // fim excluiOrcamentoMes

	public function excluiOrcamento($letra)
	{

		$par = explode("|", $letra);

		$sql  = "DELETE FROM FIN_ORCAMENTO ";
		$sql .= "WHERE (mes = " . $par[0] . ") AND (ano = " . $par[1] . ")  ";
		$sql .= "AND (centrocusto =  '" . $par[2] . "') ";
		$sql .= "AND (genero =  '" . $par[3] . "') ";

		$banco = new c_banco;
		$res_orc =  $banco->exec_sql($sql);
		$banco->close_connection();

		if ($res_orc > 0) {
			return '';
		} else {
			return 'Os dados ' . $this->getGenero() . ' n�o foram excluidos!';
		}
	}  // fim excluiOrcamentoMes

}	//	END OF THE CLASS
