<?php
/**
 * @package   astecv3
 * @name      c_pedido_venda_tools
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      28/06/2017
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../class/cat/c_atendimento_pedido.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class 
Class c_atendimento_pedido_tools extends c_atendimento_pedido {

/*=====================SERVIÇOS===============================*/ 

/**
 * <b> Rotina que inclui um Servico do atendimento</b>
 * @name incluiAtendimentoPedidoItensControle
 * @param INT cc
 * @param INT idAtendimento
 * @param INT idItem
 * @param Float itemValor
 * @return String Mensagem de sucesso ou falha.
 * @author    Tony
 * @date      17/03/2021
 */
public function incluiAtendimentoPedidoItensControle($conn=null, $arrItensPed, &$idPedido, $usrFatura){
    // try{ 
       
        $item = explode("|", $arrItensPed);      

        $numItem = 1;    
        for ($i=1; $i<count($item); $i++){
            $itemArr = explode("*", $item[$i]);
            $this->setPedido($idPedido);
            $this->setNrItem($numItem);
            $this->setItemEstoque($itemArr[0]);
            $this->setCodigoNota($itemArr[1]);
            $this->setDescricaoItem($itemArr[2]);

            $this->setQtSolicitada($itemArr[6]);
            $this->setUnitario($itemArr[7]);
            $this->setPercDescontoItem($itemArr[8]);
            $this->setDescontoItem($itemArr[9]);
            $this->setTotalItem($itemArr[10]);
            $this->setUsrFatura($usrFatura);

            $consulta = new c_banco;
            $consulta->setTab("EST_PRODUTO");
            $codFabricante = $consulta->getField("CODFABRICANTE", "CODIGO=".$itemArr[0]);
            $consulta->close_connection();

            $this->setItemFabricante($codFabricante);


            $consulta = new c_banco;
            $consulta->setTab("EST_PRODUTO");
            $custoCompra = $consulta->getField("CUSTOCOMPRA", "CODIGO=".$itemArr[0]);
            $consulta->close_connection();

            $custo = $custoCompra * $this->getQtSolicitada('B');
            $this->setCusto($custo,true);
                
            $despesas = 0;
            $this->setDespesas($despesas);
                
            $totalItem = $this->getTotalItem('B');
            $lucrobruto = floatval($totalItem) - $custo;
            $this->setLucroBruto($lucrobruto,true);
            $this->setMargemLiquida( ($lucrobruto - $despesas) ,true);
            $this->setMarkUp(round((($lucrobruto / floatval($totalItem)) * 100 ), 2),true); 

            $this->incluiPedidoItem($conn);

            $numItem++;

        }
        
        
        $tipoMsg = "sucesso";

        return '';
    // } catch (Error $e) {
    //     throw new Exception($e->getMessage()."Item não cadastrado " );

    // } catch (Exception $e) {
    //     throw new Exception($e->getMessage(). "Item não cadastrado " );

    // }
                    
}
/**
 * <b> Rotina que altera um Servico do atendimento </b>
 * @name alteraServicoAtendimentoControle
 * @param  INT idAtendimento
 * @param  Array arrServico
 * @param  INT situacao
 * @return String Mensagem de sucesso ou falha.
 * @author Tony
 * @date   17/03/2021
 */

public function alteraServicoAtendimentoControle($idAtendimento, $arrServico, $situacao){
    try{
        
        $this->setCodigoNota($codNota); 

        $calculoST = 'N';                                                
        if ($calculoST == 'S') { 
            $this->setBcIcms(0, true);
            $this->setValorIcms(0, true);
            $this->setValorIcmsDiferido(0, true);
            $this->setValorIcmsOperacao(0, true);
            $this->setValorBcSt(0, true);
            $this->setValorIcmsSt(0, true);
            $this->setMvaSt(0, true);
            $this->setAliqIcmsSt(0, true);
            $this->setAliqRedBCST(0, true);  
            $this->setAliqIcmsUfDest(0, true);        
            $this->setAliqIcmsInter(0, true);
            $this->setAliqIcmsInterPart(0, true);
            $this->setFcpUfDest(0, true);
            $this->setValorIcmsUfDest(0, true); 
            $this->setValorIcmsUFRemet(0, true);

            //ICMS/ICMS-ST
            $bcIcms = 0;
            $aliqIcms = 0;
            $vlIcms = 0;
            $vlIcmsDiferido = 0;
            $vlIcmsOperacao = 0;
            $vlBcSt = 0;
            $vlIcmsSt = 0;
            $mvaSt = 0;
            $aliqIcmsSt = 0;
            $percReduacaoBcSt = 0;
            //DIFAL
            $aliqFcpSt = 0;
            $aliqIcmsInter = 0;
            $aliqIcmsInterPart = 0;
            $vlFcpUfDest = 0;
            $vlDifal = 0;
            $vlIcmsUFRemet = 0;
            //PIS/COFINS
            $aliqPis = 0;
            $vlPis = 0;
            $aliqCofins = 0;
            $vlCofins = 0;    

            // BUSCA CLIENTE
            $banco = new c_banco();
            $sql = "select * from fin_cliente where (cliente=".$this->getCliente().")";
            $cliente = $banco->exec_sql($sql);
            $contribuinteICMS = $cliente[0]['CONTRIBUINTEICMS'];
            $consumidorfinal = $cliente[0]['CONSUMIDORFINAL'];
            $regimeespecialST = $cliente[0]['REGIMEESPECIALST'];
            $regimeespecialSTMT = $cliente[0]['REGIMEESPECIALSTMT'];
            $ufDestino = $cliente[0]['UF'];        

            // BUSCA EMPRESA (CRT/UF)
            $sql = "select * from amb_empresa where (centrocusto=".$this->m_empresacentrocusto.")";
            $emp = $banco->exec_sql($sql);
            $crt = $emp[0]['REGIMETRIBUTARIO'];
            $empresaUF = $emp[0]['UF'];

            // BUSCA EST_NAT_OP_TRIBUTO
            $sql  = "SELECT * FROM EST_NAT_OP_TRIBUTO ";
            $sql .= "WHERE (CENTROCUSTO =".$this->m_empresacentrocusto.") AND (IDNATOP = 9 ) ";
            $sql .= "AND (UF='".$cliente[0]['UF']."') AND (PESSOA='".$cliente[0]['PESSOA']."') AND ";
            if ($crt=='3'):
                $sql .= "(ORIGEM='".$arrProduto[0]['ORIGEM']."') AND (TRIBICMS='".$arrProduto[0]['TRIBICMS']."') AND ((NCM='".$arrProduto[0]['NCM']."') OR (NCM='')) AND ((CEST='".$arrProduto[0]['CEST']."') OR (CEST=''));";
            else:    
                $sql .= "(ORIGEM='".$arrProduto[0]['ORIGEM']."') ";
            endif;

            if ($arrProduto[0]['NCM'] != ''){
                $sql .= " and (NCM='".$arrProduto[0]['NCM']."');";
            }
            
            $banco->exec_sql($sql);
            $banco->close_connection();
            $arrTributos =  $banco->resultado;

            $insideIpiBc = 'N'; //??????


            $aliqIcms = $arrTributos[0]['ALIQICMS'];
            $aliqFcpSt = $arrTributos[0]['ALIQFCPST'];
            $mvaSt = $arrTributos[0]['MVAST'];
            $aliqIcmsSt = $arrTributos[0]['ALIQICMSST'];
            $percReducaoBc = $arrTributos[0]['PERCREDUCAOBC'];
            $percReducaoBcSt = $arrTributos[0]['PERCREDUCAOBCST'];
            $percDiferido = $arrTributos[0]['PERCDIFERIDO'];
            $cfop = $arrTributos[0]['CFOP'];
            $tribicms = $arrTributos[0]['TRIBICMS'];
            $csosn = $arrTributos[0]['TRIBICMSSAIDA'];
            //DIFAL
            $aliqIcmsInter = 0;
            $aliqIcmsInterPart = 0;
            //PIS/COFINS - Aliquotas
           

            $totalProduto = $this->getTotalItem('B');;
            $bcIcms = $totalProduto;
       
            //$vlBcSt = $bcIcms;
            $vlIpi = 0; // ?????? calcular
            
            $totalProduto = $totalItem;
            $descontoProduto = 0;
            $freteItem = 0;
            $despAcessoriasItem = 0;
            $seguroItem = 0;

            $origem = $arrProduto[0]['ORIGEM'];
            
            $calculoDifalNovo="S";

            //IPI
            // CALCULAR IPI 
            // controle da CST na tela? ipi na tela? 
            
            //ICMS
            // CALCULAR ICMS
            if (( $csosn == '00') || ( $csosn == '10')  || ( $csosn == '30'))
            {
                $vlIcms = ($aliqIcms/100)*$bcIcms;    
            }
                
            // CALCULAR DIFAL //00 e 102 //DIFAL
            // CALCULAR DIFAL-FCP
            //<ICMSUFDest> - Informação do ICMS Interestadual
            // Grupo a ser informado nas vendas interestaduais para consumidor final, não contribuinte do ICMS.
            if (($csosn == '00') || ($csosn == '102'))
            {
                if (($contribuinteICMS=="N") && ($consumidorfinal=="S"))
                {
                    // <vBCUFDest> - Valor da BC do ICMS na UF destino
                    if ($crt==1){ // Simples Nacional
                        $vlbcIcmsUfDest = $totalProduto-$descontoProduto+
                            $freteItem+$despAcessoriasItem+$seguroItem;
                    }
                    else{
                        $vlbcIcmsUfDest = $bcIcms;
                    }
                    //cAliqFecoepUFDest => $aliqFcpSt
                    //cAliqIcmsUFDest   => $aliqIcmsSt
                    if ($aliqFcpSt>0.01){
                        $aliqIcmsSt -= $aliqFcpSt;
                    }
            
                    // <pFCPUFDest> - Percentual do ICMS relativo FCP na UF de destino $aliqFcpSt
            
                    // <pICMSUFDest> - Alíquota interna da UF de destino $aliqIcmsSt
            
                    // <pICMSInter> - Aliquota de ICMS interestadual - cAliqIcmsInter (4, 7, 12)
                    
                    $aliqIcmsInter = $aliqIcms;
                    //<pICMSInterPart> Percentual provisório de partilha do ICMS Interestadual 
                    // 100% a partir de 2019.
                    $aliqIcmsInterPart = 100.00;                    
                    //Cálculo FCP
                    if ($aliqFcpSt>0.01){
                        //<vBCFCPUFDest> - Valor da BC FCP na UF de destino
                        $bcFecoepUFDest = $vlbcIcmsUfDest;
                        //<vFCPUFDest> - Valor do FCP UF Dest
                        $vlFcpUfDest = $bcFecoepUFDest*($aliqFcpSt/100);
                    }
                    // <vICMSUFDest> Cálculo Difal  BC * (18-12=6)
                    $vlDifal = $vlbcIcmsUfDest * (($aliqIcmsSt-$aliqIcmsInter) / 100);
                    // <vICMSUFRemet> Valor do ICMS Interestadual para a UF do remetente
                    // Nota: A partir de 2019, este valor será zero.
                    $vlIcmsUFRemet = 0.00;
                }    
            }

            //ST
            // CALCULAR ST
            // CALCULAR DIFAL-ST //'10' '30' '70' '201' '202' '203'    
            // CALCULAR FCP-ST
            if (( $csosn == '10') || ( $csosn == '30')  || ( $csosn == '70') || 
                ($csosn == '201') || ( $csosn == '202') || ( $csosn == '203') )
            {
                //Rodrigo
                //Base de ICMS-ST
                //Cálculo ICMS-ST (Normal)
                if (($regimeespecialST=="N") && ($regimeespecialSTMT=="N") &&
                    ($contribuinteICMS=="S") && ($consumidorfinal=="N"))                                    
                {
                    $vlBcSt = $bcIcms;
                    if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                        $vlBcSt += $vlIpi;
                    endif;
                    $vlBcSt = ($vlBcSt * (1 + ($mvaSt/100))); // aplica indice mva bc st
                    $vlIcmsSt = (($vlBcSt)*($aliqIcmsSt/100)) - $vlIcms; //calcula icms st
                }
            //Cálculo ICMS-ST (DIFAL-ST)
            else if (($regimeespecialST=="N") && ($regimeespecialSTMT=="N") &&
                    ($contribuinteICMS=="S") &&  ($consumidorfinal=="S"))                                    
            {
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;

                if ($calculoDifalNovo=="S") {
                    $vlBcSt = $bcIcms - $vlIcms;
                    $VlSubTribDif = 1 - ($aliqIcmsSt/100);
                    $vlBcSt = $vlBcSt/$VlSubTribDif;
                    $vlIcmsInterna = $vlBcSt*($aliqIcmsSt/100);
                    $vlIcmsSt = $vlIcmsInterna-$vlIcms; 
                } else {
                    $vlIcmsSt = ( ($aliqIcmsSt - $aliqIcms) / 100 ) * $bcIcms;
                }                   
            } 
            //Cálculo ICMS-ST (MT)
            else if (($ufDestino=="MT") && ($regimeespecialSTMT=="S") &&
                    ($regimeespecialST=="N") && ($contribuinteICMS=="S"))
            {
                /*
                if (($origem==1) || ($origem==6)){
                    $vlIcmsProprio = $vlBcSt*4/100;
                }
                else{
                    $vlIcmsProprio = $vlBcSt*$aliqIcms/100; 
                }
                */
                $vlIcmsProprio = $vlBcSt*$aliqIcms/100; 
                //$valorSTEstimativa = ($vlBcSt + $vlIpi) * ($aliqRegEspSTMT/100);                    
                $valorSTEstimativa = $vlBcSt* ($aliqRegEspSTMT/100);
                $vlTotl=$vlIcmsProprio+$valorSTEstimativa/($aliqIcmsSt/100);
                $vlIcmsSt=$valorSTEstimativa;
                $vlBcSt=$vlTotl;
            }                    
            //ICMS-ST e FCP-ST
            if ($aliqFcpSt > 0.01){
                $vlBcFcpSt = $eBaseSubTrib;
                $vlFcpSt = $vlBcFcpSt*($aliqFcpSt/100);
                //Descontar o valor da ST
                $vlIcmsSt -= $vlFcpSt;
            }           
        }

        switch ($arrTributos[0]['TRIBICMSSAIDA']){
            case '00': // tributado integralmente
                //ICMS
                // observacao
                //DIFAL                     

                break;
            case '10': // Tributada e com cobrança do ICMS por substituição tributária
                //ICMS

                //ICMS-ST
                break;
            case '20': // Tributação com redução de base de cálculo
                $bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao
                $vlIcms = ($aliqIcms/100) * $bcIcms;

                break;
            case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                //ICMS
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReducaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*($vlBcSt)) - $vlIcms; //calcula icms st
                $bcIcms = 0;
                $vlIcms = 0;
                break;
            case '40': // Tributação Isenta
            case '41': // Não tributada
            case '50': // Suspensão
                $bcIcms = 0;
                $vlIcms = 0;
                $vlBcSt = 0;
                $vlIcmsSt = 0;
                $vlBcStRet = 0;
                $vlIcmsStRet = 0;
                break;
            case '51': // Tributação com Diferimento (a exigência do preenchimento das
                    //informações do ICMS diferido fica a critério de cada UF).

                $bcIcms = ($percReducaoBc/100)*$totalProduto;
                $vlIcmsDiferido = ($percDiferido/100)*$bcIcms;
                $vlIcmsOperacao = ($aliqIcms/100)*$bcIcms;
                $vlIcms = $vlIcmsOperacao-$vlIcmsDiferido;
                break;
            case '60': // Tributação ICMS cobrado anteriormente por substituição tributária
                // buscar valor de impostos retido na nf de entrada
                /*
                O CST 060 significa: mercadoria de origem nacional, e ICMS cobrado anteriormente por Substituição Tributária.
                Como o ICMS já foi cobrado anteriormente, esse imposto NÃO deve ser destacado na próxima circulação da mercadoria, 
                em operações internas. Então, utiliza-se o CST 060. 
                O ICMS devido por este contribuinte já foi pago na entrada da mercadoria, por Substituição Tributária, 
                com margem de lucro e já recolhido aos cofres estaduais, pelo remetente. 
                Portanto o sistema está correto em não destacar “Base de Cálculo do ICMS” e “Valor do ICMS”, 
                porque o imposto já foi recolhido por ST. 
                É necessário, porém, que seja informado no campo “Dados Adicionais – Informações Complementares”, da nota fiscal, 
                o dispositivo legal que permite o não destaque do ICMS; em SC, o dispositivo legal é: 

                “ Imposto Retido por Substituição Tributária – RICMS-SC/01 – Anexo 3”. 

                Em toda nota fiscal, modelo 1, 1-A ou 55 (eletrônica), é obrigatório informar qual o dispositivo legal que permite 
                o não destaque do ICMS. 
                Há também nos Regulamentos de ICMS, uma determinação que seja indicada no campo “Dados Adicionais – 
                Informações Complementares”, quando da emissão dos mesmos modelos de notas fiscais acima mencionados, 
                a base de cálculo e o valor do imposto retido, salvo nas saídas destinadas a não contribuinte. 

                Essas informações são obtidas na NF de compra, onde o ICMS Substituto é cobrado. 
                Se faz necessária esse informação porque o destinatário poderá creditar esse ICMS, 
                no caso de mercadoria para industrialização ou ativo imbolizado. 

                Quem utiliza o CST 060, é o Contribuinte Substituído, ou seja, aquele que pagou antecipadamente o 
                ICMS que seria de sua obrigação, quando da saída posterior da mercadoria.                  

                */
                $bcIcms = 0;
                // ******* buscat o valor st retido na nf de entrada
                $vlBcStRet = 0;
                $vlIcmsStRet = 0;
                break;
            case '70': // Tributação ICMS com redução de base de cálculo e cobrança
                    // do ICMS por substituição tributária
                $bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao Bc
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReducaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*$vlBcSt) - $vlIcms; //calcula icms st


                break;
            case '90': // Tributação ICMS: Outros
                $bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao Bc
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="S"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReducaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*$vlBcSt) - $vlIcms; //calcula icms st
                break;
            case '102': // Tributação com redução de base de cálculo
                //$bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao
                //$vlIcms = ($aliqIcms/100)*$bcIcms;
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                // observacao
                
                //DIFAL
                            
                break; 
            case '201': // Tributada e com cobrança do ICMS por substituição tributária
                $vlIcms = ($aliqIcms/100)*$bcIcms;

                //ICMS-ST             
                break;
            case '202': // Tributada e com cobrança do ICMS por substituição tributária
                $vlIcms = ($aliqIcms/100)*$bcIcms;

                //ICMS-ST
            
                break;
            case '500': 
                $bcIcms = 0;
                // ******* buscat o valor st retido na nf de entrada
                $vlBcStRet = 0;
                $vlIcmsStRet = 0;
                break;             
        }

        

        //setar valores calculados
        $this->setBcIcms($bcIcms, true);
        $this->setValorIcms($vlIcms, true);
        $this->setValorIcmsDiferido($vlIcmsDiferido, true);
        $this->setValorIcmsOperacao($vlIcmsOperacao, true);
        $this->setValorBcSt($vlBcSt, true);
        $this->setValorIcmsSt($vlIcmsSt, true);
        $this->setMvaSt($mvaSt, true);
        $this->setAliqIcmsSt($aliqIcmsSt, true);
        $this->setAliqRedBCST($percReduacaoBcSt, true);  
        $this->setAliqIcmsUfDest($aliqFcpSt, true);        
        $this->setAliqIcmsInter($aliqIcmsInter, true);
        $this->setAliqIcmsInterPart($aliqIcmsInterPart, true);
        $this->setFcpUfDest($vlFcpUfDest, true);
        $this->setValorIcmsUfDest($vlDifal, true); 
        $this->setValorIcmsUFRemet($vlIcmsUFRemet, true);
        $this->setCfop($cfop);
        $this->setOrigem($origem);
        $this->setTribIcms($tribicms);
        $this->setCsosn($csosn);
    }


        return $msg;
    } catch (Error $e) {
        throw new Exception($e->getMessage()."Item não alterado " );

    } catch (Exception $e) {
        throw new Exception($e->getMessage(). "Item não alterado " );

    }
} 


}	//	END OF THE CLASS
?>
