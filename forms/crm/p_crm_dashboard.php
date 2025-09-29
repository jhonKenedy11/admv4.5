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

        $this->m_motivoSelecionados = $parmPost['motivoSelected'];
        $this->m_idVendaperdida  = $parmPost['idVendaPerdida'];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        
}

/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
function controle(){
  switch ($this->m_submenu){
    case 'pesquisa':
        $this->mostraDashboard('');
    break;
    case 'buscaAcompanhamentos':
        $this->acompanhamentos('');
    break;
    case 'motivoGeral':
        $objPedVenda = new c_pedidoVenda;
        $objPedVenda->setId($this->m_idVendaperdida);        
        $objPedVenda->atualizarMotivoItem($this->m_motivoSelecionados);
        $objPedVenda->atualizarFieldPedido(7);
        $this->m_submenu = null;
        //Zera o submenu para evitar o reload do form
        $this->mostraDashboard(''); 
        echo'<script>
                swal({
                    title: "Sucesso!",
                    text: "Pedido Alterado para VENDA PERDIDA!",
                    icon: "success",
                  });
            </script>';      
    break;
    default:
        $this->mostraDashboard('');
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
        }else{
            $vendedor = $this->m_par[2];
        }

    // ########## CENTROCUSTO ##########
    $cWhere = '';
    if ($verSomenteInfoDaLoja) {
        $cWhere = 'where centrocusto = ' . $this->m_empresacentrocusto;
    }
    $sql = "select centrocusto as id, descricao from fin_centro_custo " . $cWhere . " order by centrocusto";
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
        $metas = $objClassPedVenda->metas($dataIni, $dataFim, $wherec, $vendedor) ?? [];
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
        $metas = $objClassPedVenda->metas($dataIni, $dataFim, $wherec, $vendedor) ?? [];
        //Old meta
        //$resultMeta = $objDashboard->buscaoMeta($this->m_userid, $ano, $mes, $this->m_empresacentrocusto);
    }

    $cotOntem  = $resultBusca[0]['COTACAO_ONTEM'];
    $cotHoje   = $resultBusca[0]['COTACAO_HOJE'];
    $conversao = $resultBusca[0]['CONVERSAO'];
    $perdidos  = $resultBusca[0]['PERDIDOS'];
    $metas = $metas ?? [];
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
    if ($allPedidos > 0) {
        $percPed = number_format(($pedMes / $allPedidos * 100), 2);
    } else {
        $percPed = 0.00; 
    }
    
    //meta
    if ($pedMesValor != 0 && $vlrMetaMensal != 0) {
        $percMetaMensal = number_format(($pedMesValor / $vlrMetaMensal) * 100, 2);
    } else {
        $percMetaMensal = 0.00;
    }
    
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

    if(($vendedor !== '') and ($vendedor !== null)){
        $objAcomp = new c_contas_acompanhamento;
        $letraAcomp = $dataIni.'|'.$dataFim.'|'.$vendedor.'|'.''.'|'.$pedido;
        $resultAcomp = $objAcomp->select_pessoaConsultaAcompanhamento($letraAcomp);
    }
    

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

} //FIM CLASSE

//	END OF THE CLASS
// Rotina principal - cria classe
$dashboard = new p_crm_dashboard();
//echo 'submenu:'.$_POST['submenu'].'|letra:'. $_POST['letra'].'|opcao:'.$_POST['opcao'];

$dashboard->controle();
?>
