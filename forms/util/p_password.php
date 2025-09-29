<?php
/**
 * @package   astecv3
 * @name      p_password
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
include_once($dir."/../../class/util/c_usuario.php");

//Class p_fin_usuario
Class p_password extends c_usuario {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;

private $usuario= NULL;
private $senha= NULL;
private $senhaAnt= NULL;
private $novaSenha= NULL;
private $confirmacaoSenha= NULL;


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
        $this->smarty->template_dir = ADMraizFonte . "/template/util";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_opcao = isset($parmPost['opcao']) ? $parmPost['opcao'] : '';

                // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');

        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // metodo SET dos dados do FORM para o TABLE
        $this->usuario= $this->m_userid;
        $this->senha= $this->m_usersenha;
        $this->senhaAnt=strtoupper(isset($parmPost['senhaAnt']) ? $parmPost['senhaAnt'] : '');
        $this->novaSenha=strtoupper(isset($parmPost['novaSenha']) ? $parmPost['novaSenha'] : '');
        $this->confirmacaoSenha=strtoupper(isset($parmPost['confirmacaoSenha']) ? $parmPost['confirmacaoSenha'] : '');
        

        // include do javascript
        // include ADMjs . "/util/s_util.js";

}

/**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
function controle(){
  $msg = '';
  switch ($this->m_submenu){
		case 'altera':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'A')){
                            if ($this->senha != $this->senhaAnt):
                                $msg = "Senha atual INCORRETA";
                            else:
                                if (($this->novaSenha != $this->confirmacaoSenha) or ($this->novaSenha =='')):
                                    $msg = "Nova senha não confere, digite a nova senha identica na confirmação";
                                else:
                                    if (($this->m_opcao == 'password') and ($this->m_submenu == 'password'))
                                        $msg = $this->alteraPasswordPessoa($this->usuario, $this->novaSenha);
                                    else    
                                        $msg = $this->alteraPasswordUsuario($this->usuario, $this->novaSenha);
                                endif;
                            endif;
			//}
		default:
  			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'C')){
				$this->desenhaAlteraPassword($msg);
  			//}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Password. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaAlteraPassword($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    
    $this->smarty->display('password_altera.tpl');
    
}//fim desenhaCadastroPassword

/**
* <b> Listagem de todas as registro cadastrados de tabela usuario. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraPassword($mensagem){

  
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('password_mostra.tpl');
	

} //fim mostraPassword
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$usuario = new p_password();

                              
$usuario->controle();
 
  
?>
