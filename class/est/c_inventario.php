<?php
/**
 * @package   astec
 * @name      c_parametro
 * @version   4.3.2
 * @copyright 2021s
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      18/05/2016
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class c_inventario
Class c_inventario extends c_user {
/**
 * TABLE NAME EST_INVENTARIO
 */
    
//construtor
function __construct(){
	c_user::from_array($_SESSION['user_array']);

}


// Campos tabela
private $id         = NULL; // VARCHAR(15)
private $referencia       = NULL; // VARCHAR(15)
private $codigoProduto      = NULL; // VARCHAR(15)
private $centroCusto   = NULL; // VARCHAR(15)
private $quantidade     = NULL; // VARCHAR(15)
private $quantAnterior      = NULL; // VARCHAR(15)
private $precoCustoNovo     = NULL; // VARCHAR(15)
private $precoCusto      = NULL; // VARCHAR(15)
private $usuario      = NULL; // VARCHAR(15)
private $status      = NULL; // VARCHAR(15)

private $idInventarioProduto         = NULL; // INT(11)



/**
* METODOS DE SETS E GETS
*/

function getId() {
   return $this->id;
}

function getIdInventarioProduto() {
	return $this->idInventarioProduto;
 }
 

function getReferencia() {
   return $this->referencia;
}

function getCodigoProduto() {
   return $this->codigoProduto;
}

function getCentroCusto() {
   return $this->centroCusto;
}



function getQuantidade($format = NULL) {
	if (!empty($this->quantidade)) {
		if ($format == 'F') {
			return number_format($this->quantidade, 2, ',', '.');
		} else {
			return c_tools::moedaBd($this->quantidade);
		}
	} else {
		return 0;
	}        
}

function getQuantAnterior($format = NULL) {
	if (!empty($this->quantAnterior)) {
		if ($format == 'F') {
			return number_format($this->quantAnterior, 2, ',', '.');
		} else {
			return c_tools::moedaBd($this->quantAnterior);
		}
	} else {
		return 0;
	}        
}
function getQuantSerEntregue($format = NULL) {
	if (!empty($this->quantSerEntregue)) {
		if ($format == 'F') {
			return number_format($this->quantSerEntregue, 2, ',', '.');
		} else {
			return c_tools::moedaBd($this->quantSerEntregue);
		}
	} else {
		return 0;
	}        
}

function getPrecoCustoNovo($format = NULL) {
	if (!empty($this->precoCustoNovo)) {
		if ($format == 'F') {
			return number_format($this->precoCustoNovo, 2, ',', '.');
		} else {
			return c_tools::moedaBd($this->precoCustoNovo);
		}
	} else {
		return 0;
	}        
}

function getPrecoCusto($format = NULL) {
	if (!empty($this->precoCusto)) {
		if ($format == 'F') {
			return number_format($this->precoCusto, 2, ',', '.');
		} else {
			return c_tools::moedaBd($this->precoCusto);
		}
	} else {
		return 0;
	}        
}



 function getUsuario() {
	return $this->usuario;
 }

 function getStatus() {
	return $this->status;
 }

function setId($id) {
   $this->id = $id;
}

function setIdInventarioProduto($idInventarioProduto) {
	$this->idInventarioProduto = $idInventarioProduto;
 }

function setReferencia($referencia) {
	$this->referencia = $referencia;
}
 
 function setCodigoProduto($codigoProduto) {
	 $this->codigoProduto = $codigoProduto;
 }
 
 function setCentroCusto($centroCusto) {
	$this->centroCusto = $centroCusto;
 }

function setQuantidade($quantidade, $format=false) {
	$this->quantidade = $quantidade; 
	if ($format):
			$this->quantidade = number_format($this->quantidade, 2, ',', '.');
	endif;
	
}

function setQuantAnterior($quantAnterior, $format=false) {
	$this->quantAnterior = $quantAnterior; 
	if ($format):
			$this->quantAnterior = number_format($this->quantAnterior, 2, ',', '.');
	endif;
	
}


function setQuantSerEntregue($quantSerEntregue, $format=false) {
	$this->quantSerEntregue = $quantSerEntregue; 
	if ($format):
			$this->quantSerEntregue = number_format($this->quantSerEntregue, 2, ',', '.');
	endif;
	
}
function setPrecoCustoNovo($precoCustoNovo, $format=false) {
	$this->precoCustoNovo = $precoCustoNovo; 
	if ($format):
			$this->precoCustoNovo = number_format($this->precoCustoNovo, 2, ',', '.');
	endif;
	
}

function setPrecoCusto($precoCusto, $format=false) {
	$this->precoCusto = $precoCusto; 
	if ($format):
			$this->precoCusto = number_format($this->precoCusto, 2, ',', '.');
	endif;
	
}
 
function setUsuario($usuario) {
	$this->usuario = $usuario;
}
 
function setStatus($status) {
	$this->status = $status;
}
//############### FIM SETS E GETS ###############

    public function setInventario() {
        $inventario = $this->select_inventario();
        $this->setId($inventario[0]['ID']);
        $this->setReferencia($inventario[0]['REFERENCIA']);
        $this->setCodigoProduto($inventario[0]['CODIGOPRODUTO']);
        $this->setCentroCusto($inventario[0]['CENTROCUSTO']);
        $this->setQuantidade($inventario[0]['QUANTIDADE']);
        $this->setQuantAnterior($inventario[0]['QUANTANTERIOR']);
        $this->setPrecoCustoNovo($inventario[0]['PRECOCUSTONOVO']);
		$this->setPrecoCusto($inventario[0]['PRECOCUSTO']);
		$this->setUsuario($inventario[0]['USUARIO']);
		$this->setStatus($inventario[0]['STATUS']);
    }

/**
 * Funcao de consulta atraves do ID da table
 * @name select_parametro
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function select_inventario(){
	$sql  = "SELECT * ";
   	$sql .= "FROM est_inventario ";
   	$sql .= "WHERE (ID = '".$this->getId()."') ";
        // echo strtoupper($sql);
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_grupo

/**
 * Funcao de consulta para todos os registros da tabela
 * @name select_grupo_geral
 * @return ARRAY de todas as colunas da table
 */
public function select_inventario_letra($letra){

	$par = explode("|", $letra);
	
	if (!empty($par[0])) {
		$dataIni = DateTime::createFromFormat('d/m/Y', $par[0]);
		$dataIni = $dataIni ? $dataIni->format('Y-m-d 00:00:00') : '';
	}
	if (!empty($par[1])) {
		$dataFim = DateTime::createFromFormat('d/m/Y', $par[1]);
		$dataFim = $dataFim ? $dataFim->format('Y-m-d 23:59:59') : '';
	}
	
	$sql  = "SELECT *, U.NOME,  IF (STATUS = 'A', 'ABERTO', 'BAIXADO') as STATUS ";
   	$sql .= "FROM EST_INVENTARIO I ";
	$sql .= "LEFT JOIN AMB_USUARIO U ON (I.USUARIO = U.USUARIO) ";
	$sql .= "where (I.CENTROCUSTO = '".$this->m_empresacentrocusto."') ";


	$cond =  strpos($sql, 'where') === false ? 'where' : 'and';
	if (!empty($dataIni) && !empty($dataFim)) {
		$sql .= " $cond (CREATED_AT BETWEEN '$dataIni' AND '$dataFim') ";
	} elseif (!empty($dataIni)) {
		$sql .= " $cond (CREATED_AT >= '$dataIni') ";
	}
		   
	$cond =  strpos($sql, 'where') === false ? 'where' : 'and';
	$sql .= empty($par[1]) ? '':" $cond (STATUS = '".$par[2]."') ";
	
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_inventario_letra

public function select_inventario_produto_letra(){

	$sql  = "SELECT IP.*, P.DESCRICAO AS DESCPRODUTO, P.UNIDADE, P.CODIGOBARRAS AS EAN, G.DESCRICAO AS DESCGRUPO, P.UNIFRACIONADA as UNIFRACIONADA ";
   	$sql .= "FROM EST_INVENTARIO_PRODUTO IP ";
	$sql .= "LEFT JOIN EST_PRODUTO P ON (IP.CODPRODUTO = P.CODIGO) ";
	$sql .= "LEFT JOIN EST_GRUPO G ON (P.GRUPO = G.GRUPO) ";
	$sql .= "where INVENTARIOID =".$this->getId();

	$sql .= " ORDER BY P.GRUPO, P.DESCRICAO";
	
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_inventario_letra

public function select_prod_inventario_cadastro_letra($letra, $grupo){

	$par = explode("|", $letra);
	$parGrupo = explode("|", $grupo);


	$sql  = "SELECT DISTINCT P.*, G.DESCRICAO AS DESCGRUPO, P.DESCRICAO AS DESCPRODUTO ";
   	$sql .= "FROM EST_PRODUTO P ";
	$sql .= "LEFT JOIN EST_GRUPO G ON (P.GRUPO = G.GRUPO) ";
	
	if($par[0] != ''){
		$cond =  strpos($sql, 'where') === false ? 'where' : 'and';
		$sql .= empty($par[0]) ? '':" $cond (P.CODIGO = '".$par[0]."') ";

	}else if ($par[1] != ''){
		$cond =  strpos($sql, 'where') === false ? 'where' : 'and';
		$sql .= empty($par[1]) ? '':" $cond (P.LOCALIZACAO = '".$par[1]."') ";
	}else{

		$countGrupo = count($parGrupo)-1;
        $grupos = '';
        for($i = 1; $i < count($parGrupo); $i++){
            $i != $countGrupo ? $grupos .= "'".$parGrupo[$i]."'," : $grupos .= "'".$parGrupo[$i]."'";
        }
        $cond =  strpos($sql, 'where') === false ? 'where' : 'and';
        $sql .= empty($grupos) ? '':" $cond (P.GRUPO IN (".$grupos.")) ";

	}


	$sql .= "and NOT EXISTS ";
	$sql .= "(SELECT * FROM EST_INVENTARIO_PRODUTO PI ";
	$sql .= "where ((P.CODIGO = PI.CODPRODUTO) AND PI.INVENTARIOID = '".$this->getId()."'))";
	$sql .= "GROUP BY P.CODIGO, G.DESCRICAO, P.DESCRICAO ";
	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_inventario_letra



/**
* Funcao para Inclusao no banco
* @name incluiGrupo
* @return string vazio se ocorrer com sucesso
*/
public function incluiInventario(){
	
	$sql  = "INSERT INTO EST_INVENTARIO (REFERENCIA,  CENTROCUSTO,  USUARIO, STATUS, CREATED_USER , CREATED_AT) ";
	$sql .= "VALUES ('".$this->getReferencia()."', '".$this->getCentroCusto()."', ";
    $sql .= $this->m_userid." , '".$this->getStatus()."', '".$this->m_userid."', '".date("Y-m-d H:i:s")."'); ";
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$lastReg    = mysqli_insert_id($banco->id_connection);
	$banco->close_connection();

	if($resgrupo > 0){
        return $lastReg;
	}
	else{
        return 'Os dados do Inventario n&atilde;o foi cadastrado!';
	}
} // fim 

public function incluiInventarioProduto($dadosProdInventario){
	$par =  explode("|", $dadosProdInventario);
	for($i=0; $i < count($par); $i++){
		if($par[$i] != ''){
			$parProduto =  explode("*", $par[$i]);
			$sql = '';
			$sql  = "INSERT INTO EST_INVENTARIO_PRODUTO (INVENTARIOID,  CODPRODUTO, PRECOCUSTONOVO, CREATED_USER , CREATED_AT) ";
			$sql .= "VALUES ('".$this->getId()."', '".$parProduto[0]."', ";
			$sql .= "'".$parProduto[1]."', '";
			$sql .= $this->m_userid."', '".date("Y-m-d H:i:s")."'); ";
			$banco = new c_banco;
			$resgrupo =  $banco->exec_sql($sql);
			$banco->close_connection();
		}
		
	}
	

	if($resgrupo > 0){
        return '';
	}
	else{
        return 'Os dados do Inventario n&atilde;o foi cadastrado!';
	}
} // fim 



public function alteraInventarioProduto($dadosProdInventario){
    $par =  explode("|", $dadosProdInventario);
    for($i=0; $i < count($par); $i++){
        if($par[$i] != ''){
            $parProduto =  explode("*", $par[$i]);
            $idProduto = $parProduto[0];
            $novoPreco = $parProduto[1];
            $novaQuantidade = $parProduto[2];

            // 1. Buscar quantidade anterior (QUANTIDADENOVA atual)
            $banco = new c_banco;
            $sqlSelect = "SELECT QUANTIDADENOVA FROM EST_INVENTARIO_PRODUTO WHERE CODPRODUTO = '".$idProduto."'";
            $res = $banco->exec_sql($sqlSelect);
            $quantidadeAnterior = 0;
            if ($res && $res[0]['QUANTIDADENOVA'] !== null) {
                $quantidadeAnterior = $res[0]['QUANTIDADENOVA'];
            }

            // 2. Calcular a diferença
            $qtdeMovimentada = $novaQuantidade - $quantidadeAnterior;

            // 3. Montar o UPDATE
            $sql  = "UPDATE EST_INVENTARIO_PRODUTO ";
            $sql .= "SET  " ;
            $sql .= "QUANTIDADEANTERIOR 	= QUANTIDADENOVA, " ;
            $sql .= "PRECOCUSTOANTERIOR 	= PRECOCUSTONOVO, " ;
            $sql .= "QUANTIDADENOVA 		= '".$novaQuantidade."', " ;
            $sql .= "PRECOCUSTONOVO			= '".$novoPreco."', " ;
            $sql .= "QUANTIDADEMOVIMENTADA  = '".$qtdeMovimentada."', " ;        
            $sql .= "UPDATED_USER			= '".$this->m_userid."', " ;
            $sql .= "UPDATED_AT 			= '".date("Y-m-d H:i:s")."' " ;
            $sql .= "WHERE ID = '".$idProduto."';";

            $resgrupo =  $banco->exec_sql($sql);
            $banco->close_connection();
        }
    }

    if($resgrupo > 0){
        return '';
    }
    else{
        return 'Os dados do Inventario n&atilde;o foi cadastrado!';
    }
} // fim 

/**
* Funcao para Alteracao no banco
* @name alteraGrupo
* @return string vazio se ocorrer com sucesso
*/
public function alteraInventario(){

	$sql  = "UPDATE EST_INVENTARIO ";
	$sql .= "SET  REFERENCIA 		= '".$this->getReferencia()."', " ;
	$sql .= "CODIGOPRODUTO 			= '".$this->getCodigoProduto()."', " ;
	$sql .= "CENTROCUSTO 			= '".$this->getCentroCusto()."', " ;
	$sql .= "QUANTIDADE 			= '".$this->getQuantidade('B')."', " ;
	$sql .= "QUANTANTERIOR 			= '".$this->getQuantAnterior('B')."', " ;
	$sql .= "PRECOCUSTONOVO			= '".$this->getPrecoCustoNovo('B')."', " ;
	$sql .= "PRECOCUSTO 			= '".$this->getPrecoCusto('B')."', " ;
	$sql .= "USUARIO 				= '".$this->getUsuario()."', " ;
	$sql .= "STATUS 				= '".$this->getStatus()."', " ;
	$sql .= "UPDATED_USER			= '".$this->m_userid."', " ;
	$sql .= "UPDATED_AT 			= '".date("Y-m-d H:i:s")."' " ;
	$sql .= "WHERE ID = '".$this->getId()."';";
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$banco->close_connection();
        // echo strtoupper($sql);
	if($resgrupo > 0){
        return '';
	}
	else{
        return 'Os dados do Inventario n&atilde;o foi alterado!';
	}

}  // fim 


public function alteraInventarioProdutoQtdeMov($tipoMov, $qtdeMovimentada, $id){

	$sql  = "UPDATE EST_INVENTARIO_PRODUTO ";
	$sql .= "SET " ;
	$sql .= "TIPOMOVIMENTO 			= '".$tipoMov."', " ;
	$sql .= "QUANTIDADEMOVIMENTADA  = '".$qtdeMovimentada."', " ;
	$sql .= "UPDATED_USER			= '".$this->m_userid."', " ;
	$sql .= "UPDATED_AT 			= '".date("Y-m-d H:i:s")."' " ;
	$sql .= "WHERE ID = '".$id."';";
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$banco->close_connection();
        // echo strtoupper($sql);
	if($resgrupo > 0){
        return '';
	}
	else{
        return 'Os dados do Inventario n&atilde;o foi alterado!';
	}

}  // fim 


public function alteraStatusInventario($status, $id){

	$sql  = "UPDATE EST_INVENTARIO ";
	$sql .= "SET " ;
	$sql .= "STATUS 				= '".$status."', " ;
	$sql .= "UPDATED_USER			= '".$this->m_userid."', " ;
	$sql .= "UPDATED_AT 			= '".date("Y-m-d H:i:s")."' " ;
	$sql .= "WHERE ID = '".$id."';";
	$banco = new c_banco;
	$resgrupo =  $banco->exec_sql($sql);
	$banco->close_connection();
        // echo strtoupper($sql);
	if($resgrupo > 0){
        return '';
	}
	else{
        return 'Os dados do Inventario n&atilde;o foi alterado!';
	}

}  // fim 

public function excluirInventarioProduto($idProdutoInventario){

	$sql  = "DELETE FROM EST_INVENTARIO_PRODUTO ";
	$sql .= "WHERE (ID = ".$idProdutoInventario.")";
	$banco = new c_banco;
	$res =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($res > 0)
        return '';
	else
        return 'O Produto do Inventario não foi excluido!';
	
}  // fim exlcuiNcm


    //imagem produto

    /**
    * Funcao para selecionar imagem do produto estoque
    * @name select_produto_imagem
    * @param INT $id
    * @return array com as imagens do produto selecionado
    */
    public function select_inventario_produto_imagem($id=null){

        if ($id==null):
            $id = $this->getId();
        endif;
        
        $sql  = "SELECT * ";
        $sql .= "FROM AMB_IMAGEM ";
        $sql .= "WHERE (ID_DOC = ".$id.") AND (MODULO = 'INV') ; ";
            //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;

    } //fim select_conta_geral

    /**
    * Funcao para gravar imagem do produto estoque
    * @name gravaImagemProduto
    * @param String $mod
    * @param String $destaque
    * @return int id da imagem gravada
    */
    public function gravaImagemProduto($id, $mod, $destaque){
        $sql  = "INSERT INTO AMB_IMAGEM (ID_DOC, DESTAQUE, MODULO, USERINSERT )";
        $sql .= "VALUES (".$id.", '".$destaque."', '".$mod."', ".$this->m_userid.")";
            //echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
            if ($banco->result):
                $lastReg = $banco->insertReg;
                $banco->close_connection();
                return $lastReg;
            else:
                $banco->close_connection();
                return '';
            endif;

    } //fim gravaImagemProduto

    /**
    * Funcao para excluir imagem do produto estoque
    * @name excluiImagemProduto
    * @param int $id
    * @return string vazio se ocorrer com sucesso
    */
    public function excluiImagemProduto($id){
        $sql  = "DELETE FROM AMB_IMAGEM ";
        $sql .= "WHERE (ID = ".$id.");";
        $banco = new c_banco;
        $res_imagem =  $banco->exec_sql($sql);
        $banco->close_connection();
            //echo strtoupper($sql);
        if($res_imagem > 0){
                return '';
        }
        else{
                return 'A Imagem não foi excluida!';
        }

    } //fim excluiImagemProduto

    /**
    * Funcao para por a imagem do produto estoque em destaque
    * @name destaqueImagemProduto
    * @param int $id
    * @param CHAR $destaque
    * @return string vazio se ocorrer com sucesso
    */
    public function destaqueImagemProduto($id, $destaque){
        if ($destaque == 'N'):
            $destaque ='S';
        else:
            $destaque ='N';
        endif;
        $sql  = "UPDATE AMB_IMAGEM ";
        $sql .= "SET  DESTAQUE = '".$destaque."' ";
        $sql .= "WHERE ID = ".$id.";";
        $banco = new c_banco;
        $res_imagem =  $banco->exec_sql($sql);
        $banco->close_connection();
            //echo strtoupper($sql);
        if($res_imagem > 0):
                return '';
        
        else:
                return 'A Imagem não entrou em destaque!';

        endif;
    } 
    // fim destaqueImagemProduto

    /**
    * Funcao para Não por a imagem do produto estoque em destaque
    * @name destaqueImagemProdutoNao
    * @return string vazio se ocorrer com sucesso
    */
    public function destaqueImagemProdutoNao($id){

        $sql  = "UPDATE AMB_IMAGEM ";
        $sql .= "SET  DESTAQUE = 'N' ";
        $sql .= "WHERE ID_DOC = ".$id." AND MODULO = 'INV'";
        $banco = new c_banco;
        $res_imagem =  $banco->exec_sql($sql);
        $banco->close_connection();
            //echo strtoupper($sql);
        if($res_imagem > 0){
                return '';
        }
        else{
                return 'A Imagem não entrou em destaque!';
        }
    }

    /**
     * Realiza a pesquisa de itens para o inventário conforme filtros recebidos.
     * 
     * Para cada filtro preenchido, adiciona uma condição na cláusula WHERE da consulta SQL.
     * O filtro 'foraLinha' determina se o produto está ou não fora de linha, verificando o campo DATAFORALINHA.
     * Após montar a consulta, executa no banco de dados e obtém os resultados.
     * Os resultados são atribuídos à variável 'resultados' no Smarty.
     * Em seguida, renderiza o template 'inventario_produto_modal_resultados.tpl' com os dados encontrados.
     * Por fim, retorna o HTML gerado em formato JSON para ser utilizado na interface (normalmente via AJAX).
     */
    public function retornaPesquisaItensInventarioModal($filtros) {
    $sql = "SELECT EP.CODIGO, EP.DESCRICAO, EG.DESCRICAO AS GRUPO, EP.LOCALIZACAO FROM EST_PRODUTO EP ";
    $sql .="INNER JOIN EST_GRUPO EG ON EG.GRUPO = EP.GRUPO";
    
    $conditions = [];
    
    // controle de filtros do where.
    if (!empty($filtros['codigo'])) {
        $conditions[] = "EP.CODIGO = '{$filtros['codigo']}'";
    }
    if (!empty($filtros['nome'])) {
        $conditions[] = "EP.DESCRICAO LIKE '%{$filtros['nome']}%'";
    }
    if (!empty($filtros['grupo'])) {
        $grupo = "'" . str_replace(",", "','", $filtros['grupo']) . "'";
        $conditions[] = "EP.GRUPO IN ({$grupo})";
    }
    if (!empty($filtros['localizacao'])) {
        $conditions[] = "EP.LOCALIZACAO LIKE '{$filtros['localizacao']}%'";
    }
    $conditions[] = ($filtros['foraLinha'] == 1) ? "EP.DATAFORALINHA IS NOT NULL" : "EP.DATAFORALINHA IS NULL";
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY EP.DESCRICAO ASC";
    
    $banco = new c_banco;
    $banco->exec_sql($sql);
    $resultados = $banco->resultado;
    $banco->close_connection();
    
    $this->smarty->assign('resultados', $resultados);
    $html = $this->smarty->fetch('inventario_produto_modal_resultados.tpl');
    
    header('Content-Type: application/json');
    echo json_encode(['html' => $html]);
    exit;

	}


    /**
     * Função para incluir itens selecionados no inventário via modal.
     * 
     * Recebe o ID do inventário e um array de códigos de produtos selecionados.
     * Para cada código de produto, insere um registro na tabela EST_INVENTARIO_PRODUTO,
     * associando o produto ao inventário informado.
     * Caso algum insert falhe, interrompe o processo e redireciona para a tela de alteração do inventário,
     * passando uma mensagem de erro.
     * Se todos os itens forem incluídos com sucesso, redireciona para a tela de alteração do inventário normalmente.
     * 
     * Normalmente utilizada em requisições AJAX para adicionar múltiplos itens de uma só vez.
     */
    public function incluiItensInventarioModal($id, $itens) {
        $idInventario = $id;
        $sucesso = true;
        $msg = '';

        foreach ($itens as $codigo) {
            $sql  = "INSERT INTO EST_INVENTARIO_PRODUTO (INVENTARIOID, CODPRODUTO, CREATED_USER, CREATED_AT) ";
            $sql .= "VALUES ('" . $idInventario . "', '" . $codigo . "', '" . $this->m_userid . "', '" . date("Y-m-d H:i:s") . "');";
            $banco = new c_banco;
            $resgrupo = $banco->exec_sql($sql);
            $banco->close_connection();

            if (!$resgrupo) {
                $sucesso = false;
                $msg = 'Erro ao incluir item: ' . $codigo;
                break;
            }
        }

        // Retorno para AJAX
        header('Content-Type: application/json');
        if ($sucesso) {
            $redirectUrl = "index.php?mod=est&form=inventario&submenu=alterar&id=" . $idInventario;
            echo json_encode(['success' => true, 'redirect' => $redirectUrl]);
        } else {
            $redirectUrl = "index.php?mod=est&form=inventario&submenu=alterar&id=" . $idInventario . "&erro=" . urlencode($msg);
            echo json_encode(['success' => false, 'redirect' => $redirectUrl, 'msg' => $msg]);
        }
        exit;
    }


    public function excluirInventarioCompleto($idInventario) {
        // Exclui todos os itens do inventário
        $sqlItens = "DELETE FROM EST_INVENTARIO_PRODUTO WHERE INVENTARIOID = '$idInventario'";
        $banco = new c_banco;
        $banco->exec_sql($sqlItens);

        // Exclui o inventário
        $sqlInv = "DELETE FROM EST_INVENTARIO WHERE ID = '$idInventario' AND STATUS = 'A'";
        $res = $banco->exec_sql($sqlInv);
        $banco->close_connection();

        if ($res > 0) {
            return '';
        } else {
            return 'Erro ao excluir inventário. Verifique se o status está "Aberto".';
        }
    }


    function comboSql($sql, $par, &$id, &$ids, &$names) {
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        // $ids[0] = '';
        // $names[0] = '';
        for ($i = 0; $i < count($result); $i++) {
            $ids[$i+1] = $result[$i]['ID'];
            $names[$i+1] = $result[$i]['DESCRICAO'];
        }
        
        $param = explode("|", $par);
        for($i=0; $i < count($param); $i++){
            if($param[$i] != '')
                $id[$i] = $param[$i];
        } 
    }
}	//	END OF THE CLASS
?>
