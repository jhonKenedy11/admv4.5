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
require_once($dir . "/../../forms/est/p_nfe_json_espelho.php");
require_once($dir . "/../../forms/est/p_espelho_nfe.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/crm/c_conta.php");
require_once($dir . "/../../class/fin/c_extrato.php");
require_once($dir . "/../../class/est/c_parametro.php");
require_once($dir . "/../../class/pdv/c_cupom.php");

if ($_POST['submenu'] == 'cadastraFinanceiro') {
    require_once($dir . "/../../forms/ped/p_pedido_venda_gerente_novo.php");
}
if ($_POST['submenu'] == 'cadastraFinanceiroNotaFiscal') {
    require_once($dir . "/../../forms/est/p_nota_fiscal.php");
}


//Class P_situacao
class p_pedido_venda_nf_pecas_novo extends c_pedidoVendaNf
{

    public $m_submenu = NULL;
    public $m_letra = NULL;
    public $m_opcao = NULL;
    public $m_msg = NULL;
    public $parmPost = NULL;
    public $smarty = NULL;
    public $descCondPgto = NULL;

    public $alteraCondPgto = NULL;
    public $formNf = NULL;
    public $vendaPresencial = NULL;

    // field NF
    public $modFrete = NULL;
    public $transportador = NULL;
    public $placaVeiculo = NULL;
    public $codAntt = NULL;
    public $uf = NULL;
    public $volume = NULL;
    public $volEspecie = NULL;
    public $volMarca = NULL;
    public $volPesoLiq = NULL;
    public $volPesoBruto = NULL;
    public $dataSaidaEntrada = NULL;
    public $obs = NULL;
    public $nfAberto = false;
    public $objNotaFiscal = NULL;
    public $objProduto = NULL;
    public $objProdutoEstoque = NULL;
    public $objNfProduto = NULL;
    public $objFinanceiro = NULL;
    public $arrParcelas = NULL;
    public $arrParamFin = NULL;
    public $arrProduto = NULL;
    public $arrProdutoEstoqueReserva = NULL;
    public $arrItemPedido = NULL;
    public $t_origem = NULL;
    public $usarCredito = NULL;
    public $credito = NULL;
    public $saldoCredito = NULL;
    public $data_history = NULL;
    public $tipoDocFiscal = NULL;


    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($id = null, $submenu = null)
    {
        @set_exception_handler(array($this, 'exception_handler'));

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
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

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

        $this->vendaPresencial = (isset($this->parmPost['vendaPresencial']) ? $this->parmPost['vendaPresencial'] : 'N');

        $this->usarCredito = (isset($this->parmPost['usarCredito']) ? $this->parmPost['usarCredito'] : 'N');
        $this->credito = (isset($this->parmPost['credito']) ? $this->parmPost['credito'] : 0);
        $this->saldoCredito = (isset($this->parmPost['saldoCredito']) ? $this->parmPost['saldoCredito'] : 0);
        $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
        $this->modFrete = (isset($this->parmPost['modFrete']) ? $this->parmPost['modFrete'] : "");
        $this->transportador = (isset($this->parmPost['pessoa']) ? $this->parmPost['pessoa'] : "0");
        $this->placaVeiculo = (isset($this->parmPost['placaVeiculo']) ? $this->parmPost['placaVeiculo'] : "");
        $this->codAntt = (isset($this->parmPost['codAntt']) ? $this->parmPost['codAntt'] : "");
        $this->uf = (isset($this->parmPost['uf']) ? $this->parmPost['uf'] : "");
        $this->volume = (isset($this->parmPost['volume']) ? $this->parmPost['volume'] : "1");
        $this->volEspecie = (isset($this->parmPost['volEspecie']) ? $this->parmPost['volEspecie'] : "");
        $this->volMarca = (isset($this->parmPost['volMarca']) ? $this->parmPost['volMarca'] : "");
        $this->volPesoLiq = (isset($this->parmPost['volPesoLiq']) ? $this->parmPost['volPesoLiq'] : "");
        $this->volPesoBruto = (isset($this->parmPost['volPesoBruto']) ? $this->parmPost['volPesoBruto'] : "");
        $this->dataSaidaEntrada = (isset($this->parmPost['dataSaidaEntrada']) ? $this->parmPost['dataSaidaEntrada'] : date("d/m/Y H:i:s"));
        $this->obs = (isset($this->parmPost['obs']) ? $this->parmPost['obs'] : "");
        $this->alteraCondPgto = (isset($this->parmPost['alteraCondPgto']) ? $this->parmPost['alteraCondPgto'] : "");
        $this->t_origem = (isset($this->parmPost['t_origem']) ? $this->parmPost['t_origem'] : "");
        // Parametro usado para historico do form gerente pedido
        $this->data_history = (isset($this->parmPost['data_history']) ? $this->parmPost['data_history'] : "");
        $this->tipoDocFiscal = (isset($this->parmPost['tipoDocFiscal']) ? $this->parmPost['tipoDocFiscal'] : "");

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
    public function formParcelasNfe($condPgto = NULL, $total = 0)
    {
        $parcelas = explode("/", $condPgto);
        $numParcelas = count($parcelas);
        $totalGeral = doubleval($total);
        $totalCalc = 0;
        for ($i = 0; $i < $numParcelas; $i++) {
            $lanc[$i]['PARCELA'] = $i + 1;
            $lanc[$i]['VENCIMENTO'] = (isset($this->parmPost['venc' . $lanc[$i]['PARCELA']]) ? $this->parmPost['venc' . $lanc[$i]['PARCELA']] : "");
            $this->setTotal((isset($this->parmPost['valor' . $lanc[$i]['PARCELA']]) ? $this->parmPost['valor' . $lanc[$i]['PARCELA']] : ""));
            $lanc[$i]['VALOR'] = $this->getTotal('B');
            $lanc[$i]['TIPO'] = (isset($this->parmPost['tipo' . $lanc[$i]['PARCELA']]) ? $this->parmPost['tipo' . $lanc[$i]['PARCELA']] : "");
            $lanc[$i]['SITUACAO'] = (isset($this->parmPost['situacao' . $lanc[$i]['PARCELA']]) ? $this->parmPost['situacao' . $lanc[$i]['PARCELA']] : "");
            $lanc[$i]['CONTA'] = (isset($this->parmPost['conta' . $lanc[$i]['PARCELA']]) ? $this->parmPost['conta' . $lanc[$i]['PARCELA']] : "");
            $lanc[$i]['OBS'] = (isset($this->parmPost['obs' . $lanc[$i]['PARCELA']]) ? $this->parmPost['obs' . $lanc[$i]['PARCELA']] : "");

            $totalCalc += $lanc[$i]['VALOR'];
        }
        $epsilon = 0.00001;
        $totalAbs = abs($totalCalc - $totalGeral);
        if ($totalAbs < $epsilon):
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
    function controle()
    {
        $quantPedido = 0;
        $quantReserva = 0;

        switch ($this->m_submenu) {
            case 'financeiro': // financeiro
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $arrPedBloq = $this->select_pedidoVenda();
                    $objContaBloq = new c_conta();
                    if (is_array($arrPedBloq) && strtoupper((string) $objContaBloq->contaBloqueada((int) ($arrPedBloq[0]['CLIENTE'] ?? 0))) === 'S') {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Cliente bloqueado',
                                text: 'Cliente bloqueado. Verifique com o financeiro.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }
                    $this->formNf = false;
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'financeiroEntradaNf': // financeiro
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->formNf = false;
                    $this->desenhaCadastroFinanceiroNotafiscal();
                }
                break;
            case 'notafiscal':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $arrPedBloq = $this->select_pedidoVenda();
                    $objContaBloq = new c_conta();
                    if (is_array($arrPedBloq) && strtoupper((string) $objContaBloq->contaBloqueada((int) ($arrPedBloq[0]['CLIENTE'] ?? 0))) === 'S') {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Cliente bloqueado',
                                text: 'Cliente bloqueado. Verifique com o financeiro.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }
                    if ($this->pedidoEmEncomenda()) {
                        $this->formNf = false;
                        $this->desenhaCadastroPedido(
                            'Pedido em <b>encomenda</b> — aguardando entrada de estoque. '
                            . 'Cadastre apenas o financeiro; a NF será emitida após liberação do pedido.',
                            'warning'
                        );
                        break;
                    }
                    $this->formNf = true;
                    $this->desenhaCadastroPedido();
                }
                break;
            case 'cadastraNf':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'S')) {
                    $arrPedBloq = $this->select_pedidoVenda();
                    $objContaBloq = new c_conta();
                    if (is_array($arrPedBloq) && strtoupper((string) $objContaBloq->contaBloqueada((int) ($arrPedBloq[0]['CLIENTE'] ?? 0))) === 'S') {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Cliente bloqueado',
                                text: 'Cliente bloqueado. Verifique com o financeiro.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }
                    $this->formNf = true;
                    try {
                        // busca dados pedido e cliente
                        //$this->setPedidoVenda(); // seta dados pedido e cliente
                        $arrPedido = $this->select_pedidoVenda();
                        $seriePedido = trim((string) ($arrPedido[0]['SERIE'] ?? ''));
                        $ehCupomFiscalPedido = $this->ehCupomFiscalContext($arrPedido);
                        if ($ehCupomFiscalPedido) {
                            $this->aplicaParametrosCupomFiscal();
                            $this->vendaPresencial = 'S';
                        }

                        // valida se CC pedido é o mesmo da CC logada no sistema.
                        // if ($arrPedido[0]['CCUSTO']==$this->m_empresacentrocusto){
                        //     $this->m_msg = "Erro: Empresa da Pedido difere da empresa atual logada no sistema!";
                        //     throw new Exception( $this->m_msg );    
                        // }

                        if ($arrPedido[0]['CODMUNICIPIO'] == ''):
                            $cep = preg_replace("/[^0-9]/", "", $arrPedido[0]['CEP']);
                            $url = "https://viacep.com.br/ws/$cep/xml/";
                            $xml = simplexml_load_file($url);
                            $ibge = $xml->ibge;
                            if ($ibge != null) {
                                c_conta::updateCodMunicipio($arrPedido[0]['CLIENTE'], $ibge);
                            } else {
                                $this->m_msg = "Erro: Cógio do Município não encontrado. Conferir dados do endereço no cadastro do cliente!";
                                throw new Exception($this->m_msg);
                            }
                        endif;

                        $numPedido = $arrPedido[0]['PEDIDO'];
                        $sitPedido = $arrPedido[0]['SITUACAO'];
                        if ((int) $sitPedido === 13) {
                            $this->m_msg = 'Não é possível emitir NF com o pedido em <b>encomenda</b>. '
                                . 'Aguarde a entrada de estoque e a liberação do pedido antes de faturar.';
                            throw new Exception($this->m_msg);
                        }
                        if ($sitPedido == 9):
                            $this->m_msg = "Nota fiscal não foi gerada, pedido <b>" . $this->getId() . "</b> com BAIXADO";
                            $result = false;
                            throw new Exception($this->m_msg);
                        endif;

                        // search param
                        $parametros = new c_banco;
                        $parametros->setTab("EST_PARAMETRO");
                        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=" . $this->m_empresacentrocusto);
                        // if not location nat_op in order. search param stock
                        if (is_null($arrPedido[0]['NFAUTO'])) {
                            $integraFin = $parametros->getField("INTEGRAFIN", "FILIAL=" . $this->m_empresacentrocusto);
                            $validaNfAuto = $parametros->getField("VALIDANFAUTO", "FILIAL=" . $this->m_empresacentrocusto);
                            $modeloNf = $parametros->getField("MODELO", "FILIAL=" . $this->m_empresacentrocusto);
                        } else {
                            $validaNfAuto = $arrPedido[0]['NFAUTO'];
                            $modeloNf = $arrPedido[0]['MODELONF'];
                        }
                        if ($ehCupomFiscalPedido) {
                            $modeloNf = '65';
                        } elseif ($seriePedido === '65' || $seriePedido === '55') {
                            $modeloNf = $seriePedido;
                        }
                        $parametros->setTab("EST_NAT_OP");
                        $arrNatOp = $parametros->getRecord("ID=" . $this->getIdNatop());
                        if ($controlaEstoque == "S"):
                            $controlaEstoque = $arrNatOp[0]["ALTERAQUANT"];
                        endif;

                        $integraFin = $arrNatOp[0]["INTEGRAFIN"];
                        $parametros->close_connection();

                        // search param FAT_PARAMETRO
                        $parametros = new c_banco;
                        $parametros->setTab("FAT_PARAMETRO");
                        $situacaoBaixa = $parametros->getField("SITBAIXADO", "FILIAL=" . $this->m_empresacentrocusto);
                        $tipoNfeItemDesconto = $parametros->getField("TIPODESCONTO", "FILIAL=" . $this->m_empresacentrocusto);
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
                                $this->m_msg = "Não existem produtos no pedido: " . $this->getId();
                                $result = false;
                                throw new Exception($this->m_msg);
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
                                $this->m_msg = "Não existem produtos no pedido: " . $this->getId();
                                $result = false;
                                throw new Exception($this->m_msg);
                            endif;

                        endif;

                        // GERA NF
                        $objNotaFiscal = new c_nota_fiscal();

                        if ($ehCupomFiscalPedido) {
                            $objCupomFiscal = new c_cupom();
                            if ($objCupomFiscal->existeNfceCupomAutorizada((int) $this->getId())) {
                                $this->m_msg = 'Já existe NFC-e (cupom) autorizada para este pedido: ' . $this->getId();
                                throw new Exception($this->m_msg);
                            }
                        } elseif ($objNotaFiscal->existeNotaFiscalPedido($numPedido) == true) {
                            $this->m_msg = "Já existe nota fiscal autorizada para este pedido: " . $this->getId();
                            $result = false;
                            throw new Exception($this->m_msg);
                        }

                        $objNotaFiscal->setModelo($modeloNf);
                        $objNotaFiscal->setSerie($this->getSerie());
                        if ($ehCupomFiscalPedido) {
                            $this->vendaPresencial = 'S';
                            $this->modFrete = '9';
                            $this->transportador = '0';
                        }
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
                        $objNotaFiscal->setFormaPgto($this->getFormaPgto()); //===
                        $objNotaFiscal->setCondPgto($this->getCondPg());
                        
                        // Ajusta a data de saída/entrada para evitar conflito com a data atual
                        $data_ajustada = $this->verificarAjustaData($this->dataSaidaEntrada);
                        $objNotaFiscal->setDataSaidaEntrada($data_ajustada);
                        
                        $objNotaFiscal->setFormaEmissao('N');
                        $objNotaFiscal->setFinalidadeEmissao('1');
                        $objNotaFiscal->setCentroCusto(isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto);
                        $objNotaFiscal->setGenero($this->getGenero()); //====????
                        $objNotaFiscal->setTotalnf($this->getTotal()); //===
                        $objNotaFiscal->setModFrete($this->modFrete);
                        if ($this->transportador == "") {
                            $this->transportador = '0';
                        }
                        $objNotaFiscal->setTransportador($this->transportador);
                        $objNotaFiscal->setPlacaVeiculo($this->placaVeiculo);
                        $objNotaFiscal->setCodAntt($this->codAntt);
                        $objNotaFiscal->setUf($this->uf);
                        $objNotaFiscal->setVolume($this->volume);
                        $objNotaFiscal->setVolEspecie($this->volEspecie);
                        $objNotaFiscal->setVolMarca($this->volMarca);
                        $objNotaFiscal->setVolPesoLiq($this->volPesoLiq);
                        $objNotaFiscal->setVolPesoBruto($this->volPesoBruto);
                        $objNotaFiscal->setObs("Pedido: " . $numPedido . "; " . $arrNatOp[0]["OBS"] . ";" . $arrPedido[0]['OBS'] . ";" . $this->obs);
                        $objNotaFiscal->setOrigem($ehCupomFiscalPedido ? 'CPM' : 'PED');
                        $objNotaFiscal->setDoc($ehCupomFiscalPedido ? (int) $this->getId() : $numPedido);
                        $objNotaFiscal->setDespAcessorias($arrPedido[0]['DESPACESSORIAS'], true);
                        $objNotaFiscal->setDescontoGeral($arrPedido[0]['DESCONTO']);
                        $objNotaFiscal->setFrete((isset($arrPedido[0]['FRETE']) ? $arrPedido[0]['FRETE'] : $this->frete), true);
                        $objNotaFiscal->setVendaPresencial($this->vendaPresencial);
                        if ($ehCupomFiscalPedido) {
                            $objNotaFiscal->setCpfNota(trim((string) ($this->parmPost['cpf'] ?? '')));
                        }

                        $idGerado = $objNotaFiscal->incluiNotaFiscal($transaction->id_connection);
                        // verificar inclusao NF
                        if (intval($idGerado) == 0):
                            $this->m_msg = $idGerado;
                            $result = false;
                            throw new Exception($this->m_msg);
                        endif;

                        // CADASTRA ITENS NF
                        //$objProduto = new c_produto();
                        $objNfProduto = new c_nota_fiscal_produto();
                        for ($i = 0; $i < count($arrItemPedido); $i++) {

                            if ($controlaEstoque == 'S') {
                                if ($arrItemPedido[$i]['QTSOLICITADA'] != $arrItemPedido[$i]['QUANTIDADE']):

                                    $quantDigitada = $arrItemPedido[$i]['QTSOLICITADA'] - $arrItemPedido[$i]['QUANTIDADE'];

                                    $objProdutoQtde = new c_produto_estoque();

                                    $QTDISP = $objProdutoQtde->produtoDisponivel(
                                        $this->m_empresacentrocusto,
                                        $arrItemPedido[$i]['ITEMESTOQUE'],
                                        $transaction->id_connection
                                    );

                                    if ($QTDISP >= $quantDigitada) {
                                        $objProdutoQtde->produtoReserva(
                                            $this->m_empresacentrocusto,
                                            "PED",
                                            $numPedido,
                                            $arrItemPedido[$i]['ITEMESTOQUE'],
                                            $quantDigitada,
                                            $transaction->id_connection
                                        );
                                        $arrItemPedido[$i]['QUANTIDADE'] = $arrItemPedido[$i]['QTSOLICITADA'];
                                    } else {
                                        $this->m_msg = 'Estoque insuficiente para o produto '
                                            . ($arrItemPedido[$i]['DESCRICAO'] ?? $arrItemPedido[$i]['ITEMESTOQUE'])
                                            . ': solicitado ' . $arrItemPedido[$i]['QTSOLICITADA']
                                            . ', disponível ' . $QTDISP . '.';
                                        if ((int) $sitPedido === 13) {
                                            $this->m_msg .= ' Pedido em encomenda — a NF só pode ser emitida após liberação de estoque.';
                                        }
                                        throw new Exception($this->m_msg);
                                    }
                                endif;
                            } else {
                                $arrItemPedido[$i]['QUANTIDADE'] = $arrItemPedido[$i]['QTSOLICITADA'];
                            }


                            // verifica se envia para nfe item com valor de desconto separado ou valor liquido do item
                            if ($tipoNfeItemDesconto == 'L') {
                                $quantidade = $arrItemPedido[$i]['QUANTIDADE'];
                                $unitario = $arrItemPedido[$i]['UNITARIO'];
                                $desconto = $arrItemPedido[$i]['DESCONTO'];
                                $totalItem = ($quantidade * $unitario) - $desconto;
                                $unitario = $totalItem / $quantidade;

                                $arrItemPedido[$i]['TOTAL'] = $totalItem;
                                $arrItemPedido[$i]['UNITARIO'] = $unitario;
                                $arrItemPedido[$i]['DESCONTO'] = 0;
                            } else {
                                $arrItemPedido[$i]['TOTAL'] = $arrItemPedido[$i]['QUANTIDADE'] * $arrItemPedido[$i]['UNITARIO']; //QTSOLICITADO - ALTERADO 18/07/2019 
                            }

                            if ($arrItemPedido[$i]['TOTAL'] <= 0):
                                $this->m_msg = "Erro: Valor Total Zerado!";
                                throw new Exception($this->m_msg);
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
                            $objNfProduto->setFrete($arrItemPedido[$i]['FRETE'], true);

                            //NEW CONDITION FOR SALE WITHIN THE STATE 
                            if ($this->vendaPresencial == 'S') {
                                $expNfeConfig = explode('|', ADMnfeConfig01);
                                $ufPessoa = $expNfeConfig[3];
                            } else {
                                $ufPessoa = $objNotaFiscal->getUfPessoa();
                            }

                            $difalContext = [
                                'ie' => trim($arrPedido[0]['INSCESTRG'] ?? ''),
                                'vendaPresencial' => $this->vendaPresencial ?? 'N',
                            ];

                            $result = $this->calculaImpostosNfe(
                                $objNfProduto,
                                $objNotaFiscal->getIdNatop(),
                                $ufPessoa,
                                $objNotaFiscal->getTipoPessoa(),
                                $this->m_empresacentrocusto,
                                null,
                                $difalContext
                            );

                            if (!$result):
                                $this->m_msg = "Tributos não localizado " . $objNfProduto->getDescricao() . " Nat. Operação:" . $objNotaFiscal->getIdNatop() .
                                    "<br> UF:" . $objNotaFiscal->getUfPessoa() . " Tipo:" . $objNotaFiscal->getTipoPessoa() .
                                    " CST:" . $objNfProduto->getOrigem() . $objNfProduto->getTribIcms() .
                                    "<br> NCM:" . $objNfProduto->getNcm() . " CEST:" . $objNfProduto->getCest() . "<br>";
                                throw new Exception($this->m_msg);
                            endif;
                            $objNfProduto->setCustoProduto($arrItemPedido[$i]['CUSTOPRODUTO']);

                            $objNfProduto->setNrSerie(''); // VERIFICAR SISTEMA PARA INCLUIR OS NUMEROS DE SÉRIES
                            $objNfProduto->setDataGarantia('');
                            $objNfProduto->setLote($arrItemPedido[$i]['FABLOTE']);
                            $objNfProduto->setDataValidade($arrItemPedido[$i]['FABDATAVALIDADE']);
                            $objNfProduto->setDataFabricacao($arrItemPedido[$i]['FABDATAFABRICACAO']);
                            if (!empty($arrItemPedido[$i]['NUMEROOC'])){
                                $objNfProduto->setOrdem($arrItemPedido[$i]['NUMEROOC']);
                            }else{
                                $objNfProduto->setOrdem($arrItemPedido[$i]['ID']);
                            }
                            if (!empty($arrItemPedido[$i]['NITEMPED'])){
                                $objNfProduto->setNItemPed($arrItemPedido[$i]['NITEMPED']);
                            }else{
                                $objNfProduto->setNItemPed($arrItemPedido[$i]['NRITEM']);
                            }
                            $objNfProduto->setProjeto($arrItemPedido[$i]['PROJETO']);
                            $objNfProduto->setDataConferencia($arrItemPedido[$i]['DATACONFERENCIA']);

                            $objNfProduto->setCodigoNota($arrItemPedido[$i]['CODIGONOTA']);
                            $objNfProduto->setDespAcessorias($arrItemPedido[$i]['DESPACESSORIAS'], true);

                            $result = $objNfProduto->incluiNotaFiscalProduto($transaction->id_connection);
                            // verificar inclusao item
                            if (is_string($result)):
                                $this->m_msg = $result;
                                $result = false;
                                throw new Exception($this->m_msg);
                            endif;

                            //***************
                            //RETIRAR DE RESERVA E BAIXAR DO ESTOQUE INC O NUM NF
                            //********************
                            $uniFrac = $arrItemPedido[$i]['UNIFRACIONADA'];
                            $ifControlaEstoque = (($controlaEstoque == 'S') && ($uniFrac == 'N'));
                            //                        if ($controlaEstoque == 'S'):
                            if ($ifControlaEstoque):
                                $codProdBaixa = $objNfProduto->getCodProduto();
                                if ($objProdutoEstoque->existeBaixaReservaFinanceiroSemNf(
                                    $this->m_empresacentrocusto,
                                    $this->getId(),
                                    $codProdBaixa,
                                    $transaction->id_connection
                                )) {
                                    $objProdutoEstoque->vinculaNfBaixaFinanceiro(
                                        $this->m_empresacentrocusto,
                                        $this->getId(),
                                        $idGerado,
                                        $codProdBaixa,
                                        $transaction->id_connection
                                    );
                                } else {
                                    $objProdutoEstoque->produtoBaixaReserva(
                                        $this->m_empresacentrocusto,
                                        $this->getId(),
                                        $idGerado,
                                        $codProdBaixa,
                                        $transaction->id_connection
                                    );
                                }
                            endif;
                        } //for

                        //***************************************************
                        // baixa pedido
                        //***************************************************
                       
                        $this->setSituacao($situacaoBaixa);
                        $this->setPedido($this->getId());


                        // ************** 
                        // lanca parcelas financeiro
                        //***************

                        $objFinanceiro = new c_lancamento();
                        $existeFin = $objFinanceiro->select_lancamento_doc('PED', $this->getPedido());
                        if (!is_array($existeFin)) {
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
                                $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $transaction->id_connection);
                            endif;
                        }
                        // Lançamento do pedido já existente: não duplica parcelas; alteraParcelaPedidoNf
                        // abaixo vincula SERIE=NFS e DOCTO=número da NF quando integraFin == 'S'.

                        //; commit transação
                        //$transaction->commit($transaction->id_connection);

                        // valida e autoriza nf automaticamente
                        if ($validaNfAuto == 'S') {
                            // Gera e altera numero NF
                            $numNf = $objNotaFiscal->geraNumNf($objNotaFiscal->getModelo(), $objNotaFiscal->getSerie(), $this->m_empresacentrocusto, $transaction->id_connection);
                            if (intval($numNf) == 0):
                                $this->m_msg = "Idendificador NF >>> " . $idGerado . " - Número não Gerado";
                                $result = false;
                                throw new Exception($this->m_msg);
                            endif;
                            $objNotaFiscal->setId($idGerado);
                            $objNotaFiscal->setNumero($numNf);
                            $objNotaFiscal->alteraNfNumero($transaction->id_connection);

                            if ($integraFin == 'S'):
                                $objFinanceiro->alteraParcelaPedidoNf($numPedido, $numNf, $transaction->id_connection);
                            endif;

                            // valida e autoriza nf
                            $exporta = new p_nfe_40();
                            $result = $exporta->Gera_XML($idGerado, $this->m_empresacentrocusto, '', $transaction->id_connection, '', 'PED');

                            if ($result['cStatus'] == '100') {
                            
                                $this->atualizarFieldPedido(9);

                                // FALTA RETORNAR O RECIBO
                                $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo']);

                                if ($this->usarCredito == 'S' && $integraFin == 'S') {
                                    $totalPostCred = isset($this->parmPost['total']) ? $this->parmPost['total'] : null;
                                    $this->atualizaSaldoCredito($this->credito, $totalPostCred);
                                    $objFinanceiro->aplicaCreditoNasParcelasPedidoNf(
                                        $numPedido,
                                        $this->getCliente(),
                                        $this->credito,
                                        $transaction->id_connection
                                    );
                                }

                                //; commit transação
                                $transaction->commit($transaction->id_connection);


                                #########################################################################
                                ################### ENVIO DE BOLETO PARA API BANCARIA ###################
                                #########################################################################

                                // Obtém o parâmetro de envio de boleto da empresa
                                $processa_boleto = $this->processaBoletoApi($this->getId());

 
                                ################## FIM ENVIO DE BOLETO PARA API BANCARIA ###################
                                ##### AGORA O EMAIL E ENVIADO DA NOVA TELA DE NOTA E BOLETO #####

                                // Envia email com boletos após o commit
                                // VALIDAR
                                // if (isset($arrParcelas)) {
                                //     try {

                                //         $dir = dirname(__FILE__);
                                //         require_once($dir . "/../../forms/blt/p_boleto_email.php");
                                //         $obj_email = new p_boleto_email();

                                //         // Se o boleto nao foi processado com sucesso, lança uma exceção
                                //         if ($processa_boleto['sucesso'] !== true) {
                                //             throw new Exception('Erro ao processar boleto: ' . $processa_boleto['mensagem']);
                                //         }
                                
                                //         // para teste
                                //         //$idGerado = 146;
                                //         //$numNf = 1239;
                                //         $obj_email->sendDocumentsEmail($idGerado, $numNf, $objNotaFiscal->getPessoa(), $this->getId());
                                        
                                //     } catch (Exception $e) {

                                //         // Registra o erro no log
                                //         p_boleto_email::registraLogErro($numNf, $idGerado, $e->getMessage(), $this->m_userid);
                                        
                                //         // Opcionalmente, loga também no error_log do servidor
                                //         error_log("Erro ao enviar email da nota {$numNf}: " . $e->getMessage());
                                //     }
                                // }

                                //IMPRESSAO OLD
                                $printDanfe = new p_nfephp_imprime_danfe();
                                if ($this->m_opcao == '') {
                                    $printDanfe->printDanfe($idGerado, $objNotaFiscal->getDoc(), $objNotaFiscal->getOrigem(), $result['cDanfe'], $objNotaFiscal->getDoc(), 'pedido_venda_nf');
                                } else {
                                    //OLD 
                                    //$printDanfe->printDanfe($idGerado, $objNotaFiscal->getNumero(), $objNotaFiscal->getSerie(), $result['cDanfe'], $objNotaFiscal->getDoc(), $this->m_opcao);

                                    //NEW   
                                    echo "<script>";
                                    echo "window.open('index.php?mod=est&opcao=imprimir&form=nota_fiscal_boleto_bancario&id=" . $idGerado . "', '_blank');";echo "</script>";

                                    //NEW - chama o controle do form gerente pedido para atualizar o historico do form gerente pedido
                                    define('APENAS_FUNCOES', true);
                                    require_once("p_pedido_venda_gerente_novo.php");
                                    
                                    $objeto_gerencia_pedido = new p_pedido_venda_conferecia_novo($this->data_history, '', '', '');
                                    $objeto_gerencia_pedido->controle();

                                }
                            } elseif ($result['cStatus'] == '105' or $result['cStatus'] == '103') { //LOTE EM PROCESSAMENTO

                                $this->atualizarFieldPedido(9);
                                $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo'], $result['chave'], $result['codSituacao']);
                                //; commit transação
                                $transaction->commit($transaction->id_connection);

                                $this->desenhaCadastroPedido('<b>Nota Fiscal em processamento!</b><br> Consulte em notas fiscais pelo Nº <b>' . $numNf . '</b> para finalizar o processo de emissão.');

                            } elseif ($result['cStatus'] == '539') { //DUPLICIDADE

                                $this->atualizarFieldPedido(3);

                                $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo'], $result['chave'], $result['codSituacao']);
                                
                                //; commit transação
                                $transaction->commit($transaction->id_connection);

                                $pattern = "/\[(.*?)\]/";
                                if (preg_match($pattern, $result["motivo"], $matches)) {
                                    // A chave de acesso estará em $matches[1]
                                    $chaveDeAcesso = $matches[1];
                                } else {
                                    $chaveDeAcesso = "Chave de Acesso não encontrada na string.";
                                }
                                $this->desenhaCadastroPedido('Duplicidade de NF-e, com diferenca na Chave de Acesso!<br> Chave original: <b>' . $chaveDeAcesso . '</b>');
                            } else {
                                // roollback transação
                                //$transaction->rollback($transaction->id_connection);  
                                //$this->atualizarFieldPedido(9);
                                $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo'], $result['chave'], $result['codSituacao']);
                                //; commit transação
                                $transaction->commit($transaction->id_connection);
                                $this->desenhaCadastroPedido('Nota Fiscal gerada com situação: <b>' . $result['situacao'] . '</b><br> Consulte em notas fiscais pelo Nº ' . $numNf . "<br>Motivo: " . $result['motivo'] . "<br>");
                            }
                        } else {
                            $objNotaFiscal->alteraNfNumero($transaction->id_connection, $result['recibo'], $result['codSituacao']);
                            //; commit transação
                            $transaction->commit($transaction->id_connection);
                            $this->mostraPedido('Autorização automatica desativada na natureza de operação');
                        }
                    } catch (Error $e) {
                        $transaction->rollback($transaction->id_connection);
                        // $this->desenhaCadastroPedido($result."<br>".$e->getMessage());
                        // throw new Exception($e->getMessage()."Nf Não foi gerado " );
                        $this->desenhaCadastroPedido($e->getMessage(), 'error');
                    } catch (Exception $e) {
                        //echo 'Caught exception: ',  $e->getMessage(), "\n";
                        if ($this->nfAberto == true):
                            $transaction->commit($transaction->id_connection);
                        else:
                            if (isset($transaction)):
                                $transaction->rollback($transaction->id_connection);
                            endif;
                        endif;
                        // $this->desenhaCadastroPedido("Identificador NF: ".$idGerado."<br>".$e->getMessage()."<br>");
                        // $this->desenhaCadastroPedido($result."<br>".$e->getMessage());
                        $this->desenhaCadastroPedido($e->getMessage(), 'error');
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
                case 'geraEspelhoJson':
                    if (!$this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                        break;
                    }

                    try {
                        $arrPedido   = $this->select_pedidoVenda();
                        $arrProdutos = $this->select_pedido_item_id('1');

                        if (!$arrPedido || !$arrProdutos) {
                            $this->m_msg = "Erro ao gerar espelho JSON da NF-e";
                            throw new Exception($this->m_msg);
                        }

                        $contexto = [
                            'empresa_id' => $this->m_empresaid,
                            'filial_id'  => $this->m_empresacentrocusto,
                            'tp_amb'     => isset($this->m_tp_amb) ? $this->m_tp_amb : 2,
                            'id_nf'      => null,
                            'idNatop'    => $this->getIdNatop(),
                        ];

                        $nfeJsonTags = new c_nfe_json_tags();
                        $json        = $nfeJsonTags->monta_json_espelho('PED', $arrPedido, $arrProdutos, $contexto);

                        $espelho = new p_nfe_json_espelho();
                        $espelho->monta_contexto($json);
                        $xml = $espelho->processa_blocos([
                            'ide',
                            'emitente',
                            'destinatario',
                            'produtos',
                            'totais',
                            'transporte',
                            'cobranca_pagamentos',
                            'infAdic',
                        ]);

                        if (!preg_match('/<infNFe[^>]*Id="NFe(\d+)"/', $xml, $m)) {
                            throw new Exception('Não foi possível obter a chave da NF-e do XML gerado.');
                        }

                        $espelhoNfe = new p_espelho_nfe();
                        $pdfUrl     = $espelhoNfe->gera_DANFE($xml, $m[1], null);

                        if (empty($pdfUrl)) {
                            throw new Exception('Erro ao gerar DANFE de espelho.');
                        }

                        echo "<script type=\"text/javascript\">
                                window.open('".$pdfUrl."', 'DANFE_ESPELHO',
                                    'toolbar=no,location=no,resizable=yes,menubar=no,width=950,height=900,scrollbars=yes');
                              </script>";
                        $this->formNf = true;
                        $this->desenhaCadastroPedido("");
                        exit;
                    } catch (Error $e) {
                        $this->formNf = true;

                        $msg = $e->getMessage();
                        $msg .= $this->montaBotoesAjudaErroEspelho($msg, $arrPedido ?? []);

                        $this->desenhaCadastroPedido($msg, 'error');
                        break;
                    } catch (Exception $e) {
                        $this->formNf = true;

                        $msg = $e->getMessage();
                        $msg .= $this->montaBotoesAjudaErroEspelho($msg, $arrPedido ?? []);

                        $this->desenhaCadastroPedido($msg, 'error');
                        break;
                    }
                break;
            case 'cadastraFinanceiro':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->formNf = false;

                    $arrPedido = $this->select_pedidoVenda();
                    $objContaBloq = new c_conta();
                    if (is_array($arrPedido) && strtoupper((string) $objContaBloq->contaBloqueada((int) ($arrPedido[0]['CLIENTE'] ?? 0))) === 'S') {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Cliente bloqueado',
                                text: 'Cliente bloqueado. Verifique com o financeiro.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }
                    if (trim((string) ($arrPedido[0]['SERIE'] ?? '')) === '65') {
                        try {
                            $this->aplicaParametrosCupomFiscal();
                        } catch (Exception $e) {
                            $this->desenhaCadastroPedido($e->getMessage(), 'error');
                            break;
                        }
                    }

                    $cond_pagamento = $this->getCondPg();

                    $busca_cond_pgto = new c_banco;
                    $busca_cond_pgto->setTab("FAT_COND_PGTO");
                    $arrCondPgto = $busca_cond_pgto->getRecord("ID=" . $cond_pagamento);
                    $situacaoLcto = $arrCondPgto[0]["SITUACAOLCTO"];
                    $busca_cond_pgto->close_connection();

                    // Testa se a condição de pagamento é de extrato de pagamento
                    if ($situacaoLcto == 'E') {

                        // Busca gênero de extrato
                        $est_parametro = new c_parametro;
                        $est_parametro = $est_parametro->searchEstParametroFinanceiro($this->m_empresacentrocusto);
                        if ($est_parametro) {
                            $genero_extrato = $est_parametro[0]["GENERO_EXTRATO"];
                        }

                        // Monta dados para cadastro de parcela de extrato
                        $dados_parcela_extrato = [
                            'PESSOA' => $_POST["cliente"],
                            'PESSOAFORNECEDOR' => '',
                            'TIPOLANCAMENTO' => $est_parametro[0]["TIPOLANCAMENTO"],
                            'SITUACAOLANCAMENTO' => 'A',
                            'LANCAMENTO' => date('Y-m-d H:i:s'),
                            'COMPETENCIA' => $_POST["venc1"],
                            'GENERO' => $est_parametro[0]["GENERO_EXTRATO"],
                            'CENTROCUSTO' => $this->m_empresacentrocusto,
                            'VALOR' => $_POST["valor1"],
                            'OBS' =>  'PEDIDO: ' . $this->getId() . ' ' . $_POST["obs1"] ,
                        ];

                        // Cria objeto de extrato
                        $obj_extrato = new c_extrato();
                        $result = $obj_extrato->addParcelaExtratoOrigemPS($dados_parcela_extrato);

                        // Testa resultado da inclusão de parcela de extrato
                        if ($result) {

                            //atualiza situação do pedido para 9 - PAGO
                            $objPed = new c_pedidoVenda();
                            $objPed->setId($this->getId());
                            $objPed->atualizarField("SITUACAO", '9');

                            $textoExtratoSucesso = 'Parcela cadastrada no extrato!';
                            try {
                                $this->pedidoPsPosFinanceiroBaixaEstoque(null);
                            } catch (Exception $eExtratoPs) {
                                $textoExtratoSucesso .= ' Atenção na baixa de estoque: ' . $eExtratoPs->getMessage();
                            }

                            // Exibe mensagem de sucesso
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Parcela cadastrada no extrato!',
                                text: " . $textoExtratoSucesso . ",
                                width: 510,
                                timer: 2000,
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        } else {
                            // Exibe mensagem de erro
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro ao cadastrar parcela de extrato!',
                                width: 510,
                                timer: 2000,
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        }
                    } else {

                        //configuracao de parametros
                        $parametros = new c_banco;
                        $parametros->setTab("EST_NAT_OP");
                        $arrNatOp = $parametros->getRecord("ID=" . $this->getIdNatop());
                        $integraFin = $arrNatOp[0]["INTEGRAFIN"];

                        $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
                        $arrParcelas = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'));

                        // ************** 
                        // lanca parcelas financeiro
                        //***************
                        $objFinanceiro = new c_lancamento();

                        $arrParamFin['PESSOA'] = $this->getCliente();
                        $arrParamFin['DOCTO'] = $this->getId();
                        $arrParamFin['SERIE'] = 'PED';
                        $arrParamFin['GENERO'] = $this->getGenero();
                        $arrParamFin['CENTROCUSTO'] = isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto;
                        $arrParamFin['USER'] = $this->m_userid;
                        $arrParamFin['ORIGEM'] = "PED";
                        $arrParamFin['NUMLCTO'] = $this->getId();
                        $arrParamFin['TIPOLANCAMENTO'] = "R";
                        $arrParamFin['OBS'] = $arrPedido[0]['OBS'];
                        if ($integraFin == 'S') {
                            $posFinanceiroPs = isset($this->parmPost['pos_financeiro_ps']) ? (int) $this->parmPost['pos_financeiro_ps'] : 3;
                            if (!in_array($posFinanceiroPs, [3, 9, 13], true)) {
                                $posFinanceiroPs = 3;
                            }
                            if ((int) ($arrPedido[0]['SITUACAO'] ?? 0) === 13) {
                                $posFinanceiroPs = 13;
                            }

                            $txPedidoPs = new c_banco();
                            $txPedidoPs->inicioTransacao($txPedidoPs->id_connection);
                            $connFinPs = $txPedidoPs->id_connection;

                            $existeFinAtivo = $objFinanceiro->select_lancamento_doc('PED', $this->getId());
                            if (is_array($existeFinAtivo) && count($existeFinAtivo) > 0) {
                                $resultAdd = true;
                            } else {
                                $resultAdd = $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $connFinPs);
                            }

                            if ($this->m_opcao == "pedido_venda_gerente_novo" or $this->m_opcao == "pedido_venda_gerente" or $this->t_origem == 'pedido_ps') {
                                $financeiroOk = ($resultAdd === true) || is_int($resultAdd);
                            } else {
                                $financeiroOk = ($resultAdd === null) || ($resultAdd === true);
                            }

                            if ($financeiroOk) {
                                try {
                                    $this->pedidoPsPosFinanceiroBaixaEstoque($connFinPs);
                                    $objPedPsSit = new c_pedidoVenda();
                                    $objPedPsSit->setId($this->getId());
                                    $objPedPsSit->atualizarField('SITUACAO', (string) $posFinanceiroPs, $connFinPs);
                                    if (is_int($resultAdd) && $this->usarCredito == 'S') {
                                        $objFinanceiro->aplicaCreditoNasParcelasPedidoNf($this->getId(), $this->getCliente(), $this->credito, $connFinPs);
                                    }
                                    $txPedidoPs->commit($txPedidoPs->id_connection);
                                } catch (Exception $ePedidoPsFin) {
                                    $txPedidoPs->rollback($txPedidoPs->id_connection);
                                    $financeiroOk = false;
                                }
                            } else {
                                $txPedidoPs->rollback($txPedidoPs->id_connection);
                            }
                            if (is_int($resultAdd) && $this->usarCredito == 'S' && $financeiroOk) {
                                $objPed = new c_pedidoVenda();
                                $objPed->setId($this->getId());
                                $objPed->atualizaSaldoCredito($this->credito, $this->getTotal('B'));
                            }

                            if ($this->m_opcao == "pedido_venda_gerente_novo" or $this->m_opcao == "pedido_venda_gerente") {
                                if ($financeiroOk) {
                                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                                    echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sucesso',
                                        width: 510,
                                        text: 'Financeiro cadastrado!',
                                        confirmButtonText: 'OK'
                                    });
                                    </script>";
                                } else {
                                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                                    echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: '',
                                        width: 510,
                                        text: 'Erro ao cadastrar financeiro!',
                                        confirmButtonText: 'OK'
                                    });
                                    </script>";
                                }
                            } else {
                                if ($financeiroOk) {
                                    if ($this->t_origem != 'pedido_ps') {
                                        $objClassNf = new c_nota_fiscal;
                                        $objClassNf->setId($arrParamFin["DOCTO"]);
                                        $objClassNf->alteraSituacao('B');
                                    }
                                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                                    echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sucesso',
                                        width: 510,
                                        text: 'Financeiro cadastrado!',
                                        confirmButtonText: 'OK'
                                    });
                                    </script>";
                                } else {
                                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                                    echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: '',
                                        width: 510,
                                        text: 'Erro ao cadastrar financeiro!',
                                        confirmButtonText: 'OK'
                                    });
                                    </script>";
                                }
                            }
                        } else {
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: '',
                                width: 510,
                                text: 'Natureza de operação NÃO integra financeiro!',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        }
                    }


                    if ($this->t_origem == 'pedido_ps') {
                        require_once(ADMforms . "/ped/p_pedido_ps.php");
                        $objeto_pedido_ps = new p_pedido_ps();
                        $objeto_pedido_ps->m_letra = $this->getId();

                    } else {
                        require_once(ADMforms . "/ped/p_pedido_venda_gerente_novo.php");
                        $objPedGerente = new p_pedido_venda_conferecia_novo('', '', '', '');
                        $objPedGerente->controle('');
                    }
                }
                break;
            case 'cadastraFinanceiroNotaFiscal':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    //configuracao de parametros
                    $parametros = new c_banco;
                    $parametros->setTab("EST_NAT_OP");
                    $arrNatOp = $parametros->getRecord("ID=" . $this->getIdNatop());
                    $integraFin = $arrNatOp[0]["INTEGRAFIN"];
                    $parametros->close_connection();

                    //busca se é recebimento ou pagamento
                    $genero = new c_banco;
                    $genero->setTab("FIN_GENERO");
                    $arrGenero = $genero->getRecord("GENERO='" . $this->getGenero() . "'");
                    $tipoLancamento = $arrGenero[0]["TIPOLANCAMENTO"];
                    $genero->close_connection();

                    $this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");
                    $arrParcelas = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'));

                    // ************** 
                    // lanca parcelas financeiro
                    //***************
                    $objFinanceiro = new c_lancamento();

                    $arrParamFin['PESSOA'] = $this->getCliente();
                    $arrParamFin['DOCTO'] = $this->getId();
                    $arrParamFin['SERIE'] = 'NFE';
                    $arrParamFin['GENERO'] = $this->getGenero();
                    $arrParamFin['CENTROCUSTO'] = isset($arrPedido[0]['CCUSTO']) ? $arrPedido[0]['CCUSTO'] : $this->m_empresacentrocusto;
                    $arrParamFin['USER'] = $this->m_userid;
                    $arrParamFin['ORIGEM'] = "NFE";
                    $arrParamFin['NUMLCTO'] = $this->getId();
                    //seta se é pagamento ou recebimento
                    if ($tipoLancamento == 'R') {
                        $arrParamFin['TIPOLANCAMENTO'] = "R";
                    } else {
                        $arrParamFin['TIPOLANCAMENTO'] = "P";
                    }

                    $arrParamFin['OBS'] = $arrPedido[0]['OBS'];

                    if ($integraFin == 'S') {
                        $resultAdd = $objFinanceiro->addParcelas($arrParamFin, $arrParcelas);
                        if (is_int($resultAdd)) {
                            //Altera situação da nota fiscal
                            $objClassNf = new c_nota_fiscal;
                            $objClassNf->setId($arrParamFin["DOCTO"]);
                            $objClassNf->alteraSituacao('B');
                            //para impressao das novas msgs
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                                echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sucesso',
                                    width: 510,
                                    text: 'Financeiro cadastrado!',
                                    confirmButtonText: 'OK'
                                });
                                </script>";
                        } else {
                            echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                            echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: '',
                                width: 510,
                                text: 'Erro ao cadastrar financeiro!',
                                confirmButtonText: 'OK'
                            });
                            </script>";                            
                        }
                    } else {
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: '',
                                width: 510,
                                text: 'Natureza de operação " . $arrNatOp[0]["INTEGRAFIN"] . " configurado para não integrar financeiro!',
                                confirmButtonText: 'OK'
                            });
                            </script>";  
                    }

                    //$objPedGerente = new p_pedido_venda_conferecia_novo('', '', '', '');
                    //$objPedGerente->controle('');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    /**
     * @param array<int,array<string,mixed>>|false|null $arrPedido
     */
    /**
     * @param array<int,array<string,mixed>>|false|null $arrPedido
     */
    private function pedidoEmEncomenda($arrPedido = null): bool
    {
        if ($arrPedido === null) {
            $arrPedido = $this->select_pedidoVenda();
        }
        return is_array($arrPedido) && (int) ($arrPedido[0]['SITUACAO'] ?? 0) === 13;
    }

    /**
     * @param array<int,array<string,mixed>>|false|null $arrPedido
     */
    private function ehCupomFiscalContext($arrPedido = null): bool
    {
        if (trim((string) $this->tipoDocFiscal) === '65') {
            return true;
        }
        if ($arrPedido === null) {
            $arrPedido = $this->select_pedidoVenda();
        }
        if (is_array($arrPedido) && isset($arrPedido[0])) {
            if (trim((string) ($arrPedido[0]['SERIE'] ?? '')) === '65') {
                return true;
            }
        }
        return false;
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg = NULL)
    {

        $descCondPgto = "";
        $parcelas = 0;
        $valorParcelas = 0;
        $totalParcelas = 0;
        $numParcelas = 0;
        $fin = [];

        //$pedido = $this->select_pedidoVenda();
        $this->setPedidoVenda(); // seta dados pedido e cliente
        $arrPedidoDoc = $this->select_pedidoVenda();
        $pedidoSituacao = (int) ($arrPedidoDoc[0]['SITUACAO'] ?? 0);
        if ($pedidoSituacao === 13 && $this->formNf === true) {
            $this->formNf = false;
            if ($mensagem === null || $mensagem === '') {
                $mensagem = 'Pedido em <b>encomenda</b> — emissão de NF bloqueada até liberação de estoque.';
                $tipoMsg = 'warning';
            }
        }
        $tipoDocPedido = trim((string) ($arrPedidoDoc[0]['SERIE'] ?? ''));

        $centrocustoParam = !empty($this->getCentroCusto()) ? $this->getCentroCusto() : $this->m_empresacentrocusto;
        $ehCupomFiscal = $this->ehCupomFiscalContext(is_array($arrPedidoDoc) ? $arrPedidoDoc : null);
        if ($ehCupomFiscal) {
            $this->tipoDocFiscal = '65';
        }
        $modeloParam = ($ehCupomFiscal || $tipoDocPedido === '65') ? '65' : '55';
        $objParametro = new c_parametro();
        $paramFilial = $objParametro->getParametroFinanceiroSaida($centrocustoParam, $modeloParam);

        $cfop = $paramFilial['CFOP'] ?? null;
        $natOperacao = $paramFilial['NATOPERACAO'] ?? null;
        $condPgto = $this->getCondPg();
        if ($condPgto == 0):
            $condPgto = $paramFilial['CONDPGTO'] ?? null;
        endif;
        $genero = $paramFilial['GENERO'] ?? null;
        $conta = $paramFilial['CONTA'] ?? null;
        $serie = $paramFilial['SERIE'] ?? null;

        if ($ehCupomFiscal) {
            try {
                $this->aplicaParametrosCupomFiscal();
                $this->vendaPresencial = 'S';
            } catch (Exception $e) {
                if ($mensagem === null || $mensagem === '') {
                    $mensagem = $e->getMessage();
                }
                if ($tipoMsg === null || $tipoMsg === '') {
                    $tipoMsg = 'error';
                }
            }
        } elseif (isset($this->parmPost['idNatop'])) {
            $this->setIdNatop($this->parmPost['idNatop']);
        } elseif ($this->getIdNatop() == 'NULL') {
            $this->setIdNatop($natOperacao);
        }
        if (!$ehCupomFiscal) {
            if (isset($this->parmPost['serie']) && $this->parmPost['serie'] !== '') {
                $this->setSerie($this->parmPost['serie']);
            } elseif ($tipoDocPedido === '55') {
                $this->setSerie($serie);
            } else {
                $pedSerie = trim((string) ($arrPedidoDoc[0]['SERIE'] ?? ''));
                $this->setSerie(($pedSerie !== '' && $pedSerie !== '65' && $pedSerie !== '55') ? $pedSerie : $serie);
            }
        }
        if ($this->alteraCondPgto == true) {
            $this->setCondPg(isset($this->parmPost['condPgto']) ? $this->parmPost['condPgto'] : $condPgto);
        }
        if (isset($this->parmPost['genero']) && $this->parmPost['genero'] !== '') {
            $this->setGenero($this->parmPost['genero']);
        } elseif (!empty($genero)) {
            $this->setGenero($genero);
        }
        if (isset($this->parmPost['conta']) && $this->parmPost['conta'] !== '') {
            $this->setContaDeposito($this->parmPost['conta']);
        } elseif (!empty($conta)) {
            $this->setContaDeposito($conta);
        }


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
        $this->smarty->assign('t_origem', $this->t_origem);
        $this->smarty->assign('pedidoSituacao', $pedidoSituacao);
        $this->smarty->assign('pedidoEncomenda', $pedidoSituacao === 13);
        $this->smarty->assign('posFinanceiroPs', $pedidoSituacao === 13 ? 13 : 3);

        try {
            $totalPed = $this->getTotal();
            $totalServ = $this->getValorServicos();
            $totalProd = $this->getTotalProdutos();

            if ($totalPed  <= 0) {
                $totalSemServico = 0;
            } else {
                $totalSemServico = $this->getTotal() - $this->getValorServicos();
            }
        } catch (Exception $e) {
            echo 'Exceção capturada: ',  $e->getMessage(), "\n";
        }
        // $totalSemServico = $this->getTotal('B') - $valorServico;
        $this->setTotal($totalSemServico);

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
        $this->smarty->assign('placaVeiculo', $this->placaVeiculo);
        $this->smarty->assign('codAntt', $this->codAntt);
        $this->smarty->assign('uf', $this->uf);
        $this->smarty->assign('volume', $this->volume);
        $this->smarty->assign('volEspecie', $this->volEspecie);
        $this->smarty->assign('volMarca', $this->volMarca);
        $this->smarty->assign('volPesoLiq', $this->volPesoLiq);
        $this->smarty->assign('volPesoBruto', $this->volPesoBruto);
        $this->smarty->assign('dataSaidaEntrada', $this->dataSaidaEntrada);
        $this->smarty->assign('obs', $this->obs);
        $this->smarty->assign('saldoCredito', $this->getSaldoCredito());

        // ########## NATUREZA OPERACAO ##########
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where (tipo='S') order by id";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $natOperacao_ids[$i] = $result[$i]['ID'];
            $natOperacao_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natOperacao_ids', $natOperacao_ids);
        $this->smarty->assign('natOperacao_names', $natOperacao_names);
        $this->smarty->assign('natOperacao_id', $this->getIdNatop());

        // COMBOBOX CONDICAO PAGAMENTO
        $situacaoLcto = 'A';
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            if ($this->getCondPg() == $result[$i]['ID']):
                $descCondPgto = $result[$i]['DESCRICAO'];
                $situacaoLcto = $result[$i]['SITUACAOLCTO'];
            endif;
            $condPgto_ids[$i] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $financeiroCondExtratoTpl = ($situacaoLcto === 'E');
        $this->smarty->assign('descCondPgto', "$descCondPgto");
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPg());
        $this->smarty->assign('financeiroCondExtrato', $financeiroCondExtratoTpl);


        // COMBOBOX GENERO
        $consulta = new c_banco();
        $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero where (tipolancamento = 'R') ORDER BY descricao;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $genero_ids[$i] = $result[$i]['ID'];
            $genero_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('genero_ids', $genero_ids);
        $this->smarty->assign('genero_names', $genero_names);
        $this->smarty->assign('genero_id', $this->getGenero());

        // COMBOBOX CONTA
        $consulta = new c_banco();
        $sql = "SELECT conta as id, nomeinterno as descricao FROM fin_conta where status ='A' ORDER BY id;";
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
        for ($i = 0; $i < count($result); $i++) {
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
        for ($i = 0; $i < count($result); $i++) {
            $situacaoLanc_ids[$i] = $result[$i]['ID'];
            $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
        $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
        $this->smarty->assign('situacaoLanc_id', $situacaoLcto);

        // modalidade frete
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='modFrete')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
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
            $centroCusto_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto());

        // BOOLEAN ##############################
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='BOOLEAN')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $boolean_ids[$i] = $result[$i]['ID'];
            $boolean_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('boolean_ids', $boolean_ids);
        $this->smarty->assign('boolean_names', $boolean_names);
        $this->smarty->assign('vendaPresencial', $ehCupomFiscal ? 'S' : 'N');
        $this->smarty->assign('ehCupomFiscal', $ehCupomFiscal);
        $this->smarty->assign('tipoDocFiscal', $ehCupomFiscal ? '65' : '');
        $this->smarty->assign('cpfNota', trim((string) ($this->parmPost['cpf'] ?? '')));
        $this->smarty->assign('tipoDocPedido', $tipoDocPedido);
        $consultaFin = new c_banco();
        $consultaFin->setTab("FIN_LANCAMENTO");
        $numPedFin = $consultaFin->getField("NUMLCTO", "NUMLCTO = '" . $this->getPedido() . "' AND ORIGEM ='PED' AND SITPGTO<>'C'");
        $consultaFin->close_connection();
        if (empty($numPedFin)) {
            // CALCULA PARCELAS
            $fin = $this->calculaParcelasNfe($descCondPgto, $this->getTotal());
            $this->smarty->assign('parcelasCadastrada', false);
        } else {
            $lancObj = new c_lancamento();
            $fin = $lancObj->select_lancamento_doc('PED', $this->getPedido());
            for ($i = 0; $i < count($fin); $i++) {
                // Mantém o ID original e cria campo separado para o nome
                $con = new c_banco();
                $con->setTab("FIN_CONTA");
                $banco = $con->getField("NOMEINTERNO", "CONTA=" . $fin[$i]['CONTA']);
                $con->close_connection();
                $fin[$i]['CONTA_NOME'] = $banco;  // Nome da conta para exibição
                // $fin[$i]['CONTA'] mantém o ID para o selected do combo

                $con = new c_banco();
                $con->setTab("AMB_DDM");
                $tipoDocto = $con->getField("PADRAO", "TIPO='" . $fin[$i]['TIPODOCTO'] . "' AND (alias='FIN_MENU') and (campo='TipoDoctoPgto')");
                $con->close_connection();
                $fin[$i]['TIPODOCTO_NOME'] = $tipoDocto;  // Nome do tipo para exibição
                // $fin[$i]['TIPODOCTO'] mantém o código para o selected do combo

                $con = new c_banco();
                $con->setTab("AMB_DDM");
                $sitPgto = $con->getField("PADRAO", "TIPO='" . $fin[$i]['SITPGTO'] . "' AND (alias='FIN_MENU') and (campo='SituacaoPgto')");
                $con->close_connection();
                $fin[$i]['SITPGTO_NOME'] = $sitPgto;  // Nome da situação para exibição
                // $fin[$i]['SITPGTO'] mantém o código para o selected do combo
            }
            if ($mensagem == null) {
                $mensagem .= "Pedido com parcelas já cadastradas no financeiro!";
            }
            $this->smarty->assign('parcelasCadastrada', true);
        }
        if (!is_array($fin)) {
            $mensagem .= "Pedido sem parcelas de Produto!";
            $this->smarty->assign('parcelasCadastrada', true);
        }
        $this->smarty->assign('mensagem', $mensagem ?? '');
        $this->smarty->assign('fin', $fin);
        if ($this->formNf == true)
            $this->smarty->assign('formNf', true);
        else
            $this->smarty->assign('formNf', false);

        // Parametro usado para historico do form gerente pedido
        $this->smarty->assign('data_history', $this->data_history);

        $this->smarty->assign('mod', 'ped');
        $this->smarty->assign('form', 'pedido_venda_nf_pecas_novo');
        if ($this->m_opcao === '' || $this->m_opcao === null) {
            $this->smarty->assign('opcao', 'pedido_venda_gerente_novo');
        }

        $this->smarty->display('pedido_venda_nf_cadastro_pecas_novo.tpl');
    }

    //fim desenhaCadgrupo
    //---------------------------------------------------------------
    //---------------------------------------------------------------

    function desenhaCadastroFinanceiroNotafiscal($mensagem = NULL, $tipoMsg = NULL)
    {

        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $descCondPgto = "";
        $parcelas = 0;
        $valorParcelas = 0;
        $totalParcelas = 0;
        $numParcelas = 0;
        $fin = [];

        $objNotaFiscal = $this->select_nota_fiscal($this->parmPost['id']);
        //Set de ID para consultas
        $this->setPedido($objNotaFiscal[0]['ID']);

        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('alteraCondPgto', $this->alteraCondPgto);

        $this->smarty->assign('id', $objNotaFiscal[0]['ID']);
        $this->smarty->assign('pedido', $objNotaFiscal[0]['ID']);
        $this->smarty->assign('cliente', $objNotaFiscal[0]['PESSOA']);
        //set data para formatar
        $this->setEmissao($objNotaFiscal[0]['EMISSAO']);
        $this->smarty->assign('data', $this->getEmissao('F'));
        $this->smarty->assign('total', $objNotaFiscal[0]['TOTALNF']);
        $this->smarty->assign('natOperacao', $objNotaFiscal[0]['IDNATOP']);
        $this->smarty->assign('serie', $objNotaFiscal[0]['SERIE']);
        $this->smarty->assign('clienteNome', $objNotaFiscal[0]['NOME']);
        $this->smarty->assign('pessoa', $this->transportador);

        // Atribuições das variáveis do transportador
        $this->smarty->assign('modFrete', $objNotaFiscal[0]['MODFRETE']);
        $this->smarty->assign('placaVeiculo', $objNotaFiscal[0]['PLACAVEICULO']);
        $this->smarty->assign('codAntt', $objNotaFiscal[0]['CODANTT']);
        $this->smarty->assign('uf', $objNotaFiscal[0]['UF']);
        $this->smarty->assign('volume', $objNotaFiscal[0]['VOLUME']);
        $this->smarty->assign('volEspecie', $objNotaFiscal[0]['VOLESPECIE']);
        $this->smarty->assign('volMarca', $objNotaFiscal[0]['VOLMARCA']);
        $this->smarty->assign('volPesoLiq', $objNotaFiscal[0]['VOLPESOLIQ']);
        $this->smarty->assign('volPesoBruto', $objNotaFiscal[0]['VOLPESOBRUTO']);
        $this->smarty->assign('dataSaidaEntrada', $objNotaFiscal[0]['DATASAIDAENTRADA']);
        $this->smarty->assign('obs', $objNotaFiscal[0]['OBS']);

        $this->smarty->assign('t_origem', $this->t_origem);

        // ########## NATUREZA OPERACAO ##########
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where (tipo='S') order by id";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $natOperacao_ids[$i] = $result[$i]['ID'];
            $natOperacao_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natOperacao_ids', $natOperacao_ids);
        $this->smarty->assign('natOperacao_names', $natOperacao_names);
        $this->smarty->assign('natOperacao_id', $this->getIdNatop());

        // COMBOBOX CONDICAO PAGAMENTO
        $situacaoLcto = 'A';
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            if ($this->getCondPg() == $result[$i]['ID']) :
                $descCondPgto = $result[$i]['DESCRICAO'];
                $situacaoLcto = $result[$i]['SITUACAOLCTO'];
            endif;
            $condPgto_ids[$i] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('descCondPgto', "$descCondPgto");
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPg());
        $this->smarty->assign('financeiroCondExtrato', ($situacaoLcto === 'E'));

        $objParametro = new c_parametro();
        $generoEntrada = $objParametro->getGeneroEntradaFinanceiro($this->m_empresacentrocusto);
        $generoPadrao = !empty($objNotaFiscal[0]['GENERO']) ? $objNotaFiscal[0]['GENERO'] : $generoEntrada;
        if (isset($this->parmPost['genero']) && $this->parmPost['genero'] !== '') {
            $this->setGenero($this->parmPost['genero']);
        } elseif (!empty($generoPadrao)) {
            $this->setGenero($generoPadrao);
        }

        // COMBOBOX GENERO
        $consulta = new c_banco();
        $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero WHERE tipolancamento = 'P' ORDER BY descricao;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $genero_ids[$i] = $result[$i]['ID'];
            $genero_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
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
        for ($i = 0; $i < count($result); $i++) {
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
        for ($i = 0; $i < count($result); $i++) {
            $situacaoLanc_ids[$i] = $result[$i]['ID'];
            $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
        $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
        $this->smarty->assign('situacaoLanc_id', $situacaoLcto);

        // modalidade frete
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='modFrete')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
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
            $centroCusto_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto());

        $consultaFin = new c_banco();
        $consultaFin->setTab("FIN_LANCAMENTO");
        $numPedFin = $consultaFin->getField("NUMLCTO", "NUMLCTO = '" . $this->getPedido() . "' AND ORIGEM ='PED' AND SITPGTO<>'C'");
        $consultaFin->close_connection();
        if (empty($numPedFin)) {
            // CALCULA PARCELAS
            $fin = $this->calculaParcelasNfe($descCondPgto, $objNotaFiscal[0]['TOTALNF']);
            $this->smarty->assign('parcelasCadastrada', false);
        } else {
            $lancObj = new c_lancamento();
            $fin = $lancObj->select_lancamento_doc('PED', $this->getPedido());
            for ($i = 0; $i < count($fin); $i++) {
                // Mantém o ID original e cria campo separado para o nome
                $con = new c_banco();
                $con->setTab("FIN_CONTA");
                $banco = $con->getField("NOMEINTERNO", "CONTA=" . $fin[$i]['CONTA']);
                $con->close_connection();
                $fin[$i]['CONTA_NOME'] = $banco;  // Nome da conta para exibição
                // $fin[$i]['CONTA'] mantém o ID para o selected do combo

                $con = new c_banco();
                $con->setTab("AMB_DDM");
                $tipoDocto = $con->getField("PADRAO", "TIPO='" . $fin[$i]['TIPODOCTO'] . "' AND (alias='FIN_MENU') and (campo='TipoDoctoPgto')");
                $con->close_connection();
                $fin[$i]['TIPODOCTO_NOME'] = $tipoDocto;  // Nome do tipo para exibição
                // $fin[$i]['TIPODOCTO'] mantém o código para o selected do combo

                $con = new c_banco();
                $con->setTab("AMB_DDM");
                $sitPgto = $con->getField("PADRAO", "TIPO='" . $fin[$i]['SITPGTO'] . "' AND (alias='FIN_MENU') and (campo='SituacaoPgto')");
                $con->close_connection();
                $fin[$i]['SITPGTO_NOME'] = $sitPgto;  // Nome da situação para exibição
                // $fin[$i]['SITPGTO'] mantém o código para o selected do combo
            }
            if ($mensagem == null) {
                $mensagem .= "Pedido com parcelas já cadastradas no financeiro!";
            }
            $this->smarty->assign('parcelasCadastrada', true);
        }
        if (!is_array($fin)) {
            $mensagem .= "Pedido sem parcelas de Produto!";
            $this->smarty->assign('parcelasCadastrada', true);
        }
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('fin', $fin);


        $fin = $this->calculaParcelasNfe($descCondPgto, $this->getTotal());


        $this->smarty->display('pedido_venda_nf_cadastro_pecas_novo.tpl');
    }

    //fim desenhaCadgrupo
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraPedido($mensagem)
    {

        $lanc = $this->select_pedidoVenda_letra('||||3|');

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_nf_mostra.tpl');
    }

    /**
     * Monta botões de atalho conforme o tipo de erro (cliente, produto, tributação etc.)
     *
     * @param string $mensagemErro
     * @param array  $arrPedido
     * @return string HTML com botões (pode ser string vazia)
     */
    private function montaBotoesAjudaErroEspelho($mensagemErro, array $arrPedido)
    {
        $html = '';
        $tipoErro = null;
        if (preg_match('/TipoErro=(\d)/', $mensagemErro, $mTipo)) {
            $tipoErro = (int) $mTipo[1];
        }

        if ($tipoErro === 1 ) {
            if (!empty($this->getCliente())) {
                $urlCliente = ADMhttpCliente . '/index.php?mod=crm&submenu=alterar&form=contas&param=' . $this->getCliente();
                $html .= '<br><button type="button" style="font-size:12px !important;" class="btn btn-primary" '
                    . 'onclick="openNewWin(\'' . $urlCliente . '\')">'
                    . 'Abrir cadastro do cliente em nova guia'
                    . '</button>';
            }
            return $html;
        }
        if ($tipoErro === 2 ) {
            if (!empty($this->getIdNatop())) {
                $urlNatOp = ADMhttpCliente . '/index.php?mod=est&form=nat_tributos&submenu=cadastrar&idNatop=' . $this->getIdNatop();
                $html .= '<br><button type="button" style="font-size:12px !important;" class="btn btn-primary" '
                    . 'onclick="openNewWin(\'' . $urlNatOp . '\')">'
                    . 'Abrir Natureza de Operação '
                    . '</button>';
            }
            return $html;
        }
        if ($tipoErro === 3) {
            $urlProduto = ADMhttpCliente . '/index.php?mod=est&form=produto&opcao=pesquisarpecas&from=pedido_ps';
            $html .= '<br><button type="button" style="font-size:12px !important;" class="btn btn-primary" '
                . 'onclick="openNewWin(\'' . $urlProduto . '\')">'
                . 'Abrir pesquisa de produtos em nova guia'
                . '</button>';
            return $html;
        }

        return $html;
    }

    /**
     * Natureza de operação, série e demais defaults do EST_PARAMETRO modelo 65 (NFC-e).
     *
     * @throws Exception
     */
    private function aplicaParametrosCupomFiscal(): void
    {
        $objCupom = new c_cupom();
        $param = $objCupom->getParametroNfce((int) $this->m_empresacentrocusto);
        if (!$param) {
            throw new Exception('Parâmetros NFC-e (modelo 65) não configurados para esta filial.');
        }
        if (trim((string) ($param['SERIE'] ?? '')) === '') {
            throw new Exception('Série NFC-e não configurada em EST_PARAMETRO (modelo 65).');
        }
        if (trim((string) ($param['NATOPERACAO'] ?? '')) === '') {
            throw new Exception('Natureza de operação NFC-e não configurada em EST_PARAMETRO (modelo 65).');
        }

        $this->setSerie((string) $param['SERIE']);
        $this->setIdNatop((string) $param['NATOPERACAO']);
        if ($this->getCondPg() === '' || $this->getCondPg() === null || (int) $this->getCondPg() === 0) {
            $this->setCondPg($param['CONDPGTO']);
        }
        if (!isset($this->parmPost['genero']) || $this->parmPost['genero'] === '') {
            if (!empty($param['GENERO'])) {
                $this->setGenero((string) $param['GENERO']);
            }
        }
        if ($this->getContaDeposito() === '' || $this->getContaDeposito() === null) {
            $this->setContaDeposito((string) $param['CONTA']);
        }
    }
    //fim mostragrupos
    //-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
//$pedido = new p_pedido_venda_nf($id=null);

//$pedido->controle();
