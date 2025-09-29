<?php

/**
 * @package   adm4.1
 * @name      p_ncm
 * @version   4.1.00
 * @copyright 2019
 * @link      http://www.admservice.com.br/
 * @author    Rodrigo<Rodrigo@admservice.com.br>
 * @date      30/07/2019
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_ncm.php");

//Class P_situacao
Class p_ncm extends c_ncm {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;
    public $m_ncm = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    
    function __construct($submenu, $letra) {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_par = explode("|", $this->m_letra);
        $this->m_ncm = $ncm;
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        
        $this->smarty->assign('titulo', "Grupo Produtos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "25"); 
        
        // include do javascript
        // include ADMjs . "/est/s_grupo.js";
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstNcm', 'I')) {
                    $this->desenhaCadastroNcm();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstNcm', 'A')) {
                    $grupo = $this->selectNcmID();
                    $this->setId($grupo[0]['ID']);
                    $this->setNCM($grupo[0]['NCM']);
                    $this->setDescricao($grupo[0]['DESCRICAO']);
                    $this->setAliqIpi($grupo[0]['ALIQIPI']);
                    $this->setAliqPisMonofasica($grupo[0]['ALIQPISMONOFASICA']);
                    $this->setAliqCofinsMonofasica($grupo[0]['ALIQCOFINSMONOFASICA']);
                    $this->setAliqTTNacFederal($grupo[0]['ALIQTTNACFEDERAL']);
                    $this->setAliqTTImpFederal($grupo[0]['ALIQTTIMPFEDERAL']);
                    $this->setAliqTTEstadual($grupo[0]['ALIQTTESTADUAL']);
                    $this->setAliqTTMunicipal($grupo[0]['ALIQTTMUNICIPAL']);
                    $this->setVigenciaInicio($grupo[0]['VIGENCIAINICIO']);
                    $this->setVigenciaFim($grupo[0]['VIGENCIAFIM']);
                    $this->desenhaCadastroNcm();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstNcm', 'I')) {
                    if ($this->existeNcm()) {
                        $this->m_submenu = "cadastrar";
                        $this->desenhaCadastroNcm("Já existe registro com este código, por favor altere o codigo da NCM.",'alerta');
                    } else {
                        $this->incluiNcm()
                        ? $this->mostraNcm(msgAdd.' Ncm: '.$this->getDescricao(), typSuccess)
                        : $this->desenhaCadastroNcm(msgNotAdd, typError);
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstNcm', 'A')) {
                    $this->alteraNcm()
                    ? $this->mostraNcm(msgUpdate.' Ncm: '.$this->getDescricao(), typSuccess)
                    : $this->desenhaCadastroNcm(msgNotUpdate, typAlert);
                    
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstNcm', 'E')) {
                    $this->excluirNcm()
                    ? $this->mostraNcm(msgDelete.$this->getNcm(), typSuccess)
                    : $this->desenhaCadastroNcm(msgNotUpdate, typAlert);
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstNcm', 'C')) {
                    $this->mostraNcm('');
                }
        }
    }


    function desenhaCadastroNcm($mensagem = NULL,$tipoMsg=NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('ncm', "'" . $this->getNcm() . "'");        
        $this->smarty->assign('descricao', "'" . $this->getDescricao() . "'");
        $this->smarty->assign('aliqIpi', $this->getAliqIpi('F'));        
        $this->smarty->assign('aliqPisMonofasica', $this->getAliqPisMonofasica('F'));        
        $this->smarty->assign('aliqCofinsMonofasica', $this->getAliqCofinsMonofasica('F'));        
        $this->smarty->assign('aliqTTNacFederal', $this->getAliqTTNacFederal('F'));        
        $this->smarty->assign('aliqTTImpFederal', $this->getAliqTTImpFederal('F'));        
        $this->smarty->assign('aliqTTEstadual', $this->getAliqTTEstadual('F'));        
        $this->smarty->assign('aliqTTMunicipal', $this->getAliqTTMunicipal('F'));   
        $this->smarty->assign('vigenciaInicio', "'" . $this->getVigenciaInicio('F') . "'");
        $this->smarty->assign('vigenciaFim', "'" . $this->getVigenciaFim('F') . "'");                                                     

        $this->smarty->display('ncm_cadastro.tpl');
    }

//fim desenhaCadastroNcm
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraNcm($mensagem, $tipoMsg=NULL) {

        $lanc = $this->select_ncm_geral();

        $this->smarty->assign('pathImagem', '');
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('ncm_mostra.tpl');
    }

//fim mostraNcm
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$ncm = new p_ncm($_POST['submenu'], $_POST['letra']);

if (isset($_POST['id'])) { $ncm->setId($_POST['id']); } else {$ncm->setId('');};
if (isset($_POST['ncm'])) { $ncm->setNcm($_POST['ncm']); } else {$ncm->setNcm('');};
if (isset($_POST['descricao'])) { $ncm->setDescricao($_POST['descricao']); } else {$ncm->setDescricao('');};
if (isset($_POST['aliqIpi'])) { $ncm->setAliqIpi($_POST['aliqIpi']); } else {$ncm->setAliqIpi('0,00');};
if (isset($_POST['aliqPisMonofasica'])) { $ncm->setAliqPisMonofasica($_POST['aliqPisMonofasica']); } else {$ncm->setAliqPisMonofasica('0');};
if (isset($_POST['aliqCofinsMonofasica'])) { $ncm->setAliqCofinsMonofasica($_POST['aliqCofinsMonofasica']); } else {$ncm->setAliqCofinsMonofasica('0');};
if (isset($_POST['aliqTTNacFederal'])) { $ncm->setAliqTTNacFederal($_POST['aliqTTNacFederal']); } else {$ncm->setAliqTTNacFederal('0');};
if (isset($_POST['aliqTTImpFederal'])) { $ncm->setAliqTTImpFederal($_POST['aliqTTImpFederal']); } else {$ncm->setAliqTTImpFederal('0');};
if (isset($_POST['aliqTTEstadual'])) { $ncm->setAliqTTEstadual($_POST['aliqTTEstadual']); } else {$ncm->setAliqTTEstadual('0');};
if (isset($_POST['aliqTTMunicipal'])) { $ncm->setAliqTTMunicipal($_POST['aliqTTMunicipal']); } else {$ncm->setAliqTTMunicipal('0');};
//if (isset($_POST['vigenciaInicio'])) { $ncm->setVigenciaInicio($_POST['vigenciaInicio']); } else {$ncm->setVigenciaInicio(date("Y-m-d"));};
if (isset($_POST['vigenciaFim'])) { $ncm->setVigenciaFim($_POST['vigenciaFim']); } else {$ncm->setVigenciaFim(date("Y-m-d"));};

$ncm->controle();
?>
