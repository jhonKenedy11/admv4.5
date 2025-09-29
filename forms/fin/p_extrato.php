<?php
/**
 * @package   astecv3
 * @name      p_extrato
 * @category  PAGES - p_extrato - Lancamento de receitas ou despesas
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admsistema.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      22/05/2016
 */

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_extrato.php");
include_once($dir."/../../bib/c_date.php");


//Class p_extrato
Class p_extrato extends c_extrato {

private $m_submenu = NULL;
private $m_letra = NULL;
private $m_par = NULL;
public $smarty = NULL;
public $genero = NULL;
public $centroCusto = NULL;
public $datavenc = NULL;
public $conta = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';
        $this->m_par = explode("|", $this->m_letra);
        $this->genero = isset($parmPost['genero']) ? $parmPost['genero'] : '';
        $this->centroCusto = isset($parmPost['centrocusto']) ? $parmPost['centrocusto'] : '';
        $this->datavenc = isset($parmPost['datavenc']) ? $parmPost['datavenc'] : '';
        $this->conta = isset($parmPost['conta']) ? $parmPost['conta'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setPessoa(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setPessoaFornecedor(isset($parmPost['fornecedor']) ? $parmPost['fornecedor'] : '');
        $this->setValor(isset($parmPost['valor']) ? $parmPost['valor'] : '');
        $this->setTipoLancamento(isset($parmPost['tipolancamento']) ? $parmPost['tipolancamento'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : '');
        $this->setSituacaoLancamento(isset($parmPost['situacaoLancamento']) ? $parmPost['situacaoLancamento'] : '');
        $this->setGenero(isset($parmPost['genero']) ? $parmPost['genero'] : '');
        $this->setLancamento(isset($parmPost['dataLancamento']) ? $parmPost['dataLancamento'] : '');
        $this->setCompetencia(isset($parmPost['dataCompetencia']) ? $parmPost['dataCompetencia'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');

        // include do javascript
        // include ADMjs . "/fin/s_lancamento.js";
    
}

//---------------------------------------------------------------
//---------------------------------------------------------------
public function buscaCadastroExtrato(){
	$lanc = $this->select_Extrato();
	
	$this->setPessoa($lanc[0]['PESSOA']);
	$this->setPessoaFornecedor($lanc[0]['PESSOAFORNECEDOR']);
	$this->setPessoaNome();
	$this->setPessoaFornecedorNome();
	$this->setTipoLancamento($lanc[0]['TIPOLANCAMENTO']);
	$this->setsituacaoLancamento($lanc[0]['SITUACAOLANCAMENTO']);
	$this->setGenero($lanc[0]['GENERO']);
    $this->setDescGenero();
	$this->setCentroCusto($lanc[0]['CENTROCUSTO']);	
	$this->setLancamento($lanc[0]['LANCAMENTO']);
	$this->setCompetencia($lanc[0]['COMPETENCIA']);
	$this->setValor($lanc[0]['VALOR']);
	$this->setObs($lanc[0]['OBS']);

}
/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
 * @name controle
 * @param VARCHAR submenu 
 * @return vazio
 */
function controle(){
  switch ($this->m_submenu){
        case 'cadastrar':
            if ($this->verificaDireitoUsuario('FinExtrato', 'I')){
                $this->setLancamento(date('Y-m-d'));
                $this->setCompetencia(date('Y-m-d'));
                $this->setCentroCusto($this->m_empresacentrocusto);
                $this->desenhaCadastroExtrato();
            }
            break;
        case 'alterar':
            if ($this->verificaDireitoUsuario('FinExtrato', 'A')){
                    $this->buscaCadastroExtrato();
                    $this->desenhaCadastroExtrato();
            }
            break;
        case 'inclui':
            if ($this->verificaDireitoUsuario('FinExtrato', 'I')){
                    $this->mostraExtrato($this->incluiExtrato());
            }		
            break;
        case 'altera':
           if ($this->verificaDireitoUsuario('FinExtrato', 'A')){
                $this->mostraExtrato($this->alteraExtrato());}
            break;
        case 'exclui':
            if ($this->verificaDireitoUsuario('FinExtrato', 'E')){
                $this->m_ancora = $this->getId();
                $this->mostraExtrato($this->excluiExtrato());}
            break;
        case 'resumo':
            if ($this->verificaDireitoUsuario('FinExtrato', 'S')){
                    $this->mostraResumoExtrato();
            }
            break;
        case 'cadastroresumo':
            if ($this->verificaDireitoUsuario('FinExtrato', 'S')){
                $lanc = $this->select_extrato_resumo($this->m_letra);
                $result = $this->addLancamentoFinanceiro($lanc, $this->m_letra, $this->centroCusto, $this->genero, $this->datavenc, $this->conta);
                $this->mostraExtrato($result);
            }
            break;
        default:
            if ($this->verificaDireitoUsuario('FinExtrato', 'C')){
                $this->mostraExtrato('');}

    }

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Lançamento financeiro. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroExtrato($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('pathCliente', ADMhttpCliente);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('pessoa', $this->getPessoa());
    $this->smarty->assign('pessoaNome', $this->getPessoaNome());
    $this->smarty->assign('fornecedor', $this->getPessoaFornecedor());
    $this->smarty->assign('fornecedorNome', $this->getPessoaFornecedorNome());
    $this->smarty->assign('dataLancamento', $this->getLancamento("F"));
    $this->smarty->assign('dataCompetencia', $this->getCompetencia("F"));

    $valor = $this->getValor("F");
    $this->smarty->assign('valor', ($valor =='0,00') ? '' : $valor);
    $this->smarty->assign('obs', $this->getObs());
    

    // tipo lancamento
    $this->smarty->assign('tipolancamento', $this->getTipoLancamento());

    // genero documento
    $this->smarty->assign('genero', $this->getGenero());	
    $this->smarty->assign('descGenero', $this->getDescGenero());	
    
    $this->smarty->assign('centroCusto', $this->getCentroCusto());


    // situacao lancamento
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoExtrato')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado ?? [];
    for ($i=0; $i < count($result); $i++){
            $situacaoLanc_ids[$i] = $result[$i]['ID'];
            $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
    $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
    $this->smarty->assign('situacaoLanc_id', $this->getSituacaoLancamento());	

    $this->smarty->display('extrato_cadastro.tpl');

}//fim desenhaCadLancamento

/**
* <b> Listagem de todas as registro cadastrados de tabela Lancamentos. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraExtrato($mensagem){

    
    if ($this->m_letra != ''){
    	$lanc = $this->select_extrato_letra($this->m_letra);
    }
	
    $this->setPessoa($this->m_par[3]);
    $this->setPessoaNome();
    $this->smarty->assign('pessoa', $this->getPessoa());
    $this->smarty->assign('nome', $this->getPessoaNome());

    $this->setGenero($this->m_par[4]);
    $this->setDescGenero();
    $this->smarty->assign('genero', $this->getGenero());
    $this->smarty->assign('descGenero', $this->getDescGenero());

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('pathCliente', ADMhttpCliente);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('ancora', $this->m_ancora);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

    $this->smarty->assign('genero', $this->genero);
    $this->smarty->assign('centrocusto', $this->centrocusto);

    // dados para exportacao e relatorios
    $this->smarty->assign('titulo', "Lançamentos Financeiros");
    $this->smarty->assign('colVis', "[ 0,1,2,3,4,5]"); 
    $this->smarty->assign('disableSort', "[ 5 ]"); 
    $this->smarty->assign('numLine', "25"); 
    

    if($this->m_par[1] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
    else $this->smarty->assign('dataIni', $this->m_par[1]);
    
    if($this->m_par[2] == "") {
    	$dia = date("d");
    	$mes = date("m");
    	$ano = date("Y");
    	$data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
    	$this->smarty->assign('dataFim', $data);
    }
    else $this->smarty->assign('dataFim', $this->m_par[2]);
	
    // lista de datas.
    $this->smarty->assign('datas_ids', array('nao','lancamento','competencia'));
    $this->smarty->assign('datas_names', array('N&atilde;o Considera','Lan&ccedil;amento','Competência'));
    if($this->m_par[0] == "") $this->smarty->assign('datas_id', 'competencia');
    else $this->smarty->assign('datas_id', $this->m_par[0]);
    
    // situacao lancamento
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoExtrato')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado ?? [];
    for ($i=0; $i < count($result); $i++){
            $situacaoLanc_ids[$i] = $result[$i]['ID'];
            $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    if (($this->m_par[5] != "") and ($this->m_par[5] != '0')){
        $posSitLanc = 5;
        $i = $posSitLanc + 1;
        //$i++;
        while ($i <= ($this->m_par[$posSitLanc]+$posSitLanc)) {
                $sit[$i-5] = $this->m_par[$i];
                $i++;
        }				
    }
    else {	
            $sit[0] = "A";
    }	
    $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
    $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
    $this->smarty->assign('situacaoLanc_id', $sit);


    // tipo lancamento ##############################
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoLanc')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $tipoLanc_ids[$i] = $result[$i]['ID'];
                $tipoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }

    $posTipoLanc = $posSitLanc + $this->m_par[$posSitLanc] + 1;
    if (($this->m_par[$posTipoLanc] != "") and ($this->m_par[$posTipoLanc] != '0')){
            $i = $posTipoLanc + 1;	
        //     $tipoLanc[$i-$posTipoLanc+1] = $this->m_par[$i];
        //     $i++;
            while ($i <= ($this->m_par[$posTipoLanc]+$posTipoLanc)) {
                    $tipoLanc[$i-$posTipoLanc+1] = $this->m_par[$i];
                    $i++;
            }				
    }
    else {	
            $tipoLanc = $tipoLanc_ids;
    }	

    $this->smarty->assign('tipoLanc_ids', $tipoLanc_ids);
    $this->smarty->assign('tipoLanc_names', $tipoLanc_names);
    $this->smarty->assign('tipoLanc_id', $tipoLanc);


    // calculo totais
    $totalRec = 0;
    $totalPag = 0;
    $lanc = is_array($lanc) ? $lanc : [];
    for ($i=0; $i < count($lanc); $i++){
        if ($lanc[$i]['TIPOLANCAMENTO'] == 'RECEBIMENTO') {
            $totalRec += $lanc[$i]['VALOR'];
        }else {
            $totalPag += $lanc[$i]['VALOR'];
        }
    }
    $this->smarty->assign('totalRec', $totalRec);
    $this->smarty->assign('totalPag', $totalPag);
    $this->smarty->assign('saldo', $totalRec - $totalPag);


    $this->smarty->display('extrato_mostra.tpl');
	

} //fim mostraExtrato
/**
* <b> Listagem do saldo por empresa. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraResumoExtrato(){

    
    if ($this->m_letra != ''){
    	$lanc = $this->select_extrato_resumo($this->m_letra);
    }
	
    $this->setPessoa($this->m_par[3]);
    $this->setPessoaNome();
    $this->smarty->assign('pessoa', $this->getPessoa());
    $this->smarty->assign('nome', $this->getPessoaNome());

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('pathCliente', ADMhttpCliente);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);
    $this->smarty->assign('lancamento', $lanc);
	
    $this->smarty->assign('dataIni', $this->m_par[1]);
    $this->smarty->assign('dataFim', $this->m_par[2]);
    $this->smarty->assign('genero', $this->genero);
    $this->smarty->assign('centrocusto', $this->centroCusto);
    $this->smarty->assign('datavenc', $this->datavenc);

    // centrocusto
    $consulta = new c_banco();
    $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $centrocusto_ids[0] = '';
    $centrocusto_names[0] = '';
    for ($i = 1; $i < count($result); $i++) {
        $centrocusto_ids[$i] = $result[$i]['ID'];
        $centrocusto_names[$i] = $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('centrocusto_ids', $centrocusto_ids);
    $this->smarty->assign('centrocusto_names', $centrocusto_names);
    $this->smarty->assign('centrocusto_id', $this->centroCusto);

    // conta bancaria
    $consulta = new c_banco();
    $sql = "select conta as id, nomeinterno as descricao from fin_conta  where status ='A'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('conta_ids', $conta_ids);
    $this->smarty->assign('conta_names', $conta_names);
    $this->smarty->assign('conta_id', $this->conta);	

    // dados para exportacao e relatorios
    $this->smarty->assign('titulo', "Resumo Lançamentos Financeiros");
    $this->smarty->assign('colVis', "[ 0,1,2,3 ]"); 
    $this->smarty->assign('disableSort', "[ 3 ]"); 
    $this->smarty->assign('numLine', "100"); 
    $consulta = new c_banco();
    $sql = "SELECT genero AS id, descricao FROM fin_genero where tipolancamento='R'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $generoRec_ids[0] = '';
    $generoRec_names[0] = '';
    for ($i=1; $i < count($result); $i++){
            $generoRec_ids[$i] = $result[$i]['ID'];
            $generoRec_names[$i] = $result[$i]['ID']." - ".ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('generoRec_ids', $generoRec_ids);
    $this->smarty->assign('generoRec_names', $generoRec_names);
    $this->smarty->assign('generoRec_id', $this->generoRec);
    
    // genero documento Recebimento
    $consulta = new c_banco();
    $sql = "SELECT genero AS id, descricao FROM fin_genero where tipolancamento='R'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $generoRec_ids[0] = '';
    $generoRec_names[0] = '';
    for ($i=1; $i < count($result); $i++){
            $generoRec_ids[$i] = $result[$i]['ID'];
            $generoRec_names[$i] = $result[$i]['ID']." - ".ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('generoRec_ids', $generoRec_ids);
    $this->smarty->assign('generoRec_names', $generoRec_names);
    $this->smarty->assign('generoRec_id', $this->generoRec);

    // genero documento Pagamento
    $consulta = new c_banco();
    $sql = "SELECT genero AS id, descricao FROM fin_genero where tipolancamento='P'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $generoPag_ids[0] = '';
    $generoPag_names[0] = '';
    for ($i=1; $i < count($result); $i++){
            $generoPag_ids[$i] = $result[$i]['ID'];
            $generoPag_names[$i] = $result[$i]['ID']." - ".ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('generoPag_ids', $generoPag_ids);
    $this->smarty->assign('generoPag_names', $generoPag_names);
    $this->smarty->assign('generoPag_id', $this->generoPag);

    $this->smarty->display('extrato_resumo_mostra.tpl');
	

} //fim mostraExtrato
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$lancamento = new p_extrato();                          


$lancamento->controle();
 
  
?>
