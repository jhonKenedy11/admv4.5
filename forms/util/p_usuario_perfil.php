<?php
/**
 * @package   astec
 * @name      p_usuario_perfil
 * @version   2.0.00
 * @copyright 2013-2016 &copy; 
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      11/03/2016
*/

require_once("../../../smarty/libs/Smarty.class.php");
require_once("../../class/pss/c_usuario.php");

/**
 * Class p_usuario_perfil
 * Classe principal "CORE class"
 */
Class p_usuario_perfil extends c_usuario {

    /**
     * @name m_submenu
     * @value Recebe a acao temporaria atraves do $POST para utilizacao dentro da class
     * @access private
     * @var string
     */
    private $m_submenu  = NULL;

    /**
     * @name m_letra
     * @value Recebe dados temporario atraves do POST para pesquisa
     * @access private
     * @var string
     */
    private $m_letra    = NULL;

    /**
     * @name smarty
     * @value Recebe todos os metodos da classe smarty
     * @access public
     * @var string
     */
    public $smarty      = NULL;

    /**
     * @name __construct
     * @param  string $submenu recebe a acao a ser tomada na funcao controle, e seta a variavel interna da classe;
     * @param  string $letra parametros que passados quando do submit da classe.
     * @return boolean true sucesso false Erro
     */
    function __construct($submenu, $letra) {

        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_par = explode("|", $this->m_letra);

        session_start();
        $this->carrregaVarsConfig(0);
        $this->cabecalho();
        $this->from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = '../../template/pss/';
        $this->smarty->compile_dir = '../../smarty/templates_c/pss/';
        $this->smarty->config_dir = '../../smarty/configs/';
        $this->smarty->cache_dir = '../../smarty/cache/';
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'alterar':
                $this->alteraUsuario();
                $this->desenhaCadastroUsuario('Sucesso! Seu perfil foi atualizado.');
                break;
            default:
                    $this->setUsuario($this->m_userid);
                    $this->AmbUsuario();
                    $this->desenhaCadastroUsuario('');
        }
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function desenhaCadastroUsuario($mensagem = NULL) {
        include $this->js . "/pss/s_usuario.js";

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);


        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente()!= ""){
            $this->setPessoaNome();
            $this->smarty->assign('pessoaNome', $this->getPessoaNome());
        }
        
        $this->smarty->assign('usuario', $this->getUsuario());
        $this->smarty->assign('login', "'" . $this->getLogin() . "'");
        $this->smarty->assign('nomeReduzido', "'" . $this->getNomeReduzido() . "'");
        $this->smarty->assign('senha', "'" . $this->getsenha() . "'");
        $this->smarty->assign('conta', $this->getconta());
        $this->smarty->assign('salario', $this->getsalario('F'));
        $this->smarty->assign('encargos', $this->getencargos());
        $this->smarty->assign('generoPgto', "'" . $this->getgeneroPgto() . "'");
        $this->smarty->assign('ccustoPgto', $this->getccustoPgto());
        $this->smarty->assign('comissaoFatura', $this->getcomissaoFatura());
        $this->smarty->assign('comissaoReceb', $this->getcomissaoReceb());

        // situacao
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='SituacaoUsuario')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
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
        for ($i = 0; $i < count($result); $i++) {
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
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + 1] = $result[$i]['ID'];
            $grupo_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);
        $this->smarty->assign('grupo_id', $this->getGrupo());

        $this->smarty->display('usuario_perfil_cadastro.tpl');
    } // desenhaCadastroUsuario


}

//	END OF THE CLASS
// Rotina principal - cria classe
$usuario = new p_usuario_perfil($_POST['submenu'], $_POST['letra']);

if (isset($_POST['usuario'])) { $usuario->setUsuario($_POST['usuario']); } else {$usuario->setUsuario('');};
if (isset($_POST['login'])) { $usuario->setLogin($_POST['login']); } else {$usuario->setLogin('');};
if (isset($_POST['nomeReduzido'])) { $usuario->setNomeReduzido($_POST['nomeReduzido']); } else {$usuario->setNomeReduzido('');};                     
if (isset($_POST['pessoa'])) { $usuario->setCliente($_POST['pessoa']); } else {$usuario->setCliente('');};
if (isset($_POST['senha'])) { $usuario->setsenha($_POST['senha']); } else {$usuario->setsenha('');};
if (isset($_POST['situacao'])) { $usuario->setsituacao($_POST['situacao']); } else {$usuario->setsituacao('');};
if (isset($_POST['tipo'])) { $usuario->settipo($_POST['tipo']); } else {$usuario->settipo('');};
if (isset($_POST['conta'])) { $usuario->setconta($_POST['conta']); } else {$usuario->setconta('');};
if (isset($_POST['salario'])) { $usuario->setsalario($_POST['salario']); } else {$usuario->setsalario('');};
if (isset($_POST['encargos'])) { $usuario->setencargos($_POST['encargos']); } else {$usuario->setencargos('0');};
if (isset($_POST['generoPgto'])) { $usuario->setgeneroPgto($_POST['generoPgto']); } else {$usuario->setgeneroPgto('0');};
if (isset($_POST['ccustoPgto'])) { $usuario->setccustoPgto($_POST['ccustoPgto']); } else {$usuario->setccustoPgto('0');};
if (isset($_POST['comissaoFatura'])) { $usuario->setcomissaoFatura($_POST['comissaoFatura']); } else {$usuario->setcomissaoFatura('0');};
if (isset($_POST['comissaoReceb'])) { $usuario->setcomissaoReceb($_POST['comissaoReceb']); } else {$usuario->setcomissaoReceb('0');};
if (isset($_POST['grupo'])) { $usuario->setGrupo($_POST['grupo']); } else {$usuario->setGrupo('');};


//echo "passou".$_POST['fornecedor'];
//echo "situacao:".$_POST['total'].$situacao->getTotal();


$usuario->controle();
?>
