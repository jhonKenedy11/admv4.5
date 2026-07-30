<?php

/**
 * Consolidação Bancária — consultas API (filtros conta/banco).
 * Local: class/fin/c_consolidacao_bancaria_apis.php
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_database_pdo.php");
require_once($dir . "/../../class/util/c_api_response.php");
require_once($dir . "/c_conta_banco.php");

class c_consolidacao_bancaria_apis extends c_user
{
    public bool $existe_divergencia = false;

    /**
     * @name getBancos
     * @description Retorna todos os bancos cadastrados
     * @return array
     */
    public function getBancos()
    {

        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT
                BANCO, 
                CONCAT(BANCO, ' - ', NOME) AS NOME 
            FROM FIN_BANCO
            WHERE 1 = 1 
            ORDER BY BANCO
        ");
        $banco->execute();

        $resultado = $banco->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    /**
     * @name getContas
     * @description Retorna todas as contas cadastradas
     * @return array
     */
    public function getContas()
    {

        $conta = new c_banco_pdo();
        $conta->prepare("
            SELECT
                CONTA,
                BANCO,
                CONCAT(BANCO, ' - ', NOMEINTERNO) AS NOME
            FROM FIN_CONTA 
            WHERE 1 = 1 
            ORDER BY CONTA
        ");

        $conta->execute();
        $resultado = $conta->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    /**
     * @name getCentroCusto
     * @description Retorna todos os centros de custo cadastrados
     * @return array
     */
    public function getCentroCusto()
    {

        $centro_custo = new c_banco_pdo();
        $centro_custo->prepare("
            SELECT
                CENTROCUSTO,
                CONCAT(CENTROCUSTO, ' - ', DESCRICAO) AS NOME
            FROM FIN_CENTRO_CUSTO 
            WHERE ATIVO = 'S' 
            ORDER BY CENTROCUSTO
        ");

        $centro_custo->execute();
        $resultado = $centro_custo->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    /**
     * @name processaTitulosSelecionados
     * @description Processa os títulos selecionados
     * @param string $dados
     * @return void
     */
    public function processaTitulosSelecionados(string $dados): void
    {
        try {
            $dados_array = json_decode($dados, true);
            $resultado_processamento = [];
            $existe_divergencia = false;


            if (!$dados_array) {
                c_api_response::failure('Dados inválidos');
            }

            $titulos      = $dados_array['titulos'];
            $banco        = $dados_array['banco'];
            $conta        = $dados_array['conta'];
            //$centro_custo = $dados_array['centro_custo'];

            foreach ($titulos as $titulo) {

                $r_busca_lancamento = $this->buscaLancamento($titulo['seu_numero']);

                // Se o lançamento não for encontrado, adiciona ao resultado de processamento
                if (!$r_busca_lancamento) {

                    $resultado_processamento[] = [
                        'seu_numero' => $titulo['seu_numero'],
                        'nome_pagador' => $titulo['nome_pagador'],
                        'descricao_pagamento' => $titulo['descricao_pagamento'],
                        'lancamento_encontrado' => false,
                        'resultado_processamento' => 'Lançamento não encontrado',
                    ];

                    $this->existe_divergencia = true;

                    continue;
                }

                // Busca a conta bancária
                $busca_conta = new c_contaBanco();
                $busca_conta->setId($conta);
                $busca_conta = $busca_conta->select_ContaBanco();

                if (!$busca_conta) {

                    $resultado_processamento[] = [
                        'seu_numero' => $titulo['seu_numero'],
                        'nome_pagador' => $titulo['nome_pagador'],
                        'descricao_pagamento' => $titulo['descricao_pagamento'],
                        'lancamento_encontrado' => false,
                        'resultado_processamento' => 'Conta bancária não encontrada',
                    ];

                    $this->existe_divergencia = true;
                    continue;
                }

                $r_processa_lancamento = $this->processaLancamento($titulo, $banco, $busca_conta);

                // Se o lançamento for encontrado, processa o lançamento e adiciona ao resultado de processamento
                $resultado_processamento[] = [
                    'seu_numero' => $titulo['seu_numero'],
                    'nome_pagador' => $titulo['nome_pagador'],
                    'descricao_pagamento' => $titulo['descricao_pagamento'],
                    'lancamento_encontrado' => true,
                    'resultado_processamento' => $r_processa_lancamento,
                ];

                continue;
            }

            // Se existem títulos com divergência de dados, retorna um erro de conflito
            if ($this->existe_divergencia) {
                c_api_response::custom(false, 'Existem títulos com divergências', $resultado_processamento, [], [], 200);
            }

            // Se não existem títulos com divergência de dados, retorna um sucesso
            c_api_response::custom(true, 'Títulos processados com sucesso', $resultado_processamento, [], [], 200);
        } catch (Exception $e) {
            c_api_response::serverError('Erro ao processar os títulos selecionados', [$e->getMessage()]);
        }
    }

    /**
     * @name buscaLancamento
     * @description Busca o lançamento pelo seu número
     * @param string $seu_numero
     * @return array|bool
     */
    public function buscaLancamento(string $seu_numero): array|bool
    {
        try {
            // Conecta ao banco de dados
            $pdo = new c_banco_pdo();
            $pdo->prepare("
                SELECT
                    1
                FROM FIN_LANCAMENTO
                WHERE ID = :seu_numero
                LIMIT 1
            ");

            $pdo->bindValue(':seu_numero', $seu_numero, PDO::PARAM_STR);
            $pdo->execute();

            return $pdo->fetch() !== false ? true : false;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @name processaLancamento
     * @description Processa o lançamento
     * @param array $lancamento
     * @return string|array
     */
    public function processaLancamento(array $lancamento, string $banco, array $busca_conta): string|array
    {
        try {
            // Trata a situação do lançamento
            $trata_situacao = $this->trataSituacaoLancamento($lancamento, $banco, $busca_conta);

            if ($trata_situacao === null || $trata_situacao === '') {
                // Se a situação do lançamento for null, adiciona ao resultado de processamento
                $this->existe_divergencia = true;
                return 'A situação do lançamento não foi encontrada';
            }

            // Conecta ao banco de dados
            $pdo = new c_banco_pdo();

            // Converte a data de pagamento para o formato MySQL
            $data_pagamento = $pdo->dateToMysql($lancamento['data_pagamento']);

            // Atualiza o lançamento no banco de dados
            $pdo->prepare("
                UPDATE FIN_LANCAMENTO SET
                    SITPGTO = :situacao,
                    PAGAMENTO = :data_pagamento,
                    USERCHANGE = :user_change,
                    DATECHANGE = NOW() 
                WHERE ID = :id
            ");

            // Binds os valores
            $pdo->bindValue(':id', $lancamento['seu_numero'], PDO::PARAM_INT);
            $pdo->bindValue(':situacao', $trata_situacao ?? null, PDO::PARAM_STR);
            $pdo->bindValue(':data_pagamento', $data_pagamento ?? null, PDO::PARAM_STR);
            $pdo->bindValue(':user_change', $this->m_userid, PDO::PARAM_INT);
            $pdo->execute();

            return $pdo->rowCount() > 0 ? 'Atualizado' : 'Sem alterações';
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @name trataSituacaoLancamento
     * @description Trata a situação do lançamento
     * @param array $lancamento
     * @param string $banco
     * @return string|null
     */
    public function trataSituacaoLancamento(array $lancamento, string $banco, array $busca_conta): string|null
    {
        $situacao = '';

        switch ($banco) {
            case '77':

                // Busca e decodifica o mapeamento de situações configurado na conta
                $mapeamento_situacao = json_decode($busca_conta[0]['INTER_SITUACAO_MAP'] ?? '', true);

                // Banco Inter — usa o mapeamento dinâmico da conta; null se a chave não existir
                $situacao = $mapeamento_situacao[$lancamento['descricao_pagamento']] ?? null;
                break;

            case '237':
                // Busca e decodifica o mapeamento de situações configurado na conta
                $mapeamento_situacao = json_decode($busca_conta[0]['BRADESCO_SITUACAO_MAP'] ?? '', true);

                // Banco Bradesco — usa o mapeamento dinâmico da conta; null se a chave não existir
                $situacao = $mapeamento_situacao[$lancamento['codigo_status']] ?? null;

                break;
            default:
                $situacao = '';
        }

        return $situacao;
    }
} // End of class
