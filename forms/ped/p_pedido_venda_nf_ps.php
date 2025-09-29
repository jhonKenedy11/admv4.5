<?php

/**
 * @package   astec
 * @name      p_pedido_venda_nf
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      30/10/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../forms/est/p_nfephp_40.php");
require_once($dir . "/../../forms/est/p_nfephp_imprime_danfe.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/crm/c_conta.php");

require_once($dir . "/../../class/ped/c_pedido_ps.php");
if($_POST['submenu'] == 'cadastraFinanceiro'){
    require_once($dir . "/../../forms/ped/p_pedido_venda_gerente_novo.php");
}

//Class P_situacao
Class p_pedido_venda_nf_ps extends c_pedidoVendaNf {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_opcao = NULL;
    private $m_msg = NULL;
    private $parmPost = NULL;
    public $smarty = NULL;
    public $descCondPgto = NULL;

    // field NF
    public $modFrete = NULL;
    public $transportador = NULL;
    public $volume = NULL;
    public $volEspecie = NULL;
    public $volMarca = NULL;
    public $volPesoLiq = NULL;
    public $volPesoBruto = NULL;
    public $obs = NULL;
    public $nfAberto = false;
    
    public  $objNotaFiscal = NULL;
    public  $objProduto = NULL;
    public  $objProdutoEstoque = NULL;
    public  $objNfProduto = NULL;
    public  $objFinanceiro = NULL;

    public  $arrParcelas = NULL;
    public  $arrParamFin = NULL;
    public  $arrProduto = NULL;
    public  $arrProdutoEstoqueReserva = NULL;
    public  $arrItemPedido = NULL;

    private  $alteraCondPgto = NULL;
    private  $formNf = NULL;

    private  $numNf = NULL;


    
    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($id=null, $submenu=null) {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
//        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

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
        $this->m_submenu = $this->parmPost['submenu'];
        $this->m_opcao = $this->parmPost['opcao'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // metodo SET dos dados do FORM para o TABLE
        if (is_null($id)):
            $this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : '');
        else:    
            $this->setId($id);
            $this->m_submenu = $submenu;
        endif;
        $this->setCliente(isset($this->parmPost['cliente']) ? $this->parmPost['cliente'] : '');
        $this->setIdNatop(isset($this->parmPost['idNatop']) ? $this->parmPost['idNatop'] : '');
        $this->setSerie(isset($this->parmPost['serie']) ? $this->parmPost['serie'] : '');
        $this->setCondPg(isset($this->parmPost['condPgto']) ? $this->parmPost['condPgto'] : 0);
        $this->setGenero(isset($this->parmPost['genero']) ? $this->parmPost['genero'] : '');
        $this->setContaDeposito(isset($this->parmPost['conta']) ? $this->parmPost['conta'] : '');
        $this->setTotal(isset($this->parmPost['total']) ? $this->parmPost['total'] : '');
        $this->setCentroCusto(isset($this->parmPost['centroCusto']) ? $this->parmPost['centroCusto'] : '');

        $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
        $this->modFrete = (isset($this->parmPost['modFrete']) ? $this->parmPost['modFrete'] : "");
        $this->transportador = (isset($this->parmPost['pessoa']) ? $this->parmPost['pessoa'] : "0");
        $this->volume = (isset($this->parmPost['volume']) ? $this->parmPost['volume'] : "1");
        $this->volEspecie = (isset($this->parmPost['volEspecie']) ? $this->parmPost['volEspecie'] : "");
        $this->volMarca = (isset($this->parmPost['volMarca']) ? $this->parmPost['volMarca'] : "");
        $this->volPesoLiq = (isset($this->parmPost['volPesoLiq']) ? $this->parmPost['volPesoLiq'] : "");
        $this->volPesoBruto = (isset($this->parmPost['volPesoBruto']) ? $this->parmPost['volPesoBruto'] : "");
        $this->obs = (isset($this->parmPost['obs']) ? $this->parmPost['obs'] : "");
        $this->alteraCondPgto = (isset($this->parmPost['alteraCondPgto']) ? $this->parmPost['alteraCondPgto'] : "");
       
        $this->numNf = (isset($this->parmPost['numNf']) ? $this->parmPost['numNf'] : "");
        

        // status para cadastro da nf em aberto mesmo com erro.
        $this->nfAberto = (isset($this->parmPost['nfAberto']) ? true : false);
        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Entrega Pedidos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "50"); 

        // include do javascript
        // include ADMjs . "/ped/s_pedido_venda_nf.js";
    }

    /**
     * <b> Busca parcelas do formulário para ser lancadas no financeiro, o total das parcelas tem que fechar com o total da NF </b>
     * @name formParcelasNfe
     * @param VARCHAR condPgto (para calcular o numero de parcelas
     * @param int total
     * @return Matriz com as datas de vencimento, valores, tipo e situacao de cada parcela.
     */
    public function formParcelasNfe($condPgto = NULL, $total = 0, $servico=false){
        $parcelas = explode("/", $condPgto);
        $numParcelas = count($parcelas);
        $totalGeral = doubleval($total);
        $totalCalc = 0;
        for ($i = 0; $i < $numParcelas; $i++) {
            $lanc[$i]['PARCELA'] = $i + 1;
            $lanc[$i]['VENCIMENTO'] = (isset($this->parmPost['venc'.$lanc[$i]['PARCELA']]) ? $this->parmPost['venc'.$lanc[$i]['PARCELA']] : "");
            $this->setTotal((isset($this->parmPost['valor'.$lanc[$i]['PARCELA']]) ? $this->parmPost['valor'.$lanc[$i]['PARCELA']] : ""));
            $lanc[$i]['VALOR'] = $this->getTotal('B');
            $lanc[$i]['TIPO'] = (isset($this->parmPost['tipo'.$lanc[$i]['PARCELA']]) ? $this->parmPost['tipo'.$lanc[$i]['PARCELA']] : "");
            $lanc[$i]['SITUACAO'] = (isset($this->parmPost['situacao'.$lanc[$i]['PARCELA']]) ? $this->parmPost['situacao'.$lanc[$i]['PARCELA']] : "");
            $lanc[$i]['CONTA'] = (isset($this->parmPost['conta'.$lanc[$i]['PARCELA']]) ? $this->parmPost['conta'.$lanc[$i]['PARCELA']] : "");
            $lanc[$i]['OBS'] = (isset($this->parmPost['obs'.$lanc[$i]['PARCELA']]) ? $this->parmPost['obs'.$lanc[$i]['PARCELA']] : "");

            $totalCalc += $lanc[$i]['VALOR'];
        }
        $epsilon = 0.00001;
        $totalAbs = abs($totalCalc - $totalGeral);
        if($servico == true){
            for ($i = 0; $i < $numParcelas; $i++) {
                $lanc[$i]['PARCELA'] = $i + 1;
                $lanc[$i]['VENCIMENTO'] = (isset($this->parmPost['vencServico'.$lanc[$i]['PARCELA']]) ? $this->parmPost['vencServico'.$lanc[$i]['PARCELA']] : "");
                $this->setTotal((isset($this->parmPost['valorServico'.$lanc[$i]['PARCELA']]) ? $this->parmPost['valorServico'.$lanc[$i]['PARCELA']] : ""));
                $lanc[$i]['VALOR'] = $this->getTotal('B');
                $lanc[$i]['TIPO'] = (isset($this->parmPost['tipoServico'.$lanc[$i]['PARCELA']]) ? $this->parmPost['tipoServico'.$lanc[$i]['PARCELA']] : "");
                $lanc[$i]['SITUACAO'] = (isset($this->parmPost['situacaoServico'.$lanc[$i]['PARCELA']]) ? $this->parmPost['situacaoServico'.$lanc[$i]['PARCELA']] : "");
                $lanc[$i]['CONTA'] = (isset($this->parmPost['contaServico'.$lanc[$i]['PARCELA']]) ? $this->parmPost['contaServico'.$lanc[$i]['PARCELA']] : "");
                $lanc[$i]['OBS'] = (isset($this->parmPost['obsServico'.$lanc[$i]['PARCELA']]) ? $this->parmPost['obsServico'.$lanc[$i]['PARCELA']] : "");
    
                $totalCalc += $lanc[$i]['VALOR'];
            }
            $epsilon = 0.00001;
            $totalAbs = abs($totalCalc - $totalGeral);
        }

        if($totalAbs < $epsilon):
            return $lanc;
        else:
            return $lanc;
          //uol
          //  return "Valor total parcelas: R$ ".$totalCalc." não confere com TOTAL NF";
        endif;
    }
    
    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle() {
        $quantPedido = 0;
        $quantReserva = 0;
        
        switch ($this->m_submenu) {
            case 'financeiro': // financeiro
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->formNf = false;
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'notafiscal':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->formNf = true;
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'cadastraNf':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'S')) {
                    $this->formNf = true;

                try {
                    // busca dados pedido e cliente
                    //$this->setPedidoVenda(); // seta dados pedido e cliente
                    $arrPedido = $this->select_pedidoVenda();

                    // valida se CC pedido é o mesmo da CC logada no sistema.
                    // if ($arrPedido[0]['CCUSTO']==$this->m_empresacentrocusto){
                    //     $this->m_msg = "Erro: Empresa da Pedido difere da empresa atual logada no sistema!";
                    //     throw new Exception( $this->m_msg );    
                    // }

                    if ($arrPedido[0]['CODMUNICIPIO']==''):
                        $cep = preg_replace("/[^0-9]/", "", $arrPedido[0]['CEP']);
                        $url = "https://viacep.com.br/ws/$cep/xml/";
                        $xml = simplexml_load_file($url);                                                        
                        $ibge= $xml->ibge;
                        if ($ibge != null) {
                            c_conta::updateCodMunicipio($arrPedido[0]['CLIENTE'],$ibge);                             
                        } else {
                            $this->m_msg = "Erro: Cógio do Município não encontrado. Conferir dados do endereço no cadastro do cliente!";
                            throw new Exception( $this->m_msg );    
                        }
                    endif;

                    // Testa empresa logada com empresa pedido
                    if ($this->m_empresacentrocusto != $arrPedido[0]['CCUSTO']){
                        $this->m_msg = "Empresa do PEDIDO diferente empresa LOGADA, Conecte-se a mesma empresa do PEDIDO: Pedido==>>".$this->getId();
                        $result = false;
                        throw new Exception( $this->m_msg );                        
                    }

                    $numPedido = $arrPedido[0]['PEDIDO'];
                    $sitPedido = $arrPedido[0]['SITUACAO'];
                    if ($sitPedido == 9):
                        $this->m_msg = "Pedido já BAIXADO, nota fiscal não foi gerada: Pedido==>>".$this->getId();
                        $result = false;
                        throw new Exception( $this->m_msg );
                    endif;
                    
                    // search param
                    $parametros = new c_banco;
                    $parametros->setTab("EST_PARAMETRO");
                    $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
                    // if not location nat_op in order. search param stock
                    if (is_null($arrPedido[0]['NFAUTO'])){
                       $integraFin = $parametros->getField("INTEGRAFIN", "FILIAL=".$this->m_empresacentrocusto);
                       $validaNfAuto = $parametros->getField("VALIDANFAUTO", "FILIAL=".$this->m_empresacentrocusto);
                       $modeloNf = $parametros->getField("MODELO", "FILIAL=".$this->m_empresacentrocusto);
                    }else {
                        $validaNfAuto = $arrPedido[0]['NFAUTO'];
                        $modeloNf = $arrPedido[0]['MODELONF'];
                    }
                    $parametros->setTab("EST_NAT_OP");
                    $arrNatOp = $parametros->getRecord("ID=".$this->getIdNatop());
                    if ($controlaEstoque == "S"):
                        $controlaEstoque = $arrNatOp[0]["ALTERAQUANT"];
                    endif;
                    
                    $integraFin = $arrNatOp[0]["INTEGRAFIN"];
                    $parametros->close_connection();    
                                        

                    $transaction = new c_banco();
                    $transaction->inicioTransacao($transaction->id_connection);
                    $result = true;

                    // CHECK IF ORDER HAS BEEN INVOICE
                    
                    // BUSCA PARCELAS FORM
                    $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
                    $arrParcelas = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'));
                    /* incluir campo de desconto no form de pedido - faturamento 
                    if (is_string($arrParcelas)):
                        $this->m_msg = $arrParcelas;
                        $result = false;
                        throw new Exception( $this->m_msg );
                    endif;
                     * 
                     */
                    $this->setTotal(isset($this->parmPost['total']) ? $this->parmPost['total'] : '');
                    
                    // VERIFICA QUANTIDADE DE PRODUTOS PEDIDO X RESERVA ESTOQUE
                    if ($controlaEstoque == 'S'): // testa se controla estoque
                         // busca itens do pedido para conferencia de quantidade   
                        $arrItemPedido = $this->select_pedido_item_id('1');
                        if (!is_array($arrItemPedido)):
                            $this->m_msg = "Não existem produtos no pedido: ".$this->getId();
                            $result = false;
                            throw new Exception( $this->m_msg );
                        endif;
                        // busca quantidade reservada para teste e lancamento NF
                        $objProdutoEstoque = new c_produto_estoque();
//                        $arrProdutoEstoqueReserva = $objProdutoEstoque->consultaProdutoReserva($this->getId());
//                        if (!is_array($arrProdutoEstoqueReserva)):
//                            $this->m_msg = "Não existem produtos reservados para o pedido: ".$this->getId();
//                            $result = false;
//                            throw new Exception( $this->m_msg );
//                        endif;
//
//                        for ($i = 0; $i < count($arrItemPedido); $i++) {
//                            $quantPedido += $arrItemPedido[$i]['QTSOLICITADA'];
//                        }
//                        for ($i = 0; $i < count($arrProdutoEstoqueReserva); $i++) {
//                            $quantReserva += $arrProdutoEstoqueReserva[$i]['QUANT'];
//                        }
//                        if ($quantPedido <> $quantReserva):
//                            $this->m_msg = "Quantidade Total de Produtos Reservado DIFERENTE da quantidade Total do pedido: ".$this->getId();
//                            $result = false;
//                            throw new Exception( $this->m_msg );
//                        endif;
                    else:
                         // busca itens do pedido para conferencia de quantidade   
                        $arrItemPedido = $this->select_pedido_item_id('3');
                        if (!is_array($arrItemPedido)):
                            $this->m_msg = "Não existem produtos no pedido: ".$this->getId();
                            $result = false;
                            throw new Exception( $this->m_msg );
                        endif;
                        
                    endif;
                    
                    // GERA NF
                    $objNotaFiscal = new c_nota_fiscal();
                    
                    if ($objNotaFiscal->existeNotaFiscalPedido($numPedido) == true):
                        $this->m_msg = "Já existe nota fiscal autorizada para este pedido: ".$this->getId();
                        $result = false;
                        throw new Exception( $this->m_msg );
                    endif;
                    
                    $objNotaFiscal->setModelo($modeloNf);
                    $objNotaFiscal->setSerie($this->getSerie());
                    // ****** Gerar numero da notafiscal!! *********
                    $numNf = 0;
                    /*$this->m_msg = $numNf." >>>Numero NF";
                    if (intval($numNf)==0):
                        $result = false;
                        throw new Exception( $this->m_msg );
                    endif;*/
                    $objNotaFiscal->setNumero($numNf); 
                    $objNotaFiscal->setPessoa($this->getCliente()); // ****** Define o cliente da nf de saida notafiscal!! *********
                    $objNotaFiscal->setNomePessoa(); // ****** Seta NOME, PESSOA, UF *********
                    $objNotaFiscal->setEmissao(date("d/m/Y H:i:s"));
                    $objNotaFiscal->setIdNatop($this->getIdNatop());
//                    $objNotaFiscal->setNatOperacao($this->getNatOperacao());//====
//                    $objNotaFiscal->setNatOperacao();//====
                    $objNotaFiscal->setTipo('1');
                    $objNotaFiscal->setSituacao('A');
                    $objNotaFiscal->setFormaPgto($this->getFormaPgto());//===
                    $objNotaFiscal->setCondPgto($this->getCondPg());
                    $objNotaFiscal->setDataSaidaEntrada(date("d/m/Y H:i:s"));
                    $objNotaFiscal->setFormaEmissao('N');
                    $objNotaFiscal->setFinalidadeEmissao('1');
                    $objNotaFiscal->setCentroCusto(isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto);
                    $objNotaFiscal->setGenero($this->getGenero());//====????
                    $objNotaFiscal->setTotalnf($this->getTotal());//===
                    $objNotaFiscal->setModFrete($this->modFrete);
                    if ($this->transportador == ""){
                        $this->transportador = '0';
                    }
                    $objNotaFiscal->setTransportador($this->transportador);
                    $objNotaFiscal->setVolume($this->volume);
                    $objNotaFiscal->setVolEspecie($this->volEspecie);
                    $objNotaFiscal->setVolMarca($this->volMarca);
                    $objNotaFiscal->setVolPesoLiq($this->volPesoLiq);
                    $objNotaFiscal->setVolPesoBruto($this->volPesoBruto);
                    $objNotaFiscal->setObs("Pedido: ".$numPedido."; ".$arrNatOp[0]["OBS"].";".$arrPedido[0]['OBS'].";".$this->obs);
                    $objNotaFiscal->setOrigem('PED');
                    $objNotaFiscal->setDoc($numPedido);

                    $objNotaFiscal->setFrete((isset($arrPedido[0]['FRETE']) ? $arrPedido[0]['FRETE'] : $this->frete),true);
                                
                    $idGerado = $objNotaFiscal->incluiNotaFiscal($transaction->id_connection);
                    // verificar inclusao NF
                    if (intval($idGerado)==0):
                        $this->m_msg = $idGerado;
                        $result = false;
                        throw new Exception( $this->m_msg );
                    endif;

                    // CADASTRA ITENS NF
                    //$objProduto = new c_produto();
                    $objNfProduto = new c_nota_fiscal_produto();
                    for ($i = 0; $i < count($arrItemPedido); $i++) {
                        
                        if ($controlaEstoque == 'S') {
                            if ($arrItemPedido[$i]['QTSOLICITADA'] != $arrItemPedido[$i]['QUANTIDADE']):
                                
                                $quantDigitada = $arrItemPedido[$i]['QTSOLICITADA'] - $arrItemPedido[$i]['QUANTIDADE'];
                                
                                $objProdutoQtde = new c_produto_estoque();

                                $QTDISP = $objProdutoQtde->produtoDisponivel($this->m_empresacentrocusto, 
                                    $arrItemPedido[$i]['ITEMESTOQUE'], $transaction->id_connection);                        
                                
                                if ( $QTDISP >= $quantDigitada) {
                                    $objProdutoQtde->produtoReserva($this->m_empresacentrocusto, "PED", 
                                    $numPedido, $arrItemPedido[$i]['ITEMESTOQUE'], $quantDigitada, $transaction->id_connection);    
                                    $arrItemPedido[$i]['QUANTIDADE'] = $arrItemPedido[$i]['QTSOLICITADA'];
                                } else {
                                    $this->m_msg = "Erro: Quantidade solicitida ".$arrItemPedido[$i]['QTSOLICITADA']." não disposível! Diponível ".$arrItemPedido[$i]['QUANTIDADE']."!";
                                    throw new Exception( $this->m_msg );
                                }
                            endif;    
                        } else {
                            $arrItemPedido[$i]['QUANTIDADE'] = $arrItemPedido[$i]['QTSOLICITADA'];
                        }

                        
                        $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QUANTIDADE']*$arrItemPedido[$i]['UNITARIO']; //QTSOLICITADO - ALTERADO 18/07/2019 
                        
                        if ($arrItemPedido[$i]['TOTAL'] <= 0):
                            $this->m_msg = "Erro: Valor Total Zerado!";
                            throw new Exception( $this->m_msg );
                        endif;

                        $objNfProduto->setIdNf($idGerado);
                        $objNfProduto->setCodProduto($arrItemPedido[$i]['ITEMESTOQUE']);
                        $objNfProduto->setDescricao($arrItemPedido[$i]['DESCRICAO']);
                        $objNfProduto->setUnidade($arrItemPedido[$i]['UNIDADE']);
                        $objNfProduto->setQuant($arrItemPedido[$i]['QUANTIDADE'], true); //QTSOLICITADO - ALTERADO 18/07/2019 
                        $objNfProduto->setUnitario($arrItemPedido[$i]['UNITARIO'], true);
                        $objNfProduto->setDesconto($arrItemPedido[$i]['DESCONTO'], true); // VERIFICAR DESCONTO
                        $objNfProduto->setTotal($arrItemPedido[$i]['TOTAL'], true);

                        $objNfProduto->setOrigem($arrItemPedido[$i]['ORIGEM']);
                        $objNfProduto->setTribIcms($arrItemPedido[$i]['TRIBICMS']);
                        $objNfProduto->setNcm($arrItemPedido[$i]['NCM']);
                        $objNfProduto->setCest($arrItemPedido[$i]['CEST']);
                        $objNfProduto->setFrete($arrItemPedido[$i]['FRETE'],true);


                        $result = $this->calculaImpostosNfe($objNfProduto, 
                                      $objNotaFiscal->getIdNatop(), 
                                      $objNotaFiscal->getUfPessoa(), 
                                      $objNotaFiscal->getTipoPessoa(), 
                                      $this->m_empresacentrocusto); 

                        if (!$result):
                            $this->m_msg = "Tributos não localizado ".$objNfProduto->getDescricao()." Nat. Operação:".$objNotaFiscal->getIdNatop().
                                "<br> UF:".$objNotaFiscal->getUfPessoa()." Tipo:".$objNotaFiscal->getTipoPessoa().
                                " CST:".$objNfProduto->getOrigem().$objNfProduto->getTribIcms().
                                "<br> NCM:".$objNfProduto->getNcm()." CEST:".$objNfProduto->getCest()."<br>";
                            throw new Exception( $this->m_msg );
                        endif;
                        $objNfProduto->setCustoProduto($arrItemPedido[$i]['CUSTOPRODUTO']);

                        $objNfProduto->setNrSerie(''); // VERIFICAR SISTEMA PARA INCLUIR OS NUMEROS DE SÉRIES
                        $objNfProduto->setDataGarantia('');
                        $objNfProduto->setLote($arrItemPedido[$i]['FABLOTE']);
                        $objNfProduto->setDataValidade($arrItemPedido[$i]['FABDATAVALIDADE']);
                        $objNfProduto->setDataFabricacao($arrItemPedido[$i]['FABDATAFABRICACAO']);

                        $objNfProduto->setOrdem($arrItemPedido[$i]['NUMEROOC']);
                        $objNfProduto->setProjeto($arrItemPedido[$i]['PROJETO']);
                        $objNfProduto->setDataConferencia($arrItemPedido[$i]['DATACONFERENCIA']);
                        
                        $objNfProduto->setBcFcpUfDest('0');
                        $objNfProduto->setAliqFcpUfDest('0');
                        $objNfProduto->setValorFcpUfDest('0');
                        $objNfProduto->setBcIcmsUfDest('0');
                        $objNfProduto->setAliqIcmsUfDest('0');
                        $objNfProduto->setAliqIcmsInter('0');
                        $objNfProduto->setAliqIcmsInterPart('0');
                        $objNfProduto->setValorIcmsUfDest('0');
                        $objNfProduto->setValorIcmsUFRemet('0');

                        $objNfProduto->setCodigoNota($arrItemPedido[$i]['CODIGONOTA']);
                        $objNfProduto->setDespAcessorias($arrItemPedido[$i]['DESPACESSORIAS'], true);
                        
                        $result = $objNfProduto->incluiNotaFiscalProduto($transaction->id_connection);
                        // verificar inclusao item
                        if (is_string($result)):
                            $this->m_msg = $result;
                            $result = false;
                            throw new Exception( $this->m_msg );
                        endif;

                        //***************
                        //RETIRAR DE RESERVA E BAIXAR DO ESTOQUE INC O NUM NF
                        //********************
                        $uniFrac = $arrItemPedido[$i]['UNIFRACIONADA'];
                        $ifControlaEstoque = (($controlaEstoque == 'S') && ($uniFrac == 'N'));
//                        if ($controlaEstoque == 'S'):
                        if ($ifControlaEstoque):
                            $objProdutoEstoque->produtoBaixaReserva($this->m_empresacentrocusto,   
                                $this->getId(), $idGerado, $objNfProduto->getCodProduto(), $transaction->id_connection);
                        endif;
                    } //for
                    
                    //***************************************************
                    // baixa pedido
                    //***************************************************
                    $parametros = new c_banco;
                    $parametros->setTab("FAT_PARAMETRO");
                    $situacaoBaixa = $parametros->getField("SITBAIXADO", "FILIAL=".$this->m_empresacentrocusto);
                    $parametros->close_connection();                        
                    $this->setSituacao($situacaoBaixa);
                    $this->setPedido($this->getId());
                   
                    
                    // ************** 
                    // lanca parcelas financeiro
                    //***************
                    $objFinanceiro = new c_lancamento();

                    $objFinanceiro = new c_lancamento();
                    $existeFin = $objFinanceiro->select_lancamento_doc('PED', $this->getPedido());
                    if(!is_array($existeFin)){
                        $arrParamFin['PESSOA'] = $objNotaFiscal->getPessoa();
                        $arrParamFin['DOCTO'] = $numPedido;
                        $arrParamFin['SERIE'] = 'PED';
                        $arrParamFin['GENERO'] = $this->getGenero();
                        $arrParamFin['CENTROCUSTO'] = isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto;
                        $arrParamFin['USER'] = $this->m_userid;
                        $arrParamFin['ORIGEM'] = "PED";
                        $arrParamFin['NUMLCTO'] = $numPedido;
                        $arrParamFin['TIPOLANCAMENTO'] = "R";
                        $arrParamFin['OBS'] = $objNotaFiscal->getObs();

                        if ($integraFin == 'S'):
                            // não altera financeiro se já existir lançamento de pedido
                            $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $transaction->id_connection);
                        endif;
                    }else{
                        $integraFin = 'N';
                    }             
                    
                    //; commit transação
                    //$transaction->commit($transaction->id_connection);
                    
                    // valida e autoriza nf automaticamente
                    if ($validaNfAuto=='S'):
                        // Gera e altera numero NF
                        $numNf = $objNotaFiscal->geraNumNf($objNotaFiscal->getModelo(), $objNotaFiscal->getSerie(), $this->m_empresacentrocusto, $transaction->id_connection);
                        if (intval($numNf)==0):
                            $this->m_msg = "Idendificador NF >>> ".$idGerado." - Número não Gerado";
                            $result = false;
                            throw new Exception( $this->m_msg);
                        endif;
                        $objNotaFiscal->setId($idGerado);
                        $objNotaFiscal->setNumero($numNf);
                        $objNotaFiscal->alteraNfNumero($transaction->id_connection);
                        
                        if ($integraFin == 'S'):
                            $objFinanceiro->alteraParcelaPedidoNf($numPedido, $numNf, $transaction->id_connection);
                        endif;
                        // valida e autoriza nf
                        $exporta = new p_nfe_40();
                        $result = $exporta->Gera_XML($idGerado, $this->m_empresacentrocusto, '', $transaction->id_connection);

                        if ($result['cStatus'] == '100'):
                            $this->atualizarField('situacao', $situacaoBaixa, $transaction->id_connection);                            
                            //; commit transação
                            $transaction->commit($transaction->id_connection);

                            $printDanfe = new p_nfephp_imprime_danfe();
                            if ($this->m_opcao ==''):
                                $printDanfe->printDanfe($idGerado, $objNotaFiscal->getDoc(), $objNotaFiscal->getOrigem(), $result['cDanfe'], $objNotaFiscal->getDoc(), 'pedido_venda_nf');
                            else:    
                                $printDanfe->printDanfe($idGerado, $objNotaFiscal->getNumero(), $objNotaFiscal->getSerie(), $result['cDanfe'], $objNotaFiscal->getDoc(), $this->m_opcao);
                            endif;
                        else:    
                            // roollback transação
                            $transaction->rollback($transaction->id_connection);    
                            $this->desenhaCadastroPedido('Nota Fiscal Não Gerada, Identificador: '.$idGerado);
                        endif;

                    else:
                        //; commit transação
                        $transaction->commit($transaction->id_connection);
                        $this->mostraPedido('Nota Fiscal Gerada, Identificador: '.$idGerado);
                    endif;

                    
                } catch (Error $e) {
                    $transaction->rollback($transaction->id_connection);    
                    throw new Exception($e->getMessage()."Nf Não foi gerado " );

                }
                catch (Exception $e) {
                    //echo 'Caught exception: ',  $e->getMessage(), "\n";
                    if ($this->nfAberto == true):
                        $transaction->commit($transaction->id_connection);
                    else:
                        if (isset($conn)):
                            $transaction->rollback($transaction->id_connection);    
                        endif;    
                    endif;
                    $this->desenhaCadastroPedido("Identificador NF: ".$idGerado."<br>".$e->getMessage()."<br>");
                    break;
                }
                    /*if ($result):
                        $transaction->commit();
                    else:
                        $transaction->rollback();    
                    endif;
                    */
                    //$this->mostraPedido($this->m_msg);
                    
                }
                break;
            case 'cadastraFinanceiro':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->formNf = false;

                    $parametros = new c_banco;
                    $parametros->setTab("EST_NAT_OP");
                    $arrNatOp = $parametros->getRecord("ID=".$this->getIdNatop());
                    $integraFin = $arrNatOp[0]["INTEGRAFIN"];
                    $parametros->close_connection();   
                    
                    $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
                  //  $arrParcelas = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'));

                    $arrParcelasServico = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'), true);

                     // ************** 
                    // lanca parcelas financeiro
                    //***************
                    $objFinanceiro = new c_lancamento();

                  /*  $arrParamFin['PESSOA'] = $this->getCliente();
                    $arrParamFin['DOCTO'] = $this->getId();
                    $arrParamFin['SERIE'] = 'PED';
                    $arrParamFin['GENERO'] = $this->getGenero();
                    $arrParamFin['CENTROCUSTO'] = isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto;
                    $arrParamFin['USER'] = $this->m_userid;
                    $arrParamFin['ORIGEM'] = "PED";
                    $arrParamFin['NUMLCTO'] = $this->getId();
                    $arrParamFin['TIPOLANCAMENTO'] = "R"; */

                    $arrFinServico['PESSOA'] = $this->getCliente();
                    $arrFinServico['DOCTO'] = $this->numNf;
                    $arrFinServico['SERIE'] = 'PSV';   //  PEDIDO TIPO SERVICO
                    $arrFinServico['GENERO'] = $this->getGenero();
                    $arrFinServico['CENTROCUSTO'] = isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto;
                    $arrFinServico['USER'] = $this->m_userid;
                    $arrFinServico['ORIGEM'] = "PSV";
                    $arrFinServico['NUMLCTO'] = $this->getId();
                    $arrFinServico['TIPOLANCAMENTO'] = "R";

                    if ($integraFin == 'S'):
                       // $objFinanceiro->addParcelas($arrParamFin, $arrParcelas);
                        $objFinanceiro->addParcelas($arrFinServico, $arrParcelasServico);
                    endif;

                    $this->incluiNfServico($this->numNf, $this->getId());
                    //$objPed = new c_pedidoVenda();
                    //$objPed->setId($this->getId());
                    //$objPed->atualizarField("SITUACAO", '3');

                    $objPedGerente = new p_pedido_venda_conferecia_novo('', '', '', '');
                    $objPedGerente->controle('');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg = NULL) {

        $descCondPgto = "";
        $parcelas = 0;
        $valorParcelas = 0;
        $totalParcelas = 0;
        $numParcelas = 0;
        $fin = [];
        
        //$pedido = $this->select_pedidoVenda();
        $this->setPedidoVenda(); // seta dados pedido e cliente

        //separa valores de produto e de servico

        // $consulta = new c_banco;
        // $consulta->setTab("FAT_PEDIDO");
        // $valorServico = $consulta->getField("VALORSERVICOS", " ID =".$this->getPedido());
        // $consulta->close_connection();
        // $totalSemServico = $this->getTotal() - $this->getValorServicos('B');

        $valorServico = $this->setTotal($this->getValorServicos('F'));
        
        //parametro de pesquisa
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $cfop = $parametros->getParametros("CFOP");
        $natOperacao = $parametros->getParametros("NATOPERACAO");
        $condPgto = $this->getCondPg();
        if ($condPgto == 0):
            $condPgto = $parametros->getParametros("CONDPGTO");
        endif;
        $genero = $parametros->getParametros("GENERO");
        $conta = $parametros->getParametros("CONTA");
        $serie = $parametros->getParametros("SERIE");
        $parametros->close_connection();

        
        // metodo SET dos dados do FORM para o TABLE
        if (isset($this->parmPost['idNatop'])):
            $this->setIdNatop($this->parmPost['idNatop']);
        else:
            if ($this->getIdNatop() == 'NULL'):
                $this->setIdNatop($natOperacao);
            endif;
        endif;
        $this->setSerie(isset($this->parmPost['serie']) ? $this->parmPost['serie'] : $serie);
        if($this->alteraCondPgto == true){
            $this->setCondPg(isset($this->parmPost['condPgto']) ? $this->parmPost['condPgto'] : $condPgto);
        }
        $this->setGenero(isset($this->parmPost['genero']) ? $this->parmPost['genero'] : $genero);
        $this->setContaDeposito(isset($this->parmPost['conta']) ? $this->parmPost['conta'] : $conta);

        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('alteraCondPgto', $this->alteraCondPgto);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('pedido', $this->getPedido());
        $this->smarty->assign('cliente', $this->getCliente());
        $this->smarty->assign('data', $this->getEmissao('F'));
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('natOperacao', $this->getIdNatop());
        $this->smarty->assign('serie', $this->getSerie());
        $this->smarty->assign('clienteNome', $this->getClienteNome());
        $this->smarty->assign('modFrete', $this->modFrete);
        $this->smarty->assign('pessoa', $this->transportador);

        $transp = new c_conta();
        $transp->setId($this->transportador);
        $reg_nome = $transp->select_conta();
        
        $this->smarty->assign('transpNome', $reg_nome[0]['NOME']);
        $this->smarty->assign('volume', $this->volume);
        $this->smarty->assign('volEspecie', $this->volEspecie);
        $this->smarty->assign('volMarca', $this->volMarca);
        $this->smarty->assign('volPesoLiq', $this->volPesoLiq);
        $this->smarty->assign('volPesoBruto', $this->volPesoBruto);
        $this->smarty->assign('obs', $this->obs);

        // ########## NATUREZA OPERACAO ##########
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where (tipo='S') order by id";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $natOperacao_ids[$i] = $result[$i]['ID'];
            $natOperacao_names[$i] = $result[$i]['ID']." - ".$result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natOperacao_ids', $natOperacao_ids);
        $this->smarty->assign('natOperacao_names', $natOperacao_names);
        $this->smarty->assign('natOperacao_id', $this->getIdNatop());

        // COMBOBOX CONDICAO PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            if ($this->getCondPg()==$result[$i]['ID']):
                $descCondPgto = $result[$i]['DESCRICAO'];
            endif;
            $condPgto_ids[$i] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('descCondPgto', "$descCondPgto");
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPg());


        // COMBOBOX GENERO
        $consulta = new c_banco();
        $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero where (tipolancamento = 'R') ORDER BY descricao;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $genero_ids[$i] = $result[$i]['ID'];
            $genero_names[$i] = $result[$i]['ID']." - ".$result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('genero_ids', $genero_ids);
        $this->smarty->assign('genero_names', $genero_names);
        $this->smarty->assign('genero_id', $this->getGenero());

        // COMBOBOX CONTA
        $consulta = new c_banco();
        $sql = "SELECT conta as id, nomeinterno as descricao FROM fin_conta where status ='A' ORDER BY nomeinterno;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('conta_ids', $conta_ids);
        $this->smarty->assign('conta_names', $conta_names);
        $this->smarty->assign('conta_id', $this->getContaDeposito());

        // tipo documento
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoDoctoPgto')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $tipoDocto_ids[$i] = $result[$i]['ID'];
                $tipoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('tipoDocto_ids', $tipoDocto_ids);
        $this->smarty->assign('tipoDocto_names', $tipoDocto_names);
        $this->smarty->assign('tipoDocto_id', 'B');

        
        // situacao lancamento
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $situacaoLanc_ids[$i] = $result[$i]['ID'];
                $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
        $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
        $this->smarty->assign('situacaoLanc_id', 'A');

        // modalidade frete
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='modFrete')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $modFrete_ids[$i] = $result[$i]['ID'];
                $modFrete_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('modFrete_ids', $modFrete_ids);
        $this->smarty->assign('modFrete_names', $modFrete_names);
        $this->smarty->assign('modFrete_id', $this->modFrete);

        // COMBOBOX CENTROCUSTO
        $consulta = new c_banco();
        $sql = "SELECT CentroCusto AS ID, DESCRICAO FROM fin_centro_custo ORDER BY CentroCusto";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $centroCusto_ids[$i] = $result[$i]['ID'];
            $centroCusto_names[$i] = $result[$i]['ID']." - ".$result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto());
        
        // gera parcelas Produto
        $resp = $this->verificaParcelas($this->getPedido(),'PSV');
        $fin = $this->geraParcelas($this->getPedido(), $resp, $descCondPgto, $totalSemServico);
        if($resp == false){
            $this->smarty->assign('parcelasCadastrada', false);
        }else{
            $mensagem .= "Pedido com parcelas já cadastradas no financeiro!";
            $this->smarty->assign('parcelasCadastrada', true);
        }

        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('fin', $fin);

        $finServico = $this->geraParcelas($this->getPedido(), $resp, $descCondPgto, $this->getTotal('B'));

        $this->smarty->assign('finServico', $finServico);
        if ($this->formNf == true)
            $this->smarty->assign('formNf', true);
        else
            $this->smarty->assign('formNf', false);

        $this->smarty->display('pedido_venda_nf_ps.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem) {

        $lanc = $this->select_pedidoVenda_letra('||||3|');

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_nf_mostra.tpl');
    }

    function verificaParcelas($idPedido, $origem){
        $consultaFin = new c_banco();
        $consultaFin->setTab("FIN_LANCAMENTO");
        $numPedFin = $consultaFin->getField("NUMLCTO", "NUMLCTO = '".$idPedido."' AND ORIGEM ='".$origem."'");
        $consultaFin->close_connection();

        empty($numPedFin) ? $resp = false :  $resp = true;

        return $resp;
    }

    function geraParcelas($idPedido, $lanc, $descCondPgto, $total){
        if($lanc == false){
            $fin = $this->calculaParcelasNfe($descCondPgto, $total);
        }else{
            $lancObj = new c_lancamento();
            $fin = $lancObj->select_lancamento_doc('PED', $idPedido);
            for($i=0; $i<count($fin); $i++){
                $con = new c_banco();
                $con->setTab("FIN_CONTA");
                $banco = $con->getField("NOMEINTERNO", "CONTA=".$fin[$i]['CONTA']);
                $con->close_connection();
                $fin[$i]['CONTA'] = $banco;

                $con = new c_banco();
                $con->setTab("AMB_DDM");
                $tipoDocto = $con->getField("PADRAO", "TIPO='".$fin[$i]['TIPODOCTO']."' AND (alias='FIN_MENU') and (campo='TipoDoctoPgto')");
                $con->close_connection();
                $fin[$i]['TIPODOCTO'] = $tipoDocto;

                $con = new c_banco();
                $con->setTab("AMB_DDM");
                $sitPgto = $con->getField("PADRAO", "TIPO='".$fin[$i]['SITPGTO']."' AND (alias='FIN_MENU') and (campo='SituacaoPgto')");
                $con->close_connection();
                $fin[$i]['SITPGTO'] = $sitPgto;
            }
        }
        return $fin;
    }

    function incluiNfServico($numNf, $numPedido){

        $con = new c_banco();
        $con->setTab("FAT_PEDIDO");
        $obsPedido = $con->getField("OBS", "ID=".$numPedido);
        $con->close_connection();

        $parametro = new c_banco;
        $parametro->setTab("EST_PARAMETRO");
        $modeloNf = $parametro->getField("MODELO", "FILIAL=".$this->m_empresacentrocusto);
        $parametro->close_connection();

        // GERA NF
        $objNotaFiscal = new c_nota_fiscal();
        $objNotaFiscal->setModelo($modeloNf);
        $objNotaFiscal->setSerie($this->getSerie());
        $objNotaFiscal->setNumero($numNf); 
        $objNotaFiscal->setPessoa($this->getCliente()); // ****** Define o cliente da nf de saida notafiscal!! *********
        $objNotaFiscal->setNomePessoa(); // ****** Seta NOME, PESSOA, UF *********
        $objNotaFiscal->setEmissao(date("d/m/Y H:i:s"));
        $objNotaFiscal->setIdNatop($this->getIdNatop());
       
        $objNotaFiscal->setTipo('1');
        $objNotaFiscal->setSituacao('A');
        $objNotaFiscal->setFormaPgto($this->getFormaPgto());//===
        $objNotaFiscal->setCondPgto($this->getCondPg());
        $objNotaFiscal->setDataSaidaEntrada(date("d/m/Y H:i:s"));
        $objNotaFiscal->setFormaEmissao('N');
        $objNotaFiscal->setFinalidadeEmissao('1');
        $objNotaFiscal->setCentroCusto(isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto);
        $objNotaFiscal->setGenero($this->getGenero());//====????
        $objNotaFiscal->setTotalnf($this->getTotal());//===
        $objNotaFiscal->setModFrete($this->modFrete);
        if ($this->transportador == ""){
            $this->transportador = '0';
        }
        $objNotaFiscal->setTransportador($this->transportador);
        $objNotaFiscal->setVolume($this->volume);
        $objNotaFiscal->setVolEspecie($this->volEspecie);
        $objNotaFiscal->setVolMarca($this->volMarca);
        $objNotaFiscal->setVolPesoLiq($this->volPesoLiq);
        $objNotaFiscal->setVolPesoBruto($this->volPesoBruto);
        $objNotaFiscal->setObs("Pedido: ".$numPedido."; ".$obsPedido.";".$this->obs);
        $objNotaFiscal->setOrigem('PSV');  // PEDIDO SERVICO
        $objNotaFiscal->setDoc($numPedido);
        
        $objNotaFiscal->setFrete((isset($arrPedido[0]['FRETE']) ? $arrPedido[0]['FRETE'] : $this->frete),true);
                                        
        $idGerado = $objNotaFiscal->incluiNotaFiscal();
        

        $objPedidoPs = new c_pedido_ps();
        $objPedidoPs->setId($this->getPedido());
        $servicoObj = $objPedidoPs->select_servicos_atendimento();

        $objNfProduto = new c_nota_fiscal_produto();

        for ($i = 0; $i < count($servicoObj); $i++) {

            $objNfProduto->setIdNf($idGerado);
            $objNfProduto->setCodProduto($servicoObj[$i]['CAT_SERVICOS_ID']);
            $objNfProduto->setDescricao($servicoObj[$i]['DESCSERVICO']);
            $objNfProduto->setUnidade($servicoObj[$i]['UNIDADE']);
            $objNfProduto->setQuant($servicoObj[$i]['QUANTIDADE'], true); //QTSOLICITADO - ALTERADO 18/07/2019 
            $objNfProduto->setUnitario($servicoObj[$i]['VALUNITARIO'], true);
            $objNfProduto->setTotal($servicoObj[$i]['TOTALSERVICO'], true);
            
            
            $objNfProduto->setBcFcpUfDest('0');
            $objNfProduto->setAliqFcpUfDest('0');
            $objNfProduto->setValorFcpUfDest('0');
            $objNfProduto->setBcIcmsUfDest('0');
            $objNfProduto->setAliqIcmsUfDest('0');
            $objNfProduto->setAliqIcmsInter('0');
            $objNfProduto->setAliqIcmsInterPart('0');
            $objNfProduto->setValorIcmsUfDest('0');
            $objNfProduto->setValorIcmsUFRemet('0');
            
            
            $result = $objNfProduto->incluiNotaFiscalProduto();
        }
        // GERA NF PRODUTO "SERVICO"
        
        
        
       
    }

//fim mostragrupos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
//$pedido = new p_pedido_venda_nf($id=null);

//$pedido->controle();
?>
