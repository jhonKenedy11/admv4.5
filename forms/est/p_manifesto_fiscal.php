<?php

/**
 * @package   astec
 * @name      p_manisfesto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy<jhon.kened11@gmail.com.br>
 * @date      07/11/2022
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_manifesto_fiscal.php");
require_once($dir . "/../../class/est/c_manifesto_fiscal_nf.php");
//require_once($dir . "/../../forms/est/p_mdfe_imprime.php");

//Class p_manifesto_Fiscal
class p_manifesto_fiscal extends c_manifesto_fiscal
{

    private $m_submenu = NULL;
    private $m_letra   = NULL;
    private $m_opcao   = NULL;
    private $m_msg     = NULL;
    public  $smarty    = NULL;
    private $m_idNF    = NULL;
    private $m_idMdf   = NULL;
    private $parmGet   = NULL;
    private $parmPost  = NULL;
    private $img       = NULL;


    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function __construct()
    {
        // @set_exception_handler(array($this, 'exception_handler'));

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

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
        // $this->smarty->error_reporting = E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED ;
        // $this->smarty->setErrorReporting( E_ALL & ~E_NOTICE );

        // inicializa variaveis de controle

        //set submenu
        //$this->m_submenu = (isset($this->parmPost['submenu']) ? $this->parmPost['submenu'] : $this->parmGet['submenu']) ? $this->parmGet['submenu'] : '';
        if ((isset($this->parmPost['submenu'])) && ($this->parmPost['submenu']) !== '') {
            $this->m_submenu = $this->parmPost['submenu'];
        } elseif ((isset($this->parmGet['submenu'])) && ($this->parmGet['submenu']) !== '') {
            $this->m_submenu = $this->parmGet['submenu'];
        } else {
            $this->m_submenu = '';
        }

        //set idMdf
        //$this->m_idMdf = (isset($this->parmGet['idMdf']) ? $this->parmGet['idMdf'] : $this->parmPost['idMdf'] ? $this->parmPost['idMdf'] : '');
        if ((isset($this->parmPost['idMdf'])) && ($this->parmPost['idMdf']) !== '') {
            $this->m_idMdf = $this->parmPost['idMdf'];
        } elseif ((isset($this->parmGet['idMdf'])) && ($this->parmGet['idMdf']) !== '') {
            $this->m_idMdf = $this->parmGet['idMdf'];
        } else {
            $this->m_idMdf = $this->parmPost['id'];
        }

        $this->m_idNF = isset($this->parmGet['idNF']) ? $this->parmGet['idNF'] : (isset($this->parmPost['idNF']) ? $this->parmPost['idNF'] : '');
        $this->m_opcao = $this->parmPost['opcao'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // metodo SET dos dados do FORM para o OBJETO
        $this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : "");
        $this->setNumMdf(isset($this->parmPost['mdf']) ? $this->parmPost['mdf'] : "0");
        $this->setChaveAcessoMdfe(isset($this->parmPost['chaveAcessoMdfe']) ? $this->parmPost['chaveAcessoMdfe'] : "");

        $this->setSerie((isset($this->parmPost['serie']) and $this->parmPost['serie'] !== "") ? $this->parmPost['serie'] : '0');

        $this->setModelo(isset($this->parmPost['modelo']) ? $this->parmPost['modelo'] : "58");
        $this->setSituacao(isset($this->parmPost['situacao']) ? $this->parmPost['situacao'] : "A");
        $this->setCentroCusto(isset($this->parmPost['centroCusto']) ? $this->parmPost['centroCusto'] : "");
        $this->setCDigitoVerificador(isset($this->parmPost['digVal']) ? $this->parmPost['digVal'] : 0);
        $this->setTipoTransportador(isset($this->parmPost['tipoTransportador']) ? $this->parmPost['tipoTransportador'] : "");
        $this->setTransportador(isset($this->parmPost['transportador']) ? $this->parmPost['transportador'] : null);
        $this->setCondutor(isset($this->parmPost['condutor']) ? $this->parmPost['condutor'] : "");
        $this->setModal(isset($this->parmPost['modal']) ? $this->parmPost['modal'] : "1");
        $this->setDataHora(isset($this->parmPost['dataHora']) ? $this->parmPost['dataHora'] : date("Y/m/d H:i"));
        $this->setTipoEmitente(isset($this->parmPost['tipoEmitente']) ? $this->parmPost['tipoEmitente'] : "");
        $this->setProcEmissao(isset($this->parmPost['procEmissao']) ? $this->parmPost['procEmissao'] : "");
        $this->setVerProc(isset($this->parmPost['verAplic']) ? $this->parmPost['verAplic'] : "");
        $this->setDhRecbto(isset($this->parmPost['dhrecbto']) ? $this->parmPost['dhrecbto'] : "");
        $this->setDigVal(isset($this->parmPost['digval']) ? $this->parmPost['digval'] : "");
        $this->setUfIni(isset($this->parmPost['ufini']) ? $this->parmPost['ufini'] : "");
        $this->setUfFim(isset($this->parmPost['uffim']) ? $this->parmPost['uffim'] : "");
        $this->setReciboMdfe(isset($this->parmPost['']) ? $this->parmPost['dhRecbto'] : "");
        $this->setProtocoloMdfe(isset($this->parmPost['nProt']) ? $this->parmPost['nProt'] : "");
        $this->setProtocoloCancelamento(isset($this->parmPost['protocoloCancelamento']) ? $this->parmPost['protocoloCancelamento'] : "");
        $this->setJustificativaCancelamento(isset($this->parmPost['justificativaCancelamento']) ? $this->parmPost['justificativaCancelamento'] : "");
        $this->setProtocoloEncerramento(isset($this->parmPost['protocoloEncerramento']) ? $this->parmPost['protocoloEncerramento'] : "");
        $this->setInfMunCarrega(isset($this->parmPost['infMunCarrega']) ? $this->parmPost['infMunCarrega'] : "");
        $this->setQuantCte(isset($this->parmPost['quantCte']) ? $this->parmPost['quantCte'] : '0');
        $this->setQuantNfe(isset($this->parmPost['quantNfe']) ? $this->parmPost['quantNfe'] : '0');
        $this->setQuantMdfe(isset($this->parmPost['quantMdfe']) ? $this->parmPost['quantMdfe'] : '0');
        $this->setTotalCarga(isset($this->parmPost['totalcarga']) ? $this->parmPost['totalcarga'] : '0.00');
        $this->setUnidadeCarga(isset($this->parmPost['unidadecarga']) ? $this->parmPost['unidadecarga'] : "");
        $this->setPesoCarga(isset($this->parmPost['pesocarga']) ? $this->parmPost['pesocarga'] : "0,00");
        $this->setLacre(isset($this->parmPost['lacre']) ? $this->parmPost['lacre'] : "");
        $this->setRodoRntrc(isset($this->parmPost['rodoRntrc']) ? $this->parmPost['rodoRntrc'] : "");
        $this->setObservacao(isset($this->parmPost['observacao']) ? $this->parmPost['observacao'] : "");
        $this->setObservacaoFisco(isset($this->parmPost['observacaoFisco']) ? $this->parmPost['observacaoFisco'] : "");
        $this->setUsrSituacao(isset($this->parmPost['usrSituacao']) ? $this->parmPost['usrSituacao'] : "");
        $this->setEmissao(isset($this->parmPost['emissao']) ? $this->parmPost['emissao'] : date("Y/m/d H:i"));
        $this->setHora(isset($this->parmPost['hora']) ? $this->parmPost['hora'] : date("H:i"));
        $this->setUsrEmissao(isset($this->parmPost['usrEmissao']) ? $this->parmPost['usrEmissao'] : "");
        $this->setVeiculoTracao(isset($this->parmPost['veiculoTracao']) ? $this->parmPost['veiculoTracao'] : "");
        $this->setRodoCodAgPorto(isset($this->parmPost['rodoCodAgPorto']) ? $this->parmPost['rodoCodAgPorto'] : "");
        $this->setVeiculoReboque1(isset($this->parmPost['veiculoReboque1']) ? $this->parmPost['veiculoReboque1'] : "");
        $this->setVeiculoReboque2(isset($this->parmPost['veiculoReboque2']) ? $this->parmPost['veiculoReboque2'] : "");
        $this->setVeiculoReboque3(isset($this->parmPost['veiculoReboque3']) ? $this->parmPost['veiculoReboque3'] : "");
        $this->setProdPredTipoCarga(isset($this->parmPost['prodPredTipoCarga']) ? $this->parmPost['prodPredTipoCarga'] : "");
        $this->setProdPredDescricao(isset($this->parmPost['prodPredDescricao']) ? $this->parmPost['prodPredDescricao'] : "");
        $this->setProdPredGtin(isset($this->parmPost['prodPredGtin']) ? $this->parmPost['prodPredGtin'] : "");
        $this->setProdPredNcm(isset($this->parmPost['prodPredNcm']) ? $this->parmPost['prodPredNcm'] : "");
        $this->setProdPredCepLocalCarrega(isset($this->parmPost['prodPredCepLocalCarrega']) ? $this->parmPost['prodPredCepLocalCarrega'] : "");
        $this->setProdPredCepLocalDescarreg(isset($this->parmPost['prodPredCepLocalDescarrega']) ? $this->parmPost['prodPredCepLocalDescarrega'] : "");
        $this->setJustificativa(isset($this->parmPost['justificativa']) ? $this->parmPost['justificativa'] : "");

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Manifesto Fiscal");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8,9,10, 11 ]");
        $this->smarty->assign('disableSort', "[ 11 ]");
        $this->smarty->assign('numLine', "25");
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
        switch ($this->m_submenu) {
            case 'cadastrar':
                $this->desenhaCadastroManifestoFiscal('');
                break;
            case 'alterar':
                $this->setManifestoFiscal($this->m_idMdf);
                $pesqNF = c_manifesto_fiscal_nf::buscaNotaFiscalMdf($this->m_idMdf);
                //tratamento para quando nao existe nf relacionada, inserir um valor para nao dar erro na funcao js que está sendo chamada
                if ($pesqNF == null) {
                    $pesqNF = $this->m_idMdf;
                }
                $response = json_encode($pesqNF, JSON_FORCE_OBJECT);
                $this->desenhaCadastroManifestoFiscal('');
                echo "<script>atualizaTabelaNotaFiscal(" . $response . ");</script>";
                break;
            case 'altera':
                try {
                    $msg = '';
                    $tipoMsg = 'sucesso';
                    $verMdf = $this->selectManifestoFiscal($this->getId());

                    if ($verMdf[0]['SITUACAO'] == 'B') { // verificar se a nota esta baixada
                        $msg = 'Manifesto fiscal foi Baixado, não sendo possivel alterar!';
                        //envia mensagem para a tela mostra
                        $msgRetorno = 'Manifesto fiscal foi Baixado, não sendo possivel alterar!';
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: '" . $msgRetorno . ".',
                            confirmButtonText: 'OK'
                        });
                        </script>";


                        $this->mostraManifestoFiscal('');
                        break;
                    } else {
                        $result = $this->alteraNotaFiscal();
                        if ($result == true) {
                            //envia mensagem para a tela mostra
                            $msgRetorno = 'Manifesto Alterado!';
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->mostraManifestoFiscal('');
                        } else {
                            //envia mensagem para a tela mostra
                            $msgRetorno = 'Não foi possível alterar o manifesto, contate o suporte!';
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->mostraManifestoFiscal('');
                        }
                    }
                } catch (Exception $e) {
                    //envia mensagem para a tela mostra
                    $msgRetorno = $e->getMessage();
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";

                    $this->mostraManifestoFiscal('');
                    break;
                }
                break;
            case 'inclui':
                $numMdf = 0;
                $this->setNumMdf($numMdf);
                $this->m_msg = $this->incluiManifestoFiscal();
                if (is_numeric($this->m_msg)) {
                    $this->mostraManifestoFiscal(' Manifesto fiscal ' . $this->getId() . ' Cadastrado.', 'sucesso');
                } else {
                    $this->mostraManifestoFiscal($this->m_msg, '');
                }
                break;
            case 'exclui':
                $mdfe = $this->selectManifestoFiscal($this->m_idMdf);
                $opcao = array('B', 'C', 'E'); //situacoes que nao permite a exclusao
                //verifica se o manifesto esta baixado
                if (!in_array($mdfe[0]['SITUACAO'], $opcao)) {
                    if ($excManifesto = c_manifesto_fiscal::excluiManifestoFiscal($this->m_idMdf)) { //exclui o manifesto

                        if ($updateNfe = c_manifesto_fiscal_nf::removeMdfNumMdf($this->m_idMdf)) { //atualiza nota fiscal removendo o id mdf
                            //envia mensagem para a tela mostra
                            $msgRetorno = 'Manifesto excluído!';
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";

                            $this->mostraManifestoFiscal('');
                        } else {
                            //envia mensagem para a tela mostra
                            $msgRetorno = 'Não foi possível remover o MDFe da(s) nota(s) Fiscal(is)!';
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->mostraManifestoFiscal('');
                        }
                    } else {
                        //envia mensagem para a tela mostra
                        $msgRetorno = 'Não foi possível realizar a exclusão do manifesto!';
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        $this->mostraManifestoFiscal('');
                    }
                } else {
                    //envia mensagem para a tela mostra
                    $msgRetorno = 'Status atual do manifesto não permite a exclusão!';
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                    $this->mostraManifestoFiscal('');
                }
                break;
            case 'imprimir':
                $print = c_manifesto_fiscal_nf::buscaMdf($this->m_idMdf);

                if ($print !== null) {
                    $this->mostraManifestoFiscal('');
                    echo "<script>imprimeDamdfe('" . $this->m_idMdf . "');</script>";
                } else {
                    //envia mensagem para a tela mostra
                    $msgRetorno = 'Manifesto não localizado!';
                    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                    echo "<style>.swal-modal{width: 510px !important;}.swal-title{font-size: 21px;}</style> ";
                    echo "<script>swal({text: `$msgRetorno`, title: 'Atenção!', icon: 'warning',button: 'Ok',});</script>";
                    $this->mostraManifestoFiscal('');
                }
                break;
            case 'cancelarMdfe':
                //verifica se existe mdf
                $arrayMdf = c_manifesto_fiscal_nf::buscaMdf($this->m_idMdf);
                if (($arrayMdf[0]["SITUACAO"] !== 'B') or ($arrayMdf[0]["SITUACAO"] !== 'E')) {
                    $processo = $this->MdfeCancelar($this->m_idMdf, $arrayMdf[0]["CHAVEACESSOMDFE"], $arrayMdf[0]["PROTOCOLOMDFE"], $this->getJustificativa());

                    $return = $processo;
                    header('Content-type: application/json');
                    echo json_encode($return, JSON_FORCE_OBJECT);
                } else {
                    $return = 'MDFe não pode ser cancelada';
                    header('Content-type: application/json');
                    echo json_encode($return, JSON_FORCE_OBJECT);
                }
                break;
            case 'addNotaFiscal':
                //verifica se existe mdf
                $existeMdf = c_manifesto_fiscal_nf::buscaMdf($this->m_idMdf);
                if ($existeMdf !== null) {
                    //adiciona Mdf na nota fiscal
                    $addMdf = c_manifesto_fiscal_nf::addMdfNotaFiscal($this->m_idNF, $this->m_idMdf);
                    if ($addMdf == true) {
                        //busca notas fiscais da Mdf
                        $notasFiscais = c_manifesto_fiscal_nf::buscaNotaFiscalMdf($this->m_idMdf);
                        $lanc = $notasFiscais;
                        $return = $lanc;
                        //tratamento para quando nao existe nf relacionada, inserir um valor para nao dar erro na funcao js que está sendo chamada
                        if ($return == null) {
                            $return = $this->m_idMdf;
                        }
                        header('Content-type: application/json');
                        echo json_encode($return, JSON_FORCE_OBJECT);
                    }
                } else {
                    $return = null;
                    header('Content-type: application/json');
                    echo json_encode($return, JSON_FORCE_OBJECT);
                }
                die;
                break;
            case 'removeNotaFiscal':
                //verifica se existe mdf
                $existeMdf = c_manifesto_fiscal_nf::buscaMdf($this->m_idMdf);
                if ($existeMdf !== null) {
                    //remove Mdf na nota fiscal
                    $addMdf = c_manifesto_fiscal_nf::removeMdfNotaFiscal($this->m_idNF, $this->m_idMdf);
                    if ($addMdf == true) {
                        //busca notas fiscais da Mdf
                        $notasFiscais = c_manifesto_fiscal_nf::buscaNotaFiscalMdf($this->m_idMdf);
                        $lanc = $notasFiscais;
                        $return = $lanc;
                        header('Content-type: application/json');
                        echo json_encode($return, JSON_FORCE_OBJECT);
                    }
                } else {
                    $return = null;
                    header('Content-type: application/json');
                    echo json_encode($return, JSON_FORCE_OBJECT);
                }
                die;
                break;
            case 'geraXmlManifesto':
                // Gera e altera numero MDF
                $this->setManifestoFiscal($this->m_idMdf);
                if ($this->getNumMdf() == 0):
                    $numNf = $this->geraNumMdf($this->getModelo(), $this->getSerie(), $this->getCentroCusto());
                    if (intval($numNf) == 0):
                        $this->m_msg = "Idendificador NF >>> " . $idGerado . " - Número não Gerado";
                        $result = false;
                        throw new Exception($this->m_msg);
                    endif;
                    $this->setNumMdf($numNf);
                    $this->alteraNfNumero();
                endif;

                // valida e autoriza nf
                $objClassMan = new c_manifesto_fiscal;
                $resultManifesto = $objClassMan->MdfeTestaEnvio($this->getId(), $this->m_empresacentrocusto);

                if ($resultManifesto == '100') {
                    $msgRetorno = 'Manifesto emitido!';
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                } else {
                    $msgRetorno = $resultManifesto . "!";
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                }
                $this->mostraManifestoFiscal('');
                sleep(2);
                if ($resultManifesto == '100') {
                    echo "<script>imprimeDamdfe('" . $this->m_idMdf . "');</script>";
                }
                break;
            case 'encerraMdfe':
                $result = $this->MdfeEncerramento($this->m_idMdf);

                if ($result == '135') {
                    $msgRetorno = 'Manifesto Encerrado!';
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                } elseif ($result == '631') {
                    $msgRetorno = 'Duplicidade de Evento!';
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                } else {
                    $msgRetorno = 'Entre em contato com suporte - ' . $result;
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                }
                $this->mostraManifestoFiscal('');
                break;
            case 'consultaStatusMdfe':
                if (($this->m_idMdf == null) or ($this->m_idMdf == '')) {
                    $msgRetorno = 'Chave de acesso não localizada';
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                    break;
                } else {
                    $resultCons = $this->MdfeConsultarChave($this->m_idMdf);
                }
                //trata msg e informa na tela mostra
                switch ($resultCons['codStatus']) {
                    case '132':
                        $msgRetorno = $resultCons['msgReturn'];
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'sucess',
                                title: 'Sucesso',
                                width: 420,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        break;
                    case '100':
                        $msgRetorno = $resultCons['msgReturn'];
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 420,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        break;
                    case '404':
                        $msgRetorno = $resultCons['msgReturn'];
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 480,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        break;
                    case '101':
                        $msgRetorno = $resultCons['msgReturn'];
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 420,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        break;
                }
                $this->mostraManifestoFiscal('');
                break;
            case 'pesquisaCondutorAjax':
                $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $termAjax = (isset($parmPost['term']) ? $parmPost['term'] : '');

                $objConta = new c_conta();
                $resultPesq = $objConta->select_pessoa_letra($termAjax);
                for ($i = 0; $i < count($resultPesq); $i++) {
                    $clienteResult[$i]['id'] = trim($resultPesq[$i]['CLIENTE']);
                    $clienteResult[$i]['text'] = trim($resultPesq[$i]['NOME']);
                }
                echo json_encode($clienteResult);
                break;
            case 'consultaNaoEncerrados':
                $resultCons = $this->MdfeConsultaEncerrado();

                if ($resultCons["result"] == '112') {
                    $msgRetorno = 'Sefaz';
                    $mdfs = $resultCons["msgReturn"];
                } else {
                    $msgRetorno = $resultCons['msgReturn'];

                    //verifica se o reotorno é objeto
                    if (is_object($resultCons)) {
                        $mdfsTemp = array();
                        for ($i = 0; $i < count($resultCons["result"]); $i++) {
                            $mdfsTemp[$i] = substr($resultCons["result"][$i]->chMDFe, 25, 9);
                        }
                    } else {
                        $mdfsTemp = substr($resultCons["result"]->chMDFe, 25, 9);
                    }

                    $mdfs = json_encode($mdfsTemp);
                }

                echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 753,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                $this->mostraManifestoFiscal('');
                break;
            default:
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'C')) {
                    $this->mostraManifestoFiscal('');
                }
        }
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function desenhaCadastroManifestoFiscal($mensagem = NULL, $tipoMsg = NULL)
    {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('modelo', '58');
        $this->smarty->assign('serie', $this->getSerie());
        $this->smarty->assign('mdf', $this->getNumMdf());
        $this->smarty->assign('chaveacessomdfe', "'" . $this->getChaveAcessoMdfe() . "'");

        $this->smarty->assign('emissao', "'" . $this->getEmissao('F') . "'");

        $this->smarty->assign('datahora', "'" . $this->getDataHora('F') . "'");
        $this->smarty->assign('observacao', $this->getObservacao());
        $this->smarty->assign('observacaofisco', $this->getObservacaoFisco());

        if ($this->m_submenu !== 'cadastrar') {
            $this->smarty->assign('condutor', $this->getCondutor());
        } else {
            $this->smarty->assign('condutor', '');
        }

        if ($this->getCondutor() != '') { // VERIFICAR se existe codigo do cliente para setar no nome ajx
            $arrPessoa = $this->selectPessoa();
            $this->smarty->assign('nomecondutor', "'" . $arrPessoa[0]["NOME"] . "'");
        }

        $this->smarty->assign('pesocarga', $this->getPesoCarga('F'));
        $this->smarty->assign('protocolomdfe', $this->getProtocoloMdfe());
        $this->smarty->assign('hora', "'" . $this->getHora() . "'");
        $this->smarty->assign('idMdf', "'" . $this->m_idMdf . "'");
        $this->smarty->assign('verproc', "'" . $this->getVerProc() . "'");
        $this->smarty->assign('dhrecbto', "'" . $this->getDhRecbto() . "'");
        $this->smarty->assign('digval', "'" . $this->getDigVal() . "'");
        $this->smarty->assign('veraplic', "'" . $this->getVerAplic() . "'");

        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        $this->smarty->assign('filial_id', $this->getCentroCusto());

        // Veiculo
        $consulta = new c_banco();
        $sql = "select idveiculo as id, nome from est_veiculo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $veiculo_ids[0] = '';
        $veiculo_names[0] = 'Selecione um veiculo';
        for ($i = 0; $i < count($result); $i++) {
            $veiculo_ids[$i + 1] = $result[$i]['ID'];
            $veiculo_names[$i + 1] = $result[$i]['NOME'];
        }
        $this->smarty->assign('veiculo_ids', $veiculo_ids);
        $this->smarty->assign('veiculo_names', $veiculo_names);
        $this->smarty->assign('veiculo_id', $this->getVeiculoTracao());

        // Código da Unidade de Medida
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='codunidade')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $unidadecarga_ids[$i] = $result[$i]['ID'];
            $unidadecarga_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('unidadecarga_ids', $unidadecarga_ids);
        $this->smarty->assign('unidadecarga_names', $unidadecarga_names);
        $this->smarty->assign('unidadecarga_ids_id', $this->getUnidadeCarga());

        //situacao
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='SituacaoNota')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $this->getsituacao());


        // ########## CENTROCUSTO ##########
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo order by centrocusto";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $centroCusto_ids[$i] = $result[$i]['ID'];
            $centroCusto_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto());

        // ########## UF Ini ##########
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='ESTADO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $ufini_ids[$i] = $result[$i]['ID'];
            $ufini_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('ufini_ids', $ufini_ids);
        $this->smarty->assign('ufini_names', $ufini_names);
        $this->smarty->assign('ufini_id', $this->getUfIni());

        // ########## UF Fim ##########
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='ESTADO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $uffim_ids[$i] = $result[$i]['ID'];
            $uffim_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('uffim_ids', $uffim_ids);
        $this->smarty->assign('uffim_names', $uffim_names);
        $this->smarty->assign('uffim_id', $this->getUfFim());

        $this->smarty->display('manifesto_fiscal_cadastro.tpl');
    }

    //fim desenhaCadastroManifestoFiscal
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraManifestoFiscal($mensagem = NULL,  $tipoMsg = NULL, $file = '')
    {

        if ($this->m_letra != '') {
            $lanc = $this->selectManifestoFiscalLetra($this->m_letra);
        }

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('mdf', $this->m_par[4]);
        $this->smarty->assign('serie', $this->m_par[5]);

        if ($this->m_par[2] == "")
            $this->smarty->assign('dataIni', date("01/m/Y"));
        else
            $this->smarty->assign('dataIni', $this->m_par[2]);

        if ($this->m_par[3] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = mktime(0, 0, 0, $mes + 1, 0, $ano);
            $this->smarty->assign('dataFim', date("d/m/Y", $data));
        } else
            $this->smarty->assign('dataFim', $this->m_par[3]);

        // pessoa
        if (($this->m_par[6] == "") and ($this->m_par[7] == "")) {
            $this->smarty->assign('condutor', "");
            $this->smarty->assign('nomecondutor', "");
        } else {
            $this->smarty->assign('condutor', $this->m_par[6]);
            $this->smarty->assign('nomecondutor', $this->m_par[7]);
        }

        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        if ((!is_null($this->m_par[0])) and ($this->m_par[0] != '')) {
            $this->smarty->assign('filial_id', $this->m_par[0]);
        } else {
            $this->smarty->assign('filial_id',  $this->m_empresacentrocusto);
        }

        //sql para mostrar a situacao no combobox
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='SituacaoNota')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $situacao_ids[0] = 0;
        $situacao_names[0] = 'Todas';
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        if ($this->m_par[1] == "")
            $this->smarty->assign('situacao_id', 'B');
        else
            $this->smarty->assign('situacao_id', $this->m_par[1]);


        $this->smarty->display('manifesto_fiscal_mostra.tpl');
    }

    function comboSql($sql, $par, &$id, &$ids, &$names)
    {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i] = $result[$i]['ID'];
            $names[$i] = $result[$i]['DESCRICAO'];
        }

        $param = explode(",", $par);
        $i = 0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }
    }
    //fim mostraManifestoFiscal
    //-------------------------------------------------------------
}



//	END OF THE CLASS
// Rotina principal - cria classe
$manifesto_fiscal = new p_manifesto_fiscal();

$manifesto_fiscal->controle();
