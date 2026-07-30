<?php

/**
 * Classe para gerenciar sessões PHP com segurança
 * ADM v4.5 - Módulo UTIL
 */
class SessionManager
{
    // Configurações padrão de segurança
    private const DEFAULT_EXPIRE_TIME = 3600; // 1 hora em segundos
    private const SESSION_PREFIX = 'adm_';
    private const ENCRYPT_DATA = false; // Define se os dados devem ser criptografados

    /**
     * Inicializa a sessão se ainda não estiver iniciada
     * 
     * @return void
     */
    private static function initSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Configurações de segurança da sessão
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Strict');
            
            session_start();
            
            // Inicializa array de controle de tempo se não existir
            if (!isset($_SESSION['__session_times__'])) {
                $_SESSION['__session_times__'] = [];
            }
        }
    }

    /**
     * Define um valor na sessão com configurações de segurança
     * 
     * @param string $name Nome da variável de sessão
     * @param mixed $value Valor a ser armazenado (aceita qualquer tipo)
     * @param int $expireTime Tempo de expiração em segundos (0 para sem expiração)
     * @return bool Retorna true se a sessão foi definida com sucesso
     */
    public static function set($name, $value, $expireTime = 0)
    {
        // Valida se o nome é válido
        if (empty($name) || !is_string($name)) {
            throw new InvalidArgumentException('Nome da sessão deve ser uma string não vazia');
        }

        // Inicializa a sessão
        self::initSession();

        // Adiciona prefixo ao nome da variável
        $sessionName = self::SESSION_PREFIX . $name;

        // Criptografa o valor se a opção estiver ativada
        if (self::ENCRYPT_DATA) {
            $value = self::encryptValue(serialize($value));
        }

        // Armazena o valor na sessão
        $_SESSION[$sessionName] = $value;

        // Define o tempo de expiração se especificado
        if ($expireTime > 0) {
            $_SESSION['__session_times__'][$sessionName] = time() + $expireTime;
        } else {
            // Remove o controle de tempo se não houver expiração
            unset($_SESSION['__session_times__'][$sessionName]);
        }

        return true;
    }

    /**
     * Obtém o valor de uma variável de sessão
     * 
     * @param string $name Nome da variável de sessão
     * @param mixed $default Valor padrão se a variável não existir
     * @return mixed Valor da sessão ou valor padrão
     */
    public static function get($name, $default = null)
    {
        // Valida o nome
        if (empty($name) || !is_string($name)) {
            return $default;
        }

        // Inicializa a sessão
        self::initSession();

        // Adiciona o prefixo ao nome
        $sessionName = self::SESSION_PREFIX . $name;

        // Verifica se a variável existe na sessão
        if (!isset($_SESSION[$sessionName])) {
            return $default;
        }

        // Verifica se a sessão expirou
        if (isset($_SESSION['__session_times__'][$sessionName])) {
            if (time() > $_SESSION['__session_times__'][$sessionName]) {
                // Sessão expirada, remove e retorna valor padrão
                self::delete($name);
                return $default;
            }
        }

        $value = $_SESSION[$sessionName];

        // Descriptografa o valor se estiver criptografado
        if (self::ENCRYPT_DATA) {
            $decryptedValue = self::decryptValue($value);
            if ($decryptedValue === false) {
                return $default;
            }
            $value = @unserialize($decryptedValue);
            if ($value === false) {
                return $default;
            }
        }

        return $value;
    }

    /**
     * Remove uma variável de sessão
     * 
     * @param string $name Nome da variável de sessão
     * @return bool Retorna true se a variável foi removida com sucesso
     */
    public static function delete($name)
    {
        // Valida o nome
        if (empty($name) || !is_string($name)) {
            return false;
        }

        // Inicializa a sessão
        self::initSession();

        // Adiciona o prefixo ao nome
        $sessionName = self::SESSION_PREFIX . $name;

        // Remove a variável da sessão
        if (isset($_SESSION[$sessionName])) {
            unset($_SESSION[$sessionName]);
        }

        // Remove o controle de tempo
        if (isset($_SESSION['__session_times__'][$sessionName])) {
            unset($_SESSION['__session_times__'][$sessionName]);
        }

        return true;
    }

    /**
     * Verifica se uma variável de sessão existe e não expirou
     * 
     * @param string $name Nome da variável de sessão
     * @return bool Retorna true se a variável existe e é válida
     */
    public static function has($name)
    {
        if (empty($name) || !is_string($name)) {
            return false;
        }

        // Inicializa a sessão
        self::initSession();

        $sessionName = self::SESSION_PREFIX . $name;

        // Verifica se existe
        if (!isset($_SESSION[$sessionName])) {
            return false;
        }

        // Verifica se expirou
        if (isset($_SESSION['__session_times__'][$sessionName])) {
            if (time() > $_SESSION['__session_times__'][$sessionName]) {
                self::delete($name);
                return false;
            }
        }

        return true;
    }

    /**
     * Limpa todas as variáveis de sessão do sistema ADM
     * 
     * @param bool $clearAll Se true, limpa toda a sessão. Se false, apenas as variáveis com prefixo
     * @return bool Retorna true se as variáveis foram removidas
     */
    public static function clear($clearAll = false)
    {
        self::initSession();

        if ($clearAll) {
            // Limpa toda a sessão
            session_unset();
            session_destroy();
            return true;
        }

        // Remove apenas as variáveis com nosso prefixo
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, self::SESSION_PREFIX) === 0) {
                $name = substr($key, strlen(self::SESSION_PREFIX));
                self::delete($name);
            }
        }

        return true;
    }

    /**
     * Obtém o ID da sessão atual
     * 
     * @return string ID da sessão
     */
    public static function getId()
    {
        self::initSession();
        return session_id();
    }

    /**
     * Regenera o ID da sessão (útil após login para prevenir session fixation)
     * 
     * @param bool $deleteOldSession Se true, exclui a sessão antiga
     * @return bool Retorna true se o ID foi regenerado com sucesso
     */
    public static function regenerateId($deleteOldSession = true)
    {
        self::initSession();
        return session_regenerate_id($deleteOldSession);
    }

    /**
     * Obtém todas as variáveis de sessão do sistema ADM
     * 
     * @return array Array com todas as variáveis de sessão
     */
    public static function getAll()
    {
        self::initSession();
        
        $data = [];
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, self::SESSION_PREFIX) === 0) {
                $name = substr($key, strlen(self::SESSION_PREFIX));
                $data[$name] = self::get($name);
            }
        }
        
        return $data;
    }

    /**
     * Define múltiplas variáveis de sessão de uma vez
     * 
     * @param array $data Array associativo com nome => valor
     * @param int $expireTime Tempo de expiração em segundos (0 para sem expiração)
     * @return bool Retorna true se todas as variáveis foram definidas
     */
    public static function setMultiple($data, $expireTime = 0)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Dados devem ser um array associativo');
        }

        foreach ($data as $name => $value) {
            self::set($name, $value, $expireTime);
        }

        return true;
    }

    /**
     * Criptografa um valor usando uma chave secreta
     * 
     * @param string $value Valor a ser criptografado
     * @return string Valor criptografado em base64
     */
    private static function encryptValue($value)
    {
        // Obtém a chave de criptografia
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
        // Tenta obter da variável de ambiente
        $key = getenv('SESSION_ENCRYPTION_KEY');

        if (!$key) {
            // Fallback para chave padrão (ALTERE EM PRODUÇÃO)
            $key = 'sua-chave-secreta-muito-forte-32-caracteres-minimo';
        }

        return hash('sha256', $key);
    }

    /**
     * Limpa sessões expiradas (útil para limpeza periódica)
     * 
     * @return int Quantidade de sessões limpas
     */
    public static function cleanExpired()
    {
        self::initSession();
        
        $count = 0;
        if (isset($_SESSION['__session_times__'])) {
            $currentTime = time();
            foreach ($_SESSION['__session_times__'] as $sessionName => $expireTime) {
                if ($currentTime > $expireTime) {
                    $name = substr($sessionName, strlen(self::SESSION_PREFIX));
                    self::delete($name);
                    $count++;
                }
            }
        }
        
        return $count;
    }
}

/* Exemplo de uso da classe SessionManager
try {
    // Definir uma variável de sessão simples
    SessionManager::set('usuario_id', '12345', 3600); // Expira em 1 hora

    // Definir uma variável com array de dados
    $dadosUsuario = [
        'nome' => 'João Silva',
        'email' => 'joao@example.com',
        'permissoes' => ['ler', 'escrever']
    ];
    SessionManager::set('dados_usuario', $dadosUsuario, 7200); // Expira em 2 horas

    // Definir múltiplas variáveis de uma vez
    SessionManager::setMultiple([
        'empresa_id' => 1,
        'nivel_acesso' => 'admin',
        'ultimo_acesso' => date('Y-m-d H:i:s')
    ], 3600);

    // Recuperar valores da sessão
    $usuarioId = SessionManager::get('usuario_id');
    $dadosUsuario = SessionManager::get('dados_usuario', []);

    // Verificar se variável existe
    if (SessionManager::has('usuario_id')) {
        echo "Usuário logado: " . $usuarioId . "\n";
    }

    // Obter todas as variáveis de sessão
    $todasVariaveis = SessionManager::getAll();
    print_r($todasVariaveis);

    // Regenerar ID da sessão (após login, por exemplo)
    SessionManager::regenerateId();

    // Obter ID da sessão
    $sessionId = SessionManager::getId();
    echo "Session ID: " . $sessionId . "\n";

    // Limpar sessões expiradas
    $limpas = SessionManager::cleanExpired();
    echo "Sessões expiradas limpas: " . $limpas . "\n";

    // Remover uma variável específica
    // SessionManager::delete('usuario_id');

    // Limpar apenas variáveis do ADM
    // SessionManager::clear(false);

    // Limpar toda a sessão
    // SessionManager::clear(true);

} catch (Exception $e) {
    echo "Erro ao gerenciar sessão: " . $e->getMessage();
}

*/

/**
 * CONFIGURAÇÕES IMPORTANTES PARA PRODUÇÃO:
 * 
 * 1. Configure o php.ini para maior segurança:
 *    session.cookie_httponly = 1
 *    session.cookie_secure = 1 (se usar HTTPS)
 *    session.cookie_samesite = "Strict"
 *    session.use_strict_mode = 1
 *    session.use_only_cookies = 1
 *    session.gc_maxlifetime = 3600
 * 
 * 2. Se ativar criptografia (ENCRYPT_DATA = true), defina uma chave forte:
 *    export SESSION_ENCRYPTION_KEY="sua-chave-super-secreta-de-32-caracteres-ou-mais"
 * 
 * 3. Use HTTPS sempre que possível
 * 
 * 4. Regenere o ID da sessão após login para prevenir session fixation
 * 
 * 5. Configure o garbage collector de sessões adequadamente
 * 
 * 6. Considere usar session.save_path em local seguro
 * 
 * 7. Implemente timeout de inatividade
 * 
 * 8. Execute cleanExpired() periodicamente para limpar sessões expiradas
 * 
 * EXEMPLO DE USO NO ADM v4.5:
 * 
 * // No login do usuário
 * SessionManager::regenerateId();
 * SessionManager::set('user_id', $userId, 3600);
 * SessionManager::set('user_data', $userData, 3600);
 * 
 * // Em qualquer página protegida
 * if (!SessionManager::has('user_id')) {
 *     header('Location: login.php');
 *     exit;
 * }
 * 
 * // No logout
 * SessionManager::clear(true);
 */

