<?php
/**
 * @package   astec
 * @name      p_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      02/05/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");

require_once($dir."/../../bib/dompdf/lib/html5lib/Parser.php");
require_once($dir."/../../bib/dompdf/lib/php-font-lib-master/src/FontLib/Autoloader.php");
require_once($dir."/../../bib/dompdf/lib/php-svg-lib-master/src/autoload.php");
require_once($dir."/../../bib/dompdf/src/Autoloader.php");
include_once($dir . "/../../bib/c_mail.php");

Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

//Class P_situacao
Class p_pedido_venda_imprime extends c_pedidoVenda {

    private $m_submenu          = NULL;
    private $m_origem           = NULL;
    private $m_letra            = NULL;
    private $m_par              = NULL;
    public $smarty              = NULL;
    private $m_motivoSelecionados = null;
    private $m_vendedorSelecionados = null;
    private $m_condPagSelecionados = null;
    private $m_situacaoSelecionados = null;
    private $m_centroCustoSelecionados = null;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

    // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizCliente . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";


        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_origem = $parmPost['origem'];
        $this->m_letra = $parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmGet['parm']) ? $parmGet['parm'] : '');
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : '');
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_motivoSelecionados = (isset($parmGet['motivos']) ? $parmGet['motivos'] : '');
        $this->m_vendedorSelecionados = (isset($parmGet['vendedores']) ? $parmGet['vendedores'] : '');
        $this->m_condPagSelecionados = (isset($parmGet['condPag']) ? $parmGet['condPag'] : '');
        $this->m_situacaoSelecionados = (isset($parmGet['situacao']) ? $parmGet['situacao'] : '');
        $this->m_centroCustoSelecionados = (isset($parmGet['centroCusto']) ? $parmGet['centroCusto'] : '');

        $this->m_idPedido = $_POST['id'];
        $this->m_destinatario = (isset($parmPost['destinatario']) ? $parmPost['destinatario'] : '');
        $this->m_comCopiaPara = (isset($parmPost['comCopiaPara']) ? $parmPost['comCopiaPara'] : '');
        $this->m_assunto = (isset($parmPost['assunto']) ? $parmPost['assunto'] : '');
        $this->m_emailCorpo = (isset($parmPost['emailCorpo']) ? $parmPost['emailCorpo'] : '');
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'relVendaVendedor':
                $this->relVendaVendedor('');
            break;
            case 'relVendaMotivo':
                $this->relVendaMotivo('');
            break;
            case 'relVendaCondPagamento':
                $this->relVendaCondPagamento('');
            break;
            case 'relVendaMes':
                $this->relVenda('');
            break;
            case 'relVendaSemana':
                $this->relVenda('');
            break;
            case 'relVenda':
                $this->relVenda('');
            break;
            case 'relatorioDeEntregas':
                $this->relVendaEntrega('');
            break;
            case 'relatorioVendaDet':
                $this->relVenda('');
            break;
            case 'relatorioVendaItem':
                $this->relVenda('');
            break;
            case 'relatorioVendaFatura':
                $this->relFaturaPedVenda('');
            break;
            case 'enviaEmailPedido':
                $this->enviaEmailPedido();
            break;
            default:
                $this->mostraPedidoImprime('');
            
        }
    }

   

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------

    function relVendaCondPagamento($mensagem=NULL, $tipoMsg=NULL) {
            
            
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $par = explode("|", $this->m_letra);

        //$motivo = explode("|", $this->m_motivoSelecionados); 
        $situacao = explode("|", $this->m_situacaoSelecionados); 
        $vendedor = explode("|", $this->m_vendedorSelecionados); 
        $condPag = explode("|", $this->m_condPagSelecionados); 
        $centroCusto = explode("|", $this->m_centroCustoSelecionados); 


    //  $lanc = $this->select_pedidoVenda( $par);
        $lanc = $this->select_todos_pedidos_cond_pag($par, $vendedor, $condPag, $situacao, $centroCusto);
    //  $this->setId($lanc[0]['PEDIDO']);
    //  $lancItem = $this->select_pedido_item_id('1'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
    //  $empresa = $this->busca_dadosEmpresaCC($par[2]);

        // busca descrição condição pagamento
        if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
            $descCondPgto = '';
        else:
            $condPgto = new c_cond_pgto();
            $condPgto->setId($lanc[0]['CONDPG']);
            $descPgto = $condPgto->selectCondPgto();
            $descCondPgto = $descPgto[0]['DESCRICAO'];
        endif;
        
        if ($lanc[0]['SITUACAO'] == 9):
            // Busca lancamentos FINANCEIRO
            $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
        else:
            // Calcula lancamentos de acordo com condição pagamento.
            $fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
        endif;

        $this->smarty->assign('descCondPgto', $descCondPgto);
    //   $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('pedido', $lanc);
    // $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('periodoIni', $par[0]);
        $this->smarty->assign('periodoFim', $par[1]);
        $this->smarty->assign('fin', $fin);

        $consulta = new c_banco();
        $sql = "SELECT * FROM AMB_USUARIO_AUTORIZA WHERE USUARIO = (".$this->m_usergrupo.") 
                AND PROGRAMA = 'RELPEDIDOVENDACOMCUSTO' AND DIREITOS LIKE '%C%'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        if (sizeof($result) > 0){
            $this->smarty->assign('tipoUsuario', 'Consulta');
        }

        
        $this->smarty->display('relatorio_pedido_venda_cond_pagamento.tpl');
    }
    //-------------------------------------------------------------


    function relVendaVendedor($mensagem=NULL, $tipoMsg=NULL) {
        
        
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $par = explode("|", $this->m_letra);
        $situacao = explode("|", $this->m_situacaoSelecionados); 
        $vendedor = explode("|", $this->m_vendedorSelecionados); 
        $condPag = explode("|", $this->m_condPagSelecionados); 
        $centroCusto = explode("|", $this->m_centroCustoSelecionados); 

      //  $lanc = $this->select_pedidoVenda( $par);

        $lanc = $this->select_todos_pedidos_vendedor($par, $vendedor, $condPag, $situacao, $centroCusto, '');
      //  $this->setId($lanc[0]['PEDIDO']);
      //  $lancItem = $this->select_pedido_item_id('1'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
      //  $empresa = $this->busca_dadosEmpresaCC($par[2]);

        // busca descrição condição pagamento
        if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
            $descCondPgto = '';
        else:
            $condPgto = new c_cond_pgto();
            $condPgto->setId($lanc[0]['CONDPG']);
            $descPgto = $condPgto->selectCondPgto();
            $descCondPgto = $descPgto[0]['DESCRICAO'];
        endif;
        
        if ($lanc[0]['SITUACAO'] == 9):
            // Busca lancamentos FINANCEIRO
            $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
        else:
            // Calcula lancamentos de acordo com condição pagamento.
            $fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
        endif;

        $this->smarty->assign('descCondPgto', $descCondPgto);
     //   $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('pedido', $lanc);
       // $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('periodoIni', $par[0]);
        $this->smarty->assign('periodoFim', $par[1]);
        $this->smarty->assign('fin', $fin);

        $consulta = new c_banco();
        $sql = "SELECT * FROM AMB_USUARIO_AUTORIZA WHERE USUARIO = (".$this->m_usergrupo.") 
                AND PROGRAMA = 'RELPEDIDOVENDACOMCUSTO' AND DIREITOS LIKE '%C%'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        if (sizeof($result) > 0){
            $this->smarty->assign('tipoUsuario', 'Consulta');
        }
        
        $this->smarty->display('relatorio_pedido_venda_vendedor.tpl');
       
        
    }

    //-------------------------------------------------------------
 

    function relVenda($mensagem=NULL, $tipoMsg=NULL) {
        
        
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $par = explode("|", $this->m_letra);
        $situacao = explode("|", $this->m_situacaoSelecionados); 
        $vendedor = explode("|", $this->m_vendedorSelecionados); 
        $condPag = explode("|", $this->m_condPagSelecionados); 
        $centroCusto = explode("|", $this->m_centroCustoSelecionados); 

        //$lanc = $this->select_fatura_pedido_venda($par, $vendedor, $condPag, $situacao, $centroCusto, '');

        $lanc = $this->select_todos_pedidos($par, $vendedor, $condPag, $situacao, $centroCusto, '');
      //  $this->setId($lanc[0]['PEDIDO']);
       // $lancItem = $this->select_pedido_item_id('1'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
      //  $empresa = $this->busca_dadosEmpresaCC($par[2]);
      if ($this->m_submenu == 'relatorioVendaDet' || $this->m_submenu == 'relatorioVendaItem'){
            $lancItem = [];
            for ($i=0; $i < count($lanc); $i++){
                $resp = $this->select_todos_pedidos_item($lanc[$i]['PEDIDO']);
                    for($k=0; $k < count($resp); $k++){
                        if($lancItem[0] == ''){
                            $lancItem[$k] = $resp[$k];
                        }else{
                            array_push($lancItem, $resp[$k]);
                        }
                        
                    }
                
            }
        }    

        // busca descrição condição pagamento
        if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
            $descCondPgto = '';
        else:
            $condPgto = new c_cond_pgto();
            $condPgto->setId($lanc[0]['CONDPG']);
            $descPgto = $condPgto->selectCondPgto();
            $descCondPgto = $descPgto[0]['DESCRICAO'];
        endif;
        
        if ($lanc[0]['SITUACAO'] == 9):
            // Busca lancamentos FINANCEIRO
            $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
        else:
            // Calcula lancamentos de acordo com condição pagamento.
            $fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
        endif;

        $this->smarty->assign('descCondPgto', $descCondPgto);
     //   $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('periodoIni', $par[0]);
        $this->smarty->assign('periodoFim', $par[1]);
        $this->smarty->assign('fin', $fin);

        $consulta = new c_banco();
        $sql = "SELECT * FROM AMB_USUARIO_AUTORIZA WHERE USUARIO = (".$this->m_usergrupo.") 
                AND PROGRAMA = 'RELPEDIDOVENDACOMCUSTO' AND DIREITOS LIKE '%C%'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        if (sizeof($result) > 0){
            $this->smarty->assign('tipoUsuario', 'Consulta');
        }
        switch ($this->m_submenu) {
        
            case 'relVendaMes':
                $this->smarty->display('relatorio_pedido_venda_mes.tpl');
            break;
            case 'relVendaSemana':
                $this->smarty->display('relatorio_pedido_venda_semana.tpl');
            break;
            case 'relatorioDeEntregas':
                $this->smarty->display('relatorio_pedido_entrega.tpl');
            break;
            case 'relatorioVendaDet':
                $this->smarty->display('relatorio_pedido_venda_new.tpl');
            break;
            case 'relatorioVendaItem':
                $this->smarty->display('relatorio_pedido_venda_item.tpl');
            break;
            default:
              $this->smarty->display('relatorio_pedido_venda.tpl');
        }
        
    }

    function relVendaEntrega($mensagem=NULL, $tipoMsg=NULL) {
        
        
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $par = explode("|", $this->m_letra);
        $situacao = explode("|", $this->m_situacaoSelecionados); 
        $vendedor = explode("|", $this->m_vendedorSelecionados); 
        $condPag = explode("|", $this->m_condPagSelecionados); 
        $centroCusto = explode("|", $this->m_centroCustoSelecionados); 

      //  $lanc = $this->select_pedidoVenda( $par);

        $lanc = $this->select_todos_pedidos($par, $vendedor, $condPag, $situacao, $centroCusto, '', 'ENTREGA');
      //  $this->setId($lanc[0]['PEDIDO']);
      //  $lancItem = $this->select_pedido_item_id('1'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
      //  $empresa = $this->busca_dadosEmpresaCC($par[2]);

        // busca descrição condição pagamento
        if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
            $descCondPgto = '';
        else:
            $condPgto = new c_cond_pgto();
            $condPgto->setId($lanc[0]['CONDPG']);
            $descPgto = $condPgto->selectCondPgto();
            $descCondPgto = $descPgto[0]['DESCRICAO'];
        endif;
        
        if ($lanc[0]['SITUACAO'] == 9):
            // Busca lancamentos FINANCEIRO
            $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
        else:
            // Calcula lancamentos de acordo com condição pagamento.
            $fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
        endif;

        $this->smarty->assign('descCondPgto', $descCondPgto);
     //   $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('pedido', $lanc);
       // $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('periodoIni', $par[0]);
        $this->smarty->assign('periodoFim', $par[1]);
        $this->smarty->assign('fin', $fin);

        $consulta = new c_banco();
        $sql = "SELECT * FROM AMB_USUARIO_AUTORIZA WHERE USUARIO = (".$this->m_usergrupo.") 
                AND PROGRAMA = 'RELPEDIDOVENDACOMCUSTO' AND DIREITOS LIKE '%C%'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        if (sizeof($result) > 0){
            $this->smarty->assign('tipoUsuario', 'Consulta');
        }
        $this->smarty->display('relatorio_pedido_entrega.tpl');        
    }

    function relVendaMotivo($mensagem=NULL, $tipoMsg=NULL) {
        
        
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));
    
        $par = explode("|", $this->m_letra);
        $situacao = explode("|", $this->m_situacaoSelecionados); 
        $vendedor = explode("|", $this->m_vendedorSelecionados); 
        $condPag = explode("|", $this->m_condPagSelecionados);
        $motivo = explode("|", $this->m_motivoSelecionados); 
        $centroCusto = explode("|", $this->m_centroCustoSelecionados); 
    
      //  $lanc = $this->select_pedidoVenda( $par);
        $lanc = $this->select_todos_pedidos_motivo($par, $vendedor, $condPag, $situacao,$centroCusto, $motivo);
      //  $this->setId($lanc[0]['PEDIDO']);
      //  $lancItem = $this->select_pedido_item_id('1'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
      //  $empresa = $this->busca_dadosEmpresaCC($par[2]);
    
        // busca descrição condição pagamento
        if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
            $descCondPgto = '';
        else:
            $condPgto = new c_cond_pgto();
            $condPgto->setId($lanc[0]['CONDPG']);
            $descPgto = $condPgto->selectCondPgto();
            $descCondPgto = $descPgto[0]['DESCRICAO'];
        endif;
        
        if ($lanc[0]['SITUACAO'] == 9):
            // Busca lancamentos FINANCEIRO
            $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
        else:
            // Calcula lancamentos de acordo com condição pagamento.
            $fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
        endif;
    
        $this->smarty->assign('descCondPgto', $descCondPgto);
     //   $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('pedido', $lanc);
       // $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('periodoIni', $par[0]);
        $this->smarty->assign('periodoFim', $par[1]);
        $this->smarty->assign('fin', $fin);
    
        
        $this->smarty->display('relatorio_pedido_venda_motivo.tpl');
    }

    function relFaturaPedVenda($mensagem=NULL, $tipoMsg=NULL){
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', ADMimg);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $par = explode("|", $this->m_letra);
        $situacao = explode("|", $this->m_situacaoSelecionados); 
        $vendedor = explode("|", $this->m_vendedorSelecionados); 
        $condPag = explode("|", $this->m_condPagSelecionados); 
        $centroCusto = explode("|", $this->m_centroCustoSelecionados); 

        $lanc = $this->select_todos_pedidos($par, $vendedor, $condPag, $situacao, $centroCusto, '');

        $lancItem = [];
        for ($i=0; $i < count($lanc); $i++){
            $resp = $this->select_fatura_pedido_venda($lanc[$i]['PEDIDO']);
                for($k=0; $k < count($resp); $k++){
                    if($lancItem[0] == ''){
                        $lancItem[$k] = $resp[$k];
                    }else{
                        array_push($lancItem, $resp[$k]);
                    }
                    
                }
            
        }

        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('periodoIni', $par[0]);
        $this->smarty->assign('periodoFim', $par[1]);
        $this->smarty->display('relatorio_pedido_venda_fatura.tpl');
    }
//fim relFaturaPedVenda

    function mostraPedidoImprime($mensagem=NULL, $tipoMsg=NULL) {
            
            
        $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
        $this->smarty->assign('pathImagem', "images");
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

        $lanc = $this->select_pedidoVenda() ?? [];
        $lancItem = $this->select_pedido_item_id('1'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
        $empresa = $this->busca_dadosEmpresaCC($this->m_empresacentrocusto);

        // busca descrição condição pagamento
        if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
            $descCondPgto = '';
        else:
            $condPgto = new c_cond_pgto();
            $condPgto->setId($lanc[0]['CONDPG']);
            $descPgto = $condPgto->selectCondPgto();
            $descCondPgto = $descPgto[0]['DESCRICAO'];
        endif;
        
        if ($lanc[0]['SITUACAO'] == 9):
            // Busca lancamentos FINANCEIRO
            $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
        else:
            // Calcula lancamentos de acordo com condição pagamento.
            //$fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
        endif;

        //AJUSTA QUEBRA DE LINHA
        $lanc[0]['OBS'] = nl2br($lanc[0]['OBS']);

        $this->smarty->assign('prazoEntrega', $lanc[0]['PRAZOENTREGA']);
        $this->smarty->assign('descCondPgto', $descCondPgto);
        $this->smarty->assign('empresa', $empresa);
        $this->smarty->assign('pedido', $lanc);
        $this->smarty->assign('pedidoItem', $lancItem);
        $this->smarty->assign('fin', $fin);

        switch ($this->m_letra) {
            case 'cliente':
                $this->smarty->display('pedido_venda_imp_romaneio_cliente.tpl');
            break;
            default:
                $this->smarty->display('pedido_venda_imp_romaneio.tpl');
        }

    }

    function enviaEmailPedido(){    

        try{
            // definindo os namespaces

            $this->smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
            $this->smarty->assign('pathImagem', ADMimg);
            $this->smarty->assign('cssBootstrap', true);
            
            $this->smarty->assign('dataImp', date("d/m/Y H:i:s"));

            $this->setId($_POST['id']);

            $lanc = $this->select_pedidoVenda();
            $lancItem = $this->select_pedido_item_id('1'); // VERIFICAR SE CONTROLA LOTE E DATA VALIDADE PARAMETRO 1
            $empresa = $this->busca_dadosEmpresaCC($lanc[0]['CCUSTO']);

            // busca descrição condição pagamento
            if (($lanc[0]['CONDPG'] == '') or ($lanc[0]['CONDPG'] == '0') or ($lanc[0]['CONDPG'] == 0)):
                $descCondPgto = '';
            else:
                $condPgto = new c_cond_pgto();
                $condPgto->setId($lanc[0]['CONDPG']);
                $descPgto = $condPgto->selectCondPgto();
                $descCondPgto = $descPgto[0]['DESCRICAO'];
            endif;
            
            if ($lanc[0]['SITUACAO'] == 9):
                // Busca lancamentos FINANCEIRO
                $fin = c_lancamento::select_lancamento_doc('PED', $lanc[0]['PEDIDO']);
            else:
                // Calcula lancamentos de acordo com condição pagamento.
                //$fin = c_pedidoVendaNf::calculaParcelasNfe($descCondPgto, $lanc[0]['TOTAL']);
            endif;
            $this->smarty->assign('prazoEntrega', $lanc[0]['PRAZOENTREGA']);
            $this->smarty->assign('descCondPgto', $descCondPgto);
            $this->smarty->assign('empresa', $empresa);
            $this->smarty->assign('pedido', $lanc);
            $this->smarty->assign('pedidoItem', $lancItem);
            $this->smarty->assign('fin', $fin);

            $urlImg = "https://admsistema.com.br/".ADMcliente."/images/logo.png";

            $this->smarty->assign('urlImg', $urlImg);

            $html = $this->smarty->fetch('pedido_venda_imp_romaneio.tpl');
            
            //call dom pdf class
            /*$dompdf = new DOMPDF();
            $dompdf->load_html($html);
            //$dompdf->render();
            //display output in browser
            //$dompdf->stream();
            //or, save as a pdf
            $pdf = $dompdf->output();
            file_put_contents(ADMraizCliente."/file/pedido.pdf", $pdf);*/
            
            
            $filename = ADMraizCliente."/file/pedido".$this->getId().".pdf";
            $options = new Options();
            $options->set('isRemoteEnabled', TRUE);

            $dompdf = new DOMPDF($options);
            $dompdf->load_html($html);            
            $dompdf->set_paper('A4', 'portrait');
            $dompdf->render();
            file_put_contents($filename, $dompdf->output());

            $mail = new admMail();

           /* $result = $mail->SendMail($this->m_configsmtp, $this->m_configemail, "email Nfe", $this->m_configemailsenha, 
                               $this->m_emailCorpo, $this->m_assunto, $this->m_destinatario, "",$this->m_comCopiaPara,"", $filename,"");
        */
            
            unlink($filename);
        }catch(Error $e){

        }
    }

    
//-------------------------------------------------------------


//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$pedido = new p_pedido_venda_imprime();

$pedido->controle();
?>
