<?php

/**
 * @package   astec
 * @name      p_contas_oportunidade
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      15/01/2016
 */

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')):
    exit;
endif;
   
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
//include_once($dir . "/../../class/crm/c_contas_oportunidade.php");

include_once($dir . "/../../class/ped/c_pedido_venda.php");
include_once($dir . "/../../class/crm/c_proposta.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../bib/c_date.php");

Class p_contas_oportunidade extends c_pedidoVenda {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_opcao = NULL;
    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct($submenu, $letra, $opcao) {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);
        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);


        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/crm";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_opcao = $parmPost['opcao'];
        $this->m_letra = $parmPost['letra'];        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Contas - Acompanhamento");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
        $this->smarty->assign('disableSort', "[ 6 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setEmissao(isset($parmPost['data']) ? $parmPost['data'] : '');
        $this->setDataAlteracao(isset($parmPost['dataAlteracao']) ? $parmPost['dataAlteracao'] : '');
        $this->setProtocolo(isset($parmPost['protocolo']) ? $parmPost['protocolo'] : '');//
        $this->setVendedor(isset($parmPost['vendedor']) ? $parmPost['vendedor'] : '');//
        $this->setSituacaoOportunidade(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');//
        $this->setCliente(isset($parmPost['cliente']) ? $parmPost['cliente'] : '');
        $this->setComprador(isset($parmPost['comprador']) ? $parmPost['comprador'] : '');
        $this->setCondPgto(isset($parmPost['condpgto']) ? $parmPost['condpgto'] : '');//
        $this->setFormaPgto(isset($parmPost['formapgto']) ? $parmPost['formapgto'] : '');//
        $this->setCCusto(isset($parmPost['filial']) ? $parmPost['filial'] : '');//
        $this->setTotal(isset($parmPost['total']) ? $parmPost['total'] : '');
        $this->setOs(isset($parmPost['os']) ? $parmPost['os'] : '');//
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        
        // Itens Pedido
        $this->setNrItem(isset($parmPost['iditem']) ? $parmPost['iditem'] : '');
        $this->setProduto(isset($parmPost['codProduto']) ? $parmPost['id'] : '');//
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['codProduto'] : '');//
        $this->setQuantidade(isset($parmPost['quantidade']) ? $parmPost['quantidade'] : '');
        $this->setUnitario(isset($parmPost['unitario']) ? $parmPost['unitario'] : '');
        $this->setFinanceiro(isset($parmPost['financeiro']) ? $parmPost['financeiro'] : '');
        $this->setTotalItem(isset($parmPost['totalitem']) ? $parmPost['totalitem'] : '');

        // Itens Pedido Composicao
        $this->setNrItemComp(isset($parmPost['iditem']) ? $parmPost['iditem'] : '');
        $this->settemEstoque(isset($parmPost['codProduto']) ? $parmPost['codProduto'] : '');
        $this->setQtPedido(isset($parmPost['qtpedido']) ? $parmPost['qtpedido'] : '');
        $this->setCustoUnitario(isset($parmPost['custounitario']) ? $parmPost['custounitario'] : '');
        $this->setDespesas(isset($parmPost['despesas']) ? $parmPost['despesas'] : '');
        $this->setCustoTotal(isset($parmPost['custototal']) ? $parmPost['custototal'] : '');
        $this->setItemPedido(isset($parmPost['item']) ? $parmPost['item'] : '');
        $this->setFornecedor(isset($parmPost['fornecedor']) ? $parmPost['fornecedor'] : '');
        $this->setOrdemCompra(isset($parmPost['ordemcompra']) ? $parmPost['ordemcompra'] : '');

        
        // include do javascript
        // include ADMjs . "/crm/s_contas_oportunidade.js";
    }

    /**
     * Sets dos atributos da classe com os valores do BD
     * @name setDadosProposta
     */
    public function setDadosProposta(){
        $parametro = $this->select_dadosProposta($this->m_empresacentrocusto);
        $this->setApresentacao($parametro[0]['APRESENTACAO']);
        $this->setObjetivo($parametro[0]['OBJETIVO']);
        $this->setGarantia($parametro[0]['GARANTIA']);
        $this->setImpostos($parametro[0]['IMPOSTOS']);
        $this->setValidade($parametro[0]['VALIDADE']);
        $this->setAceite($parametro[0]['ACEITE']);
        $this->setPrazoEntrega($parametro[0]['PRAZOENTREGA']);
        $this->setObs($parametro[0]['OBS']);
    }
    /**
     * Sets dos atributos da classe com os valores do BD
     * @name buscaCadastroVenda
     */
    public function buscaCadastroOportunidade(){
        $pedidoVenda = $this->select_pedidoVenda();
        $this->setId($pedidoVenda[0]['ID']);
        $this->setEmissao($pedidoVenda[0]['EMISSAO']);
        $this->setSituacaoOportunidade($pedidoVenda[0]['SITUACAOOPORTUNIDADE']);
        $this->setNumOportunidade($pedidoVenda[0]['NUMOPORTUNIDADE']);
        $this->setDataAlteracao($pedidoVenda[0]['DATAALTERACAO']);
        $this->setVendedor($pedidoVenda[0]['USRPEDIDO']);
        $this->setCliente($pedidoVenda[0]['CLIENTE']);
        $this->setClienteNome();
        $this->setComprador($pedidoVenda[0]['COMPRADOR']);
        $this->setCondPgto($pedidoVenda[0]['CONDPG']);
        $this->setFormaPgto($pedidoVenda[0]['ESPECIE']);
        $this->setEntrega($pedidoVenda[0]['DATAENTREGA']);
        $this->setCCusto($pedidoVenda[0]['CCUSTO']);
        $this->setOs($pedidoVenda[0]['OS']);
        $this->setTotal($pedidoVenda[0]['TOTAL']);
        $this->setObs($pedidoVenda[0]['OBS']);
    }	
    

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastraritemcomp':
                if ($this->verificaDireitoUsuario('FatPedido', 'I')) {
                    $this->setPedidoVenda();
                    $this->setQtPedido('1');
                    $this->cadastroPedidoVendaComposicao('');
                }
                break;
            case 'incluiitemcomp':
                if ($this->verificaDireitoUsuario('FatPedido', 'I')) {
                    $this->incluiPedidoItemComp();
                    $this->setPedidoVenda();
                    $this->desenhaCadastroOportunidade('Item salvo.', 'sucesso');
                }
                break;
            case 'alteraitemcomp':
                if ($this->verificaDireitoUsuario('FatPedido', 'A')){
                    $this->alteraPedidoItemComp();
                    $this->setPedidoVenda();
                    $this->desenhaCadastroOportunidade('Item salvo.', 'sucesso');
                }
                break;
            case 'alteraritemcomp':
                    if ($this->verificaDireitoUsuario('FatPedido', 'A')){
                        $pedidoVendaItemComp = $this->select_pedidoVendaItemComp();
                        $this->setId($pedidoVendaItemComp[0]['ID']);
                        $this->setNumItemComp($pedidoVendaItemComp[0]['NRITEM']);
                        $this->setItemPedido($pedidoVendaItemComp[0]['ITEMPEDIDO']);
                        $this->setItemEstoque($pedidoVendaItemComp[0]['ITEMESTOQUE']);
                        $this->setEstoqueDescComp();
                        $this->setQtPedido($pedidoVendaItemComp[0]['QTPEDIDO']);
                        $this->setCustoUnitario($pedidoVendaItemComp[0]['CUSTOUNITARIO']);
                        $this->setDespesas($pedidoVendaItemComp[0]['DESPESAS']);
                        $this->setCustoTotal($pedidoVendaItemComp[0]['CUSTOTOTAL']);
                        $this->setFornecedor($pedidoVendaItemComp[0]['FORNECEDOR']);
                        $this->setOrdemCompra($pedidoVendaItemComp[0]['ORDEMCOMPRA']);
                        $this->cadastroPedidoVendaComposicao('');
                    }
                break;
            case 'excluiitemcomp':
                if ($this->verificaDireitoUsuario('FatPedido', 'E')){
                    $this->excluiPedidoItemComp();
                    $this->setPedidoVenda();
                    $this->cadastroPedidoVenda('Item Composição deletado.', 'sucesso');
                }
                break;
            case 'cadastraritem':
                if ($this->verificaDireitoUsuario('FatPedido', 'I')) {
                    $this->setPedidoVenda();
                    $this->setQuantidade('1');
                    $this->cadastroPedidoVendaItem('');
                }
                break;
            case 'incluiitem':
                if ($this->verificaDireitoUsuario('FatPedido', 'I')) {
                    $this->incluiPedidoItem();
                    $this->setPedidoVenda();
                    $this->desenhaCadastroOportunidade('Item salvo.', 'sucesso');
                }
                break;
            case 'alteraritem':
                if ($this->verificaDireitoUsuario('FatPedido', 'A')){
                    $pedidoVendaItem = $this->select_pedidoVendaItem();
                    $this->setId($pedidoVendaItem[0]['ID']);
                    $this->setNumItem($pedidoVendaItem[0]['NRITEM']);
                    $this->setProduto($pedidoVendaItem[0]['ITEMESTOQUE']);
                    $this->setDescricao($pedidoVendaItem[0]['DESCRICAO']);
                    $this->setQuantidade($pedidoVendaItem[0]['QTSOLICITADA']);
                    $this->setUnitario($pedidoVendaItem[0]['UNITARIO']);
                    $this->setFinanceiro($pedidoVendaItem[0]['FINANCEIRO']);
                    $this->setTotalItem($pedidoVendaItem[0]['TOTAL']);
                    $this->cadastroPedidoVendaItem('');
                }
                break;
            case 'alteraitem':
                if ($this->verificaDireitoUsuario('FatPedido', 'A')){
                    $this->alteraPedidoItem();
                    $this->setPedidoVenda();
                    $this->desenhaCadastroOportunidade('Item Salvo','sucesso');}
                    break;
            case 'excluiitem':
                if ($this->verificaDireitoUsuario('FatPedido', 'E')){
                    $this->excluiPedidoItem();
                    $this->setPedidoVenda();
                    $this->desenhaCadastroOportunidade('Item Deletado','sucesso');}
                    break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    $this->setEmissao(date("Y/m/d"));
                    $this->setDataAlteracao(date("Y/m/d"));
                    $this->setCCusto($this->m_empresacentrocusto);
                    $this->setVendedor($this->m_userid);
                    $this->setTotal('0');
                    $this->setSituacaoOportunidade('1');
                    $this->desenhaCadastroOportunidade('');
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    $this->buscaCadastroOportunidade();
                    $this->setDataAlteracao(date("Y/m/d"));
                    $this->desenhaCadastroOportunidade('');
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    $this->mostraOportunidade('Registro salvo, número do pedido: ' . $this->alteraOportunidade());
                }
                break;
            case 'liberarOportunidade':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    if ($this->select_situacao_oportunidade_fechado()){
                        $this->desenhaCadastroOportunidade('Oportunidade já esta finalizado, não sendo possivel alterações.','alerta');
                    }else{
                        $this->alteraSituacaoOportunidade('9'); // situacao perdida
                        $this->buscaCadastroOportunidade();
                        $this->desenhaCadastroOportunidade('Oportunidade aceita, a solicitação estará liberado em vendas!','sucesso');
                    }                    
                }
                break;
            case 'cancelaOportunidade':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    if ($this->select_situacao_oportunidade_fechado()){
                        $this->desenhaCadastroOportunidade('Oportunidade já esta finalizado, não sendo possivel alterações.','alerta');
                    }else{
                        $this->alteraSituacaoOportunidade('P'); // situacao perdida
                        $this->buscaCadastroOportunidade();
                        $this->desenhaCadastroOportunidade('Oportunidade foi Perdida!','alerta');
                    }
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('FinCliente', 'I')) {
                    $idPedido = $this->incluiOportunidade();
                    $parametrosOBJ = new c_banco;
                    $propostaOBJ = new c_proposta();
                    
                    $parametrosOBJ->setTab("FAT_PARAMETRO");
                    $propostaOBJ->setId($idPedido);
                    $propostaOBJ->setVersao('1');
                    $propostaOBJ->setApresentacao($parametrosOBJ->getParametros("APRESENTACAO"));
                    $propostaOBJ->setObjetivo($parametrosOBJ->getParametros("OBJETIVO"));
                    $propostaOBJ->setItem('');
                    $propostaOBJ->setCondPgto('');
                    $propostaOBJ->setGarantia($parametrosOBJ->getParametros("GARANTIA"));
                    $propostaOBJ->setImposto($parametrosOBJ->getParametros("IMPOSTOS"));
                    $propostaOBJ->setPrazoEntrega($parametrosOBJ->getParametros("PRAZOENTREGA"));
                    $propostaOBJ->setValidade($parametrosOBJ->getParametros("VALIDADE"));
                    $propostaOBJ->setAceite($parametrosOBJ->getParametros("ACEITE"));
                    $propostaOBJ->setUserResp($this->m_userid);
                    $propostaOBJ->setSituacao('A');
                    $propostaOBJ->setData(date("Y-m-d"));
                    $propostaOBJ->incluiProposta();
                    $parametrosOBJ->close_connection();
                    $this->setNumOportunidade($this->select_max_num_oportunidade());
                    $this->mostraOportunidade('Registro salvo, número do pedido: ' . $idPedido);
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('FinCliente', 'C')) {
                    $this->mostraOportunidade('');
                }
        }
    }

function cadastroPedidoVendaComposicao($mensagem = NULL, $tipoMsg = null) {
        include $this->js . "/ped/s_pedido_venda.js";


        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('iditem', $this->getNumItemComp());
        $this->smarty->assign('ordemcompra', $this->getOrdemCompra());
        $this->smarty->assign('codProduto', $this->getItemEstoque());
        $this->smarty->assign('descricao', "'".$this->getEstoqueDescComp()."'");
        if ($this->getItemEstoque() !=""){
            $this->setEstoqueDescComp();
        }
        // FORNECEDOR ####################
        $consulta = new c_banco();
        $sql = "SELECT CLIENTE AS ID, nome AS DESCRICAO FROM FIN_CLIENTE WHERE CLASSE = 04";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $fornecedor_ids[$i] = $result[$i]['ID'];
            $fornecedor_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('fornecedor_ids', $fornecedor_ids);
        $this->smarty->assign('fornecedor_names', $fornecedor_names);
        $this->smarty->assign('fornecedor_id', $this->getFornecedor());
        
        // ITEM ####################
        $consulta = new c_banco();
        $sql = "select itemestoque as id, descricao from fat_pedido_item where id = ".$this->getId();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $item_ids[$i] = $result[$i]['ID'];
            $item_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('item_ids', $item_ids);
        $this->smarty->assign('item_names', $item_names);
        $this->smarty->assign('item_id', $this->getItemPedido());
        
        $this->smarty->assign('qtpedido', $this->getQtPedido('F'));
        $this->smarty->assign('custounitario', $this->getCustoUnitario('F'));
        $this->smarty->assign('despesas', $this->getDespesas('F'));
        $this->smarty->assign('custototal', $this->getCustoTotal('F'));
        
        $this->smarty->assign('cliente', $this->getCliente());
        if ($this->getCliente() != ""):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome() );
        endif;
        
        
        $this->smarty->display('comp_pedido_venda_cadastro.tpl');
    }
    
    function cadastroPedidoVendaItem($mensagem = NULL, $tipoMsg = null) {
        include $this->js . "/ped/s_pedido_venda.js";


        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('iditem', $this->getNumItem());
        $this->smarty->assign('codProduto', $this->getProduto());
        if ($this->getProduto() !=""){
            $this->setProdutoDesc();
            $this->smarty->assign('descricao', "'".$this->getProdutoDesc()."'");
        }
        $this->smarty->assign('quantidade', $this->getQuantidade('F'));
        $this->smarty->assign('unitario', $this->getUnitario('F'));
        $this->smarty->assign('financeiro', $this->getFinanceiro('F'));
        $this->smarty->assign('totalitem', $this->getTotalItem('F'));
        
        $this->smarty->assign('cliente', $this->getCliente());
        if ($this->getCliente() != ""):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome() );
        endif;
        
        
        $this->smarty->display('item_pedido_venda_cadastro.tpl');
    }
    
/**
 * <b> Desenha cadastro Oportunidade. </b>
 * @param String $mensagem mensagem que ira apresentar na tela
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
    function desenhaCadastroOportunidade($mensagem = NULL, $tipoMsg = null) {
        include $this->js . "/ped/s_contas_oportunidade.js";


        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());


        // SITUACAO ####################
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Oportunidade')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $this->getSituacaoOportunidade());


        // FILIAL ####################
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where ativo='s'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        $this->smarty->assign('filial_id', $this->getCCusto());

        // VENDEDOR ####################
        $consulta = new c_banco();
        $sql = "select usuario as id, nomereduzido as descricao from amb_usuario where (situacao = 'A') and (tipo<>'O') order by nomereduzido";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $vendedor_ids[$i] = $result[$i]['ID'];
            $vendedor_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('vendedor_ids', $vendedor_ids);
        $this->smarty->assign('vendedor_names', $vendedor_names);
        $this->smarty->assign('vendedor_id', $this->getVendedor());

        // COND. PAGAMENTO ####################
        $consulta = new c_banco();
        $sql = "select id, descricao from fat_cond_pgto order by descricao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $condpgto_ids[$i] = $result[$i]['ID'];
            $condpgto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('condpgto_ids', $condpgto_ids);
        $this->smarty->assign('condpgto_names', $condpgto_names);
        $this->smarty->assign('condpgto_id', $this->getCondPgto());

        

        $this->smarty->assign('data', $this->getEmissao('F'));
        $this->smarty->assign('dataAlteracao', $this->getDataAlteracao('F'));
        $this->smarty->assign('cliente', $this->getCliente());
        if ($this->getCliente() != ""):
            $this->setClienteNome();
            $this->smarty->assign('nome', "'" . $this->getClienteNome() . "'");
        endif;
        $this->smarty->assign('os', $this->getOs());
        $this->smarty->assign('comprador', $this->getComprador());
        $this->smarty->assign('total', $this->getTotal('F'));
        $this->smarty->assign('protocolo', $this->getProtocolo());


        $this->smarty->assign('obs', $this->getObs());

        if ($this->m_submenu != 'cadastrar'){
            $pessoaOBJ = new c_pessoa();
            $pessoaOBJ->setId($this->getCliente());
            $lancAcomp = $pessoaOBJ->select_pessoaAcomp_geral();
            $lancItem = $this->select_pedidoVendaItem_geral();
            $lancComp = $this->select_pedidoVendaItemComp_geral();
            $this->smarty->assign('lancItem', $lancItem);
            $this->smarty->assign('lancComp', $lancComp);
            $this->smarty->assign('lancAcomp', $lancAcomp);
            
        }
        
        
        
        $this->smarty->display('contas_oportunidade_cadastro.tpl');
    }

/**
 * <b> Listagem das Oportunidade. </b>
 * @param String $mensagem Mensagem que ira mostrar na tela
 */
    function mostraOportunidade($mensagem = NULL) {
        include $this->js . "/ped/s_contas_oportunidade.js";

        if (($this->m_letra != "") && ($this->m_letra != "||||||")) {
            $lanc = $this->select_oportunidade_letra($this->m_letra);
        }
        for($i=0;$i<count($lanc);$i++){
            if ($lanc[$i]['DATAALTERACAO'] != ''){
                $dateOBJ = new c_date;
                $lanc[$i]['ALERTA'] = $dateOBJ->DataDif($lanc[$i]['DATAALTERACAO'], date("Y/m/d"), 'd');
                //ECHO $lanc[$i]['ALERTA']."<BR>";
            }
            
        }

        //########### FILTROS DE PESQUISA ###########
        $this->smarty->assign('nome', $this->m_par[2]);

        if ($this->m_par[0] == "")
            $this->smarty->assign('dataIni', date("01/m/Y"));
        else
            $this->smarty->assign('dataIni', $this->m_par[0]);
        if ($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = mktime(0, 0, 0, $mes + 1, 0, $ano);
            $this->smarty->assign('dataFim', date("d/m/Y", $data));
            //	$data = mktime(0, 0, 0, $mes, 1, $ano);
            //	$this->smarty->assign('dataFim', date("d",$data-1).date("/m/Y"));
        } else {
            $this->smarty->assign('dataFim', $this->m_par[1]);
        }


        // ***** VENDEDOR
        $consulta = new c_banco();
        $sql = "select usuario as id, nomereduzido as descricao from amb_usuario where (situacao = 'A') and (tipo<>'O') order by nomereduzido";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $vendedor_ids[0] = 0;
        $vendedor_names[0] = 'Todos.';
        for ($i = 0; $i < count($result); $i++) {
            $vendedor_ids[$i + 1] = $result[$i]['ID'];
            $vendedor_names[$i + 1] = $result[$i]['DESCRICAO'];
        }//FOR
        $this->smarty->assign('vendedor_ids', $vendedor_ids);
        $this->smarty->assign('vendedor_names', $vendedor_names);
        $this->smarty->assign('vendedor_id', $this->m_par[3]);
        // ***** SITUACAO
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Oportunidade')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }//FOR
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        if ($this->m_par[6]== ""){
            $this->smarty->assign('situacao_id', array('1','3','6'));
        }else{
            $sitMostra = array(); // array que vai guardar as situacoes selecionadas
            $letra = explode("|", $this->m_letra); // explode para qtde no for
            for ($i=6;$i<count($letra);$i++){
                $sitMostra[] = $letra[$i];
            }
            $this->smarty->assign('situacao_id', $sitMostra);// passar para o template o array das situacoes selecionados
        }
        
        // FIM FILIAL ****
        // CHECKBOX DETALHES
        $detalhe_ids[0] = 'N';
        $detalhe_names[0] = 'NF Entrada e Sa&iacute;da';
        $detalhe_ids[1] = 'T';
        $detalhe_names[1] = 'T&eacute;cnico';

        $detalhe[0] = $this->m_par[2];
        $detalhe[1] = $this->m_par[3];
        $this->smarty->assign('detalhe_ids', $detalhe_ids);
        $this->smarty->assign('detalhe_names', $detalhe_names);
        $this->smarty->assign('detalhe_id', $detalhe);
        $this->smarty->assign('parNF', $this->m_par[2]);
        $this->smarty->assign('parTecnico', $this->m_par[3]);
        $this->smarty->assign('pedido', $this->m_par[4]);


        //########### FIM FILTROS DE PESQUISA ###########

        

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('contas_oportunidade_mostra.tpl');
    }

//fim mostrakardexs
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$oportunidade = new p_contas_oportunidade();
//echo 'submenu:'.$_POST['submenu'].'|letra:'. $_POST['letra'].'|opcao:'.$_POST['opcao'];


$oportunidade->controle();
?>
