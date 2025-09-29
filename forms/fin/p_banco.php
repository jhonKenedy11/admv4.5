<?php
/**
 * @package   astecv3
 * @name      p_banco
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      23/04/2016
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_banco.php");
include_once($dir."/../../bib/c_tools.php");

//Class p_fin_banco
Class p_banco extends c_bancos {

  public $m_submenu = NULL;
  private $m_letra = NULL;
  public $smarty = NULL;
  public $m_msg = NULL;
  public $m_tipoMsg =NULL;


//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){
        @set_exception_handler(array($this, 'exception_handler'));

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
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

          // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Bancos");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        // $occupation = $_GET['occupation'] ?? 'bricklayer';
        $this->__set('id', $parmPost['id'] ?? '');
        $this->__set('nome', $parmPost['nome'] ?? '');
        
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
			if ($this->verificaDireitoUsuario('FinBanco', 'I')){
				$this->desenhaCadastroBanco();
			}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('FinBanco', 'A')){
				$fin_banco = $this->select_banco();
				$this->__set('id', $fin_banco[0]['BANCO']);
				$this->__set('nome', $fin_banco[0]['NOME']);
				$this->desenhaCadastroBanco();
      }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('FinBanco', 'I')){
				if ($this->existeBanco()){
					$this->m_submenu = "cadastrar";
   				$this->desenhaCadastroBanco("BANCO JÁ EXISTENTE, ALTERE O CÓDIGO DO BANCO", "alerta");}
				else {
					$this->incluiBanco()
          ? $this->mostraBanco(msgAdd.' Banco: '.$this->__get('nome'), typSuccess)
          : $this->desenhaCadastroBanco(msgNotAdd, typError);
        }
      }
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('FinBanco', 'A')){
        $this->alteraBanco() 
        ? $this->mostraBanco(msgUpdate.' Banco: '.$this->__get('nome'), typSuccess)
        : $this->desenhaCadastroBanco(msgNotUpdate, typAlert);
			}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('FinBanco', 'E')){
          $this->excluiBanco()
          ? $this->mostraBanco(msgDelete.' Banco: '.$this->__get('id'), typSuccess)
          : $this->mostraBanco(msgNotDelete.' Banco: '.$this->__get('id'), typAlert);
        }
			break;
		default:
  		if ($this->verificaDireitoUsuario('FinBanco', 'C')){
				$this->mostraBanco('');
  		}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Banco. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroBanco($mensagem=NULL, $tipoMsg = ''){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    
    $this->smarty->assign('id', $this->__get('id'));
    $this->smarty->assign('nome', "'".$this->__get('nome')."'");

    // $this->smarty->assign('id', $this->getId());
    // $this->smarty->assign('nome', "'".$this->getNome()."'");

    $this->smarty->display('banco_cadastro.tpl');
    
}//fim desenhaCadastroBanco

/**
* <b> Listagem de todas as registro cadastrados de tabela banco. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraBanco($mensagem, $tipoMsg = ''){

  
    $lanc = $this->select_banco_geral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);
    $this->smarty->assign('tipoMsg', $tipoMsg);
	
    $this->smarty->display('banco_mostra.tpl');
	

} //fim mostraBanco
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$banco = new p_banco();

                              
$banco->controle();
 
  
?>
