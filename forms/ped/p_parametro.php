<?php
/**
 * @package   admv4.5
 * @name      p_parametros
 * @version   4.5
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      20/02/2026
 */
if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir."/../../class/ped/c_parametro.php");
include_once($dir."/../../bib/c_tools.php");

Class p_parametros extends c_parametros {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Parametros");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setFilial(isset($parmPost['filial']) ? $parmPost['filial'] : '');
        $this->setGrupoServico(isset($parmPost['grupoServico']) ? $parmPost['grupoServico'] : '');
        $this->setApresentacao(isset($parmPost['apresentacao']) ? $parmPost['apresentacao'] : '');
        $this->setObjetivo(isset($parmPost['objetivo']) ? $parmPost['objetivo'] : '');
        $this->setGarantia(isset($parmPost['garantia']) ? $parmPost['garantia'] : '');
        $this->setImpostos(isset($parmPost['impostos']) ? $parmPost['impostos'] : '');
        $this->setPrazoEntrega(isset($parmPost['prazoEntrega']) ? $parmPost['prazoEntrega'] : '');
        $this->setValidade(isset($parmPost['validade']) ? $parmPost['validade'] : '');
        $this->setAceite(isset($parmPost['aceite']) ? $parmPost['aceite'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setFluxoPedido(isset($parmPost['fluxoPedido']) ? $parmPost['fluxoPedido'] : '');
        $this->setSitAberto(isset($parmPost['sitAberto']) ? $parmPost['sitAberto'] : '');
        $this->setSitEmitirNf(isset($parmPost['sitEmitirNf']) ? $parmPost['sitEmitirNf'] : '');
        $this->setSitBaixado(isset($parmPost['sitBaixado']) ? $parmPost['sitBaixado'] : '');
        $this->setValorPedMinimo(isset($parmPost['valorPedMinimo']) ? $parmPost['valorPedMinimo'] : '');
        $this->setAprovacao(isset($parmPost['aprovacao']) ? $parmPost['aprovacao'] : '');
        $this->setDescontoMaximo(isset($parmPost['descontoMaximo']) ? $parmPost['descontoMaximo'] : '');
        $this->setLancPedBaixado(isset($parmPost['lancPedBaixado']) ? $parmPost['lancPedBaixado'] : '');
        $this->setTipoDesconto(isset($parmPost['tipoDesconto']) ? $parmPost['tipoDesconto'] : '');
        $this->setEncomenda(isset($parmPost['encomenda']) ? $parmPost['encomenda'] : '');
    }

    /**
    * <b> É responsavel para indicar para onde o sistema ira executar </b>
    * @name controle
    * @param VARCHAR submenu 
    * @return vazio
    */
    function controle(){
        switch ($this->m_submenu){
            case 'cadastrar':
              //if ($this->verificaDireitoUsuario('CatParametros', 'I')){
                $this->desenhaCadastroParametros();
              //}
              break;
            case 'alterar':
                $fat_parametros = $this->selectParametros();
                $this->setFilial($fat_parametros[0]['FILIAL']);
                $this->setGrupoServico($fat_parametros[0]['GRUPOSERVICO']);
                $this->setApresentacao($fat_parametros[0]['APRESENTACAO']);
                $this->setObjetivo($fat_parametros[0]['OBJETIVO']);
                $this->setGarantia($fat_parametros[0]['GARANTIA']);
                $this->setImpostos($fat_parametros[0]['IMPOSTOS']);
                $this->setPrazoEntrega( $fat_parametros[0]['PRAZOENTREGA']);
                $this->setValidade($fat_parametros[0]['VALIDADE']);
                $this->setAceite($fat_parametros[0]['ACEITE']);
                $this->setObs($fat_parametros[0]['OBS']);
                $this->setFluxoPedido($fat_parametros[0]['FLUXOPEDIDO']);
                $this->setSitEmitirNf($fat_parametros[0]['SITEMITIRNF']);
                $this->setSitBaixado($fat_parametros[0]['SITBAIXADO']);
                $this->setSitAberto($fat_parametros[0]['SITABERTO']);
                $this->setValorPedMinimo($fat_parametros[0]['VALORPEDIDOMINIMO']);
                $this->setAprovacao($fat_parametros[0]['APROVACAO']);
                $this->setDescontoMaximo($fat_parametros[0]['DESCONTOMAXIMO']);
                $this->setLancPedBaixado($fat_parametros[0]['LANCPEDBAIXADO']);
                $this->setTipoDesconto($fat_parametros[0]['TIPODESCONTO']);
                $this->setEncomenda($fat_parametros[0]['ENCOMENDA']);
                $this->desenhaCadastroParametros();
              break;
            case 'inclui':
              //if ($this->verificaDireitoUsuario('CatParametros', 'I')){
                    if ($this->existeParametros()){
                        $this->m_submenu = "cadastrar";
                        $msgPedido = "Centro de custo já possui parâmetro cadastrado!";
                        echo "<style>.swal-modal{width: 600px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgPedido`, title: 'Atenção!', icon: 'error', dangerMode: true});</script>";
                        $this->desenhaCadastroParametros();
                      }
                    else {
                        $result = $this->incluiParametros();
                        if($result){
                            $msgPedido = "Parâmetro cadastrado!";
                            echo "<style>.swal-modal{width: 600px !important;}.swal-title{font-size: 21px;}</style> ";
                            echo "<script>swal({text: `$msgPedido`, title: 'Sucesso!', icon: 'success',button: 'Ok',});</script>";
                            $this->mostraParametros();
                        }else{
                            $msgPedido = "Erro ao incluir parâmetro, entre em contato com o suporte!";
                            echo "<style>.swal-modal{width: 600px !important;}.swal-title{font-size: 21px;}</style> ";
                            echo "<script>swal({text: `$msgPedido`, title: 'Sucesso!', dangerMode: true, icon: 'success',button: 'Ok',});</script>";
                          $this->mostraParametros();
                        }
                    }
            //}		
              break;
            case 'altera':
              //if ($this->verificaDireitoUsuario('CatParametros', 'A')){
                    $result = $this->alteraParametros();
                    if($result){
                        $msgPedido = "Parâmetro alterado!";
                        echo "<style>.swal-modal{width: 600px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgPedido`, title: 'Sucesso!', icon: 'success',button: 'Ok',});</script>";
                        $this->mostraParametros();
                    }else{
                        $msgPedido = "Parâmetro não alterado, entre em contato com o suporte!";
                        echo "<style>.swal-modal{width: 600px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgPedido`, title: 'Atenção!', dangerMode: true, icon: 'error',button: 'Ok',});</script>";
                        $this->mostraParametros(); 
                    }
              //}
              break;
            case 'exclui':
              //if ($this->verificaDireitoUsuario('CatParametros', 'E')){
                    $result = $this->excluiParametros();
                    if($result){
                        $msgPedido = "Parâmetro excluido!";
                        echo "<style>.swal-modal{width: 600px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgPedido`, title: 'Sucesso!', icon: 'success',button: 'Ok',});</script>";
                        $this->mostraParametros();
                    }else{
                        $msgPedido = "Parâmetro não excluido, entre em contato com o suporte!";
                        echo "<style>.swal-modal{width: 600px !important;}.swal-title{font-size: 21px;}</style> ";
                        echo "<script>swal({text: `$msgPedido`, title: 'Atenção!', dangerMode: true, icon: 'error',button: 'Ok',});</script>";
                        $this->mostraParametros();
                    }
            //}
              break;
            default:
              //if ($this->verificaDireitoUsuario('CatParametros', 'C')){
                $this->mostraParametros('');
              //}
        }
    } // fim controle

    /**
     * <b> Desenha form de cadastro ou alteração Parametros. </b>
     * @param String $mensagem mensagem que ira apresentar na tela no caso de erro ou msg de aviso ao usuário
     * @param String $tipoMsg tipo da mensagem sucesso/alerta
     */
    function desenhaCadastroParametros($mensagem=NULL){

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        
        $this->smarty->assign('filial', $this->getFilial());
        $this->smarty->assign('grupoServico', $this->getGrupoServico());
        $this->smarty->assign('tipoDesconto', $this->getTipoDesconto());

        // COMBOBOX FILIAL
        $consulta = new c_banco();
        $sql = "SELECT CENTROCUSTO, NOMEFANTASIA FROM AMB_EMPRESA ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['CENTROCUSTO'];
            $filial_names[$i] = $result[$i]['NOMEFANTASIA'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);

        // BOOLEAN ##############################
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='AMB_MENU') and (campo='BOOLEAN')";
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i=0; $i < count($result); $i++){
            $boolean_ids[$i] = $result[$i]['ID'];
            $boolean_names[$i] = ucwords(strtolower($result[$i]['DESCRICAO']));
        }
        $this->smarty->assign('boolean_ids', $boolean_ids);
        $this->smarty->assign('boolean_names', $boolean_names);
        
        // COMBOBOX STATUS PEDIDO
        $consulta = new c_banco();
        $sql = "SELECT TIPO, PADRAO FROM AMB_DDM ";
        $sql.= "WHERE CAMPO = 'SITUACAOPEDIDO'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $pedido_ids[$i] = $result[$i]['TIPO'];
            $pedido_names[$i] = $result[$i]['PADRAO'];
        }
        $this->smarty->assign('pedido_ids', $pedido_ids);
        $this->smarty->assign('pedido_names', $pedido_names);


        if($this->getDescontoMaximo() == ''){
            $this->smarty->assign('descontoMaximo', '0,00');   
        }else{
            $this->smarty->assign('descontoMaximo', $this->getDescontoMaximo('F'));      
        }

        if( $this->getValorPedMinimo() == ''){
            $this->smarty->assign('valorPedMinimo', '0,00');   
        }else{
            $this->smarty->assign('valorPedMinimo', $this->getValorPedMinimo('F'));      
        }

        if($this->getSitEmitirNf() == ''){
            $this->smarty->assign('sitEmitirNf', 0);   
        }else{
            $this->smarty->assign('sitEmitirNf', $this->getSitEmitirNf());      
        }

        if($this->getSitBaixado() == ''){
            $this->smarty->assign('sitBaixado', 0);   
        }else{
            $this->smarty->assign('sitBaixado', $this->getSitBaixado());   
        } 

        if($this->getSitAberto() == ''){
            $this->smarty->assign('sitAberto', 0);   
        }else{
            $this->smarty->assign('sitAberto', $this->getSitAberto());   
        }

        if($this->getLancPedBaixado() == ''){
            $this->smarty->assign('lancPedBaixado', '');   
        }else{
            $this->smarty->assign('lancPedBaixado', $this->getLancPedBaixado()); 
        }

        if($this->getAprovacao() == ''){
            $this->smarty->assign('aprovacao', '');   
        }else{
            $this->smarty->assign('aprovacao', $this->getAprovacao()); 
        }

        if($this->getEncomenda() == ''){
            $this->smarty->assign('encomenda', '');   
        }else{
            $this->smarty->assign('encomenda', $this->getEncomenda()); 
        }

        if($this->getFluxoPedido() == ''){
          $this->smarty->assign('fluxoPedido', '');   
        }else{
          $this->smarty->assign('fluxoPedido', $this->getFluxoPedido());   
        }
        
        $this->smarty->display('parametro_cadastro.tpl');
        
    }//fim desenhaCadastroParametros

    /*
    * <b> Listagem de todas as registro cadastrados de tabela banco. </b>
    * @param String $mensagem Mensagem que ira mostrar na tela
    */
    function mostraParametros(){

        $lanc = $this->selectParametrosGeral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->display('parametro_mostra.tpl');
    } //fim mostraParametros
//-------------------------------------------------------------
}
//	END OF THE CLASS
 /**
 * <b> Rotina principal - cria classe. </b>
 */
$banco = new p_parametros();
                              
$banco->controle();
