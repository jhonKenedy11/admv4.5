<?php

/**
 * @package   adm
 * @name      p_espelho_nfe
 * @version   4.3.2
 * @copyright 2019
 * @link      http://www.admservice.com.br/
 * @author    Jhon KS Mello<jhon.kened11@gmail.com>
 * @date      26/07/2023
 */
$dir = (__DIR__);

//error_reporting(E_ALL);
ini_set('display_errors', 'Off');
require_once $dir . '/../../../sped/vendor/autoload.php';


include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_mail.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_nota_fiscal.php");
include_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
require_once($dir . "/../../class/est/c_nat_operacao.php");

class p_espelho_nfe extends c_user{
  
    private $m_submenu = NULL;
   
    public function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);          

        $this->m_submenu = $parmPost['submenu'];

        // Cria uma instancia variaveis de sessao
        $this->from_array($_SESSION['user_array']);
        
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
            return $aux[0]."T".$aux[1]."-02:00"; // horario de verão 
            //return $aux[0]."T".$aux[1]."-03:00";
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
     * <b> Funcao para remover os acentos da importacao. </b>
     * @name removeAcentos
     * @param STRING $string
     * @param BOOLEAN $slug FALSE
     * @return STRING
     */
    function removeChar($string, $slug = false) {
        $conversao = array('.' => '', '/' => '', '-' => '');
        return strtr($string, $conversao);
    }
    
    /**
     * Funcao para contruir a DANFE PDF a partir dos xml assinada e protocolo
     * @param VARCHAR $chave nfe
     */
    public function gera_DANFE($xml, $chave, $idNf=null) {
        try {
            $path = BASE_DIR_NFE_AMB;
            $slash = '/'; 
            $anomes = date('Ym');
            $nfExtPdf = '-espelho.pdf';
            $pathLogo = ADMimg . '/logo.jpg';

            if (!file_exists($path . $slash . 'pdf_temp' . $slash . $anomes . $slash)) {
                mkdir($path . $slash . 'pdf_temp' . $slash . $anomes . $slash, 0777, true);
            } 

            // monta dir files
            (stristr( $path, $slash )) ? '' : $slash = '\\'; 
            define( 'BASE_DIR_PDF', $path.$slash. 'pdf_temp'.$slash.$anomes.$slash.$chave.$nfExtPdf);
            define('BASE_HTTP_PDF', ADMhttpCliente . $slash . 'nfe' . $slash . $this->m_empresaid . $slash . ADMambDesc . $slash . 'pdf_temp' . $slash . $anomes . $slash . $chave . $nfExtPdf); 

            $danfe = new NFePHP\DA\NFe\Danfe($xml, 'P', 'A4', $pathLogo, 'I', '');
            $danfe->montaDANFE();
            $res = $danfe->printDocument(BASE_DIR_PDF, 'F'); //Salva o PDF na pasta

            if($res == ''){

                $sql = "UPDATE est_nota_fiscal SET ";
                $sql .= "pathdanfe = '". BASE_HTTP_PDF."', ";
                $sql .= "userchange = '" . $this->m_userid . "', ";
                $sql .= "datechange = current_timestamp() ";
                $sql .= "WHERE id = " . $idNf . ";";
                $banco = new c_banco;
                $banco->exec_sql($sql);
                $banco->close_connection();
            }else{
                throw new Exception($e->getMessage() );
            }

            return BASE_HTTP_PDF;
        } catch (Exception $e) {
            return "Danfe NÃO gerada NFe número - ";
           //throw new Exception($e->getMessage() );
        }
    }
    
    public function gera_XML($idNf, $filial, $tipoNf, $conn=null, $gerarXML=null){
        $dir = (__DIR__);

        $nfe = new NFePHP\NFe\Make();

        
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
        $transpArray = $transpOBJ->select_conta();

        // DADOS NF PRODUTO
        $nfProdutoOBJ = new c_nota_fiscal_produto();
        $nfProdutoOBJ->setIdNf($idNf);
        $produtoArray = $nfProdutoOBJ->select_nota_fiscal_produto_nf($conn);

        // DADOS FINANCEIRO
        $lancamento = new c_lancamento();
        $financeiro = $lancamento->select_lancamento_doc('PED', $nfArray[0]['DOC'], $conn) ?? [];

        // Reordene o array usando a função usort() e a função de comparação
        usort($financeiro, array ("p_nfe_40", "compararValores"));

         $financeiroAgrupado = $lancamento->select_lancamento_doc_tipodocto('PED', $nfArray[0]['DOC'], $conn);
        
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
            //NEW CONDITION FOR SALE WITHIN THE STATE
            if($nfArray[0]['VENDAPRESENCIAL'] == 'S'){
                $indFinal = 1; // normal
                $indIEDest = 1;
            }else{
                $indFinal = 0; // normal
                $indIEDest = 1;
            }
        else:
            $indFinal = 1; // consumidor final
            if (($tipoPessoa == "F") or (strlen($ie)<=0) or ($ie=='ISENTO')):
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
        $cNF = rand(1,99999999);
        //$cNF = str_pad($nfArray[0]['NUMERO'], 8, "0",STR_PAD_LEFT); //'00000010'; //numero aleatório da NF
        $natOp = $this->removeAcentos($nfArray[0]['NATOPERACAO']); //'Venda de Produto'; //natureza da operação
        $indPag = $nfArray[0]['FORMAPGTO']; //0=Pagamento à vista; 1=Pagamento a prazo; 2=Outros
        $mod = $nfArray[0]['MODELO']; //modelo da NFe 55 ou 65 essa última NFCe
        $serie = $nfArray[0]['SERIE']; //serie da NFe
        $nNF = $nfArray[0]['NUMERO']; // numero da NFe
        $dhEmi = $this->MostraData($nfArray[0]['EMISSAO']); //date("Y-m-d\TH:i:sP");//Formato: “AAAA-MM-DDThh:mm:ssTZD” (UTC - Universal Coordinated Time).
        $tpNF = $nfArray[0]['TIPO']; // 0=Entrada; 1=Saída;
        //new validation referent the sale within the state
        if($nfArray[0]['VENDAPRESENCIAL'] == 'S'){
            $idDest = '1';
        }else{
            if($filialArray[0]['UF'] == $pessoaDestArray[0]['UF']){
                $idDest = '1'; //1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
            }else{
                $idDest = '2'; //1=Operação interna; 2=Operação interestadual; 3=Operação com exterior.
            }
        }
        $cMunFG = $cMunEmit;
        // $cMunFG = '';
        if ($nfArray[0]['MODELO'] == 55):
            $tpImp = '1';
            if (!empty($gerarXML)) {  
              $dhSaiEnt = $this->MostraData($nfArray[0]['DATASAIDAENTRADA']);//Não informar este campo para a NFC-e.                  
            } else {
              $dhSaiEnt = date("Y-m-d\TH:i:sP");//Não informar este campo para a NFC-e.
            }
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
        
        // IndIntermed = 0 - sem intermediador 1 - com intermediador ( cnpj e id do usuário de quem vendeu ( ex: mercado livre ))
        $indIntermed = 0;

        //0=Não se aplica (por exemplo, Nota Fiscal complementar ou de ajuste);
        //1=Operação presencial;
        //2=Operação não presencial, pela Internet;
        //3=Operação não presencial, Teleatendimento;
        //4=NFC-e em operação com entrega a domicílio;
        //9=Operação não presencial, outros.

        //NEW VALIDATION FOR SALE WITHIN THE STATE
        if($nfArray[0]['VENDAPRESENCIAL'] == 'S'){
            $indPres = '1';
        }else{
            $indPres = '0';
        }
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
        $cnpj = $filialArray[0]['CNPJ'];
        if(($nfArray[0]["SITUACAO"] == 'A') or ($nfArray[0]["SITUACAO"] == 'P')){
            $chave =  NFePHP\Common\Keys::build($cUF, $ano, $mes, $cnpj, $mod, $serie, $nNF, $tpEmis, $cNF);}
        else{
            $chave =  $nfArray[0]["CHNFE"];
        }
        //nfe40  $chave = $nfe->montaChave($cUF, $ano, $mes, $cnpj, $mod, $serie, $nNF, $tpEmis, $cNF);
        //nfe40  $versao = '3.10';
        //nfe40  $resp = $nfe->taginfNFe($chave, $versao);
        $std = new \stdClass();
        $std->versao = '4.00';
        $std->Id = $chave;
        $elem = $nfe->taginfNFe($std);        

        $cDV = substr($chave, -1); //Digito Verificador da Chave de Acesso da NF-e, o DV é calculado com a aplicação do algoritmo módulo 11 (base 2,9) da Chave de Acesso.

        //tag IDE
        //nfe40 $resp = $nfe->tagide($cUF, $cNF, $natOp, $indPag, $mod, $serie, $nNF, $dhEmi, $dhSaiEnt, $tpNF, $idDest, $cMunFG, $tpImp, $tpEmis, $cDV, $tpAmb, $finNFe, $indFinal, $indPres, $procEmi, $verProc, $dhCont, $xJust);
        $std = new \stdClass();
        $std->cUF = $cUF;
        $std->cNF = $cNF;
        $std->natOp = $natOp;
        $std->indPag = $indPag; //NÃO EXISTE MAIS NA VERSÃO 4.00
        $std->mod = $mod;
        $std->serie = $serie;
        $std->nNF = $nNF;
        $std->dhEmi = $dhEmi;
        $std->dhSaiEnt = $dhSaiEnt;
        $std->tpNF = $tpNF;
        $std->idDest = $idDest;
        $std->cMunFG = $cMunFG;
        $std->tpImp = $tpImp;
        $std->tpEmis = $tpEmis;
        $std->cDV = $cDV;
        $std->tpAmb = $tpAmb; // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
        $std->finNFe = $finNFe;
        $std->indFinal = $indFinal;
        $std->indPres = $indPres;
        $std->indIntermed = $indIntermed;
        $std->procEmi = $procEmi;
        $std->verProc = $verProc;
        $elem = $nfe->tagide($std);

        //refNFe NFe referenciada  -- verificar
        if (($finNFe == 2) or ($finNFe == 4)):
            $std = new stdClass();
            $std->refNFe = $nfArray[0]['NFEREFERENCIADA'];
            $elem = $nfe->tagrefNFe($std);
        endif;

        //Dados do emitente - (Importando dados do config.json)
        $CNPJ = $filialArray[0]['CNPJ'];
        $CPF = ''; // Utilizado para CPF na nota
        $xNome = $this->removeAcentos($filialArray[0]['NOMEEMPRESA']);
        $xFant = $filialArray[0]['NOMEFANTASIA'];
        $IE = $filialArray[0]['INSCESTADUAL'];
        $IEST = '';
        $IM = $filialArray[0]['INSCMUNICIPAL'];
        $CNAE = '';
        $CRT = $filialArray[0]['REGIMETRIBUTARIO'];
        //nfe40  $resp = $nfe->tagemit($CNPJ, $CPF, $xNome, $xFant, $IE, $IEST, $IM, $CNAE, $CRT);
        
        $std = new stdClass();
        $std->xNome = $xNome;
        $std->xFant = $xFant;
        $std->IE = $IE;
        $std->IEST = $IEST;
        $std->IM = $IM;
        $std->CNAE = $CNAE;
        $std->CRT = $CRT;
        $std->CNPJ = $CNPJ; //indicar apenas um CNPJ ou CPF
        $std->CPF = $CPF;
        
        $elem = $nfe->tagemit($std);

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
        if (strlen($CEP) == 7){
            $CEP = '0'.$CEP;
        }
        $cPais = '1058';
        $xPais = 'Brasil';
        $fone = $filialArray[0]['FONEAREA'].$filialArray[0]['FONENUM'];
        //nfe40 $resp = $nfe->tagenderEmit($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
        $std = new \stdClass();
        $std->xLgr = $xLgr;
        $std->nro = $nro;
        $std->xBairro = $xBairro;
        $std->cMun = $cMun;
        $std->xMun = $xMun;
        $std->UF = $UF;
        $std->CEP = $CEP;
        if (strlen($CEP) == 7){
            $CEP = '0'.$CEP;
        }
        $std->cPais = $cPais;
        $std->xPais = $xPais;
        $std->fone = $fone;
        $elem = $nfe->tagenderEmit($std);        

        //destinatário
        if ($nfArray[0]['MODELO'] == 55):
            if ($tipoPessoa == "J"):
                $CNPJ = $pessoaDestArray[0]['CNPJCPF'];
                $CPF = '';
            else:    
                $CNPJ = '';
                $CPF = $this->removeChar($pessoaDestArray[0]['CNPJCPF']);
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
            //nfe40            $resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);
            $std = new \stdClass();
            $std->xNome = $xNome;
            $std->indIEDest = $indIEDest;
            $std->IE = $IE;
            $std->CNPJ = $CNPJ;
            $std->CPF = $CPF;
            $std->IM = $IM;
            $std->ISUF = $ISUF;
            $std->email = $email;
            $elem = $nfe->tagdest($std);
            
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
            if (strlen($CEP) == 7){
                $CEP = '0'.$CEP;
            }
            $cPais = '1058';
            $xPais = 'Brasil';
            $fone = preg_replace("/[^0-9]/", "", $pessoaDestArray[0]['FONE']);
            //nfe40            $resp = $nfe->tagenderDest($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
            $std = new \stdClass();
            $std->xLgr = $xLgr;
            $std->nro = $nro;
            $std->xBairro = $xBairro;
            $std->cMun = $cMun;
            $std->xMun = $xMun;
            $std->UF = $UF;
            $std->CEP = $CEP;
            $std->fone = $fone;
            $std->cPais = '1058';
            $std->xPais = 'BRASIL';
            $elem = $nfe->tagenderDest($std);

        else:
            //destinatário
            if ($nfArray[0]['CPFNOTA'] != ''):
                if (strlen($nfArray[0]['CPFNOTA']) >11):
                    //$CNPJ = '22886247000190';
                    $CNPJ = $nfArray[0]['CPFNOTA'];
                    $CPF = '';
                else:    
                    $CNPJ = '';
                    $CPF = $this->removeChar($nfArray[0]['CPFNOTA']);
                endif;
                
                $idEstrangeiro = '';
                $xNome = '';
                $indIEDest = '9';
                $IE = '';
                $ISUF = '';
                $IM = '';
                $email = '';
                //nfe40                $resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);
                $std = new \stdClass();
                $std->xNome = $xNome;
                $std->indIEDest = $indIEDest;
                $std->IE = $IE;
                $std->CNPJ = $CNPJ;
                $std->CPF = $CPF;
                $std->IM = $IM;
                $std->ISUF = $ISUF;
                $std->email = $email;
                $elem = $nfe->tagdest($std);
            endif;
        endif;

        for ($i = 0; $i<count($produtoArray); $i++){
            $nItem = $i+1;
            $cProd = $produtoArray[$i]['CODIGONOTA'];
            $cProd = trim($cProd);
            if ($cProd == "") {
                $cProd = $produtoArray[$i]['CODPRODUTO'];
            }
            $prefixo = substr($produtoArray[$i]['CODIGOBARRAS'], 0, 3);
            if ((strlen($produtoArray[$i]['CODIGOBARRAS'])>0) and ($prefixo != '047')):
                // COMPLETA COM ZEROS PARA OS TAMANHOS 8, 12, 13, 14
                $cEAN = $produtoArray[$i]['CODIGOBARRAS'];
                $cEANTrib = $produtoArray[$i]['CODIGOBARRAS'];
            else:
                $cEAN = 'SEM GTIN';
                $cEANTrib = 'SEM GTIN';
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
            $cBenef = $produtoArray[$i]['CBENEF'];
            if ($cBenef == '0'){
                $cBenef = '';
            }
            if ($produtoArray[$i]['FRETE'] > 0 ) {
                $vFrete = number_format($produtoArray[$i]['FRETE'], 2, '.', '');   
            } else {
                $vFrete='';
            }

            $vSeg = '';
            $vDesc = number_format($produtoArray[$i]['DESCONTO'], 2, '.', '');
            $vDescTotal += $vDesc;
            if ($produtoArray[$i]['DESPACESSORIAS'] > 0) {
                $vOutro = number_format($produtoArray[$i]['DESPACESSORIAS'], 2, '.', '');
                $vOutroTotal += $vOutro;
            }
            $indTot = '1';
            $xPed = $produtoArray[$i]['ORDEM'];
            $nItemPed = '';
            $nFCI = '';
            //nfe40 $resp = $nfe->tagprod($nItem, $cProd, $cEAN, $xProd, $NCM, $EXTIPI, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $nFCI);
            
            $std = new stdClass();
            $std->item = $nItem; //item da NFe
            $std->cProd = $cProd;
            $std->cEAN = $cEAN;
            $std->xProd = $xProd;
            $std->NCM =$NCM;

            $std->cBenef = $cBenef; //incluido no layout 4.00

            $std->EXTIPI = $EXTIPI;
            $std->CFOP = $CFOP;
            $std->uCom = $uCom;
            $std->qCom = $qCom;
            $std->vUnCom = $vUnCom;
            $std->vProd = $vProd;
            $std->cEANTrib = $cEANTrib;
            $std->uTrib = $uTrib;
            $std->qTrib = $qTrib;
            $std->vUnTrib = $vUnTrib;
            $std->vFrete = $vFrete;
            $std->vSeg = $vSeg;

            if ($vDesc!='0.00'):
                $std->vDesc = $vDesc;
            endif;

            $std->vOutro = $vOutro;
            $std->indTot= $indTot;
            $std->xPed = $xPed;
            $std->nItemPed = $nItemPed;
            $std->nFCI = $nFCI;
            $elem = $nfe->tagprod($std);            
            
            if (!$produtoArray[$i]['CEST']==''):
                $std = new stdClass();
                $std->item = $nItem; //item da NFe
                $std->CEST = $produtoArray[$i]['CEST'];
                $std->indEscala = 'S'; //incluido no layout 4.00
                //$std->CNPJFab = '12345678901234'; //incluido no layout 4.00
                $elem = $nfe->tagCEST($std);            
            endif;

            //$elem = $nfe->tagRastro($std);            
            if (!$produtoArray[$i]['LOTE']==''):
                $nLote = $produtoArray[$i]['LOTE'];
                $qLote = number_format($produtoArray[$i]['QUANT'], 3, '.', '');
                $dFab = $produtoArray[$i]['DATAFABRICACAO'];
                $dVal = $produtoArray[$i]['DATAVALIDADE'];
                $std = new stdClass();
                $std->item = $nItem; //item da NFe
                $std->nLote = $nLote;
                $std->qLote = $qLote;
                $std->dFab = $dFab;
                $std->dVal = $dVal;
                
                $std->cAgreg = '1234';
                $elem = $nfe->tagRastro($std);
            endif;
            
            //impostos ============================================
            switch ($crt):
                case '1': //ICMSSN - Tributação ICMS pelo Simples Nacional - CRT (Código de Regime Tributário) = 1 
                $orig = $produtoArray[$i]['ORIGEM'];
                $csosn = 0;
                $modBC  = 0;
                $vBC = 0;
                $pRedBC = 0;
                $pICMS = 0;
                $vICMS = 0;
                $pCredSN = 0;
                $vCredICMSSN = 0;
                $modBCST = 0;
                $pMVAST = 0;
                $pRedBCST = 0;
                $vBCST = 0;
                $pICMSST = 0;
                $vICMSST = 0;
                $vBCSTRet = 0;
                $vICMSSTRet = 0;
                $pCredSN = 0;
                $vCredICMSSN = 0;

                switch ($produtoArray[$i]['TRIBICMS']){
                    case '101': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        $csosn = '101'; //101=Tributada pelo Simples Nacional com permissão de crédito. (v2.0)
                        // $modBC = $produtoArray[$i]['MODBC'];
                        // $pMVAST = $produtoArray[$i]['PERCMVAST']; //Percentual da margem de valor Adicionado do ICMS ST
                        // $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST']; //Percentual da Redução de BC do ICMS ST
                        // $vBCST = $produtoArray[$i]['VALORBCST']; //Valor da BC do ICMS ST
                        // $pICMSST = $produtoArray[$i]['ALIQICMSST']; //Alíquota do imposto do ICMS ST
                        // $vICMSST = $produtoArray[$i]['VALORICMSST']; //Valor do ICMS ST
                        $pCredSN = $produtoArray[$i]['PCREDSN'];//Alíquota aplicável de cálculo do crédito (SIMPLES NACIONAL). 
                        $vCredICMSSN = $produtoArray[$i]['VCREDICMSSN']; //Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (SIMPLES NACIONAL)

                        // $modBC = $produtoArray[$i]['MODBC'];
                        // $vBC = $produtoArray[$i]['BCICMS'];
                        // $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                        // $pICMS = $produtoArray[$i]['ALIQICMS']; 
                        // $vICMS = $produtoArray[$i]['VALORICMS'];
                                                
                        // $vICMS = number_format(($vBC * ($pICMS/100)), 2, '.', ''); 
                        // $produtoArray[$i]['VALORICMS'] = $vICMS;


                        // $modBC = $produtoArray[$i]['MODBC'];
                        // $vBC = $produtoArray[$i]['BCICMS'];
                        // $pICMS = $produtoArray[$i]['ALIQICMS']; 
                        // $vICMS = $produtoArray[$i]['VALORICMS'];

                        // $pCredSN = $produtoArray[$i]['ALIQICMS'];//Alíquota aplicável de cálculo do crédito (SIMPLES NACIONAL). 
                        // $vCredICMSSN = $produtoArray[$i]['VALORICMS']; //Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (SIMPLES NACIONAL)
                        break;
                    case '102': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        $csosn = '102';  
                        break;
                    case '103': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        $csosn = '103';  
                        break;
                    case '300': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        $csosn = '300';  
                        break;
                    case '400': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        $csosn = '400';  
                        break;
                    case '201': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        $csosn = '201'; //201=Tributada pelo Simples Nacional com permissão de crédito e com cobrança do ICMS por Substituição Tributária (v2.0) 
                        //0=Preço tabelado ou máximo sugerido;
                        //1=Lista Negativa (valor);
                        //2=Lista Positiva (valor);
                        //3=Lista Neutra (valor);
                        //4=Margem Valor Agregado (%);
                        //5=Pauta (valor); (v2.0)
                        // $modBC = $produtoArray[$i]['MODBC'];
                        // $pMVAST = $produtoArray[$i]['PERCMVAST']; //Percentual da margem de valor Adicionado do ICMS ST
                        // $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST']; //Percentual da Redução de BC do ICMS ST
                        // $vBCST = $produtoArray[$i]['VALORBCST']; //Valor da BC do ICMS ST
                        // $pICMSST = $produtoArray[$i]['ALIQICMSST']; //Alíquota do imposto do ICMS ST
                        // $vICMSST = $produtoArray[$i]['VALORICMSST']; //Valor do ICMS ST
                        $pCredSN = $produtoArray[$i]['PCREDSN'];//Alíquota aplicável de cálculo do crédito (SIMPLES NACIONAL). 
                        $vCredICMSSN = $produtoArray[$i]['VCREDICMSSN']; //Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (SIMPLES NACIONAL)
                        break;                        
                    case '202': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        //202=Tributada pelo Simples Nacional sem permissão de crédito e com cobrança do ICMS por Substituição Tributária;
                        $csosn = '202'; 
                        //0=Preço tabelado ou máximo sugerido;
                        //1=Lista Negativa (valor);
                        //2=Lista Positiva (valor);
                        //3=Lista Neutra (valor);
                        //4=Margem Valor Agregado (%);
                        //5=Pauta (valor); (v2.0)
                        $modBCST = $produtoArray[$i]['MODBCST'];
                        $modBC = $produtoArray[$i]['MODBC'];
                        $pMVAST = $produtoArray[$i]['PERCMVAST']; //Percentual da margem de valor Adicionado do ICMS ST
                        $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST']; //Percentual da Redução de BC do ICMS ST
                        $vBCST = $produtoArray[$i]['VALORBCST']; //Valor da BC do ICMS ST
                        $pICMSST = $produtoArray[$i]['ALIQICMSST']; //Alíquota do imposto do ICMS ST
                        $vICMSST = $produtoArray[$i]['VALORICMSST']; //Valor do ICMS ST
                        break;
                    case '203': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        //203- Isenção do ICMS nos Simples Nacional para faixa de receita bruta e com cobrança do ICMS por Substituição Tributária (v2.0)
                        $csosn = '203'; 
                        //0=Preço tabelado ou máximo sugerido;
                        //1=Lista Negativa (valor);
                        //2=Lista Positiva (valor);
                        //3=Lista Neutra (valor);
                        //4=Margem Valor Agregado (%);
                        //5=Pauta (valor); (v2.0)
                        $modBC = $produtoArray[$i]['MODBC'];
                        $pMVAST = $produtoArray[$i]['PERCMVAST']; //Percentual da margem de valor Adicionado do ICMS ST
                        $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST']; //Percentual da Redução de BC do ICMS ST
                        $vBCST = $produtoArray[$i]['VALORBCST']; //Valor da BC do ICMS ST
                        $pICMSST = $produtoArray[$i]['ALIQICMSST']; //Alíquota do imposto do ICMS ST
                        $vICMSST = $produtoArray[$i]['VALORICMSST']; //Valor do ICMS ST
                        break;
                    case '500': 
                        $orig = $produtoArray[$i]['ORIGEM'];
                        //500=ICMS cobrado anteriormente por substituição tributária(substituído) ou por antecipação. (v2.0)
                        $csosn = '500';
                        //Valor da BC do ICMS ST cobrado anteriormente por ST (v2.0).
                        $pST = is_null($produtoArray[$i]['ALIQICMSST']) ? '0.00' : $produtoArray[$i]['ALIQICMSST'];
                        //O valor pode ser omitido quando a legislação não exigir a sua informação. (NT 2011/004) 
                        $vBCSTRet = is_null($produtoArray[$i]['VALORBCSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORBCSTRETIDO'];
                        //Valor do ICMS ST cobrado anteriormente por ST (v2.0). O valor pode ser omitido quando a legislação não exigir a sua informação. (NT 2011/004)
                        $vICMSSTRet = is_null($produtoArray[$i]['VALORICMSSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORICMSSTRETIDO'];
                        break;
                    case '900': // Tributação ICMS: Outros
                        if (($nfArray[0]['FINALIDADEEMISSAO'] == 10) and ($crt == '1')){
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $csosn = '900'; //900=Outros (v2.0)
                        } else {
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $csosn = '900'; //900=Outros (v2.0)
                        
                        
                        //0=Margem Valor Agregado (%);
                        //1=Pauta (Valor);
                        //2=Preço Tabelado Máx. (valor);
                        //3=Valor da operação. (v2.0)
                        $modBC = $produtoArray[$i]['MODBC'];
                        $vBC = $produtoArray[$i]['BCICMS'];
                        $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                        $pICMS = $produtoArray[$i]['ALIQICMS']; 
                        $vICMS = $produtoArray[$i]['VALORICMS'];
                                                
                        //$vICMS = number_format(($vBC * ($pICMS/100)), 2, '.', ''); 
                        $produtoArray[$i]['VALORICMS'] = $vICMS;
                            

                        //0=Preço tabelado ou máximo sugerido;
                        //1=Lista Negativa (valor);
                        //2=Lista Positiva (valor);
                        //3=Lista Neutra (valor);
                        //4=Margem Valor Agregado (%);
                        //5=Pauta (valor); (v2.0)

                        if (($produtoArray[$i]['VALORICMSST'] >0) and ($produtoArray[$i]['MODBCST'] !='')){
                            $modBCST = $produtoArray[$i]['MODBCST'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            // $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; 

                            $pCredSN = $produtoArray[$i]['PCREDSN']; 
                            $vCredICMSSN = $produtoArray[$i]['VCREDICMSSN'];
                        }
                    }
                    break;                
                }
                
                $std = new stdClass();
                $std->item = $nItem; 
                $std->orig = $orig;

                if ($orig != '')  {$std->orig = $orig;}                
                if ($csosn != '') {$std->CSOSN = $csosn;}                
                if ($modBC != '') {$std->modBC = $modBC;}                
                if ($vBC != '')   {$std->vBC = $vBC;}
                if ($pRedBC != ''){$std->pRedBC = $pRedBC;}
                if ($pICMS != '') {$std->pICMS = $pICMS;}
                if ($vICMS != '') {$std->vICMS = $vICMS;}
                if ($modBCST != '') {$std->modBCST = $modBCST;}
                if ($pMVAST != '') {$std->pMVAST = $pMVAST;}
                if ($pRedBCST != '') {$std->pRedBCST = $pRedBCST;}
                if ($vBCST != '') {$std->vBCST = $vBCST;}
                if ($pICMSST != '') {$std->pICMSST = $pICMSST;}
                if ($vICMSST != '') {$std->vICMSST = $vICMSST;}
                if ($pCredSN != '') {$std->pCredSN = $pCredSN;}
                if ($vCredICMSSN != '') {$std->vCredICMSSN = $vCredICMSSN;}

                
                $elem = $nfe->tagICMSSN($std); 
                // $elem = $nfe->tagICMS($std);   
                
                break;   
                case '2': //ICMSSN - Tributação ICMS pelo Simples Nacional - CRT (Código de Regime Tributário) = 1 
                    $orig = $produtoArray[$i]['ORIGEM'];
                    $csosn = '';
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
                    $pCredSN = '';
                    $vCredICMSSN = '';

                    $std = new stdClass();
                    $std->item = $nItem; 
                    $std->orig = $orig;
                    $std->CSOSN = null;
                    $std->pCredSN = null;
                    $std->vCredICMSSN = null;
                    $std->modBCST = null;
                    $std->pMVAST = null;
                    $std->pRedBCST = null;
                    $std->vBCST = null;
                    $std->pICMSST = null;
                    $std->vICMSST = null;
                    $std->vBCFCPST = null; 
                    $std->pFCPST = null; 
                    $std->vFCPST = null; 
                    $std->vBCSTRet = null;
                    $std->pST = null;
                    $std->vICMSSTRet = null;
                    $std->vBCFCPSTRet = null; 
                    $std->pFCPSTRet = null; 
                    $std->vFCPSTRet = null; 
                    $std->modBC = null;
                    $std->vBC = null;
                    $std->pRedBC = null;
                    $std->pICMS = null;
                    $std->vICMS = null;
                    $std->pRedBCEfet = null;
                    $std->vBCEfet = null;
                    $std->pICMSEfet = null;
                    $std->vICMSEfet = null;
                    $std->pCredSN = null;
                    $std->vCredICMSSN = null;

                    switch ($produtoArray[$i]['TRIBICMS']){
                        case '00': // tributado integralmente
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '00';
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação 
                            $modBC = $produtoArray[$i]['MODBC'];
                            $vBC = number_format($produtoArray[$i]['BCICMS'], 2, '.', '');
                            $pICMS = $produtoArray[$i]['ALIQICMS']; 
                            $vICMS = $produtoArray[$i]['VALORICMS']; 
                            break;
                        case '10': // Tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '10';
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação.
                            $modBC = $produtoArray[$i]['MODBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; 
                            $vICMS = $produtoArray[$i]['VALORICMS']; 
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor)
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; 
                            break;
                        case '20': // Tributação com redução de base de cálculo
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '20'; 
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação.
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; 
                            $vICMS = $produtoArray[$i]['VALORICMS']; 
                            //$vICMSDeson = //Informar apenas nos motivos de desoneração documentados abaixo
                            //Campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração:
                            //3=Uso na agropecuária;
                            //9=Outros;
                            //12=Órgão de fomento e desenvolvimento agropecuário
                            //motDesICMS = 
                            break;
                        case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '30'; 
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor);
                            $modBCST = $produtoArray[$i]['MODBCST'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; 
                            //$vICMSDeson = Informar apenas nos motivos de desoneração documentados abaixo.
                            //Campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração:
                            //6=Utilitários e Motocicletas da Amazônia Ocidental e Áreas de Livre Comércio (Resolução 714/88 e 790/94 – CONTRAN e suas alterações);
                            //7=SUFRAMA;
                            //9=Outros;
                            //motDesICMS =  
                            break;
                        case '40': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '40'; //40=Isenta;
                            //$vICMSDeson //Valor do ICMS
                            //$motDesICMS //Motivo da desoneração do ICMS                            
                            break;
                        case '41': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '40'; //41=Não tributada;
                            //$vICMSDeson //Valor do ICMS
                            //$motDesICMS //Motivo da desoneração do ICMS                            
                            break;
                        case '50': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '50'; //50=Suspensão.                            
                            //$vICMSDeson //Valor do ICMS
                            //$motDesICMS //Motivo da desoneração do ICMS       
                            break;
                        case '51': // Tributação com Diferimento (a exigência do preenchimento das
                                   //informações do ICMS diferido fica a critério de cada UF).
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '51'; //51=Diferimento 
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; 
                            $vICMSOp = $produtoArray[$i]['VALORICMSOPERACAO']; //Valor como se não tivesse o diferimento
                            $pDif = $produtoArray[$i]['PERCDIFERIDO']; //No caso de diferimento total, informar o percentual de diferimento "100".
                            $vICMSDif = $produtoArray[$i]['VALORICMSDIFERIDO'];
                            $vICMS = $produtoArray[$i]['VALORICMS']; // Informar o valor realmente devido
                            break;
                        case '60': // Tributação ICMS cobrado anteriormente por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '60'; //60=ICMS cobrado anteriormente por substituição tributária
                            $vBCSTRet = is_null($produtoArray[$i]['VALORBCSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORBCSTRETIDO'];
                            $vICMSSTRet = is_null($produtoArray[$i]['VALORICMSSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORICMSSTRETIDO'];
                            break;
                        case '70': // Tributação ICMS com redução de base de cálculo e cobrança
                                   // do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '70'; //70=Com redução de base de cálculo e cobrança do ICMS por substituição tributária
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação.
                            $modBC = $produtoArray[$i]['MODBC'];
                            $pRedBC = is_null($produtoArray[$i]['PERCREDUCAOBC']) ? '0.00' : $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; 
                            $vICMS = $produtoArray[$i]['VALORICMS']; 
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor);
                            $modBCST = $produtoArray[$i]['MODBCST'];
                            $pMVAST = is_null($produtoArray[$i]['PERCMVAST']) ? '0.00' : $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = is_null($produtoArray[$i]['PERCREDUCAOBCST']) ? '0.00' : $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = is_null($produtoArray[$i]['VALORBCST']) ? '0.00' : $produtoArray[$i]['VALORBCST'];
                            $pICMSST = is_null($produtoArray[$i]['ALIQICMSST']) ? '0.00' : $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = is_null($produtoArray[$i]['VALORICMSST']) ? '0.00' : $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            //$vICMSDeson Informar apenas nos motivos de desoneração documentados abaixo
                            //Campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração:
                            //3=Uso na agropecuária;
                            //9=Outros;
                            //12=Órgão de fomento e desenvolvimento agropecuário.
                            //$motDesICMS
                            break;
                        case '90': // Tributação ICMS: Outros
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '90'; //90=Outros
                            //0=Margem Valor Agregado (%);
                            //1=Pauta (Valor);
                            //2=Preço Tabelado Máx. (valor);
                            //3=Valor da operação
                            $modBC = $produtoArray[$i]['MODBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pRedBC = $produtoArray[$i]['PERCREDUCAOBC'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; 
                            $vICMS = $produtoArray[$i]['VALORICMS']; 
                            //0=Preço tabelado ou máximo sugerido;
                            //1=Lista Negativa (valor);
                            //2=Lista Positiva (valor);
                            //3=Lista Neutra (valor);
                            //4=Margem Valor Agregado (%);
                            //5=Pauta (valor);
                            $modBCST = $produtoArray[$i]['MODBCST'];
                            $pMVAST = $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = $produtoArray[$i]['VALORBCST'];
                            $pICMSST = $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
                            //$vICMSDeson Informar apenas nos motivos de desoneração documentados abaixo
                            //Campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração:
                            //3=Uso na agropecuária;
                            //9=Outros;
                            //12=Órgão de fomento e desenvolvimento agropecuário.
                            //$motDesICMS
                            break;
                    } //switch

                    if ($orig != '')  {$std->orig = $orig;}                
                    if ($csosn != '') {$std->CSOSN = $csosn;}                
                    if ($modBC != '') {$std->modBC = $modBC;}                
                    if ($vBC != '')   {$std->vBC = $vBC;}
                    if ($pRedBC != ''){$std->pRedBC = $pRedBC;}
                    if ($pICMS != '') {$std->pICMS = $pICMS;}
                    if ($vICMS != '') {$std->vICMS = $vICMS;}
                    if ($modBCST != '') {$std->modBCST = $modBCST;}
                    if ($pMVAST != '') {$std->pMVAST = $pMVAST;}
                    if ($pRedBCST != '') {$std->pRedBCST = $pRedBCST;}
                    if ($vBCST != '') {$std->vBCST = $vBCST;}
                    if ($pICMSST != '') {$std->pICMSST = $pICMSST;}
                    if ($vICMSST != '') {$std->vICMSST = $vICMSST;}                
                    if ($pCredSN != '') {$std->pCredSN = $pCredSN;}
                    if ($vCredICMSSN != '') {$std->vCredICMSSN = $vCredICMSSN;}                  
                     
                    $elem = $nfe->tagICMS($std);   

                    break;
                    
                case '3':
                    //ICMS - Imposto sobre Circulação de Mercadorias e Serviços
                    $orig = '';
                    $cst = ''; 
                    $modBC = '';
                    $pRedBC = '0';
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
                            $pST = is_null($produtoArray[$i]['ALIQICMSST']) ? '18.00' : $produtoArray[$i]['ALIQICMSST'];
                            $vBCSTRet = is_null($produtoArray[$i]['VALORBCSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORBCSTRETIDO'];
                            $vICMSSTRet = is_null($produtoArray[$i]['VALORICMSSTRETIDO']) ? '0.00' : $produtoArray[$i]['VALORICMSSTRETIDO'];
                            $vICMSSubstituto = is_null($produtoArray[$i]['VICMSSUBSTITUTO']) ? '0.00' : $produtoArray[$i]['VICMSSUBSTITUTO'];
                            break;
                        case '70': // Tributação ICMS com redução de base de cálculo e cobrança
                                   // do ICMS por substituição tributária
                            $orig = $produtoArray[$i]['ORIGEM'];
                            $cst = '70';
                            $modBC = '3';
                            $pRedBC = is_null($produtoArray[$i]['PERCREDUCAOBC']) ? '0.00' : $produtoArray[$i]['PERCREDUCAOBC'];
                            $vBC = $produtoArray[$i]['BCICMS'];
                            $pICMS = $produtoArray[$i]['ALIQICMS']; // Alíquota do Estado
                            $vICMS = $produtoArray[$i]['VALORICMS']; // = $vBC * ( $pICMS / 100 )
                            $modBCST = '5'; // Calculo Por Pauta (valor)
                            $pMVAST = is_null($produtoArray[$i]['PERCMVAST']) ? '0.00' : $produtoArray[$i]['PERCMVAST'];
                            $pRedBCST = is_null($produtoArray[$i]['PERCREDUCAOBCST']) ? '0.00' : $produtoArray[$i]['PERCREDUCAOBCST'];
                            $vBCST = is_null($produtoArray[$i]['VALORBCST']) ? '0.00' : $produtoArray[$i]['VALORBCST'];
                            $pICMSST = is_null($produtoArray[$i]['ALIQICMSST']) ? '0.00' : $produtoArray[$i]['ALIQICMSST'];
                            $vICMSST = is_null($produtoArray[$i]['VALORICMSST']) ? '0.00' : $produtoArray[$i]['VALORICMSST']; // = (Valor da Pauta * Alíquota ICMS ST) - Valor ICMS Próprio
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
                    //nfe40 $resp = $nfe->tagICMS($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif, $vICMSDif, $vICMSOp, $vBCSTRet, $vICMSSTRet);
                    $std = new stdClass();
                    $std->item = $nItem; //item da NFe
                    $std->orig = $orig;
                    $std->CST = $cst;
                    $std->modBC = $modBC;
                    $std->vBC = $vBC;
                    $std->pICMS = $pICMS;
                    $std->vICMS = $vICMS;
                    $std->pFCP = null; // inserido nfe 40
                    $std->vFCP = null; // inserido nfe 40
                    $std->vBCFCP = null; // inserido nfe 40
                    $std->modBCST = $modBCST;
                    $std->pMVAST = $pMVAST;
                    $std->pRedBCST = $pRedBCST;
                    $std->vBCST = $vBCST;
                    $std->pICMSST = $pICMSST;
                    $std->vICMSST = $vICMSST;
                    $std->vBCFCPST = null; // inserido nfe 40
                    $std->pFCPST = null; // inserido nfe 40
                    $std->vFCPST = null; // inserido nfe 40
                    $std->vICMSDeson = $vICMSDeson;
                    $std->motDesICMS = $motDesICMS;
                    $std->pRedBC = $pRedBC;
                    $std->vICMSOp = $vICMSOp;
                    $std->pDif = $pDif;
                    $std->vICMSDif = $vICMSDif;
                    $std->vBCSTRet = $vBCSTRet;
                    $std->pST = $pST; // inserido nfe 40
                    $std->vICMSSTRet = $vICMSSTRet;
                    $std->vBCFCPSTRet = null; // inserido nfe 40
                    $std->pFCPSTRet = null; // inserido nfe 40
                    $std->vFCPSTRet = null; // inserido nfe 40
                    $std->pRedBCEfet = null; // inserido nfe 40
                    $std->vBCEfet = null; // inserido nfe 40
                    $std->pICMSEfet = null; // inserido nfe 40
                    $std->vICMSEfet = null; // inserido nfe 40

                    $elem = $nfe->tagICMS($std);  
            endswitch;
            
            if ($crt == '1') {
                $clEnq = '';    // Classe de enquadramento do IPI para Cigarros e Bebidas
                $CNPJProd = ''; // CNPJ do produtor da mercadoria, quando diferente do emitente. Somente para os casos de exportação direta ou indireta.
                $cSelo = '';    
                $qSelo = 0;
                $cEnq = '';
                $CST = ''; 
                $vIPI = 0;
                $vBC = '';
                $pIPI = '';
                $qUni = '';
                $vUnid = '';   
                
                $CST = $produtoArray[$i]['CSTIPI'];
                if ($produtoArray[$i]['CSTIPI'] == '') {
                    if ((($nfArray[0]['FINALIDADEEMISSAO'] == 10) and ($crt == '1')) or 
                        (($nfArray[0]['FINALIDADEEMISSAO'] == 4) and ($produtoArray[$i]['ALIQIPI'] > 0)) or
                        (($nfArray[0]['FINALIDADEEMISSAO'] == 2) and ($produtoArray[$i]['ALIQIPI'] > 0))){
                        $CST = '99';
                    } else {
                        $CST = '53';
                    }   
                }
                //if ($produtoArray[$i]['CSTIPI'] != '') {
                //    $CST = $produtoArray[$i]['CSTIPI'];
                    if ( ($CST == '00') || ($CST == '49') or ($CST == '50') or ($CST == '99') ) {
                        $cEnq = '999';     // O06 - Código de Enquadramento Legal do IPI (Tabela a ser criada pela RFB, informar 999 enquanto a tabela não for criada)
                        $vBC = $produtoArray[$i]['BCIPI'];
                        $pIPI = $produtoArray[$i]['ALIQIPI'];  
                        $vIPI = $produtoArray[$i]['VALORIPI'];                
                    } else {
                        $cEnq = '999';
                        $CST = '53';
                    }
        
                    $std = new stdClass(); 
                    if ($nItem != '')  { $std->item = $nItem; }
                    if ($clEnq != '')  { $std->clEnq = $clEnq;}
                    if ($CNPJProd != '')  {$std->CNPJProd = $CNPJProd;}
                    if ($cSelo != '')  {$std->cSelo = $cSelo;}
                    if ($qSelo != 0)  {$std->qSelo = $qSelo;}
                    if ($cEnq != '')  {$std->cEnq = $cEnq;}
                    if ($CST != '')  {$std->CST = $CST;}
                    if ($vIPI != '')  {$std->vIPI = $vIPI;}
                    if ($vBC != '')  {$std->vBC = $vBC;}
                    if ($pIPI != '')  {$std->pIPI = $pIPI;}
                    if ($qUnid != '')  {$std->qUnid = $qUnid;}
                    if ($vUnid != '')  {$std->vUnid = $vUnid;}
        
                    $elem = $nfe->tagIPI($std);
                //}
            }
     
            // TAG medicamentos
            $cProdANVISA = $produtoArray[$i]['CODPRODUTOANVISA'];
            if (($nfArray[0]['MODELO'] == 55) and ($cProdANVISA != null)):
                    $vPMC = number_format($produtoArray[$i]['UNITARIO'], 2, '.', '');
                    $std = new stdClass();
                    $std->item = $nItem; //item da NFe

                    $std->vPMC = $vPMC;

                    $std->cProdANVISA = $cProdANVISA; //incluido no layout 4.00.00

                    $elem = $nfe->tagmed($std);

            endif;

            if ((($nfArray[0]['FINALIDADEEMISSAO'] == 10) or ($nfArray[0]['FINALIDADEEMISSAO'] == 4)) 
                    and ($crt == '1')){
                $produtoArray[$i]['CSTPIS'] = 49;
            }
            
            // TAG PIS
            //PIS - Programa de Integração Social   ************* CALCULO PIS POSTERIORMENTE
            switch ($produtoArray[$i]['CSTPIS']){
                case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
                case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCPIS']; 
                    $pPIS = $produtoArray[$i]['ALIQPIS'];
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    $qBCProd = '';
                    $vAliqProd = '';
                    break;
                case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = ''; 
                    $pPIS = '';
                    $vPIS = $produtoArray[$i]['VALORPIS'];
                    $qBCProd = $produtoArray[$i]['BCPIS'];
                    $vAliqProd = $produtoArray[$i]['ALIQPIS'];
                    break;
                case '04': 
                case '05': 
                case '06': 
                case '07': 
                case '08': 
                case '09': 
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = '0'; 
                    $pPIS = '0';
                    $vPIS = '0';
                    $qBCProd = '0';
                    $vAliqProd = '0';
                    break;
                case '49': 
                    $cst = $produtoArray[$i]['CSTPIS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCPIS']; 
                    $pPIS = $produtoArray[$i]['ALIQPIS'];
                    $vPIS = $produtoArray[$i]['VALORPIS'];
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
            //nef40            $resp = $nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);

            $std = new stdClass();
            $std->item = $nItem; //item da NFe
            $std->CST = $cst;
            $std->vBC = $vBC;
            $std->pPIS = $pPIS;
            $std->vPIS = $vPIS;
            $std->qBCProd = $qBCProd;
            $std->vAliqProd = $vAliqProd;
            $elem = $nfe->tagPIS($std);  

            if ((($nfArray[0]['FINALIDADEEMISSAO'] == 10)or($nfArray[0]['FINALIDADEEMISSAO'] == 4)) and ($crt == '1')){
                $produtoArray[$i]['CSTCOFINS'] = 49;
            }
            // TAG COFINS
            //COFINS - Contribuição para o Financiamento da Seguridade Social
            switch ($produtoArray[$i]['CSTCOFINS']){
                case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
                case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCCOFINS']; 
                    $pCOFINS = $produtoArray[$i]['ALIQCOFINS'];
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    $qBCProd = '';
                    $vAliqProd = '';
                    break;
                case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = ''; 
                    $pCOFINS = '';
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
                    $qBCProd = $produtoArray[$i]['BCCOFINS'];
                    $vAliqProd = $produtoArray[$i]['ALIQCOFINS'];
                    break;
                case '04': 
                case '05': 
                case '06': 
                case '07': 
                case '08': 
                case '09': 
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = '0'; 
                    $pCOFINS = '0';
                    $vCOFINS = '0';
                    $qBCProd = '0';
                    $vAliqProd = '0';
                    break;
                case '49': 
                    $cst = $produtoArray[$i]['CSTCOFINS']; //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                    $vBC = $produtoArray[$i]['BCCOFINS']; 
                    $pCOFINS = $produtoArray[$i]['ALIQCOFINS'];
                    $vCOFINS = $produtoArray[$i]['VALORCOFINS'];
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
            //nfe40            $resp = $nfe->tagCOFINS($nItem, $cst, $vBC, $pCOFINS, $vCOFINS, $qBCProd, $vAliqProd);
                
            $std = new stdClass();
            $std->item = $nItem; //item da NFe
            $std->CST = $cst;
            $std->vBC = $vBC;
            $std->pCOFINS = $pCOFINS;
            $std->vCOFINS = $vCOFINS;
            $std->qBCProd = $qBCProd;
            $std->vAliqProd = $vAliqProd;
            $elem = $nfe->tagCOFINS($std);
            
            //Total Impostos
            $vTotTrib = number_format($vICMS + $vICMSST + $vIPI + $vPIS + $vCOFINS, 2, '.', ''); // 226.80 ICMS + 51.50 ICMSST + 50.40 IPI + 39.36 PIS + 81.84 CONFIS
            //nfe40            $resp = $nfe->tagimposto($nItem, $vTotTrib);
            $std = new stdClass();                                       
            $std->item = $nItem; //item da NFe
            $std->vTotTrib = $vTotTrib;

            $elem = $nfe->tagimposto($std);
            
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
            $vSegTotal=0;

            $vIITotal=0;
            $vIPITotal += $vIPI;
            $vPISTotal += $vPIS;
            $vCOFINSTotal += $vCOFINS;
            //$vOutroTotal=0;
            $vNFTotal=$nfArray[0]['TOTALNF'];
            $vTotTribTotal=0;

        } //for produtos        
        
        
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
        //$vICMS = 226.12;//number_format($vICMSTotal, 2, '.', '');
        $vICMS = number_format($vICMSTotal, 2, '.', '');
        $vICMSDeson = '0.00';
        $vFCP = '0.00';
        $vFCPST = '0.00';
        $vFCPSTRet = '0.00';
        $vBCST = number_format($vBCSTTotal, 2, '.', '');
        $vST = number_format($vSTTotal, 2, '.', '');
        $vProd = number_format($vProdTotal, 2, '.', '');
        if ($nfArray[0]['FRETE'] != ' '){
            $vFrete = number_format($nfArray[0]['FRETE'], 2, '.', '');                              
        } else {
            $vFrete = '';
        }
        $vSeg = '0.00';
        $vDesc = $vDescTotal;
        $vII = '0.00';
        $vIPI = number_format($vIPITotal, 2, '.', '');
        // testar se é nf de devolução 
        $vIPIDevol = '0.00';
        $vPIS = number_format($vPISTotal, 2, '.', '');
        $vCOFINS = number_format($vCOFINSTotal, 2, '.', '');
        $vOutro = '0.00';
        if ($vOutroTotal > 0){
            $vOutro = number_format($vOutroTotal, 2, '.', '');
        }
            
        $vNF = number_format($vProd-$vDesc-$vICMSDeson+$vST+$vFrete+$vSeg+$vOutro+$vII+$vIPI, 2, '.', '');
        $vTotTrib = number_format($vICMS+$vST+$vII+$vIPI+$vPIS+$vCOFINS+$vIOF+$vISS, 2, '.', '');
        //nfe40        $resp = $nfe->tagICMSTot($vBC, $vICMS, $vICMSDeson, $vBCST, $vST, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib);
        $std = new stdClass();
        $std->vBC = $vBC;
        $std->vICMS = $vICMS;
        $std->vICMSDeson = $vICMSDeson;
        $std->vFCP = $vFCP; //incluso no layout 4.00
        $std->vBCST = $vBCST;
        $std->vST = $vST;
        $std->vFCPST = $vFCPST; //incluso no layout 4.00
        $std->vFCPSTRet = $vFCPSTRet; //incluso no layout 4.00
        $std->vProd = $vProd;
        $std->vFrete = $vFrete;
        $std->vSeg = $vSeg;
        $std->vDesc = $vDesc;
        $std->vII = $vII;
        $std->vIPI = $vIPI;
        $std->vIPIDevol = $vIPIDevol; //incluso no layout 4.00
        $std->vPIS = $vPIS;
        $std->vCOFINS = $vCOFINS;
        $std->vOutro = $vOutro;
        $std->vNF = $vNF;
        $std->vTotTrib = $vTotTrib;

        $elem = $nfe->tagICMSTot($std);
        
        //FRETE
        //0=Por conta do emitente; 1=Por conta do destinatário/remetente; 2=Por conta de terceiros; 9=Sem Frete;
        //NEW CONDITION FOR SALE WITHIN THE STATE
        $std = new stdClass();
        if($nfArray[0]['VENDAPRESENCIAL'] == 'S'){
            $modFrete = '9';
        }else{
            $modFrete = $nfArray[0]['MODFRETE'];
        }
         
        $std->modFrete = $modFrete;

        $elem = $nfe->tagtransp($std);
        //nfe40        $resp = $nfe->tagtransp($modFrete);
        /*
        0 - Contratação do Frete por conta do Remetente (CIF)
        1 - Contratação do Frete por conta do Destinatário (FOB)
        2 - Contratação do Frete por conta de Terceiros
        3 - Transporte Próprio por conta do Remetente
        4 - Transporte Próprio por conta do Destinatário
        9 - Sem Ocorrência de Transporte
        */
    if ($modFrete != 9) {
        switch ($modFrete){
                case '0': // emitente/remetente
                case '3': // proprio - emitente/remetente
                if (is_array($transpArray)){
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
                //nfe40                    $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                } else {
                    $CNPJ = str_pad($filialArray[0]['CNPJ'], 14, "0", STR_PAD_LEFT);
                    $CPF = '';
                    $xNome = $filialArray[0]['NOMEEMPRESA'];
                    if ($filialArray[0]['INSCESTADUAL'] != ""):
                        $IE = $filialArray[0]['INSCESTADUAL'];
                    endif;
                    $xEnder = $this->removeAcentos($filialArray[0]['ENDERECO'].", ".$filialArray[0]['NUMERO']." - ".$filialArray[0]['COMPLEMENTO']." - ".$filialArray[0]['BAIRRO']);
                    $xMun = $filialArray[0]['CIDADE'];
                    $UF = $filialArray[0]['UF'];
                }    
                //nfe40                $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                break;
                case '1': // destinatário
                case '4': // emitente/remetente
                    if ($nfArray[0]['TRANSPORTADOR'] > 0):
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
                        $xEnder = $this->removeAcentos($transpArray[0]['ENDERECO']." ".$transpArray[0]['NUMERO']."-".$transpArray[0]['COMPLEMENTO']."-".$transpArray[0]['BAIRRO']);
                        $xMun = $transpArray[0]['CIDADE'];
                        $UF = $transpArray[0]['UF'];
                    else:    
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
                    endif;
                    //nfe40                $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                    break;
                case '2': // terceiros
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
                        //nfe40  $resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
                    endif;
                    break;
            } //switch
        $std = new stdClass();
        $std->xNome = $xNome;
        $std->IE = $IE;
        $std->xEnder = $xEnder;
        $std->xMun = $xMun;
        $std->UF = $UF;
        $std->CNPJ = $CNPJ;//só pode haver um ou CNPJ ou CPF, se um deles é especificado o outro deverá ser null
        $std->CPF = $CPF;
        
        $elem = $nfe->tagtransporta($std);        
        

        $qVol = $nfArray[0]['VOLUME']; //Quantidade de volumes transportados
        $esp = $this->removeAcentos($nfArray[0]['VOLESPECIE']); //Espécie dos volumes transportados
        $marca = $this->removeAcentos($nfArray[0]['VOLMARCA']); //Marca dos volumes transportados
        $nVol = $nfArray[0]['VOLUME']; //Numeração dos volume
        $pesoL = intval($nfArray[0]['VOLPESOLIQ']); //Kg do tipo Int, mesmo que no manual diz que pode ter 3 digitos verificador...
        $pesoB = intval($nfArray[0]['VOLPESOBRUTO']); //...se colocar Float não vai passar na expressão regular do Schema. =\
        $aLacres = '';
        //nfe40        $resp = $nfe->tagvol($qVol, $esp, $marca, $nVol, $pesoL, $pesoB, $aLacres);

        $std = new stdClass();
        $std->item = 1; //indicativo do numero do volume  *****verificar
        $std->qVol = $qVol;
        $std->esp = $esp;
        $std->marca = $marca;
        $std->nVol = $nVol;
        $std->pesoL = $pesoL;
        $std->pesoB = $pesoB;

        $elem = $nfe->tagvol($std);
            
        }
        if (count($financeiro) > 0) {
            $detFinanceiro = true;    
        } else {
            $detFinanceiro = false;    
        }

        for ($i = 0; $i<count($financeiro); $i++){
            if ($financeiro[$i]['VENCIMENTO'] < date("Y-m-d")){
                $financeiro[$i]['VENCIMENTO'] = date("Y-m-d");    
            }
        }

        for ($i = 0; $i<count($financeiro); $i++){
            if ($financeiro[$i]['VENCIMENTO'] < date("Y-m-d")){
                $detFinanceiro = false;    
            }
        }

        //if(count($financeiro) > 0) {
        if($detFinanceiro) {

        $std = new stdClass();
        $std->nFat = $nNF;
        $std->vOrig = $vNF;
        $std->vDesc = null;
        $std->vLiq = $vNF;
        $elem = $nfe->tagfat($std);

        $vNF = 0;
        for ($i = 0; $i<count($financeiro); $i++){
            $std = new stdClass();
            $std->nDup = str_pad($financeiro[$i]['PARCELA'], 3, "0", STR_PAD_LEFT);
            //$std->nDup = '00'.$financeiro[$i]['PARCELA']; //Código da Duplicata
            $std->dVenc = $financeiro[$i]['VENCIMENTO']; //Vencimento
            $std->vDup = $financeiro[$i]['VALOR']; // Valor
            $vNF += $financeiro[$i]['VALOR'];
            $elem = $nfe->tagdup($std);        
        }
        
        
    

        $std = new stdClass();
        $std->vTroco = null; //incluso no layout 4.00, obrigatório informar para NFCe (65)

        $elem = $nfe->tagpag($std);

        $std = new stdClass();
        // $std->CNPJ = '12345678901234'; // CNPJ credenciada do cartão de crédito
        // $std->tBand = '01'; // Bandeira da operadora do cartão de crédito
        // $std->cAut = '3333333'; // número autorização cartão de crédito
        // $std->tpIntegra = 2; //incluso na NT 2015/002 // sistema integrado ao TEF
        if (($nfArray[0]['FINALIDADEEMISSAO'] == 4) or
            ($nfArray[0]['FINALIDADEEMISSAO'] == 10)){
          $std->tPag = '90';
          $std->vPag = 0; //Obs: deve ser informado o valor pago pelo cliente            
        } else {
            for ($i = 0; $i<count($financeiroAgrupado); $i++){
                $std = new stdClass();
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'D'){
                    $std->tPag = '01'; //dinheiro
                    $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                    $std->indPag = '0'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'E'){
                    $std->tPag = '02'; //cheque
                    $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                    $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'K'){
                    $std->tPag = '03'; //cartao de crédito
                    $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                    $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                    $std->tpIntegra = '2';
                    $std->tBand = '99';
                } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'P'){
                    $std->tPag = '17'; //pix
                    $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                    $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'A'){
                    $std->tPag = '18'; //transferencia bancaria
                    $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                    $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                } else
                if ($financeiroAgrupado[$i]['TIPODOCTO'] == 'C'){
                    $std->tPag = '04'; //cartao de debito
                    $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                    $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                    $std->tpIntegra = '2';
                    $std->tBand = '99';
                } else {
                    $std->tPag = '15'; //boleto
                    $std->vPag = $financeiroAgrupado[$i]['VALOR']; //Obs: deve ser informado o valor pago pelo cliente
                    $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo          
                }
            }

        /*
          $std->tPag = '15';
          $std->vPag = $vNF; //Obs: deve ser informado o valor pago pelo cliente
          $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo
        */
        }
        $elem = $nfe->tagdetPag($std);

    }else{


        $std = new stdClass();
        $std->vTroco = null; //incluso no layout 4.00, obrigatório informar para NFCe (65)

        $elem = $nfe->tagpag($std);

        $std = new stdClass();
        // $std->CNPJ = '12345678901234'; // CNPJ credenciada do cartão de crédito
        // $std->tBand = '01'; // Bandeira da operadora do cartão de crédito
        // $std->cAut = '3333333'; // número autorização cartão de crédito
        // $std->tpIntegra = 2; //incluso na NT 2015/002 // sistema integrado ao TEF
        if ($nfArray[0]['FINALIDADEEMISSAO'] = 4){
          $std->tPag = '90';
          $std->vPag = 0; //Obs: deve ser informado o valor pago pelo cliente            
        } else {
          $std->tPag = '15';
          $std->vPag = $vNF; //Obs: deve ser informado o valor pago pelo cliente
          $std->indPag = '1'; //0= Pagamento à Vista 1= Pagamento à Prazo
        }
        $elem = $nfe->tagdetPag($std);    

    }      
        

        // Calculo de carga tributária similar ao IBPT - Lei 12.741/12
        $federal = number_format($vII+$vIPI+$vIOF+$vPIS+$vCOFINS, 2, ',', '.');
        $estadual = number_format($vICMS+$vST, 2, ',', '.');
        $municipal = number_format($vISS, 2, ',', '.');
        $totalT = number_format($federal+$estadual+$municipal, 2, ',', '.');
        $textoIBPT = "Valor Aprox. Tributos R$ {$totalT} - {$federal} Federal, {$estadual} Estadual e {$municipal} Municipal.";

        
        //Informações Adicionais
        //$infAdFisco = "SAIDA COM SUSPENSAO DO IPI CONFORME ART 29 DA LEI 10.637";
        $infAdFisco = "";
        $infCpl = $nfArray[0]['OBS'].' ';
        $infCpl .= $textoIBPT;
        //nfe40        $resp = $nfe->taginfAdic($infAdFisco, $infCpl);
        $std = new stdClass();
        $std->infAdFisco = '';
        //        $std->infCpl = removeAcentos($infCpl.$textoIBPT);
        $std->infCpl = $infCpl.' ';        
        $elem = $nfe->taginfAdic($std);
   
        //        function taginfRespTec($std):DOMElement
        //        Node da informação referentes ao Responsável Técnico NT 2018.005 Esta tag é OPCIONAL mas se for passada todos os campos devem ser passados para a função.

        $std = new stdClass();
        $std->CNPJ = '22886247000190'; //CNPJ da pessoa jurídica responsável pelo sistema utilizado na emissão do documento fiscal eletrônico
        $std->xContato= 'Marcio Sergio da Silva'; //Nome da pessoa a ser contatada
        $std->email = 'marcio.sergio@admservice.com.br'; //E-mail da pessoa jurídica a ser contatada
        $std->fone = '41995930181'; //Telefone da pessoa jurídica/física a ser contatada
        //      $std->CSRT = 'G8063VRTNDMO886SFNK5LDUDEI24XJ22YIPO'; //Código de Segurança do Responsável Técnico
        //      $std->idCSRT = '01'; //Identificador do CSRT

        $nfe->taginfRespTec($std);  


    //*************************************************************
    // tartamento de erro nf-e    
    if (empty($gerarXML)){
        function trataErro($codErro, $erroSefaz, $erroNf){
            $msg = "Xml não gerado <br> Código Mensagem: ".$codErro.": ";
            
            $erroNf .= $msg." - ";
            if (is_array($erroSefaz)) { 
                foreach ($erroSefaz as $err) {
                    $erroNf .= "$err <br>";
                }
            } else {
                $erroNf .= $erroSefaz;
            }
            throw new Exception( $erroNf );
            exit;
        }
    }
    
    // validacoes
    if (($cMunDest == "") or ($cMunDest == "0") or ($cMunDest == null)){
        trataErro('CÓDIGO MUNICIPIO DESTINATÁRIO NÃO CADASTRADO', "", "");
    }

    if (($pessoaDestArray[0]['CNPJCPF'] == "") or ($pessoaDestArray[0]['CNPJCPF'] == "0") or ($pessoaDestArray[0]['CNPJCPF'] == null)){
        trataErro('CNPJ DESTINATÁRIO NÃO CADASTRADO', "", "");
    }

    if (($pessoaDestArray[0]['CEP'] == "") or ($pessoaDestArray[0]['CEP'] == "0") OR
        ($pessoaDestArray[0]['CEP'] == null) or ($pessoaDestArray[0]['CEP'] == "80000000")){
        trataErro('CEP DESTINATÁRIO NÃO CADASTRADO', "", "");
    }

    $xml = $nfe->getXML();
    $return = $this->gera_DANFE($xml, $chave, $idNf);

    return $return;
    } //geraXML
    
} //class
$xml = new p_espelho_nfe();
