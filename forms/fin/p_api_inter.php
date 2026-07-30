<?php
/**
 * @package   astecv3
 * @name      p_api_inter
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      02/12/2025
 */
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir."/../../class/fin/c_api_inter.php");

//Class p_api_bradesco
Class p_api_inter extends c_api_inter {

  public $m_submenu = NULL;

  function __construct(){

    parent::__construct();
    
    // registra uma função/método para tratar exceções que não foram capturadas por nenhum try/catch
    @set_exception_handler(array($this, 'exception_handler'));

    // inicializa variaveis de controle
    $this->m_motivo_cancelamento = isset($this->parm_post['motivo_cancelamento']) ? $this->parm_post['motivo_cancelamento'] : '';
    $this->m_metodo_pagamento    = isset($this->parm_post['metodo_pagamento']) ? $this->parm_post['metodo_pagamento'] : '';
    $this->m_id_lancamento       = isset($this->parm_post['id_lancamento']) ? $this->parm_post['id_lancamento'] : 0;
    $this->m_submenu             = isset($this->parm_post['submenu']) ? $this->parm_post['submenu'] : '';
    $this->m_banco               = isset($this->parm_post['banco']) ? $this->parm_post['banco'] : '';
    $this->m_id                  = isset($this->parm_post['id']) ? $this->parm_post['id'] : 0;
    $this->m_dados               = isset($this->parm_post['dados']) ? $this->parm_post['dados'] : [];
    
  }

  // Função de tratamento de exceções personalizada
  /**
   * Função de tratamento de exceções personalizada
   * @param Throwable $exception
   * @return void
   */
  function exception_handler($exception) {
    // Log do erro para debug
    error_log('API Bradesco Exception: ' . $exception->getMessage() . ' | File: ' . $exception->getFile() . ' | Line: ' . $exception->getLine());
    
    c_api_response::failure(
      'Erro interno ao processar requisição',
      [
        'mensagem' => $exception->getMessage(),
        'arquivo' => basename($exception->getFile()),
        'linha' => $exception->getLine()
      ],
      null,
      ['type' => 'internal']
    );
  }

/**
   * <b> É responsavel para indicar para onde o sistema ira executar </b>
   * @name controle
   * @param string $submenu - Submenu da página
   * @return void
   * @throws Exception
   */
  function controle(){

    switch ($this->m_submenu){
      case 'emitirCobranca':
        $this->emitirCobranca($this->m_id_lancamento);
        break;
      case 'recuperarCobranca':
        $this->recuperarCobranca($this->m_id);
        break;
      case 'recuperarCobrancaEmPdf':
        $this->recuperarCobrancaEmPdf($this->m_id);
        break;
      case 'cancelarCobranca':
        $this->cancelarCobranca($this->m_id_lancamento, $this->m_motivo_cancelamento);
        break;
      case 'pagarCobranca':
        $this->pagarCobranca($this->m_id_lancamento, $this->m_metodo_pagamento);
        break;
      case 'recuperarColecaoCobranca':
        $this->recuperarColecaoCobranca($this->m_dados);
        break;
      case 'alterarPagina':
        $this->alterarPagina($this->m_dados);
        break;
      default:
        c_api_response::failure('Rota não encontrada.', [], null, ['type' => 'not_found']);
        break;
    }

  } // fim controle

}
//	END OF THE CLASS

$api_inter = new p_api_inter();
                              
$api_inter->controle();

?>
