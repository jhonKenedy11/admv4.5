<?php

/**
 * @package   astecv3
 * @name      p_retorno_bancario_confere
 * @version   3.0.00
 * @copyright 2018
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      02/04/2018
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
//include_once($dir."/../../class/fin/c_saldo.php");
include_once($dir."/../../class/fin/c_conta_banco.php");
include_once($dir."/../../class/fin/c_lancamento.php");


//Class P_RETORNO_BANCARIA
Class p_retorno_bancario_confere extends c_lancamento {

private $m_submenu = NULL;
private $m_letra = NULL;
private $m_lanc = NULL;
public $smarty = NULL;
public $m_name = NULL;
public $m_tmp = NULL;
public $m_type = NULL;
public $m_size = NULL;


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
        $this->arqFile = isset($parmPost['filePesquisa']) ? $parmPost['filePesquisa'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Conferencia Titulos Recebidos");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16  ]"); 
        $this->smarty->assign('disableSort', "[ 0 ]"); 
        $this->smarty->assign('numLine', "50"); 

        // include do javascript
        // include ADMjs . "/fin/s_fin.js";

}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
  switch ($this->m_submenu){
        case 'retorno':
            $this->mostraRetorno('','B');
            break;
        default:
            $this->mostraRetorno('','P');
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
 * @name remessaBancaria
 * @description gera arquivo de remessa para o banco correspondente com titulos em aberto do tipo boleto
 * @param int $banco - banco a ser gerado o arquivo de remessa
 * @return int $count - numero de parcelas geradas
 */
public function procRetorno(){
    
try {
    $lanc = json_decode($this->jsonLanc, true);
    
    echo ('JSON lancamento: '.print_r($this->jsonLanc));
    echo ('lancamento: '.var_dump($lanc));
    $this->mostraRetorno('Processamento OK','P');
    
} catch (Exception $ex) {
    $this->mostraRetorno($ex);
}
} //fim remessaBancaria



//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraRetorno($mensagem, $retorno=null){

    $par = explode("|", $this->m_letra);


   if ($this->m_letra != ''):
        $path = ADMraizCliente."/cobranca/banco/237/retorno/";
        $f_name = $_FILES['fileArq']['name'];
        $f_tmp = $_FILES['fileArq']['tmp_name'];
        $f_type = $_FILES['fileArq']['type'];
        $uploadfile = $path. $f_name;
    endif;
    $lanc = [];
    if (file_exists($f_tmp)):
        //ABRE O ARQUIVO TXT
        $ponteiro = fopen ($f_tmp,"r");

        // HEADER

        $i = 0;
        $linha = fgets($ponteiro);
        $dataGravaArq = substr($linha, 94,2).'-'.substr($linha, 96,2).'-'.substr($linha, 98,2);
    //LÊ O ARQUIVO ATÉ  CHEGAR AO FIM 
        while (!feof ($ponteiro)) {
         
          $ocorrencia = substr($linha, 5,2);
          if ($ocorrencia == '09'):
              $lanc[$i]['nn'] = substr($linha, 22,11);
              $lanc[$i]['dataVencimento'] = substr($linha, 36,2).'-'.substr($linha, 39,2).'-'.substr($linha, 42,4);
              $lanc[$i]['seuNumero'] = substr($linha, 47,10);
              $lanc[$i]['dataPagamento'] = substr($linha, 110,2).'-'.substr($linha, 113,2).'-'.substr($linha, 116,4);
              $lanc[$i]['valorRemessa'] = substr($linha, 142,11).'.'.substr($linha, 154,2);;
              $lanc[$i]['valorPago'] = substr($linha, 162,11).'.'.substr($linha, 173,2);;
              $lanc[$i]['valorOscilacao'] = substr($linha, 180,11).','.substr($linha, 192,3);

              $arrLanc = $this->select_lancamento_nossonumero($lanc[$i]['nn']);
              if (is_array($arrLanc)):
                  $lanc[$i]['idSis'] = $arrLanc[0]['ID'];
                  $lanc[$i]['nnSis'] = $arrLanc[0]['NOSSONUMERO'];
                  $lanc[$i]['nf'] = $arrLanc[0]['DOCTO'];
                  $lanc[$i]['serie'] = $arrLanc[0]['SERIE'];
                  $lanc[$i]['parcela'] = $arrLanc[0]['PARCELA'];
                  $this->setDocto($arrLanc[0]['DOCTO']);
                  $this->setSerie($arrLanc[0]['SERIE']);
                  $this->setParcela($arrLanc[0]['PARCELA']);
                  switch ($arrLanc[0]['SITPGTO']):
                      case 'A':
                          $lanc[$i]['situacao'] = 'Aberto';
                          break;
                      case 'B':
                          $lanc[$i]['situacao'] = 'Baixado';
                          break;
                      case 'C':
                          $lanc[$i]['situacao'] = 'Cancelado';
                          break;
                      case 'N':
                          $lanc[$i]['situacao'] = 'Não Autoriado';
                          break;
                      default:
                          $lanc[$i]['situacao'] = 'Não Localizado';
                  endswitch;
                  $lanc[$i]['dataEmissao'] = $arrLanc[0]['EMISSAO'];
                  $lanc[$i]['dataVencimentoSis'] = $arrLanc[0]['VENCIMENTO'];
                  $lanc[$i]['dataRecebimento'] = $arrLanc[0]['PAGAMENTO'];
                  $lanc[$i]['valorOriginal'] = $arrLanc[0]['ORIGINAL'];
                  $lanc[$i]['valorRecebido'] = $arrLanc[0]['TOTAL'];
                  $lanc[$i]['diferenca'] = $lanc[$i]['valorRecebido'] - $lanc[$i]['valorOriginal'];

                  // consulta se existe registros com mesmo numero de documento
                  $this->setPessoa($arrLanc[0]['PESSOA']);
                  $this->setDocto($arrLanc[0]['DOCTO']);
                  $this->setSerie($arrLanc[0]['SERIE']);
                  $this->setParcela($arrLanc[0]['PARCELA']);
                  $arrDoc = $this->existeDocumento(TRUE);
                  if (is_array($arrDoc)):
                        $idSis = $lanc[$i]['idSis'];
                        for ($d=0; $d < count($arrDoc); $d++):
                              if  ($idSis <> $arrDoc[$d]['ID']):
                                  $i++;
                                  $lanc[$i]['nn'] = '';
                                  $lanc[$i]['dataVencimento'] = '';
                                  $lanc[$i]['seuNumero'] = '';
                                  $lanc[$i]['dataPagamento'] = '';
                                  $lanc[$i]['valorRemessa'] = '';
                                  $lanc[$i]['valorPago'] = '';
                                  $lanc[$i]['valorOscilacao'] = '';

                                  $lanc[$i]['idSis'] = $arrDoc[$d]['ID'];
                                  $lanc[$i]['nnSis'] = $arrDoc[$d]['NOSSONUMERO'];
                                  $lanc[$i]['nf'] = $arrDoc[$d]['DOCTO'];
                                  $lanc[$i]['serie'] = $arrDoc[$d]['SERIE'];
                                  $lanc[$i]['parcela'] = $arrDoc[$d]['PARCELA'];
                                  $this->setDocto($arrDoc[$d]['DOCTO']);
                                  $this->setSerie($arrDoc[$d]['SERIE']);
                                  $this->setParcela($arrDoc[$d]['PARCELA']);
                                  switch ($arrDoc[$d]['SITPGTO']):
                                      case 'A':
                                          $lanc[$i]['situacao'] = 'Aberto';
                                          break;
                                      case 'B':
                                          $lanc[$i]['situacao'] = 'Baixado';
                                          break;
                                      case 'C':
                                          $lanc[$i]['situacao'] = 'Cancelado';
                                          break;
                                      case 'N':
                                          $lanc[$i]['situacao'] = 'Não Autoriado';
                                          break;
                                      default:
                                          $lanc[$i]['situacao'] = 'Não Localizado';
                                  endswitch;
                                  $lanc[$i]['dataEmissao'] = $arrDoc[$d]['EMISSAO'];
                                  $lanc[$i]['dataVencimentoSis'] = $arrDoc[$d]['VENCIMENTO'];
                                  $lanc[$i]['dataRecebimento'] = $arrDoc[$d]['PAGAMENTO'];
                                  $lanc[$i]['valorOriginal'] = $arrDoc[$d]['ORIGINAL'];
                                  $lanc[$i]['valorRecebido'] = $arrDoc[$d]['TOTAL'];
                                  $lanc[$i]['diferenca'] = $lanc[$i]['valorRecebido'] - $lanc[$i]['valorOriginal'];
                                  
                              endif;
                        endfor;
                  endif;
                  
              endif;
              // incrementa
              $i++;
          endif;
          //LÊ UMA  LINHA DO ARQUIVO
          $linha = fgets($ponteiro);
        }//FECHA WHILE

        //FECHA O PONTEIRO DO ARQUIVO
        fclose ($ponteiro);
        
    endif;
	
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('saldoInicial', $saldoTotal);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);
    $this->smarty->assign('filePesquisa', $f_name);
    $this->smarty->assign('lanc', $lanc);
    $this->smarty->assign('retorno', $retorno);
    //print_r($lanc);
    //$arr = array();
    //for ($i=0; $i < count($lanc); $i++){
    //    $arr[] = $lanc[$i]; 
    //}    
    //$out = json_encode($lanc);
    //print_r($out);
    
    //$this->smarty->assign('jsonLanc', serialize($lanc));
    $this->smarty->assign('dataGravaArq', $dataGravaArq);

    $this->smarty->assign('label', $arrLabel);
    $this->smarty->assign('pag', $arrPag);
    $this->smarty->assign('rec', $arrRec);
    
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
    if($par[0] == "") $this->smarty->assign('filial_id', $this->m_empresacentrocusto);
    else $this->smarty->assign('filial_id', $par[0]);

    $msg .= "Selecione o arquivo de RETORNO: ".$f_name." para conferencia.";
    $this->smarty->assign('mensagem', $msg);
    
    
    $this->smarty->display('retorno_bancario_confere_mostra.tpl');
	

} //fim mostrasituacaos
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$retorno_bancario_confere = new p_retorno_bancario_confere();

$retorno_bancario_confere->controle();
 
  
?>
