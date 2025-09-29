<?php

/**
 * Classe para gerenciar cookies de sessão com segurança
 */
class SessionCookieManager
{
    // Configurações padrão de segurança
    private const DEFAULT_EXPIRE_TIME = 3600; // 1 hora em segundos
    private const COOKIE_PREFIX = 'secure_';

    /**
     * Define um cookie de sessão com configurações de segurança
     * 
     * @param string $name Nome do cookie
     * @param mixed $value Valor do cookie (será serializado se for array/objeto)
     * @param int $expireTime Tempo de expiração em segundos (0 para cookie de sessão)
     * @param array $options Opções adicionais do cookie
     * @return bool Retorna true se o cookie foi definido com sucesso
     */
    public static function setCookie($name, $value, $expireTime = 0, $options = [])
    {
        // Valida se o nome do cookie é válido
        if (empty($name) || !is_string($name)) {
            throw new InvalidArgumentException('Nome do cookie deve ser uma string não vazia');
        }

        // Adiciona prefixo de segurança ao nome do cookie
        $cookieName = self::COOKIE_PREFIX . $name;

        // Serializa o valor se for array ou objeto
        if (is_array($value) || is_object($value)) {
            $value = serialize($value);
        }

        // Criptografa o valor do cookie para maior segurança
        $encryptedValue = self::encryptValue($value);

        // Define o tempo de expiração
        $expire = $expireTime > 0 ? time() + $expireTime : 0;

        // Configurações padrão de segurança do cookie
        $defaultOptions = [
            'expires' => $expire,
            'path' => '/', // Disponível em todo o site
            'domain' => '', // Domínio atual
            'secure' => isset($_SERVER['HTTPS']), // Apenas HTTPS se disponível
            'httponly' => true, // Não acessível via JavaScript (previne XSS)
            'samesite' => 'Strict' // Proteção contra CSRF
        ];

        // Mescla opções personalizadas com as padrão
        $cookieOptions = array_merge($defaultOptions, $options);

        // Define o cookie usando a função setcookie com todas as opções de segurança
        return setcookie($cookieName, $encryptedValue, $cookieOptions);
    }

    /**
     * Obtém o valor de um cookie de sessão
     * 
     * @param string $name Nome do cookie
     * @param mixed $default Valor padrão se o cookie não existir
     * @return mixed Valor do cookie descriptografado ou valor padrão
     */
    public static function getCookie($name, $default = null)
    {
        // Valida o nome do cookie
        if (empty($name) || !is_string($name)) {
            return $default;
        }

        // Adiciona o prefixo ao nome do cookie
        $cookieName = self::COOKIE_PREFIX . $name;

        // Verifica se o cookie existe
        if (!isset($_COOKIE[$cookieName])) {
            return $default;
        }

        // Descriptografa o valor do cookie
        $decryptedValue = self::decryptValue($_COOKIE[$cookieName]);

        // Se falhou na descriptografia, retorna o valor padrão
        if ($decryptedValue === false) {
            return $default;
        }

        // Tenta deserializar o valor (caso seja um array/objeto)
        $unserializedValue = @unserialize($decryptedValue);

        // Retorna o valor deserializado ou o valor original
        return $unserializedValue !== false ? $unserializedValue : $decryptedValue;
    }

    /**
     * Remove um cookie de sessão
     * 
     * @param string $name Nome do cookie
     * @return bool Retorna true se o cookie foi removido com sucesso
     */
    public static function deleteCookie($name)
    {
        // Valida o nome do cookie
        if (empty($name) || !is_string($name)) {
            return false;
        }

        // Define o cookie com tempo de expiração no passado para removê-lo
        return self::setCookie($name, '', time() - 3600);
    }

    /**
     * Verifica se um cookie existe
     * 
     * @param string $name Nome do cookie
     * @return bool Retorna true se o cookie existe
     */
    public static function hasCookie($name)
    {
        if (empty($name) || !is_string($name)) {
            return false;
        }

        $cookieName = self::COOKIE_PREFIX . $name;
        return isset($_COOKIE[$cookieName]);
    }

    /**
     * Criptografa um valor usando uma chave secreta
     * 
     * @param string $value Valor a ser criptografado
     * @return string Valor criptografado em base64
     */
    private static function encryptValue($value)
    {
        // Obtém a chave de criptografia (deve estar em variável de ambiente)
        $key = self::getEncryptionKey();

        // Gera um vetor de inicialização aleatório
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));

        // Criptografa o valor
        $encrypted = openssl_encrypt($value, 'AES-256-CBC', $key, 0, $iv);

        // Retorna o IV + valor criptografado em base64
        return base64_encode($iv . $encrypted);
    }

    /**
     * Descriptografa um valor criptografado
     * 
     * @param string $encryptedValue Valor criptografado em base64
     * @return string|false Valor descriptografado ou false se falhar
     */
    private static function decryptValue($encryptedValue)
    {
        // Obtém a chave de criptografia
        $key = self::getEncryptionKey();

        // Decodifica o valor de base64
        $data = base64_decode($encryptedValue);

        if ($data === false) {
            return false;
        }

        // Extrai o IV (primeiros 16 bytes)
        $ivLength = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        // Descriptografa o valor
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }

    /**
     * Obtém a chave de criptografia
     * 
     * @return string Chave de criptografia
     */
    private static function getEncryptionKey()
    {
        // IMPORTANTE: Em produção, use uma chave forte armazenada em variável de ambiente
        // Exemplo: return $_ENV['COOKIE_ENCRYPTION_KEY'];

        // Chave de exemplo (NÃO use em produção)
        $key = getenv('COOKIE_ENCRYPTION_KEY');

        if (!$key) {
            // Fallback para chave padrão (ALTERE EM PRODUÇÃO)
            $key = 'sua-chave-secreta-muito-forte-32-caracteres-minimo';
        }

        return hash('sha256', $key);
    }

    /**
     * Limpa todos os cookies do sistema
     * 
     * @return bool Retorna true se todos os cookies foram removidos
     */
    public static function clearAllCookies()
    {
        $success = true;

        // Percorre todos os cookies e remove os que têm nosso prefixo
        foreach ($_COOKIE as $cookieName => $cookieValue) {
            if (strpos($cookieName, self::COOKIE_PREFIX) === 0) {
                $name = substr($cookieName, strlen(self::COOKIE_PREFIX));
                if (!self::deleteCookie($name)) {
                    $success = false;
                }
            }
        }

        return $success;
    }
}

/* Exemplo de uso da classe
try {
    // Definir um cookie de sessão simples
    SessionCookieManager::setCookie('usuario_id', '12345', 3600); // Expira em 1 hora

    // Definir um cookie com array de dados
    $dadosUsuario = [
        'nome' => 'João Silva',
        'email' => 'joao@example.com',
        'permissoes' => ['ler', 'escrever']
    ];
    SessionCookieManager::setCookie('dados_usuario', $dadosUsuario, 7200); // Expira em 2 horas

    // Recuperar valores dos cookies
    $usuarioId = SessionCookieManager::getCookie('usuario_id');
    $dadosUsuario = SessionCookieManager::getCookie('dados_usuario', []);

    // Verificar se cookie existe
    if (SessionCookieManager::hasCookie('usuario_id')) {
        echo "Usuário logado: " . $usuarioId . "\n";
    }

    // Remover um cookie específico
    // SessionCookieManager::deleteCookie('usuario_id');

    // Limpar todos os cookies
    // SessionCookieManager::clearAllCookies();

} catch (Exception $e) {
    echo "Erro ao gerenciar cookies: " . $e->getMessage();
}

*/

/**
 * CONFIGURAÇÕES IMPORTANTES PARA PRODUÇÃO:
 * 
 * 1. Defina uma chave de criptografia forte em variável de ambiente:
 *    export COOKIE_ENCRYPTION_KEY="sua-chave-super-secreta-de-32-caracteres-ou-mais"
 * 
 * 2. Configure o php.ini para maior segurança:
 *    session.cookie_httponly = 1
 *    session.cookie_secure = 1 (se usar HTTPS)
 *    session.cookie_samesite = "Strict"
 * 
 * 3. Use HTTPS sempre que possível
 * 
 * 4. Considere implementar rotação de chaves para maior segurança
 * 
 * 5. Monitore logs para tentativas de acesso malicioso
 */
