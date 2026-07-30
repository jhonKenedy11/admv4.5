<?php

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

Class c_tabela_preco_item extends c_user {

/**
 * TABLE NAME EST_TABELA_PRECO_ITEM
 */  
    
// Campos tabela
private $id           = NULL;
private $grupo        = NULL; 
private $codigo       = NULL; 
private $margem       = NULL; 
private $precofinal   = NULL;
private $precobase    = NULL; 

/**
* METODOS DE SETS E GETS
*/

public function setId($id){
    $this->id = $id;
}

public function getId(){
         return $this->id;
}

public function setGrupo($grupo){
	$this->grupo = $grupo;
}

public function getGrupo(){
	return $this->grupo;
}

public function setCodigo($codigo) {
	if ($codigo == ''){
		$this->codigo = null;
	} else {
		$this->codigo = $codigo;
	}
}

public function getCodigo(){
	return $this->codigo ?? null;
}

public function setPrecoFinal($precofinal, $format=false) {
	if (!is_numeric($precofinal)) {
		$precofinal = c_tools::stringToDouble($precofinal);
	}
	$this->precofinal = (float) $precofinal;
	if ($format):
			$this->precofinal = number_format($this->precofinal, 2, ',', '.');
	endif;
	
}

public function getPrecoFinal($format = null) {
	if (isset($this->precofinal)):
			switch ($format) {
					case 'B':
							return c_tools::moedaBd(number_format((double) $this->precofinal, 2, ',', ''));
							break;
					case 'F':
							return number_format((double) $this->precofinal, 2, ',', '.');
							break;
					default :
							return $this->precofinal;
			}
	else:
			return 0;
	endif;
}

public function setMargem($margem, $format=false) {
	$this->margem = $margem;
	if ($format):
			$this->margem = number_format($this->margem, 2, ',', '.');
	endif;
	
}

public function getMargem($format = null) {
	if (isset($this->margem)):
			switch ($format) {
					case 'B':
							// Formata margem com vírgula antes de converter para padrão de banco
							return c_tools::moedaBd(number_format((double) $this->margem, 2, ',', ''));
							break;
					case 'F':
							return number_format((double) $this->margem, 2, ',', '.');
							break;
					default :
							return $this->margem;
			} else:
				return 0;
		endif;
}

public function setPrecoBase($precobase, $format=false) {
	$this->precobase = $precobase;
	if ($format):
			$this->precobase = number_format($this->precobase, 2, ',', '.');
	endif;
	
}

public function getPrecoBase($format = null) {
	if (isset($this->precobase)):
			switch ($format) {
					case 'B':
							// Formata preço base com vírgula antes de converter para padrão de banco
							return c_tools::moedaBd(number_format((double) $this->precobase, 4, ',', ''));
							break;
					case 'F':
							return number_format((double) $this->precobase, 2, ',', '.');
							break;
					default :
							return $this->precobase;
			}
	else:
			return 0;
	endif;
}

public function setMarca($marca) {
	$this->marca = $marca;
}

public function getMarca() {
	return $this->marca;
}	

public function setCodFabricante($codigofabricante) {
	$this->codigofabricante = $codigofabricante;
}

public function getCodFabricante() {
	return $this->codigofabricante;
}

public function setDescricao($descricao) {
	$this->descricao = $descricao;
}

public function getDescricao() {
	return $this->descricao;
}

public function setPrecoBaseAnterior($precobaseanterior, $format=false) {
	if (!is_numeric($precobaseanterior)) {
		$precobaseanterior = c_tools::stringToDouble($precobaseanterior);
	}
	$this->precobaseanterior = (float) $precobaseanterior;
	if ($format):
		$this->precobaseanterior = number_format($this->precobaseanterior, 4, ',', '.');
	endif;
}

public function getPrecoBaseAnterior($format = null) {
	if (isset($this->precobaseanterior)):
			switch ($format) {
					case 'B':
							return c_tools::moedaBd(number_format((double) $this->precobaseanterior, 4, ',', ''));
							break;
					case 'F':
							return number_format((double) $this->precobaseanterior, 4, ',', '.');
							break;
					default :
							return $this->precobaseanterior;
			}
	else:
			return 0;
	endif;
}

public function setNome($nome) {
	$this->nome = $nome;
}

public function getNome() {
	return $this->nome;
}

public function setIdTabelaPreco($idtabelapreco) {
	$this->idtabelapreco = $idtabelapreco;
}

public function getIdTabelaPreco() {
	return $this->idtabelapreco;
}

//############### FIM SETS E GETS ###############

public function buscar_tabela_preco_item() {
	$item = $this->select_tabela_preco_item();
	$this->setId($item[0]['ID']);
	$this->setGrupo($item[0]['GRUPO']);
	$this->setCodigo($item[0]['CODIGO']);
	$this->setMargem($item[0]['MARGEM']);
	$this->setPrecoFinal($item[0]['PRECOFINAL']);
	$this->setPrecoBase($item[0]['PRECOBASE']);
	$this->setMarca($item[0]['MARCA']);
	$this->setCodFabricante($item[0]['CODIGOFABRICANTE']);
	$this->setDescricao($item[0]['DESCRICAO']);
	$this->setPrecoBaseAnterior($item[0]['PRECOBASEANTERIOR']);
} 

public function existe_tabela_preco() {
	 $sql = "SELECT * ";
	$sql .= "FROM EST_TABELA_PRECO_ITEM ";
	$sql .= "WHERE (ID = '" . $this->getId() . "'); ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return is_array($banco->resultado);
}


public function select_tabela_preco_item() {
	$sql = "SELECT * ";
	$sql .= "FROM EST_TABELA_PRECO_ITEM ";
	$sql .= "WHERE (ID = '" . $this->getId() . "') ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}



public function select_tabela_preco_item_geral() {
	$sql = "SELECT I.ID, I.CODIGO, I.CODIGOFABRICANTE, I.DESCRICAO, I.PRECOBASE, I.MARGEM, I.PRECOFINAL, I.ID_TABELA_PRECO, M.DESCRICAO as MARCA, G.DESCRICAO as GRUPO ";
	$sql .= "FROM EST_TABELA_PRECO_ITEM I ";
	$sql .= "LEFT JOIN EST_MARCA M ON I.MARCA = M.ID ";
	$sql .= "LEFT JOIN EST_GRUPO G ON I.GRUPO = G.ID ";
	$sql .= "WHERE I.ID_TABELA_PRECO = '" . $this->getIdTabelaPreco() . "' ";
	$sql .= "ORDER BY I.ID; ";

	$banco = new c_banco;
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

public function alterar_tabela_preco_item() {

	$sql = "UPDATE EST_TABELA_PRECO_ITEM ";
	$sql .= "SET GRUPO = '" . $this->getGrupo() . "', ";
	$sql .= " CODIGO = " . ( $this->getCodigo() == '' ? 'null' : $this->getCodigo() ) . ", ";
	$sql .= " CODIGOFABRICANTE = '" . $this->getCodFabricante() . "', ";
	$sql .= " ID_TABELA_PRECO = '" . $this->getIdTabelaPreco() . "', ";
	$sql .= " DESCRICAO = '" . $this->getDescricao() . "', ";
	$sql .= " MARCA = '" . $this->getMarca() . "', ";
	$sql .= " PRECOBASE = '" . $this->getPrecoBase('B') . "', ";
	$sql .= " MARGEM = '" . $this->getMargem('B') . "', ";
	$sql .= " PRECOFINAL = '" . $this->getPrecoFinal('B') . "', ";
	$sql .= " PRECOBASEANTERIOR = '" . $this->getPrecoBaseAnterior('B') . "' ";
	$sql .= "WHERE (ID = '" . $this->getId() . "') ";

	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Tabela ' . $this->getNome() . ' n&atilde;o foi alterado!';
	}
}// alteraAtividade

public function excluir_tabela_preco_item() {
	$sql = "DELETE FROM EST_TABELA_PRECO_ITEM ";
	$sql .= "WHERE (ID = '" . $this->getId() . "') ";
	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
			return '';
	} else {
			return 'Tabela ' . $this->getNome() . ' n&atilde;o foi excluida!';
	}
}

public function incluir_tabela_preco_item() {
	$sql = "INSERT INTO EST_TABELA_PRECO_ITEM 
			(CODIGOFABRICANTE,
			ID_TABELA_PRECO,
			GRUPO,
			CODIGO,
			DESCRICAO,
			PRECOFINAL,
			MARGEM,
			PRECOBASE,
			MARCA) 
		VALUES ('" . $this->getCodFabricante() . "',
			'" . $this->getIdTabelaPreco() . "',
			'" . $this->getGrupo() . "',
			" . ( $this->getCodigo() == '' ? 'null' : $this->getCodigo() ) . ",
			'" . $this->getDescricao() . "',
			'" . $this->getPrecoFinal('B') . "',
			'" . $this->getMargem('B') . "',
			'" . $this->getPrecoBase('B') . "',
			'" . $this->getMarca() . "'
		)";
	$banco = new c_banco;
	$res = $banco->exec_sql($sql);
	$banco->close_connection();
	if ($res > 0) {
		return '';
	} else {
		return 'Item ' . $this->getDescricao() . ' n&atilde;o foi incluido!';
	}
}

public function combosGrupo() {
	$sql = "SELECT ID, DESCRICAO from est_grupo order by DESCRICAO asc";
	$consulta = new c_banco();
	$consulta->exec_sql($sql);
	$consulta->close_connection();
	$result = $consulta->resultado;
	$grupo_ids[0] = '';
	$grupo_names[0] = 'Selecione Grupo';
	for ($i = 0; $i < count($result); $i++) {
		$grupo_ids[$i + 1] = $result[$i]['ID'];
		$grupo_names[$i + 1] = $result[$i]['DESCRICAO'];
	}
	return array($grupo_ids, $grupo_names);
}

public function combosMarca() {
	$sql = "SELECT id, descricao from est_marca order by descricao asc";
	$consulta = new c_banco();
	$consulta->exec_sql($sql);
	$consulta->close_connection();
	$result = $consulta->resultado;
	$marca_ids[0] = '';
	$marca_names[0] = 'Selecione Marca';
	for ($i = 0; $i < count($result); $i++) {
		$marca_ids[$i + 1] = $result[$i]['ID'];
		$marca_names[$i + 1] = $result[$i]['DESCRICAO'];
	}
	return array($marca_ids, $marca_names);
}

public function combosTabelaPreco() {
	$sql = "SELECT ID, NOME from EST_TABELA_PRECO order by NOME asc";
	$consulta = new c_banco();
	$consulta->exec_sql($sql);
	$consulta->close_connection();
	$result = $consulta->resultado;
	$tabela_ids[0] = '';
	$tabela_names[0] = 'Selecione Tabela Preço';
	for ($i = 0; $i < count($result); $i++) {
		$tabela_ids[$i + 1] = $result[$i]['ID'];
		$tabela_names[$i + 1] = $result[$i]['NOME'];
	}
	return array($tabela_ids, $tabela_names);
}

public function select_produto($codfab) {
	$sql = "SELECT COALESCE(CODIGO, codigo) AS CODIGO FROM est_produto WHERE codfabricante = '" . $codfab . "' LIMIT 1";
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	if (is_array($banco->resultado)) {
		return $banco->resultado[0]['CODIGO'] ?? '';
	} else {
		return '';
	}
}

public function select_tabela_preco_item_by_codigo() {
	$sql = "SELECT ID, PRECOBASE, ID_TABELA_PRECO FROM EST_TABELA_PRECO_ITEM 
	WHERE ID_TABELA_PRECO = '" . $this->getIdTabelaPreco() . "'  
	AND CODIGOFABRICANTE = '" . $this->getCodFabricante() . "' LIMIT 1";
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
}

public function buscaMargem() {
	$sql = "SELECT MARGEM FROM EST_TABELA_PRECO WHERE ID = '" . $this->getId() . "'";
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	$resultado = $banco->resultado;
	$this->setMargem($resultado[0]['MARGEM']);
}

/**
 * Verifica e carrega o arquivo Excel enviado.
 * Retorna o objeto Spreadsheet_Excel_Reader carregado ou lança Exception em caso de erro.
 *
 * @param array $arquivo - elemento $_FILES['arquivo_excel']
 * @return Spreadsheet_Excel_Reader
 * @throws Exception
 */
public function verificarArquivoImportacao($arquivo) {
	if (!isset($arquivo['tmp_name']) || empty($arquivo['tmp_name'])) {
		$mensagem = "Arquivo não enviado.";
		throw new Exception($mensagem);
	}

	$tmp = $arquivo['tmp_name'];
	$data = new Spreadsheet_Excel_Reader();
	$data->setUTFEncoder('UTF-8');
	$data->setOutputEncoding('UTF-8');
	$data->read($tmp);

	if (empty($data->sheets[0]['numRows']) || $data->sheets[0]['numRows'] < 2) {
		$mensagem = "Arquivo vazio ou sem linhas de dados.";
		throw new Exception($mensagem);
	}

	return $data;
}

/**
 * Monta o array de preview a partir do objeto Spreadsheet_Excel_Reader.
 *
 * @param Spreadsheet_Excel_Reader $data
 * @return array
 */
public function montarPreviewImportacao($data) {
	$preview = array();
	for ($r = 2; $r <= $data->sheets[0]['numRows']; $r++) {
		$codfab    = trim($data->sheets[0]['cells'][$r][1]);
		$descricao = trim($data->sheets[0]['cells'][$r][2]);
		$marca     = trim($data->sheets[0]['cells'][$r][3]);
		$grupo     = trim($data->sheets[0]['cells'][$r][4]);
		$precobase = trim($data->sheets[0]['cells'][$r][5]);
		$margem    = trim($data->sheets[0]['cells'][$r][6]);

		$product_codigo = '';
		if ($codfab !== '') {
			$product_codigo = $this->select_produto($codfab);
		}

		$preview[] = array(
			'codigo' => $product_codigo,
			'codigo_fabricante' => $codfab,
			'descricao' => $descricao,
			'grupo' => $grupo,
			'marca' => $marca,
			'precobase' => $precobase,
			'margem' => $margem,
		);
	}
	return $preview;
}

  

}	//	END OF THE CLASS
?>
