<?php
/**
 * @package   astecv3
 * @name      c_manifesto_veiculo
 * @version   3.0.00
 * @copyright 2022
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      19/10/2022
 */

$dir = (__DIR__);
// error_reporting(E_ALL);
// ini_set('display_errors', 'Off');
//ini_set('display_errors', 'On');


require_once $dir . '/../../../sped/vendor/autoload.php';
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");

use NFePHP\Common\Certificate;
use NFePHP\MDFe\Common\Standardize;
use NFePHP\MDFe\Tools;


class c_manifesto_veiculo extends c_user{
    
    private $id = NULL;

    public function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        // session_start();
        $this->from_array($_SESSION['user_array']);


        //$this->nfePath = ADMnfe.$this->m_empresaid.$slash.ADMambDesc;
        $slash = '/'; 
        define( 'BASE_DIR_NFE_CFG', ADMnfe.$slash.$this->m_empresaid.$slash.'config'); 
        define( 'BASE_DIR_NFE_AMB', ADMnfe.$slash.$this->m_empresaid.$slash.ADMambDesc); 
        define( 'BASE_HTTP_NFE_AMB', ADMhttpCliente.$slash.'nfe'.$slash.$this->m_empresaid.$slash.ADMambDesc.$slash); 
        define( 'BASE_DIR_CERT', ADMnfe.$slash.$this->m_empresaid.$slash.'certs'.$slash); 
        
    }

    public function buscaNotaFiscalMdf($idMdf){
        $sql = "SELECT N.* , ";
        $sql .= "DATE_FORMAT(N.EMISSAO, '%d/%m/%Y') AS DATA_FORMATADA, C.NOMEREDUZIDO  AS CLIENTE_DESC, ";
        $sql .= "CONCAT('R$ ',REPLACE(REPLACE(REPLACE(FORMAT(N.TOTALNF, 2), '.', '|'), ',', '.'), '|', ',')) AS TOTALNF_FORMATADO ";
        $sql .= "FROM EST_NOTA_FISCAL N ";
        $sql .= "INNER JOIN FIN_CLIENTE C ON N.PESSOA = C.CLIENTE ";
        $sql .= "WHERE (ID_MDF = '" . $idMdf . "') ";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql_lower_case($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function buscaDadosVeiculo($idVeiculo){
        $sql = "SELECT * ";
        $sql .= "FROM est_veiculo ";
        $sql .= "WHERE (idveiculo = '" . $idVeiculo . "') ";
        // echo strtoupper($sql);

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


}


