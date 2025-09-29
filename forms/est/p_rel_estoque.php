<?php

/**
 * @package   admv4.5
 * @name      p_rel_estoque
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva <joshua.silva@admsistemas.com.br>
 * @date      04/08/2025
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_estoque_relatorio.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/est/c_produto.php");

class p_rel_estoque extends c_estoque_relatorio
{

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

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
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_tipo_relatorio = (isset($parmGet['tipoRelatorio']) ? $parmGet['tipoRelatorio'] : (isset($parmPost['tipoRelatorio']) ? $parmPost['tipoRelatorio'] : ''));
        
        // Parâmetros do relatório - datas já tratadas no JavaScript
        $this->setDataIni(isset($parmPost['dataIni']) ? $parmPost['dataIni'] : (isset($parmGet['dataIni']) ? $parmGet['dataIni'] : ''));
        $this->setDataFim(isset($parmPost['dataFim']) ? $parmPost['dataFim'] : (isset($parmGet['dataFim']) ? $parmGet['dataFim'] : ''));
        
        // Parâmetros do relatório usando setters diretamente
        $this->setIdGrupo(isset($parmPost['idGrupo']) ? $parmPost['idGrupo'] : (isset($parmGet['idGrupo']) ? $parmGet['idGrupo'] : ''));
        $this->setIdProduto(isset($parmPost['idProduto']) ? $parmPost['idProduto'] : (isset($parmGet['idProduto']) ? $parmGet['idProduto'] : array()));
        $this->setIdLocalizacao(isset($parmPost['idLocalizacao']) ? $parmPost['idLocalizacao'] : (isset($parmGet['idLocalizacao']) ? $parmGet['idLocalizacao'] : ''));
        $this->setTipoMovimento(isset($parmPost['tipoMovimento']) ? $parmPost['tipoMovimento'] : (isset($parmGet['tipoMovimento']) ? $parmGet['tipoMovimento'] : ''));
        $this->setDescProduto((isset($parmGet['descProduto']) ? $parmGet['descProduto'] : (isset($parmPost['descProduto']) ? $parmPost['descProduto'] : '')));
        $this->setDescCliente((isset($parmGet['descCliente']) ? $parmGet['descCliente'] : (isset($parmPost['descCliente']) ? $parmPost['descCliente'] : '')));
        $this->setidCliente(isset($parmPost['clienteFornecedor']) ? $parmPost['clienteFornecedor'] : (isset($parmGet['clienteFornecedor']) ? $parmGet['clienteFornecedor'] : ''));       $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : (isset($parmGet['centroCusto']) ? $parmGet['centroCusto'] : ''));
        $this->setTipoRelatorio(isset($parmPost['tipoRelatorio']) ? $parmPost['tipoRelatorio'] : (isset($parmGet['tipoRelatorio']) ? $parmGet['tipoRelatorio'] : ''));
        $this->setSituacaoNota(isset($parmPost['situacaoNF']) ? $parmPost['situacaoNF'] : (isset($parmGet['situacaoNF']) ? $parmGet['situacaoNF'] : ''));
        $this->setCurvaAbc(isset($parmPost['tipoCurvaABC']) ? $parmPost['tipoCurvaABC'] : (isset($parmGet['tipoCurvaABC']) ? $parmGet['tipoCurvaABC'] : '1'));
        $this->setOrdenacao(isset($parmPost['ordenacaoEstoque']) ? $parmPost['ordenacaoEstoque'] : (isset($parmGet['ordenacaoEstoque']) ? $parmGet['ordenacaoEstoque'] : ''));

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Relatório de Estoque");
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
            case 'buscar_produtos':
                $produtos = $this->buscarProdutosJson();
                echo json_encode($produtos);
                break;
            case 'buscar_clientes':
                $clientes = $this->buscarClientesJson();
                echo json_encode($clientes);
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
        $this->smarty->assign('dataFim', date("d/m/Y"));

        $this->comboRelatorioEstoque();

        $this->smarty->display('rel_estoque_mostra.tpl');
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
            case 'movimentacao':
                $resultado = $this->selectRelatorioMovimentacaoEstoque();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_movimentacao.tpl');
                break;
            case 'curva_abc':   
                $resultado = $this->selectCurvaAbc();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_curva_abc.tpl');
                break;
            case 'kardex_sintetico':
                $resultado = $this->selectKardexSintetico();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_kardex_sintetico.tpl');
                break;
            case 'kardex_analitico':
                $resultado = $this->selectKardexAnalitico();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_kardex_analitico.tpl');
                break;
            case 'estoque_geral':
                $resultado = $this->selectEstoqueGeral();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_geral.tpl');
                break;
            case 'estoque_localizacao':
                $resultado = $this->selectEstoqueLocalizacao();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_localizacao.tpl');
                break;
            case 'compras':
                $resultado = $this->selectRelatorioCompras();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_compras.tpl');
                break;
            case 'compras_sugestoes':
                $resultado = $this->selectRelatorioComprasSugestoes();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_compras_sugestoes.tpl');
                break;
            case 'compras_estoque_minimo':
                $resultado = $this->selectRelatorioComprasSugestoes();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_compras_estoque_minimo.tpl');
                break;
            case 'movimento_cliente':
                $resultado = $this->selectMovimentoEstoqueCliente();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_movimento_cliente.tpl');
                break;
            case 'consulta_preco':
                $resultado = $this->selectConsultaProdutoPreco();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_consulta_preco.tpl');
                break;
            case 'tabela_precos':
                $resultado = $this->selectTabelaPrecos();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_tabela_precos.tpl');
                break;
            default:
                $resultado = $this->selectRelatorioMovimentacaoEstoque();
                $this->smarty->assign('resultado', $resultado);
                $this->smarty->display('rel_estoque_movimentacao.tpl');
                break;
        }
    }

}
$estoque = new p_rel_estoque();

$estoque->controle();

?>