<?php

/**
 * @package   astec
 * @name      c_contrato
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      17/04/2025
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../class/ped/c_pedido_ps.php");
include_once($dir . "/../../class/cat/c_atendimento.php");

//Class c_contrato
class c_contrato extends c_user
{


    //construtor
    function __construct()
    {
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);
    }

    function buscarServicosDoPedido($id_pedido)
    {
        $objPedidoVenda = new c_contrato();
        $pedido = $objPedidoVenda->select_servicos_atendimento($id_pedido);
        if (!empty($lanc)) {
            foreach ($lanc as &$pedido) {
                $pedido['SERVICOS'] = $this->buscarServicosDoPedido($pedido['ID']);
            }
        }
        return $pedido;
    } // fim buscarServicosDoPedido


    public function select_servicos_atendimento($id_pedido)
    {
        $sql = "SELECT * FROM CAT_ATENDIMENTO C ";
        $sql .= "INNER JOIN FAT_PEDIDO_SERVICO S ON C.PEDIDO_ID = S.FAT_PEDIDO_ID ";
        $sql .= "WHERE (S.FAT_PEDIDO_ID = '" . $id_pedido . "') ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function select_servicos_pedido($id_pedido)
    {
        try {

            $sql = "SELECT 
                        CA.ID as ID,
                        CONCAT(
                            LPAD(DAY(CA.DATAABERATEND), 2, '0'), '/', 
                            LPAD(MONTH(CA.DATAABERATEND), 2, '0'), '/', 
                            YEAR(CA.DATAABERATEND), ' ', 
                            LPAD(HOUR(CA.DATAABERATEND), 2, '0'), ':', 
                            LPAD(MINUTE(CA.DATAABERATEND), 2, '0')
                        ) AS DATA_INICIO_SERVICO,
                        CONCAT(
                            LPAD(DAY(CA.PRAZOENTREGA), 2, '0'), '/', 
                            LPAD(MONTH(CA.PRAZOENTREGA), 2, '0'), '/', 
                            YEAR(CA.PRAZOENTREGA), ' ', 
                            LPAD(HOUR(CA.PRAZOENTREGA), 2, '0'), ':', 
                            LPAD(MINUTE(CA.PRAZOENTREGA), 2, '0')
                        ) AS DATA_FINALIZA_SERVICO,
                        E.DESCRICAO AS EQUIPE 
                    FROM CAT_ATENDIMENTO CA 
                    LEFT JOIN AMB_EQUIPE E ON E.ID = CA.EQUIPE_ID ";
            $sql .= "WHERE PEDIDO_ID = '" . $id_pedido . "';";
            //echo strtoupper($sql)."<BR>";
            $banco = new c_banco;
            $banco->exec_sql($sql);
            $banco->close_connection();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($banco->resultado, JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    public function select_servicos_atendimento_ajax($id_pedido)
    {
        try {
            if (empty($id_pedido)) {
                throw new Exception('ID do pedido não pode ser vazio.');
            }

            $sql = "SELECT 
                    S.QUANTIDADE,
                    S.ID,
                    S.DESCSERVICO,
                    P.PRAZOENTREGA, 
                    P.CLIENTE, 
                    U.NOME,
                    COALESCE(SUM(CAS.QUANTIDADE_EXECUTADA), 0) as QUANTIDADE_EXECUTADA_OS,
                    (S.QUANTIDADE - COALESCE(SUM(CAS.QUANTIDADE_EXECUTADA), 0)) as SALDO,
                    CASE 
                        WHEN S.QUANTIDADE > 0 THEN 
                            ROUND((COALESCE(SUM(CAS.QUANTIDADE_EXECUTADA), 0) / S.QUANTIDADE) * 100, 2)
                        ELSE 0 
                    END as PERCENTUAL_EXECUTADO
                FROM FAT_PEDIDO_SERVICO S 
                INNER JOIN FAT_PEDIDO P ON P.ID = S.FAT_PEDIDO_ID 
                INNER JOIN FIN_CLIENTE U ON P.CLIENTE = U.CLIENTE 
                LEFT JOIN CAT_ATENDIMENTO CA ON CA.PEDIDO_ID = S.FAT_PEDIDO_ID
                LEFT JOIN CAT_AT_SERVICOS CAS ON CAS.CAT_ATENDIMENTO_ID = CA.ID 
                    AND CAS.CAT_SERVICOS_ID = S.CAT_SERVICOS_ID
                WHERE S.FAT_PEDIDO_ID = :id_pedido
                GROUP BY 
                    S.ID, 
                    S.QUANTIDADE,
                    S.DESCSERVICO,
                    P.PRAZOENTREGA, 
                    P.CLIENTE, 
                    U.NOME";

            $banco = new c_banco_pdo();
            $banco->prepare($sql);
            $banco->bindParam(':id_pedido', $id_pedido);
            $banco->execute();

            $resultado = $banco->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }




    public function geraOsPedido($id_pedido, $id_cliente, $equipe, $m_userid, $data_inicio, $prazo_entrega, $id_servico, $quantidade, $obs_servico)
    {
        // Query SQL ajustada
        $sql = "INSERT INTO CAT_ATENDIMENTO (PEDIDO_ID, CLIENTE, USRABERTURA, VALORSERVICOS, DATAABERATEND, PRAZOENTREGA, QUANTIDADE, 
            OBSSERVICO, CONDPGTO, CAT_SITUACAO_ID, CREATED_USER, CREATED_AT )";
        $sql .= "VALUES ('$id_pedido', '$id_cliente', '$equipe', '$id_servico', '$data_inicio','$prazo_entrega', 
            '$quantidade', '$obs_servico', '" . $this->condpgto . "', '" . $this->cat_situacao_id . "', '" . $m_userid . "', NOW()
        )";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $sql = $sql;
        $banco->close_connection();
    }

    public function cadastraOrdemServico($params)
    {
        $dados = json_decode($params, true);

        $obj_pedido_ps = new c_pedido_ps;
        $obj_pedido_ps->setId($dados[0]["id_pedido"]);
        $array_pedido = $obj_pedido_ps->select_pedido_id();

        // monta a Os
        $obj_atendimento = new c_atendimento;
        $obj_atendimento->setCliente($array_pedido[0]['CLIENTE']);
        $obj_atendimento->setContato($array_pedido[0]['CONTATO']);
        $obj_atendimento->setValorServicos(''); // Setting empty value initially
        $obj_atendimento->setValorDesconto($array_pedido[0]['DESCONTO']);
        $obj_atendimento->setDescEquipamento($array_pedido[0]['DESCEQUIPAMENTO']);
        $obj_atendimento->setCondPgto($array_pedido[0]['CONDPG']);
        $obj_atendimento->setSituacao(2);
        $obj_atendimento->setValorPecas($array_pedido[0]['']);
        $obj_atendimento->setValorVisita($array_pedido[0]['']);
        $obj_atendimento->setDataFechamentoEnd('');
        $obj_atendimento->setEquipeId($dados[0]["equipe"]);
        $obj_atendimento->setCatEquipamentoId($dados[0]["equipamento"]);
        $obj_atendimento->setCatTipoId(2);
        $obj_atendimento->setPedidoId($dados[0]["id_pedido"]);
        $obj_atendimento->setUsrAbertura($this->m_userid);
        $obj_atendimento->setDataAberturaEnd($dados[0]["data_inicio"]);
        $obj_atendimento->setPrazoEntrega($dados[0]["prazo_entrega"]);
        $obj_atendimento->setObsServicos($dados[0]["obs_servico"]);
        $obj_atendimento->setCentroCusto($this->m_empresacentrocusto);


        $obj_banco = new c_banco;
        $obj_banco->inicioTransacao($obj_banco->id_connection);

        //inclui Os
        $id_os = $obj_atendimento->incluiAtendimento();

        try {
            if (!is_int($id_os)) {
                throw new Exception("Falha ao inserir Ordem de Servico ");
            }

            // Adiciona as equipes ao atendimento
            $this->updateCatAtEquipe(
                $dados[0]["usuario_equipe"] ?? null,
                $id_os,
                $dados[0]["equipe"] ?? null
            );

            // Variavel para acumular o valor total dos serviços
            $valor_total_servicos = 0;

            foreach ($dados[0]["servicos_selecionados"] as $servico) {
                // define o id do serviço
                $id_servico = $servico["id_servico"];

                // busca os serviços do pedido
                $obj_pedido_ps->setIdPedidoServico($dados[0]["id_pedido"]);
                $obj_pedido_ps->setCatServicoId($id_servico);
                $array_servico = $obj_pedido_ps->select_pedido_todos_id_servico();

                if ($servico['qtd_a_executar'] <= '0,00') {
                    throw new Exception("Quantidade do serviço selecionado não pode ser zero ou negativa.");    

                }

                // set valores de cada servico
                $obj_atendimento->setCatServicoId(intval($array_servico[0]['CAT_SERVICOS_ID']));

                // Use the same teams for each service
                $obj_atendimento->setIdUser($this->m_userid);
                $obj_atendimento->setQuantidadeServico($array_servico[0]['QUANTIDADE']);
                $obj_atendimento->setUnidadeServico($array_servico[0]['UNIDADE']);
                $obj_atendimento->setVlrUnitarioServico($array_servico[0]['VALUNITARIO']);
                $obj_atendimento->setDescricaoServico($array_servico[0]['DESCSERVICO']);

                $obj_atendimento->setIdAtendimentoServico($id_os);
                $obj_atendimento->setQuantidadeExecutada($servico['qtd_a_executar']);

                // Calcula o total do serviço multiplicando o valor unitário pela quantidade a executar
                $total_servico = $array_servico[0]['VALUNITARIO'] * $servico['qtd_a_executar'];
                $obj_atendimento->setTotalServico($total_servico);
                $valor_total_servicos += $total_servico; // Acumula o valor total

                // inclui servico
                $id_servico_os = $obj_atendimento->incluiServicosPdo($obj_banco->id_connection);

                if (!$id_servico_os) {
                    throw new Exception("Falha ao inserir serviço na Os ");
                }
            }

            // Atualiza o valor total dos serviços após inserir todos os serviços
            $this->atualizaValorTotalServicos($id_os, $valor_total_servicos);

            // commitando o resultado
            $obj_banco->commit($obj_banco->id_connection);
            // fechando a conexão
            $obj_banco->close_connection($obj_banco->id_connection);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => 'Ordem de serviço ' . $id_os . ' cadastrada'], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            $obj_banco->rollback($obj_banco->id_connection);
            $obj_banco->close_connection($obj_banco->id_connection);

            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Atualiza o valor total dos serviços de um atendimento
     * @param int $id_os ID do atendimento
     * @param float $valor_total Valor total dos serviços
     * @return bool
     * @throws Exception
     */
    public function atualizaValorTotalServicos($id_os, $valor_total)
    {
        if (empty($id_os)) {
            throw new Exception("ID do atendimento não pode ser vazio.");
        }

        $obj_banco = new c_banco_pdo;
        $sql = "UPDATE CAT_ATENDIMENTO SET VALORSERVICOS = ? WHERE ID = ?";

        $obj_banco->prepare($sql);
        $obj_banco->bindParam(1, $valor_total);
        $obj_banco->bindParam(2, $id_os);

        if (!$obj_banco->execute()) {
            throw new Exception("Erro ao atualizar valor total dos serviços do atendimento " . $id_os);
        }

        return true;
    }

    public function updateCatAtEquipe($usuarios_equipe, $id_os, $equipe_id)
    {
        if (empty($id_os)) {
            throw new Exception("ID do atendimento não pode ser vazio.");
        }

        // Se não houver equipe ou usuários para vincular, não faz nada
        if ($equipe_id === null || $usuarios_equipe === null) {
            return true;
        }

        $obj_banco = new c_banco_pdo;

        // Normaliza para array
        if (!is_array($usuarios_equipe)) {
            $usuarios_equipe = explode(',', $usuarios_equipe);
        }

        // Processa cada usuário (apenas inserção, sem deletar existentes)
        foreach ($usuarios_equipe as $usuario_id) {
            if (empty($usuario_id)) {
                continue;
            }

            // Verifica se já existe a relação para evitar duplicação
            $sql_check = "SELECT 1 FROM CAT_AT_EQUIPE_USUARIO 
                    WHERE ID_EQUIPE = ? AND ID_USUARIO = ? AND CAT_ATENDIMENTO_ID = ?";
            $obj_banco->prepare($sql_check);
            $obj_banco->bindParam(1, $equipe_id);
            $obj_banco->bindParam(2, $usuario_id);
            $obj_banco->bindParam(3, $id_os);
            $obj_banco->execute();

            if (!$obj_banco->fetch()) {
                // Insere nova relação apenas se não existir
                $sql_insert = "INSERT INTO CAT_AT_EQUIPE_USUARIO 
                         (ID_EQUIPE, ID_USUARIO, CAT_ATENDIMENTO_ID, CREATED_USER, CREATED_AT) 
                         VALUES (?, ?, ?, ?, NOW())";

                $obj_banco->prepare($sql_insert);
                $obj_banco->bindParam(1, $equipe_id);
                $obj_banco->bindParam(2, $usuario_id);
                $obj_banco->bindParam(3, $id_os);
                $obj_banco->bindParam(4, $this->m_userid);

                if (!$obj_banco->execute()) {
                    throw new Exception("Erro ao inserir usuário na equipe: " . $usuario_id);
                }
            }
        }

        return true;
    }
}

//	END OF THE CLASS
