<?php

/**
 * @package   astecv3
 * @name      p_responsavel_tecnico
 * @version   3.0.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua da Silva
 * @date      2025
 */

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/util/c_responsavel_tecnico.php");
include_once($dir."/../../class/util/c_user.php");

//Class P_responsavel_tecnico
Class p_responsavel_tecnico extends c_responsavel_tecnico {

private $m_submenu = NULL;
private $m_filtro = NULL;
public $smarty = NULL;

//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
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
        $this->m_filtro = isset($parmPost['filtro']) ? $parmPost['filtro'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', ADMraizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Responsáveis Técnicos");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5 ]"); // Colunas visíveis para exportação
        $this->smarty->assign('disableSort', "[ 6 ]"); // Coluna de ações não ordenável
        $this->smarty->assign('numLine', "25"); // Número de linhas por página 

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNome(isset($parmPost['nome']) ? $parmPost['nome'] : '');
        $this->setCpf(isset($parmPost['cpf']) ? $parmPost['cpf'] : '');
        $this->setCrea(isset($parmPost['crea']) ? $parmPost['crea'] : '');
        $this->setTelefone(isset($parmPost['telefone']) ? $parmPost['telefone'] : '');
        $this->setEmail(isset($parmPost['email']) ? $parmPost['email'] : '');
        $this->setRua(isset($parmPost['rua']) ? $parmPost['rua'] : '');
        $this->setNumero(isset($parmPost['numero']) ? $parmPost['numero'] : '');
        $this->setComplemento(isset($parmPost['complemento']) ? $parmPost['complemento'] : '');
        $this->setCidade(isset($parmPost['cidade']) ? $parmPost['cidade'] : '');
        $this->setEstado(isset($parmPost['estado']) ? $parmPost['estado'] : '');
        $this->setCep(isset($parmPost['cep']) ? $parmPost['cep'] : '');
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : 'A');
}

//---------------------------------------------------------------
function controle(){
  switch ($this->m_submenu){
		case 'cadastrar':
			if ($this->verificaDireitoUsuario('AmbResponsavelTecnico', 'I')){
                $this->incluiResponsavel();
				$this->mostraResponsavel();}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('AmbResponsavelTecnico', 'A')){
				if($this->getId() != ''){
                    $this->alteraResponsavel();
                    $this->mostraResponsavel();
				} else {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'ID não informado',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    </script>";
                }				
              }
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('AmbResponsavelTecnico', 'E')){
                $this->mostraResponsavel($this->excluiResponsavel());}
			break;
		default:
  			if ($this->verificaDireitoUsuario('AmbResponsavelTecnico', 'C')){
				$this->mostraResponsavel('');}
	
	}

} // fim controle

function mostraResponsavel($mensagem=NULL, $tipoMsg=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('submenu', $this->m_submenu);
    $this->smarty->assign('filtro', $this->m_filtro);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    
    
    $lanc = $this->select_responsavel_geral();
    $this->smarty->assign('lanc', $lanc);
    
    
    $this->smarty->display('responsavel_tecnico_mostra.tpl');
        
}//fim mostraResponsavel

//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$responsavel_tecnico = new p_responsavel_tecnico();

$responsavel_tecnico->controle();
 
  
?>
