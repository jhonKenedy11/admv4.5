<?php

/**
 * @package   astec
 * @name      p_nota_fiscal_granulacao
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      16/05/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");

//Class p_nota_fiscal_franulacao
Class p_nota_fiscal_granulacao extends c_nota_fiscal {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_pesq = NULL;
    private $m_produtos = NULL;
    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu, $letra, $pesquisa,$produtos) {
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
        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_pesq = $pesquisa;
        $this->m_produtos = $produtos;
        $this->m_par = explode("|", $this->m_letra);
        $this->m_parPesq = explode("|", $this->m_pesq);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathCliente', ADMhttpCliente);

        // include do javascript
        // include ADMjs . "/est/s_nota_fiscal_granulacao.js";
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
                    // ID da nota fiscal atraves do numero NF
                    $this->setNumero($this->m_par[0]);
                    $idNf = $this->existeNotaFiscalNum();
                    // Sets das informacoes da nota fiscal de saida
                    $this->setModelo('55');
                    $this->setSerie('1');
                    $this->setNumero('1234'); // ****** Gerar numero da notafiscal!! *********
                    $this->setPessoa('001');// ****** Definel o cliente da nf de saida notafiscal!! *********
                    $this->setEmissao(date("d/m/Y H:i:s"));
                    $this->setNatOperacao($this->m_parPesq[1]);
                    $this->setTipo('1');
                    $this->setSituacao('B');
                    $this->setFormaPgto('');
                    $this->setCodMunFG('');
                    $this->setDataSaidaEntrada(date("d/m/Y H:i:s"));
                    $this->setFormaEmissao('');
                    $this->setFinalidadeEmissao('');
                    $this->setCentroCusto($this->m_empresacentrocusto);
                    $this->setGenero('');
                    $this->setTotalnf('');
                    $this->setObs('Nota fiscal emitida por granulação de produtos.');
                    $idGerado = $this->incluiNotaFiscal();
                    
                    if (!empty($this->m_produtos)){
                        $prodChecados = explode("|", $this->m_produtos);
                        for ($i=0 ; $i<count($prodChecados);$i++){
                            $NfProdutoOBJ = new c_nota_fiscal_produto();
                            $NfProdutoOBJ->setId($prodChecados[$i]);
                            $NfProdutoOBJ->setIdNf($idNf[0]['ID']);
                            $arrNfProduto = $NfProdutoOBJ->select_nota_fiscal_produto();
                            
                            //Sets dos produtos para nota fiscal de saida
                            $NfProdutoOBJ->setIdNf($idGerado);
                            $NfProdutoOBJ->setCodProduto($arrNfProduto[0]['CODPRODUTO']);
                            $NfProdutoOBJ->setItemFabricante($arrNfProduto[0]['ITEMFABRICANTE']);
                            $NfProdutoOBJ->setDescricao($arrNfProduto[0]['DESCRICAO']);
                            $NfProdutoOBJ->setUnidade($arrNfProduto[0]['UNIDADE']);
                            $NfProdutoOBJ->setQuant($arrNfProduto[0]['QUANT']);
                            $NfProdutoOBJ->setUnitario($arrNfProduto[0]['UNITARIO']);
                            $NfProdutoOBJ->setDesconto($arrNfProduto[0]['DESCONTO']);
                            $NfProdutoOBJ->setTotal($arrNfProduto[0]['TOTAL']);
                            $NfProdutoOBJ->setOrigem($arrNfProduto[0]['ORIGEM']);
                            $NfProdutoOBJ->setTribIcms($arrNfProduto[0]['TRIBICMS']);
                            $NfProdutoOBJ->setBcIcms($arrNfProduto[0]['BCICMS']);
                            $NfProdutoOBJ->setCfop($this->m_parPesq[2]);
                            $NfProdutoOBJ->setValorIcms($arrNfProduto[0]['VALORICMS']);
                            $NfProdutoOBJ->setValorIpi($arrNfProduto[0]['VALORIPI']);
                            $NfProdutoOBJ->setAliqIpi($arrNfProduto[0]['ALIQIPI']);
                            $NfProdutoOBJ->setCustoProduto($arrNfProduto[0]['CUSTOPRODUTO']);
                            $NfProdutoOBJ->setNcm($arrNfProduto[0]['NCM']);
                            $NfProdutoOBJ->setNrSerie($arrNfProduto[0]['NRSERIE']);
                            $NfProdutoOBJ->setLote($arrNfProduto[0]['LOTE']);
                            $NfProdutoOBJ->setDataValidade($arrNfProduto[0]['DATAVALIDADE']);
                            $NfProdutoOBJ->setDataGarantia($arrNfProduto[0]['DATAGARANTIA']);
                            $NfProdutoOBJ->setOrdem($arrNfProduto[0]['ORDEM']);
                            $NfProdutoOBJ->setProjeto($arrNfProduto[0]['PROJETO']);
                            $NfProdutoOBJ->setDataConferencia($arrNfProduto[0]['DATACONFERENCIA']);
                            $NfProdutoOBJ->setLocalizacao($arrNfProduto[0]['LOCALIZACAO']);
                            $NfProdutoOBJ->setNumSerie($arrNfProduto[0]['NUMSERIE']);
                            $NfProdutoOBJ->incluiNotaFiscalProduto();
                        }
                    }
                    
                    
                    
                    // Sets das informacoes da nota fiscal de saida
                    $this->setModelo('55');
                    $this->setSerie('1');
                    $this->setNumero('1234'); // ****** Gerar numero da notafiscal!! *********
                    $this->setPessoa('001');// ****** Definel o cliente da nf de saida notafiscal!! *********
                    $this->setEmissao(date("d/m/Y H:i:s"));
                    $this->setNatOperacao($this->m_parPesq[3]);
                    $this->setTipo('0');
                    $this->setSituacao('B');
                    $this->setFormaPgto('');
                    $this->setCodMunFG('');
                    $this->setDataSaidaEntrada(date("d/m/Y H:i:s"));
                    $this->setFormaEmissao('');
                    $this->setFinalidadeEmissao('');
                    $this->setCentroCusto($this->m_empresacentrocusto);
                    $this->setGenero('');
                    $this->setTotalnf('');
                    $this->setObs('Nota fiscal emitida por granulação de produtos.');
                    $idGerado = $this->incluiNotaFiscal();
                    
                    if (!empty($this->m_produtos)){
                        $prodChecados = explode("|", $this->m_produtos);
                        for ($i=0 ; $i<count($prodChecados);$i++){
                            $NfProdutoOBJ = new c_nota_fiscal_produto();
                            $NfProdutoOBJ->setId($prodChecados[$i]);
                            $NfProdutoOBJ->setIdNf($idNf[0]['ID']);
                            $arrNfProduto = $NfProdutoOBJ->select_nota_fiscal_produto();
                            
                            //Sets dos produtos para nota fiscal de saida
                            $NfProdutoOBJ->setIdNf($idGerado);
                            $NfProdutoOBJ->setCodProduto($arrNfProduto[0]['CODPRODUTO']);
                            $NfProdutoOBJ->setItemFabricante($arrNfProduto[0]['ITEMFABRICANTE']);
                            $NfProdutoOBJ->setDescricao($arrNfProduto[0]['DESCRICAO']);
                            $NfProdutoOBJ->setUnidade('UN');
                            $NfProdutoOBJ->setQuant($arrNfProduto[0]['QUANT']);
                            $NfProdutoOBJ->setUnitario($arrNfProduto[0]['UNITARIO']);
                            $NfProdutoOBJ->setDesconto($arrNfProduto[0]['DESCONTO']);
                            $NfProdutoOBJ->setTotal($arrNfProduto[0]['TOTAL']);
                            $NfProdutoOBJ->setOrigem($arrNfProduto[0]['ORIGEM']);
                            $NfProdutoOBJ->setTribIcms($arrNfProduto[0]['TRIBICMS']);
                            $NfProdutoOBJ->setBcIcms($arrNfProduto[0]['BCICMS']);
                            $NfProdutoOBJ->setCfop($this->m_parPesq[4]);
                            $NfProdutoOBJ->setValorIcms($arrNfProduto[0]['VALORICMS']);
                            $NfProdutoOBJ->setValorIpi($arrNfProduto[0]['VALORIPI']);
                            $NfProdutoOBJ->setAliqIpi($arrNfProduto[0]['ALIQIPI']);
                            $NfProdutoOBJ->setCustoProduto($arrNfProduto[0]['CUSTOPRODUTO']);
                            $NfProdutoOBJ->setNcm($arrNfProduto[0]['NCM']);
                            $NfProdutoOBJ->setNrSerie($arrNfProduto[0]['NRSERIE']);
                            $NfProdutoOBJ->setLote($arrNfProduto[0]['LOTE']);
                            $NfProdutoOBJ->setDataValidade($arrNfProduto[0]['DATAVALIDADE']);
                            $NfProdutoOBJ->setDataGarantia($arrNfProduto[0]['DATAGARANTIA']);
                            $NfProdutoOBJ->setOrdem($arrNfProduto[0]['ORDEM']);
                            $NfProdutoOBJ->setProjeto($arrNfProduto[0]['PROJETO']);
                            $NfProdutoOBJ->setDataConferencia($arrNfProduto[0]['DATACONFERENCIA']);
                            $NfProdutoOBJ->setLocalizacao($arrNfProduto[0]['LOCALIZACAO']);
                            $NfProdutoOBJ->setNumSerie($arrNfProduto[0]['NUMSERIE']);
                            $NfProdutoOBJ->incluiNotaFiscalProduto();
                        }
                    }
                    
                    
                    $this->mostraNotaFiscalGranulacao('');
                }
                break;
            
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->mostraNotaFiscalGranulacao('');
                }
        }
    }


/**
 * @name mostraNotaFiscalGranulacao
 * @param STRING $mensagem Mensagem que vai ser apresentada na tela
 * @param STRING $tipoMsg Tipo da mensagem (sucesso/alerta)
 */
    function mostraNotaFiscalGranulacao($mensagem=NULL, $tipoMsg = NULL) {
        // Condicao do numero da nota fiscal
        if (!empty($this->m_par[0])){
            $this->setNumero($this->m_par[0]);
            $idNf = $this->existeNotaFiscalNum();
            if (is_array($idNf)){
                $this->smarty->assign('numNf', $this->m_par[0]);
                $produtoOBJ = new c_nota_fiscal_produto();
                $produtoOBJ->setIdNf($idNf[0]['ID']);
                $lanc = $produtoOBJ->select_nota_fiscal_produto_nf();
            }else{
                $this->smarty->assign('numNf', '');
                $tipoMsg = 'alerta';
                $mensagem = 'Não foi encontrado nota fiscal com este número.';
            }
        }
    //campos de pesquisa ######################################
        $this->smarty->assign('fator', $this->m_parPesq[0]);
        $this->smarty->assign('origemNatOp', $this->m_parPesq[1]);
        $this->smarty->assign('origemCfop', $this->m_parPesq[2]);
        $this->smarty->assign('destinoNatOp', $this->m_parPesq[3]);
        $this->smarty->assign('destinoCfop', $this->m_parPesq[4]);
        

        $this->smarty->assign('produtos', $this->produtos);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('nota_fiscal_granulacao_mostra.tpl');
    }

//fim mostragrupos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$notaFiscal = new p_nota_fiscal_granulacao($_POST['submenu'], $_POST['letra'], $_POST['pesquisa'], $_POST['produtos']);

if (isset($_POST['numNf'])) { $notaFiscal->setId($_POST['numNf']); } else {$notaFiscal->setId('');};

$notaFiscal->controle();
?>
