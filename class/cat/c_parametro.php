<?php
/**
 * @package ADM v4.5
 * @name c_parametro
 * @description Classe para administração dos parâmetros de Ordem de Serviço (CAT)
 */

$dir = dirname(__FILE__);
require_once($dir . '/../../bib/c_database_pdo.php');
require_once($dir . '/../../bib/c_user.php');

class c_parametro extends c_user
{
    protected $id;
    protected $situacaoinclusao;
    protected $sitagatendimento;
    protected $sitematendimento;
    protected $sitsolicitarpeca;
    protected $sitagpeca;
    protected $sitpecarecebida;
    protected $sitaporcamento;
    protected $sitfinalizado;
    protected $localatendimento;
    protected $tipointervencao;
    protected $msgatendimento;
    protected $msgorcamento;
    protected $controleestoque;
    protected $tipodoccobranca;
    protected $condpgto;
    protected $conta;
    protected $genero;
    protected $centrocusto;

    /**
     * Verifica se já existe parâmetro cadastrado
     */
    public function existeParametro()
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT COUNT(*) as total FROM CAT_PARAMETROS";
            $banco->prepare($sql);
            $banco->execute();
            $resultado = $banco->fetchAll();
            return ($resultado[0]['total'] ?? 0) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Inclui parâmetro CAT
     * @return mixed true em sucesso ou mensagem de erro
     */
    public function incluiParametro()
    {
        try {
            if ($this->existeParametro()) {
                throw new Exception('Parâmetro já cadastrado. Utilize a alteração.');
            }

            $banco = new c_banco_pdo();
            $sql = "INSERT INTO CAT_PARAMETROS (
                        SITUACAOINCLUSAO, SITAGATENDIMENTO, SITEMATENDIMENTO, SITSOLICITARPECA,
                        SITAGPECA, SITPECARECEBIDA, SITAPORCAMENTO, SITFINALIZADO,
                        LOCALATENDIMENTO, TIPOINTERVENCAO, MSGATENDIMENTO, MSGORCAMENTO,
                        CONTROLEESTOQUE, TIPODOCCOBRANCA, CONDPGTO, CONTA, GENERO, CENTROCUSTO,
                        CREATED_USER, CREATED_AT
                    ) VALUES (
                        :situacaoinclusao, :sitagatendimento, :sitematendimento, :sitsolicitarpeca,
                        :sitagpeca, :sitpecarecebida, :sitaporcamento, :sitfinalizado,
                        :localatendimento, :tipointervencao, :msgatendimento, :msgorcamento,
                        :controleestoque, :tipodoccobranca, :condpgto, :conta, :genero, :centrocusto,
                        :created_user, :created_at
                    )";

            $banco->prepare($sql);
            $banco->bindValue(':situacaoinclusao', $this->situacaoinclusao !== '' && $this->situacaoinclusao !== null ? $this->situacaoinclusao : 0);
            $banco->bindValue(':sitagatendimento', $this->sitagatendimento !== '' && $this->sitagatendimento !== null ? $this->sitagatendimento : 0);
            $banco->bindValue(':sitematendimento', $this->sitematendimento !== '' && $this->sitematendimento !== null ? $this->sitematendimento : 0);
            $banco->bindValue(':sitsolicitarpeca', $this->sitsolicitarpeca !== '' && $this->sitsolicitarpeca !== null ? $this->sitsolicitarpeca : 0);
            $banco->bindValue(':sitagpeca', $this->sitagpeca !== '' && $this->sitagpeca !== null ? $this->sitagpeca : 0);
            $banco->bindValue(':sitpecarecebida', $this->sitpecarecebida !== '' && $this->sitpecarecebida !== null ? $this->sitpecarecebida : 0);
            $banco->bindValue(':sitaporcamento', $this->sitaporcamento !== '' && $this->sitaporcamento !== null ? $this->sitaporcamento : 0);
            $banco->bindValue(':sitfinalizado', $this->sitfinalizado !== '' && $this->sitfinalizado !== null ? $this->sitfinalizado : 0);
            $banco->bindValue(':localatendimento', $this->localatendimento !== '' && $this->localatendimento !== null ? $this->localatendimento : null);
            $banco->bindValue(':tipointervencao', $this->tipointervencao !== '' && $this->tipointervencao !== null ? $this->tipointervencao : null);
            $banco->bindValue(':msgatendimento', $this->msgatendimento ?? '');
            $banco->bindValue(':msgorcamento', $this->msgorcamento ?? '');
            $banco->bindValue(':controleestoque', $this->controleestoque !== '' && $this->controleestoque !== null ? $this->controleestoque : null);
            $banco->bindValue(':tipodoccobranca', $this->tipodoccobranca !== '' && $this->tipodoccobranca !== null ? $this->tipodoccobranca : null);
            $banco->bindValue(':condpgto', $this->condpgto !== '' && $this->condpgto !== null ? $this->condpgto : null);
            $banco->bindValue(':conta', $this->conta !== '' && $this->conta !== null ? $this->conta : null);
            $banco->bindValue(':genero', $this->genero !== '' && $this->genero !== null ? $this->genero : null);
            $banco->bindValue(':centrocusto', $this->centrocusto !== '' && $this->centrocusto !== null ? $this->centrocusto : null);
            $banco->bindValue(':created_user', $this->m_userid);
            $banco->bindValue(':created_at', date('Y-m-d H:i:s'));
            $banco->execute();

            return true;
        } catch (Exception $e) {
            error_log('[c_parametro CAT] incluiParametro: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Altera parâmetro CAT
     * @return mixed true em sucesso ou mensagem de erro
     */
    public function alteraParametro()
    {
        try {
            if (empty($this->id)) {
                throw new Exception('ID do parâmetro é obrigatório para alteração');
            }

            $banco = new c_banco_pdo();
            $sql = "UPDATE CAT_PARAMETROS SET
                        SITUACAOINCLUSAO = :situacaoinclusao,
                        SITAGATENDIMENTO = :sitagatendimento,
                        SITEMATENDIMENTO = :sitematendimento,
                        SITSOLICITARPECA = :sitsolicitarpeca,
                        SITAGPECA = :sitagpeca,
                        SITPECARECEBIDA = :sitpecarecebida,
                        SITAPORCAMENTO = :sitaporcamento,
                        SITFINALIZADO = :sitfinalizado,
                        LOCALATENDIMENTO = :localatendimento,
                        TIPOINTERVENCAO = :tipointervencao,
                        MSGATENDIMENTO = :msgatendimento,
                        MSGORCAMENTO = :msgorcamento,
                        CONTROLEESTOQUE = :controleestoque,
                        TIPODOCCOBRANCA = :tipodoccobranca,
                        CONDPGTO = :condpgto,
                        CONTA = :conta,
                        GENERO = :genero,
                        CENTROCUSTO = :centrocusto,
                        UPDATED_USER = :updated_user,
                        UPDATED_AT = :updated_at
                    WHERE ID = :id";

            $banco->prepare($sql);
            $banco->bindValue(':situacaoinclusao', $this->situacaoinclusao !== '' && $this->situacaoinclusao !== null ? $this->situacaoinclusao : 0);
            $banco->bindValue(':sitagatendimento', $this->sitagatendimento !== '' && $this->sitagatendimento !== null ? $this->sitagatendimento : 0);
            $banco->bindValue(':sitematendimento', $this->sitematendimento !== '' && $this->sitematendimento !== null ? $this->sitematendimento : 0);
            $banco->bindValue(':sitsolicitarpeca', $this->sitsolicitarpeca !== '' && $this->sitsolicitarpeca !== null ? $this->sitsolicitarpeca : 0);
            $banco->bindValue(':sitagpeca', $this->sitagpeca !== '' && $this->sitagpeca !== null ? $this->sitagpeca : 0);
            $banco->bindValue(':sitpecarecebida', $this->sitpecarecebida !== '' && $this->sitpecarecebida !== null ? $this->sitpecarecebida : 0);
            $banco->bindValue(':sitaporcamento', $this->sitaporcamento !== '' && $this->sitaporcamento !== null ? $this->sitaporcamento : 0);
            $banco->bindValue(':sitfinalizado', $this->sitfinalizado !== '' && $this->sitfinalizado !== null ? $this->sitfinalizado : 0);
            $banco->bindValue(':localatendimento', $this->localatendimento !== '' && $this->localatendimento !== null ? $this->localatendimento : null);
            $banco->bindValue(':tipointervencao', $this->tipointervencao !== '' && $this->tipointervencao !== null ? $this->tipointervencao : null);
            $banco->bindValue(':msgatendimento', $this->msgatendimento ?? '');
            $banco->bindValue(':msgorcamento', $this->msgorcamento ?? '');
            $banco->bindValue(':controleestoque', $this->controleestoque !== '' && $this->controleestoque !== null ? $this->controleestoque : null);
            $banco->bindValue(':tipodoccobranca', $this->tipodoccobranca !== '' && $this->tipodoccobranca !== null ? $this->tipodoccobranca : null);
            $banco->bindValue(':condpgto', $this->condpgto !== '' && $this->condpgto !== null ? $this->condpgto : null);
            $banco->bindValue(':conta', $this->conta !== '' && $this->conta !== null ? $this->conta : null);
            $banco->bindValue(':genero', $this->genero !== '' && $this->genero !== null ? $this->genero : null);
            $banco->bindValue(':centrocusto', $this->centrocusto !== '' && $this->centrocusto !== null ? $this->centrocusto : null);
            $banco->bindValue(':updated_user', $this->m_userid);
            $banco->bindValue(':updated_at', date('Y-m-d H:i:s'));
            $banco->bindValue(':id', $this->id);
            $banco->execute();

            return true;
        } catch (Exception $e) {
            error_log('[c_parametro CAT] alteraParametro: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Exclui parâmetro CAT
     * @return mixed true em sucesso ou mensagem de erro
     */
    public function excluiParametro($id)
    {
        try {
            if (empty($id)) {
                throw new Exception('ID do parâmetro é obrigatório para exclusão');
            }

            $banco = new c_banco_pdo();
            $sql = "DELETE FROM CAT_PARAMETROS WHERE ID = :id";
            $banco->prepare($sql);
            $banco->bindValue(':id', $id);
            $banco->execute();

            return true;
        } catch (Exception $e) {
            error_log('[c_parametro CAT] excluiParametro: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Seleciona parâmetro por ID
     */
    public function selecionaParametro($id): array
    {
        try {
            if (empty($id)) {
                return [];
            }

            $banco = new c_banco_pdo();
            $sql = "SELECT * FROM CAT_PARAMETROS WHERE ID = ?";
            $banco->prepare($sql);
            $banco->bindValue(1, $id);
            $banco->execute();
            $result = $banco->fetchAll();

            return $result ? $result[0] : [];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Lista todos os parâmetros CAT
     */
    public function selecionaTodosParametros()
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT p.*, cc.DESCRICAO AS CENTROCUSTO_DESC
                    FROM CAT_PARAMETROS p
                    LEFT JOIN FIN_CENTRO_CUSTO cc ON cc.CENTROCUSTO = p.CENTROCUSTO
                    ORDER BY p.ID";
            $banco->prepare($sql);
            $banco->execute();

            return $banco->fetchAll();
        } catch (Exception $e) {
            error_log('[c_parametro CAT] selecionaTodosParametros: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Filtra parâmetros CAT por texto
     */
    public function selecionaParametrosFiltrados($filtro)
    {
        try {
            $banco = new c_banco_pdo();
            $termo = '%' . $filtro . '%';
            $sql = "SELECT p.*, cc.DESCRICAO AS CENTROCUSTO_DESC
                    FROM CAT_PARAMETROS p
                    LEFT JOIN FIN_CENTRO_CUSTO cc ON cc.CENTROCUSTO = p.CENTROCUSTO
                    WHERE p.MSGATENDIMENTO LIKE ?
                       OR p.MSGORCAMENTO LIKE ?
                       OR cc.DESCRICAO LIKE ?
                       OR CAST(p.ID AS CHAR) LIKE ?
                    ORDER BY p.ID";
            $banco->prepare($sql);
            $banco->bindValue(1, $termo);
            $banco->bindValue(2, $termo);
            $banco->bindValue(3, $termo);
            $banco->bindValue(4, $termo);
            $banco->execute();

            return $banco->fetchAll();
        } catch (Exception $e) {
            error_log('[c_parametro CAT] selecionaParametrosFiltrados: ' . $e->getMessage());
            return [];
        }
    }

    public function selecionaSituacoes()
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT ID, DESCRICAO FROM CAT_SITUACAO WHERE ATIVO = '1' ORDER BY DESCRICAO";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids = [];
            $texts = [];
            foreach ($result as $row) {
                $ids[] = $row['ID'];
                $texts[] = $row['DESCRICAO'];
            }

            return ['id' => $ids, 'text' => $texts];
        } catch (Exception $e) {
            return ['id' => [], 'text' => []];
        }
    }

    public function selecionaCondicoesPagamento()
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT ID, DESCRICAO FROM FAT_COND_PGTO WHERE SITUACAOLCTO = 'A' ORDER BY DESCRICAO";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids = [];
            $texts = [];
            foreach ($result as $row) {
                $ids[] = $row['ID'];
                $texts[] = $row['DESCRICAO'];
            }

            return ['id' => $ids, 'text' => $texts];
        } catch (Exception $e) {
            return ['id' => [], 'text' => []];
        }
    }

    public function selecionaContas()
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT CONTA, NOMEINTERNO FROM FIN_CONTA WHERE STATUS = 'A' ORDER BY NOMEINTERNO";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids = [];
            $texts = [];
            foreach ($result as $row) {
                $ids[] = $row['CONTA'];
                $texts[] = $row['NOMEINTERNO'];
            }

            return ['id' => $ids, 'text' => $texts];
        } catch (Exception $e) {
            return ['id' => [], 'text' => []];
        }
    }

    public function selecionaGeneros()
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT TIPO, DESCRICAO FROM FIN_GENERO ORDER BY DESCRICAO";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids = [];
            $texts = [];
            foreach ($result as $row) {
                $ids[] = $row['TIPO'];
                $texts[] = $row['DESCRICAO'];
            }

            return ['id' => $ids, 'text' => $texts];
        } catch (Exception $e) {
            return ['id' => [], 'text' => []];
        }
    }

    public function selecionaCentrosCusto()
    {
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT CENTROCUSTO, DESCRICAO FROM FIN_CENTRO_CUSTO WHERE ATIVO = 'S' ORDER BY DESCRICAO";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids = [];
            $texts = [];
            foreach ($result as $row) {
                $ids[] = $row['CENTROCUSTO'];
                $texts[] = $row['DESCRICAO'];
            }

            return ['id' => $ids, 'text' => $texts];
        } catch (Exception $e) {
            return ['id' => [], 'text' => []];
        }
    }
}
