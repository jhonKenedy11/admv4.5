<?php
/**
 * @package   astec
 * @name      p_acompanhamento
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      03/02/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')):
    exit;
endif;
    

$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/crm/c_contas_acompanhamento.php");
include_once($dir . "/../../class/crm/c_conta.php");

Class p_acompanhamento extends c_contas_acompanhamento {

    private $m_dashboard_origem = NULL;
    private $m_data_previous    = NULL;
    private $m_submenu          = NULL;
    private $m_letra            = NULL;
    private $m_opcao            = NULL;
    public $m_nome              = NULL;
    public $smarty              = NULL;
    public $m_par               = NULL;
    public $m_id                = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/crm";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['op   cao'] : ''));
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';
        $this->m_par = explode("|", $this->m_letra);
        //var dashboard
        $this->m_dashboard_origem = (isset($parmPost['dashboard_origem']) ? $parmPost['dashboard_origem'] : (isset($parmGet['dashboard_origem']) ? $parmGet['dashboard_origem'] : ''));
        $this->m_data_previous = (isset($parmPost['data_previous']) ? $parmPost['data_previous'] : (isset($parmGet['data_previous']) ? $parmGet['data_previous'] : ''));

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        // build calendar
        $this->smarty->assign('pathBuild',  ADMhttpBib);

          // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Bancos");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->m_nome = (isset($parmGet['pessoaNome']) ? $parmGet['pessoaNome'] : (isset($parmPost['pessoaNome']) ? $parmPost['pessoaNome'] : ''));
        $this->m_id = (isset($parmPost['id']) ? $parmPost['id'] : (isset($parmGet['id']) ? $parmGet['id'] : ''));         $this->setId(isset($parmPost['id']) ? $parmPost['id'] : (isset($parmGet['id']) ? $parmGet['id'] : ''));
        //if(isset($parmGet['id']) == ''){
        //    $this->setId((isset($parmGet['idPedido']) ? $parmGet['idPedido'] : isset($parmPost['idPedido']) ? $parmPost['idPedido'] : ''));
        //}
        $this->setIdPedido(isset($parmGet['idPedido']) ? $parmGet['idPedido'] : (isset($parmPost['idPedido']) ? $parmPost['idPedido'] : ''));
        $this->setPessoa(isset($parmGet['pessoa']) ? $parmGet['pessoa'] : (isset($parmPost['pessoa']) ? $parmPost['pessoa'] : ''));
        if (isset($parmPost['dataContato'])) {    $this->setDataContato($parmPost['dataContato']);} else {    $this->setDataContato(null);};
        if (isset($parmPost['acao'])) {    $this->setAcao($parmPost['acao']);} else {    $this->setAcao('');};
        if (isset($parmPost['vendedorAcomp'])) {    $this->setVendedorAcomp($parmPost['vendedorAcomp']);} else {    $this->setVendedorAcomp('');};
        if (isset($parmPost['proximoContato'])) {    $this->setProximoContato($parmPost['proximoContato']);} else {    $this->setProximoContato('');};
        if (isset($parmPost['resultContato'])) {    $this->setResultContato($parmPost['resultContato']);} else {    $this->setResultContato('');};
        if (isset($parmPost['veiculo'])) {    $this->setVeiculo($parmPost['veiculo']);} else {    $this->setVeiculo('');};
        if (isset($parmPost['origem'])) {    $this->setOrigem($parmPost['origem']);} else {    $this->setOrigem('');};
        if (isset($parmPost['destino'])) {    $this->setDestino($parmPost['destino']);} else {    $this->setDestino('');};
        if (isset($parmPost['km'])) {    $this->setKM($parmPost['km']);} else {    $this->setKM('');};
        $this->setUserId(isset($this->m_userid) ? $this->m_userid : '');        
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
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $this->setDataContato('');
                    $this->setVendedorAcomp($this->m_userid);
                    $this->desenhaCadastroAcompanhamento('');
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $this->buscaCadastroAcompanhamento();
                    $this->desenhaCadastroAcompanhamento('');
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $this->alteraPessoaAcomp();
                    if ($this->m_dashboard_origem == 'dashboard_crm') { // jhon
                        $returnAjax = 'Registro salvo!';
                        echo $returnAjax;
                    } else {
                         $this->mostraAcompanhamento('Registro salvo.');
                    }
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $resultInsert =  $this->incluiPessoaAcomp();
                    if ($this->m_opcao=='pessoa'){
                        ?>
                            <form NAME="lancamento" METHOD="post">
                                <input name=mod                 type=hidden value="crm">
                                <input name=form                type=hidden value="contas">
                                <input name=acao                type=hidden value="">
                                <input name=submenu             type=hidden value="">
                                <input name=opcao               type=hidden value="">
                                <input name=letra               type=hidden value="<?php echo $this->m_nome;?>">
                            <script>    
                                f = document.lancamento;
                                f.submit();
                          </script>
                        <?php  
                        //echo "<script>submitVoltar();</script>";
                        
                    }
                    else{
                        if ($this->m_dashboard_origem == 'dashboard_crm') { // jhon
                            $returnAjax = 'Registro salvo!';
                            echo $returnAjax;
                        }else{
                            if($resultInsert == ''){
                                $msgPedido = "Registro inserido com sucesso!";
                                echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                                echo "<style>.swal-title{font-size: 21px;}</style> ";
                                echo "<script>swal({text: `$msgPedido`, title: 'Sucesso!', icon: 'success',button: 'Ok'});</script>";
                                echo "<script>function closePag(){ setTimeout(function () {window.close();}, 2000) } closePag();</script>";
                                $this->desenhaCadastroAcompanhamento('');
                            }else{
                                $msgPedido = "Erro ao inserir o registro, verifique os dados ou contate o suporte!";
                                echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                                echo "<style>.swal-title{font-size: 21px;}</style> ";
                                echo "<script>swal({text: `$msgPedido`, title: 'Atenção!', icon: 'warning',button: 'Ok', dangerMode: true});</script>";
                                $this->desenhaCadastroAcompanhamento('');
                            }
                        }
                    }
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $this->excluiPessoaAcomp();
                    $this->mostraAcompanhamento('Registro Excluido.');
                }
                break;
            case 'pesquisaClienteAjax':
                $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $termAjax = (isset($parmPost['term']) ? $parmPost['term'] : '');

                $objConta = new c_conta();
                $resultPesq = $objConta->select_pessoa_letra($termAjax);
                for ($i = 0; $i < count($resultPesq); $i++) {
                    $clienteResult[$i]['id'] = trim($resultPesq[$i]['CLIENTE']);
                    $clienteResult[$i]['text'] = trim($resultPesq[$i]['NOME']);
                }
            
                echo json_encode($clienteResult);

                break;
            default:
                if ($this->verificaDireitoUsuario('FinPessoa', 'C')) {
                    $this->mostraAcompanhamento('');
                }
        }
    }

    /**
     * <b> Desenha cadastro Acompanhamento. </b>
     * @param String $mensagem mensagem que ira apresentar na tela
     * @param String $tipoMsg tipo da mensagem sucesso/alerta
     */
    function desenhaCadastroAcompanhamento($mensagem = NULL, $tipoMsg = null) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        //dashboard
        $this->smarty->assign('dashboard_origem', $this->m_dashboard_origem);
        $this->smarty->assign('data_previous', $this->m_data_previous);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idPedido', $this->getIdPedido());
        $this->smarty->assign('pessoa', $this->getPessoa());
        if ($this->getPessoa() != '') { // VERIFICAR se existe codigo do cliente para setar no nome
            $arrPessoa = $this->select_pessoa();
            $this->smarty->assign('pessoaNome', "'" . $arrPessoa[0]['NOME'] . "'");
        }
        $this->smarty->assign('dataContato', "'".$this->getDataContato('F')."'");

        //#################### COMBO ACAO ####################
        $consulta = new c_banco();
        $sql = "select atividade as id, descricao from fat_atividade_acomp";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $acao_ids[$i] = $result[$i]['ID'];
            $acao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('acao_ids', $acao_ids);
        $this->smarty->assign('acao_names', $acao_names);
        $this->smarty->assign('acao_id', $this->getAcao());

        // COMBOBOX VENDEDOR
        // valida direito de visualizar pedidos de outros vendedores
        $verTodosVend = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');
        $this->smarty->assign('verTodosVend',$verTodosVend); 
        if($verTodosVend == false){
            $vendedor = $this->verifica_vendedor();            
            $this->smarty->assign('vendedorAcomp_ids',   $vendedor[0]['USUARIO']);
            $this->smarty->assign('vendedorAcomp_names', $vendedor[0]['NOME']);
            $this->smarty->assign('vendedorAcomp_id', $vendedor[0]['USUARIO']);
        }else{
            $sql = "select usuario as id, nomereduzido as descricao from amb_usuario  where (situacao='A') and (TIPO in ('V', 'G')) order by nomereduzido";
            $this->comboSql($sql, $this->m_par[2], $vendedor_id, $vendedor_ids, $vendedor_names);
            $this->smarty->assign('vendedorAcomp_ids', $vendedor_ids);
            $this->smarty->assign('vendedorAcomp_names', $vendedor_names);
            $vendedor = $this->verifica_vendedor();
            //Verifica se acomp possui vendedor
            if($this->getVendedorAcomp() !== null or $this->getVendedorAcomp() !== ''){
                $vendedor_id = $this->getVendedorAcomp();
            }else{
                $vendedor_id = $this->m_userid;
            }
            $this->smarty->assign('vendedorAcomp_id', $vendedor_id);
        }

        ////#################### COMBO VENDEDOR ####################
        //$consulta = new c_banco();
        //$sql = "select usuario as id, nomereduzido as descricao from amb_usuario  where (situacao='A') and (TIPO in ('V', 'G')) order by nomereduzido";
        //$consulta->exec_sql($sql);
        //$consulta->close_connection();
        //$result = $consulta->resultado;
        //for ($i = 0; $i < count($result); $i++) {
        //    $vendedorAcomp_ids[$i] = $result[$i]['ID'];
        //    $vendedorAcomp_names[$i] = $result[$i]['DESCRICAO'];
        //}
        //$this->smarty->assign('vendedorAcomp_ids', $vendedorAcomp_ids);
        //$this->smarty->assign('vendedorAcomp_names', $vendedorAcomp_names);
        //$this->smarty->assign('vendedorAcomp_id', $this->getVendedorAcomp());

        //#################### COMBO VEICULO ####################
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='CAT_MENU') and (campo='Veiculo')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $veiculo_ids[$i] = $result[$i]['ID'];
            $veiculo_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('veiculo_ids', $veiculo_ids);
        $this->smarty->assign('veiculo_names', $veiculo_names);
        $this->smarty->assign('veiculo_id', $this->getVeiculo());

        $this->smarty->assign('proximoContato', "'".$this->getProximoContato('F')."'");
        $this->smarty->assign('resultContato', $this->getResultContato());
        $this->smarty->assign('origem', $this->getOrigem());
        $this->smarty->assign('destino', $this->getDestino());
        $this->smarty->assign('km', $this->getKM());

        $this->smarty->display('contas_acompanhamento_cadastro.tpl');
    }

    /**
     * <b> Listagem das Acompanhamento. </b>
     * @param String $mensagem Mensagem que ira mostrar na tela
     */
    function mostraAcompanhamento($mensagem = NULL) {

     
        
        if ($this->m_letra != "") {
            $lanc = $this->select_pessoaConsultaAcompanhamento($this->m_letra);
        }
        //########### FILTROS DE PESQUISA ###########
        $this->smarty->assign('nome', "'" . $this->m_par[3] . "'");
        $this->smarty->assign('pesPedido', "'" . $this->m_par[4] . "'");

        if ($this->m_par[0] == "")
            $this->smarty->assign('dataIni', date("01/m/Y"));
        else
            $this->smarty->assign('dataIni', $this->m_par[0]);
        if ($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = mktime(0, 0, 0, $mes + 1, 0, $ano);
            $this->smarty->assign('dataFim', date("d/m/Y", $data));
            //	$data = mktime(0, 0, 0, $mes, 1, $ano);
            //	$this->smarty->assign('dataFim', date("d",$data-1).date("/m/Y"));
        } else {
            $this->smarty->assign('dataFim', $this->m_par[1]);
        }

        // COMBOBOX VENDEDOR
        // valida direito de visualizar pedidos de outros vendedores
        $verTodosVend = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');
        $this->smarty->assign('verTodosVend',$verTodosVend); 
        if($verTodosVend == false){
            $vendedor = $this->verifica_vendedor();            
            $this->smarty->assign('vendedor_ids',   $vendedor[0]['USUARIO']);
            $this->smarty->assign('vendedor_names', $vendedor[0]['NOME']);
            $this->smarty->assign('vendedor_id', $vendedor[0]['USUARIO']);
        }else{
            $vendedor_ids[0] = 0;
            $result = $result ?? [];
            for ($i = 0; $i < count($result); $i++) {
                $vendedor_ids[$i + 0] = $result[$i]['ID'];
                $vendedor_names[$i + 0] = $result[$i]['DESCRICAO'];
            }//FOR
            $sql = "select usuario as id, nomereduzido as descricao from amb_usuario  where (situacao='A') and (TIPO in ('V', 'G')) order by nomereduzido";
            $this->comboSql($sql, $this->m_par[2], $vendedor_id, $vendedor_ids, $vendedor_names);
            $this->smarty->assign('vendedor_id', $vendedor_id);
            $this->smarty->assign('vendedor_ids',   $vendedor_ids);
            $this->smarty->assign('vendedor_names',  $vendedor_names);

            $vend = $this->getVendedorAcomp();
            if ($vend !== '') {
                $this->smarty->assign('vendedorAcomp_id', $vend);
            } else {
                $this->smarty->assign('vendedorAcomp_id', $this->m_userid);
            }
        }

        // ***** VENDEDOR
        //$consulta = new c_banco();
        //$sql = "select usuario as id, nomereduzido as descricao from amb_usuario where (situacao = 'A') and (tipo<>'O') order by nomereduzido";
        //$consulta->exec_sql($sql);
        //$consulta->close_connection();
        //$result = $consulta->resultado;
        //$vendedor_ids[0] = 0;
        //for ($i = 0; $i < count($result); $i++) {
        //    $vendedor_ids[$i + 0] = $result[$i]['ID'];
        //    $vendedor_names[$i + 0] = $result[$i]['DESCRICAO'];
        //}//FOR
        //$this->smarty->assign('vendedor_ids', $vendedor_ids);
        //$this->smarty->assign('vendedor_names', $vendedor_names);
        //$this->smarty->assign('vendedor_id', $this->m_userid);


        // FIM FILIAL ****

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('lanc', $lanc);
        $lanc = is_array($lanc) ? $lanc : [];
        $this->smarty->assign('TotalLanc', count($lanc));

        $this->smarty->display('contas_acompanhamento_mostra.tpl');
    }

//fim mostrakardexs
//-------------------------------------------------------------
}

//	END OF THE CLASS

$acompanhamento = new p_acompanhamento();
// Rotina principal - cria classe
$acompanhamento->controle();

?>
