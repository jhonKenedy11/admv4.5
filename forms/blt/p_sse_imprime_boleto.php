<?php

/**
 * @package   astec
 * @name      p_sse_imprime_boleto
 * @version   4.5.00
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy<jhon.kened11@hotmail.com>
 */

if (!defined('ADMpath')){
    exit('Acesso não autorizado');
}

$dir = (__DIR__);
require_once($dir."/../../class/blt/c_sse_imprime_boleto.php");


Class p_sse_imprime_boleto extends c_sse_imprime_boleto {

    public $parm_post;
    public $parm_get;

    /**
     * Construtor
     */
    function __construct(){

        parent::__construct();

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parm_post = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];
        $this->parm_get = filter_input_array(INPUT_GET, FILTER_DEFAULT) ?? [];

        $this->m_id_registro = $this->parm_get['id'] ?? null;
    }

    /**
    * Controlador principal
    */
    public function controle()
    {
        try {
            $this->initialize();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}

// Rotina principal - cria classe
$objeto = new p_sse_imprime_boleto();
$objeto->controle();
?>
