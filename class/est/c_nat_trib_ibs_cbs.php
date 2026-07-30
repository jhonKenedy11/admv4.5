<?php
/**
 * @package   adm4.5
 * @name      c_nat_trib_ibs_cbs
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva
 * @date      18/12/2025
 */

$dir = dirname(__FILE__);
include_once($dir."/../../bib/c_tools.php");
include_once($dir."/../../bib/c_database_pdo.php");
include_once($dir."/../../bib/c_user.php");

Class c_nat_trib_ibs_cbs extends c_user {

    private $id = NULL;
    private $idNatOp = NULL;
    private $ufDest = NULL;
    private $munDest = NULL;
    private $codMunDest = NULL;
    private $pessoa = NULL;
    private $cclasstrib = NULL;
    private $ncm = NULL;
    private $ibsUf = NULL;
    private $ibsMun = NULL;
    private $cbs = NULL;

    function __construct(){}

    // SETS E GETS
    public function setId($id){$this->id = $id;}
    public function getId(){return $this->id;}

    public function setIdNatOp($idNatOp){$this->idNatOp = $idNatOp;}
    public function getIdNatOp(){return $this->idNatOp;}

    public function setUfDest($ufDest){$this->ufDest = strtoupper($ufDest);}
    public function getUfDest(){return $this->ufDest;}

    public function setMunDest($munDest){$this->munDest = $munDest;}
    public function getMunDest(){return $this->munDest;}

    public function setCodMunDest($codMunDest){$this->codMunDest = $codMunDest;}
    public function getCodMunDest(){return $this->codMunDest;}

    public function setPessoa($pessoa){$this->pessoa = strtoupper($pessoa);}
    public function getPessoa(){return $this->pessoa;}

    public function setCclasstrib($cclasstrib){$this->cclasstrib = $cclasstrib;}
    public function getCclasstrib(){return $this->cclasstrib;}

    public function setNcm($ncm){$this->ncm = $ncm;}
    public function getNcm(){return $this->ncm;}

    public function setIbsUf($ibsUf){$this->ibsUf = $ibsUf;}
    public function getIbsUf($format = null) {
        if ($format=='F') {
            return number_format((float)$this->ibsUf, 2, ',', '.');
        } elseif ($format=='B') {
            $this->ibsUf = c_tools::moedaBd($this->ibsUf);
            return $this->ibsUf;	
        } else {
            return $this->ibsUf;
        }
    }

    public function setIbsMun($ibsMun){$this->ibsMun = $ibsMun;}
    public function getIbsMun($format = null) {
        if ($format=='F') {
            return number_format((float)$this->ibsMun, 2, ',', '.');
        } elseif ($format=='B') {
            $this->ibsMun = c_tools::moedaBd($this->ibsMun);
            return $this->ibsMun;	
        } else {
            return $this->ibsMun;
        }
    }

    public function setCbs($cbs){$this->cbs = $cbs;}
    public function getCbs($format = null) {
        if ($format=='F') {
            return number_format((float)$this->cbs, 2, ',', '.');
        } elseif ($format=='B') {
            $this->cbs = c_tools::moedaBd($this->cbs);
            return $this->cbs;	
        } else {
            return $this->cbs;
        }
    }

    // FIM GET E SET

    /**
     * Verifica se já existe registro
     */
    public function existeTribIbsCbs(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT COUNT(*) as total FROM EST_NATUREZA_OPERACAO_TRIBUTO_IBS_CBS 
                    WHERE ID_EST_NAT_OP = :idNatOp AND UF_DEST = :ufDest AND TIPO_PESSOA = :pessoa";
            
            if ($this->getCclasstrib() != '') {
                $sql .= " AND CCLASSTRIB = :cclasstrib";
            }
            if ($this->getNcm() != '') {
                $sql .= " AND NCM = :ncm";
            }
            
            $banco->prepare($sql);
            $banco->bindValue(':idNatOp', $this->getIdNatOp());
            $banco->bindValue(':ufDest', $this->getUfDest());
            $banco->bindValue(':pessoa', $this->getPessoa());
            
            if ($this->getCclasstrib() != '') {
                $banco->bindValue(':cclasstrib', $this->getCclasstrib());
            }
            if ($this->getNcm() != '') {
                $banco->bindValue(':ncm', $this->getNcm());
            }
            
            $banco->execute();
            $resultado = $banco->fetchAll();
            return ($resultado[0]['total'] > 0);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Seleciona tributos IBS/CBS de uma natureza de operação
     */
    public function selectTribIbsCbs(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT T.*, N.NATOPERACAO 
                    FROM EST_NATUREZA_OPERACAO_TRIBUTO_IBS_CBS T 
                    INNER JOIN EST_NAT_OP N ON (N.ID = T.ID_EST_NAT_OP) 
                    WHERE T.ID_EST_NAT_OP = :idNatOp 
                    ORDER BY UF_DEST, TIPO_PESSOA";
            
            $banco->prepare($sql);
            $banco->bindValue(':idNatOp', $this->getIdNatOp());
            $banco->execute();
            return $banco->fetchAll();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Seleciona tributo pelo ID
     */
    public function selectTribIbsCbsID(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT T.*, N.NATOPERACAO 
                    FROM EST_NATUREZA_OPERACAO_TRIBUTO_IBS_CBS T 
                    INNER JOIN EST_NAT_OP N ON (N.ID = T.ID_EST_NAT_OP) 
                    WHERE T.ID = :id";
            
            $banco->prepare($sql);
            $banco->bindValue(':id', $this->getId());
            $banco->execute();
            return $banco->fetchAll();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * Inclui novo tributo IBS/CBS
     */
    public function incluiTribIbsCbs(){
        try {
            $banco = new c_banco_pdo();
            $sql = "INSERT INTO EST_NATUREZA_OPERACAO_TRIBUTO_IBS_CBS 
                    (ID_EST_NAT_OP, UF_DEST, MUN_DEST, COD_MUN_DEST, TIPO_PESSOA, 
                     CCLASSTRIB, NCM, ALIQUOTA_IBS_UF, ALIQUOTA_IBS_MUN, ALIQUOTA_CBS, CREATED_USER) 
                    VALUES 
                    (:idNatOp, :ufDest, :munDest, :codMunDest, :pessoa, 
                     :cclasstrib, :ncm, :ibsUf, :ibsMun, :cbs, :createdUser)";

            $banco->prepare($sql);
            $banco->bindValue(':idNatOp', $this->getIdNatOp());
            $banco->bindValue(':ufDest', $this->getUfDest());
            $banco->bindValue(':munDest', $this->getMunDest());
            $banco->bindValue(':codMunDest', $this->getCodMunDest());
            $banco->bindValue(':pessoa', $this->getPessoa());
            $banco->bindValue(':cclasstrib', $this->getCclasstrib());
            $banco->bindValue(':ncm', $this->getNcm());
            $banco->bindValue(':ibsUf', $this->getIbsUf('B'));
            $banco->bindValue(':ibsMun', $this->getIbsMun('B'));
            $banco->bindValue(':cbs', $this->getCbs('B'));
            $banco->bindValue(':createdUser', $this->m_userid);
            $banco->execute();
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Altera tributo IBS/CBS existente
     */
    public function alteraTribIbsCbs(){
        try {
            $banco = new c_banco_pdo();
            $sql = "UPDATE EST_NATUREZA_OPERACAO_TRIBUTO_IBS_CBS SET 
                    UF_DEST = :ufDest, 
                    MUN_DEST = :munDest, 
                    COD_MUN_DEST = :codMunDest, 
                    TIPO_PESSOA = :pessoa, 
                    CCLASSTRIB = :cclasstrib, 
                    NCM = :ncm, 
                    ALIQUOTA_IBS_UF = :ibsUf, 
                    ALIQUOTA_IBS_MUN = :ibsMun, 
                    ALIQUOTA_CBS = :cbs, 
                    UPDATED_USER = :updatedUser 
                    WHERE ID = :id";

            $banco->prepare($sql);
            $banco->bindValue(':ufDest', $this->getUfDest());
            $banco->bindValue(':munDest', $this->getMunDest());
            $banco->bindValue(':codMunDest', $this->getCodMunDest());
            $banco->bindValue(':pessoa', $this->getPessoa());
            $banco->bindValue(':cclasstrib', $this->getCclasstrib());
            $banco->bindValue(':ncm', $this->getNcm());
            $banco->bindValue(':ibsUf', $this->getIbsUf('B'));
            $banco->bindValue(':ibsMun', $this->getIbsMun('B'));
            $banco->bindValue(':cbs', $this->getCbs('B'));
            $banco->bindValue(':updatedUser', $this->m_userid);
            $banco->bindValue(':id', $this->getId());
            $banco->execute();
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Exclui tributo IBS/CBS
     */
    public function excluiTribIbsCbs(){
        try {
            $banco = new c_banco_pdo();
            $sql = "DELETE FROM EST_NATUREZA_OPERACAO_TRIBUTO_IBS_CBS WHERE ID = :id";
            
            $banco->prepare($sql);
            $banco->bindValue(':id', $this->getId());
            $banco->execute();
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Retorna combo de CClassTrib
     */
    public function getCclasstribCombo(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT CCLASSTRIB, NOME FROM EST_CCLASS_TRIB ORDER BY CCLASSTRIB ASC";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids[0] = '';
            $names[0] = 'Selecione';

            if (is_array($result)) {
                for ($i = 0; $i < count($result); $i++) {
                    $ids[$i + 1] = $result[$i]['CCLASSTRIB'];
                    $names[$i + 1] = $result[$i]['CCLASSTRIB'] . " - " . $result[$i]['NOME'];
                }
            }

            return array('ids' => $ids, 'names' => $names);
        } catch (Exception $e) {
            return array('ids' => array(''), 'names' => array('Selecione'));
        }
    }

    /**
     * Retorna combo de NCM
     */
    public function getNcmCombo(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT NCM, DESCRICAO FROM EST_NCM ORDER BY NCM ASC";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids[0] = '';
            $names[0] = 'Selecione';

            if (is_array($result)) {
                for ($i = 0; $i < count($result); $i++) {
                    $ids[$i + 1] = $result[$i]['NCM'];
                    $names[$i + 1] = $result[$i]['NCM'] . " - " . $result[$i]['DESCRICAO'];
                }
            }

            return array('ids' => $ids, 'names' => $names);
        } catch (Exception $e) {
            return array('ids' => array(''), 'names' => array('Selecione'));
        }
    }

    /**
     * Retorna descrição da Natureza de Operação
     */
    public function getNatOperacaoDescricao(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT NATOPERACAO FROM EST_NAT_OP WHERE ID = :id";
            $banco->prepare($sql);
            $banco->bindValue(':id', $this->getIdNatOp());
            $banco->execute();
            $result = $banco->fetchAll();
            return isset($result[0]['NATOPERACAO']) ? $result[0]['NATOPERACAO'] : '';
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Retorna combo de UF
     */
    public function getUfCombo(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT tipo as ID, padrao as DESCRICAO FROM AMB_DDM WHERE (alias='FIN_MENU') AND (campo='Estado')";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids[0] = '';
            $names[0] = 'Selecione';

            if (is_array($result)) {
                for ($i = 0; $i < count($result); $i++) {
                    $ids[$i + 1] = $result[$i]['ID'];
                    $names[$i + 1] = $result[$i]['DESCRICAO'];
                }
            }

            return array('ids' => $ids, 'names' => $names);
        } catch (Exception $e) {
            return array('ids' => array(''), 'names' => array('Selecione'));
        }
    }

    /**
     * Retorna combo de Pessoa
     */
    public function getPessoaCombo(){ 
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT tipo as ID, padrao as DESCRICAO FROM AMB_DDM WHERE (alias='FIN_MENU') AND (campo='Pessoa')";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $ids[0] = '';
            $names[0] = 'Selecione';

            if (is_array($result)) {
                for ($i = 0; $i < count($result); $i++) {
                    $ids[$i + 1] = $result[$i]['ID'];
                    $names[$i + 1] = $result[$i]['DESCRICAO'];
                }
            }

            return array('ids' => $ids, 'names' => $names);
        } catch (Exception $e) {
            return array('ids' => array(''), 'names' => array('Selecione'));
        }
    }

    /** EST_NATUREZA_OPERACAO_TRIBUTO_IBS_CBS **/
    

}
?>
