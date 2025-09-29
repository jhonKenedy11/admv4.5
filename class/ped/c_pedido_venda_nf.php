<?php
/**
 * @package   astecv3
 * @name      c_pedido_venda_nf
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      06/12/2016
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../class/ped/c_pedido_venda.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_database_pdo.php");

//Class 
Class c_pedidoVendaNf extends c_pedidoVenda {
    // Propriedades para armazenar os resultados dos cálculos de impostos

    //ICMS
    private $icms_base_calculo = 0;
    private $icms_valor = 0;
    private $icms_diferido_valor = 0;
    private $icms_operacao_valor = 0;
    private $tributo_icms_saida = NULL;
    private $modalidade_calculo = NULL;
    private $item_estoque = 0;
    private $icms_aliq = 0;
    private $reducao_base_calculo_perc = 0;
    private $icms_base_calculo_simples_nacional = 0;
    private $credito_simples_nacional_aliq = 0;
    private $credito_icms_simples_nacional_valor = 0;

    // ST
    private $icms_st_valor = 0;
    private $icms_st_aliq = 0;
    private $base_calculo_st_retido_valor = 0;
    private $base_calculo_st_valor = 0;
    private $icms_st_retido_valor = 0;
    private $icms_valor_st_retido = 0;
    private $modalidade_calculo_st = 0;
    private $mva_st = 0;
    private $reducao_base_calculo_st_perc = 0;

    // IPI
    private $ipi_cst = NULL;
    private $ipi_aliq = 0;
    private $ipi_valor = 0;
    private $inside_ipi_base = 0;

    // PIS
    private $pis_cst = NULL;
    private $pis_base_calculo = 0;
    private $pis_valor = 0;
    private $pis_aliq = 0;

    // COFINS
    private $cofins_cst = NULL;
    private $cofins_base_calculo = 0;
    private $cofins_valor = 0;
    private $cofins_aliq = 0;

    // OUTROS
    private $origem = NULL;
    private $cfop = NULL;
    private $diferimento_perc = 0;
    private $produto_quantidade = 0;
    private $produto_desconto = 0;
    private $produto_valor = 0;
    private $produto_aliq = 0;
    private $frete_valor = 0;
    private $desp_acessoria_valor = 0;
    private $crt = NULL;
    private $ncm = NULL;
    private $cest = NULL;
    private $total_tributo = 0;
    private $codigo_beneficiario = NULL;
    
  

    //construtor
    function __construct(){
            // session_start();
            // Cria uma instancia variaveis de sessao
            c_user::from_array($_SESSION['user_array']);
    }


/**
* METODOS DE SETS E GETS
*/

//############### FIM SETS E GETS ###############


/**
 * <b> Calcaula as parcelas para ser lancadas no financeiro, a primeira parcela o valor é ajusto para fechar com o total da NF </b>
 * @name calculaParcelasNfe
 * @param VARCHAR condPgto
 * @param int total
 * @return Matriz com as datas de vencimento e valores de cada parcela.
 */
public function calculaParcelasNfe($condPgto = NULL, $total = 0, $acrescentarParcela = 0, $bonus = 0){
    $consulta = new c_banco();
    $sql = "select PARCELA, VENCIMENTO, TOTAL as VALOR, SITPGTO ,IF (SITPGTO = 'B', 'BAIXADO', '') AS SITPAG FROM FIN_LANCAMENTO WHERE DOCTO = '" . $this->getPedido() . "' AND ORIGEM = 'PED' AND SITPGTO = 'B'";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $parcsBaixada = $consulta->resultado;
    $totalParcs = 0;
    foreach($parcsBaixada as $key => $value){
        $totalParcs += $value['VALOR'];
    }
    $totalNumParcelas = 0;

    //setlocale(LC_MONETARY, 'en_US');
    $descCondPgto = str_replace('DIAS', '', $condPgto);
    $parcelas = explode("/", $condPgto);
    $numParcelas = count($parcelas);
    $totalGeral = $total - $bonus;
    //diminui o valor das parcelas pagas
    if ($totalParcs > 0){
        $totalGeral -= $totalParcs;
    }
    if ($totalGeral > 0 ) {
    //$valorParcela = money_format('%i', $totalGeral / $numParcelas);
    //$valorParcela =  str_replace(number_format(($totalGeral / $numParcelas),2),',','');
    $valorParcela =  round($totalGeral / $numParcelas, 2, PHP_ROUND_HALF_DOWN); 
    if ($acrescentarParcela > 0 ){
        $totalNumParcelas += $acrescentarParcela;
    }
    if ($bonus > 0){
        $totalNumParcelas += 1;        
    }
    $totalNumParcelas += $numParcelas;
    if ($totalGeral == 0){
        $totalNumParcelas = 1;        
    }
    
        // Se formaPgto não foi passado, buscar da condição de pagamento como fallback
        $formaPgto = new c_banco();
        $formaPgto->setTab('FAT_COND_PGTO');
        $formaPgto = $formaPgto->getField('FORMAPGTO', 'ID='.$this->getCondPg());

        // validacao se tem dias ou é só joga pro dia posterior, contra empenho bianco.
        foreach ($parcelas as $parcela) {
            if (preg_match('/\d+/', $parcela)) {
                $temNumeros = true;
                break;
            }
        }
    

        for ($i = 0; $i < $totalNumParcelas; $i++) {
        if ($formaPgto == "1" && $numParcelas == 1 && $temNumeros == false) {
            $lanc[0]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . " + 1 day"));
            $lanc[0]['VALOR'] = $totalGeral;
            $lanc[0]['TIPODOCTO_ID'] = '';
            $lanc[0]['PARCELA'] = 1;
        } else if ( ($i == 0) and ($bonus > 0) ) {
            $lanc[$i]['PARCELA'] = $i + 1;
            $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval(0)." day"));
            $lanc[$i]['VALOR'] = $bonus;  
            $lanc[$i]['TIPODOCTO_ID'] = 'N';
        } else if ($i <= $numParcelas) {
            $lanc[$i]['PARCELA'] = $i + 1;
            $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval($parcelas[$i])." day"));
            $lanc[$i]['VALOR'] = $valorParcela; 
            $lanc[$i]['TIPODOCTO_ID'] = '';   
        } else {
            $lanc[$i]['PARCELA'] = $i + 1;
            $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval($parcelas[$numParcelas - 1])." day"));
            $lanc[$i]['VALOR'] = 0;    
            $lanc[$i]['TIPODOCTO_ID'] = '';
        }
    }

    //$lanc[0]['VALOR'] = $valorParcela - (($valorParcela * $numParcelas) - doubleval($totalGeral));
    if (($valorParcela * $numParcelas) < doubleval($totalGeral)){
        $dif = (doubleval($totalGeral) - ($valorParcela * $numParcelas)) ;
        $lanc[$totalNumParcelas - 1]['VALOR'] +=  $dif;    
    }else if (($valorParcela * $numParcelas) > doubleval($totalGeral)){
        $dif = (($valorParcela * $numParcelas) - doubleval($totalGeral)) ;
        $lanc[$totalNumParcelas - 1]['VALOR'] -=  $dif;    
    }    
    //$lanc[0]['VALOR'] = str_replace(".", ",",$lanc[0]['VALOR']);
    //return $lanc;
    } else if ($bonus > 0) {
        $i = 0;
        $lanc[$i]['PARCELA'] = $i + 1;
        $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval(0)." day"));
        $lanc[$i]['VALOR'] = $bonus;  
        $lanc[$i]['TIPODOCTO_ID'] = 'N';
    }
    
    $newLanc[] = '';
    $count = 0;
    $parcsBaixada = $parcsBaixada ?? [];
    for($k=0; $k < count($parcsBaixada); $k++){
        if($newLanc[0] == ''){
            $newLanc[$k] = $parcsBaixada[$k];
        }else{
            array_push($newLanc, $parcsBaixada[$k]);
        }
        $count += 1;
    }

    if ($count > 0) {
        for($l = 0; $l < count($lanc); $l++){
            $newLanc[$count] = $lanc[$l];
            $count += 1;
            //array_push($newLanc[$count+=1], $lanc[$l]);
        }
        return $newLanc;
    } else {
        return $lanc;
    }

    
    
}

    /**
     * <b> Calcaula as parcelas para ser lancadas no financeiro, a primeira parcela o valor é ajusto para fechar com o total da NF </b>
     * @name calculaParcelasNfe
     * @param VARCHAR condPgto
     * @param int total
     * @return Matriz com as datas de vencimento e valores de cada parcela.
     */
    public function calculaParcelasAlteraPed($condPgto = NULL, $total = 0, $acrescentarParcela = 0, $bonus = 0){
        
        //PESQUISA SE TEM PARCELAS BAIXADAS FINANCEIRO
        $consulta = new c_banco();
        $sql = "select PARCELA, VENCIMENTO, TOTAL as VALOR, SITPGTO ,IF (SITPGTO = 'B', 'BAIXADO', '') AS SITPAG FROM FIN_LANCAMENTO WHERE DOCTO = '".$this->getPedido()."' AND ORIGEM = 'PED' AND SITPGTO = 'B'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $parcsBaixada = $consulta->resultado;
        $totalParcs = 0;
        foreach($parcsBaixada as $key => $value){
            $totalParcs += $value['VALOR'];
        }
        $totalNumParcelas = 0;
        //setlocale(LC_MONETARY, 'en_US');
        $descCondPgto = str_replace('DIAS', '', $condPgto);
        $parcelas = explode("/", $condPgto);
        $numParcelas = count($parcelas);
        if ($acrescentarParcela > 0 ){
            $totalNumParcelas += $acrescentarParcela;
        }
        $totalNumParcelas += $numParcelas;
        $totalGeral = $total - $bonus - $totalParcs;
        //$valorParcela = money_format('%i', $totalGeral / $numParcelas);
        //$valorParcela =  str_replace(number_format(($totalGeral / $numParcelas),2),',','');
        $valorParcela =  round($totalGeral / $totalNumParcelas, 2, PHP_ROUND_HALF_DOWN); 
        //if ($acrescentarParcela > 0 ){
        //    $totalNumParcelas += $acrescentarParcela;
        //}
        if ($bonus > 0){
            $totalNumParcelas += 1;        
        }
        //$totalNumParcelas += $numParcelas;
        if ($totalGeral == 0){
            $totalNumParcelas = 1;        
        }
        $parcsBaixada != 0 ? $counter = (count($parcsBaixada) + 1) : $counter = 1;
        for ($i = 0; $i < $totalNumParcelas; $i++) {
            if ( ($i == 0) and ($bonus > 0) ) {
                $lanc[$i]['PARCELA'] = $i + $counter;
                $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval(0)." day"));
                $lanc[$i]['VALOR'] = $bonus;  
                $lanc[$i]['TIPODOCTO_ID'] = 'N';
            } else if ($i <= $totalNumParcelas) {
                $lanc[$i]['PARCELA'] = $i + $counter;
                $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval($parcelas[$i])." day"));
                $lanc[$i]['VALOR'] = $valorParcela; 
                $lanc[$i]['TIPODOCTO_ID'] = '';   
            } else {
                $lanc[$i]['PARCELA'] = $i + $counter;
                $lanc[$i]['VENCIMENTO'] = date("Y-m-d", strtotime(date("Y-m-d") . "  + ".intval($parcelas[$numParcelas - 1])." day"));
                $lanc[$i]['VALOR'] = 0;    
                $lanc[$i]['TIPODOCTO_ID'] = '';
            }
        }
        $newLanc[] = '';
        $count = 0;
        for($k=0; $k < count($parcsBaixada); $k++){
            if($newLanc[0] == ''){
                $newLanc[$k] = $parcsBaixada[$k];
            }else{
                array_push($newLanc, $parcsBaixada[$k]);
            }
            $count += 1;
        }

        for($l = 0; $l < count($lanc); $l++){
            $newLanc[$count] = $lanc[$l];
            $count += 1;
            //array_push($newLanc[$count+=1], $lanc[$l]);
        }

        
        return $newLanc;
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
     * https://blog.tecnospeed.com.br/como-calcular-o-icms-na-nf-e-e-nfc-e/
     */
    public function calculaImpostosNfe($dadosItem, $natOp = NULL, $uf=NULL, $tipoPessoa=NULL, $centroCusto=NULL, $apenas_calculo = NULL)
    {
        $arrTributos = [];
        $dados       = [];

        // busca CRT do cliente
        $this->crt = $this->_buscaCrt($centroCusto);

        if($apenas_calculo === true)
        {
            // Modo array (para relatórios)
            $this->desp_acessoria_valor = $dadosItem['despAcessorias'] ?? 0;
            $this->tributo_icms_saida   = $dadosItem['tribIcms'] ?? '';
            $this->item_estoque         = $dadosItem['item_estoque'] ?? 0;
            $this->produto_desconto     = $dadosItem['desconto'] ?? 0;
            $this->produto_valor        = $dadosItem['produto_valor'] ?? 0;
            $this->total_tributo        = $dadosItem['total'] ?? 0;
            $this->frete_valor          = $dadosItem['frete'] ?? 0;
            $this->origem               = $dadosItem['origem'] ?? '';
            $this->ncm                  = $dadosItem['ncm'] ?? '';
            $this->cest                 = $dadosItem['cest'] ?? '';
            $this->produto_quantidade   = $dadosItem['quantidade'] ?? 0;


        } else {
            // Modo objeto (comportamento original)
            $objNfProd                  = $dadosItem;
            $this->desp_acessoria_valor = $objNfProd->getDespAcessorias('B');
            $this->tributo_icms_saida   = $objNfProd->getTribIcms();
            $this->item_estoque         = $objNfProd->getCodProduto();
            $this->produto_desconto     = $objNfProd->getDesconto('B');
            $this->produto_valor        = $objNfProd->getTotal('B');
            $this->total_tributo        = $objNfProd->getTotal('B');
            $this->frete_valor          = $objNfProd->getFrete('B');
            $this->origem               = $objNfProd->getOrigem();
            $this->ncm                  = $objNfProd->getNcm();
            $this->cest                 = $objNfProd->getCest();
            $this->produto_quantidade   = $objNfProd->getQuant('B'); 
        }

        //monta array com parametros para pesquisa
        $dados = array (
            "uf" => $uf,
            "naturezaOperacao" => $natOp,
            "centroCusto" => $centroCusto,
            "tipoPessoa" => $tipoPessoa,
            "tribIcms" => $this->tributo_icms_saida,
            "origem" => $this->origem,
            "cest" => $this->cest,
            "ncm" => $this->ncm,
            "produto" => $this->item_estoque,
        );

        // Busca tributos
        $arrTributos = $this->_buscaTributos($dados);

        // Testa tributos e alimenta variaves
        if ($arrTributos){

            // Popula as variaveis bases
            switch ($this->crt) {
                case '1': // Simples Nacional
                    $this->icms_base_calculo   = 0;
                    $this->pis_base_calculo    = 0;
                    $this->cofins_base_calculo = 0;
                    break;
                case '2': // Simples Nacional – Excesso de Sublimite da Receita Bruta
                    $this->icms_base_calculo   = ($this->total_tributo + $this->frete_valor + $this->desp_acessoria_valor) - $this->produto_desconto;
                    $this->pis_base_calculo    = ($this->total_tributo + $this->frete_valor + $this->desp_acessoria_valor) - $this->produto_desconto;
                    $this->cofins_base_calculo = ($this->total_tributo + $this->frete_valor + $this->desp_acessoria_valor) - $this->produto_desconto;
                    break;
                case '3': // Normal
                    $this->icms_base_calculo   = ($this->total_tributo + $this->frete_valor + $this->desp_acessoria_valor) - $this->produto_desconto;
                    $this->pis_base_calculo    = ($this->total_tributo + $this->frete_valor + $this->desp_acessoria_valor) - $this->produto_desconto;
                    $this->cofins_base_calculo = ($this->total_tributo + $this->frete_valor + $this->desp_acessoria_valor) - $this->produto_desconto;
                    break;
            }


            // Popula as variaveis locais

            //ICMS
            $this->tributo_icms_saida            = $arrTributos[0]['TRIBICMSSAIDA']; // Defini aqui para usar como parametro na funcao _calculaBlocoIcms();
            $this->modalidade_calculo            = $arrTributos[0]['MODBC'];
            $this->icms_aliq                     = (float) $arrTributos[0]['ALIQICMS'];
            $this->reducao_base_calculo_perc     = (float) $arrTributos[0]['PERCREDUCAOBC'];
            $this->credito_simples_nacional_aliq = (float) $arrTributos[0]['PRECCREDITOSIMPLES'];
            $this->diferimento_perc              = (float) $arrTributos[0]['PERCDIFERIDO'];

            // ST
            $this->reducao_base_calculo_st_perc = (float) $arrTributos[0]['PERCREDUCAOBCST'];
            $this->icms_st_aliq                 = (float) $arrTributos[0]['ALIQICMSST'];
            $this->mva_st                       = (float) $arrTributos[0]['MVAST'];

            // IPI
            $this->ipi_aliq        = (float) $arrTributos[0]['ALIQIPI'];
            $this->inside_ipi_base = $arrTributos[0]['INSIDEIPIBC'];

            // PIS
            $this->pis_cst  = $arrTributos[0]['CSTPIS']; // Defini aqui para usar como parametro na funcao _calculaBlocoPis();
            $this->pis_aliq = (float) $arrTributos[0]['ALIQPIS'];

            // COFINS
            $this->cofins_cst  = $arrTributos[0]['CSTCOFINS']; // Defini aqui para usar como parametro na funcao _calculaBlocoCofins();
            $this->cofins_aliq = (float) $arrTributos[0]['ALIQCOFINS'];

            // Outros
            $this->codigo_beneficiario = $arrTributos[0]['CBENEF'];
            $this->cfop = $arrTributos[0]['CFOP'];


            
            //o montante do IPI:
            // 1 - não integra a BC do ICMS quando o produto for destinado a posterior comercialização, industrialização ou outra saída tributada;
            // 2 - integra a BC do ICMS quando o produto for destinado a consumidor final, ativo imobilizado 

            if ($this->ipi_aliq > 0){

                $this->ipi_valor = ($this->ipi_aliq / 100) * $this->total_tributo;

                if ($this->inside_ipi_base == "S"){
                    $this->icms_base_calculo += $this->ipi_valor;
                }
            }

            // CALCULO ICMS
            $this->_calculaBlocoIcms();

            // CALCULO PIS
            $this->_calculaBlocoPis();

            //CALCULO COFINS
            $this->_calculaBlocoCofins();
            
            

            if ($apenas_calculo === true) {

                return [
                    'success' => true,
                    'tributos' => $arrTributos[0],
                    'valores' => [

                        // INFOS PROD
                        'cfop' => $this->cfop,
                        'origem' => $this->origem,

                        //ICMS
                        'icmsSaida' => $this->tributo_icms_saida,
                        'bcIcms' => $this->icms_base_calculo,
                        'icms_aliq' => $this->icms_aliq,
                        'vlIcms' => $this->icms_valor,
                        'vlIcmsDiferido' => $this->icms_diferido_valor,
                        'vlIcmsOperacao' => $this->icms_operacao_valor,
                        'vCredICMSSN' => $this->credito_icms_simples_nacional_valor,

                        // ICMS ST
                        'vlBcSt' => $this->base_calculo_st_valor,
                        'icms_st_aliq' => $this->icms_st_aliq,
                        'icms_base_calculo_st_retido_valor' => $this->icms_calculo_st,
                        'vlIcmsSt' => $this->icms_st_valor,
                        

                        //IPI
                        
                        'ipi_cst' => $this->ipi_cst,
                        'ipi_aliq' => $this->ipi_aliq,
                        'vlIpi' => $this->ipi_valor,

                        // PIS
                        'pis_cst' => $this->pis_cst,
                        'bcPis' => $this->pis_base_calculo,
                        'pis_aliq' => $this->pis_aliq,
                        'vlPis' => $this->pis_valor,

                        // COFINS
                        'cofins_cst' => $this->cofins_cst,
                        'bcCofins' => $this->cofins_base_calculo,
                        'cofins_aliq' => $this->cofins_aliq,
                        'vlCofins' => $this->cofins_valor

                    ]
                ];

                
            } else {

                // OTS
                $objNfProd->setCfop($this->cfop); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setCBenef($this->codigo_beneficiario); // <- ANTIGO PROCESSO INICIO DO ARQUIVO

                // ICMS
                $objNfProd->setTribIcms($this->tributo_icms_saida); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setModBc($this->modalidade_calculo); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setBcIcms($this->icms_base_calculo, true); // <- ANTIGO PROCESSO
                $objNfProd->setAliqIcms($this->icms_aliq, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setValorIcms($this->icms_valor, true); // <- ANTIGO PROCESSO
                $objNfProd->setVCredICMSSN($this->credito_icms_simples_nacional_valor, true); // <- ANTIGO PROCESSO
                $objNfProd->setValorIcmsDiferido($this->icms_diferido_valor, true); // <- ANTIGO PROCESSO
                $objNfProd->setValorIcmsOperacao($this->icms_operacao_valor, true); // <- ANTIGO PROCESSO
                $objNfProd->setPCredSN($this->credito_simples_nacional_aliq, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setPercReducaoBc($this->reducao_base_calculo_perc, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setPercDiferido($this->diferimento_perc, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO

                // ICMS ST
                $objNfProd->setValorBcSt($this->base_calculo_st_valor, true); // <- ANTIGO PROCESSO
                $objNfProd->setValorIcmsSt($this->icms_st_valor, true); // <- ANTIGO PROCESSO
                $objNfProd->setModBcSt($this->modalidade_calculo_st); // <- ANTIGO PROCESSO
                $objNfProd->setPercMvaSt($this->mva_st, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setPercReducaoBcSt($this->reducao_base_calculo_st_perc, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setAliqIcmsSt($this->icms_st_aliq, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO

                // IPI
                $objNfProd->setValorIpi($this->ipi_valor); // <- ANTIGO PROCESSO metade DO ARQUIVO
                $objNfProd->setAliqIpi($this->ipi_aliq, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setInsideIpiBc($this->inside_ipi_base); // <- ANTIGO PROCESSO INICIO DO ARQUIVO

                // PIS
                $objNfProd->setCstPis($this->pis_cst); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setBcPis($this->pis_base_calculo, true); // <- ANTIGO PROCESSO
                $objNfProd->setAliqPis($this->pis_aliq, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setValorPis($this->pis_valor, true); // <- ANTIGO PROCESSO

                // COFINS
                $objNfProd->setCstCofins($this->cofins_cst); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setAliqCofins($this->cofins_aliquota, true);  // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setBcCofins($this->cofins_base_calculo, true); // <- ANTIGO PROCESSO
                $objNfProd->setValorCofins($this->cofins_valor, true); // <- ANTIGO PROCESSO   
            }

            return true;

        } else {

            return $apenas_calculo ? ['success' => false, 'error' => 'Tributos não encontrados'] : false;
        }

    } //fim calculaImpostos

    public function _buscaTributos (array $dados)
    {   
        // Monta a consulta de tributos com parâmetros preparados
        $sql  = "SELECT N.PRECCREDITOSIMPLES, T.* 
                FROM EST_NAT_OP_TRIBUTO T
                INNER JOIN EST_NAT_OP N ON N.ID = T.IDNATOP
                WHERE T.CENTROCUSTO = :centroCusto
                AND T.IDNATOP = :naturezaOperacao
                AND T.UF = :uf
                AND T.PESSOA = :tipoPessoa";
        
        $banco = new c_banco_pdo();

        $params = [
            ':centroCusto' => $dados['centroCusto'],
            ':naturezaOperacao' => $dados['naturezaOperacao'],
            ':uf' => $dados['uf'],
            ':tipoPessoa' => $dados['tipoPessoa'],
            ':produto' => $dados['produto']
            
        ];

        // Campos opcionais
        if ($dados['origem'] !== '') {
            $sql .= " AND T.ORIGEM = :origem";
            $params[':origem'] = $dados['origem'];
        }
        if ($dados['tribIcms'] !== '') {
            $sql .= " AND T.TRIBICMS = :tribIcms";
            $params[':tribIcms'] = $dados['tribIcms'];
        }
        if ($dados['ncm'] !== '') {
            $sql .= " AND (T.NCM = :ncm OR T.NCM = '' OR T.NCM IS NULL)";
            $params[':ncm'] = $dados['ncm'];
        }
        if ($dados['cest'] !== '') {
            $sql .= " AND (T.CEST = :cest OR T.CEST = '' OR T.CEST IS NULL)";
            $params[':cest'] = $dados['cest'];
        }

        if ($dados['produto'] !== 0 && $dados['produto'] !== '') {
           $sql .= " AND (T.PRODUTO = :produto OR T.PRODUTO = '' OR T.PRODUTO IS NULL )";
           $params[':produto'] = $dados['produto'];
        }

        $sql .= " ORDER BY T.NCM DESC";

        // Prepara e executa a consulta
        $banco->prepare($sql);
        $banco->execute($params);

        return $banco->fetchAll(PDO::FETCH_ASSOC);
    }

    public function _buscaCrt(string $centroCusto)
    {
        // Busca o regime tributário da empresa
        $sql = "SELECT REGIMETRIBUTARIO FROM AMB_EMPRESA WHERE CENTROCUSTO = :centroCusto";
        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindParam('centroCusto', $centroCusto);
        $banco->execute();

        $crt = $banco->fetch(PDO::FETCH_ASSOC);

        //Seta o CRT
        if(is_array($crt)){
            return $crt['REGIMETRIBUTARIO'];
        }
    }


    private function _calculaBlocoIcms()
    {

        switch ($this->tributo_icms_saida){
            case '00': // tributado integralmente

                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;

                break;
            case '10': // Tributada e com cobrança do ICMS por substituição tributária

                // Calcula o ICMS da operação própria
                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;

                //Define a base de partida para o cálculo do ST.
                $this->base_calculo_st_valor = $this->icms_base_calculo;

                // Adiciona o IPI à base de cálculo do ST, se ele não compuser a base do ICMS próprio.
                if ($this->inside_ipi_base == "N") {
                    $this->base_calculo_st_valor += $this->ipi_valor;
                }

                // Calcula a Base de Cálculo do ICMS-ST (vBCST) aplicando a MVA
                // A MVA deve ser somada a 1 (100%) antes de multiplicar.
                $this->base_calculo_st_valor = $this->base_calculo_st_valor * (1 + ($this->mva_st / 100));

                // Aplica redução na base de cálculo do ST
                if ($this->reducao_base_calculo_st_perc > 0) {
                    $this->base_calculo_st_valor -= ($this->base_calculo_st_valor * ($this->reducao_base_calculo_st_perc / 100));
                }

                // Calcula o valor final do ICMS-ST
                $this->icms_st_valor = (($this->icms_st_aliq / 100) * $this->base_calculo_st_valor) - $this->icms_valor;

                // Arredondar para 2 casas decimais
                $this->icms_st_valor = round($this->icms_st_valor, 2);
                $this->base_calculo_st_valor = round($this->base_calculo_st_valor, 2);

                break;
            case '20': // Tributação com redução de base de cálculo

                $this->icms_base_calculo -= ($this->icms_base_calculo * ($this->reducao_base_calculo_perc / 100));
                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;

                break;
            case '30': // Tributação Isenta ou não tributada e com cobrança do ICMS por substituição tributária

                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;
                $this->base_calculo_st_valor = $this->icms_base_calculo;

                if ($this->inside_ipi_base == "N") {
                    $this->base_calculo_st_valor += $this->ipi_valor;
                }

                $this->base_calculo_st_valor = ($this->base_calculo_st_valor * $this->mva_st);
                $this->base_calculo_st_valor -= ($this->base_calculo_st_valor * ($this->reducao_base_calculo_st_perc / 100));
                $this->icms_st_valor = (($this->icms_st_aliq / 100) * ($this->base_calculo_st_valor)) - $this->icms_valor;
                $this->icms_base_calculo = 0;
                $this->icms_valor = 0;

                break;
            case '40': case '41': case '50': // Isenta, Não tributada ou Suspensão

                $this->icms_base_calculo = 0;
                $this->icms_valor = 0;
                $this->base_calculo_st_valor = 0;
                $this->icms_st_valor = 0;
                $this->base_calculo_st_retido_valor = 0;
                $this->icms_st_retido_valor = 0;

                break;
            case '51': // Tributação com Diferimento (a exigência do preenchimento das informações do ICMS diferido fica a critério de cada UF)

                $this->icms_base_calculo = $this->total_tributo;
                $this->icms_operacao_valor = ($this->total_tributo * $this->icms_aliq) / 100;
                $this->icms_diferido_valor = ($this->icms_operacao_valor * ($this->diferimento_perc / 100));
                $this->icms_valor = $this->icms_operacao_valor - $this->icms_diferido_valor;

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
                $this->icms_base_calculo = 0;
                $this->base_calculo_st_retido_valor = 0;
                $this->icms_st_retido_valor = 0;

                break;
            case '70': // Redução de BC e cobrança de ICMS/ST

                // Aplica reducao Bc
                $this->icms_base_calculo -= ($this->icms_base_calculo * ($this->reducao_base_calculo_perc / 100));

                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;
                $this->base_calculo_st_valor = $this->icms_base_calculo;

                // Soma o valor do ipi na base de calculo de ST
                if ($this->inside_ipi_base == "N") {
                    $this->base_calculo_st_valor += $this->ipi_valor;
                }

                // Aplica indice mva na base de calculo st
                $this->base_calculo_st_valor = ($this->base_calculo_st_valor * $this->mva_st);

                // Aplica redução base de calculo st
                $this->base_calculo_st_valor -= ($this->base_calculo_st_valor * ($this->reducao_base_calculo_st_perc / 100));

                // Calcula icms st final
                $this->icms_st_valor = (($this->icms_st_aliq / 100) * $this->base_calculo_st_valor) - $this->icms_valor;

                break;
            case '90': // Outros

                // Aplica reducao base de calculo 
                $this->icms_base_calculo -= ($this->icms_base_calculo * ($this->reducao_base_calculo_perc / 100));

                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;
                $this->base_calculo_st_valor = $this->icms_base_calculo;


                // Soma valor ipi na base de calculo de ST
                if ($this->inside_ipi_base == "N") {
                    $this->base_calculo_st_valor += $this->ipi_valor;
                }

                // Aplica indice mva na base de calculo st
                $this->base_calculo_st_valor = ($this->base_calculo_st_valor * $this->mva_st);

                // Aplica redução base de calculo st
                $this->base_calculo_st_valor -= ($this->base_calculo_st_valor * ($this->reducao_base_calculo_st_perc / 100));

                // Calcula icms st final
                $this->icms_st_valor = (($this->icms_st_aliq / 100) * $this->base_calculo_st_valor) - $this->icms_valor;

                break;
            case '101': // Simples Nacional - Crédito

                $this->icms_base_calculo_simples_nacional = $this->total_tributo - ($dadosItem['desconto'] ?? 0);
                $this->credito_icms_simples_nacional_valor = $this->icms_base_calculo_simples_nacional * ($this->credito_simples_nacional_aliq / 100);

                break;
            case '102': // Simples Nacional - Sem permissão de crédito

                $this->icms_base_calculo -= ($this->icms_base_calculo * ($this->reducao_base_calculo_perc / 100));
                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;

                break;
            case '201': // Simples Nacional - com cobrança do ICMS por ST
                
                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;
                $this->base_calculo_st_valor = $this->icms_base_calculo;

                // Soma valor ipi na base de calculo de ST
                if ($this->inside_ipi_base == "N") {
                    $this->base_calculo_st_valor += $this->ipi_valor;
                }

                // Aplica indice mva na base de calculo st
                $this->base_calculo_st_valor = $this->base_calculo_st_valor * (1 + ($this->mva_st / 100));

                // Aplica redução base de calculo st
                $this->base_calculo_st_valor -= ($this->base_calculo_st_valor * ($this->reducao_base_calculo_st_perc / 100));

                // Calcula icms st final
                $this->icms_st_valor = (($this->icms_st_aliq / 100) * ($this->base_calculo_st_valor)) - $this->icms_valor;
                break;

            case '202': // Simples Nacional - Sem permissão de crédito e com cobrança do ICMS por ST

                $this->icms_base_calculo = $this->total_tributo - ($dadosItem['desconto'] ?? 0);
                $this->icms_valor = ($this->icms_aliq / 100) * $this->icms_base_calculo;
                $this->base_calculo_st_valor = $this->icms_base_calculo;

                // Soma valor ipi na base de calculo de ST
                if ($this->inside_ipi_base == "N") {
                    $this->base_calculo_st_valor += $this->ipi_valor;
                }

                // Aplica indice mva na base de calculo st
                $this->base_calculo_st_valor = $this->base_calculo_st_valor * (1 + ($this->mva_st / 100));

                // Aplica redução base de calculo st
                $this->base_calculo_st_valor -= ($this->base_calculo_st_valor * ($this->reducao_base_calculo_st_perc / 100));
                // Calcula icms st final
                $this->icms_st_valor = (($this->icms_st_aliq / 100) * ($this->base_calculo_st_valor)) - $this->icms_valor;

                $this->credito_simples_nacional_aliq = 0;
                $this->credito_icms_simples_nacional_valor = 0;

                break;
        }
    }
    
    private function _calculaBlocoPis(){
        switch ($this->pis_cst){
            case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
            case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                $this->pis_valor = ($this->pis_base_calculo * $this->pis_aliq) / 100;
                break;
            case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                $this->pis_base_calculo = $this->produto_quantidade;
                $this->produto_aliq = $this->pis_aliq;
                $this->pis_valor = ($this->pis_base_calculo * $this->produto_aliq);
                break;
            case '04': 
            case '05': 
            case '06': 
            case '07': 
            case '08': 
            case '09': 
                $this->pis_base_calculo = 0;
                $this->pis_aliq = 0;
                $this->pis_valor = 0;
                break;
            default :
                $this->pis_valor = ($this->pis_base_calculo * $this->pis_aliq) / 100;
        }
    }

    
    private function _calculaBlocoCofins()
    {
        switch ($this->cofins_cst){
            case '01': // Operação Tributável (base de cálculo = valor da operação alíquota normal (cumulativo/não cumulativo)); 
            case '02': // Operação Tributável (base de cálculo = valor da operação (alíquota diferenciada)); 
                $this->cofins_valor = ($this->cofins_base_calculo * $this->cofins_aliq) / 100;
                break;
            case '03': //Operação Tributável (base de cálculo = quantidade vendida x alíquota por unidade de produto)
                $this->cofins_base_calculo = $this->produto_quantidade;
                $this->produto_aliq = $this->cofins_aliq;
                $this->cofins_aliq = ($this->cofins_base_calculo * $this->produto_aliq) / 100;
                break;
            case '04': 
            case '05': 
            case '06': 
            case '07': 
            case '08': 
            case '09': 
                $this->cofins_base_calculo = 0;
                $this->cofins_aliq = 0;
                $this->cofins_valor = 0;
                break;
            default :
                $this->cofins_valor = ($this->cofins_base_calculo * $this->cofins_aliq) / 100;
        }
    }

    function verificarAjustaData($dataSaidaEntrada) {
        try {
            // Converte a string de data para objeto DateTime
            $dataInformada = DateTime::createFromFormat('d/m/Y H:i', $dataSaidaEntrada);
            
            // Verifica se a conversão foi bem-sucedida
            if (!$dataInformada) {
                throw new Exception("Formato de data inválido. Use: dd/mm/aaaa HH:mm");
            }
            
            // Obtém a data/hora atual
            $agora = new DateTime();
            
            // Verifica se é o mesmo dia
            $mesmoDia = $dataInformada->format('Y-m-d') === $agora->format('Y-m-d');
            
            // Verifica se é a mesma hora
            $mesmaHora = $dataInformada->format('H') === $agora->format('H');
            
            // Se for o mesmo dia e a mesma hora, adiciona 2 minutos
            if ($mesmoDia && $mesmaHora) {
                $dataInformada->add(new DateInterval('PT5M')); // PT2M = 2 minutos
                return $dataInformada->format('d/m/Y H:i');
            }
            
            // Retorna a data original se não precisar ajustar
            return $dataSaidaEntrada;
            
        } catch (Exception $e) {
            // Em caso de erro, você pode logar ou tratar conforme necessário
            error_log("Erro ao processar data: " . $e->getMessage());
            return $dataSaidaEntrada; // Retorna a data original em caso de erro
        }
    }

}	//	END OF THE CLASS
?>