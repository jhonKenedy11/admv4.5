<?php
/**
 * @package ADM v4.5
 * @name c_parametro
 * @version 4.5.0
 * @copyright 2025
 * @link https://www.admv4.com.br
 * @author Sistema ADM
 * @date 15/01/2025
 * 
 * Classe para administração dos parâmetros de estoque
 * Responsável por gerenciar configurações do módulo de estoque
 */

$dir = dirname(__FILE__);
require_once($dir . '/../../bib/c_database_pdo.php');
require_once($dir . '/../../bib/c_user.php');

class c_parametro extends c_user
{
    // Propriedades privadas para cada campo da tabela EST_PARAMETRO
    protected $filial;
    protected $cfop;
    protected $natoperacao;
    protected $condpgto;
    protected $generomovimento;
    protected $genero;
    protected $genero_extrato;
    protected $conta;
    protected $serie;
    protected $modofin;
    protected $tipodoc;
    protected $natopentrada;
    protected $clientepadrao;
    protected $modelo;
    protected $grupopadrao;
    protected $consultaestoquezero;
    protected $controlaestoque;
    protected $integrafin;
    protected $validanfauto;
    protected $centrocusto;
    protected $tipovalidacao;
    protected $percdescmaximo;
    protected $precobase;
    protected $percalculo;

    
    // Novos campos solicitados
    protected $nfs_servico;
    protected $nfs_situacao_tributaria;
    protected $nfs_inss;
    protected $nfs_pis;
    protected $nfs_cofins;
    protected $nfs_ir;
    protected $nfs_contribuicao_social;
    protected $nfs_parcela;
    protected $nfs_serie;
    protected $nfs_user;
    protected $nfs_password;
    
    // Campos para controle de cálculo de IPI e ST no custo de reposição
    protected $calcula_ipi_custo_reposicao;
    protected $calcula_st_custo_reposicao;
    protected $xmlconferirestoque;
    protected $xmlmanterorigemcst;

    /**
     * Inclui novos parâmetros de estoque
     * Usa as propriedades da classe diretamente
     * @return mixed ID do registro inserido ou mensagem de erro
     */
    public function incluiParametro()
    {
        try {
            if (empty($this->filial)) {
                throw new Exception('Filial é obrigatória');
            }
            if (empty($this->modelo)) {
                throw new Exception('Modelo é obrigatório');
            }

            if (empty($this->centrocusto) && !empty($this->filial)) {
                $this->centrocusto = $this->filial;
            }

            $banco = new c_banco_pdo();
            
            $sqlVerifica = "SELECT COUNT(*) as total FROM EST_PARAMETRO WHERE FILIAL = :filial AND MODELO = :modelo";
            $banco->prepare($sqlVerifica);
            $banco->bindValue(':filial', $this->filial);
            $banco->bindValue(':modelo', $this->modelo);
            $banco->execute();
            $resultado = $banco->fetchAll();
            
            if ($resultado[0]['total'] > 0) {
                throw new Exception('Já existem parâmetros cadastrados para esta filial e modelo');
            }

            $sql = "INSERT INTO EST_PARAMETRO (
                        FILIAL, CFOP, NATOPERACAO, CONDPGTO, GENEROMOVIMENTO, 
                        GENERO, GENERO_EXTRATO, CONTA, SERIE, MODOFIN, TIPODOC, NATOPENTRADA, 
                        CLIENTEPADRAO, MODELO, GRUPOPADRAO, CONSULTAESTOQUEZERO, 
                        CONTROLAESTOQUE, INTEGRAFIN, VALIDANFAUTO, CENTROCUSTO, 
                        TIPOVALIDACAO, PERCDESCMAXIMO, PRECOBASE, PERCALCULO, 
                        CALCULA_IPI_CUSTO_REPOSICAO, CALCULA_ST_CUSTO_REPOSICAO,
                        XMLCONFERIRESTOQUE, XMLMANTERORIGEMCST,
                        NFS_SERIE, NFS_SITUACAO_TRIBUTARIA, NFS_SERVICO, NFS_INSS, 
                        NFS_PIS, NFS_COFINS, NFS_IR, NFS_CONTRIBUICAO_SOCIAL, NFS_PARCELA, 
                        NFS_USER, NFS_PASSWORD
                    ) VALUES (
                        :filial, :cfop, :natoperacao, :condpgto, :generomovimento,
                        :genero, :genero_extrato, :conta, :serie, :modofin, :tipodoc, :natopentrada,
                        :clientepadrao, :modelo, :grupopadrao, :consultaestoquezero,
                        :controlaestoque, :integrafin, :validanfauto, :centrocusto,
                        :tipovalidacao, :percdescmaximo, :precobase, :percalculo,
                        :calcula_ipi_custo_reposicao, :calcula_st_custo_reposicao,
                        :xmlconferirestoque, :xmlmanterorigemcst,
                        :nfs_serie, :nfs_situacao_tributaria, :nfs_servico, :nfs_inss,
                        :nfs_pis, :nfs_cofins, :nfs_ir, :nfs_contribuicao_social, :nfs_parcela,
                        :nfs_user, :nfs_password
                    )";

            $banco->prepare($sql);
            $banco->bindValue(':filial', $this->filial);
            $banco->bindValue(':cfop', $this->cfop);
            $banco->bindValue(':natoperacao', $this->natoperacao);
            $banco->bindValue(':condpgto', $this->condpgto);
            $banco->bindValue(':generomovimento', $this->generomovimento);
            $banco->bindValue(':genero', $this->genero);
            $banco->bindValue(':genero_extrato', $this->genero_extrato);
            $banco->bindValue(':conta', $this->conta);
            $banco->bindValue(':serie', $this->serie);
            $banco->bindValue(':modofin', $this->modofin);
            $banco->bindValue(':tipodoc', $this->tipodoc);
            $banco->bindValue(':natopentrada', $this->natopentrada);
            $banco->bindValue(':clientepadrao', $this->clientepadrao);
            $banco->bindValue(':modelo', $this->modelo);
            $banco->bindValue(':grupopadrao', $this->grupopadrao);
            $banco->bindValue(':consultaestoquezero', $this->consultaestoquezero);
            $banco->bindValue(':controlaestoque', $this->controlaestoque);
            $banco->bindValue(':integrafin', $this->integrafin);
            $banco->bindValue(':validanfauto', $this->validanfauto);
            $banco->bindValue(':centrocusto', $this->centrocusto);
            $banco->bindValue(':tipovalidacao', $this->tipovalidacao);
            $banco->bindValue(':percdescmaximo', $this->percdescmaximo);
            $banco->bindValue(':precobase', $this->precobase);
            $banco->bindValue(':percalculo', $this->percalculo);
            $banco->bindValue(':calcula_ipi_custo_reposicao', $this->calcula_ipi_custo_reposicao ?? 'N');
            $banco->bindValue(':calcula_st_custo_reposicao', $this->calcula_st_custo_reposicao ?? 'N');
            $banco->bindValue(':xmlconferirestoque', $this->xmlconferirestoque ?? 'N');
            $banco->bindValue(':xmlmanterorigemcst', $this->xmlmanterorigemcst ?? 'S');
            $banco->bindValue(':nfs_serie', $this->nfs_serie);
            $banco->bindValue(':nfs_situacao_tributaria', $this->nfs_situacao_tributaria);
            $banco->bindValue(':nfs_servico', $this->nfs_servico);
            $banco->bindValue(':nfs_inss', $this->nfs_inss);
            $banco->bindValue(':nfs_pis', $this->nfs_pis);
            $banco->bindValue(':nfs_cofins', $this->nfs_cofins);
            $banco->bindValue(':nfs_ir', $this->nfs_ir);
            $banco->bindValue(':nfs_contribuicao_social', $this->nfs_contribuicao_social);
            $banco->bindValue(':nfs_parcela', $this->nfs_parcela);
            $banco->bindValue(':nfs_user', $this->nfs_user);
            $banco->bindValue(':nfs_password', $this->nfs_password);
            
            $banco->execute();
            return true;

        } catch (Exception $e) {
            error_log('[c_parametro EST] incluiParametro: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Altera parâmetros de estoque existentes
     * Usa as propriedades da classe diretamente
     * @return mixed true em caso de sucesso ou mensagem de erro
     */
    public function alteraParametro()
    {
        try {
            if (empty($this->filial) || empty($this->modelo)) {
                throw new Exception('Filial e modelo são obrigatórios para alteração');
            }

            if (empty($this->centrocusto) && !empty($this->filial)) {
                $this->centrocusto = $this->filial;
            }

            $banco = new c_banco_pdo();
            
            $sql = "UPDATE EST_PARAMETRO SET 
                        CFOP = :cfop,
                        NATOPERACAO = :natoperacao,
                        CONDPGTO = :condpgto,
                        GENEROMOVIMENTO = :generomovimento,
                        GENERO = :genero,
                        GENERO_EXTRATO = :genero_extrato,
                        CONTA = :conta,
                        SERIE = :serie,
                        MODOFIN = :modofin,
                        TIPODOC = :tipodoc,
                        NATOPENTRADA = :natopentrada,
                        CLIENTEPADRAO = :clientepadrao,
                        GRUPOPADRAO = :grupopadrao,
                        CONSULTAESTOQUEZERO = :consultaestoquezero,
                        CONTROLAESTOQUE = :controlaestoque,
                        INTEGRAFIN = :integrafin,
                        VALIDANFAUTO = :validanfauto,
                        CENTROCUSTO = :centrocusto,
                        TIPOVALIDACAO = :tipovalidacao,
                        PERCDESCMAXIMO = :percdescmaximo,
                        PRECOBASE = :precobase,
                        PERCALCULO = :percalculo,
                        CALCULA_IPI_CUSTO_REPOSICAO = :calcula_ipi_custo_reposicao,
                        CALCULA_ST_CUSTO_REPOSICAO = :calcula_st_custo_reposicao,
                        XMLCONFERIRESTOQUE = :xmlconferirestoque,
                        XMLMANTERORIGEMCST = :xmlmanterorigemcst,
                        NFS_SERIE = :nfs_serie,
                        NFS_SITUACAO_TRIBUTARIA = :nfs_situacao_tributaria,
                        NFS_SERVICO = :nfs_servico,
                        NFS_INSS = :nfs_inss,
                        NFS_PIS = :nfs_pis,
                        NFS_COFINS = :nfs_cofins,
                        NFS_IR = :nfs_ir,
                        NFS_CONTRIBUICAO_SOCIAL = :nfs_contribuicao_social,
                        NFS_PARCELA = :nfs_parcela,
                        NFS_USER = :nfs_user,
                        NFS_PASSWORD = :nfs_password
                    WHERE FILIAL = :filial AND MODELO = :modelo";

            $banco->prepare($sql);
            $banco->bindValue(':cfop', $this->cfop);
            $banco->bindValue(':natoperacao', $this->natoperacao);
            $banco->bindValue(':condpgto', $this->condpgto);
            $banco->bindValue(':generomovimento', $this->generomovimento);
            $banco->bindValue(':genero', $this->genero);
            $banco->bindValue(':genero_extrato', $this->genero_extrato);
            $banco->bindValue(':conta', $this->conta);
            $banco->bindValue(':serie', $this->serie);
            $banco->bindValue(':modofin', $this->modofin);
            $banco->bindValue(':tipodoc', $this->tipodoc);
            $banco->bindValue(':natopentrada', $this->natopentrada);
            $banco->bindValue(':clientepadrao', $this->clientepadrao);
            $banco->bindValue(':grupopadrao', $this->grupopadrao);
            $banco->bindValue(':consultaestoquezero', $this->consultaestoquezero);
            $banco->bindValue(':controlaestoque', $this->controlaestoque);
            $banco->bindValue(':integrafin', $this->integrafin);
            $banco->bindValue(':validanfauto', $this->validanfauto);
            $banco->bindValue(':centrocusto', $this->centrocusto);
            $banco->bindValue(':tipovalidacao', $this->tipovalidacao);
            $banco->bindValue(':percdescmaximo', $this->percdescmaximo);
            $banco->bindValue(':precobase', $this->precobase);
            $banco->bindValue(':percalculo', $this->percalculo);
            $banco->bindValue(':calcula_ipi_custo_reposicao', $this->calcula_ipi_custo_reposicao ?? 'N');
            $banco->bindValue(':calcula_st_custo_reposicao', $this->calcula_st_custo_reposicao ?? 'N');
            $banco->bindValue(':xmlconferirestoque', $this->xmlconferirestoque ?? 'N');
            $banco->bindValue(':xmlmanterorigemcst', $this->xmlmanterorigemcst ?? 'S');
            $banco->bindValue(':nfs_serie', $this->nfs_serie);
            $banco->bindValue(':nfs_situacao_tributaria', $this->nfs_situacao_tributaria);
            $banco->bindValue(':nfs_servico', $this->nfs_servico);
            $banco->bindValue(':nfs_inss', $this->nfs_inss);
            $banco->bindValue(':nfs_pis', $this->nfs_pis);
            $banco->bindValue(':nfs_cofins', $this->nfs_cofins);
            $banco->bindValue(':nfs_ir', $this->nfs_ir);
            $banco->bindValue(':nfs_contribuicao_social', $this->nfs_contribuicao_social);
            $banco->bindValue(':nfs_parcela', $this->nfs_parcela);
            $banco->bindValue(':nfs_user', $this->nfs_user);
            $banco->bindValue(':nfs_password', $this->nfs_password);
            $banco->bindValue(':filial', $this->filial);
            $banco->bindValue(':modelo', (string) $this->modelo);
            $banco->execute();

            return true;

        } catch (Exception $e) {
            error_log('[c_parametro EST] alteraParametro: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Seleciona parâmetro por FILIAL (centro de custo) + MODELO
     * @param mixed $filial Centro de custo da filial
     * @param mixed $modelo Modelo do documento fiscal
     * @return array Array com os dados do parâmetro
     */
    public function selecionaParametro($filial, $modelo) : array
    {
        try {
            if (empty($filial) || $modelo === null || $modelo === '') {
                return [];
            }

            $banco = new c_banco_pdo();

            $sql = "SELECT 
                        p.*,
                        e.NOMEEMPRESA,
                        e.CNPJ,
                        cp.DESCRICAO AS CONDPGTO_DESC,
                        fg.DESCRICAO AS GENERO_DESC,
                        fc.NOMEINTERNO AS CONTA_DESC
                    FROM EST_PARAMETRO p
                    LEFT JOIN AMB_EMPRESA e ON e.CENTROCUSTO = p.FILIAL
                    LEFT JOIN FAT_COND_PGTO cp ON cp.ID = p.CONDPGTO
                    LEFT JOIN (
                        SELECT GENERO, MIN(DESCRICAO) AS DESCRICAO
                        FROM FIN_GENERO
                        GROUP BY GENERO
                    ) fg ON fg.GENERO = p.GENERO
                    LEFT JOIN FIN_CONTA fc ON fc.CONTA = p.CONTA
                    WHERE p.FILIAL = ? AND p.MODELO = ?
                    LIMIT 1";

            $banco->prepare($sql);
            $banco->bindValue(1, $filial);
            $banco->bindValue(2, (string) $modelo);
            $banco->execute();
            $result = $banco->fetchAll();

            return !empty($result[0]) ? $result[0] : [];

        } catch (Exception $e) {
            error_log('[c_parametro EST] selecionaParametro: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Seleciona todos os parâmetros
     * @return array Array com todos os parâmetros
     */
    public function selecionaTodosParametros()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT p.*, e.NOMEEMPRESA, e.NOMEFANTASIA, e.CNPJ
                    FROM EST_PARAMETRO p
                    LEFT JOIN AMB_EMPRESA e ON e.CENTROCUSTO = p.FILIAL
                    ORDER BY e.NOMEEMPRESA, p.MODELO";

            $banco->prepare($sql);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            error_log('[c_parametro EST] selecionaTodosParametros: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca opções booleanas (Sim/Não) para radios
     * @return array Arrays id e text para html_options
     */
    public function selecionaBooleanos(): array
    {
        try {
            $banco = new c_banco_pdo();

            $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO
                    FROM AMB_DDM
                    WHERE ALIAS = 'AMB_MENU' AND CAMPO = 'BOOLEAN'
                    ORDER BY TIPO";

            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids = [];
            $texts = [];
            foreach ($result as $row) {
                $ids[] = trim($row['ID']);
                $texts[] = ucwords(strtolower(trim($row['DESCRICAO'])));
            }

            return ['id' => $ids, 'text' => $texts];

        } catch (Exception $e) {
            error_log('[c_parametro EST] selecionaBooleanos: ' . $e->getMessage());
            return ['id' => ['S', 'N'], 'text' => ['Sim', 'Não']];
        }
    }

    /**
     * Busca empresas para o combo
     * @return array Array com as empresas
     */
    public function selecionaEmpresas()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT CENTROCUSTO, NOMEEMPRESA, NOMEFANTASIA 
                    FROM AMB_EMPRESA 
                    ORDER BY NOMEEMPRESA";

            $banco->prepare($sql);
            $banco->execute();
            
            $result = $banco->fetchAll();
            
            // Arrays separados para IDs e textos
            $ids = array();
            $texts = array();

            foreach ($result as $row) {
                $ids[] = trim($row['CENTROCUSTO']);
                $nome = trim($row['NOMEFANTASIA'] ?? '') ?: trim($row['NOMEEMPRESA']);
                $texts[] = $nome;
            }

            // Retorna array com IDs e textos separados
            return array(
                'id' => $ids,
                'text' => $texts
            );

        } catch (Exception $e) {
            return array(
                'id' => array(),
                'text' => array()
            );
        }
    }

    /**
     * Busca condições de pagamento para o combo
     * @return array Array com as condições de pagamento
     */
    public function selecionaCondicoesPagamento()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT ID, DESCRICAO 
                    FROM FAT_COND_PGTO 
                    ORDER BY DESCRICAO";

            $banco->prepare($sql);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca gêneros para combo (opcionalmente filtrados por tipo de lançamento).
     *
     * @param string|null $tipolancamento R=recebimento (saída), P=pagamento (entrada)
     * @return array<int,array<string,mixed>>
     */
    public function selecionaGeneros(?string $tipolancamento = null) : array
    {
        try {
            $banco = new c_banco_pdo();

            $sql = "SELECT GENERO as ID, DESCRICAO, TIPOLANCAMENTO
                    FROM FIN_GENERO";
            if ($tipolancamento !== null && $tipolancamento !== '') {
                $sql .= " WHERE TIPOLANCAMENTO = ?";
            }
            $sql .= " ORDER BY DESCRICAO";

            $banco->prepare($sql);
            if ($tipolancamento !== null && $tipolancamento !== '') {
                $banco->bindValue(1, strtoupper($tipolancamento));
            }
            $banco->execute();

            return $banco->fetchAll() ?: [];

        } catch (Exception $e) {
            error_log('[c_parametro EST] selecionaGeneros: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Inclui gênero já gravado na lista do combo (ex.: valor legado fora do filtro).
     */
    public function incluiGeneroSelecionado(array $generos, ?string $generoId) : array
    {
        $generoId = trim((string) $generoId);
        if ($generoId === '') {
            return $generos;
        }

        foreach ($generos as $genero) {
            if ((string) ($genero['ID'] ?? '') === $generoId) {
                return $generos;
            }
        }

        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT GENERO as ID, DESCRICAO, TIPOLANCAMENTO FROM FIN_GENERO WHERE GENERO = ? LIMIT 1";
            $banco->prepare($sql);
            $banco->bindValue(1, $generoId);
            $banco->execute();
            $extra = $banco->fetchAll();
            if (!empty($extra[0])) {
                array_unshift($generos, $extra[0]);
            }
        } catch (Exception $e) {
            error_log('[c_parametro EST] incluiGeneroSelecionado: ' . $e->getMessage());
        }

        return $generos;
    }

    /**
     * Busca contas para o combo
     * @return array Array com as contas
     */
    public function selecionaContas()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT CONTA as ID, NOMEINTERNO as DESCRICAO 
                    FROM FIN_CONTA 
                    ORDER BY NOMEINTERNO";

            $banco->prepare($sql);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca grupos para o combo
     * @return array Array com os grupos
     */
    public function selecionaGrupos()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT GRUPO as ID, DESCRICAO 
                    FROM EST_GRUPO 
                    ORDER BY DESCRICAO";

            $banco->prepare($sql);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca clientes para o combo
     * @return array Array com os clientes
     */
    public function selecionaClientes()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT CLIENTE as ID, NOME as DESCRICAO 
                    FROM FIN_CLIENTE 
                    ORDER BY NOME";

            $banco->prepare($sql);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca centros de custo para o combo
     * @return array Array com os centros de custo
     */
    public function selecionaCentrosCusto()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT CENTROCUSTO as ID, DESCRICAO 
                    FROM FIN_CENTRO_CUSTO 
                    ORDER BY DESCRICAO";

            $banco->prepare($sql);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Busca serviços para o combo
     * @return array Array com os serviços
     */
    public function selecionaServicos()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT ID, CONCAT(CODIGO, ' - ', SERVICO) AS SERVICO
                    FROM EST_SERVICOS_CODIGOS
                    ORDER BY ID";
            
            $banco->prepare($sql);
            $banco->execute();
            
            $result = $banco->fetchAll();
            
            // Arrays separados para IDs e textos
            $ids = array();
            $texts = array();
            
            foreach ($result as $row) {
                $ids[] = trim($row['ID']);
                $texts[] = trim($row['SERVICO']);
            }
            
            // Retorna array com IDs e textos separados
            return array(
                'id' => $ids,
                'text' => $texts
            );

        } catch (Exception $e) {

            return array(
                'id' => array('1'),
                'text' => array('Erro ao buscar serviços')
            );
        }
    }

    /**
     * Busca situações tributárias para o combo
     * @return array Array com as situações tributárias
     */
    public function selecionaSituacaoTributaria()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT ID, CONCAT(SIGLA, ' - ', DESCRICAO) AS DESCRICAO
                    FROM EST_SERVICOS_SITUACAO_TRIBUTARIA
                    WHERE 1 = 1 
                    ORDER BY CODIGO ASC";
            
            $banco->prepare($sql);
            $banco->execute();
            
            $result = $banco->fetchAll();
            
            // Arrays separados para IDs e textos
            $ids = array();
            $texts = array();
            
            foreach ($result as $row) {
                $ids[] = trim($row['ID']);
                $texts[] = trim($row['DESCRICAO']);
            }
            
            // Retorna array com IDs e textos separados
            return array(
                'id' => $ids,
                'text' => $texts
            );

        } catch (Exception $e) {
            return array(
                'id' => array('1'),
                'text' => array('Erro ao buscar situações tributárias')
            );
        }
    }

    /**
     * Busca parcelas/condições de pagamento para o combo
     * @return array Array com as parcelas
     */
    public function selecionaParcelas()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT ID, CONCAT(DESCRICAO, ' (', NUMPARCELAS, 'x)') AS DESCRICAO  
                    FROM FAT_COND_PGTO
                    WHERE 1 = 1 
                    ORDER BY DESCRICAO ASC";
            
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids = array();
            $texts = array();

            foreach ($result as $row){
                $ids[] = trim($row['ID']);
                $texts[] =  trim($row['DESCRICAO']);
            }

            return array(
                'id' => $ids,
                'text' => $texts
            );

        } catch (Exception $e) {

            return array(
                'id' => array('1'),
                'text' => array('Não foi possível buscar as parcelas')
            );
        }
    }

    /**
     * Filtra parâmetros por nome da empresa
     * @param string $filtro_empresa Nome da empresa para filtrar
     * @return array Array com os parâmetros filtrados
     */
    public function selecionaParametrosFiltrados($filtro_empresa)
    {
        try {
            $banco = new c_banco_pdo();
            $termo = '%' . $filtro_empresa . '%';

            $sql = "SELECT 
                        p.FILIAL, p.CFOP, p.NATOPERACAO, p.CONDPGTO, p.GENEROMOVIMENTO, 
                        p.GENERO, p.CONTA, p.SERIE, p.MODOFIN, p.TIPODOC, p.NATOPENTRADA, 
                        p.CLIENTEPADRAO, p.MODELO, p.GRUPOPADRAO, p.CONSULTAESTOQUEZERO, 
                        p.CONTROLAESTOQUE, p.INTEGRAFIN, p.VALIDANFAUTO, p.CENTROCUSTO, 
                        p.TIPOVALIDACAO, p.PERCDESCMAXIMO, p.PRECOBASE, p.PERCALCULO, 
                        p.NFS_SERIE, p.NFS_SITUACAO_TRIBUTARIA,
                        e.NOMEEMPRESA, e.NOMEFANTASIA, e.CNPJ
                    FROM EST_PARAMETRO p
                    LEFT JOIN AMB_EMPRESA e ON e.CENTROCUSTO = p.FILIAL
                    WHERE e.NOMEEMPRESA LIKE ? OR e.NOMEFANTASIA LIKE ?
                    ORDER BY e.NOMEEMPRESA, p.MODELO";

            $banco->prepare($sql);
            $banco->bindValue(1, $termo);
            $banco->bindValue(2, $termo);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            error_log('[c_parametro EST] selecionaParametrosFiltrados: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Exclui parâmetros de estoque por FILIAL + MODELO
     * @return mixed true em caso de sucesso ou mensagem de erro
     */
    public function excluiParametro()
    {
        try {
            if (empty($this->filial) || empty($this->modelo)) {
                throw new Exception('Filial e modelo são obrigatórios para exclusão');
            }

            $banco = new c_banco_pdo();

            $sqlVerifica = "SELECT COUNT(*) as total FROM EST_PARAMETRO WHERE FILIAL = ? AND MODELO = ?";
            $banco->prepare($sqlVerifica);
            $banco->bindValue(1, $this->filial);
            $banco->bindValue(2, (string) $this->modelo);
            $banco->execute();
            $resultado = $banco->fetchAll();

            if ($resultado[0]['total'] == 0) {
                throw new Exception('Parâmetro não encontrado para exclusão');
            }

            $sql = "DELETE FROM EST_PARAMETRO WHERE FILIAL = ? AND MODELO = ?";
            $banco->prepare($sql);
            $banco->bindValue(1, $this->filial);
            $banco->bindValue(2, (string) $this->modelo);
            $banco->execute();

            return true;

        } catch (Exception $e) {
            error_log('[c_parametro EST] excluiParametro: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function searchEstParametroFinanceiro($centrocusto) : array
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT p.GENERO_EXTRATO, e.TIPOLANCAMENTO FROM EST_PARAMETRO p
                    LEFT JOIN FIN_GENERO e ON e.GENERO = p.GENERO_EXTRATO 
                    WHERE COALESCE(p.CENTROCUSTO, p.FILIAL) = ?";
            $banco->prepare($sql);
            $banco->bindValue(1, $centrocusto);
            $banco->execute();
            return $banco->fetchAll(); 
        } catch (Exception $e) {
            error_log('[c_parametro EST] searchEstParametroFinanceiro: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Gênero financeiro padrão para notas de entrada (contas a pagar).
     * Distinto de GENERO, usado nos lançamentos de saída/recebimento.
     */
    public function getGeneroEntradaFinanceiro($centrocusto) : ?string
    {
        try {
            if (empty($centrocusto)) {
                return null;
            }

            $banco = new c_banco_pdo();
            $sql = "SELECT p.GENERO_EXTRATO FROM EST_PARAMETRO p
                    WHERE COALESCE(p.CENTROCUSTO, p.FILIAL) = ?
                    ORDER BY p.MODELO
                    LIMIT 1";
            $banco->prepare($sql);
            $banco->bindValue(1, $centrocusto);
            $banco->execute();
            $result = $banco->fetchAll();

            if (!empty($result[0]['GENERO_EXTRATO'])) {
                return $result[0]['GENERO_EXTRATO'];
            }

            return null;
        } catch (Exception $e) {
            error_log('[c_parametro EST] getGeneroEntradaFinanceiro: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Parâmetros financeiros de saída/recebimento por filial (NF pedido, gerencia pedido).
     *
     * @param int|string $centrocusto
     * @param string|null $modelo 55=NF-e, 65=NFC-e; null = primeiro registro da filial
     * @return array<string,mixed>
     */
    public function getParametroFinanceiroSaida($centrocusto, ?string $modelo = '55') : array
    {
        try {
            if (empty($centrocusto)) {
                return [];
            }

            $banco = new c_banco_pdo();
            $sql = "SELECT p.CFOP, p.NATOPERACAO, p.CONDPGTO, p.GENERO, p.CONTA, p.SERIE
                    FROM EST_PARAMETRO p
                    WHERE COALESCE(p.CENTROCUSTO, p.FILIAL) = ?";
            if ($modelo !== null && $modelo !== '') {
                $sql .= " AND p.MODELO = ?";
            }
            $sql .= " ORDER BY p.MODELO LIMIT 1";

            $banco->prepare($sql);
            $banco->bindValue(1, $centrocusto);
            if ($modelo !== null && $modelo !== '') {
                $banco->bindValue(2, $modelo);
            }
            $banco->execute();
            $result = $banco->fetchAll();

            return !empty($result[0]) ? $result[0] : [];
        } catch (Exception $e) {
            error_log('[c_parametro EST] getParametroFinanceiroSaida: ' . $e->getMessage());
            return [];
        }
    }
}