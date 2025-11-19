<?php

/**
 * @package   admv4.5
 * @name      c_rel_financeiro
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      10/10/2025
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

class c_rel_financeiro extends c_user
{
    // ============================================
    // PROPRIEDADES
    // ============================================
    
    private $referencia        = NULL; // Data de referência para ordenação
    private $dataIni           = NULL; // Data inicial
    private $dataFim           = NULL; // Data final
    private $tipoLancamento    = NULL; // Tipo de lançamento
    private $situacaoLancamento = NULL; // Situação do lançamento
    private $tipoDocumento     = NULL; // Tipo de documento
    private $idContaBanco      = NULL; // ID da conta bancária
    private $idCentroCusto     = NULL; // ID do centro de custo
    private $idGenero          = NULL; // ID do gênero
    private $pessoaBusca       = NULL; // Termo de busca para pessoa
    private $situacaoDocumento = NULL; // Situação do documento
    private $pessoa            = NULL; // Cliente/Fornecedor (pessoa)
    private $tipoRelatorio     = NULL; // Tipo do relatório

    // ============================================
    // MÉTODOS GETTERS E SETTERS
    // ============================================
    
    function getReferencia() { return $this->referencia; }
    function setReferencia($referencia) { $this->referencia = $referencia; }
    
    function getDataIni() { return $this->dataIni; }
    function setDataIni($dataIni) { $this->dataIni = $dataIni; }
    
    function getDataFim() { return $this->dataFim; }
    function setDataFim($dataFim) { $this->dataFim = $dataFim; }
    
    function getTipoLancamento() { return $this->tipoLancamento; }
    function setTipoLancamento($tipoLancamento) { $this->tipoLancamento = $tipoLancamento; }
    
    function getSituacaoLancamento() { return $this->situacaoLancamento; }
    function setSituacaoLancamento($situacaoLancamento) { $this->situacaoLancamento = $situacaoLancamento; }
    
    function getTipoDocumento() { return $this->tipoDocumento; }
    function setTipoDocumento($tipoDocumento) { $this->tipoDocumento = $tipoDocumento; }
    
    function getIdContaBanco() { return $this->idContaBanco; }
    function setIdContaBanco($idContaBanco) { $this->idContaBanco = $idContaBanco; }
    
    function getIdCentroCusto() { return $this->idCentroCusto; }
    function setIdCentroCusto($idCentroCusto) { $this->idCentroCusto = $idCentroCusto; }
    
    function getIdGenero() { return $this->idGenero; }
    function setIdGenero($idGenero) { $this->idGenero = $idGenero; }
    
    function getPessoaBusca() { return $this->pessoaBusca; }
    function setPessoaBusca($pessoaBusca) { $this->pessoaBusca = $pessoaBusca; }
    
    function getSituacaoDocumento() { return $this->situacaoDocumento; }
    function setSituacaoDocumento($situacaoDocumento) { $this->situacaoDocumento = $situacaoDocumento; }
    
    function getPessoa() { return $this->pessoa; }
    function setPessoa($pessoa) { $this->pessoa = $pessoa; }
    
    function getTipoRelatorio() { return $this->tipoRelatorio; }
    function setTipoRelatorio($tipoRelatorio) { $this->tipoRelatorio = $tipoRelatorio; }

    // ============================================
    // MÉTODOS DE COMBO
    // ============================================
    
    /**
     * Carrega todos os combos necessários para os filtros
     */
    public function comboRelatorioFinanceiro()
    {
        $this->comboCentrosCusto();
        $this->comboTiposDocumento();
        $this->comboSituacoesDocumento();
        $this->comboSituacoesLancamento();
        $this->comboTiposLancamento();
        $this->comboContasBancarias();
        $this->comboDatasReferencia();
    }
    
    /**
     * Combo de Centros de Custo (filial)
     */
    public function comboCentrosCusto()
    {
        $banco = new c_banco();
        $sql = "SELECT CENTROCUSTO AS ID, DESCRICAO 
                FROM FIN_CENTRO_CUSTO 
                WHERE ATIVO = 'S' 
                ORDER BY DESCRICAO";
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        
        $centro_custo_ids[0] = '';
        $centro_custo_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $centro_custo_ids[$i + 1] = $result[$i]['ID'];
            $centro_custo_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('centro_custo_ids', $centro_custo_ids);
        $this->smarty->assign('centro_custo_names', $centro_custo_names);
    }
    
    /**
     * Combo de Tipos de Documento
     */
    public function comboTiposDocumento()
    {
        $banco = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO 
                FROM AMB_DDM 
                WHERE ALIAS = 'FIN_MENU' 
                AND CAMPO = 'TipoDoctoPgto' 
                AND (TIPO IN ('X', 'N', 'B', 'D', 'E', 'C', 'T', 'A', 'K', 'P'))
                ORDER BY PADRAO";
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        
        $tipo_documento_ids[0] = '';
        $tipo_documento_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $tipo_documento_ids[$i + 1] = $result[$i]['ID'];
            $tipo_documento_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipo_documento_ids', $tipo_documento_ids);
        $this->smarty->assign('tipo_documento_names', $tipo_documento_names);
    }
    
    /**
     * Combo de Situações de Documento
     */
    public function comboSituacoesDocumento()
    {
        $banco = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO 
                FROM AMB_DDM 
                WHERE ALIAS = 'FIN_MENU' 
                AND CAMPO = 'SituacaoDoctoPgto' 
                ORDER BY PADRAO";
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        
        $situacao_documento_ids[0] = '';
        $situacao_documento_names[0] = 'Todas';
        for ($i = 0; $i < count($result); $i++) {
            $situacao_documento_ids[$i + 1] = $result[$i]['ID'];
            $situacao_documento_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_documento_ids', $situacao_documento_ids);
        $this->smarty->assign('situacao_documento_names', $situacao_documento_names);
    }
    
    /**
     * Combo de Situações de Lançamento
     */
    public function comboSituacoesLancamento()
    {
        $banco = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO 
                FROM AMB_DDM 
                WHERE ALIAS = 'FIN_MENU' 
                AND CAMPO = 'SituacaoPgto' 
                ORDER BY PADRAO";
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        
        $situacao_lancamento_ids[0] = '';
        $situacao_lancamento_names[0] = 'Todas';
        for ($i = 0; $i < count($result); $i++) {
            $situacao_lancamento_ids[$i + 1] = $result[$i]['ID'];
            $situacao_lancamento_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_lancamento_ids', $situacao_lancamento_ids);
        $this->smarty->assign('situacao_lancamento_names', $situacao_lancamento_names);
    }
    
    /**
     * Combo de Tipos de Lançamento
     */
    public function comboTiposLancamento()
    {
        $banco = new c_banco();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO 
                FROM AMB_DDM 
                WHERE ALIAS = 'FIN_MENU' 
                AND CAMPO = 'TipoLanc' 
                ORDER BY PADRAO";
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        
        $tipo_lancamento_ids[0] = '';
        $tipo_lancamento_names[0] = 'Todos';
        for ($i = 0; $i < count($result); $i++) {
            $tipo_lancamento_ids[$i + 1] = $result[$i]['ID'];
            $tipo_lancamento_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipo_lancamento_ids', $tipo_lancamento_ids);
        $this->smarty->assign('tipo_lancamento_names', $tipo_lancamento_names);
    }
    
    /**
     * Combo de Contas Bancárias
     */
    public function comboContasBancarias()
    {
        $banco = new c_banco();
        $sql = "SELECT CONTA AS ID, NOMEINTERNO AS DESCRICAO, BANCO 
                FROM FIN_CONTA 
                WHERE STATUS = 'A' 
                ORDER BY NOMEINTERNO";
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        
        $conta_bancaria_ids[0] = '';
        $conta_bancaria_names[0] = 'Todas';
        for ($i = 0; $i < count($result); $i++) {
            $conta_bancaria_ids[$i + 1] = $result[$i]['ID'];
            $conta_bancaria_names[$i + 1] = $result[$i]['DESCRICAO'] . ' - ' . $result[$i]['BANCO'];
        }
        $this->smarty->assign('conta_bancaria_ids', $conta_bancaria_ids);
        $this->smarty->assign('conta_bancaria_names', $conta_bancaria_names);
    }
    
    /**
     * Combo de Datas de Referência
     */
    public function comboDatasReferencia()
    {
        $data_referencia_ids = array(1, 2, 3, 4, 5);
        $data_referencia_names = array('Data Vencimento', 'Data Lançamento', 'Data Pagamento', 'Data Emissão', 'Não considera');
        
        $this->smarty->assign('data_referencia_ids', $data_referencia_ids);
        $this->smarty->assign('data_referencia_names', $data_referencia_names);
    }
    
    /**
     * Busca clientes/fornecedores via AJAX
     * @return array
     */
    public function buscarClientesJson()
    {
        $banco = new c_banco();
        $termo = trim($this->getPessoaBusca());
        
        $sql = "SELECT CLIENTE AS ID, TRIM(NOMEREDUZIDO) AS DESCRICAO 
                FROM FIN_CLIENTE 
                WHERE TRIM(NOMEREDUZIDO) LIKE '%$termo%' 
                OR CLIENTE LIKE '%$termo%'
                ORDER BY TRIM(NOMEREDUZIDO) ASC 
                LIMIT 5";
        
        $banco->exec_sql($sql);
        $banco->close_connection();
        
        return $banco->resultado;
    }
    
    /**
     * Busca gêneros via AJAX
     * @return array
     */
    public function buscarGeneroJson()
    {
        $banco = new c_banco();
        $termo = trim($this->getPessoaBusca());
        
        $sql = "SELECT GENERO AS ID, DESCRICAO 
                FROM FIN_GENERO 
                WHERE DESCRICAO LIKE '%$termo%' 
                OR GENERO LIKE '%$termo%'
                ORDER BY DESCRICAO ASC 
                LIMIT 5";
        
        $banco->exec_sql($sql);
        $banco->close_connection();
        
        return $banco->resultado;
    }

    /**
     * relatorios financeiros(lancamentos por data, fluxo de caixa, consolidacao,
     * resumo genero, centro de custo analitico e sintetico)
     * SELECT ANALÍTICO BASE
     * Retorna todos os campos necessários para os relatórios analíticos
     * @return array
     */
    public function selectLancamentosData($rateioCC = false){
        $sql = "SELECT 
        A.ID,
        A.DOCTO,
        A.SERIE,
        A.PARCELA,
        A.TIPOLANCAMENTO,
        A.SITPGTO,
        A.TOTAL,
        A.VENCIMENTO,
        A.EMISSAO,
        A.PAGAMENTO,
        A.LANCAMENTO,
        A.GENERO,
        A.TIPODOCTO,
        A.CONTA,
        A.CENTROCUSTO,
        A.OBS,
        C.NOME,
        C.CNPJCPF,
        S.PADRAO AS SITUACAOPGTO,
        R.DESCRICAO AS FILIAL,
        T.PADRAO AS TIPOLANCAMENTO_DESC,
        G.DESCRICAO AS DESCGENERO,
        R.DESCRICAO AS DESCCENTROCUSTO,                
        (SELECT COUNT(F.DOCTO) FROM FIN_LANCAMENTO F WHERE F.DOCTO = A.DOCTO AND F.PESSOA = A.PESSOA) AS TOTALPARCELAS";
        
        // Campos extras quando for rateio
        if ($rateioCC){
            $dataIni = $this->getDataIni() ? c_date::convertDateTxt($this->getDataIni()) : '';
            $sql .= ",
            X.CENTROCUSTO AS CC,
            Y.SALDO AS SALDOCC,
            (A.TOTAL * (X.PERCENTUAL / 100)) AS TOTALRATEIO,
            X.PERCENTUAL";
        } else {
            $sql .= ",
            U.NOMEREDUZIDO AS NOMEREDUZIDO";
        }
        
        $sql .= " FROM FIN_LANCAMENTO A ";
        
        // JOINs extras quando for rateio
        if ($rateioCC){
            $sql .= "INNER JOIN FIN_LANCAMENTO_RATEIO X ON (A.ID = X.ID) AND (X.PERCENTUAL > 0) ";
            $sql .= "INNER JOIN FIN_CENTRO_CUSTO R ON X.CENTROCUSTO = R.CENTROCUSTO ";
            $sql .= "LEFT JOIN FIN_CENTRO_CUSTO_SALDO Y ON (Y.CENTROCUSTO = X.CENTROCUSTO) AND (Y.DATA = '$dataIni') ";
        } else {
            $sql .= "LEFT JOIN FIN_CENTRO_CUSTO R ON A.CENTROCUSTO = R.CENTROCUSTO ";
        }
        
        // JOINs comuns
        $sql .= "LEFT JOIN FIN_CLIENTE C ON C.CLIENTE = A.PESSOA ";
        $sql .= "LEFT JOIN FIN_GENERO G ON G.GENERO = A.GENERO ";
        $sql .= "LEFT JOIN AMB_DDM S ON ((S.ALIAS='FIN_MENU') AND (S.CAMPO='SITUACAOPGTO') AND (S.TIPO = A.SITPGTO)) ";
        $sql .= "LEFT JOIN AMB_DDM T ON ((T.ALIAS='FIN_MENU') AND (T.CAMPO='TIPOLANC') AND (T.TIPO = A.TIPOLANCAMENTO)) ";
        
        // JOIN de usuário apenas se não for rateio
        if (!$rateioCC){
            $sql .= "LEFT JOIN AMB_USUARIO U ON A.USERINSERT = U.USUARIO ";
        }

        $sql .= $this->whereParams($rateioCC);
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        return $result;
    }
    

    /**
     * Relatorio Financeiro DRE
     * SELECT DRE FINANCEIRO
     * Retorna dados agrupados por gênero para montar o DRE
     * @return array
     */
    public function selectDREFinanceiro(){
        $dataIni = $this->getDataIni() ? c_date::convertDateTxt($this->getDataIni()) : '';
        $dataFim = $this->getDataFim() ? c_date::convertDateTxt($this->getDataFim()) : '';
        
        $sql = "SELECT 
            G.GENERO, 
            G.DESCRICAO, 
            SUM(A.TOTAL) AS TOTAL 
        FROM FIN_LANCAMENTO A 
        INNER JOIN FIN_GENERO G ON G.GENERO = A.GENERO ";
        
        // WHERE PERIODO baseado na referência
        if(!empty($this->getReferencia())){
            if($this->getReferencia() == 1){
                $sql .= " WHERE A.VENCIMENTO BETWEEN '".$dataIni."' AND '".$dataFim."'";
            }else if($this->getReferencia() == 2){
                $sql .= " WHERE A.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."'";
            }else if($this->getReferencia() == 3){
                $sql .= " WHERE A.PAGAMENTO BETWEEN '".$dataIni."' AND '".$dataFim."'";
            }else if($this->getReferencia() == 4){
                $sql .= " WHERE A.LANCAMENTO BETWEEN '".$dataIni."' AND '".$dataFim."'";
            }else if($this->getReferencia() == 5){
                $sql .= " WHERE 1=1";
            }

            // gênero
            if(!empty($this->getIdGenero())){
                if(is_array($this->getIdGenero())){
                    $sql .= " AND A.GENERO IN ('".implode("','", $this->getIdGenero())."')";
                }else{
                    $sql .= " AND A.GENERO = '".$this->getIdGenero()."'";
                }
            }
            
            // Tipo de Lançamento
            if(!empty($this->getTipoLancamento())){
                if(is_array($this->getTipoLancamento())){
                    $sql .= " AND A.TIPOLANCAMENTO IN ('".implode("','", $this->getTipoLancamento())."')";
                }else{
                    $sql .= " AND A.TIPOLANCAMENTO = '".$this->getTipoLancamento()."'";
                }
            }
            
            // Situação do Lançamento
            if(!empty($this->getSituacaoLancamento())){
                if(is_array($this->getSituacaoLancamento())){
                    $sql .= " AND A.SITPGTO IN ('".implode("','", $this->getSituacaoLancamento())."')";
                }else{
                    $sql .= " AND A.SITPGTO = '".$this->getSituacaoLancamento()."'";
                }
            }
            
            // Centro de Custo
            if(!empty($this->getIdCentroCusto())){
                if(is_array($this->getIdCentroCusto())){
                    $sql .= " AND A.CENTROCUSTO IN (".implode(",", $this->getIdCentroCusto()).")";
                }else{
                    $sql .= " AND A.CENTROCUSTO = ".$this->getIdCentroCusto();
                }
            }
            
        }

        $sql .= " GROUP BY G.GENERO, G.DESCRICAO ";
        $sql .= " ORDER BY G.GENERO";
        
        $banco = new c_banco(); 
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        return $result;
    }

    /**
     * Relatorio Financeiro Lancamentos com Data de Entrega
     * SELECT LANÇAMENTOS COM DATA DE ENTREGA
     * Retorna lançamentos financeiros relacionados a pedidos com informações de entrega
     * Combina dados de FIN_LANCAMENTO com PED_PEDIDO
     * @return array
     */
    public function selectLancamentosDataEntrega(){
        $sql = "SELECT 
            A.ID,
            A.DOCTO,
            A.SERIE,
            A.TOTAL,
            A.EMISSAO,
            A.VENCIMENTO,
            A.PAGAMENTO,
            A.SITPGTO,
            A.GENERO,
            C.NOME,
            C.CNPJCPF,
            S.PADRAO AS SITUACAOPGTO,
            R.DESCRICAO AS FILIAL,
            G.DESCRICAO AS DESCGENERO,
            P.PRAZOENTREGA,
            P.DATAENTREGA,
            SP.PADRAO AS SITUACAOPED
        FROM FIN_LANCAMENTO A 
        LEFT JOIN FAT_PEDIDO P ON (P.PEDIDO = A.DOCTO AND P.CLIENTE = A.PESSOA)
        LEFT JOIN FIN_CLIENTE C ON C.CLIENTE = A.PESSOA 
        LEFT JOIN FIN_GENERO G ON G.GENERO = A.GENERO 
        LEFT JOIN FIN_CENTRO_CUSTO R ON A.CENTROCUSTO = R.CENTROCUSTO 
        LEFT JOIN AMB_DDM S ON ((S.ALIAS='FIN_MENU') AND (S.CAMPO='SITUACAOPGTO') AND (S.TIPO = A.SITPGTO)) 
        LEFT JOIN AMB_DDM SP ON ((SP.ALIAS='PED_MENU') AND (SP.CAMPO='SITUACAOPED') AND (SP.TIPO = P.SITUACAO)) ";
        
        $sql .= $this->whereParams();
        
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        $result = $banco->resultado;
        return $result;
    }

    /**
     * Filtros do Parâmetro
     * @param bool $rateioCC
     * @return string
     * usa set e get para os parametros, com diferença se for vazio ou não, caso não seja vazio, usa o valor do set
     */
    public function whereParams($rateioCC = false){
        $sql = "";
        // WHERE PERIODO
        if(!empty($this->getReferencia())){
            $dataIni = c_date::convertDateTxt($this->getDataIni());
            $dataFim = c_date::convertDateTxt($this->getDataFim());
            
            if($this->getReferencia() == 1){
                $sql .= " WHERE A.VENCIMENTO BETWEEN '".$dataIni."' AND '".$dataFim."'";
            }else if($this->getReferencia() == 2){
                $sql .= " WHERE A.EMISSAO BETWEEN '".$dataIni."' AND '".$dataFim."'";
            }else if($this->getReferencia() == 3){
                $sql .= " WHERE A.PAGAMENTO BETWEEN '".$dataIni."' AND '".$dataFim."'";
            }else if($this->getReferencia() == 4){
                $sql .= " WHERE A.LANCAMENTO BETWEEN '".$dataIni."' AND '".$dataFim."'";
            }else if($this->getReferencia() == 5){
                $sql .= " WHERE 1=1";
            }

            // DEMAIS CONDIÇÕES
            
            // Tipo de Lançamento (pode ser único ou array)
            if(!empty($this->getTipoLancamento())){
                if(is_array($this->getTipoLancamento())){
                    $sql .= " AND A.TIPOLANCAMENTO IN ('".implode("','", $this->getTipoLancamento())."')";
                }else{
                    $sql .= " AND A.TIPOLANCAMENTO = '".$this->getTipoLancamento()."'";
                }
            }
            
            // Situação do Lançamento (pode ser único ou array)
            if(!empty($this->getSituacaoLancamento())){
                if(is_array($this->getSituacaoLancamento())){
                    $sql .= " AND A.SITPGTO IN ('".implode("','", $this->getSituacaoLancamento())."')";
                }else{
                    $sql .= " AND A.SITPGTO = '".$this->getSituacaoLancamento()."'";
                }
            }
            
            // Tipo de Documento (pode ser único ou array)
            if(!empty($this->getTipoDocumento())){
                if(is_array($this->getTipoDocumento())){
                    $sql .= " AND A.TIPODOCTO IN ('".implode("','", $this->getTipoDocumento())."')";
                }else{
                    $sql .= " AND A.TIPODOCTO = '".$this->getTipoDocumento()."'";
                }
            }
            
            // Situação do Documento (pode ser único ou array)
            if(!empty($this->getSituacaoDocumento())){
                if(is_array($this->getSituacaoDocumento())){
                    $sql .= " AND A.SITDOCTO IN ('".implode("','", $this->getSituacaoDocumento())."')";
                }else{
                    $sql .= " AND A.SITDOCTO = '".$this->getSituacaoDocumento()."'";
                }
            }
            
            // Centro de Custo (usa X.CENTROCUSTO se for rateio)
            if(!empty($this->getIdCentroCusto())){
                $campo = $rateioCC ? 'X.CENTROCUSTO' : 'A.CENTROCUSTO';
                if(is_array($this->getIdCentroCusto())){
                    $sql .= " AND $campo IN (".implode(",", $this->getIdCentroCusto()).")";
                }else{
                    $sql .= " AND $campo = ".$this->getIdCentroCusto();
                }
            }
            
            // Gênero
            if(!empty($this->getIdGenero())){
                $sql .= " AND A.GENERO = '".$this->getIdGenero()."'";
            }
            
            // Conta Bancária (pode ser único ou array)
            if(!empty($this->getIdContaBanco())){
                if(is_array($this->getIdContaBanco())){
                    $sql .= " AND A.CONTA IN (".implode(",", $this->getIdContaBanco()).")";
                }else{
                    $sql .= " AND A.CONTA = ".$this->getIdContaBanco();
                }
            }
            
            // Pessoa (Cliente/Fornecedor)
            if(!empty($this->getPessoa())){
                $sql .= " AND A.PESSOA = ".$this->getPessoa();
            }

            // ORDER BY
            if(!empty($this->getReferencia())){

                if($rateioCC) {
                    $sql .= " ORDER BY X.CENTROCUSTO ASC ";
                }else if($this->getReferencia() == 1){
                    $sql .= " ORDER BY A.VENCIMENTO ASC";
                }else if($this->getReferencia() == 2){
                    $sql .= " ORDER BY A.EMISSAO ASC";
                }else if($this->getReferencia() == 3){
                    $sql .= " ORDER BY A.PAGAMENTO ASC";
                }else if($this->getReferencia() == 4){
                    $sql .= " ORDER BY A.EMISSAO ASC";                    
                }else if($this->getReferencia() == 5){
                    $sql .= " ORDER BY A.DOCTO ASC";
                }
            }
        }
        return $sql;
    }

    
}
?>
