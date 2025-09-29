<?php

/**
 * @package   astec
 * @name      c_ipm_estrategy
 * @version   4.5.0
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy Dos Santos Mello <jhon.kened11@hotmail.com>
 * @date      03/06/2025
 */

$dir = (__DIR__);

include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_cookie_manager.php");
include_once($dir . "/../../bib/c_database_pdo.php");
include_once($dir . "/c_ipm_estrategy_xml.php");

class IpmStrategy extends c_user
{
    private $schemaPath = NULL;
    private $config     = NULL;
    private $prestador  = NULL;


    public function __construct(...$params)
    {
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);
        //$this->schemaPath = __DIR__ . '/Schemas/ipm_v1.json';
    }

    public function processForShipping(array $config, int $id, string $origem_dados, string $json = ''): string
    {
        $jsonNFS = null;

        switch ($origem_dados) {
            case 'pedido_servico':
                $jsonNFS = $this->montaJsonPedidoServico($id);
                break;  
            case 'ordem_servico':
                $jsonNFS = $this->montaJsonOrdemServico($id);
                break;
            case 'manual':
                $jsonNFS = $json; 
                break;
            default:
                throw new \Exception("Origem de dados não suportada: $origem_dados");
        }


        $object = new IpmStrategyXml();
        $xml = $object->mountXmlIpm($jsonNFS);
        // Lógica específica de envio para IPM

        $this->config = array(
            "url" => "https://pinhais.atende.net/?pg=rest&service=WNERestServiceNFSe",
            "user" => "26179567000160",
            "password" => "Evi#7285",
            "cookie_session" => null
        );

        $teste = $this->sendForWebServiceIpm($xml, $config);

        return $teste;
    }


    

    /**
     * Monta o JSON da NFS-e a partir dos dados de um pedido de serviço (FAT_PEDIDO_ITEM)
     * @param int $id FAT_PEDIDO_ID
     * @return string JSON pronto para ser usado em mountXmlIpm
     */
    public function montaJsonPedidoServico(int $id): string
    {

        try {

            // Busca infos do pedido
            $pedido = $this->selectPedido($id);

            if($pedido == '' or $pedido == null){
                throw new \Exception("Nenhum pedido localizado");
            }

            // ---------------- MONTA DADOS DO TOMADOR ----------------

            $tomador_bd = $this->selectTomador($pedido['CLIENTE']);

            if($tomador_bd == '' or $tomador_bd == null){
                throw new \Exception("Nenhum servico localizado");
            }
           
            // Monta dados do tomador
            $tomador_dados = array_filter([
                
                'endereco_informado' => $this->validarCampo($tomador_bd['ENDERECO_INFORMADO'] ?? null, 1),
                'tipo' => $this->validarCampo($tomador_bd['TIPOPESSOA'] ?? null, 1),
                // Número do cartão de identificação estrangeira ou passaporte.
                //'identifcador' => $this->validarCampo($tomador_bd['IDENTIFICADOR'] ?? null, 20), 
                'pais' => $this->validarCampo($tomador_bd['PAIS'] ?? null, 100),
                'cpfcnpj' => $this->validarCampo($tomador_bd['CPF'] ?? null, 14),
                'ie' => $this->validarCampo($tomador_bd['IE'] ?? null, 16),
                'nome_razao_social' => $this->validarCampo($tomador_bd['RAZAO_SOCIAL'] ?? null, 100),
                'sobrenome_nome_fantasia' => $this->validarCampo($tomador_bd['NOME_FANTASIA'] ?? null, 100),
                'logradouro' => $this->validarCampo($tomador_bd['LOGRADOURO'] ?? null, 70),
                'email' => $this->validarCampo($tomador_bd['EMAIL'] ?? null , 100),
                'numero_residencia' => $this->validarCampo($tomador_bd['NUMERO_RESIDENCIA'] ?? null, 8),
                'complemento' => $this->validarCampo($tomador_bd['COMPLEMENTO'] ?? null, 50),
                'ponto_referencia' => $this->validarCampo($tomador_bd['PONTO_REFERENCIA'] ?? null, 100),
                'bairro' => $this->validarCampo($tomador_bd['BAIRRO'] ?? null, 30),
                'cidade' => $this->validarCampo($tomador_bd['CIDADE'] ?? null, 9),
                'cep' => $this->validarCampo($tomador_bd['CEP'] ?? null, 8),
                'ddd_fone_comercial' => $this->validarCampo($tomador_bd['DDD_FONE_COMERCIAL'] ?? null, 3),
                'fone_comercial' => $this->validarCampo($tomador_bd['FONE_COMERCIAL'] ?? null, 9),
                'ddd_fone_residencial' => $this->validarCampo($tomador_bd['DDD_fone'] ?? null, 3),
                'fone_residencial' => $this->validarCampo($tomador_bd['FONE_RESIDENCIAL'] ?? null, 9),
                'ddd_fax' => $this->validarCampo($tomador_bd['DDD_FAX'] ?? null, 3),
                'fone_fax' => $this->validarCampo($tomador_bd['FONE_FAX'] ?? null, 9)

            ], function($value) {

                return $value !== null && $value !== '';

            });

            // Adicionar estado apenas se o tipo for 'E' (Estrangeiro)
            if ($tomador_bd['TIPOPESSOA'] === 'E' && isset($tomador_bd['ESTADO']) && !empty($tomador_bd['ESTADO'])) {
                $tomador_dados['estado'] = $this->validarCampo($tomador_bd['ESTADO'], 100);
            }
            // ---------------- FIM MONTA DADOS DO TOMADOR ----------------



            // ---------------- MONTA DADOS DO PRESTADOR ----------------
            $prestador_bd = $this->selectPrestador();

            // Monta dados do prestador
            $prestador_dados = [
                'cpfcnpj' => $this->validarCampo($prestador_bd['CNPJ'], 14), // Obrigatorio
                'cidade' => $this->validarCampo($prestador_bd["CODMUNICIPIO"], 9), // Obrigatorio
            ];
            // ---------------- FIM MONTA DADOS DO PRESTADOR ----------------



            // ---------------- MONTA DADOS DOS SERVICOS ----------------
            $servicos_bd = $this->selectPedidoServico($pedido['PEDIDO']); 

            if($servicos_bd == '' or $servicos_bd == null){
                throw new \Exception("Nenhum servico localizado");
            }

            $valorTotal = 0;
            foreach ($servicos_bd as $item) {

                $valor_tributavel = (float)($item['TOTALSERVICO'] ?? 0);

                $valorTotal += $valor_tributavel;

                $dadosNFS['itens'][] = array_filter([
                    'tributa_municipio_prestador' => $this->validarCampo($item['TRIBUTAMUNICIPIO'] ?? null, 1), // Obrigatorio
                    'codigo_local_prestacao_servico' => $this->validarCampo($item['CODLOCALPRESTACAO'] ?? null, 9), // Obrigatorio
                    //'unidade_codigo' => $this->validarCampo($item['CODIGO_UNIDADE'] ?? null, 9),
                    //'unidade_quantidade' => $this->validarCampo($item['QUANTIDADE_UNIDADE'] ?? null, 15),
                    //'unidade__valor_unitario' => $this->validarCampo($item['CODIGO_UNIDADE'] ?? null, 15),
                    'codigo_item_lista_servico' => $this->validarCampo($item['CODITEMLISTASERVICO'] ?? null, 9), // Obrigatorio
                    //'codigo_atividade' => $this->validarCampo($item['CODATIVIDADE'] ?? null, 9),
                    'descritivo' => $this->validarCampo($item['DESCSERVICO'] ?? null, 1000), // Obrigatorio
                    'aliquota_item_lista_servico' => $this->validarCampo($item['ALIQUOTA'] ?? null, 15), // Obrigatorio
                    'situacao_tributaria' => $this->validarCampo($item['SITUACAOTRIBUTARIA'] ?? null, 4), // Obrigatorio
                    'valor_tributavel' => $this->validarCampo($valor_tributavel, 15), // Obrigatorio
                    'valor_deducao' => $this->validarCampo($item['VALOR_DEDUCAO'] ?? null, 15),
                    'valor_issr' => $this->validarCampo($item['VALOR_ISSR'] ?? null, 15),
                    'cno' => $this->validarCampo($item['CNO'] ?? 0, 10),
                    
                ], function($value) {

                    return $value !== null && $value !== '';

                });

            }
            // ---------------- FIM MONTA DADOS DOS SERVICOS ----------------


            // ---------------- MONTA DADOS DA NOTA ----------------
            $dadosNFS = [
                'prestador' => $prestador_dados,
                'tomador' => $tomador_dados,
                'itens' => [],
            ];

            $dadosNFS['valor_total'] = $valorTotal;


            return json_encode($dadosNFS, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

        } catch (\Exception $e) {

            // DEV verifique o erro nesse caminho /var/log/apache2/error.log (sudo tail -f /var/log/apache2/error.log)
            $this->log_personalizado("Erro ao montar o JSON do pedido de servico: " . $e->getMessage() ."-". $e->getCode());

            throw new \Exception("Erro ao montar o JSON do pedido de serviço: " . $e->getMessage());
        }
    }

    /**
     * Monta o JSON da NFS-e a partir dos dados de uma ordem de serviço (CAT_ATENDIMENTO)
     * @param int $id CAT_ATENDIMENTO ID
     * @return string JSON pronto para ser usado em mountXmlIpm
     */
    public function montaJsonOrdemServico(int $id): string
    {
        try {
            // Busca infos do atendimento
            $atendimento = $this->selectAtendimento($id);

            if($atendimento == '' or $atendimento == null){
                throw new \Exception("Nenhum atendimento localizado");
            }

            // ---------------- MONTA DADOS DO TOMADOR ----------------
            $tomador_bd = $this->selectTomador($atendimento['CLIENTE']);

            if($tomador_bd == '' or $tomador_bd == null){
                throw new \Exception("Nenhum tomador localizado");
            }
           
            // Monta dados do tomador
            $tomador_dados = array_filter([
                'endereco_informado' => $this->validarCampo($tomador_bd['ENDERECO_INFORMADO'] ?? null, 1),
                'tipo' => $this->validarCampo($tomador_bd['TIPOPESSOA'] ?? null, 1),
                'pais' => $this->validarCampo($tomador_bd['PAIS'] ?? null, 100),
                'cpfcnpj' => $this->validarCampo($tomador_bd['CPF'] ?? null, 14),
                'ie' => $this->validarCampo($tomador_bd['IE'] ?? null, 16),
                'nome_razao_social' => $this->validarCampo($tomador_bd['RAZAO_SOCIAL'] ?? null, 100),
                'sobrenome_nome_fantasia' => $this->validarCampo($tomador_bd['NOME_FANTASIA'] ?? null, 100),
                'logradouro' => $this->validarCampo($tomador_bd['LOGRADOURO'] ?? null, 70),
                'email' => $this->validarCampo($tomador_bd['EMAIL'] ?? null , 100),
                'numero_residencia' => $this->validarCampo($tomador_bd['NUMERO_RESIDENCIA'] ?? null, 8),
                'complemento' => $this->validarCampo($tomador_bd['COMPLEMENTO'] ?? null, 50),
                'ponto_referencia' => $this->validarCampo($tomador_bd['PONTO_REFERENCIA'] ?? null, 100),
                'bairro' => $this->validarCampo($tomador_bd['BAIRRO'] ?? null, 30),
                'cidade' => $this->validarCampo($tomador_bd['CIDADE'] ?? null, 9),
                'cep' => $this->validarCampo($tomador_bd['CEP'] ?? null, 8),
                'ddd_fone_comercial' => $this->validarCampo($tomador_bd['DDD_FONE_COMERCIAL'] ?? null, 3),
                'fone_comercial' => $this->validarCampo($tomador_bd['FONE_COMERCIAL'] ?? null, 9),
                'ddd_fone_residencial' => $this->validarCampo($tomador_bd['DDD_fone'] ?? null, 3),
                'fone_residencial' => $this->validarCampo($tomador_bd['FONE_RESIDENCIAL'] ?? null, 9),
                'ddd_fax' => $this->validarCampo($tomador_bd['DDD_FAX'] ?? null, 3),
                'fone_fax' => $this->validarCampo($tomador_bd['FONE_FAX'] ?? null, 9)
            ], function($value) {
                return $value !== null && $value !== '';
            });

            // Adicionar estado apenas se o tipo for 'E' (Estrangeiro)
            if ($tomador_bd['TIPOPESSOA'] === 'E' && isset($tomador_bd['ESTADO']) && !empty($tomador_bd['ESTADO'])) {
                $tomador_dados['estado'] = $this->validarCampo($tomador_bd['ESTADO'], 100);
            }
            // ---------------- FIM MONTA DADOS DO TOMADOR ----------------

            // ---------------- MONTA DADOS DO PRESTADOR ----------------
            $prestador_bd = $this->selectPrestador();

            // Monta dados do prestador
            $prestador_dados = [
                'cpfcnpj' => $this->validarCampo($prestador_bd['CNPJ'], 14), // Obrigatorio
                'cidade' => $this->validarCampo($prestador_bd["CODMUNICIPIO"], 9), // Obrigatorio
            ];
            // ---------------- FIM MONTA DADOS DO PRESTADOR ----------------

            // ---------------- MONTA DADOS DOS SERVICOS ----------------
            $servicos_bd = $this->selectAtendimentoServico($id); 

            if($servicos_bd == '' or $servicos_bd == null){
                throw new \Exception("Nenhum servico localizado");
            }

            $valorTotal = 0;
            foreach ($servicos_bd as $item) {
                $valor_tributavel = (float)($item['TOTALSERVICO'] ?? 0);
                $valorTotal += $valor_tributavel;

                $dadosNFS['itens'][] = array_filter([
                    'tributa_municipio_prestador' => $this->validarCampo($item['TRIBUTAMUNICIPIO'] ?? null, 1), // Obrigatorio
                    'codigo_local_prestacao_servico' => $this->validarCampo($item['CODLOCALPRESTACAO'] ?? null, 9), // Obrigatorio
                    'codigo_item_lista_servico' => $this->validarCampo($item['CODITEMLISTASERVICO'] ?? null, 9), // Obrigatorio
                    'descritivo' => $this->validarCampo($item['DESCSERVICO'] ?? null, 1000), // Obrigatorio
                    'aliquota_item_lista_servico' => $this->validarCampo($item['ALIQUOTA'] ?? null, 15), // Obrigatorio
                    'situacao_tributaria' => $this->validarCampo($item['SITUACAOTRIBUTARIA'] ?? null, 4), // Obrigatorio
                    'valor_tributavel' => $this->validarCampo($valor_tributavel, 15), // Obrigatorio
                    'valor_deducao' => $this->validarCampo($item['VALOR_DEDUCAO'] ?? null, 15),
                    'valor_issr' => $this->validarCampo($item['VALOR_ISSR'] ?? null, 15),
                ], function($value) {
                    return $value !== null && $value !== '';
                });
            }
            // ---------------- FIM MONTA DADOS DOS SERVICOS ----------------

            // ---------------- MONTA DADOS DA NOTA ----------------
            $dadosNFS = [
                'prestador' => $prestador_dados,
                'tomador' => $tomador_dados,
                'itens' => [],
            ];

            $dadosNFS['valor_total'] = $valorTotal;

            return json_encode($dadosNFS, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

        } catch (\Exception $e) {
            // DEV verifique o erro nesse caminho /var/log/apache2/error.log (sudo tail -f /var/log/apache2/error.log)
            $this->log_personalizado("Erro ao montar o JSON da ordem de servico: " . $e->getMessage() ."-". $e->getCode());

            throw new \Exception("Erro ao montar o JSON da ordem de serviço: " . $e->getMessage());
        }
    }



    public function selectPedido(int $id): array
    {
        $sql = "SELECT * FROM FAT_PEDIDO WHERE ID = :id";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetch();  
    }


    /**
    * Seleciona todos os registros de serviço associados a um pedido específico.
    * @param int $id O identificador único do pedido de serviço (FAT_PEDIDO_ID).
    * @return array Um array de arrays associativos, onde cada array interno representa um item de serviço.
    * Retorna um array vazio se o pedido não tiver itens ou não existir.
    * @throws \PDOException Lançada se houver um erro de conexão ou sintaxe na consulta SQL.
    */
    public function selectPedidoServico (int $id): array
    {   
        $sql = "SELECT * FROM FAT_PEDIDO_SERVICO WHERE FAT_PEDIDO_ID = :id";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetchAll();
    }

    public function selectPrestador(): array
    {
        $sql = "SELECT * FROM AMB_EMPRESA WHERE EMPRESA = :id";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':id', $this->m_empresaid, PDO::PARAM_INT);
        $banco->execute();
   
        return $banco->fetch();
    }

    public function selectTomador(int $id): array
    {
        $sql = "SELECT * FROM FIN_CLIENTE WHERE CLIENTE = :id";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetch();
    }

    /**
     * Seleciona um atendimento específico pelo ID.
     * @param int $id O identificador único do atendimento (CAT_ATENDIMENTO ID).
     * @return array Um array associativo com os dados do atendimento.
     * @throws \PDOException Lançada se houver um erro de conexão ou sintaxe na consulta SQL.
     */
    public function selectAtendimento(int $id): array
    {
        $sql = "SELECT * FROM CAT_ATENDIMENTO WHERE ID = :id";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetch();
    }

    /**
     * Seleciona todos os registros de serviço associados a um atendimento específico.
     * @param int $id O identificador único do atendimento (CAT_ATENDIMENTO ID).
     * @return array Um array de arrays associativos, onde cada array interno representa um item de serviço.
     * Retorna um array vazio se o atendimento não tiver itens ou não existir.
     * @throws \PDOException Lançada se houver um erro de conexão ou sintaxe na consulta SQL.
     */
    public function selectAtendimentoServico(int $id): array
    {   
        $sql = "SELECT * FROM CAT_AT_SERVICOS WHERE CAT_ATENDIMENTO_ID = :id";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetch();
    }


     /**
     * Envia o XML da NFS-e para o Web Service da IPM.
     *
     * Esta função implementa a lógica de comunicação via cURL conforme especificado
     * no manual de integração (Nota Técnica nº 35/2021).
     *
     * @param string $xmlContent O conteúdo XML a ser enviado.
     * @param array $config As credenciais e URL para o envio. Deve conter: 'url', 'usuario', 'senha' e opcionalmente 'cookie_session'.
     * @return string A resposta do webservice.
     * @throws \Exception Se a comunicação cURL falhar ou ocorrer qualquer erro no processamento.
     */
    private function sendForWebServiceIpm(string $xmlContent, array &$config): string
    {
        try {
            // 1. Definição dos Parâmetros da Requisição
            $url = $this->config['url'];
            $username = $this->config['user']; // CPF/CNPJ do emissor 
            $password = $this->config['password']; // Senha de acesso ao sistema 

            // Validação básica dos parâmetros obrigatórios
            if (empty($url) || empty($username) || empty($password)) {
                throw new \Exception("Parâmetros obrigatórios não informados: url, user ou password");
            }

            // Codificação das credenciais em Base64 para o cabeçalho de autorização 
            $authorization = 'Authorization: Basic ' . base64_encode($username . ':' . $password);

            // 2. Preparação do arquivo XML para envio via POST (multipart/form-data)
            $postFields = [
                'arquivo' => new \CURLFile('data://text/xml;base64,' . base64_encode($xmlContent), 'nfse.xml', 'text/xml')
            ];

            // 3. Montagem dos Cabeçalhos HTTP
            $headers = [
                $authorization,
                'Content-Type: multipart/form-data' // Tipo de conteúdo exigido pelo webservice 
            ];

            // O manual recomenda o reuso da sessão para performance.
            // Se um cookie de sessão já existir, ele é adicionado ao cabeçalho.
            if (SessionCookieManager::hasCookie('cookie_session')) {
                $cookieValue = SessionCookieManager::getCookie('cookie_session');
                $headers[] = 'Cookie: ' . $cookieValue;
            }

            // 4. Inicialização e Configuração do cURL, baseado no exemplo do manual (Figura 3) 
            $ch = curl_init();

            if ($ch === false) {
                throw new \Exception("Falha ao inicializar cURL");
            }

            $curlOptions = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true, // Retorna a resposta como string 
                CURLOPT_POST => true,           // Método da requisição é POST 
                CURLOPT_POSTFIELDS => $postFields,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_HEADER => true,           // Inclui os cabeçalhos na resposta para extrair o cookie 
                CURLOPT_SSL_VERIFYPEER => false,  // Conforme exemplo do manual, mas não recomendado em produção 
                CURLOPT_CUSTOMREQUEST => 'POST',  // Garante o método POST
                CURLOPT_TIMEOUT => 30,            // Timeout de 30 segundos
                CURLOPT_CONNECTTIMEOUT => 10      // Timeout de conexão de 10 segundos
            ];

            if (!curl_setopt_array($ch, $curlOptions)) {
                curl_close($ch);
                throw new \Exception("Falha ao configurar opções do cURL");
            }

            // 5. Execução da Requisição e Tratamento da Resposta
            $response = curl_exec($ch);

            if ($response === false) {
                $error_msg = curl_error($ch);
                curl_close($ch);
                throw new \Exception("Erro na comunicação cURL: " . $error_msg);
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

            curl_close($ch);

            // Verifica se o código HTTP indica sucesso
            if ($httpCode < 200 || $httpCode >= 300) {
                throw new \Exception("Erro HTTP: Código {$httpCode} retornado pelo webservice");
            }

            
            //Atualizar com os dados reais da nota fiscal
            $dados = array(
                "id_nfs" => 123,
                "centro_custo" => "10000000",
                "serie" => 1,
                "numero" => 22,
                "tipo_evento" => "E",
                "codigo_retorno" => "204"
            );


            $responseBody = substr($response, $headerSize);
            $this->saveEventInvoice($dados, $responseBody);

            // 6. Extração e Armazenamento do Cookie de Sessão para requisições futuras
            $responseHeader = substr($response, 0, $headerSize);
            $this->saveSessionCookieFromHeader($responseHeader);

            // Retorna apenas o corpo da resposta (o XML de retorno) 
            return $responseBody;
        } catch (\Exception $e) {

            // Lanca o log
            $this->log_personalizado("Erro ao comunicar com webservice IPM: " . $e->getMessage() ."-". $e->getCode());

            // Re-lanca a excecao com contexto adicional se necessario
            throw new \Exception("Erro ao comunicar com webservice IPM: " . $e->getMessage(), $e->getCode(), $e);
        }
    }



    /**
     * Extrai e salva cookies de sessão do cabeçalho de resposta HTTP.
     *
     * Esta função busca cookies do tipo "Set-Cookie" dentro do cabeçalho de resposta,
     * junta-os em uma string única, armazena em sessão com tempo de expiração
     * e também os disponibiliza para uso imediato na configuração local.
     *
     * @param string $responseHeader Cabeçalho HTTP da resposta, contendo cookies (se houver).
     * @return bool Retorna true se o cookie foi salvo com sucesso.
     * @throws \Exception Se não for possível localizar ou salvar o cookie.
     */
    public function saveSessionCookieFromHeader(string $responseHeader): bool 
    {
        try {
            // O manual indica que após a primeira requisição, um cookie de sessão (PHPSESSID) pode ser retornado.
            //preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $responseHeader, $matches);
            preg_match_all('/^Set-Cookie:\s*(.+)$/mi', $responseHeader, $matches);

            if (!empty($matches[1])) {
                // Junta todos os cookies capturados em uma string
                $cookieString = implode(';', $matches[1]);

                // Salva o cookie de sessão com expiração de 1 hora
                SessionCookieManager::setCookie('cookie_session', $cookieString, 3600);

                // Também salva na configuração local para uso imediato
                $this->config['cookie_session'] = $cookieString;
                return true;

            } else {

                // Lanca o log
                $this->log_personalizado("Erro ao desmontar o responseHeader da funcao saveSessionCookieFromHeader():");

                return false;
            }

        } catch (\Exception $e) {

            // DEV verifique o erro nesse caminho /var/log/apache2/error.log (sudo tail -f /var/log/apache2/error.log)
            $this->log_personalizado("Erro ao salvar cookie: " . $e->getMessage() ."-". $e->getCode());
            return false;
        }
    }


    function log_personalizado($mensagem) {
        $data = date('Y-m-d H:i:s');
        $arquivo = $_SERVER['PHP_SELF'] ?? 'CLI';
        $url = $_SERVER['REQUEST_URI'] ?? 'N/A';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    
        $logFormatado = "================= [ERRO NA APLICACAO] - $data =================\n";
        $logFormatado .= "[Arquivo] $arquivo\n";
        $logFormatado .= "[URL] $url\n";
        $logFormatado .= "[IP] $ip\n";
        $logFormatado .= "[MENSAGEM] $mensagem\n";
        $logFormatado .= "================================= FIM DO ERRO =================================\n\n";
        
        // caminho default /var/log/apache2/error.log
        error_log($logFormatado);
    }

    /**
     * Função simples para validar e truncar campo por tamanho
     * @param mixed $value - Valor do campo
     * @param int $maxLength - Tamanho máximo permitido
     * @return string|null - Valor truncado ou null se vazio
     */
    function validarCampo($value, $maxLength) {
        if ($value === null || $value === '') {
            return null;
        }
        
        $value = (string) $value;
        return strlen($value) > $maxLength ? substr($value, 0, $maxLength) : $value;
    }
}
