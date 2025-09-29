<?php

/**
 * @package   astecv3
 * @name      p_remessa_iqvia
 * @version   3.0.00
 * @copyright 2018
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      10/10/2018
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/est/c_nota_fiscal.php");
include_once($dir . "/../../bib/c_mail.php");


//Class P_REMESSA_BANCARIA
Class p_remessa_iqvia extends c_nota_fiscal {

private $m_submenu = NULL;
private $m_letra = NULL;
private $lanc = NULL;
private $m_codIms = '6123';
private $m_email = NULL;
public  $smarty = NULL;


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
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';
        $this->m_lanc = isset($parmPost['lanc']) ? $parmPost['lanc'] : '';
        $this->m_lanc = '0000';
        $this->m_email = isset($parmPost['email']) ? $parmPost['email'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Remessa Iqvia");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5, 6  ]"); 
        $this->smarty->assign('disableSort', "[ 6 ]"); 
        $this->smarty->assign('numLine', "50"); 

        // include do javascript
        // include ADMjs . "/est/s_est.js";

}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
  switch ($this->m_submenu){
        case 'gerar':
            $this->remessaBancaria('');
            break;        
        case 'email':{
                $par = explode("|", $this->m_letra);
                $email = explode("|", $this->m_email);   
                $arrData = explode("-", $par[0]);
                $dFim = $arrData[1];
                $File = $this->mostraIqvia('');
                $this -> enviarEmailXML($email[0],$email[1],$email[2], null, '', $dFim, $File);
                break;
            }
        default:
            $this->mostraIqvia('');
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
 * @name remessaIqvia
 * @description gera arquivo de remessa para o banco correspondente com titulos em aberto do tipo boleto
 * @param int $banco - banco a ser gerado o arquivo de remessa
 * @return int $count - numero de parcelas geradas
 */
    
public function remessaCliente($time = null){
    
try {
    $par = explode("|", $this->m_letra);
    $arrData = explode("-", str_replace("/", "",$par[0]));
    $dataIni = trim($arrData[0]);
    $dataFim = trim($arrData[1]);

    $file_target = '';
    $remessa = $this->selectNfRemessaIqvia($this->m_letra, 'C');
    $teste_array = is_array($remessa);

    if (isset($teste_array)){
        // BUSCA DADOS EMPRESA
        $parametros = new c_banco;
        $parametros->setTab("AMB_EMPRESA");
        $arrEmpresa = $parametros->getRecord("CENTROCUSTO=".$this->m_empresacentrocusto);
        $empresaNome = substr($arrEmpresa[0]["NOMEEMPRESA"], 0, 30);
        $empresaCnpj = $arrEmpresa[0]["CNPJ"];
        $parametros->close_connection();                        

        
        
        //Arquivo remessa
//        $path = ADMraizCliente."/file/iqvia/".date("Y",  strtotime($dataFim));
//        $filename = "/C". $this->m_codIms."M".date("m", strtotime($dataFim)).".D".date("d", strtotime($dataFim));
        $path = ADMraizCliente."/file/iqvia/".substr($dataFim, 4, 4).$time; // ano
        $filename = "/C". $this->m_codIms."M".substr($dataFim, 2, 2).".D".substr($dataFim, 0, 2);
        $file_target = $path.$filename;

        if (!is_dir($path)) {
            mkdir($path,0700,true);
        }           
          

        // cria arquivo
        $wh = fopen($file_target, 'w+');
        if ( !$wh ) {
            throw new Exception( "Erro ao gerar arquivo de remessa - ".$php_errormsg );
        }

        // registro header
        $headerWrite = "1";
        $headerWrite .= "0";
        $headerWrite .= $this->m_codIms;
        $headerWrite .= str_pad($empresaNome, 30, " ", STR_PAD_RIGHT);
        $headerWrite .= str_pad($empresaCnpj, 14, " ", STR_PAD_LEFT);
        $headerWrite .= trim($dataIni);
        $headerWrite .= trim($dataFim);
        $headerWrite .= date('dmY');
        $headerWrite .= '1';
        $headerWrite .= str_pad('', 100, " ", STR_PAD_RIGHT);
        $headerWrite .= str_pad('', 171, " ", STR_PAD_RIGHT);
        $headerWrite .= 'imsbrcli1';
        
        fwrite($wh, $headerWrite."\r\n");
        $numRegistro=1;
        
        // registro tipo 1 - transacao
        $remessa  = $remessa ?? [];
        for ($i=0; $i < count($remessa); $i++){
            $numRegistro++;
            
            $transacaoWrite = "2";
            $transacaoWrite .= str_pad($remessa[$i]['CLIENTE'], 14, "0", STR_PAD_LEFT);
            $transacaoWrite .= str_pad($remessa[$i]['CNPJCPF'], 14, " ", STR_PAD_LEFT);
            $transacaoWrite .= '1';
            //$transacaoWrite .= ($remessa[$i]['PESSOA'] == 'J' ?'1':'2');
            $nomeReduzido = $this->removeAcentos($remessa[$i]['NOMEREDUZIDO']);
            $nomeReduzido = trim($nomeReduzido);
            $nomeReduzido = str_pad($nomeReduzido, 40, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $nomeReduzido;
            $nome = substr($this->removeAcentos($remessa[$i]['NOME']), 0, 40);
            $nome = trim($nome);
            $nome = str_pad($nome, 40, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $nome;
            $transacaoWrite .= '1';
            //275 a 314 Endereço Completo 040 Endereço do Pagador X
            $endereco = $this->removeAcentos($remessa[$i]['ENDERECO']);
            $endereco = trim($endereco);
            $endereco = str_pad($endereco, 70, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $endereco;
            $complemento = substr($this->removeAcentos($remessa[$i]['COMPLEMENTO']), 0, 20);
            $complemento = trim($complemento);
            $complemento = str_pad($complemento, 20, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $complemento;
            $cep = str_pad($remessa[$i]['CEP'], 8, "0", STR_PAD_LEFT);
            $transacaoWrite .= $cep;
            $cidade = substr($this->removeAcentos($remessa[$i]['CIDADE']), 0, 30);
            $cidade = trim($cidade);
            $cidade = str_pad($cidade, 30, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $cidade;
            $transacaoWrite .= $remessa[$i]['UF'];
            $transacaoWrite .= str_pad($remessa[$i]['FONE'], 20, " ", STR_PAD_LEFT);
            $transacaoWrite .= str_pad($remessa[$i]['FONE'], 20, " ", STR_PAD_LEFT);
            $transacaoWrite .= str_pad(substr(str_replace("-", "",$remessa[$i]['DATEINSERT']), 0, 8), 8, " ", STR_PAD_LEFT);
            $transacaoWrite .= str_pad(substr($remessa[$i]['EMAIL'], 0, 35), 35, " ", STR_PAD_RIGHT);
            $transacaoWrite .= str_pad(substr($remessa[$i]['HOMEPAGE'], 0, 25), 25, " ", STR_PAD_RIGHT);
            $transacaoWrite .= "     C";

            
            // grava arquivo txt
            fwrite($wh, $transacaoWrite."\r\n");
            
        } // for
        
        // grava trailler
        $numRegistro++;

        $traillerWrite = "3";
        $traillerWrite .= "0";
        $traillerWrite .= $this->m_codIms;
        $traillerWrite .= str_pad($numRegistro, 8, "0", STR_PAD_LEFT);
        $traillerWrite .= str_pad('', 200, " ", STR_PAD_RIGHT);
        $traillerWrite .= str_pad('', 132, " ", STR_PAD_RIGHT);
        $traillerWrite .= 'imsbrcli3';


        fwrite($wh, $traillerWrite."\r\n");
    } // if
    else {
       return 'Não existe boletos para enviar remessa bancária!!';
    }
    fclose($wh); // No error
    
} catch (Exception $ex) {
    $this->mostraIqvia($ex);
}
//$this->mostraIqvia($file_target, $banco);
} //fim remessaCliente

public function remessaProduto($time = null){
    
try {
    $par = explode("|", $this->m_letra);
    $arrData = explode("-", str_replace("/", "",$par[0]));
    $dataIni = trim($arrData[0]);
    $dataFim = trim($arrData[1]);

    $file_target = '';
    $remessa = $this->selectNfRemessaIqvia($this->m_letra, 'P');
    $teste_array = is_array($remessa);

    if (isset($teste_array)){
        // BUSCA DADOS EMPRESA
        $parametros = new c_banco;
        $parametros->setTab("AMB_EMPRESA");
        $arrEmpresa = $parametros->getRecord("CENTROCUSTO=".$this->m_empresacentrocusto);
        $empresaNome = substr($arrEmpresa[0]["NOMEEMPRESA"], 0, 30);
        $empresaCnpj = $arrEmpresa[0]["CNPJ"];
        $parametros->close_connection();                        

        
        
        //Arquivo remessa
//        $path = ADMraizCliente."/file/iqvia/".date("Y");
//        $filename = "/P". $this->m_codIms."M".date("m").".D".date("d");
        $path = ADMraizCliente."/file/iqvia/".substr($dataFim, 4, 4).$time; // ano
        $filename = "/P". $this->m_codIms."M".substr($dataFim, 2, 2).".D".substr($dataFim, 0, 2);
        $file_target = $path.$filename;
        
        if (!is_dir($path)) {
            mkdir($path,0700,true);
        } 

        // cria arquivo
        $wh = fopen($file_target, 'w+');
        if ( !$wh ) {
            throw new Exception( "Erro ao gerar arquivo de remessa - ".$php_errormsg );
        }

        // registro header
        $headerWrite = "7";
        $headerWrite .= "0";
        $headerWrite .= $this->m_codIms;
        $headerWrite .= str_pad($empresaNome, 30, " ", STR_PAD_RIGHT);
        $headerWrite .= str_pad($empresaCnpj, 14, " ", STR_PAD_LEFT);
        $headerWrite .= trim($dataIni);
        $headerWrite .= trim($dataFim);
        $headerWrite .= date('dmY');
        $headerWrite .= '1';
        $headerWrite .= str_pad('', 118, " ", STR_PAD_RIGHT);
        $headerWrite .= 'imsbrpro7';
        
        fwrite($wh, $headerWrite."\r\n");
        $numRegistro=1;
        $remessa = $remessa  ?? [];
        
        // registro tipo 1 - transacao
        for ($i=0; $i < count($remessa); $i++){
            $numRegistro++;
            
            $transacaoWrite = "8 0";
            $transacaoWrite .= str_pad($remessa[$i]['CODIGO'], 13, "0", STR_PAD_LEFT);
            $transacaoWrite .= "0";
            
            if (($remessa[$i]['CODIGOBARRAS'] == '') or ($remessa[$i]['CODIGOBARRAS'] == 'SEM GTIN')):
                $transacaoWrite .= str_pad(substr($remessa[$i]['CODIGO'], 0, 13), 13, " ", STR_PAD_LEFT);
            else:
                $transacaoWrite .= str_pad(substr($remessa[$i]['CODIGOBARRAS'], 0, 13), 13, " ", STR_PAD_LEFT);
            endif;
            $transacaoWrite .= "1";
            $desc = substr($this->removeAcentos($remessa[$i]['DESCRICAO']), 0, 70);
            $desc = trim($desc);
            $desc = str_pad($desc, 70, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $desc;
            $transacaoWrite .= str_pad('', 8, " ", STR_PAD_RIGHT);
            $nomeFab = substr($this->removeAcentos($remessa[$i]['NOMEFABRICANTE']), 0, 40);
            $nomeFab = trim($nomeFab);
            $nomeFab = str_pad($nomeFab, 40, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $nomeFab;
            $transacaoWrite .= str_pad(str_replace(".", "",$remessa[$i]['CUSTOCOMPRA']), 9, "0", STR_PAD_LEFT);
            $grupo = substr($this->removeAcentos($remessa[$i]['DESCGRUPO']), 0, 20);
            $grupo = trim($grupo);
            $grupo = str_pad($grupo, 20, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $grupo;
            $transacaoWrite .= str_pad('', 15, " ", STR_PAD_RIGHT);
            $transacaoWrite .= str_pad(substr(str_replace("-", "",$remessa[$i]['DATEINSERT']), 0, 8), 8, " ", STR_PAD_LEFT);
            $transacaoWrite .= "P";
            
            // grava arquivo txt
            fwrite($wh, $transacaoWrite."\r\n");
            
        } // for
        
        // grava trailler
        $numRegistro++;

        $traillerWrite = "9";
        $traillerWrite .= "0";
        $traillerWrite .= $this->m_codIms;
        $traillerWrite .= str_pad($numRegistro, 8, "0", STR_PAD_LEFT);
        $traillerWrite .= str_pad('', 179, " ", STR_PAD_RIGHT);
        $traillerWrite .= 'imsbrpro9';


        fwrite($wh, $traillerWrite."\r\n");
    } // if
    else {
       return 'Não existe boletos para enviar remessa bancária!!';
    }
    fclose($wh); // No error
    
} catch (Exception $ex) {
    $this->mostraIqvia($ex);
}
//$this->mostraIqvia($file_target, $banco);
} //fim remessa Produto

public function remessaVendas($time = null){
    
try {
    $par = explode("|", $this->m_letra);
    $arrData = explode("-", str_replace("/", "",$par[0]));
    $dataIni = trim($arrData[0]);
    $dataFim = trim($arrData[1]);

    $file_target = '';
    $remessa = $this->selectNfRemessaIqvia($this->m_letra, 'V');
    $teste_array = is_array($remessa);

    if (isset($teste_array)){
        
        //Arquivo remessa
//        $path = ADMraizCliente."/file/iqvia/".date("Y");
//        $filename = "/V". $this->m_codIms."M".date("m").".D".date("d");
        $path = ADMraizCliente."/file/iqvia/".substr($dataFim, 4, 4).$time; // ano
        $filename = "/V". $this->m_codIms."M".substr($dataFim, 2, 2).".D".substr($dataFim, 0, 2);
        $file_target = $path.$filename;
        
        if (!is_dir($path)) {
            mkdir($path,0700,true);
        } 

        // cria arquivo
        $wh = fopen($file_target, 'w+');
        if ( !$wh ) {
            throw new Exception( "Erro ao gerar arquivo de remessa - ".$php_errormsg );
        }

        // registro header
        $headerWrite = "4";
        $headerWrite .= "0";
        $headerWrite .= $this->m_codIms;
        $headerWrite .= trim($dataIni);
        $headerWrite .= trim($dataFim);
        $headerWrite .= date('dmY');
        $headerWrite .= 'D';
        $headerWrite .= str_pad('', 3, " ", STR_PAD_RIGHT);
        $headerWrite .= 'imsbrven4';
        
        fwrite($wh, $headerWrite."\r\n");
        $numRegistro=1;
        $quantTotal = 0;
        // registro tipo 1 - transacao
        $remessa = $remessa ?? [];
        for ($i=0; $i < count($remessa); $i++){
            $numRegistro++;
            $quant = explode(".", $remessa[$i]['QUANT']);
            //$quant = substr($remessa[$i]['QUANT'], -4);
            $quantTotal += $quant[0];
            $dataEmissao = explode("-", $remessa[$i]['EMISSAO']);
            $dia = explode(" ", $dataEmissao[2]);
            
            $transacaoWrite = "5";
            $transacaoWrite .= $dia[0];
            $transacaoWrite .= str_pad($remessa[$i]['PESSOA'], 14, "0", STR_PAD_LEFT);
            $transacaoWrite .= "10";
            $transacaoWrite .= str_pad($remessa[$i]['CODPRODUTO'], 13, "0", STR_PAD_LEFT);
            $transacaoWrite .= "1N";
            $transacaoWrite .= str_pad(str_replace(".", "",$quant[0]), 8, "0", STR_PAD_LEFT);
            $transacaoWrite .= "V";
            
            // grava arquivo txt
            fwrite($wh, $transacaoWrite."\r\n");
            
        } // for
        
        // grava trailler
        $numRegistro++;

        $traillerWrite = "6";
        $traillerWrite .= "0";
        $traillerWrite .= $this->m_codIms;
        $traillerWrite .= str_pad($numRegistro, 8, "0", STR_PAD_LEFT);
        $traillerWrite .= str_pad($quantTotal, 10, "0", STR_PAD_LEFT);
        $traillerWrite .= str_pad("", 10, "0", STR_PAD_LEFT);
        $traillerWrite .= 'imsbrven6';


        fwrite($wh, $traillerWrite."\r\n");
    } // if
    else {
       return 'Não existe boletos para enviar remessa bancária!!';
    }
    fclose($wh); // No error
    
} catch (Exception $ex) {
    $this->mostraIqvia($ex);
}
//$this->mostraIqvia($file_target, $banco);
} //fim remessa Vendas

public function remessaEstoque($time = null){
    
try {
    $par = explode("|", $this->m_letra);
    $arrData = explode("-", str_replace("/", "",$par[0]));
    $dataIni = trim($arrData[0]);
    $dataFim = trim($arrData[1]);

    $file_target = '';
    $remessa = $this->selectNfRemessaIqvia($this->m_letra, 'E') ?? [];
    $teste_array = is_array($remessa);

    if (isset($teste_array)){
        
        // BUSCA DADOS EMPRESA
        $parametros = new c_banco;
        $parametros->setTab("AMB_EMPRESA");
        $arrEmpresa = $parametros->getRecord("CENTROCUSTO=".$this->m_empresacentrocusto);
        $empresaCnpj = $arrEmpresa[0]["CNPJ"];
        $parametros->close_connection();                        
        
        //Arquivo remessa
//        $path = ADMraizCliente."/file/iqvia/".date("Y");
//        $filename = "/S". $this->m_codIms."M".date("m").".D".date("d");
        $path = ADMraizCliente."/file/iqvia/".substr($dataFim, 4, 4).$time; // ano
        $filename = "/S". $this->m_codIms."M".substr($dataFim, 2, 2).".D".substr($dataFim, 0, 2);
        $file_target = $path.$filename;
        
        if (!is_dir($path)) {
            mkdir($path,0700,true);
        }

        // cria arquivo
        $wh = fopen($file_target, 'w+');
        if ( !$wh ) {
            throw new Exception( "Erro ao gerar arquivo de remessa - ".$php_errormsg );
        }

        $numRegistro=1;
        $quantTotal = 0;
        // registro tipo 1 - transacao
        $remessa = $remessa ?? [];
        for ($i=0; $i < count($remessa); $i++){
            $numRegistro++;
            $quant = substr($remessa[$i]['QUANT'], -4);
            $quantTotal += $quant;
            $data = date('Ymd');
            
            $transacaoWrite = $data;
            $transacaoWrite .= $empresaCnpj;
            $transacaoWrite .= $this->m_codIms;
            $transacaoWrite .= str_pad(substr($remessa[$i]['CODIGOBARRAS'], 0, 13), 13, " ", STR_PAD_LEFT);
            $desc = substr($this->removeAcentos($remessa[$i]['DESCRICAO']), 0, 50);
            $desc = trim($desc);
            $desc = str_pad($desc, 50, " ", STR_PAD_RIGHT);
            $transacaoWrite .= $desc;
            switch ($remessa[$i]['STATUS']){
                case 0:
                    $transacaoWrite .= "02";
                    break;;
                case 1:
                    $transacaoWrite .= "03";
                    break;;
                case 8:
                    $transacaoWrite .= "05";
                    break;;
            }
            $transacaoWrite .= str_pad(str_replace(".", "",$quant), 18, "0", STR_PAD_LEFT);
            
            // grava arquivo txt
            fwrite($wh, $transacaoWrite."\r\n");
            
        } // for
        
    } // if
    else {
       return 'Não existe boletos para enviar remessa bancária!!';
    }
    fclose($wh); // No error
    
} catch (Exception $ex) {
    $this->mostraIqvia($ex.message);
}
//$this->mostraIqvia($file_target, $banco);
} //fim remessa ESTOQUE


//---------------------------------------------------------------
function enviarEmailXML($email, $title, $body, $cc=null, $dhIni, $dhFim, $fileName01, $fileName02 = null) {
        
    try {
        if (is_null($email) or ($email=='')) {
            return 'Email envio não cadastrado';                
        } else {    
            if (is_null($email)  or ($email=='')) {
                $aMails = array($cc); //se for um array vazio a classe Mail irá pegar os emails do xml
            }
        }
        
        if (is_null($fileName02)){
          $fileName02 = $fileName01;  
        }
        
        $mail = new admMail;
        
        $result = $mail->SendMail($this->m_configsmtp, $this->m_configemail, $this->m_configemail, $this->m_configemailsenha, 
                           $body, $title , $email, "",$cc,"", $fileName01, $fileName02);


        if (strstr($result, 'não')){
            return "email XML's NÃO enviado - entre em contato com o suporte";
        } else {   
            return "email XML's enviado com sucesso!!!";
        }

    } catch (Exception $e) {
        return 'Erro -> '.$e->getMessage();
    }
}


function comprimir($caminho){
    $caminho = realpath($caminho);
    $arquivo = $caminho.'.zip';
    $zip = new ZipArchive();
    $zip->open($arquivo, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $arquivos = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($caminho),
        RecursiveIteratorIterator::LEAVES_ONLY
    );    
    foreach ($arquivos as $name => $file) {
        if (!$file->isDir()) {
            $file_path = $file->getRealPath();
            $relative_path = substr($file_path, strlen($caminho) + 1);
            $zip->addFile($file_path, $relative_path);
        }
    }
    $zip->close();
    return $arquivo;       
}

//---------------------------------------------------------------
function mostraIqvia($file){
    $par = explode("|", $this->m_letra);
    $arrData = explode("-", $par[0]);
    $pathFile = '';
    if (($this->m_letra != '') and ($file=='')):
        $time ="/";
        $data = date("d/m/Y");
        $data = str_replace("/", "", $data);
        $hora = date("H:i:s");
        $hora = str_replace(":", "", $hora);
        $time .= $data.$hora;
        
       $this->remessaCliente($time);
       $this->remessaProduto($time);
       $this->remessaVendas($time);
       $this->remessaEstoque($time);

       $arrData = explode("-", str_replace("/", "",$par[0]));
       $dataFim = trim($arrData[1]);
       $pathFile = ADMraizCliente."/file/iqvia/".substr(trim($dataFim), 4, 4).$time;
       $pathFile = $this-> comprimir($pathFile);
    endif;

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem); 
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('saldoInicial', $saldoTotal);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);
    $this->smarty->assign('arquivo', ADMhttpCliente. "/remessa/iqvia/".date("Y")."/".basename($file));
    //$this->smarty->assign('arquivo', $file);
    $this->smarty->assign('nomeArq', basename($file));
    $this->smarty->assign('lanc', $lanc);

    $this->smarty->assign('label', $arrLabel);
    
    if($arrData[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
    else $this->smarty->assign('dataIni', substr(trim($arrData[0]),0,2)."/".substr(trim($arrData[0]),2,2)."/".substr(trim($arrData[0]),4,4));
    
    if($arrData[1] == "") {
    	$dia = date("d");
    	$mes = date("m");
    	$ano = date("Y");
    	$data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
    	$this->smarty->assign('dataFim', $data);
    }
    else $this->smarty->assign('dataFim', substr(trim($arrData[1]),0,2)."/".substr(trim($arrData[1]),2,2)."/".substr(trim($arrData[1]),4,4));
    
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
    
    $this->smarty->display('remessa_iqvia_mostra.tpl');

    if ($pathFile != ""){
        return $pathFile;
    }
	

} //fim mostrasituacaos

//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$remessa_iqvia = new p_remessa_iqvia();


$remessa_iqvia->controle();
 
  
?>
