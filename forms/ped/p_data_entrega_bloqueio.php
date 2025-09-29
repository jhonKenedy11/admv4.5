<?php
/**
 * @package   astecv3
 * @name      p_data_entrega_bloqueio
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy<jhon.kened11@gmail.com>
 * @date      15/05/2023
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty3/libs/Smarty.class.php");
include_once($dir."/../../class/ped/c_data_entrega_bloqueio.php");
include_once($dir . "/../../class/crm/c_contas_acompanhamento.php");
include_once($dir."/../../bib/c_tools.php");

//Class p_fin_data_entrega_bloqueio
Class p_data_entrega_bloqueio extends c_data_entrega_bloqueio {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;





//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

    //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
    $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

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

      // dados para exportacao e relatorios
    $this->smarty->assign('titulo', "data_entrega_bloqueio");
    $this->smarty->assign('colVis', "[ 0, 1 ]"); 
    $this->smarty->assign('disableSort', "[ 2 ]"); 
    $this->smarty->assign('numLine', "25"); 

    // metodo SET dos dados do FORM para o TABLE
    // $occupation = $_GET['occupation'] ?? 'bricklayer';
    $this->setId(isset($parmGet['id']) ? $parmGet['id'] : '');
    $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
    $this->m_date_delivery_block = (isset($parmPost['date_delivery_block']) ? $parmPost['date_delivery_block'] : null);
    $this->m_action = (isset($parmPost['action']) ? $parmPost['action'] : null);
    //param da busca das datas bloqueadas para entrega - origem json objeto
    $this->end_date = (isset($parmPost['end_date']) ? $parmPost['end_date'] : null);
    $this->start_date = (isset($parmPost['start_date']) ? $parmPost['start_date'] : null);
    //consulta
    $this->query_date = (isset($parmGet['query_date']) ? $parmGet['query_date'] : null);
    
}

/**
 * <b> É responsavel para indicar para onde o sistema ira executar </b>
 * @name controle
 * @param VARCHAR submenu 
 * @return vazio
 */
function controle(){
    switch ($this->m_submenu){
        case 'date_delivery_block':
            if($this->m_action == 'insert'){
                $objAcomp = new c_contas_acompanhamento();
                $objAcomp->setPessoa($this->m_userid);
                $objAcomp->setIdPedido('999');
                $objAcomp->setDataContato($this->m_date_delivery_block);
                $objAcomp->setAcao('999');
                $objAcomp->setResultContato('entrega-bloqueada');
                $objAcomp->setVendedorAcomp($this->m_userid);
                $objAcomp->setProximoContato($this->m_date_delivery_block);
                $objAcomp->setVeiculo('B');
                $result = $objAcomp->incluiPessoaAcomp();

                $return = [];
                if($result == ''){ //vazio ocorreu tudo certo
                    array_push($return, 'Registro adicionado com sucesso!', '1', '"'.$this->m_date_delivery_block.'"');
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($return);
                }else{
                    array_push($return, 'Ocorreu um erro ao adicionar a data', '0');
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($return);
                }

            }elseif('delete'){
                $result = $this->blockedDataDelete($this->m_date_delivery_block);

                $return = [];
                if($result == true){ //vazio ocorreu tudo certo
                    array_push($return, 'Registro excluido com sucesso!', '1', '"'.$this->m_date_delivery_block.'"');
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($return);
                }else{
                    array_push($return, 'Ocorreu um erro ao excluir a data', '0');
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($return);
                }
            }
        die;
        case 'consulta_data':

            $result = $this->blockedDataSearch($this->query_date);

            if($result !== null){ //ocorreu tudo certo
                //set true pois utiliza outra consulta
                $result = true;
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result);
            }else{
                //set false pois utiliza outra consulta
                $result = false;
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result);
            }
        die;
        default:
          if ($this->verificaDireitoUsuario('pedAlteraDataBloqueio', 'C')){
            $this->mostraDataEntregaBloqueio('');
          }
    
    }

} // fim controle


/**
* <b> Listagem de todas as registro cadastrados de tabela data_entrega_bloqueio. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraDataEntregaBloqueio($mensagem){

  
    $lanc = $this->existeDataEntregaBloqueio();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('data_entrega_bloqueio_mostra.tpl');
	

} //fim mostradata_entrega_bloqueio
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$data_entrega_bloqueio = new p_data_entrega_bloqueio();

                              
$data_entrega_bloqueio->controle();
 
  
?>
