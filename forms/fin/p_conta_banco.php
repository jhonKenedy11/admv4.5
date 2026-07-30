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
include_once($dir . "/../../class/fin/c_conta_banco.php");

//Class p_contaBanco
class p_contaBanco extends c_contaBanco
{

    public $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;
    public $m_msg = NULL;
    public $m_tipoMsg = NULL;


    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function __construct()
    {
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
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
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
        $dig = isset($parmPost['contaCorrenteDigito']) ? $parmPost['contaCorrenteDigito'] : '';
        $this->setContaCorrenteDigito(is_string($dig) ? substr(trim($dig), 0, 1) : '');
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
        // API de cobrança
        $this->setEnviaBoleto(isset($parmPost['envia_boleto']) ? $parmPost['envia_boleto'] : '');
        $this->setBradescoApiClientIdSandbox(isset($parmPost['bradesco_api_client_id_sandbox']) ? $parmPost['bradesco_api_client_id_sandbox'] : '');
        $this->setBradescoApiClientIdProduction(isset($parmPost['bradesco_api_client_id_production']) ? $parmPost['bradesco_api_client_id_production'] : '');
        $this->setBradescoApiClientSecretSandbox(isset($parmPost['bradesco_api_client_secret_sandbox']) ? $parmPost['bradesco_api_client_secret_sandbox'] : '');
        $this->setBradescoApiClientSecretProduction(isset($parmPost['bradesco_api_client_secret_production']) ? $parmPost['bradesco_api_client_secret_production'] : '');
        $this->setInterApiClientIdSandbox(isset($parmPost['inter_api_client_id_sandbox']) ? $parmPost['inter_api_client_id_sandbox'] : '');
        $this->setInterApiClientIdProduction(isset($parmPost['inter_api_client_id_production']) ? $parmPost['inter_api_client_id_production'] : '');
        $this->setInterApiClientSecretSandbox(isset($parmPost['inter_api_client_secret_sandbox']) ? $parmPost['inter_api_client_secret_sandbox'] : '');
        $this->setInterApiClientSecretProduction(isset($parmPost['inter_api_client_secret_production']) ? $parmPost['inter_api_client_secret_production'] : '');
        $this->setAmbiente(isset($parmPost['ambiente']) ? $parmPost['ambiente'] : 'S'); // S - sandbox, P - producao
        $this->setInterSituacaoMap(isset($parmPost['inter_situacao_map_json']) ? $parmPost['inter_situacao_map_json'] : null);
        $this->setBradescoSituacaoMap(isset($parmPost['bradesco_situacao_map_json']) ? $parmPost['bradesco_situacao_map_json'] : null);
        // include do javascript
        // include ADMjs . "/fin/s_fin.js";

    }


    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle()
    {
        switch ($this->m_submenu) {
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('FinContaBancaria', 'I')) {
                    $this->desenhaCadastroContaBanco($this->$m_msg, $this->m_tipoMsg);
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('FinContaBancaria', 'A')) {
                    $contaBanco = $this->select_contaBanco();
                    $this->setId($contaBanco[0]['CONTA']);
                    $this->setNomeInterno($contaBanco[0]['NOMEINTERNO']);
                    $this->setNomeContaBanco($contaBanco[0]['NOMECONTABANCO']);
                    $this->setBanco($contaBanco[0]['BANCO']);
                    $this->setAgencia($contaBanco[0]['AGENCIA']);
                    $this->setContaCorrente($contaBanco[0]['CONTACORRENTE']);
                    $digDb = isset($contaBanco[0]['CONTA_CORRENTE_DIGITO']) ? $contaBanco[0]['CONTA_CORRENTE_DIGITO'] : (isset($contaBanco[0]['conta_corrente_digito']) ? $contaBanco[0]['conta_corrente_digito'] : '');
                    $this->setContaCorrenteDigito(is_string($digDb) ? substr(trim($digDb), 0, 1) : '');
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
                    $this->setEnviaBoleto($contaBanco[0]['ENVIA_BOLETO'] ?? 'R');
                    $this->setBradescoApiClientIdSandbox($contaBanco[0]['BRADESCO_API_CLIENT_ID_SANDBOX']);
                    $this->setBradescoApiClientIdProduction($contaBanco[0]['BRADESCO_API_CLIENT_ID_PRODUCTION']);
                    $this->setBradescoApiClientSecretSandbox($contaBanco[0]['BRADESCO_API_CLIENT_SECRET_SANDBOX']);
                    $this->setBradescoApiClientSecretProduction($contaBanco[0]['BRADESCO_API_CLIENT_SECRET_PRODUCTION']);
                    $this->setInterApiClientIdSandbox($contaBanco[0]['INTER_API_CLIENT_ID_SANDBOX']);
                    $this->setInterApiClientIdProduction($contaBanco[0]['INTER_API_CLIENT_ID_PRODUCTION']);
                    $this->setInterApiClientSecretSandbox($contaBanco[0]['INTER_API_CLIENT_SECRET_SANDBOX']);
                    $this->setInterApiClientSecretProduction($contaBanco[0]['INTER_API_CLIENT_SECRET_PRODUCTION']);
                    $this->setAmbiente($contaBanco[0]['AMBIENTE']);
                    $this->setInterSituacaoMap(isset($contaBanco[0]['INTER_SITUACAO_MAP']) ? $contaBanco[0]['INTER_SITUACAO_MAP'] : null);
                    $this->setBradescoSituacaoMap(isset($contaBanco[0]['BRADESCO_SITUACAO_MAP']) ? $contaBanco[0]['BRADESCO_SITUACAO_MAP'] : null);
                    $this->desenhaCadastroContaBanco($this->$m_msg, $this->m_tipoMsg);
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('FinContaBancaria', 'I')) {
                    if ($this->existeContaBanco()) {
                        $this->m_submenu = "cadastrar";
                        $this->desenhaCadastroContaBanco("CONTA BANCARIA JÁ EXISTENTE, ALTERE O CÓDIGO DA CONTA", "alerta");
                    } else {
                        $this->mostraContaBanco($this->incluiContaBanco());
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('FinContaBancaria', 'A')) {
                    $this->mostraContaBanco($this->alteraContaBanco());
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('FinContaBancaria', 'E')) {
                    $this->mostraContaBanco($this->excluiContaBanco());
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('FinContaBancaria', 'C')) {
                    $this->mostraContaBanco($this->$m_msg, $this->m_tipoMsg);
                }
        }
    } // fim controle

    /**
     * <b> Desenha form de cadastro ou alteração Banco. </b>
     * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
     * @param String $tipoMsg tipo da mensagem sucesso/alerta
     */
    function desenhaCadastroContaBanco($mensagem = NULL, $tipoMsg = NULL)
    {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('nomeInterno', "'" . $this->getNomeInterno() . "'");
        $this->smarty->assign('nomeContaBanco', "'" . $this->getNomeContaBanco() . "'");
        $this->smarty->assign('banco', "'" . $this->getBanco() . "'");
        $this->smarty->assign('agencia', "'" . $this->getAgencia() . "'");
        $this->smarty->assign('contaCorrente', "'" . $this->getContaCorrente() . "'");
        $digSm = $this->getContaCorrenteDigito();
        $this->smarty->assign('contaCorrenteDigito', "'" . ($digSm !== null && $digSm !== false ? $digSm : '') . "'");
        $this->smarty->assign('contato', "'" . $this->getContato() . "'");
        $this->smarty->assign('descontoBonificacao', "'" . $this->getDescontoBonificacao('F') . "'");
        $this->smarty->assign('multa', "'" . $this->getMulta('F') . "'");
        $this->smarty->assign('juros', "'" . $this->getJuros('F') . "'");
        $this->smarty->assign('carteiraCobranca', "'" . $this->getCarteiraCobranca() . "'");
        $this->smarty->assign('numNoBanco', "'" . $this->getNumNoBanco() . "'");
        $this->smarty->assign('diaProtesto', $this->getDiaProtesto());
        $this->smarty->assign('msgBoleto', $this->getMsgBoleto());
        $this->smarty->assign('ultimoNossoNro', "'" . $this->getUltimoNossoNumero() . "'");
        $this->smarty->assign('envia_boleto', $this->getEnviaBoleto());
        $this->smarty->assign('bradesco_api_client_id_sandbox', $this->getBradescoApiClientIdSandbox() ?? '');
        $this->smarty->assign('bradesco_api_client_id_production', $this->getBradescoApiClientIdProduction() ?? '');
        $this->smarty->assign('bradesco_api_client_secret_sandbox', $this->getBradescoApiClientSecretSandbox() ?? '');
        $this->smarty->assign('bradesco_api_client_secret_production', $this->getBradescoApiClientSecretProduction() ?? '');
        $this->smarty->assign('inter_api_client_id_sandbox', $this->getInterApiClientIdSandbox() ?? '');
        $this->smarty->assign('inter_api_client_id_production', $this->getInterApiClientIdProduction() ?? '');
        $this->smarty->assign('inter_api_client_secret_sandbox', $this->getInterApiClientSecretSandbox() ?? '');
        $this->smarty->assign('inter_api_client_secret_production', $this->getInterApiClientSecretProduction() ?? '');
        $this->smarty->assign('ambiente', $this->getAmbiente());
        $path = ADMhttpBib . '/boleto/imagens';
        $this->smarty->assign('pathImagem', $path);
        // banco
        $consulta = new c_banco();
        $sql = "select banco as id, nome as descricao from fin_banco order by banco";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $banco_ids[$i] = $result[$i]['ID'];
            $banco_names[$i] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
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
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $this->getStatus());

        // situacao lancamento (mapeamento APIs)
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='SituacaoPgto')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $situacaoLanc_ids = array('');
        $situacaoLanc_names = array('-- Selecione --');
        for ($i = 0; $i < count($result); $i++) {
            $situacaoLanc_ids[$i + 1] = $result[$i]['ID'];
            $situacaoLanc_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacaoLanc_ids', $situacaoLanc_ids);
        $this->smarty->assign('situacaoLanc_names', $situacaoLanc_names);

        $this->smarty->assign('inter_situacao_map', $this->getInterSituacaoMapArray());
        $this->smarty->assign('bradesco_situacao_map', $this->getBradescoSituacaoMapArray());

        $inter_situacoes = array(
            array('id' => 'A_RECEBER',        'label' => 'A Receber'),
            array('id' => 'RECEBIDO',         'label' => 'Recebido'),
            array('id' => 'ATRASADO',         'label' => 'Atrasado'),
            array('id' => 'CANCELADO',        'label' => 'Cancelado'),
            array('id' => 'EXPIRADO',         'label' => 'Expirado'),
            array('id' => 'MARCADO_RECEBIDO', 'label' => 'Marcado Recebido'),
            array('id' => 'FALHA_EMISSAO',    'label' => 'Falha Emissão'),
            array('id' => 'EM_PROCESSAMENTO', 'label' => 'Em Processamento'),
            array('id' => 'PROTESTO',         'label' => 'Protesto'),
        );
        $this->smarty->assign('inter_situacoes', $inter_situacoes);


        $bradesco_situacoes = array(
            array('id' => '1',   'label' => 'A VENCER / VENCIDO'),
            array('id' => '2',   'label' => 'COM PAGAMENTO VINCULADO'),
            array('id' => '3',   'label' => 'COM PAGTO VINCULADO E INSTRUCAO AGENDADA'),
            array('id' => '4',   'label' => 'COM INSTRUCAO DE PROTESTO'),
            array('id' => '5',   'label' => 'COM INSTR. DE PROTESTO E PAGTO VINCULADO'),
            array('id' => '6',   'label' => 'EM PODER DO CARTORIO'),
            array('id' => '7',   'label' => 'COM INSTR. E PEDIDO SUSTACAO - SEM BAIXA'),
            array('id' => '8',   'label' => 'COM INSTR. E PEDIDO SUSTACAO - COM BAIXA'),
            array('id' => '9',   'label' => 'EM CARTORIO E PEDIDO SUSTACAO - S/ BAIXA'),
            array('id' => '10',   'label' => 'EM CARTORIO E PEDIDO SUSTACAO - C/ BAIXA'),
            array('id' => '11',   'label' => 'COM BAIXA SOLICITADA'),
            array('id' => '12',   'label' => 'COM EXECUCAO SOLICITADA'),
            array('id' => '13',   'label' => 'PAGO NO DIA'),
            array('id' => '14',   'label' => 'EM CARTORIO COM PAGAMENTO VINCULADO'),
            array('id' => '15',   'label' => 'INSTR. PED. SUST. - S/ BAIXA - PGTO VINC'),
            array('id' => '16',   'label' => 'INSTR. PED. SUST. - C/ BAIXA - PGTO VINC'),
            array('id' => '17',   'label' => 'CARTORIO PED. SUST. -S/ BAIXA - PGTO VINC'),
            array('id' => '18',   'label' => 'CARTORIO PED. SUST. -C/ BAIXA - PGTO VINC'),
            array('id' => '19',   'label' => 'SUSTADO SEM REMESSA AO CARTORIO'),
            array('id' => '20',   'label' => 'SUSTADO RETIRADO DE CARTORIO'),
            array('id' => '21',   'label' => 'SUSTADO JUDICIALMENTE'),
            array('id' => '22',   'label' => 'PENDENTE NO DISTRIBUIDOR'),
            array('id' => '23',   'label' => 'TÍTULO COM IRREGULARIDADE'),
            array('id' => '24',   'label' => 'AGUARDANDO APONTAMENTO DE IRREGULARIDADE'),
            array('id' => '25',   'label' => 'AGUARDANDO SOLICIT. DE SUSTACAO C/ BAIXA'),
            array('id' => '26',   'label' => 'AGUARDANDO SOLICIT. DE SUSTACAO S/BAIXA'),
            array('id' => '27',   'label' => 'SOLIC. SUSTACAO C/ENVIO CARTOR. C/BAIXA'),
            array('id' => '28',   'label' => 'SOLIC. SUSTACAO C/ENVIO CARTOR. S/BAIXA'),
            array('id' => '29',   'label' => 'EM CARTORIO COM EDITAL'),
            array('id' => '30',   'label' => 'COM PAGAMENTO RETIDO'),
            array('id' => '31',   'label' => 'COM INSTR NEGATIVACAO'),
            array('id' => '32',   'label' => 'EM PROC NEGATIVACAO'),
            array('id' => '33',   'label' => 'NEGATIVADO'),
            array('id' => '34',   'label' => 'EXCL NEG S/BAIXA'),
            array('id' => '35',   'label' => 'EXCL NEG C/BAIXA'),
            array('id' => '51',   'label' => 'POR ACERTO'),
            array('id' => '52',   'label' => 'BAIXA POR REGISTRO DUPLICADO'),
            array('id' => '53',   'label' => 'POR DECURSO DE PRAZO'),
            array('id' => '54',   'label' => 'POR MEDIDA JUDICIAL'),
            array('id' => '55',   'label' => 'POR REMESSA (CEB)'),
            array('id' => '56',   'label' => 'COBRADO - POR RASTREAMENTO'),
            array('id' => '57',   'label' => 'CONFORME SEU PEDIDO'),
            array('id' => '58',   'label' => 'PROTESTADO'),
            array('id' => '59',   'label' => 'DEVOLVIDO'),
            array('id' => '60',   'label' => 'ENTREGUE FRANCO DE PAGAMENTO'),
            array('id' => '61',   'label' => 'PAGO'),
            array('id' => '62',   'label' => 'PAGO EM CARTORIO'),
            array('id' => '63',   'label' => 'SUSTADO RETIRADO DE CARTORIO'),
            array('id' => '64',   'label' => 'SUSTADO SEM REMESSA A CARTORIO'),
            array('id' => '65',   'label' => 'TRANSFERIDO PARA DESCONTO'),
            array('id' => '66',   'label' => 'CRÉDITO EXDD'),
            array('id' => '67',   'label' => 'CRÉDITO EXDD - PAGO EM CARTORIO'),
            array('id' => '68',   'label' => 'COBRADO - POR BAIXA MANUAL'),
            array('id' => '69',   'label' => 'COBRADO-POR BAIXA MANUAL-PAGO EM CATORIO'),
            array('id' => '70',   'label' => 'TRANSFERENCIA RECEBIVEIS'),
            array('id' => '71',   'label' => 'DEVOLUCAO TRANSF RECEBIVEIS'),
            array('id' => '72',   'label' => 'TRANSF. FUNDOS RECEB. /COBRANCA'),
            array('id' => '73',   'label' => 'DEV. FUNDOS RECEB. /COBRANCA'),
            array('id' => '98',   'label' => 'POR REGISTRO DUPLICADO'),
            array('id' => '99',   'label' => 'COM REATIVACAO SOLICITADA'),
        );
        $this->smarty->assign('bradesco_situacoes', $bradesco_situacoes);


        $this->smarty->display('conta_banco_cadastro.tpl');
    }//fim desenhaCadastroContaBanco

    /**
     * <b> Listagem de todas as registro cadastrados de tabela contaBanco. </b>
     * @param String $mensagem Mensagem que ira mostrar na tela
     */
    function mostraContaBanco($mensagem)
    {


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
