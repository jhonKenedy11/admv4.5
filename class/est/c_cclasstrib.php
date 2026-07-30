<?php
/**
 * @package   adm4.5
 * @name      c_cclasstrib
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


Class c_cclasstrib extends c_user {

    /* Campos tabela EST_CCLASS_TRIB */
    private $id = NULL;
    private $cclasstrib = NULL;
    private $nome = NULL;
    private $descricao = NULL;
    private $cst = NULL;
    private $lc_redacao = NULL;
    private $lc_214_25 = NULL;
    private $regulamento_cbs = NULL;
    private $regulamento_ibs = NULL;
    private $tipo_aliquota = NULL;
    private $pred_ibs = NULL;
    private $pred_cbs = NULL;
    private $ind_g_trib_regular = NULL;
    private $ind_g_cred_pres_oper = NULL;
    private $ind_g_mono_padrao = NULL;
    private $ind_g_mono_reten = NULL;
    private $ind_g_mono_ret = NULL;
    private $ind_gp_bio_diferenca = NULL;
    private $ind_g_estorno_cred = NULL;
    private $tp_rbsn = NULL;
    private $d_ini_vig = NULL;
    private $d_fim_vig = NULL;
    private $data_atualizacao = NULL;
    private $ind_nfe_abi = NULL;
    private $ind_nfe = NULL;
    private $ind_nf_ce = NULL;
    private $ind_cte = NULL;
    private $ind_cte_os = NULL;
    private $ind_bpe = NULL;
    private $ind_bpe_ta = NULL;
    private $ind_bpe_tm = NULL;
    private $ind_nf_3e = NULL;
    private $ind_nfse = NULL;
    private $ind_nfse_via = NULL;
    private $ind_nf_com = NULL;
    private $ind_nf_ag = NULL;
    private $ind_nf_gas = NULL;
    private $ind_dere = NULL;
    private $ind_dir = NULL;
    private $ind_duimp = NULL;

    //construtor
    function __construct(){
    }

    //---------------------------------------------------------------
    // METODOS DE SETS E GETS
    //---------------------------------------------------------------
    
    public function setId($id){$this->id = $id;}
    public function getId(){return $this->id;}

    public function setCclasstrib($cclasstrib){$this->cclasstrib = strtoupper(trim($cclasstrib));}
    public function getCclasstrib(){return $this->cclasstrib;}

    public function setNome($nome){$this->nome = $nome;}
    public function getNome(){return $this->nome;}

    public function setDescricao($descricao){$this->descricao = $descricao;}
    public function getDescricao(){return $this->descricao;}

    public function setCst($cst){$this->cst = $cst;}
    public function getCst(){return $this->cst;}

    public function setLcRedacao($lc_redacao){$this->lc_redacao = $lc_redacao;}
    public function getLcRedacao(){return $this->lc_redacao;}

    public function setLc21425($lc_214_25){$this->lc_214_25 = $lc_214_25;}
    public function getLc21425(){return $this->lc_214_25;}

    public function setRegulamentoCbs($regulamento_cbs){$this->regulamento_cbs = $regulamento_cbs;}
    public function getRegulamentoCbs(){return $this->regulamento_cbs;}

    public function setRegulamentoIbs($regulamento_ibs){$this->regulamento_ibs = $regulamento_ibs;}
    public function getRegulamentoIbs(){return $this->regulamento_ibs;}

    public function setTipoAliquota($tipo_aliquota){$this->tipo_aliquota = $tipo_aliquota;}
    public function getTipoAliquota(){return $this->tipo_aliquota;}

    public function setPredIbs($pred_ibs){$this->pred_ibs = (int)$pred_ibs;}
    public function getPredIbs(){return $this->pred_ibs;}

    public function setPredCbs($pred_cbs){$this->pred_cbs = (int)$pred_cbs;}
    public function getPredCbs(){return $this->pred_cbs;}

    public function setIndGTribRegular($ind_g_trib_regular){$this->ind_g_trib_regular = (int)$ind_g_trib_regular;}
    public function getIndGTribRegular(){return $this->ind_g_trib_regular;}

    public function setIndGCredPresOper($ind_g_cred_pres_oper){$this->ind_g_cred_pres_oper = (int)$ind_g_cred_pres_oper;}
    public function getIndGCredPresOper(){return $this->ind_g_cred_pres_oper;}

    public function setIndGMonoPadrao($ind_g_mono_padrao){$this->ind_g_mono_padrao = (int)$ind_g_mono_padrao;}
    public function getIndGMonoPadrao(){return $this->ind_g_mono_padrao;}

    public function setIndGMonoReten($ind_g_mono_reten){$this->ind_g_mono_reten = (int)$ind_g_mono_reten;}
    public function getIndGMonoReten(){return $this->ind_g_mono_reten;}

    public function setIndGMonoRet($ind_g_mono_ret){$this->ind_g_mono_ret = (int)$ind_g_mono_ret;}
    public function getIndGMonoRet(){return $this->ind_g_mono_ret;}

    public function setIndGpBioDiferenca($ind_gp_bio_diferenca){$this->ind_gp_bio_diferenca = (int)$ind_gp_bio_diferenca;}
    public function getIndGpBioDiferenca(){return $this->ind_gp_bio_diferenca;}

    public function setIndGEstornoCred($ind_g_estorno_cred){$this->ind_g_estorno_cred = (int)$ind_g_estorno_cred;}
    public function getIndGEstornoCred(){return $this->ind_g_estorno_cred;}

    public function setTpRbsn($tp_rbsn){$this->tp_rbsn = (int)$tp_rbsn;}
    public function getTpRbsn(){return $this->tp_rbsn;}

    public function setDIniVig($d_ini_vig){$this->d_ini_vig = $d_ini_vig;}
    public function getDIniVig($format = null){
        $this->d_ini_vig = strtr($this->d_ini_vig, "/", "-");
        switch ($format) {
            case 'F':
                return $this->d_ini_vig ? date('Y-m-d', strtotime($this->d_ini_vig)) : '';
            case 'T':
                return $this->d_ini_vig ? date('d/m/Y', strtotime($this->d_ini_vig)) : '';
            case 'B':
                return c_date::convertDateBd($this->d_ini_vig, $this->m_banco);
            default:
                return $this->d_ini_vig;
        }
    }

    public function setDFimVig($d_fim_vig){$this->d_fim_vig = $d_fim_vig;}
    public function getDFimVig($format = null){
        $this->d_fim_vig = strtr($this->d_fim_vig, "/", "-");
        switch ($format) {
            case 'F':
                return $this->d_fim_vig ? date('Y-m-d', strtotime($this->d_fim_vig)) : '';
            case 'T':
                return $this->d_fim_vig ? date('d/m/Y', strtotime($this->d_fim_vig)) : '';
            case 'B':
                return c_date::convertDateBd($this->d_fim_vig, $this->m_banco);
            default:
                return $this->d_fim_vig;
        }
    }

    public function setDataAtualizacao($data_atualizacao){$this->data_atualizacao = $data_atualizacao;}
    public function getDataAtualizacao($format = null){
        $this->data_atualizacao = strtr($this->data_atualizacao, "/", "-");
        switch ($format) {
            case 'F':
                return $this->data_atualizacao ? date('Y-m-d', strtotime($this->data_atualizacao)) : '';
            case 'T':
                return $this->data_atualizacao ? date('d/m/Y', strtotime($this->data_atualizacao)) : '';
            case 'B':
                return c_date::convertDateBd($this->data_atualizacao, $this->m_banco);
            default:
                return $this->data_atualizacao;
        }
    }

    public function setIndNfeAbi($ind_nfe_abi){$this->ind_nfe_abi = (int)$ind_nfe_abi;}
    public function getIndNfeAbi(){return $this->ind_nfe_abi;}

    public function setIndNfe($ind_nfe){$this->ind_nfe = (int)$ind_nfe;}
    public function getIndNfe(){return $this->ind_nfe;}

    public function setIndNfCe($ind_nf_ce){$this->ind_nf_ce = (int)$ind_nf_ce;}
    public function getIndNfCe(){return $this->ind_nf_ce;}

    public function setIndCte($ind_cte){$this->ind_cte = (int)$ind_cte;}
    public function getIndCte(){return $this->ind_cte;}

    public function setIndCteOs($ind_cte_os){$this->ind_cte_os = (int)$ind_cte_os;}
    public function getIndCteOs(){return $this->ind_cte_os;}

    public function setIndBpe($ind_bpe){$this->ind_bpe = (int)$ind_bpe;}
    public function getIndBpe(){return $this->ind_bpe;}

    public function setIndBpeTa($ind_bpe_ta){$this->ind_bpe_ta = (int)$ind_bpe_ta;}
    public function getIndBpeTa(){return $this->ind_bpe_ta;}

    public function setIndBpeTm($ind_bpe_tm){$this->ind_bpe_tm = (int)$ind_bpe_tm;}
    public function getIndBpeTm(){return $this->ind_bpe_tm;}

    public function setIndNf3e($ind_nf_3e){$this->ind_nf_3e = (int)$ind_nf_3e;}
    public function getIndNf3e(){return $this->ind_nf_3e;}

    public function setIndNfse($ind_nfse){$this->ind_nfse = (int)$ind_nfse;}
    public function getIndNfse(){return $this->ind_nfse;}

    public function setIndNfseVia($ind_nfse_via){$this->ind_nfse_via = (int)$ind_nfse_via;}
    public function getIndNfseVia(){return $this->ind_nfse_via;}

    public function setIndNfCom($ind_nf_com){$this->ind_nf_com = (int)$ind_nf_com;}
    public function getIndNfCom(){return $this->ind_nf_com;}

    public function setIndNfAg($ind_nf_ag){$this->ind_nf_ag = (int)$ind_nf_ag;}
    public function getIndNfAg(){return $this->ind_nf_ag;}

    public function setIndNfGas($ind_nf_gas){$this->ind_nf_gas = (int)$ind_nf_gas;}
    public function getIndNfGas(){return $this->ind_nf_gas;}

    public function setIndDere($ind_dere){$this->ind_dere = (int)$ind_dere;}
    public function getIndDere(){return $this->ind_dere;}

    public function setIndDir($ind_dir){$this->ind_dir = (int)$ind_dir;}
    public function getIndDir(){return $this->ind_dir;}

    public function setIndDuimp($ind_duimp){$this->ind_duimp = (int)$ind_duimp;}
    public function getIndDuimp(){return $this->ind_duimp;}

    //############### FIM SETS E GETS ###############

    /**
     * @name existeCclasstrib
     * @description pesquisa se já existe o CClasstrib 
     */
    public function existeCclasstrib(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT COUNT(*) as total FROM EST_CCLASS_TRIB WHERE CCLASSTRIB = :cclasstrib";
            $banco->prepare($sql);
            $banco->bindValue(':cclasstrib', $this->getCclasstrib());
            $banco->execute();
            $resultado = $banco->fetchAll();
            return ($resultado[0]['total'] > 0);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @name selectCclasstribID
     * @description seleciona o CClasstrib pelo ID para alteração
     */
    public function selectCclasstribID(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT * FROM EST_CCLASS_TRIB WHERE ID = :id";
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
     * @name selectCclasstribGeral
     * @return ARRAY de todas as colunas da table
     */
    public function selectCclasstribGeral(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT C.*, S.DESCRICAO AS CST_DESCRICAO 
                    FROM EST_CCLASS_TRIB C 
                    LEFT JOIN EST_CST_IBS_CBS S ON C.CST = S.CST 
                    ORDER BY C.CCLASSTRIB ASC";
            $banco->prepare($sql);
            $banco->execute();
            return $banco->fetchAll();
        } catch (Exception $e) {
            return array();
        }
    }

    /**
     * @name incluiCclasstrib
     * @description faz a inclusão de registro cadastrado
     */
    public function incluiCclasstrib(){
        try {
            $banco = new c_banco_pdo();
            $sql = "INSERT INTO EST_CCLASS_TRIB (
                    CCLASSTRIB, NOME, DESCRICAO, CST, LC_REDACAO, LC_214_25,
                    REGULAMENTO_CBS, REGULAMENTO_IBS, TIPO_ALIQUOTA,
                    PRED_IBS, PRED_CBS, IND_G_TRIB_REGULAR, IND_G_CRED_PRES_OPER,
                    IND_G_MONO_PADRAO, IND_G_MONO_RETEN, IND_G_MONO_RET,
                    IND_GP_BIO_DIFERENCA, IND_G_ESTORNO_CRED, TP_RBSN,
                    D_INI_VIG, D_FIM_VIG, DATA_ATUALIZACAO,
                    IND_NFE_ABI, IND_NFE, IND_NF_CE, IND_CTE, IND_CTE_OS,
                    IND_BPE, IND_BPE_TA, IND_BPE_TM, IND_NF_3E, IND_NFSE,
                    IND_NFSE_VIA, IND_NF_COM, IND_NF_AG, IND_NF_GAS, IND_DERE,
                    IND_DIR, IND_DUIMP, CREATED_USER
                    ) VALUES (
                    :cclasstrib, :nome, :descricao, :cst, :lcRedacao, :lc21425,
                    :regulamentoCbs, :regulamentoIbs, :tipoAliquota,
                    :predIbs, :predCbs, :indGTribRegular, :indGCredPresOper,
                    :indGMonoPadrao, :indGMonoReten, :indGMonoRet,
                    :indGpBioDiferenca, :indGEstornoCred, :tpRbsn,
                    :dIniVig, :dFimVig, :dataAtualizacao,
                    :indNfeAbi, :indNfe, :indNfCe, :indCte, :indCteOs,
                    :indBpe, :indBpeTa, :indBpeTm, :indNf3e, :indNfse,
                    :indNfseVia, :indNfCom, :indNfAg, :indNfGas, :indDere,
                    :indDir, :indDuimp, :createdUser
                    )";
            
            $banco->prepare($sql);
            $banco->bindValue(':cclasstrib', $this->getCclasstrib());
            $banco->bindValue(':nome', $this->getNome());
            $banco->bindValue(':descricao', $this->getDescricao());
            $banco->bindValue(':cst', $this->getCst() ?: null);
            $banco->bindValue(':lcRedacao', $this->getLcRedacao());
            $banco->bindValue(':lc21425', $this->getLc21425());
            $banco->bindValue(':regulamentoCbs', $this->getRegulamentoCbs());
            $banco->bindValue(':regulamentoIbs', $this->getRegulamentoIbs());
            $banco->bindValue(':tipoAliquota', $this->getTipoAliquota());
            $banco->bindValue(':predIbs', (int)$this->getPredIbs());
            $banco->bindValue(':predCbs', (int)$this->getPredCbs());
            $banco->bindValue(':indGTribRegular', (int)$this->getIndGTribRegular());
            $banco->bindValue(':indGCredPresOper', (int)$this->getIndGCredPresOper());
            $banco->bindValue(':indGMonoPadrao', (int)$this->getIndGMonoPadrao());
            $banco->bindValue(':indGMonoReten', (int)$this->getIndGMonoReten());
            $banco->bindValue(':indGMonoRet', (int)$this->getIndGMonoRet());
            $banco->bindValue(':indGpBioDiferenca', (int)$this->getIndGpBioDiferenca());
            $banco->bindValue(':indGEstornoCred', (int)$this->getIndGEstornoCred());
            $banco->bindValue(':tpRbsn', (int)$this->getTpRbsn());
            $banco->bindValue(':dIniVig', $this->getDIniVig('F') ?: null);
            $banco->bindValue(':dFimVig', $this->getDFimVig('F') ?: null);
            $banco->bindValue(':dataAtualizacao', $this->getDataAtualizacao('F') ?: null);
            $banco->bindValue(':indNfeAbi', (int)$this->getIndNfeAbi());
            $banco->bindValue(':indNfe', (int)$this->getIndNfe());
            $banco->bindValue(':indNfCe', (int)$this->getIndNfCe());
            $banco->bindValue(':indCte', (int)$this->getIndCte());
            $banco->bindValue(':indCteOs', (int)$this->getIndCteOs());
            $banco->bindValue(':indBpe', (int)$this->getIndBpe());
            $banco->bindValue(':indBpeTa', (int)$this->getIndBpeTa());
            $banco->bindValue(':indBpeTm', (int)$this->getIndBpeTm());
            $banco->bindValue(':indNf3e', (int)$this->getIndNf3e());
            $banco->bindValue(':indNfse', (int)$this->getIndNfse());
            $banco->bindValue(':indNfseVia', (int)$this->getIndNfseVia());
            $banco->bindValue(':indNfCom', (int)$this->getIndNfCom());
            $banco->bindValue(':indNfAg', (int)$this->getIndNfAg());
            $banco->bindValue(':indNfGas', (int)$this->getIndNfGas());
            $banco->bindValue(':indDere', (int)$this->getIndDere());
            $banco->bindValue(':indDir', (int)$this->getIndDir());
            $banco->bindValue(':indDuimp', (int)$this->getIndDuimp());
            $banco->bindValue(':createdUser', $this->m_userid);
            $banco->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @name alteraCclasstrib
     * @description altera registro existente
     */
    public function alteraCclasstrib(){
        try {
            $banco = new c_banco_pdo();
            $sql = "UPDATE EST_CCLASS_TRIB SET 
                    NOME = :nome,
                    DESCRICAO = :descricao,
                    CST = :cst,
                    LC_REDACAO = :lcRedacao,
                    LC_214_25 = :lc21425,
                    REGULAMENTO_CBS = :regulamentoCbs,
                    REGULAMENTO_IBS = :regulamentoIbs,
                    TIPO_ALIQUOTA = :tipoAliquota,
                    PRED_IBS = :predIbs,
                    PRED_CBS = :predCbs,
                    IND_G_TRIB_REGULAR = :indGTribRegular,
                    IND_G_CRED_PRES_OPER = :indGCredPresOper,
                    IND_G_MONO_PADRAO = :indGMonoPadrao,
                    IND_G_MONO_RETEN = :indGMonoReten,
                    IND_G_MONO_RET = :indGMonoRet,
                    IND_GP_BIO_DIFERENCA = :indGpBioDiferenca,
                    IND_G_ESTORNO_CRED = :indGEstornoCred,
                    TP_RBSN = :tpRbsn,
                    D_INI_VIG = :dIniVig,
                    D_FIM_VIG = :dFimVig,
                    DATA_ATUALIZACAO = :dataAtualizacao,
                    IND_NFE_ABI = :indNfeAbi,
                    IND_NFE = :indNfe,
                    IND_NF_CE = :indNfCe,
                    IND_CTE = :indCte,
                    IND_CTE_OS = :indCteOs,
                    IND_BPE = :indBpe,
                    IND_BPE_TA = :indBpeTa,
                    IND_BPE_TM = :indBpeTm,
                    IND_NF_3E = :indNf3e,
                    IND_NFSE = :indNfse,
                    IND_NFSE_VIA = :indNfseVia,
                    IND_NF_COM = :indNfCom,
                    IND_NF_AG = :indNfAg,
                    IND_NF_GAS = :indNfGas,
                    IND_DERE = :indDere,
                    IND_DIR = :indDir,
                    IND_DUIMP = :indDuimp,
                    UPDATED_USER = :updatedUser
                    WHERE ID = :id";
            
            $banco->prepare($sql);
            $banco->bindValue(':nome', $this->getNome());
            $banco->bindValue(':descricao', $this->getDescricao());
            $banco->bindValue(':cst', $this->getCst() ?: null);
            $banco->bindValue(':lcRedacao', $this->getLcRedacao());
            $banco->bindValue(':lc21425', $this->getLc21425());
            $banco->bindValue(':regulamentoCbs', $this->getRegulamentoCbs());
            $banco->bindValue(':regulamentoIbs', $this->getRegulamentoIbs());
            $banco->bindValue(':tipoAliquota', $this->getTipoAliquota());
            $banco->bindValue(':predIbs', (int)$this->getPredIbs());
            $banco->bindValue(':predCbs', (int)$this->getPredCbs());
            $banco->bindValue(':indGTribRegular', (int)$this->getIndGTribRegular());
            $banco->bindValue(':indGCredPresOper', (int)$this->getIndGCredPresOper());
            $banco->bindValue(':indGMonoPadrao', (int)$this->getIndGMonoPadrao());
            $banco->bindValue(':indGMonoReten', (int)$this->getIndGMonoReten());
            $banco->bindValue(':indGMonoRet', (int)$this->getIndGMonoRet());
            $banco->bindValue(':indGpBioDiferenca', (int)$this->getIndGpBioDiferenca());
            $banco->bindValue(':indGEstornoCred', (int)$this->getIndGEstornoCred());
            $banco->bindValue(':tpRbsn', (int)$this->getTpRbsn());
            $banco->bindValue(':dIniVig', $this->getDIniVig('F') ?: null);
            $banco->bindValue(':dFimVig', $this->getDFimVig('F') ?: null);
            $banco->bindValue(':dataAtualizacao', $this->getDataAtualizacao('F') ?: null);
            $banco->bindValue(':indNfeAbi', (int)$this->getIndNfeAbi());
            $banco->bindValue(':indNfe', (int)$this->getIndNfe());
            $banco->bindValue(':indNfCe', (int)$this->getIndNfCe());
            $banco->bindValue(':indCte', (int)$this->getIndCte());
            $banco->bindValue(':indCteOs', (int)$this->getIndCteOs());
            $banco->bindValue(':indBpe', (int)$this->getIndBpe());
            $banco->bindValue(':indBpeTa', (int)$this->getIndBpeTa());
            $banco->bindValue(':indBpeTm', (int)$this->getIndBpeTm());
            $banco->bindValue(':indNf3e', (int)$this->getIndNf3e());
            $banco->bindValue(':indNfse', (int)$this->getIndNfse());
            $banco->bindValue(':indNfseVia', (int)$this->getIndNfseVia());
            $banco->bindValue(':indNfCom', (int)$this->getIndNfCom());
            $banco->bindValue(':indNfAg', (int)$this->getIndNfAg());
            $banco->bindValue(':indNfGas', (int)$this->getIndNfGas());
            $banco->bindValue(':indDere', (int)$this->getIndDere());
            $banco->bindValue(':indDir', (int)$this->getIndDir());
            $banco->bindValue(':indDuimp', (int)$this->getIndDuimp());
            $banco->bindValue(':updatedUser', $this->m_userid);
            $banco->bindValue(':id', $this->getId());
            $banco->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @name getCclasstribCombo
     * @description retorna array para combo de CClasstrib
     */
    public function getCclasstribCombo(){
        try {
            $banco = new c_banco_pdo();
            $sql = "SELECT ID, CCLASSTRIB FROM EST_CCLASS_TRIB ORDER BY CCLASSTRIB ASC";
            $banco->prepare($sql);
            $banco->execute();
            $result = $banco->fetchAll();

            $cclasstrib_ids[0] = '';
            $cclasstrib_names[0] = ' Selecione';

            if (is_array($result)) {
                for ($i = 0; $i < count($result); $i++) {
                    $cclasstrib_ids[$i + 1] = $result[$i]['ID'];
                    $cclasstrib_names[$i + 1] = $result[$i]['CCLASSTRIB'];
                }
            }

            return array(
                'ids' => $cclasstrib_ids,
                'names' => $cclasstrib_names
            );
        } catch (Exception $e) {
            return array('ids' => array(''), 'names' => array(' Selecione'));
        }
    }

    /**
     * @name getCstCombo
     * @description retorna array para combo de CST (para uso no cadastro de CClasstrib)
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
