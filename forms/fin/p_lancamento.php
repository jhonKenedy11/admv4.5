<?php
/**
 * @package   astecv3
 * @name      p_lancamento
 * @category  PAGES - P_LANCAMENTO - Lancamento de receitas ou despesas
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admsistema.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      22/05/2016
 */

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_lancamento.php");
include_once($dir."/../../class/fin/c_lancamento_agrupamento_tools.php");
include_once($dir."/../../bib/c_date.php");


//Class P_LANCAMENTO
Class p_lancamento extends c_lancamento {

private $m_submenu = NULL;
private $m_letra = NULL;
private $m_opcao = NULL;
private $m_ancora = NULL;
private $m_letraC = NULL;
private $m_dadosLancAgrupado = NULL;
private $m_dadosLanc = NULL;
public $m_rateioCC = NULL;
public $m_atividade = NULL;
public $smarty = NULL;
public $idAnexo = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct($submenu, $letra, $opcao, $letraC, $ancora, $rateioCC, $lancAgrupados, $dadosLanc){

  	 // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // inicializa variaveis de controle
        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_opcao = $opcao;
        $this->m_ancora = $ancora;
        $this->m_letraC = $letraC;
        $this->m_par = explode("|", $this->m_letra);
        $this->m_rateioCC = $rateioCC;
        $this->m_dadosLancAgrupado = $lancAgrupados;
        $this->m_dadosLanc = $dadosLanc;
        $this->m_par_agrupado = explode("|", $this->m_dadosLanc);

        //vars anexo
        $this->idAnexo = (isset($parmGet['idAnexo']) ? $parmGet['idAnexo'] : (isset($parmPost['idAnexo']) ? $parmPost['idAnexo'] : null));

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('ADMhttpCliente', ADMhttpCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Lançamentos Financeiros");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7, 8, 9, 10, 11 ]"); 
        $this->smarty->assign('disableSort', "[ 11 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // include do javascript
        // include ADMjs . "/fin/s_lancamento.js";
}

//---------------------------------------------------------------
//---------------------------------------------------------------
public function buscaCadastroLancamento(){
	$lanc = $this->select_lancamento();
	
	$this->setPessoa($lanc[0]['PESSOA']);
	$this->setPessoaNome();
	$this->setDocto($lanc[0]['DOCTO']);
	$this->setSerie($lanc[0]['SERIE']);
	$this->setParcela($lanc[0]['PARCELA']);
	$this->setAgrupamento($lanc[0]['AGRUPAMENTO']);
	$this->setTipolancamento($lanc[0]['TIPOLANCAMENTO']);
	$this->setTipodocto($lanc[0]['TIPODOCTO']);
	$this->setSitdocto($lanc[0]['SITDOCTO']);
	$this->setSitpgto($lanc[0]['SITPGTO']);
	$this->setSitpgtoAnt($lanc[0]['SITPGTO']);
	$this->setUsrsitpgto($lanc[0]['USRSITPGTO']);
	$this->setModopgto($lanc[0]['MODOPGTO']);
	$this->setDocbancario($lanc[0]['DOCBANCARIO']);
	$this->setConta($lanc[0]['CONTA']);
	$this->setNumlcto($lanc[0]['NUMLCTO']);
	$this->setCheque($lanc[0]['CHEQUE']);
	$this->setUsraprovacao($lanc[0]['USRAPROVACAO']);
	$this->setGenero($lanc[0]['GENERO']);
        $this->setDescGenero();
	$this->setCentroCusto($lanc[0]['CENTROCUSTO']);	
	$this->setLancamento($lanc[0]['LANCAMENTO']);
	$this->setEmissao($lanc[0]['EMISSAO']);
	$this->setVencimento($lanc[0]['VENCIMENTO']);
	$this->setMovimento($lanc[0]['PAGAMENTO']);
	$this->setOriginal($lanc[0]['ORIGINAL']);
	$this->setMulta($lanc[0]['MULTA']);
	$this->setJuros($lanc[0]['JUROS']);
	$this->setAdiantamento($lanc[0]['ADIANTAMENTO']);
	$this->setDesconto($lanc[0]['DESCONTO']);
	$this->setTotal($lanc[0]['TOTAL']);
	$this->setMoeda($lanc[0]['MOEDA']);
	$this->setOrigem($lanc[0]['ORIGEM']);
	$this->setObs($lanc[0]['OBS']);
        $this->setObsContabil($lanc[0]['OBSCONTABIL']);
        $this->setContabil($lanc[0]['CONTABIL']);
        $this->setNossoNumero($lanc[0]['NOSSONUMERO']);
        $this->setRemessaArq($lanc[0]['REMESSAARQ']);
        $this->setRemessaNum($lanc[0]['REMESSANUM']);
        $this->setRemessaData($lanc[0]['REMESSADATA']);
        $this->setRetornoArq($lanc[0]['RETORNOARQ']);
        $this->setRetornoCod($lanc[0]['RETORNOCOD']);
        $this->setRetornoCodRejeicao($lanc[0]['RETORNOCODREJEICAO']);
        $this->setRetornoCodBaixa($lanc[0]['RETORNOCODBAIXA']);
        $this->setRetornoDataLiq($lanc[0]['RETORNODATALIQ']);
}
/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
 * @name controle
 * @param VARCHAR submenu 
 * @return vazio
 */
function controle(){
  $this->m_ancora = $this->getId();
  switch ($this->m_submenu){
        case 'cadastrar':
            //echo "passou";
            if ($this->verificaDireitoUsuario('FinLancamento', 'I')){
                if ($this->m_opcao == 'conferencia'){
                      //  echo "passou".$this->m_letraC;

                    //crio classe c_date
                    $calcData = new c_date();

                    $par = explode("|", $this->m_letraC);
                    $this->setDocto($par[2].date("d"));
                    $this->setSerie(date("d"));
                    $this->setParcela("1");
                     if ($par[10] == 'C'){
                        $this->setDesconto(number_format((float)$par[6], 2, ',', '.'));
                        $this->setPessoa($par[11]);
                        $this->setGenero($par[12]);
                        $this->setTipodocto($par[13]);
                        $this->setConta($par[16]);

                        if ($par[15] != 0){// par[15] - franquia
                            $dia = explode("/", $par[0]); //data trabalhada

                            $valor_inteiro = floor($dia[0]/$par[15]); // divisao da data trabalhada pela franquia
                            $valor_resto = $dia[0]%$par[15];

                            if((($valor_inteiro==1) && ($valor_resto ==0)) || (($valor_inteiro==0) && ($valor_resto !=0))){
                                 $date = str_replace('/', '-', $par[0]);
                                $dataTrabalhada = date($par[19].'-m-Y',strtotime($date));
                                $dataTrabalhada = str_replace('-', '/', $dataTrabalhada);

                                $diaPagamento = $calcData->somarDias($dataTrabalhada, $par[15] * 1);

                                $this->setVencimento($calcData->somarDias($diaPagamento, $par[9]));
                                $this->setMovimento($calcData->somarDias($diaPagamento, $par[9]));

                            }else if((($valor_inteiro==2) && ($valor_resto ==0)) || (($valor_inteiro==1) && ($valor_resto !=0))){

                                $date = str_replace('/', '-', $par[0]);
                                $dataTrabalhada = date($par[19].'-m-Y',strtotime($date));
                                $dataTrabalhada = str_replace('-', '/', $dataTrabalhada);

                                $diaPagamento = $calcData->somarDias($dataTrabalhada, $par[15] * 2);
                                $this->setVencimento($calcData->somarDias($diaPagamento, $par[9]));
                                $this->setMovimento($calcData->somarDias($diaPagamento, $par[9]));
                            }else if((($valor_inteiro==3) && ($valor_resto ==0)) || (($valor_inteiro==2) && ($valor_resto !=0))){
                                $date = str_replace('/', '-', $par[0]);
                                $dataTrabalhada = date($par[19].'-m-Y',strtotime($date));
                                $dataTrabalhada = str_replace('-', '/', $dataTrabalhada);

                                $diaPagamento = $calcData->somarDias($dataTrabalhada, $par[15] * 3);
                                $this->setVencimento($calcData->somarDias($diaPagamento, $par[9]));
                                $this->setMovimento($calcData->somarDias($diaPagamento, $par[9]));
                            }else if((($valor_inteiro==4) && ($valor_resto ==0)) || (($valor_inteiro==3) && ($valor_resto !=0))){
                                $date = str_replace('/', '-', $par[0]);
                                $dataTrabalhada = date($par[19].'-m-Y',strtotime($date));
                                $dataTrabalhada = str_replace('-', '/', $dataTrabalhada);

                                $diaPagamento = $calcData->somarDias($dataTrabalhada, $par[15] * 4);
                                $this->setVencimento($calcData->somarDias($diaPagamento, $par[9]));
                                $this->setMovimento($calcData->somarDias($diaPagamento, $par[9]));
                            }else{
                                $ultimo_dia = date("t", mktime(0,0,0,$dia[1],'01',$dia[2]));
                                $this->setVencimento($calcData->somarDias($ultimo_dia, $par[9]));
                                $this->setMovimento($calcData->somarDias($ultimo_dia, $par[9]));
                            }

                        }else{
                            $this->setVencimento($calcData->somarDias($par[0], $par[9]));
                            $this->setMovimento($calcData->somarDias($par[0], $par[9]));
                        }
                       // echo "passouforaif=".$this->getMovimento();

                    }else{
                           $this->setPessoa($par[11]);
                           $this->setGenero($par[4]);
                           if ($par[18] != 0){
                               $this->setMovimento($par[18]);
                               $this->setVencimento($par[18]);
                               $this->setCheque($par[17]);
                           }else{
                               $this->setMovimento($calcData->somarDias($par[0], $par[9]));
                               $this->setVencimento($calcData->somarDias($par[0], $par[9]));
                           }


                    }
                    $this->setPessoaNome();


                    //$valorFormat = str_replace('.', ',',$par[5]);
                    $this->setOriginal(number_format((float)$par[5], 2, ',', '.'));
                    $this->setLancamento(date("d/m/Y"));

                    //$this->setMovimento($calcData->somarDias($par[0], $par[9]));
                    $this->setModopgto($par[14]);


                    $this->setTipolancamento($par[7]);
                    $this->setEmissao($par[0]);

                   // echo "passou".$this->getTotal('B');



                }
                //echo "passou - ".$totalFormat.$this->getOriginal('B');
                $this->desenhaCadastroLancamento();
            }
            break;
        case 'alterar':
            if ($this->verificaDireitoUsuario('FinLancamento', 'A')){
                    $this->buscaCadastroLancamento();
                    $this->desenhaCadastroLancamento();
            }
            break;
        case 'inclui':
            if ($this->verificaDireitoUsuario('FinLancamento', 'I')){
                    if (($this->getDocto() >0) && $this->existeDocumento()){
                        // $this->m_submenu = "cadastrar";
                        $tipoMsg = "alerta";
                        $this->desenhaCadastroLancamento("DOCUMENTO JÁ EXISTENTE, ALTERE O NÚMERO DO DOCUMENTO", $tipoMsg);
                        // $this->mostraLancamentos("DOCUMENTO JÁ EXISTENTE, ALTERE O NÚMERO DO DOCUMENTO", $tipoMsg);
                    }                        
                    else {
                        $this->setOrigem("FIN");
                        $id = $this->incluiLancamento();
                        if (is_numeric($id)) {
                                $this->incluirRateio($id, $this->m_rateioCC);
                                $tipoMsg = "sucesso";
                                $this->mostraLancamentos("OS DADOS DA LANÇAMENTO <b> $id </b> FORAM CADASTRADOS!", $tipoMsg);
                        }else{
                                $tipoMsg = "alerta";
                                $this->desenhaCadastroLancamento($id, $tipoMsg);

                        }
                   }
            }		
            break;
        case 'altera':
           if ($this->verificaDireitoUsuario('FinLancamento', 'A')){
                $this->m_ancora = $this->getId();
                // $this->deletarRateioCC($this->getId());
                // $this->incluirRateio($this->getId(), $this->m_rateioCC);
                $resultAltera = $this->alteraLancamento();
                if($resultAltera){
                        $msgRetorno = 'Financeiro alterado!';
                        echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                        echo "<script>
                        Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        width: 510,
                        text: '".$msgRetorno.".',
                        confirmButtonText: 'OK'
                        });
                        </script>";
                }else{
                        $msgRetorno = 'Não foi possível alterar o financeiro, contate o suporte!';
                        echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: '".$msgRetorno.".',
                            confirmButtonText: 'OK'
                        });
                        </script>";   
                        
                }
                $this->mostraLancamentos('');
            }
            break;
        case 'salvarateio':
           if ($this->verificaDireitoUsuario('FinLancamento', 'A')){
                $this->deletarRateioCC($this->getId());
                $this->incluirRateio($this->getId(), $this->m_rateioCC);
                $this->desenhaCadastroLancamento();
            }
            break;
        case 'exclui':
            if ($this->verificaDireitoUsuario('FinLancamento', 'E')){
                $this->m_ancora = $this->getId();
                $this->mostraLancamentos($this->excluiLancamento());}
            break;
        case 'reenvia':
            if ($this->verificaDireitoUsuario('FinLancamento', 'R')){
                    $this->buscaCadastroLancamento();
                    $this->alteraSituacaoFinanceiro(0, $this->getId(),'FIN', 'C');
                    $this->setSitpgto('A');
                    $this->setOriginal($this->getOriginal('F'));
                    $this->setTotal($this->getTotal('F'));
                    $this->setOrigem($this->getOrigem('FIN'));
                    $this->setLancamento(date('d-m-Y H:i:s'));
                    $this->setObs("****Gerado canelamento do titulo anterior e gerado novo titulo para enivar de cobrança bancaria****");
                    $this->incluiLancamento();
                    $this->setId($this->idInsert);
                    $this->buscaCadastroLancamento();
                    $this->m_submenu = 'alterar';
                    $this->desenhaCadastroLancamento();
            }
            break;
        case 'parcela':
            if ($this->verificaDireitoUsuario('FinLancamento', 'S')){
                    $this->buscaCadastroLancamentoAdd();
                    $this->setRemessaNum('');
                    $count = $this->add_parc_lancamento();
                    $this->mostraLancamentos('Parcela(s) adicionada(s) com sucesso!', 'sucesso');
            }
            break;
            case 'massa':
                if ($this->verificaDireitoUsuario('FinLancamento', 'S')){
                        //$this->buscaCadastroLancamento();
                        $count = $this->add_massa_lancamento($this->m_atividade);
                        $this->mostraLancamentos($count.' LANÇAMENTOS CADASTRADOS COM SUCESSO!!');
                }
                break;
        case 'agruparLanc':
                if ($this->verificaDireitoUsuario('FinLancamento', 'A')){
                        try{
                                $transaction = new c_banco();
                                //inicia transacao
                                $transaction->inicioTransacao($transaction->id_connection);

                                $objLancAgrupamentoTools = new c_lancamento_agrupamento_tools();
                                $idGerado = $objLancAgrupamentoTools->incluiLancamentoAgrupado($this->m_dadosLancAgrupado, $this->m_dadosLanc, $transaction->id_connection );
                                $objLancAgrupamentoTools->alteraSituacaoLancAgrupado($this->m_dadosLancAgrupado, $idGerado, $transaction->id_connection);
                                //; commit transação
                                $transaction->commit($transaction->id_connection); 

                                $this->setId($idGerado);
                                if($this->m_par_agrupado[7] == ""){
                                        $this->atualizarField('DOCTO', $idGerado,'FIN_LANCAMENTO');
                                }else{
                                        $this->atualizarField('DOCTO', $this->m_par_agrupado[7] ,'FIN_LANCAMENTO');
                                }

                                $this->m_submenu = 'alterar';
                                $this->buscaCadastroLancamento();
                                $this->desenhaCadastroLancamento();

                        }catch (Error $e) {
                                $transaction->rollback($transaction->id_connection);    
                                $transaction->close_connection($transaction->id_connection);
                                $msg = "Lançamento Não Gerado - Verificar titulos cadastrados<br>".$e->getMessage();
                                $tipoMsg = "alerta";
                                $this->mostraLancamentos($msg, $tipoMsg);
            
                            } catch (Exception $e) {
                                if ($transaction->id_connection != null){
                                    $transaction->rollback($transaction->id_connection);
                                    $transaction->close_connection($transaction->id_connection);
                                }
                                $msg = "Lançamento Não Gerado - Verificar titulos cadastrados<br>".$e->getMessage();
                                $tipoMsg = "alerta";
                                $this->mostraLancamentos($msg, $tipoMsg);
                        } 

                }
                break;
        case 'baixaLanc':
                if ($this->verificaDireitoUsuario('FinLancamento', 'S')){
                        $this->setConta($this->m_par_agrupado[0]);
                        $this->setMovimento($this->m_par_agrupado[1]);
                        for($i = 2; $i < count($this->m_par_agrupado); $i++){
                                if($this->m_par_agrupado[$i] != ''){
                                        $this->setId($this->m_par_agrupado[$i]);
                                        $this->alteraLancBaixado();
                                }       
                                
                        }
                        $this->mostraLancamentos('Lançamentos Baixados com sucesso!!', 'sucesso');
                }
                break;
        case 'clonaFinanceiro':
                $this->clonarFinanceiro('');
                break;
        case 'salvarAnexo':
                $tipoMsg = null;
                $nameFile = $_FILES['file']['name'];
                $tmp_dir = $_FILES['file']['tmp_name'];
                $sizeFile = $_FILES['file']['size'];
                // get image extension
                $anexoExt = strtolower(pathinfo($nameFile,PATHINFO_EXTENSION));

                if(empty($nameFile) and (is_file($tmp_dir))){
                        $this->desenhaCadastroLancamento('Selecione um anexo', 'error');
                }else{

                        if($this->selectAnexo()){
                                $idAnexo = $this->gravaAnexoProduto('FIN', 'N', $anexoExt);
                        }else{
                                $idAnexo = $this->gravaAnexoProduto('FIN', 'N', $anexoExt);
                        }

                        $upload_dir = ADMraizCliente . "/images/doc/fin/".$this->getId()."/"; // upload directory
                        
                        //verify if directory exists, if it is not created
                        if (!file_exists($upload_dir)){
                            mkdir($upload_dir, 0755, true);
                        }

                        // valid image extensions
                        $valid_extensions = array('jpeg', 'jpg', 'pdf'); // valid extensions

                        // rename uploading image
                        $renameAnex = $idAnexo.".".$anexoExt;
                        
                        // allow valid image file formats
                        if(in_array($anexoExt, $valid_extensions)){
                                // Check file size '2MB'
                                if($sizeFile < 2000000){
                                        if (!move_uploaded_file($tmp_dir,$upload_dir.$renameAnex)){
                                                $tipoMsg = "error";                                          
                                                $msgRetorno = "Desculpe, erro ao adicionar a imagem no diretório, contate o suporte!";
                                                echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                                                echo "<script>
                                                        Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Atenção',
                                                        width: 510,
                                                        text: '".$msgRetorno.".',
                                                        confirmButtonText: 'OK'
                                                });
                                                </script>";   
                                        }
                                }else{
                                        $tipoMsg = "error";
                                        $msgRetorno = "Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.";
                                        echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                                        echo "<script>
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Atenção',
                                            width: 510,
                                            text: '".$msgRetorno.".',
                                            confirmButtonText: 'OK'
                                        });
                                        </script>";   
                                }
                        }else{  
                                $tipoMsg = "error";
                                $msgRetorno = "Somente arquivo JPG, JPEG ou PDF são aceitos!";
                                echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                                echo "<script>
                                Swal.fire({
                                        icon: 'warning',
                                        title: 'Atenção',
                                        width: 510,
                                        text: '".$msgRetorno.".',
                                        confirmButtonText: 'OK'
                                });
                                </script>";   
                        }
                }
            
                if ($tipoMsg == "error"){
                    $this->excluiAnexo($idAnexo);
                }else{
                        $msgRetorno = 'Anexo salvo!!';
                        echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                        echo "<script>
                        Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '".$msgRetorno.".',
                                confirmButtonText: 'OK'
                        });
                        </script>";
                }
            
                $this->desenhaCadastroLancamento($errMSG, $tipoMsg);
            break;
        case 'excluiAnexo':
                $selectAnexo = $this->searchAnexo($this->idAnexo);
                $errMSG = $this->excluiAnexo($this->idAnexo);
                if ($errMSG == true){
                        //if for mount extension
                        if($selectAnexo[0]['EXTENSAO'] == 'PDF'){
                                $ext = '.pdf';
                        }elseif($selectAnexo[0]['EXTENSAO'] == 'JPG'){
                                $ext = '.jpg';
                        }elseif($selectAnexo[0]['EXTENSAO'] == 'JPEG'){
                                $ext = '.jpeg';
                        }
                        $deleteDir = unlink('images/doc/fin/'.$this->getId().'/'.$this->idAnexo.$ext);

                        $msgRetorno = 'Anexo excluído!';
                        echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                            echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '".$msgRetorno.".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                }else{
                        $msgRetorno = 'Erro ao excluir anexo, entre em contato com o suporte!';
                        echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: '".$msgRetorno.".',
                            confirmButtonText: 'OK'
                        });
                        </script>";   
                }
                $this->desenhaCadastroLancamento('');
            break;
        default:
                if ($this->verificaDireitoUsuario('FinLancamento', 'C')){
                        $this->mostraLancamentos('');
                }
    }

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Lançamento financeiro. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroLancamento($mensagem = NULL, $tipoMsg=NULL){
    
    
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('pathCliente', ADMhttpCliente);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('letraC', $this->m_letraC);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('opcao', $this->m_opcao);
    $this->smarty->assign('ancora', $this->m_ancora);
    
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('pessoa', $this->getPessoa());
    $this->smarty->assign('pessoaNome', $this->getPessoaNome());
    $this->smarty->assign('docto', $this->getDocto());
    $this->smarty->assign('serie', $this->getSerie());
    $this->smarty->assign('parcela', $this->getParcela());
    $this->smarty->assign('doctobancario', $this->getDocbancario());
    $this->smarty->assign('cheque', $this->getCheque());
    $this->smarty->assign('datalanc', $this->getLancamento("F"));
    $this->smarty->assign('dataemissao', $this->getEmissao("F"));
    $this->smarty->assign('datavenc', $this->getVencimento("F"));
    $this->smarty->assign('datamov', $this->getMovimento("F"));

    $valor = $this->getOriginal("F");
    $this->smarty->assign('original', ($valor =='0,00') ? '0,00' : $valor);
    // $this->smarty->assign('original', $this->getOriginal("F"));
    $this->smarty->assign('multa', $this->getMulta("F"));

    $this->smarty->assign('juros', $this->getJuros("F"));
    $this->smarty->assign('adiantamento', $this->getAdiantamento("F"));
    $this->smarty->assign('desconto', $this->getDesconto("F"));
    $this->smarty->assign('total', $this->getTotal("F"));
    
//     $valor = $this->getOriginal("F");
//     $this->smarty->assign('original', ($valor =='0,00') ? '' : $valor);
//     // $this->smarty->assign('original', $this->getOriginal("F"));
//     $this->smarty->assign('multa', ($this->getMulta("F")=='0,00') ? '' : $this->getMulta("F"));

//     $this->smarty->assign('juros', ($this->getJuros("F")=='0,00') ? '' : $this->getJuros("F"));
//     $this->smarty->assign('adiantamento', ($this->getAdiantamento("F")=='0,00') ? '' : $this->getAdiantamento("F"));
//     $this->smarty->assign('desconto', ($this->getDesconto("F")=='0,00') ? '' : $this->getDesconto("F"));
//     $this->smarty->assign('total', ($this->getTotal("F")=='0,00') ? '' : $this->getTotal("F"));    
    
    //echo "F".$this->getTotal("F")."-B".$this->getTotal("B");
    $this->smarty->assign('obs', $this->getObs());
    $this->smarty->assign('obscontabil', $this->getObsContabil());
    
    $this->smarty->assign('sitlancAnt', $this->getSitpgtoAnt());

    // tipo lancamento
    $this->smarty->assign('tipolancamento', $this->getTipolancamento());

    // genero documento
    $this->smarty->assign('genero', $this->getGenero());	
    $this->smarty->assign('descGenero', $this->getDescGenero());	
    
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
    $this->smarty->assign('filial_id', $this->getCentroCusto());

    // tipo documento
    $consulta = new c_banco();
    $sql  = "select tipo as id, padrao as descricao from amb_ddm ";
    $sql .= "where (alias='FIN_MENU') and (campo='TipoDoctoPgto') ";
    $sql .= "and ((Tipo = 'X') or (Tipo = 'N') or (Tipo = 'B') or (Tipo = 'D') or (Tipo = 'E') or ";
    $sql .= "(Tipo = 'C') or (Tipo = 'T') or (Tipo = 'A') or (Tipo = 'K') or (Tipo = 'P') or (Tipo = 'N') )";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $tipoDocto_ids[$i] = $result[$i]['ID'];
            $tipoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('tipoDocto_ids', $tipoDocto_ids);
    $this->smarty->assign('tipoDocto_names', $tipoDocto_names);

    $this->smarty->assign('tipoDocto_id', $this->getTipodocto());	

    // situacao documento
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoDoctoPgto')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $situacaoDocto_ids[$i] = $result[$i]['ID'];
            $situacaoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('situacaoDocto_ids', $situacaoDocto_ids);
    $this->smarty->assign('situacaoDocto_names', $situacaoDocto_names);
    $this->smarty->assign('situacaoDocto_id', $this->getSitdocto());	

    // situacao lancamento
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $situacaoLanc_ids[$i] = $result[$i]['ID'];
            $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
    $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
    $this->smarty->assign('situacaoLanc_id', $this->getSitpgto());	


    // modo PAG/REC
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='ModoPgto') and ((tipo = 'C')or(tipo='B')) ";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $modo_ids[$i] = $result[$i]['ID'];
            $modo_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('modo_ids', $modo_ids);
    $this->smarty->assign('modo_names', $modo_names);
    $this->smarty->assign('modo_id', $this->getModopgto());	

    // conta bancaria
    $consulta = new c_banco();
    $sql = "select conta as id, nomeinterno as descricao, banco from fin_conta where status ='A'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $conta = $this->getConta();
    for ($i=0; $i < count($result); $i++){
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
            if ($conta == $result[$i]['ID']){
                 $banco = $result[$i]['BANCO'];  
            }
    }
    $this->smarty->assign('conta_ids', $conta_ids);
    $this->smarty->assign('conta_names', $conta_names);
    $this->smarty->assign('conta_id', $conta);	


    // moeda
    $consulta = new c_banco();
    $sql = "select moeda as id, nome as descricao from fin_moeda order by moeda";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $moeda_ids[$i] = $result[$i]['ID'];
            $moeda_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('moeda_ids', $moeda_ids);
    $this->smarty->assign('moeda_names', $moeda_names);
    $this->smarty->assign('moeda_id', $this->getMoeda());
    
    $id = $this->getId();
    if (!empty($id)){
        $rateioCC = $this->select_rateio_id();
        $percShare = 0;
        for ($i=0; $i < Count($rateioCC); $i++){
           $percShare = $percShare + $rateioCC[$i]['PERCENTUAL'];
        }
        if ($percShare == 0) {
           $rateioCC[0]['PERCENTUAL']  = 100;   
        }
    } else {
        /*
        $consulta = new c_banco();
        $sql  = "SELECT CentroCusto, Descricao, 0 as Percentual ";
        $sql .= "FROM FIN_CENTRO_CUSTO WHERE NIVEL = 1"; 
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $percentual = 100 / count($result);
        $percShare = 0;
        for ($i=0; $i < count($result); $i++){
                $rateioCC[$i]['CENTROCUSTO'] = $result[$i]['CENTROCUSTO'];
                $rateioCC[$i]['PERCENTUAL'] = $percentual;
                $percShare .= $percentual;
        }    
        */
        $rateioCC = $this->select_rateio_cc();   

    }
    if($this->getDocto() != 0 && $this->getDocto() != '' & $this->getId() != 0 && $this->getId() != ''){
        $agrupados = $this->select_titulos_agrupados($this->getId());
    }

    $this->smarty->assign('rateioCC', $rateioCC);
    $this->smarty->assign('agrupados', $agrupados);
    $this->smarty->assign('nossonumero',  $this->getNossoNumero());
    $this->smarty->assign('remessanum', $this->getRemessaNum());
    $this->smarty->assign('remessadata', $this->getRemessaData());
    $fileName = $this->getRemessaArq();
    $this->smarty->assign('remessaarq', $fileName);

    if ( strlen($fileName) > 0 ) {
        $data = $this->getEmissao("F");
        $partes = explode("/", $data);
        $file = ADMhttpCliente."/banco/".$banco."/remessa/".$partes[2].$this->getRemessaArq();
        $this->smarty->assign('arquivo', $file);
        $this->smarty->assign('nomeArq', $fileName);
    }
        
    $this->smarty->assign('retornoarq',  $this->getRetornoArq());
    $this->smarty->assign('retornocod', $this->getRetornoCod());
    $this->smarty->assign('retornocodrejeicao', $this->getRetornoCodRejeicao());
    $this->smarty->assign('retornocodbaixa', $this->getRetornoCodBaixa());
    $this->smarty->assign('retornodataliq', $this->getRetornoDataLiq());

    if ($this->getSerie() == 'PED') {
        $consulta = new c_banco();
        $sql  = "select OBS from fat_pedido where (pedido=".$this->getDocto().") ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $this->smarty->assign('obsped',$result[0]['OBS']);    
    }

    $lancAnexo = $this->selectAnexo($this->getId());
    $this->smarty->assign('lancAnexo', $lancAnexo);

    $this->smarty->display('lancamento_cadastro.tpl');

}//fim desenhaCadLancamento

/**
* <b> Listagem de todas as registro cadastrados de tabela Lancamentos. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraLancamentos($mensagem, $tipoMsg=NULL){
    
    if ($this->m_letra != ''){
    	$lanc = $this->select_lancamento_letra($this->m_letra) ?? [];
    }else {
        $lanc = [];
    }
	
    for ($l=0; $l < count($lanc); $l++){
        if ($l==0){ 
                $vencimento = $lanc[$l]['VENCIMENTO'];
                $total = $lanc[$l]['TOTAL'];
        }
        else {
                $vencimento .= "|".$lanc[$l]['VENCIMENTO'];
                $total .= "|".$lanc[$l]['TOTAL'];
        }
    }
    $this->setPessoa($this->m_par[2]);
    $this->setPessoaNome();
    $this->smarty->assign('pessoa', $this->getPessoa());
    $this->smarty->assign('nome', $this->getPessoaNome());

    $this->smarty->assign('vencimento', $vencimento); 	
    $this->smarty->assign('total', $total); 	
    $this->smarty->assign('linhas', $l); 	
        
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('pathCliente', ADMhttpCliente);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('ancora', $this->m_ancora);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);
	
    if($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
    else $this->smarty->assign('dataIni', $this->m_par[0]);
    
    if($this->m_par[1] == "") {
    	$dia = date("d");
    	$mes = date("m");
    	$ano = date("Y");
    	$data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
    	$this->smarty->assign('dataFim', $data);
    //	$data = mktime(0, 0, 0, $mes, 1, $ano);
    //	$this->smarty->assign('dataFim', date("d",$data-1).date("/m/Y"));
    }
    else $this->smarty->assign('dataFim', $this->m_par[1]);
	
	// lista de datas.
    $this->smarty->assign('datas_ids', array('nao','lancamento','emissao', 'vencimento', 'pagamento'));
    $this->smarty->assign('datas_names', array('N&atilde;o Considera','Lan&ccedil;amento','Emiss&atilde;o','Vencimento','Movimento'));
    if($this->m_par[3] == "") $this->smarty->assign('datas_id', 'vencimento');
    else $this->smarty->assign('datas_id', $this->m_par[3]);
    
    // situacao lancamento
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $situacaoLanc_ids[$i] = $result[$i]['ID'];
            $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    if (($this->m_par[4] != "") and ($this->m_par[4] != '0')){
            $i = 5;	
            $sit[$i-5] = $this->m_par[$i];
            $i++;
            while ($i <= ($this->m_par[4]+4)) {
                    $sit[$i-5] = $this->m_par[$i];
                    $i++;
            }				
    }
    else {	
            $sit[0] = "A";
            $sit[1] = "N";
    }	
    $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
    $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
    $this->smarty->assign('situacaoLanc_id', $sit);

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

    $posFilial = 5 + $this->m_par[4];
    if (($this->m_par[$posFilial] != "") and ($this->m_par[$posFilial] != '0')){
            $i = $posFilial + 1;	
            $filial[$i-$posFilial+1] = $this->m_par[$i];
            $i++;
            while ($i <= ($this->m_par[$posFilial]+$posFilial)) {
                    $filial[$i-$posFilial+1] = $this->m_par[$i];
                    $i++;
            }				
    }
/*	else {	
		$filial = $filial_ids;
	}	
*/
    $this->smarty->assign('filial_ids', $filial_ids);
    $this->smarty->assign('filial_names', $filial_names);
    $this->smarty->assign('filial_id', $filial);

    // verifica direito de consulta no lancamento ##############################
    $consulta = $this->verificaDireitoPrograma('FinLancamento', 'C');
    $this->smarty->assign('consulta', $consulta);

    // tipo lancamento ##############################
    $sql = '';
    $recebimento = $this->verificaDireitoPrograma('FinLancamentoRecebimento', 'C');
    $pagamento = $this->verificaDireitoPrograma('FinLancamentoPagamento', 'C');
    if (($pagamento == 'C') and ($recebimento == 'C')){
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoLanc')";
    }
    else {
            if ($pagamento == 'C')
                    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoLanc') and (tipo = 'P')";
            else if ($recebimento == 'C')
                    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoLanc')  and (tipo = 'R')";
    }
    if ($sql <> ''){
            $consulta = new c_banco();
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            for ($i=0; $i < count($result); $i++){
                    $tipoLanc_ids[$i] = $result[$i]['ID'];
                    $tipoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
            }
    }	

    $posTipoLanc = $posFilial + $this->m_par[$posFilial] + 1;
    //echo 'tipoLanc'.$posTipoLanc;
    if (($this->m_par[$posTipoLanc] != "") and ($this->m_par[$posTipoLanc] != '0')){
            $i = $posTipoLanc + 1;	
            $tipoLanc[$i-$posTipoLanc+1] = $this->m_par[$i];
            $i++;
            while ($i <= ($this->m_par[$posTipoLanc]+$posTipoLanc)) {
                    $tipoLanc[$i-$posTipoLanc+1] = $this->m_par[$i];
                    $i++;
            }				
    }
    else {	
            $tipoLanc = $tipoLanc_ids;
    }	

    $this->smarty->assign('tipoLanc_ids', $tipoLanc_ids);
    $this->smarty->assign('tipoLanc_names', $tipoLanc_names);
    $this->smarty->assign('tipoLanc_id', $tipoLanc);


    // situacao documento
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoDoctoPgto')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $situacaoDocto_ids[$i] = $result[$i]['ID'];
            $situacaoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $posSitDocto = $posTipoLanc + $this->m_par[$posTipoLanc] + 1;
    //echo 'sitDocto'.$posSitDocto;
    if (($this->m_par[$posSitDocto] != "") and ($this->m_par[$posSitDocto] != '0')){
            $i = $posSitDocto + 1;	
            $sitDocto[$i-$posSitDocto+1] = $this->m_par[$i];
            $i++;
            while ($i <= ($this->m_par[$posSitDocto]+$posSitDocto)) {
                    $sitDocto[$i-$posSitDocto+1] = $this->m_par[$i];
                    $i++;
            }				
    }
//	else {	
//		$sitDocto = $situacaoDocto_ids;
//	}	
    $this->smarty->assign('situacaoDocto_ids', $situacaoDocto_ids);
    $this->smarty->assign('situacaoDocto_names', $situacaoDocto_names);
    $this->smarty->assign('situacaoDocto_id', $sitDocto);


    // conta
    $consulta = new c_banco();
    $sql = "SELECT * FROM fin_conta  where status ='A'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $conta_ids[$i] = $result[$i]['CONTA'];
            $conta_names[$i] = ucwords(strtolower($result[$i]['NOMEINTERNO']));
    }
    $posConta = $posSitDocto + $this->m_par[$posSitDocto] + 1;
    //echo 'conta'.$posConta;
    if (($this->m_par[$posConta] != "") and ($this->m_par[$posConta] != '0')){
            $arrCount = 0;
            $i = $posConta + 1;	
            //$conta[$i-$posConta+1] = $this->m_par[$i];
            //$i++;
            while ($i <= ($this->m_par[$posConta]+$posConta)) {
                    //$conta[$i-$posConta+1] = $this->m_par[$i];
                $conta[$arrCount] = $this->m_par[$i];
                $arrCount++;
                $i++;
            }				
    }
    $this->smarty->assign('conta_ids', $conta_ids);
    $this->smarty->assign('conta_names', $conta_names);
    $this->smarty->assign('conta_id', $conta);



    // genero documento
    $consulta = new c_banco();
    $sql = "SELECT genero AS id, descricao FROM fin_genero ORDER BY descricao";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $generoDocto_ids[0] = '0';
    $generoDocto_names[0] = 'Todos';
    for ($i=1; $i < count($result); $i++){
            $generoDocto_ids[$i] = $result[$i]['ID'];
            $generoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $posGenero = $posConta + $this->m_par[$posConta] + 1;
	//echo 'Genero'.$posGenero;
    $this->setGenero($this->m_par[$posGenero]);
    $this->setDescGenero();
    $this->smarty->assign('genero', $this->getGenero());
    $this->smarty->assign('descGenero', $this->getDescGenero());

    // tipo documento
    $consulta = new c_banco();
    $sql = "SELECT tipo as id, padrao as descricao FROM amb_ddm WHERE (alias='FIN_MENU') AND (campo='TipoDoctoPgto')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $tipoDocumento_ids[$i] = $result[$i]['ID'];
            $tipoDocumento_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $postipoDocumento = $posGenero +1;
    if (($this->m_par[$postipoDocumento] != "")){	
            $i = $postipoDocumento + 1;	
            $tipoDocto[$i-$postipoDocumento-1] = $this->m_par[$i];
            $i++;
            while ($i <= ($this->m_par[$postipoDocumento]+$postipoDocumento)) {
                    $tipoDocto[$i-$postipoDocumento-1] = $this->m_par[$i];
                    $i++;
            }	
    }	
    $this->smarty->assign('tipoDocumento_ids', $tipoDocumento_ids);
    $this->smarty->assign('tipoDocumento_names', $tipoDocumento_names);
    $this->smarty->assign('tipoDocumento_id', $tipoDocto);
    // FIM TIPO DOCUMENTO

    $this->smarty->assign('cc', $cc);


    // conta bancaria
    $consulta = new c_banco();
    $sql = "select conta as id, nomeinterno as descricao, banco from fin_conta where status ='A'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $conta = $this->getConta();
    for ($i=0; $i < count($result); $i++){
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
            if ($conta == $result[$i]['ID']){
                 $banco = $result[$i]['BANCO'];  
            }
    }
    $this->smarty->assign('contaCombo_ids', $conta_ids);
    $this->smarty->assign('contaCombo_names', $conta_names);
    $this->smarty->assign('contaCombo_id', $conta);	



    $this->smarty->display('lancamento_mostra.tpl');
	

} //fim mostraLancamentos
//-------------------------------------------------------------

 /**
 * <b> Desenha form de clonagem de financeiro </b>
 */
function clonarFinanceiro($mensagem = NULL, $tipoMsg=NULL){
    
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', 'cadastrar');
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('id', '');
        $this->smarty->assign('pessoa', $this->getPessoa());
        $this->setPessoaNome();
        $this->smarty->assign('pessoaNome', $this->getPessoaNome());
        $this->smarty->assign('docto', '');
        $this->smarty->assign('serie', '');
        $this->smarty->assign('parcela', '');
        $this->smarty->assign('doctobancario', $this->getDocbancario());
        $this->smarty->assign('cheque', $this->getCheque());
        $this->smarty->assign('datalanc', $this->getLancamento("F"));
        $this->smarty->assign('dataemissao', $this->getEmissao("F"));
        $this->smarty->assign('datavenc', $this->getVencimento("F"));
        $this->smarty->assign('datamov', $this->getMovimento("F"));
        $this->smarty->assign('original', $this->getOriginal(''));
        $this->smarty->assign('multa', $this->getMulta(''));
        $this->smarty->assign('juros', $this->getJuros(''));
        $this->smarty->assign('adiantamento', $this->getAdiantamento(''));
        $this->smarty->assign('desconto', $this->getDesconto(''));
        $this->smarty->assign('total', $this->getTotal(''));
        $this->smarty->assign('obs', $this->getObs());
        $this->smarty->assign('obscontabil', '');
        $this->smarty->assign('sitlancAnt', '');
        $this->smarty->assign('tipolancamento', $this->getTipolancamento());
        $this->smarty->assign('genero', $this->getGenero());
        $this->setDescGenero();	
        $this->smarty->assign('descGenero', $this->getDescGenero());
        $this->smarty->assign('nossonumero',  '');
        $this->smarty->assign('remessanum', '');
        $this->smarty->assign('remessadata', '');
        $this->smarty->assign('retornoarq',  '');
        $this->smarty->assign('retornocod', '');
        $this->smarty->assign('retornocodrejeicao', '');
        $this->smarty->assign('retornocodbaixa', '');
        $this->smarty->assign('retornodataliq', '');
        $this->smarty->assign('clonar', 'true');

        
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
        $this->smarty->assign('filial_id', $this->getCentroCusto());
    
        // tipo documento
        $consulta = new c_banco();
        $sql  = "select tipo as id, padrao as descricao from amb_ddm ";
        $sql .= "where (alias='FIN_MENU') and (campo='TipoDoctoPgto') ";
        $sql .= "and ((Tipo = 'X') or (Tipo = 'N') or (Tipo = 'B') or (Tipo = 'D') or (Tipo = 'E') or ";
        $sql .= "(Tipo = 'C') or (Tipo = 'T') or (Tipo = 'A') or (Tipo = 'K') or (Tipo = 'P') or (Tipo = 'N') )";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $tipoDocto_ids[$i] = $result[$i]['ID'];
                $tipoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('tipoDocto_ids', $tipoDocto_ids);
        $this->smarty->assign('tipoDocto_names', $tipoDocto_names);
        $this->smarty->assign('tipoDocto_id', $this->getTipodocto());	
    
        // situacao documento
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoDoctoPgto')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $situacaoDocto_ids[$i] = $result[$i]['ID'];
                $situacaoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('situacaoDocto_ids', $situacaoDocto_ids);
        $this->smarty->assign('situacaoDocto_names', $situacaoDocto_names);
        $this->smarty->assign('situacaoDocto_id', $this->getSitdocto());	
    
        // situacao lancamento
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $situacaoLanc_ids[$i] = $result[$i]['ID'];
                $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
        $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
        $this->smarty->assign('situacaoLanc_id', $this->getSitpgto());	
    
    
        // modo PAG/REC
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='ModoPgto') and ((tipo = 'C')or(tipo='B')) ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $modo_ids[$i] = $result[$i]['ID'];
                $modo_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('modo_ids', $modo_ids);
        $this->smarty->assign('modo_names', $modo_names);
        $this->smarty->assign('modo_id', $this->getModopgto());	
    
        // conta bancaria
        $consulta = new c_banco();
        $sql = "select conta as id, nomeinterno as descricao, banco from fin_conta where status ='A'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $conta = $this->getConta();
        for ($i=0; $i < count($result); $i++){
                $conta_ids[$i] = $result[$i]['ID'];
                $conta_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
                if ($conta == $result[$i]['ID']){
                     $banco = $result[$i]['BANCO'];  
                }
        }
        $this->smarty->assign('conta_ids', $conta_ids);
        $this->smarty->assign('conta_names', $conta_names);
        $this->smarty->assign('conta_id', $conta);	
    
        // moeda
        $consulta = new c_banco();
        $sql = "select moeda as id, nome as descricao from fin_moeda order by moeda";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $moeda_ids[$i] = $result[$i]['ID'];
                $moeda_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('moeda_ids', $moeda_ids);
        $this->smarty->assign('moeda_names', $moeda_names);
        $this->smarty->assign('moeda_id', $this->getMoeda());

        $this->smarty->display('lancamento_cadastro.tpl');
    
    }//fim desenhaCadLancamento

}	//	END OF THE CLASS

// Rotina principal - cria classe
$lancamento = new p_lancamento($_REQUEST['submenu'],
                               $_POST['letra'],
                               $_REQUEST['opcao'],
                                $_REQUEST['letraC'],
                                $_REQUEST['ancora'],
                                $_REQUEST['rateioCC'],
                                $_REQUEST['dadosLancAgrupamento'],
                                $_REQUEST['dadosLanc']);


                             
if (isset($_POST['id'])) { $lancamento->setId($_POST['id']); } else {$lancamento->setId('');};
if (isset($_POST['pessoa'])) { $lancamento->setPessoa($_POST['pessoa']); } else {$lancamento->setPessoa('');};
//if (isset($_POST['pessoa'])) { $lancamento->setPessoa($_POST['pessoa']); } else {$lancamento->setPessoa('');};
if (isset($_POST['docto'])) { $lancamento->setDocto($_POST['docto']); } else {$lancamento->setDocto('');};
if (isset($_POST['serie'])) { $lancamento->setSerie($_POST['serie']); } else {$lancamento->setSerie('');};
if (isset($_POST['parcela'])) { $lancamento->setParcela($_POST['parcela']); } else {$lancamento->setParcela('');};
if (isset($_POST['doctobancario'])) { $lancamento->setDocbancario($_POST['doctobancario']); } else {$lancamento->setDocbancario('');};
if (isset($_POST['cheque'])) { $lancamento->setCheque($_POST['cheque']); } else {$lancamento->setCheque('');};
if (isset($_POST['original'])) { $lancamento->setOriginal($_POST['original']); } else {$lancamento->setOriginal('');};
if (isset($_POST['multa'])) { $lancamento->setMulta($_POST['multa']); } else {$lancamento->setMulta('');};
if (isset($_POST['juros'])) { $lancamento->setJuros($_POST['juros']); } else {$lancamento->setJuros('');};
if (isset($_POST['adiantamento'])) { $lancamento->setAdiantamento($_POST['adiantamento']); } else {$lancamento->setAdiantamento('');};
if (isset($_POST['desconto'])) { $lancamento->setDesconto($_POST['desconto']); } else {$lancamento->setDesconto('');};
if (isset($_POST['total'])) { $lancamento->setTotal($_POST['total']); } else {$lancamento->setTotal('');};
if (isset($_POST['tipolancamento'])) { $lancamento->setTipolancamento($_POST['tipolancamento']); } else {$lancamento->setTipolancamento('');};
if (isset($_POST['centrocusto'])) { $lancamento->setCentroCusto($_POST['centrocusto']); } else {$lancamento->setCentroCusto('');};
if (isset($_POST['tipodocto'])) { $lancamento->setTipodocto($_POST['tipodocto']); } else {$lancamento->setTipodocto('');};
if (isset($_POST['situacaodocto'])) { $lancamento->setSitdocto($_POST['situacaodocto']); } else {$lancamento->setSitdocto('');};
if (isset($_POST['situacaolancamento'])) { $lancamento->setSitpgto($_POST['situacaolancamento']); } else {$lancamento->setSitpgto('');};
if (isset($_POST['sitlancAnt'])) { $lancamento->setSitpgtoAnt($_POST['sitlancAnt']); } else {$lancamento->setSitpgtoAnt('');};

if (isset($_POST['genero'])) { $lancamento->setGenero($_POST['genero']); } else {$lancamento->setGenero('');};
if (isset($_POST['modo'])) { $lancamento->setModopgto($_POST['modo']); } else {$lancamento->setModopgto('');};
if (isset($_POST['conta'])) { $lancamento->setConta($_POST['conta']); } else {$lancamento->setConta('');};
if (isset($_POST['moeda'])) { $lancamento->setMoeda($_POST['moeda']); } else {$lancamento->setMoeda('');};

if (isset($_POST['datalanc'])) { $lancamento->setLancamento($_POST['datalanc']); } else {$lancamento->setLancamento(date("Y-m-d"));};
if (isset($_POST['dataemissao'])) { $lancamento->setEmissao($_POST['dataemissao']); } else {$lancamento->setEmissao(date("Y-m-d"));};
if (isset($_POST['datavenc'])) { $lancamento->setVencimento($_POST['datavenc']); } else {$lancamento->setVencimento(date("Y-m-d"));};
if (isset($_POST['datamov'])) { $lancamento->setMovimento($_POST['datamov']); } else {$lancamento->setMovimento(date("Y-m-d"));};

if (isset($_POST['obs'])) { $lancamento->setObs($_POST['obs']); } else {$lancamento->setObs('');};
if (isset($_POST['obscontabil'])) { $lancamento->setObsContabil($_POST['obscontabil']); } else {$lancamento->setObsContabil('');};
if (isset($_POST['quantparc'])) { $lancamento->setQuantParc($_POST['quantparc']); } else {$lancamento->setQuantParc('');};
if (isset($_POST['atividade'])) { $lancamento->m_atividade = $_POST['atividade']; } else {$lancamento->m_atividade = '';};

if (isset($_POST['nossonumero'])) { $lancamento->setNossoNumero($_POST['nossonumero']); } else {$lancamento->setNossoNumero('');};
if (isset($_POST['remessanum'])) { $lancamento->setRemessaNum($_POST['remessanum']); } else {$lancamento->setRemessaNum('');};
if (isset($_POST['remessadata'])) { $lancamento->setRemessaData($_POST['remessadata']); } else {$lancamento->setRemessaData('');};
if (isset($_POST['remessaarq'])) { $lancamento->setRemessaArq($_POST['remessaarq']); } else {$lancamento->setRemessaArq('');};

//echo "passou".$lancamento->getQuantParc();
//echo "situacao:".$_REQUEST['opcao'].$_REQUEST['submenu'];


$lancamento->controle();
