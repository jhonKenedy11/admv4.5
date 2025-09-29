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
require_once($dir . "/../../forms/ped/p_pedido_venda_telhas.php");
require_once($dir . "/../../class/ped/c_pedido_venda_tools.php");
require_once($dir . "/../../class/ped/c_pedido_aprovacao.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");

//Class P_situacao
Class p_pedido_venda_nf extends c_pedidoVendaNf {

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

    public  $numParcelaAdd      = NULL;
    public  $dadosFinanceiros   = NULL;
    public  $idnatop            = NULL;
    public  $totalOriginal      = NULL;

    

    
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

        $this->numParcelaAdd = isset($this->parmPost['numParcelaAdd']) ? $this->parmPost['numParcelaAdd'] : '0';
        $this->dadosFinanceiros = isset($this->parmPost['dadosFinanceiros']) ? $this->parmPost['dadosFinanceiros'] : '';

        $this->totalOriginal = isset($this->parmPost['totalOriginal']) ? $this->parmPost['totalOriginal'] : '';
        
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
        $this->setUsrAprovacao(isset($this->parmPost['usrAprovacao']) ? $this->parmPost['usrAprovacao'] : '');
        $this->setCredito(isset($this->parmPost['credito']) ? $this->parmPost['credito'] : '0');
        
        $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
        $this->modFrete = (isset($this->parmPost['modFrete']) ? $this->parmPost['modFrete'] : "");
        $this->transportador = (isset($this->parmPost['pessoa']) ? $this->parmPost['pessoa'] : "0");

        $this->volume = (isset($this->parmPost['volume']) ? $this->parmPost['volume'] : "1");
        $this->volEspecie = (isset($this->parmPost['volEspecie']) ? $this->parmPost['volEspecie'] : "");
        $this->volMarca = (isset($this->parmPost['volMarca']) ? $this->parmPost['volMarca'] : "");
        $this->volPesoLiq = (isset($this->parmPost['volPesoLiq']) ? $this->parmPost['volPesoLiq'] : "");
        $this->volPesoBruto = (isset($this->parmPost['volPesoBruto']) ? $this->parmPost['volPesoBruto'] : "");
        $this->obs = (isset($this->parmPost['obs']) ? $this->parmPost['obs'] : "");
        $this->totalCredito = $this->parmPost['totalCredito'];
      

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

    public function cobrarTaxa($conta, $condpgto,$total){
                   
        $sql  = "SELECT TAXA FROM fin_conta_taxa ";
        $sql .= "WHERE (conta = '".$conta."' ) and ";
        $sql .= "(condpgto = '".$condpgto."' ) ";

        $banco = new c_banco();
	    $banco->exec_sql($sql);
	    $banco->close_connection();
        $result = $banco->resultado;
        
        if($result > 0){
            return ($total * ($result[0][TAXA] / 100) );
        }
        else{
            return '0';
        }
    }

    /**
     * <b> Busca parcelas do formulário para ser lancadas no financeiro, o total das parcelas tem que fechar com o total da NF </b>
     * @name formParcelasNfe
     * @param VARCHAR condPgto (para calcular o numero de parcelas
     * @param int total
     * @return Matriz com as datas de vencimento, valores, tipo e situacao de cada parcela.
     */
    public function formParcelasNfe($condPgto = NULL, $total = 0, $condicao = 0){
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
            if ($condicao > 0){
                $lanc[$i]['DESCONTO'] = $this->cobrarTaxa($lanc[$i]['CONTA'], $condicao, $lanc[$i]['VALOR']);            
            }
            
            $totalCalc += $lanc[$i]['VALOR'];
        }
        $epsilon = 0.00001;
        $totalAbs = abs($totalCalc - $totalGeral);
        if($totalAbs < $epsilon):
            return $lanc;
        else:
            return $lanc;
          //uol
          //  return "Valor total parcelas: R$ ".$totalCalc." não confere com TOTAL NF";
        endif;
    }

    public function formParcelasNfeFinanceiro($condPgto = NULL, $total = 0, $condicao = 0){
        $parcelas = explode("|", $condPgto);
        $numParcelas = count($parcelas) - 1;
        $totalGeral = doubleval($total);
        $totalCalc = 0;
        for ($i = 0; $i < $numParcelas; $i++) {
            $parcela = explode("*", $parcelas[$i + 1]);
            $lanc[$i]['PARCELA'] = trim($parcela[0]);
            $lanc[$i]['VENCIMENTO'] = $parcela[1];
            $lanc[$i]['VALOR'] = $parcela[2];
            $lanc[$i]['VALOR'] = c_tools::moedaBd($lanc[$i]['VALOR']);
            $lanc[$i]['TIPO'] = $parcela[3];
            $lanc[$i]['CONTA'] = $parcela[4];            
            $lanc[$i]['SITUACAO'] = $parcela[5];
            $lanc[$i]['OBS'] = $parcela[6];
            if ($condicao > 0){
                $lanc[$i]['DESCONTO'] = $this->cobrarTaxa($lanc[$i]['CONTA'], $condicao, $lanc[$i]['VALOR']);            
            }
            
            $totalCalc += $lanc[$i]['VALOR'];
        }
        $epsilon = 0.00001;
        $totalAbs = abs($totalCalc - $totalGeral);
        if($totalAbs < $epsilon):
            return $lanc;
        else:
            return $lanc;
          //uol
          //  return "Valor total parcelas: R$ ".$totalCalc." não confere com TOTAL NF";
        endif;
    }

    public function parcelamentoNfeFinanceiro($condPgto = NULL, $total = 0, $condicao = 0){
        $parcelas = explode("|", $condPgto);
        $numParcelas = count($parcelas) - 1; 
        $totalGeral = doubleval($total);
        $totalCalc = 0;
        $count = 0;
        for ($i = 0; $i < $numParcelas; $i++) {
            $parcela = explode("*", $parcelas[$i + 1]);
            
            $lanc[$count]['PARCELA'] = trim($parcela[0]);
            $lanc[$count]['VENCIMENTO'] = $parcela[1];
            $lanc[$count]['VALOR'] = $parcela[2];
            $lanc[$count]['VALOR'] = c_tools::moedaBd($lanc[$i]['VALOR']);
            $lanc[$count]['TIPO'] = $parcela[3];
            $lanc[$count]['CONTA'] = $parcela[4];            
            $lanc[$count]['SITUACAO'] = $parcela[5];
            $lanc[$count]['OBS'] = $parcela[6];
            if ($condicao > 0){
                $lanc[$count]['DESCONTO'] = $this->cobrarTaxa($lanc[$count]['CONTA'], $condicao, $lanc[$count]['VALOR']);            
            }
            $totalCalc += $lanc[$i]['VALOR'];
            $count +=1; 
        }
        $epsilon = 0.00001;
        $totalAbs = abs($totalCalc - $totalGeral);
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
            case 'NFEEnviar':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    
                        $arrPedido = $this->select_pedidoVenda();

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

                        if ($arrPedido[0]['CNPJCPF']==''):
                            $this->m_msg = "Preencha campo CNPJCPF no cadastro do cliente! ";
                            throw new Exception( $this->m_msg );
                        endif;

                        if (($arrPedido[0]['UF']=='') or ($arrPedido[0]['CIDADE']=='') or ($arrPedido[0]['BAIRRO']=='') or ($arrPedido[0]['ENDERECO']=='') or ($arrPedido[0]['CEP']=='')):
                            $this->m_msg = "Preencha campo Endereço no cadastro do cliente! ";
                            throw new Exception( $this->m_msg );
                        endif;
                        
                        $numPedido = $arrPedido[0]['PEDIDO'];
                        $sitPedido = $arrPedido[0]['SITUACAO'];
                        

                        // $parametros = new c_banco;
                        // $parametros->setTab("EST_NAT_OP");
                        // $arrNatOp = $parametros->getRecord("ID=".$arrPedido[0]['IDNATOP']);

                        $transaction = new c_banco();
                        $transaction->inicioTransacao($transaction->id_connection);
                        $result = true;

                        $this->setCondPg($arrPedido[0]['CONDPG']);
                        
                        // BUSCA PARCELAS FORM
                        $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
                        $arrParcelas = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'));
                        
                        $this->setTotal(isset($this->parmPost['total']) ? $this->parmPost['total'] : '');
                
                        $arrItemPedido = $this->select_pedido_item_id('1');
                        if (!is_array($arrItemPedido)):
                            $this->m_msg = "Não existem produtos no pedido: ".$this->getId();
                            $result = false;
                            throw new Exception( $this->m_msg );
                        endif;
                        
                        $objProdutoEstoque = new c_produto_estoque();
                        
                        $objNotaFiscal = new c_nota_fiscal();
                        
                        if ($objNotaFiscal->existeNotaFiscalPedido($numPedido) == true):
                            $this->m_msg = "Já existe nota fiscal autorizada para este pedido: ".$this->getId();
                            $result = false;
                            throw new Exception( $this->m_msg );
                        endif;

                        $modeloNf = $arrPedido[0]['MODELONF'];
                        
                        $objNotaFiscal->setModelo($modeloNf);
                        $objNotaFiscal->setSerie($this->getSerie());
                        
                        $numNf = 0;
                        
                        $objNotaFiscal->setNumero($numNf); 
                        $objNotaFiscal->setPessoa($this->getCliente()); 
                        $objNotaFiscal->setNomePessoa();
                        $objNotaFiscal->setEmissao(date("d/m/Y H:i:s"));
                        $objNotaFiscal->setIdNatop($this->getIdNatop());            
                        // $objNotaFiscal->setIdNatop($arrPedido[0]['IDNATOP']);            
                        $objNotaFiscal->setTipo('1');
                        $objNotaFiscal->setSituacao('A');                                
                        $objNotaFiscal->setCondPgto($this->getCondPg());
                        $objNotaFiscal->setFormaPgto($this->getFormaPgto());
                        $objNotaFiscal->setDataSaidaEntrada(date("d/m/Y H:i:s"));
                        $objNotaFiscal->setFormaEmissao('N');
                        $objNotaFiscal->setFinalidadeEmissao('1');
                        $objNotaFiscal->setCentroCusto(isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto);
                        $objNotaFiscal->setGenero($arrPedido[0]['GENERO']);//====????
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
                        $objNotaFiscal->setObs($this->obs);
                        $objNotaFiscal->setOrigem('PED');
                        $objNotaFiscal->setDoc($numPedido);                                
                        $objNotaFiscal->setFrete((isset($arrPedido[0]['FRETE']) ? $arrPedido[0]['FRETE'] : $this->frete),true);
                        
                        $objNotaFiscal->setFinalidadeEmissao(isset($this->parmPost['finalidadeEmissao']) ? $this->parmPost['finalidadeEmissao'] : "");
                        $objNotaFiscal->setNfeReferenciada(isset($this->parmPost['nfeReferenciada']) ? $this->parmPost['nfeReferenciada'] : "");
                
                        
                        $idGerado = $objNotaFiscal->incluiNotaFiscal($transaction->id_connection);
                        
                        if (intval($idGerado)==0):
                            $this->m_msg = $idGerado;
                            $result = false;
                            throw new Exception( $this->m_msg );
                        endif;

                        $objNfProduto = new c_nota_fiscal_produto();
                        for ($i = 0; $i < count($arrItemPedido); $i++) {
                            $arrItemPedido[$i]['QUANTIDADE'] == null || $arrItemPedido[$i]['QUANTIDADE'] == '' ? 
                            $arrItemPedido[$i]['QUANTIDADE'] = $arrItemPedido[$i]['QTSOLICITADA'] : $arrItemPedido[$i]['QUANTIDADE'];
                            $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QUANTIDADE']*$arrItemPedido[$i]['UNITARIO']; //QTSOLICITADO - ALTERADO 18/07/2019 
                            $objNfProduto->setIdNf($idGerado);
                            $objNfProduto->setCodProduto($arrItemPedido[$i]['ITEMESTOQUE']);
                            $objNfProduto->setDescricao($arrItemPedido[$i]['DESCRICAO']);
                            $objNfProduto->setUnidade($arrItemPedido[$i]['UNIDADE']);
                            $objNfProduto->setQuant($arrItemPedido[$i]['QUANTIDADE'], true); 
                            $objNfProduto->setUnitario($arrItemPedido[$i]['UNITARIO'], true);
                            $objNfProduto->setDesconto($arrItemPedido[$i]['DESCONTO'], true); 
                            $objNfProduto->setTotal($arrItemPedido[$i]['TOTAL'], true);
                            
                            if ($arrItemPedido[$i]['ORIGEM'] == ''):
                                $produto = new c_banco;
                                $produto->setTab("EST_PRODUTO");
                                $origemProduto = $produto->getField("ORIGEM", "CODIGO=".$arrItemPedido[$i]['ITEMESTOQUE']);
                                $produto->close_connection();
                                if($origemProduto == ''){
                                    $this->m_msg = "Preencha campo origem no cadastro de produto! produto:".$arrItemPedido[$i]['DESCRICAO'];
                                    throw new Exception( $this->m_msg );
                                }else{
                                    $objNfProduto->setOrigem($origemProduto);
                                }
                            else:
                                $objNfProduto->setOrigem($arrItemPedido[$i]['ORIGEM']);
                            endif;                                    

                            if ($arrItemPedido[$i]['TRIBICMS'] == ''):
                                $produto = new c_banco;
                                $produto->setTab("EST_PRODUTO");
                                $tribIcmsProduto = $produto->getField("TRIBICMS", "CODIGO=".$arrItemPedido[$i]['ITEMESTOQUE']);
                                $produto->close_connection();
                                if($tribIcmsProduto == ""){
                                    $this->m_msg = "Preencha campo tribicms no cadastro de produto! produto:".$arrItemPedido[$i]['DESCRICAO'];
                                    throw new Exception( $this->m_msg );
                                }else{
                                    $objNfProduto->setTribIcms($tribIcmsProduto);   
                                }
                            else:
                                $objNfProduto->setTribIcms($arrItemPedido[$i]['TRIBICMS']);
                            endif;                                    
                            
                            if ($arrItemPedido[$i]['NCM'] == ''):
                                $produto = new c_banco;
                                $produto->setTab("EST_PRODUTO");
                                $ncmProduto = $produto->getField("NCM", "CODIGO=".$arrItemPedido[$i]['ITEMESTOQUE']);
                                $produto->close_connection();
                                if($ncmProduto == ""){
                                    $this->m_msg = "Preencha campo NCM no cadastro de produto! produto:".$arrItemPedido[$i]['DESCRICAO'];
                                    throw new Exception( $this->m_msg );
                                }else{
                                    $objNfProduto->setNcm($ncmProduto);
                                }
                            else:    
                                $objNfProduto->setNcm($arrItemPedido[$i]['NCM']);
                            endif;
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

                            $objNfProduto->setNrSerie(''); 
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
                            
                            if (is_string($result)):
                                $this->m_msg = $result;
                                $result = false;
                                throw new Exception( $this->m_msg );
                            endif;
                            
                        } //for
                        $validaNfAuto='S';
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

                            try{
                                $exporta = new p_nfe_40();
                                $result = $exporta->Gera_XML($idGerado, $this->m_empresacentrocusto, '', $transaction->id_connection);
                                $cStatus = $result['cStatus'];
                                //$cStatus = '100';
                                if ($cStatus == '100'):

                                    $this->atualizarFieldPedido(9);
                                    $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo']);

                                    $transaction->commit($transaction->id_connection);

                                    $printDanfe = new p_nfephp_imprime_danfe();
                                    if ($this->m_opcao ==''):
                                        $printDanfe->printDanfe($idGerado, $objNotaFiscal->getDoc(), $objNotaFiscal->getOrigem(), $result['cDanfe'], $objNotaFiscal->getDoc(), 'pedido_venda_nf');
                                    else:    
                                        $printDanfe->printDanfe($idGerado, $objNotaFiscal->getDoc(), $objNotaFiscal->getOrigem(), $result['cDanfe'], $objNotaFiscal->getDoc(), $this->m_opcao);
                                    endif;
                                else:    
                                    // $transaction->rollback($transaction->id_connection);    
                                    // $this->desenhaCadastroPedido('Nota Fiscal Não Gerada, Identificador: '.$idGerado);
                                    $this->atualizarFieldPedido(9);
                                    $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo'], $result['chave'], $result['codSituacao']);

                                    //; commit transação
                                    $transaction->commit($transaction->id_connection);

                                    $this->desenhaCadastroPedido('Nota Fiscal Gerada com Situação: '.$result['situacao'].'<br> Consultar em Notas Fiscais para finalizar o processo de emissão, Identificador: '.$idGerado."<br>Status: ".$result['cstat']."<br>Motivo:".$result['motivo']);
                                endif;

                            }catch(Exception $e){
                                $transaction->rollback($transaction->id_connection);    
                                $this->m_msg = "Falha ao Gerar NF ".$e->getMessage();
                                throw new Exception( $this->m_msg );
                            }
                            /*
                            $exporta = new p_nfe_40();
                            $result = $exporta->Gera_XML($idGerado, $this->m_empresacentrocusto, '', $transaction->id_connection);
                            $cStatus = $result['cStatus'];
                            //$cStatus = '100';
                            if ($cStatus == '100'):

                                $this->atualizarFieldPedido(9);

                                $transaction->commit($transaction->id_connection);

                                $printDanfe = new p_nfephp_imprime_danfe();
                                if ($this->m_opcao ==''):
                                    $printDanfe->printDanfe($idGerado, $objNotaFiscal->getDoc(), $objNotaFiscal->getOrigem(), $result['cDanfe'], $objNotaFiscal->getDoc(), 'pedido_venda_nf');
                                else:    
                                    $printDanfe->printDanfe($idGerado, $objNotaFiscal->getDoc(), $objNotaFiscal->getOrigem(), $result['cDanfe'], $objNotaFiscal->getDoc(), $this->m_opcao);
                                endif;
                            else:    
                                $transaction->rollback($transaction->id_connection);    
                                $this->desenhaCadastroPedido('Nota Fiscal Não Gerada, Identificador: '.$idGerado);
                            endif;
                            */
                        else:
                            $transaction->commit($transaction->id_connection);
                            $this->mostraPedido('Nota Fiscal Gerada, Identificador: '.$idGerado);
                        endif;

                        } 
                break;
            case 'NFE':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                        $this->desenhaCadastroPedido(null,null,'NFE');                        
                }
                break;
            case 'cadastrarPed':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    
                    $transaction = new c_banco();
                    $transaction->inicioTransacao($transaction->id_connection);

                    $novoCcustoEntrega = $this->getCentroCusto();

                    $arrPedido = $this->select_pedidoVenda();

                    if($this->getCentroCustoEntrega() != $novoCcustoEntrega){
                        $ped = $arrPedido[0]['PEDIDO'];
                        $pedItens = $this->select_todos_pedidos_item($ped);
                        $objProdutoEstoque = new c_produto_estoque();
                        for($i = 0; $i < count($pedItens); $i++){
                            $produto = new c_banco;
                            $produto->setTab("EST_PRODUTO");
                            $result = $produto->getField("UNIFRACIONADA", "CODIGO=".$pedItens[$i]['ITEMESTOQUE']);
                            // reserva produto
                            if ($result == "N"){
                                //remove reserva
                                $objProdutoEstoque->produtoReservaExclui($this->getCentroCustoEntrega(), "PED", 
                                            $pedItens[$i]['ID'], $pedItens[$i]['ITEMESTOQUE'], 
                                            abs($pedItens[$i]['QTSOLICITADA']),$transaction->id_connection);
                                    
                                //adiciona reserva
                                $objProdutoEstoque->produtoReserva($novoCcustoEntrega, "PED", 
                                $pedItens[$i]['ID'], $pedItens[$i]['ITEMESTOQUE'], (int) $pedItens[$i]['QTSOLICITADA'], $transaction->id_connection);
                            }
                        }
                        
                        $this->atualizarField('CENTROCUSTOENTREGA', $novoCcustoEntrega);

                    }

                    $pedAprovado = new c_pedido_aprovacao(); 
                    $pedAprovado->pedido_aprovado($this->getId());

                    $this->setTotal(isset($this->parmPost['total']) ? $this->parmPost['total'] : '');                         
              
                    $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
                    
                    $arrParcelas = $this->formParcelasNfeFinanceiro($this->dadosFinanceiros, $this->getTotal('B'), $this->parmPost['condPgto']);
                    
                    //$arrParcelas = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'), $this->parmPost['condPgto']);
                    $this->atualizarField('situacao', '6'); //SIT PEDIDO
                    
                    $sql = "SELECT SITUACAO FROM FAT_PEDIDO ";
                    $sql.= "WHERE ID = '".$this->getId()."';";
                    $banco = new c_banco;
                    
                    for ($i = 0; $i < 4; $i++) {
                        $banco->exec_sql($sql, $transaction->id_connection);    
                        $result = $banco->resultado;
                        $banco->close_connection();
                        if ( $result[0]['SITUACAO'] != '6' ){
                            $this->atualizarField('situacao', '6');
                            $situacao = true;   
                        } else {
                            $situacao = false;
                            break;
                        } 
                    }
                    if ($situacao) {
                        $this->m_msg = "Problemas com informação no pedido. Tente refaturar novamente";
                        throw new Exception( $this->m_msg );
                    }
                    
                    /**
                        *   verifica nos lancamentos se tem pedido com parcelas em aberto 
                        *    
                        */
                    $sql = "SELECT * FROM FIN_LANCAMENTO WHERE DOCTO = ".$this->getId()." AND ORIGEM = 'PED' AND SITPGTO <> 'B'";
                    $banco = new c_banco();
                    $banco->exec_sql($sql);
                    $lancAberto = $banco->resultado;
                    if(is_array($lancAberto)){
                        for($i = 0; $i < count($lancAberto); $i++){
                            $sql = "DELETE FROM FIN_LANCAMENTO_RATEIO ";
                            $sql .= "WHERE ID = '".$lancAberto[$i]['ID']."';";
                            $banco = new c_banco;
                            $banco->exec_sql($sql);
                            $banco->close_connection();

                            $sql  = "DELETE FROM FIN_LANCAMENTO ";
                            $sql .= "WHERE ID = '".$lancAberto[$i]['ID']."';";
                            $banco = new c_banco;
                            $banco->exec_sql($sql);
                            $banco->close_connection();
                        }
                    }
                    
                    $objFinanceiro = new c_lancamento();
                    
                    $pedido = $arrPedido[0]['PEDIDO'];

                    $arrParamFin['PESSOA'] = $arrPedido[0]['CLIENTE'];
                    $arrParamFin['DOCTO'] = $pedido;
                    $arrParamFin['SERIE'] = 'PED';
                    $arrParamFin['GENERO'] = $this->getGenero();
                    $arrParamFin['CENTROCUSTO'] = isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto;
                    $arrParamFin['USER'] = $this->m_userid;
                    $arrParamFin['ORIGEM'] = "PED";
                    $arrParamFin['NUMLCTO'] = $pedido;
                    $arrParamFin['TIPOLANCAMENTO'] = "R";
                    $arrParamFin['OBS'] =  $objNotaFiscal->getObs();

                    $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $transaction->id_connection);
                    
                    //$arrCredito = $objFinanceiro->selecTableCredito($arrPedido[0]['CLIENTE']);
                    //$objFinanceiro->updateTableCredito($arrPedido[0]['CLIENTE'], $arrPedido[0]['CREDITO'], $pedido, $arrCredito,$transaction->id_connection, $this->getCredito());
                    $arrCredito = $objFinanceiro->selectSaldoCliente($arrPedido[0]['CLIENTE']);
                    $objFinanceiro->newUpdateTableCredito($arrPedido[0]['CLIENTE'], $arrPedido[0]['CREDITO'], $pedido, $arrCredito,$transaction->id_connection, $this->getCredito('B'));
                                        
                    $obs = $this->obs;
                    if ($arrPedido[0]['SITUACAO'] == '11'){
                        $this->atualizarFieldPedidoNEW('11',$this->parmPost['condPgto'],$obs,$this->getGenero(),$this->getIdNatop(),'',$transaction->id_connection);                    
                    } else {
                        // ATUALIZA NOVAMENTE SITUAÇÃO PARA 6 ( PEDIDO )
                        $this->atualizarFieldPedidoNEW('6',$this->parmPost['condPgto'],$obs,$this->getGenero(),$this->getIdNatop(),'',$transaction->id_connection);
                    }
                    
                    $transaction->commit($transaction->id_connection);
                    
                }          
                break;
            case 'cadastrarCOTPed':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    // busca itens do pedido para conferencia de quantidade e total  
                    $arrItemPedido = $this->select_pedido_item();
                    if (!is_array($arrItemPedido)){
                        $msg = "Não existem produtos no pedido: ".$this->getId();}
                    else{
                        // select pedido
                        $arrPedido = $this->select_pedidoVenda();
                        for ($i = 0; $i < count($arrItemPedido); $i++) {
                            $totalItens += $arrItemPedido[$i]['TOTAL'];
                        }
                        // $totalItens = $totalItens - $arrPedido[0]['DESCONTO'] 
                        //    + $arrPedido[0]['FRETE'] + $arrPedido[0]['DESPACESSORIAS'];
                        $total = $arrPedido[0]['TOTAL'] + $arrPedido[0]['DESCONTO'] 
                           - $arrPedido[0]['FRETE'] - $arrPedido[0]['DESPACESSORIAS'];
                        $total1 = $arrPedido[0]['TOTAL'];
                        $desc = $arrPedido[0]['DESCONTO']; 
                        $frete = $arrPedido[0]['FRETE'];
                        $acess = $arrPedido[0]['DESPACESSORIAS'];
                        if (strval($total) == strval($totalItens))
                            $this->desenhaCadastroPedido(null,null,'COTACAO');
                        else{
                            $msg = "Total pedido difere do Total itens: Pedido: ".$this->getId();

                            $this->mostraPedido($msg);
                        }    
                    }
                }
                break;
                case 'cadastrarPedAlteracao':
                    if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                        $pedAprovado = new c_pedido_aprovacao(); 
                        $pedAprovado->pedido_aprovado($this->getId());
    
                        $transaction = new c_banco();
                        $transaction->inicioTransacao($transaction->id_connection);
    
                        $arrPedido = $this->select_pedidoVenda();
    
                        $this->setTotal(isset($this->parmPost['total']) ? $this->parmPost['total'] : '');                         
                  
                        $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
                        
                        $arrParcelas = $this->parcelamentoNfeFinanceiro($this->dadosFinanceiros, $this->getTotal('B'), $this->parmPost['condPgto']);
                        
                        //$arrParcelas = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'), $this->parmPost['condPgto']);
                        $this->atualizarField('situacao', '6');
                        
                        $sql = "SELECT SITUACAO FROM FAT_PEDIDO ";
                        $sql.= "WHERE ID = '".$this->getId()."';";
                        $banco = new c_banco;
                        
                        for ($i = 0; $i < 4; $i++) {
                            $banco->exec_sql($sql, $transaction->id_connection);    
                            $result = $banco->resultado;
                            $banco->close_connection();
                            if ( $result[0]['SITUACAO'] != '6' ){
                                $this->atualizarField('situacao', '6');
                                $situacao = true;   
                            } else {
                                $situacao = false;
                                break;
                            } 
                        }
                        if ($situacao) {
                            $this->m_msg = "Problemas com informação no pedido. Tente refaturar novamente";
                            throw new Exception( $this->m_msg );
                        }     
                        
                        /**
                        *   verifica nos lancamentos se tem pedido com parcelas em aberto 
                        *    
                        */
                        $sql = "SELECT * FROM FIN_LANCAMENTO WHERE DOCTO = ".$this->getId()." AND ORIGEM = 'PED' AND SITPGTO <> 'B'";
                        $banco = new c_banco();
                        $banco->exec_sql($sql);
                        $lancAberto = $banco->resultado;
                        if(is_array($lancAberto)){
                            for($i = 0; $i < count($lancAberto); $i++){
                                $sql = "DELETE FROM FIN_LANCAMENTO_RATEIO ";
                                $sql .= "WHERE ID = '".$lancAberto[$i]['ID']."';";
                                $banco = new c_banco;
                                $banco->exec_sql($sql);
                                $banco->close_connection();

                                $sql  = "DELETE FROM FIN_LANCAMENTO ";
                                $sql .= "WHERE ID = '".$lancAberto[$i]['ID']."';";
                                $banco = new c_banco;
                                $banco->exec_sql($sql);
                                $banco->close_connection();
                            }
                        }
    
                        $objFinanceiro = new c_lancamento();
                        
                        $pedido = $arrPedido[0]['PEDIDO'];
    
                        $arrParamFin['PESSOA'] = $arrPedido[0]['CLIENTE'];
                        $arrParamFin['DOCTO'] = $pedido;
                        $arrParamFin['SERIE'] = 'PED';
                        $arrParamFin['GENERO'] = $this->getGenero();
                        $arrParamFin['CENTROCUSTO'] = isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto;
                        $arrParamFin['USER'] = $this->m_userid;
                        $arrParamFin['ORIGEM'] = "PED";
                        $arrParamFin['NUMLCTO'] = $pedido;
                        $arrParamFin['TIPOLANCAMENTO'] = "R";
                        $arrParamFin['OBS'] =  $arrPedido[0]['OBS'];

                        $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $transaction->id_connection);
                        
                        //$arrCredito = $objFinanceiro->selecTableCredito($arrPedido[0]['CLIENTE']);
                        //$objFinanceiro->updateTableCredito($arrPedido[0]['CLIENTE'], $arrPedido[0]['CREDITO'], $pedido, $arrCredito,$transaction->id_connection);
                        $arrCredito = $objFinanceiro->selectSaldoCliente($arrPedido[0]['CLIENTE']);
                        $objFinanceiro->newUpdateTableCredito($arrPedido[0]['CLIENTE'], $arrPedido[0]['CREDITO'], $pedido, $arrCredito,$transaction->id_connection, $this->getCredito('B'));
                        $obs = $this->obs;
                        $this->atualizarFieldPedidoNEW('6',$this->parmPost['condPgto'],$obs,$this->getGenero(),$this->getIdNatop(),'',$transaction->id_connection);
    
                        $transaction->commit($transaction->id_connection);
                        
                    }          
                    break;
            case 'alteraPED':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    // busca itens do pedido para conferencia de quantidade e total  
                    $arrItemPedido = $this->select_pedido_item_id('1');
                    if (!is_array($arrItemPedido)){
                        $msg = "Não existem produtos no pedido: ".$this->getId();}
                    else{
                        // select pedido
                        $arrPedido = $this->select_pedidoVenda();
                        for ($i = 0; $i < count($arrItemPedido); $i++) {
                            $totalItens += $arrItemPedido[$i]['TOTAL'];
                        }
                        $total = $arrPedido[0]['TOTAL'] + $arrPedido[0]['DESCONTO'] 
                           - $arrPedido[0]['FRETE'] - $arrPedido[0]['DESPACESSORIAS'];
                        if ($total == $totalItens)
                            $this->desenhaCadastroPedido(null,null,'COTACAO');
                        else{
                            $msg = "Total pedido difere do Total itens: Pedido: ".$this->getId();
    
                                $this->mostraPedido($msg);
                            }    
                    }
                }
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'cadastraNf':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'S')) {
                try {
                    // busca dados pedido e cliente
                    //$this->setPedidoVenda(); // seta dados pedido e cliente
                    $arrPedido = $this->select_pedidoVenda();
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
                    $objNotaFiscal->setTransportador($this->transportador);
                    $objNotaFiscal->setVolume($this->volume);
                    $objNotaFiscal->setVolEspecie($this->volEspecie);
                    $objNotaFiscal->setVolMarca($this->volMarca);
                    $objNotaFiscal->setVolPesoLiq($this->volPesoLiq);
                    $objNotaFiscal->setVolPesoBruto($this->volPesoBruto);
                    $objNotaFiscal->setObs($this->obs);
                    $objNotaFiscal->setOrigem('PED');
                    $objNotaFiscal->setDoc($numPedido);

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
                        $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QUANTIDADE']*$arrItemPedido[$i]['UNITARIO']; //QTSOLICITADO - ALTERADO 18/07/2019 
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
                    
                    $this->alteraPedidoSituacao($this->getCondPg(), $transaction->id_connection);
                    
                    // ************** 
                    // lanca parcelas financeiro
                    //***************
                    $objFinanceiro = new c_lancamento();

                    $arrParamFin['PESSOA'] = $objNotaFiscal->getPessoa();
                    $arrParamFin['DOCTO'] = $numPedido;
                    $arrParamFin['SERIE'] = 'PED';
                    $arrParamFin['GENERO'] = $this->getGenero();
                    $arrParamFin['CENTROCUSTO'] = isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto;
                    $arrParamFin['USER'] = $this->m_userid;
                    $arrParamFin['ORIGEM'] = "PED";
                    $arrParamFin['NUMLCTO'] = $numPedido;
                    $arrParamFin['TIPOLANCAMENTO'] = "R";
                    $arrParamFin['OBS'] =  $objNotaFiscal->getObs();

                    if ($integraFin == 'S'):
                        $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $transaction->id_connection);
                    endif;

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
                            $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo']);

                            //; commit transação
                            $transaction->commit($transaction->id_connection);

                            $printDanfe = new p_nfephp_imprime_danfe();
                            if ($this->m_opcao ==''):
                                $printDanfe->printDanfe($idGerado, $objNotaFiscal->getDoc(), $objNotaFiscal->getOrigem(), $result['cDanfe'], $objNotaFiscal->getDoc(), 'pedido_venda_nf');
                            else:    
                                $printDanfe->printDanfe($idGerado, $objNotaFiscal->getDoc(), $objNotaFiscal->getOrigem(), $result['cDanfe'], $objNotaFiscal->getDoc(), $this->m_opcao);
                            endif;
                        else:    
                            // roollback transação
                            //$transaction->rollback($transaction->id_connection);   
                            
                            $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo'], $result['codSituacao']);

                            //; commit transação
                            $transaction->commit($transaction->id_connection);
                            $this->desenhaCadastroPedido('Nota Fiscal Gerada com Situação: '.$result['situacao'].'<br> Consultar em Notas Fiscais para finalizar o processo de emissão, Identificador: '.$idGerado."<br>Status: ".$result['cstat']."<br>Motivo:".$result['motivo']);
                        endif;

                    else:
                        //; commit transação
                        $transaction->commit($transaction->id_connection);
                        $this->mostraPedido('Nota Fiscal Gerada, Identificador: '.$idGerado);
                    endif;

                    
                } catch (Error $e) {
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
            default:
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg = NULL, $tela = NULL) {

        $descCondPgto = "";
        $parcelas = 0;
        $valorParcelas = 0;
        $totalParcelas = 0;
        $numParcelas = 0;
        $fin = [];
        $credito = $this->getCredito();
        //$pedido = $this->select_pedidoVenda();
        $this->setPedidoVenda(); // seta dados pedido e cliente
        
        //parametro de pesquisa
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $cfop = $parametros->getParametros("CFOP", 
            " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
        $natOperacao = $parametros->getParametros("NATOPERACAO", 
            " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
        $condPgto = $this->getCondPg();
        if ($condPgto == 0):
            $condPgto = $parametros->getParametros("CONDPGTO", 
            " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
        endif;
        $genero = $parametros->getParametros("GENERO", 
            " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
        $conta = $parametros->getParametros("CONTA", 
            " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
        $serie = $parametros->getParametros("SERIE", 
            " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
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
        $this->setCondPg(isset($this->parmPost['condPgto']) ? $this->parmPost['condPgto'] : $condPgto);
        $this->setGenero(isset($this->parmPost['genero']) ? $this->parmPost['genero'] : $genero);
        $this->setContaDeposito(isset($this->parmPost['conta']) ? $this->parmPost['conta'] : $conta);
        $this->totalCredito = $this->parmPost['totalCredito'];
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('pedido', $this->getPedido());
        $this->smarty->assign('cliente', $this->getCliente());
        $this->smarty->assign('data', $this->getEmissao('F'));
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('natOperacao', $this->getIdNatop());
        $this->smarty->assign('serie', $this->getSerie());
        $this->smarty->assign('clienteNome', $this->getClienteNome());
        
        //$credito = $this->getCredito('F');
        if ( $credito > 0) {
            // GET TOTALCREDITO
            $banco = new c_banco;
            $banco->setTab("FIN_CLIENTE_CREDITO");
            $totalCredito = $banco->getField("(SUM(VALOR)-SUM(UTILIZADO))", "CLIENTE = ".$this->getCliente()." AND ISNULL(PEDIDOUTILIZADO)");
            $banco->close_connection(); 
            
            $totalCredito != "" ? $totalCredito = number_format((double) $totalCredito, 2, ',', '.') : $totalCredito = 0;

            $this->smarty->assign('totalCredito', $totalCredito);

            $this->smarty->assign('exibircredito', true);
            $this->smarty->assign('credito', $credito);
            /* 
            $total = $this->getTotal();
            $total = $total - $credito;
            $this->setTotal($total);
            $this->smarty->assign('total', $this->getTotal('F')); 
            */
        } else {
            $this->smarty->assign('exibircredito', false);
            $this->smarty->assign('credito', 0);
            $this->smarty->assign('totalCredito', 0);
        }
        
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

        // add numero do pedido
        $this->obs == '' ? $this->obs = "Pedido Número: ".$this->getPedido() : '';  
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
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoDoctoPgto') ";
        $sql.= "and (( tipo = 'N') or ( tipo = 'B') or ( tipo = 'D') or ( tipo = 'C') or ( tipo = 'E') or ( tipo = 'A') or ( tipo = 'K') or ( tipo = 'X') or ( tipo = 'P'))";
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
        $sql = "select tipo as id, padrao as descricao ";
        $sql .="from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto') ";
        if ($this->verificaDireitoUsuario('FINGERARDOCSOMENTEEMABERTO', 'S', 'N')){
            $sql .="AND (TIPO = 'A') ";
        };
        
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
        if($tela == 'NFE' AND ($this->getCentroCusto() != $this->getCentroCustoEntrega())){
            $ccusto = new c_banco;
            $ccusto->setTab("FIN_CENTRO_CUSTO");
            $result = $ccusto->getField("DESCRICAO", "CENTROCUSTO=".$this->getCentroCusto());
            $ccusto->close_connection();

            $ccustoEnt = new c_banco;
            $ccustoEnt->setTab("FIN_CENTRO_CUSTO");
            $res = $ccustoEnt->getField("DESCRICAO", "CENTROCUSTO=".$this->getCentroCustoEntrega());
            $ccustoEnt->close_connection();

            $centroCusto_ids[0]     = $this->getCentroCusto();
            $centroCusto_names[0]   = $this->getCentroCusto() ."-".$result;
            $centroCusto_ids[1]     = $this->getCentroCustoEntrega();
            $centroCusto_names[1]   = $this->getCentroCustoEntrega() ." - ". $res;

            $disabledCCusto = false;
        }else{

            $consulta = new c_banco();
            $sql = "SELECT CentroCusto AS ID, DESCRICAO FROM fin_centro_custo ORDER BY CentroCusto";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            for ($i = 0; $i < count($result); $i++) {
                $centroCusto_ids[$i] = $result[$i]['ID'];
                $centroCusto_names[$i] = $result[$i]['ID']." - ".$result[$i]['DESCRICAO'];
            }

            $disabledCCusto = true;
        }
        $this->smarty->assign('disabledCCusto', $disabledCCusto);

        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto());
        
        if (isset($this->parmPost['usrAprovacao'])){
            $this->setUsrAprovacao($this->parmPost['usrAprovacao']);
        }
        $this->smarty->assign('usrAprovacao', $this->getUsrAprovacao());
        
        $consulta = new c_banco();
        $sql = "select integrafin from est_nat_op where id = ".$this->getIdNatop();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        // CALCULA PARCELAS
        if ($result[0]['INTEGRAFIN'] == 'S') {
            $fin = $this->calculaParcelasNfe($descCondPgto, $this->getTotal(), $this->numParcelaAdd, $this->getCredito('B') );
        } else {
            $fin = '';
            $this->numParcelaAdd = 0;
        }
        $this->smarty->assign('INTEGRAFIN',$result[0]['INTEGRAFIN']);
        $this->smarty->assign('numParcelaAdd', $this->numParcelaAdd);
        
        $this->smarty->assign('fin', $fin);

        // finalidade emissao
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='FinalidadeEmissao')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $finalidadeEmissao_ids[$i] = $result[$i]['ID'];
            $finalidadeEmissao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('finalidadeEmissao_ids', $finalidadeEmissao_ids);
        $this->smarty->assign('finalidadeEmissao_names', $finalidadeEmissao_names);
        $this->smarty->assign('finalidadeEmissao_id', 0);

            if ($tela == 'NFE') {

                $sql = "SELECT * FROM FAT_PEDIDO_ITEM WHERE ID = '".$this->getId()."';";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();    
                $itens = $banco->resultado;
                $this->smarty->assign('itens', $itens);

                $sql = "SELECT PARCELA, VENCIMENTO, TOTAL AS VALOR, ";
                $sql .= "TIPODOCTO, CONTA, SITPGTO, OBS ";
                $sql .= "FROM FIN_LANCAMENTO ";
                $sql .= "WHERE (DOCTO = '".$this->getId()."') and ";
                $sql .= "(SERIE = 'PED') and ";
                $sql .= "(TIPOLANCAMENTO = 'R') and ";
                $sql .= "(CENTROCUSTO = '".$this->getCentroCusto()."'); ";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();    
                $fin = $banco->resultado;
                $this->smarty->assign('fin', $fin);

                $this->smarty->display('pedido_venda_nfe_cadastro.tpl');
            }else if ($tela == 'COTACAO') {
                $this->smarty->display('pedido_venda_cotacao_pedido_cadastro.tpl');
            } else {
                $this->smarty->display('pedido_venda_nf_cadastro.tpl');
            }
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
function desenhaCadastroFaturamentoLanc($mensagem = NULL, $tipoMsg = NULL, $tela = NULL) {

    $descCondPgto = "";
    $parcelas = 0;
    $valorParcelas = 0;
    $totalParcelas = 0;
    $numParcelas = 0;
    $fin = [];
    $credito = $this->getCredito();

    
    //$pedido = $this->select_pedidoVenda();
    $this->setPedidoVenda(); // seta dados pedido e cliente
    
    //parametro de pesquisa
    $parametros = new c_banco;
    $parametros->setTab("EST_PARAMETRO");
    $cfop = $parametros->getParametros("CFOP", 
        " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
    $natOperacao = $parametros->getParametros("NATOPERACAO", 
        " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
    $condPgto = $this->getCondPg();
    if ($condPgto == 0):
        $condPgto = $parametros->getParametros("CONDPGTO", 
        " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
    endif;
    $genero = $parametros->getParametros("GENERO", 
        " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
    $conta = $parametros->getParametros("CONTA", 
        " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
    $serie = $parametros->getParametros("SERIE", 
        " WHERE CENTROCUSTO = '".$this->getCentroCusto()."'");
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
    $this->setCondPg(isset($this->parmPost['condPgto']) ? $this->parmPost['condPgto'] : $condPgto);
    $this->setGenero(isset($this->parmPost['genero']) ? $this->parmPost['genero'] : $genero);
    $this->setContaDeposito(isset($this->parmPost['conta']) ? $this->parmPost['conta'] : $conta);

    
    $this->smarty->assign('pathCliente', ADMhttpCliente);
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('opcao', $this->m_opcao);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);

    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('pedido', $this->getPedido());
    $this->smarty->assign('cliente', $this->getCliente());
    $this->smarty->assign('data', $this->getEmissao('F'));
    $this->smarty->assign('total', $this->getTotal('F'));
    $this->smarty->assign('natOperacao', $this->getIdNatop());
    $this->smarty->assign('serie', $this->getSerie());
    $this->smarty->assign('clienteNome', $this->getClienteNome());
    
    if ( $credito > 0) {
        // GET TOTALCREDITO
        $banco = new c_banco;
        $banco->setTab("FIN_CLIENTE_CREDITO");
        $totalCredito = $banco->getField("(SUM(VALOR)-SUM(UTILIZADO))", "CLIENTE = ".$this->getCliente()." AND ISNULL(PEDIDOUTILIZADO)");
        $banco->close_connection(); 
        
        $totalCredito != "" ? $totalCredito = number_format((double) $totalCredito, 2, ',', '.') : $totalCredito = 0;

        $this->smarty->assign('totalCredito', $totalCredito);

        $this->smarty->assign('exibircredito', true);
        $this->smarty->assign('credito', $credito);
    } else {
        $this->smarty->assign('exibircredito', false);
        $this->smarty->assign('credito', 0);
        $this->smarty->assign('totalCredito', 0);
    }
    
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
    $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero where (tipolancamento = 'R') ORDER BY descricao;";
    $this->comboSql($sql, $this->getGenero(), $this->getGenero(), $genero_ids, $genero_names);
    $this->smarty->assign('genero_ids', $genero_ids);
    $this->smarty->assign('genero_names', $genero_names);
    $this->smarty->assign('genero_id', $this->getGenero());

    // COMBOBOX CONTA
    $sql = "SELECT conta as id, nomeinterno as descricao FROM fin_conta where status ='A' ORDER BY nomeinterno;";
    $this->comboSql($sql, $this->getContaDeposito(), $this->getContaDeposito(), $conta_ids, $conta_names);
    $this->smarty->assign('conta_ids', $conta_ids);
    $this->smarty->assign('conta_names', $conta_names);
    $this->smarty->assign('conta_id', $this->getContaDeposito());

    // tipo documento
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoDoctoPgto') ";
    $sql.= "and (( tipo = 'N') or ( tipo = 'B') or ( tipo = 'D') or ( tipo = 'C') or ( tipo = 'E') or ( tipo = 'A') or ( tipo = 'K'))";
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
    $sql = "select tipo as id, padrao as descricao ";
    $sql .="from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto') ";
    if ($this->verificaDireitoUsuario('FINGERARDOCSOMENTEEMABERTO', 'S', 'N')){
        $sql .="AND (TIPO = 'A') ";
    };
    
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
    $sql = "SELECT CentroCusto AS ID, DESCRICAO FROM fin_centro_custo ORDER BY CentroCusto";
    $this->comboSql($sql, $this->getCentroCusto(), $this->getCentroCusto(), $centroCusto_ids, $centroCusto_names);
    $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
    $this->smarty->assign('centroCusto_names', $centroCusto_names);
    $this->smarty->assign('centroCusto_id', $this->getCentroCusto());
    
    if (isset($this->parmPost['usrAprovacao'])){
        $this->setUsrAprovacao($this->parmPost['usrAprovacao']);
    }
    $this->smarty->assign('usrAprovacao', $this->getUsrAprovacao());
    
    $consulta = new c_banco();
    $sql = "select integrafin from est_nat_op where id = ".$this->getIdNatop();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    

    // CALCULA PARCELAS
    if ($result[0]['INTEGRAFIN'] == 'S') {
        $fin = $this->calculaParcelasAlteraPed($descCondPgto, $this->getTotal(), $this->numParcelaAdd, $this->getCredito('B') );
    } else {
        $fin = '';
        $this->numParcelaAdd = 0;
    }
    $this->smarty->assign('INTEGRAFIN',$result[0]['INTEGRAFIN']);
    $this->smarty->assign('numParcelaAdd', $this->numParcelaAdd);
    
    $this->smarty->assign('fin', $fin);

    // finalidade emissao
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='FinalidadeEmissao')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i = 0; $i < count($result); $i++) {
        $finalidadeEmissao_ids[$i] = $result[$i]['ID'];
        $finalidadeEmissao_names[$i] = $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('finalidadeEmissao_ids', $finalidadeEmissao_ids);
    $this->smarty->assign('finalidadeEmissao_names', $finalidadeEmissao_names);
    $this->smarty->assign('finalidadeEmissao_id', 0);

        
    $this->smarty->display('pedido_venda_nf_cadastro_telhas.tpl');
        
}

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

//fim mostragrupos
//-------------------------------------------------------------
    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i] = $result[$i]['ID'];
            $names[$i] = $result[$i]['DESCRICAO'];
        }
        $param = explode(",", $par);
        $i=0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }    
    }
//-------------------------------------------------------------
}
//	END OF THE CLASS
// Rotina principal - cria classe
//$pedido = new p_pedido_venda_nf($id=null);

//$pedido->controle();
?>
