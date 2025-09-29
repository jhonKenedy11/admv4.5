<?php
/** 
 * @package   adm
 * @name      p_pedido_venda_new
 * @version   4.5.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Maárcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      31/08/2020
 * 
 * direito de usuario 'PEDVERTODOSLANCAMENTOS' se sim pode ver todas as vendas.
 * se não, verá somente os seus
 * 
 * $tipovalidacao validação de permicao de desconto
 * N = Não se aplica
 * A = Percentual máximo que o vendedor por dar por item
 * M = Preco mínimo         
 * 
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/ped/c_pedido_venda_tools.php");
require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../forms/ped/p_pedido_venda_nf.php");
require_once($dir . "/../../class/fin/c_lancamento.php");

//Class P_pedido_venda
Class p_pedido_venda_new extends c_pedidoVendaNew {
            
    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_desconto     = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensPedidoCC= NULL;
    private $m_itensQtde    = NULL;
    private $m_agrupar_pedidos = NULL;
    private $id_prod_preco_min = NULL;
    public $smarty          = NULL;
            
    private $baseIcms = null;
    private $valorIcms = null;
    private $basePis = null;
    private $valorPis = null;
    private $baseCofins = null;
    private $valorCofins = null;
    private $exibirmotivo = null;    
    private $itensperdido = null;
 
    
    private $m_motivoSelecionados = null;
    private $m_condPagamentoSelecionados = null;
    private $m_vendedoresSelecionados = null;
    private $m_centroCustoSelecionados = null;
    
    
    private $m_motivo = null;
    private $m_motivo_pedido_id = null;
    private $m_status = null;
    
    private $m_letra_old        = NULL;

    
    private $m_useridconf       = NULL;
    private $m_passwordconf     = NULL;
    
    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        // session_start();
        c_user::from_array($_SESSION['user_array']);
       
        // ajax
        $this->ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");

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
        $this->m_letra_old = $parmPost['letra_old'];
        $this->m_desconto = $parmPost['desconto'];
        $this->m_itensPedido = $parmPost['itensPedido'];
        $this->m_itensPedidoCC = $parmPost['itensPedidoCC'];
        $this->m_itensQtde = $parmPost['itensQtde'];
        $this->m_agrupar_pedidos = $parmPost['agrupar_pedidos'];
        $this->m_motivoSelecionados = $parmPost['motivosSelecionados'];
        $this->m_vendedoresSelecionados = $parmPost['vendedorSelecionados'];
        $this->m_centroCustoSelecionados = $parmPost['centroCustoSelecionados'];
        $this->m_condPagamentoSelecionados = $parmPost['condPagamentoSelecionados'];
        $this->id_prod_preco_min = $parmPost['id_prod_preco_min'];
        
        $this->m_motivo = $parmPost['motivo'];
        $this->m_motivo_pedido_id = $parmPost['motivo_pedido_id'];

        $exibirmotivo = '';
        $this->exibirmotivo = $exibirmotivo;
        $this->smarty->assign('exibirmotivo', $this->exibirmotivo);
        
        //$this->exibirmotivo = $parmPost['exibirmotivo'];
        $this->itensperdido = $parmPost['itensperdido'];
        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_parPesq = explode("|", $this->m_pesq);

        if (isset($parmPost['usrautorizaconf'])){
            $this->m_useridconf            =  $parmPost['usrautorizaconf'];
        }
        if (isset($parmPost['passwordconf'])){
            $this->m_passwordconf        =  $parmPost['passwordconf'];
        }
        
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Pedidos de Vendas");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8]");
        $this->smarty->assign('disableSort', "[ 8 ]");
        $this->smarty->assign('numLine', "50");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');
        $this->setPrazoEntrega(isset($parmPost['prazoEntrega']) ? $parmPost['prazoEntrega'] : '');
        
        $this->setCondPg(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        $this->setIdNatop(isset($parmPost['natop']) ? $parmPost['natop'] : '1');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : '');
        if (isset($parmPost['pessoa'])):
            $this->setCliente($parmPost['pessoa']);
        else:    
            /*$parametros = new c_banco;
            $parametros->setTab("AMB_USUARIO");
            $cliente = $parametros->getField("CLIENTE", "USUARIO=".$this->m_userid);
            $parametros->close_connection();                        
            $this->setCliente($cliente);*/
            $this->setCliente('');
        endif;   
        $this->setFrete(isset($parmPost['frete']) ? $parmPost['frete'] : '0');
        $this->setDesconto(isset($parmPost['desconto']) ? $parmPost['desconto'] : '0');
        $this->setDespAcessorias(isset($parmPost['despAcessorias']) ? $parmPost['despAcessorias'] : '0');
        $this->setUsrFatura(isset($parmPost['usrfatura']) ? $parmPost['usrfatura'] : '');
        $this->setEmissao(isset($parmPost['emissao']) ? $parmPost['emissao'] : date("Y-m-d"));
        // complemento descricao pedido_item
        $this->setDescricaoItem(isset($parmPost['desc']) ? $parmPost['desc'] : '');
        $this->setCredito(isset($parmPost['credito']) ? $parmPost['credito'] : '0');
        $this->setTotal(isset($parmPost['totalPedido']) ? $parmPost['totalPedido'] : '0');
        if ($this->getCredito() == "") {
            $this->setCredito(0); 
        }
        $this->setUsrAprovacao(isset($parmPost['usrAprovacao']) ? $parmPost['usrAprovacao'] : '');
        // include do javascript
        //include ADMjs . "/ped/s_pedido_venda.js";
    }

    
/*
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'atualizarDataEmissao':
                break;
            case 'atualizarCCEntrega':
                break;
            case 'atualizarPrazoEntrega':
                break;
            case 'atualizarVendedor':
                break;
            case 'devolucao':
                break;
            case 'atualizarDataEntrega':
                break;
            case 'addParcelaCotacao': // pedido
                break;
            case 'atulizarInfoItem': 
                break;            
            case 'exclui': // CANCELA
                break;
            case 'motivoGeral':
                break;
            case 'NFE':
                break;
            case 'cadastrarPedido': // pedido
                break;
            case 'agruparPedidos':                
                break;
            case 'cadastrar':
                break;
            case 'alterar':
                break;
            case 'altera': // cotacao
                break;
            case 'aprovado': // cotacao
                break;
            case 'desaprovado': // desaprovado /  pedido perdido
                break;    
            case 'digita': //VOLTAR
                break;
            case 'exclui': // CANCELA
                break;
            case 'estorna': // Estorna pedido voltando para digitação..
                break;
            case 'cadastrarItem': //CARRINHO
                break;
            case 'incluiDescItem':
                break;
            case 'excluiItem':
                break;
            case 'entregue':
                break;
            case 'motivo':
                break;
            case 'itensmotivosalvar':
                break;
            case 'atualizarInfo': // CONCLUIR
                break;         
            default:
                if ($this->verificaDireitoUsuario('PedVendas', 'C')) {
                    $this->mostraPedido('');
                }
        }
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg=NULL, $ItemFoiAdicionado=NULL) {

       $msg = $mensagem; 
    //    if (($this->m_submenu != 'cadastrar')and($this->m_submenu != 'agruparPedidos') and ($this->getId() > 0)):
    //        $mensagem = $this->calculaImpostos();
    //    endif;
       
       if (strlen($mensagem) == 0) {
         $mensagem = $msg;
        }
        $this->smarty->assign('baseIcms', $this->baseIcms);
        $this->smarty->assign('valorIcms', $this->valorIcms);
        $this->smarty->assign('basePis', $this->basePis);
        $this->smarty->assign('valorPis', $this->valorPis);
        $this->smarty->assign('baseCofins', $this->baseCofins);
        $this->smarty->assign('valorCofins', $this->valorCofins);

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('letra_old', $this->m_letra_old);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('form', 'pedido_venda_telhas');
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
        $this->smarty->assign('esconderbtn','N'); 
        if (($this->getSituacao() == 6)or($this->getSituacao() == 9)){
            $this->smarty->assign('esconderbtn','S');    
        }
        $this->smarty->assign('situacao', $this->getSituacao());
        $this->smarty->assign('emissao', $this->getEmissao('F'));
        $this->smarty->assign('entregador', $this->getEntregador());
        $this->smarty->assign('usrFatura', $this->getUsrFatura());
        $this->smarty->assign('natop', $this->getIdNatop());
        $this->smarty->assign('tabPreco', $this->getTabPreco());
        $this->smarty->assign('entradaTabPreco', $this->getEntradaCondPg('F'));
        $this->smarty->assign('taxaFin', $this->getTaxaFin('F'));
        $this->smarty->assign('condPg', $this->getCondPg());
        $this->smarty->assign('entradaCondPg', $this->getEntradaCondPg('F'));
        $this->smarty->assign('vencimento1', $this->getVencimento1('F'));
        $this->smarty->assign('desconto', $this->getDesconto('F'));
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('moeda', $this->getMoeda());
        $this->smarty->assign('contaDeposito', $this->getContaDeposito());
        $this->smarty->assign('especie', $this->getEspecie());
        $this->smarty->assign('serie', $this->getSerie());
        $this->smarty->assign('emissao', $this->getEmissao('F'));
        $this->smarty->assign('horaEmissao', $this->getHoraEmissao('F'));
        $this->smarty->assign('taxaEntrega', $this->getTaxaEntrega('F'));
        $this->smarty->assign('totalRecebido', $this->getTotalRecebido('F'));
        $this->smarty->assign('prazoEntrega', $this->getPrazoEntrega());
        $this->smarty->assign('genero', $this->getGenero());
        $this->smarty->assign('filial', $this->getCentroCusto());
        $this->smarty->assign('tipoEntrega', $this->getTipoEntrega());
        $this->smarty->assign('tabelaPreco', $this->getTabelaPreco());
        $this->smarty->assign('ipi', $this->getIpi('F'));
        $this->smarty->assign('comprador', $this->getComprador());
        $this->smarty->assign('transportadora', $this->getTransportadora());
        $this->smarty->assign('tabelaVenda', $this->getTabelaVenda());
        $this->smarty->assign('usrPedido', $this->getUsrPedido());
        $this->smarty->assign('dtUltimoPedidoCliente', $this->getDtUltimoPedidoCliente('F'));
        $this->smarty->assign('usrAprovacao', $this->getUsrAprovacao());
        $this->smarty->assign('perDesconto', $this->getPerDesconto('F'));
        $this->smarty->assign('descontoNf', $this->getDesconto('F'));
        $this->smarty->assign('totalProdutos', $this->getTotalProdutos('F'));
        $this->smarty->assign('frete', $this->getFrete('F'));
        $this->smarty->assign('obs', $this->getObs());
        $this->smarty->assign('despAcessorias', $this->getDespAcessorias('F'));
        
        // campos de pesquisa de produtos
        $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
        $this->smarty->assign('pesLocalizacao', $this->m_parPesq[3]);
        if ($ItemFoiAdicionado != null) {
            if ($ItemFoiAdicionado = "S")  {
                $this->smarty->assign('pesProduto', "");
                $this->smarty->assign('pesLocalizacao',"");            
            } else {
                $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
                $this->smarty->assign('pesLocalizacao', $this->m_parPesq[3]);
            }
        }
        $validarDescontoGeral = 'N';
        if ($this->getId()!=''):
            {
                
                $total = $this->select_totalPedido() +
                $this->getFrete() + 
                $this->getDespAcessorias() - $this->getDesconto();
                $this->smarty->assign('totalPedido', $total);
                $perDesconto = (($this->getDesconto('F') / ($total + $this->getDesconto('F'))) * 100);
                $this->smarty->assign('perDesconto', $perDesconto);
                
                
                $banco = new c_banco();
                $sql = "SELECT DESCONTOMAXIMO FROM FAT_PARAMETRO ";
                $sql.= "WHERE (FILIAL=".$this->m_empresacentrocusto.")";
                $resul = $banco->exec_sql($sql);
                $desconto = $resul[0]['DESCONTOMAXIMO'];
                
                if ($desconto > 0) {
                    if ($perDesconto > $desconto ){
                        $validarDescontoGeral = 'S';
                    }
                    $permiteAprovarDesconto = $this->verificaDireitoUsuario('PEDPERMITEAPROVARDESCONTO', 'S', 'N');
                    if ($permiteAprovarDesconto == false) {
                        $this->setUsrAprovacao(null);
                    }
                }
                
            }
        else:
            {$this->smarty->assign('totalPedido', '0');}
        endif;
        $this->smarty->assign('validarDescontoGeral', $validarDescontoGeral);
        
        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $this->getSituacao());
        $situacao = ($this->getSituacao()); 
        if ($situacao == ''){
            $situacao = '0';
        }
        $this->smarty->assign('situacao', $situacao);

        $controlarStatusTela = $this->verificaDireitoUsuario('PEDCONTROLARSTATUSTELA', 'S', 'N');
        $this->smarty->assign('controlarStatusTela', $controlarStatusTela);        
        
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

        // COMBOBOX CONDICAO PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $condPgto_ids[0] = 0;
        $condPgto_names[0] = 'Condição Pagamento';
        for ($i = 0; $i < count($result); $i++) {
//            if ($this->getCondPg()==$result[$i]['ID']):
//                $descCondPgto = $result[$i +1]['DESCRICAO'];
//            endif;
            $condPgto_ids[$i+1] = $result[$i]['ID'];
            $condPgto_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('descCondPgto', $descCondPgto);
        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPg());

        
        // COMBOBOX GRUPO
        $consulta = new c_banco();
        $sql = "select grupo id, descricao from est_grupo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $grupo_ids[0] = '';
        $grupo_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + 1] = $result[$i]['ID'];
            $grupo_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);
        if ($this->m_parPesq[1] == "")
            $this->smarty->assign('grupo_id', 'Todos');
        else
            $this->smarty->assign('grupo_id', $this->m_parPesq[1]);
        

        //PROMOÇÃO
        $this->smarty->assign('promocoes', $this->m_parPesq[2]);
        
        if (!empty($this->m_pesq)){
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $consultaEstoque = $parametros->getField("CONSULTAESTOQUEZERO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();                        
            
            $objProdutoQtde = new c_produto_estoque();
            // if (($ItemFoiAdicionado != "S") and ($this->m_pesq !='|||')) {
            if ($this->m_pesq !='|||') {
                    $lancPesq = $objProdutoQtde->produtoQtdePreco($this->m_pesq, $this->m_empresacentrocusto, null, $consultaEstoque );
            } else {
              $lancPesq = $objProdutoQtde->null;  
            }
//            $lancPesq = $this->select_pedido_venda_item_letra($this->m_pesq);
            $this->smarty->assign('lancPesq', $lancPesq);
        }
        $id = $this->getId();
        
        //array com os itens adicionados
        $itens = array();

        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $tipovalidacao = $parametros->getField("TIPOVALIDACAO", "FILIAL=".$this->m_empresacentrocusto);
        $percdescmaximo = $parametros->getField("PERCDESCMAXIMO", "FILIAL=".$this->m_empresacentrocusto);
        $parametros->close_connection();   


        if (!empty($id)){
            $lancItens = $this->select_pedido_item_id();
            $this->smarty->assign('lancItens', $lancItens);
            if (count($lancItens) > 0) {
                for ($i = 0; $i < count($lancItens); $i++) {
                        array_push($itens, $lancItens[$i]['ITEMESTOQUE']); 
                }           
            } 
                
            if ( $tipovalidacao != 'N') {
                //incremento quando o produto esta abaixo do preco de venda
                $this->id_prod_preco_min = "";          
                for($i=0;$i < count($lancItens); $i++){  
                    if ($tipovalidacao = "M") {
                        if($lancItens[$i]['PRECOMINIMO'] > $lancItens[$i]['UNITARIO'])                                
                            $this->id_prod_preco_min = $this->id_prod_preco_min."|".$lancItens[$i]['ITEMFABRICANTE'];
                    } else if ($tipovalidacao = "A") {
                        if ($percdescmaximo > 0) { //percentual máximo de desconto
                            $percItem = $lancItens[0]['UNITARIO'] / $lancItens[0]['PRECOMINIMO'];
                            if ($percItem < 1) {
                                $percItem = (1 - $percItem) * 100;
                            }
                            if($percdescmaximo < $percItem )                                
                                $this->id_prod_preco_min = $this->id_prod_preco_min."|".$lancItens[$i]['ITEMFABRICANTE'];    
                        }                    
                    } // ($tipovalidacao = "A")                                     
                }
                $this->smarty->assign('id_prod_preco_min', $this->id_prod_preco_min);
            } else {
                $this->smarty->assign('id_prod_preco_min', '');
            }
        } else {
            $this->smarty->assign('id_prod_preco_min', '');
        }

        //**** Sequencia pesquisa Modal ****
        $this->copiarEcolar();
        
        $str=implode("|",array_unique($itens));
        $this->smarty->assign('str', $str); 

        //QUANTIDADE
        if (empty($this->m_itensQtde)){
            $this->smarty->assign('itensQtde', 1);
        }else{
            $this->smarty->assign('itensQtde', $this->m_itensQtde);
        }

        // ########## CENTROCUSTO ##########
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo order by centrocusto";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $centroCusto_ids[0] = '';
        $centroCusto_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $centroCusto_ids[$i + 1] = $result[$i]['ID'];
            $centroCusto_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);  
        
        // BUSCA PARAMETROS CENTRO CUSTO
        $cCusto = $this->getCentroCusto();
        if ($cCusto == null) { 
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $cCusto = $parametros->getField("CENTROCUSTO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();
        }    

        $this->smarty->assign('centroCusto_id', $cCusto);
        
        // COMBOBOX VENDEDOR
        $consulta = new c_banco();
        //$sql = "SELECT USUARIO, NOME FROM AMB_USUARIO WHERE TIPO='V'";
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
        $sql.= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' )";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $usrfatura_ids[$i + 1] = $result[$i]['USUARIO'];
            $usrfatura_names[$i] = $result[$i]['NOME'];
        }
        
        $this->smarty->assign('usrfatura_ids', $usrfatura_ids);
        $this->smarty->assign('usrfatura_names', $usrfatura_names);
        if ($validarDescontoGeral = 'S') {
            $this->smarty->assign('usrautoriza_ids', $usrfatura_ids);
            $this->smarty->assign('usrautoriza_names', $usrfatura_names);        
        }

        $permiteAlterarCusto = $this->verificaDireitoUsuario('PEDPERMITEALTERARCUSTO', 'S', 'N');
        $this->smarty->assign('permiteAlterarCusto', $permiteAlterarCusto);

        $permiteAlterarValor = $this->verificaDireitoUsuario('PEDPERMITEALTERARVALOR', 'S', 'N');
        $this->smarty->assign('permiteAlterarValor', $permiteAlterarValor);

        $permiteAlterarVendedor = $this->verificaDireitoUsuario('PEDPERMITEALTERARVENDEDOR', 'S', 'N');
        $this->smarty->assign('permiteAlterarVendedor', $permiteAlterarVendedor);

        $permiteGerarBonus = $this->verificaDireitoUsuario('PEDPERMITEGERARBONUS', 'S', 'N');
        $this->smarty->assign('permiteGerarBonus', $permiteGerarBonus);

        $permiteDigitarCodigo = $this->verificaDireitoUsuario('PEDPERMITEDIGITARCODIGO', 'S', 'N');
        $this->smarty->assign('permiteDigitarCodigo', $permiteDigitarCodigo);

        // BOOLEAN ##############################
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='BOOLEAN')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
                $boolean_ids[$i] = $result[$i]['ID'];
                $boolean_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('boolean_ids', $boolean_ids);
        $this->smarty->assign('boolean_names', $boolean_names);
        
        if ($this->getUsrFatura() > 0 ) {
            $this->smarty->assign('usrfatura', $this->getUsrFatura());
        } else {
            $this->smarty->assign('usrfatura', $this->m_userid);
        }
        
        
        $credito = $this->getCredito();
        if ($credito > 0 ){
            $this->smarty->assign('credito', $this->getCredito());
            $this->smarty->assign('exibircredito', 'S');
        } else {
            $this->smarty->assign('exibircredito', 'N');
        }
    
        $this->smarty->assign('usrAprovacao', $this->getUsrAprovacao());
        
        $this->smarty->assign('sistema', ADMSistema);
    
        $this->smarty->display('pedido_venda_telhas_cadastro.tpl');
    }

//fim desenhaCadgrupo

//---------------------------------------------------------------
//---------------------------------------------------------------
function comboSql($sql, $par, &$id, &$ids, &$names) {
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i = 0; $i < count($result); $i++) {
        $ids[$i] = $result[$i]['ID'];
        $names[$i] = $result[$i]['DESCRICAO'];
    }
    
    $param = explode(",", $par);
    $i=0;
    $id[$i] = "0";
    while ($param[$i] != '') {
        $id[$i] = $param[$i];
        $i++;
    }    
}


//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem=NULL) {

        //$cliente = $this->getCliente();
        $cliente = '';
        //$this->m_letra = "||".$cliente."||0|1|2|3|4";
        $this->m_letra_old = $this->m_letra;
        if ($this->m_letra !=''):
            $lanc = $this->select_pedidoVenda_letra($this->m_letra, $this->m_motivoSelecionados);
            
            $par = explode("|", $this->m_letra);
            $usuario = explode(",", $par[5]);

            if ((count($usuario) == 1)and($usuario[0] != '')) {

                $data = "";
                $labels = "";

                $result = $this->select_pedidoVenda_usuario($usuario[0], $par[0], $par[1]);
            
                $bck = ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"];
                for ($i = 0; $i < count($result); $i++) {
                    if ($i > 0 ){
                        $dados .= ",";
                        $labels .= ",";
                        $bckgroundColor .= ",";  
                    }
                    $dados .= str_replace(',', '', number_format($result[$i]['TOTAL'],2));

                    //$bckgroundColor .= " '" .bck[$i]. "' ";
                    $bckgroundColor .= " '" .$bck[$i]. "' ";
                    $labels .= "'".$result[$i]['PADRAO']."'";
                }
                $this->smarty->assign('bckgroundColor', $bckgroundColor); 
        
                $this->smarty->assign('dados', $dados); 
                
                $this->smarty->assign('labels', $labels); 

            }
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

        // COMBOBOX SITUACAO
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') ";
        
        if (ADMSistema != 'PECAS') {
            $sql .= " AND ((TIPO = 0) or (TIPO = 5) or (TIPO = 6) or (TIPO = 7) or (TIPO = 9) or (TIPO = 10))";
        }        
        
    // COMBOBOX SITUACAO
    if($this->m_par[4] == "") $this->m_par[4] = '5';

        $this->comboSql($sql, $this->m_par[4], $situacao_id, $situacao_ids, $situacao_names);
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $situacao_id);
        
        $situacao_id = $param_id;
        if (count($situacao_id) == 1) {
          $agruparPedidosSituacao = $situacao_id[0];
        } else {
          $agruparPedidosSituacao = 0;
        }
        $permiteAgruparPedidos = $this->verificaDireitoUsuario('PEDPERMITEAGRUPARPEDIDOS', 'S');
        
        $permiteAprovarPedidos = $this->verificaDireitoUsuario('PEDPERMITEAPROVARPEDIDOS', 'S', 'N');
        
        // pessoa
        if($this->m_par[2] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            
            //$this->smarty->assign('pessoa', $this->m_par[2]);
            //$this->smarty->assign('nome', $this->getClienteNome());
        }
        
        // produto
        if($this->m_par[3] == "") $this->smarty->assign('codProduto', "");
        else {
            $arrProduto = "";
            $objProduto = new c_produto();
            $objProduto->setId($this->m_par[3]);
            $arrProduto = $objProduto->select_produto();
            $objProduto->setDesc($arrProduto[0]["DESCRICAO"]);
          //  $this->smarty->assign('codProduto', $this->m_par[4]);
          //  $this->smarty->assign('descProduto', $objProduto->getDesc());
        }
        
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('form', 'pedido_venda_telhas');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('agruparPedidosSituacao', $agruparPedidosSituacao);
        $this->smarty->assign('permiteAgruparPedidos', $permiteAgruparPedidos); 
        $this->smarty->assign('permiteAprovarPedidos', $permiteAprovarPedidos); 

        // COMBOBOX MOTIVO
        $sql = "SELECT MOTIVO AS ID, DESCRICAO FROM FAT_MOTIVO";
        $this->comboSql($sql, $this->m_par[8], $motivo_id, $motivo_ids, $motivo_names);
        $this->smarty->assign('motivo_ids', $motivo_ids);
        $this->smarty->assign('motivo_names', $motivo_names);
        $this->smarty->assign('motivo_id', $motivo_id);

        // ########## CENTROCUSTO ##########
        $verSomenteInfoDaLoja = $this->verificaDireitoUsuario('PEDVERSOMENTEINFODALOJA', 'S', 'N');
        $cWhere = '';
        if ($verSomenteInfoDaLoja) {
            $cWhere = 'where centrocusto = '.$this->m_empresacentrocusto;
        }
        $sql = "select centrocusto as id, descricao from fin_centro_custo ".$aliqRegEspSTMTcWhere." order by centrocusto";
        $this->comboSql($sql, $this->m_par[7] ?? $this->m_empresacentrocusto, $centroCusto_id, $centroCusto_ids, $centroCusto_names);
        $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
        $this->smarty->assign('centroCusto_names', $centroCusto_names);  
        $this->smarty->assign('centroCusto_id', $centroCusto_id); 
        $this->smarty->assign('verSomenteInfoDaLoja',$verSomenteInfoDaLoja); 


        // COMBOBOX VENDEDOR
        $vertodoslancamentos = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');
        $this->smarty->assign('vertodoslancamentos',$vertodoslancamentos); 
        if($vertodoslancamentos == false){
            $vendedor = $this->verifica_vendedor();            
            $this->smarty->assign('vendedor_ids',   $vendedor[0]['USUARIO']);
            $this->smarty->assign('vendedor_names', $vendedor[0]['NOME']);
            $this->smarty->assign('vendedor_id', $vendedor[0]['USUARIO']);
        }else{
            //$sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO WHERE TIPO = 'V'";
            $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO ";
            $this->comboSql($sql, $this->m_par[5], $vendedor_id, $vendedor_ids, $vendedor_names);
            $this->smarty->assign('vendedor_id', $vendedor_id);
            $this->smarty->assign('vendedor_ids',   $vendedor_ids);
            $this->smarty->assign('vendedor_names', $vendedor_names);
        } 

        //COMBOBOX Cond Pagamento
        $sql = "SELECT * FROM FAT_COND_PGTO;";
        $this->comboSql($sql, $this->m_par[6], $condPag_id, $condPag_ids, $condPag_names);
        $this->smarty->assign('condPag_id', $condPag_id);
        $this->smarty->assign('condPag_ids',   $condPag_ids);
        $this->smarty->assign('condPag_names', $condPag_names);

        //COMBOBOX USR Fatura
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO ";
        $this->comboSql($sql, $this->m_par[5], $usrfatura_id, $usrfatura_ids, $usrfatura_names);
        $this->smarty->assign('usrfatura_id', $usrfatura_id);
        $this->smarty->assign('usrfatura_ids',   $usrfatura_ids);
        $this->smarty->assign('usrfatura_names', $usrfatura_names);

        $permiteEstornarPedido = $this->verificaDireitoUsuario('PEDPERMITEESTORNARPEDIDO', 'S', 'N');
        $this->smarty->assign('permiteEstornarPedido', $permiteEstornarPedido);
        $this->smarty->assign('sistema',ADMSistema); 
        $this->smarty->display('pedido_venda_telhas_mostra.tpl');
    }
//fim mostragrupos
//-------------------------------------------------------------
}
//	END OF THE CLASS

// php 7 ==>$email = $_POST['email'] ?? 'valor padrão';
// php 5 ==>$email = isset($_POST['email']) ? $_POST['email'] : 'valor padrão';

// Rotina principal - cria classe
$pedido = new p_pedido_venda_new();

$pedido->controle();    