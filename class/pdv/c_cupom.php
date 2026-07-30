<?php
/**
 * @package   admv4.5
 * @name      c_cupom
 * @description Cupom fiscal PDV: estado da tela (get/set) e consultas PDO do módulo
 */

require_once __DIR__ . '/../ped/c_pedido_venda.php';
require_once __DIR__ . '/../../bib/c_database_pdo.php';
require_once __DIR__ . '/../est/c_produto_estoque.php';
require_once __DIR__ . '/../est/c_produto.php';
require_once __DIR__ . '/../est/c_nota_fiscal.php';
require_once __DIR__ . '/../ped/c_cotacao.php';
// c_lancamento: carregado pelo form (p_cupom); não incluir aqui — c_nota_fiscal redefine $dir via forms/blt

class c_cupom extends c_pedidoVenda
{
    /** @var c_banco_pdo|null */
    private $pdo;

    /** Filtros da consulta de cupons (lista PDV). */
    private string $dataIni = '';
    private string $dataFim = '';
    private string $idFiltro = '';

    public function setDataIni(string $dataIni): void
    {
        $this->dataIni = trim($dataIni);
    }

    public function getDataIni(): string
    {
        return $this->dataIni;
    }

    public function setDataFim(string $dataFim): void
    {
        $this->dataFim = trim($dataFim);
    }

    public function getDataFim(): string
    {
        return $this->dataFim;
    }

    public function setIdFiltro(string $idFiltro): void
    {
        $this->idFiltro = trim($idFiltro);
    }

    public function getIdFiltro(): string
    {
        return $this->idFiltro;
    }

    /** @return c_banco_pdo */
    private function getPdo()
    {
        if ($this->pdo === null) {
            $this->pdo = new c_banco_pdo();
        }
        return $this->pdo;
    }

    /**
     * Baixa reserva do pedido após NFC-e autorizada (mesmo fluxo da NF de pedido).
     */
    public function baixaEstoqueCupomAposNfce(int $idNf): void
    {
        $itens = $this->select_pedido_item_id();
        $est = new c_produto_estoque();
        $idPedido = (int) $this->getId();
        foreach ($itens as $row) {
            if (strtoupper((string) ($row['UNIFRACIONADA'] ?? 'N')) === 'S') {
                continue;
            }
            $est->produtoBaixaReserva(
                $this->m_empresacentrocusto,
                $idPedido,
                $idNf,
                $row['ITEMESTOQUE']
            );
        }
    }

    /**
     * Parâmetros NFC-e (modelo 65) da filial.
     *
     * @return array<string,mixed>|null
     */
    public function getParametroNfce(int $filial): ?array
    {
        $pdo = $this->getPdo();
        $sql = 'SELECT SERIE, NATOPERACAO, CONDPGTO, GENERO, CONTA, CLIENTEPADRAO, GRUPOPADRAO '
            . 'FROM EST_PARAMETRO WHERE FILIAL = :filial AND MODELO = 65 LIMIT 1';
        $pdo->prepare($sql);
        $pdo->execute([':filial' => $filial]);
        $row = $pdo->fetch();
        return $row ?: null;
    }

    /**
     * Modos de pagamento (AMB_DDM FIN_MENU / ESPECIEREC).
     *
     * @return array<int,array<string,string>>
     */
    public function listModoPagamento(): array
    {
        $pdo = $this->getPdo();
        $sql = "SELECT TIPO AS id, PADRAO AS descricao FROM AMB_DDM "
            . "WHERE ALIAS = 'FIN_MENU' AND CAMPO = 'ESPECIEREC' ORDER BY PADRAO";
        $pdo->prepare($sql);
        $pdo->execute();
        $rows = $pdo->fetchAll();
        return is_array($rows) ? $rows : [];
    }

    /**
     * Condições de pagamento (FAT_COND_PGTO).
     *
     * @return array<int,array<string,string>>
     */
    public function listCondPagamento(): array
    {
        $pdo = $this->getPdo();
        $sql = 'SELECT ID AS id, DESCRICAO AS descricao FROM FAT_COND_PGTO '
            . "WHERE COALESCE(BLOQUEADO, 'A') = 'A' ORDER BY DESCRICAO";
        $pdo->prepare($sql);
        $pdo->execute();
        $rows = $pdo->fetchAll();
        return is_array($rows) ? $rows : [];
    }

    /**
     * Lista grupos de produtos (tipo V).
     *
     * @return array<int,array<string,string>>
     */
    public function listGruposVenda(): array
    {
        $pdo = $this->getPdo();
        $sql = 'SELECT GRUPO AS id, DESCRICAO AS descricao FROM EST_GRUPO ORDER BY DESCRICAO';
        $pdo->prepare($sql);
        $pdo->execute();
        $rows = $pdo->fetchAll();
        return is_array($rows) ? $rows : [];
    }

    /**
     * Busca produtos para o cupom (delega regras de estoque/preço ao legado).
     *
     * @return array<int,array<string,mixed>>
     */
    public function buscarProdutosCupom(
        ?string $descricao,
        ?string $grupo,
        int $filial,
        string $controlaEstoque = 'N'
    ): array {
        $desc = trim((string) $descricao);
        $grp = trim((string) $grupo);
        $letra = $desc . '|' . $grp . '|N';
        $est = new c_produto_estoque();
        $result = $est->produtoQtdePreco($letra, $filial, null, $controlaEstoque);
        return is_array($result) ? $result : [];
    }

    /**
     * Uma nota por pedido e por origem (CPM = NFC-e, CPR = recibo).
     */
    public function existeNotaCupomPedidoPorOrigem(int $idPedido, string $origem): bool
    {
        $origem = strtoupper(trim($origem));
        if (!in_array($origem, ['CPM', 'CPR'], true) || $idPedido <= 0) {
            return false;
        }

        $pdo = $this->getPdo();
        $sql = 'SELECT ID FROM EST_NOTA_FISCAL '
            . 'WHERE ORIGEM = :origem AND DOC = :doc '
            . "AND SITUACAO NOT IN ('C') LIMIT 1";
        $pdo->prepare($sql);
        $pdo->execute([
            ':origem' => $origem,
            ':doc' => $idPedido,
        ]);
        $row = $pdo->fetch();

        return is_array($row) && isset($row['ID']);
    }

    /** NFC-e (CPM) já autorizada na SEFAZ (situação B). */
    public function existeNfceCupomAutorizada(int $idPedido): bool
    {
        $idNf = $this->getIdCupomFiscalPedidoPorOrigem($idPedido, 'CPM');
        if ($idNf === null) {
            return false;
        }

        $objNf = new c_nota_fiscal();
        $objNf->setId($idNf);
        $nf = $objNf->select_nota_fiscal();

        return is_array($nf)
            && isset($nf[0])
            && strtoupper((string) ($nf[0]['SITUACAO'] ?? '')) === 'B';
    }

    /**
     * ID da nota cupom PDV (modelo 65) por origem, se existir e não cancelada.
     */
    public function getIdCupomFiscalPedidoPorOrigem(int $idPedido, string $origem): ?int
    {
        $origem = strtoupper(trim($origem));
        if (!in_array($origem, ['CPM', 'CPR'], true) || $idPedido <= 0) {
            return null;
        }

        $pdo = $this->getPdo();
        $sql = 'SELECT ID FROM EST_NOTA_FISCAL '
            . 'WHERE ORIGEM = :origem AND DOC = :doc '
            . "AND MODELO = 65 AND SITUACAO NOT IN ('C') "
            . 'ORDER BY ID DESC LIMIT 1';
        $pdo->prepare($sql);
        $pdo->execute([
            ':origem' => $origem,
            ':doc' => $idPedido,
        ]);
        $row = $pdo->fetch();
        if (!is_array($row) || !isset($row['ID'])) {
            return null;
        }

        return (int) $row['ID'];
    }

    /**
     * Converte valor monetário BR (420,00), BD (420.00) ou numérico.
     */
    public static function parseMoedaValor($valor): float
    {
        $s = trim((string) $valor);
        if ($s === '') {
            return 0.0;
        }
        if (strpos($s, ',') !== false) {
            return (float) c_tools::moedaBd($s);
        }

        return (float) $s;
    }

    /**
     * Troco PDV: total recebido menos total do pedido.
     *
     * @return array{vPag: float, vTroco: float, total: float, recebido: float}
     */
    public function getTrocoPedidoPorNumero(int $idPedido): array
    {
        $pdo = $this->getPdo();
        $sql = 'SELECT TOTAL, TOTALRECEBIDO FROM FAT_PEDIDO WHERE ID = :id LIMIT 1';
        $pdo->prepare($sql);
        $pdo->execute([':id' => $idPedido]);
        $row = $pdo->fetch();
        if (!$row) {
            return ['vPag' => 0.0, 'vTroco' => 0.0, 'total' => 0.0, 'recebido' => 0.0];
        }
        $total = self::parseMoedaValor($row['TOTAL'] ?? 0);
        $recebido = self::parseMoedaValor($row['TOTALRECEBIDO'] ?? 0);
        $troco = max(0.0, $recebido - $total);
        $vPag = $troco > 0 ? $recebido : $total;
        return [
            'vPag' => $vPag,
            'vTroco' => $troco,
            'total' => $total,
            'recebido' => $recebido,
        ];
    }

    /**
     * Carrega pedido existente (ex.: gerência) para emissão de NFC-e.
     *
     * @return string|null Mensagem de erro ou null se OK
     */
    public function carregaPedidoParaCupomFiscal(bool $validarGerente): ?string
    {
        if ((int) $this->getId() <= 0) {
            return 'Pedido inválido.';
        }

        $param = $this->getParametroNfce((int) $this->m_empresacentrocusto);
        if (!$param) {
            return 'Parâmetros NFC-e (modelo 65) não configurados para esta filial.';
        }

        $pedido = $this->select_pedidoVenda();
        if (!is_array($pedido) || !isset($pedido[0])) {
            return 'Pedido não encontrado.';
        }

        $sitPedido = (int) ($pedido[0]['SITUACAO'] ?? 0);
        if ($validarGerente && !in_array($sitPedido, [3, 6], true)) {
            return 'Cupom fiscal só pode ser emitido para pedidos em situação Pedido ou Emitir NF.';
        }

        if ((int) ($pedido[0]['SITUACAO'] ?? 0) === 9) {
            return 'Pedido já baixado.';
        }

        $idPedido = (int) $this->getId();
        $objNf = new c_nota_fiscal();
        if ($idPedido > 0 && $objNf->existeNotaFiscalPedido($idPedido)) {
            return 'Já existe nota fiscal vinculada a este pedido.';
        }

        $itens = $this->select_pedido_item_id();
        if (!is_array($itens) || count($itens) === 0) {
            return 'Pedido sem itens para emitir cupom.';
        }

        $this->setPedidoVenda();

        try {
            $this->aplicaParametrosNfceCupom();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return null;
    }

    /**
     * Aplica parâmetros NFC-e (modelo 65) — sobrescreve série/natop do pedido quando vazios.
     *
     * @throws Exception
     */
    public function aplicaParametrosNfceCupom(): void
    {
        $param = $this->getParametroNfce((int) $this->m_empresacentrocusto);
        if (!$param) {
            throw new Exception('Parâmetros NFC-e (modelo 65) não configurados para esta filial.');
        }
        if (trim((string) ($param['SERIE'] ?? '')) === '') {
            throw new Exception('Série NFC-e não configurada em EST_PARAMETRO (modelo 65).');
        }
        if (trim((string) ($param['NATOPERACAO'] ?? '')) === '') {
            throw new Exception('Natureza de operação NFC-e não configurada em EST_PARAMETRO.');
        }

        $this->setSerie((string) $param['SERIE']);
        $this->setIdNatop((string) $param['NATOPERACAO']);
        if ($this->getCondPg() === '' || $this->getCondPg() === null) {
            $this->setCondPg($param['CONDPGTO']);
        }
        if ($this->getGenero() === '' || $this->getGenero() === null) {
            $this->setGenero((string) $param['GENERO']);
        }
        if ($this->getContaDeposito() === '' || $this->getContaDeposito() === null) {
            $this->setContaDeposito((string) $param['CONTA']);
        }
    }

    public function inicializaPedidoCupom(): void
    {
        $this->setSituacao(6);
        $this->setEmissao(date('d/m/Y'));
        $this->setAtendimento(date('d/m/Y'));
        $this->setHoraEmissao(date('H:i:s'));
        $this->setEspecie('D');
        $this->setCentroCusto($this->m_empresacentrocusto);

        $this->aplicaParametrosNfceCupom();
        $this->setSerie('65');

        $idNovo = $this->incluiPedido();
        if (!is_numeric($idNovo) || (int) $idNovo <= 0) {
            throw new Exception(is_string($idNovo) ? $idNovo : 'Falha ao incluir pedido do cupom.');
        }

        $this->setId((string) $idNovo);
    }

    /**
     * @param resource|int|null $conn
     */
    public function cadastraParcelaFinanceiraCupom($conn, c_nota_fiscal $objNotaFiscal, string $modoPgto = 'D'): void
    {
        $objFinanceiro = new c_lancamento();
        $idPedido = (int) $this->getId();
        $existeFin = $objFinanceiro->select_lancamento_doc('PED', $idPedido, $conn);
        if (is_array($existeFin)) {
            return;
        }

        $valor = $this->getTotal('B');
        if ($valor === '' || $valor === null || (float) $valor == 0.0) {
            $valor = $this->getTotalProdutos('B');
        }

        $modoPgto = trim($modoPgto) !== '' ? trim($modoPgto) : 'D';

        $arrParcelas = [[
            'TIPO' => $modoPgto,
            'SITUACAO' => 'A',
            'CONTA' => $this->getContaDeposito(),
            'VENCIMENTO' => date('d/m/Y'),
            'VALOR' => $valor,
            'OBS' => 'Cupom PDV',
            'DESCONTO' => '0,00',
        ]];

        $arrParamFin = [
            'PESSOA' => $objNotaFiscal->getPessoa(),
            'DOCTO' => $idPedido,
            'SERIE' => 'PED',
            'GENERO' => $this->getGenero(),
            'CENTROCUSTO' => $this->m_empresacentrocusto,
            'USER' => $this->m_userid,
            'ORIGEM' => 'PED',
            'NUMLCTO' => $idPedido,
            'TIPOLANCAMENTO' => 'R',
            'OBS' => $this->getObs(),
        ];

        $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $conn);
    }

    /**
     * @return array{somaItens: float, desconto: float, frete: float, total: float}
     */
    private function calculaEPersisteTotaisPedidoCupom(bool $recarregarCabecalho = true): array
    {
        $id = (int) $this->getId();
        if ($recarregarCabecalho) {
            $this->setPedidoVenda();
        }

        $somaItens = max(0.0, (float) $this->select_totalPedido());
        $this->setTotalProdutos($somaItens);

        $desconto = self::parseMoedaValor($this->getDesconto('F'));
        $frete = self::parseMoedaValor($this->getFrete('F'));
        $desp = self::parseMoedaValor($this->getDespAcessorias('F'));
        $total = max(0.0, $somaItens - $desconto + $frete + $desp);

        $this->setTotal($total);
        $this->setDesconto(number_format($desconto, 2, ',', '.'), false);
        $this->setFrete(number_format($frete, 2, ',', '.'), false);

        $pdo = $this->getPdo();
        $pdo->prepare(
            'UPDATE FAT_PEDIDO SET TOTALPRODUTOS = :tp, TOTAL = :t, DESCONTO = :desc, FRETE = :frete, '
            . 'USERCHANGE = :u, DATECHANGE = NOW() WHERE ID = :id'
        );
        $pdo->execute([
            ':tp' => number_format($somaItens, 2, '.', ''),
            ':t' => number_format($total, 2, '.', ''),
            ':desc' => $this->getDesconto('B'),
            ':frete' => $this->getFrete('B'),
            ':u' => $this->m_userid,
            ':id' => $id,
        ]);

        return [
            'somaItens' => $somaItens,
            'desconto' => $desconto,
            'frete' => $frete,
            'total' => $total,
        ];
    }

    public function recalculaTotaisPedidoCupom(bool $recarregarCabecalho = true): void
    {
        if ((int) $this->getId() <= 0) {
            return;
        }
        $this->calculaEPersisteTotaisPedidoCupom($recarregarCabecalho);
    }

    /**
     * Desconto/frete do POST (emissão) + recálculo do pedido em uma única passagem.
     *
     * @param array<string,mixed> $post
     */
    public function preparaTotaisCupomParaEmissao(array $post): void
    {
        if ((int) $this->getId() <= 0) {
            return;
        }

        $desconto = trim((string) ($post['desconto'] ?? ''));
        $frete = trim((string) ($post['frete'] ?? ''));
        if ($desconto !== '' || $frete !== '') {
            if ($desconto === '') {
                $desconto = $this->getDesconto('F') ?: '0,00';
            }
            if ($frete === '') {
                $frete = $this->getFrete('F') ?: '0,00';
            }
            $this->setDesconto(number_format(self::parseMoedaValor($desconto), 2, ',', '.'), false);
            $this->setFrete(number_format(self::parseMoedaValor($frete), 2, ',', '.'), false);
            $this->calculaEPersisteTotaisPedidoCupom(false);

            return;
        }

        $this->calculaEPersisteTotaisPedidoCupom(true);
    }

    public function sincronizaTotaisPedidoNaNotaFiscal(int $idNf): void
    {
        if ($idNf <= 0 || (int) $this->getId() <= 0) {
            return;
        }

        $totais = $this->calculaEPersisteTotaisPedidoCupom(true);

        $pdo = $this->getPdo();
        $pdo->prepare(
            'UPDATE EST_NOTA_FISCAL SET FRETE = :frete, DESCONTOGERAL = :desc, TOTALNF = :total WHERE ID = :id'
        );
        $pdo->execute([
            ':frete' => number_format($totais['frete'], 2, '.', ''),
            ':desc' => number_format($totais['desconto'], 2, '.', ''),
            ':total' => number_format($totais['total'], 2, '.', ''),
            ':id' => $idNf,
        ]);

        $objNf = new c_nota_fiscal();
        $objNf->setId($idNf);
        $objNf->setFrete($totais['frete'], true);
        $objNf->setDespAcessorias($this->getDespAcessorias('B') ?: 0, true);
        $objNf->calculaRateios();
        $this->rateiaDescontoCupomNaNotaFiscal($idNf, $totais['desconto']);
    }

    private function rateiaDescontoCupomNaNotaFiscal(int $idNf, float $descontoPedido): void
    {
        if ($idNf <= 0) {
            return;
        }

        $pdo = $this->getPdo();

        if ($descontoPedido <= 0) {
            $pdo->prepare('UPDATE EST_NOTA_FISCAL_PRODUTO SET DESCONTO = 0 WHERE IDNF = :idnf');
            $pdo->execute([':idnf' => $idNf]);

            return;
        }

        $itens = (new c_nota_fiscal_produto())->selectNotaFiscalProdutoImposto($idNf) ?? [];
        if ($itens === []) {
            return;
        }

        $baseTotal = 0.0;
        foreach ($itens as $row) {
            $baseTotal += (float) $row['QUANT'] * (float) $row['UNITARIO'];
        }
        if ($baseTotal <= 0) {
            return;
        }

        $descontoDist = 0.0;
        $ultimo = count($itens) - 1;
        $pdo->prepare(
            'UPDATE EST_NOTA_FISCAL_PRODUTO SET DESCONTO = :desc WHERE ID = :id AND CODPRODUTO = :cod'
        );

        foreach ($itens as $i => $row) {
            $base = (float) $row['QUANT'] * (float) $row['UNITARIO'];
            $vlrDesc = round($descontoPedido * ($base / $baseTotal), 2);
            $descontoDist += $vlrDesc;

            if ($i === $ultimo) {
                if ($descontoDist > $descontoPedido) {
                    $vlrDesc -= $descontoDist - $descontoPedido;
                } elseif ($descontoDist < $descontoPedido) {
                    $vlrDesc += $descontoPedido - $descontoDist;
                }
            }

            $pdo->execute([
                ':desc' => number_format($vlrDesc, 2, '.', ''),
                ':id' => $row['ID'],
                ':cod' => $row['CODPRODUTO'],
            ]);
        }
    }

    public function salvaDescontoFreteCupom(string $desconto, string $frete): ?string
    {
        if ((int) $this->getId() <= 0) {
            return 'Inclua itens no cupom antes de informar desconto ou frete.';
        }

        $this->setDesconto(number_format(self::parseMoedaValor($desconto), 2, ',', '.'), false);
        $this->setFrete(number_format(self::parseMoedaValor($frete), 2, ',', '.'), false);
        $this->calculaEPersisteTotaisPedidoCupom(false);

        return null;
    }

    /**
     * Estrutura vazia de pedido para templates Smarty.
     *
     * @param array<string,mixed> $overrides
     * @return array<string,mixed>
     */
    public static function pedidoViewVazio(array $overrides = []): array
    {
        $base = [
            'id' => '',
            'emissao' => '',
            'situacao' => '',
            'cliente' => '',
            'nomeCliente' => '',
            'totalProdutos' => '0,00',
            'desconto' => '0,00',
            'frete' => '0,00',
            'totalCupom' => '0,00',
            'obs' => '',
            'qtdItens' => 0,
            'lancItens' => [],
        ];

        return array_merge($base, $overrides);
    }

    /**
     * @param array<string,mixed>|null $resultNfe
     */
    public static function nfeAutorizadaPorResultado($resultNfe): bool
    {
        if (!is_array($resultNfe)) {
            return false;
        }
        $cStat = (string) ($resultNfe['cStatus'] ?? $resultNfe['cstat'] ?? '');

        return $cStat === '100' || $cStat === '105';
    }

    /**
     * @param array<string,mixed>|null $resultNfe
     */
    public static function mensagemErroNfceResultado($resultNfe): string
    {
        if (!is_array($resultNfe)) {
            return 'Falha na autorização NFC-e na SEFAZ.';
        }

        return trim((string) ($resultNfe['motivo'] ?? 'Rejeição SEFAZ'))
            . ' (cStat: ' . ($resultNfe['cStatus'] ?? $resultNfe['cstat'] ?? '') . ')';
    }

    /**
     * Payload JSON unificado para emissão NFC-e (PDV blank ou gerência).
     *
     * @param array<string,mixed>|null $resultNfe
     * @return array{payload: array<string,mixed>, httpCode: int}
     */
    public static function montarRespostaEmissaoNfce(
        string $msg,
        $resultNfe,
        int $idPedido,
        bool $formatoGerente = false
    ): array {
        if ($msg !== '') {
            $payload = [
                'success' => false,
                'message' => $msg,
                'tipo' => 'erro',
            ];
            if (!$formatoGerente) {
                $payload['pedidoId'] = $idPedido;
            }

            return ['payload' => $payload, 'httpCode' => 400];
        }

        if ($formatoGerente) {
            $payload = [
                'success' => true,
                'tipo' => 'nfce',
                'message' => 'NFC-e emitida com sucesso.',
                'idNf' => 0,
                'danfe' => '',
            ];
        } else {
            $payload = [
                'success' => true,
                'message' => 'NFC-e emitida com sucesso.',
                'idNf' => 0,
                'danfe' => '',
                'cStat' => '',
                'pedidoId' => $idPedido,
            ];
        }

        if (is_array($resultNfe)) {
            $cStatus = (string) ($resultNfe['cStatus'] ?? $resultNfe['cstat'] ?? '');
            if (!$formatoGerente) {
                $payload['cStat'] = $cStatus;
            }
            if ($cStatus === '100' || $cStatus === '105') {
                $payload['idNf'] = (int) ($resultNfe['idNf'] ?? 0);
                $payload['danfe'] = (string) ($resultNfe['cDanfe'] ?? '');
            } else {
                $erro = [
                    'success' => false,
                    'message' => trim(
                        ($resultNfe['motivo'] ?? 'Rejeição SEFAZ') . ' (cStat: ' . $cStatus . ')'
                    ),
                    'tipo' => 'erro',
                ];
                if (!$formatoGerente) {
                    $erro['cStat'] = $cStatus;
                    $erro['pedidoId'] = $idPedido;
                }

                return ['payload' => $erro, 'httpCode' => 400];
            }
        }

        return ['payload' => $payload, 'httpCode' => 200];
    }

    /**
     * Dados do pedido para templates Smarty.
     *
     * @return array<string,mixed>
     */
    public function montaViewPedidoCupom(bool $recalcular = false): array
    {
        if ($this->getId() === '' || $this->getId() === null) {
            return [];
        }

        if ($recalcular) {
            $this->recalculaTotaisPedidoCupom();
        } else {
            $this->setPedidoVenda();
        }

        $nomeCliente = '';
        if ($this->getCliente() !== '') {
            $this->setClienteNome();
            $nomeCliente = (string) $this->getClienteNome();
        }

        $lancItens = $this->select_pedido_item_id();
        if (!is_array($lancItens)) {
            $lancItens = [];
        }

        return [
            'id' => $this->getId(),
            'emissao' => $this->getEmissao('F'),
            'situacao' => (string) $this->getSituacao(),
            'cliente' => $this->getCliente(),
            'nomeCliente' => $nomeCliente,
            'totalProdutos' => $this->getTotalProdutos('F'),
            'desconto' => $this->getDesconto('F'),
            'frete' => $this->getFrete('F'),
            'totalCupom' => $this->getTotal('F'),
            'obs' => $this->getObs(),
            'qtdItens' => count($lancItens),
            'lancItens' => $lancItens,
        ];
    }

    /** Troco para exibição (valor recebido − total do cupom). */
    public static function formataTroco(string $valorRecebido, string $totalCupom): string
    {
        $troco = max(0.0, self::parseMoedaValor($valorRecebido) - self::parseMoedaValor($totalCupom));
        return number_format($troco, 2, ',', '.');
    }

    /**
     * Busca produtos PDV (código, fabricante, descrição, barras).
     *
     * @return array{success:bool,total:int,autoIncluir:bool,itens:array<int,array<string,string>>}
     */
    public function buscaProdutoPdv(string $termo): array
    {
        $termo = trim($termo);
        if ($termo === '') {
            return ['success' => false, 'total' => 0, 'autoIncluir' => false, 'itens' => []];
        }

        if (strlen($termo) < 3 && !preg_match('/^\d+$/', $termo)) {
            return [
                'success' => false,
                'total' => 0,
                'autoIncluir' => false,
                'itens' => [],
                'message' => 'Digite pelo menos 3 caracteres para pesquisar.',
            ];
        }

        $produto = new c_produto();

        return $this->formatarBuscaProdutoPdvJson($produto->buscarProdutosPorTermo($termo));
    }

    /**
     * @param array<int,array<string,mixed>> $rows
     * @return array{success:bool,total:int,autoIncluir:bool,itens:array<int,array<string,string>>,message?:string}
     */
    private function formatarBuscaProdutoPdvJson(array $rows): array
    {
        $itens = [];
        $vistos = [];

        foreach ($rows as $row) {
            $codigo = (string) ($row['CODIGO'] ?? '');
            if ($codigo === '' || isset($vistos[$codigo])) {
                continue;
            }
            $vistos[$codigo] = true;
            $venda = isset($row['VENDA']) ? (float) $row['VENDA'] : 0.0;
            $promocao = isset($row['PROMOCAO']) ? (float) $row['PROMOCAO'] : 0.0;
            $itens[] = [
                'codigo' => $codigo,
                'codFabricante' => (string) ($row['CODFABRICANTE'] ?? ''),
                'descricao' => (string) ($row['DESCRICAO'] ?? ''),
                'unidade' => (string) ($row['UNIDADE'] ?? ''),
                'venda' => number_format($venda, 2, ',', '.'),
                'promocao' => $promocao > 0 ? number_format($promocao, 2, ',', '.') : '',
                'codigoBarras' => (string) ($row['CODIGOBARRAS'] ?? ''),
            ];
        }

        $total = count($itens);

        return [
            'success' => $total > 0,
            'total' => $total,
            'autoIncluir' => $total === 1,
            'itens' => $itens,
            'message' => $total > 0 ? '' : 'Produto não localizado.',
        ];
    }

    /**
     * Atualiza cliente no pedido PDV (cria pedido se necessário).
     *
     * @return string|null Mensagem de erro ou null se OK
     */
    public function salvaClientePedido(int $idCliente): ?string
    {
        if ($idCliente <= 0) {
            $param = $this->getParametroNfce((int) $this->m_empresacentrocusto);
            $idCliente = (int) ($param['CLIENTEPADRAO'] ?? 0);
            if ($idCliente <= 0) {
                return 'Cliente não informado e sem cliente padrão NFC-e configurado.';
            }
        }

        $this->setCliente((string) $idCliente);

        if ($this->getId() === '' || $this->getId() === null) {
            try {
                $this->inicializaPedidoCupom();
            } catch (Exception $e) {
                return $e->getMessage();
            }
            return null;
        }

        $pdo = $this->getPdo();
        $sql = 'UPDATE FAT_PEDIDO SET cliente = :cliente, userchange = :user, datechange = NOW() '
            . 'WHERE id = :id';
        $pdo->prepare($sql);
        $pdo->execute([
            ':cliente' => $idCliente,
            ':user' => $this->m_userid,
            ':id' => (int) $this->getId(),
        ]);

        return null;
    }

    public static function parseQuantidadeCupom(string $quantidade): float
    {
        $s = trim($quantidade);
        if ($s === '') {
            return 1.0;
        }
        if (strpos($s, ',') !== false) {
            return (float) str_replace(',', '.', str_replace('.', '', $s));
        }

        return (float) $s;
    }

    /**
     * @return array{produto:array<string,mixed>,unitarioOverride:?float}|array{success:false,message:string,tipo:string}
     */
    private function dadosProdutoItemCupom(
        string $codigo,
        float $qtdFloat,
        string $controlaEstoque,
        ?string $unitarioInformado,
        float $qtdJaReservadaNoPedido = 0.0
    ): array {
        $objProdutoQtde = new c_produto_estoque();
        $objProduto = new c_produto();
        $objProduto->setId($codigo);
        $arrProduto = $objProdutoQtde->produtoQtdePreco(
            null,
            $this->m_empresacentrocusto,
            $objProduto->getId(),
            $controlaEstoque
        );

        if (!is_array($arrProduto) || !isset($arrProduto[0])) {
            return ['success' => false, 'message' => 'Produto não localizado.', 'tipo' => 'erro'];
        }

        $row = $arrProduto[0];
        if ($controlaEstoque === 'S') {
            $row['QUANTIDADE'] = (float) $row['QUANTIDADE'] + $qtdJaReservadaNoPedido;
        } else {
            $row['QUANTIDADE'] = $qtdFloat;
        }

        $unitarioOverride = null;
        if ($unitarioInformado !== null && trim($unitarioInformado) !== '') {
            $unitNorm = self::parseMoedaValor(trim($unitarioInformado));
            if ($unitNorm > 0) {
                $unitarioOverride = $unitNorm;
                $row['VENDA'] = $unitNorm;
            }
        }

        $precoUnit = $unitarioOverride ?? max((float) $row['VENDA'], (float) ($row['PROMOCAO'] ?? 0));

        if ($qtdFloat > (float) $row['QUANTIDADE'] || $precoUnit <= 0) {
            return [
                'success' => false,
                'message' => $row['DESCRICAO'] . ' — preço ou quantidade não disponível.',
                'tipo' => 'alerta',
            ];
        }

        if (
            $unitarioOverride === null
            && (float) $row['PROMOCAO'] > 0
            && $qtdFloat > (float) $row['QUANTLIMITE']
        ) {
            return [
                'success' => false,
                'message' => $row['DESCRICAO'] . ' — quantidade acima do limite de promoção (' . $row['QUANTLIMITE'] . ').',
                'tipo' => 'alerta',
            ];
        }

        return ['produto' => $row, 'unitarioOverride' => $unitarioOverride];
    }

    private function preencheItemCupom(string $codigo, array $row, float $qtdFloat, ?float $unitarioOverride): void
    {
        $this->setItemEstoque($codigo);
        $this->setItemFabricante($row['CODFABRICANTE']);
        $this->setQtSolicitada(number_format($qtdFloat, 3, ',', '.'));
        if ($unitarioOverride !== null) {
            $this->setUnitario(str_replace('.', ',', (string) $unitarioOverride));
        } elseif ((float) $row['PROMOCAO'] > 0) {
            $this->setUnitario(str_replace('.', ',', $row['PROMOCAO']));
        } else {
            $this->setUnitario(str_replace('.', ',', $row['VENDA']));
        }
        $this->setPrecoPromocao(str_replace('.', ',', $row['PROMOCAO']));
        $this->setVlrTabela(str_replace('.', ',', $row['VENDA']));
        $this->setTotalItem();
        $this->setGrupoEstoque($row['GRUPO']);
        $this->setDescricaoItem($row['DESCRICAO']);
    }

    /**
     * Inclui item no cupom pelo código interno.
     *
     * @return array{success:bool,message:string,tipo:string}
     */
    public function incluirItemCupomPorCodigo(
        string $codigo,
        string $quantidade,
        string $controlaEstoque = 'N',
        ?string $unitarioInformado = null
    ): array {
        $codigo = trim($codigo);
        if ($codigo === '') {
            return ['success' => false, 'message' => 'Código do produto não informado.', 'tipo' => 'erro'];
        }

        $qtdFloat = self::parseQuantidadeCupom($quantidade);
        $dados = $this->dadosProdutoItemCupom($codigo, $qtdFloat, $controlaEstoque, $unitarioInformado);
        if (isset($dados['success']) && $dados['success'] === false) {
            return $dados;
        }

        if ($this->getId() === '' || $this->getId() === null) {
            $param = $this->getParametroNfce((int) $this->m_empresacentrocusto);
            if ($param && trim((string) ($param['CLIENTEPADRAO'] ?? '')) !== '') {
                $this->setCliente((string) $param['CLIENTEPADRAO']);
            }
            try {
                $this->inicializaPedidoCupom();
            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage(), 'tipo' => 'erro'];
            }
        }

        $this->preencheItemCupom($codigo, $dados['produto'], $qtdFloat, $dados['unitarioOverride']);

        $ultimoNrItem = $this->select_pedidoVenda_item_max_nritem();
        $maxNr = 0;
        if (is_array($ultimoNrItem) && isset($ultimoNrItem[0]['MAXNRITEM']) && $ultimoNrItem[0]['MAXNRITEM'] !== '') {
            $maxNr = (int) $ultimoNrItem[0]['MAXNRITEM'];
        }
        $this->setNrItem((string) ($maxNr + 1));

        $msgInclui = $this->IncluiPedidoItem();
        if (is_string($msgInclui) && trim($msgInclui) !== '') {
            return ['success' => false, 'message' => $msgInclui, 'tipo' => 'erro'];
        }

        if ($controlaEstoque === 'S') {
            $objProdutoQtde = new c_produto_estoque();
            $objProdutoQtde->produtoReserva(
                $this->m_empresacentrocusto,
                'PED',
                $this->getId(),
                $this->getItemEstoque(),
                (string) $qtdFloat
            );
        }

        $this->recalculaTotaisPedidoCupom();

        return ['success' => true, 'message' => 'Item incluído.', 'tipo' => 'sucesso'];
    }

    /**
     * Altera quantidade e/ou unitário de um item já incluído no cupom.
     *
     * @return array{success:bool,message:string,tipo:string}
     */
    public function alterarItemCupomPorNrItem(
        int $nrItem,
        string $quantidade,
        string $controlaEstoque = 'N',
        ?string $unitarioInformado = null
    ): array {
        if ($nrItem <= 0) {
            return ['success' => false, 'message' => 'Item inválido.', 'tipo' => 'erro'];
        }

        $this->setNrItem((string) $nrItem);
        $arrItem = $this->select_pedido_item_id_nritem();
        if (!is_array($arrItem) || !isset($arrItem[0])) {
            return ['success' => false, 'message' => 'Item não encontrado.', 'tipo' => 'erro'];
        }

        $row = $arrItem[0];
        $this->setId((string) $row['ID']);
        $codigo = trim((string) ($row['ITEMESTOQUE'] ?? ''));
        if ($codigo === '') {
            return ['success' => false, 'message' => 'Produto do item inválido.', 'tipo' => 'erro'];
        }

        $qtdFloat = self::parseQuantidadeCupom($quantidade);
        $qtdAnterior = self::parseQuantidadeCupom((string) ($row['QTSOLICITADA'] ?? '1'));
        $dados = $this->dadosProdutoItemCupom(
            $codigo,
            $qtdFloat,
            $controlaEstoque,
            $unitarioInformado,
            $controlaEstoque === 'S' ? $qtdAnterior : 0.0
        );
        if (isset($dados['success']) && $dados['success'] === false) {
            return $dados;
        }

        $this->preencheItemCupom($codigo, $dados['produto'], $qtdFloat, $dados['unitarioOverride']);
        if (isset($row['QTATENDIDA'])) {
            $this->setQtAtendida($row['QTATENDIDA']);
        }
        $this->setNrItem((string) $nrItem);

        $msg = $this->alteraPedidoItem();
        if ($msg !== '' && $msg !== null) {
            return ['success' => false, 'message' => (string) $msg, 'tipo' => 'erro'];
        }

        if ($controlaEstoque === 'S' && abs($qtdFloat - $qtdAnterior) > 0.0001) {
            $est = new c_produto_estoque();
            $est->produtoReservaExclui(
                $this->m_empresacentrocusto,
                'PED',
                $this->getId(),
                $codigo,
                (string) $qtdAnterior
            );
            $est->produtoReserva(
                $this->m_empresacentrocusto,
                'PED',
                $this->getId(),
                $codigo,
                (string) $qtdFloat
            );
        }

        $this->recalculaTotaisPedidoCupom();

        return ['success' => true, 'message' => 'Item alterado.', 'tipo' => 'sucesso'];
    }

    /**
     * Exclui item do cupom e recalcula totais do pedido.
     *
     * @return array{success:bool,message:string,tipo:string}
     */
    public function excluirItemCupomPorNrItem(int $nrItem, string $controlaEstoque = 'N'): array
    {
        $this->setNrItem((string) $nrItem);
        $arrPedidoItem = $this->select_pedido_item_id_nritem();
        if (!is_array($arrPedidoItem) || !isset($arrPedidoItem[0])) {
            return ['success' => false, 'message' => 'Item não encontrado.', 'tipo' => 'erro'];
        }

        $this->setId($arrPedidoItem[0]['ID']);
        $this->setItemEstoque($arrPedidoItem[0]['ITEMESTOQUE']);
        $this->setQtSolicitada($arrPedidoItem[0]['QTSOLICITADA']);

        if ($controlaEstoque === 'S') {
            $objProdutoQtde = new c_produto_estoque();
            $objProdutoQtde->produtoReservaExclui(
                $this->m_empresacentrocusto,
                'PED',
                $this->getId(),
                $this->getItemEstoque(),
                $this->getQtSolicitada()
            );
        }

        $msg = $this->excluiPedidoItem();
        if ($msg !== '' && $msg !== null) {
            return ['success' => false, 'message' => (string) $msg, 'tipo' => 'erro'];
        }

        $this->recalculaTotaisPedidoCupom();

        return ['success' => true, 'message' => 'Item removido.', 'tipo' => 'sucesso'];
    }

    public function carregarPedidoCupomPorId(int $idPedido): void
    {
        $this->setId((string) $idPedido);
        $this->setPedidoVenda();
    }

    /**
     * Lista cupons PDV em aberto (situação 6).
     *
     * @return array<int,array<string,mixed>>
     */
    public function selectCuponsPdvAbertos(): array
    {
        $pdo = $this->getPdo();
        $sqlBase = 'SELECT P.ID, P.EMISSAO, P.HORAEMISSAO, P.CLIENTE, C.NOME AS NOMECLIENTE, '
            . 'P.TOTAL, P.SITUACAO, P.USERINSERT, U.NOME AS NOMEUSUARIO, '
            . '(SELECT COUNT(*) FROM FAT_PEDIDO_ITEM I WHERE I.ID = P.ID) AS QTDITENS '
            . 'FROM FAT_PEDIDO P '
            . 'LEFT JOIN FIN_CLIENTE C ON C.CLIENTE = P.CLIENTE '
            . 'LEFT JOIN AMB_USUARIO U ON U.USUARIO = P.USERINSERT ';

        $idFiltro = trim($this->getIdFiltro());
        if ($idFiltro !== '' && ctype_digit($idFiltro)) {
            $sql = $sqlBase . 'WHERE P.SITUACAO = 6 AND P.ID = :id ORDER BY P.ID DESC';
            $pdo->prepare($sql);
            $pdo->execute([':id' => (int) $idFiltro]);
        } else {
            $sql = $sqlBase . 'WHERE P.SITUACAO = 6 AND P.CCUSTO = :ccusto ';
            $params = [':ccusto' => (int) $this->m_empresacentrocusto];
            $pessoa = (int) $this->getCliente();
            if ($pessoa > 0) {
                $sql .= 'AND P.CLIENTE = :pessoa ';
                $params[':pessoa'] = $pessoa;
            }
            $dataIni = c_date::convertDateBdSh($this->getDataIni());
            $dataFim = c_date::convertDateBdSh($this->getDataFim());
            if ($dataIni !== '' && $dataFim !== '') {
                $sql .= 'AND P.EMISSAO BETWEEN :dataIni AND :dataFim ';
                $params[':dataIni'] = $dataIni;
                $params[':dataFim'] = $dataFim;
            }
            $sql .= 'ORDER BY P.ID DESC LIMIT 300';
            $pdo->prepare($sql);
            $pdo->execute($params);
        }

        $rows = $pdo->fetchAll(PDO::FETCH_ASSOC);
        if (!is_array($rows)) {
            return [];
        }

        foreach ($rows as $i => $row) {
            $id = (int) ($row['ID'] ?? 0);
            $rows[$i]['TOTAL_FMT'] = number_format((float) str_replace(',', '.', (string) ($row['TOTAL'] ?? 0)), 2, ',', '.');
            $rows[$i]['TEM_NFCE'] = $id > 0 && $this->existeNfceCupomAutorizada($id) ? 'S' : 'N';
            $rows[$i]['EMISSAO_FMT'] = (string) ($row['EMISSAO'] ?? '');
        }

        return $rows;
    }
}
