<?php
/**
 * @package   astec
 * @name      p_acompanhamento
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      03/02/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')):
    exit;
endif;
    

$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/crm/c_dashboard.php");
include_once($dir . "/../../class/ped/c_pedido_venda.php");
include_once($dir . "/../../class/fin/c_lancamento.php");
include_once($dir . "/../../class/crm/c_contas_acompanhamento.php");

Class p_crm_dashboard extends c_dashboard {

private $m_submenu = NULL;
private $m_opcao = NULL;
private $m_letra = NULL;
public $smarty = NULL;
public $centroCusto = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

	    // Cria uma instancia variaveis de sessao
        // session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/crm";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";


        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_pesq = $parmPost['pesq'];
        $this->m_opcao = $parmPost['opcao'];
        $this->m_letra = $parmPost['letra'];
        $this->m_idCotacao = $parmPost['idCotacao'];
        $this->m_idCliente = $parmPost['idCliente'];
        $this->m_nomeCliente = $parmPost['nomeCliente'];
        $this->m_obsPerda = isset($parmPost['obsPerda']) ? trim($parmPost['obsPerda']) : '';
        $this->m_motivoSelecionados = $parmPost['motivoSelected'];
        $this->m_idVendaperdida  = $parmPost['idVendaPerdida'];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        
}

/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
function controle(){
  if ($this->verificaDireitoUsuario('CrmDashboard', 'C')) {
  switch ($this->m_submenu){
    case 'pesquisa':
        $this->mostraDashboard('');
    break;
    case 'buscaAcompanhamentos':
        $this->acompanhamentos('');
    break;
    case 'buscaAcompPainel':
        $this->acompPainel();
    break;
    case 'motivoGeral':
        if ($this->verificaDireitoUsuario('CrmDashboard', 'A')){
        $banco = new c_banco();
        $banco->setTab('FAT_PEDIDO');
        $situacaoPed = $banco->getField('SITUACAO', 'ID=' . (int) $this->m_idVendaperdida);
        $banco->close_connection();
        if ($situacaoPed === '' || (int) $situacaoPed !== 5) {
            $this->m_submenu = null;
            $this->mostraDashboard('');
            echo'<script>
                    swal({
                        title: "Atenção!",
                        text: "Venda perdida permitida apenas para pedidos em cotação!",
                        icon: "warning",
                      });
                </script>';
            break;
        }
        $objLancamento = new c_lancamento();
        $searchLanc = $objLancamento->select_lancamento_doc('PED', $this->m_idVendaperdida);
        if ($searchLanc == '' || $searchLanc == null) {
            $objPedVenda = new c_pedidoVenda;
            $objPedVenda->setId($this->m_idVendaperdida);
            $objPedVenda->atualizarMotivoItem($this->m_motivoSelecionados);
            $objPedVenda->atualizarObsPerda($this->m_obsPerda);
            $objPedVenda->atualizarFieldPedido(7);
            $objPedVenda->atualizarTotal($objPedVenda->select_totalPedido());
            $this->m_submenu = null;
            $this->mostraDashboard('');
            echo'<script>
                    swal({
                        title: "Sucesso!",
                        text: "Pedido Alterado para VENDA PERDIDA!",
                        icon: "success",
                      });
                </script>';
        } else {
            $this->m_submenu = null;
            $this->mostraDashboard('');
            echo'<script>
                    swal({
                        title: "Atenção!",
                        text: "Não foi possível atualizar pedido, existe financeiro cadastrado!",
                        icon: "warning",
                      });
                </script>';
        }
        }
    break;
    default:
        $this->mostraDashboard('');
    }
  }

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Genero. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function mostraDashboard($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    if($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
    else $this->smarty->assign('dataIni', $this->m_par[0]);
    
    if($this->m_par[0] == "") {
        $this->m_par[0] = date("01/m/Y");
    }
    
    if($this->m_par[1] == "") {
    	$dia = date("d");
    	$mes = date("m");
    	$ano = date("Y");
    	$data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano)); 
        $this->m_par[1] = $data;
    	$this->smarty->assign('dataFim', $data);
    }
    else $this->smarty->assign('dataFim', $this->m_par[1]);

        if ($this->m_par[3] == "") {
            $centroCusto = $this->m_empresacentrocusto;
        } else {
            $centroCusto = $this->m_par[3];
        }

        $objDashboard = new c_dashboard;
        //Dados para pesquisa origem Letra
        $dataIni     = $this->m_par[0];
        $dataFim     = $this->m_par[1];

        $vertodoslancamentos = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');
        $verSomenteInfoDaLoja = $this->verificaDireitoUsuario('PEDVERSOMENTEINFODALOJA', 'S', 'N');
        if ($vertodoslancamentos == false) {
            $vendedor = $this->m_userid;
        } else {
            $vendedor = ($this->m_par[2] ?? '') ?: $this->m_userid;
        }
        $this->m_par[2] = $vendedor;

    // ########## CENTROCUSTO ##########
    $cWhere = '';
    if ($verSomenteInfoDaLoja) {
        $cWhere = 'where centrocusto = ' . $this->m_empresacentrocusto;
    }
    $sql = "select centrocusto as id, descricao from FIN_CENTRO_CUSTO " . $cWhere . " order by centrocusto";
    $this->comboSql($sql, $centroCusto ?? $this->m_empresacentrocusto, $centroCusto_id, $centroCusto_ids, $centroCusto_names);
    $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
    $this->smarty->assign('centroCusto_names', $centroCusto_names);
    $this->smarty->assign('centroCusto_id', $centroCusto_id);
    $this->smarty->assign('verSomenteInfoDaLoja', $verSomenteInfoDaLoja);
    //########## FIM CENTROCUSTO ##########
 
    // ########## COMBOBOX VENDEDOR ##########
    // valida direito de visualizar pedidos de outros vendedores
    if ($vertodoslancamentos == true) {
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO ";
        $this->comboSql($sql, $vendedor, $vendedor_id, $vendedor_ids, $vendedor_names);
        $this->smarty->assign('vendedor_id', $vendedor_id);
        $this->smarty->assign('vendedor_ids',   $vendedor_ids);
        $this->smarty->assign('vendedor_names', $vendedor_names);
    } else {
        $verificaVendedor = $this->verifica_vendedor();
        $this->smarty->assign('vendedor_ids',   $verificaVendedor[0]['USUARIO']);
        $this->smarty->assign('vendedor_names', $verificaVendedor[0]['NOME']);
        $this->smarty->assign('vendedor_id', $verificaVendedor[0]['USUARIO']); 
    }
    //########## FIM VENDEDOR ##########

    // COMBOBOX MOTIVO
    $sql = "SELECT MOTIVO AS ID, DESCRICAO FROM FAT_MOTIVO";
    $this->comboSql($sql, $this->m_par[8], $motivo_id, $motivo_ids, $motivo_names);
    $this->smarty->assign('motivo_ids', $motivo_ids);
    $this->smarty->assign('motivo_names', $motivo_names);
    $this->smarty->assign('motivo_id', $motivo_id);
    //########## FIM MOTIVO ##########
    
    //New Meta
    $where = " (";
    $wherel = " (";
    $wherec = " (";
    $wheres = " (";
    //verifica se existe centro de custo senão monta array com todos
    if($this->m_par[3] == ''){
        $centroCusto = implode(',', $centroCusto_ids); 
    }
    $cc = explode(",", $centroCusto);
    for ($i = 0; $i < count($cc); $i++) {
        $wherel .= "( l.centrocusto = " . $cc[$i] . " ) ";
        $where .= "( centrocusto = " . $cc[$i] . " ) ";
        $wherec .= "( p.ccusto = " . $cc[$i] . " ) ";
        $wheres .= "( ccusto = " . $cc[$i] . " ) ";
        if (($i + 1) < count($cc)) {
            $wherel .= " or ";
            $where .= " or ";
            $wherec .= " or ";
            $wheres .= " or ";
            $whereM .= " or ";
        }
    }
    $where .= ") ";
    $wherel .= ") ";
    $wherec .= ") ";
    $wheres .= ") ";
    //Fim New Meta

    $objClassPedVenda = new c_pedidoVenda();
    $metasFor = ['NUMVENDAS' => 0, 'VALORVENDIDO' => 0, 'METADEVENDAS' => 0];

    if($vertodoslancamentos == true){
        //Busca Cotacoes
        $resultBusca = $objDashboard->buscaCotacaoPedidos($dataIni, $dataFim, $vendedor, $centroCusto, $this->m_empresacentrocusto, $vertodoslancamentos);

        //Parametros para busca de meta
        //data ini
        if (($dataIni == '') || ($dataIni == null)) {
            $dataIni = date("Y-m-01");
        } else {
            if (strpos($dataIni, "/")) {
                $dataIni = c_date::convertDateBdSh($dataIni, $this->m_banco);
            }
        }
        //data fim
        if (($dataFim == '') || ($dataFim == null)) {
            $dataFim = date("Y-m-t");
        } else {
            if (strpos($dataFim, "/")) {
                $dataFim = c_date::convertDateBdSh($dataFim, $this->m_banco);
            }
        }
        //New meta
        $metas = $objClassPedVenda->metas($dataIni, $dataFim, $wherec, $vendedor);
            //Old meta
            //$resultMeta = $objDashboard->buscaoMeta($vendedor, $ano, $mes, $centroCusto);
        for ($i = 0; $i < count($metas); $i++) {
            $metasFor['NUMVENDAS'] += $metas[$i]['NUMVENDAS'];
            $metasFor['VALORVENDIDO'] += $metas[$i]['VALORVENDIDO'];
            $metasFor['METADEVENDAS'] += $metas[$i]['METADEVENDAS'];
        }
    }else{
        //Busca Cotacoes
        $resultBusca = $objDashboard->buscaCotacaoPedidos($dataIni, $dataFim, $this->m_userid, null, $this->m_empresacentrocusto, $vertodoslancamentos);

        //Parametros para busca de meta
        //data Ini
        if (($dataIni == '') || ($dataIni == null)) {
            $dataIni = date("Y-m-01");
        } else {
            if (strpos($dataIni, "/")) {
                $dataIni = c_date::convertDateBdSh($dataIni, $this->m_banco);
            }
        }
        //data fim
        if (($dataFim == '') || ($dataFim == null)) {
            $dataFim = date("Y-m-t");
        } else {
            if (strpos($dataFim, "/")) {
                $dataFim = c_date::convertDateBdSh($dataFim, $this->m_banco);
            }
        }
        //New meta
        $metas = $objClassPedVenda->metas($dataIni, $dataFim, $wherec, $vendedor);
        //Old meta
        //$resultMeta = $objDashboard->buscaoMeta($this->m_userid, $ano, $mes, $this->m_empresacentrocusto);
    }

    $cotOntem  = $resultBusca[0]['COTACAO_ONTEM'];
    $cotHoje   = $resultBusca[0]['COTACAO_HOJE'];
    $conversao = $resultBusca[0]['CONVERSAO'];
    $perdidos  = $resultBusca[0]['PERDIDOS'];

    if(count($metas) > 1){
        $pedMes        = $metasFor['NUMVENDAS'];
        $pedMesValor   = $metasFor['VALORVENDIDO'];
        $vlrMetaMensal = $metasFor['METADEVENDAS'];
    }else{
        $pedMes        = $metas[0]['NUMVENDAS'];
        $pedMesValor   = $metas[0]['VALORVENDIDO'] ?? 0;
        $vlrMetaMensal = $metas[0]['METADEVENDAS'];
    }
    
    //verifica direito para consultar todos os pedidos emitidos no periodo
    if($vertodoslancamentos == true){
        $allPedidos = $objClassPedVenda->selectAllPed($this->m_par);
    }else{
        array_push($this->m_par, $this->m_userid);
        array_push($this->m_par, $this->m_empresacentrocusto);
        $allPedidos = $objClassPedVenda->selectAllPed($this->m_par);
    }
    
    //total de pedidos do periodo
    $pedMesNum = (float)($pedMes ?? 0);
    $allPedidosNum = (float)($allPedidos ?? 0);
    $percPed = ($allPedidosNum > 0)
        ? number_format(($pedMesNum / $allPedidosNum) * 100, 2)
        : number_format(0, 2);

    //meta
    $pedMesValorNum = (float)($pedMesValor ?? 0);
    $vlrMetaMensalNum = (float)($vlrMetaMensal ?? 0);
    $percMetaMensal = ($vlrMetaMensalNum > 0)
        ? number_format(($pedMesValorNum / $vlrMetaMensalNum) * 100, 2)
        : number_format(0, 2);

    if($percMetaMensal > 100){
        $this->smarty->assign('iconeFaSort', 'asc');    
    }else{
        $this->smarty->assign('iconeFaSort', 'desc');
    }

    $this->smarty->assign('metas', $metas);
    $this->smarty->assign('vlrMetaMensal', $vlrMetaMensal);
    $this->smarty->assign('cotOntem', $cotOntem);
    $this->smarty->assign('cotHoje', $cotHoje);
    $this->smarty->assign('conversao', $conversao);
    $this->smarty->assign('perdidos', $perdidos);
    $this->smarty->assign('totalPedMes', $allPedidos);
    $this->smarty->assign('percPed', $percPed);
    $this->smarty->assign('pedMes', $pedMes);
    $this->smarty->assign('pedMesValor', $pedMesValor);
    $this->smarty->assign('vlrMetaMensal', $vlrMetaMensal);
    $this->smarty->assign('percMetaMensal', $percMetaMensal);


    $objPedVenda = new c_pedidoVenda;
    //teste direito do vendedor
    if($vertodoslancamentos !== true){
        $vendedor = $this->m_userid;
    }
    $letraCot = $this->m_par[0].'|'. $this->m_par[1].'|'.''.'|'.''.'|'.'5'.'|'.$vendedor.'|'.''.'|'.$centroCusto;
    $resultCot = $objPedVenda->select_pedidoVenda_letra($letraCot);

    $resultAcomp = [];
    if (($vendedor !== '') && ($vendedor !== null)) {
        $objAcomp = new c_contas_acompanhamento;
        $letraAcomp = $dataIni.'|'.$dataFim.'|'.$vendedor.'|'.''.'|'.'';
        $resultAcomp = $objAcomp->select_pessoaConsultaAcompanhamento($letraAcomp);
    }

    $vendedorPainel = $vendedor;
    if ($vertodoslancamentos !== true) {
        $vendedorPainel = (string)$this->m_userid;
    }
    $objAcompPainel = new c_contas_acompanhamento;
    $dataIniSug = $dataIni;
    $dataFimSug = $dataFim;
    if (strpos((string)$dataIniSug, "/")) {
        $dataIniSug = c_date::convertDateBdSh($dataIniSug, $this->m_banco);
    }
    if (strpos((string)$dataFimSug, "/")) {
        $dataFimSug = c_date::convertDateBdSh($dataFimSug, $this->m_banco);
    }

    $acompHoje = $objAcompPainel->select_acomp_painel_dashboard($vendedorPainel, 'hoje', $dataIniSug, $dataFimSug, $centroCusto, 200);
    $acompAtrasados = $objAcompPainel->select_acomp_painel_dashboard($vendedorPainel, 'atrasados', $dataIniSug, $dataFimSug, $centroCusto, 200);
    $acompProximos = $objAcompPainel->select_acomp_painel_dashboard($vendedorPainel, 'proximos', $dataIniSug, $dataFimSug, $centroCusto, 200);
    $acompSugestoes = $objAcompPainel->select_sugestoes_acompanhamento($vendedorPainel, $centroCusto, $dataIniSug, $dataFimSug);

    $this->smarty->assign('acompHoje', $acompHoje);
    $this->smarty->assign('acompAtrasados', $acompAtrasados);
    $this->smarty->assign('acompProximos', $acompProximos);
    $this->smarty->assign('acompSugestoes', $acompSugestoes);
    $this->smarty->assign('acompHojeCount', is_array($acompHoje) ? count($acompHoje) : 0);
    $this->smarty->assign('acompAtrasadosCount', is_array($acompAtrasados) ? count($acompAtrasados) : 0);
    $this->smarty->assign('acompProximosCount', is_array($acompProximos) ? count($acompProximos) : 0);
    $this->smarty->assign('acompSugestoesCount', is_array($acompSugestoes) ? count($acompSugestoes) : 0);

    $this->smarty->assign('resultCot', $resultCot);
    $this->smarty->assign('resultAcomp', $resultAcomp);
    $this->smarty->assign('vertodoslancamentos', $vertodoslancamentos);
    $this->smarty->display('dashboard.tpl');
        
}//fim Mostra
//-------------------------------------------------------------

public function acompanhamentos(){

    //MODAL BUSCA COTACAO MOSTRA
    $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_BUSCA_ACOMPANHAMENTOS"] == "true");
    if($_SERVER["HTTP_AJAX_REQUEST_BUSCA_ACOMPANHAMENTOS"] == "true"){
        $ajax_request = 'true';

        $objAcomp = new c_contas_acompanhamento;
        $montaLetraPesq = ''.'|'.''.'|'.$this->m_par[2].'|'.''.'|'. $this->m_idCotacao;
        $resultAcomp = $objAcomp->select_pessoaConsultaAcompanhamento($montaLetraPesq);


        //Verifica quantos registros
        //if($resultCotacao != null){
        //    $this->smarty->assign('resultCotacao', $resultCotacao);
        //}
        
    }else{
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
    }

    $this->smarty->assign('tempClienteOtimizaIcone', $this->m_idCotacao);
    $this->smarty->assign('resultAcomp', $resultAcomp);
    $this->smarty->assign('idCotacao', $this->m_idCotacao);
    $this->smarty->assign('idCliente', $this->m_idCliente);
    $this->smarty->assign('nomeCliente', "'$this->m_nomeCliente'");
    $this->smarty->display('dashboard.tpl');
} //FIM ACOMPANHAMENTOS

public function acompPainel()
{
    $vendedorPainel = $this->m_par[2] ?? '';
    $vertodoslancamentos = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');
    if ($vertodoslancamentos !== true) {
        $vendedorPainel = (string)$this->m_userid;
    }

    $objAcompPainel = new c_contas_acompanhamento;
    $dataIniSug = $this->m_par[0] ?? '';
    $dataFimSug = $this->m_par[1] ?? '';
    if (strpos((string)$dataIniSug, "/")) {
        $dataIniSug = c_date::convertDateBdSh($dataIniSug, $this->m_banco);
    }
    if (strpos((string)$dataFimSug, "/")) {
        $dataFimSug = c_date::convertDateBdSh($dataFimSug, $this->m_banco);
    }
    $centroCustoSug = $this->m_par[3] ?? '';

    $acompHoje = $objAcompPainel->select_acomp_painel_dashboard($vendedorPainel, 'hoje', $dataIniSug, $dataFimSug, $centroCustoSug, 200);
    $acompAtrasados = $objAcompPainel->select_acomp_painel_dashboard($vendedorPainel, 'atrasados', $dataIniSug, $dataFimSug, $centroCustoSug, 200);
    $acompProximos = $objAcompPainel->select_acomp_painel_dashboard($vendedorPainel, 'proximos', $dataIniSug, $dataFimSug, $centroCustoSug, 200);
    $acompSugestoes = $objAcompPainel->select_sugestoes_acompanhamento($vendedorPainel, $centroCustoSug, $dataIniSug, $dataFimSug);

    $this->smarty->assign('acompHoje', $acompHoje);
    $this->smarty->assign('acompAtrasados', $acompAtrasados);
    $this->smarty->assign('acompProximos', $acompProximos);
    $this->smarty->assign('acompSugestoes', $acompSugestoes);
    $this->smarty->assign('acompHojeCount', is_array($acompHoje) ? count($acompHoje) : 0);
    $this->smarty->assign('acompAtrasadosCount', is_array($acompAtrasados) ? count($acompAtrasados) : 0);
    $this->smarty->assign('acompProximosCount', is_array($acompProximos) ? count($acompProximos) : 0);
    $this->smarty->assign('acompSugestoesCount', is_array($acompSugestoes) ? count($acompSugestoes) : 0);

    $this->smarty->display('dashboard.tpl');
}

} //FIM CLASSE

//	END OF THE CLASS
// Rotina principal - cria classe
$dashboard = new p_crm_dashboard();
//echo 'submenu:'.$_POST['submenu'].'|letra:'. $_POST['letra'].'|opcao:'.$_POST['opcao'];

$dashboard->controle();
?>
