<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

Class c_anp extends c_user {
    //atributos
    private $anp              = NULL; // VARCHAR(9)
    private $descricao          = NULL; // VARCHAR(260)
    
    //construtor
    function __construct(){
        session_start();
        c_user::from_array($_SESSION['user_array']);
    }

   // get e set 
    public function setAnp($anp) {
        $this->anp = c_tools::LimpaCamposGeral($anp);
    }

    public function getAnp() {
        return $this->anp;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    //metodos
    public function buscar_anp() {
        $anp = $this->select_anp();
        $this->setAnp($anp[0]['ANP']);
        $this->setDescricao($anp[0]['DESCRICAO']);
    } 

     public function existeAnp() {
         $sql = "SELECT * ";
        $sql .= "FROM est_anp ";
        $sql .= "WHERE (anp = '" . $this->getAnp() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
     }

    public function select_anp() {
        $sql = "SELECT * ";
        $sql .= "FROM EST_ANP ";
        $sql .= "WHERE (anp = '" . $this->getAnp() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_anp_geral() {
        $sql = "SELECT * ";
        $sql .= "FROM EST_ANP ";
        $sql .= "ORDER BY descricao; ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    public function incluirAnp() {
      
        $sql = "INSERT INTO EST_ANP (anp,descricao) ";
        $sql .= "VALUES ('" . $this->getAnp() . "', '"
                . $this->getDescricao() . "'); ";       
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();
        
        return $status;
    } 


    public function alterarAnp() {

        $sql = "UPDATE EST_ANP ";
        $sql .= "SET anp = '" . $this->getAnp() . "', ";
        $sql .= "descricao = '" . $this->getDescricao() . "' ";
        $sql .= "WHERE (anp = '" . $this->getAnp() . "'); ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();

        return $status;
    }

    public function excluirAnp() {
        $sql = "DELETE FROM EST_ANP ";
        $sql .= "WHERE (anp = '" . $this->getAnp() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();
        return $status;
    }

}
?>
