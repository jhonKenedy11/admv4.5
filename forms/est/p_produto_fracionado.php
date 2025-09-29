<?php
/**
 * @package   astec
 * @name      p_produto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      13/04/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_produto.php");
require_once($dir."/../../class/est/c_produto_estoque.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir."/../../class/crm/c_conta.php");

//Class P_produto
Class p_produto extends c_produto {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_quant = null;
    private $m_fora = null;
    private $m_opcao = null;
    private $m_origem = null;
    private $m_quantNova = null;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
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
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->m_origem=(isset($parmGet['origem']) ? $parmGet['origem'] : (isset($parmPost['origem']) ? $parmPost['origem'] : ''));
        $this->m_quantNova=(isset($parmGet['quantNova']) ? $parmGet['quantNova'] : (isset($parmPost['quantNova']) ? $parmPost['quantNova'] : 0));
		        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_quant = $quant;
        $this->m_fora = $fora;

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"):
            $this->smarty->assign('titulo', "Produtos");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        else:
            $this->smarty->assign('titulo', "Produtos");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6 ]"); 
            $this->smarty->assign('disableSort', "[ 6 ]"); 
            $this->smarty->assign('numLine', "25"); 
        endif;
        
        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setDesc(isset($parmPost['desc']) ? $parmPost['desc'] : '');
        $this->setGrupo(isset($parmPost['grupo']) ? $parmPost['grupo'] : '');
        $this->setUni(isset($parmPost['uni']) ? $parmPost['uni'] : '');
        $this->setUniFracionada(isset($parmPost['uniFracionada']) ? $parmPost['uniFracionada'] : '');
        $this->setFabricante(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setCodFabricante(isset($parmPost['codFabricante']) ? $parmPost['codFabricante'] : '');
        $this->setCodBarras(isset($parmPost['codBarras']) ? $parmPost['codBarras'] : '');
        $this->setCodProdutoAnvisa(isset($parmPost['codProdutoAnvisa']) ? $parmPost['codProdutoAnvisa'] : '');
        $this->setLocalizacao(isset($parmPost['localizacao']) ? $parmPost['localizacao'] : '');
        $this->setDataForaLinha(isset($parmPost['dataForaLinha']) ? $parmPost['dataForaLinha'] : '');
        $this->setNcm(isset($parmPost['ncm']) ? $parmPost['ncm'] : '');
        $this->setCest(isset($parmPost['cest']) ? $parmPost['cest'] : '');
        $this->setOrigem(isset($parmPost['origem']) ? $parmPost['origem'] : '');
        $this->setTribIcms(isset($parmPost['tribIcms']) ? $parmPost['tribIcms'] : '');
        $this->setMoeda(isset($parmPost['moeda']) ? $parmPost['moeda'] : '');
        $this->setVenda(isset($parmPost['venda']) ? $parmPost['venda'] : '');
        $this->setCustoCompra(isset($parmPost['custoCompra']) ? $parmPost['custoCompra'] : '');
        $this->setCustoMedio(isset($parmPost['custoMedio']) ? $parmPost['custoMedio'] : '');
        $this->setCustoReposicao(isset($parmPost['custoReposicao']) ? $parmPost['custoReposicao'] : '');
        $this->setQuantMinima(isset($parmPost['quantMinima']) ? $parmPost['quantMinima'] : '0');
        $this->setQuantMaxima(isset($parmPost['quantMaxima']) ? $parmPost['quantMaxima'] : '0');
        $this->setobs(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setPrecoPromocao(isset($parmPost['precoPromocao']) ? $parmPost['precoPromocao'] : '');
        $this->setInicioPromocao(isset($parmPost['inicioPromocao']) ? $parmPost['inicioPromocao'] : '');
        $this->setFimPromocao(isset($parmPost['fimPromocao']) ? $parmPost['fimPromocao'] : '');
        $this->setQuantLimite(isset($parmPost['quantLimite']) ? $parmPost['quantLimite'] : '');
        $this->setTipoPromocao(isset($parmPost['tipoPromocao']) ? $parmPost['tipoPromocao'] : '');
        $this->setPrecoPromocao1(isset($parmPost['precoPromocao1']) ? $parmPost['precoPromocao1'] : '');
        $this->setInicioPromocao1(isset($parmPost['inicioPromocao1']) ? $parmPost['inicioPromocao1'] : '');
        $this->setFimPromocao1(isset($parmPost['fimPromocao1']) ? $parmPost['fimPromocao1'] : '');
        $this->setQuantLimite1(isset($parmPost['quantLimite1']) ? $parmPost['quantLimite1'] : '');
        $this->setPrecoBase(isset($parmPost['precoBase']) ? $parmPost['precoBase'] : '');
        $this->setPrecoInformado(isset($parmPost['precoInformado']) ? $parmPost['precoInformado'] : '');
        $this->setPercCalculo(isset($parmPost['percCalculo']) ? $parmPost['percCalculo'] : '');
        $this->setDataUltimaCompra(isset($parmPost['dataUltimaCompra']) ? $parmPost['dataUltimaCompra'] : '');
        $this->setQuantUltimaCompra(isset($parmPost['quantUltimaCompra']) ? $parmPost['quantUltimaCompra'] : '');
        $this->setNfUltimaCompra(isset($parmPost['nfUltimaCompra']) ? $parmPost['nfUltimaCompra'] : '');
        $this->setDateChange(isset($parmPost['dateChange']) ? $parmPost['dateChange'] : '');
        $this->setPeso(isset($parmPost['peso']) ? $parmPost['peso'] : '0.00');
        $this->setPrecoMinimo(isset($parmPost['precoMinimo']) ? $parmPost['precoMinimo'] : '0.00');
        

        $this->setIdEquiv(isset($parmPost['idEquiv']) ? $parmPost['idEquiv'] : '');
        $this->setContaEquiv(isset($parmPost['contaEquiv']) ? $parmPost['contaEquiv'] : '');
        $this->setCodEquivalente(isset($parmPost['codEquivalente']) ? $parmPost['codEquivalente'] : '');
        if (isset($parmPost['pessoa'])){
            if ($parmPost['pessoa'] != '') {
                $this->setContaEquiv($parmPost['pessoa']);
                if ($this->getCodEquivalente() == '') {
                    $this->setCodEquivalente($parmPost['codFabricante']);
                }
            }    
        }
        $this->setDataUltimaCompraEquiv(isset($parmPost['dataUltimaCompraEquiv']) ? $parmPost['dataUltimaCompraEquiv'] : '');
        $this->setQuantUltimaCompraEquiv(isset($parmPost['quantUltimaCompraEquiv']) ? $parmPost['quantUltimaCompraEquiv'] : '');
        $this->setNfUltimaCompraEquiv(isset($parmPost['nfUltimaCompraEquiv']) ? $parmPost['nfUltimaCompraEquiv'] : '');
        
        // include do javascript
        //include ADMjs . "/est/s_produto.js";
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    $this->setUniFracionada('N');
                    $this->desenhaCadProduto();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'A')) {
                    $this->produto();
                    
                    $this->desenhaCadProduto();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    $insert = true;
                    $msg = '';
                    if (($insert) and ($msg == '')){
                        $msg = $this->incluiProduto();
                        if (($this->getCodEquivalente() != '') and 
                            ($this->getCodEquivalente() != $this->getCodFabricante())){
                            $this->setId($msg);
                            $this->incluiProdutoEquivalencia();
                        } 
                    }

                    if (is_int($msg)):
                        $this->mostraProduto($msg);
                    else:    
                        $this->m_submenu = 'cadastrar';
                        $this->desenhaCadProduto($msg, 'alerta');
                    endif;
                }
                break;
            case 'incluiequivalencia':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    $this->incluiProdutoEquivalencia();
                    $this->m_submenu = 'alterar';
                    $this->desenhaCadProduto($msg, 'alerta');
                }
                break;
            case 'incluiequivalenciaPesquisa':
                    $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    $this->setCodEquivalente($parmPost['codFabricanteNfe']);
                    $this->setContaEquiv($parmPost['pessoa']);
                    $this->setId($parmPost['codProduto']);
                    $this->incluiProdutoEquivalencia();
                    $this->mostraProduto('');
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'A')) {
                    $this->mostraProduto($this->alteraProduto());
                }
                break;
           case 'exclui':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'E')) {
                    $this->mostraProduto($this->excluiProduto());
                }
                break;
           case 'excluiequivalencia':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'E')) {
                    $this->m_submenu = 'alterar';
                    $msg = $this->excluiProdutoEquivalencia();
                    $this->desenhaCadProduto($msg, 'alerta');
                }
                break;
           case 'quant':
                $classProdutoQtde = new c_produto_estoque();
                $quant[] = array(
                        'saldo'	=> $classProdutoQtde->select_quantidade_empresa(
                            $this->getId(), $this->m_empresacentrocusto, ''),
                );
            
                echo( json_encode( $quant ) );
                break;
           case 'ajustaestoque':
               
                $objEstProduto = new c_produto_estoque();
                $classNF = new c_nota_fiscal();
                $classNFProduto = new c_nota_fiscal_produto();
                $tipoNf = '0';

                $parametros = new c_banco;
                $parametros->setTab("EST_PARAMETRO");
                $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
                $clientePadrao = $parametros->getField("CLIENTEPADRAO", "FILIAL=".$this->m_empresacentrocusto);
                $parametros->close_connection();                        
                
                $qtde = (int) $this->m_quantNova;
                if ($qtde < 0){
                    $qtde = $qtde * -1;
                    $tipoNf = '1';
                } 
                
               //EST_NOTA_FISCAL
                $classNF->setModelo(99);
                $classNF->setSerie(9);
                $classNF->setNumero(999999);
                $classNF->setPessoa($clientePadrao);
                $classNF->setEmissao(date('d/m/Y H:i'));
                //nat operacao
                $classNF->setIdNatop(99);
                $classNF->setNatOperacao('AJUSTE QUANTIDADE DE ESTOQUE');
                $classNF->setTipo($tipoNf); // 0=Entrada; 1=Saída; 
                $classNF->setSituacao('B');
                $classNF->setFormaPgto('0');
                $classNF->setDataSaidaEntrada(date('d/m/Y H:i'));
                $classNF->setFinalidadeEmissao(9);
                $classNF->setTransportador(0);
                $classNF->setCentroCusto($this->m_empresacentrocusto);
                $classNF->setGenero(99);
                $classNF->setOrigem('AJT');
                $classNF->setDoc(9999);
                $classNF->setModFrete(0); // verificar outras opção de frete no XML
                $classNF->setTotalnf(0);
                $classNF->setObs('AJUSTE DE ESTOQUE REALIZADO PELA ALTERAÇÃO DO PRODUTO');	
                // insere nf
                $lastNF = $classNF->incluiNotaFiscal();
                
               //EST_NOTA_FISCAL_ESTOQUE
                $total = 1;
                $classNFProduto->setIdNf($lastNF);
                $classNFProduto->setCodProduto($this->getId());
                $classNFProduto->setDescricao($this->getDesc());
                $classNFProduto->setUnidade($this->getUni());
                $classNFProduto->setQuant($qtde);
                $classNFProduto->setUnitario($unitario);
                $classNFProduto->setTotal(1);
                $classNFProduto->setOrigem('0');
                $classNFProduto->setTribIcms('00');
                $classNFProduto->setCfop('9999');
                $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));
                $classNFProduto->incluiNotaFiscalProduto();
                
                // QUANTIDADE PRODUTO_ESTOQUE 
                
                $ifControlaEstoque = (($controlaEstoque == 'S') && ($this->getUniFracionada() == 'N'));
                if ($ifControlaEstoque):
                    $objEstProduto = new c_produto_estoque();
                    if ($tipoNf == '0'):
                        for ($i = 0; $i < $qtde; $i++) {
                            $objEstProduto->setIdNfEntrada($lastNF);
                            $objEstProduto->setCodProduto($this->getId());
                            $objEstProduto->setStatus('0');
                            $objEstProduto->setAplicado('0');
                            $objEstProduto->setCentroCusto($this->m_empresacentrocusto);
                            $objEstProduto->setUserProduto($this->m_userid);
                            $objEstProduto->setLocalizacao('');
                            //$objEstProduto->setNsEntrada($this->getNumSerie());
                            $objEstProduto->setFabLote('');
                            $objEstProduto->setDataFabricacao('');
                            $objEstProduto->setDataValidade('');
                            $objEstProduto->incluiProdutoEstoque();
                        }//for
                    else:
                        $objEstProduto->produtoBaixaPerda($this->m_empresacentrocusto, $this->getId(), $qtde, $lastNF);
                    endif;
                endif;
                $msg = 'Quantidade estoque ajustada !!';
                $this->desenhaCadProduto($msg, 'alerta');
                
                break;
            default:
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'C')) {
                    $this->mostraProduto('');
                }
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
    function desenhaCadProduto($mensagem = NULL, $tipoMsg=NULL) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('loc', $this->m_loc);
        $this->smarty->assign('ns', $this->m_ns);
        $this->smarty->assign('idNF', $this->m_idNF);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('desc', "'" . $this->getDesc() . "'");
        $this->smarty->assign('uni', $this->getUni());
        $this->smarty->assign('uniFracionada', $this->getUniFracionada());

        // GRUPO
        $consulta = new c_banco();
        $sql = "select grupo as id, descricao from est_grupo where nivel >= 1 order by grupo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i] = $result[$i]['ID'];
            $grupo_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);

        $this->smarty->assign('grupo', $this->getGrupo());

        $this->smarty->assign('pessoa', $this->getFabricante());
        $this->smarty->assign('pessoaNome', $this->getFabricanteNome());
        $this->smarty->assign('codFabricante', $this->getCodFabricante());
        $this->smarty->assign('codBarras', "'".$this->getCodBarras()."'");
        $this->smarty->assign('codProdutoAnvisa', "'".$this->getCodProdutoAnvisa()."'");
        $this->smarty->assign('localizacao', $this->getLocalizacao());
        $this->smarty->assign('dataForaLinha', $this->getDataForaLinha('F'));
        
        // NCM
        $consulta = new c_banco();
        $sql = "select ncm, descricao from est_ncm order by ncm asc";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $ncm_ids[0] = ' ';
        $ncm_names[0] = ' Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $ncm_ids[$i+1] = $result[$i]['NCM'];
            $ncm_names[$i+1] = $result[$i]['NCM'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('ncm_ids', $ncm_ids);
        $this->smarty->assign('ncm_names', $ncm_names);
        $this->smarty->assign('ncm', $this->getNcm());

        $this->smarty->assign('cest', $this->getCest());
        $this->smarty->assign('precoPromocao', $this->getPrecoPromocao('F'));
        $this->smarty->assign('inicioPromocao', $this->getInicioPromocao('F'));
        $this->smarty->assign('quantLimite', $this->getQuantLimite());
        $this->smarty->assign('fimPromocao', $this->getFimPromocao('F'));
        $this->smarty->assign('tipoPromocao', $this->getTipoPromocao());
        $this->smarty->assign('precoPromocao1', $this->getPrecoPromocao1('F'));
        $this->smarty->assign('inicioPromocao1', $this->getInicioPromocao1('F'));
        $this->smarty->assign('quantLimite1', $this->getQuantLimite1());
        $this->smarty->assign('fimPromocao1', $this->getFimPromocao1('F'));
        $this->smarty->assign('dataUltimaCompra', $this->getDataUltimaCompra('F'));
        $this->smarty->assign('quantUltimaCompra', $this->getQuantUltimaCompra());
        $this->smarty->assign('nfUltimaCompra', $this->getNfUltimaCompra());
        //$this->smarty->assign('precoBase', $this->getPrecoBase());
        $this->smarty->assign('precoInformado', $this->getPrecoInformado('F'));
        //$this->smarty->assign('percCalculo', $this->getPerCalculo('F'));

        //TIPO PROMOCAO #############
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TIPOPROMOCAO')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $tipoPromocao_ids[0] = '';
        $tipoPromocao_names[0] = 'Selecione.';
        for ($i = 0; $i < count($result); $i++) {
            $tipoPromocao_ids[$i+1] = $result[$i]['ID'];
            $tipoPromocao_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipoPromocao_ids', $tipoPromocao_ids);
        $this->smarty->assign('tipoPromocao_names', $tipoPromocao_names);
        $this->smarty->assign('tipoPromocao_id', $this->getTipoPromocao());
        
        //PRECO BASE #############
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='PRECOBASE')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $precoBase_ids[0] = '';
        $precoBase_names[0] = 'Selecione.';
        for ($i = 0; $i < count($result); $i++) {
            $precoBase_ids[$i+1] = $result[$i]['ID'];
            $precoBase_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('precoBase_ids', $precoBase_ids);
        $this->smarty->assign('precoBase_names', $precoBase_names);
        //$this->smarty->assign('precoBase_id', $this->getPrecoBase());
        
        
        // ORIGEM MERCADORIA
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='OrigemMercadoria')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $origem_ids[0] = '';
        $origem_names[0] = 'Selecione.';
        for ($i = 0; $i < count($result); $i++) {
            $origem_ids[$i+1] = $result[$i]['ID'];
            $origem_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('origem_ids', $origem_ids);
        $this->smarty->assign('origem_names', $origem_names);
        $this->smarty->assign('origem', $this->getOrigem());

        // SIM / NAO
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='boolean')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $boolean_ids[$i] = $result[$i]['ID'];
            $boolean_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('boolean_ids', $boolean_ids);
        $this->smarty->assign('boolean_names', $boolean_names);
        $this->smarty->assign('boolean', $this->getUniFracionada());

        // TRIBUTO ICMS
        //=== consulta regime tributário da empresa.
        
        
        $consulta = new c_banco();
        $consulta->setTab('AMB_EMPRESA');
        $regime = $consulta->getField('REGIMETRIBUTARIO', 'empresa='.$this->m_empresaid);
        if ($regime==3):
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TributacaoIcms')";
        else:
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='csosn')";
        endif;
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $tribIcms_ids[0] = '';
        $tribIcms_names[0] = 'Selecione.';
        for ($i = 0; $i < count($result); $i++) {
            $tribIcms_ids[$i+1] = $result[$i]['ID'];
            $tribIcms_names[$i+1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tribIcms_ids', $tribIcms_ids);
        $this->smarty->assign('tribIcms_names', $tribIcms_names);
        $this->smarty->assign('tribIcms', $this->getTribIcms());


        // MOEDA
        $consulta = new c_banco();
        $sql = "select moeda as id, nome as descricao from fin_moeda";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $moeda_ids[$i] = $result[$i]['ID'];
            $moeda_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('moeda_ids', $moeda_ids);
        $this->smarty->assign('moeda_names', $moeda_names);
        $this->smarty->assign('moeda', $this->getMoeda());


        $this->smarty->assign('venda', $this->getVenda('F'));
        $this->smarty->assign('custoMedio', $this->getCustoMedio('F'));
        $this->smarty->assign('custoCompra', $this->getCustoCompra('F'));
        $this->smarty->assign('custoReposicao', $this->getCustoReposicao('F'));
        $this->smarty->assign('quantMinima', $this->getQuantMinima());
        $this->smarty->assign('quantMaxima', $this->getQuantMaxima());
        $this->smarty->assign('obs', $this->getObs());

        
        $quantAtual = 0;
        $quantReservada = 0;
        if (($this->m_submenu == 'alterar') || ($this->m_submenu == 'ajustaestoque')){
        // CODIGO EQUIVALENTE
            $equiv = $this->select_produto_equivalencia();
            $this->smarty->assign('equiv', $equiv);
            $classProdutoQtde = new c_produto_estoque();
            $produtoQuant = $classProdutoQtde->produtoQtde($this->getId(), $this->m_empresacentrocusto);
            $quantAtual = $produtoQuant[0]['ESTOQUE'];
            $quantReservada = $produtoQuant[0]['RESERVA'];

            // switch (count($produtoQuant)){
            //     case 1:
            //         $quantAtual = $produtoQuant[0]['QUANTIDADE'];
            //         $quantReservada = 0;
            //     break;
            //     case 2:
            //         $quantAtual = $produtoQuant[0]['QUANTIDADE'];
            //         $quantReservada = $produtoQuant[1]['QUANTIDADE'];
            //     break;
            //     case 3:
            //         $quantAtual = $produtoQuant[0]['QUANTIDADE'];
            //         $quantReservada = $produtoQuant[1]['QUANTIDADE']+$produtoQuant[2]['QUANTIDADE'];
            //     break;
            //     case 4:
            //         $quantAtual = $produtoQuant[0]['QUANTIDADE'];
            //         $quantReservada = $produtoQuant[1]['QUANTIDADE']+$produtoQuant[2]['QUANTIDADE']+$produtoQuant[3]['QUANTIDADE'];
            //     break;
            //     default :
            //         $quantAtual = 0;
            //         $quantReservada = 0;
            // }
        } 
        
        $this->smarty->assign('quantAtual', $quantAtual);
        $this->smarty->assign('quantReservada', $quantReservada);
        $this->smarty->assign('quantTotal', $quantAtual+$quantReservada);
        $this->smarty->assign('dateChange', $this->getDateChange());
        $this->smarty->assign('peso', $this->getPeso());
        $this->smarty->assign('precoMinimo', $this->getPrecoMinimo('F'));
       
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        /*
        $origem = $this->getOrigem();
        if ( $origem != "") {
            $this->smarty->assign('origem', $origem);
        } else {
            $origem = $parametros->getField("ORIGEM", "FILIAL=".$this->m_empresacentrocusto);
            $this->smarty->assign('origem', $origem);
        }
        
        $tribicms = $this->getTribIcms();
        if ( $tribicms != "") {
            $this->smarty->assign('tribIcms', $tribicms);
        } else {
            $tribicms = $parametros->getField("TRIBICMS", "FILIAL=".$this->m_empresacentrocusto);
            $this->smarty->assign('tribIcms', $tribicms);
        }
        */ 
        $base = $this->getPrecoBase();
        if ( $base != "") {
            $this->smarty->assign('precoBase_id', $base);
        } else {
            $precoBase = $parametros->getField("PRECOBASE", "FILIAL=".$this->m_empresacentrocusto);
            $this->smarty->assign('precoBase_id', $precoBase);
        }     
        
        $perc = $this->getPerCalculo('F');
        if ($perc <= 0) {
            $perCalculo= $parametros->getField("PERCALCULO", "FILIAL=".$this->m_empresacentrocusto);
            $this->setPercCalculo($perCalculo);
            $this->smarty->assign('percCalculo', $this->getPerCalculo('F'));
        } else {
            $this->smarty->assign('percCalculo', $perc);
        }  
        
        $tabela = $this->select_produto_tabela();
        $this->smarty->assign('tabela', $tabela);

        $this->smarty->assign('codEquivalente', $this->getCodEquivalente());

        $this->smarty->display('produto_cadastro.tpl');
    }

//fim desenhaCadproduto
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraProduto($mensagem) {
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $this->smarty->assign('active01', 'active');
        $this->smarty->assign('activeTab01', 'active in');

        if ((isset($this->m_letra)) && $this->m_letra != '') {
            $produto = $this->select_produto_letra($this->m_letra, $parmPost['codigo']);
            if ($parmPost['codigo'] != '') {
                $notas = $this->select_vendas_produto($parmPost['codigo']);
                $this->smarty->assign('notas', $notas);
            }
        }
        if ($parmPost['codFabricante'] != '') {
            $tabela = $this->select_importacao_tabela($parmPost['codFabricante']);
            $this->smarty->assign('tabela', $tabela);
            
            $this->smarty->assign('active01', '');
            $this->smarty->assign('activeTab01', '');
            $this->smarty->assign('active03', 'active');
            $this->smarty->assign('activeTab03', 'active in');
            
        }
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('origem', $this->m_origem);

        isset($parmPost['pessoa']) ? $this->smarty->assign('pessoa', $parmPost['pessoa']) : '';
        isset($parmPost['produtoNomeNfe']) ? $this->smarty->assign('produtoNomeNfe', $parmPost['produtoNomeNfe']) : $this->smarty->assign('produtoNomeNfe', $parmPost['produtoNome']);
        isset($parmPost['codFabricanteNfe']) ? $this->smarty->assign('codFabricanteNfe', $parmPost['codFabricanteNfe']) : $this->smarty->assign('codFabricanteNfe', $parmPost['codFabricante']);
//        isset($parmPost['produtoNome']) ? $this->smarty->assign('produtoNome', $parmPost['produtoNome']) : $this->smarty->assign('produtoNome', $this->m_par[0]);
//        isset($parmPost['codFabricante']) ? $this->smarty->assign('codFabricante', $parmPost['codFabricante']) : $this->smarty->assign('codFabricante', $this->m_par[2]);
        $this->smarty->assign('produtoNome', $this->m_par[0]);
        $this->smarty->assign('codFabricante', $this->m_par[2]);
        $this->smarty->assign('localizacao', $this->m_par[3]);
        $this->smarty->assign('quant', $this->m_par[4]);

        // tipo de Select
        //**** estoque ****
        $estoque_ids[0] = 'T';
        $estoque_names[0] = 'Todos';
        $estoque_ids[1] = 'S';
        $estoque_names[1] = 'Com Saldo';
        $estoque_ids[2] = 'N';
        $estoque_names[2] = 'Sem Saldo';
        $this->smarty->assign('estoque_ids', $estoque_ids);
        $this->smarty->assign('estoque_names', $estoque_names);
        if ($this->m_par[4] == '') {
            $this->smarty->assign('estoque_id', 'S');
        } else {
            $this->smarty->assign('estoque_id', $this->m_par[4]);
        }
        //****** fim estoque ******


        for ($i = 'A'; $i < 'Z'; $i++) {
            $arrayLetra[$i] = $i;
        }
        $this->smarty->assign('arrayLetra', $arrayLetra);

        // GRUPO
        $consulta = new c_banco();
        $sql = "select grupo id, descricao from est_grupo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $grupo_ids[0] = '';
        $grupo_names[0] = 'Selecione Grupo';
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + 1] = $result[$i]['ID'];
            $grupo_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);
        if ($this->m_par[1] == "")
            $this->smarty->assign('grupo_id', 'Todos');
        else
            $this->smarty->assign('grupo_id', $this->m_par[1]);

        $resultProduto = [];
        $p = 0;
        $classProdutoQtde = new c_produto_estoque();
        for($i=0;$i<count($produto);$i++){
            $produtoQuant = $classProdutoQtde->produtoQtde($produto[$i]['CODIGO'], $this->m_empresacentrocusto);
            $produto[$i]['ESTOQUE'] = $produtoQuant[0]['ESTOQUE'];
            $produto[$i]['RESERVA'] = $produtoQuant[0]['RESERVA'];
            // for($q=0;$q<count($produtoQuant);$q++){
            //     if ($produtoQuant[$q]['STATUS'] == 0):
            //             $produto[$i]['ESTOQUE'] = $produtoQuant[$q]['QUANTIDADE'];
            //     else:    
            //             $produto[$i]['RESERVA'] = $produtoQuant[$q]['QUANTIDADE'];
            //     endif;
            // }    
            $resultProduto[$p] = $produto[$i];
            $p++;
        }    
            
/*            $produtoQuant = $classProdutoQtde->produtoQtde($produto[$i]['CODIGO'], $this->m_empresacentrocusto);
            switch (count($produtoQuant)){
                case 0://não localizou nada
                    $produto[$i]['ESTOQUE'] = 0;
                    $produto[$i]['RESERVA'] = 0;
                    if ($this->m_par[4] == 'F'){
                        $resultProduto[$p] = $produto[$i];
                        $p++;
                    }
                break;
                case 1://DISPONIVEL somente estoque
                    $produto[$i]['ESTOQUE'] = $produtoQuant[0]['QUANTIDADE'];
                    $produto[$i]['RESERVA'] = 0;
                    $resultProduto[$p] = $produto[$i];
                    $p++;
                break;
                default : // disponivel estoque com reservas
                    $produto[$i]['ESTOQUE'] = $produtoQuant[0]['QUANTIDADE'];
                    $produto[$i]['RESERVA'] = $produtoQuant[1]['QUANTIDADE']+$produtoQuant[2]['QUANTIDADE']+$produtoQuant[3]['QUANTIDADE'];
                    $resultProduto[$p] = $produto[$i];
                    $p++;
                break;
            }
        }//for
 * 
 * 
 */
        
        $this->smarty->assign('lanc', $resultProduto);

        switch ($this->m_opcao){
            case "pesquisar":
                $this->smarty->display('produto_pesquisar.tpl');
                break;
            case "pesquisarnfe":
                //$this->smarty->assign('opcao', 'pesquisar');
                $this->smarty->display('produto_pesquisar_nfe.tpl');
                break;
            default : 
                $this->smarty->display('produto_mostra.tpl');
        }
        
/*        if ($this->m_opcao=="pesquisar"):
            $this->smarty->display('produto_pesquisar.tpl');
        else:
            $this->smarty->display('produto_mostra.tpl');
        endif;
 * 
 */
    }


//fim mostraProdutos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
//$produto = new p_produto(isset($parmPost['id']) ? $parmPost['id'] : '''submenu'], $_POST['letra'], $_POST['quant'], $_POST['acao'], $_REQUEST['pesquisa'], $_POST['opcao'], $_POST['loc'], $_POST['ns'], $_POST['idNF']);
$produto = new p_produto();

$produto->controle();
?>
l.