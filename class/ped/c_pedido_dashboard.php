<?php
/**
 * @package   admv4.5
 * @name      c_pedido_dashboard
 * @version   4.5.0
 * @copyright 2025
 * @link      http://www.admsistema.com.br/
 */

$dir = dirname(__FILE__);
require_once($dir . '/../../bib/c_user.php');
require_once($dir . '/../../bib/c_date.php');
require_once($dir . '/../../bib/c_tools.php');
require_once($dir . '/../../bib/c_database_pdo.php');
require_once($dir . '/../../bib/c_database.php');
require_once($dir . '/c_pedido_venda_telhas_dash.php');

/**
 * Classe de regras/consultas do Dashboard de Pedidos.
 *
 * Responsabilidades:
 * - Armazenar filtros do dashboard (período e centro de custo)
 * - Montar a lista (combo) de centros de custo disponíveis ao usuário
 * - Executar consultas agregadas (PDO) e devolver os dados para o form exibir no template
 *
 * Forecast, projeção e metas (mesmo critério do telhas 4.3.x):
 * - Toda a montagem de datas (BR → ISO), dias úteis, AMB_FERIADO, FAT_META_MENSAL.TOTALDIAMES
 *   e fragmentos WHERE por centro de custo fica **nesta classe** (métodos privados abaixo).
 * - Apenas as consultas SQL em si vêm de {@see c_pedidoVendaTelhasDash} (`forecast`, `projecao`, `metas`),
 *   instanciada aqui — a classe telhas não deve ser alterada para o fluxo do pedido_dashboard.
 */
class c_pedido_dashboard extends c_user
{
    private const CENTRO_CUSTO_TODOS = 'ALL';

    private ?string $dataIni = null;            // dd/mm/YYYY (filtro)
    private ?string $dataFim = null;            // dd/mm/YYYY (filtro)
    private ?string $centroCustoFiltro = null;  // CSV de IDs (ex.: "1,2,3")

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

    /**
     * Carrega o combo de Centro de Custo que o usuário pode consultar.
     *
     * Regra aplicada:
     * - Se o direito `PEDVERSOMENTEINFODALOJA` estiver ativo, restringe ao centro de custo da empresa do usuário.
     * - Caso contrário, lista todos os centros de custo.
     *
     * @return void
     */
    public function comboCentroCustoDashboard(): void
    {
        $somenteLoja = $this->verificaDireitoUsuario('PEDVERSOMENTEINFODALOJA', 'S', 'N');

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

        // Opção para consultar todos os centros de custo
        $idsCentroCusto[] = self::CENTRO_CUSTO_TODOS;
        $nomesCentroCusto[] = 'Todos';

        foreach ($linhasCentroCusto as $linhaCentroCusto) {
            $idsCentroCusto[] = (string)$linhaCentroCusto['ID'];
            $nomesCentroCusto[] = (string)$linhaCentroCusto['DESCRICAO'];
        }

        $this->centroCustoIdsCombo = $idsCentroCusto;
        $this->centroCustoNamesCombo = $nomesCentroCusto;
    }

    /**
     * Retorna todos os dados necessários para o template do dashboard.
     *
     * O form (`p_pedido_dashboard.php`) percorre o array retornado e faz `assign` no Smarty,
     * mantendo a responsabilidade de "display" no template.
     *
     * @return array<string,mixed>
     */
    public function dadosDashboardMostra(): array
    {
        $dataInicialBr = $this->getDataIni();
        $dataFinalBr = $this->getDataFim();

        $centrosCustoSelecionados = $this->centrosCustoSelecionados() ?: $this->centroCustoIdsCombo;

        $filtroCentroCusto = $this->montaFiltroCentroCustoSql($centrosCustoSelecionados);
        $whereCentroCusto = $filtroCentroCusto['where'];
        $parametrosConsulta = array_merge($filtroCentroCusto['params'], [
            ':dataIni' => c_date::convertDateBdSh($dataInicialBr, $this->m_banco) . ' 00:00:00',
            ':dataFim' => c_date::convertDateBdSh($dataFinalBr, $this->m_banco) . ' 23:59:59',
        ]);

        $kpis = $this->consultaKpisFaturado($whereCentroCusto, $parametrosConsulta);
        $total = $this->consultaResumoOperacional($whereCentroCusto, $parametrosConsulta);
        $porSituacao = $this->consultaPedidosPorSituacao($whereCentroCusto, $parametrosConsulta);
        $topVendedores = $this->consultaTopVendedoresFaturado($whereCentroCusto, $parametrosConsulta);
        $totaisDet = $this->consultaMargemPorVendedorFaturado($whereCentroCusto, $parametrosConsulta);
        $faturamentoPorDia = $this->consultaFaturamentoPorDia($whereCentroCusto, $parametrosConsulta);

        $labelsGrafico = [];
        $valoresGrafico = [];
        foreach ($faturamentoPorDia as $linhaDia) {
            $labelsGrafico[] = (string)$linhaDia['DIA'];
            $valoresGrafico[] = (float)$linhaDia['VALOR'];
        }

        $labelsVendedores = [];
        $valoresVendedores = [];
        foreach ($topVendedores as $linhaVend) {
            $labelsVendedores[] = (string)$linhaVend['VENDEDOR'];
            $valoresVendedores[] = (float)$linhaVend['VALOR'];
        }

        $telhasDash = $this->montaDadosTelhasProjecao($dataInicialBr, $dataFinalBr, $centrosCustoSelecionados);

        return array_merge([
            'dataIni' => $dataInicialBr,
            'dataFim' => $dataFinalBr,
            'kpis' => $kpis,
            'total' => $total,
            'porSituacao' => $porSituacao,
            'topVendedores' => $topVendedores,
            'totaisDet' => $totaisDet,
            'chartLabels' => json_encode($labelsGrafico, JSON_UNESCAPED_UNICODE),
            'chartValores' => json_encode($valoresGrafico, JSON_UNESCAPED_UNICODE),
            'chartVendLabels' => json_encode($labelsVendedores, JSON_UNESCAPED_UNICODE),
            'chartVendValores' => json_encode($valoresVendedores, JSON_UNESCAPED_UNICODE),
        ], $telhasDash);
    }

    /**
     * IDs numéricos de centro de custo + WHERE no formato do telhas + CSV para AMB_FERIADO.
     *
     * @param array<int,string> $centrosCustoSelecionados
     * @return array{w:array{where:string,wherec:string,wheres:string,wherel:string},csvFeriados:string}
     */
    private function telhasMontaContextoFiltro(array $centrosCustoSelecionados): array
    {
        $base = $centrosCustoSelecionados === [] ? $this->centroCustoIdsCombo : $centrosCustoSelecionados;
        $idsCc = [];
        foreach ($base as $id) {
            if ($id !== self::CENTRO_CUSTO_TODOS && (int)$id > 0) {
                $idsCc[] = (int)$id;
            }
        }
        $idsCc = array_values(array_unique($idsCc));

        if ($idsCc === []) {
            $w = ['where' => '(1=1)', 'wherec' => '(1=1)', 'wheres' => '(1=1)', 'wherel' => '(1=1)'];
        } else {
            $p = $pL = $pC = $pS = [];
            foreach ($idsCc as $cc) {
                $p[] = "( centrocusto = {$cc} )";
                $pL[] = "( l.centrocusto = {$cc} )";
                $pC[] = "( p.ccusto = {$cc} )";
                $pS[] = "( ccusto = {$cc} )";
            }
            $g = ' or ';
            $w = [
                'where' => '(' . implode($g, $p) . ')',
                'wherec' => '(' . implode($g, $pC) . ')',
                'wheres' => '(' . implode($g, $pS) . ')',
                'wherel' => '(' . implode($g, $pL) . ')',
            ];
        }

        if ($idsCc !== []) {
            $csvFeriados = implode(',', $idsCc);
        } else {
            $comboIds = [];
            foreach ($this->centroCustoIdsCombo as $id) {
                if ($id !== self::CENTRO_CUSTO_TODOS && (int)$id > 0) {
                    $comboIds[] = (int)$id;
                }
            }
            $csvFeriados = $comboIds !== [] ? implode(',', $comboIds) : ((int)$this->m_empresacentrocusto > 0 ? (string)(int)$this->m_empresacentrocusto : '0');
        }

        return ['w' => $w, 'csvFeriados' => $csvFeriados];
    }

    /** Dias úteis no intervalo (fim de semana e feriados AMB_FERIADO excluídos), mesmo critério do telhas. */
    private function telhasDiasUteisIntervalo(string $iniYmd, string $fimYmd, string $csvCentros): int
    {
        $begin = strtotime($iniYmd);
        $end = strtotime($fimYmd);
        if ($begin === false || $end === false || $begin > $end || $csvCentros === '' || $csvCentros === '0') {
            return 0;
        }
        $lista = array_filter(array_map('intval', explode(',', $csvCentros)), static fn($v) => $v > 0);
        if ($lista === []) {
            return 0;
        }
        $inList = "'" . implode("','", $lista) . "'";
        $sql = 'SELECT DATE_FORMAT(DATAFERIADO, \'%d/%m\') AS feriados FROM AMB_FERIADO '
            . "WHERE DATAFERIADO >= '{$iniYmd}' AND DATAFERIADO <= '{$fimYmd}' AND CENTROCUSTO IN ({$inList}) ORDER BY DATAFERIADO";
        $banco = new c_banco();
        $banco->exec_sql_lower_case($sql);
        $banco->close_connection();
        $rows = is_array($banco->resultado) ? $banco->resultado : [];
        $feriados = [];
        foreach ($rows as $row) {
            if (isset($row['feriados'])) {
                $feriados[] = $row['feriados'];
            }
        }

        $noDays = $weekends = $contagemFeriados = 0;
        for ($t = $begin; $t <= $end; $t += 86400) {
            $noDays++;
            if (in_array(date('d/m', $t), $feriados, true)) {
                $contagemFeriados++;
            }
            if ((int)date('N', $t) > 6) {
                $weekends++;
            }
        }
        return $noDays - $weekends - $contagemFeriados;
    }

    /**
     * @param array<int,string> $centrosCustoSelecionados
     * @return array{forecast:mixed,projecao:mixed,metas:mixed,pedDashTelhasAviso:string}
     */
    private function montaDadosTelhasProjecao(string $dataIniBr, string $dataFimBr, array $centrosCustoSelecionados): array
    {
        $vazio = ['forecast' => '', 'projecao' => '', 'metas' => '', 'pedDashTelhasAviso' => ''];

        $dataIni = c_date::convertDateTxt($dataIniBr);
        $dataFim = c_date::convertDateTxt($dataFimBr);
        if (!is_string($dataIni) || !is_string($dataFim) || $dataIni === '' || $dataFim === '') {
            return $vazio;
        }

        $hoje = date('Y-m-d');
        if ($dataFim > $hoje) {
            $dataFim = $hoje;
        }

        $ctx = $this->telhasMontaContextoFiltro($centrosCustoSelecionados);
        $w = $ctx['w'];
        $csvFeriados = $ctx['csvFeriados'];

        $dtIni = new DateTime($dataIni);
        $primeiroDiaMes = $dtIni->format('Y-m-01');
        $ultimoDiaMes = $dtIni->format('Y-m-t');

        $qtdDiasUteis = $this->telhasDiasUteisIntervalo($primeiroDiaMes, $ultimoDiaMes, $csvFeriados);
        $diasPassados = max(0, $this->telhasDiasUteisIntervalo($dataIni, $hoje, $csvFeriados));

        $di = explode('-', $dataIni);
        $df = explode('-', $dataFim);
        if (isset($di[0], $di[1], $df[0], $df[1]) && $di[0] === $df[0] && $di[1] === $df[1]) {
            $sql = "SELECT DISTINCT TOTALDIAMES FROM FAT_META_MENSAL WHERE ANO = '{$df[0]}' AND MES = '{$df[1]}' AND {$w['wheres']}";
            $consulta = new c_banco();
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $diasMeta = $consulta->resultado;
            if (is_array($diasMeta) && $diasMeta !== [] && isset($diasMeta[0]['TOTALDIAMES'])
                && $diasMeta[0]['TOTALDIAMES'] !== null && $diasMeta[0]['TOTALDIAMES'] !== '') {
                $qtdDiasUteis = (int)$diasMeta[0]['TOTALDIAMES'];
            }
        }

        if ($qtdDiasUteis <= 0) {
            return array_merge($vazio, [
                'pedDashTelhasAviso' => 'Metas (dias úteis do mês) não localizadas em FAT_META_MENSAL para o filtro '
                    . 'ou calendário inválido. Forecast, projeção e metas não foram calculados.',
            ]);
        }

        if ($diasPassados < 1) {
            return array_merge($vazio, [
                'pedDashTelhasAviso' => 'Ainda não há dias úteis decorridos no período (em relação à data atual) '
                    . 'para calcular forecast e projeção como no dashboard de telhas.',
            ]);
        }

        $mes = date('m', strtotime($dataFim));
        $ano = date('Y', strtotime($dataFim));

        $telhasSql = new c_pedidoVendaTelhasDash();
        $forecast = $telhasSql->forecast($dataIni, $dataFim, $qtdDiasUteis, $diasPassados, $w['wherec'], $mes, $w['wheres'], $ano);
        $projecao = $telhasSql->projecao($dataIni, $dataFim, $qtdDiasUteis, $diasPassados, $w['wherec']);
        $metas = $telhasSql->metas($dataIni, $dataFim, $w['wherec']);

        $ok = static fn($x) => is_array($x) && $x !== [];

        return [
            'forecast' => $ok($forecast) ? $forecast : '',
            'projecao' => $ok($projecao) ? $projecao : '',
            'metas' => $ok($metas) ? $metas : '',
            'pedDashTelhasAviso' => '',
        ];
    }

    /** @return array<int,string> */
    private function centrosCustoSelecionados(): array
    {
        $csvCentroCusto = trim($this->getCentroCustoFiltro());
        if ($csvCentroCusto === '') {
            return [];
        }
        $valoresSelecionados = array_filter(
            array_map('trim', explode(',', $csvCentroCusto)),
            fn($valor) => $valor !== ''
        );

        // Se o usuário selecionou "Todos", não aplica filtro de centro de custo
        if (in_array(self::CENTRO_CUSTO_TODOS, $valoresSelecionados, true)) {
            return [];
        }

        $idsCentroCusto = [];
        foreach ($valoresSelecionados as $valorSelecionado) {
            $idsCentroCusto[] = (string)(int)$valorSelecionado;
        }
        return array_values(array_unique($idsCentroCusto));
    }

    /**
     * Monta `IN (...)` com parâmetros nomeados para centro de custo.
     *
     * @param array<int,string> $centrosIds Lista de IDs
     * @return array{where:string,params:array<string,int>}
     */
    private function montaFiltroCentroCustoSql(array $centrosIds): array
    {
        $placeholdersCentroCusto = [];
        $paramsCentroCusto = [];

        foreach (array_values($centrosIds) as $indice => $centroCustoId) {
            if ($centroCustoId !== self::CENTRO_CUSTO_TODOS) {
                $paramCentroCusto = ':cc' . $indice;
                $placeholdersCentroCusto[] = $paramCentroCusto;
                $paramsCentroCusto[$paramCentroCusto] = (int)$centroCustoId;
            }
        }

        if (empty($placeholdersCentroCusto)) {
            return ['where' => '', 'params' => []];
        }

        return [
            'where' => ' AND P.CCUSTO IN (' . implode(',', $placeholdersCentroCusto) . ')',
            'params' => $paramsCentroCusto,
        ];
    }

    /**
     * KPI: pedidos faturados (sit. 3, 6, 9 e 13), valor faturado e ticket médio.
     *
     * @return array{numPedidosFat:int,valorFat:float,ticketMedio:float}
     */
    private function consultaKpisFaturado(string $whereCentro, array $params): array
    {
        $db = new c_banco_pdo();
        $sqlConsulta = "SELECT
                    COUNT(*) AS QTD,
                    COALESCE(SUM(P.TOTAL), 0) AS VALOR
                FROM FAT_PEDIDO P
                WHERE P.EMISSAO BETWEEN :dataIni AND :dataFim
                  AND P.SITUACAO IN (3,6,9,13)
                  {$whereCentro}";

        $db->prepare($sqlConsulta);
        foreach ($params as $parametro => $valorParametro) {
            $db->bindValue($parametro, $valorParametro, is_int($valorParametro) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $db->execute();
        $linha = $db->fetch() ?: ['QTD' => 0, 'VALOR' => 0];
        $db->close();

        $quantidade = (int)$linha['QTD'];
        $valorTotal = (float)$linha['VALOR'];
        $ticketMedio = $quantidade > 0 ? ($valorTotal / $quantidade) : 0.0;

        return [
            'numPedidosFat' => $quantidade,
            'valorFat' => $valorTotal,
            'ticketMedio' => $ticketMedio,
        ];
    }

    /**
     * Resumo operacional do período (considera faturados: sit. 3, 6, 9 e 13).
     *
     * Campos exibidos no template:
     * - VALORVENDA: soma de P.TOTAL
     * - LUCROBRUTO: soma de P.LUCROBRUTO (campo já calculado no pedido)
     * - CUSTOTOTAL: soma de P.CUSTOTOTAL (campo já calculado no pedido)
     * - MARKUP: calculado pelo período em % (quando existir custo)
     *
     * @return array<int,array{VALORVENDA:float,LUCROBRUTO:float,CUSTOTOTAL:float,MARKUP:float}>
     */
    private function consultaResumoOperacional(string $whereCentro, array $params): array
    {
        $db = new c_banco_pdo();
        $sqlConsulta = "SELECT
                    COALESCE(SUM(P.TOTAL), 0) AS VALORVENDA,
                    COALESCE(SUM(P.LUCROBRUTO), 0) AS LUCROBRUTO,
                    COALESCE(SUM(P.CUSTOTOTAL), 0) AS CUSTOTOTAL,
                    CASE
                        WHEN COALESCE(SUM(P.CUSTOTOTAL), 0) > 0
                        THEN ((COALESCE(SUM(P.TOTAL), 0) / SUM(P.CUSTOTOTAL)) - 1) * 100
                        ELSE 0
                    END AS MARKUP
                FROM FAT_PEDIDO P
                WHERE P.EMISSAO BETWEEN :dataIni AND :dataFim
                  AND P.SITUACAO IN (3,6,9,13)
                  {$whereCentro}";

        $db->prepare($sqlConsulta);
        foreach ($params as $parametro => $valorParametro) {
            $db->bindValue($parametro, $valorParametro, is_int($valorParametro) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $db->execute();
        $linha = $db->fetch() ?: ['VALORVENDA' => 0, 'LUCROBRUTO' => 0, 'CUSTOTOTAL' => 0, 'MARKUP' => 0];
        $db->close();

        return [[
            'VALORVENDA' => (float)$linha['VALORVENDA'],
            'LUCROBRUTO' => (float)$linha['LUCROBRUTO'],
            'CUSTOTOTAL' => (float)$linha['CUSTOTOTAL'],
            'MARKUP' => (float)$linha['MARKUP'],
        ]];
    }

    /**
     * Lista pedidos por situação (todas no período).
     * Retorna quantidade e valor total por situação, com descrição via `AMB_DDM`
     * (mesma fonte usada no Pedido PS: ALIAS='FAT_MENU' / CAMPO='SITUACAOPEDIDO').
     *
     * @return array<int,array{SITUACAO:int,DESCRICAOSIT:string,QTD:int,VALOR:float}>
     */
    private function consultaPedidosPorSituacao(string $whereCentro, array $params): array
    {
        $db = new c_banco_pdo();
        $sqlConsulta = "SELECT
                    P.SITUACAO AS SITUACAO,
                    COALESCE(NULLIF(TRIM(D.PADRAO), ''), 'Sem descrição') AS DESCRICAOSIT,
                    COUNT(P.SITUACAO) AS QTD,
                    COALESCE(SUM(P.TOTAL), 0) AS VALOR
                FROM FAT_PEDIDO P
                LEFT JOIN AMB_DDM D ON ((D.TIPO = P.SITUACAO) AND (D.ALIAS = 'FAT_MENU') AND (D.CAMPO = 'SITUACAOPEDIDO'))
                WHERE P.EMISSAO BETWEEN :dataIni AND :dataFim
                  {$whereCentro}
                GROUP BY P.SITUACAO, D.PADRAO
                ORDER BY QTD DESC, VALOR DESC";

        $db->prepare($sqlConsulta);
        foreach ($params as $parametro => $valorParametro) {
            $db->bindValue($parametro, $valorParametro, is_int($valorParametro) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $db->execute();
        $linhas = $db->fetchAll();
        $db->close();

        $resultado = [];
        foreach ($linhas as $linha) {
            $resultado[] = [
                'SITUACAO' => (int)$linha['SITUACAO'],
                'DESCRICAOSIT' => (string)$linha['DESCRICAOSIT'],
                'QTD' => (int)$linha['QTD'],
                'VALOR' => (float)$linha['VALOR'],
            ];
        }
        return $resultado;
    }

    /**
     * Top vendedores (faturado) pelo usuário de faturamento (`USRFATURA`).
     *
     * @return array<int,array{VENDEDOR:string,QTD:int,VALOR:float}>
     */
    private function consultaTopVendedoresFaturado(string $whereCentro, array $params): array
    {
        $db = new c_banco_pdo();
        $sqlConsulta = "SELECT
                    COALESCE(U.NOME, CONCAT('Usuário ', P.USRFATURA)) AS VENDEDOR,
                    COUNT(*) AS QTD,
                    COALESCE(SUM(P.TOTAL), 0) AS VALOR
                FROM FAT_PEDIDO P
                LEFT JOIN AMB_USUARIO U ON (U.USUARIO = P.USRFATURA)
                WHERE P.EMISSAO BETWEEN :dataIni AND :dataFim
                  AND P.SITUACAO IN (3,6,9,13)
                  {$whereCentro}
                GROUP BY P.USRFATURA, U.NOME
                ORDER BY VALOR DESC";

        $db->prepare($sqlConsulta);
        foreach ($params as $parametro => $valorParametro) {
            $db->bindValue($parametro, $valorParametro, is_int($valorParametro) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $db->execute();
        $linhas = $db->fetchAll();
        $db->close();

        $resultado = [];
        foreach ($linhas as $linha) {
            $resultado[] = [
                'VENDEDOR' => (string)$linha['VENDEDOR'],
                'QTD' => (int)$linha['QTD'],
                'VALOR' => (float)$linha['VALOR'],
            ];
        }
        return $resultado;
    }

    /**
     * Margens por vendedor (faturado):
     * - MARKUP: calculado em % sobre o período por vendedor (quando existir custo)
     * - MARGEMBRUTA: lucro bruto % (lucro/valor*100)
     *
     * @return array<int,array{VENDEDOR:string,MARKUP:float,MARGEMBRUTA:float}>
     */
    private function consultaMargemPorVendedorFaturado(string $whereCentro, array $params): array
    {
        $db = new c_banco_pdo();
        $sqlConsulta = "SELECT
                    COALESCE(U.NOME, CONCAT('Usuário ', P.USRFATURA)) AS VENDEDOR,
                    CASE
                        WHEN COALESCE(SUM(P.CUSTOTOTAL), 0) > 0
                        THEN ((COALESCE(SUM(P.TOTAL), 0) / SUM(P.CUSTOTOTAL)) - 1) * 100
                        ELSE 0
                    END AS MARKUP,
                    CASE
                        WHEN COALESCE(SUM(P.TOTAL), 0) > 0
                        THEN (COALESCE(SUM(P.LUCROBRUTO), 0) / SUM(P.TOTAL)) * 100
                        ELSE 0
                    END AS MARGEMBRUTA
                FROM FAT_PEDIDO P
                LEFT JOIN AMB_USUARIO U ON (U.USUARIO = P.USRFATURA)
                WHERE P.EMISSAO BETWEEN :dataIni AND :dataFim
                  AND P.SITUACAO IN (3,6,9,13)
                  {$whereCentro}
                GROUP BY P.USRFATURA, U.NOME
                ORDER BY MARGEMBRUTA DESC";

        $db->prepare($sqlConsulta);
        foreach ($params as $parametro => $valorParametro) {
            $db->bindValue($parametro, $valorParametro, is_int($valorParametro) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $db->execute();
        $linhas = $db->fetchAll();
        $db->close();

        $resultado = [];
        foreach ($linhas as $linha) {
            $resultado[] = [
                'VENDEDOR' => (string)$linha['VENDEDOR'],
                'MARKUP' => (float)$linha['MARKUP'],
                'MARGEMBRUTA' => (float)$linha['MARGEMBRUTA'],
            ];
        }
        return $resultado;
    }

    /**
     * Série por dia (faturado) para gráfico.
     *
     * @return array<int,array{DIA:string,VALOR:float}>
     */
    private function consultaFaturamentoPorDia(string $whereCentro, array $params): array
    {
        $db = new c_banco_pdo();
        $sqlConsulta = "SELECT
                    DATE_FORMAT(P.EMISSAO, '%d/%m') AS DIA,
                    COALESCE(SUM(P.TOTAL), 0) AS VALOR
                FROM FAT_PEDIDO P
                WHERE P.EMISSAO BETWEEN :dataIni AND :dataFim
                  AND P.SITUACAO IN (3,6,9,13)
                  {$whereCentro}
                GROUP BY DATE(P.EMISSAO)
                ORDER BY DATE(P.EMISSAO)";

        $db->prepare($sqlConsulta);
        foreach ($params as $parametro => $valorParametro) {
            $db->bindValue($parametro, $valorParametro, is_int($valorParametro) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $db->execute();
        $linhas = $db->fetchAll();
        $db->close();

        $resultado = [];
        foreach ($linhas as $linha) {
            $resultado[] = [
                'DIA' => (string)$linha['DIA'],
                'VALOR' => (float)$linha['VALOR'],
            ];
        }
        return $resultado;
    }
}

