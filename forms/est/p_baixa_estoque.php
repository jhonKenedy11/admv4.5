<?php
/**
 * @package   astec
 * @name      p_baixa_estoque
 * @version   3.0.00
 * @copyright 2020
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto 
 * @date      13/05/2020
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

//Class p_baixa_estoque
Class p_baixa_estoque extends c_produto {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;

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
        $this->id_produto=(isset($parmGet['codProduto']) ? $parmGet['codProduto'] : (isset($parmPost['codProduto']) ? $parmPost['codProduto'] : ''));
        $this->desc_prod=(isset($parmGet['descProduto']) ? $parmGet['descProduto'] : (isset($parmPost['descProduto']) ? $parmPost['descProduto'] : ''));
        $this->unidade_prod=(isset($parmGet['unidade']) ? $parmGet['unidade'] : (isset($parmPost['unidade']) ? $parmPost['unidade'] : ''));
        $this->valorVenda=(isset($parmGet['valorVenda']) ? $parmGet['valorVenda'] : (isset($parmPost['valorVenda']) ? $parmPost['valorVenda'] : ''));
        $this->uniFracionada=(isset($parmGet['uniFracionada']) ? $parmGet['uniFracionada'] : (isset($parmPost['uniFracionada']) ? $parmPost['uniFracionada'] : ''));
        $this->id_pessoa=(isset($parmGet['pessoa']) ? $parmGet['pessoa'] : (isset($parmPost['pessoa']) ? $parmPost['pessoa'] : ''));
        $this->m_quantNova=(isset($parmGet['qtdeEntrada']) ? $parmGet['qtdeEntrada'] : (isset($parmPost['qtdeEntrada']) ? $parmPost['qtdeEntrada'] : 0));
        $this->m_modelo=(isset($parmGet['modelo']) ? $parmGet['modelo'] : (isset($parmPost['modelo']) ? $parmPost['modelo'] : ''));
        $this->m_serieDocto=(isset($parmGet['serieNf']) ? $parmGet['serieNf'] : (isset($parmPost['serieNf']) ? $parmPost['serieNf'] : ''));
        $this->m_numDocto=(isset($parmGet['numDocto']) ? $parmGet['numDocto'] : (isset($parmPost['numDocto']) ? $parmPost['numDocto'] : ''));
        $this->m_genero=(isset($parmGet['genero']) ? $parmGet['genero'] : (isset($parmPost['genero']) ? $parmPost['genero'] : ''));
        $this->m_obsNf=(isset($parmGet['obs']) ? $parmGet['obs'] : (isset($parmPost['obs']) ? $parmPost['obs'] : ''));
        
        $this->ccustoOrigem=(isset($parmGet['centroCustoOrigem']) ? $parmGet['centroCustoOrigem'] : (isset($parmPost['centroCustoOrigem']) ? $parmPost['centroCustoOrigem'] : ''));
        $this->ccustoDestino=(isset($parmGet['centroCustoDestino']) ? $parmGet['centroCustoDestino'] : (isset($parmPost['centroCustoDestino']) ? $parmPost['centroCustoDestino'] : ''));
		        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"):
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        else:
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]"); 
            $this->smarty->assign('disableSort', "[ 0 ]"); 
            $this->smarty->assign('numLine', "25"); 
        endif;
    
            
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'inclui':
                $quant = str_replace('.', '',$this->m_quantNova);
                $quant = str_replace(',', '.', $quant);

                if (abs($quant) > 0) {
                    $result = $this->insereQuant($this->m_quantNova);
                    $msg = 'Quantidade estoque ajustada !! Num Docto '.$result." Modelo ".$this->m_modelo." Serie ".$this->m_serieDocto;
                    $this->mostraBaixaEstoque($msg, 'sucesso');
                } else {
                    $msg = 'Quantidade inválida !!';
                    $this->mostraBaixaEstoque($msg, 'alerta');
                }    
            break;
            default:
                $this->mostraBaixaEstoque('');
               
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------

    function mostraBaixaEstoque($mensagem, $tipoMsg = NULL) {
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);

        // CENTRO DE CUSTO
        $sql = "select CENTROCUSTO AS id, descricao from FIN_CENTRO_CUSTO";
        $this->comboSql($sql, $this->m_empresacentrocusto, $ccusto_id, $ccusto_ids, $ccusto_names);
        $this->smarty->assign('centroCusto_ids',   $ccusto_ids);
        $this->smarty->assign('centroCusto_names', $ccusto_names);

        $this->smarty->assign('centroCustoOrigem',  $ccusto_id);
        $this->smarty->assign('centroCustoDestino', $ccusto_id);

        $this->smarty->display('baixa_estoque_mostra.tpl');
    }

    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $ids[0] = '';
        $names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i+1] = $result[$i]['ID'];
            $names[$i+1] = $result[$i]['DESCRICAO'];
        }
        
        $param = explode(",", $par);
        $i=0;
        $id[$i] = "0";
        while ($param[$i] != '') {
            $id[$i] = $param[$i];
            $i++;
        }    
    }

    function insereQuant($quant) {
        $objEstProduto = new c_produto_estoque();
        $classNF = new c_nota_fiscal();
        $classNFProduto = new c_nota_fiscal_produto();
        $tipoNf = '0';
    
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
        $clientePadrao = $parametros->getField("CLIENTEPADRAO", "FILIAL=".$this->m_empresacentrocusto);
        $parametros->close_connection();                        
        
        //$qtde = $quant;//(int) $quant;
        //if ($qtde < 0){
        //    $qtde = $qtde * -1;
        //    $tipoNf = '1';
        //}
        
        $qtde = $quant;//(int) $quant;
        if ($qtde < 0){
            $exp = explode('-',$qtde);
            $newQtd = str_replace('.', '', $exp[1]);
            $newQtd = str_replace(',', '.', $newQtd) * 1;
            //$newQtd = intval(str_replace(',', '.', $newQtd));
            $qtde = $newQtd;
            $tipoNf = '1';
        } 
        
        if($tipoNf == '1'){
            $this->m_modelo = '1';
            $this->m_serieDocto = 'SAI';
        }else{
            $this->m_modelo = '0';
            $this->m_serieDocto = 'ENT';
        }

        $vlrVenda = str_replace(',', '.', $this->valorVenda);
        $quantidade = str_replace(',', '.', $qtde);
        $totalProd = ($quantidade * $vlrVenda);

        $totalFormatado = number_format((double) $totalProd, 2, ',', '.');

       //EST_NOTA_FISCAL
        $classNF->setModelo($this->m_modelo);
        $classNF->setSerie($this->m_serieDocto);
        $classNF->setNumero(0);
        $classNF->setPessoa($this->id_pessoa);
        $classNF->setEmissao(date('d/m/Y H:i:s'));
        //nat operacao
        $classNF->setIdNatop(99);
        $classNF->setNatOperacao('AJUSTE QUANTIDADE DE ESTOQUE');
        $classNF->setTipo($tipoNf); // 0=Entrada; 1=Saída; 
        $classNF->setSituacao('B');
        $classNF->setFormaPgto('0');
        $classNF->setDataSaidaEntrada(date('d/m/Y H:i:s'));
        $classNF->setFinalidadeEmissao(9);
        $classNF->setTransportador(0);
        $classNF->setCentroCusto($this->ccustoOrigem);
        $classNF->setGenero($this->m_genero);
        $classNF->setOrigem('AJT');
        $classNF->setDoc(0);
        $classNF->setModFrete(0); // verificar outras opção de frete no XML
        $classNF->setTotalnf($totalFormatado);
        $classNF->setObs($this->m_obsNf);	
        // insere nf
        $lastNF = $classNF->incluiNotaFiscal();

        $classNF->setId($lastNF);
        $classNF->setNumero($lastNF);
        $classNF->alteraNfNumero();
        
       //EST_NOTA_FISCAL_ESTOQUE
        

        $total = 1;
        $classNFProduto->setIdNf($lastNF);
        $classNFProduto->setCodProduto($this->id_produto);
        $classNFProduto->setDescricao($this->desc_prod);
        $classNFProduto->setUnidade($this->unidade_prod);
        $classNFProduto->setQuant($qtde);
        $classNFProduto->setUnitario($this->valorVenda);
        $classNFProduto->setTotal($totalFormatado);
        $classNFProduto->setOrigem('0');
        $classNFProduto->setTribIcms('00');
        $classNFProduto->setCfop('9999');
        $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));
        $classNFProduto->incluiNotaFiscalProduto();
        
        // QUANTIDADE PRODUTO_ESTOQUE 
        
        $ifControlaEstoque = (($controlaEstoque == 'S') && ($this->uniFracionada == 'N'));
        if ($ifControlaEstoque):
            $objEstProduto = new c_produto_estoque();
            if ($tipoNf == '0'):
                for ($i = 0; $i < $qtde; $i++) {
                    $objEstProduto->setIdNfEntrada($lastNF);
                    $objEstProduto->setCodProduto($this->id_produto);
                    $objEstProduto->setStatus('0');
                    $objEstProduto->setAplicado('0');
                    $objEstProduto->setCentroCusto($this->ccustoOrigem);
                    $objEstProduto->setUserProduto($this->m_userid);
                    $objEstProduto->setLocalizacao('');
                    //$objEstProduto->setNsEntrada($this->getNumSerie());
                    $objEstProduto->setFabLote('');
                    $objEstProduto->setDataFabricacao('');
                    $objEstProduto->setDataValidade('');
                    $objEstProduto->incluiProdutoEstoque();
                }//for
                
            else:
                $objEstProduto->produtoBaixa($this->ccustoOrigem, $this->id_produto, $qtde, $lastNF);
            endif;
        endif;

        return $lastNF;
        
    }

//fim mostraBaixaEstoques
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$consultas = new p_baixa_estoque();

$consultas->controle();
?>
