<?php

/**
 * @package   astecv3
 * @name      p_remessa_bancaria
 * @version   3.0.00
 * @copyright 2018
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      05/02/2018
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
//include_once($dir."/../../class/fin/c_saldo.php");
include_once($dir . "/../../class/fin/c_conta_banco.php");
include_once($dir . "/../../class/fin/c_lancamento.php");


//Class P_REMESSA_BANCARIA
class p_remessa_bancaria extends c_lancamento
{

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_lanc = NULL;
    public $smarty = NULL;


    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function __construct()
    {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';
        $this->m_lanc = isset($parmPost['lanc']) ? $parmPost['lanc'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Remessa Bancaria");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5, 6  ]");
        $this->smarty->assign('disableSort', "[ 6 ]");
        $this->smarty->assign('numLine', "50");

        // include do javascript
        // include ADMjs . "/fin/s_fin.js";

    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
        switch ($this->m_submenu) {
            case 'gerar001':
                if ($this->verificaDireitoUsuario('FinCobrancaRemessa', 'S')) {
                    $this->remessaBancaria001('');
                }
                break;
            case 'gerar237':
                if ($this->verificaDireitoUsuario('FinCobrancaRemessa', 'S')) {
                    $this->remessaBancaria237('');
                }
                break;
            case 'gerar341':
                if ($this->verificaDireitoUsuario('FinCobrancaRemessa', 'S')) {
                    $this->remessaBancaria341('');
                }
                break;
            case 'gerar748':
                if ($this->verificaDireitoUsuario('FinCobrancaRemessa', 'S')) {
                    $this->remessaBancaria748('');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('FinCobrancaRemessa', 'C')) {
                    $this->mostraRemessa('');
                }
        }
    } // fim controle

    /**
     * <b> Funcao para remover os acentos da importacao. </b>
     * @name removeAcentos
     * @param STRING $string
     * @param BOOLEAN $slug FALSE
     * @return STRING
     */
    function removeAcentos($string, $slug = false)
    {
        $conversao = array(
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ï' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            "ö" => "o",
            'ú' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'ñ' => 'n',
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'É' => 'E',
            'Ê' => 'E',
            'Í' => 'I',
            'Ï' => 'I',
            "Ö" => "O",
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ú' => 'U',
            'Ü' => 'U',
            'Ç' => 'C',
            'Ñ' => 'N'
        );
        return strtr($string, $conversao);
    }

    function removerCaracteresEspeciais($string)
    {
        // Substitui todos os caracteres especiais por uma string vazia
        $stringSemEspeciais = preg_replace('/[^A-Za-z0-9]/', '', $string);

        return $stringSemEspeciais;
    }
    /**
     * @name downloadFile
     * @description download arquivo do servidor para maquina local
     * @param string $file - arquivo com o path incluso a ser feito download
     */
    function downloadFile($arquivo)
    { // $file = include path 
        if (isset($arquivo) && file_exists($arquivo)) { // faz o teste se a variavel não esta vazia e se o arquivo realmente existe
            switch (strtolower(substr(strrchr(basename($arquivo), "."), 1))) { // verifica a extensão do arquivo para pegar o tipo
                case "pdf":
                    $tipo = "application/pdf";
                    break;
                case "exe":
                    $tipo = "application/octet-stream";
                    break;
                case "zip":
                    $tipo = "application/zip";
                    break;
                case "doc":
                    $tipo = "application/msword";
                    break;
                case "xls":
                    $tipo = "application/vnd.ms-excel";
                    break;
                case "ppt":
                    $tipo = "application/vnd.ms-powerpoint";
                    break;
                case "gif":
                    $tipo = "image/gif";
                    break;
                case "png":
                    $tipo = "image/png";
                    break;
                case "jpg":
                    $tipo = "image/jpg";
                    break;
                case "mp3":
                    $tipo = "audio/mpeg";
                    break;
                case "php": // deixar vazio por seurança
                case "htm": // deixar vazio por seurança
                case "html": // deixar vazio por seurança
                case "REM": // deixar vazio por seurança
            }
            header("Content-Type: " . $tipo); // informa o tipo do arquivo ao navegador
            header("Content-Length: " . filesize($arquivo)); // informa o tamanho do arquivo ao navegador
            header("Content-Disposition: attachment; filename=" . basename($arquivo)); // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
            readfile($arquivo); // lê o arquivo
            exit; // aborta pós-ações
        }
    }

    /**
     * @name remessaBancaria001
     * @description gera arquivo de remessa para o banco correspondente com titulos em aberto do tipo boleto
     * @param int $banco - banco a ser gerado o arquivo de remessa 001 banco do brasil
     * @return int $count - numero de parcelas geradas
     */

    // public function remessaBancaria001($letra = NULL){

    //     try {
    //         $par = explode("|", $this->m_letra);
    //         $contaBanco = $par[2];
    //         $file_target = '';
    //         $ambiente = ".REM"; //TST
    //         $remessa = $this->selectRemessaBancaria($this->m_letra);
    //         $teste_array = is_array($remessa);

    //         if (isset($teste_array)){
    //             $objContaBanco = new c_contaBanco;

    //             // DADOS CONTA
    //             $objContaBanco->setId($contaBanco);
    //             $conta = $objContaBanco->select_ContaBanco();
    //             $banco = $conta[0]['BANCO'];
    //             $codEmpresa = $conta[0]['NUMNOBANCO'];
    //             $nomeEmpresa = $conta[0]['NOMECONTABANCO'];
    //             $codCarteira = str_pad($conta[0]['CARTEIRA'], 3, "0", STR_PAD_LEFT);
    //             $agencia = substr($conta[0]['AGENCIA'], 0,5);
    //             $char = array("-", "/", ".");
    //             $contaCorrente = substr(str_replace($char, "", $conta[0]['CONTACORRENTE']), 0,8);
    //             $multa = str_replace(".", "", $conta[0]['MULTA']);
    //             $juros = $conta[0]['JUROS'];
    //             $juros = ($conta[0]['JUROS']*$remessa[$i]['TOTAL'])/100;
    //             //$nossoNumero = $conta[0]['ULTIMONOSSONRO']; // atualizar conta
    //             $charValor = array(".");
    //             $descontoBonificacao = str_replace($charValor, "", $conta[0]['DESCONTOBONIFICACAO']);
    //             $condicaoEmissaoBoleto = $conta[0]['CONDICAOEMISSAOBOLETO'];
    //             $msg1 = $conta[0]['MSG1BOLETO'];
    //             $identificacaoOcorrencia = '01';

    //             // gera e grava o numero do arquivo de remessa
    //             $numRemessa = $objContaBanco->geraNumeroRemessa($contaBanco, $conta[0]['NUMREMESSA']); // atualizar conta
    //             $numRegistro  = 1;

    //             //Arquivo remessa
    //             $path = ADMraizCliente."/banco/".$banco."/remessa/".date("Y");
    //             $filename = "/CB".date("dm");
    //             $serieArq = 0;
    //             // teste se arquivo existe
    //             do {
    //                 $serieArq++;
    //                 $file_target = $path.$filename.str_pad($serieArq, 2, "0", STR_PAD_LEFT).$ambiente;
    //             } while (file_exists($file_target));

    //             // cria arquivo
    //             $wh = fopen($file_target, 'w+');
    //             if ( !$wh ) {
    //                 throw new Exception( "Erro ao gerar arquivo de remessa - ".$php_errormsg );
    //             }

    //             // registro header
    //             // Posicao  Nome Campo                Tam Conteudo
    //             //001 a 001 Identificação do Registro 001 0
    //             $headerWrite = "0";
    //             //002 a 002 Identificação do Arquivo Remessa 001 1
    //             $headerWrite .= "1";
    //             //003 a 009 Literal Remessa 007 REMESSA X
    //             $headerWrite .= "REMESSA";
    //             //010 a 011 Código de Serviço 002 01 X
    //             $headerWrite .= "01";
    //             ///012 a 026 Literal Serviço 015 COBRANCA X
    //             $headerWrite .= str_pad("COBRANCA", 15, " ", STR_PAD_RIGHT);
    //             //027 a 046 Código da Empresa 020
    //             $headerWrite .= str_pad($codEmpresa, 20, "0", STR_PAD_LEFT);
    //             //047 a 076 Nome da Empresa 030 Razão Social X
    //             $headerWrite .= str_pad($nomeEmpresa, 30, " ", STR_PAD_RIGHT);
    //             //077 a 079 Número do Bradesco na Câmara de Compensação 003 237 X
    //             $headerWrite .= "237";
    //             ///080 a 094 Nome do Banco por Extenso 015 Bradesco X
    //             $headerWrite .= str_pad('BRADESCO', 15, " ", STR_PAD_RIGHT);
    //             //095 a 100 Data da Gravação do Arquivo 006 DDMMAA Vide Obs. Pág.16  X
    //             $headerWrite .= date("dmy");
    //             //101 a 108 Branco 008 Branco X
    //             $headerWrite .= str_pad('', 8, " ", STR_PAD_RIGHT);
    //             //109 a 110 Identificação do sistema 002 MX Vide Obs. Pág.16 X
    //             $headerWrite .= "MX";
    //             //111 a 117 Nº Seqüencial de Remessa 007 Sequencial Vide Obs. Pág.16   X
    //             $headerWrite .= str_pad($numRemessa, 7, "0", STR_PAD_LEFT);
    //             //118 a 394 Branco 277 Branco X
    //             $headerWrite .= str_pad("", 277, " ", STR_PAD_RIGHT);
    //             //395 a 400 Nº Seqüencial do Registro de Um em  Um  006 000001 X        
    //             $headerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

    //             // Grava Header
    //             fwrite($wh, $headerWrite."\r\n");

    //             // registro tipo 1 - transacao
    //             for ($i=0; $i < count($remessa); $i++){
    //                 $numRegistro++;
    //                 $juros = ($conta[0]['JUROS']*$remessa[$i]['TOTAL'])/100;
    //                 $juros = number_format($juros , 2, '.', '');

    //                 $objContaBanco->setId($remessa[$i]['CONTA']);
    //                 $arrContaBanco = $objContaBanco->select_ContaBanco();
    //                 // verifica nosso numero, senão exister gera e grava em fin_conta
    //                 if (is_null($remessa[$i]['NOSSONUMERO'])):
    //                     $nossoNumero = $objContaBanco->geraNossoNumero($remessa[$i]['CONTA'], $arrContaBanco[0]['ULTIMONOSSONRO']);  // na impressão calcular e guardar no lancamento
    //                 else:
    //                     $nossoNumero = $remessa[$i]['NOSSONUMERO']; 
    //                 endif;
    //                 $nn = str_pad($codCarteira, 3, "0", STR_PAD_LEFT).str_pad($nossoNumero, 11, "0", STR_PAD_LEFT);
    //                 $digitoNN = c_contaBanco::mod11($codCarteira.str_pad($nossoNumero, 11, "0", STR_PAD_LEFT), 7);
    //                 // Posicao  Nome Campo                Tam Conteudo
    //                 //001 a 001 Identificação do Registro 001 1 X
    //                 $transacaoWrite = "7";
    //                 //002 a 003 9(002) Tipo de Inscrição do Beneficiário
    //                 if ($remessa[$i]['TIPOPESSOA'] == 'J'):
    //                     $transacaoWrite .= '02';
    //                 else:    
    //                     $transacaoWrite .= '01';
    //                 endif;
    //                 // 004 a 017 9(014) Número do CPF/CNPJ do Beneficiário
    //                 $transacaoWrite .= str_pad($remessa[$i]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
    //                 // 018 a 021 9(004) Prefixo da Agência 
    //                 //022 a 022 X(001) Dígito Verificador - D.V. - do Prefixo da Agência
    //                 $transacaoWrite .= "0".$codCarteira.str_pad($agencia, 5, "0", STR_PAD_LEFT).str_pad($contaCorrente, 8, "0", STR_PAD_LEFT);




    //                 //002 a 006 Agência de Débito (opcional) 005 Código da Agência do Pagador Exclusivo para Débito em Conta Vide Obs. Pág. 17
    //                 $transacaoWrite .= str_pad("", 5, "0", STR_PAD_RIGHT);
    //                 //007 a 007 Dígito da Agência de Débito (opcional) 001 Dígito da Agência do Pagador Vide  Obs.Pág. 17
    //                 $transacaoWrite .= " ";
    //                 //008 a 012 Razão da Conta Corrente (opcional) 005 Razão da Conta do Pagador Vide  Obs. Pág. 17
    //                 $transacaoWrite .= str_pad("", 5, "0", STR_PAD_RIGHT);
    //                 //013 a 019 Conta Corrente (opcional) 007 Número da Conta do Pagadora Vide  Obs. Pág. 17
    //                 $transacaoWrite .= str_pad("", 7, "0", STR_PAD_RIGHT);
    //                 //020 a 020 Dígito da Conta Corrente (opcional) 001 Dígito da Conta do Pagador Vide Obs. Pág. 17
    //                 $transacaoWrite .= " ";
    //                 //038 a 062 Nº Controle do Participante 025  Uso da Empresa Vide Obs. Pág. 17
    //                 $transacaoWrite .= str_pad($remessa[$i]['ID'], 25, " ", STR_PAD_RIGHT);
    //                 //063 a 065 Código do Banco a ser debitado na Câmara de Compensação 003 Nº do Banco “237”  Vide Obs. Pág.17
    //                 $transacaoWrite .= "000";
    //                 //066 a 066 Campo de Multa 001 Se = 2 considerar percentual de multa. Se = 0, sem multa. Vide Obs.Pág. 17
    //                 //067 a 070 Percentual de multa 004 Percentual de multa a ser considerado  vide Obs. Pág. 17
    //                 if ($multa > 0):
    //                     $transacaoWrite .= "2";
    //                     $transacaoWrite .= str_pad($multa, 4, "0", STR_PAD_LEFT);
    //                 else:    
    //                     $transacaoWrite .= "0";
    //                     $transacaoWrite .= "0000";
    //                 endif;

    //                 //071 a 081 Identificação do Título no Banco 11 Número Bancário para Cobrança Com e Sem Registro  Vide Obs. Pág. 17
    //                 $transacaoWrite .= str_pad($nossoNumero, 11, "0", STR_PAD_LEFT);
    //                 //082 a 082 Digito de Auto Conferencia do Número Bancário. 001 Digito N/N Vide Obs. Pág. 17 X
    //                 $transacaoWrite .= $digitoNN;
    //                 //083 a 092 Desconto Bonificação por dia 010 Valor do desconto bonif./dia. X
    //                 $transacaoWrite .= str_pad($descontoBonificacao, 10, "0", STR_PAD_LEFT);
    //                 //093 a 093 Condição para Emissão da Papeleta de Cobrança 001 - 1 = Banco emite e Processa o registro. 2 = Cliente emite e o Banco somente processa o registro – Vide obs. Pág. 19
    //                 $transacaoWrite .= $condicaoEmissaoBoleto;
    //                 //094 a 094 Ident. se emite Boleto para Débito Automático 001 
    //                 //N= Não registra na cobrança.
    //                 //Diferente de N registra e emite Boleto.  Vide Obs. Pág. 19
    //                 $transacaoWrite .= 'N';
    //                 //095 a 104 Identificação da Operação do Banco 010 Brancos X
    //                 $transacaoWrite .= str_pad("", 10, " ", STR_PAD_LEFT);
    //                 //105 a 105 Indicador Rateio Crédito (opcional) 001 “R”Vide Obs. Pág. 19 X
    //                 $transacaoWrite .= " ";
    //                 //106 a 106 Endereçamento para Aviso do Débito Automático em Conta Corrente (opcional) 001 Vide Obs. Pág. 19 X  11/57
    //                 $transacaoWrite .= "2";
    //                 //107 a 108 Quantidade possíveis de pagamento 002 Vide Obs. Pág.20 X
    //                 $transacaoWrite .= "  ";
    //                 //109 a 110 Identificação da ocorrência 002 Códigos de ocorrência Vide Obs. Pág. 20 X
    //                 $transacaoWrite .= $identificacaoOcorrencia;
    //                 //111 a 120 Nº do Documento 010 Documento X
    //                 $transacaoWrite .= str_pad($remessa[$i]['DOCTO'].$remessa[$i]['PARCELA'], 10, "0", STR_PAD_LEFT);
    //                 //121 a 126 Data do Vencimento do Título 006 DDMMAA Vide Obs. Pág. 20 X
    //                 $transacaoWrite .= date('dmy', strtotime($remessa[$i]['VENCIMENTO']));
    //                 //127 a 139 Valor do Título 013 Valor do Título (preencher sem ponto e sem vírgula) X
    //                 $transacaoWrite .= str_pad(str_replace($charValor, "", $remessa[$i]['TOTAL']), 13, "0", STR_PAD_LEFT);
    //                 //140 a 142 Banco Encarregado da Cobrança 003 Preencher com zeros X
    //                 $transacaoWrite .= '000';
    //                 //143 a 147 Agência Depositária 005 Preencher com zeros X
    //                 $transacaoWrite .= '00000';
    //                 /*148 a 149 Espécie de Título 002
    //                 01-Duplicata
    //                 02-Nota Promissória
    //                 03-Nota de Seguro
    //                 04-Cobrança Seriada
    //                 05-Recibo
    //                 10-Letras de Câmbio
    //                 11-Nota de Débito
    //                 12-Duplicata de Serv.
    //                 31-Cartão de Crédito
    //                 32-Boleto de Proposta
    //                 99-Outros */
    //                 $transacaoWrite .= '01';

    //                 //150 a 150 Identificação 001 Sempre = N X
    //                 $transacaoWrite .= 'N';
    //                 //151 a 156 Data da emissão do Título 006 DDMMAA X
    //                 $transacaoWrite .= date('dmy', strtotime($remessa[$i]['EMISSAO']));
    //                 //157 a 158 1ª instrução 002 Vide Obs. Pág. 20 X
    //                 $transacaoWrite .= '00';
    //                 //159 a 160 2ª instrução 002 Vide Obs. Pág. 20 X
    //                 $transacaoWrite .= '00';
    //                 //161 a 173 Valor a ser cobrado por Dia de Atraso 013 Mora por Dia de Atraso Vide obs. Pág. 21 X
    //                 $transacaoWrite .= str_pad(str_replace($charValor, "", $conta[0]['JUROS']), 13, "0", STR_PAD_LEFT);
    //                 //174 a 179 Data Limite P/Concessão de Desconto 006 DDMMAA X
    //                 $transacaoWrite .= str_pad("", 6, "0", STR_PAD_LEFT);
    //                 //180 a 192 Valor do Desconto 013 Valor Desconto X
    //                 $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
    //                 //193 a 205 Valor do IOF 013 Valor do IOF – Vide Obs. Pág. 21 X
    //                 $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
    //                 //206 a 218 Valor do Abatimento a ser concedido ou  cancelado 013 Valor Abatimento X
    //                 $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
    //                 //235 a 274 Nome do Pagador 040 Nome do Pagador X
    //                 $nome = substr($this->removeAcentos($remessa[$i]['NOME']), 0, 40);
    //                 $nome = trim($nome);
    //                 $nome = str_pad($nome, 40, " ", STR_PAD_RIGHT);
    //                 $tamNome = strlen($nome);
    //                 $transacaoWrite .= $nome;
    //                 //275 a 314 Endereço Completo 040 Endereço do Pagador X
    //                 $endereco = substr($this->removeAcentos($remessa[$i]['ENDERECO'].",".$remessa[$i]['NUMERO']), 0, 40);
    //                 $endereco = trim($endereco);
    //                 $endereco = str_pad($endereco, 40, " ", STR_PAD_RIGHT);
    //                 $tamEnd = strlen($endereco);
    //                 $transacaoWrite .= $endereco;

    //                 //315 a 326 1ª Mensagem 012 Vide Obs. Pág. 22 X
    //                 $mensagem = str_pad(substr($conta[0]['MSG1BOLETO'], 0, 12), 12, " ", STR_PAD_RIGHT);
    //                 $tamMsg = strlen($mensagem);
    //                 //$transacaoWrite .= str_pad(substr($conta[0]['MSG1BOLETO'], 0, 12), 12, " ", STR_PAD_RIGHT);
    //                 $transacaoWrite .= str_pad("", 12, " ", STR_PAD_RIGHT);
    //                 //327 a 331 CEP 005 CEP Pagador X
    //                 $cep1 = $remessa[$i]['CEP'];
    //                 $cep = str_pad(substr($remessa[$i]['CEP'], 0, 5), 5, "0", STR_PAD_RIGHT);
    //                 $transacaoWrite .= str_pad(substr($remessa[$i]['CEP'], 0, 5), 5, "0", STR_PAD_RIGHT);
    //                 //332 a 334 Sufixo do CEP 003 Sufixo X
    //                 $cep2 = substr($remessa[$i]['CEP'], -3);
    //                 $transacaoWrite .= str_pad(substr($remessa[$i]['CEP'], -3), 3, "0", STR_PAD_RIGHT);
    //                 //335 a 394 Sacador/Avalista ou 2ª Mensagem 060 Decomposição Vide Obs. Pág. 22 X
    //                 $transacaoWrite .= str_pad("", 60, " ", STR_PAD_RIGHT);
    //                 //395 a 400 Nº Seqüencial do Registro 006 Nº Seqüencial do Registro X
    //                 $transacaoWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

    //                 // grava arquivo txt
    //                 fwrite($wh, $transacaoWrite."\r\n");

    //                 // atualiza fin_lancamento com nosso e numero e data do envio do arquivo de remessa
    //                 $this->atualizaRemessa($remessa[$i]['ID'], $nossoNumero, $numRemessa, date('Y-m-d'), $filename.str_pad($serieArq, 2, "0", STR_PAD_LEFT).$ambiente);

    //             } // for

    //             // grava trailler
    //             $numRegistro++;
    //             //001 a 001 Identificação Registro 001 9  X 
    //             $traillerWrite = "9";
    //             //002 a 394 Branco 393 Branco X  
    //             $traillerWrite .= str_pad("", 393, " ", STR_PAD_RIGHT);
    //             //395 a 400 Número Seqüencial de Registro 006 Nº Seqüencial do Último Registro  X 
    //             $traillerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

    //             fwrite($wh, $traillerWrite."\r\n");
    //         } // if
    //         else {
    //            return 'Não existe boletos para enviar remessa bancária!!';
    //         }
    //         echo "Total Registros:-->".$numCartao;
    //         fclose($wh); // No error
    //         //$this->downloadFile($file_target);

    //     } catch (Exception $ex) {
    //         $this->mostraRemessa($ex);
    //     }
    //     $this->mostraRemessa($file_target, $banco);
    //     } //fim remessaBancaria001

    /**
     * @name remessaBancaria001
     * @description gera arquivo de remessa para o banco correspondente com titulos em aberto do tipo boleto
     * @param int $banco - banco a ser gerado o arquivo de remessa 001 banco do brasil
     * @return int $count - numero de parcelas geradas
     */

    public function remessaBancaria001($letra = NULL)
    {

        try {
            $par = explode("|", $this->m_letra);
            $contaBanco = $par[2];
            $file_target = '';
            $ambiente = ".REM"; //TST
            $remessa = $this->selectRemessaBancaria($this->m_letra);
            if (is_array($remessa) && count($remessa) > 0) {
                $objContaBanco = new c_contaBanco;

                // DADOS CONTA
                $objContaBanco->setId($contaBanco);
                $conta = $objContaBanco->select_ContaBanco();
                $banco = $conta[0]['BANCO'];
                $codEmpresa = $conta[0]['NUMNOBANCO'];
                $nomeEmpresa = $conta[0]['NOMECONTABANCO'];
                $codCarteira = str_pad($conta[0]['CARTEIRA'], 3, "0", STR_PAD_LEFT);
                $agencia = substr($conta[0]['AGENCIA'], 0, 5);
                $char = array("-", "/", ".");
                $contaCorrente = substr(str_replace($char, "", $conta[0]['CONTACORRENTE']), 0, 8);
                $multa = str_replace(".", "", $conta[0]['MULTA']);
                $juros = $conta[0]['JUROS'];
                $valorBaseJuros = (float) ($remessa[$i]['ORIGINAL'] ?? $remessa[$i]['TOTAL'] ?? 0);
                $juros = ($conta[0]['JUROS'] * $valorBaseJuros) / 100 / 30;
                $nossoNumero = $conta[0]['ULTIMONOSSONRO']; // atualizar conta
                $charValor = array(".");
                $descontoBonificacao = str_replace($charValor, "", $conta[0]['DESCONTOBONIFICACAO']);
                $condicaoEmissaoBoleto = $conta[0]['CONDICAOEMISSAOBOLETO'];
                $msg1 = $conta[0]['MSG1BOLETO'];
                $identificacaoOcorrencia = '01';

                // gera e grava o numero do arquivo de remessa
                $numRemessa = $objContaBanco->geraNumeroRemessa($contaBanco, $conta[0]['NUMREMESSA']); // atualizar conta
                $numRegistro  = 1;

                //Arquivo remessa
                $path = ADMraizCliente . "/banco/" . $banco . "/remessa/" . date("Y");
                $filename = "/CB" . date("dm");
                $serieArq = 0;
                // teste se arquivo existe
                do {
                    $serieArq++;
                    $file_target = $path . $filename . str_pad($serieArq, 2, "0", STR_PAD_LEFT) . $ambiente;
                } while (file_exists($file_target));

                // Verifica e cria diretório se necessário
                $directory = dirname($file_target);
                if (!is_dir($directory)) {
                    if (!mkdir($directory, 0777, true)) {
                        throw new Exception("Falha ao criar diretório: $directory");
                    }
                }

                // Verifica permissões de escrita
                if (!is_writable($directory)) {
                    throw new Exception("Diretório sem permissão de escrita: $directory");
                }

                // Tenta criar o arquivo com tratamento moderno de erros
                $wh = fopen($file_target, 'w+');
                if (!$wh) {
                    $error = error_get_last();
                    throw new Exception("Erro ao gerar arquivo de remessa: " . $error['message']);
                }


                // registro header
                // Posicao  Nome Campo                Tam Conteudo
                //001 a 001 Identificação do Registro Header 9(001) 0
                $headerWrite = "0";
                //002 a 002 Tipo de Operação 9(001) 1
                $headerWrite .= "1";
                //003 a 009 Identificação por Extenso do Tipo de Operação X(007) "REMESSA" 
                $headerWrite .= "REMESSA";
                //010 a 011 Identificação do Tipo de Serviço 9(002) 01 
                $headerWrite .= "01";
                //012 a 019 Identificação por Extenso do Tipo de Serviço X(008) "COBRANCA"
                $headerWrite .= "COBRANCA";
                // 020 a 026 Complemento do Registro: “Brancos” X(007) 
                $headerWrite .= str_pad('', 7, " ", STR_PAD_LEFT);
                //027 a 030 Prefixo da Agência: Número da Agência onde está cadastrado o convênio líder do Beneficiário 9(004)     **DUVIDA
                //031 a 031 Dígito Verificador - D.V. - do Prefixo da Agência X(001)
                $headerWrite .= str_pad($agencia, 5, "0", STR_PAD_LEFT);
                //032 a 039 Número da Conta Corrente: Número da conta onde está cadastrado o Convênio Líder do Beneficiário 9(008)
                //040 a 040 Dígito Verificador - D.V. – do Número da Conta Corrente do Beneficiário X(001)
                $headerWrite .= str_pad($contaCorrente, 9, "0", STR_PAD_LEFT);
                //041 a 046 Complemento do Registro 9(006) “000000” 
                $headerWrite .= "000000";
                //047 a 076 Nome do Beneficiário X(030)
                $headerWrite .= str_pad($nomeEmpresa, 30, " ", STR_PAD_RIGHT);
                //077 a 094 001BANCODOBRASIL X(018)
                $headerWrite .= str_pad('001BANCODOBRASIL', 18, " ", STR_PAD_RIGHT);
                //095 a 100 Data da Gravação: Informe no formato “DDMMAA” 9(006)
                $headerWrite .= date("dmy");
                //101 a 107 Seqüencial da Remessa 9(007)
                $headerWrite .= str_pad($numRemessa, 7, "0", STR_PAD_LEFT);
                //108 a 129 Complemento do Registro: “Brancos” X(22)
                $headerWrite .= str_pad("", 22, " ", STR_PAD_LEFT);
                //130 a 136 Número do Convênio Líder (numeração acima de 1.000.000 um milhão) 9(007)
                $headerWrite .= str_pad($codEmpresa, 7, "0", STR_PAD_LEFT);
                //137 a 394 Complemento do Registro: “Brancos” X(258)
                $headerWrite .= str_pad("", 258, " ", STR_PAD_LEFT);
                //395 a 400 Seqüencial do Registro 9(006) ”000001”
                $headerWrite .= "000001";
                // Grava Header
                fwrite($wh, $headerWrite . "\r\n");

                // registro tipo 1 - transacao
                for ($i = 0; $i < count($remessa); $i++) {
                    $numRegistro++;
                    $valorBaseJuros = (float) ($remessa[$i]['ORIGINAL'] ?? $remessa[$i]['TOTAL'] ?? 0);
                    $juros = ($conta[0]['JUROS'] * $valorBaseJuros) / 100 / 30;
                    $juros = number_format($juros, 2, '.', '');

                    $objContaBanco->setId($remessa[$i]['CONTA']);
                    $arrContaBanco = $objContaBanco->select_ContaBanco();
                    // verifica nosso numero, senão exister gera e grava em fin_conta
                    if (is_null($remessa[$i]['NOSSONUMERO'])):
                        $nossoNumero = $objContaBanco->geraNossoNumero($remessa[$i]['CONTA'], $arrContaBanco[0]['ULTIMONOSSONRO']);  // na impressão calcular e guardar no lancamento
                    else:
                        $nossoNumero = $remessa[$i]['NOSSONUMERO'];
                    endif;
                    $nn = str_pad($codCarteira, 3, "0", STR_PAD_LEFT) . str_pad($nossoNumero, 11, "0", STR_PAD_LEFT);
                    $digitoNN = c_contaBanco::mod11($codCarteira . str_pad($nossoNumero, 11, "0", STR_PAD_LEFT), 7);

                    // busca emitente
                    $emitente = new c_banco;
                    $emitente->setTab('AMB_EMPRESA');
                    $arrEmitente = $emitente->getRecord('empresa=' . $this->m_empresaid);
                    $emitente->close_connection();

                    $objContaBanco = new c_contaBanco;

                    // Posicao  Nome Campo                Tam Conteudo
                    //001 a 001 Identificação do Registro 001 9(001) "7"
                    $transacaoWrite = "7";
                    //002 a 003 Tipo de Inscrição do Beneficiário 9(002)
                    if ($remessa[$i]['TIPOPESSOA'] == 'J'):
                        $transacaoWrite .= '02';
                    else:
                        $transacaoWrite .= '01';
                    endif;
                    // 004 a 017 Número do CPF/CNPJ do Beneficiário 9(014)
                    //004 a 017: Preencher com o número de inscrição correto para o convênio:  09656196000141 
                    $transacaoWrite .= str_pad(
                        str_replace(
                            "/",
                            "",
                            str_replace(
                                "-",
                                "",
                                str_replace(".", "", $arrEmitente[0]['CNPJ'])
                            )
                        ),
                        14,
                        "0",
                        STR_PAD_LEFT
                    );
                    //018 a 021 Prefixo da Agência 9(004)
                    //022 a 022 Dígito Verificador - D.V. - do Prefixo da Agência X(001)
                    $transacaoWrite .= str_pad($agencia, 5, "0", STR_PAD_LEFT);
                    //023 a 030 Número da Conta Corrente do Beneficiário 9(008)
                    //031 a 031 Dígito Verificador - D.V. - do Número da Conta Corrente do Beneficiário X(001)
                    $transacaoWrite .= str_pad($contaCorrente, 9, "0", STR_PAD_LEFT);
                    //032 a 038 Número do Convênio de Cobrança do Beneficiário 9(007)
                    $transacaoWrite .= str_pad($codEmpresa, 7, "0", STR_PAD_LEFT);
                    //039 a 063 Código de Controle da Empresa X(025)
                    $transacaoWrite .= str_pad($remessa[$i]['ID'], 25, " ", STR_PAD_RIGHT);
                    //064 a 080 Nosso-Número 9(017)
                    $transacaoWrite .= str_pad($codEmpresa, 17, "0", STR_PAD_RIGHT);
                    //081 a 082 Número da Prestação 9(002) “00”
                    $transacaoWrite .= "00";
                    //083 a 084 Grupo de Valor 9(002) “00”
                    $transacaoWrite .= "00";
                    //085 a 087: Preencher com brancos X(003)
                    $transacaoWrite .= str_pad("", 3, " ", STR_PAD_LEFT);
                    //088 a 088 Indicativo de Mensagem ou Sacador/Avalista X(001)
                    $transacaoWrite .= str_pad("", 1, " ", STR_PAD_LEFT);
                    //089 a 091 Prefixo do Título X(003) “Brancos”
                    $transacaoWrite .= str_pad("", 3, " ", STR_PAD_LEFT);
                    //092 a 094 Variação da Carteira 9(003)
                    $transacaoWrite .= str_pad($codCarteira, 3, "0", STR_PAD_LEFT);
                    //095 a 095 Conta Caução 9(001) “0”
                    $transacaoWrite .= "0";
                    //096 a 101 Número do Borderô 9(006) “000000”
                    $transacaoWrite .= "000000";
                    /*102 a 106 Tipo de Cobrança X(005) 
                -04DSC: Solicitação de registro na Modalidade Descontada
                -08VDR:Solicitação de registro na Modalidade BBVendor
                -02VIN:solicitação de registro na Modalidade Vinculada
                -BRANCOS: Registro na Modalidade Simples*/
                    $transacaoWrite .= str_pad("", 5, " ", STR_PAD_LEFT);
                    /*107 a 108 Carteira de Cobrança 9(002)
                11-Cobrança Simples -p/ modalidade Descontada, Vinculada e Vendor somente se informado ocód correspondente conforme nota '24'
                17 -Cobrança Simples-p/ modalidade Descontada, Vinculada,Vendore Prêmio de Segurosomente se informado ocód.correspondente conforme nota '24'
                31-Cobrança Caucionada/Vinculada
                51-Cobrança Descontada*/
                    $transacaoWrite .= "17";
                    /*109 a 110 Comando 9(002)
                01 - Registro de títulos
                02 - Solicitação de baixa
                03 - Pedido de débito em conta
                04 - Concessão de abatimento
                05 - Cancelamento de abatimento
                06 - Alteração de vencimento de título
                07 - Alteração do número de controle do participante
                08 - Alteração do número do titulo dado pelo Beneficiário
                09 - Instrução para protestar (Nota 09)
                10 - Instrução para sustar protesto
                11 - Instrução para dispensar juros
                12 - Alteração de nome e endereço do Pagador
                16 – Alterar Juros de Mora (Vide Observações)
                31 - Conceder desconto
                32 - Não conceder desconto
                33 - Retificar dados da concessão de desconto
                34 - Alterar data para concessão de desconto
                35 - Cobrar multa (Nota 11)
                36 - Dispensar multa (Nota 11)
                37 - Dispensar indexador
                38 - Dispensar prazo limite de recebimento (Nota 11)
                39 - Alterar prazo limite de recebimento (Nota 11)
                40 - Alterar modalidade (Vide Observações)
                47 – Alteração de Valor Nominal do Boleto
                85 - Inclusão de Negativação Sem Protesto (campo “Seu número” diferencia a negativação para o
                mesmo pagador) (Nota 09)
                86 - Exclusão de Negativação Sem Protesto (Nota 09)
                */
                    $transacaoWrite .= '01';
                    //111 a 120 Seu Número/Número do Título Atribuído pelo Beneficiário X(010)
                    //Este campo nunca pode se repetir (Diferente de branco) 
                    //normalmente usado neste campo o número da nota fiscal gerada para o pagador.
                    $transacaoWrite .= str_pad($remessa[$i]['ID'], 10, " ", STR_PAD_RIGHT);
                    //121 a 126 Data de Vencimento 9(006) 
                    $transacaoWrite .= date('dmy', strtotime($remessa[$i]['VENCIMENTO']));
                    //127 a 139 Valor do Título 9(011) \ PDF caixa economica 9(013)
                    $transacaoWrite .= str_pad(str_replace($charValor, "", $remessa[$i]['TOTAL']), 13, "0", STR_PAD_LEFT);
                    //140 a 142 Número do Banco 9(003) “001”
                    $transacaoWrite .= '001';
                    //143 a 146 Prefixo da Agência Cobradora 9(004) “0000”
                    $transacaoWrite .= '0000';
                    //147 a 147 Dígito Verificador do Prefixo da Agência Cobradora X(001) “Brancos”
                    $transacaoWrite .= str_pad("", 1, " ", STR_PAD_LEFT);
                    /*148 a 149 Espécie de Titulo 9(002)
                01 -Duplicata Mercantil
                02 -Nota Promissória
                03 -Nota de Seguro
                05 –Recibo
                08 -Letra de Câmbio
                09 –Warrant
                10 –Cheque
                12 -Duplicata de Serviço
                13 -Nota de Débito
                15 -Apólice de Seguro
                25 -Dívida Ativa da União
                26 -Dívida Ativa de Estado 
                27 -Dívida Ativa de Município
                31 –Boleto de Cartão de Crédito
                32 –Boleto de Proposta33–Boleto de Aporte*/
                    $transacaoWrite .= '01';
                    /*150 a 150 Aceite do Título X(001)
                N -Sem aceite
                A-Com aceite-Indica o reconhecimento formal (assinatura no documento) do Pagadorno título.*/
                    $transacaoWrite .= 'A';
                    //151 a 156 Data de Emissão: Informe no formato “DDMMAA” 9(006)
                    $transacaoWrite .= date('dmy', strtotime($remessa[$i]['EMISSAO']));
                    //157 a 158 Instrução Codificada 9(002) *COMANDOS DE PROTESTO
                    $transacaoWrite .= '00';
                    //159 a 160 Instrução Codificada 9(002) *COMANDOS DE PROTESTO
                    $transacaoWrite .= str_pad("", 2, " ", STR_PAD_LEFT);
                    //161 a 173 Juros de Mora por Dia de Atraso 9(011)v99 \ PDF caixa economica 9(013)
                    $transacaoWrite .= str_pad(str_replace($charValor, "", $juros), 13, "0", STR_PAD_LEFT);

                    $dConcessaoDesconto = 0;
                    $vDesconto = 0;
                    $dataLimite = 0;
                    if ($descontoBonificacao > 0) { //verificar se tem desconto na conta
                        $dConcessaoDesconto = $remessa[$i]['VENCIMENTO'];
                        $dataLimite = date('dmy', strtotime($dConcessaoDesconto));
                        $vDesconto = $remessa[$i]['TOTAL'] * ($descontoBonificacao / 100);
                        $vDesconto = number_format($vDesconto, 2);
                    } else {
                        $dataLimite = str_pad("", 6, "0", STR_PAD_LEFT);
                    }

                    //174 a 179 Data Limite para Concessão de Desconto/Data de Operação do BBVendor/Juros de Mora. 9(006)
                    $transacaoWrite .= trim($dataLimite);
                    //180 a 192 Valor do Desconto 9(011)v99 \ PDF caixa economica 9(013)
                    $transacaoWrite .= str_pad(str_replace($charValor, "", $vDesconto), 13, "0", STR_PAD_LEFT);
                    //193 a 205 Valor do IOF 9(011)v99 \ PDF caixa economica 9(013) Informe 0,01 a 100,00 sem virgulas para o percentual de IOF a ser recolhido
                    $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
                    /*206 a 218 Valor do Abatimento 9(011)v99 \ PDF caixa economica 9(013)
                Exemplo:
                Valor do Título: R$ 100,00
                Valor do Abatimento: R$ 35,00
                O valor do título no Sistema do Banco será registrado como R$ 75,00 */
                    $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
                    //219 a 220 Tipo de Inscrição do Pagador 9(002)
                    if ($remessa[$i]['TIPOPESSOA'] == 'J'):
                        $transacaoWrite .= '02';
                    else:
                        $transacaoWrite .= '01';
                    endif;
                    //221 a 234 Número do CNPJ ou CPF do Pagador 9(014) 
                    $transacaoWrite .= str_pad($remessa[$i]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
                    //235 a 271 Nome do Pagador X(037)
                    $nome = substr($this->removeAcentos($remessa[$i]['NOME']), 0, 37);
                    $nome = trim($nome);
                    $nome = str_pad($nome, 37, " ", STR_PAD_RIGHT);
                    $tamNome = strlen($nome);
                    $transacaoWrite .= $nome;
                    //272 a 274 Complemento do Registro X(003) “Brancos”
                    $transacaoWrite .= str_pad("", 3, " ", STR_PAD_LEFT);
                    //275 a 314 Endereço do Pagador X(040) 
                    $endereco = substr($this->removeAcentos($remessa[$i]['ENDERECO']), 0, 40);
                    $endereco = trim($endereco);
                    $endereco = str_pad($endereco, 40, " ", STR_PAD_RIGHT);
                    $tamEnd = strlen($endereco);
                    $transacaoWrite .= $endereco;
                    //315 a 326 Bairro do Pagador X(012) 
                    $bairro = substr($this->removeAcentos($remessa[$i]['BAIRRO']), 0, 12);
                    $bairro = trim($bairro);
                    $bairro = str_pad($bairro, 12, " ", STR_PAD_RIGHT);
                    $transacaoWrite .= $bairro;
                    //327 a 334 CEP do Endereço do Pagador 9(008)  
                    $cep1 = $remessa[$i]['CEP'];
                    $cep = str_pad(substr($remessa[$i]['CEP'], 0, 8), 8, "0", STR_PAD_RIGHT);
                    $transacaoWrite .= str_pad($remessa[$i]['CEP'], 8, "0", STR_PAD_RIGHT);
                    //335 a 349 Cidade do Pagador X(015)
                    $cidade = substr($this->removeAcentos($remessa[$i]['CIDADE']), 0, 15);
                    $cidade = trim($cidade);
                    $cidade = str_pad($cidade, 15, " ", STR_PAD_RIGHT);
                    $transacaoWrite .= $cidade;
                    //350 a 351 UF da Cidade do Pagador X(002)
                    $transacaoWrite .= str_pad($remessa[$i]['UF'], 2, " ", STR_PAD_RIGHT);
                    //352 a 391 Observações/Mensagem ou Sacador/Avalista X(040) 
                    $mensagem = str_pad(substr($conta[0]['MSG1BOLETO'], 0, 40), 40, " ", STR_PAD_RIGHT);
                    $tamMsg = strlen($mensagem);
                    $transacaoWrite .= str_pad(substr($conta[0]['MSG1BOLETO'], 0, 40), 40, " ", STR_PAD_RIGHT);
                    /*392 a 393 Número de Dias Para Protesto ou Negativação
                10 – SERASA
                11 - QUOD*/
                    $transacaoWrite .= '10';
                    /*394 a 394  Indicador de Recebimento Parcial X(001)
                N – Não aceita recebimento parcial do boleto
                S – Aceita recebimento parcial do boleto
                Branco – Conforme cadastrado no sistema do Banco*/
                    $transacaoWrite .= " ";
                    //395 a 400 Seqüencial de Registro 9(006)
                    $transacaoWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

                    fwrite($wh, $transacaoWrite . "\r\n");

                    // atualiza fin_lancamento com nosso e numero e data do envio do arquivo de remessa
                    //$this->atualizaRemessa($remessa[$i]['ID'], $nossoNumero, $numRemessa, date('Y-m-d'), $filename.str_pad($serieArq, 2, "0", STR_PAD_LEFT).$ambiente);

                } //for

                // grava trailler
                $numRegistro++;
                //001 a 001 Identificação Registro 001 9  X 
                $traillerWrite = "9";
                //002 a 394 Branco 393 Branco X  
                $traillerWrite .= str_pad("", 393, " ", STR_PAD_RIGHT);
                //395 a 400 Número Seqüencial de Registro 006 Nº Seqüencial do Último Registro  X 
                $traillerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

                fwrite($wh, $traillerWrite . "\r\n");
            } // if
            else {
                return 'Não existe boletos para enviar remessa bancária!!';
            }
            echo "Total Registros:-->" . count($remessa);
            fclose($wh); // No error
            //$this->downloadFile($file_target);

        } catch (Exception $ex) {
            $this->mostraRemessa($ex);
        }
        $this->mostraRemessa($file_target, $banco);
    } //fim remessaBancaria001

    /**
     * @name remessaBancaria237
     * @description gera arquivo de remessa para o banco correspondente com titulos em aberto do tipo boleto
     * @param int $banco - banco a ser gerado o arquivo de remessa 237
     * @return int $count - numero de parcelas geradas
     */

     public function remessaBancaria237($letra = NULL){
        $status = null;
        try {
            $par = explode("|", $this->m_letra);
            $contaBanco = $par[2];
            $file_target = '';
            $ambiente = ".REM"; //TST
            $remessa = $this->selectRemessaBancaria($this->m_letra);
            if (is_array($remessa) && count($remessa) > 0) {
                
                //valida numero de documento e parcela
                for ($j=0; $j < count($remessa); $j++){
                    if ($remessa[$j]['DOCTO'] == "" || $remessa[$j]['DOCTO'] == null || $remessa[$j]['DOCTO'] == "0") {
                        throw new ErrorException("Número do documento não localizado - (".$remessa[$j]["NOME"].") ID:" . $remessa[$j]["ID"]);
                    }
                }
                
                $objContaBanco = new c_contaBanco;
        
                // DADOS CONTA
                $objContaBanco->setId($contaBanco);
                $conta = $objContaBanco->select_ContaBanco();
                $banco = $conta[0]['BANCO'];
                $codEmpresa = $conta[0]['NUMNOBANCO'];
                $nomeEmpresa = $conta[0]['NOMECONTABANCO'];
                $codCarteira = str_pad($conta[0]['CARTEIRA'], 3, "0", STR_PAD_LEFT);
                $agencia = substr($conta[0]['AGENCIA'], 0,5);
                $char = array("-", "/", ".");
                $contaCorrente = substr(str_replace($char, "", $conta[0]['CONTACORRENTE']), 0,8);
                $multa = str_replace(".", "", $conta[0]['MULTA']);
                $juros = $conta[0]['JUROS'];
                $valorBaseJuros = (float) ($remessa[$i]['ORIGINAL'] ?? $remessa[$i]['TOTAL'] ?? 0);
                $juros = ($conta[0]['JUROS'] * $valorBaseJuros) / 100 / 30;
                //$nossoNumero = $conta[0]['ULTIMONOSSONRO']; // atualizar conta
                $charValor = array(".");
                $descontoBonificacao = str_replace($charValor, "", $conta[0]['DESCONTOBONIFICACAO']);
                $condicaoEmissaoBoleto = $conta[0]['CONDICAOEMISSAOBOLETO'] ?? 1;
                $msg1 = $conta[0]['MSG1BOLETO'];
                $identificacaoOcorrencia = '01';
        
                // gera e grava o numero do arquivo de remessa
                $numRemessa = $objContaBanco->geraNumeroRemessa($contaBanco, $conta[0]['NUMREMESSA']); // atualizar conta
                $numRegistro  = 1;
            
                //Arquivo remessa
                $path = ADMraizCliente."/banco/".$banco."/remessa/".date("Y");
                
                // Verifica e cria pasta do ano se necessário
                $this->checkAndCreateYearFolder($banco);
                
                $filename = "/CB".date("dm");
                $serieArq = 0;
                // teste se arquivo existe
                do {
                    $serieArq++;
                    $file_target = $path.$filename.str_pad($serieArq, 2, "0", STR_PAD_LEFT).$ambiente;
                } while (file_exists($file_target));
        
                // cria arquivo
                $wh = fopen($file_target, 'w+');
                if ( !$wh ) {
                    throw new Exception( "Erro ao criar o arquivo de remessa, entre em contato com o suporte! ".$php_errormsg );
                }
        
                // registro header
                // Posicao  Nome Campo                Tam Conteudo
                //001 a 001 Identificação do Registro 001 0
                $headerWrite = "0";
                //002 a 002 Identificação do Arquivo Remessa 001 1
                $headerWrite .= "1";
                //003 a 009 Literal Remessa 007 REMESSA X
                $headerWrite .= "REMESSA";
                //010 a 011 Código de Serviço 002 01 X
                $headerWrite .= "01";
                ///012 a 026 Literal Serviço 015 COBRANCA X
                $headerWrite .= str_pad("COBRANCA", 15, " ", STR_PAD_RIGHT);
                //027 a 046 Código da Empresa 020
                $headerWrite .= str_pad($codEmpresa, 20, "0", STR_PAD_LEFT);
                //047 a 076 Nome da Empresa 030 Razão Social X
                $headerWrite .= str_pad($nomeEmpresa, 30, " ", STR_PAD_RIGHT);
                //077 a 079 Número do Bradesco na Câmara de Compensação 003 237 X
                $headerWrite .= "237";
                ///080 a 094 Nome do Banco por Extenso 015 Bradesco X
                $headerWrite .= str_pad('BRADESCO', 15, " ", STR_PAD_RIGHT);
                //095 a 100 Data da Gravação do Arquivo 006 DDMMAA Vide Obs. Pág.16  X
                $headerWrite .= date("dmy");
                //101 a 108 Branco 008 Branco X
                $headerWrite .= str_pad('', 8, " ", STR_PAD_RIGHT);
                //109 a 110 Identificação do sistema 002 MX Vide Obs. Pág.16 X
                $headerWrite .= "MX";
                //111 a 117 Nº Seqüencial de Remessa 007 Sequencial Vide Obs. Pág.16   X
                $headerWrite .= str_pad($numRemessa, 7, "0", STR_PAD_LEFT);
                //118 a 394 Branco 277 Branco X
                $headerWrite .= str_pad("", 277, " ", STR_PAD_RIGHT);
                //395 a 400 Nº Seqüencial do Registro de Um em  Um  006 000001 X        
                $headerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);
                
                fwrite($wh, $headerWrite."\r\n");
                
                // registro tipo 1 - transacao
                for ($i=0; $i < count($remessa); $i++){
                    $numRegistro++;
                    $valorBaseJuros = (float) ($remessa[$i]['ORIGINAL'] ?? $remessa[$i]['TOTAL'] ?? 0);
                    $juros = ($conta[0]['JUROS'] * $valorBaseJuros) / 100 / 30;
                    $juros = number_format($juros , 2, '.', '');
                    
                    $objContaBanco->setId($remessa[$i]['CONTA']);
                    $arrContaBanco = $objContaBanco->select_ContaBanco();
                    // verifica nosso numero, senão exister gera e grava em fin_conta
                    if (is_null($remessa[$i]['NOSSONUMERO'])):
                        $nossoNumero = $objContaBanco->geraNossoNumero($remessa[$i]['CONTA'], $arrContaBanco[0]['ULTIMONOSSONRO']);  // na impressão calcular e guardar no lancamento
                    else:
                        $nossoNumero = $remessa[$i]['NOSSONUMERO']; 
                    endif;
                    $nn = str_pad($codCarteira, 3, "0", STR_PAD_LEFT).str_pad($nossoNumero, 11, "0", STR_PAD_LEFT);
                    $digitoNN = c_contaBanco::mod11($codCarteira.str_pad($nossoNumero, 11, "0", STR_PAD_LEFT), 7);
                    // Posicao  Nome Campo                Tam Conteudo
                    //001 a 001 Identificação do Registro 001 1 X
                    $transacaoWrite = "1";
                    //002 a 006 Agência de Débito (opcional) 005 Código da Agência do Pagador Exclusivo para Débito em Conta Vide Obs. Pág. 17
                    $transacaoWrite .= str_pad("", 5, "0", STR_PAD_RIGHT);
                    //007 a 007 Dígito da Agência de Débito (opcional) 001 Dígito da Agência do Pagador Vide  Obs.Pág. 17
                    $transacaoWrite .= " ";
                    //008 a 012 Razão da Conta Corrente (opcional) 005 Razão da Conta do Pagador Vide  Obs. Pág. 17
                    $transacaoWrite .= str_pad("", 5, "0", STR_PAD_RIGHT);
                    //013 a 019 Conta Corrente (opcional) 007 Número da Conta do Pagadora Vide  Obs. Pág. 17
                    $transacaoWrite .= str_pad("", 7, "0", STR_PAD_RIGHT);
                    //020 a 020 Dígito da Conta Corrente (opcional) 001 Dígito da Conta do Pagador Vide Obs. Pág. 17
                    $transacaoWrite .= " ";
                    //021 a 037 Identificação da Empresa Beneficiária no Banco 017 Zero, Carteira, Agência e Conta - Corrente Vide Obs. Pág. 17 X
                    /*
                        021 a 037 - Identificações da Empresa Beneficiária no Banco
                        Deverá ser preenchido (da esquerda para a direita) da seguinte maneira:
                        21 a 21 - Zero.
                        22 a 24 - Códigos da Carteira.
                        25 a 29 - Códigos da Agência Beneficiários, sem o Dígito.
                        30 a 36 - Contas-Corrente.
                        37 a 37 - Dígitos da Conta

                    */
                    $transacaoWrite .= "0".$codCarteira.str_pad($agencia, 5, "0", STR_PAD_LEFT).str_pad($contaCorrente, 8, "0", STR_PAD_LEFT);
                    //038 a 062 Nº Controle do Participante 025  Uso da Empresa Vide Obs. Pág. 17
                    $transacaoWrite .= str_pad($remessa[$i]['ID'], 25, " ", STR_PAD_RIGHT);
                    //063 a 065 Código do Banco a ser debitado na Câmara de Compensação 003 Nº do Banco "237"  Vide Obs. Pág.17
                    /*
                        063 a 065 - Códigos do Banco para Débito - “237”
                        Deverá ser informado 237, caso o cliente beneficiário tenha optado pelo Débito Automático em conta
                        do pagador.
                        Para títulos em que não deve ser aplicado o Débito Automático, esse campo deverá ser preenchido
                        com zeros. 
                    */
                    $transacaoWrite .= "000";
                    //066 a 066 Campo de Multa 001 Se = 2 considerar percentual de multa. Se = 0, sem multa. Vide Obs.Pág. 17
                    //067 a 070 Percentual de multa 004 Percentual de multa a ser considerado  vide Obs. Pág. 17
                    if ($multa > 0){
                        $transacaoWrite .= "2";
                        $transacaoWrite .= str_pad($multa, 4, "0", STR_PAD_LEFT);
                    } else {    
                        $transacaoWrite .= "0";
                        $transacaoWrite .= "0000";
                    }
                   
                    //071 a 081 Identificação do Título no Banco 11 Número Bancário para Cobrança Com e Sem Registro  Vide Obs. Pág. 17
                    $transacaoWrite .= str_pad($nossoNumero, 11, "0", STR_PAD_LEFT);
                    //082 a 082 Digito de Auto Conferencia do Número Bancário. 001 Digito N/N Vide Obs. Pág. 17 X
                    $transacaoWrite .= $digitoNN;
                    //083 a 092 Desconto Bonificação por dia 010 Valor do desconto bonif./dia. X
                    $transacaoWrite .= str_pad($descontoBonificacao, 10, "0", STR_PAD_LEFT);
                    //093 a 093 Condição para Emissão da Papeleta de Cobrança 001 - 1 = Banco emite e Processa o registro. 2 = Cliente emite e o Banco somente processa o registro – Vide obs. Pág. 19
                    $transacaoWrite .= $condicaoEmissaoBoleto;
                    //094 a 094 Ident. se emite Boleto para Débito Automático 001 
                    //N= Não registra na cobrança.
                    //Diferente de N registra e emite Boleto.  Vide Obs. Pág. 19
                    $transacaoWrite .= 'N';
                    //095 a 104 Identificação da Operação do Banco 010 Brancos X
                    $transacaoWrite .= str_pad("", 10, " ", STR_PAD_LEFT);
                    //105 a 105 Indicador Rateio Crédito (opcional) 001 "RVide Obs. Pág. 19 X
                    $transacaoWrite .= " ";
                    //106 a 106 Endereçamento para Aviso do Débito Automático em Conta Corrente (opcional) 001 Vide Obs. Pág. 19 X  11/57
                    $transacaoWrite .= "2";
                    //107 a 108 Quantidade possíveis de pagamento 002 Vide Obs. Pág.20 X
                    $transacaoWrite .= "  ";
                    //109 a 110 Identificação da ocorrência 002 Códigos de ocorrência Vide Obs. Pág. 20 X
                    $transacaoWrite .= $identificacaoOcorrencia;
                    //111 a 120 Nº do Documento 010 Documento X
                    $transacaoWrite .= str_pad($remessa[$i]['DOCTO'].$remessa[$i]['PARCELA'], 10, "0", STR_PAD_LEFT);
                    //121 a 126 Data do Vencimento do Título 006 DDMMAA Vide Obs. Pág. 20 X
                    $transacaoWrite .= date('dmy', strtotime($remessa[$i]['VENCIMENTO']));
                    //127 a 139 Valor do Título 013 Valor do Título (preencher sem ponto e sem vírgula) X
                    $transacaoWrite .= str_pad(str_replace($charValor, "", $remessa[$i]['TOTAL']), 13, "0", STR_PAD_LEFT);
                    //140 a 142 Banco Encarregado da Cobrança 003 Preencher com zeros X
                    $transacaoWrite .= '000';
                    //143 a 147 Agência Depositária 005 Preencher com zeros X
                    $transacaoWrite .= '00000';
                    /*148 a 149 Espécie de Título 002
                    01-Duplicata
                    02-Nota Promissória
                    03-Nota de Seguro
                    04-Cobrança Seriada
                    05-Recibo
                    10-Letras de Câmbio
                    11-Nota de Débito
                    12-Duplicata de Serv.
                    31-Cartão de Crédito
                    32-Boleto de Proposta
                    99-Outros */
                    $transacaoWrite .= '01';
                    
                    //150 a 150 Identificação 001 Sempre = N X
                    $transacaoWrite .= 'N';
                    //151 a 156 Data da emissão do Título 006 DDMMAA X
                    $transacaoWrite .= date('dmy', strtotime($remessa[$i]['EMISSAO']));
                    //157 a 158 1ª instrução 002 Vide Obs. Pág. 20 X
                    $transacaoWrite .= '00';
                    //159 a 160 2ª instrução 002 Vide Obs. Pág. 20 X
                    $transacaoWrite .= '00';
                    //161 a 173 Valor a ser cobrado por Dia de Atraso 013 Mora por Dia de Atraso Vide obs. Pág. 21 X
                    $transacaoWrite .= str_pad(str_replace($charValor, "", $juros), 13, "0", STR_PAD_LEFT);
                    //174 a 179 Data Limite P/Concessão de Desconto 006 DDMMAA X
                    $transacaoWrite .= str_pad("", 6, "0", STR_PAD_LEFT);
                    //180 a 192 Valor do Desconto 013 Valor Desconto X
                    $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
                    //193 a 205 Valor do IOF 013 Valor do IOF – Vide Obs. Pág. 21 X
                    $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
                    //206 a 218 Valor do Abatimento a ser concedido ou  cancelado 013 Valor Abatimento X
                    $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
                    //219 a 220 Identificação do Tipo de Inscrição do Pagador 002 01-CPF 02-CNPJ X
                    //221 a 234 Nº Inscrição do Pagador 014  CNPJ/ CPF - Vide Obs. Pág. 21 (Preenchimento obrigatório)
                    if ($remessa[$i]['TIPOPESSOA'] == 'J'):
                        $transacaoWrite .= '02';
                    else:    
                        $transacaoWrite .= '01';
                    endif;
                    $transacaoWrite .= str_pad($remessa[$i]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
                    //235 a 274 Nome do Pagador 040 Nome do Pagador X
                    $nome = substr($this->removeAcentos($remessa[$i]['NOME']), 0, 40);
                    $nome = trim($nome);
                    $nome = str_pad($nome, 40, " ", STR_PAD_RIGHT);
                    $tamNome = strlen($nome);
                    $transacaoWrite .= $nome;
                    //275 a 314 Endereço Completo 040 Endereço do Pagador X
                    $endereco = substr($this->removeAcentos($remessa[$i]['ENDERECO']), 0, 40);
                    $endereco = trim($endereco);
                    $endereco = str_pad($endereco, 40, " ", STR_PAD_RIGHT);
                    $tamEnd = strlen($endereco);
                    $transacaoWrite .= $endereco;
        
                    //315 a 326 1ª Mensagem 012 Vide Obs. Pág. 22 X
                    $mensagem = str_pad(substr($conta[0]['MSG1BOLETO'], 0, 12), 12, " ", STR_PAD_RIGHT);
                    $tamMsg = strlen($mensagem);
                    //$transacaoWrite .= str_pad(substr($conta[0]['MSG1BOLETO'], 0, 12), 12, " ", STR_PAD_RIGHT);
                    $transacaoWrite .= str_pad("", 12, " ", STR_PAD_RIGHT);
                    //327 a 331 CEP 005 CEP Pagador X
                    $cep1 = $remessa[$i]['CEP'];
                    $cep = str_pad(substr($remessa[$i]['CEP'], 0, 5), 5, "0", STR_PAD_RIGHT);
                    $transacaoWrite .= str_pad(substr($remessa[$i]['CEP'], 0, 5), 5, "0", STR_PAD_RIGHT);
                    //332 a 334 Sufixo do CEP 003 Sufixo X
                    $cep2 = substr($remessa[$i]['CEP'], -3);
                    $transacaoWrite .= str_pad(substr($remessa[$i]['CEP'], -3), 3, "0", STR_PAD_RIGHT);
                    //335 a 394 Sacador/Avalista ou 2ª Mensagem 060 Decomposição Vide Obs. Pág. 22 X
                    $transacaoWrite .= str_pad("", 60, " ", STR_PAD_RIGHT);
                    //395 a 400 Nº Seqüencial do Registro 006 Nº Seqüencial do Registro X
                    $transacaoWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);
                    
                    // grava arquivo txt
                    fwrite($wh, $transacaoWrite."\r\n");
                    
                    // atualiza fin_lancamento com nosso e numero e data do envio do arquivo de remessa
                    $this->atualizaRemessa($remessa[$i]['ID'], $nossoNumero, $numRemessa, date('Y-m-d'), $filename.str_pad($serieArq, 2, "0", STR_PAD_LEFT).$ambiente);
                    
                } // for
                
                // grava trailler
                $numRegistro++;
                //001 a 001 Identificação Registro 001 9  X 
                $traillerWrite = "9";
                //002 a 394 Branco 393 Branco X  
                $traillerWrite .= str_pad("", 393, " ", STR_PAD_RIGHT);
                //395 a 400 Número Seqüencial de Registro 006 Nº Seqüencial do Último Registro  X 
                $traillerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);
        
                fwrite($wh, $traillerWrite."\r\n");
            } // if
            else {
               return 'Não existe boletos para enviar remessa bancária!!';
            }
            //echo "Total Registros:-->".$numCartao;
            fclose($wh); // No error
            //$this->downloadFile($file_target);
            
        } catch (Exception $ex) {
            $status = false;
            $this->mostraRemessa(null, null, $ex->getMessage(), false);
        }
    
        if($status !== false){
            $this->mostraRemessa($file_target, $banco);
        }
    } //fim remessaBancaria237

    /**
     * @name remessaBancaria341
     * @description gera arquivo de remessa para o banco correspondente com titulos em aberto do tipo boleto
     * @param int $banco - banco a ser gerado o arquivo de remessa 341
     * @return int $count - numero de parcelas geradas
     */

    public function remessaBancaria341($letra = NULL)
    {

        try {
            $par = explode("|", $this->m_letra);
            $contaBanco = $par[2];
            $file_target = '';
            $ambiente = ".REM"; //REM / TST
            $remessa = $this->selectRemessaBancaria($this->m_letra) ?? [];
            if (is_array($remessa) && count($remessa) > 0) {

                // busca emitente
                $emitente = new c_banco;
                $emitente->setTab('AMB_EMPRESA');
                $arrEmitente = $emitente->getRecord('empresa=' . $this->m_empresaid);
                $emitente->close_connection();

                $objContaBanco = new c_contaBanco;

                // DADOS CONTA
                $objContaBanco->setId($contaBanco);
                $conta = $objContaBanco->select_ContaBanco();
                $banco = $conta[0]['BANCO'];
                $codEmpresa = $conta[0]['NUMNOBANCO'];
                $nomeEmpresa = $conta[0]['NOMECONTABANCO'];
                $codCarteira = str_pad($conta[0]['CARTEIRA'], 3, "0", STR_PAD_LEFT);
                //         $codCarteira = '009';
                $agencia = substr($conta[0]['AGENCIA'], 0, 5);
                $char = array("-", "/", ".");
                $contaCorrente = substr(str_replace($char, "", $conta[0]['CONTACORRENTE']), 0, 8);
                $multa = str_replace(".", "", $conta[0]['MULTA']);
                $juros = $conta[0]['JUROS'];
                //$nossoNumero = $conta[0]['ULTIMONOSSONRO']; // atualizar conta
                $charValor = array(".");
                $descontoBonificacao = $conta[0]['DESCONTOBONIFICACAO'];
                $condicaoEmissaoBoleto = $conta[0]['CONDICAOEMISSAOBOLETO'];
                $msg1 = $conta[0]['MSG1BOLETO'];
                $identificacaoOcorrencia = '01';

                // gera e grava o numero do arquivo de remessa
                $numRemessa = $objContaBanco->geraNumeroRemessa($contaBanco, $conta[0]['NUMREMESSA']); // atualizar conta
                $numRegistro  = 1;

                //Arquivo remessa
                $path = ADMraizCliente . "/banco/" . $banco . "/remessa/" . date("Y");
                $filename = "/CB" . date("dm");
                $serieArq = 0;
                // teste se arquivo existe
                do {
                    $serieArq++;
                    $file_target = $path . $filename . str_pad($serieArq, 2, "0", STR_PAD_LEFT) . $ambiente;
                } while (file_exists($file_target));

                // Verifica e cria diretório se necessário
                $directory = dirname($file_target);
                if (!is_dir($directory)) {
                    if (!mkdir($directory, 0777, true)) {
                        throw new Exception("Falha ao criar diretório: $directory");
                    }
                }

                // Verifica permissões de escrita
                if (!is_writable($directory)) {
                    throw new Exception("Diretório sem permissão de escrita: $directory");
                }

                // Tenta criar o arquivo com tratamento moderno de erros
                $wh = fopen($file_target, 'w+');
                if (!$wh) {
                    $error = error_get_last();
                    throw new Exception("Erro ao gerar arquivo de remessa: " . $error['message']);
                }

                // registro header
                // Posicao  Nome Campo                Tam Conteudo
                //TIPO DE REGISTRO IDENTIFICAÇÃO DO REGISTRO HEADER 001 001 9(01) 0
                $headerWrite = "0";
                //OPERAÇÃO TIPO DE OPERAÇÃO - REMESSA 002 002 9(01) 1 
                $headerWrite .= "1";
                //LITERAL DE REMESSA IDENTIFICAÇÃO POR EXTENSO DO MOVIMENTO 003 009 X(07) REMESSA 
                $headerWrite .= "REMESSA";
                //CÓDIGO DO SERVIÇO IDENTIFICAÇÃO DO TIPO DE SERVIÇO 010 011 9(02) 01 
                $headerWrite .= "01";
                //LITERAL DE SERVIÇO IDENTIFICAÇÃO POR EXTENSO DO TIPO DE SERVIÇO 012 026 X(15) COBRANCA
                $headerWrite .= str_pad("COBRANCA", 15, " ", STR_PAD_RIGHT);
                //AGÊNCIA AGÊNCIA MANTENEDORA DA CONTA 027 030 9(04)
                //ZEROS COMPLEMENTO DE REGISTRO 031 032 9(02) 00
                //CONTA NÚMERO DA CONTA CORRENTE DA EMPRESA 033 037 9(05)
                //DAC DÍGITO DE AUTO CONFERÊNCIA AG/CONTA EMPRESA 038 038 9(01)
                $headerWrite .= str_pad($agencia, 4, "0", STR_PAD_LEFT) . '00' . str_pad($contaCorrente, 6, "0", STR_PAD_LEFT);
                //BRANCOS COMPLEMENTO DO REGISTRO 039 046 X(08) 
                $headerWrite .= str_pad('', 8, " ", STR_PAD_RIGHT);
                //NOME DA EMPRESA NOME POR EXTENSO DA "EMPRESA MÃE" 047 076 X(30) 
                $headerWrite .= str_pad($nomeEmpresa, 30, " ", STR_PAD_RIGHT);
                //CÓDIGO DO BANCO Nº DO BANCO NA CÂMARA DE COMPENSAÇÃO 077 079 9(03) 341 
                $headerWrite .= "341";
                //NOME DO BANCO NOME POR EXTENSO DO BANCO COBRADOR 080 094 X(15) BANCO ITAU SA
                $headerWrite .= str_pad('BANCO ITAU SA', 15, " ", STR_PAD_RIGHT);
                //DATA DE GERAÇÃO DATA DE GERAÇÃO DO ARQUIVO 095 100 9(06) DDMMAA
                $headerWrite .= date("dmy");
                //BRANCOS COMPLEMENTO DO REGISTRO 101 394 X(294)
                $headerWrite .= str_pad("", 294, " ", STR_PAD_RIGHT);
                //NÚMERO SEQÜENCIAL NÚMERO SEQÜENCIAL DO REGISTRO NO ARQUIVO 395 400 9(06) 000001 
                $headerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

                fwrite($wh, $headerWrite . "\r\n");
                // registro tipo 1 - transacao
                for ($i = 0; $i < count($remessa); $i++) {
                    $numRegistro++;
                    $valorBaseJuros = (float) ($remessa[$i]['ORIGINAL'] ?? $remessa[$i]['TOTAL'] ?? 0);
                    $juros = ($conta[0]['JUROS'] * $valorBaseJuros) / 100 / 30;
                    $juros = number_format($juros, 2, '.', '');

                    $objContaBanco->setId($remessa[$i]['CONTA']);
                    $arrContaBanco = $objContaBanco->select_ContaBanco();
                    // verifica nosso numero, senão exister gera e grava em fin_conta
                    if (is_null($remessa[$i]['NOSSONUMERO'])):
                        $nossoNumero = $objContaBanco->geraNossoNumero($remessa[$i]['CONTA'], $arrContaBanco[0]['ULTIMONOSSONRO']);  // na impressão calcular e guardar no lancamento
                    else:
                        $nossoNumero = $remessa[$i]['NOSSONUMERO'];
                    endif;
                    $nn = str_pad($codCarteira, 3, "0", STR_PAD_LEFT) . str_pad($nossoNumero, 11, "0", STR_PAD_LEFT);
                    $digitoNN = c_contaBanco::mod11($codCarteira . str_pad($nossoNumero, 11, "0", STR_PAD_LEFT), 7);
                    // Posicao  Nome Campo                Tam Conteudo
                    //TIPO DE REGISTRO IDENTIFICAÇÃO DO REGISTRO TRANSAÇÃO 001 001 9(01) 1
                    $transacaoWrite = "1";
                    // CÓDIGO DE INSCRIÇÃO TIPO DE INSCRIÇÃO DA EMPRESA 002 003 9(02) NOTA 1
                    $transacaoWrite .= '02';
                    // NÚMERO DE INSCRIÇÃO No DE INSCRIÇÃO DA EMPRESA (CPF/CNPJ) 004 017 9(14) NOTA 1
                    $transacaoWrite .= str_pad(
                        str_replace(
                            "/",
                            "",
                            str_replace(
                                "-",
                                "",
                                str_replace(".", "", $arrEmitente[0]['CNPJ'])
                            )
                        ),
                        14,
                        "0",
                        STR_PAD_LEFT
                    );
                    //AGÊNCIA AGÊNCIA MANTENEDORA DA CONTA 018 021 9(04)
                    //ZEROS COMPLEMENTO DE REGISTRO 022 023 9(02) “00”
                    //CONTA NÚMERO DA CONTA CORRENTE DA EMPRESA 024 028 9(05)
                    //DAC DÍGITO DE AUTO CONFERÊNCIA AG/CONTA EMPRESA 029 029 9(01)
                    $transacaoWrite .= str_pad($agencia, 4, "00", STR_PAD_LEFT) . '00' . str_pad($contaCorrente, 6, "0", STR_PAD_LEFT);
                    //BRANCOS COMPLEMENTO DE REGISTRO 030 033 X(04)
                    $transacaoWrite .= str_pad('', 4, " ", STR_PAD_RIGHT);
                    //INSTRUÇÃO/ALEGAÇÃO CÓD.INSTRUÇÃO/ALEGAÇÃO A SER CANCELADA 034 037 9(04) NOTA 27
                    $transacaoWrite .= '0000';
                    //USO DA EMPRESA IDENTIFICAÇÃO DO TÍTULO NA EMPRESA 038 062 X(25) NOTA 2 
                    $transacaoWrite .= str_pad($remessa[$i]['ID'], 25, " ", STR_PAD_RIGHT);
                    //NOSSO NÚMERO IDENTIFICAÇÃO DO TÍTULO NO BANCO 063 070 9(08) NOTA 3
                    $transacaoWrite .= str_pad($nossoNumero, 8, "0", STR_PAD_LEFT);
                    //QTDE DE MOEDA QUANTIDADE DE MOEDA VARIÁVEL 071 083 9(08)V9(5) NOTA 4 
                    $transacaoWrite .= str_pad("", 13, "0", STR_PAD_RIGHT);
                    //Nº DA CARTEIRA NÚMERO DA CARTEIRA NO BANCO 084 086 9(03) NOTA 5
                    $transacaoWrite .= $codCarteira;
                    //USO DO BANCO IDENTIFICAÇÃO DA OPERAÇÃO NO BANCO 087 107 X(21)
                    $transacaoWrite .= str_pad("", 21, " ", STR_PAD_LEFT);
                    //CARTEIRA CÓDIGO DA CARTEIRA 108 108 X(01) NOTA 5 
                    $transacaoWrite .= 'I';
                    // CÓD. DE OCORRÊNCIA IDENTIFICAÇÃO DA OCORRÊNCIA 109 110 9(02) NOTA 6 (ARQUIVO REMESSA)
                    $transacaoWrite .= $identificacaoOcorrencia;
                    // No DO DOCUMENTO No DO DOCUMENTO DE COBRANÇA (DUPL.,NP ETC.) 111 120 X(10) NOTA 18
                    $transacaoWrite .= str_pad($remessa[$i]['DOCTO'] . $remessa[$i]['PARCELA'], 10, "0", STR_PAD_LEFT);
                    // VENCIMENTO DATA DE VENCIMENTO DO TÍTULO 121 126 9(06) NOTA 7
                    $transacaoWrite .= date('dmy', strtotime($remessa[$i]['VENCIMENTO']));
                    // VALOR DO TÍTULO VALOR NOMINAL DO TÍTULO 127 139 9(11)V9(2) NOTA 8
                    $transacaoWrite .= str_pad(str_replace($charValor, "", $remessa[$i]['TOTAL']), 13, "0", STR_PAD_LEFT);
                    // CÓDIGO DO BANCO No DO BANCO NA CÂMARA DE COMPENSAÇÃO 140 142 9(03) 341
                    $transacaoWrite .= '341';
                    // AGÊNCIA COBRADORA AGÊNCIA ONDE O TÍTULO SERÁ COBRADO 143 147 9(05) NOTA 9
                    $transacaoWrite .= str_pad("", 5, "0", STR_PAD_RIGHT);
                    // ESPÉCIE ESPÉCIE DO TÍTULO 148 149 X(02) NOTA 10 - 01-Duplicata
                    $transacaoWrite .= '01';
                    // ACEITE IDENTIFICAÇÃO DE TÍTULO ACEITO OU NÃO ACEITO 150 150 X(01) A=ACEITE N=NÃO ACEITE
                    $transacaoWrite .= 'N';
                    // DATA DE EMISSÃO DATA DA EMISSÃO DO TÍTULO 151 156 9(06) NOTA 31
                    $transacaoWrite .= date('dmy', strtotime($remessa[$i]['EMISSAO']));
                    // INSTRUÇÃO 1 1a INSTRUÇÃO DE COBRANÇA 157 158 X(02) NOTA 11
                    $transacaoWrite .= '00';
                    // INSTRUÇÃO 2 2a INSTRUÇÃO DE COBRANÇA 159 160 X(02) NOTA 11
                    $transacaoWrite .= '00';
                    // JUROS DE 1 DIA VALOR DE MORA POR DIA DE ATRASO 161 173 9(11)V9(2) NOTA 12
                    $transacaoWrite .= str_pad(str_replace($charValor, "", $juros), 13, "0", STR_PAD_LEFT);

                    $dConcessaoDesconto = 0;
                    $vDesconto = 0;
                    if ($descontoBonificacao > 0) { //verificar se tem desconto na conta
                        $dConcessaoDesconto = $remessa[$i]['VENCIMENTO'];
                        $vDesconto = $remessa[$i]['TOTAL'] * ($descontoBonificacao / 100);
                        $vDesconto = number_format($vDesconto, 2);
                    }

                    // DESCONTO ATÉ DATA LIMITE PARA CONCESSÃO DE DESCONTO 174 179 9(06) DDMMAA
                    //$transacaoWrite .= str_pad("", 6, "0", STR_PAD_LEFT);
                    $transacaoWrite .= date('dmy', strtotime($dConcessaoDesconto));
                    // VALOR DO DESCONTO VALOR DO DESCONTO A SER CONCEDIDO 180 192 9(11)V9(2) NOTA 13
                    //$transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
                    $transacaoWrite .= str_pad(str_replace($charValor, "", $vDesconto), 13, "0", STR_PAD_LEFT);
                    // VALOR DO I.O.F. VALOR DO I.O.F. RECOLHIDO P/ NOTAS SEGURO 193 205 9(11)V((2) NOTA 14
                    $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
                    // ABATIMENTO VALOR DO ABATIMENTO A SER CONCEDIDO 206 218 9(11)V9(2) NOTA 13
                    $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
                    // CÓDIGO DE INSCRIÇÃO IDENTIFICAÇÃO DO TIPO DE INSCRIÇÃO/SACADO 219 220 9(02) 01=CPF 02=CNPJ
                    if ($remessa[$i]['TIPOPESSOA'] == 'J'):
                        $transacaoWrite .= '02';
                    else:
                        $transacaoWrite .= '01';
                    endif;
                    // NÚMERO DE INSCRIÇÃO No DE INSCRIÇÃO DA EMPRESA (CPF/CNPJ) 004 017 9(14) NOTA 1
                    $transacaoWrite .= str_pad(
                        str_replace(
                            "/",
                            "",
                            str_replace(
                                "-",
                                "",
                                str_replace(".", "", $remessa[$i]['CNPJCPF'])
                            )
                        ),
                        14,
                        "0",
                        STR_PAD_LEFT
                    );
                    // NOME NOME DO SACADO 235 264 X(30) NOTA 15
                    // BRANCOS COMPLEMENTO DE REGISTRO 265 274 X(10) NOTA 15
                    $nome = substr($this->removeAcentos($remessa[$i]['NOME']), 0, 40);
                    $nome = trim($nome);
                    $nome = str_pad($nome, 40, " ", STR_PAD_RIGHT);
                    $transacaoWrite .= $nome;
                    // LOGRADOURO RUA, NÚMERO E COMPLEMENTO DO SACADO 275 314 X(40)
                    $endereco = substr($this->removeAcentos($remessa[$i]['ENDERECO'] . "," . $remessa[$i]['NUMERO']), 0, 40);
                    $endereco = trim($endereco);
                    $endereco = str_pad($endereco, 40, " ", STR_PAD_RIGHT);
                    $transacaoWrite .= $endereco;
                    // BAIRRO BAIRRO DO SACADO 315 326 X(12)
                    $bairro = substr($this->removeAcentos($remessa[$i]['BAIRRO']), 0, 12);
                    $bairro = trim($bairro);
                    $bairro = str_pad($bairro, 12, " ", STR_PAD_RIGHT);
                    $transacaoWrite .= $bairro;
                    // CEP CEP DO SACADO 327 334 9(08)
                    $transacaoWrite .= str_pad($remessa[$i]['CEP'], 8, "0", STR_PAD_RIGHT);
                    // CIDADE CIDADE DO SACADO 335 349 X(15)
                    $cidade = substr($this->removeAcentos($remessa[$i]['CIDADE']), 0, 15);
                    $cidade = trim($cidade);
                    $cidade = str_pad($cidade, 15, " ", STR_PAD_RIGHT);
                    $transacaoWrite .= $cidade;
                    //  ESTADO UF DO SACADO 350 351 X(02)
                    $transacaoWrite .= str_pad($remessa[$i]['UF'], 2, " ", STR_PAD_RIGHT);
                    // SACADOR/AVALISTA NOME DO SACADOR OU AVALISTA 352 381 X(30) NOTA 16
                    $transacaoWrite .= str_pad("", 30, " ", STR_PAD_LEFT);
                    // BRANCOS COMPLEMENTO DO REGISTRO 382 385 X(04)
                    $transacaoWrite .= str_pad("", 4, " ", STR_PAD_LEFT);
                    // DATA DE MORA DATA DE MORA 386 391 9(06) DDMMAA
                    $transacaoWrite .= str_pad("", 6, " ", STR_PAD_LEFT);
                    // PRAZO QUANTIDADE DE DIAS 392 393 9(02) NOTA 11 (A)
                    // BRANCOS COMPLEMENTO DO REGISTRO 394 394 X(01)
                    $transacaoWrite .= str_pad("", 3, " ", STR_PAD_LEFT);;
                    // No SEQÜENCIAL DO REGISTRO NO ARQUIVO 395 400 9(06)
                    $transacaoWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

                    // grava arquivo txt
                    fwrite($wh, $transacaoWrite . "\r\n");

                    // atualiza fin_lancamento com nosso e numero e data do envio do arquivo de remessa
                    $this->atualizaRemessa($remessa[$i]['ID'], $nossoNumero, $numRemessa, date('Y-m-d'), $filename . str_pad($serieArq, 2, "0", STR_PAD_LEFT) . $ambiente);
                } // for

                // 3.1.1 - Registro mensagem FRENTE (Obrigatório)
                // $numRegistro++;
                // // CÓDIGO DO REGISTRO IDENTIFICAÇÃO DO REGISTRO MENSAGEM (FRENTE) 001 001 9(001) 7
                // $transacaoFrente = "7";

                //     //315 a 326 1ª Mensagem 012 Vide Obs. Pág. 22 X
                //     $mensagem = str_pad(substr($conta[0]['MSG1BOLETO'], 0, 12), 12, " ", STR_PAD_RIGHT);
                //     $tamMsg = strlen($mensagem);
                //     //$transacaoWrite .= str_pad(substr($conta[0]['MSG1BOLETO'], 0, 12), 12, " ", STR_PAD_RIGHT);
                //     $transacaoWrite .= str_pad("", 12, " ", STR_PAD_RIGHT);
                //     //335 a 394 Sacador/Avalista ou 2ª Mensagem 060 Decomposição Vide Obs. Pág. 22 X
                //     $transacaoWrite .= str_pad("", 60, " ", STR_PAD_RIGHT);



                // NÚMERO SEQUENCIAL NÚMERO SEQUENCIAL DO REGISTRO NO ARQUIVO 395 400 9(006)
                // $transacaoWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

                // grava arquivo txt
                // fwrite($wh, $transacaoWrite."\r\n");

                // grava trailler
                $numRegistro++;
                //001 a 001 Identificação Registro 001 9  X 
                $traillerWrite = "9";
                //002 a 394 Branco 393 Branco X  
                $traillerWrite .= str_pad("", 393, " ", STR_PAD_RIGHT);
                //395 a 400 Número Seqüencial de Registro 006 Nº Seqüencial do Último Registro  X 
                $traillerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);

                fwrite($wh, $traillerWrite . "\r\n");
            } // if
            else {
                return 'Não existe boletos para enviar remessa bancária!!';
            }
            fclose($wh); // No error
            //$this->downloadFile($file_target);

        } catch (Exception $ex) {
            $this->mostraRemessa($ex);
        }
        $this->mostraRemessa($file_target, $banco);
    } //fim remessaBancaria341


         
 /**
 * @name remessaBancaria748 - SICREDI
 * @description gera arquivo de remessa para o banco correspondente com titulos em aberto do tipo boleto
 * @param int $letra - parametros
 * @return int $count - numero de parcelas geradas
 */
public function remessaBancaria748($letra = NULL){
    $status = null;
    $dadosEmpresa = $this->busca_dadosEmpresaCC($this->m_empresacentrocusto);
    
    try {
        $par = explode("|", $this->m_letra);
        $contaBanco = $par[2];
        $file_target = '';
        $ambiente = ".REM";
        $remessa = $this->selectRemessaBancaria($this->m_letra);
        if (is_array($remessa) && count($remessa) > 0) {
            $objContaBanco = new c_contaBanco;
    
            // DADOS CONTA
            $objContaBanco->setId($contaBanco);
            $conta = $objContaBanco->select_ContaBanco();
            $banco = $conta[0]['BANCO'];
            $codEmpresa = $conta[0]['NUMNOBANCO'];
            $nomeEmpresa = $conta[0]['NOMECONTABANCO'];
            $codCarteira = str_pad($conta[0]['CARTEIRA'], 3, "0", STR_PAD_LEFT);
            $agencia = substr($conta[0]['AGENCIA'], 0,5);
            $posto = $conta[0]['POSTO'];
            $char = array("-", "/", ".");
            $contaCorrente = substr(str_replace($char, "", $conta[0]['CONTACORRENTE']), 0,8);
            $multa = str_replace(".", "", $conta[0]['MULTA']);
            $juros = $conta[0]['JUROS'];
            $charValor = array(".");
            $descontoBonificacao = str_replace($charValor, "", $conta[0]['DESCONTOBONIFICACAO']);
            $condicaoEmissaoBoleto = $conta[0]['CONDICAOEMISSAOBOLETO'];
            $msg1 = $conta[0]['MSGBLOQUETO'];
            $identificacaoOcorrencia = '01';

            //Arquivo remessa
            $path = ADMraizCliente."/banco/".$banco."/remessa/".date("Y");
            
            // Verifica e cria pasta do ano se necessário
            $this->checkAndCreateYearFolder($banco);

            // IMPLEMENTACAO QUE BUSCA O UMTIMO NUMERO NO DIRETORIO
            // COMENTADO POIS QUANDO NAO A REGISTRO NAO TEM TRATAMENTO
            // Fluxo para buscar o ultimo arquivo no diretorio
            // $latestFile = $this->getMostRecentFile($path);

            // if (file_exists($latestFile)) {
            //     // Lê todas as linhas do arquivo
            //     $lines = file($latestFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            //     // Verifica se o arquivo contém pelo menos uma linha
            //     if (!empty($lines)) {
            //         // Acessa a primeira linha
            //         $firstLine = $lines[0];
            
            //         // Extrai os caracteres da posição 111 até 117
            //         $latestNumberRemessa = intval(substr($firstLine, 110, 7));
            
            //     } else {
            //         throw new Exception("Última remessa encontrada não contém conteúdo, entre em contato com o suporte!");
            //     }
            // } else {
            //     throw new Exception("Erro ao localizar último número de remessa, entre em contato com o suporte!");
            // }
            
    
            // gera e grava o numero do arquivo de remessa
            $numRemessa = $objContaBanco->geraNumeroRemessa($contaBanco, $conta[0]['NUMREMESSA']); // atualizar conta
            //$numRemessa = $objContaBanco->geraNumeroRemessa($contaBanco, $latestNumberRemessa);
            $numRegistro  = 1;
        

            /* 
            A nomenclatura esperada do arquivo precisa ser CCCCCmdd.XXX onde:
            CCCCC = código do cedente (31480)
            m = mês (1 ao 9 = janeiro a setembro; O=Out, N=Nov e D=dez)
            dd = dia;
            */
            switch(date("m")){
                case "01":
                    $m = 1;
                break;
                case "02":
                    $m = 2;
                break;
                case "03":
                    $m = 3;
                break;
                case "04":
                    $m = 4;
                break;
                case "05":
                    $m = 5;
                break;
                case "06":
                    $m = 6;
                break;
                case "07":
                    $m = 7;
                break;
                case "08":
                    $m = 8;
                break;
                case "09":
                    $m = 9;
                break;
                case "10":
                    $m = "O";
                break;
                case "11":
                    $m = "N";
                break;
                case "12":
                    $m = "D";
                break;
            }

            $filename = "/".$codEmpresa.$m.date("d");
            $serieArq = 0;

            // teste se arquivo existe
            $file_target =  $path.$filename.$ambiente;
            if(file_exists($file_target)){
                throw new Exception( "Arquivo já existe, contate o suporte!".$php_errormsg ); //401 - Erro ao criar o arquivo
            }
    
            // cria arquivo
            $wh = fopen($file_target, 'w+');
            if ( !$wh ) {
                throw new Exception( "Erro ao criar o arquivo de remessa, entre em contato com o suporte!".$php_errormsg ); //401 - Erro ao criar o arquivo
            }
            

            // 9.1 Registro header
            // Posicao   Nome Campo                       Tam Conteudo
            // 001 a 001 Identificação do registro header 001 0 (zero)
            $headerWrite = 0;

            // 002 a 002 Identificação do Arquivo Remessa 001 1 (um)
            $headerWrite .= 1;

            // 003 a 009 Literal Remessa                  007 "REMESSA" 
            $headerWrite .= "REMESSA";

            // 010 a 011 Código do serviço de cobrança    002 O código de serviço de cobrança é "01"
            $headerWrite .= "01";

            // 012 a 019 Literal cobrança                 008 "COBRANCA"
            $headerWrite .= "COBRANCA";

             // 020 a 026 Filler                          007 Deixar em branco (sem preenchimento)
            $headerWrite .= str_pad('', 7, " ", STR_PAD_RIGHT);

            // 027 a 031 Código do beneficiário/cedente cadastrado na Cooperativa  005
            $headerWrite .= str_pad($codEmpresa, 5, "0", STR_PAD_LEFT);

            // 032 a 045 CPF/CGC do beneficiário          014 Informar CPF/CNPJ do beneficiário. Alinhado à direita e zeros à esquerda;
            $headerWrite .= str_pad($dadosEmpresa['0']['CNPJ'], 14, "0", STR_PAD_LEFT);

            // 046 a 076 Filler                           031 Deixar em Brancos (sem preenchimento)
            $headerWrite .= str_pad('', 31, " ", STR_PAD_RIGHT);

            // 077 a 079 Número do SICREDI                003 "748"
            $headerWrite .= "748";

            // 080 a 094 Literal Sicredi                  015 "SICREDI" 
            $headerWrite .= str_pad('SICREDI', 15, " ", STR_PAD_RIGHT);

            // 095 a 102 Data de gravação do arquivo      008 O Formato da data de geração do arquivo deve estar no padrão: AAAAMMDD
            $headerWrite .= date("Ymd");

            // 103 a 110 Filler                           008 Deixar em Branco (sem preenchimento)
            $headerWrite .= str_pad('', 8, " ", STR_PAD_RIGHT);

            // 111 a 117 Nº Número da remessa             007 Deve ser maior que zero: número do último arquivo remessa + 1;
            $headerWrite .= str_pad($numRemessa, 7, "0", STR_PAD_LEFT);

            // 118 a 390 Filler                           273 Deixar em Branco (sem preenchimento)
            $headerWrite .= str_pad("", 273, " ", STR_PAD_RIGHT);

            // 391 a 394 Versão do sistema                004 2.00 (o ponto deve ser colocado)  
            $headerWrite .= "2.00";
            
            // 395 a 400 Número sequencial do registro    006 Alinhado à direita e zeros à esquerda;   
            $headerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);
            
            fwrite($wh, $headerWrite."\r\n");

            #################### FIM Registro header ####################

            //9.2 Registro detalhe (Tipo 1) - Cobrança com registro
            for ($i=0; $i < count($remessa); $i++){
                $numRegistro++;
                //$juros = ($conta[0]['JUROS']*$remessa[$i]['TOTAL'])/100;
                $juros = number_format($juros , 2, '.', '');
                
                $objContaBanco->setId($remessa[$i]['CONTA']);
                $arrContaBanco = $objContaBanco->select_ContaBanco();

                // verifica nosso numero, senão exister gera e grava em fin_conta
                if (is_null($remessa[$i]['NOSSONUMERO'])):
                    $nossoNumero = $objContaBanco->geraNossoNumero($remessa[$i]['CONTA'], $arrContaBanco[0]['ULTIMONOSSONRO'], $banco, $agencia, $posto, $codEmpresa);  // na impressão calcular e guardar no lancamento
                else:
                    $nossoNumero = $remessa[$i]['NOSSONUMERO'];
                endif;

                //numero sequencia para gerar o DV do nosso numero
                //$numeroSequencial = intval(substr($remessa[$i]['NOSSONUMERO'], 4, 5));

                //$byte = substr($nossoNumero, 3, 1);
                //$ano = substr($nossoNumero, 0, 2);
                //$digitoNN = c_contaBanco::mod11($agencia.$posto.$codEmpresa.$ano.$byte.$numeroSequencial);

                // Posicao   Nome Campo                     Tam Conteudo
                // 001 a 001 Identificação do Registro      001 Identificação do registro detalhe de estar "1"
                $transacaoWrite = 1;

                // 002 a 002 Tipo de cobrança               001 "A" - Sicredi Com Registro
                $transacaoWrite .= "A";

                // 003 a 003 Tipo de carteira               001 "A" – Simples
                $transacaoWrite .= "A";

                // 004 a 004 Tipo de Impressão              001 "A" – Normal / "B" – Carnê
                $transacaoWrite .= "A";

                // 005 a 005 Filler                         012 Deixar em Branco (sem preenchimento)
                $transacaoWrite .= str_pad("", 1, " ", STR_PAD_RIGHT);

                //QUANDO FOR UTILZIAR QRCODE
                // 006 a 006 Tipo de Boleto                 001 H - Híbrido
                $transacaoWrite .= " ";

                // 007 a 016 Filler                         010 Deixar em Branco (sem preenchimento)
                $transacaoWrite .= str_pad("", 10, " ", STR_PAD_RIGHT);

                // 017 a 017 Tipo de moeda                  001 "A" – Real
                $transacaoWrite .= "A";

                // 018 a 018 Tipo de desconto               001 "A" – Valor / "B" – Percentual
                $transacaoWrite .= "B";

                // 019 a 019 Tipo de juros                  001 "A" – Valor / "B" – Percentual
                $transacaoWrite .= "B";

                // 020 a 047 Filler                         028 Deixar em Branco (sem preenchimento)
                $transacaoWrite .= str_pad("", 28, " ", STR_PAD_RIGHT);

                // 048 a 056 Nosso número Sicredi           009 18 - ano atual
                //                                              2 a 9 - byte de geração "somente será "1" se
                //                                              forem boletos pré-impressos". xxxxx - número
                //                                              sequencial d - dígito verificador calculado ou
                //                                              seja, a nomenclatura correta é: 182xxxxxD
                //$nossoNumeroEnd = date("y").'/'.$byte.str_pad($nossoNumero, 4, "0", STR_PAD_LEFT).$digitoNN;
                $nossoNumeroEnd = str_replace('/', '', $nossoNumero);
                $transacaoWrite .= str_pad($nossoNumeroEnd, 8, "0", STR_PAD_LEFT);
                //$transacaoWrite .= $digitoNN;

                // 057 a 062 Filler                         006 Deixar em Branco (sem preenchimento)
                $transacaoWrite .= str_pad("", 6, " ", STR_PAD_RIGHT);

                // 063 a 070 Data da Instrução              008 O Formato da data de instrução do arquivo deve estar no padrão: AAAAMMDD
                $transacaoWrite .= date('Ymd', strtotime($remessa[$i]['EMISSAO']));                

                /* 071 a 071 Campo alterado, quando instrução "31"  001 Campo deve estar vazio (sem preenchimento),
                                                                        só utilizar quando 109-110 for = 31. Usar as
                                                                        seguintes opções:
                                                                        A – Desconto;
                                                                        B - Juros por dia;
                                                                        C - Desconto por dia de antecipação;
                                                                        D - Data limite para concessão de
                                                                        desconto; 
                                                                        E - Cancelamento de protesto automático; F - Carteira de cobrança - não disponível.
                                                                        G - Cancelamento de negativação automática */
                $transacaoWrite .= str_pad("", 1, " ", STR_PAD_RIGHT);

                /* 072 a 072 Postagem do título             001 S - Impressão e postagem dos títulos serão feitas pelo
                                                                Sicredi
                                                                N - Postagem dos títulos será feita pelo
                                                                beneficiário/cedenteObs.: Se a impressão do título
                                                                (campo 074) for realizada pelo beneficiário/cedente, a
                                                                postagem também deverá ser realizada por ele */
                $transacaoWrite .= "N";

                // 073 a 073 Filler                         001 Deixar em Branco (sem preenchimento)
                $transacaoWrite .= str_pad("", 1, " ", STR_PAD_RIGHT);

                // 074 a 074 Emissão do boleto              001 "A" – Impressão é feita pelo Sicredi / "B" – Impressão é feita pelo Beneficiário
                $transacaoWrite .= "B";

                // 075 a 076 Número da parcela do carnê     002 Quando o tipo de impressão for "B – Carnê" (posição 004).
                $transacaoWrite .= "00";

                // 077 a 078 Número total de parcelas do carnê 002 Quando o tipo de impressão for "B – Carnê" (posição 004).
                $transacaoWrite .= "00";

                // 079 a 082 Filler                         004 Deixar em Branco (sem preenchimento)
                $transacaoWrite .= str_pad("", 4, " ", STR_PAD_RIGHT);

                // 083 a 092 Valor de desconto por dia de antecipação 010 Informar valor de desconto (alinhado à direita e zeros à esquerda) ou senão preencher com zeros.
                $transacaoWrite .= str_pad("", 10, "0", STR_PAD_RIGHT);

                
                // 093 a 096 % multa por pagamento em atraso 004 Alinhado à direita com zeros à esquerda, sem separador decimal ou preencher com zeros.
                if ($multa < 0){
                    $transacaoWrite .= str_pad($multa, 4, "0", STR_PAD_LEFT);
                }else{    
                    $transacaoWrite .= str_pad("", 4, "0", STR_PAD_RIGHT);
                }

                // 097 a 108 Filler                         012 Brancos (sem preenchimento)
                $transacaoWrite .= str_pad("", 12, " ", STR_PAD_RIGHT);

                // 109 a 110 Instrução                      002 Este campo só permite usar os seguintes códigos:
                //                                              01 - Cadastro de título;
                //                                              02 - Pedido de baixa;
                //                                              04 - Concessão de abatimento;
                //                                              05 - Cancelamento de abatimento concedido;
                //                                              06 - Alteração de vencimento;
                //                                              09 - Pedido de protesto;
                //                                              18 - Sustar protesto e baixar título;
                //                                              19 - Sustar protesto e manter em carteira;
                //                                              31 - Alteração de outros dados;
                //                                              45 - Incluir Negativação;
                //                                              75 - Excluir Negativação e Manter em Carteira;
                //                                              76 -Excluir Negativação e Baixar título.
                $transacaoWrite .= $identificacaoOcorrencia;

                // 111 a 120 Seu número                     010 Diferente de branco - normalmente usados neste campo o número da nota fiscal gerada para o pagador.
                $transacaoWrite .= str_pad($remessa[$i]['ID'], 10, "0", STR_PAD_LEFT);

                // 121 a 126 Data de vencimento             006 A data de vencimento deve ser sete dias MAIOR que o campo 151 a 156 "Data de emissão". Formato: DDMMAA
                $transacaoWrite .= date('dmy', strtotime($remessa[$i]['VENCIMENTO']));

                // 127 a 139 Valor do título                013 Alinhado à direita e zeros à esquerda;
                $transacaoWrite .= str_pad(str_replace($charValor, "", $remessa[$i]['TOTAL']), 13, "0", STR_PAD_LEFT);

                // 140 a 141 Filler                         002 Deixar em Branco (sem preenchimento)
                $transacaoWrite .= str_pad("", 2, " ", STR_PAD_RIGHT);

                // 142 a 148 Filler                         003 Deixar em Branco (sem preenchimento) - PDF PARECE ESTAR COM O TAMANHO ERRADO
                $transacaoWrite .= str_pad("", 7, " ", STR_PAD_RIGHT);

                // 149 a 149 Espécie de documento           001 Este campo só permite usar os seguintes códigos:
                //                                              A - Duplicata Mercantil por Indicação;
                //                                              B - Duplicata Rural;
                //                                              C - Nota Promissória;
                //                                              D - Nota Promissória Rural;
                //                                              E - Nota de Seguros;
                //                                              G - Recibo;
                //                                              H - Letra de Câmbio;
                //                                              I - Nota de Débito;
                //                                              J - Duplicata de Serviço por Indicação; 
                //                                              K – Outros.
                //                                              O – Boleto Proposta
                //                                              Obs.: Se título possuir protesto automático, favor
                //                                              utilizar o código A, pois esta é uma espécie de
                //                                              documento que permite utilizar o protesto
                //                                              automático sem a utilização de um Sacador
                //                                              Avalista.
                //                                              Obs.: O Boleto Proposta da liberdade ao pagador
                //                                              de aceitar, ou não, o produto ou serviço vinculado
                //                                              ao boleto em questão. Não sendo prejudicado
                //                                              pelo não pagamento do mesmo.
                $transacaoWrite .= 'A';

                // 150 a 150 Aceite do título                   001 "S" – Sim / "N" – Não
                $transacaoWrite .= 'S';

                // 151 a 156 Data de emissão                    006 A data de emissão deve ser sete dias MENOR que o campo 121 a 126 "Data de vencimento". Formato: DDMMAA
                $transacaoWrite .= date('dmy', strtotime($remessa[$i]['EMISSAO']));                

                // 157 a 158 Instrução de protesto automático    002 "00" - Não protestar automaticamente / "06" - Protestar automaticamente
                if($conta[0]['PROTESTO'] !== '' and $conta[0]['PROTESTO'] > 0){
                    $transacaoWrite .= "06";
                }else{
                    $transacaoWrite .= "00";
                }

                // 159 a 160 Número de dias p/protesto automático 002 Campo numérico - mínimo 03 (três) dias
                //                                                   Quando preenchido com 3 ou 4 dias o sistema
                //                                                   comandará protesto em dias úteis após o
                //                                                   vencimento. Quando preenchido acima de 4 dias,
                //                                                   o sistema comandará protesto em dias corridos
                //                                                   após o vencimento.
                if($conta[0]['PROTESTO'] !== '' and $conta[0]['PROTESTO'] > 0){
                    $transacaoWrite .= str_pad($conta[0]['PROTESTO'], 2, "0", STR_PAD_RIGHT);
                }else{
                    $transacaoWrite .= "00";
                }

                // 161 a 173 Valor/% de juros por dia de atraso  013 Preencher com valor (alinhados à direita com zeros à esquerda) ou preencher com zeros.
                $transacaoWrite .= str_pad(str_replace($charValor, "", $juros), 13, "0", STR_PAD_LEFT);

                // 174 a 179 Data limite p/concessão de desconto 006 Informar data no padrão: DDMMAA ou preencher com zeros.
                $transacaoWrite .= str_pad("", 6, "0", STR_PAD_RIGHT);

                // 180 a 192 Valor/% do desconto                 013 Informar valor do desconto (alinhado à direita e zeros à esquerda) ou preencher com zeros.
                $transacaoWrite .= str_pad($descontoBonificacao, 13, "0", STR_PAD_LEFT);

                // 193 a 194 Instrução de negativação automática 002 "00" - não negativar automaticamente / "06" – Negativar automaticamente
                $transacaoWrite .= "00";

                // 195 a 196 Número de dias p/ negativação automática 002 Campo numérico - mínimo 03 (três) dias.
                //                                                        Quando preenchido com 3 ou 4 dias o sistema
                //                                                        comandará negativação em dias úteis após o
                //                                                        vencimento. Quando preenchido acima de 4 dias,
                //                                                        o sistema comandará negativação em dias
                //                                                        corridos após o vencimento.
                $transacaoWrite .= "00";

                //197 a 205 Filler                              009 Sempre preencher com zeros neste campo.
                $transacaoWrite .= str_pad("", 9, "0", STR_PAD_LEFT);

                // 206 a 218 Valor do abatimento                013 Informar valor do abatimento (alinhado à direita e zeros à esquerda) ou preencher com zeros.
                $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);

                // 219 a 219 Tipo de pessoa do pagador: PF ou PJ 001 "1" - Pessoa Física / "2" - Pessoa Jurídica
                if ($remessa[$i]['TIPOPESSOA'] == 'J'){
                    $transacaoWrite .= '2';
                }else{    
                    $transacaoWrite .= '1';
                }

                // 220 a 220 Filter Sempre preencher com zeros neste campo.
                $transacaoWrite .= '0';

                // 221 a 234 CPF/CNPJ do Pagador        014 Alinhado à direita e zeros à esquerda; 
                $transacaoWrite .= str_pad($this->removerCaracteresEspeciais($remessa[$i]['CNPJCPF']), 14, "0", STR_PAD_LEFT);

                // 235 a 274 Nome do pagador            040 Neste campo informar o nome do pagador sem acentuação ou caracteres especiais.
                $nome = substr($this->removeAcentos($remessa[$i]['NOME']), 0, 40);
                $nome = trim($nome);
                $nome = str_pad($nome, 40, " ", STR_PAD_RIGHT);
                $tamNome = strlen($nome);
                $transacaoWrite .= $nome;

                // 275 a 314 Endereço do pagador        040 Neste campo informar o endereço do pagador sem acentuação ou caracteres especiais.
                $endereco = substr($this->removeAcentos($remessa[$i]['ENDERECO']), 0, 40);
                $endereco = trim($endereco);
                $endereco = str_pad($endereco, 40, " ", STR_PAD_RIGHT);
                $tamEnd = strlen($endereco);
                $transacaoWrite .= $endereco;

                // 315 a 319 Código do pagador na cooperativa beneficiário 005 Se pagador novo, o campo deve conter zeros.
                //                                                             Para pagador já cadastrado, enviar o código
                //                                                             enviado no primeiro arquivo de retorno ou
                //                                                             sempre zeros quando o sistema do beneficiário
                //                                                             não utiliza esse campo – campo alfanumérico;
                $transacaoWrite .= str_pad("", 5, "0", STR_PAD_LEFT);

                // 320 a 325 Filler                     006 Sempre preencher com zeros neste campo.
                $transacaoWrite .= str_pad("", 6, "0", STR_PAD_LEFT);

                // 326 a 326 Filler                     001 Deixar em Branco (sem preenchimento)
                $transacaoWrite .= " ";

                // 327 a 334 CEP do pagador             008 Obrigatório ser um CEP Válido
                $cep1 = $remessa[$i]['CEP'];
                $transacaoWrite .= str_pad($cep1, 8, " ", STR_PAD_RIGHT);

                // 335 a 339 Código do Pagador junto ao cliente 005  Para validações de arquivos deixar este
                //                                                   campo com zeros, após a homologação pode ser
                //                                                   usado o código do cliente, conforme informação
                //                                                   do campo
                //$transacaoWrite .= str_pad($remessa[$i]['DOCTO'].$remessa[$i]['PARCELA'], 10, "0", STR_PAD_LEFT);
                $transacaoWrite .= str_pad("", 5, "0", STR_PAD_LEFT);


                // 340 a 353 CPF/CNPJ do Sacador Avalista  014        Alinhado à direita e zeros à esquerda. Deixar em
                //                                                    branco caso não exista Sacador Avalista. O
                //                                                    Sacador Avalista deve ser diferente do
                //                                                    beneficiário e pagador.
                $transacaoWrite .= str_pad("", 14, " ", STR_PAD_LEFT);

                // 354 a 394 Nome do Sacador Avalista  041           Deixar em brancos quando inexistente. Caso
                //                                                   utilize usar sem acentuação ou caracteres
                //                                                   especiais.
                $transacaoWrite .= str_pad("", 41, " ", STR_PAD_LEFT);

                // 395 a 400 Número sequencial do registro   006  Neste campo sempre informar "000002" para
                //                                                  primeiro registro de cobrança. Alinhado à direita
                //                                                  e zeros à esquerda;  
                $transacaoWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);
                
                // grava arquivo txt
                fwrite($wh, $transacaoWrite."\r\n");

                // atualiza fin_lancamento com nosso e numero e data do envio do arquivo de remessa
                $this->atualizaRemessa($remessa[$i]['ID'], $nossoNumero, $numRemessa, date('Y-m-d'), $filename.$ambiente);
                
            } // for
            
            // grava trailler
            $numRegistro++;
            //001 a 001 Identificação Registro 001 '9'  X 
            $traillerWrite = "9";
            //002 a 002 Identificação do arquivo  remessa 001 '1'
            $traillerWrite .= "1";
            //003 a 005 Número do Sicredi 003 - 748
            $traillerWrite .= "748";
            //006 a 010 Código do beneficiário/cedente
            $traillerWrite .= $codEmpresa;
            //011 a 394 Filler 384 deixar em branco
            $traillerWrite .= str_pad("", 384, " ", STR_PAD_LEFT);
            //395 a 400 Número Seqüencial de Registro 006 Nº Seqüencial do Último Registro  X 
            $traillerWrite .= str_pad($numRegistro, 6, "0", STR_PAD_LEFT);
    
            fwrite($wh, $traillerWrite."\r\n");
        } // if
        else {
           return 'Não existe boletos para enviar remessa bancária!!';
        }
        //echo "Total Registros:-->".$numCartao;
        fclose($wh); // No error
        //$this->downloadFile($file_target);
        
        //valida se existe registros duplicados
        $resultIguais =  $this->valoresDuplicados($this->array_nosso_numero);
        if(!empty($resultIguais)){
            // Remove o arquivo se ele existe
            if (file_exists($file_target)) {
                unlink($file_target);
                $duplicadosString = implode(', ', $resultIguais);
                throw new Exception("Registros duplicados (".$duplicadosString."), remessa excluída. <br> Contate o suporte e envie os registros duplicados!");
                //$this->mostraRemessa(null, null, "Registros duplicados (".$duplicadosString."), remessa excluida!");
            }
        }
        
    } catch (Exception $ex) {
        $status = false;
        $this->mostraRemessa(null, null, $ex->getMessage(), false);
    }

    if($status !== false){
        $this->mostraRemessa($file_target, $banco);
    }
    
    } //fim remessaBancaria748



    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraRemessa($file, $banco = null)
    {

        $par = explode("|", $this->m_letra);
        $arrData = explode("-", $par[0]);


        if ($this->m_letra != ''):
            $lanc = $this->selectRemessaBancaria($this->m_letra);
            //conta selecionada
            $objContaBanco = new c_contaBanco;
            $objContaBanco->setId($par[2]);
            $conta = $objContaBanco->select_ContaBanco();
            $banco = $conta[0]['BANCO'];
        endif;


        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', isset($mensagem) ? $mensagem : '');
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('saldoInicial', isset($saldoTotal) ? $saldoTotal : 0);
        $this->smarty->assign('dataInicio', $par[0]);
        $this->smarty->assign('dataFim', $par[1]);
        $this->smarty->assign('arquivo', ADMhttpCliente . "/banco/" . $banco . "/remessa/" . date("Y") . "/" . basename($file));
        //$this->smarty->assign('arquivo', $file);
        $this->smarty->assign('nomeArq', basename($file));
        $this->smarty->assign('banco', $banco);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->assign('label', isset($arrLabel) ? $arrLabel : []);
        $this->smarty->assign('pag', isset($arrPag) ? $arrPag : []);
        $this->smarty->assign('rec', isset($arrRec) ? $arrRec : []);

        if ($arrData[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $arrData[0]);

        if ($arrData[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes + 1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        } else $this->smarty->assign('dataFim', $arrData[1]);

        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $filial_ids[0] = 0;
        $filial_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i + 1] = $result[$i]['ID'];
            $filial_names[$i + 1] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        if ($par[1] == "") $this->smarty->assign('filial_id', $this->m_empresacentrocusto);
        else $this->smarty->assign('filial_id', $par[1]);

        // conta bancaria
        $consulta = new c_banco();
        $sql = "select conta as id, nomeinterno as descricao from fin_conta  where status ='A'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        for ($i = 0; $i < count($result); $i++) {
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('conta_ids', $conta_ids);
        $this->smarty->assign('conta_names', $conta_names);
        if ($par[2] == "") $this->smarty->assign('conta_id', '');
        else $this->smarty->assign('conta_id', $par[2]);




        $this->smarty->display('remessa_bancaria_mostra.tpl');
    } //fim mostrasituacaos
    //-------------------------------------------------------------




    /**
     * @name checkAndCreateYearFolder
     * @description verifica se a pasta do ano existe e cria se necessário
     * @param string $banco - código do banco
     * @return bool - true se pasta existe ou foi criada com sucesso
     */
    private function checkAndCreateYearFolder($banco) {
        $path = ADMraizCliente."/banco/".$banco."/remessa/".date("Y");
        
        // Verifica se a pasta do ano existe
        if (!is_dir($path)) {
            // Tenta criar a pasta do ano
            if (!mkdir($path, 0755, true)) {
                throw new Exception("Erro ao criar pasta do ano: " . $path . ". Verifique as permissões.");
            }
        }
        
        return true;
    }


}	//	END OF THE CLASS

// Rotina principal - cria classe
$remessa_bancaria = new p_remessa_bancaria();


$remessa_bancaria->controle();
