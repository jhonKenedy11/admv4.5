<?php

/**
 * @package   astec
 * @name      p_parametro
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      18/05/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_parametro.php");

//Class P_situacao
Class p_parametro extends c_parametros {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu, $letra) {
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
        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // include do javascript
        // include ADMjs . "/est/s_parametro.js";
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'altera':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $this->alteraGrupo();
                    $this->desenhaCadastroParametros('Registro salvo.', 'sucesso');
                    
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->setParametro();
                    $this->desenhaCadastroParametros('');
                }
        }
    }


    function desenhaCadastroParametros($mensagem = NULL,$tipoMsg=NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('cfop', $this->getCfop());
        $this->smarty->assign('natOperacao', $this->getNatOp());
        $this->smarty->assign('serie', $this->getSerie());

        // COMBOBOX CONDICAO PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPgto());
        
        
        // COMBOBOX GENERO
        $consulta = new c_banco();
        $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero ORDER BY descricao;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $genero_ids[$i] = $result[$i]['ID'];
            $genero_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('genero_ids', $genero_ids);
        $this->smarty->assign('genero_names', $genero_names);
        $this->smarty->assign('genero_id', $this->getGenero());
        
        // COMBOBOX CONTA
        $consulta = new c_banco();
        $sql = "SELECT conta as id, nomeinterno as descricao FROM fin_conta ORDER BY nomeinterno;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('conta_ids', $conta_ids);
        $this->smarty->assign('conta_names', $conta_names);
        $this->smarty->assign('conta_id', $this->getConta());
        
        $this->smarty->display('parametro_cadastro.tpl');
    }



//fim mostragrupos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$par = new p_parametro($_POST['submenu'], $_POST['letra']);

if (isset($_POST['id'])) { $par->setId($_POST['id']); } else {$par->setId('');};
if (isset($_POST['cfop'])) { $par->setCfop($_POST['cfop']); } else {$par->setCfop('');};
if (isset($_POST['natOperacao'])) { $par->setNatOp($_POST['natOperacao']); } else {$par->setNatOp('');};
if (isset($_POST['condPgto'])) { $par->setCondPgto($_POST['CondPgto']); } else {$par->setCondPgto('');};
if (isset($_POST['genero'])) { $par->setGenero($_POST['genero']); } else {$par->setGenero('');};
if (isset($_POST['conta'])) { $par->setConta($_POST['conta']); } else {$par->setConta('');};
if (isset($_POST['serie'])) { $par->setSerie($_POST['serie']); } else {$par->setserie('');};

$par->controle();
?>
