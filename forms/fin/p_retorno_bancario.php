<?php

/**
 * @package   astecv3
 * @name      p_retorno_bancario
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


//Class P_REMESSA_BANCARIA
Class p_retorno_bancario extends c_lancamento {

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
        $this->m_banco = isset($parmPost['banco']) ? $parmPost['banco'] : '';
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Retorno Bancario");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9  ]"); 
        $this->smarty->assign('disableSort', "[ 0 ]"); 
        $this->smarty->assign('numLine', "50"); 

        // include do javascript
        // include ADMjs . "/fin/s_fin.js";

}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
    $banco = $this->m_banco;
    $function_name = 'mostraRetorno';
    switch ($this->m_submenu){
        case 'retorno':
            if ($this->verificaDireitoUsuario('FinCobrancaRetorno', 'S')) {
                $this->$function_name('', 'B');
            }    
            break;
        default:
            if ($this->verificaDireitoUsuario('FinCobrancaRetorno', 'C')) {
                $this->$function_name('', 'P');
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
// retorno bradesco
//---------------------------------------------------------------
function processaRetorno237($ponteiro){

    $par = explode("|", $this->m_letra);
    $lanc = [];
        // HEADER
        $codContaBanco = 0;
        $carteiraConta = 0;

        $i = 0;
        $linha = fgets($ponteiro);
    //LÊ O ARQUIVO ATÉ  CHEGAR AO FIM 
        while (!feof ($ponteiro)) {
         
            $ocorrencia = substr($linha, 0,1);
            switch ($ocorrencia){
              case 0:
                $lancHeader[0]['ID'] = substr($linha, 0,1);
                $lancHeader[0]['IDArqRetorno'] = substr($linha, 1,1);
                $lancHeader[0]['literalRetorno'] = substr($linha, 2,7);
                $lancHeader[0]['codServico'] = substr($linha, 9,2);
                $lancHeader[0]['literalServico'] = substr($linha, 11,15);
                $lancHeader[0]['codEmpresa'] = substr($linha, 26,20);
                $codEmpresaBanco = intval(substr($linha, 26,20));
                $lancHeader[0]['nomeEmpresa'] = substr($linha, 46,30);
                $lancHeader[0]['numBanco'] = substr($linha, 76,3);
                $lancHeader[0]['nomeBanco'] = substr($linha, 79,15);
                $lancHeader[0]['dataArq'] = substr($linha, 94,2)."-".substr($linha, 96,2)."-20".substr($linha, 98,2);
                $lancHeader[0]['densGravacao'] = substr($linha, 100,8);
                $lancHeader[0]['numAviso'] = substr($linha, 190,5);
                $lancHeader[0]['dataCredito'] = substr($linha, 379,6);
                $lancHeader[0]['numSeq'] = substr($linha, 394,6);
                
                // conta bancaria
                $consulta = new c_banco();
                $sql = "select conta as id, nomeinterno as descricao, numnobanco, banco, carteira from fin_conta  where status ='A'";
                $consulta->exec_sql($sql);
                $consulta->close_connection();
                $result = $consulta->resultado;
                if($par[1] == "") $this->smarty->assign('conta_id', '');
                else $this->smarty->assign('conta_id', $par[1]);
                for ($c=0; $c < count($result); $c++){
                        $conta_ids[$c] = $result[$c]['ID'];
                        $conta_names[$c] = ucwords(strtolower($result[$c]['DESCRICAO']));
                        $numBanco = intval($result[$c]['NUMNOBANCO']);
                        $banco = intval($result[$c]['BANCO']);
                        if ($numBanco == $codEmpresaBanco){
                            $this->smarty->assign('conta_id', $result[$c]['ID']);
                            $codContaBanco = $result[$c]['ID'];
                            $carteiraConta = $result[$c]['CARTEIRA'];}
                }
                $this->smarty->assign('conta_ids', $conta_ids);
                $this->smarty->assign('conta_names', $conta_names);
                
                
                  break;
              case 1:
                  $arrLanc = '';
                  $lanc[$i]['idTituloBanco'] = substr($linha, 70,11);
                  if ($carteiraConta == 04) { // importação 99
                        // // nossonumero = idTituloBanco
                        if ($lanc[$i]['idTituloBanco'] !='')
                            $arrLanc = $this->select_lancamento_nossonumero($lanc[$i]['idTituloBanco'], $codContaBanco);
                    }else{
                        // ID = idTituloBanco
                        // $lanc[$i]['idTituloBanco'] = substr($linha, 37,25);
                        $id = substr($linha, 37,25);
                        $id = trim($id);
                        if ($id !='') {
                            $this->setId($id);
                            $arrLanc = $this->select_lancamento(); 
                        }    
                    }
                    // ID = idTituloBanco
                    // $lanc[$i]['idTituloBanco'] = substr($linha, 37,25);
                    //** */$this->setId(substr($linha, 37,25));
                    //** */$arrLanc = $this->select_lancamento(); 
                // **************** buscar nosso numero mais conta
                  if (is_array($arrLanc)):
                      $lanc[$i]['nf'] = $arrLanc[0]['DOCTO'].$arrLanc[0]['SERIE'].$arrLanc[0]['PARCELA'];
                      $lanc[$i]['id'] = $arrLanc[0]['ID'];
                      $lanc[$i]['sitant'] = $arrLanc[0]['SITPGTO'];
                      $lanc[$i]['total'] = $arrLanc[0]['TOTAL'];
                  else:    
                      $lanc[$i]['nf'] = 'não localizado';
                      $lanc[$i]['id'] = '0';
                      $lanc[$i]['total'] = '0';
                  endif;
                  $lanc[$i]['tipoIncr'] = substr($linha, 1,2);
                  $lanc[$i]['numIncr'] = substr($linha, 3,14);
                  $lanc[$i]['idEmpBeneficiaria'] = substr($linha, 20,17);
                  $lanc[$i]['numControle'] = substr($linha, 37,25); // código interno
                  $lanc[$i]['idRateio'] = substr($linha, 104,1); 
                  $lanc[$i]['pagParcial'] = substr($linha, 105,2); 
                  $lanc[$i]['carteira'] = substr($linha, 107,1); 
                  $lanc[$i]['numOcorrencia'] = substr($linha, 108,2); // numero da ocorrencia
                  switch ($lanc[$i]['numOcorrencia']):
                    case '02':
                        $lanc[$i]['descOcorrencia'] = 'Entrada Confirmada';
                        break;
                    case '03':
                        $lanc[$i]['descOcorrencia'] = 'Entrada Rejeitada';
                        break;
                    case '06':
                        $lanc[$i]['descOcorrencia'] = 'Liquidação  normal';
                        break;
                    case '09':
                        $lanc[$i]['descOcorrencia'] = 'Baixado Automat. via Arquivo';
                        break;
                    case '10':
                        $lanc[$i]['descOcorrencia'] = 'Baixado conforme instruções da Agência(entrar em contato com a agencia)';
                        break;
                    case '11':
                        $lanc[$i]['descOcorrencia'] = 'Em Ser - Arquivo de Títulos pendentes (sem motivo)';
                        break;
                    case '12':
                        $lanc[$i]['descOcorrencia'] = 'Abatimento Concedido';
                        break;
                    case '13':
                        $lanc[$i]['descOcorrencia'] = 'Abatimento Cancelado';
                        break;
                    case '14':
                        $lanc[$i]['descOcorrencia'] = 'Vencimento Alterado';
                        break;
                    case '15':
                        $lanc[$i]['descOcorrencia'] = 'Liquidação em Cartório';
                        break;
                    case '16':
                        $lanc[$i]['descOcorrencia'] = 'Título Pago em Cheque – Vinculado';
                        break;
                    case '17':
                        $lanc[$i]['descOcorrencia'] = 'Liquidação após baixa ou Título não registrado';
                        break;
                    case '18':
                        $lanc[$i]['descOcorrencia'] = 'Acerto de Depositária';
                        break;
                    case '19':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação Receb. Inst. de Protesto';
                        break;
                    case '20':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação Recebimento Instrução Sustação de Protesto';
                        break;
                    case '21':
                        $lanc[$i]['descOcorrencia'] = 'Acerto do Controle do Participante';
                        break;
                    case '22':
                        $lanc[$i]['descOcorrencia'] = 'Título Com Pagamento Cancelado';
                        break;
                    case '23':
                        $lanc[$i]['descOcorrencia'] = 'Entrada do Título em Cartório';
                        break;
                    case '24':
                        $lanc[$i]['descOcorrencia'] = 'Entrada rejeitada por CEP Irregular';
                        break;
                    case '25':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação Receb.Inst.de Protesto Falimentar';
                        break;
                    case '27':
                        $lanc[$i]['descOcorrencia'] = 'Baixa Rejeitada';
                        break;
                    case '28':
                        $lanc[$i]['descOcorrencia'] = 'Débito de tarifas/custas';
                        break;
                    case '29':
                        $lanc[$i]['descOcorrencia'] = 'Ocorrências do Pagador';
                        break;
                    case '30':
                        $lanc[$i]['descOcorrencia'] = 'Alteração de Outros Dados Rejeitados';
                        break;
                    case '32':
                        $lanc[$i]['descOcorrencia'] = 'Instrução Rejeitada';
                        break;
                    case '33':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação Pedido Alteração Outros Dados';
                        break;
                    case '34':
                        $lanc[$i]['descOcorrencia'] = 'Retirado de Cartório e Manutenção Carteira';
                        break;
                    case '35':
                        $lanc[$i]['descOcorrencia'] = 'Desagendamento do débito automático';
                        break;
                    case '40':
                        $lanc[$i]['descOcorrencia'] = 'Estorno de pagamento';
                        break;
                    case '55':
                        $lanc[$i]['descOcorrencia'] = 'Sustado judicial';
                        break;
                    case '68':
                        $lanc[$i]['descOcorrencia'] = 'Acerto dos dados do rateio de Crédito';
                        break;
                    case '69':
                        $lanc[$i]['descOcorrencia'] = 'Cancelamento dos dados do rateio';
                        break;
                    case '073':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação Receb. Pedido de Negativação';
                        break;
                    case '074':
                        $lanc[$i]['descOcorrencia'] = 'Confir Pedido de Excl de Negat';
                        break;
                  endswitch;
                  $lanc[$i]['dataOcorrencia'] = substr($linha, 110,2)."-".substr($linha, 112,2)."-".substr($linha, 114,2);
                  $lanc[$i]['dataOcorrenciaBD'] = substr($linha, 114,2)."-".substr($linha, 112,2)."-".substr($linha, 110,2);
                  $lanc[$i]['numDoc'] = substr($linha, 116,10); 
                  $lanc[$i]['idTituloBanco2'] = substr($linha, 126,20); 
                  $data = substr($linha, 146,6); 
                  $lanc[$i]['dataVencimento'] = substr($linha, 146,2)."-".substr($linha, 148,2)."-".substr($linha, 150,2); 
                  $lanc[$i]['dataVencimentoBD'] = substr($linha, 150,2)."-".substr($linha, 148,2)."-".substr($linha, 146,2); 
                  $lanc[$i]['valorTitulo'] = substr($linha, 152,11).".".substr($linha, 163,2);
                  $lanc[$i]['bancoCobrador'] = substr($linha, 165,3); 
                  $lanc[$i]['agenciaCobrador'] = substr($linha, 168,5); 
                  $lanc[$i]['despesaCobranca'] = substr($linha, 175,11).".".substr($linha, 186,2);
                  $lanc[$i]['outrasDespesa'] = substr($linha, 188,11).".".substr($linha, 199,2);
                  $lanc[$i]['jurosAtraso'] = substr($linha, 201,11).".".substr($linha, 212,2);
                  $lanc[$i]['iof'] = substr($linha, 214,11).".".substr($linha, 225,2);
                  $lanc[$i]['abatimento'] = substr($linha, 227,11).".".substr($linha, 238,2);
                  $lanc[$i]['desconto'] = substr($linha, 240,11).".".substr($linha, 251,2);
                  $lanc[$i]['valorPago'] = substr($linha, 253,11).".".substr($linha, 264,2); 
                  $lanc[$i]['juros'] = substr($linha, 266,11).".".substr($linha, 277,2);
                  $lanc[$i]['outroCredito'] = substr($linha, 279,11).".".substr($linha, 290,2);
                  $lanc[$i]['motivoCodOcorrencia'] = substr($linha, 294,1); 
                  $data = substr($linha, 295,6); 
                  $lanc[$i]['dataCredito'] = substr($linha, 295,2)."-".substr($linha, 297,2)."-".substr($linha, 299,2); 
                  $lanc[$i]['dataCreditoBD'] = substr($linha, 299,2)."-".substr($linha, 297,2)."-".substr($linha, 295,2); 
                  $lanc[$i]['cheque'] = substr($linha, 314,4); 
                  $lanc[$i]['motivoRejeicao'] = substr($linha, 318,10); 
                  $lanc[$i]['numCartorio'] = substr($linha, 368,2); 
                  $lanc[$i]['numProtocolo'] = substr($linha, 370,10); 
                  $lanc[$i]['numSeq'] = substr($linha, 394,6);
                  // incrementa
                  $i++;
                  break;
              case 9:
                    $lancTrailler[0]['ID'] = substr($linha, 0,1);
                    $lancTrailler[0]['banco'] = substr($linha, 4,3);
                    $lancTrailler[0]['quantTitulosCob'] = substr($linha, 17,8);
                    $lancTrailler[0]['valorTotalCob'] = substr($linha, 25,12).".".substr($linha, 37,2);
                    $lancTrailler[0]['numAviso'] = substr($linha, 39,8);
                    $lancTrailler[0]['quantReg02'] = substr($linha, 57,5);
                    $lancTrailler[0]['valorReg02'] = substr($linha, 62,10).".".substr($linha, 72,2);
                    $lancTrailler[0]['valorReg06Liquidacao'] = substr($linha, 74,10).".".substr($linha, 84,2);
                    $lancTrailler[0]['quantReg06Liquidacao'] = substr($linha, 86,5);
                    $lancTrailler[0]['valorReg06'] = substr($linha, 91,10).".".substr($linha, 101,2);
                    $lancTrailler[0]['quantReg0910Baixado'] = substr($linha, 103,5);
                    $lancTrailler[0]['valorReg0910Baixado'] = substr($linha, 108,10).".".substr($linha, 118,2);
                    $lancTrailler[0]['quantReg13'] = substr($linha, 57,5);
                    $lancTrailler[0]['valorReg13'] = substr($linha, 62,10).".".substr($linha, 72,2);

                    $lancTrailler[$i]['numSeq'] = substr($linha, 394,6);
                  break;
          }
          //LÊ UMA  LINHA DO ARQUIVO
          $linha = fgets($ponteiro);
        }//FECHA WHILE

        $this->smarty->assign('lancHeader', $lancHeader);
        $this->smarty->assign('lancTrailler', $lancTrailler);
        $this->smarty->assign('lanc', $lanc);  
        return $lanc;      
} //fim processaRetorno237

//---------------------------------------------------------------
// retorno ITAU
//---------------------------------------------------------------
function processaRetorno341($ponteiro){

    $par = explode("|", $this->m_letra);
    $lanc = [];
    $total02 = 0;
    $quantReg02 = 0;
    $total06 = 0;
    $quantReg06 = 0;
    $total09 = 0;
    $quantReg09 = 0;
    $total13 = 0;
    $quantReg13 = 0;
    // HEADER

    $i = 0;
    $linha = fgets($ponteiro);
    //LÊ O ARQUIVO ATÉ  CHEGAR AO FIM 
    while (!feof ($ponteiro)) {
        
        $ocorrencia = substr($linha, 0,1);
        switch ($ocorrencia){
            case 0:
                $lancHeader[0]['ID'] = substr($linha, 0,1);
                $lancHeader[0]['IDArqRetorno'] = substr($linha, 1,1);
                $lancHeader[0]['literalRetorno'] = substr($linha, 2,7);
                $lancHeader[0]['codServico'] = substr($linha, 9,2);
                $lancHeader[0]['literalServico'] = substr($linha, 11,15);
                // $lancHeader[0]['codEmpresa'] = substr($linha, 26,20);
                $lancHeader[0]['agencia'] = substr($linha, 26,4);
                $lancHeader[0]['conta'] = substr($linha, 32,5).'-'.substr($linha, 37,1);
                $conta = $lancHeader[0]['conta'];
                $lancHeader[0]['nomeEmpresa'] = substr($linha, 46,30);
                $lancHeader[0]['numBanco'] = substr($linha, 76,3);
                $lancHeader[0]['nomeBanco'] = substr($linha, 79,15);
                $lancHeader[0]['dataArq'] = substr($linha, 94,2)."-".substr($linha, 96,2)."-20".substr($linha, 98,2);
                $lancHeader[0]['densGravacao'] = substr($linha, 100,8);
                $lancHeader[0]['dataCredito'] = substr($linha, 113,2)."-".substr($linha, 115,2)."-20".substr($linha, 117,2);
                //$lancHeader[0]['numAviso'] = substr($linha, 190,5);
                //$lancHeader[0]['dataCredito'] = substr($linha, 379,6);
                $lancHeader[0]['numSeq'] = substr($linha, 394,6);
                
                // conta bancaria
                $consulta = new c_banco();
                $sql = "select conta as id, nomeinterno as descricao, numnobanco, banco, contacorrente from fin_conta  where status ='A'";
                $consulta->exec_sql($sql);
                $consulta->close_connection();
                $result = $consulta->resultado;
                if($par[1] == "") $this->smarty->assign('conta_id', '');
                else $this->smarty->assign('conta_id', $par[1]);
                for ($c=0; $c < count($result); $c++){
                        $conta_ids[$c] = $result[$c]['ID'];
                        $conta_names[$c] = ucwords(strtolower($result[$c]['DESCRICAO']));
                        $contaCorrente = intval($result[$c]['CONTACORRENTE']);
                        $banco = intval($result[$c]['BANCO']);
                        if ($contaCorrente == intval($conta)){
                            $this->smarty->assign('conta_id', $result[$c]['ID']);
                            $codContaBanco = $result[$c]['ID'];}
                }
                $this->smarty->assign('conta_ids', $conta_ids);
                $this->smarty->assign('conta_names', $conta_names);
            
                break;
            case 1:
                // nossonumero = idTituloBanco
                $lanc[$i]['numControle'] = substr($linha, 37,25); // código interno ID
                $lanc[$i]['idTituloBanco'] = substr($linha, 62,8); // Nosso Número
                // $arrLanc = $this->select_lancamento_nossonumero($lanc[$i]['idTituloBanco'], $codContaBanco); 

                $arrLanc = '';
                $lanc[$i]['idTituloBanco'] = substr($linha, 70,11);
                // ID = idTituloBanco
                // $lanc[$i]['idTituloBanco'] = substr($linha, 37,25);
                // $id = substr($linha, 37,25);
                // $id = trim($id);
                $this->setId($lanc[$i]['numControle']);
                $arrLanc = $this->select_lancamento(); 

            // **************** buscar nosso numero mais conta
                if (is_array($arrLanc)):
                    $lanc[$i]['nf'] = $arrLanc[0]['DOCTO'].$arrLanc[0]['SERIE'].$arrLanc[0]['PARCELA'];
                    $lanc[$i]['id'] = $arrLanc[0]['ID'];
                    $lanc[$i]['sitant'] = $arrLanc[0]['SITPGTO'];
                else:    
                    $lanc[$i]['nf'] = 'não localizado';
                    $lanc[$i]['id'] = '0';
                endif;
                $lanc[$i]['total'] = $arrLanc[0]['TOTAL'];
                $lanc[$i]['tipoIncr'] = substr($linha, 1,2);
                $lanc[$i]['numIncr'] = substr($linha, 3,14);
                $lanc[$i]['idEmpBeneficiaria'] = substr($linha, 20,17);
                $lanc[$i]['idRateio'] = substr($linha, 104,1); 
                $lanc[$i]['pagParcial'] = substr($linha, 105,2); 
                $lanc[$i]['carteira'] = substr($linha, 107,1); 
                $lanc[$i]['numOcorrencia'] = substr($linha, 108,2); // numero da ocorrencia
                switch ($lanc[$i]['numOcorrencia']):
                case '02':
                    $lanc[$i]['descOcorrencia'] = 'Entrada Confirmada';
                    $quantReg02 ++;
                    $total02 += $lanc[$i]['total'];
                    break;
                case '03':
                    $lanc[$i]['descOcorrencia'] = 'Entrada Rejeitada';
                    break;
                case '04':
                    $lanc[$i]['descOcorrencia'] = 'ALTERAÇÃO DE DADOS - NOVA ENTRADA OU ALTERAÇÃO/EXCLUSÃO DE DADOS ACATADA';
                    break;
                case '05':
                    $lanc[$i]['descOcorrencia'] = 'ALTERAÇÃO DE DADOS – BAIXA';
                    break;
                case '06':
                    $lanc[$i]['descOcorrencia'] = 'Liquidação  normal';
                    $quantReg06 ++;
                    $total06 += substr($linha, 253,11).".".substr($linha, 264,2); 
                    break;
                case '07':
                    $lanc[$i]['descOcorrencia'] = 'LIQUIDAÇÃO PARCIAL – COBRANÇA INTELIGENTE (B2B)';
                    break;
                case '08':
                    $lanc[$i]['descOcorrencia'] = 'LIQUIDAÇÃO EM CARTÓRIO';
                    break;
                case '09':
                    $lanc[$i]['descOcorrencia'] = 'BAIXA SIMPLES';
                    $quantReg09 ++;
                    $total09 += $lanc[$i]['total'];
                    break;
                case '10':
                    $lanc[$i]['descOcorrencia'] = 'BAIXA POR TER SIDO LIQUIDADO';
                    break;
                case '11':
                    $lanc[$i]['descOcorrencia'] = 'EM SER (SÓ NO RETORNO MENSAL)';
                    break;
                case '12':
                    $lanc[$i]['descOcorrencia'] = 'Abatimento Concedido';
                    break;
                case '13':
                    $lanc[$i]['descOcorrencia'] = 'Abatimento Cancelado';
                    $quantReg13 ++;
                    $total13 += $lanc[$i]['total'];
                    break;
                case '14':
                    $lanc[$i]['descOcorrencia'] = 'Vencimento Alterado';
                    break;
                case '15':
                    $lanc[$i]['descOcorrencia'] = 'BAIXAS REJEITADAS (NOTA 20 - TABELA 4)';
                    break;
                case '16':
                    $lanc[$i]['descOcorrencia'] = 'INSTRUÇÕES REJEITADAS (NOTA 20 - TABELA 3)';
                    break;
                case '17':
                    $lanc[$i]['descOcorrencia'] = 'ALTERAÇÃO/EXCLUSÃO DE DADOS REJEITADOS (NOTA 20 - TABELA 2)';
                    break;
                case '18':
                    $lanc[$i]['descOcorrencia'] = 'COBRANÇA CONTRATUAL - INSTRUÇÕES/ALTERAÇÕES REJEITADAS/PENDENTES (NOTA 20 - TABELA 5)';
                    break;
                case '19':
                    $lanc[$i]['descOcorrencia'] = 'CONFIRMA RECEBIMENTO DE INSTRUÇÃO DE PROTESTO';
                    break;
                case '20':
                    $lanc[$i]['descOcorrencia'] = 'CONFIRMA RECEBIMENTO DE INSTRUÇÃO DE SUSTAÇÃO DE PROTESTO /TARIFA';
                    break;
                case '21':
                    $lanc[$i]['descOcorrencia'] = 'CONFIRMA RECEBIMENTO DE INSTRUÇÃO DE NÃO PROTESTAR';
                    break;
                case '23':
                    $lanc[$i]['descOcorrencia'] = 'TÍTULO ENVIADO A CARTÓRIO/TARIFA';
                    break;
                case '24':
                    $lanc[$i]['descOcorrencia'] = 'INSTRUÇÃO DE PROTESTO REJEITADA / SUSTADA / PENDENTE (NOTA 20 - TABELA 7)';
                    break;
                case '25':
                    $lanc[$i]['descOcorrencia'] = 'ALEGAÇÕES DO SACADO (NOTA 20 - TABELA 6)';
                    break;
                case '26':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA DE AVISO DE COBRANÇA';
                    break;
                case '27':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA DE EXTRATO POSIÇÃO (B40X)';
                    break;
                case '28':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA DE RELAÇÃO DAS LIQUIDAÇÕES';
                    break;
                case '29':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA DE MANUTENÇÃO DE TÍTULOS VENCIDOS';
                    break;
                case '30':
                    $lanc[$i]['descOcorrencia'] = 'DÉBITO MENSAL DE TARIFAS (PARA ENTRADAS E BAIXAS)';
                    break;
                case '32':
                    $lanc[$i]['descOcorrencia'] = 'BAIXA POR TER SIDO PROTESTADO';
                    break;
                case '33':
                    $lanc[$i]['descOcorrencia'] = 'CUSTAS DE PROTESTO';
                    break;
                case '34':
                    $lanc[$i]['descOcorrencia'] = 'CUSTAS DE SUSTAÇÃO';
                    break;
                case '35':
                    $lanc[$i]['descOcorrencia'] = 'CUSTAS DE CARTÓRIO DISTRIBUIDOR';
                    break;
                case '36':
                    $lanc[$i]['descOcorrencia'] = 'CUSTAS DE EDITAL';
                    break;
                case '37':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA DE EMISSÃO DE BOLETO/TARIFA DE ENVIO DE DUPLICATA';
                    break;
                case '38':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA DE INSTRUÇÃO';
                    break;
                case '39':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA DE OCORRÊNCIAS';
                    break;
                case '40':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA MENSAL DE EMISSÃO DE BOLETO/TARIFA MENSAL DE ENVIO DE DUPLICATA';
                    break;
                case '41':
                    $lanc[$i]['descOcorrencia'] = 'DÉBITO MENSAL DE TARIFAS – EXTRATO DE POSIÇÃO (B4EP/B4OX)';
                    break;
                case '42':
                    $lanc[$i]['descOcorrencia'] = 'DÉBITO MENSAL DE TARIFAS – OUTRAS INSTRUÇÕES';
                    break;
                case '43':
                    $lanc[$i]['descOcorrencia'] = 'DÉBITO MENSAL DE TARIFAS – MANUTENÇÃO DE TÍTULOS VENCIDOS';
                    break;
                case '44':
                    $lanc[$i]['descOcorrencia'] = 'DÉBITO MENSAL DE TARIFAS – OUTRAS OCORRÊNCIAS';
                    break;
                case '45':
                    $lanc[$i]['descOcorrencia'] = 'DÉBITO MENSAL DE TARIFAS – PROTESTO';
                    break;
                case '46':
                    $lanc[$i]['descOcorrencia'] = 'DÉBITO MENSAL DE TARIFAS – SUSTAÇÃO DE PROTESTO';
                    break;
                case '47':
                    $lanc[$i]['descOcorrencia'] = 'BAIXA COM TRANSFERÊNCIA PARA DESCONTO';
                    break;
                case '48':
                    $lanc[$i]['descOcorrencia'] = 'CUSTAS DE SUSTAÇÃO JUDICIAL';
                    break;
                case '51':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA MENSAL REF A ENTRADAS BANCOS CORRESPONDENTES NA CARTEIRA';
                    break;
                case '52':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA MENSAL BAIXAS NA CARTEIRA';
                    break;
                case '53':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA MENSAL BAIXAS EM BANCOS CORRESPONDENTES NA CARTEIRA';
                    break;
                case '54':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA MENSAL DE LIQUIDAÇÕES NA CARTEIRA';
                    break;
                case '55':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA MENSAL DE LIQUIDAÇÕES EM BANCOS CORRESPONDENTES NA CARTEIRA';
                    break;
                case '56':
                    $lanc[$i]['descOcorrencia'] = 'CUSTAS DE IRREGULARIDADE';
                    break;
                case '57':
                    $lanc[$i]['descOcorrencia'] = 'NSTRUÇÃO CANCELADA (NOTA 20 – TABELA 8)';
                    break;
                case '59':
                    $lanc[$i]['descOcorrencia'] = 'BAIXA POR CRÉDITO EM C/C ATRAVÉS DO SISPAG';
                    break;
                case '60':
                    $lanc[$i]['descOcorrencia'] = 'ENTRADA REJEITADA CARNÊ (NOTA 20 – TABELA 1)';
                    break;
                case '61':
                    $lanc[$i]['descOcorrencia'] = 'TARIFA EMISSÃO AVISO DE MOVIMENTAÇÃO DE TÍTULOS (2154)';
                    break;
                case '62':
                    $lanc[$i]['descOcorrencia'] = 'DÉBITO MENSAL DE TARIFA - AVISO DE MOVIMENTAÇÃO DE TÍTULOS (2154)';
                    break;
                case '63':
                    $lanc[$i]['descOcorrencia'] = 'TÍTULO SUSTADO JUDICIALMENTE';
                    break;
                case '64':
                    $lanc[$i]['descOcorrencia'] = 'ENTRADA CONFIRMADA COM RATEIO DE CRÉDITO';
                    break;
                case '69':
                    $lanc[$i]['descOcorrencia'] = 'CHEQUE DEVOLVIDO (NOTA 20 - TABELA 9)';
                    break;
                case '71':
                    $lanc[$i]['descOcorrencia'] = 'ENTRADA REGISTRADA, AGUARDANDO AVALIAÇÃO';
                    break;
                case '72':
                    $lanc[$i]['descOcorrencia'] = 'BAIXA POR CRÉDITO EM C/C ATRAVÉS DO SISPAG SEM TÍTULO CORRESPONDENTE';
                    break;
                case '73':
                    $lanc[$i]['descOcorrencia'] = 'CONFIRMAÇÃO DE ENTRADA NA COBRANÇA SIMPLES – ENTRADA NÃO ACEITA NA COBRANÇA CONTRATUAL';
                    break;
                case '76':
                    $lanc[$i]['descOcorrencia'] = 'CHEQUE COMPENSADO';
                    break;
                endswitch;
                $lanc[$i]['dataOcorrencia'] = substr($linha, 110,2)."-".substr($linha, 112,2)."-".substr($linha, 114,2);
                $lanc[$i]['dataOcorrenciaBD'] = substr($linha, 114,2)."-".substr($linha, 112,2)."-".substr($linha, 110,2);
                $lanc[$i]['numDoc'] = substr($linha, 116,10); 
                $lanc[$i]['idTituloBanco2'] = substr($linha, 126,20); 
                $data = substr($linha, 146,6); 
                $lanc[$i]['dataVencimento'] = substr($linha, 146,2)."-".substr($linha, 148,2)."-".substr($linha, 150,2); 
                $lanc[$i]['dataVencimentoBD'] = substr($linha, 150,2)."-".substr($linha, 148,2)."-".substr($linha, 146,2); 
                $lanc[$i]['valorTitulo'] = substr($linha, 152,13); 
                $lanc[$i]['bancoCobrador'] = substr($linha, 165,3); 
                $lanc[$i]['agenciaCobrador'] = substr($linha, 168,5); 
                $lanc[$i]['despesaCobranca'] = substr($linha, 175,13); 
                $lanc[$i]['outrasDespesa'] = 0; // ******
                $lanc[$i]['jurosAtraso'] = substr($linha, 266,13); // ******
                $lanc[$i]['iof'] = substr($linha, 214,13); 
                $lanc[$i]['abatimento'] = substr($linha, 227,13); 
                $lanc[$i]['desconto'] = substr($linha, 240,13); 
                $lanc[$i]['valorPago'] = substr($linha, 253,11).".".substr($linha, 264,2); 
                $lanc[$i]['juros'] = substr($linha, 266,13); 
                $lanc[$i]['outroCredito'] = substr($linha, 279,13); 
                $lanc[$i]['motivoCodOcorrencia'] = substr($linha, 377,2); // *****
                $data = substr($linha, 295,6);
                $lanc[$i]['dataCredito'] = substr($linha, 295,2)."-".substr($linha, 297,2)."-".substr($linha, 299,2); 
                $lanc[$i]['dataCreditoBD'] = substr($linha, 299,2)."-".substr($linha, 297,2)."-".substr($linha, 295,2); 
                $lanc[$i]['cheque'] = ''; // **** 
                $lanc[$i]['motivoRejeicao'] = substr($linha, 318,10); // ***
                $lanc[$i]['numCartorio'] = ''; // *****
                $lanc[$i]['numProtocolo'] = ''; // ****
                $lanc[$i]['numSeq'] = substr($linha, 394,6);
                // incrementa
                $i++;
                break;
            case 9:
                $lancTrailler[0]['ID'] = substr($linha, 0,1);
                $lancTrailler[0]['banco'] = substr($linha, 4,3);
                $lancTrailler[0]['quantTitulosCob'] = substr($linha, 212,8);
                $lancTrailler[0]['valorTotalCob'] = substr($linha, 220,12).".".substr($linha, 232,2);
                $lancTrailler[0]['numAviso'] = substr($linha, 39,8);
                $lancTrailler[0]['quantReg02'] = $quantReg02; //substr($linha, 57,5);
                $lancTrailler[0]['valorReg02'] = $total02;//substr($linha, 62,10).".".substr($linha, 72,2);
                $lancTrailler[0]['valorReg06Liquidacao'] = $total06;//substr($linha, 74,10).".".substr($linha, 84,2);
                $lancTrailler[0]['quantReg06Liquidacao'] = $quantReg06;//substr($linha, 86,5);
                $lancTrailler[0]['valorReg06'] = $total06; //substr($linha, 91,10).".".substr($linha, 101,2);
                $lancTrailler[0]['quantReg0910Baixado'] = $quantReg09; //substr($linha, 103,5);
                $lancTrailler[0]['valorReg0910Baixado'] = $total09;//substr($linha, 108,10).".".substr($linha, 118,2);
                $lancTrailler[0]['quantReg13'] = $quantReg13;// substr($linha, 57,5);
                $lancTrailler[0]['valorReg13'] = $total13;// substr($linha, 62,10).".".substr($linha, 72,2);

                $lancTrailler[$i]['numSeq'] = substr($linha, 394,6);
                break;
        }
        //LÊ UMA  LINHA DO ARQUIVO
        $linha = fgets($ponteiro);
    }//FECHA WHILE
    $this->smarty->assign('lancHeader', $lancHeader);
    $this->smarty->assign('lancTrailler', $lancTrailler);
    $this->smarty->assign('lanc', $lanc);  
    return $lanc;   

} //fim processaRetorno341



function processaRetorno748($ponteiro){

    $par = explode("|", $this->m_letra);
    $lanc = [];
    $total02 = 0;
    $quantReg02 = 0;
    $total06 = 0;
    $quantReg06 = 0;
    $total09 = 0;
    $quantReg09 = 0;
    $total13 = 0;
    $quantReg13 = 0;
    $quantReg28 = 0;
    $total28 = 0;
    // HEADER
    $codContaBanco = 0;
    $carteiraConta = 0;
    $carteiraRetorno = 0;

    $i = 0;

    //$linha = fgets($ponteiro);
    //LÊ O ARQUIVO ATÉ  CHEGAR AO FIM 
    while (($linha = fgets($ponteiro)) !== false) {
        
        $ocorrencia = substr($linha, 0,1);
        switch ($ocorrencia){
            case 0:
                //INICIO - FINAL - TAMANHO - CAMPO
                //001 - 001 - 001 - Identificação do Registro
                $lancHeader[0]['ID'] = substr($linha, 0,1);
                //002 - 002 - 001 - Identificação do Arquivo
                $lancHeader[0]['IDArqRetorno'] = substr($linha, 1,1);
                //003 - 009 - 007 - Literal Retorno
                $lancHeader[0]['literalRetorno'] = substr($linha, 2,7);
                //010 - 011 - 002 - Código do serviço de cobrança
                $lancHeader[0]['codServico'] = substr($linha, 9,2);
                //012 - 019 - 008 - Literal Cobranca
                $lancHeader[0]['literalServico'] = substr($linha, 11,15);
                //027 - 031 - 005 - Código do beneficiário/cedente
                $lancHeader[0]['codEmpresa'] = substr($linha, 26,5);
                $codEmpresaBanco = intval(substr($linha, 26,5));
                //SEM INFO
                $lancHeader[0]['agencia'] = null;
                //027 - 031 - 005 - Código do beneficiário/cedente
                $lancHeader[0]['conta'] = substr($linha, 26,5);
                $conta = $lancHeader[0]['conta'];
                //080 - 094 - 015 - Literal Sicredi
                $lancHeader[0]['nomeEmpresa'] = substr($linha, 79,15);
                $lancHeader[0]['numBanco'] = substr($linha, 76,3);
                $lancHeader[0]['nomeBanco'] = substr($linha, 79,15);
                $lancHeader[0]['dataArq'] = substr($linha, 100,2)."-".substr($linha, 98,2). "-" .substr($linha, 94,4);
                //SEM INFO
                $lancHeader[0]['densGravacao'] = null;
                //SEM INFO
                $lancHeader[0]['dataCredito'] = null;
                $lancHeader[0]['numSeq'] = substr($linha, 394,6);

                // conta bancaria
                $consulta = new c_banco();
                $sql = "select conta as id, nomeinterno as descricao, numnobanco, banco, contacorrente from fin_conta  where status ='A'";
                $consulta->exec_sql($sql);
                $consulta->close_connection();
                $result = $consulta->resultado;

                if($par[1] == ""){
                    $this->smarty->assign('conta_id', '');
                }else{
                    $this->smarty->assign('conta_id', $par[1]);
                }
                for ($c=0; $c < count($result); $c++){
                        $conta_ids[$c] = $result[$c]['ID'];
                        $conta_names[$c] = ucwords(strtolower($result[$c]['DESCRICAO']));
                        $contaCorrente = intval($result[$c]['CONTACORRENTE']);
                        $numBanco = intval($result[$c]['NUMNOBANCO']);
                        $banco = intval($result[$c]['BANCO']);
                        if (($numBanco == $codEmpresaBanco) and ($contaCorrente == $conta)){
                            $this->smarty->assign('conta_id', $result[$c]['ID']);
                            $codContaBanco = $result[$c]['ID'];
                        }
                }
                $this->smarty->assign('conta_ids', $conta_ids);
                $this->smarty->assign('conta_names', $conta_names);
            
                break;
            case 1:
                $arrLanc = '';
                //INICIO - FINAL - TAM - CAMPO
                //001 - 001 - 001 - Identificação do registro detalhe
                //002 = 002 - 001 - Tipo de carteira (OBS: esse campo retornará preenchido somente quando houver a ocorrência 33/H4)
                //003 - 013 - 011 - Filler
                //014 - 014 - 001 - Tipo de cobrança (A - Sicredi Cobrança com registro)
                //015 - 019 - 005 - Código do pagador na cooperativa do beneficiário
                $lanc[$i]['codigoPagadorNaCooperativa'] = trim(substr($linha, 14, 5));
                //020 - 024 - 005 - Código do pagador junto ao associado (Retornará preenchido com o código informado pelo beneficiário/cedente no arquivo de remessa)
                $lanc[$i]['codigoPagadorAssociado'] = trim(substr($linha, 19, 5));
                //025 - 025 - 001 - Boleto DDA (1 - Boleto enviado a CIP/DDA / 2 - Boleto normal)
                //026 - 047 - 022 - Filler
                //048 - 062 - 015 - Nosso número Sicredi sem edição
                $lanc[$i]['nossoNumero'] = trim(substr($linha, 47,15));
                //063 - 108 - 046 - Filler
                //109 - 110 - 002 - Ocorrência
                $lanc[$i]['numOcorrencia'] = substr($linha, 108,2); // numero da ocorrencia
                
                //111 - 116 - Data da ocorrência (Formato: DDMMAA)
                $lanc[$i]['dataOcorrencia'] = substr($linha, 110,2)."-".substr($linha, 112,2)."-".substr($linha, 114,2);
                // Formatar a data para o formato adequado para o banco de dados
                $data_objeto = DateTime::createFromFormat('d-m-y', $lanc[$i]['dataOcorrencia']);
                $lanc[$i]['dataOcorrenciaBD'] = $data_objeto->format('Y-m-d');

                //117 - 126 - 010 - Seu número
                //Nosso ID do lancamento enviado pelo arquivo de remessa
                $lanc[$i]['idTituloBanco'] = substr($linha, 116,10);
                $lanc[$i]['numControle'] = substr($linha, 116,10); // código interno
                $lanc[$i]['numDoc'] = substr($linha, 116,10); 

                //********* COMENTADO POIS O RETORNO VEM COM ZEROS A DIREITA ***************************
                //busca por id titulo ou nosso numero 
                //$idLancamento  = rtrim($lanc[$i]['idTituloBanco'], '0');
                //$this->setId($idLancamento);
                //$arrLanc = $this->select_lancamento();
                //se nao localizar pelo id, busca pelo nosso numero
                //if(!is_array($arrLanc)){
                    $barra = "/";
                    $nosso_numero = substr_replace($lanc[$i]['nossoNumero'], $barra, 2, 0);
                    $arrLanc = $this->select_lancamento_nossonumero_748($nosso_numero, $codContaBanco);
                //}

                // **************** buscar nosso numero mais conta
                if (is_array($arrLanc)){
                    $lanc[$i]['nf'] = $arrLanc[0]['DOCTO'].$arrLanc[0]['SERIE'].$arrLanc[0]['PARCELA'];
                    $lanc[$i]['id'] = $arrLanc[0]['ID'];
                    $lanc[$i]['sitant'] = $arrLanc[0]['SITPGTO'];
                    $lanc[$i]['total'] = $arrLanc[0]['TOTAL'];
                }else{
                    $lanc[$i]['nf'] = 'não localizado';
                    $lanc[$i]['id'] = '0';
                }
                /*127 - 146 - 020 - Filler (Quando tratar-se de um registro de retorno de
                                            liquidação via compensação, na posição 127-131, irá a
                                            palavra “COMPE”. Quando for liquidado pela rede
                                            Sicredi, na posição 127-132, irá o número da
                                            cooperativa de crédito/agência e o posto que realizou a
                                            liquidação do título.)*/
                //147 - 152 - 006 - Data de vencimento (Formato: DDMMAA)
                $lanc[$i]['dataVencimento'] = substr($linha, 146,2)."-".substr($linha, 148,2)."-".substr($linha, 150,2);
                $data_objeto = DateTime::createFromFormat('d-m-y', $lanc[$i]['dataVencimento']);
                $lanc[$i]['dataVencimentoBD'] = $data_objeto->format('Y-m-d');
                //153 - 165 - 013 - Valor do título
                $lanc[$i]['valorDoTitulo'] = substr($linha, 152, 13);

                //166 - 174 - 009 - Filler
                //175 - 175 - 001 - Espécie de documento
                //176 - 188 - 013 - Despesas de cobrança
                $lanc[$i]['despesaCobranca'] = substr($linha, 175,13).".".substr($linha, 186,2);
                //189 - 201- 013 - Despesas de custas de protesto
                $lanc[$i]['despesaCobranca'] = substr($linha, 188,13).".".substr($linha, 199,2);
                //202 - 227 - 026 - Filler
                //228 - 240 - 013 - Abatimento concedido
                $lanc[$i]['abatimento'] = substr($linha, 227,11).".".substr($linha, 238,2);
                //241 - 253 - 013 - Desconto concedido
                $lanc[$i]['desconto'] = substr($linha, 240,11).".".substr($linha, 251,2);
                //254 - 266 - Valor efetivamente pago
                $lanc[$i]['valorPago'] = substr($linha, 253,11).".".substr($linha, 264,2);
                $lanc[$i]['valorPago'] = $lanc[$i]['valorPago'] + $lanc[$i]['despesaCobranca'];
                //267 - 279 - 013 - Juros de mora
                $lanc[$i]['jurosAtraso'] = substr($linha, 266,11).".".substr($linha, 277,2);
                $lanc[$i]['juros'] = substr($linha, 266,11).".".substr($linha, 277,2); 
                //280 - 292 - 013 - Multa 
                $lanc[$i]['multa'] = substr($linha, 266,11).".".substr($linha, 277,2); 
                //293 - 294 - 002 - Filler
                //295 - 295 - 001 - Somente para ocorrência “19” A – Aceito / D - Desprezado
                //296 - 318 - 023 - Filler
                //319 - 328 - 010 - Motivos da ocorrência
                $lanc[$i]['motivoCodOcorrencia'] = substr($linha, 318,10);
                $lanc[$i]['motivoRejeicao'] = substr($linha, 318,10);
                //329 - 336 - 008 - Data prevista para lançamento na conta corrente (Formato: AAAAMMDD)
                $lanc[$i]['dataCredito'] = substr($linha, 328,4)."-".substr($linha, 332,2)."-".substr($linha, 334,2); 
                $lanc[$i]['dataCreditoBD'] = substr($linha, 328,4)."-".substr($linha, 332,2)."-".substr($linha, 334,2);
                //337 - 394 - 058 - Filler
                //395 - 400 - 006 - Número sequencial do registro
                $lanc[$i]['numSeq'] = substr($linha, 394,6);

                switch ($lanc[$i]['numOcorrencia']):
                    case '02':
                        $lanc[$i]['descOcorrencia'] = 'Entrada Confirmada';
                        $quantReg02 ++;
                        $total02 += $lanc[$i]['total'];
                        break;
                    case '03':
                        $lanc[$i]['descOcorrencia'] = 'Entrada Rejeitada';
                        break;
                    case '06':
                        $lanc[$i]['descOcorrencia'] = 'Liquidação  normal';
                        $quantReg06 ++;
                        $total06 += substr($linha, 253,11).".".substr($linha, 264,2); 
                        break;
                    case '07':
                        $lanc[$i]['descOcorrencia'] = 'Intenção de pagamento';
                        break;
                    case '09':
                        $lanc[$i]['descOcorrencia'] = 'Baixado automaticamente via arquivo';
                        $quantReg09 ++;
                        $total09 += $lanc[$i]['total'];
                        break;
                    case '10':
                        $lanc[$i]['descOcorrencia'] = 'Baixado conforme instruções da cooperativa';
                        break;
                    case '12':
                        $lanc[$i]['descOcorrencia'] = 'Abatimento concedido';
                        break;
                    case '13':
                        $lanc[$i]['descOcorrencia'] = 'Abatimento Cancelado';
                        $quantReg13 ++;
                        $total13 += $lanc[$i]['total'];
                        break;
                    case '14':
                        $lanc[$i]['descOcorrencia'] = 'Vencimento alterado';
                        break;
                    case '15':
                        $lanc[$i]['descOcorrencia'] = 'Liquidação em cartório';
                        break;
                    case '17':
                        $lanc[$i]['descOcorrencia'] = 'Liquidação após baixa';
                        $quantReg17 ++;
                        $total17 += $lanc[$i]['total'];
                        break;
                    case '19':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação de recebimento de instrução de protesto';
                        break;
                    case '20':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação de recebimento de instrução de sustação de protesto';
                        break;
                    case '23':
                        $lanc[$i]['descOcorrencia'] = 'Entrada de título em cartório';
                        break;
                    case '24':
                        $lanc[$i]['descOcorrencia'] = 'Entrada rejeitada por CEP irregular';
                        break;
                    case '27':
                        $lanc[$i]['descOcorrencia'] = 'Baixa rejeitada';
                        break;
                    case '28':
                        $lanc[$i]['descOcorrencia'] = 'Tarifa';
                        $quantReg28 ++;
                        $total28 += $lanc[$i]['valorPago'];
                        break;
                    case '29':
                        $lanc[$i]['descOcorrencia'] = 'Rejeição do pagador';
                        break;
                    case '30':
                        $lanc[$i]['descOcorrencia'] = 'Alteração rejeitada';
                        break;
                    case '32':
                        $lanc[$i]['descOcorrencia'] = 'Instrução rejeitada';
                        break;
                    case '33':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação de pedido de alteração de outros dados';
                        break;
                    case '34':
                        $lanc[$i]['descOcorrencia'] = 'Retirado de cartório e manutenção em carteira';
                        break;
                    case '35':
                        $lanc[$i]['descOcorrencia'] = 'Aceite do pagador';
                        break;
                    case '78':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação de recebimento de pedido de negativação';
                        break;
                    case '79':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação de recebimento de pedido de exclusão de negativação';
                        break;
                    case '80':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação de entrada de negativação';
                        break;
                    case '81':
                        $lanc[$i]['descOcorrencia'] = 'Entrada de negativação rejeitada';
                        break;
                    case '82':
                        $lanc[$i]['descOcorrencia'] = 'Confirmação de exclusão de negativação';
                        break;
                    case '83':
                        $lanc[$i]['descOcorrencia'] = 'Exclusão de negativação rejeitada';
                        break;
                    case '84':
                        $lanc[$i]['descOcorrencia'] = 'Exclusão de negativação por outros motivos';
                        break;
                    case '85':
                        $lanc[$i]['descOcorrencia'] = 'Ocorrência informacional por outros motivos';
                        break;
                    endswitch;
                
                
                //$lanc[$i]['numCartorio'] = ''; // *****
                //$lanc[$i]['numProtocolo'] = ''; // ****
                //$lanc[$i]['cheque'] = '';
                //$lanc[$i]['iof'] = substr($linha, 214,11).".".substr($linha, 225,2);
                //$lanc[$i]['outroCredito'] = substr($linha, 279,11).".".substr($linha, 290,2); 
                //$lanc[$i]['bancoCobrador'] = substr($linha, 165,3); 
                //$lanc[$i]['agenciaCobrador'] = substr($linha, 168,5); 
                //$lanc[$i]['idTituloBanco2'] = substr($linha, 126,20); 
                //$lanc[$i]['outrasDespesa'] = 0; // ******
                //$lanc[$i]['tipoIncr'] = substr($linha, 1,2);
                //$lanc[$i]['numIncr'] = substr($linha, 3,14);
                //$lanc[$i]['idEmpBeneficiaria'] = substr($linha, 20,17);
                //$lanc[$i]['idRateio'] = substr($linha, 104,1); 
                //$lanc[$i]['pagParcial'] = substr($linha, 105,2); 

                // incrementa
                $i++;
                break;
            case 9:
                //001 -001 - 001 - Identificação do registro detalhe
                $lancTrailler[0]['ID'] = substr($linha, 0,1);
                //002 - 016 - 015 - Nosso número SICREDI sem edição
                $lancTrailler[0]['banco'] = substr($linha, 1,15);
                //017 - 017 - 001 - Filler
                //018 - 018 - 001 - Híbrido
                //019 - 020 - 002 - Filler
                //021 - 055 - 035 - TXID (Código de Identificação do QR Code)
                //056 - 056 - 001 - Filler
                //057 - 133 - 077 - URL do QRCode
                //134 - 134 - 001 - Filler
                //135 - 390 - 256 - Copia e cola
                //391 - 394 - 004 - filler
                //395 - 400 - 006 - Número sequencial do registro
                $lancTrailler[0]['numSeq'] = substr($linha, 394,6);
                $lancTrailler[0]['quantReg02'] = $quantReg02; //substr($linha, 57,5);
                $lancTrailler[0]['valorReg02'] = $total02;//substr($linha, 62,10).".".substr($linha, 72,2);
                $lancTrailler[0]['valorReg06Liquidacao'] = $total06;//substr($linha, 74,10).".".substr($linha, 84,2);
                $lancTrailler[0]['quantReg06Liquidacao'] = $quantReg06;//substr($linha, 86,5);
                $lancTrailler[0]['valorReg06'] = $total06; //substr($linha, 91,10).".".substr($linha, 101,2);
                $lancTrailler[0]['quantReg0910Baixado'] = $quantReg09; //substr($linha, 103,5);
                $lancTrailler[0]['valorReg0910Baixado'] = $total09;//substr($linha, 108,10).".".substr($linha, 118,2);
                $lancTrailler[0]['quantReg13'] = $quantReg13;// substr($linha, 57,5);
                $lancTrailler[0]['valorReg13'] = $total13;// substr($linha, 62,10).".".substr($linha, 72,2);
                $lancTrailler[0]['quantReg17'] = $quantReg17;// substr($linha, 57,5);
                $lancTrailler[0]['valorReg17'] = $total17;
                $lancTrailler[0]['quantReg28'] = $quantReg28;// substr($linha, 57,5);
                $lancTrailler[0]['valorReg28'] = $total28;
                break;
        }

    }//FECHA WHILE
    $this->smarty->assign('lancHeader', $lancHeader);
    $this->smarty->assign('lancTrailler', $lancTrailler);
    $this->smarty->assign('lanc', $lanc);  
    return $lanc;   

} //fim processaRetorno748


//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraRetorno($mensagem=null, $retorno=null){

    $par = explode("|", $this->m_letra);
    $codEmpresaBanco = 0;
    $codContaBanco = 0;

    // conta bancaria
    // $consulta = new c_banco();
    // $sql = "select conta as id, nomeinterno as descricao, numnobanco, banco from fin_conta";
    // $consulta->exec_sql($sql);
    // $consulta->close_connection();
    // $result = $consulta->resultado;
    // if($par[1] == "") $this->smarty->assign('conta_id', '');
    // else $this->smarty->assign('conta_id', $par[1]);
    // for ($c=0; $c < count($result); $c++){
    //         $conta_ids[$c] = $result[$c]['ID'];
    //         $conta_names[$c] = ucwords(strtolower($result[$c]['DESCRICAO']));
    //         $numBanco = intval($result[$c]['NUMNOBANCO']);
    //         $banco = intval($result[$c]['BANCO']);
    // }
    // $this->smarty->assign('conta_ids', $conta_ids);
    // $this->smarty->assign('conta_names', $conta_names);
    // $this->smarty->assign('conta_id', $result[$c]['ID']);
   
    
   if ($this->m_letra != ''):
         //conta selecionada
        //  $objContaBanco = new c_contaBanco;
        //  $objContaBanco->setId($par[1]);
        //  $conta = $objContaBanco->select_ContaBanco();
        //  $banco = $conta[0]['BANCO'];
 
        // nome arquivo
        $path = ADMraizCliente."/cobranca/banco/".$banco."/retorno/";
        $f_name = $_FILES['fileArq']['name'];
        $f_tmp = $_FILES['fileArq']['tmp_name'];
        $f_type = $_FILES['fileArq']['type'];
        $uploadfile = $path. $f_name;
    endif;
    $lanc = [];
    if (file_exists($f_tmp)):
        //ABRE O ARQUIVO TXT
        $ponteiro = fopen ($f_tmp,"r");
        $linha = fgets($ponteiro);
        $banco = substr($linha, 76,3);
        fclose ($ponteiro);
        $ponteiro = fopen ($f_tmp,"r");

        // HEADER
        //$banco = $this->m_banco;
        $function_name = 'processaRetorno'.$banco;
        $lanc = $this->$function_name($ponteiro);
        
        // $this->processaRetorno237($ponteiro);

        //FECHA O PONTEIRO DO ARQUIVO
        fclose ($ponteiro);
        
    endif;
	
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('saldoInicial', $this->saldoTotal);
    $this->smarty->assign('dataInicio', $par[0]);
    $this->smarty->assign('dataFim', $par[1]);
    $this->smarty->assign('filePesquisa', $f_name);
    //print_r($lanc);
    //$arr = array();
    //for ($i=0; $i < count($lanc); $i++){
    //    $arr[] = $lanc[$i]; 
    //}    
    //$out = json_encode($lanc);
    //print_r($out);
    
    //$this->smarty->assign('jsonLanc', serialize($lanc));


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


    if ($retorno == 'B'){
        if ($this->arqFile == $f_name){
            if (!$this->retornoProcessado($f_name, $lanc[$i]['dataOcorrenciaBD'])){
                if ($this->atualizaRetorno($lanc, $f_name) == true){
                    $msg = 'Arquivo de retorno processado com sucesso';
                }else{
                    $msg = 'Arquivo de retorno NÃO processado, realize nova consolidação';
                }
            }else{    
                $msg = "Arquivo de retorno já processado!!";
                $retorno = '';
            }   
        }else{
            $retorno = 'P';
            $msg = "Seleção diferente de arquivo de retorno!!<br>";
            $msg .= "Consolidação: ".$this->arqFile." - Processamento: ".$f_name;
            $retorno = '';
        }
    }else{
        $msg .= "Selecione o arquivo de RETORNO: ".$f_name." para confirmar.";
    }
    $this->smarty->assign('retorno', $retorno);
    $this->smarty->assign('mensagem', $msg);
    
    
    $this->smarty->display('retorno_bancario_mostra.tpl');
	

} //fim mostraRetorno


//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$retorno_bancario = new p_retorno_bancario();

$retorno_bancario->controle();
 
  
?>
