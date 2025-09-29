<?php

/**
 * @package   astec
 * @name      p_nota_fiscal
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
require_once($dir . "/../../forms/est/p_nfephp_40.php");
require_once($dir . "/../../forms/est/p_nfephp_imprime_danfe.php");
require_once($dir . "/../../forms/est/p_relatorio_geral_notas.php");
require_once($dir . "/../../forms/est/p_espelho_nfe.php");
require_once($dir . "/../../forms/est/p_nota_fiscal_xml.php");

require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/crm/c_conta.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../class/ped/c_parametro.php");
include_once($dir . "/../../bib/c_date.php");

//Class P_Nota_Fiscal
class p_nota_fiscal extends c_nota_fiscal
{

    private $m_submenu       = NULL;
    private $m_letra         = NULL;
    private $m_opcao         = NULL;
    private $m_msg           = NULL;
    private $m_justificativa = NULL;
    private $m_inutModelo    = NULL;
    private $m_inutSerie     = NULL;
    private $m_inutNumIni    = NULL;
    private $m_inutNumFim    = NULL;
    private $m_inutJust      = NULL;
    private $m_cartaC        = NULL;
    private $m_notas_xml     = NULL;
    private $m_email         = NULL;
    private $filialid        = NULL;
    private $m_from          = NULL;
    private $m_email_para    = NULL;
    private $m_email_cc      = NULL;
    private $m_email_assunto = NULL;
    private $m_email_body    = NULL;
    public $smarty           = NULL;


    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function __construct()
    {
        // @set_exception_handler(array($this, 'exception_handler'));

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

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
        // $this->m_submenu = $this->parmPost['submenu'] ? $this->parmPost['submenu'] : (isset($this->parmGet['submenu']) ? $this->parmGet['submenu']);
        if ($this->parmPost['submenu'] !== '' && $this->parmPost['submenu'] !== null) {
            $this->m_submenu = $this->parmPost['submenu'];
        } elseif ($this->parmGet['submenu'] !== '' && $this->parmGet['submenu'] !== null) {
            $this->m_submenu = $this->parmGet['submenu'];
        } else {
            $this->m_submenu = '';
        }

        $this->m_opcao = $this->parmPost['opcao'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);
        $this->m_notas_xml = $this->parmPost['notas_xml'];
        $this->m_email = $this->parmPost['email'];
        $this->nfProdDevolucao = $this->parmPost['nfProdutos'];
        $this->telaOrigem = $this->parmPost['telaOrigem'];

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        // metodo SET dos dados do FORM para o TABLE
        //$this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : "");
        if ($this->parmPost['id'] !== '' && $this->parmPost['id'] !== null) {
            $this->setId($this->parmPost['id']);
        } elseif ($this->parmGet['id'] !== '' && $this->parmGet['id'] !== null) {
            $this->setId($this->parmGet['id']);
        } else {
            $this->setId('');
        }
        $this->setModelo(isset($this->parmPost['modelo']) ? $this->parmPost['modelo'] : "55");
        $this->setSerie(isset($this->parmPost['serie']) ? $this->parmPost['serie'] : "");
        $this->setNumero(isset($this->parmPost['numero']) ? $this->parmPost['numero'] : "0");
        $this->setPessoa(isset($this->parmPost['pessoa']) ? $this->parmPost['pessoa'] : "");
        $this->setEmissao(isset($this->parmPost['emissao']) ? $this->parmPost['emissao'] : date("Y/m/d H:i"));
        $this->setIdNatop(isset($this->parmPost['idNatOp']) ? $this->parmPost['idNatOp'] : "");
        $this->setTipo(isset($this->parmPost['tipo']) ? $this->parmPost['tipo'] : "");
        $this->setContrato(isset($this->parmPost['contrato']) ? $this->parmPost['contrato'] : "");
        $this->setSituacao(isset($this->parmPost['situacao']) ? $this->parmPost['situacao'] : "A");
        $this->setFormaPgto(isset($this->parmPost['formaPgto']) ? $this->parmPost['formaPgto'] : "");
        $this->setCondPgto(isset($this->parmPost['condPgto']) ? $this->parmPost['condPgto'] : "");
        $this->setDataSaidaEntrada(isset($this->parmPost['dataSaidaEntrada']) ? $this->parmPost['dataSaidaEntrada'] : date("Y/m/d H:i"));
        $this->setFormaEmissao(isset($this->parmPost['formaEmissao']) ? $this->parmPost['formaEmissao'] : "");
        $this->setFinalidadeEmissao(isset($this->parmPost['finalidadeEmissao']) ? $this->parmPost['finalidadeEmissao'] : "");
        $this->setNfeReferenciada(isset($this->parmPost['nfeReferenciada']) ? $this->parmPost['nfeReferenciada'] : "");
        $this->setCentroCusto(isset($this->parmPost['centroCusto']) ? $this->parmPost['centroCusto'] : "");
        $this->setGenero(isset($this->parmPost['genero']) ? $this->parmPost['genero'] : "");
        $this->setTotalnf(isset($this->parmPost['totalnf']) ? $this->parmPost['totalnf'] : "");
        $this->setObs(isset($this->parmPost['obs']) ? $this->parmPost['obs'] : "");
        $this->setModFrete(isset($this->parmPost['modFrete']) ? $this->parmPost['modFrete'] : "");
        $this->setTransportador(isset($this->parmPost['transportador']) ? $this->parmPost['transportador'] : "0");
        $this->setPlacaVeiculo(isset($this->parmPost['placaVeiculo']) ? $this->parmPost['placaVeiculo'] : "");
        $this->setCodAntt(isset($this->parmPost['codAntt']) ? $this->parmPost['codAntt'] : "");
        $this->setUf(isset($this->parmPost['uf']) ? $this->parmPost['uf'] : "");
        $this->setVolume(isset($this->parmPost['volume']) ? $this->parmPost['volume'] : "1");
        $this->setVolEspecie(isset($this->parmPost['volEspecie']) ? $this->parmPost['volEspecie'] : "");
        $this->setVolMarca(isset($this->parmPost['volMarca']) ? $this->parmPost['volMarca'] : "");
        $this->setVolPesoLiq(isset($this->parmPost['volPesoLiq']) ? $this->parmPost['volPesoLiq'] : "");
        $this->setVolPesoBruto(isset($this->parmPost['volPesoBruto']) ? $this->parmPost['volPesoBruto'] : "");
        $this->setFrete(isset($this->parmPost['frete']) ? $this->parmPost['frete'] : "0,00");
        $this->setDespAcessorias(isset($this->parmPost['despacessorias']) ? $this->parmPost['despacessorias'] : "0,00");
        $this->setSeguro(isset($this->parmPost['seguro']) ? $this->parmPost['seguro'] : "0,00");
        $this->setNProt(isset($this->parmPost['nProt']) ? $this->parmPost['nProt'] : "");
        $this->setDhRecbto(isset($this->parmPost['dhRecbto']) ? $this->parmPost['dhRecbto'] : "");
        $this->setDigVal(isset($this->parmPost['digVal']) ? $this->parmPost['digVal'] : "");
        $this->setVerAplic(isset($this->parmPost['verAplic']) ? $this->parmPost['verAplic'] : "");
        $this->setOrigem(isset($this->parmPost['origem']) ? $this->parmPost['origem'] : "");
        $this->setDoc(isset($this->parmPost['doc']) ? $this->parmPost['doc'] : "");
        $this->m_justificativa = isset($this->parmPost['justificativa']) ? $this->parmPost['justificativa'] : "";
        $this->m_inutModelo = isset($this->parmPost['inutModelo']) ? $this->parmPost['inutModelo'] : "";
        $this->m_inutSerie = isset($this->parmPost['inutSerie']) ? $this->parmPost['inutSerie'] : "";
        $this->m_inutNumIni = isset($this->parmPost['inutNumIni']) ? $this->parmPost['inutNumIni'] : "";
        $this->m_inutNumFim = isset($this->parmPost['inutNumFim']) ? $this->parmPost['inutNumFim'] : "";
        $this->m_inutJust = isset($this->parmPost['inutJustificativa']) ? $this->parmPost['inutJustificativa'] : "";
        $this->m_cartaC = isset($this->parmPost['carta']) ? $this->parmPost['carta'] : "";
        $this->devolucaoNotaFiscal = isset($this->parmPost['devolucaoNotaFiscal']) ? $this->parmPost['devolucaoNotaFiscal'] : "";
        // atualizacoa para 8.3
        $this->m_condicao = $this->parmGet["condicao"] ?? $this->parmPost["condicao"] ?? "";
        //$this->m_condicao = (isset($this->parmGet['condicao']) ? $this->parmGet['condicao'] : $this->parmPost['condicao'] ? $this->parmPost['condicao'] : '');

        //condicao que verifica a tela origem para ajustar tela
        if (isset($this->parmPost['from']) && $this->parmPost['from'] !== '') {
            $this->m_from = $this->parmPost['from'];
        } elseif (isset($this->parmGet['from']) && $this->parmGet['from'] !== '') {
            $this->m_from = $this->parmGet['from'];
        } else {
            $this->m_from = '';
        } //FIM condicao


        $this->m_email_para = isset($this->parmPost['destinatario']) ? $this->parmPost['destinatario'] : "";
        $this->m_email_cc = isset($this->parmPost['comCopiaPara']) ? $this->parmPost['comCopiaPara'] : "";
        $this->m_email_assunto = isset($this->parmPost['assunto']) ? $this->parmPost['assunto'] : "";
        $this->m_email_body = isset($this->parmPost['emailCorpo']) ? $this->parmPost['emailCorpo'] : "";

        // dados para exportacao e relatorios

        if ($this->m_submenu == 'devolucaoNotaFiscal') {
            $this->smarty->assign('titulo', "Devolução Nota Fiscal");
            $this->smarty->assign('colVis', "[0,1,2,3,4,5,6,7]");
            $this->smarty->assign('disableSort', "[0]");
            $this->smarty->assign('numLine', "25");
        } else {
            $this->smarty->assign('titulo', "Nota Fiscal");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8,9,10, 11 ]");
            $this->smarty->assign('disableSort', "[ 11 ]");
            $this->smarty->assign('numLine', "25");
        }


        // include do javascript
        //        include ADMjs . "/est/s_nota_fiscal.js";
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
         switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'I')) {
                    $parametros = new c_banco;
                    $sql = "SELECT * FROM EST_PARAMETRO ";
                    $sql .= "WHERE (FILIAL = " . $this->m_empresacentrocusto . ") AND (MODELO=55)";
                    $banco = new c_banco;
                    $res_parametro = $banco->exec_sql($sql);
                    //$parametros->close_connection();
                    if (!is_array($res_parametro)) {
                        $result = false;
                        throw new Exception("Parametros não cadastrado!! - FILIAL = " . $this->m_empresacentrocusto);
                    }
                    $this->setNatOperacao($res_parametro[0]['NATOPERACAO']);
                    $this->setGenero($res_parametro[0]['GENERO']);
                    $this->setSerie($res_parametro[0]['SERIE']);
                    //$parametros->close_connection();    
                    $this->setTipo('1'); // padrão saída

                    $parametros = new c_banco;
                    $parametros->setTab("EST_PARAMETRO");
                    $cCusto = $parametros->getField("CENTROCUSTO", "FILIAL=" . $this->m_empresacentrocusto);
                    //$parametros->close_connection();

                    $this->setCentroCusto($cCusto);

                    $this->desenhaCadastroNotaFiscal();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'A')) {
                    $this->setNotaFiscal();
                    //CONDIÇÃO PARA CALCULO DE RATEIOS QUANDO VOLTA PARA A NOTA FISCAL (s_nota_fiscal_produto->submitVoltarNfMostra())
                    if ($this->m_condicao == 'calcRateios') {
                        $this->calculaRateios();
                    };
                    $this->desenhaCadastroNotaFiscal();
                }
                break;
            case 'alterarAjax':
                $this->desenhaCadastroNotaFiscal();
                break;

            case 'inclui':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'I')) {
                    // ****** Gerar numero da notafiscal!! *********
                    //$numNf = $this->geraNumNf($this->getModelo(), $this->getSerie());
                    $numNf = 0;
                    $this->setOrigem('NFE');
                    $this->setDoc($numNf);
                    //$this->setNumero($numNf);
                    /*$this->m_msg = "Numero NF >>>".$numNf;
                    if (intval($numNf)==0):
                        $result = false;
                        throw new Exception( $this->m_msg );
                    endif;
                    if ($this->existeNotaFiscal()) {
                        $this->m_submenu = "cadastrar";
                        $this->desenhaCadastroNotaFiscal($this->m_msg." Nota Fiscal já existe, altere o numero da NF ou Cliente",'alerta');
                    } else {*/
                    $this->getNatOperacao(); //====
                    $this->m_msg = $this->incluiNotaFiscal();
                    if (is_numeric($this->m_msg))
                        $this->mostraNotaFiscal(' Nota fiscal ' . $this->getId() . ' Cadastrada.', 'sucesso');
                    else
                        $this->mostraNotaFiscal($this->m_msg, '');
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'A')) {
                    try {
                        $msg = '';
                        $tipoMsg = 'sucesso';
                        $statusNf = '';
                        if ($this->existeNotaFiscalBaixa($this->getId())) { // verificar si a nota esta baixada
                            $statusNf = 'Nota fiscal baixada, n&atilde;o sendo poss&iacute;vel alterar!';
                        } else {
                            $result = $this->alteraNotaFiscal();
                            $this->calculaRateios($this->getId());
                            $msg = 'Nota fiscal alterada com sucesso!';
                            if (!$result):
                                throw new Exception("Nota " . $this->getNumero() . " não cadastrado ");
                            endif;
                        }
                    } catch (Exception $e) {
                        $tipoMsg = 'alerta';
                        $msg = $e->getMessage();
                    } finally {
                        if (($msg !== '') and ($statusNf == '')) {
                            $this->mostraNotaFiscal($msg, 'sucesso');
                        } elseif (($msg == '') and ($statusNf == '')) {
                            $this->mostraNotaFiscal();
                        } elseif (($msg == '') and ($statusNf !== '')) {
                            $this->mostraNotaFiscal($statusNf, 'alerta');
                        } else {
                            $this->desenhaCadastroNotaFiscal($msg, $tipoMsg);
                        }
                    }
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'E')) {
                    if ($this->existeNotaFiscalBaixa($this->getId())) { // verificar si a nota esta baixada
                        $this->mostraNotaFiscal('Nota fiscal foi recebida, n&atilde;o sendo possivel excluir.', 'alerta');
                    } else {
                        if ($this->existeNotaFiscalProduto($this->getId())) {
                            $this->mostraNotaFiscal('Existem Produtos na NF, Exclua os produtos antes de deletar a NF.', 'alerta');
                        } else {
                            $this->setNotaFiscal();
                            if ((c_lancamento::verificaDocBaixado($this->getPessoa(), $this->getNumero(), 'NFS'))) {
                                $this->mostraNotaFiscal('Existem financeiros baixados para esta NF, Altere para aberto antes de deletar a NF.', 'alerta');
                            } else {
                                $this->excluiNotafiscal();
                                $this->mostraNotaFiscal('Nota fiscal exclu&iacute;da.', 'sucesso');
                            }
                        }
                    }
                }
                break;
            case 'geraXML':
                if (!$this->existeNotaFiscalBaixa($this->getId())) {
                    // Gera e altera numero NF
                    $this->setNotaFiscal();
                    if ($this->getNumero() == 0):
                        $numNf = $this->geraNumNf($this->getModelo(), $this->getSerie(), $this->getCentroCusto());
                        if (intval($numNf) == 0):
                            $this->m_msg = "Idendificador NF >>> " . $idGerado . " - Número não Gerado";
                            $result = false;
                            throw new Exception($this->m_msg);
                        endif;
                        $this->setNumero($numNf);
                        $this->alteraNfNumero();
                    endif;

                    // valida e autoriza nf
                    $exporta = new p_nfe_40();
                    $result = $exporta->Gera_XML($this->getId(), $this->m_empresacentrocusto, $this->getTipo());

                    //$msg='';
                    //$this->desenhaPrintDanfe($msg, null, $result);

                    if ($result['cStatus'] == '100') {
                        //OLD
                        //$this->alteraNfNumero(null, null, $result['cStatus']);
                        //$printDanfe = new p_nfephp_imprime_danfe();
                        //$printDanfe->printDanfe($this->getId(), '', '', '', '', 'nota_fiscal');

                        //NEW
                        echo "<script>";
                        echo "function printDanfe(id) {
                                    window.open('index.php?mod=est&origem=imprimeDanfe&opcao=imprimir&form=nfephp_imprime_danfe&id='+id, 'DANFE', 'toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
                            }";
                        echo "printDanfe(" . $this->getId() . ");";
                        echo "</script>";
                        $this->mostraNotaFiscal('');
                    } elseif (isset($result['erroInterno'])) {

                        $this->mostraNotaFiscal($result['motivo'], 'alerta');
                    } else {

                        $this->alteraNfNumero(null, $result['recibo'], $result['codSituacao']);

                        // print log error
                        if ($result['msg_log'] == null) {
                            echo "<script>console.log('" . $result['msg_log'] . "' );</script>";
                        }

                        $this->mostraNotaFiscal($result['motivo'], 'alerta');

                        // ATUALIZACAO ANTERIOR DA NOTA PARA IMPLEMENTAR MSG DE ERRO - 17/DEZEMBRO/2024
                        //$this->mostraNotaFiscal($result['msgCompleta']."</br></br>".$result['button'], 'alerta');
                        //$objNotaXml = new p_nota_fiscal_xml($result['idNotaFiscal'],'carregar');
                        //$objNotaXml->controle();
                    }
                } else {
                    $this->mostraNotaFiscal('Nota fiscal baixada, não sendo poss&iacute;vel emitir!', 'alerta');
                }
                break;
            case 'emailDANFE':
                //  $email_par = explode("|", $this->m_email);
                $this->setNotaFiscal();
                $danfe = new p_nfe_40();
                $conta = new c_conta;
                $conta->setId($this->getPessoa());
                $arrConta = $conta->select_conta();

                // busca emitente
                $emitente = new c_banco;
                $emitente->setTab('AMB_EMPRESA');
                $arrEmitente = $emitente->getRecord('centrocusto= ' . $this->getCentroCusto());
                $emitente->close_connection();
                $msg = $danfe->enviaEmailDANFE(
                    $this->getModelo(),
                    $this->m_email_para,
                    $this->m_email_cc,
                    $this->getChNfe(),
                    $this->getEmissao(),
                    $this->getNumero(),
                    $this->getSerie(),
                    $arrEmitente[0]['NOMEEMPRESA'],
                    $this->m_email_assunto,
                    $this->m_email_body
                );
                $tipoMsg = 'sucesso';
                if ((strstr($msg, 'NÃO'))) {
                    $tipoMsg = 'alerta';
                }
                $this->m_submenu = 'alterar';
                $this->desenhaCadastroNotaFiscal($msg, $tipoMsg);
                //$this->mostraNotaFiscal($msg,'sucesso');
                break;
            case 'geraDANFE':
                $this->setNotaFiscal();
                $danfe = new p_nfe_40();
                $msg = $danfe->gera_DANFE($this->getChNfe());
                if ($msg = 'Danfe NÃO gerada NFe número - ') {
                    $this->mostraNotaFiscal($msg . $this->getNumero(), 'error');
                } else {
                    $this->mostraNotaFiscal($msg . $this->getNumero(), 'sucesso');
                }

                break;
            case 'cancelaNFE':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'S')) {
                    $this->setNotaFiscal();
                    if ($this->getSituacao() == 'B') {
                        if (!(c_lancamento::verificaDocBaixado($this->getPessoa(), $this->getNumero(), 'NFS'))) {
                            $danfe = new p_nfe_40();
                            $msg = $danfe->cancela_NFE($this->getChNfe(), $this->getNProt(), $this->m_justificativa, $this->getModelo());
                            $cStat = $msg->retEvento->infEvento->cStat;
                            if (($msg->cStat == '128') and (($cStat == '101' || $cStat == '135' || $cStat == '155'))) {
                                $this->alteraSituacao('C');
                                c_lancamento::alteraSituacaoFinanceiro($this->getPessoa(), $this->getNumero(), 'NFS', 'C');
                                $this->incluiNfEvento($msg, 'C', '1', $this->getNumero(), $this->getNumero(), $this->m_justificativa);
                                // altera situação pedido
                                $origem = $this->getOrigem();
                                if ($this->getOrigem() == 'PED') {
                                    $objPedido = new c_pedidoVenda();
                                    $objPedido->setId($this->getDoc());
                                    $objPedido->setSituacao(3);
                                    $objPedido->setEmissao(date("d/m/Y"));
                                    $objPedido->alteraPedidoSituacao();
                                }

                                // estorna produto
                                $objNfProduto = new c_nota_fiscal_produto();
                                $objNfProduto->setIdNf($this->getId());
                                $arrNfProduto = $objNfProduto->select_nota_fiscal_produto_nf();
                                for ($i = 0; $i < count($arrNfProduto); $i++) {
                                    c_produto_estoque::produtoBaixaEstorna($this->m_empresacentrocusto, 'NFS',  $this->getId(), $arrNfProduto[$i]['CODPRODUTO'], $arrNfProduto[$i]['QUANT']);
                                } //for                             

                                $this->estornaNf();

                                $this->mostraNotaFiscal('Cancelamento Nfe: ' . $this->getNumero() . ' - Realizado com sucesso', 'sucesso');
                            } else {
                                $this->mostraNotaFiscal($msg->retEvento->infEvento->cStat . ' - ' . $msg->retEvento->infEvento->xMotivo . ' NFe:' . $this->getNumero(), 'erro');
                            }
                        } else {
                            $this->mostraNotaFiscal('Documento financeiro já baixo, <br>realize o estorno antes do CANCELAMENTO da NFe: ' . $this->getNumero(), 'erro');
                        }
                    } else {
                        $this->mostraNotaFiscal('Nota Fiscal Eletrônica não autorizada, <br>impossibilitando o CANCELAMENTO da NFe: ' . $this->getNumero(), 'erro');
                    }
                }
                break;
            case 'cartaCNFEImprimir':
                $this->setNotaFiscal();
                $chave = $this->getChNfe();
                $id = $this->getId();
                $NProt = $this->getNProt();
                $evento = $this->selectNfEvento($id);
                $emitente = new c_banco;

                $emitente->setTab('AMB_EMPRESA');
                $arrEmitente = $emitente->getRecord('centrocusto= ' . $this->getCentroCusto());
                $emitente->close_connection();

                // visualizar 
                $arq = '';
                $aEnd = array(
                    'razao' => $arrEmitente[0]['NOMEEMPRESA'],
                    'logradouro' => $arrEmitente[0]['TIPOEND'] . ' ' . $arrEmitente[0]['ENDERECO'],
                    'numero' => $arrEmitente[0]['NUMERO'],
                    'complemento' => $arrEmitente[0]['COMPLEMENTO'],
                    'bairro' => $arrEmitente[0]['BAIRRO'],
                    'CEP' => $arrEmitente[0]['CEP'],
                    'municipio' => $arrEmitente[0]['CIDADE'],
                    'UF' => $arrEmitente[0]['UF'],
                    'telefone' => '(' . $arrEmitente[0]['FONEAREA'] . ') ' . $arrEmitente[0]['FONENUM'],
                    'email' => $arrEmitente[0]['EMAIL']
                );
                $danfe = new p_nfe_40();

                $danfe->visualizar_carta_correcao_NFE($chave, $NProt, 1, date('Ym'), $aEnd, $arq);
                $this->mostraNotaFiscal('Carta Correção Nfe: ' . $this->getNumero() . ' realizado com sucesso', 'sucesso', $arq);
                break;
            case 'cartaCNFE':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'S')) {
                    $this->setNotaFiscal();
                    if ($this->getSituacao() == 'B') {
                        $seqEvento = $this->geraNumEvento($this->getModelo(), $this->getSerie(), $this->getNumero(), $this->getCentroCusto(), "T");
                        $danfe = new p_nfe_40();
                        $msg = $danfe->carta_correcao_NFE($this->getChNfe(), $this->getNProt(), $this->m_cartaC, $this->getModelo(), $seqEvento);
                        //if (($msg['cStat'] == '128') and ($msg['evento'][0]['cStat'] == '135')):
                        $this->incluiNfEvento($msg, 'T', $seqEvento, $this->getNumero(), $this->getNumero(), $this->m_cartaC);
                        // busca emitente
                        $emitente = new c_banco;
                        $emitente->setTab('AMB_EMPRESA');
                        $arrEmitente = $emitente->getRecord('centrocusto= ' . $this->getCentroCusto());
                        $emitente->close_connection();

                        // visualizar 
                        $arq = '';
                        $aEnd = array(
                            'razao' => $arrEmitente[0]['NOMEEMPRESA'],
                            'logradouro' => $arrEmitente[0]['TIPOEND'] . ' ' . $arrEmitente[0]['ENDERECO'],
                            'numero' => $arrEmitente[0]['NUMERO'],
                            'complemento' => $arrEmitente[0]['COMPLEMENTO'],
                            'bairro' => $arrEmitente[0]['BAIRRO'],
                            'CEP' => $arrEmitente[0]['CEP'],
                            'municipio' => $arrEmitente[0]['CIDADE'],
                            'UF' => $arrEmitente[0]['UF'],
                            'telefone' => '(' . $arrEmitente[0]['FONEAREA'] . ') ' . $arrEmitente[0]['FONENUM'],
                            'email' => $arrEmitente[0]['EMAIL']
                        );

                        $danfe->visualizar_carta_correcao_NFE($this->getChNfe(), $this->getNProt(), $seqEvento, date('Ym'), $aEnd, $arq);
                        $this->mostraNotaFiscal('Carta Correção Nfe: ' . $this->getNumero() . ' realizado com sucesso', 'sucesso', $arq);
                    }
                } else {
                    $this->mostraNotaFiscal('Nota Fiscal Eletrônica não autorizada, <br>impossibilitando o CANCELAMENTO da NFe: ' . $this->getNumero(), 'erro');
                }

                break;
            case 'inutilizaNFE':
                $arrNf = $this->existeNotaFiscalEmp($this->m_inutModelo, $this->m_inutSerie, $this->m_inutNumIni, $this->m_empresacentrocusto, true);
                // if (is_array($arrNf)){
                //     $this->mostraNotaFiscal('Nota Fiscal '.$this->m_inutNumIni.' já existente na base de dados, não sendo possível INUTILIZAR!','erro');
                // }else{
                $danfe = new p_nfe_40();
                $msg = $danfe->inutiliza_NFE($this->m_inutModelo, $this->m_inutSerie, $this->m_inutNumIni, $this->m_inutNumFim, $this->m_inutJust);
                $cStat = $msg->infInut->cStat;
                $xMotivo = $msg->infInut->xMotivo;
                if ($cStat == '102'):
                    $this->salvaJustificativa($msg, $this->m_inutModelo, $this->m_inutSerie, $this->m_inutNumIni, $this->m_inutNumFim, $this->m_inutJust);
                    $this->mostraNotaFiscal($xMotivo . ' : Número Inicial: ' . $this->m_inutNumIni . ' - Número Final: ' . $this->m_inutNumFim . ', Protocolo: ' . $msg->infInut->nProt, 'sucesso');
                else:
                    $this->mostraNotaFiscal($xMotivo, 'erro');
                endif;
                // }
                break;
            case 'danfe':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'A')) {
                    $printDanfe = new p_nfephp_imprime_danfe();
                    $printDanfe->printDanfe($this->getId(), '', '', '', '', 'nota_fiscal');
                    //$this->setNotaFiscal();
                    //$this->desenhaCadastroNotaFiscal();
                }
                break;
            case 'calculoTributos':
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'A')) {
                    try {
                        $this->setNotaFiscal();
                        $objNfProduto = new c_nota_fiscal_produto();
                        $objNfProduto->setIdNf($this->getId());
                        $arrNfProduto = $objNfProduto->select_nota_fiscal_produto_nf();
                        for ($i = 0; $i < count($arrNfProduto); $i++) {
                            $objNfProduto->setId($arrNfProduto[$i]['ID']);
                            $objNfProduto->setNotaFiscalProduto();
                            $objNfProduto->setQuant($objNfProduto->getQuant(), true);
                            $objNfProduto->setUnitario($objNfProduto->getUnitario(), true);
                            $objNfProduto->setDesconto($objNfProduto->getDesconto(), true); // VERIFICAR DESCONTO
                            $objNfProduto->setTotal($objNfProduto->getTotal(), true);

                            $c_pedidoVendaNf = new c_pedidoVendaNf();

                            /*
                        $result = $c_pedidoVendaNf->calculaImpostosNfe($objNfProduto, 
                            $this->getIdNatop(), 
                            $this->getUfPessoa(), 
                            $this->getTipoPessoa(),
                            $this->getCentroCusto(),
                            $this->getPessoa()); 

                            */

                            $result = c_pedidoVendaNf::calculaImpostosNfe(
                                $objNfProduto,
                                $this->getIdNatop(),
                                $this->getUfPessoa(),
                                $this->getTipoPessoa(),
                                $this->m_empresacentrocusto
                            );


                            if (!$result):
                                $msg = "Tributos não localizado " . $objNfProduto->getDescricao() . " Nat. Operação:" . $this->getIdNatop() .
                                    " UF:" . $this->getUfPessoa() . " Tipo:" . $this->getTipoPessoa() .
                                    " CST:" . $objNfProduto->getOrigem() . $objNfProduto->getTribIcms() .
                                    " NCM:" . $objNfProduto->getNcm() . " CEST:" . $objNfProduto->getCest();
                                throw new Exception($this->m_msg);
                            endif;

                            $result = $objNfProduto->alteraNotaFiscalProduto();
                            // verificar inclusao item
                            if (is_string($result)):
                                $msg = $result;
                                $result = false;
                                throw new Exception($msg);
                            endif;
                        } //for
                        $this->desenhaCadastroNotaFiscal($msg);
                    } catch (Error $e) {
                        throw new Exception($e->getMessage() . "Alteração não realizada - Nf ");
                    } catch (Exception $e) {
                        $this->desenhaCadastroNotaFiscal($msg, "alerta");
                        break;
                    }
                }
                break;
            case 'gerarXMLsContabilidade':
                $path = BASE_DIR_NFE_AMB;
                $slash = '/';

                $email = explode("|", $this->m_email);

                //monta desc do diretorio
                $param = explode('|', $this->m_letra);
                if ($param['4'] !== '') {
                    $paramExp = explode('/', $param['4']);
                    $anomes = $paramExp[2] . $paramExp[1];
                } else {
                    $anomes = date('Ym');
                }

                $dirAprovadas = $path . $slash . 'enviadas' . $slash . 'aprovadas' . $slash . $anomes . $slash;
                if (file_exists($dirAprovadas)) {
                    $File = $this->comprimir($dirAprovadas);
                    //echo $File;

                    $objRelGeralNotas = new p_relatorio_geral_notas();
                    $corpoTable = $objRelGeralNotas->mostraRelatorioGeral($this->m_letra);

                    $par = explode("|", $this->m_letra);
                    $dataIni = $par[3];
                    $dataFim = $par[4];

                    $letras = array('Á', 'á', 'ê', 'é', 'ô');
                    $subs = array('&Aacute;', '&aacute;', '&ecirc;', '&eacute;', '&ocirc;');

                    $email[2] = str_replace($letras, $subs, $email[2]);
                    $email[1] = $this->removeAcentos($email[1]);

                    //verifica se existe configuração, se não pega o padrão do sistema
                    if ($this->m_configsmtp == '' or $this->m_configemail == '' or $this->m_configemailsenha == '') {
                        $this->m_configsmtp = 'mail.admsistema.com.br';
                        $this->m_configemail = 'nfe@admsistema.com.br';
                        $this->m_configemailsenha = 'adm@2023';
                    }

                    $resultEmail = $this->enviarEmailXML($email[0], $email[1], $email[2] . $corpoTable, null, $dataIni, $dataFim, $File);

                    if ($resultEmail == true) {
                        $msgPrint = "Email enviado com sucesso!";
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                width: 510,
                                text: '" . $msgPrint . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        $this->mostraNotaFiscal('');
                    } else {
                        $msgPrint = $resultEmail;
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 510,
                                text: 'Não enviado, entre em contato com suporte',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        $this->mostraNotaFiscal('');
                    }
                } else {
                    $msgPrint = 'Diretório não localizado, entre em contato com suporte';
                    echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção',
                        width: 510,
                        text: '" . $msgPrint . ".',
                        confirmButtonText: 'OK'
                    });
                    </script>";
                    $this->mostraNotaFiscal('');
                }
                break;
            case 'devolucaoNotaFiscal':
                $this->devolucaoNotaFiscal();
                $this->cadastroDevolucaoNotaFiscal();

                break;
            case 'alterarDevolucaoNf':
                $idNfp = $this->getId();
                $nfp = new c_nota_fiscal_produto();
                $nfp->setId($idNfp);
                $nfp->setNotaFiscalProduto();
                $nfId = $nfp->getIdNf();
                $this->setId($nfId);
                $this->setNotaFiscal();
                $this->m_submenu = 'alterar';
                $this->desenhaCadastroNotaFiscal();

                break;
            case 'alteraDevolucao':
                $this->alteraDevolucaoNotaFiscal();
                $this->setNotaFiscal();
                $this->desenhaCadastroNotaFiscal();
                break;

            case 'voltarDevolucao':
                $this->excluiDevolucao();
                $this->m_submenu = "";
                $this->mostraNotaFiscal();
                break;
            case 'gerarEspelho':
                $objBanco = new c_banco();
                $objBanco->setTab('EST_NOTA_FISCAL');
                $msg =  $objBanco->getParametros('PATHDANFE', ' WHERE ID =' . $this->getId());
                $msg = '';
                if ($msg == '') {
                    $danfe = new p_espelho_nfe();
                    $time = round(microtime(true) * 1000);
                    $msg = $danfe->gera_XML($this->getId(), $this->getCentroCusto(), $this->getTipo(), null, $time);
                }

                if ($msg !== '') {
                    echo "<script>
                                function printDanfe(id) {
                                    window.open('index.php?mod=est&origem=imprimeDanfe&opcao=imprimir&form=nfephp_imprime_danfe&id='+id, 
                                    'DANFE','toolbar=no,location=no,resizable=yes,menubar=yes,width=950,height=900,scrollbars=yes');
                                };
                                </script>";
                    echo "<script>printDanfe(" . $this->getId() . ");</script>";
                    $this->mostraNotaFiscal('');
                } else {
                    $this->mostraNotaFiscal('Erro ao gerar a NFe sem valor fiscal', 'alert');
                }
                break;
                case 'consultaRecibo':
                    $objBanco = new c_banco();
                    $objBanco->setTab('EST_NOTA_FISCAL');
                    $numRecibo =  $objBanco->getParametros('NUMRECIBO', ' WHERE ID =' . $this->getId());
    
                    if($numRecibo == '' or $numRecibo == null){
    
                        $response = [
                            'status' => 'error',
                            'code' => 400,
                            'message' => 'Recibo não localizado!',
                            'errors' => [
                                'file' => "p_nota_fiscal",
                                'description' => "Recibo não localizado!"
                            ]
                        ];
    
                        //send json
                        $this->respondWithJson($response);
                        
                        die;
    
                    }else{
    
                        $objNfePhp = new p_nfe_40();
                        $objNotaFiscal = new c_nota_fiscal();
                        $result = $objNfePhp->consultaRecibo($numRecibo, $this->getId());
                        
                        $this->respondWithJson($result);
                        die;
                    }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstNotaFiscal', 'C')) {
                    $this->mostraNotaFiscal('');
                }
        }
    }
    /*
     * NOTA FISCAL PRODUTO
     */

    function desenhaPrintDanfe($mensagem = NULL, $tipoMsg = NULL, $result = null)
    {

        $id = $this->getId();
        $numPedido = $this->getDoc();
        $numNf = $this->getNumero();
        $this->smarty->assign('id', $id);
        $this->smarty->assign('danfe', $result['cDanfe']);
        $this->smarty->assign('pathCliente', ADMhttpCliente);


        $this->smarty->assign('numPedido', $numPedido);
        $this->smarty->assign('numNf', $numNf);
        //$this->smarty->assign('retorno', $retorno);


        $this->smarty->display('nota_fiscal_mostra_danfe.tpl');
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------

    function mostraEtiqueta($mensagem = NULL)
    {
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $notaFiscal = $this->setNotaFiscal();
        $this->smarty->assign('notaFiscal', $notaFiscal);
        $this->smarty->display('etiqueta.tpl');
    }
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function desenhaCadastroNotaFiscal($mensagem = NULL, $tipoMsg = NULL)
    {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('modelo', $this->getModelo());
        $this->smarty->assign('serie', $this->getSerie());
        $this->smarty->assign('numero', $this->getNumero());
        $this->smarty->assign('pessoa', $this->getPessoa());
        $this->setNomePessoa();
        $this->smarty->assign('nome', $this->getNomePessoa());
        $this->smarty->assign('emissao', "'" . $this->getEmissao('F') . "'");
        $this->smarty->assign('idNatop', $this->getIdNatop());
        $this->smarty->assign('natOperacao', "'" . $this->getNatOperacao() . "'");
        $this->smarty->assign('nfeReferenciada', "'" . $this->getNfeReferenciada() . "'");
        $this->smarty->assign('dataSaidaEntrada', "'" . $this->getDataSaidaEntrada('F') . "'");
        $this->smarty->assign('totalnf', $this->getTotalnf('F'));
        $this->smarty->assign('obs', $this->getObs());
        $this->smarty->assign('transportador', $this->getTransportador());
        $this->setNomeTransportador();
        $this->smarty->assign('transpNome', $this->getNomeTransportador());
        $this->smarty->assign('placaVeiculo', $this->getPlacaVeiculo());
        $this->smarty->assign('codAntt', $this->getCodAntt());
        $this->smarty->assign('uf', $this->getUf());
        $this->smarty->assign('volume', $this->getVolume());
        $this->smarty->assign('volumeEspecie', $this->getVolEspecie());
        $this->smarty->assign('volMarca', $this->getVolMarca());
        $this->smarty->assign('volPesoLiq', $this->getVolPesoLiq());
        $this->smarty->assign('volPesoBruto', $this->getVolPesoBruto());
        $this->smarty->assign('nProt', $this->getNProt());
        $this->smarty->assign('dhRecbto', "'" . $this->getDhRecbto() . "'");
        $this->smarty->assign('digVal', "'" . $this->getDigVal() . "'");
        $this->smarty->assign('verAplic', "'" . $this->getVerAplic() . "'");
        $this->smarty->assign('origem', "'" . $this->getOrigem() . "'");
        $this->smarty->assign('doc', "'" . $this->getDoc() . "'");
        $this->smarty->assign('t_origem', '');
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');


        // ########## NATUREZA OPERACAO ##########
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op order by natoperacao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $natOperacao_ids[$i] = $result[$i]['ID'];
            $natOperacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natOperacao_ids', $natOperacao_ids);
        $this->smarty->assign('natOperacao_names', $natOperacao_names);
        $this->smarty->assign('natOperacao_id', $this->getIdNatop());

        // COMBOBOX CONDICAO PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto order by descricao;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPgto());

        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        $this->smarty->assign('filial_id', $this->getCentroCusto());

        // tipo
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TipoNotaFiscal')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        $tipo_ids[0] = '';
        $tipo_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $tipo_ids[$i + 1] = $result[$i]['ID'];
            $tipo_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipo_ids', $tipo_ids);
        $this->smarty->assign('tipo_names', $tipo_names);
        $this->smarty->assign('tipo_id', $this->getTipo());


        //situacao
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='SituacaoNota')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $this->getsituacao());

        // ########## PROJETO ##########
        $consulta = new c_banco();
        $sql = "select nrcontrato as id, descricao from cat_contrato where situacao = 'a' order by descricao; ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para php 8.3 
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $contrato_ids[$i] = $result[$i]['ID'];
            $contrato_names[$i] =  $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('contrato_ids', $contrato_ids);
        $this->smarty->assign('contrato_names', $contrato_names);
        $this->smarty->assign('contrato_id', $this->getContrato());
        $this->smarty->assign('projeto', count($result));

        // genero documento
        $consulta = new c_banco();
        $sql = "select genero as id, descricao from fin_genero order by descricao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $generoDocto_ids[$i] = $result[$i]['ID'];
            $generoDocto_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('generoDocto_ids', $generoDocto_ids);
        $this->smarty->assign('generoDocto_names', $generoDocto_names);
        $this->smarty->assign('generoDocto_id', $this->getGenero());

        // forma pagamento
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='FormaPagamento')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $formaPagamento_ids[$i] = $result[$i]['ID'];
            $formaPagamento_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('formaPagamento_ids', $formaPagamento_ids);
        $this->smarty->assign('formaPagamento_names', $formaPagamento_names);
        $this->smarty->assign('formaPagamento_id', $this->getFormaPgto());

        // forma emissao
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='FormaEmissao')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $formaEmissao_ids[$i] = $result[$i]['ID'];
            $formaEmissao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('formaEmissao_ids', $formaEmissao_ids);
        $this->smarty->assign('formaEmissao_names', $formaEmissao_names);
        $this->smarty->assign('formaEmissao_id', $this->getFormaEmissao());


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
        $this->smarty->assign('finalidadeEmissao_id', $this->getFinalidadeEmissao());

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
        if ($this->modFrete == '') {
            $this->smarty->assign('modFrete_id', $this->getModFrete());
        } else {
            $this->smarty->assign('modFrete_id', $this->modFrete);
        }

        // ########## CENTROCUSTO ##########
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo order by centrocusto";
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

        $this->smarty->assign('frete', $this->getFrete());
        $this->smarty->assign('despacessorias', $this->getDespAcessorias());
        $this->smarty->assign('seguro', $this->getSeguro());



        /*
         * Nota Fiscal Produtos
         */
        //       if (($this->m_opcao == 'produto') || ($this->m_opcao == 'receber')){
        $digitarNum = 'S';
        $idNf = $this->getId();
        if (is_numeric($idNf)) {
            $NfProdutoOBJ = new c_nota_fiscal_produto();
            $NfProdutoOBJ->setIdNf($this->getId());
            $lancProd = $NfProdutoOBJ->select_nota_fiscal_produto_nf();
            $this->smarty->assign('lancProd', $lancProd);
            $digitarNum = 'N';
        }
        $this->smarty->assign('digitarNum', $digitarNum);

        //modal Email xmlDANFE
        if ($this->m_submenu == 'alterar') {
            $emailTitulo = "Nfe - envio XML/DANFE";

            $cliente = new c_banco;
            $cliente->setTab("FIN_CLIENTE");
            $emailCliente = strtolower($cliente->getField("EMAILNFE", "CLIENTE=" . $this->getPessoa()));
            $cliente->close_connection();

            if ($emailCliente == '') {
                $cliente = new c_banco;
                $cliente->setTab("FIN_CLIENTE");
                $emailCliente = strtolower($cliente->getField("EMAIL", "CLIENTE=" . $this->getPessoa()));
                $cliente->close_connection();
            }
            $this->smarty->assign('destinatario', $emailCliente);

            $usuario = new c_banco;
            $usuario->setTab("AMB_USUARIO");
            $emailUsuario = strtolower($usuario->getField("EMAIL", "USUARIO=" . $this->m_userid));
            $usuario->close_connection();

            $emailBody = "Prezados\n NF-E EMITIDA EM AMBIENTE DE " . ADMambDesc . "\n";
            $emailBody .= "Você está recebendo a Nota Fiscal Eletrônica emitida em " . $this->getEmissao('F') . " com o número " . $this->getNumero() . ", série " . $this->getSerie() . " de " . $this->m_empresanome . "
                    \n Junto com a mercadoria, você receberá também um DANFE (Documento Auxiliar da Nota Fiscal Eletrônica), que acompanha o trânsito das mercadorias.
                    \n Podemos conceituar a Nota Fiscal Eletrônica como um documento de existência apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins fiscais, uma operação de circulação de mercadorias, ocorrida entre as partes. Sua validade jurídica garantida pela assinatura digital do remetente (garantia de autoria e de integridade) e recepção, pelo Fisco, do documento eletrônico, antes da ocorrência do Fato Gerador.
                    \n Os registros fiscais e contábeis devem ser feitos, a partir do próprio arquivo da NF-e, anexo neste e-mail, ou utilizando o DANFE, que representa graficamente a Nota Fiscal Eletrônica. A validade e autenticidade deste documento eletrônico pode ser verificada no site nacional do projeto (www.nfe.fazenda.gov.br), através da chave de acesso contida no DANFE.
                    \n Para poder utilizar os dados descritos do DANFE na escrituração da NF-e, tanto o contribuinte destinatário, como o contribuinte emitente, terão de verificar a validade da NF-e. Esta validade está vinculada à efetiva existência da NF-e nos arquivos da SEFAZ, e comprovada através da emissão da Autorização de Uso.
                    \n O DANFE não é uma nota fiscal, nem substitui uma nota fiscal, servindo apenas como instrumento auxiliar para consulta da NF-e no Ambiente Nacional.
                    \n \n Para mais detalhes, consulte: www.nfe.fazenda.gov.br
                    \n \n Atenciosamente
                    \n " . $this->m_empresanome;

            $this->smarty->assign('comCopiaPara', $emailUsuario);

            $this->smarty->assign('assunto',  "'" . $emailTitulo . "'");
            $this->smarty->assign('emailCorpo', $emailBody);
        }

        if ($this->getId() != '') {
            $sql = "SELECT sum(total) as totalItem FROM EST_NOTA_FISCAL_PRODUTO  where (idnf=" . $this->getId() . ")";
            $banco = new c_banco();
            $banco->exec_sql($sql);
            $arrTotal =  $banco->resultado;
            $banco->close_connection();

            $this->smarty->assign('totalItem', $arrTotal[0]['TOTALITEM']);
        }

        //MODAL BUSCA COTACAO MOSTRA
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_ATUALIZA_TOTAL"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_ATUALIZA_TOTAL"] == "true") {
            $ajax_request = 'true';

            $updateTotal = $this->alteraTotalNf();
            $total = $this->atualizaTotalNfe($this->getId());
            if ($total !== '' || $total !== null) {
                $this->setTotalnf($total);
            }
            $newRateio = $this->calculaRateios($this->getId());

            $this->smarty->assign('totalnf', $this->getTotalnf('F'));
        } else {
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        }

        // Busca parâmetro CASASDECIMAIS
        $parametros = new c_parametros();
        $parametros->setFilial($this->m_empresacentrocusto);
        $casasDecimais = $parametros->getCasasDecimais();
        $this->smarty->assign('casasDecimais', $casasDecimais);

        $this->smarty->display('nota_fiscal_cadastro.tpl');
    }

    //fim desenhaCadastroNotaFiscal
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraNotaFiscal($mensagem = NULL,  $tipoMsg = NULL, $file = '')
    {

        if ($this->m_letra != '') {

            $lanc = $this->select_nota_fiscal_letra($this->m_letra);
        }

        if ((is_null($this->m_par[3]) or ($this->m_par[3] == '')) and (is_null($this->m_par[4]))  or ($this->m_par[4] == '')) {
            $emailBody = "";
            $emailTitulo = "";
            $emailContador = "";
        } else {
            $emailBody = "Você está recebendo os XML's das Notas Fiscais Eletrônica emitidas em " . $this->m_par[3] . " até " . $this->m_par[4] . ".";
            $emailTitulo = "Nfe - período de " . $this->m_par[3] . " até " . $this->m_par[4];

            $consulta = new c_banco();
            $sql  = "SELECT EMAIL FROM fin_cliente c ";
            $sql .= "LEFT JOIN fin_classe cla on (cla.classe=c.classe) ";
            $sql .= "WHERE cla.descricao = 'CONTABILIDADE' LIMIT 1 ";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            $emailContador = $result[0]['EMAIL'];
        }
        $this->smarty->assign('emailCorpo', $emailBody);
        $this->smarty->assign('emailTitulo', $emailTitulo);
        $this->smarty->assign('emailContador', $emailContador);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('numNf', $this->m_par[5]);
        $this->smarty->assign('serieNf', $this->m_par[6]);
        $this->smarty->assign('modeloNf', $this->m_par[13]);

        $this->smarty->assign('arquivo', $file);
        $this->smarty->assign('nomeArq', basename($file));

        if ($this->m_par[3] == "")
            $this->smarty->assign('dataIni', date("01/m/Y"));
        else
            $this->smarty->assign('dataIni', $this->m_par[3]);

        if ($this->m_par[4] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = mktime(0, 0, 0, $mes + 1, 0, $ano);
            $this->smarty->assign('dataFim', date("d/m/Y", $data));
        } else
            $this->smarty->assign('dataFim', $this->m_par[4]);

        // pessoa
        if ($this->m_par[7] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setPessoa($this->m_par[7]);
            $this->setNomePessoa();
            $this->smarty->assign('pessoa', $this->m_par[7]);
            $this->smarty->assign('nome', $this->getNomePessoa());
        }


        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        if ((!is_null($this->m_par[0])) and ($this->m_par[0] != '')) {
            $this->smarty->assign('filial_id', $this->m_par[0]);
        } else {
            $this->smarty->assign('filial_id',  $this->m_empresacentrocusto);
        }

        // tipo
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TipoNotaFiscal')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $tipo_ids[0] = "";
        $tipo_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $tipo_ids[$i + 1] = $result[$i]['ID'];
            $tipo_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipo_ids', $tipo_ids);
        $this->smarty->assign('tipo_names', $tipo_names);
        if ($this->m_par[1] == "")
            $this->smarty->assign('tipo_id', '1');
        else
            $this->smarty->assign('tipo_id', $this->m_par[1]);

        //sql para mostrar a situacao no combobox
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='SituacaoNota')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $situacao_ids[0] = 0;
        $situacao_names[0] = 'Todas';
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        if ($this->m_par[2] == "")
            $this->smarty->assign('situacao_id', 'B');
        else
            $this->smarty->assign('situacao_id', $this->m_par[2]);

        // finalidade emissao
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='FinalidadeEmissao')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $finalidadeEmissao_ids[0] = '';
        $finalidadeEmissao_names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $finalidadeEmissao_ids[$i + 1] = $result[$i]['ID'];
            $finalidadeEmissao_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('finalidadeEmissao_ids', $finalidadeEmissao_ids);
        $this->smarty->assign('finalidadeEmissao_names', $finalidadeEmissao_names);
        $this->smarty->assign('finalidadeEmissao_id', $this->m_par[9]);


        // modalidade frete
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='modFrete')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $modFrete_ids[0] = '';
        $modFrete_names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $modFrete_ids[$i + 1] = $result[$i]['ID'];
            $modFrete_names[$i + 1] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('modFrete_ids', $modFrete_ids);
        $this->smarty->assign('modFrete_names', $modFrete_names);
        $this->smarty->assign('modFrete_id', $this->m_par[10]);

        // ########## NATUREZA OPERACAO ##########
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where (tipo='S') order by id";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $natOperacao_ids[0] = '';
        $natOperacao_names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $natOperacao_ids[$i + 1] = $result[$i]['ID'];
            $natOperacao_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natOperacao_ids', $natOperacao_ids);
        $this->smarty->assign('natOperacao_names', $natOperacao_names);
        $this->smarty->assign('natOperacao_id', $this->m_par[8]);


        //genero 11
        if (!empty($this->m_par[11])) {
            $consulta = new c_banco();
            $consulta->setTab("FIN_GENERO");
            $result = $consulta->getField("DESCRICAO", "GENERO='" . $this->m_par[11] . "'");
            $consulta->close_connection();

            $this->smarty->assign('genero', $this->m_par[11]);
            $this->smarty->assign('descGenero', $result);
        }

        // transportador 12

        if (!empty($this->m_par[12])) {
            $this->setTransportador($this->m_par[12]);
            $this->setNomeTransportador();
            $this->smarty->assign('transportador', $this->m_par[12]);
            $this->smarty->assign('transpNome', $this->getNomeTransportador());
        }

        $this->smarty->assign('from', $this->m_from);

        $this->smarty->display('nota_fiscal_mostra.tpl');
    }



    function buscarNotasFiscais($letra)
    {
        $par = explode("|", $letra);
        $sql = "SELECT * ";
        $sql .= "FROM est_nota_fiscal ";
        if (!empty($par[0])) {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
            $sql .= "(descricao like '" . $par[0] . "%') ";
        }
        if (!empty($par[1])) {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
            $sql .= "(grupo = '" . $par[1] . "') ";
        }

        if ($par[2] == 'S') {
            if (!$isWhere) {
                $sql .= "WHERE ";
                $isWhere = true;
            } else {
                $sql .= "AND ";
            }
            $sql .= "((iniciopromocao <= '" . $data . "') and (fimpromocao >= '" . $data . "'))";
        }
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //buscarNotaFiscais

    function comprimir($caminho)
    {
        $caminho = realpath($caminho);
        $arquivo = $caminho . '.zip';
        $zip = new ZipArchive();
        $zip->open($arquivo, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $arquivos = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($caminho),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($arquivos as $name => $file) {
            if (!$file->isDir()) {
                $file_path = $file->getRealPath();
                $relative_path = substr($file_path, strlen($caminho) + 1);
                $zip->addFile($file_path, $relative_path);
            }
        }
        $zip->close();
        //echo $arquivo;
        return $arquivo;
    }


    public function enviarEmailXML($email, $title, $body, $cc = null, $dhIni, $dhFim, $fileName01, $fileName02 = null)
    {

        try {
            if (is_null($email) or ($email == '')) {
                return 'Email envio não cadastrado';
            } else {
                if (is_null($email)  or ($email == '')) {
                    $aMails = array($cc); //se for um array vazio a classe Mail irá pegar os emails do xml
                }
            }

            if (is_null($fileName02)) {
                $fileName02 = $fileName01;
            }

            $mail = new admMail;

            $descPrincipal = "Email Xml's - " . $this->removeAcentos($this->m_empresanome);

            //verifica se existe configuração, se não pega o padrão do sistema
            if ($this->m_configsmtp == '' or $this->m_configemail == '' or $this->m_configemailsenha == '') {
                $this->m_configsmtp = 'mail.admsistema.com.br';
                $this->m_configemail = 'nfe@admsistema.com.br';
                $this->m_configemailsenha = 'adm@2023';
            }

            $result = $mail->SendMail(
                $this->m_configsmtp,
                $this->m_configemail,
                $descPrincipal,
                $this->m_configemailsenha,
                $body,
                $title,
                $email,
                "",
                $cc,
                "",
                $fileName01,
                $fileName02
            );


            if (strstr($result, 'não')) {
                //return "email XML's NÃO enviado - entre em contato com o suporte";
                return $result;
            } else {
                return true;
            }
        } catch (Exception $e) {
            return false; // Retorna false em caso de exceção
        }
    }
    public function excluiDevolucao()
    {
        $sql = "DELETE FROM EST_NOTA_FISCAL_PRODUTO WHERE IDNF = " . $this->getId();
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        $sql = "DELETE FROM EST_NOTA_FISCAL WHERE ID = " . $this->getId();
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }

    public function devolucaoNotaFiscal()
    {
        try {

            $transaction = new c_banco();

            //inicia transacao
            $transaction->inicioTransacao($transaction->id_connection);
            $nfRef = $this->pegarNfeReferenciada();

            $nfAntigas = explode("|", $this->devolucaoNotaFiscal);
            $this->setId($nfAntigas[1]);
            $this->setNotaFiscal();

            $pessoa = $this->getPessoa();
            // limpando o id da NF
            $this->setId("");

            // nova NF

            $this->setModelo(55);
            $this->setSerie(1);
            $this->setNumero(0);
            $this->setPessoa($pessoa);
            $this->setEmissao(date('d/m/Y H:i'));
            //nat operacao
            $this->setIdNatop(2);
            $this->setNatOperacao('DEVOLUCAO');
            $this->setTipo(1); // 0=Entrada; 1=Saída; 
            $this->setSituacao('A');
            $this->setFormaPgto('0');
            $this->setDataSaidaEntrada(date('d/m/Y H:i'));
            $this->setFinalidadeEmissao(4);
            $this->setCentroCusto($this->m_empresacentrocusto);
            $this->setTotalnf(0);
            $this->setObs('DEVOLUÇÃO NF ' . $this->devolucaoNotaFiscal . " " . date('d/m/Y H:i'));
            $this->setNfeReferenciada($nfRef);
            // insere nf
            $lastNF = $this->incluiNotaFiscal($transaction->id_connection);
            $classNFProduto = new c_nota_fiscal_produto();

            //busca itens nf
            $notasFiscal =  $classNFProduto->select_nota_fiscal_produtos_devolucao($this->devolucaoNotaFiscal);


            for ($i = 0; $i < count($notasFiscal); $i++) {
                //EST_NOTA_FISCAL_ESTOQUE

                $classNFProduto->setId($notasFiscal[$i]['ID']);

                // $classNFProduto->setNotaFiscalProduto();



                $notaFiscal = $classNFProduto->select_nota_fiscal_produto();
                $classNFProduto->setId($notaFiscal[0]['ID']);
                $classNFProduto->setIdNf($notaFiscal[0]['IDNF']);
                $classNFProduto->setCodProduto($notaFiscal[0]['CODPRODUTO']);
                $classNFProduto->setDescricao($notaFiscal[0]['DESCRICAO']);
                $classNFProduto->setUnidade($notaFiscal[0]['UNIDADE']);
                $classNFProduto->setQuant($notaFiscal[0]['QUANT'], true);
                $classNFProduto->setUnitario($notaFiscal[0]['UNITARIO'], true);
                $classNFProduto->setDesconto($notaFiscal[0]['DESCONTO'], true);
                $classNFProduto->setTotal($notaFiscal[0]['TOTAL'], true);
                $classNFProduto->setOrigem($notaFiscal[0]['ORIGEM']);
                $classNFProduto->setTribIcms($notaFiscal[0]['TRIBICMS']);
                $classNFProduto->setNcm($notaFiscal[0]['NCM']);
                $classNFProduto->setCest($notaFiscal[0]['CEST']);
                $classNFProduto->setCfop($notaFiscal[0]['CFOP']);
                $classNFProduto->setAliqIcms($notaFiscal[0]['ALIQICMS'], true);
                $classNFProduto->setPercReducaoBc($notaFiscal[0]['PERCREDUCAOBC'], true);
                $classNFProduto->setModBc($notaFiscal[0]['MODBC']);
                $classNFProduto->setBcIcms($notaFiscal[0]['BCICMS'], true);
                $classNFProduto->setValorIcms($notaFiscal[0]['VALORICMS'], true);
                $classNFProduto->setAliqIpi($notaFiscal[0]['ALIQIPI'], true);
                $classNFProduto->setValorIpi($notaFiscal[0]['VALORIPI'], true);
                $classNFProduto->setPercDiferido($notaFiscal[0]['PERCDIFERIDO'], true);
                $classNFProduto->setValorIcmsDiferido($notaFiscal[0]['VALORICMSDIFERIDO'], true);
                $classNFProduto->setValorIcmsOperacao($notaFiscal[0]['VALORICMSOPERACAO'], true);
                $classNFProduto->setModBcSt($notaFiscal[0]['MODBCST']);
                $classNFProduto->setPercMvaSt($notaFiscal[0]['PERCMVAST'], true);
                $classNFProduto->setBcFcpSt($notaFiscal[0]['BCFCPST'], true);
                $classNFProduto->setAliqFcpSt($notaFiscal[0]['ALIQFCPST'], true);
                $classNFProduto->setValorFcpSt($notaFiscal[0]['VALORFCPST'], true);
                $classNFProduto->setPercReducaoBcSt($notaFiscal[0]['PERCREDUCAOBCST'], true);
                $classNFProduto->setValorBcSt($notaFiscal[0]['VALORBCST'], true);
                $classNFProduto->setAliqIcmsSt($notaFiscal[0]['ALIQICMSST'], true);
                $classNFProduto->setValorIcmsSt($notaFiscal[0]['VALORICMSST'], true);
                $classNFProduto->setValorTotalTributos($notaFiscal[0]['VALORTOTALTRIBUTOS'], true);
                //$classNFProduto->setVBCSTRet($notaFiscal[0]['VALORBCSTRETIDO']);
                //$classNFProduto->setVICMSSTRet($notaFiscal[0]['VALORICMSSTRETIDO']);
                $classNFProduto->setCustoProduto($notaFiscal[0]['CUSTOPRODUTO'], true);
                $classNFProduto->setNrSerie($notaFiscal[0]['NRSERIE']);
                $classNFProduto->setLote($notaFiscal[0]['LOTE']);
                $classNFProduto->setDataFabricacao($notaFiscal[0]['DATAFABRICACAO']);
                $classNFProduto->setDataValidade($notaFiscal[0]['DATAVALIDADE']);
                $classNFProduto->setDataGarantia($notaFiscal[0]['DATAGARANTIA']);
                $classNFProduto->setDataConferencia($notaFiscal[0]['DATACONFERENCIA']);
                $classNFProduto->setOrdem($notaFiscal[0]['ORDEM']);
                $classNFProduto->setProjeto($notaFiscal[0]['PROJETO']);
                $classNFProduto->setCstPis($notaFiscal[0]['CSTPIS']);
                $classNFProduto->setBcPis($notaFiscal[0]['BCPIS'], true);
                $classNFProduto->setAliqPis($notaFiscal[0]['ALIQPIS'], true);
                $classNFProduto->setValorPis($notaFiscal[0]['VALORPIS'], true);
                $classNFProduto->setCstCofins($notaFiscal[0]['CSTCOFINS'], true);
                $classNFProduto->setBcCofins($notaFiscal[0]['BCCOFINS'], true);
                $classNFProduto->setAliqCofins($notaFiscal[0]['ALIQCOFINS'], true);
                $classNFProduto->setValorCofins($notaFiscal[0]['VALORCOFINS'], true);
                $classNFProduto->setVBCSTRet($notaFiscal[0]['VBCSTRET'], true);
                $classNFProduto->setPSt($notaFiscal[0]['PST']);
                $classNFProduto->setVICMSSubstituto($notaFiscal[0]['VICMSSUBSTITUTO'], true);
                $classNFProduto->setVICMSSTRet($notaFiscal[0]['VICMSSTRET'], true);
                $classNFProduto->setCBenef($notaFiscal[0]['CBENEF'], true);

                $classNFProduto->setBcFcpUfDest($notaFiscal[0]['BCFCPUFDEST'], true);
                $classNFProduto->setAliqFcpUfDest($notaFiscal[0]['ALIQFCPUFDEST'], true);
                $classNFProduto->setValorFcpUfDest($notaFiscal[0]['VALORFCPUFDEST'], true);

                $classNFProduto->setBcIcmsUfDest($notaFiscal[0]['BCICMSUFDEST'], true);
                $classNFProduto->setAliqIcmsUfDest($notaFiscal[0]['ALIQICMSUFDEST'], true);
                $classNFProduto->setAliqIcmsInter($notaFiscal[0]['ALIQICMSINTER'], true);
                $classNFProduto->setAliqIcmsInterPart($notaFiscal[0]['ALIQICMSINTERPART'], true);
                $classNFProduto->setValorIcmsUfDest($notaFiscal[0]['VALORICMSUFDEST'], true);
                $classNFProduto->setValorIcmsUfRemet($notaFiscal[0]['VALORICMSUFREMET'], true);

                $classNFProduto->setIdNf($lastNF);
                $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));

                // $qtde = $classNFProduto->getQuant('F');
                // $desconto = $classNFProduto->getDesconto('F');
                // $total = $classNFProduto->getTotal('F');
                // $vlrUnitario = $classNFProduto->getUnitario('F');

                // $classNFProduto->setQuant($qtde);
                // $classNFProduto->setDesconto($desconto);
                // $classNFProduto->setTotal($total);
                // $classNFProduto->setUnitario( $vlrUnitario);

                $ObjProduto = new c_produto();
                $ObjProduto->setId($notaFiscal[0]['CODPRODUTO']);
                $resultConsProd = $ObjProduto->select_produto();
                if ($resultConsProd[0]['CODFABRICANTE'] !== '' and $resultConsProd[0]['CODFABRICANTE'] !== null) {
                    $classNFProduto->setCodigoNota($resultConsProd[0]['CODFABRICANTE']);
                } else {
                    $classNFProduto->setCodigoNota($notaFiscal[0]['CODPRODUTO']);
                }

                $classNFProduto->incluiNotaFiscalProduto($transaction->id_connection);
            }

            $transaction->commit($transaction->id_connection);

            $this->setId($lastNF);
        } catch (Error $e) {
            $transaction->rollback($transaction->id_connection);
            throw new Exception($e->getMessage() . "NF não cadastrada ");
        }
    }
    public function cadastroDevolucaoNotaFiscal($mensagem = null, $tipoMsg = null)
    {

        // CENTRO DE CUSTO
        $sql = "select CENTROCUSTO AS id, descricao from FIN_CENTRO_CUSTO";
        $this->comboSql($sql, $this->m_empresacentrocusto, $ccusto_id, $ccusto_ids, $ccusto_names);
        $this->smarty->assign('ccusto_id',    $ccusto_id);
        $this->smarty->assign('ccusto_ids',   $ccusto_ids);
        $this->smarty->assign('ccusto_names', $ccusto_names);

        // NAT OPERACAO
        $sql = "select id, natoperacao as descricao from est_nat_op order by natoperacao";
        $this->comboSql($sql, $this->getIdNatop(), $this->getIdNatop(), $natOperacao_ids, $natOperacao_names);
        $this->smarty->assign('natOperacao_ids', $natOperacao_ids);
        $this->smarty->assign('natOperacao_names', $natOperacao_names);
        $this->smarty->assign('natOperacao_id', $this->getIdNatop());


        $this->setNotaFiscal();

        //$this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idnf', $this->getId());
        $this->smarty->assign('pessoa', $this->getPessoa());
        $this->smarty->assign('pessoaNome', $this->getnomePessoa());
        // $this->smarty->assign('totalnf', $this->getTotalnf('F'));
        $this->smarty->assign('emissao', $this->getEmissao('F'));

        $nfp = new c_nota_fiscal_produto();

        $nfp->setIdNf($this->getId());
        $lanc = $nfp->select_nota_fiscal_produto_nf();


        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('dataConferencia', "'" . $this->getDataConferencia('F') . "'");
        $this->smarty->assign('lanc', $lanc);

        //sql para mostrar a situacao no combobox
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='SituacaoNota') and (tipo = '" . $this->getSituacao() . "')";
        $consulta->exec_sql($sql);
        $this->smarty->assign('situacao_name', "'" . $consulta->resultado[0]['DESCRICAO'] . "'");

        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TipoNotaFiscal') and (tipo = '" . $this->getTipo() . "')";
        $consulta->exec_sql($sql);
        $this->smarty->assign('tipo_name', "'" . $consulta->resultado[0]['DESCRICAO'] . "'");

        $consulta->close_connection();


        $this->smarty->display('nota_fiscal_devolucao_cadastro.tpl');
    }

    public function alteraDevolucaoNotaFiscal()
    {

        (float)$totalNota = 0.00;
        $idNf = $this->getId();
        $itensDevolucao = explode("|", $this->nfProdDevolucao);
        $idsNfp = "";
        for ($i = 1; $i < count($itensDevolucao); $i++) {
            $itemNf = explode("*", $itensDevolucao[$i]);

            $this->setId($itemNf[1]);

            $qtde = str_replace('.', '', $itemNf[2]);
            $qtde = str_replace(',', '.', $qtde);

            $vlrUni = str_replace('.', '', $itemNf[3]);
            $vlrUni = str_replace(',', '.', $vlrUni);

            (float)$totalItemNf = ($qtde * $vlrUni);

            $sql = "UPDATE EST_NOTA_FISCAL_PRODUTO SET ";
            $sql .= "QUANT      = '" . $qtde . "', ";
            $sql .= "UNITARIO   = '" . $vlrUni . "', ";
            $sql .= "TOTAL      = '" . $totalItemNf . "', ";
            $sql .= "CFOP       = '" . $itemNf[4] . "' ";
            $sql .= "WHERE ID = " . $this->getId();
            $banco = new c_banco;
            $banco->exec_sql($sql);
            $banco->close_connection();

            $totalNota += $totalItemNf;

            $idsNfp = $idsNfp . "|" . $itemNf[1];
        }

        $nfp = new c_nota_fiscal_produto();
        $nfp->deleteNfProdutosDevolucao($idsNfp, $idNf);
        $this->setId($idNf);

        $sql = "UPDATE EST_NOTA_FISCAL SET ";
        $sql .= "IDNATOP = '" . $this->getIdNatop() . "', ";
        $sql .= "FORMAPGTO = 0, ";
        $sql .= "TOTALNF = " . $totalNota . ", ";
        $sql .= "FORMAEMISSAO = 'N' ";
        $sql .= "WHERE ID = " . $this->getId();
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        $this->m_opcao = 'devolucao';
    }

    public function pegarNfeReferenciada()
    {
        $nfs = explode("|", $this->devolucaoNotaFiscal);
        $nfRef = "";
        for ($i = 1; $i < count($nfs); $i++) {
            $notaFiscal = new c_banco;
            $notaFiscal->setTab("EST_NOTA_FISCAL");
            $nfReferenciada = $notaFiscal->getField("CHNFE", "ID= '" . $nfs[$i] . "'");
            $notaFiscal->close_connection();
            if ($nfReferenciada != '') {
                $nfRef = $nfRef . "|" . $nfReferenciada;
            }
        }

        return $nfRef;
    }



    function comboSql($sql, $par, &$id, &$ids, &$names)
    {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        $ids = [];
        $names = [];
        $id = [];

        for ($i = 0; $i < count($result); $i++) {
            $ids[$i] = $result[$i]['ID'];
            $names[$i] = $result[$i]['DESCRICAO'];
        }

        $param = explode(",", $par);
        foreach ($param as $value) {
            if (!empty(trim($value))) {
                $id[] = $value;
            }
        }

        if (empty($id)) {
            $id[] = "0";
        }
    }

    function respondWithJson($data)
    {
        header('Content-type: application/json');
        echo json_encode($data, JSON_FORCE_OBJECT);
    }
    //fim mostraNotaFiscal
    //-------------------------------------------------------------

    function removeAcentos($string)
    {
        $conversao = array(
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ï' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            "ö" => "o",
            'ú' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'ñ' => 'n',
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'É' => 'E',
            'Ê' => 'E',
            'Í' => 'I',
            'Ï' => 'I',
            "Ö" => "O",
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ú' => 'U',
            'Ü' => 'U',
            'Ç' => 'C',
            'Ñ' => 'N'
        );
        return strtr($string, $conversao);
    }
}



//	END OF THE CLASS
// Rotina principal - cria classe
$NotaFiscal = new p_nota_fiscal();

$NotaFiscal->controle();
