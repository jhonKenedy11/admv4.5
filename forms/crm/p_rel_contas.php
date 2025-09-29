<?php

/**
 * @package   admv4.5
 * @name      p_rel_contas
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva <joshua.silva@admsistemas.com.br>
 * @date      08/05/2025
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/crm/c_contas_relatorio.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");

class p_rel_contas extends c_contas_relatorio
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

        $this->smarty->template_dir = ADMraizFonte . "/template/crm";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = isset($this->parmPost['submenu']) ? $this->parmPost['submenu'] : ($this->parmGet['submenu'] ? $this->parmGet['submenu'] : null);
        $this->pesNome = isset($this->parmPost['pesNome']) ? $this->parmPost['pesNome'] : ($this->parmGet['pesNome'] ? $this->parmGet['pesNome'] : null);
        $this->pesCnpjCpf = isset($this->parmPost['pesCnpjCpf']) ? $this->parmPost['pesCnpjCpf'] : ($this->parmGet['pesCnpjCpf'] ? $this->parmGet['pesCnpjCpf'] : null);
        $this->pesCidade = isset($this->parmPost['pesCidade']) ? $this->parmPost['pesCidade'] : ($this->parmGet['pesCidade'] ? $this->parmGet['pesCidade'] : null);
        $this->idEstado = isset($this->parmPost['idEstado']) ? $this->parmPost['idEstado'] : ($this->parmGet['idEstado'] ? $this->parmGet['idEstado'] : null);
        $this->idFilial = isset($this->parmPost['idFilial']) ? $this->parmPost['idFilial'] : ($this->parmGet['idFilial'] ? $this->parmGet['idFilial'] : null);
        $this->idPessoa = isset($this->parmPost['idPessoa']) ? $this->parmPost['idPessoa'] : ($this->parmGet['idPessoa'] ? $this->parmGet['idPessoa'] : null);
        $this->idClasse = isset($this->parmPost['idClasse']) ? $this->parmPost['idClasse'] : ($this->parmGet['idClasse'] ? $this->parmGet['idClasse'] : null);
        $this->idAtividade = isset($this->parmPost['idAtividade']) ? $this->parmPost['idAtividade'] : ($this->parmGet['idAtividade'] ? $this->parmGet['idAtividade'] : null);
        $this->idVendedor = isset($this->parmPost['idVendedor']) ? $this->parmPost['idVendedor'] : ($this->parmGet['idVendedor'] ? $this->parmGet['idVendedor'] : null);
        $this->m_tipo_relatorio = isset($this->parmPost['tipoRelatorio']) ? $this->parmPost['tipoRelatorio'] : ($this->parmGet['tipoRelatorio'] ? $this->parmGet['tipoRelatorio'] : null);
        $this->data_consulta = isset($this->parmPost['data_consulta']) ? $this->parmPost['data_consulta'] : ($this->parmGet['data_consulta'] ? $this->parmGet['data_consulta'] : null);
        $dates = explode(' - ', $this->data_consulta);
        $this->dataIni = $dates[0];
        $this->dataFim = $dates[1];


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
            case 'relatorio_aniversario':
                $this->mostraRelatorioAniversario();
                break;
            case 'relatorio_contas':
                $this->mostraRelatorioAniversario();
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

        $this->smarty->assign('dataIni', date("01/m/Y"));
        $this->smarty->assign('dataFim', date("d/m/Y"));

        $this->comboAniversario();

        $this->smarty->display('rel_contas_mostra.tpl');
    }

    function mostraRelatorioAniversario()
    {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign("ADMhttpBib", ADMhttpBib);


        $this->smarty->assign('dataIni', $this->dataIni);
        $this->smarty->assign('dataFim', $this->dataFim);

        
        $this->setPesNome($this->pesNome);
        $this->setPesCnpjCpf($this->pesCnpjCpf);
        $this->setDataConsulta($this->data_consulta);
        $this->setPesCidade($this->pesCidade);
        $this->setIdEstado($this->idEstado);
        $this->setIdFilial($this->idFilial);
        $this->setIdPessoa($this->idPessoa);
        $this->setIdClasse($this->idClasse);
        $this->setIdAtividade($this->idAtividade);
        $this->setIdVendedor($this->idVendedor);

        if($this->m_submenu == 'relatorio_aniversario'){
            $lanc = $this->select_conta_aniversario() ?? [];
        }else { 
            $this->setDataConsulta('');
            $lanc = $this->selectRelatorioContas() ?? [];
        }


        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $this->smarty->assign('lanc', $lanc);

        switch ($this->m_tipo_relatorio) {
            case 'relatorio_aniversario':
                $this->smarty->display('relatorio_aniversario.tpl');                
                break;
            case 'relatorio_contas':
                $this->smarty->display('relatorio_contas.tpl');
                break;
            }

       
    }


}

$rel_contas = new p_rel_contas();
$rel_contas->controle();
