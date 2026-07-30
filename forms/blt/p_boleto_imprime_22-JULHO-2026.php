<?php

/**
 * @package   astecv3
 * @name      p_boleto
 * @version   4.5.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/06/2017
 * @updated   2025 - Migrado para usar mPDF com layouts otimizados
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/blt/c_boleto.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/fin/c_conta_banco.php");
include_once($dir . "/../../class/fin/c_lancamento.php");

if ($_GET["submenu"] != 'imprime_api_inter') {
    include_once($dir . "/../../forms/blt/p_boleto_pdf.php");
}

//Class p_fin_boleto
class p_boleto extends c_boleto
{

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;



    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function __construct()
    {

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
        $this->m_submenu = $parmGet['submenu']
            ?? $parmPost['submenu']
            ?? '';

        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));

        // Se não tem letra mas tem id, usa o id como primeiro parâmetro
        if (empty($this->m_letra) && isset($parmGet['id'])) {
            $this->m_letra = $parmGet['id'];
        }

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
    function controle()
    {
        switch ($this->m_submenu) {
            case 'imprime':
                $this->imprimeBoleto();
                break;
            case 'imprime_api_inter':
                $this->imprimeBoletoApiInter();
                break;
            default:
                // Se não especificar submenu, imprime diretamente
                $this->imprimeBoleto();
        }
    } // fim controle

    /**
     * <b> Gera e exibe PDF do boleto usando mPDF com layouts otimizados </b>
     * @name imprimeBoleto
     * @description Usa a classe p_boleto_pdf para gerar PDF otimizado com mPDF
     * @return void
     */
    function imprimeBoleto()
    {
        try {
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Busca lançamentos do boleto
            $lanc = $this->selectLancBoleto($this->m_par[0], $this->m_par[1], $this->m_par[2], $this->m_par[3]);

            $dados_conta_banco = $this->getDadosContaBanco($lanc[0]['CONTA']);

            // Verificacao de boletos manuais e API Inter
            if ($dados_conta_banco['ENVIA_BOLETO'] == 'A') {

                switch ($dados_conta_banco['BANCO']) {
                    case 77:
                        $this->imprimeBoletoApiInter();
                        break;
                    case 237:
                        $lanc = $lanc[0];
                        $this->imprimeBoletoApiBradesco($lanc);
                        break;
                    default:
                        die("Banco não suportado para impressão de boleto.");
                }
                exit;
            }

            // Verifica se encontrou lançamentos
            if (empty($lanc)) {
                die("Nenhum lançamento encontrado para impressão do boleto.");
            }

            // Usa a classe p_boleto_pdf que gera PDF otimizado com mPDF
            $obj_pdf = new p_boleto_pdf();
            $pdf_content = $obj_pdf->geraPdfBoletos($lanc);

            // Verifica se houve erro na geração
            if (is_array($pdf_content) && isset($pdf_content['status']) && $pdf_content['status'] == false) {
                die("Erro ao gerar PDF: " . $pdf_content['msg']);
            }

            // Desativa compressão de saída para evitar corrupção do PDF
            ini_set('zlib.output_compression', '0');
            header('Content-Encoding: none');

            // Define headers para download do PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="boleto.pdf"');
            header('Content-Length: ' . strlen($pdf_content));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            // Exibe o PDF
            echo $pdf_content;
            exit;
        } catch (Exception $e) {
            die("Erro ao gerar boleto: " . $e->getMessage());
        }
    }

    function imprimeBoletoApiInter()
    {
        try {

            $dados_tabela_api = $this->getDadosTabelaApiInter($this->m_par[0]);

            // se nao foi encontrado dados da tabela de API, exibe erro
            if (!$dados_tabela_api) {
                $this->exibirErroBoletoPagina(['erros' => ['title' => 'Não foi encontrado dados da tabela de API para o lançamento.']]);
                exit;
            }

            // se o PDF binario nao foi gerado, recupera o PDF da API
            if ($dados_tabela_api['PDF_BINARIO'] == NULL) {

                require_once (__DIR__) . "/../../class/fin/c_api_inter.php";

                $objeto_api_inter = new c_api_inter();
                $retorno = $objeto_api_inter->recuperarCobrancaEmPdf($dados_tabela_api['ID']);
            } else {

                $pdf_content = base64_encode($dados_tabela_api['PDF_BINARIO']);
                $retorno['data']['boleto_Base64'] = $pdf_content;
            }




            // Se o boleto foi gerado com sucesso, exibe o PDF
            if ($retorno["http_code"] == 200) {
                $pdf_content = base64_decode($retorno['data']['boleto_Base64']);

                // Verifica se o PDF foi gerado com sucesso
                if (!$pdf_content) {
                    $this->exibirErroBoletoPagina($retorno);
                }
            } else {
                // Exibe página de erro formatada
                $this->exibirErroBoletoPagina($retorno);
                exit;
            }


            while (ob_get_level()) {
                ob_end_clean();
            }

            // Define headers para download do PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="boleto.pdf"');
            header('Content-Length: ' . strlen($pdf_content));

            echo $pdf_content;
            exit;
        } catch (Exception $e) {
            $this->exibirErroBoletoPagina(['erros' => ['title' => $e->getMessage()]]);
            exit;
        }
    }

    function imprimeBoletoApiBradesco(array $lanc)
    {
        try {

            $dados_tabela_api = $this->getDadosTabelaApiBradesco($lanc['ID']);

            // se nao foi encontrado dados da tabela de API, exibe erro
            if (!$dados_tabela_api) {
                $this->exibirErroBoletoPagina(['erros' => ['title' => 'Não foi encontrado dados da tabela de API para o lançamento.']]);
                exit;
            }

            $cdBarrasNumerico = $dados_tabela_api['CD_BARRAS'];
            $linhaDigitavel = $dados_tabela_api['LINHA_DIGITAVEL'];

            $obj_pdf = new p_boleto_pdf();
            $pdf_content = $obj_pdf->geraPdfBoletoApiBradesco($lanc, $dados_tabela_api, $cdBarrasNumerico, $linhaDigitavel);

            if (is_array($pdf_content) && isset($pdf_content['status']) && $pdf_content['status'] == false) {
                die("Erro ao gerar PDF: " . $pdf_content['msg']);
            }

            // Desativa compressão de saída para evitar corrupção do PDF
            ini_set('zlib.output_compression', '0');
            header('Content-Encoding: none');

            // Define headers para download do PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="boleto.pdf"');
            header('Content-Length: ' . strlen($pdf_content));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            // Exibe o PDF
            echo $pdf_content;
            exit;
        } catch (Exception $e) {
            $this->exibirErroBoletoPagina(['erros' => ['title' => $e->getMessage()]]);
            exit;
        }
    }

    //-------------------------------------------------------------
}
//	END OF THE CLASS

$boleto = new p_boleto();
$boleto->controle();
