<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class C_TESTE
/**
 * Classe de teste para teste o GET e SET generico (get/sets mágicos)
 * @package   adm4.0
 * @example   | $teste = new c_teste(); |  $teste->nome = "jon";
 * @name      c_teste
 * @version   4.1.00
 * @copyright 2019
 * @link      http://www.admservice.com.br/
 * @author    Rodrigo <rodrigo@admservice.com.br>
 * @date      16/07/2019
 */
class c_teste extends c_user
{

    /*
     * TABLE NAME TESTE
     */

    // Campos tabela
    private $props = [];

    /**
     * METODOS DE SETS E GETS
     */
    public function __get($name)
    {
        if (isset($this->props[strtolower($name)])) {
            return $this->props[strtolower($name)];
        } else {
            return false;
        }
    }

    public function __set($name, $value)
    {
        $this->props[strtolower($name)] = $value;
    }

    //############### FIM SETS E GETS ###############

    public function busca_classe()
    {
        $classe = $this->select_classe();
        $this->setClasse($classe[0]['CLASSE']);
        $this->setDescricao($classe[0]['DESCRICAO']);
        $this->setBloqueado($classe[0]['BLOQUEADO']);
    } // busca_classe


    /**
     * 
     * @name existeClasse
     */
    public function existeClasse()
    {

        $sql  = "SELECT * ";
        $sql .= "FROM fin_classe ";
        $sql .= "WHERE (classe = '" . $this->getClasse() . "');";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    } //fim existeClasse

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_classe()
    {

        $sql  = "SELECT * ";
        $sql .= "FROM fin_classe ";
        $sql .= "WHERE (classe = '" . $this->getClasse() . "') ";
        $sql .= "ORDER BY descricao; ";
        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_classe

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_classe_geral()
    {
        $sql  = "SELECT * ";
        $sql .= "FROM fin_classe ";
        $sql .= "ORDER BY classe; ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;

        //	$this->exec_sql($sql);
        //	return $this->resultado;


    } //fim select_classe_geral
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_classe_letra($letra)
    {
        $sql  = "SELECT DISTINCT * ";
        $sql .= "FROM fin_classe ";
        $sql .= "WHERE descricao LIKE '" . $letra . "%' ";
        $sql .= "ORDER BY classe; ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // fim select_classe_letra

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function incluiClasse()
    {

        $sql  = "INSERT INTO fin_classe (classe, descricao, bloqueado)";
        $sql .= "VALUES ('" . $this->getClasse() . "', '" . $this->getDescricao() . "', '" . $this->getBloqueado() . "'); ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $res_classe =  $banco->exec_sql($sql);
        $banco->close_connection();

        if ($res_classe > 0) {
            return '';
        } else {
            return 'Os dados de Classes ' . $this->getDescricao() . ' n&atilde;o foram cadastrados!';
        }
    } // fim incluiClasse

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function alteraClasse()
    {

        $sql  = "UPDATE fin_classe ";
        $sql .= "SET  descricao = '" . $this->getDescricao() . "', ";
        $sql .= "bloqueado = '" . $this->getBloqueado() . "' ";
        $sql .= "WHERE classe = '" . $this->getClasse() . "';";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $res_classe =  $banco->exec_sql($sql);
        $banco->close_connection();

        if ($res_classe > 0) {
            return '';
        } else {
            return 'Os dados de Classes ' . $this->getDescricao() . ' n&atilde;o foram alterados!';
        }
    }  // fim alteraClasse

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function excluiClasse()
    {

        $sql  = "DELETE FROM fin_classe ";
        $sql .= "WHERE classe = '" . $this->getClasse() . "';";
        $banco = new c_banco;
        $res_classe =  $banco->exec_sql($sql);
        $banco->close_connection();
        //echo strtoupper($sql);
        if ($res_classe > 0) {
            return '';
        } else {
            return 'Os dados de Classes ' . $this->getClasse() . ' n&atilde;o foram excluidos!';
        }
    }  // fim excluiClasse

}	//	END OF THE CLASS
