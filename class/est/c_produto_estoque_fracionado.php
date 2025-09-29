<?php
/**
 * @package   astec
 * @name      c_produto_estoque
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      13/04/2016
 * @utilizado select_quantidade_empresa
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/est/c_produto.php");

//Class C_PRODUTO_ESTOQUE
Class c_produto_estoque extends c_user {

// Campos tabela | Objetos da Classe

    private $id                     = NULL; // int(11)
    private $codProduto             = NULL; // int(11)
    private $descricao = NULL;
    private $nsEntrada              = NULL; // varchar(30)
    private $centroCusto            = NULL; // varchar(10)
    private $status                 = NULL; // int(11)
    private $aplicado               = NULL; // char(1)
    private $fabLote                = NULL; // varchar(20)
    private $dataValidade           = NULL; // date
    private $dataFabricacao         = NULL; // date
    private $ns                     = NULL; // varchar(20)
    private $localizacao            = NULL; // varchar(30)
    private $projeto                = NULL; // int(11)
    private $idOs                   = NULL; // int(11)
    private $idPedido               = NULL; // int(11)
    private $idNfSaida              = NULL; // int(11)
    private $idLote                 = NULL; // int(11)
    private $idLoteTec              = NULL; // int(11)
    private $userProduto            = NULL; // int(11)
    private $devolucaoUserProduto   = NULL; // timestamp
    private $quantidade             = NULL; // float
    private $dataemissao            = NULL; // timestamp
    private $obs                    = NULL; // blob
    
    
//construtor
    function __construct() {
        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);
    }



    public function setId($id) {$this->id = $id;}
    public function getId() {return $this->id;}

    public function setCodProduto($codProduto) {$this->codProduto = $codProduto;}
    public function getCodProduto() { return $this->codProduto;}
    public function getDescProduto() {
            $produto = new c_produto();
            $produto->setId($this->getCodProduto());
            $reg_nome = $produto->select_produto();
            $this->descricao = $reg_nome[0]['DESCRICAO'];
    }

    public function setIdNfEntrada($idNfEntrada) {$this->idNfEntrada = $idNfEntrada;}
    public function getIdNfEntrada() { return $this->idNfEntrada;}

    public function setCentroCusto($centroCusto) { $this->centroCusto = $centroCusto;}
    public function getCentroCusto() { return $this->centroCusto;}

    public function setStatus($status) {$this->status = $status;}
    public function getStatus() {return $this->status;}

    public function setAplicado($aplicado) { $this->aplicado = strtoupper($aplicado);}
    public function getAplicado() { return $this->aplicado;}

    public function setFabLote($fabLote) { $this->fabLote = strtoupper($fabLote);}
    public function getFabLote() { return $this->fabLote;}

    function setDataValidade($dataValidade) { $this->dataValidade = $dataValidade;}
    function getDataValidade($format=NULL) {
        //if (!empty($this->dataValidade)){
            switch ($format) {
                case 'F':
                        return date('d/m/Y', strtotime($this->dataValidade)); 
                        break;
                case 'B':
                        return c_date::convertDateBdSh($this->dataValidade, $this->m_banco);
                        break;
                default:
                        return $this->dataValidade;
            }
//        }else{
//            return '';
//        }

    }

    function setDataFabricacao($dataFabricacao) { $this->dataFabricacao = $dataFabricacao;}
    function getDataFabricacao($format=NULL) {
  //    if (!empty($this->dataFabricacao)){
            switch ($format) {
                case 'F':
                        return date('d/m/Y', strtotime($this->dataFabricacao)); 
                        break;
                case 'B':
                        return c_date::convertDateBdSh($this->dataFabricacao, $this->m_banco);
                        break;
                default:
                        return $this->dataFabricacao;
            }
//        }else{
//            return '';
//        }

    }

    public function setNsEntrada($nsEntrada) { $this->nsEntrada = strtoupper($nsEntrada);}
    public function getNsEntrada() { return $this->nsEntrada;}

    public function setLocalizacao($localizacao) {$this->localizacao = strtoupper($localizacao);}
    public function getLocalizacao() {return $this->localizacao;}

    public function setProjeto($projeto) { $this->projeto = strtoupper($projeto);}
    public function getProjeto() { return $this->projeto;}


    public function setIdOs($idOs) {$this->idOs = $idOs;}
    public function getIdOs() { return $this->idOs;}

    public function setIdPedido($idPedido) {$this->idPedido = $idPedido;}
    public function getIdPedido() { return $this->idPedido;}

    public function setIdNfSaida($idNfSaida) {$this->idNfSaida = $idNfSaida;}
    public function getIdNfSaida() { return $this->idNfSaida;}

    public function setIdLote($idLote) {$this->idLote = $idLote;}
    public function getIdLote() {return $this->idLote;}

    public function setIdLoteTec($idLoteTec) {$this->idLoteTec = $idLoteTec;}
    public function getIdLoteTec() {return $this->idLoteTec;}

    public function setUserProduto($userProduto) { $this->userProduto = $userProduto;}
    public function getUserProduto() { return $this->userProduto;}

    public function setDevolucaoUserProduto($devolucaoUserProduto) {
        $this->devolucaoUserProduto = $devolucaoUserProduto;
    }
    public function getDevolucaoUserProduto() {
        return $this->devolucaoUserProduto;
    }

    public function setObs($obs) { $this->obs = strtoupper($obs);}
    public function getObs() { return $this->obs;}


    //############### FIM SETS E GETS ###############
    

    public function movimento_estoque(){
        $sql = "SELECT NFP.IDNF,NFP.CODPRODUTO, NFP.DESCRICAO, NFP.QUANT AS ENTRADA, ";
        $sql .= "NFP.TOTAL AS TOTAL_ENTRADA, NF.DATASAIDAENTRADA AS DATA_ENTRADA, ";
        $sql .= "PI.QTSOLICITADA AS SAIDA, P.TOTAL AS TOTAL_SAIDA, P.EMISSAO AS DATA_SAIDA ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO NFP ";
        $sql .= "LEFT JOIN EST_NOTA_FISCAL NF ON (NFP.IDNF = NF.ID) ";        
        $sql .= "INNER JOIN FAT_PEDIDO_ITEM PI ON (NFP.CODPRODUTO = PI.ITEMESTOQUE) ";
        $sql .= "LEFT JOIN EST_PRODUTO PROD ON (NFP.CODPRODUTO = PROD.CODIGO) ";
        $sql .= "LEFT JOIN FAT_PEDIDO P ON (P.ID = PI.ID) ";

        $sql .= "ORDER BY NFP.CODPRODUTO ";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * Funcao consulta a quantidade de produto por filial tabela PRODUTO_ESTOQUE CONSIDERANDO DATA INI DATA FIM
     * @name produtoQtdePeriodo
     * @param VAHCHAR produto produto a ser pesquisada
     * @param DATE dataIni data inicio para calculo da quantidade
     * @param DATE dataFim data fim para calculo da quantidade
     */

    public function produtoQtdePeriodo($letra, $produto = null) {

        $par = explode("|", $letra);
        $arrData = explode("-", str_replace("/", "",$par[0]));
        $dataIni = trim($arrData[0]);
        $dataFim = trim($arrData[1]);
        $dataFim = substr($dataFim, 4, 4)."-".substr($dataFim, 2, 2)."-".substr($dataFim, 0, 2);
        $sql = "SELECT P.CODIGO, P.UNIDADE, P.CUSTOCOMPRA, ";
        $sql .= "sum(if((E.DATASAIDAENTRADA >= '".$dataIni."') and (E.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS ENTRADAPERIODO, ";
        $sql .= "sum(if((T.STATUS= 9) and (S.DATASAIDAENTRADA >= '".$dataIni."') and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS SAIDAPERIODO, ";
        $sql .= "sum(if((T.STATUS= 1) and (S.DATASAIDAENTRADA >= '".$dataIni."') and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS RESERVADOPERIODO, ";
        $sql .= "sum(if((T.STATUS= 8) and (S.DATASAIDAENTRADA >= '".$dataIni."') and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS PERDAPERIODO, ";
        $sql .= "sum(if(E.DATASAIDAENTRADA <= '".$dataFim."', 1, 0)) AS ENTRADA, ";
        $sql .= "sum(if((T.STATUS= 9) and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS SAIDA, ";
        $sql .= "sum(if((T.STATUS= 1) and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS RESERVADO, ";
        $sql .= "sum(if((T.STATUS= 8) and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS PERDA ";
        $sql .= "FROM EST_PRODUTO_ESTOQUE T ";
        $sql .= "LEFT join EST_NOTA_FISCAL E ON (E.ID=T.IDNFENTRADA) ";
        $sql .= "LEFT join EST_NOTA_FISCAL S ON (S.ID=T.IDNFSAIDA) ";
        $sql .= "LEFT join EST_PRODUTO P ON (P.CODIGO=T.CODPRODUTO) ";
        $sql .= "group by P.CODIGO";

        // $sql = "SELECT P.CODIGO, P.UNIDADE, P.CUSTOCOMPRA, ";
        // $sql .= "sum(1) AS ENTRADA, ";
        // $sql .= "sum(if((T.STATUS= 9), 1, 0)) AS SAIDA, ";
        // $sql .= "sum(if((T.STATUS= 1), 1, 0)) AS RESERVADO, ";
        // $sql .= "sum(if((T.STATUS= 8), 1, 0)) AS PERDA ";
        // $sql .= "FROM EST_PRODUTO_ESTOQUE T ";
        // $sql .= "LEFT join EST_NOTA_FISCAL E ON (E.ID=T.IDNFENTRADA) ";
        // $sql .= "LEFT join EST_NOTA_FISCAL S ON (S.ID=T.IDNFSAIDA) ";
        // $sql .= "LEFT join EST_PRODUTO P ON (P.CODIGO=T.CODPRODUTO) ";
        // $sql .= "WHERE (S.DATASAIDAENTRADA >= '".$dataIni."') and (S.DATASAIDAENTRADA <= '".$dataFim."')"
        // $sql .= "group by P.CODIGO";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }    

    /**
     * Funcao consulta a quantidade de produto por filial tabela PRODUTO_ESTOQUE CONSIDERANDO POR DATA FIM
     * @name produtoQtdeData
     * @param VAHCHAR produto produto a ser pesquisada
     * @param DATE dataFim data fim para calculo da quantidade
     * @utilizado remessa_bloco_k
     */
    public function produtoQtdeData($letra, $produto = null) {

        $par = explode("|", $letra);
        $arrData = explode("-", str_replace("/", "",$par[0]));
        $dataIni = trim($arrData[0]);
        $dataFim = trim($arrData[1]);
        $dataFim = substr($dataFim, 4, 4)."-".substr($dataFim, 2, 2)."-".substr($dataFim, 0, 2);
//        $arrData = explode("-", $par[0]);
//        $dataIni = trim($arrData[0]);
//        $dataFim = trim($arrData[1]);
//        $dataIni = date("Y-m-d", strtotime($arrData[0]));
//        $dataFim = date("Y-m-d", strtotime($dataFim));

        // $sql = "SELECT P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.CUSTOCOMPRA, P.PRECOINFORMADO, ";
        // $sql .= "(SELECT COUNT(*) FROM EST_PRODUTO_ESTOQUE E ";
        // $sql .= "inner join EST_NOTA_FISCAL N ON (N.ID=E.IDNFENTRADA) ";
        // $sql .= " WHERE E.CODPRODUTO=P.CODIGO and (N.DATASAIDAENTRADA <= '".$dataFim."')) AS ENTRADA1, ";
        // $sql .= "(SELECT COUNT(*) FROM EST_PRODUTO_ESTOQUE T WHERE T.IDNFENTRADA=1 AND T.CODPRODUTO = P.CODIGO) as ENTRADA2, ";
        // $sql .= "(SELECT count(*) FROM EST_PRODUTO_ESTOQUE S ";
        // $sql .= "inner join EST_NOTA_FISCAL N ON (N.ID=S.IDNFSAIDA) ";
        // $sql .= "WHERE S.CODPRODUTO=P.CODIGO AND (S.status = 9) and (N.DATASAIDAENTRADA <= '".$dataFim."')) AS SAIDA, ";
        // $sql .= "(SELECT count(*) FROM EST_PRODUTO_ESTOQUE S ";
        // $sql .= "inner join EST_NOTA_FISCAL N ON (N.ID=S.IDNFSAIDA) ";
        // $sql .= "WHERE S.CODPRODUTO=P.CODIGO AND (S.status = 1) and (N.DATASAIDAENTRADA <= '".$dataFim."')) AS RESERVADO, ";
        // $sql .= "(SELECT count(*) FROM EST_PRODUTO_ESTOQUE S ";
        // $sql .= "inner join EST_NOTA_FISCAL N ON (N.ID=S.IDNFSAIDA) ";
        // $sql .= "WHERE S.CODPRODUTO=P.CODIGO AND (S.status = 8) and (N.DATASAIDAENTRADA <= '".$dataFim."')) AS PERDA ";
        // $sql .= "FROM EST_PRODUTO AS P";

        $sql = "SELECT P.CODIGO, P.UNIDADE, P.CUSTOCOMPRA, ";
        $sql .= "sum(if(E.DATASAIDAENTRADA <= '".$dataFim."', 1, 0)) AS ENTRADA1, ";
        $sql .= "sum(if(T.IDNFENTRADA=1, 1, 0)) AS ENTRADA2, ";
        $sql .= "sum(if((T.STATUS= 9) and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS SAIDA, ";
        $sql .= "sum(if((T.STATUS= 1) and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS RESERVADO, ";
        $sql .= "sum(if((T.STATUS= 8) and (S.DATASAIDAENTRADA <= '".$dataFim."'), 1, 0)) AS PERDA ";
        $sql .= "FROM EST_PRODUTO_ESTOQUE T ";
        $sql .= "LEFT join EST_NOTA_FISCAL E ON (E.ID=T.IDNFENTRADA) ";
        $sql .= "LEFT join EST_NOTA_FISCAL S ON (S.ID=T.IDNFSAIDA) ";
        $sql .= "LEFT join EST_PRODUTO P ON (P.CODIGO=T.CODPRODUTO) ";
        $sql .= "group by P.CODIGO";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }    

    

    /**
     * Funcao consulta a quantidade de produto por filial tabela PRODUTO_ESTOQUE
     * @name produtoQtde
     * @param VAHCHAR produto produto a ser pesquisada
     * @param NUMBER filial para consulta da quantidade do produto
     * @return NUMBER quantidade
     * @utilizado select_quantidade_empresa, select_quantidade ( interno )
     */
    public static function produtoQtde($produto, $filial) {
        // $sql = "SELECT status,";
        // $sql .= "IF((P.UNIFRACIONADA = 'S'), ";
        // $sql .= "((SELECT COALESCE(sum(quant),0) as quant FROM est_nota_fiscal_produto x INNER JOIN est_nota_fiscal y ON (y.id = x.idnf) WHERE (x.codproduto = '".$produto."') AND  (y.tipo='0') AND (y.centrocusto = ".$filial.")) - ";
        // $sql .= "(SELECT COALESCE(sum(quant),0) as quant FROM est_nota_fiscal_produto x INNER JOIN est_nota_fiscal y ON (y.id = x.idnf) WHERE (x.codproduto = '".$produto."') AND  (y.tipo='1') AND (y.centrocusto = ".$filial.")) - ";
        // $sql .= "(SELECT COALESCE(sum(qtsolicitada),0) as quant FROM FAT_PEDIDO_ITEM s INNER JOIN FAT_PEDIDO t ON (s.id = t.id) WHERE (s.ITEMESTOQUE = '".$produto."') AND  (t.situacao = '6') AND (t.ccusto = ".$filial."))) "; //(t.situacao<>'9') AND (t.situacao <> '8') AND (t.ccusto = ".$filial."))) ";
        // $sql .= ", count(E.CODPRODUTO)) AS 'Quantidade' ";
        // //$sql .= "FROM EST_PRODUTO_ESTOQUE E ";
        // //$sql .= "inner join EST_PRODUTO P ON (P.CODIGO = E.CODPRODUTO) ";
        // $sql .= "FROM EST_PRODUTO P ";
        // $sql .= "LEFT JOIN EST_PRODUTO_ESTOQUE E ON (P.CODIGO = E.CODPRODUTO) AND (E.centrocusto = ".$filial.") AND (E.status <> 9) AND (E.status <> 8) ";
        // //$sql .= "WHERE (centrocusto = ".$filial.") AND (CODPRODUTO = ".$produto.") AND  (status <> 9) AND  (status <> 8)" ;
        // $sql .= "WHERE (P.CODIGO = '".$produto."') ";
        // $sql .= "GROUP BY status";

        $sql = "SELECT T.CODPRODUTO, ";
        $sql .= "SUM(QUANTIDADE) AS ENTRADA, ";
        $sql .= "SUM(IF((T.STATUS= 0), QUANTIDADE, 0)) AS ESTOQUE, ";
        $sql .= "SUM(IF((T.STATUS= 1), QUANTIDADE, 0)) AS RESERVA, ";
        $sql .= "SUM(IF((T.STATUS= 8), QUANTIDADE, 0)) AS PERDA, ";
        $sql .= "SUM(IF((T.STATUS= 9), QUANTIDADE, 0)) AS  SAIDA ";
        $sql .= "FROM EST_PRODUTO_ESTOQUE T ";
        $sql .= "WHERE T.CENTROCUSTO='".$filial."') and (T.CODPRODUTO = '".$produto."')";
        $sql .= "GROUP BY P.CODIGO ";
        
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }    

    /**
     * Funcao consulta a quantidade DISPONIVEL do produto por filial e o preço de VENDA e PROMOÇAO tabela PRODUTO_ESTOQUE e EST_PRODUTO
     * @name produtoQtdePreco
     * @param NUMBER filial codigo da filial para o padrão da consulta
     * @param VAHCHAR letra parametros para a consulta (descricao, grupo, promocao S/N), codigo produto
     * @param VAHCHAR tipoConsulta 'S' - produtos com estoque / 'N' - listagem produto sem estoque / 'P' - produtos com quantidade zero
     * @return ARRAY status, quantidade, codigo, descrição, preço venda, preço promoção
     */
    public function produtoQtdePreco($letra, $filial=NULL, $produto=NULL, $tipoConsulta = 'S') {
        $par = explode("|", $letra);
        $data = date("Y-m-d");
        $isWhere = false;
        $alias = '';

        switch ($tipoConsulta){
            case 'S': // PESQUISA UNIFICADA PRODUTO QUANTIDADE, SOMENTE PRODUTOS COM QUANTIDADE DE ESTOQUE
                $sql = "SELECT P.CODFABRICANTE, P.TIPOPROMOCAO, P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.UNIFRACIONADA, E.STATUS, P.QUANTLIMITE, P.VENDA, ";
                $sql .= "IF((P.UNIFRACIONADA = 'S'), ";
                $sql .= "((SELECT COALESCE(sum(quant),0) as quant FROM est_nota_fiscal_produto x INNER JOIN est_nota_fiscal y ON (y.id = x.idnf) WHERE (x.codproduto = E.CODPRODUTO) AND  (y.tipo='0') AND (y.centrocusto = ".$filial.")) - ";
                $sql .= "(SELECT COALESCE(sum(quant),0) as quant FROM est_nota_fiscal_produto x INNER JOIN est_nota_fiscal y ON (y.id = x.idnf) WHERE (x.codproduto = E.CODPRODUTO) AND  (y.tipo='1') AND (y.centrocusto = ".$filial.")) - ";
                $sql .= "(SELECT COALESCE(sum(qtsolicitada),0) as quant FROM FAT_PEDIDO_ITEM s INNER JOIN FAT_PEDIDO t ON (s.id = t.id) WHERE (s.ITEMESTOQUE = E.CODPRODUTO) AND  (t.situacao<>'9') AND (t.ccusto = ".$filial."))) ";
                $sql .= ", count(E.CODPRODUTO)) AS 'Quantidade', ";
                $sql .= "P.QUANTLIMITE, P.VENDA, ";
                $sql .= "IF(((P.INICIOPROMOCAO <= CURDATE()) and (P.FIMPROMOCAO >= CURDATE())), P.PRECOPROMOCAO, 0) as PROMOCAO, COALESCE(P.CUSTOCOMPRA,0) AS CUSTOCOMPRA, P.PRECOMINIMO, P.ORIGEM, P.NCM, P.CEST, P.TRIBICMS, ";
                $sql .= "N.ALIQPISMONOFASICA, N.ALIQCOFINSMONOFASICA ";
                $sql .= "FROM EST_PRODUTO_ESTOQUE E ";
                $sql .= "inner join EST_PRODUTO P ON (P.CODIGO = E.CODPRODUTO) ";
                $sql .= "LEFT JOIN EST_NCM N ON (N.NCM = P.NCM) ";
                $where = "WHERE (DATAFORALINHA is null) and (status=0) ";
                break;
            case 'N': // PESQUISA SEPARADA PRODUTO QUANTIDADE
                $sql = "SELECT P.CODFABRICANTE, P.TIPOPROMOCAO, P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.UNIFRACIONADA, 0 as STATUS, 0 AS 'Quantidade', P.QUANTLIMITE, P.VENDA, ";
                $sql .= "IF(((P.INICIOPROMOCAO <= CURDATE()) and (P.FIMPROMOCAO >= CURDATE())), P.PRECOPROMOCAO, 0) as PROMOCAO, ";
                $sql .= "COALESCE(P.CUSTOCOMPRA,0) AS CUSTOCOMPRA, P.PRECOMINIMO, P.ORIGEM, P.NCM, P.CEST, P.TRIBICMS, ";
                $sql .= "N.ALIQPISMONOFASICA, N.ALIQCOFINSMONOFASICA ";
                $sql .= "FROM EST_PRODUTO P ";
                $sql .= "LEFT JOIN EST_NCM N ON (N.NCM = P.NCM) ";
                $where = "WHERE (DATAFORALINHA is null) ";
                break;
            case 'P': // PRODUTOS SEM QUANTIDADE - PESQUISA TODOS OS PRODUTOS E BUSCA QUANTIDADE
                $sql = "SELECT DISTINCT(P.CODFABRICANTE), P.TIPOPROMOCAO, P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.UNIFRACIONADA, 0 as STATUS, ";
                $sql .= "IF((P.UNIFRACIONADA = 'S'), ";
                $sql .= "((SELECT COALESCE(sum(quant),0) as quant "; 
                $sql .= "FROM est_nota_fiscal_produto x ";
                $sql .= "INNER JOIN est_nota_fiscal y ON (y.id = x.idnf) WHERE (x.codproduto = P.codigo) AND  (y.tipo='0')) - ";
                $sql .= "(SELECT COALESCE(sum(quant),0) as quant ";
                $sql .= "FROM est_nota_fiscal_produto x ";
                $sql .= "INNER JOIN est_nota_fiscal y ON (y.id = x.idnf) WHERE (x.codproduto = P.codigo) AND  (y.tipo='1')) - "; 
                $sql .= "(SELECT COALESCE(sum(qtsolicitada),0) as quant "; 
                $sql .= "FROM FAT_PEDIDO_ITEM s ";
                $sql .= "INNER JOIN FAT_PEDIDO t ON (s.id = t.id) WHERE (s.ITEMESTOQUE = P.codigo) AND  (t.situacao = '6'))) , ";
                $sql .= "(SELECT count(E.CODPRODUTO) AS 'Quantidade' ";
                $sql .= "FROM EST_PRODUTO_ESTOQUE E WHERE (E.CODPRODUTO=P.CODIGO) AND (E.STATUS=0) ";
                $sql .= "GROUP BY E.CODPRODUTO)) AS 'Quantidade', P.QUANTLIMITE, P.VENDA, ";
                $sql .= "IF(((P.INICIOPROMOCAO <= CURDATE()) and (P.FIMPROMOCAO >= CURDATE())), P.PRECOPROMOCAO, 0) as PROMOCAO, ";
                $sql .= "COALESCE(P.CUSTOCOMPRA,0) AS CUSTOCOMPRA, P.PRECOMINIMO, P.ORIGEM, P.NCM, P.CEST, P.TRIBICMS, ";
                $sql .= "N.ALIQPISMONOFASICA, N.ALIQCOFINSMONOFASICA ";
                $sql .= "FROM EST_PRODUTO P ";
                $sql .= "LEFT JOIN EST_NCM N ON (N.NCM = P.NCM) ";
                $sql .= "LEFT JOIN EST_PRODUTO_EQUIVALENCIA E ON (E.IDPRODUTO = P.CODIGO) ";
                $alias = 'P.';
                $where = "WHERE (DATAFORALINHA is null) ";
                break;
            default :
                $sql = "SELECT P.CODFABRICANTE, P.TIPOPROMOCAO, P.CODIGO, P.DESCRICAO, P.GRUPO, P.UNIDADE, P.UNIFRACIONADA, E.STATUS, count(E.CODPRODUTO) AS 'Quantidade', P.QUANTLIMITE, P.VENDA, ";
                $sql .= "IF(((P.INICIOPROMOCAO <= CURDATE()) and (P.FIMPROMOCAO >= CURDATE())), P.PRECOPROMOCAO, 0) as PROMOCAO, ";
                $sql .= "COALESCE(P.CUSTOCOMPRA,0) AS CUSTOCOMPRA, P.PRECOMINIMO, P.ORIGEM, P.NCM, P.CEST, P.TRIBICMS, ";
                $sql .= "N.ALIQPISMONOFASICA, N.ALIQCOFINSMONOFASICA ";
                $sql .= "FROM EST_PRODUTO_ESTOQUE E ";
                $sql .= "inner join EST_PRODUTO P ON (P.CODIGO = E.CODPRODUTO) ";
                $sql .= "LEFT JOIN EST_NCM N ON (N.NCM = P.NCM) ";
                $where = "WHERE (DATAFORALINHA is null) ";
                
        }//   endswitch;
        if (!empty($par[4])){
            $produto = $par[4];
        }
        if (!empty($produto)){ // consulta somente um produto
            $where .= "AND (P.CODIGO=". $produto.") ";
        }
        else{
            $isWhere = true;
            if (!empty($par[0])){
                $where .= "and ((".$alias."descricao like '%" . $par[0] . "%') or (P.CODFABRICANTE like '" . $par[0] . "%') or (E.CODEQUIVALENTE like '" . $par[0] . "%')) ";
            }
            if (!empty($par[1])){
                $where .= "and (grupo like '".$par[1]."%') ";
            }

            if (!empty($par[3])){
                $where .= "and (p.localizacao like '".$par[3]."%') ";
            }

            switch ($par[2]):
                case '0':
                    $where .= "and ((iniciopromocao <= '".$data."') and (fimpromocao >= '".$data."') and (P.TIPOPROMOCAO='0'))";
                    break;
                case '1':
                    $where .= "and ((iniciopromocao <= '".$data."') and (fimpromocao >= '".$data."') and (P.TIPOPROMOCAO='1'))";
                    break;
                case '2':
                    $where .= "and ((iniciopromocao <= '".$data."') and (fimpromocao >= '".$data."') and (P.TIPOPROMOCAO='2'))";
                    break;
                case 'T':
                    $where .= "and ((iniciopromocao <= '".$data."') and (fimpromocao >= '".$data."'))";
                    break;
            endswitch;
            //if ($par[2] == 'S'){
            //    $where .= "and ((iniciopromocao <= '".$data."') and (fimpromocao >= '".$data."') and (P.TIPOPROMOCAO='0'))";
            //}
//            if ($controlaEstoque == 'S'):
//                if (!empty($filial)):
//                    $where .= "and (CENTROCUSTO ='" . $filial . "') ";
//                endif;
//            endif;
            if ($tipoConsulta=='S'):// PESQUISA UNIFICADA PRODUTO QUANTIDADE, SOMENTE PRODUTOS COM QUANTIDADE DE ESTOQUE
                $where .= "GROUP BY E.CODPRODUTO";
            endif;
            $where .= " ORDER BY ".$alias."DESCRICAO";
        }

        $sql .= $where;
        $banco = new c_banco;
        // echo $sql;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * Funcao reserva a quantidade solicitada para determinado ID
     * @name produtoReserva
     * @param NUMBER filial para consulta da quantidade do produto
     * @param VAHCHAR produto produto a ser pesquisada
     * @param VAHCHAR origem sistema que está solicitando a reserva
     *                (PED, NFS, CAT, LOT (ESTOQUE LOTE), TEC (ESTOQUE LOTE TÉCNICO))  
     * @param NUMBER id numero do id da solicitação origem 
     * @return NUMBER quantidade
     * @utilizado produtoQtde ( interno )
     */

    public function produtoReserva($filial, $origem, $id, $produto, $qtde, $conn=null) {

        $sql = "update EST_PRODUTO_ESTOQUE ";
        $sql .= "set status=1, ";

        switch ($origem){
            case "PED":
                $sql .= "idpedido = ".$id;
                break;
            case "NFS":
                $sql .= "idnfsaida = ".$id;
                break;
            default :
                $quantAtual = 0;
                $quantReservada = 0;
        }


        $sql .= " WHERE (centrocusto = ".$filial.") AND (CODPRODUTO = ".$produto.") AND  (status = 0)";
        $sql .= "order by FABDATAVALIDADE, FABLOTE limit ".intval($qtde);

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->result;
    }    

    /**
     * Funcao exclui produto da reserva para quantidade solicitada para determinado ID
     * @name produtoReservaExclui
     * @param NUMBER filial para consulta da quantidade do produto
     * @param VAHCHAR produto produto a ser pesquisada
     * @param VAHCHAR origem sistema que está solicitando a reserva
     *                (PED, CAT, NFS, LOT (ESTOQUE LOTE), TEC (ESTOQUE LOTE TÉCNICO))  
     * @param NUMBER id numero do id da solicitação origem
     * @return NUMBER quantidade
     * @utilizado produtoQtde ( interno )
     */

    public function produtoReservaExclui($filial, $origem, $id, $produto, $qtde, $conn=null) {

        $qtde = (int) $qtde;
        
        $sql = "update EST_PRODUTO_ESTOQUE ";
        $sql .= "set status=0, ";
        switch ($origem){
            case "PED":
                $sql .= "idpedido = 0";
                $sql .= " where (idpedido = ".$id.")";
                break;
            case "NFS":
                $sql .= "idnfsaida = 0";
                $sql .= " where (idnfsaida = ".$id.")";
                break;
            default :
                $quantAtual = 0;
                $quantReservada = 0;
        }
        $sql .= " and (centrocusto = ".$filial.") AND (CODPRODUTO = ".$produto.") AND  (status = 1) ";
        $sql .= " order by FABDATAVALIDADE DESC, FABLOTE limit ".$qtde;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->result;
    }    
    
    /**
     * Funcao exclui produto da reserva para quantidade solicitada para determinado ID
     * @name produtoReservaExclui
     * @param NUMBER filial para consulta da quantidade do produto
     * @param VAHCHAR produto produto a ser pesquisada
     * @param VAHCHAR origem sistema que está solicitando a reserva
     *                (PED, CAT, NFS, LOT (ESTOQUE LOTE), TEC (ESTOQUE LOTE TÉCNICO))  
     * @param NUMBER id numero do id da solicitação origem
     * @return NUMBER quantidade
     * @utilizado produtoQtde ( interno )
     */

    public static function produtoBaixaEstorna($filial, $origem, $id, $produto, $qtde, $conn=null) {

        $sql = "update EST_PRODUTO_ESTOQUE ";
        $sql .= "set status=1, ";
        $sql .= "idnfsaida = 0";
        $sql .= " where (idnfsaida = ".$id.")";
        $sql .= " and (centrocusto = ".$filial.") AND (CODPRODUTO = ".$produto.") AND  (status = 9) ";

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->result;
    }    
    
    
    /**
     * Funcao baixa quantidade reservada para determinado ID de Pedido
     * @name produtoBaixaReserva
     * @param NUMBER filial para consulta da quantidade do produto
     * @param VAHCHAR produto produto a ser pesquisada
     * @param NUMBER idPedido numero do id do pedido a ser baixado
     * @param NUMBER idNf numero do id da NF 
     */

    public function produtoBaixaReserva($filial, $idPedido, $idNf, $produto, $conn=null) {

        $sql = "update EST_PRODUTO_ESTOQUE ";
        $sql .= "set status=9, ";
        $sql .= "idnfsaida = ".$idNf;
        $sql .= " WHERE (centrocusto = ".$filial.") AND (CODPRODUTO = ".$produto.") AND  (status = 1) AND (IDPEDIDO=".$idPedido.")";

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->result;
    }    

    /**
     * Funcao baixa quantidade do estoque por perda, buscando produtos com data de fabricação mais antigo.
     * @name produtoBaixaPerda
     * @param NUMBER filial para consulta da quantidade do produto
     * @param VAHCHAR produto produto a ser pesquisada
     * @param NUMBER idNf numero do id da NF 
     * @param NUMBER quant numero quantidade a ser baixada.
     */

    public function produtoBaixaPerda($filial, $produto, $quant, $idNf, $conn=null) {

        $sql = "update EST_PRODUTO_ESTOQUE ";
        $sql .= "set status=8, ";
        $sql .= "idnfsaida = ".$idNf;
        $sql .= " WHERE (centrocusto = ".$filial.") AND (CODPRODUTO = ".$produto.") AND (status = 0)";
        $sql .= " order by FABDATAVALIDADE ";
        $sql .= " limit ".$quant;

        $banco = new c_banco();
        $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        return $banco->row;
    }    


    /**
     * Funcao consulta produtos em reserva por idpedido agrupando por FABDATAVALIDADE e FABLOTE
     * @name consultaProdutoReserva
     * @param NUMBER idPedido numero do id do pedido para consulta
     * @return array   QUANT, ID, CODPRODUTO, FABLOTE, FABDATAVALIDADE, NS
     */

    public function consultaProdutoReserva($idPedido) {

        $sql = "SELECT count(FABDATAVALIDADE) AS QUANT, ID, CODPRODUTO, FABLOTE, FABDATAVALIDADE, FABDATAFABRICACAO, NS FROM EST_PRODUTO_ESTOQUE ";
        $sql .= " WHERE  (status = 1) AND (IDPEDIDO=".$idPedido.") ";
        $sql .= "group by FABDATAVALIDADE, FABLOTE ";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }    


//---------------------------------------------------------------
//---------------------------------------------------------------

    public function selectProdutoEstoqueLetra($letra, $tipoSql=null) {

        $par = explode("|", $letra);
        $dataIni = c_date::convertDateTxt($par[3]);
        $dataFim = c_date::convertDateTxt($par[4]);

        switch ($tipoSql):
            case "V": //  data validade
                $sql = "SELECT count(e.FABDATAVALIDADE) AS QUANT, e.ID, e.CODPRODUTO, p.DESCRICAO, e.FABLOTE, e.FABDATAVALIDADE, e.FABDATAFABRICACAO, e.NS FROM EST_PRODUTO_ESTOQUE e ";
                $sql .= "inner join EST_PRODUTO p on (p.CODIGO=e.CODPRODUTO) ";
                $order = "";
                $group = "group by FABDATAVALIDADE, FABLOTE ";
                
                if ($par[2] != ''){ // caso cod fabricante-> iginorar todos os filtros
                    $sql .= "WHERE (p.codFabricante LIKE '%".$par[2]."') ";
                    $iswhere = true;
                }else{
                    $iswhere = true;
                    $sql .= "WHERE (e.status in(0,1)) ";
                    
                    if ($par[0]!= ''){
                       if ($iswhere){
                            $sql .= "and (p.descricao LIKE '%".$par[0]."%') ";
                        }else{
                            $sql .= "WHERE (p.descricao LIKE '%".$par[0]."%') ";
                            $iswhere = true;
                        } 
                    }
                    if ($par[1] != ''){
                        if ($iswhere){
                            $sql .= "and (p.grupo = '".$par[1]."') ";
                        }else{
                            $sql .= "where (p.grupo = '".$par[1]."') ";
                            $iswhere = true;
                        }
                    }
                    if ($par[3] != ''){
                        if ($iswhere){
                            $sql .= "and (p.localizacao = '".$par[3]."') ";
                        }else{
                            $sql .= "where (p.localizacao = '".$par[3]."') ";
                            $iswhere = true;
                        }
                    }
                    if ($par[5] == 'T'){
                        $fora = "not";
                        if ($iswhere){
                            $sql .= "and (".$fora." isnull(p.dataforalinha)) ";
                        }else{
                            $sql .= "where (".$fora." isnull(p.dataforalinha)) ";
                        }
                    }
                }
                
                break;
            default:
                $sql = "SELECT DISTINCT p.*, n.numero, n.emissao, n.centrocusto, n.tipo, n.situacao, t.padrao as tipoNota ";
                $sql .= "FROM est_nota_fiscal_produto p ";
                $sql .= "inner join est_nota_fiscal n on n.id = p.idnf ";
                $sql .= "inner join fin_centro_custo r on n.centrocusto = r.centrocusto ";
                $sql .= "inner join fin_cliente c on c.cliente = n.pessoa ";
                $sql .= "inner join amb_ddm t on ((t.alias='EST_MENU') and (t.campo='TipoNotaFiscal') and (t.tipo = n.tipo)) ";
                $order .= "ORDER BY n.centrocusto, n.serie, n.numero ";
                $group = "";
                
                $iswhere = false;

                if ($par[5] != '') { // CASO PESQUISA POR NUMERO DA NF
                    $sql .= "WHERE (N.NUMERO = '" . $par[5] . "') ";
                } else {

                    if ($par[3] != '') { // PERIODO INICIAL DE PESQUISA
                        $iswhere = true;
                        $sql .= "WHERE (N.EMISSAO >= '" . $dataIni . "') ";
                    }

                    if ($par[4] != '') { // PERIODO FINAL DE PESQUISA
                        if ($iswhere) {
                            $sql .= "AND (N.EMISSAO <= '" . $dataFim . "') ";
                        } else {
                            $iswhere = true;
                            $sql .= "WHERE (N.EMISSAO <= '" . $dataFim . "') ";
                        }
                    }

                    if (($par[0] != '') and ( $par[0] != '0')) { // FILIAL
                        if ($iswhere) {
                            $sql .= "AND (N.CENTROCUSTO= " . $par[0] . ") ";
                        } else {
                            $iswhere = true;
                            $sql .= "WHERE (N.CENTROCUSTO = " . $par[0] . ") ";
                        }
                    }// FIM FILIAL

                    if ($par[1] != '') {  //TIPO DA NF 0-ENTRADA, 1 SAIDA
                        if ($iswhere) {
                            $sql .= "AND (N.TIPO= " . $par[1] . ") ";
                        } else {
                            $iswhere = true;
                            $sql .= "WHERE (N.TIPO = " . $par[1] . ") ";
                        }
                    }// FIM TIPO DA NF

                    if (($par[2] != '') and ( $par[2] != '0')) { // SITUACAO DA NOTA
                        if ($iswhere) {
                            $sql .= "AND (N.SITUACAO= '" . $par[2] . "') ";
                        } else {
                            $iswhere = true;
                            $sql .= "WHERE (N.SITUACAO = '" . $par[2] . "') ";
                        }
                    }
                } // if numero
                
        endswitch;


        $sql .= $group.$order;
      // echo strtoupper($sql);
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    
    /**
     * @name     incluiProdutoEstoque
     * @param    string gets de todos os objetos private da classe
     * @return   INSERT retorna VAZIO caso a insercao ocorra com sucesso
     */ 
    public function incluiProdutoEstoque($conn=null) {
        $banco = new c_banco;
        $sql = "INSERT INTO EST_PRODUTO_ESTOQUE (";
        $sql .= "IDNFENTRADA, CODPRODUTO, CENTROCUSTO, STATUS, APLICADO, NS,  ";
        $sql .= "FABLOTE, FABDATAVALIDADE, FABDATAFABRICACAO, LOCALIZACAO, OBS )";
        $sql .= "values ( ";
        $sql .= $this->getIdNfEntrada().", '".  $this->getCodProduto()."', ".  $this->getCentroCusto().", ";
        $sql .= $this->getStatus().", '".  $this->getAplicado()."', '".  $this->getNsEntrada()."', '";
        $sql .= $this->getFabLote()."', ".  ($this->getDataValidade('B') != '' ? "'".$this->getDataValidade('B')."'" : 'null' ) .", ".  ( $this->getDataFabricacao('B') != '' ? "'".$this->getDataFabricacao('B')."'" : 'null' ).", '";
        $sql .= $this->getLocalizacao()."', '".  $this->getObs()."');";
       // echo strtoupper($sql) . "<BR>";
        $resProduto = $banco->exec_sql($sql, $conn);
        $banco->close_connection();
        if ($resProduto > 0) {
            return '';
        } else {
            return 'Os dados do Item ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }//if
    }

    /**
     * @name     alteraProdutoEstoque
     * @param    string gets de todos objetos private da classe
     * @return   UPDATE retorna VAZIO caso o update ocorra com sucesso
     */
    public function alteraProdutoEstoque() {
        $sql = "UPDATE EST_PRODUTO_ESTOQUE ";
        $sql .= "SET  status = " . $this->getStatus() . ", ";
        $sql .= "aplicado = '" . $this->getAplicado() . "', ";
        $sql .= "obs = '" . $this->getObs() . "' ";
        $sql .= "WHERE (id = '" . $this->getId() . "');";
     // echo strtoupper($sql) . "<BR>";
        $banco = new c_banco;
        $resProdutoUser = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProdutoUser > 0) {
            return '';
        } else {
            return 'Os dados do produto ' . $this->getDesc() . ' n&atilde;o foi alterado!';
        }//if
    }
    
}//	END OF THE CLASS
?>
