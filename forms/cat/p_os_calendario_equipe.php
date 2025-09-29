<?php

/**
 * @package   admv4.5
 * @name      p_os_calendario_equipe
 * @version   4.3.01
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy dos Santos<jhon.kened11@gmail.com>
 * @date      17/04/2025
 */

if (!defined('ADMpath')):
    exit;
endif;

$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/cat/c_os_calendario_equipe.php");
include_once($dir . "/../../class/crm/c_contas_acompanhamento.php");

class p_calendar_equipe extends c_os_calendario_equipe
{
    private $smarty    = NULL;
    private $submenu   = NULL;
    private $parm_post = NULL;
    private $parm_get  = NULL;
    private $id        = NULL;
    private $data_ini  = NULL;
    private $data_fim  = NULL;
    private $equipe    = NULL;
    private $json      = NULL;

    function __construct()
    {
        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parm_post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parm_get  = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;
        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        $this->submenu  = (isset($this->parm_get['submenu']) ? $this->parm_get['submenu'] : (isset($this->parm_post['submenu']) ? $this->parm_post['submenu'] : ''));
        $this->id       = (isset($this->parm_get['id']) ? $this->parm_get['id'] : (isset($this->parm_post['id']) ? $this->parm_post['id'] : ''));
        $this->data_ini = (isset($this->parm_post['data_ini']) ? $this->parm_post['data_ini'] : null);
        $this->data_fim = (isset($this->parm_post['data_fim']) ? $this->parm_post['data_fim'] : null);
        $this->equipe   = (isset($this->parm_post['equipe']) ? $this->parm_post['equipe'] : null);
        $this->usuario_equipe = (isset($this->parm_post['usuario_equipe']) ? $this->parm_post['usuario_equipe'] : null);
        $this->json     = (isset($this->parm_post['json']) ? $this->parm_post['json'] : null);
    }

    public function controle()
    {
         switch ($this->submenu) {
            case 'searchOrderService': //update origem Ajax
                $mountParam = [
                    "data_ini" => $this->data_ini,
                    "data_fim" => $this->data_fim,
                    "equipe" => $this->equipe,
                    "usuario_equipe" => $this->usuario_equipe
                ];

                $this->selectOrdemServico($mountParam);
                break;

            case 'updateOrderService':
                
                $this->updateOrigemCalendario($this->json);
                break;
            default:
                $this->desenhaCalendar();
        }
    }

    public function desenhaCalendar()
    {
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathRaiz', ADMhttpBib);
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('pathJsCalendar',  ADMhttpBib . '/bib/calendar');

        // COMBOBOX EQUIPE
        $consulta = new c_banco();
        $sql = "SELECT ID, DESCRICAO FROM AMB_EQUIPE;";   
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        
        $equipe_ids = [];
        $equipe_names = [];
        for ($i = 0; $i < count($result); $i++) {
            $equipe_ids[$i + 1] = $result[$i]['ID'];
            $equipe_names[$i] = $result[$i]['DESCRICAO'];
        }

        // COMBOBOX USUARIOS
        $consulta = new c_banco();
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO WHERE SITUACAO = 'A' AND USUARIO < 999 ORDER BY NOME;";   
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        
        $usuario_equipe_ids = [0];
        $usuario_equipe_names = ['selecione'];
        for ($i = 0; $i < count($result); $i++) {
            $usuario_equipe_ids[$i] = $result[$i]['USUARIO'];
            $usuario_equipe_names[$i] = $result[$i]['NOME'];
        }

        $this->smarty->assign('equipe_ids', $equipe_ids);
        $this->smarty->assign('equipe_names', $equipe_names);
        $this->smarty->assign('usuario_equipe_ids', $usuario_equipe_ids);
        $this->smarty->assign('usuario_equipe_names', $usuario_equipe_names);
        $this->smarty->assign('user_id', $this->m_userid);

        $this->smarty->display('os_calendario_equipe.tpl');
    }
}

$calendar = new p_calendar_equipe();
$calendar->controle(); 