<?php
/**
 * Endpoint PÚBLICO do webhook da Apuração Assistida IBS/CBS.
 *
 * COMO USAR (implantação por cliente):
 *   1. Copie este arquivo para a RAIZ do cliente (mesma pasta do config.php),
 *      por exemplo: /var/www/html/<cliente>/webhook_apuracao_cbs.php
 *   2. Cadastre a URL pública (HTTPS) deste arquivo no campo "URL de retorno
 *      (webhook)" das credenciais, por ex.:
 *        https://seu-dominio.com.br/<cliente>/webhook_apuracao_cbs.php
 *   3. (Recomendado) Defina um "Segredo do webhook" nas credenciais e informe-o
 *      na URL como ?secret=... ou no header X-Webhook-Secret.
 *
 * Este endpoint não usa sessão e responde sempre rápido (200) para não
 * bloquear a Receita; o processamento é registrado em EST_APURACAO_CBS_WEBHOOK_LOG.
 */

header('Content-Type: application/json; charset=utf-8');

// Bootstrap do cliente (define constantes de banco e caminhos)
require_once __DIR__ . '/config.php';

// Classe handler (fonte compartilhada)
require_once ADMclass . '/est/c_apuracao_cbs_webhook.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Método não permitido.']);
    exit;
}

$rawPayload = file_get_contents('php://input');

// Segredo: header X-Webhook-Secret tem prioridade; senão, query string ?secret=
$headers = function_exists('getallheaders') ? getallheaders() : [];
$segredo = '';
foreach ($headers as $k => $v) {
    if (strcasecmp($k, 'X-Webhook-Secret') === 0) {
        $segredo = (string) $v;
        break;
    }
}
if ($segredo === '' && isset($_GET['secret'])) {
    $segredo = (string) $_GET['secret'];
}

$origemIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ($_SERVER['REMOTE_ADDR'] ?? '');

try {
    $handler = new c_apuracao_cbs_webhook();
    $resultado = $handler->processar($rawPayload, $segredo, $headers, $origemIp);

    http_response_code((int) ($resultado['http_code'] ?? 200));
    echo json_encode([
        'ok' => (bool) ($resultado['ok'] ?? false),
        'msg' => $resultado['msg'] ?? '',
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    // Nunca vaza detalhes internos para o chamador.
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Erro interno ao processar o webhook.']);
}
