<?php
/**
 * @package   astec
 * @name      p_nota_fiscal_servico
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silvao<marcio.sergio@admservice.com.br>
 * @date      27/04/2018
 */

$dir = dirname(__FILE__);
require_once($dir."/../../class/est/c_nota_fiscal_servico.php");
require_once($dir."/../../../smarty/libs/Smarty.class.php");

class p_nota_fiscal_servico extends c_nota_fiscal_servico
{
    private $m_submenu = NULL;
    private $m_opcao = NULL;
    public $m_contrato = NULL;
    
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  
        
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
        $this->m_submenu = $parmGet['submenu'] ?? $parmPost['submenu'] ?? '';
        $this->m_opcao = $parmGet['opcao'] ?? $parmPost['opcao'] ?? '';
        $this->m_contrato = $parmGet['contrato'] ?? $parmPost['contrato'] ?? '';


        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Nota Fiscal de Serviço");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
        $this->smarty->assign('disableSort', "[ 5 ]"); 
        $this->smarty->assign('numLine', "25"); 

    
        if($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[0]);
    
        if($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        }else {
            $this->smarty->assign('dataFim', $this->m_par[1]);
        }
    }


    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                $this->cadastrarNotaFiscalServico('');
                break;  
            case 'mostra':
                $this->mostraNotaFiscalServico('');
                break;
            case 'enviarNotaFiscalServico':

                $this->mostraNotaFiscalServico('');
                break;
            case 'enviarNotaFiscalServicoContrato': 

                $this->typeFramework();

                $this->mostraNotaFiscalServico('');
                break;
            default:
                $this->mostraNotaFiscalServico('');
        }
    }


    function mostraNotaFiscalServico($mensagem){
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        
        $this->smarty->display('nota_fiscal_servico_mostra.tpl');
    }

    function cadastrarNotaFiscalServico($mensagem){
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        
        $this->smarty->display('nota_fiscal_servico_cadastro.tpl');
    }



}

// Rotina principal - cria classe
$notaFiscalServico = new p_nota_fiscal_servico();

$notaFiscalServico->controle();
?>
