<?php
/**
 * @package   astecv3
 * @name      c_atendimento_gera_nf_tools
 * @version   3.0.00
 * @copyright 2021
 * @link      http://www.admservice.com.br/
 * @author   Tony
 * @date  17/03/2021
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/cat/c_atendimento.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/est/c_cond_pgto.php");


//Class c_atendimento_gera_nf_tools
Class c_atendimento_gera_nf_tools extends c_atendimento {

   
    public function gera_os_nf($arrOrdemServico){
        try{
            $atendimento = explode("|", $arrOrdemServico);
            
            /* $atendimento[0] = id 
            $atendimento[1] = serieDocto 
            $atendimento[2] = numDocto 
            $atendimento[3] = modeloDocto 
            $atendimento[4] = dataFechamento
            $atendimento[5] = situacao 
            $atendimento[6] = cliente 
            $atendimento[7] = nomeCliente  
            $atendimento[8] = genero 
            $atendimento[9] = condPgtoId 
            $atendimento[10] = descCondPgto 
            $atendimento[11] = total 
            $atendimento[12] = naturezaOpId 
            $atendimento[13] = centroCusto
            $atendimento[13] = obs */     

            $objNotaFiscal = new c_nota_fiscal();
            
            $objNotaFiscal->setModelo($atendimento[3]);
            $objNotaFiscal->setSerie($atendimento[1]);
            $objNotaFiscal->setNumero($atendimento[2]); 

            if ($objNotaFiscal->existeNotaFiscal() == true):
                throw new Exception("Já existe nota fiscal autorizada para esta Ordem de Serviço: ".$atendimento[0]);
            endif;
            
            $objNotaFiscal->setPessoa($atendimento[6]); 
            $objNotaFiscal->setNomePessoa();
            $objNotaFiscal->setEmissao(date("d/m/Y H:i:s"));
            $objNotaFiscal->setIdNatop($atendimento[12]);            
            $objNotaFiscal->setTipo('1');
            $objNotaFiscal->setSituacao('A');                                
            $objNotaFiscal->setCondPgto($atendimento[9]);
            $objNotaFiscal->setDataSaidaEntrada(date("d/m/Y H:i:s"));
            $objNotaFiscal->setFormaEmissao('N');
            $objNotaFiscal->setFinalidadeEmissao('1');
            $objNotaFiscal->setCentroCusto($atendimento[13]);
            $objNotaFiscal->setGenero($atendimento[8]);//====????
            $objNotaFiscal->setTotalnf($atendimento[11]);//===
            $objNotaFiscal->setModFrete($this->modFrete);
            if ($this->transportador == ""){
                $this->transportador = '0';
            }
            $objNotaFiscal->setTransportador($this->transportador);
            $objNotaFiscal->setVolume($this->volume);
            $objNotaFiscal->setVolEspecie($this->volEspecie);
            $objNotaFiscal->setVolMarca($this->volMarca);
            $objNotaFiscal->setVolPesoLiq($this->volPesoLiq);
            $objNotaFiscal->setVolPesoBruto($this->volPesoBruto);                                
            $objNotaFiscal->setObs($atendimento[14]);
            $objNotaFiscal->setOrigem('OS');
            $objNotaFiscal->setDoc($atendimento[2]);                                
            $objNotaFiscal->setFrete("0");
            $objNotaFiscal->setModFrete("0");
                                    
            $objNotaFiscal->setFinalidadeEmissao(isset($this->parmPost['finalidadeEmissao']) ? $this->parmPost['finalidadeEmissao'] : "1");
            $objNotaFiscal->setNfeReferenciada(isset($this->parmPost['nfeReferenciada']) ? $this->parmPost['nfeReferenciada'] : "");
                            
                                    
            $idGerado = $objNotaFiscal->incluiNotaFiscal();
            return $idGerado;
        }catch (Error $e) {
            throw new Exception($e->getMessage()." Nota Fiscal não cadastrada " ); 
        }
       
    }

  

    public function adiciona_produto_nf($idGerado, $arrPecas){
        try{
            $objNotaFiscal = new c_nota_fiscal();
            $objNotaFiscal->setId($idGerado);
            $arrNf = $objNotaFiscal->select_nota_fiscal();
            $objNotaFiscal->setPessoa($arrNf[0]['PESSOA']); 
            $objNotaFiscal->setNomePessoa();
            $objNfProduto = new c_nota_fiscal_produto();
            
    
            for ($i = 0; $i < count($arrPecas); $i++) {

                if ($arrPecas[$i]['ORIGEM'] == ''):
                    $produto = new c_banco;
                    $produto->setTab("EST_PRODUTO");
                    $origemProduto = $produto->getField("ORIGEM", "CODIGO=".$arrPecas[$i]['CODPRODUTO']);
                    $produto->close_connection();
                    if($origemProduto == ''){
                        $this->m_msg = "Preencha campo origem no cadastro de produto! produto:".$arrPecas[$i]['DESCRICAO'];
                        throw new Exception( $this->m_msg );
                    }
                    $arrPecas[$i]['ORIGEM'] = $origemProduto;
                endif; 

                if ($arrPecas[$i]['TRIBICMS'] == ''):
                    $produto = new c_banco;
                    $produto->setTab("EST_PRODUTO");
                    $tribIcmsProduto = $produto->getField("TRIBICMS", "CODIGO=".$arrPecas[$i]['CODPRODUTO']);
                    $produto->close_connection();
                    if($tribIcmsProduto == ""){
                        $this->m_msg = "Preencha campo tribicms no cadastro de produto! produto:".$arrPecas[$i]['DESCRICAO'];
                        throw new Exception( $this->m_msg );
                    }
                    $arrPecas[$i]['TRIBICMS'] = $tribIcmsProduto;
                endif;                                    
                
                if ($arrPecas[$i]['NCM'] == ''):
                    $produto = new c_banco;
                    $produto->setTab("EST_PRODUTO");
                    $ncmProduto = $produto->getField("NCM", "CODIGO=".$arrPecas[$i]['CODPRODUTO']);
                    $produto->close_connection();
                    if($ncmProduto == ""){
                        $this->m_msg = "Preencha campo NCM no cadastro de produto! produto:".$arrPecas[$i]['DESCRICAO'];
                        throw new Exception( $this->m_msg );
                    }
                    $arrPecas[$i]['NCM'] = $ncmProduto;
                endif;
                
                $arrPecas[$i]['TOTAL'] = $arrPecas[$i]['QUANTIDADEUTILIZADA'] * $arrPecas[$i]['VALORUNITARIO']; //QTSOLICITADO - ALTERADO 18/07/2019 
                $objNfProduto->setIdNf($idGerado);
                $objNfProduto->setCodProduto($arrPecas[$i]['CODPRODUTO']);
                $objNfProduto->setDescricao($arrPecas[$i]['DESCRICAO']);
                $objNfProduto->setUnidade($arrPecas[$i]['UNIDADE']);
                $objNfProduto->setQuant($arrPecas[$i]['QUANTIDADE'], true); 
                $objNfProduto->setUnitario($arrPecas[$i]['VALORUNITARIO'], true);
                $objNfProduto->setDesconto($arrPecas[$i]['DESCONTO'], true); 
                $objNfProduto->setTotal($arrPecas[$i]['TOTAL'], true);                
                                                   
                $objNfProduto->setOrigem($arrPecas[$i]['ORIGEM']);
                $objNfProduto->setTribIcms($arrPecas[$i]['TRIBICMS']);

                $objNfProduto->setNcm($arrPecas[$i]['NCM']);
                $objNfProduto->setCest($arrPecas[$i]['CEST']);
                $objNfProduto->setFrete($arrPecas[$i]['FRETE'],true);

                $objNfProduto->setCustoProduto($arrPecas[$i]['CUSTOPRODUTO']);
        
                $objNfProduto->setNrSerie(''); 
                $objNfProduto->setDataGarantia('');
                $objNfProduto->setLote($arrPecas[$i]['FABLOTE']);
                $objNfProduto->setDataValidade($arrPecas[$i]['FABDATAVALIDADE']);
                $objNfProduto->setDataFabricacao($arrPecas[$i]['FABDATAFABRICACAO']);
        
                $objNfProduto->setOrdem($arrPecas[$i]['ORDEM']);
                $objNfProduto->setProjeto($arrPecas[$i]['PROJETO']);
                $objNfProduto->setDataConferencia($arrPecas[$i]['DATACONFERENCIA']);
                
                $objNfProduto->setBcFcpUfDest('0');
                $objNfProduto->setAliqFcpUfDest('0');
                $objNfProduto->setValorFcpUfDest('0');
                $objNfProduto->setBcIcmsUfDest('0');
                $objNfProduto->setAliqIcmsUfDest('0');
                $objNfProduto->setAliqIcmsInter('0');
                $objNfProduto->setAliqIcmsInterPart('0');
                $objNfProduto->setValorIcmsUfDest('0');
                $objNfProduto->setValorIcmsUFRemet('0');
                $objNfProduto->setCodigoNota($arrPecas[$i]['CODIGOPRODUTONOTA']);
                $objNfProduto->setDespAcessorias($arrPecas[$i]['DESPACESSORIAS'], true);
                
        
                $result = $this->calculaImpostosNfe($objNfProduto, 
                            $arrNf[0]['IDNATOP'], 
                            $objNotaFiscal->getUfPessoa(), 
                            $objNotaFiscal->getTipoPessoa($this->m_empresacentrocusto), 
                            $this->m_empresacentrocusto); 
        
                if (!$result):
                    $this->m_msg = "Tributos não localizado ".$objNfProduto->getDescricao()." Nat. Operação:".$objNotaFiscal->getIdNatop().
                        "<br> UF:".$objNotaFiscal->getUfPessoa()." Tipo:".$objNotaFiscal->getTipoPessoa().
                        " CST:".$objNfProduto->getOrigem().$objNfProduto->getTribIcms().
                        "<br> NCM:".$objNfProduto->getNcm()." CEST:".$objNfProduto->getCest()."<br>";
                    throw new Exception( $this->m_msg );
                endif;

                $result = $objNfProduto->incluiNotaFiscalProduto();

                
            } // FIM FOR
        }catch(Error $e){
            throw new Exception($e->getMessage()." <br> Falha ao cadastrar produtos na Nota Fiscal");
        }
       
       }
                                    
    public function gera_xml_os($idGerado, $conn=null){
    
        try{
            $exporta = new p_nfe_40();
            $result = $exporta->Gera_XML($idGerado, $this->m_empresacentrocusto, '', $conn);
            $cStatus = $result['cStatus'];
            $cStatus = '100';
            return $cStatus; 
        }catch(Exception $e){
            $this->m_msg = "Falha ao Gerar NF ".$e->getMessage();
            throw new Exception( $this->m_msg );
        }
    
    }                           
                                    
    public function valida_nf_auto($idGerado){
        try{
            $objNotaFiscal = new c_nota_fiscal();
            $objNotaFiscal->setId($idGerado);
            $arrNf = $objNotaFiscal->select_nota_fiscal();

            $numNf = $objNotaFiscal->geraNumNf($arrNf[0]['MODELO'], $arrNf[0]['SERIE'], $this->m_empresacentrocusto);
            $objNotaFiscal->setId($idGerado);
            $objNotaFiscal->setNumero($numNf);
            $objNotaFiscal->alteraNfNumero();
        }catch(Error $e){
            throw new Exception( "Idendificador NF >>> ".$idGerado." - Número não Gerado");
        }
    } 
    
    public function adiciona_info_nf_obs_lancamento($idNf){
        $objNf = new c_nota_fiscal();
        $objNf->setId($idNf);
        $arrNf = $objNf->select_nota_fiscal();
        $msg = "Num NF : ".$idNf." <br> Link de acesso: ". strtolower($arrNf[0]['PATHDANFE']);
        
        $sql = "UPDATE FIN_LANCAMENTO SET OBS = '".$msg."' ";
        $sql .= "WHERE DOCTO = '".$arrNf[0]['DOC']."' AND ORIGEM = 'OS'";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        
    } 
    
    
    /**
 * <b> É responsavel por buscar informações da tabela tritutos para calculo dos impostos da NFe </b>
 * <b> os campos NCM e CEST são opcionais </b>
 * @name calculaImpostosNfe
 * @param int natOp
 * @param VARCHAR uf
 * @param CHAR tipoPessoa
 * @param VARCHAR origem 
 * @param VARCHAR tribIcms
 * @param VARCHAR ncm
 * @param VARCHAR cest
 * @return vazio - os campos são calculados e setados dentro da função, não havendo retorno.
 */
public function calculaImpostosNfe($objNfProd = NULL, $natOp = NULL, $uf=NULL, $tipoPessoa=NULL, $centroCusto=NULL){

    $arrTributos =  [];
    $origem=$objNfProd->getOrigem();
    $tribIcms=$objNfProd->getTribIcms();
    $ncm=$objNfProd->getNcm();
    $cest=$objNfProd->getCest();

    // BUSCA CRT EMPRESA
    $banco = new c_banco();
    $sql = "select * from amb_empresa where (centrocusto=".$centroCusto.")";
    //$sql = "select * from amb_empresa where (centrocusto=".$this->m_empresacentrocusto.")";
    $emp = $banco->exec_sql($sql);
    $crt=$emp[0]['REGIMETRIBUTARIO'];


    $sql  = "SELECT * FROM EST_NAT_OP_TRIBUTO ";
    $sql .= "WHERE (CENTROCUSTO =".$centroCusto.") AND (IDNATOP =".$natOp.") AND (UF='".$uf."') AND (PESSOA='".$tipoPessoa."') ";
    // $sql .= "WHERE (CENTROCUSTO =".$this->m_empresacentrocusto.") AND (IDNATOP =".$natOp.") AND (UF='".$uf."') AND (PESSOA='".$tipoPessoa."') ";
    if (($natOp=='2') and ($crt!='3')){
        if ($origem!='') {
            $sql .= " AND (ORIGEM='".$origem."') ";        
        }
        if ($tribIcms!='') {
            $sql .= " AND (TRIBICMS='".$tribIcms."') ";        
        }
        if ($ncm!='') {
            $sql .= " AND ((NCM='".$ncm."') OR (NCM='')) ";        
        }
        if ($cest!='') {
            $sql .= " AND ((CEST='".$cest."') OR (CEST='')) ";
        }
    } else if ($crt=='3') {
        $sql .= " AND (ORIGEM='".$origem."') AND (TRIBICMS='".$tribIcms."') AND ((NCM='".$ncm."') OR (NCM='')) AND ((CEST='".$cest."') OR (CEST=''))";
    } else {    
        $sql .= " AND (ORIGEM='".$origem."')";
    }
    $sql .= ' ORDER BY NCM DESC ';
    
   // echo strtoupper($sql);
    $banco->exec_sql($sql);
    $banco->close_connection();
    $arrTributos =  $banco->resultado;
    if (isset($arrTributos)):

        // seta aliquotas
        $objNfProd->setCfop($arrTributos[0]['CFOP']);
        $objNfProd->setAliqIcms($arrTributos[0]['ALIQICMS'], true);
        $objNfProd->setPercReducaoBc($arrTributos[0]['PERCREDUCAOBC'], true);
        $objNfProd->setModBc($arrTributos[0]['MODBC']);
        $objNfProd->setPercDiferido($arrTributos[0]['PERCDIFERIDO'], true);
        $objNfProd->setModBcSt($arrTributos[0]['MODBCST']);
        $objNfProd->setPercMvaSt($arrTributos[0]['MVAST'], true);
        $objNfProd->setPercReducaoBcSt($arrTributos[0]['PERCREDUCAOBCST'], true);
        $objNfProd->setAliqIcmsSt($arrTributos[0]['ALIQICMSST'], true);
        $objNfProd->setAliqIpi($arrTributos[0]['ALIQIPI'], true);
        $objNfProd->setInsideIpiBc($arrTributos[0]['INSIDEIPIBC']);
        $objNfProd->setCstPis($arrTributos[0]['CSTPIS']);
        $objNfProd->setAliqPis($arrTributos[0]['ALIQPIS'], true);
        $objNfProd->setCstCofins($arrTributos[0]['CSTCOFINS']);
        $objNfProd->setAliqCofins($arrTributos[0]['ALIQCOFINS'], true);
        $objNfProd->setTribIcms($arrTributos[0]['TRIBICMSSAIDA']);
        $objNfProd->setCBenef($arrTributos[0]['CBENEF']);


        // inicializa campos , SEM PARAMETRO DE FORMATAÇÃO PARA TRAZER DEFAULT
        $totalProduto = $objNfProd->getTotal('B');
        $descontoProduto = $objNfProd->getDesconto('B');
        $aliqIcms = $objNfProd->getAliqIcms('B');
        $percReducaoBc = $objNfProd->getPercReducaoBc('B');
        $percDiferido = $objNfProd->getPercDiferido('B');
        $mvaSt = $objNfProd->getPercMvaSt('B');
        $percReduacaoBcSt = $objNfProd->getPercReducaoBcSt('B');
        $aliqIcmsSt = $objNfProd->getAliqIcmsSt('B');
        $aliqIpi = $objNfProd->getAliqIpi('B');
        $insideIpiBc = $objNfProd->getInsideIpiBc('B');
        $vlIcms = 0;
        $vlIpi = 0;
        $vlIcmsDiferido = 0;
        $vlIcmsOperacao = 0;
        $vlBcSt = 0;
        $vlIcmsSt = 0;
        $vlBcStRet = 0;
        $vlIcmsStRet = 0;
        $cstPis = $objNfProd->getCstPis();
        $aliqPis = $objNfProd->getAliqPis('B');
        $vlPis = 0;
        $cstCofins = $objNfProd->getCstCofins();
        $aliqCofins = $objNfProd->getAliqCofins('B');
        $vlCofins = 0;

        
        if (($crt!='3') and ($natOp!='2')):
            $bcIcms = 0;
            $bcPis = 0;
            $bcCofins = 0;
        else:    
            $bcIcms = $totalProduto - $descontoProduto;
            $bcPis = $totalProduto - $descontoProduto;
            $bcCofins = $totalProduto - $descontoProduto;
        endif;
        
        // calcula IPI
        //o montante do IPI:

        // 1 - não integra a BC do ICMS quando o produto for destinado a posterior comercialização, industrialização ou outra saída tributada;
        // 2 - integra a BC do ICMS quando o produto for destinado a consumidor final, ativo imobilizado 
        $objNfProd->setValorIpi(0);
        if ($aliqIpi>0):
            $vlIpi = ($aliqIpi/100)*$totalProduto;
            if ($insideIpiBc=="S"):
                $bcIcms += $vlIpi;
            endif;
            $objNfProd->setValorIpi($vlIpi);
        endif;

        switch ($arrTributos[0]['TRIBICMSSAIDA']){
            case '00': // tributado integralmente
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                // observacao
                break;
            case '10': // Tributada e com cobrança do ICMS por substituição tributária
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="N"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReduacaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*($vlBcSt)) - $vlIcms; //calcula icms st

                break;
            case '20': // Tributação com redução de base de cálculo
                $bcIcms -= ($bcIcms*($percReduacaoBc/100)); // aplica reducao
                $vlIcms = ($aliqIcms/100)*$bcIcms;

                break;
            case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="N"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReduacaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*($vlBcSt)) - $vlIcms; //calcula icms st
                $bcIcms = 0;
                $vlIcms = 0;
                break;
            case '40': // Tributação Isenta, Não tributada ou Suspensão.
            case '41': // Tributação Isenta, Não tributada ou Suspensão.
            case '50': // Tributação Isenta, Não tributada ou Suspensão.
                $bcIcms = 0;
                $vlIcms = 0;
                $vlBcSt = 0;
                $vlIcmsSt = 0;
                $vlBcStRet = 0;
                $vlIcmsStRet = 0;
                break;
            case '51': // Tributação com Diferimento (a exigência do preenchimento das
                       //informações do ICMS diferido fica a critério de cada UF).
                /*
                $bcIcms = ($percReducaoBc/100)*$totalProduto;
                $vlIcmsDiferido = ($percDiferido/100)*$bcIcms;
                $vlIcmsOperacao = ($aliqIcms/100)*$bcIcms;
                $vlIcms = $vlIcmsOperacao-$vlIcmsDiferido;
                */
                $bcIcms = $totalProduto;
                $vlIcmsOperacao = ($totalProduto *  $aliqIcms ) / 100 ;
                $vlIcmsDiferido = ($vlIcmsOperacao * ($percDiferido /  100 ) );
                $vlIcms = $vlIcmsOperacao - $vlIcmsDiferido; 
                
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
                if ($insideIpiBc=="N"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReduacaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*$vlBcSt) - $vlIcms; //calcula icms st


                break;
            case '90': // Tributação ICMS: Outros
                $bcIcms -= ($bcIcms*($percReduacaoBc/100)); // aplica reducao Bc
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="N"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReduacaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*$vlBcSt) - $vlIcms; //calcula icms st
                break;
            case '102': // Tributação com redução de base de cálculo
                $bcIcms -= ($bcIcms*($percReducaoBc/100)); // aplica reducao
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                break; 
            case '201': // Tributada e com cobrança do ICMS por substituição tributária
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="N"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReduacaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*($vlBcSt)) - $vlIcms; //calcula icms st

                break;
            case '202': // Tributada e com cobrança do ICMS por substituição tributária
                $vlIcms = ($aliqIcms/100)*$bcIcms;
                $vlBcSt = $bcIcms;
                if ($insideIpiBc=="N"): // soma vl ipi na base de calculo de ST
                    $vlBcSt += $vlIpi;
                endif;
                $vlBcSt = ($vlBcSt * $mvaSt); // aplica indice mva bc st
                $vlBcSt -= ($vlBcSt*($percReduacaoBcSt/100)); // aplica redução bc st
                $vlIcmsSt = (($aliqIcmsSt/100)*($vlBcSt)) - $vlIcms; //calcula icms st
                $pCredSN = 0;
                $vCredICMSSN = 0;
                break;   
        }

        // calululo PIS
        switch ($cstPis){
            case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
            case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                $vlPis = ($bcPis * $aliqPis) / 100;
                break;
            case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                $bcPis = $objNfProd->getQuant();
                $vAliqProd = $aliqPis;
                $vlPis = ($bcPis * $vAliqProd);
                break;
            case '04': 
            case '05': 
            case '06': 
            case '07': 
            case '08': 
            case '09': 
                $bcPis = 0;
                $aliqPis = 0;
                $vlPis = 0;
                break;
            default :
                $vlPis = ($bcPis * $aliqPis) / 100;
        }
        
        // calululo COFINS
        switch ($cstCofins){
            case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
            case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                $vlCofins = ($bcCofins * $aliqCofins) / 100;
                break;
            case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                $bcCofins = $objNfProd->getQuant();
                $vAliqProd = $aliqCofins;
                $vlCofins = ($bcCofins * $vAliqProd) / 100;
                break;
            case '04': 
            case '05': 
            case '06': 
            case '07': 
            case '08': 
            case '09': 
                $bcCofins = 0;
                $aliqCofins = 0;
                $vlCofins = 0;
                break;
            default :
                $vlCofins = ($bcCofins * $aliqCofins) / 100;
        }
        
        $objNfProd->setBcIcms($bcIcms, true);
        $objNfProd->setValorIcms($vlIcms, true);

        $objNfProd->setValorIcmsDiferido($vlIcmsDiferido,true);
        $objNfProd->setValorIcmsOperacao($vlIcmsOperacao,true);

        $objNfProd->setValorBcSt($vlBcSt, true);
        $objNfProd->setValorIcmsSt($vlIcmsSt, true);

        $objNfProd->setBcPis($bcPis, true);
        $objNfProd->setValorPis($vlPis, true);
        $objNfProd->setBcCofins($bcCofins, true);
        $objNfProd->setValorCofins($vlCofins, true);
        
        return true;
    else:
        return false;
    endif;

} //fim calculaImpostos



}	//	END OF THE CLASS
?>
