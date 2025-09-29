<?php
/**
 * @package   astecv3
 * @name      c_extrato
 * @category  BUSINESS CLASS - Lancamento de receitas ou despesas financeiro
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admsistema.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      22/05/2016
 */
$dir = dirname(__FILE__);

include_once($dir."/../../bib/c_user.php");
include_once($dir."/../../bib/c_date.php");
//include_once("../../bib/c_mail.php");
include_once($dir."/../../bib/c_tools.php");
include_once($dir."/../../bib/c_date.php");
include_once($dir."/../../class/crm/c_conta.php");
include_once($dir."/../../class/fin/c_lancamento.php");

//include_once("class.phpmailer.php");

//Class C_extrato
Class c_extrato extends c_user {

// Campos tabela
private $id = NULL;
private $pessoa = NULL;	// integer not null,
private $nomePessoa = NULL;	// nome pessoa, nao faz parte do cadastro
private $pessoaFornecedor = NULL;	// integer not null,
private $nomePessoaFornecedor = NULL;	// nome pessoa, nao faz parte do cadastro
private $tipoLancamento = NULL;	 //char(1) not null,
private $situacaoLancamento = NULL;	 //char(1) not null,
private $genero = NULL; //	 varchar(4) not null,
private $generoDesc = NULL;	// genero descricao, nao faz parte do cadastro
private $centrocusto = NULL; //	 integer not null,
private $lancamento = NULL; //	 date,
private $competencia = NULL; //	 date not null,
private $valor = NULL; //	 numeric(11,2),
private $obs = NULL; //	 blob sub_type text segment size 80,


//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);

}

/*---------------------------------------------------------------
* METODOS DE SETS E GETS
---------------------------------------------------------------*/

public function setId($lancamento){ $this->id = $lancamento; }
public function getId(){ return $this->id; }

public function setPessoa($pessoa){ $this->pessoa = $pessoa; }
public function getPessoa(){ 
    if (is_numeric($this->pessoa))
        return $this->pessoa;
    else
        return 0;
}

public function setPessoaNome(){
		$cliente = new c_conta();
		$cliente->setId($this->getPessoa());
		$reg_nome = $cliente->select_conta();
		$this->nomePessoa = $reg_nome[0]['NOME'];
		$this->emailPessoa = $reg_nome[0]['EMAIL'];
		
}
public function getPessoaNome(){ return $this->nomePessoa; }

public function setPessoaFornecedor($pessoaFornecedor){ $this->pessoaFornecedor = $pessoaFornecedor; }
public function getPessoaFornecedor(){ 
    if (is_numeric($this->pessoaFornecedor))
        return $this->pessoaFornecedor;
    else
        return 0;
}

public function setPessoaFornecedorNome(){
		$pessoa = new c_conta();
		$pessoa->setId($this->getPessoaFornecedor());
		$reg_nome = $pessoa->select_conta();
		$this->nomePessoaFornecedor = $reg_nome[0]['NOME'];
		
}
public function getPessoaFornecedorNome(){ return $this->nomePessoaFornecedor; }

public function setTipoLancamento($tipoLancamento){ $this->tipoLancamento= strtoupper($tipoLancamento); }
public function getTipoLancamento(){ return $this->tipoLancamento; }

public function setSituacaoLancamento($situacaoLancamento){ $this->situacaoLancamento= strtoupper($situacaoLancamento); }
public function getSituacaoLancamento(){return $this->situacaoLancamento;}


public function setGenero($genero) { $this->genero = strtoupper($genero); }
public function getGenero() { return $this->genero; }

public function setDescGenero(){
  		$consulta = new c_banco();
  		$sql = "select genero as id, descricao from fin_genero where genero='".$this->getGenero()."'";
  		$consulta->exec_sql($sql);
		$consulta->close_connection();
  		$result = $consulta->resultado;
                $this->generoDesc = $result[0]['DESCRICAO'];
}
public function getDescGenero(){
		return $this->generoDesc;
	}

public function setCentroCusto($cc) { $this->centrocusto = $cc; }
public function getCentroCusto() { return $this->centrocusto; }
	
public function setLancamento($lancamento) { $this->lancamento=$lancamento; }
public function getLancamento($format = null) { 
		$this->lancamento = strtr($this->lancamento, "/","-");
		switch ($format) {
			case 'F':
				return date('d/m/Y', strtotime($this->lancamento)); 
				break;
			case 'B':
                                return c_date::convertDateBd($this->lancamento, $this->m_banco);
				break;
			default:
				return $this->lancamento;
		}
}

public function setCompetencia($competencia) { $this->competencia=$competencia; }
public function getCompetencia($format = null) { 
		$this->competencia = strtr($this->competencia, "/","-");
		switch ($format) {
			case 'F':
				return date('d/m/Y', strtotime($this->competencia)); 
				break;
			case 'B':
                                return c_date::convertDateBd($this->competencia, $this->m_banco);
				break;
			default:
				return $this->competencia;
		}
}


public function setValor($valor, $format = null) { 
    // $valor = strtr($valor, "_","0");
    $this->valor = $valor; 
    if ($format):
            $this->valor = number_format($this->valor, 2, ',', '.');
    endif;
    
}
public function getValor($format = null) {
	if ($format=='F') {
			//return number_format($this->valor, 2, ',', '.'); }
                        return number_format((float)$this->valor, 2, ',', '.'); }
		else if ($format=='B'){      
                    $this->valor = c_tools::moedaBd($this->valor);
                    return $this->valor;
                        
                }else {
                    return $this->valor;
                }	
}                

public function setObs($obs){ $this->obs = strtoupper($obs); }
public function getObs(){ return $this->obs; }



//############### FIM SETS E GETS ###############





 /**
 * @name select_extrato
 * @description busca na tabela lancamentos um documento
 * @param string $this->getId() - num do documento a ser pesquisado
 * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
 */
public function select_extrato(){

	
	$sql  = "SELECT * ";
	$sql .= "FROM FIN_EXTRATO ";
	$sql .= "WHERE (id = ".$this->getId().") ";
//	ECHO $sql;

	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

} //fim select_extrato



 /**
 * @name select_extrato_geral
 * @description busca todos os lancamento independente de parametros digitados no form
 * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
 */
public function select_extrato_geral(){
	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM FIN_EXTRATO  ";
   	$sql .= "ORDER BY lancamento ";
//	ECHO $sql;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;

} //fim select_extrato_geral

 /**
 * @name select_extrato_letra
 * @description busca lancamento de acrodo com informacoes digitados no form
 * @param string $letra - parametros digitados no form para consulta sql
 *        int valor = 0 resumo por genero e descricao
 *            valor = 1 classifica pela data escolhida  
 *            valor = 2 classifca por genero e data de competencia
 * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
 */
public function select_extrato_resumo($letra){

        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[1]);
        $dataFim = c_date::convertDateTxt($par[2]);
        $iswhere = "WHERE ";

		$sqln  = "SELECT E.PESSOA, P.NOME, P.NOMEREDUZIDO, ";
		$sqln  .= "sum(if(TIPOLANCAMENTO='R', VALOR, 0)) AS RECEBIMENTO, ";
		$sqln  .= "sum(if(TIPOLANCAMENTO='P', VALOR, 0)) AS PAGAMENTO ";
		$sqln  .= "FROM FIN_EXTRATO E  ";
		$sqln  .= "INNER JOIN FIN_CLIENTE P ON P.CLIENTE=E.PESSOA ";
		$sqlGroup  = " group by E.PESSOA  ";

        if (array_sum($par) > 0){
                // data
                $where = "WHERE (e.situacaoLancamento = 'A') and ";
                $where .= "(e.competencia >= '".$dataIni."') and (e.competencia <= '".$dataFim."') ";
                $iswhere = " AND ";

                // pessoa
                if ($par[3] != '0'){
                        $where .= $iswhere."(E.pessoa = ".$par[3].") ";
                        $iswhere = " AND ";
                }
        }

        $sql = $sqln.$where.$sqlGroup;
        $banco = new c_banco;
        $banco->exec_sql($sql);
		$banco->close_connection();
		
        $result = is_array($banco->resultado) ? $banco->resultado : [];
        for ($i=0; $i < count($result); $i++){
			$result[$i]['TOTAL'] = $result[$i]['PAGAMENTO'] - $result[$i]['RECEBIMENTO'];
        }

        return $result;

}// fim select_extrato_letra

 /**
 * @name select_extrato_letra
 * @description busca lancamento de acrodo com informacoes digitados no form
 * @param string $letra - parametros digitados no form para consulta sql
 *        int valor = 0 resumo por genero e descricao
 *            valor = 1 classifica pela data escolhida  
 *            valor = 2 classifca por genero e data de competencia
 * @return array $banco->resultado - resultado da pesquisa sql na tabela lancamento doc
 */
public function select_extrato_letra($letra){

        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[1]);
        $dataFim = c_date::convertDateTxt($par[2]);
        $iswhere = "WHERE ";

        $sqln  = "SELECT a.*, c.nomereduzido, c.nome, c.cidade, s.padrao as situacaolancamento, t.padrao as tipoLancamento, g.descricao as descgenero ";
        $sqln .= "FROM FIN_EXTRATO a ";
        $sqln .= "inner join fin_cliente c on c.cliente = a.pessoa ";
        $sqln .= "inner join fin_genero g on g.genero = a.genero ";
        $sqln .= "inner join amb_ddm s on ((s.alias='FIN_MENU') and (s.campo='SituacaoExtrato') and (s.tipo = a.situacaoLancamento)) ";
        $sqln .= "inner join amb_ddm t on ((t.alias='FIN_MENU') and (t.campo='TipoLanc') and (t.tipo = a.tipoLancamento)) ";
        $sqln .= " ";
        if (array_sum($par) > 0){
                // data
                $where .= "WHERE ";
                if ($par[0] != 'nao'){
                        $where .= "(a.".$par[0]." >= '".$dataIni."') and (a.".$par[0]." <= '".$dataFim."') ";
                        $iswhere = " AND ";
                }

                // pessoa
                if ($par[3] != '0'){
                        $where .= $iswhere."(a.pessoa = ".$par[3].") ";
                        $iswhere = " AND ";
                }

                // genero
                if ($par[4] != ''){
                        $where .= $iswhere."(a.genero = ".$par[4].") ";
                        $iswhere = " AND ";
                }

                // sit lancamento
                if ($par[5] != '0'){
                        $posSitLanc = 5;
                        $i = $posSitLanc + 1;
                        $where .= $iswhere."(a.situacaoLancamento in ('".$par[$i]."'";
                        $i++;
                        while ($i <= ($par[5]+5)) { 
                                $where .= ",'".$par[$i]."' ";
                                $i++;}				
                        $where .= ")) ";
                        $iswhere = " AND ";
                }

                // tipo lancamento
                $posTipoLanc = $posSitLanc + $par[$posSitLanc] + 1;
                if ($par[$posTipoLanc] != '0'){
                        $i = $posTipoLanc + 1;	
                        $where .= $iswhere."(a.tipoLancamento in ('".$par[$i]."'";
                        $i++;
                        while ($i <= ($par[$posTipoLanc]+$posTipoLanc)) {
                                $where .= ",'".$par[$i]."' ";
                                $i++; }				
                        $where .= ")) ";
                }
        }

        $sql = $sqln.$where;
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;

}// fim select_extrato_letra

 /**
 * @name addParcelasNf
 * @description adiciona parcelas referente a Nf
 * @param int $this->getQuantParc - quantidade de parcelas adicionais
 * @return int $count - numero de parcelas adicionadas
 */
public function addLancamentoFinanceiro($arrLancamentoFin = NULL, $letra, $centrocusto, $genero, $vencimento, $conta = 1){

	$par = explode("|", $letra);
	$dataIni = c_date::convertDateTxt($par[1]);
	$dataFim = c_date::convertDateTxt($par[2]);
//	$vencimento = c_date::convertDateTxt($vencimento);
	$data = explode("-", $dataIni);

	$transaction = new c_banco();
	$transaction->inicioTransacao($transaction->id_connection);
	


	try {
		$objFinanceiro = new c_lancamento();

		for ($i = 0; $i < count($arrLancamentoFin); $i++) {
			if ($arrLancamentoFin[$i]['TOTAL'] < 0){
				$tipoLancamento = 'P';
				$modoPgto = 'C';
				$tipoDoc = 'R';
				$valor = $arrLancamentoFin[$i]['TOTAL'] * -1;
			}else {
				$tipoLancamento = 'R';
				$modoPgto = 'B';
				$tipoDoc = 'B';
				$valor = $arrLancamentoFin[$i]['TOTAL'];
			}

			$objFinanceiro->setPessoa($arrLancamentoFin[$i]['PESSOA']);
			$objFinanceiro->setDocto($data[0].$arrLancamentoFin[$i]['PESSOA']); // ano
			$objFinanceiro->setSerie('EXT');
			$objFinanceiro->setTipolancamento($tipoLancamento); //??
			$objFinanceiro->setSitdocto('N'); // normal
			$objFinanceiro->setUsrsitpgto($this->m_userid); //usuario
			$objFinanceiro->setModopgto($modoPgto); // bancario
			$objFinanceiro->setOrigem('EXT'); // ??/
			$objFinanceiro->setNumlcto($data[0].$data[1]); // ??/
			$objFinanceiro->setGenero($genero); 
			$objFinanceiro->setCentroCusto($centrocusto);	// centro custo atual
			$objFinanceiro->setLancamento(date("d/m/Y"));
			$objFinanceiro->setEmissao(date("d/m/Y"));
			$objFinanceiro->setMulta(0);
			$objFinanceiro->setJuros(0);
			$objFinanceiro->setAdiantamento(0);
			$objFinanceiro->setDesconto(0);
			$objFinanceiro->setMoeda(0);
		
			$objFinanceiro->setParcela($data[1]); // mes

			$objFinanceiro->setTipodocto($tipoDoc); // boleto
			$objFinanceiro->setSitpgto('A'); // aberto
			$objFinanceiro->setConta($conta); //array

			$objFinanceiro->setVencimento($vencimento); //arry
			$objFinanceiro->setMovimento($vencimento);
			$objFinanceiro->setOriginal($valor, true);
			$objFinanceiro->setTotal($valor, true); //array
			$objFinanceiro->setObs('EXTRATO - FECHAMENTO PERÍODO: '.$dataIni.' - '.$dataFim, true); //array

			$idInsert = $objFinanceiro->incluiLancamento($transaction->id_connection);

			// baixa extrato por cliente e periodo
			if (is_numeric($idInsert)){
				$extrato = new c_banco;
				$sql = "update fin_extrato set situacaolancamento = 'B', idfin = ".$idInsert;
                $sql .= " where (competencia >= '".$dataIni."') and (competencia <= '".$dataFim."') ";
                $sql .= " and (pessoa = ".$arrLancamentoFin[$i]['PESSOA'].")";
				$res_pessoa =  $extrato->exec_sql($sql, $transaction->id_connection);
                $extrato->close_connection();

			}
		}
		$transaction->commit($transaction->id_connection);
		return "Lançamentos financeiros realizados com sucesso!!";
	}	
	catch (Exception $e) {
		if (isset($transaction->id_connection)):
			$transaction->rollback($transaction->id_connection);    
			return "ERRO ao realizar Lançamentos financeiros!!";
		endif;    
	}

} //fim addLancamentoFinanceiro




 /**
 * @name incluiExtrato
 * @description faz a inclusão do registro cadastrado
 * @return bool true se inclusao ocorreu com sucesso
 *         string mensagem informando que não foi realizado a inclusao
*/
public function incluiExtrato($conn=null){

		$banco = new c_banco;
        $sql  = "INSERT INTO FIN_EXTRATO (";
		$sql  .= "PESSOA,
            PESSOAFORNECEDOR,
            tipoLancamento,
  			SITUACAOLANCAMENTO,
  			GENERO,
  			CENTROCUSTO,
  			LANCAMENTO,
  			competencia,
  			valor,
  			OBS,
  			USERINSERT, DATEINSERT
		
		)";

        $sql .= "VALUES (";
		$sql .= $this->getPessoa().", "
		.$this->getPessoaFornecedor().", '"
		.$this->getTipoLancamento()."', '"
		.$this->getSituacaoLancamento()."', '"
		.$this->getGenero()."', "
		.$this->getCentroCusto().", '"
		.$this->getLancamento('B')."', '"
		.$this->getCompetencia('B')."', '"
		.$this->getValor('B')."', '"
		.$this->getObs()."', "
		.$this->m_userid.",'".date("Y-m-d H:i:s")."'); ";
		//    echo $this->getID.$sql;
		
		// echo strtoupper($sql)."<BR>";
		$res_pessoa =  $banco->exec_sql($sql, $conn);
        $this->idInsert = $banco->insertReg;
                
		$banco->close_connection();

		if($res_pessoa > 0){
			return true;
		}
		else{
			return 'Os dados do Lan&ccedil;amento '.$this->getNome().' no foram cadastrados!';
	}
} // fim incluiExtrato

 /**
 * @name alteraExtrato
 * @description altera registro existente
 * @param int $this->getId() Identificação do registro a ser alterado
 * @return string Null se alteração ocorreu com sucesso
 *         string mensagem informando que não foi realizado a alteração
*/
public function alteraExtrato(){
	

	$sql  = "UPDATE FIN_EXTRATO ";
	$sql .= "SET pessoa = ".$this->getPessoa().", ";
	$sql .= "pessoafornecedor = ".$this->getPessoaFornecedor().", ";
	$sql .= "SituacaoLancamento = '".$this->getSituacaoLancamento()."', ";
	$sql .= "tipoLancamento = '".$this->getTipoLancamento()."', ";
	$sql .= "genero = '".$this->getGenero()."', ";
	$sql .= "centrocusto = ".$this->getCentroCusto().", ";
	$sql .= "lancamento = '".$this->getLancamento('B')."', ";
	$sql .= "competencia = '".$this->getCompetencia('B')."', ";
	$sql .= "valor = ".$this->getvalor('B').", ";
	$sql .= "obs = '".$this->getObs()."', ";
	$sql .= "userchange = ".$this->m_userid.", ";
	$sql .= "datechange = '".date("Y-m-d H:i:s")."' ";
	$sql .= "WHERE id = ".$this->getId().";";

        //echo strtoupper($sql)."<BR>";
	$banco = new c_banco;
	$res_extrato =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res_extrato > 0){
		return '';
	}
	else{
        return 'Os dados do Lan&ccedil;amento '.$this->getDesc().' n&atilde;o foram alterados!';
	}

}  // fim alteraExtrato

 /**
 * @name excluiExtrato
 * @description Exclui registro existente
 * @param int $this->getId() Identificação do registro a ser excluido
 * @return string Null se alteração ocorreu com sucesso
 *         string mensagem informando que não foi realizado a alteração
*/
public function excluiExtrato(){

	$sql  = "DELETE FROM FIN_EXTRATO ";
	$sql .= "WHERE id = ".$this->getId();
        //echo $sql;
	$banco = new c_banco;
	$res_extrato =  $banco->exec_sql($sql);
	$banco->close_connection();

			  
	if($res_extrato > 0){

	        return '';
	}
	else{
        return 'Os dados do Lan&ccedil;amento '.$this->getId().' n&atilde;o foram excluidos!';
	}
}  // fim excluiExtrato

}	//	END OF THE CLASS
?>
