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
    protected $m_submenu = NULL;
    protected $m_opcao   = NULL;
    protected $m_data_consulta = NULL;
    
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
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
        $this->id             = $parmPost['id'] ?? '';
        $this->m_submenu      = $parmPost['submenu'] ?? '';
        $this->m_opcao        = $parmPost['opcao'] ?? '';
        $this->centro_custo   = $parmPost['centro_custo'] ?? '';
        $this->situacao_nfs   = $parmPost['situacao_nfs'] ?? '';
        $this->cliente_id     = $parmPost['cliente_id'] ?? '';
        $this->data_consulta  = $parmPost['data_consulta'] ?? '';
        $this->origem         = $parmPost['origem'] ?? '';
        $this->cliente_nome   = $parmPost['cliente_nome'] ?? '';
        $this->motivo         = $parmPost['motivo'] ?? '';

        if($this->data_consulta != ''){
            $data_explode = explode(' - ', $this->data_consulta);
            $this->data_ini = $data_explode[0];
            $this->data_fim = $data_explode[1];
        }

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Nota Fiscal de Serviço");
        $this->smarty->assign('colVis', "[0,1,2,3,4,5,6]");
        $this->smarty->assign('disableSort', "[ 1, 2, 3, 4, 5, 6, 7, 8]"); 
        $this->smarty->assign('numLine', "10"); 
    }


    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                $this->cadastrarNotaFiscalServico('');
                break;  
            case 'pesquisa':
                $this->mostraNotaFiscalServico('pesquisa');
                break;
            case 'deletInvoice':
                $this->deletInvoice($this->id);
                break;
            case 'cancelInvoice':
                $this->cancelInvoice();
                break;
            case 'log':
                $this->mostraLogNFS();
                break;
            case 'logSearchXMLLog':
                $this->selectXMLLog($this->id);
                break;
            default:
                $this->mostraNotaFiscalServico('');
        }
    }


    function mostraNotaFiscalServico($fluxo = null){

        if( $fluxo == 'pesquisa' ){
            $nfs = $this->selectNotaFiscalServico();
            $this->smarty->assign('notasFiscais', $nfs);
        }

        if( $this->data_ini == "" ){
            $this->smarty->assign('data_ini', date("01/m/Y"));
        } else {
            $this->smarty->assign('data_ini', $this->data_ini);
        }
        
        if( $this->data_fim == "" ){
            $this->smarty->assign('data_fim', date("d/m/Y", mktime(0, 0, 0, date("m")+1, 0, date("Y"))));
        } else {
            $this->smarty->assign('data_fim', $this->data_fim);
        }

        // COMBOBOX SITUACAO NOTA FISCAL - Situações fixas
        $situacao_ids = ['', 0, 1, 2, 3, 4];
        $situacao_names = ['Selecione', 'Aberta', 'Emitida', 'Cancelada', 'Solicitação de Cancelamento', 'Todas'];
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_id', $this->situacao_nfs);

        // COMBOBOX CENTRO DE CUSTO
        $centro_custos = $this->selectCentroCusto();
        $this->smarty->assign('centro_custo_ids', $centro_custos['ID']);
        $this->smarty->assign('centro_custo_names', $centro_custos['DESCRICAO']);
        $this->smarty->assign('centro_custo_id', $this->centro_custo);

        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('submenu', $this->m_submenu);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('data_atual', date("d/m/Y H:i:s"));
        
        $this->smarty->display('nota_fiscal_servico_mostra.tpl');
    }

    function cadastrarNotaFiscalServico($mensagem){
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('submenu', $this->m_submenu);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
        
        $this->smarty->display('nota_fiscal_servico_cadastro.tpl');
    }

    function mostraLogNFS(){

        // Passa os filtros para o template
        $this->smarty->assign('origem', $this->origem);
        $this->smarty->assign('cliente_id', $this->cliente_id);
        $this->smarty->assign('cliente_nome', $this->cliente_nome);
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('submenu', $this->m_submenu);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('data_atual', date("d/m/Y H:i:s"));

        // $this->smarty->assign('titulo', "LOG NOTA FISCAL DE SERVIÇO");
        // $this->smarty->assign('colVis', " [ 2, 3, 4, 5 ] "); 
        // $this->smarty->assign('disableSort', ""); 
        // $this->smarty->assign('numLine', "10"); 
    
        // Define datas padrão (mês atual)
        if( $this->data_ini == "" ){
            $this->smarty->assign('data_ini', date("01/m/Y"));
        } else {
            $this->smarty->assign('data_ini', $this->data_ini);
        }
        
        if( $this->data_fim == "" ){
            $this->smarty->assign('data_fim', date("d/m/Y", mktime(0, 0, 0, date("m")+1, 0, date("Y"))));
        } else {
            $this->smarty->assign('data_fim', $this->data_fim);
        }

        // Se for pesquisa, busca os logs
        if( $this->m_opcao == 'pesquisa' ){
            // Prepara os filtros
            $filtros = array(
                'data_ini' => $this->data_ini,
                'data_fim' => $this->data_fim,
                'origem' => $this->origem,
                'cliente_id' => $this->cliente_id
            );

            $logs = $this->selectLogNFS($filtros);
            $this->smarty->assign('logs', $logs);
        }
        
        $this->smarty->display('mostra_log_nfs.tpl');
    }

}

// Rotina principal - cria classe
$notaFiscalServico = new p_nota_fiscal_servico();

$notaFiscalServico->controle();
?>
