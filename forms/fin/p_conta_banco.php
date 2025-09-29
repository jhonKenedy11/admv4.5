<?php
/**
 * @package   astecv3
 * @name      p_contaBanco
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      23/04/2016
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/fin/c_conta_banco.php");

//Class p_contaBanco
Class p_contaBanco extends c_contaBanco {

public $m_submenu = NULL;
private $m_letra = NULL;
public $smarty = NULL;
public $m_msg = NULL;
public $m_tipoMsg =NULL;


//---------------------------------------------------------------
//---------------------------------------------------------------
function __construct(){
        @set_exception_handler(array($this, 'exception_handler'));

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        // $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/fin";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Contas Bancarias");
        $this->smarty->assign('colVis', "[ 0, 1, 2, 3, 4, 5  ]"); 
        $this->smarty->assign('disableSort', "[ 6 ]"); 
        $this->smarty->assign('numLine', "25"); 

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setNomeInterno(isset($parmPost['nomeInterno']) ? $parmPost['nomeInterno'] : '');
        $this->setNomeContaBanco(isset($parmPost['nomeContaBanco']) ? $parmPost['nomeContaBanco'] : '');
        $this->setBanco(isset($parmPost['banco']) ? $parmPost['banco'] : '0');
        $this->setAgencia(isset($parmPost['agencia']) ? $parmPost['agencia'] : '0');
        $this->setContaCorrente(isset($parmPost['contaCorrente']) ? $parmPost['contaCorrente'] : '0');
        $this->setContato(isset($parmPost['contato']) ? $parmPost['contato'] : '');
        $this->setDescontoBonificacao(isset($parmPost['descontoBonificacao']) ? $parmPost['descontoBonificacao'] : '');
        $this->setStatus(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');     
        $this->setMulta(isset($parmPost['multa']) ? $parmPost['multa'] : '0');   
        $this->setJuros(isset($parmPost['juros']) ? $parmPost['juros'] : '0');   
        $this->setCarteiraCobranca(isset($parmPost['carteiraCobranca']) ? $parmPost['carteiraCobranca'] : '0'); 
        $this->setNumNoBanco(isset($parmPost['numNoBanco']) ? $parmPost['numNoBanco'] : '0');   
        $this->setDiaProtesto(isset($parmPost['diaProtesto']) ? $parmPost['diaProtesto'] : '0');   
        $this->setMsgBoleto(isset($parmPost['msgBoleto']) ? $parmPost['msgBoleto'] : '');  
        $this->setUltimoNossoNumero(isset($parmPost['UltimoNossoNro']) ? $parmPost['UltimoNossoNro'] : '');               
        
        // include do javascript
        // include ADMjs . "/fin/s_fin.js";

}


/**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
function controle(){
  switch ($this->m_submenu){
		case 'cadastrar':
			if ($this->verificaDireitoUsuario('FinContaBancaria', 'I')){
				$this->desenhaCadastroContaBanco($this->$m_msg, $this->m_tipoMsg);
			}
			break;
		case 'alterar':
			if ($this->verificaDireitoUsuario('FinContaBancaria', 'A')){
				$contaBanco = $this->select_contaBanco();
				$this->setId($contaBanco[0]['CONTA']);
				$this->setNomeInterno($contaBanco[0]['NOMEINTERNO']);
				$this->setNomeContaBanco($contaBanco[0]['NOMECONTABANCO']);
				$this->setBanco($contaBanco[0]['BANCO']);
				$this->setAgencia($contaBanco[0]['AGENCIA']);
				$this->setContaCorrente($contaBanco[0]['CONTACORRENTE']);
				$this->setContato($contaBanco[0]['CONTATO']);
                $this->setDescontoBonificacao($contaBanco[0]['DESCONTOBONIFICACAO']);
                $this->setStatus($contaBanco[0]['STATUS']);
                $this->setMulta($contaBanco[0]['MULTA']);
                $this->setJuros($contaBanco[0]['JUROS']);                
                $this->setDiaProtesto($contaBanco[0]['PROTESTO']);
                $this->setNumNoBanco($contaBanco[0]['NUMNOBANCO']);
                $this->setCarteiraCobranca($contaBanco[0]['CARTEIRA']);
                $this->setMsgBoleto($contaBanco[0]['MSGBLOQUETO']);
                $this->setUltimoNossoNumero($contaBanco[0]['ULTIMONOSSONRO']);
				$this->desenhaCadastroContaBanco($this->$m_msg, $this->m_tipoMsg);
            }
			break;
		case 'inclui':
			if ($this->verificaDireitoUsuario('FinContaBancaria', 'I')){
				if ($this->existeContaBanco()){
					$this->m_submenu = "cadastrar";
   					$this->desenhaCadastroContaBanco("CONTA BANCARIA JÁ EXISTENTE, ALTERE O CÓDIGO DA CONTA", "alerta");}
				else {
                    $this->mostraContaBanco($this->incluiContaBanco());
                }
			}		
			break;
		case 'altera':
			if ($this->verificaDireitoUsuario('FinContaBancaria', 'A')){
				$this->mostraContaBanco($this->alteraContaBanco());
			}
			break;
		case 'exclui':
			if ($this->verificaDireitoUsuario('FinContaBancaria', 'E')){
                            $this->mostraContaBanco($this->excluiContaBanco());
			}
			break;
		default:
  			if ($this->verificaDireitoUsuario('FinContaBancaria', 'C')){
				$this->mostraContaBanco($this->$m_msg, $this->m_tipoMsg);
  			}
	
	}

} // fim controle

 /**
 * <b> Desenha form de cadastro ou alteração Banco. </b>
 * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
 * @param String $tipoMsg tipo da mensagem sucesso/alerta
 */
function desenhaCadastroContaBanco($mensagem=NULL,$tipoMsg =NULL){

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('tipoMsg', $tipoMsg);
    
    $this->smarty->assign('id', $this->getId());
    $this->smarty->assign('nomeInterno', "'".$this->getNomeInterno()."'");
    $this->smarty->assign('nomeContaBanco', "'".$this->getNomeContaBanco()."'");
    $this->smarty->assign('banco', "'".$this->getBanco()."'");
    $this->smarty->assign('agencia', "'".$this->getAgencia()."'");
    $this->smarty->assign('contaCorrente', "'".$this->getContaCorrente()."'");
    $this->smarty->assign('contato', "'".$this->getContato()."'");
    $this->smarty->assign('descontoBonificacao', "'".$this->getDescontoBonificacao()."'");
    $this->smarty->assign('multa', $this->getMulta());
    $this->smarty->assign('juros', $this->getJuros());
    $this->smarty->assign('carteiraCobranca', "'".$this->getCarteiraCobranca()."'");
    $this->smarty->assign('numNoBanco', "'".$this->getNumNoBanco()."'");
    $this->smarty->assign('diaProtesto', $this->getDiaProtesto());
    $this->smarty->assign('msgBoleto', $this->getMsgBoleto());
    $this->smarty->assign('ultimoNossoNro', "'".$this->getUltimoNossoNumero()."'");

    // banco
    $consulta = new c_banco();
    $sql = "select banco as id, nome as descricao from fin_banco order by banco";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $banco_ids[$i] = $result[$i]['ID'];
            $banco_names[$i] = $result[$i]['ID'] ." - ".$result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('banco_ids', $banco_ids);
    $this->smarty->assign('banco_names', $banco_names);
    $this->smarty->assign('banco_id', $this->getBanco());	
    
    // situacao
    $consulta = new c_banco();
    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='SituacaoUsuario')";
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado;
    for ($i=0; $i < count($result); $i++){
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
    }
    $this->smarty->assign('situacao_ids', $situacao_ids);
    $this->smarty->assign('situacao_names', $situacao_names);
    $this->smarty->assign('situacao_id', $this->getStatus());	


    $this->smarty->display('conta_banco_cadastro.tpl');
    
}//fim desenhaCadastroContaBanco

/**
* <b> Listagem de todas as registro cadastrados de tabela contaBanco. </b>
* @param String $mensagem Mensagem que ira mostrar na tela
*/
function mostraContaBanco($mensagem){

  
    $lanc = $this->select_contaBanco_geral();

    $this->smarty->assign('pathImagem', $this->img);
    $this->smarty->assign('mensagem', $mensagem);
    $this->smarty->assign('letra', $this->m_letra);
    $this->smarty->assign('subMenu', $this->m_submenu);
    $this->smarty->assign('lanc', $lanc);

	
    $this->smarty->display('conta_banco_mostra.tpl');
	

} //fim mostraContaBanco
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$contaBanco = new p_contaBanco();
                              
$contaBanco->controle();
 
  
?>
