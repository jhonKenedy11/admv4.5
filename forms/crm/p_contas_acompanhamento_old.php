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

Class p_acompanhamento extends c_contas_acompanhamento {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_opcao = NULL;
    public $m_nome = NULL;
    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu, $letra, $opcao) {


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
        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_opcao = $opcao;
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Contas - Acompanhamento");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
        $this->smarty->assign('disableSort', "[ 6 ]"); 
        $this->smarty->assign('numLine', "25"); 
        
        // include do javascript
        // include ADMjs . "/crm/s_contas_acompanhamento.js";
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
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    $this->setDataContato(date("d-m-Y H:i"));
                    $this->setVendedorAcomp($this->m_userid);
                    $this->desenhaCadastroAcompanhamento('');
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    $this->buscaCadastroAcompanhamento();
                    $this->desenhaCadastroAcompanhamento('');
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    $this->alteraPessoaAcomp();
                    $this->mostraAcompanhamento('Registro salvo.');
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    $this->incluiPessoaAcomp();
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
                        $this->mostraAcompanhamento('Registro salvo.');}
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
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
                if ($this->verificaDireitoUsuario('FinCliente', 'C')) {
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

        $this->smarty->assign('id', $this->getId());
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

        //#################### COMBO VENDEDOR ####################
        $consulta = new c_banco();
        $sql = "select usuario as id, nomereduzido as descricao from amb_usuario  where (situacao='A') order by nomereduzido";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $vendedorAcomp_ids[$i] = $result[$i]['ID'];
            $vendedorAcomp_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('vendedorAcomp_ids', $vendedorAcomp_ids);
        $this->smarty->assign('vendedorAcomp_names', $vendedorAcomp_names);
        $this->smarty->assign('vendedorAcomp_id', $this->getVendedorAcomp());

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


        // ***** VENDEDOR
        $consulta = new c_banco();
        $sql = "select usuario as id, nomereduzido as descricao from amb_usuario where (situacao = 'A') and (tipo<>'O') order by nomereduzido";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $vendedor_ids[0] = 0;
        $vendedor_names[0] = 'Todos.';
        for ($i = 0; $i < count($result); $i++) {
            $vendedor_ids[$i + 1] = $result[$i]['ID'];
            $vendedor_names[$i + 1] = $result[$i]['DESCRICAO'];
        }//FOR
        $this->smarty->assign('vendedor_ids', $vendedor_ids);
        $this->smarty->assign('vendedor_names', $vendedor_names);
        $this->smarty->assign('vendedor_id', $this->m_par[2]);


        // FIM FILIAL ****

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('TotalLanc', count($lanc));

        $this->smarty->display('contas_acompanhamento_mostra.tpl');
    }

//fim mostrakardexs
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$acompanhamento = new p_acompanhamento($_POST['submenu'], $_POST['letra'], $_POST['opcao']);
//echo 'submenu:'.$_POST['submenu'].'|letra:'. $_POST['letra'].'|opcao:'.$_POST['opcao'];


if (isset($_POST['pessoaNome'])) {   $acompanhamento->m_nome = $_POST['pessoaNome'];} else {  $acompanhamento->m_nome='';};
if (isset($_POST['id'])) {   $acompanhamento->setId($_POST['id']);} else {    $acompanhamento->setId('');};
if (isset($_POST['pessoa'])) {   $acompanhamento->setPessoa($_POST['pessoa']);} else {$acompanhamento->setPessoa('');};
if (isset($_POST['dataContato'])) {    $acompanhamento->setDataContato($_POST['dataContato']);} else {    $acompanhamento->setDataContato('');};
if (isset($_POST['acao'])) {    $acompanhamento->setAcao($_POST['acao']);} else {    $acompanhamento->setAcao('');};
if (isset($_POST['vendedorAcomp'])) {    $acompanhamento->setVendedorAcomp($_POST['vendedorAcomp']);} else {    $acompanhamento->setVendedorAcomp('');};
if (isset($_POST['proximoContato'])) {    $acompanhamento->setProximoContato($_POST['proximoContato']);} else {    $acompanhamento->setProximoContato('');};
if (isset($_POST['resultContato'])) {    $acompanhamento->setResultContato($_POST['resultContato']);} else {    $acompanhamento->setResultContato('');};
if (isset($_POST['veiculo'])) {    $acompanhamento->setVeiculo($_POST['veiculo']);} else {    $acompanhamento->setVeiculo('');};
if (isset($_POST['origem'])) {    $acompanhamento->setOrigem($_POST['origem']);} else {    $acompanhamento->setOrigem('');};
if (isset($_POST['destino'])) {    $acompanhamento->setDestino($_POST['destino']);} else {    $acompanhamento->setDestino('');};
if (isset($_POST['km'])) {    $acompanhamento->setKM($_POST['km']);} else {    $acompanhamento->setKM('');};

$acompanhamento->controle();
?>
