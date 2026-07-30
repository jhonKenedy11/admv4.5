<?php
/**
 * @package   admv4.5
 * @name      c_financeiro_dashboard
 * @version   4.5.0
 * @copyright 2026
 * @link      http://www.admsistema.com.br/
 */

$dir = dirname(__FILE__);
require_once($dir . '/../../bib/c_user.php');
require_once($dir . '/../../bib/c_date.php');
require_once($dir . '/../../bib/c_tools.php');
require_once($dir . '/../../bib/c_database_pdo.php');

/**
 * Dashboard financeiro (base: FIN_LANCAMENTO).
 *
 * Convenções usadas:
 * - TIPOLANCAMENTO: 'R' (recebimento) / 'P' (pagamento)
 * - SITPGTO: 'B' = baixado; demais/NULL = em aberto
 * - Datas: usa VENCIMENTO para carteira e PAGAMENTO para baixas.
 */
class c_financeiro_dashboard extends c_user
{
    private const CENTRO_CUSTO_TODOS = 'ALL';

    private ?string $dataIni = null;            // dd/mm/YYYY
    private ?string $dataFim = null;            // dd/mm/YYYY
    private ?string $centroCustoFiltro = null;  // CSV IDs

    /** @var array<int,string> */
    private array $centroCustoIdsCombo = [];
    /** @var array<int,string> */
    private array $centroCustoNamesCombo = [];

    public function setDataIni($dataIni): void
    {
        $valor = is_string($dataIni) ? trim($dataIni) : '';
        $this->dataIni = $valor !== '' ? $valor : null;
    }

    public function getDataIni(): string
    {
        return $this->dataIni ?? date('01/m/Y');
    }

    public function setDataFim($dataFim): void
    {
        $valor = is_string($dataFim) ? trim($dataFim) : '';
        $this->dataFim = $valor !== '' ? $valor : null;
    }

    public function getDataFim(): string
    {
        return $this->dataFim ?? date('d/m/Y');
    }

    /**
     * Aceita:
     * - string (GET) no formato "10300000,10400000"
     * - array (POST) vindo de <select multiple name="centroCusto[]">
     *
     * Armazena internamente como CSV (somente IDs numéricos).
     *
     * @param mixed $csvCentroCusto
     */
    public function setCentroCustoFiltro($csvCentroCusto): void
    {
        if (is_array($csvCentroCusto)) {
            $ids = array_values(array_unique(array_filter(array_map(
                static fn($v) => (int)trim((string)$v),
                $csvCentroCusto
            ), static fn($v) => $v > 0)));

            $csv = implode(',', array_map('strval', $ids));
            $this->centroCustoFiltro = $csv !== '' ? $csv : null;
            return;
        }

        $valor = is_string($csvCentroCusto) ? trim($csvCentroCusto) : '';
        $this->centroCustoFiltro = $valor !== '' ? $valor : null;
    }

    public function getCentroCustoFiltro(): string
    {
        return $this->centroCustoFiltro ?? '';
    }

    /** @return array<int,string> */
    public function getCentroCustoIdsCombo(): array
    {
        return $this->centroCustoIdsCombo;
    }

    /** @return array<int,string> */
    public function getCentroCustoNamesCombo(): array
    {
        return $this->centroCustoNamesCombo;
    }

    /** @return array<int,int> */
    private function centrosCustoSelecionados(): array
    {
        $csv = trim($this->getCentroCustoFiltro());
        if ($csv === '') {
            return [];
        }

        $ids = array_values(array_filter(array_map('trim', explode(',', $csv)), fn($v) => $v !== '' && $v !== self::CENTRO_CUSTO_TODOS));
        $idsInt = array_values(array_filter(array_map('intval', $ids), fn($v) => $v > 0));
        return array_values(array_unique($idsInt));
    }

    /**
     * Monta trecho SQL de IN() com bind params nomeados.
     * Retorna [sqlWhere, params].
     *
     * @param string $coluna Ex.: "L.CENTROCUSTO"
     * @param array<int,int> $ids
     * @param string $prefix Ex.: "cc"
     * @return array{0:string,1:array<string,int>}
     */
    private function montaFiltroInSql(string $coluna, array $ids, string $prefix): array
    {
        if (count($ids) === 0) {
            return ['', []];
        }

        $params = [];
        $placeholders = [];
        foreach ($ids as $i => $id) {
            $key = ':' . $prefix . $i;
            $placeholders[] = $key;
            $params[$key] = (int)$id;
        }

        return [" AND {$coluna} IN (" . implode(',', $placeholders) . ")", $params];
    }

    public function comboCentroCustoDashboard(): void
    {
        // Reaproveita o mesmo direito usado em pedidos/estoque para restringir a loja
        $somenteLoja = $this->verificaDireitoUsuario('PEDVERSOMENTEINFODALOJA', 'S', 'N');

        $db = new c_banco_pdo();
        $sql = "SELECT CENTROCUSTO AS ID, DESCRICAO FROM FIN_CENTRO_CUSTO";
        if ($somenteLoja) {
            $sql .= " WHERE CENTROCUSTO = :centro";
        }
        $sql .= " ORDER BY CENTROCUSTO";

        $db->prepare($sql);
        if ($somenteLoja) {
            $db->bindValue(':centro', (int)$this->m_empresacentrocusto, PDO::PARAM_INT);
        }
        $db->execute();
        $linhas = $db->fetchAll();
        $db->close();

        $ids = [self::CENTRO_CUSTO_TODOS];
        $nomes = ['Todos'];

        foreach ($linhas as $linha) {
            $ids[] = (string)$linha['ID'];
            $nomes[] = (string)$linha['DESCRICAO'];
        }

        $this->centroCustoIdsCombo = $ids;
        $this->centroCustoNamesCombo = $nomes;
    }

    /** @return array<string,mixed> */
    public function dadosDashboardMostra(): array
    {
        $dataHoraIni = c_date::convertDateBdSh($this->getDataIni(), $this->m_banco) . ' 00:00:00';
        $dataHoraFim = c_date::convertDateBdSh($this->getDataFim(), $this->m_banco) . ' 23:59:59';

        $ccSelecionados = $this->centrosCustoSelecionados();
        [$whereCc, $paramsCc] = $this->montaFiltroInSql('L.CENTROCUSTO', $ccSelecionados, 'cc');

        $paramsPeriodo = [
            ':dtIni' => $dataHoraIni,
            ':dtFim' => $dataHoraFim,
        ];

        $params = array_merge($paramsPeriodo, $paramsCc);

        $kpis = $this->consultaKpis($whereCc, $params);
        $serie = $this->consultaMovimentoPorDia($whereCc, $params);
        $topGeneroDesp = $this->consultaTopGeneroPeriodo('P', $whereCc, $params, 8);
        $topGeneroRec = $this->consultaTopGeneroPeriodo('R', $whereCc, $params, 8);

        $vencidosPagar = $this->consultaVencidos('P', $whereCc, $params, 20);
        $vencidosReceber = $this->consultaVencidos('R', $whereCc, $params, 20);
        $baixadosRecentes = $this->consultaBaixadosRecentes($whereCc, $paramsCc, 20);

        return [
            'dataIni' => $this->getDataIni(),
            'dataFim' => $this->getDataFim(),
            'kpis' => $kpis,
            'chartLabels' => json_encode(array_column($serie, 'DIA'), JSON_UNESCAPED_UNICODE),
            'chartReceb' => json_encode(array_map('floatval', array_column($serie, 'RECEB')), JSON_UNESCAPED_UNICODE),
            'chartPag' => json_encode(array_map('floatval', array_column($serie, 'PAG')), JSON_UNESCAPED_UNICODE),
            'pieDespLabels' => json_encode(array_column($topGeneroDesp, 'LABEL'), JSON_UNESCAPED_UNICODE),
            'pieDespValores' => json_encode(array_map('floatval', array_column($topGeneroDesp, 'VALOR')), JSON_UNESCAPED_UNICODE),
            'pieRecLabels' => json_encode(array_column($topGeneroRec, 'LABEL'), JSON_UNESCAPED_UNICODE),
            'pieRecValores' => json_encode(array_map('floatval', array_column($topGeneroRec, 'VALOR')), JSON_UNESCAPED_UNICODE),
            'vencidosPagar' => $vencidosPagar,
            'vencidosReceber' => $vencidosReceber,
            'baixadosRecentes' => $baixadosRecentes,
        ];
    }

    /** @return array<string,mixed> */
    private function consultaKpis(string $whereCc, array $params): array
    {
        $db = new c_banco_pdo();
        $sql = "SELECT
                    (SELECT COALESCE(SUM(L.TOTAL), 0)
                       FROM FIN_LANCAMENTO L
                      WHERE L.TIPOLANCAMENTO = 'R'
                        AND (L.SITPGTO IS NULL OR L.SITPGTO <> 'B')
                        AND L.TOTAL > 0
                        AND L.VENCIMENTO BETWEEN :dtIni AND :dtFim
                        {$whereCc}
                    ) AS RECEBER_ABERTO,
                    (SELECT COALESCE(SUM(L.TOTAL), 0)
                       FROM FIN_LANCAMENTO L
                      WHERE L.TIPOLANCAMENTO = 'P'
                        AND (L.SITPGTO IS NULL OR L.SITPGTO <> 'B')
                        AND L.TOTAL > 0
                        AND L.VENCIMENTO BETWEEN :dtIni AND :dtFim
                        {$whereCc}
                    ) AS PAGAR_ABERTO,
                    (SELECT COALESCE(SUM(L.ORIGINAL), 0)
                       FROM FIN_LANCAMENTO L
                      WHERE L.TIPOLANCAMENTO = 'R'
                        AND L.SITPGTO = 'B'
                        AND L.PAGAMENTO BETWEEN :dtIni AND :dtFim
                        {$whereCc}
                    ) AS RECEBIDO_PERIODO,
                    (SELECT COALESCE(SUM(L.ORIGINAL), 0)
                       FROM FIN_LANCAMENTO L
                      WHERE L.TIPOLANCAMENTO = 'P'
                        AND L.SITPGTO = 'B'
                        AND L.PAGAMENTO BETWEEN :dtIni AND :dtFim
                        {$whereCc}
                    ) AS PAGO_PERIODO,
                    (SELECT COUNT(*)
                       FROM FIN_LANCAMENTO L
                      WHERE L.TIPOLANCAMENTO = 'R'
                        AND (L.SITPGTO IS NULL OR L.SITPGTO <> 'B')
                        AND L.TOTAL > 0
                        AND L.VENCIMENTO < NOW()
                        {$whereCc}
                    ) AS RECEBER_ATRASADO_QTD,
                    (SELECT COUNT(*)
                       FROM FIN_LANCAMENTO L
                      WHERE L.TIPOLANCAMENTO = 'P'
                        AND (L.SITPGTO IS NULL OR L.SITPGTO <> 'B')
                        AND L.TOTAL > 0
                        AND L.VENCIMENTO < NOW()
                        {$whereCc}
                    ) AS PAGAR_ATRASADO_QTD";

        $db->prepare($sql);
        foreach ($params as $k => $v) {
            if ($k === ':dtIni' || $k === ':dtFim') {
                $db->bindValue($k, (string)$v, PDO::PARAM_STR);
            } else {
                $db->bindValue($k, (int)$v, PDO::PARAM_INT);
            }
        }
        $db->execute();
        $row = $db->fetch();
        $db->close();

        $recebido = (float)($row['RECEBIDO_PERIODO'] ?? 0);
        $pago = (float)($row['PAGO_PERIODO'] ?? 0);

        return [
            'receberAberto' => (float)($row['RECEBER_ABERTO'] ?? 0),
            'pagarAberto' => (float)($row['PAGAR_ABERTO'] ?? 0),
            'recebidoPeriodo' => $recebido,
            'pagoPeriodo' => $pago,
            'saldoLiquidoPeriodo' => $recebido - $pago,
            'receberAtrasadoQtd' => (int)($row['RECEBER_ATRASADO_QTD'] ?? 0),
            'pagarAtrasadoQtd' => (int)($row['PAGAR_ATRASADO_QTD'] ?? 0),
        ];
    }

    /**
     * Série diária (por vencimento) de valores em aberto (receber/pagar).
     * @return array<int,array{DIA:string,RECEB:float,PAG:float}>
     */
    private function consultaMovimentoPorDia(string $whereCc, array $params): array
    {
        $db = new c_banco_pdo();
        $sql = "SELECT
                    DATE(L.VENCIMENTO) AS DIA,
                    COALESCE(SUM(CASE WHEN L.TIPOLANCAMENTO = 'R' THEN L.TOTAL ELSE 0 END), 0) AS RECEB,
                    COALESCE(SUM(CASE WHEN L.TIPOLANCAMENTO = 'P' THEN L.TOTAL ELSE 0 END), 0) AS PAG
                FROM FIN_LANCAMENTO L
                WHERE (L.SITPGTO IS NULL OR L.SITPGTO <> 'B')
                  AND L.TOTAL > 0
                  AND L.VENCIMENTO BETWEEN :dtIni AND :dtFim
                  {$whereCc}
                GROUP BY DATE(L.VENCIMENTO)
                ORDER BY DATE(L.VENCIMENTO)";
        $db->prepare($sql);
        foreach ($params as $k => $v) {
            if ($k === ':dtIni' || $k === ':dtFim') {
                $db->bindValue($k, (string)$v, PDO::PARAM_STR);
            } else {
                $db->bindValue($k, (int)$v, PDO::PARAM_INT);
            }
        }
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();

        $out = [];
        foreach (($rows ?: []) as $r) {
            $out[] = [
                'DIA' => (string)$r['DIA'],
                'RECEB' => (float)$r['RECEB'],
                'PAG' => (float)$r['PAG'],
            ];
        }
        return $out;
    }

    /**
     * Top gêneros no período (por vencimento; em aberto).
     * @return array<int,array{LABEL:string,VALOR:float}>
     */
    private function consultaTopGeneroPeriodo(string $tipo, string $whereCc, array $params, int $topN): array
    {
        $tipo = $tipo === 'P' ? 'P' : 'R';

        $db = new c_banco_pdo();
        $sql = "SELECT
                    COALESCE(G.DESCRICAO, L.GENERO) AS LABEL,
                    COALESCE(SUM(L.TOTAL), 0) AS VALOR
                FROM FIN_LANCAMENTO L
                LEFT JOIN FIN_GENERO G ON (G.GENERO = L.GENERO)
                WHERE L.TIPOLANCAMENTO = :tipo
                  AND (L.SITPGTO IS NULL OR L.SITPGTO <> 'B')
                  AND L.TOTAL > 0
                  AND L.VENCIMENTO BETWEEN :dtIni AND :dtFim
                  {$whereCc}
                GROUP BY L.GENERO, G.DESCRICAO
                ORDER BY VALOR DESC
                LIMIT {$topN}";
        $db->prepare($sql);
        $db->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        foreach ($params as $k => $v) {
            if ($k === ':dtIni' || $k === ':dtFim') {
                $db->bindValue($k, (string)$v, PDO::PARAM_STR);
            } else {
                $db->bindValue($k, (int)$v, PDO::PARAM_INT);
            }
        }
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();

        $out = [];
        foreach (($rows ?: []) as $r) {
            $out[] = ['LABEL' => (string)$r['LABEL'], 'VALOR' => (float)$r['VALOR']];
        }
        return $out;
    }

    /** @return array<int,array<string,mixed>> */
    private function consultaVencidos(string $tipo, string $whereCc, array $params, int $limit): array
    {
        $tipo = $tipo === 'P' ? 'P' : 'R';

        $db = new c_banco_pdo();
        $sql = "SELECT
                    L.ID,
                    L.PESSOA,
                    C.NOME AS PESSOA_NOME,
                    L.DOCTO,
                    L.SERIE,
                    L.PARCELA,
                    L.GENERO,
                    G.DESCRICAO AS GENERO_DESC,
                    L.CENTROCUSTO,
                    CC.DESCRICAO AS CENTROCUSTO_DESC,
                    L.VENCIMENTO,
                    L.TOTAL
                FROM FIN_LANCAMENTO L
                LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = L.PESSOA)
                LEFT JOIN FIN_GENERO G ON (G.GENERO = L.GENERO)
                LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = L.CENTROCUSTO)
                WHERE L.TIPOLANCAMENTO = :tipo
                  AND (L.SITPGTO IS NULL OR L.SITPGTO <> 'B')
                  AND L.TOTAL > 0
                  AND L.VENCIMENTO BETWEEN :dtIni AND :dtFim
                  AND L.VENCIMENTO < NOW()
                  {$whereCc}
                ORDER BY L.VENCIMENTO ASC, L.ID ASC
                LIMIT {$limit}";
        $db->prepare($sql);
        $db->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        foreach ($params as $k => $v) {
            if ($k === ':dtIni' || $k === ':dtFim') {
                $db->bindValue($k, (string)$v, PDO::PARAM_STR);
            } else {
                $db->bindValue($k, (int)$v, PDO::PARAM_INT);
            }
        }
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();
        return $rows ?: [];
    }

    /** @return array<int,array<string,mixed>> */
    private function consultaBaixadosRecentes(string $whereCc, array $paramsCc, int $limit): array
    {
        $db = new c_banco_pdo();
        $sql = "SELECT
                    L.ID,
                    L.TIPOLANCAMENTO,
                    L.PESSOA,
                    C.NOME AS PESSOA_NOME,
                    L.DOCTO,
                    L.SERIE,
                    L.PARCELA,
                    L.GENERO,
                    G.DESCRICAO AS GENERO_DESC,
                    L.CENTROCUSTO,
                    CC.DESCRICAO AS CENTROCUSTO_DESC,
                    L.PAGAMENTO,
                    L.ORIGINAL
                FROM FIN_LANCAMENTO L
                LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = L.PESSOA)
                LEFT JOIN FIN_GENERO G ON (G.GENERO = L.GENERO)
                LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = L.CENTROCUSTO)
                WHERE L.SITPGTO = 'B'
                  AND L.PAGAMENTO IS NOT NULL
                  {$whereCc}
                ORDER BY L.PAGAMENTO DESC, L.ID DESC
                LIMIT {$limit}";
        $db->prepare($sql);
        foreach ($paramsCc as $k => $v) {
            $db->bindValue($k, (int)$v, PDO::PARAM_INT);
        }
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();
        return $rows ?: [];
    }
}

