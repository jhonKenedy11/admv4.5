<?php

if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/util/c_cond_pgto.php");

//Class p_cond_pgto
Class p_cond_pgto extends c_cond_pgto {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {
        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmSession = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/util";
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
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        $this->setFormaPgto(isset($parmPost['formaPgto']) ? $parmPost['formaPgto'] : '');
        $this->setNumParcelas(isset($parmPost['numparcelas']) ? $parmPost['numparcelas'] : '');
        $this->setSituacaoLcto(isset($parmPost['situacaoLcto']) ? $parmPost['situacaoLcto'] : '');

    }

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('FATCONDPGTO', 'I')) {
                    $this->desenhaCadastroCondPgto();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('FATCONDPGTO', 'A')) {
                    $this->buscar_cond_pgto();
                    $this->desenhaCadastroCondPgto();
                }
                break;
            case 'inclui':
                if ($this->existeCondpgto()) {
                    $this->m_submenu = "cadastrar";
                    $this->desenhaCadastroCondPgto("Já existe condição de pagamento cadastrada, por favor altere a condição de pagamento", "alerta");
                } else {
                    $this->mostrarCondPgto($this->incluirCondpgto());
                }
                break;
            case 'altera':
                $this->alterarCondpgto();
                $this->mostrarCondpgto('Registro salvo.');
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('FATCONDPGTO', 'E')) {
                    $this->excluirCondpgto();
                    $this->mostrarCondpgto('Registro excluido.');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('FATCONDPGTO', 'C')) {
                    $this->mostrarCondpgto('');
                
                }
        }
    }

// fim controle

    /**
     * <b> Desenha cadastro Atividade. </b>
     * @param String $mensagem mensagem que ira apresentar na tela
     * @param String $tipoMsg tipo da mensagem sucesso/alerta
     */
    function desenhaCadastroCondPgto($mensagem = NULL, $tipoMsg = null) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        if ($this->getId() == '') {
            $sql = "SELECT Coalesce(MAX(ID),0) AS ID FROM FAT_COND_PGTO";
            
            $banco = new c_banco();
            $banco->exec_sql($sql, $conn);
            $banco->close_connection();

            $num = $banco->resultado;
            $id = intval($num[0]['ID']);
            $this->smarty->assign('id', $id + 1);
        
        } else {
            $this->smarty->assign('id', $this->getId());
        }

        // situacao lancamento
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $situacaoLanc_ids[$i] = $result[$i]['ID'];
                $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
        $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
        $this->smarty->assign('situacaoLanc_id', $this->getSituacaoLcto());

        // forma pagamento
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='FormaPagamento')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $formaPagamento_ids[$i] = $result[$i]['ID'];
            $formaPagamento_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('formaPagamento_ids', $formaPagamento_ids);
        $this->smarty->assign('formaPagamento_names', $formaPagamento_names);
        $this->smarty->assign('formaPagamento_id', $this->getFormaPgto());

        $this->smarty->assign('descricao',  "'" . $this->getDescricao().  "'");
        $this->smarty->assign('formapgto',  "'" . $this->getFormapgto().  "'");
        $this->smarty->assign('numparcelas',  "'" . $this->getNumparcelas().  "'");

        $this->smarty->display('cond_pgto_cadastro.tpl');

    }

//fim desenhaCadastroAtividade

    /**
     * <b> Listagem das Atividade. </b>
     * @param String $mensagem Mensagem que ira mostrar na tela
     */
    function mostrarCondPgto($mensagem) {

        $cond = $this->select_cond_pgto_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('cond', $cond);


        $this->smarty->display('cond_pgto_mostra.tpl');
    }

//fim mostraAtividade
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$cond_pgto = new p_cond_pgto();

$cond_pgto->controle();
?>
