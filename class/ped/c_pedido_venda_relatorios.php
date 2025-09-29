<?php

/**
 * @package   astec
 * @name      c_pedido_venda_relatorios
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_produto.php");

//Class c_pedido_venda_relatorios
class c_pedido_venda_relatorios extends c_user
{
    /**
     * TABLE NAME FAT_PEDIDO
     */

    // Campos tabela
    private $id         = NULL; // VARCHAR(15)

    /**
     * METODOS DE SETS E GETS
     */
    public function setId($grupo)
    {
        $this->id = c_tools::LimpaCamposGeral($grupo);
    }
    public function getId()
    {
        return $this->id;
    }

    //############### FIM SETS E GETS ###############

    /**
     * Funcao de consulta para todos os registros da tabela
     * @name select_pedidos_geral
     * @return ARRAY de todas as colunas da table
     */
    public function select_pedidos_geral(object $params)
    {
        //   $this->m_par[0] - E O ID DO PEDIDO 
        $params->tupula = 'EMISSAO';

        $sql  = "SELECT DISTINCT P.*, C.NOME AS NOMECLIENTE, U.NOMEREDUZIDO AS NOMEVENDEDOR, ";
        $sql .= "CC.DESCRICAO AS CCUSTO, D.PADRAO AS SIT, M.DESCRICAO AS MOTIVO, CC.DESCRICAO as CENTROCUSTO, ";
        $sql .= "COALESCE(CC1.DESCRICAO,CC.DESCRICAO) as CENTROCUSTOENTREGA, DE.PADRAO AS DESCTIPOENTREGA ";

        if ($this->m_tipo_relatorio == 'relatorioSemana') {
            $sql .= ", CASE WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Sunday' THEN 'Domingo' ";
            $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Monday' THEN 'Segunda-Feira' ";
            $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Tuesday' THEN 'Terca-Feira' ";
            $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Wednesday' THEN 'Quarta-Feira' ";
            $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Thursday' THEN 'Quinta-Feira' ";
            $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Friday' THEN 'Sexta-Feira' ";
            $sql .= " WHEN DATE_FORMAT(P.EMISSAO, '%w' ) = 'Saturday' THEN 'Sabado' END AS DIASEMANA ";
        }

        // Relatorio de condicao de pagamento inclui a coluna para consulta
        $this->m_tipo_relatorio == 'relatorioCondPagamento' ? $sql .= ', CP.DESCRICAO AS CONDPAGAMENTO ' : '';

        $sql .= "FROM FAT_PEDIDO P ";
        $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID = P.ID) ";
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = P.USRFATURA) ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (P.CLIENTE = C.CLIENTE) ";
        $sql .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = P.CCUSTO) ";
        $sql .= "LEFT JOIN FIN_CENTRO_CUSTO CC1 ON (CC1.CENTROCUSTO = P.CENTROCUSTOENTREGA) ";
        $sql .= "LEFT JOIN FAT_MOTIVO M ON (M.MOTIVO = I.MOTIVO) ";
        $sql .= "LEFT JOIN FAT_COND_PGTO CP ON (P.CONDPG = CP.ID ) ";
        $sql .= "LEFT JOIN AMB_DDM D ON ((ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND (D.TIPO = P.SITUACAO)) ";
        $sql .= "LEFT JOIN AMB_DDM DE ON ((DE.ALIAS='FAT_MENU') AND (DE.CAMPO='TIPOENTREGA') AND (DE.TIPO = P.TIPOENTREGA)) ";
        $sql .= "LEFT JOIN FIN_LANCAMENTO L ON (P.ID = L.DOCTO) ";

        // Relatorio tipo entregas, inclui a tupula ENTREGA para inserir no select
        $this->m_tipo_relatorio == 'relatorioItemEntrega' ? $params->tupula  = 'ENTREGA' : '';
        $this->m_tipo_relatorio == 'relatorioEntrega' ? $params->tupula  = 'ENTREGA' : '';
        // if ternario, se existir o id do pedido inclui o where ID, se nao ira para os filtros
        $this->m_par[0] != '' ? $sql .= "WHERE (P.ID = '" . $this->m_par[0] . "')" :
            $sql = $this->where_filtros($params, $sql);

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_grupo_geral

    /**
     * Consulta o Banco atraves do pedido id para obter os dados de pedido item
     * @name select_pedidos_item_geral
     * @param INT idPedido
     * @return ARRAY todos os campos da table de FAT_PEDIDO_ITEM
     * @version 20200902
     */
    public function select_pedidos_item_geral($idPedido, $m_item_estoque)
    {
        $sql = "SELECT * FROM FAT_PEDIDO_ITEM WHERE ID = " . $idPedido ;
        if ($m_item_estoque !== '') {
            $sql .= " AND ITEMESTOQUE = '" . $m_item_estoque . "'";
        }
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * Consulta o Banco atraves do pedido id para obter os dados de pedido, fazendo junção com
     * as tabelas de [FIN_CLIENTE, FIN_LANCAMENTO e AMB_DDM ] 
     * @name select_fatura_pedido_venda
     * @param INT id_pedido
     * @return ARRAY todos os campos da tabela de pedido , nomeCliente, dataVenciemnto, TipoDocto, 
     * modoPgto, total_lanc e situacaoPgto
     * @version 20200903
     */
    public function select_fatura_pedidos_venda($id_pedido, $situacao)
    {
        $sql = "SELECT P.*, C.NOME AS NOMECLIENTE, L.VENCIMENTO AS VENC, L.ORIGINAL, TD.PADRAO AS TPDOCTO, MP.PADRAO AS MODOPAG , L.TOTAL AS TOTAL_FAT, SP.PADRAO AS SITUACAOPAG ";
        $sql .= "FROM FAT_PEDIDO P ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (P.CLIENTE = C.CLIENTE) ";
        $sql .= "LEFT JOIN FIN_LANCAMENTO L ON (P.ID = L.DOCTO) ";
        $sql .= "LEFT JOIN AMB_DDM TD ON ((L.TIPODOCTO = TD.TIPO) AND TD.ALIAS='FIN_MENU' AND TD.CAMPO='TipoDoctoPgto') ";
        $sql .= "LEFT JOIN AMB_DDM MP ON ((L.MODOPGTO = MP.TIPO) AND MP.ALIAS='FIN_MENU' AND MP.CAMPO='ModoPgto') ";
        $sql .= "LEFT JOIN AMB_DDM SP ON ((L.SITPGTO = SP.TIPO) AND SP.ALIAS='FIN_MENU' AND SP.CAMPO='SituacaoPgto') ";
        $sql .= "WHERE P.ID = " . $id_pedido;
        if ($situacao !== null) {
            $sql .= " AND L.SITPGTO = '" . $situacao . "'";
        }
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    /**
     * Função filtro para as consultas ao Banco de dados []
     * @name where_filtros
     * @param STRING sql
     * @param STRING tipoRelatorio
     * @param DATE data
     * @return STRING sql concatenado com os filtros selecionados  
     * @version 20201020
     */
    public function where_filtros(object $params = null, $sql)
    {
        $condPagamento = explode(",", $params->condicao_pagamento);
        $centroCusto   = explode(",", $params->centro_custo);
        $situacao      = explode(",", $params->situacao);
        $vendedor      = explode(",", $params->vendedor);
        $motivo        = explode(",", $params->motivo);
        $dataIni       = c_date::convertDateTxt($params->data_ini);
        $dataFim       = c_date::convertDateTxt($params->data_fim);
        $entrega       = null;
        $this->m_tipo_entrega =  $this->m_tipo_entrega;

        if ($params->tupula == 'ENTREGA') {
            $params->tupula = 'PRAZOENTREGA';
            $entrega = 'ENTREGA';
        }

        if ($entrega == 'ENTREGA') {
            $sqlData = "(cast(concat(SUBSTRING(P.PRAZOENTREGA, 7, 4),'-',SUBSTRING(P.PRAZOENTREGA, 4, 2),'-',SUBSTRING(P.PRAZOENTREGA, 1, 2)) as date) ";
            $sqlData .= " BETWEEN '" . $dataIni . "' AND '" . $dataFim . "' )";
        } else {
            $sqlData = " ( P." . $params->tupula . " BETWEEN '" . $dataIni . "' AND '" . $dataFim . "' )";
        }

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= "$cond $sqlData ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($params->cliente_id) ? '' : " $cond (P.CLIENTE = '" . $params->cliente_id . "') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($this->m_par[3]) ? '' : " $cond (I.ITEMESTOQUE = '" . $this->m_par[3] . "') ";

        if (!empty($centroCusto)) {

            $cCusto = "";

            for ($i = 0; $i < count($centroCusto); $i++) {
                if ($centroCusto[$i] != "") {
                    $cCusto == "" ? $cCusto .= "'" . $centroCusto[$i] . "'" : $cCusto .= ",'" . $centroCusto[$i] . "'";
                }
            }

            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            //$sql .= empty($cCusto) ? '':" $cond (P.CCUSTO IN (".$cCusto.")) ";  

            if ($entrega == 'ENTREGA') {
                $sql .= empty($cCusto) ? '' : " $cond (P.CENTROCUSTOENTREGA IN (" . $cCusto . ")) ";
            } else {
                $sql .= empty($cCusto) ? '' : " $cond (P.CCUSTO IN (" . $cCusto . ")) ";
            }
        }

        if ($params->tipo_relatorio != 'Motivo') {
            if (!empty($situacao)) {
                $sit = "";
                for ($i = 0; $i < count($situacao); $i++) {
                    if ($situacao[$i] != "") {
                        $sit == "" ? $sit .= "'" . $situacao[$i] . "'" :  $sit .= ",'" . $situacao[$i] . "'";
                    }
                }
                $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
                $sql .= empty($sit) ? '' : " $cond (P.SITUACAO IN (" . $sit . ")) ";
            }
        }

        if (!empty($vendedor)) {
            $vend = "";
            for ($i = 0; $i < count($vendedor); $i++) {
                if ($vendedor[$i] != "") {
                    $vend == "" ? $vend .= "'" . $vendedor[$i] . "'" : $vend .= ",'" . $vendedor[$i] . "'";
                }
            }
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($vend) ? '' : " $cond (P.USRFATURA IN (" . $vend . ")) ";
        }

        if (!empty($condPagamento)) {
            $condPag = "";
            for ($i = 0; $i < count($condPagamento); $i++) {
                if ($condPagamento[$i] != "") {
                    $condPag == "" ? $condPag .= "'" . $condPagamento[$i] . "'" : $condPag .= ",'" . $condPagamento[$i] . "'";
                }
            }
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($condPag) ? '' : " $cond (P.CONDPG IN (" . $condPag . ")) ";
        }

        if (!empty($motivo)) {
            $mot = "";
            for ($i = 0; $i < count($motivo); $i++) {
                if ($motivo[$i] != "") {
                    $mot == "" ? $mot .= "'" . $motivo[$i] . "'" : $mot .= ",'" . $motivo[$i] . "'";
                }
            }
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($mot) ? '' : " $cond (I.MOTIVO IN (" . $mot . ")) ";
            $params->tipo_relatorio == 'Motivo' ? $sql .= "AND (I.MOTIVO <> '0') " : '';
        }
        if (!empty($params->codProduto)) {
            $sql .= " AND (I.ITEMESTOQUE = '" . $params->codProduto . "') ";
        }
        if (!empty($this->rel_situacao)) {
            $sql .= " AND L.SITPGTO = '" . $this->rel_situacao . "'";
        }
        if ($this->m_tipo_entrega != '') {
            $sql .= "AND (P.TIPOENTREGA = '" . $this->m_tipo_entrega . "') ";
        }

        if ($entrega == 'ENTREGA') {
            $sql .= "ORDER BY P.PRAZOENTREGA ";
        } else if ($params->tipo_relatorio == 'relatorioCondPagamento') {
            $sql .= "ORDER BY P.CONDPG ";
        } else if ($params->tipo_relatorio == 'relatorioVendedor') {
            $sql .= "ORDER BY P.USRFATURA ";
        } else {
            $sql .= "ORDER BY P.EMISSAO ";
        }
        return $sql;
    }
    /**
     * Consulta para o Banco atraves do pedido id
     * @name select_faturas_sintetico
     * @return ARRAY todos os campos da table
     * @version 20200902
     */
    public function select_faturas_sintetico()
    {
        $dataIni = c_date::convertDateTxt($this->m_data_ini);
        $dataFim = c_date::convertDateTxt($this->m_data_fim);
        $centroCusto    = explode("|", $this->m_centro_custo);

        $sql = "SELECT ANY_VALUE(TD.PADRAO) AS TPDOCTO,  SUM(L.TOTAL) as TOTALDOC, ANY_VALUE(P.EMISSAO) FROM FIN_LANCAMENTO L ";
        $sql .= "JOIN FAT_PEDIDO P ON (L.DOCTO = P.ID) ";
        $sql .= "JOIN AMB_DDM TD ON ((L.TIPODOCTO = TD.TIPO) AND TD.ALIAS='FIN_MENU' AND TD.CAMPO='TipoDoctoPgto') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($dataIni) &&  empty($dataFim) ? '' : " $cond  ( P.EMISSAO BETWEEN '" . $dataIni . "' AND '" . $dataFim . "' ) ";

        if (!empty($centroCusto)) {
            $cCusto = "";
            for ($i = 0; $i < count($centroCusto); $i++) {
                if ($centroCusto[$i] != "") {
                    $cCusto == "" ? $cCusto .= "'" . $centroCusto[$i] . "'" : $cCusto .= ",'" . $centroCusto[$i] . "'";
                }
            }
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($cCusto) ? '' : " $cond (L.CENTROCUSTO IN (" . $cCusto . ")) AND L.ORIGEM = 'PED' AND L.TIPOLANCAMENTO = 'R'  ";
        }

        $sql .= "GROUP BY L.TIPODOCTO";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_faturas_analitico()
    {
        $dataIni = c_date::convertDateTxt($this->m_data_ini);
        $dataFim = c_date::convertDateTxt($this->m_data_fim);
        $centroCusto    = explode("|", $this->m_centro_custo);

        $sql = "SELECT L.*, C.NOME, CC.DESCRICAO AS CCUSTO, G.DESCRICAO AS GENERO, TD.PADRAO AS TPDOCTO, P.EMISSAO, L.TOTAL AS TOTAL_LANC ";
        $sql .= "FROM FIN_LANCAMENTO L ";
        $sql .= "LEFT JOIN FAT_PEDIDO P ON (L.DOCTO = P.ID) ";
        $sql .= "LEFT JOIN FIN_CLIENTE C ON (L.PESSOA = C.CLIENTE) ";
        $sql .= "LEFT JOIN FIN_CENTRO_CUSTO CC ON (L.CENTROCUSTO = CC.CENTROCUSTO) ";
        $sql .= "LEFT JOIN FIN_GENERO G ON (L.GENERO = G.GENERO) ";
        $sql .= "LEFT JOIN AMB_DDM TD ON ((L.TIPODOCTO = TD.TIPO) AND TD.ALIAS='FIN_MENU' AND TD.CAMPO='TipoDoctoPgto') ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($dataIni) &&  empty($dataFim) ? '' : " $cond  ( P.EMISSAO BETWEEN '" . $dataIni . "' AND '" . $dataFim . "' ) AND L.ORIGEM = 'PED' AND L.TIPOLANCAMENTO = 'R' ";

        if (!empty($centroCusto)) {
            $cCusto = "";
            for ($i = 0; $i < count($centroCusto); $i++) {
                if ($centroCusto[$i] != "") {
                    $cCusto == "" ? $cCusto .= "'" . $centroCusto[$i] . "'" : $cCusto .= ",'" . $centroCusto[$i] . "'";
                }
            }
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($cCusto) ? '' : " $cond (L.CENTROCUSTO IN (" . $cCusto . ")) ";
        }

        $sql .= "ORDER BY  L.TIPODOCTO, P.EMISSAO ";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function relBonus()
    {
        $codPedido = $this->m_pedido_id;
        $cliente = $this->m_cliente_id;
        $dataIni = c_date::convertDateTxt($this->m_data_ini);
        $dataFim = c_date::convertDateTxt($this->m_data_fim);
        $vendedor = explode(",", $this->m_vendedor);
        $centroCusto = explode(",", $this->m_centro_custo);

        $sql = "SELECT R.*, C.NOME AS NCLIENTE, U.NOME AS NVENDEDOR, D.DESCRICAO AS DESCITEM, E.NOMEFANTASIA FROM FIN_CLIENTE_CREDITO R ";
        $sql .= "INNER JOIN FIN_CLIENTE C ON (C.CLIENTE=R.CLIENTE) ";
        $sql .= "INNER JOIN FAT_PEDIDO P ON (P.ID=R.PEDIDO) ";
        $sql .= "INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID=P.ID) AND (I.NRITEM=R.NRITEM) ";
        $sql .= "INNER JOIN EST_PRODUTO D ON (D.CODIGO=I.ITEMESTOQUE) ";
        $sql .= "INNER JOIN AMB_USUARIO U ON (U.USUARIO=P.USRFATURA) ";
        $sql .= "INNER JOIN AMB_EMPRESA E ON (E.CENTROCUSTO=P.CCUSTO) ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($codPedido) ? '' : " $cond (P.PEDIDO IN (" . $codPedido . ")) ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($cliente) ? '' : " $cond (P.CLIENTE IN (" . $cliente . ")) ";

        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($dataIni) &&  empty($dataFim) ? '' : " $cond  ( R.DATEINSERT BETWEEN '" . $dataIni . " 00:00:00' AND '" . $dataFim . " 23:59:59' ) ";

        if (!empty($centroCusto)) {
            $cCusto = "";
            for ($i = 0; $i < count($centroCusto); $i++) {
                if ($centroCusto[$i] != "") {
                    $cCusto == "" ? $cCusto .= "'" . $centroCusto[$i] . "'" : $cCusto .= ",'" . $centroCusto[$i] . "'";
                }
            }
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($cCusto) ? '' : " $cond (P.CCUSTO IN (" . $cCusto . ")) ";
        }

        if (!empty($vendedor)) {
            $vVend = "";
            for ($i = 0; $i < count($vendedor); $i++) {
                if ($vendedor[$i] != "") {
                    $vVend == "" ? $vVend .= "'" . $vendedor[$i] . "'" : $vVend .= ",'" . $vendedor[$i] . "'";
                }
            }
            $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
            $sql .= empty($vVend) ? '' : " $cond (U.USUARIO IN (" . $vVend . ")) ";
        }

        $sql .= "ORDER BY U.USUARIO, P.CCUSTO, R.DATEINSERT";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Consulta o Banco atraves do pedido id para obter os dados de pedido nao entregue
     * @name select_pedidos_item_geral
     * @param INT data Inicio e data Fim
     * @return ARRAY todos os campos da table de FAT_PEDIDO
     * @version 20220725
     */
    public function select_pedidos_nao_entregue()
    {
        $dataIni = c_date::convertDateTxt($this->m_data_ini);
        $dataFim = c_date::convertDateTxt($this->m_data_fim);
        $sql = "SELECT * FROM FAT_PEDIDO P ";
        $sql .= " INNER JOIN FAT_PEDIDO_ITEM I ON I.ID = P.ID ";
        $sql .= "WHERE ";
        $sql .= "(EMISSAO BETWEEN '" . $dataIni . "' AND '" . $dataFim . "') AND ";
        $sql .= "(DATAENTREGA IS NULL) AND ";
        $sql .= "(SITUACAO = 6 OR SITUACAO = 9) ";
        if (!empty($this->m_item_estoque)) {
            $sql .= " AND (I.ITEMESTOQUE = '" . $this->m_item_estoque . "') ";
        }
        if (!empty($this->m_centro_custo)) {
            $sql .= " AND (P.CENTROCUSTOENTREGA = '" . $this->m_centro_custo . "') ";
        }
        if ($this->m_tipo_entrega != '') {
            $sql .= "AND (P.TIPOENTREGA = '" . $this->m_tipo_entrega . "') ";
        }
        $sql .= "ORDER BY EMISSAO;";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
}    //	END OF THE CLASS
