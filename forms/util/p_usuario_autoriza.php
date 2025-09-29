<?php

/****************************************************************************
 *Cliente...........:
 *Contratada........: ADMService
 *Desenvolvedor.....: Lucas Tortola da Silva Bucko
 *Sistema...........: Sistema de Informacao Gerencial
 *Classe............: P_USUARIO AUTORIZA - Manutencao de Direitos - PAGES
 *Ultima Atualizacao: 14/09/2012
 ****************************************************************************/

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/util/c_usuario.php");
include_once($dir . "/../../class/util/c_usuario_autoriza.php");

//Class P_USUARIO_AUTORIZA
class p_usuario_autoriza extends c_usuario_autoriza
{

	private $m_submenu = NULL;
	public $m_letra = NULL;
	public $smarty = NULL;
	public $m_user = NULL;


	//---------------------------------------------------------------
	//---------------------------------------------------------------
	public function __construct()
	{


		//Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
		$parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
		$parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

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
		$this->smarty->assign('pathJs',  ADMhttpBib . '/js');
		$this->smarty->assign('pathCliente', ADMhttpCliente);
		$this->smarty->assign('bootstrap', ADMbootstrap);
		$this->smarty->assign('raizCliente', ADMraizCliente);
		$this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

		// dados para exportacao e relatorios
		$this->smarty->assign('titulo', "Usuários Autoriza");
		$this->smarty->assign('colVis', "[ 0, 1, 2, 3 ]");
		$this->smarty->assign('disableSort', "[ 3 ]");
		$this->smarty->assign('numLine', "25");

		// metodo SET dos dados do FORM para o TABLE
		$this->setUsuario(isset($parmGet['usuario']) ? $parmGet['usuario'] : (isset($parmPost['usuario']) ? $parmPost['usuario'] : ''));
		$this->setPrograma(isset($parmPost['programa']) ? $parmPost['programa'] : '');
		$this->setDireitos(isset($parmPost['direitoUser']) ? $parmPost['direitoUser'] : '');
	}

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	function controle()
	{
		switch ($this->m_submenu) {
			case 'cadastrar':
				if ($this->verificaDireitoUsuario('AmbUsuarioAutoriza', 'I')) {
					$this->setNome();
					$this->desenhaCadastroAutorizacao();
				}
				break;

			case 'alterar':
				if ($this->verificaDireitoUsuario('AmbUsuarioAutoriza', 'A')) {
					$autoriza = $this->select_autorizacao();
					$this->setUsuario($autoriza[0]['USUARIO']);
					$this->setPrograma($autoriza[0]['PROGRAMA']);
					$this->setDireitos($autoriza[0]['DIREITOS']);
					$this->setNome();

					$this->desenhaCadastroAutorizacao();
				}
				break;

			case 'inclui':
				if ($this->verificaDireitoUsuario('AmbUsuarioAutoriza', 'I')) {
					if ($this->existeAutorizacao()) {
						$this->m_submenu = "cadastrar";
						$msgRetorno = "Direito já existente, altere a opção";
						echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";                        
                            echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                                width: 420,
                                text: '".$msgRetorno.".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
						$this->desenhaCadastroAutorizacao($msgRetorno);
					} else {
						$this->mostraAutorizacao($this->incluiAutorizacao());
					}
				}
				break;

			case 'altera':
				if ($this->verificaDireitoUsuario('AmbUsuarioAutoriza', 'A')) {
					$this->mostraAutorizacao($this->alteraAutorizacao());
				}
				break;

			case 'exclui':
				if ($this->verificaDireitoUsuario('AmbUsuarioAutoriza', 'E')) {
					$this->mostraAutorizacao($this->excluiAutorizacao());
				}
				break;

			default:
				if ($this->verificaDireitoUsuario('AmbUsuarioAutoriza', 'C')) {
					if ($this->m_user != '') {
						$this->setUsuario($this->m_user);
					}
					$this->setNome();
					$this->mostraAutorizacao('');
				}
		}
	} // fim controle

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	function desenhaCadastroAutorizacao($mensagem = NULL)
	{

		$this->smarty->assign('pathImagem', $this->img);
		$this->smarty->assign('subMenu', $this->m_submenu);
		$this->smarty->assign('letra', $this->m_letra);
		$this->smarty->assign('mensagem', $mensagem);

		$this->smarty->assign('usuario', $this->getUsuario());
		$this->smarty->assign('nome', "'" . $this->getNome() . "'");
		$this->smarty->assign('Programa', "'" . $this->getPrograma() . "'");
		$this->smarty->assign('Direitos', "'" . $this->getDireitos() . "'");

		//combobox PROGRAMA
		$consulta = new c_banco();
		$sql = "SELECT DISTINCT * FROM amb_form order by descricao";
		//echo $sql;
		$consulta->exec_sql($sql);
		$consulta->close_connection();
		$result = $consulta->resultado;

		for ($i = 0; $i < count($result); $i++) {
			$form_ids[$i] = $result[$i]['ID'];
			$form_names[$i] = $result[$i]['NOMEFORM'];
			$form_descricao[$i] = $result[$i]['DESCRICAO'];
		}

		$this->smarty->assign('form_ids', $form_ids);
		$this->smarty->assign('form_names', $form_names);
		$this->smarty->assign('form_descricao', $form_descricao);
		$this->smarty->assign('form_id', $this->getPrograma());
		// fim combobox PROGRAMA

		// Combobox USUÁRIO 
		$consulta = new c_banco();
		$sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ORDER BY NOME";
		$consulta->exec_sql($sql);
		$result = $consulta->resultado;
		$consulta->close_connection();

		$usuario_ids = array();
		$usuario_nomes = array();

		for ($i = 0; $i < count($result); $i++) {
			$usuario_ids[$i] =  $result[$i]['USUARIO'];
			$usuario_nomes[$i] = $result[$i]['NOME'];
		}

		$this->smarty->assign('usuario_ids', $usuario_ids);
		$this->smarty->assign('usuario_nomes', $usuario_nomes);
		$this->smarty->assign('usuario_selected', $this->getUsuario());
		// fim combobox usuario
		
		// Checkbox Direitos
		$direitos_ids[0] = 'I';
		$direitos_names[0] = 'Inclusão';
		$direitos_ids[1] = 'A';
		$direitos_names[1] = 'Alteração';
		$direitos_ids[2] = 'E';
		$direitos_names[2] = 'Exclusão';
		$direitos_ids[3] = 'C';
		$direitos_names[3] = 'Consulta';
		$direitos_ids[4] = 'S';
		$direitos_names[4] = 'Serviço';
		$direitos_ids[5] = 'R';
		$direitos_names[5] = 'Relatório';

		$this->smarty->assign('direitos_ids', $direitos_ids);
		$this->smarty->assign('direitos_names', $direitos_names);
		$this->smarty->assign('direitos_id', str_split($this->getDireitos()));
		// fim checkbox Direitos

		$this->smarty->display('usuario_autoriza_cadastro.tpl');
	} //fim desenhaCadAutorizacao

	//---------------------------------------------------------------
	//---------------------------------------------------------------
	function mostraAutorizacao($mensagem)
	{

		$lanc = $this->select_autorizacao_geral();

		$this->smarty->assign('pathImagem', $this->img);
		$this->smarty->assign('mensagem', $mensagem);
		$this->smarty->assign('letra', $this->m_letra);
		$this->smarty->assign('subMenu', $this->m_submenu);
		$this->smarty->assign('lanc', $lanc);


		$this->smarty->display('usuario_autoriza_mostra.tpl');
	} //fim mostraAutorizacao
	//-------------------------------------------------------------
}	//	END OF THE CLASS

// Rotina principal - cria classe
$autoriza = new p_usuario_autoriza();

$autoriza->controle();
