<?php


if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_tabela_preco.php");

Class p_tabela_preco extends c_tabela_preco {

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
        $this->setNome(isset($parmPost['nome']) ? $parmPost['nome'] : '');
        $this->setValidade(isset($parmPost['validade']) ? $parmPost['validade'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : '');
        $this->setPrecoBase(isset($parmPost['precoBase']) ? $parmPost['precoBase'] : '');
        $this->setMargem(isset($parmPost['margem']) ? $parmPost['margem'] : '');
    }

    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                //if ($this->verificaDireitoUsuario('TABELAPRECO', 'I')) {
                {
                    $this->desenharCadastroTabelaPreco();
                }
                break;
            case 'alterar':
                //if ($this->verificaDireitoUsuario('TABELAPRECO', 'A')) {
                {
                    $this->buscar_tabela_preco();
                    $this->desenharCadastroTabelaPreco();
                }
                break;
            case 'inclui':
                if ($this->existe_tabela_preco()) {
                    $this->m_submenu = "cadastrar";
                    $this->desenharCadastroTabelaPreco("Já existe tabela com este código, por favor altere a tabela", "alerta");
                } else {
                    
                    $transaction = new c_banco();
                    $transaction->inicioTransacao($transaction->id_connection);
                    $result = true;
                    $identificador = $this->incluir_tabela_preco($transaction->id_connection);
                    $transaction->commit($transaction->id_connection);
                    
                    $itens = $this->select_itens();
                        
                    $id = $identificador;
                    $margem =$this->getMargem('B');
                        
                    for ($i = 0; $i < count($itens); $i++) {
                        $this->insere_item_tabela_preco(
                        $id,
                        $itens[$i]['GRUPO'],                            
                        $itens[$i]['CODIGO'],
                        $itens[$i]['VENDA'],
                        $margem,
                        $itens[$i]['VENDA'] * (1 + ($margem / 100)));
                    } 
                    $this->mostrarTabelaPreco('');
                }
                break;
            case 'altera':
                $this->alterar_tabela_preco();
                $this->excluir_tabela_preco('_ITEM');

                $id = $this->getID();
                $margem =$this->getMargem();
                
                $itens = $this->select_itens();

                for ($i = 0; $i < count($itens); $i++) {
                    $this->insere_item_tabela_preco(
                    $id,
                    $itens[$i]['GRUPO'],                            
                    $itens[$i]['CODIGO'],
                    $itens[$i]['VENDA'],
                    $margem,
                    $itens[$i]['VENDA'] * (1 + ($margem / 100)));
                }
                $this->mostrarTabelaPreco('Registro salvo.');
                break;
            case 'exclui':
                //if ($this->verificaDireitoUsuario('TABELAPRECO', 'E')) {
                {
                    $this->excluir_tabela_preco('_ITEM');
                    $this->excluir_tabela_preco();
                    $this->mostrarTabelaPreco('Registro excluido.');
                }
                break;
            default:
                //if ($this->verificaDireitoUsuario('TABELAPRECO', 'C')) {
                {
                    $this->mostrarTabelaPreco('');
                }
        }
    }


    function desenharCadastroTabelaPreco($mensagem = NULL, $tipoMsg = null) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('nome',  "'" . $this->getNome().  "'");
        if ($this->m_submenu == 'cadastrar') {
            $this->smarty->assign('validade',  "'" . $this->getValidade().  "'");
        } else {
            $this->smarty->assign('validade',  "'" . $this->getValidade('F').  "'");
        }
        $this->smarty->assign('centrocusto',  "'" . $this->getCentroCusto().  "'");
        $this->smarty->assign('precobase',  "'" . $this->getPrecoBase().  "'");
        $this->smarty->assign('margem',  "'" . $this->getMargem().  "'");

        //PRECO BASE #############
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='PRECOBASE')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $precoBase_ids[0] = '';
        $precoBase_names[0] = 'Selecione.';
        for ($i = 0; $i < count($result); $i++) {
            $precoBase_ids[$i+1] = $result[$i]['ID'];
            $precoBase_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('precoBase_ids', $precoBase_ids);
        $this->smarty->assign('precoBase_names', $precoBase_names);
        $this->smarty->assign('precoBase_id', $this->getPrecoBase());

        //CENTRO CUSTO ############# 
        $sql = "select centrocusto as id, descricao from fin_centro_custo ".$aliqRegEspSTMTcWhere." order by centrocusto";
        $this->comboSql($sql, $this->m_par[7] ?? $this->m_empresacentrocusto, $centroCusto_id, $centroCusto_ids, $centroCusto_names);
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);  
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto()); 

        $this->smarty->display('tabela_preco_cadastro.tpl');
    }


    function mostrarTabelaPreco($mensagem) {

        $tabelaPreco = $this->select_tabela_preco_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $tabelaPreco);

        $this->smarty->display('tabela_preco_mostra.tpl');
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
$tabela_preco = new p_tabela_preco();

$tabela_preco->controle();
?>
