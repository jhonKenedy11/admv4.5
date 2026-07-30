<?php
/**
 * @package   Admservice
 * @name      p_crm_acompanhamento_dashboard
 * @version   4.5
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      09/04/2026
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')):
    exit;
endif;
    

$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/crm/c_crm_dashboard.php");
include_once($dir . "/../../class/crm/c_crm_contas_acompanhamento.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../bib/c_date.php");

Class p_crm_acompanhamento_dashboard extends c_crm_dashboard {

private $m_submenu    = NULL;
private $m_parametro  = '';
public  $smarty       = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?: [];
	    // Cria uma instancia variaveis de sessao
        // session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/crm";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu            = $parmPost['submenu'] ?? null;
        $this->m_opcao              = $parmPost['opcao'] ?? null;

        $this->m_idCliente = $parmPost['idCliente'] ?? '';
        $this->m_parametro = $parmPost['parametro'] ?? '';

        // Filtros (campos explícitos no POST — sem "letra")
        $this->setDataIni($parmPost['dataIni'] ?? '');
        $this->setDataFim($parmPost['dataFim'] ?? '');
        $this->setVendedorSelected($parmPost['vendedor'] ?? '');
        $this->setCentroCustoSelected($parmPost['centroCusto'] ?? '');
        $this->setClasseSelected($parmPost['classe'] ?? '');
        $this->setEstadoSelected($parmPost['estado'] ?? '');
        $this->setCidade($parmPost['cidade'] ?? '');

    //informações default
    if($this->getDataIni() == ""){
        $this->setDataIni(date("01/m/Y"));
    }
    if($this->getDataFim() == "") {
        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");
    	$data = date("d/m/Y", strtotime("last day of this month", strtotime("$ano-$mes-$dia")));
        $this->setDataFim($data);
    }
    
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
  switch ($this->m_submenu){
    case 'pesquisa':
        if ($this->verificaDireitoUsuario('CrmDashboard', 'S') ){
            $this->mostraDashboard('');
        }
    break;
    case 'buscaAcompanhamentos':
        if ($this->verificaDireitoUsuario('CrmDashboard', 'S') ){
            $this->acompanhamentos('');
        }
    break;
    default:
        if ($this->verificaDireitoUsuario('CrmDashboard', 'S') ){
            $this->mostraDashboard('');
        }
    break;
  }
} // fim controle

function mostraDashboard($mensagem=NULL){
    $dadosUsuario = $this->verifica_vendedor();
    $podeVerOutrosVendedores = $this->verificaDireitoPrograma('CrmDashboardVerOutrosVendedores', 'S');
    $filtroVendedor = $podeVerOutrosVendedores ? null : (int) $this->m_userid;

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('opcao', $this->m_opcao ?? '');
    $this->smarty->assign('idAcomp', '');
    $this->smarty->assign('parametro', $this->m_parametro);

    // ########## COMBOBOX AÇÃO (ACOMPANHAMENTO) ##########
    $acao = $this->comboAcaoAcompanhamentoOptions();
    $this->smarty->assign('acao_ids', $acao['ids']);
    $this->smarty->assign('acao_names', $acao['names']);
    $this->smarty->assign('acao_id', '');

    // ########## Combos modal acompanhamento ##########
    $ddmStatusAcomp = c_crm_contas_acompanhamento::carregaComboDdmStatusAcomp();
    $this->smarty->assign('acomp_status_ids', $ddmStatusAcomp['ids']);
    $this->smarty->assign('acomp_status_names', $ddmStatusAcomp['names']);

    // ########## COMBOBOX VEICULO ##########
    $veiculo = $this->comboVeiculoOptions();
    $this->smarty->assign('acomp_veiculo_ids', $veiculo['ids']);
    $this->smarty->assign('acomp_veiculo_names', $veiculo['names']);
    $this->smarty->assign('acomp_veiculo_selected', '');
    
    
    
    // ########## CENTROCUSTO ##########
    
    $cc = $this->comboCentroCustoOptions();
    $this->smarty->assign('centroCusto_ids', $cc['ids']);
    $this->smarty->assign('centroCusto_names', $cc['names']);
    $csvCc = trim($this->getCentroCustoSelected());
    if ($csvCc !== '') {
        $this->smarty->assign('centroCusto_id', array_map('strval', explode(',', $csvCc)));
    } else {
        $def = trim($this->m_empresacentrocusto ?? '');
        $this->smarty->assign('centroCusto_id', $def !== '' ? array_map('strval', preg_split('/\s*,\s*/', $def)) : []);
    }
 
    if (!$podeVerOutrosVendedores) {
        $this->setVendedorSelected($this->m_userid);
    }

    // ########## COMBOBOX VENDEDOR ##########
    if (!$podeVerOutrosVendedores) {
        $nomeVendLogado = $dadosUsuario[0]['NOME'] ?? '';
        $this->smarty->assign('vendedor_ids',   [$this->m_userid]);
        $this->smarty->assign('vendedor_names', [$nomeVendLogado]);
        $this->smarty->assign('vendedor_id', [$this->m_userid]);
    } else {
        $vend = $this->comboVendedorOptions();
        $this->smarty->assign('vendedor_ids',   $vend['ids']);
        $this->smarty->assign('vendedor_names', $vend['names']);
        $this->smarty->assign('vendedor_id', $this->getVendedorSelected() ?? $this->m_userid);
    }
    // Modal "Novo acompanhamento": hidden vendedorAcomp (crm_dashboard_modal_acompanhamento.tpl)
    $this->smarty->assign('crm_dash_vendedor_id', isset($this->m_userid) ? (string) $this->m_userid : '');

    // COMBOBOX SITUACAO
    $cl = $this->comboClasseOptions();
    $this->smarty->assign('classe_ids', $cl['ids']);
    $this->smarty->assign('classe_names', $cl['names']);
    $csvCl = trim((string) $this->getClasseSelected());
    $this->smarty->assign('classe_id', $csvCl !== '' ? array_map('strval', explode(',', $csvCl)) : []);
    
    // COMBOBOX ESTADO
    $uf = $this->comboEstadoOptions();
    $this->smarty->assign('estado_ids', $uf['ids']);
    $this->smarty->assign('estado_names', $uf['names']);
    $csvUf = trim((string) $this->getEstadoSelected());
    $this->smarty->assign('estado_id', $csvUf !== '' ? array_map('strval', explode(',', $csvUf)) : []);

    if($this->getCidade() != ''){
        $this->smarty->assign('cidade', $this->getCidade());
    }

    // BUSCA CLIENTES
    $resultClientes = $this->contasAcompanhamento() ?: [];
    $resultClientes = ($resultClientes) ?: [];
    $totalClientes = count($resultClientes);
    $this->smarty->assign('resultClientes', $resultClientes);
    $this->smarty->assign('totalClientes', $totalClientes);
    $this->smarty->assign('dataIni', $this->getDataIni());
    $this->smarty->assign('dataFim', $this->getDataFim());

    //consulta headers (datas do filtro em yyyy-mm-dd para SQL)
    $objAcomp = new c_crm_contas_acompanhamento;
    $objAcomp->from_array($_SESSION['user_array']);
    $contatoDiario = $objAcomp->selectBuscaContatoDiario($filtroVendedor);
    $rowCd = (is_array($contatoDiario) && !empty($contatoDiario[0])) ? $contatoDiario[0] : [];
    $nContatoDiario = isset($rowCd['COUNT(ID)']) ? (int) $rowCd['COUNT(ID)'] : (int) (reset($rowCd) ?: 0);
    $this->smarty->assign('contatoDiario', $nContatoDiario);

    $dataIniSql = c_date::convertDateBdSh($this->getDataIni(), $this->m_banco);
    $dataFimSql = c_date::convertDateBdSh($this->getDataFim(), $this->m_banco);
    $contatoPeriodo = $objAcomp->selectBuscaContatoPeriodo($dataIniSql, $dataFimSql, $filtroVendedor);
    $rowCp = (is_array($contatoPeriodo) && !empty($contatoPeriodo[0])) ? $contatoPeriodo[0] : [];
    $nContatoPeriodo = isset($rowCp['COUNT(ID)']) ? (int) $rowCp['COUNT(ID)'] : (int) (reset($rowCp) ?: 0);
    $this->smarty->assign('contatoPeriodo', $nContatoPeriodo);

    $vertodoslancamentos = $podeVerOutrosVendedores;
    $vendSel = trim((string) ($this->getVendedorSelected() ?? ''));
    $vendedorParam = ($vendSel !== '') ? $vendSel : null;
    $csvCc = trim((string) $this->getCentroCustoSelected());
    $centroCustoParam = ($csvCc !== '') ? $csvCc : null;
    $resultBusca = $this->buscaCotacaoPedidos(
        $this->getDataIni(),
        $this->getDataFim(),
        $vendedorParam,
        $centroCustoParam,
        $this->m_empresacentrocusto,
        $vertodoslancamentos
    );
    $buscaRow = (is_array($resultBusca) && isset($resultBusca[0])) ? $resultBusca[0] : [];
    $this->smarty->assign('oportunidadeDiario', (int) ($buscaRow['COTACAO_HOJE'] ?? 0));
    $this->smarty->assign('oportunidadePeriodo', (int) ($buscaRow['COTACAO_PERIODO'] ?? 0));

    $this->smarty->assign('resultAcomp', []);
    $this->smarty->assign('idCliente', '');
    $this->smarty->assign('nomeCliente', '');
    $this->smarty->assign('tempClienteOtimizaIcone', '');

    $this->smarty->display('crm_dashboard.tpl');
        
}//fim Mostra
//-------------------------------------------------------------

public function acompanhamentos(){
    $objAcomp = new c_crm_contas_acompanhamento;
    $modo = $this->m_parametro;
    $podeVerOutrosVendedores = $this->verificaDireitoPrograma('CrmDashboardVerOutrosVendedores', 'S');
    $filtroVendedor = $podeVerOutrosVendedores ? null : $this->m_userid;

    if ($modo === 'diario') {
        $hoje = date('d/m/Y');
        $hojeSql = c_date::convertDateBdSh($hoje, $this->m_banco);
        $resultAcomp = $objAcomp->selectAcompanhamentoPeriodo($hojeSql, $hojeSql, $filtroVendedor);
    } elseif ($modo === 'concluido') {
        $dataIniSql = c_date::convertDateBdSh($this->getDataIni(), $this->m_banco);
        $dataFimSql = c_date::convertDateBdSh($this->getDataFim(), $this->m_banco);
        $resultAcomp = $objAcomp->selectAcompanhamentoConcluido($dataIniSql, $dataFimSql, $filtroVendedor);
    } elseif ($modo === 'periodo') {
        $dataIniSql = c_date::convertDateBdSh($this->getDataIni(), $this->m_banco);
        $dataFimSql = c_date::convertDateBdSh($this->getDataFim(), $this->m_banco);
        $resultAcomp = $objAcomp->selectAcompanhamentoPeriodo($dataIniSql, $dataFimSql, $filtroVendedor);
    } elseif (trim((string) $this->m_idCliente) !== '') {
        $resultAcomp = $objAcomp->selectAcompanhamentoPessoa($this->m_idCliente, $filtroVendedor);
    } else {
        $dataIniSql = c_date::convertDateBdSh($this->getDataIni(), $this->m_banco);
        $dataFimSql = c_date::convertDateBdSh($this->getDataFim(), $this->m_banco);
        $resultAcomp = $objAcomp->selectAcompanhamentoPeriodo($dataIniSql, $dataFimSql, $filtroVendedor);
    }

    $resultAcomp = ($resultAcomp) ?: [];
    $nomeClienteFrag = '';

    if (!empty($this->m_idCliente) && !empty($resultAcomp[0])) {
        foreach (['nome_cliente'] as $k) {
            if (!empty($resultAcomp[0][$k])) {
                $nomeClienteFrag = (string) $resultAcomp[0][$k];
                break;
            }
        }
    }
    $this->smarty->assign('resultAcomp', $resultAcomp);
    $this->smarty->assign('idCliente', $this->m_idCliente);
    $this->smarty->assign('nomeCliente', $nomeClienteFrag);
    $this->smarty->assign('tempClienteOtimizaIcone', '');

    $html = $this->smarty->fetch('crm_dashboard_lateral_acompanhamentos.tpl');

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'html' => $html,
        'nomeCliente' => $nomeClienteFrag,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

} //FIM CLASSE

$dashboard = new p_crm_acompanhamento_dashboard();
$dashboard->controle();
?>
