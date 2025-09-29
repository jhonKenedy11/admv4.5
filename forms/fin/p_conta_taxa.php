<?php
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty3/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_conta_taxa.php");

Class p_conta_taxa extends c_conta_taxa {

private $m_submenu = NULL;
private $m_letra = NULL;
public  $smarty = NULL;

function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

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

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

          // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Conta Taxas");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setConta(isset($parmPost['conta']) ? $parmPost['conta'] : '');
        $this->setCondpgto(isset($parmPost['condpgto']) ? $parmPost['condpgto'] : '');
        $this->setTaxa(isset($parmPost['taxa']) ? $parmPost['taxa'] : '');
        

}

function controle(){
  switch ($this->m_submenu){
		case 'cadastrar':
			if ($this->verificaDireitoUsuario('FinContaTaxa', 'I')){
				$this->desenhaCadastroContaTaxa();
			}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('FinContaTaxa', 'A')){
				$taxa = $this->select_conta_taxa();
				$this->setId($taxa[0]['ID']);
				$this->setConta($taxa[0]['CONTA']);
				$this->setCondpgto($taxa[0]['CONDPGTO']);
				$this->setTaxa($taxa[0]['TAXA']);
				$this->desenhaCadastroContaTaxa();
      }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('FinContaTaxa', 'I')){
				if ($this->existeContaTaxa()){
					$this->m_submenu = "cadastrar";
   					$this->desenhaCadastroContaTaxa("INFORMACAO JÁ EXISTENTE, ALTERE A CONTA");}
				else {
					$this->mostraContaTaxa($this->incluiContaTaxa());}
			}		
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('FinContaTaxa', 'A')){
				$this->mostraContaTaxa($this->alteraContaTaxa());
			}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('FinContaTaxa', 'E')){
          $this->mostraContaTaxa($this->excluiContaTaxa());
			}
			break;
		default:
  		if ($this->verificaDireitoUsuario('FinContaTaxa', 'C')){
				$this->mostraContaTaxa('');
  		}	
	}
} 

function desenhaCadastroContaTaxa($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);

    // conta
    $consulta = new c_banco();
    $sql = "select conta as id, nomeinterno as descricao from fin_conta";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $conta_ids[0] = '';
    $conta_names[0] = 'Selecione o Banco';
    for ($i = 0; $i < count($result); $i++) {
        $conta_ids[$i + 1] = $result[$i]['ID'];
        $conta_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('conta_ids', $conta_ids);
    $this->smarty->assign('conta_names', $conta_names);
    $this->smarty->assign('conta', $this->getConta());

    // condição de pagamento 
    $consulta = new c_banco();
    $sql = "select id, descricao from fat_cond_pgto";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $condpgto_ids[0] = '';
    $condpgto_names[0] = 'Selecione a Cond Pgto';
    for ($i = 0; $i < count($result); $i++) {
        $condpgto_ids[$i + 1] = $result[$i]['ID'];
        $condpgto_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('condpgto_ids', $condpgto_ids);
    $this->smarty->assign('condpgto_names', $condpgto_names); 
    $this->smarty->assign('condpgto', $this->getCondpgto());

    $this->smarty->assign('taxa', "'".$this->getTaxa()."'");
    $this->smarty->assign('id', "'".$this->getId()."'");

    $this->smarty->display('conta_taxa_cadastro.tpl');
    
}

function mostraContaTaxa($mensagem){
    $lanc = $this->select_conta_taxa_geral();

    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);
    $this->smarty->display('conta_taxa_mostra.tpl');
} 

}

$conta_taxa = new p_conta_taxa();

                              
$conta_taxa->controle();
 
  
?>
