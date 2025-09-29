<?php

/**
 * @package   astec
 * @name      p_consultas
 * @version   3.0.00
 * @copyright 2020
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto 
 * @date      13/05/2020
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);

require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir . "/../../class/fin/c_lancamento.php");

//Class P_produto
class p_consultas extends c_produto
{

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;
    public $m_tipoGrupo = null;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct()
    {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));

        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao == "pesquisar"):
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
            $this->smarty->assign('disableSort', "[ 5 ]");
            $this->smarty->assign('numLine', "25");
        else:
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]");
            $this->smarty->assign('disableSort', "[ 0 ]");
            $this->smarty->assign('numLine', "25");
        endif;

        $this->m_tipoGrupo = $parmPost['tipoGrupo'];

        if ($this->m_par[6] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[6]);

        if ($this->m_par[7] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes + 1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        } else $this->smarty->assign('dataFim', $this->m_par[7]);
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
        switch ($this->m_submenu) {
            default:
                $this->mostraConsulta('');
        } //switch
    }

    // fim controle
    //---------------------------------------------------------------
    //---------------------------------------------------------------

    function mostraConsulta($mensagem)
    {

        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);

        //Data Referencia
        $dataRef_ids[0] = '1';
        $dataRef_names[0] = 'Cadastro';
        $dataRef_ids[1] = '2';
        $dataRef_names[1] = 'Ult Entrada';
        $dataRef_ids[2] = '3';
        $dataRef_names[2] = 'Ult Saida';
        $this->smarty->assign('dataRef_ids',   $dataRef_ids);
        $this->smarty->assign('dataRef_names', $dataRef_names);

        //Tipo CURVA ABC
        $tipoCurva_ids[0] = 'QUANT';
        $tipoCurva_names[0] = 'QTDE VENDIDA';
        $tipoCurva_ids[1] = 'VALOR';
        $tipoCurva_names[1] = 'VALOR VENDIDO';
        $tipoCurva_ids[2] = 'NUMVENDAS';
        $tipoCurva_names[2] = 'NUMERO DE VENDAS';
        $this->smarty->assign('tipoCurva_ids',   $tipoCurva_ids);
        $this->smarty->assign('tipoCurva_names', $tipoCurva_names);

        // TIPO GRUPO 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_TIPO_GRUPO"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_TIPO_GRUPO"] == "true") {
            $ajax_request = 'true';
            // GRUPO      
            if ($this->m_tipoGrupo != '') {
                $sql = "select grupo id, descricao from est_grupo where tipo ='" . $this->m_tipoGrupo . "'";
            } else {
                $sql = "select grupo id, descricao from est_grupo";
            }

            $this->comboSql($sql, $this->m_par[1], $grupo_id, $grupo_ids, $grupo_names);
            $this->smarty->assign('grupo_id', $grupo_id);
            $this->smarty->assign('grupo_ids', $grupo_ids);
            $this->smarty->assign('grupo_names', $grupo_names);
        } else {
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);

            // GRUPO       
            $sql = "select grupo id, descricao from est_grupo";
            $this->comboSql($sql, $this->m_par[1], $grupo_id, $grupo_ids, $grupo_names);
            $this->smarty->assign('grupo_id', $grupo_id);
            $this->smarty->assign('grupo_ids', $grupo_ids);
            $this->smarty->assign('grupo_names', $grupo_names);
        }

        // TIPO GRUPO
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TipoGrupo')";
        $this->comboSql($sql, $this->m_par[1], $tipoGrupo_id, $tipoGrupo_ids, $tipoGrupo_names);
        $this->smarty->assign('tipoGrupo_id', $tipoGrupo_id);
        $this->smarty->assign('tipoGrupo_ids', $tipoGrupo_ids);
        $this->smarty->assign('tipoGrupo_names', $tipoGrupo_names);

        // PRODUTO
        $sql = "select CODIGO AS id, descricao from EST_PRODUTO";
        $this->comboSql($sql, $this->m_par[0], $produto_id, $produto_ids, $produto_names);
        $this->smarty->assign('produto_id', $produto_id);
        $this->smarty->assign('produto_ids',   $produto_ids);
        $this->smarty->assign('produto_names', $produto_names);

        // CENTRO DE CUSTO
        $sql = "select CENTROCUSTO AS id, descricao from FIN_CENTRO_CUSTO";
        $this->comboSql($sql, $this->m_empresacentrocusto, $ccusto_id, $ccusto_ids, $ccusto_names);
        $this->smarty->assign('ccusto_id',    $ccusto_id);
        $this->smarty->assign('ccusto_ids',   $ccusto_ids);
        $this->smarty->assign('ccusto_names', $ccusto_names);

        //TIPO LANCAMENTO
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoLanc')";
        $this->comboSql($sql, $this->m_par[0], $tipoLanc_id, $tipoLanc_ids, $tipoLanc_names);
        $this->smarty->assign('tipoLanc_id',    $tipoLanc_id);
        $this->smarty->assign('tipoLanc_ids',   $tipoLanc_ids);
        $this->smarty->assign('tipoLanc_names', $tipoLanc_names);

        //SITUAÇÃO LANCAMENTO
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto')";
        $this->comboSql($sql, $this->m_par[0], $situacaoLanc_id, $situacaoLanc_ids, $situacaoLanc_names);
        $this->smarty->assign('situacaoLanc_id',    $situacaoLanc_id);
        $this->smarty->assign('situacaoLanc_ids',   $situacaoLanc_ids);
        $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);

        $sql = "SELECT DISTINCT localizacao AS DESCRICAO, localizacao AS ID FROM EST_PRODUTO WHERE localizacao <> '' ORDER BY DESCRICAO";
        $this->comboSql2($sql, $this->m_par[1], $localizacao_id, $localizacao_ids, $localizacao_names);
        $this->smarty->assign('localizacao_id', $localizacao_id);
        $this->smarty->assign('localizacao_ids', $localizacao_ids);
        $this->smarty->assign('localizacao_names', $localizacao_names);

        $this->smarty->display('consultas.tpl');
    }

    function comboSql($sql, $par, &$id, &$ids, &$names)
    {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        $ids[0] = '';
        $names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i + 1] = $result[$i]['ID'];
            $names[$i + 1] = $result[$i]['DESCRICAO'];
        }

        $param = explode(",", $par);
        $i = 0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }
    }

    function comboSql2($sql, $par, &$id, &$ids, &$names)
    {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $ids[$i + 1] = $result[$i]['ID'];
            $names[$i + 1] = $result[$i]['DESCRICAO'];
        }

        $param = explode(",", $par);
        $i = 0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }
    }



    //fim mostraConsultas
    //-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$consultas = new p_consultas();

$consultas->controle();
