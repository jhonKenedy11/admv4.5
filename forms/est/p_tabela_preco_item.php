<?php


if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_tabela_preco_item.php");

Class p_tabela_preco_item extends c_tabela_preco_item {

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
        $this->setGrupo(isset($parmPost['grupo']) ? $parmPost['grupo'] : '');
        $this->setCodigo(isset($parmPost['codigo']) ? $parmPost['codigo'] : '');
        $this->setPrecoBase(isset($parmPost['precobase']) ? $parmPost['precobase'] : '');
        $this->setMargem(isset($parmPost['margem']) ? $parmPost['margem'] : '');
        $this->setPrecoFinal(isset($parmPost['precofinal']) ? $parmPost['precofinal'] : '');
    }

    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                //if ($this->verificaDireitoUsuario('TABELAPRECO', 'I')) {
                {
                    $this->desenharCadastroTabelaPrecoItem();
                }
                break;
            case 'alterar':
                //if ($this->verificaDireitoUsuario('TABELAPRECO', 'A')) {
                {
                    $this->buscar_tabela_preco_item();
                    $this->desenharCadastroTabelaPrecoItem();
                }
                break;            
            case 'altera':
                $this->alterar_tabela_preco_item();
                $this->mostrarTabelaPrecoItem('Registro salvo.');
                break;
            case 'exclui':
                //if ($this->verificaDireitoUsuario('TABELAPRECO', 'E')) {
                {
                    $this->excluir_tabela_preco_item();
                    $this->mostrarTabelaPrecoItem('Registro excluido.');
                }
                break;
            default:
                //if ($this->verificaDireitoUsuario('TABELAPRECO', 'C')) {
                {
                    $this->mostrarTabelaPrecoItem('');
                }
        }
    }


    function desenharCadastroTabelaPrecoItem($mensagem = NULL, $tipoMsg = null) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());

        // GRUPO
        $consulta = new c_banco();
        $sql = "select grupo id, descricao from est_grupo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $grupo_ids[0] = '';
        $grupo_names[0] = 'Selecione Grupo';
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + 1] = $result[$i]['ID'];
            $grupo_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names',  $grupo_names);
        $this->smarty->assign('grupo', $this->getGrupo());

        $this->smarty->assign('codigo', $this->getCodigo());
        $this->smarty->assign('precofinal', $this->getPrecoFinal('F'));
        $this->smarty->assign('margem',  $this->getMargem('F'));
        $this->smarty->assign('precobase', $this->getPrecoBase('F'));
        
        $this->smarty->display('tabela_preco_item_cadastro.tpl');
    }


    function mostrarTabelaPrecoItem($mensagem) {

        $itens = $this->select_tabela_preco_item_geral($this->m_letra);

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $itens);

        $this->smarty->display('tabela_preco_item_mostra.tpl');
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
$tabela_preco_item = new p_tabela_preco_item();

$tabela_preco_item->controle();
?>
