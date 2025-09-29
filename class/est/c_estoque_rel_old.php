<?php
/**
 * @package   astec
 * @name      c_estoque_rel
 * @version   4.3
 * @copyright 2020
 * @link      http://www.admservice.com.br/
 * @author    
 * @date      18/05/2020
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");

//Class C_PRODUTO
Class c_estoque_rel extends c_user {

/**
 * Função  where para consulta do relatorio de curva abc 
 * @param $sql, comando que ira ser concatenado 
 * baseados nos filtros [DataInicio, DataFim, Data Referencia, Cod Produto, Id Pessoa, 
 * Num NotaFiscal, CentroCusto e tipoCurvaABC ]
 * grupoProduto var a parte
 * @name where_curva_ABC
 * @return string sql concatenada com os filtros selecionados * 
 */
public function where_curva_ABC($sql){
    /*
    $this->m_par[0] = DataInicio
    $this->m_par[1] = DataFim
    $this->m_par[2] = Data Referencia
    $this->m_par[3] = Cod Produto
    $this->m_par[4] = Id Pessoa
    $this->m_par[5] = Num Nota Fiscal
    $this->m_par[6] = centrocusto
    $this->m_par[7] = tipoCurvaABC
    */ 
    $grupo   = explode("|", $this->m_grupo);
    $dataIni = c_date::convertDateTxt($this->m_par[0]);
    $dataFim = c_date::convertDateTxt($this->m_par[1]);
    $tipoCurvaAbc = $this->m_par[7];

    $cond = strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[0]) ? '':" $cond (P.EMISSAO >= '$dataIni') ";

    $cond = strpos($sql, 'where') === false ? 'where' : 'and';
    $sql.=  empty($this->m_par[1]) ? '':" $cond (P.EMISSAO <= '$dataFim') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[3]) ? '':" $cond (PRO.CODIGO = '".$this->m_par[3]."')";

    $cond = strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[6]) ? '':" $cond (P.CCUSTO = '".$this->m_par[6]."') ";

    $grupos = '';
    for($i = 1; $i < count($grupo); $i++){
        $grupos .= "'".$grupo[$i]."',";
    }
    $grupos = strstr($grupos, ',', true);
    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($grupos) ? '':" $cond (PRO.GRUPO IN (".$grupos.")) ";

    $sql .= "GROUP BY I.ITEMESTOQUE ";
    $sql .= "ORDER BY ".$tipoCurvaAbc. " DESC";

    return $sql;
}

/**
 * Consulta ao Banco para gerar o relatorio de curva ABC
 * baseados nos filtros [DataInicio, DataFim, Cod Produto, CentroCusto, Grupo de Produtos, 
 * tipoCurvaABC]
 * @name select_relatorio_curva_abc
 * @return ARRAY  os campos [CENTROCUSTO, ITEMESTOQUE, GRUPO, PRODUTO, CODFABRICACAO, QUANTMIN, 
 * VENDA, EMISSAO, SUM(QTSOLICITADA), SUM(I.TOTAL) COUNT(I.ID)] 
 */
public function select_relatorio_curva_abc(){
    $sql =  "SELECT P.CCUSTO, I.ITEMESTOQUE, G.DESCRICAO AS GRUPO, PRO.DESCRICAO, PRO.CODFABRICANTE, ";
    $sql .= "PRO.QUANTMINIMA, PRO.VENDA , P.EMISSAO, ";
    $sql .= "SUM(QTSOLICITADA) AS QUANT, ";
    $sql .= "SUM(I.TOTAL) AS VALOR, ";
    $sql .= "COUNT(I.ID) AS NUMVENDAS ";
    $sql .= "FROM FAT_PEDIDO P ";
    $sql .= "JOIN FAT_PEDIDO_ITEM I ON (P.ID = I.ID) ";
    $sql .= "JOIN EST_PRODUTO PRO ON (PRO.CODIGO = I.ITEMESTOQUE) ";
    $sql .= "JOIN EST_GRUPO G ON (G.GRUPO = PRO.GRUPO) ";
    
    $sql = $this->where_curva_ABC($sql);
    
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    $result = $banco->resultado;
    return $result;
}

public function select_estoque_nf($codProd, $ccusto, $dataIni, $dataFim, $saldoInicial = NULL){

    $sql = "SELECT NFP.CODPRODUTO, NFP.DESCRICAO,
		            SUM(CASE WHEN NF.TIPO = 0 THEN NFP.QUANT END) AS ENTRADA, 
                    SUM(CASE WHEN NF.TIPO = 1 THEN NFP.QUANT END) AS SAIDA,
        CC.DESCRICAO AS CENTROCUSTO FROM EST_NOTA_FISCAL_PRODUTO NFP 
        LEFT JOIN EST_NOTA_FISCAL NF ON (NFP.IDNF = NF.ID)
        LEFT JOIN FIN_CENTRO_CUSTO CC ON (NF.CENTROCUSTO = CC.CENTROCUSTO) ";
        $sql .= "WHERE NFP.CODPRODUTO = '".$codProd."' ";
        $sql .= " AND (NF.CENTROCUSTO = '".$ccusto."') ";
        if($saldoInicial == true){
            $sql .= " AND (NF.DATASAIDAENTRADA < '$dataIni') ";
        }else{
        $sql .= "AND ((NF.DATASAIDAENTRADA >= '".$dataIni."') 
                 AND (NF.DATASAIDAENTRADA <= '".$dataFim."'))";
        }
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
}

public function select_estoque_ped($codProd, $ccusto, $dataIni, $dataFim, $saldoInicial = NULL){
    
    $sql = "SELECT PI.ITEMESTOQUE, PI.DESCRICAO, 
                SUM(QTSOLICITADA) AS SAIDA, CCP.DESCRICAO AS CCUSTO  FROM FAT_PEDIDO_ITEM PI
        LEFT JOIN FAT_PEDIDO P ON (PI.ID = P.ID)
        LEFT JOIN FIN_CENTRO_CUSTO CCP ON (P.CCUSTO = CCP.CENTROCUSTO) ";
        $sql .= "WHERE P.SITUACAO = 9 AND PI.ITEMESTOQUE = '".$codProd."' ";
        $sql .= " AND (P.CCUSTO = '".$ccusto."') ";
        if($saldoInicial == true){
            $sql .= " AND (P.EMISSAO < '$dataIni') ";
        }else{
            $sql .= "AND ((P.EMISSAO >= '".$dataIni."') 
                 AND (P.EMISSAO <= '".$dataFim."'))";
        }
        

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
}

/**
 * Consulta para o Banco atraves de parametros para gerar o relatorio de kardex sintetico
 * baseados nos filtros [DataInicio, DataFim, Data Referencia, Cod Produto, Id Pessoa, 
 * Num NotaFiscal, CentroCusto, Grupo de Produtos]
 * @name select_relatorio_kardex_sintetico
 * @return ARRAY todos os campos da table * 
 */
public function select_relatorio_kardex_sintetico($par, $grupo){
    /*
    $par[0] = DataInicio
    $par[1] = DataFim
    $par[2] = Data Referencia
    $par[3] = Cod Produto
    $par[4] = Id Pessoa
    $par[5] = Num Nota Fiscal
    $par[6] = centrocusto
    */ 
    $dataIni = c_date::convertDateTxt($par[0]) ;
    $dataFim = c_date::convertDateTxt($par[1]) ;

    $sql =  "SELECT PROD.CODIGO, PROD.DESCRICAO, G.DESCRICAO AS DESCGRUPO ";
    $sql .= "FROM EST_PRODUTO PROD ";
    $sql .= "LEFT JOIN EST_GRUPO G ON (PROD.GRUPO = G.GRUPO ) ";
    
  
    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($par[3]) ? '':" $cond (PROD.CODIGO = $par[3]) ";

    $grupos = '';
    for($i = 1; $i < count($grupo); $i++){
        $grupos .= "'".$grupo[$i]."',";
    }
    $grupos = strstr($grupos, ',', true);
    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($grupos) ? '':" $cond (PROD.GRUPO IN (".$grupos.")) ";
   
    $sql .= "GROUP BY PROD.CODIGO ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    //return $banco->resultado;
    $result = $banco->resultado;

    for($i=0;$i < count($result); $i++){
        
        $nfResult = $this->select_estoque_nf($result[$i]['CODIGO'],$par[6], $dataIni, $dataFim);
        
        $pedResult = $this->select_estoque_ped($result[$i]['CODIGO'], $par[6], $dataIni, $dataFim);

        $entrada = (($nfResult[0]['ENTRADA']));
        $saida = (($nfResult[0]['SAIDA']) + ($pedResult[0]['SAIDA']));

        $saldo = ($entrada - $saida);

        $nfSaldoInicial = $this->select_estoque_nf($result[$i]['CODIGO'], $par[6], $dataIni, $dataFim, true);
        
        $pedSaldoInicial = $this->select_estoque_ped($result[$i]['CODIGO'], $par[6],$dataIni, $dataFim, true);

        $saldoInicial = ($nfSaldoInicial[0]['ENTRADA'] - ($nfSaldoInicial[0]['SAIDA'] + $pedSaldoInicial[0]['SAIDA']));
        
        $saldoAtual = ($saldoInicial + $saldo);
        $resp[$i] = [
            'CENTROCUSTO'   => $nfResult[0]['CENTROCUSTO'],
            'CODIGO'        => $result[$i]['CODIGO'],
            'DESCRICAO'     => $result[$i]['DESCRICAO'],
            'SALDO_INICIAL' => $saldoInicial,
            'QUANT_ENTRADA' => $entrada,
            'QUANT_SAIDA'   => $saida,
            'SALDO_ATUAL'   => $saldoAtual
        ];
    }

    return $resp;
}


/**
 * Consulta para o Banco atraves de parametros para gerar o relatorio de kardex Analitico
 * baseados nos filtros [DataInicio, DataFim, Data Referencia, Cod Produto, Id Pessoa, 
 * Num NotaFiscal, CentroCusto, Grupo de Produtos, Situação NotaFiscal, Tipo NotaFiscal]
 * @name select_relatorio_kardex
 * @return ARRAY todos os campos da table * 
 */
public function select_relatorio_kardex($par, $grupo, $sit, $tipo){
    /*
    $par[0] = DataInicio
    $par[1] = DataFim
    $par[2] = Data Referencia
    $par[3] = Cod Produto
    $par[4] = Id Pessoa
    $par[5] = Num Nota Fiscal
    $par[6] = Centro de Custo

    */ 
    $dataIni = c_date::convertDateTxt($par[0]) ;
    $dataFim = c_date::convertDateTxt($par[1]) ;

    $sqlE =  "SELECT IF (TIPO = 0, 'ENTRADA', 'SAIDA') as TIPO, 'NF' AS DOC, NF.ID, NF.NUMERO, NF.DATASAIDAENTRADA AS DATAEMISSAO, CC.DESCRICAO AS CENTROCUSTO, PROD.CODIGO, PROD.DESCRICAO, NFP.QUANT AS QUANTIDADE ";
    $sqlE .= "FROM EST_PRODUTO PROD ";
    $sqlE .= "LEFT JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (PROD.CODIGO = NFP.CODPRODUTO) ";
    $sqlE .= "LEFT JOIN EST_NOTA_FISCAL NF ON (NFP.IDNF = NF.ID) ";
    $sqlE .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (NF.CENTROCUSTO = CC.CENTROCUSTO) ";
    
    $cond =  strpos($sqlE, 'where') === false ? 'where' : 'and';
    $sqlE .= empty($par[0]) ? '':" $cond (NF.DATASAIDAENTRADA >= '$dataIni') ";

    $cond =  strpos($sqlE, 'where') === false ? 'where' : 'and';
    $sqlE .= empty($par[1]) ? '':" $cond (NF.DATASAIDAENTRADA <= '$dataFim') ";

    $cond =  strpos($sqlE, 'where') === false ? 'where' : 'and';
    $sqlE .= empty($par[3]) ? '':" $cond (PROD.CODIGO = $par[3]) ";

    $cond =  strpos($sqlE, 'where') === false ? 'where' : 'and';
    $sqlE .= empty($par[5]) ? '':" $cond (NF.NUMERO = $par[5]) ";

    $cond =  strpos($sqlE, 'where') === false ? 'where' : 'and';
    $sqlE .= empty($par[6]) ? '':" $cond (NF.CENTROCUSTO = '".$par[6]."') ";

    
    $sqlE .= "UNION ";

    $sqlS =  "SELECT 'SAIDA' as TIPO, 'PED' AS DOC, P.ID, P.ID AS NUMERO, P.EMISSAO AS DATAEMISSAO, CC.DESCRICAO AS CENTROCUSTO,PROD.CODIGO, PROD.DESCRICAO, PI.QTSOLICITADA AS QUANTIDADE ";
    $sqlS .= "FROM EST_PRODUTO PROD ";
    $sqlS .= "LEFT JOIN FAT_PEDIDO_ITEM PI ON (PROD.CODIGO = PI.ITEMESTOQUE) ";
    $sqlS .= "LEFT JOIN FAT_PEDIDO P ON (PI.ID = P.ID) ";
    $sqlS .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (P.CCUSTO = CC.CENTROCUSTO) ";
    $sqlS .= "where P.SITUACAO <> '7' ";

    $cond =  strpos($sqlS, 'where') === false ? 'where' : 'and';
    $sqlS .= empty($par[0]) ? '':" $cond (P.EMISSAO >= '$dataIni') ";

    $cond =  strpos($sqlS, 'where') === false ? 'where' : 'and';
    $sqlS .= empty($par[1]) ? '':" $cond (P.EMISSAO <= '$dataFim') ";

    $cond =  strpos($sqlS, 'where') === false ? 'where' : 'and';
    $sqlS .= empty($par[3]) ? '':" $cond (PI.ITEMESTOQUE = $par[3]) ";

    $cond =  strpos($sqlS, 'where') === false ? 'where' : 'and';
    $sqlS .= empty($par[4]) ? '':" $cond (P.CLIENTE = $par[4]) ";

    $cond =  strpos($sqlS, 'where') === false ? 'where' : 'and';
    $sqlS .= empty($par[5]) ? '':" $cond (P.ID = $par[5]) ";

    $cond =  strpos($sqlS, 'where') === false ? 'where' : 'and';
    $sqlS .= empty($par[6]) ? '':" $cond (P.CCUSTO = '".$par[6]."') ";

    
    $sqlS .= "order by DATAEMISSAO, TIPO";

    $sql = $sqlE.$sqlS;

    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

/**
 * Consulta para o Banco atraves de parametros para pegar o valor do SALDO INICIAL DE ENTRADA 
 * DO relatorio de kardex Analitico
 * baseados nos filtros [DataInicio e Cod Produto]
 * @name saldo_inicial_entrada
 * @return ARRAY com o valor do SALDO INICIAL DE ENTRADA * 
 */
public function saldo_inicial_entrada($dataIni, $codProduto){
    $dataInicio = c_date::convertDateTxt($dataIni); 

    $sql = "SELECT SUM(NFP.QUANT) AS QUANTIDADE
            FROM EST_NOTA_FISCAL_PRODUTO NFP ";
    $sql .= "INNER JOIN EST_NOTA_FISCAL NF ON (NFP.IDNF = NF.ID) ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($codProduto) ? '':" $cond (NFP.CODPRODUTO = $codProduto)";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($dataInicio) ? '':" $cond (NF.DATASAIDAENTRADA < '$dataInicio') ";

    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

/**
 * Consulta ao Banco atraves de parametros para pegar o valor do SALDO INICIAL DE SAIDA 
 * DO relatorio de kardex Analitico
 * baseados nos filtros [DataInicio e Cod Produto ]
 * @name saldo_inicial_saida
 * @return ARRAY com o valor do SALDO INICIAL DE SAIDA * 
 */
public function saldo_inicial_saida($dataIni, $codProduto){
    $dataInicio = c_date::convertDateTxt($dataIni); 
    $sql = "SELECT SUM(PI.QTSOLICITADA) AS QUANTIDADE
            FROM FAT_PEDIDO_ITEM PI ";
    $sql .= "INNER JOIN FAT_PEDIDO P ON (PI.ID = P.ID) ";
    $sql .= "where P.SITUACAO = 9 ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($codProduto) ? '':" $cond (PI.ITEMESTOQUE = $codProduto)";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($dataInicio) ? '':" $cond (P.EMISSAO < '$dataInicio') ";

    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

/**
 * Consulta ao Banco atraves de parametros para obter as Entradas do movimento de ESTOQUE  
 * baseados nos filtros [dataIni, dataFim , centroCusto, CodProduto e grupo de Produto]
 * @name movimento_estoque_entrada
 * @return ARRAY com [IDNotaFiscal, CodProduto, DESCRICAO, ENTRADA, TOTAL_ENTRADA, DATA_ENTRADA  ]  
 */
public function movimento_estoque_entrada(){
    $grupo   = explode("|", $this->m_grupo);
    $dataIni = c_date::convertDateTxt($this->m_par[0]);
    $dataFim = c_date::convertDateTxt($this->m_par[1]);

    $sql =  "SELECT NF.ID, NFP.CODPRODUTO,NFP.DESCRICAO, NFP.QUANT AS ENTRADA, NFP.TOTAL AS TOTAL_ENTRADA, 
             NF.DATASAIDAENTRADA AS DATA_ENTRADA, PROD.UNIDADE  ";
    $sql .= "FROM EST_NOTA_FISCAL NF ";
    $sql .= "LEFT JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NF.ID = NFP.IDNF) ";
    $sql .= "LEFT JOIN EST_PRODUTO PROD ON (PROD.CODIGO = NFP.CODPRODUTO) ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .=  empty($dataIni) ? '':" $cond (NF.DATASAIDAENTRADA BETWEEN '".$dataIni." 00:00:00' AND '".$dataFim." 23:59:59') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[3]) ? '':" $cond (PROD.CODIGO = '".$this->m_par[3]."') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[4]) ? '':" $cond (NF.PESSOA = '".$this->m_par[4]."') ";

    $cond = strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[6]) ? '':" $cond (NF.CENTROCUSTO = '".$this->m_par[6]."') ";

    $grupos = '';
    for($i = 1; $i < count($grupo); $i++){
        $grupos .= "'".$grupo[$i]."',";
    }
    $grupos = strstr($grupos, ',', true);
    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($grupos) ? '':" $cond (PROD.GRUPO IN (".$grupos.")) ";

   
    $sql .= "ORDER BY NF.DATASAIDAENTRADA ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

/**
 * Consulta ao Banco atraves de parametros para obter as Saidas do movimento de ESTOQUE (EST_NOTA_FISCAL e FAT_PEDIDO)  
 * baseados nos filtros [dataIni, dataFim , centroCusto, CodProduto e grupo de Produto]
 * @name movimento_estoque_saida
 * @return ARRAY com [IDPedido, CodProduto, DESCRICAO, SAIDA, TOTAL_SAIDA, DATA_SAIDA ]  
 */
public function movimento_estoque_saida(){
    $grupo   = explode("|", $this->m_grupo);
    $dataIni = c_date::convertDateTxt($this->m_par[0]);
    $dataFim = c_date::convertDateTxt($this->m_par[1]);

    $sql =  "SELECT P.PEDIDO, 'PED' AS TIPODOC, PI.ITEMESTOQUE AS CODPRODUTO ,PI.DESCRICAO, PI.QTSOLICITADA AS SAIDA, P.TOTAL AS TOTAL_SAIDA, P.EMISSAO AS DATA_SAIDA, PROD.UNIDADE ";
    $sql .= "FROM FAT_PEDIDO P ";
    $sql .= "LEFT JOIN FAT_PEDIDO_ITEM PI ON (P.ID = PI.ID) ";
    $sql .= "LEFT JOIN EST_PRODUTO PROD ON (PI.ITEMESTOQUE = PROD.CODIGO) ";
    $sql .= "LEFT JOIN EST_NOTA_FISCAL NF ON (P.ID = NF.DOC AND NF.TIPO = '1')  ";
    $sql .= "where (P.SITUACAO <> '0' AND P.SITUACAO <> '5' AND P.SITUACAO <> '7' AND  P.SITUACAO <> '8' AND   P.SITUACAO <> '10' AND  P.SITUACAO <> '11' ) ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .=  empty($dataIni) ? '':" $cond (P.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[3]) ? '':" $cond (PROD.CODIGO = '".$this->m_par[3]."') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[4]) ? '':" $cond (P.CLIENTE = '".$this->m_par[4]."') ";

    $cond = strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[6]) ? '':" $cond (P.CCUSTO = '".$this->m_par[6]."') ";

    $grupos = '';
    for($i = 1; $i < count($grupo); $i++){
        $grupos .= "'".$grupo[$i]."',";
    }
    $grupos = strstr($grupos, ',', true);
    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($grupos) ? '':" $cond (PROD.GRUPO IN (".$grupos.")) ";
    
    $sql .= "ORDER BY P.EMISSAO, P.ID ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    $banco->resultado;
    $pedSaida = $banco->resultado;

    // NOTA FISCAL SAIDA 

    $sql =  "SELECT NFP.IDNF AS PEDIDO, NFP.CODPRODUTO, NFP.DESCRICAO, NFP.QUANT AS SAIDA, NFP.TOTAL AS TOTAL_SAIDA, NF.DATASAIDAENTRADA AS DATA_SAIDA, PROD.UNIDADE ";
    $sql .= "FROM EST_NOTA_FISCAL_PRODUTO NFP ";
    $sql .= "LEFT JOIN EST_NOTA_FISCAL NF ON (NFP.IDNF = NF.ID) ";
    $sql .= "LEFT JOIN EST_PRODUTO PROD ON (NFP.CODPRODUTO = PROD.CODIGO) ";
    $sql .= "where NF.TIPO = '1' ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .=  empty($dataIni) ? '':" $cond (NF.DATASAIDAENTRADA BETWEEN '".$dataIni."' AND '".$dataFim."') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[3]) ? '':" $cond (PROD.CODIGO = '".$this->m_par[3]."') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[4]) ? '':" $cond (NF.PESSOA = '".$this->m_par[4]."') ";

    $cond = strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($this->m_par[6]) ? '':" $cond (NF.CENTROCUSTO = '".$this->m_par[6]."') ";

    $grupos = '';
    for($i = 1; $i < count($grupo); $i++){
        $grupos .= "'".$grupo[$i]."',";
    }
    $grupos = strstr($grupos, ',', true);
    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($grupos) ? '':" $cond (PROD.GRUPO IN (".$grupos.")) ";

    $sql .= "ORDER BY NF.DATASAIDAENTRADA ";
    $banco = new c_banco();
    $banco->exec_sql($sql);
    $banco->close_connection();
    $nfSaida = $banco->resultado;

    $count = count($pedSaida);
    for($i = 0; $i < count($nfSaida); $i++){
        $pedSaida[$count] = $nfSaida[$i];
        $count++;
    }

    return $pedSaida;
}
/**
 * Consulta ao Banco atraves de parametros para obter as Saidas do movimento de ESTOQUE  
 * baseados nos filtros [dataIni, dataFim , centroCusto, CodProduto e grupo de Produto]
 * @name movimento_estoque_saida
 * @return ARRAY com [IDPedido, CodProduto, DESCRICAO, SAIDA, TOTAL_SAIDA, DATA_SAIDA ]  
 */
public function select_relatorio_compras(){
    
    $grupo   = explode("|", $this->m_grupo);
	$sql  = "SELECT DISTINCT P.*, G.DESCRICAO AS NOMEGRUPO, 0 as ESTOQUE, 0 as RESERVA ";
   	$sql .= "FROM EST_PRODUTO P left JOIN EST_GRUPO G ON (G.GRUPO=P.GRUPO) ";
    $sql .= "left JOIN EST_PRODUTO_EQUIVALENCIA E ON (E.IDPRODUTO=P.CODIGO) ";
       
    if ($this->m_par[3] != null) {
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[3]) ? '':" $cond (P.CODIGO = '".$this->m_par[3]."')";
    }else{

        $grupos = '';
        for($i = 1; $i < count($grupo); $i++){
            $grupos .= "'".$grupo[$i]."',";
        }
        $grupos = strstr($grupos, ',', true);
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($grupos) ? '':" $cond (P.GRUPO IN (".$grupos.")) ";

    }
   	$sql .= "ORDER BY p.descricao ";
        //echo strtoupper($sql)."<br>";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}// fim select_PRODUTO_letra

/**
 * Consulta ao Banco atraves de parametros para obter as Saidas do movimento de ESTOQUE  
 * baseados nos filtros [dataIni, dataFim , centroCusto, CodProduto e grupo de Produto]
 * @name select_relatorio_estoque_geral
 * @return ARRAY com [IDPedido, CodProduto, DESCRICAO, SAIDA, TOTAL_SAIDA, DATA_SAIDA ]  
 */
public function select_relatorio_estoque_geral($order=''){
    
    $grupo = explode("|", $this->m_grupo);
	$sql  = "SELECT DISTINCT P.*, G.DESCRICAO AS NOMEGRUPO, 0 as ESTOQUE, 0 as RESERVA ";
   	$sql .= "FROM EST_PRODUTO P left JOIN EST_GRUPO G ON (G.GRUPO=P.GRUPO) ";
    $sql .= "left JOIN EST_PRODUTO_EQUIVALENCIA E ON (E.IDPRODUTO=P.CODIGO) ";
       
    if ($this->m_par[3] != null) {
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[3]) ? '':" $cond (P.CODIGO = '".$this->m_par[3]."')";
    }else{
        $countGrupo = count($grupo)-1;
        $grupos = '';
        for($i = 1; $i < count($grupo); $i++){
            $i != $countGrupo ? $grupos .= "'".$grupo[$i]."'," : $grupos .= "'".$grupo[$i]."'";
        }
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($grupos) ? '':" $cond (P.GRUPO IN (".$grupos.")) ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[9]) ? '':" $cond (P.LOCALIZACAO LIKE '%".$this->m_par[9]."%')";

    }
    if($order == 'grupo'){
   	    $sql .= "ORDER BY P.GRUPO, P.DESCRICAO  ";
    }elseif('localizacao'){
        $sql .= "ORDER BY P.LOCALIZACAO ";
    }
    else{
        $sql .= "ORDER BY P.DESCRICAO ";
    }
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}// fim select_PRODUTO_letra


public function select_geral_localizacao($order = ''){
    $grupo = explode("|", $this->m_grupo);
    $localizacao = explode("|", $this->m_localizacao);
    array_shift($localizacao);
    $sql = "SELECT DISTINCT P.*, G.DESCRICAO AS NOMEGRUPO, 0 as ESTOQUE, 0 as RESERVA ";
    $sql .= "FROM EST_PRODUTO P left JOIN EST_GRUPO G ON (G.GRUPO=P.GRUPO) ";
    $sql .= "left JOIN EST_PRODUTO_EQUIVALENCIA E ON (E.IDPRODUTO=P.CODIGO) ";

    if ($this->m_par[3] != null) {
        $cond = strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[3]) ? '' : "$cond (P.CODIGO = '" . $this->m_par[3] . "')";
    } else {
        $countGrupo = count($grupo) - 1;
        $grupos = '';
        for ($i = 1; $i < count($grupo); $i++) {
            $i != $countGrupo ? $grupos .= "'" . $grupo[$i] . "'," : $grupos .= "'" . $grupo[$i] . "'";
        }
        $cond = strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($grupos) ? '' : "$cond (P.GRUPO IN (" . $grupos . ")) ";
        
        $cond = strpos($sql, 'where') === false ? 'where' : 'and';
        $localizacoes = implode("', '", $localizacao);
        $sql .= empty($this->m_par[9]) ? '' : "$cond (P.LOCALIZACAO IN ('" . $localizacoes . "')) and P.LOCALIZACAO <> '' ";
    }

    if ($order == 'grupo') {
        $sql .= "ORDER BY P.GRUPO, P.DESCRICAO  ";
    } elseif ($order == 'localizacao') {
        $sql .= "ORDER BY P.LOCALIZACAO ";
    } else {
        $sql .= "ORDER BY P.DESCRICAO ";
    }

    //echo strtoupper($sql);
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}// fim select_PRODUTO_letra

/**
 * Consulta ao Banco atraves de parametros para obter as Saidas do movimento de ESTOQUE  
 * baseados nos filtros [dataIni, dataFim , centroCusto, CodProduto e grupo de Produto]
 * @name select_relatorio_estoque_geral
 * @return ARRAY com [IDPedido, CodProduto, DESCRICAO, SAIDA, TOTAL_SAIDA, DATA_SAIDA ]  
 *     
 *   $par[0] = DataInicio
 *   $par[1] = DataFim
 *   $par[2] = Data Referencia
 *   $par[3] = Cod Produto
 *   $par[4] = Id Pessoa
 *   $par[5] = Num Nota Fiscal
 *   $par[6] = centrocusto
 */
public function select_consulta_produto_preco($letra){

    $par = explode("|", $letra);

    $dataIni = $par[0] =='' ? '' : c_date::convertDateTxt($par[0]);
    $dataFim = $par[1] =='' ? '' : c_date::convertDateTxt($par[1]);

    $sqlS  = "SELECT NFP.CODIGONOTA, NF.NUMERO, 'NF SAIDA' AS TIPO, NF.ORIGEM, NF.DOC, NF.EMISSAO, C.NOME, PI.QTSOLICITADA, ";
    $sqlS  .= "PI.UNITARIO AS UNITARIOPEDIDO, PI.PERCDESCONTO,  ";
    $sqlS  .= "(PI.UNITARIO - ((PI.UNITARIO * PI.PERCDESCONTO)/100) + NFP.VALORICMSST) AS UNITARIOLIQUIDO, NFP.VALORICMSST AS ST,  ";
    $sqlS  .= "(PI.TOTAL - ((PI.TOTAL * PI.PERCDESCONTO)/100)) AS TOTALITEM ";
    $sqlS  .= "FROM EST_NOTA_FISCAL NF  ";
    $sqlS  .= "LEFT JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NF.ID = NFP.IDNF)  ";
    $sqlS  .= "LEFT JOIN EST_PRODUTO PRO  ON (PRO.CODIGO = NFP.CODPRODUTO)  ";
    $sqlS  .= "LEFT JOIN FIN_CLIENTE C ON (NF.PESSOA = C.CLIENTE)  ";
    $sqlS  .= "LEFT JOIN FAT_PEDIDO PED ON (PED.ID = NF.DOC AND NF.ORIGEM='PED')  ";
    $sqlS  .= "LEFT JOIN FAT_PEDIDO_ITEM PI ON (PED.ID = PI.ID AND PI.ITEMESTOQUE=PRO.CODIGO)  ";
    $sqlS  .= "where NF.ORIGEM<>'AJT' AND NF.TIPO=1 "; 
    
    $cond =  strpos($sqlS, 'where') === false ? 'where' : 'and';
    $sqlS .= empty($par[3]) ? '':" $cond (PRO.CODIGO = '".$par[3]."') ";

    $cond = strpos($sqlS, 'where') === false ? 'where' : 'and';
    $sqlS .= empty($par[6]) ? '':" $cond (NF.CENTROCUSTO = '".$par[6]."') ";

    
    $sqlE  = "SELECT NFP.CODIGONOTA, NF.NUMERO, 'NF ENTRADA' AS TIPO, NF.ORIGEM, NF.DOC, NF.EMISSAO, C.NOME, CI.QTSOLICITADA, ";
    $sqlE  .= "CI.UNITARIO AS UNITARIOPEDIDO, CI.PERCDESCONTO,  ";
    $sqlE  .= "(CI.UNITARIO - ((CI.UNITARIO * CI.PERCDESCONTO)/100)) AS UNITARIOLIQUIDO, NFP.VALORICMSST AS ST,  ";
    $sqlE  .= "(CI.TOTAL - ((CI.TOTAL * CI.PERCDESCONTO)/100) + NFP.VALORICMSST) AS TOTALITEM ";
    $sqlE  .= "FROM EST_NOTA_FISCAL NF  ";
    $sqlE  .= "LEFT JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NF.ID = NFP.IDNF)  ";
    $sqlE  .= "LEFT JOIN EST_PRODUTO PRO  ON (PRO.CODIGO = NFP.CODPRODUTO)  ";
    $sqlE  .= "LEFT JOIN FIN_CLIENTE C ON (NF.PESSOA = C.CLIENTE)  ";
    $sqlE  .= "LEFT JOIN EST_ORDEM_COMPRA COM ON (COM.ID = NF.DOC AND NF.ORIGEM='COC')  ";
    $sqlE  .= "LEFT JOIN EST_ORDEM_COMPRA_ITEM CI ON (COM.ID = CI.ID AND CI.ITEMESTOQUE=PRO.CODIGO)  ";
    $sqlE  .= "where NF.ORIGEM<>'AJT' AND NF.TIPO=0 "; 
    
    $cond =  strpos($sqlE, 'where') === false ? 'where' : 'and';
    $sqlE .= empty($par[3]) ? '':" $cond (PRO.CODIGO = '".$par[3]."') ";

    $cond = strpos($sqlE, 'where') === false ? 'where' : 'and';
    $sqlE .= empty($par[6]) ? '':" $cond (NF.CENTROCUSTO = '".$par[6]."') ";


    $sqlPed = "SELECT PI.CODIGONOTA, PED.ID, 'PEDIDO' AS TIPO, '' AS ORIGEM, '' AS DOC, PED.EMISSAO, C.NOME, PI.QTSOLICITADA, "; 
    $sqlPed .= "PI.UNITARIO AS UNITARIOPEDIDO, PI.PERCDESCONTO,  "; 
    $sqlPed .= "(PI.UNITARIO - ((PI.UNITARIO * PI.PERCDESCONTO)/100)) AS UNITARIOLIQUIDO, PI.VALORICMSST AS ST,  "; 
    $sqlPed .= "(PI.TOTAL - ((PI.TOTAL * PI.PERCDESCONTO)/100)) AS TOTALITEM "; 
    $sqlPed .= "FROM FAT_PEDIDO PED  "; 
    $sqlPed .= "LEFT JOIN FAT_PEDIDO_ITEM PI ON (PED.ID = PI.ID)  "; 
    $sqlPed .= "LEFT JOIN EST_PRODUTO PRO  ON (PRO.CODIGO = PI.ITEMESTOQUE)  "; 
    $sqlPed .= "LEFT JOIN FIN_CLIENTE C ON (PED.CLIENTE = C.CLIENTE)  "; 
    $sqlPed .= "where PED.SITUACAO = 6  "; 

    $cond =  strpos($sqlPed, 'where') === false ? 'where' : 'and';
    $sqlPed .= empty($par[3]) ? '':" $cond (PRO.CODIGO = '".$par[3]."') ";

    $cond = strpos($sqlPed, 'where') === false ? 'where' : 'and';
    $sqlPed .= empty($par[6]) ? '':" $cond (PED.CCUSTO = '".$par[6]."') ";

    $sql = "$sqlS UNION $sqlE UNION $sqlPed ORDER BY 6 DESC";
    // echo strtoupper($sql);
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}// fim select_consulta_produto_preco


/**
 * Consulta ao Banco atraves de parametros para obter as Saidas do movimento de ESTOQUE  
 * baseados nos filtros [dataIni, dataFim , centroCusto, CodProduto e grupo de Produto]
 * @name select_relatorio_estoque_geral
 * @return ARRAY com [IDPedido, CodProduto, DESCRICAO, SAIDA, TOTAL_SAIDA, DATA_SAIDA ]  
 *     
 *   $par[0] = DataInicio
 *   $par[1] = DataFim
 *   $par[2] = Data Referencia
 *   $par[3] = Cod Produto
 *   $par[4] = Id Pessoa
 *   $par[5] = Num Nota Fiscal
 *   $par[6] = centrocusto
 */
public function select_relatorio_mov_estoque($letra, $order=''){

    $par = explode("|", $letra);

    $dataIni = $par[0] =='' ? '' : c_date::convertDateTxt($par[0]);
    $dataFim = $par[1] =='' ? '' : c_date::convertDateTxt($par[1]);

    $sql  = "SELECT NF.EMISSAO, NF.DOC, 'NF' as ORIGEM, C.NOME, U.NOME AS NOMEUSUARIO, NF.OBS, PRO.CODIGO, PRO.CODFABRICANTE, NF.ID, ";
    $sql  .= "NF.NUMERO, IF(NF.TIPO = 0, 'ENTRADA', 'SAIDA') AS TIPO, PRO.DESCRICAO, PRO.UNIDADE, NFP.QUANT AS QTDE, NFP.UNITARIO, "; 
    $sql  .= "NFP.TOTAL, NF.TOTALNF AS TOTALNOTA, PI.PERCDESCONTO, NFP.DESCONTO, (NFP.TOTAL - NFP.DESCONTO) AS TOTALITEMDESCONTO, ";
    $sql  .= "(PI.UNITARIO - ((PI.UNITARIO * PI.PERCDESCONTO)/100)) AS UNITARIOLIQUIDO, NFP.VALORICMSST AS ST,  ";
    $sql  .= "NFP.CODIGONOTA, PI.UNITARIO AS UNITARIOPEDIDO FROM EST_PRODUTO PRO  ";
    $sql  .= "LEFT JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (PRO.CODIGO = NFP.CODPRODUTO) ";
    $sql  .= "LEFT JOIN EST_NOTA_FISCAL NF ON (NF.ID = NFP.IDNF) ";
    $sql  .= "LEFT JOIN FIN_CLIENTE C ON (NF.PESSOA = C.CLIENTE)  ";
    $sql  .= "LEFT JOIN AMB_USUARIO U ON (NF.USERINSERT = U.USUARIO) "; 
    $sql  .= "LEFT JOIN FAT_PEDIDO PED ON (PED.ID = NF.DOC AND NF.ORIGEM='PED')  ";
    $sql  .= "LEFT JOIN FAT_PEDIDO_ITEM PI ON (PED.ID = PI.ID  AND PI.ITEMESTOQUE=PRO.CODIGO) ";

    
    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .=  empty($dataIni) ? '':" $cond (NF.EMISSAO BETWEEN '".$dataIni." 00:00:00' AND '".$dataFim." 23:59:59') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($par[3]) ? '':" $cond (PRO.CODIGO = '".$par[3]."') ";

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($par[4]) ? '':" $cond (NF.PESSOA = '".$par[4]."') ";

    $cond = strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .= empty($par[6]) ? '':" $cond (NF.CENTROCUSTO = '".$par[6]."') ";

    $cond = strpos($sql, 'where') === false ? 'where' : 'and';

    $sql .= " UNION ";
 
    $sqlPed = "SELECT PED.EMISSAO, NF.ID AS DOC, 'PED' AS ORIGEM, C.NOME, U.NOME AS NOMEUSUARIO, PED.OBS, PRO.CODIGO, PRO.CODFABRICANTE, PED.ID, ";
    $sqlPed .= "PED.ID AS NUMERO, 'SAIDA' AS TIPO, PRO.DESCRICAO, PRO.UNIDADE, PI.QTSOLICITADA AS QTDE, PI.UNITARIO,  ";
    $sqlPed .= "(PI.TOTAL - PI.DESCONTO) AS TOTAL, PED.TOTAL AS TOTALNOTA , PI.PERCDESCONTO, PI.DESCONTO, (PI.TOTAL - PI.DESCONTO) AS TOTALITEMDESCONTO, ";
    $sqlPed .= "(PI.UNITARIO - ((PI.UNITARIO * PI.PERCDESCONTO)/100)) AS UNITARIOLIQUIDO, 0 AS ST, PI.CODIGONOTA, PI.UNITARIO AS UNITARIOPEDIDO ";
    $sqlPed .= "FROM EST_PRODUTO PRO ";
    $sqlPed .= "LEFT JOIN FAT_PEDIDO_ITEM PI ON (PRO.CODIGO = PI.ITEMESTOQUE) ";
    $sqlPed .= "LEFT JOIN FAT_PEDIDO PED ON (PED.ID = PI.ID) ";
    $sqlPed .= "LEFT JOIN EST_NOTA_FISCAL NF ON (NF.ORIGEM='PED') AND (NF.DOC = PED.ID) ";
    $sqlPed .= "LEFT JOIN FIN_CLIENTE C ON (PED.CLIENTE = C.CLIENTE)  ";
    $sqlPed .= "LEFT JOIN AMB_USUARIO U ON (PED.USERINSERT = U.USUARIO) "; 

    $sqlPed .= " where isnull(NF.DOC) and PED.SITUACAO in (6,9) "; // situacao 6 - pedido / 9 -baixado

    $cond =  strpos($sqlPed, 'where') === false ? 'where' : 'and';
    $sqlPed .=  empty($dataIni) ? '':" $cond (PED.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."') ";

    $cond =  strpos($sqlPed, 'where') === false ? 'where' : 'and';
    $sqlPed .= empty($par[3]) ? '':" $cond (PRO.CODIGO = '".$par[3]."') ";

    $cond =  strpos($sqlPed, 'where') === false ? 'where' : 'and';
    $sqlPed .= empty($par[4]) ? '':" $cond (PED.CLIENTE = '".$par[4]."') ";

    $cond = strpos($sqlPed, 'where') === false ? 'where' : 'and';
    $sqlPed .= empty($par[6]) ? '':" $cond (PED.CCUSTO = '".$par[6]."') ";

$sql .= $sqlPed;
$sql  .= " ORDER BY 1 ".$order;
// echo strtoupper($sql);
$banco = new c_banco;
$banco->exec_sql($sql);
$banco->close_connection();
return $banco->resultado;
}// fim select_PRODUTO_letra

/**
 * Consulta ao Banco atraves de parametros para obter as Saidas do movimento de ESTOQUE  
 * baseados nos filtros [dataIni, dataFim , centroCusto, CodProduto e grupo de Produto]
 * @name select_relatorio_estoque_geral
 * @return ARRAY com [IDPedido, CodProduto, DESCRICAO, SAIDA, TOTAL_SAIDA, DATA_SAIDA ]  
 */
public function select_relatorio_mov_estoque_cliente(){

        $dataIni = c_date::convertDateTxt($this->m_par[0]);
        $dataFim = c_date::convertDateTxt($this->m_par[1]);
    
        $sql  = "SELECT C.NOME, PRO.CODIGO AS COD, NF.ID, IF(NF.TIPO = 0, 'ENTRADA', 'SAIDA') AS TIPO, PRO.DESCRICAO, PRO.UNIDADE, NF.EMISSAO, NFP.QUANT AS QTDE, NFP.TOTAL  FROM EST_PRODUTO PRO
                LEFT JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (PRO.CODIGO = NFP.CODPRODUTO)
                LEFT JOIN EST_NOTA_FISCAL NF ON (NF.ID = NFP.ID)
                LEFT JOIN FIN_CLIENTE C ON (NF.PESSOA = C.CLIENTE) ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .=  empty($dataIni) ? '':" $cond (NF.DATASAIDAENTRADA BETWEEN '".$dataIni." 00:00:00' AND '".$dataFim." 23:59:59') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[3]) ? '':" $cond (PRO.CODIGO = '".$this->m_par[3]."') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[4]) ? '':" $cond (NF.PESSOA = '".$this->m_par[4]."') ";

        $cond = strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[6]) ? '':" $cond (NF.CENTROCUSTO = '".$this->m_par[6]."') ";

    $sql .= " UNION ";
     
        $sqlPed  = "SELECT C.NOME, PRO.CODIGO, PED.ID,'SAIDA' as TIPO, PRO.DESCRICAO, PRO.UNIDADE, PED.EMISSAO, PI.QTSOLICITADA AS QTDE, PI.TOTAL  FROM EST_PRODUTO PRO
                LEFT JOIN FAT_PEDIDO_ITEM PI ON (PRO.CODIGO = PI.ITEMESTOQUE)
                LEFT JOIN FAT_PEDIDO PED ON (PED.ID = PI.ID)
                LEFT JOIN FIN_CLIENTE C ON (PED.CLIENTE = C.CLIENTE) "; 

        $cond =  strpos($sqlPed, 'where') === false ? 'where' : 'and';
        $sqlPed .=  empty($dataIni) ? '':" $cond (PED.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."') ";

        $cond =  strpos($sqlPed, 'where') === false ? 'where' : 'and';
        $sqlPed .= empty($this->m_par[3]) ? '':" $cond (PRO.CODIGO = '".$this->m_par[3]."') ";

        $cond =  strpos($sqlPed, 'where') === false ? 'where' : 'and';
        $sqlPed .= empty($this->m_par[4]) ? '':" $cond (PED.CLIENTE = '".$this->m_par[4]."') ";

        $cond = strpos($sqlPed, 'where') === false ? 'where' : 'and';
        $sqlPed .= empty($this->m_par[6]) ? '':" $cond (PED.CCUSTO = '".$this->m_par[6]."') ";

    $sql .= $sqlPed;
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}// fim select_PRODUTO_letra

/**
 * Consulta ao Banco atraves de parametros para obter as Saidas do movimento de ESTOQUE  
 * baseados nos filtros [dataIni, dataFim , centroCusto, CodProduto e grupo de Produto]
 * @name movimento_estoque_saida
 * @return ARRAY com [IDPedido, CodProduto, DESCRICAO, SAIDA, TOTAL_SAIDA, DATA_SAIDA ]  
 */
public function select_relatorio_compras_sugestoes(){
    
    $dataIni = c_date::convertDateTxt($this->m_par[0]);
    $dataFim = c_date::convertDateTxt($this->m_par[1]);
    $grupo   = explode("|", $this->m_grupo);
    $sql  = "SELECT I.ITEMESTOQUE, P.DESCRICAO, G.DESCRICAO AS NOMEGRUPO, P.CODFABRICANTE, P.QUANTMINIMA, P.QUANTMAXIMA,  P.VENDA, 0 as ESTOQUE, 0 as RESERVA, ";
    $sql .= "SUM(I.QTSOLICITADA) AS QUANT, ";
    $sql .= "SUM(I.TOTAL) AS VALOR, ";
    $sql .= "COUNT(I.ID) AS NUMVENDAS ";
   	$sql .= "FROM FAT_PEDIDO_ITEM I ";
    $sql .= "JOIN EST_PRODUTO P ON P.CODIGO = I.ITEMESTOQUE ";
    $sql .= "JOIN EST_GRUPO G ON P.GRUPO = G.GRUPO ";
    $sql .= "JOIN FAT_PEDIDO PED ON (PED.ID = I.ID) ";
       
    if ($this->m_par[3] != null) {
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[3]) ? '':" $cond (P.CODIGO = '".$this->m_par[3]."')";
    }else{

        $grupos = '';
        for($i = 1; $i < count($grupo); $i++){
            $grupos .= "'".$grupo[$i]."',";
        }
        $grupos = strstr($grupos, ',', true);
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($grupos) ? '':" $cond (P.GRUPO IN (".$grupos.")) ";

    }

    $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
    $sql .=  empty($dataIni) ? '':" $cond (PED.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."') ";
   	$sql .= "GROUP BY I.ITEMESTOQUE 
             ORDER BY NUMVENDAS DESC ";
        //echo strtoupper($sql)."<br>";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}// fim select_PRODUTO_letra

}	//	END OF THE CLASS
?>
