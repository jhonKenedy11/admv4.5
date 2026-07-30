<?php
/**
 * @package   adm4.5
 * @name      p_apuracao_cbs
 * @version   4.5.00
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 * @author    Auto
 * @date      14/07/2026
 */

// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/est/c_apuracao_cbs.php");
require_once($dir . "/../../class/util/c_api_response.php");

Class p_apuracao_cbs extends c_apuracao_cbs {

    private $m_submenu = NULL;
    private $m_opcao = NULL;
    private $m_aba = 'consulta';
    public $smarty = NULL;

    function __construct() {
        parent::__construct();

        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_opcao   = isset($parmPost['opcao'])   ? $parmPost['opcao']   : '';

        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');

        $this->smarty->assign('titulo', "Apuração Assistida IBS/CBS");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6 ]");
        $this->smarty->assign('disableSort', "[ 7 ]");
        $this->smarty->assign('numLine', "25");

        // SET dos dados do FORM
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setIdHistorico(isset($parmPost['id_historico']) ? $parmPost['id_historico'] : '');
        $this->setIdDebito(isset($parmPost['id_debito']) ? $parmPost['id_debito'] : '');
        $this->setCnpjBase(isset($parmPost['cnpj_base']) ? $parmPost['cnpj_base'] : '');
        $this->setClientId(isset($parmPost['client_id']) ? $parmPost['client_id'] : '');
        $this->setClientSecret(isset($parmPost['client_secret']) ? $parmPost['client_secret'] : '');
        $this->setAmbiente(isset($parmPost['ambiente']) ? $parmPost['ambiente'] : 'PRODUCAO');
        $this->setWebhookUrl(isset($parmPost['webhook_url']) ? $parmPost['webhook_url'] : '');
        $this->setWebhookSecret(isset($parmPost['webhook_secret']) ? $parmPost['webhook_secret'] : '');
        $this->setTiquete(isset($parmPost['tiquete']) ? $parmPost['tiquete'] : '');
        $this->setChaveDfe(isset($parmPost['chave_dfe']) ? $parmPost['chave_dfe'] : '');
        $this->setTpEvento(isset($parmPost['tp_evento']) ? $parmPost['tp_evento'] : '');
        $this->setPapel(isset($parmPost['papel']) ? $parmPost['papel'] : '');
        $this->setObservacao(isset($parmPost['observacao']) ? $parmPost['observacao'] : '');

        $this->m_aba = isset($parmPost['aba']) && $parmPost['aba'] !== '' ? $parmPost['aba'] : 'consulta';
    }

    /**
     * Exibe SweetAlert2 e segue o fluxo da tela
     */
    private function msgSwal($icon, $titulo, $texto) {
        $textoJs = addslashes($texto);
        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
        echo "<script>
        Swal.fire({
            icon: '" . $icon . "',
            title: '" . addslashes($titulo) . "',
            width: 510,
            text: '" . $textoJs . "',
            confirmButtonText: 'OK'
        });
        </script>";
    }

    function controle() {
        switch ($this->m_submenu) {
            case 'salvar_credencial':
                if ($this->verificaDireitoUsuario('EstApuracaoCbs', 'I')) {
                    $ajax = ($this->m_opcao === 'ajax');
                    if ($this->salvarCredencial()) {
                        $cred = $this->buscarCredencial($this->getCnpjBase());
                        if ($ajax) {
                            c_api_response::success($this->getMsg(), [
                                'cnpj_base'          => $this->getCnpjBase(),
                                'client_id'          => $this->getClientId(),
                                'ambiente'           => $this->getAmbiente(),
                                'webhook_url'        => $this->getWebhookUrl(),
                                'token_expira'       => $cred['TOKEN_EXPIRA_EM'] ?? '',
                                'tem_webhook_secret' => !empty($cred['WEBHOOK_SECRET']),
                            ]);
                        }
                        $this->msgSwal('success', 'Sucesso', $this->getMsg());
                    } else {
                        if ($ajax) {
                            c_api_response::failure($this->getMsg());
                        }
                        $this->msgSwal('error', 'Erro', $this->getMsg());
                    }
                    $this->mostraApuracaoCbs();
                }
                break;

            case 'gerar_token':
                if ($this->verificaDireitoUsuario('EstApuracaoCbs', 'C')) {
                    $ajax = ($this->m_opcao === 'ajax');
                    if ($this->gerarToken()) {
                        $cred = $this->buscarCredencial($this->getCnpjBase());
                        if ($ajax) {
                            c_api_response::success($this->getMsg(), [
                                'cnpj_base'    => $this->getCnpjBase(),
                                'ambiente'     => $this->getAmbiente(),
                                'token_expira' => $cred['TOKEN_EXPIRA_EM'] ?? '',
                            ]);
                        }
                        $this->msgSwal('success', 'Token', $this->getMsg());
                    } else {
                        if ($ajax) {
                            c_api_response::failure($this->getMsg());
                        }
                        $this->msgSwal('error', 'Erro', $this->getMsg());
                    }
                    $this->mostraApuracaoCbs();
                }
                break;

            case 'solicitar_consulta':
                if ($this->verificaDireitoUsuario('EstApuracaoCbs', 'I')) {
                    if ($this->solicitarConsulta()) {
                        $this->msgSwal('success', 'Consulta', $this->getMsg());
                    } else {
                        $this->msgSwal('warning', 'Atenção', $this->getMsg());
                    }
                    $this->mostraApuracaoCbs();
                }
                break;

            case 'download_debitos':
                if ($this->verificaDireitoUsuario('EstApuracaoCbs', 'C')) {
                    if ($this->downloadDebitos()) {
                        $this->msgSwal('success', 'Download', $this->getMsg());
                    } else {
                        $this->msgSwal('warning', 'Atenção', $this->getMsg());
                    }
                    $this->mostraApuracaoCbs();
                }
                break;

            case 'ver_debitos':
                if ($this->verificaDireitoUsuario('EstApuracaoCbs', 'C')) {
                    $this->mostraApuracaoCbs();
                }
                break;

            case 'emitir_evento':
                if ($this->verificaDireitoUsuario('EstApuracaoCbs', 'E')) {
                    if ($this->emitirEvento()) {
                        $this->msgSwal('success', 'Evento', $this->getMsg());
                    } else {
                        $this->msgSwal('error', 'Erro', $this->getMsg());
                    }
                    $this->mostraApuracaoCbs();
                }
                break;

            default:
                if ($this->verificaDireitoUsuario('EstApuracaoCbs', 'C')) {
                    $this->mostraApuracaoCbs();
                }
        }
    }

    function mostraApuracaoCbs() {
        $cnpjBase = $this->getCnpjBase();
        $credenciais = $this->selecionaCredenciais();
        $credencial = false;

        if ($cnpjBase) {
            $credencial = $this->buscarCredencial($cnpjBase);
        } elseif (!empty($credenciais[0]['CNPJ_BASE'])) {
            $cnpjBase = $credenciais[0]['CNPJ_BASE'];
            $this->setCnpjBase($cnpjBase);
            $credencial = $this->buscarCredencial($cnpjBase);
            if ($credencial) {
                $this->setAmbiente($credencial['AMBIENTE']);
                $this->setClientId($credencial['CLIENT_ID']);
                $this->setWebhookUrl($credencial['WEBHOOK_URL']);
            }
        }

        if ($credencial && !$this->getClientId()) {
            $this->setClientId($credencial['CLIENT_ID']);
            $this->setWebhookUrl($credencial['WEBHOOK_URL']);
            $this->setAmbiente($credencial['AMBIENTE']);
        }

        $temWebhookSecret = $credencial && !empty($credencial['WEBHOOK_SECRET']);

        $historico = $this->selecionaHistorico($cnpjBase ?: null);
        $limiteConsulta = $this->verificaLimiteDiario($cnpjBase ?: null, 'consulta');
        $limiteDownload = $this->verificaLimiteDiario($cnpjBase ?: null, 'download');

        $debitosCredito = [];
        $debitosDebito = [];
        if ($this->getIdHistorico()) {
            $debitosCredito = $this->repository->getDebitosPorPapel((int) $this->getIdHistorico(), 'DESTINATARIO');
            $debitosDebito = $this->repository->getDebitosPorPapel((int) $this->getIdHistorico(), 'EMITENTE');
            // preenche tíquete do histórico selecionado
            $histSel = $this->repository->getHistoricoPorId((int) $this->getIdHistorico());
            if ($histSel && !$this->getTiquete()) {
                $this->setTiquete($histSel['TIQUETE']);
            }
        }

        $eventos = $this->selecionaEventos($cnpjBase ?: null);
        $catalogo = $this->catalogoEventos();

        $tokenExpira = '';
        if ($credencial && !empty($credencial['TOKEN_EXPIRA_EM'])) {
            $tokenExpira = $credencial['TOKEN_EXPIRA_EM'];
        }

        $this->smarty->assign('pathImagem', '');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('cnpj_base', $this->getCnpjBase());
        $this->smarty->assign('client_id', $this->getClientId());
        $this->smarty->assign('webhook_url', $this->getWebhookUrl());
        $this->smarty->assign('tem_webhook_secret', $temWebhookSecret);
        $this->smarty->assign('ambiente', $this->getAmbiente());
        $this->smarty->assign('tiquete', $this->getTiquete());
        $this->smarty->assign('id_historico', $this->getIdHistorico());
        $this->smarty->assign('token_expira', $tokenExpira);
        $this->smarty->assign('credenciais', $credenciais);
        $this->smarty->assign('historico', $historico);
        $this->smarty->assign('debitosCredito', $debitosCredito);
        $this->smarty->assign('debitosDebito', $debitosDebito);
        $this->smarty->assign('eventos', $eventos);
        $this->smarty->assign('catalogoCredito', $catalogo['DESTINATARIO']);
        $this->smarty->assign('catalogoDebito', $catalogo['EMITENTE']);
        $this->smarty->assign('abaAtiva', $this->m_aba);
        $this->smarty->assign('limiteConsulta', $limiteConsulta);
        $this->smarty->assign('limiteDownload', $limiteDownload);
        $this->smarty->assign('limiteConsultaExcedido', !empty($limiteConsulta['excedido']));

        $this->smarty->display('apuracao_cbs_mostra.tpl');
    }
}

// Rotina principal
$apuracaoCbs = new p_apuracao_cbs();
$apuracaoCbs->controle();
?>
