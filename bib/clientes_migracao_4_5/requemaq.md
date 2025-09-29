
Ajustar biblioteca datarangepicker.
adicionar a pasta em css, no js alterar a versão, e a linha abaixo no header.tpl
<link href="css/daterangepicker/daterangepicker.css" rel="stylesheet">


biblioteca local/cliente do sweet alert na pasta js, e adicionar no config.
define( "ADMsweetAlert2", ADMhttpCliente . '/../sweetalert2');

########### PEDIDO ########
ADICIONAR O RELATORIOS.
<li><a href="index.php?mod=ped&form=pedido_relatorios">Relatórios</a></li>

ALTER TABLE `FAT_COND_PGTO` 
ADD COLUMN `BLOQUEADO` CHAR(1) NOT NULL DEFAULT 'A';

*** OBRA *****
CREATE TABLE `FIN_CLIENTE_OBRA` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `CNO` varchar(20) DEFAULT '0',
  `PROJETO` varchar(90) DEFAULT '0',
  `RESPONSAVEL_TECNICO` int DEFAULT '0',
  `CREA` varchar(20) DEFAULT NULL,
  `ART` varchar(20) DEFAULT NULL,
  `CLIENTE` int NOT NULL,
  `STATUS` char(1) NOT NULL DEFAULT 'A' COMMENT 'A-Ativo, I-Inativo',
  `USERINSERT` int DEFAULT NULL,
  `DATEINSERT` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `USERCHANGE` int DEFAULT NULL,
  `DATECHANGE` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;


**** OBRA ****
ALTER TABLE FAT_PEDIDO 
ADD COLUMN OBRA_ID INT DEFAULT NULL AFTER OS;

**** PEDIDO ****
ALTER TABLE FAT_PARAMETRO ADD CASASDECIMAIS INT DEFAULT 4 NOT NULL;


############# pedido servico 21/07/2025 Joshua ###########
ALTER TABLE FAT_PEDIDO_SERVICO
ADD COLUMN OBSSERVICO TEXT AFTER DESCSERVICO;

-- Alterar o campo UNIDADE para suportar mais caracteres e UTF-8 completo
ALTER TABLE `FAT_PEDIDO_SERVICO` 
MODIFY COLUMN `UNIDADE` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- Alterar a tabela para usar UTF-8 completo
ALTER TABLE `FAT_PEDIDO_SERVICO` 
CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

**** gerencia  nf ****
#### PAGAMENTOS A VISTA nDup ####
verificar se o cliente usa 0 ou 1 como a vista.

*** nf *****
APÓS OS PASSOS ABAIXO BATEU NO ASSINCRONO EM HOMOLOGACAO.

1- desativar o sql mode para poder alterar as colunas:
$ SET @@SESSION.sql_mode = REPLACE(@@SESSION.sql_mode, 'STRICT_TRANS_TABLES', '');

2- Executar:
$ ALTER TABLE EST_NOTA_FISCAL MODIFY COLUMN DATASAIDAENTRADA timestamp DEFAULT '2020-01-01 01:00:00' NULL;
$ ALTER TABLE EST_NOTA_FISCAL MODIFY COLUMN DATACONFERENCIA timestamp DEFAULT '2020-01-01 01:00:00' NULL;

2- Incluir a coluna:
$ ALTER TABLE EST_NOTA_FISCAL 
ADD VENDAPRESENCIAL CHAR(1) DEFAULT 'N';

4- Voltar o padrao:
$ SET @@SESSION.sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

incluir no banco se não tiver.
ALTER TABLE EST_NAT_OP_TRIBUTO 
ADD `PRODUTO` varchar(25) DEFAULT NULL AFTER CEST;


########### Financeiro ############
remover o boletos do menu open.


########### Estoque ###############
rever os menus do estoque

**** GRUPO ****
ALTER TABLE EST_GRUPO 
ADD COMISSAOVENDAS DECIMAL(5,2) NULL,
ADD USERINSERT INT DEFAULT NULL,
ADD DATEINSERT TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
ADD USERCHANGE INT DEFAULT NULL,
ADD DATECHANGE TIMESTAMP NULL DEFAULT NULL;


ALTER TABLE EST_GRUPO MODIFY COLUMN GRUPO INT auto_increment NOT NULL;


########### CRM ##################
<li><a href="index.php?mod=crm&form=rel_contas">Relatórios</a></li>


***ENDERECO ENTREGA *****
CREATE TABLE `FIN_CLIENTE_ENDERECO` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `CLIENTE` int NOT NULL,
  `DESCRICAO` varchar(35) NOT NULL,
  `TIPOEND` varchar(15) DEFAULT 'RUA',
  `TITULOEND` varchar(15) DEFAULT NULL,
  `ENDERECO` varchar(60) DEFAULT 'SEM ENDERECO',
  `NUMERO` varchar(7) DEFAULT 'S/N',
  `COMPLEMENTO` varchar(15) DEFAULT NULL,
  `BAIRRO` varchar(20) DEFAULT NULL,
  `CIDADE` varchar(40) DEFAULT 'SEM CIDADE',
  `UF` varchar(2) DEFAULT 'PR',
  `CEP` int DEFAULT NULL,
  `HORARIO` time DEFAULT NULL,
  `FONEAREA` varchar(4) DEFAULT NULL,
  `FONE` varchar(9) DEFAULT NULL,
  `FONERAMAL` varchar(4) DEFAULT NULL,
  `FONECONTATO` varchar(15) DEFAULT NULL,
  `ENDENTREGAPADRAO` char(1) DEFAULT 'N',
  `ENDRETIRADAPADRAO` char(1) DEFAULT 'N',
  `PAIS` varchar(3) DEFAULT NULL,
  `STATUS` char(1) DEFAULT 'A',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1142 DEFAULT CHARSET=latin1;

responsavel tecnico - obra
INSERT INTO AMB_DDM
(ALIAS, NRCAMPO, CAMPO, TIPO, TAMANHO, DECIMAIS, FORMATO, REQUERIDO, PADRAO, LEGENDA, MASCARA, NOMEEXTERNO, ALTERACAO, DESCRICAO, `SQL`, MENSAGEM, MENU)
VALUES('AMB_MENU', 318, 'TIPOUSUARIO', 'R', 0, 0, NULL, 'N', 'R - RESPONSAVEL TECNICO', NULL, NULL, NULL, '2005-10-05', 'RESPONSAVEL TECNICO', NULL, NULL, NULL);





