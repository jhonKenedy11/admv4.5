<?php
/**
 * @package   astecv3
 * @name      c_inventario_tools
 * @version   3.0.00
 * @copyright 2021
 * @link      http://www.admservice.com.br/
 * @author   Tony
 * @date  17/03/2021
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/est/c_inventario.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");
require_once($dir . "/../../class/est/c_produto.php");



//Class c_inventario_tools
Class c_inventario_tools extends c_inventario {

/**
* <b> É responsavel gerar nota fiscal de ajuste de estoque para o inventário cadastrado </b>
* @name insereNfInventario
* @param $arrInventario, $idInventario, $tipoNf, $centroCusto
* @return ''
*/   
    function insereNfInventario($arrInventario, $idInventario, $tipoNf, $centroCusto) {
        // $objEstProduto = new c_produto_estoque();
        $classNF = new c_nota_fiscal();
        $classNFProduto = new c_nota_fiscal_produto();
        
        $parItens = explode("|", $arrInventario);
    
        $parametros = new c_banco;
        $parametros->setTab("EST_PARAMETRO");
        $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$centroCusto);
        $clientePadrao = $parametros->getField("CLIENTEPADRAO", "FILIAL=".$centroCusto);
        $parametros->close_connection();                      
        
        $parametros = new c_banco;
        $parametros->setTab("EST_INVENTARIO");
        $dateInv = $parametros->getField("CREATED_AT", "ID=".$idInventario);
        $parametros->close_connection();     

        $dateInv = date('d/m/Y H:i:s');
        //$dateInv = date('d/m/Y 23:59:59', strtotime($dateInv));

       //EST_NOTA_FISCAL
        $classNF->setModelo($tipoNf);
        $classNF->setSerie('INV');
        $classNF->setNumero($idInventario);
        $classNF->setPessoa(1);
        $classNF->setEmissao($dateInv);
        //nat operacao
        $classNF->setIdNatop(99);
        $classNF->setNatOperacao('INVENTARIO');
        $classNF->setTipo($tipoNf); // 0=Entrada; 1=Saída; 
        $classNF->setSituacao('B');
        $classNF->setFormaPgto('0');
        $classNF->setDataSaidaEntrada($dateInv);
        $classNF->setFinalidadeEmissao(9);
        $classNF->setTransportador(0);
        $classNF->setCentroCusto($centroCusto);
        $classNF->setGenero("INV");
        $classNF->setOrigem('INV');
        $classNF->setDoc($idInventario);
        $classNF->setModFrete(0); // verificar outras opção de frete no XML
        $classNF->setTotalnf($parItens[0]);
        
        //$classNF->setObs($this->m_obsNf);	
        // insere nf
        $lastNF = $classNF->incluiNotaFiscal();

        //$classNF->setId($lastNF);
        //$classNF->setNumero($lastNF);
        //$classNF->alteraNfNumero();
        
       //EST_NOTA_FISCAL_ESTOQUE
        
        for($i= 1; $i < count($parItens); $i++){
            $item = explode("*", $parItens[$i]);
            
            
            $classNFProduto->setIdNf($lastNF);
            $classNFProduto->setCodProduto($item[1]);
            $classNFProduto->setDescricao($item[2]);
            $classNFProduto->setUnidade($item[3]);
            $classNFProduto->setUnifrac($item[4]);
            $classNFProduto->setQuant($item[5]);
            $classNFProduto->setUnitario($item[6]);
            $classNFProduto->setTotal($item[7]);
            $classNFProduto->setOrigem('0');
            $classNFProduto->setTribIcms('00');
            $classNFProduto->setCfop('9999');
            $classNFProduto->setDataConferencia($dateInv);
            $classNFProduto->incluiNotaFiscalProduto();


            //quantidade movimentada
            $this->setQuantidade($item[5]);
            $this->setQuantAnterior($item[10]);
            $this->setQuantSerEntregue($item[9]);


            $this->alteraInventarioProdutoQtdeMov($item[9],$this->getQuantidade('B'), $this->getQuantAnterior('B'), $this->getQuantSerEntregue('B') ,$item[0]);

            $ifControlaEstoque = (($controlaEstoque == 'S') && ($item[4] == 'N'));
            if ($ifControlaEstoque):
                $objEstProduto = new c_produto_estoque();
                if ($tipoNf == '0'):
                    for ($j = 0; $j < $item[5] ; $j++) {
                        $objEstProduto->setIdNfEntrada($lastNF);
                        $objEstProduto->setCodProduto($item[1]);
                        $objEstProduto->setStatus('0');
                        $objEstProduto->setAplicado('0');
                        $objEstProduto->setCentroCusto($centroCusto);
                        $objEstProduto->setUserProduto($this->m_userid);
                        $objEstProduto->setLocalizacao('');
                        //$objEstProduto->setNsEntrada($this->getNumSerie());
                        $objEstProduto->setFabLote('');
                        $objEstProduto->setDataFabricacao('');
                        $objEstProduto->setDataValidade('');
                        $objEstProduto->incluiProdutoEstoque();
                    }//for
                else:
                    $objEstProduto->produtoBaixaPerda($centroCusto, $item[1], $item[5], $lastNF);
                endif;
            endif;
        }
        
        
        // QUANTIDADE PRODUTO_ESTOQUE 
        
        /*$ifControlaEstoque = (($controlaEstoque == 'S') && ($this->uniFracionada == 'N'));
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
        endif; */

        return $lastNF;
        
    }

/**
* <b> É responsavel gerar nota fiscal de ajuste de estoque  </b>
* @name nfAjusteEstoque
* @param $arrInventario, $idInventario, $tipoNf, $centroCusto
* @return ''
*/  
    function nfAjusteEstoque($pessoa = null, $produto, $qtde, $unitario, $total, $centroCusto, $tipoNf = '0', $serie, $modelo, $genero, $origem, $doc, $obsNf) {
        // $objEstProduto = new c_produto_estoque();
        $objProduto = new c_produto();
        $classNF = new c_nota_fiscal();
        $classNFProduto = new c_nota_fiscal_produto();
    
        if ($pessoa == null){
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=".$this->m_empresacentrocusto);
            $clientePadrao = $parametros->getField("CLIENTEPADRAO", "FILIAL=".$this->m_empresacentrocusto);
            $parametros->close_connection();                        
        }

        if ($qtde < 0){
            $qtde = $qtde * -1;
            $tipoNf = '1';
        } 

        $objProduto->setId($produto);
        $objProduto->produto();

       //EST_NOTA_FISCAL
        $classNF->setModelo($modelo);
        $classNF->setSerie($serie);
        $classNF->setNumero(0);
        $classNF->setPessoa($pessoa);
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
        $classNF->setCentroCusto($centroCusto);
        $classNF->setGenero($genero);
        $classNF->setOrigem($origem);
        $classNF->setDoc($doc);
        $classNF->setModFrete(0); // verificar outras opção de frete no XML
        $classNF->setTotalnf($total, true);
        $classNF->setObs($obsNf);	
        // insere nf
        $lastNF = $classNF->incluiNotaFiscal();

        $classNF->setId($lastNF);
        $classNF->setNumero($lastNF);
        $classNF->alteraNfNumero();
        
       //EST_NOTA_FISCAL_ESTOQUE
        
        // $total = 1;
        $classNFProduto->setIdNf($lastNF);
        $classNFProduto->setCodProduto($produto);
        $classNFProduto->setDescricao($objProduto->getDesc());
        $classNFProduto->setUnidade($objProduto->getUni());
        $classNFProduto->setQuant($qtde, true);
        $classNFProduto->setUnitario($unitario, true);
        $classNFProduto->setTotal($total, true);
        $classNFProduto->setOrigem('0');
        $classNFProduto->setTribIcms('00');
        $classNFProduto->setCfop('9999');
        $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));
        $classNFProduto->incluiNotaFiscalProduto();
        
        return $lastNF;
    }

}	//	END OF THE CLASS
?>
