<?php

/**
 * @package   admv4.5
 * @name      c_api_response
 * @version   4.5.0
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Sistema ADM v4.5
 * @date      09/12/2025
 */

/**
 * Classe global responsável por padronizar todas as respostas JSON/API do sistema
 * Segue o padrão REST com códigos HTTP apropriados e estrutura consistente
 * 
 * Estrutura de resposta padrão:
 * {
 *     "success": boolean,
 *     "message": string,
 *     "data": object|array|null,
 *     "errors": array|null,
 *     "meta": object|null
 * }
 */
class c_api_response
{
    // Códigos HTTP padrão REST
    const HTTP_OK           = 200;
    const HTTP_CREATED      = 201;
    const HTTP_NO_CONTENT   = 204;
    const HTTP_BAD_REQUEST  = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN    = 403;
    const HTTP_NOT_FOUND    = 404;
    const HTTP_METHOD_NOT_ALLOWED   = 405;
    const HTTP_CONFLICT             = 409;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_INTERNAL_ERROR       = 500;
    const HTTP_SERVICE_UNAVAILABLE  = 503;

    /**
     * Envia resposta de sucesso (HTTP 200)
     *
     * @param string $message Mensagem de sucesso
     * @param mixed $data Dados de retorno (opcional)
     * @param array $meta Metadados adicionais como paginação, etc. (opcional)
     */
    public static function success(string $message, $data = null, array $meta = []): void
    {
        self::send(true, $message, $data, [], $meta, self::HTTP_OK);
    }

    /**
     * Falha de aplicação com HTTP 200 e success=false (mesmo envelope JSON das demais respostas).
     * Indicado para endpoints consumidos por jQuery.ajax quando os erros de negócio devem cair no
     * callback success (status 2xx); use validationError/badRequest/etc. para APIs REST com 4xx/5xx.
     *
     * @param string $message Mensagem principal
     * @param array $errors Lista de erros (opcional)
     * @param mixed $data Dados extras (opcional)
     * @param array $meta Metadados, ex.: ['type' => 'validation'|'api'|'unauthorized']
     */
    public static function failure(string $message, array $errors = [], $data = null, array $meta = []): void
    {
        self::send(false, $message, $data, $errors, $meta, self::HTTP_OK);
    }

    /**
     * Envia resposta de criação com sucesso (HTTP 201)
     *
     * @param string $message Mensagem de sucesso
     * @param mixed $data Dados do recurso criado
     * @param array $meta Metadados adicionais (opcional)
     */
    public static function created(string $message, $data = null, array $meta = []): void
    {
        self::send(true, $message, $data, [], $meta, self::HTTP_CREATED);
    }

    /**
     * Envia resposta de erro genérico (HTTP 400)
     *
     * @param string $message Mensagem do erro
     * @param array $errors Lista de erros detalhados (opcional)
     * @param mixed $data Dados adicionais (opcional)
     */
    public static function badRequest(string $message, array $errors = [], $data = null): void
    {
        self::send(false, $message, $data, $errors, [], self::HTTP_BAD_REQUEST);
    }

    /**
     * Envia resposta de não autorizado (HTTP 401)
     *
     * @param string $message Mensagem do erro
     */
    public static function unauthorized(string $message = 'Não autorizado. Faça login para continuar.'): void
    {
        self::send(false, $message, null, [], [], self::HTTP_UNAUTHORIZED);
    }

    /**
     * Envia resposta de acesso negado (HTTP 403)
     *
     * @param string $message Mensagem do erro
     */
    public static function forbidden(string $message = 'Acesso negado. Você não tem permissão para esta ação.'): void
    {
        self::send(false, $message, null, [], [], self::HTTP_FORBIDDEN);
    }

    /**
     * Envia resposta de recurso não encontrado (HTTP 404)
     *
     * @param string $message Mensagem do erro
     */
    public static function notFound(string $message = 'Recurso não encontrado.'): void
    {
        self::send(false, $message, null, [], [], self::HTTP_NOT_FOUND);
    }

    /**
     * Envia resposta de conflito (HTTP 409)
     * Ex: Tentar criar recurso que já existe
     *
     * @param string $message Mensagem do erro
     * @param array $errors Lista de erros detalhados (opcional)
     */
    public static function conflict(string $message, array $errors = []): void
    {
        self::send(false, $message, null, $errors, [], self::HTTP_CONFLICT);
    }

    /**
     * Envia resposta de erro de validação (HTTP 422)
     *
     * @param string $message Mensagem principal do erro
     * @param array $errors Lista de erros de validação por campo
     * @param array $data Dados extras (ex: código de erro da API, http_code, etc.)
     */
    public static function validationError(string $message = 'Erro de validação dos dados', array $errors = [], $data = null): void
    {
        $meta = ['type' => 'validation'];
        self::send(false, $message, $data, $errors, $meta, self::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Envia resposta de erro interno do servidor (HTTP 500)
     *
     * @param string $message Mensagem do erro
     * @param array $errors Lista de erros detalhados (opcional, use com cuidado em produção)
     */
    public static function serverError(string $message = 'Erro interno do servidor.', array $errors = []): void
    {
        self::send(false, $message, null, $errors, [], self::HTTP_INTERNAL_ERROR);
    }

    /**
     * Envia resposta de serviço indisponível (HTTP 503)
     *
     * @param string $message Mensagem do erro
     */
    public static function serviceUnavailable(string $message = 'Serviço temporariamente indisponível.'): void
    {
        self::send(false, $message, null, [], [], self::HTTP_SERVICE_UNAVAILABLE);
    }

    /**
     * Envia resposta customizada com código HTTP específico
     *
     * @param bool $success Status de sucesso
     * @param string $message Mensagem
     * @param mixed $data Dados (opcional)
     * @param array $errors Lista de erros (opcional)
     * @param array $meta Metadados (opcional)
     * @param int $httpCode Código HTTP
     */
    public static function custom(bool $success, string $message, $data = null, array $errors = [], array $meta = [], int $httpCode = 200): void
    {
        self::send($success, $message, $data, $errors, $meta, $httpCode);
    }

    /**
     * Converte resultado de operação para resposta padronizada
     * Útil para integrar com métodos que retornam arrays com 'success'
     *
     * @param array $result Array com 'success', 'message' e outros dados
     */
    public static function fromResult(array $result): void
    {
        $success = $result['success'] ?? false;
        $message = $result['message'] ?? ($success ? 'Operação realizada com sucesso' : 'Erro na operação');
        $data = $result['data'] ?? null;
        $errors = $result['errors'] ?? $result['erros'] ?? [];
        $meta = $result['meta'] ?? [];
        
        // Determinar código HTTP
        $httpCode = self::HTTP_OK;
        if (!$success) {
            $httpCode = self::HTTP_BAD_REQUEST;
            
            // Se há tipo de erro específico
            if (isset($result['type']) || isset($result['tipo'])) {
                $type = $result['type'] ?? $result['tipo'];
                switch ($type) {
                    case 'validation':
                    case 'validacao':
                        $httpCode = self::HTTP_UNPROCESSABLE_ENTITY;
                        break;
                    case 'not_found':
                    case 'nao_encontrado':
                        $httpCode = self::HTTP_NOT_FOUND;
                        break;
                    case 'unauthorized':
                    case 'nao_autorizado':
                        $httpCode = self::HTTP_UNAUTHORIZED;
                        break;
                    case 'forbidden':
                    case 'acesso_negado':
                        $httpCode = self::HTTP_FORBIDDEN;
                        break;
                }
            }
        }
        
        // Extrair dados específicos para o objeto data se não definido
        if ($data === null && $success) {
            $reservedKeys = ['success', 'message', 'data', 'errors', 'erros', 'meta', 'type', 'tipo'];
            $data = array_diff_key($result, array_flip($reservedKeys));
            if (empty($data)) {
                $data = null;
            }
        }
        
        self::send($success, $message, $data, $errors, $meta, $httpCode);
    }

    /**
     * Envia resposta com paginação
     *
     * @param array $items Itens da página atual
     * @param int $total Total de registros
     * @param int $page Página atual
     * @param int $perPage Itens por página
     * @param string $message Mensagem (opcional)
     */
    public static function paginated(array $items, int $total, int $page, int $perPage, string $message = 'Dados carregados com sucesso'): void
    {
        $totalPages = ceil($total / $perPage);
        
        $meta = [
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];
        
        self::send(true, $message, $items, [], $meta, self::HTTP_OK);
    }

    /**
     * Método principal que monta e envia a resposta JSON
     *
     * @param bool $success Status de sucesso
     * @param string $message Mensagem
     * @param mixed $data Dados
     * @param array $errors Erros
     * @param array $meta Metadados
     * @param int $httpCode Código HTTP
     */
    private static function send(bool $success, string $message, $data, array $errors, array $meta, int $httpCode): void
    {
        self::setHeaders($httpCode);
        
        $response = [
            'success' => $success,
            'message' => $message
        ];
        
        // Adicionar data se não for null
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        // Adicionar errors se não estiver vazio
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        // Adicionar meta se não estiver vazio
        if (!empty($meta)) {
            $response['meta'] = $meta;
        }
        
        // Adicionar timestamp para debugging e cache control
        $response['timestamp'] = date('c');

        $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
        
        if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
            $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
        }
        $json = json_encode($response, $flags);
        if ($json === false) {
            $json = json_encode(
                [
                    'success' => false,
                    'message' => 'Falha ao serializar a resposta.',
                    'timestamp' => date('c'),
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }
        echo $json;
        exit;
    }

    /**
     * Define os headers HTTP padrão para respostas de API
     *
     * @param int $httpCode Código HTTP de resposta
     */
    private static function setHeaders(int $httpCode = 200): void
    {
        // Definir código de status HTTP
        http_response_code($httpCode);
        
        // Content-Type como JSON com charset UTF-8
        header('Content-Type: application/json; charset=utf-8');
        
        // Headers de cache - API responses não devem ser cacheadas
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Headers de segurança
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        
        // CORS headers (ajuste conforme necessidade)
        // header('Access-Control-Allow-Origin: *');
        // header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }

    /**
     * Trata requisições OPTIONS para CORS (preflight)
     */
    public static function handleCors(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
            header('Access-Control-Max-Age: 86400');
            http_response_code(204);
            exit;
        }
    }

    /**
     * Retorna descrição textual do código HTTP
     *
     * @param int $code Código HTTP
     * @return string Descrição do código
     */
    public static function getHttpStatusText(int $code): string
    {
        $statusTexts = [
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            422 => 'Unprocessable Entity',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable'
        ];
        
        return $statusTexts[$code] ?? 'Unknown Status';
    }
}

