<?php
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/fin/c_orcamento.php");

//Class p_orcamento
class p_orcamento extends c_orcamento
{

	private $m_submenu = NULL;
	private $m_letra = NULL;
	public $smarty = NULL;

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	function __construct()
	{

		// Obtain POST data securely using filter_input_array
		$parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		// Initialize session and user data
		session_start();
		c_user::from_array($_SESSION['user_array']);

		// Initialize Smarty template engine
		$this->smarty = new Smarty;
		$this->smarty->template_dir = ADMraizFonte . "/template/fin";
		$this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
		$this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
		$this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

		// Assign path variables to Smarty
		$this->smarty->assign('pathJs', ADMhttpBib . '/js');
		$this->smarty->assign('bootstrap', ADMbootstrap);
		$this->smarty->assign('raizCliente', $this->raizCliente);
		$this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

		// Assign report configuration to Smarty
		$this->smarty->assign('titulo', "Lançamentos Financeiros");
		$this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]");
		$this->smarty->assign('disableSort', "[ 9 ]");
		$this->smarty->assign('numLine', "25");

		// Initialize control variables from POST data
		$this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
		$this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';
		$this->m_par = explode("|", $this->m_letra);

		// Set object properties using POST data with fallbacks
		$this->setMes(isset($parmPost['mes']) ? $parmPost['mes'] : '');
		$this->setAno(isset($parmPost['ano']) ? $parmPost['ano'] : '');
		$this->setCentroCusto(isset($parmPost['filial']) ? $parmPost['filial'] : '');
		$this->setGenero(isset($parmPost['genero']) ? $parmPost['genero'] : '');
		$this->setValor(isset($parmPost['valor']) ? $parmPost['valor'] : '');
	}

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	function controle()
	{
		switch ($this->m_submenu) {
			case 'cadastrar':
				if ($this->verificaDireitoUsuario('FinOrcamento', 'I')) {
					$this->desenhaCadastroOrcamento();
				}
				break;
			case 'alterar':
				if ($this->verificaDireitoUsuario('FinOrcamento', 'A')) {
					$orcamento = $this->select_orcamento_genero();
					$this->setMes($orcamento[0]['MES']);
					$this->setAno($orcamento[0]['ANO']);
					$this->setCentroCusto($orcamento[0]['CENTROCUSTO']);
					$this->setGenero($orcamento[0]['GENERO']);
					$this->setValor($orcamento[0]['VALOR']);
					$this->desenhaCadastroOrcamento();
				}
				break;
			case 'inclui':
				if ($this->verificaDireitoUsuario('FinOrcamento', 'I')) {
					if ($this->existeOrcamentoGenero()) {
						$this->m_submenu = "cadastrar";
						$this->desenhaCadastroOrcamento("Orcamento Já existe nesta data, altere o gnereo, filial ou data");
					} else {
						$this->mostraOrcamento($this->incluiOrcamento());
					}
				}
				break;
			case 'altera':
				if ($this->verificaDireitoUsuario('FinOrcamento', 'A')) {
					$this->mostraOrcamento($this->alteraOrcamento());
				}
				break;
			case 'exclui':
				if ($this->verificaDireitoUsuario('FinOrcamento', 'E')) {
					$this->mostraOrcamento($this->excluiOrcamento($this->m_letra));
				}
				break;
			case 'parametros':
				if ($this->verificaDireitoUsuario('FinOrcamento', 'I')) {
					$this->geraOrcamento('');
				}
				break;
			case 'gerar':
				if ($this->verificaDireitoUsuario('FinOrcamento', 'I')) {
					$resultado = $this->gera_previsao_media($this->m_letra, 12);
					$mensagem = 'Orcamento Gerado com sucesso!';
					$tipoMsg = 'sucesso';
					$this->mostraOrcamento($mensagem, $tipoMsg, $resultado);
				}
				break;
			default:
				if ($this->verificaDireitoUsuario('FinOrcamento', 'C')) {
					$this->mostraOrcamento('');
				}
		}
	} // fim controle

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	function desenhaCadastroOrcamento($mensagem = NULL)
	{

		$this->smarty->assign('pathImagem', $this->img);
		$this->smarty->assign('subMenu', $this->m_submenu);
		$this->smarty->assign('letra', $this->m_letra);
		$this->smarty->assign('mensagem', $mensagem);
		$this->smarty->assign('valor', $this->getValor('F'));

		if ($this->getAno() == "") $this->smarty->assign('ano', $this->m_par[1]);
		else $this->smarty->assign('ano', $this->getAno());

		//combobox  Mes base
		$mesBase_ids[0] = 0;
		$mesBase_names[0] = 'Selecionar';
		$mesBase_ids[1] = 1;
		$mesBase_names[1] = 'Janeiro';
		$mesBase_ids[2] = 2;
		$mesBase_names[2] = 'Fevereiro';
		$mesBase_ids[3] = 3;
		$mesBase_names[3] = 'Mar&ccedil;o';
		$mesBase_ids[4] = 4;
		$mesBase_names[4] = 'Abril';
		$mesBase_ids[5] = 5;
		$mesBase_names[5] = 'Maio';
		$mesBase_ids[6] = 6;
		$mesBase_names[6] = 'Junho';
		$mesBase_ids[7] = 7;
		$mesBase_names[7] = 'Julho';
		$mesBase_ids[8] = 8;
		$mesBase_names[8] = 'Agosto';
		$mesBase_ids[9] = 9;
		$mesBase_names[9] = 'Setembro';
		$mesBase_ids[10] = 10;
		$mesBase_names[10] = 'Outubro';
		$mesBase_ids[11] = 11;
		$mesBase_names[11] = 'Novembro';
		$mesBase_ids[12] = 12;
		$mesBase_names[12] = 'Dezembro';
		$this->smarty->assign('mesBase_ids', $mesBase_ids);
		$this->smarty->assign('mesBase_names', $mesBase_names);
		if ($this->getMes() == "") $mesBase[0] = $this->m_par[0];
		else $mesBase[0] = $this->getMes();
		$this->smarty->assign('mesBase_id', $mesBase);

		// filial
		$consulta = new c_banco();
		$sql = "SELECT centrocusto as id, descricao FROM fin_centro_custo WHERE (ativo='S')";
		$consulta->exec_sql($sql);
		$consulta->close_connection();
		$result = $consulta->resultado;
		$filial_ids[0] = "";
		$filial_names[0] = "Selecionar";
		for ($i = 0; $i < count($result); $i++) {
			$filial_ids[$i + 1] = $result[$i]['ID'];
			$filial_names[$i + 1] = $result[$i]['DESCRICAO'];
		}
		$this->smarty->assign('filial_ids', $filial_ids);
		$this->smarty->assign('filial_names', $filial_names);
		$this->smarty->assign('filial_id', $this->getCentroCusto());


		// GENERO
		$consulta = new c_banco();
		$sql = "SELECT genero AS id, descricao FROM fin_genero ORDER BY id";
		$consulta->exec_sql($sql);
		$consulta->close_connection();
		$result = $consulta->resultado;
		for ($i = 0; $i < count($result); $i++) {
			$genero_ids[$i] = $result[$i]['ID'];
			$genero_names[$i] = $result[$i]['DESCRICAO'];
		}
		$this->smarty->assign('genero_ids', $genero_ids);
		$this->smarty->assign('genero_names', $genero_names);
		$this->smarty->assign('genero_id', $this->getGenero());


		$this->smarty->display('orcamento_cadastro.tpl');
	} //fim desenhaCadastroOrcamento

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	function geraOrcamento($mensagem)
	{


		$this->smarty->assign('pathImagem', $this->img);
		$this->smarty->assign('mensagem', $mensagem);
		$this->smarty->assign('letra', $this->m_letra);
		$this->smarty->assign('subMenu', $this->m_submenu);
		$this->smarty->assign('anoBase', date("Y"));


		$consulta = new c_banco();
		$sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
		$consulta->exec_sql($sql);
		$consulta->close_connection();
		$result = $consulta->resultado;
		$filial_ids[0] = '';
		$filial_names[0] = '';
		for ($i = 0; $i < count($result); $i++) {
			$filial_ids[$i + 1] = $result[$i]['ID'];
			$filial_names[$i + 1] = $result[$i]['DESCRICAO'];
		}
		$this->smarty->assign('filial_ids', $filial_ids);
		$this->smarty->assign('filial_names', $filial_names);

		if ($this->m_par[2] == "")
			$this->smarty->assign('filial_id', '');
		else
			$this->smarty->assign('filial_id', $this->m_par[2]);

		//combobox  Mes base
		$mesBase_ids[0] = 0;
		$mesBase_names[0] = '';
		$mesBase_ids[1] = 1;
		$mesBase_names[1] = 'Janeiro';
		$mesBase_ids[2] = 2;
		$mesBase_names[2] = 'Fevereiro';
		$mesBase_ids[3] = 3;
		$mesBase_names[3] = 'Mar&ccedil;o';
		$mesBase_ids[4] = 4;
		$mesBase_names[4] = 'Abril';
		$mesBase_ids[5] = 5;
		$mesBase_names[5] = 'Maio';
		$mesBase_ids[6] = 6;
		$mesBase_names[6] = 'Junho';
		$mesBase_ids[7] = 7;
		$mesBase_names[7] = 'Julho';
		$mesBase_ids[8] = 8;
		$mesBase_names[8] = 'Agosto';
		$mesBase_ids[9] = 9;
		$mesBase_names[9] = 'Setembro';
		$mesBase_ids[10] = 10;
		$mesBase_names[10] = 'Outubro';
		$mesBase_ids[11] = 11;
		$mesBase_names[11] = 'Novembro';
		$mesBase_ids[12] = 12;
		$mesBase_names[12] = 'Dezembro';
		$this->smarty->assign('mesBase_ids', $mesBase_ids);
		$this->smarty->assign('mesBase_names', $mesBase_names);
		if ($this->m_par[0] == "")
			$mesBase[0] = date("m");
		else
			$mesBase[0] = $this->m_par[0];
		$this->smarty->assign('mesBase_id', $mesBase);

		//combobox Mes trabalho
		$media_ids[0] = 0;
		$media_names[0] = '';
		$media_ids[1] = 1;
		$media_names[1] = 'Ultimo mes';
		$media_ids[2] = 3;
		$media_names[2] = 'M&eacute;dia 3 meses';
		$media_ids[3] = 12;
		$media_names[3] = 'M&eacute;dia 12 meses';
		$this->smarty->assign('media_ids', $media_ids);
		$this->smarty->assign('media_names', $media_names);
		$this->smarty->assign('media_id', 0);

		$this->smarty->display('orcamento_gerar.tpl');
	} //fim geraOrcamento
	//---------------------------------------------------------------
	//---------------------------------------------------------------
	function mostraOrcamento($mensagem)
	{

		if ($this->m_letra != '') {
			$lanc = $this->select_orcamento_letra($this->m_letra);
			$orcCPG = $this->select_orcamento_total($this->m_letra, "CPG");
			$finCPG = $this->select_financeiro_total($this->m_letra, "CPG");
		}
		$orcCPG = $orcCPG ?? [];

		$this->smarty->assign('pathImagem', $this->img);
		$this->smarty->assign('mensagem', $mensagem);
		$this->smarty->assign('letra', $this->m_letra);
		$this->smarty->assign('subMenu', $this->m_submenu);
		$this->smarty->assign('lanc', $lanc);
		$this->smarty->assign('anoTrabalho', date("Y"));
		$this->smarty->assign('anoBase', date("Y"));
		$this->smarty->assign('linhas', count($orcCPG));
		$this->smarty->assign('orcCPG', $orcCPG);
		$this->smarty->assign('finCPG', $finCPG);


		//echo "passou 1".$this->m_letra.$this->m_par[0];

		// combobox filial
		$consulta = new c_banco();
		$sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
		$consulta->exec_sql($sql);
		$consulta->close_connection();
		$result = $consulta->resultado;
		$filial_ids[0] = '';
		$filial_names[0] = 'Todos';
		for ($i = 0; $i < count($result); $i++) {
			$filial_ids[$i + 1] = $result[$i]['ID'];
			$filial_names[$i + 1] = $result[$i]['DESCRICAO'];
		}
		$this->smarty->assign('filial_ids', $filial_ids);
		$this->smarty->assign('filial_names', $filial_names);
		if ($this->m_par[2] == "") $this->smarty->assign('filial_id', '');
		else $this->smarty->assign('filial_id', $this->m_par[2]);

		//combobox  Mes base
		$mesBase_ids[0] = 0;
		$mesBase_names[0] = 'Selecionar';
		$mesBase_ids[1] = 1;
		$mesBase_names[1] = 'Janeiro';
		$mesBase_ids[2] = 2;
		$mesBase_names[2] = 'Fevereiro';
		$mesBase_ids[3] = 3;
		$mesBase_names[3] = 'Mar&ccedil;o';
		$mesBase_ids[4] = 4;
		$mesBase_names[4] = 'Abril';
		$mesBase_ids[5] = 5;
		$mesBase_names[5] = 'Maio';
		$mesBase_ids[6] = 6;
		$mesBase_names[6] = 'Junho';
		$mesBase_ids[7] = 7;
		$mesBase_names[7] = 'Julho';
		$mesBase_ids[8] = 8;
		$mesBase_names[8] = 'Agosto';
		$mesBase_ids[9] = 9;
		$mesBase_names[9] = 'Setembro';
		$mesBase_ids[10] = 10;
		$mesBase_names[10] = 'Outubro';
		$mesBase_ids[11] = 11;
		$mesBase_names[11] = 'Novembro';
		$mesBase_ids[12] = 12;
		$mesBase_names[12] = 'Dezembro';
		$this->smarty->assign('mesBase_ids', $mesBase_ids);
		$this->smarty->assign('mesBase_names', $mesBase_names);
		if ($this->m_par[0] == "") $mesBase[0] = date("m");
		else $mesBase[0] = $this->m_par[0];

		$this->smarty->assign('mesBase_id', $mesBase);

		//combobox Mes trabalho
		$media_ids[0] = 0;
		$media_names[0] = 'Selecionar';
		$media_ids[1] = 1;
		$media_names[1] = 'Ultimo mes';
		$media_ids[2] = 3;
		$media_names[2] = 'M�dia 3 meses';
		$media_ids[3] = 12;
		$media_names[3] = 'M�dia 12 meses';
		$this->smarty->assign('media_ids', $media_ids);
		$this->smarty->assign('media_names', $media_names);
		$this->smarty->assign('media_id', 0);
		$this->smarty->display('orcamento_mostra.tpl');
	} //fim mostraOrcamento
	//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$orcamento = new p_orcamento();


$orcamento->controle();
