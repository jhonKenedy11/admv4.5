<?php
/**
 * @package   admv4.5
 * @name      c_estoque_dashboard
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
 * Classe de regras/consultas do Dashboard de Estoque.
 *
 * Padrão espelhado do dashboard de pedidos:
 * - mantém filtros (período e centro de custo)
 * - carrega combo de centros de custo disponíveis
 * - executa consultas agregadas (PDO) e devolve array para o form/template
 */
class c_estoque_dashboard extends c_user
{
    private const CENTRO_CUSTO_TODOS = 'ALL';

    private ?string $dataIni = null;            // dd/mm/YYYY
    private ?string $dataFim = null;            // dd/mm/YYYY
    private ?string $centroCustoFiltro = null;  // CSV de IDs

    /** @var array<int,string> */
    private array $centroCustoIdsCombo = [];
    /** @var array<int,string> */
    private array $centroCustoNamesCombo = [];

    /**
     * @return array{':dataIni':string,':dataFim':string}
     */
    private function periodoParams(): array
    {
        return [
            ':dataIni' => c_date::convertDateBdSh($this->getDataIni(), $this->m_banco) . ' 00:00:00',
            ':dataFim' => c_date::convertDateBdSh($this->getDataFim(), $this->m_banco) . ' 23:59:59',
        ];
    }

    /**
     * Bind genérico de parâmetros (int/string) em uma statement do c_banco_pdo.
     * @param array<string,int|float|string> $params
     */
    private function bindParams(c_banco_pdo $db, array $params): void
    {
        foreach ($params as $k => $v) {
            if (is_int($v)) {
                $db->bindValue($k, $v, PDO::PARAM_INT);
                continue;
            }
            $db->bindValue($k, (string)$v, PDO::PARAM_STR);
        }
    }

    /** @return array<int,string> */
    private function centrosCustoSelecionados(): array
    {
        $csv = trim($this->getCentroCustoFiltro());
        if ($csv === '') {
            return [];
        }

        $ids = array_values(array_filter(
            array_map('trim', explode(',', $csv)),
            static fn($v) => $v !== '' && $v !== self::CENTRO_CUSTO_TODOS
        ));

        $idsInt = array_values(array_filter(array_map('intval', $ids), static fn($v) => $v > 0));
        $idsStr = array_map(static fn($v) => (string)$v, $idsInt);
        return array_values(array_unique($idsStr));
    }

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
     * Armazena internamente como CSV (somente IDs numéricos), para reaproveitar o restante do fluxo.
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

    public function comboCentroCustoDashboard(): void
    {
        $somenteLoja = $this->verificaDireitoUsuario('ESTVERSOMENTEINFODALOJA', 'S', 'N');

        $db = new c_banco_pdo();
        $sqlConsulta = "SELECT CENTROCUSTO AS ID, DESCRICAO
                FROM FIN_CENTRO_CUSTO";
        if ($somenteLoja) {
            $sqlConsulta .= " WHERE CENTROCUSTO = :centro";
        }
        $sqlConsulta .= " ORDER BY CENTROCUSTO";

        $db->prepare($sqlConsulta);
        if ($somenteLoja) {
            $db->bindValue(':centro', (int)$this->m_empresacentrocusto, PDO::PARAM_INT);
        }
        $db->execute();
        $linhasCentroCusto = $db->fetchAll();
        $db->close();

        $idsCentroCusto = [];
        $nomesCentroCusto = [];

        $idsCentroCusto[] = self::CENTRO_CUSTO_TODOS;
        $nomesCentroCusto[] = 'Todos';

        foreach ($linhasCentroCusto as $linhaCentroCusto) {
            $idsCentroCusto[] = (string)$linhaCentroCusto['ID'];
            $nomesCentroCusto[] = (string)$linhaCentroCusto['DESCRICAO'];
        }

        $this->centroCustoIdsCombo = $idsCentroCusto;
        $this->centroCustoNamesCombo = $nomesCentroCusto;
    }

    /** @return array<string,mixed> */
    public function dadosDashboardMostra(): array
    {
        $dataInicialBr = $this->getDataIni();
        $dataFinalBr = $this->getDataFim();
        $centrosCustoSelecionados = $this->centrosCustoSelecionados();

        $filtroCentroCusto = $this->montaFiltroCentroCustoSql($centrosCustoSelecionados);
        $whereCentro = $filtroCentroCusto['where'];
        $paramsCentro = $filtroCentroCusto['params'];

        $paramsBase = $paramsCentro + $this->periodoParams();

        // Consulta 1: base completa de estoque por produto.
        $produtosEstoque = $this->consultaProdutosEstoque($whereCentro, $paramsCentro);

        // Consulta 2: base completa de notas no período.
        $movimentosNf = $this->consultaMovimentoNotas($whereCentro, $paramsBase);

        $kpis = [
            'itensEstoque' => 0,
            'itensReservados' => 0,
            'produtosComEstoque' => 0,
        ];
        $alertas = [
            'produtosAbaixoMinimo' => 0,
            'produtosSemEstoque' => 0,
        ];
        $abaixoMinimoLista = [];

        foreach ($produtosEstoque as $p) {
            $estoque = (int)$p['ESTOQUE'];
            $reserva = (int)$p['RESERVA'];
            $disponivel = max($estoque - $reserva, 0);
            $minimo = (float)$p['MINIMO'];

            $kpis['itensEstoque'] += $estoque;
            $kpis['itensReservados'] += $reserva;
            if ($estoque > 0) {
                $kpis['produtosComEstoque']++;
            }
            if ($disponivel <= $minimo) {
                $alertas['produtosAbaixoMinimo']++;
                $abaixoMinimoLista[] = [
                    'CODIGO' => (int)$p['CODIGO'],
                    'DESCRICAO' => (string)$p['DESCRICAO'],
                    'GRUPO' => (string)$p['GRUPO'],
                    'MINIMO' => $minimo,
                    'ESTOQUE' => $estoque,
                    'RESERVA' => $reserva,
                    'DISPONIVEL' => $disponivel,
                ];
            }
            if ($estoque <= 0) {
                $alertas['produtosSemEstoque']++;
            }
        }

        usort($abaixoMinimoLista, static function (array $a, array $b): int {
            $faltaA = $a['MINIMO'] - $a['DISPONIVEL'];
            $faltaB = $b['MINIMO'] - $b['DISPONIVEL'];
            if ($faltaA !== $faltaB) {
                return $faltaB <=> $faltaA;
            }
            if ($a['DISPONIVEL'] !== $b['DISPONIVEL']) {
                return $a['DISPONIVEL'] <=> $b['DISPONIVEL'];
            }
            return strcmp($a['DESCRICAO'], $b['DESCRICAO']);
        });
        $abaixoMinimoLista = array_slice($abaixoMinimoLista, 0, 25);

        $movimentoPorDiaMap = [];
        $topSaidasMap = [];
        $saidasPorGrupoMap = [];
        foreach ($movimentosNf as $m) {
            $diaSql = (string)$m['DIA_SQL'];
            if (!isset($movimentoPorDiaMap[$diaSql])) {
                $movimentoPorDiaMap[$diaSql] = [
                    'DIA' => date('d/m', strtotime($diaSql)),
                    'ENTRADA' => 0.0,
                    'SAIDA' => 0.0,
                ];
            }

            $tipo = (int)$m['TIPO'];
            $quant = (float)$m['QUANT'];
            $valor = (float)$m['VALOR'];

            if ($tipo === 0) {
                $movimentoPorDiaMap[$diaSql]['ENTRADA'] += $quant;
                continue;
            }

            $movimentoPorDiaMap[$diaSql]['SAIDA'] += $quant;

            $chaveProduto = implode('|', [
                (string)$m['CODIGO'],
                (string)$m['DESCRICAO'],
                (string)$m['UNIDADE'],
            ]);
            if (!isset($topSaidasMap[$chaveProduto])) {
                $topSaidasMap[$chaveProduto] = [
                    'CODIGO' => (int)$m['CODIGO'],
                    'DESCRICAO' => (string)$m['DESCRICAO'],
                    'UNIDADE' => (string)$m['UNIDADE'],
                    'QUANT' => 0.0,
                    'VALOR' => 0.0,
                ];
            }
            $topSaidasMap[$chaveProduto]['QUANT'] += $quant;
            $topSaidasMap[$chaveProduto]['VALOR'] += $valor;

            $grupo = (string)$m['GRUPO'];
            if (!isset($saidasPorGrupoMap[$grupo])) {
                $saidasPorGrupoMap[$grupo] = 0.0;
            }
            $saidasPorGrupoMap[$grupo] += $quant;
        }

        ksort($movimentoPorDiaMap);
        $movimentoPorDia = array_values($movimentoPorDiaMap);

        $topSaidas = array_values($topSaidasMap);
        usort($topSaidas, static function (array $a, array $b): int {
            if ($a['QUANT'] !== $b['QUANT']) {
                return $b['QUANT'] <=> $a['QUANT'];
            }
            return $b['VALOR'] <=> $a['VALOR'];
        });
        $topSaidas = array_slice($topSaidas, 0, 25);

        $saidasPorGrupo = [];
        foreach ($saidasPorGrupoMap as $grupo => $quantGrupo) {
            $saidasPorGrupo[] = ['GRUPO' => (string)$grupo, 'QUANT' => (float)$quantGrupo];
        }
        usort($saidasPorGrupo, static fn(array $a, array $b): int => $b['QUANT'] <=> $a['QUANT']);

        $saidasPorGrupoFinal = [];
        $outros = 0.0;
        foreach ($saidasPorGrupo as $idx => $itemGrupo) {
            if ($idx < 8) {
                $saidasPorGrupoFinal[] = $itemGrupo;
            } else {
                $outros += (float)$itemGrupo['QUANT'];
            }
        }
        if ($outros > 0) {
            $saidasPorGrupoFinal[] = ['GRUPO' => 'Outros', 'QUANT' => $outros];
        }

        $labels = array_column($movimentoPorDia, 'DIA');
        $entradas = array_map('floatval', array_column($movimentoPorDia, 'ENTRADA'));
        $saidas = array_map('floatval', array_column($movimentoPorDia, 'SAIDA'));

        $pizzaLabels = array_column($saidasPorGrupoFinal, 'GRUPO');
        $pizzaValores = array_map('floatval', array_column($saidasPorGrupoFinal, 'QUANT'));

        $kpiCards = [
            ['icone' => 'fa fa-cubes blue', 'valor' => $kpis['itensEstoque'], 'titulo' => 'Itens disponíveis', 'descricao' => 'Quantidade disponível para venda/uso'],
            ['icone' => 'fa fa-lock orange', 'valor' => $kpis['itensReservados'], 'titulo' => 'Itens reservados', 'descricao' => 'Separados/comprometidos (não disponíveis)'],
            ['icone' => 'fa fa-tags green', 'valor' => $kpis['produtosComEstoque'], 'titulo' => 'Produtos com estoque', 'descricao' => 'Produtos distintos com ao menos 1 unidade disponível'],
            ['icone' => 'fa fa-exclamation-triangle red', 'valor' => $alertas['produtosAbaixoMinimo'], 'titulo' => 'Abaixo do mínimo', 'descricao' => 'Disponível menor ou igual ao mínimo cadastrado'],
        ];

        return [
            'dataIni' => $dataInicialBr,
            'dataFim' => $dataFinalBr,
            'kpiCards' => $kpiCards,
            'kpis' => $kpis,
            'alertas' => $alertas,
            'abaixoMinimoLista' => $abaixoMinimoLista,
            'topSaidas' => $topSaidas,
            'chartLabels' => json_encode($labels, JSON_UNESCAPED_UNICODE),
            'chartEntradas' => json_encode($entradas, JSON_UNESCAPED_UNICODE),
            'chartSaidas' => json_encode($saidas, JSON_UNESCAPED_UNICODE),
            'pieLabels' => json_encode($pizzaLabels, JSON_UNESCAPED_UNICODE),
            'pieValores' => json_encode($pizzaValores, JSON_UNESCAPED_UNICODE),
        ];
    }

    /**
     * @param array<int,string> $centrosIds
     * @return array{where:string,params:array<string,int>}
     */
    private function montaFiltroCentroCustoSql(array $centrosIds): array
    {
        $placeholders = [];
        $params = [];

        foreach (array_values($centrosIds) as $i => $centroId) {
            if ($centroId !== self::CENTRO_CUSTO_TODOS) {
                $p = ':cc' . $i;
                $placeholders[] = $p;
                $params[$p] = (int)$centroId;
            }
        }

        if (empty($placeholders)) {
            return ['where' => '', 'params' => []];
        }

        return [
            'where' => ' AND CENTROCUSTO IN (' . implode(',', $placeholders) . ')',
            'params' => $params,
        ];
    }

    /**
     * Consulta-base de estoque por produto (única para todos os blocos de estoque).
     *
     * @return array<int,array{CODIGO:int,DESCRICAO:string,GRUPO:string,MINIMO:float,ESTOQUE:int,RESERVA:int}>
     */
    private function consultaProdutosEstoque(string $whereCentro, array $paramsCentro): array
    {
        $whereE = $whereCentro !== '' ? str_replace('CENTROCUSTO', 'E.CENTROCUSTO', $whereCentro) : '';

        $db = new c_banco_pdo();
        $sql = "WITH EST AS (
                    SELECT E.CODPRODUTO,
                           SUM(CASE WHEN E.STATUS = 0 THEN 1 ELSE 0 END) AS ESTOQUE,
                           SUM(CASE WHEN E.STATUS = 1 THEN 1 ELSE 0 END) AS RESERVA
                    FROM EST_PRODUTO_ESTOQUE E
                    WHERE 1=1
                      AND E.STATUS IN (0,1)
                      {$whereE}
                    GROUP BY E.CODPRODUTO
                )
                SELECT
                    P.CODIGO AS CODIGO,
                    P.DESCRICAO AS DESCRICAO,
                    COALESCE(G.DESCRICAO, '') AS GRUPO,
                    COALESCE(P.QUANTMINIMA, 0) AS MINIMO,
                    COALESCE(EST.ESTOQUE, 0) AS ESTOQUE,
                    COALESCE(EST.RESERVA, 0) AS RESERVA
                FROM EST_PRODUTO P
                LEFT JOIN (
                    SELECT GRUPO, MAX(DESCRICAO) AS DESCRICAO
                    FROM EST_GRUPO
                    GROUP BY GRUPO
                ) G ON (G.GRUPO = P.GRUPO)
                LEFT JOIN EST ON (EST.CODPRODUTO = P.CODIGO)
                WHERE P.DATAFORALINHA IS NULL";

        $db->prepare($sql);
        $this->bindParams($db, $paramsCentro);
        $db->execute();
        $linhas = $db->fetchAll();
        $db->close();

        $resp = [];
        foreach ($linhas as $l) {
            $resp[] = [
                'CODIGO' => (int)$l['CODIGO'],
                'DESCRICAO' => (string)$l['DESCRICAO'],
                'GRUPO' => (string)$l['GRUPO'],
                'MINIMO' => (float)$l['MINIMO'],
                'ESTOQUE' => (int)$l['ESTOQUE'],
                'RESERVA' => (int)$l['RESERVA'],
            ];
        }
        return $resp;
    }

    /**
     * Consulta-base de notas (única para todos os blocos de NF).
     *
     * @return array<int,array{DIA_SQL:string,TIPO:int,CODIGO:int,DESCRICAO:string,UNIDADE:string,GRUPO:string,QUANT:float,VALOR:float}>
     */
    private function consultaMovimentoNotas(string $whereCentro, array $params): array
    {
        $whereNF = $whereCentro !== '' ? str_replace('CENTROCUSTO', 'NF.CENTROCUSTO', $whereCentro) : '';

        $db = new c_banco_pdo();
        $sql = "SELECT
                    DATE(NF.EMISSAO) AS DIA_SQL,
                    NF.TIPO AS TIPO,
                    NFP.CODPRODUTO AS CODIGO,
                    COALESCE(P.DESCRICAO, NFP.DESCRICAO, '') AS DESCRICAO,
                    COALESCE(P.UNIDADE, '') AS UNIDADE,
                    COALESCE(G.DESCRICAO, 'Sem grupo') AS GRUPO,
                    COALESCE(SUM(NFP.QUANT), 0) AS QUANT,
                    COALESCE(SUM(NFP.TOTAL), 0) AS VALOR
                FROM EST_NOTA_FISCAL NF
                INNER JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NFP.IDNF = NF.ID)
                LEFT JOIN EST_PRODUTO P ON (P.CODIGO = NFP.CODPRODUTO)
                LEFT JOIN (
                    SELECT GRUPO, MAX(DESCRICAO) AS DESCRICAO
                    FROM EST_GRUPO
                    GROUP BY GRUPO
                ) G ON (G.GRUPO = P.GRUPO)
                WHERE NF.EMISSAO BETWEEN :dataIni AND :dataFim
                  AND NF.SITUACAO = 'B'
                  AND NF.SERIE <> 'INV'
                  {$whereNF}
                GROUP BY DATE(NF.EMISSAO),
                         NF.TIPO,
                         NFP.CODPRODUTO,
                         COALESCE(P.DESCRICAO, NFP.DESCRICAO, ''),
                         COALESCE(P.UNIDADE, ''),
                         COALESCE(G.DESCRICAO, 'Sem grupo')";

        $db->prepare($sql);
        $this->bindParams($db, $params);
        $db->execute();
        $linhas = $db->fetchAll();
        $db->close();

        $resp = [];
        foreach ($linhas as $l) {
            $resp[] = [
                'DIA_SQL' => (string)$l['DIA_SQL'],
                'TIPO' => (int)$l['TIPO'],
                'CODIGO' => (int)$l['CODIGO'],
                'DESCRICAO' => (string)$l['DESCRICAO'],
                'UNIDADE' => (string)$l['UNIDADE'],
                'GRUPO' => (string)$l['GRUPO'],
                'QUANT' => (float)$l['QUANT'],
                'VALOR' => (float)$l['VALOR'],
            ];
        }
        return $resp;
    }
}

