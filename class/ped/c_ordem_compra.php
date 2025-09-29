<?php

/**
 * @package   astec
 * @name      c_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      29/04/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_produto.php");


Class c_ordemCompra extends c_user {

    /**
     * TABLE NAME EST_ORDEM_COMPRA
     */
    private $id = NULL; 
    private $oc = NULL;  // ORDEM COMPRA
    private $cliente = NULL; 
    private $clienteNome = NULL; 
    private $pedido = NULL; 
    private $situacao = NULL; 
    private $emissao = NULL; 
    private $condPg = NULL; 
    private $obs = NULL; 
    private $centroCusto = NULL; 
    private $desconto = NULL; 
    private $produtos = NULL;    
    private $total = NULL; 

    private $idNatop = NULL;
    
    private $nrItem = NULL; 
    
    private $itemEstoque = NULL; 
    private $itemFabricante = NULL;  
    private $qtSolicitada = NULL;
    private $unitario = NULL;
    private $totalItem = NULL;
    private $descricaoItem = NULL;

    //construtor
    function __construct(){
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);
    }

    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }

    function setOc($oc) { $this->oc = $oc; }
    function getOc() { return $this->oc; }


    function setCliente($cliente) { $this->cliente = $cliente; }
    function getCliente() { return $this->cliente; }

    function setClienteNome() {
        $pessoa = new c_conta();
        $pessoa->setId($this->getCliente());
        $reg_nome = $pessoa->select_conta();
        $this->clienteNome = $reg_nome[0]['NOME'];
        $this->tipoPessoa = $reg_nome[0]['PESSOA'];
        $this->ufPessoa = $reg_nome[0]['UF'];
    }    
    function getClienteNome() { return $this->clienteNome; }   
    
    function setPedido($pedido) { $this->pedido = $pedido; }
    function getPedido() { 
        return isset($this->pedido) ? $this->pedido : 'NULL';  }

    function setSituacao($situacao) { $this->situacao = $situacao; }
    function getSituacao() { return $this->situacao; }

    function setEmissao($emissao) { $this->emissao = $emissao; }
    function getEmissao($format = NULL) {
        return c_date::formatDateTime($format, $this->emissao, false);
    }

    function setCondPg($condPg) {
        $this->condPg = $condPg;
        }
    function getCondPg() { 
        return $this->condPg; 
       // return isset($this->condPg) ? $this->condPg : 0;
    }
    
    function setObs($obs) { $this->obs = $obs; }
    function getObs() { return $this->obs; }
    
    function setCentroCusto($centroCusto) { $this->centroCusto = $centroCusto; }
    function getCentroCusto() { return $this->centroCusto; }
    
    function setDesconto($desconto, $format=false) {
        $this->desconto = $desconto; 
        if ($format):
                $this->desconto = number_format($this->desconto, 2, ',', '.');
        endif;
        
    }
    
    function getDesconto($format = NULL) {
        if (!empty($this->desconto)) {
            if ($format == 'F') {
                return number_format($this->desconto, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->desconto);
            }
        } else {
            return 0;
        }        
    }

    function setProdutos($produtos, $format=false) { 
        $this->produtos = $produtos; 
        if ($format):
                $this->produtos = number_format($this->produtos, 2, ',', '.');
        endif;
    }
    function getProdutos($format = NULL) {
        if (!empty($this->produtos)) {
            if ($format == 'F') {
                return number_format($this->produtos, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->produtos);
            }
        } else {
            return 0;
        }        
    }

    function setTotal($total, $format=false) { 
        $this->total = $total; 
        if ($format):
                $this->total = number_format($this->total, 2, ',', '.');
        endif;
    }
    function getTotal($format = NULL) {
        if (!empty($this->total)) {
            if ($format == 'F') {
                return number_format($this->total, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->total);
            }
        } else {
            return 0;
        }        
    }

    function setNrItem($nrItem) { $this->nrItem = $nrItem; }
    function getNrItem() { return $this->nrItem; }

    function setIdNatop($idNatop) { $this->idNatop = $idNatop; }
    function getIdNatop()  { return isset($this->idNatop) ? $this->idNatop : 'NULL'; }
    
    function setItemEstoque($itemEstoque) { $this->itemEstoque = $itemEstoque; }
    function getItemEstoque() { return $this->itemEstoque; }

    function setItemFabricante($itemFabricante) { $this->itemFabricante = $itemFabricante; }
    function getItemFabricante() { return $this->itemFabricante;  }

    function setQtSolicitada($qtSolicitada) { $this->qtSolicitada = $qtSolicitada;   }
    function getQtSolicitada($format = NULL) {
        if (!empty($this->qtSolicitada)) {
            return $this->qtSolicitada;
        } else {
            return 0;
        }
    }

    function setUnitario($unitario) { $this->unitario = $unitario; }
    function getUnitario($format = NULL) {
        if (!empty($this->unitario)) {
            if ($format == 'F') {
                return number_format($this->unitario, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->unitario);
            }
        } else {
            return 0;
        }
    }
    
    function setTotalItem() {
        $this->totalItem = str_replace('.', ',', ($this->getQtSolicitada() * $this->getUnitario('B')) - $this->getDesconto('B'));
    }
    function getTotalItem($format = NULL) {
        if (!empty($this->totalItem)) {
            if ($format == 'F') {
                return number_format($this->totalItem, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->totalItem);
            }
        } else {
            return 0;
        }
    }

    function setDescricaoItem($descricaoItem) { $this->descricaoItem = $descricaoItem;}    
    function getDescricaoItem() { return $this->descricaoItem; }

    /**
     * Funcao para setar todos os objetos da classe
     * @name setPedidoVenda
     * @param INT GetId chave primaria da table pedidos
     */
    public function setOrdemCompra() {

        $ordemCompra = $this->select_ordem_compra_id();
        $this->setId($ordemCompra[0]['ID']);
        $this->setCliente($ordemCompra[0]['CLIENTE']);
        $this->setClienteNome($ordemCompra[0]['NOME']);
        $this->setPedido($ordemCompra[0]['PEDIDO']);
        $this->setSituacao($ordemCompra[0]['SITUACAO']);
        $this->setEmissao($ordemCompra[0]['EMISSAO']);
        $this->setDesconto($ordemCompra[0]['DESCONTO']);
        $this->setTotal($ordemCompra[0]['TOTAL']);
        $this->setObs($ordemCompra[0]['OBS']);

        $this->setIdNatop($ordemCompra[0]['IDNATOP']);
        $this->setCondPg($ordemCompra[0]['CONDPG']);
        $this->setCentroCusto($ordemCompra[0]['CCUSTO']);
        
        $this->setProdutos($ordemCompra[0]['TOTALPRODUTOS']);
    }



    /**
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @return ARRAY todos os campos da table
     * @version 20161004
     */
    public function select_ordem_compra_id($situacao = NULL) {

        $sql = "SELECT DISTINCT p.*, c.nome, c.nomereduzido, c.pessoa, c.fonearea, c.fone, c.celular, c.fonecontato, c.tipoend, c.tituloend, c.pessoa, ";
        $sql .= "if (pessoa='J',";
        $sql .= "CONCAT(SUBSTRING(cnpjcpf, 1,2), '.', SUBSTRING(cnpjcpf,3,3), '.', SUBSTRING(cnpjcpf,6,3), '/', SUBSTRING(cnpjcpf,9,4), '-', SUBSTRING(cnpjcpf,13, 2)), 
                CONCAT(SUBSTRING(cnpjcpf, 1,3), '.', SUBSTRING(cnpjcpf,4,3), '.', SUBSTRING(cnpjcpf,7,3), '-', SUBSTRING(cnpjcpf,10, 2))";
        $sql .= ") AS cnpjcpf, ";        
        $sql .= "c.endereco, c.numero, c.complemento, c.bairro, c.cidade, c.uf, c.cep, c.email, t.descricao as descpgto ";
        $sql .= "FROM est_ordem_compra p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "LEFT join fat_cond_pgto t on t.id = p.condpg ";
        $sql .= "WHERE (p.id = " . $this->getId() . ") ";

        if ($situacao == '0') {
            $sql .= "and (situacao ='" . $situacao . "') ";
        }
        $sql .= "ORDER BY id;";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    /**
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @return ARRAY todos os campos da table
     * @version 20161004
     */
    public function max_pedidoVendaAberto($situacao = NULL) {

        $sql = "SELECT max(id) as id, cliente, pedido, SITUACAO, TOTAL, TAXAENTREGA, DESCONTO FROM FAT_PEDIDO  ";
        $sql .= "where (situacao=0) and (userinsert=". $this->m_userid.") and (ccusto=".$this->m_empresacentrocusto.")";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @return ARRAY todos os campos da table
     */
    public function select_pedidoVenda_situacao($situacao) {

        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM fat_pedido ";
        $sql .= "where (situacao ='" . $situacao . "') ";
        $sql .= "ORDER BY id;";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_pedido_venda_letra_situacao($letra) {
        $par = explode("|", $letra);
        $letra = implode(",", $par);
        $sql = "SELECT p.*, D.PADRAO ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAO) AND (D.ALIAS='FAT_MENU') AND (D.CAMPO='SITUACAOPEDIDO')) ";
        $sql .= "where (p.cliente = " . $par[0] . ") and (p.situacao in (";
        for ($i = 1; $i < count($par); $i++) {
            if ($i == 1) {
                $sql .= "'" . $par[$i] . "'";
            } else {
                $sql .= ",'" . $par[$i] . "'";
            }
        }
        $sql .=")) ";
        $sql .= "and (P.ccusto = '" . $this->m_empresacentrocusto . "') ";
        $sql .= "order by P.situacao, P.emissao;";
        // echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta para o Banco atraves de parametros
     * @name select_pedidoVenda_letra
     * @return ARRAY todos os campos da table
     * @version 20161006
     */
    public function select_pedidoVenda_letra($letra) {
        /*
         * [0] = data inicio
         * [1] = data FIm
         * [2] = cliente
         * [3] = vendedor
         * [4] = produto        
         * [5] = reservado        
         * [6] = reservado        
         * [7] = situacao        
         */
        $isWhere = true;
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT p.*, D.PADRAO, C.NOMEREDUZIDO, C.NOME, C.USERLOGIN ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAO) AND (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')) ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=P.CLIENTE) ";
        if ($par[4] != '') {
            $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID=P.ID) AND (I.ITEMESTOQUE= ".$par[4].")";
            }
        if ($par[0] != '') {
            if ($isWhere) {
                $sql .= "where (p.emissao >= '" . $dataIni . "') ";
                $isWhere = false;
            } else {
                $sql .= "(p.emissao >= '" . $dataIni . "') ";
            }
        }//if
        if ($par[1] != '') {
            if ($isWhere) {
                $sql .= "where (p.emissao <= '" . $dataFim . "') ";
                $isWhere = false;
            } else {
                $sql .= "AND (p.emissao <= '" . $dataFim . "') ";
            }
        }
        if ($par[2] != '') {
            if ($isWhere) {
                $sql .= "where (p.cliente =" . $par[2] . ") ";
                $isWhere = false;
            } else {
                $sql .= "and (p.cliente =" . $par[2] . ") ";
            }
        }
        if ($par[3] != '') {
            if ($isWhere) {
                $sql .= "where (p.usrpedido = '" . $par[3] . "') ";
                $isWhere = false;
            } else {
                $sql .= "AND (p.usrpedido = '" . $par[3] . "') ";
            }
        }
        if (($par[7] != '') and ($par[7] != '0')) {
            if ($isWhere) {
                $sql .= "where (p.situacao in (";
                $isWhere = false;
            } else {
                $sql .= "AND (p.situacao in (";
            }
            
            for ($i = 8; $i < count($par); $i++) {
                if ($i == 8) {
                    $sql .= "'" . $par[$i] . "'";
                } else {
                    $sql .= ",'" . $par[$i] . "'";
                }
            }
            $sql .=")) ";
        }


       // $sql .= "ORDER BY p.situacao, p.emissao, p.cliente ";
       // $sql .= "ORDER BY c.nome ";
        $sql .= "ORDER BY p.emissao Desc";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * <b> Seleciona as oportunidades na tabela fat_pedido: p_pessoa_oportunidade </b>
     * @name select_oportunidade_letra
     * @param String $letra dataInicio|dataFim|nome cliente|vendedor|situacaoOprt|Numoportunidade
     * @return Array listagem conforme SQL
     */
    public function select_oportunidade_letra($letra) {
        /*
         * [0] = data inicio
         * [1] = data FIm
         * [2] = nome cliente
         * [3] = vendedor
         * [4] = numPedido        
         * [5] = situacao        
         */
        $isWhere = true;
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT p.*, c.nome, D.PADRAO, 0 AS ALERTA ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "inner join amb_usuario u on u.usuario = p.usrpedido ";
        $sql .= "INNER JOIN AMB_DDM D ON ((D.TIPO=P.SITUACAOOPORTUNIDADE) AND (alias='FIN_MENU') and (campo='Oportunidade')) ";
        if (($par[4] == '')) {
            if ($par[0] != '') {
                if ($isWhere) {
                    $sql .= "where (p.emissao >= '" . $dataIni . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "(p.emissao >= '" . $dataIni . "') ";
                }
            }//if
            if ($par[1] != '') {
                if ($isWhere) {
                    $sql .= "where (p.emissao <= '" . $dataFim . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (p.emissao <= '" . $dataFim . "') ";
                }
            }
            if ($par[2] != '') {
                if ($isWhere) {
                    $sql .= "where (c.nome like '%" . $par[2] . "%') ";
                    $isWhere = false;
                } else {
                    $sql .= "and (c.nome like '%" . $par[2] . "%') ";
                }
            }
            if ($par[3] != '0') {
                if ($isWhere) {
                    $sql .= "where (p.usrpedido = '" . $par[3] . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (p.usrpedido = '" . $par[3] . "') ";
                }
            }
            if ($par[5] != '0') {
                $sitMostra = array(); // array que vai guardar as situacoes selecionadas
                for ($i = 6; $i < count($par); $i++) {
                    $sitMostra[] = "'" . $par[$i] . "'";
                }
                $sitImplode = implode(",", $sitMostra);
                if ($isWhere) {
                    $sql .= "where (p.situacaooportunidade in (" . $sitImplode . ")) ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (p.situacaooportunidade in (" . $sitImplode . ")) ";
                }
            }
        } else {
            if ($par[4] != '') {
                if ($isWhere) {
                    $sql .= "where (p.id = '" . $par[4] . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (p.id = '" . $par[4] . "') ";
                }
            }
        }



        $sql .= "ORDER BY p.DATAALTERACAO,P.SITUACAOOPORTUNIDADE ,p.cliente ";
        // echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

// fim select_pedidoVenda_letra

    public function select_pedidoVenda_entrega($letra) {


        $par = explode("|", $letra);

        $sql = "SELECT p.*, c.nome FROM fat_pedido p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "inner join amb_usuario u on u.usuario = p.usrpedido ";
        $sql .= " ";
        //echo 'letra'.$letra;
        if (($letra != '|||') and ( $letra != '')) {
            $sql .= "WHERE ";
        }
        if ($par[0] != '') {
            $sql .= "(p.cliente = " . $par[0] . ") ";
        }
        if ($par[1] != '') {
            if ($par[0] != '') {
                $sql .= "AND (p.situacao = '" . $par[1] . "') ";
            } else {
                $sql .= "(p.situacao = '" . $par[1] . "') ";
            }
        }
        if ($par[2] != '') {
            if (($par[0] != '') or ( $par[1] != '')) {
                $sql .= "AND (p.usrpedido = '" . $par[2] . "') ";
            } else {
                $sql .= "(p.usrpedido = '" . $par[2] . "') ";
            }
        }
        $sql .= "ORDER BY p.dataentrega ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

// fim select_pedidoVenda_entrega
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_pedidoVendaComissao($letra) {


        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT i.id, p.emissao, c.nomereduzido, e.descricao, i.total as totalvenda, i.financeiro, ";
        $sql .= "(SELECT sum(custototal) FROM fat_pedido_item_comp c WHERE ((c.id = i.id) and (c.itempedido = i.itemestoque))) as totalcusto ";
        $sql .= "FROM fat_pedido_item i ";
        $sql .= "inner join est_produto e on e.codigo = i.itemestoque ";
        $sql .= "inner join fat_pedido p on p.id = i.id ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= " ";
        if ($letra != '||||') {
            $sql .= "WHERE ";
        }
        if ($par[0] != '') {
            $sql .= "(p.emissao >= '" . $dataIni . "') ";
        }
        if ($par[1] != '') {
            if ($par[0] != '') {
                $sql .= "AND (p.emissao <= '" . $dataFim . "') ";
            }
        }
        if ($par[2] != '') {
            if (($par[0] != '') or ( $par[1] != '')) {
                $sql .= "AND (p.usrpedido = '" . $par[2] . "') ";
            } else {
                $sql .= "(p.usrpedido = '" . $par[2] . "') ";
            }
        }

        // Multiplo
        if ($par[3] != '') {
            $situacao = true;
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '')) {
                $sql .= "AND (";
            }
            $sql .= "(p.situacao = '" . $par[3] . "') ";
        }

        if ($situacao == true) {
            for ($p = 4; $p < count($par); $p++) {
                if ($par[$p] != '') {
                    $sql .= "OR (p.situacao = '" . $par[$p] . "')";
                }
            }
            $sql .= ") ";
        }


        $sql .= "ORDER BY i.id ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

// fim select_pedidoVenda_Comissao
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function select_pedidoVendaClientesPeriodo($letra) {


        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT distinct c.nome, c.fonearea, c.fone, c.fonecontato, ";
        $sql .= "(SELECT sum(total) FROM fat_pedido s WHERE (p.cliente = s.cliente) and (s.emissao >= '" . str_replace("/", ".", $par[0]) . "') AND (s.emissao <= '" . str_replace("/", ".", $par[1]) . "')) as total ";
        $sql .= "FROM fat_pedido p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= " ";
        if ($letra != '||||') {
            $sql .= "WHERE ";
        }
        if ($par[0] != '') {
            $sql .= "(p.emissao >= '" . $dataIni . "') ";
        }
        if ($par[1] != '') {
            if ($par[0] != '') {
                $sql .= "AND (p.emissao <= '" . $dataFim . "') ";
            }
        }
        if ($par[2] != '') {
            if (($par[0] != '') or ( $par[1] != '')) {
                $sql .= "AND (p.usrpedido = '" . $par[2] . "') ";
            } else {
                $sql .= "(p.usrpedido = '" . $par[2] . "') ";
            }
        }

        // Multiplo
        if ($par[3] != '') {
            $situacao = true;
            if (($par[0] != '') or ( $par[1] != '') or ( $par[2] != '')) {
                $sql .= "AND (";
            }
            $sql .= "(p.situacao = '" . $par[3] . "') ";
        }

        if ($situacao == true) {
            for ($p = 4; $p < count($par); $p++) {
                if ($par[$p] != '') {
                    $sql .= "OR (p.situacao = '" . $par[$p] . "')";
                }
            }
            $sql .= ") ";
        }


        $sql .= "ORDER BY TOTAL DESC ";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Calcula a quantidade do do item do cliente dentro do mes corrente
     * @name selectQuantPedidoItem
     * @return quantidade total do item
     */
    public function selectQuantPedidoItem($pessoa, $produto, $conn=null) {

        if ($pessoa != ''):
            $sql = "SELECT sum(i.qtsolicitada) as quant ";
            $sql .= "FROM fat_pedido_item i ";
            $sql .= "inner join FAT_PEDIDO p on (p.id=i.id) ";
            $sql .= "WHERE (i.itemestoque =".$produto.") and (p.cliente=".$pessoa.") and (p.SITUACAO<>0) and ";
            $sql .= "(i.precopromocao > 0) and ";
            $sql .= "(date_format(emissao, '%Y%m')= date_format(curdate(), '%Y%m')) ";
            //echo strtoupper($sql)."<BR>";

            $banco = new c_banco;
            $res_quant = $banco->exec_sql($sql, $conn);
            $banco->close_connection();
            if (is_array($res_quant)): 
                return $banco->resultado[0]['QUANT'];
             else: 
                return 0;

            endif;
        else:
            return 0;
        endif;
    }

// fim select_pedidoVenda_ClientePeriodo
    /**
     * Calcula o total do pedido atraves do id
     * @name select_ordem_compra_total
     * @return ARRAY total do pedido
     */
    public function select_ordem_compra_total() {

        if ($this->getId() != ''):
            $sql = "SELECT sum(total) as totalordemcompra ";
            $sql .= "FROM est_ordem_compra_item ";
            $sql .= "WHERE (id = " . $this->getId() . ") ";
            
            $banco = new c_banco;
            $res_pedidoVenda = $banco->exec_sql($sql);
            $banco->close_connection();
            if ($res_pedidoVenda > 0): {
                    return $banco->resultado[0]['TOTALORDEMCOMPRA'];
                } else: {
                    return 0;
                }
            endif;
        else:
            return 0;
        endif;
    }

// fim select_pedidoVenda_ClientePeriodo
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function incluiOrdemCompra() {

        $banco = new c_banco;
        // $banco->sqlStrtoupper = false;

        if ($banco->gerenciadorDB == 'interbase') {
            $this->setId($banco->geraID("FAT_GEN_ID_ORDEM_COMPRA"));
            $sql = "INSERT INTO EST_ORDEM_COMPRA (ID, ";
        } else {
            $sql = "INSERT INTO EST_ORDEM_COMPRA (";
        }

        $sql .= "CLIENTE,EMISSAO,CONDPG,OBS,CCUSTO, ";
        $sql .= "DESCONTO,PRODUTOS,TOTAL,USERINSERT,DATEINSERT) ";
        
        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }

        $sql .=   $this->getCliente() . "','"
                . $this->getEmissao('B') . "', '"
                . $this->getCondPg() . "', '"
                . $this->getObs() . "', '"
                . $this->getCentroCusto() . "', "
                . $this->getDesconto('B') . ", "
                . $this->getProdutos('B') . ", "
                . $this->getTotal('B') . ", ";
        $sql .= $this->m_userid.",'".date("Y-m-d H:i:s"). "' );";
        //echo strtoupper($sql) . "<BR>";
        $res_pedidoVenda = $banco->exec_sql($sql);
        $lastReg = $banco->insertReg;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da ordem de compra ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

// fim incluiPedido
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraOrdemCompraTotal() {

        $sql = "UPDATE EST_ORDEM_COMPRA ";
        $sql .= "SET total = " . $this->getTotal() . ", ";        
        $sql .= "situacao = '" . $this->getSituacao() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o da ordem de compra ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoSituacao
     * @return NULL quando ok ou msg erro
     */
    public function alteraPedidoSituacao($condPgto = null, $conn=null) {

        $sql = "UPDATE fat_pedido ";
        $sql .= "SET situacao = '" . $this->getSituacao() . "' ";
        if (!is_null($condPgto)):
            $sql .= ", condpg = " . $this->getCondPg() . " ";
        endif;
        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraPedidoNf
     * @return NULL quando ok ou msg erro
     */
    public function alteraPedidoNf() {

        $sql = "UPDATE fat_pedido SET ";
        $sql .= "serie = '" . $this->getSerie() . "', ";
        $sql .= "idnatop = '" . $this->getIdNatop() . "', ";
        $sql .= "condpg = " . $this->getCondPg() . ", ";
        $sql .= "genero = '" . $this->getGenero() . "', ";
        $sql .= "contadeposito = '" . $this->getContaDeposito() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        //	$res_pedidoVenda =  $banco->m_cmdTuples ;
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o do Pedido ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }


    
    public function excluiOrdemCompra() {

        $sql = "DELETE FROM ";
        $sql .= "est_ordem_compra ";
        $sql .= "WHERE (id = " . $this->getId() . "); ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function excluiItemOrdemCompra() {

        $sql = "DELETE FROM ";
        $sql .= "est_ordem_compra_item ";
        $sql .= "WHERE (id = " . $this->getId() . "); ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_pedido_venda_item_letra($letra) {
        $par = explode("|", $letra);
        $data = date("Y-m-d");
        $isWhere = false;
        $sql = "SELECT * ";
        $sql .= "FROM est_produto ";
        if (!empty($par[0])) {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
//            $sql .= "(descricao like '%" . $par[0] . "%') ";
            $sql .= "(descricao like '" . $par[0] . "%') ";
        }
        if (!empty($par[1])) {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
            $sql .= "(grupo = '" . $par[1] . "') ";
        }

        if ($par[2] == 'S') {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
            $sql .= "((iniciopromocao <= '" . $data . "') and (fimpromocao >= '" . $data . "'))";
        }
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * ****************************************
     * ******* Funções Pedido Itens ***********
     * ****************************************
     */

    /**
     * Funcao para setar todos os objetos da classe de acordo com consulta no banco
     * @param INT ID Chave primaria da table fat_pedido
     * @param SMALLINT NRITEM chave primaria para a table fat_pedido_item
     * @name pedido_venda_item
     * @return Todos os objetos da classe setados
     */
    public function pedido_venda_item($select=true, $arr='') {
        if ($select):
            $item = $this->select_pedido_item_id_nritem();
        else:
            $item = $arr;
        endif;
        $this->setId($item[0]['ID']);
        $this->setNrItem($item[0]['NRITEM']);
        $this->setItemEstoque($item[0]['ITEMESTOQUE']);
        $this->setItemFabricante($item[0]['ITEMFABRICANTE']);
        $this->setQtSolicitada(number_format($item[0]['QTSOLICITADA'], 4, ',', '.'));
        $this->setUnitario(number_format($item[0]['UNITARIO'], 4, ',', '.'));
        $this->setPrecoPromocao($item[0]['PRECOPROMOCAO']);
        $this->setVlrTabela(number_format($item[0]['VLRTABELA'], 2, ',', '.'));
        $this->setTotalItem(number_format($item[0]['TOTAL'], 2, ',', '.'));
        $this->setGrupoEstoque($item[0]['GRUPOESTOQUE']);
        $this->setDescricaoItem($item[0]['DESCRICAO']);

        return $item;
/*        $this->setQtAtendida(number_format($item[0]['QTATENDIDA'], 4, ',', '.'));
        $this->setAtendimento($item[0]['ATENDIMENTO']);
        $this->setDescontoItem(number_format($item[0]['DESCONTO'], 2, ',', '.'));
        $this->setFinanceiro(number_format($item[0]['FINANCEIRO'], 2, ',', '.'));
        $this->setFabricanteItem($item[0]['FABRICANTE']);
        $this->setAliqIpi(number_format($item[0]['ALIQIPI'], 2, ',', '.'));
        $this->setPeso(number_format($item[0]['PESO'], 4, ',', '.'));
        $this->setPrazo($item[0]['PRAZO']);
        $this->setAliqDesconto(number_format($item[0]['ALIQDESCONTO'], 2, ',', '.'));
        $this->setComissao(number_format($item[0]['COMISSAO'], 2, ',', '.'));
        $this->setBaseComissao(number_format($item[0]['BASECOMISSAO'], 2, ',', '.'));
        $this->setPrecoSelecionado($item[0]['PRECOSELECIONADO']);
        $this->setAutorizDesconto($item[0]['AUTORIZDESCONTO']);
        $this->setDescontoMaxAConceder(number_format($item[0]['DESCONTOMAXACONCEDER'], 2, ',', '.'));
        $this->setPerDesconto(number_format($item[0]['PERCDESCONTO'], 2, ',', '.'));
        $this->setDescontoOutros(number_format($item[0]['DESCONTOOUTROS'], 2, ',', '.'));
        $this->setQtConferida(number_format($item[0]['QTCONFERENCIA'], 4, ',', '.'));
        $this->setPercFinanceiro(number_format($item[0]['PERCFINANCEIRO'], 2, ',', '.'));*/
    }

    /**
     * Funcao de consulta ao banco de dados de acordo com as chaves Primarias: ID e NRITEM
     * @param INT ID Chave primaria da table fat_pedido
     * @param SMALLINT NRITEM chave primaria para a table fat_pedido_item
     * @name select_pedido_item_id_nritem
     * @return ARRAY todos as colunas da table fat_pedido_item
     */
    public function select_ordem_compra_item_id_nritem() {
        $sql = "SELECT i.* FROM ";
        $sql .= "est_ordem_compra_item i ";
        $sql .= "left join est_produto e on (e.codigo=i.itemestoque) ";
        $sql .= "WHERE (i.id = '" . $this->getId() . "') AND ";
        $sql .= "(i.nritem = '" . $this->getNrItem() . "');";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta para o Banco atraves do id
     * @name select_pedidoVenda
     * @param INT GetId Chave primaria da tabela fat_pedido_item
     * @return ARRAY todos os campos da table
     */
    public function select_ordem_compra_item_max_nritem($conn=null) {

        $sql = "SELECT max(nritem) as maxnritem ";
        $sql .= "FROM EST_ORDEM_COMPRA_ITEM ";
        $sql .= "WHERE (id = " . $this->getId() . ") ";
        $sql .= "ORDER BY id";
        
        $banco = new c_banco;
        $banco->exec_sql($sql,$conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Funcao de consulta ao banco de dados de acordo com o id da table fat_pedido_item
     * @name select_pedido_item_id
     * @param INT ID Chave primaria da table fat_pedido
     * @return ARRAY todos as colunas da table fat_pedido_item
     * @version 20161004
     */
    public function select_ordem_compra_item_id($tipoConsulta=NULL) {
        
        switch ($tipoConsulta){
            case '1': // group by com lote e data fab
                $sql = "SELECT i.*, count(i.ITEMESTOQUE) as quantidade, e.fablote, e.fabdatavalidade, e.fabdatafabricacao, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras FROM ";
                $sql .= "fat_pedido_item i ";
                $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                $sql .= "inner join est_produto_estoque e on (e.idpedido = i.id and e.codproduto=i.itemestoque) ";
                $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
                $sql .= "group by i.ITEMESTOQUE, e.FABLOTE, e.fabdatavalidade; ";
                break;
            case '2': // group by sem lote e data fab
                $sql = "SELECT i.*, count(i.ITEMESTOQUE) as quantidade, e.fablote, e.fabdatavalidade, e.fabdatafabricacao, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras FROM ";
                $sql .= "fat_pedido_item i ";
                $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                $sql .= "LEFT join est_produto_estoque e on (e.idpedido = i.id and e.codproduto=i.itemestoque) ";
                $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
                $sql .= "group by i.ITEMESTOQUE; ";
                break;
            default: // sem lote e data fab
                $sql = "SELECT i.*, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras FROM ";
                $sql .= "est_ordem_compra_item i ";
                $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
                $sql .= "LEFT JOIN EST_GRUPO G ON (G.GRUPO=P.GRUPO)  ";
                $sql .= "WHERE (i.id = '" . $this->getId() . "'); ";
        }
        
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_ordem_compra_item_id_itemestoque($conn=null) {
        $sql = "SELECT i.* FROM ";
        $sql .= "EST_ORDEM_COMPRA_ITEM as i ";
        $sql .= "WHERE (i.id = '" . $this->getId() . "') ";
        $sql .= "and (i.itemestoque='" . $this->itemEstoque . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql,$conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_ordem_compra_produto($conn=null) {
        $sql = "SELECT * FROM EST_PRODUTO ";
        $sql .= "WHERE (codigo='" . $this->itemEstoque . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql,$conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Funcao para inclusão do registro no banco de dados
     * @name IncluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function IncluiOrdemCompraItem($conn=null) {
        $banco = new c_banco;
        // "apssou inclui item<br>";

        $sql = "INSERT INTO EST_ORDEM_COMPRA_ITEM (";

        $sql .= "id, nritem, oc, itemestoque, itemfabricante, qtsolicitada, ";
        $sql .= "unitario, desconto, total, descricao ) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .=   $this->getId() . "', '"
                . $this->getNrItem() . "', "
                . $this->getOc() . ", '"
                . $this->getItemEstoque() . "', '"
                . $this->getItemFabricante() . "', "
                . $this->getQtSolicitada('B') . ", "
                . $this->getUnitario('B') . ", "
                . $this->getDesconto('B') . ", "
                . $this->getTotalItem('B') . ", '"
                . $this->getDescricaoItem() ."'); ";
 
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = $banco->insertReg;
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da ordem compra ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }
    /**
     * Funcao para alterar a situacao do pedido
     * @param INT ID Chave primaria da table fat_pedido
     * @param CHAR(1) SITUACAO nova situacao a ser alterada
     * @name alteraOrdemCompra
     * @return NULL quando ok ou msg erro
     */
    public function alteraOrdemCompra() {

        $sql = "UPDATE EST_ORDEM_COMPRA ";
        $sql .= "SET total = " . $this->getTotal() . ", ";
        $sql .= "cliente = '" . $this->getCliente() . "', ";
        $sql .= "condpg = '" . $this->getCondPg() . "', ";        
        $sql .= "situacao = '" . $this->getSituacao() . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql);
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A situac&atilde;o da ordem de compra ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }
    

    /**
     * Funcao para alterar um registro no banco de dados
     * @name alteraPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraOrdemCompraItem($conn=null) {

        $sql = "UPDATE EST_ORDEM_COMPRA_ITEM ";
        $sql .= "SET id = '" . $this->getId() . "', ";
        $sql .= "nritem = '" . $this->getNrItem() . "', ";
        $sql .= "oc = '" . $this->getOc() . "', ";
        $sql .= "itemestoque = '" . $this->getItemEstoque() . "', ";
        $sql .= "itemfabricante = '" . $this->getItemFabricante() . "', ";
        $sql .= "qtsolicitada = " . $this->getQtSolicitada('B') . ", ";
        $sql .= "unitario = " . $this->getUnitario('B') . ", ";
        $sql .= "desconto = " . $this->getDesconto('B') . ", ";
        $sql .= "total = " . $this->getTotalItem('B') . ", ";
        $sql .= "descricao = '" . $this->getDescricaoItem() . "' ";
        
        $sql .= "WHERE (id = '" . $this->getId() . "') AND ";
        $sql .= "(nritem = '" . $this->getNrItem() . "');";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);

        $banco->close_connection();

        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' - Item não Alterado!!!';
        endif;
        return $msg;
    }

    /**
     * Funcao de exclusao do item do pedido, no banco de dados
     * @name excluiPedidoItem
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiOrdemCompraItem($conn=null) {
        $sql = "DELETE FROM ";
        $sql .= "est_ordem_compra_item ";
        $sql .= "WHERE (id = '" . $this->getId() . "') AND ";
        $sql .= "(nritem = '" . $this->getNrItem() . "');";
        
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' Item não localizado para Exclusão!!!';
        endif;
        return $msg;
    }
    /**
     * Funcao de exclusao de todos os itens do pedido, no banco de dados
     * @name excluiPedidoItemGeral
     * @return string vazio se ocorrer com sucesso
     */
    public function excluiPedidoItemGeral() {
        $sql = "DELETE FROM ";
        $sql .= "fat_pedido_item ";
        $sql .= "WHERE (id = '" . $this->getId() . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        $msg = '';
        if ($banco->row <= 0):
            $msg = $this->getNrItem() .' Item não localizado para Exclusão!!!';
        endif;
        return $msg;
    }
    
    
    public function select_ordem_compra($letra) {
        /*
         * [0] = data inicio
         * [1] = data FIm
         * [2] = cliente
         * [3] = vendedor
         * [4] = produto        
         * [5] = reservado        
         * [6] = reservado        
         * [7] = situacao        
         */
        $isWhere = true;
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT O.ID, O.EMISSAO, O.TOTAL, O.CLIENTE, C.NOME ";
        $sql .= "FROM EST_ORDEM_COMPRA O ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=O.CLIENTE) ";
        if ($par[3] != '') {
            $sql .= "where (O.ID =" . $par[3] . ") ";
        } else {
            if ($par[0] != '') {
                if ($isWhere) {
                    $sql .= "where (O.EMISSAO >= '" . $dataIni . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "(O.EMISSAO >= '" . $dataIni . "') ";
                }
            }    
            if ($par[1] != '') {
                if ($isWhere) {
                    $sql .= "where ( O.EMISSAO <= '" . $dataFim . "') ";
                    $isWhere = false;
                } else {
                    $sql .= "AND (O.EMISSAO <= '" . $dataFim . "') ";
                }
            }
            if ($par[2] != '') {
                if ($isWhere) {
                    $sql .= "where (C.CLIENTE =" . $par[2] . ") ";
                    $isWhere = false;
                } else {
                    $sql .= "and (C.CLIENTE =" . $par[2] . ") ";
                }
            }
        }
        
        $sql .= "ORDER BY O.EMISSAO Desc";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

}

//	END OF THE CLASS
?>