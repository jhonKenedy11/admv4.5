<?php
/**
 * @package   astec
 * @name      p_baixa_estoque_new
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
require_once($dir."/../../class/est/c_estoque_rel.php");

//Class p_baixa_estoque_new
Class p_baixa_estoque_new extends c_produto {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;
    private $m_mostraRelMatConsumoConta = null;
    private $m_id = null;
    private $id_produto   = null;
    private $desc_prod    = null;
    private $unidade_prod = null;
    private $valorVenda   = null;
    private $uniFracionada= null;
    private $id_pessoa    = null;
    private $m_quantNova  = null;
    private $m_quantAnterior  = null;
    private $m_modelo     = null;
    private $m_serieDocto = null;
    private $m_numDocto   = null;
    private $m_genero     = null;
    private $m_obsNf      = null;
    private $ccustoOrigem  = null;
    private $ccustoDestino = null;

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
        $this->m_id=(isset($parmGet['id']) ? $parmGet['id'] : (isset($parmPost['id']) ? $parmPost['id'] : ''));
        $this->id_produto   =(isset($parmGet['codProduto']) ? $parmGet['codProduto'] : (isset($parmPost['codProduto']) ? $parmPost['codProduto'] : ''));
        $this->desc_prod    =(isset($parmGet['descProduto']) ? $parmGet['descProduto'] : (isset($parmPost['descProduto']) ? $parmPost['descProduto'] : ''));
        $this->unidade_prod =(isset($parmGet['unidade']) ? $parmGet['unidade'] : (isset($parmPost['unidade']) ? $parmPost['unidade'] : ''));
        $this->valorVenda   =(isset($parmGet['valorVenda']) ? $parmGet['valorVenda'] : (isset($parmPost['valorVenda']) ? $parmPost['valorVenda'] : ''));
        $this->uniFracionada=(isset($parmGet['uniFracionada']) ? $parmGet['uniFracionada'] : (isset($parmPost['uniFracionada']) ? $parmPost['uniFracionada'] : ''));
        $this->id_pessoa    =(isset($parmGet['pessoa']) ? $parmGet['pessoa'] : (isset($parmPost['pessoa']) ? $parmPost['pessoa'] : ''));
        $this->m_quantNova  =(isset($parmGet['qtdeEntrada']) ? $parmGet['qtdeEntrada'] : (isset($parmPost['qtdeEntrada']) ? $parmPost['qtdeEntrada'] : 0));
        $this->m_modelo     =(isset($parmGet['modelo']) ? $parmGet['modelo'] : (isset($parmPost['modelo']) ? $parmPost['modelo'] : ''));
        $this->m_serieDocto =(isset($parmGet['serieNf']) ? $parmGet['serieNf'] : (isset($parmPost['serieNf']) ? $parmPost['serieNf'] : ''));
        $this->m_numDocto   =(isset($parmGet['numDocto']) ? $parmGet['numDocto'] : (isset($parmPost['numDocto']) ? $parmPost['numDocto'] : ''));
        $this->m_genero     =(isset($parmGet['genero']) ? $parmGet['genero'] : (isset($parmPost['genero']) ? $parmPost['genero'] : ''));
        $this->m_obsNf      =(isset($parmGet['obs']) ? $parmGet['obs'] : (isset($parmPost['obs']) ? $parmPost['obs'] : ''));
        
        $this->ccustoOrigem =(isset($parmGet['centroCustoOrigem']) ? $parmGet['centroCustoOrigem'] : (isset($parmPost['centroCustoOrigem']) ? $parmPost['centroCustoOrigem'] : $this->m_empresacentrocusto));
        $this->ccustoDestino=(isset($parmGet['centroCustoDestino']) ? $parmGet['centroCustoDestino'] : (isset($parmPost['centroCustoDestino']) ? $parmPost['centroCustoDestino'] : ''));
		 
        $this->m_mostraRelMatConsumoConta=(isset($parmGet['mostraRelMatConsumoConta']) ? $parmGet['mostraRelMatConsumoConta'] : (isset($parmPost['mostraRelMatConsumoConta']) ? $parmPost['mostraRelMatConsumoConta'] : false));
        $this->m_quantAnterior  =(isset($parmGet['mQuantidade']) ? $parmGet['mQuantidade'] : (isset($parmPost['mQuantidade']) ? $parmPost['mQuantidade'] : 0));
        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"):
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        else:
            $this->smarty->assign('titulo', "Consulta");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]"); 
            $this->smarty->assign('disableSort', "[ 8 ]"); 
            $this->smarty->assign('numLine', "25"); 
        endif;
    
            
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'inclui':
                $res = $this->validacaoTipoGrupoProduto($this->id_produto);
                if($res != ''){
                    $this->valorVenda = $res;
                    $quant = str_replace('.', '',$this->m_quantNova);
                    $quant = str_replace(',', '.', $quant);

                    if (abs($quant) > 0) {
                        $result = $this->insereQuant($this->m_quantNova);
                        $this->m_submenu = '';
                        $this->m_mostraRelMatConsumoConta = true;
                        $msg = 'Quantidade estoque ajustada ! <br> Documento nº '.$result;
                        $this->cadastroBaixaEstoque($msg, 'sucesso');
                    } else {
                        $this->m_submenu = '';
                        $msg = 'Quantidade inválida !!';
                        $this->cadastroBaixaEstoque($msg, 'alerta');
                    }  
                }else{
                    $this->m_submenu = '';
                    $msg = 'Verificar Preço de Venda ou Valor de Ultima Compra do Produto '.$this->id_produto." - ".$this->desc_prod;
                    $this->cadastroBaixaEstoque($msg, 'alerta');
                }
                  
            break;
            case 'relatorioMatConsumoConta':
                $this->cadastroBaixaEstoque('');
                break;
            case 'cadastrar':
                $this->cadastroBaixaEstoque('');
                break;
            case 'altera':
                $quant = str_replace('.', '',$this->m_quantNova);
                $quant = str_replace(',', '.', $quant);

                $objNf = new c_nota_fiscal();

                $objNf->alteraMovEstoqueSaida($this->m_id, $quant);
                $this->atualizaQuantLog($this->m_id);
                $this->m_submenu = '';
                $this->mostraBaixaEstoque('Quantidade Alterada.', 'sucesso');
                break;

            case 'exclui':
                $this->excluiNfProduto($this->m_id);
                $objNf = new c_nota_fiscal();
                $objNf->setId($this->m_id);
                $objNf->excluiNotaFiscal();
                $this->m_submenu = '';
                $this->mostraBaixaEstoque('Nf Excluido.', 'sucesso');
                break;
            default:
                $this->mostraBaixaEstoque('');
               
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------

    function cadastroBaixaEstoque($mensagem, $tipoMsg = NULL) {
        
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

        $consulta = new c_banco();
        $consulta->setTab("EST_PARAMETRO");
        $genero = $consulta->getField("GENEROMOVIMENTO", "FILIAL =".$this->m_empresacentrocusto);
        $consulta->close_connection();

        if(!empty($genero)){
            $consulta = new c_banco();
            $consulta->setTab("FIN_GENERO");
            $generoDesc = $consulta->getField("DESCRICAO", "GENERO =".$genero);
            $consulta->close_connection();

            $this->smarty->assign('genero', $genero);
            $this->smarty->assign('descGenero', $generoDesc);
        }

        if( $this->m_mostraRelMatConsumoConta == true){
            $dataIni =  date("01/m/Y");
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $dataFim = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            /* pesquisa params 
                [0] - DataIni
                [1] - Data fim
                [4] - id Pessoa
                [6] - centro Custo
            */
            $letraPesquisa = $dataIni."|".$dataFim."|||".$this->id_pessoa."||".$this->m_empresacentrocusto;
            $objRelEstoque = new c_estoque_rel();

            $lanc = $objRelEstoque->select_relatorio_mov_estoque($letraPesquisa);

            $cliente = new c_conta();
            $cliente->setId($this->id_pessoa);
            $clienteSelecionado = $cliente->select_conta();    
        
            $this->smarty->assign('cliente', $clienteSelecionado[0]['NOME']);

            $this->smarty->assign('pedido', $lanc);
            $this->smarty->assign('periodoIni', $dataIni);
            $this->smarty->assign('periodoFim', $dataFim);

            $this->smarty->assign('pessoa', $this->id_pessoa);
            $this->smarty->assign('nome', $clienteSelecionado[0]['NOME']);
        }
        $this->smarty->assign('mostraRelMatConsumoConta', $this->m_mostraRelMatConsumoConta);


        $this->smarty->display('baixa_estoque_mostra_new.tpl');
    }

    function mostraBaixaEstoque($mensagem, $tipoMsg = NULL) {
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);

        if($this->m_letra != ''){
            $objNf = new c_nota_fiscal();
            $letra = "0|1|B|".$this->m_par[2]."|".$this->m_par[3]."|".$this->m_par[0]."||||||||AJT|".$this->m_par[4];
            $lanc =   $objNf->select_nota_fiscal_letra($letra, true);
        }

        $this->smarty->assign('lanc', $lanc);
        
        if ($this->m_par[2] == "")
            $this->smarty->assign('dataIni', date("01/m/Y"));
        else
            $this->smarty->assign('dataIni', $this->m_par[2]);

        if ($this->m_par[3] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = mktime(0, 0, 0, $mes + 1, 0, $ano);
            $this->smarty->assign('dataFim', date("d/m/Y", $data));
        } else
            $this->smarty->assign('dataFim', $this->m_par[3]);

        if($this->m_par[4] != ''){
            $consulta = new c_banco();
            $consulta->setTab("EST_PRODUTO");
            $produtoDesc = $consulta->getField("DESCRICAO", "CODIGO =".$this->m_par[4]);
            $consulta->close_connection();

            $this->smarty->assign('codProduto', $this->m_par[4]);
            $this->smarty->assign('pesProduto', $produtoDesc);
        }


        $this->smarty->display('baixa_estoque_consulta_new.tpl');
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

    function validacaoTipoGrupoProduto($idProduto){
        $sql = "SELECT GRUPO, VENDA, CUSTOCOMPRA FROM EST_PRODUTO WHERE CODIGO ='".$idProduto."'";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        $consulta = new c_banco();
        $consulta->setTab("EST_GRUPO");
        $tipoGrupo = $consulta->getField("TIPO", "GRUPO ='".$result[0]['GRUPO']."'");
        $consulta->close_connection();

        if($tipoGrupo == 'V'){
            $valorVenda = $result[0]['VENDA'];
        }else if ($tipoGrupo == 'C'){
            $valorVenda = $result[0]['CUSTOCOMPRA'];
        }else{
            $valorVenda = '';
        }

        return $valorVenda;
    }

    function atualizaQuantLog($idNf){
        $consulta = new c_banco();
        $consulta->setTab("EST_NOTA_FISCAL");
        $obs = $consulta->getField("OBS", "ID ='".$idNf."'");
        $consulta->close_connection();

        $obs .= "<br> ".date("d/m/Y")." Quantidade Anterior: ".$this->m_quantAnterior."  Nova Quantidade: ".$this->m_quantNova;

        $sql = "UPDATE EST_NOTA_FISCAL SET OBS = '" . $obs . "' WHERE (id = '" . $idNf . "');";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }

    function excluiNfProduto($idNf){
        $sql = "DELETE FROM est_nota_fiscal_produto ";
        $sql .= "WHERE idnf = " . $idNf . ";";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
    }

    function insereQuant($quant) {
        $objEstProduto = new c_produto_estoque();
        $classNF = new c_nota_fiscal();
        $classNFProduto = new c_nota_fiscal_produto();
        $tipoNf = '1';
    
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
        $clientePadrao = $parametros->getField("CLIENTEPADRAO", "FILIAL=".$this->m_empresacentrocusto);
        $parametros->close_connection();                        
        
        $qtde = $quant;//(int) $quant;
        if ($qtde < 0){
            $qtde = $qtde * -1;
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
        $classNFProduto->setUnitario($this->valorVenda, true);
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

//fim cadastroBaixaEstoques
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$consultas = new p_baixa_estoque_new();

$consultas->controle();
?>
