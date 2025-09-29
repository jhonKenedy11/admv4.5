<?php
/**
 * @package   admv4.3.1
 * @name      p_servico
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
include_once($dir."/../../class/cat/c_servico.php");
include_once($dir."/../../bib/c_tools.php");

//Class p_cat_servico
Class p_servico extends c_servico {

private $m_submenu = NULL;
private $m_letra = NULL;
private $m_opcao = null;
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

          // dados para exportacao e relatorios
        if($this->m_opcao !== 'pesquisar'){
          $this->smarty->assign('titulo', "Servico");
          $this->smarty->assign('colVis', "[ 0, 1 ]"); 
          $this->smarty->assign('disableSort', "[ 2 ]"); 
          $this->smarty->assign('numLine', "25");  
        }
        

        // metodo SET dos dados do FORM para o TABLE
        // $occupation = $_GET['occupation'] ?? 'bricklayer';
        $this->__set('ID', $parmPost['id'] ?? '');
        $this->__set('DESCRICAO', $parmPost['descricao'] ?? '');
        $this->__set('UNIDADE', $parmPost['unidade'] ?? '');
        $this->__set('QUANTIDADE', $parmPost['quantidade'] ?? '');
        $this->__set('VALORUNITARIO', $parmPost['valorunitario'] ?? '');
        $this->__set('STATUS', $parmPost['status'] ?? '');
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
			if ($this->verificaDireitoUsuario('CatServico', 'I')){
				$this->desenhaCadastroServico();
			}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('CatServico', 'A')){
        $this->set_servico();
				$this->desenhaCadastroServico();
      }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('CatServico', 'I')){
					$this->mostraServico($this->incluiServico());
			}		
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('CatServico', 'A')){
				$this->mostraServico($this->alteraServico());
			}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('CatServico', 'E')){
          $this->mostraServico($this->excluiServico());
			}
			break;
		default:
  		if ($this->verificaDireitoUsuario('CatServico', 'C')){
				$this->mostraServico('');
  		}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Servico. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroServico($mensagem=NULL){

  $this->smarty->assign('pathImagem', $this->img);
  $this->smarty->assign('subMenu', $this->m_submenu);
  $this->smarty->assign('letra', $this->m_letra);
  $this->smarty->assign('mensagem', $mensagem);
  $this->smarty->assign('origem', $this->m_origem);
  $this->smarty->assign('opcao', $this->m_opcao);

  $this->smarty->assign('id', $this->__get('ID'));
  $this->smarty->assign('descricao', "'".$this->__get('DESCRICAO')."'");
  $this->smarty->assign('unidade', "'".$this->__get('UNIDADE')."'");
  $this->smarty->assign('quantidade', "'".$this->__get('QUANTIDADE')."'");
  $this->smarty->assign('valorunitario', "'".$this->__get('VALORUNITARIO')."'");
  $this->smarty->assign('status', $this->__get('STATUS'));

  $this->smarty->display('servico_cadastro.tpl');
    
}//fim desenhaCadastroServico

/**
* <b> Listagem de todas as registro cadastrados de tabela servico. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraServico($mensagem){


    if ($this->m_opcao == 'pesquisar') {
      $status = 1; // Mostra apenas ativos na pesquisa
    } else {
      $status = NULL; // Mostra todos (ativos e inativos)
    }

    $lanc = $this->select_servico_geral($status);
    

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);
    $this->smarty->assign('opcao', $this->m_opcao);

    $this->smarty->assign('origem', $this->m_origem);

    switch ($this->m_opcao){
      case "pesquisar": 
        $this->smarty->display('servico_pesquisa_mostra.tpl');
      break;  
      default:
      $this->smarty->display('servico_mostra.tpl');
      
    }     
	
	

} //fim mostraServico
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$form = new p_servico();

                              
$form->controle();
 
  
?>
