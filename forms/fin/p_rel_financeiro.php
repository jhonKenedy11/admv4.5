<?php

/**
 * @package   admv4.5
 * @name      p_rel_financeiro
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      10/10/2025
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/fin/c_rel_financeiro.php");
include_once($dir . "/../../bib/c_tools.php");

class p_rel_financeiro extends c_rel_financeiro
{

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;
    private $m_tipo_relatorio = NULL;

    function __construct()
    {
        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_tipo_relatorio = (isset($parmGet['tipoRelatorio']) ? $parmGet['tipoRelatorio'] : (isset($parmPost['tipoRelatorio']) ? $parmPost['tipoRelatorio'] : ''));
        
        // Parâmetros do relatório - datas já tratadas no JavaScript
        $this->setReferencia(isset($parmPost['referencia']) ? $parmPost['referencia'] : (isset($parmGet['referencia']) ? $parmGet['referencia'] : ''));
        $this->setDataIni(isset($parmPost['dataIni']) ? $parmPost['dataIni'] : (isset($parmGet['dataIni']) ? $parmGet['dataIni'] : ''));
        $this->setDataFim(isset($parmPost['dataFim']) ? $parmPost['dataFim'] : (isset($parmGet['dataFim']) ? $parmGet['dataFim'] : ''));
        
        // Parâmetros específicos do financeiro usando setters diretamente
        $this->setTipoLancamento(isset($parmPost['tipolanc']) ? $parmPost['tipolanc'] : (isset($parmGet['tipolanc']) ? $parmGet['tipolanc'] : ''));
        $this->setSituacaoLancamento(isset($parmPost['sitlanc']) ? $parmPost['sitlanc'] : (isset($parmGet['sitlanc']) ? $parmGet['sitlanc'] : ''));
        $this->setTipoDocumento(isset($parmPost['tipoDocto']) ? $parmPost['tipoDocto'] : (isset($parmGet['tipoDocto']) ? $parmGet['tipoDocto'] : ''));
        $this->setIdContaBanco(isset($parmPost['conta']) ? $parmPost['conta'] : (isset($parmGet['conta']) ? $parmGet['conta'] : ''));
        $this->setIdCentroCusto(isset($parmPost['filial']) ? $parmPost['filial'] : (isset($parmGet['filial']) ? $parmGet['filial'] : ''));
        $this->setIdGenero(isset($parmPost['genero']) ? $parmPost['genero'] : (isset($parmGet['genero']) ? $parmGet['genero'] : ''));
        $this->setSituacaoDocumento(isset($parmPost['sitdocto']) ? $parmPost['sitdocto'] : (isset($parmGet['sitdocto']) ? $parmGet['sitdocto'] : ''));
        $this->setPessoa(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : (isset($parmGet['pessoa']) ? $parmGet['pessoa'] : ''));
        
        //pesquisa pessoa
        $this->setPessoaBusca(isset($parmPost['termo']) ? $parmPost['termo'] : (isset($parmGet['termo']) ? $parmGet['termo'] : ''));
        // Parâmetros específicos do relatório
        $this->setTipoRelatorio(isset($parmPost['tipoRelatorio']) ? $parmPost['tipoRelatorio'] : (isset($parmGet['tipoRelatorio']) ? $parmGet['tipoRelatorio'] : ''));
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Relatórios Financeiros");
        $this->smarty->assign('colVis', "[ 0, 1 ]");
        $this->smarty->assign('disableSort', "[ 2 ]");
        $this->smarty->assign('numLine', "25");
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'relatorio':
                $this->imprimeRelatorio();
                break;
            case 'buscar_clientes':
                $clientes = $this->buscarClientesJson();
                echo json_encode($clientes);
                break;
            case 'buscar_genero':
                $generos = $this->buscarGeneroJson();
                echo json_encode($generos);
                break;
            default:
                $this->mostraRelatorio();
                break;
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
        $this->smarty->assign('dataFim', date("t/m/Y"));

        $this->comboRelatorioFinanceiro();

        $this->smarty->display('rel_financeiro_mostra.tpl');
    }


    function imprimeRelatorio()
    {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign("ADMhttpBib", ADMhttpBib);
        
        $this->smarty->assign('dataIni', $this->getDataIni());
        $this->smarty->assign('dataFim', $this->getDataFim());
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        

        switch ($this->m_tipo_relatorio) {
            // Relatórios do menu do lançamento financeiro
            case 'lancamentos_data':
                $resultado = $this->selectLancamentosData();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->assign('tipoReferencia', $this->getReferencia());
                $this->smarty->display('rel_financeiro_lancamentos_data.tpl');
                break;
            case 'fluxo_caixa':
                $resultado = $this->selectLancamentosData();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->assign('tipoReferencia', $this->getReferencia());
                $this->smarty->display('rel_financeiro_fluxo_caixa.tpl');
                break;
            case 'consolidacao':
                $resultado = $this->selectLancamentosData();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->assign('tipoReferencia', $this->getReferencia());
                $this->smarty->display('rel_financeiro_consolidacao.tpl');
                break;
            case 'resumo_genero':
                $resultado = $this->selectLancamentosData();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_financeiro_resumo_genero.tpl');
                break;
            case 'centro_custo_analitico':
                $resultado = $this->selectLancamentosData(true);
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_financeiro_centro_custo_analitico.tpl');
                break;
            case 'centro_custo_sintetico':
                $resultado = $this->selectLancamentosData(true);
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_financeiro_centro_custo_sintetico.tpl');
                break;
            case 'dre_financeiro':
                $resultado = $this->selectDREFinanceiro();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_financeiro_dre.tpl');
                break;
            case 'rel_financeiro_data_entrega':
                $resultado = $this->selectLancamentosDataEntrega();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_financeiro_data_entrega.tpl');
                break;
            default:
                $resultado = $this->selectLancamentosData();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_financeiro_lancamentos_data.tpl');
                break;
        }
    }

}
$financeiro = new p_rel_financeiro();

$financeiro->controle();

?>

