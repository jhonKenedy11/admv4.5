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
  echo("menu===>>>".$this->m_submenu);
  switch ($this->m_submenu){
        case 'gerar':
            $this->remessaBancaria('');
            break;
        default:
            $this->mostraRemessa('');
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
    $ambiente = ".TST"; //REM
    $remessa = $this->selectRemessaBancaria($this->m_letra);
    $teste_array = is_array($remessa);
    echo("letra==> ".$this->m_letra);
    if (isset($teste_array)){

        // busca emitente
        $emitente = new c_banco;
        $emitente->setTab('AMB_EMPRESA');
        $arrEmitente = $emitente->getRecord('empresa='.$this->m_empresaid);
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
        $headerWrite .= str_pad($agencia, 4, "0", STR_PAD_LEFT).'00'.str_pad($contaCorrente, 6, "0", STR_PAD_LEFT);
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
            //TIPO DE REGISTRO IDENTIFICAÇÃO DO REGISTRO TRANSAÇÃO 001 001 9(01) 1
            $transacaoWrite = "1";
            // CÓDIGO DE INSCRIÇÃO TIPO DE INSCRIÇÃO DA EMPRESA 002 003 9(02) NOTA 1
            $transacaoWrite .= '02';
            // NÚMERO DE INSCRIÇÃO No DE INSCRIÇÃO DA EMPRESA (CPF/CNPJ) 004 017 9(14) NOTA 1
            $transacaoWrite .= str_pad(                    
                    str_replace ("/" , "",                            
                    str_replace ("-" , "",
                    str_replace ("." , "", $arrEmitente[0]['CNPJ'])))                                  
                    , 14, "0", STR_PAD_LEFT);
            //AGÊNCIA AGÊNCIA MANTENEDORA DA CONTA 018 021 9(04)
            //ZEROS COMPLEMENTO DE REGISTRO 022 023 9(02) “00”
            //CONTA NÚMERO DA CONTA CORRENTE DA EMPRESA 024 028 9(05)
            //DAC DÍGITO DE AUTO CONFERÊNCIA AG/CONTA EMPRESA 029 029 9(01)
            $transacaoWrite .= str_pad($agencia, 4, "00", STR_PAD_LEFT).'00'.str_pad($contaCorrente, 6, "0", STR_PAD_LEFT);
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
            $transacaoWrite .= str_pad($remessa[$i]['DOCTO'].$remessa[$i]['SERIE'].$remessa[$i]['PARCELA'], 10, "0", STR_PAD_LEFT);
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
            $transacaoWrite .= str_pad(str_replace($charValor, "", $conta[0]['JUROS']), 13, "0", STR_PAD_LEFT);
            // DESCONTO ATÉ DATA LIMITE PARA CONCESSÃO DE DESCONTO 174 179 9(06) DDMMAA
            $transacaoWrite .= str_pad("", 6, "0", STR_PAD_LEFT);
            // VALOR DO DESCONTO VALOR DO DESCONTO A SER CONCEDIDO 180 192 9(11)V9(2) NOTA 13
            $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
            // VALOR DO I.O.F. VALOR DO I.O.F. RECOLHIDO P/ NOTAS SEGURO 193 205 9(11)V((2) NOTA 14
            $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
            // ABATIMENTO VALOR DO ABATIMENTO A SER CONCEDIDO 206 218 9(11)V9(2) NOTA 13
            $transacaoWrite .= str_pad("", 13, "0", STR_PAD_LEFT);
            // CÓDIGO DE INSCRIÇÃO IDENTIFICAÇÃO DO TIPO DE INSCRIÇÃO/SACADO 219 220 9(02) 01=CPF 02=CNPJ
            if ($remessa[$i]['PESSOA'] == 'J'):
                $transacaoWrite .= '02';
            else:    
                $transacaoWrite .= '01';
            endif;
            // NÚMERO DE INSCRIÇÃO No DE INSCRIÇÃO DA EMPRESA (CPF/CNPJ) 004 017 9(14) NOTA 1
            $transacaoWrite .= str_pad(                    
                    str_replace ("/" , "",                            
                    str_replace ("-" , "",
                    str_replace ("." , "", $remessa[$i]['CNPJCPF'])))                                  
                    , 14, "0", STR_PAD_LEFT);
            // NOME NOME DO SACADO 235 264 X(30) NOTA 15
            // BRANCOS COMPLEMENTO DE REGISTRO 265 274 X(10) NOTA 15
            $nome = substr($this->removeAcentos($remessa[$i]['NOME']), 0, 40);
            $nome = trim($nome);
            $nome = str_pad($nome, 40, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $nome;
            // LOGRADOURO RUA, NÚMERO E COMPLEMENTO DO SACADO 275 314 X(40)
            $endereco = substr($this->removeAcentos($remessa[$i]['ENDERECO']), 0, 40);
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
            fwrite($wh, $transacaoWrite."\r\n");

            // atualiza fin_lancamento com nosso e numero e data do envio do arquivo de remessa
            // $this->atualizaRemessa($remessa[$i]['ID'], $nossoNumero, $numRemessa, date('Y-m-d'), $filename.str_pad($serieArq, 2, "0", STR_PAD_LEFT).$ambiente);
            
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

        fwrite($wh, $traillerWrite."\r\n");
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
} //fim remessaBancaria



//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraRemessa($file, $banco=null){

    $par = explode("|", $this->m_letra);
    $arrData = explode("-", $par[0]);


   if ($this->m_letra != ''):
        $lanc = $this->selectRemessaBancaria($this->m_letra);
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
