<?php
/**
 * @package   admv4.3.1
 * @name      p_equipamento
 * @version   4.3.01
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      22/02/2021
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/cat/c_equipamento.php");
include_once($dir."/../../bib/c_tools.php");

//Class p_cat_equipamento
Class p_equipamento extends c_equipamento {

private $m_submenu = NULL;
private $m_letra = NULL;
private $m_opcao = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->m_origem=(isset($parmGet['origem']) ? $parmGet['origem'] : (isset($parmPost['origem']) ? $parmPost['origem'] : ''));

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        
          // dados para exportacao e relatorios
          if($this->m_opcao == 'pesquisar'){
            $this->smarty->assign('titulo', "Pesquisa Equipamentos");
            $this->smarty->assign('colVis', "[ 0, 1 ]"); 
            $this->smarty->assign('disableSort', "[ 2 ]"); 
            $this->smarty->assign('numLine', "25"); 

          }else{
            $this->smarty->assign('titulo', "Equipamento");
            $this->smarty->assign('colVis', "[ 0, 1 ]"); 
            $this->smarty->assign('disableSort', "[ 2 ]"); 
            $this->smarty->assign('numLine', "25"); 

          }
        
        // metodo SET dos dados do FORM para o TABLE
        // $occupation = $_GET['occupation'] ?? 'bricklayer';
        $this->__set('ID', $parmPost['id'] ?? '');
        $this->__set('DESCRICAO', $parmPost['descricao'] ?? '');
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
			if ($this->verificaDireitoUsuario('CatEquipamento', 'I')){
				$this->desenhaCadastroEquipamento();
			}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('CatEquipamento', 'A')){
        $this->set_equipamento();
				$this->desenhaCadastroEquipamento();
      }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('CatEquipamento', 'I')){
          $result = $this->incluiEquipamento();
        
          //Validação de para setar o produto cadastrado
          if ($this->m_origem == 'pesquisaEquipamento'){
				    $this->mostraEquipamento('',$result);
          } else { 
            $this->mostraEquipamento(''); 

          } 
			}		
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('CatEquipamento', 'A')){
				$this->mostraEquipamento($this->alteraEquipamento());
			}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('CatEquipamento', 'E')){
          $this->mostraEquipamento($this->excluiEquipamento());
			}
			break;
		default:
  		if ($this->verificaDireitoUsuario('CatEquipamento', 'C')){
				$this->mostraEquipamento('');
  		}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Equipamento. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroEquipamento($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('origem', $this->m_origem);
    
    $this->smarty->assign('id', $this->__get('ID'));
    $this->smarty->assign('descricao', "'".$this->__get('DESCRICAO')."'");


    $this->smarty->display('equipamento_cadastro.tpl');
    
}//fim desenhaCadastroEquipamento

/**
* <b> Listagem de todas as registro cadastrados de tabela equipamento. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/

function mostraEquipamento($mensagem, $id=null)
{
  //Validação de para setar o produto cadastrado
    if($id != null){
      $this->__set('ID', $id);
      $lanc = $this->select_equipamento();
      $this->m_opcao = 'pesquisar';
    }else{
      $lanc = $this->select_equipamento_geral();
    }

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);  
    $this->smarty->assign('origem', $this->m_origem);

    switch ($this->m_opcao){
      case "pesquisar": 
        $this->smarty->display('equipamento_pesquisa_mostra.tpl');
      break; 
      default:
        $this->smarty->display('equipamento_mostra.tpl');

    }
	

} //fim mostraEquipamento
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$form = new p_equipamento();

                              
$form->controle();
 
  
?>
