<?php
/**
 * @name      p_atendimento_nf
 * @version   4.3.1
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony
 * @date      17/03/2021
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/cat/c_atendimento.php");
require_once($dir . "/../../class/cat/c_atendimento_financeiro_tools.php");
require_once($dir . "/../../class/cat/c_atendimento_gera_nf_tools.php");
require_once($dir . "/../../forms/est/p_nfephp_40.php");
require_once($dir . "/../../forms/est/p_nfephp_imprime_danfe.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir."/../../class/crm/c_conta.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");



//Class p_atendimento_nf
Class p_atendimento_nf extends c_atendimento {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_par          = NULL;
 
    public  $smarty             = NULL;
    private $numDocto           = NULL;
    private $serieDocto         = NULL;
    private $m_descCondPgto     = NULL;
    public  $m_dadosFinanceiros = NULL;
    public  $m_dadosParcelas    = NULL;
    private $m_situacoesAtendimento  = NULL;


    

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //$parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];

        $this->m_letra = $parmPost['letra'];
        
        $this->m_par = explode("|", $this->m_letra);

        $this->numDocto = $parmPost['numDocto'];
        $this->serieDocto = $parmPost['serieDocto'];
        $this->m_descCondPgto = $parmPost['descCondPgto'];
        $this->m_dadosFinanceiros = $parmPost['dadosFinanceiros'];
        $this->m_dadosNf = $parmPost['dadosNf']; 
        $this->m_dadosParcelas = $parmPost['dadosParcelas'];     
        $this->m_situacoesAtendimento = $parmPost['situacoesAtendimento']; 
        
        $this->m_par_nf = explode("|", $this->m_dadosNf);
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Atendimento Financeiro");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setCondPgto(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');

                    
        if (isset($parmPost['pessoa'])):
            $this->setCliente($parmPost['pessoa']);
        else:    
            $this->setCliente('');
        endif;
        
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'lancAtendimentoFinanceiro':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')){
                    
                    $objAtendimentoFinTools = new c_atendimento_financeiro_tools();
                    $msg = $objAtendimentoFinTools->lancaParcelasFinanceiroAtendimento($this->m_dadosFinanceiros, $this->m_dadosParcelas);
                    if($msg == true){
                        // BUSCA SITFINALIZADO EM CAT_PARAMETROS
                        $consulta = new c_banco;
                        $consulta->setTab("CAT_PARAMETROS");
                        $sitFinalizado = $consulta->getField("SITFINALIZADO", "ID=2");
                        $consulta->close_connection();  
                        // ATUALIZA SITUACAO ATENDIMENTO p/ FINALIZADO ID = 10 
                        $this->updateField("CAT_SITUACAO_ID", $sitFinalizado, "CAT_ATENDIMENTO");
                        $msg = "Lançamento do Docto ".$this->numDocto." Origem 'OS' salvo.";
                        $tipoMsg = 'sucesso';
                    }else{
                        $msg = "Já existe Lançamento do Docto ".$this->numDocto." Origem 'OS'";
                        $tipoMsg = 'alerta';
                    }
                    
                    $this->desenhaCadastroAtendimentoFinanceiro($msg, $tipoMsg);
                }
            break;
           case 'lancAtendimentoNf':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    $objAtendimentoFinTools = new c_atendimento_financeiro_tools();
                    $lanc = $objAtendimentoFinTools->verifica_lancamento_financeiro($this->numDocto,'OS');
                    if(is_array($lanc)) {
                        $objAtendimentoNfTools = new c_atendimento_gera_nf_tools();
                        $idGerado = $objAtendimentoNfTools->gera_os_nf($this->m_dadosNf);
                        $objAtendimentoNfTools->valida_nf_auto($idGerado);
                        $objPecas = $this->select_pecas_atendimento();
                        $objAtendimentoNfTools->adiciona_produto_nf($idGerado, $objPecas, $this->m_dadosNf);
                        try{
                            $transaction = new c_banco();
                            $transaction->inicioTransacao($transaction->id_connection);
                            $result = $objAtendimentoNfTools->gera_xml_os($idGerado, $transaction->id_connection);
                            if ($result == '100'):                                
                                $transaction->commit($transaction->id_connection);

                                $objAtendimentoNfTools->adiciona_info_nf_obs_lancamento($idGerado);
                                
                                $printDanfe = new p_nfephp_imprime_danfe();
                                $printDanfe->printDanfe($idGerado, '', '', '', '', 'atendimento_nf');
                            else:    
                                $transaction->rollback($transaction->id_connection);    
                                $this->desenhaCadastroAtendimentoFinanceiro('Nota Fiscal Não Gerada, Identificador: '.$idGerado);
                            endif;
                        }catch(Error $e){
                            $transaction->rollback($transaction->id_connection);
                            $this->desenhaCadastroAtendimentoFinanceiro($e->getMessage().' Nota Fiscal Não Gerada, Identificador: '.$idGerado);
                        }
                    }else{
                        $this->desenhaCadastroAtendimentoFinanceiro('Faça o lançamento do Documento no Financeiro antes de cadastrar a Nota fiscal.', 'alerta');
                    }
                    
                }
                break;
            case 'financeiro':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    $this->desenhaCadastroAtendimentoFinanceiro();
                }
                break;
            case 'cadastrarNf':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    $this->desenhaCadastroAtendimentoFinanceiro();
                }
                break;
          
            default:
                if ($this->verificaDireitoUsuario('CatAtendimento', 'C')) {
                    $this->mostraAtendimentoFinanceiro();
                }
        }
    }

    

    function desenhaCadastroAtendimentoFinanceiro($mensagem = NULL, $tipoMsg = NULL) {

        $descCondPgto = "";
        $fin = [];

        // BUSCA SITFINALIZADO EM CAT_PARAMETROS
        $consulta = new c_banco;
        $consulta->setTab("CAT_PARAMETROS");
        $sitFinalizado = $consulta->getField("SITFINALIZADO", "ID=2");
        $consulta->close_connection();        

        // BUSCA DATA FECHAMENTO 
        $consulta = new c_banco;
        $consulta->setTab("CAT_ATENDIMENTO");
        $dataFechamento = $consulta->getField("DATAFECHATEND", "ID=".$this->getId());
        $consulta->close_connection();

        if($dataFechamento == '' ||  $dataFechamento == '0000-00-00 00:00:00'){
            // ATUALIZA DATA FECHAMENTO ATENDIMENTO p/ DATA ATUAL 
        $this->updateField("DATAFECHATEND", date("Y-m-d H:i:s"), "CAT_ATENDIMENTO");
        }
        
        $atendimento = $this->select_atendimento($this->getId());
        $this->setValorTotal($atendimento[0]['TOTALUTILIZADOPECAS']);  
        $this->setCliente($atendimento[0]['CLIENTE']);   
        $this->setSituacao($sitFinalizado);
        if($this->getCondPgto() == ''){
            $this->setCondPgto($atendimento[0]['CONDPGTO']);
        }

        
        
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        if($this->numDocto == ''){
            $this->numDocto = $this->getId();
        }
        $this->smarty->assign('numDocto', $this->numDocto);

        if($this->serieDocto == ''){
            $this->serieDocto = 'OS';
        }
        $this->smarty->assign('serieDocto', $this->serieDocto);
        $this->smarty->assign('cliente', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('clienteNome', $this->getClienteNome());
        endif;
        $this->smarty->assign('dataFechamento', date("d/m/Y"));
        $this->smarty->assign('total', $this->getValorTotal('F'));

        // BUSCA NOTA FISCAL 
        $objNotaFiscal = new c_nota_fiscal();
        $objNotaFiscal->setId($this->getId());
        $arrNf = $objNotaFiscal->select_nota_fiscal();

        $this->smarty->assign('serieDoctoNf', $arrNf[0]['SERIE']);
        $this->smarty->assign('numDoctoNf', $arrNf[0]['NUMERO']);
        $this->smarty->assign('modeloDocto', $arrNf[0]['MODELO']);
        $this->smarty->assign('linkNfe', strtolower($arrNf[0]['PATHDANFE']));
        
        //desabilita btn cadastrar Nf 
        
        if(is_array($arrNf)){
            $this->smarty->assign('disabledBtnNf', 'true');
        }

        // COMBOBOX CONDICAO PAGAMENTO
        
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            if ($this->getCondPgto()==$result[$i]['ID']):
                $descCondPgto = $result[$i]['DESCRICAO'];
            endif;
            $condPgto_ids[$i] = $result[$i]['ID'];
            $condPgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);                
        $this->smarty->assign('condPgto_id', $this->getCondPgto());

        if($descCondPgto == '' || $descCondPgto == null ){
            $consulta = new c_banco;
            $consulta->setTab("FAT_COND_PGTO");
            $descCondPgto = $consulta->getField("DESCRICAO", "ID=".$this->getCondPgto());
            $consulta->close_connection();
        }
        $this->smarty->assign('descCondPgto', $descCondPgto);

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT ID , DESCRICAO FROM CAT_SITUACAO ";
        $sql.= "WHERE ATIVO = '1'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids',   $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao', $this->getSituacao());    

        // COMBOBOX GENERO
        $consulta = new c_banco();
        $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero where (tipolancamento = 'P') ORDER BY descricao;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $genero_ids[$i] = $result[$i]['ID'];
            $genero_names[$i] = $result[$i]['ID']." - ".$result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('genero_ids', $genero_ids);
        $this->smarty->assign('genero_names', $genero_names);        
        $this->smarty->assign('genero_id', $this->getGenero());

        // COMBOBOX CONTA
        $consulta = new c_banco();
        $sql = "SELECT conta as id, nomeinterno as descricao FROM fin_conta where status ='A' ORDER BY nomeinterno;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $conta_ids[$i] = $result[$i]['ID'];
            $conta_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('conta_ids', $conta_ids);
        $this->smarty->assign('conta_names', $conta_names);

        // tipo documento
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoDoctoPgto')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $tipoDocto_ids[$i] = $result[$i]['ID'];
                $tipoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('tipoDocto_ids', $tipoDocto_ids);
        $this->smarty->assign('tipoDocto_names', $tipoDocto_names);
        $this->smarty->assign('tipoDocto_id', 'B');

        
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
        $this->smarty->assign('situacaoLanc_id', 'A');

        // ########## NATUREZA OPERACAO ##########
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where (tipo='S') order by id";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $natOperacao_ids[$i] = $result[$i]['ID'];
            $natOperacao_names[$i] = $result[$i]['ID']." - ".$result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natOperacao_ids', $natOperacao_ids);
        $this->smarty->assign('natOperacao_names', $natOperacao_names);


        // COMBOBOX CENTROCUSTO
        $consulta = new c_banco();
        $sql = "SELECT CentroCusto AS ID, DESCRICAO FROM fin_centro_custo ORDER BY CentroCusto";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $centroCusto_ids[$i] = $result[$i]['ID'];
            $centroCusto_names[$i] = $result[$i]['ID']." - ".$result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);
        $this->smarty->assign('centroCusto_id', $this->getCentroCusto());

        $id = $this->getId();
        if (!empty($id)){
            $lancPecas = $this->select_pecas_atendimento();
            $this->smarty->assign('lancPecas', $lancPecas);

            $lancServicos = $this->select_servicos_atendimento();
            $this->smarty->assign('lancServicos', $lancServicos);
        }        
        
        // CALCULA PARCELAS
        $objAtendimentoFinTools = new c_atendimento_financeiro_tools();
        $lanc = $objAtendimentoFinTools->verifica_lancamento_financeiro($this->numDocto, 'OS');
        if(is_array($lanc)){
            $this->smarty->assign('fin', $lanc);
        }else{
            $fin = $objAtendimentoFinTools->geraParcelasFinanceiro($descCondPgto, $this->getValorTotal('F'));
            $this->smarty->assign('fin', $fin);
        }

        $this->smarty->display('atendimento_cadastro_nf.tpl');
    }

    function mostraAtendimentoFinanceiro($mensagem=NULL) {

        $cliente = '';
        if ($this->m_letra !=''):
            $lanc = $this->select_atendimento_letra($this->m_letra, $this->m_situacoesAtendimento);
        endif;
        
        if($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[0]);

        if($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        }
        else $this->smarty->assign('dataFim', $this->m_par[1]);

        // pessoa
        if($this->m_par[2] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            $this->smarty->assign('pessoa', $this->m_par[2]);
            $this->smarty->assign('nome', $this->getClienteNome());
        }

        $this->smarty->assign('numAtendimento', $this->m_par[3]);

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT ID , DESCRICAO FROM CAT_SITUACAO ";
        $sql.= "WHERE ATIVO = '1'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacaoAtendimento_ids[$i] = $result[$i]['ID'];
            $situacaoAtendimento_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacaoAtendimento_ids',   $situacaoAtendimento_ids);
        $this->smarty->assign('situacaoAtendimento_names', $situacaoAtendimento_names);
        // SITUACAO ENCERRADO = 6
        if($this->m_situacoesAtendimento == ''){
            $this->smarty->assign('situacaoAtendimento_id', 6);
        }else{
            $parSit = explode("|", $this->m_situacoesAtendimento);
            $this->smarty->assign('situacaoAtendimento_id', $parSit);
        }
  
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('origem', $this->m_origem);

        
        $this->smarty->display('atendimento_nf_mostra.tpl');
            
           

    }
    
    
}

// Rotina principal - cria classe
$atendimento_nf = new p_atendimento_nf();

$atendimento_nf->controle();

