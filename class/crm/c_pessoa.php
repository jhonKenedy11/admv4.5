<?php

/****************************************************************************
*Cliente...........:
*Contratada........: Infosystem
*Desenvolvedor.....: Marcio Sergio da Silva
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: C_PESSOA - cadastro de Pessoas - BUSINESS CLASS
*Ultima Atualizacao: 17/09/05 - 29/03/06 - 03/08/2010
****************************************************************************/

$dir = dirname(__FILE__);

include_once($dir."/../../bib/c_user.php");
include_once($dir."/../../bib/c_date.php");

//Class C_PESSOA
Class c_pessoa extends c_user {

	// Campos tabela
	private $id = NULL;
	private $nome = NULL;
	private $nomeReduzido = NULL;
	private $pessoa = NULL;
	private $cnpjCpf = NULL;
	private $ieRg = NULL;
	private $im = NULL;
	private $cep = NULL;
	private $tipo = NULL;
	private $titulo = NULL;
	private $endereco = NULL;
	private $numero = NULL;
	private $complemento = NULL;
	private $bairro = NULL;
	private $codMunicipio = NULL;
	private $cidade = NULL;
	private $estado = NULL;
	private $foneArea = NULL;
	private $foneNum = NULL;
	private $faxArea = NULL;
	private $faxNum = NULL;
	private $contato = NULL;
	private $email = NULL;
	private $homePage = NULL;
	private $classe = NULL;
	private $atividade = NULL;
	private $centrocusto = NULL;
	private $obs = NULL;

	// campos Acompanhamento
	private $dataContato = NULL;
	private $horaContato = NULL;
	private $acao = NULL;
	private $vendedorAcomp = NULL;
	private $proximoContato = NULL;
	private $proximoContatoHora = NULL;
	private $resultContato = NULL;
	private $veiculo = NULL;
	private $origem = NULL;
	private $destino = NULL;
	private $km = NULL;

	// campos Oportunidade
	private $dataOportunidade = NULL;
	private $horaOportunidade = NULL;
	private $oportunidade = NULL;
	private $resumo = NULL;
	private $status = NULL;
	private $nomeProposta = NULL;
	private $valorOportunidade = NULL;
	private $vendedorOportunidade = NULL;
	


	//construtor
	function c_pessoa(){

	}

	//---------------------------------------------------------------
	//---------------------------------------------------------------

	// Campos Tabela
	public function setId($cliente){
		$this->id = $cliente;
	}
	public function getId(){
		return $this->id;
	}

	public function setNome($nome){
		$this->nome = strtoupper($nome);
	}
	public function getNome(){
		return $this->nome;
	}

	public function setNomeReduzido($nomeReduzido){
		$this->nomeReduzido = strtoupper($nomeReduzido);
	}
	public function getNomeReduzido(){
		return $this->nomeReduzido;
	}

	public function setPessoa($pessoa){
		$this->pessoa = strtoupper($pessoa);
	}
	public function getPessoa(){
		return $this->pessoa;
	}

	public function setCnpjCpf($cnpjCpf){
		$this->cnpjCpf = strtoupper($cnpjCpf);
	}
	public function getCnpjCpf(){
		return $this->cnpjCpf;
	}

	public function setIeRg($ieRg){
		$this->ieRg = strtoupper($ieRg);
	}
	public function getIeRg(){
		return $this->ieRg;
	}

	public function setIm($im){
		$this->im = strtoupper($im);
	}
	public function getIm(){
		return $this->im;
	}

	public function setCep($cep){
		if ($cep != '') {
			$this->cep = strtoupper($cep); }
			else {
				$this->cep = "80000000"; }
	}
	public function getCep(){
		return $this->cep;
	}

	public function setTipo($tipo){
		$this->tipo = strtoupper($tipo);
	}
	public function getTipo(){
		return $this->tipo;
	}

	public function setTitulo($titulo){
		$this->titulo = strtoupper($titulo);
	}
	public function getTitulo(){
		return $this->titulo;
	}

	public function setEndereco($endereco){
		$this->endereco = strtoupper($endereco);
	}
	public function getEndereco(){
		return $this->endereco;
	}

	public function setNumero($numero){
		$this->numero = strtoupper($numero);
	}
	public function getNumero(){
		return $this->numero;
	}

	public function setComplemento($complemento){
		$this->complemento = strtoupper($complemento);
	}
	public function getComplemento(){
		return $this->complemento;
	}

	public function setBairro($bairro){
		$this->bairro = strtoupper($bairro);
	}
	public function getBairro(){
		return $this->bairro;
	}

	public function setCidade($cidade){
		$this->cidade = strtoupper($cidade);
	}
	public function getCidade(){
		return $this->cidade;
	}

	public function setCodMunicipio(){
  		$consulta = new c_banco();
  		$sql = "select codigo from amb_municipio where nome='".$this->getCidade()."'";
  		$consulta->exec_sql($sql);
		$consulta->close_connection();
  		$result = $consulta->resultado;
                $this->codMunicipio = $result[0]['CODIGO'];
	}
	public function getCodMunicipio(){
		return $this->codMunicipio;
	}

        public function setEstado($estado){
		$this->estado = strtoupper($estado);
	}
	public function getEstado(){
		return $this->estado;
	}

	public function setFoneArea($foneArea){
		$this->foneArea = strtoupper($foneArea);
	}
	public function getFoneArea(){
		return $this->foneArea;
	}

	public function setFoneNum($foneNum){
		$this->foneNum = strtoupper($foneNum);
	}
	public function getFoneNum(){
		return $this->foneNum;
	}

	public function setFaxArea($faxArea){
		$this->faxArea = strtoupper($faxArea);
	}
	public function getFaxArea(){
		return $this->faxArea;
	}

	public function setFaxNum($faxNum){
		$this->faxNum = strtoupper($faxNum);
	}
	public function getFaxNum(){
		return $this->faxNum;
	}

	public function setContato($contato){
		$this->contato = strtoupper($contato);
	}
	public function getContato(){
		return $this->contato;
	}

	public function setEmail($email){
		$this->email = $email;
	}
	public function getEmail(){
		return $this->email;
	}

	public function setHomePage($homePage){
		$this->homePage = $homePage;
	}
	public function getHomePage(){
		return $this->homePage;
	}

	public function setClasse($classe){
		$this->classe = strtoupper($classe);
	}
	public function getClasse(){
		return $this->classe;
	}

	public function setAtividade($atividade){
		$this->atividade = strtoupper($atividade);
	}
	public function getAtividade(){
		return $this->atividade;
	}

	public function setVendedor($vendedor){
		$this->vendedor = $vendedor;
	}
	public function getVendedor(){
		return $this->vendedor;
	}

	public function setCentroCusto($cCusto){
		$this->centroCusto = $cCusto;
	}
	public function getCentroCusto(){
		return $this->centroCusto;
	}
	
	public function setObs($obs){
		$this->obs = strtoupper($obs);
	}
	public function getObs(){
		return $this->obs;
	}


	//--------------------------------------------------------------------------
	//====== Pessoa Acompanhamento
	//--------------------------------------------------------------------------
	
	public function setDataContato($dataContato){
		$this->dataContato = $dataContato;
	}
	public function getDataContato($format = null){
		switch ($format) {
			case 'F':
				return date('d/m/Y', strtotime($this->dataContato)); 
				break;
			case 'B':
				return c_date::convertDateBdSh($this->dataContato,$this->m_banco); 
				break;
			default:
				return $this->dataContato;
		}
	}

	public function setHoraContato($horaContato){
		$this->horaContato = $horaContato;
	}
	public function getHoraContato($format = null){
		switch ($format) {
			case 'F':
				return date('H:m:s', strtotime($this->horaContato)); 
				break;
			case 'B':
//				$hora = date('H:m:s', strtotime($this->horaContato)); 
				return $this->horaContato; 
				break;
			default:
				return $this->horaContato;
		}
	}

	public function setVendedorAcomp($vendedorAcomp){
		$this->vendedorAcomp = $vendedorAcomp;
	}
	public function getVendedorAcomp(){
		return $this->vendedorAcomp;
	}
		
	public function setAcao($acao){
		$this->acao = $acao;
	}
	public function getAcao(){
		return $this->acao;
	}

	public function setProximoContato($proximoContato){
		$this->proximoContato = $proximoContato;
	}
	public function getProximoContato($format = null){
		if ($this->proximoContato != null){
			switch ($format) {
				case 'F':
					return date('d/m/Y', strtotime($this->proximoContato)); 
					break;
				case 'B':
					return c_date::convertDateBdSh($this->proximoContato,$this->m_banco); 
					break;
				default:
					return $this->proximoContato;
			}
		}
		else{
			return null;}
	}

	public function setProximoContatoHora($proximoContatoHora){
		$this->proximoContatoHora = $proximoContatoHora;
	}
	public function getProximoContatoHora($format = null){
		if ($this->proximoContatoHora != null){
                    switch ($format) {
                    case 'F':
                        return date('H:i:s', strtotime($this->proximoContatoHora));
                        break;
                    default:
                        return $this->proximoContatoHora;
                    }
		}
		else{
			return null;}
	}

	public function setResultContato($resultContato){
		$this->resultContato = strtoupper($resultContato);
	}
	public function getResultContato(){
		return $this->resultContato;
	}

	public function setVeiculo($veiculo){
		$this->veiculo = strtoupper($veiculo);
	}
	public function getVeiculo(){
		return $this->veiculo;
	}

	public function setOrigem($origem){
		$this->origem = strtoupper($origem);
	}
	public function getOrigem(){
		return $this->origem;
	}

	public function setDestino($destino){
		$this->destino = strtoupper($destino);
	}
	public function getDestino(){
		return $this->destino;
	}

	public function setKM($km){
		$this->km = ($km);
	}
	public function getKM(){
		if ($this->km!=null) {
			return $this->km;}
		else {
			return 0;}
	}

	//-----------------------------------------------------------------------
	//====== Pessoa Oportunidade
	//-----------------------------------------------------------------------
	
	public function setDataOportunidade($data){
		$this->dataOportunidade = $data;
	}
	public function getDataOportunidade($format = null){
		switch ($format) {
			case 'F':
				return date('d/m/Y', strtotime($this->dataOportunidade)); 
				break;
			case 'B':
				return c_date::convertDateBdSh($this->dataOportunidade,$this->m_banco); 
				break;
			default:
				return $this->dataOportunidade;
		}
	}

	public function setHoraOportunidade($hora){
		$this->horaOportunidade = $hora;
	}
	public function getHoraOportunidade($format = null){
		switch ($format) {
			case 'F':
				return date('H:m:s', strtotime($this->horaOportunidade)); 
				break;
			case 'B':
//				return date('H:m:s', strtotime($this->horaOportunidade)); 
				return $this->horaOportunidade; 
				break;
			default:
				return $this->horaOportunidade;
		}
	}

	public function setOportunidade($oportunidade){
		$this->oportunidade = strtoupper($oportunidade);
	}
	public function getOportunidade(){
		return $this->oportunidade;
	}

	public function setResumo($resumo){
		$this->resumo = strtoupper($resumo);
	}
	public function getResumo(){
		return $this->resumo;
	}

	public function setStatus($status){
		$this->status = strtoupper($status);
	}
	public function getStatus(){
		return $this->status;
	}

	public function setNomeProposta($nomeProposta){
		$this->nomeProposta = strtoupper($nomeProposta);
	}
	public function getNomeProposta(){
		return $this->nomeProposta;
	}

	public function setValorOportunidade($valor){
		$this->valorOportunidade =   $valor;
	}
	public function getValorOportunidade($format = null){
		if ($format=='F') {
			return number_format(doubleval($this->valorOportunidade), 2, ',', '.'); }
		else {
			if ($this->valorOportunidade!=null){
				$num = str_replace('.', '', $this->valorOportunidade);
				$num = str_replace(',', '.', $num);
				return $num; }
			else{
				return 0; }
		}	

	}

	public function setVendedorOportunidade($vendedor){
		$this->vendedorOportunidade = $vendedor;
	}
	public function getVendedorOportunidade(){
		return $this->vendedorOportunidade;
	}
		
        public function buscaCadastroPessoa(){
            $pessoa = $this->select_pessoa();
            $this->setNome($pessoa[0]['NOME']);
            $this->setNomeReduzido($pessoa[0]['NOMEREDUZIDO']);
            $this->setPessoa($pessoa[0]['PESSOA']);
            $this->setCnpjCpf($pessoa[0]['CNPJCPF']);
            $this->setIeRg($pessoa[0]['INSCESTRG']);
            $this->setIm($pessoa[0]['INSCMUNICIPAL']);
            $this->setCep($pessoa[0]['CEP']);
            $this->setTipo($pessoa[0]['TIPOEND']);
            $this->setTitulo($pessoa[0]['TITULOEND']);
            $this->setEndereco($pessoa[0]['ENDERECO']);
            $this->setNumero($pessoa[0]['NUMERO']);
            $this->setComplemento($pessoa[0]['COMPLEMENTO']);
            $this->setBairro($pessoa[0]['BAIRRO']);
            $this->setCidade($pessoa[0]['CIDADE']);
            $this->setEstado($pessoa[0]['UF']);
            $this->setFoneArea($pessoa[0]['FONEAREA']);
            $this->setFoneNum($pessoa[0]['FONE']);
            $this->setFaxArea($pessoa[0]['FAXAREA']);
            $this->setFaxNum($pessoa[0]['FAX']);
            $this->setContato($pessoa[0]['FONECONTATO']);
            $this->setEmail($pessoa[0]['EMAIL']);
            $this->setHomePage($pessoa[0]['HOMEPAGE']);
            $this->setClasse($pessoa[0]['CLASSE']);
            $this->setAtividade($pessoa[0]['ATIVIDADE']);
            $this->setVendedor($pessoa[0]['REPRESENTANTE']);
            $this->setObs($pessoa[0]['OBS']);
            $this->setCentroCusto($pessoa[0]['CENTROCUSTO']);
        } // buscaCadastroPessoa
	
	//---------------------------------------------------------------
	// --- Consulta Agenda
	//---------------------------------------------------------------
	public function select_pessoaAgenda($letra){

		$par = explode("|", $letra);
                $par[0] = c_date::convertDateBdSh($par[0],$this->m_banco);
                $par[1] = c_date::convertDateBdSh($par[1],$this->m_banco);
		$sql  = "SELECT a.ligardia, A.LIGARDIAHORA, A.RESULTADO, p.descricao, c.cliente, c.nomereduzido, c.fonearea, c.fone, c.fonecontato ";
		$sql .= "FROM fin_cliente_acomp a ";
		$sql .= "inner join fin_cliente c on c.cliente = a.cliente ";
		$sql .= "inner join fat_atividade_acomp p on p.atividade = a.atividade ";
		$sql .= " ";
		if ($letra != '||'){
			$sql .= "WHERE ";}
			if ($par[0] != ''){
				$sql .= "(a.ligardia >= '".$par[0]."') ";}
				if ($par[1] != ''){
					if ($par[0] != ''){
						$sql .= "AND (a.ligardia <= '".$par[1]."') ";}
				}
				if ($par[2] != ''){
					if (($par[0] != '') or ($par[1] != '')){
						$sql .= "AND (a.usrvendedor = ".$par[2].") ";}
						else{
							$sql .= "(a.usrvendedor = ".$par[2].") ";}
				}
				$sql .= "ORDER BY a.ligardia";
		//echo strtoupper($sql)."<BR>";

		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoaAgenda

	//---------------------------------------------------------------
	// --- Consulta Oportunidade
	//---------------------------------------------------------------
	public function select_pessoaConsultaOportunidade($letra){

		$par = explode("|", $letra);

		$sql  = "SELECT o.data, o.hora, o.status, o.oportunidade, c.cliente, c.nomereduzido, o.valor ";
		$sql .= "FROM fin_cliente c ";
		$sql .= "inner join fin_cliente_oportunidade o on c.cliente = o.cliente ";
		$sql .= " ";
		if ($letra != '|'){
			$sql .= "WHERE ";}
			if ($par[0] != ''){
				$sql .= "(o.vendedor = ".$par[0].") ";}
				if ($par[1] != ''){
					if ($par[0] != ''){
						$sql .= "AND (o.status = '".$par[1]."') ";}
						else{
							$sql .= "(o.status = '".$par[1]."') ";}
				}
				$sql .= "ORDER BY o.data, o.status";
		 //ECHO strtoupper($sql);

		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoaOportunidade

	
	
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_pessoa(){

		$sql  = "SELECT DISTINCT * ";
		$sql .= "FROM fin_cliente ";
		$sql .= "WHERE (cliente = ".$this->getId().") ";
		//ECHO strtoupper($sql)."<BR>";

		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoa
        
        //---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_pessoa_nome($nome){

		$sql  = "SELECT * ";
		$sql .= "FROM fin_cliente ";
		$sql .= "WHERE NOME LIKE '%".$nome."%' ";
		//ECHO strtoupper($sql);

		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoa

        public function select_pessoa_cnpj_cpf($cnpjCpf){

		$sql  = "SELECT * ";
		$sql .= "FROM fin_cliente ";
		$sql .= "WHERE CNPJCPF = '".$cnpjCpf."' ";
		//ECHO strtoupper($sql);

		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoa
        
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_pessoa_geral(){
		$sql  = "SELECT DISTINCT * ";
		$sql .= "FROM fin_cliente ";
		$sql .= "ORDER BY nome ";
		//	ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoa_geral
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_pessoa_letra($letra, $total = false){

		$par = explode("|", $letra);
//		print_r($par);

		$sql  = "SELECT c.* ";
		$sql .= "FROM fin_cliente c ";
		$count  = "SELECT u.nomereduzido, count(u.nomereduzido) ";
//		$count  = "SELECT count(c.nomereduzido) ";
		$count .= "FROM fin_cliente c ";
		$count .= "left join amb_usuario u on u.usuario = c.representante ";
		$count .= " ";
                $isWhere = false;
                
                /*
		if (($letra != '|||||') || ($letra!=null)){
//		if (array_sum($par) > 0){
			}*/
			if ($par[0] != ''){
                            if ($isWhere == false){
                                $where .= "WHERE ";
                                $isWhere = true;
                            }
                            $where .= "(c.nome LIKE '".$par[0]."%') ";}
                            if ($par[1] != ''){
                                if ($isWhere == false){
                                    $where .= "WHERE ";
                                    $isWhere = true;
                                }
                                if ($par[0] != ''){
                                    $where .= "AND (c.classe = '".$par[1]."') ";}
                                else{
                                    $where .= "(c.classe = '".$par[1]."') ";}
				}
                            if ($par[2] != ''){
                                if ($isWhere == false){
                                    $where .= "WHERE ";
                                    $isWhere = true;
                                }
                                if (($par[0] != '') or ($par[1] != '')){
                                    $where .= "AND (c.pessoa = '".$par[2]."') ";}
                                else{
                                    $where .= "(c.pessoa = '".$par[2]."') ";}
				}
				if ($par[3] != ''){
                                    if ($isWhere == false){
                                        $where .= "WHERE ";
                                        $isWhere = true;
                                    }
                                    if (($par[0] != '') or ($par[1] != '') or ($par[2] != '')){
                                        $where .= "AND (c.UF LIKE '".$par[3]."') ";}
                                    else{
                                        $where .= "(c.UF LIKE '".$par[3]."') ";}
				}
				if ($par[4] != ''){
                                    if ($isWhere == false){
                                        $where .= "WHERE ";
                                        $isWhere = true;
                                    }
                                    if (($par[0] != '') or ($par[1] != '') or ($par[2] != '') or ($par[3] != '')){
                                        $where .= "AND (c.representante = '".$par[4]."') ";}
                                    else{
                                        $where .= "(c.representante = '".$par[4]."') ";}
				}
				if ($par[5] != ''){
                                    if ($isWhere == false){
                                        $where .= "WHERE ";
                                        $isWhere = true;
                                    }
                                    if (($par[0] != '') or ($par[1] != '') or ($par[2] != '') or ($par[3] != '') or ($par[4] != '')){
                                        $where .= "AND (c.cidade LIKE '".$par[5]."%') ";}
                                    else{
                                        $where .= "(c.cidade LIKE '".$par[5]."%') ";}
				}
				if ($par[6] != ''){
                                    if ($isWhere == false){
                                        $where .= "WHERE ";
                                        $isWhere = true;
                                    }
                                    if (($par[0] != '') or ($par[1] != '') or ($par[2] != '') or ($par[3] != '') or ($par[4] != '') or ($par[5] != '')){
                                        $where .= "AND (c.atividade = '".$par[6]."') ";}
                                    else{
                                        $where .= "(c.atividade = '".$par[6]."') ";}
				}
				if ($total) {
				$sql = $count.$where."GROUP BY u.nomereduzido"; }
//				$sql = $count.$where; }
			else {
				$sql .= $where."ORDER BY c.nome"; }

		//echo $par.' '.$sql;
                // echo strtoupper($sql);
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	}// fim select_pessoa_letra

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function incluiPessoa(){

		$banco = new c_banco;
		
		//variavel auxiliar para pegar os 20 primeiros caracteres
//		$aux_bairro = substr($this->getBairro(), 0,20);


                if ($banco->gerenciadorDB == 'interbase') {
                    $this->setId($banco->geraID("FIN_GEN_CLIENTE"));
                    $sql  = "INSERT INTO FIN_CLIENTE (ID, ";
                }
                else{
                    $sql  = "INSERT INTO FIN_CLIENTE ( ";
                }

		$sql  .= "NOME,
                    NOMEREDUZIDO, 
                    PESSOA, 
                    CNPJCPF, 
                    INSCESTRG, 
                    INSCMUNICIPAL, 
                    CEP, 
                    TIPOEND, 
                    TITULOEND, 
                    ENDERECO, 
                    NUMERO, 
                    COMPLEMENTO, 
                    BAIRRO, 
                    CIDADE, 
                    UF, 
                    FONEAREA, 
                    FONE, 
                    FAXAREA, 
                    FAX, 
                    FONECONTATO, 
                    EMAIL, 
                    HOMEPAGE, 
                    CLASSE, 
                    ATIVIDADE,
                    CENTROCUSTO,
                    REPRESENTANTE, obs)";

                if ($banco->gerenciadorDB == 'interbase') {
                    $sql .= "VALUES (".$this->getId().", '";
                }
                else{
                    $sql .= "VALUES ('";
                }	
                
		$sql .= $this->getNome()."', '"
		.$this->getNomeReduzido()."', '"
		.$this->getPessoa()."', '"
		.$this->getCnpjCpf()."', '"
		.$this->getIeRg()."', '"
		.$this->getIm()."', "
		.$this->getCep().", '"
		.$this->getTipo()."', '"
		.$this->getTitulo()."', '"
		.$this->getEndereco()."', '"
		.$this->getNumero()."', '"
		.$this->getComplemento()."', '"
		.$this->getBairro()."', '"
		.$this->getCidade()."', '"
		.$this->getEstado()."', '"
		.$this->getFoneArea()."', '"
		.$this->getFoneNum()."', '"
		.$this->getFaxArea()."', '"
		.$this->getFaxNum()."', '"
		.$this->getContato()."', '"
		.$this->getEmail()."', '"
		.$this->getHomePage()."', '"
		.$this->getClasse()."', '"
		.$this->getAtividade()."', "
		.$this->getCentroCusto().", "
		.$this->getVendedor().", '"
		.$this->getObs()."') ";
		//echo strtoupper($sql)."<BR>";
		$res_pessoa =  $banco->exec_sql($sql);
                $lastReg = mysqli_insert_id($banco->id_connection);
                $banco->close_connection();

		if($res_pessoa > 0){
			return $lastReg;
		}
		else{
			return 'Os dados de Pessoas '.$this->getNome().' nao foram cadastrados!';
		}
	} // fim incluiPessoa

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function alteraPessoa(){

		$sql  = "UPDATE fin_cliente ";
		$sql .= "SET  nome = '".$this->getNome()."', " ;
		$sql .= "NOMEREDUZIDO = '".$this->getNomeReduzido()."', " ;
		$sql .= "PESSOA = '".$this->getPessoa()."', " ;
		$sql .= "CNPJCPF = '".$this->getCnpjCpf()."', " ;
		$sql .= "INSCESTRG = '".$this->getIeRg()."', " ;
		$sql .= "INSCMUNICIPAL = '".$this->getIm()."', ";
		$sql .= "CEP = ".$this->getCep().", ";
		$sql .= "TIPOEND = '".$this->getTipo()."', ";
		$sql .= "TITULOEND = '".$this->getTitulo()."', ";
		$sql .= "ENDERECO = '".$this->getEndereco()."', ";
		$sql .= "NUMERO = '".$this->getNumero()."', ";
		$sql .= "COMPLEMENTO = '".$this->getComplemento()."', ";
		$sql .= "BAIRRO = '".$this->getBairro()."', ";
		$sql .= "CIDADE = '".$this->getCidade()."', ";
		$sql .= "UF = '".$this->getEstado()."', ";
		$sql .= "FONEAREA = '".$this->getFoneArea()."', ";
		$sql .= "FONE = '".$this->getFoneNum()."', ";
		$sql .= "FAXAREA = '".$this->getFaxArea()."', ";
		$sql .= "FAX = '".$this->getFaxNum()."', ";
		$sql .= "FONECONTATO = '".$this->getContato()."', ";
		$sql .= "EMAIL = '".$this->getEmail()."', ";
		$sql .= "HOMEPAGE = '".$this->getHomePage()."', ";
		$sql .= "CLASSE = '".$this->getClasse()."', ";
		$sql .= "ATIVIDADE = '".$this->getAtividade()."', ";
		$sql .= "CENTROCUSTO = ".$this->getCentroCusto().", ";
		$sql .= "REPRESENTANTE = ".$this->getVendedor().", ";
		$sql .= "obs = '".$this->getObs()."' " ;
		$sql .= "WHERE cliente = ".$this->getId().";";
                //ECHO strtoupper($sql);
		$banco = new c_banco;
		$res_pessoa =  $banco->exec_sql($sql);
		//	$res_pessoa =  $banco->m_cmdTuples ;
		$banco->close_connection();

		if($res_pessoa > 0){
			return '';
		}
		else{
			return 'Os dados de Pessoas '.$this->getNome().' nao foram alterados!';
		}

	}  // fim alteraPessoa

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function excluiPessoa(){

		$sql  = "DELETE FROM fin_cliente ";
		$sql .= "WHERE cliente = ".$this->getId();
		//    echo $sql;
		$banco = new c_banco;
		$res_pessoa =  $banco->exec_sql($sql);
		//	$res_pessoa =  $banco->m_cmdTuples ;
		$banco->close_connection();

		if($res_pessoa > 0){
			return '';
		}
		else{
			return 'Os dados de Pessoas '.$this->getId().' n&atilde;o foram excluidos!';
		}
	}  // fim excluiPessoa


	//---------------------------------------------------------------
	// procedures Pessoa Acompanhamento
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	// --- Consulta Acompanhamento
	//---------------------------------------------------------------
	public function select_pessoaConsultaAcompanhamento($letra, $total = false){

		$par = explode("|", $letra);
                $par[0] = c_date::convertDateBdSh($par[0],$this->m_banco);
                $par[1] = c_date::convertDateBdSh($par[1],$this->m_banco);
                
		$sql  = "SELECT a.data, a.hora, a.atividade,A.RESULTADO, f.descricao, a.ligardia, c.cliente, c.nomereduzido, u.nomereduzido as vendedor ";
		$sql .= "FROM fin_cliente c ";
		$sql .= "inner join fin_cliente_acomp a on c.cliente = a.cliente ";
		$sql .= "inner join fat_atividade_acomp f on f.atividade = a.atividade ";
		$sql .= "left join amb_usuario u on u.usuario = a.usrvendedor ";
		$sql .= " ";
		$count  = "SELECT u.nomereduzido, count(u.nomereduzido) as count ";
		$count .= "FROM fin_cliente c ";
		$count .= "inner join fin_cliente_acomp a on c.cliente = a.cliente ";
		$count .= "inner join fat_atividade_acomp f on f.atividade = a.atividade ";
		$count .= "left join amb_usuario u on u.usuario = a.usrvendedor ";
		$count .= " ";
		if ($letra != '|||'){
			$where = "WHERE ";}
		if ($par[0] != ''){
			$where .= "(a.data >= '".$par[0]."') ";}
		if ($par[1] != ''){
			if ($par[0] != ''){
				$where .= "AND (a.data <= '".$par[1]."') ";}
		}
		if ($par[2] != '0'){
			if (($par[0] != '') or ($par[1] != '')){
				$where .= "AND (a.usrvendedor = ".$par[2].") ";}
			else{
				$where .= "(a.usrvendedor = ".$par[2].") ";}
		}
		if ($par[3] != ''){
			if (($par[0] != '') or ($par[1] != '') or ($par[2] != '')){
				$where .= "AND (c.nome like '%".$par[3]."%') ";}
			else{
				$where .= "(c.nome like '%".$par[3]."%') ";}
		}		
				
		if ($total) {
			$sql = $count.$where."GROUP BY u.nomereduzido"; }
		else {
			$sql .= $where."ORDER BY a.data, a.hora"; }
	
                //echo strtoupper($sql);

		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoaOportunidade


	
	
			//---------------------------------------------------------------
			//---------------------------------------------------------------
			public function select_rdv_resumo($letra){

				$par = explode("|", $letra);
				$situacao = false;

				$sql  = "SELECT u.nomereduzido as tecnico, h.veiculo, (h.km*h.custokm) as totalkm ";
				$sql .= "FROM fin_cliente_acomp h ";
				$sql .= "left join amb_usuario u on ((u.usuario = h.usrvendedor) and (u.situacao ='A') and (u.tipo in ('V'))) ";

				$sql .= " ";
				if (array_sum($par) > 0){
					$sql .= "WHERE  ";}
					if ($par[0] != ''){
						$sql .= "(h.data >= '".str_replace("/", ".",$par[0])."') ";}
						if ($par[1] != ''){
							if ($par[0] != ''){
								$sql .= "AND (h.data <= '".str_replace("/", ".",$par[1])."') ";}
						}
						if ($par[2] != '' and ($par[2] != '0')){
							if (($par[0] != '') or ($par[1] != '')){
								$sql .= "AND (h.usrvendedor = ".$par[2].") ";}
								else{
									$sql .= "(h.usrvendedor = ".$par[2].") ";}
						}

						$sql .= "ORDER BY u.nomereduzido ";
						//ECHO $sql;

						$banco = new c_banco;
						$banco->exec_sql($sql);
						$banco->close_connection();
						return $banco->resultado;
			}// fim select_ordemServico_rdv

			//---------------------------------------------------------------
			//---------------------------------------------------------------
			public function select_rav_resumo($letra){

				$par = explode("|", $letra);
				$situacao = false;

				$sql  = "SELECT u.nomereduzido as tecnico, sum(p.total) as total  ";
				$sql .= "FROM fin_lancamento p ";
				$sql .= "inner join amb_usuario u on u.pessoa = p.fornecedor ";

				$sql .= " ";
				if (array_sum($par) > 0){
					$sql .= "WHERE (p.TIPODOCTO = 'K') AND";}
					//			$sql .= "WHERE  (h.localatendimento = 'O') AND ";}
					if ($par[0] != ''){
						$sql .= "(p.pagamento >= '".str_replace("/", ".",$par[0])."') ";}
						if ($par[1] != ''){
							if ($par[0] != ''){
								$sql .= "AND (p.pagamento <= '".str_replace("/", ".",$par[1])."') ";}
						}
						if ($par[2] != '' and ($par[2] != '0')){
							if (($par[0] != '') or ($par[1] != '')){
								$sql .= "AND (u.usuario = ".$par[2].") ";}
								else{
									$sql .= "(u.usuario = ".$par[2].") ";}
						}

						$sql .= "group by u.nomereduzido ORDER BY u.nomereduzido ";
						//ECHO $sql;

						$banco = new c_banco;
						$banco->exec_sql($sql);
						$banco->close_connection();
						return $banco->resultado;
			}// fim select_ordemServico_rdv
			
			//---------------------------------------------------------------
			//---------------------------------------------------------------
			public function select_visita_rdv($letra){

				$par = explode("|", $letra);
				$situacao = false;

				$sql  = "SELECT c.nomereduzido, c.cidade,  u.nomereduzido as vendedor, h.* ";
				$sql .= "FROM fin_cliente_acomp h ";
				$sql .= "inner join fin_cliente c on c.cliente = h.cliente ";
				$sql .= "left join amb_usuario u on u.usuario = h.usrvendedor ";
				$sql .= " ";
				if (array_sum($par) > 0){
					$sql .= "WHERE  ";}
					//			$sql .= "WHERE  (h.localatendimento = 'O') AND ";}
					if ($par[0] != ''){
						$sql .= "(h.data >= '".str_replace("/", ".",$par[0])."') ";}
						if ($par[1] != ''){
							if ($par[0] != ''){
								$sql .= "AND (h.data <= '".str_replace("/", ".",$par[1])."') ";}
						}
						if ($par[2] != '' and ($par[2] != '0')){
							if (($par[0] != '') or ($par[1] != '')){
								$sql .= "AND (h.usrvendedor = ".$par[2].") ";}
								else{
									$sql .= "(h.usrvendedor = ".$par[2].") ";}
						}

						$sql .= "ORDER BY h.data, h.hora ";	
						// ECHO $sql;

						$banco = new c_banco;
						$banco->exec_sql($sql);
						$banco->close_connection();
						return $banco->resultado;
			}// fim select_ordemServico_rdv


			//---------------------------------------------------------------
			//---------------------------------------------------------------
			public function select_visita_rav($letra){

				$par = explode("|", $letra);
				$situacao = false;

				$sql  = "select p.* from amb_usuario u ";
				$sql .= "inner join fin_lancamento p on u.pessoa = p.fornecedor ";
				$sql .= " ";
				if (array_sum($par) > 0){
					$sql .= "WHERE  ";}
					if ($par[0] != ''){
						$sql .= "(tipodocto = 'K') and (p.pagamento >= '".str_replace("/", ".",$par[0])."') ";}
						if ($par[1] != ''){
							if ($par[0] != ''){
								$sql .= "AND (p.pagamento <= '".str_replace("/", ".",$par[1])."') ";}
						}
					if ($par[2] != '' and ($par[2] != '0')){
							if (($par[0] != '') or ($par[1] != '')){
								$sql .= "AND (u.usuario = ".$par[2].") ";}
								else{
									$sql .= "(u.usuario = ".$par[2].") ";}
						}

						$sql .= "ORDER BY p.pagamento ";	
						// ECHO $sql;

						$banco = new c_banco;
						$banco->exec_sql($sql);
						$banco->close_connection();
						return $banco->resultado;
			}// fim select_ordemServico_rav

				
	
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_pessoaAcomp(){
		$sql  = "SELECT * ";
		$sql .= "FROM fin_cliente_acomp ";
		$sql .= "WHERE (cliente = ".$this->getId().") and (data = '".$this->getDataContato('B')."') and (hora = '".$this->getHoraContato('B')."')";
		//ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pedidoVendaComp

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_pessoaAcomp_geral(){
		$sql  = "SELECT c.*, a.descricao, u.nomereduzido ";
		$sql .= "FROM fin_cliente_acomp c ";
		$sql .= "left join amb_usuario u on u.usuario = c.usrvendedor ";
		$sql .= "left join fat_atividade_acomp a on a.atividade = c.atividade ";
		$sql .= "WHERE (c.cliente = ".$this->getId().") ";
		$sql .= "ORDER BY c.data, c.hora desc ";
		//ECHO strtoupper($sql)."<BR>";
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoaAcomp_geral

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function incluiPessoaAcomp(){

		$proximoContato = $this->getProximoContato('B');
		
		$sql  = "INSERT INTO fin_cliente_acomp (cliente, data, hora, atividade, resultado, usrvendedor, ligardia, ligardiahora, veiculo, origem, destino, km) ";
		$sql .= "VALUES (".$this->getId().", '"
		.$this->getDataContato('B')."', '"
		.$this->getHoraContato('B')."', '"
		.$this->getAcao()."', '"
		.$this->getResultContato()."', "
		.$this->getVendedorAcomp().", ";
		if ($proximoContato==null) { $sql .= "null, '"; } else {$sql .="'".$proximoContato."', '";};
		$sql .= $this->getProximoContatoHora()."', '".$this->getVeiculo()."', '"
		.$this->getOrigem()."', '"
		.$this->getDestino()."', "
		.$this->getKM().") ";
		//echo strtoupper($sql);
		$banco = new c_banco;
                    $res_pessoaAcomp =  $banco->exec_sql($sql);
                    
		$banco->close_connection();

		if($res_pessoaAcomp > 0){
			return '';
		}
		else{
			return 'Os dados de Pessoa Acompanhamento '.$this->getId().' n�o foi cadastrado!';
		}
	} // fim incluiPessoaAcomp
	
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function alteraPessoaAcomp(){

		$proximoContato = $this->getProximoContato('B');

		$sql  = "UPDATE fin_cliente_acomp ";
		$sql .= "SET atividade = '".$this->getAcao()."', " ;
		$sql .= "resultado = '".$this->getResultContato()."', " ;
		$sql .= "usrvendedor = ".$this->getVendedorAcomp().", " ;
		$sql .= "ligardia = ";
		if ($proximoContato==null) { $sql .= "null, "; } else {$sql .="'".$proximoContato."', ";};
		$sql .= "ligardiahora = '".$this->getProximoContatoHora()."', " ;
		$sql .= "veiculo = '".$this->getVeiculo()."', " ;
		$sql .= "origem = '".$this->getOrigem()."', " ;
		$sql .= "destino = '".$this->getDestino()."', " ;
		$sql .= "km = ".$this->getKM()." " ;
		$sql .= "WHERE (cliente = ".$this->getId().") and (data = '".$this->getDataContato('B')."') and (hora = '".$this->getHoraContato('B')."')";
		//ECHO $sql;
		$banco = new c_banco;
		$res_pessoaAcomp =  $banco->exec_sql($sql);
		//	$res_pedidoVenda =  $banco->m_cmdTuples ;
		$banco->close_connection();

		if($res_pessoaAcomp > 0){
			return '';
		}
		else{
			return 'Os dados de Pessoa Acompanhamento '.$this->getId().' n&atilde;o foi alterado!';
		}

	}  // fim alteraPessoaAcomp

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function excluiPessoaAcomp(){

		$sql  = "DELETE FROM fin_cliente_acomp ";
		$sql .= "WHERE (cliente = ".$this->getId().") and (data = '".$this->getDataContato('B')."') and (hora = '".$this->getHoraContato('B')."')";
		//echo $sql;
		$banco = new c_banco;
		$res_pessoaAcomp =  $banco->exec_sql($sql);
		$banco->close_connection();

		if($res_pessoaAcomp > 0){
			return '';
		}
		else{
			return 'Os dados de Pessoa Acompanhamento '.$this->getId().' n&atilde;o foi excluido!';
		}
	}  // fim excluiPessoaAcomp


	//---------------------------------------------------------------
	// procedures Pessoa Oportunidade
	//---------------------------------------------------------------

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_pessoaOportunidade(){
		$sql  = "SELECT * ";
		$sql .= "FROM fin_cliente_Oportunidade ";
		$sql .= "WHERE (cliente = ".$this->getId().") and (data = '".$this->getDataOportunidade('B')."') and (hora = '".$this->getHoraOportunidade('B')."')";
		//ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pedidoVendOportunidade

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function select_pessoaOportunidade_geral(){
		$sql  = "SELECT c.* ";
		$sql .= "FROM fin_cliente_Oportunidade c ";
		$sql .= "WHERE (c.cliente = ".$this->getId().") ";
		$sql .= "ORDER BY c.data, c.hora desc ";
		//ECHO $sql;
		$banco = new c_banco;
		$banco->exec_sql($sql);
		$banco->close_connection();
		return $banco->resultado;
	} //fim select_pessoaOportunidade_geral

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function incluiPessoaOportunidade(){

		$sql  = "INSERT INTO fin_cliente_oportunidade (cliente, data, hora, oportunidade, resumo, status, caminhoproposta, valor, vendedor) ";
		$sql .= "VALUES (".$this->getId().", '"
		.$this->getDataOportunidade('B')."', '"
		.$this->getHoraOportunidade('B')."', '"
		.$this->getOportunidade()."', '"
		.$this->getResumo()."', '"
		.$this->getStatus()."', '"
		.$this->getNomeProposta()."', "
		.$this->getValorOportunidade('B').", "
		.$this->getVendedorOportunidade().") ";
		//echo strtoupper($sql);
		$banco = new c_banco;
		$res_pessoaOportunidade =  $banco->exec_sql($sql);
		$banco->close_connection();

		if($res_pessoaOportunidade > 0){
			return '';
		}
		else{
			return 'Os dados de Pessoa Oportunidade '.$this->getId().' n�o foi cadastrado!';
		}
	} // fim incluiPessoaOportunidade
	
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function alteraPessoaOportunidade(){


		$sql  = "UPDATE fin_cliente_oportunidade ";
		$sql .= "SET oportunidade = '".$this->getOportunidade()."', " ;
		$sql .= "resumo = '".$this->getResumo()."', " ;
		$sql .= "status = '".$this->getStatus()."', " ;
		$sql .= "caminhoproposta = '".$this->getNomeProposta()."', " ;
		$sql .= "valor = ".$this->getValorOportunidade('B').", " ;
		$sql .= "vendedor = ".$this->getVendedorOportunidade()." " ;
		$sql .= "WHERE (cliente = ".$this->getId().") and (data = '".$this->getDataOportunidade('B')."') and (hora = '".$this->getHoraOportunidade('B')."')";
		// ECHO $sql;
		$banco = new c_banco;
		$res_pessoaOportunidade =  $banco->exec_sql($sql);
		//	$res_pedidoVenda =  $banco->m_cmdTuples ;
		$banco->close_connection();

		if($res_pessoaOportunidade > 0){
			return '';
		}
		else{
			return 'Os dados de Pessoa Oportunidade '.$this->getId().' n�o foi alterado!';
		}

	}  // fim alteraPessoaOportunidade

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function excluiPessoaOportunidade(){

		$sql  = "DELETE FROM fin_cliente_Oportunidade ";
		$sql .= "WHERE (cliente = ".$this->getId().") and (data = '".$this->getDataOportunidade('B')."') and (hora = '".$this->getHoraOportunidade('B')."')";
		//echo $sql;
		$banco = new c_banco;
		$res_pessoaOportunidade =  $banco->exec_sql($sql);
		$banco->close_connection();

		if($res_pessoaOportunidade > 0){
			return '';
		}
		else{
			return 'Os dados de Pessoa Oportunidade '.$this->getId().' n�o foi excluido!';
		}
	}  // fim excluiPessoaOportunidade

	
	
}	//	END OF THE CLASS
?>
