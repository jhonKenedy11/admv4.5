<?php

/**
 * @package   astecv3
 * @name      c_api_bradesco_service
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      03/12/2025
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_session_manager.php");
include_once($dir . "/c_api_bradesco_json_builder_validate.php");

class c_api_bradesco_json_builder
{



    function jsonRegistraBoleto($dados)
    {

        $erros = c_api_bradesco_json_builder_validate::validateDadosRegistroBoleto($dados);

        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }

        $json = [
            // Débito Automático e Identificação
            "debitoAutomatico" => isset($dados['DEBITO_AUTOMATICO']) ? $dados['DEBITO_AUTOMATICO'] : "N",

            "nuCPFCNPJ" => isset($dados['NU_CPFCNPJ']) ?  $dados['NU_CPFCNPJ'] : "00000000000",

            "filialCPFCNPJ" => isset($dados['FILIAL_CPF_CNPJ']) ? $dados['FILIAL_CPF_CNPJ'] : 0,
            "ctrlCPFCNPJ" => isset($dados['CTRL_CPFCNPJ']) ? $dados['CTRL_CPFCNPJ'] : 0,

            // Produto e Negociação
            "idProduto" => isset($dados['ID_PRODUTO']) ? intval($dados['ID_PRODUTO']) : 9,
            "nuNegociacao" => isset($dados['NU_NEGOCIACAO']) ? intval($dados['NU_NEGOCIACAO']) : 0,
            "nuTitulo" => isset($dados['NU_TITULO']) ? intval($dados['NU_TITULO']) : 0,
            "nuCliente" => $dados['NU_CLIENTE'],

            // Datas e Valores do Título
            "dtEmissaoTitulo" => isset($dados['DT_EMISSAO_TITULO']) ? $dados['DT_EMISSAO_TITULO'] : "",
            "dtVencimentoTitulo" => isset($dados['DT_VENCIMENTO_TITULO']) ? $dados['DT_VENCIMENTO_TITULO'] : "",
            "indicadorMoeda" => isset($dados['INDICADOR_MOEDA']) ? intval($dados['INDICADOR_MOEDA']) : 1,
            "vlNominalTitulo" => $this->formatarDecimalNumerico($dados['VL_NOMINAL_TITULO'] ?? 0),
            "cdEspecieTitulo" => isset($dados['CD_ESPECIE_TITULO']) ? intval($dados['CD_ESPECIE_TITULO']) : 1,
            "tpVencimento" => 0,

            // Protesto/Negativação
            // Informar 1 - para dias corridos ou 2 - para dias úteis
            "tpProtestoAutomaticoNegativacao" => isset($dados['TP_PROTESTO_AUTOMATICO_NEGATIVACAO']) ? intval($dados['TP_PROTESTO_AUTOMATICO_NEGATIVACAO']) : 0,
            //Se protesto → mínimo 3 - Se negativação → mínimo 5
            "prazoProtestoAutomaticoNegativacao" => isset($dados['PRAZO_PROTESTO_AUTOMATICO_NEGATIVACAO']) ? intval($dados['PRAZO_PROTESTO_AUTOMATICO_NEGATIVACAO']) : 0,

            // Controles
            //"controleParticipante" => isset($dados['CONTROLE_PARTICIPANTE']) ? $dados['CONTROLE_PARTICIPANTE'] : "",
            "cdPagamentoParcial" => isset($dados['CD_PAGAMENTO_PARCIAL']) ? $dados['CD_PAGAMENTO_PARCIAL'] : "N",
            "qtdePagamentoParcial" => isset($dados['QTDE_PAGAMENTO_PARCIAL']) ? intval($dados['QTDE_PAGAMENTO_PARCIAL']) : 0,
            "tipoPrazoDecursoTres" => isset($dados['TIPO_PRAZO_DECURSO_TRES']) ? intval($dados['TIPO_PRAZO_DECURSO_TRES']) : 0,

            // Juros
            "percentualJuros" => $this->formatarDecimalNumerico($dados['PERCENTUAL_JUROS'] ?? 0),
            "vlJuros" => $this->formatarDecimalNumerico($dados['VL_JUROS'] ?? 0),
            "qtdeDiasJuros" => isset($dados['QTDE_DIAS_JUROS']) ? intval($dados['QTDE_DIAS_JUROS']) : 0,

            // Multa
            "percentualMulta" => $this->formatarDecimalNumerico($dados['PERCENTUAL_MULTA'] ?? 0),
            "vlMulta" => $this->formatarDecimalNumerico($dados['VL_MULTA'] ?? 0),
            "qtdeDiasMulta" => isset($dados['QTDE_DIAS_MULTA']) ? intval($dados['QTDE_DIAS_MULTA']) : 0,

            // Desconto 1
            "percentualDesconto1" => $this->formatarDecimalNumerico($dados['PERCENTUAL_DESCONTO1'] ?? 0),
            "vlDesconto1" => $this->formatarDecimalNumerico($dados['VL_DESCONTO1'] ?? 0),
            "dataLimiteDesconto1" => isset($dados['DATA_LIMITE_DESCONTO1']) ? $dados['DATA_LIMITE_DESCONTO1'] : "",

            // Desconto 2
            "percentualDesconto2" => $this->formatarDecimalNumerico($dados['PERCENTUAL_DESCONTO2'] ?? 0),
            "vlDesconto2" => $this->formatarDecimalNumerico($dados['VL_DESCONTO2'] ?? 0),
            "dataLimiteDesconto2" => isset($dados['DATA_LIMITE_DESCONTO2']) ? $dados['DATA_LIMITE_DESCONTO2'] : "",

            // Desconto 3
            "percentualDesconto3" => $this->formatarDecimalNumerico($dados['PERCENTUAL_DESCONTO3'] ?? 0),
            "vlDesconto3" => $this->formatarDecimalNumerico($dados['VL_DESCONTO3'] ?? 0),
            "dataLimiteDesconto3" => isset($dados['DATA_LIMITE_DESCONTO3']) ? $dados['DATA_LIMITE_DESCONTO3'] : "",

            // Bonificação
            "prazoBonificacao" => isset($dados['PRAZO_BONIFICACAO']) ? intval($dados['PRAZO_BONIFICACAO']) : 0,
            "percentualBonificacao" => $this->formatarDecimalNumerico($dados['PERCENTUAL_BONIFICACAO'] ?? 0),
            "vlBonificacao" => $this->formatarDecimalNumerico($dados['VL_BONIFICACAO'] ?? 0),
            "dtLimiteBonificacao" => isset($dados['DT_LIMITE_BONIFICACAO']) ? $dados['DT_LIMITE_BONIFICACAO'] : "",

            // Abatimento e IOF
            "vlAbatimento" => $this->formatarDecimalNumerico($dados['VL_ABATIMENTO'] ?? 0),
            "vlIOF" => $this->formatarDecimalNumerico($dados['VL_IOF'] ?? 0),

            // Dados do Pagador
            "nomePagador" => isset($dados['NOME_PAGADOR']) ? $dados['NOME_PAGADOR'] : "",
            "logradouroPagador" => isset($dados['LOGRADOURO_PAGADOR']) ? $dados['LOGRADOURO_PAGADOR'] : "",
            "nuLogradouroPagador" => isset($dados['NU_LOGRADOURO_PAGADOR']) ? $dados['NU_LOGRADOURO_PAGADOR'] : "",
            "complementoLogradouroPagador" => isset($dados['COMPLEMENTO_LOGADOURO_PAGADOR']) ? $dados['COMPLEMENTO_LOGADOURO_PAGADOR'] : "",
            "cepPagador" => isset($dados['CEP_PAGADOR']) ? ($dados['CEP_PAGADOR']) : 8000000,
            "complementoCepPagador" => isset($dados['COMPLEMENTO_CEP_PAGADOR']) ? $dados['COMPLEMENTO_CEP_PAGADOR'] : 0,
            "bairroPagador" => isset($dados['BAIRRO_PAGADOR']) ? $dados['BAIRRO_PAGADOR'] : "",
            "municipioPagador" => isset($dados['MUNICIPIO_PAGADOR']) ? $dados['MUNICIPIO_PAGADOR'] : "",
            "ufPagador" => isset($dados['UF_PAGADOR']) ? $dados['UF_PAGADOR'] : "",
            "cdIndCpfcnpjPagador" => isset($dados['CD_IND_CPFCNPJ_PAGADOR']) ? intval($dados['CD_IND_CPFCNPJ_PAGADOR']) : 2,
            "nuCpfcnpjPagador" => isset($dados['NU_CPFCNPJ_PAGADOR']) ? intval($dados['NU_CPFCNPJ_PAGADOR']) : 0,
            "endEletronicoPagador" => isset($dados['END_ELETRONICO_PAGADOR']) ? $dados['END_ELETRONICO_PAGADOR'] : "",

            "dddFoneSacado" => 0,
            "foneSacado" => 0,

            //"bairroSacadorAvalista" => isset($dados['BAIRRO_SACADOR_AVALISTA']) ? $dados['BAIRRO_SACADOR_AVALISTA'] : "",
            //"cdIndCpfcnpjSacadorAvalista" => 0,
            //"nuCpfcnpjSacadorAvalista" => isset($dados['NU_CPFCNPJ_SACADOR_AVALISTA']) ? intval($dados['NU_CPFCNPJ_SACADOR_AVALISTA']) : 0,
            //"cepSacadorAvalista" => isset($dados['CEP_SACADOR_AVALISTA']) ? intval($dados['CEP_SACADOR_AVALISTA']) : "",
            //"nomeSacadorAvalista" => isset($dados['NOME_SACADOR_AVALISTA']) ? $dados['NOME_SACADOR_AVALISTA'] : "",
            //"nuLogradouroSacadorAvalista" => isset($dados['NU_LOGRADOURO_SACADOR_AVALISTA']) ? intval($dados['NU_LOGRADOURO_SACADOR_AVALISTA']) : "",

            //"complementoCepSacadorAvalista" => isset($dados['COMPLEMENTO_CEP_SACADOR_AVALISTA']) ? $dados['COMPLEMENTO_CEP_SACADOR_AVALISTA'] : "",
            //"complementoLogradouroSacadorAvalista" => isset($dados['COMPLEMENTO_LOGADOURO_SACADOR_AVALISTA']) ? $dados['COMPLEMENTO_LOGADOURO_SACADOR_AVALISTA'] : "",
            //"dddFoneSacadorAvalista" =>  0,
            //"enderecoSacadorAvalista" => isset($dados['ENDERECO_SACADOR_AVALISTA']) ? $dados['ENDERECO_SACADOR_AVALISTA'] : "",
            //"foneSacadorAvalista" => 0,
            //"logradouroSacadorAvalista" => isset($dados['LOGRADOURO_SACADOR_AVALISTA']) ? $dados['LOGRADOURO_SACADOR_AVALISTA'] : "",
            //"municipioSacadorAvalista" => isset($dados['MUNICIPIO_SACADOR_AVALISTA']) ? $dados['MUNICIPIO_SACADOR_AVALISTA'] : "",
            //"ufSacadorAvalista" => isset($dados['UF_SACADOR_AVALISTA']) ? $dados['UF_SACADOR_AVALISTA'] : "",


            // Dados Bancários para Débito Automático
            "bancoDoDebAutomatico" => 0,
            "agenciaDoDebAutomatico" => 0,
            "digitoAgenciaDoDebAutomat" => isset($dados['DIGITO_AGENCIA_DEB_AUTOMATICO']) ? intval($dados['DIGITO_AGENCIA_DEB_AUTOMATICO']) : 0,
            "contaDoDebAutomatico" => isset($dados['CONTA_DEB_AUTOMATICO']) ? intval($dados['CONTA_DEB_AUTOMATICO']) : 0,
            "razaoDoDebAutomatico" => isset($dados['RAZAO_DEB_AUTOMATICO']) ? intval($dados['RAZAO_DEB_AUTOMATICO']) : 0,

            // Dados do Protesto
            "codBancoDoProtesto" => isset($dados['COD_BANCO_PROTESTO']) ? intval($dados['COD_BANCO_PROTESTO']) : 0,
            "agenciaDoProtesto" => 0,
        ];

        // Mensagem (campo único)
        if (isset($dados['MENSAGEM']) && !empty($dados['MENSAGEM'])) {
            $json['listaMsgs'] = [
                ["mensagem" => $dados['MENSAGEM']]
            ];
        }


        // Gera o JSON
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        //$jsonString = preg_replace('/"nuCPFCNPJ":"(\d+)"/', '"nuCPFCNPJ":$1', $jsonString);

        // Corrige campos específicos que precisam de .00
        $camposDecimais = [
            'vlNominalTitulo',
            'percentualJuros',
            'vlJuros',
            'vlMulta',
            'percentualDesconto1',
            'vlDesconto1',
            'percentualBonificacao',
            'vlBonificacao',
            'vlAbatimento',
            'vlIOF'
        ];

        foreach ($camposDecimais as $campo) {
            // Substitui "campo":123.4 ou 123.45 → 123.40 ou mantém 123.45 (normaliza para 2 casas)
            $jsonString = preg_replace(
                '/"' . $campo . '":(\d+\.\d)([,}])/',
                '"' . $campo . '":${1}0$2',
                $jsonString
            );
            // Substitui "campo":123 (inteiro, sem ponto) → 123.00
            $jsonString = preg_replace(
                '/"' . $campo . '":(\d+)(?!\.)([,}])/',
                '"' . $campo . '":${1}.00$2',
                $jsonString
            );
        }


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
    function jsonBaixaTitulo($dados)
    {

        $erros = c_api_bradesco_json_builder_validate::validateDadosBaixaTitulo($dados);

        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }

        // Monta o JSON para baixa de título consolidado
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


        // Gera o JSON
        $jsonString = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return [
            'sucesso' => true,
            'dados' => $jsonString
        ];
    }

    function jsonAlteraTitulo($dados)
    {
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

                "codigoControleParticipante" => $dados['CONTROLE_PARTICIPANTE'] ?? '',
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

    function jsonConsultaTitulosLiquidados($dados)
    {

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
            "produto" => intval($dados['PRODUTO']),
            "negociacao" => intval($dados['NEGOCIACAO'] ?? 0),
            "dataMovimentoDe" => isset($dados['DATA_MOVIMENTO_DE']) ? (int) $dados['DATA_MOVIMENTO_DE'] : 0,
            "dataMovimentoAte" => isset($dados['DATA_MOVIMENTO_ATE']) ? (int) $dados['DATA_MOVIMENTO_ATE'] : 0,
            "dataPagamentoDe" => isset($dados['DATA_PAGAMENTO_DE']) ? (int) $dados['DATA_PAGAMENTO_DE'] : 0,
            "dataPagamentoAte" => isset($dados['DATA_PAGAMENTO_ATE']) ? (int) $dados['DATA_PAGAMENTO_ATE'] : 0,
            "origemPagamento" => intval($dados['TIPO_REGISTRO'] ?? 0),
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

    function jsonManutencaoSplitPayment($dados)
    {
        return [
            "nossoNumero" => $dados['nosso_numero'],
            "valor"       => floatval($dados['valor']),
            // ...
        ];
    }

    function jsonConsultaSplitPayment($dados)
    {
        return [
            "nossoNumero" => $dados['nosso_numero'],
            "valor"       => floatval($dados['valor']),
            // ...
        ];
    }

    function jsonProtestoNegativacao($dados)
    {
        return [
            "nossoNumero" => $dados['nosso_numero'],
            "valor"       => floatval($dados['valor']),
            // ...
        ];
    }

    function jsonConsultaTituloPendente(array $dados)
    {

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
                "cpfCnpj" => $dados['CPF_CNPJ_PAGADOR'] == '' ? 0 : $dados['CPF_CNPJ_PAGADOR'],
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

    function jsonConsultaTitulosBaixados(array $dados)
    {
        $erros = c_api_bradesco_json_builder_validate::validateDadosConsultaTitulosBaixados($dados);

        if (!empty($erros)) {
            return [
                'sucesso' => false,
                'erros' => $erros
            ];
        }

        $json = [
            "versao" => 1,
            "cpfCnpj" => [
                "cpfCnpj" => $dados['CPF_CNPJ'],
                "filial" => (int) $dados['FILIAL'],
                "controle" => (int) $dados['CONTROLE']
            ],
            "produto" => (int) $dados['PRODUTO'],
            "negociacao" => (int) ($dados['NEGOCIACAO'] ?? 0),
            "dataVencimentoDe" => isset($dados['DATA_VENCIMENTO_DE']) ? (int)$dados['DATA_VENCIMENTO_DE'] : 0,
            "dataVencimentoAte" => isset($dados['DATA_VENCIMENTO_ATE']) ? (int)$dados['DATA_VENCIMENTO_ATE'] : 0,
            "valorTituloInicio" => $valorTituloInicio ?? 1,
            "codigoBaixa" => (int)($dados['CODIGO_BAIXA'] ?: 57),
            "paginaAnterior" => (int)($dados['PAGINA_ANTERIOR'] ?: 0),
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
     */
    function jsonConsultaTituloUnitario($dados)
    {
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

    // Mantém a função simples
    function formatarDecimalNumerico($valor)
    {
        return round((float)$valor, 2);
    }
}
