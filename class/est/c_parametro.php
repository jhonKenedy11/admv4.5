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
    protected $nfs_serie;
    protected $nfs_situacao_tributaria;
    
    // Novos campos solicitados
    protected $servico;
    protected $situacao_tributaria;
    protected $inss;
    protected $pis;
    protected $cofins;
    protected $ir;
    protected $contribuicao_social;
    protected $parcela;

    /**
     * Inclui novos parâmetros de estoque
     * @param array $dados_parametros Array com os dados dos parâmetros
     * @return mixed ID do registro inserido ou mensagem de erro
     */
    public function incluiParametro($dados_parametros)
    {
        try {
            // Validação de campos obrigatórios
            if (empty($dados_parametros['filial'])) {
                throw new Exception('Filial é obrigatória');
            }
            if (empty($dados_parametros['modelo'])) {
                throw new Exception('Modelo é obrigatório');
            }

            $banco = new c_banco_pdo();
            
            // Verificar se já existe parâmetro para esta filial e modelo
            $sqlVerifica = "SELECT COUNT(*) as total FROM EST_PARAMETRO WHERE FILIAL = ? AND MODELO = ?";
            $banco->prepare($sqlVerifica);
            $banco->bindValue(1, $dados_parametros['filial']);
            $banco->bindValue(2, $dados_parametros['modelo']);
            $banco->execute();
            $resultado = $banco->fetchAll();
            
            if ($resultado[0]['total'] > 0) {
                throw new Exception('Já existem parâmetros cadastrados para esta filial e modelo');
            }

            $sql = "INSERT INTO EST_PARAMETRO (
                        FILIAL, CFOP, NATOPERACAO, CONDPGTO, GENEROMOVIMENTO, 
                        GENERO, CONTA, SERIE, MODOFIN, TIPODOC, NATOPENTRADA, 
                        CLIENTEPADRAO, MODELO, GRUPOPADRAO, CONSULTAESTOQUEZERO, 
                        CONTROLAESTOQUE, INTEGRAFIN, VALIDANFAUTO, CENTROCUSTO, 
                        TIPOVALIDACAO, PERCDESCMAXIMO, PRECOBASE, PERCALCULO, 
                        NFS_SERIE, NFS_SITUACAO_TRIBUTARIA
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $banco->prepare($sql);
            $banco->bindValue(1, $dados_parametros['filial']);
            $banco->bindValue(2, $dados_parametros['cfop'] ?? '');
            $banco->bindValue(3, $dados_parametros['natoperacao'] ?? '');
            $banco->bindValue(4, $dados_parametros['condpgto'] ?? null);
            $banco->bindValue(5, $dados_parametros['generomovimento'] ?? '');
            $banco->bindValue(6, $dados_parametros['genero'] ?? '');
            $banco->bindValue(7, $dados_parametros['conta'] ?? null);
            $banco->bindValue(8, $dados_parametros['serie'] ?? '');
            $banco->bindValue(9, $dados_parametros['modofin'] ?? '');
            $banco->bindValue(10, $dados_parametros['tipodoc'] ?? '');
            $banco->bindValue(11, $dados_parametros['natopentrada'] ?? null);
            $banco->bindValue(12, $dados_parametros['clientepadrao'] ?? 1);
            $banco->bindValue(13, $dados_parametros['modelo'] ?? '55');
            $banco->bindValue(14, $dados_parametros['grupopadrao'] ?? '');
            $banco->bindValue(15, $dados_parametros['consultaestoquezero'] ?? 'S');
            $banco->bindValue(16, $dados_parametros['controlaestoque'] ?? 'S');
            $banco->bindValue(17, $dados_parametros['integrafin'] ?? 'S');
            $banco->bindValue(18, $dados_parametros['validanfauto'] ?? 'S');
            $banco->bindValue(19, $dados_parametros['centrocusto'] ?? null);
            $banco->bindValue(20, $dados_parametros['tipovalidacao'] ?? 'N');
            $banco->bindValue(21, $dados_parametros['percdescmaximo'] ?? 0.0000);
            $banco->bindValue(22, $dados_parametros['precobase'] ?? 'C');
            $banco->bindValue(23, $dados_parametros['percalculo'] ?? 0.0000);
            $banco->bindValue(24, $dados_parametros['nfs_serie'] ?? null);
            $banco->bindValue(25, $dados_parametros['nfs_situacao_tributaria'] ?? null);
            
            $banco->execute();
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Altera parâmetros de estoque existentes
     * @param array $dados_parametros Array com os dados dos parâmetros
     * @return mixed true em caso de sucesso ou mensagem de erro
     */
    public function alteraParametro($dados_parametros)
    {
        try {

            $banco = new c_banco_pdo();
            
            $sql = "UPDATE EST_PARAMETRO SET 
                        CFOP = ?, NATOPERACAO = ?, CONDPGTO = ?, GENEROMOVIMENTO = ?, 
                        GENERO = ?, CONTA = ?, SERIE = ?, MODOFIN = ?, TIPODOC = ?, 
                        NATOPENTRADA = ?, CLIENTEPADRAO = ?, GRUPOPADRAO = ?, 
                        CONSULTAESTOQUEZERO = ?, CONTROLAESTOQUE = ?, INTEGRAFIN = ?, 
                        VALIDANFAUTO = ?, CENTROCUSTO = ?, TIPOVALIDACAO = ?, 
                        PERCDESCMAXIMO = ?, PRECOBASE = ?, PERCALCULO = ?, 
                        NFS_SERIE = ?, NFS_SITUACAO_TRIBUTARIA = ?
                    WHERE FILIAL = ? AND MODELO = ?";

            $banco->prepare($sql);
            $banco->bindValue(1, $dados_parametros['cfop'] ?? '');
            $banco->bindValue(2, $dados_parametros['natoperacao'] ?? '');
            $banco->bindValue(3, $dados_parametros['condpgto'] ?? null);
            $banco->bindValue(4, $dados_parametros['generomovimento'] ?? '');
            $banco->bindValue(5, $dados_parametros['genero'] ?? '');
            $banco->bindValue(6, $dados_parametros['conta'] ?? null);
            $banco->bindValue(7, $dados_parametros['serie'] ?? '');
            $banco->bindValue(8, $dados_parametros['modofin'] ?? '');
            $banco->bindValue(9, $dados_parametros['tipodoc'] ?? '');
            $banco->bindValue(10, $dados_parametros['natopentrada'] ?? null);
            $banco->bindValue(11, $dados_parametros['clientepadrao'] ?? 1);
            $banco->bindValue(12, $dados_parametros['grupopadrao'] ?? '');
            $banco->bindValue(13, $dados_parametros['consultaestoquezero'] ?? 'S');
            $banco->bindValue(14, $dados_parametros['controlaestoque'] ?? 'S');
            $banco->bindValue(15, $dados_parametros['integrafin'] ?? 'S');
            $banco->bindValue(16, $dados_parametros['validanfauto'] ?? 'S');
            $banco->bindValue(17, $dados_parametros['centrocusto'] ?? null);
            $banco->bindValue(18, $dados_parametros['tipovalidacao'] ?? 'N');
            $banco->bindValue(19, $dados_parametros['percdescmaximo'] ?? 0.0000);
            $banco->bindValue(20, $dados_parametros['precobase'] ?? 'C');
            $banco->bindValue(21, $dados_parametros['percalculo'] ?? 0.0000);
            $banco->bindValue(22, $dados_parametros['nfs_serie'] ?? null);
            $banco->bindValue(23, $dados_parametros['nfs_situacao_tributaria'] ?? null);
            $banco->bindValue(24, $dados_parametros['filial']);
            $banco->bindValue(25, $dados_parametros['modelo']);
            
            $banco->execute();
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Seleciona parâmetros por filial e modelo
     * @param int $filial ID da filial
     * @param string $modelo Modelo do documento
     * @return array Array com os dados do parâmetro
     */
    public function selecionaParametro($id) : array
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT 
                        p.ID, p.FILIAL, p.CFOP, p.NATOPERACAO, p.CONDPGTO, p.GENEROMOVIMENTO, 
                        p.GENERO, p.CONTA, p.SERIE, p.MODOFIN, p.TIPODOC, p.NATOPENTRADA, 
                        p.CLIENTEPADRAO, p.MODELO, p.GRUPOPADRAO, p.CONSULTAESTOQUEZERO, 
                        p.CONTROLAESTOQUE, p.INTEGRAFIN, p.VALIDANFAUTO, p.CENTROCUSTO, 
                        p.TIPOVALIDACAO, p.PERCDESCMAXIMO, p.PRECOBASE, p.PERCALCULO, 
                        p.NFS_SERIE, p.NFS_SERVICO, p.NFS_INSS, p.NFS_PIS, p.NFS_COFINS, 
                        p.NFS_IR, p.NFS_CONTRIBUICAO_SOCIAL, p.NFS_PARCELA, e.NOMEEMPRESA, e.CNPJ, p.NFS_SITUACAO_TRIBUTARIA,
                        cp.DESCRICAO as CONDPGTO_DESC, fg.DESCRICAO as GENERO_DESC, fc.NOMEINTERNO as CONTA_DESC
                    FROM EST_PARAMETRO p
                    LEFT JOIN AMB_EMPRESA e ON e.EMPRESA = p.FILIAL
                    LEFT JOIN FAT_COND_PGTO cp ON cp.ID = p.CONDPGTO
                    LEFT JOIN FIN_GENERO fg ON fg.GENERO = p.GENERO
                    LEFT JOIN FIN_CONTA fc ON fc.CONTA = p.CONTA
                    WHERE p.ID = ?";

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
     * Seleciona todos os parâmetros
     * @return array Array com todos os parâmetros
     */
    public function selecionaTodosParametros()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT 
                        e.NOMEEMPRESA as NOMEEMPRESA, p.CFOP, p.SERIE, p.ID, e.CNPJ 
                    FROM EST_PARAMETRO p 
                    LEFT JOIN AMB_EMPRESA e ON e.CENTROCUSTO = p.CENTROCUSTO 
                    ORDER BY e.NOMEEMPRESA";

            $banco->prepare($sql);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            return [];
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
            
            $sql = "SELECT EMPRESA, NOMEEMPRESA, CNPJ 
                    FROM AMB_EMPRESA 
                    ORDER BY NOMEEMPRESA";

            $banco->prepare($sql);
            $banco->execute();
            
            $result = $banco->fetchAll();
            
            // Arrays separados para IDs e textos
            $ids = array();
            $texts = array();

            foreach ($result as $row) {
                $ids[] = trim($row['EMPRESA']);
                $texts[] = trim($row['NOMEEMPRESA']);
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
     * Busca gêneros para o combo
     * @return array Array com os gêneros
     */
    public function selecionaGeneros()
    {
        try {
            $banco = new c_banco_pdo();
            
            $sql = "SELECT GENERO as ID, DESCRICAO 
                    FROM FIN_GENERO 
                    ORDER BY DESCRICAO";

            $banco->prepare($sql);
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            return [];
        }
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
            
            $sql = "SELECT CLIENTE as ID, NOMECLIENTE as DESCRICAO 
                    FROM CRM_CLIENTE 
                    ORDER BY NOMECLIENTE";

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
                'id' => ('1'),
                'text' => ('Não foi possível buscar as parcelas')
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
            
            $sql = "SELECT 
                        p.FILIAL, p.CFOP, p.NATOPERACAO, p.CONDPGTO, p.GENEROMOVIMENTO, 
                        p.GENERO, p.CONTA, p.SERIE, p.MODOFIN, p.TIPODOC, p.NATOPENTRADA, 
                        p.CLIENTEPADRAO, p.MODELO, p.GRUPOPADRAO, p.CONSULTAESTOQUEZERO, 
                        p.CONTROLAESTOQUE, p.INTEGRAFIN, p.VALIDANFAUTO, p.CENTROCUSTO, 
                        p.TIPOVALIDACAO, p.PERCDESCMAXIMO, p.PRECOBASE, p.PERCALCULO, 
                        p.NFS_SERIE, p.NFS_SITUACAO_TRIBUTARIA,
                        e.NOMEEMPRESA, e.CNPJ
                    FROM EST_PARAMETRO p
                    LEFT JOIN AMB_EMPRESA e ON e.EMPRESA = p.FILIAL
                    WHERE e.NOMEEMPRESA LIKE ?
                    ORDER BY e.NOMEEMPRESA, p.MODELO";

            $banco->prepare($sql);
            $banco->bindValue(1, '%' . $filtro_empresa . '%');
            $banco->execute();
            
            return $banco->fetchAll();

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Exclui parâmetros de estoque
     * @param int $filial ID da filial
     * @param string $modelo Modelo do documento
     * @return mixed true em caso de sucesso ou mensagem de erro
     */
    public function excluiParametro($filial, $modelo)
    {
        try {
            // Validação de campos obrigatórios
            if (empty($filial)) {
                throw new Exception('Filial é obrigatória para exclusão');
            }
            if (empty($modelo)) {
                throw new Exception('Modelo é obrigatório para exclusão');
            }

            $banco = new c_banco_pdo();
            
            // Verificar se o parâmetro existe
            $sqlVerifica = "SELECT COUNT(*) as total FROM EST_PARAMETRO WHERE FILIAL = ? AND MODELO = ?";
            $banco->prepare($sqlVerifica);
            $banco->bindValue(1, $filial);
            $banco->bindValue(2, $modelo);
            $banco->execute();
            $resultado = $banco->fetchAll();
            
            if ($resultado[0]['total'] == 0) {
                throw new Exception('Parâmetro não encontrado para exclusão');
            }

            $sql = "DELETE FROM EST_PARAMETRO WHERE FILIAL = ? AND MODELO = ?";

            $banco->prepare($sql);
            $banco->bindValue(1, $filial);
            $banco->bindValue(2, $modelo);
            $banco->execute();
            
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
    * Monta array com dados para inclusão/alteração
    */
    public function montarArrayDados()
    {
        $dados = [
            'filial'                  => $this->filial,
            'cfop'                    => $this->cfop,
            'natoperacao'             => $this->natoperacao,
            'condpgto'                => $this->condpgto,
            'generomovimento'         => $this->generomovimento,
            'genero'                  => $this->genero,
            'conta'                   => $this->conta,
            'serie'                   => $this->serie,
            'modofin'                 => $this->modofin,
            'tipodoc'                 => $this->tipodoc,
            'natopentrada'            => $this->natopentrada,
            'clientepadrao'           => $this->clientepadrao,
            'modelo'                  => $this->modelo,
            'grupopadrao'             => $this->grupopadrao,
            'consultaestoquezero'     => $this->consultaestoquezero,
            'controlaestoque'         => $this->controlaestoque,
            'integrafin'              => $this->integrafin,
            'validanfauto'            => $this->validanfauto,
            'centrocusto'             => $this->centrocusto,
            'tipovalidacao'           => $this->tipovalidacao,
            'percdescmaximo'          => $this->percdescmaximo,
            'precobase'               => $this->precobase,
            'percalculo'              => $this->percalculo,
            'nfs_serie'               => $this->nfs_serie,
            'nfs_situacao_tributaria' => $this->nfs_situacao_tributaria,
            'servico'                 => $this->servico,
            'situacao_tributaria'     => $this->situacao_tributaria,
            'inss'                    => $this->inss,
            'pis'                     => $this->pis,
            'cofins'                  => $this->cofins,
            'ir'                      => $this->ir,
            'contribuicao_social'     => $this->contribuicao_social,
            'parcela'                 => $this->parcela
        ];

        return $dados;
    }
}