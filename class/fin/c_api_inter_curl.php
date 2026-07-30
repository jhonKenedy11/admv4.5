<?php

/**
 * @package   astecv3
 * @name      c_api_inter_curl
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      03/12/2025
 * 
 * Classe responsável pela comunicação HTTP com as APIs do Inter
 */

$dir = dirname(__FILE__);
include_once($dir."/../../bib/c_session_manager.php");
include_once($dir."/../../bib/c_user.php");


class c_api_inter_curl extends c_user {

    private $ambiente = 'sandbox';
    private $timeout = 30;
    
    // Certificados conforme especificação Inter:
    private $certPath = '';      // Certificado Público (.crt ou .pem)
    private $keyPath = '';       // Chave Privada (.key)
    
    // Credenciais OAuth
    private $clientId = '';
    private $clientSecret = '';
    
    // Token
    private $accessToken = '';
    
    // Resposta
    private $responseHeaders = [];
    private $httpCode = 0;
    
    // ID da empresa
    private $empresa_id = 0;
    
    /**
     * Construtor
     * @param string $ambiente 'sandbox' ou 'producao'
     * @param int $empresa_id ID da empresa (opcional)
     */
    public function __construct($ambiente = 'S', $empresa_id = null) {
        $this->ambiente = ($ambiente === 'P') ? 'P' : 'S';

        $this->empresa_id = $empresa_id;

        // Define diretório de certificados
        if (!defined('BASE_DIR_CERT')) {
            $slash = DIRECTORY_SEPARATOR;
            define('BASE_DIR_CERT', ADMnfe . $slash . $this->empresa_id . $slash . 'certs' . $slash);
        }
        
        // Carrega certificados da empresa
        $this->carregarCertificados();
    }


    public function setClientId(string $clientId) {
        $this->clientId = $clientId;
    }

    public function setClientSecret(string $clientSecret) {
        $this->clientSecret = $clientSecret;
    }

    public function getAmbiente() {
        return $this->ambiente;
    }
    
    /**
     * Carrega certificados mTLS do Inter: .crt e .key no diretório certs da empresa
     * (ADMnfe/{empresa_id}/certs/), sem conversão PFX.
     *
     * Sandbox: Sandbox_InterAPI_Certificado.crt + Sandbox_InterAPI_Chave.key
     * Produção: ajuste os nomes em $arquivosPorAmbiente quando tiver os arquivos oficiais.
     */
    private function carregarCertificados() {
        $empresaId = (int)$this->empresa_id;
        
        if ($empresaId < 1) {
            c_api_response::failure(
                'ID da empresa inválido para certificados Inter',
                [],
                null,
                ['type' => 'error']
            );
        }

        // Necessario renomear os arquivos para producao e sandbox
        $arquivosPorAmbiente = [
            'S' => [
                'cert' => 'Sandbox_InterAPI_Certificado.crt',
                'key' => 'Sandbox_InterAPI_Chave.key',
            ],
            'P' => [
                'cert' => 'InterAPI_Certificado.crt',
                'key' => 'InterAPI_Chave.key',
            ],
        ];

        $amb = ($this->ambiente === 'P') ? 'P' : 'S';
        $nomes = $arquivosPorAmbiente[$amb];

        $slash = DIRECTORY_SEPARATOR;
        $certDir = ADMnfe . $slash . $empresaId . $slash . 'certs' . $slash;

        $this->certPath = $certDir . $nomes['cert'];
        $this->keyPath = $certDir . $nomes['key'];

        if (!is_file($this->certPath)) {
            c_api_response::failure(
                'Certificado Inter não encontrado: ' . $this->certPath,
                [],
                null,
                ['type' => 'error']
            );
        }
        if (!is_file($this->keyPath)) {
            c_api_response::failure(
                'Chave privada Inter não encontrada: ' . $this->keyPath,
                [],
                null,
                ['type' => 'error']
            );
        }
    }


    /**
     * Configura certificados manualmente
     * @param string $certPath Certificado Público (.crt ou .pem)
     * @param string $keyPath Chave Privada (.key)
     */
    public function setCertificados($certPath, $keyPath) {
        $this->certPath = $certPath;
        $this->keyPath = $keyPath;
    }
    
    
    /**
     * Configura credenciais OAuth
     * @param string $clientId Client ID
     * @param string $clientSecret Client Secret
     */
    public function setCredenciais($clientId, $clientSecret) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }


    // =========================================================================
    // MÉTODOS PÚBLICOS - OPERAÇÕES DA API
    // =========================================================================
    
    
    /**
     * Emite uma cobrança na API do Inter
     * @param string $jsonData Dados do boleto montados pelo json_builder
     * @param string $conta_corrente_header Conta Corrente e digito verificador
     * @return array
     */
    public function emitirCobranca(string $jsonData, string $conta_corrente_header) {
        
        // Verifica se certificados estão configurados
        if (empty($this->certPath)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Certificados não configurados para a empresa ' . $this->empresa_id
            ];
        }

        

        // Parametro para solicitacao de credencial
        // producao 120 por minuto | sandbox 10 por minuto
        // Token valido por 60 minutos e reutilizado 
        $scope    = "boleto-cobranca.write";
        $endpoint = "/cobranca/v3/cobrancas";

        $retorno = $this->post($endpoint, $scope, $conta_corrente_header, $jsonData);
        
        // Registro usa status_header para definir sucesso
        $statusHeader = $this->extrairStatusHeader();
        
        $retorno['sucesso'] = ($retorno['http_code'] >= 200 && $retorno['http_code'] < 300) 
                              && in_array($statusHeader, [0, 5]);
        
        
        return $retorno;
    }

    /**
     * Consulta uma cobrança pelo código de solicitação (GET).
     *
     * @param string $codigo_solicitacao UUID retornado na emissão (codigoSolicitacao)
     * @param string $conta_corrente_header Conta corrente + dígito (mesmo header da emissão)
     */
    public function recuperarCobranca(string $codigo_solicitacao, string $conta_corrente_header) {
        $scope = 'boleto-cobranca.read';
        $endpoint = '/cobranca/v3/cobrancas/{codigoSolicitacao}';

        $statusHeader = $this->extrairStatusHeader();

        $retorno = $this->get($endpoint, $scope, $conta_corrente_header, [
            'codigoSolicitacao' => $codigo_solicitacao,
        ]);

        return $retorno;
    }


    public function recuperarCobrancaEmPdf(string $codigo_solicitacao, string $conta_corrente_header) {
        $scope = 'boleto-cobranca.read';
        $endpoint = '/cobranca/v3/cobrancas/{codigoSolicitacao}/pdf';

        $statusHeader = $this->extrairStatusHeader();

        $retorno = $this->get($endpoint, $scope, $conta_corrente_header, [
            'codigoSolicitacao' => $codigo_solicitacao,
        ]);

        return $retorno;
    }


    /**
     * Cancela uma cobrança na API do Inter
     * @param string $codigo_solicitacao UUID da cobrança (codigoSolicitacao)
     * @param string $conta_corrente_header Conta corrente + dígito
     * @param string $motivo_cancelamento Motivo do cancelamento
     * @return array
     */
    public function cancelarCobranca(string $codigo_solicitacao, string $conta_corrente_header, string $motivo_cancelamento) {
        $scope    = 'boleto-cobranca.write';
        $endpoint = '/cobranca/v3/cobrancas/{codigoSolicitacao}/cancelar';

        $json = json_encode(['motivoCancelamento' => $motivo_cancelamento], JSON_UNESCAPED_UNICODE);

        // Cancelar Cobranca em REQUEST BODY - usar o ultimo parametro $pathParams
        $retorno = $this->post($endpoint, $scope, $conta_corrente_header, $json, [
            'codigoSolicitacao' => $codigo_solicitacao,
        ]);

        return $retorno;
    }

    /**
     * Paga uma cobrança na API do Inter
     * @param string $codigo_solicitacao UUID da cobrança (codigoSolicitacao)
     * @param string $conta_corrente_header Conta corrente + dígito
     * @param string $metodo_pagamento Método de pagamento
     * @return array
     */
    public function pagarCobranca(string $codigo_solicitacao, string $conta_corrente_header, string $metodo_pagamento) {
        $scope = 'boleto-cobranca.write';
        $endpoint = '/cobranca/v3/cobrancas/{codigoSolicitacao}/pagar';

        $json = json_encode(['pagarCom' => $metodo_pagamento], JSON_UNESCAPED_UNICODE);

        // Pagar Cobranca em REQUEST BODY - usar o ultimo parametro $pathParams
        $retorno = $this->post($endpoint, $scope, $conta_corrente_header, $json, [
            'codigoSolicitacao' => $codigo_solicitacao,
        ]);

        return $retorno;
    }

    /**
     * Recupera a coleção de cobranças na API do Inter
     * @param array $query_array Parâmetros da consulta
     * @param string $conta_corrente_header Conta corrente + dígito
     * @return array
     */
    public function recuperarColecaoCobranca(array $query_array, string $conta_corrente_header) {
        $scope = 'boleto-cobranca.read';
        $endpoint = '/cobranca/v3/cobrancas';

        $retorno = $this->get($endpoint, $scope, $conta_corrente_header, [], $query_array);

        return $retorno;
    }

    // =========================================================================
    // MÉTODOS PRIVADOS - INFRAESTRUTURA
    // =========================================================================
    
    
    /**
     * Executa requisição HTTP para a API com mTLS.
     *
     * @param string $endpoint           Caminho após o host; use placeholders `{nome}` para path parameters (ex.: /cobranca/v3/cobrancas/{codigoSolicitacao})
     * @param string $scope              Scope OAuth
     * @param string $contaCorrenteHeader Conta corrente + dígito
     * @param string $metodoHttp         GET | POST (padrão POST)
     * @param string|null $jsonData      Corpo JSON (apenas POST)
     * @param array $pathParams          Valores para substituir no path, ex.: ['codigoSolicitacao' => 'uuid'] → /.../uuid
     */
    private function executarRequisicao(
        string $endpoint,
        string $scope,
        string $contaCorrenteHeader,
        string $metodoHttp = 'POST',
        ?string $jsonData = null,
        array $pathParams = [],
        array $queryParams = []
    ): array {

        $auth = $this->autenticar($scope);
        
        if (!$auth['sucesso']) {
            return $auth;
        }

        $urlBase = $this->ambiente === 'P'
            ? 'https://cdpj.partners.bancointer.com.br'
            : 'https://cdpj-sandbox.partners.uatinter.co';

        $metodo              = $this->normalizarMetodo($metodoHttp);
        $end_point_resolvido = $this->aplicarPathParameters($endpoint, $pathParams);
        $url                 = $urlBase . $end_point_resolvido;


        if($queryParams) {
            $url .= '?' . http_build_query($queryParams);
        }

        // Define o Accept conforme a última parte do endpoint
        $ultimaparte = basename($endpoint);
        // Se a última parte do endpoint for cancelar, define o Accept como application/problem+json
        if($ultimaparte == 'cancelar' || $ultimaparte == 'pagar') {
            $accept = 'application/problem+json';
        } else {
            $accept = 'application/json';
        }

        $headers = [
            'Accept: ' . $accept,
            'Authorization: Bearer ' . $this->accessToken,
            'x-conta-corrente: ' . $contaCorrenteHeader,
        ];

        if ($metodo === 'POST') {
            $headers[] = 'Content-Type: application/json';
        }

        $this->responseHeaders = [];

        $ch = curl_init();
        curl_setopt_array($ch, $this->buildCurlOpts($url, $metodo, $headers, $jsonData));

        $response  = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return [
                'sucesso'   => false,
                'mensagem'  => "Erro cURL: {$curlError}",
                'ambiente'  => $this->ambiente,
                'endpoint'  => $end_point_resolvido,
            ];
        }

        return [
            'sucesso'       => true, // definido pelo chamador
            'http_code'     => $this->httpCode,
            'ambiente'      => $this->ambiente,
            'endpoint'      => $end_point_resolvido,
            'body'          => json_decode($response, true),
            'response_raw'  => $response,
        ];
    }

    // --- Fachadas semânticas (limpas para os chamadores) ---

    /**
     * GET com path parameters opcionais: endpoint com `{chave}` e $pathParams ['chave' => valor].
     */
    private function get(string $endpoint, string $scope, string $conta, array $pathParams = [], array $queryParams = []): array
    {
        return $this->executarRequisicao($endpoint, $scope, $conta, 'GET', null, $pathParams, $queryParams);
    }

    /**
     * POST — ordem: endpoint, scope, conta, JSON (igual emitirCobranca).
     */
    private function post(string $endpoint, string $scope, string $conta, string $jsonData, array $pathParams = []): array
    {
        return $this->executarRequisicao($endpoint, $scope, $conta, 'POST', $jsonData, $pathParams);
    }

    // --- Helpers privados ---

    /**
     * Substitui `{nomeParam}` no path por valores com rawurlencode (UUID e segmentos de URL).
     *
     * @param array $pathParams ex.: ['codigoSolicitacao' => 'd1564131-b0e4-460f-95be-0278b8734bce']
     */
    private function aplicarPathParameters(string $endpoint, array $pathParams): string
    {
        if ($pathParams === []) {
            return $endpoint;
        }
        $path = $endpoint;
        foreach ($pathParams as $nome => $valor) {
            if ($valor === null || $valor === '') {
                continue;
            }
            $marcador = '{' . $nome . '}';
            if (strpos($path, $marcador) === false) {
                continue;
            }
            $path = str_replace($marcador, rawurlencode((string) $valor), $path);
        }
        return $path;
    }

    private function normalizarMetodo(string $metodo): string
    {
        $m = strtoupper(trim($metodo));
        return in_array($m, ['GET', 'POST'], true) ? $m : 'POST';
    }

    private function buildCurlOpts(string $url, string $metodo, array $headers, ?string $body): array
    {
        $opts = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_HEADERFUNCTION => [$this, 'parseHeaderLine'],
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_SSLCERT        => $this->certPath,
            CURLOPT_SSLKEY         => $this->keyPath,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ];

        if ($metodo === 'GET') {
            $opts[CURLOPT_HTTPGET] = true;
        } else {
            $opts[CURLOPT_POST]       = true;
            $opts[CURLOPT_POSTFIELDS] = $body ?? '';
        }

        return $opts;
    }
    
    
    /**
     * Autentica na API e obtém o Bearer Token
     * 
     * Endpoint conforme especificação:
     * - Produção: https://cdpj.partners.bancointer.com.br/oauth/v2/token
     * - Sandbox: https://cdpj-sandbox.partners.uatinter.co/oauth/v2/token
     */
    private function autenticar(string $scope) {
        $sessionKey = 'inter_token_' . $this->ambiente . '_' . md5($scope);

        //SessionManager::delete('inter_token_' . $this->ambiente . '_' . md5($scope)); 

        // Verifica token em cache
        $tokenSessao = SessionManager::get($sessionKey);
        if ($tokenSessao && isset($tokenSessao['access_token'])) {
            $this->accessToken = $tokenSessao['access_token'];
            return ['sucesso' => true, 'access_token' => $this->accessToken];
        }
        
        // Validações
        if (empty($this->clientId) || empty($this->clientSecret)) {
            return ['sucesso' => false, 'mensagem' => 'Client ID e Client Secret são obrigatórios'];
        }

        if (empty($this->certPath)) {
            return ['sucesso' => false, 'mensagem' => 'Certificado público (.crt/.pem) não configurado'];
        }
        
        if (!file_exists($this->certPath)) {
            return ['sucesso' => false, 'mensagem' => 'Certificado público não encontrado: ' . $this->certPath];
        }
        
        if (empty($this->keyPath)) {
            return ['sucesso' => false, 'mensagem' => 'Chave privada (.key) não configurada'];
        }
        
        if (!file_exists($this->keyPath)) {
            return ['sucesso' => false, 'mensagem' => 'Chave privada não encontrada: ' . $this->keyPath];
        }

        if (empty($scope)) {
            return ['sucesso' => false, 'mensagem' => 'Scope não configurado'];
        }
        
        // URL de autenticação conforme ambiente
        $url = ($this->ambiente === 'P') 
            ? "https://cdpj.partners.bancointer.com.br/oauth/v2/token"
            : "https://cdpj-sandbox.partners.uatinter.co/oauth/v2/token";
        
        $postFields = http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => $scope,
        ]);
        
        // Configura cURL com mTLS
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            // Certificado Público (.crt ou .pem)
            CURLOPT_SSLCERT => $this->certPath,
            // Chave Privada (.key)
            CURLOPT_SSLKEY => $this->keyPath,
            // Verificação SSL
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);
        
        // Executa
        $response = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            return [
                'sucesso' => false, 
                'mensagem' => "Erro cURL: {$curlError}",
                'certPath' => $this->certPath,
                'keyPath' => $this->keyPath
            ];
        }
        
        $data = json_decode($response, true);
        
        // Sucesso
        if ($this->httpCode === 200 && isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
            $expiresIn = $data['expires_in'] ?? 3600;
            
            // Cache do token (menos 5 minutos de margem)
            SessionManager::set($sessionKey, [
                'access_token' => $this->accessToken,
                'expires_in' => $expiresIn
            ], $expiresIn - 300);
            
            return [
                'sucesso' => true,
                'access_token' => $this->accessToken,
                'expires_in' => $expiresIn
            ];
        }
        
        return [
            'sucesso' => false,
            'mensagem' => $data['error_description'] ?? $data['error'] ?? 'Erro na autenticação',
            'http_code' => $this->httpCode
        ];
    }
    
    
    /**
     * Extrai o status-header da resposta (usado na API de registro)
     */
    private function extrairStatusHeader() {
        foreach ($this->responseHeaders as $name => $value) {
            if (strtolower($name) === 'status' || strtolower($name) === 'status-header') {
                return (int)$value;
            }
        }
        return null;
    }
    
    
    /**
     * Callback para capturar headers da resposta
     */
    private function parseHeaderLine($ch, $headerLine) {
        $len = strlen($headerLine);
        $header = explode(':', $headerLine, 2);
        if (count($header) === 2) {
            $this->responseHeaders[trim($header[0])] = trim($header[1]);
        }
        return $len;
    }

}