<?php

/****************************************************************************
 *Cliente...........:
 *Contratada........: ADMService
 *Desenvolvedor.....: Marcio Sergio da Silva
 *Sistema...........: Sistema de Informacao Gerencial
 *Classe............: P_NOTA_XML_IMPORTA - IMPORTA NOTA FISCAL ATRAVES DO XML
 *Data Ultima Atualizacao: 28/03/2012
 *Ultima Atualizacao: setCentrocusto , faz uma sql para vereficar pelo cnpj qual o centro custo
 *se  NF de saida entao o centro custo sera o que o usuario estara logado
 ****************************************************************************/
if (!defined('ADMpath')) : exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/fin/c_lancamento.php");
//require_once($dir . "/../../class/ped/c_pedido_venda_nf.php");
include_once($dir . "/../../class/crm/c_conta.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../class/est/c_nat_operacao.php");
include_once($dir . "/../../class/est/c_produto_estoque.php");

//Class P_mostraUpload
class p_nota_xml_importa extends c_nota_fiscal
{
    
    public $m_id              = NULL;
    public $m_idNf            = NULL;
    public $m_arquivo         = NULL;
    public $m_name            = NULL;
    public $m_tmp             = NULL;
    public $m_type            = NULL;
    public $m_size            = NULL;
    public $m_msg             = NULL;
    public $smarty            = NULL;
    public $xml_name          = NULL;
    public $xml_tmp           = NULL;
    public $xml_type          = NULL;
    public $natOper           = NULL;
    public $objItensValida    = NULL;
    public $numParcelaAdd     = NULL;
    public $dadosFinanceiros  = NULL;
    public $cnpj              = NULL;
    public $f_temp            = NULL;
    public $m_msg_cobranca    = NULL;
    public $m_nota_fiscal_div = NULL;
    public $existeNotaFiscal  = NULL;
    public $m_input           = NULL;
    public $m_color_tr        = NULL;
    public $m_param           = NULL;

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function p_nota_xml_importa($submenu, $letra)
    {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        //session_start();
        c_user::from_array($_SESSION['user_array']);

        // ajax
        $this->ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->natOper = (isset($parmPost['idNatOp']) ? $parmPost['idNatOp'] : '');

        $this->m_submenu = $submenu;
        if ($this->m_submenu == '' and $_GET["submenu"] == "conferirAjax") {
            $this->m_submenu = $_GET["submenu"];
        } else if ($parmGet['submenu'] !== '' and $parmGet['submenu'] !== null){
            $this->m_submenu = $parmGet['submenu'];
        }

        $this->m_letra = $letra;
        $this->m_par = explode("|", $this->m_letra);
        $this->m_msg = '';

        $this->numParcelaAdd = isset($parmPost['numParcelaAdd']) ? $parmPost['numParcelaAdd'] : '0';
        $this->dadosFinanceiros = isset($parmPost['dadosFinanceiros']) ? $parmPost['dadosFinanceiros'] : '';
        $this->cnpj = isset($parmPost['cnpj']) ? $parmPost['cnpj'] : '';

        if($parmGet[''] !== '' and $parmGet[''] !== null){
            $this->xml_arq = $parmGet['xml_arq'] ? $parmGet['xml_arq'] : '';
        }else{
            $this->xml_arq = isset($parmPost['xml_arq']) ? $parmPost['xml_arq'] : '';
        }

        if($parmGet['param'] !== '' and $parmGet['param'] !== null){
            $this->m_param = isset($parmGet['param']) ? $parmGet['param'] : '';
        }else{
            $this->m_param = isset($parmPost['param']) ? $parmPost['param'] : '';
        }


        if ($parmGet['idNf'] !== '' and $parmGet['idNf'] !== null) {
            $this->m_idNf = isset($parmGet['idNf']) ? $parmGet['idNf'] : '';
        } else {
            $this->m_idNf = isset($parmPost['idNf']) ? $parmPost['idNf'] : '';
        }
        
        $this->xml_name = isset($parmPost['tempFile']) ? $parmPost['tempFile'] : '';
        $this->objItensValida = isset($parmPost['objItensValida']) ? $parmPost['objItensValida'] : null;
        $this->existeNotaFiscal = isset($parmPost['existeNotaFiscal']) ? $parmPost['existeNotaFiscal'] : null;
        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // include ADMjs . "/est/s_nota_xml_importa.js";


    }

    /**
     * <b> Funcao para remover os acentos da importacao. </b>
     * @name removeAcentos
     * @param STRING $string
     * @return STRING
     */
    function removeChar($string)
    {
        $conversao = array('&' => 'e');
        return strtr($string, $conversao);
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function controle()
    {
        switch ($this->m_submenu) {
            case 'cobranca':
                if ($this->verificaDireitoUsuario('EstImportaNFE', 'I')) {
                    $this->mostraNota('', '', 'FINANCEIRO');
                }
                break;
            case 'gerarfinanceiro':
                if ($this->verificaDireitoUsuario('PedGeraNf', 'C')) {
                    

                    $transaction = new c_banco();
                    $transaction->inicioTransacao($transaction->id_connection);

                    //$arrPedido = $this->select_pedidoVenda();

                    //$this->setTotal(isset($this->parmPost['total']) ? $this->parmPost['total'] : '');                         

                    //$this->descCondPgto = (isset($this->parmPost['descCondPgto']) ? $this->parmPost['descCondPgto'] : "");

                    $arrParcelas = $this->formParcelasNfeFinanceiro($this->dadosFinanceiros, str_replace(',', '.', $this->m_par[1]), $this->m_par[6]);

                    //$arrParcelas = $this->formParcelasNfe($this->descCondPgto, $this->getTotal('B'), $this->parmPost['condPgto']);

                    $objFinanceiro = new c_lancamento();

                    if ($this->cnpj) {
                        $regPessoa = $this->busca_cliente('', $this->cnpj);
                        $teste_array = is_array($regPessoa);
                        if ($teste_array) {
                            $arrParamFin['PESSOA'] = $regPessoa[0]['CLIENTE'];
                        }
                    } else {
                        $arrParamFin['PESSOA'] = '0';
                    }

                    $arrParamFin['DOCTO'] = $this->m_par[0];
                    $arrParamFin['NUMLCTO'] = $this->m_par[0];
                    $arrParamFin['SERIE'] = $this->m_par[3];
                    $arrParamFin['CENTROCUSTO'] = $this->m_par[4];
                    $arrParamFin['GENERO'] = $this->m_par[5];
                    $arrParamFin['USER'] = $this->m_userid;
                    $arrParamFin['ORIGEM'] = "XML";
                    $arrParamFin['TIPOLANCAMENTO'] = "P";

                    $resultFin = $objFinanceiro->addParcelas($arrParamFin, $arrParcelas, $transaction->id_connection);

                    if($resultFin){
                        $transaction->commit($transaction->id_connection);
                        
                        $msgRetorno = 'Nota fiscal e financeiro cadastrados!';
                        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                        echo "<style>.swal-modal{width: 510px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgRetorno`, title: 'Sucesso!', icon: 'success',button: 'Ok',});</script>";
                    }else{
                        $transaction->rollback($transaction->id_connection);

                        $msgRetorno = 'Financeiro já existe para nota fiscal '. $arrParamFin['DOCTO'].'!';
                        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                        echo "<style>.swal-modal{width: 510px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgRetorno`, title: 'Atenção!', icon: 'error',button: 'Ok', dangerMode: true});</script>";
                    }

                    $this->mostraNota('');
                }
                break;
            case 'condpg':
                if ($this->verificaDireitoUsuario('EstImportaNFE', 'I')) {
                    $this->mostraNota('', '', 'FINANCEIRO');
                }
                break;
            case 'enviar':
                if ($this->verificaDireitoUsuario('EstImportaNFE', 'I')) {
                    $this->mostraNota('');
                }
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstImportaNFE', 'I')) {
                    if ($this->cadastrarNotaFiscal()){
                        $msgRetorno = 'Nota cadastra, prossiga com o financeiro!';
                        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                        echo "<style>.swal-modal{width: 510px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgRetorno`, title: 'Sucesso!', icon: 'success',button: 'Ok',});</script>";
                        $this->mostraNota('', '', 'FINANCEIRO');
                    }else{
                        $this->mostraNota($this->m_msg, "alerta");
                    }
                }
                break;
            case 'conferir':
                if ($this->verificaDireitoUsuario('EstImportaNFE', 'I')) {
                    $this->conferirNotaFiscal();
                    $this->mostraNota('');
                }
                break;
            case 'conferirAjax':
                if ($this->verificaDireitoUsuario('EstImportaNFE', 'I')) {
                    $tableDisagreements = $this->conferirNotaFiscal($this->xml_arq);
                    $tableItens = $this->desenhaTabelaItens($this->xml_arq);
                    $result = $tableDisagreements . '<br>' . $tableItens;
                }

                header('Content-type: application/json');
                header('existeNotaFiscal:'. $this->existeNotaFiscal);
                echo json_encode($result, JSON_FORCE_OBJECT);
                die;
            case 'entradaManifesto':
                if ($this->verificaDireitoUsuario('EstImportaNFE', 'I')) {
                    $searchXml = $this->busca_xml($this->m_idNf);

                    if($searchXml){
                        $this->xml_arq = $searchXml[0]['XMLCONSULTA'];
                        $this->mostraNota('');
                    }else{
                        $msgRetorno = 'Xml não localizado!';
                        echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script> ";
                        echo "<style>.swal-modal{width: 510px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgRetorno`, title: 'Atenção!', icon: 'error',button: 'Ok',dangerMode: true});</script>";
                        $this->mostraNota('');
                    }
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstImportaNFE', 'C')) {
                    $this->mostraNota('');
                }
        }
    } // fim controle

    //---------------------------------------------------------------
    //---------------------------------------------------------------

    public function cobrarTaxa($conta, $condpgto, $total)
    {

        $sql  = "SELECT TAXA FROM fin_conta_taxa ";
        $sql .= "WHERE (conta = '" . $conta . "' ) and ";
        $sql .= "(condpgto = '" . $condpgto . "' ) ";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;

        if ($result > 0) {
            return ($total * ($result[0][TAXA] / 100));
        } else {
            return '0';
        }
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------

    public function formParcelasNfeFinanceiro($condPgto = NULL, $total = 0)
    {
        $parcelas = explode("|", $condPgto);
        $numParcelas = count($parcelas) - 1;
        $totalGeral = doubleval($total);
        $totalCalc = 0;
        for ($i = 0; $i < $numParcelas; $i++) {
            $parcela = explode("*", $parcelas[$i + 1]);
            $lanc[$i]['PARCELA'] = trim($parcela[0]);
            $lanc[$i]['VENCIMENTO'] = $parcela[1];
            $lanc[$i]['VALOR'] = $parcela[2];
            $lanc[$i]['TIPO'] = $parcela[3];
            $lanc[$i]['CONTA'] = $parcela[4];
            $lanc[$i]['SITUACAO'] = $parcela[5];
            $lanc[$i]['OBS'] = $parcela[6];
            $totalCalc += $lanc[$i]['VALOR'];
        }
        $epsilon = 0.00001;
        $totalAbs = abs($totalCalc - $totalGeral);
        if ($totalAbs < $epsilon) :
            return $lanc;
        else :
            return $lanc;
        //uol
        //  return "Valor total parcelas: R$ ".$totalCalc." não confere com TOTAL NF";
        endif;
    }

    //---------------------------------------------------------------
    //---------------------------------------------------------------

    public function calculaParcelasNfe($condPgto = NULL, $total = 0, $acrescentarParcela = 0)
    {
        //setlocale(LC_MONETARY, 'en_US');
        $descCondPgto = str_replace('DIAS', '', $condPgto);
        $parcelas = explode("/", $condPgto);
        $numParcelas = count($parcelas);
        $totalGeral = $total;
        //$valorParcela = money_format('%i', $totalGeral / $numParcelas);
        //$valorParcela =  str_replace(number_format(($totalGeral / $numParcelas),2),',','');
        $valorParcela =  round($totalGeral / $numParcelas, 2, PHP_ROUND_HALF_DOWN);
        if ($acrescentarParcela > 0) {
            $totalNumParcelas += $acrescentarParcela;
        }

        $totalNumParcelas += $numParcelas;

        for ($i = 0; $i < $totalNumParcelas; $i++) {
            if ($i < $numParcelas) {
                $lanc[$i]['PARCELA'] = $i + 1;
                $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + " . intval($parcelas[$i]) . " day"));
                //$lanc[$i]['VALOR'] = str_replace(".", ",", $valorParcela);
                $lanc[$i]['VALOR'] = number_format((double) $valorParcela, 2, ',', '.');
            } else {
                $lanc[$i]['PARCELA'] = $i + 1;
                $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + " . intval($parcelas[$numParcelas - 1]) . " day"));
                $lanc[$i]['VALOR'] = str_replace(".", ",", 0);
            }
        }
        $lanc[0]['VALOR'] = number_format((double) ($valorParcela - (($valorParcela * $numParcelas) - doubleval($totalGeral))), 2, ',', '.');
        //$lanc[0]['VALOR'] = str_replace(".", ",", $lanc[0]['VALOR']);
        return $lanc;
    }
    //---------------------------------------------------------------
    //---------------------------------------------------------------

    public function busca_cliente($nome, $cnpj)
    {

        //$sql = "select * FROM FIN_CLIENTE WHERE (nome = '".strtoupper($nome)."')";
        $sql = "select * FROM FIN_CLIENTE WHERE (CNPJCPF = '" . $cnpj . "')";
        //	ECHO strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_pessoa
    //---------------------------------------------------------------
    //---------------------------------------------------------------

    public function busca_xml($idNf)
    {
        $sql = "SELECT DISTINCT XMLCONSULTA FROM EST_NOTA_FISCAL_XML WHERE (IDNF = '" . $idNf . "') COLLATE utf8_bin;";
        //	ECHO strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql_lower_case($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_pessoa
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    public function busca_produto($desc, $cod, $fabricante, $codBarras)
    {

        //	$sql = "select * FROM EST_PRODUTO WHERE (DESCRICAO = '".strtoupper($desc)."') AND (CODFABRICANTE = ".$cod.") AND (FABRICANTE = ".$fabricante.");";
        //	$sql = "select * FROM EST_PRODUTO WHERE (CODFABRICANTE = ".$cod.") AND (FABRICANTE = ".$fabricante.");";
        $sql = "select distinct p.* FROM EST_PRODUTO P  ";
        $sql .= "left JOIN EST_PRODUTO_EQUIVALENCIA E ON (E.IDPRODUTO=P.CODIGO) ";
        $sql .= "WHERE ";
        if (($codBarras != '') and ($codBarras != 'SEM GTIN')) :
            $sql .= "(CODIGOBARRAS = '" . $codBarras . "') OR ";
        endif;
        $sql .= "((CODFABRICANTE = '" . $cod . "') AND (FABRICANTE = " . $fabricante . ")) ";
        $sql .= "OR ((E.CODEQUIVALENTE = '" . $cod . "')) LIMIT 1";

        //ECHO strtoupper($sql)."<br>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_pessoa

    public function busca_produto_new($cod, $fabricante=null)
    {

        if($fabricante !== null){
            $sql = "SELECT DISTINCT p.*,  ";
            $sql .= "CASE ";
            $sql .= "WHEN (p.CODFABRICANTE = '" . $cod . "' AND p.FABRICANTE = " . $fabricante . ") THEN 'CODFABRICANTE_FABRICANTE' ";
            $sql .= "WHEN (p.CODFABRICANTE = '" . $cod . "') THEN 'CODFABRICANTE' ";
            $sql .= "WHEN (e.CODEQUIVALENTE = '" . $cod . "') THEN 'EQUIVALENCIA' ";
            $sql .= "END AS ORIGEM_CONSULTA ";
            $sql .= "FROM EST_PRODUTO p ";
            $sql .= "LEFT JOIN EST_PRODUTO_EQUIVALENCIA e ON (e.IDPRODUTO = p.CODIGO) ";
            $sql .= "WHERE ";
            $sql .= "(p.CODFABRICANTE = '" . $cod . "' AND p.FABRICANTE = " . $fabricante . ") ";
            $sql .= "OR (p.CODFABRICANTE = '" . $cod . "')";
            $sql .= "OR (e.CODEQUIVALENTE = '" . $cod . "') LIMIT 1;";
        }else{
            $sql = "SELECT DISTINCT p.*,  ";
            $sql .= "CASE ";
            $sql .= "WHEN (p.CODFABRICANTE = '" . $cod . "') THEN 'CODFABRICANTE' ";
            $sql .= "WHEN (e.CODEQUIVALENTE = '" . $cod . "') THEN 'EQUIVALENCIA' ";
            $sql .= "END AS ORIGEM_CONSULTA ";
            $sql .= "FROM EST_PRODUTO p ";
            $sql .= "LEFT JOIN EST_PRODUTO_EQUIVALENCIA e ON (e.IDPRODUTO = p.CODIGO) ";
            $sql .= "WHERE ";
            $sql .= "(p.CODFABRICANTE = '" . $cod . "')";
            $sql .= "OR (e.CODEQUIVALENTE = '" . $cod . "') LIMIT 1;";
        }

        //ECHO strtoupper($sql)."<br>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim select_pessoa


    public function busca_financeiro($pessoa, $docto, $serie)
    {

        $sql = "select * FROM FIN_LANCAMENTO ";
        $sql .= "WHERE (PESSOA = '" . $pessoa . "') and ";
        $sql .= " (DOCTO = '" . $docto . "') and ";
        $sql .= " (SERIE = '" . $serie . "') and  ";
        $sql .= " (TIPOLANCAMENTO = 'P')  ";
        //	ECHO strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //busca_financeiro


    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function conferirNotaFiscal($xml)
    {

        $result = true;
        $this->m_msg = '';
        $pessoa = new c_conta();
        $produto = new c_produto();
        if (!$xml->NFe) {
            $xml = new SimpleXMLElement($xml);
        }

        //rodo um foreach que vai varrer o objeto xml e printar na tela toda a lista de cidades que est� dentro do meu xml. Note que os m�todos da classe s�o as tags do xml que foram convertidas no objeto com a classe simplexml, facilita demais a vida de n�s s�mples suricatos do php!!!
        $this->m_msg .= "<table id='tableDisagreements' class='table tableProd table-bordered' width='100%' style='border-radius:8px !important; border-collapse:inherit !important;'>";
        $this->m_msg .= "<tr colspan='4' align='center'><td align='center' id='divergencia' colspan='4'><h5>Diverg&ecirc;ncias !</h5></tr>";
        foreach ($xml->NFe as $key => $item) {

            // cliente
            $regPessoa = $this->busca_cliente(substr($item->infNFe->emit->xNome, 0, 50), $item->infNFe->emit->CNPJ);
            $array_existe_pessoa = is_array($regPessoa);

            if (!$array_existe_pessoa) {
                $result = false;
                $this->m_msg .= "<td style='padding-top:10px;'><b><i>";
                $this->m_msg .= substr($item->infNFe->emit->xNome, 0, 50) . "</b></i></td><td align='center' style='padding-top:10px;'><i> CNPJ: " . $item->infNFe->emit->CNPJ . "</i></td><td colspan='2'>";
                $params = $pessoa->contaXmlJson($item);
                $this->m_msg .= "<input type='button' id='submitFornecedor' style='margin-top:5px; margin-left:37%;' class='btn btn-xs btn-success' name='button_envia' value='CADASTRAR' onClick='javascript:submitInsertJson(" . $params . ");'>";
                $pessoa->setPessoa(0);
            } else { // teste cliente

                //$this->m_msg .= "Nome: ".substr($item->infNFe->emit->xNome, 0, 50)."</td><td> CNPJ: ".$item->infNFe->emit->CNPJ. "</td><td></td><td>  LOCALIZADO";
                $pessoa->setPessoa($regPessoa[0]['CLIENTE']);
            }
            if (!$array_existe_pessoa) {
                $this->m_msg .= "</td>";
            }
            $this->m_msg .= "</tr>";

            // nota fiscal
            $this->setModelo($item->infNFe->ide->mod);
            $this->setSerie($item->infNFe->ide->serie);
            $this->setNumero($item->infNFe->ide->nNF);
            $this->setPessoa($pessoa->getPessoa());

            if ($this->existeNotaFiscalEntrada()) {
                $result = false;
                $existeNotaFiscal = true;
                $this->existeNotaFiscal = true;
                $this->m_msg .= "<tr><td id='existeNotaFiscal' align='center'>";
                $this->m_msg .=  "<h4><B>NOTA FISCAL IMPORTADA ANTERIORMENTE</B></h4>";
                $this->m_msg .= "</td></tr>";

                if ($regPessoa[0]['CLIENTE'] > 0) {
                    $regProduto = $this->busca_financeiro(
                        $regPessoa[0]['CLIENTE'],
                        $item->infNFe->ide->nNF,
                        $item->infNFe->ide->serie
                    );
                    $teste_array = is_array($regProduto);
                    $this->m_msg_cobranca = '';
                    if (!$teste_array) {
                        $this->m_msg_cobranca .=  "<input style='color:red' type='button' name='button_cobranca' value='GERAR' onClick='javascript:submitCobranca();'>";
                    }
                }
            } else {
                $this->existeNotaFiscal = false;
            }

            //ITENS
            if (!$existeNotaFiscal) {

                for ($i = 0; $i < count($item->infNFe->det); $i++) {

                    //forehead of the tag nfAdProd exists - implementation day 11-07-23
                    if (isset($item->infNFe->det[$i]->prod->cProdAlter)) {
                        $regProduto = $this->busca_produto_new(substr($item->infNFe->det[$i]->prod->cProdAlter, 0, 25), $pessoa->getPessoa());
                    } else {
                        $regProduto = $this->busca_produto_new(substr($item->infNFe->det[$i]->prod->cProd, 0, 25), $pessoa->getPessoa());
                    }

                    $teste_array = is_array($regProduto);

                    if (!$teste_array) {
                        $this->m_msg .= "<tr><td>";

                        //forehead of the tag nfAdProd exists - implementation day 11-07-23
                        if (isset($item->infNFe->det[$i]->prod->cProdAlter)) {
                            $this->m_msg .= $item->infNFe->det[$i]->prod->xProd .
                                "</td><td align='center'> " .
                                $item->infNFe->det[$i]->prod->cProdAlter .
                                "</td><td align='center'> " .
                                $item->infNFe->det[$i]->prod->cEANTrib .
                                "</td><td align='center'>";
                        } else {
                            $this->m_msg .= $item->infNFe->det[$i]->prod->xProd .
                                "</td><td align='center'> " .
                                $item->infNFe->det[$i]->prod->cProd .
                                "</td><td align='center'> " .
                                $item->infNFe->det[$i]->prod->cEANTrib .
                                "</td><td align='center'>";
                        }

        
                        if ($pessoa->getPessoa() > 0){
                            $result = false;
                            $params = $produto->produtoXmlJson($item->infNFe->det[$i], $pessoa->getPessoa());
                            $this->m_msg .= "<input type='button' style='margin-top:5px;vertical-align:middle;' class='btn btn-xs btn-success' name='button_envia' value='CADASTRAR' onClick='javascript:submitInsertJson(" . $params . ");'>";
                            $this->m_msg .= "<input type='button' style='margin-top:5px;vertical-align:middle' class='btn btn-xs btn-dark' name='button_pesquisa' value='PESQUISAR' onClick='javascript:submitSearchJson(" . $params . ");'>";
                        }else{
                            $this->m_msg .= "AGUARDANDO...";
                        }

                        $existeProduto = true;
                        $this->m_msg .= "</td></tr>";
                    } else {
                        //se existir produto faltando nao alterar o status para false
                        if(!$existeProduto){
                            $existeProduto = false;
                        }
                        
                    }
                } // FIM FOR
                $this->m_msg .= "</table>";

            } //FIM ITENS

        } ##################################  FIM foreach ##################################



        if ($this->m_submenu == "conferirAjax") {
            //se nao existir produto retorna false para habilitar btn cadastrar na view
            if (!$existeProduto) {
                $this->m_msg = false;
            }
            return $this->m_msg;
        } else {
            //teste para zerar a var msg que contem o xml que sera impresso na tela
            if ((!$existeNotaFiscal) and (!$existeProduto)) {
                $this->m_nota_fiscal_div = 'false';
                if($array_existe_pessoa){ //se nao existir cliente nao zera a mensagem para mostrar a divergencia na tela
                    $this->m_msg = '';
                }
                
            }
            return $result;
        }
    } // Conferir Nota


    //---------------------------------------------------------------
    // insere somente a nfe e itens
    //---------------------------------------------------------------
    function cadastrarNotaFiscal()
    {

        //Obtendo info. dos arquivos
        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];
        //$f_tmp = $_POST['f_tmp'];

        if ((file_exists($f_tmp)) or (strlen($this->xml_arq) > 10)) {

            // if (strlen($this->xml_arq) > 10){
            //    $xml = new SimpleXMLElement($this->xml_arq);
            // } else {
            //     $xml = simplexml_load_file($f_tmp);
            // }

            $this->m_msg = "Arquivo XML com erro de leitura, selecionar novamente XML para Cadastrar ou Visualizar!!";
            if (file_exists($f_tmp)) {
                $xml = simplexml_load_file($f_tmp);
            } elseif (strlen($this->xml_arq) > 10) {
                $xml = simplexml_load_string($this->xml_arq);
                if ($xml != false) {
                    $xml = new SimpleXMLElement($this->xml_arq);
                }
            } else {
                $xml = false;
            }
            $xml != false ?  $result = $this->conferirNotaFiscal($xml) : $result = false;
        } else {
            $result = false;
        }

    
        if ($result) {
            $this->m_msg = "";

            $pessoa = new c_conta();
            $nfProduto = new c_nota_fiscal_produto();
            $produto = new c_produto();
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $genero = $parametros->getParametros("GENERO");
            $nopEntrada = $parametros->getParametros("NATOPENTRADA");
            $xmlConferirEstoque = $parametros->getParametros("XMLCONFERIRESTOQUE");

            //novo parametro para entrada de xml de nota nao processada pelo manifesto
            $existeNotaFiscalNaoProcessada = $this->existeNotaFiscalNaoProcessada();
            //seta o id da nota nao processada se existir
            if($existeNotaFiscalNaoProcessada !== false){
                $this->m_idNf = $existeNotaFiscalNaoProcessada;
            }

            $msgErro = "";

            //rodo um foreach que vai varrer o objeto xml e printar na tela toda a lista de cidades que est� dentro do meu xml. Note que os m�todos da classe s�o as tags do xml que foram convertidas no objeto com a classe simplexml, facilita demais a vida de n�s s�mples suricatos do php!!!
            foreach ($xml->NFe as $key => $item) {
                if (isset($item->infNFe)) {

                    if ($item->infNFe->ide->tpNF == 0) : // 0=Entrada; 1=Saída; 
                        echo "<table border='1'>";
                        echo "<tr><td class='marcadortitulo'>";
                        echo "NOTA FISCAL ENTRADA FORNCEDOR - TIPO INCORRETO PARA IMPORTAÇÃO";
                        $this->m_msg = "NOTA FISCAL ENTRADA FORNCEDOR - TIPO INCORRETO PARA IMPORTAÇÃO";
                        return "";

                    endif;

                    //filial da nota fiscal
                    $consulta = new c_banco();
                    $sql = "SELECT * FROM amb_empresa WHERE (cnpj = '" . $item->infNFe->dest->CNPJ . "')";
                    //echo strtoupper($sql)."<BR>";
                    $consulta->exec_sql($sql);
                    $consulta->close_connection();
                    $result = $consulta->resultado;
                    // ECHO "PASSOU CC".$result[0]['CENTROCUSTO'];
                    if ($result[0]['CENTROCUSTO'] != '') {
                        $this->setCentroCusto($result[0]['CENTROCUSTO']);
                    } else {
                        echo "<table border='1'>";
                        echo "<tr><td class='marcadortitulo'>";
                        echo "EMPRESA NÃO CADASTRADA NO SISTEMA PARA IMPORTAÇÃO - " . $item->infNFe->dest->CNPJ;
                        echo "</td></tr>";
                        $this->m_msg = "DESTINATÁRIO INCORRETO!</br></br>" . "NOME: " . $item->infNFe->dest->xNome . "</br> CNPJ: " . $item->infNFe->dest->CNPJ;
                        return "";
                    }
                    if (!$teste_array) {
                        $this->m_msg .= "</td>";
                    }
                    $this->m_msg .= "</tr>";
                }

                // cliente
                $regPessoa = $this->busca_cliente(substr($item->infNFe->emit->xNome, 0, 50), $item->infNFe->emit->CNPJ);
                $teste_array = is_array($regPessoa);
                if ($teste_array) {
                    $pessoa->setPessoa($regPessoa[0]['CLIENTE']);
                } // teste cliente
                else {
                    echo "<table border='1'>";
                    echo "<tr><td class='marcadortitulo'>";
                    echo "FORNECEDOR NÃO CADASTRADO - " . $item->infNFe->emit->xNome;
                    echo "</td></tr>";
                    $this->m_msg = "FORNCEDOR NÃO CADASTRADO";
                    return "";
                }

                // testa nf existe
                $this->setModelo($item->infNFe->ide->mod);
                $this->setSerie($item->infNFe->ide->serie);
                $this->setNumero($item->infNFe->ide->nNF);
                $this->setPessoa($pessoa->getPessoa());

                //new validation input manifest
                if($this->m_param !== 'entradaManifesto' and $existeNotaFiscalNaoProcessada == false){
                    if ($this->existeNotaFiscalEntrada()) {
                        $this->m_msg .=  "<table border='1'>";
                        $this->m_msg .= "<tr><td class='marcadortitulo'>";
                        $this->m_msg .= "NOTA FISCAL J&atilde; CADASTRADA";
                        $this->m_msg .= "</td></tr>";
                        $this->m_msg = "NOTA FISCAL J&atilde; CADASTRADA";
                        return "";
                    }
                }

                //funcao para ajustar data e hr da NF 3.0 - format <dhEmi>2015-08-21T16:14:44-03:00</dhEmi>
                $expData = explode("T", $item->infNFe->ide->dhEmi);
                $data = str_replace("-", "/", $expData[0]);
                $data = date("d/m/Y", strtotime($data));
                $expHora = explode("-", $expData[1]);
                //-------------------------------------------------------------------------------------------

                $this->setEmissao($data . " " . $expHora[0]);

                //nat operacao
                $objNatOper = new c_nat_operacao();
                $objNatOper->setId($this->natOper);
                $arrNatOper = $objNatOper->selectNatOperacao();
                if (is_array($arrNatOper)) {
                    $this->setIdNatop($this->natOper);
                    $this->setNatOperacao($arrNatOper[0]['NATOPERACAO']);
                } else {
                    $this->setIdNatop($this->natOper);
                    $this->setNatOperacao(substr($item->infNFe->ide->natOp, 0, 60));
                }

                $this->setTipo('0'); // 0=Entrada; 1=Saída;

                if ($xmlConferirEstoque == 'N') {
                    $this->setSituacao('B');
                } else {
                    //new validation input manifest
                    if($this->m_param !== 'entradaManifesto' and $existeNotaFiscalNaoProcessada == false){
                        $this->setSituacao('A');
                    }else{
                        $this->setSituacao('B');
                    }
                }
                $this->setFormaPgto('0');

                if (isset($item->infNFe->ide->dhSaiEnt)) :
                    $expData = '';
                    $expData = explode("T", $item->infNFe->ide->dhSaiEnt);
                    $data = str_replace("-", "/", $expData[0]);
                    $data = date("d/m/Y", strtotime($data));
                    $expHora = explode("-", $expData[1]);
                    $this->setDataSaidaEntrada($data . " " . $expHora[0]);
                else :
                    $this->setDataSaidaEntrada(date('d/m/Y H:i'));
                endif;

                $this->setFinalidadeEmissao($item->infNFe->ide->finNFe);
                $this->setGenero($genero);
                $this->setOrigem('NFE');
                $this->setDoc($item->infNFe->ide->nNF);
                $this->setTransportador($item->infNFe->transp->modFrete); // verificar outras opção de frete no XML
                $this->setModFrete($item->infNFe->transp->modFrete); // verificar outras opção de frete no XML
                $this->setDhRecbto($xml->protNFe->infProt->dhRecbto);
                $this->setNProt($xml->protNFe->infProt->nProt);
                $this->setDigVal($xml->protNFe->infProt->digVal);
                $this->setVerAplic($xml->protNFe->infProt->verAplic);


                if($this->m_param == 'entradaManifesto' and $existeNotaFiscalNaoProcessada !== false){
                    $this->setTotalnf(($item->infNFe->total->ICMSTot->vNF->__toString()), true);
                }else{
                    $this->setTotalnf(($item->infNFe->total->ICMSTot->vNF->__toString()), true);
                }

                $this->setObs(str_replace("'", ',', $item->infNFe->infAdic->infCpl));

                //new validation input manifest
                if($this->m_param !== 'entradaManifesto' and $existeNotaFiscalNaoProcessada == false){
                    // insere nf
                    $lastNF = $this->incluiNotaFiscal();
                }else{
                    $lastNF = intval($this->m_idNf);
                }


                $this->m_id = $lastNF;
                if (!is_int($lastNF)) {
                    echo "<table border='1'>";
                    echo "<tr><td class='marcadortitulo'>";
                    echo "ERRO NA INCLUSÃO DA NOTA FISCAL - CONTATAR O SUPORTE";
                    echo "</td></tr>";
                    break;
                }

                /////////////////////////////////////////////////////////////////
                //PRODUTOS da nota fiscal
                for ($i = 0; $i < count($item->infNFe->det); $i++) {

                    //OLD
                    // produto
                    // $regProduto = $this->busca_produto_new(
                    //     substr($item->infNFe->det[$i]->prod->xProd, 0, 60),
                    //     substr($item->infNFe->det[$i]->prod->cProd, 0, 25),
                    //     $pessoa->getPessoa(),
                    //     trim($item->infNFe->det[$i]->prod->cEANTrib)
                    // );

                    if (isset($item->infNFe->det[$i]->prod->cProdAlter)) {
                        $regProduto = $this->busca_produto_new(substr($item->infNFe->det[$i]->prod->cProdAlter, 0, 25), $pessoa->getPessoa());
                    } else {
                        $regProduto = $this->busca_produto_new(substr($item->infNFe->det[$i]->prod->cProd, 0, 25), $pessoa->getPessoa());
                    }

                    $teste_array = is_array($regProduto);
                    if ($teste_array) {
                        // ATUALIZA DADOS DO PRODUTOS;
                        $params = $produto->produtoXmlJson($item->infNFe->det[$i], $pessoa->getPessoa(), 'P', $produto, $regProduto[0]['CODIGO']);
                        $produto->setPrecoBase($regProduto[0]['PRECOBASE'], true);
                        $produto->setPercCalculo($regProduto[0]['PERCCALCULO'], true);
                        $produto->setPrecoInformado($regProduto[0]['PRECOINFORMADO'], true);
                        $produto->setCustoCompra(doubleval($item->infNFe->det[$i]->prod->vUnCom), true);
                        $produto->setCustoMedio($regProduto[0]['CUSTOMEDIO'], true);
                        $produto->setCustoReposicao($regProduto[0]['CUSTOREPOSICAO'], true);

                        $produto->setNfUltimaCompra($item->infNFe->ide->nNF);
                        $produto->setNfUltimaCompraEquiv($item->infNFe->ide->nNF);
                        $dataultima = substr($item->infNFe->ide->dhEmi, 8, 2) . "/" .
                            substr($item->infNFe->ide->dhEmi, 5, 2) . "/" .
                            substr($item->infNFe->ide->dhEmi, 0, 4);
                        $produto->setDataUltimaCompra($dataultima);
                        $produto->setDataUltimaCompraEquiv($dataultima);
                        $produto->setContaEquiv($pessoa->getPessoa());


                        //forehead of the tag nfAdProd exists - implementation day 11-07-23
                        if (isset($item->infNFe->det[$i]->prod->cProdAlter)) {
                            $produto->setCodEquivalente(substr($item->infNFe->det[$i]->prod->cProdAlter, 0, 25));
                        } else {
                            $produto->setCodEquivalente(substr($item->infNFe->det[$i]->prod->cProd, 0, 25));
                        }

                        $produto->setQuantUltimaCompraEquiv(doubleval($item->infNFe->det[$i]->prod->qCom), true);
                        if ($produto->getTribIcms() == null) {
                            $produto->setTribIcms($regProduto[0]['TRIBICMS']);
                        }
                        if ($produto->getOrigem() == null) {
                            $produto->setOrigem($regProduto[0]['ORIGEM']);
                        }

                        if (!$produto->alteraProdutoEquivalencia()) {
                            $produto->incluiProdutoEquivalencia();
                        }

                        $msgErro .= $produto->alteraProdutoNFEntrada($arrNatOper[0]['ALTERAPRECOS'], $produto->getPrecoBase());

                        // insere produto nf
                        $params = $produto->produtoXmlJson($item->infNFe->det[$i], $pessoa->getPessoa(), 'N', $nfProduto, $regProduto[0]['CODIGO'], $lastNF) . "\n";
                        //new validation input manifest
                        $resultIncluiProduto = $nfProduto->incluiNotaFiscalProduto();

                        if ($xmlConferirEstoque == 'N') {

                            $parametros = new c_banco;
                            $parametros->setTab("EST_PARAMETRO");
                            $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=" . $this->m_empresacentrocusto);

                            $parametros->close_connection();
                            $objProduto = new c_produto();
                            $objProduto->setId($regProduto[0]['CODIGO']);
                            $arrProduto = $objProduto->select_produto();
                            $uniFrac = $arrProduto[0]['UNIFRACIONADA'];
                            $ifControlaEstoque = (($controlaEstoque == 'S') && ($uniFrac == 'N'));

                            if ($ifControlaEstoque) {
                                $quant = $arrProduto[0]['QUANTULTIMACOMPRA'];
                                $objEstProduto = new c_produto_estoque();
                                for ($it = 0; $it < $quant; $it++) {
                                    $objEstProduto->setIdNfEntrada($item->infNFe->ide->nNF);
                                    $objEstProduto->setCodProduto($arrProduto[0]['CODIGO']);
                                    $objEstProduto->setStatus('0');
                                    $objEstProduto->setAplicado('0');
                                    $objEstProduto->setCentroCusto($this->m_empresacentrocusto);
                                    $objEstProduto->setUserProduto($this->m_userid);
                                    $objEstProduto->setLocalizacao('');
                                    $objEstProduto->incluiProdutoEstoque($transaction->id_connection);
                                } //for
                            }
                        }
                    }
                } // FOR PRODUTO
                
                //new validation input manifest
                if($resultIncluiProduto == true){
                    $this->setId($this->m_idNf);
                    $this->alteraNotaFiscal();
                }

            } //foreach

            if ($msgErro != "") {
                $this->m_msg = $msgErro;
            }
            //$this->m_msg .= 'Nota entrada importada com sucesso';
            return true;
        } else {
            return false;
        }


        // Cadastrar Nota    

    } // cadastrarNotaFiscal


    ////---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraNotaFiscalXML1($xml)
    {

        echo $this->m_msg;
        //rodo um foreach que vai varrer o objeto xml e printar na tela toda a lista de cidades que est� dentro do meu xml. Note que os m�todos da classe s�o as tags do xml que foram convertidas no objeto com a classe simplexml, facilita demais a vida de n�s s�mples suricatos do php!!!
        foreach ($xml->NFe as $key => $item) {
            if (isset($item->infNFe)) {

                ////////////////////////////////////////////////////////////////////////
                //cabecalho da nota fiscal
                $ide_cUF = $item->infNFe->ide->cUF;
                $ide_cNF = $item->infNFe->ide->cNF;
                $ide_natOp = $item->infNFe->ide->natOp;
                $ide_indPag = $item->infNFe->ide->indPag;
                $ide_mod = $item->infNFe->ide->mod;
                $ide_serie = $item->infNFe->ide->serie;
                $ide_nNF = $item->infNFe->ide->nNF;
                $ide_dEmi = $item->infNFe->ide->dhEmi;
                $ide_dhSaiEnt = $item->infNFe->ide->dhSaiEnt;
                $ide_tpNF = $item->infNFe->ide->tpNF;
                $ide_cMunFG = $item->infNFe->ide->cMunFG;
                $ide_tpImp = $item->infNFe->ide->tpImp;
                $ide_tpEmis = $item->infNFe->ide->tpEmis;
                $ide_cDV = $item->infNFe->ide->cDV;
                $ide_tpAmb = $item->infNFe->ide->tpAmb;
                $ide_finNFe = $item->infNFe->ide->finNFe;
                $ide_procEmi = $item->infNFe->ide->procEmi;
                $ide_verProc = $item->infNFe->ide->verProc;

                ////////////////////////////////////////////////////////////////////////
                //Informacaes da Empresa que esta enviando as pecas
                $emit_CNPJ = $item->infNFe->emit->CNPJ;
                $emit_xNome = $item->infNFe->emit->xNome;
                $emit_xFant = $item->infNFe->emit->xFant;
                $emit_xLgr = $item->infNFe->emit->enderEmit->xLgr;
                $emit_nro = $item->infNFe->emit->enderEmit->nro;
                $emit_xBairro = $item->infNFe->emit->enderEmit->xBairro;
                $emit_cMun = $item->infNFe->emit->enderEmit->cMun;
                $emit_xMun = $item->infNFe->emit->enderEmit->xMun;
                $emit_UF = $item->infNFe->emit->enderEmit->UF;
                $emit_CEP = $item->infNFe->emit->enderEmit->CEP;
                $emit_cPais = $item->infNFe->emit->enderEmit->cPais;
                $emit_xPais = $item->infNFe->emit->enderEmit->xPais;
                $emit_fone = $item->infNFe->emit->enderEmit->fone;
                $emit_IE = $item->infNFe->emit->IE;
                $emit_CRT = $item->infNFe->emit->CRT;

                ////////////////////////////////////////////////////////////////////////
                //Informacoes do local do recebimento de pecas
                $dest_CNPJ = $item->infNFe->dest->CNPJ;
                $dest_xNome = $item->infNFe->dest->xNome;
                $dest_xLgr = $item->infNFe->dest->enderDest->xLgr;
                $dest_nro = $item->infNFe->dest->enderDest->nro;
                $dest_xBairro = $item->infNFe->dest->enderDest->xBairro;
                $dest_cMun = $item->infNFe->dest->enderDest->cMun;
                $dest_xMun = $item->infNFe->dest->enderDest->xMun;
                $dest_UF = $item->infNFe->dest->enderDest->UF;
                $dest_CEP = $item->infNFe->dest->enderDest->CEP;
                $dest_cPais = $item->infNFe->dest->enderDest->cPais;
                $dest_xPais = $item->infNFe->dest->enderDest->xPais;
                $dest_fone = $item->infNFe->dest->enderDest->fone;
                $dest_IE = $item->infNFe->dest->IE;
                $dest_email = $item->infNFe->dest->email;

                ////////////////////////////////////////////////////////////////////////
                //Informacoes do protocolo NFe
                $chNFe = $xml->protNFe->infProt->chNFe;

                ////////////////////////////////////////////////////////////////////////
                //produtos da nota fiscal
                $nitem = 1;
                for ($i = 0; $i < count($item->infNFe->det); $i++) {


                    //##################### OLD 19-julho-2024 ###################
                    // //if there is an invoice blocks the input
                    // if ($this->existeNotaFiscal) {
                    //     $det_cProd[$i] = '<input type="text" class="form-control" 
                    // style="background-color:#f3a4a4; padding:0;pointer-events: none;" id="codProd" 
                    // name="codProd' . $nitem . '" value="' . $item->infNFe->det[$i]->prod->cProd . '" 
                    // onchange="javascript:mudaCodProdXmlNew(this.value, ' .
                    //         $item->infNFe->det[$i]->prod->cProd . ')">';
                    // } else {
                    //     $det_cProd[$i] = '<input type="text" class="form-control" 
                    // style="background-color:#fff0; padding:0;" id="codProd" 
                    // name="codProd' . $nitem . '" value="' . $item->infNFe->det[$i]->prod->cProd . '" 
                    // onchange="javascript:mudaCodProdXmlNew(this.value, ' .
                    //         $item->infNFe->det[$i]->prod->cProd . ')">';
                    // }

                    $det_cProdXml[$i] = $item->infNFe->det[$i]->prod->cProd;
                    $det_xProd[$i] = $item->infNFe->det[$i]->prod->xProd;
                    $det_NCM[$i] = $item->infNFe->det[$i]->prod->NCM;
                    $det_CST[$i] = $item->infNFe->det[$i]->prod->CEST;
                    $det_CFOP[$i] = $item->infNFe->det[$i]->prod->CFOP;
                    $det_CEST[$i] = $item->infNFe->det[$i]->prod->CEST;
                    $det_uCom[$i] = $item->infNFe->det[$i]->prod->uCom;
                    $det_qCom[$i] = $item->infNFe->det[$i]->prod->qCom;
                    $det_vUnCom[$i] = $item->infNFe->det[$i]->prod->vUnCom;
                    $det_vProd[$i] = $item->infNFe->det[$i]->prod->vProd;
                    $det_uTrib[$i] = $item->infNFe->det[$i]->prod->uTrib;
                    $det_qTrib[$i] = $item->infNFe->det[$i]->prod->qTrib;
                    $det_vUnTrib[$i] = $item->infNFe->det[$i]->prod->vUnTrib;
                    $det_indTot[$i] = $item->infNFe->det[$i]->prod->indTot;
                    $det_xPed[$i] = $item->infNFe->det[$i]->prod->xPed;
                    $det_cEAN[$i] = $item->infNFe->det[$i]->prod->cEAN;
                    $det_cEANTrib[$i] = $item->infNFe->det[$i]->prod->cEANTrib;
                    //IMPOSTO-ICMS
                    $det_orig[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS20->orig;
                    $det_CSTICMS[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS20->CST;
                    $det_modBC[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS20->modBC;
                    $det_pRedBC[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS20->pRedBC;
                    $det_vBCICMS[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS20->vBC;
                    $det_pICMS[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS20->pICMS;
                    $det_vICMS[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS20->vICMS;

                    $det_vBCSTRet[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS60->vBCSTRet;
                    $det_vICMSSTRet[$i] = $item->infNFe->det[$i]->imposto->ICMS->ICMS60->vICMSSTRet;

                    //IMPOSTO-IPI
                    $det_cEnq[$i] = $item->infNFe->det[$i]->imposto->IPI->cEnq;
                    $det_CSTIPI[$i] = $item->infNFe->det[$i]->imposto->IPI->IPITrib->CST;
                    $det_vBCIPI[$i] = $item->infNFe->det[$i]->imposto->IPI->IPITrib->vBC;
                    $det_pIPI[$i] = $item->infNFe->det[$i]->imposto->IPI->IPITrib->pIPI;
                    $det_vIPI[$i] = $item->infNFe->det[$i]->imposto->IPI->IPITrib->vIPI;

                    //IMPOSTO-PIS - OLD
                    //$det_CSTPIS[$i] = $item->infNFe->det[$i]->imposto->PIS->CST;
                    //$det_vBCPIS[$i] = $item->infNFe->det[$i]->imposto->PIS->vBC;
                    //$det_pPIS[$i] = $item->infNFe->det[$i]->imposto->PIS->pPIS;
                    //$det_vPIS[$i] = $item->infNFe->det[$i]->imposto->PIS->vPIS;
                    //IMPOSTO-PIS - NEW
                    $det_CSTPIS[$i] = $item->infNFe->det[$i]->imposto->PIS->PISAliq->CST;
                    $det_vBCPIS[$i] = $item->infNFe->det[$i]->imposto->PIS->PISAliq->vBC;
                    $det_pPIS[$i] = $item->infNFe->det[$i]->imposto->PIS->PISAliq->pPIS;
                    $det_vPIS[$i] = $$item->infNFe->det[$i]->imposto->PIS->PISAliq->vPIS;

                    //IMPOSTO-COFINS - OLD
                    //$det_CSTCOFINS[$i] = $item->infNFe->det[$i]->imposto->PIS->PISAliq->CST;
                    //$det_vBCCOFINS[$i] = $item->infNFe->det[$i]->imposto->COFINS->COFINSOutr->vBC;
                    //$det_pCOFINS[$i] = $item->infNFe->det[$i]->imposto->COFINS->COFINSOutr->pCOFINS;
                    //$det_vCOFINS[$i] = $item->infNFe->det[$i]->imposto->COFINS->COFINSOutr->vCOFINS;

                    //IMPOSTO-COFINS - NEW
                    $det_CSTCOFINS[$i] = $item->infNFe->det[$i]->imposto->COFINS->COFINSAliq->CST;
                    $det_vBCCOFINS[$i] = $item->infNFe->det[$i]->imposto->COFINS->COFINSAliq->vBC;
                    $det_pCOFINS[$i] = $item->infNFe->det[$i]->imposto->COFINS->COFINSAliq->pCOFINS;
                    $det_vCOFINS[$i] = $item->infNFe->det[$i]->imposto->COFINS->COFINSAliq->vCOFINS;
                    //Numero da Ordem de servi�o
                    $det_infAdProd[$i] = $item->infNFe->det[$i]->infAdProd;

                    $nitem++;
                }


                $total_vBC = $item->infNFe->total->ICMSTot->vBC;
                $total_vICMS = $item->infNFe->total->ICMSTot->vICMS;
                $total_vBCST = $item->infNFe->total->ICMSTot->vBCST;
                $total_vST = $item->infNFe->total->ICMSTot->vST;
                $total_vProd = $item->infNFe->total->ICMSTot->vProd;
                $total_vFrete = $item->infNFe->total->ICMSTot->vFrete;
                $total_vSeg = $item->infNFe->total->ICMSTot->vSeg;
                $total_vDesc = $item->infNFe->total->ICMSTot->vDesc;
                $total_vII = $item->infNFe->total->ICMSTot->vII;
                $total_vIPI = $item->infNFe->total->ICMSTot->vIPI;
                $total_vPIS = $item->infNFe->total->ICMSTot->vPIS;
                $total_vCOFINS = $item->infNFe->total->ICMSTot->vCOFINS;
                $total_vOutro = $item->infNFe->total->ICMSTot->vOutro;
                $total_vNF = $item->infNFe->total->ICMSTot->vNF;
                //INFORMACOES ADICIONAIS
                $infAdic_infCpl = $item->infNFe->infAdic->infCpl;

                if (count($item->infNFe->cobr->dup) > 0) {
                    for ($i = 0; $i < count($item->infNFe->cobr->dup); $i++) {
                        $det_pag_nDup[$i] = $item->infNFe->cobr->dup[$i]->nDup;
                        $det_pag_dVenc[$i] = $item->infNFe->cobr->dup[$i]->dVenc;
                        $det_pag_vDup[$i] = $item->infNFe->cobr->dup[$i]->vDup;
                    }
                } else {
                    for ($i = 0; $i < count($item->infNFe->pag->detPag); $i++) {
                        $det_pag_nDup[$i] = '001';
                        $det_pag_dVenc[$i] = date('d/m/Y');
                        $det_pag_vDup[$i] = $item->infNFe->pag->detPag->vPag;
                    }
                }
            } // if
        } //for
?>
        <table border="1" width="100%">
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">RECEBEMOS DE <?php echo $emit_xNome . " - " . $emit_CNPJ; ?> OS PRODUTOS CONSTANTES NA NOTA FISCAL INDICADA AO LADO</td>
                <td align="center" rowspan="2"> NFe <br><?php
                                                        echo 'N&ordm; ' . $ide_nNF;
                                                        echo '<br>';
                                                        echo ' Serie 00' . $item->infNFe->ide->serie; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <h6>IDENTIFICA&Ccedil;&Atilde;O E ASSINATURA DO RECEBEDOR</h6>
                </td>
                <td colspan="2">
                    <h6>DATA DE RECEBIMENTO</h6>
                </td>

            </tr>
            <tr>
                <td colspan="5">
                    ........................................................................................................................................................................................................................................................................
                </td>
            <tr>
                <td><?php echo $emit_xNome;
                    echo '<br>';
                    echo $emit_xLgr;
                    echo ' -' . $emit_nro;
                    echo '<br>';
                    echo 'Bairro: ' . $emit_xBairro;
                    echo '<br>';
                    echo 'Fone: ' . $emit_fone;
                    echo '<br>';
                    echo 'CEP: ' . $emit_CEP;
                    echo ' ' . $emit_xMun;
                    echo ' / ' . $emit_UF;
                    ?>
                </td>
                <td align="left" colspan="4">
                    <h3 style="text-align:center;">DANFE</h3>
                    Documento Auxiliar de Nota Fiscal Eletr&ocirc;nica
                    <br>
                    0 - ENTRADA<br>
                    1 - SA&iacute;DA <?php echo $ide_tpNF = $item->infNFe->ide->tpNF;
                                        echo '<br>';
                                        echo 'N&ordm;. 000' . $ide_nNF;
                                        echo ' Serie 00' . $item->infNFe->ide->serie;
                                        ?><br>
                </td>
            </tr>
        </table>

        <table border="1" width="100%">
            <tr>
                <td colspan="3">
                    <h6>Natureza da Opera&ccedil;&atilde;o </h6>
                    <?php echo $ide_natOp; ?>
                </td>
            </tr>
        </table>
        <table border="1" width="100%">
            <tr>
                <td style="text-align:center;" width="20%">
                    <h6>INSCRI&Ccedil;&Atilde;O ESTADUAL </h6> <?php echo $emit_IE; ?>
                </td>
                <td style="text-align:center;" width="25%">
                    <h6>INSCRI&Ccedil;&Atilde;O ESTADUAL DO SUBST. TRIBUTARIO</h6>
                </td>
                <td style="text-align:center;" width="15%">
                    <h6>CNPJ</h6> <?php echo $emit_CNPJ; ?>
                </td>
                <td style="text-align:center;">
                    <h6>CHAVE DE ACESSO</h6><?php echo $chNFe; ?>
                </td>
            </tr>

            </tr>
        </table>

        <table border="1" width="100%">
            <tr>
                <td>DESTINAT&Aacute;RIO/REMETENTE</td>
            </tr>
            <tr>
                <td width="50%">
                    <h6> NOME/RAZAO SOCIAL </h6> <?php echo $dest_xNome; ?>
                </td>
                <td width="20%">
                    <h6>CNPJ</h6> <?php echo $dest_CNPJ; ?>
                </td>
                <td width="100%" colspan="2" style="text-align:center;">
                    <h6>DATA.EMISSAO</h6> <?php echo $ide_dEmi; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h6>ENDERE&Ccedil;O</h6> <?php echo $dest_xLgr;
                                                echo ' ' . $dest_nro;
                                                echo ' - CEP: ' . $dest_CEP; ?>
                </td>

                <td>
                    <h6>BAIRRO</h6> <?php echo $dest_xBairro; ?>
                </td>
                <td width="40%" colspan="2" style="text-align:center;">
                    <h6>DATA ENTRADA/SAIDA</h6> <?php echo $ide_dEmi; ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h6>MUNICIPIO</h6> <?php echo $dest_xMun; ?>
                </td>
                <td style="text-align:center;">
                    <h6>FONE/FAX</h6> <?php echo $dest_fone; ?>
                </td>
                <td style="text-align:center;">
                    <h6> UF </h6> <?php echo $dest_UF; ?>
                </td>
                <td style="text-align:center;">
                    <h6> INSC. ESTADUAL </h6> <?php echo $dest_IE; ?>
                </td>
            </tr>
        </table>

        <table border="1" width="100%">
            <tr>
                <td>CALCULO DO IMPOSTO</td>
            </tr>
            <tr>
                <td style="text-align:center;">
                    <h6> BASE DO ICMS </h6> <?php echo $total_vBC; ?>
                </td>
                <td style="text-align:center;">
                    <h6> VALOR DO ICMS </h6> <?php echo $total_vICMS; ?>
                </td>
                <td style="text-align:center;">
                    <h6> BASE DE CALCULO DO ICMS SUBSTITUI&Ccedil;&Atilde;O </h6> <?php echo $total_vBCST; ?>
                </td>
                <td style="text-align:center;">
                    <h6> VALOR DO ICMS SUBSTITUI&Ccedil;&Atilde;O </h6> <?php echo $total_vST; ?>
                </td>

                <td style="text-align:center;" colspan="2">
                    <h6> VALOR TOTAL DOS PRODUTOS </h6> <?php echo $total_vProd; ?>
                </td>
            </tr>
            <tr>
                <td style="text-align:center;">
                    <h6> VALOR DO FRETE </h6> <?php echo $total_vFrete; ?>
                </td>
                <td style="text-align:center;">
                    <h6> VALOR DO SEGURO </h6> <?php echo $total_vSeg; ?>
                </td>
                <td style="text-align:center;">
                    <h6> DESCONTO </h6> <?php echo $total_vDesc; ?>
                </td>
                <td style="text-align:center;">
                    <h6> OUTRO DESPESAS ACESSORIAS </h6> <?php echo $total_vOutro; ?>
                </td>
                <td style="text-align:center;">
                    <h6> VALOR IPI </h6> <?php echo $total_vIPI; ?>
                </td>
                <td style="text-align:center;">
                    <h6> VALOR TOTAL DA NOTA </h6> <?php echo $total_vNF; ?>
                </td>
            </tr>
        </table>
        <br>

        <!--######################### ANTIGO LAYOUT TABELA DE ITENS 19-julho-24 ######################### 
        (componentized the assembly of the itemns table  function->$this->desenhaTabelaItens();) 
            
        <table>
            <tr>
                <td style="width: 40vw;"> DADOS DO PRODUTO/SERVI&Ccedil;OS </td>
            </tr>
        </table> -->
        
        <!-- <table border="1" width="100%" id="tabelaItens">
            <tr>
                <th style="width: 60vw;">&nbsp;&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp; C&Oacute;D. ALTERAÇÃO </th>
                <th> C&Oacute;D. XML</th>
                <th> DESCRI&Ccedil;&Atilde;O DO PRODUTO / SERVI&Ccedil;OS</th>
                <th style="text-align:center;"> NCM </th>
                <th> CST</th>
                <th> CFOP</th>
                <th> UND</th>
                <th style="text-align:center;"> QTD</th>
                <th style="text-align:center;"> V. UNITARIO</th>
                <th style="text-align:center;"> V. TOTAL</th>
                <th> BC ICMS</th>
	            <th> V. ICMS</th>
	            <th> V. IPI</th>
	            <th> ALIQ. ICMS</th>
	            <th> ALIQ. IPI</th>
	            <th> BC ICMS RET</th>
	            <th> V. ICMS RET</th>
            </tr> -->
            <!-- ?<php

            for ($i = 0; $i < count($item->infNFe->det); $i++) {

                //logic to test if the code was found and set the color of the <TR>
                $regPessoa = $this->busca_cliente(substr($item->infNFe->emit->xNome, 0, 50), $item->infNFe->emit->CNPJ);
                $buscaProduto = $this->busca_produto_new(substr($item->infNFe->det[$i]->prod->cProd, 0, 25), $regPessoa[0]['CLIENTE']);

                if ($buscaProduto[0]['ORIGEM_CONSULTA'] == 'CODFABRICANTE_FABRICANTE') {
                    $color = '#99e794eb';
                } elseif ($buscaProduto[0]['ORIGEM_CONSULTA'] == 'CODFABRICANTE') {
                    $color = '#efbf67';
                } elseif ($buscaProduto[0]['ORIGEM_CONSULTA'] == 'EQUIVALENCIA') {
                    $color = '#a5a1a1c7';
                } else {
                    $color = '#a5a1a100';
                }

                echo '<tr style="background-color:' . $color . ';" id="infoProd">';

                echo '<td id="cProd" width="13%">' . $det_cProd[$i] . '</td>';
                echo '<td id="cProdXml" width="7%" name="cProdXml' . $item->infNFe->det[$i]->prod->cProd . '">' . $det_cProdXml[$i] . '</td>';
                echo '<td id="xProd">' . $item->infNFe->det[$i]->prod->xProd . '<br>' . $det_infAdProd[$i] . '</td>';
                echo '<td id="ncm" align="center" width="6%">' . $det_NCM[$i] . '</td>';
                // echo '<td>'.$det_CST[$i].'</td>';
                echo '<td id="cfop" align="center" width="3.8%">' . $det_CFOP[$i] . '</td>';
                echo '<td id="unidade" align="center" width="3%">' . $det_uCom[$i] . '</td>';
                echo '<td id="quantidade" align="center" width="7%">' . $det_qCom[$i] . '</td>';
                echo '<td id="vlrUnitario" width="8%" align="center">' . $det_vUnTrib[$i] . '</td>';
                echo '<td id="vlrTotal" width="7%" align="center">' . $det_vProd[$i] . '</td>';
                echo '<td id="cEan" hidden width="7%" align="center">' . $det_cEAN[$i] . '</td>';
                echo '<td id="cEANTrib" hidden width="7%" align="center">' . $det_cEANTrib[$i] . '</td>';
                //  echo '   <td>'.$det_vBCICMS[$i].'</td>';
                //  echo '   <td>'.$det_vICMS[$i].'</td>';                                
                //  echo '   <td>'.$det_vIPI[$i].'</td>';
                //  echo '   <td>'.$det_pICMS[$i].'</td>';
                //  echo '   <td>'.$det_pIPI[$i].'</td>';
                //  echo '   <td>'.$det_vBCSTRet[$i].'</td>';
                //  echo '   <td>'.$det_vICMSSTRet[$i].'</td>';
                echo '</tr>';
            }

            ?>

        </table> -->
        <!--#########################   FIM ANTIGO LAYOUT TABELA DE ITENS 19-julho-24   #########################-->


        <!--#########################   NOVO LAYOUT TABELA DE ITENS 19-julho-24   #########################-->
        <?php 
        $tabelaItens = $this->desenhaTabelaItens($xml); 
        echo $tabelaItens;
        ?>
        <!--#########################   FIM NOVO LAYOUT TABELA DE ITENS 19-julho-24   #########################-->

        <div id="legendas">
            
            <div class="legenda1" style="margin-right: 40px !important;">
                <div class="caixa-cor" style="background-color: #99e794eb;"></div>
                <span>Localizado código fabricante e fabricante</span>
            </div>

            <div class="legenda2" style="margin-right: 40px !important;">
                <div class="caixa-cor" style="background-color: #efbf67;"></div>
                <span>Localizado código fabricante</span>
            </div>

            <div class="legenda3">
                <div class="caixa-cor" style="background-color: #a5a1a1c7;"></div>
                <span>Localizado código equivalente</span>
            </div>
        </div>
        <br><br><br><br>
        <table>
            <tr>
            <tr>
                <td><b>COBRANÇA</b><?php echo '' . $this->m_msg_cobranca . ' ' ?> </td>
            </tr>
            </tr>
        </table>
        <table border="1" width="100%">
            <tr>
                <th> Parcela</th>
                <th> Vencimento</th>
                <th> Valor</th>
            </tr>
            <?php

            if (count($item->infNFe->cobr->dup) > 0) {
                for ($i = 0; $i < count($item->infNFe->cobr->dup); $i++) {
                    echo '<tr>';
                    echo '   <td>' . $det_pag_nDup[$i] . '</td>';
                    echo '   <td>' . $det_pag_dVenc[$i] . '</td>';
                    echo '   <td>' . $det_pag_vDup[$i] . '</td>';
                    echo '</tr>';
                }
            } else {
                for ($i = 0; $i < count($item->infNFe->pag->detPag); $i++) {
                    echo '<tr>';
                    echo '   <td>' . $det_pag_nDup[$i] . '</td>';
                    echo '   <td>' . $det_pag_dVenc[$i] . '</td>';
                    echo '   <td>' . $det_pag_vDup[$i] . '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </table>
        <br><br><br><br>

        <table>
            <tr>
                <td> <b> DADOS ADICIONAIS</b></td>
            </tr>
        </table>
        <table id="informacoesComplementares" border="1" width="100%">
            <tr>
                <td width="85%" align="left" valign="top"> INFORMA&Ccedil;&Atilde;ES COMPLEMENTARES:<br><?php echo $infAdic_infCpl; ?> </td>
                <td>
                    <h6>RESERVADO A FISCO</h6><br><br><br><br><br><br><br><br><br>
                </td>
            </tr>
        </table>

<?php
    }
    //---------------------------------------------------------------

    
    public function desenhaTabelaItens($xml_arq)
    { 
        
        if(!is_object($xml_arq)){
            $xml = new SimpleXMLElement($xml_arq);
        }else{
            $xml = $xml_arq;
        }
       
        //########################## <TABLE> #################################
        $table = ' <div id="tableItemns">
                    <tr>
                        <td style="width: 40vw;"> DADOS DO PRODUTO/SERVI&Ccedil;OS </td>
                    </tr>
            
                <table border="1" width="100%">
                    <tr>
                        <th style="width: 60vw;">&nbsp;<i class="fa fa-pencil" aria-hidden="true"></i>&nbsp; C&Oacute;D. ALTERAÇÃO </th>
                        <th> C&Oacute;D. XML</th>
                        <th> DESCRI&Ccedil;&Atilde;O DO PRODUTO / SERVI&Ccedil;OS</th>
                        <th style="text-align:center;"> NCM </th>
                        <!--<th> CST</th> -->
                        <th> CFOP</th>
                        <th> UND</th>
                        <th style="text-align:center;"> QTD</th>
                        <th style="text-align:center;"> V. UNITARIO</th>
                        <th style="text-align:center;"> V. TOTAL</th>
                        <!--<th> BC ICMS</th> -->
                        <!--<th> V. ICMS</th> -->
                        <!--<th> V. IPI</th> -->
                        <!--<th> ALIQ. ICMS</th> -->
                        <!--<th> ALIQ. IPI</th> -->
                        <!--<th> BC ICMS RET</th> -->
                        <!--<th> V. ICMS RET</th> -->
                    </tr>
                ';
            
            for ($i = 0; $i < count($xml->NFe->infNFe->det); $i++) {

                //logic to test if the code was found and set the color of the <TR>
                $regPessoa = $this->busca_cliente(substr($xml->NFe->infNFe->emit->xNome, 0, 50), $xml->NFe->infNFe->emit->CNPJ);
                $regPessoa[0]['CLIENTE'] ? $regPessoa[0]['CLIENTE'] : null;

                //se exitir o codigo alterado faz a busca por ele
                if (isset($xml->NFe->infNFe->det[$i]->prod->cProdAlter)) {
                    $buscaProduto = $this->busca_produto_new(substr($xml->NFe->infNFe->det[$i]->prod->cProdAlter, 0, 25), $$regPessoa[0]['CLIENTE']);
                } else {
                    $buscaProduto = $this->busca_produto_new(substr($xml->NFe->infNFe->det[$i]->prod->cProd, 0, 25), $regPessoa[0]['CLIENTE']);
                }


                //se existir nota fiscal desabilita acoes do input e seta a cor vermelha para a <TR>
                if($this->existeNotaFiscal){
                    $this->m_input = 'none';
                    $this->m_color_tr =  '#ffb3c2cc';
                }else{
                    //se existir seta a cor de cada <TD> com a cor da consulta
                    if ($buscaProduto[0]['ORIGEM_CONSULTA'] == 'CODFABRICANTE_FABRICANTE') {
                        $this->m_color_tr = '#99e794eb';
                    } elseif ($buscaProduto[0]['ORIGEM_CONSULTA'] == 'CODFABRICANTE') {
                        $this->m_color_tr = '#efbf67';
                    } elseif ($buscaProduto[0]['ORIGEM_CONSULTA'] == 'EQUIVALENCIA') {
                        $this->m_color_tr = '#a5a1a1c7';
                    } else {
                        $this->m_color_tr = '#a5a1a100';
                    }
                    //habilita acoes do input
                    $this->m_input =  'all';
                }

                //########################## <TR> #################################
                $table .= '<tr style="background-color:' . $this->m_color_tr . ';" id="infoProd">';

                //END logic <TR>

                //se existir o codigo alterado no xml insere o valor alterado
                if (isset($xml->NFe->infNFe->det[$i]->prod->cProdAlter)) {
                    $input = '<input type="text" class="form-control" 
                    style="background-color: #fff0; padding:0; pointer-events: ' .$this->m_input. '"
                    id="codProd" name="codProd' . $xml->NFe->infNFe->det[$i]->prod->cProdAlter . '" 
                    value="' . $xml->NFe->infNFe->det[$i]->prod->cProdAlter . '" 
                    onchange="javascript:mudaCodProdXmlNew(this.value, `' .
                    $xml->NFe->infNFe->det[$i]->prod->cProd . '`)">';
                }else{
                    $input = '<input type="text" class="form-control" 
                    style="background-color: #fff0; padding:0; pointer-events: ' .$this->m_input. '"
                    id="codProd" name="codProd' . $xml->NFe->infNFe->det[$i]->prod->cProd . '" 
                    value="' . $xml->NFe->infNFe->det[$i]->prod->cProd . '" 
                    onchange="javascript:mudaCodProdXmlNew(this.value, `' . 
                    $xml->NFe->infNFe->det[$i]->prod->cProd . '`)">';
                }

                //########################## <TD> #################################
                $table .= '<td id="cProd" width="14.5%">' . $input . '</td>';
                $table .= '<td id="cProdXml" width="7%" name="cProdXml' . $xml->NFe->infNFe->det[$i]->prod->cProd . '">' . $xml->NFe->infNFe->det[$i]->prod->cProd . '</td>';
                $table .= '<td id="xProd">' . $xml->NFe->infNFe->det[$i]->prod->xProd . '</td>';
                $table .= '<td id="ncm" align="center" width="6%">' . $xml->NFe->infNFe->det[$i]->prod->NCM . '</td>';
                // echo '<td>'.$det_CST[$i].'</td>';
                $table .= '<td id="cfop" align="center" width="3.8%">' . $xml->NFe->infNFe->det[$i]->prod->CFOP . '</td>';
                $table .= '<td id="unidade" align="center" width="3%">' . $xml->NFe->infNFe->det[$i]->prod->uCom . '</td>';
                $table .= '<td id="quantidade" align="center" width="7%">' . $xml->NFe->infNFe->det[$i]->prod->qCom . '</td>';
                $table .= '<td id="vlrUnitario" width="8%" align="center">' . $xml->NFe->infNFe->det[$i]->prod->vUnTrib . '</td>';
                $table .= '<td id="vlrTotal" width="7%" align="center">' . $xml->NFe->infNFe->det[$i]->prod->vProd . '</td>';
                $table .= '<td id="cEan" hidden width="7%" align="center">' . $xml->NFe->infNFe->det[$i]->prod->cEAN . '</td>';
                $table .= '<td id="cEANTrib" hidden width="7%" align="center">' . $xml->NFe->infNFe->det[$i]->prod->cEANTrib . '</td>';
                //  echo '   <td>'.$det_vBCICMS[$i].'</td>';
                //  echo '   <td>'.$det_vICMS[$i].'</td>';                                
                //  echo '   <td>'.$det_vIPI[$i].'</td>';
                //  echo '   <td>'.$det_pICMS[$i].'</td>';
                //  echo '   <td>'.$det_pIPI[$i].'</td>';
                //  echo '   <td>'.$det_vBCSTRet[$i].'</td>';
                //  echo '   <td>'.$det_vICMSSTRet[$i].'</td>';
                $table .= '</tr>';
            }

            $table .= '</table></div>';

            return $table;
    }

    //---------------------------------------------------------------
    function mostraNota($mensagem, $tipoMsg = NULL, $tela = NULL)
    {
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('raizFonte', $this->raizFonte);
        $this->smarty->assign('sweetalert', ADMhttpBib . '/js/sweetalert2.all.min.js');


        $result = false;
        $cadastrar = 'false';
        $mensagem != '' ? $this->m_msg = $mensagem :  '';
        $this->smarty->assign('mensagem', $this->m_msg);

        //Obtendo info. dos arquivos
        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];
        /*
    if ($_POST['f_tmp'] != ""){
        $f_tmp = $_POST['f_tmp'];
    }
    */
        // Estancio o arquivo xml na variavel $xml que sera um objeto contendo o arquivo.
        //if (file_exists($_FILES['file']['tmp_name'])):

        if (file_exists($f_tmp)) :
            $xml = simplexml_load_file($f_tmp);

            if(isset($xml->infNFe->det)){
                for($i=0; $i < count($xml->infNFe->det); $i++){
                    $xml->infNFe->det[$i]->prod->xProd = str_replace(["'", '"'], '', $xml->infNFe->det[$i]->prod->xProd); 
                }
            }else{
                // Verifica se o elemento det existe antes de acessar seus atributos
                if (isset($xml->NFe->infNFe->det) && $xml->NFe->infNFe->det !== null) {
                    $count = $xml->NFe->infNFe->det->attributes()["nItem"];
                    if ($count && isset($count[0])) {
                        for($i=0; $i < $count[0]; $i++){
                            if (isset($xml->NFe->infNFe->det[$i]->prod->xProd)) {
                                $xml->NFe->infNFe->det[$i]->prod->xProd = str_replace(["'", '"'], '', $xml->NFe->infNFe->det[$i]->prod->xProd); 
                            }
                        }
                    }
                }
            }

            // filtra caracter especial.
            $nome = $xml->NFe->infNFe->emit->xNome;
            $xml->NFe->infNFe->emit->xNome = $this->removeChar($nome);
            if ($xml != false) {
                $xml_str = $xml->asXML();
                $this->smarty->assign('xml_arq', $xml_str);
                $result = $this->conferirNotaFiscal($xml);
            } else {
                $tipoMsg = 'alerta';
                $this->m_msg = "Falha ao importar xml. Arquivo xml invalido.";
                $this->smarty->assign('mensagem', $this->m_msg);
            }

        elseif ($this->xml_arq != '') :
            $this->smarty->assign('xml_arq', $this->xml_arq);

            libxml_use_internal_errors(true);

            $xml = simplexml_load_string($this->xml_arq);

            if ($xml === false) {
                echo "Erro ao carregar o XML:";
                foreach(libxml_get_errors() as $error) {
                    echo "<br>", $error->message;
                }
            }
            
            $result = $this->conferirNotaFiscal($xml);
        endif;

        $this->smarty->assign('url', ADMhttpCliente . "/index.php");
        $this->smarty->assign('pathImagem', $this->img);
        if ($result) :
            if ($_FILES['file']['name'] == $this->m_name) :
                $this->smarty->assign('cadastrar', true);
            endif;
        else :
            $this->smarty->assign('cadastrar', $cadastrar);
        endif;
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('tmp_name', $this->m_name);
        $this->smarty->assign('file', $_FILES['file']['name']);
        $this->smarty->assign('tempFile', $_FILES['file']['name'] == '' ? $this->xml_name : $_FILES['file']['name']);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('nota_fiscal_div', $this->m_nota_fiscal_div);
        $this->smarty->assign('existeNotaFiscal', $this->existeNotaFiscal);
        $this->smarty->assign('idNf', $this->m_idNf);

        //$permiteAlterarFinanceiroXML = $this->verificaDireitoUsuario('PEDPERMITEALTERARFINANCEIROXML', 'S', 'N');
        //$this->smarty->assign('permiteAlterarFinanceiroXML', $permiteAlterarFinanceiroXML);

        // ########## NATUREZA OPERACAO ##########
        $consulta = new c_banco();
        $sql = "select id, natoperacao as descricao from est_nat_op where tipo='E' order by natoperacao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $natOperacao_ids[$i] = $result[$i]['ID'];
            $natOperacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('natOperacao_ids', $natOperacao_ids);
        $this->smarty->assign('natOperacao_names', $natOperacao_names);
        $this->smarty->assign('natOperacao_id', $this->natOper);

        if ($tela == 'FINANCEIRO') {

            // COMBOBOX CONDICAO PAGAMENTO
            $consulta = new c_banco();
            $sql = "SELECT * FROM fat_cond_pgto;";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            $condPgto_ids[0] = 0;
            $condPgto_names[0] = 'Selecione';
            for ($i = 1; $i < count($result); $i++) {
                if ($this->m_par[6] != '') {
                    if ($this->m_par[6] == $result[$i]['ID']) {
                        $descCondPgto = $result[$i]['DESCRICAO'];
                    }
                }
                $condPgto_ids[$i] = $result[$i]['ID'];
                $condPgto_names[$i] = $result[$i]['DESCRICAO'];
            }
            $this->smarty->assign('condPgto_ids', $condPgto_ids);
            $this->smarty->assign('condPgto_names', $condPgto_names);


            // COMBOBOX GENERO
            $consulta = new c_banco();
            $sql = "SELECT GENERO AS ID, DESCRICAO FROM fin_genero where (tipolancamento = 'P') ORDER BY descricao;";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            for ($i = 0; $i < count($result); $i++) {
                $genero_ids[$i] = $result[$i]['ID'];
                $genero_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
            }
            $this->smarty->assign('genero_ids', $genero_ids);
            $this->smarty->assign('genero_names', $genero_names);
            $this->smarty->assign('genero_id', $this->getGenero());

            // COMBOBOX CONTA
            $consulta = new c_banco();
            $sql = "SELECT conta as id, nomeinterno as descricao FROM fin_conta where status ='A' ORDER BY nomeinterno;";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            for ($i = 0; $i < count($result); $i++) {
                $conta_ids[$i] = $result[$i]['ID'];
                $conta_names[$i] = $result[$i]['DESCRICAO'];
            }
            $this->smarty->assign('conta_ids', $conta_ids);
            $this->smarty->assign('conta_names', $conta_names);

            // TIPO DOCUMENTO
            $consulta = new c_banco();
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='TipoDoctoPgto') ";
            $sql .= "and (( tipo = 'B') or ( tipo = 'D') or ( tipo = 'C') or ( tipo = 'E') or ( tipo = 'A') or ( tipo = 'K'))";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            for ($i = 0; $i < count($result); $i++) {
                $tipoDocto_ids[$i] = $result[$i]['ID'];
                $tipoDocto_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
            }
            $this->smarty->assign('tipoDocto_ids', $tipoDocto_ids);
            $this->smarty->assign('tipoDocto_names', $tipoDocto_names);
            $this->smarty->assign('tipoDocto_id', 'B');

            // SITUACAO LANCAMENTO
            $consulta = new c_banco();
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto')";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            for ($i = 0; $i < count($result); $i++) {
                $situacaoLanc_ids[$i] = $result[$i]['ID'];
                $situacaoLanc_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
            }
            $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
            $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);
            $this->smarty->assign('situacaoLanc_id', 'A');

            // COMBOBOX CENTROCUSTO
            $consulta = new c_banco();
            $sql = "SELECT CentroCusto AS ID, DESCRICAO FROM fin_centro_custo ORDER BY CentroCusto";
            $consulta->exec_sql($sql);
            $consulta->close_connection();
            $result = $consulta->resultado;
            for ($i = 0; $i < count($result); $i++) {
                $centroCusto_ids[$i] = $result[$i]['ID'];
                $centroCusto_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
            }
            $this->smarty->assign('centroCusto_ids', $centroCusto_ids);
            $this->smarty->assign('centroCusto_names', $centroCusto_names);
            $this->smarty->assign('centroCusto_id', $this->m_empresacentrocusto);

            if ($this->m_par[0] != "") {
                $this->smarty->assign('numero', $this->m_par[0]);
            }
            if ($this->m_par[1] != "") {
                $this->smarty->assign('data', $this->m_par[1]);
            }
            if ($this->m_par[2] != "") {
                $this->smarty->assign('total', $this->m_par[2]);
            }
            if ($this->m_par[3] != "") {
            }
            if ($this->m_par[4] != "") {
                $this->smarty->assign('serie', $this->m_par[4]);
            }
            if ($this->m_par[5] != "") {
                $this->smarty->assign('natOperacao_id', $this->m_par[5]);
            }
            if ($this->m_par[6] != "") {
                $this->smarty->assign('condPgto_id', $this->m_par[6]);
                $fin = $this->calculaParcelasNfe(
                    $descCondPgto,
                    str_replace(",", ".", $this->m_par[2]),
                    $this->numParcelaAdd
                );
                $this->smarty->assign('fin', $fin);
                $this->smarty->assign('numParcelaAdd', $this->numParcelaAdd);
                $regPessoa = $this->busca_cliente(substr($item->infNFe->emit->xNome, 0, 50), $this->cnpj);
                $teste_array = is_array($regPessoa);
                if ($teste_array) {
                    $this->smarty->assign('fornecedorNome', $regPessoa[0]['NOME']);
                    $this->smarty->assign('cnpj', $this->cnpj);
                }
            }
            if ($this->m_par[7] != "") {
                $this->smarty->assign('centroCusto_id', $this->m_par[7]);
            }
            if ($this->m_par[8] != "") {
                $this->smarty->assign('genero_id', $this->m_par[8]);
            }

            foreach ($xml->NFe as $key => $item) {
                if (isset($item->infNFe)) {
                    $cnpj = $item->infNFe->emit->CNPJ;
                    $regPessoa = $this->busca_cliente(substr($item->infNFe->emit->xNome, 0, 50), $cnpj);
                    $teste_array = is_array($regPessoa);
                    if ($teste_array) {
                        $this->smarty->assign('fornecedorNome', $regPessoa[0]['NOME']);
                        $this->smarty->assign('cnpj', $cnpj);
                    } // teste cliente

                    $this->smarty->assign('numero', $item->infNFe->ide->nNF);
                    $this->smarty->assign('data', $item->infNFe->ide->dhEmi);
                    $this->smarty->assign('serie', $item->infNFe->ide->serie);
                    $total = $item->infNFe->total->ICMSTot->vNF;
                    $total = str_replace(".", ",", $total);
                    $this->smarty->assign('total', $total);
                    if (count($item->infNFe->cobr->dup) > 0) {
                        for ($i = 0; $i < count($item->infNFe->cobr->dup); $i++) {
                            $nDup = $item->infNFe->cobr->dup[$i]->nDup;
                            $fin[$i]['PARCELA'] = trim($nDup);
                            $dVenc = $item->infNFe->cobr->dup[$i]->dVenc;
                            $fin[$i]['VENCIMENTO'] = trim($dVenc); // date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval($parcelas[$i])." day"));
                            $vDup = $item->infNFe->cobr->dup[$i]->vDup;
                            //OLD
                            //$fin[$i]['VALOR'] = str_replace(".", ",", trim($vDup));

                            //NEW
                            $fin[$i]['VALOR'] =number_format((double) $vDup, 2, ',', '.');
                        }
                    } else {
                        for ($i = 0; $i < count($item->infNFe->pag->detPag); $i++) {
                            $nDup = $item->infNFe->pag->detPag->indPag;
                            $fin[$i]['PARCELA'] = trim($nDup);
                            $fin[$i]['VENCIMENTO'] = date("Y-m-d");
                            $vDup = $item->infNFe->pag->detPag->vPag;

                            //$fin[$i]['VALOR'] = str_replace(".", ",", trim($vDup));
                            $fin[$i]['VALOR'] =number_format((double) $vDup, 2, ',', '.');
                        }
                    }

                    $this->smarty->assign('condPgto_id', 0);
                    $this->smarty->assign('fin', $fin);
                    $this->smarty->assign('numParcelaAdd', 0);
                }
            }

            $this->smarty->display('nota_xml_importa_financeiro.tpl');
        } else {
            if (file_exists($_FILES['file']['tmp_name'])) {
                /*       
        $teste = str_replace('.tmp', random_int(100, 999).'.tmp' , $_FILES['file']['tmp_name']);
        
        $_FILES['file']['tmp_name'] = $teste;

        //$this->smarty->assign('f_tmp',$this->$teste);
        
        $this->smarty->assign('f_tmp',$_FILES['file']['tmp_name']);

        $this->smarty->assign('f_name', $this->$f_name);
        $this->smarty->assign('f_type)', $this->$f_type);
        
        $arquivo = fopen($teste,'w');
        $info = $xml->asXML();
        fwrite($arquivo, $info);
        fclose($arquivo);
        */
                $this->smarty->assign('f_tmp', $_FILES['file']['tmp_name']);
                $this->smarty->assign('f_name', $_FILES['file']['name']);
                $this->smarty->assign('f_type', $_FILES['file']['type']);
            }

            if (($xml == false) and (file_exists($f_tmp))) {
                $this->m_msg = "Arquivo XML com erro de leitura, selecionar novamente XML para Cadastrar ou Visualizar!!";
                $this->smarty->assign('mensagem', $this->m_msg);
                $this->smarty->assign('tipoMsg', 'alerta');
            }
            $this->smarty->display('nota_xml_importa.tpl');
            // $xml = $this->xml_arq;
            if ($xml != false) {
                if (file_exists($_FILES['file']['tmp_name'])) {
                    $this->mostraNotaFiscalXML1($xml);
                } elseif ($this->xml_arq != "") {
                    $this->mostraNotaFiscalXML1($xml);
                }
            }
        }
    } //fim nota_xml_importa
    //-------------------------------------------------------------
}    //	END OF THE CLASS

// Rotina principal - cria classe
$notaXml = new p_nota_xml_importa(
    $_POST['submenu'],
    $_POST['letra']
);


if (isset($_POST['tempFile'])) {
    $notaXml->m_tmp = $_POST['tempFile'];
} else {
    $notaXml->m_tmp = '';
};
if (isset($_FILES['file'])) {
    $notaXml->m_name = $_FILES['file']['name'];
} else {
    $notaXml->m_name = '';
};
if (isset($_FILES['file'])) {
    $notaXml->m_tmp = $_FILES['file']['tmp_name'];
} else {
    $notaXml->m_tmp = '';
};
if (isset($_FILES['file'])) {
    $notaXml->m_type = $_FILES['file']['type'];
} else {
    $notaXml->m_type = '';
};
if (isset($_FILES['file'])) {
    $notaXml->m_size = $_FILES['file']['size'];
} else {
    $notaXml->m_size = '';
};

$f_name = $_FILES['file']['name'];
$f_tmp = $_FILES['file']['tmp_name'];
$f_type = $_FILES['file']['type'];

$notaXml->controle();


?>