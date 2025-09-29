<?php

/**
 * @package   astec
 * @name      p_grupo
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_grupo.php");

//Class P_situacao
Class p_grupo extends c_grupo {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {
// Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

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

        $this->smarty->assign('titulo', "Grupo Produtos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "25"); 
        

        $this->m_submenu= (isset($parmPost['submenu']) ? $parmPost['submenu'] : '');
        $this->m_letra= (isset($parmPost['letra']) ? $parmPost['letra'] : '');
        $this->m_opcao= (isset($parmPost['opcao']) ? $parmPost['opcao'] : '');
        $this->setDesc(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        $this->setTipo(isset($parmPost['tipo']) ? $parmPost['tipo'] : '');
        $this->setNivel(isset($parmPost['nivel']) ? $parmPost['nivel'] : '');
        $this->setComissaoVendas(isset($parmPost['comissao']) ? $parmPost['comissao'] : '' );
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setGrupoBase(isset($parmPost['grpBase']) ? $parmPost['grpBase'] : '');
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
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->desenhaCadastroGrupo();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $grupo = $this->select_grupo();
                    $this->setDesc($grupo[0]['DESCRICAO']);
                    $this->setTipo($grupo[0]['TIPO']);
                    $this->setNivel($grupo[0]['NIVEL']);
                    $this->setComissaoVendas($grupo[0]['COMISSAOVENDAS']);
                    $this->setGrupoBase($grupo[0]['GRUPO']);
                    $this->desenhaCadastroGrupo();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    if ($this->existeDocumento()) {
                        $this->m_submenu = "cadastrar";
                        $this->desenhaCadastroGrupo("Já existe registro com este código, por favor altere o codigo do grupo.",'alerta');
                    } else {
                        $id = $this->incluiGrupo();
                        $id = 'texto';
                        if($id > 0){
                            $this->mostraGrupo('Registro inserido.','Sucesso');
                        }
                        else{
                            $this->mostraGrupo('Erro ao inserir registro.','Alerta');
                        }
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $id = $this->alteraGrupo();
                    if($id > 0){
                        $this->mostraGrupo('Registro salvo.','Sucesso');
                    }
                    else{
                        $this->mostraGrupo('Erro ao alterar registro.','Alerta');
                    }
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $this->excluiGrupo();
                    $this->mostraGrupo('Registro excluido.','Sucesso');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->mostraGrupo('');
                }
        }
    }


    function desenhaCadastroGrupo($mensagem = NULL,$tipoMsg=NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('grupoBase', $this->getGrupoBase());
        $this->smarty->assign('comissao', $this->getComissaoVendas('F'));
        $this->smarty->assign('id', $this->getId());  
        $this->smarty->assign('descricao', "'" . $this->getDesc() . "'");
        $this->smarty->assign('nivel', $this->getNivel());

        // tipo GRUPO
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TipoGrupo')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $tipoGrupo_ids[$i] = $result[$i]['ID'];
            $tipoGrupo_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipoGrupo_ids', $tipoGrupo_ids);
        $this->smarty->assign('tipoGrupo_names', $tipoGrupo_names);

        $this->smarty->assign('tipo', $this->getTipo());



        //$this->smarty->display('grupo_cadastro.tpl');
        $this->smarty->display('grupo_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraGrupo($mensagem, $tipoMsg='') {

        $lanc = $this->select_grupo_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('grupo_mostra.tpl');
    }

//fim mostragrupos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$grupo = new p_grupo();

// if (isset($_POST['id'])) { $grupo->setId($_POST['id']); } else {$grupo->setId('');};
// if (isset($_POST['grupoBase'])) { $grupo->setGrupoBase($_POST['grupoBase']); } else {$grupo->setGrupoBase('');};
// if (isset($_POST['descricao'])) { $grupo->setDesc($_POST['descricao']); } else {$grupo->setDesc('');};
// if (isset($_POST['tipo'])) { $grupo->setTipo($_POST['tipo']); } else {$grupo->setTipo('');};
// if (isset($_POST['nivel'])) { $grupo->setNivel($_POST['nivel']); } else {$grupo->setNivel('');};

$grupo->controle();
?>
