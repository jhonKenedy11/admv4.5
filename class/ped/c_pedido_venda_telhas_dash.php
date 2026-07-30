<?php

if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/c_pedido_venda.php");

/**
 * Classe específica para o dashboard de telhas.
 * Replica as funções da c_pedido_venda original, mas sem os filtros de GENERO <> '1.40'
 * para manter o comportamento legado apenas neste fluxo.
 */
class c_pedidoVendaTelhasDash extends c_pedidoVenda
{
    public function projecao($dataIni, $dataFim, $qtdDiasUteis, $diasPassados, $wherec)
    {
        $sql1  = "Select u.nome as VENDEDOR, ";
        $sql1 .= "( Sum(p.total) / " . $diasPassados . " ) *  " . $qtdDiasUteis . "  ";
        $sql1 .= " as PROJECAOVENDAS, ";

        $sql2  = "(( Count(*) / " . $diasPassados . " ) * " . $qtdDiasUteis . ")  ";
        $sql2 .= " as NUMERODEVENDAS, ";

        $sql3  = "( Sum(p.lucrobruto) / " . $diasPassados . " ) * " . $qtdDiasUteis . "  ";
        $sql3 .= " as PROJECAOLUCROBRUTO, ";

        $sql4  = "( Sum(p.margemliquida) / " . $diasPassados . " ) * " . $qtdDiasUteis . " ";
        $sql4 .= " as PROJECAOLUCROLIQUIDO ";

        $sql5  = "FROM fat_pedido p ";
        $sql5 .= "LEFT JOIN amb_usuario u on (u.usuario=p.usrfatura) ";
        $sql5 .= "where " . $wherec . " and (p.emissao >= '" . $dataIni . "') AND (p.emissao <= '" . $dataFim . "') ";
        $sql5 .= " and ((p.situacao = 9 ) or (p.situacao = 6) or (p.situacao = 13)) ";
        $sql5 .= "group by u.nome order by u.nome asc";

        $banco = new c_banco;
        $sql   = $sql1 . $sql2 . $sql3 . $sql4 . $sql5;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado;
    }

    public function forecast($dataIni, $dataFim, $qtdDiasUteis, $diasPassados, $where, $mes, $whereM, $ano)
    {
        $sql1  = "Select ";
        $sql1 .= "(((SELECT Sum(meta) from fat_meta_mensal ";
        $sql1 .= "where " . $whereM . " and (mes in ('" . $mes . "')) and (ano in ('" . $ano . "')) ) ";
        $sql1 .= " - Sum(p.TOTAL)) /  (" . $qtdDiasUteis . "-" . $diasPassados . ")) ";
        $sql1 .= " as METADIARIA, ";

        $sql2  = "((SELECT Sum(meta) from fat_meta_mensal ";
        $sql2 .= "where " . $whereM . " and (mes in ('" . $mes . "')) and (ano in ('" . $ano . "')) ) ";
        $sql2 .= "- Sum(p.TOTAL)) as FALTA,";
        $sql2 .= "(( Sum(p.Total) / " . $diasPassados . " ) * " . $qtdDiasUteis . ") ";
        $sql2 .= " as PROJECAOVALORVENDA, ";

        // Despesas: sem filtro de GENERO <> '1.40'
        $sql3  = "((Select Sum(L.Total) from FIN_LANCAMENTO L ";
        $sql3 .= "where (L.emissao >= '" . $dataIni . "') AND (L.emissao <= '" . $dataFim . "') ";
        $sql3 .= "and (L.TIPOLANCAMENTO = 'P') and ( ";
        $sql3 .= " L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS COM LOGISTICA%') ";
        $sql3 .= " or L.GENERO = (select GENERO from fin_genero where descricao like '%DESPESAS FIXAS%') ";
        $sql3 .= " or L.TIPODOCTO = 'B' or L.TIPODOCTO = 'D' or L.TIPODOCTO = 'T')) ";
        $sql3 .= "/ " . $diasPassados . " ) * " . $qtdDiasUteis . " ";
        $sql3 .= " as PROJECAODESPESAS, ";

        // Receitas financeiras: sem filtro de GENERO <> '1.40'
        $sql4  = "((Select Sum(L.Total) from FIN_LANCAMENTO L ";
        $sql4 .= "where (L.emissao >= '" . $dataIni . "') AND (L.emissao <= '" . $dataFim . "') ";
        $sql4 .= "and (L.TIPOLANCAMENTO = 'R') and ( ";
        $sql4 .= " L.GENERO = (select GENERO from fin_genero where descricao like '%RECEITAS FUTURAS%') ";
        $sql4 .= " or L.TIPODOCTO = 'N' or L.TIPODOCTO = 'B' or L.TIPODOCTO = 'D' ";
        $sql4 .= " or L.TIPODOCTO = 'C' or L.TIPODOCTO = 'E' or L.TIPODOCTO = 'R' ";
        $sql4 .= " or L.TIPODOCTO = 'K' or L.TIPODOCTO = 'X' or L.TIPODOCTO = 'P')) ";
        $sql4 .= "/ " . $diasPassados . " ) * " . $qtdDiasUteis . " ";
        $sql4 .= " as PROJECAORECEITAS, ";

        // Projeção de lucro líquido
        $sql5  = "((( Sum(p.LUCROBRUTO) - ( ";
        $sql5 .= "Select Coalesce(Sum(Total),0) as TOTAL ";
        $sql5 .= "from FIN_LANCAMENTO L where " . str_replace('p.ccusto', 'centrocusto', $where);
        $sql5 .= " and (L.emissao >= '" . $dataIni . "') AND (L.emissao <= '" . $dataFim . "') ";
        $sql5 .= "and (L.TIPOLANCAMENTO = 'P') )) ";
        $sql5 .= "/ " . $diasPassados . " ) * " . $qtdDiasUteis . ") as PROJECAOLUCROLIQUIDO, ";

        $sql6  = $qtdDiasUteis . "-" . $diasPassados . " as DIASRESTANTESDOMES, ";

        $sql7  = "Sum(p.TOTAL) / (( Count(*) / " . $diasPassados . " ) * " . $qtdDiasUteis . ") ";
        $sql7 .= " as TICKETMEDIODEVENDAS,";

        $sql8  = "Sum(p.LucroBruto) /  (( Count(*) / " . $diasPassados . " ) * " . $qtdDiasUteis . ") ";
        $sql8 .= " as LUCROBRUTOMEDIOPORVENDA,";

        $sql9  = "(Sum(p.LucroBruto) - ";
        $sql9 .= "( Select Coalesce(Sum(Total),0)  ";
        $sql9 .= "from FIN_LANCAMENTO L where " . str_replace('p.ccusto', 'centrocusto', $where);
        $sql9 .= " and (L.emissao >= '" . $dataIni . "') AND (L.emissao <= '" . $dataFim . "') ";
        $sql9 .= "and (L.TIPOLANCAMENTO = 'P') ";
        $sql9 .= ")) / (( Count(*) / " . $diasPassados . " ) * " . $qtdDiasUteis . ") ";
        $sql9 .= " as LUCROLIQUIDOMEDIOPORVENDA, ";

        $sql10  = "( Count(*) / " . $diasPassados . ") *  " . $qtdDiasUteis . " ";
        $sql10 .= " as NUMERODEVENDASPROJETADAS ";

        $sql11  = "FROM fat_pedido p ";
        $sql11 .= "LEFT JOIN amb_usuario u on (u.usuario=p.usrfatura) ";
        $sql11 .= "where " . $where . " AND (p.emissao >= '" . $dataIni . "') AND (p.emissao <= '" . $dataFim . "')  and ";
        $sql11 .= " ((p.situacao = 9) or (p.situacao = 6) or (p.situacao = 13)) ";
        $sql11 .= "GROUP BY year(p.emissao), month(p.emissao) ";

        $banco = new c_banco;
        $sql   = $sql1 . $sql2 . $sql3 . $sql4 . $sql5 . $sql6 . $sql7 . $sql8 . $sql9 . $sql10 . $sql11;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado;
    }

    public function totais($dataIni, $dataFim, $where, $wherel)
    {
        $sql  = "select sum(Total) AS VALORVENDA, Sum(LUCROBRUTO) as LUCROBRUTO, ";
        $sql .= "(Select Sum(Total) ";
        $sql .= "from FIN_LANCAMENTO L ";
        $sql .= "where " . $wherel . " AND (L.vencimento >= '" . $dataIni . "') AND (L.vencimento <= '" . $dataFim . "') ";
        $sql .= "and (L.TIPOLANCAMENTO = 'P') and (L.TOTAL > 0)) as DESPESAS, ";
        $sql .= "SUM(CUSTOTOTAL) as CUSTOTOTAL, ((SUM(LUCROBRUTO) / SUM(TOTAL)) * 100) as MARKUP, ";
        $sql .= "(( ( SUM(TOTAL) / SUM(CUSTOTOTAL) ) - 1 ) * 100) as MARGEMBRUTA ";
        $sql .= "FROM FAT_PEDIDO ";
        $sql .= "WHERE " . $where . " and (emissao >= '" . $dataIni . "') AND (emissao <= '" . $dataFim . "') and ";
        $sql .= "((situacao = 6) or (situacao = 9) or (situacao = 13))";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado;
    }

    public function totaisDetalhes($dataIni, $dataFim, $where)
    {
        $sql  = "select (Sum(p.CUSTOTOTAL)/Count(*)) AS CUSTOVENDEDOR, ";
        $sql .= "(Sum(p.LUCROBRUTO) / Sum(p.TOTAL)) * 100 as MARKUP, ";
        $sql .= "(((Sum(p.TOTAL) / Sum(p.CUSTOTOTAL)) -1) * 100) as MARGEMBRUTA, ";
        $sql .= "u.NOME as VENDEDOR ";
        $sql .= "FROM FAT_PEDIDO p ";
        $sql .= "LEFT JOIN AMB_USUARIO u on ( p.USRFATURA = u.USUARIO) ";
        $sql .= "WHERE " . $where . " AND (p.emissao >= '" . $dataIni . "') AND (p.emissao <= '" . $dataFim . "') and ";
        $sql .= "((p.situacao = 6) or (p.situacao = 9) or (p.situacao = 13)) ";
        $sql .= "group by p.usrfatura";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado;
    }

    public function metas($dataIni, $dataFim, $where, $vendedor = null)
    {
        $sql   = "Select Sum(p.Total) as VALORVENDIDO, u.nome as VENDEDOR, ";
        $sql  .= "v.Meta as METADEVENDAS, ((Sum(p.Total) / v.Meta)* 100) as ICMVENDAS, ";
        $sql  .= "(v.Meta * (m.metamargem / 100) ) MMLIQUIDA, ";
        $sql  .= "(( Sum(p.MARGEMLIQUIDA) / (v.Meta * (m.metamargem / 100) ) ) * 100) as ICM , ";
        $sql  .= "Sum(p.MARGEMLIQUIDA) as MARGEMLIQUIDA, ";
        $sql  .= "Sum(p.CUSTOTOTAL) as CUSTOTOTAL, ";
        $sql  .= "Sum(p.FRETE) as FRETE, ";
        $sql  .= "Sum(p.DESPACESSORIAS) as DESPACESSORIAS, ";
        $sql  .= "Sum(p.LUCROBRUTO) as LUCROBRUTO, ";
        $sql  .= "Count(*) as NUMVENDAS ";
        $sql  .= "FROM fat_pedido p ";
        $sql  .= "LEFT JOIN fat_meta_mensal m on (p.ccusto = m.ccusto) ";
        $sql  .= " and (m.ano = EXTRACT(year FROM p.emissao) ) ";
        $sql  .= " and (m.mes = EXTRACT(month FROM p.emissao) ) ";
        $sql  .= "LEFT JOIN fat_meta_mensal_vendedor v on (v.vendedor=p.usrfatura) and (m.id = v.metaid) ";
        $sql  .= "LEFT JOIN amb_usuario u on (u.usuario=p.usrfatura) ";
        $sql  .= "where " . $where . " and ((p.situacao = 6) or (p.situacao = 9) or (p.situacao = 13)) ";
        $sql  .= "and (p.emissao >= '" . $dataIni . "') AND (p.emissao <= '" . $dataFim . "') ";
        if ($vendedor !== null && $vendedor !== "") {
            $sql .= " AND (p.usrfatura = '" . $vendedor . "') ";
        }
        $sql  .= "group by u.nome, v.Meta, m.metamargem order by u.nome asc";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado;
    }

    public function financeiro($dataIni, $dataFim, $where)
    {
        // Receitas (R) ligadas a pedidos, por vencimento, sem excluir GENERO '1.40'
        $sql0  = "Select Sum(L.Original) as TOTAL, ";
        $sql0 .= "MAX((select DESCRICAO from FIN_GENERO where GENERO = L.GENERO)) as GENERO, ";
        $sql0 .= "CASE L.TIPODOCTO ";
        $sql0 .= "WHEN 'B' THEN 'BOLETO' ";
        $sql0 .= "WHEN 'D' THEN 'DINHEIRO' ";
        $sql0 .= "WHEN 'C' THEN 'CARTAO DEBITO'  ";
        $sql0 .= "WHEN 'K' THEN 'CARTAO CREDITO'  ";
        $sql0 .= "WHEN 'E' THEN 'CHEQUE' ";
        $sql0 .= "WHEN 'A' THEN 'TRANFERENCIA BANCARIA' ";
        $sql0 .= "WHEN 'N' THEN 'BONUS' ";
        $sql0 .= "WHEN 'X' THEN 'A RECEBER' ";
        $sql0 .= "WHEN 'P' THEN 'PIX' ";
        $sql0 .= "WHEN 'L' THEN 'NOTA FISCAL' ";
        $sql0 .= "ELSE '' END as TIPODOCTO, ";
        $sql0 .= "L.TIPOLANCAMENTO as TIPOLANCAMENTO, L.SITPGTO ";
        $sql0 .= "from FIN_LANCAMENTO L ";
        $sql0 .= "left join FAT_PEDIDO p on (p.pedido = L.DOCTO) ";
        $sql0 .= "where " . $where . " ";
        $sql0 .= "AND (L.vencimento >= '" . $dataIni . "') AND (L.vencimento <= '" . $dataFim . "') ";
        $sql0 .= "and (L.TIPOLANCAMENTO = 'R') AND (L.SITPGTO <> 'C') AND ";
        $sql0 .= "((L.TIPODOCTO = 'P') or (L.TIPODOCTO = 'X') or (L.TIPODOCTO = 'N') ";
        $sql0 .= "or (L.TIPODOCTO = 'B') or (L.TIPODOCTO = 'K') or (L.TIPODOCTO = 'D') ";
        $sql0 .= "or (L.TIPODOCTO = 'C') or (L.TIPODOCTO = 'E') or (L.TIPODOCTO = 'A') or (L.TIPODOCTO = 'L')) ";
        $sql0 .= "AND (L.docto > 0) AND (p.situacao <> 8) ";
        $sql0 .= "group by L.TIPODOCTO, L.SITPGTO, L.TIPOLANCAMENTO ";

        // Entradas (R) por gênero "ENTRADA"
        $sql1  = "Select Sum(L.Original) as TOTAL, ";
        $sql1 .= "MAX((select DESCRICAO from FIN_GENERO where GENERO = L.GENERO)) as GENERO, ";
        $sql1 .= "L.TIPODOCTO as TIPODOCTO, L.TIPOLANCAMENTO as TIPOLANCAMENTO, L.SITPGTO ";
        $sql1 .= "from FIN_LANCAMENTO L ";
        $sql1 .= "where " . $where . " ";
        $sql1 .= "AND (L.vencimento >= '" . $dataIni . "') AND (L.vencimento <= '" . $dataFim . "') ";
        $sql1 .= "and (L.TIPOLANCAMENTO = 'R') AND (L.SITPGTO <> 'C') ";
        $sql1 .= "and ( L.GENERO = (select GENERO from fin_genero where descricao like '%ENTRADA%' )) ";
        $sql1 .= "AND (L.docto > 0) ";
        $sql1 .= "group by L.TIPODOCTO, L.TIPOLANCAMENTO, L.SITPGTO ";

        // Pagamentos (P)
        $sql2  = "Select Sum(L.ORIGINAL) as TOTAL, ";
        $sql2 .= "g.DESCRICAO as GENERO, ";
        $sql2 .= "L.TIPODOCTO, ";
        $sql2 .= "L.TIPOLANCAMENTO as TIPOLANCAMENTO, L.SITPGTO ";
        $sql2 .= "from FIN_LANCAMENTO L ";
        $sql2 .= "Left join FIN_GENERO g on (L.GENERO = g.genero) ";
        $sql2 .= "where " . $where . " ";
        $sql2 .= "AND (L.vencimento >= '" . $dataIni . "') AND (L.vencimento <= '" . $dataFim . "') ";
        $sql2 .= "AND (L.SITPGTO <> 'C') ";
        $sql2 .= "and (L.TIPOLANCAMENTO = 'P') and (L.TOTAL > 0) ";
        $sql2 .= "group by g.DESCRICAO, L.TIPODOCTO, L.TIPOLANCAMENTO, L.SITPGTO ";
        $sql2 .= "ORDER BY TIPOLANCAMENTO, SITPGTO ASC";

        $banco = new c_banco;
        $sql   = $sql0 . " union " . $sql1 . " union " . $sql2;
        $banco->exec_sql($sql);
        $banco->close_connection();

        return $banco->resultado;
    }
}

