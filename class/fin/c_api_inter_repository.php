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
include_once($dir . "/../../bib/c_database_pdo.php");

class c_api_inter_repository
{

    public $m_pdo = NULL;

    /**
     * Busca os dados para emitir uma cobrança
     * @param int $id_lancamento ID do lançamento financeiro
     * @return array Dados da cobrança
     */
    function getDadosEmitirCobranca($id_lancamento): array
    {

        // API Inter - Cobrança (v3)
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT 
            CONCAT(FCO.CONTACORRENTE, FCO.CONTA_CORRENTE_DIGITO) AS contaCorrenteHeader,
            CAST(FL.ID AS CHAR) AS seuNumero,
            ROUND(IFNULL(FL.TOTAL, 0), 2) AS valorNominal,
            DATE_FORMAT(FL.VENCIMENTO, '%Y-%m-%d') AS dataVencimento,
            NULL AS numDiasAgenda,
            LEFT(COALESCE(FC.EMAIL, FC.EMAILNFE, ''), 100) AS pagadorEmail,
            LEFT(
                IFNULL(
                    REPLACE(
                        REPLACE(
                            REPLACE(FC.FONEAREA, '(', ''),
                             ')', ''), 
                        ' ', ''), 
                    ''), 
                2
            ) AS pagadorDdd, 
            LEFT(
                IFNULL(
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(FC.FONE, '(', ''), 
                            ')', ''), 
                        '-', ''), 
                    ' ', ''), 
                ''), 
            9
            ) AS pagadorTelefone,
            LEFT(IFNULL(FC.NUMERO, ''), 10) AS pagadorNumero,
            LEFT(IFNULL(FC.COMPLEMENTO, ''), 30) AS pagadorComplemento,
            REPLACE(REPLACE(REPLACE(IFNULL(FC.CNPJCPF, ''), '.', ''), '/', ''), '-', '') AS pagadorCpfCnpj,
            CASE WHEN FC.PESSOA = 'F' THEN 'FISICA' ELSE 'JURIDICA' END AS pagadorTipoPessoa,
            LEFT(IFNULL(FC.NOME, ''), 100) AS pagadorNome,
            LEFT(CONCAT(IFNULL(FC.TIPOEND,''), ' ', IFNULL(FC.ENDERECO,'')), 100) AS pagadorEndereco,
            LEFT(IFNULL(FC.BAIRRO, ''), 60) AS pagadorBairro,
            LEFT(IFNULL(FC.CIDADE, ''), 60) AS pagadorCidade,
            LEFT(IFNULL(FC.UF, ''), 2) AS pagadorUf,
            CONCAT(
                LEFT(
                    REPLACE(REPLACE(IFNULL(FC.CEP, ''), '-', ''), '.', ''),
                    8
                )
            ) AS pagadorCep,
            ROUND(IFNULL(FCO.DESCONTOBONIFICACAO, 0), 2) AS descontoTaxa,
            'PERCENTUALDATAINFORMADA' AS descontoCodigo,
            NULL AS descontoQuantidadeDias,
            ROUND(IFNULL(FCO.MULTA, 0), 2) AS multaTaxa,
            'PERCENTUAL' AS multaCodigo,
            ROUND(IFNULL(FCO.JUROS, 0), 2) AS moraTaxa,
            'VALORDIA' AS moraCodigo,
            LEFT(IFNULL(FCO.MSGBLOQUETO, ''), 78) AS mensagemLinha1,
            NULL AS mensagemLinha2,
            NULL AS mensagemLinha3,
            NULL AS mensagemLinha4,
            NULL AS mensagemLinha5,
            REPLACE(REPLACE(REPLACE(IFNULL(AE.CNPJ, ''), '.', ''), '/', ''), '-', '') AS beneficiarioCpfCnpj,
            'JURIDICA' AS beneficiarioTipoPessoa,
            LEFT(IFNULL(AE.NOMEEMPRESA, ''), 100) AS beneficiarioNome,
            LEFT(CONCAT(IFNULL(AE.TIPOEND,''), ' ', IFNULL(AE.ENDERECO,''), ' ', IFNULL(AE.NUMERO,'')), 100) AS beneficiarioEndereco,
            LEFT(IFNULL(AE.BAIRRO, ''), 60) AS beneficiarioBairro,
            LEFT(IFNULL(AE.CIDADE, ''), 60) AS beneficiarioCidade,
            LEFT(IFNULL(AE.UF, ''), 2) AS beneficiarioUf,
            LEFT(IFNULL(AE.CEP, ''), 8) AS beneficiarioCep,
            NULL AS formasRecebimento,
            NULL AS notaFiscalChaveNFe,
            NULL AS notaFiscalNumero,
            NULL AS notaFiscalSerie,
            NULL AS notaFiscalDataEmissao,
            NULL AS notaFiscalParcela,
            NULL AS notaFiscalNaturezaOperacao,
            FL.CONTA AS conta_bancaria 
            FROM FIN_LANCAMENTO FL 
            LEFT JOIN FIN_CLIENTE FC ON FL.PESSOA = FC.CLIENTE
            LEFT JOIN AMB_EMPRESA AE ON AE.CENTROCUSTO = FL.CENTROCUSTO
            LEFT JOIN FIN_CONTA FCO ON FCO.CONTA = FL.CONTA
            WHERE FL.ID = :id_lancamento");


        $banco->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);

        //$query = $banco->queryString();

        $banco->execute();

        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados;
    }


    /**
     * Busca os dados da cobrança pelo ID da tabela FIN_API_INTER
     * 
     * @param int $id ID da tabela FIN_API_INTER
     * @return array Dados da cobrança
     */
    function getDadosRecuperarCobranca($id): array|bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT 
                FI.CODIGO_SOLICITACAO, 
                FI.CONTA_CORRENTE_HEADER,
                FL.CONTA AS conta_bancaria
            FROM FIN_API_INTER FI 
            LEFT JOIN FIN_LANCAMENTO FL ON FL.ID = FI.ID_LANCAMENTO
            WHERE FI.ID = :id 
            AND (FI.SITUACAO NOT IN ('CANCELADO') OR FI.SITUACAO IS NULL)
        ");
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados;
    }

    /**
     * Busca os dados da cobrança pelo ID do lançamento financeiro
     * 
     * @param int $id ID da tabela FIN_API_INTER
     * @return array Dados da cobrança
     */
    function getDadosCobrancaIdLancamento(int $id): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT 
                FI.CODIGO_SOLICITACAO, 
                FI.CONTA_CORRENTE_HEADER,
                FL.ID AS ID_LANCAMENTO,
                FL.CONTA AS conta_bancaria
            FROM FIN_API_INTER FI 
            LEFT JOIN FIN_LANCAMENTO FL ON FL.ID = FI.ID_LANCAMENTO
            WHERE FI.ID = :id
        ");
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados;
    }

    /**
     * Busca os dados da cobrança pelo ID da tabela FIN_API_INTER
     * 
     * @param int $id ID da tabela FIN_API_INTER
     * @return array Dados da cobrança
     */
    function getDadosPagarCobranca(int $id): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT 
                CODIGO_SOLICITACAO, 
                CONTA_CORRENTE_HEADER 
            FROM FIN_API_INTER FI
            WHERE FI.ID = :id
        ");

        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados;
    }

    /**
     * Busca ID_LANCAMENTO na tabela FIN_API_INTER
     * 
     * @param int $id ID da tabela FIN_API_INTER
     * @return int|null ID_LANCAMENTO ou null se não encontrado
     */
    static function getIdLancamento(int $id): ?int
    {
        $banco = new c_banco_pdo();

        $banco->prepare("
            SELECT ID_LANCAMENTO FROM FIN_API_INTER WHERE ID = :id
        ");

        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        $resultado = $banco->fetch(PDO::FETCH_ASSOC) ?? null;

        if ($resultado === null) {
            return null;
        }

        return $resultado['ID_LANCAMENTO'];
    }

    /**
     * Atualiza o lançamento financeiro com o ID da API Bradesco e o JSON retornado
     * 
     * @param int $id_lancamento ID do lançamento financeiro
     * @param array $response Response da API
     * @param bool $cancela Se é para cancelar a cobrança
     * @return bool Sucesso da atualização
     */
    static function updateLancamento(int $id_lancamento, array $response, bool $cancela = false): bool
    {

        $banco = new c_banco_pdo();
        $sql = "UPDATE FIN_LANCAMENTO SET 
                    NOSSONUMERO = :nosso_numero,
                    REMESSANUM = :remessa_numero,
                    REMESSAARQ = :remessa_arquivo,
                    REMESSADATA = :remessa_data,
                    USERCHANGE = :user_change,
                    DATECHANGE = :date_change 
                WHERE ID = :id_lancamento";
        $banco->prepare($sql);

        $banco->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);
        $banco->bindValue(':user_change', $response["user"] ?? null, PDO::PARAM_INT);
        $banco->bindValue(':date_change', date('Y-m-d H:i:s'), PDO::PARAM_STR);

        // fluxo de cancelamento
        if ($cancela) {
            $banco->bindValue(':nosso_numero',    null, PDO::PARAM_NULL);
            $banco->bindValue(':remessa_numero',  null, PDO::PARAM_NULL);
            $banco->bindValue(':remessa_arquivo', null, PDO::PARAM_NULL);
            $banco->bindValue(':remessa_data',    null, PDO::PARAM_NULL);
        } else {
            // fluxo de emissao
            $nosso_numero = (int) $response["response"]["boleto"]["nossoNumero"] ?? null;

            $banco->bindValue(':nosso_numero',    $nosso_numero, PDO::PARAM_INT);
            $banco->bindValue(':remessa_numero',  $response["id"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':remessa_arquivo', 'API_INTER', PDO::PARAM_STR);
            $banco->bindValue(':remessa_data',    date('Y-m-d'), PDO::PARAM_STR);
        }

        $banco->execute();

        $resultado = $banco->rowCount() > 0;

        return $resultado;
    }

    /**
     * Persiste emissão de cobrança (API Inter) em FIN_API_INTER — por enquanto só ID_LANCAMENTO, CODIGO_SOLICITACAO e CREATED_USER.
     * O corpo da API é no formato {"codigoSolicitacao":"uuid"}; pode vir em response_array, body ou no próprio $dados.
     *
     * @param int $id_lancamento ID FIN_LANCAMENTO
     * @param array $dados created_user / user_id e response_array ou body com codigoSolicitacao (raiz ou em cobranca)
     * @return array { sucesso: bool, id?: int, mensagem?: string }
     */
    function insertParcialEmitirCobranca($id_lancamento, $dados, $conta_corrente_header)
    {
        try {
            if (isset($dados['response_array']) && is_array($dados['response_array'])) {
                $body = $dados['response_array'];
            } elseif (isset($dados['body']) && is_array($dados['body'])) {
                $body = $dados['body'];
            } elseif (isset($dados['codigoSolicitacao'])) {
                $body = $dados;
            } else {
                $body = [];
            }

            $codigo = null;
            if (isset($body['codigoSolicitacao']) && trim((string) $body['codigoSolicitacao']) !== '') {
                $codigo = trim((string) $body['codigoSolicitacao']);
            } elseif (isset($body['cobranca']['codigoSolicitacao']) && trim((string) $body['cobranca']['codigoSolicitacao']) !== '') {
                $codigo = trim((string) $body['cobranca']['codigoSolicitacao']);
            }

            $createdUser = (int) ($dados['created_user'] ?? $dados['user_id'] ?? 0);

            $banco = new c_banco_pdo();
            $banco->prepare("
                INSERT INTO FIN_API_INTER (
                    ID_LANCAMENTO,
                    CODIGO_SOLICITACAO,
                    CONTA_CORRENTE_HEADER,
                    CREATED_USER
                ) VALUES (
                    :id_lancamento,
                    :codigo_solicitacao,
                    :conta_corrente_header,
                    :created_user
                )
            ");

            $banco->bindValue(':id_lancamento', (int) $id_lancamento, PDO::PARAM_INT);
            $banco->bindValue(':codigo_solicitacao', $codigo, $codigo === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
            $banco->bindValue(':conta_corrente_header', $conta_corrente_header, PDO::PARAM_STR);
            $banco->bindValue(':created_user', $createdUser, PDO::PARAM_INT);

            $banco->execute();

            return [
                'sucesso' => true,
                'id' => (int) $banco->lastInsertId(),
            ];
        } catch (Exception $e) {

            error_log('Erro ao inserir FIN_API_INTER: ' . $e->getMessage());

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao inserir FIN_API_INTER',
                'erros' => [$e->getMessage()],
                'http_code' => 500,
            ];
        }
    }

    function updateRecuperarCobranca(array $dados): bool
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "
                UPDATE FIN_API_INTER SET
                    SEU_NUMERO = :seu_numero,
                    DATA_EMISSAO = :data_emissao,
                    DATA_VENCIMENTO = :data_vencimento,
                    VALOR_NOMINAL = :valor_nominal,
                    TIPO_COBRANCA = :tipo_cobranca,
                    SITUACAO = :situacao,
                    DATA_SITUACAO = :data_situacao,
                    VALOR_TOTAL_RECEBIDO = :valor_total_recebido,
                    ORIGEM_RECEBIMENTO = :origem_recebimento,
                    ARQUIVADA = :arquivada,
                    PAGADOR_EMAIL = :pagador_email,
                    PAGADOR_DDD = :pagador_ddd,
                    PAGADOR_TELEFONE = :pagador_telefone,
                    PAGADOR_NUMERO = :pagador_numero,
                    PAGADOR_COMPLEMENTO = :pagador_complemento,
                    PAGADOR_CPFCNPJ = :pagador_cpf_cnpj,
                    PAGADOR_TIPO_PESSOA = :pagador_tipo_pessoa,
                    PAGADOR_NOME = :pagador_nome,
                    PAGADOR_ENDERECO = :pagador_endereco,
                    PAGADOR_BAIRRO = :pagador_bairro,
                    PAGADOR_CIDADE = :pagador_cidade,
                    PAGADOR_UF = :pagador_uf,
                    PAGADOR_CEP = :pagador_cep,
                    BOLETO_NOSSO_NUMERO = :boleto_nosso_numero,
                    BOLETO_CODIGO_BARRAS = :boleto_codigo_barras,
                    BOLETO_LINHA_DIGITAVEL = :boleto_linha_digitavel,
                    PIX_TXID = :pix_txid,
                    PIX_COPIA_E_COLA = :pix_copia_e_cola,
                    NF_CHAVE_NFE = :nf_chave_nfe,
                    NF_NUMERO = :nf_numero,
                    NF_SERIE = :nf_serie,
                    NF_DATA_EMISSAO = :nf_data_emissao,
                    NF_PARCELA = :nf_parcela,
                    NF_NATUREZA_OPERACAO = :nf_natureza_operacao,
                    JSON_RETORNO_COMPLETO = :json_retorno_completo,
                    CREATED_USER = :created_user
                WHERE ID = :id
            ";
            $banco->prepare($sql);

            // Separacao de acordo com a resposta da API
            // Geral
            $banco->bindValue(':id', (int) $dados['id'], PDO::PARAM_INT);
            $banco->bindValue(':seu_numero', $dados["response"]["cobranca"]["seuNumero"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':data_emissao', $dados["response"]["cobranca"]["dataEmissao"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':data_vencimento', $dados["response"]["cobranca"]["dataVencimento"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':valor_nominal', $dados["response"]["cobranca"]["valorNominal"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':tipo_cobranca', $dados["response"]["cobranca"]["tipoCobranca"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':situacao', $dados["response"]["cobranca"]["situacao"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':data_situacao', $dados["response"]["cobranca"]["dataSituacao"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':valor_total_recebido', $dados["response"]["cobranca"]["valorTotalRecebido"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':origem_recebimento', $dados["response"]["cobranca"]["origemRecebimento"] ?? null, PDO::PARAM_STR);

            // Tratamento de null para INT
            $arquivada = isset($dados["response"]["cobranca"]["arquivada"])
                ? (int) filter_var($dados["response"]["cobranca"]["arquivada"], FILTER_VALIDATE_BOOLEAN)
                : 0;
            $banco->bindValue(':arquivada', $arquivada, PDO::PARAM_INT);

            // PAGADOR
            $banco->bindValue(':pagador_email', $dados["response"]["cobranca"]["pagador"]["email"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_ddd', $dados["response"]["cobranca"]["pagador"]["ddd"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_telefone', $dados["response"]["cobranca"]["pagador"]["telefone"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_numero', $dados["response"]["cobranca"]["pagador"]["numero"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_complemento', $dados["response"]["cobranca"]["pagador"]["complemento"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_cpf_cnpj', $dados["response"]["cobranca"]["pagador"]["cpfCnpj"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_tipo_pessoa', $dados["response"]["cobranca"]["pagador"]["tipoPessoa"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_nome', $dados["response"]["cobranca"]["pagador"]["nome"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_endereco', $dados["response"]["cobranca"]["pagador"]["endereco"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_bairro', $dados["response"]["cobranca"]["pagador"]["bairro"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_cidade', $dados["response"]["cobranca"]["pagador"]["cidade"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_uf', $dados["response"]["cobranca"]["pagador"]["uf"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pagador_cep', $dados["response"]["cobranca"]["pagador"]["cep"] ?? null, PDO::PARAM_STR);

            // Boleto
            $banco->bindValue(':boleto_nosso_numero', $dados["response"]["boleto"]["nossoNumero"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':boleto_codigo_barras', $dados["response"]["boleto"]["codigoBanco"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':boleto_linha_digitavel', $dados["response"]["boleto"]["linhaDigitavel"] ?? null, PDO::PARAM_STR);

            // PIX
            $banco->bindValue(':pix_txid', $dados["response"]["pix"]["txid"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':pix_copia_e_cola', $dados["response"]["pix"]["pixCopiaECola"] ?? null, PDO::PARAM_STR);

            // NF
            $banco->bindValue(':nf_chave_nfe', $dados["response"]["notaFiscal"]["chaveNfe"] ?? null, PDO::PARAM_STR);

            // Tratamento de null para INT
            $nf_numero = $dados["response"]["notaFiscal"]["numero"] ?? null;
            if ($nf_numero === null) {
                $banco->bindValue(':nf_numero', null, PDO::PARAM_NULL);
            } else {
                $banco->bindValue(':nf_numero', (int)$nf_numero, PDO::PARAM_INT);
            }

            // Tratamento de null para INT
            $nf_serie = $dados["response"]["notaFiscal"]["serie"] ?? null;
            if ($nf_serie === null) {
                $banco->bindValue(':nf_serie', null, PDO::PARAM_NULL);
            } else {
                $banco->bindValue(':nf_serie', (int)$nf_serie, PDO::PARAM_INT);
            }

            // Tratamento de null para INT
            $nf_parcela = $dados["response"]["notaFiscal"]["parcela"] ?? null;
            if ($nf_parcela === null) {
                $banco->bindValue(':nf_parcela', null, PDO::PARAM_NULL);
            } else {
                $banco->bindValue(':nf_parcela', (int)$nf_parcela, PDO::PARAM_INT);
            }

            $banco->bindValue(':nf_data_emissao', $dados["response"]["notaFiscal"]["dataEmissao"] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':nf_natureza_operacao', $dados["response"]["notaFiscal"]["naturezaOperacao"] ?? null, PDO::PARAM_STR);

            // OTS
            $banco->bindValue(':json_retorno_completo', $dados["json_retorno_completo"] ?? null, PDO::PARAM_STR);

            $createdUser = (int) ($dados['user'] ?? $dados['user_id'] ?? 0);
            $banco->bindValue(':created_user', $createdUser, PDO::PARAM_INT);
            $banco->execute();

            $resultado = $banco->rowCount() > 0;

            if ($resultado) {

                return true;
            } else {

                return false;
            }
        } catch (Exception $e) {

            error_log('Erro ao atualizar FIN_API_INTER: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Atualiza a situação da cobrança para CANCELADO na tabela FIN_API_INTER
     *
     * @param int $id_tabela_api ID da tabela FIN_API_INTER
     * @return bool Sucesso da atualização
     */
    function updateCancelarCobranca(int $id_tabela_api): bool
    {
        try {
            $banco = new c_banco_pdo();
            $banco->prepare("
                UPDATE FIN_API_INTER SET 
                    SITUACAO = 'CANCELADO',
                    DATA_SITUACAO = :data_situacao
                WHERE ID = :id
            ");
            $banco->bindValue(':id', $id_tabela_api, PDO::PARAM_INT);
            $banco->bindValue(':data_situacao', date('Y-m-d'), PDO::PARAM_STR);
            $banco->execute();

            if ($banco->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log('Erro ao cancelar cobrança Inter: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza a situação da cobrança para RECEBIDO na tabela FIN_API_INTER
     *
     * @param int $id_tabela_api ID da tabela FIN_API_INTER
     * @return bool Sucesso da atualização
     */
    function updatePagarCobranca(int $id_tabela_api): bool
    {
        try {
            $banco = new c_banco_pdo();
            $banco->prepare("
                UPDATE FIN_API_INTER SET 
                    SITUACAO = 'RECEBIDO',
                    DATA_SITUACAO = :data_situacao
                WHERE ID = :id
            ");
            $banco->bindValue(':id', $id_tabela_api, PDO::PARAM_INT);
            $banco->bindValue(':data_situacao', date('Y-m-d'), PDO::PARAM_STR);
            $banco->execute();

            if ($banco->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log('Erro ao pagar cobrança Inter: ' . $e->getMessage());
            return false;
        }
    }


    /**
     * Salva o PDF da cobrança na tabela FIN_API_INTER
     *
     * @param int $id ID da tabela FIN_API_INTER
     * @param string $pdf_base64 PDF em base64
     * @return bool Sucesso da salvamento
     */
    function savePdf(int $id, string $pdf_base64): bool
    {

        $binary_pdf = base64_decode($pdf_base64);

        if (!$binary_pdf) {
            error_log('Erro ao decodificar PDF base64: ' . $pdf_base64);
            return false;
        }

        $banco = new c_banco_pdo();
        $sql = "UPDATE FIN_API_INTER SET PDF_BINARIO = :pdf_binario WHERE ID = :id";
        $banco->prepare($sql);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->bindParam(':pdf_binario', $binary_pdf, PDO::PARAM_LOB);
        $banco->execute();

        if ($banco->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Insere registro de log na tabela FIN_API_BRADESCO_LOG
     * Salva todas as requisições (sucesso e erro) para auditoria
     * 
     * @param array $dados Dados do log
     * @return array Resultado da inserção
     */
    function insertLog($dados)
    {
        try {
            $banco = new c_banco_pdo();

            $banco->prepare("
                INSERT INTO FIN_API_BANCOS_LOG (
                    BANCO,
                    ID_LANCAMENTO,
                    ID_CONTA,
                    TIPO_OPERACAO,
                    AMBIENTE,
                    ENDPOINT,
                    HTTP_CODE,
                    SUCESSO,
                    COD_RETORNO_API,
                    MENSAGEM_API,
                    ERROS_VALIDACAO,
                    JSON_ENVIADO,
                    JSON_RETORNO,
                    IP_ORIGEM,
                    CREATED_USER
                ) VALUES (
                    :banco,
                    :id_lancamento,
                    :id_conta,
                    :tipo_operacao,
                    :ambiente,
                    :endpoint,
                    :http_code,
                    :sucesso,
                    :cod_retorno_api,
                    :mensagem_api,
                    :erros_validacao,
                    :json_enviado,
                    :json_retorno,
                    :ip_origem,
                    :created_user
                )
            ");

            $banco->bindValue(':banco', $dados['banco'] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':id_lancamento', $dados['id_lancamento'] ?? null, PDO::PARAM_INT);
            $banco->bindValue(':id_conta', $dados['id_conta'] ?? null, PDO::PARAM_INT);
            $banco->bindValue(':tipo_operacao', $dados['tipo_operacao'] ?? 'DESCONHECIDO', PDO::PARAM_STR);
            $banco->bindValue(':ambiente', $dados['ambiente'] ?? 'sandbox', PDO::PARAM_STR);
            $banco->bindValue(':endpoint', $dados['endpoint'] ?? '', PDO::PARAM_STR);
            $banco->bindValue(':http_code', $dados['http_code'] ?? null, PDO::PARAM_INT);
            $banco->bindValue(':sucesso', isset($dados['sucesso']) && $dados['sucesso'] ? 1 : 0, PDO::PARAM_INT);
            $banco->bindValue(':cod_retorno_api', $dados['cod_retorno_api'] ?? null, PDO::PARAM_STR);
            $banco->bindValue(':mensagem_api', isset($dados['mensagem_api']) ? substr($dados['mensagem_api'], 0, 500) : null, PDO::PARAM_STR);
            $banco->bindValue(':erros_validacao', isset($dados['erros_validacao']) ? json_encode($dados['erros_validacao'], JSON_UNESCAPED_UNICODE) : null, PDO::PARAM_STR);
            $banco->bindValue(':json_enviado', isset($dados['json_enviado']) ? json_encode($dados['json_enviado'], JSON_UNESCAPED_UNICODE) : null, PDO::PARAM_STR);
            $banco->bindValue(':json_retorno', isset($dados['json_retorno']) ? json_encode($dados['json_retorno'], JSON_UNESCAPED_UNICODE) : null, PDO::PARAM_STR);
            $banco->bindValue(':ip_origem', 'null', PDO::PARAM_STR);
            $banco->bindValue(':created_user', $dados['user_id'] ?? 0, PDO::PARAM_INT);

            $banco->execute();
        } catch (Exception $e) {
            error_log('Erro ao inserir log API Bradesco: ' . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ];
        }
    }

    /**
     * Retorna todos os lançamentos do Banco Inter vinculados a uma Nota Fiscal,
     * junto ao ID e CODIGO_SOLICITACAO de FIN_API_INTER (necessário para buscar o PDF).
     *
     * O vínculo entre as tabelas é:
     *   FIN_LANCAMENTO.REMESSANUM = FIN_API_INTER.CODIGO_SOLICITACAO
     *
     * Apenas lançamentos com REMESSANUM preenchido (boleto já emitido no Inter)
     * são retornados.
     *
     * @param int $id_nota_fiscal ID da tabela EST_NOTA_FISCAL
     * @return array Lista de lançamentos com os campos:
     *               ID_LANCAMENTO, ID_API_INTER, CODIGO_SOLICITACAO, VENCIMENTO, VALOR
     */
    public function getFinApiInter(int $id): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT * FROM FIN_API_INTER WHERE ID_LANCAMENTO = :id_lancamento");
        $banco->bindValue(':id_lancamento', $id, PDO::PARAM_INT);
        $banco->execute();
        return $banco->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Retorna todos os lançamentos do Banco Inter vinculados a uma Nota Fiscal,
     * junto ao ID e CODIGO_SOLICITACAO de FIN_API_INTER (necessário para buscar o PDF).
     *
     * O vínculo entre as tabelas é:
     *   FIN_LANCAMENTO.REMESSANUM = FIN_API_INTER.CODIGO_SOLICITACAO
     *
     * Apenas lançamentos com REMESSANUM preenchido (boleto já emitido no Inter)
     * são retornados.
     *
     * @param int $id_nota_fiscal ID da tabela EST_NOTA_FISCAL
     * @return array Lista de lançamentos com os campos:
     *               ID_LANCAMENTO, ID_API_INTER, CODIGO_SOLICITACAO, VENCIMENTO, VALOR
     */
    public function getLancamentosPorNotaFiscal(int $id_nota_fiscal): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT
                FL.ID AS ID_LANCAMENTO,
                FL.REMESSANUM AS ID_API_INTER
            FROM FIN_LANCAMENTO FL 
            WHERE FL.REMESSANUM IS NOT NULL 
              AND FL.REMESSANUM  <> '' AND FL.NUMLCTO = :numlcto 
            ORDER BY FL.ID ASC
        ");

        $banco->bindValue(':numlcto', $id_nota_fiscal, PDO::PARAM_STR);
        $banco->execute();

        return $banco->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Busca o header da conta corrente
     * @param array $dados Dados da consulta
     * @return array
     */
    public function getDadosRecuperarColecaoCobranca(array $dados): array
    {
        $conta_bancaria = $dados['conta_bancaria'];

        $banco = new c_banco_pdo();
        $banco->prepare("SELECT CONCAT(FCO.CONTACORRENTE, FCO.CONTA_CORRENTE_DIGITO) AS CONTA_CORRENTE_HEADER FROM FIN_CONTA FCO WHERE FCO.CONTA = :conta_bancaria");
        $banco->bindValue(':conta_bancaria', $conta_bancaria, PDO::PARAM_STR);
        $banco->execute();
        $conta_corrente_header = $banco->fetch(PDO::FETCH_ASSOC) ?: [];

        // Deve retornar com casing camelCase para validar no json_builder
        if ($conta_corrente_header) {

            return [
                'contaCorrenteHeader' => $conta_corrente_header['CONTA_CORRENTE_HEADER'],
                'dataInicial' => $dados['data_inicial'],
                'dataFinal' => $dados['data_final'],
                'situacao' => $dados['situacao'],
                'filtrarDataPor' => $dados['filtrar_data_por'],
                'tipoCobranca' => $dados['tipo_cobranca']
            ];
        } else {
            return [];
        }
    }

    /**
     * Busca as credenciais da API Bradesco para uma conta
     * 
     * @param int $id_lancamento ID do lançamento
     * @return array Array com as credenciais
     */
    static function getCredenciais(string $ambiente, int $id_lancamento = 0, int $conta_id = 0): array
    {
        try {

            $sql = new c_banco_pdo();

            // se a conta for informada, busca as credenciais na tabela FIN_CONTA
            if ($conta_id > 0) {

                $sql->prepare("
                    SELECT
                        FC.INTER_API_CLIENT_ID_PRODUCTION,
                        FC.INTER_API_CLIENT_SECRET_PRODUCTION,
                        FC.INTER_API_CLIENT_ID_SANDBOX,
                        FC.INTER_API_CLIENT_SECRET_SANDBOX
                    FROM FIN_CONTA FC WHERE FC.CONTA = :conta_id
                ");

                $sql->bindValue(':conta_id', $conta_id, PDO::PARAM_INT);
            } else { // se a conta não for informada, busca as credenciais na tabela FIN_LANCAMENTO

                $sql->prepare("
                    SELECT
                        FC.INTER_API_CLIENT_ID_PRODUCTION,
                        FC.INTER_API_CLIENT_SECRET_PRODUCTION,
                        FC.INTER_API_CLIENT_ID_SANDBOX,
                        FC.INTER_API_CLIENT_SECRET_SANDBOX
                    FROM FIN_LANCAMENTO FL 
                    INNER JOIN FIN_CONTA FC ON FL.CONTA = FC.CONTA
                    WHERE FL.ID = :id_lancamento
                ");

                $sql->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);
            }

            $sql->execute();

            $resultado = $sql->fetch(PDO::FETCH_ASSOC) ?? [];

            // testa se as credenciais em produção existem e se estão vazias
            if ($ambiente == 'P' && (!empty($resultado['INTER_API_CLIENT_ID_PRODUCTION']) && !empty($resultado['INTER_API_CLIENT_SECRET_PRODUCTION']))) {
                $client_id     = $resultado['INTER_API_CLIENT_ID_PRODUCTION'];
                $client_secret = $resultado['INTER_API_CLIENT_SECRET_PRODUCTION'];

                return [
                    'sucesso' => true,
                    'ambiente' => $ambiente,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret
                ];
            }


            // testa se as credenciais em sandbox existem e se estão vazias
            if ($ambiente == 'S' && (!empty($resultado['INTER_API_CLIENT_ID_SANDBOX']) && !empty($resultado['INTER_API_CLIENT_SECRET_SANDBOX']))) {
                $client_id     = $resultado['INTER_API_CLIENT_ID_SANDBOX'];
                $client_secret = $resultado['INTER_API_CLIENT_SECRET_SANDBOX'];

                return [
                    'sucesso' => true,
                    'ambiente' => $ambiente,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret
                ];
            }

            // se não encontrou as credenciais, retorna erro
            return [
                'sucesso' => false,
                'mensagem' => 'Credenciais da API do Inter em ' . $ambiente . ' não encontradas'
            ];
        } catch (Exception $e) {

            return [
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ];
        }
    }

    /**
     * Busca o ambiente da conta bancária
     * @param array $dados_conta Dados da consulta
     * @return string
     */
    public function getAmbiente(array $dados_conta): string
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT AMBIENTE FROM FIN_CONTA WHERE CONTA = :conta_bancaria");
        $banco->bindValue(':conta_bancaria', $dados_conta['conta_bancaria'], PDO::PARAM_STR);
        $banco->execute();
        $ambiente = $banco->fetch(PDO::FETCH_ASSOC) ?: 'S';
        return $ambiente['AMBIENTE'];
    }
}
