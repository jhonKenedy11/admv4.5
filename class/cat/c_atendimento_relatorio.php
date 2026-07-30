<?php

/**
 * @package   admv4.5
 * @name      c_atendimento_relatorio
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva <joshua.silva@admsistemas.com.br>
 * @date      14/05/2025
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../bib/c_database_pdo.php");



//Class c_pedido_venda_relatorios
class c_atendimento_relatorio extends c_user
{

    /**
     * METODOS DE SETS E GETS
     */
    function getCentrocusto()
    {
        return $this->centroCusto;
    }

    function setCentrocusto($centroCusto)
    {
        $this->centroCusto = $centroCusto;
    }

    function getDataConsulta()
    {
        return $this->data_consulta;
    }

    function setDataConsulta($data_consulta)
    {
        $this->data_consulta = $data_consulta;
    }

    function getStatus()
    {
        return $this->status;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    function getEquipamento()
    {
        return $this->equipamento;
    }

    function setEquipamento($equipamento)
    {
        $this->equipamento = $equipamento;
    }

    function getServico()
    {
        return $this->id_servico;
    }

    function setServico($id_servico)
    {
        $this->id_servico = $id_servico;
    }

    function getIdVendedor()
    {
        return $this->idVendedor;
    }

    function setIdVendedor($idVendedor)
    {
        $this->idVendedor = $idVendedor;
    }


    //############### FIM SETS E GETS ###############

    /**
     * Consulta atendimentos com base em múltiplos filtros
     * 
     * @param array $params Array com parâmetros de filtro:
     *              - data_ini (string): Data inicial (formato 'DD/MM/YYYY')
     *              - data_fim (string): Data final (formato 'DD/MM/YYYY')
     *              - usuario (int) [opcional]: ID do usuário
     *              - id_servico (int) [opcional]: ID do serviço
     *              - equipamento (int) [opcional]: ID do equipamento
     *              - id_status (int) [opcional]: ID do status
     *              - centro_custo (int) [opcional]: ID do centro de custo
     *              - num_pedido (int) [opcional]: Número do pedido
     *              - num_os (int) [opcional]: Número da OS
     *              - cliente_id (int) [opcional]: ID do cliente
     * 
     * @return array Resultados formatados com dados dos atendimentos
     * @throws Exception Em caso de erro na consulta
     */
    public function selectRelatorioAtendimento($params)
    {
        $usuario = $params["usuario"];
        $id_servico = $params["id_servico"];
        $equipamento = $params["equipamento"];
        $id_status = $params["id_status"];
        $centro_custo = $params["centro_custo"];
        $num_pedido = $params["num_pedido"];
        $num_os = $params["num_os"];
        $cliente_id = $params["cliente_id"];
        $data_fim = c_date::convertDateBd($params["data_fim"]);
        $data_ini = c_date::convertDateBd($params["data_ini"]);

        $sql = "SELECT 
        U.NOMEREDUZIDO AS USUARIO, 
        SE.DESCRICAO AS SERVICO,
        E.DESCRICAO AS EQUIPAMENTO,  
        S.DESCRICAO AS STATUSDESC,
        S.ID AS STATUS_ID,
        C.DESCRICAO AS CENTRO_CUSTO, 
        A.PEDIDO_ID AS NUM_PEDIDO, 
        A.ID AS NUM_OS, 
        F.NOME AS CLIENTE, 
        A.DATAABERATEND AS DATA_ABERTURA, 
        A.DATAFECHATEND AS DATA_FECHAMENTO,
        A.VALORSERVICOS AS VALOR_OS_TOTAL,
        SAT.TOTALSERVICO AS VALOR_SERVICO,
        SAT.VALUNITARIO AS VALOR_UNITARIO,
        SAT.QUANTIDADE AS QUANTIDADE_SERVICO,
        SAT.QUANTIDADE_EXECUTADA AS QUANTIDADE_EXECUTADA
        FROM CAT_ATENDIMENTO A  
        LEFT JOIN FIN_CENTRO_CUSTO C ON A.CENTROCUSTO = C.CENTROCUSTO
        LEFT JOIN CAT_SITUACAO S ON S.ID = A.CAT_SITUACAO_ID 
        LEFT JOIN CAT_EQUIPAMENTO E ON E.ID = A.CAT_EQUIPAMENTO_ID
        LEFT JOIN AMB_USUARIO U ON U.USUARIO = A.USRABERTURA
        LEFT JOIN FIN_CLIENTE F ON F.CLIENTE = A.CLIENTE
        LEFT JOIN CAT_AT_SERVICOS SAT ON SAT.CAT_ATENDIMENTO_ID = A.ID
        LEFT JOIN CAT_SERVICO SE ON SE.ID = SAT.CAT_SERVICOS_ID 
        WHERE A.DATAABERATEND BETWEEN :data_ini AND :data_fim ";

        if (!empty($usuario)) {
            $sql .= " AND A.USRABERTURA = :usuario";
        }
        if (!empty($id_servico)) {
            $sql .= " AND SAT.CAT_SERVICOS_ID = :id_servico";
        }
        if (!empty($equipamento)) {
            $sql .= " AND A.CAT_EQUIPAMENTO_ID = :equipamento";
        }
        if (!empty($id_status)) {
            $sql .= " AND A.CAT_SITUACAO_ID = :id_status";
        }
        if (!empty($centro_custo)) {
            $sql .= " AND A.CENTROCUSTO = :centro_custo";
        }
        if (!empty($num_pedido)) {
            $sql .= " AND A.PEDIDO_ID = :num_pedido";
        }
        if (!empty($num_os)) {
            $sql .= " AND A.ID = :num_os";
        }
        if (!empty($cliente_id)) {
            $sql .= " AND A.CLIENTE = :cliente_id";
        }

        try {
            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);

            // Bind dos parâmetros
            if (!empty($data_ini)) {
                $this->banco->bindValue(":data_ini", $data_ini);
            }
            if (!empty($data_fim)) {
                $this->banco->bindValue(":data_fim", $data_fim);
            }
            if (!empty($usuario)) {
                $this->banco->bindValue(":usuario", $usuario);
            }
            if (!empty($id_servico)) {
                $this->banco->bindValue(":id_servico", $id_servico);
            }
            if (!empty($equipamento)) {
                $this->banco->bindValue(":equipamento", $equipamento);
            }
            if (!empty($id_status)) {
                $this->banco->bindValue(":id_status", $id_status);
            }
            if (!empty($centro_custo)) {
                $this->banco->bindValue(":centro_custo", $centro_custo);
            }
            if (!empty($num_pedido)) {
                $this->banco->bindValue(":num_pedido", $num_pedido);
            }
            if (!empty($num_os)) {
                $this->banco->bindValue(":num_os", $num_os);
            }
            if (!empty($cliente_id)) {
                $this->banco->bindValue(":cliente_id", $cliente_id);
            }


            //$teste = $this->banco->rowCount();
            //$query = $this->banco->queryString();

            // se nao existir registro ira mandar o array vazio
            $this->banco->execute();

            $resultados = [];

            if ($this->banco->rowCount() > 0) {

                while ($row_events = $this->banco->fetch(PDO::FETCH_ASSOC)) {
                    $valor_servico = $row_events['VALOR_SERVICO'] ?? $row_events['VALOR_OS_TOTAL'] ?? 0;
                    
                    $resultados[] = [
                        'num_os' => $row_events['NUM_OS'] ?? null,
                        'id_servico' => $row_events['SERVICO'] ?? '-',
                        'cliente' => $row_events['CLIENTE'] ?? '-',
                        'data_abertura' => $row_events['DATA_ABERTURA'] ?? null,
                        'data_fechamento' => $row_events['DATA_FECHAMENTO'] ?? null,
                        'usuario' => $row_events['USUARIO'] ?? '-',
                        'status' => $row_events['STATUSDESC'] ?? '-',
                        'status_id' => $row_events['STATUS_ID'] ?? null,
                        'num_pedido' => $row_events['NUM_PEDIDO'] ?? null,
                        'equipamento' => $row_events['EQUIPAMENTO'] ?? '-',
                        'centro_custo' => $row_events['CENTRO_CUSTO'] ?? '-',
                        'valor_servicos' => $valor_servico,
                        'valor_unitario' => $row_events['VALOR_UNITARIO'] ?? 0,
                        'quantidade_servico' => $row_events['QUANTIDADE_SERVICO'] ?? 0,
                        'quantidade_executada' => $row_events['QUANTIDADE_EXECUTADA'] ?? 0,
                    ];
                }
            }

            return $resultados;
        } catch (Exception $e) {
            error_log("Erro ao gerar relatório: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Consulta relatório de período detalhado com informações completas
     * 
     * @param array $params Array com parâmetros de filtro:
     *              - data_ini (string): Data inicial (formato 'DD/MM/YYYY')
     *              - data_fim (string): Data final (formato 'DD/MM/YYYY')
     *              - usuario (int) [opcional]: ID do usuário
     *              - id_servico (int) [opcional]: ID do serviço
     *              - equipamento (int) [opcional]: ID do equipamento
     *              - id_status (int) [opcional]: ID do status
     *              - centro_custo (int) [opcional]: ID do centro de custo
     *              - num_pedido (int) [opcional]: Número do pedido
     *              - num_os (int) [opcional]: Número da OS
     *              - cliente_id (int) [opcional]: ID do cliente
     *              - ordenacao (string) [opcional]: Tipo de ordenação (padrão: 'situacao_cliente_os')
     * 
     * @return array Resultados formatados com dados detalhados do relatório
     * @throws Exception Em caso de erro na consulta
     */
    public function selectRelatorioPeriodoDetalhado($params)
    {
        $usuario = $params["usuario"];
        $id_servico = $params["id_servico"];
        $equipamento = $params["equipamento"];
        $id_status = $params["id_status"];
        $centro_custo = $params["centro_custo"];
        $num_pedido = $params["num_pedido"];
        $num_os = $params["num_os"];
        $cliente_id = $params["cliente_id"];
        $ordenacao = isset($params["ordenacao"]) ? $params["ordenacao"] : '1';
        $data_fim = c_date::convertDateBd($params["data_fim"]);
        $data_ini = c_date::convertDateBd($params["data_ini"]);

        $sql = "SELECT 
        F.NOME AS CLIENTE,
        CO.PROJETO AS OBRA,
        SE.DESCRICAO AS TIPO_SERVICO,
        SAT.UNIDADE AS UNIDADE_SERVICO,
        PS.QUANTIDADE AS QUANTIDADE_CONTRATADA,
        PS.OBSSERVICO,
        SAT.QUANTIDADE AS QUANTIDADE_EXECUTADA,
        SAT.VALUNITARIO AS CUSTO_UNITARIO,
        SAT.TOTALSERVICO AS TOTAL_FATURADO_SERVICO,
        P.TOTAL AS TOTAL_CONTRATO,
        S.DESCRICAO AS SITUACAO_OS,
        S.ID AS STATUS_ID,
        A.ID AS NUM_OS,
        A.PEDIDO_ID AS NUM_PEDIDO,
        A.DATAABERATEND AS DATA_ABERTURA,
        A.DATAFECHATEND AS DATA_FECHAMENTO,
        U.NOMEREDUZIDO AS USUARIO,
        E.DESCRICAO AS EQUIPAMENTO,
        C.DESCRICAO AS CENTRO_CUSTO
        FROM CAT_ATENDIMENTO A  
        LEFT JOIN FIN_CENTRO_CUSTO C ON A.CENTROCUSTO = C.CENTROCUSTO
        LEFT JOIN CAT_SITUACAO S ON S.ID = A.CAT_SITUACAO_ID 
        LEFT JOIN CAT_EQUIPAMENTO E ON E.ID = A.CAT_EQUIPAMENTO_ID
        LEFT JOIN AMB_USUARIO U ON U.USUARIO = A.USRABERTURA
        LEFT JOIN FIN_CLIENTE F ON F.CLIENTE = A.CLIENTE
        LEFT JOIN FAT_PEDIDO P ON P.ID = A.PEDIDO_ID
        LEFT JOIN FIN_CLIENTE_OBRA CO ON CO.CLIENTE = A.CLIENTE AND CO.STATUS = 'A' AND P.OBRA_ID = CO.ID
        LEFT JOIN CAT_AT_SERVICOS SAT ON SAT.CAT_ATENDIMENTO_ID = A.ID
        LEFT JOIN CAT_SERVICO SE ON SE.ID = SAT.CAT_SERVICOS_ID
        LEFT JOIN FAT_PEDIDO_SERVICO PS ON PS.ID = SAT.FAT_PEDIDO_SERVICO_ID
        WHERE A.DATAABERATEND IS NOT NULL 
        AND A.DATAABERATEND BETWEEN :data_ini AND :data_fim ";

        if (!empty($usuario)) {
            $sql .= " AND A.USRABERTURA = :usuario";
        }
        if (!empty($id_servico)) {
            $sql .= " AND SAT.CAT_SERVICOS_ID = :id_servico";
        }
        if (!empty($equipamento)) {
            $sql .= " AND A.CAT_EQUIPAMENTO_ID = :equipamento";
        }
        if (!empty($id_status)) {
            $sql .= " AND A.CAT_SITUACAO_ID = :id_status";
        }
        if (!empty($centro_custo)) {
            $sql .= " AND A.CENTROCUSTO = :centro_custo";
        }
        if (!empty($num_pedido)) {
            $sql .= " AND A.PEDIDO_ID = :num_pedido";
        }
        if (!empty($num_os)) {
            $sql .= " AND A.ID = :num_os";
        }
        if (!empty($cliente_id)) {
            $sql .= " AND A.CLIENTE = :cliente_id";
        }

        // Aplica ordenação dinâmica
        $sql .= " ORDER BY " . $this->getOrderByClause($ordenacao);

        try {
            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);

            // Bind dos parâmetros obrigatórios
            $this->banco->bindValue(":data_ini", $data_ini);
            $this->banco->bindValue(":data_fim", $data_fim);
            
            // Bind dos parâmetros opcionais
            if (!empty($usuario)) {
                $this->banco->bindValue(":usuario", $usuario);
            }
            if (!empty($id_servico)) {
                $this->banco->bindValue(":id_servico", $id_servico);
            }
            if (!empty($equipamento)) {
                $this->banco->bindValue(":equipamento", $equipamento);
            }
            if (!empty($id_status)) {
                $this->banco->bindValue(":id_status", $id_status);
            }
            if (!empty($centro_custo)) {
                $this->banco->bindValue(":centro_custo", $centro_custo);
            }
            if (!empty($num_pedido)) {
                $this->banco->bindValue(":num_pedido", $num_pedido);
            }
            if (!empty($num_os)) {
                $this->banco->bindValue(":num_os", $num_os);
            }
            if (!empty($cliente_id)) {
                $this->banco->bindValue(":cliente_id", $cliente_id);
            }

            $this->banco->execute();

            $resultados = [];

            if ($this->banco->rowCount() > 0) {
                while ($row_events = $this->banco->fetch(PDO::FETCH_ASSOC)) {
                    $resultados[] = [
                        'cliente' => $row_events['CLIENTE'] ?? '',
                        'obra' => $row_events['OBRA'] ?? '',
                        'tipo_servico' => $row_events['TIPO_SERVICO'] ?? '',
                        'unidade_servico' => $row_events['UNIDADE_SERVICO'] ?? '',
                        'obs_servico' => $row_events['OBSSERVICO'] ?? '',
                        'quantidade_contratada' => floatval($row_events['QUANTIDADE_CONTRATADA'] ?? 0),
                        'quantidade_executada' => floatval($row_events['QUANTIDADE_EXECUTADA'] ?? 0),
                        'custo_unitario' => floatval($row_events['CUSTO_UNITARIO'] ?? 0),
                        'total_faturado_servico' => floatval($row_events['TOTAL_FATURADO_SERVICO'] ?? 0),
                        'total_contrato' => floatval($row_events['TOTAL_CONTRATO'] ?? 0),
                        'situacao_os' => $row_events['SITUACAO_OS'] ?? '',
                        'status_id' => $row_events['STATUS_ID'] ?? null,
                        'num_os' => $row_events['NUM_OS'] ?? null,
                        'num_pedido' => $row_events['NUM_PEDIDO'] ?? null,
                        'data_abertura' => $row_events['DATA_ABERTURA'] ?? null,
                        'data_fechamento' => $row_events['DATA_FECHAMENTO'] ?? null,
                        'usuario' => $row_events['USUARIO'] ?? '',
                        'equipamento' => $row_events['EQUIPAMENTO'] ?? '',
                        'centro_custo' => $row_events['CENTRO_CUSTO'] ?? '',
                    ];
                }
            }

            return $resultados;
        } catch (Exception $e) {
            error_log("Erro ao gerar relatório detalhado: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Parâmetros - data_ini: " . $data_ini . ", data_fim: " . $data_fim);
            return [];
        }
    }

    /**
     * Consulta medicao com base no numero do pedido
     * 
     * @param array $fixo do pedido_id, :
     *              - id_pedido (int) [necessário]: Número do pedido
     *              
     * 
     * @return array Resultados formatados com dados dos atendimentos
     * @throws Exception Em caso de erro na consulta
     */
    public function selectRelatorioMedicao($num_pedido)
    {
        $num_pedido = $num_pedido;


        $sql = "SELECT 
            RT.NOME AS RESPONSAVEL_TECNICO,
            RT_CLI.CELULAR AS FONE_RESPONSAVEL,
            RT_CLI.EMAIL AS EMAIL_RESPONSAVEL,
            RT_CLI.NOME AS NOME_COMPLETO_RESPONSAVEL,  
            RTC.NOME AS NOME_RESPONSAVEL_TECNICO,
            RTC.CPF AS CPF_RESPONSAVEL_TECNICO,
            RTC.CREA AS CREA_RESPONSAVEL_TECNICO,
            RTC.TELEFONE AS TELEFONE_RESPONSAVEL_TECNICO,
            RTC.EMAIL AS EMAIL_RESPONSAVEL_TECNICO,
            RTC.SITUACAO AS STATUS_RESPONSAVEL_TECNICO,
            SE.DESCRICAO AS SERVICO,
            E.DESCRICAO AS EQUIPE, 
            S.DESCRICAO AS STATUSDESC, 
            C.DESCRICAO AS CENTROCUSTO, 
            A.PEDIDO_ID AS NUM_PEDIDO, 
            A.ID AS NUM_OS, 
            A.VALORDESCONTO AS VALOR_DESCONTO,
            F.EMAIL AS EMAIL_CLIENTE, 
            F.FONE AS FONE_CLIENTE,
            F.NOME AS CLIENTE, 
            F.CNPJCPF AS DOCUMENTO_CLIENTE, 
            F.INSCESTRG AS INSC_ESTADUAL_CLIENTE,
            A.DATAABERATEND AS DATA_ABERTURA, 
            A.DATAFECHATEND AS DATA_FECHAMENTO,
            EM.NOMEEMPRESA, 
            EM.CNPJ AS CNPJ_EMPRESA,
            EM.INSCESTADUAL AS INSC_ESTADUAL_EMPRESA,
            SAT.UNIDADE, 
            SAT.DESCSERVICO, 
            SAT.QUANTIDADE, 
            SAT.QUANTIDADE_EXECUTADA, 
            SAT.VALUNITARIO,
            SAT.TOTALSERVICO, 
            SAT.ID AS SAT_ID, 
            PS.QUANTIDADE AS QUANTIDADE_CONTRATADA,
            PS.OBSSERVICO,
            CO.PROJETO AS OBRA,
            CO.CNO, CO.CREA, CO.ART,
            A.VALORSERVICOS
            FROM CAT_ATENDIMENTO A  
            LEFT JOIN FAT_PEDIDO P ON P.ID = A.PEDIDO_ID
            LEFT JOIN FIN_CENTRO_CUSTO C ON A.CENTROCUSTO = C.CENTROCUSTO
            LEFT JOIN AMB_EMPRESA EM ON EM.CENTROCUSTO = A.CENTROCUSTO
            LEFT JOIN AMB_EQUIPE E ON E.ID = A.EQUIPE_ID 
            LEFT JOIN CAT_SITUACAO S ON S.ID = A.CAT_SITUACAO_ID 
            LEFT JOIN FIN_CLIENTE F ON F.CLIENTE = A.CLIENTE          
            LEFT JOIN FIN_CLIENTE_OBRA CO ON CO.CLIENTE = A.CLIENTE AND CO.STATUS = 'A' AND P.OBRA_ID = CO.ID
            LEFT JOIN AMB_USUARIO RT ON RT.USUARIO = CO.RESPONSAVEL_TECNICO 
            LEFT JOIN FIN_CLIENTE RT_CLI ON RT_CLI.CLIENTE = RT.CLIENTE 
            LEFT JOIN AMB_RESPONSAVEL_TECNICO RTC ON RTC.ID = P.RESP_TECNICO
            LEFT JOIN CAT_AT_SERVICOS SAT ON SAT.CAT_ATENDIMENTO_ID = A.ID
            LEFT JOIN CAT_SERVICO SE ON SE.ID = SAT.CAT_SERVICOS_ID 
            LEFT JOIN FAT_PEDIDO_SERVICO PS ON PS.ID = SAT.FAT_PEDIDO_SERVICO_ID";

        if (!empty($num_pedido)) {
            $sql .= " WHERE A.PEDIDO_ID = :num_pedido";
        }

        try {
            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);

            // Bind dos parâmetros
            if (!empty($num_pedido)) {
                $this->banco->bindValue(":num_pedido", $num_pedido);
            }



            $this->banco->execute();

            $resultados = [];

            if ($this->banco->rowCount() > 0) {
                while ($row_events = $this->banco->fetch(PDO::FETCH_ASSOC)) {
                    $executada = $row_events['QUANTIDADE_EXECUTADA'] ?? 0;
                    $quantidade = $row_events['QUANTIDADE'] ?? 0;

                    $percentual = 0;
                    if ($quantidade > 0) {
                        $percentual = ($executada / $quantidade) * 100;
                    }
                    $percentual = ($percentual == 0) ? 100 : $percentual;

                    $row_events['PERCENTUAL_EXECUCAO'] = round($percentual, 2);

                    $resultados[] = $row_events;
                }
            }

            return $resultados;
        } catch (Exception $e) {
            error_log("Erro ao gerar relatório: " . $e->getMessage());
            return [];
        }
    }


    /**
     * Gera a cláusula ORDER BY baseada no ID de ordenação selecionado
     * 
     * @param int|string $ordenacaoId ID da ordenação selecionada (1-4)
     * @return string Cláusula ORDER BY para SQL
     */
    private function getOrderByClause($ordenacaoId)
    {
        // Converte para inteiro para garantir comparação correta
        $ordenacaoId = (int)$ordenacaoId;
        
        $orderByMap = [
            1 => 'F.NOME',      // Cliente
            2 => 'CO.PROJETO',  // Obra
            3 => 'SE.DESCRICAO', // Serviço
            4 => 'S.ID',        // Situação
        ];

        // Retorna a ordenação padrão (ID 1 - Cliente) se não encontrar a selecionada
        return isset($orderByMap[$ordenacaoId]) ? $orderByMap[$ordenacaoId] : $orderByMap[1];
    }

    /**
     * Carrega todos os combos para filtros do relatório
     * 
     * Inclui combos de: centro de custo, usuários, status, 
     * equipamentos e serviços
     * 
     * @return void
     */
    public function comboAtendimento()
    {
        $this->comboCentroCusto();
        $this->comboUsuario();
        $this->comboStatus();
        $this->comboEquipamento();
        $this->comboServicos();
        $this->comboOrdenacao();
    }

    /**
     * Gera combo de centros de custo ativos
     * 
     * Preenche arrays para template:
     * - centro_custo_ids: Valores dos IDs
     * - centro_custo_names: Descrições
     * 
     * @return void
     */
    public function comboCentroCusto()
    {
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        $centro_custo_ids[0] = '';
        $centro_custo_names[0] = 'Selecione um Centro de Custo';

        for ($i = 0; $i < count($result); $i++) {
            $centro_custo_ids[$i + 1] = $result[$i]['ID'];
            $centro_custo_names[$i + 1] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('centro_custo_ids', $centro_custo_ids);
        $this->smarty->assign('centro_custo_names', $centro_custo_names);
        $this->smarty->assign('centro_custo_id', $this->getCentroCusto());
    }


    /**
     * Gera combo de usuários ativos (equipes)
     * 
     * Preenche arrays para template:
     * - usuario_ids: Valores dos IDs
     * - usuario_names: Nomes reduzidos
     * 
     * @return void
     */
    public function comboUsuario()
    {
        $consulta = new c_banco();
        $sql = "select usuario as id, nomereduzido as descricao from amb_usuario where (situacao = 'A') order by nomereduzido";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $usuario_ids[0] = '';
        $usuario_names[0] = 'Selecione uma Equipe';
        for ($i = 0; $i < count($result); $i++) {
            $usuario_ids[$i + 1] = $result[$i]['ID'];
            $usuario_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('usuario_ids', $usuario_ids);
        $this->smarty->assign('usuario_names', $usuario_names);
        if ($this->m_par[4] == "") {
            $this->smarty->assign('usuario_id', 'Todos');
        } else {
            $this->smarty->assign('usuario_id', $this->m_par[4]);
        }
    }

    /**
     * Gera combo de status de atendimento ativos
     * 
     * Preenche arrays para template:
     * - id_status_ids: Valores dos IDs
     * - id_status_names: Descrições
     * 
     * @return void
     */
    public function comboStatus()
    {
        $consulta = new c_banco();
        $sql = "SELECT ID , DESCRICAO FROM CAT_SITUACAO ";
        $sql .= "WHERE ATIVO = '1'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        $id_status_ids[0] = '';
        $id_status_names[0] = 'Selecione um status';

        for ($i = 0; $i < count($result); $i++) {
            $id_status_ids[$i + 1] = $result[$i]['ID'];
            $id_status_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('id_status_ids', $id_status_ids);
        $this->smarty->assign('id_status_names', $id_status_names);
        $this->smarty->assign('id_status_id', $this->getStatus());
    }

    /**
     * Gera combo de Equipamentos cadastrados
     * 
     * Preenche arrays para template:
     * - equipamento_ids: Valores dos IDs
     * - equipamento_names: Descrições
     * 
     * @return void
     */
    public function comboEquipamento()
    {
        $consulta = new c_banco();
        $sql = "select id, descricao from cat_equipamento  order by descricao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $equipamento_ids[0] = '';
        $equipamento_names[0] = 'Selecione um Equipamento';
        for ($i = 0; $i < count($result); $i++) {
            $equipamento_ids[$i + 1] = $result[$i]['ID'];
            $equipamento_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('equipamento_ids', $equipamento_ids);
        $this->smarty->assign('equipamento_names', $equipamento_names);
        if ($this->m_par[4] == "") {
            $this->smarty->assign('equipamento_id', 'Todos');
        } else {
            $this->smarty->assign('equipamento_id', $this->getEquipamento());
        }
    }

    /**
     * Gera combo de serviços cadastrados
     * 
     * Preenche arrays para template:
     * - id_servico_ids: Valores dos IDs
     * - id_servico_names: Descrições
     * 
     * @return void
     */
    public function comboServicos()
    {
        $consulta = new c_banco();
        $sql = "select id, descricao from cat_servico  order by descricao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $id_servico_ids[0] = '';
        $id_servico_names[0] = 'Selecione um serviço';
        for ($i = 0; $i < count($result); $i++) {
            $id_servico_ids[$i + 1] = $result[$i]['ID'];
            $id_servico_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('id_servico_ids', $id_servico_ids);
        $this->smarty->assign('id_servico_names', $id_servico_names);
        if ($this->m_par[4] == "") {
            $this->smarty->assign('id_servico_id', 'Todos');
        } else {
            $this->smarty->assign('id_servico_id', $this->getServico());
        }
    }

    /**
     * Gera combo de opções de ordenação para relatórios
     * 
     * Preenche arrays para template:
     * - ordenacao_ids: Valores dos IDs (1-4)
     * - ordenacao_names: Descrições das opções
     * 
     * @return void
     */
    public function comboOrdenacao()
    {
        $ordenacao_ids = [];
        $ordenacao_names = [];

        $ordenacao_ids[0] = '1';
        $ordenacao_names[0] = 'Cliente';
        
        $ordenacao_ids[1] = '2';
        $ordenacao_names[1] = 'Obra';
        
        $ordenacao_ids[2] = '3';
        $ordenacao_names[2] = 'Serviço';
        
        $ordenacao_ids[3] = '4';
        $ordenacao_names[3] = 'Situação';

        $this->smarty->assign('ordenacao_ids', $ordenacao_ids);
        $this->smarty->assign('ordenacao_names', $ordenacao_names);
    }
}    //	END OF THE CLASS
