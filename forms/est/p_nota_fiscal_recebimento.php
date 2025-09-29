<?php
/**
 * @package   astec
 * @name      p_nota_fiscal_recebimento
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/03/2017
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_produto.php");
require_once($dir."/../../class/est/c_produto_estoque.php");

//Class P_Nota_Fiscal
Class p_nota_fiscal_recebimento extends c_nota_fiscal_produto {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_opcao = NULL;
    private $m_pesq = null;

    public $smarty = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * @param VARCHAR $opcao
     * @param VARCHAR $pesquisa
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = $parmPost['submenu'];
        $this->m_opcao = $parmPost['opcao'];
        $this->m_pesquisa = $parmPost['pesquisa'];
        $this->m_letra = $parmPost['letra'];
        $this->m_par = explode("|", $this->m_letra);

        // variaveis form
        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : "");
        $this->setCodProduto(isset($parmPost['codProduto']) ? $parmPost['codProduto'] : "");
        $this->setIdNfEntrada(isset($parmPost['idnf']) ? $parmPost['idnf'] : "");
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : "");
        $this->setStatus(isset($parmPost['status']) ? $parmPost['status'] : "");
        $this->setFabLote(isset($parmPost['fabLote']) ? $parmPost['fabLote'] : "");
        $this->setDataValidade(isset($parmPost['dataValidade']) ? $parmPost['dataValidade'] : "");
        $this->setDataFabricacao(isset($parmPost['dataFabricacao']) ? $parmPost['dataFabricacao'] : "");
        $this->setNsEntrada(isset($parmPost['ns']) ? $parmPost['ns'] : "");
        $this->setLocalizacao(isset($parmPost['localizacao']) ? $parmPost['localizacao'] : "");
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : "");

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('admClass', ADMclass);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Nota Fiscal Recebimento de Produtos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8,9,10,11,12]"); 
        $this->smarty->assign('disableSort', "[ 12 ]"); 
        $this->smarty->assign('numLine', "25"); 
                

        // include do javascript
        // include ADMjs . "/est/s_nota_fiscal_produto.js";
    }


//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'baixar':

                $objNfProd = new c_nota_fiscal_produto();
                $objNfProd->setIdNf($this->getIdNfEntrada());
                $objNfProd->setNotaFiscalProduto();
                if ($objNfProd->existeDataConferencia()) {
                    $this->m_opcao = "receber";
                    $this->mostraNotaFiscalProduto("PRODUTO JA RECEBIDO - " . $this->getDescProduto());
                } else {
                    $this->desenhaBaixaNotaFiscalProduto('');
                }

                break;

            case 'baixa':
                try {
                    //altera a data da conferencia do nf_produto
                    $mensagem = $this->alteraDataConferencia();
                    if (intval($numNf)==0):
                        $result = false;
                        throw new Exception( $this->m_msg );
                    endif;

                    //cadastro na table est_nf_produto_os produtio um a um
                    $this->setNotaFiscalProduto();
                    $objEstProduto = new c_estoque_produto();
                    for ($i = 0; $i < $this->getQuant(); $i++) {
                        $objEstProduto->setIdNfEntrada($this->getIdNf());
                        $objEstProduto->setCodProduto($this->getCodProduto());
                        $objEstProduto->setStatus('');
                        $objEstProduto->setCentroCusto($this->m_empresacentrocusto);
                        $objEstProduto->setLocalizacao($this->getLocalizacao());
                        $objEstProduto->setNsEntrada($this->getNumSerie());
                        $objEstProduto->setAplicado('');
                        $objEstProduto->setFabLote('');
                        if (($ordemServico[0]['ID'] != '0') || ($ordemServico[0]['ID'] != '')) {
                            if (($ordemServico[0]['SITUACAO'] == '1') || ($ordemServico[0]['SITUACAO'] == '2') || ($ordemServico[0]['SITUACAO'] == '3')){
                                $objEstProduto->setDoc('');
                            }  else {
                                $objEstProduto->setDoc($ordemServico[0]['ID']);
                            }

                        } else {
                            $objEstProduto->setDoc('');
                                                 
                        } // if tem ordem de servico
                        $objEstProduto->incluiNFProdutoOs();
                    }//for
                    
                } catch (Exception $ex) {
                    
                }
                $this->m_opcao = "recebimento"; //ao receber um produto, continuar recebendo os prod. da  NF

                    $this->mostraNotaFiscalProduto($mensagem);

                break;
            default:
                //if ($this->verificaDireitoUsuario('CatSituacaoAtendimento', 'C')){
                $this->mostraNotaFiscalProduto(''); //}
        }
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
    function desenhaBaixaNotaFiscalProduto($mensagem) {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);


        $nf = new c_nota_fiscal();
        $nf->setId($this->getIdNfEntrada());
        $nf->setNotaFiscal();

        $produto = new c_produto();
        $produto->setId($this->getCodProduto());
        $reg_prod = $produto->select_produto();

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idnf', $this->getIdNfEntrada());
        $this->smarty->assign('modelo', $nf->getModelo());
        $this->smarty->assign('serie', $nf->getSerie());
        $this->smarty->assign('numero', $nf->getNumero());
        $this->smarty->assign('pessoa', $nf->getPessoa());
        $this->smarty->assign('pessoaNome', $nf->getnomePessoa());
//        $this->smarty->assign('quant', $nf->getQuant('F'));
//        $this->smarty->assign('unitario', $nf->getUnitario('F'));

        $this->smarty->assign('codProduto', "'" . $this->getCodProduto() . "'");
//        $this->smarty->assign('descProduto', "'" . $this->getDescProduto() . "'");
        $this->smarty->assign('projeto', $this->getProjeto());
//        $this->smarty->assign('ordem', "'" . $this->getOrdem() . "'");
//        $this->smarty->assign('dataConferencia', "'" . $this->getDataConferencia('F') . "'");
//        $this->smarty->assign('localizacao', $reg_prod[0]['LOCALIZACAO']);


        //----------------------------------------------
        // filial
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        $this->smarty->assign('filial_id', $nf->getCentroCusto());

        // tipo
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TipoNotaFiscal')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $tipo_ids[$i] = $result[$i]['ID'];
            $tipo_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipo_ids', $tipo_ids);
        $this->smarty->assign('tipo_names', $tipo_names);
        $this->smarty->assign('tipo_id', $nf->gettipo());

        //-----------------------------------------------------------------------
        //sql para mostrar a situacao no combobox
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='SituacaoNota')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids', $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        $this->smarty->assign('situacao_id', $nf->getsituacao());

        //-----------------------------------------------------------------------
        //consulta ordem de servi�o para verificar o projeto
/*        $consulta = new c_banco();
        $sql = "SELECT tipoatendimento FROM cat_atendimento";
        $sql .= " WHERE numchamadosolicitante = '" . $this->getOrdem() . "'";
        //echo $sql;
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result_projeto_atend = $consulta->resultado;
*/
        //-----------------------------------------------------------------------
        //sql para mostrar projeto


        $consulta = new c_banco();
        $sql = "SELECT nrcontrato, descricao FROM cat_contrato where situacao='a' ";
        $sql .= "ORDER BY descricao";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $projeto_ids[0] = '';
        $projeto_names[0] = 'Sem cliente';
        for ($i = 0; $i < count($result); $i++) {
            $projeto_ids[$i+1] = $result[$i]['NRCONTRATO'];
            $projeto_names[$i+1] = $result[$i]['NRCONTRATO'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('projeto_ids', $projeto_ids);
        $this->smarty->assign('projeto_names', $projeto_names);
        $this->smarty->assign('projeto_id', $result_projeto_atend[0]['TIPOATENDIMENTO']);



        $this->smarty->display('nota_fiscal_produto_baixa.tpl');
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraNotaFiscalProduto($mensagem = null  ) {


        $nf = new c_nota_fiscal();
        $nf->setId($this->getIdNfEntrada());
        $nf->setNotaFiscal();
        //$this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idnf', $this->getIdNfEntrada());
        $this->smarty->assign('modelo', $nf->getModelo());
        $this->smarty->assign('serie', $nf->getSerie());
        $this->smarty->assign('numero', $nf->getNumero());
        $this->smarty->assign('pessoa', $nf->getPessoa());
        $this->smarty->assign('pessoaNome', $nf->getnomePessoa());
        $this->smarty->assign('natOperacao_name', "'".$nf->getNatOperacao()."'");
        $this->smarty->assign('totalnf', $nf->getTotalnf('F'));
        $this->smarty->assign('emissao', $nf->getEmissao('F'));
        

        
        
        $nfProd = new c_nota_fiscal_produto();
        $nfProd->setIdNf($this->getIdNfEntrada());
        $lancProd = $nfProd->select_nota_fiscal_produto_nf();

        // VERIFICA SE NF ESTA ABERTA E FAZ O BAIXA NA NOTA

/*        if (($nf->getSituacao() == 'A') and ( $this->m_opcao == 'recebimento')) {
            $situacao = 'B';
            for ($i = 0; $i < count($lanc); $i++) {
                if (($lanc[$i]['DATACONFERENCIA'] == '0000-00-00 00:00:00') || ($lanc[$i]['DATACONFERENCIA'] == NULL)) {
                    $situacao = 'A';
                } //if
            } //for
            if ($situacao == 'B') {
                $nf->setSituacao($situacao);
                $nf->setDataConferencia($this->getDataConferencia('B'));
                $nf->setUsuarioConferencia($this->m_userid);
                $nf->setTotalnf($nf->getTotalnf('F'));
                $nf->alteraNotaFiscal();
            }
        }//if	
        if ($nf->getTipo() == '1') {
            $situacao = 'B';
            $nf->setSituacao($situacao);
            $nf->setDataConferencia($this->getDataConferencia('B'));
            $nf->setUsuarioConferencia($this->m_userid);
            $nf->setTotalnf($nf->getTotalnf('F'));
            $nf->alteraNotaFiscal();
        }
*/
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
//        $this->smarty->assign('dataConferencia', "'" . $this->getDataConferencia('F') . "'");
        $this->smarty->assign('lanc', $lancProd);

        //sql para mostrar a situacao no combobox
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='SituacaoNota') and (tipo = '".$nf->getSituacao()."')";
        $consulta->exec_sql($sql);
        $this->smarty->assign('situacao_name', "'".$consulta->resultado[0]['DESCRICAO']."'");
        
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='EST_MENU') and (campo='TipoNotaFiscal') and (tipo = '".$nf->getTipo()."')";
        $consulta->exec_sql($sql);
        $this->smarty->assign('tipo_name', "'".$consulta->resultado[0]['DESCRICAO']."'");
        
        $consulta->close_connection();


        $this->smarty->display('nota_fiscal_produto_mostra.tpl');
    }

//fim mostraNotaFiscal
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$recebe = new p_nota_fiscal_recebimento();

$recebe->controle();
?>
