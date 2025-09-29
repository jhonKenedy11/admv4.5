<?php
/*
FullCalendar Interaction Plugin v4.1.0
Docs & License: https://fullcalendar.io/
(c) 2019 Adam Shaw

* @author Jhon Kenedy - jhon.kened11@hotmail.com
* @pagina desenvolvida usando FullCalendar,
*/


$dir = (__DIR__);

include_once($dir . "/../../bib/c_database_pdo.php");

class c_os_calendario extends c_user
{

    private $banco = NULL;

    public function __construct()
    {

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);
    }


    /**
     * Consulta ordens de serviço dentro de um período específico
     * 
     * Esta função realiza uma consulta no banco de dados para obter eventos de atendimento
     * dentro de um intervalo de datas, com possibilidade de filtro por vendedor. Os resultados
     * são formatados como um array de eventos no formato JSON.
     * 
     * @param array $params Array com os parâmetros de consulta:
     *              - data_ini (string): Data inicial no formato 'YYYY-MM-DD'
     *              - data_fim (string): Data final no formato 'YYYY-MM-DD'
     *              - vendedor (int|null) [opcional]: ID do vendedor para filtro
     * 
     * @return void Não retorna valor diretamente, mas envia JSON com array de eventos contendo:
     *              - id (int): ID do atendimento
     *              - title (string): Descrição do serviço
     *              - color (string): Cor associada ao usuário
     *              - start (string): Data/hora de início (formato 'Y-m-d H:i:s')
     *              - end (string): Data/hora de término (formato 'Y-m-d H:i:s')
     *              - equipe_id (int): ID da equipe responsável
     *              - situacao_descricao (string): Descrição da situação
     *              - cliente_descricao (string): Nome do cliente
     * 
     * @example Exemplo de uso:
     * <code>
     * $params = [
     *     "data_ini" => "2023-04-01",
     *     "data_fim" => "2023-04-30",
     *     "vendedor" => 5
     * ];
     * selectOrdemServico($params);
     * 
     * @throws Exception Em caso de erro na consulta, registra no log (/var/tmp/my-errors.log)
     *             e retorna JSON com mensagem de erro
     * 
     * @uses c_banco_pdo() Classe de conexão com o banco de dados via PDO
     * @link https://www.php.net/manual/pt_BR/book.pdo.php Documentação do PDO
     */
    public function selectOrdemServico($params)
    {
        $data_ini = $params["data_ini"];
        $data_fim = $params["data_fim"];
        $vendedor = $params["vendedor"];
        //$dataIni = "2023-04-01";
        //$dataFim = "2023-04-14";

        $sql = "SELECT 
                CA.ID AS id,
                CA.OBSSERVICO AS title,
                CA.DATAABERATEND AS start,
                CA.PRAZOENTREGA AS end, 
                CA.CAT_SITUACAO_ID AS status_id,
                CA.USRABERTURA AS equipe_id,
                CS.DESCRICAO AS situacao_descricao,
                FC.NOME AS cliente_descricao,
                AU.USER_COLOR AS user_color,
                GROUP_CONCAT(CAU.USUARIO) AS usuarios
                FROM CAT_ATENDIMENTO CA
                INNER JOIN CAT_SITUACAO CS ON CA.CAT_SITUACAO_ID = CS.ID
                INNER JOIN FIN_CLIENTE FC ON CA.CLIENTE = FC.CLIENTE 
                INNER JOIN AMB_USUARIO AU ON CA.USRABERTURA = AU.USUARIO 
                LEFT JOIN CAT_ATENDIMENTO_USUARIOS CAU ON CA.ID = CAU.ATENDIMENTO_ID
                WHERE CA.DATAABERATEND >= :data_ini 
                AND CA.PRAZOENTREGA <= :data_fim ";

            if($vendedor !== "" and $vendedor !== null){
                // Se vendedor for um array, cria condição IN
                if (is_array($vendedor)) {
                    $placeholders = str_repeat('?,', count($vendedor) - 1) . '?';
                    $sql .= " AND CA.USRABERTURA IN ($placeholders)";
                } else {
                    $sql .= " AND CA.USRABERTURA = :vendedor";
                }
            }

        try {

            // cria a class PDO
            $this->banco = new c_banco_pdo();

            //Prepara o SQL que sera executado
            $this->banco->prepare($sql);

            //bind dos parametros
            $this->banco->bindValue(":data_ini", $data_ini);
            $this->banco->bindValue(":data_fim", $data_fim);

            // Verifica se existe vendedor e inclui na consulta
            if (!empty($vendedor)) {
                if (is_array($vendedor)) {
                    foreach ($vendedor as $key => $value) {
                        $this->banco->bindValue($key + 1, $value);
                    }
                } else {
                    $this->banco->bindValue(":vendedor", $vendedor);
                }
            }

            //executa a query
            $this->banco->execute();

            $eventos = [];

            //$teste = $this->banco->rowCount();
            //$query = $this->banco->queryString();

            // se nao existir registro ira mandar o array vazio
            if($this->banco->rowCount() > 0){

                while ($row_events = $this->banco->fetch(PDO::FETCH_ASSOC)) {

                    $id                 = $row_events['id'];
                    //$title              = utf8_encode($row_events['title']);
                    $title              = $row_events['title'];
                    $start              = $row_events['start'];
                    $end                = $row_events['end'];
                    $equipe_id          = $row_events['equipe_id'];
                    $situacao_descricao = $row_events['situacao_descricao'];
                    $cliente_descricao  = $row_events['cliente_descricao'];
                    $user_color         = $row_events['user_color'];

                    $eventos[] = [
                        'id' => $id,
                        'title' => $title,
                        'color' => $user_color,
                        'start' => $start,
                        'end' => $end,
                        'equipe_id' => $equipe_id,
                        'situacao_descricao' => $situacao_descricao,
                        'cliente_descricao' => $cliente_descricao,
                        'usuarios' => $row_events['usuarios']
                    ];
                }
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {

            // DEV verifique o erro nesse caminho /var/tmp/my-errors.log
            error_log("Erro na consulta: " . $e->getMessage());
            echo json_encode(['error' => 'Falha ao carregar eventos']);
            exit;
        }
    }


    /**
     * Atualiza a origem do calendário no banco de dados
     * 
     * Esta função recebe parâmetros em formato JSON, atualiza o registro na tabela CAT_ATENDIMENTO
     * e retorna uma resposta JSON com o status da operação. Realiza conversão de datas do formato
     * brasileiro (d/m/Y H:i) para o formato MySQL (Y-m-d H:i:s) antes de executar a atualização.
     * 
     * @param string $params JSON contendo os parâmetros para atualização. Deve conter:
     *              - data_inicio (string): Data de abertura no formato 'd/m/Y H:i'
     *              - data_finalizacao (string): Data de finalização no formato 'd/m/Y H:i'
     *              - equipe (string): Identificação do usuário/equipe que abriu
     *              - id (int): ID do registro a ser atualizado
     * 
     * @return void Esta função não retorna valor diretamente, mas envia uma resposta JSON com:
     *              - status (string): 'success', 'warning' ou 'error'
     *              - message (string): Mensagem descritiva do resultado
     * 
     * @example Exemplo de uso:
     * <code>
     * $params = '{
     *     "data_inicio": "25/12/2023 14:30",
     *     "data_finalizacao": "31/12/2023 18:00",
     *     "equipe": "Equipe_Técnica",
     *     "id": 123
     * }';
     * updateOrigemCalendario($params);
     * 
     * // Resposta de sucesso:
     * {"status":"success","message":"Registro atualizado com sucesso!"}
     * </code>
     * 
     * @throws PDOException Em caso de erro na execução da query, registra o erro no log
     *             (/var/tmp/my-errors.log) e retorna JSON com status 'error'
     * 
     * @uses c_banco_pdo() Classe de conexão com o banco de dados via PDO
     * @link https://www.php.net/manual/pt_BR/class.pdo.php Documentação do PDO
     */
    public function updateOrigemCalendario($params)
    {
        $param_decode = json_decode($params);
        // Convertendo datas do input para formato MySQL
        $data_abertura_obj = DateTime::createFromFormat('d/m/Y H:i', $param_decode->data_inicio);
        $data_finalizacao_obj = DateTime::createFromFormat('d/m/Y H:i', $param_decode->data_finalizacao);

        // Formatação para o SQL
        $data_abertura_formatada = $data_abertura_obj->format('Y-m-d H:i:s');
        $data_finalizacao_formatada = $data_finalizacao_obj->format('Y-m-d H:i:s');

        try {
            $this->banco = new c_banco_pdo();
            
            // Inicia a transação
            $this->banco->beginTransaction();

            // Atualiza o atendimento
            $sql = "UPDATE CAT_ATENDIMENTO SET 
                    DATAABERATEND = :data_abertura,
                    PRAZOENTREGA = :prazo_entrega,
                    USRABERTURA = :usr_abertura 
                    WHERE ID = :id";

            $this->banco->prepare($sql);
            
            // Parâmetros para o binding do atendimento
            $params = [
                ':data_abertura' => $data_abertura_formatada,
                ':prazo_entrega' => $data_finalizacao_formatada,
                ':usr_abertura' => $param_decode->equipe,
                ':id' => $param_decode->id
            ];

            $execute = $this->banco->execute($params);

            if ($execute) {
                // Remove usuários antigos
                $sql_delete = "DELETE FROM CAT_ATENDIMENTO_USUARIOS WHERE ATENDIMENTO_ID = :atendimento_id";
                $this->banco->prepare($sql_delete);
                $this->banco->execute([':atendimento_id' => $param_decode->id]);

                // Insere novos usuários
                if (!empty($param_decode->usuarios)) {
                    $sql_insert = "INSERT INTO CAT_ATENDIMENTO_USUARIOS (ATENDIMENTO_ID, USUARIO) VALUES (:atendimento_id, :usuario)";
                    $this->banco->prepare($sql_insert);
                    
                    foreach ($param_decode->usuarios as $usuario) {
                        $this->banco->execute([
                            ':atendimento_id' => $param_decode->id,
                            ':usuario' => $usuario
                        ]);
                    }
                }

                // Commit da transação
                $this->banco->commit();

                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    "status" => 'success',
                    'message' => 'Registro atualizado com sucesso!'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new PDOException('Falha na execução da query');
            }

            exit;
        } catch (PDOException $e) {
            // Em caso de erro, faz rollback
            if ($this->banco) {
                $this->banco->rollBack();
            }

            // DEV verifique o erro nesse caminho /var/tmp/my-errors.log
            error_log("Erro na consulta: " . $e->getMessage());

            // Resposta para o cliente
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Erro no banco de dados, entre em contato com o suporte'
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
