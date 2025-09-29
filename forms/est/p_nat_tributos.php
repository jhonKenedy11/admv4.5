<?php

/**
 * @package   astecv3
 * @name      p_nat_tributos
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date     23/10/2016
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_nat_tributos.php");

//Class p_nat_operacao_pag
class p_nat_tributos extends c_nat_tributos
{

    private $m_submenu = NULL;
    private $m_opcao = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;
    private $desabilitarCampos = NULL;



    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function __construct()
    {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        //// $parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
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
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra = (isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao = (isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        if ($this->m_opcao == "pesquisar") {
            $this->smarty->assign('titulo', "Natureza Operação Tributos");
            $this->smarty->assign('colVis', "[ 0, 1, 2 ]");
            $this->smarty->assign('disableSort', "[ 2 ]");
            $this->smarty->assign('numLine', "25");
        } else {
            $this->smarty->assign('titulo', "Natureza Operação Tributos");
            $this->smarty->assign('colVis', "[ 0, 1, 2, 3,4,5,6,7,8,9,10,11,12 ]");
            $this->smarty->assign('disableSort', "[ 12 ]");
            $this->smarty->assign('numLine', "25");
        }

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        // opcao = nat quando chama pelo form mostra da natureza de operacao.
        // if ($this->m_opcao =='nat'):
        //     $this->setIdNatop(isset($parmPost['id']) ? $parmPost['id'] : '');
        // else:    
        //     $this->setIdNatop(isset($parmPost['idNatop']) ? $parmPost['idNatop'] : '');
        // endif;
        $this->setIdNatop(isset($parmPost['idNatop']) ? $parmPost['idNatop'] : '');
        $this->setuf(isset($parmPost['uf']) ? $parmPost['uf'] : '');
        $this->setPessoa(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setOrigem(isset($parmPost['origem']) ? $parmPost['origem'] : '');
        $this->setTribIcms(isset($parmPost['tribIcms']) ? $parmPost['tribIcms'] : '');
        $this->setTribIcmsSaida(isset($parmPost['tribIcmsSaida']) ? $parmPost['tribIcmsSaida'] : '');
        $this->setNcm(isset($parmPost['ncm']) ? $parmPost['ncm'] : '');
        $this->setCest(isset($parmPost['cest']) ? $parmPost['cest'] : '');
        $this->setCfop(isset($parmPost['cfop']) ? $parmPost['cfop'] : '');
        $this->setAliqIcms(isset($parmPost['aliqIcms']) ? $parmPost['aliqIcms'] : '');
        $this->setRedBaseIcms(isset($parmPost['redBaseIcms']) ? $parmPost['redBaseIcms'] : '');
        $this->setPercDiferido(isset($parmPost['percDiferido']) ? $parmPost['percDiferido'] : '');
        $this->setModBc(isset($parmPost['modBc']) ? $parmPost['modBc'] : '3');
        $this->setModBcSt(isset($parmPost['modBcSt']) ? $parmPost['modBcSt'] : '5');
        $this->setMvaSt(isset($parmPost['mvast']) ? $parmPost['mvast'] : '');
        $this->setAliqIcmsSt(isset($parmPost['aliqicmsst']) ? $parmPost['aliqicmsst'] : '');
        //$this->setAliqSitTrib(isset($parmPost['aliqSitTrib']) ? $parmPost['aliqSitTrib'] : '');
        $this->setPercReducaoBcSt(isset($parmPost['percReducaoBcSt']) ? $parmPost['percReducaoBcSt'] : '');
        $this->setIss(isset($parmPost['iss']) ? $parmPost['iss'] : '');
        $this->setIpi(isset($parmPost['ipi']) ? $parmPost['ipi'] : '');
        $this->setInsideIpiBc(isset($parmPost['insideIpiBc']) ? $parmPost['insideIpiBc'] : 'N');
        $this->setCalculaIpi(isset($parmPost['calculaIpi']) ? $parmPost['calculaIpi'] : '');
        $this->setCstPis(isset($parmPost['cstPis']) ? $parmPost['cstPis'] : '');
        $this->setAliqPis(isset($parmPost['aliqPis']) ? $parmPost['aliqPis'] : '0');
        $this->setCstCofins(isset($parmPost['cstCofins']) ? $parmPost['cstCofins'] : '');
        $this->setAliqCofins(isset($parmPost['aliqCofins']) ? $parmPost['aliqCofins'] : '0');
        $this->setLegislacao(isset($parmPost['legislacao']) ? $parmPost['legislacao'] : '');
        $this->setCBenef(isset($parmPost['cbenef']) ? $parmPost['cbenef'] : '');
        $this->setMvaStAjustada(isset($parmPost['mvastajustada']) ? $parmPost['mvastajustada'] : '');
        $this->setContribuinteICMS(isset($parmPost['contribuinteICMS']) ? $parmPost['contribuinteICMS'] : 'N');
        $this->setConsumidorFinal(isset($parmPost['consumidorFinal']) ? $parmPost['consumidorFinal'] : 'N');
        $this->setAliqICMSSimplesST(isset($parmPost['aliqicmssimplesst']) ? $parmPost['aliqicmssimplesst'] : '');
        $this->setAliqFCPST(isset($parmPost['aliqfcpst']) ? $parmPost['aliqfcpst'] : '');
        $this->setAnp(isset($parmPost['anp']) ? $parmPost['anp'] : '');
        $this->desabilitarCampos = "N";


        // include do javascript
        //  include ADMjs . "/est/s_nat_tributos.js";

    }

    /**
     * <b> É responsavel para indicar para onde o sistema ira executar </b>
     * @name controle
     * @param VARCHAR submenu 
     * @return vazio
     */
    function controle()
    {
        switch ($this->m_submenu) {
            case 'updateGeneral':
                $this->alteraTributosGeral($this->m_par);
                $msg = 'Atualizado com sucesso!';
                $this->mostraTributos($msg);
                break;
            case 'atualizarInformacoes':
                $this->setNatDesc();
                $this->setUf($this->m_par[1]);
                $this->setPessoa($this->m_par[2]);
                $this->setOrigem($this->m_par[3]);
                $this->setTribIcms($this->m_par[4]);
                $this->setNcm($this->m_par[5]);
                $this->setAnp($this->m_par[6]);
                $this->desabilitarCampos = "S";

                $this->desenhaCadastroTributos();
                break;
            case 'copiar':
                if ($this->verificaDireitoUsuario('EstTributos', 'A')) {
                    $nat_operacao = $this->selectTributosID();
                    //$this->setId($nat_operacao[0]['ID']);
                    $this->setIdNatop($nat_operacao[0]['IDNATOP']);
                    $this->setCcusto($nat_operacao[0]['CENTROCUSTO']);
                    $this->setNatDesc();
                    $this->setUf($nat_operacao[0]['UF']);
                    $this->setPessoa($nat_operacao[0]['PESSOA']);
                    $this->setOrigem($nat_operacao[0]['ORIGEM']);
                    $this->setTribIcms($nat_operacao[0]['TRIBICMS']);
                    $this->setTribIcmsSaida($nat_operacao[0]['TRIBICMSSAIDA']);
                    $this->setNcm($nat_operacao[0]['NCM']);
                    $this->setCest($nat_operacao[0]['CEST']);
                    $this->setCfop($nat_operacao[0]['CFOP']);
                    $this->setAliqIcms($nat_operacao[0]['ALIQICMS']);
                    $this->setRedBaseIcms($nat_operacao[0]['PERCREDUCAOBC']);
                    $this->setPercDiferido($nat_operacao[0]['PERCDIFERIDO']);
                    $this->setModBc($nat_operacao[0]['MODBC']);
                    $this->setModBcSt($nat_operacao[0]['MODBCST']);
                    $this->setMvaSt($nat_operacao[0]['MVAST']);
                    //$this->setAliqSitTrib($nat_operacao[0]['ALIQICMSST']);
                    $this->setAliqIcmsSt($nat_operacao[0]['ALIQICMSST']);
                    $this->setPercReducaoBcSt($nat_operacao[0]['PERCREDUCAOBCST']);
                    $this->setIss($nat_operacao[0]['ISS']);
                    $this->setIpi($nat_operacao[0]['IPI']);
                    $this->setInsideIpiBc($nat_operacao[0]['INSIDEIPIBC']);
                    $this->setCalculaIpi($nat_operacao[0]['CALCULAIPI']);
                    $this->setCstPis($nat_operacao[0]['CSTPIS']);
                    $this->setAliqPis($nat_operacao[0]['ALIQPIS']);
                    $this->setCstCofins($nat_operacao[0]['CSTCOFINS']);
                    $this->setAliqCofins($nat_operacao[0]['ALIQCOFINS']);
                    $this->setLegislacao($nat_operacao[0]['LEGISLACAO']);
                    $this->setCBenef($nat_operacao[0]['CBENEF']);
                    $this->setMvaStAjustada($nat_operacao[0]['MVASTAJUSTADA']);
                    $this->setAliqIcmsSt($nat_operacao[0]['ALIQICMSST']);
                    $this->setConsumidorFinal($nat_operacao[0]['CONSUMIDORFINAL']);
                    $this->setContribuinteICMS($nat_operacao[0]['CONTRIBUINTEICMS']);
                    $this->setAliqICMSSimplesST($nat_operacao[0]['ALIQICMSSIMPLESST']);
                    $this->setAliqFCPST($nat_operacao[0]['ALIQFCPST']);
                    $this->setAnp($nat_operacao[0]['ANP']);

                    $this->m_submenu = 'cadastrar';

                    $this->desenhaCadastroTributos();
                }
                break;
            case 'automatico':
                $id = $this->getId();
                $nat_operacao = $this->selectTributosID();
                // NCM
                /*
                $consultaN = new c_banco();
                $sql = "select ncm, descricao from est_ncm order by ncm asc";
                $consultaN->exec_sql($sql);
                $consultaN->close_connection();
                $resultN = $consultaN->resultado;
                for ($n = 0; $n < count($resultN) + 1; $n++) {                    
                    if ($n > count($resultN)) {
                        $ncm = '';
                    } else {
                        $ncm = $result[$n]['NCM'];                    
                    }
                */
                // Pessoa
                $consultaP = new c_banco();
                $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Pessoa')";
                $consultaP->exec_sql($sql);
                $consultaP->close_connection();
                $resultP = $consultaP->resultado;
                for ($p = 0; $p < count($resultP); $p++) {
                    $pessoa = $resultP[$p]['ID'];
                    //Estado
                    $consulta = new c_banco();
                    $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado')";
                    $consulta->exec_sql($sql);
                    $consulta->close_connection();
                    $result = $consulta->resultado;
                    for ($u = 0; $u < count($result); $u++) {
                        //$this->setNcm($ncm);
                        $this->setPessoa($pessoa);
                        $this->setUf($result[$u]['ID']);
                        if (!$this->existeTributos()) {
                            $this->incluiTributos();
                        }
                        $this->setId($id);
                        $nat_operacao = $this->selectTributosID();
                    }
                }
                //}
                $this->mostraTributos('Copias realizada com sucesso');
                break;
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('EstTributos', 'I')) {
                    $this->setNatDesc();
                    $this->desenhaCadastroTributos();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('EstTributos', 'A')) {
                    $nat_operacao = $this->selectTributosID();
                    $this->setId($nat_operacao[0]['ID']);
                    $this->setIdNatop($nat_operacao[0]['IDNATOP']);
                    $this->setCcusto($nat_operacao[0]['CENTROCUSTO']);
                    $this->setNatDesc();
                    $this->setUf($nat_operacao[0]['UF']);
                    $this->setPessoa($nat_operacao[0]['PESSOA']);
                    $this->setOrigem($nat_operacao[0]['ORIGEM']);
                    $this->setTribIcms($nat_operacao[0]['TRIBICMS']);
                    $this->setTribIcmsSaida($nat_operacao[0]['TRIBICMSSAIDA']);
                    $this->setNcm($nat_operacao[0]['NCM']);
                    $this->setCest($nat_operacao[0]['CEST']);
                    $this->setCfop($nat_operacao[0]['CFOP']);
                    $this->setAliqIcms($nat_operacao[0]['ALIQICMS']);
                    $this->setRedBaseIcms($nat_operacao[0]['PERCREDUCAOBC']);
                    $this->setPercDiferido($nat_operacao[0]['PERCDIFERIDO']);
                    $this->setModBc($nat_operacao[0]['MODBC']);
                    $this->setModBcSt($nat_operacao[0]['MODBCST']);
                    $this->setMvaSt($nat_operacao[0]['MVAST']);
                    //$this->setAliqSitTrib($nat_operacao[0]['ALIQICMSST']);
                    $this->setAliqIcmsSt($nat_operacao[0]['ALIQICMSST']);
                    $this->setPercReducaoBcSt($nat_operacao[0]['PERCREDUCAOBCST']);
                    $this->setIss($nat_operacao[0]['ISS']);
                    $this->setIpi($nat_operacao[0]['IPI']);
                    $this->setInsideIpiBc($nat_operacao[0]['INSIDEIPIBC']);
                    $this->setCalculaIpi($nat_operacao[0]['CALCULAIPI']);
                    $this->setCstPis($nat_operacao[0]['CSTPIS']);
                    $this->setAliqPis($nat_operacao[0]['ALIQPIS']);
                    $this->setCstCofins($nat_operacao[0]['CSTCOFINS']);
                    $this->setAliqCofins($nat_operacao[0]['ALIQCOFINS']);
                    $this->setLegislacao($nat_operacao[0]['LEGISLACAO']);
                    $this->setCBenef($nat_operacao[0]['CBENEF']);
                    $this->setMvaStAjustada($nat_operacao[0]['MVASTAJUSTADA']);
                    $this->setAliqIcmsSt($nat_operacao[0]['ALIQICMSST']);
                    $this->setConsumidorFinal($nat_operacao[0]['CONSUMIDORFINAL']);
                    $this->setContribuinteICMS($nat_operacao[0]['CONTRIBUINTEICMS']);
                    $this->setAliqICMSSimplesST($nat_operacao[0]['ALIQICMSSIMPLESST']);
                    $this->setAliqFCPST($nat_operacao[0]['ALIQFCPST']);
                    $this->setAnp($nat_operacao[0]['ANP']);

                    $this->desenhaCadastroTributos();
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('EstTributos', 'I')) {
                    try {
                        $msg = $this->incluiTributos();
                        $this->mostraTributos($msg);
                        /*if ($this->existeTributos()){
                                $this->m_submenu = "cadastrar";
                                $this->desenhaCadastroTributos("TRIBUTO JÁ EXISTENTE, ALTERE OS PARAMETROS");}
                        else {
                                $this->mostraTributos($this->incluiTributos());}*/
                    } catch (Error $e) {
                        throw new Exception($e->getMessage() . "Item não cadastrado ");
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage() . "Item não cadastrado ");
                    }
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('EstTributos', 'A')) {
                    $this->mostraTributos($this->alteraTributos());
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('EstTributos', 'E')) {

                    if ($this->excluiTributos() == '') {
                        $msgPedido = "Registro excluido com sucesso!";
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                               
                                text: '" . $msgPedido . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        $this->mostraTributos('');
                    } else {
                        $msgPedido = "Erro ao excluir o registro, caso persista o erro contate o suporte!";
                        echo "<script type='text/javascript' src='" . ADMsweetAlert2 . "/dist/sweetalert2.all.min.js'></script> ";
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atenção',
                    
                                text: '" . $msgPedido . ".',
                                confirmButtonText: 'OK'
                            });
                            </script>";
                        $this->mostraTributos('');
                    }
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('EstTributos', 'C')) {

                    $this->mostraTributos('');
                }
        }
    } // fim controle


    /**
     * <b> Desenha form de cadastro ou alteração Tributos. </b>
     * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
     * @param String $tipoMsg tipo da mensagem sucesso/alerta
     */
    function desenhaCadastroTributos($mensagem = NULL)
    {

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idNatop', $this->getIdNatop());
        $this->smarty->assign('natDesc', "'" . $this->getNatDesc() . "'");
        $this->smarty->assign('natTipo', $this->getNatTipo());
        $this->smarty->assign('uf', $this->getUf());
        $this->smarty->assign('pessoa', $this->getPessoa());
        $this->smarty->assign('origem', $this->getorigem());
        $this->smarty->assign('tribIcms', $this->getTribIcms());
        $this->smarty->assign('tribIcmsSaida', $this->getTribIcmsSaida());
        $this->smarty->assign('ncm', $this->getNcm());
        $this->smarty->assign('cest', $this->getCest());
        $this->smarty->assign('cfop', $this->getCfop());
        $this->smarty->assign('aliqIcms', $this->getAliqIcms('F'));
        $this->smarty->assign('redBaseIcms', $this->getRedBaseIcms('F'));
        $this->smarty->assign('percDiferido', $this->getPercDiferido('F'));
        $this->smarty->assign('modBc', $this->getModBc());
        $this->smarty->assign('modBcSt', $this->getModBcSt());
        $this->smarty->assign('mvast', $this->getMvaSt('F'));
        $this->smarty->assign('aliqicmsst', $this->getAliqIcmsSt('F'));
        //$this->smarty->assign('aliqSitTrib', $this->getAliqSitTrib('F'));
        $this->smarty->assign('percReducaoBcSt', $this->getPercReducaoBcSt('F'));
        $this->smarty->assign('iss', $this->getIss('F'));
        $this->smarty->assign('ipi', $this->getIpi('F'));
        $this->smarty->assign('insideIpiBc', $this->getInsideIpiBc());
        $this->smarty->assign('calculaIpi', $this->getCalculaIpi());
        $this->smarty->assign('cstPis', $this->getCstPis());
        $this->smarty->assign('aliqPis', $this->getAliqPis('F'));
        $this->smarty->assign('cstCofins', $this->getCstCofins());
        $this->smarty->assign('aliqCofins', $this->getAliqCofins('F'));
        $this->smarty->assign('legislacao', $this->getLegislacao());
        $this->smarty->assign('mvastajustada', $this->getMvaStAjustada('F'));
        //$this->smarty->assign('aliqicmsst', $this->getAliqIcmsSt('F'));
        $this->smarty->assign('contribuinteICMS', $this->getContribuinteICMS());
        $this->smarty->assign('consumidorFinal', $this->getConsumidorFinal());
        $this->smarty->assign('aliqicmssimplesst', $this->getAliqICMSSimplesST('F'));
        $this->smarty->assign('aliqfcpst', $this->getAliqFCPST('F'));


        // empresa ##############################
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $ccusto_ids[$i] = $result[$i]['ID'];
            $ccusto_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('ccusto_ids', $ccusto_ids);
        $this->smarty->assign('ccusto_names', $ccusto_names);
        if ($this->m_submenu == 'cadastrar')
            $this->smarty->assign('ccusto_id',  $this->m_empresacentrocusto);
        else
            $this->smarty->assign('ccusto_id',  $this->getCcusto());


        // CFOP ##############################
        $sql = "select cfop as id, descricao from est_nat_cfop where (tipo='" . $this->getNatTipo() . "')";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $cfop_ids[$i] = $result[$i]['ID'];
            $cfop_names[$i] = $result[$i]['ID'] . " - " . ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('cfop_ids', $cfop_ids);
        $this->smarty->assign('cfop_names', $cfop_names);

        // tipo Nat Operacao##############################
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TipoNatOp')";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $tipoNatOp_ids[$i] = $result[$i]['ID'];
            $tipoNatOp_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('tipoNatOp_ids', $tipoNatOp_ids);
        $this->smarty->assign('tipoNatOp_names', $tipoNatOp_names);

        // Modalidade Bc
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='ModBc')";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $modBc_ids[$i] = $result[$i]['ID'];
            $modBc_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('modBc_ids', $modBc_ids);
        $this->smarty->assign('modBc_names', $modBc_names);

        // Modalidade Bc St
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='ModBcSt')";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $modBcSt_ids[$i] = $result[$i]['ID'];
            $modBcSt_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('modBcSt_ids', $modBcSt_ids);
        $this->smarty->assign('modBcSt_names', $modBcSt_names);

        // CST PIS/COFINS
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='PISCOFINS')";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $pisCofins_ids[$i] = $result[$i]['ID'];
            $pisCofins_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('pisCofins_ids', $pisCofins_ids);
        $this->smarty->assign('pisCofins_names', $pisCofins_names);

        // Pessoa
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Pessoa')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $pessoa_ids[0] = '';
        $pessoa_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $pessoa_ids[$i + 1] = $result[$i]['ID'];
            $pessoa_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('pessoa_ids', $pessoa_ids);
        $this->smarty->assign('pessoa_names', $pessoa_names);

        // estado
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $estado_ids[0] = '0';
        $estado_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $estado_ids[$i + 1] = $result[$i]['ID'];
            $estado_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('uf_ids', $estado_ids);
        $this->smarty->assign('uf_names', $estado_names);

        // ORIGEM MERCADORIA
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='OrigemMercadoria')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $origem_ids[$i] = $result[$i]['ID'];
            $origem_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('origem_ids', $origem_ids);
        $this->smarty->assign('origem_names', $origem_names);

        // TRIBUTO ICMS
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and ((campo='TributacaoIcms') or (campo='CSOSN'))";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        if ($this->desabilitarCampos == "S") {
            $tribIcms_ids[0] = '';
            $tribIcms_names[0] = 'Selecionar';
            for ($i = 0; $i < count($result); $i++) {
                $tribIcms_ids[$i + 1] = $result[$i]['ID'];
                $tribIcms_names[$i + 1] = $result[$i]['DESCRICAO'];
            }
        } else {
            for ($i = 0; $i < count($result); $i++) {
                $tribIcms_ids[$i] = $result[$i]['ID'];
                $tribIcms_names[$i] = $result[$i]['DESCRICAO'];
            }
        }

        $this->smarty->assign('tribIcms_ids', $tribIcms_ids);
        $this->smarty->assign('tribIcms_names', $tribIcms_names);

        // TRIBUTO ICMS SAIDA
        $consulta = new c_banco();
        $sql = "select * from amb_empresa where (centrocusto=" . $this->m_empresacentrocusto . ")";
        $emp = $consulta->exec_sql($sql);
        $crt = $emp[0]['REGIMETRIBUTARIO'];

        if ($crt == '3'){
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TributacaoIcms')";
        } else if ($crt == '2'){
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and ((campo='TributacaoIcms') or (campo='CSOSN'))"; 
        } else {
            $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='CSOSN')";
        }

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        if ($this->desabilitarCampos == "S") {
            $tribIcms_saida_ids[0] = '';
            $tribIcms_saida_names[0] = 'Selecionar';
            for ($i = 0; $i < count($result); $i++) {
                $tribIcms_saida_ids[$i + 1] = $result[$i]['ID'];
                $tribIcms_saida_names[$i + 1] = $result[$i]['DESCRICAO'];
            }
        } else {
            for ($i = 0; $i < count($result); $i++) {
                $tribIcms_saida_ids[$i] = $result[$i]['ID'];
                $tribIcms_saida_names[$i] = $result[$i]['DESCRICAO'];
            }
        }

        $this->smarty->assign('tribIcms_saida_ids', $tribIcms_saida_ids);
        $this->smarty->assign('tribIcms_saida_names', $tribIcms_saida_names);


        // BOOLEAN ##############################
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='BOOLEAN')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $boolean_ids[$i] = $result[$i]['ID'];
            $boolean_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('boolean_ids', $boolean_ids);
        $this->smarty->assign('boolean_names', $boolean_names);

        $sql = "SELECT id, cbenef, descricao FROM EST_NAT_OP_BENEFICIO ";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $cbenef_ids[0] = 0;
        $cbenef_names[0] = 'Sem benefício';
        for ($i = 1; $i < count($result); $i++) {
            $cbenef_ids[$i] = $result[$i]['CBENEF'];
            $cbenef_names[$i] = $result[$i]['CBENEF'] . ' - ' . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('cbenef_ids', $cbenef_ids);
        $this->smarty->assign('cbenef_names', $cbenef_names);
        $this->smarty->assign('cbenef_id', $this->getCBenef());

        // ANP ##############################
        $sql = "select anp, descricao from est_anp ";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        $anp_ids[0] = ' ';
        $anp_names[0] = ' Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $anp_ids[$i + 1] = $result[$i]['ANP'];
            $anp_names[$i + 1] = $result[$i]['ANP'] . " - " . ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('anp_ids', $anp_ids);
        $this->smarty->assign('anp_names', $anp_names);

        $this->smarty->assign('anp', $this->getAnp());

        $this->smarty->assign('desabilitarCampos', $this->desabilitarCampos);

        $this->smarty->display('nat_tributos_cadastro.tpl');
    } //fim desenhaCadastroNatOp

    /**
     * <b> Listagem de todas as registro cadastrados de tabela nat_operacao. </b>
     * @param String $mensagem Mensagem que ira mostrar na tela
     */
    function mostraTributos($mensagem)
    {

        $lanc = $this->selectTributos();

        // filtro estado
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $filtro_estado_ids[0] = '0';
        $filtro_estado_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $filtro_estado_ids[$i + 1] = $result[$i]['ID'];
            $filtro_estado_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filtro_uf_ids', $filtro_estado_ids);
        $this->smarty->assign('filtro_uf_names', $filtro_estado_names);

        // Pessoa
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Pessoa')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $filtro_pessoa_ids[0] = '';
        $filtro_pessoa_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $filtro_pessoa_ids[$i + 1] = $result[$i]['ID'];
            $filtro_pessoa_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filtro_pessoa_ids', $filtro_pessoa_ids);
        $this->smarty->assign('filtro_pessoa_names', $filtro_pessoa_names);

        // ORIGEM MERCADORIA
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='OrigemMercadoria')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $filtro_origem_ids[0] = '';
        $filtro_origem_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $filtro_origem_ids[$i + 1] = $result[$i]['ID'];
            $filtro_origem_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filtro_origem_ids', $filtro_origem_ids);
        $this->smarty->assign('filtro_origem_names', $filtro_origem_names);

        // TRIBUTO ICMS
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and ((campo='TributacaoIcms') or (campo='CSOSN'))";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $filtro_tribIcms_ids[0] = '';
        $filtro_tribIcms_names[0] = 'Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $filtro_tribIcms_ids[$i + 1] = $result[$i]['ID'];
            $filtro_tribIcms_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filtro_tribIcms_ids', $filtro_tribIcms_ids);
        $this->smarty->assign('filtro_tribIcms_names', $filtro_tribIcms_names);

        // NCM
        $consulta = new c_banco();
        $sql = "select ncm, descricao from est_ncm order by ncm asc";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $filtro_ncm_ids[0] = ' ';
        $filtro_ncm_names[0] = ' Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $filtro_ncm_ids[$i + 1] = $result[$i]['NCM'];
            $filtro_ncm_names[$i + 1] = $result[$i]['NCM'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filtro_ncm_ids', $filtro_ncm_ids);
        $this->smarty->assign('filtro_ncm_names', $filtro_ncm_names);

        // ANP
        $consulta = new c_banco();
        $sql = "select anp, descricao from est_anp ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        $filtro_anp_ids[0] = ' ';
        $filtro_anp_names[0] = ' Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $filtro_anp_ids[$i + 1] = $result[$i]['ANP'];
            $filtro_anp_names[$i + 1] = $result[$i]['ANP'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filtro_anp_ids', $anp_ids);
        $this->smarty->assign('filtro_anp_names', $anp_names);

        // tipo Nat Operacao##############################
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FAT_MENU') and (campo='TipoNatOp')";
        $consulta = new c_banco();

        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $filtro_tipoNatOp_ids[0] = ' ';
        $filtro_tipoNatOp_names[0] = ' Selecione';
        for ($i = 0; $i < count($result); $i++) {
            $filtro_tipoNatOp_ids[$i + 1] = $result[$i]['ID'];
            $filtro_tipoNatOp_names[$i + 1] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('filtro_tipoNatOp_ids', $filtro_tipoNatOp_ids);
        $this->smarty->assign('filtro_tipoNatOp_names', $filtro_tipoNatOp_names);

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('idNatop', $this->getIdNatop());

        if ($this->m_opcao == "pesquisar") {
            $this->smarty->display('nat_tributos_pesquisar.tpl');
        } else {
            $this->smarty->display('nat_tributos_mostra.tpl');
        }
    } //fim mostraTributos
    //-------------------------------------------------------------
}
//	END OF THE CLASS
/**
 * <b> Rotina principal - cria classe. </b>
 */
$nat_tributos = new p_nat_tributos();

$nat_tributos->controle();
