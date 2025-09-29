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
require_once($dir . "/../../class/ped/c_pedido_venda.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../forms/ped/p_pedido_venda_nf.php");

//Class P_situacao
Class p_pedido_venda extends c_pedidoVenda {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_parPesq      = NULL;
    private $m_itensPedido  = NULL;
    private $m_itensQtde    = NULL;
    private $parmPost       = NULL;
    private $parmGet        = NULL;
    public $totalPedido     = NULL;
    public $smarty          = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $this->parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        //// $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

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
        $this->m_submenu = isset($this->parmPost['submenu']) ? $this->parmPost['submenu'] : (isset($this->parmGet['submenu']) ? $this->parmGet['submenu'] : null);
        $this->m_pesq = $this->parmPost['pesq'];
        $this->m_letra = $this->parmPost['letra'];
        $this->m_itensPedido = $this->parmPost['itensPedido'];
        $this->m_itensQtde = $this->parmPost['itensQtde'];
        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_parPesq = explode("|", $this->m_pesq);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        
        // dados para exportacao e relatorios
        $this->smarty->assign('form', $this->parmPost['form']);
        $this->smarty->assign('titulo', "Pedidos de Vendas");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]");
        $this->smarty->assign('disableSort', "[ 5 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($this->parmPost['id']) ? $this->parmPost['id'] : '');
        $this->setNrItem(isset($this->parmPost['nrItem']) ? $this->parmPost['nrItem'] : '');
        $this->setSituacao(isset($this->parmPost['situacao']) ? $this->parmPost['situacao'] : '');

        $this->setCliente($this->m_empresacliente);
        
        // include do javascript
        // include ADMjs . "/ped/s_pedido_venda.js";
    }
    /**
     * <b> Busca produtos com quantidade para lançar no pedido </b>
     * @name formItemQuant
     * @param ARRAY item dados dos produtos a serem cadastrados.
     * @return messagem de alerta
     */
    public function formItemQuant($post){
        for ($i = 0; $i < count($post); $i++) {
            $lanc[$i]['CODIGO'] = (isset($this->parmPost['quant'.$lanc[$i]['PARCELA']]) ? $this->parmPost['venc'.$lanc[$i]['PARCELA']] : "");
            $lanc[$i]['QUANT'] = (isset($this->parmPost['venc'.$lanc[$i]['PARCELA']]) ? $this->parmPost['venc'.$lanc[$i]['PARCELA']] : "");
            
            $nome = $post[$i];
            if (isset($post['quant'.$post[$i]['CODIGO']])):
                $quant += $post['quant'.$lanc[$i]['CODIGO']];
                
            endif;
        }
    }

/**
* <b> É responsavel para calcular os impostos de um item </b>
* @name calculoImpostosItem
* @param vazio
* @return atualiza os totais dos impostos
*/
function calculaImpostosItem() {
    $objPedidoNf = new c_pedidoVendaNf();
    $objNfProduto = new c_nota_fiscal_produto();

    // seta dados do item 
    $total  = $this->getQtSolicitada('B')*$this->getUnitario('B');

    $objNfProduto->setCodProduto($this->getItemEstoque());
    $objNfProduto->setDescricao($this->getDescricaoItem());
    // $objNfProduto->setUnidade($this->get  arrItemPedido[$i]['UNIDADE']);
    $objNfProduto->setQuant($this->getQtSolicitada('B'), true);
    $objNfProduto->setUnitario($this->getUnitario('B'), true);
    $objNfProduto->setDesconto($this->getPerDesconto('B'), true); // VERIFICAR DESCONTO
    $objNfProduto->setTotal($total, true);

    // busca produto
    $objProdutoC = new c_produto();
    $objProdutoC->setId($this->getItemEstoque());
    $arrProdutoC = $objProdutoC->select_produto();

    $objNfProduto->setOrigem($arrProdutoC[0]['ORIGEM']);
    $objNfProduto->setTribIcms($arrProdutoC[0]['TRIBICMS']);
    $objNfProduto->setNcm($arrProdutoC[0]['NCM']);
    $objNfProduto->setCest($arrProdutoC[0]['CEST']);

    $cliente = $this->getCliente();
    $this->setClienteNome();
    $uf = $this->getUfPessoa();
    $tipop = $this->getTipoPessoa();
    $natOp = 1;

    $result = $objPedidoNf->calculaImpostosNfe($objNfProduto, 
                    $natOp, 
                    $this->getUfPessoa(), 
                    $this->getTipoPessoa(),
                    $this->m_empresacentrocusto); 

    if (!$result):
        return  0 ;
    else:
        $valorIcmsSt = $objNfProduto->getValorIcmsSt();
        return $valorIcmsSt;
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
                //if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                //verifica se o cadastro de pedido esta liberado
                $sql = "select * from amb_usuario_autoriza where usuario = 1003 and programa = 'PEDINATIVA';";
                $banco = new c_banco();
                $banco->exec_sql($sql);
                $banco->close_connection();
                
                if($banco->resultado[0]['DIREITOS'] !== 'Y'){
                    $result = c_lancamento::verificaPendenciaFinanceira($this->getCliente(), date('Y-m-d'));
                    $result = false;
                    if (!$result) :
                        $this->desenhaCadastroPedido();
                    else :
                        $this->mostraPedido('Favor entrar em contato com financeiro para liberação, celular 41 99815-2323.');
                    endif;
                }else{
                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                    echo "<script>Swal.fire('Atenção!','Cadastro de pedido bloqueado temporariamente!','danger')</script>";
                    $this->mostraPedido('');
                }
                break;
            case 'alterar':
                //if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    //$arrPedido = $this->select_pedidoVenda('0');
                    if ($this->getSituacao() == '0'){
                        //$this->setSituacao($arrPedido[0]['SITUACAO']);
                        //$this->setCliente($arrPedido[0]['CLIENTE']);
                        $result = c_lancamento::verificaPendenciaFinanceira($this->getCliente(), date('Y-m-d'));
                        $result = false;
                        if (!$result):
                            $this->desenhaCadastroPedido();
                        else:
                            $this->mostraPedido('Favor entrar em contato com financeiro para liberação, celular 41 99815-2323.');
                        endif;
                    }else{
                        $this->e_submenu = '';
                        $this->mostraPedido('Pedido não pode ser alterado.');
                    }
                    
                
                break;
            /*case 'inclui':
                if ($this->verificaDireitoUsuario('EstGrupo', 'I')) {
                    $this->setPedidoVenda();
                    $this->setSituacao(1);
                    $this->setTotal(str_replace('.', ',', $this->select_totalPedido()));
                    
                    $this->alteraPedido();
                    $this->mostraPedido('Pedido confirmado.');
                }
                break;*/
            case 'altera': // CONCLUIR
                //if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    //$this->setPedidoVenda();
                    $itens = $this->m_itensPedido;
                    $this->setTotal($this->select_totalPedido());
                    // BUSCA VALOR PEDIDO MINIMO
                    $parametros = new c_banco;
                    $parametros->setTab("FAT_PARAMETRO");
                    $valorPedidoMinimo = $parametros->getField("VALORPEDIDOMINIMO", "FILIAL=".$this->m_empresacentrocusto);
                    $parametros->close_connection();                        
                    if ($this->getTotal()>0):

                        if ((($this->getTotal() >= $valorPedidoMinimo) and ($valorPedidoMinimo !='')) or 
                            ($valorPedidoMinimo ==0)):
                            $this->setSituacao(1);
                            $this->setPedido($this->getId());
                            $this->alteraPedidoTotal();
                            $this->mostraPedido('Pedido confirmado.');
                        else:    
                            $this->desenhaCadastroPedido('Pedido com valor abaixo do mínimo. Valor mínimo:'.$valorPedidoMinimo,'erro');
                        endif;
                    else:    
                        $this->mostraPedido('Pedido em Digitação.');
                    endif;
                    
                break;
            case 'digita': //VOLTAR
                //if ($this->verificaDireitoUsuario('EstGrupo', 'A')) {
                    if ($this->getId()!=''):
                        $this->setSituacao(0);
                        //$this->setPedidoVenda();

                        //$this->setTotal(str_replace('.', ',', $this->select_totalPedido()));
                        $this->setTotal($this->select_totalPedido());
                        $this->setPedido(0);

                        $this->alteraPedidoTotal();
                        $this->mostraPedido('Pedido em Digitação.');
                    else:    
                        $this->mostraPedido('');
                    endif;
                    
                
                break;
            case 'exclui':
                //if ($this->verificaDireitoUsuario('EstGrupo', 'E')) {
                    if (is_array($this->select_pedidoVenda(0))){
                        $this->excluiPedido();
                        $this->mostraPedido();
                    }else{
                        $this->mostraPedido('Pedido não pode ser deletado.');
                    }
                    
                    
                
                break;
            case 'cadastrarItem': //CARRINHO
                $pedido = new p_pedido_venda();
                $retorno = $pedido->cadastrarItemAjax($_POST);
                header('Content-Type: application/json');
                echo json_encode($retorno);
                exit;
            case 'excluiItem':
                $pedido = new p_pedido_venda();
                $retorno = $pedido->excluirItemAjax($_POST);
                header('Content-Type: application/json');
                echo json_encode($retorno);
                exit;
            case 'todosEmLinha':
                $this->m_pesq = '|';
                
                $this->desenhaCadastroPedido(null, null, 'N');
                break;
            case 'getEstoque':
                $codigo = $_POST['codigo'];
                $objProdutoQtde = new c_produto_estoque();
                $arrProduto = $objProdutoQtde->quantidadeEstoqueItemEstoque($this->m_empresacentrocusto, $codigo);
                $estoque = isset($arrProduto[0]['QUANTIDADE']) ? $arrProduto[0]['QUANTIDADE'] : 0;
                echo json_encode(['success' => true, 'estoque' => $estoque]);
                exit;
            case 'kit':
                $this->cadastroKit();
                break;
            default:
                    // BUSCA VALOR PEDIDO MINIMO
                    $parametros = new c_banco;
                    $parametros->setTab("FAT_PARAMETRO");
                    $valorPedidoMinimo = $parametros->getField("VALORPEDIDOMINIMO", "FILIAL=".$this->m_empresacentrocusto);
                    $parametros->close_connection();                        

                    $this->mostraPedido('Pedido Mínimo VALOR: '.$valorPedidoMinimo);
        }
    }

    function desenhaCadastroPedido($mensagem = NULL, $tipoMsg = NULL, $tipoConsulta = 'S') {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        $this->smarty->assign('itensPedido', $this->m_itensPedido);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        //$this->smarty->assign('promocoes', 'S');

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
        $this->smarty->assign('entregador', $this->getEntregador());
        $this->smarty->assign('usrFatura', $this->getUsrFatura());
        $this->smarty->assign('idNatop', $this->getIdNatop());
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
        $this->smarty->assign('horaEmissao', $this->getHoraEmissao('F'));
        $this->smarty->assign('taxaEntrega', $this->getTaxaEntrega('F'));
        $this->smarty->assign('totalRecebido', $this->getTotalRecebido('F'));
        $this->smarty->assign('dataEntrega', $this->getDataEntrega('F'));
        $this->smarty->assign('horaEntrega', $this->getHoraEntrega('F'));
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
        

        //PROMOÇÃO
        //$this->smarty->assign('promocoes', $this->m_parPesq[2]);
        //TIPO PROMOCAO #############
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TIPOPROMOCAO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $promocoes_ids[0] = '';
        $promocoes_names[0] = 'Estoque A-Z.';
        $promocoes_ids[1] = 'T';
        $promocoes_names[1] = 'TODAS PROMOÇÕES';
        for ($i = 0; $i < count($result); $i++) {
            $promocoes_ids[$i+2] = $result[$i]['ID'];
            $promocoes_names[$i+2] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('promocoes_ids', $promocoes_ids);
        $this->smarty->assign('promocoes_names', $promocoes_names);
        $this->smarty->assign('promocoes_id', $this->m_parPesq[2]);
        
        // campos de pesquisa de produtos
        $this->smarty->assign('pesProduto', $this->m_parPesq[0]);
        if ($this->getId()!=''):
            $this->totalPedido = $this->select_totalPedido();
            $this->smarty->assign('totalPedido', $this->totalPedido);
        else:
            {$this->smarty->assign('totalPedido', '0');}
        endif;

        // ITENS DO PEDIDO JÁ CADASTRADO
        if (!empty($this->getId())){
            $lancItens = $this->select_pedido_item_id();
            $this->smarty->assign('lancItens', $lancItens);
            if ($this->m_parPesq[1] == ""){
                $grupo = explode(".", $lancItens[0]['GRUPOESTOQUE']);
                $this->m_parPesq[1] = $grupo[0];
            }
        }else {
            $this->m_parPesq[1] = '';
        }

        if (!is_null($this->m_pesq)) 
            $this->m_pesq = $this->m_parPesq[0] . '|' . $this->m_parPesq[1] . '|' . $this->m_parPesq[2] . '|' . $this->m_parPesq[3];

        // ITENS PESQUISADOS
        // if ((!empty($this->m_pesq)) and ($this->m_parPesq[2] != "2")){
        $this->smarty->assign('kit', $this->m_parPesq[2]);
        if (!empty($this->m_pesq)){
            $objProdutoQtde = new c_produto_estoque();
            $lancPesq = $objProdutoQtde->produtoQtdePreco($this->m_pesq, $this->m_empresacentrocusto, null, $tipoConsulta);
//            $lancPesq = $this->select_pedido_venda_item_letra($this->m_pesq);
            $this->smarty->assign('lancPesq', $lancPesq);
        }


        // COMBOBOX GRUPO
        $consulta = new c_banco();
        // if ($this->m_parPesq[1] == ""){
            $sql = "select grupo id, descricao from est_grupo where nivel = 1";
            $grupo_ids[0] = '';
            $grupo_names[0] = 'Selecione um grupo para o pedido';
            $indice = 1;
        // }    
        // else{
        //     $sql = "select grupo id, descricao from est_grupo where nivel = 1 and grupo = '".$this->m_parPesq[1]."'";
        //     $indice = 0;
        // }
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + $indice] = $result[$i]['ID'];
            $grupo_names[$i + $indice] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);
        if ($this->m_parPesq[1] == "")
            $this->smarty->assign('grupo_id', 'Todos');
        else
            $this->smarty->assign('grupo_id', $this->m_parPesq[1]);

        

        //QUANTIDADE
        if (empty($this->m_itensQtde)){
            $this->smarty->assign('itensQtde', 1);
        }else{
            $this->smarty->assign('itensQtde', $this->m_itensQtde);
        }

        $this->smarty->display('pedido_venda_online_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function cadastroKit() {
        $this->m_submenu = 'cadastrarItem';
        $this->controle();
        $this->m_submenu = 'altera';
        $this->controle();
    }

//fim mostragrupos
//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedido($mensagem=NULL) {
//            $this->m_letra = "1|2|3|4";
        // busco codigo do cliente no cadastro de usuário.
/*        $parametros = new c_banco;
        $parametros->setTab("AMB_USUARIO");
        $cliente = $parametros->getField("CLIENTE", "USUARIO=".$this->m_userid);
        $parametros->close_connection();                        
        $this->setCliente($cliente);*/

        $cliente = $this->getCliente();
        //$cliente = '';
        $this->m_letra = "||".$cliente."|||||5|0|1|2|3|4";
        $lanc = $this->select_pedidoVenda_letra($this->m_letra);

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO') AND (tipo in ('a','d'))";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $situacao_ids[0] = '';
        $situacao_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $arr = array('D','A');
        $this->smarty->assign('situacao_id', $arr);

        $consulta = new c_banco();
        $sql = "select direitos from amb_usuario_autoriza where programa = 'PEDINATIVA';"; //BLOQUEIA PEDIDO
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
            
        $this->smarty->assign('pedido_bloqueia', $result[0]['DIREITOS']);
        
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);


        $this->smarty->display('pedido_venda_online_mostra.tpl');
    }

//fim mostragrupos
//-------------------------------------------------------------
}
//	END OF THE CLASS

// php 7 ==>$email = $_POST['email'] ?? 'valor padrão';
// php 5 ==>$email = isset($_POST['email']) ? $_POST['email'] : 'valor padrão';

// Rotina principal - cria classe
$pedido = new p_pedido_venda();

$pedido->controle();

