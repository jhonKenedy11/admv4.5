<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

Class c_unidade extends c_user {

    private $id         = NULL;
    private $unidade     = NULL;
    private $descricao   = NULL;
    private $ativo       = 'S';

    function __construct() {
        session_start();
        c_user::from_array($_SESSION['user_array']);
    }

    public function setId($id) {
        $this->id = c_tools::LimpaCamposGeral($id);
    }

    public function getId() {
        return $this->id;
    }

    public function setUnidade($unidade) {
        $this->unidade = strtoupper(c_tools::LimpaCamposGeral($unidade));
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function setDescricao($descricao) {
        $this->descricao = addslashes($descricao);
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setAtivo($ativo) {
        $this->ativo = strtoupper($ativo) === 'N' ? 'N' : 'S';
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function buscar_unidade() {
        $reg = $this->select_unidade();
        if (is_array($reg) && count($reg) > 0) {
            $this->setId($reg[0]['UNIDADE']);
            $this->setUnidade($reg[0]['UNIDADE']);
            $this->setDescricao($reg[0]['DESCRICAO']);
            $this->setAtivo($reg[0]['ATIVO']);
        }
    }

    public function existeUnidade() {
        $sql  = "SELECT * FROM est_unidade ";
        $sql .= "WHERE (unidade = '" . $this->getUnidade() . "')";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }

    public function unidadeEmUso() {
        $sql  = "SELECT COUNT(*) AS TOTAL FROM est_produto ";
        $sql .= "WHERE (unidade = '" . $this->getId() . "')";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return ($banco->resultado[0]['TOTAL'] ?? 0) > 0;
    }

    public function select_unidade() {
        $sql  = "SELECT * FROM est_unidade ";
        $sql .= "WHERE (unidade = '" . $this->getId() . "')";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_unidade_geral() {
        $sql  = "SELECT * FROM est_unidade ORDER BY descricao, unidade";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function incluirUnidade() {
        $sql  = "INSERT INTO est_unidade (UNIDADE, DESCRICAO, ATIVO, USERINSERT) ";
        $sql .= "VALUES ('" . $this->getUnidade() . "', '" . $this->getDescricao() . "', ";
        $sql .= "'" . $this->getAtivo() . "', '" . $this->m_userid . "')";
        $banco = new c_banco();
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        return $res > 0;
    }

    public function alterarUnidade() {
        $sql  = "UPDATE est_unidade SET ";
        $sql .= "descricao = '" . $this->getDescricao() . "', ";
        $sql .= "ativo = '" . $this->getAtivo() . "', ";
        $sql .= "userchange = '" . $this->m_userid . "', ";
        $sql .= "datechange = now() ";
        $sql .= "WHERE unidade = '" . $this->getId() . "'";
        $banco = new c_banco();
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        return $res > 0;
    }

    public function excluirUnidade() {
        if ($this->unidadeEmUso()) {
            return false;
        }
        $sql  = "DELETE FROM est_unidade ";
        $sql .= "WHERE unidade = '" . $this->getId() . "'";
        $banco = new c_banco();
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        return $res > 0;
    }

    public function select_unidade_combo() {
        $consulta = new c_banco();
        $sql = "SELECT UNIDADE, DESCRICAO FROM EST_UNIDADE WHERE ATIVO = 'S' ORDER BY DESCRICAO, UNIDADE";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];

        $ids[0] = '';
        $names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i + 1] = $result[$i]['UNIDADE'];
            $names[$i + 1] = $result[$i]['UNIDADE'] . ' - ' . $result[$i]['DESCRICAO'];
        }
        return array(
            'ids' => $ids,
            'names' => $names
        );
    }
}
?>
