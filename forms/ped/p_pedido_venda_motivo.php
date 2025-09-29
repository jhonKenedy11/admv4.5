<?php


if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/ped/c_pedido_venda_motivo.php");
//Class p_cond_pgto
Class p_pedido_venda_motivo extends c_pedido_venda_motivo {

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
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Motivo");
        $this->smarty->assign('colVis', "[ 0, 1, 2]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setMotivo(isset($parmPost['motivo']) ? $parmPost['motivo'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        
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
                if ($this->verificaDireitoUsuario('FATMOTIVO', 'I')) {
                    $this->desenhaCadastroMotivo();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('FATMOTIVO', 'A')) {
                    $this->buscar_Motivo();
                    $this->desenhaCadastroMotivo();
                }
                break;
            case 'inclui':
                if ($this->existeMotivo()) {
                    $this->m_submenu = "cadastrar";
                    $this->desenhaCadastroMotivo("Já existe condição de pagamento cadastrada, por favor altere a condição de pagamento", "alerta");
                } else {
                    $this->mostrarMotivo($this->incluirMotivo());
                }
                break;
            case 'altera':
                $this->alterarMotivo();
                $this->mostrarMotivo('Registro salvo.');
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('FATMOTIVO', 'E')) {
                    $this->excluirMotivo();
                    $this->mostrarMotivo('Registro excluido.');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('FATMOTIVO', 'C')) {
                    $this->mostrarMotivo('');
                }
        }
    }

// fim controle

    /**
     * <b> Desenha cadastro Atividade. </b>
     * @param String $mensagem mensagem que ira apresentar na tela
     * @param String $tipoMsg tipo da mensagem sucesso/alerta
     */
    function desenhaCadastroMotivo($mensagem = NULL, $tipoMsg = null) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('motivo', $this->getMotivo());
        $this->smarty->assign('descricao',  "'" . $this->getDescricao().  "'");
        
        $this->smarty->display('pedido_venda_motivo_cadastro.tpl');
    }

//fim desenhaCadastroAtividade

    /**
     * <b> Listagem das Atividade. </b>
     * @param String $mensagem Mensagem que ira mostrar na tela
     */
    function mostrarMotivo($mensagem) {

        $cond = $this->select_motivo_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('cond', $cond);


        $this->smarty->display('pedido_venda_motivo_mostra.tpl');
    }

//fim mostraAtividade
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$cond_pgto = new p_pedido_venda_motivo();

$cond_pgto->controle();
?>
