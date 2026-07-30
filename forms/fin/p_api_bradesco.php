<?php
/**
 * @package   astecv3
 * @name      p_banco
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      02/12/2025
 */
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
include_once($dir."/../../class/fin/c_api_bradesco.php");

//Class p_api_bradesco
Class p_api_bradesco extends c_api_bradesco {

  public $m_submenu = NULL;

  function __construct(){

    parent::__construct();
    
    // registra uma função/método para tratar exceções que não foram capturadas por nenhum try/catch
    @set_exception_handler(array($this, 'exception_handler'));

    // inicializa variaveis de controle
    $this->m_submenu = isset($this->parm_post['submenu']) ? $this->parm_post['submenu'] : '';
    $this->m_id_lancamento = isset($this->parm_post['id_lancamento']) ? $this->parm_post['id_lancamento'] : '';
    $this->m_banco = isset($this->parm_post['banco']) ? $this->parm_post['banco'] : '';
    $this->m_dados = isset($this->parm_post['dados']) ? $this->parm_post['dados'] : [];
    $this->m_id_conta = isset($this->parm_post['id_conta']) ? $this->parm_post['id_conta'] : 0;
  }

  // Função de tratamento de exceções personalizada
  function exception_handler($exception) {
    // Log do erro para debug
    error_log('API Bradesco Exception: ' . $exception->getMessage() . ' | File: ' . $exception->getFile() . ' | Line: ' . $exception->getLine());
    
    // Retorna erro formatado em JSON para o frontend
    c_api_response::serverError(
      'Erro interno ao processar requisição',
      [
        'mensagem' => $exception->getMessage(),
        'arquivo' => basename($exception->getFile()),
        'linha' => $exception->getLine()
      ]
    );
  }

/**
   * <b> É responsavel para indicar para onde o sistema ira executar </b>
   * @name controle
   * @param string this->m_submenu 
   * @return void
   */
  function controle(){

    switch ($this->m_submenu){
      case 'consultaTitulosLiquidados':
        $this->consultaTitulosLiquidados($this->m_dados);
        break;
      case 'consultaTituloPendente':
        $this->consultaTituloPendente($this->m_dados);
        break;
      case 'consultaTitulosBaixados':
        $this->consultaTitulosBaixados($this->m_dados);
        break;
      case 'consultaDeTituloUnitario':
        $this->consultaDeTituloUnitario($this->m_dados);
        break;
      case 'registraBoleto':
        $this->registraBoleto($this->m_id_lancamento);
        break;
      case 'baixaTitulo':
        $this->baixaTitulo($this->m_dados);
        break;
      case 'baixaTituloConsolidacao':
        $this->baixaTituloConsolidacao($this->m_dados);
        break;
      case 'alteraTitulo':
        $this->alteraTitulo($this->m_id_lancamento);
        break;
      case 'manutencaoSplitPayment':// Rateio de credito
        break;
      case 'consultaSplitPayment': // Rateio de credito
        break;
      case 'protestoNegativacao': // Inclui solicitacao de instrucao  de protesto e sustacao de protesto
        break;
      case 'alterarPagina':
        $this->alterarPagina($this->m_dados);
        break;
      default:
        break;
    }

  } // fim controle

}
//	END OF THE CLASS

$api_bradesco = new p_api_bradesco();
                              
$api_bradesco->controle();

?>
