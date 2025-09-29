<?php
$dir = (__DIR__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../crm/c_conta.php");
include_once($dir . "/../est/c_nota_fiscal.php");
include_once($dir . "/../est/c_nota_fiscal_produto.php");


/**
 * Description of c_exporta_xml
 *
 * @author lucas
 */
class c_exporta_xml {
    
//    public function __construct($idNf,$filial, $tipoNf) {
//        $this->Gera_XML($idNf, $filial,$tipoNf);
//    }
    public function __construct() {
    }
    
    /**
     * Funcao de consulta ao BD para pegar dados da empresa de acordo
     * com o centro de custo logado.
     * @param INT $centrocusto Filial que esta logado
     * @return ARRAY todos os campos da table amb_empresa
     */
    public function select_empresa_centro_custo($centrocusto) {
        $sql = "SELECT * ";
        $sql .= "FROM amb_empresa ";
        $sql .= "WHERE (centrocusto = '" . $centrocusto . "') ";
        // echo strtoupper($sql);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
    
    /**
     * Funcao para formatar a data que vai estar na NFe
     * @param TIMESTAMP $data
     * @return data no formato para NFe - 2016-03-03T09:16:00-03:00, PHP.INI = date.timezone = 'UTC''
     */
    public function MostraData($data) {
        $aux = explode(" ", $data);
        return $aux[0]."T".$aux[1]."-03:00";
    }
    
    /**
     * <b> Funcao para remover os acentos da importacao. </b>
     * @name removeAcentos
     * @param STRING $string
     * @param BOOLEAN $slug FALSE
     * @return STRING
     */
    function removeAcentos($string, $slug = false) {
        $conversao = array('á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e',
            'ê' => 'e', 'í' => 'i', 'ï' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', "ö" => "o",
            'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'ñ' => 'n', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A',
            'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ï' => 'I', "Ö" => "O", 'Ó' => 'O',
            'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C', 'Ñ' => 'N');
        return strtr($string, $conversao);
    }
    
    /**
     * Funcao para contruir a nota fiscal XML e gerar o arquivo no diretorio raiz
     * @param INT $idNf Chave primaria na table nota_fiscal
     * @param INT $Filial filial logado pelo sistema
     * @param INT $tipoNf tipo da NF 0 - Entrada / 1 - Saida
     */
    public function Gera_XML($idNf, $Filial, $tipoNf) {
       

        // variavies totais
        $vBCTotal = 0;
        $vICMSTotal = 0;
        $vICMSDesonTotal = 0;
        $vFCPUFDestTotal = 0;
        $vICMSUFDestTotal=0;
        $vICMSUFRemetTotal=0;
        $vBCSTTotal=0;
        $vSTTotal=0;
        $vProdTotal = 0;
        $vFreteTotal=0;
        $vSegTotal=0;
        $vDescTotal=0;
        $vIITotal=0;
        $vIPITotal=0;
        $vPISTotal=0;
        $vCOFINSTotal=0;
        $vOutroTotal=0;
        $vNFTotal=0;
        $vTotTribTotal=0;
                        
        // CONSULTA DE DADOS DA NOTA FISCAL
        $NfOBJ = new c_nota_fiscal();
        $NfOBJ->setId($idNf);
        $NfArray = $NfOBJ->select_nota_fiscal();
        
        //DADOS DA EMPRESA/EMITENTE
        $FilialArray = $this->select_empresa_centro_custo($Filial);
        
        // DADOS DO DESTINATARIO
        $PessoaDestOBJ = new c_conta();
        $PessoaDestOBJ->setId($NfArray[0]['PESSOA']);
        $PessoaDestArray = $PessoaDestOBJ->select_conta();

        // DADOS DO TRANSPORTADOR
        $transpOBJ = new c_conta();
        $transpOBJ->setId($NfArray[0]['TRANSPORTADOR']);
        $transpArray = $PessoaDestOBJ->select_conta();

        // DADOS NF PRODUTO
        $NfProdutoOBJ = new c_nota_fiscal_produto();
        $NfProdutoOBJ->setIdNf($idNf);
        $ProdutoArray = $NfProdutoOBJ->select_nota_fiscal_produto_nf();
        
        // incluir codigo e desc pais na tabela cidade.
        // codigo do municipio emitente
        $cMunEmit = $FilialArray[0]['CODMUNICIPIO']; // pg 175 -incluir código do municipio na tabela amb_empresa, buscas os 2 primeiros digitos do codigo
        // codigo do municipio destinatario
        $cMunDest = $PessoaDestArray[0]['CODMUNICIPIO']; // pg 181 -incluir código do municipio na tabela fin_cliente 
        // pag 180 = CRT = Codigo de Regime Tributario 1=Simples Nacional;2=Simples Nacional, excesso sublimite de receita bruta;3=Regime Normal. (v2.0). 
        // incluir na amb_empresa
        $crt = $FilialArray[0]['REGIMETRIBUTARIO'];  // ok código regime tributário 1=Simples Nacional; 2=Simples Nacional, excesso sublimite de receita bruta; 3=Regime Normal. (v2.0).
       
       
        //##################################################################
        //################### Gerando o ID da Inf da NFe ###################
        //##################################################################
        /**
        * cUF - Código da UF do emitente do Documento Fiscal (2)
        * AAMM - Ano e Mês de emissão da NF-e (4)
        * CNPJ - CNPJ do emitente (14)
        * mod - Modelo do Documento Fiscal (2)
        * serie - Série do Documento Fiscal (3)
        * nNF - Número do Documento Fiscal (9)
        * cNF - Código Numérico que compõe a Chave de Acesso (9)
        * cDV - Dígito Verificador da Chave de Acesso (1)
        */
        $cUf = substr($cMunEmit, 0, 2); // pg 175 - buscas os 2 primeiros digitos do codigo do municipio
        $aux = explode("-", $NfArray[0]['EMISSAO']);
        $aamm = substr($aux[0], 2, 2).$aux[1];
        $cnpj = $FilialArray[0]['CNPJ'];
        $modelo = $NfArray[0]['MODELO'];
        $serie = str_pad($NfArray[0]['SERIE'], 3, "0",STR_PAD_LEFT);
        $nNF = str_pad($NfArray[0]['NUMERO'], 9, "0",STR_PAD_LEFT);
        $tpEmis = 1;
        $cNF = str_pad($NfArray[0]['ID'], 8, "0",STR_PAD_LEFT);
        $cDV = '2'; // calcular digito verificar
                    // Informar o DV da Chave de Acesso da NF-e, o DV será
                    //calculado com a aplicação do algoritmo módulo 11 (base 2,9) da
                    //Chave de Acesso. (vide item 5 do Manual de Orientação)
        
        $titulo = $cUf.$aamm.$cnpj.$modelo.$serie.$nNF.$tpEmis.$cNF;
        $cDV =  c_tools::calculaDigitoMod11($titulo,1,9,false);
        $titulo = "NFe".$cUf.$aamm.$cnpj.$modelo.$serie.$nNF.$tpEmis.$cNF.$cDV;
        echo $titulo;
        // idDest = identificato do local de destino
        $ufEmpresa = $FilialArray[0]['UF'];
        $ufPessoa = $PessoaDestArray[0]['UF'];
        if ($ufEmpresa == $ufPessoa):
            $idDest = 1;
        else:    
            $idDest = 2;
        endif;
        
        // indPres = OK pag 177 = verificar calculo de tipo venda   fin_fat_pedido
        // Indicador de presença do comprador no estabelecimento comercial no momento da operação
        // 0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
        // 1=Operação presencial;
        // 2=Operação não presencial, pela Internet;
        // 3=Operação não presencial, Teleatendimento;
        // 4=NFC-e em operação com entrega a domicílio;
        // 9=Operação não presencial, outros.
        // 
        // 
        // pag 177 = indFinal = indicacao de venda consumidor final
        // se pessoa fisica ou IE não for preenchida, sistema considera venda consumidor final
        // PAG 181 = indIEDest = Indicador da IE do Destinatário
        /*  1=Contribuinte ICMS (informar a IE do destinatário);
            2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS;
            9=Não Contribuinte, que pode ou não possuir Inscrição
            Estadual no Cadastro de Contribuintes do ICMS.
            Nota 1: No caso de NFC-e informar indIEDest=9 e não informar
            a tag IE do destinatário;
            Nota 2: No caso de operação com o Exterior informar
            indIEDest=9 e não informar a tag IE do destinatário;
            Nota 3: No caso de Contribuinte Isento de Inscrição
            (indIEDest=2), não informar a tag IE do destinatário. */
        $tipoPessoa = $PessoaDestArray[0]['PESSOA'];
        $ie = $PessoaDestArray[0]['INSCESTRG'];
        if (($tipoPessoa == "J") AND (strlen($ie)>0)):
            $indFinal = 0; // normal
            $indIEDest = 1;
        else:
            $indFinal = 1; // consumidor final
            if ($tipoPessoa == "F"):
                $indIEDest = 9;
            else:    
                $indIEDest = 2;
            endif;
        endif;
        // pag 182 = OK - suframa = codigo SUFRAMA, incluir fin_cliente
        // pag 182 = OK - email = email do destinatario para receber nf, incluir fin_cliente
        if (strlen($PessoaDestArray[0]['EMAILNFE'])>0):
            $email = $PessoaDestArray[0]['EMAILNFE'];
        else:    
            $email = $PessoaDestArray[0]['EMAIL'];
        endif;
        // pag 191 = OK - vTotTrib = Valor aproximado total de tributos federais, estaduais e municipais., incluir est_nota_fiscal_produto
        // pag 192 = modBC = Modalidade de determinação da BC do ICMS, verificar como controlar
        // pag 193 = ...ST = incluir campos de ST est_nota_fiscal_produto
        // PAG 194 = ok - pRedBC = Percentual da Redução de BC = INCLUIR est_nota_fiscal_produto
        // PAG 194/195 = vICMSDeson = Valor do ICMS desonerado = ANALISAR
        // PAG 198 = OK  pRedBC = Percentual da Redução de BC = INCLUIR est_nota_fiscal_produto
        //         = OK vICMSOp = Valor do ICMS da Operação
        //         = OK pDif = Percentual do diferimento
        //         = OK vICMSDif = Valor do ICMS diferido
        // PAG 199 = OK -vBCSTRet Valor da BC do ICMS ST retido = INCLUIR est_nota_fiscal_produto
        //         = OK vICMSSTRet Valor do ICMS ST retido
        // pag 204 = OK Grupo CRT=1 = calculo simples nacional
        // pag 210 = IPI = O. Imposto sobre Produtos Industrializados
        // pag 212 = PIS
        // pag 215 = COFINS
        
        /*
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><nfeProc versao=\"3.10\" xmlns=\"http://www.portalfiscal.inf.br/nfe\">";//cabeçalho do arquivo */

//não utilizado tecnospeed
//        $xml = "<nfeProc xmlns=\"http://www.portalfiscal.inf.br/nfe\" versao=\"3.10\" >";//cabeçalho do arquivo
//        $xml .= "<NFe xmlns=\"http://www.portalfiscal.inf.br/nfe\">";
//não utilizado tecnospeed
        
        
            $xml.= "<infNFe Id=\"{$titulo}\" versao=\"3.10\">";// titulo = nome do arquivo
                $xml.="<ide>"; // ##### IDENTIFICACAO DA NF
                    $xml.="<cUF>{$cUf}</cUF>"; 
                    $xml.="<cNF>{$cNF}</cNF>"; // id nota fiscal CONTEUDO ANTERIOR = 07909900
                    $xml.="<natOp>{$this->removeAcentos($NfArray[0]['NATOPERACAO'])}</natOp>";
                    $xml.="<indPag>{$NfArray[0]['FORMAPGTO']}</indPag>";
                    $xml.="<mod>{$NfArray[0]['MODELO']}</mod>";
                    $xml.="<serie>{$NfArray[0]['SERIE']}</serie>";
                    $xml.="<nNF>{$NfArray[0]['NUMERO']}</nNF>";
                    $xml.="<dhEmi>".$this->MostraData($NfArray[0]['EMISSAO'])."</dhEmi>";
                    $xml.="<tpNF>{$NfArray[0]['TIPO']}</tpNF>"; // 0=Entrada; 1=Saída; 
                    $xml.="<idDest>{$idDest}</idDest>";
                    $xml.="<cMunFG>{$cMunEmit}</cMunFG>"; // pag 176
                    $xml.="<tpImp>1</tpImp>"; // 1=DANFE normal, Retrato;
                    $xml.="<tpEmis>1</tpEmis>"; // pag 176 contingência
                    $xml.="<cDV>{$cDV}</cDV>";
                    $xml.="<tpAmb>2</tpAmb>"; // OK incluir em parametros tipo de ambiente 1 = producao / 2 = homologacao
                    $xml.="<finNFe>{$NfArray[0]['FINALIDADEEMISSAO']}</finNFe>";
                    $xml.="<indFinal>{$indFinal}</indFinal>";
                    $xml.="<indPres>1</indPres>"; // OK pag 177 = verificar calculo de tipo venda
                    $xml.="<procEmi>3</procEmi>"; // pag 177 = incluir em parametros processamento  
                    $xml.="<verProc>3.10.79</verProc>";
                $xml.="</ide>";
                $xml.="<emit>"; 
                    $xml.="<CNPJ>{$FilialArray[0]['CNPJ']}</CNPJ>";
                    $xml.="<xNome>{$this->removeAcentos($FilialArray[0]['NOMEEMPRESA'])}</xNome>";
                    $xml.="<xFant>{$this->removeAcentos($FilialArray[0]['NOMEFANTASIA'])}</xFant>";
                    $xml.="<enderEmit>";
                        $xLgr = $this->removeAcentos($FilialArray[0]['TIPOEND']." ".$FilialArray[0]['TITULOEND']." ".$FilialArray[0]['ENDERECO']);
                        $xml.="<xLgr>{$xLgr}</xLgr>";
                        $xml.="<nro>{$FilialArray[0]['NUMERO']}</nro>";
                        if (!$FilialArray[0]['COMPLEMENTO']==''):
                            $xml.="<xCpl>{$this->removeAcentos($FilialArray[0]['COMPLEMENTO'])}</xCpl>";
                        endif;    
                        $xml.="<xBairro>{$this->removeAcentos($FilialArray[0]['BAIRRO'])}</xBairro>";
                        $xml.="<cMun>{$cMunEmit}</cMun>";
                        $xml.="<xMun>{$this->removeAcentos($FilialArray[0]['CIDADE'])}</xMun>";
                        $xml.="<UF>{$FilialArray[0]['UF']}</UF>";
                        $xml.="<CEP>{$FilialArray[0]['CEP']}</CEP>";
                        $xml.="<cPais>1058</cPais>";
                        $xml.="<xPais>BRASIL</xPais>";
                        $fone= $FilialArray[0]['FONEAREA'].$FilialArray[0]['FONENUM'];
                        $xml.="<fone>{$fone}</fone>";
                    $xml.="</enderEmit>";
                    $xml.="<IE>{$FilialArray[0]['INSCESTADUAL']}</IE>"; // pag 180 = incluir IE do substituto tributatio de UF de destino
                    $xml.="<CRT>{$crt}</CRT>"; // codigo regima tributário
                $xml.="</emit>";
                $xml.="<dest>";
                    // incluir opção estrangeiro
                    if ($tipoPessoa == "J"):
                        $xml.="<CNPJ>".$PessoaDestArray[0]['CNPJCPF']."</CNPJ>";
                    else:    
                        $xml.="<CPF>".$PessoaDestArray[0]['CNPJCPF']."</CPF>";
                    endif;
// teste homologação                    $xml.="<xNome>".$this->removeAcentos($PessoaDestArray[0]['NOME'])."</xNome>"; // opcional modelo NFC-e
                    $xml.="<xNome>NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL</xNome>"; // opcional modelo NFC-e
                    $xml.="<enderDest>"; // Grupo obrigatório para a NF-e (modelo 55)
                        $xml.="<xLgr>{$this->removeAcentos($PessoaDestArray[0]['ENDERECO'])}</xLgr>";
                        $xml.="<nro>".$PessoaDestArray[0]['NUMERO']."</nro>";
                        if (!$PessoaDestArray[0]['COMPLEMENTO']==''):
                            $xml.="<xCpl>".$this->removeAcentos($PessoaDestArray[0]['COMPLEMENTO'])."</xCpl>";
                        endif;
                        $xml.="<xBairro>".$this->removeAcentos($PessoaDestArray[0]['BAIRRO'])."</xBairro>";
                        $xml.="<cMun>{$cMunDest}</cMun>";
                        $xml.="<xMun>".$this->removeAcentos($PessoaDestArray[0]['CIDADE'])."</xMun>";
                        $xml.="<UF>".$PessoaDestArray[0]['UF']."</UF>";
                        $xml.="<CEP>".$PessoaDestArray[0]['CEP']."</CEP>";
                        $xml.="<cPais>1058</cPais>";
                        $xml.="<xPais>BRASIL</xPais>";
                        $fone=$PessoaDestArray[0]['FONEAREA'].$PessoaDestArray[0]['FONENUM'];
                        if (!$fone==''):
                            $xml.="<fone>{$fone}</fone>";
                        endif;    
                    $xml.="</enderDest>";
                    $xml.="<indIEDest>{$indIEDest}</indIEDest>";
                    if ($indIEDest == 1):
                        $xml.="<IE>{$PessoaDestArray[0]['INSCESTRG']}</IE>";
                    endif;
                    if (strlen($PessoaDestArray[0]['SUFRAMA'])>0):
                        $xml.="<ISUF>{$PessoaDestArray[0]['SUFRUMA']}</ISUF>";
                    endif;
                    $xml.="<email>{$email}</email>";
                $xml.="</dest>";
                
                for ($i = 0; $i<count($ProdutoArray); $i++){
                    $vBCTotal += $ProdutoArray[$i]['BCICMS'];
                    $vICMSTotal += $ProdutoArray[$i]['VALORICMS'];
                    $vICMSDesonTotal = 0;
                    $vFCPUFDestTotal = 0;
                    $vICMSUFDestTotal=0;
                    $vICMSUFRemetTotal=0;
                    $vBCSTTotal += $ProdutoArray[$i]['VALORBCST'];
                    $vSTTotal += $ProdutoArray[$i]['VALORICMSST'];
                    $vProdTotal += $ProdutoArray[$i]['TOTAL'];
                    $vFreteTotal=0;
                    $vSegTotal=0;
                    $vDescTotal=0;
                    $vIITotal=0;
                    $vIPITotal=0;
                    $vPISTotal=0;
                    $vCOFINSTotal=0;
                    $vOutroTotal=0;
                    $vNFTotal=$NfArray[0]['TOTALNF'];
                    $vTotTribTotal=0;
                    $l = $i+1;
                    $xml.="<det nItem=\"{$l}\">";
                        $xml.="<prod>";
                            $xml.="<cProd>{$ProdutoArray[$i]['CODPRODUTO']}</cProd>";
                            if (strlen($ProdutoArray[0]['CODIGOBARRAS'])>0):
                                $xml.="<cEAN>".$ProdutoArray[0]['CODIGOBARRAS']."</cEAN>";
                            else:
                                $xml.="<cEAN></cEAN>";
                            endif;
                            $xml.="<xProd>{$this->removeAcentos($ProdutoArray[$i]['DESCRICAO'])}</xProd>";
                            if (!$ProdutoArray[$i]['NCM']==''):
                                $xml.="<NCM>".$ProdutoArray[$i]['NCM']."</NCM>";
                            else:
                                $xml.="<NCM>00</NCM>";
                            endif;
                            if (!$ProdutoArray[$i]['CEST']==''):
                                $xml.="<CEST>".$ProdutoArray[$i]['CEST']."</CEST>";
                            endif;
                            $xml.="<CFOP>".$ProdutoArray[$i]['CFOP']."</CFOP>";
                            $xml.="<uCom>".$ProdutoArray[$i]['UNIDADE']."</uCom>";
                            $xml.="<qCom>".$ProdutoArray[$i]['QUANT']."</qCom>";
                            $xml.="<vUnCom>".$ProdutoArray[$i]['UNITARIO']."</vUnCom>";
                            $xml.="<vProd>".$ProdutoArray[$i]['TOTAL']."</vProd>";
                            if (strlen($ProdutoArray[0]['CODIGOBARRAS'])>0):
                                $xml.="<cEANTrib>".$ProdutoArray[0]['CODIGOBARRAS']."</cEANTrib>";
                            else:
                                $xml.="<cEANTrib></cEANTrib>";
                            endif;
                            $xml.="<uTrib>{$ProdutoArray[$i]['UNIDADE']}</uTrib>";
                            $xml.="<qTrib>".$ProdutoArray[$i]['QUANT']."</qTrib>";
                            $xml.="<vUnTrib>".$ProdutoArray[$i]['UNITARIO']."</vUnTrib>";
                            $xml.="<indTot>1</indTot>"; // pag 185 = Indica se valor do Item (vProd) entra no valor total da NF-e (vProd)
                             $xml.="<med>"; // PAG 190
                                 $xml.="<nLote>{$ProdutoArray[$i]['LOTE']}</nLote>";
                                 $xml.="<qLote>".intval($ProdutoArray[$i]['QUANT'])."</qLote>";
                                 if (isset($ProdutoArray[$i]['DATAFABRACAO'])):
                                     $xml.="<dFab>{$ProdutoArray[$i]['DATAFABRICACAO']}</dFab>";
                                 else:    
                                     $xml.="<dFab>{$ProdutoArray[$i]['DATAVALIDADE']}</dFab>";
                                 endif;
                                 $xml.="<dVal>{$ProdutoArray[$i]['DATAVALIDADE']}</dVal>";
                                 $xml.="<vPMC>0.00</vPMC>";
/*                             
                                 $xml.="<nLote>{$ProdutoArray[$i]['LOTE']}</nLote>";
                                 $xml.="<qLote>{$ProdutoArray[$i]['QUANT']}</qLote>";
                                 $xml.="<dFab>{$ProdutoArray[$i]['DATAFABRICACAO']}</dFab>";
                                 $xml.="<dVal>{$ProdutoArray[$i]['DATAVALIDADE']}</dVal>";
                                 $xml.="<vPMC>0.00</vPMC>"; 
                                 $xml.="<nLote>161008</nLote>";
                                 $xml.="<qLote>6.000</qLote>";
                                 $xml.="<dFab>2016-10-01</dFab>";
                                 $xml.="<dVal>2019-10-19</dVal>";
                                 $xml.="<vPMC>0.00</vPMC>";*/
                             $xml.="</med>";
                        $xml.="</prod>";
                        $xml.="<imposto>";
                            $xml.="<ICMS>";
                              switch ($ProdutoArray[$i]['TRIBICMS']){
                                    case '00': // tributado integralmente
                                        $xml.="<ICMS00>"; 
                                            $xml.="<orig>{$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>00</CST>";
                                            $xml.="<modBC>3</modBC>";
                                            $xml.="<vBC>{$ProdutoArray[$i]['BCICMS']}</vBC>";
                                            $xml.="<pICMS>{$ProdutoArray[$i]['ALIQICMS']}</pICMS>";
                                            $xml.="<vICMS>{$ProdutoArray[$i]['VALORICMS']}</vICMS>";
                                        $xml.="</ICMS00>";
                                        break;
                                    case '10': // Tributada e com cobrança do ICMS por substituição tributária
                                        $xml.="<ICMS10>"; 
                                            $xml.="<orig>{$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>10</CST>";
                                            $xml.="<modBC>3</modBC>";
                                            $xml.="<vBC>{$ProdutoArray[$i]['BCICMS']}</vBC>";
                                            $xml.="<plICMS>{$ProdutoArray[$i]['ALIQICMS']}</plICMS>";
                                            $xml.="<vlICMS>{$ProdutoArray[$i]['VALORICMS']}</vlICMS>";
                                            $xml.="<modBCST>3</modBCST>"; // incluir
                                            $xml.="<pMVAST>{$ProdutoArray[$i]['PERCMVAST']}</pMVAST>"; // incluir
                                            $xml.="<pRedBCST>{$ProdutoArray[$i]['PERCREDUCAOBCST']}</pRedBCST>"; // incluir
                                            $xml.="<vBCST>{$ProdutoArray[$i]['VALORBCST']}</vBCST>"; // incluir
                                            $xml.="<plICMSST>{$ProdutoArray[$i]['ALIQICMSST']}</plICMSST>"; // incluir
                                            $xml.="<vlICMSST>{$ProdutoArray[$i]['VALORICMSST']}</vlICMSST>"; // incluir
                                        $xml.="</ICMS10>";
                                        break;
                                    case '20': // Tributação com redução de base de cálculo
                                        $xml.="<ICMS20>"; 
                                            $xml.="<orig>$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>20</CST>";
                                            $xml.="<modBC>3</modBC>";
                                            $xml.="<pRedBC>{$ProdutoArray[$i]['PERCREDUCAOBC']}</pRedBC>"; // incluir
                                            $xml.="<vBC>{$ProdutoArray[$i]['BCICMS']}</vBC>";
                                            $xml.="<plICMS>{$ProdutoArray[$i]['ALIQICMS']}</plICMS>";
                                            $xml.="<vlICMS>{$ProdutoArray[$i]['VALORICMS']}</vlICMS>";
                                        $xml.="</ICMS20>";
                                        break;
                                    case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                                        $xml.="<ICMS30>"; 
                                            $xml.="<orig>$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>30</CST>";
                                            $xml.="<modBCST>5</modBCST>"; // incluir
                                            $xml.="<pMVAST>{$ProdutoArray[$i]['PERCMVAST']}</pMVAST>"; // incluir
                                            $xml.="<pRedBCST>{$ProdutoArray[$i]['PERCREDUCAOBCST']}</pRedBCST>"; // incluir
                                            $xml.="<vBCST>{$ProdutoArray[$i]['VALORBCST']}</vBCST>"; // incluir
                                            $xml.="<plICMSST>{$ProdutoArray[$i]['ALIQICMSST']}</plICMSST>"; // incluir
                                            $xml.="<vlICMSST>{$ProdutoArray[$i]['VALORICMSST']}</vlICMSST>"; // incluir
                                        $xml.="</ICMS30>";
                                        break;
                                    case '40': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                                    case '41': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                                    case '50': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                                        $xml.="<ICMS40>"; 
                                            $xml.="<orig>$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>$ProdutoArray[$i]['TRIBICMS']}</CST>"; // 40=Isenta;41=Não tributada;50=Suspensão.
                                        $xml.="</ICMS40>";
                                        break;
                                    case '51': // Tributação com Diferimento (a exigência do preenchimento das
                                               //informações do ICMS diferido fica a critério de cada UF).
                                        $xml.="<ICMS51>"; 
                                            $xml.="<orig>$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>51</CST>";
                                            $xml.="<modBC>3</modBC>";
                                            $xml.="<pRedBC>{$ProdutoArray[$i]['PERCREDUCAOBC']}</pRedBC>"; // incluir
                                            $xml.="<vBC>{$ProdutoArray[$i]['BCICMS']}</vBC>";
                                            $xml.="<plICMS>{$ProdutoArray[$i]['ALIQICMS']}</plICMS>";
                                            $xml.="<vlICMSOp>{$ProdutoArray[$i]['VALORICMSOPERACAO']}</vlICMSOp>"; //incluir
                                            $xml.="<pDif>{$ProdutoArray[$i]['PERCDIFERIDO']}</pDif>"; //incluir
                                            $xml.="<vlICMSDif>{$ProdutoArray[$i]['VALORICMSDIFERIDO']}</vlICMSDif>";
                                            $xml.="<vlICMS>{$ProdutoArray[$i]['VALORICMS']}</vlICMS>";
                                        $xml.="</ICMS51>";
                                        break;
                                    case '60': // Tributação ICMS cobrado anteriormente por substituição tributária
                                        $xml.="<ICMS60>"; 
                                            $xml.="<orig>$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>60</CST>";
                                            $xml.="<modBC>3</modBC>";
                                            $xml.="<vBCSTRet>{$ProdutoArray[$i]['VALORBCSTRETIDO']}</vBCSTRet>"; // incluir
                                            $xml.="<vICMSSTRet>{$ProdutoArray[$i]['VALORICMSSTRETIDO']}</vICMSSTRet>"; // incluir
                                        $xml.="</ICMS60>";
                                        break;
                                    case '70': // Tributação ICMS com redução de base de cálculo e cobrança
                                               // do ICMS por substituição tributária
                                        $xml.="<ICMS70>"; 
                                            $xml.="<orig>$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>70</CST>";
                                            $xml.="<modBC>3</modBC>";
                                            $xml.="<pRedBC>{$ProdutoArray[$i]['PERCREDUCAOBC']}</pRedBC>"; // incluir
                                            $xml.="<vBC>{$ProdutoArray[$i]['BCICMS']}</vBC>";
                                            $xml.="<plICMS>{$ProdutoArray[$i]['ALIQICMS']}</plICMS>";
                                            $xml.="<vlICMS>{$ProdutoArray[$i]['VALORICMS']}</vlICMS>";
                                            $xml.="<modBCST>5</modBCST>"; // incluir
                                            $xml.="<pMVAST>{$ProdutoArray[$i]['PERCMVAST']}</pMVAST>"; // incluir
                                            $xml.="<pRedBCST>{$ProdutoArray[$i]['PERCREDUCAOBCST']}</pRedBCST>"; // incluir
                                            $xml.="<vBCST>{$ProdutoArray[$i]['VALORBCST']}</vBCST>"; // incluir
                                            $xml.="<plICMSST>{$ProdutoArray[$i]['ALIQICMSST']}</plICMSST>"; // incluir
                                            $xml.="<vlICMSST>{$ProdutoArray[$i]['VALORICMSST']}</vlICMSST>"; // incluir
                                        $xml.="</ICMS70>";
                                        break;
                                    case '90': // Tributação ICMS: Outros
                                        $xml.="<ICMS90>"; 
                                            $xml.="<orig>$ProdutoArray[$i]['ORIGEM']}</orig>";
                                            $xml.="<CST>90</CST>";
                                            $xml.="<modBC>3</modBC>";
                                            $xml.="<pRedBC>{$ProdutoArray[$i]['PERCREDUCAOBC']}</pRedBC>"; // incluir
                                            $xml.="<vBC>{$ProdutoArray[$i]['BCICMS']}</vBC>";
                                            $xml.="<plICMS>{$ProdutoArray[$i]['ALIQICMS']}</plICMS>";
                                            $xml.="<vlICMS>{$ProdutoArray[$i]['VALORICMS']}</vlICMS>";
                                            $xml.="<modBCST>5</modBCST>"; // incluir
                                            $xml.="<pMVAST>{$ProdutoArray[$i]['PERCMVAST']}</pMVAST>"; // incluir
                                            $xml.="<pRedBCST>{$ProdutoArray[$i]['PERCREDUCAOBCST']}</pRedBCST>"; // incluir
                                            $xml.="<vBCST>{$ProdutoArray[$i]['VALORBCST']}</vBCST>"; // incluir
                                            $xml.="<plICMSST>{$ProdutoArray[$i]['ALIQICMSST']}</plICMSST>"; // incluir
                                            $xml.="<vlICMSST>{$ProdutoArray[$i]['VALORICMSST']}</vlICMSST>"; // incluir

                                        $xml.="</ICMS90>";
                                        break;

                                }
                            
                            
                            $xml.="</ICMS>";
                            $xml.="<IPI>";
                                $xml.="<cEnq>123</cEnq>";
                                $xml.="<IPINT>";
                                    $xml.="<CST>53</CST>";
                                $xml.="</IPINT>";
                            $xml.="</IPI>";
                            $xml.="<PIS>";
                                $xml.="<PISNT>";
                                    $xml.="<CST>07</CST>";
                                $xml.="</PISNT>";
                            $xml.="</PIS>";
                            $xml.="<COFINS>";
                                $xml.="<COFINSNT>";
                                    $xml.="<CST>07</CST>";
                                $xml.="</COFINSNT>";
                            $xml.="</COFINS>";
                        $xml.="</imposto>";
                    $xml.="</det>";
                }
                
                
                $xml.="<total>";
                    $xml.="<ICMSTot>";
                        $xml.="<vBC>{$vBCTotal}</vBC>";
                        $xml.="<vICMS>{$vICMSTotal}</vICMS>";
                        $xml.="<vICMSDeson>0.00</vICMSDeson>";
                        $xml.="<vFCPUFDest>0.00</vFCPUFDest>";
                        $xml.="<vICMSUFDest>0.00</vICMSUFDest>";
                        $xml.="<vICMSUFRemet>0.00</vICMSUFRemet>";
                        $xml.="<vBCST>{$vBCSTTotal}</vBCST>";                        
                        $xml.="<vST>{$vSTTotal}</vST>";
                        $xml.="<vProd>{$vProdTotal}</vProd>";
                        $xml.="<vFrete>0.00</vFrete>";
                        $xml.="<vSeg>0.00</vSeg>";
                        $xml.="<vDesc>0.00</vDesc>";
                        $xml.="<vII>0.00</vII>";
                        $xml.="<vIPI>0.00</vIPI>";
                        $xml.="<vPIS>0.00</vPIS>";                        
                        $xml.="<vCOFINS>0.00</vCOFINS>";
                        $xml.="<vOutro>0.00</vOutro>";
                        $xml.="<vNF>{$vNFTotal}</vNF>";
                        $xml.="<vTotTrib>0.00</vTotTrib>";
                    $xml.="</ICMSTot>";
                $xml.="</total>";
                
                $xml.="<transp>"; // pag 222 - Informações do Transporte da NF-e
                    $xml.="<modFrete>{$NfArray[0][MODFRETE]}</modFrete>";
                    if (is_array($transpArray)):
                        $xml.="<transporta>";
                            if ($transpArray[0][PESSOA] == "J"):
                                $xml.="<CNPJ>".str_pad($transpArray[0]['CNPJCPF'], 14, "0", STR_PAD_LEFT)."</CNPJ>";
                            else:    
                                $xml.="<CPF>".str_pad($transpArray[0]['CNPJCPF'], 11, "0", STR_PAD_LEFT)."</CPF>";
                            endif;
                            $xml.="<xNome>{$transpArray[0][NOME]}</xNome>";
                            if ($transpArray[0][INSCESTRG] != ""):
                                $xml.="<IE>{$transpArray[0][INSCESTRG]}</IE>";
                            endif;
                            $xEnder = $this->removeAcentos($transpArray[0]['ENDERECO'].", ".$transpArray[0]['NUMERO']." - ".$transpArray[0]['COMPLEMENTO']." - ".$transpArray[0]['BAIRRO']);
                            $xml.="<xEnder>{$xEnder}</xEnder>";
                            $xml.="<xMun>{$transpArray[0][CIDADE]}</xMun>";
                            $xml.="<UF>{$transpArray[0][UF]}</UF>";
                        $xml.="</transporta>";
                    endif;
                    $xml.="<vol>";
                        $xml.="<qVol>{$NfArray[0][VOLUME]}</qVol>";
                        $xml.="<esp>{$this->removeAcentos($NfArray[0][VOLESPECIE])}</esp>";
                        $xml.="<marca>{$this->removeAcentos($NfArray[0][VOLMARCA])}</marca>";
                        $xml.="<nVol>{$NfArray[0][VOLUME]}</nVol>";
                        $xml.="<pesoL>{$NfArray[0][VOLPESOLIQ]}</pesoL>";
                        $xml.="<pesoB>{$NfArray[0][VOLPESOBRUTO]}</pesoB>";
                    $xml.="</vol>";
                $xml.="</transp>";

                //$xml.="<cobr>"; // pag 224 - Informações do Transporte da NF-e
                //$xml.="</cobr>";
                
                $xml.="<infAdic>";
                    $xml.="<infAdFisco>teste.</infAdFisco>";
                    $xml.="<infCpl>teste.</infCpl>";
                $xml.="</infAdic>";
                
            $xml.= "</infNFe>";

//não utilizado tecnospeed
            /*            
            $xml.="<Signature xmlns=\"http://www.w3.org/2000/09/xmldsig#\">";
                $xml.="<SignedInfo>";
                    $xml.="<CanonicalizationMethod Algorithm=\"http://www.w3.org/TR/2001/REC-xml-c14n-20010315\"/>";
                    $xml.="<SignatureMethod Algorithm=\"http://www.w3.org/2000/09/xmldsig#rsa-sha1\"/>";
                    $xml.="<Reference URI=\"#NFe41160309112859000167550010000016551079099002\">";
                        $xml.="<Transforms>";
                            $xml.="<Transform Algorithm=\"http://www.w3.org/2000/09/xmldsig#enveloped-signature\"/>";
                            $xml.="<Transform Algorithm=\"http://www.w3.org/TR/2001/REC-xml-c14n-20010315\"/>";
                        $xml.="</Transforms>";
                        $xml.="<DigestMethod Algorithm=\"http://www.w3.org/2000/09/xmldsig#sha1\"/>";
                       // $xml.="<DigestValue>GYQDW8w/pA/4YDL3ctboUnyBl18=</DigestValue>";
                        $xml.="<DigestValue/>";
                    $xml.="</Reference>";
                $xml.="</SignedInfo>";
                $xml.="<SignatureValue></SignatureValue>";
                $xml.="<KeyInfo>";
                    $xml.="<X509Data>";
                        $xml.="<X509Certificate></X509Certificate>";
                    $xml.="</X509Data>";
                $xml.="</KeyInfo>";
            $xml.="</Signature>";
            
        $xml .= "</NFe>";
        $xml .="<protNFe versao=\"3.10\">";
            $xml .="<infProt>";
                $xml .="<tpAmb>1</tpAmb>";
                $xml .="<verAplic>PR-v3_5_1</verAplic>";
                $xml .="<chNFe>41160309112859000167550010000016551079099002</chNFe>";
                $xml .="<dhRecbto>2016-03-03T10:07:48-03:00</dhRecbto>";
                $xml .="<nProt>141160034492476</nProt>";
                $xml .="<digVal>GYQDW8w/pA/4YDL3ctboUnyBl18=</digVal>";
                $xml .="<cStat>100</cStat>";
                $xml .="<xMotivo>Autorizado o uso da NF-e</xMotivo>";
            $xml .="</infProt>";
        $xml .="</protNFe>";
    $xml .="</nfeProc>";
 */    
//não utilizado tecnospeed
            
    $path = ADMraizCliente;
    $slash = '/'; 
    (stristr( $path, $slash )) ? '' : $slash = '\\'; 
    define( 'BASE_DIR', $path.$slash.'nfe'.$slash); 

    $dirPath = BASE_DIR;
    if (!file_exists($dirPath)) {
            $rs = @mkdir( $dirPath, 0777 ); 
            if( $rs ){
                if ($tipoNf == '1'){
                    // NF Saida
                    $dirPath = $dirPath.'saida'.$slash;
                    if (!file_exists($dirPath)){
                        $rs = @mkdir( $dirPath, 0777 ); 
                        if( !$rs ){
                            echo "Erro ao Criar diretorio: ".$dirPath;
                        }
                    }
                }else{
                    // NF entrada
                    $dirPath = $dirPath.'entrada'.$slash;
                    if (!file_exists($dirPath)){
                        $rs = @mkdir( $dirPath, 0777 ); 
                        if( !$rs ){
                            echo "Erro ao Criar diretorio: ".$dirPath;
                        }
                    }
                }
            }else{
                echo "Erro ao Criar diretorio: ".$dirPath;
            }
                
    }else{
        if ($tipoNf == '1'){
            // NF Saida
            $dirPath = $dirPath.'saida'.$slash;
            if (!file_exists($dirPath)){
                $rs = @mkdir( $dirPath, 0777 ); 
                if( !$rs ){
                    echo "Erro ao Criar diretorio: ".$dirPath;
                }
            }
        }else{
            // NF entrada
            $dirPath = $dirPath.'entrada'.$slash;
            if (!file_exists($dirPath)){
                $rs = @mkdir( $dirPath, 0777 ); 
                if( !$rs ){
                    echo "Erro ao Criar diretorio: ".$dirPath;
                }
            }
        }
    }
    
//      CURLOPT_POSTFIELDS => "Grupo=edoc&CNPJ=08187168000160&Arquivo=Formato%3DXML%0A%3CinfNFe%20Id%3D%22NFe41170108187168000160553210000000001000000016%22%20versao%3D%223.10%22%3E%3Cide%3E%3CcUF%3E41%3C%2FcUF%3E%3CcNF%3E00000001%3C%2FcNF%3E%3CnatOp%3EVENDA%20MERC.ADQ.REC.TERC%3C%2FnatOp%3E%3CindPag%3E1%3C%2FindPag%3E%3Cmod%3E55%3C%2Fmod%3E%3Cserie%3E321%3C%2Fserie%3E%3CnNF%3E0%3C%2FnNF%3E%3CdhEmi%3E2017-01-09T10%3A29%3A19-02%3A00%3C%2FdhEmi%3E%3CtpNF%3E1%3C%2FtpNF%3E%3CidDest%3E2%3C%2FidDest%3E%3CcMunFG%3E4115200%3C%2FcMunFG%3E%3CtpImp%3E1%3C%2FtpImp%3E%3CtpEmis%3E1%3C%2FtpEmis%3E%3CcDV%3E6%3C%2FcDV%3E%3CtpAmb%3E2%3C%2FtpAmb%3E%3CfinNFe%3E1%3C%2FfinNFe%3E%3CindFinal%3E1%3C%2FindFinal%3E%3CindPres%3E9%3C%2FindPres%3E%3CprocEmi%3E0%3C%2FprocEmi%3E%3CverProc%3E000%3C%2FverProc%3E%3C%2Fide%3E%3Cemit%3E%3CCNPJ%3E08187168000160%3C%2FCNPJ%3E%3CxNome%3ETecnoSpeed%3C%2FxNome%3E%3CxFant%3ETecnoSpeed%3C%2FxFant%3E%3CenderEmit%3E%3CxLgr%3EAvenida%20Duque%20de%20Caxias%3C%2FxLgr%3E%3Cnro%3E882%3C%2Fnro%3E%3CxCpl%3ESalas%20102%20e%20909%3C%2FxCpl%3E%3CxBairro%3ECentro%3C%2FxBairro%3E%3CcMun%3E4115200%3C%2FcMun%3E%3CxMun%3EMaringa%3C%2FxMun%3E%3CUF%3EPR%3C%2FUF%3E%3CCEP%3E87020025%3C%2FCEP%3E%3CcPais%3E1058%3C%2FcPais%3E%3CxPais%3EBrasil%3C%2FxPais%3E%3Cfone%3E4430379500%3C%2Ffone%3E%3C%2FenderEmit%3E%3CIE%3E9044016688%3C%2FIE%3E%3CIM%3E8214100028%3C%2FIM%3E%3CCRT%3E3%3C%2FCRT%3E%3C%2Femit%3E%3Cdest%3E%3CCNPJ%3E08187168000160%3C%2FCNPJ%3E%3CxNome%3ENF-E%20EMITIDA%20EM%20AMBIENTE%20DE%20HOMOLOGACAO%20-%20SEM%20VALOR%20FISCAL%3C%2FxNome%3E%3CenderDest%3E%3CxLgr%3ERua%20Parnaiba%3C%2FxLgr%3E%3Cnro%3E897%3C%2Fnro%3E%3CxBairro%3ESanto%20Antao%3C%2FxBairro%3E%3CcMun%3E4115200%3C%2FcMun%3E%3CxMun%3EMaringa%3C%2FxMun%3E%3CUF%3EPR%3C%2FUF%3E%3CCEP%3E95700000%3C%2FCEP%3E%3CcPais%3E1058%3C%2FcPais%3E%3CxPais%3EBRASIL%3C%2FxPais%3E%3Cfone%3E445555555%3C%2Ffone%3E%3C%2FenderDest%3E%3CindIEDest%3E1%3C%2FindIEDest%3E%3CIE%3E9044016688%3C%2FIE%3E%3C%2Fdest%3E%3Cdet%20nItem%3D%221%22%3E%3Cprod%3E%3CcProd%3E1%3C%2FcProd%3E%3CcEAN%3E%3C%2FcEAN%3E%3CxProd%3EADAPTADOR%20WIRELESS%20USB%20INTELBRAS%20%2054%20MBPS%20WBG901%20(OT0909002580)%3C%2FxProd%3E%3CNCM%3E84183000%3C%2FNCM%3E%3CCFOP%3E6101%3C%2FCFOP%3E%3CuCom%3EUn%3C%2FuCom%3E%3CqCom%3E10.0000%3C%2FqCom%3E%3CvUnCom%3E15.52%3C%2FvUnCom%3E%3CvProd%3E155.20%3C%2FvProd%3E%3CcEANTrib%3E%3C%2FcEANTrib%3E%3CuTrib%3EUn%3C%2FuTrib%3E%3CqTrib%3E10.0000%3C%2FqTrib%3E%3CvUnTrib%3E15.5200%3C%2FvUnTrib%3E%3CindTot%3E1%3C%2FindTot%3E%3C%2Fprod%3E%3Cimposto%3E%3CICMS%3E%3CICMS00%3E%3Corig%3E0%3C%2Forig%3E%3CCST%3E00%3C%2FCST%3E%3CmodBC%3E3%3C%2FmodBC%3E%3CvBC%3E155.20%3C%2FvBC%3E%3CpICMS%3E12.00%3C%2FpICMS%3E%3CvICMS%3E18.62%3C%2FvICMS%3E%3C%2FICMS00%3E%3C%2FICMS%3E%3CPIS%3E%3CPISOutr%3E%3CCST%3E99%3C%2FCST%3E%3CvBC%3E155.20%3C%2FvBC%3E%3CpPIS%3E5.00%3C%2FpPIS%3E%3CvPIS%3E7.76%3C%2FvPIS%3E%3C%2FPISOutr%3E%3C%2FPIS%3E%3CCOFINS%3E%3CCOFINSOutr%3E%3CCST%3E99%3C%2FCST%3E%3CvBC%3E155.20%3C%2FvBC%3E%3CpCOFINS%3E5.00%3C%2FpCOFINS%3E%3CvCOFINS%3E7.76%3C%2FvCOFINS%3E%3C%2FCOFINSOutr%3E%3C%2FCOFINS%3E%3C%2Fimposto%3E%3C%2Fdet%3E%3Ctotal%3E%3CICMSTot%3E%3CvBC%3E155.20%3C%2FvBC%3E%3CvICMS%3E18.62%3C%2FvICMS%3E%3CvICMSDeson%3E0.00%3C%2FvICMSDeson%3E%3CvBCST%3E0.00%3C%2FvBCST%3E%3CvST%3E0.00%3C%2FvST%3E%3CvProd%3E155.20%3C%2FvProd%3E%3CvFrete%3E0.00%3C%2FvFrete%3E%3CvSeg%3E0.00%3C%2FvSeg%3E%3CvDesc%3E0.00%3C%2FvDesc%3E%3CvII%3E0.00%3C%2FvII%3E%3CvIPI%3E0.00%3C%2FvIPI%3E%3CvPIS%3E7.76%3C%2FvPIS%3E%3CvCOFINS%3E7.76%3C%2FvCOFINS%3E%3CvOutro%3E0.00%3C%2FvOutro%3E%3CvNF%3E155.20%3C%2FvNF%3E%3C%2FICMSTot%3E%3C%2Ftotal%3E%3Ctransp%3E%3CmodFrete%3E0%3C%2FmodFrete%3E%3C%2Ftransp%3E%3Ccobr%3E%3Cfat%3E%3CnFat%3E2000%3C%2FnFat%3E%3CvOrig%3E500.00%3C%2FvOrig%3E%3CvDesc%3E100.00%3C%2FvDesc%3E%3CvLiq%3E400.00%3C%2FvLiq%3E%3C%2Ffat%3E%3Cdup%3E%3CnDup%3E4%3C%2FnDup%3E%3CdVenc%3E2009-04-25%3C%2FdVenc%3E%3CvDup%3E100.00%3C%2FvDup%3E%3C%2Fdup%3E%3C%2Fcobr%3E%3CinfAdic%3E%3CinfAdFisco%3EOBSERVACAO%20TESTE%20DA%20DANFE%20-%20FISCO%3C%2FinfAdFisco%3E%3CinfCpl%3EOBSERVACAO%20TESTE%20DA%20DANFE%20%7C%20CONTRIBUINTE%3C%2FinfCpl%3E%3C%2FinfAdic%3E%3C%2FinfNFe%3E",
    
    //envio nfe
    $xmlEncode = urlencode("Formato=XML\r\n".$xml);

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://managersaas.tecnospeed.com.br:8081/ManagerAPIWeb/nfe/envia",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 60,
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,        
      CURLOPT_SSLVERSION => 3,        
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
        
      CURLOPT_POSTFIELDS => "grupo=maxifarma&cnpj=02829379000172&arquivo=".$xmlEncode,
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic YWRtaW46bWF4aTEyM25mZQ=="
      ),
    ));

    try {
    
        $response = curl_exec($curl);
        $err = curl_error($curl);
    
    }catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }


    curl_close($curl);

//    echo "<br><br><br>";
//    echo $xmlEncode;
    
    $ponteiro = fopen($dirPath."ENCODE".$titulo.'.xml', 'w'); //cria um arquivo com o nome backup.xml
    fwrite($ponteiro, $xmlEncode); // salva conteúdo da variável $xml dentro do arquivo backup.xml

    $ponteiro = fclose($ponteiro); //fecha o arquivo

    $ponteiro = fopen($dirPath.$titulo.'.xml', 'w'); //cria um arquivo com o nome backup.xml
    fwrite($ponteiro, $xml); // salva conteúdo da variável $xml dentro do arquivo backup.xml

    $ponteiro = fclose($ponteiro); //fecha o arquivo

    // retorno autorizacao
    if ($err):
      return "cURL Error #:" . $err;
    else:
        if (strpos($response, 'Autorizado o uso da NF-e') === false ):
            return $response;
        else:
            return $response;
        endif;
        
    endif;

    
    }
    
}
