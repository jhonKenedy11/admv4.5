<?php
/**
 * Formulário para administração dos parâmetros de estoque
 * Arquivo: forms/est/p_parametro.php
 * Atualizado seguindo padrões ADM v4.5 com telas separadas
 */

if (!defined('ADMpath')) exit;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_parametro.php");

class p_parametro extends c_parametro
{
    public $smarty = null;
    protected $m_submenu = null;

    function __construct()
    {
        session_start();
        c_user::from_array($_SESSION['user_array']);

        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Atribuição direta das propriedades
        $this->id                       = $parmPost['id'] ?? $parmGet['id'] ?? '';
        $this->cfop                     = $parmPost['cfop'] ?? $parmGet['cfop'] ?? '';
        $this->natoperacao              = $parmPost['natoperacao'] ?? $parmGet['natoperacao'] ?? '';
        $this->condpgto                 = $parmPost['condpgto'] ?? $parmGet['condpgto'] ?? '';
        $this->generomovimento          = $parmPost['generomovimento'] ?? $parmGet['generomovimento'] ?? '';
        $this->genero                   = $parmPost['genero'] ?? $parmGet['genero'] ?? '';
        $this->conta                    = $parmPost['conta'] ?? $parmGet['conta'] ?? '';
        $this->serie                    = $parmPost['serie'] ?? $parmGet['serie'] ?? '';
        $this->modofin                  = $parmPost['modofin'] ?? $parmGet['modofin'] ?? '';
        $this->tipodoc                  = $parmPost['tipodoc'] ?? $parmGet['tipodoc'] ?? '';
        $this->natopentrada             = $parmPost['natopentrada'] ?? $parmGet['natopentrada'] ?? '';
        $this->clientepadrao            = $parmPost['clientepadrao'] ?? $parmGet['clientepadrao'] ?? '1';
        $this->modelo                   = $parmPost['modelo'] ?? $parmGet['modelo'] ?? '55';
        $this->grupopadrao              = $parmPost['grupopadrao'] ?? $parmGet['grupopadrao'] ?? '';
        $this->consultaestoquezero      = $parmPost['consultaestoquezero'] ?? $parmGet['consultaestoquezero'] ?? 'S';
        $this->controlaestoque          = $parmPost['controlaestoque'] ?? $parmGet['controlaestoque'] ?? 'S';
        $this->integrafin               = $parmPost['integrafin'] ?? $parmGet['integrafin'] ?? 'S';
        $this->validanfauto             = $parmPost['validanfauto'] ?? $parmGet['validanfauto'] ?? 'S';
        $this->centrocusto              = $parmPost['centrocusto'] ?? $parmGet['centrocusto'] ?? '';
        $this->tipovalidacao            = $parmPost['tipovalidacao'] ?? $parmGet['tipovalidacao'] ?? 'N';
        $this->percdescmaximo           = $parmPost['percdescmaximo'] ?? $parmGet['percdescmaximo'] ?? '0.0000';
        $this->precobase                = $parmPost['precobase'] ?? $parmGet['precobase'] ?? 'C';
        $this->percalculo               = $parmPost['percalculo'] ?? $parmGet['percalculo'] ?? '0.0000';
        $this->nfs_serie                = $parmPost['nfs_serie'] ?? $parmGet['nfs_serie'] ?? '';
        $this->nfs_situacao_tributaria  = $parmPost['nfs_situacao_tributaria'] ?? $parmGet['nfs_situacao_tributaria'] ?? '';
        
        // Novos campos
        $this->servico                  = $parmPost['servico'] ?? $parmGet['servico'] ?? '';
        $this->situacao_tributaria      = $parmPost['situacao_tributaria'] ?? $parmGet['situacao_tributaria'] ?? '';
        $this->inss                     = $parmPost['inss'] ?? $parmGet['inss'] ?? '0.00';
        $this->pis                      = $parmPost['pis'] ?? $parmGet['pis'] ?? '0.00';
        $this->cofins                   = $parmPost['cofins'] ?? $parmGet['cofins'] ?? '0.00';
        $this->ir                       = $parmPost['ir'] ?? $parmGet['ir'] ?? '0.00';
        $this->contribuicao_social      = $parmPost['contribuicao_social'] ?? $parmGet['contribuicao_social'] ?? '0.00';
        $this->parcela                  = $parmPost['parcela'] ?? $parmGet['parcela'] ?? '';
        
        // Campos de controle
        $this->filtro_empresa           = $parmPost['filtro_empresa'] ?? $parmGet['filtro_empresa'] ?? '';

        $this->smarty = new Smarty;
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir  = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir   = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir    = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = $parmGet['submenu'] ?? $parmPost['submenu'] ?? '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('pathBibImagens',  ADMhttpBib . '/bib/imagens');
        //ADMraizFonte
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');


        $this->smarty->assign('titulo', "Parametros");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'inclui':
                $dados = $this->montarArrayDados();
                $resultado = $this->incluiParametro($dados);
                
                if ($resultado === true) {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Parâmetros cadastrados com sucesso!',timer: 3000,showConfirmButton: false}).then(function(){window.location='?mod=est&form=parametro';});</script>";
                    exit;
                } else {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: '".addslashes($resultado)."',confirmButtonText: 'OK'});</script>";
                }
                $this->desenhaCadastroParametros();
                break;

            case 'altera':
                $dados = $this->montarArrayDados();
                $resultado = $this->alteraParametro($dados);
                
                if ($resultado === true) {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Parâmetros alterados com sucesso!',timer: 3000,showConfirmButton: false}).then(function(){window.location='?mod=est&form=parametro';});</script>";
                    exit;
                } else {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: '".addslashes($resultado)."',confirmButtonText: 'OK'});</script>";
                }
                $this->desenhaCadastroParametros();
                break;

            case 'excluir':
                $resultado = $this->excluiParametro($this->filial, $this->modelo);
                
                if ($resultado === true) {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>Swal.fire({icon: 'success',title: 'Sucesso',width: 510,text: 'Parâmetro excluído com sucesso!',timer: 3000,showConfirmButton: false}).then(function(){window.location='?mod=est&form=parametro';});</script>";
                    exit;
                } else {
                    echo "<script type='text/javascript' src='".ADMsweetAlert2."/dist/sweetalert2.all.min.js'></script>";
                    echo "<script>Swal.fire({icon: 'warning',title: 'Atenção',width: 510,text: '".addslashes($resultado)."',confirmButtonText: 'OK'}).then(function(){window.location='?mod=est&form=parametro';});</script>";
                    exit;
                }
                break;

            case 'cadastro':
                $this->desenhaCadastroParametros();
                break;

            case 'alterar':
                $this->desenhaCadastroParametros();
                break;

            case 'consulta':
                $this->desenhaMostraParametros();
                break;

            default:
                $this->desenhaMostraParametros();
        }
    }

    /**
     * Desenha tela de listagem/mostra dos parâmetros
     */
    function desenhaMostraParametros($mensagem = null, $tipoMsg = null)
    {
        // Buscar dados para listagem
        if ($this->id) {
            $dados = $this->selecionaParametrosFiltrados($this->id);
        } else {
            $dados = $this->selecionaTodosParametros();
        }
        
        // Usar $lanc seguindo padrão ADM v4.5
        $this->smarty->assign('lanc', $dados);
        $this->smarty->assign('filtro_empresa', $this->filtro_empresa);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        
        // Passar variáveis de contexto para o template
        $this->smarty->assign('mod', 'est');
        $this->smarty->assign('form', 'parametro');
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);
        $this->smarty->assign('letra', $_GET['letra'] ?? '');
        $this->smarty->assign('subMenu', $this->m_submenu);
        
        $this->smarty->display('parametro_mostra.tpl');
    }

    /**
     * Desenha tela de cadastro/alteração dos parâmetros
     */
    function desenhaCadastroParametros($mensagem = null, $tipoMsg = null, $dados = null)
    {
        if ($dados === null) {

            if ($this->id) {
                // Modo alteração - buscar dados específicos
                $dados = $this->selecionaParametro($this->id);
                $this->smarty->assign('dados',  $dados ?? []);
            } else {
                // Modo cadastro
                $this->smarty->assign('dados', []);
            }
        }

        // ######## Buscar dados para os combos ########

        // Buscar dados para o combo de empresas
        $empresa = $this->selecionaEmpresas();
        $this->smarty->assign('empresas_ids', $empresa['id']);
        $this->smarty->assign('empresas_names', $empresa['text']);
        $this->smarty->assign('empresa_id', $dados['ID'] ?? '');

        // Buscar dados para o combo de serviços
        $servicos = $this->selecionaServicos();
        $this->smarty->assign('servicos_ids', $servicos['id']);
        $this->smarty->assign('servicos_names', $servicos['text']);
        $this->smarty->assign('servico_id', $dados['NFS_SERVICO'] ?? '');

        // Buscar dados para o combo de situações tributárias
        $situacoes_tributarias = $this->selecionaSituacaoTributaria();
        $this->smarty->assign('situacao_tributaria_ids', $situacoes_tributarias['id']);
        $this->smarty->assign('situacao_tributaria_names', $situacoes_tributarias['text']);
        $this->smarty->assign('situacao_tributaria_id', $dados['NFS_SITUACAO_TRIBUTARIA'] ?? '');

        // Buscar dados para o combo de parcelas
        $parcelas = $this->selecionaParcelas();
        $this->smarty->assign('parcelas_ids', $parcelas['id']);
        $this->smarty->assign('parcelas_names', $parcelas['text']);
        $this->smarty->assign('parcela_id', $dados['NFS_PARCELA'] ?? '');


        // FALTA AJUSTAR OS COMBOS 
        $condicoes_pagamento = $this->selecionaCondicoesPagamento();
        $generos = $this->selecionaGeneros();
        $contas = $this->selecionaContas();
        $grupos = $this->selecionaGrupos();
        $clientes = $this->selecionaClientes();
        $centros_custo = $this->selecionaCentrosCusto();


        $this->smarty->assign('condicoes_pagamento', $condicoes_pagamento);
        $this->smarty->assign('generos', $generos);
        $this->smarty->assign('contas', $contas);
        $this->smarty->assign('grupos', $grupos);
        $this->smarty->assign('clientes', $clientes);
        $this->smarty->assign('centros_custo', $centros_custo);
        $this->smarty->assign('parcelas', $parcelas);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        
        // Passar variáveis de contexto para o template
        $this->smarty->assign('mod', 'est');
        $this->smarty->assign('form', 'parametro');
        $this->smarty->assign('submenu', $this->m_submenu);
        
        $this->smarty->display('parametro_cadastro.tpl');
    }
}

// Execução principal
$parametro = new p_parametro();
$parametro->controle();