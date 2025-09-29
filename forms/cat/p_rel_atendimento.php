<?php

/**
 * @package   admv4.5
 * @name      p_rel_atendimento
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva <joshua.silva@admsistemas.com.br>
 * @date      14/05/2025
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/cat/c_atendimento_relatorio.php");

class p_rel_atendimento extends c_atendimento_relatorio
{

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    function __construct()
    {
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";


        $this->m_submenu = isset($this->parmPost['submenu']) ? $this->parmPost['submenu'] : ($this->parmGet['submenu'] ? $this->parmGet['submenu'] : null);
        $this->m_tipo_relatorio = isset($this->parmPost['tipoRelatorio']) ? $this->parmPost['tipoRelatorio'] : ($this->parmGet['tipoRelatorio'] ? $this->parmGet['tipoRelatorio'] : null);

        // filtos
        $this->cliente_id = isset($this->parmPost['cliente_id']) ? $this->parmPost['cliente_id'] : ($this->parmGet['cliente_id'] ? $this->parmGet['cliente_id'] : null);
        $this->equipamento = isset($this->parmPost['equipamento']) ? $this->parmPost['equipamento'] : ($this->parmGet['equipamento'] ? $this->parmGet['equipamento'] : null);
        $this->id_status = isset($this->parmPost['id_status']) ? $this->parmPost['id_status'] : ($this->parmGet['id_status'] ? $this->parmGet['id_status'] : null);
        $this->id_servico = isset($this->parmPost['id_servico']) ? $this->parmPost['id_servico'] : ($this->parmGet['id_servico'] ? $this->parmGet['id_servico'] : null);
        $this->centro_custo = isset($this->parmPost['centro_custo']) ? $this->parmPost['centro_custo'] : ($this->parmGet['centro_custo'] ? $this->parmGet['centro_custo'] : null);
        $this->num_pedido = isset($this->parmPost['num_pedido']) ? $this->parmPost['num_pedido'] : ($this->parmGet['num_pedido'] ? $this->parmGet['num_pedido'] : null);
        $this->num_os = isset($this->parmPost['num_os']) ? $this->parmPost['num_os'] : ($this->parmGet['num_os'] ? $this->parmGet['num_os'] : null);
        $this->usuario = isset($this->parmPost['usuario']) ? $this->parmPost['usuario'] : ($this->parmGet['usuario'] ? $this->parmGet['usuario'] : null);
        $this->id_pedido = isset($this->parmPost['id_pedido']) ? $this->parmPost['id_pedido'] : ($this->parmGet['id_pedido'] ? $this->parmGet['id_pedido'] : null);
        $this->dataIni = isset($this->parmPost['dataIni']) ? $this->parmPost['dataIni'] : ($this->parmGet['dataIni'] ? $this->parmGet['dataIni'] : null);
        $this->dataFim = isset($this->parmPost['dataFim']) ? $this->parmPost['dataFim'] : ($this->parmGet['dataFim'] ? $this->parmGet['dataFim'] : null);
        
        $this->data_consulta = isset($this->parmPost['data_consulta']) ? $this->parmPost['data_consulta'] : ($this->parmGet['data_consulta'] ? $this->parmGet['data_consulta'] : null);
        $dates = explode(' - ', $this->data_consulta);
        $this->data_ini = $dates[0];
        $this->data_fim = $dates[1];

        //globais
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        $this->smarty->assign('titulo', "Relatório de Aniversário");
        $this->smarty->assign('colVis', "[ 0, 1 ]");
        $this->smarty->assign('disableSort', "[ 2 ]");
        $this->smarty->assign('numLine', "25");
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'relatorio_medicao':               
                $this->mostraRelatorioAtendimento($this->id_pedido);
                break;

            case 'relatorio_servico':
                $this->m_object = [
                    "usuario" => $this->usuario,
                    "id_servico" => $this->id_servico,
                    "equipamento" => $this->equipamento,
                    "id_status" => $this->id_status,
                    "centro_custo" => $this->centro_custo,
                    "data_ini" => $this->data_ini,
                    "data_fim" => $this->data_fim,
                    "num_pedido" => $this->num_pedido,
                    "num_os" => $this->num_os,
                    "cliente_id" => $this->cliente_id,
                ];

                $this->mostraRelatorioAtendimento($this->m_object);
                break;
            case 'relatorio_usuario':
                $this->m_object = [
                    "usuario" => $this->usuario,
                    "centro_custo" => $this->centro_custo,
                    "data_fim" => $this->data_fim,
                    "data_ini" => $this->data_ini,
                    "num_pedido" => $this->num_pedido,
                    "num_os" => $this->num_os,
                ];

                $this->mostraRelatorioAtendimento($this->m_object);
                break;
            case 'relatorio_equipamento':
                $this->m_object = [
                    "equipamento" => $this->equipamento,
                    "centro_custo" => $this->centro_custo,
                    "data_fim" => $this->data_fim,
                    "data_ini" => $this->data_ini,
                    "num_pedido" => $this->num_pedido,
                    "num_os" => $this->num_os,
                    "cliente_id" => $this->cliente_id,
                ];

                $this->mostraRelatorioAtendimento($this->m_object);
                break;
            default:
                $this->mostraRelatorio();
        }
    }
    function mostraRelatorio()
    {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign("ADMhttpBib", ADMhttpBib);

        $this->smarty->assign('data_ini', date("01/m/Y"));
        $this->smarty->assign('data_fim', date("d/m/Y"));

        $this->comboAtendimento();

        $this->smarty->display('rel_atendimento_mostra.tpl');
    }

    function mostraRelatorioAtendimento($params)
    {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign("ADMhttpBib", ADMhttpBib);


        $this->smarty->assign('data_ini', $this->data_ini);
        $this->smarty->assign('data_fim', $this->data_fim);

        if ($this->m_tipo_relatorio != 'relatorio_medicao') {
            $lanc = $this->selectRelatorioAtendimento($params);
        }else {
            $lanc = $this->selectRelatorioMedicao($params);

        }

        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        $this->smarty->assign('lanc', $lanc);

        switch ($this->m_tipo_relatorio) {
            case 'relatorio_medicao':

                $this->smarty->display('relatorio_medicao.tpl');
                break;
            case 'relatorio_servico':
                $this->smarty->display('relatorio_servico.tpl');
                break;
            case 'relatorio_equipamento':
                $this->smarty->display('relatorio_equipamento.tpl');
                break;
            case 'relatorio_usuario':
                $this->smarty->display('relatorio_usuario.tpl');
                break;
        }
    }
}

$rel_atendimento = new p_rel_atendimento();
$rel_atendimento->controle();
