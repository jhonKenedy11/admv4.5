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
Class c_crm_contas_acompanhamento extends c_user {

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
        if (!is_array($acompanhamento) || count($acompanhamento) < 1) {
            return false;
        }
        $acompanhamento = array_change_key_case($acompanhamento[0], CASE_UPPER);
        $this->setId($acompanhamento['ID']);
        $this->setPessoa($acompanhamento['PESSOA']);
        $this->setIdPedido($acompanhamento['PEDIDO_ID']);
        $this->setAcao($acompanhamento['ATIVIDADE']);
        $this->setDataContato($acompanhamento['DATA']);
        $this->setResultContato($acompanhamento['RESULTADO']);
        $this->setVendedorAcomp($acompanhamento['USRVENDEDOR']);
        $this->setProximoContato($acompanhamento['LIGARDIA']);
        $this->setVeiculo($acompanhamento['VEICULO']);
        $this->setOrigem($acompanhamento['ORIGEM']);
        $this->setDestino($acompanhamento['DESTINO']);
        $this->setKM($acompanhamento['KM']);
        $this->setStatus($acompanhamento['STATUS']);
        return true;
    } // buscaCadastroAcompanhamento

    /**
     * Funcao select para filtro de pesquisa
     * @name select_pessoaConsultaAcompanhamento
     * @param String $letra dataIni | dataFim | vendedor | nome do cliente
     * @param Boolean $total Se vazio: ORDER BY | Caso não vazio GROUP BY
     * @return ARRAY
     */    
    public function select_pessoaConsultaAcompanhamento($letra, $total = false, $usrVendedor = null) {

        $par = explode("|", $letra);
        $par[0] = c_date::convertDateBdSh($par[0], $this->m_banco);
        $par[1] = c_date::convertDateBdSh($par[1], $this->m_banco);

        $sql = "SELECT a.data, a.atividade,A.RESULTADO, f.descricao, A.ID, a.ligardia, c.cliente, c.nomereduzido, u.nomereduzido as vendedor, a.pedido_id ";
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
        $where = '';
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
        if ($par[2] != '0') {
            if (($par[0] != '') or ( $par[1] != '')) {
                $where .= "AND (a.usrvendedor in (" . $par[2] . ")) ";
            } else {
                $where .= "((a.usrvendedor in (" . $par[2] . "))) ";
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

        if ($usrVendedor !== null) {
            if (trim((string) $where) === '') {
                $where = "WHERE ";
            }
            if (substr(trim($where), -5) !== "WHERE") {
                $where .= "AND ";
            }
            $where .= "(a.usrvendedor = " . $usrVendedor . ") ";
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
    public function select_pessoaAcomp($usrVendedor = null) {
        $sql = "SELECT a.*, c.classe, c.nomereduzido ";
        $sql .= "FROM fin_cliente_acomp a ";
        $sql .= "INNER JOIN fin_cliente c on c.cliente = a.pessoa ";
        $sql .= "WHERE (id = " . $this->getId() . ") ";
        if ($usrVendedor !== null) {
            $sql .= "AND a.usrvendedor = " . $usrVendedor . " ";
        }
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
    public function select_pessoaAcomp_geral($usrVendedor = null) {
        $sql = "SELECT c.*, a.descricao, u.nomereduzido ";
        $sql .= "FROM fin_cliente_acomp c ";
        $sql .= "left join amb_usuario u on u.usuario = c.usrvendedor ";
        $sql .= "left join fat_atividade_acomp a on a.atividade = c.atividade ";
        $sql .= "WHERE (c.id = " . $this->getId() . ") ";
        if ($usrVendedor !== null) {
            $sql .= "AND c.usrvendedor = " . $usrVendedor . " ";
        }
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
            $sql .= "null, '";
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
            $sql .= "NOW(), ";
        }else{
            $sql .= "'". $this->getDateInsert('') . "', ";
        }
        //status
        if($this->getStatus() == ''){
            $sql .= "'1');";
        }else{
            $sql .= "'". $this->getStatus() . "'); ";
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
        if($this->getDataContato() == ''){
            $sql .= "data = null, ";
        }else{
            $sql .= "data = '" . $this->getDataContato('B') . "', ";
        }

        $sql .= "resultado = '" . $this->getResultContato() . "', ";
        if ($this->getIdPedido() == '') {
            $sql .= "pedido_id = null, ";
        } else {
            $sql .= "pedido_id = '" . $this->getIdPedido() . "', ";
        }
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
            $sql .= "datechange = NOW(), ";
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
            return '';
        } else {
            return 'Os dados de Pessoa Acompanhamento ' . $this->getPessoa() . ' n&atilde;o foi alterado!';
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

    /**
     * Valores AMB_DDM para o status do acompanhamento (alias FIN_CLIENTE_ACOMP).
     * Aceita campo STATUSACOMP ou STATUS (cadastros legados) e chaves id/ID, descricao/DESCRICAO no mysqli.
     *
     * @return array{ids: string[], names: string[]}
     */
    public static function carregaComboDdmStatusAcomp() {
        $ids = [];
        $names = [];
        $sql = "select distinct tipo as id, padrao as descricao from amb_ddm "
            . "where upper(trim(alias)) = 'FIN_CLIENTE_ACOMP' "
            . "and upper(trim(campo)) in ('STATUSACOMP', 'STATUS', 'STATUS_ACOMP') "
            . "order by tipo";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado ?: [];
        $i = 0;
        foreach ($result as $row) {
            $rid = isset($row['ID']) ? $row['ID'] : (isset($row['id']) ? $row['id'] : null);
            $rdesc = isset($row['DESCRICAO']) ? $row['DESCRICAO'] : (isset($row['descricao']) ? $row['descricao'] : '');
            if ($rid === null || $rid === '') {
                continue;
            }
            $ids[$i] = $rid;
            $names[$i] = $rdesc;
            $i++;
        }
        if ($i === 0) {
            $ids = ['1', '2', '3'];
            $names = ['Aberto', 'Em andamento', 'Concluído'];
        }
        return ['ids' => $ids, 'names' => $names];
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
     * @name selectAcompanhamentoPessoa
     * @param VARCHAR ID do cliente
     * @return ARRAY todas as colunas da table
     */
    public function selectAcompanhamentoPessoa($pessoa, $usrVendedor = null){
        $sql = "SELECT fa.*, f.nome as nome_cliente, u.nomereduzido as vendedor_nome ";
        $sql .= "FROM fin_cliente_acomp fa ";
        $sql .= "INNER JOIN fin_cliente f ON fa.pessoa = f.cliente ";
        $sql .= "LEFT JOIN amb_usuario u ON u.usuario = fa.usrvendedor ";
        $sql .= "WHERE (fa.pessoa = " . $pessoa . ") ";
        if ($usrVendedor !== null) {
            $sql .= "AND fa.usrvendedor = " . $usrVendedor . " ";
        }
        $sql .= "order by fa.data";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// select_pessoaAcomp

    /**
     * @name selectAcompanhamentoPeriodo
     * @param VARCHAR ID do cliente
     * @return ARRAY todas as colunas da table
     */
    public function selectAcompanhamentoPeriodo($dataIni=null, $dataFim =null, $usrVendedor = null){
        $sql = "SELECT fa.*, f.nome as nome_cliente, u.nomereduzido as vendedor_nome ";
        $sql .= "FROM fin_cliente_acomp fa ";
        $sql .= "INNER JOIN fin_cliente f ON fa.pessoa = f.cliente ";
        $sql .= "LEFT JOIN amb_usuario u ON u.usuario = fa.usrvendedor ";
        $sql .= "WHERE fa.data BETWEEN '" . $dataIni . " 00:00:00' and '" . $dataFim . " 23:59:59' and fa.status <> 3 ";
        if ($usrVendedor !== null) {
            $sql .= "AND fa.usrvendedor = " . $usrVendedor . " ";
        }
        $sql .= ";";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// select_pessoaAcomp

    /**
     * @name selectAcompanhamentoConcluido
     * @param VARCHAR ID do cliente
     * @return ARRAY todas as colunas da table
     */
    public function selectAcompanhamentoConcluido($dataIni=null, $dataFim =null, $usrVendedor = null){
        
        $sql = "SELECT fa.*, f.nome as nome_cliente, u.nomereduzido as vendedor_nome ";
        $sql .= "FROM fin_cliente_acomp fa ";
        $sql .= "INNER JOIN fin_cliente f ON fa.pessoa = f.cliente ";
        $sql .= "LEFT JOIN amb_usuario u ON u.usuario = fa.usrvendedor ";
        $sql .= "WHERE fa.data BETWEEN '" . $dataIni . " 00:00:00' and '" . $dataFim . " 23:59:59' and fa.status = '3' ";
        if ($usrVendedor !== null) {
            $sql .= "AND fa.usrvendedor = " . $usrVendedor . " ";
        }
        $sql .= ";";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// selectAcompanhamentoConcluido

    /**
     * @name selectBuscaContatoDiario
     * @param VARCHAR dates
     * @return ARRAY quantidade encontrada
     */
    public function selectBuscaContatoDiario($usrVendedor = null){
        $sql = "SELECT COUNT(ID) FROM fin_cliente_acomp ";
        $sql .= "WHERE data BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59' ";
        if ($usrVendedor !== null) {
            $sql .= "AND USRVENDEDOR = " . $usrVendedor . " ";
        }
        $sql .= ";";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// selectBuscaContatoDiario

    /**
     * @name selectBuscaContatoPeriodo
     * @param VARCHAR dates
     * @return ARRAY quantidade encontrada
     */
    public function selectBuscaContatoPeriodo($dataIni, $dataFim, $usrVendedor = null){ 
        $sql = "SELECT COUNT(ID) FROM fin_cliente_acomp ";
        $sql .= "WHERE data BETWEEN '" . $dataIni . " 00:00:00' and '" . $dataFim . " 23:59:59' ";
        if ($usrVendedor !== null) {
            $sql .= "AND USRVENDEDOR = " . $usrVendedor . " ";
        }
        $sql .= ";";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// selectBuscaContatoPeriodo

    /**
    * @name insertContatoCliente
    * @param VARCHAR json de dados
    * @return true se verdadeiro ou erro
    */
    public function insertContatoCliente($dados){ 
        //trata json
        $dadosJson = json_decode($dados);
        //seta pessoa para fazer consulta antes de enviar para tela
        $this->setPessoa($dadosJson->id_pessoa);
        $sql = "INSERT INTO fin_cliente_contato(id_cliente, telefone ,email, nome_contato, userinsert, dateinsert) ";
        $sql .= "VALUES (" . $dadosJson->id_pessoa . ", '";
        $sql .=  $dadosJson->telefone_contato . "', '";
        $sql .=  $dadosJson->email_contato . "', '";
        $sql .=  $dadosJson->nome_contato . "', ";
        $sql .=  $this->m_userid . ", CURRENT_TIMESTAMP());";
        
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);

        //monta retorno
        if($banco->result){
            $return = array(
                'codigo' => 100,
                'mensagem' => 'Contato adicionado com sucesso!'
            );
        }else{
            $return = array(
                'codigo' => $banco->id_connection->errno,
                'mensagem' => $banco->id_connection->error_list[0]["error"]
            );
        }
        //fecha a conexao
        $banco->close_connection();
        return $return;
    }// insertContatoCliente

    /**
    * @name selectBuscaContatoCliente
    * @param VARCHAR ID do cliente
    * @return ARRAY todas as colunas da table
    */
    public function selectBuscaContatoCliente($pessoa){
        $sql = "SELECT CONCAT(c.FONEAREA, '', c.FONE) AS 'TELEFONE', c.EMAIL AS 'EMAIL' , c.NOMEREDUZIDO AS 'NOME_CONTATO' ";
        $sql .= "FROM FIN_CLIENTE c ";
        $sql .= "WHERE c.cliente = " . $pessoa;
        $sql .= " UNION ALL ";
        $sql .= "SELECT cc.TELEFONE, cc.EMAIL, cc.NOME_CONTATO ";
        $sql .= "FROM FIN_CLIENTE_CONTATO cc ";
        $sql .= "WHERE cc.id_cliente = " . $pessoa . ";";

        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // select_pessoaAcomp

    /**
     * Funcao para alteracao no banco
     * @name alteraStatusAcompanhamento
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraStatusAcompanhamento(){
        $sql = "UPDATE fin_cliente_acomp ";
        $sql .= "SET atividade = '" . $this->getAcao() . "' where id = ".$this->getId().";";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->result;
    }

    /**
    * @name insertContatoCliente
    * @param VARCHAR json de dados
    * @return true se verdadeiro ou erro
    */
    public function insertEmail($dados, $jsonData=null){ 

        //monta a descricao anexo
        if(!empty($dados["anexos"])){
            $at         = null;
            $finalAnexo = null;
            foreach($dados["anexos"] as $anexo){

                if($at !== null){
                    $at .= ";" .  $anexo;
                }else{
                    $at = $anexo;
                }
            }

            $finalAnexo .= $at;
            unset($at);
        }

        //echo strtoupper($sql);
        $banco = new c_banco;

        //escape parametro para tratar aspas duplas e simples para registro no banco
        if($jsonData == 'escape'){
            $jsonData = preg_replace('/(?<!\\\\)([\'"])/', '\\\\$1', $dados["body"]);
        }else{
            //$body_escape = mysqli_real_escape_string($banco->id_connection, $dados["body"]); 
            $jsonData = json_encode($dados["body"]);
        }


        $sql = "INSERT INTO AMB_EMAIL(REMETENTE, DESTINATARIO ,ASSUNTO, CORPO, ANEXO, `STATUS`, ORIGEM, ID_ORIGEM, MSG, USERINSERT) VALUES ('";
        $sql .=  $dados["remetente"] . "', '";
        $sql .=  $dados["destinatario"] . "', '";
        $sql .=  $dados["assunto"] . "', '";
        $sql .=  $jsonData. "', '";
        $sql .=  $finalAnexo . "', '";
        $sql .=  $dados["status"] . "', '";
        $sql .=  $dados["origem"] . "', ";
        $sql .=  $dados["id_novo_acompanhamento"] . ", '";
        $sql .=  $dados["msg"] . "', ";
        $sql .=  $this->m_userid . ");";
        
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql_lower_case($sql);
        $banco->close_connection();

        if($banco->result){
            return $banco->result;
        }else{
            return $banco->resultado;
        }
        
    }// insertContatoCliente

    /**
    * @name insertAcompanhamentoFromEmail
    * @param VARCHAR array
    * @return true se verdadeiro ou erro
    */
    public function insertAcompanhamentoFromEmail($dados=null, $param=null){

        //monta a descricao com anexo
        // if(!empty($dados["anexos"])){
        //     $at = null;
        //     foreach($dados["anexos"] as $anexo){
                
        //         $partes = explode('/', $anexo);
        //         $ultima_parte = end($partes);

        //         if($at !== null){
        //             $at .= " | " .  $ultima_parte;
        //         }else{
        //             $at = $ultima_parte;
        //         }
        //     }

        //     $dados["descAcompanhamento"] .= "\r\n" . "ANEXO(S) ENVIADO(S): ". $at;
        //     unset($at);
        // }

        //monta descricao com mais de um destinatario
        // if(!empty($dados["destinatario"])){
        //     $at = null;
        //     $destis = explode(';',$dados["destinatario"]);
        //     foreach($destis as $dest){
        //         $dest = trim($dest);
        //         $partes = explode('/', $dest);
        //         $ultima_parte = end($partes);

        //         if($at !== null){
        //             $at .= " | " . $ultima_parte;
        //         }else{
        //             $at = $ultima_parte;
        //         }
                 
        //     }

        //     $dados["descAcompanhamento"] .= "\r\n" . "DESTINATARIO(S): ". $at;
        //     unset($at);
        // }

        if($param !== 'saved'){
            $dados["descAcompanhamento"] .= "\r\n" . "DATA e HORA ENVIO: ". $dados["data_hora"];
            $dados["descAcompanhamento"] .= "\r\n" . "STATUS ENVIO: ". $dados["status"];
        }


        //sets do acompanhamento
        $this->setPessoa($dados["id_pessoa"]);
        $this->setAcao(3);
        $this->setVendedorAcomp($this->m_userid);
        $this->setResultContato($dados["descAcompanhamento"]);

        //status do acompanhamento
        if($dados["status"] == 'ENVIADO'){
            $this->setStatus(3);
        }elseif($dados["status"] == 'ABERTO'){
            $this->setStatus(1);
        }else{
            $this->setStatus(2);
        }

        $this->setProximoContato(null);
        $this->setDataContato($dados["data_hora"]);
        
        $result = $this->incluiPessoaAcompEmail();
        if(is_int($result)){
            return $result;
        }else{
            return false;
        }
    }// insertAcompanhamentoFromEmail

    /**
     * Funcao para incluir no Banco
     * @name incluiPessoaAcomp
     * @return string vazio se ocorrer com sucesso
     */
    public function incluiPessoaAcompEmail() {
        $proximoContato = $this->getProximoContato('B');
      
        $sql = "INSERT INTO fin_cliente_acomp (pessoa, pedido_id, data, atividade, resultado, usrvendedor, ligardia, veiculo, origem, destino, km, userinsert, dateinsert, status) ";
        $sql .= "VALUES (" . $this->getPessoa() . ", ";

        if($this->getIdPedido() == ''){
            $sql .= "null, ";
        }else{
            $sql .= "'" .$this->getIdPedido() . "', ";
        }

        if($this->getDataContato() == null){
            $sql .= "null, '";
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
            $sql .= "NOW(), ";
        }else{
            $sql .= "'". $this->getDateInsert('') . "', ";
        }
        //status
        if($this->getStatus() == ''){
            $sql .= "'1');";
        }else{
            $sql .= "'". $this->getStatus() . "'); ";
        }
        
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        if ($banco->resultado == true) {
            return $banco->insertReg;
        } else {
            return false;
        }
    } // incluiPessoaAcomp

    /**
    * @name selectBuscaTemplate
    * @param int|string $id ID do registro em amb_template
    * @return ARRAY todas as colunas da table
    */
    public function selectBuscaTemplate($id){
        $sql = "SELECT * ";
        $sql .= "FROM amb_template ";
        $sql .= "WHERE (ID = " . $id . ") ";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // selectBuscaTemplate

    /**
    * @name selectBuscaTemplate
    * @param VARCHAR $param(descricao template)
    * @return ARRAY todas as colunas da table
    */
    /*public function selectBuscaEmail($id){
        $sql = "SELECT * ";
        $sql .= "FROM amb_email ";
        $sql .= "WHERE (ID_ORIGEM = " . $id . ") ";
        //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } // selectBuscaTemplate
    */
}//	END OF THE CLASS
?>
