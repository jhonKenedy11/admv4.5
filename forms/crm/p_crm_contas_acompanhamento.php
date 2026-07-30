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
include_once($dir . "/../../class/crm/c_crm_contas_acompanhamento.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../bib/c_mail.php");

Class p_crm_contas_acompanhamento extends c_crm_contas_acompanhamento {

    private $m_dashboard_origem = NULL;
    private $m_dados_contato    = NULL;
    private $m_data_previous    = NULL;
    private $m_submenu          = NULL;
    private $m_letra            = NULL;
    private $m_opcao            = NULL;
    private $m_status_cli       = NULL;
    public $m_nome              = NULL;
    public $smarty              = NULL;
    public $m_par               = NULL;
    public $m_id                = NULL;
    public $m_json              = NULL;

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
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';
        $this->m_par = explode("|", $this->m_letra);
        //var dashboard
        $this->m_dashboard_origem = (isset($parmPost['dashboard_origem']) ? $parmPost['dashboard_origem'] : (isset($parmGet['dashboard_origem']) ? $parmGet['dashboard_origem'] : ''));
        $this->m_data_previous = (isset($parmPost['data_previous']) ? $parmPost['data_previous'] : (isset($parmGet['data_previous']) ? $parmGet['data_previous'] : ''));

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
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
        $this->m_id = (isset($parmPost['id']) ? $parmPost['id'] : (isset($parmGet['id']) ? $parmGet['id'] : ''));
        $this->m_json = (isset($parmPost['jsonAcompanhamento']) ? $parmPost['jsonAcompanhamento'] : (isset($parmGet['jsonAcompanhamento']) ? $parmGet['jsonAcompanhamento'] : null));
        $this->m_status_cli = (isset($parmPost['status_cli']) ? $parmPost['status_cli'] : (isset($parmGet['status_cli']) ? $parmGet['status_cli'] : null));
        $this->m_dados_contato = (isset($parmPost['json_dados_contato']) ? $parmPost['json_dados_contato'] : (isset($parmGet['json_dados_contato']) ? $parmGet['json_dados_contato'] : null));
        $this->m_email = (isset($parmPost['email']) ? $parmPost['email'] : (isset($parmGet['email']) ? $parmGet['email'] : null));
        $this->m_template = (isset($parmPost['template']) ? $parmPost['template'] : (isset($parmGet['template']) ? $parmGet['template'] : null));
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : (isset($parmGet['id']) ? $parmGet['id'] : ''));
        $this->setIdPedido(isset($parmGet['idPedido']) ? $parmGet['idPedido'] : (isset($parmPost['idPedido']) ? $parmPost['idPedido'] : ''));
        $this->setPessoa(isset($parmGet['pessoa']) ? $parmGet['pessoa'] : (isset($parmPost['pessoa']) ? $parmPost['pessoa'] : ''));
        $this->setDataContato(isset($parmGet['dataContato']) ? $parmGet['dataContato'] : (isset($parmPost['dataContato']) ? $parmPost['dataContato'] : null));
        $this->setAcao(isset($parmGet['acao']) ? $parmGet['acao'] : (isset($parmPost['acao']) ? $parmPost['acao'] : ''));
        $this->setVendedorAcomp(isset($parmGet['vendedorAcomp']) ? $parmGet['vendedorAcomp'] : (isset($parmPost['vendedorAcomp']) ? $parmPost['vendedorAcomp'] : ''));
        $proxIni = isset($parmGet['proximoContato']) ? $parmGet['proximoContato'] : (isset($parmPost['proximoContato']) ? $parmPost['proximoContato'] : '');
        if (($proxIni === '' || $proxIni === null) && isset($parmPost['dataHoraProximoAcomp']) && $parmPost['dataHoraProximoAcomp'] !== '') {
            $proxIni = $parmPost['dataHoraProximoAcomp'];
        }
        $this->setProximoContato($proxIni);
        $this->setResultContato(isset($parmGet['resultContato']) ? $parmGet['resultContato'] : (isset($parmPost['resultContato']) ? $parmPost['resultContato'] : ''));
        $this->setVeiculo(isset($parmGet['veiculo']) ? $parmGet['veiculo'] : (isset($parmPost['veiculo']) ? $parmPost['veiculo'] : ''));
        $this->setOrigem(isset($parmGet['origem']) ? $parmGet['origem'] : (isset($parmPost['origem']) ? $parmPost['origem'] : ''));
        $this->setDestino(isset($parmGet['destino']) ? $parmGet['destino'] : (isset($parmPost['destino']) ? $parmPost['destino'] : ''));
        $this->setKM(isset($parmGet['km']) ? $parmGet['km'] : (isset($parmPost['km']) ? $parmPost['km'] : ''));
        $this->setStatus(isset($parmGet['status']) ? $parmGet['status'] : (isset($parmPost['status']) ? $parmPost['status'] : ''));
        $this->setUserId((isset($this->m_userid) ? $this->m_userid : ''));

    }

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle() {
        switch ($this->m_submenu) {
            case 'emailAcompanhamento':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $this->desenhaEmailAcompanhamento();
                }
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $this->setDataContato(date('Y-m-d H:i'));
                    $this->setVendedorAcomp($this->m_userid);
                    $this->desenhaCadastroAcompanhamento('');
                }
                break;
            case 'buscaAcompanhamentoAjax':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $ajax = isset($_SERVER["HTTP_AJAX_REQUEST_ACOMPANHAMENTO"]) && $_SERVER["HTTP_AJAX_REQUEST_ACOMPANHAMENTO"] == "true";
                    if (!$ajax) {
                        break;
                    }
                    header('Content-Type: application/json; charset=utf-8');
                    if ($this->getId() === '' || $this->getId() === null) {
                        echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
                        exit;
                    }
                    $rows = $this->select_pessoaAcomp();
                    if (!is_array($rows) || count($rows) < 1) {
                        echo json_encode(['ok' => false, 'msg' => 'Registro não encontrado']);
                        exit;
                    }
                    $r = array_change_key_case($rows[0], CASE_UPPER);
                    $dataFmt = '';
                    if (!empty($r['DATA'])) {
                        $dataFmt = date('d/m/Y H:i', strtotime($r['DATA']));
                    }
                    $ligFmt = '';
                    if (!empty($r['LIGARDIA'])) {
                        $ligFmt = date('d/m/Y H:i', strtotime($r['LIGARDIA']));
                    }
                    $pedId = '';
                    if (isset($r['PEDIDO_ID']) && $r['PEDIDO_ID'] !== null && $r['PEDIDO_ID'] !== '') {
                        $pedId = (string) $r['PEDIDO_ID'];
                    }
                    echo json_encode([
                        'ok' => true,
                        'id' => (int) $r['ID'],
                        'pessoa' => $r['PESSOA'],
                        'nomeReduzido' => isset($r['NOMEREDUZIDO']) ? $r['NOMEREDUZIDO'] : '',
                        'acao' => $r['ATIVIDADE'],
                        'descricao' => $r['RESULTADO'],
                        'status' => (string) $r['STATUS'],
                        'dataHora' => $dataFmt,
                        'proximoContato' => $ligFmt,
                        'idPedido' => $pedId,
                        'veiculo' => isset($r['VEICULO']) ? (string) $r['VEICULO'] : '',
                        'origem' => isset($r['ORIGEM']) ? (string) $r['ORIGEM'] : '',
                        'destino' => isset($r['DESTINO']) ? (string) $r['DESTINO'] : '',
                        'km' => isset($r['KM']) && $r['KM'] !== null && $r['KM'] !== '' ? (string) $r['KM'] : '',
                        'status_cli' => isset($r['CLASSE']) ? (string) $r['CLASSE'] : '',
                        'vendedorAcomp' => isset($r['USRVENDEDOR']) ? (string) $r['USRVENDEDOR'] : '',
                    ]);
                    exit;
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    if ($this->buscaCadastroAcompanhamento()) {
                        $this->desenhaCadastroAcompanhamento('');
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $ajaxAcomp = (isset($_SERVER["HTTP_AJAX_REQUEST_ACOMPANHAMENTO"]) && $_SERVER["HTTP_AJAX_REQUEST_ACOMPANHAMENTO"] == "true");

                    if ($ajaxAcomp && $this->m_json !== null) {
                        $dados = json_decode($this->m_json);
                        if (!isset($dados->id)) {
                            header('Content-Type: application/json; charset=utf-8');
                            echo json_encode(['ok' => false, 'msg' => 'ID inválido']);
                            exit;
                        }
                        $this->setId($dados->id);
                        if (!$this->buscaCadastroAcompanhamento()) {
                            header('Content-Type: application/json; charset=utf-8');
                            echo json_encode(['ok' => false, 'msg' => 'Registro não encontrado']);
                            exit;
                        }
                        $this->setAcao($dados->acao);
                        $this->setResultContato($dados->descricao);
                        $this->setStatus(isset($dados->status) ? $dados->status : '1');
                        $this->setDataContato($dados->dataHora);
                        if (isset($dados->proximoContato) && $dados->proximoContato !== '') {
                            $this->setProximoContato($dados->proximoContato);
                        } else {
                            $this->setProximoContato(null);
                        }
                        $result = $this->alteraPessoaAcomp();
                        header('Content-Type: application/json; charset=utf-8');
                        if ($result == '') {
                            echo json_encode(['ok' => true, 'msg' => 'Registro alterado com sucesso!']);
                        } else {
                            echo json_encode(['ok' => false, 'msg' => 'Não foi possível alterar o registro.']);
                        }
                        exit;
                    }

                    if ($ajaxAcomp && $this->m_json === null) {
                        $result = $this->alteraPessoaAcomp();
                        if ($this->m_status_cli !== '') {
                            $objConta = new c_conta();
                            $objConta->updateClasse($this->pessoa, $this->m_status_cli);
                        }
                        header('Content-Type: application/json; charset=utf-8');
                        if ($result == '') {
                            echo json_encode(['ok' => true, 'msg' => 'Registro alterado com sucesso!']);
                        } else {
                            echo json_encode(['ok' => false, 'msg' => 'Não foi possível alterar o registro.']);
                        }
                        exit;
                    }

                    //altera o acompanhamento
                    $result = $this->alteraPessoaAcomp();

                    if($this->m_status_cli !== ''){
                        $objConta = new c_conta();
                        $updateStatusPessoa = $objConta->updateClasse($this->pessoa, $this->m_status_cli);
                    }

                    if ($this->m_dashboard_origem == 'dashboard_crm') { // jhon
                        //LOGICA ANTIDA, DEVE TESTANDO EM OUTROS LOCAIS PARA ATIVAR
                        // $returnAjax = 'Registro salvo!';
                        // echo $returnAjax;
                        if($result == ''){
                            $msgPedido = "Registro alterado com sucesso!";
                            echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                            echo "<style>.swal-title{font-size: 21px;}</style> ";
                            echo "<script>swal({text: `$msgPedido`, title: 'Sucesso!', icon: 'success',button: 'Ok'});</script>";
                            echo "<script>function closePag(){ setTimeout(function () {window.close();}, 2000) } closePag();</script>";
                        }else{
                            $msgPedido = "Registro não alterado!";
                            echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                            echo "<style>.swal-title{font-size: 21px;, width: 600px !important}</style> ";
                            echo "<script>swal({text: `$msgPedido`, title: 'Atenção!', icon: 'info',button: 'Ok', dangerMode: true});</script>";
                        }
                        $this->desenhaCadastroAcompanhamento('');
                    } else {
                         $this->mostraAcompanhamento('Registro salvo.');
                    }
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $ajaxAcomp = (isset($_SERVER["HTTP_AJAX_REQUEST_ACOMPANHAMENTO"]) && $_SERVER["HTTP_AJAX_REQUEST_ACOMPANHAMENTO"] == "true");
                    //tratamento para setar as variaveis antes de inserir (logica para a tela do dashboard crm)
                    if($this->m_json !== null){
                        $dados = json_decode($this->m_json);
                        $this->setPessoa($dados->pessoa);
                        $this->setAcao($dados->acao);
                        $this->setVendedorAcomp($this->m_userid);
                        $this->setResultContato($dados->descricao);
                        $this->setStatus(isset($dados->status) ? $dados->status : '1');
                        $this->setDataContato($dados->dataHora);
                        if (isset($dados->proximoContato) && $dados->proximoContato !== '') {
                            $this->setProximoContato($dados->proximoContato);
                        } else {
                            $this->setProximoContato($dados->dataHora);
                        }
                        $this->m_dashboard_origem = null;
                    }

                    $resultInsert =  $this->incluiPessoaAcomp();
                    if($this->m_status_cli !== ''){
                        $objConta = new c_conta();
                        $updateStatusPessoa = $objConta->updateClasse($this->pessoa, $this->m_status_cli);
                    }

                    if ($ajaxAcomp) {
                        header('Content-Type: application/json; charset=utf-8');
                        if ($resultInsert == '') {
                            echo json_encode(['ok' => true, 'msg' => 'Registro inserido com sucesso!']);
                        } else {
                            echo json_encode(['ok' => false, 'msg' => 'Erro ao inserir o registro.']);
                        }
                        exit;
                    }

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
                            // $returnAjax = 'Registro salvo!';
                            // echo $returnAjax;
                            if($resultInsert == ''){
                                $msgPedido = "Registro alterado com sucesso!";
                                echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                                echo "<style>.swal-title{font-size: 21px;}</style> ";
                                echo "<script>swal({text: `$msgPedido`, title: 'Sucesso!', icon: 'success',button: 'Ok'});</script>";
                                echo "<script>function closePag(){ setTimeout(function () {window.close();}, 2000) } closePag();</script>";
                            }else{
                                $msgPedido = "Registro não alterado!";
                                echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                                echo "<style>.swal-title{font-size: 21px;, width: 600px !important}</style> ";
                                echo "<script>swal({text: `$msgPedido`, title: 'Atenção!', icon: 'info',button: 'Ok', dangerMode: true});</script>";
                            }
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
            case 'incluiContato':
                $resultInsertContato = $this->insertContatoCliente($this->m_dados_contato);
                $this->desenhaCadastroAcompanhamento(null, null, $resultInsertContato);
                break;
            case 'sendEmail':
                $mail = new admMail;

                //validate host
                if(empty($this->m_configsmtp) || empty($this->m_configemail) || empty($this->m_configemailsenha)){
                    $response  = [ 'codigo' => '403', 'msg' => 'Configuracao de e-mail do remetente invalida!'];
                    echo json_encode($response);
                    break;
                }

                $resultSend = $mail->SendMail( $this->m_configsmtp,
                                               $this->m_configemail,
                                               $this->m_configemail,
                                               $this->m_configemailsenha,
                                               $this->m_email["body"],
                                               $this->m_email["assunto"],
                                               $this->m_email["destinatario"],
                                                '', null, null, 
                                                $this->m_email["anexos"],null,
                                                'several');

                $data_hora_envio = date("Y-m-d H:i:s");
                
                if($resultSend !== true){
                    //monta array temporario para fazer o merge para o insert 
                    $arrayTemp = ['status' => 'NAOENVIADO', 
                                  'origem' => 'ACO', 
                                  'msg' => $resultSend->getMessage() . '| code:' . $resultSend->getCode(),
                                  'data_hora' => $data_hora_envio];
                    
                    //check which code 
                    if($resultSend->getCode() == 1){ //codigo de erro de endereco de e-mail
                        // mount regex (expressao regular)
                        $padrao = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/';
                        //which will capture emails 
                        if (preg_match_all($padrao, $resultSend->getMessage(), $matches)) {
                            //eliminates repeated emails
                            //$emails = array_unique($matches[0]);
                            $emails = $matches[0];
                            $stringEmails = "(".implode(", ", $emails).")";
                        }
                        $response  = ['codigo' => '404', 'msg' => 'Não foi possível enviar e-mail para esse(s) endereço(s) ' . $stringEmails];
                    }
                    
                }else{
                    $arrayTemp = ['status' => 'ENVIADO', 'origem' => 'ACO', 'msg' => null, 'data_hora' => $data_hora_envio];
                    $response  = ['codigo' => '100','msg' => 'E-mail enviado!'];
                }

                //realiza o merge dos arrays
                $newArray = array_merge($this->m_email, $arrayTemp);
                unset($arrayTemp);

                //insert do acompanhamento
                $insertAcompEmail = $this->insertAcompanhamentoFromEmail($newArray);
                if(is_int($insertAcompEmail)){
                    $newArray['id_novo_acompanhamento'] = $insertAcompEmail;
                }else{
                    $newArray['id_novo_acompanhamento'] = 9999;
                }

                $response['resultAcompanhamentoEmail'] = $insertAcompEmail;

                //inclui o registro na tabela amb_email
                $insertEmail = $this->insertEmail($newArray);
                $response['resultInsertEmail'] = $insertEmail;

                echo json_encode($response);

                break;
            case 'savedEmail':
                $this->m_email['status'] = 'ABERTO';
                $this->m_email['data_hora'] = date("Y-m-d H:i:s");
                $this->m_email['origem'] = 'ACO';
                //insert do acompanhamento
                $insertAcompEmail = $this->insertAcompanhamentoFromEmail($this->m_email, 'saved');
                if(is_int($insertAcompEmail)){
                    $newArray['id_novo_acompanhamento'] = $insertAcompEmail;
                }else{
                    $newArray['id_novo_acompanhamento'] = 9999;
                }

                $newArray = array_merge($this->m_email, $newArray);

                //inclui o registro na tabela amb_email
                $insertEmail = $this->insertEmail($newArray, 'escape');
                $response['resultInsertEmail'] = $insertEmail;
                
                
                if($response == true){
                    echo json_encode($response);
                }else{
                    echo json_encode('404');
                }

                break;
            case 'buscaTemplate':

                $result = $this->selectBuscaTemplate($this->m_template);

                if(is_array($result)){
                    echo json_encode($result[0]['BODY']);
                }else{
                    echo json_encode('404');
                }

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
    function desenhaCadastroAcompanhamento($mensagem = NULL, $tipoMsg = null, $param = null) {

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
        $nomePessoa = '';
        $arrPessoa = [];
        if ($this->getPessoa() != '') {
            $arrPessoa = $this->select_pessoa();
            if (is_array($arrPessoa) && isset($arrPessoa[0]['NOME'])) {
                $nomePessoa = (string) $arrPessoa[0]['NOME'];
            }
        }
        if ($nomePessoa === '' && $this->m_nome !== '' && $this->m_nome !== null) {
            $nomePessoa = trim((string) $this->m_nome, " \t\n\r\0\x0B'\"");
        }
        $this->smarty->assign('pessoaNome', $nomePessoa);
        $this->smarty->assign('pessoaNomeJson', json_encode($nomePessoa, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT));
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


        ////#################### COMBO VENDEDOR ####################
        $consulta = new c_banco();
        $sql = "select usuario as id, nomereduzido as descricao from amb_usuario  where (situacao='A') and (TIPO in ('V', 'G')) order by nomereduzido";
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

        //#################### COMBO STATUS ####################
        $ddmStatusAcomp = c_crm_contas_acompanhamento::carregaComboDdmStatusAcomp();
        $this->smarty->assign('status_ids', $ddmStatusAcomp['ids']);
        $this->smarty->assign('status_names', $ddmStatusAcomp['names']);
        $this->smarty->assign('status_id', $this->getStatus());

        //#################### COMBO ESTADO ####################
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado');";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
           $estado_ids[$i] = $result[$i]['ID'];
           $estado_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('estado_ids', $estado_ids);
        $this->smarty->assign('estado_names', $estado_names);
        // $this->smarty->assign('estado_id', $this->getEstado());

        // COMBOBOX SITUACAO
        $sql = "SELECT CLASSE AS ID, DESCRICAO FROM FIN_CLASSE";
        $this->comboSql($sql, "", $status_cli_id, $status_cli_ids, $status_cli_names);
        $this->smarty->assign('status_cli_ids', $status_cli_ids);
        $this->smarty->assign('status_cli_names', $status_cli_names);
        if($this->pessoa !== ''){
            $objConta = new c_conta;
            $objConta->setId($this->pessoa);
            $pessoa = $objConta->select_conta();
            $this->smarty->assign('status_cli_id', $pessoa[0]["CLASSE"]);
        }else{
            $this->smarty->assign('status_cli_id', $status_cli_id);
        }
        
        //########## FIM SITUACAO ##########

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

        //#################### COMBO CONTATO ####################
        $consulta = new c_banco();
        $sql = "select id as id, CONCAT(nome_contato, ' - ', telefone) as descricao from fin_cliente_contato where id_cliente = " . $this->getPessoa() . ";";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?: [];
        for ($i = 0; $i < count($result); $i++) {
            $contato_ids[$i] = $result[$i]['ID'];
            $contato_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('contato_ids', $contato_ids);
        $this->smarty->assign('contato_names', $contato_names);
        $this->smarty->assign('contato_id', $this->getPessoa());

        $this->smarty->assign('proximoContato', "'".$this->getProximoContato('F')."'");
        $this->smarty->assign('resultContato', $this->getResultContato());
        $this->smarty->assign('origem', $this->getOrigem());
        $this->smarty->assign('destino', $this->getDestino());
        $this->smarty->assign('km', $this->getKM());

        //consulta acompanhamentos clientes
        $resultAcompanhamento = $this->selectAcompanhamentoPessoa($this->getPessoa());
        $this->smarty->assign('resultAcompanhamento', $resultAcompanhamento);
        if(is_array($resultAcompanhamento)){
            $this->smarty->assign('existeAcompanhamento', 'yes');
        }else{
            $this->smarty->assign('existeAcompanhamento', 'no');
        }


        //BUSCA CONTATO CLIENTE
        $contatos = $this->selectBuscaContatoCliente($this->pessoa);
        $this->smarty->assign('contatos_cliente', $contatos);

        // Templates de e-mail (AMB_TEMPLATE — chaves ID / DESCRICAO como no banco)
        $templatesEmail = [];
        $consultaTemplate = new c_banco();
        $sqlTemplate = "SELECT ID, DESCRICAO FROM AMB_TEMPLATE ORDER BY DESCRICAO";
        $consultaTemplate->exec_sql($sqlTemplate);
        $consultaTemplate->close_connection();
        if (is_array($consultaTemplate->resultado)) {
            $templatesEmail = $consultaTemplate->resultado;
        }
        $this->smarty->assign('templates_email', $templatesEmail);

        //parametro de retorno do insert contato
        if($param !== null){
            $this->smarty->assign('codigo_retorno_contato', $param["codigo"]);
            $this->smarty->assign('mensagem_retorno_contato', '"'.$param["mensagem"].'"');
        }else{
            $this->smarty->assign('mensagem_retorno_contato', null);
            $this->smarty->assign('codigo_retorno_contato', null);
        }


        //CONFIGURACAO EMAIL
        $string = json_decode($_SESSION["user_array"]);
        //remetente
        if($string[15] !== ''){
            $this->smarty->assign('email_remetente', $string[15]);
        }else{
            $this->smarty->assign('email_remetente', 'E-mail não localizado nas configurações do usuário');
        }

        $consultaEmail = [];
        $emailRow = [];

        //se existir o id do email
       // if($this->id !== ""){
       //     $consultaEmail = $this->selectBuscaEmail($this->id);
       //     $this->smarty->assign('email_id', $this->id);
       // }
       // if (is_array($consultaEmail) && isset($consultaEmail[0]) && is_array($consultaEmail[0])) {
       //     $emailRow = $consultaEmail[0];
       // }
        
        //Verifica body email para setar a variavel
        if(isset($emailRow["CORPO"]) && $emailRow["CORPO"] !== '' && $emailRow["CORPO"] !== null){
            $this->smarty->assign('editorOne', $emailRow["CORPO"]);
        }else{
            $this->smarty->assign('editorOne', null);
        }

        //Verifica destinatario email para setar a variavel
        if(isset($emailRow["DESTINATARIO"]) && $emailRow["DESTINATARIO"] !== '' && $emailRow["DESTINATARIO"] !== null){
            $this->smarty->assign('email_destinatario', $emailRow["DESTINATARIO"]);
        }else{
            //verifica se existe ja email se nao insere o cliente
            if (isset($arrPessoa[0]["EMAIL"]) && $arrPessoa[0]["EMAIL"] !== '' && $arrPessoa[0]["EMAIL"] !== null) {
                $mail = trim($arrPessoa[0]["EMAIL"]);
                $this->smarty->assign('email_destinatario', $mail);
            }else{
                $this->smarty->assign('email_destinatario', null);
            }
        }
        //Verifica destinatario email para setar a variavel
        if(isset($emailRow["ASSUNTO"]) && $emailRow["ASSUNTO"] !== '' && $emailRow["ASSUNTO"] !== null){
            $this->smarty->assign('email_assunto', $emailRow["ASSUNTO"]);
        }else{
            $this->smarty->assign('email_assunto', null);
        }

        //Verifica destinatario email para setar a variavel
        if(isset($emailRow["ANEXO"]) && $emailRow["ANEXO"] !== '' && $emailRow["ANEXO"] !== null){
            $anexoExplode = explode(';', $emailRow["ANEXO"]);

            foreach ($anexoExplode as $valor) {
                // Atribui o valor à variável usando o Smarty
                $this->smarty->assign($valor, true);
            }

            $this->smarty->assign('email_anexo', $emailRow["ANEXO"]);

        }else{
            $this->smarty->assign('email_anexo', null);
        }

        $pxf = $this->getProximoContato('F');
        $this->smarty->assign('dataHoraProxCont', ($pxf !== null && $pxf !== '') ? (string) $pxf : '');
        $this->smarty->display('crm_contas_acompanhamento_cadastro.tpl');
    }

    function desenhaEmailAcompanhamento()
    {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('dashboard_origem', $this->m_dashboard_origem);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('pessoa', $this->getPessoa());

        $arrPessoa = [];
        $nomePessoaEmail = '';
        if ($this->getPessoa() != '') {
            $arrPessoa = $this->select_pessoa();
            if (is_array($arrPessoa) && isset($arrPessoa[0]['NOME'])) {
                $nomePessoaEmail = (string) $arrPessoa[0]['NOME'];
            }
        }
        if ($nomePessoaEmail === '' && $this->m_nome !== '' && $this->m_nome !== null) {
            $nomePessoaEmail = trim((string) $this->m_nome, " \t\n\r\0\x0B'\"");
        }
        $this->smarty->assign('pessoaNome', $nomePessoaEmail);
        $this->smarty->assign('pessoaNomeJson', json_encode($nomePessoaEmail, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT));

        $contatos = $this->selectBuscaContatoCliente($this->pessoa);
        $this->smarty->assign('contatos_cliente', $contatos);

        // Templates de e-mail (dinâmico da tabela AMB_TEMPLATE)
        $templatesEmail = [];
        $consultaTemplate = new c_banco();
        $sqlTemplate = "SELECT  ID, DESCRICAO FROM AMB_TEMPLATE ORDER BY DESCRICAO";
        $consultaTemplate->exec_sql($sqlTemplate);
        $consultaTemplate->close_connection();
        if (is_array($consultaTemplate->resultado)) {
            $templatesEmail = $consultaTemplate->resultado;
        }
        $this->smarty->assign('templates_email', $templatesEmail);

        // CONFIGURACAO EMAIL
        $string = json_decode($_SESSION["user_array"]);
        if (isset($string[15]) && $string[15] !== '') {
            $this->smarty->assign('email_remetente', $string[15]);
        } else {
            $this->smarty->assign('email_remetente', 'E-mail não localizado nas configurações do usuário');
        }

        $consultaEmail = [];
        $emailRow = [];
       // if ($this->id !== "") {
       //     $consultaEmail = $this->selectBuscaEmail($this->id);
       //     $this->smarty->assign('email_id', $this->id);
       // }
       // if (is_array($consultaEmail) && isset($consultaEmail[0]) && is_array($consultaEmail[0])) {
       //     $emailRow = $consultaEmail[0];
       // }

        if (isset($emailRow["CORPO"]) && $emailRow["CORPO"] !== '' && $emailRow["CORPO"] !== null) {
            $this->smarty->assign('editorOne', $emailRow["CORPO"]);
        } else {
            $this->smarty->assign('editorOne', null);
        }

        if (isset($emailRow["DESTINATARIO"]) && $emailRow["DESTINATARIO"] !== '' && $emailRow["DESTINATARIO"] !== null) {
            $this->smarty->assign('email_destinatario', $emailRow["DESTINATARIO"]);
        } else {
            if (isset($arrPessoa[0]["EMAIL"]) && $arrPessoa[0]["EMAIL"] !== '' && $arrPessoa[0]["EMAIL"] !== null) {
                $this->smarty->assign('email_destinatario', trim($arrPessoa[0]["EMAIL"]));
            } else {
                $this->smarty->assign('email_destinatario', null);
            }
        }

        if (isset($emailRow["ASSUNTO"]) && $emailRow["ASSUNTO"] !== '' && $emailRow["ASSUNTO"] !== null) {
            $this->smarty->assign('email_assunto', $emailRow["ASSUNTO"]);
        } else {
            $this->smarty->assign('email_assunto', null);
        }

        if (isset($emailRow["ANEXO"]) && $emailRow["ANEXO"] !== '' && $emailRow["ANEXO"] !== null) {
            $anexoExplode = explode(';', $emailRow["ANEXO"]);
            foreach ($anexoExplode as $valor) {
                $this->smarty->assign($valor, true);
            }
            $this->smarty->assign('email_anexo', $emailRow["ANEXO"]);
        } else {
            $this->smarty->assign('email_anexo', null);
        }

        $this->smarty->display('crm_contas_acompanhamento_email.tpl');
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
            $result = [];
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
        $this->smarty->assign('TotalLanc', count($lanc));

        $this->smarty->display('crm_contas_acompanhamento_mostra.tpl');
    }

//fim mostrakardexs
//-------------------------------------------------------------
}

//	END OF THE CLASS

$acompanhamento = new p_crm_contas_acompanhamento();
// Rotina principal - cria classe
$acompanhamento->controle();

?>
