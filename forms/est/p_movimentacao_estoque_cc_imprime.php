<?php
/**
 * @package   astec
 * @name      movimentacao_estoque_cc_imprime
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

//Class movimentacao_estoque_cc_imprime
Class movimentacao_estoque_cc_imprime extends c_produto{

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;
    public $smarty = null;

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
		        
        $this->m_par = explode("|", $this->m_letra);
        

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);
    }

/*
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/

function controle() {
    switch ($this->m_submenu) {        
        default:                
            $this->movImprime('');
            
    }
}

//-------------------------------------------------------------

    public function movImprime($mensagem, $tipoMsg = NULL) {
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('dataHora', date("d/m/Y H:i:s"));
        $this->smarty->assign('obs', $this->m_par[8]);

        //VERIFICA SE È MOVIMENTO CC OU REIMPRESSAO DE ROMANEIO
        if($this->m_par[1] == 'reimpressao')
            //Busca dados das nfs de entrada e saída
            $notas = $this->selectNotas($this->m_par[0]);
        else
            $notas = $this->selectNotas($this->m_par[1]);

        if (isset($notas[0]['OBS']) !== '' || isset($notas[0]['OBS']) !== null) {
            $this->smarty->assign('obs', $notas[0]['OBS']);
        }
            
        $this->smarty->assign('notaSaida',  $notas[0]['NUMERO']);
        $this->smarty->assign('notaEntrada',  $notas[1]['NUMERO']);

        //BUSCA DESCRIÇÂO CENTRO CUSTO SAÍDA
        $ccustoS = new c_banco;
        $ccustoS->setTab("FIN_CENTRO_CUSTO");
        $desCCSaida = $ccustoS->getField("DESCRICAO", "CENTROCUSTO=".$notas[0]['CENTROCUSTO']);
        $ccustoS->close_connection();
        $this->smarty->assign('centroCustoSaida', $desCCSaida);
        
        //BUSCA DESCRIÇÂO CENTRO CUSTO ENTRADA
        $ccustoE = new c_banco;
        $ccustoE->setTab("FIN_CENTRO_CUSTO");
        $desCCEntrada = $ccustoE->getField("DESCRICAO", "CENTROCUSTO=".$notas[1]['CENTROCUSTO']);
        $ccustoE->close_connection();
        $this->smarty->assign('centroCustoEntrada', $desCCEntrada);

        //Dados Nota de Saida
        $nfDados = $this->selectNotaSaida($notas[0]['NUMERO']);
        $this->smarty->assign('pessoa',  $nfDados[0]['NOME']);
        $this->smarty->assign('genero',  $nfDados[0]['DESCRICAO']);
        
        //Busca dados Produto
        $produto = $this->selectProdNf($notas[0]['NUMERO']);

        $this->smarty->assign('codProduto',  $produto[0]['CODPRODUTO']);
        $this->smarty->assign('produto',  $produto[0]['DESCRICAO']);
        $this->smarty->assign('qtd',  $produto[0]['QUANT']);

        //BUSCA VALOR DO PRODUTO
        $pproduto = new c_banco;
        $pproduto->setTab("EST_PRODUTO");
        $lancProd = $pproduto->getField("VENDA", "CODIGO=".$produto[0]['CODPRODUTO']);
        $pproduto->close_connection();
        $this->smarty->assign('valorProd', $lancProd);

        //Dados da empresa
        $empresa = $this->busca_dadosEmpresaCC($this->m_empresacentrocusto);
        $this->smarty->assign('empresa',  $empresa);

        // }else{
        //     //form mov estoque cc
        //     $this->smarty->assign('notaEntrada',  $this->m_par[0]);
        //     $this->smarty->assign('notaSaida',  $this->m_par[1]);
            
        //     $this->smarty->assign('codProduto',  $this->m_par[9]);
        //     $this->smarty->assign('produto',  $this->m_par[4]);
        //     $this->smarty->assign('qtd',  $this->m_par[5]);
        //     $this->smarty->assign('pessoa',  $this->m_par[6]);
        //     $this->smarty->assign('genero',  $this->m_par[7]);
        //     $this->smarty->assign('obs',  $this->m_par[8]);

        //     //$empresa = $this->busca_dadosEmpresaCC($lanc[0]['CCUSTO']);

        //     //BUSCA DESCRIÇÂO CENTRO CUSTO SAÍDA
        //     $ccustoS = new c_banco;
        //     $ccustoS->setTab("FIN_CENTRO_CUSTO");
        //     $desCCSaida = $ccustoS->getField("DESCRICAO", "CENTROCUSTO=".$this->m_par[2]);
        //     $ccustoS->close_connection();
        //     $this->smarty->assign('centroCustoSaida', $desCCSaida);

        //     //BUSCA DESCRIÇÂO CENTRO CUSTO ENTRADA
        //     $ccustoE = new c_banco;
        //     $ccustoE->setTab("FIN_CENTRO_CUSTO");
        //     $desCCEntrada = $ccustoE->getField("DESCRICAO", "CENTROCUSTO=".$this->m_par[3]);
        //     $ccustoE->close_connection();
        //     $this->smarty->assign('centroCustoEntrada', $desCCEntrada);

        //     //BUSCA VALOR DO PRODUTO
        //     $pproduto = new c_banco;
        //     $pproduto->setTab("EST_PRODUTO");
        //     $lancProd = $pproduto->getField("VENDA", "CODIGO=".$this->m_par[9]);
        //     $pproduto->close_connection();
        //     $this->smarty->assign('valorProd', $lancProd);

        //     $empresa = $this->busca_dadosEmpresaCC($this->m_empresacentrocusto);
        //     $this->smarty->assign('empresa',  $empresa);
        // }

        $this->smarty->display('romaneio_mov_est_cc_imprime.tpl');
        
    }


//fim MovImprime
//-------------------------------------------------------------
}
$produto = new movimentacao_estoque_cc_imprime();

$produto->controle();
?>
