<?php
/**
 * @package   admv4.5
 * @name      c_fatura_nfs
 * @version   1.0.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy dos Santos <jhonkenedy@gmail.com>
 * @date      06/08/2025
 * @description Classe para gestão de faturas e notas fiscais de serviços
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_database_pdo.php");

    Class c_fatura_nfs extends c_user {
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
            S.DESCRICAO AS SITUACAO_DESC 
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
            D.PADRAO AS SITUACAO_DESC 
        FROM FAT_PEDIDO P 
        LEFT JOIN FIN_CLIENTE C ON P.CLIENTE = C.CLIENTE 
        LEFT JOIN AMB_DDM D ON D.CAMPO = 'SITUACAOPEDIDO' AND D.TIPO = P.SITUACAO 
        WHERE EXISTS (SELECT 1 FROM FAT_PEDIDO_SERVICO PS WHERE PS.FAT_PEDIDO_ID = P.ID)";

        $params = array();

        if ($cliente_id !== null && $cliente_id !== '') {
            $sql .= " AND P.CLIENTE = :cliente_id";
            $params[':cliente_id'] = (int)$cliente_id;
        }

        if ($document_id !== null && $document_id !== '') {
            $sql .= " AND P.ID = :document_id";
            $params[':document_id'] = $document_id;
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
            $client_id  = isset($dados['client_id']) ? $dados['client_id'] : null;
            $document_id = isset($dados['document_id']) ? $dados['document_id'] : null;

            // Search in both tables using separate functions
            $resultados_atendimento = $this->searchAtendimento($date_initial, $date_end, $client_id, $document_id);
            $resultados_pedido = $this->searchPedido($date_initial, $date_end, $client_id, $document_id);

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
    public function buscarServicos(int $id, string $tipoDocumento)
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
            
            if ($tipoDocumento === 'OS') {
                // Buscar serviços de ordem de serviço
                $servicos = $this->buscarServicosOS($id);
            } elseif ($tipoDocumento === 'PEDIDO') {
                // Buscar serviços de pedido
                $servicos = $this->buscarServicosPedido($id);
            }
            
            header('Content-Type: application/json; charset=utf-8');

            $json = json_encode(['success' => true, 'data' => $servicos], JSON_UNESCAPED_UNICODE);
            
            if ($json === false) {
                // Erro no json_encode
                echo json_encode(['error' => 'Erro ao codificar JSON: ' . json_last_error_msg()]);
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
        
        return $banco->fetchAll();
    }
    
    /**
     * Busca serviços de um pedido
     * @param int $id ID do pedido
     * @return array
     */
    private function buscarServicosPedido($id)
    {
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
        
        return $banco->fetchAll();
    }

}	//	END OF THE CLASS
?>
