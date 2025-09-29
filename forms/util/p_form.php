<?php

/****************************************************************************
*Cliente...........:
*Contratada........: ADMService
*Desenvolvedor.....: Lucas Tortola da Silva Bucko
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: P_FORM OS - Manutencao cadastro de Formul�rios - PAGES
*Ultima Atualizacao: 02/08/2012
****************************************************************************/
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/util/c_form.php");

//Class P_situacao
Class p_form extends c_form {

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
        $this->smarty->template_dir = ADMraizFonte . "/template/util";
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
        $this->smarty->assign('colVis', "[ 0, 1, 2 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNomeForm(isset($parmPost['nomeForm']) ? $parmPost['nomeForm'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        $this->setHelp(isset($parmPost['help']) ? $parmPost['help'] : '');


        // include do javascript
        // include ADMjs . "/util/s_form.js";
}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
  switch ($this->m_submenu){
		case 'cadastrar':
			if ($this->verificaDireitoUsuario('ambform', 'I')){
				$this->desenhaCadastroForm();}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('ambform', 'A')){
				$forms = $this->select_form();
				$this->setId($forms[0]['ID']);
				$this->setNomeForm($forms[0]['NOMEFORM']);
				$this->setDescricao($forms[0]['DESCRICAO']);
				$this->setHelp($forms[0]['HELP']);
				$this->desenhaCadastroForm();
              }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('ambform', 'I')){				
				$response = $this->incluiForm();
                                if ($response){
                                        $this->mostraForm('Cadastro Realizado com Sucesso', 'sucesso');
                                } else{
                                        $this->desenhaCadastroForm('Erro ao cadastrar', 'error');
                                }
                        }
					
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('ambform', 'A')){
                                $response = $this->alteraForm();
				if ($response){
                                        $this->mostraForm('Alterado com Sucesso', 'sucesso');
                                }else{
                                        $this->desenhaCadastroForm('Erro ao Alterar', 'error');      
                                }
                        
                        }
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('ambform', 'E')){
                                $response = $this->excluiForm(); 
                                if ($response){
                                        $this->mostraForm('Excluido com sucesso', 'sucesso');
                                }else{
                                        $this->mostraForm('Erro ao Excluir', 'error');
                                }
                }
			break;
		default:
  			if ($this->verificaDireitoUsuario('ambform', 'C')){
				$this->mostraForm('');}
	
	}

} // fim controle

//---------------------------------------------------------------
//---------------------------------------------------------------
function desenhaCadastroForm($mensagem = NULL, $tipoMsg=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('nomeForm', "'".$this->getNomeForm()."'");
    $this->smarty->assign('descricao', "'".$this->getDescricao()."'");
    $this->smarty->assign('help', "'".$this->getHelp()."'");

    $this->smarty->display('form_cadastro.tpl');
    
}//fim desenhaCadsituacao

//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraForm($mensagem, $tipoMsg=NULL){
    if (isset($this->m_letra) and ($this->m_letra!='')) 
            $lanc = $this->select_form_letra(strtoupper($this->m_letra));
    else
            $lanc = $this->select_form_geral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

    $this->smarty->display('form_mostra.tpl');
	

} //fim mostrasituacaos
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$forms = new p_form();

$forms->controle();
 
  
?>
