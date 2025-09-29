<?php

/**
 * @package   astec
 * @name      c_ordemCompra
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
include_once($dir . "/../../form/est/p_ordem_compra.php");


Class c_ordemCompra extends c_user {

    /**
     * TABLE NAME EST_ORDEM_COMPRA
     */
    private $id = NULL; 
    private $oc = NULL;  // ORDEM COMPRA
    private $cliente = NULL; 
    private $clienteNome = NULL; 
    private $situacao = NULL; 
    private $produtos = NULL; 
    private $emissao = NULL; 
    private $condPg = NULL; 
    private $obs = NULL; 
    private $centroCusto = NULL; 
    private $desconto = NULL; 
    private $total = NULL;
    private $frete = NULL;
    private $despacessorias = NULL;
    private $seguro = NULL;
    private $totalOc = NULL;
    private $situacaoCombo = NULL;

    // EST_ORDEM_COMPRA_ITEM
    private $itemEstoque    = NULL; 
    private $nrItem         = NULL; 
    private $itemFabricante = NULL;  
    private $codigoNota     = NULL;  
    private $qtSolicitada   = NULL;
    private $unitario       = NULL;
    private $descontoItem   = NULL;
    private $percDescontoItem = NULL;
    private $totalItem      = NULL;
    private $descricaoItem  = NULL;
    private $unidade        = NULL;

    // ORDEM DE COMPRA FINANCEIRO
    private $genero = NULL;
    private $dataEntrada = NULL;
    private $serie = NULL; 
    private $numNf = NULL; 
    private $dataEmissao = NULL;   
    private $idNatop = NULL;

    //construtor
    function __construct(){
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);
    }
    
    /* GET SET ORDEM DE COMPRA */
    function setId($id) { $this->id = $id; }
    function getId() { return $this->id; }

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

    function setSituacao($situacao) { $this->situacao = $situacao; }
    function getSituacao() { return $this->situacao; }

    function setSituacaoCombo($situacaoCombo) { $this->situacaoCombo = $situacaoCombo; }
    function getSituacaoCombo() { return $this->situacaoCombo; }

    function setEmissao($emissao) { $this->emissao = $emissao; }
    function getEmissao($format=false) {
        //return c_date::formatDateTime($format, $this->emissao, false);
        $this->emissao = strtr($this->emissao, "/","-");
		switch ($format) {
			case 'F':
				return date('d/m/Y', strtotime($this->emissao)); 
				break;
			case 'B':
                return c_date::convertDateBd($this->emissao, $this->m_banco);
				break;
			default:
				return $this->emissao;
		}        
    }

    function setCondPg($condPg) {
        $this->condPg = $condPg;
        }
    function getCondPg() { 
        return $this->condPg; 
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

    public function setFrete($frete, $format=false) {
        $this->frete = $frete;
        if ($format):
            $this->frete = number_format($this->frete, 2, ',', '.');
        endif;
        
    }
    public function getFrete($format = null) {
        if (isset($this->frete)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->frete);
                    break;
                case 'F':
                    return number_format((double) $this->frete, 2, ',', '.');
                    break;
                default :
                    return $this->frete;
            }
        else:
            return 0;            
        endif;        
    }

    public function setDespAcessorias($despacessorias, $format=false) {
        $this->despacessorias = $despacessorias;
        if ($format):
            $this->despacessorias = number_format($this->despacessorias, 2, ',', '.');
        endif;
        
    }
    public function getDespAcessorias($format = null) {
        if (isset($this->despacessorias)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->despacessorias);
                    break;
                case 'F':
                    return number_format((double) $this->despacessorias, 2, ',', '.');
                    break;
                default :
                    return $this->despacessorias;
            }
        else:
            return 0;            
        endif;        
    }        

    public function setSeguro($seguro, $format=false) {
        $this->seguro = $seguro;
        if ($format):
            $this->seguro = number_format($this->seguro, 2, ',', '.');
        endif;
        
    }
    public function getSeguro($format = null) {
        if (isset($this->seguro)):
            switch ($format) {
                case 'B':
                    return c_tools::moedaBd($this->seguro);
                    break;
                case 'F':
                    return number_format((double) $this->seguro, 2, ',', '.');
                    break;
                default :
                    return $this->seguro;
            }
        else:
            return 0;            
        endif;        
    }

    public function setTotalOc($totalOc, $format=false) {
        $this->totalOc = $totalOc;
        if ($format):
            $this->totalOc = number_format($this->totalOc, 2, ',', '.');
        endif;
        
    }

    function getTotalOc($format = NULL) {
        if (!empty($this->totalOc)) {
            if ($format == 'F') {
                return number_format($this->totalOc, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->totalOc);
            }
        } else {
            return 0;
        }        
    }

    /* FIM GET SET ORDEM DE COMPRA */

    /* GET SET ORDEM DE COMPRA ITEM */

    function setNrItem($nrItem) { $this->nrItem = $nrItem; }
    function getNrItem() { return $this->nrItem; }

    function setOc($oc) { $this->oc = $oc; }
    function getOc() { return $this->oc; }

    function setItemEstoque($itemEstoque) { $this->itemEstoque = $itemEstoque; }
    function getItemEstoque() { return $this->itemEstoque; }

    function setItemFabricante($itemFabricante) { $this->itemFabricante = $itemFabricante; }
    function getItemFabricante() { return $this->itemFabricante;  }

    function setCodigoNota($codigoNota) { $this->codigoNota = $codigoNota; }
    function getCodigoNota() { return $this->codigoNota;  }

    function setQtSolicitada($qtSolicitada, $format=false) {
         $this->qtSolicitada = $qtSolicitada;
         if ($format):
            $this->qtSolicitada = number_format($this->qtSolicitada, 2, ',', '.');
         endif;
 
    }
    function getQtSolicitada($format = NULL) {
        if (!empty($this->qtSolicitada)) {
            if ($format == 'F') {
                return number_format($this->qtSolicitada, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->qtSolicitada);
            }
        } else {
            return 0;
        }
    }

    function setUnitario($unitario, $format=false) { 
        $this->unitario = $unitario; 
        if ($format):
            $this->unitario = number_format($this->unitario, 2, ',', '.');
        endif;    
    }
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
    function setDescontoItem($descontoItem, $format=false) {
        $this->descontoItem = $descontoItem;
        if ($format):
            $this->descontoItem = number_format($this->descontoItem, 2, ',', '.');
        endif;    

    }
    function getDescontoItem($format = NULL) {
        if (!empty($this->descontoItem)) {
            if ($format == 'F') {
                return number_format($this->descontoItem, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->descontoItem);
            }
        } else {
            return 0;
        }
    }

    function setPercDescontoItem($percDescontoItem, $format=false) {
        $this->percDescontoItem = $percDescontoItem;
        if ($format):
            $this->percDescontoItem = number_format($this->percDescontoItem, 2, ',', '.');
        endif;    

    }
    function getPercDescontoItem($format = NULL) {
        if (!empty($this->percDescontoItem)) {
            if ($format == 'F') {
                return number_format($this->percDescontoItem, 2, ',', '.');
            } else {
                return c_tools::moedaBd($this->percDescontoItem);
            }
        } else {
            return 0;
        }
    }
    
    function setTotalItem($totalItem, $format=false) {
        $this->totalItem = $totalItem;
        if ($format):
            $this->totalItem = number_format($this->totalItem, 2, ',', '.');
        endif;    

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

    function setUnidade($unidade) { $this->unidade = $unidade;}    
    function getUnidade() { return $this->unidade; }

    /* FIM GET SET ORDEM DE COMPRA ITEM */


    /* GET SET ORDEM DE COMPRA FINANCEIRO */

    function setIdNatop($idNatop) { $this->idNatop = $idNatop; }
    function getIdNatop()  { return isset($this->idNatop) ? $this->idNatop : 'NULL'; }

    function setGenero($genero) {$this->genero = strtoupper($genero);}
    function getGenero() {return $this->genero;}

    function setDataEntrada($dataEntrada) { 
        if($dataEntrada == ''){
            $this->dataEntrada = '';    
        }else{
            $this->dataEntrada = $dataEntrada; 
        }
    }
    function getDataEntrada($format = NULL) {
        if($this->dataEntrada == ''){
            return '';
        }else{
            return c_date::formatDateTime($format, $this->dataEntrada, false);
        }
    }

    function setNumeroNf($numNf) {$this->numNf = strtoupper($numNf);}
    public function getNumeroNf() {
        if (!empty($this->numNf)) {
            return $this->numNf;
        } else {
            return 0;
        }    
    }

    function setSerie($serie) {$this->serie = strtoupper($serie);}
    function getSerie() {return $this->serie;}

    function setDataEmissao($dataEmissao) { 
        if($dataEmissao == ''){
            $this->dataEmissao = '';    
        }else{
            $this->dataEmissao = $dataEmissao; 
        }
    }
    function getDataEmissao($format = NULL) {
        if($this->dataEmissao == ''){
            return '';
        }else{
            return c_date::formatDateTime($format, $this->dataEmissao, false);
        }
    }

    /* FIM GET SET ORDEM DE COMPRA FINANCEIRO */

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
        $this->setSituacao($ordemCompra[0]['SITUACAO']);
        $this->setEmissao($ordemCompra[0]['EMISSAO']);
        $this->setDesconto($ordemCompra[0]['DESCONTO']);
        $this->setTotal($ordemCompra[0]['TOTAL']);
        $this->setObs($ordemCompra[0]['OBS']);

        $this->setNumeroNf($ordemCompra[0]['NUMERONF']);
        $this->setSerie($ordemCompra[0]['SERIENF']);
        $this->setDataEmissao($ordemCompra[0]['DATAEMISSAO']);
        $this->setIdNatop($ordemCompra[0]['IDNATOP']);
        $this->setCondPg($ordemCompra[0]['CONDPG']);
        $this->setCentroCusto($ordemCompra[0]['CCUSTO']);
        $this->setFrete($ordemCompra[0]['FRETE']);
        $this->setSeguro($ordemCompra[0]['SEGURO']);
        $this->setDespAcessorias($ordemCompra[0]['DESPACESSORIAS']);
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
         * [8] = numero NF        
         */
        $isWhere = true;
        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[0]);
        $dataFim = c_date::convertDateTxt($par[1]);

        $sql = "SELECT O.*, C.NOME, ";
        $sql .= " NF.ID AS IDNF, NF.NUMERO, NF.SERIE, S.PADRAO AS DESCSITUACAO, ";
        $sql .= " IF ( C.CNPJCPF <> '', IF ";
        $sql .= " (C.PESSOA = 'J', CONCAT(SUBSTRING(C.CNPJCPF, 1,2), '.' , SUBSTRING(C.CNPJCPF, 3,3),'.', SUBSTRING(C.CNPJCPF, 6,3),'/',SUBSTRING(C.CNPJCPF, 9,4), ";
        $sql .= " '-',SUBSTRING(C.CNPJCPF, 13,2)), ";
        $sql .= " CONCAT(SUBSTRING(cnpjcpf, 1,3), '.' , SUBSTRING(C.CNPJCPF, 4,3),'.',SUBSTRING(C.CNPJCPF, 7,3),'-',SUBSTRING(C.CNPJCPF, 10,2)) ";
        $sql .= " ), '')  AS CNPJCPF ";
        $sql .= "FROM EST_ORDEM_COMPRA O ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE=O.CLIENTE) ";
        $sql .= "LEFT JOIN EST_NOTA_FISCAL NF ON (O.ID = NF.DOC AND NF.ORIGEM = 'COC') ";
        $sql .= "LEFT JOIN AMB_DDM S ON ((S.ALIAS='FAT_MENU') AND (S.CAMPO='SITUACAOPEDIDO') AND (S.TIPO=O.SITUACAO)) ";
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
            if ($par[4] != '') {
                if ($isWhere) {
                    $sql .= "where (O.SITUACAO in (".$par[4].")) ";
                    $isWhere = false;
                } else {
                    $sql .= "and (O.SITUACAO in (".$par[4].")) ";
                }
            }

            if ($par[5] != '') {
                if ($isWhere) {
                    $sql .= "where (NF.NUMERO = (".$par[5].")) ";
                    $isWhere = false;
                } else {
                    $sql .= "and (NF.NUMERO = (".$par[5].")) ";
                }
            }
        }
        
        $sql .= "ORDER BY O.EMISSAO Desc";
        
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
    public function select_ordem_compra_id($situacao = NULL) {

        $sql = "SELECT DISTINCT p.*, c.nome, c.nomereduzido, C.PESSOA, c.fonearea, c.fone, c.celular, c.fonecontato, c.tipoend, c.tituloend, C.PESSOA, ";
        $sql .= "if (pessoa='J',";
        $sql .= "CONCAT(SUBSTRING(cnpjcpf, 1,2), '.', SUBSTRING(cnpjcpf,3,3), '.', SUBSTRING(cnpjcpf,6,3), '/', SUBSTRING(cnpjcpf,9,4), '-', SUBSTRING(cnpjcpf,13, 2)), 
                CONCAT(SUBSTRING(cnpjcpf, 1,3), '.', SUBSTRING(cnpjcpf,4,3), '.', SUBSTRING(cnpjcpf,7,3), '-', SUBSTRING(cnpjcpf,10, 2))";
        $sql .= ") AS cnpjcpf, cnpjcpf as cnpjcpfsemformatacao, ";        
        $sql .= "c.endereco, c.numero, c.complemento, c.bairro, c.cidade, ";
        $sql .= "c.uf, c.cep, c.email, t.descricao as descpgto, u.Nome as USRFATURA_ ";
        $sql .= "FROM est_ordem_compra p ";
        $sql .= "inner join fin_cliente c on c.cliente = p.cliente ";
        $sql .= "LEFT join fat_cond_pgto t on t.id = p.condpg ";
        $sql .= "LEFT join amb_usuario u on u.usuario = p.userinsert ";
        $sql .= "WHERE (p.id = " . $this->getId() . ") ";

        if ($situacao == '0') {
            $sql .= "and (situacao ='" . $situacao . "') ";
        }
        $sql .= "ORDER BY id;";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

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
    /* FUNCOES SELECT ORDEM DE COMPRA ITEM */
    
    /**
     * Funcao de consulta ao banco de dados de acordo com as chaves Primarias: ID e NRITEM
     * @param INT ID Chave primaria da table fat_pedido
     * @param SMALLINT NRITEM chave primaria para a table fat_pedido_item
     * @name verifica_ordem_compra_item
     * @return ARRAY todos as colunas da table fat_pedido_item
     */
    public function verifica_ordem_compra_item() {
        $sql = "SELECT i.* FROM ";
        $sql .= "est_ordem_compra_item i ";
        $sql .= "left join est_produto e on (e.codigo=i.itemestoque) ";
        $sql .= "WHERE (i.id = '" . $this->getId() . "') AND ";
        $sql .= "(i.ITEMESTOQUE = 0);";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
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
     * Consulta para o Banco atraves do id
     * @name select_total_e_desconto_oc
     * @param INT GetId Chave primaria da tabela fat_pedido_item
     * @return ARRAY todos os campos da table
     */
    public function select_total_e_desconto_oc($id, $totaisFDS, $conn=null) {

        if($id > 0){
            $sql = "SELECT sum(total) as totalItens, sum(desconto) as totalDescontoItens ";
            $sql .= "FROM EST_ORDEM_COMPRA_ITEM ";
            $sql .= "WHERE (id = " . $id . ") ";
            $sql .= "ORDER BY id";
            
            $banco = new c_banco;  
            $banco->exec_sql($sql,$conn);
            $banco->close_connection();
            $totalItens = $banco->resultado;

            $totalOc = array(
                'TOTALOC' => ($totaisFDS['FRETE'] + $totaisFDS['SEGURO'] + $totaisFDS['DESPACESSORIAS']) + ($totalItens[0]['TOTALITENS']),
                'DESCONTOS' => $totalItens[0]['TOTALDESCONTOITENS']
            );

        }else{
            $totalOc = array(
                'TOTALOC' => ($totaisFDS['FRETE'] + $totaisFDS['SEGURO'] + $totaisFDS['DESPACESSORIAS']),
                'DESCONTOS' => 0
            );
        }
        return $totalOc;
        
    }

    /**
     * Funcao de consulta ao banco de dados de acordo com o id da table fat_pedido_item
     * @name select_pedido_item_id
     * @param INT ID Chave primaria da table fat_pedido
     * @return ARRAY todos as colunas da table fat_pedido_item
     * @version 20161004
     */
    public function select_ordem_compra_item_id() {
        
        $sql = "SELECT i.*,P.descricao as descest, P.unidade, P.unifracionada, p.origem, p.TRIBICMS, p.ncm, p.cest, p.codigobarras, p.localizacao FROM ";
        $sql .= "est_ordem_compra_item i ";
        $sql .= "LEFT JOIN EST_PRODUTO P ON (P.CODIGO=I.ITEMESTOQUE)  ";
        $sql .= "LEFT JOIN EST_GRUPO G ON (G.GRUPO=P.GRUPO)  ";
        $sql .= "WHERE (i.id = '" . $this->getId() . "'); ";
        
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

    public function select_ordem_compra_items($id, $conn=null) {
        $sql = "SELECT c.*, p.LOCALIZACAO FROM EST_ORDEM_COMPRA_ITEM c ";
        $sql .= "INNER JOIN EST_PRODUTO p ON p.CODIGO = c.ITEMESTOQUE ";
        $sql .= "WHERE (c.ID ='" . $id . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql,$conn);
        $banco->close_connection();
        return $banco->resultado;
    }

    /* FIM FUNCOES SELECT OC ITEM  */

    //---------------------------------------------------------------
	public function incluiOrdemCompra($conn=null) {

        $banco = new c_banco;
        if ($banco->gerenciadorDB == 'interbase') {
            $this->setId($banco->geraID("FAT_GEN_ID_ORDEM_COMPRA"));
            $sql = "INSERT INTO EST_ORDEM_COMPRA (ID, ";
        } else {
            $sql = "INSERT INTO EST_ORDEM_COMPRA (";
        }
        $sql .= "CLIENTE,SITUACAO, EMISSAO,CONDPG,OBS,CCUSTO, "; 
        if ($this->getNumeroNf() != ''){
            $sql .= "  NUMERONF, "; 
        }   
        $sql .= "SERIENF, ";
        if($this->getDataEmissao() != ''){
            $sql .= " DATAEMISSAO,  ";
        }
        $sql .= "DESCONTO,PRODUTOS,TOTAL,USERINSERT,DATEINSERT, FRETE, SEGURO, DESPACESSORIAS) ";
        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .=   $this->getCliente() . "','"  . $this->getSituacao() . "','" . $this->getEmissao('B') . "', '"
                . $this->getCondPg() . "', '"  . $this->getObs() . "', '"
                . $this->getCentroCusto() . "', ";

        if($this->getNumeroNf()  != ''){
            $sql .=  $this->getNumeroNf() . ", ";
        }
        $sql .= "'" . $this->getSerie() . "', ";

        if($this->getDataEmissao() != ''){
            $sql .="'". $this->getDataEmissao('B') . "', ";
        }
        $sql .= $this->getDesconto('B') . ", "  . $this->getProdutos('B') . ", "
                . $this->getTotal('B') . ", ";

        $sql .= $this->m_userid.",'".date("Y-m-d H:i:s")."',".$this->getFrete('B').",".$this->getSeguro('B').",".$this->getDespAcessorias('B').");";
        $res_pedidoVenda = $banco->exec_sql($sql);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da ordem de compra ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }
    // fim incluiOrdemCompra

    /**
     * Funcao para alterar a situacao do pedido
     * @name alteraOrdemCompra
     * @return NULL quando ok ou msg erro
     */
    public function alteraOrdemCompra() {

        $sql = "UPDATE EST_ORDEM_COMPRA ";
        $sql .= "SET total = " . $this->getTotalOc() . ", ";
        $sql .= "cliente = '" . $this->getCliente() . "', ";
        $sql .= "condpg = '" . $this->getCondPg() . "', ";
        $sql .= "obs = '" . $this->getObs() . "', ";
        $sql .= "frete = '" . $this->getFrete('B') . "', ";
        $sql .= "seguro = '" . $this->getSeguro('B') . "', ";
        $sql .= "despacessorias = '" . $this->getDespAcessorias('B') . "', ";  
        if($this->getNumeroNf()  != ''){
            $sql .= "numeronf = " . $this->getNumeroNf() . ", ";   
        }else{
            $sql .= "numeronf = null, ";  
        }
        $sql .= "serienf = '" . $this->getSerie() . "', ";  
        if($this->getDataEmissao() != ''){ 
            $sql .= "dataemissao = '" . $this->getDataEmissao('B') . "', ";  
        }else{
            $sql .= "dataemissao = null, ";
        }  
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
    public function alteraOrdemCompraTotal($total, $desconto) {

        $sql = "UPDATE EST_ORDEM_COMPRA ";
        $sql .= "SET total = " . $total . ", ";   
        $sql .= "desconto = " . $desconto . ", ";
        $sql .= "descontoitens = " . $desconto . ", ";        
        $sql .= "situacao = '" . $this->getSituacao() . "', ";
        $sql .= "frete = '" . $this->getFrete('B') . "', ";
        $sql .= "seguro = '" . $this->getSeguro('B') . "', ";
        $sql .= "despacessorias = '" . $this->getDespAcessorias('B') . "' ";
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

    public function atualizaOrdemCompraNumNfSerieDataEmissao($conn=null) {

        $sql = "UPDATE EST_ORDEM_COMPRA ";
        $sql .= "SET NumeroNf = " . $this->getNumeroNf() . ", ";   
        $sql .= "serieNf = '" . $this->getSerie() . "', ";        
        $sql .= "DATAEMISSAO = '" . $this->getDataEmissao('B') . "' ";
        $sql .= "WHERE id = " . $this->getId() . ";";

        $banco = new c_banco;
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $banco->close_connection();

        if ($res_pedidoVenda > 0) {
            return '';
        } else {
            return 'A  ordem de compra ' . $this->getId() . ' n&atilde;o foi alterado!';
        }
    }

    /**
     * Funcao para duplicar Ordem de Servico
     * @name duplicaOrdemCompra
     * @return INT ID ORDEM DE COMPRA se ocorrer com sucesso
     */
    public function duplicaOrdemCompra($conn=null) {
        $banco = new c_banco;

        $situacao = 5; // COTACAO
        $emissao = date('Y-m-d H:i:s');

        $sql = "INSERT INTO EST_ORDEM_COMPRA (
            CLIENTE,CONDPG,EMISSAO,OBS,CCUSTO,DESCONTOITENS,DESCONTO,
            PRODUTOS,TOTAL,USERINSERT,NUMERONF,SERIENF,DATAEMISSAO, SITUACAO, DATEINSERT, FRETE, SEGURO, DESPACESSORIAS) ";
        $sql .= "SELECT CLIENTE,CONDPG,'".$emissao."' as EMISSAO, OBS,CCUSTO, DESCONTOITENS, DESCONTO,
            PRODUTOS,TOTAL,USERINSERT,NUMERONF,SERIENF,DATAEMISSAO,
            ".$situacao." as SITUACAO, DATEINSERT, FRETE, SEGURO, DESPACESSORIAS  FROM EST_ORDEM_COMPRA ";
        $sql .= "WHERE ID = '".$this->getId()."'";
                
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da Ordem de Compra ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para alterar a situacao da ordem de compra para Pedido Baixado = 9
     * @param INT ID Chave primaria da table EST_ORDEM_COMPRA
     * @name updateSituacao
     * @return NULL quando ok ou msg erro
     */
    public function updateSituacao($conn=null) {
        $sql = "UPDATE ";
        $sql .= "est_ordem_compra ";
        $sql .= "SET situacao = 9 ";
        $sql .= "WHERE (id = '" . $this->getId() . "') ";
        $banco = new c_banco;
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
    }
    /**
     * Funcao para excluir uma ordem de compra 
     * @param INT ID Chave primaria da table EST_ORDEM_COMPRA
     * @name excluiOrdemCompra
     * @return NULL quando ok ou msg erro
     */
    public function excluiOrdemCompra() {

        $sql = "DELETE FROM ";
        $sql .= "est_ordem_compra ";
        $sql .= "WHERE (id = " . $this->getId() . "); ";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * ****************************************
     * ******* Funções CRUD OC ITEM ***********
     * ****************************************
     */

    /**
     * Funcao para inclusão de um item a ordem de compra 
     * @name incluiOrdemCompraItem
     * @return string vazio se ocorrer com sucesso
     */
    public function incluiOrdemCompraItem($conn=null) {
        $banco = new c_banco;
        $sql = "INSERT INTO EST_ORDEM_COMPRA_ITEM (";
        $sql .= "id, nritem, oc, itemestoque, itemfabricante, codigoNota, qtsolicitada, ";
        $sql .= "unitario, desconto, percDesconto, total, descricao, unidade ) ";

        if ($banco->gerenciadorDB == 'interbase') {
            $sql .= "VALUES (" . $this->getId() . ", '";
        } else {
            $sql .= "VALUES ('";
        }
        $sql .=   $this->getId() . "', '"
                . $this->getNrItem() . "', "
                . $this->getOc() . ", '"
                . $this->getItemEstoque() . "', '"
                . $this->getItemFabricante() . "', '"
                . $this->getCodigoNota() . "', "
                . $this->getQtSolicitada('B') . ", "
                . $this->getUnitario('B') . ", "
                . $this->getDescontoItem('B') . ", "
                . $this->getPercDescontoItem('B') . ", "
                . $this->getTotalItem('B') . ", '"
                . $this->getDescricaoItem() ."', '"
                . $this->getUnidade()."'); ";
 
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da ordem compra ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }
    }

    /**
     * Funcao para alterar um registro no banco de dados na tabela de EST_ORDEM_COMPRA_ITEM
     * @name alteraOrdemCompraItem
     * @return string vazio se ocorrer com sucesso
     */
    public function alteraOrdemCompraItem($conn=null) {

        $sql = "UPDATE EST_ORDEM_COMPRA_ITEM ";
        $sql .= "SET id = '" . $this->getId() . "', ";
        $sql .= "nritem = '" . $this->getNrItem() . "', ";
        $sql .= "oc = '" . $this->getOc() . "', ";
        $sql .= "itemestoque = '" . $this->getItemEstoque() . "', ";
        $sql .= "itemfabricante = '" . $this->getItemFabricante() . "', ";
        $sql .= "codigoNota = '" . $this->getCodigoNota() . "', ";
        $sql .= "qtsolicitada = " . $this->getQtSolicitada('B') . ", ";
        $sql .= "unitario = " . $this->getUnitario('B') . ", ";
        $sql .= "desconto = " . $this->getDescontoItem('B') . ", ";
        $sql .= "percDesconto = " . $this->getPercDescontoItem('B') . ", ";
        $sql .= "total = " . $this->getTotalItem('B') . ", ";
        $sql .= "unidade = '" . $this->getUnidade() . "', ";
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
     * Funcao para duplicar Ordem de Compra Item 
     * @name duplicaOrdemCompraItem
     * @param INT idNovo novo
     * @param INT idAntigo antigo 
     * @return INT ID ORDEM_COMPRA_ITEM se ocorrer com sucesso
     */
    public function duplicaOrdemCompraItem($idNovo, $idAntigo, $conn=null) {

        $banco = new c_banco;        
        $sql = "INSERT INTO EST_ORDEM_COMPRA_ITEM (
            ID, OC, NRITEM, ITEMFABRICANTE, ITEMESTOQUE, CODIGONOTA, QTSOLICITADA, UNITARIO, DESCONTO, PERCDESCONTO, TOTAL, 
            DESCRICAO, UNIDADE) 
            SELECT '".$idNovo."' as ID, ".$idNovo." as OC, 
                NRITEM, ITEMFABRICANTE, ITEMESTOQUE, CODIGONOTA, QTSOLICITADA, UNITARIO, DESCONTO, PERCDESCONTO, TOTAL, 
                DESCRICAO, UNIDADE 
            FROM EST_ORDEM_COMPRA_ITEM 
            WHERE OC = '".$idAntigo."' ";
                
        $res_pedidoVenda = $banco->exec_sql($sql, $conn);
        $lastReg = mysqli_insert_id($banco->id_connection);
        $banco->close_connection();
        if ($res_pedidoVenda > 0) {
            return $lastReg;
        } else {
            return 'Os dados da ordem de compra Item ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }

    }


    /**
     * Funcao de exclusao do item da Ordem de Compra, no banco de dados EST_ORDEM_COMPRA_ITEM
     * @name excluiOrdemCompraItem
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
}

//	END OF THE CLASS
?>