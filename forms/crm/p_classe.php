<?php
/**
 * @package   astec
 * @name      p_classe
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      11/04/2016
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/crm/c_classe.php");



//Class p_classe
Class p_classe extends c_classe {

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
        $this->smarty->template_dir = ADMraizFonte . "/template/crm";
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
        $this->smarty->assign('titulo', "Classe");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3 ]"); 
        $this->smarty->assign('disableSort', "[ 3 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setClasse(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        $this->setBloqueado($parmPost['bloqueado'] == true ? 'S' : 'N');

        // include do javascript
        // include ADMjs . "/crm/s_classe.js";
  
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
			if ($this->verificaDireitoUsuario('FinClasse', 'I')){
				$this->desenhaCadastroClasse();
			}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('FinClasse', 'A')){
                            $this->busca_classe();
                            $this->desenhaCadastroClasse();
                        }
			break;
		case 'inclui':
                        if($this->verificaDireitoUsuario('FinClasse', 'I')){
                                if ($this->existeClasse()){
                                        $this->m_submenu = "cadastrar";
                                        $this->desenhaCadastroClasse("CLASSE JÁ EXISTE, ALTERE O CÓDIGO DA CLASSE.", "alerta");}
                        else {
                                $this->incluiClasse()
                                ? $this->mostraClasse(msgAdd.' ==> Classe: '.$this->__get('descricao'), typSuccess)
                                : $this->desenhaCadastroClasse(msgNotAdd, typError);
                                }
                        }
			break;
		case 'altera':
                        if($this->verificaDireitoUsuario('FinClasse', 'A')){
                                $this->alteraClasse()
                                ? $this->mostraClasse(msgUpdate.' ==> Classe: '.$this->__get('descricao'), typSuccess)
				: $this->desenhaCadastroClasse(msgNotUpdate, typAlert);
                        }
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('FinClasse', 'E')){
                            $this->excluiClasse()
                            ? $this->mostraClasse(msgDelete.' ==> Classe: '.$this->__get('descricao'), typSuccess)
                            : $this->mostraClasse(msgNotDelete.' ==> Classe: '.$this->__get('descricao'), typAlert);
			}
			break;
		default:
  			if ($this->verificaDireitoUsuario('FinClasse', 'C')){
				$this->mostraClasse('');
  			}
	
	}

} // fim controle

 /**
     * <b> Desenha cadastro Atividade. </b>
     * @param String $mensagem mensagem que ira apresentar na tela
     * @param String $tipoMsg tipo da mensagem sucesso/alerta
     */
function desenhaCadastroClasse($mensagem = NULL, $tipoMsg = ''){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    $this->smarty->assign('id', $this->getClasse());
    $this->smarty->assign('descricao',"'".$this->getDescricao()."'");
    $this->smarty->assign('bloqueado', $this->getBloqueado());
    $this->smarty->display('classe_cadastro.tpl');
    
}//fim desenhaCadastroClasse

  /**
     * <b> Listagem das Atividade. </b>
     * @param String $mensagem Mensagem que ira mostrar na tela
     */
function mostraClasse($mensagem, $tipoMsg = ''){

   
    $lanc = $this->select_classe_geral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('classe_mostra.tpl');
	

} //fim mostraClasse
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$classe = new p_classe();

$classe->controle();
 
  
?>
