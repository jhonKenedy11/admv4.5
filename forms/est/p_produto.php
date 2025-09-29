<?php

/**
 * @package   astec
 * @name      p_produto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      13/04/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);

require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/crm/c_conta.php");
require_once($dir . "/../../class/est/c_ncm.php");
require_once($dir . "/../../class/est/c_estoque_rel.php");
require_once($dir . "/../../class/ped/c_parametro.php");

//Class P_produto
class p_produto extends c_produto
{

    private $m_submenu = null;

    private $m_quant = null;
    private $m_fora = null;
    private $m_opcao = null;
    private $m_origem = null;
    private $m_quantNova = null;
    private $m_quantTotal = null;
    private $m_form_old = NULL;
    private $m_pesquisaCodigo = NULL;
    private $m_carrinho = NULL;
    private $m_idPedido = NULL;
    private $m_function = NULL;
    private $m_produtoId = NULL;
    private $m_divId = NULL;
    private $m_kitReparo = NULL;
    private $produto = null;



    public $m_letra = null;
    public $from = NULL; // tela de pesquisa (qual tpl esta chamando)
    public $acao = NULL; // tela de pesquisa (qual acao ira tomar ex: alterar)
    public $tipoCategoriaAtendimento = NULL;

    private $pedidoChecked = null; //checkbox pesquisa
    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
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
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->m_origem = (isset($parmGet['origem']) ? $parmGet['origem'] : (isset($parmPost['origem']) ? $parmPost['origem'] : ''));
        $this->m_quantNova = (isset($parmGet['quantNova']) ? $parmGet['quantNova'] : (isset($parmPost['quantNova']) ? $parmPost['quantNova'] : 0));
        $this->m_quantTotal = (isset($parmGet['quantTotal']) ? $parmGet['quantTotal'] : (isset($parmPost['quantTotal']) ? $parmPost['quantTotal'] : 0));
        $this->m_pesquisaCodigo = (isset($parmGet['pesquisaCodigo']) ? $parmGet['pesquisaCodigo'] : (isset($parmPost['pesquisaCodigo']) ? $parmPost['pesquisaCodigo'] : null));
        $this->m_carrinho = (isset($parmGet['carrinho']) ? $parmGet['carrinho'] : (isset($parmPost['carrinho']) ? $parmPost['carrinho'] : null));
        $this->m_idPedido = (isset($parmGet['idPedido']) ? $parmGet['idPedido'] : (isset($parmPost['idPedido']) ? $parmPost['idPedido'] : null));
        $this->m_kitReparo = (isset($parmPost['codKitReparo']) ? $parmPost['codKitReparo'] : null);

        $this->m_function = (isset($parmPost['function']) ? $parmPost['function'] : null);
        $this->m_produtoId = (isset($parmPost['produtoId']) ? $parmPost['produtoId'] : null);
        $this->m_divId = (isset($parmPost['divId']) ? $parmPost['divId'] : null);

        $this->from = (isset($parmGet['from']) ? $parmGet['from'] : (isset($parmPost['from']) ? $parmPost['from'] : ''));

        $this->acao = (isset($parmGet['acao']) ? $parmGet['acao'] : (isset($parmPost['acao']) ? $parmPost['acao'] : ''));
        $this->m_form_old = (isset($parmGet['form_old']) ? $parmGet['form_old'] : (isset($parmPost['form_old']) ? $parmPost['form_old'] : ''));

        // form produto_pesquisar pedidoChecked [checkbox]
        $this->pedidoChecked = (isset($parmGet['checkbox']) ? $parmGet['checkbox'] : (isset($parmPost['checkbox']) ? $parmPost['checkbox'] : ''));

        // form atendimento (consulta)
        $this->idTipoAtendimento = (isset($parmGet['idTipoAtendimento']) ? $parmGet['idTipoAtendimento'] : (isset($parmPost['idTipoAtendimento']) ? $parmPost['idTipoAtendimento'] : ''));
        $this->tipoCategoriaAtendimento = (isset($parmGet['tipoCategoriaAtendimento']) ? $parmGet['tipoCategoriaAtendimento'] : (isset($parmPost['tipoCategoriaAtendimento']) ? $parmPost['tipoCategoriaAtendimento'] : ''));

        $this->m_idImagem = $_REQUEST['idimg'];
        $this->m_destaque = $_REQUEST['destaque'];
        $this->m_titulo   = $_REQUEST['tituloImg'];

        $this->m_par = explode("|", $this->m_letra);
        $this->m_quant = $quant;
        $this->m_fora = $fora;

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('pathBibImagens',  ADMhttpBib . '/bib/imagens');
        //ADMraizFonte
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        if ($this->m_opcao == "pesquisar"):
            $this->smarty->assign('titulo', "Produtos");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
            $this->smarty->assign('disableSort', "[ 5 ]");
            $this->smarty->assign('numLine', "25");
        else:
            $this->smarty->assign('titulo', "Produtos");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6 ]");
            $this->smarty->assign('disableSort', "[ 6 ]");
            $this->smarty->assign('numLine', "25");
        endif;

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setDesc(isset($parmPost['desc']) ? $parmPost['desc'] : '');
        $this->setDescricaoDetalhada(isset($parmPost['descricaoDetalhada']) ? $parmPost['descricaoDetalhada'] : '');
        $this->setGrupo(isset($parmPost['grupo']) ? $parmPost['grupo'] : '');
        $this->setUni(isset($parmPost['uni']) ? $parmPost['uni'] : '');
        $this->setUniFracionada(isset($parmPost['uniFracionada']) ? $parmPost['uniFracionada'] : 'S');
        $this->setFabricante(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setCodFabricante(isset($parmPost['codFabricante']) ? $parmPost['codFabricante'] : '');
        $this->setCodBarras(isset($parmPost['codBarras']) ? $parmPost['codBarras'] : '');
        $this->setCodProdutoAnvisa(isset($parmPost['codProdutoAnvisa']) ? $parmPost['codProdutoAnvisa'] : '');
        $this->setLocalizacao(isset($parmPost['localizacao']) ? $parmPost['localizacao'] : '');
        $this->setDataForaLinha(isset($parmPost['dataForaLinha']) ? $parmPost['dataForaLinha'] : '');
        $this->setNcm(isset($parmPost['ncm']) ? $parmPost['ncm'] : '');
        $this->setCest(isset($parmPost['cest']) ? $parmPost['cest'] : '');
        $this->setOrigem(isset($parmPost['origem']) ? $parmPost['origem'] : '');
        $this->setTribIcms(isset($parmPost['tribIcms']) ? $parmPost['tribIcms'] : '');
        $this->setMoeda(isset($parmPost['moeda']) ? $parmPost['moeda'] : '');
        $this->setVenda(isset($parmPost['venda']) ? $parmPost['venda'] : '');
        $this->setCustoCompra(isset($parmPost['custoCompra']) ? $parmPost['custoCompra'] : '0');
        $this->setCustoMedio(isset($parmPost['custoMedio']) ? $parmPost['custoMedio'] : '0');
        $this->setCustoReposicao(isset($parmPost['custoReposicao']) ? $parmPost['custoReposicao'] : '');
        $this->setQuantMinima(isset($parmPost['quantMinima']) ? $parmPost['quantMinima'] : '0,00');
        $this->setQuantMaxima(isset($parmPost['quantMaxima']) ? $parmPost['quantMaxima'] : '0,00');
        $this->setobs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setPrecoPromocao(isset($parmPost['precoPromocao']) ? $parmPost['precoPromocao'] : '0,00');
        $this->setInicioPromocao(isset($parmPost['inicioPromocao']) ? $parmPost['inicioPromocao'] : '');
        $this->setFimPromocao(isset($parmPost['fimPromocao']) ? $parmPost['fimPromocao'] : '');
        $this->setQuantLimite(isset($parmPost['quantLimite']) ? $parmPost['quantLimite'] : '0,00');
        $this->setTipoPromocao(isset($parmPost['tipoPromocao']) ? $parmPost['tipoPromocao'] : '');
        $this->setPrecoPromocao1(isset($parmPost['precoPromocao1']) ? $parmPost['precoPromocao1'] : '');
        $this->setInicioPromocao1(isset($parmPost['inicioPromocao1']) ? $parmPost['inicioPromocao1'] : '');
        $this->setFimPromocao1(isset($parmPost['fimPromocao1']) ? $parmPost['fimPromocao1'] : '');
        $this->setQuantLimite1(isset($parmPost['quantLimite1']) ? $parmPost['quantLimite1'] : '');
        $this->setPrecoBase(isset($parmPost['precoBase']) ? $parmPost['precoBase'] : '');
        $this->setPrecoInformado(isset($parmPost['precoInformado']) ? $parmPost['precoInformado'] : '0,00');
        $this->setPercCalculo(isset($parmPost['percCalculo']) ? $parmPost['percCalculo'] : '');
        $this->setDataUltimaCompra(isset($parmPost['dataUltimaCompra']) ? $parmPost['dataUltimaCompra'] : '');
        $this->setQuantUltimaCompra(isset($parmPost['quantUltimaCompra']) ? $parmPost['quantUltimaCompra'] : '');
        $this->setNfUltimaCompra(isset($parmPost['nfUltimaCompra']) ? $parmPost['nfUltimaCompra'] : '');
        $this->setDateChange(isset($parmPost['dateChange']) ? $parmPost['dateChange'] : '');
        $this->setPeso(isset($parmPost['peso']) ? $parmPost['peso'] : '0,00');
        $this->setPrecoMinimo(isset($parmPost['precoMinimo']) ? $parmPost['precoMinimo'] : '0,00');
        $this->setAnp(isset($parmPost['anp']) ? $parmPost['anp'] : '');
        $this->setMarca(isset($parmPost['marca']) ? $parmPost['marca'] : '');


        // REPARO DADOS UNITARIO
        $this->setIdReparo(isset($parmPost['idReparo']) ? $parmPost['idReparo'] : '');
        $this->setProdutoReparo(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setProdutoIdReparo(isset($parmPost['reparoCodProduto']) ? $parmPost['reparoCodProduto'] : '');
        $this->setQuantReparo(isset($parmPost['reparoQuant']) ? $parmPost['reparoQuant'] : '');

        $this->setReparoCodFabricante(isset($parmPost['reparoCodFabricante']) ? $parmPost['reparoCodFabricante'] : '');
        $this->reparoCodFabricante = (isset($parmGet['reparoCodFabricante']) ? $parmGet['reparoCodFabricante'] : (isset($parmPost['reparoCodFabricante']) ? $parmPost['reparoCodFabricante'] : ''));
        $this->setReparoCodProduto(isset($parmPost['reparoCodProduto']) ? $parmPost['reparoCodProduto'] : '');

        // EQUIVALENCIA DADOS UNITARIO
        $this->setIdEquiv(isset($parmPost['idEquiv']) ? $parmPost['idEquiv'] : '');
        $this->setContaEquiv(isset($parmPost['contaEquiv']) ? $parmPost['contaEquiv'] : '');
        $this->setCodEquivalente(isset($parmPost['codEquivalente']) ? $parmPost['codEquivalente'] : '');
        if (isset($parmPost['pessoa'])) {
            if ($parmPost['pessoa'] != "0") {
                $this->setContaEquiv($parmPost['pessoa']);
                if ($this->getCodEquivalente() == '') {
                    $this->setCodEquivalente($parmPost['codFabricante']);
                }
            }
        }
        $this->setDataUltimaCompraEquiv(isset($parmPost['dataUltimaCompraEquiv']) ? $parmPost['dataUltimaCompraEquiv'] : '');
        $this->setQuantUltimaCompraEquiv(isset($parmPost['quantUltimaCompraEquiv']) ? $parmPost['quantUltimaCompraEquiv'] : '');
        $this->setNfUltimaCompraEquiv(isset($parmPost['nfUltimaCompraEquiv']) ? $parmPost['nfUltimaCompraEquiv'] : '');


        // include do javascript
        //include ADMjs . "/est/s_produto.js";

        $parametros = new c_parametros();
        $parametros->setFilial($this->m_empresacentrocusto); // ou o campo correto de filial
        $casasDecimais = $parametros->getCasasDecimais();
        $this->smarty->assign('casasDecimais', $casasDecimais);
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function insereQuant($quant)
    {

        $objEstProduto = new c_produto_estoque();
        $classNF = new c_nota_fiscal();
        $classNFProduto = new c_nota_fiscal_produto();
        $tipoNf = '0';

        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=" . $this->m_empresacentrocusto);
        $clientePadrao = $parametros->getField("CLIENTEPADRAO", "FILIAL=" . $this->m_empresacentrocusto);
        $parametros->close_connection();


        $qtde = $quant; //(int) $quant;
        if ($qtde < 0) {
            $qtde = $qtde * -1;
            $tipoNf = '1';
        }

        if ($tipoNf == '1') {
            $modelo = '1';
            $serieDocto = 'SAI';
        } else {
            $modelo = '0';
            $serieDocto = 'ENT';
        }
        $quantidade = str_replace(',', '.', $qtde);
        $totalNf = $quantidade * $this->getVenda();

        $totalFormatado = number_format((float) $totalNf, 2, ',', '.');
        //EST_NOTA_FISCAL
        $classNF->setModelo($modelo);
        $classNF->setSerie($serieDocto);
        $classNF->setNumero(0);
        $classNF->setPessoa($clientePadrao);
        $classNF->setEmissao(date('d/m/Y H:i'));
        //nat operacao
        $classNF->setIdNatop(99);
        $classNF->setNatOperacao('AJUSTE QUANTIDADE DE ESTOQUE');
        $classNF->setTipo($tipoNf); // 0=Entrada; 1=Saída; 
        $classNF->setSituacao('B');
        $classNF->setFormaPgto('0');
        $classNF->setDataSaidaEntrada(date('d/m/Y H:i'));
        $classNF->setFinalidadeEmissao(9);
        $classNF->setTransportador(0);
        $classNF->setCentroCusto($this->m_empresacentrocusto);
        $classNF->setGenero(99);
        $classNF->setOrigem('AJT');
        $classNF->setDoc(0);
        $classNF->setModFrete(0); // verificar outras opção de frete no XML
        $classNF->setTotalnf($totalFormatado);
        $classNF->setObs('AJUSTE DE ESTOQUE REALIZADO PELA ALTERAÇÃO DO PRODUTO');
        // insere nf
        $lastNF = $classNF->incluiNotaFiscal();

        $classNF->setId($lastNF);
        $classNF->setNumero($lastNF);
        $classNF->alteraNfNumero();

        //EST_NOTA_FISCAL_ESTOQUE
        $total = 1;
        $classNFProduto->setIdNf($lastNF);
        $classNFProduto->setCodProduto($this->getId());
        $classNFProduto->setDescricao($this->getDesc());
        $classNFProduto->setUnidade($this->getUni());
        //$classNFProduto->setQuant($quant);
        $classNFProduto->setQuant($qtde);
        $classNFProduto->setUnitario($this->getVenda('F'));
        $classNFProduto->setTotal($totalFormatado);
        $classNFProduto->setOrigem('0');
        $classNFProduto->setTribIcms('00');
        $classNFProduto->setCfop('9999');
        $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));
        $classNFProduto->setModBcSt('0');
        $classNFProduto->incluiNotaFiscalProduto();

        // QUANTIDADE PRODUTO_ESTOQUE 

        $ifControlaEstoque = (($controlaEstoque == 'S') && ($this->getUniFracionada() == 'N'));
        if ($ifControlaEstoque):
            $objEstProduto = new c_produto_estoque();
            if ($tipoNf == '0'):
                for ($i = 0; $i < $qtde; $i++) {
                    $objEstProduto->setIdNfEntrada($lastNF);
                    $objEstProduto->setCodProduto($this->getId());
                    $objEstProduto->setStatus('0');
                    $objEstProduto->setAplicado('0');
                    $objEstProduto->setCentroCusto($this->m_empresacentrocusto);
                    $objEstProduto->setUserProduto($this->m_userid);
                    $objEstProduto->setLocalizacao('');
                    //$objEstProduto->setNsEntrada($this->getNumSerie());
                    $objEstProduto->setFabLote('');
                    $objEstProduto->setDataFabricacao('');
                    $objEstProduto->setDataValidade('');
                    $objEstProduto->incluiProdutoEstoque();
                } //for
            else:
                $objEstProduto->produtoBaixa($this->m_empresacentrocusto, $this->getId(), $qtde, $lastNF);
            endif;
        endif;
    } // insere quantidade
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
        switch ($this->m_submenu) {
            case 'destaqueImagem':
                $tipoMsg = 'sucesso';
                $this->destaqueImagemProdutoNao();
                $errMSG = $this->destaqueImagemProduto($this->m_idImagem, $this->m_destaque);
                if ($errMSG != ''):
                    $tipoMsg = 'erro';
                endif;
                $this->desenhaCadastroImagemProduto($errMSG, $tipoMsg);
                break;
            case 'excluiImagem':
                $tipoMsg = 'erro';
                $errMSG = $this->excluiImagemProduto($this->m_idImagem);
                if ($errMSG == ''):
                    unlink('images/doc/meli/' . $this->getId() . '/' . $this->m_idImagem . '.jpg');
                    $tipoMsg = 'sucesso';
                endif;
                $this->desenhaCadastroImagemProduto($errMSG, $tipoMsg);
                break;
            case 'salvarImagem':
                if ($this->select_produto_imagem()) {
                    $idImagem = $this->gravaImagemProduto('EST', 'N');
                } else {
                    $idImagem = $this->gravaImagemProduto('EST', 'S');
                }

                //$tipoMsg = "sucesso";
                if ($idImagem > 0):

                    $imgFile = $_FILES['upload']['name'];
                    $tmp_dir = $_FILES['upload']['tmp_name'];
                    $imgSize = $_FILES['upload']['size'];

                    if (empty($imgFile) and (is_file($this->m_tmp))):
                        $errMSG = "Selecione uma imagem.";
                    else:
                        $upload_dir = ADMraizCliente . "/images/doc/est/" . $this->getId() . "/"; // upload directory

                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }

                        //$upload_dir = $this->mkDir('images', 'auto', $this->getId());
                        //$upload_dir = 'images/auto/'; // upload directory

                        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

                        // valid image extensions
                        $valid_extensions = array('jpeg', 'jpg'); // valid extensions

                        // rename uploading image
                        $anunciopic = $idImagem . ".jpg";



                        $tipoMsg = "sucesso";
                        // allow valid image file formats
                        if (in_array($imgExt, $valid_extensions)):
                            // Check file size '2MB'
                            if ($imgSize < 2000000):
                                try {
                                    if (!move_uploaded_file($tmp_dir, $upload_dir . $anunciopic)):
                                        //throw new RuntimeException('Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.');                                                    
                                        $errMSG = "Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.";
                                        $tipoMsg = "erro";
                                    endif;
                                } catch (Error $e) {
                                    throw new Exception($e->getMessage() . "Imagem não salva ");
                                }

                            else:
                                $errMSG = "Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.";
                                $tipoMsg = "erro";
                            endif;

                        else:
                            $errMSG = "Desculpe, inserir somente arquivo JPG, JPEG são permitidos.";
                            $tipoMsg = "erro";
                        endif;
                    endif;

                    if ($tipoMsg != "sucesso"):
                        $this->excluiImagemProduto($idImagem);
                    endif;
                else:
                    $errMSG = "Imagem não foi salva.";
                    $tipoMsg = "erro";
                endif;

                $this->desenhaCadastroImagemProduto($errMSG, $tipoMsg);

                break;
            case 'cadastrarImagem':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    $this->desenhaCadastroImagemProduto();
                }
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    $this->setUniFracionada('S');
                    $this->desenhaCadProduto();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'A')) {
                    $this->produto();

                    $this->desenhaCadProduto();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    //OLD
                    $insert = true;
                    $msg = $this->existeProdutoFabricante($this->getCodFabricante());

                    if (($insert) and ($msg == '')) {
                        $msg = $this->incluiProduto();
                        $this->setId($msg);
                        if (($this->getCodEquivalente() != '') and
                            ($this->getCodEquivalente() != $this->getCodFabricante())
                        ) {
                            $this->incluiProdutoEquivalencia();
                        }
                        $quant = str_replace('.', '', $this->m_quantNova);
                        $quant = str_replace(',', '.', $quant);
                        if (abs($quant) > 0) {
                            $this->insereQuant($this->m_quantTotal);
                        }
                    }

                    if ($this->m_form_old != "produtoPesquisarNfe") {
                        if ($msg) {
                            $msgRetorno = 'Cadastro produto realizado!';
                            // echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                            // echo "<style>.swal-modal{width: 510px !important;}.swal-title{font-size: 21px;}</style> ";
                            // echo "<script>swal({text: `$msgRetorno`, title: 'Sucesso!', icon: 'success',button: 'Ok',});</script>";
                            // echo "<script>setTimeout(function() { window.opener.location.reload(); window.close(); }, 2000);</script>";
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->mostraProduto('');
                        } else {
                            $this->m_submenu = 'cadastrar';
                            $this->desenhaCadProduto($msg, 'alerta');
                        }
                    } else {
                        if (is_int($msg) || $msg = true) { //testar cadastro
                            $msgRetorno = 'Produto cadastrado!';
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->desenhaCadProduto('');
                        } else {
                            $msgRetorno = 'Erro ao inserir o produto, entre em contato com o suporte!';
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                            $this->desenhaCadProduto('');
                        }
                    }

                    //NEW
                    // $msg = $this->incluiProduto();

                    // if(is_int($msg)){
                    //     $this->setId($msg);
                    //     if (($this->getCodEquivalente() != '') and 
                    //         ($this->getCodEquivalente() != $this->getCodFabricante())){
                    //         $this->incluiProdutoEquivalencia();
                    //     }

                    //     $quant = str_replace('.', '',$this->m_quantNova);
                    //     $quant = str_replace(',', '.', $quant);

                    //     if (abs($quant) > 0) {
                    //         $this->insereQuant($this->m_quantTotal);
                    //     }

                    //     if($this->m_form_old != "produtoPesquisarNfe"){
                    //         if($msg){
                    //             $this->mostraProduto('Cadastro produto realizado com sucesso, código: '.$msg,'sucesso');
                    //         }else{
                    //             $this->m_submenu = 'cadastrar';
                    //             $msg = 'Erro ao inserir o produto, entre em contato com o suporte!';
                    //             $this->desenhaCadProduto($msg, 'alerta');
                    //         }
                    //     }else{
                    //         if($msg){
                    //             $msgRetorno = 'Produto cadastrado!';
                    //             echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                    //             echo "<style>.swal-modal{width: 510px !important;}.swal-title{font-size: 21px;}</style> ";
                    //             echo "<script>swal({text: `$msgRetorno`, title: 'Sucesso!', icon: 'success',button: 'Ok',});</script>";
                    //             echo "<script>setTimeout(function() { window.opener.location.reload(); window.close(); }, 2000);</script>";
                    //             $this->desenhaCadProduto('');
                    //         }
                    //     }

                    // }else{
                    //     $msgRetorno = 'Erro ao inserir o produto, entre em contato com o suporte!';
                    //     echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                    //     echo "<style>.swal-modal{width: 510px !important;}.swal-title{font-size: 21px;}</style> ";
                    //     echo "<script>swal({text: `$msgRetorno`, title: 'Atenção!', icon: 'error',button: 'Ok',});</script>";
                    //     echo "<script>setTimeout(function() { window.opener.location.reload(); window.close(); }, 2000);</script>";
                    //     $this->desenhaCadProduto('');
                    // }

                }
                break;
            case 'incluiReparo':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    $msg = $this->incluiProdutoReparo($this->getProdutoReparo(), $this->getProdutoIdReparo(), $this->getQuantReparo('B'));
                    $this->alteraProduto();
                    $this->produto();
                    $this->m_submenu = 'alterar';
                    if ($msg) {
                        $msgRetorno = 'Item adicionado ao reparo!';
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            width: 510,
                            text: '" . $msgRetorno . ".',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                    } else {
                        $msgRetorno = 'Erro ao adicionar o reparo!';
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                    }
                    $this->desenhaCadProduto('');
                }
                break;
            case 'excluiReparo':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'E')) {
                    $this->m_submenu = 'alterar';
                    $msg = $this->excluiProdutoReparo($this->getIdReparo());
                    $this->alteraProduto();
                    $this->produto();
                    if ($msg) {
                        $msgRetorno = 'Item excluído!';
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '" . $msgRetorno . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                    } else {
                        $msgRetorno = 'Erro ao excluir item!';
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 510,
                            text: '" . $msgRetorno . ".',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                    }
                    $this->desenhaCadProduto('');
                }
                break;
            case 'incluiequivalencia':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    $this->incluiProdutoEquivalencia();
                    $this->alteraProduto();
                    $this->produto();
                    $this->m_submenu = 'alterar';
                    $this->desenhaCadProduto($msg, 'alerta');
                }
                break;
            case 'excluiequivalencia':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'E')) {
                    $this->m_submenu = 'alterar';
                    $msg = $this->excluiProdutoEquivalencia();
                    $this->alteraProduto();
                    $this->produto();
                    $this->desenhaCadProduto($msg, 'alerta');
                }
                break;
            case 'incluiequivalenciaPesquisa':
                $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $this->setCodEquivalente($parmPost['codFabricanteNfe']);
                $this->setContaEquiv($parmPost['pessoa']);
                $this->setId($parmPost['codProduto']);
                $this->incluiProdutoEquivalencia();
                $this->mostraProduto('');
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'A')) {
                    $this->mostraProduto($this->alteraProduto());
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'E')) {
                    $this->mostraProduto($this->excluiProduto());
                }
                break;
            case 'quant':
                $classProdutoQtde = new c_produto_estoque();
                $quant[] = array(
                    'saldo'    => $classProdutoQtde->select_quantidade_empresa(
                        $this->getId(),
                        $this->m_empresacentrocusto,
                        ''
                    ),
                );

                // echo( json_encode( $quant ) );
                break;
            case 'ajustaestoque':
                $quant = str_replace('.', '', $this->m_quantNova);
                $quant = str_replace(',', '.', $quant);

                $ultimaCompra = str_replace('.', '', $this->getCustoCompra());
                $ultimaCompra = str_replace(',', '.', $ultimaCompra);
                $this->setCustoCompra($ultimaCompra);

                $custoMedio = str_replace('.', '', $this->getCustoMedio());
                $custoMedio = str_replace(',', '.', $custoMedio);
                $this->setCustoMedio($custoMedio);

                $custoReposicao = str_replace('.', '', $this->getCustoReposicao());
                $custoReposicao = str_replace(',', '.', $custoReposicao);
                $this->setCustoReposicao($custoReposicao);

                $precoInformado = str_replace('.', '', $this->getPrecoInformado());
                $precoInformado = str_replace(',', '.', $precoInformado);
                $this->setPrecoInformado($precoInformado);

                $precoMinimo = str_replace('.', '', $this->getPrecoMinimo());
                $precoMinimo = str_replace(',', '.', $precoMinimo);
                $this->setPrecoMinimo($precoMinimo);

                $venda = str_replace('.', '', $this->getVenda());
                $venda = str_replace(',', '.', $venda);
                $this->setVenda($venda);

                if (abs($quant) > 0) {
                    $this->insereQuant($this->m_quantNova);
                    $msg = 'Quantidade estoque ajustada !!';
                    $this->desenhaCadProduto($msg, 'alerta');
                } else {
                    $msg = 'Quantidade inválida !!';
                    $this->desenhaCadProduto($msg, 'alerta');
                }

                break;
            case 'pesquisaCodigo':
                $result = $this->select_produto_cod_fabricante($this->m_pesquisaCodigo);
                if ($result) {
                    if ($result[0]['ORIGEM'] == 'EQUIVALENTE') {
                        $return = $result[0]['DESCRICAO'] . " (CÓDIGO EQUIVALENTE)";
                    } else {
                        $return = $result[0]['DESCRICAO'];
                    }
                } else {
                    $return = 'false';
                }
                header('Content-type: application/json');
                echo json_encode($return, JSON_FORCE_OBJECT);
                die;
            case 'pesquisaClienteAjax':
                $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $termAjax = (isset($parmPost['term']) ? $parmPost['term'] : '');

                $objConta = new c_conta();
                $resultPesq = $objConta->select_pessoa_letra($termAjax);
                for ($i = 0; $i < count($resultPesq); $i++) {
                    $clienteResult[$i]['id'] = trim($resultPesq[$i]['CLIENTE']);
                    $clienteResult[$i]['text'] = trim($resultPesq[$i]['NOME']);
                }

                echo json_encode($clienteResult);

                break;
            case 'pesquisaKitReparo':
                $exkitReparo = explode("|", $this->m_kitReparo);

                for ($i = 0; $i < count($exkitReparo); $i++) {
                    $resultPesq = c_produto_estoque::produtoQtde($exkitReparo[$i], $this->m_empresacentrocusto);
                    if ($resultPesq[0]["QUANTIDADE"] == "0.0000") {
                    }
                }

                echo json_encode($resultPesq);

                break;
            case 'pesquisaProdutoComboKit':
                $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $termAjax = (isset($parmPost['term']) ? $parmPost['term'] : '');

                $searchProds = $this->select_produto_letra_combo($termAjax) ?? [];

                for ($i = 0; $i < count($searchProds); $i++) {
                    $prodResult[$i]['id'] = trim($searchProds[$i]['CODIGO']);
                    $string = trim($searchProds[$i]['CODIGO']) . ' | ' . trim($searchProds[$i]['DESCRICAO']);
                    if ($searchProds[$i]['CODEQUIVALENTE'] !== '' and $searchProds[$i]['CODEQUIVALENTE'] !== null) {
                        $string = $string . " | EQUIVALENTE(" . $searchProds[$i]['CODEQUIVALENTE'] . ")";
                    }
                    $prodResult[$i]['text'] = $string;
                }

                echo json_encode($prodResult);

                break;
            case 'adicionaNovoItemKit':
                $quantFormat = str_replace('.', '', $this->m_quantNova);
                $quantFormat = str_replace(',', '.', $quantFormat);
                $resultInsert = $this->incluiProdutoReparo($this->m_kitReparo, $this->m_produtoId, $quantFormat);

                if ($resultInsert) {
                    $returnAjax = [
                        'success' => true,
                        'mensagem' => 'Item adicionado com sucesso!'
                    ];
                } else {
                    $returnAjax = [
                        'success' => false,
                        'mensagem' => 'Falha ao adicionar item, entre em contato com o suporte!'
                    ];
                }
                echo json_encode($returnAjax);
                break;

            case 'excluiItemReparo':

                if ($this->m_produtoId == '' and $this->m_produtoId == null) {
                    $returnAjax = [
                        'success' => false,
                        'mensagem' => 'Falha ao excluir item, entre em contato com o suporte!'
                    ];
                    echo json_encode($returnAjax);
                    die;
                }

                $resultDelete = $this->excluiProdutoReparo($this->m_produtoId);

                if ($resultDelete) {
                    $returnAjax = [
                        'success' => true,
                        'mensagem' => 'Item excluído com sucesso!'
                    ];
                } else {
                    $returnAjax = [
                        'success' => false,
                        'mensagem' => 'Falha ao excluir item, entre em contato com o suporte!'
                    ];
                }
                echo json_encode($returnAjax);
                break;
            default:
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'C')) {
                    $this->mostraProduto('');
                }
        } //switch
    }

    // fim controle
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function desenhaCadProduto($mensagem = NULL, $tipoMsg = NULL)
    {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('loc', $this->m_loc);
        $this->smarty->assign('ns', $this->m_ns);
        $this->smarty->assign('idNF', $this->m_idNF);

        $this->smarty->assign('id', $this->getId());

        //verifica se origem e da tela de cadastro de pedido e recebe os param de letra
        if ($this->m_par[0] === 'registerProd') {
            $this->smarty->assign('descricaoDetalhada', $this->m_par[3]);
            $this->smarty->assign('desc', '"' . $this->m_par[3] . '"');
        } else {
            $this->smarty->assign('desc', "'" . $this->getDesc() . "'");
            $this->smarty->assign('descricaoDetalhada', $this->getDescricaoDetalhada());
        }

        //verifica se origem e da tela de cadastro de pedido e recebe os param de letra
        if ($this->m_par[0] === 'registerProd') {
            $this->smarty->assign('uni', '"' . $this->m_par[4] . '"');
        } else {
            $this->smarty->assign('uni', $this->getUni());
        }
        $this->smarty->assign('uniFracionada', $this->getUniFracionada());

        // ANP
        $consulta = new c_banco();
        $sql = "select anp, descricao from est_anp ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        $anp_ids[0] = ' ';
        $anp_names[0] = ' Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $anp_ids[$i + 1] = $result[$i]['ANP'];
            $anp_names[$i + 1] = $result[$i]['ANP'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('anp_ids', $anp_ids);
        $this->smarty->assign('anp_names', $anp_names);
        $this->smarty->assign('anp', $this->getAnp());

        // GRUPO
        $consulta = new c_banco();
        $sql = "select grupo as id, descricao from est_grupo where nivel >= 1 order by descricao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i] = $result[$i]['ID'];
            $grupo_names[$i] = $result[$i]['DESCRICAO'] . " - " . $result[$i]['ID'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);

        $this->smarty->assign('grupo', $this->getGrupo());

        // MARCA
        $consulta = new c_banco();
        $sql = "SELECT marca as id, descricao FROM EST_MARCA ORDER BY descricao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        for ($i = 0; $i < count($result); $i++) {
            $marca_ids[$i] = $result[$i]['ID'];
            $marca_names[$i] = $result[$i]['DESCRICAO'] . " - " . $result[$i]['ID'];
        }
        $this->smarty->assign('marca_ids', $marca_ids);
        $this->smarty->assign('marca_names', $marca_names);

        $this->smarty->assign('marca', $this->getMarca());

        $this->smarty->assign('pessoa', $this->getFabricante());
        $this->smarty->assign('pessoaNome', $this->getFabricanteNome());

        //verifica se origem e da tela de cadastro de pedido e recebe os param de letra
        if ($this->m_par[0] === 'registerProd') {
            $this->smarty->assign('codFabricante', "'" . $this->m_par[1] . "'");
        } else {
            $this->smarty->assign('codFabricante', "'" . $this->getCodFabricante() . "'");
        }

        //verifica se origem e da tela de cadastro de pedido e recebe os param de letra
        if ($this->m_par[0] === 'registerProd') {
            $this->smarty->assign('codBarras', "'" . $this->m_par[2] . "'");
        } else {
            $this->smarty->assign('codBarras', "'" . $this->getCodBarras() . "'");
        }

        $this->smarty->assign('codProdutoAnvisa', "'" . $this->getCodProdutoAnvisa() . "'");
        $this->smarty->assign('localizacao', "'" . $this->getLocalizacao() . "'");
        $this->smarty->assign('dataForaLinha', $this->getDataForaLinha('F'));

        // NCM
        $ncm = trim($this->getNcm());
        if ($ncm != '') {
            $objNcm = new c_ncm();
            $objNcm->setNcm($this->getNcm());
            if (!$objNcm->existeNcm()) {
                $objNcm->setDescricao($this->getNcm());
                $objNcm->incluiNcm();
            }
        }
        $consulta = new c_banco();
        $sql = "select ncm, descricao from est_ncm order by ncm asc";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $ncm_ids[0] = ' ';
        $ncm_names[0] = ' Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $ncm_ids[$i + 1] = $result[$i]['NCM'];
            $ncm_names[$i + 1] = $result[$i]['NCM'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('ncm_ids', $ncm_ids);
        $this->smarty->assign('ncm_names', $ncm_names);
        $this->smarty->assign('ncm', $this->getNcm());

        $this->smarty->assign('cest', $this->getCest());
        $this->smarty->assign('precoPromocao', $this->getPrecoPromocao('F'));
        $this->smarty->assign('inicioPromocao', $this->getInicioPromocao('F'));
        $this->smarty->assign('quantLimite', $this->getQuantLimite());
        $this->smarty->assign('fimPromocao', $this->getFimPromocao('F'));
        $this->smarty->assign('tipoPromocao', $this->getTipoPromocao());
        $this->smarty->assign('precoPromocao1', $this->getPrecoPromocao1('F'));
        $this->smarty->assign('inicioPromocao1', $this->getInicioPromocao1('F'));
        $this->smarty->assign('quantLimite1', $this->getQuantLimite1());
        $this->smarty->assign('fimPromocao1', $this->getFimPromocao1('F'));
        $this->smarty->assign('dataUltimaCompra', $this->getDataUltimaCompra('F'));
        $this->smarty->assign('quantUltimaCompra', $this->getQuantUltimaCompra());
        $this->smarty->assign('nfUltimaCompra', $this->getNfUltimaCompra());
        //$this->smarty->assign('precoBase', $this->getPrecoBase());
        $this->getPrecoInformado('F');
        $this->smarty->assign('precoInformado', $this->getPrecoInformado('F'));
        //$this->smarty->assign('percCalculo', $this->getPerCalculo('F'));
        $this->smarty->assign('form_old', $this->m_form_old);

        //TIPO PROMOCAO #############
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TIPOPROMOCAO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $tipoPromocao_ids[0] = '';
        $tipoPromocao_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $tipoPromocao_ids[$i + 1] = $result[$i]['ID'];
            $tipoPromocao_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipoPromocao_ids', $tipoPromocao_ids);
        $this->smarty->assign('tipoPromocao_names', $tipoPromocao_names);
        $this->smarty->assign('tipoPromocao_id', $this->getTipoPromocao());

        //PRECO BASE #############
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='PRECOBASE')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $precoBase_ids[0] = '';
        $precoBase_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $precoBase_ids[$i + 1] = $result[$i]['ID'];
            $precoBase_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('precoBase_ids', $precoBase_ids);
        $this->smarty->assign('precoBase_names', $precoBase_names);
        //$this->smarty->assign('precoBase_id', $this->getPrecoBase());


        // ORIGEM MERCADORIA
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='OrigemMercadoria')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $origem_ids[0] = '';
        $origem_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $origem_ids[$i + 1] = $result[$i]['ID'];
            $origem_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('origem_ids', $origem_ids);
        $this->smarty->assign('origem_names', $origem_names);

        //verifica se origem e da tela de cadastro de pedido e recebe os param de letra
        if ($this->m_par[0] === 'registerProd') {
            $this->smarty->assign('origem', '0');
        } else {
            $this->smarty->assign('origem', $this->getOrigem());
        }

        // SIM / NAO
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='boolean')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $boolean_ids[$i] = $result[$i]['ID'];
            $boolean_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('boolean_ids', $boolean_ids);
        $this->smarty->assign('boolean_names', $boolean_names);
        $this->smarty->assign('boolean', $this->getUniFracionada());

        // TRIBUTO ICMS
        //=== consulta regime tributário da empresa.

        $consulta = new c_banco();
        //$consulta->setTab('AMB_EMPRESA');
        //$regime = $consulta->getField('REGIMETRIBUTARIO', 'empresa=' . $this->m_empresaid);
            //if ($regime == 3):
                //$sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TributacaoIcms')";
            //else:
                    // $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='csosn')";
            //endif;
        // Busca tanto TributacaoIcms quanto csosn
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TributacaoIcms')";
        $consulta->exec_sql($sql);
        $resultTribIcms = $consulta->resultado ?? [];
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='csosn')";
        $consulta->exec_sql($sql);
        $resultCsosn = $consulta->resultado ?? [];
        
        $consulta->close_connection();
        
        // Combina os resultados
        $result = array_merge($resultTribIcms, $resultCsosn);
        
        $tribIcms_ids[0] = '';
        $tribIcms_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $tribIcms_ids[$i + 1] = $result[$i]['ID'];
            $tribIcms_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tribIcms_ids', $tribIcms_ids);
        $this->smarty->assign('tribIcms_names', $tribIcms_names);

        //verifica se origem e da tela de cadastro de pedido e recebe os param de letra
        if ($this->m_par[0] === 'registerProd') {
            $this->smarty->assign('tribIcms', '102');
        } else {
            $this->smarty->assign('tribIcms', $this->getTribIcms());
        }

        // MOEDA
        $consulta = new c_banco();
        $sql = "select moeda as id, nome as descricao from fin_moeda";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $moeda_ids[$i] = $result[$i]['ID'];
            $moeda_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('moeda_ids', $moeda_ids);
        $this->smarty->assign('moeda_names', $moeda_names);
        $this->smarty->assign('moeda', $this->getMoeda());

        //verifica se origem e da tela de cadastro de pedido e recebe os param de letra
        if ($this->m_par[0] === 'registerProd') {
            $this->setVenda($this->m_par[5]);
            $this->smarty->assign('venda', $this->getVenda('F'));
        } else {
            $this->smarty->assign('venda', $this->getVenda('F'));
        }

        $this->smarty->assign('custoMedio', $this->getCustoMedio('F'));
        $this->smarty->assign('custoCompra', $this->getCustoCompra('F'));
        $this->smarty->assign('custoReposicao', $this->getCustoReposicao('F'));
        $this->smarty->assign('quantMinima', $this->getQuantMinima('F'));
        $this->smarty->assign('quantMaxima', $this->getQuantMaxima('F'));
        $this->smarty->assign('obs', $this->getObs());


        $quantAtual = 0;
        $quantReservada = 0;
        if (($this->m_submenu == 'alterar') || ($this->m_submenu == 'ajustaestoque')) {

            $classProdutoQtde = new c_produto_estoque();
            $produtoQuant = $classProdutoQtde->produtoQtde($this->getId(), $this->m_empresacentrocusto) ?? [];
            switch (count($produtoQuant)) {
                case 1:
                    $quantAtual = $produtoQuant[0]['QUANTIDADE'];
                    $quantReservada = 0;
                    break;
                case 2:
                    $quantAtual = $produtoQuant[0]['QUANTIDADE'];
                    $quantReservada = $produtoQuant[1]['QUANTIDADE'];
                    break;
                case 3:
                    $quantAtual = $produtoQuant[0]['QUANTIDADE'];
                    $quantReservada = $produtoQuant[1]['QUANTIDADE'] + $produtoQuant[2]['QUANTIDADE'];
                    break;
                case 4:
                    $quantAtual = $produtoQuant[0]['QUANTIDADE'];
                    $quantReservada = $produtoQuant[1]['QUANTIDADE'] + $produtoQuant[2]['QUANTIDADE'] + $produtoQuant[3]['QUANTIDADE'];
                    break;
                default:
                    $quantAtual = 0;
                    $quantReservada = 0;
            }
        }
        //$this->smarty->assign('quantAtual', $quantAtual);   old
        //formatar QtdeAtual
        $quantTotal = $quantAtual + $quantReservada;
        $quantAtual = number_format($quantAtual, 2, ',', '.');
        $quantReservada = number_format($quantReservada, 2, ',', '.');
        $quantTotal = number_format($quantTotal, 2, ',', '.');
        $this->smarty->assign('quantAtual', $quantAtual);
        $this->smarty->assign('quantReservada', $quantReservada);
        $this->smarty->assign('quantTotal', $quantTotal);
        $this->smarty->assign('dateChange', $this->getDateChange());
        $this->smarty->assign('peso', $this->getPeso('F'));
        $this->smarty->assign('precoMinimo', $this->getPrecoMinimo('F'));

        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        /*
        $origem = $this->getOrigem();
        if ( $origem != "") {
            $this->smarty->assign('origem', $origem);
        } else {
            $origem = $parametros->getField("ORIGEM", "FILIAL=".$this->m_empresacentrocusto);
            $this->smarty->assign('origem', $origem);
        }
        
        $tribicms = $this->getTribIcms();
        if ( $tribicms != "") {
            $this->smarty->assign('tribIcms', $tribicms);
        } else {
            $tribicms = $parametros->getField("TRIBICMS", "FILIAL=".$this->m_empresacentrocusto);
            $this->smarty->assign('tribIcms', $tribicms);
        }
        */
        $base = $this->getPrecoBase();
        if ($base != "") {
            $this->smarty->assign('precoBase_id', $base);
        } else {
            $precoBase = $parametros->getField("PRECOBASE", "FILIAL=" . $this->m_empresacentrocusto);
            $this->smarty->assign('precoBase_id', $precoBase);
        }

        $perc = $this->getPerCalculo('F');
        if ($perc <= 0) {
            $perCalculo = $parametros->getField("PERCALCULO", "FILIAL=" . $this->m_empresacentrocusto);
            $this->setPercCalculo($perCalculo);
            $this->smarty->assign('percCalculo', $this->getPerCalculo('F'));
        } else {
            $this->smarty->assign('percCalculo', $perc);
        }

        // TABELA
        $tabela = $this->select_produto_tabela();
        $this->smarty->assign('tabela', $tabela);

        // CODIGO EQUIVALENTE
        $equiv = $this->select_produto_equivalencia();
        $this->smarty->assign('equiv', $equiv);

        // REPAROS
        $reparo = $this->selectProdutoReparo($this->getId());
        $this->smarty->assign('reparo', $reparo);

        $this->smarty->assign('codEquivalente', $this->getCodEquivalente());

        //BUSCA PRODUTO REPARO
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PROD_REPAROS"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PROD_REPAROS"] == "true"):
            $ajax_request = 'true';

            $prod = new c_produto();

            if ($this->reparoCodFabricante !== '' & $this->reparoCodFabricante !== null) {
                $resultProd = $prod->select_produto_cod_fabricante($this->reparoCodFabricante);
            } else {
                $prod->setId($this->reparoCodProduto);
                $resultProd = $prod->select_produto();
            }

            if ($resultProd !== null) {
                $this->smarty->assign('prodExiste', 'yes');
                $this->smarty->assign('reparoCodProduto', $resultProd[0]['CODIGO']);
                $this->smarty->assign('reparoCodFabricante', $resultProd[0]['CODFABRICANTE']);
                $this->smarty->assign('reparoProdDesc', $resultProd[0]['DESCRICAO']);
            } else {
                $this->smarty->assign('prodExiste', 'no');
            }

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);

        endif;

        $this->smarty->display('produto_cadastro.tpl');
    }

    //fim desenhaCadproduto
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraProduto($mensagem, $tipoMsg = '')
    {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('active01', 'active');
        $this->smarty->assign('activeTab01', 'active in');
        $this->smarty->assign('pathCliente', ADMhttpCliente);

        if ((isset($this->m_letra)) && $this->m_letra != '') {
            $parmPost['codigo'] == '' ? $parmPost['codigo'] = $this->m_par[6] : $parmPost['codigo'];
            $produto = $this->select_produto_letra($this->m_letra, $parmPost['codigo']);
            $this->m_par[6] != '' ? $this->m_par[0] = $produto[0]['DESCRICAO'] : '';
            $equi = $this->select_equivalente_letra($this->m_letra, $parmPost['codigo']);
            $this->smarty->assign('equi', $equi);

            // Operador de coalescencia para php 8.3 
            $produto = is_array($produto) ? $produto : [];

            $numProduto = count($produto);
            if (($parmPost['codigo'] != '') or ($numProduto == 1)) {
                if ($numProduto == 1) {
                    $parmPost['codigo'] = $produto[0]['CODIGO'];
                    $this->smarty->assign('id', $produto[0]['CODIGO']);
                }


                //DESATIVADO POIS A CONSULTA SERA REALIZADO POR AJAX (Funcao ativaAba())
                //$param = '|||'.$parmPost['codigo'].'|||'.$this->m_empresacentrocusto;
                // $consulta = new c_estoque_rel;
                //BUSCA NOTAS
                //$notas = c_estoque_rel::select_consulta_produto_preco($param);
                //$this->smarty->assign('notas', $notas);

                //BUSCA PEDIDOS
                // $par = explode("|", $param);

                // $pedido = $this->buscaPedidoPedido($par[3]);
                // if($pedido != null){
                //     $this->smarty->assign('pedido', $pedido);
                //     //variavel que habilita tabela ou msg que nao existe
                //     $this->smarty->assign('existePedido', 'yes');
                // }else{
                //     $this->smarty->assign('existePedido', 'no');
                // }
                //BUSCA COTACAO
                // $cotacao = $this->buscaPedidoCotacao($par[3]);
                // if($cotacao != null){
                //     $this->smarty->assign('cotacao', $cotacao);
                //     //variavel que habilita tabela ou msg que nao existe
                //     $this->smarty->assign('existeCotacao', 'yes');
                // }else{
                //     $this->smarty->assign('existeCotacao', 'no');
                // }

                //BUSCA REPARO
                // $reparo = $this->selectProdutoReparo($par[3]);
                // if($reparo != null){
                //     $this->smarty->assign('reparo', $reparo);
                //     //variavel que habilita tabela ou msg que nao existe
                //     $this->smarty->assign('existeReparo', 'yes');
                // }else{
                //     $this->smarty->assign('existeReparo', 'no');
                // }

            }
        }
        $parmPost['codFabricante'] == '' ? $parmPost['codFabricante'] = $this->m_par[2] : $parmPost['codFabricante'];

        if ($parmPost['codFabricante'] != '') {
            $tabela = $this->select_importacao_tabela($parmPost['codFabricante']);
            $this->smarty->assign('tabela', $tabela);

            if (is_array($tabela) && count($tabela) > 0) {
                $this->smarty->assign('active01', '');
                $this->smarty->assign('activeTab01', '');
                $this->smarty->assign('active03', 'active');
                $this->smarty->assign('activeTab03', 'active in');
            }
        }



        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('origem', $this->m_origem);
        $this->smarty->assign('from', $this->from);

        isset($parmPost['pessoa']) ? $this->smarty->assign('pessoa', $parmPost['pessoa']) : '';
        isset($parmPost['produtoNomeNfe']) ? $this->smarty->assign('produtoNomeNfe', $parmPost['produtoNomeNfe']) : $this->smarty->assign('produtoNomeNfe', $parmPost['produtoNome']);
        isset($parmPost['codFabricanteNfe']) ? $this->smarty->assign('codFabricanteNfe', $parmPost['codFabricanteNfe']) : $this->smarty->assign('codFabricanteNfe', $parmPost['codFabricante']);
        //        isset($parmPost['produtoNome']) ? $this->smarty->assign('produtoNome', $parmPost['produtoNome']) : $this->smarty->assign('produtoNome', $this->m_par[0]);
        //        isset($parmPost['codFabricante']) ? $this->smarty->assign('codFabricante', $parmPost['codFabricante']) : $this->smarty->assign('codFabricante', $this->m_par[2]);
        $this->smarty->assign('produtoNome', $this->m_par[0]);
        $this->smarty->assign('codFabricante', $this->m_par[2]);
        $this->smarty->assign('localizacao', $this->m_par[3]);
        $this->smarty->assign('quant', $this->m_par[4]);

        // tipo de Select
        //**** estoque ****
        $estoque_ids[0] = 'T';
        $estoque_names[0] = 'Todos';
        $estoque_ids[1] = 'S';
        $estoque_names[1] = 'Com Saldo';
        $estoque_ids[2] = 'N';
        $estoque_names[2] = 'Sem Saldo';
        $this->smarty->assign('estoque_ids', $estoque_ids);
        $this->smarty->assign('estoque_names', $estoque_names);
        if ($this->m_par[4] == '') {
            $this->smarty->assign('estoque_id', 'S');
        } else {
            $this->smarty->assign('estoque_id', $this->m_par[4]);
        }
        //****** fim estoque ******


        for ($i = 'A'; $i < 'Z'; $i++) {
            $arrayLetra[$i] = $i;
        }
        $this->smarty->assign('arrayLetra', $arrayLetra);

        // GRUPO
        $consulta = new c_banco();
        $sql = "select grupo id, descricao from est_grupo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? $consulta->resultado ?? [];

        $grupo_ids[0] = '';
        $grupo_names[0] = 'Selecione Grupo';
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + 1] = $result[$i]['ID'];
            $grupo_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);
        if ($this->m_par[1] == "")
            $this->smarty->assign('grupo_id', 'Todos');
        else
            $this->smarty->assign('grupo_id', $this->m_par[1]);

        //condicao para buscar estoque para as divs
        if ($this->m_function == 'updateDivs' and $this->m_divId == 'divEstoque') {
            $produto[$i]['CODIGO'] = $this->m_produtoId;
        }

        $resultProduto = [];
        $p = 0;
        $classProdutoQtde = new c_produto_estoque();

        // Operador de coalescencia para php 8.3 
        $produto = $produto ?? [];

        if (is_array($produto) && count($produto) > 0) {
            for ($i = 0; $i < count($produto); $i++) {

                $produtoQuant = $classProdutoQtde->produtoQtde($produto[$i]['CODIGO'], $this->m_empresacentrocusto) ?? [];

                $produto[$i]['ESTOQUE'] = 0;

                $produto[$i]['RESERVA'] = 0;

                for ($q = 0; $q < count($produtoQuant); $q++) {
                    if ($produtoQuant[$q]['STATUS'] == 0):
                        $produto[$i]['ESTOQUE'] = $produtoQuant[$q]['QUANTIDADE'];
                    else:
                        $produto[$i]['RESERVA'] = $produtoQuant[$q]['QUANTIDADE'];
                    endif;
                    //$produto[$i]['CENTROCUSTO'] = $produtoQuant[$q]['CCUSTO'];
                }
                $resultProduto[$p] = $produto[$i];
                $p++;
            }
        } else {
            $resultProduto = [];
        }
        //ESTOQUE POR CENTRO CUSTO
        $consulta = new c_banco();
        $sql = "select centroCusto from FIN_CENTRO_CUSTO WHERE NIVEL = '1'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $centroCusto = $consulta->resultado ?? $consulta->resultado ?? [];

        for ($i = 0; $i < count($produto); $i++) {
            for ($k = 0; $k < count($centroCusto); $k++) {

                $produtoQuant = $classProdutoQtde->produtoQtde($produto[$i]['CODIGO'], $centroCusto[$k]['CENTROCUSTO']) ?? [];
                $produtoEst[$i]['ESTOQUE'] = 0;

                $produtoEst[$i]['RESERVA'] = 0;

                for ($q = 0; $q < count($produtoQuant); $q++) {
                    if ($produtoQuant[$q]['STATUS'] == 0) {
                        $produtoEst[$i]['ESTOQUE'] = $produtoQuant[$q]['QUANTIDADE'];
                    } else {
                        $produtoEst[$i]['RESERVA'] = $produtoQuant[$q]['QUANTIDADE'];
                    }
                    $produtoEst[$i]['CENTROCUSTO'] = $produtoQuant[$q]['CCUSTO'];
                }
                $resultEstoque[$k] = $produtoEst[$i];
            }
        }
        if ($parmPost['codigo'] != '' || $parmPost['codFabricante'] != '') {
            $this->smarty->assign('estoque', $resultEstoque);
        }

        // MARCA
        $consulta = new c_banco();
        $sql = "SELECT marca as ID, descricao FROM EST_MARCA";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = is_array($consulta->resultado) ? $consulta->resultado : [];
        $marca_ids[0] = '';
        $marca_names[0] = 'Selecione Marca';
        for ($i = 0; $i < count($result); $i++) {
            $marca_ids[$i + 1] = $result[$i]['ID'];
            $marca_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('marca_ids', $marca_ids);
        $this->smarty->assign('marca_names', $marca_names);
        if ($this->m_par[1] == "")
            $this->smarty->assign('marca_id', 'Todos');
        else
            $this->smarty->assign('marca_id', $this->m_par[1]);

        $this->smarty->assign('imgBtn', true);

        /*            $produtoQuant = $classProdutoQtde->produtoQtde($produto[$i]['CODIGO'], $this->m_empresacentrocusto);
            switch (count($produtoQuant)){
                case 0://não localizou nada
                    $produto[$i]['ESTOQUE'] = 0;
                    $produto[$i]['RESERVA'] = 0;
                    if ($this->m_par[4] == 'F'){
                        $resultProduto[$p] = $produto[$i];
                        $p++;
                    }
                break;
                case 1://DISPONIVEL somente estoque
                    $produto[$i]['ESTOQUE'] = $produtoQuant[0]['QUANTIDADE'];
                    $produto[$i]['RESERVA'] = 0;
                    $resultProduto[$p] = $produto[$i];
                    $p++;
                break;
                default : // disponivel estoque com reservas
                    $produto[$i]['ESTOQUE'] = $produtoQuant[0]['QUANTIDADE'];
                    $produto[$i]['RESERVA'] = $produtoQuant[1]['QUANTIDADE']+$produtoQuant[2]['QUANTIDADE']+$produtoQuant[3]['QUANTIDADE'];
                    $resultProduto[$p] = $produto[$i];
                    $p++;
                break;
            }
        }//for
 * 
 * 
 */
        //validação Botão imagem [padrão]
        //$parametros = new c_banco;
        //$parametros->setTab("EST_PARAMETRO");
        //$imgBtn = $parametros->getField("IMGBTN", "FILIAL=".$this->m_empresacentrocusto);
        //if ($imgBtn == 'S'){ 
        $this->smarty->assign('imgBtn', true);
        // }

        //checkBox produto_pesquisar 
        if (count($resultProduto) == 1) {
            $this->pedidoChecked = 'true';
        }
        $this->smarty->assign('pedidoChecked', $this->pedidoChecked);

        $this->smarty->assign('from', $this->from);
        $this->smarty->assign('acao', $this->acao);  // pode ser vazio ou alterar 
        $this->smarty->assign('lanc', $resultProduto);
        $this->smarty->assign('quantArray', count($resultProduto));

        if ($this->from == 'atendimento' || $this->from == 'atendimento_new') {
            if ($this->tipoCategoriaAtendimento == '') {
                $consulta = new c_banco;
                $consulta->setTab("CAT_TIPO");
                $tipoCobPreco = $consulta->getField("COBTIPOPRECO", "ID=" . $this->idTipoAtendimento);
                $consulta->close_connection();

                $this->tipoCategoriaAtendimento = $tipoCobPreco;
            }
            $this->smarty->assign('idTipoAtendimento', $this->idTipoAtendimento);
            $this->smarty->assign('tipoCategoriaAtendimento', $this->tipoCategoriaAtendimento);
        }


        //condicao que fara as buscas do ajax nas abas
        if ($this->m_function == 'updateDivs') {
            //fluxo busca notas
            $param = '|||' . $this->m_produtoId . '|||' . $this->m_empresacentrocusto;

            $notas = c_estoque_rel::select_consulta_produto_preco($param) ?? [];

            $this->smarty->assign('notas', $notas);

            if (count($notas) > 0) {
                $this->smarty->assign('existeNota', 'yes');
            } else {
                $this->smarty->assign('existeNota', 'no');
            }

            //condicao para impressao, sera uitlizado apenas no ajax
            $this->smarty->assign('lanc', 1);

            //fluxo busca cotacoes
            $cotacao = $this->buscaPedidoCotacao($this->m_produtoId) ?? [];

            if ($cotacao != null) {
                $this->smarty->assign('cotacao', $cotacao);
                //variavel que habilita tabela ou msg que nao existe
                $this->smarty->assign('existeCotacao', 'yes');
            } else {
                $this->smarty->assign('existeCotacao', 'no');
            }

            //fluxo de pedidos
            $pedido = $this->buscaPedidoPedido($this->m_produtoId) ?? [];
            if ($pedido != null) {
                $this->smarty->assign('pedido', $pedido);
                //variavel que habilita tabela ou msg que nao existe
                $this->smarty->assign('existePedido', 'yes');
            } else {
                $this->smarty->assign('existePedido', 'no');
            }

            $this->smarty->assign('estoque', $resultEstoque);
            $this->smarty->assign('existeEstoque', 'yes');

            //BUSCA REPARO
            $reparo = $this->selectProdutoReparo($this->m_produtoId) ?? [];


            for ($i = 0; $i < count($reparo); $i++) {
                $produtoQuant = $classProdutoQtde->produtoQtde($reparo[$i]['PRODUTO_ID'], $this->m_empresacentrocusto);
                $reparo[$i]['ESTOQUE'] = $produtoQuant[0]['QUANTIDADE'];
            }


            if (!empty($reparo)) {

                $produto = $this->select_produto_letra(null, $this->m_produtoId);

                $consultaProduto = c_produto::quant_atual($this->m_produtoId);
                $kitCodDesc = $produto[0]['CODIGO'] . " - " . $produto[0]['DESCRICAO'];
                $this->smarty->assign('kitCodDesc', $kitCodDesc);

                $this->smarty->assign('reparo', $reparo);
                //variavel que habilita tabela ou msg que nao existe
                $this->smarty->assign('existeReparo', 'yes');
            } else {
                $this->smarty->assign('existeReparo', 'no');
            }
        }

        switch ($this->m_opcao) {
            case "pesquisarpecas":
            case "pesquisarpecasmenu":
                // se o carrinho estiver vazio, seta nulo para testar no front
                if ($this->m_carrinho !== '' and $this->m_carrinho !== null) {

                    $this->smarty->assign('carrinho', $this->m_carrinho);

                    if ($this->m_idPedido !== '' and $this->m_idPedido !== null) {
                        $this->smarty->assign('idPedido', $this->m_idPedido);
                    }
                } else {
                    if ($_GET["idPedido"] !== '' and $_GET["idPedido"] !== null) {
                        $this->smarty->assign('idPedido', $_GET["idPedido"]);
                    } else {
                        $this->smarty->assign('idPedido', $this->m_idPedido);
                    }

                    $this->smarty->assign('carrinho', null);
                }

                $this->smarty->display('produto_pesquisar.tpl');
                break;
            case "pesquisarnfe":
                //$this->smarty->assign('opcao', 'pesquisar');
                $this->smarty->display('produto_pesquisar_nfe.tpl');
                break;
            case "pesquisar":
                // $this->smarty->assign('opcao', 'imprimir');
                if ($this->from == 'atendimento') {
                    $this->smarty->display('produto_pesquisar_atendimento.tpl');
                } elseif ($this->from == 'baixa_estoque') {
                    $this->smarty->display('produto_pesquisar.tpl');
                } elseif ($this->from == 'uni_produto_permanece') {
                    $this->smarty->display('produto_pesquisar_uni.tpl');
                } else {
                    $this->smarty->display('produto_pesquisar_consultas.tpl');
                }
                break;
            default:
                $this->smarty->display('produto_mostra.tpl');
        }

        /*        if ($this->m_opcao=="pesquisar"):
            $this->smarty->display('produto_pesquisar.tpl');
        else:
            $this->smarty->display('produto_mostra.tpl');
        endif;
 * 
 */
    }
    // fim mostraProdutos
    //----------------------------------------------------------------
    function desenhaCadastroImagemProduto($mensagem = NULL, $tipoMsg = null)
    {

        $lanc = $this->select_produto_imagem() ?? [];
        $produto = $this->select_produto_letra(null, $this->getId());

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('descricaoProduto', $produto[0]['DESCRICAO']);
        $this->smarty->assign('titulo', "'" . $this->m_titulo . "'");
        $this->smarty->assign('totalImg', count($lanc));

        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('produto_mostra_imagem.tpl');
    }
    //fim mostraProdutos
    //-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
//$produto = new p_produto(isset($parmPost['id']) ? $parmPost['id'] : '''submenu'], $_POST['letra'], $_POST['quant'], $_POST['acao'], $_REQUEST['pesquisa'], $_POST['opcao'], $_POST['loc'], $_POST['ns'], $_POST['idNF']);
$produto = new p_produto();

$produto->controle();
