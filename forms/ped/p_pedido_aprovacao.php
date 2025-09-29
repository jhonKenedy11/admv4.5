<?php

/**
 * @package   astec
 * @name      p_nota_fiscal
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko <lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_aprovacao.php");
require_once($dir . "/../../class/crm/c_conta.php"); 
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");  
include_once($dir."/../../bib/c_date.php");


//Class p_pedido_aprovacao
Class p_pedido_aprovacao extends c_pedido_aprovacao {

    private $m_submenu  = NULL;
    private $m_letra    = NULL;
    private $m_opcao    = NULL;
    private $m_id       = NULL;
    private $obs        = NULL;
    private $m_par      = NULL;
    private $m_checkbox = NULL;
    
    
    public $smarty = NULL;

    
//---------------------------------------------------------------
//---------------------------------------------------------------
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT); 

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // ajax
        $this->ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";
        
        // inicializa variaveis de controle
       // $this->m_submenu = $this->parmPost['submenu'] ? $this->parmPost['submenu'] : (isset($parmGet['submenu']) ? $parmGet['submenu']);
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->m_checkbox = (isset($parmPost['checkPeriodo']) ? $parmPost['checkPeriodo'] : '');
        $this->m_par = explode("|", $this->m_letra);

        
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // metodo SET dos dados do FORM para o TABLE
        $this->m_id = (isset($parmPost['id']) ? $parmPost['id'] : "");
        $this->obs = (isset($parmPost['observacao']) ? $parmPost['observacao'] : '');
      
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Pedido Aprovação");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7 ]"); 
        $this->smarty->assign('disableSort', "[ 7 ]"); 
        $this->smarty->assign('numLine', "25"); 
                
        // include do javascript
//        include ADMjs . "/est/s_nota_fiscal.js";
}

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {

            case 'aprovado':
                $this->pedido_aprovado($this->m_id);
                $this->mostraPedidoAprovacao('Pedido Aprovado - '.$this->m_id, 'sucesso');
                break; 
            
            case 'desaprovado':
                $this->pedido_desaprovado($this->m_id, $this->obs);
                $this->mostraPedidoAprovacao('');
                break;            
            default:
                if ($this->verificaDireitoUsuario('PEDAPROVACAO', 'C')) {
                        $this->mostraPedidoAprovacao('');
                }
        }
    }
        
    
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedidoAprovacao($mensagem=NULL,  $tipoMsg = NULL) {        
        
       
        if ($this->m_letra != '') {           
            $lanc = $this->select_pedido_aprovacao_letra($this->m_letra);
        }
      

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('lanc', $lanc);

        //CENTRO DE CUSTO   
        $sql = "SELECT CENTROCUSTO AS ID, DESCRICAO FROM FIN_CENTRO_CUSTO";
        $this->comboSql($sql, $this->m_empresacentrocusto, $ccusto_id, $ccusto_ids, $ccusto_names);
        $this->smarty->assign('ccusto_id',    $ccusto_id);
        $this->smarty->assign('ccusto_ids',   $ccusto_ids);
        $this->smarty->assign('ccusto_names', $ccusto_names);
        
        // VENDEDOR 
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO WHERE TIPO = 'V'";
        $this->comboSql($sql, $this->m_par[0], $vendedor_id, $vendedor_ids, $vendedor_names);
        $this->smarty->assign('vendedor_id', $vendedor_id);
        $this->smarty->assign('vendedor_ids',   $vendedor_ids);
        $this->smarty->assign('vendedor_names', $vendedor_names);

        //PERIODO

        if($this->m_par[4] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[4]);
        
        if($this->m_par[5] == "") {
        	$dia = date("d");
        	$mes = date("m");
        	$ano = date("Y");
        	$data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
        	$this->smarty->assign('dataFim', $data);
        }
        else $this->smarty->assign('dataFim', $this->m_par[5]);

        
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST"] == "true"):
            $ajax_request = 'true';

            $sql = "SELECT OBS FROM FAT_PEDIDO WHERE ID = ".$this->m_id;

            $consulta = new c_banco();
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;

            $this->smarty->assign('observacao', $result[0]['OBS']);


        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
  
        endif; 

        $this->smarty->assign('codCotacao', $this->m_par[2]);
        $this->smarty->assign('usrfatura', $this->m_userid);    
        $this->smarty->assign('checked', $this->m_checkbox); 
        $this->smarty->display('pedido_aprovacao_mostra.tpl');
    }



    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $ids[0] = '';
        $names[0] = 'Selecione um Vendedor';

        for ($i = 0; $i < count($result); $i++) {
            $ids[$i+1] = $result[$i]['ID'];
            $names[$i+1] = $result[$i]['DESCRICAO'];
        }
        
        $param = explode(",", $par);
        $i=0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }    
    }


//fim mostraPedidoAprovacao
//-------------------------------------------------------------
}



//	END OF THE CLASS
// Rotina principal - cria classe
$pedidoAprovacao = new p_pedido_aprovacao();

$pedidoAprovacao->controle();
?>
