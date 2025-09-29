<?php
/**
 * @package   astec
 * @name      p_nfephp_imprime_danfe
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      01/06/2017
 */

/**
 * 
PARA INCLUIR NO INDEX
if($this->form == "nfephp_imprime_danfe"){
    $class = "p_".$this->form;
    $form = new $class();
    $form->printDanfe($_GET["id"]);
}
 * 
 * 
 * ATENÇÃO : Esse exemplo usa classe PROVISÓRIA que será removida assim que 
 * a nova classe DANFE estiver refatorada e a pasta EXTRAS será removida.

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../nfephp/bootstrap.php';

use NFePHP\NFe\ToolsNFe;
use NFePHP\Extras\Danfe;
use NFePHP\Common\Files\FilesFolders;

$nfe = new ToolsNFe('C:\www\Apache24\htdocs\maxifarma\nfe\config\config.json');

$path = "C:\www\Apache24\htdocs\maxifarma";
$slash = '/'; 
(stristr( $path, $slash )) ? '' : $slash = '\\'; 

define( 'BASE_DIR_ASSINADA', 'C:\www\Apache24\htdocs\maxifarma\nfe\homologacao\assinadas\201705'.$slash); 
define( 'BASE_DIR_PDF', $path.$slash.'nfe\homologacao\pdf\201705'.$slash); 


$chave = '41170502829379000172550010000001991000001998';
$dirPath = BASE_DIR_ASSINADA;
$xmlProt = $dirPath.$chave.'-nfe.xml'; // Ambiente Windows

$dirPath = BASE_DIR_PDF;
$pdfDanfe = $dirPath.$chave.'-danfe.pdf'; // Ambiente Windows

//$xmlProt = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/enviadas/aprovadas/201605/{$chave}-protNFe.xml";
// Uso da nomeclatura '-danfe.pdf' para facilitar a diferenciação entre PDFs DANFE e DANFCE salvos na mesma pasta...
//$pdfDanfe = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/pdf/201605/{$chave}-danfe.pdf";

$docxml = FilesFolders::readFile($xmlProt);
$danfe = new Danfe($docxml, 'P', 'A4', $nfe->aConfig['aDocFormat']->pathLogoFile, 'I', '');
$id = $danfe->montaDANFE();
$salva = $danfe->printDANFE($pdfDanfe, 'F'); //Salva o PDF na pasta
//header("Location:/{$pdfDanfe}", true);
$abre = $danfe->printDANFE("{$id}-danfe.pdf", 'I'); //Abre o PDF no Navegador
 */

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");

//Class P_Nota_Fiscal
Class p_nfephp_imprime_danfe extends c_nota_fiscal {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_opcao = NULL;
    private $origem  = NULL;
    private $m_msg   = NULL;
    public $smarty   = NULL;

//---------------------------------------------------------------
//---------------------------------------------------------------
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

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
        $this->m_submenu = $this->parmPost['submenu'];
        $this->m_opcao = $this->origemparmPost['opcao'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_origem = $this->parmGet['origem'];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');
        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmGet['id']) ? $parmGet['id'] : (isset($parmPost['id']) ? $parmPost['id'] : ''));
        
    }

    function printDanfe($id, $numNf=null, $serie='', $danfe='', $numPedido='', $retorno=null) {

        if ($danfe==''):
            $this->setId($id);
            $result = $this->select_nota_fiscal();
            $danfe = strtolower($result[0]['PATHDANFE']);
            $numNf = $result[0]['NUMERO'];
            $numPedido = $result[0]['DOC'];
        endif;
        
        $this->smarty->assign('id', $id);
        $this->smarty->assign('numPedido', $numPedido);
        $this->smarty->assign('numNf', $numNf);
        $this->smarty->assign('danfe', $danfe);
        $this->smarty->assign('retorno', $retorno);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('origem', $this->m_origem);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        
        $this->smarty->display('nota_fiscal_mostra_danfe.tpl');
        
    }
}