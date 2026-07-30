<?php
/**
 * @package   astecv3
 * @name      c_crm_dashboard
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kenedy11@gmail.com.br>
 * @date      20/06/2022
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class C_DASHBOARD
Class c_crm_dashboard extends c_user {
    
// Campos tabela FAT_METAS_MENSAL

private $id       = NULL; // int(11)
private $vendedor = NULL; // int(11)
private $ano      = NULL; // int(4)
private $mes      = NULL; // int(2)
private $meta     = NULL; // decimal(11,2)
private $ccusto   = NULL; // int(11)

// Campos tabela FAT_META_MENSAL_VENDEDOR

private $v_id       = NULL; // int(11)                         	
private $v_metaid   = NULL; // int(11)                     
private $v_vendedor = NULL; // int(11)	                    
private $v_meta     = NULL; // decimal(11,2)

// Campos tabela TEMPLATE
private $idAcomp = NULL;
private $idCotacao = NULL;
/**
* METODOS DE SETS E GETS
*/
public function setCotOntem($cotOntem){
         $this->cotOntem = c_tools::LimpaCamposGeral($cotOntem);
}

public function getCotOntem(){
         return $this->cotOntem;
}

public function setCotHoje($cotHoje){
         $this->cotHoje = c_tools::LimpaCamposGeral($cotHoje);
}

public function getCotHoje(){
         return $this->cotHoje;
}

public function setConversao($conversao){
    $this->conversao = c_tools::LimpaCamposGeral($conversao);
}

public function getConversao(){
    return $this->conversao;
}

public function setPerdido($perdido){
    $this->perdido = c_tools::LimpaCamposGeral($perdido);
}

public function getPerdido(){
    return $this->perdido;
}

public function setPedMes($pedMes){
    $this->pedMes = c_tools::LimpaCamposGeral($pedMes);
}

public function getPedMes(){
    return $this->pedMes;
}

public function setPedMesValor($pedMesValor){
    $this->pedMes = c_tools::LimpaCamposGeral($pedMesValor);
}

public function getPedMesValor(){
    return $this->pedMesValor;
}

public function setCentroCusto($centroCusto){
    $this->centroCusto = c_tools::LimpaCamposGeral($centroCusto);
}

public function getCentroCusto(){
    return $this->centroCusto;
}

public function setBloqueado($bloqueado){
        if ($bloqueado == ''){
            $this->bloqueado = 'N';
        }else{
            $this->bloqueado = strtoupper($bloqueado);
        }
         
}

public function getBloqueado(){
         return $this->bloqueado;
}

public function getIdAcompanhamento(){
    return $this->idAcomp;
}

public function setIdAcompanhamento($idAcomp){
    $this->idAcomp = c_tools::LimpaCamposGeral($idAcomp);
}

public function getIdCotacao(){
    return $this->idCotacao;
}

public function setIdCotacao($idCotacao){
    $this->idCotacao = c_tools::LimpaCamposGeral($idCotacao);
}


public function setDataIni($dataIni) { $this->dataIni = $dataIni; }
public function getDataIni() { return $this->dataIni; }

public function setDataFim($dataFim) { $this->dataFim = $dataFim; }
public function getDataFim() { return $this->dataFim; }

public function setVendedorSelected($vendedor) { 
    $this->vendedor = $vendedor; 
}
public function getVendedorSelected() { return $this->vendedor; }


public function setCentroCustoSelected($centroCusto)
{
    if (is_array($centroCusto)) {
        $ids = array_values(array_unique(array_filter(array_map(
            static fn($v) => (int) trim((string) $v),
            $centroCusto
        ), static fn($v) => $v > 0)));
        $csv = implode(',', array_map('strval', $ids));
        $this->centroCusto = $csv !== '' ? $csv : '';
        return;
    }
    if ($centroCusto === null || $centroCusto === '') {
        $this->centroCusto = '';
        return;
    }
    $this->centroCusto = c_tools::LimpaCamposGeral($centroCusto);
}

public function getCentroCustoSelected()
{
    return $this->centroCusto ?? '';
}

/**
 * @param string|array|null $classe CSV ou POST de name="classe[]"
 */
public function setClasseSelected($classe)
{
    if (is_array($classe)) {
        $tok = [];
        foreach ($classe as $v) {
            $s = trim((string) $v);
            if ($s !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $s)) {
                $tok[] = $s;
            }
        }
        $tok = array_values(array_unique($tok));
        $this->classe = $tok !== [] ? implode(',', $tok) : '';
        return;
    }
    if ($classe === null || $classe === '') {
        $this->classe = '';
        return;
    }
    $tok = [];
    foreach (array_filter(array_map('trim', explode(',', (string) $classe))) as $frag) {
        if ($frag !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $frag)) {
            $tok[] = $frag;
        }
    }
    $this->classe = $tok !== [] ? implode(',', array_values(array_unique($tok))) : '';
}

public function getClasseSelected()
{
    return $this->classe ?? '';
}

/**
 * @param string|array|null $estado CSV ou POST de name="estado[]" (UF 2 letras)
 */
public function setEstadoSelected($estado)
{
    if (is_array($estado)) {
        $ufs = [];
        foreach ($estado as $v) {
            $s = strtoupper(preg_replace('/[^A-Za-z]/', '', (string) $v));
            if (strlen($s) === 2) {
                $ufs[] = $s;
            }
        }
        $ufs = array_values(array_unique($ufs));
        $this->estado = $ufs !== [] ? implode(',', $ufs) : '';
        return;
    }
    if ($estado === null || $estado === '') {
        $this->estado = '';
        return;
    }
    $ufs = [];
    foreach (array_filter(array_map('trim', explode(',', (string) $estado))) as $frag) {
        $u = strtoupper(preg_replace('/[^A-Za-z]/', '', $frag));
        if (strlen($u) === 2) {
            $ufs[] = $u;
        }
    }
    $this->estado = $ufs !== [] ? implode(',', array_values(array_unique($ufs))) : '';
}

public function getEstadoSelected()
{
    return $this->estado ?? '';
}

public function setCidade($cidade) { $this->cidade = $cidade; }
public function getCidade() { return $this->cidade; }

//############### FIM SETS E GETS ###############
/**
 * 
 * @name existeClasse
 */
public function buscaCotacaoPedidos($dataIni=null, $dataFim=null, $vendedor=null, $centroCusto=null, $ccLogado, $vertodoslancamentos=null){
    //Ajuste de datas - data inicial
    if(($dataIni == '') || ($dataIni == null)){
        $dataIni = date("Y-m-01");
    }else{
        if(strpos($dataIni, "/")){
            $dataIni = c_date::convertDateBdSh($dataIni, $this->m_banco);
        }
    }
    //Ajuste de datas - data final
    if(($dataFim == '') || ($dataFim == null)){
        $dataFim = date("Y-m-t");
    }else{
        if(strpos($dataFim, "/")){
            $dataFim = c_date::convertDateBdSh($dataFim, $this->m_banco);
        }
    }

    $dataAtual = date("Y-m-d");

    $sql = "SELECT DISTINCT ";
    //Busca COTACAO ONTEM
    $sql .= "(SELECT COUNT(CO.ID) FROM FAT_PEDIDO CO WHERE CO.EMISSAO = DATE_SUB('".$dataAtual."', INTERVAL 1 DAY) and CO.SITUACAO = 5 ";
            //Verifica se existe vendedor, se não existir não gera esse where e tras todos
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .= "and CO.USRFATURA IN ($vendedor) ";
            }
            //Verifica se existe centro de custo, se não traz o logado
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and CO.CCUSTO IN ($centroCusto)) AS COTACAO_ONTEM, ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and CO.CCUSTO IN ($ccLogado) ";
                }
                $sql .= ") AS COTACAO_ONTEM, ";
            }
    //Busca COTACAO HOJE
    $sql .= "(SELECT COUNT(CH.ID) FROM FAT_PEDIDO CH WHERE CH.EMISSAO = '".$dataAtual."' and CH.SITUACAO = 5 ";
            //Verifica se existe vendedor, se não existir não gera esse where e tras todos
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .="and CH.USRFATURA IN ($vendedor) ";
            }
            //Verifica se existe centro de custo, se não traz o logado
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and CH.CCUSTO IN ($centroCusto)) AS COTACAO_HOJE, ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and CH.CCUSTO IN ($ccLogado)";
                }
                $sql .= ") AS COTACAO_HOJE, ";
            }
    //Busca COTACAO PERIODO (cotações em aberto no intervalo dataIni/dataFim)
    $sql .= "(SELECT COUNT(CP.ID) FROM FAT_PEDIDO CP WHERE CP.SITUACAO = 5 
            and CP.EMISSAO >= '$dataIni' and CP.EMISSAO <= '$dataFim' ";
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .= "and CP.USRFATURA IN ($vendedor) ";
            }
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and CP.CCUSTO IN ($centroCusto)) AS COTACAO_PERIODO, ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and CP.CCUSTO IN ($ccLogado) ";
                }
                $sql .= ") AS COTACAO_PERIODO, ";
            }
    //Busca CONVERSAO
    $sql .= "(SELECT COUNT(CVH.ID) FROM FAT_PEDIDO CVH WHERE CVH.EMISSAO = '".$dataAtual."' and CVH.SITUACAO = 6 ";
            //Verifica se existe vendedor, se não existir não gera esse where e tras todos
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .= "and CVH.USRFATURA IN ($vendedor) "; 
            }
            //Verifica se existe centro de custo, se não traz o logado
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and CVH.CCUSTO IN ($centroCusto)) AS CONVERSAO, ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and CVH.CCUSTO IN ($ccLogado) ";
                }
                $sql .= ") AS CONVERSAO, ";
            }
    //Busca vendas PERDIDAS
    $sql .= "(SELECT COUNT(PER.ID) FROM FAT_PEDIDO PER WHERE PER.SITUACAO = 7 
            and PER.EMISSAO >= '$dataIni' and PER.EMISSAO <= '$dataFim' ";
            //Verifica se existe vendedor, se não existir não gera esse where e tras todos
            if(($vendedor !== '') and ($vendedor !== null)){
                $sql .="and PER.USRFATURA IN ($vendedor) ";
            }
            //Verifica se existe centro de custo, se não traz o logado
            if(($centroCusto !== '') and ($centroCusto !== null)){
                $sql .="and PER.CCUSTO IN ($centroCusto)) AS PERDIDOS ";
            }else{
                if($vertodoslancamentos !== true){
                    $sql .="and PER.CCUSTO IN ($ccLogado) ";
                }
                $sql .= ") AS PERDIDOS ";
                
            }
    $sql .= "FROM FAT_PEDIDO P ";

    //echo strtoupper($sql);
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim existeClasse


public function verifica_vendedor() {

	$sql = "SELECT USUARIO, NOME, TIPO FROM AMB_USUARIO  ";
	$sql .= "WHERE (USUARIO = ". $this->m_userid.")";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}



function comboSql($sql, $par, &$id, &$ids, &$names) {
    $result = [];
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    if ($consulta->resultado != null) {
        $result = $consulta->resultado;
    }
    for ($i = 0; $i < count($result); $i++) {
        $ids[$i] = $result[$i]['ID'];
        $names[$i] = $result[$i]['DESCRICAO'];
    }
    
    $param = explode(",", $par);
    $i=0;
    $id[$i] = "0";
    while ($param[$i] != '') {
        $id[$i] = $param[$i];
        $i++;
    }    
}
/**
 * Executa um SQL de combo (ID/DESCRICAO) e retorna options prontas.
 *
 * @return array{ids: array, names: array}
 */
protected function comboOptions($sql) {
    $ids = [];
    $names = [];
    $consulta = new c_banco();
    $consulta->exec_sql($sql);
    $consulta->close_connection();
    $result = $consulta->resultado ?: [];
    $i = 0;
    foreach ($result as $row) {
        $rid = $row['ID'] ?? $row['id'] ?? null;
        $rdesc = $row['DESCRICAO'] ?? $row['descricao'] ?? '';
        $ids[$i] = $rid;
        $names[$i] = $rdesc;
        $i++;
    }
    return ['ids' => $ids, 'names' => $names];
}

// ########## Combos do dashboard CRM ##########
public function comboAcaoAcompanhamentoOptions() {
    return $this->comboOptions("select atividade as id, descricao from fat_atividade_acomp");
}

public function comboVeiculoOptions() {
    return $this->comboOptions("select tipo as id, padrao as descricao from amb_ddm where (alias='CAT_MENU') and (campo='Veiculo')");
}

public function comboCentroCustoOptions() {
    return $this->comboOptions("select centrocusto as id, descricao from fin_centro_custo order by centrocusto");
}

public function comboVendedorOptions() {
    return $this->comboOptions("SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO");
}

public function comboClasseOptions() {
    return $this->comboOptions("SELECT CLASSE AS ID, DESCRICAO FROM FIN_CLASSE");
}

public function comboEstadoOptions() {
    return $this->comboOptions("select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado');");
}

 /**
 * Funcao para buscar os clientes do dash da classe C = leads
 * @name contaAll
 */
public function contasAcompanhamento()
{
    $sql = "SELECT CLIENTE, NOME, NOMEREDUZIDO, CIDADE, UF, FONEAREA, FONE, EMAIL ";
    $sql .= "FROM fin_cliente ";
    $sql .= "WHERE 1 = 1 ";
    if ($this->getVendedorSelected() != "") {
        $sql .= "AND REPRESENTANTE = '" . $this->getVendedorSelected() . "' ";
    }
    if ($this->getCentroCustoSelected() != "") {
        $sql .= "AND CENTROCUSTO IN (" . $this->getCentroCustoSelected() . ") ";
    }
    $csvClasse = trim((string) $this->getClasseSelected());
    if ($csvClasse !== '') {
        $partesCl = array_filter(array_map('trim', explode(',', $csvClasse)));
        $inCl = [];
        foreach ($partesCl as $p) {
            if ($p !== '') {
                $inCl[] = "'" . addslashes($p) . "'";
            }
        }
        if ($inCl !== []) {
            $sql .= 'AND CLASSE IN (' . implode(',', $inCl) . ') ';
        }
    }
    $csvUf = trim((string) $this->getEstadoSelected());
    if ($csvUf !== '') {
        $partesUf = array_filter(array_map('trim', explode(',', $csvUf)));
        $inUf = [];
        foreach ($partesUf as $u) {
            $u = strtoupper(preg_replace('/[^A-Za-z]/', '', $u));
            if (strlen($u) === 2) {
                $inUf[] = "'" . addslashes($u) . "'";
            }
        }
        if ($inUf !== []) {
            $sql .= 'AND UF IN (' . implode(',', $inUf) . ') ';
        }
    }
    if ($this->getCidade() != "") {
        $sql .= "AND CIDADE like '%" . $this->getCidade() . "%' ";
    }
    $sql .= "ORDER BY UF, CIDADE, NOME;";
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $banco->close_connection();
    return $banco->resultado;
}

}	//	END OF THE CLASS
?>
