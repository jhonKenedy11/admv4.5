<?php

/**
 * @package   astec
 * @name      c_apontamento_os_mobile
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      30/04/2025
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/cat/c_atendimento.php");


//Class c_apontamento_os_mobile
class c_apontamento_os_mobile extends c_user
{


    //construtor
    function __construct()
    {
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);
    }

    // metodos get e set
    public function setidOs($idOs)
    {
        $this->idOs = $idOs;
    }
    public function getidOs()
    {
        return $this->idOs;
    }

    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
    }

    public function getIdPedido()
    {
        return $this->idPedido;
    }

    public function setNomeCliente($nomeCliente)
    {
        $this->nomeCliente = $nomeCliente;
    }

    public function getNomeCliente()
    {
        return $this->nomeCliente;
    }

    public function setDescServico($descServico)
    {
        $this->descServico = $descServico;
    }

    public function getDescServico()
    {
        return $this->descServico;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setDataAbertura($dataAbertura)
    {
        $this->dataAbertura = $dataAbertura;
    }

    public function getDataAbertura()
    {
        return $this->dataAbertura;
    }

    public function setDataFechamento($dataFechamento)
    {
        $this->dataFechamento = $dataFechamento;
    }

    public function getDataFechamento()
    {
        return $this->dataFechamento;
    }

    public function setPrazoEntrega($prazoEntrega)
    {
        $this->prazoEntrega = $prazoEntrega;
    }

    public function getPrazoEntrega()
    {
        return $this->prazoEntrega;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setQtdExecutada($qtdExecutada)
    {
        $this->qtdExecutada = $qtdExecutada;
    }
    public function getQtdExecutada()
    {
        return $this->qtdExecutada;
    }

    public function setQtdSaldo($qtdSaldo)
    {
        $this->qtdSaldo = $qtdSaldo;
    }
    public function getQtdSaldo()
    {
        return $this->qtdSaldo;
    }

    public function setQtdContratada($qtdContratada)
    {
        $this->qtdContratada = $qtdContratada;
    }
    public function getQtdContratada()
    {
        return $this->qtdContratada;
    }

    public function setQtdExec($qtdExec)
    {
        $this->qtdExec = $qtdExec;
    }
    public function getQtdExec()
    {
        return $this->qtdExec;
    }

    public function setServicoId($id_servico)
    {
        $this->id_servico = $id_servico;
    }
    public function getServicoId()
    {
        return $this->id_servico;
    }



    public function select_atendimento_fechamento($numAtendimento)
    {
        $sql = "SELECT SA.ID,SA.DESCSERVICO, SA.QUANTIDADE, CL.NOME, AT.CLIENTE, AT.CAT_SITUACAO_ID, AT.DATAABERATEND, AT.DATAFECHATEND, AT.PRAZOENTREGA, AT.ID FROM CAT_AT_SERVICOS SA ";
        $sql .= "INNER JOIN CAT_ATENDIMENTO AT ON AT.ID = SA.CAT_ATENDIMENTO_ID ";
        $sql .= "INNER JOIN FIN_CLIENTE CL ON AT.CLIENTE = CL.CLIENTE ";
        $sql .= "WHERE AT.ID = '$numAtendimento';";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function selectServicoApontamento($numAtendimento)
    {
        $sql = "SELECT SA.* FROM CAT_AT_SERVICOS SA ";
        $sql .= "INNER JOIN CAT_ATENDIMENTO AT ON AT.ID = SA.CAT_ATENDIMENTO_ID ";
        $sql .= "WHERE AT.ID = '$numAtendimento';";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function incluiApontamento()
    {
        //variaveis vindo do front
        $servicos = $this->json_data = json_decode($_POST['json_data'], true);

        $data_finalizacao = c_date::convertDateBd($this->data_finalizacao);

        // set variaveis OS
        $this->setIdOs(intval($this->numero_os));
        $this->setDataFechamento($data_finalizacao);
        $this->setStatus($this->situacao);


        $obj_banco = new c_banco;
        $obj_banco->inicioTransacao($obj_banco->id_connection);

        try {

            if (is_int($this->numero_os)) {
                throw new Exception("Falha ao atualizar a Ordem de Servico ");
            }
            if (!empty($servicos)) {
                foreach ($servicos as $servico) {
                    $this->setServicoId($servico['id_servico']);
                    $this->setQtdExecutada($servico['quantidade_executada']);
                    $this->setQuantidade($servico['qtd_exec']);
                    $result = $this->incluiQuantidadeExecutadaServico();
                    if ($result != true) {
                        throw new Exception("Falha ao atualizar serviço na Os ");
                    }
                }

                $result = $this->atualizaOS();
                if ($result != true) {
                    throw new Exception("Falha ao atualizar serviço na Os ");
                }
            }

            // desmonta obj
            unset($this->json_data);


            // commitando o resultado
            $obj_banco->commit($obj_banco->id_connection);

            // fechando a conexão
            $obj_banco->close_connection($obj_banco->id_connection);

            return intval($this->numero_os);
        } catch (Exception $e) {

            $obj_banco->rollback($obj_banco->id_connection);
            $obj_banco->close_connection($obj_banco->id_connection);


            echo json_encode(['error' => $e->getMessage()]);
            return $e;
        }
    } //fim incluir apontamento, retornar msg de sucesso.


    public function incluiQuantidadeExecutadaServico()
    {
        //validacao suspensa de quandidade executada
        //$quantidade_contrato = $this->selectQuantidadeServicoContrato();
        // if($this->getQtdExecutada() <= $quantidade_contrato) {
        // Verifica se a quantidade executada é maior que a quantidade contratada

        $sql = "UPDATE CAT_AT_SERVICOS SET ";
        $sql .= "QUANTIDADE_EXECUTADA = '" . $this->getQtdExecutada() . "' ";
        $sql .= "WHERE ID = '" . $this->getServicoId() . "';";
        $banco = new c_banco;
        $result = $banco->exec_sql($sql);
        return $result;
        // }
        // else {
        //     $this->m_msg = "Quantidade informada maior que a quantidade contratada";
        //     $this->m_tipoMsg = 'error';
        //     return false;
        // }
    }

    // public function selectQuantidadeServicoContrato()
    // {
    //     $sql = "SELECT FA.QUANTIDADE FROM FAT_PEDIDO_SERVICO FA
    //     INNER JOIN CAT_ATENDIMENTO CA ON CA.PEDIDO_ID = FA.FAT_PEDIDO_ID
    //     INNER JOIN CAT_AT_SERVICOS SA ON SA.CAT_ATENDIMENTO_ID = CA.ID
    //     WHERE SA.ID = '" . $this->getServicoId() . "' 
    //     AND FA.CAT_SERVICOS_ID = SA.CAT_SERVICOS_ID";

    //     $banco = new c_banco;
    //     $banco->exec_sql($sql);
    //     $banco->close_connection();
    //     return $banco->resultado[0]['QUANTIDADE'];
    // }

    public function atualizaOS()
    {
        $sql = "UPDATE CAT_ATENDIMENTO SET ";
        $sql .= "DATAFECHATEND = '" . $this->getDataFechamento() . "', ";
        $sql .= "CAT_SITUACAO_ID = '" . $this->getStatus() . "' ";
        $sql .= "WHERE ID = '" . $this->getIdOs() . "';";
        $banco = new c_banco;
        $result = $banco->exec_sql($sql);
        return $result;
    }

    public function  cadastroApontamento()
    {
        $busca_os = $this->select_atendimento_fechamento($this->numAtendimento);
        $this->setIdOs($busca_os[0]['ID']);
        $this->setIdPedido($busca_os[0]['PEDIDO_ID']);
        $this->setNomeCliente($busca_os[0]['NOME']);
        $this->setDescServico($busca_os[0]['DESCSERVICO']);
        $this->setQuantidade($busca_os[0]['QUANTIDADE']);
        $this->setDataAbertura($busca_os[0]['DATAABERATEND']);
        $this->setDataFechamento($busca_os[0]['DATAFECHATEND']);
        $this->setPrazoEntrega($busca_os[0]['PRAZOENTREGA']);
        $this->setStatus($busca_os[0]['CAT_SITUACAO_ID']);
        $this->setQtdExecutada($this->qtd_executada);
        $this->desenhaApontamentoOS($this->$m_msg, $this->m_tipoMsg);
    }


    function desenhaApontamentoOS($mensagem = NULL, $tipoMsg = NULL)
    {

        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);

        $this->smarty->assign('servicos', $this->getDescServico());
        $this->smarty->assign('quantidade', $this->getQuantidade());
        $this->smarty->assign('quantidade_executada', $this->getQtdExecutada());
        $this->smarty->assign('nome_cliente', $this->getNomeCliente());
        $this->smarty->assign('numero_os', $this->numAtendimento);
        $this->smarty->assign('data_inicio', $this->getDataAbertura());
        $this->smarty->assign('prazo_entrega', $this->getPrazoEntrega());
        $this->smarty->assign('data_finalizacao', $this->getDataFechamento());

        $lanc = $this->selectServicoApontamento($this->numAtendimento) ?? [];
        $this->smarty->assign('lanc', $lanc);

        $consulta = new c_banco();
        $sql = "SELECT ID , DESCRICAO FROM CAT_SITUACAO ";
        $sql .= "WHERE ATIVO = '1'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $this->getStatus());




        $this->smarty->display('apontamento_os_mobile_cadastro.tpl');
    }
}
//	END OF THE CLASS
