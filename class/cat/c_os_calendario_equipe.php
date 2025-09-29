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

class c_os_calendario_equipe extends c_user
{
    private $banco = NULL;

    public function __construct()
    {
        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);
    }

    /**
     * Consulta ordens de serviço dentro de um período específico filtrando por equipe
     * 
     * @param array $params Array com os parâmetros de consulta
     * @return void Retorna JSON com array de eventos no formato aninhado
     */
    /**
     * Consulta ordens de serviço dentro de um período específico filtrando por equipe
     * 
     * @param array $params Array com os parâmetros de consulta:
     *              - data_ini (string): Data inicial no formato 'YYYY-MM-DD'
     *              - data_fim (string): Data final no formato 'YYYY-MM-DD'
     *              - equipe (int|null) [opcional]: ID da equipe para filtro
     * 
     * @return void Retorna JSON com array de eventos no formato:
     *              {
     *                  id: [number],
     *                  start: [string],
     *                  end: [string],
     *                  details: {
     *                      title: [string],
     *                      color: [string],
     *                      equipe_id: [number],
     *                      situacao_descricao: [string],
     *                      cliente_descricao: [string],
     *                      usuario_equipe: [string]
     *                  }
     *              }
     */
    public function selectOrdemServico($params)
    {
        $data_ini = $params["data_ini"];
        $data_fim = $params["data_fim"];
        $equipe = $params["equipe"] ?? null;

        $sql = "SELECT
            CA.ID AS id,
            CA.OBSSERVICO AS title,
            CA.DATAABERATEND AS start,
            CA.PRAZOENTREGA AS end,
            CA.CAT_SITUACAO_ID AS status_id,
            CAE.ID_EQUIPE AS equipe_id,
            CS.DESCRICAO AS situacao_descricao,
            FC.NOME AS cliente_descricao,
            AE.COR_EQUIPE AS equipe_cor,
            (SELECT GROUP_CONCAT(DISTINCT CAE2.ID_USUARIO SEPARATOR ',')
                FROM CAT_AT_EQUIPE_USUARIO CAE2
                WHERE CAE2.CAT_ATENDIMENTO_ID = CA.ID) as usuario_equipe
        FROM CAT_ATENDIMENTO CA
        INNER JOIN CAT_SITUACAO CS ON CA.CAT_SITUACAO_ID = CS.ID
        INNER JOIN FIN_CLIENTE FC ON CA.CLIENTE = FC.CLIENTE
        LEFT JOIN CAT_AT_EQUIPE_USUARIO CAE ON CA.ID = CAE.CAT_ATENDIMENTO_ID
        LEFT JOIN AMB_USUARIO AU ON CAE.ID_USUARIO = AU.USUARIO
        LEFT JOIN AMB_EQUIPE AE ON CA.EQUIPE_ID = AE.ID
        WHERE CA.DATAABERATEND >= :data_ini
        AND CA.PRAZOENTREGA <= :data_fim ";

        if ($equipe !== "" && $equipe !== null) {
            $sql .= " AND CA.EQUIPE_ID = :equipe";
        }
        $sql .= " GROUP BY CA.ID, CAE.ID_EQUIPE, CS.DESCRICAO, FC.NOME, AE.COR_EQUIPE
            ORDER BY CA.DATAABERATEND ASC";

        try {
            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);
            $this->banco->bindValue(":data_ini", $data_ini);
            $this->banco->bindValue(":data_fim", $data_fim);

            if (!empty($equipe) && is_numeric($equipe)) {
                $this->banco->bindValue(":equipe", $equipe);
            }

            $this->banco->execute();

            $eventos = [];

            if ($this->banco->rowCount() > 0) {
                while ($row_events = $this->banco->fetch(PDO::FETCH_ASSOC)) {
                    $eventos[] = [
                        'id' => $row_events['id'],
                        'start' => $row_events['start'],
                        'end' => $row_events['end'],
                        'title' => $row_events['title'],
                        'color' => $row_events['equipe_cor'],
                        'details' => [
                            'equipe_id' => $row_events['equipe_id'],
                            'situacao_descricao' => $row_events['situacao_descricao'],
                            'cliente_descricao' => $row_events['cliente_descricao'],
                            'usuario_equipe' => $row_events['usuario_equipe']
                        ]
                    ];
                }
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            error_log("Erro na consulta: " . $e->getMessage());
            echo json_encode(['error' => 'Falha ao carregar eventos']);
            exit;
        }
    }

    /**
     * Atualiza informações de origem no calendário e equipes associadas
     * 
     * @param string $params JSON com os parâmetros de atualização
     * @return void Retorna JSON com status da operação
     */
    public function updateOrigemCalendario($params)
    {
        try {
            $param_decode = json_decode($params);

            // Validações básicas
            if (!isset($param_decode->id)) {
                throw new Exception("ID do atendimento não informado");
            }

            // Convertendo datas
            $data_abertura_obj = DateTime::createFromFormat('d/m/Y H:i', $param_decode->data_inicio);
            $data_finalizacao_obj = DateTime::createFromFormat('d/m/Y H:i', $param_decode->data_finalizacao);

            if (!$data_abertura_obj || !$data_finalizacao_obj) {
                throw new Exception("Formato de data inválido. Use DD/MM/YYYY HH:MM");
            }

            $this->banco = new c_banco_pdo();

            // 1. Atualiza dados principais do atendimento (incluindo equipe principal)
            $sql_atendimento = "UPDATE CAT_ATENDIMENTO SET 
            DATAABERATEND = :data_abertura,
            PRAZOENTREGA = :prazo_entrega,
            OBSSERVICO = :desc_servico,
            EQUIPE_ID = :equipe_id
            WHERE ID = :id";

            $this->banco->prepare($sql_atendimento);
            $this->banco->bindParam(':data_abertura', $data_abertura_obj->format('Y-m-d H:i:s'));
            $this->banco->bindParam(':prazo_entrega', $data_finalizacao_obj->format('Y-m-d H:i:s'));
            $this->banco->bindParam(':desc_servico', $param_decode->desc_servico);
            $this->banco->bindParam(':equipe_id', $param_decode->equipe);
            $this->banco->bindParam(':id', $param_decode->id);

            $this->banco->execute();

            // 2. Gerenciamento dos usuários da equipe
            if (isset($param_decode->equipe)) {
                // Validação: não pode ter usuários sem equipe
                if (empty($param_decode->equipe) && !empty($param_decode->usuario_equipe)) {
                    throw new Exception("Selecione uma equipe para adicionar usuários.");
                }

                // Remove TODOS os usuários deste atendimento (independente da equipe)
                // Isso garante que ao mudar de equipe, os antigos serão removidos
                $sql_delete_all = "DELETE FROM CAT_AT_EQUIPE_USUARIO 
                            WHERE CAT_ATENDIMENTO_ID = :atendimento_id";

                $this->banco->prepare($sql_delete_all);
                $this->banco->bindParam(':atendimento_id', $param_decode->id);
                $this->banco->execute();

                // Se foi informada uma equipe (não é null nem vazio)
                if (!empty($param_decode->equipe)) {
                    // Se houver usuários para adicionar
                    if (!empty($param_decode->usuario_equipe)) {
                        foreach ($param_decode->usuario_equipe as $usuario_id) {
                            $sql_insert = "INSERT INTO CAT_AT_EQUIPE_USUARIO 
                                    (ID_EQUIPE, ID_USUARIO, CAT_ATENDIMENTO_ID, CREATED_USER, CREATED_AT) 
                                    VALUES (:equipe_id, :usuario_id, :atendimento_id, :created_user, NOW())";

                            $this->banco->prepare($sql_insert);
                            $this->banco->bindParam(':equipe_id', $param_decode->equipe);
                            $this->banco->bindParam(':usuario_id', $usuario_id);
                            $this->banco->bindParam(':atendimento_id', $param_decode->id);
                            $this->banco->bindParam(':created_user', $this->m_userid);

                            $this->banco->execute();
                        }
                    }
                }
            }

            echo json_encode([
                "status" => 'success',
                'message' => 'Registro atualizado com sucesso!'
            ], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Erro: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
