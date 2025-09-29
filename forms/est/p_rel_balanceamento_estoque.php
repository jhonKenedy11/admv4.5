<?php
/**
 * @package   astec
 * @name      p_rel_balanceamento_estoque
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy
 * @date      04/02/2022
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_produto_estoque.php");
require_once($dir."/../../class/est/c_estoque_rel.php");

//Class p_rel_balanceamento_estoque
Class p_rel_balanceamento_estoque extends c_estoque_rel {

    private $m_submenu = null;
    private $m_opcao = null;

    public $m_grupo = null;
    public $m_letra = null;
    
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
    
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            default:
                $this->relatorioBalanceamentoEstoque('');
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
function relatorioBalanceamentoEstoque($mensagem){
    $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
    $this->smarty->assign('pathImagem', ADMimg);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('dataImp', date("d/m/Y"));
    
    $lanc = $this->select_relatorio_balanceamento_estoque();

    $resultProduto = [];
    $p = 0;
    $classProdutoQtde = new c_produto_estoque();
    for($i=0;$i<count($lanc);$i++){
        $produtoQuant = $classProdutoQtde->produtoQtdeCC($lanc[$i]['CODIGO'], $this->m_empresacentrocusto);
        
        $lanc[$i]['ESTOQUE'] = $produtoQuant[0]['ESTOQUE'];
        if($lanc[$i]['ESTOQUE'] === null){
            $lanc[$i]['ESTOQUE'] = 0;
        }

        $lanc[$i]['RESERVA'] = $produtoQuant[0]['RESERVA'];
        if($lanc[$i]['RESERVA'] === null){
            $lanc[$i]['RESERVA'] = 0;
        }

        $lanc[$i]['ENCOMENDA'] = $produtoQuant[0]['ENCOMENDA'];
        if($lanc[$i]['ENCOMENDA'] === null){
            $lanc[$i]['ENCOMENDA'] = 0;
        }

        $lanc[$i]['DISPONIVELVENDA'] = $lanc[$i]['ESTOQUE'] - $lanc[$i]['RESERVA'] - $lanc[$i]['ENCOMENDA'];
 
        $resultProduto[$p] = $lanc[$i];
        $p++;
        
    }

    $this->smarty->assign('pedido', $resultProduto);
    
    $this->smarty->display('relatorio_balanceamento_estoque.tpl');
}

//fim mostraProdutos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$produto = new p_rel_balanceamento_estoque();

$produto->controle();
?>
