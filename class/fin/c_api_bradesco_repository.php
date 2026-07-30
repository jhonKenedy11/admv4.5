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
include_once($dir . "/c_api_bradesco_barcode.php");


class c_api_bradesco_repository
{

    public $m_pdo = NULL;
    public array $situacao_api_map = [
        1 => 'A VENCER / VENCIDO',
        2 => 'COM PAGAMENTO VINCULADO',
        3 => 'COM PAGTO VINCULADO E INSTRUCAO AGENDADA',
        4 => 'COM INSTRUCAO DE PROTESTO',
        5 => 'COM INSTR. DE PROTESTO E PAGTO VINCULADO',
        6 => 'EM PODER DO CARTORIO',
        7 => 'COM INSTR. E PEDIDO SUSTACAO - SEM BAIXA',
        8 => 'COM INSTR. E PEDIDO SUSTACAO - COM BAIXA',
        9 => 'EM CARTORIO E PEDIDO SUSTACAO - S/ BAIXA',
        10 => 'EM CARTORIO E PEDIDO SUSTACAO - C/ BAIXA',
        11 => 'COM BAIXA SOLICITADA',
        12 => 'COM EXECUCAO SOLICITADA',
        13 => 'PAGO NO DIA',
        14 => 'EM CARTORIO COM PAGAMENTO VINCULADO',
        15 => 'INSTR. PED. SUST. - S/ BAIXA - PGTO VINC',
        16 => 'INSTR. PED. SUST. - C/ BAIXA - PGTO VINC',
        17 => 'CARTORIO PED. SUST. -S/ BAIXA - PGTO VINC',
        18 => 'CARTORIO PED. SUST. -C/ BAIXA - PGTO VINC',
        19 => 'SUSTADO SEM REMESSA AO CARTORIO',
        20 => 'SUSTADO RETIRADO DE CARTORIO',
        21 => 'SUSTADO JUDICIALMENTE',
        22 => 'PENDENTE NO DISTRIBUIDOR',
        23 => 'TITULO COM IRREGULARIDADE',
        24 => 'AGUARDANDO APONTAMENTO DE IRREGULARIDADE',
        25 => 'AGUARDANDO SOLICIT. DE SUSTACAO C/ BAIXA',
        26 => 'AGUARDANDO SOLICIT. DE SUSTACAO S/BAIXA',
        27 => 'SOLIC. SUSTACAO C/ENVIO CARTOR. C/BAIXA',
        28 => 'SOLIC. SUSTACAO C/ENVIO CARTOR. S/BAIXA',
        29 => 'EM CARTORIO COM EDITAL',
        30 => 'COM PAGAMENTO RETIDO',
        31 => 'COM INSTR NEGATIVACAO',
        32 => 'EM PROC NEGATIVACAO',
        33 => 'NEGATIVADO',
        34 => 'EXCL NEG S/BAIXA',
        35 => 'EXCL NEG C/BAIXA',
        51 => 'POR ACERTO',
        52 => 'BAIXA POR REGISTRO DUPLICADO',
        53 => 'POR DECURSO DE PRAZO',
        54 => 'POR MEDIDA JUDICIAL',
        55 => 'POR REMESSA (CEB)',
        56 => 'COBRADO - POR RASTREAMENTO',
        57 => 'CONFORME SEU PEDIDO',
        58 => 'PROTESTADO',
        59 => 'DEVOLVIDO',
        60 => 'ENTREGUE FRANCO DE PAGAMENTO',
        61 => 'PAGO',
        62 => 'PAGO EM CARTORIO',
        63 => 'SUSTADO RETIRADO DE CARTORIO',
        64 => 'SUSTADO SEM REMESSA A CARTORIO',
        65 => 'TRANSFERIDO PARA DESCONTO',
        66 => 'CREDITO EXDD',
        67 => 'CREDITO EXDD - PAGO EM CARTORIO',
        68 => 'COBRADO - POR BAIXA MANUAL',
        69 => 'COBRADO - POR BAIXA MANUAL - PAGO EM CARTORIO',
        70 => 'TRANSFERENCIA RECEBIVEIS',
        71 => 'DEVOLUCAO TRANSF RECEBIVEIS',
        72 => 'TRANSF. FUNDOS RECEB. / COBRANCA',
        98 => 'POR REGISTRO DUPLICADO',
        99 => 'COM REATIVACAO SOLICITADA',
    ];

    /**
     * Obtém os dados necessários para registro de boleto na API Bradesco
     * 
     * @param int $id_lancamento ID do lançamento financeiro
     * @return array Dados formatados para registro de boleto
     */
    function getDadosRegistraBoleto(int $id_lancamento): array
    {
        // NU_TITULO esta como NULL para nao ser informado pelo cliente pois o banco ira gerar o numero de negociação automaticamente
        // TP_VENCIMENTO - A documentação informa como 0
        /* 
            CAMPO DESATIVADOS MANUALMENTE - (Sem necessidade no momento do desenvolvimento)

            NULL AS PERCENTUAL_DESCONTO2,
            NULL AS VL_DESCONTO2,
            NULL AS DATA_LIMITE_DESCONTO2,
            NULL AS PERCENTUAL_DESCONTO3,   
            NULL AS VL_DESCONTO3,
            NULL AS DATA_LIMITE_DESCONTO3,

            NULL AS PERCENTUAL_BONIFICACAO,
            NULL AS VL_BONIFICACAO,
            NULL AS DT_LIMITE_BONIFICACAO,

            NULL AS VL_ABATIMENTO,
            NULL AS VL_IOF - #####   CAMPO NAO UTILIZADO PARA BOLETOS

            NULL AS COMPLEMENTO_CEP_PAGADOR,

            NULL AS NOME_SACADOR_AVALISTA,
            NULL AS LOGRADOURO_SACADOR_AVALISTA,
            NULL AS NU_LOGRADOURO_SACADOR_AVALISTA,
            NULL AS COMPLEMENTO_LOGADOURO_SACADOR_AVALISTA,
            NULL AS CEP_SACADOR_AVALISTA,
            NULL AS COMPLEMENTO_CEP_SACADOR_AVALISTA,
            NULL AS BAIRRO_SACADOR_AVALISTA,
            NULL AS MUNICIPIO_SACADOR_AVALISTA,
            NULL AS UF_SACADOR_AVALISTA,
            NULL AS CD_IND_CPF_CNPJ_SACADOR_AVALISTA,
            NULL AS NU_CPFCNPJ_SACADOR_AVALISTA,
            NULL AS ENDERECO_SACADOR_AVALISTA,
        */





        /*   AJUSTES FALTANDO


        Varificar idProduto(ID_PRODUTO) - Carteira de Cobrança - Valor 9

        Incluir uma tabela ou no codigo o FC.ESPECIE_TITULO AS CD_ESPECIE_TITULO,
        
        Incluir na tabela FIN_LANCAMENTO o campo TP_PROTESTO_AUTOMATICO_NEGATIVACAO 

        Incluir na tabela FIN_LANCAMENTO o campo PRAZO_PROTESTO_AUTOMATICO_NEGATIVACAO
        
        Incluir na tabela FIN_LANCAMENTO o campo CD_PAGAMENTO_PARCIAL - remove

        Incluir na tabela FIN_LANCAMENTO o campo QTDE_PAGAMENTO_PARCIAL - remove

        Incluir na tabela FIN_LANCAMENTO o campo TIPO_PRAZO_DECURSO_TRES - remove
        Explicacao: quantos dias depois do vencimento o banco ainda deve tentar processar (cobrar, debitar, protestar ou fazer retorno) antes de considerar o título vencido/baixado.
        
        Validar o calculo de juros, o percentual na tabela FIN_CONTA com o valor cobrado no FIN_LANCAMENTO
        
        Incluir na tabela FIN_LANCAMENTO o campo QTDE_DIAS_JUROS - Dias a partir da data de vencimento (dtVencimentoTitulo) para início da cobrança de juros.
        
        Validar o calculo de multa, o percentual na tabela FIN_CONTA com o valor cobrado no FIN_LANCAMENTO
        
        Incluir na tabela FIN_LANCAMENTO o campo QTDE_DIAS_MULTA - Dias a partir da data de vencimento (dtVencimentoTitulo) para início da cobrança de multa.
        
        Incluir na tabela FIN_CONTA o campo PERCENTUAL_DESCONTO1 - Percentual do Primeiro Desconto.
        
        Validar o calculo de desconto, o percentual na tabela FIN_CONTA com o valor cobrado no FIN_LANCAMENTO

        Incluir na tabela FIN_LANCAMENTO o campo DATA_LIMITE_DESCONTO1 - Data Limite para Primeiro Desconto.
        
        Incluir na tabela FIN_LANCAMENTO o campo CONTA_DEBITO_AUTOMATICO - Conta Débito Automático.
        
        Incluir na tabela FIN_LANCAMENTO o campo BANCO_DEBITO_AUTOMATICO - Banco Débito Automático.
        
        Incluir na tabela FIN_LANCAMENTO o campo AGENCIA_DEBITO_AUTOMATICO - Agência Débito Automático.
        
        Verificar tabela de RAZAO_DEBITO_AUTOMATICO para incluir na tabela FIN_LANCAMENTO


        */

        /* VERIFICAR FUNCAO DENTRO DO BANCO
        
        DELIMITER $$

        CREATE FUNCTION limpar_texto(texto TEXT)
        RETURNS TEXT
        DETERMINISTIC
        BEGIN

            SET texto = REPLACE(texto,'Á','A');
            SET texto = REPLACE(texto,'À','A');
            SET texto = REPLACE(texto,'Ã','A');
            SET texto = REPLACE(texto,'Â','A');
            SET texto = REPLACE(texto,'Ä','A');

            SET texto = REPLACE(texto,'á','a');
            SET texto = REPLACE(texto,'à','a');
            SET texto = REPLACE(texto,'ã','a');
            SET texto = REPLACE(texto,'â','a');
            SET texto = REPLACE(texto,'ä','a');

            SET texto = REPLACE(texto,'Ç','C');
            SET texto = REPLACE(texto,'ç','c');

            SET texto = REGEXP_REPLACE(texto, '[^A-Za-z0-9 ]', '');
            SET texto = REGEXP_REPLACE(texto, '[[:space:]]+', ' ');

            RETURN TRIM(texto);

        END$$

        DELIMITER ;

        */



        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT 
            NULL AS DEBITO_AUTOMATICO,
            LEFT(CNPJ, 8) AS NU_CPFCNPJ,
            CAST(SUBSTRING(LPAD(CNPJ, 14, '0'), 9, 4) AS UNSIGNED) AS FILIAL_CPF_CNPJ,
            CAST(RIGHT(LPAD(CNPJ, 14, '0'), 2) AS UNSIGNED) AS CTRL_CPFCNPJ,
            FCO.CARTEIRA AS ID_PRODUTO,
            CONCAT(
                LPAD(SUBSTRING(FCO.AGENCIA, 1, 4), 4, '0'),
                '0000000',
                LPAD(SUBSTRING(FCO.CONTACORRENTE, 1, 7), 7, '0')
            ) AS NU_NEGOCIACAO,
            NULL AS NU_TITULO,
            LEFT(FL.NUMLCTO, 25) AS NU_CLIENTE,
            DATE_FORMAT(FL.EMISSAO, '%d.%m.%Y') AS DT_EMISSAO_TITULO,
            DATE_FORMAT(FL.VENCIMENTO, '%d.%m.%Y') AS DT_VENCIMENTO_TITULO,
            FL.TOTAL AS VL_NOMINAL_TITULO,
            FL.TIPODOCTO AS CD_ESPECIE_TITULO,
            NULL AS TP_PROTESTO_AUTOMATICO_NEGATIVACAO,
            FCO.PROTESTO AS PRAZO_PROTESTO_AUTOMATICO_NEGATIVACAO,
            FL.ID AS CONTROLE_PARTICIPANTE,
            NULL AS CD_PAGAMENTO_PARCIAL,
            NULL AS QTDE_PAGAMENTO_PARCIAL,
            NULL AS TIPO_PRAZO_DECURSO_TRES,
            FCO.JUROS  AS PERCENTUAL_JUROS,
            NULL AS VL_JUROS,
            NULL AS QTDE_DIAS_JUROS,
            FCO.MULTA AS PERCENTUAL_MULTA,
            NULL AS VL_MULTA,
            NULL AS QTDE_DIAS_MULTA,
            NULL AS PERCENTUAL_DESCONTO1,
            NULL AS VL_DESCONTO1,
            NULL AS DATA_LIMITE_DESCONTO1,
            NULL AS PERCENTUAL_DESCONTO2,
            NULL AS VL_DESCONTO2,
            NULL AS DATA_LIMITE_DESCONTO2,
            NULL AS PERCENTUAL_DESCONTO3,   
            NULL AS VL_DESCONTO3,
            NULL AS DATA_LIMITE_DESCONTO3,
            NULL AS PERCENTUAL_BONIFICACAO,
            NULL AS VL_BONIFICACAO,
            NULL AS DT_LIMITE_BONIFICACAO,
            NULL AS VL_ABATIMENTO,
            NULL AS VL_IOF,
            LEFT(limpar_texto(FC.NOME), 70) AS NOME_PAGADOR, 
            LEFT(limpar_texto(CONCAT(FC.TIPOEND, ' ', FC.ENDERECO)), 100) AS LOGRADOURO_PAGADOR,
            LEFT(FC.NUMERO, 10) AS NU_LOGRADOURO_PAGADOR,
            LEFT(FC.COMPLEMENTO, 30) AS COMPLEMENTO_LOGADOURO_PAGADOR,
            NULL AS TP_VENCIMENTO,
            LEFT(FC.CEP, 5) AS CEP_PAGADOR,
            NULL AS COMPLEMENTO_CEP_PAGADOR,
            LEFT(limpar_texto(FC.BAIRRO), 50) AS BAIRRO_PAGADOR,
            LEFT(limpar_texto(FC.CIDADE), 50) AS MUNICIPIO_PAGADOR,
            LEFT(FC.UF, 2) AS UF_PAGADOR,
            IF(FC.PESSOA = 'J', '2', '1') AS CD_IND_CPF_CNPJ_PAGADOR,
            LEFT(FC.CNPJCPF, 14) AS NU_CPFCNPJ_PAGADOR,
            LEFT(COALESCE(FC.EMAIL, FC.EMAILNFE), 100) AS END_ELETRONICO_PAGADOR,
            NULL AS BANCO_DEBITO_AUTOMATICO,
            NULL AS AGENCIA_DEBITO_AUTOMATICO,
            NULL AS CONTA_DEBITO_AUTOMATICO,
            NULL AS RAZAO_DEBITO_AUTOMATICO,
            NULL AS NOME_SACADOR_AVALISTA,
            NULL AS LOGRADOURO_SACADOR_AVALISTA,
            NULL AS NU_LOGRADOURO_SACADOR_AVALISTA,
            NULL AS COMPLEMENTO_LOGADOURO_SACADOR_AVALISTA,
            NULL AS CEP_SACADOR_AVALISTA,
            NULL AS COMPLEMENTO_CEP_SACADOR_AVALISTA,
            NULL AS BAIRRO_SACADOR_AVALISTA,
            NULL AS MUNICIPIO_SACADOR_AVALISTA,
            NULL AS UF_SACADOR_AVALISTA,
            1 AS CD_IND_CPFCNPJ_SACADOR_AVALISTA,
            AE.CNPJ AS NU_CPFCNPJ_SACADOR_AVALISTA,
            NULL AS ENDERECO_SACADOR_AVALISTA,
            LEFT(FCO.MSGBLOQUETO, 80) AS MENSAGEM,
            FCO.CONTA AS CONTA_BANCARIA
            FROM FIN_LANCAMENTO FL 
            LEFT JOIN FIN_CLIENTE FC ON FL.PESSOA = FC.CLIENTE
            LEFT JOIN AMB_EMPRESA AE ON AE.CENTROCUSTO = FL.CENTROCUSTO
            LEFT JOIN FIN_CONTA FCO ON FCO.CONTA = FL.CONTA
            WHERE FL.ID = :id_lancamento");

        $banco->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);

        $banco->execute();

        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        // Ajusta o código da espécie do título para o código da espécie do título do Bradesco
        if ($dados['CD_ESPECIE_TITULO'] == 'B') {
            $dados['CD_ESPECIE_TITULO'] = 2; // Duplicata de Venda Mercantil
        } else {
            $dados['CD_ESPECIE_TITULO'] = 99; // Outros
        }

        return $dados;
    }

    /**
     * Obtém os dados necessários para baixa de título na API Bradesco
     * 
     * @param int $id_lancamento ID do lançamento financeiro
     * @param int $id_tabela_api ID do título na API Bradesco
     * @return array Dados formatados para baixa de título
     */
    function getDadosBaixaTitulo(int $id_lancamento, int $id_tabela_api): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT
                LPAD(LEFT(LPAD(AE.CNPJ, 14, '0'), 8), 9, '0') AS CPF_CNPJ,
                CAST(SUBSTRING(LPAD(AE.CNPJ, 14, '0'), 9, 4) AS UNSIGNED) AS FILIAL,
                CAST(RIGHT(LPAD(AE.CNPJ, 14, '0'), 2) AS UNSIGNED) AS CONTROLE,
                FAB.ID_PRODUTO AS PRODUTO,
                CONCAT(
                    SUBSTRING(FAB.NEGOCIACAO, 1, 4),
                    SUBSTRING(FAB.NEGOCIACAO, 12, 7)
                ) AS NEGOCIACAO,
                FAB.NU_TITULO_GERADO AS NOSSO_NUMERO,
                0 AS SEQUENCIA,
                57 AS CODIGO_BAIXA,
                FAB.ID_LANCAMENTO AS ID_LANCAMENTO,
                FCO.CONTA AS CONTA_BANCARIA
            FROM FIN_API_BRADESCO FAB 
            LEFT JOIN FIN_LANCAMENTO FL ON FL.ID = FAB.ID_LANCAMENTO
            LEFT JOIN AMB_EMPRESA AE ON AE.CENTROCUSTO = FL.CENTROCUSTO
            LEFT JOIN FIN_CONTA FCO ON FCO.CONTA = FL.CONTA
            WHERE FL.ID = :id_lancamento AND FAB.ID = :id_tabela_api
        ");

        $banco->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);
        $banco->bindValue(':id_tabela_api', $id_tabela_api, PDO::PARAM_INT);
        $banco->execute();
        $resultado = $banco->fetch(PDO::FETCH_ASSOC) ?? [];
        return $resultado;
    }

    /**
     * Obtém os dados necessários para baixa de título na API Bradesco
     * 
     * @param int $centro_custo Centro de custo
     * @param int $conta_bancaria Conta bancária
     * @return array Dados formatados para baixa de título
     */
    function getDadosBaixaTituloConsolidacao($centro_custo, $conta_bancaria, $nosso_numero)
    {

        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT
                LEFT(AE.CNPJ, 8) AS CPF_CNPJ,
                CAST(SUBSTRING(LPAD(AE.CNPJ, 14, '0'), 9, 4) AS UNSIGNED) AS FILIAL,
                CAST(RIGHT(LPAD(AE.CNPJ, 14, '0'), 2) AS UNSIGNED) AS CONTROLE,
                FC.CARTEIRA AS PRODUTO,
                CONCAT(
                    LPAD(SUBSTRING(FC.AGENCIA, 1, 4), 4, '0'),
                    LPAD(SUBSTRING(FC.CONTACORRENTE, 1, 7), 7, '0')
                ) AS NEGOCIACAO,
                FC.CONTA AS CONTA_BANCARIA
            FROM FIN_CONTA FC, AMB_EMPRESA AE
            WHERE FC.CONTA = :conta_bancaria AND AE.CENTROCUSTO = :centro_custo
        ");

        $banco->bindValue(':centro_custo', $centro_custo, PDO::PARAM_INT);
        $banco->bindValue(':conta_bancaria', $conta_bancaria, PDO::PARAM_INT);
        $banco->execute();

        $resultado = $banco->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return false;
        }

        $resultado['NOSSO_NUMERO'] = $nosso_numero;
        // Parametros fixos para baixa de título consolidado
        $resultado['SEQUENCIA'] = 0;
        $resultado['CODIGO_BAIXA'] = 57;

        return $resultado;
    }


    /**
     * Obtém os dados necessários para consulta de título unitário na API Bradesco
     * 
     * @param int $id_tabela_api ID da tabela API
     * @return array Dados formatados para consulta de título unitário
     */
    function getConsultaTituloUnitario($id_tabela_api)
    {
        $banco = new c_banco_pdo();

        $banco->prepare("
            SELECT  
                FAB.ID AS ID_TABELA_API,
                CONCAT(
                    LPAD(SUBSTRING(FC.AGENCIA, 1, 4), 4, '0'),
                    LPAD(SUBSTRING(FC.CONTACORRENTE, 1, 7), 7, '0')
                ) AS NEGOCIACAO,
                FAB.NU_TITULO_GERADO AS NOSSO_NUMERO,
                FAB.ID_PRODUTO AS PRODUTO,
                LEFT(AE.CNPJ, 8) AS CPF_CNPJ,
                CAST(SUBSTRING(LPAD(AE.CNPJ, 14, '0'), 9, 4) AS UNSIGNED) AS FILIAL,
                CAST(RIGHT(LPAD(AE.CNPJ, 14, '0'), 2) AS UNSIGNED) AS CONTROLE,
                FL.CONTA AS CONTA_BANCARIA 
            FROM FIN_API_BRADESCO FAB 
            LEFT JOIN FIN_LANCAMENTO FL ON FL.ID = FAB.ID_LANCAMENTO
            LEFT JOIN AMB_EMPRESA AE ON AE.CENTROCUSTO = FL.CENTROCUSTO
            LEFT JOIN FIN_CONTA FC ON FC.CONTA = FL.CONTA
            WHERE FAB.ID = :id_tabela_api
        ");

        $banco->bindValue(':id_tabela_api', $id_tabela_api, PDO::PARAM_STR);
        $banco->execute();
        $resultado = $banco->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return [];
        }

        return $resultado;
    }

    /**
     * Obtém os dados necessários para consulta de título pendente na API Bradesco
     * 
     * @param array $dados Dados para consulta de título pendente
     * @return array Dados formatados para consulta de título pendente
     */
    function getDadosConsultaTituloPendente(array $dados)
    {

        $conta               = $dados['conta_bancaria'];
        $banco               = $dados['banco'];
        $centro_custo        = $dados['centro_custo'];
        $data_registro_de    = $dados['data_registro_de'] == "" ? 0 : $dados['data_registro_de'];
        $data_registro_ate   = $dados['data_registro_ate'] == "" ? 0 : $dados['data_registro_ate'];
        $data_vencimento_de  = $dados['data_vencimento_de'] == "" ? 0 : $dados['data_vencimento_de'];
        $data_vencimento_ate = $dados['data_vencimento_ate'] == "" ? 0 : $dados['data_vencimento_ate'];

        // Se o CPF/CNPJ não foi informado, retorna false
        if (($dados['cpf_cnpj'] !== '' && $dados['cpf_cnpj'] !== null)) {
            // Converte o CPF/CNPJ para o formato do Bradesco
            $cpf_cnpj = $this->parseCpfCnpj($dados['cpf_cnpj']);

            $cpf_cnpj_pagador = $cpf_cnpj['cpfcnpj'];
            $filial_pagador   = $cpf_cnpj['filial'];
            $controle_pagador = $cpf_cnpj['controle'];
        }

        $pdo = new c_banco_pdo();
        $pdo->prepare("
            SELECT
                LEFT(AE.CNPJ, 8) AS CPF_CNPJ,
                CAST(SUBSTRING(LPAD(AE.CNPJ, 14, '0'), 9, 4) AS UNSIGNED) AS FILIAL,
                CAST(RIGHT(LPAD(AE.CNPJ, 14, '0'), 2) AS UNSIGNED) AS CONTROLE,
                FC.CARTEIRA AS PRODUTO,
                CONCAT(
                    LPAD(SUBSTRING(FC.AGENCIA, 1, 4), 4, '0'),
                    LPAD(SUBSTRING(FC.CONTACORRENTE, 1, 7), 7, '0')
                ) AS NEGOCIACAO,
                NULL AS NOSSO_NUMERO,
                NULL AS VALOR_TITULO_DE,
                NULL AS FAIXA_VENCIMENTO,
                NULL AS PAGINA_ANTERIOR
            FROM FIN_CONTA FC, AMB_EMPRESA AE 
            WHERE FC.CONTA = :conta AND FC.BANCO = :banco AND AE.CENTROCUSTO = :centro_custo
        ");

        $pdo->bindValue(':conta', $conta, PDO::PARAM_INT);
        $pdo->bindValue(':banco', $banco, PDO::PARAM_INT);
        $pdo->bindValue(':centro_custo', $centro_custo, PDO::PARAM_STR);
        $pdo->execute();
        $resultado = $pdo->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return false;
        }

        $resultado['CPF_CNPJ_PAGADOR']    = $cpf_cnpj_pagador;
        $resultado['FILIAL_PAGADOR']      = $filial_pagador;
        $resultado['CONTROLE_PAGADOR']    = $controle_pagador;
        $resultado['DATA_REGISTRO_DE']    = $data_registro_de;
        $resultado['DATA_REGISTRO_ATE']   = $data_registro_ate;
        $resultado['DATA_VENCIMENTO_DE']  = $data_vencimento_de;
        $resultado['DATA_VENCIMENTO_ATE'] = $data_vencimento_ate;

        return $resultado;
    }

    /**
     * Monta dados base para consulta de títulos baixados na API do Bradesco.
     *
     * A consulta usa os dados do beneficiário (empresa) e conta (FIN_CONTA) para preencher:
     * - cpfCnpj/filial/controle
     * - produto
     * - negociacao
     * e completa com filtros (vencimento, valor e        if (empty($dados['id_conta']) || empty($dados['centro_custo'])) {
            return [];
        }código de baixa).
     *
     * @param array $dados Parâmetros recebidos da tela
     * @return array|null
     */
    function getDadosConsultaTitulosBaixados($dados)
    {

        $conta               = $dados['conta_bancaria'];
        $centro_custo        = $dados['centro_custo'];
        $banco               = $dados['banco'];
        $valor_titulo_inicio = $dados['valor_titulo_inicio'];
        $codigo_baixa        = $dados['codigo_baixa'];
        $pagina_anterior     = $dados['pagina_anterior'];
        $data_vencimento_de  = $dados['data_vencimento_de']  == "" ? 0 : $dados['data_vencimento_de'];
        $data_vencimento_ate = $dados['data_vencimento_ate'] == "" ? 0 : $dados['data_vencimento_ate'];

        $pdo = new c_banco_pdo();
        $pdo->prepare("
            SELECT
                LPAD(LEFT(LPAD(AE.CNPJ, 14, '0'), 8), 9, '0') AS CPF_CNPJ,
                CAST(SUBSTRING(LPAD(AE.CNPJ, 14, '0'), 9, 4) AS UNSIGNED) AS FILIAL,
                CAST(RIGHT(LPAD(AE.CNPJ, 14, '0'), 2) AS UNSIGNED) AS CONTROLE,
                FC.CARTEIRA AS PRODUTO,
                CONCAT(
                    LPAD(SUBSTRING(FC.AGENCIA, 1, 4), 4, '0'),
                    LPAD(SUBSTRING(FC.CONTACORRENTE, 1, 7), 7, '0')
                ) AS NEGOCIACAO 
            FROM FIN_CONTA FC, AMB_EMPRESA AE
            WHERE FC.BANCO = :banco AND FC.CONTA = :conta AND AE.CENTROCUSTO = :centro_custo
        ");

        $pdo->bindValue(':banco', $banco, PDO::PARAM_INT);
        $pdo->bindValue(':conta', $conta, PDO::PARAM_INT);
        $pdo->bindValue(':centro_custo', $centro_custo, PDO::PARAM_STR);
        $pdo->execute();

        $resultado = $pdo->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return false;
        }

        $resultado['DATA_VENCIMENTO_DE']  = $data_vencimento_de;
        $resultado['DATA_VENCIMENTO_ATE'] = $data_vencimento_ate;
        $resultado['VALOR_TITULO_INICIO'] = $valor_titulo_inicio;
        $resultado['CODIGO_BAIXA']        = $codigo_baixa;
        $resultado['PAGINA_ANTERIOR']     = $pagina_anterior;

        return $resultado;
    }
    /**
     * Monta dados base para consulta de títulos liquidados na API do Bradesco.
     *
     * A consulta usa os dados do beneficiário (empresa) e conta (FIN_CONTA) para preencher:
     * - cpfCnpj/filial/controle
     * - produto
     * - negociacao
     * e completa com filtros (vencimento, valor e código de baixa).
     *
     * @param array $dados Parâmetros recebidos da tela
     * @return array|null
     */
    function getDadosConsultaTitulosLiquidados($dados)
    {

        $conta              = $dados['conta_bancaria'];
        $banco              = $dados['banco'];
        $centro_custo       = $dados['centro_custo'];
        $tipo_registro      = $dados['tipo_registro'];
        $pagina_anterior    = $dados['pagina_anterior'];
        $data_movimento_de  = $dados['data_movimento_de']  == "" ? 0 : $dados['data_movimento_de'];
        $data_movimento_ate = $dados['data_movimento_ate'] == "" ? 0 : $dados['data_movimento_ate'];
        $data_pagamento_de  = $dados['data_pagamento_de']  == "" ? 0 : $dados['data_pagamento_de'];
        $data_pagamento_ate = $dados['data_pagamento_ate'] == "" ? 0 : $dados['data_pagamento_ate'];
        // Filtro nao incluso no frontend
        $valor_titulo_de = $dados['valor_titulo_de'];
        // Filtro nao incluso no frontend
        $valor_titulo_ate = $dados['valor_titulo_ate'];


        // Busca infos da conta e empresa
        $consulta = new c_banco_pdo();
        $consulta->prepare("
            SELECT
                LPAD(LEFT(LPAD(AE.CNPJ, 14, '0'), 8), 9, '0') AS CPF_CNPJ,
                CAST(SUBSTRING(LPAD(AE.CNPJ, 14, '0'), 9, 4) AS UNSIGNED) AS FILIAL,
                CAST(RIGHT(LPAD(AE.CNPJ, 14, '0'), 2) AS UNSIGNED) AS CONTROLE,
                FC.CARTEIRA AS PRODUTO,
                CONCAT(
                    LPAD(SUBSTRING(FC.AGENCIA, 1, 4), 4, '0'),
                    LPAD(SUBSTRING(FC.CONTACORRENTE, 1, 7), 7, '0')
                ) AS NEGOCIACAO
            FROM FIN_CONTA FC, AMB_EMPRESA AE
            WHERE FC.CONTA = :conta AND FC.BANCO = :banco AND AE.CENTROCUSTO = :centro_custo
        ");

        $consulta->bindValue(':conta', $conta, PDO::PARAM_INT);
        $consulta->bindValue(':centro_custo', $centro_custo, PDO::PARAM_STR);
        $consulta->bindValue(':banco', $banco, PDO::PARAM_INT);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        $resultado['DATA_MOVIMENTO_DE']  = $data_movimento_de;
        $resultado['DATA_MOVIMENTO_ATE'] = $data_movimento_ate;
        $resultado['DATA_PAGAMENTO_DE']  = $data_pagamento_de;
        $resultado['DATA_PAGAMENTO_ATE'] = $data_pagamento_ate;
        $resultado['TIPO_REGISTRO']      = (is_numeric($tipo_registro) ? $tipo_registro : 0);
        $resultado['PAGINA_ANTERIOR']    = (is_numeric($pagina_anterior) ? $pagina_anterior : 0);

        $resultado['VALOR_TITULO_DE']  = (is_numeric($valor_titulo_de) ? $valor_titulo_de : 0);
        $resultado['VALOR_TITULO_ATE'] = (is_numeric($valor_titulo_ate) ? $valor_titulo_ate : 0);

        return $resultado;
    }

    /**
     * Normaliza valor extraído da API para o tipo da coluna em FIN_API_BRADESCO.
     *
     * A API envia campos numéricos (valores, códigos, CEP, etc.) como int/float no JSON,
     * sem formatação textual. Strings vazias aparecem apenas em campos opcionais de texto.
     *
     * @param string $coluna Nome da coluna no banco
     * @param mixed $valor Valor bruto retornado pela API
     * @return mixed|null
     */
    function normalizarValorColunaFinApiBradesco(string $coluna, $valor)
    {
        if ($valor === null) {
            return null;
        }

        static $colunasMonetarias = [
            'VL_IOF',
            'VL_TITULO',
            'VL_ABATIMENTO',
            'VL_MULTA',
            'VL_JUROS_AO_DIA',
            'VL_JUROS',
            'VL_DESCONTO_1_BONIFICACAO',
            'VL_DESCONTO_2',
            'VL_DESCONTO_3',
            'VL_TITULO_EMITIDO_BOLETO',
        ];

        static $colunasMoeda = ['CODIGO_MOEDA_TITULO', 'DESCRICACAO_MOEDA'];

        static $colunasChar1 = [
            'DEBITO_AUTO_10',
            'ACEITE_10',
            'EXIBE_LIN_DIG_10',
            'STATUS_ANTERIOR',
        ];

        static $colunasBigint = [
            'NEGOCIACAO',
            'NSEQ_CONTR_NEGOC',
            'NU_TITULO_GERADO',
            'CTA_CRED_10',
            'CPF_CNPJ_PAGADOR',
            'CNPJ_CPF_SACADOR_AVALISTA',
            'IDENT_TIT_DDA_10',
            'CONTA_DEB',
        ];

        static $colunasInteiro = [
            'ID_PRODUTO',
            'CPSSOA_JURID_CONTR',
            'CTPO_CONTR_NEGOC',
            'CPRODT_SERVC_OPER',
            'TP_08_REG_1',
            'AGENC_CRED_10',
            'CIP_10',
            'COD_STATUS_10',
            'RAZ_CREDT_10',
            'CEP_BENEFICIARIO',
            'CEP_COMPLEMENTO_BENEFICIARIO',
            'CEP_PAGADOR',
            'CEP_SACADOR_AVALISTA',
            'CEP_COMPLEMENTO_SACADOR_AVALISTA',
            'TP_08_REG_2',
            'CENSE_10',
            'AGEN_OPER_10',
            'BCO_DEPOS_10',
            'AGEN_DEPOS_10',
            'QUANTIDADE_CASAS',
            'DIAS_INSTRUCAO_PROTESTO_NEGATIVACAO',
            'QTDE_CASAS_DECIMAIS_MULTA',
            'CD_VALOR_MULTA',
            'DIAS_JUROS',
            'CD_JUROS',
            'DIAS_DISPENSA_JUROS',
            'CD_VALOR_JUROS',
            'QTDE_CASAS_DECIMAIS_DESCONTO_1_BONIFICACAO',
            'CD_VALOR_DESCONTO_1_BONIFICACAO',
            'TP_DESCONTO_1',
            'QTDE_CASAS_DECIMAIS_DESCONTO_2',
            'CD_VALOR_DESCONTO_2',
            'TP_DESCONTO_2',
            'QTDE_CASAS_DECIMAIS_DESCONTO_3',
            'CD_VALOR_DESCONTO_3',
            'TP_DESCONTO_3',
            'DIAS_DISPENSA_MULTA',
            'DESP_CART_10',
            'BCO_CENTR_10',
            'AGE_CENTR_10',
            'ACESS_ESC_10',
            'CODIGO_ORIGEM_PROTESTO',
            'TP_VENCIMENTO',
            'IND_INSTRUCAO_PROTESTO',
            'INDICADOR_DECURSO',
            'QUANTIDADE_DIAS_DECURSO',
            'CTPO_ABAT_10',
            'HORA_IMPRESSAO_10',
            'QTDE_PGTO_PARCIAL',
            'BANCO_DEB',
            'AGENCIA_DEB',
            'AGENCIA_DEB_DV',
            'RAZAO_CONTA_DEBITO',
        ];

        static $colunasDataBruta = [
            'DT_REGISTRO',
            'DT_EMISSAO',
            'DT_VENCIMENTO',
            'DT_INSTRUCAO_PROTESTO_NEGATIVACAO',
            'DATA_ENVIO_CARTORIO',
            'DATA_PEDIDO_SUSTACAO',
            'DATA_SUSTACAO',
            'DT_MULTA',
            'DT_JUROS',
            'DT_DESCONTO_1_BONIFICACAO',
            'DT_DESCONTO_2',
            'DT_DESCONTO_3',
            'DT_VENCIMENTO_BOLETO',
            'DT_LIMITE_PAGAMENTO_BOLETO',
            'DATA_IMPRESSAO_10',
        ];

        // Campos numéricos da API: tratar antes de regras de string vazia
        if (in_array($coluna, $colunasMonetarias, true)) {
            return $this->normalizarValorMonetarioBradesco($valor);
        }

        if (in_array($coluna, $colunasMoeda, true)) {
            return $this->normalizarMoedaBradesco($valor);
        }

        if ($coluna === 'QUANTIDADE_MOEDA') {
            $numero = $this->valorNumericoBradesco($valor);
            return $numero !== null ? round($numero, 5) : null;
        }

        if (in_array($coluna, $colunasBigint, true)) {
            $numero = $this->valorNumericoBradesco($valor);
            return $numero !== null ? (int) $numero : null;
        }

        if (in_array($coluna, $colunasInteiro, true)) {
            $numero = $this->valorNumericoBradesco($valor);
            return $numero !== null ? (int) $numero : null;
        }

        if ($coluna === 'CPF_CNPJ_BENEFICIARIO') {
            if (is_int($valor) || is_float($valor)) {
                return (string) (int) $valor;
            }
            if (is_string($valor) && $valor !== '') {
                return $valor;
            }
            return null;
        }

        if (in_array($coluna, $colunasDataBruta, true)) {
            if (is_int($valor) || is_float($valor)) {
                return (string) (int) $valor;
            }
            if (is_string($valor)) {
                $texto = trim($valor);
                return $texto === '' ? null : $texto;
            }
            return null;
        }

        if (is_string($valor)) {
            $valor = trim($valor);
            if ($valor === '') {
                return null;
            }
        }

        if (in_array($coluna, $colunasChar1, true)) {
            return strtoupper(substr((string) $valor, 0, 1));
        }

        if (in_array($coluna, ['UF_BENEFICIARIO', 'UF_PAGADOR', 'UF_SACADOR_AVALISTA'], true)) {
            return strtoupper(substr((string) $valor, 0, 2));
        }

        if (is_string($valor)) {
            return $valor;
        }

        if (is_scalar($valor)) {
            return $valor;
        }

        return null;
    }

    /**
     * Extrai número puro da API (int, float ou string somente com dígitos).
     *
     * @param mixed $valor
     * @return float|null
     */
    function valorNumericoBradesco($valor): ?float
    {
        if (is_int($valor) || is_float($valor)) {
            return (float) $valor;
        }

        if (is_string($valor) && $valor !== '' && ctype_digit($valor)) {
            return (float) $valor;
        }

        return null;
    }

    /**
     * Converte código de moeda da API para CHAR(2) do banco (BACEN: Real = 9).
     *
     * @param mixed $valor
     * @return string|null
     */
    function normalizarMoedaBradesco($valor): ?string
    {
        $numero = $this->valorNumericoBradesco($valor);
        if ($numero !== null) {
            return str_pad((string) (int) $numero, 2, '0', STR_PAD_LEFT);
        }

        if (!is_string($valor)) {
            return null;
        }

        $texto = trim($valor);
        if ($texto === '') {
            return null;
        }

        // API envia rótulo textual "R$" — converte para código numérico BACEN
        if ($texto === 'R$') {
            return '09';
        }

        return null;
    }

    /**
     * Converte valores monetários da API (centavos inteiros) para DECIMAL do banco.
     *
     * @param mixed $valor
     * @return float|null
     */
    function normalizarValorMonetarioBradesco($valor): ?float
    {
        $numero = $this->valorNumericoBradesco($valor);

        if ($numero === null) {
            return null;
        }

        return round($numero / 100, 2);
    }

    /**
     * Mapeia resposta JSON do registro de boleto para colunas de FIN_API_BRADESCO.
     *
     * @param array $responseArray
     * @return array
     */
    function mapearColunasRegistroBoleto($responseArray)
    {
        $mapa = [
            'ID_PRODUTO' => ['idProduto'],
            'NEGOCIACAO' => ['negociacao'],
            'CPSSOA_JURID_CONTR' => ['cpssoaJuridContr'],
            'CTPO_CONTR_NEGOC' => ['ctpoContrNegoc'],
            'NSEQ_CONTR_NEGOC' => ['nseqContrNegoc'],
            'CPRODT_SERVC_OPER' => ['cprodtServcOper'],
            'NU_TITULO_GERADO' => ['nuTituloGerado'],
            'TP_08_REG_1' => ['tp08Reg1'],
            'AGENC_CRED_10' => ['agencCred10'],
            'CTA_CRED_10' => ['ctaCred10'],
            'DIG_CRED_10' => ['digCred10'],
            'CIP_10' => ['cip10'],
            'COD_STATUS_10' => ['codStatus10'],
            'STATUS_10' => ['status10'],
            'NOME_BENEFICIARIO' => ['nomeBeneficiario'],
            'LOGRADOURO_BENEFICIARIO' => ['logradouroBeneficiario'],
            'NU_LOGRADOURO_BENEFICIARIO' => ['nuLogradouroBeneficiario'],
            'COMPLEMENTO_LOGRADOURO_BENEFICIARIO' => ['complementoLogradouroBeneficiario'],
            'BAIRRO_BENEFICIARIO' => ['bairroBeneficiario'],
            'CEP_BENEFICIARIO' => ['cepBeneficiario'],
            'CEP_COMPLEMENTO_BENEFICIARIO' => ['cepComplementoBeneficiario'],
            'MUNICIPIO_BENEFICIARIO' => ['municipioBeneficiario'],
            'UF_BENEFICIARIO' => ['ufBeneficiario'],
            'RAZ_CREDT_10' => ['razCredt10'],
            'CPF_CNPJ_BENEFICIARIO' => ['cpfcnpjBeneficiário', 'cpfcnpjBeneficiario'],
            'NOME_PAGADOR' => ['nomePagador'],
            'CPF_CNPJ_PAGADOR' => ['cpfcnpjPagador'],
            'ENDERECO_PAGADOR' => ['enderecoPagador'],
            'BAIRRO_PAGADOR' => ['bairroPagador'],
            'MUNICIPIO_PAGADOR' => ['municipioPagador'],
            'UF_PAGADOR' => ['ufPagador'],
            'CEP_PAGADOR' => ['cepPagador'],
            'CEP_COMPLEMENTO_PAGADOR' => ['cepComplementoPagador'],
            'CEBP_10' => ['cebp10'],
            'DEBITO_AUTO_10' => ['debitoAuto10'],
            'ACEITE_10' => ['aceite10'],
            'END_ELETRONICO_PAGADOR' => ['endEletronicoPagador'],
            'NOME_SACADOR_AVALISTA' => ['nomeSacadorAvalista'],
            'CNPJ_CPF_SACADOR_AVALISTA' => ['cnpjCpfSacadorAvalista'],
            'ENDERECO_SACADOR_AVALISTA' => ['enderecoSacadorAvalista'],
            'MUNICIPIO_SACADOR_AVALISTA' => ['municipioSacadorAvalista'],
            'UF_SACADOR_AVALISTA' => ['ufSacadorAvalista'],
            'CEP_SACADOR_AVALISTA' => ['cepSacadorAvalista'],
            'CEP_COMPLEMENTO_SACADOR_AVALISTA' => ['cepComplementoSacadorAvalista'],
            'TP_08_REG_2' => ['tp08Reg2'],
            'CENSE_10' => ['cense10'],
            'AGEN_OPER_10' => ['agenOper10'],
            'BCO_DEPOS_10' => ['bcoDepos10'],
            'AGEN_DEPOS_10' => ['agenDepos10'],
            'SEU_NUMERO_TITULO' => ['seuNumeroTitulo'],
            'DT_REGISTRO' => ['dtRegistro'],
            'ESPECIE_DOCUMENTO_TITULO' => ['especieDocumentoTitulo'],
            'DESC_ESPECIE' => ['descEspecie'],
            'VL_IOF' => ['vlIOF'],
            'DT_EMISSAO' => ['dtEmissao'],
            'CODIGO_MOEDA_TITULO' => ['codigoMoedaTitulo'],
            'QUANTIDADE_MOEDA' => ['quantidadeMoeda'],
            'QUANTIDADE_CASAS' => ['quantidadeCasas'],
            'DT_VENCIMENTO' => ['dtVencimento'],
            'DESCRICACAO_MOEDA' => ['descricacaoMoeda'],
            'VL_TITULO' => ['vlTitulo'],
            'VL_ABATIMENTO' => ['vlAbatimento'],
            'DT_INSTRUCAO_PROTESTO_NEGATIVACAO' => ['dtInstrucaoProtestoNegativação', 'dtInstrucaoProtestoNegativacao'],
            'DIAS_INSTRUCAO_PROTESTO_NEGATIVACAO' => ['diasInstrucaoProtestoNegativação', 'diasInstrucaoProtestoNegativacao'],
            'DATA_ENVIO_CARTORIO' => ['dataEnvioCartorio'],
            'NUMERO_CARTORIO' => ['numeroCartorio'],
            'NUMERO_PROTOCOLO_CARTORIO' => ['numeroProtocoloCartorio'],
            'DATA_PEDIDO_SUSTACAO' => ['dataPedidoSustacao'],
            'DATA_SUSTACAO' => ['dataSustacao'],
            'DT_MULTA' => ['dtMulta'],
            'VL_MULTA' => ['vlMulta'],
            'QTDE_CASAS_DECIMAIS_MULTA' => ['qtdeCasasDecimaisMulta'],
            'CD_VALOR_MULTA' => ['cdValorMulta'],
            'DESC_CD_MULTA' => ['descCdMulta'],
            'DT_JUROS' => ['dtJuros'],
            'VL_JUROS_AO_DIA' => ['vlJurosAoDia'],
            'DIAS_JUROS' => ['diasJuros'],
            'CD_JUROS' => ['cdJuros'],
            'VL_JUROS' => ['vlJuros'],
            'DIAS_DISPENSA_JUROS' => ['diasDispensaJuros'],
            'CD_VALOR_JUROS' => ['cdValorJuros'],
            'DT_DESCONTO_1_BONIFICACAO' => ['dtDesconto1Bonificacao'],
            'VL_DESCONTO_1_BONIFICACAO' => ['vlDesconto1Bonificacao'],
            'QTDE_CASAS_DECIMAIS_DESCONTO_1_BONIFICACAO' => ['qtdeCasasDecimaisDesconto1Bonificacao'],
            'CD_VALOR_DESCONTO_1_BONIFICACAO' => ['cdValorDesconto1Bonificacao'],
            'DESC_CD_DESCONTO_1_BONIFICACAO' => ['descCdDesconto1Bonificacao'],
            'TP_DESCONTO_1' => ['tpDesconto1'],
            'DT_DESCONTO_2' => ['dtDesconto2'],
            'VL_DESCONTO_2' => ['vlDesconto2'],
            'QTDE_CASAS_DECIMAIS_DESCONTO_2' => ['qtdeCasasDecimaisDesconto2'],
            'CD_VALOR_DESCONTO_2' => ['cdValorDesconto2'],
            'DESC_CD_DESCONTO_2' => ['descCdDesconto2'],
            'TP_DESCONTO_2' => ['tpDesconto2'],
            'DT_DESCONTO_3' => ['dtDesconto3'],
            'VL_DESCONTO_3' => ['vlDesconto3'],
            'QTDE_CASAS_DECIMAIS_DESCONTO_3' => ['qtdeCasasDecimaisDesconto3'],
            'CD_VALOR_DESCONTO_3' => ['cdValorDesconto3'],
            'DESC_CD_DESCONTO_3' => ['descCdDesconto3'],
            'TP_DESCONTO_3' => ['tpDesconto3'],
            'DIAS_DISPENSA_MULTA' => ['diasDispensaMulta'],
            'CD_BARRAS' => ['cdBarras'],
            'LINHA_DIGITAVEL' => ['linhaDigitavel'],
            'VL_TITULO_EMITIDO_BOLETO' => ['vlTituloEmitidoBoleto'],
            'DT_VENCIMENTO_BOLETO' => ['dtVencimentoBoleto'],
            'DT_LIMITE_PAGAMENTO_BOLETO' => ['dtLimitePagamentoBoleto'],
            'DESP_CART_10' => ['despCart10'],
            'BCO_CENTR_10' => ['bcoCentr10'],
            'AGE_CENTR_10' => ['ageCentr10'],
            'ACESS_ESC_10' => ['acessEsc10'],
            'TIPO_ENDOSSO' => ['tipoEndosso'],
            'CODIGO_ORIGEM_PROTESTO' => ['codigoOrigemProtesto'],
            'CODIGO_ORIGEM_TITULO' => ['codigoOrigemTitulo'],
            'TP_VENCIMENTO' => ['tpVencimento'],
            'IND_INSTRUCAO_PROTESTO' => ['indInstrucaoProtesto'],
            'INDICADOR_DECURSO' => ['indicadorDecurso'],
            'QUANTIDADE_DIAS_DECURSO' => ['quantidadeDiasDecurso'],
            'CTPO_ABAT_10' => ['ctpoAbat10'],
            'NU_CONTROLE_PARTICIPANTE' => ['nuControleParticipante'],
            'IND_TIT_PARCELD_10' => ['indTitParceld10'],
            'IND_PARCELA_PRIN_10' => ['indParcelaPrin10'],
            'IND_BOLETO_DDA_10' => ['indBoletoDda10'],
            'DATA_IMPRESSAO_10' => ['dataImpressao10'],
            'HORA_IMPRESSAO_10' => ['horaImpressao10'],
            'IDENT_TIT_DDA_10' => ['identTitDda10'],
            'EXIBE_LIN_DIG_10' => ['exibeLinDig10'],
            'PERM_PGTO_PARCIAL' => ['permPgtoParcial'],
            'QTDE_PGTO_PARCIAL' => ['qtdePgtoParcial'],
            'BANCO_DEB' => ['bancoDeb'],
            'AGENCIA_DEB' => ['agenciaDeb'],
            'AGENCIA_DEB_DV' => ['agenciaDebDv'],
            'CONTA_DEB' => ['contaDeb'],
            'RAZAO_CONTA_DEBITO' => ['razaoContaDebito'],
        ];

        $dados = [];
        foreach ($mapa as $coluna => $chaves) {
            $valor = $this->valorRespostaRegistroBoleto($responseArray, $chaves);
            if ($valor === null) {
                continue;
            }

            $valor = $this->normalizarValorColunaFinApiBradesco($coluna, $valor);
            if ($valor !== null) {
                $dados[$coluna] = $valor;
            }
        }

        return $dados;
    }

    /**
     * Extrai valor da resposta da API Bradesco sem conversão de formato.
     *
     * @param array $fonte
     * @param array $chaves Chaves em camelCase (inclui variantes com acento da documentação)
     * @return mixed|null
     */
    function valorRespostaRegistroBoleto($fonte, array $chaves)
    {
        // Verifica se o array fonte é válido
        if (!is_array($fonte)) {
            return null;
        }

        // Percorre as chaves
        foreach ($chaves as $chave) {
            if (array_key_exists($chave, $fonte)) {
                // Retorna o valor da chave
                return $fonte[$chave];
            }
        }

        return null;
    }

    /**
     * Insere dados de registro de boleto na tabela FIN_API_BRADESCO.
     *
     * @param array $dados Dados a serem inseridos
     * @return int ID do registro inserido
     */
    function insertRegistraBoleto($dados)
    {
        // Obtém o array de resposta da API
        $responseArray = $dados['response_array'] ?? $dados;

        // Mapeia as colunas da tabela FIN_API_BRADESCO que serão inseridas
        $colunasValores = $this->mapearColunasRegistroBoleto($responseArray);

        // Define o ID do lançamento financeiro
        $colunasValores['ID_LANCAMENTO'] = $dados['id_lancamento'] ?? $dados['idLancamento'] ?? null;

        // Define o JSON retornado completo
        $colunasValores['JSON_RETORNO_COMPLETO'] = $dados['json_retorno_completo']
            ?? json_encode($dados['response_array'] ?? [], JSON_UNESCAPED_UNICODE);

        // Define o ID do usuário que realizou o registro
        $colunasValores['CREATED_USER'] = $dados['created_user'] ?? $dados['createdUser'] ?? null;

        // Monta os campos e placeholders para a query de inserção
        $campos = array_keys($colunasValores);
        $placeholders = array_map(function ($campo) {
            return ':' . strtolower($campo);
        }, $campos);

        // Prepara a query de inserção
        $banco = new c_banco_pdo();
        $banco->prepare(
            'INSERT INTO FIN_API_BRADESCO (' . implode(', ', $campos) . ') VALUES (' . implode(', ', $placeholders) . ')'
        );

        // Define os valores dos campos
        foreach ($colunasValores as $campo => $valor) {
            $banco->bindValue(':' . strtolower($campo), $valor);
        }

        $banco->execute();

        return $banco->lastInsertId();
    }


    /**
     * Mapeia resposta da consulta de título unitário (spec CBTTIAGS) para FIN_API_BRADESCO.
     *
     * @param array $responseArray Corpo JSON retornado pela API
     * @return array
     */
    function mapearColunasConsultaTituloUnitario($responseArray)
    {
        $mapa = [
            //'AGENC_CRED_10' => ['agencCred'],
            //'CTA_CRED_10' => ['ctaCred'],
            //'DIG_CRED_10' => ['digCred'],
            //'RAZ_CREDT_10' => [['razCredt', 'razCred']],
            'CIP_10' => ['cip'],
            'COD_STATUS_10' => ['codStatus'],
            'STATUS_10' => ['status'],
            //'END_ELETRONICO_PAGADOR' => ['enderecoEma'],
            //'NOME_SACADOR_AVALISTA' => ['sacador.nome'],
            //'CNPJ_CPF_SACADOR_AVALISTA' => ['sacador.cnpj'],
            //'ENDERECO_SACADOR_AVALISTA' => ['sacador.endereco'],
            //'MUNICIPIO_SACADOR_AVALISTA' => ['sacador.cidade'],
            //'UF_SACADOR_AVALISTA' => ['sacador.uf'],
            //'CEP_SACADOR_AVALISTA' => ['sacador.cep'],
            //'CEP_COMPLEMENTO_SACADOR_AVALISTA' => ['sacador.cepc'],
            // 'AGEN_OPER_10' => ['agenOper'],
            // 'BCO_DEPOS_10' => ['bcoDepos'],
            // 'AGEN_DEPOS_10' => ['agenDepos'],
            //'SEU_NUMERO_TITULO' => ['snumero'],
            //'DT_REGISTRO' => ['dataReg'],
            //'ESPECIE_DOCUMENTO_TITULO' => ['especDocto'],
            //'VL_IOF' => ['valorIof'],
            //'DT_EMISSAO' => ['dataEmis'],
            //'DT_VENCIMENTO' => ['dataVencto'],
            //'VL_TITULO' => ['valMoeda'],
            //'VL_ABATIMENTO' => ['valAbat'],
            //'DT_INSTRUCAO_PROTESTO_NEGATIVACAO' => ['dataInstr'],
            //'DIAS_INSTRUCAO_PROTESTO_NEGATIVACAO' => ['diasProt'],
            //'DATA_ENVIO_CARTORIO' => ['dataCartor'],
            //'NUMERO_CARTORIO' => ['numCartor'],
            //'NUMERO_PROTOCOLO_CARTORIO' => ['numProtoc'],
            //'DATA_PEDIDO_SUSTACAO' => ['dataPedSus'],
            //'DATA_SUSTACAO' => ['dataSust'],
            //'DT_MULTA' => ['dataMulta'],
            'VL_MULTA' => ['valMulta'],
            'DESC_CD_MULTA' => ['descrMulta'],
            'DT_JUROS' => ['dataPerm'],
            'DIAS_JUROS' => ['diasJuros'],
            'VL_JUROS' => ['valPerm'],
            'DT_DESCONTO_1_BONIFICACAO' => ['dataDesc1'],
            'VL_DESCONTO_1_BONIFICACAO' => ['valDesc1'],
            'DT_DESCONTO_2' => ['dataDesc2'],
            'VL_DESCONTO_2' => ['valDesc2'],
            //'CD_VALOR_DESCONTO_2' => ['codValDe2'],
            'DESC_CD_DESCONTO_2' => ['descrDesc2'],
            'DT_DESCONTO_3' => ['dataDesc3'],
            'VL_DESCONTO_3' => ['valDesc3'],
            //'CD_VALOR_DESCONTO_3' => ['codValDe3'],
            //'DESC_CD_DESCONTO_3' => ['descrDesc3'],
            //'DIAS_DISPENSA_MULTA' => ['diasMulta'],
            //'CD_BARRAS' => ['codBarras'],
            //'LINHA_DIGITAVEL' => ['linhaDig'],
            //'DT_VENCIMENTO_BOLETO' => ['dataVenctoBol'],
            //'DT_LIMITE_PAGAMENTO_BOLETO' => ['dataLimitePgt'],
            //'CODIGO_ORIGEM_PROTESTO' => ['oriProt'],
            //'CODIGO_ORIGEM_TITULO' => ['corige35'],
            //'TP_VENCIMENTO' => ['ctpoVencto'],
            //'IND_INSTRUCAO_PROTESTO' => ['codInscrProt'],
            'QUANTIDADE_DIAS_DECURSO' => ['qtdDiasDecurPrz'],
            //'NU_CONTROLE_PARTICIPANTE' => ['ctrlPartic']
        ];

        // Inicializa o array de dados
        $dados = [];

        // Mapeia as colunas da tabela FIN_API_BRADESCO que serão atualizadas
        foreach ($mapa as $coluna => $caminhos) {
            $valor = $this->obterCampoTituloConsultaUnitario($responseArray, $caminhos);
            if ($valor === null) {
                continue;
            }

            $valor = $this->normalizarValorColunaFinApiBradesco($coluna, $valor);
            if ($valor !== null) {
                $dados[$coluna] = $valor;
            }
        }

        return $dados;
    }

    /**
     * Navega array aninhado por caminho com notação ponto (ex: cedente.nome).
     *
     * @param array $fonte
     * @param string $caminho
     * @return mixed|null
     */
    function navegarArrayConsultaTituloUnitario($fonte, $caminho)
    {
        if (!is_array($fonte)) {
            return null;
        }

        // Inicializa o array atual
        $atual = $fonte;

        // Navega pelos caminhos informados
        foreach (explode('.', $caminho) as $parte) {
            if (!is_array($atual) || !array_key_exists($parte, $atual)) {
                return null;
            }
            $atual = $atual[$parte];
        }

        return $atual;
    }

    /**
     * Obtém valor do objeto titulo da consulta unitária (spec Bradesco CBTTIAGS).
     *
     * @param array $titulo
     * @param array $caminhos Caminhos relativos a titulo (ex: ['agencCred'], ['razCredt', 'razCred'])
     * @return mixed|null
     */
    function obterCampoTituloConsultaUnitario($titulo, array $caminhos)
    {

        // Navega pelos caminhos informados
        foreach ($caminhos as $caminho) {
            // Se o caminho for um array, navega pelos caminhos alternativos
            if (is_array($caminho)) {
                // Navega pelos caminhos alternativos
                foreach ($caminho as $alternativa) {

                    $valor = $this->navegarArrayConsultaTituloUnitario($titulo, $alternativa);
                    // Se o valor foi encontrado, retorna o valor
                    if ($valor !== null) {
                        return $valor;
                    }
                }
                continue;
            }

            // Navega pelo caminho informado
            $valor = $this->navegarArrayConsultaTituloUnitario($titulo, $caminho);
            if ($valor !== null) {
                return $valor;
            }
        }

        return null;
    }

    /**
     * Atualiza FIN_API_BRADESCO com retorno da consulta de título unitário.
     *
     * @param array $dados id_tabela_api, response_array, json_retorno_completo, updated_user
     * @return array Resultado da atualização
     */
    function updateTabelaApiConsultaTituloUnitario(array $dados)
    {
        try {
            // Obtém o ID da tabela API
            $id_tabela_api = (int) ($dados['id_tabela_api'] ?? $dados['idTabelaApi'] ?? 0);

            // Obtém o array de resposta da API
            $titulo = $dados['response_array']['titulo'] ?? [];

            // Mapeia as colunas da tabela FIN_API_BRADESCO que serão atualizadas
            $colunas_valores = $this->mapearColunasConsultaTituloUnitario($titulo);

            // Obtém o ID do usuário
            $colunas_valores['UPDATED_USER'] = $dados['updated_user'];

            // Monta a query de atualização
            $sets = [];
            foreach (array_keys($colunas_valores) as $campo) {
                $sets[] = $campo . ' = :' . strtolower($campo);
            }

            // Executa a query de atualização
            $banco = new c_banco_pdo();
            $banco->prepare(
                'UPDATE FIN_API_BRADESCO SET ' . implode(', ', $sets) . ' WHERE ID = :id_tabela_api'
            );

            // Define os valores dos campos
            foreach ($colunas_valores as $campo => $valor) {
                $banco->bindValue(':' . strtolower($campo), $valor);
            }
            $banco->bindValue(':id_tabela_api', $id_tabela_api, PDO::PARAM_INT);

            $banco->execute();

            return $banco->rowCount() > 0;
        } catch (Exception $e) {
            error_log('Erro ao atualizar título na tabela API Bradesco através da consulta unitária: ' . $e->getMessage());

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao atualizar título na tabela API Bradesco através da consulta!',
                'erros' => [$e->getMessage()],
                'http_code' => 422
            ];
        }
    }

    /**
     * Atualiza o lançamento financeiro com o ID da API Bradesco e o JSON retornado
     * 
     * @param array $dados id_lancamento, response_array, json_retorno_completo, updated_user
     * @return array Resultado da atualização
     */
    function updateLancamentoConsultaTituloUnitario(array $dados)
    {
        try {

            // Obtém o mapeamento de situação
            $mapa_situacao = json_decode($this->buscaMapSituacao($dados['id_conta']), true);

            if (empty($mapa_situacao)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Lançamento financeiro não atualizado!',
                    'erros' => ['Mapeamento de situação não encontrado.'],
                    'http_code' => 422
                ];
            }

            // Obtém a situação do lançamento financeiro
            $situacao = $mapa_situacao[$dados['titulo']['codStatus']] ?? null;

            // Se a situação foi encontrada, atualiza o lançamento financeiro
            if ($situacao  && $situacao != '' && $situacao != null) {

                // Obtém a situação do lançamento financeiro
                $situacao_atual = $this->getSituacaoLancamento($dados['id_lancamento']);

                if ($situacao_atual == $situacao) {
                    return [
                        'sucesso' => false,
                        'mensagem' => 'Lançamento financeiro não atualizado!',
                        'erros' => ['Título já está com a situação atual informada no sistema.'],
                        'http_code' => 422
                    ];
                }

                // Obtém a data de pagto e o código de retorno da baixa
                $data_pagto           = $dados['titulo']['dtPagto'] ?? null;
                $retorno_codigo_baixa = $dados['titulo']['codStatus'] ?? null;

                $banco = new c_banco_pdo();

                $sql = "UPDATE FIN_LANCAMENTO SET
                            SITPGTO = :situacao,
                            RETORNOARQ = 'API_BRADESCO',
                            RETORNODATALIQ = DATE_FORMAT(NOW(), '%Y-%m-%d'),
                            ID_LANCAMENTO = :id_lancamento,
                            RETORNOCODBAIXA = :retorno_codigo_baixa ";

                // se a data de pagto for informada, atualiza a coluna PAGAMENTO
                if ($data_pagto !== 0) {
                    $sql .= ", PAGAMENTO = :data_pagto ";
                }

                $sql .= ", USERCHANGE = :updated_user, DATECHANGE = NOW() WHERE REMESSANUM = :id_tabela_api";

                $banco->prepare($sql);
                $banco->bindValue(':id_tabela_api', $dados['id_tabela_api'] ?? null, PDO::PARAM_INT);
                $banco->bindValue(':id_lancamento', $dados['id_lancamento'] ?? null, PDO::PARAM_INT);
                $banco->bindValue(':situacao', $situacao, PDO::PARAM_STR);
                $banco->bindValue(':retorno_codigo_baixa', $retorno_codigo_baixa, PDO::PARAM_STR);
                $banco->bindValue(':updated_user', $dados['updated_user'] ?? null, PDO::PARAM_INT);

                // se a data de pagto for informada, atualiza a coluna PAGAMENTO
                if ($data_pagto !== 0) {
                    $data_pagto = date('Y-m-d', strtotime($data_pagto));
                    $banco->bindValue(':data_pagto', $data_pagto, PDO::PARAM_STR);
                }

                $banco->execute();

                return $banco->rowCount() > 0;
            } else {

                return [
                    'sucesso' => false,
                    'mensagem' => 'Consulta realizada mas lançamento financeiro não atualizado!',
                    'erros' => [
                        "Mapemamento de situação não encontrado na conta bancária.<br />",
                        "Situação no banco: <b>" . $dados['titulo']['codStatus'] . " - " . $dados['titulo']['status'] . "</b>"
                    ],
                    'http_code' => 422
                ];
            }
        } catch (Exception $e) {
            error_log('Erro ao atualizar lançamento financeiro com retorno da consulta unitária: ' . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao atualizar lançamento financeiro!',
                'erros' => [$e->getMessage()],
                'http_code' => 422
            ];
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
                INSERT INTO FIN_API_BRADESCO_LOG (
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
            $banco->bindValue(':ip_origem', $_SERVER['REMOTE_ADDR'] ?? null, PDO::PARAM_STR);
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
     * Busca nosso número na tabela FIN_API_BRADESCO
     * 
     * @param string $nosso_numero Nosso número do título
     * @return array Resultado da busca
     */
    static function getNossoNumero($nosso_numero)
    {
        $banco = new c_banco_pdo();

        $banco->prepare("
            SELECT ID FROM FIN_LANCAMENTO WHERE NOSSONUMERO = :nosso_numero
        ");

        $banco->bindValue(':nosso_numero', $nosso_numero, PDO::PARAM_STR);
        $banco->execute();

        $resultado = $banco->fetch(PDO::FETCH_ASSOC) ?? [];
        if (empty($resultado)) {
            return [];
        }

        return $resultado;
    }

    /**
     * Atualiza o lançamento financeiro com o ID da API Bradesco e o JSON retornado
     * 
     * @param int $id_tabela_api_bradesco ID da API Bradesco
     * @param string $json_retorno_completo JSON retornado
     * @param int $id_lancamento ID do lançamento financeiro
     * @param int $user_id ID do usuário
     * @return bool Sucesso da atualização
     */
    static function updateLancamento($id_tabela_api_bradesco, $json_retorno_completo, $id_lancamento, $user_id)
    {

        $nu_titulo_gerado = $json_retorno_completo['nuTituloGerado'] ?? null;
        $user_change      = $user_id;

        $banco = new c_banco_pdo();
        $sql = "UPDATE FIN_LANCAMENTO SET 
                    NOSSONUMERO = :nu_titulo_gerado,
                    REMESSANUM = :id_tabela_api_bradesco,
                    REMESSAARQ = 'API_BRADESCO',
                    REMESSADATA = DATE_FORMAT(NOW(), '%Y-%m-%d'),
                    USERCHANGE = :user_change,
                    DATECHANGE = NOW()
                WHERE ID = :id_lancamento";
        $banco->prepare($sql);
        $banco->bindValue(':nu_titulo_gerado', $nu_titulo_gerado, PDO::PARAM_INT);
        $banco->bindValue(':id_tabela_api_bradesco', $id_tabela_api_bradesco, PDO::PARAM_INT);
        $banco->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);
        $banco->bindValue(':user_change', $user_change, PDO::PARAM_INT);

        $banco->execute();
        return $banco->rowCount() > 0;
    }

    /**
     * Atualiza o lançamento financeiro 
     * 
     * @param array $dados Dados do lançamento baixado
     * @return bool Sucesso da atualização
     */
    static function updateLancamentoBaixado(array $dados): bool
    {

        $user_change      = $dados['updated_user'];
        $id_lancamento    = $dados['id_lancamento'];
        $situacao         = $dados['situacao'];
        $descricao        = 'BAIXA UNITÁRIA API BRADESCO | DATA: ' . date('Y-m-d') . ' - USUÁRIO: ' . $dados['user_id'];

        $banco = new c_banco_pdo();
        $sql = "UPDATE FIN_LANCAMENTO SET 
                    SITPGTO = :situacao,
                    OBSCONTABIL = :descricao,
                    USERCHANGE = :user_change,
                    DATECHANGE = NOW()
                WHERE ID = :id_lancamento";

        $banco->prepare($sql);
        $banco->bindValue(':situacao', $situacao, PDO::PARAM_STR);
        $banco->bindValue(':descricao', $descricao, PDO::PARAM_STR);
        $banco->bindValue(':user_change', $user_change, PDO::PARAM_INT);
        $banco->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);

        $banco->execute();
        return $banco->rowCount() > 0 ? true : false;
    }

    /**
     * Retorna a descrição do status conforme mapa da API Bradesco.
     *
     * @param int|string|null $status Código do status
     * @return string Descrição do status ou string vazia se não encontrado
     */
    function buscaDescricaoStatus($status): string
    {
        if ($status === null || $status === '') {
            return '';
        }

        $codigo = (int) $status;

        return $this->situacao_api_map[$codigo] ?? '';
    }

    /**
     * Atualiza o registro na tabela FIN_API_BRADESCO
     * 
     * @param array $dados Dados do registro
     * @return array Resultado da atualização
     */
    function updateTabelaApi(array $dados): bool
    {
        $user_change   = $dados['updated_user'];
        $id_tabela_api = $dados['id_tabela_api'];
        $status_atual  = $dados['response_array']['status'];
        $status_anterior = $dados['response_array']['statusAnterior'];

        $descricao_atual    = $this->buscaDescricaoStatus($status_atual);
        $descricao_anterior = $this->buscaDescricaoStatus($status_anterior);


        $banco = new c_banco_pdo();
        $banco->prepare("
            UPDATE FIN_API_BRADESCO SET 
                COD_STATUS_10 = :status_atual,
                STATUS_10 = :busca_descricao_atual,
                STATUS_ANTERIOR = :status_anterior,
                STATUS_DESC_ANTERIOR = :busca_descricao_anterior,
                USERCHANGE = :user_change,
                DATECHANGE = NOW()
            WHERE ID = :id_tabela_api
        ");

        $banco->bindValue(':status_atual', $status_atual, PDO::PARAM_INT);
        $banco->bindValue(':status_anterior', $status_anterior, PDO::PARAM_INT);
        $banco->bindValue(':descricao_atual', $descricao_atual, PDO::PARAM_STR);
        $banco->bindValue(':descricao_anterior', $descricao_anterior, PDO::PARAM_STR);
        $banco->bindValue(':user_change', $user_change, PDO::PARAM_INT);
        $banco->bindValue(':id_tabela_api', $id_tabela_api, PDO::PARAM_INT);

        $banco->execute();

        return $banco->rowCount() > 0;
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
                        FC.BRADESCO_API_CLIENT_ID_PRODUCTION,
                        FC.BRADESCO_API_CLIENT_SECRET_PRODUCTION,
                        FC.BRADESCO_API_CLIENT_ID_SANDBOX,
                        FC.BRADESCO_API_CLIENT_SECRET_SANDBOX
                    FROM FIN_CONTA FC WHERE FC.CONTA = :conta_id
                ");

                $sql->bindValue(':conta_id', $conta_id, PDO::PARAM_INT);
            } else { // se a conta não for informada, busca as credenciais na tabela FIN_LANCAMENTO

                $sql->prepare("
                    SELECT
                        FC.BRADESCO_API_CLIENT_ID_PRODUCTION,
                        FC.BRADESCO_API_CLIENT_SECRET_PRODUCTION,
                        FC.BRADESCO_API_CLIENT_ID_SANDBOX,
                        FC.BRADESCO_API_CLIENT_SECRET_SANDBOX
                    FROM FIN_LANCAMENTO FL 
                    INNER JOIN FIN_CONTA FC ON FL.CONTA = FC.CONTA
                    WHERE FL.ID = :id_lancamento
                ");

                $sql->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);
            }

            $sql->execute();

            $resultado = $sql->fetch(PDO::FETCH_ASSOC) ?? [];

            // testa se as credenciais em produção existem e se estão vazias
            if ($ambiente == 'P' && (!empty($resultado['BRADESCO_API_CLIENT_ID_PRODUCTION']) && !empty($resultado['BRADESCO_API_CLIENT_SECRET_PRODUCTION']))) {
                $client_id     = $resultado['BRADESCO_API_CLIENT_ID_PRODUCTION'];
                $client_secret = $resultado['BRADESCO_API_CLIENT_SECRET_PRODUCTION'];

                return [
                    'sucesso' => true,
                    'ambiente' => $ambiente,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret
                ];
            }


            // testa se as credenciais em sandbox existem e se estão vazias
            if ($ambiente == 'S' && (!empty($resultado['BRADESCO_API_CLIENT_ID_SANDBOX']) && !empty($resultado['BRADESCO_API_CLIENT_SECRET_SANDBOX']))) {
                $client_id     = $resultado['BRADESCO_API_CLIENT_ID_SANDBOX'];
                $client_secret = $resultado['BRADESCO_API_CLIENT_SECRET_SANDBOX'];

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
                'mensagem' => 'Credenciais da API do Bradesco em ' . $ambiente . ' não encontradas'
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
     * @param int $conta_bancaria ID da conta bancária
     * @return string
     */
    public function getAmbiente(int $conta_bancaria): string
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT AMBIENTE FROM FIN_CONTA WHERE CONTA = :conta_bancaria");
        $banco->bindValue(':conta_bancaria', $conta_bancaria, PDO::PARAM_INT);
        $banco->execute();
        $ambiente = $banco->fetch(PDO::FETCH_ASSOC) ?: 'S';
        return $ambiente['AMBIENTE'];
    }

    /**
     * Busca o ambiente da conta bancária
     * @param int $conta_bancaria ID da conta bancária
     * @return string
     */
    public function buscaMapSituacao(int $conta_bancaria): string
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT BRADESCO_SITUACAO_MAP FROM FIN_CONTA WHERE CONTA = :conta_bancaria");
        $banco->bindValue(':conta_bancaria', $conta_bancaria, PDO::PARAM_INT);
        $banco->execute();
        $mapa = $banco->fetch(PDO::FETCH_ASSOC) ?: 'S';
        return $mapa['BRADESCO_SITUACAO_MAP'];
    }

    /**
     * Busca a situação do lançamento financeiro
     * @param int $id_tabela_api ID da tabela API Bradesco
     * @return string|null
     */
    public function getSituacaoLancamento(int $id_tabela_api): string|null
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT SITPGTO FROM FIN_LANCAMENTO WHERE REMESSANUM = :id_tabela_api");
        $banco->bindValue(':id_tabela_api', $id_tabela_api, PDO::PARAM_INT);
        $banco->execute();
        $situacao = $banco->fetch(PDO::FETCH_ASSOC) ?: [];
        return $situacao['SITPGTO'] ?? null;
    }


    /**
     * Parses a CPF/CNPJ and returns an array with the cpfcnpj, filial and controle
     * @param string $documento CPF/CNPJ
     * @return array Array with the cpfcnpj, filial and controle
     */
    function parseCpfCnpj(string $documento): array
    {
        $doc = preg_replace('/\D/', '', $documento);
        $len = strlen($doc);

        if ($len === 11) {
            // CPF: 9 + 0 (filial zeros) + 2
            return [
                'cpfcnpj'  => substr($doc, 0, 9),
                'filial'   => '0000',
                'controle' => substr($doc, 9, 2),
            ];
        }

        if ($len === 14) {
            // CNPJ: 9 + 4 (filial) + 2 (dígitos verificadores)
            return [
                'cpfcnpj'  => substr($doc, 0, 8),
                'filial'   => substr($doc, 8, 4),
                'controle' => substr($doc, 12, 2),
            ];
        }

        return [];
    }

    /**
     * Obtém dados do boleto registrado via API Bradesco para impressão local (PDF).
     *
     * @param int $id_tabela_api ID do registro em FIN_API_BRADESCO (REMESSANUM do lançamento)
     * @return array
     */
    function getDadosImpressaoBoleto(int $id_tabela_api): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT
                FAB.ID,
                FAB.ID_LANCAMENTO,
                FAB.CD_BARRAS,
                FAB.LINHA_DIGITAVEL,
                FAB.NU_TITULO_GERADO,
                FAB.SEU_NUMERO_TITULO,
                FAB.ID_PRODUTO,
                FAB.AGENC_CRED_10,
                FAB.CTA_CRED_10,
                FAB.DIG_CRED_10,
                FAB.CIP_10,
                FAB.NOME_BENEFICIARIO,
                FAB.CPF_CNPJ_BENEFICIARIO,
                FAB.NOME_PAGADOR,
                FAB.CPF_CNPJ_PAGADOR,
                FAB.ENDERECO_PAGADOR,
                FAB.BAIRRO_PAGADOR,
                FAB.MUNICIPIO_PAGADOR,
                FAB.UF_PAGADOR,
                FAB.CEP_PAGADOR,
                FAB.VL_TITULO,
                FAB.VL_TITULO_EMITIDO_BOLETO,
                FAB.DT_VENCIMENTO,
                FAB.DT_VENCIMENTO_BOLETO,
                FAB.DT_EMISSAO,
                FAB.ESPECIE_DOCUMENTO_TITULO,
                FAB.DESC_ESPECIE,
                FAB.ACEITE_10,
                FAB.CODIGO_MOEDA_TITULO,
                FL.ID AS ID_LANCAMENTO_FIN,
                FL.CONTA,
                FL.PESSOA,
                FL.NUMLCTO,
                FL.DOCTO,
                FL.PARCELA,
                FL.VENCIMENTO,
                FL.EMISSAO,
                FL.TOTAL,
                FL.CENTROCUSTO,
                FL.NOSSONUMERO,
                FC.AGENCIA,
                FC.CONTACORRENTE,
                FC.CARTEIRA,
                FC.MULTA,
                FC.JUROS,
                FC.CARENCIA,
                FC.DESCONTOBONIFICACAO,
                FC.PROTESTO,
                FC.ESPECIEDOC
            FROM FIN_API_BRADESCO FAB
            INNER JOIN FIN_LANCAMENTO FL ON FL.ID = FAB.ID_LANCAMENTO
            INNER JOIN FIN_CONTA FC ON FC.CONTA = FL.CONTA
            WHERE FAB.ID = :id_tabela_api
        ");

        $banco->bindValue(':id_tabela_api', $id_tabela_api, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetch(PDO::FETCH_ASSOC) ?: [];
    }
}
