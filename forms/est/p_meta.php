<?php

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_meta.php");

//Class p_meta
class p_meta extends c_meta
{

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;
    public $m_meta = NULL;
    private $parmPost = NULL;
    private $parmGet = NULL;


    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */


    function __construct()
    {
        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // inicializa variaveis de controle
        $this->m_submenu = (isset($this->parmGet['submenu']) ? $this->parmGet['submenu'] : (isset($this->parmPost['submenu']) ? $this->parmPost['submenu'] : ''));
        $this->m_letra = (isset($this->parmGet['letra']) ? $this->parmGet['letra'] : (isset($this->parmPost['letra']) ? $this->parmPost['letra'] : ''));
        $this->m_par = explode("|", $this->m_letra);
        $this->m_userid = $this->m_userid;
        $this->setVendedor(isset($this->parmPost['vendedor']) ? $this->parmPost['vendedor'] : '');
        $this->setMes(isset($this->parmPost['mes']) ? $this->parmPost['mes'] : '');
        $this->setAno(isset($this->parmPost['ano']) ? $this->parmPost['ano'] : '');
        $this->setMeta(isset($this->parmPost['meta']) ? $this->parmPost['meta'] : '');
        $this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : '');

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('titulo', "Meta");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4]");
        $this->smarty->assign('disableSort', "[ 4 ]");
        $this->smarty->assign('numLine', "25");
    }


    function controle()
    {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstMeta', 'I')) {
                    $this->desenhaMeta();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstMeta', 'A')) {
                    $meta = $this->select_meta();
                    $this->setVendedor($meta[0]['VENDEDOR']);
                    $this->setAno($meta[0]['ANO']);
                    $this->setMes($meta[0]['MES']);
                    $this->setMeta($meta[0]['META']);
                    $this->desenhaMeta();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstMeta', 'I')) {
                    if ($this->existeMeta()) {
                        $this->m_submenu = "cadastrar";
                        $this->desenhaMeta("Já existe meta.", 'alert');
                    } else {
                        $this->incluiMeta();
                        $this->mostraMeta('Registro inserido.','sucess');
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstMeta', 'A')) {
                    $this->setId($this->parmPost['id']);
                    $this->alteraMeta();
                    $this->mostraMeta('Registro salvo.', 'sucess');
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstMeta', 'E')) {
                    $this->excluiMeta();
                    $this->mostraMeta('Registro excluido.', 'sucess');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstMeta', 'C')) {
                    $this->mostraMeta('');
                }
        }
    }

    function comboSql($sql, $par, &$id, &$ids, &$names)
    {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $result =  $result ?? [];
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


    function desenhaMeta($mensagem = NULL, $tipoMsg = NULL)
    {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());

        // COMBOBOX VENDEDOR
        $vendedor = $this->getVendedor();
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO WHERE TIPO = 'V'";
        $this->comboSql($sql, $vendedor, $vendedor, $vendedor_ids, $vendedor_names);
        $this->smarty->assign('vendedor_id', $vendedor);
        $this->smarty->assign('vendedor_ids',   $vendedor_ids);
        $this->smarty->assign('vendedor_names', $vendedor_names);

        $this->smarty->assign('vendedor', $this->getVendedor());

        $ano = $this->getAno();
        if ($ano == "")
            $this->smarty->assign('ano', date("Y"));
        else
            $this->smarty->assign('ano', $this->getAno());

        //combobox Mes 
        $mes_ids[0] = '';
        $mes_names[0] = '';
        $mes_ids[1] = 1;
        $mes_names[1] = 'Janeiro';
        $mes_ids[2] = 2;
        $mes_names[2] = 'Fevereiro';
        $mes_ids[3] = 3;
        $mes_names[3] = 'Março';
        $mes_ids[4] = 4;
        $mes_names[4] = 'Abril';
        $mes_ids[5] = 5;
        $mes_names[5] = 'Maio';
        $mes_ids[6] = 6;
        $mes_names[6] = 'Junho';
        $mes_ids[7] = 7;
        $mes_names[7] = 'Julho';
        $mes_ids[8] = 8;
        $mes_names[8] = 'Agosto';
        $mes_ids[9] = 9;
        $mes_names[9] = 'Setembro';
        $mes_ids[10] = 10;
        $mes_names[10] = 'Outubro';
        $mes_ids[11] = 11;
        $mes_names[11] = 'Novembro';
        $mes_ids[12] = 12;
        $mes_names[12] = 'Dezembro';

        $this->smarty->assign('mes_ids', $mes_ids);
        $this->smarty->assign('mes_names', $mes_names);

        $mes = $this->getMes();

        if ($mes == "")
            $mes = date("m");
        $this->smarty->assign('mes_id', $mes);

        //$this->smarty->assign('mes', $this->getMes());
        $this->smarty->assign('meta', $this->getMeta());

        $this->smarty->display('meta_cadastro.tpl');
    }


    function mostraMeta($mensagem)
    {

        $lanc = $this->select_meta_geral();

        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('meta_mostra.tpl');
    }

    //fim mostragrupos
    //-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$meta = new p_meta();
$meta->controle();
