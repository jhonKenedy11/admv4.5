<?php
/**
 * @package   astecv3
 * @name      p_boleto
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/06/2017
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/blt/c_boleto.php");

//Class p_fin_boleto
Class p_boleto extends c_boleto {

private $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;



//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  
    
  	 // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/blt";
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
        $this->smarty->assign('pathCliente', ADMhttpCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Ordem Serviço");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3 ]"); 
        $this->smarty->assign('disableSort', "[ 4 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // include do javascript
        // include ADMjs . "/blt/s_blt.js";

}

/**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
function controle(){
  switch ($this->m_submenu){
		case 'imprime':
			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'I')){
				$this->imprimeBoleto();
			//}
			break;
		default:
  			//if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'C')){
				$this->mostraBoleto('');
  			//}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Banco. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function imprimeBoleto($mensagem=NULL){

    $lanc = $this->selectLancBoleto();

    for ($i = 0; $i < count($lanc); $i++) {
        
    }
    // DADOS DO BOLETO PARA O SEU CLIENTE
    $dias_de_prazo_para_pagamento = 5;
    $taxa_boleto = 2.95;
    $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
    $valor_cobrado = "2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
    $valor_cobrado = str_replace(",", ".",$valor_cobrado);
    $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

    $dadosboleto["nosso_numero"] = '12345678';  // Nosso numero - REGRA: Maximo de 8 caracteres!
    $dadosboleto["numero_documento"] = '0123';	// Num do pedido ou nosso numero
    $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
    $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
    $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
    $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com virgula e sempre com duas casas depois da virgula

    // DADOS DO SEU CLIENTE
    $dadosboleto["sacado"] = "Nome do seu Cliente";
    $dadosboleto["endereco1"] = "Endereço do seu Cliente";
    $dadosboleto["endereco2"] = "Cidade - Estado -  CEP: 00000-000";

    // INFORMACOES PARA O CLIENTE
    $dadosboleto["demonstrativo1"] = "Pagamento de Compra na Loja Nonononono";
    $dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
    $dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";
    $dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
    $dadosboleto["instrucoes2"] = "- Receber até 10 dias após o vencimento";
    $dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br";
    $dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";

    // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
    $dadosboleto["quantidade"] = "";
    $dadosboleto["valor_unitario"] = "";
    $dadosboleto["aceite"] = "";		
    $dadosboleto["especie"] = "R$";
    $dadosboleto["especie_doc"] = "";


    // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


    // DADOS DA SUA CONTA - ITAÚ
    $dadosboleto["agencia"] = "1565"; // Num da agencia, sem digito
    $dadosboleto["conta"] = "13877";	// Num da conta, sem digito
    $dadosboleto["conta_dv"] = "4"; 	// Digito do Num da conta

    // DADOS PERSONALIZADOS - ITAÚ
    $dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

    // SEUS DADOS
    $dadosboleto["identificacao"] = "BoletoPhp - Código Aberto de Sistema de Boletos";
    $dadosboleto["cpf_cnpj"] = "";
    $dadosboleto["endereco"] = "Coloque o endereço da sua empresa aqui";
    $dadosboleto["cidade_uf"] = "Cidade / Estado";
    $dadosboleto["cedente"] = "Coloque a Razão Social da sua empresa aqui";

    // NÃO ALTERAR!
    include("include/funcoes_itau.php"); 
    include("include/layout_itau.php");
    echo "<br><br><br><br><br><br><br><br><br>";
    include("include/layout_itau.php");
    echo "<br><br><br><br><br><br><br><br><br>";
    include("include/layout_itau.php");    
}//fim desenhaCadastroBanco

/**
* <b> Listagem de todas as registro cadastrados de tabela boleto. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraBoleto($mensagem){

  
    $lanc = $this->selectLancBoleto();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('boleto_mostra.tpl');
	

} //fim mostraBanco
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$boleto = new p_boleto();

$boleto->controle();
 
  
?>
