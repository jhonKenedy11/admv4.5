<?php

/**
 * @package   astecv3
 * @name      c_responsavel_tecnico
 * @version   3.0.00
 * @copyright 2024
 * @link      http://www.admservice.com.br/
 * @author    Sistema ADM v4.5
 * @date      2024
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");

//Class C_RESPONSAVEL_TECNICO
Class c_responsavel_tecnico extends c_user {

    /** CLASSE C_RESPONSAVEL_TECNICO - OBJETOS TABLE AMB_RESPONSAVEL_TECNICO   */
    private $id                = NULL; // INT(11)
    private $nome              = NULL; // VARCHAR(100)
    private $cpf               = NULL; // VARCHAR(14)
    private $crea              = NULL; // VARCHAR(20)
    private $telefone          = NULL; // VARCHAR(20)
    private $email             = NULL; // VARCHAR(100)
    private $rua               = NULL; // VARCHAR(200)
    private $numero            = NULL; // VARCHAR(10)
    private $complemento       = NULL; // VARCHAR(100)
    private $cidade            = NULL; // VARCHAR(50)
    private $estado            = NULL; // VARCHAR(2)
    private $cep               = NULL; // VARCHAR(10)
    private $situacao          = NULL; // CHAR(1)
    private $data_cadastro     = NULL; // TIMESTAMP
    private $data_alteracao    = NULL; // TIMESTAMP
    private $usuario_cadastro  = NULL; // INT(11)
    private $usuario_alteracao = NULL; // INT(11)

// ###############################################################
// #################### INICIO GETS E SETS #######################    

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function setCrea($crea) {
        $this->crea = $crea;
    }

    public function getCrea() {
        return $this->crea;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setRua($rua) {
        $this->rua = $rua;
    }

    public function getRua() {
        return $this->rua;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    public function getComplemento() {
        return $this->complemento;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function getCep() {
        return $this->cep;
    }


    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function setDataCadastro($data_cadastro) {
        $this->data_cadastro = $data_cadastro;
    }

    public function getDataCadastro() {
        return $this->data_cadastro;
    }

    public function setDataAlteracao($data_alteracao) {
        $this->data_alteracao = $data_alteracao;
    }

    public function getDataAlteracao() {
        return $this->data_alteracao;
    }

    public function setCreatedBy($m_userid) {
        $this->m_userid = $m_userid;
    }

    public function getCreatedBy() {
        return $this->m_userid;
    }

        public function setUpdatedBy($m_userid) {
        $this->m_userid = $m_userid;
    }

    public function getUpdatedBy() {
        return $this->m_userid;
    }

// ####################### FIM GETS E SETS #######################    
// ###############################################################

    /**
     * Verifica se existe responsável técnico com o mesmo CPF
     * @name existeCpf
     * @return boolean true caso retorne valor
     */
    public function existeCpf() {
        $sql = "SELECT * ";
        $sql .= "FROM amb_responsavel_tecnico ";
        $sql .= "WHERE (cpf = '" . $this->getCpf() . "')";
        if ($this->getId() != NULL) {
            $sql .= " AND (id != '" . $this->getId() . "')";
        }
        
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }//fim existeCpf

    /**
     * Verifica se existe responsável técnico com o mesmo CREA
     * @name existeCrea
     * @return boolean true caso retorne valor
     */
    public function existeCrea() {
        $sql = "SELECT * ";
        $sql .= "FROM amb_responsavel_tecnico ";
        $sql .= "WHERE (crea = '" . $this->getCrea() . "')";
        if ($this->getId() != NULL) {
            $sql .= " AND (id != '" . $this->getId() . "')";
        }
        
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }//fim existeCrea

    /**
     * Consulta na tabela trazendo todos as colunas de acordo com o ID
     * @name select_responsavel_id
     * @return ARRAY com todos os campos do banco
     */
    public function select_responsavel_id() {
        $sql = "SELECT * ";
        $sql .= "FROM amb_responsavel_tecnico ";
        $sql .= "WHERE (id = '" . $this->getId() . "') ";
        
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }//fim select_responsavel_id

    /**
     * Consulta todos os registros da tabela
     * @name select_responsavel_geral
     * @return ARRAY com todos os campos do banco
     */
    public function select_responsavel_geral() {
        $sql = "SELECT * ";
        $sql .= "FROM amb_responsavel_tecnico ";
        $sql .= "ORDER BY situacao DESC, nome";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }//fim select_responsavel_geral

    /**
     * Inclusao no banco de dados
     * @name incluiResponsavel
     * @return string Retorna vazio se a operacao for bem sucedida
     */
    public function incluiResponsavel() {
        // Verifica se CPF já existe
        if ($this->getCpf() != '' && $this->existeCpf()) {
            return 'CPF já cadastrado para outro responsável técnico!';
        }
        
        // Verifica se CREA já existe
        if ($this->getCrea() != '' && $this->existeCrea()) {
            return 'CREA já cadastrado para outro responsável técnico!';
        }

        $sql = "INSERT INTO amb_responsavel_tecnico (";
        $sql .= "nome, cpf, crea, telefone, email, rua, numero, complemento, cidade, estado, cep, situacao, created_by) ";
        $sql .= "VALUES ('" . $this->getNome() . "', '";
        $sql .= $this->getCpf() . "', '" . $this->getCrea() . "', '";
        $sql .= $this->getTelefone() . "', '" . $this->getEmail() . "', '";
        $sql .= $this->getRua() . "', '" . $this->getNumero() . "', '";
        $sql .= $this->getComplemento() . "', '" . $this->getCidade() . "', '";
        $sql .= $this->getEstado() . "', '" . $this->getCep() . "', '";
        $sql .= $this->getSituacao() . "', '" . $this->getCreatedBy() . "')";
        
        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            return '';
        } else {
            return 'Os dados do responsável técnico ' . $this->getNome() . ' não foram cadastrados!';
        }//if
    }// fim incluiResponsavel

    /**
     * Alteracao no Banco de dados
     * @name alteraResponsavel
     * @return string Retorna vazio se a operacao for bem sucedida
     */
    public function alteraResponsavel() {

        $sql = "UPDATE amb_responsavel_tecnico ";
        $sql .= "SET nome = '" . $this->getNome() . "', ";
        $sql .= "cpf = '" . $this->getCpf() . "', ";
        $sql .= "crea = '" . $this->getCrea() . "', ";
        $sql .= "telefone = '" . $this->getTelefone() . "', ";
        $sql .= "email = '" . $this->getEmail() . "', ";
        $sql .= "rua = '" . $this->getRua() . "', ";
        $sql .= "numero = '" . $this->getNumero() . "', ";
        $sql .= "complemento = '" . $this->getComplemento() . "', ";
        $sql .= "cidade = '" . $this->getCidade() . "', ";
        $sql .= "estado = '" . $this->getEstado() . "', ";
        $sql .= "cep = '" . $this->getCep() . "', ";
        $sql .= "situacao = '" . $this->getSituacao() . "', ";
        $sql .= "updated_by = '" . $this->getUpdatedBy() . "', ";
        $sql .= "data_alteracao = NOW() ";
        $sql .= "WHERE id = '" . $this->getId() . "'";
        
        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            return '';
        } else {
            return 'Os dados do responsável técnico ' . $this->getNome() . ' não foram alterados!';
        }//if
    }// fim alteraResponsavel

    /**
     * Exclusao no banco de dados
     * @name excluiResponsavel
     * @return string Retorna vazio se a operacao for bem sucedida
     */
    public function excluiResponsavel() {
        $sql = "DELETE FROM amb_responsavel_tecnico ";
        $sql .= "WHERE id = '" . $this->getId() . "'";
        
        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            return '';
        } else {
            return 'Os dados do responsável técnico não foram excluídos!';
        }//if
    }// fim excluiResponsavel
}

//	END OF THE CLASS
?>
