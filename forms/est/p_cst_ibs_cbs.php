<?php
/**
 * @package   adm4.5
 * @name      p_cst_ibs_cbs
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      17/12/2025
 */

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_cst_ibs_cbs.php");


Class p_cst_ibs_cbs extends c_cst_ibs_cbs {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;


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
        
        $this->smarty->assign('titulo', "CST IBS/CBS");
        $this->smarty->assign('colVis', "[ 0,1,2]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setCst(isset($parmPost['cst']) ? $parmPost['cst'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
    }//construtor


    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstCstIbsCbs', 'I')) {
                    $this->desenhaCadastroCst();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstCstIbsCbs', 'A')) {
                    $registro = $this->selectCstID();
                    $this->setId($registro[0]['ID']);
                    $this->setCst($registro[0]['CST']);
                    $this->setDescricao($registro[0]['DESCRICAO']);
                    $this->desenhaCadastroCst();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstCstIbsCbs', 'I')) {
                    if ($this->existeCst()) {
                        $this->m_submenu = "cadastrar";
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: 'Já existe registro com este código CST.',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->desenhaCadastroCst();
                    } else {
                        if ($this->incluiCst()) {
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: 'CST cadastrado com sucesso!',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->mostraCst();
                        } else {
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                width: 510,
                                text: 'Erro ao cadastrar CST!',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->desenhaCadastroCst();
                        }
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstCstIbsCbs', 'A')) {
                    if ($this->alteraCst()) {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            width: 510,
                            text: 'CST alterado com sucesso!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->mostraCst();
                    } else {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: 'Erro ao alterar CST!',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                        $this->desenhaCadastroCst();
                    }
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstCstIbsCbs', 'C')) {
                    $this->mostraCst();
                }
        }
    }


    function desenhaCadastroCst() {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('cst', "'" . $this->getCst() . "'");        
        $this->smarty->assign('descricao', "'" . $this->getDescricao() . "'");

        $this->smarty->display('cst_ibs_cbs_cadastro.tpl');
    }

    function mostraCst() {
        $lanc = $this->selectCstGeral();

        $this->smarty->assign('pathImagem', '');
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->display('cst_ibs_cbs_mostra.tpl');
    }
}

// Rotina principal - instacia objeto
$cstIbsCbs = new p_cst_ibs_cbs();
$cstIbsCbs->controle();
?>

