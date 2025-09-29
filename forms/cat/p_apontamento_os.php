<?php
/**
 * @package   astec
 * @name      p_apontamento_os
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Maárcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/06/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/cat/c_apontamento_os.php");
require_once($dir . "/../../class/cat/c_atendimento.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");

//Class p_apontamento_os
Class p_apontamento_os extends c_apontamento_os {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    public $smarty          = NULL;
    public $m_letra_apontamento    = NULL;
    private $m_situacoesAtendimento  = NULL;

    public $idServicos = NULL;

    public $idPecas = NULL;
    public $codProduto = NULL;
    public $qtdeUtilizada = NULL;
    public $totalUtilizado = NULL;

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

        // ajax
        $this->ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_pesq = $parmPost['pesq'];        

        $this->checkServ = $parmPost['checkServ'];
        $this->idServicos = $parmPost['idServicos'];
        $this->idPecas   = $parmPost['idPecas'];
        $this->codProduto = $parmPost['codProduto'];
        $this->qtdeUtilizada = $parmPost['qtdeUtilizada'];
        $this->totalUtilizado = $parmPost['totalUtilizado'];


        $this->m_letra = $parmPost['letra'];
        $this->m_letra_apontamento    = $parmPost['letra_apontamento'];
        $this->m_situacoesAtendimento = $parmPost['situacoesAtendimento'];

        $this->m_par = explode("|", $this->m_letra);
        $this->m_par_apontamento = explode("|", $this->m_letra_apontamento);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Apontamento de Ordem de Serviços");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7 ]");
        $this->smarty->assign('disableSort', "[ 7 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setAtendimentoId(isset($parmPost['atendimentoId']) ? $parmPost['atendimentoId'] : '');
        $this->setCliente(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setContato(isset($parmPost['contato']) ? $parmPost['contato'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setObsServicos(isset($parmPost['obsServicos']) ? $parmPost['obsServicos'] : '');
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
      
        $this->setIdServico(isset($parmPost['mCodServico'])  ? $parmPost['mCodServico'] : '');
        $this->setIdApontamento(isset($parmPost['mIdApontamento'])  ? $parmPost['mIdApontamento'] : '');
        
        $this->setIdUser(isset($parmPost['idUser']) ? $parmPost['idUser'] : $this->m_userid);
        $this->setData(isset($parmPost['mData']) ? $parmPost['mData'] : '');
        $this->setDataInicio(isset($parmPost['mDataInicio']) ? $parmPost['mDataInicio'] : '');
        $this->setDataFim(isset($parmPost['mDataFim']) ? $parmPost['mDataFim'] : '');
        $this->setTotalHoras(isset($parmPost['mTotalHoras']) ? $parmPost['mTotalHoras'] : '');
        $this->setDescricao(isset($parmPost['mDescricaoApontamento']) ? $parmPost['mDescricaoApontamento'] : '');
      

        
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
                if ($this->verificaDireitoUsuario('CatApontamento', 'I')) {
                    $this->desenhaCadastroApontamento();
                }
                break;            
            case 'alterar':
                if ($this->verificaDireitoUsuario('CatApontamento', 'A')) {
                    $idAtendimento = $this->getId();
                    $this->setAtendimentoId($idAtendimento);                    
                    if ($this->getId() > 0){
                        $this->desenhaCadastroApontamento();
                    }else{
                        $this->mostraApontamento('Atendimento não pode ser alterado.');
                    }                  
                }
                break;
            case 'inclui': // CONCLUIR
                if ($this->verificaDireitoUsuario('CatApontamento', 'A')) {                 
                    //$this->incluiAtendimento();
                    //$result = $this->getValorTotal();
                    $idAtendimento = $this->getId();
                    $objAtendimento = new c_atendimento();
                    $objAtendimento->setId($idAtendimento);
                    $objAtendimento->updateField("CAT_SITUACAO_ID", $this->getSituacao(), "CAT_ATENDIMENTO");
                    $this->mostraApontamento('Registro Salvo.');
                }
                break;
            case 'altera': // CONCLUIR
                if ($this->verificaDireitoUsuario('CatApontamento', 'A')) {   
                    $idAtendimento = $this->getId();
                    $objAtendimento = new c_atendimento();
                    $objAtendimento->setId($idAtendimento);
                    $objAtendimento->updateField("CAT_SITUACAO_ID", $this->getSituacao(), "CAT_ATENDIMENTO");              
                    //$this->alteraAtendimento();
                    //$result = $this->getValorTotal();
                    //$this->updateField("VALORTOTAL", $result, "CAT_ATENDIMENTO");
                    $this->mostraApontamento('Registro Salvo.');
                }
                break;
            case 'digita': //VOLTAR
                if ($this->verificaDireitoUsuario('CatApontamento', 'C')) {
                    $this->mostraApontamento('');
                }
                break;
            case 'cancela': // CANCELA
                if ($this->verificaDireitoUsuario('CatApontamento', 'E')) {
                    // 8 - CAT_SITUACAO  = CANCELADO
                   // $this->updateField("CAT_SITUACAO_ID", 8, "CAT_ATENDIMENTO");
                    $this->mostraApontamento('Atendimento Cacelado');              
                }
                break;       
            
            default:
                if ($this->verificaDireitoUsuario('CatApontamento', 'C')) {
                    $this->mostraApontamento('');
                }
        }
    }

    function desenhaCadastroApontamento($mensagem = NULL,$tipoMsg=NULL) {       
        

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);        

        $this->smarty->assign('id', $this->getId());

        $this->smarty->assign('checkServ', $this->checkServ);

         // ATUALIZA TODAS QTDE UTILIZADA 
         $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_TODOS_QTDEUTILIZADA_PECA"] == "true");
         if($_SERVER["HTTP_AJAX_REQUEST_TODOS_QTDEUTILIZADA_PECA"] == "true"):
            $ajax_request = 'true';
                       
            $lancPecas = $this->select_pecas_atendimento();
            for($i = 0; $i < count($lancPecas); $i++){
               $this->idPecas = $lancPecas[$i]['ID'];
               $this->codProduto = $lancPecas[$i]['CODPRODUTO'];
               $this->qtdeUtilizada = $lancPecas[$i]['QUANTIDADE'];
               $this->totalUtilizado = $lancPecas[$i]['VALORTOTAL'];

               $this->atualizaQtdeUtilizada();

            }
            $atendimento = $this->select_atendimento_id();

            $idAtendimento = $this->getId();
            $atendimentoObj = new c_atendimento();
            $atendimentoObj ->setId($idAtendimento);
            

            $totalAtendimento = ($atendimento[0]['VALORSERVICOS'] + $atendimento[0]['VALORPECAS'] + $atendimento[0]['VALORVISITA']) - $atendimento[0]['VALORDESCONTO'];

            $atendimentoObj->updateField("VALORUTILIZADOPECAS", $atendimento[0]['VALORPECAS'], "CAT_ATENDIMENTO");

            $atendimentoObj->updateField("TOTALUTILIZADOPECAS", $totalAtendimento, "CAT_ATENDIMENTO");

            $lancPecas = $this->select_pecas_atendimento();
            $this->smarty->assign('lancPecas', $lancPecas);
         else:
             $ajax_request = 'false';
             $this->smarty->assign('ajax', $ajax_request);  
         endif;

         // ATUALIZA A QTDE UTILIZADA 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_ADD_QTDEUTILIZADA_PECA"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_ADD_QTDEUTILIZADA_PECA"] == "true"):
            $ajax_request = 'true';

            $atendimento = $this->select_atendimento_id();
            $this->totalUtilizado = formataBdVlr($this->totalUtilizado);
            $this->atualizaQtdeUtilizada();

            //Soma novo Total peças 
            $pecas = $this->select_pecas_atendimento();
            $novoTotalPecas = $this->totalUtilizado;
            for($i = 0; $i < count($pecas); $i++){
                if($pecas[$i]['ID'] != $this->idPecas){
                    $novoTotalPecas += $pecas[$i]['VALORTOTAL'];
                }
            }
            
            $idAtendimento = $this->getId();
            $atendimentoObj = new c_atendimento();
            $atendimentoObj ->setId($idAtendimento);
            $atendimentoObj->updateField("VALORUTILIZADOPECAS", $novoTotalPecas, "CAT_ATENDIMENTO");

            $totalAtendimento = ($atendimento[0]['VALORSERVICOS'] + $novoTotalPecas + $atendimento[0]['VALORVISITA']) - $atendimento[0]['VALORDESCONTO']; 
            
            $atendimentoObj->updateField("TOTALUTILIZADOPECAS", $totalAtendimento, "CAT_ATENDIMENTO");
            $lancPecas = $this->select_pecas_atendimento();
            $this->smarty->assign('lancPecas', $lancPecas);
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);  
        endif;         

        // PESQUISA APONTAMENTOS SERVICO 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_PESQ_AP_SERV"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_PESQ_AP_SERV"] == "true"):
            $ajax_request = 'true';
            if($this->checkServ == 'true'){
                $lancItens = $this->select_apontamento_servico();
            }else{
                $lancItens = $this->select_apontamento();
            }
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);  
        endif;
        
        // CADASTRA/ALTERA APONTAMENTO 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_APONTAMENTO"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_APONTAMENTO"] == "true"):
            $ajax_request = 'true';

            // VERIFICA SE NAO TEM ID APONTAMENTO
            if(empty($this->getIdApontamento())){
                $this->incluiApontamento();                  
            }else{
                $this->alteraApontamento();
            }
            
            $lancItens = $this->select_apontamento();
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);  
        endif; 
        // EXCLUI APONTAMENTO
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_APONTAMENTO"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_APONTAMENTO"] == "true"):
            $ajax_request = 'true';
            $this->excluiApontamento($this->m_letra_apontamento);

            $lancItens = $this->select_apontamento();
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif; 

        if ($this->getAtendimentoId()!=''):
            $atendimento = $this->select_atendimento_id();
            $this->setCliente($atendimento[0]['CLIENTE']);
            $this->setContato($atendimento[0]['CONTATO']);
            $this->setObs($atendimento[0]['OBS']);
            $this->setObsServicos($atendimento[0]['OBSSERVICO']);
            $this->smarty->assign('pessoa', $this->getCliente());
            if ($this->getCliente()!=''):
                $this->setClienteNome();
                $this->smarty->assign('nome', $this->getClienteNome());
            endif;
            $this->smarty->assign('contato', $this->getContato());
            $this->smarty->assign('atendimentoId', $this->getAtendimentoId());
            $this->smarty->assign('obs', $this->getObs());
            $this->smarty->assign('obsServicos', $this->getObsServicos());

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
            if($this->getSituacao() == ''){
                $this->smarty->assign('situacao', $atendimento[0]['CAT_SITUACAO_ID']);   
            }else{
                $this->smarty->assign('situacao', $this->getSituacao());      
            }    

            $vlrPecas = number_format($atendimento[0]['VALORPECAS'], 2, ',', '.');
            $this->smarty->assign('valorPecas', $vlrPecas);

            if($atendimento[0]['VALORUTILIZADOPECAS'] == NULL){
                $vlrPecasUtilizado = '';
            }else{
                $vlrPecasUtilizado = number_format($atendimento[0]['VALORUTILIZADOPECAS'], 2, ',', '.');
            }
            $this->smarty->assign('valorPecasUtilizado', $vlrPecasUtilizado);

            if($atendimento[0]['TOTALUTILIZADOPECAS'] == NULL){
                $totalPecasUtilizado = '';
            }else{
                $totalPecasUtilizado = number_format($atendimento[0]['TOTALUTILIZADOPECAS'], 2, ',', '.');
            }
            $this->smarty->assign('totalPecasUtilizado', $totalPecasUtilizado);
            
            $vlrServicos = number_format($atendimento[0]['VALORSERVICOS'], 2, ',', '.');
            $this->smarty->assign('valorServicos', $vlrServicos);
            
            $vlrVisita = number_format($atendimento[0]['VALORVISITA'], 2, ',', '.');
            $this->smarty->assign('valorVisita', $vlrVisita); 
            
            $vlrDesconto = number_format($atendimento[0]['VALORDESCONTO'], 2, ',', '.');
            $this->smarty->assign('valorDesconto', $vlrDesconto); 
            
            $vlrTotal = number_format($atendimento[0]['VALORTOTAL'], 2, ',', '.');
            $this->smarty->assign('valorTotal', $vlrTotal); 


            // COMBOBOX ATENDENTE
            $consulta = new c_banco();
            $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
            $sql.= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' )";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            for ($i = 0; $i < count($result); $i++) {
                $usr_ids[$i + 1] = $result[$i]['USUARIO'];
                $usr_names[$i] = $result[$i]['NOME'];
            }
            $this->smarty->assign('usr_ids',   $usr_ids);
            $this->smarty->assign('usr_names', $usr_names);
            if($this->getIdUser() == ''){
                $this->setIdUser($this->m_userid);
            }
            $this->smarty->assign('usr', $this->getIdUser());

            $lancPecas = $this->select_pecas_atendimento();
            $this->smarty->assign('lancPecas', $lancPecas);

            $lancServicos = $this->select_servicos_atendimento();
            $this->smarty->assign('lancServicos', $lancServicos); 
            
            if($this->checkServ == 'true'){
                $lancItens = $this->select_apontamento_servico();
            }else{
                $lancItens = $this->select_apontamento();
            }
            $this->smarty->assign('lancItens', $lancItens);
       
        endif;


        $this->smarty->display('apontamento_os_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraApontamento($mensagem=NULL) {

        $cliente = '';
        if ($this->m_letra !=''):
            $objAtendimento = new c_atendimento();
            $lanc = $objAtendimento->select_atendimento_letra($this->m_letra, $this->m_situacoesAtendimento);
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
        if($this->m_situacoesAtendimento == ''){
            $this->smarty->assign('situacaoAtendimento_id', 2);
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

        $this->smarty->display('apontamento_os_mostra.tpl');
    }
}
function formataBdVlr($vlr){
    //formatação vlr 
    if(strlen($vlr) > 6){
        $number = explode(",", ($vlr));
        $newNumber = str_replace('.', '', $vlr);
        $vlrBd = $newNumber.".".$number[1];
    }else{
        $vlrBd = str_replace(',', '.',$vlr);
    }
    return $vlrBd;
}
function setTotalGeral($vlrPecas, $vlrServicos, $vlrVisita, $vlrDesconto){
    $pecas    = formataBdVlr($vlrPecas); 
    $servicos = formataBdVlr($vlrServicos); 
    $visita =   formataBdVlr($vlrVisita);
    $desconto = formataBdVlr($vlrDesconto);

    $totalGeral = (($pecas+$servicos+$visita) - $desconto);

    return $totalGeral;
}
// Rotina principal - cria classe
$atendimento = new p_apontamento_os();

$atendimento->controle();

