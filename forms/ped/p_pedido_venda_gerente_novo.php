<?php
/**
 * @package   astec
 * @name      p_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      02/05/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/ped/c_pedido_venda_gerente_tools.php");
require_once($dir . "/../../forms/ped/p_pedido_venda_nf_pecas_novo.php");
require_once($dir . "/../../forms/ped/p_pedido_venda_nf_ps.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/crm/c_conta.php");

//Class P_situacao
Class p_pedido_venda_conferecia_novo extends c_pedidoVenda {

    private $m_submenu          = NULL;
    private $m_letra            = NULL;
    private $m_par              = NULL;
    private $m_par_ped          = NULL;
    private $m_codProduto       = NULL;
    private $m_qtdeConferido    = NULL;

    private $m_dados_ped        = NULL;
    private $m_pedidos_agrupado = NULL;
    private $m_pessoa_filtro    = NULL;
    private $m_parm               = [];
    public $smarty              = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu = null, $letra = null, $codProduto = null, $qtdeConferido = null) {
// Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT) ?: [];
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];
        $parm = array_merge($parmGet, $parmPost);
        $this->m_parm = $parm;

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = ($submenu !== null) ? $submenu : ($parm['submenu'] ?? '');
        $this->m_letra = ($letra !== null) ? $letra : ($parm['letra'] ?? '');
        $this->m_codProduto = ($codProduto !== null) ? $codProduto : ($parm['codProduto'] ?? '');
        $this->m_qtdeConferido = ($qtdeConferido !== null) ? $qtdeConferido : ($parm['qtdeConferido'] ?? '');

        $this->m_dados_ped = $parm['dadosPed'] ?? '';
        $this->m_pedidos_agrupado = $parm['pedidoAgrupado'] ?? '';
        $this->m_pessoa_filtro = trim((string) ($parm['pessoaFiltro'] ?? ''));
        // Parametro usado para historico do form gerente pedido
        $this->m_data_history = $parm['data_history'] ?? '';

        if ($submenu === null) {
            $this->setId((string) ($parm['id'] ?? ''));
        }

        $this->m_par = explode("|", $this->m_letra);
        $this->m_par_ped = explode("|", $this->m_dados_ped);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('raizFonte', ADMraizFonte);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('httpCliente', ADMhttpCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME'] ?? 'index.php');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Gerência Pedidos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,7,8 ]"); 
        $this->smarty->assign('disableSort', "[ 0, 3, 7, 8 ]");
        $this->smarty->assign('numLine', "25");
        // include do javascript
        // include ADMjs . "/ped/s_pedido_venda_gerente.js";
        // include ADMjs . "/ped/s_pedido_venda_conferencia.js";
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'imprime':
                if ($this->verificaDireitoUsuario('PedGerente', 'R')) {
                    // Romaneio: na situação 6 (Pedido) imprime e avança fase; na 3 (Emitir NF) só reimprime
                    $arrPedido = $this->select_pedidoVenda();
                    $sitAtual = (int) ($arrPedido[0]['SITUACAO'] ?? 0);
                    if ($sitAtual === 6) {
                        $parametros = new c_banco;
                        $parametros->setTab("FAT_PARAMETRO");
                        $sitEmiteNf = $parametros->getField("FLUXOPEDIDO", "FILIAL=".$this->m_empresacentrocusto);
                        $parametros->close_connection();
                        if ($sitEmiteNf == 'S') {
                            $this->atualizarField('situacao', 2);
                        } else {
                            $this->atualizarField('situacao', 3);
                        }
                    }
                    $this->mostraPedidoGerente('');
                }
                break;
            case 'financeiro':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $arrPedFin = $this->select_pedidoVenda();
                    $objContaBloq = new c_conta();
                    if (strtoupper((string) $objContaBloq->contaBloqueada((int) ($arrPedFin[0]['CLIENTE'] ?? 0))) === 'S') {
                        $this->mostraPedidoGerente('');
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
                    $pedidoFinaliza = new p_pedido_venda_nf_pecas_novo($this->getId(), 'financeiro');
                    $pedidoFinaliza->controle();
                }
                break;
            case 'financeiroServico':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $pedidoFinaliza = new p_pedido_venda_nf_ps($this->getId(), 'financeiro');
                    $pedidoFinaliza->controle();
                }
                break;
            case 'notafiscal':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $arrPedNf = $this->select_pedidoVenda();
                    if (is_array($arrPedNf) && (int) ($arrPedNf[0]['SITUACAO'] ?? 0) === 13) {
                        $this->mostraPedidoGerente('');
                        $msgEnc = addslashes(
                            'Pedido em encomenda — emissão de NF bloqueada até liberação de estoque. '
                            . 'Use Financeiro → Produtos para cadastrar parcelas.'
                        );
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script>";
                        echo "<script>
                            Swal.fire({
                                icon: 'info',
                                title: 'Encomenda',
                                text: '" . $msgEnc . "',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                        break;
                    }
                    $objContaBloq = new c_conta();
                    if (strtoupper((string) $objContaBloq->contaBloqueada((int) ($arrPedNf[0]['CLIENTE'] ?? 0))) === 'S') {
                        $this->mostraPedidoGerente('');
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
                    $pedidoFinaliza = new p_pedido_venda_nf_pecas_novo($this->getId(), 'notafiscal');
                    $pedidoFinaliza->controle();
                }
                break;
            case 'MesAtual':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $this->mostraPedidoGerente('A');
                }
                break;
            case 'todosPedidosMes':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $this->mostraPedidoGerente('todosDoMes');
                }
                break;
            case 'ultimos60Dias':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $this->mostraPedidoGerente('ultimos60Dias');
                }
                break;
            case 'todosPedidos':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    $this->mostraPedidoGerente('todosPedidos');
                }
                break;
            case 'agrupaPedido':
                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {
                    try{
                        $transaction = new c_banco();
                        //inicia transacao
                        $transaction->inicioTransacao($transaction->id_connection);

                        $objPedGerenteTools = new c_pedido_venda_gerente_tools();
                        $objPedGerenteTools->cancelaPedidoAgrupado($this->m_pedidos_agrupado, $transaction->id_connection);
                        $idGerado = $objPedGerenteTools->incluiPedidoAgrupado($this->m_pedidos_agrupado, $this->m_dados_ped, $transaction->id_connection);

                        $objPedGerenteTools->incluiItensPedidoAgrupado($this->m_pedidos_agrupado, $idGerado, $transaction->id_connection);
                       
                        //; commit transação
                        $transaction->commit($transaction->id_connection); 
                        $pedSit = new c_banco();
                        $pedSit->setTab("AMB_DDM");
                        $situacao = $pedSit->getField("PADRAO", "TIPO='".$this->m_par_ped[1]."' AND ((ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')) ");
                        $pedSit->close_connection();

                        $msg = 'Pedidos Agrupado em Ped: '.$idGerado.' Situação - '.$situacao;
                        $tipoMsg = 'sucesso';
                        $this->mostraPedidoGerente($msg, $tipoMsg);

                    }catch (Error $e) {
                        $transaction->rollback($transaction->id_connection);    
                        $transaction->close_connection($transaction->id_connection);
                        $msg = "Pedido Não Gerado - Verificar produtos cadastrados<br>".$e->getMessage();
                        $tipoMsg = "alerta";
                        $this->mostraPedidoGerente($msg, $tipoMsg);
    
                    } catch (Exception $e) {
                        if ($transaction->id_connection != null){
                            $transaction->rollback($transaction->id_connection);
                            $transaction->close_connection($transaction->id_connection);
                        }
                        $msg = "Pedido Não Gerado - Verificar produtos cadastrados<br>".$e->getMessage();
                        $tipoMsg = "alerta";
                        $this->mostraPedidoGerente($msg, $tipoMsg);
                    } 
                }
                break;    
            case 'cadastraFinanceiro': 
                break;
            case 'pesquisaClienteAjax':
                if ($this->verificaDireitoUsuario('PedGerente', 'C')) {
                    $clienteResult = [];
                    $term = trim((string) ($this->m_parm['term'] ?? ''));
                    if ($term !== '') {
                        $resultPesq = (new c_conta())->select_pessoa_letra($term);
                        for ($i = 0; $i < count($resultPesq); $i++) {
                            $clienteResult[$i]['id'] = trim($resultPesq[$i]['CLIENTE']);
                            $clienteResult[$i]['text'] = trim($resultPesq[$i]['NOME']);
                        }
                    }
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($clienteResult, JSON_UNESCAPED_UNICODE);
                    exit;
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('PedGerente', 'C')) {
                    $this->mostraPedidoGerente('');
                }
        }
    }

   

//fim desenhaCadgrupo
//-------------------------------------------------------------
//---------------------------------------------------------------
//---------------------------------------------------------------
    function imprimePedido($mensagem) {

        // $lanc = $this->select_pedidoVenda_letra('||||1|2|3');
        $lanc = $this->select_pedidoVenda_letra('||||' . $this->getSituacoesFiltroGerente() . '|');

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_gerente.tpl');
    }

//fim mostraPedidoConferencia

//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedidoGerente($mensagem, $tipoMsg=NULL) {
        $pessoaFiltro = trim((string) $this->m_pessoa_filtro);

        if ($mensagem == 'A'){
              $lanc = $this->select_pedidoVenda_letra_atual('');
        }
        else if($mensagem == 'todosDoMes'){
            $dataIni =  date("01/m/Y");
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $dataFim = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $sitFiltro = $this->getSituacoesFiltroGerente();
            $letra = $dataIni . '|' . $dataFim . '|' . $pessoaFiltro . '||' . $sitFiltro . '|';
            $lanc = $this->select_pedidoVenda_letra($letra, null, true);
        }
        else if($mensagem == 'ultimos60Dias'){
            $dataFim = date("d/m/Y");
            $dataIni = date("d/m/Y", strtotime('-59 days'));
            $sitFiltro = $this->getSituacoesFiltroGerente();
            $letra = $dataIni . '|' . $dataFim . '|' . $pessoaFiltro . '||' . $sitFiltro . '|';
            $lanc = $this->select_pedidoVenda_letra($letra, null, true);
        }
        else if($mensagem == 'todosPedidos'){
            $sitFiltro = $this->getSituacoesFiltroGerente();
            $letra = '||' . $pessoaFiltro . '||' . $sitFiltro . '|';
            $lanc = $this->select_pedidoVenda_letra($letra, null, true);
        }
        else {     
            $dataIni =  date("d/m/Y");
            $dataFim =  date("d/m/Y");
            $sitFiltro = $this->getSituacoesFiltroGerente();
            $letra = $dataIni . '|' . $dataFim . '|' . $pessoaFiltro . '||' . $sitFiltro . '|';
            $lanc = $this->select_pedidoVenda_letra($letra, null, true);
            $this->smarty->assign('mensagem', $mensagem);
            $this->smarty->assign('tipoMsg', $tipoMsg);
        }
       
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        
        
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('mSituacao', '5');

        // COMBOBOX CONDICAO PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i + 1] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPg());
        // Parametro usado para historico do form gerente pedido
        // submenu esta sendo uitlizado como parametro de pesquisa
        $this->smarty->assign('data_history', $this->m_submenu );
        $this->smarty->assign('mod', 'ped');
        $this->smarty->assign('form', 'pedido_venda_gerente_novo');
        $this->smarty->assign('pessoaFiltro', $this->m_pessoa_filtro);
        $nomeClienteFiltro = '';
        if ($pessoaFiltro !== '') {
            $consulta = new c_banco();
            $consulta->setTab('FIN_CLIENTE');
            $nomeClienteFiltro = (string) $consulta->getField('NOME', 'CLIENTE=' . $pessoaFiltro);
            $consulta->close_connection();
        }
        $this->smarty->assign('nomeClienteFiltro', $nomeClienteFiltro);

        $this->smarty->display('pedido_venda_gerente_novo.tpl');
    }

    /**
     * Situações exibidas na grade: 3,6 e 13 (encomenda) quando ENCOMENDA=S na filial.
     */
    private function getSituacoesFiltroGerente(): string
    {
        $param = new c_banco();
        $param->setTab('FAT_PARAMETRO');
        $encomenda = $param->getField('ENCOMENDA', 'FILIAL=' . $this->m_empresacentrocusto);
        $param->close_connection();
        return ($encomenda === 'S') ? '3,6,13' : '3,6';
    }

//fim mostraPedidoConferencia
//-------------------------------------------------------------
}

// variavel definida no p_pedido_venda_nf_pecas_novo.php para evitar execução do controle
if (!defined('APENAS_FUNCOES')) {
    (new p_pedido_venda_conferecia_novo())->controle();
}
?>
