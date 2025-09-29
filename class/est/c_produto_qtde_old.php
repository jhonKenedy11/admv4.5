<?php
/**
 * @package   astec
 * @name      c_produto_qtde
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      13/04/2016
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
    function c_produto_qtde() {
        
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
 * Funcao consulta a quantidade de produto por filial tabela PRODUTO_OS NOVA
 * @name produtoQtde
 * @param VAHCHAR produto produto a ser pesquisada
 * @param NUMBER filial para consulta da quantidade do produto
 * @return NUMBER quantidade
 * @utilizado select_quantidade_empresa, select_quantidade ( interno )
 */

public function produtoQtde($produto, $filial) {
    $sql = "SELECT status, count(CODPRODUTO) AS 'Quantidade' FROM EST_PRODUTO_ESTOQUE ";
    $sql .= "WHERE (centrocusto = ".$filial.") AND (CODPRODUTO = ".$produto.") AND  (status <> 9)" ;
    $sql .= "GROUP BY status";
    
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}    

    
/**
 * Funcao consulta se o produto esta alguma quantidade disponivel na lista de estoque , server para listar produtos com estoque.
 * @name consultaProdutoEstoque
 * @param VAHCHAR produto produto a ser pesquisada
 * @param NUMBER filial para consulta da quantidade do produto
 * @return BOOLEAN 
 * @utilizado p_pedido_online
 */

public function consultaProdutoEstoque($produto, $filial) {
    $sql = "SELECT * FROM EST_NOTA_FISCAL_PRODUTO_OS ";
    $sql .= "WHERE (CODPRODUTO = '".$produto."') and ((idlote='') or (idlote='0')) and (centrocusto = '".$filial."')";

    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}



    
/**
 * Funcao para verificar a data do ultimo inventário
 * @name select_data_referenciao
 * @param VAHCHAR produto produto a ser pesquisada
 * @return DATE data base para consulta
 * @utilizado select_quantidade_old (interno)
 */
    public function select_data_referencia($produto) {

        $sql = "SELECT max(referencia) as referencia ";
        $sql .= "FROM est_inventario ";
        //$sql .= "WHERE (codigoproduto = ".$this->getCodPeca().")";
          //ECHO strtoupper($sql)."<BR>";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

/**
 * Funcao para verificar a quantidade de um produto por filial
 * @name select_at_pecas_qtde
 * @param VAHCHAR produto produto a ser pesquisada
 * @param NUMBER filial para consulta da quantidade do produto
 * @return NUMBER quantidade
 * @utilizado select_quantidade_empresa (interno)
 */
    public function select_at_pecas_qtde($produto, $filial) {
        $sql = "SELECT * FROM est_nota_fiscal_produto_os ";
        $sql .= "WHERE (CODPRODUTO = '".$produto."') and ((userproduto = '') or(userproduto = '0')) and ((idlote='') or (idlote='0')) ";
        $sql .= "AND (centrocusto = '".$filial."')";
        $sql .= "AND ((aplicado <> 'S') or (aplicado <> 'D')) ";
            
        
        
//        $sql = "SELECT * FROM CAT_AT_PECAS ";
//        $sql .= "WHERE SITUACAO IN ('RESERVADO', 'TECNICO','APLICADO') ";
//        $sql .= "and (itemfabricante = '".$produto."')";
        //$sql .= "WHERE (codigoproduto = ".$this->getCodPeca().")";
        //  ECHO strtoupper($sql)."<BR>";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

/**
 * Funcao consulta a quantidade de produto por Usuario
 * @name select_nf_prod_os_user_produto
 * @param VAHCHAR produto produto a ser pesquisada
 * @param CHAR aplicado se retorna peças aplicadas e devolvidas
 * @return NUMBER quantidade
 * @utilizado Kardex
 */
    public function select_nf_prod_os_user_produto($produto, $aplicado=null) {

        $sql = "SELECT * FROM est_nota_fiscal_produto_os ";
        $sql .= "WHERE (codproduto = '".$produto."') ";
        if ($aplicado == 'S'){
            $sql .= "and (aplicado in ('s','d')) ";
        }else if ($aplicado == 'N'){
            $sql .= "and ((aplicado <> 's') and (aplicado <> 'D')) ";
        }
        $sql .= "and (userproduto <> ''); ";
        //$sql .= "WHERE (codigoproduto = ".$this->getCodPeca().")";
          //ECHO strtoupper($sql)."<BR>";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

/**
 * Funcao consulta a quantidade de produto por filial tabela PRODUTO_OS NOVA
 * @name select_nf_produto_os_qtde
 * @param VAHCHAR produto produto a ser pesquisada
 * @param NUMBER filial para consulta da quantidade do produto
 * @return NUMBER quantidade
 * @utilizado select_quantidade_empresa, select_quantidade ( interno )
 */

    public function select_nf_produto_os_qtde($produto, $filial) {
        $sql = "SELECT * FROM EST_NOTA_FISCAL_PRODUTO_OS ";
        $sql .= "WHERE (CODPRODUTO = '".$produto."') and ((idlote='') or (idlote='0')) and (centrocusto = '".$filial."')";
        //$sql .= "WHERE (codigoproduto = ".$this->getCodPeca().")";
         // ECHO strtoupper($sql)."<BR>";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

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
    public function select_qtde_NF($data, $tipo, $produto, $filial, $projeto) {

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

        if (($projeto != '') and ( $projeto != '0')) {
            $sql .= "AND (p.projeto = " . $projeto . ") ";
        }

       // ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

//fim select_qtde_NF

/**
 * <b> SQL para verificar todas as notas fiscais de um determinado produto. Utilizado: p_kardex. </>
 * @name select_qtde_NF_prod_os
 * @param DATE $data default null
 * @param INT $tipo tipo da NF 0-entrada, 1 - saida
 * @param INT $produto codigo interno do produto
 * @param INT $filial codigo do centro de custo - 10000000
 * @param VARCHAR $projeto codigo do projeto
 * @utilizado KARDEX
 * @return o.*, n.numero, c.nomereduzido, n.natoperacao
 */
    public function select_qtde_NF_prod_os($data=null, $tipo, $produto, $filial=null, $projeto=null) {

        $sql = "SELECT o.*, n.numero, c.nomereduzido, n.natoperacao, N.EMISSAO ";
        $sql .= "FROM est_nota_fiscal_produto_os o ";
        $sql .= "left JOIN est_nota_fiscal n ON (n.id = o.idnfentrada) ";
        $sql .= "left JOIN fin_cliente c ON (n.pessoa = c.cliente) ";
        $sql .= "WHERE (o.codproduto = " . $produto . ") AND  (n.tipo='" . $tipo . "') ";
        //$sql .= "AND (n.situacao = 'B') ";
      
        if (($filial != '') and ( $filial != '0')) {
            $sql .= "AND (n.centrocusto = " . $filial . ") ";
        }//if
        if (($projeto != '') and ( $projeto != '0')) {
            $sql .= "AND (p.projeto = " . $projeto . ") ";
        }//if

       // ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

//fim select_qtde_NF_prod_os

/**
 * <b> SQL para verificar todas as notas fiscais de um determinado produto. Utilizado: p_kardex. </>
 * @name select_qtde_NF_prod_os
 * @param DATE $data default null
 * @param INT $tipo tipo da NF 0-entrada, 1 - saida
 * @param INT $produto codigo interno do produto
 * @param INT $filial codigo do centro de custo - 10000000
 * @param VARCHAR $projeto codigo do projeto
 * @utilizado KARDEX
 * @return o.*, n.numero, c.nomereduzido, n.natoperacao
 */
    public function select_NF_letra($data, $tipo, $produto, $filial, $projeto) {

        $sql = "SELECT c.nomereduzido, n.*, p.* ";
        $sql .= "FROM est_nota_fiscal_produto p ";
        $sql .= "left JOIN est_nota_fiscal n ON (n.id = p.idnf) ";
        $sql .= "left JOIN fin_cliente c ON (c.cliente = n.pessoa) ";
        $sql .= "WHERE (p.codproduto = " . $produto . ") AND  (n.tipo='" . $tipo . "') ";
        $sql .= "AND (n.situacao = 'B') AND (P.DATACONFERENCIA <> '0000-00-00 00:00:00') ";

        if ($data != '') {
            $sql .= " AND (p.dataconferencia >  '" . $data . "') ";
        }
        if (($filial != '') and ( $filial != '0')) {
            $sql .= "AND (n.centrocusto = " . $filial . ") ";
        }

        if (($projeto != '') and ( $projeto != '0')) {
            $sql .= "AND (p.projeto = " . $projeto . ") ";
        }

        //ECHO strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

//fim select_qtde_NF

/**
 * <b> SQL para retornar a quantidade do produto DE UMA EMPRESA
 * @name select_quantidade_empresa
 * @param INT $produto codigo interno do produto
 * @param VARCHAR $projeto codigo do projeto (não utilizado)
 * @utilizado P_PRODUTO
 * @return QUANTIDADE
 */
    public function select_quantidade_empresa($produto, $filial, $projeto) {

//        $qtdeNFEntrada = count($this->select_nf_produto_os_qtde($produto,$filial));
//        $qtdeProdReserva = count($this->select_at_pecas_qtde($produto, $filial));
//        echo "peca nova".$qtdeNFEntrada." - peca reservada".$qtdeProdReserva;
        
        
        return count($this->select_at_pecas_qtde($produto, $filial));
    }

/**
 * <b> SQL para retornar a quantidade do produto
 * @name select_quantidade_empresa
 * @param INT $produto codigo interno do produto
 * @param VARCHAR $projeto codigo do projeto (não utilizado)
 * @utilizado P_PRODUTO, P_REAVALIA_CHAMADO, P_TECNICO_OS
 * @return QUANTIDADE
 */
    public function select_quantidade($produto, $filial, $projeto=null) {

        $qtdeNFEntrada = count($this->select_nf_produto_os_qtde($produto, $filial));
       // $qtdeProdReserva = count($this->select_at_pecas_qtde($produto));
        
        
        
        return ($qtdeNFEntrada-$qtdeProdReserva);
    }
    


// UTILZIADA QUANDO ESTOQUE CALCULADO PELAS NFs    
    public function select_quantidade_old($produto, $filial, $projeto) {
        //ultima data que foi feito o inventario
        $reg_data = $this->select_data_referencia($produto);
        $data = $reg_data[0]['REFERENCIA'];
        
        //quantidade do produto pelo inventario
        $reg_inventario = $this->select_qtde_inventario($data, $produto, $filial, $projeto);
        if ($reg_inventario == '') {
            $inventario = 0;
        } else {
            $inventario = $reg_inventario[0]['QUANTIDADE'];
        }


        //quantidade do produto pela nota fiscal de entrada
        $reg_notaFiscalEntrada = $this->select_qtde_NF($data, '0', $produto, $filial, $projeto);
        if (($reg_notaFiscalEntrada[0]['QUANT'] == '') || ($reg_notaFiscalEntrada[0]['QUANT'] == NULL)) {
            $notaFiscalEntrada = 0;
        } else {

            $notaFiscalEntrada = $reg_notaFiscalEntrada[0]['QUANT'];
        }

        //quantidade do produto pela nota fiscalde saida
        $reg_notaFiscalSaida = $this->select_qtde_NF($data, '1', $produto, $filial, $projeto);
        if (($reg_notaFiscalSaida[0]['QUANT'] == '') || ($reg_notaFiscalSaida[0]['QUANT'] == NULL)) {
            $notaFiscalSaida = 0;
        } else {
            $notaFiscalSaida = $reg_notaFiscalSaida[0]['QUANT'];
        }
        //echo "<BR> passou".$inventario."+".$notaFiscalEntrada."+".$notaFiscalSaida;
        //calculo para obter a quantidade do produto em estoque
        return ($inventario + $notaFiscalEntrada - $notaFiscalSaida);
    }

//fim select_quantidade
}

//	END OF THE CLASS
?>
