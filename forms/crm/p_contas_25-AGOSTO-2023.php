<?php
/**
 * @package   astec
 * @name      p_contas
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      05/03/2016
 */
if (!defined('ADMpath')): exit; endif;

$dir = (__DIR__);
require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/crm/c_conta.php");
require_once($dir."/../../class/ped/c_pedido_venda.php");
require_once($dir."/../../bib/c_tools.php");

//Class P_conta
Class p_conta extends c_conta {

    private $m_submenu = NULL;
    private $m_opcao = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;
    public $from = NULL; // tela de pesquisa (qual tpl esta chamando)
    public $m_check = NULL;
    private $checkPedido = 'N';

//---------------------------------------------------------------
//---------------------------------------------------------------
    function __construct($submenu, $letra, $opcao) {
        @set_exception_handler(array($this, 'exception_handler'));

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);
        
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
        $this->from = (isset($parmGet['from']) ? $parmGet['from'] : (isset($parmPost['from']) ? $parmPost['from'] : ''));
        $this->m_check = (isset($parmGet['check']) ? $parmGet['check'] : (isset($parmPost['check']) ? $parmPost['check'] : ''));
        $this->checkPedido = (isset($parmGet['checkPedido']) ? $parmGet['checkPedido'] : (isset($parmPost['checkPedido']) ? $parmPost['checkPedido'] : 'N'));


        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Contas");
        if ($this->m_opcao == 'os'){
            $this->smarty->assign('colVis', "[ 0,1,2,3 ]"); 
            $this->smarty->assign('disableSort', "[ 3 ]"); }
        else{    
            $this->smarty->assign('colVis', "[ 0,1,2,3,4 ]"); 
            $this->smarty->assign('disableSort', "[ 4 ]"); }
        $this->smarty->assign('numLine', "25"); 

        // include do javascript
        //include ADMjs . "/crm/s_conta.js";
    }

    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
            if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                $this->setAtividade('CB');
                    //$this->setPessoa('F');
                    $this->setClasse('01');
                    $this->setCentrocusto($this->m_empresacentrocusto);
                    $this->setRepresentante($this->m_userid);
                    $this->desenhaCadastroConta();
                }
                break;
            
            case 'alterar':
                if ($this->verificaDireitoUsuario('FinPessoa', 'C')) {
                    $this->busca_conta();
                    
                    $this->desenhaCadastroConta();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('FinPessoa', 'I')) {
                    $insert = true;
                    $msg    = '';
                    if ($this->getCnpjCpf()!= ''){
                        $cnpjCpf = new ValidaCPFCNPJ($this->getCnpjCpf());
                        if (!$cnpjCpf->valida()){
                            $insert = false;
                            $msg    = '<b>'.'CNPJ/CPF '.'</b>'.'INV&Aacute;LIDO!';
                        }else {
                            if ($this->existeContaCnpj($this->getCnpjCpf(), false) > 0 ) {
                                $insert = false;
                                $msg    = '<b>'.'CNPJ/CPF '.'</b>'.'J&Aacute; POSSUI CADASTRO!';
                            }
                        }
                    }

                    //Verifica se já existe o nome do cliente cadastrado
                    if($this->getNome() != '' and $msg == ''){
                        $cel = $this->verificaNome($this->getNome());
                        if($cel != null){
                            $insert = false;
                            $msg    = '<b>' .'NOME COMPLETO JÁ CADASTRADO!' . '</b>' . '<br>' . 
                                      'Cliente: ' . '<b>' . $cel[0]['NOME'].'</b>'.'<br>'.
                                      'ID: '  . '<b>' . $cel[0]['CLIENTE'].'</b>';
                        }
                    }
                    
                    //Verifica se já existe o celular cadastrado
                    if($this->getCelular() != '' and $msg == ''){
                        $cel = $this->verificaCelular($this->getCelular());
                        if($cel != null){
                            $insert = false;
                            $msg    = '<b>'.'CELULAR JÁ CADASTRADO!'.'<b>' .'<br>' . 
                                      'Cliente: '.'<b>'.$cel[0]['NOME'].'</b>'.'<br>'.
                                      'ID: '.'<b>'.$cel[0]['CLIENTE'].'</b>';
                        }
                    }

                    //Verifica se existe pessoa fisica ou juridica
                    if($this->getPessoa() === '' and $msg == ''){
                        $insert = false;
                        $msg    = "<b>"."TIPO PESSOA "."</b>"."NÃO"." SELECIONADO!";
                    }

                    //Verifica tipo da pessoa e se é CPF ou CNPJ
                    if($this->getPessoa() !== '' and $msg == ''){

                        if($this->getCnpjCpf() !== ''){
                            $tipoPessoa  = $this->getPessoa();
                            $cpfCnpj     = $this->getCnpjCpf();
                            $sizeCpfCnpj = strlen($cpfCnpj);

                            if($tipoPessoa === 'J'){
                                if($sizeCpfCnpj < 14){
                                    $insert = false;
                                    $msg    = "<b>"."CNPJ "."</b>"."INFORMADO MENOR QUE O PERMITIDO!";        
                                }
                            }else{
                                if($sizeCpfCnpj > 11){
                                    $insert = false;
                                    $msg    = "<b>"."CPF "."</b>"."INFORMADO MAIOR QUE O PERMITIDO!";
                                }
                                if($sizeCpfCnpj < 11){
                                    $insert = false;
                                    $msg    = "<b>"."CPF "."</b>"."INFORMADO MENOR QUE O PERMITIDO!";
                                }
                            }
                        }
                    }

                    //Cadastra o cliente se $insert for true e não contem msg
                    if (($insert) and ($msg == '')){
                        $msg = $this->incluiConta();
                    }

                    if ($msg === true):
                        $sizeCli = strlen($_REQUEST["nome"]);
                        $result = 'Cliente '.$_REQUEST["nome"]. ' cadastrado(a).';

                        //ajusta widht da modal
                        if($sizeCli >= 40){
                            $sizeModal = '800px';
                        }elseif($sizeCli >= 30){
                            $sizeModal = '600px';
                        }elseif($sizeCli >= 80){
                            $sizeModal = '900px';
                        }elseif($sizeCli >= 100){
                            $sizeModal = '1000px';
                        }else{
                            $sizeModal = '510px';
                        }
                        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                        echo "<style>.swal-modal{width: ".$sizeModal." !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$result`, title: 'Sucesso!', icon: 'success',button: 'Ok',});</script>";

                        $this->mostraConta();
                    else:    
                        $this->m_submenu = 'cadastrar';
                        $this->desenhaCadastroConta($msg, 'alerta');
                    endif;
                }
                    /* SCRIPT OLD
                    $insert = true;
                    $msg = '';
                    if ($this->getCnpjCpf()!= ''){
                        $cnpjCpf = new ValidaCPFCNPJ($this->getCnpjCpf());
                        if (!$cnpjCpf->valida()){
                            $insert = false;
                            $msg = 'CNPJ/CPF Invalido!';
                        }else {
                            if ($this->existeContaCnpj($this->getCnpjCpf(), false) > 0 ) {
                                $insert = false;
                                $msg = 'CNPJ/CPF Já cadastrado!';
                            }
                        }
                    }    
                    if (($insert) and ($msg == '')){
                        $msg = $this->incluiConta();
                    }

                    if ($msg == true):
                        $this->mostraConta('');
                        $clienteCad = $this->getNomeReduzido();

                        
                        echo"<script>
                                swal({
                                    title: 'Cadastro $clienteCad Realizado!',
                                    icon: 'success',
                                  });
                            </script>";
                    else:    
                        $this->m_submenu = 'cadastrar';
                        $this->desenhaCadastroConta($msg, 'alerta');
                    endif;
                } */
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('FinPessoa', 'A')) {
                    if ($this->getCnpjCpf()!= ''){
                        $cnpjCpf = new ValidaCPFCNPJ($this->getCnpjCpf());
                        if ($cnpjCpf->valida()){
                            if ($this->existeContaCnpj($this->getCnpjCpf(), false) > 1 ) {
                                $this->desenhaCadastroConta('CNPJ/CPF Já Cadastrado!', 'alerta');
                            }
                            else {
                                $this->alteraConta();
                                $this->mostraConta('Registro Salvo.');
                            }    
                        }else{
                            $this->desenhaCadastroConta('CNPJ/CPF Invalido!', 'alerta');
                        }
                    }else{
                        $this->alteraConta();
                        $this->mostraConta('Registro Salvo.');
                    }
                    
                    //if ( $cnpjCpf->valida() ) {
                    
                    
                }
                break;
            
            case 'exclui':
                if ($this->verificaDireitoUsuario('FinPessoa', 'E')) {
                    if ($this->existeLancamentosPessoa($this->getId()) == true):
                        $this->mostraConta("Exclusão não autorizada, lancamentos existentes para esta conta", 'alerta');
                    else:    
                        $this->mostraConta($this->excluiConta());
                    endif;
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('FinPessoa', 'C')) {
                    //$this->existeContaDuplicada();  
                    $this->mostraConta();
                }
        }
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
    function desenhaCadastroConta($mensagem = NULL, $tipoMsg=NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('acao', $this->m_acao);
        $this->smarty->assign('aba', $this->m_aba);
        $this->smarty->assign('letra', "'" . $this->m_letra . "'");
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('nome', "'" . $this->getNome() . "'");
        $this->smarty->assign('nomeReduzido', "'" . $this->getNomeReduzido() . "'");
        $this->smarty->assign('cnpjCpf', "'" . $this->getCnpjCpf() . "'");
        $this->smarty->assign('dataNascimento', $this->getDatanascimento('F'));
        $this->smarty->assign('ieRg', "'" . $this->getIeRg() . "'");
        $this->smarty->assign('im', $this->getIm());
        $this->smarty->assign('cep', $this->getCep());
        $this->smarty->assign('tipo', $this->getTipo());
        $this->smarty->assign('titulo', "'" . $this->getTitulo() . "'");
        $this->smarty->assign('endereco', "'" . $this->getEndereco() . "'");
        $this->smarty->assign('numero', $this->getNumero());
        $this->smarty->assign('complemento', "'" . $this->getComplemento('F') . "'");
        $this->smarty->assign('bairro', "'" . $this->getBairro() . "'");
        $this->smarty->assign('cidade', "'" . $this->getCidade() . "'");
        $this->smarty->assign('codMunicipio', "'" . $this->getCodMunicipio() . "'");
        $this->smarty->assign('suframa', "'" . $this->getSuframa() . "'");
        $this->smarty->assign('fone', "'" . $this->getFone() . "'");
        $this->smarty->assign('celular', "'" . $this->getCelular() . "'");
        $this->smarty->assign('faxArea', "'" . $this->getFaxArea() . "'");
        $this->smarty->assign('faxNum', "'" . $this->getFaxNum() . "'");
        $this->smarty->assign('contato', "'" . $this->getContato() . "'");
        $this->smarty->assign('email', "'" . $this->getEmail() . "'");
        $this->smarty->assign('homePage', "'" . $this->getHomePage() . "'");
        $this->smarty->assign('emailNfe', "'" . $this->getEmailNfe() . "'");
        $this->smarty->assign('userLogin', "'" . $this->getUserLogin() . "'");
        $this->smarty->assign('senhaLogin', "'" . $this->getSenhaLogin() . "'");
        $this->smarty->assign('limiteCredito', $this->getLimiteCredito('F'));
        $this->smarty->assign('obs', $this->getObs());
        $this->smarty->assign('transversal1', $this->getTransversal1());
        $this->smarty->assign('transversal2', $this->getTransversal2());
        $this->smarty->assign('referencia', $this->getReferencia());
        $this->smarty->assign('regimeEspecialST', $this->getRegimeEspecialST());
        $this->smarty->assign('regimeEspecialSTMsg', $this->getRegimeEspecialSTMsg());
        $this->smarty->assign('regimeEspecialSTMT', $this->getRegimeEspecialSTMT());
        $this->smarty->assign('contribuinteICMS', $this->getContribuinteICMS());
        $this->smarty->assign('consumidorFinal', $this->getConsumidorFinal());
        $this->smarty->assign('regimeEspecialSTMTAliq', $this->getRegimeEspecialSTMTAliq());
        $this->smarty->assign('regimeEspecialSTAliq', $this->getRegimeEspecialSTAliq());
        
        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        $this->smarty->assign('filial_id', $this->getCentroCusto());

        // Pessoa
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Pessoa')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $pessoa_ids[0] = '';
        $pessoa_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $pessoa_ids[$i + 1] = $result[$i]['ID'];
            $pessoa_names[$i + 1] = $result[$i]['DESCRICAO'];
            //echo "Nome: ".$result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('pessoa_ids', $pessoa_ids);
        $this->smarty->assign('pessoa_names', $pessoa_names);
        $this->smarty->assign('pessoa_id', $this->getPessoa());

        // estado
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $estado_ids[0] = '0';
        $estado_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $estado_ids[$i+1] = $result[$i]['ID'];
            $estado_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('estado_ids', $estado_ids);
        $this->smarty->assign('estado_names', $estado_names);
        $this->smarty->assign('estado_id', $this->getEstado());

       
//vendedor
    $consulta = new c_banco();
	$sql = "select usuario as id, nomereduzido as descricao from amb_usuario where ((situacao = 'A')) order by nomereduzido";
  	$consulta->exec_sql($sql);
	$consulta->close_connection();
  	$result = $consulta->resultado;
  	$responsavel_ids[0] = '0';
  	$responsavel_names[0] = 'Selecione';
  	 
	for ($i=0; $i < count($result); $i++){
		$responsavel_ids[$i+1] = $result[$i]['ID'];
		$responsavel_names[$i+1] = $result[$i]['ID']." - ".$result[$i]['DESCRICAO'];
	}	
	
	$this->smarty->assign('responsavel_ids', $responsavel_ids);
	$this->smarty->assign('responsavel_names', $responsavel_names);	
	$this->smarty->assign('responsavel_id', $this->getRepresentante());

        //atividade
        $consulta = new c_banco();
        $sql = "select atividade as id, descricao from fin_atividade";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        for ($i = 0; $i < count($result); $i++) {
            $atividade_ids[$i] = $result[$i]['ID'];
            $atividade_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('atividade_ids', $atividade_ids);
        $this->smarty->assign('atividade_names', $atividade_names);
        $this->smarty->assign('atividade_id', $this->getAtividade());

        //classe
        $consulta = new c_banco();
        $sql = "select classe as id, descricao from fin_classe";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        for ($i = 0; $i < count($result); $i++) {
            $classe_ids[$i] = $result[$i]['ID'];
            $classe_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }

        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='BOOLEAN')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $boolean_ids[$i] = $result[$i]['ID'];
                $boolean_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('boolean_ids', $boolean_ids);
        $this->smarty->assign('boolean_names', $boolean_names);
        
        $this->smarty->assign('classe_ids', $classe_ids);
        $this->smarty->assign('classe_names', $classe_names);
        $this->smarty->assign('classe_id', $this->getClasse());

        $sql = "SELECT * from FIN_CLIENTE_CREDITO where (CLIENTE = '".$this->getId()."')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $this->smarty->assign('credito', $result);
        

        $this->smarty->display('conta_cadastro.tpl');
    }

//fim desenhaCadastroConta
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraConta($mensagem = null, $tipoMsg = 'sucesso', $lanc = null) {
        
        //Verifica se check igual true para pesquisa de pedidos
        if($this->m_check == true){
            $par = explode("|", $this->m_letra);
            $idCliente = $par[1];
            array_splice($par, 1, 2);
            
            //consulta pedidos
            $consultaPed = new c_pedidoVenda();
            $letra = '||'.$idCliente;
            $resultPed = $consultaPed->select_pedidoVenda_letra($letra);

            if($resultPed !== null and $resultPed !== ''){
                $this->smarty->assign('existePedido', 'yes');
                $this->smarty->assign('resultPed', $resultPed);
                $this->smarty->assign('active2', 'active in');
                //remove o atributo 'active in' para aba de pesquisa
                $active = true;
                $this->smarty->assign('active1', '');
            }else{
                $this->smarty->assign('existePedido', 'no');
            }
            //seta o nome para a consulta de pedidos
            $this->m_letra = $par[0];

        }


        if (isset($this->m_letra) and ( $this->m_letra != '')) {
            $lanc = $this->select_pessoa_letra(strtoupper($this->m_letra));
        } else
            $lanc = '';
        $teste_array = (is_array($lanc) and ($this->checkPedido ==="S"));
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', "'" . $this->m_letra . "'");
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('acao', $this->m_acao);
        $this->smarty->assign('from', $this->from);

        $this->smarty->assign('pesNome', "'" . $this->m_par[0] . "'");
        $this->smarty->assign('cidade', "'" . $this->m_par[5] . "'");
        $this->smarty->assign('pesCnpjCpf', $this->m_par[7]);
        $this->smarty->assign('pesObs', $this->m_par[8]);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('ADMhttpCliente', ADMhttpCliente);
        
        if($active !== true){
            $this->smarty->assign('active1', 'active in');
        }
        

        // Responsavel
        $consulta = new c_banco();
        $sql = "select usuario as id, nomereduzido as descricao from amb_usuario where (situacao = 'A') order by nomereduzido";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $responsavel_ids[0] = '';
        $responsavel_names[0] = 'Selecione um Responsável';
        for ($i = 0; $i < count($result); $i++) {
            $responsavel_ids[$i + 1] = $result[$i]['ID'];
            $responsavel_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('responsavel_ids', $responsavel_ids);
        $this->smarty->assign('responsavel_names', $responsavel_names);
        if ($this->m_par[4] == "")
            $this->smarty->assign('responsavel_id', 'Todos');
        else
            $this->smarty->assign('responsavel_id', $this->m_par[4]);

        // tipoPessoa
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Pessoa')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $tipoPessoa_ids[0] = '';
        $tipoPessoa_names[0] = 'Selecione um Tipo';
        for ($i = 0; $i < count($result); $i++) {
            $tipoPessoa_ids[$i + 1] = $result[$i]['ID'];
            $tipoPessoa_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipoPessoa_ids', $tipoPessoa_ids);
        $this->smarty->assign('tipoPessoa_names', $tipoPessoa_names);
        if ($this->m_par[2] == "")
            $this->smarty->assign('tipoPessoa_id', 'Todos');
        else
            $this->smarty->assign('tipoPessoa_id', $this->m_par[2]);

        // Estado
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $estado_ids[0] = '';
        $estado_names[0] = 'Selecione um Estado';
        for ($i = 0; $i < count($result); $i++) {
            $estado_ids[$i + 1] = $result[$i]['ID'];
            $estado_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('estado_ids', $estado_ids);
        $this->smarty->assign('estado_names', $estado_names);
        if ($this->m_par[3] == "")
            $this->smarty->assign('estado_id', 'Todos');
        else
            $this->smarty->assign('estado_id', $this->m_par[3]);

        // ATIVIDADE
        $consulta = new c_banco();
        $sql = "select atividade as id, descricao from fin_atividade";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $atividade_ids[0] = '';
        $atividade_names[0] = 'Selecione uma Atividade';
        for ($i = 0; $i < count($result); $i++) {
            $atividade_ids[$i + 1] = $result[$i]['ID'];
            $atividade_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('atividade_ids', $atividade_ids);
        $this->smarty->assign('atividade_names', $atividade_names);
        if ($this->m_par[6] == "")
            $this->smarty->assign('atividade_id', 'Todos');
        else
            $this->smarty->assign('atividade_id', $this->m_par[6]);

        // Classe
        $consulta = new c_banco();
        $sql = "select classe as id, descricao from fin_classe";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $classe_ids[0] = '';
        $classe_names[0] = 'Selecione uma Classe';
        for ($i = 0; $i < count($result); $i++) {
            $classe_ids[$i + 1] = $result[$i]['ID'];
            $classe_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('classe_ids', $classe_ids);
        $this->smarty->assign('classe_names', $classe_names);
        if ($this->m_par[1] == "")
            $this->smarty->assign('classe_id', 'Todos');
        else
            $this->smarty->assign('classe_id', $this->m_par[1]);

        switch ($this->m_opcao) {
            case 'pesquisarfornecedor':
            case 'pesquisartransportador':
            case 'pesquisarequivalente':
            case 'pesquisarAtendimento':
            case 'pesquisar':
                $this->smarty->display('conta_pesquisar.tpl');
                break;
            case 'pesquisarUnificaCliente':
                $this->smarty->display('conta_pesquisar_unifica_cliente.tpl');
            case 'os':
                $this->smarty->display('conta_mostra_os.tpl');
                break;
            case 'lista':
                $this->smarty->display('conta_mostra.tpl');
                break;
            default:
                if ($teste_array==true){
                    $consultaVendas = new c_pedidoVenda();
                    for ($i = 0; $i < count($lanc); $i++) {
                        $this->setId($lanc[$i]['CLIENTE']);
                        $result = $this->last_perfil();
                        // consulta Vendas Pedido
                        $letra = '||'.$this->getId();
                        $resultVendas = $consultaVendas->select_pedidoVenda_letra($letra);
                        $lanc[$i]['DATA']=$result[0]['DATA'];
                        $lanc[$i]['RESULTADO']=$result[0]['RESULTADO'];
                        $lanc[$i]['LIGARDIA']=$result[0]['LIGARDIA'];
                        $lanc[$i]['VENDAS']=$resultVendas;
                    }
                    $this->smarty->assign('lanc', $lanc);
                }    
                $this->smarty->display('conta_perfil_mostra.tpl');
        }
                
}

//fim mostraConta
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$conta = new p_conta($_POST['submenu'] == ''? $_REQUEST['submenu'] : $_POST['submenu'] , 
                     $_POST['letra'], 
                     $_REQUEST['opcao']
                    );




// ************* CADASTRO GERAIS ******************* \\
//echo 'submenu-'.$_POST['submenu'].'|opcao='.$_POST['opcao'].'|aba='.$_POST['aba'];
if (isset($_POST['filial'])) { $conta->setCentroCusto($_POST['filial']); } else {$conta->setCentroCusto('');};
if (isset($_REQUEST['id'])) { $conta->setId($_REQUEST['id']); } else {$conta->setId('');};
if (isset($_POST['nome'])) { $conta->setNome($_POST['nome']); } else {$conta->setNome('');};
if (isset($_POST['nomeReduzido'])) { $conta->setNomeReduzido($_POST['nomeReduzido']); } else {$conta->setNomeReduzido('');};
if (isset($_POST['pessoa'])) { $conta->setPessoa($_POST['pessoa']); } else {$conta->setPessoa('');};
if (isset($_POST['cnpjCpf'])) { $conta->setCnpjCpf($_POST['cnpjCpf']); } else {$conta->setCnpjCpf('');};
if (isset($_POST['dataNascimento'])) { $conta->setDataNascimento($_POST['dataNascimento']); } else {$conta->setDataNascimento(NULL);};
if (isset($_POST['ieRg'])) { $conta->setIeRg($_POST['ieRg']); } else {$conta->setIeRg('');};
if (isset($_POST['im'])) { $conta->setIm($_POST['im']); } else {$conta->setIm('');};
if (isset($_POST['cep'])) { $conta->setCep($_POST['cep']); } else {$conta->setCep('');};
if (isset($_POST['tipo'])) { $conta->setTipo($_POST['tipo']); } else {$conta->setTipo('');};
if (isset($_POST['titulo'])) { $conta->setTitulo($_POST['titulo']); } else {$conta->setTitulo('');};
if (isset($_POST['endereco'])) { $conta->setEndereco($_POST['endereco']); } else {$conta->setEndereco('');};
if (isset($_POST['numero'])) { $conta->setNumero($_POST['numero']); } else {$conta->setNumero('');};
if (isset($_POST['complemento'])) { $conta->setComplemento($_POST['complemento']); } else {$conta->setComplemento('');};
if (isset($_POST['bairro'])) { $conta->setBairro($_POST['bairro']); } else {$conta->setBairro('');};
if (isset($_POST['cidade'])) { $conta->setCidade($_POST['cidade']); } else {$conta->setCidade('');};
if (isset($_POST['codMunicipio'])) { $conta->setCodMunicipio($_POST['codMunicipio']); } else {$conta->setCodMunicipio('');};
if (isset($_POST['suframa'])) { $conta->setSuframa($_POST['suframa']); } else {$conta->setSuframa('');};
if (isset($_POST['estado'])) { $conta->setEstado($_POST['estado']); } else {$conta->setEstado('');};
if (isset($_POST['fone'])) { $conta->setFone($_POST['fone']); } else {$conta->setFone('');};
if (isset($_POST['celular'])) { $conta->setCelular($_POST['celular']); } else {$conta->setCelular('');};
if (isset($_POST['faxArea'])) { $conta->setFaxArea($_POST['faxArea']); } else {$conta->setFaxArea('');};
if (isset($_POST['faxNum'])) { $conta->setFaxNum($_POST['faxNum']); } else {$conta->setFaxNum('');};
if (isset($_POST['contato'])) { $conta->setContato($_POST['contato']); } else {$conta->setContato('');};
if (isset($_POST['email'])) { $conta->setEmail($_POST['email']); } else {$conta->setEmail('');};
if (isset($_POST['emailNfe'])) { $conta->setEmailNfe($_POST['emailNfe']); } else {$conta->setEmailNfe('');};
if (isset($_POST['homePage'])) { $conta->setHomePage($_POST['homePage']); } else {$conta->setHomePage('');};
if (isset($_POST['classe'])) { $conta->setClasse($_POST['classe']); } else {$conta->setClasse('');};
if (isset($_POST['atividade'])) { $conta->setAtividade($_POST['atividade']); } else {$conta->setAtividade('');};
if (isset($_POST['vendedor'])) { $conta->setRepresentante($_POST['vendedor']); } else {$conta->setRepresentante('');};
if (isset($_POST['userLogin'])) { $conta->setUserLogin($_POST['userLogin']); } else {$conta->setUserLogin('');};
if (isset($_POST['senhaLogin'])) { $conta->setSenhaLogin($_POST['senhaLogin']); } else {$conta->setSenhaLogin('');};
if (isset($_POST['limiteCredito'])) { $conta->setLimiteCredito($_POST['limiteCredito']); } else {$conta->setLimiteCredito('');};
if (isset($_POST['obs'])) { $conta->setObs($_POST['obs']); } else {$conta->setObs('');};
if (isset($_POST['transversal1'])) { $conta->setTransversal1($_POST['transversal1']); } else {$conta->setTransversal1('');};
if (isset($_POST['transversal2'])) { $conta->setTransversal2($_POST['transversal2']); } else {$conta->setTransversal2('');};
if (isset($_POST['referencia'])) { $conta->setReferencia($_POST['referencia']); } else {$conta->setReferencia('');};
if (isset($_POST['referencia'])) { $conta->setReferencia($_POST['referencia']); } else {$conta->setReferencia('');};

if (isset($_POST['regimeEspecialST'])) { $conta->setRegimeEspecialST($_POST['regimeEspecialST']); } else {$conta->setRegimeEspecialST('N');};
if (isset($_POST['regimeEspecialSTMsg'])) { $conta->setRegimeEspecialSTMsg($_POST['regimeEspecialSTMsg']); } else {$conta->setRegimeEspecialSTMsg('N');};
if (isset($_POST['regimeEspecialSTMT'])) { $conta->setRegimeEspecialSTMT($_POST['regimeEspecialSTMT']); } else {$conta->setRegimeEspecialSTMT('N');};
if (isset($_POST['contribuinteICMS'])) { $conta->setContribuinteICMS($_POST['contribuinteICMS']); } else {$conta->setContribuinteICMS('N');};
if (isset($_POST['consumidorFinal'])) { $conta->setConsumidorFinal($_POST['consumidorFinal']); } else {$conta->setConsumidorFinal('N');};
if (isset($_POST['regimeEspecialSTMTAliq'])) { $conta->setRegimeEspecialSTMTAliq($_POST['regimeEspecialSTMTAliq']); } else {$conta->setRegimeEspecialSTMTAliq('0');};
if (isset($_POST['regimeEspecialSTAliq'])) { $conta->setRegimeEspecialSTAliq($_POST['regimeEspecialSTAliq']); } else {$conta->setRegimeEspecialSTAliq('0');};

//$cnpjCpf = new ValidaCPFCNPJ('09112859000248');
//if ( $cnpjCpf->valida() ) {
//	echo 'CPF ou CNPJ válido'; // Retornará este valor
//} else {
//	echo 'CPF ou CNPJ Inválido';
//}
//echo "<BR>";
//echo $cnpjCpf->formata();

$conta->controle();
?>
