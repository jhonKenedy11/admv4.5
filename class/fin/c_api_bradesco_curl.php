<?php

/**
 * @package   astecv3
 * @name      c_api_bradesco_curl
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      03/12/2025
 * 
 * Classe responsável pela comunicação HTTP com as APIs do Bradesco
 */

$dir = dirname(__FILE__);
include_once($dir."/../../bib/c_session_manager.php");
include_once($dir."/../../bib/c_user.php");


class c_api_bradesco_curl extends c_user {

    private $ambiente = 'sandbox';
    private $timeout = 30;

    private int $id_conta;
    
    // Certificados conforme especificação Bradesco:
    // - Certificado Público (.crt ou .pem)
    // - Chave Privada (.key)
    private $certPath = '';      // Certificado Público (.crt ou .pem)
    private $keyPath = '';       // Chave Privada (.key)
    
    // Credenciais OAuth
    private string $clientId = '';
    private string $clientSecret = '';

    
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
     * @param int $empresa_id ID da empresa
     */
    public function __construct(string $ambiente = 'sandbox',int $empresa_id = 0) {
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
     * Carrega certificados conforme especificação Bradesco:
     * - Certificado Público (.crt ou .pem)
     * - Chave Privada (.key)
     * 
     * IMPORTANTE: Se o certificado for PFX, será convertido automaticamente para PEM.
     * 
     * Conversão manual via OpenSSL (caso necessário):
     * openssl pkcs12 -in certificado.pfx -clcerts -nokeys -out certificado.pem -passin pass:SENHA
     * openssl pkcs12 -in certificado.pfx -nocerts -nodes -out chave.key -passin pass:SENHA
     */
    private function carregarCertificados() {
        // Mapeamento: empresa => [certificado .pfx/.pem, senha do certificado]
        $certificados = [
            1 => ['cert' => 'ADMnfeCert01', 'senha' => 'ADMnfeSenha01'],
            2 => ['cert' => 'ADMnfeCert02', 'senha' => 'ADMnfeSenha02'],
            3 => ['cert' => 'ADMnfeCert03', 'senha' => 'ADMnfeSenha03'],
            4 => ['cert' => 'ADMnfeCert04', 'senha' => 'ADMnfeSenha04'],
            5 => ['cert' => 'ADMnfeCert05', 'senha' => 'ADMnfeSenha05'],
        ];
        
        $empresaId = (int)$this->empresa_id;

        if (!isset($certificados[$empresaId])) {
            throw new Exception('Certificado não encontrado para a empresa ' . $empresaId);
            return;
        }
        
        $certConst = $certificados[$empresaId]['cert'];
        $senhaConst = $certificados[$empresaId]['senha'];
        
        // Obtém nome do arquivo e senha
        if (!defined($certConst) || !defined($senhaConst)) {
            throw new Exception('Certificado ou senha não encontrados para a empresa ' . $empresaId);
        }
        
        $certFile = constant($certConst);
        $certSenha = constant($senhaConst);
        $certFullPath = BASE_DIR_CERT . $certFile;
        
        // Verifica se o arquivo existe
        if (!file_exists($certFullPath)) {
            throw new Exception('Certificado não encontrado para a empresa ' . $empresaId);
        }
        
        // Se for PFX, converte para PEM
        $extensao = strtolower(pathinfo($certFile, PATHINFO_EXTENSION));
        if ($extensao === 'pfx' || $extensao === 'p12') {
            $resultado = $this->converterPfxParaPem($certFullPath, $certSenha);
            if ($resultado) {
                $this->certPath = $resultado['cert'];
                $this->keyPath = $resultado['key'];
            }
        } else {
            // Já está em formato PEM
            $this->certPath = $certFullPath;
            // Assume que a chave está em arquivo separado com extensão .key
            $this->keyPath = str_replace(['.pem', '.crt'], '.key', $certFullPath);
        }
    }
    
    
    /**
     * Converte certificado PFX (PKCS#12) para formato PEM
     * Extrai certificado público e chave privada separadamente
     * 
     * @param string $pfxPath Caminho do arquivo PFX
     * @param string $senha Senha do certificado PFX
     * @return array|false Array com paths do cert e key, ou false em caso de erro
     */
    private function converterPfxParaPem($pfxPath, $senha) {
        // Diretório de saída (mesmo do PFX)
        $dirCerts = dirname($pfxPath);
        $baseName = pathinfo($pfxPath, PATHINFO_FILENAME);

        if(!is_dir($dirCerts) || !is_writable($dirCerts)) {
            c_api_response::serverError('Diretório de certificados não encontrado ou não é writable');
            return false;
        }
        
        // Arquivos de saída
        $certPemPath = $dirCerts . '/' . $baseName . '.pem';
        $keyPemPath = $dirCerts . '/' . $baseName . '.key';
        
        // Verifica se já foram extraídos (evita reprocessamento)
        if (file_exists($certPemPath) && file_exists($keyPemPath)) {
            // Verifica se o PFX é mais recente (certificado renovado)
            if (filemtime($pfxPath) <= filemtime($certPemPath)) {
                return ['cert' => $certPemPath, 'key' => $keyPemPath];
            }
        }
        
        // Lê o conteúdo do PFX
        $pfxContent = file_get_contents($pfxPath);
        if ($pfxContent === false) {
            return false;
        }
        
        // Extrai certificado e chave do PFX usando OpenSSL do PHP
        $certs = [];
        $privateKey = null;
        
        if (!openssl_pkcs12_read($pfxContent, $certs, $senha)) {
            // Erro ao ler PFX - senha incorreta ou arquivo corrompido
            return false;
        }
        
        // Extrai o certificado público
        if (!isset($certs['cert'])) {
            return false;
        }
        $certPem = $certs['cert'];
        
        // Extrai a chave privada
        if (!isset($certs['pkey'])) {
            return false;
        }
        $keyPem = $certs['pkey'];
        
        // Salva o certificado público em formato PEM
        if (file_put_contents($certPemPath, $certPem) === false) {
            return false;
        }
        
        // Salva a chave privada em formato PEM
        if (file_put_contents($keyPemPath, $keyPem) === false) {
            return false;
        }
        
        // Define permissões restritas para a chave privada
        chmod($keyPemPath, 0600);
        
        return ['cert' => $certPemPath, 'key' => $keyPemPath];
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
    

    // =========================================================================
    // MÉTODOS PÚBLICOS - OPERAÇÕES DA API
    // =========================================================================
    
    
    /**
     * Registra um boleto na API do Bradesco
     * @param string $jsonData Dados do boleto montados pelo json_builder
     * @return array
     */
    public function registrarBoleto(string $jsonData) : array {
        
        // Verifica se certificados estão configurados
        if (empty($this->certPath)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Certificado público não configurado',
                'erros' => ['certificado_publico_nao_configurado' => 'Certificado público (.crt/.pem) não configurado']
            ];
        }

        $endpoint = "/boleto/cobranca-registro/v1/cobranca";

        $retorno = $this->executarRequisicao($jsonData, $endpoint);

        
        return $retorno;
    }
    
    
    /**
     * Solicita baixa de um título na API do Bradesco
     * @param string $jsonData Dados da baixa montados pelo json_builder
     * @return array
     */
    public function baixarTitulo(string $jsonData) : array {
        $endpoint = "/boleto/cobranca-baixa/v1/baixar";
        $retorno = $this->executarRequisicao($jsonData, $endpoint);
        
        // Baixa usa status no body para definir sucesso
        $body = $retorno['body'] ?? [];
        $retorno['sucesso'] = ($retorno['http_code'] === 200 && isset($body['status']) && $body['status'] === 200);
        
        // Extrai campos específicos da resposta
        $this->extrairCamposResposta($retorno, $body);
        
        return $retorno;
    }
    
    
    /**
     * Altera um título na API do Bradesco
     * @param array $jsonData Dados da alteração montados pelo json_builder
     * @return array
     */
    public function alterarTitulo($jsonData) {
        $endpoint = "/boleto/cobranca-alteracao/v1/alterar";
        $retorno = $this->executarRequisicao($jsonData, $endpoint);
        
        $body = $retorno['body'] ?? [];
        $retorno['sucesso'] = ($retorno['http_code'] === 200 && isset($body['status']) && $body['status'] === 200);
        $this->extrairCamposResposta($retorno, $body);
        
        return $retorno;
    }
    
    
    /**
     * Consulta títulos liquidados
     * @param string $jsonData Parâmetros de consulta
     * @return array
     */
    public function consultarLiquidados(string $jsonData) {
        // Verifica se certificados estão configurados
        if (empty($this->certPath)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Certificado público não configurado',
                'erros' => ['certificado_publico_nao_configurado' => 'Certificado público (.crt/.pem) não configurado']
            ];
        }

        $endpoint = "/boleto/cobranca-lista/v1/listar";
        $retorno = $this->executarRequisicao($jsonData, $endpoint);
        
        return $retorno;
    }
    
    
    /**
     * Consulta títulos pendentes
     * @param string $jsonData Parâmetros de consulta
     * @return array
     */
    public function consultarPendentes(string $jsonData) {

        // Verifica se certificados estão configurados
        if (empty($this->certPath)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Certificado público não configurado',
                'erros' => ['certificado_publico_nao_configurado' => 'Certificado público (.crt/.pem) não configurado']
            ];
        }

        $endpoint = "/boleto/cobranca-pendente/v1/listar";
        $retorno = $this->executarRequisicao($jsonData, $endpoint);
        
        return $retorno;
    }
    
    
    /**
     * Consulta títulos baixados
     * @param string $jsonData Parâmetros de consulta
     * @return array
     */
    public function consultarBaixados(string $jsonData) {

        // Verifica se certificados estão configurados
        if (empty($this->certPath)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Certificado público não configurado',
                'erros' => ['certificado_publico_nao_configurado' => 'Certificado público (.crt/.pem) não configurado']
            ];
        }

        $endpoint = "/boleto/cobranca-baixado-consulta/v1/listar";
        $retorno = $this->executarRequisicao($jsonData, $endpoint);

        return $retorno;
    }

    /**
     * Consulta título unitário
     * @param string $jsonData Parâmetros da consulta
     * @return array
     */
    public function consultarTituloUnitario(string $jsonData) {
        $endpoint = "/boleto/cobranca-consulta/v1/consultar";
        $retorno = $this->executarRequisicao($jsonData, $endpoint);
        
        $body = $retorno['body'] ?? [];
        $this->extrairCamposResposta($retorno, $body);
        
        return $retorno;
    }
    
    
    /**
     * Solicita protesto/negativação de um título
     * @param string $jsonData Dados do protesto montados pelo json_builder
     * @return array
     */
    public function protestarTitulo(string $jsonData) {
        $endpoint = "/boleto/cobranca-protesto/v1/protestar";
        $retorno = $this->executarRequisicao($jsonData, $endpoint);
        
        $body = $retorno['body'] ?? [];
        $retorno['sucesso'] = ($retorno['http_code'] === 200 && isset($body['status']) && $body['status'] === 200);
        $this->extrairCamposResposta($retorno, $body);
        
        return $retorno;
    }


    // =========================================================================
    // MÉTODOS PRIVADOS - INFRAESTRUTURA
    // =========================================================================
    
    
    /**
     * Executa a requisição HTTP POST para a API
     */
    private function executarRequisicao(string $jsonData, string $endpoint) {

        // Autentica primeiro
        $auth = $this->autenticar();
        if (!$auth['sucesso']) {
            return $auth;
        }
        
        // Monta URL conforme ambiente
        $urlBase = ($this->ambiente === 'P') 
            ? "https://openapi.bradesco.com.br" 
            : "https://openapisandbox.prebanco.com.br";
        $url = $urlBase . $endpoint;

        //gravação simples do JSON (não interrompe execução se falhar)
        // $debugDir = __DIR__ . '/../../tmp';
        // if (!is_dir($debugDir)) {
        //     @mkdir($debugDir, 0755, true);
        // } 
        // $filename = $debugDir . '/bradesco_request_' . date('Ymd_His') . '.json';
        // @file_put_contents($filename, $jsonData);

        $this->responseHeaders = [];
        
        // Configura cURL com mTLS
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HEADERFUNCTION => [$this, 'parseHeaderLine'],
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer ' . $this->accessToken
            ],
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

        // gravação simples do JSON de resposta (não interrompe execução se falhar)
        $debugDir = __DIR__ . '/../../tmp';
        if (!is_dir($debugDir)) {
            @mkdir($debugDir, 0755, true);
        }
        $filename = $debugDir . '/bradesco_response_' . date('Ymd_His') . '.json';
        @file_put_contents($filename, $response);


        
        // Erro de conexão
        if ($curlError) {
            return [
                'sucesso' => false,
                'mensagem' => "Erro cURL: {$curlError}",
                'ambiente' => $this->ambiente,
                'endpoint' => $endpoint
            ];
        }
        
        // Retorno base
        return [
            'sucesso' => false, // será definido pelo método chamador
            'http_code' => $this->httpCode,
            'ambiente' => $this->ambiente,
            'endpoint' => $endpoint,
            'body' => json_decode($response, true),
            'response_raw' => $response // Response bruto para log no console JavaScript
        ];
    }
    
    
    /**
     * Autentica na API e obtém o Bearer Token
     * 
     * Endpoint conforme especificação:
     * - Produção: https://openapi.bradesco.com.br/auth/server-mtls/v2/token
     * - Sandbox: https://openapisandbox.prebanco.com.br/auth/server-mtls/v2/token
     */
    private function autenticar() : array
    {
        $sessionKey = 'bradesco_token_' . $this->ambiente;

        //SessionManager::delete('bradesco_token_' . $this->ambiente); 

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
        
        // URL de autenticação conforme ambiente
        $url = ($this->ambiente === 'P') 
            ? "https://openapi.bradesco.com.br/auth/server-mtls/v2/token"
            : "https://openapisandbox.prebanco.com.br/auth/server-mtls/v2/token";
        
        $postFields = http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
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
     * Extrai campos comuns da resposta (transacao, mensagem, dados)
     */
    private function extrairCamposResposta(&$retorno, $body) {
        if (isset($body['status'])) {
            $retorno['status'] = $body['status'];
        }
        if (isset($body['transacao'])) {
            $retorno['transacao'] = $body['transacao'];
        }
        if (isset($body['mensagem'])) {
            $retorno['mensagem'] = $body['mensagem'];
        }
        if (isset($body['dados'])) {
            $retorno['dados'] = $body['dados'];
        }
        if(isset($body['causa'])) {
            $retorno['causa'] = $body['causa'];
        }
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
    
    /**
     * Retorna descrição do código status-header (API de registro)
     */
    private function getDescricaoStatusHeader($codigo) {
        $descricoes = [
            -99 => 'Serviço indisponível no momento',
            -4 => 'Tamanho do campo inválido',
            -3 => 'Tipo do campo inválido',
            -2 => 'Contrato não encontrado',
            -1 => 'Contrato não aprovado',
            0 => 'Solicitação atendida',
            1 => 'Solicitação não encontrada',
            2 => 'Erro Genérico – sistema indisponível',
            5 => 'Inclusão efetuada',
            6 => 'Dados inconsistentes',
            10 => 'Erro Acesso Subrotina',
            12 => 'Cliente/Negociação Bloqueado',
            13 => 'Usuário não Autorizado',
            14 => 'Espécie Título Inválida',
            15 => 'Tipo/Número Inscrição Inválido',
            16 => 'Informe todos os campos para decurso de Prazo',
            17 => 'Nome do Pagador Especial Não Informado',
            18 => 'Endereço Inválido',
            19 => 'CEP Inválido',
            20 => 'Agência Depositária Inválida',
            21 => 'Informe todos os campos para Instrução de Protesto',
            22 => 'Banco Inválido',
            23 => 'Seu Número Inválido',
            24 => 'Informe todos os campos para Abatimento',
            25 => 'Valor dos Juros maior que o Valor do Título',
            26 => 'Data de Emissão maior que a Data de Vencimento',
            27 => 'Documento do Sacador Avalista Inválido',
            28 => 'Informe todos os campos para Desconto',
            29 => 'Informe todos os campos para Sacador Avalista',
            30 => 'Data Vencimento Menor ou igual Data Emissão',
            31 => 'Data Desconto menor ou igual Data Emissão',
            32 => 'Data Desconto maior que Data Vencimento',
            33 => 'Valor Desconto/Bonificação maior ou igual Valor Título',
            34 => 'Tipo informado deve ser 1, 2 ou 3',
            35 => 'Valor Abatimento maior que o Valor do Título',
            36 => 'CEP Inválido',
            37 => 'Data Emissão Inválida',
            38 => 'Data Vencimento Inválida',
            39 => 'Percentual informado maior ou igual 100,00',
            40 => 'Número CGC/CPF inválido',
            41 => 'Protesto Automático x Decurso de Prazo Incompatível',
            42 => 'Banco/Agência Depositária Inválido',
            43 => 'Espécie de Documento inválido',
            44 => 'Informe 1-contra apresentação ou 2-a vista',
            45 => 'Código da instrução de protesto inválido',
            46 => 'Dias para instrução de protesto inválido',
            47 => 'Código para desconto inválido',
            48 => 'Código para multa inválido',
            49 => 'Código para comissão permanência dia inválido',
            50 => 'Espécie Documento exige CGC para Sacador Avalista',
            51 => 'CEP e/ou Banco/Agência Depositária Inválido',
            52 => 'Data Emissão maior ou igual Data Vencimento',
            53 => 'Data Desconto Inválida',
            54 => 'Data emissão maior Data Registro',
            55 => 'Percentual multa informado maior que o permitido',
            56 => 'Percentual comissão permanência informado maior que o permitido',
            57 => 'Percentual Bonificação informado maior que o permitido',
            58 => 'Prazo para Protesto inválido',
            59 => 'Informe a data ou tipo do vencimento',
            60 => 'Valor do IOF não permitido para produtos 05,15,43 ou 44',
            61 => 'Abatimento já cadastrado para o título',
            62 => 'Abatimento não cadastrado para o título',
            63 => 'Não é permitida mais de uma bonificação para o título',
            64 => 'Não é permitido datas de desconto/bonificação iguais',
            65 => 'Negociação inexistente',
            66 => 'Cliente inexistente',
            67 => 'CNPJ/CPF inválido',
            68 => 'N.Número não pode ser informado quando status 4',
            69 => 'Título já cadastrado',
            70 => 'Data e tipo de vencimento incompatíveis',
            71 => 'Data de vencimento não pode ser posterior a 10 anos',
            72 => 'Dias para instrução inferior ao padrão',
            73 => 'Dias para instrução antecipa data de protesto',
            74 => 'Valor IOF obrigatório',
            75 => 'Valor IOF incompatível com id produto',
            76 => 'Tipo de abatimento inválido',
            77 => 'Status Inválido',
            78 => 'Registro online não permite banco diferente de 237',
            79 => 'Carta para protesto não recebida',
            80 => 'Tipo de vencimento inválido',
            81 => 'Valor acumulado desconto/bonificação maior ou igual valor título',
            82 => 'Datas desconto/bonificação fora de sequência',
            83 => 'Informe todos os campos para multa',
            84 => 'Código comissão permanência inválido',
            85 => 'Informe todos os campos para comissão permanência',
            86 => 'Registro duplicado na tabela de ocorrências',
            87 => 'Solicitação de protesto já existente',
            88 => 'Registro duplicado na base de atualização sequencial',
            89 => 'Sacador avalista já cadastrado',
            90 => 'Indicador CIP inexistente',
            91 => 'Moeda negociada inexistente',
            92 => 'Banco/agência operadora inexistente',
            93 => 'Acessório escritural negociado inexistente',
            94 => 'Pólo de serviço inexistente para banco/agência',
            95 => 'Banco/agência centralizadora não cadastrada',
            96 => 'Título não encontrado pelo módulo CBON8230',
            97 => 'Valor IOF maior ou igual valor título',
            98 => 'Data Inválida',
            99 => 'Id Prod/Cta não cadastrados'
        ];
        
        return $descricoes[$codigo] ?? "Código desconhecido: {$codigo}";
    }
}
