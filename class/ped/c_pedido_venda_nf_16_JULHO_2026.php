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
include_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_database_pdo.php");


//Class 
Class c_pedidoVendaNf extends c_pedidoVenda {
    // Propriedades para armazenar os resultados dos cálculos de impostos

    //ICMS
    public $icms_base_calculo = 0;
    public $icms_valor = 0;
    public $icms_diferido_valor = 0;
    public $icms_operacao_valor = 0;
    public $tributo_icms_saida = NULL;
    public $modalidade_calculo = NULL;
    public $item_estoque = 0;
    public $icms_aliq = 0;
    public $reducao_base_calculo_perc = 0;
    public $icms_base_calculo_simples_nacional = 0;
    public $credito_simples_nacional_aliq = 0;
    public $credito_icms_simples_nacional_valor = 0;

    // ST
    public $icms_st_valor = 0;
    public $icms_st_aliq = 0;
    public $base_calculo_st_retido_valor = 0;
    public $base_calculo_st_valor = 0;
    public $icms_st_retido_valor = 0;
    public $icms_valor_st_retido = 0;
    public $modalidade_calculo_st = 0;
    public $mva_st = 0;
    public $reducao_base_calculo_st_perc = 0;

    // IPI
    public $ipi_cst = NULL;
    public $ipi_aliq = 0;
    public $ipi_valor = 0;
    public $inside_ipi_base = 0;

    // PIS
    public $pis_cst = NULL;
    public $pis_base_calculo = 0;
    public $pis_valor = 0;
    public $pis_aliq = 0;

    // COFINS
    public $cofins_cst = NULL;
    public $cofins_base_calculo = 0;
    public $cofins_valor = 0;
    public $cofins_aliq = 0;

    // OUTROS
    public $origem = NULL;
    public $cfop = NULL;
    public $diferimento_perc = 0;
    public $produto_quantidade = 0;
    public $produto_desconto = 0;
    public $produto_valor = 0;
    public $produto_aliq = 0;
    public $frete_valor = 0;
    public $desp_acessoria_valor = 0;
    public $crt = NULL;
    public $ncm = NULL;
    public $cest = NULL;
    public $total_tributo = 0;
    public $codigo_beneficiario = NULL;

    // DIFAL (ICMS interestadual UF destino)
    public $difal_bc = 0;
    public $difal_bc_fcp = 0;
    public $difal_aliq_interna = 0;
    public $difal_aliq_inter = 0;
    public $difal_aliq_inter_part = 0;
    public $difal_aliq_fcp = 0;
    public $difal_valor = 0;
    public $difal_fcp_valor = 0;
    public $difal_remet_valor = 0;

    private $difal_context = [];
    private $difal_centro_custo = NULL;

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
    $sql = "select PARCELA, VENCIMENTO, TOTAL as VALOR, SITPGTO ,IF (SITPGTO = 'B', 'BAIXADO', '') AS SITPAG FROM FIN_LANCAMENTO WHERE NUMLCTO = '" . $this->getPedido() . "' AND ORIGEM = 'PED' AND SITPGTO = 'B'";
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
        $sql = "select PARCELA, VENCIMENTO, TOTAL as VALOR, SITPGTO ,IF (SITPGTO = 'B', 'BAIXADO', '') AS SITPAG FROM FIN_LANCAMENTO WHERE NUMLCTO = '".$this->getPedido()."' AND ORIGEM = 'PED' AND SITPGTO = 'B'";
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
    public function calculaImpostosNfe($dadosItem, $natOp = NULL, $uf=NULL, $tipoPessoa=NULL, $centroCusto=NULL, $apenas_calculo = NULL, $difalContext = NULL)
    {
        $arrTributos = [];
        $dados       = [];

        // busca CRT do cliente
        $this->crt = $this->_buscaCrt($centroCusto);

        if($apenas_calculo === true)
        {
            // Modo array (para relatórios / espelho NFe): espelhar o mesmo preenchimento do modo objeto
            $this->desp_acessoria_valor = $dadosItem['despAcessorias'] ?? 0;
            $this->tributo_icms_saida   = $dadosItem['tribIcms'] ?? '';
            $this->item_estoque         = $dadosItem['item_estoque'] ?? 0;
            $this->produto_desconto     = $dadosItem['desconto'] ?? 0;
            $this->produto_valor        = (float) ($dadosItem['produto_valor'] ?? ($dadosItem['total'] ?? 0));
            $this->total_tributo        = (float) ($dadosItem['total'] ?? ($dadosItem['produto_valor'] ?? 0));
            $this->frete_valor          = (float) ($dadosItem['frete'] ?? 0);
            $this->origem               = $dadosItem['origem'] ?? '';
            $this->ncm                  = $dadosItem['ncm'] ?? '';
            $this->cest                 = $dadosItem['cest'] ?? '';
            $this->produto_quantidade   = $dadosItem['quantidade'] ?? 0;
            $this->difal_context      = is_array($difalContext) ? $difalContext : [];
            $this->difal_centro_custo = $centroCusto;
        

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
            $this->difal_context      = is_array($difalContext) ? $difalContext : [];
            $this->difal_centro_custo = $centroCusto;
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

        $exigeDifal = $this->_exigeDifalOperacao(
            $uf,
            $tipoPessoa,
            trim((string) ($this->difal_context['ie'] ?? '')),
            $this->difal_context['vendaPresencial'] ?? 'N'
        );
        
        if ($exigeDifal && (string) $this->crt === '3') {
            $dados['tribIcms'] = '00';
        } else {
            $exigeDifal = false;
        }

        // Busca tributos
        $arrTributos = $this->_buscaTributos($dados);

        if ($exigeDifal && $arrTributos && count($arrTributos) > 1) {
            usort($arrTributos, function ($a, $b) {
                $stA = (float) ($a['ALIQICMSST'] ?? 0);
                $stB = (float) ($b['ALIQICMSST'] ?? 0);
                return $stA !== $stB ? ($stB <=> $stA) : ((float) ($b['ALIQICMS'] ?? 0) <=> (float) ($a['ALIQICMS'] ?? 0));
            });
            $arrTributos = [ $arrTributos[0] ];
        }

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
            $this->diferimento_perc              = (float) $arrTributos[0]["PERCDIFERIDO"];

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

            // DIFAL (interestadual consumidor final / não contribuinte)
            $this->_calculaBlocoDifal($arrTributos, $uf, $tipoPessoa);

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
                        'vlCofins' => $this->cofins_valor,

                        // DIFAL
                        'bcIcmsUfDest' => $this->difal_bc,
                        'aliqIcmsUfDest' => $this->difal_aliq_interna,
                        'aliqIcmsInter' => $this->difal_aliq_inter,
                        'aliqIcmsInterPart' => $this->difal_aliq_inter_part,
                        'bcFcpUfDest' => $this->difal_bc_fcp,
                        'aliqFcpUfDest' => $this->difal_aliq_fcp,
                        'vlFcpUfDest' => $this->difal_fcp_valor,
                        'vlIcmsUfDest' => $this->difal_valor,
                        'vlIcmsUfRemet' => $this->difal_remet_valor

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

                // Zera as variáveis do ICMS após serem setadas no objeto
                $this->icms_base_calculo = 0;
                $this->icms_valor = 0;
                $this->credito_icms_simples_nacional_valor = 0;
                $this->icms_diferido_valor = 0;
                $this->icms_operacao_valor = 0;

                // ICMS ST
                $objNfProd->setValorBcSt($this->base_calculo_st_valor, true); // <- ANTIGO PROCESSO
                $objNfProd->setValorIcmsSt($this->icms_st_valor, true); // <- ANTIGO PROCESSO
                $objNfProd->setModBcSt($this->modalidade_calculo_st); // <- ANTIGO PROCESSO
                $objNfProd->setPercMvaSt($this->mva_st, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setPercReducaoBcSt($this->reducao_base_calculo_st_perc, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setAliqIcmsSt($this->icms_st_aliq, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO

                // Zera as variáveis do ICMS ST após serem setadas no objeto
                $this->base_calculo_st_valor = 0;
                $this->icms_st_valor = 0;
                $this->modalidade_calculo_st = 0;
                $this->mva_st = 0;
                $this->reducao_base_calculo_st_perc = 0;
                $this->icms_st_aliq = 0;

                // IPI
                $objNfProd->setValorIpi($this->ipi_valor); // <- ANTIGO PROCESSO metade DO ARQUIVO
                $objNfProd->setAliqIpi($this->ipi_aliq, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setInsideIpiBc($this->inside_ipi_base); // <- ANTIGO PROCESSO INICIO DO ARQUIVO

                // Zera as variáveis do IPI após serem setadas no objeto
                $this->ipi_valor = 0;
                $this->inside_ipi_base = 0;
                $this->ipi_aliq = 0;


                // PIS
                $objNfProd->setCstPis($this->pis_cst); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setBcPis($this->pis_base_calculo, true); // <- ANTIGO PROCESSO
                $objNfProd->setAliqPis($this->pis_aliq, true); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setValorPis($this->pis_valor, true); // <- ANTIGO PROCESSO

                // Zera as variáveis do PIS após serem setadas no objeto
                $this->pis_base_calculo = 0;
                $this->pis_valor = 0;
                $this->pis_aliq = 0;
                $this->pis_cst = 0;

                // COFINS
                $objNfProd->setCstCofins($this->cofins_cst); // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setAliqCofins($this->cofins_aliq, true);  // <- ANTIGO PROCESSO INICIO DO ARQUIVO
                $objNfProd->setBcCofins($this->cofins_base_calculo, true); // <- ANTIGO PROCESSO
                $objNfProd->setValorCofins($this->cofins_valor, true); // <- ANTIGO PROCESSO   

                // Zera as variáveis do COFINS após serem setadas no objeto
                $this->cofins_base_calculo = 0;
                $this->cofins_valor = 0;
                $this->cofins_aliq = 0;
                $this->cofins_cst = 0;

                // DIFAL
                $objNfProd->setBcIcmsUfDest($this->difal_bc, true);
                $objNfProd->setAliqIcmsUfDest($this->difal_aliq_interna, true);
                $objNfProd->setAliqIcmsInter($this->difal_aliq_inter, true);
                $objNfProd->setAliqIcmsInterPart($this->difal_aliq_inter_part, true);
                $objNfProd->setValorIcmsUFDest($this->difal_valor, true);
                $objNfProd->setValorIcmsUFRemet($this->difal_remet_valor, true);
                $objNfProd->setBcFcpUfDest($this->difal_bc_fcp, true);
                $objNfProd->setAliqFcpUfDest($this->difal_aliq_fcp, true);
                $objNfProd->setValorFcpUfDest($this->difal_fcp_valor, true);

                $this->difal_bc = 0;
                $this->difal_bc_fcp = 0;
                $this->difal_aliq_interna = 0;
                $this->difal_aliq_inter = 0;
                $this->difal_aliq_inter_part = 0;
                $this->difal_aliq_fcp = 0;
                $this->difal_valor = 0;
                $this->difal_fcp_valor = 0;
                $this->difal_remet_valor = 0;
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

        # Tese do Século (Tema 69 do STF) consolidou que o ICMS destacado não compõe a receita bruta e, 
        # por isso, deve ser removido da base de cálculo ($vBC$) desses impostos
        # CST 10, 30, 70 ou 90, você só subtrai o valor da tag <vICMS>. O valor da tag <vICMSST> não entra no abatimento.

        # Se o produto for monofásico (PIS/COFINS com alíquota zero para o varejo/atacadista, mas tributado na indústria)
        # Para o revendedor que usa CST 04 ou 06 de PIS/COFINS, a base de cálculo é irrelevante (pois a alíquota é zero), então não há o que descontar.
        # Para a indústria (que paga o imposto), a regra de exclusão se aplica normalmente.
        switch ($this->tributo_icms_saida){
            case '00':
            case '10':
            case '30':
            case '70':
            case '90':
                $this->pis_base_calculo = $this->pis_base_calculo - $this->icms_valor;
                break;
        }

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
        # Tese do Século (Tema 69 do STF) consolidou que o ICMS destacado não compõe a receita bruta e, 
        # por isso, deve ser removido da base de cálculo ($vBC$) desses impostos
        # CST 10, 30, 70 ou 90, você só subtrai o valor da tag <vICMS>. O valor da tag <vICMSST> não entra no abatimento.

        # Se o produto for monofásico (PIS/COFINS com alíquota zero para o varejo/atacadista, mas tributado na indústria)
        # Para o revendedor que usa CST 04 ou 06 de PIS/COFINS, a base de cálculo é irrelevante (pois a alíquota é zero), então não há o que descontar.
        # Para a indústria (que paga o imposto), a regra de exclusão se aplica normalmente.

        switch ($this->tributo_icms_saida){
            case '00':
            case '10':
            case '30':
            case '70':
            case '90':
                $this->cofins_base_calculo = $this->cofins_base_calculo - $this->icms_valor;
                break;
        }


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

    /**
     * Operação interestadual que exige partilha ICMS (DIFAL) — espelha p_nfephp_40.php
     */
    private function _exigeDifalOperacao($ufDest, $tipoPessoa, $ie, $vendaPresencial)
    {
        if ($vendaPresencial === 'S') {
            return false;
        }

        $ufEmit = '';
        if (!empty($this->difal_centro_custo)) {
            static $cacheUfEmpresa = [];
            $cc = $this->difal_centro_custo;
            if (!isset($cacheUfEmpresa[$cc])) {
                $sql = "SELECT UF FROM AMB_EMPRESA WHERE CENTROCUSTO = :centroCusto";
                $banco = new c_banco_pdo();
                $banco->prepare($sql);
                $banco->bindParam('centroCusto', $cc);
                $banco->execute();
                $row = $banco->fetch(PDO::FETCH_ASSOC);
                $cacheUfEmpresa[$cc] = $row['UF'] ?? '';
            }
            $ufEmit = $cacheUfEmpresa[$cc];
        }

        if ($ufEmit === '' || strtoupper($ufEmit) === strtoupper((string) $ufDest)) {
            return false;
        }

        // PJ com IE informada = contribuinte ICMS, sem DIFAL
        if ($tipoPessoa === 'J' && strlen(trim((string) $ie)) > 0) {
            return false;
        }

        return true;
    }

    private function _calculaBlocoDifal(array $arrTributos, $ufDest, $tipoPessoa)
    {
        $this->difal_bc = $this->difal_bc_fcp = $this->difal_aliq_interna = 0;
        $this->difal_aliq_inter = $this->difal_aliq_inter_part = $this->difal_aliq_fcp = 0;
        $this->difal_valor = $this->difal_fcp_valor = $this->difal_remet_valor = 0;

        $ie = trim((string) ($this->difal_context['ie'] ?? ''));
        $vendaPresencial = $this->difal_context['vendaPresencial'] ?? 'N';

        if (!$this->_exigeDifalOperacao($ufDest, $tipoPessoa, $ie, $vendaPresencial)
            || !in_array($this->tributo_icms_saida, ['00', '20', '102'], true)) {
            return;
        }

        $aliqInter = (float) $arrTributos[0]['ALIQICMS'];
        $aliqInterna = (float) $arrTributos[0]['ALIQICMSST'];
        $aliqFcp = (float) ($arrTributos[0]['ALIQFCPST'] ?? 0);

        if ($this->crt == '1') {
            $this->difal_bc = ($this->total_tributo + $this->frete_valor + $this->desp_acessoria_valor) - $this->produto_desconto;
        } else {
            $this->difal_bc = $this->icms_base_calculo;
        }

        $aliqInternaCalc = $aliqInterna;
        if ($aliqFcp > 0.01) {
            $aliqInternaCalc = $aliqInterna - $aliqFcp;
        }

        $this->difal_valor = round($this->difal_bc * (($aliqInternaCalc - $aliqInter) / 100), 2);
        $this->difal_aliq_interna = $aliqInterna;
        $this->difal_aliq_inter = $aliqInter;
        $this->difal_aliq_inter_part = 100;
        $this->difal_remet_valor = 0;

        if ($aliqFcp > 0.01) {
            $this->difal_bc_fcp = $this->difal_bc;
            $this->difal_aliq_fcp = $aliqFcp;
            $this->difal_fcp_valor = round($this->difal_bc * ($aliqFcp / 100), 2);
        }
    }

    function verificarAjustaData($dataSaidaEntrada) {
        try {
            $dataSaidaEntrada = trim((string) $dataSaidaEntrada);
            $dataInformada = false;
            foreach (['d/m/Y H:i:s', 'd/m/Y H:i'] as $fmt) {
                $dataInformada = DateTime::createFromFormat($fmt, $dataSaidaEntrada);
                if ($dataInformada instanceof DateTime) {
                    break;
                }
            }

            if (!$dataInformada) {
                throw new Exception("Formato de data inválido. Use: dd/mm/aaaa HH:mm ou dd/mm/aaaa HH:mm:ss");
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

    /**
     * Obtém o parâmetro de envio de boleto da empresa
     * @param int $id - ID da conta bancaria
     * @return array
     */
    function getParamEnvioBoleto(int $id) {
        // Busca o regime tributário da empresa
        $sql = "SELECT ENVIA_BOLETO, BANCO FROM FIN_CONTA WHERE CONTA = :conta";
        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindParam('conta', $id);
        $banco->execute();

        $conta = $banco->fetch(PDO::FETCH_ASSOC);
        return $conta;
    }

    /**
     * Cria o objeto da API do banco
     * @param string $banco
     * @return object
     */
    function createObjectApi($banco = null) {
        switch ($banco) {
            case '237': // Banco Bradesco

                $dir = __DIR__;
                include_once($dir . "/../fin/c_api_bradesco_service.php");
                return new c_api_bradesco_service();

            case '77': // Banco Inter

                $dir = __DIR__;
                include_once($dir . "/../fin/c_api_inter_service.php");
                return new c_api_inter_service();
                break;

            default:
                return false;
        }
    }

    /**
     * Obtém as parcelas do lançamento financeiro do pedido
     * @param int $id_pedido
     * @return array
     */
    function getParcelas($numero_pedido = null) {
        $sql = "SELECT FL.*, FC.BANCO AS BANCO FROM FIN_LANCAMENTO FL
                INNER JOIN FIN_CONTA FC ON FL.CONTA = FC.CONTA
                WHERE FL.NUMLCTO = :numlct AND FL.SERIE = :serie";
        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindParam('serie', 'NFS');
        $banco->bindParam('numlct', $numero_pedido);
        $banco->execute();
        return $banco->fetchAll(PDO::FETCH_ASSOC);
    }

    function processaPorBanco( array $parcela) : array
    {
        // Obtém o banco da parcela
        $banco           = $parcela['BANCO'] ?? null;
        $id_parcela      = $parcela['PARCELA'] ?? null;
        $insert_database = false;

        // Se a parcela não foi localizada, insere o log de erro e retorna false
        if($id_parcela == null || $id_parcela == '') {
            $this->insertLog([
                'banco' => $banco,
                'id_lancamento' => $parcela['ID'],
                'id_conta' => $parcela['CONTA'],
                'sucesso' => false,
                'cod_retorno_api' => 500,
                'mensagem_api' => 'Parcela não localizada no lancamento financeiro',
            ]);

            return [
                'sucesso'  => false,
                'parcela'  => ''
            ];
        }

        // Se o banco não foi localizado, insere o log de erro e retorna false
        if($banco == null || $banco == '') {
            $this->insertLog([
                'banco' => $banco,
                'id_lancamento' => $parcela['ID'],
                'id_conta' => $parcela['CONTA'],
                'sucesso' => false,
                'cod_retorno_api' => 500,
                'mensagem_api' => 'Banco não localizado no lancamento financeiro',
            ]);

            return [
                'sucesso'  => false,
                'parcela'  => $id_parcela
            ];
        }


        //Cria o objeto da API de cada banco
        $objeto_api_service = $this->createObjectApi($banco);

        // Se o banco não suportado, insere o log de erro e retorna false
        if($objeto_api_service === false) {
            $this->insertLog([
                'banco' => $banco,
                'id_lancamento' => $parcela['ID'],
                'id_conta' => $parcela['CONTA'],
                'sucesso' => false,
                'cod_retorno_api' => 500,
                'mensagem_api' => 'Banco não suportado',
            ]);

            return [
                'sucesso'  => false,
                'parcela'  => $id_parcela
            ];
        }


        // Processa o boleto por banco
        switch ($banco) {
            case '237': // Bradesco
                $retorno_registra_boleto = $objeto_api_service->processaRegistraBoleto($id_parcela);

                // Insere o registro do boleto na tabela FIN_API_BRADESCO
                $obj_insert_registra_boleto = new c_api_bradesco_repository();
                $insert_database = $obj_insert_registra_boleto->insertRegistraBoleto($parcela['ID'], $retorno_registra_boleto['dados']);

                // Se o registro do boleto nao foi realizado com sucesso, insere o log de erro
                if($insert_database === false) {
                    $this->insertLog([
                        'banco' => $banco,
                        'id_lancamento' => $parcela['ID'],
                        'id_conta' => $parcela['CONTA'],
                        'sucesso' => false,
                        'cod_retorno_api' => 500,
                        'mensagem_api' => 'Erro ao registrar boleto na API do banco',
                        'erros_validacao' => '',
                        'json_enviado' => json_encode($parcela),
                        'json_retorno' => json_encode($retorno_registra_boleto['dados']),
                        'ip_origem' => $_SERVER['REMOTE_ADDR'],
                        'created_user' => $this->m_id_usuario,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                // Se o boleto nao foi registrado
                if($retorno_registra_boleto['sucesso'] !== true) {
                    return [
                        'sucesso'  => false,
                        'parcela'  => $id_parcela,
                        'mensagem' => 'Erro ao registrar boleto na API do banco'
                    ];
                }

                break;
            case '77': // Inter
                $retorno_registra_boleto = $objeto_api_service->processaEmitirCobranca($parcela['ID']);

                // Na Api do inter a insercao ja esta no processo de emissao
                // Se necessario apenas recuperar o ID na resposta para atualizacao do registro

                // Se o boleto nao foi registrado retorna false, o log do resgistro ja foi inserido na etapa anterior
                if($retorno_registra_boleto['sucesso'] !== true) {
                    return [
                        'sucesso'  => false,
                        'parcela'  => $id_parcela
                    ];
                }

                // Recupera a cobrança na API do banco
                $retorno_recuperar_cobranca = $objeto_api_service->processaRecuperarCobranca($retorno_registra_boleto['data']['id']);

                // Se o boleto nao foi recuperado com sucesso retorna false, o log da recuperacao ja foi inserido na etapa anterior
                if($retorno_recuperar_cobranca['sucesso'] !== true) {
                    return [
                        'sucesso'  => false,
                        'parcela'  => $id_parcela
                    ];
                }

                break;
        }


        return [
            'sucesso'  => true,
            'parcela'  => $id_parcela,
        ];

    }
    // ------------------------------ END OF THE FUNCTION -----------------------------------------

    /**
     * Registra o boleto na API Bradesco
     * @param array $array_parcelas
     * @param string $bancos
     * @return array
     */
    function processaBoletoApi( int $numero_nf) : array
    {
        try {

            $return = [];

            if ($numero_nf == null) {
                return [
                    'sucesso'  => false,
                    'mensagem' => 'Número da nota fiscal não informado',
                ];
            } 

            $array_parcelas = $this->getParcelas($numero_nf);

            // se nao localizar parcelas lanca uma excecao
            if(empty($array_parcelas)) {
                return [
                    'sucesso'  => false,
                    'mensagem' => 'Não foi possível obter as parcelas do lançamento financeiro da nota fiscal',
                ];
            }

            // Processa cada parcela
            foreach($array_parcelas as $parcela) 
            {
                //Busca parametro de envio de boleto
                $param_envio_boleto = $this->getParamEnvioBoleto($parcela['CONTA']);

                // Remessa/CNAB (ex.: Sicredi 748): boleto é gerado na impressão, não na API
                if ($param_envio_boleto['ENVIA_BOLETO'] !== 'A') {
                    $return[] = [
                        'sucesso'  => true,
                        'parcela'  => $parcela['PARCELA'],
                        'mensagem' => 'Boleto via remessa — impressão na tela de NF/boletos',
                    ];
                    continue;
                }

                // Fluxo de processo de cada banco (API: Inter, Bradesco)
                $retorno_processa_por_banco = $this->processaPorBanco($parcela);


                // Se o boleto foi registrado com sucesso
                $return[] = [
                    'sucesso'  => $retorno_processa_por_banco['sucesso'],
                    'parcela'  => $retorno_processa_por_banco['parcela'],
                ];
                
            }

            return $return;

        } catch (Exception $e) {
            $return['sucesso']  = false;
            $return['parcela']  = null;
            return $return;
        }
    }


    /**
     * Insere o log de execucao da API de cada banco
     * @param array $log
     * @return int
     */
    function insertLog(array $log) {
        $sql = "INSERT INTO 
                FIN_API_BANCOS_LOG (
                    BANCO, 
                    ID_LANCAMENTO, 
                    ID_CONTA, 
                    SUCESSO,
                    COD_RETORNO_API,
                    MENSAGEM_API,
                    ERROS_VALIDACAO,
                    JSON_ENVIADO,
                    JSON_RETORNO,
                    IP_ORIGEM,
                    CREATED_USER,
                    CREATED_AT) 
                VALUES (
                    :banco, 
                    :id_lancamento, 
                    :id_conta, 
                    :sucesso, 
                    :cod_retorno_api, 
                    :mensagem_api, 
                    :erros_validacao, 
                    :json_enviado, 
                    :json_retorno, 
                    :ip_origem, 
                    :created_user, 
                    :created_at)";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindParam('banco', $log['banco']);
        $banco->bindParam('id_lancamento', $log['id_lancamento']);
        $banco->bindParam('id_conta', $log['id_conta']);
        $banco->bindParam('sucesso', $log['sucesso']);
        $banco->bindParam('cod_retorno_api', $log['cod_retorno_api']);
        $banco->bindParam('mensagem_api', $log['mensagem_api']);
        $banco->bindParam('erros_validacao', $log['erros_validacao']);
        $banco->bindParam('json_enviado', $log['json_enviado']);
        $banco->bindParam('json_retorno', $log['json_retorno']);
        $banco->bindParam('ip_origem', $log['ip_origem']);
        $banco->bindParam('created_user', $log['created_user']);
        $banco->bindParam('created_at', $log['created_at']);
        $banco->execute();

        return $banco->lastInsertId();
    }
    
    /**
     * Baixa reserva 1→9 após gerar financeiro (itens não fracionados), se filial controla estoque.
     *
     * @param resource|null $conn mysqli
     */
    public function pedidoPsPosFinanceiroBaixaEstoque($conn)
    {
        $ped = new c_banco();
        $ped->setTab('FAT_PEDIDO');
        $sitPed = $ped->getField('SITUACAO', 'ID=' . (int) $this->getId());
        $ped->close_connection();
        if ((int) $sitPed === 13) {
            return;
        }

        $p = new c_banco();
        $p->setTab("EST_PARAMETRO");
        $ctrl = $p->getParametros("CONTROLAESTOQUE", " where FILIAL=" . $this->m_empresacentrocusto);
        $p->close_connection();
        if ($ctrl !== 'S') {
            return;
        }

        $est = new c_produto_estoque();
        foreach ((array) $this->select_pedido_item_id() as $row) {
            $cod = $row['ITEMESTOQUE'];
            $est->produtoBaixaReservaFinanceiro($this->m_empresacentrocusto, $this->getId(), $cod, $conn);
        }
    }
}	//	END OF THE CLASS