<?php

/**
 * @package   astec
 * @name      p_apontamento_os_mobile
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      30/04/2025
 */

if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/cat/c_atendimento.php");
require_once($dir . "/../../class/cat/c_apontamento_os_mobile.php");


//Class
class p_apontamento_os_mobile extends c_apontamento_os_mobile
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
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));

        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->dataIni = (isset($parmGet['dataIni']) ? $parmGet['dataIni'] : (isset($parmPost['dataIni']) ? $parmPost['dataIni'] : ''));
        $this->dataFim = (isset($parmGet['dataFim']) ? $parmGet['dataFim'] : (isset($parmPost['dataFim']) ? $parmPost['dataFim'] : ''));
        $this->numAtendimento = (isset($parmGet['numAtendimento']) ? $parmGet['numAtendimento'] : (isset($parmPost['numAtendimento']) ? $parmPost['numAtendimento'] : ''));
        $this->qtd_executada = (isset($parmGet['qtd_executada']) ? $parmGet['qtd_executada'] : (isset($parmPost['qtd_executada']) ? $parmPost['qtd_executada'] : ''));
        $this->nome = (isset($parmGet['nome']) ? $parmGet['nome'] : (isset($parmPost['nome']) ? $parmPost['nome'] : ''));
        $this->data_finalizacao = (isset($parmGet['data_finalizacao']) ? $parmGet['data_finalizacao'] : (isset($parmPost['data_finalizacao']) ? $parmPost['data_finalizacao'] : ''));
        $this->situacaoSelecionada = (isset($parmGet['situacaoSelecionada']) ? $parmGet['situacaoSelecionada'] : (isset($parmPost['situacaoSelecionada']) ? $parmPost['situacaoSelecionada'] : ''));
        $this->situacao = (isset($parmGet['situacao']) ? $parmGet['situacao'] : (isset($parmPost['situacao']) ? $parmPost['situacao'] : ''));
        $this->numero_os = (isset($parmGet['numero_os']) ? $parmGet['numero_os'] : (isset($parmPost['numero_os']) ? $parmPost['numero_os'] : ''));
        $this->json_data = (isset($parmGet['json_data']) ? $parmGet['json_data'] : (isset($parmPost['json_data']) ? $parmPost['json_data'] : ''));

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('raizFonte', ADMraizFonte);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('httpCliente', ADMhttpCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');


        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "OS");
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
                    $this->mostraApontamentoOS('');
                }
                break;
            case 'cadastro':
                if ($this->verificaDireitoUsuario('PedGerente', 'I')) {
                    if ($this->numAtendimento !== '') {
                        $cadastroApontamento = $this->cadastroApontamento();
                    }
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('PedGerente', 'I')) {

                    $id_os = $this->incluiApontamento();
                   
                    if (is_int($id_os)) {
                        $msgRetorno = 'Apontamento alterado!';
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                        icon: 'success',
                        title: 'Sucesso',
                        width: 320,
                        text: '" . $msgRetorno . ".',
                        confirmButtonText: 'OK'
                        });
                        </script>";
                    } else {
                        $msgRetorno = 'Não foi possível alterar o apontamento, contate o suporte!';
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            width: 320,
                            text: '" . $msgRetorno . ".',
                            confirmButtonText: 'OK'
                        });
                        </script>";
                    }
                    $this->mostraApontamentoOS('');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('PedGerente', 'C')) {
                    $this->mostraApontamentoOS('');
                }
        }
    }



    function mostraApontamentoOS($mensagem, $tipoMsg = NULL)
    {
        $objAtendimento = new c_atendimento();
        if ($this->m_letra != '') {
            $lanc = $objAtendimento->select_atendimento_letra($this->m_letra,  $this->situacaoSelecionada);
        } else if ($this->m_letra == '') {
            $this->dataIni = date("d/m/Y");
            $this->dataFim = date("d/m/Y");
            $letra = $this->dataIni . "|" . $this->dataFim;
            $lanc = $objAtendimento->select_atendimento_letra($letra, $this->situacaoSelecionada) ?? [];
        }



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


        $consulta = new c_banco();
        $sql = "SELECT ID , DESCRICAO FROM CAT_SITUACAO ";
        $sql .= "WHERE ATIVO = '1'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $this->getStatus());
        if ($this->situacaoSelecionada == '') {
            $this->smarty->assign('situacao_id', 2);
        } else {
            $parSit = explode("|", $this->situacaoSelecionada);
            $this->smarty->assign('situacao_id', $parSit);
        }

        $this->smarty->display('apontamento_os_mobile_mostra.tpl');
    }
}
//	END OF THE CLASS

$pedido = new p_apontamento_os_mobile();

$pedido->controle();
