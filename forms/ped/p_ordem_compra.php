<?php
/**
 * @package   astec
 * @name      p_pedido_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Maárcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/06/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_ordem_compra.php");
require_once($dir . "/../../class/ped/c_ordem_compra_tools.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");

//Class P_situacao
Class p_ordem_compra extends c_ordemCompra {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_desconto     = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensQtde    = NULL;
    private $m_natop        = NULL;
    public $smarty          = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //$parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

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
        $this->m_submenu = $parmPost['submenu'];
        $this->m_pesq = $parmPost['pesq'];

        $this->m_letra = $parmPost['letra'];
        $this->m_desconto = $parmPost['desconto'];
        $this->m_itensPedido = $parmPost['itensPedido'];
        $this->m_itensQtde = $parmPost['itensQtde'];
        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_parPesq = explode("|", $this->m_pesq);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Pedidos de Vendas");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');
        //$this->setIdNatop(isset($parmPost['natop']) ? $parmPost['natop'] : '');
        $this->setCondPg(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        
        if (isset($parmPost['pessoa'])):
            $this->setCliente($parmPost['pessoa']);
        else:    
            $this->setCliente('');
        endif;
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->desenhaCadastroOrdemCompra();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $this->setOrdemCompra();
                    $testeSit = $this->getSituacao();
                    if ($this->getId() > 0){
                        $this->desenhaCadastroOrdemCompra();
                    }else{
                        $this->mostraOrdemCompra('Ordem de Compra não pode ser alterada.');
                    }                  
                }
                break;
            case 'altera': // CONCLUIR
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    $this->setTotal($this->select_ordem_compra_total(),true);
                    $this->setSituacao(0);
                    $this->alteraOrdemCompraTotal();
                    $this->alteraOrdemCompra();
                    $this->mostraOrdemCompra('Ordem Compra confirmado.');
                }
                break;
            case 'digita': //VOLTAR
                if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    if ($this->getId()!=''):
                        
                        $this->setTotal($this->select_ordem_compra_total(),true);
                        $this->setSituacao(0);
                        $this->alteraOrdemCompra();
                        $this->mostraOrdemCompra('');
                    else:    
                        $this->mostraOrdemCompra('');
                    endif;
                    
                }
                break;
            case 'exclui': // CANCELA
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $arrOrdemCompra = $this->select_ordem_compra_id();
                    if (is_array($arrOrdemCompra)){
                        $this->excluiOrdemCompraItem();
                        $this->excluiOrdemCompra();                       
                        $this->mostraOrdemCompra("Ordem de Compra excluída com sucesso!!");
                    }else{
                        $this->mostraOrdemCompra('Ordem de Compra não pode ser EXCLUÍDA.');
                    }                   
                }
                break;         
            case 'cadastrarItem': //CARRINHO
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $tipoMensagem = '';
                    $objPedidoTools = new c_ordemCompraTools();
                    $id = $this->getId();
                    $msg = $objPedidoTools->incluiItensOrdemCompraControle($this->m_empresacentrocusto, $id, $this->m_itensPedido, 
                            $this->m_itensQtde, $this->m_desconto, $tipoMensagem, $this->getCliente(), $this->getIdNatop());
                    $this->setId($id);
                    $this->desenhaCadastroOrdemCompra($msg, $tipoMensagem);
                }
                break;
            case 'excluiItem':
                if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    $tipoMensagem = '';
                    $objPedidoTools = new c_ordemCompraTools();
                    $msg = $objPedidoTools->excluiItensOrdemCompraControle($this->m_empresacentrocusto, 
                            $this->getId(), $this->getNrItem(), $tipoMensagem);
                    $this->desenhaCadastroOrdemCompra($msg);
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstGrupo', 'C')) {
                    $this->mostraOrdemCompra('');
                }
        }
    }

    function desenhaCadastroOrdemCompra($mensagem = NULL,$tipoMsg=NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('form', 'ordem_compra');
        $this->smarty->assign('itensPedido', $this->m_itensPedido);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('promocoes', 'S');

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('nrItem', $this->getId());
        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
        endif;
        $this->smarty->assign('pedido', $this->getPedido());
        $this->smarty->assign('situacao', $this->getSituacao());
        $this->smarty->assign('emissao', $this->getEmissao('F'));
        $this->smarty->assign('condPg', $this->getCondPg());
        $this->smarty->assign('desconto', $this->getDesconto('F'));
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('produtos', $this->getProdutos('F'));
        $this->smarty->assign('obs', $this->getObs());

        if ($this->getId()!=''):
            {$this->smarty->assign('totalPedido', $this->select_ordem_compra_total());}
        else:
            {$this->smarty->assign('totalPedido', '0');}
        endif;
        
     
        
        // COMBOBOX NAT OPERAÇÃO
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where tipo='S'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $natop_ids[0] = '';
        $natop_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $natop_ids[$i + 1] = $result[$i]['ID'];
            $natop_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natop_ids', $natop_ids);
        $this->smarty->assign('natop_names', $natop_names);
        $this->smarty->assign('natop_id', $this->getIdNatop());
        
        //PROMOÇÃO
        $this->smarty->assign('promocoes', $this->m_parPesq[2]);
        
        if (!empty($this->m_pesq)){
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $consultaEstoque = $parametros->getField("CONSULTAESTOQUEZERO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();                        
            
            $objProdutoQtde = new c_produto_estoque();
            if ($ItemFoiAdicionado != "S")  {
                $lancPesq = $objProdutoQtde->produtoQtdePreco($this->m_pesq, $this->m_empresacentrocusto, null, 'O');
            } else {
              $lancPesq = $objProdutoQtde->null;  
            }
            $this->smarty->assign('lancPesq', $lancPesq);
        }
        $id = $this->getId();
        if (!empty($id)){
            $lancItens = $this->select_ordem_compra_item_id();
            $this->smarty->assign('lancItens', $lancItens);
        }

        $consulta = new c_banco();
        $sql = "SELECT * FROM FAT_COND_PGTO WHERE BLOQUEADO = 'A' ORDER BY DESCRICAO;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $condPgto_ids[0] = 0;
        $condPgto_names[0] = 'Condição Pagamento';
        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i+1] = $result[$i]['ID'];
            $condPgto_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('descCondPgto', "$descCondPgto");
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPg());
        
        $this->smarty->display('ordem_compra_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraOrdemCompra($mensagem=NULL) {

        $cliente = '';
        if ($this->m_letra !=''):
            $lanc = $this->select_ordem_compra($this->m_letra);
        endif;
        
        if($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[0]);

        if($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        }
        else $this->smarty->assign('dataFim', $this->m_par[1]);

        // pessoa
        if($this->m_par[2] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            $this->smarty->assign('pessoa', $this->m_par[2]);
            $this->smarty->assign('nome', $this->getClienteNome());
        }
  
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('form', 'ordem_compra');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('ordem_compra_mostra.tpl');
    }
}

// Rotina principal - cria classe
$pedido = new p_ordem_compra();

$pedido->controle();

