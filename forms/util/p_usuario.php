<?php

/**
 * @package   astecv3
 * @name      p_usuario
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      20/08/2017
 */
if (!defined('ADMpath')):
    exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/util/c_usuario.php");

class p_usuario extends c_usuario
{
    private $m_submenu = '';
    private $m_letra = '';
    private $m_senhaConfirm = '';
    private $m_direitosJson = '';
    public $smarty = null;

    function __construct()
    {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;
        $this->smarty->template_dir = ADMraizFonte . "/template/util";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        $this->setUsuario(isset($parmPost['usuario']) ? $parmPost['usuario'] : '');
        $this->setLogin(isset($parmPost['login']) ? $parmPost['login'] : '');
        $this->setNomeReduzido(isset($parmPost['nomeReduzido']) ? $parmPost['nomeReduzido'] : '');
        $this->setCliente(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $senhaPost = isset($parmPost['senha']) ? trim($parmPost['senha']) : '';
        $this->setsenha($senhaPost);
        $this->m_senhaConfirm = isset($parmPost['senhaConfirm']) ? trim($parmPost['senhaConfirm']) : '';
        $this->m_direitosJson = isset($_POST['direitos_json']) ? (string) $_POST['direitos_json'] : '';
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
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('AmbUsuario', 'I')) {
                    $this->aplicarProximaMatriculaCadastro();
                    $this->desenhaCadastroUsuario();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('AmbUsuario', 'A')) {
                    $reg = $this->select_usuario_matricula();
                    if (is_array($reg) && isset($reg[0])) {
                        $u = $reg[0];
                        $this->setUsuario($u['USUARIO']);
                        $this->setLogin($u['NOME']);
                        $this->setNomeReduzido($u['NOMEREDUZIDO']);
                        $this->setcliente($u['CLIENTE']);
                        $this->setsenha('');
                        $this->setsituacao($u['SITUACAO']);
                        $this->settipo($u['TIPO']);
                        $this->setconta($u['CONTA']);
                        $this->setsalario($u['SALARIO']);
                        $this->setencargos($u['ENCARGOS']);
                        $this->setgeneroPgto($u['GENEROPGTO']);
                        $this->setccustoPgto($u['CCUSTOPGTO']);
                        $this->setcomissaoFatura($u['COMISSAOFATURA']);
                        $this->setcomissaoReceb($u['COMISSAORECEB']);
                        $this->setGrupo($u['GRUPO']);
                        $this->setSmtp($u['SMTP']);
                        $this->setEmail($u['EMAIL']);
                        $this->setEmailSenha($u['EMAILSENHA']);
                        $this->setEmpresa($u['EMPRESA']);
                    }
                    $this->desenhaCadastroUsuario();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('AmbUsuario', 'I')) {
                    if ($this->getsenha() === '') {
                        $this->m_submenu = 'cadastrar';
                        $this->desenhaCadastroUsuario('Informe a senha de acesso.', 'alerta');
                    } elseif ($this->getsenha() !== '' && $this->getsenha() !== $this->m_senhaConfirm) {
                        $this->m_submenu = 'cadastrar';
                        $this->desenhaCadastroUsuario('Senha e confirma&ccedil;&atilde;o n&atilde;o conferem.', 'alerta');
                    } else {
                        $this->aplicarProximaMatriculaCadastro();
                        if ($this->ehTipoGrupo() && (int) $this->getUsuario() < c_usuario::MATRICULA_GRUPO_MIN) {
                            $this->m_submenu = 'cadastrar';
                            $this->desenhaCadastroUsuario('N&atilde;o foi poss&iacute;vel gerar matr&iacute;cula para o grupo.', 'alerta');
                        } elseif (!$this->ehTipoGrupo() && ((int) $this->getUsuario() <= 0 || $this->matriculaReservadaAdmin($this->getUsuario()))) {
                            $this->m_submenu = 'cadastrar';
                            $this->desenhaCadastroUsuario('Faixa operacional cheia ou matr&iacute;cula inv&aacute;lida (999 &eacute; admin).', 'alerta');
                        } elseif ($this->existeUsuario()) {
                            $this->m_submenu = 'cadastrar';
                            $this->desenhaCadastroUsuario('Matr&iacute;cula j&aacute; em uso. Atualize a tela e tente novamente.', 'alerta');
                        } else {
                            $this->mostraUsuario($this->incluiUsuario() . $this->salvarDireitosCadastro($this->m_direitosJson));
                        }
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('AmbUsuario', 'A')) {
                    if ($this->getsenha() !== '' && $this->getsenha() !== $this->m_senhaConfirm) {
                        $this->m_submenu = 'alterar';
                        $this->desenhaCadastroUsuario('Senha e confirma&ccedil;&atilde;o n&atilde;o conferem.', 'alerta');
                    } else {
                        $atualizarSenha = ($this->getsenha() !== '');
                        $this->mostraUsuario($this->alteraUsuario($atualizarSenha) . $this->salvarDireitosCadastro($this->m_direitosJson));
                    }
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('AmbUsuario', 'E')) {
                    $this->mostraUsuario($this->excluiUsuario());
                }
                break;
            case 'cancelar':
            default:
                if ($this->verificaDireitoUsuario('AmbUsuario', 'C')) {
                    $this->mostraUsuario('');
                }
        }
    }

    function desenhaCadastroUsuario($mensagem = null, $tipoMsg = null)
    {
        $casasDecimais = 2;
        $dirPed = dirname(__FILE__);
        if (is_file($dirPed . '/../../class/ped/c_parametro.php')) {
            require_once($dirPed . '/../../class/ped/c_parametro.php');
            $parametros = new c_parametros();
            $casasDecimais = $parametros->getCasasDecimais();
        }
        $this->smarty->assign('casasDecimais', $casasDecimais);
        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', ADMraizCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('mod', 'util');
        $this->smarty->assign('form', 'usuario');
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('pessoa', $this->getCliente());
        $this->setPessoaNome();
        $this->smarty->assign('pessoaNome', $this->getPessoaNome());
        $this->smarty->assign('usuario', $this->getUsuario());
        $this->smarty->assign('proximaMatriculaOperacional', $this->proximaMatriculaOperacional());
        $this->smarty->assign('proximaMatriculaGrupo', $this->proximaMatriculaGrupo());
        $this->smarty->assign('login', $this->getLogin());
        $this->smarty->assign('nomeReduzido', $this->getNomeReduzido());
        $this->smarty->assign('ehAlteracao', ($this->m_submenu === 'alterar'));
        $this->smarty->assign('conta', $this->getconta());
        $this->smarty->assign('salario', $this->getsalario('F'));
        $this->smarty->assign('encargos', $this->getencargos());
        $this->smarty->assign('generoPgto', $this->getgeneroPgto());
        $this->smarty->assign('ccustoPgto', $this->getccustoPgto());
        $this->smarty->assign('comissaoFatura', $this->getcomissaoFatura('F'));
        $this->smarty->assign('comissaoReceb', $this->getcomissaoReceb('F'));
        $this->smarty->assign('smtp', $this->getSmtp());
        $this->smarty->assign('email', $this->getEmail());
        $this->smarty->assign('emailsenha', $this->getEmailSenha());

        $empresa = $this->comboEmpresaUsuario();
        $this->smarty->assign('empresa_ids', $empresa['ids']);
        $this->smarty->assign('empresa_names', $empresa['names']);
        $this->smarty->assign('empresa_id', $this->getEmpresa());

        $situacao = $this->comboSituacaoUsuario();
        $this->smarty->assign('situacao_ids', $situacao['ids']);
        $this->smarty->assign('situacao_names', $situacao['names']);
        $this->smarty->assign('situacao_id', $this->getsituacao());

        $tipo = $this->comboTipoUsuario();
        $this->smarty->assign('tipo_ids', $tipo['ids']);
        $this->smarty->assign('tipo_names', $tipo['names']);
        $this->smarty->assign('tipo_id', $this->gettipo());

        $grupo = $this->comboGrupoUsuario();
        $this->smarty->assign('grupo_ids', $grupo['ids']);
        $this->smarty->assign('grupo_names', $grupo['names']);
        $this->smarty->assign('grupo_id', $this->getGrupo());

        $dir = $this->programasUiCadastro();
        $this->smarty->assign('programasUi', $dir['programasUi']);
        $this->smarty->assign('grupoId', $dir['grupoId']);
        $this->smarty->assign('grupoNome', $dir['grupoNome']);

        $this->smarty->display('usuario_cadastro.tpl');
    }

    function mostraUsuario($mensagem)
    {
        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', ADMraizCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('mod', 'util');
        $this->smarty->assign('form', 'usuario');
        $this->smarty->assign('titulo', 'Usu&aacute;rios');
        $this->smarty->assign('colVis', '[ 0, 1, 2, 3, 4, 5 ]');
        $this->smarty->assign('disableSort', '[ 6 ]');
        $this->smarty->assign('numLine', '25');
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('lanc', $this->select_usuario_geral());
        $this->smarty->display('usuario_mostra.tpl');
    }
}

$usuario = new p_usuario();
$usuario->controle();
