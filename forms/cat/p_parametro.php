<?php
/**
 * Formulário para administração dos parâmetros CAT (Ordem de Serviço)
 * Arquivo: forms/cat/p_parametro.php
 * Padrão ADM v4.5 — alinhado ao módulo EST
 */

if (!defined('ADMpath')) exit;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/cat/c_parametro.php");

class p_parametro extends c_parametro
{
    public $smarty = null;
    protected $m_submenu = null;
    protected $filtro_busca = null;

    function __construct()
    {
        session_start();
        c_user::from_array($_SESSION['user_array']);

        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];
        $parmGet  = filter_input_array(INPUT_GET, FILTER_DEFAULT) ?? [];

        $this->id                   = $parmPost['id'] ?? $parmGet['id'] ?? '';
        $this->situacaoinclusao     = array_key_exists('situacaoinclusao', $parmPost) ? ($parmPost['situacaoinclusao'] === '' ? '0' : $parmPost['situacaoinclusao']) : '0';
        $this->sitagatendimento     = array_key_exists('sitagatendimento', $parmPost) ? ($parmPost['sitagatendimento'] === '' ? '0' : $parmPost['sitagatendimento']) : '0';
        $this->sitematendimento     = array_key_exists('sitematendimento', $parmPost) ? ($parmPost['sitematendimento'] === '' ? '0' : $parmPost['sitematendimento']) : '0';
        $this->sitsolicitarpeca     = array_key_exists('sitsolicitarpeca', $parmPost) ? ($parmPost['sitsolicitarpeca'] === '' ? '0' : $parmPost['sitsolicitarpeca']) : '0';
        $this->sitagpeca            = array_key_exists('sitagpeca', $parmPost) ? ($parmPost['sitagpeca'] === '' ? '0' : $parmPost['sitagpeca']) : '0';
        $this->sitpecarecebida      = array_key_exists('sitpecarecebida', $parmPost) ? ($parmPost['sitpecarecebida'] === '' ? '0' : $parmPost['sitpecarecebida']) : '0';
        $this->sitaporcamento       = array_key_exists('sitaporcamento', $parmPost) ? ($parmPost['sitaporcamento'] === '' ? '0' : $parmPost['sitaporcamento']) : '0';
        $this->sitfinalizado        = array_key_exists('sitfinalizado', $parmPost) ? ($parmPost['sitfinalizado'] === '' ? '0' : $parmPost['sitfinalizado']) : '0';
        $this->localatendimento     = array_key_exists('localatendimento', $parmPost) ? ($parmPost['localatendimento'] === '' ? null : $parmPost['localatendimento']) : null;
        $this->tipointervencao      = array_key_exists('tipointervencao', $parmPost) ? ($parmPost['tipointervencao'] === '' ? null : $parmPost['tipointervencao']) : null;
        $this->msgatendimento       = array_key_exists('msgatendimento', $parmPost) ? ($parmPost['msgatendimento'] === '' ? '' : $parmPost['msgatendimento']) : '';
        $this->msgorcamento         = array_key_exists('msgorcamento', $parmPost) ? ($parmPost['msgorcamento'] === '' ? '' : $parmPost['msgorcamento']) : '';
        $this->controleestoque      = array_key_exists('controleestoque', $parmPost) ? ($parmPost['controleestoque'] === '' ? null : $parmPost['controleestoque']) : null;
        $this->tipodoccobranca      = array_key_exists('tipodoccobranca', $parmPost) ? ($parmPost['tipodoccobranca'] === '' ? null : $parmPost['tipodoccobranca']) : null;
        $this->condpgto             = array_key_exists('condpgto', $parmPost) ? ($parmPost['condpgto'] === '' ? null : $parmPost['condpgto']) : null;
        $this->conta                = array_key_exists('conta', $parmPost) ? ($parmPost['conta'] === '' ? null : $parmPost['conta']) : null;
        $this->genero               = array_key_exists('genero', $parmPost) ? ($parmPost['genero'] === '' ? null : $parmPost['genero']) : null;
        $this->centrocusto          = array_key_exists('centrocusto', $parmPost) ? ($parmPost['centrocusto'] === '' ? null : $parmPost['centrocusto']) : null;

        $filtro = $parmPost['filtro_busca'] ?? $parmGet['filtro_busca'] ?? null;
        $this->filtro_busca = ($filtro !== null && $filtro !== '') ? trim($filtro) : null;

        $this->smarty = new Smarty;
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        $submenu = $parmPost['submenu'] ?? $parmGet['submenu'] ?? '';
        if ($submenu === 'cadastrar') {
            $submenu = 'cadastro';
        }
        if ($submenu === 'exclui') {
            $submenu = 'excluir';
        }
        $this->m_submenu = $submenu;

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('pathBibImagens', ADMhttpBib . '/bib/imagens');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');

        $this->smarty->assign('titulo', "Parametros");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3 ]");
        $this->smarty->assign('disableSort', "[ 4 ]");
        $this->smarty->assign('numLine', "25");
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'inclui':
                if ($this->verificaDireitoUsuario('CatParametros', 'I')) {
                $resultado = $this->incluiParametro();

                if ($resultado === true) {
                    $this->smarty->assign('swalIcon', 'success');
                    $this->smarty->assign('swalTitle', 'Sucesso');
                    $this->smarty->assign('swalText', 'Parâmetros cadastrados com sucesso!');
                    $this->smarty->assign('swalAutoClose', true);
                    $this->desenhaMostraParametros();
                } else {
                    $this->smarty->assign('swalIcon', 'warning');
                    $this->smarty->assign('swalTitle', 'Atenção');
                    $this->smarty->assign('swalText', $resultado);
                    $this->smarty->assign('swalAutoClose', false);
                    $this->m_submenu = 'cadastro';
                    $this->desenhaCadastroParametros(null, null, [
                        'ID' => $this->id,
                        'SITUACAOINCLUSAO' => $this->situacaoinclusao,
                        'SITAGATENDIMENTO' => $this->sitagatendimento,
                        'SITEMATENDIMENTO' => $this->sitematendimento,
                        'SITSOLICITARPECA' => $this->sitsolicitarpeca,
                        'SITAGPECA' => $this->sitagpeca,
                        'SITPECARECEBIDA' => $this->sitpecarecebida,
                        'SITAPORCAMENTO' => $this->sitaporcamento,
                        'SITFINALIZADO' => $this->sitfinalizado,
                        'LOCALATENDIMENTO' => $this->localatendimento,
                        'TIPOINTERVENCAO' => $this->tipointervencao,
                        'MSGATENDIMENTO' => $this->msgatendimento,
                        'MSGORCAMENTO' => $this->msgorcamento,
                        'CONTROLEESTOQUE' => $this->controleestoque,
                        'TIPODOCCOBRANCA' => $this->tipodoccobranca,
                        'CONDPGTO' => $this->condpgto,
                        'CONTA' => $this->conta,
                        'GENERO' => $this->genero,
                        'CENTROCUSTO' => $this->centrocusto,
                    ]);
                }
                }
                break;

            case 'altera':
                if ($this->verificaDireitoUsuario('CatParametros', 'A')) {
                $resultado = $this->alteraParametro();

                if ($resultado === true) {
                    $this->smarty->assign('swalIcon', 'success');
                    $this->smarty->assign('swalTitle', 'Sucesso');
                    $this->smarty->assign('swalText', 'Parâmetros alterados com sucesso!');
                    $this->smarty->assign('swalAutoClose', true);
                    $this->desenhaMostraParametros();
                } else {
                    $this->smarty->assign('swalIcon', 'warning');
                    $this->smarty->assign('swalTitle', 'Atenção');
                    $this->smarty->assign('swalText', $resultado);
                    $this->smarty->assign('swalAutoClose', false);
                    $this->m_submenu = 'alterar';
                    $this->desenhaCadastroParametros(null, null, [
                        'ID' => $this->id,
                        'SITUACAOINCLUSAO' => $this->situacaoinclusao,
                        'SITAGATENDIMENTO' => $this->sitagatendimento,
                        'SITEMATENDIMENTO' => $this->sitematendimento,
                        'SITSOLICITARPECA' => $this->sitsolicitarpeca,
                        'SITAGPECA' => $this->sitagpeca,
                        'SITPECARECEBIDA' => $this->sitpecarecebida,
                        'SITAPORCAMENTO' => $this->sitaporcamento,
                        'SITFINALIZADO' => $this->sitfinalizado,
                        'LOCALATENDIMENTO' => $this->localatendimento,
                        'TIPOINTERVENCAO' => $this->tipointervencao,
                        'MSGATENDIMENTO' => $this->msgatendimento,
                        'MSGORCAMENTO' => $this->msgorcamento,
                        'CONTROLEESTOQUE' => $this->controleestoque,
                        'TIPODOCCOBRANCA' => $this->tipodoccobranca,
                        'CONDPGTO' => $this->condpgto,
                        'CONTA' => $this->conta,
                        'GENERO' => $this->genero,
                        'CENTROCUSTO' => $this->centrocusto,
                    ]);
                }
                }
                break;

            case 'excluir':
            case 'exclui':
                if ($this->verificaDireitoUsuario('CatParametros', 'E')) {
                $resultado = $this->excluiParametro($this->id);

                if ($resultado === true) {
                    $this->smarty->assign('swalIcon', 'success');
                    $this->smarty->assign('swalTitle', 'Sucesso');
                    $this->smarty->assign('swalText', 'Parâmetro excluído com sucesso!');
                    $this->smarty->assign('swalAutoClose', true);
                } else {
                    $this->smarty->assign('swalIcon', 'warning');
                    $this->smarty->assign('swalTitle', 'Atenção');
                    $this->smarty->assign('swalText', $resultado);
                    $this->smarty->assign('swalAutoClose', false);
                }
                $this->desenhaMostraParametros();
                }
                break;

            case 'cadastro':
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('CatParametros', 'I')) {
                $this->m_submenu = 'cadastro';
                $this->desenhaCadastroParametros();
                }
                break;

            case 'alterar':
                if ($this->verificaDireitoUsuario('CatParametros', 'A')) {
                $this->desenhaCadastroParametros();
                }
                break;

            case 'consulta':
                if ($this->verificaDireitoUsuario('CatParametros', 'C')) {
                $this->desenhaMostraParametros();
                }
                break;

            default:
                if ($this->verificaDireitoUsuario('CatParametros', 'C')) {
                $this->desenhaMostraParametros();
                }
        }
    }

    function desenhaMostraParametros()
    {
        if (!empty($this->filtro_busca)) {
            $dados = $this->selecionaParametrosFiltrados($this->filtro_busca);
        } else {
            $dados = $this->selecionaTodosParametros();
        }

        $this->smarty->assign('lanc', $dados);
        $this->smarty->assign('filtro_busca', $this->filtro_busca);
        $this->smarty->assign('mod', 'cat');
        $this->smarty->assign('form', 'parametro');
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);
        $this->smarty->assign('letra', $_GET['letra'] ?? '');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('id', '');

        $this->smarty->display('parametro_mostra.tpl');
    }

    function desenhaCadastroParametros($mensagem = null, $tipoMsg = null, $dados = null)
    {
        if ($dados === null) {
            if ($this->id) {
                $dados = $this->selecionaParametro($this->id);
                $this->smarty->assign('id', $this->id);
            } else {
                $dados = [];
                $this->smarty->assign('id', '');
            }
        } else {
            $this->smarty->assign('id', $this->id);
        }

        $this->smarty->assign('dados', $dados ?? []);

        $situacoes = $this->selecionaSituacoes();
        $this->smarty->assign('situacao_ids', $situacoes['id']);
        $this->smarty->assign('situacao_names', $situacoes['text']);

        $condicoes = $this->selecionaCondicoesPagamento();
        $this->smarty->assign('condpgto_ids', $condicoes['id']);
        $this->smarty->assign('condpgto_names', $condicoes['text']);

        $contas = $this->selecionaContas();
        $this->smarty->assign('conta_ids', $contas['id']);
        $this->smarty->assign('conta_names', $contas['text']);

        $generos = $this->selecionaGeneros();
        $this->smarty->assign('genero_ids', $generos['id']);
        $this->smarty->assign('genero_names', $generos['text']);

        $centros = $this->selecionaCentrosCusto();
        $this->smarty->assign('centrocusto_ids', $centros['id']);
        $this->smarty->assign('centrocusto_names', $centros['text']);

        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('mod', 'cat');
        $this->smarty->assign('form', 'parametro');
        $this->smarty->assign('submenu', $this->m_submenu);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);

        $this->smarty->display('parametro_cadastro.tpl');
    }
}

$parametro = new p_parametro();
$parametro->controle();
