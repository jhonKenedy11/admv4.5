<?php
/**
 * @package   adm4.5
 * @name      p_cclasstrib
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    ADM Service
 * @date      17/12/2025
 */

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_cclasstrib.php");


Class p_cclasstrib extends c_cclasstrib {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    //construtor
    function __construct() {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

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
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "CClassTrib");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4 ]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setCclasstrib(isset($parmPost['cclasstrib']) ? $parmPost['cclasstrib'] : '');
        $this->setNome(isset($parmPost['nome']) ? $parmPost['nome'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        $this->setCst(isset($parmPost['cst']) ? $parmPost['cst'] : '');
        $this->setLcRedacao(isset($parmPost['lc_redacao']) ? $parmPost['lc_redacao'] : '');
        $this->setLc21425(isset($parmPost['lc_214_25']) ? $parmPost['lc_214_25'] : '');
        $this->setRegulamentoCbs(isset($parmPost['regulamento_cbs']) ? $parmPost['regulamento_cbs'] : '');
        $this->setRegulamentoIbs(isset($parmPost['regulamento_ibs']) ? $parmPost['regulamento_ibs'] : '');
        $this->setTipoAliquota(isset($parmPost['tipo_aliquota']) ? $parmPost['tipo_aliquota'] : '');
        $this->setPredIbs(isset($parmPost['pred_ibs']) ? $parmPost['pred_ibs'] : 0);
        $this->setPredCbs(isset($parmPost['pred_cbs']) ? $parmPost['pred_cbs'] : 0);
        $this->setIndGTribRegular(isset($parmPost['ind_g_trib_regular']) ? $parmPost['ind_g_trib_regular'] : 0);
        $this->setIndGCredPresOper(isset($parmPost['ind_g_cred_pres_oper']) ? $parmPost['ind_g_cred_pres_oper'] : 0);
        $this->setIndGMonoPadrao(isset($parmPost['ind_g_mono_padrao']) ? $parmPost['ind_g_mono_padrao'] : 0);
        $this->setIndGMonoReten(isset($parmPost['ind_g_mono_reten']) ? $parmPost['ind_g_mono_reten'] : 0);
        $this->setIndGMonoRet(isset($parmPost['ind_g_mono_ret']) ? $parmPost['ind_g_mono_ret'] : 0);
        $this->setIndGpBioDiferenca(isset($parmPost['ind_gp_bio_diferenca']) ? $parmPost['ind_gp_bio_diferenca'] : 0);
        $this->setIndGEstornoCred(isset($parmPost['ind_g_estorno_cred']) ? $parmPost['ind_g_estorno_cred'] : 0);
        $this->setTpRbsn(isset($parmPost['tp_rbsn']) ? $parmPost['tp_rbsn'] : 0);
        $this->setDIniVig(isset($parmPost['d_ini_vig']) ? $parmPost['d_ini_vig'] : '');
        $this->setDFimVig(isset($parmPost['d_fim_vig']) ? $parmPost['d_fim_vig'] : '');
        $this->setDataAtualizacao(isset($parmPost['data_atualizacao']) ? $parmPost['data_atualizacao'] : '');
        $this->setIndNfeAbi(isset($parmPost['ind_nfe_abi']) ? $parmPost['ind_nfe_abi'] : 0);
        $this->setIndNfe(isset($parmPost['ind_nfe']) ? $parmPost['ind_nfe'] : 0);
        $this->setIndNfCe(isset($parmPost['ind_nf_ce']) ? $parmPost['ind_nf_ce'] : 0);
        $this->setIndCte(isset($parmPost['ind_cte']) ? $parmPost['ind_cte'] : 0);
        $this->setIndCteOs(isset($parmPost['ind_cte_os']) ? $parmPost['ind_cte_os'] : 0);
        $this->setIndBpe(isset($parmPost['ind_bpe']) ? $parmPost['ind_bpe'] : 0);
        $this->setIndBpeTa(isset($parmPost['ind_bpe_ta']) ? $parmPost['ind_bpe_ta'] : 0);
        $this->setIndBpeTm(isset($parmPost['ind_bpe_tm']) ? $parmPost['ind_bpe_tm'] : 0);
        $this->setIndNf3e(isset($parmPost['ind_nf_3e']) ? $parmPost['ind_nf_3e'] : 0);
        $this->setIndNfse(isset($parmPost['ind_nfse']) ? $parmPost['ind_nfse'] : 0);
        $this->setIndNfseVia(isset($parmPost['ind_nfse_via']) ? $parmPost['ind_nfse_via'] : 0);
        $this->setIndNfCom(isset($parmPost['ind_nf_com']) ? $parmPost['ind_nf_com'] : 0);
        $this->setIndNfAg(isset($parmPost['ind_nf_ag']) ? $parmPost['ind_nf_ag'] : 0);
        $this->setIndNfGas(isset($parmPost['ind_nf_gas']) ? $parmPost['ind_nf_gas'] : 0);
        $this->setIndDere(isset($parmPost['ind_dere']) ? $parmPost['ind_dere'] : 0);
        $this->setIndDir(isset($parmPost['ind_dir']) ? $parmPost['ind_dir'] : 0);
        $this->setIndDuimp(isset($parmPost['ind_duimp']) ? $parmPost['ind_duimp'] : 0);
    }//construtor


    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstCclasstrib', 'I')) {
                    $this->desenhaCadastroCclasstrib();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstCclasstrib', 'A')) {
                    $this->carregaRegistro();
                    $this->desenhaCadastroCclasstrib();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstCclasstrib', 'I')) {
                    if ($this->existeCclasstrib()) {
                        $this->m_submenu = "cadastrar";
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: 'Já existe registro com este código CClassTrib.',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->desenhaCadastroCclasstrib();
                    } else {
                        if ($this->incluiCclasstrib()) {
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: 'CClassTrib cadastrado com sucesso!',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->mostraCclasstrib();
                        } else {
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                width: 510,
                                text: 'Erro ao cadastrar CClassTrib!',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->desenhaCadastroCclasstrib();
                        }
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstCclasstrib', 'A')) {
                    if ($this->alteraCclasstrib()) {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            width: 510,
                            text: 'CClassTrib alterado com sucesso!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->mostraCclasstrib();
                    } else {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: 'Erro ao alterar CClassTrib!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->desenhaCadastroCclasstrib();
                    }
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstCclasstrib', 'C')) {
                    $this->mostraCclasstrib();
                }
        }
    }

 
    function carregaRegistro() {
        $registro = $this->selectCclasstribID();
        if (is_array($registro) && count($registro) > 0) {
            $this->setId($registro[0]['ID']);
            $this->setCclasstrib($registro[0]['CCLASSTRIB']);
            $this->setNome($registro[0]['NOME']);
            $this->setDescricao($registro[0]['DESCRICAO']);
            $this->setCst($registro[0]['CST']);
            $this->setLcRedacao($registro[0]['LC_REDACAO']);
            $this->setLc21425($registro[0]['LC_214_25']);
            $this->setRegulamentoCbs($registro[0]['REGULAMENTO_CBS']);
            $this->setRegulamentoIbs($registro[0]['REGULAMENTO_IBS']);
            $this->setTipoAliquota($registro[0]['TIPO_ALIQUOTA']);
            $this->setPredIbs($registro[0]['PRED_IBS']);
            $this->setPredCbs($registro[0]['PRED_CBS']);
            $this->setIndGTribRegular($registro[0]['IND_G_TRIB_REGULAR']);
            $this->setIndGCredPresOper($registro[0]['IND_G_CRED_PRES_OPER']);
            $this->setIndGMonoPadrao($registro[0]['IND_G_MONO_PADRAO']);
            $this->setIndGMonoReten($registro[0]['IND_G_MONO_RETEN']);
            $this->setIndGMonoRet($registro[0]['IND_G_MONO_RET']);
            $this->setIndGpBioDiferenca($registro[0]['IND_GP_BIO_DIFERENCA']);
            $this->setIndGEstornoCred($registro[0]['IND_G_ESTORNO_CRED']);
            $this->setTpRbsn($registro[0]['TP_RBSN']);
            $this->setDIniVig($registro[0]['D_INI_VIG']);
            $this->setDFimVig($registro[0]['D_FIM_VIG']);
            $this->setDataAtualizacao($registro[0]['DATA_ATUALIZACAO']);
            $this->setIndNfeAbi($registro[0]['IND_NFE_ABI']);
            $this->setIndNfe($registro[0]['IND_NFE']);
            $this->setIndNfCe($registro[0]['IND_NF_CE']);
            $this->setIndCte($registro[0]['IND_CTE']);
            $this->setIndCteOs($registro[0]['IND_CTE_OS']);
            $this->setIndBpe($registro[0]['IND_BPE']);
            $this->setIndBpeTa($registro[0]['IND_BPE_TA']);
            $this->setIndBpeTm($registro[0]['IND_BPE_TM']);
            $this->setIndNf3e($registro[0]['IND_NF_3E']);
            $this->setIndNfse($registro[0]['IND_NFSE']);
            $this->setIndNfseVia($registro[0]['IND_NFSE_VIA']);
            $this->setIndNfCom($registro[0]['IND_NF_COM']);
            $this->setIndNfAg($registro[0]['IND_NF_AG']);
            $this->setIndNfGas($registro[0]['IND_NF_GAS']);
            $this->setIndDere($registro[0]['IND_DERE']);
            $this->setIndDir($registro[0]['IND_DIR']);
            $this->setIndDuimp($registro[0]['IND_DUIMP']);
        }
    }


    function desenhaCadastroCclasstrib() {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);

        // Campos principais
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('cclasstrib', "'" . $this->getCclasstrib() . "'");        
        $this->smarty->assign('nome', "'" . $this->getNome() . "'");
        $this->smarty->assign('descricao', "'" . $this->getDescricao() . "'");
        $this->smarty->assign('lc_redacao', $this->getLcRedacao());
        $this->smarty->assign('lc_214_25', "'" . $this->getLc21425() . "'");
        $this->smarty->assign('regulamento_cbs', $this->getRegulamentoCbs());
        $this->smarty->assign('regulamento_ibs', $this->getRegulamentoIbs());
        $this->smarty->assign('tipo_aliquota', "'" . $this->getTipoAliquota() . "'");
        
        // Indicadores numéricos
        $this->smarty->assign('pred_ibs', $this->getPredIbs());
        $this->smarty->assign('pred_cbs', $this->getPredCbs());
        $this->smarty->assign('ind_g_trib_regular', $this->getIndGTribRegular());
        $this->smarty->assign('ind_g_cred_pres_oper', $this->getIndGCredPresOper());
        $this->smarty->assign('ind_g_mono_padrao', $this->getIndGMonoPadrao());
        $this->smarty->assign('ind_g_mono_reten', $this->getIndGMonoReten());
        $this->smarty->assign('ind_g_mono_ret', $this->getIndGMonoRet());
        $this->smarty->assign('ind_gp_bio_diferenca', $this->getIndGpBioDiferenca());
        $this->smarty->assign('ind_g_estorno_cred', $this->getIndGEstornoCred());
        $this->smarty->assign('tp_rbsn', $this->getTpRbsn());
        
        // Datas
        $this->smarty->assign('d_ini_vig', $this->getDIniVig('T'));
        $this->smarty->assign('d_fim_vig', $this->getDFimVig('T'));
        $this->smarty->assign('data_atualizacao', $this->getDataAtualizacao('T'));
        
        // Indicadores de documentos
        $this->smarty->assign('ind_nfe_abi', $this->getIndNfeAbi());
        $this->smarty->assign('ind_nfe', $this->getIndNfe());
        $this->smarty->assign('ind_nf_ce', $this->getIndNfCe());
        $this->smarty->assign('ind_cte', $this->getIndCte());
        $this->smarty->assign('ind_cte_os', $this->getIndCteOs());
        $this->smarty->assign('ind_bpe', $this->getIndBpe());
        $this->smarty->assign('ind_bpe_ta', $this->getIndBpeTa());
        $this->smarty->assign('ind_bpe_tm', $this->getIndBpeTm());
        $this->smarty->assign('ind_nf_3e', $this->getIndNf3e());
        $this->smarty->assign('ind_nfse', $this->getIndNfse());
        $this->smarty->assign('ind_nfse_via', $this->getIndNfseVia());
        $this->smarty->assign('ind_nf_com', $this->getIndNfCom());
        $this->smarty->assign('ind_nf_ag', $this->getIndNfAg());
        $this->smarty->assign('ind_nf_gas', $this->getIndNfGas());
        $this->smarty->assign('ind_dere', $this->getIndDere());
        $this->smarty->assign('ind_dir', $this->getIndDir());
        $this->smarty->assign('ind_duimp', $this->getIndDuimp());

        // Combo CST
        $cstCombo = $this->getCstCombo();
        $this->smarty->assign('cst_ids', $cstCombo['ids']);
        $this->smarty->assign('cst_names', $cstCombo['names']);
        $this->smarty->assign('cst', $this->getCst());

        $this->smarty->display('cclasstrib_cadastro.tpl');
    }

    function mostraCclasstrib() {
        $lanc = $this->selectCclasstribGeral();

        $this->smarty->assign('pathImagem', '');
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->display('cclasstrib_mostra.tpl');
    }
}

// Rotina principal - instacia objeto
$cclasstrib = new p_cclasstrib();
$cclasstrib->controle();
?>

