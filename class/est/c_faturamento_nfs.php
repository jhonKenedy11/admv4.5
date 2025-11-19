<?php
/**
 * @package   admv4.5
 * @name      c_faturamento_nfs
 * @version   1.0.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy dos Santos <jhonkenedy@gmail.com>
 * @date      06/08/2025
 * @description Classe para gestão de faturas e notas fiscais de serviços (módulo EST)
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_database_pdo.php");

    Class c_faturamento_nfs extends c_user {
    /**
     * TABLE NAME CAT_Fatura_NFS
     */    
        
    // Campos tabela
    private $id         	= NULL; // INT(11)
    private $descricao  	= NULL; // VARCHAR(60)
    private $created_user  	= NULL; // INT(11)
    private $update_user  	= NULL; // INT(11)
    private $created_at	    = NULL; // TIMESTAMP
    private $update_at     	= NULL; //TIMESTAMP

    //construtor
    function __construct(){
        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

    }

    /**
     * @name buscaOrdemServico
     * @description Busca ordens de serviço (OS) na tabela CAT_ATENDIMENTO com filtros opcionais
     * @param int|null $id ID do atendimento para busca específica
     * @param string|null $dataInicial Data inicial (YYYY-MM-DD) para filtrar por DATAABERATEND
     * @param string|null $dataFinal Data final (YYYY-MM-DD) para filtrar por DATAABERATEND
     * @param int|null $clienteId ID do cliente para filtrar atendimentos
     * @param int|string|null $numAtendimento Número do atendimento para busca específica
     * @return array Array com os registros encontrados ordenados por data de abertura (mais recente primeiro)
     */
    public function buscaOrdemServico($id = null, $dataInicial = null, $dataFinal = null, $clienteId = null, $numAtendimento = null): array
    {
        $sql = "SELECT 
                    A.ID,
                    A.NUMATENDIMENTO,
                    A.CLIENTE,
                    A.CONTATO,
                    A.DATAABERATEND AS EMISSAO,
                    DATE_FORMAT(A.DATAABERATEND, '%d/%m/%Y %H:%i') AS DATAABERATEND_FORMATADA,
                    DATE_FORMAT(A.DATAFECHATEND, '%d/%m/%Y %H:%i') AS DATAFECHATEND_FORMATADA,
                    A.USRABERTURA,
                    A.PRIORIDADE,
                    A.PRAZOENTREGA,
                    A.DESCEQUIPAMENTO,
                    A.KMENTRADA,
                    A.OBS,
                    A.OBSSERVICO,
                    A.SOLUCAO,
                    A.VALORSERVICOS,
                    A.VALORPECAS,
                    A.VALORUTILIZADOPECAS,
                    A.TOTALUTILIZADOPECAS,
                    A.VALORVISITA,
                    A.VALORDESCONTO AS DESCONTO,
                    A.VALORTOTAL AS TOTAL,
                    A.TIPOCOBRANCA,
                    A.CONDPGTO AS CONDPG,
                    A.CONTA,
                    A.GENERO,
                    A.CENTROCUSTO,
                    A.CAT_SITUACAO_ID AS SITUACAO,
                    A.CAT_EQUIPAMENTO_ID,
                    A.EQUIPE_ID,
                    A.CAT_TIPO_ID,
                    A.PEDIDO_ID AS PEDIDO,
                    A.CREATED_USER,
                    A.UPDATED_USER,
                    A.CREATED_AT,
                    A.UPDATED_AT,
                    0 AS FRETE,
                    0 AS DESPACESSORIAS,
                    C.NOMEREDUZIDO AS NOME,
                    S.DESCRICAO AS SITUACAO_DESC,
                    E.DESCRICAO AS EQUIPAMENTO_DESC,
                    T.DESCRICAO AS TIPO_DESC
                FROM CAT_ATENDIMENTO A
                LEFT JOIN FIN_CLIENTE C ON A.CLIENTE = C.CLIENTE
                LEFT JOIN CAT_SITUACAO S ON A.CAT_SITUACAO_ID = S.ID
                LEFT JOIN CAT_EQUIPAMENTO E ON A.CAT_EQUIPAMENTO_ID = E.ID
                LEFT JOIN CAT_TIPO T ON A.CAT_TIPO_ID = T.ID
                WHERE 1=1";

        // Monta parâmetros dinamicamente
        $params = [];

        if ($id !== null && $id !== '') {
            $sql .= " AND A.ID = :id";
            $params[':id'] = (int)$id;
        }

        if ($numAtendimento !== null && $numAtendimento !== '') {
            $sql .= " AND A.NUMATENDIMENTO = :num_atendimento";
            $params[':num_atendimento'] = $numAtendimento;
        }

        if ($clienteId !== null && $clienteId !== '') {
            $sql .= " AND A.CLIENTE = :cliente";
            $params[':cliente'] = (int)$clienteId;
        }

        if ($dataInicial !== null && $dataInicial !== '' && $dataFinal !== null && $dataFinal !== '') {
            $sql .= " AND DATE(A.DATAABERATEND) BETWEEN :data_inicial AND :data_final";
            $params[':data_inicial'] = $dataInicial;
            $params[':data_final'] = $dataFinal;
        } else if ($dataInicial !== null && $dataInicial !== '') {
            $sql .= " AND DATE(A.DATAABERATEND) >= :data_inicial";
            $params[':data_inicial'] = $dataInicial;
        } else if ($dataFinal !== null && $dataFinal !== '') {
            $sql .= " AND DATE(A.DATAABERATEND) <= :data_final";
            $params[':data_final'] = $dataFinal;
        }

        $sql .= " ORDER BY A.DATAABERATEND DESC";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':id' || $key === ':cliente') {
                $banco->bindValue($key, (int)$value, PDO::PARAM_INT);
            } else {
                $banco->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $banco->execute();

        $resultado = $banco->fetchAll();

        // Garante chaves em caixa alta como usadas acima ao montar JSON
        if (is_array($resultado)) {
            foreach ($resultado as $index => $linha) {
                if (is_array($linha)) {
                    $resultado[$index] = array_change_key_case($linha, CASE_UPPER);
                }
            }
        }

        return $resultado;
    }

    /**
     * @name selectPerson
     * @description Busca clientes na tabela FIN_CLIENTE para autocomplete
     * @param string $term Termo de busca para filtrar por nome do cliente
     * @return void Retorna JSON com lista de clientes encontrados
     */
    public function selectPerson($term){
        $sql = "SELECT 
                    CLIENTE,
                    COALESCE(NOMEREDUZIDO, NOME) AS NOME
                FROM FIN_CLIENTE 
                WHERE (NOMEREDUZIDO LIKE :term OR NOME LIKE :term) 
                ORDER BY NOME ";

        try {
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->bindValue(':term', '%'.$term.'%', PDO::PARAM_STR);
            $banco->execute();
            
            $resultPesq = $banco->fetchAll();
            $clienteResult = array();

            foreach ($resultPesq as $row) {
                $clienteResult[] = array(
                    'id' => trim($row['CLIENTE']),
                    'text' => trim($row['NOME'])
                );
            }

            // Set proper headers for JSON response
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($clienteResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            exit;

        } catch (PDOException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
            exit;
        }
    }  // fim selectPerson

    /**
     * @name searchAtendimento
     * @description Busca ordens de serviço (OS) na tabela CAT_ATENDIMENTO para geração de faturas
     * @param string|null $dateInitial Data inicial no formato dd/mm/yyyy para filtrar por DATAABERATEND
     * @param string|null $dateEnd Data final no formato dd/mm/yyyy para filtrar por DATAABERATEND
     * @param int|null $clienteId ID do cliente para filtrar resultados
     * @param string|null $documentId Número do atendimento para busca específica
     * @return array Array com os resultados da busca ordenados por data de abertura
     */
    private function searchAtendimento($date_initial = null, $date_end = null, $cliente_id = null, $document_id = null): array
    {
        $sql = "SELECT 
            'OS' AS TIPO_DOCUMENTO,
            A.ID,
            A.NUMATENDIMENTO AS NUMERO_DOCUMENTO,
            DATE_FORMAT(A.DATAABERATEND, '%d/%m/%Y %H:%i') AS DATA_EMISSAO_FORMATADA,
            A.VALORSERVICOS AS VALOR_SERVICOS,
            A.VALORTOTAL AS VALOR_TOTAL,
            A.CENTROCUSTO AS CENTROCUSTO,
            C.NOMEREDUZIDO AS NOME_CLIENTE,
            S.DESCRICAO AS SITUACAO_DESC,
            A.CLIENTE AS CLIENTE_ID 
        FROM CAT_ATENDIMENTO A 
        LEFT JOIN FIN_CLIENTE C ON A.CLIENTE = C.CLIENTE 
        LEFT JOIN CAT_SITUACAO S ON A.CAT_SITUACAO_ID = S.ID 
        WHERE 1=1";

        $params = array();

        if ($cliente_id !== null && $cliente_id !== '') {
            $sql .= " AND A.CLIENTE = :cliente_id";
            $params[':cliente_id'] = (int)$cliente_id;
        }

        if ($document_id !== null && $document_id !== '') {
            $sql .= " AND A.NUMATENDIMENTO = :document_id";
            $params[':document_id'] = $document_id;
        }

        // Converter datas do formato brasileiro (dd/mm/yyyy) para formato do banco (yyyy-mm-dd)
        if ($date_initial !== null && $date_initial !== '' && $date_end !== null && $date_end !== '') {

            $sql .= " AND DATE(A.DATAABERATEND) BETWEEN :data_inicial AND :data_final";
            $params[':data_inicial'] = $this->convertDateToDatabase($date_initial);
            $params[':data_final'] = $this->convertDateToDatabase($date_end);

        } else if ($date_initial !== null && $date_initial !== '') {

            $sql .= " AND DATE(A.DATAABERATEND) >= :data_inicial";
            $params[':data_final'] = $this->convertDateToDatabase($date_initial);

        } else if ($date_end !== null && $date_end !== '') {

            $sql .= " AND DATE(A.DATAABERATEND) <= :data_final";
            $params[':data_final'] = $this->convertDateToDatabase($date_end);

        }

        $sql .= " ORDER BY A.DATAABERATEND DESC";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':cliente_id') {
                $banco->bindValue($key, (int)$value, PDO::PARAM_INT);
            } else {
                $banco->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $teste = $banco->queryString();

        $banco->execute();
        return $banco->fetchAll();
    }

    /**
     * @name searchPedido
     * @description Busca pedidos na tabela FAT_PEDIDO que possuem serviços registrados em FAT_PEDIDO_SERVICO
     * @param string|null $dateInitial Data inicial no formato dd/mm/yyyy para filtrar por EMISSAO
     * @param string|null $dateEnd Data final no formato dd/mm/yyyy para filtrar por EMISSAO
     * @param int|null $clienteId ID do cliente para filtrar resultados
     * @param string|null $documentId ID do pedido para busca específica
     * @return array Array com os resultados da busca ordenados por data de emissão
     */

    private function searchPedido($date_initial = null, $date_end = null, $cliente_id = null, $document_id = null): array
    {
        $sql = "SELECT 
            'PEDIDO' AS TIPO_DOCUMENTO,
            P.ID,
            P.ID AS NUMERO_DOCUMENTO,
            DATE_FORMAT(P.DATEINSERT, '%d/%m/%Y %H:%i') AS DATA_EMISSAO_FORMATADA,
            P.VALORSERVICOS AS VALOR_SERVICOS,
            P.TOTAL AS VALOR_TOTAL,
            P.CCUSTO AS CENTROCUSTO,
            C.NOMEREDUZIDO AS NOME_CLIENTE, 
            D.PADRAO AS SITUACAO_DESC,
            P.CLIENTE AS CLIENTE_ID 
        FROM FAT_PEDIDO P 
        LEFT JOIN FIN_CLIENTE C ON P.CLIENTE = C.CLIENTE 
        LEFT JOIN AMB_DDM D ON D.CAMPO = 'SITUACAOPEDIDO' AND D.TIPO = P.SITUACAO 
        WHERE EXISTS (SELECT 1 FROM FAT_PEDIDO_SERVICO PS WHERE PS.FAT_PEDIDO_ID = P.ID)";

        $params = array();

        // Se document_id foi informado, busca apenas esse documento ignorando outros parâmetros
        if ($document_id !== null && $document_id !== '') {

            $sql .= " AND P.ID = :document_id";
            $params[':document_id'] = $document_id;
            
        } else {
            // Aplica outros filtros apenas se document_id não foi informado
            if ($cliente_id !== null && $cliente_id !== '') {
                $sql .= " AND P.CLIENTE = :cliente_id";
                $params[':cliente_id'] = (int)$cliente_id;
            }

            // Converter datas do formato brasileiro (dd/mm/yyyy) para formato do banco (yyyy-mm-dd)
            if ($date_initial !== null && $date_initial !== '' && $date_end !== null && $date_end !== '') {

                $sql .= " AND DATE(P.EMISSAO) BETWEEN :data_inicial AND :data_final";
                $params[':data_inicial'] = $this->convertDateToDatabase($date_initial);
                $params[':data_final'] = $this->convertDateToDatabase($date_end);

            } else if ($date_initial !== null && $date_initial !== '') {

                $sql .= " AND DATE(P.EMISSAO) >= :data_inicial";
                $params[':data_inicial'] = $this->convertDateToDatabase($date_initial);

            } else if ($date_end !== null && $date_end !== '') {

                $sql .= " AND DATE(P.EMISSAO) <= :data_final";
                $params[':data_final'] = $this->convertDateToDatabase($date_end);

            }
        }

        $sql .= " ORDER BY P.EMISSAO DESC";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);

        foreach ($params as $key => $value) {
            if ($key === ':cliente_id') {
                $banco->bindValue($key, (int)$value, PDO::PARAM_INT);
            } else {
                $banco->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $teste = $banco->queryString();

        $banco->execute();
        return $banco->fetchAll();
    }

    /**
     * @name searchDocuments
     * @description Busca documentos (OS e Pedidos) em ambas as tabelas CAT_ATENDIMENTO e FAT_PEDIDO para geração de faturas
     * @param string $data String JSON com parâmetros de busca (date_initial, date_end, cliente_id, document_id)
     * @return void Retorna JSON com resultados combinados de OS e Pedidos ordenados por data
     */
    public function searchDocuments($data)
    {
        try {
            $dados = array();

            if ($data) {
                $dados = json_decode($data, true);
            }

            // Extract search parameters
            $date_initial = isset($dados['date_initial']) ? $dados['date_initial'] : null;
            $date_end     = isset($dados['date_end']) ? $dados['date_end'] : null;
            $client_id    = isset($dados['client_id']) ? $dados['client_id'] : null;
            $document_id  = isset($dados['document_id']) ? $dados['document_id'] : null;

            // Search in both tables using separate functions
            $resultados_atendimento = $this->searchAtendimento($date_initial, $date_end, $client_id, $document_id);
            //$resultados_pedido = $this->searchPedido($date_initial, $date_end, $client_id, $document_id);
            $resultados_pedido = [];
            // Combine results without case conversion
            $resultados = array();

            if (is_array($resultados_atendimento) && !empty($resultados_atendimento)) {
                $resultados = array_merge($resultados, $resultados_atendimento);
            }

            if (is_array($resultados_pedido) && !empty($resultados_pedido)) {
                $resultados = array_merge($resultados, $resultados_pedido);
            }

            // Sort combined results by date (most recent first)
            usort($resultados, function($a, $b) {
                $dateA = strtotime($a['DATA_EMISSAO_FORMATADA']);
                $dateB = strtotime($b['DATA_EMISSAO_FORMATADA']);
                return $dateB - $dateA;
            });

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array(
                'success' => true,
                'total' => count($resultados),
                'data' => $resultados
            ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;

        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode(array(
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ));
            exit;
        }
    }

    /**
     * @name convertDateToDatabase
     * @description Converte data do formato brasileiro (dd/mm/yyyy) para formato do banco (yyyy-mm-dd)
     * @param string $date Data no formato dd/mm/yyyy ou yyyy-mm-dd
     * @return string Data no formato yyyy-mm-dd para uso no banco de dados
     */
    private function convertDateToDatabase($date)
    {
        if (empty($date)) {
            return null;
        }
        
        // Verifica se a data já está no formato correto
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }
        
        // Converte de dd/mm/yyyy para yyyy-mm-dd
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $date, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }
        
        // Se não conseguir converter, retorna a data original
        return $date;
    }

    /**
     * Busca serviços de uma ordem de serviço ou pedido
     * @return void
     */
    public function buscarServicos(int $id, string $client_id ,string $tipo_documento)
    {
        // Verificar se a sessão está ativa
        if (!isset($_SESSION['user_array']) || empty($_SESSION['user_array'])) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Sessão expirada', 'redirect' => true], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        if ($id <= 0) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'ID inválido'], JSON_UNESCAPED_UNICODE);
            return;
        }   
        
        try {
            $servicos = [];
            $data_provider = [];
            $data_borrower = [];
            
            // Assembly of services
            if ($tipo_documento === 'OS') {
                $servicos = $this->buscarServicosOS($id);
            } elseif ($tipo_documento === 'PEDIDO') {
                $servicos = $this->buscarServicosPedido($id);
            }

            // Assembly of provider
            $data_provider = $this->searchProvider($this->m_empresaid);

            // Assembly of borrower
            $data_borrower = $this->searchBorrower($client_id);
            
            // Criar array multidimensional com todos os dados
            $response_data = [
                'servicos' => $servicos,
                'data_provider' => $data_provider,
                'data_borrower' => $data_borrower
            ];
            
            header('Content-Type: application/json; charset=utf-8');

            $json = json_encode(['success' => true, 'data' => $response_data], JSON_UNESCAPED_UNICODE);
            
            if ($json === false) {
                // Erro no json_encode
                echo json_encode(['success' => false, 'message' => 'Erro ao codificar JSON: ' . json_last_error_msg()], JSON_UNESCAPED_UNICODE);
            } else {
                // Sucesso - enviar o JSON
                echo $json;
            }
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Erro ao buscar serviços: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }
    
    /**
     * Busca serviços de uma ordem de serviço
     * @param int $id ID da ordem de serviço
     * @return array
     */
    private function buscarServicosOS($id)
    {
        try {
            $sql = "SELECT 
                S.ID,
                S.CAT_ATENDIMENTO_ID,
                S.ID_USER,
                S.DATA,
                S.HORAINI,
                S.HORAFIM,
                S.HORATOTAL,
                S.CUSTOUSER,
                S.DESCSERVICO,
                S.UNIDADE,
                S.QUANTIDADE,
                S.QUANTIDADE_EXECUTADA,
                S.VALUNITARIO,
                S.TOTALSERVICO,
                S.CAT_SERVICOS_ID,
                S.CREATED_USER,
                S.UPDATED_USER,
                S.CREATED_AT,
                S.UPDATED_AT,
                U.NOMEREDUZIDO AS NOME_USUARIO
            FROM CAT_AT_SERVICOS S
            LEFT JOIN AMB_USUARIO U ON S.ID_USER = U.USUARIO
            WHERE S.CAT_ATENDIMENTO_ID = :id
            ORDER BY S.ID";
            
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->bindValue(':id', $id, \PDO::PARAM_INT);
            $banco->execute();
            
            $result = $banco->fetchAll();
            
            // Garantir que sempre retorne um array
            return is_array($result) ? $result : [];
            
        } catch (Exception $e) {
            // Em caso de erro, retornar array com informação de erro para o frontend
            return [
                'error' => true,
                'message' => 'Erro na consulta buscarServicosOS: ' . $e->getMessage(),
                'details' => $e->getTraceAsString()
            ];
        }
    }
    
    /**
     * Busca serviços de um pedido
     * @param int $id ID do pedido
     * @return array
     */
    private function buscarServicosPedido($id)
    {
        try {
            $sql = "SELECT 
                PS.ID,
                PS.FAT_PEDIDO_ID,
                PS.ID_USER,
                PS.DATA,
                PS.HORAINI,
                PS.HORAFIM,
                PS.HORATOTAL,
                PS.CUSTOUSER,
                PS.DESCSERVICO,
                PS.OBSSERVICO,
                PS.UNIDADE,
                PS.QUANTIDADE,
                PS.VALUNITARIO,
                PS.TOTALSERVICO,
                PS.CAT_SERVICOS_ID,
                PS.CREATED_USER,
                PS.UPDATED_USER,
                PS.CREATED_AT,
                PS.UPDATED_AT,
                U.NOMEREDUZIDO AS NOME_USUARIO
            FROM FAT_PEDIDO_SERVICO PS
            LEFT JOIN AMB_USUARIO U ON PS.ID_USER = U.USUARIO
            WHERE PS.FAT_PEDIDO_ID = :id
            ORDER BY PS.ID";
            
            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->bindValue(':id', $id, \PDO::PARAM_INT);
            $banco->execute();
            
            $result = $banco->fetchAll();
            
            // Garantir que sempre retorne um array
            return is_array($result) ? $result : [];
            
        } catch (Exception $e) {
            // Em caso de erro, retornar array com informação de erro para o frontend
            return [
                'error' => true,
                'message' => 'Erro na consulta buscarServicosPedido: ' . $e->getMessage(),
                'details' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Busca cidades via ViaCEP (prioritário) ou IBGE (fallback)
     * @param string $term Termo de busca
     * @return void
     */
    public function searchCidadeAjax(string $term, string $estado, string $estado_sigla) : void
    {
        $term = trim($term);

        $resultado = $this->searchCityLocal($term);
        
       // Se ViaCEP falhar, usa IBGE
        if (empty($resultado)) {
            $resultado = $this->searchCityViaIbge($term,$estado);
        }

        // Se Brasil Api
        if(empty($resultado)){
            $resultado = $this->searchCityBrasilApi($term,$estado_sigla);
        }

        // Retorna resultado ou erro
        if (!empty($resultado)) {
            $this->retornarJson($resultado);
        } else {
            $this->retornarJson(['erro' => 'Nenhuma cidade encontrada']);
        }
    }

    /**
     * Busca cidades via IBGE
     * @param string $term Termo de busca
     * @return array
     * @link https://servicodados.ibge.gov.br/api/docs/localidades
     */
    private function searchCityViaIbge(string $term, string $estado): array
    {
        //. example https://servicodados.ibge.gov.br/api/v1/localidades/estados/{UF}/municipios
        $url = "https://servicodados.ibge.gov.br/api/v1/localidades/estados/" . urlencode($estado) . "/municipios";
        
        $response = $this->fazerRequisicaoHttp($url);
        if (!$response) return [];

        $municipios = json_decode($response, true);
        if (!$municipios) return [];

        $resultado = [];
        $term_lowercase = mb_strtolower($term, 'UTF-8');
        
        foreach ($municipios as $municipio) {
            $nomeMunicipio = mb_strtolower($municipio['nome'], 'UTF-8');
            
            if (strpos($nomeMunicipio, $term_lowercase) !== false) {
                $resultado[] = [
                    'id' => $municipio['id'],
                    'text' => $municipio['nome']
                ];
            }
        }

        // Ordena e limita resultados
        usort($resultado, [$this, 'ordenarMunicipios']);
        return array_slice($resultado, 0, 50);
    }

    /**
     * Busca cidades via Brasil Api
     * @param string $term Termo de busca
     * @return array
     * @link https://brasilapi.com.br/docs/api/ibge/municipios
     */
    private function searchCityBrasilApi(string $term, string $estado): array
    {   
        // example https://brasilapi.com.br/api/ibge/municipios/v1/{siglaUF}?providers=dados-abertos-br,gov,wikipedia
        $url = "https://brasilapi.com.br/api/ibge/municipios/v1/" . urlencode($estado) . "?providers=dados-abertos-br,gov,wikipedia";

        $response = $this->fazerRequisicaoHttp($url);

        if (!$response) return [];

        $municipios = json_decode($response, true);

        if (!$municipios) return [];

        $resultado = [];
        $term_lowercase = mb_strtolower($term, 'UTF-8');
        
        foreach ($municipios as $municipio) {
            $nomeMunicipio = mb_strtolower($municipio['nome'], 'UTF-8');
            
            if (strpos($nomeMunicipio, $term_lowercase) !== false) {
                $resultado[] = [
                    'id' => $municipio['codigo_ibge'],
                    'text' => $municipio['nome']
                ];
            }
        }

        // Ordena e limita resultados
        usort($resultado, [$this, 'ordenarMunicipios']);
        return array_slice($resultado, 0, 50);
    }


    /**
     * Busca cidades via Local
     * @param string $term Termo de busca
     * @return array
     */
    private function searchCityLocal(string $term) : array
    {
        $banco = new c_banco_pdo();
        $sql = "SELECT DISTINCT CIDADE as text, CODMUNICIPIO as id FROM FIN_CLIENTE WHERE CIDADE LIKE :term;";
        $banco->prepare($sql);
        $banco->bindValue(':term', '%'.$term.'%', \PDO::PARAM_STR);
        $banco->execute();

        return $banco->fetchAll();
    }

    /**
     * Faz requisição HTTP com cURL
     * @param string $url URL da requisição
     * @return string|false
     */
    private function fazerRequisicaoHttp(string $url)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Connection: keep-alive'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpCode === 200 && $response !== false) ? $response : false;
    }

    /**
     * Ordena municípios alfabeticamente
     * @param array $a
     * @param array $b
     * @return int
     */
    private function ordenarMunicipios(array $a, array $b): int
    {
        return strcmp($a['text'], $b['text']);
    }

    /**
     * Retorna resposta JSON
     * @param array $dados
     * @return void
     */
    private function retornarJson(array $dados): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function searchProvider(string $empresa_id) : array
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT 
                    P.NFS_SERIE AS PRESTADOR_NFS_SERIE,
                    P.NFS_SITUACAO_TRIBUTARIA AS PRESTADOR_NFS_SITUACAO_TRIBUTARIA,
                    E.EMPRESA AS PRESTADOR_EMPRESA,
                    E.NOMEEMPRESA AS PRESTADOR_EMPRESA_NOME,
                    E.CODMUNICIPIO AS PRESTADOR_CODIGO_MUNICIPIO,
                    E.CNPJ AS PRESTADOR_CNPJ,
                    CONCAT(
                        SUBSTRING(CNPJ, 1, 2), '.', 
                        SUBSTRING(CNPJ, 3, 3), '.', 
                        SUBSTRING(CNPJ, 6, 3), '/', 
                        SUBSTRING(CNPJ, 9, 4), '-',
                        SUBSTRING(CNPJ, 13, 2)
                    ) as PRESTADOR_CNPJ_FORMATADO
                FROM AMB_EMPRESA E
                LEFT JOIN EST_PARAMETRO P ON P.FILIAL = E.CENTROCUSTO
                WHERE EMPRESA = :empresa_id";
                
            $banco->prepare($sql);
            $banco->bindValue(':empresa_id', $empresa_id, \PDO::PARAM_INT);
            $banco->execute();
            $local_incidencia = $banco->fetchAll();
            
            // Garantir que sempre retorne um array
            return is_array($local_incidencia) ? $local_incidencia : [];
            
        } catch (Exception $e) {
            // Em caso de erro, retornar array com informação de erro para o frontend
            return [
                'error' => true,
                'message' => 'Erro na consulta searchProvider: ' . $e->getMessage(),
                'details' => $e->getTraceAsString()
            ];
        }
    }

    public function searchBorrower(string $client_id) : array
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT 
                        CLIENTE AS TOMADOR_ID,
                        CASE 
                            WHEN PESSOA = 'F' THEN 'FISICA'
                            WHEN PESSOA = 'J' THEN 'JURIDICA'
                            WHEN PESSOA = 'E' THEN 'ESTRANGEIRO'
                            ELSE PESSOA
                        END AS TOMADOR_TIPO_PESSOA_DESC,
                        PESSOA AS TOMADOR_TIPO_PESSOA,
                        CNPJCPF AS TOMADOR_CNPJCPF,
                        CONCAT(
                            SUBSTRING(CNPJCPF, 1, 2), '.', 
                            SUBSTRING(CNPJCPF, 3, 3), '.', 
                            SUBSTRING(CNPJCPF, 6, 3), '/', 
                            SUBSTRING(CNPJCPF, 9, 4), '-',
                            SUBSTRING(CNPJCPF, 13, 2)
                        ) as TOMADOR_CNPJ_FORMATADO, 
                        INSCESTRG AS TOMADOR_INSCRICAO_ESTADUAL_RG, 
                        NOME AS TOMADOR_NOME,
                        NOMEREDUZIDO AS TOMADOR_NOME_REDUZIDO,
                        EMAIL AS TOMADOR_EMAIL,
                        ENDERECO AS TOMADOR_ENDERECO,
                        NUMERO AS TOMADOR_ENDERECO_NUMERO,
                        COMPLEMENTO AS TOMADOR_ENDERECO_COMPLEMENTO, 
                        BAIRRO AS TOMADOR_ENDERECO_BAIRRO, 
                        CIDADE AS TOMADOR_ENDERECO_CIDADE,
                        CODMUNICIPIO AS TOMADOR_ENDERECO_CODIGO_MUNICIPIO,
                        UF AS TOMADOR_ENDERECO_UF,
                        E.CODIGO_UF AS TOMADOR_ENDERECO_UF_ID,
                        CONCAT(
                            SUBSTRING(CEP, 1, 5), '-', 
                            SUBSTRING(CEP, 6, 3)
                        ) as TOMADOR_ENDERECO_CEP_FORMATADO,
                        CEP AS TOMADOR_ENDERECO_CEP,
                        FONE AS TOMADOR_FONE,
                        CELULAR AS TOMADOR_CELULAR 
                    FROM FIN_CLIENTE 
                    INNER JOIN AMB_ESTADOS E ON E.SIGLA = FIN_CLIENTE.UF
                    WHERE CLIENTE = :client_id";
                
            $banco->prepare($sql);
            $banco->bindValue(':client_id', $client_id, \PDO::PARAM_STR);
            $banco->execute();
            $result = $banco->fetchAll();
            
            // Garantir que sempre retorne um array
            return is_array($result) ? $result : [];
            
        } catch (Exception $e) {
            // Em caso de erro, retornar array com informação de erro para o frontend
            return [
                'error' => true,
                'message' => 'Erro na consulta searchBorrower: ' . $e->getMessage(),
                'details' => $e->getTraceAsString()
            ];
        }
    }


    /**
     * Busca todos os serviços disponíveis para um município específico
     * @param string $codigoMunicipio - Código do município
     * @return array - Array com IDs e descrições dos serviços
     */
    public function searchListaServicosAjax(string $codigo_municipio) : array
    {
        try {
            $banco = new c_banco_pdo();
            
            // Consulta SQL para buscar todos os serviços disponíveis
            // Pode ser filtrada por município se necessário
            $sql = "SELECT ID, REPLACE(CODIGO, '.', '') AS CODIGO_ID, CODIGO, SERVICO, ALIQUOTA, RETENCAO, RETENCAO_LEI_189_25, OBRAS 
                    FROM EST_SERVICOS_CODIGOS
                    WHERE COD_MUNICIPIO = :codigo_municipio ";
            
            $banco->prepare($sql);
            $banco->bindValue(':codigo_municipio', $codigo_municipio, \PDO::PARAM_STR);
            $banco->execute();
            
            $resultado = $banco->fetchAll();
            
            // Formata os resultados para o formato esperado pelo combo
            $servicosResult = array();
            foreach ($resultado as $servico) {
                $servicosResult[] = array(
                    'id' => $servico['CODIGO_ID'],
                    'text' => $servico['CODIGO'] . ' - ' . $servico['SERVICO'],
                    'aliquota' => $servico['ALIQUOTA'] ? number_format($servico['ALIQUOTA'], 4, ',', '') : '0,0000'
                );
            }
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($servicosResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('erro' => 'Erro na consulta: ' . $e->getMessage()), JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }

    /**
     * Busca todos os estados disponíveis
     * @return void
     */
    public function searchEstadosAjax() : void
    {
        try {
            $banco = new c_banco_pdo();
            
            // Consulta SQL para buscar todos os estados
            $sql = "SELECT ID, CODIGO_UF, NOME_ESTADO, SIGLA 
                    FROM AMB_ESTADOS
                    ORDER BY NOME_ESTADO ASC";
            
            $banco->prepare($sql);
            $banco->execute();
            
            $resultado = $banco->fetchAll();
            
            // Formata os resultados para o formato esperado pelo combo
            $estadosResult = array();
            foreach ($resultado as $estado) {
                $estadosResult[] = array(
                    'id' => $estado['CODIGO_UF'],
                    'text' => $estado['SIGLA'] . ' - ' . $estado['NOME_ESTADO']
                );
            }
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($estadosResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('erro' => 'Erro na consulta: ' . $e->getMessage()), JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Busca situações tributárias disponíveis para um município específico
     * @param string $codigoMunicipio - Código do município
     * @return array - Array com IDs e descrições das situações tributárias
     */
    public function searchSituacaoTributaria(string $codigo_municipio) : array
    {
        try {
            $banco = new c_banco_pdo();
            
            // Consulta SQL para buscar situações tributárias disponíveis
            // Pode ser filtrada por município se necessário
            $sql = "SELECT ID, CODIGO, SIGLA, DESCRICAO, UF
                    FROM EST_SERVICOS_SITUACAO_TRIBUTARIA
                    WHERE 1 = 1 
                    ORDER BY CODIGO ASC";
            
            $banco->prepare($sql);
            //$banco->bindValue(':codigo_municipio', $codigo_municipio, \PDO::PARAM_STR);
            $banco->execute();
            
            $resultado = $banco->fetchAll();
            
            // Formata os resultados para o formato esperado pelo combo
            $situacoesResult = array();
            foreach ($resultado as $situacao) {
                $situacoesResult[] = array(
                    'id' => $situacao['CODIGO'],
                    'text' => $situacao['CODIGO'] . ' - ' . $situacao['SIGLA'] . ' - ' . $situacao['DESCRICAO']
                );
            }
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($situacoesResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('erro' => 'Erro na consulta: ' . $e->getMessage()), JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }


    /**
     * Busca situações tributárias disponíveis para um município específico
     * @param string $codigoMunicipio - Código do município
     * @return array - Array com IDs e descrições das situações tributárias
     */
    public function searchParcelas() : array
    {
        try {
            $banco = new c_banco_pdo();
            
            // Consulta SQL para buscar situações tributárias disponíveis
            // Pode ser filtrada por município se necessário
            $sql = "SELECT ID, DESCRICAO, NUMPARCELAS 
                    FROM FAT_COND_PGTO
                    WHERE 1 = 1 
                    ORDER BY DESCRICAO ASC";
            
            $banco->prepare($sql);
            //$banco->bindValue(':codigo_municipio', $codigo_municipio, \PDO::PARAM_STR);
            $banco->execute();
            
            $resultado = $banco->fetchAll();
            
            // Formata os resultados para o formato esperado pelo combo
            $situacoesResult = array();
            foreach ($resultado as $situacao) {
                $situacoesResult[] = array(
                    'id' => $situacao['ID'],
                    'text' => $situacao['DESCRICAO'],
                    'data_value' => $situacao['NUMPARCELAS']
                );
            }
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($situacoesResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('erro' => 'Erro na consulta: ' . $e->getMessage()), JSON_UNESCAPED_UNICODE);
        }
        
        exit;
    }

    

} 	//	END OF THE CLASS
?>
 

