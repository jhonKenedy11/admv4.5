<?php

/**
 * @package   admv4.5
 * @name      c_estoque_relatorio
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva <joshua.silva@admsistemas.com.br>
 * @date      08/05/2025
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/est/c_produto.php");

//Class c_estoque_relatorio
class c_estoque_relatorio extends c_user
{

    private $id         = NULL; // VARCHAR(15)
    private $dataIni    = NULL; // Data inicial
    private $dataFim    = NULL; // Data final
    private $idGrupo    = NULL; // ID do grupo
    private $idProduto  = NULL; // ID do produto
    private $idLocalizacao = NULL; // ID da localização
    private $tipoMovimento = NULL; // Tipo de movimento (E/S)
    private $descProduto = NULL; // Descrição do produto para busca
    private $descCliente = NULL; // Descrição do cliente para busca
    private $idCentroCusto = NULL; // ID do centro de custo
    private $tipoRelatorio = NULL; // Tipo do relatório
    private $situacaoNota = NULL; // Situação da nota fiscal
    private $curvaAbc = NULL; // Tipo de curva ABC
    private $ordenacao = NULL; // Tipo de ordenação
    private $grupos = NULL; // Grupos de produtos
    private $tipoCurvaABC = NULL; // Tipo de curva ABC para ordenação

    /**
     * METODOS DE SETS E GETS
     */
    function getDataIni()
    {
        return $this->dataIni;
    }

    function setDataIni($dataIni)
    {
        $this->dataIni = $dataIni;
    }

    function getDataFim()
    {
        return $this->dataFim;
    }

    function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;
    }

    function getIdGrupo()
    {
        return $this->idGrupo;
    }

    function setIdGrupo($idGrupo)
    {
        $this->idGrupo = $idGrupo;
    }

    function getIdProduto()
    {
        return $this->idProduto;
    }

    function setIdProduto($idProduto)
    {
        $this->idProduto = $idProduto;
    }

    function getIdLocalizacao()
    {
        return $this->idLocalizacao;
    }

    function setIdLocalizacao($idLocalizacao)
    {
        $this->idLocalizacao = $idLocalizacao;
    }

    function getTipoMovimento()
    {
        return $this->tipoMovimento;
    }

    function setTipoMovimento($tipoMovimento)
    {
        $this->tipoMovimento = $tipoMovimento;
    }

    function getDescProduto()
    {
        return $this->descProduto;
    }

    function setDescProduto($descProduto)
    {
        $this->descProduto = $descProduto;
    }

    function getDescCliente()
    {
        return $this->descCliente;
    }

    function setDescCliente($descCliente)
    {
        $this->descCliente = $descCliente;
    }



    function getTipoRelatorio()
    {
        return $this->tipoRelatorio;
    }

    function setTipoRelatorio($tipoRelatorio)
    {
        $this->tipoRelatorio = $tipoRelatorio;
    }

    function getSituacaoNota()
    {
        return $this->situacaoNota;
    }

    function setSituacaoNota($situacaoNota)
    {
        $this->situacaoNota = $situacaoNota;
    }

    function getCurvaAbc()
    {
        return $this->curvaAbc;
    }

    function setCurvaAbc($curvaAbc)
    {
        $this->curvaAbc = $curvaAbc;
    }

    function getOrdenacao()
    {
        return $this->ordenacao;
    }

    function setOrdenacao($ordenacao)
    {
        $this->ordenacao = $ordenacao;
    }

    function getIdCliente()
    {
        return $this->idCliente;
    }

    function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }    

    function getCentroCusto()
    {
        return $this->idCentroCusto;
    }

    function setCentroCusto($centroCusto)
    {
        $this->idCentroCusto = $centroCusto;
    }

    //fim dos gets e sets

    /**
     * Função para gerar relatório de movimentação de estoque baseados nos filtros 
     * [DataInicio, DataFim, Cod Produto, CentroCusto, Grupo de Produtos, Localizacao, Tipo de Movimento]
     * @return array
     */
    public function selectRelatorioMovimentacaoEstoque()
    {
        $sql = "SELECT 
                    NF.ID as ID,
                    NF.EMISSAO as DATA,
                    NF.TIPO as TIPO,
                    NFP.QUANT as QUANTIDADE,
                    NFP.UNITARIO as VALOR_UNITARIO,
                    NFP.TOTAL as VALOR_TOTAL,
                    CONCAT(NF.NUMERO, ' ', NF.SERIE) as DOCUMENTO,
                    NF.OBS as OBSERVACAO,
                    P.CODIGO as CODIGO_PRODUTO,
                    CONCAT(P.CODIGO, ' - ', P.DESCRICAO) as PRODUTO,
                    G.DESCRICAO as GRUPO,
                    P.LOCALIZACAO as LOCALIZACAO,
                    COALESCE(U.NOMEREDUZIDO, 'Sistema') as USUARIO
                FROM EST_NOTA_FISCAL NF
                INNER JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NFP.IDNF = NF.ID)
                LEFT JOIN EST_PRODUTO P ON (P.CODIGO = NFP.CODPRODUTO)
                LEFT JOIN EST_GRUPO G ON (G.GRUPO = P.GRUPO)
                LEFT JOIN AMB_USUARIO U ON (U.USUARIO = NF.USERINSERT)";
        
        // Filtro por período
        $dataIni = c_date::convertDateTxt($this->getDataIni());
        $dataFim = c_date::convertDateTxt($this->getDataFim());
        $sql .= " WHERE NF.EMISSAO >= '$dataIni' AND NF.EMISSAO <= '$dataFim'";

        // Filtro por grupo
        if (!empty($this->getIdGrupo())) {
            $sql .= " AND P.GRUPO = '" . $this->getIdGrupo() . "'";
        }

        // Filtro por produtos
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto()); // Remove valores vazios
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sql .= " AND NFP.CODPRODUTO IN ($produtos_str)";
                }
            } else {
                $sql .= " AND NFP.CODPRODUTO = '" . $this->getIdProduto() . "'";
            }
        }

        // Filtro por localização
        if (!empty($this->getIdLocalizacao())) {
            $sql .= " AND P.LOCALIZACAO LIKE '%" . $this->getIdLocalizacao() . "%'";
        }

        // Filtro por tipo de movimento
        if (!empty($this->getTipoMovimento())) {
            $sql .= " AND NF.TIPO = '" . $this->getTipoMovimento() . "'";
        }
        
        // Filtro por centro de custo
        if (!empty($this->getCentroCusto())) {
            $sql .= " AND NF.CENTROCUSTO = '" . $this->getCentroCusto() . "'";
        }
        
        $sql .= " ORDER BY NF.EMISSAO DESC, P.DESCRICAO";
        
        // Conexão e execução do banco de dados.
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }


    /**
     * Consulta ao Banco para gerar o relatorio de curva ABC
     * baseados nos filtros [DataInicio, DataFim, Cod Produto, CentroCusto, Grupo de Produtos, 
     * tipoCurvaABC]
     *
     * Esta função utiliza setters e getters para obter os parâmetros,
     * seguindo o padrão da função `selectCurvaAbc`.
     *
     * @name select_relatorio_curva_abc_by_getter
     * @return ARRAY os campos [CENTROCUSTO, ITEMESTOQUE, GRUPO, PRODUTO, CODFABRICACAO, QUANTMIN, 
     * VENDA, EMISSAO, SUM(QTSOLICITADA), SUM(I.TOTAL) COUNT(I.ID)]
     */
    public function selectCurvaAbc(){
        // Query base para Curva ABC
        $sql = "SELECT 
                    P.CCUSTO, 
                    I.ITEMESTOQUE, 
                    G.DESCRICAO AS GRUPO, 
                    PRO.DESCRICAO, 
                    PRO.CODFABRICANTE, 
                    PRO.QUANTMINIMA, 
                    PRO.VENDA, 
                    SUM(I.QTSOLICITADA) AS QUANT, 
                    SUM(I.TOTAL) AS VALOR, 
                    COUNT(I.ID) AS NUMVENDAS 
                FROM FAT_PEDIDO P 
                INNER JOIN FAT_PEDIDO_ITEM I ON (P.ID = I.ID) 
                INNER JOIN EST_PRODUTO PRO ON (PRO.CODIGO = I.ITEMESTOQUE) 
                INNER JOIN EST_GRUPO G ON (G.GRUPO = PRO.GRUPO) 
                WHERE P.SITUACAO = '9' ";

        if (!empty($this->getDataIni())) {
            $dataIni = c_date::convertDateTxt($this->getDataIni());
            $sql .= "AND P.EMISSAO >= '$dataIni' ";
        }
        
        if (!empty($this->getDataFim())) {
            $dataFim = c_date::convertDateTxt($this->getDataFim());
            $sql .= "AND P.EMISSAO <= '$dataFim' ";
        }

        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = $this->getIdProduto();
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sql .= " AND PRO.CODIGO IN ($produtos_str)";
                }
            } else {
                $sql .= " AND PRO.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }

        if (!empty($this->getCentroCusto())) {
            $sql .= "AND P.CCUSTO = '" . $this->getCentroCusto() . "' ";
        }

        if (!empty($this->getIdGrupo())) {
            $sql .= "AND PRO.GRUPO = '" . $this->getIdGrupo() . "' ";
        }
        
        // Define campo de ordenação baseado no ID selecionado
        $ordenacao = $this->getCurvaAbc();
        switch ($ordenacao) {
            case '1':
                $campoOrdenacao = "SUM(I.TOTAL)"; // Por Valor Total
                break;
            case '2':
                $campoOrdenacao = "SUM(I.QTSOLICITADA)"; // Por Quantidade
                break;
            case '3':
                $campoOrdenacao = "COUNT(I.ID)"; // Por Frequência
                break;
            case '4':
                $campoOrdenacao = "PRO.VENDA"; // Por Preço Unitário
                break;
            default:
                $campoOrdenacao = "SUM(I.TOTAL)"; // Padrão: Por Valor Total
                break;
        }
        
        $sql .= "GROUP BY I.ITEMESTOQUE, P.CCUSTO, G.DESCRICAO, PRO.DESCRICAO, PRO.CODFABRICANTE, PRO.QUANTMINIMA, PRO.VENDA ";
        $sql .= "ORDER BY " . $campoOrdenacao . " DESC";
        
        // Conexão e execução do banco de dados.
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        
        // Processa os dados para gerar a Curva ABC
        if (!empty($result)) {
            $total_valor = 0;
            $total_quant = 0;
            $total_freq = 0;
            
            // Calcula totais
            foreach ($result as $row) {
                $total_valor += $row['VALOR'];
                $total_quant += $row['QUANT'];
                $total_freq += $row['NUMVENDAS'];
            }
            
            // Define qual total usar baseado na ordenação
            switch ($ordenacao) {
                case '1':
                    $total_criterio = $total_valor;
                    break;
                case '2':
                    $total_criterio = $total_quant;
                    break;
                case '3':
                    $total_criterio = $total_freq;
                    break;
                case '4':
                    // Para preço unitário, calcula o valor total (preço * quantidade)
                    $total_criterio = 0;
                    foreach ($result as $row) {
                        $total_criterio += ($row['VENDA'] * $row['QUANT']);
                    }
                    break;
                default:
                    $total_criterio = $total_valor;
                    break;
            }
            
            // Processa cada linha para adicionar classificação ABC
            $acumulado = 0;
            $count = 1;
            
            foreach ($result as &$row) {
                // Define o valor do critério baseado na ordenação
                switch ($ordenacao) {
                    case '1':
                        $valor_criterio = $row['VALOR'];
                        break;
                    case '2':
                        $valor_criterio = $row['QUANT'];
                        break;
                    case '3':
                        $valor_criterio = $row['NUMVENDAS'];
                        break;
                    case '4':
                        $valor_criterio = ($row['VENDA'] * $row['QUANT']); // Valor total do produto
                        break;
                    default:
                        $valor_criterio = $row['VALOR'];
                        break;
                }
                
                // Calcula percentual de participação
                $participacao = ($total_criterio > 0) ? ($valor_criterio / $total_criterio) * 100 : 0;
                $acumulado += $participacao;
                
                // Classifica como A, B ou C
                if ($acumulado <= 80) {
                    $classificacao = 'A';
                } elseif ($acumulado <= 95) {
                    $classificacao = 'B';
                } else {
                    $classificacao = 'C';
                }
                
                // Adiciona campos calculados
                $row['COUNT'] = $count;
                $row['PARTICIPACAO'] = $participacao;
                $row['ACUMULADO'] = $acumulado;
                $row['CLASSIFICACAO'] = $classificacao;
                
                $count++;
            }
        }
        
        return $result;
    }

    /**
     * Função responsável pelas combos
     */
    public function comboRelatorioEstoque()
    {
        $this->comboGrupos();
        $this->comboLocalizacoes();
        $this->comboTiposMovimento();
        $this->comboCentrosCusto();
    }

    /**
     * Combo grupos de produtos
     */
    public function comboGrupos()
    {
        $consulta = new c_banco();
        $sql = "SELECT GRUPO as id, DESCRICAO as descricao FROM EST_GRUPO ORDER BY DESCRICAO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        
        $grupo_ids[0] = '';
        $grupo_names[0] = 'Selecione um Grupo';
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + 1] = $result[$i]['ID'];
            $grupo_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);
        $this->smarty->assign('grupo_id', $this->getIdGrupo());
    }


    /**
     * Função para buscar produtos e retornar JSON
     * @return array
     */
    public function buscarProdutosJson()
    {
        $consulta = new c_banco();
        $termo = $this->getDescProduto();
        
        $sql = "SELECT CODIGO as id, CONCAT(CODIGO, ' - ', DESCRICAO) as descricao 
                FROM EST_PRODUTO 
                WHERE DESCRICAO LIKE '%$termo%' OR CODIGO LIKE '%$termo%'
                ORDER BY DESCRICAO 
                LIMIT 20";
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        
        return $consulta->resultado;
    }

    /**
     * Função para buscar clientes e retornar JSON
     * @return array
     */
    public function buscarClientesJson()
    {
        $consulta = new c_banco();
        $termo = trim($this->getDescCliente()); // Remove espaços do termo
        
        $sql = "SELECT CLIENTE as id, TRIM(NOMEREDUZIDO) as descricao 
                FROM FIN_CLIENTE 
                WHERE TRIM(NOMEREDUZIDO) LIKE '%$termo%'
                ORDER BY TRIM(NOMEREDUZIDO) ASC 
                LIMIT 10";
        
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        
        return $consulta->resultado;
    }


    /**
     * Combo localizações
     */
    public function comboLocalizacoes()
    {
        $consulta = new c_banco();
        $sql = "SELECT DISTINCT LOCALIZACAO as id, LOCALIZACAO as descricao FROM EST_PRODUTO_ESTOQUE WHERE LOCALIZACAO IS NOT NULL AND LOCALIZACAO != '' ORDER BY LOCALIZACAO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        
        $localizacao_ids[0] = '';
        $localizacao_names[0] = 'Selecione uma Localização';
        for ($i = 0; $i < count($result); $i++) {
            $localizacao_ids[$i + 1] = $result[$i]['ID'];
            $localizacao_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('localizacao_ids', $localizacao_ids);
        $this->smarty->assign('localizacao_names', $localizacao_names);
        $this->smarty->assign('localizacao_id', $this->getIdLocalizacao());
    }

    /**
     * Combo tipos de movimento
     */
    public function comboTiposMovimento()
    {
        // Consultar valores reais da tabela EST_NOTA_FISCAL
        $consulta = new c_banco();
        $sql = "SELECT DISTINCT TIPO as id, 
                       CASE 
                           WHEN TIPO = 0 THEN 'Entrada'
                           WHEN TIPO = 1 THEN 'Saída'
                           ELSE CONCAT('Tipo ', TIPO)
                       END as descricao
                FROM EST_NOTA_FISCAL 
                WHERE TIPO IS NOT NULL 
                ORDER BY TIPO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        
        $tipo_ids = array('');
        $tipo_names = array('Todos');
        
        for ($i = 0; $i < count($result); $i++) {
            $tipo_ids[] = $result[$i]['ID'];
            $tipo_names[] = $result[$i]['DESCRICAO'];
        }
        
        $this->smarty->assign('tipo_ids', $tipo_ids);
        $this->smarty->assign('tipo_names', $tipo_names);
        $this->smarty->assign('tipo_id', $this->getTipoMovimento());
    }

    /**
     * Combo centros de custo
     */
    public function comboCentrosCusto()
    {
        $consulta = new c_banco();
        $sql = "SELECT CENTROCUSTO as id, DESCRICAO as descricao FROM FIN_CENTRO_CUSTO ORDER BY DESCRICAO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        
        $centro_custo_ids = array('');
        $centro_custo_names = array('Todos');
        
        for ($i = 0; $i < count($result); $i++) {
            $centro_custo_ids[] = $result[$i]['ID'];
            $centro_custo_names[] = $result[$i]['DESCRICAO'];
        }
        
        $this->smarty->assign('centro_custo_ids', $centro_custo_ids);
        $this->smarty->assign('centro_custo_names', $centro_custo_names);
    }

    /**
     * Função para gerar relatório Kardex Sintético
     * Filtros aceitos:
     * - dataIni, dataFim, idProduto, idGrupo, idCentroCusto
     * @return array
     */
    public function selectKardexSintetico()
    {
        $sql = "SELECT 
                    P.CODIGO,
                    P.DESCRICAO,
                    G.DESCRICAO AS GRUPO,
                    CC.DESCRICAO AS CENTROCUSTO,
                    COALESCE(C.NOMEREDUZIDO, 'Cliente não identificado') AS CLIENTE,
                    COALESCE(SUM(CASE WHEN NF.TIPO = 0 THEN NFP.QUANT ELSE 0 END),0) AS ENTRADA,
                    COALESCE(SUM(CASE WHEN NF.TIPO = 1 THEN NFP.QUANT ELSE 0 END),0) AS SAIDA,
                    COALESCE(SUM(CASE WHEN NF.TIPO = 0 THEN NFP.QUANT ELSE 0 END),0) - COALESCE(SUM(CASE WHEN NF.TIPO = 1 THEN NFP.QUANT ELSE 0 END),0) AS SALDO
                FROM EST_PRODUTO P
                LEFT JOIN EST_GRUPO G ON (P.GRUPO = G.GRUPO)
                LEFT JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (P.CODIGO = NFP.CODPRODUTO)
                LEFT JOIN EST_NOTA_FISCAL NF ON (NFP.IDNF = NF.ID)
                LEFT JOIN FIN_CENTRO_CUSTO CC ON (NF.CENTROCUSTO = CC.CENTROCUSTO)
                LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = NF.PESSOA)";

        // Filtro por período
        $dataIni = c_date::convertDateTxt($this->getDataIni());
        $dataFim = c_date::convertDateTxt($this->getDataFim());
        $sql .= " WHERE NF.EMISSAO >= '$dataIni' AND NF.EMISSAO <= '$dataFim'";

        // Filtro por produto
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sql .= " AND P.CODIGO IN ($produtos_str)";
                }
            } else {
                $sql .= " AND P.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }

        // Filtro por grupo
        if (!empty($this->getIdGrupo())) {
            $sql .= " AND P.GRUPO = '" . $this->getIdGrupo() . "'";
        }

        // Filtro por centro de custo
        if (!empty($this->getCentroCusto())) {
            $sql .= " AND NF.CENTROCUSTO = '" . $this->getCentroCusto() . "'";
        }

        $sql .= " GROUP BY P.CODIGO, P.DESCRICAO, G.DESCRICAO, CC.DESCRICAO, C.NOMEREDUZIDO";
        $sql .= " ORDER BY P.DESCRICAO";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Função para gerar relatório Kardex Analítico (igual ao antigo)
     * Filtros aceitos:
     * - dataIni, dataFim, idProduto, idGrupo, idCentroCusto, descCliente, numeroDocumento, tipoMovimento, situacaoNota
     * @return array
     */
    public function selectKardexAnalitico()
    {
        // NOTA FISCAL (ENTRADA/SAÍDA)
        $sqlNF = "SELECT 
            IF(NF.TIPO = 0, 'ENTRADA', 'SAIDA') as TIPO,
            'NF' AS DOC,
            NF.ID,
            CONCAT('NF-', NF.NUMERO) AS NUMERO,
            NF.EMISSAO AS DATAEMISSAO,
            CC.DESCRICAO AS CENTROCUSTO,
            P.CODIGO,
            P.DESCRICAO,
            COALESCE(C.NOMEREDUZIDO, 'Cliente não identificado') AS CLIENTE,
            NFP.QUANT AS QUANTIDADE
        FROM EST_PRODUTO P
        LEFT JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (P.CODIGO = NFP.CODPRODUTO)
        LEFT JOIN EST_NOTA_FISCAL NF ON (NFP.IDNF = NF.ID)
        LEFT JOIN FIN_CENTRO_CUSTO CC ON (NF.CENTROCUSTO = CC.CENTROCUSTO)
        LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = NF.PESSOA)";

        // Filtros para NOTA FISCAL
        $dataIni = c_date::convertDateTxt($this->getDataIni());
        $dataFim = c_date::convertDateTxt($this->getDataFim());
        $sqlNF .= " WHERE NF.EMISSAO >= '$dataIni' AND NF.EMISSAO <= '$dataFim'";

        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sqlNF .= " AND P.CODIGO IN ($produtos_str)";
                }
            } else {
                $sqlNF .= " AND P.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }

        if (!empty($this->getIdGrupo())) {
            $sqlNF .= " AND P.GRUPO = '" . $this->getIdGrupo() . "'";
        }

        if (!empty($this->getCentroCusto())) {
            $sqlNF .= " AND NF.CENTROCUSTO = '" . $this->getCentroCusto() . "'";
        }

        if (!empty($this->getDescCliente())) {
            $termo = trim($this->getDescCliente());
            $sqlNF .= " AND EXISTS (SELECT 1 FROM FIN_CLIENTE C WHERE C.CLIENTE = NF.PESSOA AND TRIM(C.NOMEREDUZIDO) LIKE '%$termo%')";
        }

        if (!empty($this->getSituacaoNota())) {
            $sqlNF .= " AND NF.SITUACAO = '" . $this->getSituacaoNota() . "'";
        }

        if (!empty($this->getTipoMovimento())) {
            $sqlNF .= " AND NF.TIPO = '" . $this->getTipoMovimento() . "'";
        }

        // PEDIDO (SAÍDA) - apenas quando NÃO houver NF correspondente
        $sqlPED = "SELECT 
            'SAIDA' as TIPO,
            'PED' AS DOC,
            P.ID,
            CONCAT('PED-', P.ID) AS NUMERO,
            P.EMISSAO AS DATAEMISSAO,
            CC.DESCRICAO AS CENTROCUSTO,
            PROD.CODIGO,
            PROD.DESCRICAO,
            COALESCE(C.NOMEREDUZIDO, 'Cliente não identificado') AS CLIENTE,
            PI.QTSOLICITADA AS QUANTIDADE
        FROM EST_PRODUTO PROD
        LEFT JOIN FAT_PEDIDO_ITEM PI ON (PROD.CODIGO = PI.ITEMESTOQUE)
        LEFT JOIN FAT_PEDIDO P ON (PI.ID = P.ID)
        LEFT JOIN FIN_CENTRO_CUSTO CC ON (P.CCUSTO = CC.CENTROCUSTO)
        LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = P.CLIENTE)";

        // Filtros para PEDIDO - apenas pedidos que NÃO geraram NF
        $sqlPED .= " WHERE P.SITUACAO <> '7' AND P.EMISSAO >= '$dataIni' AND P.EMISSAO <= '$dataFim'";
        $sqlPED .= " AND NOT EXISTS (SELECT 1 FROM EST_NOTA_FISCAL NF2 WHERE NF2.DOC = P.ID AND NF2.ORIGEM = 'PED')";

        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sqlPED .= " AND PROD.CODIGO IN ($produtos_str)";
                }
            } else {
                $sqlPED .= " AND PROD.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }

        if (!empty($this->getIdGrupo())) {
            $sqlPED .= " AND PROD.GRUPO = '" . $this->getIdGrupo() . "'";
        }

        if (!empty($this->getCentroCusto())) {
            $sqlPED .= " AND P.CCUSTO = '" . $this->getCentroCusto() . "'";
        }

        if (!empty($this->getDescCliente())) {
            $termo = trim($this->getDescCliente());
            $sqlPED .= " AND EXISTS (SELECT 1 FROM FIN_CLIENTE C WHERE C.CLIENTE = P.CLIENTE AND TRIM(C.NOMEREDUZIDO) LIKE '%$termo%')";
        }

        // UNION
        $sql = $sqlNF . " UNION " . $sqlPED . " ORDER BY DATAEMISSAO, TIPO";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Função para gerar relatório de Estoque Geral
     * Filtros aceitos: idProduto, idGrupo, idLocalizacao, ordenacao
     * @return array
     */
    public function selectEstoqueGeral()
    {
        $sql = "SELECT DISTINCT 
                    P.CODIGO,
                    P.DESCRICAO,
                    P.GRUPO,
                    P.LOCALIZACAO,
                    P.UNIDADE,
                    P.CODFABRICANTE,
                    P.CUSTOCOMPRA,
                    P.VENDA,
                    G.DESCRICAO AS NOMEGRUPO,
                    COALESCE(ESTOQUE.QUANTIDADE, 0) as ESTOQUE, 
                    COALESCE(RESERVA.QUANTIDADE, 0) as RESERVA,
                    GREATEST(COALESCE(ESTOQUE.QUANTIDADE, 0) - COALESCE(RESERVA.QUANTIDADE, 0), 0) as DISPONIVEL
                FROM EST_PRODUTO P 
                LEFT JOIN EST_GRUPO G ON (G.GRUPO = P.GRUPO)
                LEFT JOIN (
                    SELECT CODPRODUTO, COUNT(*) as QUANTIDADE 
                    FROM EST_PRODUTO_ESTOQUE 
                    WHERE STATUS = 0 
                    GROUP BY CODPRODUTO
                ) ESTOQUE ON (ESTOQUE.CODPRODUTO = P.CODIGO)
                LEFT JOIN (
                    SELECT I.ITEMESTOQUE as CODPRODUTO, SUM(I.QTSOLICITADA) as QUANTIDADE
                    FROM FAT_PEDIDO P
                    INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID = P.ID)
                    WHERE P.SITUACAO IN ('6','9') 
                    AND P.DATAENTREGA IS NULL
                    AND NOT EXISTS (
                        SELECT 1 FROM EST_NOTA_FISCAL NF 
                        WHERE NF.DOC = P.ID 
                        AND NF.ORIGEM = 'PED' 
                        AND NF.TIPO = '1'
                    )
                    GROUP BY I.ITEMESTOQUE
                ) RESERVA ON (RESERVA.CODPRODUTO = P.CODIGO)";

        // Filtro por produto específico
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sql .= " WHERE P.CODIGO IN ($produtos_str)";
                }
            } else {
                $sql .= " WHERE P.CODIGO = '" . $this->getIdProduto() . "'";
            }
        } else {
            // Filtro por grupo
            if (!empty($this->getIdGrupo())) {
                $sql .= " WHERE P.GRUPO = '" . $this->getIdGrupo() . "'";
            } else {
                // Filtro por localização
                if (!empty($this->getIdLocalizacao())) {
                    $sql .= " WHERE P.LOCALIZACAO LIKE '%" . $this->getIdLocalizacao() . "%'";
                }
            }
        }
        
        // Ordenação
        switch ($this->getOrdenacao()) {
            case 'grupo':
                $sql .= " ORDER BY P.GRUPO, P.DESCRICAO";
                break;
            case 'localizacao':
                $sql .= " ORDER BY P.LOCALIZACAO";
                break;
            default:
                $sql .= " ORDER BY P.DESCRICAO";
                break;
        }
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Função para gerar relatório de Estoque por Localização
     * Filtros aceitos: idProduto, idGrupo, idLocalizacao, ordenacao
     * @return array
     */
    public function selectEstoqueLocalizacao()
    {
        $sql = "SELECT DISTINCT 
                    P.CODIGO,
                    P.DESCRICAO,
                    P.GRUPO,
                    P.LOCALIZACAO,
                    P.CUSTOCOMPRA,
                    P.CUSTOMEDIO,
                    P.VENDA,
                    G.DESCRICAO AS NOMEGRUPO, 
                    COALESCE(ESTOQUE.QUANTIDADE, 0) as ESTOQUE, 
                    COALESCE(RESERVA.QUANTIDADE, 0) as RESERVA, 
                    LPAD(P.LOCALIZACAO, 5, '0') as TESTE 
                FROM EST_PRODUTO P 
                LEFT JOIN EST_GRUPO G ON (G.GRUPO = P.GRUPO)
                LEFT JOIN (
                    SELECT CODPRODUTO, COUNT(*) as QUANTIDADE 
                    FROM EST_PRODUTO_ESTOQUE 
                    WHERE STATUS = 0 
                    GROUP BY CODPRODUTO
                ) ESTOQUE ON (ESTOQUE.CODPRODUTO = P.CODIGO)
                LEFT JOIN (
                    SELECT I.ITEMESTOQUE as CODPRODUTO, SUM(I.QTSOLICITADA) as QUANTIDADE
                    FROM FAT_PEDIDO P
                    INNER JOIN FAT_PEDIDO_ITEM I ON (I.ID = P.ID)
                    WHERE P.SITUACAO IN ('6','9') 
                    AND P.DATAENTREGA IS NULL
                    AND NOT EXISTS (
                        SELECT 1 FROM EST_NOTA_FISCAL NF 
                        WHERE NF.DOC = P.ID 
                        AND NF.ORIGEM = 'PED' 
                        AND NF.TIPO = '1'
                    )
                    GROUP BY I.ITEMESTOQUE
                ) RESERVA ON (RESERVA.CODPRODUTO = P.CODIGO)";
        
        $where = [];
        
        // Filtro por produto específico
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    // Garantir que todos os IDs sejam strings
                    $produtos = array_map('strval', $produtos);
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $where[] = "P.CODIGO IN ($produtos_str)";
                }
            } else {
                $where[] = "P.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }
        
        // Filtro por grupo (independente do produto)
        if (!empty($this->getIdGrupo())) {
            $where[] = "P.GRUPO = '" . $this->getIdGrupo() . "'";
        }
        
        // Filtro por localização (suporte a range)
        if (!empty($this->getIdLocalizacao())) {
            $localizacoes = explode("|", $this->getIdLocalizacao());
            if (count($localizacoes) > 1) {
                $where[] = "P.LOCALIZACAO >= '" . $localizacoes[0] . "' AND P.LOCALIZACAO <= '" . $localizacoes[1] . "' AND P.LOCALIZACAO <> ''";
            } else {
                $where[] = "P.LOCALIZACAO = '" . $localizacoes[0] . "' AND P.LOCALIZACAO <> ''";
            }
        }
        
        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        // Ordenação especial para localização
        switch ($this->getOrdenacao()) {
            case 'grupo':
                $sql .= " ORDER BY P.GRUPO, P.DESCRICAO";
                break;
            case 'localizacao':
                $sql .= " ORDER BY SUBSTRING(P.LOCALIZACAO, 1, 1), CAST(SUBSTRING(P.LOCALIZACAO, 3) AS UNSIGNED), P.DESCRICAO";
                break;
            default:
                $sql .= " ORDER BY P.DESCRICAO";
                break;
        }
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Função para gerar relatório de Compras
     * Filtros aceitos: dataIni, dataFim, descCliente (fornecedor), idProduto, idGrupo
     * @return array
     */
    public function selectRelatorioCompras()
    {
        $sql = "SELECT 
                    NF.ID as ID,
                    NF.EMISSAO as DATA,
                    NF.TIPO as TIPO,
                    NFP.QUANT as QUANTIDADE,
                    NFP.UNITARIO as VALOR_UNITARIO,
                    NFP.TOTAL as VALOR_TOTAL,
                    CONCAT(NF.NUMERO, ' ', NF.SERIE) as DOCUMENTO,
                    NF.OBS as OBSERVACAO,
                    P.CODIGO as CODIGO_PRODUTO,
                    CONCAT(P.CODIGO, ' - ', P.DESCRICAO) as PRODUTO,
                    G.DESCRICAO as GRUPO,
                    P.LOCALIZACAO as LOCALIZACAO,
                    COALESCE(C.NOMEREDUZIDO, 'Fornecedor não identificado') as FORNECEDOR,
                    COALESCE(U.NOMEREDUZIDO, 'Sistema') as USUARIO
                FROM EST_NOTA_FISCAL NF
                INNER JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NFP.IDNF = NF.ID)
                LEFT JOIN EST_PRODUTO P ON (P.CODIGO = NFP.CODPRODUTO)
                LEFT JOIN EST_GRUPO G ON (G.GRUPO = P.GRUPO)
                LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = NF.PESSOA)
                LEFT JOIN AMB_USUARIO U ON (U.USUARIO = NF.USERINSERT)
                WHERE NF.TIPO = '0' AND NF.SERIE != 'INV' AND NF.SITUACAO = 'B'";

        // Filtro por período
        if (!empty($this->getDataIni())) {
            $dataIni = c_date::convertDateTxt($this->getDataIni());
            $sql .= " AND NF.EMISSAO >= '$dataIni'";
        }

        if (!empty($this->getDataFim())) {
            $dataFim = c_date::convertDateTxt($this->getDataFim());
            $sql .= " AND NF.EMISSAO <= '$dataFim'";
        }

        // Filtro por fornecedor (cliente)
        if (!empty($this->getIdCliente())) {
            $sql .= " AND NF.PESSOA = '" . $this->getIdCliente() . "'";
        }

        // Filtro por grupo
        if (!empty($this->getIdGrupo())) {
            $sql .= " AND P.GRUPO = '" . $this->getIdGrupo() . "'";
        }

        // Filtro por produto específico
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    // Garantir que todos os IDs sejam strings
                    $produtos = array_map('strval', $produtos);
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sql .= " AND NFP.CODPRODUTO IN ($produtos_str)";
                }
            } else {
                $sql .= " AND NFP.CODPRODUTO = '" . $this->getIdProduto() . "'";
            }
        }

        $sql .= " ORDER BY NF.EMISSAO DESC, P.DESCRICAO";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Função para gerar relatório de Sugestões de Compras
     * Filtros aceitos: dataIni, dataFim, idProduto, idGrupo
     * @return array
     */
    public function selectRelatorioComprasSugestoes()
    {
        $sql = "SELECT 
                    I.ITEMESTOQUE, 
                    P.DESCRICAO, 
                    G.DESCRICAO AS NOMEGRUPO, 
                    P.CODFABRICANTE, 
                    P.QUANTMINIMA, 
                    P.QUANTMAXIMA, 
                    P.VENDA, 
                    0 as ESTOQUE, 
                    0 as RESERVA,
                    SUM(I.QTSOLICITADA) AS QUANT,
                    SUM(I.TOTAL) AS VALOR,
                    COUNT(I.ID) AS NUMVENDAS
                FROM FAT_PEDIDO_ITEM I 
                JOIN EST_PRODUTO P ON P.CODIGO = I.ITEMESTOQUE 
                JOIN EST_GRUPO G ON P.GRUPO = G.GRUPO 
                JOIN FAT_PEDIDO PED ON (PED.ID = I.ID)";

        // Filtro por período
        if (!empty($this->getDataIni()) || !empty($this->getDataFim())) {
            $dataIni = c_date::convertDateTxt($this->getDataIni());
            $dataFim = c_date::convertDateTxt($this->getDataFim());
            $sql .= " WHERE PED.EMISSAO >= '$dataIni' AND PED.EMISSAO <= '$dataFim'";
        }
        
        // Filtro por produto específico
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sql .= " AND P.CODIGO IN ($produtos_str)";
                }
            } else {
                $sql .= " AND P.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }
        
        // Filtro por grupo
        if (!empty($this->getIdGrupo())) {
            $sql .= " AND P.GRUPO = '" . $this->getIdGrupo() . "'";
        }
        
        $sql .= " GROUP BY I.ITEMESTOQUE ORDER BY NUMVENDAS DESC";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Função para gerar relatório de Movimento de Estoque por Cliente
     * Filtros aceitos: dataIni, dataFim, idProduto, idGrupo, idCentroCusto, descCliente
     * @return array
     */
    public function selectMovimentoEstoqueCliente()
    {
        // Filtros de data
        $dataIni = c_date::convertDateTxt($this->getDataIni());
        $dataFim = c_date::convertDateTxt($this->getDataFim());
        
        // NOTA FISCAL
        $sqlNF = "SELECT 
            COALESCE(C.NOMEREDUZIDO, 'Cliente não identificado') as CLIENTE,
            P.CODIGO as CODIGO_PRODUTO,
            P.DESCRICAO as DESCRICAO_PRODUTO,
            NFP.QUANT as QUANTIDADE,
            NFP.TOTAL as VALOR_TOTAL,
            CONCAT(NF.NUMERO, '/', NF.SERIE) as NUMERO_DOCUMENTO,
            'NFE' as TIPO_DOCUMENTO,
            NF.EMISSAO as DATA,
            CASE 
                WHEN NF.TIPO = '0' THEN 'ENTRADA'
                WHEN NF.TIPO = '1' THEN 'SAIDA'
                ELSE 'OUTROS'
            END as TIPO_MOVIMENTO
        FROM EST_NOTA_FISCAL NF
        INNER JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NFP.IDNF = NF.ID)
        LEFT JOIN EST_PRODUTO P ON (P.CODIGO = NFP.CODPRODUTO)
        LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = NF.PESSOA)
        WHERE NF.SITUACAO = 'B' AND NF.SERIE != 'INV' AND NF.EMISSAO BETWEEN '$dataIni' AND '$dataFim'";

        // Filtro por produto para NF
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sqlNF .= " AND P.CODIGO IN ($produtos_str)";
                }
            } else {
                $sqlNF .= " AND P.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }

        // Filtro por grupo para NF
        if (!empty($this->getIdGrupo())) {
            $sqlNF .= " AND P.GRUPO = '" . $this->getIdGrupo() . "'";
        }

        // Filtro por cliente para NF
        if (!empty($this->getIdCliente())) {
            $sqlNF .= " AND C.CLIENTE = '" . $this->getIdCliente() . "'";
        }

        // Filtro por centro de custo para NF
        if (!empty($this->getCentroCusto())) {
            $sqlNF .= " AND NF.CENTROCUSTO = '" . $this->getCentroCusto() . "'";
        }

        // PEDIDOS
        $sqlPED = "SELECT 
            COALESCE(C.NOMEREDUZIDO, 'Cliente não identificado') as CLIENTE,
            P.CODIGO as CODIGO_PRODUTO,
            P.DESCRICAO as DESCRICAO_PRODUTO,
            PI.QTSOLICITADA as QUANTIDADE,
            PI.TOTAL as VALOR_TOTAL,
            CONCAT(PED.PEDIDO, '/', COALESCE(PED.SERIE, '')) as NUMERO_DOCUMENTO,
            'PEDIDO' as TIPO_DOCUMENTO,
            PED.EMISSAO as DATA,
            'SAIDA' as TIPO_MOVIMENTO
        FROM FAT_PEDIDO PED
        INNER JOIN FAT_PEDIDO_ITEM PI ON (PI.ID = PED.ID)
        LEFT JOIN EST_PRODUTO P ON (P.CODIGO = PI.ITEMESTOQUE)
        LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = PED.CLIENTE)
        WHERE PED.SITUACAO IN ('6','9') AND PED.EMISSAO BETWEEN '$dataIni' AND '$dataFim'
        AND NOT EXISTS (SELECT 1 FROM EST_NOTA_FISCAL NF2 WHERE NF2.DOC = PED.ID AND NF2.ORIGEM = 'PED')";

        // Filtro por produto para PED
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $sqlPED .= " AND P.CODIGO IN ($produtos_str)";
                }
            } else {
                $sqlPED .= " AND P.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }

        // Filtro por grupo para PED
        if (!empty($this->getIdGrupo())) {
            $sqlPED .= " AND P.GRUPO = '" . $this->getIdGrupo() . "'";
        }

        // Filtro por cliente para PED
        if (!empty($this->getIdCliente())) {
            $sqlPED .= " AND C.CLIENTE = '" . $this->getIdCliente() . "'";
        }

        // Filtro por centro de custo para PED
        if (!empty($this->getCentroCusto())) {
            $sqlPED .= " AND PED.CCUSTO = '" . $this->getCentroCusto() . "'";
        }

        // UNION dos dois SELECTs
        $sql = $sqlNF . " UNION ALL " . $sqlPED . " ORDER BY DATA DESC, CLIENTE, DESCRICAO_PRODUTO";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Função para consulta de preços de produtos
     * Filtros aceitos: dataIni, dataFim, idProduto, idCentroCusto
     * @return array
     */
    public function selectConsultaProdutoPreco()
    {
        $where = [];
        
        // Filtros de data
        if (!empty($this->getDataIni()) && !empty($this->getDataFim())) {
            $dataIni = c_date::convertDateTxt($this->getDataIni());
            $dataFim = c_date::convertDateTxt($this->getDataFim());
            $where[] = "DATA BETWEEN '$dataIni 00:00:00' AND '$dataFim 23:59:59'";
        } elseif (!empty($this->getDataIni())) {
            $dataIni = c_date::convertDateTxt($this->getDataIni());
            $where[] = "DATA >= '$dataIni 00:00:00'";
        } elseif (!empty($this->getDataFim())) {
            $dataFim = c_date::convertDateTxt($this->getDataFim());
            $where[] = "DATA <= '$dataFim 23:59:59'";
        }
        
        // Filtro por produto
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    // Garantir que todos os IDs sejam strings
                    $produtos = array_map('strval', $produtos);
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $where[] = "CODIGO_PRODUTO IN ($produtos_str)";
                }
            } else {
                $where[] = "CODIGO_PRODUTO = '" . $this->getIdProduto() . "'";
            }
        }
        
        // Filtro por cliente
        if (!empty($this->getIdCliente())) {
            $where[] = "ID_CLIENTE = '" . $this->getIdCliente() . "'";
        }
        
        // Filtro por centro de custo
        if (!empty($this->getCentroCusto())) {
            $where[] = "CENTRO_CUSTO = '" . $this->getCentroCusto() . "'";
        }
        
        $whereClause = '';
        if (count($where) > 0) {
            $whereClause = ' WHERE ' . implode(' AND ', $where);
        }
        
        $sql = "SELECT 
                    CODIGO_PRODUTO,
                    DESCRICAO_PRODUTO,
                    TIPO_DOCUMENTO,
                    NUMERO_DOCUMENTO,
                    CLIENTE,
                    QUANTIDADE,
                    VALOR_UNITARIO,
                    VALOR_TOTAL,
                    DESCONTO_PERCENTUAL,
                    VALOR_LIQUIDO,
                    ST,
                    OS,
                    DATA
                FROM (
                    -- NOTA FISCAL SAÍDA
                    SELECT 
                        NFP.CODPRODUTO as CODIGO_PRODUTO,
                        NFP.DESCRICAO as DESCRICAO_PRODUTO,
                        'NF SAIDA' as TIPO_DOCUMENTO,
                        CONCAT(NF.NUMERO, '/', NF.SERIE) as NUMERO_DOCUMENTO,
                        COALESCE(C.NOMEREDUZIDO, 'Cliente não identificado') as CLIENTE,
                        NFP.QUANT as QUANTIDADE,
                        NFP.UNITARIO as VALOR_UNITARIO,
                        NFP.TOTAL as VALOR_TOTAL,
                        COALESCE(PI.PERCDESCONTO, 0) as DESCONTO_PERCENTUAL,
                        (NFP.UNITARIO - ((NFP.UNITARIO * COALESCE(PI.PERCDESCONTO, 0))/100) + COALESCE(NFP.VALORICMSST, 0)) as VALOR_LIQUIDO,
                        COALESCE(NFP.VALORICMSST, 0) as ST,
                        COALESCE(PED.OS, '') as OS,
                        NF.EMISSAO as DATA,
                        C.CLIENTE as ID_CLIENTE,
                        NF.CENTROCUSTO as CENTRO_CUSTO
                    FROM EST_NOTA_FISCAL NF
                    INNER JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NF.ID = NFP.IDNF)
                    LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = NF.PESSOA)
                    LEFT JOIN FAT_PEDIDO PED ON (PED.ID = NF.DOC)
                    LEFT JOIN FAT_PEDIDO_ITEM PI ON (PED.ID = PI.ID AND PI.ITEMESTOQUE = NFP.CODPRODUTO)
                    WHERE NF.ORIGEM <> 'AJT' AND NF.TIPO = '1' AND NF.SITUACAO = 'B'
                    
                    UNION ALL
                    
                    -- NOTA FISCAL ENTRADA
                    SELECT 
                        NFP.CODPRODUTO as CODIGO_PRODUTO,
                        NFP.DESCRICAO as DESCRICAO_PRODUTO,
                        'NF ENTRADA' as TIPO_DOCUMENTO,
                        CONCAT(NF.NUMERO, '/', NF.SERIE) as NUMERO_DOCUMENTO,
                        COALESCE(C.NOMEREDUZIDO, 'Fornecedor não identificado') as CLIENTE,
                        NFP.QUANT as QUANTIDADE,
                        NFP.UNITARIO as VALOR_UNITARIO,
                        NFP.TOTAL as VALOR_TOTAL,
                        0 as DESCONTO_PERCENTUAL,
                        NFP.UNITARIO as VALOR_LIQUIDO,
                        COALESCE(NFP.VALORICMSST, 0) as ST,
                        '' as OS,
                        NF.EMISSAO as DATA,
                        C.CLIENTE as ID_CLIENTE,
                        NF.CENTROCUSTO as CENTRO_CUSTO
                    FROM EST_NOTA_FISCAL NF
                    INNER JOIN EST_NOTA_FISCAL_PRODUTO NFP ON (NF.ID = NFP.IDNF)
                    LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = NF.PESSOA)
                    WHERE NF.ORIGEM <> 'AJT' AND NF.TIPO = '0' AND NF.SITUACAO = 'B'
                    
                    UNION ALL
                    
                    -- PEDIDOS
                    SELECT 
                        PI.ITEMESTOQUE as CODIGO_PRODUTO,
                        PI.DESCRICAO as DESCRICAO_PRODUTO,
                        'PEDIDO' as TIPO_DOCUMENTO,
                        CONCAT(PED.PEDIDO, '/', COALESCE(PED.SERIE, '')) as NUMERO_DOCUMENTO,
                        COALESCE(C.NOMEREDUZIDO, 'Cliente não identificado') as CLIENTE,
                        PI.QTSOLICITADA as QUANTIDADE,
                        PI.UNITARIO as VALOR_UNITARIO,
                        PI.TOTAL as VALOR_TOTAL,
                        COALESCE(PI.PERCDESCONTO, 0) as DESCONTO_PERCENTUAL,
                        (PI.UNITARIO - ((PI.UNITARIO * COALESCE(PI.PERCDESCONTO, 0))/100)) as VALOR_LIQUIDO,
                        0 as ST,
                        COALESCE(PED.OS, '') as OS,
                        PED.EMISSAO as DATA,
                        C.CLIENTE as ID_CLIENTE,
                        PED.CCUSTO as CENTRO_CUSTO
                    FROM FAT_PEDIDO PED
                    INNER JOIN FAT_PEDIDO_ITEM PI ON (PED.ID = PI.ID)
                    LEFT JOIN FIN_CLIENTE C ON (C.CLIENTE = PED.CLIENTE)
                    WHERE PED.SITUACAO IN ('6','9')
                ) CONSULTA_PRECOS
                $whereClause
                ORDER BY DATA DESC, CLIENTE, DESCRICAO_PRODUTO
                LIMIT 1000";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * Função para gerar relatório de Tabela de Preços
     * Filtros aceitos: idProduto, idGrupo
     * @return array
     */
    public function selectTabelaPrecos()
    {
        $sql = "SELECT DISTINCT 
                    P.CODIGO,
                    P.DESCRICAO,
                    P.GRUPO,
                    P.CUSTOCOMPRA as PRECO_CUSTO,
                    P.VENDA as PRECO_VENDA,
                    G.DESCRICAO AS NOMEGRUPO
                FROM EST_PRODUTO P 
                LEFT JOIN EST_GRUPO G ON (G.GRUPO = P.GRUPO)";
        
        $where = [];
        
        // Filtro por produto específico
        if (!empty($this->getIdProduto())) {
            if (is_array($this->getIdProduto())) {
                $produtos = array_filter($this->getIdProduto());
                if (!empty($produtos)) {
                    // Garantir que todos os IDs sejam strings
                    $produtos = array_map('strval', $produtos);
                    $produtos_str = "'" . implode("','", $produtos) . "'";
                    $where[] = "P.CODIGO IN ($produtos_str)";
                }
            } else {
                $where[] = "P.CODIGO = '" . $this->getIdProduto() . "'";
            }
        }
        
        // Filtro por grupo (independente do produto)
        if (!empty($this->getIdGrupo())) {
            $where[] = "P.GRUPO = '" . $this->getIdGrupo() . "'";
        }
        
        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        $sql .= " ORDER BY P.DESCRICAO";
        
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

} 