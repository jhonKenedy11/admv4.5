<?php
/**
 * @package   admv4.5
 * @name      p_parametros
 * @version   4.5
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      20/02/2026
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/ped/c_pedido_venda.php");
include_once($dir."/../../bib/c_tools.php");

Class p_pedido_relatorios extends c_pedidoVenda {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Parametros");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25");
    }

    /**
    * <b> É responsavel para indicar para onde o sistema ira executar </b>
    * @name controle
    * @param VARCHAR submenu 
    * @return vazio
    */
    function controle(){
        switch ($this->m_submenu){
            default:
                $this->mostraRelatorio('');
        }
    } // fim controle


    /*
    * <b> Listagem de todas as registro cadastrados de tabela banco. </b>
    * @param String $mensagem Mensagem que ira mostrar na tela
    */
    function mostraRelatorio(){


        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign("ADMhttpBib", ADMhttpBib);

        if($this->m_par[0] == ""){
            $this->smarty->assign('dataIni', date("01/m/Y"));
        } else { 
            $this->smarty->assign('dataIni', $this->m_par[0]);
        }

        if($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        } else { 
            $this->smarty->assign('dataFim', $this->m_par[1]);
        }


        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') ";
        $consulta->exec_sql($sql);
        $result = $consulta->resultado ?? [];
        $consulta->close_connection();


        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        //$this->smarty->assign('situacao_id', $situacao_id);
        // FIM COMBOBOX SITUACAO

        // COMBOBOX CENTRO DE CUSTO
        $consulta = new c_banco();
        $sql = "SELECT CENTROCUSTO AS ID, DESCRICAO FROM FIN_CENTRO_CUSTO ORDER BY CENTROCUSTO";
        $consulta->exec_sql($sql);
        $result = $consulta->resultado ?? [];
        $consulta->close_connection();


        for ($i = 0; $i < count($result); $i++) {
            $centro_custo_ids[$i] = $result[$i]['ID'];
            $centro_custo_names[$i] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('centro_custo_ids', $centro_custo_ids);
        $this->smarty->assign('centro_custo_names', $centro_custo_names);
        //$this->smarty->assign('centroCusto_id', $centroCusto_id);
        // FIM COMBOBOX CENTRO DE CUSTO

        // COMBOBOX VENDEDOR
        $consulta = new c_banco();
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO ";
        $consulta->exec_sql($sql);
        $result = $consulta->resultado ?? [];
        $consulta->close_connection();

        for ($i = 0; $i < count($result); $i++) {
            $vendedor_ids[$i] = $result[$i]['ID'];
            $vendedor_names[$i] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('vendedor_ids', $vendedor_ids);
        $this->smarty->assign('vendedor_names',   $vendedor_names);
        //$this->smarty->assign('vendedor_id', $vendedor_id);
        // FIM COMBOBOX VENDEDOR


        // COMBOBOX CONDICAO DE PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM FAT_COND_PGTO WHERE BLOQUEADO = 'A' ORDER BY DESCRICAO;";
        $consulta->exec_sql($sql);
        $result = $consulta->resultado ?? [];
        $consulta->close_connection();


        for ($i = 0; $i < count($result); $i++) {
            $condicao_pagamento_ids[$i] = $result[$i]['ID'];
            $condicao_pagamento_names[$i] = $result[$i]['DESCRICAO'];
        }
        
        $this->smarty->assign('condicao_pagamento_ids',   $condicao_pagamento_ids);
        $this->smarty->assign('condicao_pagamento_names', $condicao_pagamento_names);
        //$this->smarty->assign('condPag_id', $condPag_id);
        // FIM COMBOBOX CONDICAO DE PAGAMENTO


        // COMBOBOX TIPO ENTREGA
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE ALIAS = 'FAT_MENU' AND CAMPO = 'TIPOENTREGA'";
        $consulta->exec_sql($sql);
        $result = $consulta->resultado ?? [];
        $consulta->close_connection();


        for ($i = 0; $i < count($result); $i++) {
            $tipo_entrega_ids[$i] = $result[$i]['ID'];
            $tipo_entrega_names[$i] = $result[$i]['DESCRICAO'];
        }
        
        $this->smarty->assign('tipo_entrega_ids',   $tipo_entrega_ids);
        $this->smarty->assign('tipo_entrega_names', $tipo_entrega_names);
        // FIM COMBOBOX TIPO ENTREGA

        // ########## COMBOBOX MOTIVO ##########)
        $consulta = new c_banco();
        $sql = "SELECT MOTIVO AS ID, DESCRICAO FROM FAT_MOTIVO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();

        $result = $consulta->resultado ?? [];

        for ($i = 0; $i < count($result); $i++) {
            $motivo_ids[$i] = $result[$i]['ID'];
            $motivo_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('motivo_ids', $motivo_ids);
        $this->smarty->assign('motivo_names', $motivo_names);
        //$this->smarty->assign('motivo_id', $this->getIdNatop());
        // ########## FIM COMBOBOX MOTIVO ##########



        $this->smarty->display('pedido_relatorios.tpl');
    } //fim mostraRelatorio
//-------------------------------------------------------------
}
//	END OF THE CLASS
/**
* <b> Rotina principal - cria classe. </b>
*/
$pedido_relatorio = new p_pedido_relatorios();

$pedido_relatorio->controle();
