<?php
/**
 * @package   astec
 * @name      c_produto_quant
 * @version   3.5.00
 * @copyright 2018
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva
 * @date      23/09/2018
 * @utilizado select_quantidade_empresa
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../class/est/c_produto.php");

//Class C_PRODUTO_QTDE
Class c_produto_qtde extends c_user {

// Campos tabela | Objetos da Classe
    private $codPeca = NULL;
    private $descricao = NULL;
    private $quant = NULL;
    private $filial = NULL;

//construtor
    function __construct() {
        
    }

//---------------------------------------------------------------
//---------------------------------------------------------------

    public function setCodPeca($codPeca) {
        $this->codPeca = $codPeca;
    }

    public function getCodPeca() {
        return $this->codPeca;
    }

    public function setDescricao() {
        $produto = new c_produto();
        $produto->setId($this->getCodPeca());
        $reg_nome = $produto->select_produto();
        $this->descricao = $reg_nome[0]['DESCRICAO'];
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setQuant($quant) {
        $this->quant = $quant;
    }

    public function getQuant() {
        return $this->quant;
    }

    public function setFilial($filial) {
        $this->filial = $filial;
    }

    public function getFilial() {
        return $this->filial;
    }

//############### FIM SETS E GETS ###############



/**
 * Funcao para verificar a quantidade do ultimo inventário
 * @name select_data_referenciao
 * @param VAHCHAR produto produto a ser pesquisada
 * @return DATE data base para consulta
 * @param NUMBER filial para consulta da quantidade do produto
 * @param NUMBER projeto para consulta da quantidade do produto
 * @utilizado select_quantidade_old (interno)
 */
    public function select_qtde_inventario($data, $produto, $filial, $projeto) {

        $sql = "SELECT quantidade ";
        $sql .= "FROM est_inventario ";
        $sql .= "WHERE (codigoproduto = " . $produto . ")";

        if ($data != '') {
            $sql .= " AND (referencia =  '" . $data . "') ";
        }
        if (($filial != '') and ( $filial != '0')) {
            $sql .= "AND (centrocusto = " . $filial . ") ";
        }

        if (($projeto != '') and ( $projeto != '0')) {
            $sql .= "AND (projeto = " . $projeto . ") ";
        }

         //ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

//fim select_qtde_inventario


/**
 * Funcao para verificar a quantidade do ultimo inventário
 * @name select_qtde_NF
 * @param VAHCHAR produto produto a ser pesquisada
 * @param CHAR tipo da nota fiscal ENTRADA ou SAIDA
 * @return DATE data base para consulta
 * @param NUMBER filial para consulta da quantidade do produto
 * @param NUMBER projeto para consulta da quantidade do produto
 * @utilizado select_quantidade_old (interno), KARDEX
 */    
    public function select_quantidade_nf($data, $tipo, $produto, $filial) {

        $sql = "SELECT sum(quant) as quant ";
        $sql .= "FROM est_nota_fiscal_produto p ";
        $sql .= "INNER JOIN est_nota_fiscal n ON (n.id = p.idnf) ";
        $sql .= "WHERE (p.codproduto = " . $produto . ") AND  (n.tipo='" . $tipo . "') ";
        $sql .= "AND (n.situacao = 'B') ";

        if ($data != '') {
            $sql .= " AND (p.dataconferencia >  '" . $data . "') ";
        }
        if (($filial != '') and ( $filial != '0')) {
            $sql .= "AND (n.centrocusto = " . $filial . ") ";
        }

       // ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

//fim select_qtde_NF

/**
 * Funcao para calcular ataves da nf de entrada e saída e quatidade reservado pelos pedidos não faturados.
 * @name select_qtde_NF
 * @param VAHCHAR produto produto a ser pesquisada
 * @param NUMBER filial para consulta da quantidade do produto
 * @utilizado select_quantidade (interno), KARDEX
 */      
public function select_quantidade($produto, $filial) {
//    //ultima data que foi feito o inventario
//    $reg_data = $this->select_data_referencia($produto);
//    $data = $reg_data[0]['REFERENCIA'];
//
//    //quantidade do produto pelo inventario
//    $reg_inventario = $this->select_qtde_inventario($data, $produto, $filial, $projeto);
//    if ($reg_inventario == '') {
//        $inventario = 0;
//    } else {
//        $inventario = $reg_inventario[0]['QUANTIDADE'];
//    }


    //quantidade do produto pela nota fiscal de entrada
    $inventario = 0;
    $quantReservada = 0;
    $data = '';
    $reg_notaFiscalEntrada = $this->select_quantidade_nf($data, '0', $produto, $filial);
    if (($reg_notaFiscalEntrada[0]['QUANT'] == '') || ($reg_notaFiscalEntrada[0]['QUANT'] == NULL)) {
        $notaFiscalEntrada = 0;
    } else {
        $notaFiscalEntrada = $reg_notaFiscalEntrada[0]['QUANT'];
    }

    //quantidade do produto pela nota fiscalde saida
    $reg_notaFiscalSaida = $this->select_quantidade_nf($data, '1', $produto, $filial);
    if (($reg_notaFiscalSaida[0]['QUANT'] == '') || ($reg_notaFiscalSaida[0]['QUANT'] == NULL)) {
        $notaFiscalSaida = 0;
    } else {
        $notaFiscalSaida = $reg_notaFiscalSaida[0]['QUANT'];
    }

    //calculo para obter a quantidade do produto em estoque
    return ($inventario + $notaFiscalEntrada - $notaFiscalSaida - $quantReservada);
}

//fim select_quantidade
}

//	END OF THE CLASS
?>
