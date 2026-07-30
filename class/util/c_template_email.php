<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");

/**
 * Tabela AMB_TEMPLATE (templates de e-mail)
 */
class c_template_email extends c_user {

    private $id = null;
    private $descricao = null;
    private $parametro = null;
    private $body = null;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setParametro($parametro) {
        $this->parametro = $parametro;
    }

    public function getParametro() {
        return $this->parametro;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function getBody() {
        return $this->body;
    }


    public function select_template_email() {
        $sql = "SELECT * FROM AMB_TEMPLATE ";
        $sql .= "WHERE ID = '" . $this->getId() . "'";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_template_email_geral() {
        $sql = "SELECT * FROM AMB_TEMPLATE ORDER BY ID";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function incluirTemplate() {
        $sql = "INSERT INTO AMB_TEMPLATE (DESCRICAO, PARAMETRO, BODY) VALUES ('"
            . $this->getDescricao() . "', '"
            . $this->getParametro() . "', '"
            . addslashes($this->getBody()) . "')";
        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            return '';
        }
        return 'Template n&atilde;o foi cadastrado.';
    }

    public function alterarTemplate() {
        $sql = "UPDATE AMB_TEMPLATE SET "
            . "DESCRICAO = '" . $this->getDescricao() . "', "
            . "PARAMETRO = '" . $this->getParametro() . "', "
            . "BODY = '" . addslashes($this->getBody()) . "' "
            . "WHERE ID = '" . $this->getId() . "'";
        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            return '';
        }
        return 'Template n&atilde;o foi alterado.';
    }

    public function excluirTemplate() {
        $sql = "DELETE FROM AMB_TEMPLATE WHERE ID = '" . $this->getId() . "'";
        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            return '';
        }
        return 'Template n&atilde;o foi exclu&iacute;do.';
    }
}
