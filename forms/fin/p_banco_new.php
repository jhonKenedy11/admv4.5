<?php
/**
 * @package   adm
 * @name      p_banco_new
 * @version   4.5.00
 * @copyright 2020
 * @link      http://www.admsistema.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admsistema.com.br>
 * @date      01/09/2020
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty3/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_banco.php");
include_once($dir."/../../bib/c_tools.php");
require_once($dir . "/../../bib/admv5.php");

//Class p_fin_banco
Class p_banco extends c_bancos {

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
				// $this->setId($fin_banco[0]['BANCO']);
				// $this->setNome($fin_banco[0]['NOME']);
				$this->desenhaCadastroBanco();
      }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('FinBanco', 'I')){
				if ($this->existeBanco()){
					$this->m_submenu = "cadastrar";
   					$this->desenhaCadastroBanco("BANCO JÁ EXISTENTE, ALTERE O CÓDIGO DO BANCO");}
				else {
					$this->mostraBanco($this->incluiBanco());}
			}		
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('FinBanco', 'A')){
				$this->mostraBanco($this->alteraBanco());
			}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('FinBanco', 'E')){
          $this->mostraBanco($this->excluiBanco());
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
function desenhaCadastroBanco($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    
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
function mostraBanco($mensagem){

  
    $lanc = $this->select_banco_geral();

    // $mensagem = 'Teste';
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('banco_mostra_new.tpl');
	

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
