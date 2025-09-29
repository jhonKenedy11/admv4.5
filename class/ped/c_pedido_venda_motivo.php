<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class c_atividade
Class c_pedido_venda_motivo extends c_user {

    /*
     * TABLE NAME FAT_COND_PGTO
     */
    
    // Campos tabela    
    private $motivo                  = NULL; 
    private $descricao           = NULL; 
    
    /**
     * METODOS DE SETS E GETS
     */
    
    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDescricao() {
        return $this->descricao;
    }
  
    //############### FIM SETS E GETS ###############
    

    public function buscar_motivo() {
        $cond_pgto = $this->select_motivo();
        $this->setMotivo($cond_pgto[0]['MOTIVO']);
        $this->setDescricao($cond_pgto[0]['DESCRICAO']);
    } // busca_cond_pgto

    
    public function existeMotivo() {
         $sql = "SELECT * ";
        $sql .= "FROM fat_motivo ";
        $sql .= "WHERE (motivo = '" . $this->getMotivo() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
     } // existeCondpgto

    public function select_motivo() {
        $sql = "SELECT * ";
        $sql .= "FROM fat_motivo ";
        $sql .= "WHERE (motivo = '" . $this->getMotivo() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // select_cond_pgto

    public function select_motivo_geral() {
        $sql = "SELECT * ";
        $sql .= "FROM fat_motivo ";
        $sql .= "ORDER BY descricao; ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // select_cond_pgto_geral

    public function incluirMotivo() {
      
        $sql = "INSERT INTO fat_motivo (descricao) ";
        $sql .= "VALUES ('" . $this->getDescricao(). "'); ";

        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Motivo ' . $this->getDescricao() . ' nao foi cadastrado!';
        }
    } // incluirCondpgto

    public function alterarMotivo() {

        $sql = "UPDATE fat_motivo ";
        $sql .= "SET descricao = '" . $this->getDescricao() . "' ";
        $sql .= "WHERE (motivo = '" . $this->getMotivo() . "'); ";
        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Motivo ' . $this->getDescricao() . ' n&atilde;o foi alterado!';
        }
    }  // alterarCondpgto


    public function excluirMotivo() {
        $sql = "DELETE FROM fat_motivo ";
        $sql .= "WHERE (motivo = '" . $this->getMotivo() . "'); ";
        
        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Motivo ' . $this->getDescricao() . ' n&atilde;o foi excluido!';
        }
    } // excluirCondpgto

}//	END OF THE CLASS
?>
