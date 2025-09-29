<?php
/**
 * @package   adm
 * @name      p_cliente_endereco
 * @version   4.5.00
 * @copyright 2020
 * @link      http://www.admsistema.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admsistema.com.br>
 * @date      01/09/2020
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty3/libs/Smarty.class.php");
include_once($dir."/../../class/crm/c_conta.php");
include_once($dir."/../../bib/c_tools.php");

//Class p_fin_banco
Class p_cliente_endereco extends c_conta {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;
private $m_id_cliente = NULL;


//---------------------------------------------------------------
//---------------------------------------------------------------
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
    $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
    $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

    $this->m_id_endereco = isset($parmGet['id_endereco']) !== '' ? $parmGet['id_endereco'] : '';
    $this->m_id_cliente = isset($parmPost['id_cliente']) ? $parmPost['id_cliente'] : ($parmGet['id_cliente'] ? $parmGet['id_cliente'] : null);

    $this->m_address_status = isset($parmPost['address_status']) !== '' ? $parmPost['address_status'] : null;
    $this->m_address_descricao = isset($parmPost['address_descricao']) !== '' ? $parmPost['address_descricao'] : null;
    $this->m_address_ddd = isset($parmPost['address_ddd']) !== '' ? $parmPost['address_ddd'] : null;
    $this->m_address_fone = isset($parmPost['address_fone']) !== '' ? $parmPost['address_fone'] : null;
    $this->m_address_fone_contato = isset($parmPost['address_fone_contato']) !== '' ? $parmPost['address_fone_contato'] : null;
    $this->m_address_cep = isset($parmPost['address_cep']) !== '' ? $parmPost['address_cep'] : null;
    $this->m_address_endereco = isset($parmPost['address_endereco']) !== '' ? $parmPost['address_endereco'] : null;
    $this->m_address_numero = isset($parmPost['address_numero']) !== '' ? $parmPost['address_numero'] : null;
    $this->m_address_complemento = isset($parmPost['address_complemento']) !== '' ? $parmPost['address_complemento'] : null;
    $this->m_address_bairro = isset($parmPost['address_bairro']) !== '' ? $parmPost['address_bairro'] : null;
    $this->m_address_estado = isset($parmPost['address_estado']) !== '' ? $parmPost['address_estado'] : null;
    $this->m_parm_post = isset($parmPost) !== '' ? $parmPost : null;


    // caminhos absolutos para todos os diretorios biblioteca e sistema
    $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
    $this->smarty->assign('bootstrap', ADMbootstrap);
    $this->smarty->assign('raizCliente', $this->raizCliente);

      // dados para exportacao e relatorios
    $this->smarty->assign('titulo', "Endereco Cliente");
    $this->smarty->assign('colVis', "[ 0, 1 ]"); 
    $this->smarty->assign('disableSort', "[ 2 ]"); 
    $this->smarty->assign('numLine', "25"); 
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
            $this->desenhaCadastroBanco();
        break;
        case 'inserir':
            $result = $this->insert_address($this->m_parm_post);
            $return = $result ? $result : $result." Erro ao adicionar o endereço!";

            header('Content-type: application/json');
            echo json_encode($result, JSON_FORCE_OBJECT);
        die;
        default:
            $this->mostraClienteEndereco('');
    }

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Banco. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroBanco($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('id_cliente', $this->m_id_cliente);

    $this->smarty->assign('address_status', null);
    $this->smarty->assign('address_descricao', null);
    $this->smarty->assign('address_ddd', null);
    $this->smarty->assign('address_fone', '');
    $this->smarty->assign('address_fone_contato', '');
    $this->smarty->assign('address_cep', '');
    $this->smarty->assign('address_endereco', '');
    $this->smarty->assign('address_numero', '');
    $this->smarty->assign('address_complemento', '');
    $this->smarty->assign('address_bairro', '');
    $this->smarty->assign('address_estado', '');

    // estado
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    $address_estado_ids[0] = '0';
    $address_estado_names[0] = 'Selecione';
    for (
      $i = 0;
      $i < count($result);
      $i++
    ) {
      $address_estado_ids[$i + 1] = $result[$i]['ID'];
      $address_estado_names[$i + 1] = $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('address_estado_ids', $address_estado_ids);
    $this->smarty->assign('address_estado_names', $address_estado_names);
    //$this->smarty->assign('estado_id', $this->getEstado());

    
    $this->smarty->display('cliente_endereco_cadastro.tpl');
    
}//fim desenhaCadastroBanco

/**
* <b> Listagem de todas as registro cadastrados de tabela banco. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraClienteEndereco(){

    // COMBOBOX ENDERECO ENTREGA
    $consulta = new c_banco();
    $sql = "SELECT * FROM FIN_CLIENTE_ENDERECO ";
    $sql .= "WHERE CLIENTE ='". $this->m_id_cliente ."' AND STATUS = 'A';";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $lanc = $consulta->resultado;

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);
    $this->smarty->assign('id_cliente', $this->m_id_cliente);


    $this->smarty->display('cliente_endereco_mostra.tpl');
	

} //fim mostraClienteEndereco
//-------------------------------------------------------------


}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$cliente_endereco = new p_cliente_endereco();

$cliente_endereco->controle();
 
  
?>
