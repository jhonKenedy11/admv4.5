<?php
/**
 * @package   astec
 * @name      p_rel_compras
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silvao<marcio.sergio@admservice.com.br>
 * @date      27/04/2018
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_estoque_rel.php");
require_once($dir."/../../class/est/c_produto_estoque.php");

//Class p_rel_movimento_estoque
Class p_rel_movimento_estoque extends c_estoque_rel {

    private $m_submenu = null;
    private $m_opcao = null;

    public $m_grupo = null;
    public $m_letra = null;
    public $m_par = null;

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
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->m_grupo=(isset($parmGet['grupoSelected']) ? $parmGet['grupoSelected'] : (isset($parmPost['grupoSelected']) ? $parmPost['grupoSelected'] : ''));
        $this->m_localizacao = (isset($parmGet['localizacaoSelected']) ? $parmGet['localizacaoSelected'] : (isset($parmPost['localizacaoSelected']) ? $parmPost['localizacaoSelected'] : ''));
		        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"):
            $this->smarty->assign('titulo', "Produtos");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        else:
            $this->smarty->assign('titulo', "Produtos");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]"); 
            $this->smarty->assign('disableSort', "[ 0 ]"); 
            $this->smarty->assign('numLine', "25"); 
        endif;
    
        if($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[0]);
    
        if($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        }
        else $this->smarty->assign('dataFim', $this->m_par[1]);
            
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'geral':
                $this->relatorioLocalizacaoGeral('');
                break;
            default:
                $this->relatorioMovimentoEstoque('');
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
function relatorioMovimentoEstoque($mensagem){
    $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
    $this->smarty->assign('pathImagem', ADMimg);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

    //$lanc = $this->select_relatorio_estoque_geral('localizacao');
    $lanc = $this->select_geral_localizacao('localizacao') ?? [];
   
    $this->smarty->assign('pedido', $lanc);
    $this->smarty->assign('periodoIni', $this->m_par[0]);
    $this->smarty->assign('periodoFim', $this->m_par[1]);
    
    $this->smarty->display('relatorio_movimento_estoque_localizacao.tpl');
}

function relatorioLocalizacaoGeral($mensagem){
    $this->smarty->assign('pathImagem', ADMimg);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

    $lanc = $this->select_geral_localizacao('localizacao') ?? [];

    $p = 0;
    $classProdutoQtde = new c_produto_estoque();
    for($i=0;$i<count($lanc);$i++){
        
        $produtoQuant = $classProdutoQtde->produtoQtde($lanc[$i]['CODIGO'], $this->m_empresacentrocusto);
        
        $lanc[$i]['ESTOQUE'] = 0;
        $lanc[$i]['RESERVA'] = 0;
        for($q=0;$q<count($produtoQuant ?? []);$q++){
            if ($produtoQuant[$q]['STATUS'] == 0):
                    $lanc[$i]['ESTOQUE'] = $produtoQuant[$q]['QUANTIDADE'];
            else:    
                    $lanc[$i]['RESERVA'] = $produtoQuant[$q]['QUANTIDADE'];
            endif;

        }    

    };

    $this->smarty->assign('pedido', $lanc);
    $this->smarty->assign('periodoIni', $this->m_par[0]);
    $this->smarty->assign('periodoFim', $this->m_par[1]);

    $this->smarty->display('relatorio_localizacao_geral.tpl');
}

//fim mostraProdutos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$produto = new p_rel_movimento_estoque();

$produto->controle();
?>
