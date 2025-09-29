<?php
$dir = (__DIR__);

//error_reporting(E_ALL);
ini_set('display_errors', 'Off');
require_once $dir . '/../../../nfephp/bootstrap.php';

use NFePHP\Extras\Danfe;
use NFePHP\Extras\Danfce;
use NFePHP\Extras\Dacce;
use NFePHP\Common\Files\FilesFolders;
use NFePHP\NFe\MakeNFe;
use NFePHP\NFe\ToolsNFe;

include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_mail.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_nota_fiscal.php");
include_once($dir . "/../../class/est/c_nota_fiscal_produto.php");



/**
 * Description of c_exporta_xml
 *
 * @author lucas
 */
class p_exporta_xml {
  
   
//    public function __construct($idNf,$filial, $tipoNf) {
//        $this->Gera_XML($idNf, $filial,$tipoNf);
//    }
    public function __construct() {
        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //// $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        //$this->nfePath = ADMnfe.$this->m_empresaid.$slash.ADMambDesc;
        $slash = '/'; 
        define( 'BASE_DIR_NFE_CFG', ADMnfe.$this->m_empresaid.$slash.'config'); 
        define( 'BASE_DIR_NFE_AMB', ADMnfe.$this->m_empresaid.$slash.ADMambDesc); 
        define( 'BASE_HTTP_NFE_AMB', ADMhttpCliente.$slash.'nfe'.$slash.$this->m_empresaid.$slash.ADMambDesc.$slash); 
        
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
    public function MostraData($data, $tipo=null) {
        $aux = explode(" ", $data);
        if ($tipo=='D'):
            return $aux[0];
        else:
            // horario de verão return $aux[0]."T".$aux[1]."-02:00";
            return $aux[0]."T".$aux[1]."-03:00";
        endif;
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
     * Funcao para CANCELAR uma NFe assinada
     * @param VARCHAR $chave nfe
     */
    public function cancela_NFE($chave, $nProt, $xJust, $modelo) {
        try {
            //$nfeTools = new ToolsNFe(ADMraizCliente . '/nfe/config/config_'.$this->m_empresaid.'.json');
            $nfeTools = new ToolsNFe(BASE_DIR_NFE_CFG.'/config.json');
            $nfeTools->setModelo($modelo);
            
            // cancela nfe
            $aResposta = array();
            $tpAmb = ADMnfeAmbiente;
            $retorno = $nfeTools->sefazCancela($chave, $tpAmb, $xJust, $nProt, $aResposta);

            return $aResposta;

        } catch (Exception $e) {
            return "Cancelamento NF NÃO realizado <br>".$e.message;
           //throw new Exception($e->getMessage() );
        }
    }
    
    /**
     * Funcao para enviar CARTA DE CORREÇÃO uma NFe assinada
     * @param VARCHAR $chave nfe
     */
    public function carta_correcao_NFE($chave, $nProt, $xCorrecao, $modelo, $nSeqEvento = 1) {
        try {
            //$nfeTools = new ToolsNFe(ADMraizCliente . '/nfe/config/config_'.$this->m_empresaid.'.json');
            $nfeTools = new ToolsNFe(BASE_DIR_NFE_CFG.'/config.json');
            $nfeTools->setModelo($modelo);
            
            // cancela nfe
            
            $aResposta = array();
            $tpAmb = ADMnfeAmbiente;
            

            $retorno = $nfeTools->sefazCCe($chave, $tpAmb, $xCorrecao, $nSeqEvento, $aResposta);

            return $aResposta;

        } catch (Exception $e) {
            return "carta Correção NF NÃO realizado <br>".$e.message;
           //throw new Exception($e->getMessage() );
        }
    }
    
    /**
     * Funcao para enviar VISUALIZAR CARTA DE CORREÇÃO uma NFe assinada
     * @param VARCHAR $chave nfe
     */
    public function visualizar_carta_correcao_NFE($chave, $nProt, $nSeqEvento = 1, $anomes, $aEnd, &$arq='') {
        try {
            // impressão carta correção
            $nfProc = '-CCe-'.$nSeqEvento.'-procEvento.xml';
            $nfExtPdf = '-CCe-'.$nSeqEvento.'.pdf';

            // monta dir files
            $path = BASE_DIR_NFE_AMB;
            $http = BASE_HTTP_NFE_AMB;
            $slash = '/'; 
            (stristr( $path, $slash )) ? '' : $slash = '\\'; 
            define( 'BASE_DIR_ENVIADA_CARTA_CORRECAO', $path.$slash.'cartacorrecao'.$slash.$anomes.$slash.$chave.$nfProc); 
            define( 'BASE_DIR_PDF', $path.$slash.'pdf'.$slash.$anomes.$slash.$chave.$nfExtPdf); 
            define( 'BASE_HTTP_PDF', $http.$slash.'pdf'.$slash.$anomes.$slash.$chave.$nfExtPdf); 
            $arq = BASE_HTTP_PDF;

            $pathLogo = ADMimg.'/logo.png';
            $docxml = FilesFolders::readFile(BASE_DIR_ENVIADA_CARTA_CORRECAO);

            $dacce = new Dacce($docxml, 'P', 'A4', $pathLogo, 'I', $aEnd);
            $teste = $dacce->printDACCE(BASE_DIR_PDF, 'F');

        } catch (Exception $e) {
            return "carta Correção NF NÃO realizado <br>".$e.message;
           //throw new Exception($e->getMessage() );
        }
    }
    
    /**
     * Funcao para CANCELAR uma NFe assinada
     * @param VARCHAR $chave nfe
     */
    public function inutiliza_NFE($modelo, $nSerie, $nIni, $nFim, $xJust) {
        try {
            
            $nfeTools = new ToolsNFe(BASE_DIR_NFE_CFG.'/config.json');
            $nfeTools->setModelo($modelo);
            
            // cancela nfe
            $aResposta = array();
            $tpAmb = ADMnfeAmbiente;

            $xml = $nfeTools->sefazInutiliza($nSerie, $nIni, $nFim, $xJust, $tpAmb, $aResposta);
            
            return $aResposta;
        } catch (Exception $e) {
            return "Inutilização NÃO realizado <br>".$e.message;
           //throw new Exception($e->getMessage() );
        }
    }
    
    /**
     * Funcao para contruir a DANFE PDF a partir dos xml assinada e protocolo
     * @param VARCHAR $chave nfe
     */
    public function gera_DANFE($chave) {
        try {
            $anomes = date('Ym');
            $nfExt = '-nfe.xml';
            $nfProt = '-protNFe.xml';
            $nfExtPdf = '-danfe.pdf';

            // monta dir files
            $path = BASE_DIR_NFE_AMB;
            $slash = '/'; 
            (stristr( $path, $slash )) ? '' : $slash = '\\'; 
            define( 'BASE_DIR_ENVIADA_APROVADAS', $path.$slash.'enviadas'.$slash.'aprovadas'.$slash.$anomes.$slash.$chave.$nfProt); 
            define( 'BASE_DIR_PDF', $path.$slash.'pdf'.$slash.$anomes.$slash.$chave.$nfExtPdf); 

            $pathLogo = ADMimg.'/logo.png';
            $docxml = FilesFolders::readFile(BASE_DIR_ENVIADA_APROVADAS);
            $danfe = new Danfe($docxml, 'P', 'A4', $pathLogo, 'I', '');
            $id = $danfe->montaDANFE();
            $salva = $danfe->printDANFE(BASE_DIR_PDF, 'F'); //Salva o PDF na pasta
            
            return "Danfe gerada NFe número - ";
        } catch (Exception $e) {
            return "Danfe NÃO gerada NFe número - ";
           //throw new Exception($e->getMessage() );
        }
    }
    
    /**
     * Funcao para enviar email e pdf da DANFE PDF a partir dos xml assinada e protocolo
     * @param VARCHAR $chave nfe
     */
    public function enviaEmailDANFE($modelo, $email=null, $cc=null, $chave, $dhEmi,$cNF,$serie,$xNome) {
        try {
            $dateEmi = explode("/", $dhEmi);

            $anomes = substr($dateEmi[2],0,4).$dateEmi[1];
            $nfExt = '-nfe.xml';
            $nfProt = '-protNFe.xml';
            $nfExtPdf = '-danfe.pdf';


            //$nfeTools = new ToolsNFe(BASE_DIR_NFE_CFG.'/config.json');
            //$nfeTools->setModelo($modelo);


            // monta dir files
            $path = BASE_DIR_NFE_AMB;
            $slash = '/'; 
            (stristr( $path, $slash )) ? '' : $slash = '\\'; 
            define( 'BASE_DIR_ENVIADA_APROVADAS', $path.$slash.'enviadas'.$slash.'aprovadas'.$slash.$anomes.$slash.$chave.$nfProt); 
            define( 'BASE_DIR_PDF', $path.$slash.'pdf'.$slash.$anomes.$slash.$chave.$nfExtPdf); 

            $pathXml = BASE_DIR_ENVIADA_APROVADAS;

            $pathPdf = BASE_DIR_PDF;
            
            if ((is_null($email) or ($email=='')) and (is_null($cc))  or ($cc=='')):
                return 'Email envio não cadastrado';
            else:    
                if (is_null($email)  or ($email=='')):
                    $aMails = array($cc); //se for um array vazio a classe Mail irá pegar os emails do xml
                elseif (is_null($cc)  or ($cc=='')):
                        $aMails = array($email); //se for um array vazio a classe Mail irá pegar os emails do xml
                    else:
                        $aMails = array($email, $cc); //se for um array vazio a classe Mail irá pegar os emails do xml
                endif;    
                $templateFile = ''; //se vazio usará o template padrão da mensagem
                $comPdf = true; //se true, anexa a DANFE no e-mail


                $mail = new admMail;

                $body = "
                        Prezados<br> NF-E EMITIDA EM AMBIENTE DE ".ADMambDesc. "<br>";

                $body .= "
Você está recebendo a Nota Fiscal Eletrônica emitida em ".$dhEmi."com o número ".$cNF.", série ".$serie." de ".$xNome.".<br> Junto com a mercadoria, você receberá também um DANFE (Documento Auxiliar da Nota Fiscal Eletrônica), que acompanha o trânsito das mercadorias.

<br>Podemos conceituar a Nota Fiscal Eletrônica como um documento de existência apenas digital, emitido e armazenado eletronicamente, com o intuito de documentar, para fins fiscais, uma operação de circulação de mercadorias, ocorrida entre as partes. Sua validade jurídica garantida pela assinatura digital do remetente (garantia de autoria e de integridade) e recepção, pelo Fisco, do documento eletrônico, antes da ocorrência do Fato Gerador.

<br>Os registros fiscais e contábeis devem ser feitos, a partir do próprio arquivo da NF-e, anexo neste e-mail, ou utilizando o DANFE, que representa graficamente a Nota Fiscal Eletrônica. A validade e autenticidade deste documento eletrônico pode ser verificada no site nacional do projeto (www.nfe.fazenda.gov.br), através da chave de acesso contida no DANFE.

<br>Para poder utilizar os dados descritos do DANFE na escrituração da NF-e, tanto o contribuinte destinatário, como o contribuinte emitente, terão de verificar a validade da NF-e. Esta validade está vinculada à efetiva existência da NF-e nos arquivos da SEFAZ, e comprovada através da emissão da Autorização de Uso.

<br>O DANFE não é uma nota fiscal, nem substitui uma nota fiscal, servindo apenas como instrumento auxiliar para consulta da NF-e no Ambiente Nacional.

<br><br>Para mais detalhes, consulte: www.nfe.fazenda.gov.br

<br><br>Atenciosamente";
                
               //$result =  $mail->SendMail("mail.admservice.com.br", "marcio.sergio@admservice.com.br", "Comercial admNfe", "mss=2018#-",
               //     $body, "Contato Sistema admNfe", "marcio.sergio@admservice.com.br", "Marcio","","");


                $result = $mail->SendMail("mail.admservice.com.br", "nfemaxi@admservice.com.br", "email Nfe", "renemaxi578", 
                               $body, "Nfe - envio XML/DANFE", $email, "",$cc,"", $pathXml,$pathPdf);


//                $result = $mail->SendMail("smtp.gmail.com", "maxifarmanfe@google.com", "email Nfe", "renemaxi578", $body, "Nfe - envio XML/DANFE", $email, "",$cc,"", $pathXml,$pathPdf);

                //$result = $nfeTools->enviaMail($pathXml, $aMails, $templateFile, $comPdf, $pathPdf);
                //$result = true;               
                if (strstr($result, 'não')):
                //if ($result):
                    return "email XML/DANFE NÃO enviado - entre em contato com o suporte";
                else:    
                    return "email XML/DANFE enviado com sucesso!!!";
                endif;
            endif;
 
        } catch (Exception $e) {
            return 'Erro -> '.$e->getMessage();
        }
    }
    
    /**
     * Funcao para contruir a nota fiscal XML e gerar o arquivo no diretorio raiz
     * @param INT $idNf Chave primaria na table nota_fiscal
     * @param INT $filial filial logado pelo sistema
     * @param INT $tipoNf tipo da NF 0 - Entrada / 1 - Saida
     */
    public function gera_XML($idNf, $filial, $tipoNf, $conn=null) {
        $dir = (__DIR__);

        $nfe = new MakeNFe();

        //$nfeTools = new ToolsNFe(ADMraizCliente . '/nfe/config/config_'.$this->m_empresaid.'.json');
        $nfeTools = new ToolsNFe(BASE_DIR_NFE_CFG.'/config.json');

        
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
        $nfOBJ = new c_nota_fiscal();
        $nfOBJ->setId($idNf);
        $nfArray = $nfOBJ->select_nota_fiscal($conn);
        
        //DADOS DA EMPRESA/EMITENTE
        $filialArray = $this->select_empresa_centro_custo($filial);
        
        // DADOS DO DESTINATARIO
        $pessoaDestOBJ = new c_conta();
        $pessoaDestOBJ->setId($nfArray[0]['PESSOA']);
        $pessoaDestArray = $pessoaDestOBJ->select_conta();

        // DADOS DO TRANSPORTADOR
        $transpOBJ = new c_conta();
        $transpOBJ->setId($nfArray[0]['TRANSPORTADOR']);
        $transpArray = $pessoaDestOBJ->select_conta();

        // DADOS NF PRODUTO
        $nfProdutoOBJ = new c_nota_fiscal_produto();
        $nfProdutoOBJ->setIdNf($idNf);
        $produtoArray = $nfProdutoOBJ->select_nota_fiscal_produto_nf($conn);

        // incluir codigo e desc pais na tabela cidade.
        // codigo do municipio emitente
        $cMunEmit = $filialArray[0]['CODMUNICIPIO']; // pg 175 -incluir código do municipio na tabela amb_empresa, buscas os 2 primeiros digitos do codigo
        // codigo do municipio destinatario
        $cMunDest = $pessoaDestArray[0]['CODMUNICIPIO']; // pg 181 -incluir código do municipio na tabela fin_cliente 
        // pag 180 = CRT = Codigo de Regime Tributario 1=Simples Nacional;2=Simples Nacional, excesso sublimite de receita bruta;3=Regime Normal. (v2.0). 
        // incluir na amb_empresa
        $crt = $filialArray[0]['REGIMETRIBUTARIO'];  // ok código regime tributário 1=Simples Nacional; 2=Simples Nacional, excesso sublimite de receita bruta; 3=Regime Normal. (v2.0).
        
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
        $tipoPessoa = $pessoaDestArray[0]['PESSOA'];
        $ie = $pessoaDestArray[0]['INSCESTRG'];
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
        
        
        // GERA XML NFEPHP
        //Dados da NFe - infNFe
        $cUF = substr($cMunEmit, 0, 2); // pg 175 - buscas os 2 primeiros digitos do codigo do municipio '52'; //codigo numerico do estado
        $cNF = str_pad($nfArray[0]['NUMERO'], 8, "0",STR_PAD_LEFT); //'00000010'; //numero aleatório da NF
        $natOp = $this->removeAcentos($nfArray[0]['NATOPERACAO']); //'Venda de Produto'; //natureza da operação
        $indPag = $nfArray[0]['FORMAPGTO']; //0=Pagamento à vista; 1=Pagamento a prazo; 2=Outros
        $mod = $nfArray[0]['MODELO']; //modelo da NFe 55 ou 65 essa última NFCe
        $serie = $nfArray[0]['SERIE']; //serie da NFe
        $nNF = $nfArray[0]['NUMERO']; // numero da NFe
        $dhEmi = $this->MostraData($nfArray[0]['EMISSAO']); //date("Y-m-d\TH:i:sP");//Formato: “AAAA-MM-DDThh:mm:ssTZD” (UTC - Universal Coordinated Time).
        $tpNF = $nfArray[0]['TIPO']; // 0=Entrada; 1=Saída; 
        if ($filialArray[0]['UF'] == $pessoaDestArray[0]['UF'])
            $idDest = '1'; //1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
        else
            $idDest = '2'; //1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
        $cMunFG = $cMunEmit;
        if ($nfArray[0]['MODELO'] == 55):
            $tpImp = '1';
            $dhSaiEnt = date("Y-m-d\TH:i:sP");//Não informar este campo para a NFC-e.
        else:
            $tpImp = '4';
            $dhSaiEnt = '';//Não informar este campo para a NFC-e.
        endif;
         //0=Sem geração de DANFE; 1=DANFE normal, Retrato; 2=DANFE normal, Paisagem;
                      //3=DANFE Simplificado; 4=DANFE NFC-e; 5=DANFE NFC-e em mensagem eletrônica
                      //(o envio de mensagem eletrônica pode ser feita de forma simultânea com a impressão do DANFE;
                      //usar o tpImp=5 quando esta for a única forma de disponibilização do DANFE).
        $tpEmis = '1'; //1=Emissão normal (não em contingência);
                       //2=Contingência FS-IA, com impressão do DANFE em formulário de segurança;
                       //3=Contingência SCAN (Sistema de Contingência do Ambiente Nacional);
                       //4=Contingência DPEC (Declaração Prévia da Emissão em Contingência);
                       //5=Contingência FS-DA, com impressão do DANFE em formulário de segurança;
                       //6=Contingência SVC-AN (SEFAZ Virtual de Contingência do AN);
                       //7=Contingência SVC-RS (SEFAZ Virtual de Contingência do RS);
                       //9=Contingência off-line da NFC-e (as demais opções de contingência são válidas também para a NFC-e);
                       //Nota: Para a NFC-e somente estão disponíveis e são válidas as opções de contingência 5 e 9.
        $tpAmb = ADMnfeAmbiente; //1=Produção; 2=Homologação
        $finNFe = $nfArray[0]['FINALIDADEEMISSAO']; //1=NF-e normal; 2=NF-e complementar; 3=NF-e de ajuste; 4=Devolução/Retorno.
        //$indFinal = $indFinal; //0=Normal; 1=Consumidor final;
        $indPres = '1'; //0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
                       //1=Operação presencial;
                       //2=Operação não presencial, pela Internet;
                       //3=Operação não presencial, Teleatendimento;
                       //4=NFC-e em operação com entrega a domicílio;
                       //9=Operação não presencial, outros.
        $procEmi = '0'; //0=Emissão de NF-e com aplicativo do contribuinte;
                        //1=Emissão de NF-e avulsa pelo Fisco;
                        //2=Emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco;
                        //3=Emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco.
        $verProc = '4.0.43'; //versão do aplicativo emissor
        $dhCont = ''; //entrada em contingência AAAA-MM-DDThh:mm:ssTZD
        $xJust = ''; //Justificativa da entrada em contingência

        //Numero e versão da NFe (infNFe)
        $ano = date('y', strtotime($dhEmi));
        $mes = date('m', strtotime($dhEmi));
        $cnpj = $nfeTools->aConfig['cnpj'];
        $chave = $nfe->montaChave($cUF, $ano, $mes, $cnpj, $mod, $serie, $nNF, $tpEmis, $cNF);
        $versao = '3.10';
        $resp = $nfe->taginfNFe($chave, $versao);

        $cDV = substr($chave, -1); //Digito Verificador da Chave de Acesso da NF-e, o DV é calculado com a aplicação do algoritmo módulo 11 (base 2,9) da Chave de Acesso.

        //tag IDE
        $resp = $nfe->tagide($cUF, $cNF, $natOp, $indPag, $mod, $serie, $nNF, $dhEmi, $dhSaiEnt, $tpNF, $idDest, $cMunFG, $tpImp, $tpEmis, $cDV, $tpAmb, $finNFe, $indFinal, $indPres, $procEmi, $verProc, $dhCont, $xJust);

        //refNFe NFe referenciada  
        if ($finNFe == 4):
            $refNFe = $nfArray[0]['NFEREFERENCIADA'];
            $resp = $nfe->tagrefNFe($refNFe);
        endif;

        //refNF Nota Fiscal 1A referenciada
        //$cUF = '35';
        //$AAMM = '1312';
        //$CNPJ = '12345678901234';
        //$mod = '1A';
        //$serie = '0';
        //$nNF = '1234';
        //$resp = $nfe->tagrefNF($cUF, $AAMM, $CNPJ, $mod, $serie, $nNF);

        //NFPref Nota Fiscal Produtor Rural referenciada
        //$cUF = '35';
        //$AAMM = '1312';
        //$CNPJ = '12345678901234';
        //$CPF = '123456789';
        //$IE = '123456';
        //$mod = '1';
        //$serie = '0';
        //$nNF = '1234';
        //$resp = $nfe->tagrefNFP($cUF, $AAMM, $CNPJ, $CPF, $IE, $mod, $serie, $nNF);

        //CTeref CTe referenciada
        //$refCTe = '12345678901234567890123456789012345678901234';
        //$resp = $nfe->tagrefCTe($refCTe);

        //ECFref ECF referenciada
        //$mod = '90';
        //$nECF = '12243';
        //$nCOO = '111';
        //$resp = $nfe->tagrefECF($mod, $nECF, $nCOO);

        //Dados do emitente - (Importando dados do config.json)
        $CNPJ = $nfeTools->aConfig['cnpj'];
        $CPF = ''; // Utilizado para CPF na nota
        $xNome = $nfeTools->aConfig['razaosocial'];
        $xFant = $nfeTools->aConfig['nomefantasia'];
        $IE = $nfeTools->aConfig['ie'];
        $IEST = $nfeTools->aConfig['iest'];
        $IM = $nfeTools->aConfig['im'];
        $CNAE = $nfeTools->aConfig['cnae'];
        $CRT = $nfeTools->aConfig['regime'];
        $resp = $nfe->tagemit($CNPJ, $CPF, $xNome, $xFant, $IE, $IEST, $IM, $CNAE, $CRT);

        //endereço do emitente
        $xLgr = $this->removeAcentos($filialArray[0]['TIPOEND']." ".$filialArray[0]['TITULOEND']." ".$filialArray[0]['ENDERECO']);//'Av. Rio de Janeiro';
        $nro = $filialArray[0]['NUMERO'];
        $xCpl = '';
        if (!$filialArray[0]['COMPLEMENTO']==''):
            $xCpl = "<xCpl>{$this->removeAcentos($filialArray[0]['COMPLEMENTO'])}</xCpl>";
        endif;    
        $xBairro = $this->removeAcentos($filialArray[0]['BAIRRO']);
        $cMun = $cMunEmit;
        $xMun = $this->removeAcentos($filialArray[0]['CIDADE']);
        $UF = $filialArray[0]['UF'];
        $CEP = $filialArray[0]['CEP'];
        $cPais = '1058';
        $xPais = 'Brasil';
        $fone = $filialArray[0]['FONEAREA'].$filialArray[0]['FONENUM'];
        $resp = $nfe->tagenderEmit($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);

        //destinatário
        if ($nfArray[0]['MODELO'] == 55):
            if ($tipoPessoa == "J"):
                $CNPJ = $pessoaDestArray[0]['CNPJCPF'];
                $CPF = '';
            else:    
                $CNPJ = '';
                $CPF = $pessoaDestArray[0]['CNPJCPF'];
            endif;
            $idEstrangeiro = '';
            $xNome = $this->removeAcentos($pessoaDestArray[0]['NOME']);
            //$indIEDest = '1';  //acima
            $IE = '';
            if ($indIEDest == 1):
                $IE = $pessoaDestArray[0]['INSCESTRG'];
            endif;
            $ISUF = '';
            if (strlen($pessoaDestArray[0]['SUFRAMA'])>0):
                $ISUF = $pessoaDestArray[0]['SUFRUMA'];
            endif;
            $IM = $pessoaDestArray[0]['IM'];
            if (strlen($pessoaDestArray[0]['EMAILNFE'])>0):
                $email = $pessoaDestArray[0]['EMAILNFE'];
            else:    
                $email = $pessoaDestArray[0]['EMAIL'];
            endif;
            $resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);

            //Endereço do destinatário
            $xLgr = $this->removeAcentos($pessoaDestArray[0]['ENDERECO']); //'Av. Vila Alpes';
            $nro = $this->removeAcentos($pessoaDestArray[0]['NUMERO']);
            $xCpl = '';
            if (!$pessoaDestArray[0]['COMPLEMENTO']==''):
                $xCpl = $this->removeAcentos($pessoaDestArray[0]['COMPLEMENTO']);
            endif;
            $xBairro = $this->removeAcentos($pessoaDestArray[0]['BAIRRO']);
            $cMun = $cMunDest;
            $xMun = $this->removeAcentos($pessoaDestArray[0]['CIDADE']);
            $UF = $this->removeAcentos($pessoaDestArray[0]['UF']);
            $CEP = $this->removeAcentos($pessoaDestArray[0]['CEP']);
            $cPais = '1058';
            $xPais = 'Brasil';
            $fone = $pessoaDestArray[0]['FONEAREA'].$pessoaDestArray[0]['FONENUM'];
            $resp = $nfe->tagenderDest($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
        else:
            //destinatário
            if ($nfArray[0]['CPFNOTA'] != ''):
                if (strlen($nfArray[0]['CPFNOTA']) >11):
                    //$CNPJ = '22886247000190';
                    $CNPJ = $nfArray[0]['CPFNOTA'];
                    $CPF = '';
                else:    
                    $CNPJ = '';
                    $CPF = $nfArray[0]['CPFNOTA'];
                endif;
                
                $idEstrangeiro = '';
                $xNome = '';
                $indIEDest = '9';
                $IE = '';
                $ISUF = '';
                $IM = '';
                $email = '';
                $resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);
            endif;
        endif;

        //Identificação do local de retirada (se diferente do emitente)
        //$CNPJ = '12345678901234';
        //$CPF = '';
        //$xLgr = 'Rua Vanish';
        //$nro = '000';
        //$xCpl = 'Ghost';
        //$xBairro = 'Assombrado';
        //$cMun = '3509502';
        //$xMun = 'Campinas';
        //$UF = 'SP';
        //$resp = $nfe->tagretirada($CNPJ, $CPF, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF);

        //Identificação do local de Entrega (se diferente do destinatário)
        //$CNPJ = '12345678901234';
        //$CPF = '';
        //$xLgr = 'Viela Mixuruca';
        //$nro = '2';
        //$xCpl = 'Quabrada do malandro';
        //$xBairro = 'Favela Mau Olhado';
        //$cMun = '3509502';
        //$xMun = 'Campinas';
        //$UF = 'SP';
        //$resp = $nfe->tagentrega($CNPJ, $CPF, $xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF);

        //Identificação dos autorizados para fazer o download da NFe (somente versão 3.1)
        /*$aAut = array('23401454000170');
        foreach ($aAut as $aut) {
            if (strlen($aut) == 14) {
                $resp = $nfe->tagautXML($aut);
            } else {
                $resp = $nfe->tagautXML('', $aut);
            }
        }*/

        for ($i = 0; $i<count($produtoArray); $i++){
            $nItem = $i+1;
            $cProd = $produtoArray[$i]['CODPRODUTO'];
            if (strlen($produtoArray[$i]['CODIGOBARRAS'])>0):
                // COMPLETA COM ZEROS PARA OS TAMANHOS 8, 12, 13, 14
                $cEAN = $produtoArray[$i]['CODIGOBARRAS'];
                $cEANTrib = $produtoArray[$i]['CODIGOBARRAS'];
            else:
                $cEAN = '';
                $cEANTrib = '';
            endif;
            $xProd = $this->removeAcentos($produtoArray[$i]['DESCRICAO']);
            if (!$produtoArray[$i]['NCM']==''):
                $NCM = $produtoArray[$i]['NCM'];
            else:
                $NCM = '00';
            endif;
            $EXTIPI = '';
            $CFOP = $produtoArray[$i]['CFOP'];
            $uCom = $produtoArray[$i]['UNIDADE'];
            $qCom = $produtoArray[$i]['QUANT'];
            $vUnCom = $produtoArray[$i]['UNITARIO'];
            $vProd = $produtoArray[$i]['TOTAL'];
            $uTrib = $produtoArray[$i]['UNIDADE'];
            $qTrib = $produtoArray[$i]['QUANT'];
            $vUnTrib = $produtoArray[$i]['UNITARIO'];
            $vFrete = '';
            $vSeg = '';
            $vDesc = number_format($produtoArray[$i]['DESCONTO'], 2, '.', '');
            if ($vDesc=='0.00'):
                $vDesc='';
            endif;
            $vOutro = '';
            $indTot = '1';
            $xPed = '';
            $nItemPed = '';
            $nFCI = '';
            $resp = $nfe->tagprod($nItem, $cProd, $cEAN, $xProd, $NCM, $EXTIPI, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $nFCI);
            if (!$produtoArray[$i]['CEST']==''):
                $nfe->tagCEST($nItem, $produtoArray[$i]['CEST']);
            else:
                $nfe->tagCEST($nItem, '');
            endif;

/*            if (!$produtoArray[$i]['LOTE']==''):
                $nLote = $produtoArray[$i]['LOTE'];
                $qLote = number_format($produtoArray[$i]['QUANT'], 3, '.', '');
                $dFab = $produtoArray[$i]['DATAFABRICACAO'];
                $dVal = $produtoArray[$i]['DATAVALIDADE'];
                $vPMC = number_format($produtoArray[$i]['UNITARIO'], 2, '.', '');
                $nfe->tagmed($nItem, $nLote, $qLote, $dFab, $dVal, $vPMC);
            endif;
*/            
            //impostos ============================================
            switch ($crt):
                case '1':
                case '2':
                    //ICMSSN - Tributação ICMS pelo Simples Nacional - CRT (Código de Regime Tributário) = 1 
                    $orig = $produtoArray[$i]['ORIGEM'];
                    $csosn = $produtoArray[$i]['TRIBICMS'];
                    $modBC  = '';
                    $vBC = '';
                    $pRedBC = '';
                    $pICMS = '';
                    $vICMS = '';
                    $pCredSN = '';
                    $vCredICMSSN = '';
                    $modBCST = '';
                    $pMVAST = '';
                    $pRedBCST = '';
                    $vBCST = '';
                    $pICMSST = '';
                    $vICMSST = '';
                    $vBCSTRet = '';
                    $vICMSSTRet = '';
                    $resp = $nfe->tagICMSSN($nItem, $orig, $csosn, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $pCredSN, $vCredICMSSN, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $vBCSTRet, $vICMSSTRet);
                    break;
                    
                case '3':
                    //ICMS - Imposto sobre Circulação de Mercadorias e Serviços
                    $orig = '';
                    $cst = ''; 
                    $modBC = '';
                    $pRedBC = '';
                    $vBC = ''; // = $qTrib * $vUnTrib
                    $pICMS = ''; // Alíquota do Estado de GO p/ 'NCM 2203.00.00 - Cervejas de Malte, inclusive Chope'
                    $vICMS = ''; // = $vBC * ( $pICMS / 100 )
                    $vICMSDeson = '';
                    $motDesICMS = '';
                    $modBCST = '';
                    $pMVAST = '';
                    $pRedBCST = '';
                    $vBCST = '0.00';
                    $pICMSST = '';
                    $vICMSST = '';
                    $pDif = '';
                    $vICMSDif = '';
                    $vICMSOp = '';
                    $vBCSTRet = '';
                    $vICMSSTRet = '';

                    switch ($produtoArray[$i]['TRIBICMS']){
                        case '00': // tributado integralmente
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '00'; 
                            $modBC = '3';
                            $vBC = number_format($produtoArray[$i]['BCICMS'], 2, '.', '');
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            break;
                        case '10': // Tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '10';
                            $modBC = '3';
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            break;
                        case '20': // Tributação com redução de base de cálculo
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '20';
                            $modBC = '3';
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            break;
                        case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '30';
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            break;
                        case '40': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                        case '41': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                        case '50': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = $produtoArray[$i]['TRIBICMS'];
                            break;
                        case '51': // Tributação com Diferimento (a exigência do preenchimento das
                                   //informações do ICMS diferido fica a critério de cada UF).
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '51';
                            $modBC = '3';
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMSOp = $produtoArray[$i]['VALORICMSOPERACAO'];
                            $pDif = $produtoArray[$i]['PERCDIFERIDO'];
                            $vICMSDif = $produtoArray[$i]['VALORICMSDIFERIDO'];
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            break;
                        case '60': // Tributação ICMS cobrado anteriormente por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '60';
                            $modBC = '3';
                            $vBCSTRet = $produtoArray[$i]['VALORBCSTRETIDO'];
                            $vICMSSTRet = $produtoArray[$i]['VALORICMSSTRETIDO'];
                            break;
                        case '70': // Tributação ICMS com redução de base de cálculo e cobrança
                                   // do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '70';
                            $modBC = '3';
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            break;
                        case '90': // Tributação ICMS: Outros
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '90';
                            $modBC = '3';
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            break;
                    } //switch 
                    $resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);
                    
            endswitch;
            
            if ($nfArray[0]['MODELO'] == 55):
                // medicamentos
                if (($produtoArray[$i]['LOTE']!='') and ($produtoArray[$i]['DATAFABRICACAO']!='') AND ($produtoArray[$i]['DATAVALIDADE']!='')):
                    $nLote = $produtoArray[$i]['LOTE'];
                    $qLote = (int) $produtoArray[$i]['QUANT'];
                    $dFab = $this->MostraData($produtoArray[$i]['DATAFABRICACAO'], 'D');

                    $dVal = $this->MostraData($produtoArray[$i]['DATAVALIDADE'],'D');
                    $vPMC = number_format($produtoArray[$i]['UNITARIO'], 2, '.', '');
                    $nfe->tagmed($nItem, $nLote, $qLote, $dFab, $dVal, $vPMC);
                else:
                    $vDescr='';
                    if ($produtoArray[$i]['LOTE']!=''):
                        $vDescr = 'Lote: '.$produtoArray[$i]['LOTE'];
                    endif;
                    if ($produtoArray[$i]['DATAFABRICACAO']!=''):
                        $vDescr = 'Data Fabricação: '.$produtoArray[$i]['DATAFABRICACAO'];
                    endif;
                    if ($produtoArray[$i]['DATAVALIDADE']!=''):
                        $vDescr = 'Data Validade: '.$produtoArray[$i]['DATAVALIDADE'];
                    endif;
                    if ($vDescr!=''):
                        $resp = $nfe->taginfAdProd($nItem, $vDescr);
                    endif;

                endif;
                
                
            //IPI - Imposto sobre Produto Industrializado  ************* CALCULO IPI 
             /*   
                $cst = '53'; // 50 - Saída Tributada (Código da Situação Tributária)
                $clEnq = '';
                $cnpjProd = '';
                $cSelo = '';
                $qSelo = '';
                $cEnq = '999';
                $vBC = '0';
                $pIPI = '0'; //Calculo por alíquota - 6% Alíquota GO.
                $qUnid = '';
                $vUnid = '';
                $vIPI = '0'; // = $vBC * ( $pIPI / 100 )
                $resp = $nfe->tagIPI($nItem, $cst, $clEnq, $cnpjProd, $cSelo, $qSelo, $cEnq, $vBC, $pIPI, $qUnid, $vUnid, $vIPI);
              * 
              */
            endif;
            
            // TAG PIS
            //PIS - Programa de Integração Social   ************* CALCULO PIS POSTERIORMENTE
            switch ($produtoArray[$i]['CSTPIS']){
                case 01: // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
                case 02: // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCPIS']; 
                    $pPIS = $produtoArray[$i]['ALIQPIS'];
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    $qBCProd = '';
                    $vAliqProd = '';
                    break;
                case 03: //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = ''; 
                    $pPIS = '';
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    $qBCProd = $produtoArray[$i]['BCPIS'];
                    $vAliqProd = $produtoArray[$i]['ALIQPIS'];
                    break;
                case 04: 
                case 05: 
                case 06: 
                case 07: 
                case 08: 
                case 09: 
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = '0'; 
                    $pPIS = '0';
                    $vPIS = '0';
                    $qBCProd = '0';
                    $vAliqProd = '0';
                    break;
                default :
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCPIS']; 
                    $pPIS = $produtoArray[$i]['ALIQPIS'];
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    $qBCProd = '0';
                    $vAliqProd = '0';
            }        
            $cst = sprintf("%02d", $produtoArray[$i]['CSTPIS']); //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
            $resp = $nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);
            
            // TAG COFINS
            //COFINS - Contribuição para o Financiamento da Seguridade Social
            switch ($produtoArray[$i]['CSTCOFINS']){
                case 01: // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
                case 02: // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCCOFINS']; 
                    $pCOFINS = $produtoArray[$i]['ALIQCOFINS'];
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    $qBCProd = '';
                    $vAliqProd = '';
                    break;
                case 03: //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = ''; 
                    $pCOFINS = '';
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    $qBCProd = $produtoArray[$i]['BCCOFINS'];
                    $vAliqProd = $produtoArray[$i]['ALIQCOFINS'];
                    break;
                case 04: 
                case 05: 
                case 06: 
                case 07: 
                case 08: 
                case 09: 
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = '0'; 
                    $pCOFINS = '0';
                    $vCOFINS = '0';
                    $qBCProd = '0';
                    $vAliqProd = '0';
                    break;
                default :
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCCOFINS']; 
                    $pCOFINS = $produtoArray[$i]['ALIQCOFINS'];
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    $qBCProd = '0';
                    $vAliqProd = '0';
            }        
            $cst = sprintf("%02d", $produtoArray[$i]['CSTCOFINS']); //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
            $resp = $nfe->tagCOFINS($nItem, $cst, $vBC, $pCOFINS, $vCOFINS, $qBCProd, $vAliqProd);
            
            //Impostos
            $vTotTrib = number_format($vICMS + $vICMSST + $vIPI + $vPIS + $vCOFINS, 2, '.', ''); // 226.80 ICMS + 51.50 ICMSST + 50.40 IPI + 39.36 PIS + 81.84 CONFIS
            $resp = $nfe->tagimposto($nItem, $vTotTrib);
            
            $vST += $vICMSST; // Total de ICMS ST
            
            $vBCTotal += $produtoArray[$i]['BCICMS'];
            $vICMSTotal += $produtoArray[$i]['VALORICMS'];
            $vICMSDesonTotal = 0;
            $vFCPUFDestTotal = 0;
            $vICMSUFDestTotal=0;
            $vICMSUFRemetTotal=0;
            $vBCSTTotal += $produtoArray[$i]['VALORBCST'];
            $vSTTotal += $produtoArray[$i]['VALORICMSST'];
            $vProdTotal += $produtoArray[$i]['TOTAL'];
            $vFreteTotal=0;
            $vSegTotal=0;
            if ($vDesc>0):
                $vDescTotal+=$vDesc;
            else:
            endif;
            $vIITotal=0;
            $vIPITotal=0;
            $vPISTotal += $vPIS;
            $vCOFINSTotal += $vCOFINS;
            $vOutroTotal=0;
            $vNFTotal=$nfArray[0]['TOTALNF'];
            $vTotTribTotal=0;

        } //for produtos
        
//        $nfe->tagCEST(1, '2345');
//        $nfe->tagCEST(2, '9999');

        ////////////////////////////////////////////////////
        // Informações adicionais na linha do Produto
        /*$nItem = 1; //produtos 1
        $vDesc = 'Barril 30 Litros Chopp Tipo Pilsen - Pedido Nº15';
        $resp = $nfe->taginfAdProd($nItem, $vDesc);*/
        
//        $nItem = 2; //produtos 2
//        $vDesc = 'Caixa com 1000 unidades';
//        $resp = $nfe->taginfAdProd($nItem, $vDesc);

        //DI - Declaração de Importação
        /*$nItem = '1';
        $nDI = '234556786';
        $dDI = date('Y-m-d'); // Formato: “AAAA-MM-DD”
        $xLocDesemb = 'SANTOS';
        $UFDesemb = 'SP';
        $dDesemb = date('Y-m-d'); // Formato: “AAAA-MM-DD”
        $tpViaTransp = '1';
        $vAFRMM = '1.00';
        $tpIntermedio = '1';
        $CNPJ = '';
        $UFTerceiro = '';
        $cExportador = '111';
        $resp = $nfe->tagDI($nItem, $nDI, $dDI, $xLocDesemb, $UFDesemb, $dDesemb, $tpViaTransp, $vAFRMM, $tpIntermedio, $CNPJ, $UFTerceiro, $cExportador);*/

        //adi - Adições
        /*$nItem = '1';
        $nDI = '234556786';
        $nAdicao = '1';
        $nSeqAdicC = '123';
        $cFabricante = 'Klima Chopp';
        $vDescDI = '5.00';
        $nDraw = '9393939';
        $resp = $nfe->tagadi($nItem, $nDI, $nAdicao, $nSeqAdicC, $cFabricante, $vDescDI, $nDraw);*/

        //detExport
        //$nItem = '2';
        //$nDraw = '9393939';
        //$exportInd = '1';
        //$nRE = '2222';
        //$chNFe = '1234567890123456789012345678901234';
        //$qExport = '100';
        //$resp = $nfe->tagdetExport($nItem, $nDraw, $exportInd, $nRE, $chNFe, $qExport);

        //Impostos **********************
/*        $nItem = 1; //produtos 1
        $vTotTrib = '449.90'; // 226.80 ICMS + 51.50 ICMSST + 50.40 IPI + 39.36 PIS + 81.84 CONFIS
        $resp = $nfe->tagimposto($nItem, $vTotTrib);
        $nItem = 2; //produtos 2
        $vTotTrib = '74.34'; // 61.20 ICMS + 2.34 PIS + 10.80 CONFIS
        $resp = $nfe->tagimposto($nItem, $vTotTrib);
*/
        //ICMS - Imposto sobre Circulação de Mercadorias e Serviços *********************************
/*        $nItem = 1; //produtos 1
        $orig = '0';
        $cst = '00'; // Tributado Integralmente
        $modBC = '3';
        $pRedBC = '';
        $vBC = '840.00'; // = $qTrib * $vUnTrib
        $pICMS = '27.00'; // Alíquota do Estado de GO p/ 'NCM 2203.00.00 - Cervejas de Malte, inclusive Chope'
        $vICMS = '226.80'; // = $vBC * ( $pICMS / 100 )
        $vICMSDeson = '';
        $motDesICMS = '';
        $modBCST = '';
        $pMVAST = '';
        $pRedBCST = '';
        $vBCST = '';
        $pICMSST = '';
        $vICMSST = '';
        $pDif = '';
        $vICMSDif = '';
        $vICMSOp = '';
        $vBCSTRet = '';
        $vICMSSTRet = '';
        $resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);

        $nItem = 2; //produtos 2
        $orig = '0';
        $cst = '00';
        $modBC = '3';
        $pRedBC = '';
        $vBC = '360.00'; // = $qTrib * $vUnTrib
        $pICMS = '17.00'; // Alíquota Interna do Estado de GO 
        $vICMS = '61.20'; // = $vBC * ( $pICMS / 100 )
        $vICMSDeson = '';
        $motDesICMS = '';
        $modBCST = '';
        $pMVAST = '';
        $pRedBCST = '';
        $vBCST = ''; 
        $pICMSST = '';
        $vICMSST = '';
        $pDif = '';
        $vICMSDif = '';
        $vICMSOp = '';
        $vBCSTRet = '';
        $vICMSSTRet = '';
        $resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);

        //ICMS 10
        $nItem = 1; //produtos 1
        $orig = '0';
        $cst = '10'; // Tributada e com cobrança do ICMS por substituição tributária
        $modBC = '3';
        $pRedBC = '';
        $vBC = '840.00';
        $pICMS = '27.00'; // Alíquota do Estado de GO p/ 'NCM 2203.00.00 - Cervejas de Malte, inclusive Chope'
        $vICMS = '226.80'; // = $vBC * ( $pICMS / 100 )
        $vICMSDeson = '';
        $motDesICMS = '';
        $modBCST = '5'; // Calculo Por Pauta (valor)
        $pMVAST = '';
        $pRedBCST = '';
        $vBCST = '1030.80'; // Pauta do Chope Claro 1000ml em GO R$ 8,59 x 60 Litros
        $pICMSST = '27.00'; // GO para GO
        $vICMSST = '51.50'; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
        $pDif = '';
        $vICMSDif = '';
        $vICMSOp = '';
        $vBCSTRet = '';
        $vICMSSTRet = '';
        $resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);

        $vST = $vICMSST; // Total de ICMS ST
 */

        //ICMSPart - ICMS em Operações Interestaduais - CST 10 e 90 quando possui partilha (com partilha do ICMS entre a UF origem e a UF de destino ou UF definida na legislação)
        //$resp = $nfe->tagICMSPart($nItem, $orig, $cst, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pBCOp, $ufST);

        //ICMSST - Tributação ICMS por Substituição Tributária (ST) - CST 41 (devido para a UF de destino, nas operações interestaduais de produtos que tiveram retenção antecipada de ICMS por ST na UF do remetente)
        //$resp = $nfe->tagICMSST($nItem, $orig, $cst, $vBCSTRet, $vICMSSTRet, $vBCSTDest, $vICMSSTDest);

        //ICMSSN - Tributação ICMS pelo Simples Nacional - CRT (Código de Regime Tributário) = 1 
        //$resp = $nfe->tagICMSSN($nItem, $orig, $csosn, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $pCredSN, $vCredICMSSN, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $vBCSTRet, $vICMSSTRet);

        //IPI - Imposto sobre Produto Industrializado  ************* CALCULO IPI POSTERIORMENTE
/*        $nItem = 1; //produtos 1
        $cst = '50'; // 50 - Saída Tributada (Código da Situação Tributária)
        $clEnq = '';
        $cnpjProd = '';
        $cSelo = '';
        $qSelo = '';
        $cEnq = '999';
        $vBC = '840.00';
        $pIPI = '6.00'; //Calculo por alíquota - 6% Alíquota GO.
        $qUnid = '';
        $vUnid = '';
        $vIPI = '50.40'; // = $vBC * ( $pIPI / 100 )
        $resp = $nfe->tagIPI($nItem, $cst, $clEnq, $cnpjProd, $cSelo, $qSelo, $cEnq, $vBC, $pIPI, $qUnid, $vUnid, $vIPI);

        $nItem = 2; //produtos 2
        $cst = '53'; // 53 - Saída Não-Tributada
        $clEnq = '';
        $cnpjProd = '';
        $cSelo = '';
        $qSelo = '';
        $cEnq = '999';
        $vBC = '';
        $pIPI = '';
        $qUnid = '';
        $vUnid = '';
        $vIPI = ''; // = $vBC * ( $pIPI / 100 )
        $resp = $nfe->tagIPI($nItem, $cst, $clEnq, $cnpjProd, $cSelo, $qSelo, $cEnq, $vBC, $pIPI, $qUnid, $vUnid, $vIPI);
*/
        //PIS - Programa de Integração Social   ************* CALCULO PIS POSTERIORMENTE
/*
        $nItem = 1; //produtos 1
        $cst = '03'; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
        $vBC = ''; 
        $pPIS = '';
        $vPIS = '39.36';
        $qBCProd = '60.00';
        $vAliqProd = '0.3280';
        $resp = $nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);

        $nItem = 2; //produtos 2
        $cst = '01'; //Operação Tributável (base de cálculo = (valor da operação * alíquota normal) / 100
        $vBC = '180.00'; 
        $pPIS = '0.6500';
        $vPIS = '2.34';
        $qBCProd = '';
        $vAliqProd = '';
        $resp = $nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);

        //PISST
        //$resp = $nfe->tagPISST($nItem, $vBC, $pPIS, $qBCProd, $vAliqProd, $vPIS);

        //COFINS - Contribuição para o Financiamento da Seguridade Social  ************* CALCULO COFINS POSTERIORMENTE

        $nItem = 1; //produtos 1
        $cst = '03'; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
        $vBC = '';
        $pCOFINS = '';
        $vCOFINS = '81.84';
        $qBCProd = '60.00';
        $vAliqProd = '0.682';
        $resp = $nfe->tagCOFINS($nItem, $cst, $vBC, $pCOFINS, $vCOFINS, $qBCProd, $vAliqProd);

        $nItem = 2; //produtos 2
        $cst = '01'; //Operação Tributável (base de cálculo = (valor da operação * alíquota normal) / 100
        $vBC = '180.00';
        $pCOFINS = '3.00';
        $vCOFINS = '10.80';
        $qBCProd = '';
        $vAliqProd = '';
        $resp = $nfe->tagCOFINS($nItem, $cst, $vBC, $pCOFINS, $vCOFINS, $qBCProd, $vAliqProd);
*/
        // impostos PROSUTOS ==================================================================
        
        //COFINSST
        //$resp = $nfe->tagCOFINSST($nItem, $vBC, $pCOFINS, $qBCProd, $vAliqProd, $vCOFINS);

        //II
        //$resp = $nfe->tagII($nItem, $vBC, $vDespAdu, $vII, $vIOF);

        //ICMSTot
        //$resp = $nfe->tagICMSTot($vBC, $vICMS, $vICMSDeson, $vBCST, $vST, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib);

        //ISSQNTot
        //$resp = $nfe->tagISSQNTot($vServ, $vBC, $vISS, $vPIS, $vCOFINS, $dCompet, $vDeducao, $vOutro, $vDescIncond, $vDescCond, $vISSRet, $cRegTrib);

        //retTrib
        //$resp = $nfe->tagretTrib($vRetPIS, $vRetCOFINS, $vRetCSLL, $vBCIRRF, $vIRRF, $vBCRetPrev, $vRetPrev);

        
        //Inicialização de váriaveis não declaradas...
        $vII = isset($vII) ? $vII : 0;
        $vIPI = isset($vIPI) ? $vIPI : 0;
        $vIOF = isset($vIOF) ? $vIOF : 0;
        $vPIS = isset($vPIS) ? $vPIS : 0;
        $vCOFINS = isset($vCOFINS) ? $vCOFINS : 0;
        $vICMS = isset($vICMS) ? $vICMS : 0;
        $vBCST = isset($vBCST) ? $vBCST : 0;
        $vST = isset($vST) ? $vST : 0;
        $vISS = isset($vISS) ? $vISS : 0;

        //total
        $vBC = number_format($vBCTotal, 2, '.', '');
        $vICMS = number_format($vICMSTotal, 2, '.', '');
        $vICMSDeson = '0.00';
        $vBCST = number_format($vBCSTTotal, 2, '.', '');
        $vST = number_format($vSTTotal, 2, '.', '');
        $vProd = number_format($vProdTotal, 2, '.', '');
        $vFrete = '0.00';
        $vSeg = '0.00';
        $vDesc = $vDescTotal;
        $vII = '0.00';
        $vIPI = number_format($vIPITotal, 2, '.', '');
        $vPIS = number_format($vPISTotal, 2, '.', '');
        $vCOFINS = number_format($vCOFINSTotal, 2, '.', '');
        $vOutro = '0.00';
            
        $vNF = number_format($vProd-$vDesc-$vICMSDeson+$vST+$vFrete+$vSeg+$vOutro+$vII+$vIPI, 2, '.', '');
        $vTotTrib = number_format($vICMS+$vST+$vII+$vIPI+$vPIS+$vCOFINS+$vIOF+$vISS, 2, '.', '');
        $resp = $nfe->tagICMSTot($vBC, $vICMS, $vICMSDeson, $vBCST, $vST, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib);

        //frete
        $modFrete = $nfArray[0][MODFRETE]; //0=Por conta do emitente; 1=Por conta do destinatário/remetente; 2=Por conta de terceiros; 9=Sem Frete;
        $resp = $nfe->tagtransp($modFrete);
        switch ($modFrete){
            case '0':
                $CNPJ = str_pad($filialArray[0]['CNPJ'], 14, "0", STR_PAD_LEFT);
                $CPF = '';
                $xNome = $filialArray[0]['NOMEEMPRESA'];
                if ($filialArray[0]['INSCESTADUAL'] != ""):
                    $IE = $filialArray[0][INSCESTADUAL];
                endif;
                $xEnder = $this->removeAcentos($filialArray[0]['ENDERECO'].", ".$filialArray[0]['NUMERO']." - ".$filialArray[0]['COMPLEMENTO']." - ".$filialArray[0]['BAIRRO']);
                $xMun = $filialArray[0]['CIDADE'];
                $UF = $filialArray[0]['UF'];
                $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                break;
            case '1':
                if ($pessoaDestArray[0]['PESSOA'] == "J"):
                    $CNPJ = str_pad($pessoaDestArray[0]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
                    $CPF = '';
                else:
                    $CNPJ = '';
                    $CPF = str_pad($pessoaDestArray[0]['CNPJCPF'], 11, "0", STR_PAD_LEFT);
                endif;
                $xNome = $pessoaDestArray[0]['NOME'];
                if ($pessoaDestArray[0]['INSCESTRG'] != ""):
                    $IE = $pessoaDestArray[0]['INSCESTRG'];
                endif;
                $xEnder = $this->removeAcentos($pessoaDestArray[0]['ENDERECO'].", ".$pessoaDestArray[0]['NUMERO']." - ".$pessoaDestArray[0]['COMPLEMENTO']." - ".$pessoaDestArray[0]['BAIRRO']);
                $xMun = $pessoaDestArray[0]['CIDADE'];
                $UF = $pessoaDestArray[0]['UF'];
                $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                break;
            case '2':
                if (is_array($transpArray)):
                    if ($transpArray[0]['PESSOA'] == "J"):
                        $CNPJ = str_pad($transpArray[0]['CNPJCPF'], 14, "0", STR_PAD_LEFT);
                        $CPF = '';
                    else:
                        $CNPJ = '';
                        $CPF = str_pad($transpArray[0]['CNPJCPF'], 11, "0", STR_PAD_LEFT);
                    endif;
                    $xNome = $transpArray[0]['NOME'];
                    if ($transpArray[0]['INSCESTRG'] != ""):
                        $IE = $transpArray[0]['INSCESTRG'];
                    endif;
                    $xEnder = $this->removeAcentos($transpArray[0]['ENDERECO'].", ".$transpArray[0]['NUMERO']." - ".$transpArray[0]['COMPLEMENTO']." - ".$transpArray[0]['BAIRRO']);
                    $xMun = $transpArray[0]['CIDADE'];
                    $UF = $transpArray[0]['UF'];
                    $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                endif;
                break;
        }
//        if ($modFrete!='9'){


            $qVol = $nfArray[0][VOLUME]; //Quantidade de volumes transportados
            $esp = $this->removeAcentos($nfArray[0][VOLESPECIE]); //Espécie dos volumes transportados
            $marca = $this->removeAcentos($nfArray[0][VOLMARCA]); //Marca dos volumes transportados
            $nVol = $nfArray[0][VOLUME]; //Numeração dos volume
            $pesoL = intval($nfArray[0][VOLPESOLIQ]); //Kg do tipo Int, mesmo que no manual diz que pode ter 3 digitos verificador...
            $pesoB = intval($nfArray[0][VOLPESOBRUTO]); //...se colocar Float não vai passar na expressão regular do Schema. =\
            $aLacres = '';
            $resp = $nfe->tagvol($qVol, $esp, $marca, $nVol, $pesoL, $pesoB, $aLacres);
            
//        }
        
        //transportadora
        //$CNPJ = '';
        //$CPF = '12345678901';
        //$xNome = 'Ze da Carroca';
        //$IE = '';
        //$xEnder = 'Beco Escuro';
        //$xMun = 'Campinas';
        //$UF = 'SP';
        //$resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);

        //valores retidos para transporte
        //$vServ = '258,69'; //Valor do Serviço
        //$vBCRet = '258,69'; //BC da Retenção do ICMS
        //$pICMSRet = '10,00'; //Alíquota da Retenção
        //$vICMSRet = '25,87'; //Valor do ICMS Retido
        //$CFOP = '5352';
        //$cMunFG = '3509502'; //Código do município de ocorrência do fato gerador do ICMS do transporte
        //$resp = $nfe->tagretTransp($vServ, $vBCRet, $pICMSRet, $vICMSRet, $CFOP, $cMunFG);

        //dados dos veiculos de transporte
        //$placa = 'AAA1212';
        //$UF = 'SP';
        //$RNTC = '12345678';
        //$resp = $nfe->tagveicTransp($placa, $UF, $RNTC);

        //dados dos reboques
        //$aReboque = array(
        //    array('ZZQ9999', 'SP', '', '', ''),
        //    array('QZQ2323', 'SP', '', '', '')
        //);
        //foreach ($aReboque as $reb) {
        //    $placa = $reb[0];
        //    $UF = $reb[1];
        //    $RNTC = $reb[2];
        //    $vagao = $reb[3];
        //    $balsa = $reb[4];
        //    //$resp = $nfe->tagreboque($placa, $UF, $RNTC, $vagao, $balsa);
        //}

        //Dados dos Volumes Transportados exemplo
/*        $aVol = array(
            array('4','Barris','','','120.000','120.000',''),
            array('2','Volume','','','10.000','10.000','')
        );
        foreach ($aVol as $vol) {
            $qVol = $vol[0]; //Quantidade de volumes transportados
            $esp = $vol[1]; //Espécie dos volumes transportados
            $marca = $vol[2]; //Marca dos volumes transportados
            $nVol = $vol[3]; //Numeração dos volume
            $pesoL = intval($vol[4]); //Kg do tipo Int, mesmo que no manual diz que pode ter 3 digitos verificador...
            $pesoB = intval($vol[5]); //...se colocar Float não vai passar na expressão regular do Schema. =\
            $aLacres = $vol[6];
            $resp = $nfe->tagvol($qVol, $esp, $marca, $nVol, $pesoL, $pesoB, $aLacres);
        }
*/
        //dados da fatura  ***********************  INCLUIR
/*        $nFat = '000035342';
        $vOrig = '1200.00';
        $vDesc = '';
        $vLiq = '1200.00';
        $resp = $nfe->tagfat($nFat, $vOrig, $vDesc, $vLiq);

        //dados das duplicatas (Pagamentos)
        $aDup = array(
            array('35342-1','2016-06-20','300.00'),
            array('35342-2','2016-07-20','300.00'),
            array('35342-3','2016-08-20','300.00'),
            array('35342-4','2016-09-20','300.00')
        );
        foreach ($aDup as $dup) {
            $nDup = $dup[0]; //Código da Duplicata
            $dVenc = $dup[1]; //Vencimento
            $vDup = $dup[2]; // Valor
            $resp = $nfe->tagdup($nDup, $dVenc, $vDup);
        }
*/

        //*************************************************************
        //Grupo obrigatório para a NFC-e. Não informar para a NF-e.
        //$tPag = '03'; //01=Dinheiro 02=Cheque 03=Cartão de Crédito 04=Cartão de Débito 05=Crédito Loja 10=Vale Alimentação 11=Vale Refeição 12=Vale Presente 13=Vale Combustível 99=Outros
        if ($nfArray[0]['MODELO'] == 65):
            $tPag = '01';
            $vPag = $vNF;
            $resp = $nfe->tagpag($tPag, $vPag);
        endif;

        //se a operação for com cartão de crédito essa informação é obrigatória
        //$CNPJ = '31551765000143'; //CNPJ da operadora de cartão
        //$tBand = '01'; //01=Visa 02=Mastercard 03=American Express 04=Sorocred 99=Outros
        //$cAut = 'AB254FC79001'; //número da autorização da tranzação
        //$resp = $nfe->tagcard($CNPJ, $tBand, $cAut);
        //**************************************************************

        // Calculo de carga tributária similar ao IBPT - Lei 12.741/12
        $federal = number_format($vII+$vIPI+$vIOF+$vPIS+$vCOFINS, 2, ',', '.');
        $estadual = number_format($vICMS+$vST, 2, ',', '.');
        $municipal = number_format($vISS, 2, ',', '.');
        $totalT = number_format($federal+$estadual+$municipal, 2, ',', '.');
        $textoIBPT = "Valor Aprox. Tributos R$ {$totalT} - {$federal} Federal, {$estadual} Estadual e {$municipal} Municipal.";

        //Informações Adicionais
        //$infAdFisco = "SAIDA COM SUSPENSAO DO IPI CONFORME ART 29 DA LEI 10.637";
        $infAdFisco = "";
        $infCpl = $nfArray[0]['OBS'].'<br>';
        //$infCpl .= $textoIBPT;
        $resp = $nfe->taginfAdic($infAdFisco, $infCpl);

        //observações emitente
        //$aObsC = array(
        //    array('email','roberto@x.com.br'),
        //    array('email','rodrigo@y.com.br'),
        //    array('email','rogerio@w.com.br'));
        //foreach ($aObsC as $obs) {
        //    $xCampo = $obs[0];
        //    $xTexto = $obs[1];
        //    $resp = $nfe->tagobsCont($xCampo, $xTexto);
        //}

        //observações fisco
        //$aObsF = array(
        //    array('email','roberto@x.com.br'),
        //    array('email','rodrigo@y.com.br'),
        //    array('email','rogerio@w.com.br'));
        //foreach ($aObsF as $obs) {
        //    $xCampo = $obs[0];
        //    $xTexto = $obs[1];
        //    //$resp = $nfe->tagobsFisco($xCampo, $xTexto);
        //}

        //Dados do processo
        //0=SEFAZ; 1=Justiça Federal; 2=Justiça Estadual; 3=Secex/RFB; 9=Outros
        //$aProcRef = array(
        //    array('nProc1','0'),
        //    array('nProc2','1'),
        //    array('nProc3','2'),
        //    array('nProc4','3'),
        //    array('nProc5','9')
        //);
        //foreach ($aProcRef as $proc) {
        //    $nProc = $proc[0];
        //    $indProc = $proc[1];
        //    //$resp = $nfe->tagprocRef($nProc, $indProc);
        //}

        //dados exportação
        //$UFSaidaPais = 'SP';
        //$xLocExporta = 'Maritimo';
        //$xLocDespacho = 'Porto Santos';
        //$resp = $nfe->tagexporta($UFSaidaPais, $xLocExporta, $xLocDespacho);

        //dados de compras
        //$xNEmp = '';
        //$xPed = '12345';
        //$xCont = 'A342212';
        //$resp = $nfe->tagcompra($xNEmp, $xPed, $xCont);

        //dados da colheita de cana
        //$safra = '2014';
        //$ref = '01/2014';
        //$resp = $nfe->tagcana($safra, $ref);
        //$aForDia = array(
        //    array('1', '100', '1400', '1000', '1400'),
        //    array('2', '100', '1400', '1000', '1400'),
        //    array('3', '100', '1400', '1000', '1400'),
        //    array('4', '100', '1400', '1000', '1400'),
        //    array('5', '100', '1400', '1000', '1400'),
        //    array('6', '100', '1400', '1000', '1400'),
        //    array('7', '100', '1400', '1000', '1400'),
        //    array('8', '100', '1400', '1000', '1400'),
        //    array('9', '100', '1400', '1000', '1400'),
        //    array('10', '100', '1400', '1000', '1400'),
        //    array('11', '100', '1400', '1000', '1400'),
        //    array('12', '100', '1400', '1000', '1400'),
        //    array('13', '100', '1400', '1000', '1400'),
        ///    array('14', '100', '1400', '1000', '1400')
        //);
        //foreach ($aForDia as $forDia) {
        //    $dia = $forDia[0];
        //    $qtde = $forDia[1];
        //    $qTotMes = $forDia[2];
        //    $qTotAnt = $forDia[3];
        //    $qTotGer = $forDia[4];
        //    //$resp = $nfe->tagforDia($dia, $qtde, $qTotMes, $qTotAnt, $qTotGer);
        //}
        
        
        
    function trataErro($codErro, $erroSefaz, $erroNf){
        $a = 0;
        switch ($codErro) {
            case '-2':        
                $msg = $erroSefaz.' - Nota não foi VALIDADA';
                break;
            case '-1':        
                $msg = $erroSefaz.' - Nota não foi ASSINADA !! ';
                break;
            case '204':        
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Duplicidade de NF-e, já autorizada anteriormente <br> ';
                break;
            case '108':        
                $msg = $erroSefaz.' - Serviço SEFAZ paralisado momentaneamente (curto prazo) <br> ';
                break;
            case '210':        
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> IE inválida, já autorizada anteriormente <br> ';
                break;
            case '232':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> IE do destinatário não informado <br> ';
                break;
            case '267':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Chave de Acesso referenciada inexistente <br> ';
                break;
            case '301':
                $msg = $erroSefaz.' - Uso Denegado: Irregularidade fiscal do emitente <br> ';
                break;
            case '321':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> NF-e de devolução de mercadoria não possui documento fiscal referenciado <br> ';
                break;
            case '328':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> CFOP de devolução de mercadoria para NFe que não tem finalidade de devolução de mercadoria <br> ';
                break;
            case '471':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Informado NCM=00 indevidamente <br> ';
                break;
            case '528':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Valor do ICMS difere do produto BC e Alíquota <br> ';
                break;
            case '531':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Total da BC ICMS difere do somatório dos itens <br> ';
                break;
            case '539':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Duplicidade de NF-e, com diferença na Chave de Acesso  <br> ';
                break;
            case '542':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> CNPJ do Transportador inválido  <br> ';
                break;
            case '544':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> IE do transportador inválida  <br> ';
                break;
            case '573':
                $msg = $erroSefaz.' - Duplicidade de Evento - emitido Evento para NF-e que já possui um Evento do mesmo Tipo e com o mesmo Número Sequencial  <br> ';
                break;
            case '591':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Informado CSOSN para emissor que não é do Simples Nacional (CRT diferente de 1)  <br> ';
                break;
            case '602':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Total do PIS difere do somatório dos itens sujeitos ao ICMS  <br> ';
                break;
            case '611':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> cEAN inválido <br> ';
                break;
            case '612':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> cEANTrib inválido <br> ';
                break;
            case '629':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Valor do Produto difere do produto Valor Unitário de Comercialização e Quantidade Comercial  <br> ';
                break;
            case '693':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Alíquota de ICMS superior a definida para a operação interestadual  <br> ';
                break;
            case '732':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> CFOP de operação interestadual e idDest diferente de 2 <br> ';
                break;
            case '770':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> CFOP Inexistente <br> ';
                break;
            case '777':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Obrigatória a informação do NCM completo  <br> ';
                break;
            case '778':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Informado NCM inexistente  <br> ';
                break;
            case '806':
                $msg = $erroSefaz.' - Nota não AUTORIZADA <br> Operação com ICMS-ST sem informação do CEST  <br> ';
            default :        
                $msg = "Código Mensagem: ".$codErro." - ".$erroSefaz.' - Nota não AUTORIZADA <br> ';
        }   



        $erroNf .= $msg." - ".$codErro;
        foreach ($erroSefaz as $erro) {
            if (is_array($erro)) { 
                foreach ($erro as $err) {
                    $erroNf .= "$err <br>";
                }
            } else {
                $erroNf .= "$erro <br>";
            }
        }
        //throw new Exception( $msg." - ". $erroNf );
        throw new Exception( $erroNf );
        exit;
        
    }   
    
    try {

        $nfeTools->setModelo($nfArray[0]['MODELO']);
        $tpAmb = ADMnfeAmbiente; // 1 - producao / 2 homologacao
        $anomes = date('Ym');
        if ($nfArray[0]['MODELO'] == 55):
            $nfExt = '-nfe.xml';
            $nfProt = '-protNFe.xml';
            $nfExtPdf = '-danfe.pdf';
        else:    
            $nfExt = '-nfe.xml';
            $nfProt = '-protNFe.xml';
            $nfExtPdf = '-danfce.pdf';
        endif;
        
        // monta dir files
        $path = BASE_DIR_NFE_AMB;
        $slash = '/'; 
        (stristr( $path, $slash )) ? '' : $slash = '\\'; 
        define( 'BASE_DIR_ENTRADA', $path.$slash.'entradas'.$slash.$anomes.$slash.$chave.$nfExt); 
        define( 'BASE_DIR_ASSINADA', $path.$slash.'assinadas'.$slash.$anomes.$slash.$chave.$nfExt); 
        define( 'BASE_DIR_ENVIADA_APROVADAS', $path.$slash.'enviadas'.$slash.'aprovadas'.$slash.$anomes.$slash.$chave.$nfProt); 
        define( 'BASE_DIR_TEMP', $path.$slash.'temporarias'.$slash.$anomes.$slash); 
        define( 'BASE_DIR_PDF', $path.$slash.'pdf'.$slash.$anomes.$slash.$chave.$nfExtPdf); 
        define( 'BASE_HTTP_PDF', ADMhttpCliente.$slash.'nfe'.$slash.$this->m_empresaid.$slash.ADMambDesc.$slash.'pdf'.$slash.$anomes.$slash.$chave.$nfExtPdf); 
//$xmlProt = "D:/xampp/htdocs/GIT-nfephp-org/nfephp/xmls/NF-e/homologacao/enviadas/aprovadas/201605/{$chave}-protNFe.xml";

        //monta a NFe e retorna na tela
        //===============================================
        /* GRAVA SAIDA - grava xml
        function zGravaFile(
                $tipo = '',
                $tpAmb = '2',
                $filename = '',
                $data = '',
                $subFolder = 'temporarias',
                $anomes = ''
        ===============================================
        */
        // entradas/{$chave}-nfe.xml
        $erroNf = "";
        $resp = $nfe->montaNFe();
        if ($resp) {
            $xml = $nfe->getXML();
             $nfeTools->zGravaFile('nfe', $tpAmb, $chave.$nfExt, $xml, 'entradas');
        } else {
            foreach ($nfe->erros as $err) {
                $erroNf .= 'tag: &lt;'.$err['tag'].'&gt; ---- '.$err['desc'].'<br>';
            }
            throw new Exception( "Erro na montagem XML". $erroNf );
            exit;
        }

        //===============================================
        // ASSINA E GRAVA
        // se modelo 65 inclui qrcode
        //===============================================
        // assina nf ==============
        // /assinadas/{$chave}-nfe.xml
        $xmlAssinada = $nfeTools->assina($xml, true);
        if (sizeof($nfeTools->errors)) {
            trataErro('-1', $nfeTools->errors, $erroNf);
        }    
        $erroNf = $chave."<br>";
        

        //===============================================
        // valida nf ==============
        //===============================================
        if (! $nfeTools->validarXml($xmlAssinada) || sizeof($nfeTools->errors)) {
            trataErro('-2', $nfeTools->errors, $erroNf);
        }    
            
        //===============================================
        // ENVIA NF 
        // Solicita a autorização de uso de Lote de NFe
        // GRAVA pasta temporarias 
        // $idLote-enviNFe.xml";
        // $idLote-retEnviNFe.xml";
        //===============================================
        $aResposta = array();
        $idLote = '';
        $indSinc = '1';
        $flagZip = false;
        $retorno = $nfeTools->sefazEnviaLote($xmlAssinada, $tpAmb, $idLote, $aResposta, $indSinc, $flagZip);
        //caso o envio seja recebido com sucesso mover a NFe da pasta
        //das assinadas para a pasta das enviadas
        
        switch ($aResposta['cStat']) {
            case '100':
                $aResposta['cStatus'] = $aResposta['cStat'];
                break;
            case '104':
                if ($aResposta['prot'][0]['cStat'] != '100'):
                    trataErro($aResposta['prot'][0]['cStat'], $nfeTools->errors,  $erroNf);
                endif;
                $aResposta['cStatus'] = $aResposta['prot'][0]['cStat'];
                break;
            default :        
                //trataErro($aResposta['cStat'], $nfeTools->errors, $erroNf);
                trataErro($aResposta['cStat'], $aResposta['xMotivo'], $erroNf);
        }   
        

        // add protocolo e grava APROVADAS
        $saveFile = true;
        $pathNFefile = BASE_DIR_ASSINADA;
        $pathProtfile = BASE_DIR_TEMP.$idLote."-retEnviNFe.xml";
        $docProt = $nfeTools->addProtocolo($pathNFefile, $pathProtfile, $saveFile);
        //echo $docProt;
        // DANFE GRAVA PDF E IMPRIME
        $pathLogo = ADMimg.'/logo.png';
        $pdfDanfe = BASE_DIR_PDF;
  
        if ($nfArray[0]['MODELO'] == 55):
            //ob_start();
            //$docxml = FilesFolders::readFile($xmlProt);
            $danfe = new Danfe($docProt, 'P', 'A4', $pathLogo, 'I', '');
            $id = $danfe->montaDANFE();
            $salva = $danfe->printDANFE($pdfDanfe, 'F'); //Salva o PDF na pasta
            //ob_end_clean();
            //header("Location:/{$pdfDanfe}", true);
            //$abre = $danfe->printDANFE("{$id}-danfe.pdf", 'I'); //Abre o PDF no Navegador
        else:
            $danfce = new Danfce($xmlAssinada, $pathLogo, 2);

            $ecoNFCe = false; //false = Não (NFC-e Completa); true = Sim (NFC-e Simplificada)
            $id = $danfce->montaDANFCE($ecoNFCe);

    //        $nfeTools->zGravaFile('nfe', $tpAmb, '', '', 'pdf');
            $salva = $danfce->printDANFCE('pdf', $pdfDanfe, 'F'); //Salva na pasta pdf
        endif;

        //$aResposta['cDanfe'] = BASE_HTTP_PDF;
        $aResposta['cDanfe'] = BASE_HTTP_PDF;
        
        // altera dados danfe na est_nota_fiscal
        if ($aResposta['cStatus'] == '100'):
            $nfOBJ->setPathDanfe($aResposta['cDanfe']);
            $nfOBJ->setChNFe($aResposta['prot'][0]['chNFe']);
            $nfOBJ->setDhRecbto($aResposta['prot'][0]['dhRecbto']);
            $nfOBJ->setNProt($aResposta['prot'][0]['nProt']);
            $nfOBJ->setDigVal($aResposta['prot'][0]['digVal']);
            $nfOBJ->setVerAplic($aResposta['verAplic']);
            $nfOBJ->alteraNfPath($conn);
            
            // envia email
            if ($nfArray[0]['MODELO'] == 55):
                $this->enviaEmailDANFE($nfArray[0]['MODELO'], $pessoaDestArray[0]['EMAILNFE'], 
                            $pessoaDestArray[0]['EMAIL'],$aResposta['prot'][0]['chNFe'], $dhEmi,$cNF,$serie,$xNome);
            endif;
        endif;
        
        return $aResposta;
        
        //$saida = 'pdf'; // pdf ou html
        //$abre = $danfce->printDANFCE($saida, $pdfDanfe, 'I'); //Abre na tela
        
    } catch (Exception $e) {
       throw new Exception($e->getMessage() );
      }
        
    } //geraXML
    
} //class
$xml = new p_exporta_xml();

?>