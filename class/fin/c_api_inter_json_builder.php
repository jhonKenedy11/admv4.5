<?php

/**
 * @package   astecv3
 * @name      c_api_inter_json_builder
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      03/12/2025
 */

 $dir = dirname(__FILE__);
 include_once($dir."/../../bib/c_session_manager.php");
 include_once($dir."/c_api_inter_json_builder_validate.php");

Class c_api_inter_json_builder {

    

    function jsonRegistraBoleto($dados) {

        $erros = c_api_inter_json_builder_validate::validateDadosEmitirCobranca($dados);
        
        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }
        
        $json = [
            "seuNumero" => $dados['seuNumero'] ?? "",
            "valorNominal" => $this->formatarDecimalNumerico($dados['valorNominal'] ?? 0),
            "dataVencimento" => $dados['dataVencimento'] ?? "",
            "numDiasAgenda" => isset($dados['numDiasAgenda']) ? intval($dados['numDiasAgenda']) : 0,
            "pagador" => [
                "email" => $dados['pagadorEmail'] ?? "",
                "ddd" => $dados['pagadorDdd'] ?? "",
                "telefone" => $dados['pagadorTelefone'] ?? "",
                "numero" => $dados['pagadorNumero'] ?? "",
                "complemento" => $dados['pagadorComplemento'] ?? "",
                "cpfCnpj" => $dados['pagadorCpfCnpj'] ?? "",
                "tipoPessoa" => $dados['pagadorTipoPessoa'] ?? "",
                "nome" => $dados['pagadorNome'] ?? "",
                "endereco" => $dados['pagadorEndereco'] ?? "",
                "bairro" => $dados['pagadorBairro'] ?? "",
                "cidade" => $dados['pagadorCidade'] ?? "",
                "uf" => $dados['pagadorUf'] ?? "",
                "cep" => $dados['pagadorCep'] ?? "",
            ],
            "beneficiarioFinal" => [
                "cpfCnpj" => $dados['beneficiarioCpfCnpj'] ?? "",
                "tipoPessoa" => $dados['beneficiarioTipoPessoa'] ?? "",
                "nome" => $dados['beneficiarioNome'] ?? "",
                "endereco" => $dados['beneficiarioEndereco'] ?? "",
                "bairro" => $dados['beneficiarioBairro'] ?? "",
                "cidade" => $dados['beneficiarioCidade'] ?? "",
                "uf" => $dados['beneficiarioUf'] ?? "",
                "cep" => $dados['beneficiarioCep'] ?? "",
            ],
            "formasRecebimento" => $dados['formasRecebimento'] ?? ["BOLETO", "PIX"],
        ];

        if (isset($dados['desconto']) && is_array($dados['desconto'])) {
            $json['desconto'] = [
                "taxa" => $this->formatarDecimalNumerico($dados['descontoTaxa'] ?? 0),
                "codigo" => $dados['descontoCodigo'] ?? "PERCENTUALDATAINFORMADA",
                "quantidadeDias" => isset($dados['descontoQuantidadeDias']) ? intval($dados['descontoQuantidadeDias']) : 0,
            ];
        }

        if (isset($dados['multa']) && is_array($dados['multa'])) {
            $json['multa'] = [
                "taxa" => $this->formatarDecimalNumerico($dados['multaTaxa'] ?? 0),
                "codigo" => $dados['multaCodigo'] ?? "PERCENTUAL",
            ];
        }

        if (isset($dados['mora']) && is_array($dados['mora'])) {
            $json['mora'] = [
                "taxa" => $this->formatarDecimalNumerico($dados['moraTaxa'] ?? 0),
                "codigo" => $dados['moraCodigo'] ?? "TAXAMENSAL",
            ];
        }

        if (isset($dados['mensagem']) && is_array($dados['mensagem'])) {
            $json['mensagem'] = [
                "linha1" => $dados['mensagemLinha1'] ?? "",
                "linha2" => $dados['mensagemLinha2'] ?? "",
                "linha3" => $dados['mensagemLinha3'] ?? "",
                "linha4" => $dados['mensagemLinha4'] ?? "",
                "linha5" => $dados['mensagemLinha5'] ?? "",
            ];
        }

        if (isset($dados['notaFiscal']) && is_array($dados['notaFiscal'])) {
            $json['notaFiscal'] = [
                "chaveNFe" => $dados['notaFiscalChaveNFe'] ?? "",
                "numero" => isset($dados['notaFiscalNumero']) ? intval($dados['notaFiscalNumero']) : 0,
                "serie" => isset($dados['notaFiscalSerie']) ? intval($dados['notaFiscalSerie']) : 0,
                "dataEmissao" => $dados['notaFiscalDataEmissao'] ?? "",
                "parcela" => isset($dados['notaFiscalParcela']) ? intval($dados['notaFiscalParcela']) : null,
                "naturezaOperacao" => $dados['notaFiscalNaturezaOperacao'] ?? "",
            ];
        }


        // Gera o JSON
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        
        return [
            'sucesso' => true,
            'dados' => $jsonString
        ];
    }

    /**
     * Monta o JSON para baixa de título na API do Bradesco
     * 
     * @param array $dados Dados do título a ser baixado
     * @return array ['sucesso' => bool, 'dados' => array|null, 'erros' => array|null]
     */
    function jsonBaixaTitulo($dados) {
        
        $erros = c_api_bradesco_json_builder_validate::validateDadosBaixaTitulo($dados);
        
        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }
        
        $json = [
            "cpfCnpj" => [
                "cpfCnpj" => intval($dados['CPF_CNPJ']),
                "filial" => intval($dados['FILIAL']),
                "controle" => intval($dados['CONTROLE'])
            ],
            "produto" => intval($dados['PRODUTO']),
            "negociacao" => intval($dados['NEGOCIACAO']),
            "nossoNumero" => intval($dados['NOSSO_NUMERO']),
            "sequencia" => isset($dados['SEQUENCIA']) ? intval($dados['SEQUENCIA']) : 0,
            "codigoBaixa" => intval($dados['CODIGO_BAIXA'])
        ];
        
        return [
            'sucesso' => true,
            'dados' => $json
        ];
    }

    function jsonAlteraTitulo($dados) {
        $erros = c_api_bradesco_json_builder_validate::validateDadosAlteraTitulo($dados);
        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }

        $json = [
            "cpfCnpj" => [
                "cpfCnpj" => intval($dados['CPF_CNPJ']),
                "filial" => intval($dados['FILIAL']),
                "controle" => intval($dados['CONTROLE'])
            ],
            "produto" => intval($dados['PRODUTO']),
            "negociacao" => intval($dados['NEGOCIACAO']),
            "nossoNumero" => intval($dados['NOSSO_NUMERO']),
            "dadosPagador" => [
                "sacado" => $dados['SACADO'] ?? $dados['NOME_SACADO'] ?? '',
                "cpfCnpjSacado" => [
                    "cpfCnpj" => intval($dados['CPF_CNPJ_SACADO'] ?? 0),
                    "filial" => intval($dados['FILIAL_SACADO'] ?? 0),
                    "controle" => intval($dados['CONTROLE_SACADO'] ?? 0)
                ],
                "endereco" => $dados['ENDERECO_SACADO'] ?? '',
                "cep" => intval($dados['CEP_SACADO'] ?? 0),
                "sufixo" => intval($dados['SUFIXO_SACADO'] ?? 0),
                "nomeSacador" => $dados['NOME_SACADOR'] ?? $dados['NOME_SACADOR_AVALISTA'] ?? '',
                "aceite" => 'N',
                "cpfCnpjSacador" => [
                    "cpfCnpj" => intval($dados['CPF_CNPJ_SACADOR'] ?? 0),
                    "filial" => intval($dados['FILIAL_SACADOR'] ?? 0),
                    "controle" => intval($dados['CONTROLE_SACADOR'] ?? 0)
                ],
                "emailSacado" => $dados['EMAIL_SACADO'] ?? ''
            ],
            "dadosTitulo" => [
                "seuNumero" => $dados['SEU_NUMERO'] ?? '',
                "dataEmissao" => intval($dados['DATA_EMISSAO'] ?? 0),
                "especie" => $dados['ESPECIE'] ?? $dados['ESPECIE_TITULO'] ?? '',
                "vencimento" => [
                    "dataVencimento" => intval($dados['DATA_VENCIMENTO'] ?? 0),
                    "tipoVencimento" => intval($dados['TIPO_VENCIMENTO'] ?? 0)
                ],
                "protesto" => [
                    "codInstrucaoProtesto" => intval($dados['COD_INSTRUCAO_PROTESTO'] ?? 0),
                    "qtdeDiasProtesto" => intval($dados['QTDE_DIAS_PROTESTO'] ?? 0)
                ],
                "decurso" => [
                    "codDecursoPrazo" => intval($dados['COD_DECURSO_PRAZO'] ?? 0),
                    "diasDecursoPrazo" => intval($dados['DIAS_DECURSO_PRAZO'] ?? 0)
                ],
                "abatimento" => [
                    "tipoAbatimento" => intval($dados['TIPO_ABATIMENTO'] ?? 1),
                    "valorAbatimento" => $this->formatarDecimalNumerico($dados['VALOR_ABATIMENTO'] ?? 0)
                ],
                "dataDesc1" => intval($dados['DATA_DESC1'] ?? 0),
                "valDesc1" => $this->formatarDecimalNumerico($dados['VAL_DESC1'] ?? $dados['VALOR_DESC1'] ?? 0),
                "codValDe1" => intval($dados['COD_VAL_DE1'] ?? 0),
                "tipoDesc1" => intval($dados['TIPO_DESC1'] ?? 0),

                "dataDesc2" => intval($dados['DATA_DESC2'] ?? 0),
                "valDesc2" => $this->formatarDecimalNumerico($dados['VAL_DESC2'] ?? $dados['VALOR_DESC2'] ?? 0),
                "codValDe2" => intval($dados['COD_VAL_DE2'] ?? 0),
                "tipoDesc2" => intval($dados['TIPO_DESC2'] ?? 0),

                "dataDesc3" => intval($dados['DATA_DESC3'] ?? 0),
                "valDesc3" => $this->formatarDecimalNumerico($dados['VAL_DESC3'] ?? $dados['VALOR_DESC3'] ?? 0),
                "codValDe3" => intval($dados['COD_VAL_DE3'] ?? 0),
                "tipoDesc3" => intval($dados['TIPO_DESC3'] ?? 0),

                "codigoControleParticipante" => $dados['CODIGO_CONTROLE_PARTICIPANTE'] ?? '',
                "indicadorAvisoSacado" => $dados['INDICADOR_AVISO_SACADO'] ?? '0',

                "comissaoPermanencia" => [
                    "diasComissaoPermanencia" => intval($dados['DIAS_COMISSAO_PERMANENCIA'] ?? 0),
                    "valorComissaoPermanencia" => $this->formatarDecimalNumerico($dados['VALOR_COMISSAO_PERMANENCIA'] ?? 0),
                    "codigoComissaoPermanencia" => intval($dados['CODIGO_COMISSAO_PERMANENCIA'] ?? 0)
                ],

                "codigoMulta" => intval($dados['CODIGO_MULTA'] ?? 0),
                "diasMulta" => intval($dados['DIAS_MULTA'] ?? 0),
                "valorMulta" => $this->formatarDecimalNumerico($dados['VALOR_MULTA'] ?? 0),

                "codigoNegativacao" => intval($dados['CODIGO_NEGATIVACAO'] ?? 1),
                "diasNegativacao" => intval($dados['DIAS_NEGATIVACAO'] ?? 0),

                "pagamentoParcial" => $dados['PAGAMENTO_PARCIAL'] ?? '',
                "qtdePagamentoParcial" => intval($dados['QTDE_PAGAMENTO_PARCIAL'] ?? 0)
            ]
        ];

        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return [
            'sucesso' => true,
            'dados' => $jsonString
        ];
    }

    function jsonConsultaTitulosLiquidados($dados) {

        $erros = c_api_bradesco_json_builder_validate::validateDadosConsultaTitulosLiquidados($dados);
        
        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }

        $json = [
            "cpfCnpj" => [
                "cpfCnpj" => $dados['CPF_CNPJ'],
                "filial" => $dados['FILIAL'],
                "controle" => $dados['CONTROLE']
            ],
            "produto" => $dados['PRODUTO'],
            "negociacao" => $dados['NEGOCIACAO'] ?? 0,
            "dataMovimentoDe" => isset($dados['DATA_MOVIMENTO_DE']) ? (int) $dados['DATA_MOVIMENTO_DE'] : 0,
            "dataMovimentoAte" => isset($dados['DATA_MOVIMENTO_ATE']) ? (int) $dados['DATA_MOVIMENTO_ATE'] : 0,
            "dataPagamentoDe" => isset($dados['DATA_PAGAMENTO_DE']) ? (int) $dados['DATA_PAGAMENTO_DE'] : 0,
            "dataPagamentoAte" => isset($dados['DATA_PAGAMENTO_ATE']) ? (int) $dados['DATA_PAGAMENTO_ATE'] : 0,
            "origemPagamento" => $dados['TIPO_REGISTRO'] ?? 0,
            "valorTituloDe" => $dados['VALOR_TITULO_DE'] ?? 0,
            "valorTituloAte" => $dados['VALOR_TITULO_ATE'] ?? 0,
            "paginaAnterior" => $dados['PAGINA_ANTERIOR'] ?? 0,
        ];
    
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
        return [
            'sucesso' => true,
            'dados' => $jsonString
        ];
    }

    function jsonManutencaoSplitPayment($dados) {
        return [
            "nossoNumero" => $dados['nosso_numero'],
            "valor"       => floatval($dados['valor']),
            // ...
        ];
    }

    function jsonConsultaSplitPayment($dados) {
        return [
            "nossoNumero" => $dados['nosso_numero'],
            "valor"       => floatval($dados['valor']),
            // ...
        ];
    }

    function jsonProtestoNegativacao($dados) {
        return [
            "nossoNumero" => $dados['nosso_numero'],
            "valor"       => floatval($dados['valor']),
            // ...
        ];
    }

    function jsonConsultaTituloPendente($dados) {

        $erros = c_api_bradesco_json_builder_validate::validateDadosConsultaTituloPendente($dados);
        
        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }
    
        $json = [
            "cpfCnpj" => [
                "cpfCnpj" => $dados['CPF_CNPJ'],
                "filial" => $dados['FILIAL'],
                "controle" => $dados['CONTROLE']
            ],
            "produto" => $dados['PRODUTO'],
            "negociacao" => $dados['NEGOCIACAO'] ?? 0,
            "nossoNumero" => $dados['NOSSO_NUMERO'] ?? 0,
            "cpfCnpjPagador" => [
                "cpfCnpj" => $dados['CPF_CNPJ_PAGADOR'] ?? 0,
                "filial" => $dados['FILIAL_PAGADOR'] ?? 0,
                "controle" => $dados['CONTROLE_PAGADOR'] ?? 0
            ],
            "dataVencimentoDe" => isset($dados['DATA_VENCIMENTO_DE']) ? (int) $dados['DATA_VENCIMENTO_DE'] : 0,
            "dataVencimentoAte" => isset($dados['DATA_VENCIMENTO_ATE']) ? (int) $dados['DATA_VENCIMENTO_ATE'] : 0,
            "dataRegistroDe" => isset($dados['DATA_REGISTRO_DE']) ? (int) $dados['DATA_REGISTRO_DE'] : 0,
            "dataRegistroAte" => isset($dados['DATA_REGISTRO_ATE']) ? (int) $dados['DATA_REGISTRO_ATE'] : 0,
            "valorTituloDe" => $dados['VALOR_TITULO_DE'] ?? 0,
            "faixaVencto" => $dados['FAIXA_VENCTO'] ?? 7,
            "paginaAnterior" => $dados['PAGINA_ANTERIOR'] ?? 0,
        ];
    
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
        return [
            'sucesso' => true,
            'dados' => $jsonString
        ];
    }

    function jsonConsultaTitulosBaixados($dados) {
        $erros = c_api_bradesco_json_builder_validate::validateDadosConsultaTitulosBaixados($dados);
        
        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }

        // valorTituloInicio pode ser informado como inteiro/decimal.
        // A API aceita numéricos; aqui mantemos 2 casas decimais no formato numérico.
        $valorTituloInicio = 0;
        if (isset($dados['VALOR_TITULO_INICIO']) && $dados['VALOR_TITULO_INICIO'] !== '' && $dados['VALOR_TITULO_INICIO'] !== null) {
            $valorTituloInicio = $this->formatarDecimalNumerico($dados['VALOR_TITULO_INICIO']);
        }

        $json = [
            // Layout de entrada: versao fixa 001
            "versao" => '001',
            "cpfCnpj" => [
                "cpfCnpj" => $dados['CPF_CNPJ'],
                "filial" => $dados['FILIAL'],
                "controle" => $dados['CONTROLE']
            ],
            "produto" => intval($dados['PRODUTO']),
            "negociacao" => $dados['NEGOCIACAO'] ?? '0',
            "dataVencimentoDe" => isset($dados['DATA_VENCIMENTO_DE']) ? (int)$dados['DATA_VENCIMENTO_DE'] : 0,
            "dataVencimentoAte" => isset($dados['DATA_VENCIMENTO_ATE']) ? (int)$dados['DATA_VENCIMENTO_ATE'] : 0,
            "valorTituloInicio" => $valorTituloInicio ?? 0,
            "codigoBaixa" => $dados['CODIGO_BAIXA'] ?? 0,
            "paginaAnterior" => $dados['PAGINA_ANTERIOR'] ?? 0,
        ];

        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return [
            'sucesso' => true,
            'dados' => $jsonString
        ];
    }

    /**
     * Monta JSON para consulta de título unitário
     * Endpoint: /boleto/cobranca-consulta/v1/consultar
     * @param array $dados Dados da consulta de título unitário.
     * @return array Array com sucesso, dados e erros.
     * @throws Exception Se houver erro na validação dos dados.
     * @throws Exception Se houver erro na consulta de título unitário.
     */
    function jsonConsultaTituloUnitario($dados) {
        $erros = c_api_bradesco_json_builder_validate::validateDadosConsultaTituloUnitario($dados);
        
        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }

        $statusTitulo = $dados['STATUS_TITULO'] ?? ($dados['STATUS'] ?? 0);

        $json = [
            "cpfCnpj" => [
                "cpfCnpj" => intval($dados['CPF_CNPJ']),
                "filial" => intval($dados['FILIAL']),
                "controle" => intval($dados['CONTROLE'])
            ],
            "produto" => intval($dados['PRODUTO']),
            "negociacao" => intval($dados['NEGOCIACAO']),
            "nossoNumero" => intval($dados['NOSSO_NUMERO']),
            "sequencia" => intval($dados['SEQUENCIA'] ?? 0),
            "status" => intval($statusTitulo),
        ];

        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return [
            'sucesso' => true,
            'dados' => $jsonString
        ];
    }

    function jsonRecuperarColecaoCobranca(array $dados) {

        $erros = c_api_inter_json_builder_validate::validateDadosRecuperarColecaoCobranca($dados);

        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }

        $query_array = [
            "dataInicial" => $dados['dataInicial'],
            "dataFinal" => $dados['dataFinal'],
            "filtrarDataPor" => $dados['filtrarDataPor'],
            "situacao" => $dados['situacao'],
            "pessoaPagadora" => $dados['pessoaPagadora'] ?? '',
            //"cpfCnpjPessoaPagadora" => $dados['cpfCnpjPessoaPagadora'] ?? '',
            "seuNumero" => $dados['seuNumero'] ?? '',
            "tipoCobranca" => $dados['tipoCobranca'] ?? '',
            "paginacao.itensPorPagina" => $dados['itensPorPagina'] ?? 50,
            "paginacao.paginaAtual" => $dados['paginaAtual'] ?? 0,
            "ordenarPor" => 'STATUS',
            "tipoOrdenacao" => $dados['tipoOrdenacao'] ?? 'ASC',
        ];

        return [
            'sucesso' => true,
            'query_array' => $query_array
        ];
    }

    // Mantém a função simples
    /**
     * Formata um valor decimal para 2 casas decimais.
     *
     * @param float $valor Valor a ser formatado.
     * @return float Valor formatado com 2 casas decimais.
     */
    function formatarDecimalNumerico($valor) {
        return round((float)$valor, 2);
    }


    /**
     * Mockup para consulta de coleção de cobranças (com paginação).
     *
     * A API Inter usa paginação base-0:
     *   - paginaAtual = 0 representa a primeira página
     *   - primeiraPagina = (paginaAtual === 0)
     *   - ultimaPagina   = (paginaAtual === totalPaginas - 1)
     *
     * @param array $dados {
     *     @type int  $paginaAtual    Página solicitada (base 0). Default: 0
     *     @type int  $itensPorPagina Itens por página. Default: 20
     *     @type int  $totalMockup    Total de cobranças simuladas no "banco". Default: 47
     * }
     * @return array
     */
    function getMockupColecaoCobranca(array $dados = []) {
        $paginaAtual    = isset($dados['paginaAtual'])    ? max(0, intval($dados['paginaAtual']))    : 0;
        $itensPorPagina = isset($dados['itensPorPagina']) ? max(1, intval($dados['itensPorPagina'])) : 20;
        $totalMockup    = isset($dados['totalMockup'])    ? max(0, intval($dados['totalMockup']))    : 47;

        $todasCobrancas = $this->gerarMockupListaCobrancas($totalMockup);
        $totalElementos = count($todasCobrancas);

        $totalPaginas = ($totalElementos === 0)
            ? 0
            : (int) ceil($totalElementos / $itensPorPagina);

        $offset      = $paginaAtual * $itensPorPagina;
        $cobrancas   = array_slice($todasCobrancas, $offset, $itensPorPagina);
        $numeroDeElementos = count($cobrancas);

        $primeiraPagina = ($paginaAtual === 0);
        $ultimaPagina   = ($totalPaginas === 0) ? true : ($paginaAtual >= $totalPaginas - 1);

        return [
            'sucesso'   => true,
            'http_code' => 200,
            'data' => [
                'totalPaginas'      => $totalPaginas,
                'totalElementos'    => $totalElementos,
                'tamanhoPagina'     => $itensPorPagina,
                'paginaAtual'       => $paginaAtual,
                'primeiraPagina'    => $primeiraPagina,
                'ultimaPagina'      => $ultimaPagina,
                'numeroDeElementos' => $numeroDeElementos,
                'cobrancas'         => $cobrancas,
            ],
        ];
    }

    /**
     * Gera uma lista determinística de cobranças simuladas, com variações
     * de situação, tipo de cobrança e pagadores para testes de paginação.
     *
     * @param int $quantidade Quantidade total de cobranças a gerar.
     * @return array
     */
    private function gerarMockupListaCobrancas($quantidade) {
        $situacoes = ['A_RECEBER', 'RECEBIDO', 'VENCIDO', 'CANCELADO', 'EXPIRADO'];
        $tipos     = ['SIMPLES', 'RECORRENTE', 'PARCELADO'];

        $pagadores = [
            ['nome' => 'Nome do pagador',     'cpfCnpj' => '01234567890'],
            ['nome' => 'Maria Oliveira',      'cpfCnpj' => '12345678901'],
            ['nome' => 'Carlos Pereira',      'cpfCnpj' => '23456789012'],
            ['nome' => 'Fernanda Lima',       'cpfCnpj' => '34567890123'],
            ['nome' => 'João Santos',         'cpfCnpj' => '45678901234'],
            ['nome' => 'Empresa XPTO LTDA',   'cpfCnpj' => '12345678000199'],
            ['nome' => 'Ana Beatriz Souza',   'cpfCnpj' => '56789012345'],
            ['nome' => 'Roberto Almeida',     'cpfCnpj' => '67890123456'],
            ['nome' => 'Comercial ABC ME',    'cpfCnpj' => '98765432000110'],
            ['nome' => 'Patrícia Nogueira',   'cpfCnpj' => '78901234567'],
        ];

        $lista        = [];
        $baseEmissao  = strtotime('2023-09-26');
        $baseSeqNumero = 36908;

        for ($i = 0; $i < $quantidade; $i++) {
            $situacao    = $situacoes[$i % count($situacoes)];
            $tipo        = $tipos[$i % count($tipos)];
            $pagador     = $pagadores[$i % count($pagadores)];

            $dataEmissao    = date('Y-m-d', strtotime("+{$i} days", $baseEmissao));
            $dataVencimento = date('Y-m-d', strtotime("+5 days",   strtotime($dataEmissao)));
            $dataSituacao   = $dataEmissao;

            $valorNominal = number_format(50 + ($i * 17.37), 2, '.', '');

            $seuNumero   = (string) ($baseSeqNumero + $i);
            $sufixoUuid  = str_pad(dechex($i), 4, '0', STR_PAD_LEFT);
            $codigoSol   = sprintf('mock-%04d-aaaa-bbbb-cccc-%s5432a', $i, $sufixoUuid);

            $nossoNumero = str_pad((string)($i + 10000000000), 11, '0', STR_PAD_LEFT);

            $lista[] = [
                'cobranca' => [
                    'codigoSolicitacao' => $codigoSol,
                    'seuNumero'         => $seuNumero,
                    'situacao'          => $situacao,
                    'dataSituacao'      => $dataSituacao,
                    'dataEmissao'       => $dataEmissao,
                    'dataVencimento'    => $dataVencimento,
                    'valorNominal'      => $valorNominal,
                    'tipoCobranca'      => $tipo,
                    'pagador' => [
                        'nome'    => $pagador['nome'],
                        'cpfCnpj' => $pagador['cpfCnpj'],
                    ],
                ],
                'boleto' => [
                    'nossoNumero'    => $nossoNumero,
                    'codigoBarras'   => $nossoNumero . '0123456789012345678901234567890123',
                    'linhaDigitavel' => $nossoNumero . '01234567890123456789012345678901234567',
                ],
                'pix' => [
                    'txid'          => str_pad((string)$i, 36, '0', STR_PAD_LEFT),
                    'pixCopiaECola' => sprintf('mockup-pix-copia-e-cola-%05d-BR.GOV.BCB.PIX...', $i),
                ],
            ];
        }

        return $lista;
    }
}