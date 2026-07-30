<?php
/**
 * @package   adm4.5
 * @name      c_apuracao_cbs_log
 * @version   4.5.00
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 * @author    Auto
 * @date      22/07/2026
 *
 * Log em arquivo das interações da Apuração Assistida IBS/CBS
 * (requisições HTTP de saída e recebimentos do webhook). Grava um JSON
 * por evento para permitir reprocessar/simular depois sem chamar a API.
 *
 * Ativação: constante APURACAO_CBS_LOG (definível no config.php do cliente).
 * Se não estiver definida, cai para o valor de DEBUG.
 */

class c_apuracao_cbs_log
{
    /**
     * Indica se o log em arquivo está habilitado.
     */
    public static function habilitado(): bool
    {
        if (defined('APURACAO_CBS_LOG')) {
            return (bool) APURACAO_CBS_LOG;
        }
        return defined('DEBUG') && DEBUG;
    }

    /**
     * Diretório onde os logs são gravados (por cliente).
     */
    protected static function diretorio(): string
    {
        $base = defined('ADMraizCliente') ? ADMraizCliente : sys_get_temp_dir();
        return rtrim($base, '/') . '/logs/apuracao_cbs';
    }

    /**
     * Registra um evento em arquivo JSON.
     *
     * @param string $tipo  Rótulo do evento (token|solicitar_consulta|download|webhook|...)
     * @param array  $dados Envelope com 'request' e/ou 'response'
     */
    public static function registrar(string $tipo, array $dados): void
    {
        if (!self::habilitado()) {
            return;
        }

        try {
            $dir = self::diretorio();
            if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
                return;
            }

            $tipoSlug = preg_replace('/[^a-z0-9_-]/i', '_', $tipo) ?: 'evento';
            $nome = date('Ymd_His') . '_' . substr(uniqid('', true), -8) . '_' . $tipoSlug . '.json';

            $envelope = [
                'tipo' => $tipo,
                'data_hora' => date('Y-m-d H:i:s'),
                'dados' => self::mascarar($dados),
            ];

            $json = json_encode($envelope, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            @file_put_contents($dir . '/' . $nome, $json);
        } catch (\Throwable $e) {
            // Log é auxiliar: nunca deve interromper o fluxo principal.
        }
    }

    /**
     * Mascara valores sensíveis (segredos/tokens) recursivamente antes de gravar.
     */
    protected static function mascarar($valor)
    {
        if (is_array($valor)) {
            $limpo = [];
            foreach ($valor as $chave => $item) {
                if (is_string($chave) && self::ehChaveSensivel($chave)) {
                    $limpo[$chave] = self::ofuscar($item);
                } else {
                    $limpo[$chave] = self::mascarar($item);
                }
            }
            return $limpo;
        }

        if (is_string($valor)) {
            return self::mascararTextoAutenticacao($valor);
        }

        return $valor;
    }

    /**
     * Determina se o nome da chave indica dado sensível.
     */
    protected static function ehChaveSensivel(string $chave): bool
    {
        $c = strtolower($chave);
        $sensiveis = ['client_secret', 'clientsecret', 'secret', 'access_token', 'token', 'password', 'senha', 'webhook_secret'];
        foreach ($sensiveis as $s) {
            if (strpos($c, $s) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Ofusca um valor mantendo apenas um resíduo para depuração.
     */
    protected static function ofuscar($valor): string
    {
        $texto = is_scalar($valor) ? (string) $valor : json_encode($valor);
        $tam = strlen($texto);
        if ($tam === 0) {
            return '';
        }
        $residuo = $tam > 8 ? substr($texto, -4) : '';
        return '***' . ($residuo !== '' ? '(' . $residuo . ')' : '') . '[len:' . $tam . ']';
    }

    /**
     * Mascara cabeçalhos/textos que contenham Bearer ou secret embutidos.
     */
    protected static function mascararTextoAutenticacao(string $texto): string
    {
        $texto = preg_replace('/(Authorization\s*:\s*Bearer\s+)([^\s"]+)/i', '$1***', $texto);
        $texto = preg_replace('/(X-Webhook-Secret\s*:\s*)([^\s"]+)/i', '$1***', $texto);
        $texto = preg_replace('/(client_secret=)([^&\s"]+)/i', '$1***', $texto);
        $texto = preg_replace('/(secret=)([^&\s"]+)/i', '$1***', $texto);
        return $texto;
    }
}
