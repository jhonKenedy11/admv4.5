<?php
/**
 * @package   admv4.5
 * @name      c_paramentro
 * @version   4.5
 * @copyright 2023
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      20/02/2023
 */

$dir = dirname(__FILE__);
require_once($dir . '/../../bib/c_database_pdo.php');

Class c_parametros extends c_user {

private $filial         = NULL; //"int(11)"
private $grupoServico   = NULL; //"varchar(15)"
private $fluxoPedido    = NULL; //"char(1)"
private $sitEmitirNf    = NULL; //"smallint(6)"
private $sitBaixado     = NULL; //"smallint(6)"
private $sitAberto      = NULL; //"smallint(6)"
private $valorPedMinimo = NULL; //"decimal(11,2)"
private $aprovacao      = NULL; //"char(1)"
private $descontoMaximo = NULL; //"decimal(11,2)"
private $lancPedBaixado = NULL; //"char(1)" 
private $tipoDesconto   = NULL; //"char(1)"
private $encomenda      = NULL; //"char(1)"
private $faturaPedido   = NULL; //"char(1)" S/N — PS cotação→pedido abre financeiro gerente novo
private $casasDecimais  = NULL; //"int"
private $controleVendedor = NULL; //"int(1)"
private $tipoComissao   = NULL; //"int" 1=Faturamento; 2=Recebimento

//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    //session_start();
    c_user::from_array($_SESSION['user_array']);
}

###### INICIO SET's e GET's ######
function setFilial($filial){$this->filial = $filial;}
function getFilial(){return $this->filial;}

function setGrupoServico($grupoServico){$this->grupoServico = $grupoServico;}
function getGrupoServico(){return $this->grupoServico;}

function setFluxoPedido($fluxoPedido){$this->fluxoPedido = $fluxoPedido;}
function getFluxoPedido(){return $this->fluxoPedido;}

function setSitEmitirNf($sitEmitirNf){$this->sitEmitirNf = $sitEmitirNf;}
function getSitEmitirNf(){return $this->sitEmitirNf;}

function setSitBaixado($sitBaixado){$this->sitBaixado = $sitBaixado;}
function getSitBaixado(){return $this->sitBaixado;}

function setSitAberto($sitAberto){$this->sitAberto = $sitAberto;}
function getSitAberto(){return $this->sitAberto;}

function setValorPedMinimo($valorPedMinimo){$this->valorPedMinimo =$valorPedMinimo;}
function getValorPedMinimo($format=null){
    if (!empty($this->valorPedMinimo)) {
        if ($format == 'F') {
            return number_format((float) $this->valorPedMinimo, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->valorPedMinimo);
        }
    } else {
        return 0;
    } 
}

function setAprovacao($aprovacao){$this->aprovacao = $aprovacao;}
function getAprovacao(){return $this->aprovacao;}

function setDescontoMaximo($descontoMaximo){$this->descontoMaximo = $descontoMaximo;}
function getDescontoMaximo($format=null){
    if (!empty($this->descontoMaximo)) {
        if ($format == 'F') {
            return number_format((float) $this->descontoMaximo, 2, ',', '.');
        } else {
            return c_tools::moedaBd($this->descontoMaximo);
        }
    } else {
        return 0;
    }
}

function setLancPedBaixado($lancPedBaixado){$this->lancPedBaixado = $lancPedBaixado;}
function getLancPedBaixado(){return $this->lancPedBaixado;}

function setTipoDesconto($tipoDesconto){$this->tipoDesconto = $tipoDesconto;}
function getTipoDesconto(){return $this->tipoDesconto;}

function setEncomenda($encomenda){$this->encomenda = $encomenda;}
function getEncomenda(){return $this->encomenda;}

function setFaturaPedido($faturaPedido){$this->faturaPedido = $faturaPedido;}
function getFaturaPedido(){return $this->faturaPedido;}

function setCasasDecimais($casasDecimais){$this->casasDecimais = $casasDecimais;}
function getCasasDecimaisParam(){return $this->casasDecimais;}

function setControleVendedor($controleVendedor){$this->controleVendedor = $controleVendedor;}
function getControleVendedorParam(){return $this->controleVendedor;}

function setTipoComissao($tipoComissao){$this->tipoComissao = $tipoComissao;}
function getTipoComissao(){return $this->tipoComissao;}
###### FIM SET's e GET's ######

/**
 * @name existeParametros
 * @description pesquisa se já existe parâmetro para a filial
 */
public function existeParametros(){
    try {
        if (empty($this->getFilial())) {
            return false;
        }
        $banco = new c_banco_pdo();
        $sql = "SELECT * FROM FAT_PARAMETRO WHERE FILIAL = :filial";
        $banco->prepare($sql);
        $banco->bindValue(':filial', $this->getFilial());
        $banco->execute();
        $result = $banco->fetchAll();
        return !empty($result) ? $result : false;
    } catch (Exception $e) {
        error_log('[c_parametro PED] existeParametros: ' . $e->getMessage());
        return false;
    }
}

/**
 * @name selectParametros
 * @description retorna parâmetros da filial informada
 */
public function selectParametros(){
    try {
        if (empty($this->getFilial())) {
            return [];
        }
        $banco = new c_banco_pdo();
        $sql = "SELECT * FROM FAT_PARAMETRO WHERE FILIAL = :filial";
        $banco->prepare($sql);
        $banco->bindValue(':filial', $this->getFilial());
        $banco->execute();
        return $banco->fetchAll();
    } catch (Exception $e) {
        error_log('[c_parametro PED] selectParametros: ' . $e->getMessage());
        return [];
    }
}

/**
 * @name incluiParametros
 * @description inclui registro de parâmetros
 * @return mixed true em sucesso ou mensagem de erro
 */
public function incluiParametros(){
    try {
        if (empty($this->getFilial())) {
            throw new Exception('Filial é obrigatória');
        }

        $banco = new c_banco_pdo();

        $sqlVerifica = "SELECT COUNT(*) as total FROM FAT_PARAMETRO WHERE FILIAL = :filial";
        $banco->prepare($sqlVerifica);
        $banco->bindValue(':filial', $this->getFilial());
        $banco->execute();
        $existe = $banco->fetchAll();
        if (($existe[0]['total'] ?? 0) > 0) {
            throw new Exception('Centro de custo já possui parâmetro cadastrado');
        }

        $sql = "INSERT INTO FAT_PARAMETRO (
                    FILIAL, GRUPOSERVICO, APRESENTACAO, OBJETIVO, GARANTIA, IMPOSTOS,
                    PRAZOENTREGA, VALIDADE, ACEITE, OBS, FLUXOPEDIDO, SITEMITIRNF, SITBAIXADO,
                    SITABERTO, VALORPEDIDOMINIMO, APROVACAO, DESCONTOMAXIMO, LANCPEDBAIXADO, TIPODESCONTO,
                    CASASDECIMAIS, CONTROLEVENDEDOR, ENCOMENDA, FATURAPEDIDO, TIPOCOMISSAO
                ) VALUES (
                    :filial, :gruposervico, '', '', '', '',
                    '', '', '', '', :fluxopedido, :sitemitirnf, :sitbaixado,
                    :sitaberto, :valorpedidominimo, :aprovacao, :descontomaximo, :lancpedbaixado, :tipodesconto,
                    :casasdecimais, :controlevendedor, :encomenda, :faturapedido, :tipocomissao
                )";

        $banco->prepare($sql);
        $casasDecimais = ($this->getCasasDecimaisParam() !== '' && $this->getCasasDecimaisParam() !== null)
            ? (int)$this->getCasasDecimaisParam() : 4;
        $controleVendedor = ($this->getControleVendedorParam() !== '' && $this->getControleVendedorParam() !== null)
            ? (int)$this->getControleVendedorParam() : 0;
        $grupoServico = $this->getGrupoServico();
        $tipoDesconto = $this->getTipoDesconto();
        $faturaPedido = $this->getFaturaPedido() !== '' ? $this->getFaturaPedido() : 'N';
        $banco->bindValue(':filial', $this->getFilial());
        $banco->bindValue(':gruposervico', $grupoServico !== '' ? $grupoServico : null);
        $banco->bindValue(':fluxopedido', $this->getFluxoPedido() ?? '');
        $banco->bindValue(':sitemitirnf', $this->getSitEmitirNf() !== '' && $this->getSitEmitirNf() !== null ? (int)$this->getSitEmitirNf() : 0);
        $banco->bindValue(':sitbaixado', $this->getSitBaixado() !== '' && $this->getSitBaixado() !== null ? (int)$this->getSitBaixado() : 0);
        $banco->bindValue(':sitaberto', $this->getSitAberto() !== '' && $this->getSitAberto() !== null ? (int)$this->getSitAberto() : 0);
        $banco->bindValue(':valorpedidominimo', $this->getValorPedMinimo());
        $banco->bindValue(':aprovacao', $this->getAprovacao() ?? '');
        $banco->bindValue(':descontomaximo', $this->getDescontoMaximo());
        $banco->bindValue(':lancpedbaixado', $this->getLancPedBaixado() ?? '');
        $banco->bindValue(':tipodesconto', $tipoDesconto !== '' ? $tipoDesconto : null);
        $banco->bindValue(':casasdecimais', $casasDecimais);
        $banco->bindValue(':controlevendedor', $controleVendedor);
        $banco->bindValue(':encomenda', $this->getEncomenda() ?? '');
        $banco->bindValue(':faturapedido', $faturaPedido);
        $banco->bindValue(':tipocomissao', (int)$this->getTipoComissao());
        $banco->execute();

        return true;
    } catch (Exception $e) {
        error_log('[c_parametro PED] incluiParametros: ' . $e->getMessage());
        return $e->getMessage();
    }
}

/**
 * @name alteraParametros
 * @description altera registro existente
 * @return mixed true em sucesso ou mensagem de erro
 */
public function alteraParametros(){
    try {
        if (empty($this->getFilial())) {
            throw new Exception('Filial é obrigatória');
        }

        $banco = new c_banco_pdo();

        $sql = "UPDATE FAT_PARAMETRO SET
                    GRUPOSERVICO = :gruposervico,
                    FLUXOPEDIDO = :fluxopedido,
                    SITEMITIRNF = :sitemitirnf,
                    SITBAIXADO = :sitbaixado,
                    SITABERTO = :sitaberto,
                    VALORPEDIDOMINIMO = :valorpedidominimo,
                    APROVACAO = :aprovacao,
                    DESCONTOMAXIMO = :descontomaximo,
                    LANCPEDBAIXADO = :lancpedbaixado,
                    TIPODESCONTO = :tipodesconto,
                    CASASDECIMAIS = :casasdecimais,
                    CONTROLEVENDEDOR = :controlevendedor,
                    ENCOMENDA = :encomenda,
                    FATURAPEDIDO = :faturapedido,
                    TIPOCOMISSAO = :tipocomissao
                WHERE FILIAL = :filial";

        $banco->prepare($sql);
        $casasDecimais = ($this->getCasasDecimaisParam() !== '' && $this->getCasasDecimaisParam() !== null)
            ? (int)$this->getCasasDecimaisParam() : 4;
        $controleVendedor = ($this->getControleVendedorParam() !== '' && $this->getControleVendedorParam() !== null)
            ? (int)$this->getControleVendedorParam() : 0;
        $grupoServico = $this->getGrupoServico();
        $tipoDesconto = $this->getTipoDesconto();
        $faturaPedido = $this->getFaturaPedido() !== '' ? $this->getFaturaPedido() : 'N';
        $banco->bindValue(':filial', $this->getFilial());
        $banco->bindValue(':gruposervico', $grupoServico !== '' ? $grupoServico : null);
        $banco->bindValue(':fluxopedido', $this->getFluxoPedido() ?? '');
        $banco->bindValue(':sitemitirnf', $this->getSitEmitirNf() !== '' && $this->getSitEmitirNf() !== null ? (int)$this->getSitEmitirNf() : 0);
        $banco->bindValue(':sitbaixado', $this->getSitBaixado() !== '' && $this->getSitBaixado() !== null ? (int)$this->getSitBaixado() : 0);
        $banco->bindValue(':sitaberto', $this->getSitAberto() !== '' && $this->getSitAberto() !== null ? (int)$this->getSitAberto() : 0);
        $banco->bindValue(':valorpedidominimo', $this->getValorPedMinimo());
        $banco->bindValue(':aprovacao', $this->getAprovacao() ?? '');
        $banco->bindValue(':descontomaximo', $this->getDescontoMaximo());
        $banco->bindValue(':lancpedbaixado', $this->getLancPedBaixado() ?? '');
        $banco->bindValue(':tipodesconto', $tipoDesconto !== '' ? $tipoDesconto : null);
        $banco->bindValue(':casasdecimais', $casasDecimais);
        $banco->bindValue(':controlevendedor', $controleVendedor);
        $banco->bindValue(':encomenda', $this->getEncomenda() ?? '');
        $banco->bindValue(':faturapedido', $faturaPedido);
        $banco->bindValue(':tipocomissao', (int)$this->getTipoComissao());
        $banco->execute();

        return true;
    } catch (Exception $e) {
        error_log('[c_parametro PED] alteraParametros: ' . $e->getMessage());
        return $e->getMessage();
    }
}

/**
 * @name excluiParametros
 * @description exclui registro existente
 * @return mixed true em sucesso ou mensagem de erro
 */
public function excluiParametros(){
    try {
        if (empty($this->getFilial())) {
            throw new Exception('Filial é obrigatória');
        }

        $banco = new c_banco_pdo();
        $sql = "DELETE FROM FAT_PARAMETRO WHERE FILIAL = :filial";
        $banco->prepare($sql);
        $banco->bindValue(':filial', $this->getFilial());
        $banco->execute();

        return true;
    } catch (Exception $e) {
        error_log('[c_parametro PED] excluiParametros: ' . $e->getMessage());
        return $e->getMessage();
    }
}

/**
 * @name getCasasDecimais
 * @description retorna CASASDECIMAIS da filial
 */
public function getCasasDecimais(){
    try {
        if (empty($this->getFilial())) {
            return 2;
        }
        $banco = new c_banco_pdo();
        $sql = "SELECT CASASDECIMAIS FROM FAT_PARAMETRO WHERE FILIAL = :filial";
        $banco->prepare($sql);
        $banco->bindValue(':filial', $this->getFilial());
        $banco->execute();
        $result = $banco->fetchAll();

        if (!empty($result)) {
            return $result[0]['CASASDECIMAIS'];
        }
        return 2;
    } catch (Exception $e) {
        error_log('[c_parametro PED] getCasasDecimais: ' . $e->getMessage());
        return 2;
    }
}

/**
 * @name getControleVendedor
 * @description retorna CONTROLEVENDEDOR da filial
 */
public function getControleVendedor(){
    try {
        if (empty($this->getFilial())) {
            return 0;
        }
        $banco = new c_banco_pdo();
        $sql = "SELECT CONTROLEVENDEDOR FROM FAT_PARAMETRO WHERE FILIAL = :filial";
        $banco->prepare($sql);
        $banco->bindValue(':filial', $this->getFilial());
        $banco->execute();
        $result = $banco->fetchAll();

        return $result[0]['CONTROLEVENDEDOR'] ?? 0;
    } catch (Exception $e) {
        error_log('[c_parametro PED] getControleVendedor: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Lista parâmetros com join de empresa (filtro opcional por nome)
 */
public function selectParametrosGeral($filtro = null){
    try {
        $banco = new c_banco_pdo();
        $sql = "SELECT DISTINCT f.*, e.NOMEFANTASIA, e.NOMEEMPRESA
                FROM FAT_PARAMETRO f
                INNER JOIN AMB_EMPRESA e ON e.CENTROCUSTO = f.FILIAL";
        if (!empty($filtro)) {
            $sql .= " WHERE e.NOMEFANTASIA LIKE :filtro OR e.NOMEEMPRESA LIKE :filtro2";
        }
        $sql .= " ORDER BY e.NOMEFANTASIA, f.FILIAL";
        $banco->prepare($sql);
        if (!empty($filtro)) {
            $termo = '%' . $filtro . '%';
            $banco->bindValue(':filtro', $termo);
            $banco->bindValue(':filtro2', $termo);
        }
        $banco->execute();
        $result = $banco->fetchAll();
        return $result ?: false;
    } catch (Exception $e) {
        error_log('[c_parametro PED] selectParametrosGeral: ' . $e->getMessage());
        return false;
    }
}

public function selecionaEmpresasCombo(){
    try {
        $banco = new c_banco_pdo();
        $sql = "SELECT CENTROCUSTO, NOMEFANTASIA, NOMEEMPRESA FROM AMB_EMPRESA ORDER BY NOMEFANTASIA";
        $banco->prepare($sql);
        $banco->execute();
        $result = $banco->fetchAll();
        $ids = [];
        $texts = [];
        foreach ($result as $row) {
            $ids[] = trim($row['CENTROCUSTO']);
            $nome = trim($row['NOMEFANTASIA'] ?? '') ?: trim($row['NOMEEMPRESA']);
            $texts[] = $nome;
        }
        return ['id' => $ids, 'text' => $texts];
    } catch (Exception $e) {
        error_log('[c_parametro PED] selecionaEmpresasCombo: ' . $e->getMessage());
        return ['id' => [], 'text' => []];
    }
}

public function selecionaBooleanosCombo(){
    try {
        $banco = new c_banco_pdo();
        $sql = "SELECT TIPO AS ID, PADRAO AS DESCRICAO FROM AMB_DDM WHERE ALIAS = 'AMB_MENU' AND CAMPO = 'BOOLEAN' ORDER BY TIPO";
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
        error_log('[c_parametro PED] selecionaBooleanosCombo: ' . $e->getMessage());
        return ['id' => ['S', 'N'], 'text' => ['Sim', 'Não']];
    }
}

public function selecionaSituacaoPedidoCombo(){
    try {
        $banco = new c_banco_pdo();
        $sql = "SELECT TIPO, PADRAO FROM AMB_DDM WHERE CAMPO = 'SITUACAOPEDIDO' ORDER BY PADRAO";
        $banco->prepare($sql);
        $banco->execute();
        $result = $banco->fetchAll();
        $ids = [];
        $texts = [];
        foreach ($result as $row) {
            $ids[] = trim($row['TIPO']);
            $texts[] = trim($row['PADRAO']);
        }
        return ['id' => $ids, 'text' => $texts];
    } catch (Exception $e) {
        error_log('[c_parametro PED] selecionaSituacaoPedidoCombo: ' . $e->getMessage());
        return ['id' => [], 'text' => []];
    }
}


}	//	END OF THE CLASS
?>
