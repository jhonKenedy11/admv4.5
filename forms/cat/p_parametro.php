<?php
/**
 * @package   admv4.3.2
 * @name      p_parametros
 * @version   4.3.20
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      04/06/2021
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/cat/c_parametro.php");
include_once($dir."/../../bib/c_tools.php");

//Class p_cat_parametros
Class p_parametros extends c_parametros {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

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
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

          // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Parametros");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        // $occupation = $_GET['occupation'] ?? 'bricklayer';
        $this->__set('id', $parmPost['id'] ?? '');
        $this->__set('situacaoinclusao', $parmPost['situacaoinclusao'] ?? '');
        $this->__set('sitagatendimento', $parmPost['sitagatendimento'] ?? '');
        $this->__set('sitematendimento', $parmPost['sitematendimento'] ?? '');
        $this->__set('sitsolicitarpeca', $parmPost['sitsolicitarpeca'] ?? '');
        $this->__set('sitagpeca', $parmPost['sitagpeca'] ?? '');
        $this->__set('sitpecarecebida', $parmPost['sitpecarecebida'] ?? '');
        $this->__set('sitaporcamento', $parmPost['sitaporcamento'] ?? '');
        $this->__set('sitfinalizado', $parmPost['sitfinalizado'] ?? '');
        //$this->__set('localatendimento', $parmPost['localatendimento'] ?? '');
        //$this->__set('tipointervencao', $parmPost['tipointervencao'] ?? '');
        $this->__set('msgatendimento', $parmPost['msgatendimento'] ?? '');
        $this->__set('msgorcamento', $parmPost['msgorcamento'] ?? '');
        //$this->__set('controleestoque', $parmPost['controleestoque'] ?? '');
        //$this->__set('tipodoccobranca', $parmPost['tipodoccobranca'] ?? '');
        $this->__set('condpgto', $parmPost['condpgto'] ?? '');
        $this->__set('conta', $parmPost['conta'] ?? '');
        $this->__set('genero', $parmPost['genero'] ?? '');
        $this->__set('centrocusto', $parmPost['centrocusto'] ?? '');
        $this->__set('created_user', $parmPost['created_user'] ?? '');
        $this->__set('updated_user', $parmPost['updated_user'] ?? '');
        $this->__set('created_at', $parmPost['created_at'] ?? '');
        $this->__set('updated_at', $parmPost['updated_at'] ?? '');


        // include do javascript
        // include ADMjs . "/fin/s_parametro.js";

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
			//if ($this->verificaDireitoUsuario('CatParametros', 'I')){
				$this->desenhaCadastroParametros();
			//}
			break;
		case 'alterar':
			//if ($this->verificaDireitoUsuario('CatParametros', 'A')){
				$cat_parametros = $this->select_parametros();
				$this->__set('id', $cat_parametros[0]['ID']);
	      $this->__set('situacaoinclusao', $cat_parametros[0]['SITUACAOINCLUSAO']);
	      $this->__set('sitagatendimento', $cat_parametros[0]['SITAGATENDIMENTO']);
	      $this->__set('sitematendimento', $cat_parametros[0]['SITEMATENDIMENTO']);
	      $this->__set('sitsolicitarpeca', $cat_parametros[0]['SITSOLICITARPECA']);
	      $this->__set('sitagpeca', $cat_parametros[0]['SITAGPECA']);
	      $this->__set('sitpecarecebida', $cat_parametros[0]['SITPECARECEBIDA']);
	      $this->__set('sitaporcamento', $cat_parametros[0]['SITAPORCAMENTO']);
	      $this->__set('sitfinalizado', $cat_parametros[0]['SITFINALIZADO']);
	      //$this->__set('localatendimento', $cat_parametros[0]['LOCALATENDIMENTO']);
	      //$this->__set('tipointervencao', $cat_parametros[0]['TIPOINTERVENCAO']);
	      $this->__set('msgatendimento', $cat_parametros[0]['MSGATENDIMENTO']);
	      $this->__set('msgorcamento', $cat_parametros[0]['MSGORCAMENTO']);
	      //$this->__set('controleestoque', $cat_parametros[0]['CONTROLEESTOQUE']);
	      //$this->__set('tipodoccobranca', $cat_parametros[0]['TIPODOCCOBRANCA']);
	      $this->__set('condpgto', $cat_parametros[0]['CONDPGTO']);
	      $this->__set('conta', $cat_parametros[0]['CONTA']);
	      $this->__set('genero', $cat_parametros[0]['GENERO']);
	      $this->__set('centrocusto', $cat_parametros[0]['CENTROCUSTO']);
	      $this->__set('updated_user', $cat_parametros[0]['UPDATED_USER']);
	      $this->__set('updated_at', $cat_parametros[0]['UPDATED_AT']);
				$this->desenhaCadastroParametros();
      //}
			break;
		case 'inclui':
			//if ($this->verificaDireitoUsuario('CatParametros', 'I')){
				    if ($this->existeParametros()){
				    	  $this->m_submenu = "cadastrar";
   			    		$this->desenhaCadastroParametros('PAR&Acirc;METRO J&Aacute; EXISTENTE, ALTERE O C&Oacute;DIGO DO PAR&Acirc;METRO'.$msg, 'error');
              }
				    else {
                if($this->incluiParametros()){
                  $this->mostraParametros('Cadastro realizado com sucesso!'.$msg, 'sucesso');
                }else{
                  $this->mostraParametros('Cadastro n&atilde;o realizado'.$msg, 'error');
                }
            }
		//}		
			break;
		case 'altera':
			//if ($this->verificaDireitoUsuario('CatParametros', 'A')){
            if($this->alteraParametros()){
                $this->mostraParametros('Dados alterados com sucesso!'.$msg, 'sucesso');
            }else{
                $this->mostraParametros('Os dados do registro n&atilde;o foram alterados!'.$msg, 'error'); 
            }
			//}
			break;
		case 'exclui':
			//if ($this->verificaDireitoUsuario('CatParametros', 'E')){
            if($this->excluiParametros()){
              $this->mostraParametros('Registro exclu&iacute;do com sucesso!'.$msg, 'sucesso');
            }else{
              $this->mostraParametros('O registro n&atilde;o foi exclu&iacute;do!'.$msg, 'error');
            }
		//}
			break;
		default:
  		//if ($this->verificaDireitoUsuario('CatParametros', 'C')){
				$this->mostraParametros('');
  		//}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Parametros. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroParametros($mensagem=NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    
    $this->smarty->assign('id', $this->__get('id'));
    $this->smarty->assign('situacaoinclusao', $this->__get('situacaoinclusao'));
    $this->smarty->assign('sitagatendimento', $this->__get('sitagatendimento'));
    $this->smarty->assign('sitematendimento', $this->__get('sitematendimento'));
    $this->smarty->assign('sitsolicitarpeca', $this->__get('sitsolicitarpeca'));
    $this->smarty->assign('sitagpeca', $this->__get('sitagpeca'));
    $this->smarty->assign('sitpecarecebida', $this->__get('sitpecarecebida'));
    $this->smarty->assign('sitaporcamento', $this->__get('sitaporcamento'));
    $this->smarty->assign('sitfinalizado', $this->__get('sitfinalizado'));
    //$this->smarty->assign('localatendimento', $this->__get('localatendimento'));
    //$this->smarty->assign('tipointervencao', $this->__get('tipointervencao'));
    $this->smarty->assign('msgatendimento', $this->__get('msgatendimento'));
    $this->smarty->assign('msgorcamento', $this->__get('msgorcamento'));
    $this->smarty->assign('controleestoque', $this->__get('controleestoque'));
    //$this->smarty->assign('tipodoccobranca', $this->__get('tipodoccobranca'));
    $this->smarty->assign('condpgto', $this->__get('condpgto'));
    $this->smarty->assign('conta', $this->__get('conta'));
    $this->smarty->assign('genero', $this->__get('genero'));
    $this->smarty->assign('centrocusto', $this->__get('centrocusto'));
    $this->smarty->assign('created_user', $this->__get('created_user'));
    $this->smarty->assign('updated_user', $this->__get('updated_user'));
    $this->smarty->assign('created_at', $this->__get('created_at'));
    $this->smarty->assign('updated_at', $this->__get('updated_at')."'");

    // COMBOBOX SITUACAO (UNICO)
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
    
    // situacaoinclusao
    $this->smarty->assign('situacao_ids', $situacao_ids);
    $this->smarty->assign('situacao_names', $situacao_names);
      
    if($this->__get('situacaoinclusao') == ''){
        $this->smarty->assign('situacaoinclusao', 0);   
    }else{
        $this->smarty->assign('situacaoinclusao', $this->__get('situacaoinclusao'));      
    }    
    
    // sitagatendimento
    $this->smarty->assign('sitagatendimento_ids', $situacao_ids);
    $this->smarty->assign('sitagatendimento_names', $situacao_names);
    
    if($this->__get('sitagatendimento') == ''){
        $this->smarty->assign('sitagatendimento', 0);   
    }else{
        $this->smarty->assign('sitagatendimento', $this->__get('sitagatendimento'));      
    }

    // sitematendimento
    $this->smarty->assign('sitematendimento_ids', $situacao_ids);
    $this->smarty->assign('sitematendimento_names', $situacao_names);

    if($this->__get('sitematendimento') == ''){
      $this->smarty->assign('sitematendimento', 0);   
    }else{
      $this->smarty->assign('sitematendimento', $this->__get('sitematendimento'));      
    }
    
    // sitsolicitarpeca
    $this->smarty->assign('sitsolicitarpeca_ids', $situacao_ids);
    $this->smarty->assign('sitsolicitarpeca_names', $situacao_names);

    if($this->__get('sitsolicitarpeca') == ''){
      $this->smarty->assign('sitsolicitarpeca', 0);   
    }else{
      $this->smarty->assign('sitsolicitarpeca', $this->__get('sitsolicitarpeca'));      
    }

    // sitagpeca
    $this->smarty->assign('sitagpeca_ids', $situacao_ids);
    $this->smarty->assign('sitagpeca_names', $situacao_names);

    if($this->__get('sitagpeca') == ''){
      $this->smarty->assign('sitagpeca', 0);   
    }else{
      $this->smarty->assign('sitagpeca', $this->__get('sitagpeca'));      
    }

    //sitpecarecebida
    $this->smarty->assign('sitpecarecebida_ids', $situacao_ids);
    $this->smarty->assign('sitpecarecebida_names', $situacao_names);

    if($this->__get('sitpecarecebida') == ''){
      $this->smarty->assign('sitpecarecebida', 0);   
    }else{
      $this->smarty->assign('sitpecarecebida', $this->__get('sitpecarecebida'));      
    }

    //sitaporcamento
    $this->smarty->assign('sitaporcamento_ids', $situacao_ids);
    $this->smarty->assign('sitaporcamento_names', $situacao_names);

    if($this->__get('sitaporcamento') == ''){
      $this->smarty->assign('sitaporcamento', 0);   
    }else{
      $this->smarty->assign('sitaporcamento', $this->__get('sitaporcamento'));      
    }

    //sitfinalizado
    $this->smarty->assign('sitfinalizado_ids', $situacao_ids);
    $this->smarty->assign('sitfinalizado_names', $situacao_names);

    if($this->__get('sitfinalizado') == ''){
      $this->smarty->assign('sitfinalizado', 0);   
    }else{
      $this->smarty->assign('sitfinalizado', $this->__get('sitfinalizado'));      
    }


    // COMBOBOX CONDIÇÃO DE PAGAMENTO
    $consulta = new c_banco();
    $sql = "SELECT ID , DESCRICAO FROM FAT_COND_PGTO ";
    $sql.= "WHERE SITUACAOLCTO = 'A'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i = 0; $i < count($result); $i++) {
        $condpgto_ids[$i] = $result[$i]['ID'];
        $condpgto_names[$i] = $result[$i]['DESCRICAO'];
    }

    $this->smarty->assign('condpgto_ids', $condpgto_ids);
    $this->smarty->assign('condpgto_names', $condpgto_names);
     
    if($this->__get('condpgto') == ''){
      $this->smarty->assign('condpgto', 0);   
    }else{
      $this->smarty->assign('condpgto', $this->__get('condpgto'));      
    }


    //COMBOBOX CONTA
    $consulta = new c_banco();
    $sql = "SELECT CONTA, NOMEINTERNO FROM FIN_CONTA ";
    $sql.= "WHERE STATUS = 'A'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i = 0; $i < count($result); $i++) {
        $conta_ids[$i] = $result[$i]['CONTA'];
        $conta_names[$i] = $result[$i]['NOMEINTERNO'];
    }

    $this->smarty->assign('conta_ids', $conta_ids);
    $this->smarty->assign('conta_names', $conta_names);

    if($this->__get('conta') == ''){
      $this->smarty->assign('conta', 0);   
    }else{
      $this->smarty->assign('conta', $this->__get('conta'));      
    }


    //COMBOBOX GENERO
    $consulta = new c_banco();
    $sql = "SELECT TIPO, DESCRICAO FROM FIN_GENERO ";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i = 0; $i < count($result); $i++) {
        $genero_ids[$i] = $result[$i]['TIPO'];
        $genero_names[$i] = $result[$i]['DESCRICAO'];
    }

    $this->smarty->assign('genero_ids', $genero_ids);
    $this->smarty->assign('genero_names', $genero_names);

    if($this->__get('genero') == ''){
      $this->smarty->assign('genero', 0);   
    }else{
      $this->smarty->assign('genero', $this->__get('genero'));      
    }


    // COMBOBOX CENTROCUSTO
    $consulta = new c_banco();
    $sql = "SELECT CENTROCUSTO, DESCRICAO FROM FIN_CENTRO_CUSTO ";
    $sql.=" WHERE ATIVO= 'S' ";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i = 0; $i < count($result); $i++) {
        $centrocusto_ids[$i] = $result[$i]['CENTROCUSTO'];
        $centrocusto_names[$i] = $result[$i]['DESCRICAO'];
    }

    $this->smarty->assign('centrocusto_ids', $centrocusto_ids);
    $this->smarty->assign('centrocusto_names', $centrocusto_names);

    if($this->__get('centrocusto') == ''){
      $this->smarty->assign('centrocusto', 0);   
    }else{
      $this->smarty->assign('centrocusto', $this->__get('centrocusto'));      
    }


    $this->smarty->display('parametro_cadastro.tpl');
    
}//fim desenhaCadastroParametros

/*
* <b> Listagem de todas as registro cadastrados de tabela banco. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraParametros($mensagem, $tipoMsg=NULL){

  
    $lanc = $this->select_parametros_geral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);
    $this->smarty->assign('tipoMsg', $tipoMsg);

    $this->smarty->display('parametro_mostra.tpl');
	

} //fim mostraParametros
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$banco = new p_parametros();

                              
$banco->controle();
 
  
?>
