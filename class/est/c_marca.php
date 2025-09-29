<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

Class c_marca extends c_user {
    //atributos
    private $marca              = NULL; // VARCHAR(4)
    private $descricao          = NULL; // VARCHAR(30)
    
    //construtor
    function __construct(){
        session_start();
        c_user::from_array($_SESSION['user_array']);
    }

   // get e set 
    public function setMarca($marca) {
        $this->marca = c_tools::LimpaCamposGeral($marca);
    }

    public function getMarca() {
        return $this->marca;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    //metodos
    public function buscar_marca() {
        $marca = $this->select_marca();
        $this->setMarca($marca[0]['MARCA']);
        $this->setDescricao($marca[0]['DESCRICAO']);
    } 

     public function existeMarca() {
         $sql = "SELECT * ";
        $sql .= "FROM est_marca ";
        $sql .= "WHERE (marca = '" . $this->getMarca() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
     }

    public function select_marca() {
        $sql = "SELECT * ";
        $sql .= "FROM EST_MARCA ";
        $sql .= "WHERE (marca = '" . $this->getMarca() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_marca_geral() {
        $sql = "SELECT * ";
        $sql .= "FROM EST_MARCA ";
        $sql .= "ORDER BY descricao; ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    public function incluirMarca() {
      
        $sql = "INSERT INTO EST_MARCA (marca,descricao) ";
        $sql .= "VALUES ('" . $this->getMarca() . "', '"
                . $this->getDescricao() . "'); ";       
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();
        
        return $status;
    } 


    public function alterarMarca() {

        $sql = "UPDATE EST_MARCA ";
        $sql .= "SET marca = '" . $this->getMarca() . "', ";
        $sql .= "descricao = '" . $this->getDescricao() . "' ";
        $sql .= "WHERE (marca = '" . $this->getMarca() . "'); ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();

        return $status;
    }

    public function excluirMarca() {
        $sql = "DELETE FROM EST_MARCA ";
        $sql .= "WHERE (marca = '" . $this->getMarca() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();
        return $status;
    }

}
?>
