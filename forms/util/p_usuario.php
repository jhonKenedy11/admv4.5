<?php

/**
 * @package   astecv3
 * @name      p_banco
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      20/08/2017
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/util/c_usuario.php");



//Class P_situacao
Class p_usuario extends c_usuario {

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
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', ADMraizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        
          // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Bancos");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5 ]"); 
        $this->smarty->assign('disableSort', "[ 5 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setUsuario(isset($parmPost['usuario']) ? $parmPost['usuario'] : '');
        $this->setLogin(isset($parmPost['login']) ? $parmPost['login'] : '');
        $this->setNomeReduzido(isset($parmPost['nomeReduzido']) ? $parmPost['nomeReduzido'] : '');
        $this->setCliente(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setsenha(isset($parmPost['senha']) ? $parmPost['senha'] : '');
        $this->setsituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
        $this->settipo(isset($parmPost['tipo']) ? $parmPost['tipo'] : '');
        $this->setconta(isset($parmPost['conta']) ? $parmPost['conta'] : '0');
        $this->setsalario(isset($parmPost['salario']) ? $parmPost['salario'] : '0');
        $this->setencargos(isset($parmPost['encargos']) ? $parmPost['encargos'] : '0');
        $this->setgeneroPgto(isset($parmPost['generoPgto']) ? $parmPost['generoPgto'] : '');
        $this->setccustoPgto(isset($parmPost['ccustoPgto']) ? $parmPost['ccustoPgto'] : '0');
        $this->setcomissaoFatura(isset($parmPost['comissaoFatura']) ? $parmPost['comissaoFatura'] : '0');
        $this->setcomissaoReceb(isset($parmPost['comissaoReceb']) ? $parmPost['comissaoReceb'] : '0');
        $this->setGrupo(isset($parmPost['grupo']) ? $parmPost['grupo'] : '');
        $this->setSmtp(isset($parmPost['smtp']) ? $parmPost['smtp'] : '');
        $this->setEmail(isset($parmPost['email']) ? $parmPost['email'] : '');
        $this->setEmailSenha(isset($parmPost['emailsenha']) ? $parmPost['emailsenha'] : '');  
        $this->setEmpresa(isset($parmPost['empresa']) ? $parmPost['empresa'] : '');      

        // include do javascript
        // include ADMjs . "/util/s_usuario.js";
}

//---------------------------------------------------------------
//---------------------------------------------------------------
function controle(){
  switch ($this->m_submenu){
		case 'cadastrar':
			if ($this->verificaDireitoUsuario('AmbUsuario', 'I')){
				$this->desenhaCadastroUsuario();}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('AmbUsuario', 'A')){
                            
				$usuario = $this->select_usuario_matricula();
				$this->setUsuario($usuario[0]['USUARIO']);
				$this->setLogin($usuario[0]['NOME']);
				$this->setNomeReduzido($usuario[0]['NOMEREDUZIDO']);
				$this->setcliente($usuario[0]['CLIENTE']);
				$this->setPessoaNome();
				$this->setsenha($usuario[0]['SENHA']);
				$this->setsituacao($usuario[0]['SITUACAO']);
				$this->settipo($usuario[0]['TIPO']);
				$this->setconta($usuario[0]['CONTA']);
				$this->setsalario($usuario[0]['SALARIO']);
				$this->setencargos($usuario[0]['ENCARGOS']);
				$this->setgeneroPgto($usuario[0]['GENEROPGTO']);
				$this->setccustoPgto($usuario[0]['CCUSTOPGTO']);
				$this->setcomissaoFatura($usuario[0]['COMISSAOFATURA']);
				$this->setcomissaoReceb($usuario[0]['COMISSAORECEB']);
				$this->setGrupo($usuario[0]['GRUPO']);
				$this->setSmtp($usuario[0]['SMTP']);
				$this->setEmail($usuario[0]['EMAIL']);
                                $this->setEmailSenha($usuario[0]['EMAILSENHA']);
                                $this->setEmpresa($usuario[0]['EMPRESA']);
				
				$this->desenhaCadastroUsuario();
              }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('AmbUsuario', 'I')){
				if (($this->existeUsuario()) AND ($this->gettipo()<>'Z')){
					$this->m_submenu = "cadastrar";
   					$this->desenhaCadastroUsuario("USUARIO J&aacute; EXISTENTE, ALTERE O N&uacute;MERO DO CLIENTE", 'alerta');}
				else {
					$this->mostraUsuario($this->incluiUsuario());}
			}		
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('AmbUsuario', 'A')){
				$this->mostraUsuario($this->alteraUsuario());}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('AmbUsuario', 'E')){
                                $this->mostraUsuario($this->excluiUsuario());}
			break;
		default:
  			if ($this->verificaDireitoUsuario('AmbUsuario', 'C')){
				$this->mostraUsuario('');}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Usuário. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroUsuario($mensagem=NULL, $tipoMsg=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    
    
    $this->smarty->assign('pessoa', $this->getCliente());
    $this->smarty->assign('pessoaNome', $this->getPessoaNome());
    $this->smarty->assign('usuario', $this->getUsuario());
    $this->smarty->assign('login', "'".$this->getLogin()."'");
    $this->smarty->assign('nomeReduzido', "'".$this->getNomeReduzido()."'");
    $this->smarty->assign('senha', "'".$this->getsenha()."'");
    $this->smarty->assign('conta', $this->getconta());
    $this->smarty->assign('salario', $this->getsalario('F'));
    $this->smarty->assign('encargos', $this->getencargos());
    $this->smarty->assign('generoPgto', "'".$this->getgeneroPgto()."'");
    $this->smarty->assign('ccustoPgto', $this->getccustoPgto());
    $this->smarty->assign('comissaoFatura', $this->getcomissaoFatura());
    $this->smarty->assign('comissaoReceb', $this->getcomissaoReceb());
    $this->smarty->assign('smtp', $this->getSmtp());
    $this->smarty->assign('email', $this->getEmail());
    $this->smarty->assign('emailsenha', $this->getEmailSenha());


    // EMPRESA
    $consulta = new c_banco();
    $sql = "select EMPRESA as id, NOMEFANTASIA as descricao from AMB_EMPRESA ";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $empresa_ids[0] = '';
    $empresa_names[0] = 'selecione uma Empresa';
    for ($i=0; $i < count($result); $i++){
        $empresa_ids[$i+1] = $result[$i]['ID'];
        $empresa_names[$i+1] = $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('empresa_ids',   $empresa_ids);
    $this->smarty->assign('empresa_names', $empresa_names);
    $this->smarty->assign('empresa_id', $this->getEmpresa());	
    
    // situacao
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='SituacaoUsuario')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('situacao_ids', $situacao_ids);
    $this->smarty->assign('situacao_names', $situacao_names);
    $this->smarty->assign('situacao_id', $this->getsituacao());	

    // tipo
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='TipoUsuario')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $tipo_ids[$i] = $result[$i]['ID'];
            $tipo_names[$i] = $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('tipo_ids', $tipo_ids);
    $this->smarty->assign('tipo_names', $tipo_names);
    $this->smarty->assign('tipo_id', $this->gettipo());	


    // grupo
    $consulta = new c_banco();
    $sql = "SELECT usuario as id, nomereduzido as descricao FROM AMB_USUARIO  where (situacao='A') and (tipo='Z')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $grupo_ids[0] = 0;
    $grupo_names[0] = "Sem Grupo";
    for ($i=0; $i < count($result); $i++){
            $grupo_ids[$i+1] = $result[$i]['ID'];
            $grupo_names[$i+1] = $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('grupo_ids', $grupo_ids);
    $this->smarty->assign('grupo_names', $grupo_names);
    $this->smarty->assign('grupo_id', $this->getGrupo());	

    $this->smarty->display('usuario_cadastro.tpl');
        
}//fim desenhaCadastro usuario

//---------------------------------------------------------------
//---------------------------------------------------------------
function mostraUsuario($mensagem){

    $lanc = $this->select_usuario_geral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('usuario_mostra.tpl');
	

} //fim mostra usuario
//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$usuario = new p_usuario();

$usuario->controle();
 
  
?>
