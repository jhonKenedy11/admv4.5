<?php

/**
 * @package   astec
 * @name      p_grupo
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");

//Class P_situacao
Class p_pedido_venda_entrega extends c_pedidoVenda {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu, $letra) {
// Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Entrega Pedidos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "50"); 

        // include do javascript
        // include ADMjs . "/ped/s_pedido_venda_entrega.js";
    }

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'incluir':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $NotaFiscalOBJ = new c_nota_fiscal();

                    $NotaFiscalOBJ->setModelo('55');
                    $NotaFiscalOBJ->setSerie('1');
                    $NotaFiscalOBJ->setNumero('1234'); // ****** Gerar numero da notafiscal!! *********
                    $NotaFiscalOBJ->setPessoa('001'); // ****** Definel o cliente da nf de saida notafiscal!! *********
                    $NotaFiscalOBJ->setEmissao(date("d/m/Y H:i:s"));
                    $NotaFiscalOBJ->setNatOperacao($this->m_parPesq[1]);
                    $NotaFiscalOBJ->setTipo('1');
                    $NotaFiscalOBJ->setSituacao('B');
                    $NotaFiscalOBJ->setFormaPgto('');
                    // comentado por não utilização pela função inclui nota fiscal - Joshua
                    // $NotaFiscalOBJ->setCodMunFG(''); 
                    $NotaFiscalOBJ->setDataSaidaEntrada(date("d/m/Y H:i:s"));
                    $NotaFiscalOBJ->setFormaEmissao('');
                    $NotaFiscalOBJ->setFinalidadeEmissao('');
                    $NotaFiscalOBJ->setCentroCusto($this->m_empresacentrocusto);
                    $NotaFiscalOBJ->setGenero('');
                    $NotaFiscalOBJ->setTotalnf('');
                    $NotaFiscalOBJ->setObs('Nota fiscal emitida por granulação de produtos.');
                    $idGerado = $NotaFiscalOBJ->incluiNotaFiscal(); 

                    $pecasPedido = $this->select_pedido_item_id();
                    if (is_array($pecasPedido)) {
                        $NfProdutoOBJ = new c_nota_fiscal_produto();
                        for ($i = 0; $i < count($pecasPedido); $i++) {
                            $NfProdutoOBJ->setIdNf($idGerado);
                            $NfProdutoOBJ->setCodProduto($pecasPedido[0]['ITEMESTOQUE']);
                            $NfProdutoOBJ->setDescricao($pecasPedido[0]['DESCRICAO']);
                            $NfProdutoOBJ->setUnidade('UN');
                            $NfProdutoOBJ->setQuant($pecasPedido[0]['QTSOLICITADA']);
                            $NfProdutoOBJ->setUnitario($pecasPedido[0]['UNITARIO']);
                            $NfProdutoOBJ->setDesconto($pecasPedido[0]['DESCONTO']);
                            $NfProdutoOBJ->setTotal($pecasPedido[0]['TOTAL']);
                            $NfProdutoOBJ->setOrigem('0');
                            $NfProdutoOBJ->setTribIcms($pecasPedido[0]['TRIBICMS']);
                            $NfProdutoOBJ->setBcIcms($pecasPedido[0]['BCICMS']);
                            $NfProdutoOBJ->setCfop($this->m_parPesq[2]);
                            $NfProdutoOBJ->setValorIcms($pecasPedido[0]['VALORICMS']);
                            $NfProdutoOBJ->setValorIpi($pecasPedido[0]['VALORIPI']);
                            $NfProdutoOBJ->setAliqIpi($pecasPedido[0]['ALIQIPI']);
                            $NfProdutoOBJ->setCustoProduto($pecasPedido[0]['CUSTOPRODUTO']);
                            $NfProdutoOBJ->setNcm($pecasPedido[0]['NCM']);
                            $NfProdutoOBJ->setNrSerie($pecasPedido[0]['NRSERIE']);
                            $NfProdutoOBJ->setLote($pecasPedido[0]['LOTE']);
                            $NfProdutoOBJ->setDataValidade($pecasPedido[0]['DATAVALIDADE']);
                            $NfProdutoOBJ->setDataGarantia($pecasPedido[0]['DATAGARANTIA']);
                            $NfProdutoOBJ->setOrdem($pecasPedido[0]['ORDEM']);
                            $NfProdutoOBJ->setProjeto($pecasPedido[0]['PROJETO']);
                            $NfProdutoOBJ->setDataConferencia($pecasPedido[0]['DATACONFERENCIA']);
                            // comentado por não utilização pela função inclui nf produto - Joshua
                            // $NfProdutoOBJ->setLocalizacao($pecasPedido[0]['LOCALIZACAO']);
                            // $NfProdutoOBJ->setNumSerie($pecasPedido[0]['NUMSERIE']);
                            $NfProdutoOBJ->incluiNotaFiscalProduto();
                        }
                    }


                    $this->desenhaCadastroPedido();
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg = NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        //parametro de pesquisa
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $cfop = $parametros->getParametros("CFOP");
        $natOperacao = $parametros->getParametros("NATOPERACAO");
        $condPgto = $parametros->getParametros("CONDPGTO");
        $genero = $parametros->getParametros("GENERO");
        $conta = $parametros->getParametros("CONTA");
        $serie = $parametros->getParametros("SERIE");
        $parametros->close_connection();

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('cfop', $cfop);
        $this->smarty->assign('natOperacao', $natOperacao);
        $this->smarty->assign('serie', $serie);

        // COMBOBOX CONDICAO PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $condPgto);


        // COMBOBOX GENERO
        $consulta = new c_banco();
        $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero ORDER BY descricao;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $genero_ids[$i] = $result[$i]['ID'];
            $genero_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('genero_ids', $genero_ids);
        $this->smarty->assign('genero_names', $genero_names);
        $this->smarty->assign('genero_id', $genero);

        // COMBOBOX CONTA
        $consulta = new c_banco();
        $sql = "SELECT conta as id, nomeinterno as descricao FROM fin_conta  where status ='A' ORDER BY nomeinterno;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('conta_ids', $conta_ids);
        $this->smarty->assign('conta_names', $conta_names);
        $this->smarty->assign('conta_id', $conta);




        $this->smarty->display('pedido_venda_entrega_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem) {

        $lanc = $this->select_pedidoVenda_letra('||||2|');

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_entrega_mostra.tpl');
    }

//fim mostragrupos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$pedido = new p_pedido_venda_entrega($_POST['submenu'], $_POST['letra']);

if (isset($_POST['id'])) {
    $pedido->setId($_POST['id']);
} else {
    $pedido->setId('');
};

$pedido->controle();
?>
