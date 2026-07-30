<?php

/**
 * @package   astec
 * @name      c_nota_fiscal_devolucao
 * @version   4.5.0
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 */

$dir = dirname(__FILE__);
include_once($dir . '/../../bib/c_user.php');
include_once($dir . '/../../bib/c_database_pdo.php');
include_once($dir . '/../../bib/c_tools.php');
include_once($dir . '/../../bib/c_date.php');
include_once($dir . '/../../class/est/c_nota_fiscal.php');
include_once($dir . '/../../class/est/c_nota_fiscal_produto.php');
include_once($dir . '/../../class/ped/c_pedido_venda_nf.php');

class c_nota_fiscal_devolucao extends c_user
{
    // Tipos de referência XML para tpNFCredito / tpNFDebito
    const REF_NENHUMA            = 'NENHUMA';            // sem referência (nota rejeitada se houver)
    const REF_NFREF              = 'NFREF';              // <NFref> no cabeçalho (opcional)
    const REF_NFREF_PROIBE_DFE   = 'NFREF_PROIBE_DFE';  // <NFref> no cabeçalho, proibido DFeReferenciado no item
    const REF_DFE_OPCIONAL       = 'DFE_OPCIONAL';       // <DFeReferenciado> com chave+nItem (opcional)
    const REF_DFE_OBRIG_SEM_ITEM = 'DFE_OBRIG_SEM_ITEM'; // <DFeReferenciado> com chave sem nItem (obrigatório)
    const REF_DFE_OBRIG_COM_ITEM = 'DFE_OBRIG_COM_ITEM'; // <DFeReferenciado> com chave+nItem (obrigatório)

    const OPCOES_TP_NF_CREDITO = [
        [
            'valor' => '01',
            'label' => '01 - Multa e juros',
            'cClassTrib' => null,
            'refTipo' => 'NFREF'
        ],
        [
            'valor' => '02',
            'label' => '02 - Apropriação de crédito presumido de IBS sobre o saldo devedor na ZFM',
            'cClassTrib' => '810001',
            'refTipo' => 'NENHUMA'
        ],
        [
            'valor' => '03',
            'label' => '03 - Retorno por recusa total na entrega ou por não localização do destinatário',
            'cClassTrib' => null,
            'refTipo' => 'NFREF'
        ],
        [
            'valor' => '04',
            'label' => '04 - Redução de valores',
            'cClassTrib' => null,
            'refTipo' => 'NFREF_PROIBE_DFE'
        ],
        [
            'valor' => '05',
            'label' => '05 - Transferência de crédito na sucessão',
            'cClassTrib' => '800001',
            'refTipo' => 'NFREF_PROIBE_DFE'
        ],
        [
            'valor' => '06',
            'label' => '06 - Retorno por recusa parcial na entrega',
            'cClassTrib' => null,
            'refTipo' => 'DFE_OBRIG_COM_ITEM'
        ],
    ];

    const OPCOES_TP_NF_DEBITO = [
        [
            'valor' => '01',
            'label' => '01 - Transferência de créditos para Cooperativas',
            'cClassTrib' => '800002',
            'refTipo' => 'DFE_OPCIONAL'
        ],
        [
            'valor' => '02',
            'label' => '02 - Anulação de Crédito por Saídas Imunes/Isentas',
            'cClassTrib' => '811001',
            'refTipo' => 'DFE_OPCIONAL'
        ],
        [
            'valor' => '03',
            'label' => '03 - Débitos de notas fiscais não processadas na apuração',
            'cClassTrib' => '811002',
            'refTipo' => 'DFE_OBRIG_SEM_ITEM'
        ],
        [
            'valor' => '04',
            'label' => '04 - Multa e Juros',
            'cClassTrib' => null,
            'refTipo' => 'DFE_OBRIG_COM_ITEM'
        ],
        [
            'valor' => '05',
            'label' => '05 - Transferência de crédito na sucessão',
            'cClassTrib' => '800001',
            'refTipo' => 'DFE_OPCIONAL'
        ],
        [
            'valor' => '06',
            'label' => '06 - Pagamento antecipado',
            'cClassTrib' => null,
            'refTipo' => 'DFE_OPCIONAL'
        ],
        [
            'valor' => '07',
            'label' => '07 - Perda em estoque: Perecimento, Perda, Furto ou Roubo',
            'cClassTrib' => '410030',
            'refTipo' => 'DFE_OPCIONAL'
        ],
        [
            'valor' => '08',
            'label' => '08 - Desenquadramento do Simples Nacional',
            'cClassTrib' => '811003',
            'refTipo' => 'DFE_OPCIONAL'
        ],
    ];

    public function getOpcoesTPNF(): array
    {
        return [
            'credito' => self::OPCOES_TP_NF_CREDITO,
            'debito'  => self::OPCOES_TP_NF_DEBITO,
        ];
    }

    private $idNfOrigem = 0;
    private $idNfDev = 0;
    private $idNatop = 0;
    private $idPessoa = 0;
    private $codProduto = 0;
    private $cenarioCodigo = null;
    private $origem = 'nota_fiscal_devolucao';
    private $submenuTela = '';
    private $idNfpOrigem = 0;
    private $qtdeDevolver = '';
    private $unitario = '';
    private $cfop = '';
    private $itens = '[]';
    private $cabecalho = '{}';

    public function setIdNfOrigem($idNfOrigem)
    {
        $this->idNfOrigem = (int) $idNfOrigem;
    }

    public function getIdNfOrigem()
    {
        return $this->idNfOrigem;
    }

    public function setIdNfDev($idNfDev)
    {
        $this->idNfDev = (int) $idNfDev;
    }

    public function getIdNfDev()
    {
        return $this->idNfDev;
    }

    public function setIdNatop($idNatop)
    {
        $this->idNatop = (int) $idNatop;
    }

    public function getIdNatop()
    {
        return $this->idNatop;
    }

    public function setIdPessoa($idPessoa)
    {
        $this->idPessoa = (int) $idPessoa;
    }

    public function getIdPessoa()
    {
        return $this->idPessoa;
    }

    public function setCodProduto($codProduto)
    {
        $this->codProduto = (int) $codProduto;
    }

    public function getCodProduto()
    {
        return $this->codProduto;
    }

    public function setCenarioCodigo($cenarioCodigo)
    {
        $this->cenarioCodigo = $cenarioCodigo;
    }

    public function getCenarioCodigo()
    {
        return $this->cenarioCodigo;
    }

    public function setOrigem($origem)
    {
        $this->origem = $origem;
    }

    public function getOrigem()
    {
        return $this->origem;
    }

    public function setSubmenuTela($submenuTela)
    {
        $this->submenuTela = $submenuTela;
    }

    public function getSubmenuTela()
    {
        return $this->submenuTela;
    }

    public function setIdNfpOrigem($idNfpOrigem)
    {
        $this->idNfpOrigem = (int) $idNfpOrigem;
    }

    public function getIdNfpOrigem()
    {
        return $this->idNfpOrigem;
    }

    public function setQtdeDevolver($qtdeDevolver)
    {
        $this->qtdeDevolver = $qtdeDevolver;
    }

    public function getQtdeDevolver()
    {
        return $this->qtdeDevolver;
    }

    public function setUnitario($unitario)
    {
        $this->unitario = $unitario;
    }

    public function getUnitario()
    {
        return $this->unitario;
    }

    public function setCfop($cfop)
    {
        $this->cfop = $cfop;
    }

    public function getCfop()
    {
        return $this->cfop;
    }

    public function setItens($itens)
    {
        $this->itens = $itens;
    }

    public function getItens()
    {
        return $this->itens;
    }

    public function setCabecalho($cabecalho)
    {
        $this->cabecalho = $cabecalho;
    }

    public function getCabecalho()
    {
        return $this->cabecalho;
    }

    private function converterDataHoraBd($dataHora): string
    {
        if ($dataHora === null || $dataHora === '') {
            return date('Y-m-d H:i:s');
        }

        $dataHora = trim((string) $dataHora);

        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $dataHora)) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $dataHora)
                ?: DateTime::createFromFormat('Y-m-d H:i', $dataHora);
            if ($dt instanceof DateTime) {
                return $dt->format('Y-m-d H:i:s');
            }
            return $dataHora;
        }

        if (strpos($dataHora, '/') !== false) {
            $dt = DateTime::createFromFormat('d/m/Y H:i:s', $dataHora)
                ?: DateTime::createFromFormat('d/m/Y H:i', $dataHora)
                ?: DateTime::createFromFormat('d/m/Y', $dataHora);
            if ($dt instanceof DateTime) {
                return $dt->format('Y-m-d H:i:s');
            }
        }

        $ts = strtotime(str_replace('/', '-', $dataHora));
        if ($ts !== false) {
            return date('Y-m-d H:i:s', $ts);
        }

        return date('Y-m-d H:i:s');
    }

    private function buscarNotaFiscalPorId(int $id): ?array
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT en.*, fc.gera_boleto_automatico AS gera_boleto_automatico '
            . 'FROM EST_NOTA_FISCAL en '
            . 'INNER JOIN FIN_CLIENTE fc ON fc.CLIENTE = en.PESSOA '
            . 'WHERE en.ID = :id');
        $banco->execute([':id' => $id]);
        $row = $banco->fetch();
        return $row ?: null;
    }

    private function buscarClientePorId(int $id): ?array
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT * FROM FIN_CLIENTE WHERE CLIENTE = :id');
        $banco->execute([':id' => $id]);
        $row = $banco->fetch();
        return $row ?: null;
    }

    private function buscarProdutosPorIdNf(int $idNf, bool $comJoinProduto = false): array
    {
        if ($comJoinProduto) {
            $sql = 'SELECT DISTINCT N.*, P.CODIGOBARRAS, P.CODPRODUTOANVISA, P.CODFABRICANTE, P.CCLASSTRIB '
                . 'FROM EST_NOTA_FISCAL_PRODUTO N '
                . 'LEFT JOIN EST_PRODUTO P ON N.CODPRODUTO = P.CODIGO '
                . 'WHERE N.IDNF = :idnf';
        } else {
            $sql = 'SELECT * FROM EST_NOTA_FISCAL_PRODUTO WHERE IDNF = :idnf';
        }
        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->execute([':idnf' => $idNf]);
        $rows = $banco->fetchAll();
        return is_array($rows) ? $rows : [];
    }

    private function buscarProdutoPorId(int $id): ?array
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT * FROM EST_NOTA_FISCAL_PRODUTO WHERE ID = :id');
        $banco->execute([':id' => $id]);
        $row = $banco->fetch();
        return $row ?: null;
    }

    private function inserirRegistroPdo(c_banco_pdo $banco, string $tabela, array $dados): void
    {
        unset($dados['ID']);
        if (empty($dados)) {
            return;
        }
        $cols = array_keys($dados);
        $placeholders = array_map(function ($col) {
            return ':' . $col;
        }, $cols);
        $sql = 'INSERT INTO ' . $tabela . ' (' . implode(', ', $cols) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $params = [];
        foreach ($dados as $col => $valor) {
            $params[':' . $col] = $valor;
        }
        $banco->prepare($sql);
        $banco->execute($params);
    }

    private function prepararItemParaGravacao(array $item, int $idNfDev): array
    {
        $item['IDNF'] = $idNfDev;
        unset($item['ID']);
        foreach (['CODIGOBARRAS', 'CODPRODUTOANVISA', 'CODFABRICANTE', 'CCLASSTRIB'] as $campoExtra) {
            unset($item[$campoExtra]);
        }
        $item = $this->normalizarTribIcmsDevolucao($item);
        $item['DATACONFERENCIA'] = date('Y-m-d H:i:s');
        if (empty($item['CODIGONOTA'])) {
            $item['CODIGONOTA'] = $item['CODPRODUTO'] ?? null;
        }

        return $item;
    }

    private function inserirNotaFiscalDevolucaoPdo(c_banco_pdo $banco, array $cabecalho, array $nfOrig, array $cenario, int $idNfOrigem, array $overrides = []): int
    {
        $emissao = $this->converterDataHoraBd($cabecalho['emissao'] ?? date('d/m/Y H:i'));
        $chnfe = $overrides['chnfe'] ?? ($nfOrig['CHNFE'] ?? '');
        $obs = $overrides['obs'] ?? ('DEVOLUÇÃO NF |' . $idNfOrigem . '| ' . date('d/m/Y H:i'));
        $doc = $overrides['doc'] ?? $idNfOrigem;
        $pessoa = (int) ($overrides['pessoa'] ?? ($nfOrig['PESSOA'] ?? 0));
        $tpCredito = $overrides['tpNFCredito'] ?? ($cabecalho['tpNFCredito'] ?? null);
        $tpDebito  = $overrides['tpNFDebito']  ?? ($cabecalho['tpNFDebito']  ?? null);
        // UF do destinatário: usa override se fornecido; senão consulta FIN_CLIENTE
        if (array_key_exists('uf', $overrides)) {
            $ufDestinatario = strtoupper(trim((string) $overrides['uf']));
        } else {
            $pessoaRowUf = $pessoa > 0 ? ($this->buscarClientePorId($pessoa) ?? []) : [];
            $ufDestinatario = strtoupper(trim((string) ($pessoaRowUf['UF'] ?? '')));
        }
        $params = [
            ':modelo' => 55,
            ':serie' => $nfOrig['SERIE'] ?? 1,
            ':numero' => 0,
            ':pessoa' => $pessoa,
            ':cpfnota' => $nfOrig['CPFNOTA'] ?? '',
            ':emissao' => $emissao,
            ':idnatop' => (int) ($cabecalho['idNatop'] ?? $nfOrig['IDNATOP'] ?? 0),
            ':natoperacao' => $this->resolverNatOperacao((int) ($cabecalho['idNatop'] ?? $nfOrig['IDNATOP'] ?? 0)),
            ':tipo' => $nfOrig['TIPO'],
            ':situacao' => 'A',
            ':formapgto' => 0,
            ':condpgto' => (int) ($nfOrig['CONDPGTO'] ?? 0),
            ':datasaidaentrada' => $emissao,
            ':formaemissao' => 'N',
            ':finalidadeemissao' => (int) ($cabecalho['finNFe'] ?? 4),
            ':nfereferenciada' => $chnfe,
            ':centrocusto' => (int) ($cabecalho['centroCusto'] ?? $this->m_empresacentrocusto),
            ':genero' => $nfOrig['GENERO'] ?? '',
            ':modfrete' => $nfOrig['MODFRETE'] ?? '9',
            ':transportador' => (int) ($nfOrig['TRANSPORTADOR'] ?? 0),
            ':placaveiculo' => $nfOrig['PLACAVEICULO'] ?? '',
            ':codantt' => $nfOrig['CODANTT'] ?? '',
            ':uf' => $ufDestinatario,
            ':volume' => (int) ($nfOrig['VOLUME'] ?? 0),
            ':volespecie' => $nfOrig['VOLESPECIE'] ?? '',
            ':volmarca' => $nfOrig['VOLMARCA'] ?? '',
            ':volpesoliq' => (int) ($nfOrig['VOLPESOLIQ'] ?? 0),
            ':volpesobruto' => (int) ($nfOrig['VOLPESOBRUTO'] ?? 0),
            ':totalnf' => 0,
            ':origem' => 'NFE',
            ':doc' => $doc,
            ':obs' => $obs,
            ':frete' => 0,
            ':despacessorias' => 0,
            ':seguro' => 0,
            ':dhrecbto' => null,
            ':nprot' => null,
            ':digval' => null,
            ':veraplic' => null,
            ':vendapresencial' => $nfOrig['VENDAPRESENCIAL'] ?? 'N',
            ':contrato' => $nfOrig['CONTRATO'] ?? '',
            ':userinsert' => (int) $this->m_userid,
            ':dateinsert' => date('Y-m-d H:i:s'),
            ':ibscbstipocredito' => ($tpCredito !== '' && $tpCredito !== null) ? (string) $tpCredito : null,
            ':ibscbstipodebito'  => ($tpDebito  !== '' && $tpDebito  !== null) ? (string) $tpDebito  : null,
        ];

        $sql = 'INSERT INTO EST_NOTA_FISCAL ('
            . 'MODELO, SERIE, NUMERO, PESSOA, CPFNOTA, EMISSAO, IDNATOP, NATOPERACAO, TIPO, SITUACAO, FORMAPGTO, CONDPGTO, '
            . 'DATASAIDAENTRADA, FORMAEMISSAO, FINALIDADEEMISSAO, NFEREFERENCIADA, CENTROCUSTO, GENERO, '
            . 'MODFRETE, TRANSPORTADOR, PLACAVEICULO, CODANTT, UF, VOLUME, VOLESPECIE, VOLMARCA, VOLPESOLIQ, VOLPESOBRUTO, '
            . 'TOTALNF, ORIGEM, DOC, OBS, FRETE, DESPACESSORIAS, SEGURO, DHRECBTO, NPROT, DIGVAL, VERAPLIC, VENDAPRESENCIAL, CONTRATO, USERINSERT, DATEINSERT, '
            . 'IBS_CBS_TIPO_CREDITO, IBS_CBS_TIPO_DEBITO'
            . ') VALUES ('
            . ':modelo, :serie, :numero, :pessoa, :cpfnota, :emissao, :idnatop, :natoperacao, :tipo, :situacao, :formapgto, :condpgto, '
            . ':datasaidaentrada, :formaemissao, :finalidadeemissao, :nfereferenciada, :centrocusto, :genero, '
            . ':modfrete, :transportador, :placaveiculo, :codantt, :uf, :volume, :volespecie, :volmarca, :volpesoliq, :volpesobruto, '
            . ':totalnf, :origem, :doc, :obs, :frete, :despacessorias, :seguro, :dhrecbto, :nprot, :digval, :veraplic, :vendapresencial, :contrato, :userinsert, :dateinsert, '
            . ':ibscbstipocredito, :ibscbstipodebito'
            . ')';

        $banco->prepare($sql);
        $banco->execute($params);
        return (int) $banco->lastInsertId();
    }

    private function contextoManual($idNfDev = null, $cenarioCodigo = null)
    {
        $arrDevRow = null;
        $idNatopRascunho = null;
        $emissaoRascunho = null;
        $codigo = $cenarioCodigo ?: 'DEVOLUCAO_VENDA';
        $tipoDevolucao = ($codigo === 'DEVOLUCAO_COMPRA') ? '1' : '0';
        $cenario = [
            'codigo' => $codigo,
            'tipoDevolucao' => $tipoDevolucao,
            'natOpTipo' => ($tipoDevolucao === '0') ? 'E' : 'S',
        ];
        $pessoaId = 0;
        $chnfe = '';
        $nfNumero = '';
        $nfSerie = '';

        if ($idNfDev) {
            $arrDevRow = $this->buscarNotaFiscalPorId((int) $idNfDev);
            if (empty($arrDevRow)) {
                return ['ok' => false, 'erro' => 'NF de devolução não encontrada.'];
            }
            $codigo = ((string) ($arrDevRow['TIPO'] ?? '0') === '0') ? 'DEVOLUCAO_VENDA' : 'DEVOLUCAO_COMPRA';
            $tipoDevolucao = ($codigo === 'DEVOLUCAO_COMPRA') ? '1' : '0';
            $cenario = [
                'codigo' => $codigo,
                'tipoDevolucao' => $tipoDevolucao,
                'natOpTipo' => ($tipoDevolucao === '0') ? 'E' : 'S',
            ];
            $pessoaId = (int) ($arrDevRow['PESSOA'] ?? 0);
            $chnfe = trim((string) ($arrDevRow['NFEREFERENCIADA'] ?? ''));
            $idNatopRascunho = (int) ($arrDevRow['IDNATOP'] ?? 0) ?: null;
            $emissaoRascunho = $arrDevRow['EMISSAO'] ?? null;
            if (preg_match('/NF\s+(\d+)\s*\/\s*S[ée]rie\s+(\S+)/iu', (string) ($arrDevRow['OBS'] ?? ''), $m)) {
                $nfNumero = $m[1];
                $nfSerie = $m[2];
            }
        }

        $pessoaRow = $pessoaId > 0 ? ($this->buscarClientePorId($pessoaId) ?? []) : [];
        $transporteNf = !empty($arrDevRow) ? $arrDevRow : ['MODFRETE' => '9', 'TRANSPORTADOR' => 0];
        $transporte = $this->montarTransporteNf($transporteNf);
        $finNFeCombo = $this->comboFinalidadeEmissao();

        return [
            'ok' => true,
            'manual' => true,
            'idNfOrigem' => null,
            'idNfDev' => $idNfDev ? (int) $idNfDev : null,
            'idNatop' => $idNatopRascunho,
            'cenario' => $cenario,
            'cenarioCodigo' => $codigo,
            'nfOrigem' => [
                'numero' => $nfNumero,
                'serie' => $nfSerie,
                'chnfe' => $chnfe,
            ],
            'pessoa' => [
                'id' => $pessoaId,
                'nome' => $pessoaRow['NOME'] ?? $pessoaRow['NOMEREDUZIDO'] ?? '',
                'uf' => $pessoaRow['UF'] ?? '',
                'tipo' => $pessoaRow['TIPO'] ?? 'J',
            ],
            'transporte' => $transporte,
            'financeiro' => $this->montarCabecalhoFinanceiro($transporteNf, 0, $arrDevRow),
            'centroCusto' => $this->m_empresacentrocusto,
            'finNFe' => $arrDevRow ? (int) ($arrDevRow['FINALIDADEEMISSAO'] ?? 0) : '',
            'finNFeCombo' => $finNFeCombo,
            'natOps' => $this->listarNatOpDevolucao(),
            'emissao' => $emissaoRascunho ?: date('d/m/Y H:i'),
            'rascunhoGravado' => $idNfDev && !empty($arrDevRow) && (float) c_tools::parseMoedaValor($arrDevRow['TOTALNF'] ?? 0) > 0,
        ];
    }

    private function buscarProdutoCatalogo(int $codProduto): ?array
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT * FROM EST_PRODUTO WHERE CODIGO = :cod');
        $banco->execute([':cod' => $codProduto]);
        $row = $banco->fetch();
        return $row ?: null;
    }

    public function buscarProdutoManual($codProduto, $idNatop, $cenarioCodigo, $idPessoa = 0)
    {
        $produto = $this->buscarProdutoCatalogo((int) $codProduto);
        if (empty($produto)) {
            return ['ok' => false, 'erro' => 'Produto não encontrado.'];
        }

        $codigo = $cenarioCodigo ?: 'DEVOLUCAO_VENDA';
        $tipoDevolucao = ($codigo === 'DEVOLUCAO_COMPRA') ? '1' : '0';
        $cenario = [
            'codigo' => $codigo,
            'tipoDevolucao' => $tipoDevolucao,
            'natOpTipo' => ($tipoDevolucao === '0') ? 'E' : 'S',
        ];
        $pessoaRow = $this->buscarClientePorId((int) $idPessoa) ?? [];
        $contexto = [
            'ok' => true,
            'manual' => true,
            'cenario' => $cenario,
            'centroCusto' => $this->m_empresacentrocusto,
            'pessoa' => [
                'uf' => $pessoaRow['UF'] ?? '',
                'tipo' => $pessoaRow['TIPO'] ?? 'J',
            ],
            'ufEmpresa' => $this->obterUfEmpresa(),
        ];

        $qtde = 1.0;
        $unit = c_tools::parseMoedaValor($produto['VENDA'] ?? $produto['CUSTOCOMPRA'] ?? 0);
        $itemBase = [
            'CODPRODUTO' => $produto['CODIGO'],
            'DESCRICAO' => $produto['DESCRICAO'],
            'UNIDADE' => $produto['UNIDADE'],
            'NCM' => $produto['NCM'] ?? '',
            'CEST' => $produto['CEST'] ?? '',
            'ORIGEM' => $produto['ORIGEM'] ?? '0',
            'TRIBICMS' => $produto['TRIBICMS'] ?? '',
            'QUANT' => $qtde,
            'UNITARIO' => $unit,
            'DESCONTO' => 0,
            'TOTAL' => round($qtde * $unit, 2),
            'CFOP' => '',
            'FRETE' => 0,
            'DESPACESSORIAS' => 0,
            'ALIQICMS' => $produto['ALIQICMS'] ?? 0,
            'ALIQIPI' => $produto['ALIQIPI'] ?? 0,
        ];
        $cfop = $this->resolverCfopItem((int) $idNatop, $itemBase, $contexto);

        return [
            'ok' => true,
            'item' => [
                'manual' => true,
                'codProduto' => (int) $produto['CODIGO'],
                'descricao' => $produto['DESCRICAO'],
                'unidade' => $produto['UNIDADE'],
                'ncm' => $produto['NCM'] ?? '',
                'qtdeOriginal' => 0,
                'qtdeDevolver' => 1,
                'unitario' => c_tools::parseMoedaValor($produto['VENDA'] ?? $produto['CUSTOCOMPRA'] ?? 0),
                'cfop' => $cfop,
                'selecionado' => true,
            ],
        ];
    }

    public function buscarItensManual($idNfDev = null)
    {
        $itensNf = $this->buscarProdutosPorIdNf((int) $idNfDev, false);
        $resultado = [];
        $nItemSeq = 0;
        foreach ($itensNf as $item) {
            $nItemSeq++;
            $qtde = c_tools::parseMoedaValor($item['QUANT'] ?? 0);
            $resultado[] = [
                'manual' => true,
                'codProduto' => (int) ($item['CODPRODUTO'] ?? 0),
                'descricao' => $item['DESCRICAO'] ?? '',
                'ncm' => $item['NCM'] ?? '',
                'unidade' => $item['UNIDADE'] ?? '',
                'qtdeOriginal' => 0,
                'qtdeDevolver' => $qtde,
                'unitario' => c_tools::parseMoedaValor($item['UNITARIO'] ?? 0),
                'cfop' => $item['CFOP'] ?? '',
                'selecionado' => true,
                'nItem' => $nItemSeq,
                'chaveRef' => '',
            ];
        }
        return $resultado;
    }

    private function contarProdutosNf($idNf)
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT COUNT(*) AS QTD FROM EST_NOTA_FISCAL_PRODUTO WHERE IDNF = :idnf');
        $banco->execute([':idnf' => (int) $idNf]);
        $row = $banco->fetch();
        return (int) ($row['QTD'] ?? 0);
    }

    /**
     * Quando existem NFs duplicadas (mesma CHNFE/número), prioriza a autorizada com produtos.
     */
    private function resolverIdNfOrigemPorChnfe($chnfe)
    {
        $chnfe = trim((string) $chnfe);
        if ($chnfe === '') {
            return 0;
        }

        $banco = new c_banco_pdo();
        $banco->prepare(
            'SELECT N.ID FROM EST_NOTA_FISCAL N '
                . 'INNER JOIN EST_NOTA_FISCAL_PRODUTO P ON P.IDNF = N.ID '
                . 'WHERE N.CHNFE = :chnfe '
                . 'AND COALESCE(N.FINALIDADEEMISSAO, 1) <> 4 '
                . 'GROUP BY N.ID '
                . 'ORDER BY (N.SITUACAO = \'B\') DESC, N.ID ASC '
                . 'LIMIT 1'
        );
        $banco->execute([':chnfe' => $chnfe]);
        $row = $banco->fetch();
        return (int) ($row['ID'] ?? 0);
    }

    private function resolverIdNfOrigemPorNumero($numero)
    {
        $numero = trim((string) $numero);
        if ($numero === '') {
            return 0;
        }

        $banco = new c_banco_pdo();
        $banco->prepare(
            'SELECT N.ID FROM EST_NOTA_FISCAL N '
                . 'INNER JOIN EST_NOTA_FISCAL_PRODUTO P ON P.IDNF = N.ID '
                . 'WHERE N.NUMERO = :num AND COALESCE(N.FINALIDADEEMISSAO, 1) <> 4 '
                . 'GROUP BY N.ID '
                . 'ORDER BY (N.SITUACAO = \'B\') DESC, N.ID ASC '
                . 'LIMIT 1'
        );
        $banco->execute([':num' => $numero]);
        $row = $banco->fetch();
        return (int) ($row['ID'] ?? 0);
    }

    public function resolverIdNfOrigemCanonica($idNfOrigem)
    {
        $idNfOrigem = (int) $idNfOrigem;
        if ($this->contarProdutosNf($idNfOrigem) > 0) {
            return $idNfOrigem;
        }

        $arr = $this->buscarNotaFiscalPorId($idNfOrigem);
        if (empty($arr)) {
            return $idNfOrigem;
        }

        $chnfe = trim((string) ($arr['CHNFE'] ?? ''));
        if ($chnfe === '') {
            return $idNfOrigem;
        }

        $canonico = $this->resolverIdNfOrigemPorChnfe($chnfe);
        return $canonico > 0 ? $canonico : $idNfOrigem;
    }

    public function validarNfOrigem($idNfOrigem)
    {
        $id = $this->resolverIdNfOrigemCanonica((int) $idNfOrigem);

        $row = $this->buscarNotaFiscalPorId($id);
        if (empty($row)) {
            return ['ok' => false, 'erro' => 'NF de origem não encontrada.'];
        }

        if (empty($row['CHNFE'])) {
            return ['ok' => false, 'erro' => 'A NF de origem precisa estar autorizada (CHNFE preenchido).'];
        }

        if ($this->contarProdutosNf($id) <= 0) {
            return [
                'ok' => false,
                'erro' => 'Nenhum produto na NF de origem nº ' . ($row['NUMERO'] ?? $id) . ' (ID ' . $id . ').',
            ];
        }

        $tipo = (string) ($row['TIPO'] ?? '');
        if ($tipo !== '0' && $tipo !== '1') {
            return ['ok' => false, 'erro' => 'Tipo de NF de origem não suportado para devolução.'];
        }

        $codigo = ($tipo === '1') ? 'DEVOLUCAO_VENDA' : 'DEVOLUCAO_COMPRA';
        $tipoDevolucao = ($codigo === 'DEVOLUCAO_COMPRA') ? '1' : '0';
        $cenario = [
            'codigo' => $codigo,
            'tipoDevolucao' => $tipoDevolucao,
            'natOpTipo' => ($tipoDevolucao === '0') ? 'E' : 'S',
        ];

        return ['ok' => true, 'nf' => $row, 'cenario' => $cenario, 'idNfOrigem' => $id];
    }

    public function obterRegimeTributarioEmpresa($centroCusto = null): string
    {
        $cc = $centroCusto ?: $this->m_empresacentrocusto;
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT REGIMETRIBUTARIO FROM AMB_EMPRESA WHERE CENTROCUSTO = :cc LIMIT 1');
        $banco->execute([':cc' => $cc]);
        $row = $banco->fetch();
        return (string) ($row['REGIMETRIBUTARIO'] ?? '3');
    }

    /**
     * Ajusta TRIBICMS da devolução conforme CRT do emitente (p_nfephp_40).
     * CRT 1: CSOSN (tagICMSSN).
     * CRT 2 e 3: CST ICMS (tagICMS).
     */
    public function normalizarTribIcmsDevolucao(array $item, $crt = null): array
    {
        $crt = (string) ($crt ?: $this->obterRegimeTributarioEmpresa());
        $trib = trim((string) ($item['TRIBICMS'] ?? ''));

        if ($crt === '1') {
            if ($trib !== '' && in_array($trib, ['101', '102', '103', '201', '202', '203', '300', '400', '500', '900'], true)) {
                return $item;
            }

            $cst = str_pad(preg_replace('/\D/', '', $trib), 2, '0', STR_PAD_LEFT);
            $stRetido = c_tools::parseMoedaValor($item['VALORICMSSTRETIDO'] ?? 0) > 0
                || c_tools::parseMoedaValor($item['VALORBCSTRETIDO'] ?? 0) > 0;
            $stCobrado = c_tools::parseMoedaValor($item['VALORICMSST'] ?? 0) > 0
                || c_tools::parseMoedaValor($item['VALORBCST'] ?? 0) > 0;

            if ($cst === '60' || $stRetido) {
                $item['TRIBICMS'] = '500';
            } elseif ($stCobrado || in_array($cst, ['10', '30', '70'], true)) {
                $item['TRIBICMS'] = (c_tools::parseMoedaValor($item['VALORICMS'] ?? 0) <= 0) ? '202' : '201';
            } elseif (in_array($cst, ['40', '41', '50'], true)) {
                $item['TRIBICMS'] = '400';
            } elseif (in_array($cst, ['00', '20', '51'], true) && c_tools::parseMoedaValor($item['VCREDICMSSN'] ?? 0) > 0) {
                $item['TRIBICMS'] = '101';
            } else {
                $item['TRIBICMS'] = '900';
            }

            return $item;
        }

        if ($crt !== '2' && $crt !== '3') {
            return $item;
        }

        $csosnLista = ['101', '102', '103', '201', '202', '203', '300', '400', '500', '900'];
        $cst = str_pad(preg_replace('/\D/', '', $trib), 2, '0', STR_PAD_LEFT);
        if (in_array($cst, ['00', '10', '20', '30', '40', '41', '50', '51', '60', '70', '90'], true)) {
            $item['TRIBICMS'] = $cst;
            return $item;
        }

        $stRetido = c_tools::parseMoedaValor($item['VALORICMSSTRETIDO'] ?? 0) > 0
            || c_tools::parseMoedaValor($item['VALORBCSTRETIDO'] ?? 0) > 0;
        $stCobrado = c_tools::parseMoedaValor($item['VALORICMSST'] ?? 0) > 0
            || c_tools::parseMoedaValor($item['VALORBCST'] ?? 0) > 0;
        $icms = c_tools::parseMoedaValor($item['VALORICMS'] ?? 0);

        if (($trib !== '' && in_array($trib, $csosnLista, true)) || $trib === '500') {
            $csosn = trim($trib);
            if ($csosn === '500' || $stRetido) {
                $item['TRIBICMS'] = '60';
            } elseif (in_array($csosn, ['201', '202', '203'], true)) {
                $item['TRIBICMS'] = ($stCobrado && $icms <= 0) ? '30' : '10';
            } elseif (in_array($csosn, ['101', '201'], true) && $icms > 0) {
                $item['TRIBICMS'] = '00';
            } elseif (in_array($csosn, ['102', '103', '300', '400'], true)) {
                $item['TRIBICMS'] = '40';
            }
            if (!empty($item['TRIBICMS'])) {
                return $item;
            }
        }

        $redBc = c_tools::parseMoedaValor($item['PERCREDUCAOBC'] ?? 0) > 0;
        $diferido = c_tools::parseMoedaValor($item['VALORICMSDIFERIDO'] ?? 0) > 0
            || c_tools::parseMoedaValor($item['PERCDIFERIDO'] ?? 0) > 0;

        if ($stRetido) {
            $item['TRIBICMS'] = '60';
        } elseif ($stCobrado && $icms <= 0) {
            $item['TRIBICMS'] = '30';
        } elseif ($stCobrado) {
            $item['TRIBICMS'] = '10';
        } elseif ($diferido) {
            $item['TRIBICMS'] = '51';
        } elseif ($redBc) {
            $item['TRIBICMS'] = '20';
        } elseif ($icms > 0) {
            $item['TRIBICMS'] = '00';
        } else {
            $item['TRIBICMS'] = '40';
        }

        return $item;
    }

    public function listarCombosTributacao($crt = null): array
    {
        $crt = (string) ($crt ?: $this->obterRegimeTributarioEmpresa());

        $ddm = function (string $campo, string $order = 'TIPO') {
            $banco = new c_banco_pdo();
            $banco->prepare("SELECT TIPO AS ID, PADRAO AS LABEL FROM AMB_DDM WHERE ALIAS = 'FAT_MENU' AND CAMPO = :campo ORDER BY {$order} ASC");
            $banco->execute([':campo' => $campo]);
            $rows = $banco->fetchAll();
            return is_array($rows) ? $rows : [];
        };

        $comOpcaoVazia = function (array $itens, string $rotulo = 'Selecione uma opção') {
            $itens[] = ['ID' => '', 'LABEL' => $rotulo];
            return $itens;
        };

        if ($crt === '1') {
            $tribIcms = $comOpcaoVazia($ddm('csosn'));
        } else {
            $tribIcms = $comOpcaoVazia($ddm('TributacaoIcms'));
        }

        return [
            'tribIcms' => $tribIcms,
            'origem' => $ddm('OrigemMercadoria'),
            'modBc' => $ddm('ModBc'),
            'modBcSt' => $comOpcaoVazia($ddm('ModBcSt')),
            'cstIpi' => $comOpcaoVazia($ddm('CSTIPI')),
            'pisCofins' => $comOpcaoVazia($ddm('PISCOFINS')),
            'regimeTributario' => $crt,
            'usaCsosn' => ($crt === '1'),
            'usaCst' => ($crt === '2' || $crt === '3'),
        ];
    }

    public function getComboModFrete(): array
    {
        return $this->comboModFrete();
    }

    public function getComboFinalidadeEmissao(): array
    {
        return $this->comboFinalidadeEmissao();
    }

    private function resolverNatOperacao(int $idNatop): string
    {
        if ($idNatop <= 0) {
            return '';
        }
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT NATOPERACAO FROM EST_NAT_OP WHERE ID = :id');
        $banco->execute([':id' => $idNatop]);
        $row = $banco->fetch();
        return trim((string) ($row['NATOPERACAO'] ?? ''));
    }

    private function montarTransporteNf(array $nf): array
    {
        return [
            'modFrete' => (string) ($nf['MODFRETE'] ?? '9'),
            'transportador' => '',
            'transportadorNome' => '',
            'placaVeiculo' => '',
            'codAntt' => '',
            'uf' => $nf['UF'] ?? '',
            'volume' => (int) ($nf['VOLUME'] ?? 0),
            'volEspecie' => $nf['VOLESPECIE'] ?? '',
            'volMarca' => $nf['VOLMARCA'] ?? '',
            'volPesoLiq' => (int) ($nf['VOLPESOLIQ'] ?? 0),
            'volPesoBruto' => (int) ($nf['VOLPESOBRUTO'] ?? 0),
        ];
    }

    private function aplicarTransporteCabecalhoEmNfOrig(array &$nfOrig, array $cabecalho): void
    {
        if (array_key_exists('modFrete', $cabecalho)) {
            $nfOrig['MODFRETE'] = (string) $cabecalho['modFrete'];
        }
        if (array_key_exists('transportador', $cabecalho)) {
            $nfOrig['TRANSPORTADOR'] = (int) $cabecalho['transportador'];
        }
        if (array_key_exists('placaVeiculo', $cabecalho)) {
            $nfOrig['PLACAVEICULO'] = trim((string) $cabecalho['placaVeiculo']);
        }
        if (array_key_exists('codAntt', $cabecalho)) {
            $nfOrig['CODANTT'] = trim((string) $cabecalho['codAntt']);
        }
        if (array_key_exists('uf', $cabecalho)) {
            $nfOrig['UF'] = strtoupper(substr(trim((string) $cabecalho['uf']), 0, 2));
        }
        if (array_key_exists('volume', $cabecalho)) {
            $nfOrig['VOLUME'] = (int) $cabecalho['volume'];
        }
        if (array_key_exists('volEspecie', $cabecalho)) {
            $nfOrig['VOLESPECIE'] = trim((string) $cabecalho['volEspecie']);
        }
        if (array_key_exists('volMarca', $cabecalho)) {
            $nfOrig['VOLMARCA'] = trim((string) $cabecalho['volMarca']);
        }
        if (array_key_exists('volPesoLiq', $cabecalho)) {
            $nfOrig['VOLPESOLIQ'] = (int) $cabecalho['volPesoLiq'];
        }
        if (array_key_exists('volPesoBruto', $cabecalho)) {
            $nfOrig['VOLPESOBRUTO'] = (int) $cabecalho['volPesoBruto'];
        }
    }

    private function paramsTransporteCabecalho(array $cabecalho): array
    {
        return [
            ':modfrete' => (string) ($cabecalho['modFrete'] ?? '9'),
            ':transportador' => (int) ($cabecalho['transportador'] ?? 0),
            ':placaveiculo' => trim((string) ($cabecalho['placaVeiculo'] ?? '')),
            ':codantt' => trim((string) ($cabecalho['codAntt'] ?? '')),
            ':uf' => strtoupper(substr(trim((string) ($cabecalho['uf'] ?? '')), 0, 2)),
            ':volume' => (int) ($cabecalho['volume'] ?? 0),
            ':volespecie' => trim((string) ($cabecalho['volEspecie'] ?? '')),
            ':volmarca' => trim((string) ($cabecalho['volMarca'] ?? '')),
            ':volpesoliq' => (int) ($cabecalho['volPesoLiq'] ?? 0),
            ':volpesobruto' => (int) ($cabecalho['volPesoBruto'] ?? 0),
        ];
    }

    private function montarCabecalhoFinanceiro(array $nf, $idNfOrigem = 0, ?array $arrDevRow = null): array
    {
        $src = !empty($arrDevRow) ? $arrDevRow : $nf;
        $obs = trim((string) ($src['OBS'] ?? ''));
        if ($obs === '' && $idNfOrigem > 0 && empty($arrDevRow)) {
            $obs = 'DEVOLUÇÃO NF |' . $idNfOrigem . '| ' . date('d/m/Y H:i');
        }

        return [
            'obs' => $obs,
            'frete' => c_tools::parseMoedaValor($src['FRETE'] ?? 0),
            'seguro' => c_tools::parseMoedaValor($src['SEGURO'] ?? 0),
            'despAcessorias' => c_tools::parseMoedaValor($src['DESPACESSORIAS'] ?? 0),
            'tpNFCredito' => (string) ($src['IBS_CBS_TIPO_CREDITO'] ?? ''),
            'tpNFDebito'  => (string) ($src['IBS_CBS_TIPO_DEBITO']  ?? ''),
        ];
    }

    private function paramsCabecalhoFinanceiro(array $cabecalho, $idNfOrigem = 0): array
    {
        $obs = trim((string) ($cabecalho['obs'] ?? ''));
        if ($obs === '' && $idNfOrigem > 0) {
            $obs = 'DEVOLUÇÃO NF |' . $idNfOrigem . '| ' . date('d/m/Y H:i');
        }

        return [
            ':frete' => round(c_tools::parseMoedaValor($cabecalho['frete'] ?? 0), 2),
            ':seguro' => round(c_tools::parseMoedaValor($cabecalho['seguro'] ?? 0), 2),
            ':desp' => round(c_tools::parseMoedaValor($cabecalho['despAcessorias'] ?? ($cabecalho['despacessorias'] ?? 0)), 2),
            ':obs' => $obs,
        ];
    }

    private function aplicarRateioCabecalho(int $idNfDev): void
    {
        if ($idNfDev <= 0) {
            return;
        }
        $objNfProd = new c_nota_fiscal_produto();
        $objNfProd->calculaRateios($idNfDev);
    }

    public function buscarContexto($idNfOrigem, $idNfDev = null, $manual = false, $cenarioCodigo = null)
    {
        if ($manual) {
            return $this->contextoManual($idNfDev, $cenarioCodigo);
        }

        $arrDevRow = null;
        $idNatopRascunho = null;
        $emissaoRascunho = null;

        if ($idNfDev) {
            $arrDevRow = $this->buscarNotaFiscalPorId((int) $idNfDev);
            if (empty($arrDevRow)) {
                return ['ok' => false, 'erro' => 'NF de devolução não encontrada.'];
            }
            if ((int) $idNfOrigem <= 0) {
                $obs = (string) ($arrDevRow['OBS'] ?? '');
                if ($obs !== '' && preg_match('/DEVOLU[ÇCÃ]AO NF\s*\|(\d+)/iu', $obs, $m)) {
                    $ref = (int) $m[1];
                    $canon = $this->resolverIdNfOrigemCanonica($ref);
                    if ($canon > 0 && $this->contarProdutosNf($canon) > 0) {
                        $idNfOrigem = $canon;
                    } else {
                        $idNfOrigem = $this->resolverIdNfOrigemPorNumero($ref);
                    }
                } else {
                    $chnfe = trim((string) ($arrDevRow['NFEREFERENCIADA'] ?? ''));
                    if ($chnfe !== '') {
                        $idNfOrigem = $this->resolverIdNfOrigemPorChnfe($chnfe);
                    }
                }
            }
            $idNatopRascunho = (int) ($arrDevRow['IDNATOP'] ?? 0) ?: null;
            $emissaoRascunho = $arrDevRow['EMISSAO'] ?? null;
        }

        $validacao = $this->validarNfOrigem((int) $idNfOrigem);
        if (!$validacao['ok']) {
            return $validacao;
        }

        $nf = $validacao['nf'];
        $cenario = $validacao['cenario'];

        $pessoaRow = $this->buscarClientePorId((int) $nf['PESSOA']) ?? [];

        $natOps = $this->listarNatOpDevolucao();
        $transporteNf = !empty($arrDevRow) ? $arrDevRow : $nf;
        $transporte = $this->montarTransporteNf($transporteNf);
        $finNFeCombo = $this->comboFinalidadeEmissao();

        return [
            'ok' => true,
            'idNfOrigem' => (int) ($validacao['idNfOrigem'] ?? $nf['ID']),
            'idNfDev' => $idNfDev ? (int) $idNfDev : null,
            'idNatop' => $idNatopRascunho,
            'cenario' => $cenario,
            'nfOrigem' => [
                'id' => (int) $nf['ID'],
                'numero' => $nf['NUMERO'],
                'serie' => $nf['SERIE'],
                'tipo' => $nf['TIPO'],
                'chnfe' => $nf['CHNFE'],
                'emissao' => $nf['EMISSAO'],
                'totalnf' => $nf['TOTALNF'],
            ],
            'pessoa' => [
                'id' => (int) $nf['PESSOA'],
                'nome' => $pessoaRow['NOME'] ?? $pessoaRow['NOMEREDUZIDO'] ?? '',
                'uf' => $pessoaRow['UF'] ?? '',
                'tipo' => $pessoaRow['TIPO'] ?? 'J',
            ],
            'transporte' => $transporte,
            'financeiro' => $this->montarCabecalhoFinanceiro($transporteNf, (int) ($validacao['idNfOrigem'] ?? $nf['ID']), $arrDevRow),
            'centroCusto' => $this->m_empresacentrocusto,
            'finNFe' => $arrDevRow ? (int) ($arrDevRow['FINALIDADEEMISSAO'] ?? 0) : '',
            'finNFeCombo' => $finNFeCombo,
            'natOps' => $natOps,
            'emissao' => $emissaoRascunho ?: date('d/m/Y H:i'),
            'rascunhoGravado' => $idNfDev && !empty($arrDevRow) && (float) c_tools::parseMoedaValor($arrDevRow['TOTALNF'] ?? 0) > 0,
        ];
    }

    public function listarNatOpDevolucao()
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT ID, NATOPERACAO AS DESCRICAO FROM EST_NAT_OP WHERE 1 = 1 ORDER BY NATOPERACAO');
        $banco->execute();
        $rows = $banco->fetchAll();
        return is_array($rows) ? $rows : [];
    }

    public function buscarItensOrigem($idNfOrigem, $idNfDev = null, $idNatopSelecionada = null)
    {
        $validacao = $this->validarNfOrigem((int) $idNfOrigem);
        if (!$validacao['ok']) {
            return [];
        }

        $idNfOrigem = (int) $validacao['idNfOrigem'];
        $itens = $this->buscarProdutosPorIdNf($idNfOrigem, true);
        if (count($itens) === 0) {
            return [];
        }

        $cenario = $validacao['cenario'];
        $contexto = $this->contextoCfopOrigem($validacao);

        $idNatOp = (int) $idNatopSelecionada;
        if ($idNatOp <= 0 && $idNfDev) {
            $arrDev = $this->buscarNotaFiscalPorId((int) $idNfDev);
            $idNatOp = (int) ($arrDev['IDNATOP'] ?? 0);
        }
        if ($idNatOp <= 0) {
            $natOps = $this->listarNatOpDevolucao();
            $idNatOp = (int) ($natOps[0]['ID'] ?? 0);
        }

        $itensRascunho = [];
        $temItensRascunho = false;
        if ($idNfDev) {
            foreach ($this->buscarProdutosPorIdNf((int) $idNfDev, false) as $devItem) {
                $cod = (string) ($devItem['CODPRODUTO'] ?? '');
                $itensRascunho[$cod] = $devItem;
            }
            $temItensRascunho = count($itensRascunho) > 0;
        }

        $chaveRefOrigem = trim((string) ($validacao['nf']['CHNFE'] ?? ''));
        $nItemSeq = 0;

        $resultado = [];
        foreach ($itens as $item) {
            $nItemSeq++;
            $qtdeOrig = c_tools::parseMoedaValor($item['QUANT'] ?? 0);
            $unitOrig = c_tools::parseMoedaValor($item['UNITARIO'] ?? 0);
            $codProduto = (string) ($item['CODPRODUTO'] ?? '');
            $devItem = $itensRascunho[$codProduto] ?? null;
            $selecionado = !$idNfDev || !$temItensRascunho || $devItem !== null;

            $cfopSugerido = $this->resolverCfopItem($idNatOp, $item, $contexto);

            $qtdeDev = $qtdeOrig;
            $unitDev = $unitOrig;
            $cfop = $cfopSugerido ?: ($item['CFOP'] ?? '');

            if ($devItem !== null) {
                $qtdeDev = c_tools::parseMoedaValor($devItem['QUANT'] ?? 0) ?: $qtdeOrig;
                $unitDev = c_tools::parseMoedaValor($devItem['UNITARIO'] ?? 0) ?: $unitOrig;
                $cfopDraft = trim((string) ($devItem['CFOP'] ?? ''));
                if ($cfopDraft !== '') {
                    $cfop = $cfopDraft;
                }
            }

            $resultado[] = [
                'idNfpOrigem' => (int) $item['ID'],
                'codProduto' => $item['CODPRODUTO'],
                'descricao' => $item['DESCRICAO'],
                'ncm' => $item['NCM'],
                'unidade' => $item['UNIDADE'],
                'qtdeOriginal' => $qtdeOrig,
                'qtdeDevolver' => $qtdeDev,
                'unitario' => $unitDev,
                'cfop' => $cfop,
                'cfopOriginal' => $item['CFOP'] ?? '',
                'cfopSugerido' => $cfopSugerido,
                'tribIcms' => $item['TRIBICMS'] ?? '',
                'origemFiscal' => $item['ORIGEM'] ?? '',
                'cenario' => $cenario['codigo'] ?? '',
                'selecionado' => $selecionado,
                'nItem' => $nItemSeq,
                'chaveRef' => $chaveRefOrigem,
            ];
        }
        return $resultado;
    }

    private function obterUfEmpresa()
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT UF FROM FIN_CLIENTE WHERE CLIENTE = :cliente');
        $banco->execute([':cliente' => (int) $this->m_empresacliente]);
        $row = $banco->fetch();
        return strtoupper(trim((string) ($row['UF'] ?? '')));
    }

    private function contextoCfopOrigem(array $validacao, $centroCusto = null): array
    {
        $nf = $validacao['nf'];
        $pessoaRow = $this->buscarClientePorId((int) ($nf['PESSOA'] ?? 0)) ?? [];

        return [
            'ok' => true,
            'cenario' => $validacao['cenario'],
            'centroCusto' => $centroCusto ?: $this->m_empresacentrocusto,
            'pessoa' => [
                'uf' => $pessoaRow['UF'] ?? '',
                'tipo' => $pessoaRow['TIPO'] ?? 'J',
            ],
            'ufEmpresa' => $this->obterUfEmpresa(),
        ];
    }

    private function mapaItensRascunho($idNfDev): array
    {
        if (!$idNfDev) {
            return [];
        }
        $mapa = [];
        foreach ($this->buscarProdutosPorIdNf((int) $idNfDev, false) as $item) {
            $mapa[(string) ($item['CODPRODUTO'] ?? '')] = $item;
        }
        return $mapa;
    }

    public function resolverCfopItem($idNatop, array $itemOrig, array $contexto)
    {
        $cenarioCodigo = $contexto['cenario']['codigo'] ?? '';

        if (!empty($idNatop)) {
            $dados = [
                'centroCusto' => $contexto['centroCusto'] ?? $this->m_empresacentrocusto,
                'naturezaOperacao' => $idNatop,
                'uf' => $contexto['pessoa']['uf'] ?? '',
                'tipoPessoa' => $contexto['pessoa']['tipo'] ?? 'J',
                'origem' => $itemOrig['ORIGEM'] ?? '',
                'tribIcms' => $itemOrig['TRIBICMS'] ?? '',
                'ncm' => $itemOrig['NCM'] ?? '',
                'cest' => $itemOrig['CEST'] ?? '',
                'produto' => $itemOrig['CODPRODUTO'] ?? 0,
            ];

            $motorTributos = new c_pedidoVendaNf();
            $tributos = $motorTributos->_buscaTributos($dados);
            if (is_array($tributos) && !empty($tributos[0]['CFOP'])) {
                return $tributos[0]['CFOP'];
            }
        }

        $cfopOrigem = $itemOrig['CFOP'] ?? '';
        $cfop = preg_replace('/\D/', '', (string) $cfopOrigem);
        if (strlen($cfop) < 4) {
            return (string) $cfopOrigem;
        }

        $ufPessoa = strtoupper(trim((string) ($contexto['pessoa']['uf'] ?? '')));
        $ufEmpresa = $contexto['ufEmpresa'] ?? $this->obterUfEmpresa();
        $mesmoEstado = ($ufPessoa !== '' && $ufEmpresa !== '' && $ufPessoa === $ufEmpresa);
        $digito = $cfop[0];

        if ($cenarioCodigo === 'DEVOLUCAO_VENDA' && in_array($digito, ['5', '6', '7'], true)) {
            $novoDigito = $mesmoEstado ? '1' : '2';
            return $novoDigito . substr($cfop, 1);
        }

        if ($cenarioCodigo === 'DEVOLUCAO_COMPRA' && in_array($digito, ['1', '2', '3'], true)) {
            $novoDigito = $mesmoEstado ? '5' : '6';
            return $novoDigito . substr($cfop, 1);
        }

        return (string) $cfopOrigem;
    }

    public function calcularProporcional(array $itemOrig, $qtdeDev, $vlrUni, $cfop = null)
    {
        $qtdeOrig = c_tools::parseMoedaValor($itemOrig['QUANT'] ?? 0);
        $qtdeDev = c_tools::parseMoedaValor($qtdeDev);
        if ($qtdeOrig <= 0) {
            $fator = $qtdeDev > 0 ? 1 : 0;
        } else {
            $fator = min(1, $qtdeDev / $qtdeOrig);
        }

        $vlrUni = c_tools::parseMoedaValor($vlrUni);
        $totalProduto = round($qtdeDev * $vlrUni, 2);

        $camposProporcionais = [
            'BCICMS',
            'VALORICMS',
            'BCIPI',
            'VALORIPI',
            'VALORBCST',
            'VALORICMSST',
            'BCPIS',
            'VALORPIS',
            'BCCOFINS',
            'VALORCOFINS',
            'FRETE',
            'DESPACESSORIAS',
            'VALORTOTALTRIBUTOS',
            'VALORICMSDIFERIDO',
            'VALORICMSOPERACAO',
            'VALORBCSTRETIDO',
            'VALORICMSSTRETIDO',
            'VICMSSUBSTITUTO',
            'VCREDICMSSN',
            'VALORFCPUFDEST',
            'VALORICMSUFDEST',
            'VALORICMSUFREMET',
            'BCFCPST',
            'VALORFCPST',
            'BCICMSUFDEST',
            'BCFCPUFDEST',
            'VALORFCPUFDEST',
        ];

        $calc = [
            'qtdeDev' => $qtdeDev,
            'qtdeOrig' => $qtdeOrig,
            'fator' => $fator,
            'unitario' => $vlrUni,
            'total' => $totalProduto,
            'cfop' => $cfop ?: ($itemOrig['CFOP'] ?? ''),
            'frete' => round(c_tools::parseMoedaValor($itemOrig['FRETE'] ?? 0) * $fator, 2),
            'despAcessorias' => round(c_tools::parseMoedaValor($itemOrig['DESPACESSORIAS'] ?? 0) * $fator, 2),
        ];

        foreach ($camposProporcionais as $campo) {
            $calc[$campo] = round(c_tools::parseMoedaValor($itemOrig[$campo] ?? 0) * $fator, 2);
        }

        return $calc;
    }

    private function aplicarOverridesTributos(array $calc, array $itemOrig, array $sel): array
    {
        $mapa = [
            'tribIcms' => ['col' => 'TRIBICMS', 'tipo' => 'texto'],
            'origem' => ['col' => 'ORIGEM', 'tipo' => 'texto'],
            'modBc' => ['col' => 'MODBC', 'tipo' => 'texto'],
            'bcIcms' => ['col' => 'BCICMS', 'tipo' => 'valor'],
            'aliqIcms' => ['col' => 'ALIQICMS', 'tipo' => 'aliq'],
            'valorIcms' => ['col' => 'VALORICMS', 'tipo' => 'valor'],
            'percReducaoBc' => ['col' => 'PERCREDUCAOBC', 'tipo' => 'aliq'],
            'valorIcmsOperacao' => ['col' => 'VALORICMSOPERACAO', 'tipo' => 'valor'],
            'percDiferido' => ['col' => 'PERCDIFERIDO', 'tipo' => 'aliq'],
            'valorIcmsDiferido' => ['col' => 'VALORICMSDIFERIDO', 'tipo' => 'valor'],
            'modBcSt' => ['col' => 'MODBCST', 'tipo' => 'texto'],
            'valorbcst' => ['col' => 'VALORBCST', 'tipo' => 'valor'],
            'aliqicmsst' => ['col' => 'ALIQICMSST', 'tipo' => 'aliq'],
            'percMvaSt' => ['col' => 'PERCMVAST', 'tipo' => 'aliq'],
            'percReducaoBcSt' => ['col' => 'PERCREDUCAOBCST', 'tipo' => 'aliq'],
            'valoricmsst' => ['col' => 'VALORICMSST', 'tipo' => 'valor'],
            'valorBaseCalculoStRetido' => ['col' => 'VALORBCSTRETIDO', 'tipo' => 'valor'],
            'valorIcmsStRetido' => ['col' => 'VALORICMSSTRETIDO', 'tipo' => 'valor'],
            'valorIcmsSubstituto' => ['col' => 'VICMSSUBSTITUTO', 'tipo' => 'valor'],
            'pCredSn' => ['col' => 'PCREDSN', 'tipo' => 'aliq'],
            'creditoSn' => ['col' => 'VCREDICMSSN', 'tipo' => 'valor'],
            'cstIpi' => ['col' => 'CSTIPI', 'tipo' => 'texto'],
            'baseCalculoIpi' => ['col' => 'BCIPI', 'tipo' => 'valor'],
            'aliqIpi' => ['col' => 'ALIQIPI', 'tipo' => 'aliq'],
            'valorIpi' => ['col' => 'VALORIPI', 'tipo' => 'valor'],
            'cstPis' => ['col' => 'CSTPIS', 'tipo' => 'texto'],
            'bcPis' => ['col' => 'BCPIS', 'tipo' => 'valor'],
            'aliqPis' => ['col' => 'ALIQPIS', 'tipo' => 'aliq'],
            'valorPis' => ['col' => 'VALORPIS', 'tipo' => 'valor'],
            'cstCofins' => ['col' => 'CSTCOFINS', 'tipo' => 'texto'],
            'bcCofins' => ['col' => 'BCCOFINS', 'tipo' => 'valor'],
            'aliqCofins' => ['col' => 'ALIQCOFINS', 'tipo' => 'aliq'],
            'valorCofins' => ['col' => 'VALORCOFINS', 'tipo' => 'valor'],
        ];

        foreach ($mapa as $json => $meta) {
            if (!array_key_exists($json, $sel)) {
                continue;
            }
            $col = $meta['col'];
            $valor = $sel[$json];
            if ($meta['tipo'] === 'texto') {
                $itemOrig[$col] = trim((string) $valor);
                continue;
            }
            $num = c_tools::parseMoedaValor($valor);
            if ($meta['tipo'] === 'aliq') {
                $itemOrig[$col] = $num;
                continue;
            }
            $calc[$col] = round($num, 2);
        }

        if (array_key_exists('valorSt', $sel) && !array_key_exists('valoricmsst', $sel)) {
            $calc['VALORICMSST'] = round(c_tools::parseMoedaValor($sel['valorSt']), 2);
        }

        return [$calc, $itemOrig];
    }

    private function gravarItensNfDevolucao(
        c_banco_pdo $banco,
        int $idNfDev,
        array $itensSelecionados,
        array $contexto,
        $idNatop,
        bool $manual
    ): array {
        $banco->prepare('DELETE FROM EST_NOTA_FISCAL_PRODUTO WHERE IDNF = :idnf');
        $banco->execute([':idnf' => $idNfDev]);

        $totalProdutos = 0.0;

        foreach ($itensSelecionados as $sel) {
            if ($manual) {
                $produto = $this->buscarProdutoCatalogo((int) ($sel['codProduto'] ?? 0));
                if (empty($produto)) {
                    continue;
                }
                $cfop = trim((string) ($sel['cfop'] ?? ''));
                $qtde = c_tools::parseMoedaValor($sel['qtdeDevolver'] ?? 1);
                $unit = c_tools::parseMoedaValor($sel['unitario'] ?? 0);
                $orig = [
                    'CODPRODUTO' => $produto['CODIGO'],
                    'DESCRICAO' => $produto['DESCRICAO'],
                    'UNIDADE' => $produto['UNIDADE'],
                    'NCM' => $produto['NCM'] ?? '',
                    'CEST' => $produto['CEST'] ?? '',
                    'ORIGEM' => $produto['ORIGEM'] ?? '0',
                    'TRIBICMS' => $produto['TRIBICMS'] ?? '',
                    'QUANT' => $qtde,
                    'UNITARIO' => $unit,
                    'DESCONTO' => 0,
                    'TOTAL' => round($qtde * $unit, 2),
                    'CFOP' => $cfop,
                    'FRETE' => 0,
                    'DESPACESSORIAS' => 0,
                    'ALIQICMS' => $produto['ALIQICMS'] ?? 0,
                    'ALIQIPI' => $produto['ALIQIPI'] ?? 0,
                ];
                if ($cfop === '') {
                    $cfop = $this->resolverCfopItem($idNatop, $orig, $contexto);
                }
                $calc = $this->calcularProporcional($orig, $qtde, $unit, $cfop);
            } else {
                $orig = $this->buscarProdutoPorId((int) ($sel['idNfpOrigem'] ?? 0));
                if (empty($orig)) {
                    continue;
                }
                $cfop = $sel['cfop'] ?? $this->resolverCfopItem($idNatop, $orig, $contexto);
                $calc = $this->calcularProporcional(
                    $orig,
                    $sel['qtdeDevolver'] ?? $orig['QUANT'],
                    $sel['unitario'] ?? $orig['UNITARIO'],
                    $cfop
                );
            }

            [$calc, $orig] = $this->aplicarOverridesTributos($calc, $orig, $sel);
            $espelhado = $this->espelharTributosItem($orig, $calc);
            $itemGravar = $this->prepararItemParaGravacao($espelhado, $idNfDev);

            $nItemRef  = trim((string) ($sel['nItem']    ?? ''));
            $chaveRef  = trim((string) ($sel['chaveRef'] ?? ''));
            $itemGravar['IBS_CBS_NITEM']  = $nItemRef  !== '' ? $nItemRef  : null;
            $itemGravar['IBS_CBS_NF_REF'] = $chaveRef  !== '' ? $chaveRef  : null;

            $this->inserirRegistroPdo($banco, 'EST_NOTA_FISCAL_PRODUTO', $itemGravar);

            $totalProdutos += $calc['total'];
        }

        if ($totalProdutos <= 0) {
            throw new Exception($manual ? 'Nenhum produto válido para gravar.' : 'Nenhum item válido para gravar.');
        }

        return [
            'totalProdutos' => $totalProdutos,
        ];
    }

    private function mesclarItemOrigemComRascunho(array $orig, ?array $draft): array
    {
        if (empty($draft)) {
            return $orig;
        }

        $qtdeOrig = $orig['QUANT'] ?? 0;
        $merged = $orig;

        if (!empty($draft['CFOP'])) {
            $merged['CFOP'] = $draft['CFOP'];
        }
        if (c_tools::parseMoedaValor($draft['UNITARIO'] ?? 0) > 0) {
            $merged['UNITARIO'] = $draft['UNITARIO'];
        }

        $temTributos = false;
        foreach (['VALORICMS', 'VALORIPI', 'VALORICMSST', 'VALORPIS', 'VALORCOFINS', 'BCICMS'] as $col) {
            if (c_tools::parseMoedaValor($draft[$col] ?? 0) > 0) {
                $temTributos = true;
                break;
            }
        }

        if (!$temTributos) {
            $merged['QUANT'] = $qtdeOrig;
            $merged['ID'] = $orig['ID'] ?? null;
            return $merged;
        }

        $merged = array_merge($orig, $draft);
        $merged['QUANT'] = $qtdeOrig;
        $merged['ID'] = $orig['ID'] ?? null;
        return $merged;
    }

    public function espelharTributosItem(array $itemOrig, array $calc)
    {
        $item = $itemOrig;
        $item['QUANT'] = $calc['qtdeDev'];
        $item['UNITARIO'] = $calc['unitario'];
        $item['TOTAL'] = $calc['total'];
        $item['CFOP'] = $calc['cfop'];
        $item['FRETE'] = $calc['frete'];
        $item['DESPACESSORIAS'] = $calc['despAcessorias'];

        $campos = [
            'BCICMS',
            'VALORICMS',
            'BCIPI',
            'VALORIPI',
            'VALORBCST',
            'VALORICMSST',
            'BCPIS',
            'VALORPIS',
            'BCCOFINS',
            'VALORCOFINS',
            'VALORTOTALTRIBUTOS',
            'VALORICMSDIFERIDO',
            'VALORICMSOPERACAO',
            'VALORBCSTRETIDO',
            'VALORICMSSTRETIDO',
            'VICMSSUBSTITUTO',
            'VCREDICMSSN',
            'VALORFCPUFDEST',
            'VALORICMSUFDEST',
            'VALORICMSUFREMET',
            'BCFCPST',
            'VALORFCPST',
            'BCICMSUFDEST',
            'BCFCPUFDEST',
            'VALORFCPUFDEST',
        ];
        foreach ($campos as $campo) {
            if (isset($calc[$campo])) {
                $item[$campo] = $calc[$campo];
            }
        }

        return $this->normalizarTribIcmsDevolucao($item);
    }

    public function resumoTributosPreview(array $itemOrig, array $calc)
    {
        $itemNorm = $this->normalizarTribIcmsDevolucao($itemOrig);
        $fator = (float) ($calc['fator'] ?? 1);
        $bcIpi = $calc['BCIPI'] ?? round(c_tools::parseMoedaValor($itemOrig['BCIPI'] ?? 0) * $fator, 2);

        $tributos = [
            'tribIcms' => $itemNorm['TRIBICMS'] ?? '',
            'origem' => $itemOrig['ORIGEM'] ?? '',
            'modBc' => $itemOrig['MODBC'] ?? '',
            'bcIcms' => $calc['BCICMS'] ?? 0,
            'aliqIcms' => c_tools::parseMoedaValor($itemOrig['ALIQICMS'] ?? 0),
            'valorIcms' => $calc['VALORICMS'] ?? 0,
            'percReducaoBc' => c_tools::parseMoedaValor($itemOrig['PERCREDUCAOBC'] ?? 0),
            'valorIcmsOperacao' => $calc['VALORICMSOPERACAO'] ?? 0,
            'percDiferido' => c_tools::parseMoedaValor($itemOrig['PERCDIFERIDO'] ?? 0),
            'valorIcmsDiferido' => $calc['VALORICMSDIFERIDO'] ?? 0,
            'modBcSt' => $itemOrig['MODBCST'] ?? '',
            'valorbcst' => $calc['VALORBCST'] ?? 0,
            'aliqicmsst' => c_tools::parseMoedaValor($itemOrig['ALIQICMSST'] ?? 0),
            'percMvaSt' => c_tools::parseMoedaValor($itemOrig['PERCMVAST'] ?? 0),
            'percReducaoBcSt' => c_tools::parseMoedaValor($itemOrig['PERCREDUCAOBCST'] ?? 0),
            'valoricmsst' => $calc['VALORICMSST'] ?? 0,
            'valorBaseCalculoStRetido' => $calc['VALORBCSTRETIDO'] ?? 0,
            'valorIcmsStRetido' => $calc['VALORICMSSTRETIDO'] ?? 0,
            'valorIcmsSubstituto' => $calc['VICMSSUBSTITUTO'] ?? 0,
            'pCredSn' => c_tools::parseMoedaValor($itemOrig['PCREDSN'] ?? 0),
            'creditoSn' => $calc['VCREDICMSSN'] ?? 0,
            'cstIpi' => $itemOrig['CSTIPI'] ?? '',
            'baseCalculoIpi' => $bcIpi,
            'aliqIpi' => c_tools::parseMoedaValor($itemOrig['ALIQIPI'] ?? 0),
            'valorIpi' => $calc['VALORIPI'] ?? 0,
            'cstPis' => $itemOrig['CSTPIS'] ?? '',
            'bcPis' => $calc['BCPIS'] ?? 0,
            'aliqPis' => c_tools::parseMoedaValor($itemOrig['ALIQPIS'] ?? 0),
            'valorPis' => $calc['VALORPIS'] ?? 0,
            'cstCofins' => $itemOrig['CSTCOFINS'] ?? '',
            'bcCofins' => $calc['BCCOFINS'] ?? 0,
            'aliqCofins' => c_tools::parseMoedaValor($itemOrig['ALIQCOFINS'] ?? 0),
            'valorCofins' => $calc['VALORCOFINS'] ?? 0,
        ];

        return [
            'tributos' => $tributos,
            'icms' => [
                'cst' => $tributos['tribIcms'],
                'bc' => $tributos['bcIcms'],
                'aliq' => $tributos['aliqIcms'],
                'valor' => $tributos['valorIcms'],
                'creditoSn' => $tributos['creditoSn'],
            ],
            'ipi' => [
                'bc' => $tributos['baseCalculoIpi'],
                'valor' => $tributos['valorIpi'],
                'aliq' => $tributos['aliqIpi'],
            ],
            'st' => [
                'bc' => $tributos['valorbcst'],
                'valor' => $tributos['valoricmsst'],
                'retido' => $tributos['valorIcmsStRetido'],
            ],
            'pis' => [
                'bc' => $tributos['bcPis'],
                'valor' => $tributos['valorPis'],
                'aliq' => $tributos['aliqPis'],
            ],
            'cofins' => [
                'bc' => $tributos['bcCofins'],
                'valor' => $tributos['valorCofins'],
                'aliq' => $tributos['aliqCofins'],
            ],
            'frete' => $calc['frete'] ?? 0,
            'despAcessorias' => $calc['despAcessorias'] ?? 0,
            'total' => $calc['total'] ?? 0,
        ];
    }

    public function previewItem($idNfpOrigem, $qtdeDev, $vlrUni, $cfop, $idNatop, $idNfOrigem, $codProduto = 0, $cenarioCodigo = null, $idPessoa = 0, $idNfDev = null, $contextoCfop = null, $mapaRascunho = null)
    {
        if ((int) $idNfpOrigem > 0) {
            $orig = $this->buscarProdutoPorId((int) $idNfpOrigem);
            if (empty($orig)) {
                return ['ok' => false, 'erro' => 'Item da NF de origem não encontrado.'];
            }

            if ($mapaRascunho === null) {
                $mapaRascunho = $this->mapaItensRascunho($idNfDev);
            }
            $draft = $mapaRascunho[(string) ($orig['CODPRODUTO'] ?? '')] ?? null;
            $orig = $this->mesclarItemOrigemComRascunho($orig, $draft);

            if ($contextoCfop === null) {
                $validacao = $this->validarNfOrigem((int) $idNfOrigem);
                if (!$validacao['ok']) {
                    return $validacao;
                }
                $contextoCfop = $this->contextoCfopOrigem($validacao);
            }
            if (empty($cfop)) {
                $cfop = $this->resolverCfopItem($idNatop, $orig, $contextoCfop);
            }

            $calc = $this->calcularProporcional($orig, $qtdeDev, $vlrUni, $cfop);
            return [
                'ok' => true,
                'calc' => $calc,
                'preview' => $this->resumoTributosPreview($orig, $calc),
            ];
        }

        if ((int) $codProduto > 0) {
            $produto = $this->buscarProdutoCatalogo((int) $codProduto);
            if (empty($produto)) {
                return ['ok' => false, 'erro' => 'Produto não encontrado.'];
            }
            $codigo = $cenarioCodigo ?: 'DEVOLUCAO_VENDA';
            $tipoDevolucao = ($codigo === 'DEVOLUCAO_COMPRA') ? '1' : '0';
            $cenario = [
                'codigo' => $codigo,
                'tipoDevolucao' => $tipoDevolucao,
                'natOpTipo' => ($tipoDevolucao === '0') ? 'E' : 'S',
            ];
            $pessoaRow = $this->buscarClientePorId((int) $idPessoa) ?? [];
            $contexto = [
                'ok' => true,
                'manual' => true,
                'cenario' => $cenario,
                'centroCusto' => $this->m_empresacentrocusto,
                'pessoa' => [
                    'uf' => $pessoaRow['UF'] ?? '',
                    'tipo' => $pessoaRow['TIPO'] ?? 'J',
                ],
                'ufEmpresa' => $this->obterUfEmpresa(),
            ];
            $qtde = c_tools::parseMoedaValor($qtdeDev);
            $unit = c_tools::parseMoedaValor($vlrUni);
            $orig = [
                'CODPRODUTO' => $produto['CODIGO'],
                'DESCRICAO' => $produto['DESCRICAO'],
                'UNIDADE' => $produto['UNIDADE'],
                'NCM' => $produto['NCM'] ?? '',
                'CEST' => $produto['CEST'] ?? '',
                'ORIGEM' => $produto['ORIGEM'] ?? '0',
                'TRIBICMS' => $produto['TRIBICMS'] ?? '',
                'QUANT' => $qtde,
                'UNITARIO' => $unit,
                'DESCONTO' => 0,
                'TOTAL' => round($qtde * $unit, 2),
                'CFOP' => $cfop,
                'FRETE' => 0,
                'DESPACESSORIAS' => 0,
                'ALIQICMS' => $produto['ALIQICMS'] ?? 0,
                'ALIQIPI' => $produto['ALIQIPI'] ?? 0,
            ];
            if (empty($cfop)) {
                $cfop = $this->resolverCfopItem($idNatop, $orig, $contexto);
                $orig['CFOP'] = $cfop;
            }
            $calc = $this->calcularProporcional($orig, $qtdeDev, $vlrUni, $cfop);
            return [
                'ok' => true,
                'calc' => $calc,
                'preview' => $this->resumoTributosPreview($orig, $calc),
            ];
        }

        return ['ok' => false, 'erro' => 'Item não informado para cálculo tributário.'];
    }

    private function gravarRascunhoManual(array $cabecalho, array $itensSelecionados, $idNfDev = null)
    {
        $idPessoa = (int) ($cabecalho['idPessoa'] ?? 0);
        $chnfe = trim((string) ($cabecalho['chnfe'] ?? ''));
        $nfNumero = trim((string) ($cabecalho['nfNumero'] ?? ''));
        $nfSerie = trim((string) ($cabecalho['nfSerie'] ?? ''));
        $cenarioCodigo = $cabecalho['cenarioCodigo'] ?? 'DEVOLUCAO_VENDA';

        if ($idPessoa <= 0) {
            return ['ok' => false, 'erro' => 'Informe a pessoa (cliente/fornecedor).'];
        }
        if ($chnfe === '' && $nfNumero === '') {
            return ['ok' => false, 'erro' => 'Informe a chave NFe ou o número da NF de origem.'];
        }
        if (empty($itensSelecionados)) {
            return ['ok' => false, 'erro' => 'Inclua ao menos um produto na devolução.'];
        }

        $codigo = $cenarioCodigo ?: 'DEVOLUCAO_VENDA';
        $tipoDevolucao = ($codigo === 'DEVOLUCAO_COMPRA') ? '1' : '0';
        $cenario = [
            'codigo' => $codigo,
            'tipoDevolucao' => $tipoDevolucao,
            'natOpTipo' => ($tipoDevolucao === '0') ? 'E' : 'S',
        ];
        $pessoaRow = $this->buscarClientePorId($idPessoa) ?? [];
        $contexto = [
            'ok' => true,
            'manual' => true,
            'cenario' => $cenario,
            'centroCusto' => (int) ($cabecalho['centroCusto'] ?? $this->m_empresacentrocusto),
            'pessoa' => [
                'uf' => $pessoaRow['UF'] ?? '',
                'tipo' => $pessoaRow['TIPO'] ?? 'J',
            ],
            'ufEmpresa' => $this->obterUfEmpresa(),
        ];

        $nfOrig = [
            'SERIE' => 1,
            'PESSOA' => $idPessoa,
            'CPFNOTA' => '',
            'CONDPGTO' => 0,
            'GENERO' => '',
            'MODFRETE' => '9',
            'TRANSPORTADOR' => 0,
            'PLACAVEICULO' => '',
            'CODANTT' => '',
            'UF' => '',
            'VOLUME' => 0,
            'VOLESPECIE' => '',
            'VOLMARCA' => '',
            'VOLPESOLIQ' => 0,
            'VOLPESOBRUTO' => 0,
            'VENDAPRESENCIAL' => 'N',
            'CONTRATO' => '',
            'CHNFE' => $chnfe,
        ];

        $obs = 'DEVOLUÇÃO MANUAL | NF ' . $nfNumero . ' / Série ' . $nfSerie . ' | ' . date('d/m/Y H:i');
        $cabecalho['emissao'] = $this->converterDataHoraBd($cabecalho['emissao'] ?? date('d/m/Y H:i'));
        $this->aplicarTransporteCabecalhoEmNfOrig($nfOrig, $cabecalho);
        $fin = $this->paramsCabecalhoFinanceiro($cabecalho);
        if ($fin[':obs'] === '') {
            $fin[':obs'] = $obs;
        }

        $banco = new c_banco_pdo();
        $banco->beginTransaction();

        try {
            if ($idNfDev) {
                $idNfDev = (int) $idNfDev;
            } else {
                $idNfDev = $this->inserirNotaFiscalDevolucaoPdo($banco, $cabecalho, $nfOrig, $cenario, 0, [
                    'obs' => $obs,
                    'chnfe' => $chnfe,
                    'pessoa' => $idPessoa,
                    'doc' => 0,
                    'uf' => $pessoaRow['UF'] ?? '',
                ]);
                if ($idNfDev <= 0) {
                    throw new Exception('Falha ao criar NF de devolução.');
                }
            }

            $totais = $this->gravarItensNfDevolucao(
                $banco,
                $idNfDev,
                $itensSelecionados,
                $contexto,
                $cabecalho['idNatop'] ?? null,
                true
            );
            $emissao = $cabecalho['emissao'];

            $tpCredito = ($cabecalho['tpNFCredito'] ?? '') !== '' ? (string) $cabecalho['tpNFCredito'] : null;
            $tpDebito  = ($cabecalho['tpNFDebito']  ?? '') !== '' ? (string) $cabecalho['tpNFDebito']  : null;
            $idNatopManual = (int) ($cabecalho['idNatop'] ?? 0);
            $banco->prepare('UPDATE EST_NOTA_FISCAL SET '
                . 'PESSOA = :pessoa, '
                . 'IDNATOP = :idnatop, '
                . 'NATOPERACAO = :natoperacao, '
                . 'TIPO = :tipo, '
                . 'EMISSAO = :emissao, '
                . 'TOTALNF = :totalnf, '
                . 'FRETE = :frete, '
                . 'SEGURO = :seguro, '
                . 'DESPACESSORIAS = :desp, '
                . 'MODFRETE = :modfrete, '
                . 'TRANSPORTADOR = :transportador, '
                . 'PLACAVEICULO = :placaveiculo, '
                . 'CODANTT = :codantt, '
                . 'UF = :uf, '
                . 'VOLUME = :volume, '
                . 'VOLESPECIE = :volespecie, '
                . 'VOLMARCA = :volmarca, '
                . 'VOLPESOLIQ = :volpesoliq, '
                . 'VOLPESOBRUTO = :volpesobruto, '
                . 'OBS = :obs, '
                . 'NFEREFERENCIADA = :chnfe, '
                . 'FINALIDADEEMISSAO = :finalidadeemissao, '
                . 'IBS_CBS_TIPO_CREDITO = :ibscbstipocredito, '
                . 'IBS_CBS_TIPO_DEBITO = :ibscbstipodebito '
                . 'WHERE ID = :id');
            $totalNf = round($totais['totalProdutos'] + $fin[':frete'] + $fin[':seguro'] + $fin[':desp'], 2);
            $banco->execute(array_merge([
                ':pessoa' => $idPessoa,
                ':idnatop' => $idNatopManual,
                ':natoperacao' => $this->resolverNatOperacao($idNatopManual),
                ':tipo' => $cenario['tipoDevolucao'],
                ':emissao' => $emissao,
                ':totalnf' => $totalNf,
                ':chnfe' => $chnfe,
                ':finalidadeemissao' => (int) ($cabecalho['finNFe'] ?? 4),
                ':ibscbstipocredito' => $tpCredito,
                ':ibscbstipodebito'  => $tpDebito,
                ':id' => $idNfDev,
            ], $fin, $this->paramsTransporteCabecalho($cabecalho), [
                ':uf' => strtoupper(trim((string) ($pessoaRow['UF'] ?? ''))),
            ]));

            $banco->commit();
            $this->aplicarRateioCabecalho((int) $idNfDev);
            return [
                'ok' => true,
                'idNfDev' => (int) $idNfDev,
                'totalNf' => $totalNf,
                'totalFrete' => $fin[':frete'],
                'totalDesp' => $fin[':desp'],
                'totalSeguro' => $fin[':seguro'],
            ];
        } catch (Exception $e) {
            $banco->rollBack();
            return ['ok' => false, 'erro' => $e->getMessage()];
        }
    }

    public function criarOuAtualizarRascunho(array $cabecalho, array $itensSelecionados, $idNfDev = null)
    {
        if (!empty($cabecalho['manual'])) {
            return $this->gravarRascunhoManual($cabecalho, $itensSelecionados, $idNfDev);
        }

        $idNfOrigem = (int) ($cabecalho['idNfOrigem'] ?? 0);
        $validacao = $this->validarNfOrigem($idNfOrigem);
        if (!$validacao['ok']) {
            return $validacao;
        }

        if (empty($itensSelecionados)) {
            return ['ok' => false, 'erro' => 'Selecione ao menos um item para devolução.'];
        }

        $nfOrig = $validacao['nf'];
        $cenario = $validacao['cenario'];
        $contexto = $this->contextoCfopOrigem($validacao, $cabecalho['centroCusto'] ?? null);
        $cabecalho['emissao'] = $this->converterDataHoraBd($cabecalho['emissao'] ?? date('d/m/Y H:i'));
        $nfOrig['TRANSPORTADOR'] = 0;
        $this->aplicarTransporteCabecalhoEmNfOrig($nfOrig, $cabecalho);

        $banco = new c_banco_pdo();
        $banco->beginTransaction();

        try {
            if ($idNfDev) {
                $idNfDev = (int) $idNfDev;
            } else {
                $idNfDev = $this->inserirNotaFiscalDevolucaoPdo($banco, $cabecalho, $nfOrig, $cenario, $idNfOrigem, [
                    'uf' => $contexto['pessoa']['uf'] ?? '',
                ]);
                if ($idNfDev <= 0) {
                    throw new Exception('Falha ao criar NF de devolução.');
                }
            }

            $totais = $this->gravarItensNfDevolucao(
                $banco,
                $idNfDev,
                $itensSelecionados,
                $contexto,
                $cabecalho['idNatop'] ?? null,
                false
            );
            $emissao = $cabecalho['emissao'];
            $fin = $this->paramsCabecalhoFinanceiro($cabecalho, $idNfOrigem);

            $tpCredito = ($cabecalho['tpNFCredito'] ?? '') !== '' ? (string) $cabecalho['tpNFCredito'] : null;
            $tpDebito  = ($cabecalho['tpNFDebito']  ?? '') !== '' ? (string) $cabecalho['tpNFDebito']  : null;
            $idNatopUpd = (int) ($cabecalho['idNatop'] ?? 0);
            $banco->prepare('UPDATE EST_NOTA_FISCAL SET '
                . 'IDNATOP = :idnatop, '
                . 'NATOPERACAO = :natoperacao, '
                . 'EMISSAO = :emissao, '
                . 'TOTALNF = :totalnf, '
                . 'FRETE = :frete, '
                . 'SEGURO = :seguro, '
                . 'DESPACESSORIAS = :desp, '
                . 'MODFRETE = :modfrete, '
                . 'TRANSPORTADOR = :transportador, '
                . 'PLACAVEICULO = :placaveiculo, '
                . 'CODANTT = :codantt, '
                . 'UF = :uf, '
                . 'VOLUME = :volume, '
                . 'VOLESPECIE = :volespecie, '
                . 'VOLMARCA = :volmarca, '
                . 'VOLPESOLIQ = :volpesoliq, '
                . 'VOLPESOBRUTO = :volpesobruto, '
                . 'OBS = :obs, '
                . 'NFEREFERENCIADA = :chnfe, '
                . 'FINALIDADEEMISSAO = :finalidadeemissao, '
                . 'IBS_CBS_TIPO_CREDITO = :ibscbstipocredito, '
                . 'IBS_CBS_TIPO_DEBITO = :ibscbstipodebito '
                . 'WHERE ID = :id');
            $totalNf = round($totais['totalProdutos'] + $fin[':frete'] + $fin[':seguro'] + $fin[':desp'], 2);
            $banco->execute(array_merge([
                ':idnatop' => $idNatopUpd,
                ':natoperacao' => $this->resolverNatOperacao($idNatopUpd),
                ':emissao' => $emissao,
                ':totalnf' => $totalNf,
                ':chnfe' => $nfOrig['CHNFE'] ?? '',
                ':finalidadeemissao' => (int) ($cabecalho['finNFe'] ?? 4),
                ':ibscbstipocredito' => $tpCredito,
                ':ibscbstipodebito'  => $tpDebito,
                ':id' => $idNfDev,
            ], $fin, $this->paramsTransporteCabecalho($cabecalho), [
                ':uf' => strtoupper(trim((string) ($contexto['pessoa']['uf'] ?? ''))),
            ]));

            $banco->commit();
            $this->aplicarRateioCabecalho((int) $idNfDev);
            return [
                'ok' => true,
                'idNfDev' => (int) $idNfDev,
                'totalNf' => $totalNf,
                'totalFrete' => $fin[':frete'],
                'totalDesp' => $fin[':desp'],
                'totalSeguro' => $fin[':seguro'],
            ];
        } catch (Exception $e) {
            $banco->rollBack();
            return ['ok' => false, 'erro' => $e->getMessage()];
        }
    }

    public function cancelarRascunho($idNfDev)
    {
        $id = (int) $idNfDev;
        $banco = new c_banco_pdo();
        $banco->prepare('DELETE FROM EST_NOTA_FISCAL_PRODUTO WHERE IDNF = :id');
        $banco->execute([':id' => $id]);
        $banco->prepare("DELETE FROM EST_NOTA_FISCAL WHERE ID = :id AND SITUACAO = 'A' AND CHNFE IS NULL");
        $banco->execute([':id' => $id]);
        return ['ok' => true];
    }

    public function listarNfsElegiveisDevolucao($letra)
    {
        $nf = new c_nota_fiscal();
        $rows = $nf->select_nota_fiscal_letra($letra);
        if (!is_array($rows) || count($rows) === 0) {
            return [];
        }

        $chnfesVistos = [];
        $resultado = [];

        foreach ($rows as $row) {
            if (empty($row['CHNFE'])) {
                continue;
            }
            if ((int) ($row['FINALIDADEEMISSAO'] ?? 1) === 4) {
                continue;
            }

            $idCanonico = $this->resolverIdNfOrigemCanonica((int) $row['ID']);
            if ($this->contarProdutosNf($idCanonico) <= 0) {
                continue;
            }

            $chnfe = trim((string) ($row['CHNFE'] ?? ''));
            if ($chnfe !== '' && isset($chnfesVistos[$chnfe])) {
                continue;
            }
            if ($chnfe !== '') {
                $chnfesVistos[$chnfe] = true;
            }

            if ($idCanonico !== (int) $row['ID']) {
                $rowCanon = $this->buscarNotaFiscalPorId($idCanonico);
                if (!empty($rowCanon)) {
                    $row = array_merge($row, $rowCanon);
                }
            }

            $row['ID'] = $idCanonico;
            $natOpAtual = trim((string) ($row['NATOPERACAO'] ?? ''));
            if ((empty($natOpAtual) || $natOpAtual === 'DEVOLUCAO') && !empty($row['IDNATOP'])) {
                $resolved = $this->resolverNatOperacao((int) $row['IDNATOP']);
                if ($resolved !== '') {
                    $row['NATOPERACAO'] = $resolved;
                }
            }
            $resultado[] = $row;
        }

        return $resultado;
    }

    public function listarDevolucoes($dataIni, $dataFim, $pessoa = null, $situacao = null)
    {
        $sql = "SELECT N.ID, N.NUMERO, N.SERIE, N.EMISSAO, N.PESSOA, N.TOTALNF, N.SITUACAO, N.OBS, N.CHNFE, C.NOME, S.PADRAO AS SITUACAONOTA, "
            . "COALESCE(NULLIF(TRIM(N.NATOPERACAO), ''), OP.NATOPERACAO) AS NATOPERACAO "
            . "FROM EST_NOTA_FISCAL N "
            . "LEFT JOIN FIN_CLIENTE C ON C.CLIENTE = N.PESSOA "
            . "LEFT JOIN EST_NAT_OP OP ON OP.ID = N.IDNATOP "
            . "LEFT JOIN AMB_DDM S ON ((S.ALIAS='EST_MENU') AND (S.CAMPO='SituacaoNota') AND (S.TIPO = N.SITUACAO)) "
            . "WHERE N.FINALIDADEEMISSAO IN (4, 5, 6) "
            . "AND N.CENTROCUSTO = :centrocusto";

        $params = [':centrocusto' => (int) $this->m_empresacentrocusto];

        if ($dataIni && $dataFim) {
            $di = c_date::convertDateBdSh($dataIni);
            $df = c_date::convertDateBdSh($dataFim);
            $sql .= ' AND DATE(N.EMISSAO) BETWEEN :dataini AND :datafim';
            $params[':dataini'] = $di;
            $params[':datafim'] = $df;
        }
        if ($pessoa) {
            $sql .= ' AND N.PESSOA = :pessoa';
            $params[':pessoa'] = (int) $pessoa;
        }
        if ($situacao !== null && $situacao !== '') {
            $sql .= ' AND N.SITUACAO = :situacao';
            $params[':situacao'] = (string) $situacao;
        }
        $sql .= ' ORDER BY N.ID DESC';

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->execute($params);
        $rows = $banco->fetchAll();
        return is_array($rows) ? $rows : [];
    }

    public function previewTotaisComItens(array $itensPayload, $idNfOrigem, $idNatop, $cenarioCodigo = null, $idPessoa = 0, $idNfDev = null)
    {
        $totais = [
            'produtos' => 0,
            'icms' => 0,
            'ipi' => 0,
            'st' => 0,
            'stRetido' => 0,
            'pis' => 0,
            'cofins' => 0,
            'frete' => 0,
            'desp' => 0,
            'creditoSn' => 0,
            'total' => 0,
        ];
        $itens = [];

        $contextoCfop = null;
        $mapaRascunho = null;
        if ((int) $idNfOrigem > 0) {
            $validacao = $this->validarNfOrigem((int) $idNfOrigem);
            if ($validacao['ok']) {
                $contextoCfop = $this->contextoCfopOrigem($validacao);
            }
            $mapaRascunho = $this->mapaItensRascunho($idNfDev);
        }

        foreach ($itensPayload as $sel) {
            $prev = $this->previewItem(
                $sel['idNfpOrigem'] ?? 0,
                $sel['qtdeDevolver'],
                $sel['unitario'],
                $sel['cfop'] ?? '',
                $idNatop,
                $idNfOrigem,
                (int) ($sel['idNfpOrigem'] ?? 0) > 0 ? 0 : (int) ($sel['codProduto'] ?? 0),
                $cenarioCodigo,
                $idPessoa,
                $idNfDev,
                $contextoCfop,
                $mapaRascunho
            );
            $itens[] = $prev;
            if (empty($prev['ok'])) {
                continue;
            }
            $p = $prev['preview'];
            $totais['produtos'] += $p['total'];
            $totais['icms'] += $p['icms']['valor'];
            $totais['ipi'] += $p['ipi']['valor'];
            $totais['st'] += $p['st']['valor'];
            $totais['stRetido'] += $p['st']['retido'];
            $totais['pis'] += $p['pis']['valor'];
            $totais['cofins'] += $p['cofins']['valor'];
            $totais['frete'] += $p['frete'];
            $totais['desp'] += $p['despAcessorias'];
            $totais['creditoSn'] += $p['icms']['creditoSn'];
        }
        $totais['total'] = $totais['produtos'] + $totais['frete'] + $totais['desp'];

        return [
            'ok' => true,
            'totais' => $totais,
            'itens' => $itens,
        ];
    }

    public function buscarItensOrigemAjax($idNfOrigem, $idNfDev, $idNatop, $manual): array
    {
        if ($manual) {
            return ['ok' => true, 'itens' => $this->buscarItensManual($idNfDev)];
        }
        if ((int) $idNfOrigem <= 0) {
            return ['ok' => false, 'erro' => 'NF de origem não informada.'];
        }
        $itens = $this->buscarItensOrigem($idNfOrigem, $idNfDev, $idNatop);
        if (!empty($itens)) {
            return ['ok' => true, 'itens' => $itens];
        }
        $nf = $this->buscarNotaFiscalPorId((int) $idNfOrigem);
        $numero = $nf['NUMERO'] ?? $idNfOrigem;
        return [
            'ok' => false,
            'erro' => 'Nenhum produto na NF de origem nº ' . $numero . ' (ID ' . $idNfOrigem . ').',
        ];
    }

    public function confirmarDevolucao($idNfDev): array
    {
        $id = (int) $idNfDev;
        if ($id <= 0) {
            return ['ok' => false, 'erro' => 'NF não informada.'];
        }

        $nf = $this->buscarNotaFiscalPorId($id);
        if (empty($nf)) {
            return ['ok' => false, 'erro' => 'NF não encontrada.'];
        }

        $finalidade = (int) ($nf['FINALIDADEEMISSAO'] ?? 0);
        if (!in_array($finalidade, [4, 5, 6], true)) {
            return ['ok' => false, 'erro' => 'Operação inválida para esta finalidade de emissão.'];
        }

        $banco = new c_banco_pdo();
        $banco->prepare("UPDATE EST_NOTA_FISCAL SET FORMAEMISSAO = 'N', SITUACAO = 'A' WHERE ID = :id AND FINALIDADEEMISSAO IN (4, 5, 6)");
        $banco->execute([':id' => $id]);
        return ['ok' => true, 'idNfDev' => $id];
    }

    public function gerarEspelhoDevolucao($idNfDev): array
    {
        $id = (int) $idNfDev;
        if ($id <= 0) {
            return ['ok' => false, 'erro' => 'NF de devolução não informada.'];
        }
        $arr = $this->buscarNotaFiscalPorId($id);
        if (empty($arr)) {
            return ['ok' => false, 'erro' => 'NF de devolução não encontrada.'];
        }

        $dir = dirname(__FILE__);
        require_once($dir . '/../../forms/est/p_espelho_nfe.php');
        $danfe = new p_espelho_nfe();
        $time = round(microtime(true) * 1000);
        $resultado = $danfe->gera_XML($id, $arr['CENTROCUSTO'], $arr['TIPO'], null, $time);

        if (is_string($resultado) && strpos($resultado, 'http') === 0) {
            return [
                'ok' => true,
                'pdfUrl' => $resultado,
                'xmlUrl' => $danfe->getUltimoXmlEspelhoUrl(),
            ];
        }
        return ['ok' => false, 'erro' => 'Falha ao gerar espelho da NF.'];
    }

    public function emitirNfeDevolucao($idNfDev, $origem): array
    {
        $id = (int) $idNfDev;
        if ($id <= 0) {
            return ['ok' => false, 'erro' => 'NF não informada.'];
        }
        $arr = $this->buscarNotaFiscalPorId($id);
        if (empty($arr)) {
            return ['ok' => false, 'erro' => 'NF não encontrada.'];
        }

        if ((int) $arr['NUMERO'] === 0) {
            $nf = new c_nota_fiscal();
            $nf->setId($id);
            $numNf = $nf->geraNumNf($arr['MODELO'], $arr['SERIE'], $arr['CENTROCUSTO']);
            if ((int) $numNf === 0) {
                return ['ok' => false, 'erro' => 'Não foi possível gerar número da NF.'];
            }
            $nf->setNumero($numNf);
            $nf->alteraNfNumero();
        }

        $dir = dirname(__FILE__);
        require_once($dir . '/../../forms/est/p_nfephp_40.php');
        $exporta = new p_nfe_40();
        $result = $exporta->Gera_XML($id, $this->m_empresacentrocusto, $arr['TIPO']);

        if (isset($result['cStatus']) && $result['cStatus'] == '100') {
            return [
                'ok' => true,
                'cStatus' => $result['cStatus'],
                'idNfDev' => $id,
                'redirectUrl' => ($origem === 'nota_fiscal_devolucao')
                    ? 'index.php?mod=est&form=nota_fiscal_devolucao&submenu=mostra'
                    : 'index.php?mod=est&form=nota_fiscal&submenu=mostra',
                'danfeUrl' => 'index.php?mod=est&origem=imprimeDanfe&opcao=imprimir&form=nfephp_imprime_danfe&id=' . $id,
            ];
        }

        return [
            'ok' => false,
            'erro' => $result['motivo'] ?? 'Falha na emissão da NFe.',
            'detalhe' => $result,
        ];
    }

    private function comboFilial(): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT CENTROCUSTO AS ID, DESCRICAO FROM FIN_CENTRO_CUSTO WHERE ATIVO = 'S'");
        $banco->execute();
        $rows = $banco->fetchAll() ?: [];
        $ids = [];
        $names = [];
        foreach ($rows as $i => $row) {
            $ids[$i] = $row['ID'];
            $names[$i] = $row['DESCRICAO'];
        }
        return ['ids' => $ids, 'names' => $names];
    }

    private function comboTipoNotaFiscal(): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE ALIAS = 'EST_MENU' AND CAMPO = 'TipoNotaFiscal'");
        $banco->execute();
        $rows = $banco->fetchAll() ?: [];
        $ids = [''];
        $names = ['Todos'];
        foreach ($rows as $i => $row) {
            $ids[$i + 1] = $row['ID'];
            $names[$i + 1] = $row['DESCRICAO'];
        }
        return ['ids' => $ids, 'names' => $names];
    }

    private function comboSituacaoNota(): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE ALIAS = 'EST_MENU' AND CAMPO = 'SituacaoNota'");
        $banco->execute();
        $rows = $banco->fetchAll() ?: [];
        $ids = [0];
        $names = ['Todas'];
        foreach ($rows as $i => $row) {
            $ids[$i + 1] = $row['ID'];
            $names[$i + 1] = $row['DESCRICAO'];
        }
        return ['ids' => $ids, 'names' => $names];
    }

    private function comboFinalidadeEmissao(): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE ALIAS = 'EST_MENU' AND CAMPO = 'FinalidadeEmissao' AND TIPO IN (2 ,3 ,4 ,5 ,6)");
        $banco->execute();
        $rows = $banco->fetchAll() ?: [];
        $ids = [''];
        $names = ['-- Selecione --'];
        foreach ($rows as $i => $row) {
            $ids[$i + 1] = $row['ID'];
            $names[$i + 1] = $row['DESCRICAO'];
        }
        return ['ids' => $ids, 'names' => $names];
    }

    private function comboModFrete(): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE ALIAS = 'EST_MENU' AND CAMPO = 'modFrete'");
        $banco->execute();
        $rows = $banco->fetchAll() ?: [];
        $ids = [''];
        $names = [''];
        foreach ($rows as $i => $row) {
            $ids[$i + 1] = $row['ID'];
            $names[$i + 1] = ucwords(strtolower($row['DESCRICAO']));
        }
        return ['ids' => $ids, 'names' => $names];
    }

    private function comboNatOperacao(): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT ID, NATOPERACAO AS DESCRICAO FROM EST_NAT_OP WHERE 1=1 ORDER BY ID');
        $banco->execute();
        $rows = $banco->fetchAll() ?: [];
        $ids = [''];
        $names = [''];
        foreach ($rows as $i => $row) {
            $ids[$i + 1] = $row['ID'];
            $names[$i + 1] = $row['ID'] . ' - ' . $row['DESCRICAO'];
        }
        return ['ids' => $ids, 'names' => $names];
    }

    private function buscarDescGenero(string $genero): string
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT DESCRICAO FROM FIN_GENERO WHERE GENERO = :genero');
        $banco->execute([':genero' => $genero]);
        $row = $banco->fetch();
        return $row['DESCRICAO'] ?? '';
    }

    private function buscarNomeTransportador(int $cliente): string
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT NOMEREDUZIDO, NOME FROM FIN_CLIENTE WHERE CLIENTE = :cliente');
        $banco->execute([':cliente' => $cliente]);
        $row = $banco->fetch();
        return $row['NOMEREDUZIDO'] ?? $row['NOME'] ?? '';
    }

    public function montarDadosMostra($letra, array $par): array
    {
        $lancOrigem = ($letra != '') ? $this->listarNfsElegiveisDevolucao($letra) : [];

        $dataIni = ($par[3] ?? '') == '' ? date('01/m/Y') : $par[3];
        $dataFim = ($par[4] ?? '') == '' ? date('d/m/Y') : $par[4];
        $pessoa = !empty($par[7]) ? (int) $par[7] : null;
        $situacaoDev = (!empty($par[2]) && $par[2] !== '0') ? $par[2] : null;

        $dados = [
            'letra' => $letra,
            'lancOrigem' => $lancOrigem,
            'lanc' => $this->listarDevolucoes($dataIni, $dataFim, $pessoa, $situacaoDev),
            'numNf' => $par[5] ?? '',
            'serieNf' => $par[6] ?? '',
            'modeloNf' => $par[13] ?? '',
            'dataIni' => $dataIni,
            'dataFim' => $dataFim,
            'dataConsulta' => $dataIni . ' - ' . $dataFim,
            'pessoa' => '',
            'nome' => '',
            'filial_id' => (!empty($par[0]) ? $par[0] : $this->m_empresacentrocusto),
            'tipo_id' => ($par[1] ?? '') == '' ? '1' : $par[1],
            'situacao_id' => ($par[2] ?? '') == '' ? 'B' : $par[2],
            'finalidadeEmissao_id' => $par[9] ?? '',
            'modFrete_id' => $par[10] ?? '',
            'natOperacao_id' => $par[8] ?? '',
        ];

        if (!empty($par[7])) {
            $cliente = $this->buscarClientePorId((int) $par[7]);
            $dados['pessoa'] = $par[7];
            $dados['nome'] = $cliente['NOME'] ?? $cliente['NOMEREDUZIDO'] ?? '';
        }

        $filial = $this->comboFilial();
        $dados['filial_ids'] = $filial['ids'];
        $dados['filial_names'] = $filial['names'];

        $tipo = $this->comboTipoNotaFiscal();
        $dados['tipo_ids'] = $tipo['ids'];
        $dados['tipo_names'] = $tipo['names'];

        $situacao = $this->comboSituacaoNota();
        $dados['situacao_ids'] = $situacao['ids'];
        $dados['situacao_names'] = $situacao['names'];

        $finalidade = $this->comboFinalidadeEmissao();
        $dados['finalidadeEmissao_ids'] = $finalidade['ids'];
        $dados['finalidadeEmissao_names'] = $finalidade['names'];

        $modFrete = $this->comboModFrete();
        $dados['modFrete_ids'] = $modFrete['ids'];
        $dados['modFrete_names'] = $modFrete['names'];

        $natOperacao = $this->comboNatOperacao();
        $dados['natOperacao_ids'] = $natOperacao['ids'];
        $dados['natOperacao_names'] = $natOperacao['names'];

        if (!empty($par[11])) {
            $dados['genero'] = $par[11];
            $dados['descGenero'] = $this->buscarDescGenero($par[11]);
        }

        if (!empty($par[12])) {
            $dados['transportador'] = $par[12];
            $dados['transpNome'] = $this->buscarNomeTransportador((int) $par[12]);
        }

        return $dados;
    }
}
