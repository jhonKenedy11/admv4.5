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
require_once $dir . '/../../vendor/autoload.php';


// Carrega o mPDF v6.1
//require_once $dir . "/../../bib/mpdf/mpdf.php";


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
        // Fecha escrita na sessão para liberar o lock e permitir requests paralelos
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

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

            $dir = (__DIR__);
            $basepathRaiz = rtrim(realpath(ADMraizFonte) ?: ADMraizFonte, '/') . '/';

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'tempDir' => sys_get_temp_dir(),
                'autoPageBreak' => true,
                'shrink_tables_to_fit' => 1,
                'keep_table_proportions' => true,
                'basepath' => $basepathRaiz,
            ]);
            
            // Configura para evitar páginas vazias
            $mpdf->SetAutoPageBreak(true, 2); // 2mm de margem antes de quebrar página (reduzido para evitar página vazia)
            
            $mpdf->showImageErrors = false;
            $mpdf->debug = false;
            
            $objConta       = new c_conta;
            $objContaBanco  = new c_contaBanco;
            $htmlContent    = '';
            // true = ainda não gravamos nenhum boleto (não chamar AddPage antes do primeiro)
            $primeira_folha = true;

            $css_global = '';

            for ($i = 0; $i < count($lanc); $i++) {
            
                // Busca dados da conta bancária
                $objContaBanco->setId($lanc[$i]['CONTA']);
                $arrContaBanco = $objContaBanco->select_ContaBanco();
                $banco = $arrContaBanco[0]['BANCO'];
            
                if (empty($banco)) {
                    return ['status' => false, 'msg' => 'Banco não encontrado para o lançamento ' . $lanc[$i]['ID']];
                }
            
                if ($banco == "77") {
                    // Banco inter - boleto em binário na tabela FIN_API_INTER
                    $pdfBinario = $this->buscaPdfBancoInter($lanc[$i]['ID']);
            
                    if (!$pdfBinario) {
                        return ['status' => false, 'msg' => 'Boleto não encontrado para o lançamento ' . $lanc[$i]['ID']];
                    }
            
                    if (!$primeira_folha) {
                        $mpdf->AddPage();
                    }
            
                    $this->adicionaPdfBinario($mpdf, $pdfBinario);
                    $primeira_folha = false;
            
                } else {
            
                    // Gera nosso número se não existir
                    if (is_null($lanc[$i]['NOSSONUMERO']) || $lanc[$i]['NOSSONUMERO'] == '') {
            
                        if ($banco == '748') {
                            $nossoNumero = $objContaBanco->geraNossoNumero(
                                $lanc[$i]['CONTA'],
                                $arrContaBanco[0]['ULTIMONOSSONRO'],
                                $banco,
                                $arrContaBanco[0]['AGENCIA'],
                                $arrContaBanco[0]['POSTO'],
                                $arrContaBanco[0]['NUMNOBANCO']
                            );
                        } else {
                            $nossoNumero = $objContaBanco->geraNossoNumero(
                                $lanc[$i]['CONTA'],
                                $arrContaBanco[0]['ULTIMONOSSONRO']
                            );
                        }
            
                        $lanc[$i]['NOSSONUMERO'] = $nossoNumero;
                        c_lancamento::gravaNossoNumero($lanc[$i]['ID'], $nossoNumero);
                    }
            
                    // Monta dados do boleto
                    $dadosboleto = $this->montaDadosBoleto($lanc[$i], $arrContaBanco[0], $objConta, $banco);
            
                    // Gera HTML do boleto
                    $boletoHtml = $this->geraHtmlBoleto($dadosboleto, $banco, $arrContaBanco[0]);
            
                    if (is_array($boletoHtml) && isset($boletoHtml['status']) && $boletoHtml['status'] == false) {
                        return ['status' => false, 'msg' => $boletoHtml['msg']];
                    }
            
                    // Monta HTML completo
                    $htmlContent  = $this->geraInstrucoesImpressao($dadosboleto, $arrContaBanco[0]);
                    $htmlContent .= $boletoHtml;
            
                    // Limpa e formata o HTML
                    $htmlContent = preg_replace('/\s+/', ' ', $htmlContent);
                    $htmlContent = trim($htmlContent);
            
                    // Captura CSS apenas na primeira iteração de boleto manual
                    if (empty($css_global)) {
                        preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $htmlContent, $matches);
                        $css_global = implode("\n", $matches[1] ?? []);
                        $mpdf->WriteHTML($css_global, \Mpdf\HTMLParserMode::HEADER_CSS);
                    }
            
                    // Remove blocos <style> do HTML
                    $htmlContent = preg_replace('/<style[^>]*>.*?<\/style>/si', '', $htmlContent);
            
                    // Adiciona nova página se não for a primeira
                    if (!$primeira_folha) {
                        $mpdf->AddPage();
                    }
            
                    $mpdf->WriteHTML($htmlContent, \Mpdf\HTMLParserMode::HTML_BODY);
            
                    $primeira_folha = false;
                }

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
            // $debugPath = ADMraizFonte . '/debug/boletos/';
            // if (!file_exists($debugPath)) {
            //     mkdir($debugPath, 0775, true);
            // }
            
            // $debugFileName = 'boleto_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.pdf';
            // $debugFilePath = $debugPath . $debugFileName;
            
            // $mpdf->Output($debugFilePath, 'F');

            // exit;
            }
            
            // Restaura o nível de report de erros original
            //error_reporting($current_error_reporting);
            
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
     * Gera PDF de boleto Bradesco registrado via API, usando código de barras retornado pelo banco.
     *
     * @param array  $lanc             Lançamento financeiro
     * @param array  $dadosApi         Registro FIN_API_BRADESCO + joins
     * @param string $cdBarrasNumerico Código de barras FEBRABAN (44 dígitos)
     * @param string $linhaDigitavel   Linha digitável formatada
     * @return string|array
     */
    public function geraPdfBoletoApiBradesco(array $lanc, array $dadosApi, string $cdBarrasNumerico, string $linhaDigitavel)
    {
        try {
            if (empty($lanc) || empty($dadosApi) || empty($cdBarrasNumerico)) {
                return ['status' => false, 'msg' => 'Dados insuficientes para gerar boleto API Bradesco.'];
            }

            $current_error_reporting = error_reporting();
            error_reporting($current_error_reporting & ~E_NOTICE);

            $dir = (__DIR__);
            $basepathRaiz = rtrim(realpath(ADMraizFonte) ?: ADMraizFonte, '/') . '/';

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 5,
                'margin_right' => 5,
                'margin_top' => 5,
                'margin_bottom' => 5,
                'tempDir' => sys_get_temp_dir(),
                'autoPageBreak' => true,
                'shrink_tables_to_fit' => 1,
                'keep_table_proportions' => true,
                'basepath' => $basepathRaiz,
            ]);

            $mpdf->SetAutoPageBreak(true, 2);
            $mpdf->showImageErrors = false;
            $mpdf->debug = false;

            $objConta      = new c_conta;
            $objContaBanco = new c_contaBanco;
            $css_global    = '';

            $objContaBanco->setId($lanc['CONTA']);
            $arrContaBanco = $objContaBanco->select_ContaBanco();
            $banco = $arrContaBanco[0]['BANCO'] ?? '';

            if ($banco !== '237') {
                return ['status' => false, 'msg' => 'Conta bancária não é Bradesco (237).'];
            }

            $dadosboleto = $this->montaDadosBoleto($lanc, $arrContaBanco[0], $objConta, $banco);
            $dadosboleto = $this->aplicaDadosApiBradesco($dadosboleto, $dadosApi, $cdBarrasNumerico, $linhaDigitavel);

            $boletoHtml = $this->geraHtmlBoleto($dadosboleto, $banco, $arrContaBanco[0]);

            if (is_array($boletoHtml) && isset($boletoHtml['status']) && $boletoHtml['status'] === false) {
                return ['status' => false, 'msg' => $boletoHtml['msg']];
            }

            $htmlContent  = $this->geraInstrucoesImpressao($dadosboleto, $arrContaBanco[0]);
            $htmlContent .= $boletoHtml;
            $htmlContent = preg_replace('/\s+/', ' ', trim($htmlContent));

            preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $htmlContent, $matches);
            $css_global = implode("\n", $matches[1] ?? []);
            if ($css_global !== '') {
                $mpdf->WriteHTML($css_global, \Mpdf\HTMLParserMode::HEADER_CSS);
            }

            $htmlContent = preg_replace('/<style[^>]*>.*?<\/style>/si', '', $htmlContent);
            $mpdf->WriteHTML($htmlContent, \Mpdf\HTMLParserMode::HTML_BODY);

            error_reporting($current_error_reporting);

            return $mpdf->Output('', 'S');
        } catch (Exception $e) {
            if (isset($current_error_reporting)) {
                error_reporting($current_error_reporting);
            }
            return ['status' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * Enriquece dados do boleto com informações retornadas pela API Bradesco.
     */
    private function aplicaDadosApiBradesco(array $dadosboleto, array $dadosApi, string $cdBarrasNumerico, string $linhaDigitavel): array
    {
        $dir = (__DIR__);
        include_once($dir . '/../../class/fin/c_api_bradesco_barcode.php');
        include_once($dir . '/../../class/blt/funcoes_bradesco.php');

        $cdBarrasNumerico = c_api_bradesco_barcode::resolveCodigoBarrasNumerico($cdBarrasNumerico);

        $barcodeInfo = c_api_bradesco_barcode::parseCodigoBarrasNumerico($cdBarrasNumerico);

        $dadosboleto['usar_codigo_api']     = true;
        $dadosboleto['codigo_barras_api']   = $cdBarrasNumerico;
        $dadosboleto['linha_digitavel_api'] = $linhaDigitavel;

        if (!empty($dadosApi['VL_TITULO_EMITIDO_BOLETO'])) {
            $dadosboleto['valor_boleto'] = number_format((float) $dadosApi['VL_TITULO'], 2, ',', '');
        } elseif (!empty($dadosApi['VL_TITULO'])) {
            $dadosboleto['valor_boleto'] = number_format((float) $dadosApi['VL_TITULO_EMITIDO_BOLETO'], 2, ',', '');
        }

        if (!empty($barcodeInfo['data_vencimento']) && $barcodeInfo['data_vencimento'] !== 'sem vencimento') {
            $dadosboleto['data_vencimento'] = $barcodeInfo['data_vencimento'];
        } elseif (!empty($dadosApi['DT_VENCIMENTO_BOLETO'])) {
            $dadosboleto['data_vencimento'] = $this->formatarDataBradescoApi($dadosApi['DT_VENCIMENTO_BOLETO']);
        } elseif (!empty($dadosApi['DT_VENCIMENTO'])) {
            $dadosboleto['data_vencimento'] = $this->formatarDataBradescoApi($dadosApi['DT_VENCIMENTO']);
        }

        if (!empty($dadosApi['DT_EMISSAO'])) {
            $dataEmissao = $this->formatarDataBradescoApi($dadosApi['DT_EMISSAO']);
            if ($dataEmissao !== '') {
                $dadosboleto['data_documento'] = $dataEmissao;
                $dadosboleto['data_processamento'] = $dataEmissao;
            }
        }

        if (!empty($dadosApi['SEU_NUMERO_TITULO'])) {
            $dadosboleto['numero_documento'] = $dadosApi['SEU_NUMERO_TITULO'];
        }

        if (!empty($dadosApi['DESC_ESPECIE'])) {
            $dadosboleto['especie_doc'] = $dadosApi['DESC_ESPECIE'];
        } elseif (!empty($dadosApi['ESPECIE_DOCUMENTO_TITULO'])) {
            $dadosboleto['especie_doc'] = $dadosApi['ESPECIE_DOCUMENTO_TITULO'];
        }

        if (!empty($dadosApi['ACEITE_10'])) {
            $dadosboleto['aceite'] = $dadosApi['ACEITE_10'];
        }

        if (!empty($dadosApi['NOME_BENEFICIARIO'])) {
            $dadosboleto['cedente'] = $dadosApi['NOME_BENEFICIARIO'];
            $dadosboleto['identificacao'] = $dadosApi['NOME_BENEFICIARIO'];
        }

        if (!empty($dadosApi['CPF_CNPJ_BENEFICIARIO'])) {
            $dadosboleto['cpf_cnpj'] = $dadosApi['CPF_CNPJ_BENEFICIARIO'];
        }

        if (!empty($dadosApi['NOME_PAGADOR'])) {
            $cpfCnpjPagador = '';
            if (!empty($dadosApi['CPF_CNPJ_PAGADOR'])) {
                $doc = preg_replace('/\D/', '', (string) $dadosApi['CPF_CNPJ_PAGADOR']);
                if (strlen($doc) === 14) {
                    $cpfCnpjPagador = substr($doc, 0, 2) . '.' . substr($doc, 2, 3) . '.' .
                        substr($doc, 5, 3) . '/' . substr($doc, 8, 4) . '-' . substr($doc, 12, 2);
                } elseif (strlen($doc) === 11) {
                    $cpfCnpjPagador = substr($doc, 0, 3) . '.' . substr($doc, 3, 3) . '.' .
                        substr($doc, 6, 3) . '-' . substr($doc, 9, 2);
                }
            }
            $dadosboleto['sacado'] = trim($dadosApi['NOME_PAGADOR'] . ($cpfCnpjPagador !== '' ? ' - ' . $cpfCnpjPagador : ''));
        }

        if (!empty($dadosApi['ENDERECO_PAGADOR'])) {
            $dadosboleto['endereco1'] = $dadosApi['ENDERECO_PAGADOR'];
            if (!empty($dadosApi['BAIRRO_PAGADOR'])) {
                $dadosboleto['endereco1'] .= ', ' . $dadosApi['BAIRRO_PAGADOR'];
            }
        }

        if (!empty($dadosApi['MUNICIPIO_PAGADOR']) || !empty($dadosApi['UF_PAGADOR'])) {
            $cep = !empty($dadosApi['CEP_PAGADOR']) ? preg_replace('/\D/', '', (string) $dadosApi['CEP_PAGADOR']) : '';
            $cepFormatado = strlen($cep) === 8 ? substr($cep, 0, 5) . '-' . substr($cep, 5, 3) : $cep;
            $dadosboleto['endereco2'] = trim(
                ($dadosApi['MUNICIPIO_PAGADOR'] ?? '') . ' - ' . ($dadosApi['UF_PAGADOR'] ?? '') .
                ($cepFormatado !== '' ? ' - CEP: ' . $cepFormatado : '')
            );
        }

        $agencia = !empty($dadosApi['AGENC_CRED_10'])
            ? str_pad((string) $dadosApi['AGENC_CRED_10'], 4, '0', STR_PAD_LEFT)
            : str_pad((string) ($barcodeInfo['agencia'] ?? ''), 4, '0', STR_PAD_LEFT);

        $conta = !empty($dadosApi['CTA_CRED_10'])
            ? str_pad((string) $dadosApi['CTA_CRED_10'], 7, '0', STR_PAD_LEFT)
            : str_pad((string) ($barcodeInfo['conta_beneficiario'] ?? ''), 7, '0', STR_PAD_LEFT);

        $contaDv = !empty($dadosApi['DIG_CRED_10']) ? (string) $dadosApi['DIG_CRED_10'] : ($dadosboleto['conta_dv'] ?? '');

        $carteira = !empty($dadosApi['CARTEIRA'])
            ? (string) $dadosApi['CARTEIRA']
            : ($barcodeInfo['carteira'] ?? $dadosboleto['carteira']);

        $nnumBase = $carteira . str_pad((string) ($barcodeInfo['nosso_numero'] ?? ''), 11, '0', STR_PAD_LEFT);
        $dvNossoNumero = c_contaBanco::mod11($nnumBase, 7);

        $dadosboleto['agencia'] = $agencia;
        $dadosboleto['conta'] = ltrim(substr($conta, 0, 6), '0') ?: '0';
        $dadosboleto['conta_dv'] = $contaDv;
        $dadosboleto['carteira'] = $carteira;
        $dadosboleto['agencia_dv'] = '7';
        $dadosboleto['agencia_codigo_api'] = $agencia . '-' . $dadosboleto['agencia_dv'] . ' / ' . $conta . '-' . $contaDv;
        $dadosboleto['nosso_numero_api'] = substr($nnumBase, 0, 2) . '/' . substr($nnumBase, 2) . '-' . $dvNossoNumero;

        if (!empty($dadosApi['CIP_10'])) {
            $dadosboleto['cip'] = str_pad((string) $dadosApi['CIP_10'], 3, '0', STR_PAD_LEFT);
        }

        return $dadosboleto;
    }

    /**
     * Converte data bruta da API Bradesco (DDMMAAAA, AAAAMMDD ou ISO) para d/m/Y.
     */
    private function formatarDataBradescoApi($valor): string
    {
        if ($valor === null || $valor === '' || $valor === '0') {
            return '';
        }

        $valor = preg_replace('/\D/', '', (string) $valor);

        if (strlen($valor) === 8) {
            if ((int) substr($valor, 0, 4) > 1900) {
                return substr($valor, 6, 2) . '/' . substr($valor, 4, 2) . '/' . substr($valor, 0, 4);
            }
            return substr($valor, 0, 2) . '/' . substr($valor, 2, 2) . '/' . substr($valor, 4, 4);
        }

        $timestamp = strtotime((string) $valor);
        return $timestamp ? date('d/m/Y', $timestamp) : '';
    }

    /**
     * Monta os dados do boleto
     * 
     * Processa e formata todos os dados necessários para geração do boleto,
     * incluindo dados do cliente, empresa, conta bancária, instruções de pagamento,
     * cálculos de multa (percentual único sobre ORIGINAL) e juros (percentual ao mês ÷ 30 = mora diária).
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

        // Cálculo de multa e juros (percentuais da conta; base = valor original do título)
        $valorBase = (float) ($lanc['ORIGINAL'] ?? $lanc['TOTAL'] ?? 0);
        $percMulta = (float) ($arrContaBanco['MULTA'] ?? 0);
        $percJuros = (float) ($arrContaBanco['JUROS'] ?? 0);

        // Multa: percentual único sobre o valor original
        $multa = ($percMulta * $valorBase) / 100;

        // Juros: percentual ao mês convertido em mora diária (÷ 30), alinhado a c_lancamento::atualizaJuros
        $juros = ($percJuros * $valorBase) / 100 / 30;

        $dadosboleto["instrucoes1"] = "Após o vencimento, <br>";
        if ($percMulta > 0){
            $dadosboleto["instrucoes1"] .= "Cobrar multa de R$ ".number_format($multa, 2, ',', '.')."<br> ";
        }
        if ($percJuros > 0){
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
    private function geraHtmlBoleto(&$dadosboleto, $banco, $arrContaBanco) {
        // Passa $dadosboleto por referência para que modificações (como linha digitável) sejam refletidas
        
        switch ($banco) {
            case '237': // Bradesco
                return $this->geraHtmlBradesco($dadosboleto, $arrContaBanco);
            case '341': // Itaú
                return $this->geraHtmlItau($dadosboleto, $arrContaBanco);
            case '748': // Sicredi
                return $this->geraHtmlSicredi($dadosboleto, $arrContaBanco);
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
    private function geraHtmlBradesco(&$dadosboleto, $arrContaBanco) {
        // Inclui funções específicas do Bradesco
        $dir = (__DIR__);
        include_once($dir."/../../class/blt/funcoes_bradesco.php");

        $codigobanco = "237";
        $codigo_banco_com_dv = geraCodigoBanco($codigobanco);

        // Boleto registrado via API — usa código de barras e linha digitável do banco
        if (!empty($dadosboleto['usar_codigo_api'])) {
            $dadosboleto["codigo_barras"] = $dadosboleto['codigo_barras_api'];
            $dadosboleto["linha_digitavel"] = $dadosboleto['linha_digitavel_api'];
            $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
            $dadosboleto["agencia_codigo"] = $dadosboleto['agencia_codigo_api'] ?? '';
            $dadosboleto["nosso_numero"] = $dadosboleto['nosso_numero_api'] ?? $dadosboleto['nosso_numero'];

            return $this->getLayoutBradesco($dadosboleto);
        }
        
        // Cálculos específicos do Bradesco
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
     * Gera HTML específico para Itaú
     * 
     * Processa os dados do boleto aplicando as regras específicas do Banco Itaú,
     * incluindo cálculos de dígitos verificadores, formação de código de barras,
     * linha digitável e nosso número conforme padrões do banco.
     * 
     * @param array $dadosboleto Dados básicos do boleto
     * @param array $arrContaBanco Dados da conta bancária do Itaú
     * @return string HTML formatado do boleto Itaú
     * @throws Exception Se arquivo de layout não for encontrado
     * @author ADMSistema
     * @since 4.5
     */
    private function geraHtmlItau(&$dadosboleto, $arrContaBanco) {
        // Inclui funções específicas do Itaú
        $dir = (__DIR__);
        include_once($dir."/../../class/blt/funcoes_itau.php");
        
        // Cálculos específicos do Itaú
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
        //carteira
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
        $dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
        $dadosboleto["agencia_codigo"] = $agencia_codigo;
        $dadosboleto["nosso_numero"] = $nossonumero;
        $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

        // Retorna HTML do layout Itaú otimizado para mPDF
        return $this->getLayoutItau($dadosboleto);
    }

    /**
     * Gera HTML específico para Sicredi
     * 
     * Processa os dados do boleto aplicando as regras específicas do Banco Sicredi,
     * incluindo cálculos de dígitos verificadores, formação de código de barras,
     * linha digitável e nosso número conforme padrões do banco.
     * 
     * @param array $dadosboleto Dados básicos do boleto
     * @param array $arrContaBanco Dados da conta bancária do Sicredi
     * @return string HTML formatado do boleto Sicredi
     * @throws Exception Se arquivo de layout não for encontrado
     * @author ADMSistema
     * @since 4.5
     */
    private function geraHtmlSicredi(&$dadosboleto, $arrContaBanco) {
        // PRESERVA nosso número original ANTES de qualquer processamento
        // O nosso número vem do banco como: 25/2091595 (sem hífen)
        $nosso_numero_original = $dadosboleto["nosso_numero"];
        
        // Inclui funções específicas do Sicredi
        $dir = (__DIR__);
        include_once($dir."/../../class/blt/funcoes_sicredi.php");
        
        // Cálculos específicos do Sicredi
        $codigobanco = "748";
        $codigo_banco_com_dv = geraCodigoBanco($codigobanco);
        $nummoeda = "9";
        $fator_vencimento = fator_vencimento($dadosboleto["data_vencimento"]);
        
        //valor tem 10 digitos, sem virgula
        $valor = formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
        //agencia é 4 digitos
        $agencia = formata_numero($dadosboleto["agencia"],4,0);
        //posto da cooperativa de credito é dois digitos
        $posto = formata_numero($arrContaBanco['POSTO'],2,0);
        //conta é 5 digitos
        $conta = formata_numero($dadosboleto["conta"],5,0);
        //dv da conta
        $conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);
        //carteira é 2 caracteres
        $carteira = $dadosboleto["carteira"];
        
        //fillers - zeros Obs: filler1 contera 1 quando houver valor expresso no campo valor
        $filler1 = 1;
        $filler2 = 0;
        
        // Byte de Identificação do cedente 1 - Cooperativa; 2 a 9 - Cedente
        // Extrai do nosso número armazenado (posição 3 do nosso número)
        $byteidt = intval(substr($arrContaBanco["ULTIMONOSSONRO"], 3, 1));
        
        // Codigo referente ao tipo de cobranca: "1" - SICREDI
        $tipo_cobranca = 1;
        
        // Codigo referente ao tipo de carteira: "1" - Carteira Simples 
        $tipo_carteira = 1;
        
        // Processa nosso número - na versão 4.0 já vem formatado como AA/BXXXXX-D
        // Segue exatamente a mesma lógica da versão 4.0 (funcoes_sicredi.php linhas 64-84)
        
        //nosso número (sem dv) - extrai componentes do nosso número formatado
        $dadosboleto["inicio_nosso_numero"] = substr($nosso_numero_original, 0, 2);
        $nnum = formata_numero($nosso_numero_original,5,0);
        $nnumCampoLivre = str_replace('/', '', $nosso_numero_original);
        
        // Verifica se a string tem 10 caracteres
        if (strlen($nnumCampoLivre) == 9) {
            // Remove o último caractere
            $nnumCampoLivre = substr($nnumCampoLivre, 0, -1);
        }
        
        //calculo do DV do nosso número
        //Relacionar os códigos da Cooperativa (aaaa), posto beneficiário (pp), beneficiário (ccccc), ano atual (yy), byte
        //de geração do Nosso Número (b) e o número sequencial do beneficiário (nnnnn):
        //aaaappcccccyybnnnnn;
        $sequencial_completo = substr($nosso_numero_original, 4, 5);
        $sequencial = intval(substr($nosso_numero_original, 4, 5));
        $dadosboleto["inicio_nosso_numero"] = substr($nosso_numero_original, 0, 2);
        $byte = substr($nosso_numero_original, 3, 1);
        $teste_nosso = $agencia.$posto.$conta.$dadosboleto["inicio_nosso_numero"].$byte.$sequencial_completo;
        $dv_nosso_numero = digitoVerificador_nossonumero($teste_nosso);
        
        //formação do campo livre (SEM o DV do nosso número)
        $campolivre = "$tipo_cobranca$tipo_carteira$nnumCampoLivre$dv_nosso_numero$agencia$posto$conta$filler1$filler2";
        $campolivre_dv = $campolivre . digitoVerificador_campolivre($campolivre);
        
        // 43 numeros para o calculo do digito verificador do codigo de barras
        $dv = digitoVerificador_barra("$codigobanco$nummoeda$fator_vencimento$valor$campolivre_dv");
        
        // Numero para o codigo de barras com 44 digitos
        $linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$campolivre_dv";
        
        // Formata strings para impressao no boleto
        // Na versão 4.0, o nosso número é salvo SEM hífen (25/2091595) e o hífen é inserido na exibição
        // Segue exatamente a mesma lógica da versão 4.0 (funcoes_sicredi.php linhas 96-99)
        // Usa $nnum que vem de formata_numero (linha 66 da versão 4.0)
        $tamanho = strlen($nnum);
        // Insere o hífen antes da última posição
        $string = substr_replace($nnum, '-', $tamanho - 1, 0);
        $nossonumero = $string;
        
        $agencia_codigo = $agencia.".". $posto.".".$conta;
        
        $dadosboleto["codigo_barras"] = $linha;
        $dadosboleto["linha_digitavel"] = monta_linha_digitavel($linha);
        $dadosboleto["agencia_codigo"] = $agencia_codigo;
        $dadosboleto["nosso_numero"] = $nossonumero;
        $dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;
        $dadosboleto["posto"] = $posto;

        // Retorna HTML do layout Sicredi otimizado para mPDF
        return $this->getLayoutSicredi($dadosboleto);
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
     * Retorna o layout HTML do Itaú otimizado para mPDF v6.1
     * 
     * Carrega o template HTML específico do Itaú otimizado para geração
     * de PDF com mPDF. Inclui validação de existência do arquivo de layout.
     * 
     * @param array $dadosboleto Dados completos do boleto já processados
     * @return string HTML do layout do boleto Itaú
     * @throws Exception Se o arquivo de layout não for encontrado
     * @author ADMSistema
     * @since 4.5
     */
    private function getLayoutItau($dadosboleto) {
        $dir = (__DIR__);
        $layoutPath = $dir . '/../../boleto/include/layout_itau_email.php';
        
        // Verifica se o arquivo de layout existe
        if (!file_exists($layoutPath)) {
            throw new Exception("Arquivo de layout não encontrado em: " . $layoutPath);
        }

        ob_start();
        include($layoutPath);
        return ob_get_clean();
    }

    /**
     * Retorna o layout HTML do Sicredi otimizado para mPDF v6.1
     * 
     * Carrega o template HTML específico do Sicredi otimizado para geração
     * de PDF com mPDF. Inclui validação de existência do arquivo de layout.
     * 
     * @param array $dadosboleto Dados completos do boleto já processados
     * @return string HTML do layout do boleto Sicredi
     * @throws Exception Se o arquivo de layout não for encontrado
     * @author ADMSistema
     * @since 4.5
     */
    private function getLayoutSicredi($dadosboleto) {
        $dir = (__DIR__);
        $layoutPath = $dir . '/../../boleto/include/layout_sicredi_email.php';
        
        // Verifica se o arquivo de layout existe
        if (!file_exists($layoutPath)) {
            throw new Exception("Arquivo de layout não encontrado em: " . $layoutPath);
        }

        ob_start();
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

    private function geraInstrucoesImpressao($dadosboleto, $arrContaBanco) {

        $logoPathAbsoluto = ADMraizCliente . '/images/logo.png';
        $logoPath = file_exists($logoPathAbsoluto) ? realpath($logoPathAbsoluto) : null;
    
        $html = '';
    
        // Linha "Recibo do Sacado" alinhada à direita
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
        $html .= '<tr>';
        $html .= '<td align="right" style="border: none; padding: 2pt 0; font-family: Arial, sans-serif; font-size: 9pt; font-weight: bold;">Recibo do Sacado</td>';
        $html .= '</tr>';
        $html .= '</table>';
    
        // Logo à esquerda + texto centralizado no meio
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
        $html .= '<tr>';
    
        // Coluna da logo (esquerda, largura fixa)
        $html .= '<td width="180" valign="middle" align="left" style="border: none; padding: 0;">';
        if ($logoPath) {
            $html .= '<img id="logo_empresa" src="' . htmlspecialchars($logoPath) . '" width="160" height="55" />';
        }
        $html .= '</td>';
    
        // Coluna do texto (centro, ocupa o restante)
        $html .= '<td valign="middle" align="center" style="border: none; padding: 2pt; font-family: Arial, sans-serif; font-size: 7pt;">';
        $html .= '<strong style="font-size: 8pt;">' . htmlspecialchars($dadosboleto["identificacao"]) . '</strong>';
    
        if (!empty($dadosboleto["cpf_cnpj"])) {
            $html .= '<br />' . htmlspecialchars($dadosboleto["cpf_cnpj"]);
        }
        if (!empty($dadosboleto["endereco"])) {
            $html .= '<br />' . htmlspecialchars($dadosboleto["endereco"]);
        }
        if (!empty($dadosboleto["cidade_uf"])) {
            $html .= '<br />' . htmlspecialchars($dadosboleto["cidade_uf"]);
        }
    
        $html .= '</td>';
    
        // Coluna vazia à direita (espelho da logo para equilibrar o centro)
        $html .= '<td width="180" style="border: none; padding: 0;"></td>';
    
        $html .= '</tr>';
        $html .= '</table>';
    
        return $html;
    }


    /**
     * Recupera o PDF binário de um lançamento salvo no banco de dados
     * 
     * @param int $lancamento_id ID do lançamento
     * @return string|false Conteúdo binário do PDF ou false em caso de erro
     */
    private function buscaPdfBancoInter(int $lancamento_id) : string|false {
        try {

            $banco = new c_banco_pdo();
            $banco->prepare("SELECT PDF_BINARIO FROM FIN_API_INTER WHERE ID_LANCAMENTO = :id_lancamento");
            $banco->bindValue(':id_lancamento', $lancamento_id, PDO::PARAM_INT);
            $banco->execute();
            $resultado = $banco->fetchAll(PDO::FETCH_ASSOC);
            
            return $resultado[0]['PDF_BINARIO'];
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Adiciona um PDF em formato binário ao documento mPDF sendo construído
     * Utiliza importação de página para manter formatação original
     * 
     * @param mPDF $mpdf Instância do mPDF para adicionar conteúdo
     * @param string $pdfBinario Conteúdo binário do PDF a adicionar
     * @return bool True em caso de sucesso
     */
    private function adicionaPdfBinario(&$mpdf, $pdfBinario) {
        try {
            // Cria arquivo temporário para o PDF binário
            $tempDir = sys_get_temp_dir();
            $tempFile = $tempDir . '/temp_boleto_' . uniqid() . '.pdf';
            
            // Salva o PDF binário em arquivo temporário
            file_put_contents($tempFile, $pdfBinario);
            
            if (!file_exists($tempFile)) {
                throw new Exception('Falha ao criar arquivo temporário para PDF');
            }
    
            // Importa o PDF usando mPDF SetSourceFile
            try {
                $pageCount = $mpdf->setSourceFile($tempFile);
                
                // Importa todas as páginas do PDF binário
                for ($pageNum = 1; $pageNum <= $pageCount; $pageNum++) {
                    // Só quebra entre páginas do MESMO PDF importado; entre boletos diferentes o loop em geraPdfBoletos já chamou AddPage
                    if ($pageNum > 1) {
                        $mpdf->AddPage();
                    }

                    $tplId = $mpdf->importPage($pageNum);
                    $mpdf->useTemplate($tplId);
                }
                
                // Limpa arquivo temporário
                @unlink($tempFile);
                
                return true;
                
            } catch (Exception $e) {
                @unlink($tempFile);
                throw new Exception('Erro ao importar PDF: ' . $e->getMessage());
            }
    
        } catch (Exception $e) {
            throw new Exception('Erro ao adicionar PDF binário: ' . $e->getMessage());
        }
    }
}
