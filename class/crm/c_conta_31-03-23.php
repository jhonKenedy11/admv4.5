<?php

/**
 * @package   astec
 * @name      c_conta
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");

//Class C_CONTA
Class c_conta extends c_user {
    /*
     * TABLE NAME FIN_CLIENTE
     */

// Campos tabela
    private $id             = NULL; // INT(11)
    private $nome           = NULL; // VARCHAR(50)
    private $nomeReduzido   = NULL; // VARCHAR(15)
    private $pessoa         = NULL; // CHAR(1)
    private $cnpjCpf        = NULL; // VARCHAR(14)
    private $dataNascimento = NULL; // DATE
    private $ieRg           = NULL; // VARCHAR(15)
    private $im             = NULL; // VARCHAR(14)
    private $cep            = NULL; // INT(11)
    private $tipo           = NULL; // VARCHAR(15)
    private $titulo         = NULL; // VARCHAR(15)
    private $endereco       = NULL; // VARCHAR(40)
    private $numero         = NULL; // VARCHAR(7)
    private $complemento    = NULL; // VARCHAR(15)
    private $bairro         = NULL; // VARCHAR(20)
    private $cidade         = NULL; // VARCHAR(40)
    private $codMunicipio   = NULL; // VARCHAR(10)
    private $suframa        = NULL; // VARCHAR(10)
    private $estado         = NULL; // VARCHAR(2)
    private $fone           = NULL; // VARCHAR(15)
    private $celular        = NULL; // VARCHAR(15)
    private $foneArea       = NULL; // VARCHAR(2)
    private $foneNum        = NULL; // VARCHAR(8)
    private $faxArea        = NULL; // VARCHAR(2)
    private $faxNum         = NULL; // VARCHAR(8)
    private $contato        = NULL; // VARCHAR(15)
    private $email          = NULL; // TEXT
    private $homePage       = NULL; // TEXT
    private $classe         = NULL; // VARCHAR(2)
    private $atividade      = NULL; // VARCHAR(4)
    private $centrocusto    = NULL; // INT(11)
    private $representante  = NULL; // INT(11)
    private $obs            = NULL; // TEXT
    private $transversal1   = NULL; // TEXT
    private $transversal2   = NULL; // TEXT
    private $referencia     = NULL; // TEXT
    private $emailNfe       = NULL; // VARCHAR(45)
    private $userLogin      = NULL; // VARCHAR(30)
    private $senhaLogin     = NULL; // VARCHAR(30)
    private $limiteCredito  = NULL; // VARCHAR(30)
    private $regimeEspecialST    = NULL; //CHAR(1)
    private $regimeEspecialSTMsg = NULL; //TEXT
    private $regimeEspecialSTMT  = NULL; //CHAR(1)     
    private $contribuinteICMS    = NULL; //CHAR(1)    
    private $consumidorFinal     = NULL; //CHAR(1)
    private $regimeEspecialSTMTAliq = NULL;
    private $regimeEspecialSTAliq   = NULL;  
    

    function __construct(){
    // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);

    }
    
    /**
     * METODOS DE SETS E GETS
     */

    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getNomeReduzido() {
        return $this->nomeReduzido;
    }

    function getPessoa() {
        return $this->pessoa;
    }

    function getCnpjCpf() {
        return $this->cnpjCpf;
    }

    public function getDatanascimento($format = null) { 
		if ($this->dataNascimento != ''){ 
			$this->dataNascimento = strtr($this->dataNascimento, "/","-");
			switch ($format) {
				case 'F':
					return date('d/m/Y', strtotime($this->dataNascimento)); 
					break;
				case 'B':
					return c_date::convertDateBd($this->dataNascimento, $this->m_banco);
					break;
				default:
					return $this->dataNascimento;
			}
		}else{
			return null;
		}
			
	}

    function getIeRg() {
        return $this->ieRg;
    }

    function getIm() {
        return $this->im;
    }

    function getCep() {
        return str_replace('-', '', $this->cep);
    }

    function getTipo() {
        return $this->tipo;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function getNumero() {
        return $this->numero;
    }

    function getComplemento() {
        return $this->complemento;
    }

    function getBairro() {
        return $this->bairro;
    }

    function getCidade() {
        return $this->cidade;
    }

    public function getCodMunicipio(){
            return $this->codMunicipio;
    }

    public function getSuframa(){
            return $this->suframa;
    }
    
    function getEstado() {
        return $this->estado;
    }

    function getFone() {
        return $this->fone;
    }

    function getCelular() {
        return $this->celular;
    }

    function getFoneArea() {
        return $this->foneArea;
    }

    function getFoneNum() {
        return $this->foneNum;
    }

    function getFaxArea() {
        return $this->faxArea;
    }

    function getFaxNum() {
        return $this->faxNum;
    }

    function getContato() {
        return $this->contato;
    }

    function getEmail() {
        return $this->email;
    }

    function getHomePage() {
        return $this->homePage;
    }

    function getClasse() {
        return $this->classe;
    }

    function getAtividade() {
        return $this->atividade;
    }

    function getCentrocusto() {
        return $this->centrocusto;
    }

    function getRepresentante() {
        return $this->representante;
    }

    function getObs() {
        return $this->obs;
    }

    function getTransversal1() {
        return $this->transversal1;
    }

    function getTransversal2() {
        return $this->transversal2;
    }

    function getReferencia() {
        return $this->referencia;
    }

    function getEmailNfe() {
        return $this->emailNfe;
    }
    
    function getUserLogin() {
        return $this->userLogin;
    }

    function getSenhaLogin() {
        return $this->senhaLogin;
    }


    function getRegimeEspecialST() {  return $this->regimeEspecialST; } 
    function getRegimeEspecialSTMsg() {  return $this->regimeEspecialSTMsg; }
    function getRegimeEspecialSTMT() {  return $this->regimeEspecialSTMT; } 
    function getContribuinteICMS() {  return $this->contribuinteICMS; }         
    function getConsumidorFinal() {  return $this->consumidorFinal; }     
    function getRegimeEspecialSTMTAliq() {  return $this->regimeEspecialSTMTAliq; } 
    function getRegimeEspecialSTAliq() {  return $this->regimeEspecialSTAliq; } 
       
    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = strtr($nome ,"'"," ");

    }

    function setNomeReduzido($nomeReduzido) {
        $this->nomeReduzido = $nomeReduzido;
    }

    function setPessoa($pessoa) {
        $this->pessoa = $pessoa;
    }

    function setCnpjCpf($cnpjCpf) {
        $this->cnpjCpf = $cnpjCpf;
    }

    function setDataNascimento($dataNascimento) { 
        $this->dataNascimento = $dataNascimento; 
    }

    function setIeRg($ieRg) {
        $this->ieRg = $ieRg;
    }

    function setIm($im) {
        $this->im = $im;
    }

    function setCep($cep) {
        if ( strlen($cep) == 7 ) {
            $cep = '0'.$cep;
        }
        $this->cep = $cep;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function setCodMunicipio($codMun){
            $this->codMunicipio = $codMun;
    }

    public function setSuframa($suframa){
            $this->suframa = $suframa;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setFone($fone) {
        $this->fone = $fone;
    }

    function setCelular($celular) {
        $this->celular = $celular;
    }

    function setFoneArea($foneArea) {
        $this->foneArea = $foneArea;
    }

    function setFaxArea($faxArea) {
        $this->faxArea = $faxArea;
    }

    function setFaxNum($faxNum) {
        $this->faxNum = $faxNum;
    }

    function setContato($contato) {
        $this->contato = $contato;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setHomePage($homePage) {
        $this->homePage = $homePage;
    }

    function setClasse($classe) {
        $this->classe = $classe;
    }

    function setAtividade($atividade) {
        $this->atividade = $atividade;
    }

    function setCentrocusto($centrocusto) {
        $this->centrocusto = $centrocusto;
    }

    function setRepresentante($representante) {
        $this->representante = $representante;
    }

    function setObs($obs) {
        $this->obs = $obs;
    }
    function setTransversal1($transversal1) {
        $this->transversal1 = $transversal1;
    }
    function setTransversal2($transversal2) {
        $this->transversal2 = $transversal2;
    }
    function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    function setRegimeEspecialST($regimeEspecialST) { $this->regimeEspecialST = $regimeEspecialST; }     
    function setRegimeEspecialSTMsg($regimeEspecialSTMsg) { $this->regimeEspecialSTMsg = $regimeEspecialSTMsg; }     
    function setRegimeEspecialSTMT($regimeEspecialSTMT) { $this->regimeEspecialSTMT = $regimeEspecialSTMT; }     
    function setContribuinteICMS($contribuinteICMS) { $this->contribuinteICMS = $contribuinteICMS; }     
    function setConsumidorFinal($consumidorFinal) { $this->consumidorFinal = $consumidorFinal; }                     
    function setRegimeEspecialSTMTAliq($regimeEspecialSTMTAliq) { $this->regimeEspecialSTMTAliq = $regimeEspecialSTMTAliq; }     
    function setRegimeEspecialSTAliq($regimeEspecialSTAliq) { $this->regimeEspecialSTAliq = $regimeEspecialSTAliq; }     

    function setEmailNfe($emailNfe) {
        $this->emailNfe = $emailNfe;
    }

    function setUserLogin($userLogin) {
        $this->userLogin = $userLogin;
    }
    function setSenhaLogin($senhaLogin) {
        $this->senhaLogin = $senhaLogin;
    }
    
    public function setLimiteCredito($limiteCredito) { $this->limiteCredito = $limiteCredito; }
    public function getLimiteCredito($format = null) {
	 switch ($format){
		case 'F':
			return number_format(doubleval($this->limiteCredito), 2, ',', '.'); 
			break;
		case 'B':
			if ($this->limiteCredito!=null){
				$num = str_replace('.', '', $this->limiteCredito);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
				break;
		default:
			return $this->limiteCredito; 
     }					   
}	
    
//############### FIM SETS E GETS ###############

/**
* Funcao para Unificar Produto
* @name unificaCliente
* @return string vazio se ocorrer com sucesso
*/
public function unificaCliente($codPermanecer, $codRetirar){
     // CAT_ATENDIMENTO - ID - CLIENTE
     $sqlCatAtendimento = "UPDATE CAT_ATENDIMENTO SET CLIENTE= '".$codPermanecer."' WHERE CLIENTE = '".$codRetirar."';";

    // CAT_CONTRATO - CLIENTE
    $sqlCatContrato = "UPDATE CAT_CONTRATO SET CLIENTE= '".$codPermanecer."' WHERE CLIENTE = '".$codRetirar."';";
    
    // EST_NOTA_FISCAL - ID - PESSOA
    $sqlEstNotaFiscal = "UPDATE EST_NOTA_FISCAL SET PESSOA= '".$codPermanecer."' WHERE PESSOA = '".$codRetirar."';";
    
    // EST_ORDEM_COMPRA - ID - CLIENTE
    $sqlEstOrdemCompra = "UPDATE EST_ORDEM_COMPRA SET CLIENTE= '".$codPermanecer."' WHERE CLIENTE = '".$codRetirar."';";

    // FAT_PEDIDO - ID - CLIENTE
    $sqlFatPedido = "UPDATE FAT_PEDIDO SET CLIENTE= '".$codPermanecer."' WHERE CLIENTE = '".$codRetirar."';";
    
    // FIN_CLIENTE_CREDITO - ID - CLIENTE
    $sqlFinClienteCredito = "UPDATE FIN_CLIENTE_CREDITO SET ID= '".$codPermanecer."' WHERE CLIENTE = '".$codRetirar."';";

     // FIN_CLIENTE - CLIENTE
    $sqlFinCliente = "DELETE FROM FIN_CLIENTE WHERE CLIENTE = '".$codRetirar."';";

    // FIN_CLIENTE_ACOMP - CLIENTE
    $sqlFinClienteAcomp = "DELETE FROM FIN_CLIENTE_ACOMP WHERE CLIENTE = '".$codRetirar."';";

    // FIN_CLIENTE_OPORTUNIDADE - CLIENTE
    $sqlFinClienteOpor = "DELETE FROM FIN_CLIENTE_OPORTUNIDADE WHERE CLIENTE = '".$codRetirar."';";

	$banco = new c_banco;
    //inicia transacao
    $banco->inicioTransacao($banco->id_connection);
    
    try{
	    $sqlCatAtendimento     =  $banco->exec_sql($sqlCatAtendimento, $banco->id_connection);
        $sqlCatContrato        =  $banco->exec_sql($sqlCatContrato, $banco->id_connection);
	    $sqlEstNotaFiscal      =  $banco->exec_sql($sqlEstNotaFiscal, $banco->id_connection);
        $sqlEstOrdemCompra     =  $banco->exec_sql($sqlEstOrdemCompra, $banco->id_connection);
	    $sqlFatPedido          =  $banco->exec_sql($sqlFatPedido, $banco->id_connection);
        $$sqlFinClienteCredito =  $banco->exec_sql($sqlFinClienteCredito, $banco->id_connection);
	    $sqlFinCliente         =  $banco->exec_sql($sqlFinCliente, $banco->id_connection);
        $sqlFinClienteAcomp    =  $banco->exec_sql( $sqlFinClienteAcomp, $banco->id_connection);
        $sqlFinClienteOpor     =  $banco->exec_sql($sqlFinClienteOpor, $banco->id_connection);
        //echo strtoupper($sqlEstOrdemCompraItem)."<BR>";
 
        $banco->commit($banco->id_connection);
        $banco->close_connection($banco->id_connection);
        return $banco->result;

    }
    catch(Exception $e){
        if ($banco->id_connection != null){
            $banco->rollback($banco->id_connection);
            $banco->close_connection($banco->id_connection);
            return false;
        }
     
    }
	
}  // fim UnificaCliente

    /**
     * Funcao para setar todos os sets da table
     * @name busca_conta
     * @param INT getId codigo do cliente, chave primaria
     */
    public function busca_conta() {
        $conta = $this->select_conta();
        $this->setId($conta[0]['CLIENTE']);
        $this->setNome($conta[0]['NOME']);
        $this->setNomeReduzido($conta[0]['NOMEREDUZIDO']);
        $this->setPessoa($conta[0]['PESSOA']);
        $this->setCnpjCpf($conta[0]['CNPJCPF']);
        $this->setDataNascimento($conta[0]['DATANASCIMENTO']);
        $this->setIeRg($conta[0]['INSCESTRG']);
        $this->setIm($conta[0]['INSCMUNICIPAL']);
        $this->setCep($conta[0]['CEP']);
        $this->setTipo($conta[0]['TPOEND']);
        $this->setTitulo($conta[0]['TITULOEND']);
        $this->setEndereco($conta[0]['ENDERECO']);
        $this->setNumero($conta[0]['NUMERO']);
        $this->setComplemento($conta[0]['COMPLEMENTO']);
        $this->setBairro($conta[0]['BAIRRO']);
        $this->setCidade($conta[0]['CIDADE']);
        $this->setCodMunicipio($conta[0]['CODMUNICIPIO']);
        $this->setSuframa($conta[0]['SUFRAMA']);
        $this->setEstado($conta[0]['UF']);
        $this->setFone($conta[0]['FONE']);
        $this->setCelular($conta[0]['CELULAR']);
        $this->setContato($conta[0]['FONECONTATO']);
        $this->setEmail($conta[0]['EMAIL']);
        $this->setHomePage($conta[0]['HOMEPAGE']);
        $this->setClasse($conta[0]['CLASSE']);
        $this->setAtividade($conta[0]['ATIVIDADE']);
        $this->setCentrocusto($conta[0]['CENTROCUSTO']);
        $this->setRepresentante($conta[0]['REPRESENTANTE']);
        $this->setEmailNfe($conta[0]['EMAILNFE']);
        $this->setUserLogin($conta[0]['USERLOGIN']);
        $this->setSenhaLogin($conta[0]['PASSWORD']);
        $this->setLimiteCredito($conta[0]['LIMITECREDITO']);
        $this->setObs($conta[0]['OBS']);
        $this->setTransversal1($conta[0]['TRANSVERSAL1']);
        $this->setTransversal2($conta[0]['TRANSVERSAL2']);
        $this->setReferencia($conta[0]['REFERENCIA']);
        $this->setRegimeEspecialST($conta[0]['REGIMEESPECIALST']);
        $this->setRegimeEspecialSTMsg($conta[0]['REGIMEESPECIALSTMSG']);
        $this->setRegimeEspecialSTMT($conta[0]['REGIMEESPECIALSTMT']);
        $this->setContribuinteICMS($conta[0]['CONTRIBUINTEICMS']);
        $this->setConsumidorFinal($conta[0]['CONSUMIDORFINAL']);        
        $this->setRegimeEspecialSTMTAliq($conta[0]['REGIMEESPECIALSTMTALIQ']);
        $this->setRegimeEspecialSTAliq($conta[0]['REGIMEESPECIALSTALIQ']);
             
    }// busca_conta

    /**
     * Funcao para verificar a existencia de registros iguais na chave primaria da table
     * @name existeContaCnpj
     * @param INT getCnpj Chave primaria da tabela
     */
     public function contaBloqueada($id) {

        $sql = "SELECT A.BLOQUEADO ";
        $sql .= "FROM fin_cliente C";
        $sql .= "LEFT JOIN FIN_CLASSE A ON (A.CLASSE=C.CLASSE) ";
        $sql .= "WHERE (CLIENTE = " . $id . "); ";

        $banco = new c_banco;
        $result = $banco->exec_sql($sql);
        $banco->close_connection();
        if (is_array($result))
            return $result[0]['BLOQUEADO'];
        else    
            return 'A';
     }

    /**
     * Funcao para verificar a existencia de registros iguais na chave primaria da table
     * @name existeContaCnpj
     * @param INT getCnpj Chave primaria da tabela
     */
    public function existeContaCnpj($cnpj, $arr = false) {
        $sql = "SELECT * ";
       $sql .= "FROM fin_cliente ";
       $sql .= "WHERE (CNPJCPF = '" . $cnpj . "'); ";
       //echo strtoupper($sql);
       $banco = new c_banco;
       $banco->exec_sql($sql);
       $banco->close_connection();
       if ($arr)
           return $banco->resultado;
       else    
           return is_array($banco->resultado);
    }

    /**
     * @name contaXmlJson
     * Funcao para montar um Jsonatravés do XML da NFe
     * @param XML arquivo xml  nfe
     * @return STRING JSON com os dados do cliente
     */
    public function contaXmlJson($item) {

        if (strlen($item->infNFe->emit->CNPJ)>11):
            $tipo = 'J';
        else:    
            $tipo = 'F';
        endif;

        //cria um array cliente
        $conta = array('conta' => array(
            array('campo' => 'opcao', 'valor' => 'cadastrar'),
            array('campo' => 'mod', 'valor' => 'crm'),
            array('campo' => 'form', 'valor' => 'contas'),
            array('campo' => 'submenu', 'valor' => 'cadastrar'),
            array('campo' => 'nome', 'valor' => substr($item->infNFe->emit->xNome, 0, 50)),
            array('campo' => 'nomeReduzido', 'valor' => substr($item->infNFe->emit->xNome, 0, 15)),
            array('campo' => 'pessoa', 'valor' => $tipo),
            array('campo' => 'cnpjCpf', 'valor' => (string) $item->infNFe->emit->CNPJ),
            array('campo' => 'ieRg', 'valor' => (string) $item->infNFe->emit->IE),
            array('campo' => 'im', 'valor' => substr($item->infNFe->emit->IM, 0, 14)),
            array('campo' => 'cep', 'valor' => intval($item->infNFe->emit->enderEmit->CEP)),
            array('campo' => 'endereco', 'valor' => substr($item->infNFe->emit->enderEmit->xLgr, 0, 60)),
            array('campo' => 'numero', 'valor' => (string) $item->infNFe->emit->enderEmit->nro),
            array('campo' => 'bairro', 'valor' => (string) $item->infNFe->emit->enderEmit->xBairro),
            array('campo' => 'cidade', 'valor' => (string) substr($item->infNFe->emit->enderEmit->xMun, 0, 40)),
            array('campo' => 'codMunicipio', 'valor' => intval($item->infNFe->emit->enderEmit->cMun)),
            array('campo' => 'estado', 'valor' => (string) $item->infNFe->emit->enderEmit->UF),
            array('campo' => 'filial', 'valor' => $this->m_empresacentrocusto)
            ));

            //converte o conteúdo do array para uma string JSON
            $json_str = json_encode($conta);
            return $json_str;
            

        
    }//fim contaXmlJson

    /**
     * @name select_conta
     * @param INT GetId codigo do cliente, chave primaria
     * @return Array todos as colunas da tabela
     */
    public function select_conta() {
        $sql = "SELECT * ";
        $sql .= "FROM fin_cliente ";
        $sql .= "WHERE (cliente = '" . $this->getId() . "') ";
        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }//fim select_conta

    

    /**
     * 
     * @param Array $letra conteudo= [0]nome | [1]Classe | [2]TipoPessoa(F/J) | [3]UF | [4]Vendedor | [5]Cidade | [6]Atividade
     * @param String $total Caso tenha algum valor, o select tera um GROUP BY
     * @return Array todas as colunas da tabela Cliente
     */
    public function where_pessoa_letra($letra) {

        $par = explode("|", $letra);

        $isWhere = false;

        /*
          if (($letra != '|||||') || ($letra!=null)){
          //		if (array_sum($par) > 0){
          } */
        if ($par[0] != '') {
            if ($isWhere == false) {
                $where .= "WHERE ";
                $isWhere = true;
            }
            $where .= "((c.nome LIKE '%" . $par[0] . "%') ";
            $where .= " or (c.nomereduzido LIKE '%" . $par[0] . "%')) ";
        }
        if ($par[1] != '') {
            if ($isWhere == false) {
                $where .= "WHERE ";
                $isWhere = true;
            }
            if ($par[0] != '') {
                $where .= "AND (c.classe = '" . $par[1] . "') ";
            } else {
                $where .= "(c.classe = '" . $par[1] . "') ";
            }
        }
        if ($par[2] != '') {
            if ($isWhere == false) {
                $where .= "WHERE ";
                $isWhere = true;
            }
            if (($par[0] != '') or ( $par[1] != '')) {
                $where .= "AND (c.pessoa = '" . $par[2] . "') ";
            } else {
                $where .= "(c.pessoa = '" . $par[2] . "') ";
            }
        }
        if ($par[3] != '') {
            if ($isWhere == false) {
                $where .= "WHERE ";
                $isWhere = true;
            }
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '')) {
                $where .= "AND (c.UF LIKE '" . $par[3] . "') ";
            } else {
                $where .= "(c.UF LIKE '" . $par[3] . "') ";
            }
        }
        if ($par[4] != '') {
            if ($isWhere == false) {
                $where .= "WHERE ";
                $isWhere = true;
            }
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '') or ( $par[3] != '')) {
                $where .= "AND (c.representante = '" . $par[4] . "') ";
            } else {
                $where .= "(c.representante = '" . $par[4] . "') ";
            }
        }
        if ($par[5] != '') {
            if ($isWhere == false) {
                $where .= "WHERE ";
                $isWhere = true;
            }
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '') or ( $par[3] != '') or ( $par[4] != '')) {
                $where .= "AND (c.cidade LIKE '" . $par[5] . "%') ";
            } else {
                $where .= "(c.cidade LIKE '" . $par[5] . "%') ";
            }
        }
        if ($par[6] != '') {
            if ($isWhere == false) {
                $where .= "WHERE ";
                $isWhere = true;
            }
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '') or ( $par[3] != '') or ( $par[4] != '') or ( $par[5] != '')) {
                $where .= "AND (c.atividade = '" . $par[6] . "') ";
            } else {
                $where .= "(c.atividade = '" . $par[6] . "') ";
            }
        }
        if ($par[8] != '') {
            if ($isWhere == false) {
                $where .= "WHERE ";
                $isWhere = true;
            }else{
                $where .= "AND ";
            }
            $where .= "(c.OBS LIKE '%" . $par[8] . "%') OR";
            $where .= "(c.REFERENCIA LIKE '%" . $par[8] . "%') OR";
            $where .= "(c.TRANSVERSAL1 LIKE '%" . $par[8] . "%') OR";
            $where .= "(c.TRANSVERSAL2 LIKE '%" . $par[8] . "%') ";
        }
        //CNPJCPF
        $par[7] != "" ? $where = "WHERE c.CNPJCPF = '".$par[7]."'" : ''; 

        return $where;
    }
    /**
     * 
     * @param Array $letra conteudo= [0]nome | [1]Classe | [2]TipoPessoa(F/J) | [3]UF | [4]Vendedor | [5]Cidade | [6]Atividade
     * @param String $total Caso tenha algum valor, o select tera um GROUP BY
     * @return Array todas as colunas da tabela Cliente
     */
    public function select_pessoa_letra($letra, $total = false) {

        $where = $this->where_pessoa_letra($letra);
        
        if ($total) {
            $count = "SELECT u.nomereduzido, count(u.nomereduzido) ";
            $count .= "FROM fin_cliente c ";
            $count .= "left join amb_usuario u on u.usuario = c.representante ";
            $count .= " ";
            $sql = $count . $where . "GROUP BY u.nomereduzido";
        }else {
            $sql = "SELECT c.*, u.nomereduzido as representante, ";
            $sql .= "IF(A.BLOQUEADO = 'S', 'BLOQUEADO', 'ATIVO') AS BLOQUEADO, ";
            $sql .= "(SELECT (Sum(VALOR)-Sum(UTILIZADO)) FROM FIN_CLIENTE_CREDITO ";
            $sql .= "WHERE (CLIENTE = c.CLIENTE) AND  ISNULL(PEDIDOUTILIZADO) ) as CREDITO ";
            $sql .= "FROM fin_cliente c ";
            $sql .= "left join amb_usuario u on u.usuario = c.representante ";
            $sql .= "left join fin_classe a on a.classe = c.classe ";
            $sql .= $where . "ORDER BY c.nome";
        }
        
        // echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    /**
     * 
     * @param Array $letra conteudo= [0]nome | [1]Classe | [2]TipoPessoa(F/J) | [3]UF | [4]Vendedor | [5]Cidade | [6]Atividade
     * @param String $total Caso tenha algum valor, o select tera um GROUP BY
     * @return Array todas as colunas da tabela Cliente
     */
    public function last_perfil() {

       
/*        $sql = "SELECT MAX(A.DATA) as data, A.RESULTADO, ";
        $sql .= "(select count(p.id) from fat_pedido p where p.situacao=3 and p.cliente=c.cliente) as vendas, ";
        $sql .= "C.* FROM FIN_CLIENTE C ";
        $sql .= "left join FIN_CLIENTE_ACOMP A ON (A.PESSOA=C.CLIENTE) ";
        
        $where = $this->where_pessoa_letra($letra);
        
        $sql .= $where . " group by c.CLIENTE ";
        $sql .= "ORDER BY c.nome";*/
        
        $sql = "SELECT A.DATA, A.RESULTADO, A.LIGARDIA, ";
        $sql .= "(select count(p.id) from fat_pedido p where p.situacao=9 and p.cliente=a.pessoa) as vendas ";
        $sql .= "FROM FIN_CLIENTE_ACOMP A ";
        $sql .= "where (a.pessoa=".$this->getId().") ";
        $sql .= "ORDER BY A.DATA DESC limit 1";
        
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

// fim select_pessoa_letra
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function incluiConta() {

        $banco = new c_banco;
        if ($banco->gerenciadorDB == 'interbase') {
            $this->setId($banco->geraID("FIN_GEN_CLIENTE"));
            $sql = "INSERT INTO FIN_CLIENTE (ID, ";
        } else {
            $sql = "INSERT INTO FIN_CLIENTE ( ";
        }

        $sqlField = "";
        $sqlValue = "";

        if ( $this->getCep() != "" ) {
            $sqlField .= "CEP,";
            $sqlValue .= $this->getCep() . ", ";        
        }       
        if ( $this->getTipo() != "" ) {
            $sqlField .= "TIPOEND, "; 
            $sqlValue .= " '".$this->getTipo() . "', ";
        }
        if ( $this->getTitulo() != "" ) {
            $sqlField .= "TITULOEND, "; 
            $sqlValue .= " '".$this->getTitulo() . "', ";        
        }
        if ( $this->getEndereco() != "" ) {
            $sqlField .= "ENDERECO, "; 
            $sqlValue .= " '".$this->getEndereco() . "', ";        
        }       
        if ( $this->getNumero() != "" ) {
            $sqlField .= "NUMERO, "; 
            $sqlValue .= " '".$this->getNumero() . "', ";        
        }       
        if ( $this->getComplemento() != "" ) {
            $sqlField .= "COMPLEMENTO, "; 
            $sqlValue .= " '".$this->getComplemento() . "', ";        
        }      
        if ( $this->getBairro() != "" ) {
            $sqlField .= "BAIRRO, "; 
            $sqlValue .= " '".$this->getBairro() . "', ";        
        }       
        if ( $this->getCidade() != "" ) {
            $sqlField .= "CIDADE, "; 
            $sqlValue .= " '".$this->getCidade() . "', ";        
        }       
        if ( $this->getCodMunicipio() != "" ) {
            $sqlField .= "CODMUNICIPIO, "; 
            $sqlValue .= " '".$this->getCodMunicipio() . "', ";
        } else {
            $sqlField .= "CODMUNICIPIO, "; 
            $sqlValue .= " '0', ";
        }
                
        $sql .= "NOME,
                    NOMEREDUZIDO, 
                    PESSOA, 
                    CNPJCPF,
                    DATANASCIMENTO, 
                    INSCESTRG, 
                    INSCMUNICIPAL,";
        $sql .= $sqlField;                             
        $sql .=    "SUFRAMA, 
                    UF, 
                    FONE, 
                    FONECONTATO, 
                    CELULAR, 
                    EMAIL, 
                    HOMEPAGE, 
                    CLASSE, 
                    ATIVIDADE,
                    CENTROCUSTO,
                    REPRESENTANTE, EMAILNFE, USERLOGIN, PASSWORD, LIMITECREDITO, USERINSERT, DATEINSERT, obs, transversal1, transversal2, referencia,
                    REGIMEESPECIALST, REGIMEESPECIALSTMSG, REGIMEESPECIALSTMT, CONTRIBUINTEICMS, CONSUMIDORFINAL, REGIMEESPECIALSTMTALIQ, REGIMEESPECIALSTALIQ)";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }

        $sql .= $this->getNome() . "', '"
                . $this->getNomeReduzido() . "', '"
                . $this->getPessoa() . "', '"
                . $this->getCnpjCpf() . "', ";
        $sql .=  $this->getDatanascimento() == '' ? "NULL, '" : "'".$this->getDataNascimento('B')."', '";
        $sql .= $this->getIeRg() . "', '"
                . $this->getIm() . "', ";
                
        $sql .= $sqlValue;

        $sql .= "'". $this->getSuframa() . "', '"
                . $this->getEstado() . "', '"
                . $this->getFone() . "', '"
                . $this->getContato() . "', '"
                . $this->getCelular() . "', '"
                . $this->getEmail() . "', '"
                . $this->getHomePage() . "', '"
                . $this->getClasse() . "', '"
                . $this->getAtividade() . "', "
                . $this->getCentroCusto() . ", "
                . $this->getRepresentante() . ", '"
                . $this->getEmailNfe() . "', '"
                . $this->getUserLogin() . "', '"
                . $this->getSenhaLogin() . "', "
                . $this->getLimiteCredito('B') . ", "
                . $this->m_userid . ", '"
                . date("Y-m-d H:i:s") . "', '"
                . $this->getObs() . "', '"
                . $this->getTransversal1() . "', '"
                . $this->getTransversal2() . "', '"
                . $this->getReferencia() . "', '"
                . $this->getRegimeEspecialST() . "', '"
                . $this->getRegimeEspecialSTMSG() . "', '"                               
                . $this->getRegimeEspecialSTMT() . "', '"    
                . $this->getContribuinteICMS() . "', '"   
                . $this->getConsumidorFinal() . "', "   
                . $this->getRegimeEspecialSTMTAliq() . ", " 
                . $this->getRegimeEspecialSTAliq() . "); ";                                                    
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $result =  $banco->exec_sql($sql);
        $banco->result;
        $status = $banco->result;
        $banco->close_connection();
    
        return $status;

    }

// fim incluiClasse
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function alteraConta() {

        $sql = "UPDATE fin_cliente ";
        $sql .= "SET  nome = '" . $this->getNome() . "', ";
        $sql .= "NOMEREDUZIDO = '" . $this->getNomeReduzido() . "', ";
        $sql .= "PESSOA = '" . $this->getPessoa() . "', ";
        $sql .= "CNPJCPF = '" . $this->getCnpjCpf() . "', ";
        $sql .=  $this->getDatanascimento() == '' ? "DATANASCIMENTO = NULL, " : 
                "DATANASCIMENTO = '".$this->getDataNascimento('B')."', ";
        $sql .= "INSCESTRG = '" . $this->getIeRg() . "', ";
        $sql .= "INSCMUNICIPAL = '" . $this->getIm() . "', ";
        $sql .= "CEP = " . $this->getCep() . ", ";
        $sql .= "TIPOEND = '" . $this->getTipo() . "', ";
        $sql .= "TITULOEND = '" . $this->getTitulo() . "', ";
        $sql .= "ENDERECO = '" . $this->getEndereco() . "', ";
        $sql .= "NUMERO = '" . $this->getNumero() . "', ";
        $sql .= "COMPLEMENTO = '" . $this->getComplemento() . "', ";
        $sql .= "BAIRRO = '" . $this->getBairro() . "', ";
        $sql .= "CIDADE = '" . $this->getCidade() . "', ";
        $sql .= "CODMUNICIPIO = '" . $this->getCodMunicipio() . "', ";
        $sql .= "SUFRAMA = '" . $this->getSuframa() . "', ";
        $sql .= "UF = '" . $this->getEstado() . "', ";
        $sql .= "FONE = '" . $this->getFone() . "', ";
        $sql .= "FONECONTATO = '" . $this->getContato() . "', ";
        $sql .= "CELULAR = '" . $this->getCelular() . "', ";
        $sql .= "EMAIL = '" . $this->getEmail() . "', ";
        $sql .= "HOMEPAGE = '" . $this->getHomePage() . "', ";
        $sql .= "CLASSE = '" . $this->getClasse() . "', ";
        $sql .= "ATIVIDADE = '" . $this->getAtividade() . "', ";
        $sql .= "CENTROCUSTO = " . $this->getCentroCusto() . ", ";
        $sql .= "REPRESENTANTE = " . $this->getRepresentante() . ", ";
        $sql .= "EMAILNFE = '" . $this->getEmailNfe() . "', ";
        $sql .= "USERLOGIN = '" . $this->getUserLogin() . "', ";
        $sql .= "PASSWORD = '" . $this->getSenhaLogin() . "', ";
        $sql .= "LIMITECREDITO = " . $this->getLimiteCredito('B') . ", ";
        $sql .= "OBS = '" . $this->getObs() . "', ";
        $sql .= "TRANSVERSAL1 = '" . $this->getTransversal1() . "', ";
        $sql .= "TRANSVERSAL2 = '" . $this->getTransversal2() . "', ";
        $sql .= "REFERENCIA = '" . $this->getReferencia() . "', ";
        $sql .= "REGIMEESPECIALST = '" . $this->getRegimeEspecialST() . "', ";
        $sql .= "REGIMEESPECIALSTMSG = '" . $this->getRegimeEspecialSTMsg() . "', ";                
        $sql .= "REGIMEESPECIALSTMT = '" . $this->getRegimeEspecialSTMT() . "', ";
        $sql .= "CONTRIBUINTEICMS = '" . $this->getContribuinteICMS() . "', ";
        $sql .= "CONSUMIDORFINAL = '" . $this->getConsumidorfinal() . "', "; 
        $sql .= "REGIMEESPECIALSTMTALIQ = '" . $this->getRegimeEspecialSTMTAliq() . "', ";
        $sql .= "REGIMEESPECIALSTALIQ = '" . $this->getRegimeEspecialSTAliq() . "', ";                       
	$sql .= "userchange = ".$this->m_userid.", ";
	$sql .= "datechange = '".date("Y-m-d H:i:s")."' ";
        $sql .= "WHERE cliente = " . $this->getId() . ";";
        //ECHO strtoupper($sql);
        $banco = new c_banco;
	    $banco->exec_sql($sql);
	    $status = $banco->result;
	    $banco->close_connection();

	    return $status;
    }

    /**
     * Funcao para verificar a existencia de lancamentos no financeiro
     * @name existeLancamentos
     * @param INT código cliente
     */
     public function existeLancamentosPessoa($codigo) {
         $sql = "SELECT * ";
        $sql .= "FROM fin_lancamento ";
        $sql .= "WHERE (pessoa = '" . $codigo . "'); ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
     }
    
    
// fim alteraClasse
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function excluiConta() {

        $sql = "DELETE FROM fin_cliente ";
        $sql .= "WHERE cliente = '" . $this->getId() . "';";
        $banco = new c_banco;
	    $banco->exec_sql($sql);
	    $status = $banco->result;
	    $banco->close_connection();

	    return $status;
    }
 // fim excluiClasse   

    public function selecionaCreditoUtilizadoCliente($cliente, $pedido) {

        $sql = "SELECT * FROM FIN_CLIENTE_CREDITO ";
        $sql .= "WHERE (cliente = '" . $cliente . "') and ";
        $sql .= "(pedidoutilizado like '%;" . $pedido . "%' ) ";
        $banco = new c_banco;
        $res_classe = $banco->exec_sql($sql);
        $banco->close_connection();
        return $res_classe;
        
    }

public function updateCreditoCliente($id, $pedidoutilizado = "", $valor = 0) {

    $sql = "UPDATE fin_cliente_credito ";
    $sql .= "SET  UTILIZADO =  ".$valor." ";
    if ($pedidoutilizado == "") {
        $sql .= ", pedidoutilizado = NULL ";
    } else {
        $sql .= ", pedidoutilizado =  '".$pedidoutilizado."' ";
    }
    $sql .= "WHERE ID = " . $id . ";";
    $banco = new c_banco;
    $res_pessoa = $banco->exec_sql($sql);
    $banco->close_connection();
    return '';
}

public function existeContaDuplicada() {
   $sql = "SELECT Count(CNPJCPF) as QUANT, CNPJCPF ";
   $sql .= "FROM fin_cliente ";
   $sql .= "WHERE (CNPJCPF != '00000000000') and (CNPJCPF != '')  ";
   $sql .= "GROUP BY CNPJCPF ";
   $sql .= "HAVING COUNT(CNPJCPF) > 1 ";
   $sql .= "LIMIT 500 ";
   $banco = new c_banco;
   $banco->exec_sql($sql);
   $banco->close_connection();
   $result = $banco->resultado;

   for ($i = 0; $i < count($result); $i++) {
        $sql1 = "SELECT CLIENTE FROM FIN_CLIENTE ";
        $sql1.= "WHERE (CNPJCPF = '".$result[$i]['CNPJCPF']."')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql1);
        $consulta->close_connection();
        $resultado = $consulta->resultado;
        $cliente01 = $resultado[0]['CLIENTE'];
        $cliente02 = $resultado[1]['CLIENTE'];
        
        $sql2 = "UPDATE FIN_LANCAMENTO SET PESSOA = ".$cliente01." ";
        $sql2.= "WHERE PESSOA = ".$cliente02;
        $consulta01 = new c_banco();
        $consulta01->exec_sql($sql2);
        $consulta01->close_connection();

        $sql3 = "DELETE FROM FIN_CLIENTE WHERE CLIENTE = ".$cliente02;
        $consulta02 = new c_banco();
        $consulta02->exec_sql($sql3);
        $consulta02->close_connection();
        
    }
}

public function verificaNome($name){
    $sql = "SELECT CLIENTE, NOME ";
    $sql .= "FROM fin_cliente ";
    $sql .= "WHERE NOME LIKE ( '" . $name . "'); ";
    //echo strtoupper($sql);
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

public function verificaCelular($cel){
    $sql = "SELECT CLIENTE, NOME ";
    $sql .= "FROM fin_cliente ";
    $sql .= "WHERE CELULAR LIKE ( '" . $cel . "'); ";
    //echo strtoupper($sql);
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

}

//	END OF THE CLASS
?>
