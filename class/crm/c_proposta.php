<?php

/**
 * @package   astec
 * @name      c_proposta
 * @version   2.0.00
 * @copyright 2013-2016 &copy; 
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      20/01/2016
 */
$dir = dirname(__FILE__);

include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");

Class c_proposta extends c_user {

    /**
      TABLE FAT_PROPOSTA
     */
    private $id             = NULL;        // INT(11) NOT NULL
    private $versao         = NULL;        // INT(11) NOT NULL
    private $apresentacao   = NULL;        // BLOB
    private $objetivo       = NULL;        // BLOB
    private $item           = NULL;        // BLOB
    private $condPgto       = NULL;        // BLOB
    private $garantia       = NULL;        // BLOB
    private $imposto        = NULL;        // BLOB
    private $prazoEntrega   = NULL;        // BLOB
    private $validade       = NULL;        // BLOB
    private $aceite         = NULL;        // BLOB
    private $userResp       = NULL;        // INT(11)
    private $situacao       = NULL;        // CHAR(1)
    private $data           = NULL;        // DATE
    private $obs            = NULL;        // BLOB
    



//---------------------------------------------------------------
//// set e gets ***************
//---------------------------------------------------------------
    function getId() {
        return $this->id;
    }

    function getVersao() {
        return $this->versao;
    }

    function getApresentacao() {
        return $this->apresentacao;
    }

    function getObjetivo() {
        return $this->objetivo;
    }

    function getItem() {
        return $this->item;
    }

    function getCondPgto() {
        return $this->condPgto;
    }

    function getGarantia() {
        return $this->garantia;
    }

    function getImposto() {
        return $this->imposto;
    }

    function getPrazoEntrega() {
        return $this->prazoEntrega;
    }

    function getValidade() {
        return $this->validade;
    }

    function getAceite() {
        return $this->aceite;
    }

    function getUserResp() {
        return $this->userResp;
    }

    function getSituacao() {
        return $this->situacao;
    }

    function getData($format=NULL) {
        switch ($format) {
            case 'F':
                return date('d/m/Y', strtotime($this->data));
                break;
            case 'B':
                return c_date::convertDateBdSh($this->data, $this->m_banco);
                break;
            default:
                return $this->data;
        }
    }
    
    function getObs() {
        return $this->obs;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setVersao($versao) {
        $this->versao = $versao;
    }

    function setApresentacao($apresentacao) {
        $this->apresentacao = $apresentacao;
    }

    function setObjetivo($objetivo) {
        $this->objetivo = $objetivo;
    }

    function setItem($item) {
        $this->item = $item;
    }

    function setCondPgto($condPgto) {
        $this->condPgto = $condPgto;
    }

    function setGarantia($garantia) {
        $this->garantia = $garantia;
    }

    function setImposto($imposto) {
        $this->imposto = $imposto;
    }

    function setPrazoEntrega($prazoEntrega) {
        $this->prazoEntrega = $prazoEntrega;
    }

    function setValidade($validade) {
        $this->validade = $validade;
    }

    function setAceite($aceite) {
        $this->aceite = $aceite;
    }

    function setUserResp($userResp) {
        $this->userResp = $userResp;
    }

    function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setObs($obs) {
        $this->obs = trim($obs);
    }

// FIM SETS E GETS ***************

    /**
     * @name     setProposta
     * @param    INT GetId
     * @return   set todas as variaveis de acordo com o select
     */
    public function setProposta() {
        $lanc = $this->select_proposta();
        $this->setId($lanc[0]['ID']);
        $this->setVersao($lanc[0]['VERSAO']);
        $this->setApresentacao($lanc[0]['APRESENTACAO']);
        $this->setObjetivo($lanc[0]['OBJETIVO']);
        $this->setItem($lanc[0]['ITEM']);
        $this->setCondPgto($lanc[0]['CONDPGTO']);
        $this->setGarantia($lanc[0]['GARANTIA']);
        $this->setImposto($lanc[0]['IMPOSTO']);
        $this->setPrazoEntrega($lanc[0]['PRAZOENTREGA']);
        $this->setValidade($lanc[0]['VALIDADE']);
        $this->setAceite($lanc[0]['ACEITE']);
        $this->setUserResp($lanc[0]['USERRESP']);
        $this->setSituacao($lanc[0]['SITUACAO']);
        $this->setData($lanc[0]['DATA']);
        $this->setObs($lanc[0]['OBS']);
    } // fim setProposta

    /**
     * SQL usado para buscar um registro especifico
     * @name select_proposta_id
     * @param INT ID Id do pedido
     * @param INT VERSAO numero do versionamento
     * @return ARRAY conteudo do select
     */
    public function select_proposta() {
        $sql = "select * ";
        $sql .= "from fat_proposta ";
        $sql .= "where (id=".  $this->getId().") ";
        $sql .= "and (versao=".  $this->getVersao().") ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_proposta

    /**
     * SQL usado para buscar um registro especifico
     * @name select_proposta_id
     * @param INT ID Id do pedido
     * @param INT VERSAO numero do versionamento
     * @return ARRAY conteudo do select
     */
    public function select_proposta_id() {
        $sql = "select p.*, o.cliente, c.nome, C.TITULOEND, C.TIPOEND, C.ENDERECO, C.NUMERO, C.COMPLEMENTO, C.BAIRRO, C.CIDADE, C.UF ";
        $sql .= "from fat_proposta p ";
        $sql .= "inner join fat_pedido o on (o.id=p.id) ";
        $sql .= "inner join fin_cliente c on (c.cliente=o.cliente) ";
        $sql .= "where (p.id=".  $this->getId().") ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_proposta_id
    
    /**
     * @name     select_ultimo_versao
     * @param    VAZIO
     * @return   SELECT retorna o maior numero do numero do lote
     */
    public function select_ultimo_versao() {
        $sql  = "SELECT max(versao) as MAX ";
        $sql .= "FROM fat_proposta ";
        $sql .= "WHERE (id= '".  $this->getId()."') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // fim select_ultimo_versao


    /**
     * @name     incluiProposta
     * @param    string gets de todos os objetos private da classe
     * @return   INSERT retorna ID caso a insercao ocorra com sucesso
     */
    public function incluiProposta() {
        $banco = new c_banco;
        if ($banco->gerenciadorDB == 'interbase') {
            $banco = new c_banco;
            $this->setId($banco->geraID("EST_GEN_ID_PROPOSTA"));
            $sql = "INSERT INTO FAT_PROPOSTA (ID,";
        } else {
            $sql = "INSERT INTO FAT_PROPOSTA (";
        }
        $sql .= "ID, 
                VERSAO, 
                APRESENTACAO, 
                OBJETIVO, 
                ITEM, 
                CONDPGTO, 
                GARANTIA, 
                IMPOSTOS, 
                PRAZOENTREGA, 
                VALIDADE, 
                ACEITE, 
                USERRESP, 
                SITUACAO, 
                DATA,
                OBS) ";
        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", ";
        } else {
            $sql .= "VALUES (";
        }
        $sql .= $this->getId().", '".$this->getVersao()."', '".$this->getApresentacao()."', '".$this->getObjetivo()."', '";
        $sql .= $this->getItem()."', '".  $this->getCondPgto()."', '";
        $sql .= $this->getGarantia()."', '".  $this->getImposto()."', '".  $this->getPrazoEntrega()."', '";
        $sql .= $this->getValidade()."', '".  $this->getAceite()."', '";
        $sql .= $this->getUserResp()."', '".  $this->getSituacao()."', '".  $this->getData()."', '".$this->getObs()."');";
      //  echo strtoupper($sql) . "<BR>";
        $resProduto =  $banco->exec_sql($sql);
        $lastReg = mysqli_insert_id($banco->id_connection);
	$banco->close_connection();
        if ($resProduto > 0) {
            return $lastReg;
        } else {
            return 'Os dados do Item ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }//if
    }// fim incluiProposta

    /**
     * @name     alteraProposta
     * @param    string gets de todos objetos private da classe
     * @return   UPDATE retorna VAZIO caso o update ocorra com sucesso
     */
    public function alteraProposta() {
        $sql = "UPDATE fat_proposta ";
        $sql .= "SET ";
        $sql .= "apresentacao = '" . $this->getApresentacao() . "', ";
        $sql .= "objetivo = '" . $this->getObjetivo() . "', ";
        $sql .= "item = '" . $this->getItem() . "', ";
        $sql .= "condpgto = '" . $this->getCondPgto() . "', ";
        $sql .= "garantia = '" . $this->getGarantia() . "', ";
        $sql .= "impostos = '" . $this->getImposto() . "', ";
        $sql .= "prazoentrega = '" . $this->getPrazoEntrega() . "', ";
        $sql .= "validade = '" . $this->getValidade() . "', ";
        $sql .= "aceite = '" . $this->getAceite() . "', ";
        $sql .= "userresp = '" . $this->getUserResp() . "', ";
        $sql .= "situacao = '" . $this->getSituacao() . "', ";
        $sql .= "data = '" . $this->getData('B') . "', ";
        $sql .= "obs = '" . $this->getObs() . "' ";
        $sql .= "WHERE (id = '" . $this->getId() . "') ";
        $sql .= "and (versao = '".$this->getVersao()."')";
       // echo strtoupper($sql) . "<BR>";
        $banco = new c_banco;
        $resProdutoUser = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProdutoUser > 0) {
            return '';
        } else {
            return 'Os dados do produto ' . $this->getId() . ' n&atilde;o foi alterado!';
        }//if
    } // fim alteraProposta

}
//	END OF THE CLASS
?>
