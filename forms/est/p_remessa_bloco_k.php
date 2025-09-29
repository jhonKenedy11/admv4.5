<?php

/**
 * @package   astecv3
 * @name      p_remessa_blocok
 * @version   3.0.00
 * @copyright 2019
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      10/02/2019
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir."/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/est/c_produto.php");
include_once($dir."/../../class/est/c_produto_estoque.php");
include_once($dir."/../../bib/c_mail.php");


//Class P_REMESSA_BANCARIA
Class p_remessa_blocok extends c_produto {

private $m_submenu = NULL;
private $m_letra = NULL;
public $wh = NULL;
public $dadosOK = '1';
public $remessa = '';
public $smarty = NULL;
private $m_email = NULL;
public $emailFile = '';


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
        $this->m_email = isset($parmPost['email']) ? $parmPost['email'] : '';
        $this->m_lanc = '0000';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Remessa BlocoK");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5, 6  ]"); 
        $this->smarty->assign('disableSort', "[ 6 ]"); 
        $this->smarty->assign('numLine', "50"); 

}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
  switch ($this->m_submenu){
        case 'gerar':
            $this->remessaBancaria('');
            break;
        case 'enviarFile':{
              $par = explode("|", $this->m_letra);
              $arrData = explode("-", str_replace("/", "",$par[0]));
              $dataIni = trim($arrData[0]);
              $dataFim = trim($arrData[1]);
              $emailFile = $this->controleSecao();
              $email = explode("|", $this->m_email);
              $this -> enviarFile($email[0],$email[1],$email[2], null, $emailFile); 
              $this->m_letra = '';
              $this->mostraBlocoK('');
              break;
            }
        default:
            $this->mostraBlocoK('');
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
 * @name aberturaBloco0
 * @description seção que grava a abertura do bloco 0
 * @param string $dataBase - data base para consulta de registros
 * @param string $arquivo - arquivo para gravação
 * @return boolean operação ocorreu normalmente ou não
 */
public function aberturaBloco0($dataIni, $dataFim){
    
try {
        // BUSCA DADOS EMPRESA
        $parametros = new c_banco;
        $parametros->setTab("AMB_EMPRESA");
        $arrEmpresa = $parametros->getRecord("CENTROCUSTO=".$this->m_empresacentrocusto);
        $empresaNome = $arrEmpresa[0]["NOMEEMPRESA"];
        $empresaCnpj = $arrEmpresa[0]["CNPJ"];
        $parametros->close_connection();                        
        
        // registro header
        $headerWrite = "|0000";
        $headerWrite .= "|013"; // VERIFICAR
        $headerWrite .= "|0"; 
        $headerWrite .= "|".trim($dataIni);
        $headerWrite .= "|".trim($dataFim);
        $headerWrite .= "|".$empresaNome;
        $headerWrite .= "|".$empresaCnpj;
        $headerWrite .= "|"; // cpf
        $headerWrite .= "|".$arrEmpresa[0]["UF"];
        $headerWrite .= "|".$arrEmpresa[0]["INSCESTADUAL"];
        $headerWrite .= "|".$arrEmpresa[0]["CODMUNICIPIO"];
        $headerWrite .= "|".$arrEmpresa[0]["INSCMUNICIPAL"];
        $headerWrite .= "|";
        $headerWrite .= '|A';
        $headerWrite .= '|1|';
        
        fwrite($this->wh, $headerWrite."\n");
        $this->numRegistro=1;
        
    echo "Total Registros:-->".$numCartao;
    
} catch (Exception $ex) {
    $this->mostraBlocoK($ex);
}
} //fim aberturaBloco0
    
 /**
 * @name reg0200Produto
 * @description REGISTRO 0200: TABELA DE IDENTIFICAÇÃO DO ITEM
 * @param string $letra - empresa e ano e mês base 
 * @return boolean operação ocorreu normalmente ou não
 */
public function reg200Produto(){
    
try {
    $remessa = $this->select_produto_letra();
    $teste_array = is_array($remessa);

    if (isset($teste_array)){
        $this->dadosOK = '0';
        
        // registro tipo 0200
        for ($i=0; $i < count($remessa); $i++){
            $this->numRegistro++;
            
            $transacaoWrite = "|0200";
            $transacaoWrite .= "|".$remessa[$i]['CODIGO'];
            $desc = $this->removeAcentos($remessa[$i]['DESCRICAO']);
            $transacaoWrite .= "|".$desc;
            $transacaoWrite .= "|".$remessa[$i]['CODIGOBARRAS'];
            $transacaoWrite .= "|";
            $transacaoWrite .= "|".$remessa[$i]['UNIDADE'];
            $transacaoWrite .= "|00";
            $transacaoWrite .= "|".$remessa[$i]['NCM'];
            $transacaoWrite .= "|";
            $transacaoWrite .= "|30";
            $transacaoWrite .= "|"; // serviço
            $transacaoWrite .= "|0,00"; // ICMS
            $transacaoWrite .= "|".$remessa[$i]['CEST']."|";
            
            // grava arquivo txt
            fwrite($this->wh, $transacaoWrite."\n");
            
        } // for
        

    } // if
    else {
       return 'Não existe boletos para enviar remessa bancária!!';
    }
    echo "Total Registros:-->".$numCartao;
    
} catch (Exception $ex) {
    $this->mostraBlocoK($ex);
}
//$this->mostraBlocoK($file_target, $banco);
} //fim remessa Produto

 /**
 * @name regBlocoH
 * @description BLOCO H: INVENTÁRIO FÍSICO
 * @param string $letra - empresa e ano e mês base 
 * @return boolean operação ocorreu normalmente ou não
 */
public function regBlocoH($dataIni, $dataFim){
    
try {
    $produtoQtda = new c_produto_estoque;
    $this->remessa = $produtoQtda->produtoQtdeData($this->m_letra);
    $teste_array = is_array($this->remessa);

    if (isset($teste_array)){
        $remessa = $this->remessa;
        $this->dadosOK = '0';
        // header bloco H com movimento
        $transacaoHeader = "|H001|0|";
        fwrite($this->wh, $transacaoHeader."\n");

        // REGISTRO H005: TOTAIS DO INVENTÁRIO
        $valorItem = 0;
        $totalGeral = 0;
        for ($i=0; $i < count($remessa); $i++){
            $quant = ($remessa[$i]['ENTRADA1'] + $remessa[$i]['ENTRADA2'] + $remessa[$i]['RESERVADO']) - ($remessa[$i]['SAIDA']+$remessa[$i]['PERDA']);
            if ($quant > 0):
                $quant = ($remessa[$i]['ENTRADA1'] + $remessa[$i]['ENTRADA2'] + $remessa[$i]['RESERVADO']) - ($remessa[$i]['SAIDA']+$remessa[$i]['PERDA']);
                $valorUnitario = $remessa[$i]['CUSTOCOMPRA'];
                $valorItem = ($quant*$valorUnitario);
                $totalGeral += $valorItem;
            endif;
        } // for
        $transacaoHeaderH005 = "|H005|".$dataFim."|";
        $transacaoHeaderH005 .= "|".number_format((float)$totalGeral,2, ',','');
        $transacaoHeaderH005 .= "|01|";
        fwrite($this->wh, $transacaoHeaderH005."\n");

        $numRegistro=2;
        $quantTotal = 0;
        // REGISTRO H010: INVENTÁRIO.
        for ($i=0; $i < count($remessa); $i++){
            $quant = ($remessa[$i]['ENTRADA1'] + $remessa[$i]['ENTRADA2'] + $remessa[$i]['RESERVADO']) - ($remessa[$i]['SAIDA']+$remessa[$i]['PERDA']);
            $valorUnitario = $remessa[$i]['CUSTOCOMPRA'];
            $valorItem = ($quant*$valorUnitario);
            if ($quant > 0):
                $numRegistro++;
                $transacaoWrite = "|H010";
                $transacaoWrite .= "|".$remessa[$i]['CODIGO'];
                $transacaoWrite .= "|".$remessa[$i]['UNIDADE'];
                $transacaoWrite .= "|".number_format((float)$quant,3, ',','');
                $transacaoWrite .= "|".number_format((float)$valorUnitario,2, ',','');
                $transacaoWrite .= "|".number_format((float)$valorItem,2, ',','');
                $transacaoWrite .= "|0";
                $transacaoWrite .= "|";
                $transacaoWrite .= "|";
                $transacaoWrite .= "|0";
                $transacaoWrite .= "|0|";

                // grava arquivo txt
                fwrite($this->wh, $transacaoWrite."\n");
            endif;
            
        } // for
        $numRegistro++;
        $transacaoHeader = "|H990|".$numRegistro."|";
        fwrite($this->wh, $transacaoHeader."\n");
        
    } // if
    else {
       // header bloco K sem movimento
       $transacaoHeader = "|H001|1|";
       fwrite($this->wh, $transacaoHeader."\n");
       // finalização bloco K
        $transacaoHeader = "|H990|0|";
        fwrite($this->wh, $transacaoHeader."\n");
       return 'Não existe boletos para enviar remessa bancária!!';
    }
    echo "Total Registros:-->".$numCartao;
    
} catch (Exception $ex) {
    $this->mostraBlocoK($ex.message);
}
//$this->mostraBlocoK($file_target, $banco);
} //fim remessa ESTOQUE BLOCO H

    
 /**
 * @name regBlocoK
 * @description BLOCO K: CONTROLE DA PRODUÇÃO E DO ESTOQUE
 * @param string $letra - empresa e ano e mês base 
 * @return boolean operação ocorreu normalmente ou não
 */
public function regBlocoK($dataIni, $dataFim){
    
try {
    $teste_array = is_array($this->remessa);

    if (isset($teste_array)){
        $remessa = $this->remessa;
        $this->dadosOK = '0';
        // header bloco K com movimento
        $transacaoHeader = "|K001|0|";
        fwrite($this->wh, $transacaoHeader."\n");

        // bloco K100
        $transacaoHeaderk100 = "|K100|".$dataIni."|".$dataFim."|";
        fwrite($this->wh, $transacaoHeaderk100."\n");

        $numRegistro=2;
        $quantTotal = 0;
        // registro tipo 1 - transacao
        for ($i=0; $i < count($remessa); $i++){
            $quant = ($remessa[$i]['ENTRADA1'] + $remessa[$i]['ENTRADA2'] + $remessa[$i]['RESERVADO']) - ($remessa[$i]['SAIDA']+$remessa[$i]['PERDA']);
            if ($quant > 0):
                $numRegistro++;
                $transacaoWrite = "|K200";
                $transacaoWrite .= "|".$dataFim;
                $transacaoWrite .= "|".$remessa[$i]['CODIGO'];
                $transacaoWrite .= "|".number_format((float)$quant,3, ',','');
                $transacaoWrite .= "|0";
                $transacaoWrite .= "||";

                // grava arquivo txt
                fwrite($this->wh, $transacaoWrite."\n");
            endif;
            
        } // for
        $numRegistro++;
        $transacaoHeader = "|K990|".$numRegistro."|";
        fwrite($this->wh, $transacaoHeader."\n");
        
    } // if
    else {
       // header bloco K sem movimento
       $transacaoHeader = "|K001|1|";
       fwrite($this->wh, $transacaoHeader."\n");
       // finalização bloco K
        $transacaoHeader = "|K990|0|";
        fwrite($this->wh, $transacaoHeader."\n");
       return 'Não existe boletos para enviar remessa bancária!!';
    }
    echo "Total Registros:-->".$numCartao;
    
} catch (Exception $ex) {
    $this->mostraBlocoK($ex.message);
}
//$this->mostraBlocoK($file_target, $banco);
} //fim remessa ESTOQUE


 /**
 * @name controleSecao
 * @description chama funções que gravar no mesmo arquivo varias seções do bloco K
 * @param string $letra - empresa e ano e mês base 
 * @return boolean operação ocorreu normalmente ou não
 */
public function controleSecao(){
    
try {
    $par = explode("|", $this->m_letra);
    $arrData = explode("-", str_replace("/", "",$par[0]));
    $dataIni = trim($arrData[0]);
    $dataFim = trim($arrData[1]);

    $file_target = '';

    //Arquivo remessa
    $path = ADMraizCliente."/file/sped/"; 
    $filename = "blocok". substr($dataFim, 4, 4).substr($dataFim, 2, 2).".txt"; // ano dia
    $file_target = $path.$filename;

    // cria arquivo
    $this->wh = fopen($file_target, 'w+');
    if ( !$this->wh ) {
        throw new Exception( "Erro ao gerar arquivo de remessa - ".$php_errormsg );
    }

    // Abertura bloco O
    $this->aberturaBloco0($dataIni, $dataFim);
    $this->reg200Produto();
    $this->regBlocoH($dataIni, $dataFim);
    $this->regBlocoK($dataIni, $dataFim);
    

    //BLOCO 9: CONTROLE E ENCERRAMENTO DO ARQUIVO DIGITAL
    $transacaoHeader = "|9001|".$this->dadosOK."|";
    fwrite($this->wh, $transacaoHeader."\n");
    
    
    echo "Total Registros:-->".$numCartao;
    // fecha arquivo texto.
    fclose($this->wh); // No error
    
    return $file_target;

} catch (Exception $ex) {
    $this->mostraBlocoK($ex);
}
//$this->mostraBlocoK($file_target, $banco);
} //fim controleSecao



//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraBlocoK($file){

    
    $par = explode("|", $this->m_letra);
    $arrData = explode("-", $par[0]);


    if (($this->m_letra != '') and ($file=='')):
      $emailFile = $this->controleSecao();
    endif;

	
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('saldoInicial', $saldoTotal);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);
    $this->smarty->assign('arquivo', ADMhttpCliente. "/remessa/blocok/".date("Y")."/".basename($file));
    //$this->smarty->assign('arquivo', $file);
    $this->smarty->assign('nomeArq', basename($file));
    $this->smarty->assign('lanc', $lanc);

    $this->smarty->assign('label', $arrLabel);
    
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

    
    
    $this->smarty->display('remessa_bloco_k_mostra.tpl');
	

} //fim mostrasituacaos

public function enviarFile($email, $title, $body, $cc=null, $fileName01 = null) {
        
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
        
        $result = $mail->SendMail($this->m_configsmtp, $this->m_configemail, "Bloco K", $this->m_configemailsenha, 
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

//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$remessa_blocok = new p_remessa_blocok();


$remessa_blocok->controle();
 
  
?>
