<?php
/**
 * @package   adm4.5
 * @name      c_cst_ibs_cbs
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      17/12/2025
 */

$dir = dirname(__FILE__);

include_once($dir."/../../bib/c_tools.php");
include_once($dir."/../../bib/c_database_pdo.php");
include_once($dir."/../../bib/c_date.php");

/**
 * Class c_cst_ibs_cbs
 * Gerencia os CST IBS/CBS do sistema
 */
Class c_cst_ibs_cbs extends c_user {
    
    private $id = NULL;
    private $cst = NULL;
    private $descricao = NULL;

    //construtor
    function __construct(){
    }

    //---------------------------------------------------------------
    // METODOS DE SETS E GETS
    //---------------------------------------------------------------
    
    public function setId($id){$this->id = $id;}
    public function getId(){return $this->id;}

    public function setCst($cst){$this->cst = strtoupper(trim($cst));}
    public function getCst(){return $this->cst;}

    public function setDescricao($descricao){$this->descricao = $descricao;}
    public function getDescricao(){return $this->descricao;}

    //############### FIM SETS E GETS ###############

    /**
     * @name existeCst
     * @description pesquisa se já existe o CST 
     */
    public function existeCst(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT COUNT(*) as total FROM EST_CST_IBS_CBS WHERE CST = :cst";
            $banco->prepare($sql);
            $banco->bindValue(':cst', $this->getCst());
            $banco->execute();
            $resultado = $banco->fetchAll();
            return ($resultado[0]['total'] > 0);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @name selectCstID
     * @description seleciona o CST pelo ID para alteração
     */
    public function selectCstID(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT * FROM EST_CST_IBS_CBS WHERE ID = :id";
            $banco->prepare($sql);
            $banco->bindValue(':id', $this->getId());
            $banco->execute();
            return $banco->fetchAll();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Funcao de consulta para todos os registros da tabela
     * @name selectCstGeral
     * @return ARRAY de todas as colunas da table
     */
    public function selectCstGeral(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT * FROM EST_CST_IBS_CBS ORDER BY CST ASC";
            $banco->prepare($sql);
            $banco->execute();
            return $banco->fetchAll();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * @name incluiCst
     * @description faz a inclusão de registro cadastrado
     */
    public function incluiCst(){
        try {
            $banco = new c_banco_pdo();
            $sql = "INSERT INTO EST_CST_IBS_CBS (CST, DESCRICAO, CREATED_USER) 
                    VALUES (:cst, :descricao, :createdUser)";
            
            $banco->prepare($sql);
            $banco->bindValue(':cst', $this->getCst());
            $banco->bindValue(':descricao', $this->getDescricao());
            $banco->bindValue(':createdUser', $this->m_userid);
            $banco->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @name alteraCst
     * @description altera registro existente
     */
    public function alteraCst(){
        try {
            $banco = new c_banco_pdo();
            $sql = "UPDATE EST_CST_IBS_CBS SET 
                    DESCRICAO = :descricao, 
                    UPDATED_USER = :updatedUser 
                    WHERE ID = :id";
            
            $banco->prepare($sql);
            $banco->bindValue(':descricao', $this->getDescricao());
            $banco->bindValue(':updatedUser', $this->m_userid);
            $banco->bindValue(':id', $this->getId());
            $banco->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @name getCstCombo
     * @description retorna array para combo de CST
     */
    public function getCstCombo(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT CST, DESCRICAO FROM EST_CST_IBS_CBS ORDER BY CST ASC";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $cst_ids[0] = '';
            $cst_names[0] = ' Selecione';

            if (is_array($result)) {
                for ($i = 0; $i < count($result); $i++) {
                    $cst_ids[$i + 1] = $result[$i]['CST'];
                    $cst_names[$i + 1] = $result[$i]['CST'] . " - " . $result[$i]['DESCRICAO'];
                }
            }

            return array(
                'ids' => $cst_ids,
                'names' => $cst_names
            );
        } catch (Exception $e) {
            return array('ids' => array(''), 'names' => array(' Selecione'));
        }
    }

}	//	END OF THE CLASS
?>
