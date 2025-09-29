<?php
/**
 * @package   astec
 * @name      c_nota_fiscal_produto_os
 * @version   2.0.00
 * @copyright 2013-2016 &copy; 
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      07/10/2015
*/
$dir = dirname(__FILE__);

include_once($dir."/../../bib/c_user.php");
include_once($dir."/../../bib/c_date.php");

/** <b> Classe NF Produto OS - Table: EST_NOTA_FISCAL_PRODUTO_OS </b> */
Class c_nota_fiscal_produto_os extends c_user {
/**
 * TABLE NAME EST_NOTA_FISCAL_PRODUTO_OS
 */
    
    
private $id                     = NULL; // int(11)
private $idNfEntrada            = NULL; // int(11)
private $codProduto             = NULL; // int(11)
private $doc                    = NULL; // int(11)
private $nsEntrada              = NULL; // varchar(30)
private $nfDevolucao            = NULL; // int(11)
private $aplicado               = NULL; // char(1)
private $idLote                 = NULL; // int(11)
private $centroCusto            = NULL; // varchar(10)
private $projeto                = NULL; // int(11)
private $idLoteTec              = NULL; // int(11)
private $localizacao            = NULL; // varchar(30)
private $userProduto            = NULL; // int(11)
private $devolucaoUserProduto   = NULL; // timestamp
private $obs                    = NULL; // blob
private $lote                   = NULL; // varchar(20)
private $dataValidade           = NULL; // date
private $referencia             = NULL; // varchar(5)

/**
* METODOS DE SETS E GETS
*/

function getLote() {
   return $this->lote;
}

function getDataValidade($format=NULL) {
    if (!empty($this->dataValidade)){
        switch ($format) {
            case 'F':
                    return date('d/m/Y', strtotime($this->dataValidade)); 
                    break;
            case 'B':
                    return c_date::convertDateBdSh($this->dataValidade, $this->m_banco);
                    break;
            default:
                    return $this->dataValidade;
        }
    }else{
        return '';
    }
    
}

function getReferencia() {
   return $this->referencia;
}

function setLote($lote) {
   $this->lote = $lote;
}

function setDataValidade($dataValidade) {
   $this->dataValidade = $dataValidade;
}

function setReferencia($referencia) {
   $this->referencia = $referencia;
}

public function getObs() {
    return $this->obs;
}

public function setObs($obs) {
    $this->obs = $obs;
}

public function getId() {
    return $this->id;
}

public function getIdNfEntrada() {
    return $this->idNfEntrada;
}

public function getCodProduto() {
    return $this->codProduto;
}

public function getDoc() {
    return $this->doc;
}

public function getNsEntrada() {
    return $this->nsEntrada;
}

public function getNfDevolucao() {
    return $this->nfDevolucao;
}

public function getAplicado() {
    return $this->aplicado;
}

public function getIdLote() {
    return $this->idLote;
}

public function getCentroCusto() {
    return $this->centroCusto;
}

public function getProjeto() {
    return $this->projeto;
}

public function getIdLoteTec() {
    return $this->idLoteTec;
}

public function getLocalizacao() {
    return $this->localizacao;
}

public function getUserProduto() {
    return $this->userProduto;
}

public function getDevolucaoUserProduto() {
    return $this->devolucaoUserProduto;
}

public function setId($id) {
    $this->id = $id;
}

public function setIdNfEntrada($idNfEntrada) {
    $this->idNfEntrada = $idNfEntrada;
}

public function setCodProduto($codProduto) {
    $this->codProduto = $codProduto;
}

public function setDoc($doc) {
    $this->doc = $doc;
}

public function setNsEntrada($nsEntrada) {
    $this->nsEntrada = $nsEntrada;
}

public function setNfDevolucao($nfDevolucao) {
    $this->nfDevolucao = $nfDevolucao;
}

public function setAplicado($aplicado) {
    $this->aplicado = $aplicado;
}

public function setIdLote($idLote) {
    $this->idLote = $idLote;
}

public function setCentroCusto($centroCusto) {
    $this->centroCusto = $centroCusto;
}

public function setProjeto($projeto) {
    $this->projeto = $projeto;
}

public function setIdLoteTec($idLoteTec) {
    $this->idLoteTec = $idLoteTec;
}

public function setLocalizacao($localizacao) {
    $this->localizacao = $localizacao;
}

public function setUserProduto($userProduto) {
    $this->userProduto = $userProduto;
}

public function setDevolucaoUserProduto($devolucaoUserProduto) {
    $this->devolucaoUserProduto = $devolucaoUserProduto;
}

/**
 * <b> Consulta os valores da NF produto OS e seta todos os atributos </b>
 * @name $nota_fiscal_produto_os
 * @param INT ID Chave primaria da tabela
 */
public function nota_fiscal_produto_os() {
        $lanc = $this->select_produto_os_id();
        $this->setId($lanc[0]['ID']);
        $this->setIdNfEntrada($lanc[0]['IDNFENTRADA']);
        $this->setCodProduto($lanc[0]['CODPRODUTO']);
        $this->setDoc($lanc[0]['DOC']);
        $this->setNsEntrada($lanc[0]['NSENTREGA']);
        $this->setNfDevolucao($lanc[0]['NFDEVOLUCAO']);
        $this->setAplicado($lanc[0]['APLICADO']);
        $this->setIdLote($lanc[0]['IDLOTE']);
        $this->setProjeto($lanc[0]['PROJETO']);
        $this->setCentroCusto($lanc[0]['CENTROCUSTO']);
        $this->setIdLoteTec($lanc[0]['IDLOTETEC']);
        $this->setLocalizacao($lanc[0]['LOCALIZACAO']);
        $this->setUserProduto($lanc[0]['USERPRODUTO']);
        $this->setDevolucaoUserProduto($lanc[0]['DEVOLUCAOUSERPRODUTO']);
        $this->setDataValidade($lanc[0]['DATAVALIDADE']);
        $this->setReferencia($lanc[0]['REFERENCIA']);
        $this->setObs($lanc[0]['OBS']);
}

    /**
     * <b> select utilizado: p_confere_produto_excel </b>
     * @name     select_nf_produto_geral
     * @param    INT Filial centrocusto
     */
    public function select_nf_produto_geral($filial) {
        $sql = "SELECT P.CODIGO, P.CODFABRICANTE, P.DESCRICAO, COUNT(O.ID) AS SALDO ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (P.CODIGO=O.CODPRODUTO) ";
        $sql .= "WHERE (O.IDLOTE='') and (o.centrocusto = '".$filial."') ";
        $sql .= "GROUP BY CODPRODUTO ORDER BY P.CODFABRICANTE; ";
       // echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os

    /**
     * <b> select utilizado: p_confere_produto_excel </b>
     * @name     select_nf_produto_codproduto
     * @param    INT Produto cod interno produto
     * @param    INT Filial centrocusto
     */
    public function select_nf_produto_codproduto($produto, $filial) {
        $sql = "SELECT * ";
        $sql .= "from EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO = P.CODIGO) ";
        $sql .= "WHERE (p.codfabricante like '%".$produto."') ";
        $sql .= "AND (o.centrocusto = '".$filial."'); ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os

    /**
     * <b> select utilizado: p_relatorio_gerencial </b>
     * @name     select_consolidacao_fiscal
     * @param    date dataInicio 
     * @param    date dataFim
     */
    public function select_nf_produto_imprime_etiqueta($nf, $tipo) {
        $sql = "SELECT N.NUMERO,N.EMISSAO,N.NATOPERACAO,  ";//F.DATACONFERENCIA,
        $sql .= "P.CODFABRICANTE, P.DESCRICAO,C.DESCRICAO AS CONTRATO, A.NUMCHAMADOSOLICITANTE, O.*, ";
        $sql .= "L.NUMLOTE, L.DATAENTREGA, L.NUMNF, U.NOMEREDUZIDO,I.CIDADE,I.UF ";
        $sql .= "from EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO = P.CODIGO) ";
        $sql .= "LEFT JOIN CAT_ATENDIMENTO A ON (A.ID = O.DOC) ";
        $sql .= "INNER JOIN EST_NOTA_FISCAL N ON (N.ID = O.IDNFENTRADA) ";
        //$sql .= "INNER JOIN EST_NOTA_FISCAL_PRODUTO F ON (F.IDNF = O.IDNFENTRADA) ";
        $sql .= "LEFT JOIN EST_LOTE L ON (L.ID = O.IDLOTE) ";
        $sql .= "LEFT JOIN CAT_CONTRATO C ON (C.NRCONTRATO = O.PROJETO) ";
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = O.USERPRODUTO) ";
        $sql .= "LEFT JOIN FIN_CLIENTE I ON (I.CLIENTE = A.CLIENTE) ";
        if ($tipo == 'notafiscal'){
            $sql .= "WHERE o.idnfentrada = '".$nf."' ";
        }elseif ($tipo == 'produto'){
            $sql .= "WHERE o.id in(".$nf.") ";
        }
        $sql .= "order by p.codfabricante; ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    /**
     * <b> select utilizado: p_relatorio_gerencial </b>
     * @name     select_consolidacao_fiscal
     * @param    date dataInicio 
     * @param    date dataFim
     */
    public function select_consolidacao_fiscal($letra) {
        $par = explode("|", $letra);
        $sql = "SELECT N.NUMERO,N.EMISSAO,N.NATOPERACAO,  ";//F.DATACONFERENCIA,
        $sql .= "P.CODFABRICANTE, P.DESCRICAO,C.DESCRICAO AS CONTRATO, A.NUMCHAMADOSOLICITANTE, O.*, ";
        $sql .= "L.NUMLOTE, L.DATAENTREGA, L.NUMNF, U.NOMEREDUZIDO ";
        $sql .= "from EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO = P.CODIGO) ";
        $sql .= "LEFT JOIN CAT_ATENDIMENTO A ON (A.ID = O.DOC) ";
        $sql .= "INNER JOIN EST_NOTA_FISCAL N ON (N.ID = O.IDNFENTRADA) ";
        //$sql .= "INNER JOIN EST_NOTA_FISCAL_PRODUTO F ON (F.IDNF = O.IDNFENTRADA) ";
        $sql .= "LEFT JOIN EST_LOTE L ON (L.ID = O.IDLOTE) ";
        $sql .= "LEFT JOIN CAT_CONTRATO C ON (C.NRCONTRATO = O.PROJETO) ";
        $sql .= "LEFT JOIN AMB_USUARIO U ON (U.USUARIO = O.USERPRODUTO) ";
        $sql .= "WHERE n.emissao >= '".$par[0]."' ";
        $sql .= "and n.emissao <= '".$par[1]."'; ";
       // echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    /**
     * @name     select_produto_os_idLote: p_lote
     * @param    INT getIdLote
     * @return   SELECT retorna todo conteudo da nf produto
     */
    public function select_produto_os_idLote() {
        $sql = "SELECT * ";
        $sql .= "from est_nota_fiscal_produto_os ";
        $sql .= "WHERE idLote = '".$this->getIdLote()."'; ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    /**
     * <b> Seleciona os dados da table NF_prod_os que estao aplicados. utilizado p_kardex </b>
     * @name     select_produto_os_aplicado
     * @param    INT codProduto
     * @param    INT filial
     * @return   SELECT retorna todo conteudo da nf produto
     */
    public function select_produto_os_aplicado($codProduto, $filial=null) {
        $sql = "SELECT * ";
        $sql .= "from est_nota_fiscal_produto_os ";
        $sql .= "WHERE (codProduto = '".$codProduto."') ";
        if ($filial != '0'){
            $sql .= " and (centrocusto = '".$filial."') ";
        }
        $sql .= "and (aplicado in ('s', 'd')) ";
        $sql .= "AND (((DEVOLUCAOUSERPRODUTO <> '0000-00-00 00:00:00') AND ((USERPRODUTO = '0') OR (USERPRODUTO=''))) ";
        $sql .= "OR ((DEVOLUCAOUSERPRODUTO = '0000-00-00 00:00:00') AND ((USERPRODUTO = '0') OR (USERPRODUTO='')))) ";
        $sql .= "and ((idlote='') or (idlote='0')); ";
       // echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os


    /**
     * @name     select_produto_os_id
     * @param    VAZIO
     * @return   SELECT retorna todo conteudo da nf produto
     */
    public function select_produto_os_id() {
        $sql = "SELECT * ";
        $sql .= "from est_nota_fiscal_produto_os ";
        $sql .= "WHERE id = '".$this->getId()."'; ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    /**
     * <b> Select utilizado no(s) formulario(s): p_distribui_os, p_tecnico_os </b>
     * @category CAT
     * @name     select_produto_os_doc
     * @param    INT DOC Variavel que contem o id do atendimento
     * @return   ARRAY retorna o select de acordo com o Id do atendimento
     */
    public function select_produto_os_doc($aplicado=null) {
        $sql = "SELECT O.*, P.*, n.numero FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "INNER JOIN EST_nota_fiscal n ON (o.idnfentrada=n.id) ";
        $sql .= "WHERE O.DOC = '".$this->getDoc()."' ";
        $sql .= "and (o.aplicado <> 'S') ";
        $sql .= "order by p.descricao; ";
        
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    /**
     * <b> Select utilizado no(s) formulario(s): p_distribui_os </b>
     * @category CAT
     * @name     select_produto_os_nome
     * @param    INT $produto Variavel que contem a descricao do produto
     * @return   ARRAY retorna o select
     */
    public function select_produto_os_nome($produto) {
        $sql = "SELECT O.*, P.*, n.numero FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "INNER JOIN EST_nota_fiscal n ON (o.idnfentrada=n.id) ";
        $sql .= "WHERE (p.descricao like '%".$produto."%') ";
        $sql .= "and (o.aplicado <> 'S') ";
        $sql .= "and (o.userproduto = '0') ";
        $sql .= "order by p.descricao; ";
        
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
 
    /**
     * <b> Select utilizado no(s) formulario(s): p_tecnico_os </b>
     * @category CAT
     * @name     select_produto_os_user_produto
     * @param    INT USERPRODUTO Variavel que contem o ID DO usuario
     * @return   ARRAY retorna o select de acordo com o Id do usuario
     */
    public function select_produto_os_user_produto() {
        $sql = "SELECT O.*, P.*, n.numero FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "INNER JOIN EST_nota_fiscal n ON (o.idnfentrada=n.id) ";
        $sql .= "WHERE O.userproduto = '".$this->getUserProduto()."' and (o.aplicado <>'s') ";
        $sql .= "order by p.descricao;";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
   
 
    /**
     * <b> Select utilizado no(s) formulario(s): p_tecnico_os </b>
     * @category EST
     * @name     select_nf_produto_os_user_produto_tranferenica
     * @param    INT TECNICO Variavel que contem o ID do tecnico
     * @return   ARRAY retorna o select de acordo com o Id do tecnico
     */
    public function select_nf_produto_os_user_produto_tranferenica($tecnico) {
        $sql = "SELECT O.*, P.*, n.numero ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "INNER JOIN EST_nota_fiscal n ON (o.idnfentrada=n.id) ";
        $sql .= "WHERE O.userproduto = '".$this->getUserProduto()."' and (o.aplicado <>'s'); ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_nf_produto_os_user_produto_tranferenica
   
    /**
     * @name     select_produto_os
     * @param    getDoc e getCodproduto Obrigatorios.
     * @return   SELECT retorna o maior numero do numero do lote
     */
    public function select_produto_nf_os() {
        $sql = "SELECT O.*, P.* ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "INNER JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "WHERE O.DOC = '".$this->getDoc()."' and ";
        $sql .= "O.codproduto = '".$this->getCodProduto()."'; ";
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    /**
     * <b> SQL usado na impressao do lote </b>
     * @name     select_produto_nf_os_lote_imprime
     * @param    INT lote Id do lote 
     * @return   SELECT retorna registros do lote
     */
    public function select_produto_nf_os_lote_imprime($lote) {
        $sql = "SELECT c.descricao as descprojeto, u.nomereduzido,a.numchamadosolicitante, n.numero, O.*, P.codfabricante,p.descricao ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "left JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "left JOIN cat_atendimento a ON (a.id=o.doc) ";
        $sql .= "left JOIN est_nota_fiscal n ON (n.id=o.idnfentrada) ";
        $sql .= "left JOIN amb_usuario u ON (u.usuario=o.userproduto) ";
        $sql .= "left JOIN est_lote l ON (l.id=o.idlotetec) ";
        $sql .= "left JOIN cat_contrato c ON (c.nrcontrato=o.projeto) ";
        $sql .= "WHERE (o.idlotetec = '".$lote."') ";
        $sql .= "ORDER BY P.DESCRICAO; ";
        
      //  echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    /**
     * <b> SQL para verificar as peças disponiveis para devolução. Utilizado: p_consulta_devolucao </b>
     * @name     select_produto_nf_os_consulta_devolucao
     * @param VARCHAR $letra Cod Fabricante | Descricao | Numero NF | localizacao | Classificar
     * @return   ARRAY SELECT retorna registros da table
     */
    public function select_produto_nf_os_consulta_devolucao($letra) {
        $par = explode("|", $letra);
        $sql = "SELECT c.descricao as descprojeto, u.nomereduzido,a.numchamadosolicitante, n.numero, O.*, P.codfabricante,p.descricao ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "left JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "left JOIN cat_atendimento a ON (a.id=o.doc) ";
        $sql .= "left JOIN est_nota_fiscal n ON (n.id=o.idnfentrada) ";
        $sql .= "left JOIN amb_usuario u ON (u.usuario=o.userproduto) ";
        $sql .= "left JOIN est_lote l ON (l.id=o.idlotetec) ";
        $sql .= "left JOIN cat_contrato c ON (c.nrcontrato=o.projeto) ";

        $sql .= "WHERE ((O.APLICADO = 'S') or (O.APLICADO = 'D')) ";// a peca tem que estar aplicado ou com defeito
        $sql .= "AND ((O.idlote = '0') OR (O.idlote IS NULL)) "; // nao ter sido devolvido
         // se a peca foi devolvida para empresa, o userproduto tem que ser 0 ou vazio
        $sql .= "AND (((O.DEVOLUCAOUSERPRODUTO <> '0000-00-00 00:00:00') AND ((O.USERPRODUTO = '0') OR (O.USERPRODUTO=''))) ";
        $sql .= "OR ((O.DEVOLUCAOUSERPRODUTO = '0000-00-00 00:00:00') AND ((O.USERPRODUTO = '0') OR (O.USERPRODUTO='')))) ";
        if ($par[0]!= ''){
            $sql .= "and (p.codfabricante like '%".$par[0]."') ";
        }
        if ($par[1]!= ''){
            $sql .= "and (p.descricao like '%".$par[1]."%') ";
        }
        if ($par[2]!= ''){
            $sql .= "and (n.numero = '".$par[2]."') ";
        }
        if ($par[3]!= ''){
            $sql .= "and (o.localizacao like '".$par[2]."%') ";
        }
        
        if ($par[4]== '0'){
            $sql .= "ORDER BY P.CODFABRICANTE; ";
        }else if ($par[4]== '1'){
            $sql .= "ORDER BY P.DESCRICAO; ";
        }else if ($par[4]== '2'){
            $sql .= "ORDER BY O.PROJETO; ";
        }else if ($par[4]== '3'){
            $sql .= "ORDER BY O.LOCALIZACAO; ";
        }else if ($par[4]== '4'){
            $sql .= "ORDER BY n.numero; ";
        }else{
            $sql .= "ORDER BY P.DESCRICAO; ";
        }
        
        
       // echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    /**
     * <b> SQL usado no lote tecnico </b>
     * @name     select_produto_nf_os_lote_nao_aplicado
     * @param    varchar aplicado
     * @param    varchar opcao parametro de pesquisa (codfabricante, descricao, numnf)
     * @return   SELECT retorna o maior numero do numero do lote
     */
    public function select_produto_nf_os_lote($aplicado, $opcao, $filial) {
        $par = explode("|", $opcao); // codfabricante|descricao|numnf
        $sql = "SELECT u.nomereduzido,a.numchamadosolicitante, n.numero, O.*, P.codfabricante,p.descricao ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "left JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "left JOIN cat_atendimento a ON (a.id=o.doc) ";
        $sql .= "left JOIN est_nota_fiscal n ON (n.id=o.idnfentrada) ";
        $sql .= "left JOIN amb_usuario u ON (u.usuario=o.userproduto) ";
        if ($aplicado == 'T'){//caso lote para tecnico
            $sql .= "WHERE ((O.idlotetec = '0') or (O.idlotetec is NULL)) "; // id do lote tecnico
            $sql .= "and ((O.APLICADO <> 'S') OR (O.APLICADO <> 'D') or (O.APLICADO IS NULL)) ";// a peca ser diferente de aplicado ou defeito
            $sql .= "and ((o.userproduto = '0') or (o.userproduto = ''))"; //nao estar em nome de outro tecnico
            $sql .= "AND ((O.idlote <> '0') OR (O.idlote IS NOT NULL)) "; // nao foi gerado lote de devolucao
        }elseif ($aplicado == 'A'){//pecas aplicadas
            $sql .= "WHERE ((O.APLICADO = 'S') or (O.APLICADO = 'D')) ";// a peca tem que estar aplicado ou com defeito
            $sql .= "AND ((O.idlote <> '0') OR (O.idlote IS NOT NULL)) ";
             // se a peca foi devolvida para empresa, o userproduto tem que ser 0 ou vazio
            $sql .= "AND (((O.DEVOLUCAOUSERPRODUTO <> '0000-00-00 00:00:00') AND ((O.USERPRODUTO = '0') OR (O.USERPRODUTO=''))) ";
            
            $sql .= "OR ((O.DEVOLUCAOUSERPRODUTO = '0000-00-00 00:00:00') AND ((O.USERPRODUTO = '0') OR (O.USERPRODUTO='')))) ";
        }elseif ($aplicado == 'N'){//pecas nao aplicadas
            $sql .= "WHERE ((O.APLICADO <> 'S') or (O.APLICADO IS NULL)) ";
            $sql .= "AND ((O.idlote = '0') OR (O.idlote IS NULL)) "; // nao ter sido devolvido
            $sql .= "AND (((O.DEVOLUCAOUSERPRODUTO <> '0000-00-00 00:00:00') AND ((O.USERPRODUTO = '0') OR (O.USERPRODUTO=''))) ";
            $sql .= "OR ((O.DEVOLUCAOUSERPRODUTO = '0000-00-00 00:00:00') AND ((O.USERPRODUTO = '0') OR (O.USERPRODUTO='')))) ";
        }else{
            
        }
        if($par[0] != ''){
            $sql .= "and (p.codfabricante like '%".$par[0]."') ";
        }
        if ($par[1] != ''){
            $sql .= "and (p.descricao like '%".$par[1]."%') ";
        }
        if($par[2] != ''){
            $sql .= "and (n.numero = '".$par[2]."') ";   
        }
        
        if ($filial != ''){
            $sql .= "and (o.centrocusto = '".$filial."') ";
        }
        
        $sql .= "ORDER BY P.DESCRICAO; ";
        
       // echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
   
    /**
     * <b> SQL usado: p_produto, P_PRODUTO_ESTOQUE </b>
     * @name     select_produto_nf_os_detalhes
     * @return   SELECT retorna o maior numero do numero do lote
     */
    public function select_produto_nf_os_detalhes($aplicado = null, $filial=null) {
        $sql  = "SELECT U.NOMEREDUZIDO, a.numchamadosolicitante, n.numero, O.*, p.codigo, p.descricao, p.codfabricante, o.localizacao as loc, c.descricao as descprojeto ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "LEFT JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "left JOIN cat_atendimento a ON (a.id=o.doc) ";
        $sql .= "LEFT JOIN est_nota_fiscal n ON (n.id=o.idnfentrada) ";
        $sql .= "LEFT JOIN cat_contrato c ON (c.nrcontrato=o.projeto) ";
        $sql .= "LEFT JOIN amb_usuario u ON (u.usuario=o.userproduto) ";
        $sql .= "WHERE ((O.idlote = '') or (O.idlote = '0')) and (o.codproduto = '".$this->getCodProduto()."') ";
        $sql .= "and (o.centrocusto = ".$filial.") ";
        if ($aplicado != NULL){
            $sql .= "and ((o.userproduto = '') OR (o.userproduto = '0'))";
            $sql .= "and (o.aplicado <> 's') ";
        }
        $sql .= "ORDER BY n.numero;";
        
      //  echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    
   
    /**
     * <b> SQL usado: p_nota_fiscal_produto_os </b>
     * @name  select_produto_nf_os_geral
     * @param STRING $letra variavel de pesquisa codigo|descricao|localizacao|nota fiscal|Classif
     * @param INT $filial Filial que esta logado
     * @return   SELECT retorna dados conforme sql
     */
    public function select_produto_nf_os_geral($letra, $filial) {
        $par = explode("|", $letra);
        /**
         * $letra:
         * [0] - codigo
         * [1] - descricao
         * [2] - localizacao
         * [3] - notafiscal
         * [4] - Classificacao para Order by
         */
        $sql  = "SELECT U.NOMEREDUZIDO, a.numchamadosolicitante, n.numero, O.*, p.codigo, p.descricao, p.codfabricante, o.localizacao as loc, c.descricao as descprojeto ";
        $sql .= "FROM EST_NOTA_FISCAL_PRODUTO_OS O ";
        $sql .= "LEFT JOIN EST_PRODUTO P ON (O.CODPRODUTO=P.CODIGO) ";
        $sql .= "left JOIN cat_atendimento a ON (a.id=o.doc) ";
        $sql .= "LEFT JOIN est_nota_fiscal n ON (n.id=o.idnfentrada) ";
        $sql .= "LEFT JOIN cat_contrato c ON (c.nrcontrato=o.projeto) ";
        $sql .= "LEFT JOIN amb_usuario u ON (u.usuario=o.userproduto) ";
        $sql .= "WHERE ((O.idlote = '') or (O.idlote = '0')) ";
        $sql .= "and (o.centrocusto = ".$filial.") ";
        //caso pesq tiver o codigo igunorar os outros filtros
        if ($par[0] != ''){
            $sql .= "and (p.codfabricante like '%".$par[0]."') ";
        }else{
           if ($par[1]!= '') {// descricao produto
               $sql .= "and (p.descricao like '%".$par[1]."%') ";
           }//if
           if ($par[2] != ''){// localizacao da peca
               $sql .= "and (o.localizacao like '%".$par[2]."%') ";
           }//if
           if ($par[3]!= ''){// numero da nota fiscal
               $sql .= "and (n.numero = '".$par[3]."') ";
           }//if
        }// if $par[0]
        
        $sql .= "ORDER BY {$par[4]};";
        
        //echo strtoupper($sql)."<BR>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }// fim select_produto_os
    
    


    /**
     * @name     incluiNFProdutoOs
     * @param    string gets de todos os objetos private da classe
     * @return   INSERT retorna VAZIO caso a insercao ocorra com sucesso
     */ 
    public function incluiNFProdutoOs() {
        $banco = new c_banco;
        $sql = "INSERT INTO EST_NOTA_FISCAL_PRODUTO_OS (";
        $sql .= " IDNFENTRADA, CODPRODUTO, DOC, NSENTREGA, NFDEVOLUCAO, APLICADO, IDLOTE, projeto, centrocusto,idlotetec, ";
        $sql .= "localizacao, userproduto, DEVOLUCAOUSERPRODUTO,DATAVALIDADE, REFERENCIA, OBS )";
        $sql .= "values ( ";
        $sql .= $this->getIdNfEntrada().", '".  $this->getCodProduto()."', '".  $this->getDoc()."', '";
        $sql .= $this->getNsEntrada()."', '";
        $sql .= $this->getNfDevolucao()."', '".  $this->getAplicado()."', '".  $this->getIdLote()."', '";
        $sql .= $this->getProjeto()."', '".$this->getCentroCusto()."', '".$this->getIdLoteTec()."', '";
        $sql .= $this->getLocalizacao()."', '".  $this->getUserProduto()."', '".  $this->getDevolucaoUserProduto()."', '";
        $sql .= $this->getDataValidade('B')."', '".  $this->getReferencia()."', '";
        $sql .= $this->getObs()."'); ";
       // echo strtoupper($sql) . "<BR>";
        $resProduto = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProduto > 0) {
            return '';
        } else {
            return 'Os dados do Item ' . $this->getId() . ' n&atilde;o foi cadastrado!';
        }//if
    }

    /**
     * @name     alteraNFProdutoOs
     * @param    string gets de todos objetos private da classe
     * @return   UPDATE retorna VAZIO caso o update ocorra com sucesso
     */
    public function alteraNFProdutoOs() {
        $sql = "UPDATE EST_NOTA_FISCAL_PRODUTO_OS ";
        $sql .= "SET  id = " . $this->getId() . ", ";
        $sql .= "idnfentrada = " . $this->getIdNfEntrada() . ", ";
        $sql .= "codproduto = " . $this->getCodProduto() . ", ";
        $sql .= "doc = " . $this->getDoc() . ", ";
        $sql .= "nsentrega = '" . $this->getNsEntrada() . "', ";
        $sql .= "nfdevolucao = '" . $this->getNfDevolucao() . "', ";
        $sql .= "aplicado = '" . $this->getAplicado() . "', ";
        $sql .= "idLote = '" . $this->getIdLote() . "', ";
        $sql .= "idLoteTec = '" . $this->getIdLoteTec() . "', ";
        $sql .= "localizacao = '" . $this->getLocalizacao() . "', ";
        $sql .= "userproduto = '" . $this->getUserProduto() . "', ";
        $sql .= "devolucaouserproduto = '" . $this->getDevolucaoUserProduto() . "', ";
        $sql .= "datavalidade = '" . $this->getDataValidade('B') . "', ";
        $sql .= "referencia = '" . $this->getReferencia() . "', ";
        $sql .= "obs = '" . $this->getObs() . "' ";
        $sql .= "WHERE (id = '" . $this->getId() . "');";
     // echo strtoupper($sql) . "<BR>";
        $banco = new c_banco;
        $resProdutoUser = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProdutoUser > 0) {
            return '';
        } else {
            return 'Os dados do produto ' . $this->getDesc() . ' n&atilde;o foi alterado!';
        }//if
    }
    
    /**
     * @name     alteraNFProdutoOs
     * @param    string gets de todos objetos private da classe
     * @return   UPDATE retorna VAZIO caso o update ocorra com sucesso
     */
    public function alteraNFProdutoOsSituacao() {
        $sql = "UPDATE EST_NOTA_FISCAL_PRODUTO_OS ";
        $sql .= "SET ";
        $sql .= "DOC = '" . $this->getDoc() . "', ";
        $sql .= "aplicado = '" . $this->getAplicado() . "' ";
        $sql .= "WHERE (ID = '" . $this->getId() . "'); ";
       //echo strtoupper($sql) . "<BR>";
        $banco = new c_banco;
        $resProdutoUser = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProdutoUser > 0) {
            return '';
        } else {
            return 'Os dados do produto ' . $this->getDesc() . ' n&atilde;o foi alterado!';
        }//if
    }
    /**
     * @name     alteraNFProdutoOsIdLoteTec
     * @param    string gets IdLoteTec e Id
     * @return   UPDATE retorna VAZIO caso o update ocorra com sucesso
     */
    public function alteraNFProdutoOsIdLoteTec() {
        $sql = "UPDATE EST_NOTA_FISCAL_PRODUTO_OS ";
        $sql .= "SET ";
        $sql .= "idlotetec = '" . $this->getIdLoteTec() . "' ";
        $sql .= "WHERE (id = '" . $this->getId() . "'); ";
       //echo strtoupper($sql) . "<BR>";
        $banco = new c_banco;
        $resProdutoUser = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProdutoUser > 0) {
            return '';
        } else {
            return 'Os dados do produto ' . $this->getDesc() . ' n&atilde;o foi alterado!';
        }//if
    }
    /**
     * @name     alteraNFProdutoOsNfDevolucao
     * @param    string gets NFDevolucao e IdLote
     * @return   UPDATE retorna VAZIO caso o update ocorra com sucesso
     */
    public function alteraNFProdutoOsNfDevolucao() {
        $sql = "UPDATE EST_NOTA_FISCAL_PRODUTO_OS ";
        $sql .= "SET ";
        $sql .= "nfdevolucao = '" . $this->getNfDevolucao() . "' ";
        $sql .= "WHERE (idLote = '" . $this->getIdLote() . "'); ";
      // echo strtoupper($sql) . "<BR>";
        $banco = new c_banco;
        $resProdutoUser = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProdutoUser > 0) {
            return '';
        } else {
            return 'Os dados do produto ' . $this->getDesc() . ' n&atilde;o foi alterado!';
        }//if
    }

     /**
     * @name     excluiNfProdutoOs
     * @param    id get objeto id private da classe
     * @return   DELETE retorna VAZIO caso o delete ocorra com sucesso
     */
    public function excluiNfProdutoOs() {
        $sql = "DELETE FROM EST_NOTA_FISCAL_PRODUTO_OS ";
        $sql .= "WHERE (id = '" . $this->getId() . "');";
        $banco = new c_banco;
        $resProdutoUser = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($resProdutoUser > 0) {
            return 'Exclus&atilde;o feita com sucesso.';
        } else {
            return 'Os dados do ProdutoUser ' . $this->getId() . ' n&atilde;o foi excluido!';
        }//if
    }

}
//	END OF THE CLASS
?>
