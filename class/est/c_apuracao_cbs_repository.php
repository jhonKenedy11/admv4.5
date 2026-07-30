<?php
/**
 * @package   adm4.5
 * @name      c_apuracao_cbs_repository
 * @version   4.5.00
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 * @author    Auto
 * @date      14/07/2026
 *
 * Acesso a dados (PDO) das tabelas EST_APURACAO_CBS_*
 */

$dir = dirname(__FILE__);
include_once($dir . '/../../bib/c_database_pdo.php');

class c_apuracao_cbs_repository
{
    /**
     * Busca credencial por CNPJ base e ambiente
     */
    public function getCredencial(string $cnpj_base, ?string $ambiente = null): array|bool
    {
        $banco = new c_banco_pdo();

        if ($ambiente) {
            $banco->prepare("
                SELECT *
                FROM EST_APURACAO_CBS_CREDENCIAL
                WHERE CNPJ_BASE = :cnpj
                  AND AMBIENTE = :ambiente
                LIMIT 1
            ");
            $banco->bindValue(':cnpj', $cnpj_base);
            $banco->bindValue(':ambiente', strtoupper($ambiente));
        } else {
            $banco->prepare("
                SELECT *
                FROM EST_APURACAO_CBS_CREDENCIAL
                WHERE CNPJ_BASE = :cnpj
                ORDER BY FIELD(AMBIENTE, 'HOMOLOGACAO', 'PRODUCAO', 'PRODUCAO_RESTRITA'), ID DESC
                LIMIT 1
            ");
            $banco->bindValue(':cnpj', $cnpj_base);
        }

        $banco->execute();
        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados ?: false;
    }

    /**
     * Lista credenciais (sem secret/token)
     */
    public function getCredenciais(): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT
                ID, CNPJ_BASE, CLIENT_ID, AMBIENTE, WEBHOOK_URL,
                TOKEN_EXPIRA_EM, DT_INSERT, DT_UPDATE,
                CASE WHEN TOKEN IS NOT NULL AND TOKEN <> '' THEN 'S' ELSE 'N' END AS TEM_TOKEN
            FROM EST_APURACAO_CBS_CREDENCIAL
            ORDER BY CNPJ_BASE, AMBIENTE
        ");
        $banco->execute();

        return $banco->fetchAll() ?: [];
    }

    /**
     * Inclui credencial
     */
    public function insertCredencial(array $dados): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            INSERT INTO EST_APURACAO_CBS_CREDENCIAL
                (CNPJ_BASE, CLIENT_ID, CLIENT_SECRET, AMBIENTE, WEBHOOK_URL, WEBHOOK_SECRET, USER_INSERT)
            VALUES
                (:cnpj, :client_id, :secret, :ambiente, :webhook, :wh_secret, :user)
        ");
        $banco->bindValue(':cnpj', $dados['cnpj_base']);
        $banco->bindValue(':client_id', $dados['client_id']);
        $banco->bindValue(':secret', $dados['client_secret']);
        $banco->bindValue(':ambiente', $dados['ambiente']);
        $banco->bindValue(':webhook', $dados['webhook_url'] ?? '');
        $banco->bindValue(':wh_secret', $dados['webhook_secret'] ?? null);
        $banco->bindValue(':user', (int) ($dados['user_insert'] ?? 0), PDO::PARAM_INT);
        $banco->execute();

        return (int) $banco->lastInsertId();
    }

    /**
     * Atualiza credencial
     */
    public function updateCredencial(int $id, array $dados): bool
    {
        $banco = new c_banco_pdo();

        if (!empty($dados['client_secret'])) {
            $banco->prepare("
                UPDATE EST_APURACAO_CBS_CREDENCIAL SET
                    CLIENT_ID = :client_id,
                    CLIENT_SECRET = :secret,
                    WEBHOOK_URL = :webhook,
                    WEBHOOK_SECRET = :wh_secret,
                    USER_UPDATE = :user,
                    DT_UPDATE = NOW()
                WHERE ID = :id
            ");
            $banco->bindValue(':secret', $dados['client_secret']);
        } else {
            $banco->prepare("
                UPDATE EST_APURACAO_CBS_CREDENCIAL SET
                    CLIENT_ID = :client_id,
                    WEBHOOK_URL = :webhook,
                    WEBHOOK_SECRET = :wh_secret,
                    USER_UPDATE = :user,
                    DT_UPDATE = NOW()
                WHERE ID = :id
            ");
        }

        $banco->bindValue(':client_id', $dados['client_id']);
        $banco->bindValue(':webhook', $dados['webhook_url'] ?? '');
        $banco->bindValue(':wh_secret', $dados['webhook_secret'] ?? null);
        $banco->bindValue(':user', (int) ($dados['user_update'] ?? 0), PDO::PARAM_INT);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return true;
    }

    /**
     * Atualiza token da credencial (receber TOKEN já criptografado)
     */
    public function updateToken(int $id, string $token, string $expira_em): bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            UPDATE EST_APURACAO_CBS_CREDENCIAL SET
                TOKEN = :token,
                TOKEN_EXPIRA_EM = :expira,
                DT_UPDATE = NOW()
            WHERE ID = :id
        ");
        $banco->bindValue(':token', $token);
        $banco->bindValue(':expira', $expira_em);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return true;
    }

    /**
     * Conta consultas do dia por CNPJ
     */
    public function countConsultasDia(string $cnpj_base): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT COUNT(*) AS total
            FROM EST_APURACAO_CBS_HISTORICO
            WHERE CNPJ_BASE = :cnpj
              AND DATE(DT_SOLICITACAO) = CURDATE()
        ");
        $banco->bindValue(':cnpj', $cnpj_base);
        $banco->execute();
        $row = $banco->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Conta downloads do dia por CNPJ
     */
    public function countDownloadsDia(string $cnpj_base): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT COUNT(*) AS total
            FROM EST_APURACAO_CBS_HISTORICO
            WHERE CNPJ_BASE = :cnpj
              AND DATE(DT_DOWNLOAD) = CURDATE()
              AND DT_DOWNLOAD IS NOT NULL
        ");
        $banco->bindValue(':cnpj', $cnpj_base);
        $banco->execute();
        $row = $banco->fetch(PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Inclui histórico de solicitação
     */
    public function insertHistorico(array $dados): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            INSERT INTO EST_APURACAO_CBS_HISTORICO
                (CNPJ_BASE, TIQUETE, WEBHOOK_URL, STATUS, HTTP_CODE, MSG_RETORNO, USER_INSERT)
            VALUES
                (:cnpj, :tiquete, :webhook, :status, :http, :msg, :user)
        ");
        $banco->bindValue(':cnpj', $dados['cnpj_base']);
        $banco->bindValue(':tiquete', $dados['tiquete'] ?? null);
        $banco->bindValue(':webhook', $dados['webhook_url'] ?? '');
        $banco->bindValue(':status', $dados['status'] ?? 'SOLICITADO');
        $banco->bindValue(':http', $dados['http_code'] ?? null);
        $banco->bindValue(':msg', $dados['msg_retorno'] ?? null);
        $banco->bindValue(':user', (int) ($dados['user_insert'] ?? 0), PDO::PARAM_INT);
        $banco->execute();

        return (int) $banco->lastInsertId();
    }

    /**
     * Inclui histórico já baixado (quando download sem registro prévio)
     */
    public function insertHistoricoBaixado(array $dados, int $validade_horas = 24): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            INSERT INTO EST_APURACAO_CBS_HISTORICO
                (CNPJ_BASE, TIQUETE, STATUS, HTTP_CODE, MSG_RETORNO, DT_DOWNLOAD, DT_EXPIRA_ARQUIVO, USER_INSERT)
            VALUES
                (:cnpj, :tiquete, 'BAIXADO', :http, :msg, NOW(),
                 DATE_ADD(NOW(), INTERVAL :horas HOUR), :user)
        ");
        $banco->bindValue(':cnpj', $dados['cnpj_base']);
        $banco->bindValue(':tiquete', $dados['tiquete']);
        $banco->bindValue(':http', $dados['http_code'] ?? null);
        $banco->bindValue(':msg', $dados['msg_retorno'] ?? 'Download realizado');
        $banco->bindValue(':horas', $validade_horas, PDO::PARAM_INT);
        $banco->bindValue(':user', (int) ($dados['user_insert'] ?? 0), PDO::PARAM_INT);
        $banco->execute();

        return (int) $banco->lastInsertId();
    }

    /**
     * Atualiza status do histórico
     */
    public function updateStatusHistorico(int $id, string $status, $http_code = null, $msg = null): bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            UPDATE EST_APURACAO_CBS_HISTORICO SET
                STATUS = :status,
                HTTP_CODE = COALESCE(:http, HTTP_CODE),
                MSG_RETORNO = COALESCE(:msg, MSG_RETORNO)
            WHERE ID = :id
        ");
        $banco->bindValue(':status', $status);
        $banco->bindValue(':http', $http_code);
        $banco->bindValue(':msg', $msg);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return true;
    }

    /**
     * Marca histórico como baixado
     */
    public function updateHistoricoBaixado(int $id, int $http_code, string $msg, int $validade_horas = 24): bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            UPDATE EST_APURACAO_CBS_HISTORICO SET
                STATUS = 'BAIXADO',
                HTTP_CODE = :http,
                MSG_RETORNO = :msg,
                DT_DOWNLOAD = NOW(),
                DT_EXPIRA_ARQUIVO = DATE_ADD(NOW(), INTERVAL :horas HOUR)
            WHERE ID = :id
        ");
        $banco->bindValue(':http', $http_code, PDO::PARAM_INT);
        $banco->bindValue(':msg', $msg);
        $banco->bindValue(':horas', $validade_horas, PDO::PARAM_INT);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return true;
    }

    /**
     * Busca histórico por ID
     */
    public function getHistoricoPorId(int $id): array|bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT * FROM EST_APURACAO_CBS_HISTORICO WHERE ID = :id");
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();
        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados ?: false;
    }

    /**
     * Busca histórico por tíquete
     */
    public function getHistoricoPorTiquete(string $tiquete): array|bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("SELECT * FROM EST_APURACAO_CBS_HISTORICO WHERE TIQUETE = :t LIMIT 1");
        $banco->bindValue(':t', $tiquete);
        $banco->execute();
        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados ?: false;
    }

    /**
     * Lista histórico (com total de débitos)
     */
    public function getHistorico(?string $cnpj_base = null, array $filtros = []): array
    {
        $banco = new c_banco_pdo();
        $sql = "
            SELECT h.*,
                   (SELECT COUNT(*) FROM EST_APURACAO_CBS_DEBITO d WHERE d.ID_HISTORICO = h.ID) AS QTDE_DEBITOS
            FROM EST_APURACAO_CBS_HISTORICO h
            WHERE 1 = 1
        ";

        if ($cnpj_base) {
            $sql .= ' AND h.CNPJ_BASE = :cnpj';
        }
        if (!empty($filtros['status'])) {
            $sql .= ' AND h.STATUS = :status';
        }

        $sql .= ' ORDER BY h.DT_SOLICITACAO DESC, h.ID DESC LIMIT 200';
        $banco->prepare($sql);

        if ($cnpj_base) {
            $banco->bindValue(':cnpj', $cnpj_base);
        }
        if (!empty($filtros['status'])) {
            $banco->bindValue(':status', $filtros['status']);
        }

        $banco->execute();

        return $banco->fetchAll() ?: [];
    }

    /**
     * Remove débitos de um histórico (re-download)
     */
    public function deleteDebitosPorHistorico(int $id_historico): bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare('DELETE FROM EST_APURACAO_CBS_DEBITO WHERE ID_HISTORICO = :id');
        $banco->bindValue(':id', $id_historico, PDO::PARAM_INT);
        $banco->execute();

        return true;
    }

    /**
     * Inclui débito (por DF-e / chave de acesso)
     */
    public function insertDebito(array $dados): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            INSERT INTO EST_APURACAO_CBS_DEBITO
                (ID_HISTORICO, TIPO_APURACAO, DATA_APURACAO, CHAVE_DFE, MODELO_DFE, NUMERO_DFE,
                 NI_EMITENTE, NI_ADQUIRENTE, CNPJ_BASE, DATA_DFE_EMISSAO, DATA_DFE_AUTORIZACAO, DATA_DFE_REGISTRO,
                 VALOR_CBS_TOTAL, VALOR_CBS_EXTINTO, VALOR_CBS_NAO_EXTINTO, VALOR_PRESCRITO, DATA_PRESCRITO, SITUACAO_DEBITO,
                 PAPEL_EMPRESA, STATUS_EVENTO, JSON_ORIGINAL, DIVERGENTE, ID_DEBITO_ANTERIOR)
            VALUES
                (:hist, :tp_apur, :dt_apur, :chave, :modelo, :numero,
                 :ni_emit, :ni_adq, :cnpj, :dt_emis, :dt_autoriz, :dt_reg,
                 :cbs_total, :cbs_ext, :cbs_nao_ext, :vl_presc, :dt_presc, :situacao,
                 :papel, 'PENDENTE', :json, :div, :ant)
        ");
        $banco->bindValue(':hist', (int) $dados['id_historico'], PDO::PARAM_INT);
        $banco->bindValue(':tp_apur', $dados['tipo_apuracao'] ?? null);
        $banco->bindValue(':dt_apur', $dados['data_apuracao'] ?? null);
        $banco->bindValue(':chave', $dados['chave_dfe']);
        $banco->bindValue(':modelo', $dados['modelo_dfe'] ?? null);
        $banco->bindValue(':numero', $dados['numero_dfe'] ?? null);
        $banco->bindValue(':ni_emit', $dados['ni_emitente'] ?? null);
        $banco->bindValue(':ni_adq', $dados['ni_adquirente'] ?? null);
        $banco->bindValue(':cnpj', $dados['cnpj_base']);
        $banco->bindValue(':dt_emis', $dados['data_dfe_emissao'] ?? null);
        $banco->bindValue(':dt_autoriz', $dados['data_dfe_autorizacao'] ?? null);
        $banco->bindValue(':dt_reg', $dados['data_dfe_registro'] ?? null);
        $banco->bindValue(':cbs_total', $dados['valor_cbs_total'] ?? null);
        $banco->bindValue(':cbs_ext', $dados['valor_cbs_extinto'] ?? null);
        $banco->bindValue(':cbs_nao_ext', $dados['valor_cbs_nao_extinto'] ?? null);
        $banco->bindValue(':vl_presc', $dados['valor_prescrito'] ?? null);
        $banco->bindValue(':dt_presc', $dados['data_prescrito'] ?? null);
        $banco->bindValue(':situacao', $dados['situacao_debito'] ?? null);
        $banco->bindValue(':papel', $dados['papel_empresa'] ?? 'OUTRO');
        $banco->bindValue(':json', $dados['json_original'] ?? null);
        $banco->bindValue(':div', $dados['divergente'] ?? 'N');
        $banco->bindValue(':ant', $dados['id_debito_anterior'] ?? null);
        $banco->execute();

        return (int) $banco->lastInsertId();
    }

    /**
     * Inclui forma de extinção - pagamento/split
     */
    public function insertPagamento(array $dados): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            INSERT INTO EST_APURACAO_CBS_PGTO
                (ID_DEBITO, NUMERO_DARF, TIPO_PAGAMENTO, DATA_ARRECADACAO, DATA_UTILIZACAO,
                 VALOR_PRINCIPAL, VALOR_MULTA, VALOR_JUROS)
            VALUES
                (:deb, :darf, :tipo, :dt_arrec, :dt_util, :princ, :multa, :juros)
        ");
        $banco->bindValue(':deb', (int) $dados['id_debito'], PDO::PARAM_INT);
        $banco->bindValue(':darf', $dados['numero_darf'] ?? null);
        $banco->bindValue(':tipo', $dados['tipo_pagamento'] ?? null);
        $banco->bindValue(':dt_arrec', $dados['data_arrecadacao'] ?? null);
        $banco->bindValue(':dt_util', $dados['data_utilizacao'] ?? null);
        $banco->bindValue(':princ', $dados['valor_principal'] ?? null);
        $banco->bindValue(':multa', $dados['valor_multa'] ?? null);
        $banco->bindValue(':juros', $dados['valor_juros'] ?? null);
        $banco->execute();

        return (int) $banco->lastInsertId();
    }

    /**
     * Inclui forma de extinção - crédito utilizado
     */
    public function insertCreditoUtilizado(array $dados): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            INSERT INTO EST_APURACAO_CBS_CREDITO_UTILIZADO
                (ID_DEBITO, TIPO_TRIBUTO, CCLASS_CRED, ORIGEM, CHAVE_DFE_ORIGEM, MODELO_DFE_ORIGEM, NUMERO_DFE_ORIGEM, DATA_UTILIZACAO,
                 VALOR_PRINCIPAL, VALOR_MULTA, VALOR_JUROS)
            VALUES
                (:deb, :tributo, :cclass, :origem, :chave_orig, :modelo_orig, :numero_orig, :dt_util, :princ, :multa, :juros)
        ");
        $banco->bindValue(':deb', (int) $dados['id_debito'], PDO::PARAM_INT);
        $banco->bindValue(':tributo', $dados['tipo_tributo'] ?? null);
        $banco->bindValue(':cclass', $dados['cclass_cred'] ?? null);
        $banco->bindValue(':origem', $dados['origem'] ?? null);
        $banco->bindValue(':chave_orig', $dados['chave_dfe_origem'] ?? null);
        $banco->bindValue(':modelo_orig', $dados['modelo_dfe_origem'] ?? null);
        $banco->bindValue(':numero_orig', $dados['numero_dfe_origem'] ?? null);
        $banco->bindValue(':dt_util', $dados['data_utilizacao'] ?? null);
        $banco->bindValue(':princ', $dados['valor_principal'] ?? null);
        $banco->bindValue(':multa', $dados['valor_multa'] ?? null);
        $banco->bindValue(':juros', $dados['valor_juros'] ?? null);
        $banco->execute();

        return (int) $banco->lastInsertId();
    }

    /**
     * Busca débito anterior pela mesma chave (download anterior)
     */
    public function getDebitoAnteriorPorChave(string $chave_dfe, int $id_historico_atual): array|bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT d.*
            FROM EST_APURACAO_CBS_DEBITO d
            WHERE d.CHAVE_DFE = :chave
              AND d.ID_HISTORICO <> :hist
            ORDER BY d.DT_INSERT DESC
            LIMIT 1
        ");
        $banco->bindValue(':chave', $chave_dfe);
        $banco->bindValue(':hist', $id_historico_atual, PDO::PARAM_INT);
        $banco->execute();
        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados ?: false;
    }

    /**
     * Lista débitos de um histórico (com valores anteriores da mesma chave)
     */
    public function getDebitos(int $id_historico): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT d.*,
                   a.VALOR_CBS_NAO_EXTINTO AS VALOR_CBS_NAO_EXTINTO_ANTERIOR,
                   a.SITUACAO_DEBITO AS SITUACAO_DEBITO_ANTERIOR
            FROM EST_APURACAO_CBS_DEBITO d
            LEFT JOIN EST_APURACAO_CBS_DEBITO a ON a.ID = d.ID_DEBITO_ANTERIOR
            WHERE d.ID_HISTORICO = :id
            ORDER BY d.TIPO_APURACAO, d.DATA_APURACAO DESC, d.ID
        ");
        $banco->bindValue(':id', $id_historico, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetchAll() ?: [];
    }

    /**
     * Lista débitos de um histórico filtrando pelo papel da empresa
     */
    public function getDebitosPorPapel(int $id_historico, string $papel): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT d.*,
                   a.VALOR_CBS_NAO_EXTINTO AS VALOR_CBS_NAO_EXTINTO_ANTERIOR,
                   a.SITUACAO_DEBITO AS SITUACAO_DEBITO_ANTERIOR
            FROM EST_APURACAO_CBS_DEBITO d
            LEFT JOIN EST_APURACAO_CBS_DEBITO a ON a.ID = d.ID_DEBITO_ANTERIOR
            WHERE d.ID_HISTORICO = :id
              AND d.PAPEL_EMPRESA = :papel
            ORDER BY d.VALOR_CBS_NAO_EXTINTO DESC, d.DATA_APURACAO DESC, d.ID
        ");
        $banco->bindValue(':id', $id_historico, PDO::PARAM_INT);
        $banco->bindValue(':papel', $papel);
        $banco->execute();

        return $banco->fetchAll() ?: [];
    }

    /**
     * Lista pagamentos (formas de extinção) de um débito
     */
    public function getPagamentos(int $id_debito): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT * FROM EST_APURACAO_CBS_PGTO WHERE ID_DEBITO = :id ORDER BY ID');
        $banco->bindValue(':id', $id_debito, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetchAll() ?: [];
    }

    /**
     * Lista créditos utilizados de um débito
     */
    public function getCreditos(int $id_debito): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT * FROM EST_APURACAO_CBS_CREDITO_UTILIZADO WHERE ID_DEBITO = :id ORDER BY ID');
        $banco->bindValue(':id', $id_debito, PDO::PARAM_INT);
        $banco->execute();

        return $banco->fetchAll() ?: [];
    }

    /**
     * Busca débito por ID
     */
    public function getDebitoPorId(int $id): array|bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT * FROM EST_APURACAO_CBS_DEBITO WHERE ID = :id');
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();
        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados ?: false;
    }

    /**
     * Busca débito por chave dentro de um histórico
     */
    public function getDebitoPorChave(string $chave_dfe, int $id_historico): array|bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT * FROM EST_APURACAO_CBS_DEBITO
            WHERE CHAVE_DFE = :chave AND ID_HISTORICO = :hist
            LIMIT 1
        ");
        $banco->bindValue(':chave', $chave_dfe);
        $banco->bindValue(':hist', $id_historico, PDO::PARAM_INT);
        $banco->execute();
        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados ?: false;
    }

    /**
     * Atualiza status de evento do débito
     */
    public function updateStatusEventoDebito(int $id, string $status): bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            UPDATE EST_APURACAO_CBS_DEBITO SET
                STATUS_EVENTO = :st,
                DT_UPDATE = NOW()
            WHERE ID = :id
        ");
        $banco->bindValue(':st', $status);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return true;
    }

    /**
     * Inclui evento fiscal (por chave)
     */
    public function insertEvento(array $dados): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            INSERT INTO EST_APURACAO_CBS_EVENTO
                (CHAVE_DFE, ID_DEBITO, TP_EVENTO, ORIGEM, PAPEL, DESCRICAO, OBSERVACAO,
                 PROTOCOLO, HTTP_CODE, JSON_ENVIO, JSON_RETORNO, MSG_RETORNO, STATUS, USER_INSERT)
            VALUES
                (:chave, :deb, :tp, :origem, :papel, :desc, :obs,
                 :prot, :http, :envio, :ret, :msg, :status, :user)
        ");
        $banco->bindValue(':chave', $dados['chave_dfe']);
        $banco->bindValue(':deb', isset($dados['id_debito']) ? (int) $dados['id_debito'] : null, PDO::PARAM_INT);
        $banco->bindValue(':tp', $dados['tp_evento']);
        $banco->bindValue(':origem', $dados['origem'] ?? 'LOCAL');
        $banco->bindValue(':papel', $dados['papel'] ?? null);
        $banco->bindValue(':desc', $dados['descricao'] ?? null);
        $banco->bindValue(':obs', $dados['observacao'] ?? null);
        $banco->bindValue(':prot', $dados['protocolo'] ?? null);
        $banco->bindValue(':http', $dados['http_code'] ?? null);
        $banco->bindValue(':envio', $dados['json_envio'] ?? null);
        $banco->bindValue(':ret', $dados['json_retorno'] ?? null);
        $banco->bindValue(':msg', $dados['msg_retorno'] ?? null);
        $banco->bindValue(':status', $dados['status'] ?? 'REGISTRADO');
        $banco->bindValue(':user', (int) ($dados['user_insert'] ?? 0), PDO::PARAM_INT);
        $banco->execute();

        return (int) $banco->lastInsertId();
    }

    /**
     * Inclui evento retornado pela RF na apuração (ORIGEM=RF).
     */
    public function insertEventoRf(array $dados): int
    {
        return $this->insertEvento([
            'chave_dfe' => $dados['chave_dfe'],
            'id_debito' => $dados['id_debito'] ?? null,
            'tp_evento' => $dados['tp_evento'],
            'origem' => 'RF',
            'papel' => $dados['papel'] ?? null,
            'descricao' => $dados['descricao'] ?? null,
            'protocolo' => $dados['protocolo'] ?? null,
            'json_retorno' => $dados['json_retorno'] ?? null,
            'status' => 'REGISTRADO',
        ]);
    }

    /**
     * Lista eventos (opcionalmente por CNPJ base via join no débito)
     */
    public function getEventos(array $filtros = []): array
    {
        $banco = new c_banco_pdo();
        $sql = "
            SELECT e.*, d.CNPJ_BASE, d.TIPO_APURACAO, d.DATA_APURACAO, d.VALOR_CBS_NAO_EXTINTO
            FROM EST_APURACAO_CBS_EVENTO e
            LEFT JOIN EST_APURACAO_CBS_DEBITO d ON d.ID = e.ID_DEBITO
            WHERE 1 = 1
        ";
        if (!empty($filtros['cnpj_base'])) {
            $sql .= ' AND d.CNPJ_BASE = :cnpj';
        }
        if (!empty($filtros['papel'])) {
            $sql .= ' AND e.PAPEL = :papel';
        }
        $sql .= ' ORDER BY e.DT_INSERT DESC, e.ID DESC LIMIT 300';
        $banco->prepare($sql);
        if (!empty($filtros['cnpj_base'])) {
            $banco->bindValue(':cnpj', $filtros['cnpj_base']);
        }
        if (!empty($filtros['papel'])) {
            $banco->bindValue(':papel', $filtros['papel']);
        }
        $banco->execute();

        return $banco->fetchAll() ?: [];
    }

    /**
     * Lista eventos de uma chave
     */
    public function getEventosPorChave(string $chave_dfe): array
    {
        $banco = new c_banco_pdo();
        $banco->prepare('SELECT * FROM EST_APURACAO_CBS_EVENTO WHERE CHAVE_DFE = :chave ORDER BY DT_INSERT DESC');
        $banco->bindValue(':chave', $chave_dfe);
        $banco->execute();

        return $banco->fetchAll() ?: [];
    }

    //---------------------------------------------------------------
    // WEBHOOK (recebimento assíncrono do tíquete)
    //---------------------------------------------------------------

    /**
     * Busca credencial pelo CNPJ base (qualquer ambiente) para validar o webhook.
     */
    public function getCredencialPorCnpj(string $cnpj_base): array|bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            SELECT *
            FROM EST_APURACAO_CBS_CREDENCIAL
            WHERE CNPJ_BASE = :cnpj
            ORDER BY FIELD(AMBIENTE, 'HOMOLOGACAO', 'PRODUCAO', 'PRODUCAO_RESTRITA'), ID DESC
            LIMIT 1
        ");
        $banco->bindValue(':cnpj', $cnpj_base);
        $banco->execute();
        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados ?: false;
    }

    /**
     * Localiza a solicitação aguardando retorno de um CNPJ (mais recente).
     * Considera também o tíquete quando já conhecido.
     */
    public function getHistoricoAguardandoPorCnpj(string $cnpj_base, ?string $tiquete = null): array|bool
    {
        $banco = new c_banco_pdo();
        $sql = "
            SELECT *
            FROM EST_APURACAO_CBS_HISTORICO
            WHERE CNPJ_BASE = :cnpj
              AND STATUS IN ('AGUARDANDO_RETORNO', 'SOLICITADO', 'PROCESSANDO')
        ";
        if ($tiquete !== null && $tiquete !== '') {
            $sql .= ' AND (TIQUETE = :t OR TIQUETE IS NULL OR TIQUETE = \'\')';
        }
        $sql .= ' ORDER BY DT_SOLICITACAO DESC, ID DESC LIMIT 1';

        $banco->prepare($sql);
        $banco->bindValue(':cnpj', $cnpj_base);
        if ($tiquete !== null && $tiquete !== '') {
            $banco->bindValue(':t', $tiquete);
        }
        $banco->execute();
        $dados = $banco->fetch(PDO::FETCH_ASSOC);

        return $dados ?: false;
    }

    /**
     * Grava o tíquete de download recebido pelo webhook e marca como DISPONIVEL.
     */
    public function updateTiqueteDownload(int $id, string $tiquete, ?string $msg = null): bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            UPDATE EST_APURACAO_CBS_HISTORICO SET
                TIQUETE = :t,
                STATUS = 'DISPONIVEL',
                MSG_RETORNO = COALESCE(:msg, MSG_RETORNO)
            WHERE ID = :id
        ");
        $banco->bindValue(':t', $tiquete);
        $banco->bindValue(':msg', $msg);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return true;
    }

    /**
     * Registra o recebimento bruto de um webhook (auditoria do beta).
     */
    public function insertWebhookLog(array $dados): int
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            INSERT INTO EST_APURACAO_CBS_WEBHOOK_LOG
                (CNPJ_BASE, ORIGEM_IP, HEADERS, PAYLOAD, TIQUETE, PROCESSADO, MSG)
            VALUES
                (:cnpj, :ip, :headers, :payload, :tiquete, :proc, :msg)
        ");
        $banco->bindValue(':cnpj', $dados['cnpj_base'] ?? null);
        $banco->bindValue(':ip', $dados['origem_ip'] ?? null);
        $banco->bindValue(':headers', $dados['headers'] ?? null);
        $banco->bindValue(':payload', $dados['payload'] ?? null);
        $banco->bindValue(':tiquete', $dados['tiquete'] ?? null);
        $banco->bindValue(':proc', $dados['processado'] ?? 'N');
        $banco->bindValue(':msg', $dados['msg'] ?? null);
        $banco->execute();

        return (int) $banco->lastInsertId();
    }

    /**
     * Atualiza o log do webhook após o processamento.
     */
    public function updateWebhookLog(int $id, string $processado, ?string $msg = null, ?string $cnpj_base = null, ?string $tiquete = null): bool
    {
        $banco = new c_banco_pdo();
        $banco->prepare("
            UPDATE EST_APURACAO_CBS_WEBHOOK_LOG SET
                PROCESSADO = :proc,
                MSG = COALESCE(:msg, MSG),
                CNPJ_BASE = COALESCE(:cnpj, CNPJ_BASE),
                TIQUETE = COALESCE(:tiquete, TIQUETE)
            WHERE ID = :id
        ");
        $banco->bindValue(':proc', $processado);
        $banco->bindValue(':msg', $msg);
        $banco->bindValue(':cnpj', $cnpj_base);
        $banco->bindValue(':tiquete', $tiquete);
        $banco->bindValue(':id', $id, PDO::PARAM_INT);
        $banco->execute();

        return true;
    }
}
