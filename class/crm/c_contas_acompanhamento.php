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
    public $id                     = NULL; // INT(11)
    public $idPedido               = NULL; // INT(11)
    public $pessoa                 = NULL; // INT(11)
    public $dataContato            = NULL; // DATE
    public $acao                   = NULL; // VARCHAR(20)
    public $vendedorAcomp          = NULL; // INT(11)
    public $proximoContato         = NULL; // DATE
    public $resultContato          = NULL; // TEXT
    public $veiculo                = NULL; // INT(11)
    public $origem                 = NULL; // VARCHAR(20)
    public $destino                = NULL; // VARCHAR(20)
    public $km                     = NULL; // INT(11)
    public $user_id                = NULL; // INT(11)
    public $status                 = NULL; // CHAR(1)
    public $datechange             = NULL; // DATECHANGE(1)  
    public $dateinsert             = NULL; // DATECHANGE(1) 

    /**
     * METODOS DE SETS E GETS
     */
    
    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setIdPedido($idPedido) {
        $this->idPedido = $idPedido;
    }

    public function getIdPedido() {
        return $this->idPedido;
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
                if($this->dataContato == null or $this->dataContato == '' ){
                    return null;
                }else{
                    return date('d/m/Y H:i', strtotime($this->dataContato));
                }
                break;
            case 'B':
                if($this->dataContato == null){
                    return null;
                }else{
                    return c_date::convertDateBd($this->dataContato, $this->m_banco);
                }
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

    public function setUsrIC($user_id) {
        $this->user_id = $user_id;
    }

    public function getUsrIC() {
        return $this->user_id;
    }

    public function setStatus($status){
        $this->status = ($status);
    }

    public function getStatus(){
        return $this->status;
    }

    public function setDateInsert($dateinsert)
    {
        $this->dateinsert = $dateinsert;
    }

    public function getDateInsert($format = null){
        if ($this->dateinsert != null) {
            switch ($format) {
                case 'F':
                    return date('d/m/Y H:i', strtotime($this->dateinsert));
                    break;
                case 'B':
                    return c_date::convertDateBd($this->dateinsert, $this->m_banco);
                    break;
                default:
                    return $this->dateinsert;
            }
        } else {
            return null;
        }
    }

    public function setDateChange($datechange){
        $this->datechange = ($datechange);
    }

    public function getDateChange($format=null){
        if ($this->datechange != null) {
            switch ($format) {
                case 'F':
                    return date('d/m/Y H:i', strtotime($this->datechange));
                    break;
                case 'B':
                    return c_date::convertDateBd($this->datechange, $this->m_banco);
                    break;
                default:
                    return $this->datechange;
            }
        } else {
            return null;
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
        $this->setIdPedido($acompanhamento[0]['PEDIDO_ID']);
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

        $sql = "SELECT a.data, a.atividade,a.RESULTADO, f.descricao, a.ID, a.ligardia, c.cliente, c.nomereduzido, u.nomereduzido as vendedor, a.pedido_id ";
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

        $where = "";
        if ($letra != '||||') {
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
        if ($par[2] != '') {
            if (($par[0] != '') or ( $par[1] != ''))  {
                $where .= "AND (a.usrvendedor IN (" . $par[2] . ")) ";
            }else{
                $where .= "(a.usrvendedor IN (" . $par[2] . ")) ";  
            }
        }

        if ($par[3] != '') {
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '')) {
                $where .= "AND (c.nome like '%" . $par[3] . "%') ";
            } else {
                $where .= "(c.nome like '%" . $par[3] . "%') ";
            }
        }

        if ($par[4] != '') {
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '') or ( $par[3] != '')) {
                $where .= "AND (a.pedido_id =" . $par[4] . ") ";
            } else {
                $where .= "(a.pedido_id =" . $par[4].") ";
            }
        }

        if ($total) {
            $sql = $count . $where . "GROUP BY u.nomereduzido";
        } else {
            $sql .= $where . "ORDER BY a.data";
        }
        //echo strtoupper($sql) ;
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
      
        $sql = "INSERT INTO fin_cliente_acomp (pessoa, pedido_id, data, atividade, resultado, usrvendedor, ligardia, veiculo, origem, destino, km, userinsert, dateinsert, status) ";
        $sql .= "VALUES (" . $this->getPessoa() . ", ";

        if($this->getIdPedido() == ''){
            $sql .= "null, ";
        }else{
            $sql .= "'" .$this->getIdPedido() . "', ";
        }

        if($this->getDataContato() == null){
            $sql .= "NOW(), '";
        }else{
            $sql .="'". $this->getDataContato('B') . "', '";
        }
            $sql .= $this->getAcao() . "', '"
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
                . $this->getKM() . ", '";
        if($this->getUsrIC() == ''){
            $sql .= $this->m_userid ."', ";
        }else{
            $sql .= $this->getUsrIC() . "', ";
        }
                //. $this->getUsrIC() . "', ";
        if($this->getDateInsert() == ''){
            $sql .= "NOW(), 1);";
        }else{
            $sql .= "'". $this->getDateInsert('') . "', 1); ";
        }
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
        //data ctt
        // if($this->getDataContato() == ''){
        //     $sql .= "data = null, ";
        // }else{
        //     $sql .= "data = '" . $this->getDataContato('B') . "', ";
        // }

        if( $this->getDataContato() !== "" and $this->getDataContato() !== null){
            $sql .= "data = '" . $this->getDataContato('B') . "', ";
        }

        $sql .= "resultado = '" . $this->getResultContato() . "', ";
        $sql .= "usrvendedor = " . $this->getVendedorAcomp() . ", ";
        $sql .= "ligardia = ";
        //proximo ctt
        if ($proximoContato == null) {
            $sql .= "null, ";
        } else {
            $sql .="'" . $proximoContato . "', ";
        };

        $sql .= "veiculo = '" . $this->getVeiculo() . "', ";
        $sql .= "origem = '" . $this->getOrigem() . "', ";
        $sql .= "destino = '" . $this->getDestino() . "', ";
        $sql .= "km = " . $this->getKM() . ", ";
        $sql .= "userchange = '" . $this->m_userid . "', ";
        //data atualizacao udpate
        if($this->getDateChange('B') == ''){
            $sql .= "datechange = null, ";
        }else{
            $sql .= "datechange = '" . $this->getDateChange('B') . "', ";
        }

        $sql .= "status = '" . $this->getStatus() . "' ";
        $sql .= "WHERE (id = " . $this->getId() . ") ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $res_pessoaAcomp = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_pessoaAcomp > 0) {
            return true;
        } else {
            return false;
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
     * Consulta para o Banco atraves do id
     * @name verifica_vendedor
     * @return ARRAY todos os campos da table
     * @version 20200505
     */
    public function verifica_vendedor() {

        $sql = "SELECT USUARIO, NOME, TIPO FROM AMB_USUARIO  ";
        $sql .= "WHERE (USUARIO = ". $this->m_userid.")";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i] = $result[$i]['ID'];
            $names[$i] = $result[$i]['DESCRICAO'];
        }
        
        $param = explode(",", $par);
        $i=0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }    
    }

    /**
     * Query for the Database through the id
     * @name buscaDatasBloqueadas
     * @return ARRAY todos os campos da table
     * @version 28042023
     * @author Jhon Kenedy <jhon.kened11@gmail.com>
     */
    function buscaDatasBloqueadas($start_date, $end_date=null) {

        if($end_date == null or $end_date == ''){
            // Primeiro dia do mês atual
            $primeiro_dia = date('Y-m-01 00:00:00', strtotime($start_date));
            // Último dia do mês atual
            $ultimo_dia = date('Y-m-t 23:59:59', strtotime($start_date));
        }else{
            // Primeiro dia do mês atual
            $primeiro_dia = $start_date;
            // Último dia do mês atual
            $ultimo_dia = $end_date;
        }


        $sql = "SELECT DATA FROM fin_cliente_acomp ";
        $sql .= "WHERE ATIVIDADE = 999 and RESULTADO = 'ENTREGA-BLOQUEADA' and ";
        $sql .= "DATA BETWEEN '".$primeiro_dia."' and '".$ultimo_dia. "';";

        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        if(is_array($consulta->resultado)){
            $events = array();
            for($i = 0; $i < count($consulta->resultado); $i++){
                $event = array(
                    'data' => date("Y-m-d", strtotime($consulta->resultado[$i]['DATA']))
                );
                array_push($events, $event);
            }
        }else{
            $events = false;
        }

        return $events;
        
    }

}//	END OF THE CLASS
?>
