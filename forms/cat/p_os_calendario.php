<?php

/**
 * @package   admv4.5
 * @name      p_os_calendario
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
include_once($dir . "/../../class/cat/c_os_calendario.php");
include_once($dir . "/../../class/crm/c_contas_acompanhamento.php");

class p_calendar extends c_os_calendario
{
    private $smarty    = NULL;
    private $submenu   = NULL;
    private $parm_post = NULL;
    private $parm_get  = NULL;
    private $id        = NULL;
    private $data_ini  = NULL;
    private $data_fim  = NULL;
    private $vendedor  = NULL;
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
        $this->vendedor = (isset($this->parm_post['vendedor']) ? $this->parm_post['vendedor'] : null);
        $this->json = (isset($this->parm_post['json']) ? $this->parm_post['json'] : null);
    }

    public function controle()
    {
        switch ($this->submenu) {
            case 'searchOrderService': //update origem Ajax

                $mountParam = [
                    "data_ini" => $this->data_ini,
                    "data_fim" => $this->data_fim,
                    "vendedor" => $this->vendedor
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


        // COMBOBOX VENDEDOR
        $consulta = new c_banco();
        //$sql = "SELECT USUARIO, NOME FROM AMB_USUARIO WHERE TIPO='V'";
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
        $sql .= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' ) and (SITUACAO != 'I') and (TIPO != 'Z')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        for ($i = 0; $i < count($result); $i++) {
            $equipe_ids[$i + 1] = $result[$i]['USUARIO'];
            $equipe_names[$i] = $result[$i]['NOME'];
        }

        $this->smarty->assign('equipe_ids', $equipe_ids);
        $this->smarty->assign('equipe_names', $equipe_names);
        $this->smarty->assign('user_id', $this->m_userid);

        $this->smarty->display('os_calendario.tpl');
    }

}

$calendar = new p_calendar();
$calendar->controle();
