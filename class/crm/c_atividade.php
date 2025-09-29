<?php
/**
 * @package   astecv3
 * @name      c_atividade
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      11/04/2016
*/

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class c_atividade
Class c_atividade extends c_user {

    /*
     * TABLE NAME FIN_ATIVIDADE
     */
    
    // Campos tabela    
    private $atividade              = NULL; // VARCHAR(4)
    private $descricao              = NULL; // VARCHAR(30)
    
    /**
     * METODOS DE SETS E GETS
     */

//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);
}

    
    public function setAtividade($atividade) {
        $this->atividade = c_tools::LimpaCamposGeral($atividade);
    }

    public function getAtividade() {
        return $this->atividade;
    }

    public function setDescricao($descricao) {
        //$this->descricao = c_tools::LimpaCamposGeral($descricao);
        $this->descricao = $descricao;
    }

    public function getDescricao() {
        return $this->descricao;
    }
    //############### FIM SETS E GETS ###############
    

    /**
     * Funcao para setar todos os registros da table.
     * @name buscaCadastroAcompanhamento
     * @param INT GetAtividade Codigo da atividade
     */
    public function busca_atividade() {
        $atividade = $this->select_atividade();
        $this->setAtividade($atividade[0]['ATIVIDADE']);
        $this->setDescricao($atividade[0]['DESCRICAO']);
    } // buscaCadastroAcompanhamento

    /**
     * Funcao para verificar a existencia de registros iguais na chave primaria da table
     * @name existeAtividade
     * @param INT GetAtividade Chave primaria da tabela
     */
     public function existeAtividade() {
         $sql = "SELECT * ";
        $sql .= "FROM fin_atividade ";
        $sql .= "WHERE (atividade = '" . $this->getAtividade() . "'); ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
     }

     /**
     * @name select_atividade
     * @param INT GetAtividade Codigo da atividade
     * @return ARRAY todas as colunas da table
     */
    public function select_atividade() {
        $sql = "SELECT * ";
        $sql .= "FROM fin_atividade ";
        $sql .= "WHERE (atividade = '" . $this->getAtividade() . "'); ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// select_atividade

   /**
    * @name select_atividade_geral
    * @return ARRAY todos as colunas da table
    */
    public function select_atividade_geral() {
        $sql = "SELECT * ";
        $sql .= "FROM fin_atividade ";
        $sql .= "ORDER BY descricao; ";
        //ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// select_atividade_geral

    /**
     * Funcao para incluir no Banco
     * @name incluiAtividade
     * @return string vazio se ocorrer com sucesso
     */
    public function incluiAtividade() {
      
        $sql = "INSERT INTO fin_atividade (atividade,descricao) ";
        $sql .= "VALUES ('" . $this->getAtividade() . "', '"
                . $this->getDescricao() . "'); ";
        
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();
        
        return $status;
    } // incluiAtividade

    /**
     * Funcao para alteracao no banco
     * @name alteraAtividade
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraAtividade() {

        $sql = "UPDATE fin_atividade ";
        $sql .= "SET atividade = '" . $this->getAtividade() . "', ";
        $sql .= "descricao = '" . $this->getDescricao() . "' ";
        $sql .= "WHERE (atividade = '" . $this->getAtividade() . "'); ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();

        return $status;
    }// alteraAtividade

    /**
     * Funcao para exclusao no banco
     * @name excluiAtividade
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiAtividade() {
        $sql = "DELETE FROM fin_atividade ";
        $sql .= "WHERE (atividade = '" . $this->getAtividade() . "'); ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $status = $banco->result;
        $banco->close_connection();

        return $status;
    }// excluiAtividade

}//	END OF THE CLASS
?>
