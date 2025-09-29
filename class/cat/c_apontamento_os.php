<?php

/**
 * @package   astec
 * @name      c_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      29/04/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_produto.php");


Class c_apontamento_os extends c_user {

    /**
     * TABLE NAME CAT_ATENDIMENTO
     */
    private $id = NULL; 
    private $atendimento_id = NULL;  // ATENDIMENTO
    private $cliente = NULL; 
    private $clienteNome = NULL; 
    private $contato 			= NULL;
    private $obs 				= NULL;
    private $obsServicos 		= NULL; 
    private $idServico = NULL;
    private $idApontamento = NULL; 
    private $catSituacao = NULL;
    
    private $idUser = NULL;  
    private $dataInicio = NULL; 
    private $dataFim = NULL;
    private $totalHoras 	= NULL;
    private $descricao    = NULL;


    //construtor
    function __construct(){
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);
    }

    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }

    function setAtendimentoId($atendimento_id) { $this->atendimento_id = $atendimento_id; }
    function getAtendimentoId() { return $this->atendimento_id; }

    function setIdApontamento($idApontamento) { $this->idApontamento = $idApontamento; }
   function getIdApontamento() { return $this->idApontamento; }


    function setCliente($cliente) { $this->cliente = $cliente; }
    function getCliente() { return $this->cliente; }

    function setClienteNome() {
        $pessoa = new c_conta();
        $pessoa->setId($this->getCliente());
        $reg_nome = $pessoa->select_conta();
        $this->clienteNome = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->ufPessoa = $reg_nome[0]['UF'];
    }    
        
    function getClienteNome() { return $this->clienteNome; }  

    function setContato($contato) { $this->contato = $contato; }
    function getContato() { return $this->contato; }

    function setContatoNome() {
        $pessoa = new c_conta();
        $pessoa->setId($this->getCliente());
        $reg_nome = $pessoa->select_conta();
        $this->contatoNome = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->ufPessoa = $reg_nome[0]['UF'];
    }    
    function getContatoNome() { return $this->contatoNome; }   
    
    function setNumAtendimento($numAtendimento) { $this->numAtendimento = $numAtendimento; }
    function getNumAtendimento() { 
        return isset($this->numAtendimento) ? $this->numAtendimento : 'NULL';  }

    

 

    function setObs($obs) { $this->obs = $obs; }
    function getObs() { return $this->obs; }

    function setObsServicos($obsServicos) { $this->obsServicos = $obsServicos; }
    function getObsServicos() { return $this->obsServicos; }

    function setSituacao($catSituacao) { $this->catSituacao = $catSituacao; }
    function getSituacao() { return $this->catSituacao; }

   
   //===============SERVICO ==========================
   function setIdServico($idServico) { $this->idServico = $idServico; }
   function getIdServico() { return $this->idServico; }

  
   function setIdUser($idUser) { $this->idUser = $idUser; }
   function getIdUser() { return $this->idUser; }

   public function setData($data) { $this->data=$data; }
    public function getData($format = null) { 
            if ($this->data!=''){
                    $this->data = strtr($this->data, "/","-");
                    switch ($format) {
                            case 'F':
                                    return date('d/m/Y', strtotime($this->data));
                                    break;
                            case 'B':
                                    return c_date::convertDateBd($this->data, $this->m_banco);
                                    break;
                            default:
                                    return $this->data;
                    }
            }
            else
                return null;
    }

   function setDataFim($dataFim) { $this->dataFim = $dataFim; }
   function getDataFim() { return $this->dataFim; }

   function setDataInicio($dataInicio) { $this->dataInicio = $dataInicio; }
   function getDataInicio() { return $this->dataInicio; }
    

   function setTotalHoras($totalHoras) { $this->totalHoras = $totalHoras; }
   function getTotalHoras() { return $this->totalHoras; }

   function setDescricao($descricao) { $this->descricao = $descricao; }
   function getDescricao() { return $this->descricao; }

   

   //===============FIM-SERVICO=========================

    /**
     * Funcao para setar todos os objetos da classe
     * @name setPedidoVenda
     * @param INT GetId chave primaria da table pedidos
     */
    public function buscaApontamento() {

        $apontamento = $this->select_apontamento_id();
        $this->setId($apontamento[0]['ID']);
        $this->setCliente($apontamento[0]['CLIENTE']);
        $this->setContato($apontamento[0]['CONTATO']);
        $this->setClienteNome($apontamento[0]['NOME']);
        $this->setAtendimentoId($apontamento[0]['NUMATENDIMENTO']);
        
        $this->setObs($apontamento[0]['OBS']);
        $this->setObsServicos($apontamento[0]['OBSSERVICO']);
       
    }

    /**
     * <b> Rotina que busca dados de um apontamento pelo id do atendimento</b>
     * @name select_apontamento
     * @return ARRAY Apontamento.
     * @author    Tony
     * @date      17/03/2021
     */

    public function select_apontamento() {

        $sql = "SELECT * ";
        $sql .= "FROM CAT_AT_TAREFAS ";
        $sql .= "WHERE (ATENDIMENTO_ID = " . $this->getAtendimentoId() . ") ";
        $sql .= "ORDER BY ID;";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }  

    /**
     * <b> Rotina que busca dados de um apontamento pelo id </b>
     * @name select_apontamento_id
     * @return ARRAY Apontamento.
     * @author    Tony
     * @date      17/03/2021
     */
    public function select_apontamento_id() {

        $sql = "SELECT * ";
        $sql .= "FROM CAT_AT_TAREFAS ";
        $sql .= "WHERE (ID = " . $this->getId() . ") ";
        $sql .= "ORDER BY ID;";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }  

    /**
     * <b> Rotina que busca dados de um apontamento pelo id do servico</b>
     * @name select_apontamento_servico
     * @return ARRAY Apontamento.
     * @author    Tony
     * @date      17/03/2021
     */
    public function select_apontamento_servico() {

        $sql = "SELECT * ";
        $sql .= "FROM CAT_AT_TAREFAS ";
        $sql .= "WHERE (SERVICO_ID = " . $this->idServicos . ") ";
        $sql .= "ORDER BY ID;";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * <b> Rotina que busca dados de um atendimento pelo id do atendimento</b>
     * @name select_atendimento_id
     * @return ARRAY Atendimento.
     * @author    Tony
     * @date      17/03/2021
     */
    public function select_atendimento_id() {

        $sql = "SELECT * ";
        $sql .= "FROM CAT_ATENDIMENTO ";
        $sql .= "WHERE (ID = " . $this->getAtendimentoId() . ") ";
        $sql .= "ORDER BY ID;";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    
    /**
     * <b> Rotina que busca dados de um produto pelo id do atendimento</b>
     * @name select_pecas_atendimento
     * @return ARRAY de peças.
     * @author    Tony
     * @date      17/03/2021
     */
    public function select_pecas_atendimento() {
        $sql = "SELECT T.CODFABRICANTE, P.* FROM CAT_AT_PECAS P ";
        $sql .= "INNER JOIN EST_PRODUTO T ON  T.CODIGO=P.CODPRODUTO ";
        $sql .= "WHERE (CAT_ATENDIMENTO_ID = '" . $this->getAtendimentoId() . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    /**
     * <b> Rotina que busca dados de um serviço pelo id do atendimento</b>
     * @name select_servicos_atendimento
     * @return ARRAY de serviço.
     * @author    Tony
     * @date      17/03/2021
     */
    public function select_servicos_atendimento() {
        $sql = "SELECT * FROM CAT_AT_SERVICOS ";
        $sql .= "WHERE (CAT_ATENDIMENTO_ID = '" . $this->getAtendimentoId() . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * <b> Rotina que inclui um apontamento na tabela CAT_AT_TAREFAS</b>
     * @name select_apontamento
     * @return ARRAY Apontamento.
     * @author    Tony
     * @date      17/03/2021
     */
    public function incluiApontamento($conn=null) {

        $sql = "INSERT INTO CAT_AT_TAREFAS (";
        $sql .= "ATENDIMENTO_ID, SERVICO_ID, USER_ID, DATA_INICIO, DATA_FIM, TOTALHORAS,  DESCRICAO,  CREATED_USER, CREATED_AT )";
        $dataAtual = c_date::convertDateTxt($this->getData('F')); 
        $dataIni = $dataAtual ." ". $this->getDataInicio();
        $dataFim = $dataAtual ." ". $this->getDataFim();

        $sql .= "VALUES ('";
        $sql .=   $this->getAtendimentoId() . "','"
                . $this->getIdServico() . "', '"
                . $this->getIdUser() . "', '"
                . $dataIni ."', '"
                . $dataFim . "', '"
                . $this->getTotalHoras() . "', '"
                . $this->getDescricao() . "', '";
        $sql .= $this->m_userid."','".date("Y-m-d H:i:s"). "' );";
        //echo strtoupper($sql) . "<BR>";
        $banco = new c_banco;
        $result = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();

        if ($result > 0) {
            return $lastReg;
        } else {
            return 'Os dados do Apontamento ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * <b> Rotina que atualiza os dados do apontamento na tabela CAT_AT_TAREFAS</b>
     * @name alteraApontamento
     * @return NULL quando ok ou msg erro
     * @author    Tony
     * @date      17/03/2021
     */
    public function alteraApontamento($conn=null) {

        $dataAtual = c_date::convertDateTxt($this->getData('F')); 
        $dataIni = $dataAtual ." ". $this->getDataInicio();
        $dataFim = $dataAtual ." ". $this->getDataFim();

        $sql = "UPDATE CAT_AT_TAREFAS SET ";
        $sql .= "ATENDIMENTO_ID = '" . $this->getAtendimentoId() . "', ";
        $sql .= "SERVICO_ID     = '" . $this->getIdServico() . "', ";
        $sql .= "USER_ID        = '" . $this->getIdUser() . "', ";   
        $sql .= "DATA_INICIO    = '" . $dataIni . "', "; 
        $sql .= "DATA_FIM       = '" . $dataFim ."', ";  
        $sql .= "TOTALHORAS     = '" . $this->getTotalHoras() . "', ";  
        $sql .= "DESCRICAO      = '" . $this->getDescricao() . "', ";  
        $sql .= "UPDATED_USER   = '" . $this->m_userid. "', ";  
        $sql .= "UPDATED_AT     = '" . date("Y-m-d H:i:s") . "' "; 
        $sql .= "WHERE ID       = " . $this->getIdApontamento() . ";";

        $banco = new c_banco;
        $result = $banco->exec_sql($sql, $conn);
        $banco->close_connection();

        if ($result > 0) {
            return '';
        } else {
            return 'Apontamento ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    /**
     * <b> Rotina que atualiza a Quantidade Utilizada e o total Utilizado do produto tabela CAT_AT_PECAS</b>
     * @name atualizaQtdeUtilizada
     * @return NULL quando ok ou msg erro
     * @author    Tony
     * @date      17/03/2021
     */
    public function atualizaQtdeUtilizada($conn=null) {

        $sql = "UPDATE CAT_AT_PECAS SET ";
        $sql .= "QUANTIDADEUTILIZADA = '" . $this->qtdeUtilizada . "' ";
        if($this->totalUtilizado != ''){
            $sql .= ", TOTALUTILIZADO = '" . $this->totalUtilizado . "' ";
        }
        $sql .= "WHERE ID       = " . $this->idPecas . " AND CODPRODUTO = ".$this->codProduto.";";
        $banco = new c_banco;
        $result = $banco->exec_sql($sql, $conn);
        $banco->close_connection();

        if ($result > 0) {
            return '';
        } else {
            return 'Registro ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    
    /**
     * Funcao para excluir apontamento
     * @param conn id da conexão com o banco no caso de trasaction
     * @name excluiAtendimento
     * @return NULL quando ok ou msg erro
     */
    public function excluiApontamento($conn=null) {
        $sql = "DELETE FROM ";
        $sql .= "CAT_AT_TAREFAS ";
        $sql .= "WHERE (id = '" . $this->getIdApontamento() . "')";
        
        $banco = new c_banco;
        $result = $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        
        if ($result > 0) {
            return '';
        } else {
            return 'Apontamento ' . $this->getId() . ' n&atilde;o foi excluido!';
        }
    
    }
    
    
   
   
    

    




}

//=======================



//	END OF THE CLASS
?>