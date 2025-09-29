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
include_once($dir."/../../class/cat/c_ordem_servico.php");

//Class p_fin_banco
Class p_ordem_servico extends c_ordem_servico {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct($submenu, $letra){

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
        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Ordem Serviço");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3 ]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // include do javascript
        //include ADMjs . "/fin/s_banco.js";

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
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'I')){
				$this->desenhaCadastroBanco();
			//}
			break;
		case 'alterar':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'A')){
				$fin_banco = $this->select_banco();
				$this->setId($fin_banco[0]['BANCO']);
				$this->setNome($fin_banco[0]['NOME']);
				$this->desenhaCadastroBanco();
             // }
			break;
		case 'inclui':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'I')){
				if ($this->existeBanco()){
					$this->m_submenu = "cadastrar";
   					$this->desenhaCadastroBanco("BANCO JÁ EXISTENTE, ALTERE O CÓDIGO DO BANCO");}
				else {
					$this->mostraBanco($this->incluiBanco());//}
			}		
			break;
		case 'altera':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'A')){
				$this->mostraBanco($this->alteraBanco());
			//}
			break;
		case 'exclui':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'E')){
                            $this->mostraBanco($this->excluiBanco());
			//}
			break;
		default:
  			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'C')){
				$this->mostraBanco('');
  			//}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Banco. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroOs($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('nome', "'".$this->getNome()."'");

    $this->smarty->display('banco_cadastro.tpl');
    
}//fim desenhaCadastroBanco

/**
* <b> Listagem de todas as registro cadastrados de tabela banco. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraOs($mensagem){

  
    $lanc = $this->selectOrdemServiceAberto();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('os_aberto_mostra.tpl');
	

} //fim mostraBanco
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$os = new p_ordem_servico();

                              
$os->mostraOs();
 
  
?>
