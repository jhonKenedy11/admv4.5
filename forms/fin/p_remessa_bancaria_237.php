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
include_once($dir."/../../class/fin/c_conta_banco.php");
include_once($dir."/../../class/fin/c_lancamento.php");


//Class P_REMESSA_BANCARIA
Class p_remessa_bancaria extends c_lancamento {

private $m_submenu = NULL;
private $m_letra = NULL;
private $m_lanc = NULL;
public $smarty = NULL;


//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

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
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
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
function controle(){
  switch ($this->m_submenu){
        case 'gerar':
            if ($this->verificaDireitoUsuario('FinCobrancaRemessa', 'C')) {
                $this->remessaBancaria('');
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
    function removeAcentos($string, $slug = false) {
        $conversao = array('á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e',
            'ê' => 'e', 'í' => 'i', 'ï' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', "ö" => "o",
            'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'ñ' => 'n', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A',
            'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ï' => 'I', "Ö" => "O", 'Ó' => 'O',
            'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C', 'Ñ' => 'N');
        return strtr($string, $conversao);
    }

 /**
 * @name downloadFile
 * @description download arquivo do servidor para maquina local
 * @param string $file - arquivo com o path incluso a ser feito download
 */
 function downloadFile($arquivo) { // $file = include path 
   if(isset($arquivo) && file_exists($arquivo)){ // faz o teste se a variavel não esta vazia e se o arquivo realmente existe
      switch(strtolower(substr(strrchr(basename($arquivo),"."),1))){ // verifica a extensão do arquivo para pegar o tipo
         case "pdf": $tipo="application/pdf"; break;
         case "exe": $tipo="application/octet-stream"; break;
         case "zip": $tipo="application/zip"; break;
         case "doc": $tipo="application/msword"; break;
         case "xls": $tipo="application/vnd.ms-excel"; break;
         case "ppt": $tipo="application/vnd.ms-powerpoint"; break;
         case "gif": $tipo="image/gif"; break;
         case "png": $tipo="image/png"; break;
         case "jpg": $tipo="image/jpg"; break;
         case "mp3": $tipo="audio/mpeg"; break;
         case "php": // deixar vazio por seurança
         case "htm": // deixar vazio por seurança
         case "html": // deixar vazio por seurança
         case "REM": // deixar vazio por seurança
      }
      header("Content-Type: ".$tipo); // informa o tipo do arquivo ao navegador
      header("Content-Length: ".filesize($arquivo)); // informa o tamanho do arquivo ao navegador
      header("Content-Disposition: attachment; filename=".basename($arquivo)); // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
      readfile($arquivo); // lê o arquivo
      exit; // aborta pós-ações
    }   
 }    
 /**
 * @name remessaBancaria
 * @description gera arquivo de remessa para o banco correspondente com titulos em aberto do tipo boleto
 * @param int $banco - banco a ser gerado o arquivo de remessa
 * @return int $count - numero de parcelas geradas
 */
    
public function remessaBancaria($letra = NULL){
    
try {
    $par = explode("|", $this->m_letra);
    $contaBanco = $par[2];
    $file_target = '';
    $ambiente = ".REM"; //TST
    $remessa = $this->selectRemessaBancaria($this->m_letra);
    $teste_array = is_array($remessa);

    if (isset($teste_array)){
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
        //$nossoNumero = $conta[0]['ULTIMONOSSONRO']; // atualizar conta
        $charValor = array(".");
        $descontoBonificacao = str_replace($charValor, "", $conta[0]['DESCONTOBONIFICACAO']);
        $condicaoEmissaoBoleto = $conta[0]['CONDICAOEMISSAOBOLETO'];
        $msg1 = $conta[0]['MSG1BOLETO'];
        $identificacaoOcorrencia = '01';

        // gera e grava o numero do arquivo de remessa
        $numRemessa = $objContaBanco->geraNumeroRemessa($contaBanco, $conta[0]['NUMREMESSA']); // atualizar conta
        $numRegistro  = 1;
    
        //Arquivo remessa
        $path = ADMraizCliente."/banco/".$banco."/remessa/".date("Y");
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
            throw new Exception( "Erro ao gerar arquivo de remessa - ".$php_errormsg );
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
            $transacaoWrite .= "0".$codCarteira.str_pad($agencia, 5, "0", STR_PAD_LEFT).str_pad($contaCorrente, 8, "0", STR_PAD_LEFT);
            //038 a 062 Nº Controle do Participante 025  Uso da Empresa Vide Obs. Pág. 17
            $transacaoWrite .= str_pad($remessa[$i]['ID'], 25, " ", STR_PAD_RIGHT);
            //063 a 065 Código do Banco a ser debitado na Câmara de Compensação 003 Nº do Banco “237”  Vide Obs. Pág.17
            $transacaoWrite .= "000";
            //066 a 066 Campo de Multa 001 Se = 2 considerar percentual de multa. Se = 0, sem multa. Vide Obs.Pág. 17
            //067 a 070 Percentual de multa 004 Percentual de multa a ser considerado  vide Obs. Pág. 17
            if ($multa > 0):
                $transacaoWrite .= "2";
                $transacaoWrite .= str_pad($multa, 4, "0", STR_PAD_LEFT);
            else:    
                $transacaoWrite .= "0";
                $transacaoWrite .= "0000";
            endif;
           
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
            //105 a 105 Indicador Rateio Crédito (opcional) 001 “R”Vide Obs. Pág. 19 X
            $transacaoWrite .= " ";
            //106 a 106 Endereçamento para Aviso do Débito Automático em Conta Corrente (opcional) 001 Vide Obs. Pág. 19 X  11/57
            $transacaoWrite .= "2";
            //107 a 108 Quantidade possíveis de pagamento 002 Vide Obs. Pág.20 X
            $transacaoWrite .= "  ";
            //109 a 110 Identificação da ocorrência 002 Códigos de ocorrência Vide Obs. Pág. 20 X
            $transacaoWrite .= $identificacaoOcorrencia;
            //111 a 120 Nº do Documento 010 Documento X
            $transacaoWrite .= str_pad($remessa[$i]['DOCTO'].$remessa[$i]['SERIE'].$remessa[$i]['PARCELA'], 10, "0", STR_PAD_LEFT);
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
            $transacaoWrite .= str_pad(str_replace($charValor, "", $conta[0]['JUROS']), 13, "0", STR_PAD_LEFT);
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
            if ($remessa[$i]['PESSOA'] = 'J'):
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
    echo "Total Registros:-->".$numCartao;
    fclose($wh); // No error
    //$this->downloadFile($file_target);
    
} catch (Exception $ex) {
    $this->mostraRemessa($ex);
}
$this->mostraRemessa($file_target, $banco);
} //fim remessaBancaria



//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraRemessa($file, $banco=null){

    $par = explode("|", $this->m_letra);
    $arrData = explode("-", $par[0]);


   if ($this->m_letra != ''):
        $lanc = $this->selectRemessaBancaria($this->m_letra);
        $objContaBanco->setId($par[2]); //conta selecionada
        $conta = $objContaBanco->select_ContaBanco();
        $banco = $conta[0]['BANCO'];
    endif;

	
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('saldoInicial', $saldoTotal);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);
    $this->smarty->assign('arquivo', ADMhttpCliente. "/banco/".$banco."/remessa/".date("Y")."/".basename($file));
    //$this->smarty->assign('arquivo', $file);
    $this->smarty->assign('nomeArq', basename($file));
    $this->smarty->assign('banco', $banco);
    $this->smarty->assign('lanc', $lanc);

    $this->smarty->assign('label', $arrLabel);
    $this->smarty->assign('pag', $arrPag);
    $this->smarty->assign('rec', $arrRec);
    
    if($arrData[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
    else $this->smarty->assign('dataIni', $arrData[0]);
    
    if($arrData[1] == "") {
    	$dia = date("d");
    	$mes = date("m");
    	$ano = date("Y");
    	$data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
    	$this->smarty->assign('dataFim', $data);
    }
    else $this->smarty->assign('dataFim', $arrData[1]);
    
    // filial
    $consulta = new c_banco();
    $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('filial_ids', $filial_ids);
    $this->smarty->assign('filial_names', $filial_names);
    if($par[1] == "") $this->smarty->assign('filial_id', $this->m_empresacentrocusto);
    else $this->smarty->assign('filial_id', $par[1]);

    // conta bancaria
    $consulta = new c_banco();
    $sql = "select conta as id, nomeinterno as descricao from fin_conta";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('conta_ids', $conta_ids);
    $this->smarty->assign('conta_names', $conta_names);
    if($par[2] == "") $this->smarty->assign('conta_id', '');
    else $this->smarty->assign('conta_id', $par[2]);


    
    
    $this->smarty->display('remessa_bancaria_mostra.tpl');
	

} //fim mostrasituacaos
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$remessa_bancaria = new p_remessa_bancaria();


$remessa_bancaria->controle();
 
  
?>
