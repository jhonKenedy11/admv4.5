<?php
/**
 * @package   admsis
 * @name      p_pedido_ps
 * @version   4.3.2
 * @copyright 2021
 * @link      http://www.admsistema.com.br/
 * @author    Márcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      10/05/2021
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/ped/c_pedido_ps.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../class/ped/c_pedido_ps_tools.php");

//Class p_pedido_ps
Class p_pedido_ps extends c_pedido_ps {

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_vlrVisita    = NULL;
    private $m_vlrDesconto  = NULL;
    private $m_situacoesAtendimento  = NULL;
    public $smarty          = NULL;
    public $m_letra_peca    = NULL;
    public $m_letra_servico = NULL;

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

        $this->m_vlrVisita   = $parmPost['valorVisita'];
        $this->m_vlrDesconto = $parmPost['valorDesconto'];

        $this->m_letra = $parmPost['letra'];
        $this->m_letra_peca    = $parmPost['letra_peca'];
        $this->m_letra_servico = $parmPost['letra_servico'];
        $this->m_situacoesAtendimento = $parmPost['situacoesAtendimento'];

        $this->m_par = explode("|", $this->m_letra);
        $this->m_par_peca = explode("|", $this->m_letra_peca);
        $this->m_par_servico = explode("|", $this->m_letra_servico);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        
        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Pedido");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6]");
        $this->smarty->assign('disableSort', "[ 0 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setPedido(isset($parmPost['atendimento']) ? $parmPost['atendimento'] : '');
        $this->setCliente(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setContato(isset($parmPost['contato']) ? $parmPost['contato'] : '');
        $this->setEmissao(isset($parmPost['emissao']) ? $parmPost['emissao'] : '');
        $this->setUsrAbertura(isset($parmPost['usrAbertura']) ? $parmPost['usrAbertura'] : '');
        $this->setPrioridade(isset($parmPost['prioridade']) ? $parmPost['prioridade'] : '');
        $this->setPrazoEntrega(isset($parmPost['prazoEntrega']) ? $parmPost['prazoEntrega'] : '');
        $this->setDescEquipamento(isset($parmPost['descEquipamento']) ? $parmPost['descEquipamento'] : '');
        $this->setKmEntrada(isset($parmPost['kmEntrada']) ? $parmPost['kmEntrada'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setSolucao(isset($parmPost['solucao']) ? $parmPost['solucao'] : '');
        $this->setValorProduto(isset($parmPost['valorPecas']) ? $parmPost['valorPecas'] : 0);
        $this->setValorServicos(isset($parmPost['valorServicos']) ? $parmPost['valorServicos'] : 0);
        $this->setValorFrete(isset($parmPost['valorFrete']) ? $parmPost['valorFrete'] : 0);
        $this->setValorDespAcessorias(isset($parmPost['valorDespAcessorias']) ? $parmPost['valorDespAcessorias'] : 0);
        $this->setValorDesconto(isset($parmPost['valorDesconto']) ? $parmPost['valorDesconto'] : 0);
        $this->setValorTotal(isset($parmPost['valorTotal']) ? $parmPost['valorTotal'] : 0);
        $this->setTipoCobranca(isset($parmPost['tipoCobranca']) ? $parmPost['tipoCobranca'] : '');
        $this->setCondPgto(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        $this->setConta(isset($parmPost['conta']) ? $parmPost['conta'] : '');
        $this->setGenero(isset($parmPost['genero']) ? $parmPost['genero'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : $this->m_empresacentrocusto);
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
     

        //=========================PECAS==================================
        $this->setNrItem(isset($parmPost['nrItem']) ? $parmPost['nrItem'] : '');
        $this->setIdPedidoItem(isset($parmPost['idAtendimentoPecas']) ? $parmPost['idAtendimentoPecas'] : '');
        $this->setCodProduto(isset($parmPost['codProduto']) ? $parmPost['codProduto'] : '');
        $this->setCodProdutoNota(isset($parmPost['codProdutoNota']) ? $parmPost['codProdutoNota'] : '');
        $this->setQuantidadeProduto(isset($parmPost['quantidadePecas']) ? $parmPost['quantidadePecas'] : '');
        $this->setUnidadeProduto(isset($parmPost['uniProduto']) ? $parmPost['uniProduto'] : '');
        $this->setVlrUnitarioProduto(isset($parmPost['vlrUnitarioPecas']) ? $parmPost['vlrUnitarioPecas'] : '');
        $this->setDescricaoProduto(isset($parmPost['descProduto']) ? $parmPost['descProduto'] : '');
        $this->setVlrCustoProduto(isset($parmPost['vlrCustoPecas']) ? $parmPost['vlrCustoPecas'] : '');
        $this->setDescontoProduto(isset($parmPost['vlrDescontoPecas']) ? $parmPost['vlrDescontoPecas'] : '');
        $this->setPercDescontoProduto(isset($parmPost['percDescontoPecas']) ? $parmPost['percDescontoPecas'] : '');
        $this->setAcrescimoProduto(isset($parmPost['acrescimoPecas']) ? $parmPost['acrescimoPecas'] : '');
        $this->setTotalProduto(isset($parmPost['totalPecas']) ? $parmPost['totalPecas'] : '');

        $this->setCodFabricante(isset($parmPost['codFabricante']) ? $parmPost['codFabricante'] : '');

        //==========================SERVICOS=======================
        $this->setIdServico(isset($parmPost['idServicos']) ? $parmPost['idServicos'] : '');
        $this->setIdPedidoServico(isset($parmPost['idAtendimentoServicos']) ? $parmPost['idAtendimentoServicos'] : '');
        $this->setIdUser(isset($parmPost['idUser']) ? $parmPost['idUser'] : '');
        $this->setDataServico(isset($parmPost['dataServico']) ? $parmPost['dataServico'] : '');
        $this->setHoraIniServico(isset($parmPost['horaIni']) ? $parmPost['horaIni'] : '');
        $this->setHoraFimServico(isset($parmPost['horaFim']) ? $parmPost['horaFim'] : '');
        $this->setQuantidadeServico(isset($parmPost['quantidadeServico']) ? $parmPost['quantidadeServico'] : 0);
        $this->setUnidadeServico(isset($parmPost['unidadeServico']) ? $parmPost['unidadeServico'] : '');
        $this->setVlrUnitarioServico(isset($parmPost['vlrUnitarioServico']) ? $parmPost['vlrUnitarioServico'] : '');
        $this->setHoraTotalServico(isset($parmPost['horaTotalServico']) ? $parmPost['horaTotalServico'] : '');
        $this->setCustoUser(isset($parmPost['custoUser']) ? $parmPost['custoUser'] : '');
        $this->setDescricaoServico(isset($parmPost['descricaoServico']) ? $parmPost['descricaoServico'] : '');
        $this->setTotalServico(isset($parmPost['totalServico']) ? $parmPost['totalServico'] : 0);

        //==========================OS=======================
        $this->setOs(isset($parmPost['os']) ? $parmPost['os'] : '');
        $this->setCatEquipamentoId(isset($parmPost['catEquipamentoId']) ? $parmPost['catEquipamentoId'] : '');
        $this->setDescEquipamento(isset($parmPost['descEquipamento']) ? $parmPost['descEquipamento'] : '');
        $this->setDataAbertura(isset($parmPost['dataAbertura']) ? $parmPost['dataAbertura'] : '');
        $this->setDataFechamentoEnd(isset($parmPost['dataFechamentoEnd']) ? $parmPost['dataFechamentoEnd'] : '');
        $this->setPrazoEntregaOs(isset($parmPost['prazoEntregaOs']) ? $parmPost['prazoEntregaOs'] : '');
        $this->setObsOs(isset($parmPost['obsOs']) ? $parmPost['obsOs'] : '');
        $this->setObsServicos(isset($parmPost['obsServicos']) ? $parmPost['obsServicos'] : '');
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
               if ($this->verificaDireitoUsuario('PedVendas', 'I')) {
                    $this->desenhaCadastroPedidoPs();
               }
                break;
            case 'alterar':
               if ($this->verificaDireitoUsuario('PedVendas', 'A')) {
                    $this->buscaPedido();
                    $testeSit = $this->getSituacao();
                    if ($this->getId() > 0){
                        $this->desenhaCadastroPedidoPs();
                    }else{
                        $this->mostraPedidoPs('Pedido não pode ser alterado.');
                    }                  
               }
                break;
            case 'inclui': // CONCLUIR
               if ($this->verificaDireitoUsuario('PedVendas', 'A')) {                 
                    $idInsert = $this->incluiPedido();
                    $result = $this->getValorTotal();
                    $this->updateField("TOTAL", $result, "FAT_PEDIDO");
                    $this->mostraPedidoPs('Registro Salvo. Número: '.$idInsert);
                }
                break;
            case 'altera': // CONCLUIR
               if ($this->verificaDireitoUsuario('PedVendas', 'A')) {                 
                    $this->alteraPedido();
                    $result = $this->getValorTotal();
                    $this->updateField("TOTAL", $result, "FAT_PEDIDO");
                    if($this->getOs() != '0'){
                        $this->atualizaOsPedido();
                    }
                    $this->mostraPedidoPs('Registro Salvo. Número: '.$this->getId());
               }
                break;
            case 'digita': //VOLTAR
                if ($this->verificaDireitoUsuario('PedVendas', 'C')) {
                    $this->mostraPedidoPs('');
                }
                break;
            case 'cancela': // CANCELA
               if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    // 8 - CAT_SITUACAO  = CANCELADO
                    $this->updateField("SITUACAO", 8, "FAT_PEDIDO");
                    $this->mostraPedidoPs('Pedido Cacelado');              
                }
                break;       
            case 'recalcularDesconto':
               if ($this->verificaDireitoUsuario('PedVendas', 'I')){
                    $idAtendimento = $this->getId();
                    $novoDescontoAtendimento = $this->getValorDesconto();
                    $objatendimentoTools = new c_pedido_ps_tools();
                    $msg = $objatendimentoTools->recalcularDescontoPecas($idAtendimento, $novoDescontoAtendimento);
                    $this->desenhaCadastroPedidoPs($msg);
                }
            break;
            case 'gerarOs': // Gerar OS
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $this->updateField("OS", $this->getId(), "FAT_PEDIDO");
                    $this->setOs($this->getId());
                    $this->desenhaCadastroPedidoPs('OS Gerada - '.$this->getId(), 'sucesso');            
                }
                break;    
            case 'estornarOs': // Estornar OS
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $this->updateField("OS", 0, "FAT_PEDIDO");
                    $this->estornaDadosOsPedido();
                    $this->buscaPedido();
                    $this->desenhaCadastroPedidoPs('OS Estornada - '.$this->getId(), 'sucesso');             
                }
                break;    
            case 'duplicaPedido': // Duplica Pedido
                if ($this->verificaDireitoUsuario('PedVendas', 'E')) {
                    $idAntigo = $this->getId();
                    $idGerado = $this->duplicaPedido();
                    $this->setId($idGerado);
                    $this->updateField('PEDIDO', $idGerado, "FAT_PEDIDO");                   
                    $this->duplicaPedidoItem($idGerado, $idAntigo);
                    $this->duplicaPedidoServicos($idGerado, $idAntigo);
                    $this->buscaPedido();
                    $this->m_submenu = 'alterar';                 
                    $this->desenhaCadastroPedidoPs('Pedido Duplicado. - '.$this->getId(), 'sucesso');           
                }
                break;  
                
            default:
                $this->mostraPedidoPs('');
        }
    }

    function desenhaCadastroPedidoPs($mensagem = NULL,$tipoMsg=NULL) {       
        

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);
        
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);        

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('tab', '');
        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente()!=''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
        endif;
        $this->smarty->assign('contato', $this->getContato());
        $this->smarty->assign('pedido', $this->getPedido());
        $this->smarty->assign('situacao', $this->getSituacao());

        

        if($this->getEmissao('F') == ''){
            $this->smarty->assign('emissao', date("d/m/Y"));
        }else{
            $this->smarty->assign('emissao', $this->getEmissao('F'));
        }
        // CADASTRA PEDIDO ITEM 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_PECA"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_PECA"] == "true"):
            $ajax_request = 'true';
            $objPedidoPsTools = new c_pedido_ps_tools();
            // VERIFICA SE NAO TEM Nr Item
            if(empty($this->m_par_peca[12])){
                if(empty($this->m_par_peca[0])){
                    if($this->getEmissao() == ''){
                        $this->setEmissao(date("d/m/Y"));
                    }
                    $this->setCentroCusto($this->m_empresacentrocusto);
                    $this->setEspecie("D");
                    $this->setIdNatop("1");
                    $idPedido = $this->incluiPedido();
                    $this->setId($idPedido);    
                    $this->updateField("PEDIDO", $this->getId(), "FAT_PEDIDO");
                    $this->smarty->assign('id', $this->getId());
                }else{
                    $idPedido = $this->getId();
                }
                $objPedidoPsTools->incluiPecasAtendimentoControle($this->m_letra_peca, $idPedido);
            }else{
                $objPedidoPsTools->alteraPecasAtendimentoControle($this->m_letra_peca);
                $idPedido = $this->getId();
            }
            $this->setId($idPedido);
            $result = $this->select_pedido_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");
            $this->setIdPedidoItem($idPedido);
            $lancPesq = $this->select_pedido_todos_itens_id();
            $this->smarty->assign('lancPesq', $lancPesq);
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);  
        endif; 
        // EXCLUI ITEM
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_PECA"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_PECA"] == "true"):
            $ajax_request = 'true';

            $objPedidoPsTools = new c_pedido_ps_tools();
            $objPedidoPsTools->excluiPecasAtendimento($this->m_letra_peca);
            $result = $this->select_pedido_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");

            $idPedido = $this->getId();

            $this->setIdPedidoItem($idPedido);
            $lancPesq = $this->select_pedido_todos_itens_id();
            $this->smarty->assign('lancPesq', $lancPesq);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif; 

        // CADASTRA SERVIÇO 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_SERVICO"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_SERVICO"] == "true"):
            $ajax_request = 'true';

            $objatendimentoTools = new c_pedido_ps_tools();
            // VERIFICA SE NAO TEM ID SERVICO
            if(empty($this->m_par_servico[9])){
                // VERIFICA SE NAO TEM ID ATENDIMENTO
                if(empty($this->m_par_servico[0])){
                    $this->setCentroCusto($this->m_empresacentrocusto);
                    $this->setEspecie("D");
                    $this->setIdNatop("1");
                    $idPedido = $this->incluiPedido();
                    $this->setId($idPedido);    
                    $this->updateField("PEDIDO", $this->getId(), "FAT_PEDIDO");
                    $this->smarty->assign('id', $this->getId());
                }else{
                    $idPedido = $this->getId();
                }
                $objatendimentoTools->incluiServicoAtendimentoControle($this->m_letra_servico, $idPedido);
            }else{
                $objatendimentoTools->alteraServicoAtendimentoControle($this->m_letra_servico);
            }
            $this->setId($idPedido);
            $result = $this->select_pedido_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);  
        endif; 
        // EXCLUI SERVIÇO
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_SERVICO"] == "true");
        if($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_SERVICO"] == "true"):
            $ajax_request = 'true';

            $objatendimentoTools = new c_pedido_ps_tools();
            $objatendimentoTools->excluiServicoAtendimento($this->m_letra_servico);
            $result = $this->select_pedido_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("TOTAL", $this->getValorTotal(), "FAT_PEDIDO");

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;

        //$ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PRODUTO"] == "true");
        //if($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PRODUTO"] == "true"):
        //    $ajax_request = 'true';
        //    $prodObj = new c_produto();
        //    $produto = $prodObj->select_produto_letra($this->m_pesq);
        //
        //    if (count($produto) > 1) {
        //        $this->smarty->assign('abrePesquisa', 'true');
        //    }elseif(count($produto) == 1){
        //        //dados do produto
        //        $this->smarty->assign('codProduto', $produto[0]['CODIGO']);
        //        $this->smarty->assign('codFabricante', $produto[0]['CODFABRICANTE']);
        //        $this->smarty->assign('codProdutoNota', $produto[0]['CODFABRICANTE']);
        //        $this->smarty->assign('descProduto', $produto[0]['DESCRICAO']);
        //        $this->smarty->assign('uniProduto', $produto[0]['UNIDADE']);
        //        $this->smarty->assign('vlrUnitarioPecas', $produto[0]['VENDA']);
        //        $this->smarty->assign('abrePesquisa', 'false');
        //    }else{
        //        $parPesq  = explode("|", $this->m_pesq);
        //        $this->smarty->assign('codFabricante', $parPesq[2]);
        //    }
        //    
        //
        //else:
        //    $ajax_request = 'false';
        //    $this->smarty->assign('ajax', $ajax_request);
        //endif; 

        //BUSCA PRODUTO 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PROD"] == "true");
         if ($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PROD"] == "true") :
            $ajax_request = 'true';

            $prod       = new c_produto();
            $codFab     = $this->getCodFabricante();
            $resultProd = $prod->select_produto_cod_fabricante($codFab);
            $codFab     = $resultProd[0]['CODFABRICANTE'];
            $codEqui    = $resultProd[0]['CODEQUIVALENTE'];
            $descProd   = $resultProd[0]['DESCRICAO'];
            $unProd     = $resultProd[0]['UNIDADE'];

            if ($resultProd !== null) {
                $this->smarty->assign('prodExiste', 'yes');
                $this->smarty->assign('codProduto', $resultProd[0]['CODIGO']);
                //Testa se é produto ou equivalente
                if ($resultProd[0]['ORIGEM'] == 'EQUIVALENTE') {
                    $this->smarty->assign('codFabricante', "'$codFab'");
                    $this->smarty->assign('codProdutoNota', "'$codEqui'");
                } else {
                    $this->smarty->assign('codFabricante', "'$codFab'");
                    $this->smarty->assign('codProdutoNota', "'$codFab'");
                }

                $this->smarty->assign('descProduto', "$descProd");
                $this->smarty->assign('uniProduto', "'$unProd'");
                $this->setVlrUnitarioProduto($resultProd[0]['VENDA']);
                $this->smarty->assign('vlrUnitarioPecas', $this->getVlrUnitarioProduto('F'));
            } else {
                $this->smarty->assign('prodExiste', 'no');
            }

        else :
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);

        endif;

        $this->smarty->assign('prazoEntrega', $this->getPrazoEntrega('F'));
        $this->smarty->assign('condPgto', $this->getCondPgto());
        $this->smarty->assign('obs', $this->getObs());
        $this->smarty->assign('obsServicos', $this->getObsServicos());
        

        if ($this->getId()!=''):
            $this->setIdPedidoItem($this->getId());
            $lancPesq = $this->select_pedido_todos_itens_id();
            $this->smarty->assign('lancPesq', $lancPesq);

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

            $totais = $this->select_valores_pedido();

            $nrItens = $this->select_pedido_item_nrItem($this->getId());

            if ($totais[0]['TOTALPRODUTOS'] == 0 && $nrItens[0]['NRITEM'] > 0){
                $this->setIdPedidoItem($this->getId());
                $totalProduto = $this->select_produto_total();
                $this->updateField("TOTALPRODUTOS", $totalProduto, "FAT_PEDIDO");
                $totais = $this->select_valores_pedido();
            }
            
            $vlrPecas = $totais[0]['TOTALPRODUTOS'];
            $vlrPecas = number_format($vlrPecas, 2, ',', '.');
            $this->smarty->assign('valorPecas', $vlrPecas);

           
            $vlrServicos = $totais[0]['VALORSERVICOS'];
            if($vlrServicos == null){
                $this->updateField("VALORSERVICOS", 0, "FAT_PEDIDO");
            }
            $vlrServicos = number_format($vlrServicos, 2, ',', '.');
            $this->smarty->assign('valorServicos', $vlrServicos);

            
            $vlrFrete =  $totais[0]['FRETE'];
            $vlrFrete = number_format($vlrFrete, 2, ',', '.');
            $this->smarty->assign('valorFrete', $vlrFrete); 

            $vlrDespAcesorias =  $totais[0]['DESPACESSORIAS'];
            $vlrDespAcesorias = number_format($vlrDespAcesorias, 2, ',', '.');
            $this->smarty->assign('valorDespAcessorias', $vlrDespAcesorias); 
            
           
            $vlrDesconto =  $totais[0]['DESCONTO'];
            $vlrDesconto = number_format($vlrDesconto, 2, ',', '.');
            $this->smarty->assign('valorDesconto', $vlrDesconto); 

         
            $vlrTotal =  $totais[0]['TOTAL'];
            $vlrTotal = number_format($vlrTotal, 2, ',', '.');
            $this->smarty->assign('valorTotal', $vlrTotal);  

        else:
            {$this->smarty->assign('totalatendimento', '0');}
        endif;

        // COMBOBOX ATENDENTE
        $consulta = new c_banco();
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
        $sql.= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' )";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $usrAbertura_ids[$i + 1] = $result[$i]['USUARIO'];
            $usrAbertura_names[$i] = $result[$i]['NOME'];
        }
        $this->smarty->assign('usrAbertura_ids',   $usrAbertura_ids);
        $this->smarty->assign('usrAbertura_names', $usrAbertura_names);
        if($this->getUsrAbertura() == ''){
            $this->setUsrAbertura($this->m_userid);
        }
        $this->smarty->assign('usrAbertura', $this->getUsrAbertura());

       // COMBOBOX SITUACAO
       $consulta = new c_banco();
       $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')";
       if (ADMSistema != 'PECAS') {
           $sql .= " AND ((TIPO = 0) or (TIPO = 5) or (TIPO = 6) or (TIPO = 7) or (TIPO = 9) or (TIPO = 10) or (TIPO = 11) or (TIPO = 12))";
       }  
       $consulta->exec_sql($sql);
       $consulta->close_connection();
       $result = $consulta->resultado;
       for ($i = 0; $i < count($result); $i++) {
           $situacao_ids[$i + 1] = $result[$i]['ID'];
           $situacao_names[$i] = $result[$i]['DESCRICAO'];
       }
        $this->smarty->assign('situacao_ids',   $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        if($this->getSituacao() == ''){
            $this->smarty->assign('situacao', 5);   
        }else{
            $this->smarty->assign('situacao', $this->getSituacao());      
        }      

        

        // COMBOBOX COND PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $condPgto_ids[0] = 0;
        $condPgto_names[0] = 'Condição Pagamento';
        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i+1] = $result[$i]['ID'];
            $condPgto_names[$i+1] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPgto());
        $this->getOs() == '' ? $this->setOs('0') : $this->getOs();
        $this->smarty->assign('os', $this->getOs());

        if($this->getOs() != '0'){

            $this->smarty->assign('tab', 'os');
            
            $this->smarty->assign('catEquipamentoId', $this->getCatEquipamentoId());
            $this->smarty->assign('descEquipamento', $this->getDescEquipamento());
            if($this->getDataAbertura('F') == ''){
                $this->smarty->assign('dataAbertura', date("d/m/Y"));
            }else{
                $this->smarty->assign('dataAbertura', $this->getDataAbertura('F'));
            }
            $this->smarty->assign('dataFechamentoEnd', $this->getDataFechamentoEnd('F'));
            $this->smarty->assign('prazoEntregaOs', $this->getPrazoEntregaOs('F'));
            $this->smarty->assign('obsOs', $this->getObsOs());
            $this->smarty->assign('obsServicos', $this->getObsServicos());
        }

        $this->smarty->display('pedido_ps_cadastro.tpl');
    }

//fim desenhaCadgrupo
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraPedidoPs($mensagem=NULL) {

        if($this->m_situacoesAtendimento == ''){
            $this->m_situacoesAtendimento = '|5|12';
        }

        $cliente = '';
        if ($this->m_letra !=''):
            $lanc = $this->select_pedido_letra($this->m_letra, $this->m_situacoesAtendimento);
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

        $this->smarty->assign('numAtendimento', $this->m_par[3]);

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE (ALIAS='FAT_MENU') AND (CAMPO='SITUACAOPEDIDO')";
        if (ADMSistema != 'PECAS') {
            $sql .= " AND ((TIPO = 0) or (TIPO = 5) or (TIPO = 6) or (TIPO = 7) or (TIPO = 9) or (TIPO = 10) or (TIPO = 11) or (TIPO = 12))";
        }  
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i + 1] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids',   $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
       

        $parSit = explode("|", $this->m_situacoesAtendimento);
        $this->smarty->assign('situacao_id', $parSit);
  
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->assign('situacoesAtendimento', $this->m_situacoesAtendimento);

        $this->smarty->display('pedido_ps_mostra.tpl');
    }
}
function formataBdVlr($vlr){
    //formatação vlr 
    if(strlen($vlr) > 6){
        $number = explode(",", ($vlr));
        $newNumber = str_replace('.', '', $vlr);
        $vlrBd = $newNumber.".".$number[1];
    }else{
        $vlrBd = str_replace(',', '.',$vlr);
    }
    return $vlrBd;
}
function setTotalGeral($vlrPecas, $vlrServicos, $frete, $vlrDespAcessorias, $vlrDesconto){
    $pecas    = formataBdVlr($vlrPecas); 
    $servicos = formataBdVlr($vlrServicos); 
    $frete = formataBdVlr($vlrServicos); 
    $despAcessorias =   formataBdVlr($vlrDespAcessorias);
    $desconto = formataBdVlr($vlrDesconto);

    $totalGeral = (($pecas+$servicos+$frete+$despAcessorias) - $desconto);

    return $totalGeral;
}
// Rotina principal - cria classe
$pedido = new p_pedido_ps();

$pedido->controle();

