<?php

/**
 * @package   astec
 * @name      c_contrato
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      17/04/2025
 */

if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_contrato.php");
require_once($dir . "/../../class/ped/c_pedido_venda_gerente_tools.php");
require_once($dir . "/../../forms/ped/p_pedido_venda_nf_pecas_novo.php");
require_once($dir . "/../../forms/ped/p_pedido_venda_nf_ps.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/ped/c_pedido_ps_tools.php");
require_once($dir . "/../../class/ped/c_pedido_ps.php");
require_once($dir . "/../../class/cat/c_atendimento.php");

//Class P_situacao
class p_contrato extends c_contrato
{

    private $m_submenu          = NULL;
    private $m_letra            = NULL;
    private $m_opcao           = NULL;
    public $smarty              = NULL;


    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct()
    {
        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_situacoesAtendimento = (isset($parmGet['situacoesAtendimento']) ? $parmGet['situacoesAtendimento'] : (isset($parmPost['situacoesAtendimento']) ? $parmPost['situacoesAtendimento'] : ''));
        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->dataIni = $parmPost['dataIni'];
        $this->dataFim = $parmPost['dataFim'];
        $this->numAtendimento = $parmPost['numAtendimento'];
        $this->nome = $parmPost['nome'];
        $this->id_pedido = (isset($parmGet['id_pedido']) ? $parmGet['id_pedido'] : (isset($parmPost['id_pedido']) ? $parmPost['id_pedido'] : ''));
        $this->gerencia_ordem_servico = (isset($parmGet['gerencia_ordem_servico']) ? $parmGet['gerencia_ordem_servico'] : (isset($parmPost['gerencia_ordem_servico']) ? $parmPost['gerencia_ordem_servico'] : ''));

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('raizFonte', ADMraizFonte);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('httpCliente', ADMhttpCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');


        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Contratos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7 ]");
        $this->smarty->assign('disableSort', "[ 0, 6, 7 ]");
        $this->smarty->assign('numLine', "25");
    }

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle()
    {
        switch ($this->m_submenu) {
            case 'pesquisa':

                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {

                    $this->mostraAcompanhamento('');
                }
                break;
            case 'btnAtalho':

                if ($this->verificaDireitoUsuario('PedGerente', 'S')) {

                    $this->mostraAcompanhamento($this->m_opcao);
                }
                break;
            case 'mostraOsAjax':

                $this->select_servicos_atendimento_ajax($this->id_pedido);

                break;
            case 'OrdemServicosCadastradas':

                $this->select_servicos_pedido($this->id_pedido);

                break;
            case 'gerarOs':

                if ($this->verificaDireitoUsuario('PedGerente', 'E')) {

                    $this->cadastraOrdemServico($this->gerencia_ordem_servico);
                    $this->mostraAcompanhamento('');
                }
                break;
            default:

                if ($this->verificaDireitoUsuario('PedGerente', 'C')) {
                    $this->mostraAcompanhamento('');
                }
                break;
        }
    }



    function mostraAcompanhamento($mensagem, $tipoMsg = NULL)
    {
        $this->dataIni = date("01/m/Y");
        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");
        $this->dataFim = date("d/m/Y", mktime(0, 0, 0, $mes + 1, 0, $ano));
        $letra = '';

        switch ($mensagem) {
            case 'dia':
                $this->dataIni = date("d/m/Y");
                $this->dataFim = date("d/m/Y");
                $letra = $this->dataIni . "|" . $this->dataFim . '|||3,6|';
                $this->numAtendimento = '';
                $this->nome = '';
                break;
            case 'mes':
                $letra = $this->dataIni . "|" . $this->dataFim . '|||3,6|';
                $this->numAtendimento = '';
                $this->nome = '';
                break;
            case 'todos':
                $letra = '||||3,6|';
                $this->numAtendimento = '';
                $this->nome = '';
                break;
            default:
                if ($this->m_letra == '') {
                    $this->dataIni = date("d/m/Y");
                    $this->dataFim = date("d/m/Y");
                    $letra = $this->dataIni . "|" . $this->dataFim;
                } else {
                    $letra = $this->m_letra;
                    $this->nome = "";
                }
                break;
        }


        $objPedidoVenda = new c_pedidoVenda();
        $lanc = $objPedidoVenda->select_pedidoVenda_letra($letra, '');

        if (!empty($lanc)) {
            foreach ($lanc as &$pedido) {
                $pedido['SERVICOS'] = $this->buscarServicosDoPedido($pedido['ID']);
            }
        }

        // COMBOBOX USUÁRIO EQUIPE
        $consulta = new c_banco();
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO WHERE SITUACAO = 'A' ORDER BY NOME";
        $consulta->exec_sql($sql);
        $result = $consulta->resultado ?? [];

        $usuario_equipe_ids = [];
        $usuario_equipe_names = [];

        if (!empty($result)) {
            foreach ($result as $row) {
                $usuario_equipe_ids[] = $row['ID'];
                $usuario_equipe_names[] = $row['DESCRICAO'];
            }
        }

        $this->smarty->assign('usuario_equipe_ids', $usuario_equipe_ids);
        $this->smarty->assign('usuario_equipe_names', $usuario_equipe_names);

        // COMBOBOX EQUIPE
        $sql = "SELECT ID, DESCRICAO FROM AMB_EQUIPE ORDER BY DESCRICAO";
        $consulta->exec_sql($sql);
        $result = $consulta->resultado ?? [];

        // Adiciona os valores iniciais para a opção vazia
        $equipe_ids = [''];
        $equipe_names = ['Selecione uma equipe.'];

        if (!empty($result)) {
            foreach ($result as $row) {
                $equipe_ids[] = $row['ID'];
                $equipe_names[] = $row['DESCRICAO'];
            }
        }

        $this->smarty->assign('equipe_ids', $equipe_ids);
        $this->smarty->assign('equipe_names', $equipe_names);

        $this->smarty->assign('numAtendimento', $this->numAtendimento);
        $this->smarty->assign('dataIni', $this->dataIni);
        $this->smarty->assign('dataFim', $this->dataFim);
        $this->smarty->assign('nome', $this->nome);

        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->assign('pedido', $pedido);
        $this->smarty->display('contrato.tpl');
    }
}

//	END OF THE CLASS
// Rotina principal - cria classe
$contrato = new p_contrato();
$contrato->controle();
