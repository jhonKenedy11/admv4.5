<?php
/**
 * @package   admv4.3.1
 * @name      c_servico
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto <>
 * @date      12/11/2020
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_database_pdo.php");
require_once($dir . "/../../../smarty/libs/Smarty.class.php");

//Class c_anuncio_mkp
Class c_servico extends c_user {

    /** @var Smarty|null */
    private $smartyPed = null;
/**
 * TABLE NAME CAT_SERVICO
 */    
    
// Campos tabela
private $id         	= NULL; // INT(11)
private $descricao  	= NULL; // VARCHAR(60)
private $unidade		= NULL; // VARCHAR(3)
private $quantidade		= NULL; // DECIMAL(6,2)
private $valorunitario	= NULL; // DECIMAL(8,2)
private $status         = NULL; // TINYINT(1)
private $created_user  	= NULL; // INT(11)
private $update_user  	= NULL; // INT(11)
private $created_at	    = NULL; // TIMESTAMP
private $update_at     	= NULL; //TIMESTAMP

//construtor
function __construct(){
    // Cria uma instancia variaveis de sessao
    session_start();
    c_user::from_array($_SESSION['user_array']);

}

/**
 * Funcao de consulta atraves do ID da table
 * @name select_servico
 * @param VARCHAR GetId Chave primaria da tabela
 * @return ARRAY de todas as colunas da table
 */
public function set_servico(){
	$result = $this->select_servico();
	$this->__set('ID',$result[0]['ID']);
	$this->__set('DESCRICAO',$result[0]['DESCRICAO']);
	$this->__set('UNIDADE',$result[0]['UNIDADE']);
	$this->__setNumber('QUANTIDADE',$result[0]['QUANTIDADE'],2,'F');
	$this->__setNumber('VALORUNITARIO',$result[0]['VALORUNITARIO'],2,'F');
	$this->__set('STATUS',$result[0]['STATUS']);
	$this->__set('CREATED_USER',$result[0]['CREATED_USER']);
	$this->__set('UPDATE_USER',$result[0]['UPDATE_USER']);
	$this->__setDateTime('CREATED_AT',$result[0]['CREATED_AT']);
	$this->__setDateTime('UPDATE_AT',$result[0]['UPDATE_AT']);

} 


 /**
 * @name select_Servico
 * @description pesquisa se já existe código do servico cadastrado
 */
public function select_servico(){

	$sql  = "SELECT DISTINCT * ";
   	$sql .= "FROM cat_servico ";
   	$sql .= "WHERE (id = ".$this->__get('ID').") ";
   	

   	//echo $sql;
	$banco = new c_banco();
	$banco->exec_sql($sql);
	$banco->close_connection();
	return $banco->resultado;
} //fim select_servico

 /**
 * @name select_servico_geral
 * @description pesquisa que retorna todos os registros cadastrado
 */
public function select_servico_geral($status = NULL){
    $sql = "SELECT * FROM CAT_SERVICO WHERE 1=1 ";

    if ($status !== NULL && is_numeric($status)) {
        $sql .= " AND STATUS = :status ";
    }

    try {
        $this->banco = new c_banco_pdo();
        $this->banco->prepare($sql);

        if ($status !== NULL && is_numeric($status)) {
            $this->banco->bindValue(":status", $status, PDO::PARAM_INT);
        }

        $this->banco->execute();
        return $this->banco->fetchAll();

    } catch (PDOException $e) {
        echo "Erro na consulta: " . $e->getMessage();
        return false;
    }
} //fim select_servico_geral

 /**
 * @name incluiServico
 * @description faz a inclusão do registro cadastrado
 */
public function incluiServico(){

	$sql  = "INSERT INTO cat_servico (DESCRICAO, UNIDADE, QUANTIDADE, VALORUNITARIO, STATUS, CREATED_USER, CREATED_AT) ";
	$sql .= "VALUES ( '".$this->__get('DESCRICAO')."','".$this->__get('UNIDADE')."','".$this->__getNumber('QUANTIDADE', 2, 'B')."'
						,'".$this->__getNumber('VALORUNITARIO', 2, 'B')."',".$this->__get('STATUS').",".$this->m_userid.",'".date("Y-m-d H:i:s"). "')"; 
					
    // echo $sql;
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($result > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->__get('DESCRICAO').' não foram cadastrados!';
	}
} // fim incluiServico

 /**
 * @name alteraServico
 * @description altera registro existente
 */
public function alteraServico(){

	$sql  = "UPDATE cat_servico ";
	$sql .= "SET DESCRICAO = '".$this->__get('DESCRICAO')."', " ;
	$sql .= "UNIDADE = '".$this->__get('UNIDADE')."',";
	$sql .= "QUANTIDADE = '".$this->__getNumber('QUANTIDADE', 2, 'B')."',";
	$sql .= "VALORUNITARIO = '".$this->__getNumber('VALORUNITARIO', 2, 'B')."',";
	$sql .= "STATUS = ".$this->__get('STATUS').",";
	$sql .= "UPDATED_USER = ".$this->m_userid.", ";
	$sql .= "UPDATED_AT = '".date("Y-m-d H:i:s")."' ";
	$sql .= "WHERE id = ".$this->__get('ID').";";
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($result > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->__get('DESCRICAO').' não foram alterados!';
	}

}  // fim alteraServico

 /**
 * @name exlcuiServico
 * @description esclui resgistro existe
 */
public function excluiServico(){

	$sql  = "DELETE FROM cat_servico ";
	$sql .= "WHERE id = ".$this->__get('ID');
	$banco = new c_banco;
	$result =  $banco->exec_sql($sql);
	$banco->close_connection();

	if($result > 0){
        return '';
	}
	else{
        return 'Os dados '.$this->__get('DESCRICAO').' não foram excluidos!';
	}
	
}  // fim excluiServico

    /**
     * Smarty para templates AJAX em telas de pedido (lista de serviços).
     *
     * @return Smarty
     */
    private function obterSmartyPed()
    {
        if ($this->smartyPed === null) {
            $this->smartyPed = new Smarty();
            $this->smartyPed->template_dir = ADMraizFonte . '/template/ped';
            $this->smartyPed->compile_dir = ADMraizCliente . '/smarty/templates_c/';
            $this->smartyPed->config_dir = ADMraizCliente . '/smarty/configs/';
            $this->smartyPed->cache_dir = ADMraizCliente . '/smarty/cache/';
        }
        return $this->smartyPed;
    }

    /**
     * Busca serviços ativos por código (exato/prefixo) e descrição.
     *
     * @return array<int,array<string,mixed>>
     */
    public function buscarServicosPorTermo(string $termo): array
    {
        $termo = trim($termo);
        if ($termo === '') {
            return [];
        }

        $likePrefix = $termo . '%';
        $filtro = 'S.STATUS = 1 AND (CAST(S.ID AS CHAR) = :termo OR CAST(S.ID AS CHAR) LIKE :likePrefix';
        $params = [
            ':termo' => $termo,
            ':likePrefix' => $likePrefix,
            ':ordTermo' => $termo,
            ':ordLike' => $likePrefix,
        ];

        if (strlen($termo) > 3) {
            $filtro .= ' OR S.DESCRICAO LIKE :likeDesc';
            $params[':likeDesc'] = '%' . $termo . '%';
        }
        $filtro .= ')';

        $sql = "SELECT S.ID, S.DESCRICAO, S.UNIDADE, S.VALORUNITARIO,
                       (CASE WHEN CAST(S.ID AS CHAR) = :termo THEN 1 ELSE 0 END) AS MATCH_EXATO
                FROM CAT_SERVICO S
                WHERE {$filtro}
                ORDER BY
                    CASE
                        WHEN CAST(S.ID AS CHAR) = :ordTermo THEN 0
                        WHEN CAST(S.ID AS CHAR) LIKE :ordLike THEN 1
                        ELSE 2
                    END,
                    LENGTH(CAST(S.ID AS CHAR)),
                    CAST(S.ID AS CHAR)
                LIMIT 50";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->execute($params);
        $rows = $banco->fetchAll();

        return is_array($rows) ? $rows : [];
    }

    /**
     * Retorna JSON com HTML da lista de serviços (Pedido PS e demais telas).
     */
    public function retornaHtmlServicos($termoPesquisa)
    {
        $rows = $this->buscarServicosPorTermo((string) $termoPesquisa);

        header('Content-Type: application/json; charset=utf-8');

        if ($rows === []) {
            echo json_encode(['success' => false, 'servico' => null, 'htmlServicos' => ''], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $autoSelect = (int) ($rows[0]['MATCH_EXATO'] ?? 0) === 1 || count($rows) === 1
            ? (string) ($rows[0]['ID'] ?? '')
            : null;

        $servicos = [];
        foreach ($rows as $row) {
            $id = (string) ($row['ID'] ?? '');
            $vlr = $row['VALORUNITARIO'] ?? 0;
            $servicos[] = [
                'ID' => $id,
                'DESCRICAO' => $row['DESCRICAO'] ?? '',
                'UNIDADE' => $row['UNIDADE'] ?? '',
                'VALORUNITARIO' => is_numeric($vlr) ? number_format((float) $vlr, 2, ',', '.') : '0,00',
                'SELECIONADO' => $autoSelect !== null && $id === $autoSelect,
            ];
        }

        $p = $rows[0];
        $smarty = $this->obterSmartyPed();
        $smarty->assign('servicos', $servicos);
        $smarty->assign('mostrarMensagemRefinar', count($servicos) >= 50);

        echo json_encode([
            'success' => true,
            'totalServicos' => count($servicos),
            'preencherAutomatico' => $autoSelect !== null,
            'servico' => [
                'codServico' => (string) ($p['ID'] ?? ''),
                'descricaoServico' => $p['DESCRICAO'] ?? '',
                'unidadeServico' => $p['UNIDADE'] ?? '',
                'vlrUnitarioServico' => is_numeric($p['VALORUNITARIO'] ?? null)
                    ? number_format((float) $p['VALORUNITARIO'], 2, ',', '.')
                    : '0,00',
            ],
            'htmlServicos' => $smarty->fetch('pedido_ps_servicos_lista.tpl'),
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

}	//	END OF THE CLASS
?>
