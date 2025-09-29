<?php
/**
 * @package   astecv3
 * @name      p_nat_operacao
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date     15/10/2016
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/est/c_nat_operacao.php");
include_once($dir."/../../class/crm/c_conta.php");

//Class p_nat_operacao_pag
Class p_nat_operacao extends c_nat_operacao {

private $m_submenu = NULL;
private $m_opcao = NULL;
private $m_letra = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

    //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
    $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    //// $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);
        
     // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);

    // Cria uma instancia do Smarty
    $this->smarty = new Smarty;

    // caminhos absolutos para todos os diretorios do Smarty
    $this->smarty->template_dir = ADMraizFonte . "/template/est";
    $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
    $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
    $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

    // inicializa variaveis de controle
    $this->m_submenu = $submenu;
    $this->m_opcao = $opcao;
    $this->m_letra = $letra;
    $this->m_par = explode("|", $this->m_letra);

    // caminhos absolutos para todos os diretorios biblioteca e sistema
    $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
    $this->smarty->assign('bootstrap', ADMbootstrap);
    $this->smarty->assign('raizCliente', $this->raizCliente);
    $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');


    // dados para exportacao e relatorios
    if ($this->m_opcao=="pesquisar"){
        $this->smarty->assign('titulo', "Natureza Operação");
        $this->smarty->assign('colVis', "[ 0, 1, 2 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 
    }
    else{
        $this->smarty->assign('titulo', "Natureza Operação");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3 ]"); 
        $this->smarty->assign('disableSort', "[ 3 ]"); 
        $this->smarty->assign('numLine', "25"); 

    }
        
        // metodo SET dos dados do FORM para o TABLE
    $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
    $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
    $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
				
    $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
    $this->setNatOperacao(isset($parmPost['natOperacao']) ? $parmPost['natOperacao'] : '');
    $this->setTipo(isset($parmPost['tipo']) ? $parmPost['tipo'] : '');
    $this->setCodFiscOrigem(isset($parmPost['codFiscOrigem']) ? $parmPost['codFiscOrigem'] : '');
    $this->setUsrMensagem(isset($parmPost['usrMensgaem']) ? $parmPost['usrMensgaem'] : 0);
    $this->setCompoeCredito(isset($parmPost['compoeCredito']) ? $parmPost['compoeCredito'] : 'N');
    $this->setAlteraQuant(isset($parmPost['alteraQuant']) ? $parmPost['alteraQuant'] : 'S');
    $this->setIntegraFin(isset($parmPost['integraFin']) ? $parmPost['integraFin'] : 'S');
    $this->setPosicaoTributos(isset($parmPost['posicaoTributos']) ? $parmPost['posicaoTributos'] : 'S');
    $this->setAlteraPrecos(isset($parmPost['alteraPrecos']) ? $parmPost['alteraPrecos'] : 'S');
    $this->setTribSimples(isset($parmPost['tribSimples']) ? $parmPost['tribSimples'] : '');
    $this->setPercCreditoSimples(isset($parmPost['percCreditoSimples']) ? $parmPost['percCreditoSimples'] : '');
    $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
    $this->setDescCompleta(isset($parmPost['descCompleta']) ? $parmPost['descCompleta'] : '');
    $this->setUtilizacao(isset($parmPost['utilizacao']) ? $parmPost['utilizacao'] : '');
    $this->setNfAuto(isset($parmPost['nfAuto']) ? $parmPost['nfAuto'] : '');
    $this->setModeloNf(isset($parmPost['modeloNf']) ? $parmPost['modeloNf'] : '');
    

    // include do javascript
    // include ADMjs . "/est/s_est.js";

}

/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
function controle(){
  switch ($this->m_submenu){
        case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstNatOperacao', 'I')){
                        $this->desenhaCadastroNatOperacao();
                }
                break;
        case 'alterar':
                if ($this->verificaDireitoUsuario('EstNatOperacao', 'A')){
                        $nat_operacao = $this->selectNatOperacao();
                        $this->setId($nat_operacao[0]['ID']);
                        $this->setNatOperacao($nat_operacao[0]['NATOPERACAO']);
                        $this->setTipo($nat_operacao[0]['TIPO']);
                        $this->setCodFiscOrigem($nat_operacao[0]['CODFISCORIGEM']);
                        $this->setUsrMensagem($nat_operacao[0]['USRMENSSAGEM']);
                        $this->setCompoeCredito($nat_operacao[0]['COMPOECREDITO']);
                        $this->setAlteraQuant($nat_operacao[0]['ALTERAQUANT']);
                        $this->setIntegraFin($nat_operacao[0]['INTEGRAFIN']);
                        $this->setPosicaoTributos($nat_operacao[0]['POSICAOTRIBUTOS']);
                        $this->setAlteraPrecos($nat_operacao[0]['ALTERAPRECOS']);
                        $this->setTribSimples($nat_operacao[0]['TRIBSIMPLES']);
                        $this->setPercCreditoSimples($nat_operacao[0]['PRECCREDITOSIMPLES']);
                        $this->setObs($nat_operacao[0]['OBS']);
                        $this->setDescCompleta($nat_operacao[0]['DESCCOMPLETA']);
                        $this->setUtilizacao($nat_operacao[0]['UTILIZACAO']);
                        $this->setNfAuto($nat_operacao[0]['NFAUTO']);
                        $this->setModeloNf($nat_operacao[0]['MODELONF']);
                        $this->desenhaCadastroNatOperacao();
                } 
                break;
        case 'inclui':
                if ($this->verificaDireitoUsuario('EstNatOperacao', 'I')){
                        if ($this->existeNatOperacao()){
                                $this->m_submenu = "cadastrar";
                                $this->desenhaCadastroNatOperacao("CÓDIGO FISCAL JÁ EXISTENTE, ALTERE O NÚMERO DO GENERO");}
                        else {
                                $this->mostraNatOperacao($this->incluiNatOperacao());}
                }		
                break;
        case 'altera':
                if ($this->verificaDireitoUsuario('EstNatOperacao', 'A')){
                        $this->mostraNatOperacao($this->alteraNatOperacao());
                }
                break;
        case 'exclui':
                if ($this->verificaDireitoUsuario('EstNatOperacao', 'E')){
                    $this->mostraNatOperacao($this->excluiNatOperacao());
                }
                break;
        case 'validaNaturezaOperacao':
                $expLetra = explode('|',$this->m_letra);

                $objDesti = new c_conta();
                $objDesti->setId($expLetra[0]);
                $arrayCliente = $objDesti->select_conta();
                
                $expEmpresaEmitente = explode('|',  ADMnfeConfig01);
                $UfEmitente = $expEmpresaEmitente[3];

                $this->setId($expLetra[1]);
                $natOp = $this->selectNatOperacao();
                $natOp = trim($natOp[0]["NATOPERACAO"]);

                switch($natOp){
                    case 'VENDA DENTRO DO ESTADO':
                        if($arrayCliente[0]['UF'] !== $UfEmitente){
                            $return = 'Natureza de operação diverge da UF para venda DENTRO DO ESTADO!';
                        }else{
                             $return = null;  
                        }
                        break;
                    case 'VENDA FORA DO ESTADO':
                        if($arrayCliente[0]['UF'] == $UfEmitente){
                            $return = 'Natureza de operação diverge da UF para venda FORA DO ESTADO!';
                        }else{
                            $return = null;  
                        }
                        break;
                    default:
                        $return = null;
                }
                header('Content-Type: application/json'); 
                echo json_encode($return, JSON_UNESCAPED_UNICODE);
            break;
        default:
                if ($this->verificaDireitoUsuario('EstNatOperacao', 'C')){
                        $this->mostraNatOperacao('');
                }
    }

} // fim controle


 /**
 * <b> Desenha form de cadastro ou alteração NatOperacao. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroNatOperacao($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('natOperacao', "'".$this->getNatOperacao()."'");
    $this->smarty->assign('tipo', $this->getTipo());
    $this->smarty->assign('codFiscOrigem', $this->getCodFiscOrigem());
    $this->smarty->assign('usrMensagem', $this->getUsrMensagem());//
    $this->smarty->assign('compoeCredito', $this->getCompoeCredito());//
    $this->smarty->assign('alteraQuant', $this->getAlteraQuant());
    $this->smarty->assign('integraFin', $this->getIntegraFin());
    $this->smarty->assign('posicaoTributos', $this->getPosicaoTributos());
    $this->smarty->assign('alteraPrecos', $this->getAlteraPrecos());
    $this->smarty->assign('tribSimples', $this->getTribSimples('F'));
    $this->smarty->assign('percCreditoSimples', $this->getPercCreditoSimples('F'));
    $this->smarty->assign('obs', $this->getObs()."");
    $this->smarty->assign('descCompleta', $this->getDescCompleta()."");
    $this->smarty->assign('utilizacao', $this->getUtilizacao()."");
    $this->smarty->assign('nfAuto', $this->getNfAuto()."");
    $this->smarty->assign('modeloNf', $this->getModeloNf()."");
					    
   
    // tipo Nat Operacao##############################
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TipoNatOp')";
    $consulta = new c_banco();
    
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $tipoNatOp_ids[$i] = $result[$i]['ID'];
            $tipoNatOp_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('tipoNatOp_ids', $tipoNatOp_ids);
    $this->smarty->assign('tipoNatOp_names', $tipoNatOp_names);
    $this->smarty->assign('tipoNatOp_id', $this->getTipo());		

    // BOOLEAN ##############################
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
    
    $this->smarty->display('nat_operacao_cadastro.tpl');
    
}//fim desenhaCadastroNatOp

/**
* <b> Listagem de todas as registro cadastrados de tabela nat_operacao. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraNatOperacao($mensagem){

    $lanc = $this->selectNatOperacaoGeral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('opcao', $this->m_opcao);
    $this->smarty->assign('lanc', $lanc);

    if ($this->m_opcao=="pesquisar"){
        $this->smarty->display('nat_operacao_pesquisar.tpl');}
    else{
        $this->smarty->display('nat_operacao_mostra.tpl');}

} //fim mostraNatOperacao
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$nat_operacao = new p_nat_operacao();

$nat_operacao->controle();
 
  
?>
