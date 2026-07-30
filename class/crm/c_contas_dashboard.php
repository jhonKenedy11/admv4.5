<?php
/**
 * @package   admv4.5
 * @name      c_contas_dashboard
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
 * Classe de regras/consultas do Dashboard de Contas (CRM).
 *
 * Baseado no padrão dos dashboards novos (ex.: estoque):
 * - filtros (período / centro de custo / responsável)
 * - combos (centro de custo / responsável)
 * - consultas agregadas via PDO
 */
class c_contas_dashboard extends c_user
{
    private const CENTRO_CUSTO_TODOS = 'ALL';

    private ?string $dataIni = null;            // dd/mm/YYYY
    private ?string $dataFim = null;            // dd/mm/YYYY
    private ?string $centroCustoFiltro = null;  // CSV IDs
    private ?string $responsavelFiltro = null;  // CSV IDs (na prática 1)

    /** @var array<int,string> */
    private array $centroCustoIdsCombo = [];
    /** @var array<int,string> */
    private array $centroCustoNamesCombo = [];

    /** @var array<int,string> */
    private array $responsavelIdsCombo = [];
    /** @var array<int,string> */
    private array $responsavelNamesCombo = [];

    /**
     * @return array{0:string,1:string} [dtIni, dtFim] em DATETIME (YYYY-mm-dd HH:ii:ss)
     */
    private function periodoDateTime(): array
    {
        $dtIni = c_date::convertDateBdSh($this->getDataIni(), $this->m_banco) . ' 00:00:00';
        $dtFim = c_date::convertDateBdSh($this->getDataFim(), $this->m_banco) . ' 23:59:59';
        return [$dtIni, $dtFim];
    }

    /** @return array{':dtIni':string,':dtFim':string} */
    private function periodoParams(): array
    {
        [$dtIni, $dtFim] = $this->periodoDateTime();
        return [':dtIni' => $dtIni, ':dtFim' => $dtFim];
    }

    /**
     * Bind genérico de parâmetros (int/string) em uma statement do c_banco_pdo.
     * @param array<string,int|string> $params
     */
    private function bindParams(c_banco_pdo $db, array $params): void
    {
        foreach ($params as $k => $v) {
            $db->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
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

    public function setResponsavelFiltro($csvResponsavel): void
    {
        $valor = is_string($csvResponsavel) ? trim($csvResponsavel) : '';
        $this->responsavelFiltro = $valor !== '' ? $valor : null;
    }

    public function getResponsavelFiltro(): string
    {
        return $this->responsavelFiltro ?? '';
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

    /** @return array<int,string> */
    public function getResponsavelIdsCombo(): array
    {
        return $this->responsavelIdsCombo;
    }

    /** @return array<int,string> */
    public function getResponsavelNamesCombo(): array
    {
        return $this->responsavelNamesCombo;
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

    /** @return array<int,int> */
    private function responsaveisSelecionados(): array
    {
        $csv = trim($this->getResponsavelFiltro());
        if ($csv === '') {
            return [];
        }

        $ids = array_values(array_filter(array_map('trim', explode(',', $csv)), fn($v) => $v !== ''));
        $idsInt = array_values(array_filter(array_map('intval', $ids), fn($v) => $v > 0));
        return array_values(array_unique($idsInt));
    }

    /**
     * Monta trecho SQL de IN() com bind params nomeados.
     * Retorna [sqlWhere, params].
     *
     * @param string $coluna Ex.: "C.CENTROCUSTO"
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

    public function comboResponsavelDashboard(): void
    {
        $verTodos = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');

        $db = new c_banco_pdo();
        if ($verTodos) {
            $sql = "SELECT USUARIO AS ID, NOMEREDUZIDO AS DESCRICAO
                    FROM AMB_USUARIO
                    WHERE SITUACAO = 'A'
                    ORDER BY NOMEREDUZIDO";
            $db->prepare($sql);
        } else {
            $sql = "SELECT USUARIO AS ID, NOMEREDUZIDO AS DESCRICAO
                    FROM AMB_USUARIO
                    WHERE SITUACAO = 'A' AND USUARIO = :usr
                    ORDER BY NOMEREDUZIDO";
            $db->prepare($sql);
            $db->bindValue(':usr', (int)$this->m_userid, PDO::PARAM_INT);
        }
        $db->execute();
        $linhas = $db->fetchAll();
        $db->close();

        $ids = [''];
        $nomes = ['Todos'];
        foreach ($linhas as $linha) {
            $ids[] = (string)$linha['ID'];
            $nomes[] = (string)$linha['DESCRICAO'];
        }

        $this->responsavelIdsCombo = $ids;
        $this->responsavelNamesCombo = $nomes;
    }

    /** @return array<string,mixed> */
    public function dadosDashboardMostra(): array
    {
        $ccSelecionados = $this->centrosCustoSelecionados();
        $respSelecionados = $this->responsaveisSelecionados();

        [$whereCcConta, $paramsCcConta] = $this->montaFiltroInSql('C.CENTROCUSTO', $ccSelecionados, 'cc');
        [$whereRespConta, $paramsRespConta] = $this->montaFiltroInSql('C.REPRESENTANTE', $respSelecionados, 'rp');

        [$whereRespAcomp, $paramsRespAcomp] = $this->montaFiltroInSql('A.USRVENDEDOR', $respSelecionados, 'ra');
        [$whereCentroAcomp, $paramsCentroAcomp] = $this->montaFiltroInSql('C.CENTROCUSTO', $ccSelecionados, 'ca');

        $paramsPeriodo = $this->periodoParams();

        $whereConta = $whereCcConta . $whereRespConta;
        $paramsConta = $paramsPeriodo + $paramsCcConta + $paramsRespConta;

        $paramsAcomp = $paramsPeriodo + $paramsRespAcomp;

        $kpis = $this->consultaKpis(
            $whereConta,
            $paramsConta,
            $whereRespAcomp,
            $paramsAcomp,
            $whereCentroAcomp,
            $paramsCentroAcomp
        );

        $novasContas = $this->consultaNovasContasPeriodo($whereConta, $paramsConta);

        $paramsCentroPeriodo = $paramsPeriodo + $paramsCcConta;
        $paramsCentroSemPeriodo = $paramsCcConta;
        $paramsRespCentroPeriodo = $paramsPeriodo + $paramsCcConta + $paramsRespAcomp;
        $paramsRespCentroSemPeriodo = $paramsCcConta + $paramsRespAcomp;

        $acompRecentes = $this->consultaAcompanhamentosPeriodo($whereCcConta, $paramsCentroPeriodo, $whereRespAcomp, $paramsRespCentroPeriodo);
        $proximos = $this->consultaProximosContatos($whereCcConta, $paramsCentroSemPeriodo, $whereRespAcomp, $paramsRespCentroSemPeriodo);

        $serieAcompDia = $this->consultaAcompanhamentosPorDia($whereCcConta, $paramsCentroPeriodo, $whereRespAcomp, $paramsRespCentroPeriodo);

        $pieAtividades = $this->consultaAcompanhamentosPorAtividade($whereCcConta, $paramsCentroPeriodo, $whereRespAcomp, $paramsRespCentroPeriodo, 8);

        return [
            'dataIni' => $this->getDataIni(),
            'dataFim' => $this->getDataFim(),
            'kpis' => $kpis,
            'novasContas' => $novasContas,
            'acompRecentes' => $acompRecentes,
            'proximosContatos' => $proximos,
            'chartAcompLabels' => json_encode(array_column($serieAcompDia, 'DIA'), JSON_UNESCAPED_UNICODE),
            'chartAcompValores' => json_encode(array_map('intval', array_column($serieAcompDia, 'QTD')), JSON_UNESCAPED_UNICODE),
            'pieLabels' => json_encode(array_column($pieAtividades, 'LABEL'), JSON_UNESCAPED_UNICODE),
            'pieValores' => json_encode(array_map('intval', array_column($pieAtividades, 'QTD')), JSON_UNESCAPED_UNICODE),
        ];
    }

    /** @return array<string,int|float|string> */
    private function consultaKpis(
        string $whereConta,
        array $paramsConta,
        string $whereAcomp,
        array $paramsAcomp,
        string $whereCentroAcomp,
        array $paramsCentroAcomp
    ): array
    {
        // Otimização: em vez de vários subselects, usa agregações em 2 consultas (cliente e acompanhamento).
        $db = new c_banco_pdo();

        $sqlClientes = "SELECT
                            COUNT(*) AS CONTAS_TOTAL,
                            SUM(CASE WHEN COALESCE(CL.BLOQUEADO, 'N') = 'S' THEN 1 ELSE 0 END) AS CONTAS_BLOQUEADAS,
                            SUM(CASE WHEN C.DATEINSERT BETWEEN :dtIni AND :dtFim THEN 1 ELSE 0 END) AS CONTAS_NOVAS
                        FROM FIN_CLIENTE C
                        LEFT JOIN FIN_CLASSE CL ON (CL.CLASSE = C.CLASSE)
                        WHERE 1=1 {$whereConta}";
        $db->prepare($sqlClientes);
        $this->bindParams($db, $paramsConta);
        $db->execute();
        $rowClientes = $db->fetch() ?: [];

        $sqlAcomp = "SELECT
                         SUM(CASE WHEN A.DATA BETWEEN :dtIni AND :dtFim THEN 1 ELSE 0 END) AS ACOMPANHAMENTOS_PERIODO,
                         SUM(CASE WHEN A.LIGARDIA IS NOT NULL AND A.LIGARDIA < NOW() THEN 1 ELSE 0 END) AS CONTATOS_ATRASADOS
                     FROM FIN_CLIENTE_ACOMP A
                     INNER JOIN FIN_CLIENTE C ON (C.CLIENTE = A.PESSOA)
                     WHERE (
                            A.DATA BETWEEN :dtIni AND :dtFim
                            OR (A.LIGARDIA IS NOT NULL AND A.LIGARDIA < NOW())
                           )
                       {$whereAcomp}
                       {$whereCentroAcomp}";
        $db->prepare($sqlAcomp);
        $this->bindParams($db, $paramsAcomp + $paramsCentroAcomp);
        $db->execute();
        $rowAcomp = $db->fetch() ?: [];

        $db->close();

        return [
            'contasTotal' => (int)($rowClientes['CONTAS_TOTAL'] ?? 0),
            'contasBloqueadas' => (int)($rowClientes['CONTAS_BLOQUEADAS'] ?? 0),
            'contasNovas' => (int)($rowClientes['CONTAS_NOVAS'] ?? 0),
            'acompPeriodo' => (int)($rowAcomp['ACOMPANHAMENTOS_PERIODO'] ?? 0),
            'contatosAtrasados' => (int)($rowAcomp['CONTATOS_ATRASADOS'] ?? 0),
        ];
    }

    /** @return array<int,array<string,mixed>> */
    private function consultaNovasContasPeriodo(string $whereConta, array $paramsConta): array
    {
        $db = new c_banco_pdo();
        $sql = "SELECT
                    C.CLIENTE,
                    C.NOMEREDUZIDO,
                    C.NOME,
                    C.CIDADE,
                    C.UF,
                    C.CENTROCUSTO,
                    CC.DESCRICAO AS CENTROCUSTO_DESC,
                    C.REPRESENTANTE,
                    U.NOMEREDUZIDO AS RESPONSAVEL,
                    C.DATEINSERT
                FROM FIN_CLIENTE C
                LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = C.CENTROCUSTO)
                LEFT JOIN AMB_USUARIO U ON (U.USUARIO = C.REPRESENTANTE)
                WHERE C.DATEINSERT BETWEEN :dtIni AND :dtFim
                  {$whereConta}
                ORDER BY C.DATEINSERT DESC, C.CLIENTE DESC";
        $db->prepare($sql);
        $this->bindParams($db, $paramsConta);
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();
        return $rows ?: [];
    }

    /** @return array<int,array<string,mixed>> */
    private function consultaAcompanhamentosPeriodo(string $whereCentro, array $paramsCentro, string $whereResp, array $paramsAcomp): array
    {
        $db = new c_banco_pdo();
        $sql = "SELECT
                    A.ID,
                    A.DATA,
                    A.LIGARDIA,
                    A.RESULTADO,
                    A.PEDIDO_ID,
                    A.PESSOA AS CLIENTE,
                    C.NOMEREDUZIDO,
                    C.NOME,
                    C.CENTROCUSTO,
                    CC.DESCRICAO AS CENTROCUSTO_DESC,
                    A.ATIVIDADE,
                    FA.DESCRICAO AS ATIVIDADE_DESC,
                    A.USRVENDEDOR,
                    U.NOMEREDUZIDO AS RESPONSAVEL
                FROM FIN_CLIENTE_ACOMP A
                INNER JOIN FIN_CLIENTE C ON (C.CLIENTE = A.PESSOA)
                LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = C.CENTROCUSTO)
                LEFT JOIN FAT_ATIVIDADE_ACOMP FA ON (FA.ATIVIDADE = A.ATIVIDADE)
                LEFT JOIN AMB_USUARIO U ON (U.USUARIO = A.USRVENDEDOR)
                WHERE A.DATA BETWEEN :dtIni AND :dtFim
                  {$whereResp}
                  {$whereCentro}
                ORDER BY A.DATA DESC, A.ID DESC";
        $db->prepare($sql);
        $this->bindParams($db, $paramsAcomp + $paramsCentro);
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();
        return $rows ?: [];
    }

    /** @return array<int,array<string,mixed>> */
    private function consultaProximosContatos(string $whereCentro, array $paramsCentro, string $whereResp, array $paramsResp): array
    {
        $db = new c_banco_pdo();
        $sql = "SELECT
                    A.ID,
                    A.LIGARDIA,
                    A.ATIVIDADE,
                    FA.DESCRICAO AS ATIVIDADE_DESC,
                    A.PEDIDO_ID,
                    A.PESSOA AS CLIENTE,
                    C.NOMEREDUZIDO,
                    C.NOME,
                    C.CENTROCUSTO,
                    CC.DESCRICAO AS CENTROCUSTO_DESC,
                    A.USRVENDEDOR,
                    U.NOMEREDUZIDO AS RESPONSAVEL
                FROM FIN_CLIENTE_ACOMP A
                INNER JOIN FIN_CLIENTE C ON (C.CLIENTE = A.PESSOA)
                LEFT JOIN FIN_CENTRO_CUSTO CC ON (CC.CENTROCUSTO = C.CENTROCUSTO)
                LEFT JOIN FAT_ATIVIDADE_ACOMP FA ON (FA.ATIVIDADE = A.ATIVIDADE)
                LEFT JOIN AMB_USUARIO U ON (U.USUARIO = A.USRVENDEDOR)
                WHERE A.LIGARDIA IS NOT NULL
                  {$whereResp}
                  {$whereCentro}
                ORDER BY A.LIGARDIA ASC, A.ID ASC";
        $db->prepare($sql);
        $this->bindParams($db, $paramsResp + $paramsCentro);
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();
        return $rows ?: [];
    }

    /** @return array<int,array{DIA:string,QTD:int}> */
    private function consultaAcompanhamentosPorDia(string $whereCentro, array $paramsCentro, string $whereResp, array $paramsAcomp): array
    {
        $db = new c_banco_pdo();
        $sql = "SELECT DATE(A.DATA) AS DIA, COUNT(*) AS QTD
                FROM FIN_CLIENTE_ACOMP A
                INNER JOIN FIN_CLIENTE C ON (C.CLIENTE = A.PESSOA)
                WHERE A.DATA BETWEEN :dtIni AND :dtFim
                  {$whereResp}
                  {$whereCentro}
                GROUP BY DATE(A.DATA)
                ORDER BY DATE(A.DATA)";
        $db->prepare($sql);
        $this->bindParams($db, $paramsAcomp + $paramsCentro);
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();
        return $rows ?: [];
    }

    /**
     * Retorna pie data (topN + Outros).
     * @return array<int,array{LABEL:string,QTD:int}>
     */
    private function consultaAcompanhamentosPorAtividade(string $whereCentro, array $paramsCentro, string $whereResp, array $paramsAcomp, int $topN): array
    {
        $db = new c_banco_pdo();
        $sql = "SELECT
                    COALESCE(FA.DESCRICAO, CONCAT('Atividade ', A.ATIVIDADE)) AS LABEL,
                    COUNT(*) AS QTD
                FROM FIN_CLIENTE_ACOMP A
                INNER JOIN FIN_CLIENTE C ON (C.CLIENTE = A.PESSOA)
                LEFT JOIN FAT_ATIVIDADE_ACOMP FA ON (FA.ATIVIDADE = A.ATIVIDADE)
                WHERE A.DATA BETWEEN :dtIni AND :dtFim
                  {$whereResp}
                  {$whereCentro}
                GROUP BY A.ATIVIDADE, FA.DESCRICAO
                ORDER BY QTD DESC
                LIMIT " . ((int)$topN + 1);
        $db->prepare($sql);
        $this->bindParams($db, $paramsAcomp + $paramsCentro);
        $db->execute();
        $rows = $db->fetchAll();
        $db->close();

        $rows = $rows ?: [];
        if (count($rows) <= $topN) {
            return array_map(fn($r) => ['LABEL' => (string)$r['LABEL'], 'QTD' => (int)$r['QTD']], $rows);
        }

        $top = array_slice($rows, 0, $topN);
        $rest = array_slice($rows, $topN);
        $outros = 0;
        foreach ($rest as $r) {
            $outros += (int)$r['QTD'];
        }

        $final = array_map(fn($r) => ['LABEL' => (string)$r['LABEL'], 'QTD' => (int)$r['QTD']], $top);
        if ($outros > 0) {
            $final[] = ['LABEL' => 'Outros', 'QTD' => $outros];
        }
        return $final;
    }
}

