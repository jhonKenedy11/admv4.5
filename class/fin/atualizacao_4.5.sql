
#######  05-AGOSTO-2025 - JHON #######
ALTER TABLE FIN_LANCAMENTO 
MODIFY GENERO VARCHAR(6);


##################### TABELA API BRADESCO 03-DEZEMBRO-2025 #####################

-- CREATE TABLE FIN_API_BRADESCO (
--     ID INT AUTO_INCREMENT PRIMARY KEY,
--     ID_LANCAMENTO INT NOT NULL,
--     ID_PRODUTO INT NOT NULL COMMENT 'Identificador do produto',
--     NEGOCIACAO BIGINT NOT NULL COMMENT 'Número da negociação',
--     CPSSOA_JURID_CONTRN INT NOT NULL COMMENT 'Código da pessoa jurídica contratante',
--     CTPO_CONTR_NEGOC INT NOT NULL COMMENT 'Tipo de contrato da negociação',
--     NSEQ_CONTR_NEGOC BIGINT NOT NULL COMMENT 'Número sequencial do contrato da negociação',
--     CPRODT_SERVC_OPER INT NOT NULL COMMENT 'Código do produto ou serviço da operação',
--     NU_TITULO_GERADO BIGINT COMMENT 'Número do título gerado',
--     TP08_REG1 TINYINT COMMENT 'Tipo de registro 1',
--     AGENC_CRED10 INT COMMENT 'Agência de crédito',
--     CTA_CRED10 BIGINT COMMENT 'Conta de crédito',
--     DIG_CRED10 TINYINT COMMENT 'Dígito da conta de crédito',
--     CIP10 SMALLINT COMMENT 'Código CIP',
--     COD_STATUS10 TINYINT COMMENT 'Código do status do título',
--     STATUS10 VARCHAR(40) COMMENT 'Descrição do status do título',
--     NOME_BENEFICIARIO VARCHAR(40) COMMENT 'Nome do beneficiário',
--     LOGRADOURO_BENEFICIARIO VARCHAR(40) COMMENT 'Logradouro do beneficiário',
--     NU_LOGRADOURO_BENEFICIARIO VARCHAR(7) COMMENT 'Número do logradouro do beneficiário',
--     COMPLEMENTO_LOGRADOURO_BENEFICIARIO VARCHAR(20) COMMENT 'Complemento do logradouro do beneficiário',
--     BAIRRO_BENEFICIARIO VARCHAR(20) COMMENT 'Bairro do beneficiário',
--     CEP_BENEFICIARIO INT COMMENT 'CEP do beneficiário',
--     CEP_COMPLEMENTO_BENEFICIARIO SMALLINT COMMENT 'Complemento do CEP do beneficiário',
--     MUNICIPIO_BENEFICIARIO VARCHAR(50) COMMENT 'Município do beneficiário',
--     UF_BENEFICIARIO CHAR(2) COMMENT 'UF do beneficiário',
--     RAZ_CREDT10 INT COMMENT 'Razão do crédito',
--     NOME_PAGADOR VARCHAR(40) COMMENT 'Nome do pagador',
--     CPFCNPJ_PAGADOR BIGINT COMMENT 'CPF ou CNPJ do pagador',
--     ENDERECO_PAGADOR VARCHAR(40) COMMENT 'Endereço do pagador',
--     BAIRRO_PAGADOR VARCHAR(20) COMMENT 'Bairro do pagador',
--     MUNICIPIO_PAGADOR VARCHAR(40) COMMENT 'Município do pagador',
--     UF_PAGADOR CHAR(2) COMMENT 'UF do pagador',
--     CEP_PAGADOR INT COMMENT 'CEP do pagador',
--     CEP_COMPLEMENTO_PAGADOR SMALLINT COMMENT 'Complemento do CEP do pagador',
--     CEPB10 VARCHAR(10) COMMENT 'CEP adicional',
--     DEBITO_AUTO10 CHAR(1) COMMENT 'Indicação de débito automático',
--     ACEITE10 CHAR(1) COMMENT 'Indicação de aceite',
--     END_ELETRONICO_PAGADOR VARCHAR(40) COMMENT 'Endereço eletrônico do pagador',
--     NOME_SACADOR_AVALISTA VARCHAR(40) COMMENT 'Nome do sacador/avalista',
--     CNPJ_CPF_SACADOR_AVALISTA BIGINT COMMENT 'CNPJ ou CPF do sacador/avalista',
--     ENDERECO_SACADOR_AVALISTA VARCHAR(100) COMMENT 'Endereço do sacador/avalista',
--     MUNICIPIO_SACADOR_AVALISTA VARCHAR(50) COMMENT 'Município do sacador/avalista',
--     UF_SACADOR_AVALISTA CHAR(2) COMMENT 'UF do sacador/avalista',
--     CEP_SACADOR_AVALISTA INT COMMENT 'CEP do sacador/avalista',
--     CEP_COMPLEMENTO_SACADOR_AVALISTA SMALLINT COMMENT 'Complemento do CEP do sacador/avalista',
--     TP08_REG2 TINYINT COMMENT 'Tipo de registro 2',
--     CENSE10 TINYINT COMMENT 'Código de censo',
--     AGEN_OPER10 TINYINT COMMENT 'Agência da operação',
--     BCO_DEPOS10 TINYINT COMMENT 'Banco de depósito',
--     AGEN_DEPOS10 TINYINT COMMENT 'Agência de depósito',
--     SEU_NUMERO_TITULO VARCHAR(25) COMMENT 'Número do título do cliente',
--     DT_REGISTRO DATE COMMENT 'Data de registro',
--     ESPECIE_DOCUMENTO_TITULO CHAR(2) COMMENT 'Espécie do documento do título',
--     DESC_ESPECIE VARCHAR(50) COMMENT 'Descrição da espécie do documento',
--     VL_IOF DECIMAL(15,2) COMMENT 'Valor do IOF',
--     DT_EMISSAO DATE COMMENT 'Data de emissão',
--     CODIGO_MOEDA_TITULO CHAR(2) COMMENT 'Código da moeda do título',
--     QUANTIDADE_MOEDA DECIMAL(15,5) COMMENT 'Quantidade de moeda',
--     QUANTIDADE_CASAS TINYINT COMMENT 'Quantidade de casas decimais',
--     DT_VENCIMENTO DATE COMMENT 'Data de vencimento',
--     DESCRICACAO_MOEDA CHAR(2) COMMENT 'Descrição da moeda',
--     VL_TITULO DECIMAL(15,2) COMMENT 'Valor do título',
--     VL_ABATIMENTO DECIMAL(15,2) COMMENT 'Valor de abatimento',
--     JSON_RETORNO_COMPLETO JSON COMMENT 'Resposta completa da API Bradesco',
--     CREATED_USER INT NOT NULL,
--     UPDATED_USER INT,
--     CREATED_AT DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     UPDATED_AT DATETIME ON UPDATE CURRENT_TIMESTAMP,
--     INDEX idx_id_lancamento (ID_LANCAMENTO),
--     INDEX idx_nu_titulo (NU_TITULO_GERADO),
--     INDEX idx_negociacao (NEGOCIACAO),
--     INDEX idx_dt_vencimento (DT_VENCIMENTO),
--     INDEX idx_cpfcnpj_pagador (CPFCNPJ_PAGADOR));

-- Baseado no exemplo json da documentação da API Bradesco, pois a documentacao nao esta completa.
CREATE TABLE FIN_API_BRADESCO (
    ID                                          INT AUTO_INCREMENT PRIMARY KEY,
    ID_LANCAMENTO                               INT NOT NULL,

    -- Identificação
    ID_PRODUTO                                  TINYINT COMMENT 'Identificador do produto',
    NEGOCIACAO                                  BIGINT COMMENT 'Número da negociação',
    CPSSOA_JURID_CONTR                          INT COMMENT 'Código da pessoa jurídica contratante',
    CTPO_CONTR_NEGOC                            TINYINT COMMENT 'Tipo de contrato da negociação',
    NSEQ_CONTR_NEGOC                            BIGINT COMMENT 'Número sequencial do contrato da negociação',
    CPRODT_SERVC_OPER                           INT COMMENT 'Código do produto ou serviço da operação',
    NU_TITULO_GERADO                            BIGINT COMMENT 'Número do título gerado',
    TP_08_REG_1                                 TINYINT COMMENT 'Tipo de registro 1',
    AGENC_CRED_10                               INT COMMENT 'Agência de crédito',
    CTA_CRED_10                                 BIGINT COMMENT 'Conta de crédito',
    DIG_CRED_10                                 VARCHAR(2) COMMENT 'Dígito da conta de crédito',
    CIP_10                                      SMALLINT COMMENT 'Código CIP',
    COD_STATUS_10                               TINYINT COMMENT 'Código do status do título',
    STATUS_10                                   VARCHAR(40) COMMENT 'Descrição do status do título',

    -- Beneficiário
    NOME_BENEFICIARIO                           VARCHAR(40) COMMENT 'Nome do beneficiário',
    LOGRADOURO_BENEFICIARIO                     VARCHAR(40) COMMENT 'Logradouro do beneficiário',
    NU_LOGRADOURO_BENEFICIARIO                  VARCHAR(7) COMMENT 'Número do logradouro do beneficiário',
    COMPLEMENTO_LOGRADOURO_BENEFICIARIO         VARCHAR(20) COMMENT 'Complemento do logradouro do beneficiário',
    BAIRRO_BENEFICIARIO                         VARCHAR(20) COMMENT 'Bairro do beneficiário',
    CEP_BENEFICIARIO                            INT COMMENT 'CEP do beneficiário',
    CEP_COMPLEMENTO_BENEFICIARIO                SMALLINT COMMENT 'Complemento do CEP do beneficiário',
    MUNICIPIO_BENEFICIARIO                      VARCHAR(50) COMMENT 'Município do beneficiário',
    UF_BENEFICIARIO                             CHAR(2) COMMENT 'UF do beneficiário',
    RAZ_CREDT_10                                INT COMMENT 'Razão do crédito',
    CPF_CNPJ_BENEFICIARIO                       VARCHAR(15) COMMENT 'CPF ou CNPJ do beneficiário',

    -- Pagador
    NOME_PAGADOR                                VARCHAR(40) COMMENT 'Nome do pagador',
    CPF_CNPJ_PAGADOR                            BIGINT COMMENT 'CPF ou CNPJ do pagador',
    ENDERECO_PAGADOR                            VARCHAR(40) COMMENT 'Endereço do pagador',
    BAIRRO_PAGADOR                              VARCHAR(20) COMMENT 'Bairro do pagador',
    MUNICIPIO_PAGADOR                           VARCHAR(40) COMMENT 'Município do pagador',
    UF_PAGADOR                                  CHAR(2) COMMENT 'UF do pagador',
    CEP_PAGADOR                                 INT COMMENT 'CEP do pagador',
    CEP_COMPLEMENTO_PAGADOR                     VARCHAR(3) COMMENT 'Complemento do CEP do pagador',
    CEBP_10                                     VARCHAR(10) COMMENT 'CEP adicional',
    DEBITO_AUTO_10                              CHAR(1) COMMENT 'Indicação de débito automático',
    ACEITE_10                                   CHAR(1) COMMENT 'Indicação de aceite',
    END_ELETRONICO_PAGADOR                      VARCHAR(40) COMMENT 'Endereço eletrônico do pagador',

    -- Sacador / Avalista
    NOME_SACADOR_AVALISTA                       VARCHAR(40) COMMENT 'Nome do sacador/avalista',
    CNPJ_CPF_SACADOR_AVALISTA                   BIGINT COMMENT 'CNPJ ou CPF do sacador/avalista',
    ENDERECO_SACADOR_AVALISTA                   VARCHAR(100) COMMENT 'Endereço do sacador/avalista',
    MUNICIPIO_SACADOR_AVALISTA                  VARCHAR(50) COMMENT 'Município do sacador/avalista',
    UF_SACADOR_AVALISTA                         CHAR(2) COMMENT 'UF do sacador/avalista',
    CEP_SACADOR_AVALISTA                        INT COMMENT 'CEP do sacador/avalista',
    CEP_COMPLEMENTO_SACADOR_AVALISTA            SMALLINT COMMENT 'Complemento do CEP do sacador/avalista',

    -- Registro 2
    TP_08_REG_2                                 TINYINT COMMENT 'Tipo de registro 2',
    CENSE_10                                    TINYINT COMMENT 'Código de censo',
    AGEN_OPER_10                                TINYINT COMMENT 'Agência da operação',
    BCO_DEPOS_10                                TINYINT COMMENT 'Banco de depósito',
    AGEN_DEPOS_10                               TINYINT COMMENT 'Agência de depósito',

    -- Título
    SEU_NUMERO_TITULO                           VARCHAR(25) COMMENT 'Número do título do cliente',
    DT_REGISTRO                                 VARCHAR(20) COMMENT 'Data de registro (valor bruto da API)',
    ESPECIE_DOCUMENTO_TITULO                    CHAR(2) COMMENT 'Espécie do documento do título',
    DESC_ESPECIE                                VARCHAR(50) COMMENT 'Descrição da espécie do documento',
    VL_IOF                                      DECIMAL(15,2) COMMENT 'Valor do IOF',
    DT_EMISSAO                                  VARCHAR(20) COMMENT 'Data de emissão (valor bruto da API)',
    CODIGO_MOEDA_TITULO                         CHAR(2) COMMENT 'Código da moeda do título',
    QUANTIDADE_MOEDA                            DECIMAL(15,5) COMMENT 'Quantidade de moeda',
    QUANTIDADE_CASAS                            TINYINT COMMENT 'Quantidade de casas decimais',
    DT_VENCIMENTO                               VARCHAR(20) COMMENT 'Data de vencimento (valor bruto da API)',
    DESCRICACAO_MOEDA                           CHAR(2) COMMENT 'Descrição da moeda',
    VL_TITULO                                   DECIMAL(15,2) COMMENT 'Valor do título',
    VL_ABATIMENTO                               DECIMAL(15,2) COMMENT 'Valor de abatimento',

    -- Protesto / Negativação
    DT_INSTRUCAO_PROTESTO_NEGATIVACAO           VARCHAR(20) COMMENT 'Data instrução protesto/negativação (valor bruto da API)',
    DIAS_INSTRUCAO_PROTESTO_NEGATIVACAO         TINYINT COMMENT 'Dias para instrução de protesto/negativação',
    DATA_ENVIO_CARTORIO                         VARCHAR(20) COMMENT 'Data de envio ao cartório (valor bruto da API)',
    NUMERO_CARTORIO                             VARCHAR(10) COMMENT 'Número do cartório',
    NUMERO_PROTOCOLO_CARTORIO                   VARCHAR(20) COMMENT 'Número do protocolo do cartório',
    DATA_PEDIDO_SUSTACAO                        VARCHAR(20) COMMENT 'Data do pedido de sustação (valor bruto da API)',
    DATA_SUSTACAO                               VARCHAR(20) COMMENT 'Data de sustação (valor bruto da API)',

    -- Multa
    DT_MULTA                                    VARCHAR(8) COMMENT 'Data de multa (formato DDMMAAAA ou 0)',
    VL_MULTA                                    DECIMAL(15,2) COMMENT 'Valor da multa',
    QTDE_CASAS_DECIMAIS_MULTA                   TINYINT COMMENT 'Quantidade de casas decimais da multa',
    CD_VALOR_MULTA                              TINYINT COMMENT 'Código do valor da multa',
    DESC_CD_MULTA                               VARCHAR(50) COMMENT 'Descrição do código de multa',

    -- Juros
    DT_JUROS                                    VARCHAR(20) COMMENT 'Data de início dos juros (valor bruto da API)',
    VL_JUROS_AO_DIA                             DECIMAL(15,2) COMMENT 'Valor de juros ao dia',
    DIAS_JUROS                                  TINYINT COMMENT 'Dias para início dos juros',
    CD_JUROS                                    TINYINT COMMENT 'Código de juros',
    VL_JUROS                                    DECIMAL(15,2) COMMENT 'Valor dos juros',
    DIAS_DISPENSA_JUROS                         TINYINT COMMENT 'Dias de dispensa de juros',
    CD_VALOR_JUROS                              TINYINT COMMENT 'Código do valor de juros',

    -- Desconto 1 / Bonificação
    DT_DESCONTO_1_BONIFICACAO                   VARCHAR(20) COMMENT 'Data desconto 1/bonificação (valor bruto da API)',
    VL_DESCONTO_1_BONIFICACAO                   DECIMAL(15,2) COMMENT 'Valor do desconto 1 ou bonificação',
    QTDE_CASAS_DECIMAIS_DESCONTO_1_BONIFICACAO  TINYINT COMMENT 'Casas decimais do desconto 1 ou bonificação',
    CD_VALOR_DESCONTO_1_BONIFICACAO             TINYINT COMMENT 'Código do valor do desconto 1 ou bonificação',
    DESC_CD_DESCONTO_1_BONIFICACAO              VARCHAR(50) COMMENT 'Descrição do código do desconto 1 ou bonificação',
    TP_DESCONTO_1                               TINYINT COMMENT 'Tipo do desconto 1',

    -- Desconto 2
    DT_DESCONTO_2                               VARCHAR(20) COMMENT 'Data do desconto 2 (valor bruto da API)',
    VL_DESCONTO_2                               DECIMAL(15,2) COMMENT 'Valor do desconto 2',
    QTDE_CASAS_DECIMAIS_DESCONTO_2              TINYINT COMMENT 'Casas decimais do desconto 2',
    CD_VALOR_DESCONTO_2                         TINYINT COMMENT 'Código do valor do desconto 2',
    DESC_CD_DESCONTO_2                          VARCHAR(50) COMMENT 'Descrição do código do desconto 2',
    TP_DESCONTO_2                               TINYINT COMMENT 'Tipo do desconto 2',

    -- Desconto 3
    DT_DESCONTO_3                               VARCHAR(20) COMMENT 'Data do desconto 3 (valor bruto da API)',
    VL_DESCONTO_3                               DECIMAL(15,2) COMMENT 'Valor do desconto 3',
    QTDE_CASAS_DECIMAIS_DESCONTO_3              TINYINT COMMENT 'Casas decimais do desconto 3',
    CD_VALOR_DESCONTO_3                         TINYINT COMMENT 'Código do valor do desconto 3',
    DESC_CD_DESCONTO_3                          VARCHAR(50) COMMENT 'Descrição do código do desconto 3',
    TP_DESCONTO_3                               TINYINT COMMENT 'Tipo do desconto 3',

    -- Dispensa
    DIAS_DISPENSA_MULTA                         TINYINT COMMENT 'Dias de dispensa de multa',

    -- Boleto
    CD_BARRAS                                   VARCHAR(200) COMMENT 'Código de barras',
    LINHA_DIGITAVEL                             VARCHAR(60) COMMENT 'Linha digitável',
    VL_TITULO_EMITIDO_BOLETO                    DECIMAL(15,2) COMMENT 'Valor do título emitido no boleto',
    DT_VENCIMENTO_BOLETO                        VARCHAR(20) COMMENT 'Data vencimento boleto (valor bruto da API)',
    DT_LIMITE_PAGAMENTO_BOLETO                  VARCHAR(20) COMMENT 'Data limite pagamento boleto (valor bruto da API)',

    -- Outros campos de retorno
    DESP_CART_10                                TINYINT COMMENT 'Despesa de cartório',
    BCO_CENTR_10                                TINYINT COMMENT 'Banco central',
    AGE_CENTR_10                                TINYINT COMMENT 'Agência central',
    ACESS_ESC_10                                TINYINT COMMENT 'Acesso escritório',
    TIPO_ENDOSSO                                VARCHAR(10) COMMENT 'Tipo de endosso',
    CODIGO_ORIGEM_PROTESTO                      TINYINT COMMENT 'Código de origem do protesto',
    CODIGO_ORIGEM_TITULO                        VARCHAR(10) COMMENT 'Código de origem do título',
    TP_VENCIMENTO                               TINYINT COMMENT 'Tipo de vencimento',
    IND_INSTRUCAO_PROTESTO                      TINYINT COMMENT 'Indicador de instrução de protesto',
    INDICADOR_DECURSO                           TINYINT COMMENT 'Indicador de decurso',
    QUANTIDADE_DIAS_DECURSO                     TINYINT COMMENT 'Quantidade de dias de decurso',
    CTPO_ABAT_10                                TINYINT COMMENT 'Tipo de abatimento',
    NU_CONTROLE_PARTICIPANTE                    VARCHAR(25) COMMENT 'Número de controle do participante',
    IND_TIT_PARCELD_10                          VARCHAR(5) COMMENT 'Indicador de título parcelado',
    IND_PARCELA_PRIN_10                         VARCHAR(5) COMMENT 'Indicador de parcela principal',
    IND_BOLETO_DDA_10                           VARCHAR(5) COMMENT 'Indicador de boleto DDA',
    DATA_IMPRESSAO_10                           VARCHAR(20) COMMENT 'Data de impressão (valor bruto da API)',
    HORA_IMPRESSAO_10                           INT COMMENT 'Hora de impressão',
    IDENT_TIT_DDA_10                            BIGINT COMMENT 'Identificador do título DDA',
    EXIBE_LIN_DIG_10                            CHAR(1) COMMENT 'Exibe linha digitável',
    PERM_PGTO_PARCIAL                           VARCHAR(5) COMMENT 'Permissão de pagamento parcial',
    QTDE_PGTO_PARCIAL                           TINYINT COMMENT 'Quantidade de pagamentos parciais',
    STATUS_ANTERIOR                             CHAR(1) COMMENT 'Status anterior',
    STATUS_DESC_ANTERIOR                        VARCHAR(40) COMMENT 'Descrição do status anterior',

    -- Débito automático
    BANCO_DEB                                   SMALLINT COMMENT 'Banco para débito automático',
    AGENCIA_DEB                                 INT COMMENT 'Agência para débito automático',
    AGENCIA_DEB_DV                              TINYINT COMMENT 'Dígito verificador da agência de débito',
    CONTA_DEB                                   BIGINT COMMENT 'Conta para débito automático',
    RAZAO_CONTA_DEBITO                          INT COMMENT 'Razão da conta de débito',

    -- Controle
    JSON_RETORNO_COMPLETO                       JSON COMMENT 'Resposta completa da API Bradesco',
    CREATED_USER                                INT NOT NULL,
    UPDATED_USER                                INT,
    CREATED_AT                                  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UPDATED_AT                                  DATETIME ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_id_lancamento     (ID_LANCAMENTO),
    INDEX idx_nu_titulo         (NU_TITULO_GERADO),
    INDEX idx_negociacao        (NEGOCIACAO),
    INDEX idx_dt_vencimento     (DT_VENCIMENTO),
    INDEX idx_cpf_cnpj_pagador  (CPF_CNPJ_PAGADOR)
);




##################### TABELA API BRADESCO 06-JANEIRO-2026 #####################

CREATE TABLE FIN_ESPECIE_TITULO (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    CODIGO INT NOT NULL,
    SIGLA VARCHAR(5) NOT NULL,
    DESCRICAO VARCHAR(100) NOT NULL,
    NUMERO_BANCO VARCHAR(5) NOT NULL,
    CREATED_USER INT(11) NOT NULL,
    UPDATED_USER INT(11) DEFAULT NULL,
    CREATED_AT TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UPDATED_AT TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO FIN_ESPECIE_TITULO 
(CODIGO, SIGLA, DESCRICAO, NUMERO_BANCO, CREATED_USER, UPDATED_USER)
VALUES
(1,  'CH',  'CHEQUE', '237', 999, NULL),
(2,  'DM',  'DUPLICATA DE VENDA MERCANTIL', '237', 999, NULL),
(3,  'DMI', 'DUPLICATA MERCANTIL POR INDICAÇÃO', '237', 999, NULL),
(4,  'DS',  'DUPLICATA DE PRESTAÇÃO DE SERVIÇOS', '237', 999, NULL),
(5,  'DSI', 'DUPLICATA PREST. SERVIÇOS POR INDICAÇÃO', '237', 999, NULL),
(6,  'DR',  'DUPLICATA RURAL', '237', 999, NULL),
(7,  'LC',  'LETRA DE CÂMBIO', '237', 999, NULL),
(8,  'NCC', 'NOTA DE CRÉDITO COMERCIAL', '237', 999, NULL),
(9,  'NCE', 'NOTA DE CRÉDITO EXPORTAÇÃO', '237', 999, NULL),
(10, 'NCI', 'NOTA DE CRÉDITO INDUSTRIAL', '237', 999, NULL),
(11, 'NCR', 'NOTA DE CRÉDITO RURAL', '237', 999, NULL),
(12, 'NP',  'NOTA PROMISSÓRIA', '237', 999, NULL),
(13, 'NPR', 'NOTA PROMISSÓRIA RURAL', '237', 999, NULL),
(14, 'TM',  'TRIPLICATA DE VENDA MERCANTIL', '237', 999, NULL),
(15, 'TS',  'TRIPLICATA DE PRESTAÇÃO DE SERVIÇOS', '237', 999, NULL),
(16, 'NS',  'NOTA DE SERVIÇO', '237', 999, NULL),
(17, 'RC',  'RECIBO', '237', 999, NULL),
(18, 'FAT', 'FATURA', '237', 999, NULL),
(19, 'ND',  'NOTA DE DÉBITO', '237', 999, NULL),
(20, 'AP',  'APÓLICE DE SEGURO', '237', 999, NULL),
(21, 'ME',  'MENSALIDADE ESCOLAR', '237', 999, NULL),
(22, 'PC',  'PARCELA DE CONSÓRCIO', '237', 999, NULL),
(23, 'DD',  'DOCUMENTO DE DÍVIDA', '237', 999, NULL),
(24, 'CCB', 'CÉDULA DE CRÉDITO BANCÁRIO', '237', 999, NULL),
(25, 'FI',  'FINANCIAMENTO', '237', 999, NULL),
(26, 'RD',  'RATEIO DE DESPESAS', '237', 999, NULL),
(27, 'DRI', 'DUPLICATA RURAL INDICAÇÃO', '237', 999, NULL),
(28, 'EC',  'ENCARGOS CONDOMINIAIS', '237', 999, NULL),
(29, 'ECI', 'ENCARGOS CONDOMINIAIS POR INDICAÇÃO', '237', 999, NULL),
(31, 'CC',  'CARTÃO DE CRÉDITO', '237', 999, NULL),
(32, 'BDP', 'BOLETO DE PROPOSTA', '237', 999, NULL),
(99, 'OUT', 'OUTROS', '237', 999, NULL);


##################### TABELA LOG API BRADESCO 15-JANEIRO-2026 #####################

CREATE TABLE FIN_API_BANCOS_LOG (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    BANCO VARCHAR(10) NOT NULL COMMENT 'Código do banco',
    ID_LANCAMENTO INT DEFAULT NULL COMMENT 'ID do lançamento financeiro (quando aplicável)',
    ID_CONTA INT DEFAULT NULL COMMENT 'ID da conta bancária',
    TIPO_OPERACAO VARCHAR(50) NOT NULL COMMENT 'Tipo de operação: REGISTRO, BAIXA, ALTERACAO, CONSULTA, PROTESTO',
    AMBIENTE VARCHAR(20) NOT NULL COMMENT 'Ambiente: sandbox ou producao',
    ENDPOINT VARCHAR(255) NOT NULL COMMENT 'Endpoint da API chamado',
    HTTP_CODE INT DEFAULT NULL COMMENT 'Código HTTP retornado',
    SUCESSO TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=Sucesso, 0=Erro',
    COD_RETORNO_API VARCHAR(10) DEFAULT NULL COMMENT 'Código de retorno da API Bradesco',
    MENSAGEM_API VARCHAR(500) DEFAULT NULL COMMENT 'Mensagem de retorno da API',
    ERROS_VALIDACAO TEXT DEFAULT NULL COMMENT 'Lista de erros de validação (JSON)',
    JSON_ENVIADO JSON DEFAULT NULL COMMENT 'JSON enviado para a API',
    JSON_RETORNO JSON DEFAULT NULL COMMENT 'JSON completo retornado pela API',
    IP_ORIGEM VARCHAR(45) DEFAULT NULL COMMENT 'IP de origem da requisição',
    CREATED_USER INT NOT NULL COMMENT 'Usuário que solicitou',
    CREATED_AT DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data/hora da requisição',
    INDEX idx_id_lancamento (ID_LANCAMENTO),
    INDEX idx_tipo_operacao (TIPO_OPERACAO),
    INDEX idx_sucesso (SUCESSO),
    INDEX idx_created_at (CREATED_AT),
    INDEX idx_created_user (CREATED_USER),
    INDEX idx_ambiente (AMBIENTE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Log de requisições da API Bradesco';


##################### ADICIONAR CAMPOS PARA CREDENCIAIS API BRADESCO e INTER 08-FEVEREIRO-2026 #####################
ALTER TABLE FIN_CONTA 
ADD COLUMN BRADESCO_API_CLIENT_ID_SANDBOX VARCHAR(100) NULL AFTER INTER_SITUACAO_MAP,
ADD COLUMN BRADESCO_API_CLIENT_ID_PRODUCTION VARCHAR(100) NULL AFTER BRADESCO_API_CLIENT_ID_SANDBOX,
ADD COLUMN BRADESCO_API_CLIENT_SECRET_SANDBOX VARCHAR(100) NULL AFTER BRADESCO_API_CLIENT_ID_PRODUCTION,
ADD COLUMN BRADESCO_API_CLIENT_SECRET_PRODUCTION VARCHAR(100) NULL AFTER BRADESCO_API_CLIENT_SECRET_SANDBOX;

ALTER TABLE FIN_CONTA 
ADD COLUMN INTER_API_CLIENT_ID_SANDBOX VARCHAR(100) NULL AFTER BRADESCO_API_CLIENT_SECRET_PRODUCTION,
ADD COLUMN INTER_API_CLIENT_ID_PRODUCTION VARCHAR(100) NULL AFTER INTER_API_CLIENT_ID_SANDBOX,
ADD COLUMN INTER_API_CLIENT_SECRET_SANDBOX VARCHAR(100) NULL AFTER INTER_API_CLIENT_ID_PRODUCTION,
ADD COLUMN INTER_API_CLIENT_SECRET_PRODUCTION VARCHAR(100) NULL AFTER INTER_API_CLIENT_SECRET_SANDBOX;

##################### ADICIONAR COLUNA ENVIA_BOLETO 01-ABRIL-2026 #####################
ALTER TABLE FIN_CONTA 
ADD COLUMN ENVIA_BOLETO CHAR(1) NULL DEFAULT 'R' COMMENT 'A=API, R=Remessa bancária';

UPDATE FIN_CONTA SET ENVIA_BOLETO = 'R' WHERE 1 = 1;


##################### ADICIONAR COLUNA CONTA_CORRENTE_DIGITO 15-ABRIL-2026 #####################
ALTER TABLE FIN_CONTA 
ADD COLUMN CONTA_CORRENTE_DIGITO CHAR(1) NULL AFTER CONTACORRENTE;


##################### TABELA CONSULTAS TITULOS API 26-MARÇO-2026 #####################

CREATE TABLE FIN_CONSULTAS_TITULOS_API (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    BANCO VARCHAR(10) NOT NULL COMMENT 'Código do banco',
    CONTA VARCHAR(10) NOT NULL COMMENT 'Número da conta',
    CENTRO_CUSTO VARCHAR(10) NOT NULL COMMENT 'Centro de custo',
    TIPO_OPERACAO VARCHAR(100) NOT NULL,
    JSON_REQUEST JSON NULL,
    JSON_RESPONSE JSON NULL,
    ENDPOINT VARCHAR(255) NOT NULL,
    AMBIENTE VARCHAR(20) NOT NULL,
    DATA_HORA_CONSULTA DATETIME NOT NULL,
    CREATED_USER INT(11) NULL,
    CREATED_AT TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

##################### TABELA API INTER 16-ABRIL-2026 #####################

CREATE TABLE FIN_API_INTER (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ID_LANCAMENTO INT NOT NULL COMMENT 'FIN_LANCAMENTO.ID',
    CONTA_CORRENTE_HEADER VARCHAR(10) DEFAULT NULL COMMENT 'Conta corrente + dígito',
    CODIGO_SOLICITACAO VARCHAR(40) DEFAULT NULL COMMENT 'UUID retornado pela API (cobranca.codigoSolicitacao)',
    SEU_NUMERO VARCHAR(20) DEFAULT NULL,
    DATA_EMISSAO DATE DEFAULT NULL,
    DATA_VENCIMENTO DATE DEFAULT NULL,
    VALOR_NOMINAL DECIMAL(15,2) DEFAULT NULL,
    TIPO_COBRANCA VARCHAR(30) DEFAULT NULL,
    SITUACAO VARCHAR(40) DEFAULT NULL COMMENT 'A_RECEBER, RECEBIDO, ATRASADO, CANCELADO, EXPIRADO, MARCADO_RECEBIDO, FALHA_EMISSAO, EM_PROCESSAMENTO, PROTESTO',
    DATA_SITUACAO DATE DEFAULT NULL,
    VALOR_TOTAL_RECEBIDO DECIMAL(15,2) DEFAULT NULL,
    ORIGEM_RECEBIMENTO VARCHAR(30) DEFAULT NULL,
    ARQUIVADA TINYINT(1) NOT NULL DEFAULT 0,
    PAGADOR_EMAIL VARCHAR(100) DEFAULT NULL,
    PAGADOR_DDD VARCHAR(5) DEFAULT NULL,
    PAGADOR_TELEFONE VARCHAR(15) DEFAULT NULL,
    PAGADOR_NUMERO VARCHAR(15) DEFAULT NULL,
    PAGADOR_COMPLEMENTO VARCHAR(60) DEFAULT NULL,
    PAGADOR_CPFCNPJ VARCHAR(18) DEFAULT NULL,
    PAGADOR_TIPO_PESSOA VARCHAR(15) DEFAULT NULL,
    PAGADOR_NOME VARCHAR(100) DEFAULT NULL,
    PAGADOR_ENDERECO VARCHAR(100) DEFAULT NULL,
    PAGADOR_BAIRRO VARCHAR(60) DEFAULT NULL,
    PAGADOR_CIDADE VARCHAR(60) DEFAULT NULL,
    PAGADOR_UF CHAR(2) DEFAULT NULL,
    PAGADOR_CEP VARCHAR(10) DEFAULT NULL,
    BOLETO_NOSSO_NUMERO VARCHAR(20) DEFAULT NULL,
    BOLETO_CODIGO_BARRAS VARCHAR(54) DEFAULT NULL,
    BOLETO_LINHA_DIGITAVEL VARCHAR(54) DEFAULT NULL,
    PIX_TXID VARCHAR(40) DEFAULT NULL,
    PIX_COPIA_E_COLA TEXT DEFAULT NULL,
    NF_CHAVE_NFE CHAR(44) DEFAULT NULL,
    NF_NUMERO INT DEFAULT NULL,
    NF_SERIE INT DEFAULT NULL,
    NF_DATA_EMISSAO DATE DEFAULT NULL,
    NF_PARCELA INT DEFAULT NULL,
    NF_NATUREZA_OPERACAO VARCHAR(255) DEFAULT NULL,
    PDF_BINARIO LONGBLOB DEFAULT NULL COMMENT 'PDF do boleto em binário',
    JSON_RETORNO_COMPLETO JSON DEFAULT NULL COMMENT 'Payload completo retornado pela API',
    CREATED_USER INT NOT NULL,
    CREATED_AT DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_api_inter_lancamento (ID_LANCAMENTO),
    INDEX idx_api_inter_codigo_solicitacao (CODIGO_SOLICITACAO),
    INDEX idx_api_inter_seu_numero (SEU_NUMERO),
    INDEX idx_api_inter_conta_corrente_header (CONTA_CORRENTE_HEADER)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


##################### ADICIONAR COLUNA AMBIENTE 28-MAIO-2026 #####################
ALTER TABLE FIN_CONTA 
ADD COLUMN AMBIENTE CHAR(1) DEFAULT 'S' COMMENT 'S - sandbox, P - producao' NULL AFTER ENVIA_BOLETO;

##################### ADICIONAR COLUNA NOSSONUMERO 29-MAIO-2026 #####################

ALTER TABLE FIN_LANCAMENTO
MODIFY COLUMN NOSSONUMERO BIGINT NULL;

##################### ADICIONAR COLUNA INTER_SITUACAO_MAP 29-MAIO-2026 #####################
ALTER TABLE FIN_CONTA 
ADD COLUMN INTER_SITUACAO_MAP JSON NULL 
COMMENT 'Mapeamento situação API Inter => SITPGTO do lançamento' AFTER AMBIENTE;

##################### ADICIONAR COLUNA BRADESCO_SITUACAO_MAP 10-JUNHO-2026 #####################
ALTER TABLE FIN_CONTA 
ADD COLUMN BRADESCO_SITUACAO_MAP JSON NULL 
COMMENT 'Mapeamento situação API Bradesco => SITPGTO do lançamento' AFTER INTER_SITUACAO_MAP;

