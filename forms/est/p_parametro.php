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

        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];
        $parmGet  = filter_input_array(INPUT_GET, FILTER_DEFAULT) ?? [];

        $this->filial                   = array_key_exists('filial', $parmPost) ? ($parmPost['filial'] === '' ? null : $parmPost['filial']) : ($parmGet['filial'] ?? null);
        $this->cfop                     = array_key_exists('cfop', $parmPost) ? ($parmPost['cfop'] === '' ? null : $parmPost['cfop']) : null;
        $this->natoperacao              = array_key_exists('natoperacao', $parmPost) ? ($parmPost['natoperacao'] === '' ? null : $parmPost['natoperacao']) : null;
        $this->condpgto                 = array_key_exists('condpgto', $parmPost) ? ($parmPost['condpgto'] === '' ? null : $parmPost['condpgto']) : null;
        $this->generomovimento          = array_key_exists('generomovimento', $parmPost) ? ($parmPost['generomovimento'] === '' ? null : $parmPost['generomovimento']) : null;
        $this->genero                   = array_key_exists('genero', $parmPost) ? ($parmPost['genero'] === '' ? null : $parmPost['genero']) : null;
        $this->genero_extrato           = array_key_exists('genero_extrato', $parmPost) ? ($parmPost['genero_extrato'] === '' ? null : $parmPost['genero_extrato']) : null;
        $this->conta                    = array_key_exists('conta', $parmPost) ? ($parmPost['conta'] === '' ? null : $parmPost['conta']) : null;
        $this->serie                    = array_key_exists('serie', $parmPost) ? ($parmPost['serie'] === '' ? null : $parmPost['serie']) : null;
        $this->modofin                  = array_key_exists('modofin', $parmPost) ? ($parmPost['modofin'] === '' ? null : $parmPost['modofin']) : null;
        $this->tipodoc                  = array_key_exists('tipodoc', $parmPost) ? ($parmPost['tipodoc'] === '' ? null : $parmPost['tipodoc']) : null;
        $this->natopentrada             = array_key_exists('natopentrada', $parmPost) ? ($parmPost['natopentrada'] === '' ? null : $parmPost['natopentrada']) : null;
        $this->clientepadrao            = array_key_exists('clientepadrao', $parmPost) ? ($parmPost['clientepadrao'] === '' ? '1' : $parmPost['clientepadrao']) : '1';
        $this->modelo                   = array_key_exists('modelo', $parmPost) ? ($parmPost['modelo'] === '' ? '55' : $parmPost['modelo']) : ($parmGet['modelo'] ?? '55');
        $this->grupopadrao              = array_key_exists('grupopadrao', $parmPost) ? ($parmPost['grupopadrao'] === '' ? null : $parmPost['grupopadrao']) : null;
        $this->consultaestoquezero      = array_key_exists('consultaestoquezero', $parmPost) ? ($parmPost['consultaestoquezero'] === '' ? null : $parmPost['consultaestoquezero']) : null;
        $this->controlaestoque          = array_key_exists('controlaestoque', $parmPost) ? ($parmPost['controlaestoque'] === '' ? null : $parmPost['controlaestoque']) : null;
        $this->integrafin               = array_key_exists('integrafin', $parmPost) ? ($parmPost['integrafin'] === '' ? null : $parmPost['integrafin']) : null;
        $this->validanfauto             = array_key_exists('validanfauto', $parmPost) ? ($parmPost['validanfauto'] === '' ? null : $parmPost['validanfauto']) : null;
        $this->centrocusto              = array_key_exists('centrocusto', $parmPost) ? ($parmPost['centrocusto'] === '' ? null : $parmPost['centrocusto']) : null;
        $this->tipovalidacao            = array_key_exists('tipovalidacao', $parmPost) ? ($parmPost['tipovalidacao'] === '' ? 'N' : $parmPost['tipovalidacao']) : 'N';
        $this->percdescmaximo           = array_key_exists('percdescmaximo', $parmPost) ? ($parmPost['percdescmaximo'] === '' ? '0.0000' : $parmPost['percdescmaximo']) : '0.0000';
        $this->precobase                = array_key_exists('precobase', $parmPost) ? ($parmPost['precobase'] === '' ? 'C' : $parmPost['precobase']) : 'C';
        $this->percalculo               = array_key_exists('percalculo', $parmPost) ? ($parmPost['percalculo'] === '' ? '0.0000' : $parmPost['percalculo']) : '0.0000';
        $this->calcula_ipi_custo_reposicao = array_key_exists('calcula_ipi_custo_reposicao', $parmPost) ? ($parmPost['calcula_ipi_custo_reposicao'] === '' ? 'N' : $parmPost['calcula_ipi_custo_reposicao']) : 'N';
        $this->calcula_st_custo_reposicao  = array_key_exists('calcula_st_custo_reposicao', $parmPost) ? ($parmPost['calcula_st_custo_reposicao'] === '' ? 'N' : $parmPost['calcula_st_custo_reposicao']) : 'N';
        $this->xmlconferirestoque       = array_key_exists('xmlconferirestoque', $parmPost) ? ($parmPost['xmlconferirestoque'] === '' ? 'N' : $parmPost['xmlconferirestoque']) : 'N';
        $this->xmlmanterorigemcst       = array_key_exists('xmlmanterorigemcst', $parmPost) ? ($parmPost['xmlmanterorigemcst'] === '' ? 'S' : $parmPost['xmlmanterorigemcst']) : 'S';
        $this->nfs_serie                = array_key_exists('nfs_serie', $parmPost) ? ($parmPost['nfs_serie'] === '' ? null : $parmPost['nfs_serie']) : null;
        $this->nfs_servico              = array_key_exists('nfs_servico', $parmPost) ? ($parmPost['nfs_servico'] === '' ? null : $parmPost['nfs_servico']) : null;
        $this->nfs_situacao_tributaria  = array_key_exists('nfs_situacao_tributaria', $parmPost) ? ($parmPost['nfs_situacao_tributaria'] === '' ? null : $parmPost['nfs_situacao_tributaria']) : null;
        $this->nfs_inss                 = array_key_exists('nfs_inss', $parmPost) ? ($parmPost['nfs_inss'] === '' ? '0.00' : $parmPost['nfs_inss']) : '0.00';
        $this->nfs_pis                  = array_key_exists('nfs_pis', $parmPost) ? ($parmPost['nfs_pis'] === '' ? '0.00' : $parmPost['nfs_pis']) : '0.00';
        $this->nfs_cofins               = array_key_exists('nfs_cofins', $parmPost) ? ($parmPost['nfs_cofins'] === '' ? '0.00' : $parmPost['nfs_cofins']) : '0.00';
        $this->nfs_ir                   = array_key_exists('nfs_ir', $parmPost) ? ($parmPost['nfs_ir'] === '' ? '0.00' : $parmPost['nfs_ir']) : '0.00';
        $this->nfs_contribuicao_social  = array_key_exists('nfs_contribuicao_social', $parmPost) ? ($parmPost['nfs_contribuicao_social'] === '' ? '0.00' : $parmPost['nfs_contribuicao_social']) : '0.00';
        $this->nfs_parcela              = array_key_exists('nfs_parcela', $parmPost) ? ($parmPost['nfs_parcela'] === '' ? null : $parmPost['nfs_parcela']) : null;
        $this->nfs_user                 = array_key_exists('nfs_user', $parmPost) ? ($parmPost['nfs_user'] === '' ? null : $parmPost['nfs_user']) : null;
        $this->nfs_password             = array_key_exists('nfs_password', $parmPost) ? ($parmPost['nfs_password'] === '' ? null : $parmPost['nfs_password']) : null;

        $filtro = $parmPost['filtro_empresa'] ?? $parmGet['filtro_empresa'] ?? null;
        $this->filtro_empresa = ($filtro !== null && $filtro !== '') ? trim($filtro) : null;

        $this->smarty = new Smarty;
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
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
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5 ]");
        $this->smarty->assign('disableSort', "[ 6 ]");
        $this->smarty->assign('numLine', "25");
    }

    function controle()
    {
        switch ($this->m_submenu) {
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstParametros', 'I')) {
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
                        'FILIAL' => $this->filial,
                        'CFOP' => $this->cfop,
                        'NATOPERACAO' => $this->natoperacao,
                        'CONDPGTO' => $this->condpgto,
                        'GENEROMOVIMENTO' => $this->generomovimento,
                        'GENERO' => $this->genero,
                        'GENERO_EXTRATO' => $this->genero_extrato,
                        'CONTA' => $this->conta,
                        'SERIE' => $this->serie,
                        'MODOFIN' => $this->modofin,
                        'TIPODOC' => $this->tipodoc,
                        'NATOPENTRADA' => $this->natopentrada,
                        'CLIENTEPADRAO' => $this->clientepadrao,
                        'MODELO' => $this->modelo,
                        'GRUPOPADRAO' => $this->grupopadrao,
                        'CONSULTAESTOQUEZERO' => $this->consultaestoquezero,
                        'CONTROLAESTOQUE' => $this->controlaestoque,
                        'INTEGRAFIN' => $this->integrafin,
                        'VALIDANFAUTO' => $this->validanfauto,
                        'CENTROCUSTO' => $this->centrocusto,
                        'TIPOVALIDACAO' => $this->tipovalidacao,
                        'PERCDESCMAXIMO' => $this->percdescmaximo,
                        'PRECOBASE' => $this->precobase,
                        'PERCALCULO' => $this->percalculo,
                        'CALCULA_IPI_CUSTO_REPOSICAO' => $this->calcula_ipi_custo_reposicao,
                        'CALCULA_ST_CUSTO_REPOSICAO' => $this->calcula_st_custo_reposicao,
                        'XMLCONFERIRESTOQUE' => $this->xmlconferirestoque,
                        'XMLMANTERORIGEMCST' => $this->xmlmanterorigemcst,
                        'NFS_SERIE' => $this->nfs_serie,
                        'NFS_SERVICO' => $this->nfs_servico,
                        'NFS_SITUACAO_TRIBUTARIA' => $this->nfs_situacao_tributaria,
                        'NFS_INSS' => $this->nfs_inss,
                        'NFS_PIS' => $this->nfs_pis,
                        'NFS_COFINS' => $this->nfs_cofins,
                        'NFS_IR' => $this->nfs_ir,
                        'NFS_CONTRIBUICAO_SOCIAL' => $this->nfs_contribuicao_social,
                        'NFS_PARCELA' => $this->nfs_parcela,
                        'NFS_USER' => $this->nfs_user,
                        'NFS_PASSWORD' => $this->nfs_password,
                    ]);
                }
                }
                break;

            case 'altera':
                if ($this->verificaDireitoUsuario('EstParametros', 'A')) {
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
                        'FILIAL' => $this->filial,
                        'CFOP' => $this->cfop,
                        'NATOPERACAO' => $this->natoperacao,
                        'CONDPGTO' => $this->condpgto,
                        'GENEROMOVIMENTO' => $this->generomovimento,
                        'GENERO' => $this->genero,
                        'GENERO_EXTRATO' => $this->genero_extrato,
                        'CONTA' => $this->conta,
                        'SERIE' => $this->serie,
                        'MODOFIN' => $this->modofin,
                        'TIPODOC' => $this->tipodoc,
                        'NATOPENTRADA' => $this->natopentrada,
                        'CLIENTEPADRAO' => $this->clientepadrao,
                        'MODELO' => $this->modelo,
                        'GRUPOPADRAO' => $this->grupopadrao,
                        'CONSULTAESTOQUEZERO' => $this->consultaestoquezero,
                        'CONTROLAESTOQUE' => $this->controlaestoque,
                        'INTEGRAFIN' => $this->integrafin,
                        'VALIDANFAUTO' => $this->validanfauto,
                        'CENTROCUSTO' => $this->centrocusto,
                        'TIPOVALIDACAO' => $this->tipovalidacao,
                        'PERCDESCMAXIMO' => $this->percdescmaximo,
                        'PRECOBASE' => $this->precobase,
                        'PERCALCULO' => $this->percalculo,
                        'CALCULA_IPI_CUSTO_REPOSICAO' => $this->calcula_ipi_custo_reposicao,
                        'CALCULA_ST_CUSTO_REPOSICAO' => $this->calcula_st_custo_reposicao,
                        'XMLCONFERIRESTOQUE' => $this->xmlconferirestoque,
                        'XMLMANTERORIGEMCST' => $this->xmlmanterorigemcst,
                        'NFS_SERIE' => $this->nfs_serie,
                        'NFS_SERVICO' => $this->nfs_servico,
                        'NFS_SITUACAO_TRIBUTARIA' => $this->nfs_situacao_tributaria,
                        'NFS_INSS' => $this->nfs_inss,
                        'NFS_PIS' => $this->nfs_pis,
                        'NFS_COFINS' => $this->nfs_cofins,
                        'NFS_IR' => $this->nfs_ir,
                        'NFS_CONTRIBUICAO_SOCIAL' => $this->nfs_contribuicao_social,
                        'NFS_PARCELA' => $this->nfs_parcela,
                        'NFS_USER' => $this->nfs_user,
                        'NFS_PASSWORD' => $this->nfs_password,
                    ]);
                }
                }
                break;

            case 'excluir':
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstParametros', 'E')) {
                $resultado = $this->excluiParametro();

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
                if ($this->verificaDireitoUsuario('EstParametros', 'I')) {
                $this->m_submenu = 'cadastro';
                $this->desenhaCadastroParametros();
                }
                break;

            case 'alterar':
                if ($this->verificaDireitoUsuario('EstParametros', 'A')) {
                $this->desenhaCadastroParametros();
                }
                break;

            case 'consulta':
                if ($this->verificaDireitoUsuario('EstParametros', 'C')) {
                $this->desenhaMostraParametros();
                }
                break;

            default:
                if ($this->verificaDireitoUsuario('EstParametros', 'C')) {
                $this->desenhaMostraParametros();
                }
        }
    }

    function desenhaMostraParametros($mensagem = null, $tipoMsg = null)
    {
        if (!empty($this->filtro_empresa)) {
            $dados = $this->selecionaParametrosFiltrados($this->filtro_empresa);
        } else {
            $dados = $this->selecionaTodosParametros();
        }

        $labelsModelo = ['55' => '55 - NFe', '65' => '65 - NFCe', '57' => '57 - CTe'];
        foreach ($dados as $i => $row) {
            $cnpj = $row['CNPJ'] ?? '';
            if (!empty($cnpj)) {
                $numeros = preg_replace('/\D/', '', $cnpj);
                if (strlen($numeros) === 14) {
                    $dados[$i]['CNPJ_FORMATADO'] = substr($numeros, 0, 2) . '.' .
                        substr($numeros, 2, 3) . '.' .
                        substr($numeros, 5, 3) . '/' .
                        substr($numeros, 8, 4) . '-' .
                        substr($numeros, 12, 2);
                } else {
                    $dados[$i]['CNPJ_FORMATADO'] = $cnpj;
                }
            } else {
                $dados[$i]['CNPJ_FORMATADO'] = '';
            }
            $modelo = $row['MODELO'] ?? '';
            $dados[$i]['MODELO_DESC'] = $labelsModelo[$modelo] ?? $modelo;
            if (!empty($row['NOMEFANTASIA'])) {
                $dados[$i]['NOMEEMPRESA'] = $row['NOMEFANTASIA'];
            }
        }

        $this->smarty->assign('lanc', $dados);
        $this->smarty->assign('filtro_empresa', $this->filtro_empresa);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('mod', 'est');
        $this->smarty->assign('form', 'parametro');
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);
        $this->smarty->assign('letra', $_GET['letra'] ?? '');
        $this->smarty->assign('subMenu', $this->m_submenu);

        $this->smarty->display('parametro_mostra.tpl');
    }

    function desenhaCadastroParametros($mensagem = null, $tipoMsg = null, $dados = null)
    {
        if ($dados === null) {
            $dados = $this->selecionaParametro($this->filial, $this->modelo);

            if (!empty($dados['FILIAL'])) {
                $this->filial = $dados['FILIAL'];
            }
            if (!empty($dados['MODELO'])) {
                $this->modelo = $dados['MODELO'];
            }
        }

        $this->smarty->assign('dados', $dados ?? []);

        $empresa = $this->selecionaEmpresas();
        $this->smarty->assign('empresas_ids', $empresa['id']);
        $this->smarty->assign('empresas_names', $empresa['text']);
        $this->smarty->assign('empresa_id', $dados['FILIAL'] ?? '');

        $booleanos = $this->selecionaBooleanos();
        $this->smarty->assign('boolean_ids', $booleanos['id']);
        $this->smarty->assign('boolean_names', $booleanos['text']);

        $servicos = $this->selecionaServicos();
        $this->smarty->assign('servicos_ids', $servicos['id']);
        $this->smarty->assign('servicos_names', $servicos['text']);
        $this->smarty->assign('servico_id', $dados['NFS_SERVICO'] ?? '');

        $situacoes_tributarias = $this->selecionaSituacaoTributaria();
        $this->smarty->assign('situacao_tributaria_ids', $situacoes_tributarias['id']);
        $this->smarty->assign('situacao_tributaria_names', $situacoes_tributarias['text']);
        $this->smarty->assign('situacao_tributaria_id', $dados['NFS_SITUACAO_TRIBUTARIA'] ?? '');

        $parcelas = $this->selecionaParcelas();
        $this->smarty->assign('parcelas_ids', $parcelas['id']);
        $this->smarty->assign('parcelas_names', $parcelas['text']);
        $this->smarty->assign('parcela_id', $dados['NFS_PARCELA'] ?? '');

        $condicoes_pagamento = $this->selecionaCondicoesPagamento();
        $generos_saida = $this->incluiGeneroSelecionado(
            $this->selecionaGeneros('R'),
            $dados['GENERO'] ?? null
        );
        $generos_entrada = $this->incluiGeneroSelecionado(
            $this->selecionaGeneros('P'),
            $dados['GENERO_EXTRATO'] ?? null
        );
        $contas = $this->selecionaContas();
        $grupos = $this->selecionaGrupos();
        $clientes = $this->selecionaClientes();
        $centros_custo = $this->selecionaCentrosCusto();

        $this->smarty->assign('condicoes_pagamento', $condicoes_pagamento);
        $this->smarty->assign('generos_saida', $generos_saida);
        $this->smarty->assign('generos_entrada', $generos_entrada);
        $this->smarty->assign('contas', $contas);
        $this->smarty->assign('grupos', $grupos);
        $this->smarty->assign('clientes', $clientes);
        $this->smarty->assign('centros_custo', $centros_custo);
        $this->smarty->assign('parcelas', $parcelas);

        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('mod', 'est');
        $this->smarty->assign('form', 'parametro');
        $this->smarty->assign('submenu', $this->m_submenu);
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);

        $this->smarty->display('parametro_cadastro.tpl');
    }
}

$parametro = new p_parametro();
$parametro->controle();
