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
include_once($dir."/../../class/crm/c_conta.php");
include_once($dir."/../../class/fin/c_conta_banco.php");
include_once($dir."/../../class/fin/c_lancamento.php");

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

        // inicializa variaveis de controle
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
		        
        $this->m_par = explode("|", $this->m_letra);

        // Cria uma instancia do Smarty
       /* $this->smarty = new Smarty;

                // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');

        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);
*/
        // include do javascript
        //include ADMjs . "/fin/s_boleto.js";

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
function imprimeBoleto(){

    $dir = (__DIR__);
    $imagens = ADMraizFonte."/template/blt/imagens";
    // cria objeto
    $objConta = new c_conta;
    $objContaBanco = new c_contaBanco;
    // DADOS DO BOLETO PARA O SEU CLIENTE
    $dias_de_prazo_para_pagamento = 5;
    $taxa_boleto = 2.95;
    $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
    $valor_cobrado = "2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
    $valor_cobrado = str_replace(",", ".",$valor_cobrado);
    $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
    
    $lanc = $this->selectLancBoleto($this->m_par[0],$this->m_par[1],$this->m_par[2],$this->m_par[3]);

    $numBoleto = count($lanc)-1;
    for ($i = 0; $i < count($lanc); $i++) {
        $objContaBanco->setId($lanc[$i]['CONTA']);
        $arrContaBanco = $objContaBanco->select_ContaBanco();
        $banco = $arrContaBanco[0]['BANCO'];
        if (is_null($lanc[$i]['NOSSONUMERO'])):
            $nossoNumero = $objContaBanco->geraNossoNumero($lanc[$i]['CONTA'], $arrContaBanco[0]['ULTIMONOSSONRO']);  // na impressão calcular e guardar no lancamento
            $dadosboleto["nosso_numero"] = $nossoNumero;
            c_lancamento::gravaNossoNumero($lanc[$i]['ID'], $nossoNumero);
        else:
            $dadosboleto["nosso_numero"] = $lanc[$i]['NOSSONUMERO'];
        endif;
        //$dadosboleto["nosso_numero"] = '12345678';  // na impressão calcular e guardar no lancamento
        
        // Num do pedido ou nosso numero
        $dadosboleto["numero_documento"] = (($lanc[$i]['DOCTO']=='0') ? $dadosboleto["nosso_numero"] : $lanc[$i]['DOCTO']."-".$lanc[$i]['PARCELA']);

        //$dadosboleto["numero_documento"] = $lanc[$i]['DOCTO']."-".$lanc[$i]['PARCELA'];	// Num do pedido ou nosso numero
        $dadosboleto["data_vencimento"] = date("d/m/Y", strtotime($lanc[$i]['VENCIMENTO'])); // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = date("d/m/Y", strtotime($lanc[$i]['EMISSAO'])); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] =  number_format($lanc[$i]['TOTAL'], 2, ',', ''); // Valor do Boleto - REGRA: Com virgula e sempre com duas casas depois da virgula

        // DADOS DO SEU CLIENTE
        $objConta->setId($lanc[$i]['PESSOA']);
        $arrConta = $objConta->select_conta();
        if ($arrConta[0]['PESSOA'] = 'J') {
          $CnpjCPF = substr($arrConta[0]['CNPJCPF'], 0, 2).'.'.substr($arrConta[0]['CNPJCPF'], 2, 3).'.'.substr($arrConta[0]['CNPJCPF'], 5, 3).'/'.substr($arrConta[0]['CNPJCPF'], 8, 4).'-'.substr($arrConta[0]['CNPJCPF'], 12, 2);
        } else {
            $CnpjCPF = substr($arrConta[0]['CNPJCPF'], 0, 3).'.'.substr($arrConta[0]['CNPJCPF'], 3, 3).'.'.substr($arrConta[0]['CNPJCPF'], 6, 3).'-'.substr($arrConta[0]['CNPJCPF'], 9, 2);
        }        

        $dadosboleto["sacado"] = $arrConta[0]['NOME']." - ".$CnpjCPF;
        $dadosboleto["endereco1"] = $arrConta[0]['ENDERECO'].", ".$arrConta[0]['NUMERO']." ".$arrConta[0]['COMPLEMENTO'];
        $dadosboleto["endereco2"] = $arrConta[0]['CIDADE']." - ".$arrConta[0]['UF']." -  CEP: ".$arrConta[0]['CEP'];

        // INFORMACOES PARA O CLIENTE
        $dadosboleto["demonstrativo1"] = "Pagamento do Pedido Número: ".$lanc[$i]['NUMLCTO'];
        $dadosboleto["demonstrativo2"] = "";
        $dadosboleto["demonstrativo3"] = "";
        $dadosboleto["instrucoes1"] = "";
        $dadosboleto["instrucoes2"] = "";
        $dadosboleto["instrucoes3"] = "";
        $multa = ($arrContaBanco[0]['MULTA']*$lanc[$i]['TOTAL'])/100;
        $juros = ($arrContaBanco[0]['JUROS']*$lanc[$i]['TOTAL'])/100;
        if ($juros < 0.10):
            $juros = 0.10;
        endif;
        // INFORMACOES PARA O CLIENTE
        $dadosboleto["instrucoes1"] = "Após o vencimento, <br>";
        if ($arrContaBanco[0]['MULTA'] > 0){
            $dadosboleto["instrucoes1"] .= "Cobrar multa de R$ ".number_format($multa, 2, ',', '.')."<br> ";
        }
        if ($arrContaBanco[0]['JUROS'] > 0){
            $dadosboleto["instrucoes1"] .= "Cobrar mora diária de R$ ".number_format($juros , 2, ',', '.')."<br>";
        }
        if ($arrContaBanco[0]['CARENCIA'] > 0){
            $dadosboleto["instrucoes2"] = "Não Receber até ".$arrContaBanco[0]['CARENCIA']." dias após o vencimento<br>";
        }
        // if ($arrContaBanco[0]['PROTESTO'] > 0){
        //     // $dadosboleto["instrucoes4"] = "Em caso de dúvidas entre em contato conosco: financeiro@maxifarma.com.br";
        //     $dadosboleto["instrucoes4"] = "";
        // }
        if ($arrContaBanco[0]['DESCONTOBONIFICACAO'] > 0){
            $dadosboleto["instrucoes4"] = "Desconto de ".number_format($arrContaBanco[0]['DESCONTOBONIFICACAO'],2,',', '.')."% para pagamento até a data do vencimento.";
        }

        if ($arrContaBanco[0]['PROTESTO'] > 0){
            $dadosboleto["instrucoes3"] = "Protestar ".$arrContaBanco[0]['PROTESTO']." dias após o vencimento";
        }

        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "N";		
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = $arrContaBanco[0]['ESPECIEDOC'];


        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
        if ($i ==0):
            switch ($banco) {
                case '237':
                    include_once($dir."/../../class/blt/funcoes_bradesco.php");
                    break;
                case '341':
                    include_once($dir."/../../class/blt/funcoes_itau.php");
                    break;
            }
        endif;   
        

        // DADOS DA SUA CONTA - BRADESCO
        $dadosboleto["agencia"] = $arrContaBanco[0]['AGENCIA']; // Num da agencia, sem digito
        $conta = explode("-", $arrContaBanco[0]['CONTACORRENTE']);
        $dadosboleto["conta"] = $conta[0];	// Num da conta, sem digito
        $dadosboleto["conta_dv"] = $conta[1]; 	// Digito do Num da conta

        // DADOS PERSONALIZADOS - ITAÚ
        $dadosboleto["carteira"] = $arrContaBanco[0]['CARTEIRA'];  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

        // SEUS DADOS
        $arrEmpresa = $this->busca_dadosEmpresaCC(substr($lanc[$i]['CENTROCUSTO'], 0,2).'000000');
        $dadosboleto["identificacao"] = $arrEmpresa[0]['NOMEEMPRESA'];
        $dadosboleto["cpf_cnpj"] = $arrEmpresa[0]['CNPJ'];
        $dadosboleto["endereco"] = $arrEmpresa[0]['TIPOEND']." ".$arrEmpresa[0]['TITULOEND']." ".
                $arrEmpresa[0]['ENDERECO'].", ".$arrEmpresa[$i]['NUMERO']." ".$arrEmpresa[0]['COMPLEMENTO'];
        $dadosboleto["cidade_uf"] = $arrEmpresa[0]['CIDADE']." ".$arrEmpresa[0]['UF'];
        $dadosboleto["cedente"] = $arrEmpresa[0]['NOMEEMPRESA'];
    
        //include_once("../astecv3/boleto/include/funcoes_itau.php");
        //include_once("../astecv3/boleto/include/layout_itau.php");

        switch ($banco) {
            case '237':
                // ****************CALCULA VALORES NOSSO NUMERO - retirado de funções_bradesco
                $codigobanco = "237";
                $codigo_banco_com_dv = geraCodigoBanco($codigobanco);
                $nummoeda = "9";
                $fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

                //valor tem 10 digitos, sem virgula
                $valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
                $dadosboleto["valor_boleto"] =  number_format($lanc[$i]['TOTAL'], 2, ',', '.'); // Valor do Boleto - REGRA: Com virgula e sempre com duas casas depois da virgula
                //agencia é 4 digitos
                $agencia = formata_numero($dadosboleto["agencia"],4,0);
                //conta é 6 digitos
                $conta = formata_numero($dadosboleto["conta"],6,0);
                //dv da conta
                $conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
                //carteira é 2 caracteres
                $carteira = $dadosboleto["carteira"];

                //nosso número (sem dv) é 11 digitos
                $nnum = formata_numero($dadosboleto["carteira"],2,0).formata_numero($dadosboleto["nosso_numero"],11,0);
                //dv do nosso número
                $dv_nosso_numero = c_contaBanco::mod11($nnum, 7);
                //$dv_nosso_numero = digitoVerificador_nossonumero($nnum);

                //conta cedente (sem dv) é 7 digitos
                $conta_cedente = formata_numero($dadosboleto["conta"],7,0);
                //dv da conta cedente
                $conta_cedente_dv = formata_numero($dadosboleto["conta_dv"],1,0);

                //$ag_contacedente = $agencia . $conta_cedente;

                // 43 numeros para o calculo do digito verificador do codigo de barras
                $dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$agencia$nnum$conta_cedente".'0', 9, 0);
                // Numero para o codigo de barras com 44 digitos
                $linha1 = $codigobanco;
                $linha1 .= $nummoeda;
                $linha1 .= $dv;
                $linha1 .= $fator_vencimento;
                $linha1 .= $valor;
                $linha1 .= $agencia;
                $linha1 .= $nnum;
                $linha1 .= $conta_cedente;
                $linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$agencia$nnum$conta_cedente"."0";

                $dadosboleto["agencia_dv"] = '7';

                $nossonumero = substr($nnum,0,2).'/'.substr($nnum,2).'-'.$dv_nosso_numero;
                $agencia_codigo = $agencia."-".$dadosboleto["agencia_dv"]." / ". $conta_cedente ."-". $conta_cedente_dv;


                $dadosboleto["codigo_barras"] = $linha;
                $dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
                $dadosboleto["agencia_codigo"] = $agencia_codigo;
                $dadosboleto["nosso_numero"] = $nossonumero;
                $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

                ////////////////////////
                include($dir."/../../boleto/include/layout_bradesco.php");
                break;
            case '341':
                // ****************CALCULA VALORES NOSSO NUMERO - retirado de funções_itau
                $codigobanco = "341";
                $codigo_banco_com_dv = geraCodigoBanco($codigobanco);
                $nummoeda = "9";
                $fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);
                
                //valor tem 10 digitos, sem virgula
                $valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
                //agencia é 4 digitos
                $agencia = formata_numero($dadosboleto["agencia"],4,0);
                //conta é 5 digitos + 1 do dv
                $conta = formata_numero($dadosboleto["conta"],5,0);
                $conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
                //carteira 175
                $carteira = $dadosboleto["carteira"];
                //nosso_numero no maximo 8 digitos
                $nnum = formata_numero($dadosboleto["nosso_numero"],8,0);
                
                $codigo_barras = $codigobanco.$nummoeda.$fator_vencimento.$valor.$carteira.$nnum.modulo_10($agencia.$conta.$carteira.$nnum).$agencia.$conta.modulo_10($agencia.$conta).'000';
                // 43 numeros para o calculo do digito verificador
                $dv = digitoVerificador_barra($codigo_barras);
                // Numero para o codigo de barras com 44 digitos
                $linha = substr($codigo_barras,0,4).$dv.substr($codigo_barras,4,43);
                
                $nossonumero = $carteira.'/'.$nnum.'-'.modulo_10($agencia.$conta.$carteira.$nnum);
                $agencia_codigo = $agencia." / ". $conta."-".modulo_10($agencia.$conta);
                
                $dadosboleto["codigo_barras"] = $linha;
                $dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha); // verificar
                $dadosboleto["agencia_codigo"] = $agencia_codigo ;
                $dadosboleto["nosso_numero"] = $nossonumero;
                $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
                ////////////////////////
                include($dir."/../../boleto/include/layout_itau.php");
                break;
            case '748':
                $dadosboleto["posto"] = "56";
                $dadosboleto["byte_idt"] = "2";
                $dadosboleto["inicio_nosso_numero"] = date("y", strtotime($lanc[$i]['EMISSAO']));
                
                if (strlen($dadosboleto["nosso_numero"]) > 5)
                    $dadosboleto["nosso_numero"] = substr($dadosboleto["nosso_numero"], 3, 5);
                include_once($dir."/../../class/blt/funcoes_sicredi.php");
                c_lancamento::gravaNossoNumero($lanc[$i]['ID'], $nossonumero_dv);
                include($dir."/../../boleto/include/layout_sicredi.php");
                break;
    
        }
        //include_once($dir."/../../template/blt/layout_itau.php");
        //include($dir."/../../boleto/include/layout_bradesco.php");
        if ($i < $numBoleto):
            echo "<br><br><br><br><br><br><br><br><br>";
        endif;

        
    }

}//fim desenhaCadastroBanco

//-------------------------------------------------------------
}
//	END OF THE CLASS
  
$boleto = new p_boleto();
$boleto->imprimeBoleto();
?>
