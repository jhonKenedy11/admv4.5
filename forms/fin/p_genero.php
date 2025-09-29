<?php
/**
 * @package   astecv3
 * @name      p_genero
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date     12/06/2016
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_genero.php");
include_once($dir."/../../bib/c_tools.php");

//Class p_genero_pag
Class p_genero extends c_genero {

private $m_submenu = NULL;
private $m_opcao = NULL;
private $m_letra = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct($submenu, $letra, $opcao){
        @set_exception_handler(array($this, 'exception_handler'));

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

  	 // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
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
            $this->smarty->assign('titulo', "Genero");
            $this->smarty->assign('colVis', "[ 0, 1, 2 ]"); 
            $this->smarty->assign('disableSort', "[ 2 ]"); 
            $this->smarty->assign('numLine', "25"); 
        }
        else{
            $this->smarty->assign('titulo', "Genero");
            $this->smarty->assign('colVis', "[ 0, 1, 2, 3 ]"); 
            $this->smarty->assign('disableSort', "[ 4 ]"); 
            $this->smarty->assign('numLine', "25"); 
            
        }
        // include do javascript
        // include ADMjs . "/fin/s_fin.js";

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
                if ($this->verificaDireitoUsuario('FinGenero', 'I')){
                        $this->desenhaCadastroGenero();
                }
                break;
        case 'alterar':
                if ($this->verificaDireitoUsuario('FinGenero', 'A')){
                        $genero = $this->select_genero();
                        $this->setGenero($genero[0]['GENERO']);
                        $this->setTipo($genero[0]['TIPO']);
                        $this->setDescricao($genero[0]['DESCRICAO']);
                        $this->setTipoLancamento($genero[0]['TIPOLANCAMENTO']);
                        $this->desenhaCadastroGenero();
                } 
                break;
        case 'inclui':
                if ($this->verificaDireitoUsuario('FinGenero', 'I')){
                        if ($this->existeGenero()){
                                $this->m_submenu = "cadastrar";
                                $this->desenhaCadastroGenero("GENERO JÁ EXISTENTE, ALTERE O NÚMERO DO GENERO");}
                        else {
                                $this->incluiGenero()
                                ? $this->mostraGenero(msgAdd.' ==> Genero: '.$this->getGenero(), typSuccess)
                                : $this->desenhaCadastroGenero(msgNotAdd, typError);
                        }
                }		
                break;
        case 'altera':
                if ($this->verificaDireitoUsuario('FinGenero', 'A')){
                        $this->alteraGenero()
                        ? $this->mostraGenero(msgUpdate.'==> Genero: '.$this->getGenero(), typAlert)
                        : $this->desenhaCadastroGenero(msgNotUpdate, typAlert);
                }
                break;
        case 'exclui':
                if ($this->verificaDireitoUsuario('FinGenero', 'E')){
                    $this->excluiGenero()
                    ? $this->mostraGenero(msgDelete.'==> Genero: '.$this->getGenero(), typSuccess)
                    : $this->mostraGenero(msgNotDelete.'==> Genero: '.$this->getGenero(), typAlert);
                }
                break;
        default:
                if ($this->verificaDireitoUsuario('FinGenero', 'C')){
                        $this->mostraGenero('');
                }

    }

} // fim controle


 /**
 * <b> Desenha form de cadastro ou alteração Genero. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroGenero($mensagem=NULL, $tipoMsg = ''){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    
    $this->smarty->assign('id', $this->getGenero());
    $this->smarty->assign('tipo', "'".$this->getTipo()."'");
    $this->smarty->assign('descricao', "'".$this->getDescricao()."'");
    $this->smarty->assign('tipoLancamento', "'".$this->getTipoLancamento()."'");
    
    // tipo Genero##############################
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoGeneroPgto')";
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $tipoGenero_ids[$i] = $result[$i]['ID'];
            $tipoGenero_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('tipoGenero_ids', $tipoGenero_ids);
    $this->smarty->assign('tipoGenero_names', $tipoGenero_names);
    $this->smarty->assign('tipoGenero_id', $this->getTipo());		

    // tipo lancamento ##############################
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoLanc')";
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $tipoLanc_ids[$i] = $result[$i]['ID'];
            $tipoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
    }
    $this->smarty->assign('tipoLanc_ids', $tipoLanc_ids);
    $this->smarty->assign('tipoLanc_names', $tipoLanc_names);
    $this->smarty->assign('tipoLanc_id', $this->getTipoLancamento());		
    
    $this->smarty->display('genero_cadastro.tpl');
    
}//fim desenhaCadastroGenero

/**
* <b> Listagem de todas as registro cadastrados de tabela genero. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraGenero($mensagem, $tipoMsg = ''){

    if (isset($this->m_letra) and ($this->m_letra!='')) 
        $lanc = $this->select_genero_letra(strtoupper($this->m_letra));
    else
	$lanc = $this->select_genero_geral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

    if ($this->m_opcao=="pesquisar"){
        $this->smarty->display('genero_pesquisar.tpl');}
    else{
        $this->smarty->display('genero_mostra.tpl');}

} //fim mostraGenero
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$genero = new p_genero($_POST['submenu'],
                       $_POST['letra'],$_REQUEST['opcao']);

                              
if (isset($_POST['id'])) { $genero->setGenero($_POST['id']); } else {$genero->setGenero('');};
if (isset($_POST['tipo'])) { $genero->setTipo($_POST['tipo']); } else {$genero->setTipo('');};
if (isset($_POST['descricao'])) { $genero->setDescricao($_POST['descricao']); } else {$genero->setDescricao('');};
if (isset($_POST['tipoLancamento'])) { $genero->setTipoLancamento($_POST['tipoLancamento']); } else {$genero->setTipoLancamento('');};

$genero->controle();
 
  
?>
