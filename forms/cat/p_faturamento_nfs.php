<?php
/**
 * @package   admv4.3.1
 * @name      p_fatura_nfs
 * @version   1.0.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy dos Santos <jhonkenedy@gmail.com>
 * @date      06/08/2025
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/cat/c_fatura_nfs.php");
include_once($dir."/../../bib/c_tools.php");
include_once($dir."/../../bib/c_date.php");



Class p_fatura_nfs extends c_fatura_nfs 
{

    private $m_submenu = NULL;
    private $m_term_pesquisa = NULL;
    private $m_opcao = NULL;
    private $m_data = NULL;
    
    public $smarty = NULL;

    function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));

        $this->m_term_pesquisa=(isset($parmGet['term']) ? $parmGet['term'] : (isset($parmPost['term']) ? $parmPost['term'] : ''));
        $this->m_data=(isset($parmGet['data']) ? $parmGet['data'] : (isset($parmPost['data']) ? $parmPost['data'] : ''));
        
        $this->m_id=(isset($parmGet['id']) ? $parmGet['id'] : (isset($parmPost['id']) ? $parmPost['id'] : ''));
        $this->m_tipoDocumento=(isset($parmGet['tipo_documento']) ? $parmGet['tipo_documento'] : (isset($parmPost['tipo_documento']) ? $parmPost['tipo_documento'] : ''));
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Faturamento NFS");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 
    }

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle()
    {
        switch ($this->m_submenu){
            case 'pesquisaClienteAjax':
                 $this->selectPerson($this->m_term_pesquisa);
                break;
            case 'searchDocuments':
                $this->searchDocuments($this->m_data);
                break;
            case 'buscarServicos':
                $this->buscarServicos($this->m_id, $this->m_tipoDocumento);
                break;
            default:
            //if ($this->verificaDireitoUsuario('CatEquipamento', 'C')){
                    $this->mostraOrdemServico('');
            //}
        }

    }

    /**
    * <b> Listagem de todas as registro cadastrados de tabela equipamento. </b>
    * @param String $mensagem Mensagem que ira mostrar na tela
    */

    function mostraOrdemServico($mensagem, $id=null)
    {
        // Captura filtros do formulário (compatível com atendimento_new)
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Datas vindas como "DD/MM/YYYY - DD/MM/YYYY" nos campos dataConsulta ou dataIni/dataFim
        $dataIni = isset($parmPost['dataIni']) ? $parmPost['dataIni'] : '';
        $dataFim = isset($parmPost['dataFim']) ? $parmPost['dataFim'] : '';
        if (empty($dataIni) && !empty($parmPost['dataConsulta'])) {
            $fatias = explode(' - ', $parmPost['dataConsulta']);
            $dataIni = isset($fatias[0]) ? trim($fatias[0]) : '';
            $dataFim = isset($fatias[1]) ? trim($fatias[1]) : '';
        }

        // Preenche padrão visual de datas para o filtro (mês atual), caso vazio
        $dataIniMesAtual = date('01/m/Y'); // Primeiro dia do mês atual
        $dataFimMesAtual = date('d/m/Y', strtotime('last day of this month')); // Último dia do mês atual
        if ($dataIni === '') { $dataIni = $dataIniMesAtual; }
        if ($dataFim === '') { $dataFim = $dataFimMesAtual; }

        // Converte datas para YYYY-MM-DD caso venham preenchidas
        if (!empty($dataIni)) {
            $dataInicial = c_date::convertDateBdSh($dataIni, $this->m_banco);
        } else {
            $dataInicial = null;
        }
        if (!empty($dataFim)) {
            $dataFinal = c_date::convertDateBdSh($dataFim, $this->m_banco);
        } else {
            $dataFinal = null;
        }

        // Cliente e número de atendimento
        $clienteId = isset($parmPost['pessoa']) ? $parmPost['pessoa'] : null;
        $numAtendimento = isset($parmPost['numAtendimento']) ? $parmPost['numAtendimento'] : null;

        $lanc = $this->buscaOrdemServico($id, $dataInicial, $dataFinal, $clienteId, $numAtendimento);
        
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        // Atribui filtros à view
        $this->smarty->assign('dataIni', $dataIni);
        $this->smarty->assign('dataFim', $dataFim);
        $this->smarty->assign('pessoa', $clienteId);
        $this->smarty->assign('nome', isset($parmPost['nome']) ? $parmPost['nome'] : '');
        $this->smarty->assign('numAtendimento', $numAtendimento);

        $this->smarty->assign('lanc', $lanc);  
        $this->smarty->assign('origem', $this->m_origem);


        $this->smarty->display('fatura_nfs_mostra.tpl');

    }



}

//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$form = new p_fatura_nfs();
                             
$form->controle();
?>
