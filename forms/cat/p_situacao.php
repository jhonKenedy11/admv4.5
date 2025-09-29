<?php
/**
 * @package   admv4.3.1
 * @name      p_situacao
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
include_once($dir."/../../class/cat/c_situacao.php");
include_once($dir."/../../bib/c_tools.php");

//Class p_cat_situacao
Class p_situacao extends c_situacao {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
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
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
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
        $this->smarty->assign('titulo', "situacao");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        // $occupation = $_GET['occupation'] ?? 'bricklayer';
        $this->__set('ID', $parmPost['id'] ?? '');
        $this->__set('DESCRICAO', $parmPost['descricao'] ?? '');
        $this->__set('ATIVO', $parmPost['ativo'] ?? '');
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
			if ($this->verificaDireitoUsuario('CatSituacao', 'I')){
				$this->desenhaCadastroSituacao();
			}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('CatSituacao', 'A')){
        $this->set_situacao();
				$this->desenhaCadastroSituacao();
      }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('CatSituacao', 'I')){
					$this->mostraSituacao($this->incluiSituacao());
			}		
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('CatSituacao', 'A')){
				$this->mostraSituacao($this->alteraSituacao());
			}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('CatSituacao', 'E')){
          $this->mostraSituacao($this->excluiSituacao());
			}
			break;
		default:
  		if ($this->verificaDireitoUsuario('CatSituacao', 'C')){
				$this->mostraSituacao('');
  		}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Situacao. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroSituacao($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    
    $this->smarty->assign('id', $this->__get('ID'));
    $this->smarty->assign('descricao', "'".$this->__get('DESCRICAO')."'");
    $this->smarty->assign('ativo', "'".$this->__get('ATIVO')."'");


    $this->smarty->display('situacao_cadastro.tpl');
    
}//fim desenhaCadastroSituacao

/**
* <b> Listagem de todas as registro cadastrados de tabela situacao. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraSituacao($mensagem){

  
    $lanc = $this->select_situacao_geral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('situacao_mostra.tpl');
	

} //fim mostraSituacao
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$form = new p_situacao();

                              
$form->controle();
 
  
?>
