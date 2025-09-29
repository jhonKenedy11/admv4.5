<?php

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_orcamento.php");


//Class p_pedido_orcamento
Class p_pedido_orcamento extends c_pedido_orcamento {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_par          = NULL;
    public $smarty          = NULL;

	/**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */

//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct (){

	// Cria uma instancia variaveis de sessao
	session_start();
	c_user::from_array($_SESSION['user_array']);
	
	//Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
	$parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

	// Cria uma instancia do Smarty
	$this->smarty = new Smarty;

	// caminhos absolutos para todos os diretorios do Smarty
	$this->smarty->template_dir = ADMraizFonte . "/template/ped";
	$this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
	$this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
	$this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";
	
	// caminhos absolutos para todos os diretorios biblioteca e sistema
	$this->smarty->assign('pathJs',  ADMhttpBib.'/js');
	$this->smarty->assign('bootstrap', ADMbootstrap);
	$this->smarty->assign('raizCliente', $this->raizCliente);
	
	// inicializa variaveis de controle
	$this->m_submenu = $parmPost['submenu'];
	$this->m_letra = $parmPost['letra'];
	$this->m_par = explode("|", $this->m_letra);

	// dados para exportacao e relatorios
	$this->smarty->assign('titulo', "Planejamento Orcamentario");
	$this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
	$this->smarty->assign('disableSort', "[ 5 ]");
	$this->smarty->assign('numLine', "25");	

	//$this->m_submenu= (isset($this->parmPost['submenu']) ? $this->parmPost['submenu'] : '');
	$this->m_letra= (isset($this->parmPost['letra']) ? $this->parmPost['letra'] : '');
	$this->m_opcao= (isset($this->parmPost['opcao']) ? $this->parmPost['opcao'] : '');
	
}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
  switch ($this->m_submenu){
		case 'cadastrar':
			if ($this->verificaDireitoUsuario('FinOrcamento', 'I')){
				$this->desenhaCadastroOrcamento();
			}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('FinOrcamento', 'A')){
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
			if ($this->verificaDireitoUsuario('FinOrcamento', 'I')){
  				if ($this->existeOrcamentoGenero()){
					$this->m_submenu = "cadastrar";
   					$this->desenhaCadastroOrcamento("OR&ccedil;AMENTO J� EXISTENTE NESTA DATA, ALTERE GENERO, FILIAL OU A DATA");}
				else {
					$this->mostraOrcamento($this->incluiOrcamento());;}
				
			}		
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('FinOrcamento', 'A')){
				$this->mostraOrcamento($this->alteraOrcamento());
			}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('FinOrcamento', 'E')){
                            $this->mostraOrcamento($this->excluiOrcamento($this->m_letra));
			}
			break;
		case 'parametros':
			if ($this->verificaDireitoUsuario('FinOrcamento', 'I')){
				$this->geraOrcamento();
			}
			break;
		case 'gerar':
			if ($this->verificaDireitoUsuario('FinOrcamento', 'I')){
				

/*                            
				$mesAnterior = $this->select_data_genero($this->m_letra);
				for ($i=0; $i < count($mesAnterior); $i++){
					$this->setMes($this->m_par[2]);
					$this->setAno($this->m_par[3]);
					$this->setCentroCusto($mesAnterior[$i]['CENTROCUSTO']);
					$this->setGenero($mesAnterior[$i]['GENERO']);
					$this->setValor($mesAnterior[$i]['VALOR']);
					$this->incluiOrcamento();
				}
*/				$this->mostraOrcamento($this->gera_previsao_media($this->m_letra, 12));
			}
			break;
		default:
  			if ($this->verificaDireitoUsuario('FINORCAMENTO', 'C')){
				$this->mostraOrcamento('');
  			}
	
	}

} // fim controle

//---------------------------------------------------------------
//---------------------------------------------------------------
function desenhaCadastroOrcamento($mensagem=NULL){
//include $this->js."/fin/s_orcamento.js";

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('valor', $this->getValor());
        
    if($this->getAno() == "") $this->smarty->assign('ano', $this->m_par[1]);
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
        if($this->getMes() == "") $mesBase[0] = $this->m_par[0];
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
	for ($i=0; $i < count($result); $i++){
		$filial_ids[$i+1] = $result[$i]['ID'];
		$filial_names[$i+1] = $result[$i]['DESCRICAO'];
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
  	for ($i=0; $i < count($result); $i++){
		$genero_ids[$i] = $result[$i]['ID'];
		$genero_names[$i] = $result[$i]['DESCRICAO'];
	}
	$this->smarty->assign('genero_ids', $genero_ids);
	$this->smarty->assign('genero_names', $genero_names);
        $this->smarty->assign('genero_id', $this->getGenero());
	
    
	$this->smarty->display('pedido_orcamento_cadastro.tpl');
    
}//fim desenhaCadastroOrcamento

//---------------------------------------------------------------
//---------------------------------------------------------------
function geraOrcamento($mensagem){
//include $this->js."/fin/s_orcamento.js";

	
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('anoBase', date("Y"));

    
    //echo "passou 1".$this->m_letra.$this->m_par[0];
    
    // combobox filial
  	$consulta = new c_banco();
  	$sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
  	$consulta->exec_sql($sql);
	$consulta->close_connection();
  	$result = $consulta->resultado;
	$filial_ids[0] = '';
	$filial_names[0] = '';
  	for ($i=0; $i < count($result); $i++){
		$filial_ids[$i+1] = $result[$i]['ID'];
		$filial_names[$i+1] = $result[$i]['DESCRICAO'];
	}
	$this->smarty->assign('filial_ids', $filial_ids);
	$this->smarty->assign('filial_names', $filial_names);
    if($this->m_par[2] == "") $this->smarty->assign('filial_id', '');
    else $this->smarty->assign('filial_id', $this->m_par[2]);	
    
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
        if($this->m_par[0] == "") $mesBase[0] = date("m");
        else $mesBase[0] = $this->m_par[0];	
        $this->smarty->assign('mesBase_id', $mesBase);	
    
    //combobox Mes trabalho
	$media_ids[0] = 0;
	$media_names[0] = '';
	$media_ids[1] = 1;
	$media_names[1] = 'Ultimo mes';
	$media_ids[2] = 3;
	$media_names[2] = 'Media 3 meses';
	$media_ids[3] = 12;
	$media_names[3] = 'Media 12 meses';
	$this->smarty->assign('media_ids', $media_ids);
	$this->smarty->assign('media_names', $media_names);
        $this->smarty->assign('media_id', 0);	
	//$this->smarty->display('pedido_orcamento_gerar.tpl');
	

} //fim geraOrcamento
//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraOrcamento($mensagem){
//include $this->js."/fin/s_orcamento.js";
	$orcCPG = [];

	if ($this->m_letra != ''){
		$lanc = $this->select_orcamento_letra($this->m_letra);	
		$orcCPG = $this->select_orcamento_total($this->m_letra, "CPG");	
		//$finCPG = $this->select_financeiro_total($this->m_letra, "CPG");	
	}
	
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
  	for ($i=0; $i < count($result); $i++){
		$filial_ids[$i+1] = $result[$i]['ID'];
		$filial_names[$i+1] = $result[$i]['DESCRICAO'];
	}
	$this->smarty->assign('filial_ids', $filial_ids);
	$this->smarty->assign('filial_names', $filial_names);
        if($this->m_par[2] == "") $this->smarty->assign('filial_id', '');
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
        if($this->m_par[0] == "") $mesBase[0] = date("m");
        else $mesBase[0] = $this->m_par[0];	
        
    $this->smarty->assign('mesBase_id', $mesBase);	
    
    //combobox Mes trabalho
	$media_ids[0] = 0;
	$media_names[0] = 'Selecionar';
	$media_ids[1] = 1;
	$media_names[1] = 'Ultimo mes';
	$media_ids[2] = 3;
	$media_names[2] = 'Media 3 meses';
	$media_ids[3] = 12;
	$media_names[3] = 'Media 12 meses';

	$this->smarty->assign('media_ids', $media_ids);
	$this->smarty->assign('media_names', $media_names);
    $this->smarty->assign('media_id', 0);	
	$this->smarty->display('pedido_orcamento_mostra.tpl');
	

} //fim mostraOrcamento
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$orcamento = new p_pedido_orcamento();

// if (isset($_POST['mes'])) { $orcamento->setMes($_POST['mes']); } else {$orcamento->setMes('');};
// if (isset($_POST['ano'])) { $orcamento->setAno($_POST['ano']); } else {$orcamento->setAno('');};
// if (isset($_POST['filial'])) { $orcamento->setcentroCusto($_POST['filial']); } else {$orcamento->setCentroCusto('');};
// if (isset($_POST['genero'])) { $orcamento->setGenero($_POST['genero']); } else {$orcamento->setGenero('');};
// if (isset($_POST['valor'])) { $orcamento->setValor($_POST['valor']); } else {$orcamento->setValor('');};


$orcamento->controle();


 
  
?>
