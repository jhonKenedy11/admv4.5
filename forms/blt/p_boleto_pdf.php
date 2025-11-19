<?php
/**
 * @package   astecv3
 * @name      p_boleto_pdf
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy dos Santos Mello <jhon.kened11@gmail.com>
 * @date      20/09/2025
 */
if (!defined('ADMpath')): exit;
endif;

$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/blt/c_boleto.php");
include_once($dir."/../../class/crm/c_conta.php");
include_once($dir."/../../class/fin/c_conta_banco.php");
include_once($dir."/../../class/fin/c_lancamento.php");


// Carrega o mPDF v6.1
require_once $dir . "/../../bib/mpdf/mpdf.php";


//Class p_boleto_pdf
Class p_boleto_pdf extends c_boleto {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;
    

    /**
     * Construtor da classe p_boleto_pdf
     * 
     * Inicializa a classe obtendo parâmetros POST e GET de forma segura,
     * configura a sessão do usuário e define variáveis de controle para
     * geração de PDFs de boletos bancários.
     * 
     * @return void
     * @author ADMSistema
     * @since 4.5
     */
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
    }

    /**
     * Gera PDF com todos os boletos baseado nos lançamentos fornecidos
     * 
     * Função principal que processa um array de lançamentos financeiros
     * e gera um PDF único contendo todos os boletos bancários correspondentes.
     * Inclui controle de erros, geração de nosso número e formatação adequada.
     * 
     * @param array $lanc Array com os lançamentos dos boletos contendo:
     *                    - ID: identificador do lançamento
     *                    - CONTA: conta bancária
     *                    - NOSSONUMERO: nosso número do boleto
     *                    - PESSOA: ID do cliente
     *                    - VENCIMENTO: data de vencimento
     *                    - TOTAL: valor do boleto
     * @return string|array Conteúdo do PDF como string ou array com status/msg em caso de erro
     * @throws Exception Em caso de erro na geração do PDF
     * @author ADMSistema
     * @since 4.5
     * 
     * @example
     * $pdf = new p_boleto_pdf();
     * $lancamentos = [['ID' => 1, 'CONTA' => '123', ...]];
     * $pdfContent = $pdf->geraPdfBoletos($lancamentos);
     */
    public function geraPdfBoletos(array $lanc) {
        try {
            // Verifica se houve erro na consulta de lançamentos e inclui no log de erro
            // Aceita array indexado de lançamentos; só bloqueia se estiver vazio
            // ou quando vier um objeto-resposta com status=false
            if (empty($lanc) || (isset($lanc['status']) && $lanc['status'] === false)) {
                return ['status' => false, 'msg' => 'Lancamentos nao informado'];
            }

            // AJUSTE 1: Controle de Erros
            // Salva o nível de report de erros atual
            $current_error_reporting = error_reporting();
            // Desativa a exibição de notices, que podem quebrar o HTML
            error_reporting($current_error_reporting & ~E_NOTICE);

            $mpdf = new mPDF([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'tempDir' => sys_get_temp_dir(), // importante!
                'simpleTables' => true,
                'packTableData' => true,                
            ]);
            
            $mpdf->showImageErrors = false;
            $mpdf->debug = false;
            
            $htmlContent = '';
            $objConta = new c_conta;
            $objContaBanco = new c_contaBanco;

            for ($i = 0; $i < count($lanc); $i++) {
                // Busca dados da conta bancária
                $objContaBanco->setId($lanc[$i]['CONTA']);
                $arrContaBanco = $objContaBanco->select_ContaBanco();
                $banco = $arrContaBanco[0]['BANCO'];

                // Gera nosso número se não existir
                if (is_null($lanc[$i]['NOSSONUMERO'])){
                    $nossoNumero = $objContaBanco->geraNossoNumero($lanc[$i]['CONTA'], $arrContaBanco[0]['ULTIMONOSSONRO']);
                    $lanc[$i]['NOSSONUMERO'] = $nossoNumero;
                    c_lancamento::gravaNossoNumero($lanc[$i]['ID'], $nossoNumero);
                }

                // Monta dados do boleto
                $dadosboleto = $this->montaDadosBoleto($lanc[$i], $arrContaBanco[0], $objConta, $banco);
                
                // Gera HTML do boleto
                $boletoHtml = $this->geraHtmlBoleto($dadosboleto, $banco, $arrContaBanco[0]);

                // Verifica se houve erro na geração do HTML do boleto e inclui no log de erro
                if ($boletoHtml['status'] == false) {
                    return ['status' => false, 'msg' => $boletoHtml['msg']];
                }
                
                
                // Adiciona quebra de página entre boletos
                if ($i > 0) {
                    $htmlContent .= '<pagebreak />';
                }
                $htmlContent .= $boletoHtml;
            }

            // Carrega HTML no mPDF
            $mpdf->WriteHTML($htmlContent);

            // Configurações de proteção
            // Primeiro parâmetro: permissões
            // Segundo parâmetro: senha do usuário (para abrir o PDF)
            // Terceiro parâmetro: senha do proprietário (para mudar permissões)
            // $primeiros4 = $this->getPrimeirosDigitos($dadosboleto["cpf_cnpj"]);

            // // Configuração do mPDF com proteção usando os 4 dígitos
            // $mpdf->SetProtection(
            //     ['print'], // permissões: 'copy', 'print', 'modify'
            //     $primeiros4, // senha para abrir (4 primeiros dígitos)
            //     'superuser@12345' // senha do admin
            // );
            
            // Salva PDF para análise

            //VERIFIQUE OS DIREITOS DE ACESSO AO PDF
            // $debugPath = '/var/www/html/admv4.5/debug/boletos/';
            // if (!file_exists($debugPath)) {
            //     mkdir($debugPath, 0775, true);
            // }
            
            // $debugFileName = 'boleto_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.pdf';
            // $debugFilePath = $debugPath . $debugFileName;
            
            // $mpdf->Output($debugFilePath, 'F');

            // exit;
            
            // Restaura o nível de report de erros original
            error_reporting($current_error_reporting);
            
            // Retorna o PDF como string
            return $mpdf->Output('', 'S');

        } catch (Exception $e) {
            // Restaura o nível de report de erros em caso de exceção
            if (isset($current_error_reporting)) {
                error_reporting($current_error_reporting);
            }
            return ['status' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * Monta os dados do boleto
     * 
     * Processa e formata todos os dados necessários para geração do boleto,
     * incluindo dados do cliente, empresa, conta bancária, instruções de pagamento,
     * cálculos de multa e juros.
     * 
     * @param array $lanc Dados do lançamento financeiro
     * @param array $arrContaBanco Dados da conta bancária
     * @param c_conta $objConta Objeto da classe conta para consultas
     * @param string $banco Código do banco (ex: '237' para Bradesco)
     * @return array Array associativo com todos os dados formatados do boleto:
     *               - nosso_numero: nosso número do boleto
     *               - numero_documento: número do documento
     *               - data_vencimento: data de vencimento formatada
     *               - valor_boleto: valor formatado
     *               - sacado: dados do cliente
     *               - endereco1/endereco2: endereço do cliente
     *               - instrucoes1-4: instruções de pagamento
     *               - dados da empresa e conta bancária
     * @author ADMSistema
     * @since 4.5
     */
    private function montaDadosBoleto($lanc, $arrContaBanco, $objConta, $banco) {
        $dadosboleto = array();

        // Dados básicos
        $dadosboleto["nosso_numero"] = $lanc['NOSSONUMERO'];
        $dadosboleto["numero_documento"] = (($lanc['DOCTO']=='0') ? $dadosboleto["nosso_numero"] : $lanc['DOCTO']."-".$lanc['PARCELA']);
        $dadosboleto["data_vencimento"] = date("d/m/Y", strtotime($lanc['VENCIMENTO']));
        $dadosboleto["data_documento"] = date("d/m/Y", strtotime($lanc['EMISSAO']));
        $dadosboleto["data_processamento"] = date("d/m/Y");
        $dadosboleto["valor_boleto"] = number_format($lanc['TOTAL'], 2, ',', '');

        // Dados do cliente
        $objConta->setId($lanc['PESSOA']);
        $arrConta = $objConta->select_conta();
        
        if ($arrConta[0]['PESSOA'] == 'J') {
            $CnpjCPF = substr($arrConta[0]['CNPJCPF'], 0, 2).'.'.substr($arrConta[0]['CNPJCPF'], 2, 3).'.'.substr($arrConta[0]['CNPJCPF'], 5, 3).'/'.substr($arrConta[0]['CNPJCPF'], 8, 4).'-'.substr($arrConta[0]['CNPJCPF'], 12, 2);
        } else {
            $CnpjCPF = substr($arrConta[0]['CNPJCPF'], 0, 3).'.'.substr($arrConta[0]['CNPJCPF'], 3, 3).'.'.substr($arrConta[0]['CNPJCPF'], 6, 3).'-'.substr($arrConta[0]['CNPJCPF'], 9, 2);
        }

        $dadosboleto["sacado"] = $arrConta[0]['NOME']." - ".$CnpjCPF;
        $dadosboleto["endereco1"] = $arrConta[0]['ENDERECO'].", ".$arrConta[0]['NUMERO']." ".$arrConta[0]['COMPLEMENTO'];
        $dadosboleto["endereco2"] = $arrConta[0]['CIDADE']." - ".$arrConta[0]['UF']." -  CEP: ".$arrConta[0]['CEP'];

        // Informações para o cliente
        $dadosboleto["demonstrativo1"] = "Pagamento do Pedido Número: ".$lanc['NUMLCTO'];
        $dadosboleto["demonstrativo2"] = "";
        $dadosboleto["demonstrativo3"] = "";
        $dadosboleto["instrucoes1"] = "";
        $dadosboleto["instrucoes2"] = "";
        $dadosboleto["instrucoes3"] = "";

        // Cálculo de multa e juros
        $multa = ($arrContaBanco['MULTA']*$lanc['TOTAL'])/100;
        $juros = ($arrContaBanco['JUROS']*$lanc['TOTAL'])/100;
        if ($juros < 0.10):
            $juros = 0.10;
        endif;

        $dadosboleto["instrucoes1"] = "Após o vencimento, <br>";
        if ($arrContaBanco['MULTA'] > 0){
            $dadosboleto["instrucoes1"] .= "Cobrar multa de R$ ".number_format($multa, 2, ',', '.')."<br> ";
        }
        if ($arrContaBanco['JUROS'] > 0){
            $dadosboleto["instrucoes1"] .= "Cobrar mora diária de R$ ".number_format($juros , 2, ',', '.')."<br>";
        }
        if ($arrContaBanco['CARENCIA'] > 0){
            $dadosboleto["instrucoes2"] = "Não Receber até ".$arrContaBanco['CARENCIA']." dias após o vencimento<br>";
        }
        if ($arrContaBanco['DESCONTOBONIFICACAO'] > 0){
            $dadosboleto["instrucoes4"] = "Desconto de ".number_format($arrContaBanco['DESCONTOBONIFICACAO'],2,',', '.')."% para pagamento até a data do vencimento.";
        }
        if ($arrContaBanco['PROTESTO'] > 0){
            $dadosboleto["instrucoes3"] = "Protestar ".$arrContaBanco['PROTESTO']." dias após o vencimento";
        }

        // Dados opcionais
        $dadosboleto["quantidade"] = "";
        $dadosboleto["valor_unitario"] = "";
        $dadosboleto["aceite"] = "N";		
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = $arrContaBanco['ESPECIEDOC'];

        // Dados da conta bancária
        $dadosboleto["agencia"] = $arrContaBanco['AGENCIA'];
        $conta = explode("-", $arrContaBanco['CONTACORRENTE']);
        $dadosboleto["conta"] = $conta[0];
        $dadosboleto["conta_dv"] = $conta[1];
        $dadosboleto["carteira"] = $arrContaBanco['CARTEIRA'];

        // Dados da empresa
        $arrEmpresa = $this->busca_dadosEmpresaCC(substr($lanc['CENTROCUSTO'], 0,2).'000000');
        $dadosboleto["identificacao"] = $arrEmpresa[0]['NOMEEMPRESA'];
        $dadosboleto["cpf_cnpj"] = $arrEmpresa[0]['CNPJ'];
        $dadosboleto["endereco"] = $arrEmpresa[0]['TIPOEND']." ".$arrEmpresa[0]['TITULOEND']." ".
                $arrEmpresa[0]['ENDERECO'].", ".$arrEmpresa[0]['NUMERO']." ".$arrEmpresa[0]['COMPLEMENTO'];
        $dadosboleto["cidade_uf"] = $arrEmpresa[0]['CIDADE']." ".$arrEmpresa[0]['UF'];
        $dadosboleto["cedente"] = $arrEmpresa[0]['NOMEEMPRESA'];

        // AJUSTE 3: Inicializa TODOS os campos opcionais para evitar notices PHP
        $camposOpcionais = [
            "desconto_abatimento" => "",
            "juros_multa" => "",
            "valor_pago" => $dadosboleto["valor_boleto"],
            "instrucoes1" => isset($dadosboleto["instrucoes1"]) ? $dadosboleto["instrucoes1"] : "",
            "instrucoes2" => isset($dadosboleto["instrucoes2"]) ? $dadosboleto["instrucoes2"] : "",
            "instrucoes3" => isset($dadosboleto["instrucoes3"]) ? $dadosboleto["instrucoes3"] : "",
            "instrucoes4" => isset($dadosboleto["instrucoes4"]) ? $dadosboleto["instrucoes4"] : "",
            "demonstrativo1" => isset($dadosboleto["demonstrativo1"]) ? $dadosboleto["demonstrativo1"] : "",
            "demonstrativo2" => isset($dadosboleto["demonstrativo2"]) ? $dadosboleto["demonstrativo2"] : "",
            "demonstrativo3" => isset($dadosboleto["demonstrativo3"]) ? $dadosboleto["demonstrativo3"] : ""
        ];
        
        foreach ($camposOpcionais as $campo => $valorPadrao) {
            if (!isset($dadosboleto[$campo]) || empty($dadosboleto[$campo])) {
                $dadosboleto[$campo] = $valorPadrao;
            }
        }

        return $dadosboleto;
    }

    /**
     * Gera HTML do boleto baseado no banco
     * 
     * Função de roteamento que direciona a geração do HTML do boleto
     * para a função específica do banco correspondente.
     * 
     * @param array $dadosboleto Dados completos do boleto já formatados
     * @param string $banco Código do banco (ex: '237' para Bradesco)
     * @param array $arrContaBanco Dados da conta bancária
     * @return string|false HTML do boleto formatado ou false se banco não suportado
     * @author ADMSistema
     * @since 4.5
     */
    private function geraHtmlBoleto($dadosboleto, $banco, $arrContaBanco) {
        
        switch ($banco) {
            case '237': // Bradesco
                return $this->geraHtmlBradesco($dadosboleto, $arrContaBanco);
            default:
                return array('status' => false, 'msg' => 'Banco não suportado');
        }
    }

    /**
     * Gera HTML específico para Bradesco
     * 
     * Processa os dados do boleto aplicando as regras específicas do Banco Bradesco,
     * incluindo cálculos de dígitos verificadores, formação de código de barras,
     * linha digitável e nosso número conforme padrões do banco.
     * 
     * @param array $dadosboleto Dados básicos do boleto
     * @param array $arrContaBanco Dados da conta bancária do Bradesco
     * @return string HTML formatado do boleto Bradesco
     * @throws Exception Se arquivo de layout não for encontrado
     * @author ADMSistema
     * @since 4.5
     */
    private function geraHtmlBradesco($dadosboleto, $arrContaBanco) {
        // Inclui funções específicas do Bradesco
        $dir = (__DIR__);
        include_once($dir."/../../class/blt/funcoes_bradesco.php");
        
        // Cálculos específicos do Bradesco
        $codigobanco = "237";
        $codigo_banco_com_dv = geraCodigoBanco($codigobanco);
        $nummoeda = "9";
        $fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);

        $valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
        $agencia = formata_numero($dadosboleto["agencia"],4,0);
        $conta = formata_numero($dadosboleto["conta"],6,0);
        $conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
        $carteira = $dadosboleto["carteira"];

        $nnum = formata_numero($dadosboleto["carteira"],2,0).formata_numero($dadosboleto["nosso_numero"],11,0);
        $dv_nosso_numero = c_contaBanco::mod11($nnum, 7);

        $conta_cedente = formata_numero($dadosboleto["conta"],7,0);
        $conta_cedente_dv = formata_numero($dadosboleto["conta_dv"],1,0);

        $dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$agencia$nnum$conta_cedente".'0', 9, 0);
        $linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$agencia$nnum$conta_cedente"."0";

        $dadosboleto["agencia_dv"] = '7';
        $nossonumero = substr($nnum,0,2).'/'.substr($nnum,2).'-'.$dv_nosso_numero;
        $agencia_codigo = $agencia."-".$dadosboleto["agencia_dv"]." / ". $conta_cedente ."-". $conta_cedente_dv;

        $dadosboleto["codigo_barras"] = $linha;
        $dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
        $dadosboleto["agencia_codigo"] = $agencia_codigo;
        $dadosboleto["nosso_numero"] = $nossonumero;
        $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

        // Retorna HTML do layout Bradesco otimizado para mPDF
        return $this->getLayoutBradesco($dadosboleto);
    }



    /**
     * Retorna o layout HTML do Bradesco otimizado para mPDF v6.1
     * 
     * Carrega o template HTML específico do Bradesco otimizado para geração
     * de PDF com mPDF. Inclui validação de existência do arquivo de layout.
     * 
     * @param array $dadosboleto Dados completos do boleto já processados
     * @return string HTML do layout do boleto Bradesco
     * @throws Exception Se o arquivo de layout não for encontrado
     * @author ADMSistema
     * @since 4.5
     */
    private function getLayoutBradesco($dadosboleto) {
        // AJUSTE 2: Caminho dinâmico para o layout otimizado para mPDF
        $dir = (__DIR__);
        $layoutPath = $dir . '/../../boleto/include/layout_bradesco_email.php';
        
        // Verifique se o arquivo de layout existe antes de incluí-lo
        if (!file_exists($layoutPath)) {
            // Lança um erro ou retorna uma mensagem de erro clara
            throw new Exception("Arquivo de layout não encontrado em: " . $layoutPath);
        }

        ob_start();
        // Inclui o arquivo usando o caminho dinâmico e seguro
        include($layoutPath);
        return ob_get_clean();
    }

    /**
     * Extrai os 4 primeiros dígitos do CPF/CNPJ
     * 
     * Remove toda a formatação (pontos, traços, espaços) do CPF/CNPJ
     * e retorna apenas os 4 primeiros dígitos numéricos.
     * Utilizada para geração de senhas de proteção de PDF.
     * 
     * @param string $cpf_cnpj CPF ou CNPJ formatado ou não
     * @return string Os 4 primeiros dígitos numéricos
     * @author ADMSistema
     * @since 4.5
     * 
     * @example
     * $digitos = $this->getPrimeirosDigitos('123.456.789-01');
     * // Retorna: '1234'
     */
    function getPrimeirosDigitos($cpf_cnpj) {
        // Remove qualquer formatação (pontos, traços, espaços)
        $limpo = preg_replace('/[^0-9]/', '', $cpf_cnpj);
        
        // Pega os 4 primeiros dígitos
        return substr($limpo, 0, 4);
    }



}

?>
