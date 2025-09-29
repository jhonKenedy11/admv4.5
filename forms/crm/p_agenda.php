<?php
/**
 * @package   astecv3
 * @name      p_agenda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      25/05/2017
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/crm/c_contas_acompanhamento.php");

//Class p_agenda
Class p_agenda extends c_contas_acompanhamento {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

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
        $this->m_submenu = $parmPost['submenu'];
        $this->m_letra = $parmPost['letra'];
        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);


        // include do javascript
        // include ADMjs . "/crm/s_agenda.js";

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
				$agenda = $this->select_banco();
				$this->setId($agenda[0]['BANCO']);
				$this->setNome($agenda[0]['NOME']);
				$this->desenhaCadastroBanco();
             // }
			break;
		case 'inclui':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'I')){
				if ($this->existeBanco()){
					$this->m_submenu = "cadastrar";
   					$this->desenhaCadastroBanco("BANCO JÁ EXISTENTE, ALTERE O CÓDIGO DO BANCO");}
				else {
					$this->mostraAgenda($this->incluiBanco());//}
			}		
			break;
		case 'altera':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'A')){
				$this->mostraAgenda($this->alteraBanco());
			//}
			break;
		case 'exclui':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'E')){
                            $this->mostraAgenda($this->excluiBanco());
			//}
			break;
		default:
  			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'C')){
				$this->mostraAgenda('');
  			//}
	
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
    
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('nome', "'".$this->getNome()."'");

    $this->smarty->display('banco_cadastro.tpl');
    
}//fim desenhaCadastroBanco

/**
* <b> Listagem de todas as registro cadastrados de tabela banco. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraAgenda($mensagem){

    $letra = "2016-01-01|2017-06-30|". $this->m_userid;
    $lanc = $this->select_pessoaConsultaAcompanhamento($letra);
    $agenda = "";
    for ($i = 0; $i < count($lanc); $i++) {
        if ($i>0) $agenda .=",";
        $agenda .= "
				{
					id: '".$lanc[$i][NOMEREDUZIDO].
					"',title: '".$lanc[$i][NOMEREDUZIDO].
					"',start: '".$lanc[$i][LIGARDIA]."'}";
    }
    
    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('opcao', 'calendar');
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('agenda', $agenda);

	
    $this->smarty->display('agenda_mostra.tpl');
	

} //fim mostraAgenda
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$agenda = new p_agenda();

                              
$agenda->controle();
 
  
?>
