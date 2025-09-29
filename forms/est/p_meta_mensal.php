<?php


if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_meta_mensal.php");

Class p_meta_mensal extends c_meta_mensal {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    
    function __construct() {
        
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Classe");
        $this->smarty->assign('colVis', "[ 0, 1, 2 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setCentroCusto(isset($parmPost['centrocusto']) ? $parmPost['centrocusto'] : '');
        $this->setTotalDiaMes(isset($parmPost['totaldiames']) ? $parmPost['totaldiames'] : '');
        $this->setMetaMargem(isset($parmPost['metamargem']) ? $parmPost['metamargem'] : '');
        $this->setAno(isset($parmPost['ano']) ? $parmPost['ano'] : '');
        $this->setMes(isset($parmPost['mes']) ? $parmPost['mes'] : '');
        $this->setMetaId(isset($parmPost['metaid']) ? $parmPost['metaid'] : '');
        $this->setMeta(isset($parmPost['meta']) ? $parmPost['meta'] : '0');
        $this->setVendedor(isset($parmPost['vendedor']) ? $parmPost['vendedor'] : '');
    }

    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                {
                    $this->desenharCadastroMetaMensal();
                }
                break;
            case 'alterar':
                {
                    $this->buscar_meta_mensal();
                    $this->desenharCadastroMetaMensal();
                }
                break;
            case 'inclui':
                {  
                    $transaction = new c_banco();
                    $transaction->inicioTransacao($transaction->id_connection);
                    $result = true;
                    $identificador = $this->incluir_meta_mensal($transaction->id_connection);
                    $transaction->commit($transaction->id_connection);
                                            
                    $this->mostrarMetaMensal('');
                }
                break;
            case 'exclui':
                {
                    $this->excluir_meta_mensal();
                    $this->mostrarMetaMensal('Registro excluido.');
                }
                break;
            case 'altera':
                $this->alterar_meta_mensal();               
                $this->mostrarMetaMensal('Registro salvo.');
                break;
            case 'cadastrarVendedor':
                {
                    $this->desenharCadastroMetaMensalVendedor();
                }
                break;
            case 'incluirVendedor':
                {  
                    $transaction = new c_banco();
                    $transaction->inicioTransacao($transaction->id_connection);  
                    $this->setMetaId($this->getId());
                    $identificador = $this->incluir_meta_mensal_vendedor($transaction->id_connection);
                    $transaction->commit($transaction->id_connection);
                    $this->buscar_meta_mensal();
                    $this->desenharCadastroMetaMensal();
                }
                break;
            case 'alterarVendedor':
                {  
                    $this->buscar_meta_mensal_vendedor();
                    $this->desenharCadastroMetaMensalVendedor();
                }
                break;
            case 'alteraVendedor':
                $this->alterar_meta_mensal_vendedor();
                $this->setId($this->getMetaId());
                $this->buscar_meta_mensal();               
                $this->desenharCadastroMetaMensal();
                break;
            case 'excluiVendedor':
                {   
                    $id = $this->getMetaId();
                    $this->excluir_meta_mensal_vendedor();
                    $this->setId($id);
                    $this->buscar_meta_mensal();               
                    $this->desenharCadastroMetaMensal();
                }
                break;
            default:
                {
                    $this->mostrarMetaMensal('');
                }
        }
    }

    function desenharCadastroMetaMensalVendedor($mensagem = NULL, $tipoMsg = null) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('metaid',  $this->getMetaId());
        if ($this->m_submenu == 'cadastrarVendedor'){
            $this->setMeta(0);
        }
        $this->smarty->assign('meta',  $this->getMeta('F'));

        // COMBOBOX VENDEDOR
        $vendedor = $this->getVendedor();
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO WHERE TIPO = 'V'";
        $this->comboSql($sql, $vendedor, $vendedor, $vendedor_ids, $vendedor_names);
        $this->smarty->assign('vendedor_id', $vendedor);
        $this->smarty->assign('vendedor_ids',   $vendedor_ids);
        $this->smarty->assign('vendedor_names', $vendedor_names);

        $this->smarty->assign('vendedor', $this->getVendedor());
        
        $this->smarty->display('meta_mensal_vendedor_cadastro.tpl');
    }


    function desenharCadastroMetaMensal($mensagem = NULL, $tipoMsg = null) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('totaldiames',  $this->getTotalDiaMes());
        $this->smarty->assign('metamargem',   $this->getMetaMargem('F'));
        $this->smarty->assign('meta',   $this->getMeta('F'));
        
        $ano = $this->getAno();
        if($ano == "") 
            $this->smarty->assign('ano',date("Y"));
        else 
            $this->smarty->assign('ano', $this->getAno());

        //combobox Mes 
        $mes_ids[0] = '';
        $mes_names[0] = '';
        $mes_ids[1] = 1;
        $mes_names[1] = 'Janeiro';
        $mes_ids[2] = 2;
        $mes_names[2] = 'Fevereiro';
        $mes_ids[3] = 3;
        $mes_names[3] = 'Março';
        $mes_ids[4] = 4;
        $mes_names[4] = 'Abril';
        $mes_ids[5] = 5;
        $mes_names[5] = 'Maio';
        $mes_ids[6] = 6;
        $mes_names[6] = 'Junho';
        $mes_ids[7] = 7;
        $mes_names[7] = 'Julho';
        $mes_ids[8] = 8;
        $mes_names[8] = 'Agosto';
        $mes_ids[9] = 9;
        $mes_names[9] = 'Setembro';
        $mes_ids[10] = 10;
        $mes_names[10] = 'Outubro';
        $mes_ids[11] = 11;
        $mes_names[11] = 'Novembro';
        $mes_ids[12] = 12;
        $mes_names[12] = 'Dezembro'; 
        
        $this->smarty->assign('mes_ids', $mes_ids);
        $this->smarty->assign('mes_names', $mes_names);
        
        $mes = $this->getMes();
        
        if($mes == "") 
          $mes = date("m");
        $this->smarty->assign('mes_id', $mes);

        //CENTRO CUSTO ############# 
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo order by centrocusto";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $centroCusto_ids[0] = '';
        $centroCusto_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $centroCusto_ids[$i + 1] = $result[$i]['ID'];
            $centroCusto_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);  
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto()); 
        
        $metas = $this->buscar_meta_vendedores();
        $this->smarty->assign('metas', $metas); 
        
        $this->smarty->display('meta_mensal_cadastro.tpl');
    }


    function mostrarMetaMensal($mensagem) {

        $meta = $this->select_meta_mensal_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('meta', $meta);

        $this->smarty->display('meta_mensal_mostra.tpl');
    }

    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i] = $result[$i]['ID'];
            $names[$i] = $result[$i]['DESCRICAO'];
        }
        
        $param = explode(",", $par);
        $i=0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }    
    }

//fim mostraAtividade
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$meta_mensal = new p_meta_mensal();

$meta_mensal->controle();
?>
