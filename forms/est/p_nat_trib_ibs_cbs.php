<?php
/**
 * @package   adm4.5
 * @name      p_nat_trib_ibs_cbs
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    ADM Service
 * @date      18/12/2025
 */

if (!defined('ADMpath')): exit;
endif;

$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_nat_trib_ibs_cbs.php");

Class p_nat_trib_ibs_cbs extends c_nat_trib_ibs_cbs {

    private $m_submenu = NULL;
    private $m_opcao = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    //construtor
    function __construct() {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

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
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Tributos IBS/CBS");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5, 6, 7 ]");
        $this->smarty->assign('disableSort', "[ 7 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setIdNatOp(isset($parmPost['idNatOp']) ? $parmPost['idNatOp'] : (isset($parmPost['idNatop']) ? $parmPost['idNatop'] : ''));
        $this->setUfDest(isset($parmPost['uf_dest']) ? $parmPost['uf_dest'] : '');
        $this->setMunDest(isset($parmPost['mun_dest']) ? $parmPost['mun_dest'] : '');
        $this->setCodMunDest(isset($parmPost['cod_mun_dest']) ? $parmPost['cod_mun_dest'] : '');
        $this->setPessoa(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setCclasstrib(isset($parmPost['cclasstrib']) ? $parmPost['cclasstrib'] : '');
        $this->setNcm(isset($parmPost['ncm']) ? $parmPost['ncm'] : '');
        $this->setIbsUf(isset($parmPost['ibs_uf']) ? $parmPost['ibs_uf'] : '0');
        $this->setIbsMun(isset($parmPost['ibs_mun']) ? $parmPost['ibs_mun'] : '0');
        $this->setCbs(isset($parmPost['cbs']) ? $parmPost['cbs'] : '0');
    }

    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstTribIbsCbs', 'I')) {
                    $this->desenhaCadastro();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstTribIbsCbs', 'A')) {
                    $this->carregaRegistro();
                    $this->desenhaCadastro();
                }
                break;
            case 'copiar':
                if ($this->verificaDireitoUsuario('EstTribIbsCbs', 'I')) {
                    $this->carregaRegistro();
                    $this->setId(''); // Limpa o ID para criar novo
                    $this->m_submenu = 'cadastrar';
                    $this->desenhaCadastro();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstTribIbsCbs', 'I')) {
                    if ($this->incluiTribIbsCbs()) {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            width: 510,
                            text: 'Tributo IBS/CBS cadastrado com sucesso!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->mostraTributos();
                    } else {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            width: 510,
                            text: 'Erro ao cadastrar Tributo IBS/CBS!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->desenhaCadastro();
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstTribIbsCbs', 'A')) {
                    if ($this->alteraTribIbsCbs()) {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            width: 510,
                            text: 'Tributo IBS/CBS alterado com sucesso!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->mostraTributos();
                    } else {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: 'Erro ao alterar Tributo IBS/CBS!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->desenhaCadastro();
                    }
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstTribIbsCbs', 'E')) {
                    if ($this->excluiTribIbsCbs()) {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            width: 510,
                            text: 'Tributo IBS/CBS excluído com sucesso!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->mostraTributos();
                    } else {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: 'Erro ao excluir Tributo IBS/CBS!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->mostraTributos();
                    }
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstTribIbsCbs', 'C')) {
                    $this->mostraTributos();
                }
        }
    }

    function carregaRegistro() {
        $registro = $this->selectTribIbsCbsID();
        if (is_array($registro) && count($registro) > 0) {
            $this->setId($registro[0]['ID']);
            $this->setIdNatOp($registro[0]['ID_EST_NAT_OP']);
            $this->setUfDest($registro[0]['UF_DEST']);
            $this->setMunDest($registro[0]['MUN_DEST']);
            $this->setCodMunDest($registro[0]['COD_MUN_DEST']);
            $this->setPessoa($registro[0]['TIPO_PESSOA']);
            $this->setCclasstrib($registro[0]['CCLASSTRIB']);
            $this->setNcm($registro[0]['NCM']);
            $this->setIbsUf($registro[0]['ALIQUOTA_IBS_UF']);
            $this->setIbsMun($registro[0]['ALIQUOTA_IBS_MUN']);
            $this->setCbs($registro[0]['ALIQUOTA_CBS']);
        }
    }

    function desenhaCadastro() {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);

        // Campos
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idNatOp', $this->getIdNatOp());
        $this->smarty->assign('uf_dest', $this->getUfDest());
        $this->smarty->assign('mun_dest', $this->getMunDest());
        $this->smarty->assign('cod_mun_dest', $this->getCodMunDest());
        $this->smarty->assign('pessoa', $this->getPessoa());
        $this->smarty->assign('cclasstrib', $this->getCclasstrib());
        $this->smarty->assign('ncm', $this->getNcm());
        $this->smarty->assign('ibs_uf', $this->getIbsUf('F'));
        $this->smarty->assign('ibs_mun', $this->getIbsMun('F'));
        $this->smarty->assign('cbs', $this->getCbs('F'));

        // Descrição da Natureza de Operação
        $this->smarty->assign('natOperacao', $this->getNatOperacaoDescricao());

        // Combo UF
        $ufCombo = $this->getUfCombo();
        $this->smarty->assign('uf_ids', $ufCombo['ids']);
        $this->smarty->assign('uf_names', $ufCombo['names']);

        // Combo Pessoa
        $pessoaCombo = $this->getPessoaCombo();
        $this->smarty->assign('pessoa_ids', $pessoaCombo['ids']);
        $this->smarty->assign('pessoa_names', $pessoaCombo['names']);

        // Combo CClassTrib
        $cclasstribCombo = $this->getCclasstribCombo();
        $this->smarty->assign('cclasstrib_ids', $cclasstribCombo['ids']);
        $this->smarty->assign('cclasstrib_names', $cclasstribCombo['names']);

        // Combo NCM
        $ncmCombo = $this->getNcmCombo();
        $this->smarty->assign('ncm_ids', $ncmCombo['ids']);
        $this->smarty->assign('ncm_names', $ncmCombo['names']);

        $this->smarty->display('nat_trib_ibs_cbs_cadastro.tpl');
    }

    function mostraTributos() {
        $lanc = $this->selectTribIbsCbs();

        // Descrição da Natureza de Operação
        $this->smarty->assign('natOperacao', $this->getNatOperacaoDescricao());

        $this->smarty->assign('pathImagem', '');
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('idNatOp', $this->getIdNatOp());

        $this->smarty->display('nat_trib_ibs_cbs_mostra.tpl');
    }
}

// Rotina principal - instacia objeto
$natTribIbsCbs = new p_nat_trib_ibs_cbs();
$natTribIbsCbs->controle();
?>
