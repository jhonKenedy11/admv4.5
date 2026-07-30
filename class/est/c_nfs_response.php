<?php

/**
 * @package   admv4.5
 * @name      c_nfs_response
 * @version   4.5.0
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Sistema ADM v4.5
 * @date      09/10/2025
 */

/**
 * Classe responsável por padronizar as respostas JSON do sistema de NFS-e
 * Garante headers corretos e formato consistente para o frontend
 */
class c_nfs_response
{
    /**
     * Envia resposta de sucesso para o frontend
     *
     * @param string $message Mensagem de sucesso
     * @param array $data Dados adicionais (número da nota, código de verificação, etc.)
     * @param string|null $xmlResposta XML de resposta do webservice (opcional)
     */
    public static function success(string $message, array $data = [], ?string $xmlResposta = null): void
    {
        self::setHeaders();
        
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        
        // Adicionar XML de resposta se fornecido
        if ($xmlResposta !== null) {
            $response['xml_resposta'] = $xmlResposta;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    /**
     * Envia resposta de erro para o frontend
     *
     * @param string $message Mensagem principal do erro
     * @param array $erros Lista de erros detalhados (opcional)
     * @param string|null $xmlResposta XML de resposta do webservice (opcional)
     * @param int $httpCode Código HTTP de resposta (padrão 400)
     */
    public static function error(string $message, array $erros = [], ?string $xmlResposta = null, int $httpCode = 400): void
    {
        self::setHeaders();
        
        // Definir código de status HTTP se diferente de 200
        if ($httpCode !== 200) {
            http_response_code($httpCode);
        }
        
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        // Adicionar lista de erros se fornecida
        if (!empty($erros)) {
            $response['erros'] = $erros;
        }
        
        // Adicionar XML de resposta se fornecido
        if ($xmlResposta !== null) {
            $response['xml_resposta'] = $xmlResposta;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    /**
     * Envia resposta de validação com erros específicos
     *
     * @param array $errosValidacao Lista de erros de validação
     * @param string $message Mensagem principal (opcional)
     */
    public static function validationError(string $message = 'Erro de validação dos dados'): void
    {
        self::setHeaders();
        http_response_code(422); // Unprocessable Entity
        
        $response = [
            'success' => false,
            'message' => $message,
            'tipo' => 'validacao'
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
    
    /**
     * Envia resposta baseada no resultado do processamento
     * Automaticamente determina se é sucesso ou erro baseado no array de resultado
     *
     * @param array $resultado Array com 'success', 'message' e outros dados
     */
    public static function fromResult(array $resultado): void
    {
        $isSuccess = $resultado['success'] ?? false;
        $message = $resultado['message'] ?? ($isSuccess ? 'Operação realizada com sucesso' : 'Erro na operação');
        
        if ($isSuccess) {
            // Extrair dados para resposta de sucesso
            $data = [];
            if (isset($resultado['numero_nota'])) $data['numero_nota'] = $resultado['numero_nota'];
            if (isset($resultado['codigo_verificacao'])) $data['codigo_verificacao'] = $resultado['codigo_verificacao'];
            if (isset($resultado['link_nfse'])) $data['link_nfse'] = $resultado['link_nfse'];
            
            self::success($message, $data, $resultado['xml_resposta'] ?? null);
            
        } else {
            // Extrair erros para resposta de erro
            $erros = $resultado['erros'] ?? [];
            $httpCode = 400;
            
            // Se é erro de validação, usar código 422
            if (isset($resultado['tipo']) && $resultado['tipo'] === 'validacao') {
                $httpCode = 422;
            }
            
            self::error($message, $erros, $resultado['xml_resposta'] ?? null, $httpCode);
        }
    }
    
    /**
     * Define os headers padrão para respostas JSON
     */
    private static function setHeaders(): void
    {
        // Definir tipo de conteúdo como JSON com charset UTF-8
        header('Content-Type: application/json; charset=utf-8');
        
        // Headers de cache para evitar cache de respostas de API
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Headers de segurança básicos
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
    }
    
    /**
     * Converte resultado do webservice para formato padronizado
     * Usado para converter respostas das estratégias (IPM, GINFES, etc.)
     *
     * @param array $resultadoWebservice Resultado do processamento do webservice
     * @return array Array padronizado para uso com fromResult()
     */
    public static function normalizeWebserviceResult(array $resultadoWebservice): array
    {
        $normalized = [
            'success' => $resultadoWebservice['success'] ?? false,
            'message' => $resultadoWebservice['message'] ?? 'Resposta do webservice sem mensagem'
        ];
        
        // Preservar dados específicos do webservice
        if (isset($resultadoWebservice['numero_nota'])) {
            $normalized['numero_nota'] = $resultadoWebservice['numero_nota'];
        }
        
        if (isset($resultadoWebservice['codigo_verificacao'])) {
            $normalized['codigo_verificacao'] = $resultadoWebservice['codigo_verificacao'];
        }
        
        if (isset($resultadoWebservice['link_nfse'])) {
            $normalized['link_nfse'] = $resultadoWebservice['link_nfse'];
        }
        
        if (isset($resultadoWebservice['erros'])) {
            $normalized['erros'] = $resultadoWebservice['erros'];
        }
        
        if (isset($resultadoWebservice['xml_resposta'])) {
            $normalized['xml_resposta'] = $resultadoWebservice['xml_resposta'];
        }
        
        return $normalized;
    }
}

?>

