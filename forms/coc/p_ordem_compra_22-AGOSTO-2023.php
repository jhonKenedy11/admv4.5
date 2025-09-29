<?php
/**
 * @package   astec
 * @name      p_ordem_compra
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Maárcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/06/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/coc/c_ordem_compra.php");
require_once($dir . "/../../class/coc/c_ordem_compra_tools.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir."/../../class/crm/c_conta.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");

require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir."/../../bib/dompdf/lib/html5lib/Parser.php");
require_once($dir."/../../bib/dompdf/lib/php-font-lib-master/src/FontLib/Autoloader.php");
require_once($dir."/../../bib/dompdf/lib/php-svg-lib-master/src/autoload.php");
require_once($dir."/../../bib/dompdf/src/Autoloader.php");
include_once($dir . "/../../bib/c_mail.php");

Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;
//Class p_ordem_compra
Class p_ordem_compra extends c_ordemCompra {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_desconto     = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensQtde    = NULL;
    private $m_natop        = NULL;
    public  $smarty         = NULL;
    private $m_nf           = NULL;
    private $m_serie        = NULL;
    private $m_descCondPgto = NULL;
    public  $m_dadosFinanceiros   = NULL;
    public  $m_itenscotacao = NULL;
    public  $m_basest       = NULL;
    public  $m_st           = NULL;
    public  $m_nfreferenciada  = NULL;
    private $totaisFDS      = NULL;

    private $m_numNf        = NULL;

    //EMAIL VARIAVEIS 
    private $m_destinatario = NULL;
    private $m_comCopiaPara = NULL;
    private $m_assunto = NULL;
    private $m_emailCorpo = NULL;

    private $m_letra_item = NULL;
    private $m_par_item = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //$parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/coc";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_pesq = $parmPost['pesq'];

        $this->m_letra = $parmPost['letra'];
        $this->m_letra_item = $parmPost['letra_item'];
        $this->m_desconto = $parmPost['desconto'];
        $this->m_itensPedido = $parmPost['itensPedido'];
        $this->m_itensQtde = $parmPost['itensQtde'];
        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_parPesq = explode("|", $this->m_pesq);
        $this->m_par_item = explode("|", $this->m_letra_item);

        $this->m_nf = $parmPost['nf'];
        $this->m_numNf = $parmPost['numNf'];
        $this->m_serie = $parmPost['serie'];
        $this->m_descCondPgto = $parmPost['descCondPgto'];
        $this->m_dadosFinanceiros = $parmPost['dadosFinanceiros'];
        $this->m_itenscotacao   = $parmPost['itenscotacao'];
        $this->m_basest   = $parmPost['basest'];
        $this->m_st   = $parmPost['st'];
        $this->m_nfreferenciada  = $parmPost['nfeReferenciada'];

        // Envia Email Ordem de Compra 
        $this->m_destinatario = $parmPost['destinatario'];
        $this->m_comCopiaPara = $parmPost['comCopiaPara'];
        $this->m_assunto = $parmPost['assunto'];
        $this->m_emailCorpo = $parmPost['emailCorpo'];
        $this->m_cod_fabricante = $parmPost['codFabricante'];
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Ordem De Compra");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');
        $this->setIdNatop(isset($parmPost['natop']) ? $parmPost['natop'] : '');
        $this->setCondPg(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setNumeroNf(isset($parmPost['numNf']) ? $parmPost['numNf'] : '');
        $this->setSerie(isset($parmPost['serie']) ? $parmPost['serie'] : '');
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
        $this->setSituacaoCombo(isset($parmPost['situacaoCombo']) ? $parmPost['situacaoCombo'] : '');
        $this->setDataEmissao(isset($parmPost['dataEmissao']) ? $parmPost['dataEmissao'] : '');
        $this->setDataEntrada(isset($parmPost['dataEntrada']) ? $parmPost['dataEntrada'] : date('d/m/Y H:i'));
        $this->setFrete(isset($parmPost['frete']) ? $parmPost['frete'] : "0,00");
        $this->setDespAcessorias(isset($parmPost['despacessorias']) ? $parmPost['despacessorias'] : "0,00");
        $this->setSeguro(isset($parmPost['seguro']) ? $parmPost['seguro'] : "0,00");
        $this->setTotalOc(isset($parmPost['totalOc']) ? $parmPost['totalOc'] : "0,00");

        $this->totaisFDS = array( 
            'FRETE' => $this->getFrete('B'), 
            'SEGURO' => $this->getSeguro('B'), 
            'DESPACESSORIAS' => $this->getDespAcessorias('B')
        );
        
        if (isset($parmPost['pessoa'])):
            $this->setCliente($parmPost['pessoa']);
        else:    
            $this->setCliente('');
        endif;

        
        //parametro de pesquisa
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $genero = $parametros->getParametros("GENERO");
        $parametros->close_connection();
        $this->setGenero(isset($parmPost['genero']) ? $parmPost['genero'] : $genero);
        
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

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastraNf':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'S')) {
                try {
                    $dv = $this->modulo_11(substr($this->m_nfreferenciada,0,
                                ( strlen($num) - 1) ));
                    $dvChaveDigitada = substr($this->m_nfreferenciada, (strlen($this->m_nfreferenciada) - 1), 1);
                    if ($dv != $dvChaveDigitada ){
                        throw new Exception("Digito verificador da chave de acesso inválido!");
                    } 


                    //informações que veio da tela 
                    $condpg = $this->getCondPg();
                    $genero = $this->getGenero() ;
                    $centrocusto = $this->getCentroCusto();
                    $obs = $this->getObs();
                    $dataEntrada = $this->getDataEntrada('F');
                    $dataEmissao = $this->getDataEmissao('F');
                    $serie = $this->getSerie();

                    $arrOC     = $this->select_ordem_compra_id();
                    $arrOCItem = $this->select_ordem_compra_item_id();                     

                    $nf = new c_nota_fiscal();
                    $nf->setModelo('55');
                    $nf->setSerie($this->getSerie());
                    $nf->setNumero($this->getNumeroNf());
                    $nf->setPessoa($arrOC[0]['CLIENTE']);

                    // verifica se existe nf cadastrada para o cliente
                    if ($nf->existeNotaFiscalEntrada() == true){
                        $this->desenhaCadastroOrdemCompraFinanceiro("Numero ".$nf->getNumero()." Nota Fiscal já existente para esse fornecedor", "ERROR");
                        break;
                    }


                    //inicio da transacao
                    $transaction = new c_banco();
                    $transaction->inicioTransacao($transaction->id_connection);

                                       
                    $this->updateSituacao($transaction->id_connection);

                    $nf->setCpfNota($arrOC[0]['CNPJCPFSEMFORMATACAO']);                    
                    $nf->setEmissao($dataEmissao);

                    $nf->setIdNatop($this->getIdNatop()); //?                
                    
                    $nf->setTipo('0'); // 0=Entrada; 1=Saída;
                    $nf->setSituacao('B');
                    $nf->setFormaPgto('0');                    
                    $nf->setDataSaidaEntrada($dataEntrada);                    
                    $nf->setOrigem('COC');//?                    
                    $nf->setDoc($this->getId());
                    $totalNF = str_replace('.', ',', $arrOC[0]['TOTAL']);
                    if ($this->m_st > 0 ){
                        $totalNF += $this->m_st; 
                    }
                    $nf->setTotalnf($totalNF);
                    
                    $nf->setCondPgto($condpg);//?                    
                    $nf->setGenero($genero) ;
                    $nf->setCentroCusto($centrocusto);
                    
        
                    $nf->setFormaEmissao('');
                    $nf->setFinalidadeEmissao('');
                    $nf->setNfeReferenciada($this->m_nfreferenciada);
                    $nf->setModFrete('1');
                    $nf->setTransportador('0');
                    $nf->setPlacaVeiculo('') ;
                    $nf->setVolume('0'); 
                    $nf->setVolEspecie('');
                    $nf->setVolMarca('');
                    $nf->setVolPesoLiq('0');
                    $nf->setVolPesoBruto('0');
                    $nf->setObs($arrOC[0]['OBS']);
                    $nf->setFrete($arrOC[0]['FRETE']);
                    $nf->setDespAcessorias($arrOC[0]['DESPACESSORIAS']);
                    $nf->setSeguro($arrOC[0]['SEGURO']);
                    $nf->setDescontoGeral($arrOC[0]['DESCONTOITENS']);                      
                    $nf->setContrato('');                       
                    $nf->setDataConferencia(date("d/m/Y"));       
        
                    // insere nf
                    $lastNF = $nf->incluiNotaFiscalOC($transaction->id_connection);
                    $this->m_id = $lastNF;

                    //PRODUTOS da nota fiscal                    
                    $nfProduto = new c_nota_fiscal_produto();    
                    
                    $totalNFST = 0;
                    $itens = explode("|", $this->m_itenscotacao);
                    for ($i = 0; $i < count($itens); $i++) {
                        $item = explode("*", $itens[$i + 1]);
                        // if ($item[5] == "10"){
                            $totalNFST += $arrOCItem[$i]['TOTAL'];
                        // } 
                    }
                    
                    //$totalNF = str_replace('.', ',', $arrOC[0]['TOTAL']);

                    $bcSTGeralDist = 0;                    
                    $stGeralDist = 0;
                    for ($i = 0; $i < count($arrOCItem); $i++) {
                        $item = explode("*", $itens[$i + 1]);
                        
                        $vlrST = 0;
                        $vlrBcST = 0;

                        if ($this->m_basest > 0 ) {
                            // $perc = ( str_replace('.', ',', $arrOCItem[$i]['TOTAL']) / $totalNFST) * 100;
                            if ($totalNFST == 0){
                                $perc = 0;
                                $vlrBcST = 0;
                                $vlrST = 0;
                            } else {
                                $perc = ( $arrOCItem[$i]['TOTAL'] / $totalNFST) * 100;
                                $vlrBcST = round(($this->m_basest * ($perc/100)),2);
                                $vlrST = round(($this->m_st * ($perc/100)),2);
                            }
                            
                            $bcSTGeralDist += $vlrBcST;
                            $stGeralDist += $vlrST;

                            if ($i == (count($arrOCItem) - 1)) {
                                if ($bcSTGeralDist > $this->m_basest) {
                                    $vlrBcST = $vlrBcST - ($bcSTGeralDist - $this->m_basest);
                                } else if ($bcSTGeralDist < $this->m_basest) {
                                    $vlrBcST = $$vlrBcST + ($this->m_basest - $bcSTGeralDist);
                                }

                                if ($stGeralDist > $this->m_st) {
                                    $vlrST = $vlrBcST - ($stGeralDist - $this->m_st);
                                } else if ($bcSTGeralDist < $this->m_st) {
                                    $vlrST = $vlrBcST + ($this->m_st - $stGeralDist);
                                }
                            }  
                        }

                        
                        // ATUALIZA DADOS DO PRODUTOS;
                        $nfProduto->setIdNf($this->m_id);
                        $nfProduto->setCodProduto($arrOCItem[$i]['ITEMESTOQUE']);
                        $nfProduto->setDescricao($arrOCItem[$i]['DESCRICAO']);
                        $nfProduto->setUnidade($arrOCItem[$i]['UNIDADE']);
                        $nfProduto->setQuant(str_replace('.', ',',$arrOCItem[$i]['QTSOLICITADA'])); 
                        $nfProduto->setUnitario(str_replace('.', ',', $arrOCItem[$i]['UNITARIO']));
                        $nfProduto->setDesconto(str_replace('.', ',', $arrOCItem[$i]['DESCONTO']));
                        $totalItem = $arrOCItem[$i]['TOTAL']; 


                        $nfProduto->setValorIpi($item[7]);
                        if ($vlrST > 0 ){
                            $totalItem += $vlrST;
                        }
                        $vlrIPI = $nfProduto->getValorIpi('B');
                        if ($vlrIPI > 0 ){
                            $totalItem += $vlrIPI;
                        }
                        
                        $nfProduto->setValorBcSt($vlrBcST, true);
                        $nfProduto->setValorIcmsSt($vlrST, true);
                        $nfProduto->setTotal($totalItem, true);
                        
                        $nfProduto->setOrigem('0');
                        $nfProduto->setCfop($item[4]);
                        $nfProduto->setTribIcms($item[5]);
                        $nfProduto->setBcIcms(0);
                        $nfProduto->setValorIcms(0);
                        $nfProduto->setAliqIcms(0);
                        $nfProduto->setAliqIpi($item[6]);
                        $nfProduto->setCstPis('');
                        $nfProduto->setBcPis(0);
                        $nfProduto->setAliqPis(0);
                        $nfProduto->setValorPis(0);
                        $nfProduto->setCstCofins('');
                        $nfProduto->setBcCofins(0);
                        $nfProduto->setAliqCofins(0);
                        $nfProduto->setValorCofins(0);
                        $nfProduto->setCustoProduto(0);
                        $nfProduto->setNcm('');
                        $nfProduto->setCest('');
                        $nfProduto->setNrSerie('');
                        $nfProduto->setLote('');
                        $nfProduto->setBcFcpUfDest(0);
                        $nfProduto->setAliqFcpUfDest(0);
                        $nfProduto->setValorFcpUfDest(0);
                        $nfProduto->setBcIcmsUfDest(0);
                        $nfProduto->setAliqIcmsUfDest(0);
                        $nfProduto->setAliqIcmsInter(0);
                        $nfProduto->setAliqIcmsInterPart(0);
                        $nfProduto->setValorIcmsUfDest(0);
                        $nfProduto->setValorIcmsUFRemet(0);
                        $nfProduto->setAliqIcmsSt(0);
                        $nfProduto->setModBcSt(0);                        
                        $nfProduto->setDataFabricacao('');
                        $nfProduto->setDataValidade('');
                        $nfProduto->setDataGarantia('');
                        $nfProduto->setOrdem(''); 
                        $nfProduto->setProjeto('');
                        $nfProduto->setDataConferencia('');
                        $nfProduto->setCBenef('');
                        $nfProduto->setFrete('0');
                        $nfProduto->setCodigoNota('');
                        $nfProduto->setDespAcessorias('0');
                        $nfProduto->setDataConferencia($dataEntrada);
                        
                        $nfProduto->incluiNotaFiscalProduto($transaction->id_connection);

                        // verificar se é produtos fracionado                        
                        if ($arrOCItem[$i]['UNIFRACIONADA'] == "N"){
                            $quant = $nfProduto->getQuant();
                            $objEstProduto = new c_produto_estoque();
                            for ($it = 0; $it < $quant; $it++) {
                                    $objEstProduto->setIdNfEntrada($this->m_id);
                                    $objEstProduto->setCodProduto($nfProduto->getCodProduto());
                                    $objEstProduto->setStatus('0');
                                    $objEstProduto->setAplicado('0');
                                    $objEstProduto->setCentroCusto($nf->getCentroCusto());
                                    $objEstProduto->setUserProduto($this->m_userid);
                                    $objEstProduto->setLocalizacao('');
                                    $objEstProduto->incluiProdutoEstoque($transaction->id_connection);
                            }//for
                        }//if

                    } // FOR PRODUTO	    

                    // ************** 
                    // lanca parcelas financeiro
                    //***************
                    
                    $objFinanceiro = new c_lancamento();
                    
                    $arrParcelas = $this->formParcelasNfeFinanceiro(
                        $this->m_dadosFinanceiros, $arrOC[0]['TOTAL']);
                    
                    $arrParamFin['PESSOA'] = $arrOC[0]['CLIENTE'];
                    $arrParamFin['DOCTO'] = $this->getNumeroNf();
                    $arrParamFin['SERIE'] = $this->getSerie();
                    $arrParamFin['GENERO'] = $this->getGenero();
                    $arrParamFin['CENTROCUSTO'] = $centrocusto;
                    $arrParamFin['USER'] = $this->m_userid;
                    $arrParamFin['ORIGEM'] = "NFC";
                    $arrParamFin['NUMLCTO'] = $this->getNumeroNf();
                    $arrParamFin['TIPOLANCAMENTO'] = "P";
                    $arrParamFin['EMISSAO'] = $dataEmissao;
                    $arrParamFin['OBS'] = $obs;

                    $objFinanceiro->setCheque('0');
                    $objFinanceiro->setDocbancario('0');

                    $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $transaction->id_connection);

                    $this->atualizaOrdemCompraNumNfSerieDataEmissao($transaction->id_connection);
                                     
                    $transaction->commit($transaction->id_connection);

                    $this->mostraOrdemCompra('NF Gerada número: '.$this->getNumeroNf(), 'sucesso');
                                        
                } 
                catch (Error $e) {
                    $transaction->rollback($transaction->id_connection);    
                    throw new Exception($e->getMessage()."Nf Não foi gerada " );
                    $this->mostraOrdemCompra("Nf Não foi gerado <br>".$e->getMessage(), "ERROR");

                }
                catch (Exception $e) {
                    //echo 'Caught exception: ',  $e->getMessage(), "\n";
                    // if ($this->nfAberto == true):
                    //     $transaction->commit($transaction->id_connection);
                    // else:
                        $transaction->rollback($transaction->id_connection);    
                    // endif;
                    $this->mostraOrdemCompra("Nf Não foi gerado <br>".$e->getMessage(), "ERROR");
                    //$this->desenhaCadastroOrdemCompra("Identificador NF: ".$idGerado."<br>".$e->getMessage()."<br>");
                    break;
                }
                   
                    
                }
                break;            
            case 'financeiro':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    // Verifica se tem oc itens com id 0 
                    if($this->verifica_ordem_compra_item()){
                        $msg = 'Realizar cadastro do(s) iten(s) antes de realizar o Financeiro.';
                        $tipoMsg = 'alerta';
                        $this->mostraOrdemCompra($msg, $tipoMsg);
                    }else{
                        $this->desenhaCadastroOrdemCompraFinanceiro();
                    }
                }
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->desenhaCadastroOrdemCompra();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $this->setOrdemCompra();

                    $totalOrdem = $this->select_total_e_desconto_oc($this->getId(), $this->totaisFDS);
                    $this->setDesconto($totalOrdem['DESCONTOS'] == '' ? 0 : $totalOrdem['DESCONTOS']);

                    if ($this->getId() > 0){
                        $this->desenhaCadastroOrdemCompra();
                    }else{
                        $this->mostraOrdemCompra('Ordem de Compra não pode ser alterada', 'alerta');
                    }                  
                }
                break;
            case 'inclui': 
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    if (!is_numeric($this->getId())){
                        $this->setEmissao(date("d/m/Y"));
                        $this->getCentroCusto($this->m_empresacentrocusto);
                        $idGerado = $this->incluiOrdemCompra();
                        $this->setId($idGerado);
                        $totalOrdem = $this->select_total_e_desconto_oc($this->getId(), $this->totaisFDS);
                        $this->setSituacao(5); // SITUACAO = 5 COTACAO
                        $totalOrdemCompra = $totalOrdem['TOTALOC'] == '' ? 0 : $totalOrdem[0]['TOTALOC'];
                        $descontoOrdemCompra = $totalOrdem['DESCONTOS'] == '' ? 0 : $totalOrdem['DESCONTOS'];
                        $this->alteraOrdemCompraTotal($totalOrdemCompra, $descontoOrdemCompra);
                    }
                    $this->mostraOrdemCompra('Ordem de Compra confirmada. '.$this->getId(), 'sucesso');
                }
                break;
            case 'altera': // CONCLUIR
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $this->alteraOrdemCompra();
                    $totalOrdem = $this->select_total_e_desconto_oc($this->getId(), $this->totaisFDS);
                    $this->setSituacao(5); // SITUACAO = 5 COTACAO
                    $totalOrdemCompra = $totalOrdem['TOTALOC'] == '' ? 0 : $totalOrdem['TOTALOC'];
                    $descontoOrdemCompra = $totalOrdem['DESCONTO'] == '' ? 0 : $totalOrdem[0]['DESCONTO'];
                    $this->alteraOrdemCompraTotal($totalOrdemCompra, $descontoOrdemCompra);
                    $this->mostraOrdemCompra('Ordem de Compra confirmada '.$this->getId(), 'sucesso');
                }
                break;
            case 'digita': //VOLTAR
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    if ($this->getId()!=''):
                        
                        $this->setTotal($this->select_ordem_compra_total(),true);
                        $this->setSituacao(5);
                        $this->alteraOrdemCompra();
                        $this->mostraOrdemCompra('');
                    else:    
                        $this->mostraOrdemCompra('');
                    endif;
                }
                break;
            case 'exclui': // CANCELA
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $arrOrdemCompra = $this->select_ordem_compra_id();
                    if (is_array($arrOrdemCompra)){
                        $this->excluiOrdemCompraItem();
                        $this->excluiOrdemCompra();                       
                        $this->mostraOrdemCompra("Ordem de Compra excluída com sucesso!!", 'sucesso');
                    }else{
                        $this->mostraOrdemCompra('Ordem de Compra não pode ser EXCLUÍDA.', 'alerta');
                    }                   
                }
                break; 
            case 'duplicaOrdemCompra':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $idAntigo = $this->getId();
                    $idGerado = $this->duplicaOrdemCompra();
                    $this->setId($idGerado);
                    $this->duplicaOrdemCompraItem($idGerado, $idAntigo);
                    $this->setOrdemCompra();
                    $this->m_submenu = 'alterar';                 
                    $this->desenhaCadastroOrdemCompra();
                }
            break;
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->mostraOrdemCompra('');
                }
        }
    }

    function modulo_11($num, $base=9) {
        $soma = 0;
        $fator = 2;
        for ($i = strlen($num); $i > 0; $i--) {
            $numeros[$i] = substr($num,$i-1,1);
            $parcial[$i] = $numeros[$i] * $fator;
            $soma += $parcial[$i];
            if ($fator == $base) {
                $fator = 1;
            }
            $fator++;
        }        
        
        $resto = $soma % 11;
        if ($resto == 0){
            return $resto;
        } else {
            return (11 - $resto);
        }            
        
    }

    function desenhaCadastroOrdemCompraFinanceiro($mensagem = NULL, $tipoMsg = NULL) {

        $descCondPgto = "";
        $parcelas = 0;
        $valorParcelas = 0;
        $totalParcelas = 0;
        $numParcelas = 0;
        $fin = [];
        
        $condPgto = $this->getCondPg();
        $this->setOrdemCompra(); 
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('numNf', $this->getNumeroNf());
        $this->smarty->assign('serie', $this->getSerie());
        $this->smarty->assign('cliente', $this->getCliente());
        $this->smarty->assign('data', $this->getEmissao('F'));
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('natOperacao', $this->getIdNatop());
        $this->smarty->assign('clienteNome', $this->getClienteNome());
        if($this->getDataEntrada() == ''){
            $dataAtual = date("d/m/Y");
            $this->smarty->assign('dataEntrada', $dataAtual);
        }else{
            $this->smarty->assign('dataEntrada', $this->getDataEntrada('F'));
        }
        if ($this->getDataEmissao() == '') {
            $dataAtual = date("d/m/Y");
            $this->smarty->assign('dataEmissao', $dataAtual);
        } else {
            $this->smarty->assign('dataEmissao', $this->getDataEmissao('F'));    
        } 
        
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
        $this->setCondPg($condPgto ? $condPgto : $this->getCondPg());
        
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
        $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero where (tipolancamento = 'P') ORDER BY descricao;";
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
        $this->smarty->assign('situacaoLanc_id', 'N');


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

        $id = $this->getId();
        if ($id > 0){
            $lancItens = $this->select_ordem_compra_item_id();
            $this->smarty->assign('lancItens', $lancItens);
        }        
        
        // CALCULA PARCELAS
        $objPedidoTools = new c_ordemCompraTools();
        $fin = $objPedidoTools->calculaParcelasNfe($descCondPgto, $this->getTotal('F'), 0, 0, $this->getDataEmissao());
        
        $this->smarty->assign('fin', $fin); 

        $this->smarty->display('ordem_compra_cadastro_financeiro.tpl');
    }
    
    function desenhaCadastroOrdemCompra($mensagem = NULL,$tipoMsg=NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('form', 'ordem_compra');
        $this->smarty->assign('itensPedido', $this->m_itensPedido);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('promocoes', 'S');

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('nrItem', $this->getId());
        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
        endif;

        // situacao 9 = PEDIDO BAIXADO
        if($this->getSituacao() == 9){
            $ocBaixado = true;
            $this->smarty->assign('ocBaixado', $ocBaixado);
        }

        $this->smarty->assign('situacao', $this->getSituacao());
        $this->smarty->assign('emissao', $this->getEmissao('F'));
        $this->smarty->assign('condPg', $this->getCondPg());
        $this->smarty->assign('desconto', $this->getDesconto('F'));
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('produtos', $this->getProdutos('F'));
        $this->smarty->assign('obs', $this->getObs());

        $this->smarty->assign('numNf', $this->getNumeroNf());
        $this->smarty->assign('serie', $this->getSerie());
        $this->smarty->assign('dataEmissao', $this->getDataEmissao('F'));

        $this->smarty->assign('frete', $this->getFrete('F'));  
        $this->smarty->assign('despacessorias', $this->getDespAcessorias('F'));  
        $this->smarty->assign('seguro', $this->getSeguro('F'));

        
        // COMBOBOX NAT OPERAÇÃO
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where tipo='S'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $natop_ids[0] = '';
        $natop_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $natop_ids[$i + 1] = $result[$i]['ID'];
            $natop_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natop_ids', $natop_ids);
        $this->smarty->assign('natop_names', $natop_names);
        $this->smarty->assign('natop_id', $this->getIdNatop());        
       
        $id = $this->getId();
        if (!empty($id)){
            $lancItens = $this->select_ordem_compra_items($id);
            $this->smarty->assign('lancItens', $lancItens);
        }

        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $condPgto_ids[0] = 0;
        $condPgto_names[0] = 'Condição Pagamento';
        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i+1] = $result[$i]['ID'];
            $condPgto_names[$i+1] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('descCondPgto', "$descCondPgto");
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPg());

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO ";
        $sql .= "FROM AMB_DDM ";
        $sql .= "WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND ";
        $sql .= "((TIPO = 5) OR (TIPO = 9))";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        if($this->getSituacao() == ''){
            $this->smarty->assign('situacao_id', '5');
        }else{
            $this->smarty->assign('situacao_id', $this->getSituacao());
        }

        $this->smarty->assign('basest', '0,00');
        $this->smarty->assign('st', '0,00');

        //AJAX ATUALIZA TOTAIS DE FRETE - SEGURO - DESP ACESSORIAS = TOTAL
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_ATUALIZA_TOTAIS"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_ATUALIZA_TOTAIS"] == "true"):
            $ajax_request = 'true';
            
            // ATUALIZA TOTAL DE ITENS E TOTAL DESCONTO
            $totalOrdem = $this->select_total_e_desconto_oc($this->getId(), $this->totaisFDS);
   
            $this->setSituacao(5); // SITUACAO = 5 COTACAO

            $this->setTotal($totalOrdem['TOTALOC']);
            $this->smarty->assign('totalOc', $this->getTotal('F'));
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);

        endif; 

        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_ITEM"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_ITEM"] == "true"):
            $ajax_request = 'true';
            $objPedidoTools = new c_ordemCompraTools();
            if(empty($this->m_par_item[14])){
                if(empty($this->m_par_item[0])){
                    $this->setSituacao(5); // SITUACAO = 5 COTACAO
                    $this->setEmissao(date("d/m/Y")); 
                    $idOrdemCompra = $this->incluiOrdemCompra();
                    $this->setId($idOrdemCompra);
                }
                $objPedidoTools->incluiItemOrdemCompra($this->getId(), $this->m_letra_item);
            }else{
                $objPedidoTools->alteraItemOrdemCompra($this->getId(), $this->m_letra_item);
            }
            // ATUALIZA TOTAL DE ITENS E TOTAL DESCONTO 
            $totalOrdem = $this->select_total_e_desconto_oc($this->getId(), $this->totaisFDS); 
            $this->setSituacao(5); // SITUACAO = 5 COTACAO
            
            $this->alteraOrdemCompraTotal($totalOrdem['TOTALOC'], $totalOrdem['DESCONTOS']);
            
            $this->setTotal($totalOrdem['TOTALOC']);
            $this->setDesconto($totalOrdem['DESCONTOS']);
            $this->smarty->assign('totalOc', $this->getTotal('F'));
            $this->smarty->assign('descontoOc', $this->getDesconto('F'));

            $lancItens = $this->select_ordem_compra_items($this->getId());
            $this->smarty->assign('lancItens', $lancItens);
            $this->smarty->assign('id', $this->getId());
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif; 

        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_ITEM"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_ITEM"] == "true"):
            $ajax_request = 'true';
            $objPedidoTools = new c_ordemCompraTools();
            $objPedidoTools->excluiItensOrdemCompraControle($this->getId(),$this->m_par_item[1]);
            // ATUALIZA TOTAL DE ITENS E TOTAL DESCONTO 
            $totalOrdem = $this->select_total_e_desconto_oc($this->getId(), $this->totaisFDS);
            $this->setSituacao(5); // SITUACAO = 5 COTACAO
            
            $totalOrdemCompra = $totalOrdem['TOTALOC'] == '' ? 0 : $totalOrdem['TOTALOC'];
            $descontoOrdemCompra = $totalOrdem['DESCONTOS'] == '' ? 0 : $totalOrdem['DESCONTOS'];
            $this->alteraOrdemCompraTotal($totalOrdemCompra, $descontoOrdemCompra);

            $this->setTotal($totalOrdem['TOTALOC']);
            $this->setDesconto($totalOrdem['DESCONTOS']);
            $this->smarty->assign('totalOc', $this->getTotal('F'));
            $this->smarty->assign('descontoOc', $this->getDesconto('F'));

            $lancItens = $this->select_ordem_compra_items($this->getId());
            $this->smarty->assign('lancItens', $lancItens);
            $this->smarty->assign('id', $this->getId());
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);

        endif; 

        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_VERIFICA_NF"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_VERIFICA_NF"] == "true"):
            $ajax_request = 'true';
            $objNf = new c_nota_fiscal();
            $objNf->setNumero($this->getNumeroNf());
            $objNf->setSerie($this->getSerie());
            $objNf->setPessoa($this->getCliente());

            //verifica se existe nf cadastrada para o cliente
            if ($objNf->existeNotaFiscalEntrada() == true):
                $msg = "Nota Fiscal já cadastrada para este Fornecedor!";
            endif;

            $this->smarty->assign('msgNf', "'$msg'");
        
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);

        endif;

        //BUSCA PRODUTO OS
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PROD"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PROD"] == "true"):
            $ajax_request = 'true';

            $prod       = new c_produto();
            $codFab     = $this->m_cod_fabricante;
            $resultProd = $prod->select_produto_cod_fabricante($codFab);
            $codFab     = $resultProd[0]['CODFABRICANTE'];
            $codEqui    = $resultProd[0]['CODEQUIVALENTE'];
            $descProd   = $resultProd[0]['DESCRICAO'];
            $unProd     = $resultProd[0]['UNIDADE'];

            if($resultProd !== null){
                $this->smarty->assign('prodExiste', 'yes');
                $this->smarty->assign('codProduto', $resultProd[0]['CODIGO']);
                //Testa se é produto ou equivalente
                if($resultProd[0]['ORIGEM'] == 'EQUIVALENTE'){
                    $this->smarty->assign('codFabricante', "'$codFab'");
                    $this->smarty->assign('codProdutoNota', "'$codEqui'");
                }else{
                    $this->smarty->assign('codFabricante', "'$codFab'");
                    $this->smarty->assign('codProdutoNota', "'$codFab'");
                }
        
                $this->smarty->assign('descProduto', "$descProd");
                $this->smarty->assign('uniProduto', "'$unProd'");
                $this->setUnitario($resultProd[0]['VENDA']);
                $this->smarty->assign('unitario', $this->getUnitario('F'));
            }else{
                $this->smarty->assign('prodExiste', 'no');
            }

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);

        endif;

        $this->smarty->assign('totalOc', $this->getTotal('F'));
        $this->smarty->assign('descontoOc', $this->getDesconto('F'));
    
        $this->smarty->display('ordem_compra_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraOrdemCompra($mensagem=NULL, $tipoMsg=NULL) {

        $cliente = '';
        if ($this->m_letra !=''):
            $lanc = $this->select_ordem_compra($this->m_letra);
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

        // pessoa
        if($this->m_par[2] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            $this->smarty->assign('pessoa', $this->m_par[2]);
            $this->smarty->assign('nome', $this->getClienteNome());
        }
  
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mod', 'coc');
        $this->smarty->assign('form', 'ordem_compra');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('numDocto', $this->m_par[5]);
        //$this->smarty->assign('numOrdemCompra', $this->m_par[3]);

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO ";
        $sql .= "FROM AMB_DDM ";
        $sql .= "WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND ";
        $sql .= "((TIPO = 5) or (TIPO = 9))";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        if ($this->m_par[4] != '') {            
            $this->smarty->assign('situacao_id', $this->m_par[4]);
        } else {
            $this->smarty->assign('situacao_id', "5");
        }
         //Modal Email

         $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_ENVIAR_EMAIL"] == "true");
         if($_SERVER["HTTP_AJAX_REQUEST_ENVIAR_EMAIL"] == "true"):
             $ajax_request = 'true';
 
             $consulta = new c_banco;
             $consulta->setTab("FIN_CLIENTE");
             $clienteEmail = $consulta->getField("EMAIL", "CLIENTE=".$this->getCliente());
             $consulta->close_connection();
 
             $assunto = $this->m_empresafantasia." - Ref Ordem de Compra Nº ".$this->getId();
 
             $emailCorpo = "Prezado(a) Cliente, \n \n".
             "Estamos encaminhando a Ordem de Compra no formato PDF.\n \n".
             
             $this->m_usernome ."\n".
             $this->m_empresanome;
 
             $idOrdemCompra = $this->getId();
 
             $this->setId('');
             $this->setCliente('');
             $this->smarty->assign('pessoa', '');
             $this->smarty->assign('id', '');
 
             $this->smarty->assign('idOrdemCompra', $idOrdemCompra);
             $this->smarty->assign('destinatario', $clienteEmail);
             $this->smarty->assign('comCopiaPara', $this->m_configemail);
             $this->smarty->assign('assunto',"'".$assunto."'");
             $this->smarty->assign('emailCorpo', $emailCorpo);
 
         else:
             $ajax_request = 'false';
             $this->smarty->assign('ajax', $ajax_request);
         endif; 
 
         // envia email Ordem Compra
 
         $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_ENVIAR_EMAIL_ORDEM_COMPRA"] == "true");
         if($_SERVER["HTTP_AJAX_REQUEST_ENVIAR_EMAIL_ORDEM_COMPRA"] == "true"):
             $ajax_request = 'true';
 
             // caminhos absolutos para todos os diretorios do Smarty
             $this->smarty->template_dir = ADMraizCliente . "/template/coc";
             $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
             $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
             $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";
 
             try{
                 // Monta tpl p/ converter para pdf
                
                 
                $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
                $this->smarty->assign('pathImagem', ADMimg);
                $this->smarty->assign('cssBootstrap', true);

                $this->smarty->assign('subMenu', $this->m_submenu);
                $this->smarty->assign('letra', $this->m_letra);
                $this->smarty->assign('mensagem', $mensagem);
                $this->smarty->assign('tipoMsg', $tipoMsg);
                $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

                $lanc = $this->select_ordem_compra_id();
                $lancItem = $this->select_ordem_compra_item_id();
                $empresa = $this->busca_dadosEmpresaCC($lanc[0]['CCUSTO']);

                $condPgto = new c_cond_pgto();
                $condPgto->setId($lanc[0]['CONDPG']);
                $descPgto = $condPgto->selectCondPgto();
                $descCondPgto = $descPgto[0]['DESCRICAO'];
                

                $this->smarty->assign('descCondPgto', $descCondPgto);
                $this->smarty->assign('empresa', $empresa);
                $this->smarty->assign('pedido', $lanc);
                $this->smarty->assign('pedidoItem', $lancItem);
                $this->smarty->assign('fin', $fin);
                 
                 // pega url imagem p/ converter pdf
                 $urlImg = "https://admsistema.com.br/".ADMcliente."/images/logo.png";
     
                 $this->smarty->assign('urlImg', $urlImg);
     
                 $html = $this->smarty->fetch('ordem_compra_imprime.tpl');  
                 $filePath =  ADMraizCliente."/images/doc/ordemCompra".$this->getId(); 
                 
                 $filename = ADMraizCliente."/images/doc/ordemCompra".$this->getId().".pdf";
                 $options = new Options();
                 $options->set('isRemoteEnabled', TRUE);
                 // conversão PDF
                 $dompdf = new DOMPDF($options);
                 $dompdf->load_html($html);            
                 $dompdf->set_paper('A4', 'portrait');
                 $dompdf->render();
                 file_put_contents($filename, $dompdf->output());
 
                 chmod($filename, 0777);
                 
                 $tipoMsg = 'alerta';
                 $ok = true;
                 // Envia por email
                 if($this->m_configsmtp == ''){
                     $msgAlert = "o SMTP do Usuario esta vazio. \n";
                     $ok = false;
                 }
                 if($this->m_configemail == ''){
                     empty($msgAlert) == true ? $msgAlert = "Email do Usuario esta vazio. \n" :
                     $msgAlert .= "Email do Usuario esta vazio.\n";
                     $ok = false;
                 }
                 if($this->m_configemailsenha == ''){
                     empty($msgAlert) == true ? $msgAlert = "Senha do Email do Usuario esta vazio." :
                     $msgAlert .= "Senha do Email do Usuario esta vazio.";
                     $ok = false;
                 }
                 if($ok == true){
                     $tipoMsg = 'sucesso';
                     $mail = new admMail();    
 
                     
 
                     $resp = $mail->SendMail($this->m_configsmtp, $this->m_configemail, "email Ordem Compra PDF", $this->m_configemailsenha, 
                                    $this->m_emailCorpo, $this->m_assunto, $this->m_destinatario, "",$this->m_comCopiaPara,"", $filename, $filename);
                 
                     $msgAlert = "Email Enviado.";
                 }
 
                 // deleta o PDF criado
                 unlink($filename);
                 $this->setId('');
 
                 // caminhos absolutos para todos os diretorios do Smarty
                 $this->smarty->template_dir = ADMraizFonte . "/template/coc";
                 $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
                 $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
                 $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";
 
                 $this->smarty->assign('mensagem', $msgAlert);
                 $this->smarty->assign('tipoMsg', $tipoMsg);
 
             }catch(Error $e){
                 throw new Error($e->getMessage()."Erro ao enviar Email Ordem Compra " );
             }
         else:
             $ajax_request = 'false';
             $this->smarty->assign('ajax', $ajax_request);
   
         endif; 
        $this->smarty->display('ordem_compra_mostra.tpl');
    }
}

// Rotina principal - cria classe
$pedido = new p_ordem_compra();

$pedido->controle();

