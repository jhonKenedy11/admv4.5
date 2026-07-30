<?php

/**
 * @package   admv4.5
 * @name      p_cotacao
 * @version   4.5.0
 * @copyright 2025
 * @link      http://www.admsistema.com.br/
 * @author    Joshua Silva
 * @date      2025
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_ps.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/ped/c_parametro.php");
require_once($dir . "/../../class/ped/c_cotacao.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir."/../../bib/dompdf/lib/html5lib/Parser.php");
require_once($dir."/../../bib/dompdf/lib/php-font-lib-master/src/FontLib/Autoloader.php");
require_once($dir."/../../bib/dompdf/lib/php-svg-lib-master/src/autoload.php");
require_once($dir."/../../bib/dompdf/src/Autoloader.php");
include_once($dir . "/../../bib/c_mail.php");

Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

//Class p_cotacao
class p_cotacao extends  c_cotacao {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    public $smarty          = NULL;
    public $m_letra_peca    = NULL;
    private $m_cliente      = NULL;

    function __construct()
    {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");

        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Cotação");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6]");
        $this->smarty->assign('disableSort', "[ 0 ]");
        $this->smarty->assign('numLine', "25");
        
        // metodo SET dos dados do FORM para o TABLE
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->setId(isset($parmPost['numCotacao']) ? $parmPost['numCotacao'] : (isset($parmGet['numCotacao']) ? $parmGet['numCotacao'] : ''));
        $this->setCliente(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setDataIni(isset($parmPost['dataIni']) ? $parmPost['dataIni'] : '');
        $this->setDataFim(isset($parmPost['dataFim']) ? $parmPost['dataFim'] : '');
        $this->setEmissao(isset($parmPost['emissao']) ? $parmPost['emissao'] : date("d/m/Y"));
        $this->setUsrAbertura($this->m_userid); // Sempre pega o usuário logado
       
        //=========================PARAMETROS==================================
        $this->setPrazoEntrega(isset($parmPost['prazoEntrega']) ? $parmPost['prazoEntrega'] : '');
        $this->setValorProduto(isset($parmPost['valorProdutos']) ? $parmPost['valorProdutos'] : 0);
        $this->setValorDesconto(isset($parmPost['valorDesconto']) ? $parmPost['valorDesconto'] : 0);
        $this->setDesconto(isset($parmPost['valorDesconto']) ? $parmPost['valorDesconto'] : 0);
        $this->setValorTotal(isset($parmPost['valorTotal']) ? $parmPost['valorTotal'] : 0);
        $this->setCondPgto(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : $this->m_empresacentrocusto);
        $this->setSituacao('5'); // Situacao sempre será cotação
        $this->setEspecie("D");
        $this->setIdNatop("1");

        //=========================PRODUTOS==================================
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');
        $this->setIdPedidoItem(isset($parmPost['idCotacaoItem']) ? $parmPost['idCotacaoItem'] : '');
        $this->setCodProduto(isset($parmPost['codProduto']) ? $parmPost['codProduto'] : '');
        $this->setCodProdutoNota(isset($parmPost['codProdutoNota']) ? $parmPost['codProdutoNota'] : '');
        $this->setQuantidadeProduto(isset($parmPost['quantidadeProduto']) ? $parmPost['quantidadeProduto'] : '');
        $this->setUnidadeProduto(isset($parmPost['uniProduto']) ? $parmPost['uniProduto'] : '');
        $this->setVlrUnitarioProduto(isset($parmPost['vlrUnitarioProduto']) ? $parmPost['vlrUnitarioProduto'] : '');
        $this->setDescricaoProduto(isset($parmPost['descProduto']) ? $parmPost['descProduto'] : '');
        $this->setVlrCustoProduto(isset($parmPost['vlrCustoProduto']) ? $parmPost['vlrCustoProduto'] : '');
        $this->setDescontoProduto(isset($parmPost['vlrDescontoProduto']) ? $parmPost['vlrDescontoProduto'] : '');
        $this->setPercDescontoProduto(isset($parmPost['percDescontoProduto']) ? $parmPost['percDescontoProduto'] : '');
        $this->setTotalProduto(isset($parmPost['totalProduto']) ? $parmPost['totalProduto'] : '');
        $this->setCodFabricante(isset($parmPost['codFabricante']) ? $parmPost['codFabricante'] : '');
        $this->setDataEntregaPeca(isset($parmPost['dataEntregaPeca']) ? $parmPost['dataEntregaPeca'] : '');
        $this->setDataAbertura(isset($parmPost['dataIni']) ? $parmPost['dataIni'] : '');
        $this->setDataFechamentoEnd(isset($parmPost['dataFim']) ? $parmPost['dataFim'] : '');
        $this->setPercentualAplicar(isset($parmPost['percentualAplicar']) ? $parmPost['percentualAplicar'] : '0');
        $this->setItensPedidoCC(isset($parmPost['itensPedidoCC']) ? $parmPost['itensPedidoCC'] : '');
        $this->setPesqCc(isset($parmPost['pesq_cc']) ? $parmPost['pesq_cc'] : '');
        $this->setDescCc(isset($parmPost['desc_cc']) ? $parmPost['desc_cc'] : '');
        $this->setMarkupProduto(isset($parmPost['markupItem']) ? $parmPost['markupItem'] : '');
        $this->setMarkup(isset($parmPost['markupCotacao']) ? $parmPost['markupCotacao'] : '');
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $this->desenhaCadastroCotacao();
                }
                break;
                case 'gerar_pedido':
                    if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                        if (!empty($this->getId())) {
                            $this->updateField("SITUACAO", "6", "FAT_PEDIDO");
                            $this->updateField("PEDIDO", $this->getId(), "FAT_PEDIDO");
                            $idPedido = $this->getId();
                            $this->setId('');
                            $this->mostraCotacao();
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pedido Gerado!',
                                    text: 'Cotação N°: " . $idPedido . " convertida para pedido com sucesso.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        } else {
                            $this->mostraCotacao();
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'ID da cotação não informado.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        }
                    }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    if ($this->getId() != ''){
                        $this->desenhaCadastroCotacao();
                    }
                }
                break;
            case 'inclui': // CONCLUIR
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $idInsert = $this->incluiPedido();
                    $result = $this->getValorTotal();
                    $this->updateField("TOTAL", $result, "FAT_PEDIDO");
                    if($idInsert > 0){
                        $this->mostraCotacao();
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Registro Salvo!',
                                text: 'N°: " . $idInsert . "',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    } else {
                        $this->mostraCotacao();
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro ao salvar o registro.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    }
                }
                break;
            case 'altera': // CONCLUIR
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    if($this->getId() != ''){
                        $res = $this->alteraPedido();
                        if(!empty($this->getDesconto())){
                            
                        }
                        if($res === true){
                            $this->mostraCotacao();
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro Salvo!',
                                    text: 'N°: " . $this->getId() . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        }else{
                            $this->mostraCotacao();
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: '" . addslashes($res) . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        }
                    }
                }
                break;
                case 'exclui':
                    if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                        $res = $this->cancelaCotacao();
                        if($res === true){
                            $id = $this->getId();
                            $this->setId('');
                            $this->mostraCotacao();
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                            Swal.fire({ 
                                icon: 'success',
                                title: 'Registro Cancelado!',
                                text: 'N°: " . $id . "',
                                confirmButtonText: 'OK'
                                });
                                </script>";
                        } else {
                            $this->mostraCotacao();
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: '" . addslashes($res) . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        }
                    }
                break;
                case 'duplica':
                    if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                        $idAntigo = $this->getId();
                        $idGerado = $this->duplicaPedido();
                        $this->setId($idGerado);
                        $this->updateField('PEDIDO', $idGerado, "FAT_PEDIDO");
                        $this->duplicaPedidoItem($idGerado, $idAntigo);
                        $this->buscaPedido();
                        $this->m_submenu = 'alterar';
                        $this->desenhaCadastroCotacao();
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Cotação Duplicada!',
                                text: 'N°: " . $idGerado . "',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    }
                    break;

            case 'inclui_item':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $this->incluiItemCotacaoAjax();
                }
            break;
            case 'altera_item':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $this->alteraItemCotacaoAjax();
                }
            break;
            case 'busca_produto':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $produto = new c_produto();
                    $produto->retornaHtmlEquivalencias($this->getCodFabricante());
                }
            break;
            case 'info_produto':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $produto = new c_produto();
                    $produto->retornaInfoProduto(
                        $this->getCodProduto(),
                        $this->getCentroCusto(),
                        $this->getCliente(),
                        $this->getVlrUnitarioProduto(),
                        $this->getQuantidadeProduto(),
                        $this->getDescontoProduto()
                    );
                }
            break;
            case 'exclui_item':
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $this->excluiItemCotacaoAjax();
                }
            break;
            case 'aplicar_percentual':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $res = $this->aplicarPercentualItens();
                    if($res === true){
                        $this->desenhaCadastroCotacao();
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Percentual Aplicado!',
                                text: 'Percentual de " . $this->getPercentualAplicar() . "% aplicado com sucesso em todos os itens.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    } else {
                        $this->desenhaCadastroCotacao();
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: '" . addslashes($res) . "',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    }
                }
            break;
            case 'atualizarInfo':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $desconto = $this->getDesconto();
                    $descontoFormatado = $this->getDesconto('F');
                    $this->setDesconto($descontoFormatado);
                    $this->calculaImpostos();
                    $this->m_pesq = '';
                    $this->setDesconto($desconto);
                    $this->desenhaCadastroCotacao();
                }
            break;
            case 'copiar_colar':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $this->desenhaCadastroCotacao();
                }
            break;
            case 'cadastrarItemCC':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $res = $this->cadastrarItensCopiarColar();
                    if($res === true){
                        $this->desenhaCadastroCotacao();
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Itens Adicionados!',
                                text: 'Produtos adicionados com sucesso.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    } else {
                        $this->desenhaCadastroCotacao();
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: '" . addslashes($res) . "',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    }
                }
            break;            
            case 'atualizar_markup':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $res = $this->atualizarMarkup();
                    echo $res;
                    exit;
                }
            break;
            case 'enviar_email':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $this->enviarEmailCotacao();
                }
            break;
            case 'download_pdf':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $this->downloadPdfCotacao();
                }
            break;           
            default:
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $this->mostraCotacao();
                }
            break;
        }
    }

    function desenhaCadastroCotacao()
    {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('id', $this->getId());


        $this->smarty->assign('prazoEntrega', $this->getPrazoEntrega('F'));
        $this->smarty->assign('condPgto', $this->getCondPgto());

        if ($this->getId() != ''){ 
            $lanc = $this->select_pedido_parametros();
            $this->smarty->assign('lanc', $lanc);
        }
        $this->smarty->assign('pessoa', $lanc[0]['CLIENTE']);
        $this->smarty->assign('nome', $lanc[0]['NOME']);
        $this->smarty->assign('emissao', $lanc[0]['EMISSAO']);
        if ($this->getId() != ''){
            $lancPesq = $this->select_pedido_item_id('1');
            $this->smarty->assign('lancPesq', $lancPesq);
        }
        $valorProduto = number_format($lanc[0]['TOTALPRODUTOS'], 2, ',', '.');
        $this->smarty->assign('valorProduto', $valorProduto);
        $valorDesconto = number_format($lanc[0]['DESCONTO'], 2, ',', '.');
        $this->smarty->assign('valorDesconto', $valorDesconto);
        $valorTotal = number_format($lanc[0]['TOTAL'], 2, ',', '.');
        $this->smarty->assign('valorTotal', $valorTotal);

        // Busca parâmetro CASASDECIMAIS
        $parametros = new c_parametros();
        $parametros->setFilial($this->m_empresacentrocusto);
        $casasDecimais = $parametros->getCasasDecimais();
        $this->smarty->assign('casasDecimais', $casasDecimais);


        $this->comboCondPgto();
        
        $this->copiarEcolar();
        
        $this->smarty->display('cotacao_cadastro.tpl');
    }

    function mostraCotacao()
    {   

        if ($this->getId() != ''){
            $lanc = $this->select_pedido_parametros();
        } else if ($this->getCliente() != '' || $this->getDataIni() != '' || $this->getDataFim() != ''){
            $lanc = $this->select_pedido_parametros();
        } else {
            $this->setDataIni(date("01/m/Y"));
            $this->setDataFim(date("d/m/Y"));
            $lanc = $this->select_pedido_parametros();
        }
        if ($this->getDataIni() == '' && $this->getDataFim() == ''){
            $this->setDataIni(date("01/m/Y"));
            $this->setDataFim(date("d/m/Y"));
        }
        if($this->getId() != ''){
            $this->smarty->assign('numCotacao', $this->getId());
        }

        $valorTotal = number_format($lanc[0]['TOTAL'], 2, ',', '.');
        $this->smarty->assign('valorTotal', $valorTotal);
        $this->smarty->assign('dataIni', $this->getDataIni());
        $this->smarty->assign('dataFim', $this->getDataFim());
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->display('cotacao_mostra.tpl');
    }

}

$cotacao = new p_cotacao();
$cotacao->controle();

