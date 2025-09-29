<?php
if (!defined('ADMpath')) exit;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir. "/../../class/util/c_empresa.php");



class p_empresa extends c_empresa
{
    public $smarty = null;
    private $m_submenu = null;

    // Propriedades para os campos do formulário
    private $empresa_id;
    private $nome_empresa;
    private $nome_fantasia;
    private $centro_custo;
    private $cnpj;

    function __construct()
    {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        

        $this->smarty = new Smarty;
        $this->smarty->template_dir = ADMraizFonte . "/template/util";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = $parmGet['submenu'] ?? $parmPost['submenu'] ?? '';

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

        $this->empresa_id        = $parmPost['empresa_id'] ?? $parmGet['empresa_id'] ?? '';
        $this->nome_empresa      = $parmPost['nome_empresa'] ?? $parmGet['nome_empresa'] ?? '';
        $this->nome_fantasia     = $parmPost['nome_fantasia'] ?? $parmGet['nome_fantasia'] ?? '';
        $this->cnpj              = $parmPost['cnpj'] ?? $parmGet['cnpj'] ?? '';
        $this->inscricao_estadual= $parmPost['inscricao_estadual'] ?? $parmGet['inscricao_estadual'] ?? '';
        $this->cep               = $parmPost['cep'] ?? $parmGet['cep'] ?? '';
        $this->rua               = $parmPost['rua'] ?? $parmGet['rua'] ?? '';
        $this->numero            = $parmPost['numero'] ?? $parmGet['numero'] ?? '';
        $this->complemento       = $parmPost['complemento'] ?? $parmGet['complemento'] ?? '';
        $this->bairro            = $parmPost['bairro'] ?? $parmGet['bairro'] ?? '';
        $this->cidade            = $parmPost['cidade'] ?? $parmGet['cidade'] ?? '';
        $this->estado            = $parmPost['estado'] ?? $parmGet['estado'] ?? '';
        $this->codigo_municipio  = $parmPost['codigo_municipio'] ?? $parmGet['codigo_municipio'] ?? '';
        $this->email             = $parmPost['email'] ?? $parmGet['email'] ?? '';
        $this->telefone          = $parmPost['telefone'] ?? $parmGet['telefone'] ?? '';
        $this->regime_tributario = $parmPost['regime_tributario'] ?? $parmGet['regime_tributario'] ?? '';
        $this->casas_decimais    = $parmPost['casas_decimais'] ?? $parmGet['casas_decimais'] ?? '';
        $this->mensagem_complementar = $parmPost['msg_informacao_complementar'] ?? $parmGet['msg_informacao_complementar'] ?? '';
        $this->centro_custo      = $parmPost['centro_custo'] ?? $parmGet['centro_custo'] ?? '';
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'inclui':
                $dados = [
                    'nome_empresa'           => $this->nome_empresa,
                    'nome_fantasia'          => $this->nome_fantasia,
                    'cnpj'                   => $this->cnpj,
                    'inscricao_estadual'     => $this->inscricao_estadual,
                    'cep'                    => $this->cep,
                    'rua'                    => $this->rua,
                    'numero'                 => $this->numero,
                    'complemento'            => $this->complemento,
                    'bairro'                 => $this->bairro,
                    'cidade'                 => $this->cidade,
                    'estado'                 => $this->estado,
                    'codigo_municipio'       => $this->codigo_municipio,
                    'email'                  => $this->email,
                    'telefone'               => $this->telefone,
                    'regime_tributario'      => $this->regime_tributario,
                    'casas_decimais'         => $this->casas_decimais,
                    'mensagem_complementar'  => $this->mensagem_complementar
                ];
                $resultado = $this->incluiEmpresa($dados);
                if (is_numeric($resultado)) {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Empresa cadastrada com sucesso!',timer: 3000,showConfirmButton: false}).then(function(){window.location='?mod=util&form=empresa';});</script>";
                    $this->desenhaEmpresa('');
                    exit;
                } else {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: '".addslashes($resultado)."',confirmButtonText: 'OK'});</script>";
                    $this->desenhaEmpresa('');
                    exit;
                }
                break;
                case 'consulta':
                    $this->desenhaCadastroEmpresa('', '');
                    break;
            case 'alterar':
                $empresa_id = $this->empresa_id;
                $dados = [
                    'nome_empresa'       => $this->nome_empresa,
                    'nome_fantasia'      => $this->nome_fantasia,
                    'cnpj'               => $this->cnpj,
                    'inscricao_estadual' => $this->inscricao_estadual,
                    'cep'                => $this->cep,
                    'rua'                => $this->rua,
                    'numero'             => $this->numero,
                    'complemento'        => $this->complemento,
                    'bairro'             => $this->bairro,
                    'cidade'             => $this->cidade,
                    'estado'             => $this->estado,
                    'codigo_municipio'   => $this->codigo_municipio,
                    'email'              => $this->email,
                    'telefone'           => $this->telefone 
                ];
                $resultado = $this->alteraEmpresa($empresa_id, $dados);
                if ($resultado === true) {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Empresa alterada com sucesso!',timer: 3000,showConfirmButton: false}).then(function(){window.location='?mod=util&form=empresa';});</script>";
                    $this->desenhaEmpresa('');
                    exit;
                } else {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: '".addslashes($resultado)."',confirmButtonText: 'OK'});</script>";
                    $this->desenhaEmpresa('');
                    exit;
                }
                break;
            case 'excluir':
                $empresa_id = $this->empresa_id;
                $resultado = $this->excluiEmpresa($empresa_id);
                if (is_numeric($resultado) && $resultado > 0) {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Empresa excluída com sucesso!',confirmButtonText: 'OK'}).then(function(){window.location='?mod=util&form=empresa';});</script>";
                    $this->desenhaEmpresa('');
                    exit;
                } else {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script> ";
                    echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: '".addslashes($resultado)."',confirmButtonText: 'OK'});</script>";
                    $this->desenhaEmpresa('');
                    exit;
                }
                break;
            case 'salvarLogoEmpresa':
                try {
                    $dados = [
                        'id_empresa' => (int)$_POST['id_empresa'],
                        'file' => $_FILES['file'] ?? null
                    ];
                    $resultado = $this->salvarLogoEmpresa($dados);
                    echo json_encode($resultado);
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                }
                exit;
            case 'carregarLogoEmpresa':
                try {
                    $id_empresa = (int)$_GET['id_empresa'];
                    $resultado = $this->selectLogoEmpresa($id_empresa);
                    header('Content-Type: application/json');
                    echo json_encode($resultado);
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erro ao carregar logos: ' . $e->getMessage()
                    ]);
                }
                exit;
            case 'excluirLogoEmpresa':
                try {
                    $id_logo = (int)$_GET['id_logo'];
                    $resultado = $this->excluiLogoEmpresa($id_logo);
                    if ($resultado) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Falha ao excluir logo']);
                    }
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erro ao excluir logo: ' . $e->getMessage()
                    ]);
                }
                exit;
            default:
                $this->desenhaEmpresa();
                break;
        }
    }

    function desenhaEmpresa($mensagem = null, $tipoMsg = null)
    {
        // Monta array de filtros a partir dos atributos do objeto
        $filtros = [];
        if ($this->nome_empresa)  $filtros['nome_empresa']  = $this->nome_empresa;
        

        if (!empty($filtros)) {
            $dados = $this->selecionaEmpresasFiltradas($filtros);
        } else {
            $dados = $this->selecionaTodasEmpresas();
        }

        $this->smarty->assign('dados', $dados);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->display('empresa_mostra.tpl');
    }

    function desenhaCadastroEmpresa($mensagem = null, $tipoMsg = null) {
        if ($this->empresa_id !== '') {
            $dados = $this->selecionaEmpresaPorId($this->empresa_id);
        } else {
            $dados = [];
        }

        $this->smarty->assign('dados', $dados);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->display('empresa_cadastro.tpl');
    }
}

// Execução principal
$empresa = new p_empresa();
$empresa->controle();