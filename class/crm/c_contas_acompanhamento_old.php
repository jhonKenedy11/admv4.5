<?php
/**
 * @package   astecv3
 * @name      c_pessoa_acompanhamento
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      05/04/2016
*/

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");

//Class c_pessoa_acompanhamento
Class c_contas_acompanhamento extends c_user {

    /*
     * TABLE NAME FIN_CLIENTE_ACOMP
     */
    
    // Campos tabela    
    private $id                     = NULL; // INT(11)
    private $pessoa                 = NULL; // INT(11)
    private $dataContato            = NULL; // DATE
    private $acao                   = NULL; // VARCHAR(20)
    private $vendedorAcomp          = NULL; // INT(11)
    private $proximoContato         = NULL; // DATE
    private $resultContato          = NULL; // TEXT
    private $veiculo                = NULL; // INT(11)
    private $origem                 = NULL; // VARCHAR(20)
    private $destino                = NULL; // VARCHAR(20)
    private $km                     = NULL; // INT(11)

    /**
     * METODOS DE SETS E GETS
     */
    
    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setPessoa($cliente) {
        $this->pessoa = $cliente;
    }

    public function getPessoa() {
        return $this->pessoa;
    }

    public function setDataContato($dataContato) {
        $this->dataContato = $dataContato;
    }

    public function getDataContato($format = null) {
        switch ($format) {
            case 'F':
                return date('d/m/Y H:i', strtotime($this->dataContato));
                break;
            case 'B':
                return c_date::convertDateBd($this->dataContato, $this->m_banco);
                break;
            default:
                return $this->dataContato;
        }
    }

    public function setVendedorAcomp($vendedorAcomp) {
        $this->vendedorAcomp = $vendedorAcomp;
    }

    public function getVendedorAcomp() {
        return $this->vendedorAcomp;
    }

    public function setAcao($acao) {
        $this->acao = $acao;
    }

    public function getAcao() {
        return $this->acao;
    }

    public function setProximoContato($proximoContato) {
        $this->proximoContato = $proximoContato;
    }

    public function getProximoContato($format = null) {
        if ($this->proximoContato != null) {
            switch ($format) {
                case 'F':
                    return date('d/m/Y H:i', strtotime($this->proximoContato));
                    break;
                case 'B':
                    return c_date::convertDateBd($this->proximoContato, $this->m_banco);
                    break;
                default:
                    return $this->proximoContato;
            }
        } else {
            return null;
        }
    }

    public function setResultContato($resultContato) {
        $this->resultContato = strtoupper($resultContato);
    }

    public function getResultContato() {
        return $this->resultContato;
    }

    public function setVeiculo($veiculo) {
        $this->veiculo = strtoupper($veiculo);
    }

    public function getVeiculo() {
        return $this->veiculo;
    }

    public function setOrigem($origem) {
        $this->origem = strtoupper($origem);
    }

    public function getOrigem() {
        return $this->origem;
    }

    public function setDestino($destino) {
        $this->destino = strtoupper($destino);
    }

    public function getDestino() {
        return $this->destino;
    }

    public function setKM($km) {
        $this->km = ($km);
    }

    public function getKM() {
        if ($this->km != null) {
            return $this->km;
        } else {
            return 0;
        }
    }
    //############### FIM SETS E GETS ###############
    

    /**
     * Funcao para setar todos os registros da table.
     * @name buscaCadastroAcompanhamento
     * @param INT GetId Codigo do cliente
     * @param DATE GetDataContato data do contato
     * @param TIME GetHoraContato hora do contato
     */
    public function buscaCadastroAcompanhamento() {
        $acompanhamento = $this->select_pessoaAcomp();
        $this->setId($acompanhamento[0]['ID']);
        $this->setPessoa($acompanhamento[0]['PESSOA']);
        $this->setDataContato($acompanhamento[0]['DATA']);
        $this->setResultContato($acompanhamento[0]['RESULTADO']);
        $this->setVendedorAcomp($acompanhamento[0]['USRVENDEDOR']);
        $this->setProximoContato($acompanhamento[0]['LIGARDIA']);
        $this->setVeiculo($acompanhamento[0]['VEICULO']);
        $this->setOrigem($acompanhamento[0]['ORIGEM']);
        $this->setDestino($acompanhamento[0]['DESTINO']);
        $this->setKM($acompanhamento[0]['KM']);
    } // buscaCadastroAcompanhamento

    /**
     * Funcao select para filtro de pesquisa
     * @name select_pessoaConsultaAcompanhamento
     * @param String $letra dataIni | dataFim | vendedor | nome do cliente
     * @param Boolean $total Se vazio: ORDER BY | Caso não vazio GROUP BY
     * @return ARRAY
     */    
    public function select_pessoaConsultaAcompanhamento($letra, $total = false) {

        $par = explode("|", $letra);
        $par[0] = c_date::convertDateBdSh($par[0], $this->m_banco);
        $par[1] = c_date::convertDateBdSh($par[1], $this->m_banco);

        $sql = "SELECT a.data, a.atividade,A.RESULTADO, f.descricao, A.ID, a.ligardia, c.cliente, c.nomereduzido, u.nomereduzido as vendedor ";
        $sql .= "FROM fin_cliente c ";
        $sql .= "inner join fin_cliente_acomp a on c.cliente = a.pessoa ";
        $sql .= "inner join fat_atividade_acomp f on f.atividade = a.atividade ";
        $sql .= "left join amb_usuario u on u.usuario = a.usrvendedor ";
        $sql .= " ";
        $count = "SELECT u.nomereduzido, count(u.nomereduzido) as count ";
        $count .= "FROM fin_cliente c ";
        $count .= "inner join fin_cliente_acomp a on c.cliente = a.pessoa";
        $count .= "inner join fat_atividade_acomp f on f.atividade = a.atividade ";
        $count .= "left join amb_usuario u on u.usuario = a.usrvendedor ";
        $count .= " ";
        if ($letra != '|||') {
            $where = "WHERE ";
        }
        if ($par[0] != '') {
            $where .= "(a.data >= '" . $par[0] . "') ";
        }
        if ($par[1] != '') {
            if ($par[0] != '') {
                $where .= "AND (a.data <= '" . $par[1] . "') ";
            }
        }
        if ($par[2] != '0') {
            if (($par[0] != '') or ( $par[1] != '')) {
                $where .= "AND (a.usrvendedor = " . $par[2] . ") ";
            } else {
                $where .= "(a.usrvendedor = " . $par[2] . ") ";
            }
        }
        if ($par[3] != '') {
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '')) {
                $where .= "AND (c.nome like '%" . $par[3] . "%') ";
            } else {
                $where .= "(c.nome like '%" . $par[3] . "%') ";
            }
        }

        if ($total) {
            $sql = $count . $where . "GROUP BY u.nomereduzido";
        } else {
            $sql .= $where . "ORDER BY a.data";
        }
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// select_pessoaConsultaAcompanhamento

    /**
     * Funcao para verificar dados a partir do codigo do cliente
     * @name select_pessoa
     * @param INT GetId Codigo do cliente
     * @return ARRAY todos os campos da table
     */
    public function select_pessoa(){
        $sql  = "SELECT DISTINCT * ";
        $sql .= "FROM fin_cliente ";
        $sql .= "WHERE (CLIENTE = ".$this->getPessoa().") ";
        //ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_pessoa
    
    /**
     * @name select_pessoaAcomp
     * @param INT GetId Codigo do cliente
     * @param DATE GetData Data do contato
     * @param TIME GetHora Hora do contato
     * @return ARRAY todas as colunas da table
     */
    public function select_pessoaAcomp() {
        $sql = "SELECT * ";
        $sql .= "FROM fin_cliente_acomp ";
        $sql .= "WHERE (id = " . $this->getId() . ") ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// select_pessoaAcomp

   /**
    * @name select_pessoaAcomp_geral
    * @param INT GetId Codigo do cliente
    * @return ARRAY todos as colunas da table ACOMP, DESC Atividade e Nome Usuario
    */
    public function select_pessoaAcomp_geral() {
        $sql = "SELECT c.*, a.descricao, u.nomereduzido ";
        $sql .= "FROM fin_cliente_acomp c ";
        $sql .= "left join amb_usuario u on u.usuario = c.usrvendedor ";
        $sql .= "left join fat_atividade_acomp a on a.atividade = c.atividade ";
        $sql .= "WHERE (c.id = " . $this->getId() . ") ";
        $sql .= "ORDER BY c.data desc ";
        //ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// select_pessoaAcomp_geral

    /**
     * Funcao para incluir no Banco
     * @name incluiPessoaAcomp
     * @return string vazio se ocorrer com sucesso
     */
    public function incluiPessoaAcomp() {
        $proximoContato = $this->getProximoContato('B');
      
        $sql = "INSERT INTO fin_cliente_acomp (pessoa, data, atividade, resultado, usrvendedor, ligardia, veiculo, origem, destino, km) ";
        $sql .= "VALUES (" . $this->getPessoa() . ", '"
                . $this->getDataContato('B') . "', '"
                . $this->getAcao() . "', '"
                . $this->getResultContato() . "', "
                . $this->getVendedorAcomp() . ", ";
        if ($proximoContato == null) {
            $sql .= "null, '";
        } else {
            $sql .="'" . $proximoContato . "', '";
        };
        $sql .= $this->getVeiculo() . "', '"
                . $this->getOrigem() . "', '"
                . $this->getDestino() . "', "
                . $this->getKM() . ") ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Os dados de Pessoa Acompanhamento ' . $this->getId() . ' nao foi cadastrado!';
        }
    } // incluiPessoaAcomp

    /**
     * Funcao para alteracao no banco
     * @name alteraPessoaAcomp
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraPessoaAcomp() {
        $proximoContato = $this->getProximoContato('B');

        $sql = "UPDATE fin_cliente_acomp ";
        $sql .= "SET atividade = '" . $this->getAcao() . "', ";
        $sql .= "resultado = '" . $this->getResultContato() . "', ";
        $sql .= "usrvendedor = " . $this->getVendedorAcomp() . ", ";
        $sql .= "ligardia = ";
        if ($proximoContato == null) {
            $sql .= "null, ";
        } else {
            $sql .="'" . $proximoContato . "', ";
        };
        $sql .= "veiculo = '" . $this->getVeiculo() . "', ";
        $sql .= "origem = '" . $this->getOrigem() . "', ";
        $sql .= "destino = '" . $this->getDestino() . "', ";
        $sql .= "km = " . $this->getKM() . " ";
        $sql .= "WHERE (id = " . $this->getId() . ") ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Os dados de Pessoa Acompanhamento ' . $this->getCliente() . ' n&atilde;o foi alterado!';
        }
    }

    /**
     * Funcao para exclusao no banco
     * @name excluiPessoaAcomp
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiPessoaAcomp() {
        $sql = "DELETE FROM fin_cliente_acomp ";
        $sql .= "WHERE (id = " . $this->getId() . ") ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return '';
        } else {
            return 'Os dados de Pessoa Acompanhamento ' . $this->getId() . ' n&atilde;o foi excluido!';
        }
    }// excluiPessoaAcomp

    /**
     * @name select_pessoaAcomp
     * @param INT GetId Codigo do cliente
     * @param DATE GetData Data do contato
     * @param TIME GetHora Hora do contato
     * @return ARRAY todas as colunas da table
     */
    public function selectAcompanhamentoPessoa($pessoa) {
        $sql = "SELECT * ";
        $sql .= "FROM fin_cliente_acomp ";
        $sql .= "WHERE (pessoa = " . $pessoa . ") ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// select_pessoaAcomp

}//	END OF THE CLASS
?>
