<?php

/**
 * @package   admsis
 * @name      p_pedido_ps
 * @version   4.3.2
 * @copyright 2021
 * @link      http://www.admsistema.com.br/
 * @author    Márcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      10/05/2021
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_ps.php");
require_once($dir . "/../../class/ped/c_cotacao.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/cat/c_servico.php");
require_once($dir . "/../../class/ped/c_pedido_ps_tools.php");
require_once($dir . "/../../class/ped/c_parametro.php");
include_once($dir . "/../../forms/ped/p_pedido_venda_nf_pecas_novo.php");

//Class p_pedido_ps
class p_pedido_ps extends c_pedido_ps
{

    private $m_submenu      = NULL;
    public $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_vlrVisita    = NULL;
    private $m_vlrDesconto  = NULL;
    private $m_situacoesAtendimento  = NULL;
    private $m_vendedorSelected  = NULL;
    public $smarty          = NULL;
    public $m_letra_peca    = NULL;
    public $m_letra_servico = NULL;
    public $m_param = NULL;
    private $m_cliente      = NULL;
    private $m_origem       = NULL;
    private $m_metodo       = NULL;
    private $m_motivoSelecionados = NULL;
    private $m_obsPerda     = NULL;
    private $m_condPagSelected = NULL;


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
        //$parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // ajax
        $this->ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        if ($parmPost['submenu'] !== '' and $parmPost['submenu'] !== null) {
            $this->m_submenu = $parmPost['submenu'];
        } elseif ($parmGet['submenu'] !== '' and $parmGet['submenu'] !== null) {
            $this->m_submenu = $parmGet['submenu'];
        } else {
            $this->m_submenu = '';
        }
        $this->m_pesq = $parmPost['pesq'];

        $this->m_vlrVisita   = $parmPost['valorVisita'];
        $this->m_vlrDesconto = $parmPost['valorDesconto'];

        $this->m_letra = $parmPost['letra'];
        $this->m_letra_peca    = $parmPost['letra_peca'];
        $this->m_letra_servico = $parmPost['letra_servico'];
        $this->m_situacoesAtendimento = $parmPost['situacoesAtendimento'];
        $this->m_vendedorSelected = $parmPost['vendedorSelected'];
        $this->m_condPagSelected = $parmPost['condPagamentoSelected'];
        $this->m_motivoSelecionados = isset($parmPost['motivoSelected']) ? $parmPost['motivoSelected'] : '';
        $this->m_obsPerda = isset($parmPost['obsPerda']) ? trim($parmPost['obsPerda']) : '';
        $this->m_param = (isset($parmPost['param']) && $parmPost['param'] !== '' && $parmPost['param'] !== null)
            ? $parmPost['param']
            : (isset($parmGet['param']) ? $parmGet['param'] : null);
        $this->m_cliente = isset($parmPost['pessoa']) ? $parmPost['pessoa'] : ''; // Add this line
        $this->m_origem = (isset($parmGet['origem']) ? $parmGet['origem'] : (isset($parmPost['origem']) ? $parmPost['origem'] : ''));
        $this->m_metodo = (isset($parmGet['metodo']) ? $parmGet['metodo'] : (isset($parmPost['metodo']) ? $parmPost['metodo'] : ''));


        $this->m_par = explode("|", $this->m_letra);
        $this->m_par_peca = explode("|", $this->m_letra_peca);
        $this->m_par_servico = explode("|", $this->m_letra_servico);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');


        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Pedido");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6]");
        $this->smarty->assign('disableSort', "[ 6 ]");
        $this->smarty->assign('numLine', "25");
        // Mantém a ordem vinda do backend (select_pedido_letra: ORDER BY A.EMISSAO DESC)
        // em vez do default do DataTable (1ª coluna asc = "Ped."/ID).
        $this->smarty->assign('orderDefault', '[]');

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : (isset($parmGet['id']) ? $parmGet['id'] : ''));
        $this->setPedido(isset($parmPost['atendimento']) ? $parmPost['atendimento'] : '');
        $this->setCliente(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setContato(isset($parmPost['contato']) ? $parmPost['contato'] : '');
        $this->setEmissao(isset($parmPost['emissao']) ? $parmPost['emissao'] : '');
        $this->setUsrAbertura(isset($parmPost['usrAbertura']) ? $parmPost['usrAbertura'] : '');
        $this->setPrioridade(isset($parmPost['prioridade']) ? $parmPost['prioridade'] : '');
        $this->setPrazoEntrega(isset($parmPost['prazoEntrega']) ? $parmPost['prazoEntrega'] : '');
        $this->setDescEquipamento(isset($parmPost['descEquipamento']) ? $parmPost['descEquipamento'] : '');
        $this->setKmEntrada(isset($parmPost['kmEntrada']) ? $parmPost['kmEntrada'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setSolucao(isset($parmPost['solucao']) ? $parmPost['solucao'] : '');
        $this->setValorProduto(isset($parmPost['valorPecas']) ? $parmPost['valorPecas'] : 0);
        $this->setValorServicos(isset($parmPost['valorServicos']) ? $parmPost['valorServicos'] : 0);
        $this->setValorFrete(isset($parmPost['valorFrete']) ? $parmPost['valorFrete'] : 0);
        $this->setValorDespAcessorias(isset($parmPost['valorDespAcessorias']) ? $parmPost['valorDespAcessorias'] : 0);
        $this->setValorDesconto(isset($parmPost['valorDesconto']) ? $parmPost['valorDesconto'] : 0);
        $this->setValorTotal(isset($parmPost['valorTotal']) ? $parmPost['valorTotal'] : 0);
        $this->setTipoCobranca(isset($parmPost['tipoCobranca']) ? $parmPost['tipoCobranca'] : '');
        $this->setCondPgto(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        $this->setConta(isset($parmPost['conta']) ? $parmPost['conta'] : '');
        $this->setGenero(isset($parmPost['genero']) ? $parmPost['genero'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : $this->m_empresacentrocusto);
        $this->setCentroCustoEntrega(isset($parmPost['centroCustoEntrega']) ? $parmPost['centroCustoEntrega'] : $this->m_empresacentrocusto);
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
        $this->setSerie(isset($parmPost['tipoDocFiscal']) ? $parmPost['tipoDocFiscal'] : NULL);
        $this->setObra(isset($parmPost['obra']) ? $parmPost['obra'] : NULL);
        $this->setResponsavelTecnico(isset($parmPost['responsavel_tecnico']) ? $parmPost['responsavel_tecnico'] : NULL);
        $this->setEnderecoEntrega(isset($parmPost['endereco_entrega']) ? $parmPost['endereco_entrega'] : NULL);
        $this->setUsrAbertura(isset($parmPost['usrAbertura']) ? $parmPost['usrAbertura'] : '');

        //=========================PECAS==================================
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');
        $this->setIdPedidoItem(isset($parmPost['idAtendimentoPecas']) ? $parmPost['idAtendimentoPecas'] : '');
        $this->setCodProduto(isset($parmPost['codProduto']) ? $parmPost['codProduto'] : '');
        $this->setCodProdutoNota(isset($parmPost['codProdutoNota']) ? $parmPost['codProdutoNota'] : '');
        $this->setQuantidadeProduto(isset($parmPost['quantidadePecas']) ? $parmPost['quantidadePecas'] : '');
        $this->setUnidadeProduto(isset($parmPost['uniProduto']) ? $parmPost['uniProduto'] : '');
        $this->setVlrUnitarioProduto(isset($parmPost['vlrUnitarioPecas']) ? $parmPost['vlrUnitarioPecas'] : '');
        $this->setDescricaoProduto(isset($parmPost['descProduto']) ? $parmPost['descProduto'] : '');
        $this->setVlrCustoProduto(isset($parmPost['vlrCustoPecas']) ? $parmPost['vlrCustoPecas'] : '');
        $this->setDescontoProduto(isset($parmPost['vlrDescontoPecas']) ? $parmPost['vlrDescontoPecas'] : '');
        $this->setPercDescontoProduto(isset($parmPost['percDescontoPecas']) ? $parmPost['percDescontoPecas'] : '');
        $this->setAcrescimoProduto(isset($parmPost['acrescimoPecas']) ? $parmPost['acrescimoPecas'] : '');
        $this->setTotalProduto(isset($parmPost['totalPecas']) ? $parmPost['totalPecas'] : '');
        $this->setDesconto(isset($parmPost['valorDesconto']) ? $parmPost['valorDesconto'] : 0);

        $this->setCodFabricante(isset($parmPost['codFabricante']) ? $parmPost['codFabricante'] : '');
        $this->setNumeroOc(isset($parmPost['numeroOcPecas']) ? $parmPost['numeroOcPecas'] : '');
        $this->setNItemPed(isset($parmPost['nItemPedPecas']) ? $parmPost['nItemPedPecas'] : '');
        $this->setDataEntregaPeca(isset($parmPost['dataEntregaPeca']) ? $parmPost['dataEntregaPeca'] : '');

        //==========================MARKUP=======================
        $this->setMarkupProduto(isset($parmPost['markupProduto']) ? $parmPost['markupProduto'] : '');
        $this->setMarkup(isset($parmPost['markup']) ? $parmPost['markup'] : '');

        //==========================SERVICOS=======================
        $this->setIdServico(isset($parmPost['idServicos']) ? $parmPost['idServicos'] : '');
        $this->setIdPedidoServico(isset($parmPost['idAtendimentoServicos']) ? $parmPost['idAtendimentoServicos'] : '');
        $this->setIdUser(isset($parmPost['idUser']) ? $parmPost['idUser'] : '');
        $this->setDataServico(isset($parmPost['dataServico']) ? $parmPost['dataServico'] : '');
        $this->setHoraIniServico(isset($parmPost['horaIni']) ? $parmPost['horaIni'] : '');
        $this->setHoraFimServico(isset($parmPost['horaFim']) ? $parmPost['horaFim'] : '');
        $this->setQuantidadeServico(isset($parmPost['quantidadeServico']) ? $parmPost['quantidadeServico'] : 0);
        $this->setUnidadeServico(isset($parmPost['unidadeServico']) ? $parmPost['unidadeServico'] : '');
        $this->setVlrUnitarioServico(isset($parmPost['vlrUnitarioServico']) ? $parmPost['vlrUnitarioServico'] : '');
        $this->setHoraTotalServico(isset($parmPost['horaTotalServico']) ? $parmPost['horaTotalServico'] : '');
        $this->setCustoUser(isset($parmPost['custoUser']) ? $parmPost['custoUser'] : '');
        $this->setDescricaoServico(isset($parmPost['descricaoServico']) ? $parmPost['descricaoServico'] : '');
        $this->setTotalServico(isset($parmPost['totalServico']) ? $parmPost['totalServico'] : 0);
        // observacao do servico/item no pedido, salvo em fat_pedido_servico.
        $this->setObsItemServico(isset($parmPost['obsItemServico']) ? $parmPost['obsItemServico'] : '');

        //==========================OS=======================
        $this->setOs(isset($parmPost['os']) ? $parmPost['os'] : '');
        $this->setCatEquipamentoId(isset($parmPost['catEquipamentoId']) ? $parmPost['catEquipamentoId'] : '');
        $this->setDescEquipamento(isset($parmPost['descEquipamento']) ? $parmPost['descEquipamento'] : '');
        $this->setDataAbertura(isset($parmPost['dataAbertura']) ? $parmPost['dataAbertura'] : '');
        $this->setDataFechamentoEnd(isset($parmPost['dataFechamentoEnd']) ? $parmPost['dataFechamentoEnd'] : '');
        $this->setPrazoEntregaOs(isset($parmPost['prazoEntregaOs']) ? $parmPost['prazoEntregaOs'] : '');
        $this->setObsOs(isset($parmPost['obsOs']) ? $parmPost['obsOs'] : '');
        // observacao na Os.
        $this->setObsServicos(isset($parmPost['obsServicos']) ? $parmPost['obsServicos'] : '');
    }

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle()
    {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $this->desenhaCadastroPedidoPs();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $this->buscaPedido();
                    $testeSit = $this->getSituacao();
                    if ($this->getId() > 0) {
                        $this->desenhaCadastroPedidoPs();
                    } else {
                        $this->mostraPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Pedido não pode ser alterado.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    }
                }
                break;
            case 'inclui': // CONCLUIR
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $idInsert = $this->incluiPedido();
                    $result = $this->getValorTotal();
                    $this->updateField("TOTAL", $result, "FAT_PEDIDO");
                    if ($idInsert > 0) {
                        $this->mostraPedidoPs('');
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
                        $this->mostraPedidoPs('');
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
                    $idPed = $this->getId();
                    if ($idPed === '' || $idPed === null || (int) $idPed <= 0) {
                        $this->mostraPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Identificador do pedido inválido.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    $atendimentoDb = $this->select_pedido_id();
                    $situacaoDb = isset($atendimentoDb[0]['SITUACAO']) ? $atendimentoDb[0]['SITUACAO'] : null;
                    $this->setUsrAprovacao($atendimentoDb[0]['USRAPROVACAO'] ?? null);
                    $situacaoNova = $this->getSituacao();
                    $msgAlertaEncomenda = '';
                    $estoqueValidado = isset($_POST['estoqueValidado']) && (string) $_POST['estoqueValidado'] === '1';

                    if ($this->getSituacao() === null) {
                        $this->mostraPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Pedido não localizado.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    // Cancelado (8) -> Cotação (5): reativação sem estorno de estoque / financeiro
                    if ((int) $situacaoDb === 8 && (int) $situacaoNova === 5) {
                        $this->updateField('SITUACAO', 5, 'FAT_PEDIDO');
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Cotação',
                                text: 'Pedido reativado para cotação.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    // Baixado (9) -> Emitir NF (3): apenas fase; baixa de estoque mantém-se
                    // Com NF já quitada no financeiro, não permite voltar para Emitir NF
                    if ((int) $situacaoDb === 9 && (int) $situacaoNova === 3) {
                        if (!$this->verificaFinanceiroNota((int) $this->getId())) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Alteração não permitida',
                                    text: 'Não é possível voltar para Emitir NF enquanto existir parcela baixada no financeiro.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }
                        if ($this->pedidoPossuiNotaComFinanceiroBaixado((int) $this->getId())) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Alteração não permitida',
                                    text: 'Este pedido está baixado e possui nota com financeiro quitado. Não é possível voltar para Emitir NF.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }
                        $this->updateField('SITUACAO', 3, 'FAT_PEDIDO');
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Emitir NF',
                                text: 'Situação alterada para Emitir NF.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    // Emitir NF (3) -> Pedido (6): apenas fase; sem nova reserva
                    if ((int) $situacaoDb === 3 && (int) $situacaoNova === 6) {
                        if (!$this->verificaFinanceiroNota((int) $this->getId())) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Alteração não permitida',
                                    text: 'Não é possível voltar para Pedido enquanto existir parcela baixada no financeiro.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }
                        $this->updateField('SITUACAO', 6, 'FAT_PEDIDO');
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Pedido',
                                text: 'Situação alterada para Pedido.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    if ((int) $situacaoNova === 8) {
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'info',
                                title: 'Cancelado',
                                text: 'Este pedido está cancelado e não pode ser alterado.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    // Emitir NF (3) -> Cotação (5): exige ausência de NF/financeiro baixado
                    if ((int) $situacaoDb === 3 && (int) $situacaoNova === 5) {
                        if (!$this->verificaFinanceiroNota((int) $this->getId())) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Alteração não permitida',
                                    text: 'Existe nota fiscal ou financeiro baixado vinculado a este pedido. Não é possível voltar para cotação.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }
                        if (c_produto_estoque::pedidoPossuiEstoqueBaixadoPorPedido($this->m_empresacentrocusto, (int) $this->getId())) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Estorno não permitido',
                                    text: 'Já houve baixa de estoque pelo financeiro ou NF. Não é possível voltar para cotação.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }
                        $paramEst = new c_banco();
                        $paramEst->setTab("EST_PARAMETRO");
                        $controlaEstorno = $paramEst->getField("CONTROLAESTOQUE", "FILIAL=" . $this->m_empresacentrocusto);
                        $paramEst->close_connection();
                        if ($controlaEstorno === 'S') {
                            $cceEstorno = (int) ($this->getCentroCustoEntrega() ?: $this->m_empresacentrocusto);
                            c_produto_estoque::liberaEstoquePedidoCancelamento($cceEstorno, (int) $this->getId());
                        }
                        $this->updateField('SITUACAO', 5, 'FAT_PEDIDO');
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Cotação',
                                text: 'Pedido voltou para cotação.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    // Encomenda (13) -> Cotação (5): libera reserva parcial; bloqueia se NF/financeiro baixado
                    if ((int) $situacaoDb === 13 && (int) $situacaoNova === 5) {
                        if (!$this->verificaFinanceiroNota((int) $this->getId())) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Alteração não permitida',
                                    text: 'Existe nota fiscal ou financeiro baixado vinculado a este pedido. Não é possível voltar para cotação.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }
                        if (c_produto_estoque::pedidoPossuiEstoqueBaixadoPorPedido($this->m_empresacentrocusto, (int) $this->getId())) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Estorno não permitido',
                                    text: 'Já houve baixa de estoque pelo financeiro ou NF. Não é possível voltar para cotação.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }
                        $paramEstEnc = new c_banco();
                        $paramEstEnc->setTab("EST_PARAMETRO");
                        $controlaEstornoEnc = $paramEstEnc->getField("CONTROLAESTOQUE", "FILIAL=" . $this->m_empresacentrocusto);
                        $paramEstEnc->close_connection();
                        if ($controlaEstornoEnc === 'S') {
                            $this->buscaPedido();
                            $cceEnc = (int) ($this->getCentroCustoEntrega() ?: $this->m_empresacentrocusto);
                            c_produto_estoque::liberaEstoquePedidoCancelamento($cceEnc, (int) $this->getId());
                            $bancoQa = new c_banco();
                            $bancoQa->exec_sql('UPDATE FAT_PEDIDO_ITEM SET QTATENDIDA = 0 WHERE ID = ' . (int) $this->getId());
                            $bancoQa->close_connection();
                        }
                        $this->updateField('SITUACAO', 5, 'FAT_PEDIDO');
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Cotação',
                                text: 'Encomenda voltou para cotação.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    // Estorno: estava confirmado (6) no banco e volta para cotação (5) — libera reserva (status 0 no estoque)
                    if ((int) $situacaoDb === 6 && (int) $situacaoNova === 5) {
                        if (c_produto_estoque::pedidoPossuiEstoqueBaixadoPorPedido($this->m_empresacentrocusto, (int) $this->getId())) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Estorno não permitido',
                                    text: 'Já houve baixa de estoque pelo financeiro ou NF. Não é possível voltar para cotação.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }
                        $paramEst = new c_banco();
                        $paramEst->setTab("EST_PARAMETRO");
                        $controlaEstorno = $paramEst->getField("CONTROLAESTOQUE", "FILIAL=" . $this->m_empresacentrocusto);
                        $paramEst->close_connection();
                        if ($controlaEstorno === 'S') {
                            $this->buscaPedido();
                            $cceEst = (int) ($this->getCentroCustoEntrega() ?: $this->m_empresacentrocusto);
                            c_produto_estoque::liberaEstoquePedidoCancelamento($cceEst, (int) $this->getId());
                            $bancoQaEst = new c_banco();
                            $bancoQaEst->exec_sql('UPDATE FAT_PEDIDO_ITEM SET QTATENDIDA = 0 WHERE ID = ' . (int) $this->getId());
                            $bancoQaEst->close_connection();
                        }
                        $this->updateField('SITUACAO', 5, 'FAT_PEDIDO');
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Estorno',
                                text: 'Pedido voltou para cotação.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    // Pedido (6) -> Pedido (6): permite alterar dados administrativos (frete/obs/vendedor/cond pgto/etc)
                    // mantendo bloqueio de itens (já controlado pelo frontend e rotas AJAX específicas).
                    // Também evita refazer reserva de estoque desnecessariamente.
                    if ((int) $situacaoDb === 6 && (int) $situacaoNova === 6) {
                        $res = $this->alteraPedido();
                        if (!is_string($res)) {
                            $totalPedidoCalc = $this->calculaImpostos();
                            if ($totalPedidoCalc !== null) {
                                $this->setValorTotal(round((float) $totalPedidoCalc, 2, PHP_ROUND_HALF_EVEN), true);
                            }
                            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");
                        }
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        if (!is_string($res)) {
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro Salvo!',
                                    text: 'N°: " . $this->getId() . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: '" . addslashes($res) . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        }
                        break;
                    }

                    // Encomenda (13) -> Pedido (6): exige validação de estoque nas ferramentas
                    if ((int) $situacaoDb === 13 && (int) $situacaoNova === 6 && !$estoqueValidado) {
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Validação necessária',
                                text: 'Use Ferramentas → Validar Estoque antes de confirmar como pedido.',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    if (
                        ((int) $situacaoDb === 5 || (int) $situacaoDb === 13)
                        && (int) $situacaoNova === 6
                    ) {
                        $itensPed = $this->select_pedido_item_id();
                        $servsPed = $this->select_servicos_atendimento();
                        $nIt = is_array($itensPed) ? count($itensPed) : 0;
                        $nSv = is_array($servsPed) ? count($servsPed) : 0;
                        if ($nIt === 0 && $nSv === 0) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pedido',
                                    text: 'Inclua ao menos um produto ou um serviço antes de confirmar como pedido.',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }

                        $totalPedidoCalc = $this->calculaImpostos();
                        if ($totalPedidoCalc !== null) {
                            $this->setValorTotal(round((float) $totalPedidoCalc, 2, PHP_ROUND_HALF_EVEN), true);
                        }

                        $fatParamsDesconto = $this->getFatParametrosFilial();
                        $perDesconto = $this->calcularPercentualDescontoPedido();
                        $descontoMaximo = (float) ($fatParamsDesconto['descontoMaximo'] ?? 0);
                        $aprovacaoDesconto = strtoupper((string) ($fatParamsDesconto['aprovacao'] ?? 'N'));
                        $excedeuDesconto = $descontoMaximo > 0 && $perDesconto > $descontoMaximo;

                        if ($excedeuDesconto) {
                            $perFmt = number_format($perDesconto, 2, ',', '.');
                            $maxFmt = number_format($descontoMaximo, 2, ',', '.');
                            $msgDescontoBase = 'Desconto máximo permitido: ' . $maxFmt
                                . '%. Desconto aplicado: ' . $perFmt . '%. ';

                            if (!in_array($aprovacaoDesconto, ['S', 'O'], true)) {
                                $this->setSituacao(5);
                                $this->buscaPedido();
                                $this->desenhaCadastroPedidoPs('');
                                $msgDescontoJs = addslashes($msgDescontoBase . 'Não é possível confirmar o pedido.');
                                echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                                echo "<script>
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Desconto não permitido',
                                        text: '" . $msgDescontoJs . "',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
                                break;
                            }

                            if (!$this->possuiUsrAprovacaoValido()) {
                            $toolsPs = new c_pedido_ps_tools();
                            $resolucaoEstoque = $toolsPs->pedidoPsResolverConfirmacaoEstoque((int) $this->getId());
                            $destinoAprov = (int) ($resolucaoEstoque['destino'] ?? 6);
                            if ($destinoAprov === 0) {
                                $this->setSituacao(5);
                                $this->buscaPedido();
                                $this->desenhaCadastroPedidoPs('');
                                $msgEstJs = addslashes(
                                    str_replace(["\r\n", "\r", "\n"], ' ', strip_tags((string) ($resolucaoEstoque['msg'] ?? '')))
                                );
                                echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                                echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Estoque insuficiente',
                                        text: '" . $msgEstJs . "',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
                                break;
                            }
                            $this->setSituacao(5);
                            $this->alteraPedido();
                            $totalPedidoCalc = $this->calculaImpostos();
                            if ($totalPedidoCalc !== null) {
                                $this->setValorTotal(round((float) $totalPedidoCalc, 2, PHP_ROUND_HALF_EVEN), true);
                            }
                            $this->updateField('TOTAL', $this->getValorTotal(), 'FAT_PEDIDO');
                            $this->alteraSituacaoAprovacaoPedido(10);
                            $this->buscaPedido();
                            $msgAprovacao = 'Pedido aguardando aprovação gerencial (desconto acima do limite).';
                            if ($destinoAprov === 13) {
                                $msgAprovacao .= '<br>Há itens sem saldo em estoque — após a liberação, seguirá como encomenda.';
                            }
                            $this->desenhaCadastroPedidoPs($msgAprovacao);
                            break;
                            }
                        }

                        $objContaBloq = new c_conta();
                        if (strtoupper((string) $objContaBloq->contaBloqueada((int) $this->getCliente())) === 'S') {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
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

                        $valorPedidoLimite = doubleval($this->getValorTotal('B'));
                        if ($valorPedidoLimite <= 0) {
                            $valorPedidoLimite = doubleval($this->select_totalPedido());
                        }
                        $checkLimite = c_lancamento::validaLimiteCreditoPedido(
                            (int) $this->getCliente(),
                            (int) $this->getId(),
                            $valorPedidoLimite,
                            'confirmar'
                        );
                        if (!$checkLimite['ok']) {
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs('');
                            $msgLimiteJs = addslashes(
                                str_replace(["\r\n", "\r", "\n"], '<br>', $checkLimite['mensagem'])
                            );
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Limite de crédito',
                                    html: '" . $msgLimiteJs . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            break;
                        }

                        if ($estoqueValidado) {
                            $cceVal = (int) ($this->getCentroCustoEntrega() ?: $this->m_empresacentrocusto);
                            $resultValEnc = $this->validarEncomendaPedido((int) $this->getId(), $cceVal);
                            if (empty($resultValEnc['estoqueOk'])) {
                                $this->buscaPedido();
                                $this->desenhaCadastroPedidoPs('');
                                $msgValEncJs = addslashes(
                                    str_replace(["\r\n", "\r", "\n"], '<br>', $resultValEnc['mensagem'] ?? 'Estoque insuficiente.')
                                );
                                echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                                echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Estoque insuficiente',
                                        html: '" . $msgValEncJs . "',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
                                break;
                            }
                            $this->setSituacao(6);
                            $situacaoNova = 6;
                        } elseif ((int) $situacaoDb === 5) {
                            $toolsPs = new c_pedido_ps_tools();
                            $resolucaoEstoque = $toolsPs->pedidoPsResolverConfirmacaoEstoque((int) $this->getId());
                            $destinoEstoque = (int) ($resolucaoEstoque['destino'] ?? 6);
                            if ($destinoEstoque === 0) {
                                $this->buscaPedido();
                                $this->desenhaCadastroPedidoPs('');
                                $msgEstJs = addslashes(
                                    str_replace(["\r\n", "\r", "\n"], '<br>', strip_tags($resolucaoEstoque['msg'] ?? 'Estoque insuficiente.'))
                                );
                                echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                                echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Estoque insuficiente',
                                        html: '" . $msgEstJs . "',
                                        confirmButtonText: 'OK'
                                    });
                                </script>";
                                break;
                            }
                            if ($destinoEstoque === 13) {
                                $this->setSituacao(13);
                                $situacaoNova = 13;
                                $msgAlertaEncomenda = addslashes(
                                    'Pedido em <b>encomenda</b> — aguardando entrada de estoque.<br><br>'
                                    . str_replace(["\r\n", "\r", "\n"], '<br>', $resolucaoEstoque['msg'] ?? '')
                                );
                            }
                        }
                    }

                    // Cotação (5) -> Cotação (5): "Confirmar" grava cabeçalho (frete/despesas/etc.) e precisa recalcular TOTAL
                    if ((int) $situacaoNova === 5) {
                        $this->alteraPedido();
                        $totalPedidoCalc = $this->calculaImpostos();
                        if ($totalPedidoCalc !== null) {
                            $this->setValorTotal(round((float) $totalPedidoCalc, 2, PHP_ROUND_HALF_EVEN), true);
                        }
                        $result = $this->getValorTotal();
                        $this->updateField("TOTAL", $result, "FAT_PEDIDO");
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        break;
                    }

                    $finalizacao = $this->pedidoPsFinalizarPosConfirmacao((int) $situacaoDb, (int) $situacaoNova);
                    if (!$finalizacao['ok']) {
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: '" . addslashes($finalizacao['erro']) . "',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }

                    if ($finalizacao['gerouFinanceiro']) {
                        break;
                    }

                    if (!is_string($finalizacao['res'])) {
                        $this->mostraPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        if ($msgAlertaEncomenda !== '') {
                            echo "<script>
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Encomenda',
                                    html: '" . $msgAlertaEncomenda . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro Salvo!',
                                    text: 'N°: " . $this->getId() . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                        }
                    } else {
                        $this->mostraPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: '" . addslashes($finalizacao['res']) . "',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    }
                }
                break;
            case 'digita': //VOLTAR
                if ($this->verificaDireitoUsuario('PedVendas', 'C')) {
                    $this->mostraPedidoPs('');
                }
                break;
            case 'cancela': // CANCELA
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    // 8 - CAT_SITUACAO  = CANCELADO
                    if ($this->verificaFinanceiroNota($this->getId())) {
                        $transaction = new c_banco();
                        $transaction->inicioTransacao($transaction->id_connection);
                        try {
                            $this->pedidoPsLiberarEstoqueCancelamento($transaction->id_connection);
                            c_lancamento::cancelaLancamentosAbertosPedido(
                                $this->getId(),
                                $this->m_userid,
                                $transaction->id_connection
                            );
                            $this->zeraComissaoItens($this->getId(), $transaction->id_connection);
                            $this->updateField("SITUACAO", 8, "FAT_PEDIDO", $transaction->id_connection);
                            $transaction->commit($transaction->id_connection);
                        } catch (Exception $e) {
                            $transaction->rollback($transaction->id_connection);
                            echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: '" . addslashes($e->getMessage()) . "',
                                    confirmButtonText: 'OK'
                                });
                            </script>";
                            $this->mostraPedidoPs('');
                            break;
                        }
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Pedido Cancelado!',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        $this->mostraPedidoPs('');
                    } else {
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Pedido não pode ser cancelado, pois já foi emitida uma nota fiscal ou financeiro baixado.',
                                });
                            </script>";
                        $this->mostraPedidoPs('');
                    }
                }
                break;
            case 'recalcularDesconto':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $idAtendimento = $this->getId();
                    if ($this->getSituacao() === '6' || $this->getSituacao() === '8') {
                        $this->buscaPedido();
                        $this->desenhaCadastroPedidoPs('Operação não permitida nesta situação.');
                        break;
                    }
                    $novoDescontoAtendimento = $this->getValorDesconto();
                    $objatendimentoTools = new c_pedido_ps_tools();
                    $msg = $objatendimentoTools->recalcularDescontoPecas($idAtendimento, $novoDescontoAtendimento);
                    $this->desenhaCadastroPedidoPs($msg);
                }
                break;
            case 'gerarOs': // Gerar OS
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $this->updateField("OS", $this->getId(), "FAT_PEDIDO");
                    $this->setOs($this->getId());
                    $this->desenhaCadastroPedidoPs('');
                    echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'OS Gerada!',
                            confirmButtonText: 'OK'
                        });
                    </script>";
                }
                break;
            case 'estornarOs': // Estornar OS
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $this->updateField("OS", 0, "FAT_PEDIDO");
                    $this->estornaDadosOsPedido();
                    $this->buscaPedido();
                    $this->desenhaCadastroPedidoPs('');
                    echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'OS Estornada!',
                            confirmButtonText: 'OK'
                        });
                    </script>";
                }
                break;
            case 'duplicaPedido': // Duplica Pedido
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $idAntigo = $this->getId();
                    $idGerado = $this->duplicaPedido();
                    $this->setId($idGerado);
                    $this->updateField('PEDIDO', $idGerado, "FAT_PEDIDO");
                    $this->duplicaPedidoItem($idGerado, $idAntigo);
                    $this->duplicaPedidoServicos($idGerado, $idAntigo);
                    $this->buscaPedido();
                    $this->m_submenu = 'alterar';
                    $this->desenhaCadastroPedidoPs('');
                    echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido Duplicado!',
                            confirmButtonText: 'OK'
                        });
                    </script>";
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
                    $this->desenhaCadastroPedidoPs();
                }
                break;

            case 'prosseguirComDesconto':
                $situacao = $this->prosseguirComDesconto();
                $response = array('situacao' => $situacao);
                echo json_encode($response);
                break;
            case 'cadastrarCarrinho':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {

                    //se o json que retornar for pelo pedidoExiste, entao
                    // a primeira posicao e o numero do pedido
                    $arrayCarrinho = json_decode($this->m_letra, true);

                    //set e inclusao do pedido
                    $this->setCliente($arrayCarrinho[0]["pessoaId"]);
                    $this->setContato('');
                    $this->setUsrAbertura($this->m_userid);
                    $this->setValorProduto('');
                    $this->setValorServicos('');
                    $this->setValorFrete('');
                    $this->setValorDespAcessorias('');
                    $this->setValorDesconto('');
                    $this->setEmissao(date('y-m-d'));
                    $this->setPrazoEntrega('');
                    $this->setObs('');
                    $this->setCondPgto('1');
                    $this->setSituacao('5');
                    $this->setEspecie('');
                    $this->setIdNatop('1');
                    $idInsertPed = $this->incluiPedido();

                    //função para atualizar ao pedido com o id
                    $this->setId($idInsertPed);
                    $this->updateField("PEDIDO", $idInsertPed, "fat_pedido");


                    //sets e inclusao dos itens
                    if (is_int($idInsertPed)) {
                        //remove o primeiro regitro que é o cliente ou o pedido
                        array_shift($arrayCarrinho);

                        for ($i = 0; $i < count($arrayCarrinho); $i++) {

                            $sql  = "SELECT DISTINCT * FROM est_produto WHERE (codigo = " . $arrayCarrinho[$i]["codigo"] . ") ";
                            //echo strtoupper($sql)."<BR>";
                            $banco = new c_banco();
                            $banco->exec_sql($sql);
                            $banco->close_connection();
                            $banco->resultado;

                            $this->setIdPedidoItem($idInsertPed);
                            $this->setCodProduto($banco->resultado[0]['CODIGO']);
                            $this->setCodFabricante($banco->resultado[0]['CODFABRICANTE']);
                            $this->setNrItem($i + 1);
                            $this->setQuantidadeProduto($arrayCarrinho[$i]["quantidade"]);
                            $valorVenda = number_format($banco->resultado[0]['VENDA'], 2, ',', '.');
                            $this->setVlrUnitarioProduto($valorVenda);
                            $this->setDescricaoProduto($banco->resultado[0]['DESCRICAO']);
                            $this->setDescontoProduto('');
                            $this->setPercDescontoProduto('');
                            $this->setCodProdutoNota($banco->resultado[0]['CODFABRICANTE']);

                            //verifica se existe quantidade e preco de venda para formar o valor total do item
                            if (($this->getQuantidadeProduto() !== '' and $this->getQuantidadeProduto() !== '0.00') and $banco->resultado[0]["VENDA"] !== "0.0000") {
                                $quantidadeProd = (float) $arrayCarrinho[$i]["quantidade"];
                                $valorVenda = (float) $banco->resultado[0]["VENDA"];
                                $totalItens = ($quantidadeProd * $valorVenda);
                                $this->setTotalProduto($totalItens);
                            } else {
                                $this->setTotalProduto('');
                            }

                            $resultInsertItem = $this->incluiProduto();
                        }
                    }

                    //if there is an error when entering, delete the order
                    if ($resultInsertItem) {
                        $sql  = "DELETE FROM fat_pedido WHERE (ID = " . $idInsertPed . ") ";
                        //echo strtoupper($sql)."<BR>";
                        $banco = new c_banco();
                        $banco->exec_sql($sql);

                        $sql  = "DELETE FROM fat_pedido_item WHERE (ID = " . $idInsertPed . ") ";
                        $banco = new c_banco();
                        $banco->exec_sql($sql);

                        $banco->close_connection();

                        $this->m_letra = '';
                        $this->m_par = '';
                        $this->mostraPedidoPs('');
                        echo "<script type='text/javascript' src='" . ADMhttpCliente . "/../sweetalert2/dist/sweetalert2.all.min.js'></script>";
                        echo '<script> swal.fire({
                            title: "Atenção!",
                            text: "Pedido não inserido, entre em contato com o suporte!",
                            icon: "error",
                            button: "OK",
                            dangerMode: true,
                          });</script>';
                    } else {
                        $this->setId($idInsertPed);
                        $this->calculaImpostos();
                        $this->m_submenu = 'alterar';
                        $this->m_letra = '';
                        $this->controle();
                    }
                }
                break;
            case 'cadastrarCarrinhoPedidoExiste':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {

                    $arrayCarrinho = json_decode($this->m_letra, true);
                    $idInsertPed = intval($arrayCarrinho[0]["pedidoId"]);

                    //remove o primeiro regitro que é o pedido
                    array_shift($arrayCarrinho);

                    //consulta maior numero dos itens no pedido 
                    $sql  = "SELECT MAX(NRITEM) AS ULTIMOITEM FROM FAT_PEDIDO_ITEM WHERE ID = " . $idInsertPed . ";";
                    //echo strtoupper($sql)."<BR>";
                    $banco = new c_banco();
                    $banco->exec_sql($sql);
                    $banco->close_connection();
                    $banco->resultado;
                    $numSeqItens = $banco->resultado[0]['ULTIMOITEM'] + 1;


                    for ($i = 0; $i < count($arrayCarrinho); $i++) {

                        $sql  = "SELECT DISTINCT * FROM est_produto WHERE (codigo = " . $arrayCarrinho[$i]["codigo"] . ") ";
                        //echo strtoupper($sql)."<BR>";
                        $banco = new c_banco();
                        $banco->exec_sql($sql);
                        $banco->close_connection();
                        $banco->resultado;

                        $this->setIdPedidoItem($idInsertPed);
                        $this->setCodProduto($banco->resultado[0]['CODIGO']);
                        $this->setCodFabricante($banco->resultado[0]['CODFABRICANTE']);
                        $this->setNrItem($numSeqItens);
                        $this->setQuantidadeProduto($arrayCarrinho[$i]["quantidade"]);
                        $valorVenda = number_format($banco->resultado[0]['VENDA'], 2, ',', '.');
                        $this->setVlrUnitarioProduto($valorVenda);
                        $this->setDescricaoProduto($banco->resultado[0]['DESCRICAO']);
                        $this->setDescontoProduto('');
                        $this->setPercDescontoProduto('');
                        $this->setCodProdutoNota($banco->resultado[0]['CODFABRICANTE']);

                        //verifica se existe quantidade e preco de venda para formar o valor total do item
                        if (($this->getQuantidadeProduto() !== '' and $this->getQuantidadeProduto() !== '0.00') and $banco->resultado[0]["VENDA"] !== "0.0000") {
                            $quantidadeProd = (float) $arrayCarrinho[$i]["quantidade"];
                            $valorVenda = (float) $banco->resultado[0]["VENDA"];
                            $totalItens = $quantidadeProd * $valorVenda;
                            $totalItensFormatado = number_format(($quantidadeProd * $valorVenda), 2, ',', '.');
                            $this->setTotalProduto($totalItensFormatado);
                        } else {
                            $this->setTotalProduto('');
                        }

                        $resultInsertItem = $this->incluiProduto();


                        //update total products
                        $sql  = "UPDATE FAT_PEDIDO SET TOTALPRODUTOS = TOTALPRODUTOS + " . $totalItens . " WHERE ID = " . $idInsertPed . ";";
                        //echo strtoupper($sql)."<BR>";
                        $banco = new c_banco();
                        $banco->exec_sql($sql);
                        $banco->close_connection();
                        $banco->resultado;

                        $numSeqItens++;
                    }

                    //if there is an error when entering, delete the order
                    $this->setId($idInsertPed);
                    $this->calculaImpostos();
                }
                break;
            case 'motivoGeral':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $pedido = $this->select_pedido_id();
                    if (empty($pedido) || (int) $pedido[0]['SITUACAO'] !== 5) {
                        $this->mostraPedidoPs('Venda perdida permitida apenas para pedidos em cotação!', 'alerta');
                        break;
                    }
                    $objLancamento = new c_lancamento();
                    $searchLanc = $objLancamento->select_lancamento_doc('PED', $this->getId());

                    if ($searchLanc == '' || $searchLanc == null) {
                        $this->atualizarMotivoItem($this->m_motivoSelecionados);
                        $this->atualizarObsPerda($this->m_obsPerda);
                        $this->atualizarFieldPedido(7);
                        $this->atualizarTotal($this->select_totalPedido());
                        $this->mostraPedidoPs('Venda perdida confirmada!', 'sucesso');
                    } else {
                        $this->mostraPedidoPs('Não foi possível atualizar pedido, existe financeiro cadastrado!', 'alerta');
                    }
                }
                break;
            case 'ajax_obra':
                $cliente_id = $_POST['cliente_id'];
                $obras = $this->comboObra($cliente_id);
                $responsaveis = $this->comboResponsavelTecnico();
                if (!is_array($obras)) {
                    $obras = [];
                }
                if (!is_array($responsaveis)) {
                    $responsaveis = [];
                }

                $response = array_merge([
                    'obras' => $obras,
                    'responsaveis' => $responsaveis,
                ], $this->saldoCreditoClienteParaJson($cliente_id, (int) $this->getId()));

                header('Content-Type: application/json');
                echo json_encode($response);
                break;
            case 'ajax_enderecos':
                $cliente_id = $_POST['cliente_id'];
                $enderecos = $this->buscarEnderecosCliente($cliente_id);
                $id_representante = $this->busca_representante_cliente($cliente_id);
                $response = array_merge([
                    'enderecos' => $enderecos,
                    'id_representante' => $id_representante,
                ], $this->saldoCreditoClienteParaJson($cliente_id, (int) $this->getId()));

                header('Content-Type: application/json');
                echo json_encode($response);
                break;
            case 'ajax_validar_encomenda':
                header('Content-Type: application/json; charset=utf-8');
                $idPedido = (int) $this->getId();
                if ($idPedido <= 0 && isset($_POST['id'])) {
                    $idPedido = (int) $_POST['id'];
                }
                if ($idPedido <= 0) {
                    echo json_encode([
                        'ok' => false,
                        'estoqueOk' => false,
                        'titulo' => 'Erro',
                        'mensagem' => 'Pedido inválido.',
                        'itens' => [],
                    ], JSON_UNESCAPED_UNICODE);
                    break;
                }
                $this->setId($idPedido);
                $this->buscaPedido();
                $cce = (int) ($this->getCentroCustoEntrega() ?: $this->m_empresacentrocusto);
                echo json_encode($this->validarEncomendaPedido($idPedido, $cce), JSON_UNESCAPED_UNICODE);
                break;
            case 'simulaImpostos':
                $dadosRelatorio = $this->getRelatorioImpostosPedido($this->getId());
                if ($dadosRelatorio['status'] == false) {
                    $this->smarty->assign('status_relatorio', $dadosRelatorio['status']);
                    $this->smarty->assign('msg_erro', $dadosRelatorio['erro']);
                    $this->smarty->display('relatorio_pedido_impostos.tpl');
                } else {
                    $this->smarty->assign('status_relatorio', $dadosRelatorio['status']);
                    $this->smarty->assign('pedido', $dadosRelatorio['pedido']);
                    $this->smarty->assign('itens', $dadosRelatorio['itens']);
                    $this->smarty->display('relatorio_pedido_impostos.tpl');
                }
                break;
            case 'abrirDashboardCrm':
                if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $idCliente = ($this->m_param ?? null);
                    if ($idCliente > 0) {
                        $idPedido = $this->selectIdUltimaCotacaoAbertaCliente($idCliente);
                        if ($idPedido > 0) {
                            $this->setId($idPedido);
                            $this->buscaPedido();
                            $this->desenhaCadastroPedidoPs();
                        } else {
                            $this->setCliente($idCliente);
                            $this->m_submenu = 'cadastrar';
                            $this->desenhaCadastroPedidoPs();
                        }
                    } else {
                        $this->mostraPedidoPs('');
                    }
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
                    $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];
                    $produto = new c_produto();
                    $produto->retornaInfoProduto(
                        $this->getCodProduto(),
                        $this->getCentroCusto(),
                        $this->getCliente(),
                        $parmPost['vlrUnitarioProduto'] ?? $this->getVlrUnitarioProduto(),
                        $parmPost['quantidadeProduto'] ?? $this->getQuantidadeProduto(),
                        $parmPost['vlrDescontoProduto'] ?? $this->getDescontoProduto()
                    );
                }
                break;
            case 'busca_servico':
                if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $termo = isset($_POST['termoPesquisaServico'])
                        ? trim((string) $_POST['termoPesquisaServico'])
                        : '';
                    $servico = new c_servico();
                    $servico->retornaHtmlServicos($termo);
                }
                break;
            default:
                $this->mostraPedidoPs('');
                break;
        }
    }


    function desenhaCadastroPedidoPs($mensagem = NULL, $tipoMsg = NULL)
    {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathCleave', ADMhttpBib . '/bib/cleave/dist');

        if ($this->m_submenu == 'atualizarInfo') {
            $this->smarty->assign('subMenu', 'alterar');
        } else {
            $this->smarty->assign('subMenu', $this->m_submenu);
        }

        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);

        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('tab', '');
        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente() != ''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
        endif;
        $saldoCreditoCliente = 0.0;
        $limiteCreditoCliente = 0.0;
        $saldoLimiteDisponivel = 0.0;
        $clienteBloqueado = false;
        if ($this->getCliente() != '') {
            $objContaCred = new c_conta();
            $saldoCreditoCliente = $objContaCred->selectSaldoCreditoCliente((int) $this->getCliente());
            $limiteJson = c_lancamento::limiteCreditoClienteParaJson((int) $this->getCliente(), (int) $this->getId());
            $limiteCreditoCliente = $limiteJson['limite_credito'];
            $saldoLimiteDisponivel = $limiteJson['saldo_limite_disponivel'];
            $clienteBloqueado = strtoupper((string) $objContaCred->contaBloqueada((int) $this->getCliente())) === 'S';
        }
        $this->smarty->assign('saldoCreditoCliente', $saldoCreditoCliente);
        $this->smarty->assign('saldoCreditoClienteFmt', number_format($saldoCreditoCliente, 2, ',', '.'));
        $this->smarty->assign('limiteCreditoCliente', $limiteCreditoCliente);
        $this->smarty->assign('limiteCreditoClienteFmt', number_format($limiteCreditoCliente, 2, ',', '.'));
        $this->smarty->assign('saldoLimiteDisponivel', $saldoLimiteDisponivel);
        $this->smarty->assign('saldoLimiteDisponivelFmt', number_format(max(0, $saldoLimiteDisponivel), 2, ',', '.'));
        $this->smarty->assign('clienteBloqueado', $clienteBloqueado);
        $this->smarty->assign('contato', $this->getContato());
        $this->smarty->assign('pedido', $this->getPedido());
        $sitTpl = $this->getSituacao();
        $sitTpl = ($sitTpl !== '' && $sitTpl !== null) ? (int) $sitTpl : 5;
        $this->smarty->assign('situacao', $sitTpl);
        $this->smarty->assign('origem', $this->m_origem);
        $fatParamsPs = $this->getFatParametrosFilial();
        $this->smarty->assign('encomendaAtiva', strtoupper((string) ($fatParamsPs['encomenda'] ?? 'N')) === 'S');

        $perDesconto = $this->calcularPercentualDescontoPedido();
        $descontoMaximo = (float) ($fatParamsPs['descontoMaximo'] ?? 0);
        $aprovacaoParam = strtoupper((string) ($fatParamsPs['aprovacao'] ?? 'N'));
        $excedeuDesconto = $descontoMaximo > 0 && $perDesconto > $descontoMaximo;
        $jaAprovadoGerencial = $this->possuiUsrAprovacaoValido();
        if (!$jaAprovadoGerencial && $this->getId() != '') {
            $rowPedAprov = $this->select_pedido_id();
            $this->setUsrAprovacao($rowPedAprov[0]['USRAPROVACAO'] ?? null);
            $jaAprovadoGerencial = $this->possuiUsrAprovacaoValido();
        }
        $this->smarty->assign('perDesconto', number_format($perDesconto, 2, ',', '.'));
        $this->smarty->assign('descontoMaximo', number_format($descontoMaximo, 2, ',', '.'));
        $this->smarty->assign('aprovacaoParam', $aprovacaoParam);
        $this->smarty->assign('validarDescontoGeral', ($excedeuDesconto && !$jaAprovadoGerencial) ? 'S' : 'N');

        $pedidoPsAprovacaoPrevEncomenda = false;
        if ((int) $this->getSituacao() === 10) {
            $toolsPsEnc = new c_pedido_ps_tools();
            $resEncAprov = $toolsPsEnc->pedidoPsResolverConfirmacaoEstoque((int) $this->getId());
            $pedidoPsAprovacaoPrevEncomenda = ((int) ($resEncAprov['destino'] ?? 6) === 13);
            if ($mensagem === null || $mensagem === '') {
                $mensagem = 'Pedido aguardando aprovação gerencial (desconto acima do limite).';
                if ($pedidoPsAprovacaoPrevEncomenda) {
                    $mensagem .= '<br>Há itens sem saldo em estoque — após a liberação, seguirá como encomenda.';
                }
            }
        }
        $this->smarty->assign('pedidoPsAprovacaoPrevEncomenda', $pedidoPsAprovacaoPrevEncomenda);
        $this->smarty->assign('mensagem', $mensagem);

        $serieDoc = $this->getSerie();
        if ($serieDoc !== '65' && $serieDoc !== '55') {
            $serieDoc = '';
        }
        $this->smarty->assign('tipoDocFiscal', $serieDoc);
        $this->smarty->assign('tipoDocFiscal_options', [
            '' => 'A definir',
            '65' => 'Cupom (NFC-e)',
            '55' => 'Nota fiscal (NF-e)',
        ]);

        if ($this->getEmissao('F') == '') {
            $this->smarty->assign('emissao', date("d/m/Y"));
        } else {
            $this->smarty->assign('emissao', $this->getEmissao('F'));
        }
        // CADASTRA PEDIDO ITEM 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_PECA"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_PECA"] == "true"):
            $ajax_request = 'true';
            $objPedidoPsTools = new c_pedido_ps_tools();
            // VERIFICA SE NAO TEM Nr Item
            if (empty($this->m_par_peca[12])) {
                if (empty($this->m_par_peca[0])) {
                    if ($this->getEmissao() == '') {
                        $this->setEmissao(date("d/m/Y"));
                    }
                    $this->setCentroCusto($this->m_empresacentrocusto);
                    $this->setEspecie("D");
                    $this->setIdNatop("1");
                    $idPedido = $this->incluiPedido();
                    if (intval($idPedido) > 0) {
                        $this->setId($idPedido);
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro ao incluir',
                                text: 'Ocorreu um erro ao cadastrar o pedido. Tente novamente.'
                            });
                        </script>";
                        return;
                    }
                    $this->updateField("PEDIDO", $this->getId(), "FAT_PEDIDO");
                    $this->smarty->assign('id', $this->getId());
                } else {
                    $idPedido = $this->getId();
                }
                $objPedidoPsTools->incluiPecasAtendimentoControle($this->m_letra_peca, $idPedido);
            } else {
                $objPedidoPsTools->alteraPecasAtendimentoControle($this->m_letra_peca);
                $idPedido = $this->getId();
            }
            $this->setId($idPedido);
            $result = $this->select_pedido_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");
            $this->setIdPedidoItem($idPedido);
            $lancPesq = $this->select_pedido_todos_itens_id();

            $this->smarty->assign('lancPesq', $lancPesq);
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;
        // EXCLUI ITEM
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_PECA"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_PECA"] == "true"):
            $ajax_request = 'true';

            $objPedidoPsTools = new c_pedido_ps_tools();
            $objPedidoPsTools->excluiPecasAtendimento($this->m_letra_peca);
            $result = $this->select_pedido_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");

            $idPedido = $this->getId();

            $this->setIdPedidoItem($idPedido);
            $lancPesq = $this->select_pedido_todos_itens_id();
            $this->smarty->assign('lancPesq', $lancPesq);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;

        // CADASTRA SERVIÇO 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_SERVICO"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_SERVICO"] == "true"):
            $ajax_request = 'true';

            $objatendimentoTools = new c_pedido_ps_tools();
            // VERIFICA SE NAO TEM ID SERVICO
            if (empty($this->m_par_servico[9])) {
                // VERIFICA SE NAO TEM ID ATENDIMENTO
                if (empty($this->m_par_servico[0])) {
                    $this->setCentroCusto($this->m_empresacentrocusto);
                    $this->setEspecie("D");
                    $this->setIdNatop("1");
                    $idPedido = $this->incluiPedido();
                    $this->setId($idPedido);
                    $this->updateField("PEDIDO", $this->getId(), "FAT_PEDIDO");
                    $this->smarty->assign('id', $this->getId());
                } else {
                    $idPedido = $this->getId();
                }
                $objatendimentoTools->incluiServicoAtendimentoControle($this->m_letra_servico, $idPedido);
            } else {
                $objatendimentoTools->alteraServicoAtendimentoControle($this->m_letra_servico);
            }
            //$this->setId(explode('|', $this->m_letra_servico)[0]);
            $result = $this->select_pedido_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;
        // EXCLUI SERVIÇO
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_SERVICO"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_SERVICO"] == "true"):
            $ajax_request = 'true';

            $objatendimentoTools = new c_pedido_ps_tools();
            $objatendimentoTools->excluiServicoAtendimento($this->m_letra_servico);
            $result = $this->select_pedido_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;

        $this->smarty->assign('prazoEntrega', $this->getPrazoEntrega('F'));
        $this->smarty->assign('condPgto', $this->getCondPgto());
        $this->smarty->assign('obs', $this->getObs());
        $this->smarty->assign('obsServicos', $this->getObsServicos());

        // pedido já existente selecionar obra ou alterar.
        $obra_ids = [];
        $obra_names = [];
        $responsavel_tecnico_ids = [];
        $responsavel_tecnico_names = [];

        if ($this->getCliente()) {
            $obras = $this->comboObra($this->getCliente());
            if (is_array($obras) && count($obras) > 0) {
                $obra_ids = array_column($obras, 'ID');
                $obra_names = array_column($obras, 'PROJETO');

                // Se tem obra selecionada, carrega responsáveis técnicos
                if ($this->getObra() && $this->getObra() != '') {
                    $responsaveis = $this->comboResponsavelTecnico();
                    if (is_array($responsaveis) && count($responsaveis) > 0) {
                        $responsavel_tecnico_ids = array_column($responsaveis, 'ID');
                        $responsavel_tecnico_names = array_column($responsaveis, 'NOME');
                    }
                }
            }
        }

        $this->smarty->assign([
            'obra_ids' => $obra_ids,
            'obra_names' => $obra_names,
            'obra_id' => $this->getObra(),
            'responsavel_tecnico_ids' => $responsavel_tecnico_ids,
            'responsavel_tecnico_names' => $responsavel_tecnico_names,
            'responsavel_tecnico_id' => $this->getResponsavelTecnico()
        ]);

        // Endereços de entrega
        $endereco_ids = [];
        $endereco_names = [];
        $endereco_entrega_selecionado = $this->getEnderecoEntrega();

        if ($this->getCliente()) {
            $enderecos = $this->buscarEnderecosCliente($this->getCliente());
            foreach ($enderecos as $endereco) {
                $endereco_ids[] = $endereco['ID'];
                $endereco_names[] = $endereco['ENDERECO_ENTREGA'];
            }
        }

        $this->smarty->assign([
            'endereco_ids' => $endereco_ids,
            'endereco_names' => $endereco_names,
            'endereco_entrega_id' => $endereco_entrega_selecionado
        ]);


        if ($this->getId() != ''):
            $this->setIdPedidoItem($this->getId());
            $lancPesq = $this->select_pedido_todos_itens_id();
            $this->smarty->assign('lancPesq', $lancPesq);

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

            $totais = $this->select_valores_pedido();

            $nrItens = $this->select_pedido_item_nrItem($this->getId());

            if ($totais[0]['TOTALPRODUTOS'] == 0 && $nrItens[0]['NRITEM'] > 0) {
                $this->setIdPedidoItem($this->getId());
                $totalProduto = $this->select_produto_total();
                $this->updateField("TOTALPRODUTOS", $totalProduto, "FAT_PEDIDO");
                $totais = $this->select_valores_pedido();
            }

            $vlrPecas = $totais[0]['TOTALPRODUTOS'];
            $vlrPecas = number_format($vlrPecas, 2, ',', '.');
            $this->smarty->assign('valorPecas', $vlrPecas);


            $vlrServicos = $totais[0]['VALORSERVICOS'];
            if ($vlrServicos == null) {
                $this->updateField("VALORSERVICOS", 0, "FAT_PEDIDO");
            }
            $vlrServicos = number_format($vlrServicos, 2, ',', '.');
            $this->smarty->assign('valorServicos', $vlrServicos);


            $vlrFrete =  $totais[0]['FRETE'];
            $vlrFrete = number_format($vlrFrete, 2, ',', '.');
            $this->smarty->assign('valorFrete', $vlrFrete);

            $vlrDespAcesorias =  $totais[0]['DESPACESSORIAS'];
            $vlrDespAcesorias = number_format($vlrDespAcesorias, 2, ',', '.');
            $this->smarty->assign('valorDespAcessorias', $vlrDespAcesorias);


            $vlrDesconto =  $totais[0]['DESCONTO'];
            $vlrDesconto = number_format($vlrDesconto, 2, ',', '.');
            $this->smarty->assign('valorDesconto', $vlrDesconto);


            $vlrTotal =  $totais[0]['TOTAL'];
            $vlrTotal = number_format($vlrTotal, 2, ',', '.');
            $this->smarty->assign('valorTotal', $vlrTotal);

        else: {
                $this->smarty->assign('totalatendimento', '0');
            }
        endif;

        // COMBOBOX ATENDENTE
        $consulta = new c_banco();
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
        $sql .= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' )";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        // Inicializa arrays sequenciais
        $usrAbertura_ids = array();
        $usrAbertura_names = array();

        // Primeira posição vazia
        $usrAbertura_ids[] = '';
        $usrAbertura_names[] = '';

        // Preenche arrays de forma sequencial, garantindo que IDs sejam strings
        for ($i = 0; $i < count($result); $i++) {
            // Converte o ID para string para garantir match correto
            $usrAbertura_ids[] = strval($result[$i]['USUARIO']);
            $usrAbertura_names[] = $result[$i]['NOME'];
        }

        $this->smarty->assign('usrAbertura_ids',   $usrAbertura_ids);
        $this->smarty->assign('usrAbertura_names', $usrAbertura_names);

        if ($this->getUsrAbertura() == '') {
            $this->setUsrAbertura($this->m_userid);
        }

        $usrAberturaValue = strval($this->getUsrAbertura());
        $this->smarty->assign('usrAbertura', $usrAberturaValue);

        // COMBOBOX COND PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $condPgto_ids[0] = 0;
        $condPgto_names[0] = 'Condição Pagamento';
        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i + 1] = $result[$i]['ID'];
            $condPgto_names[$i + 1] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPgto());
        $this->getOs() == '' ? $this->setOs('0') : $this->getOs();
        $this->smarty->assign('os', $this->getOs());

        if ($this->getOs() != '0') {

            $this->smarty->assign('tab', 'os');

            $this->smarty->assign('catEquipamentoId', $this->getCatEquipamentoId());
            $this->smarty->assign('descEquipamento', $this->getDescEquipamento());
            if ($this->getDataAbertura('F') == '') {
                $this->smarty->assign('dataAbertura', date("d/m/Y"));
            } else {
                $this->smarty->assign('dataAbertura', $this->getDataAbertura('F'));
            }
            $this->smarty->assign('dataFechamentoEnd', $this->getDataFechamentoEnd('F'));
            $this->smarty->assign('prazoEntregaOs', $this->getPrazoEntregaOs('F'));
            $this->smarty->assign('obsOs', $this->getObsOs());
            $this->smarty->assign('obsServicos', $this->getObsServicos());
        }

        $this->smarty->assign('centroCusto', $this->m_empresacentrocusto);
        $this->smarty->assign('centroCustoEntrega', $this->m_empresacentrocusto);

        // Busca parâmetro CASASDECIMAIS
        $parametros = new c_parametros();
        $parametros->setFilial($this->m_empresacentrocusto);
        $casasDecimais = $parametros->getCasasDecimais();
        $this->smarty->assign('casasDecimais', $casasDecimais);

        // Busca parâmetro CONTROLEVENDEDOR
        $controleVendedor = $parametros->getControleVendedor();
        $this->smarty->assign('controleVendedor', $controleVendedor);

        // Verifica se o usuário tem permissão para alterar vendedor (admin/gerente)
        $permiteAlterarVendedor = $this->verificaDireitoUsuario('PEDPERMITEALTERARVENDEDOR', 'S', 'N');
        $this->smarty->assign('permiteAlterarVendedor', $permiteAlterarVendedor);

        $this->smarty->assign('obsPerda', $this->getObs());

        $objProduto = new c_produto();
        $unidade_combo = $objProduto->select_unidade_combo();
        $this->smarty->assign('uni_ids', $unidade_combo['ids']);
        $this->smarty->assign('uni_names', $unidade_combo['names']);
        $this->smarty->assign('uniProduto', $this->getUnidadeProduto());
        $this->smarty->assign('unidadeServico', $this->getUnidadeServico());

        $this->smarty->display('pedido_ps_cadastro.tpl');
    }

    //fim desenhaCadgrupo
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraPedidoPs($mensagem = NULL, $tipoMsg = NULL)
    {

        if ($this->m_situacoesAtendimento == '') {
            $this->m_situacoesAtendimento = '|5|10|12|13';
        }

        $cliente = '';

        // Se o método for voltarPedidoPs, seleciona o pedido pelo ID
        if ($this->m_metodo == 'voltarPedidoPs') {
            $lanc = $this->select_pedido_letra("|||" . $this->getId(), "", "", "", "");
        } else {
            if ($this->m_letra != '') {
                $motivosFiltro = !empty($this->m_motivoSelecionados) ? $this->m_motivoSelecionados : ($this->m_par[8] ?? '');
                $lanc = $this->select_pedido_letra(
                    $this->m_letra,
                    $this->m_situacoesAtendimento,
                    $this->m_vendedorSelected,
                    $this->m_condPagSelected,
                    $motivosFiltro
                );
            }
        }



        if ($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[0]);

        if ($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes + 1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        } else $this->smarty->assign('dataFim', $this->m_par[1]);

        // pessoa
        if ($this->m_par[2] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            $this->smarty->assign('pessoa', $this->m_par[2]);
            $this->smarty->assign('nome', $this->getClienteNome());
        }

        $this->smarty->assign('numAtendimento', $this->m_par[3]);

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')";
        // if (ADMSistema != 'PECAS') {
        //     $sql .= " AND ((TIPO = 0) or (TIPO = 5) or (TIPO = 6) or (TIPO = 7) or (TIPO = 9) or (TIPO = 10) or (TIPO = 11) or (TIPO = 12))";
        // }
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids',   $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);


        $parSit = explode("|", $this->m_situacoesAtendimento);
        $this->smarty->assign('situacao_id', $parSit);

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->assign('situacoesAtendimento', $this->m_situacoesAtendimento);



        // COMBOBOX MOTIVO
        $sql = "SELECT MOTIVO AS ID, DESCRICAO FROM FAT_MOTIVO";
        $this->comboSql($sql, $this->m_motivoSelecionados, $motivo_id, $motivo_ids, $motivo_names);
        $this->smarty->assign('motivo_ids', $motivo_ids);
        $this->smarty->assign('motivo_names', $motivo_names);
        $this->smarty->assign('motivo_id', $motivo_id);
        $this->smarty->assign('motivoSelected', $this->m_motivoSelecionados);

        // ########## CENTROCUSTO ##########
        $verSomenteInfoDaLoja = $this->verificaDireitoUsuario('PEDVERSOMENTEINFODALOJA', 'S', 'N');
        $cWhere = '';
        if ($verSomenteInfoDaLoja) {
            $cWhere = 'where centrocusto = ' . $this->m_empresacentrocusto;
        }
        $sql = "select centrocusto as id, descricao from fin_centro_custo " . $aliqRegEspSTMTcWhere . " order by centrocusto";
        $this->comboSql($sql, $this->m_par[7] ?? $this->m_empresacentrocusto, $centroCusto_id, $centroCusto_ids, $centroCusto_names);
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);
        $this->smarty->assign('centroCusto_id', $centroCusto_id);
        $this->smarty->assign('verSomenteInfoDaLoja', $verSomenteInfoDaLoja);


        // COMBOBOX VENDEDOR
        $vertodoslancamentos = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');
        $this->smarty->assign('vertodoslancamentos', $vertodoslancamentos);
        if ($vertodoslancamentos == false) {
            $vendedor = $this->verifica_vendedor();
            $this->smarty->assign('vendedor_ids',   $vendedor[0]['USUARIO']);
            $this->smarty->assign('vendedor_names', $vendedor[0]['NOME']);
            $this->smarty->assign('vendedor_id', $vendedor[0]['USUARIO']);
        } else {
            //$sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO WHERE TIPO = 'V'";
            $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO ";

            // Prepara vendedores selecionados
            $vendedorSelecionado = array();
            if (!empty($this->m_vendedorSelected)) {
                $parVend = explode("|", $this->m_vendedorSelected);
                for ($i = 1; $i < count($parVend); $i++) {
                    if (!empty($parVend[$i])) {
                        $vendedorSelecionado[] = $parVend[$i];
                    }
                }
            }

            $this->comboSql($sql, $this->m_par[5], $vendedor_id, $vendedor_ids, $vendedor_names);
            $this->smarty->assign('vendedor_id', !empty($vendedorSelecionado) ? $vendedorSelecionado : $vendedor_id);
            $this->smarty->assign('vendedor_ids',   $vendedor_ids);
            $this->smarty->assign('vendedor_names', $vendedor_names);
        }

        //COMBOBOX Cond Pagamento
        $sql = "SELECT * FROM FAT_COND_PGTO;";
        $this->comboSql($sql, $this->m_par[6], $condPag_id, $condPag_ids, $condPag_names);
        $this->smarty->assign('condPag_id', $condPag_id);
        $this->smarty->assign('condPag_ids',   $condPag_ids);
        $this->smarty->assign('condPag_names', $condPag_names);

        //COMBOBOX USR Fatura
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO ";
        $this->comboSql($sql, $this->m_par[5], $usrfatura_id, $usrfatura_ids, $usrfatura_names);
        $this->smarty->assign('usrfatura_id', $usrfatura_id);
        $this->smarty->assign('usrfatura_ids',   $usrfatura_ids);
        $this->smarty->assign('usrfatura_names', $usrfatura_names);

        $this->smarty->display('pedido_ps_mostra.tpl');
    }

    function comboSql($sql, $par, &$id, &$ids, &$names)
    {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        // Operador de coalescencia para versao 8.3 do php
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $ids[$i] = $result[$i]['ID'];
            $names[$i] = $result[$i]['DESCRICAO'];
        }

        $param = explode(",", $par);
        $i = 0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }
    }
}



function formataBdVlr($vlr)
{
    //formatação vlr 
    if (strlen($vlr) > 6) {
        $number = explode(",", ($vlr));
        $newNumber = str_replace('.', '', $vlr);
        $vlrBd = $newNumber . "." . $number[1];
    } else {
        $vlrBd = str_replace(',', '.', $vlr);
    }
    return $vlrBd;
}
function setTotalGeral($vlrPecas, $vlrServicos, $frete, $vlrDespAcessorias, $vlrDesconto)
{
    $pecas    = formataBdVlr($vlrPecas);
    $servicos = formataBdVlr($vlrServicos);
    $frete = formataBdVlr($vlrServicos);
    $despAcessorias =   formataBdVlr($vlrDespAcessorias);
    $desconto = formataBdVlr($vlrDesconto);

    $totalGeral = (($pecas + $servicos + $frete + $despAcessorias) - $desconto);

    return $totalGeral;
}


// Rotina principal - cria classe (evita bootstrap ao incluir de outro form)
if (!defined('PEDIDO_PS_SEM_BOOTSTRAP')) {
    $pedido = new p_pedido_ps();
    $pedido->controle();
}
