<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class c_atividade
Class c_cond_pgto extends c_user {

    /*
     * TABLE NAME FAT_COND_PGTO
     */
    
    // Campos tabela    
    private $id                  = NULL; 
    private $descricao           = NULL; 
    private $formapgto           = NULL; 
    private $numparcelas         = NULL; 
    private $situacaoLcto        = NULL;
     
    
    /**
     * METODOS DE SETS E GETS
     */
    
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
    
    public function setFormapgto($formapgto) {
        $this->formapgto = $formapgto;
    }

    public function getFormapgto() {
        return $this->formapgto;
    }

    public function setNumparcelas($numparcelas) {
        $this->numparcelas = $numparcelas;
    }

    public function getNumparcelas() {
        return $this->numparcelas;
    }

    public function setSituacaoLcto($situacaoLcto) {
        $this->situacaoLcto = $situacaoLcto;
    }

    public function getSituacaoLcto() {
        return $this->situacaoLcto;
    }

    //############### FIM SETS E GETS ###############
    

    public function buscar_cond_pgto() {
        $cond_pgto = $this->select_cond_pgto();
        $this->setId($cond_pgto[0]['ID']);
        $this->setDescricao($cond_pgto[0]['DESCRICAO']);
        $this->setFormaPgto($cond_pgto[0]['FORMAPGTO']);
        $this->setNumParcelas($cond_pgto[0]['NUMPARCELAS']);
        $this->setSituacaoLcto($cond_pgto[0]['SITUACAOLCTO']);
    } // busca_cond_pgto

    
    public function existeCondpgto() {
         $sql = "SELECT * ";
        $sql .= "FROM fat_cond_pgto ";
        $sql .= "WHERE (id = '" . $this->getId() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
     } // existeCondpgto

    public function select_cond_pgto() {
        $sql = "SELECT * ";
        $sql .= "FROM fat_cond_pgto ";
        $sql .= "WHERE (id = '" . $this->getId() . "'); ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // select_cond_pgto

    public function select_cond_pgto_geral() {
        $sql = "SELECT * ";
        $sql .= "FROM fat_cond_pgto ";
        $sql .= "ORDER BY descricao; ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // select_cond_pgto_geral

    public function incluirCondpgto() {
      
        $sql = "INSERT INTO fat_cond_pgto (id, descricao, formapgto, numparcelas, situacaolcto) ";
        $sql .= "VALUES ('" . $this->getId() . "', '"
                . $this->getDescricao() . "', '"
                . $this->getFormaPgto() . "', '"
                . $this->getNumParcelas() . "', '"
                . $this->getSituacaoLcto() . "'); ";

        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Condição de pagamento ' . $this->getDescricao() . ' nao foi cadastrado!';
        }
    } // incluirCondpgto

    public function alterarCondpgto() {

        $sql = "UPDATE fat_cond_pgto ";
        $sql .= "SET id = '" . $this->getId() . "', ";        
        $sql .= "descricao = '" . $this->getDescricao() . "', ";
        $sql .= "formapgto = '" . $this->getFormaPgto() . "', ";
        $sql .= "numparcelas = '" . $this->getNumParcelas() . "', ";
        $sql .= "situacaolcto = '" . $this->getSituacaoLcto() . "' ";
        $sql .= "WHERE (id = '" . $this->getId() . "'); ";
        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Condição de pagamento ' . $this->getDescricao() . ' n&atilde;o foi alterado!';
        }
    }  // alterarCondpgto


    public function excluirCondpgto() {
        $sql = "DELETE FROM fat_cond_pgto ";
        $sql .= "WHERE (id = '" . $this->getId() . "'); ";
        
        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Condição de pagamento ' . $this->getDescricao() . ' n&atilde;o foi excluido!';
        }
    } // excluirCondpgto

}//	END OF THE CLASS
?>
