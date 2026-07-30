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
    private $filtro_empresa = NULL;
    public $smarty = NULL;

    function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? [];
        $parmGet  = filter_input_array(INPUT_GET, FILTER_DEFAULT) ?? [];

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

        $submenu = $parmPost['submenu'] ?? $parmGet['submenu'] ?? '';
        if ($submenu === 'cadastro') {
            $submenu = 'cadastrar';
        }
        if ($submenu === 'excluir') {
            $submenu = 'exclui';
        }
        $this->m_submenu = $submenu;

        $filtro = $parmPost['filtro_empresa'] ?? $parmGet['filtro_empresa'] ?? null;
        $this->filtro_empresa = ($filtro !== null && $filtro !== '') ? trim($filtro) : null;

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('pathSweet', ADMhttpCliente . '/../sweetalert2');
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Parametros");
        $this->smarty->assign('colVis', "[ 0, 1 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setFilial(isset($parmPost['filial']) ? $parmPost['filial'] : '');
        $this->setGrupoServico(isset($parmPost['grupoServico']) ? $parmPost['grupoServico'] : '');
        $this->setCasasDecimais(isset($parmPost['casasDecimais']) ? $parmPost['casasDecimais'] : '4');
        $this->setControleVendedor(isset($parmPost['controleVendedor']) ? $parmPost['controleVendedor'] : '0');
        $this->setFluxoPedido(
            (isset($parmPost['fluxoPedido']) && $parmPost['fluxoPedido'] !== '') ? $parmPost['fluxoPedido'] : 'S'
        );
        $this->setSitAberto(isset($parmPost['sitAberto']) ? $parmPost['sitAberto'] : '');
        $this->setSitEmitirNf(isset($parmPost['sitEmitirNf']) ? $parmPost['sitEmitirNf'] : '');
        $this->setSitBaixado(isset($parmPost['sitBaixado']) ? $parmPost['sitBaixado'] : '');
        $this->setValorPedMinimo(isset($parmPost['valorPedMinimo']) ? $parmPost['valorPedMinimo'] : '');
        $this->setAprovacao(isset($parmPost['aprovacao']) ? $parmPost['aprovacao'] : '');
        $this->setDescontoMaximo(isset($parmPost['descontoMaximo']) ? $parmPost['descontoMaximo'] : '');
        $this->setTipoDesconto(isset($parmPost['tipoDesconto']) ? $parmPost['tipoDesconto'] : '');
        $controleDescontoPost = (isset($parmPost['controleDesconto']) && $parmPost['controleDesconto'] === 'S');
        if (!$controleDescontoPost) {
            $this->setDescontoMaximo('0');
            $this->setTipoDesconto('');
            $this->setAprovacao('N');
        }
        $this->setLancPedBaixado(
            (isset($parmPost['lancPedBaixado']) && $parmPost['lancPedBaixado'] !== '') ? $parmPost['lancPedBaixado'] : 'N'
        );
        $this->setEncomenda(
            (isset($parmPost['encomenda']) && $parmPost['encomenda'] !== '') ? $parmPost['encomenda'] : 'N'
        );
        $this->setFaturaPedido(isset($parmPost['faturaPedido']) ? $parmPost['faturaPedido'] : 'N');
        $this->setTipoComissao(
            (isset($parmPost['tipoComissao']) && $parmPost['tipoComissao'] !== '') ? $parmPost['tipoComissao'] : '1'
        );
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
                if ($this->verificaDireitoUsuario('PedParametros', 'I')) {
                    $this->desenhaCadastroParametros();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('PedParametros', 'A')) {
                $fat_parametros = $this->selectParametros();
                $this->setFilial($fat_parametros[0]['FILIAL']);
                $this->setGrupoServico($fat_parametros[0]['GRUPOSERVICO']);
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
                $this->setFaturaPedido(isset($fat_parametros[0]['FATURAPEDIDO']) ? $fat_parametros[0]['FATURAPEDIDO'] : 'N');
                $this->setCasasDecimais(isset($fat_parametros[0]['CASASDECIMAIS']) ? $fat_parametros[0]['CASASDECIMAIS'] : '4');
                $this->setControleVendedor(isset($fat_parametros[0]['CONTROLEVENDEDOR']) ? $fat_parametros[0]['CONTROLEVENDEDOR'] : '0');
                $this->setTipoComissao(isset($fat_parametros[0]['TIPOCOMISSAO']) ? $fat_parametros[0]['TIPOCOMISSAO'] : '1');
                $this->desenhaCadastroParametros();
                }
              break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('PedParametros', 'I')) {
                    if ($this->existeParametros()){
                        $this->m_submenu = "cadastrar";
                        $this->smarty->assign('swalIcon', 'warning');
                        $this->smarty->assign('swalTitle', 'Atenção');
                        $this->smarty->assign('swalText', 'Centro de custo já possui parâmetro cadastrado!');
                        $this->smarty->assign('swalAutoClose', false);
                        $this->desenhaCadastroParametros();
                      }
                    else {
                        $result = $this->incluiParametros();
                        if ($result === true) {
                            $this->smarty->assign('swalIcon', 'success');
                            $this->smarty->assign('swalTitle', 'Sucesso');
                            $this->smarty->assign('swalText', 'Parâmetro cadastrado!');
                            $this->smarty->assign('swalAutoClose', true);
                            $this->mostraParametros();
                        } else {
                            $this->smarty->assign('swalIcon', 'warning');
                            $this->smarty->assign('swalTitle', 'Atenção');
                            $this->smarty->assign('swalText', is_string($result) ? $result : 'Erro ao incluir parâmetro, entre em contato com o suporte!');
                            $this->smarty->assign('swalAutoClose', false);
                            $this->m_submenu = 'cadastrar';
                            $this->desenhaCadastroParametros();
                        }
                    }
                }
              break;
            case 'altera':
                if ($this->verificaDireitoUsuario('PedParametros', 'A')) {
                    $result = $this->alteraParametros();
                    if ($result === true) {
                        $this->smarty->assign('swalIcon', 'success');
                        $this->smarty->assign('swalTitle', 'Sucesso');
                        $this->smarty->assign('swalText', 'Parâmetro alterado!');
                        $this->smarty->assign('swalAutoClose', true);
                        $this->mostraParametros();
                    } else {
                        $this->smarty->assign('swalIcon', 'warning');
                        $this->smarty->assign('swalTitle', 'Atenção');
                        $this->smarty->assign('swalText', is_string($result) ? $result : 'Parâmetro não alterado, entre em contato com o suporte!');
                        $this->smarty->assign('swalAutoClose', false);
                        $this->m_submenu = 'alterar';
                        $this->desenhaCadastroParametros();
                    }
                }
              break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('PedParametros', 'E')) {
                    $result = $this->excluiParametros();
                    if ($result === true) {
                        $this->smarty->assign('swalIcon', 'success');
                        $this->smarty->assign('swalTitle', 'Sucesso');
                        $this->smarty->assign('swalText', 'Parâmetro excluído!');
                        $this->smarty->assign('swalAutoClose', true);
                    } else {
                        $this->smarty->assign('swalIcon', 'warning');
                        $this->smarty->assign('swalTitle', 'Atenção');
                        $this->smarty->assign('swalText', is_string($result) ? $result : 'Parâmetro não excluído, entre em contato com o suporte!');
                        $this->smarty->assign('swalAutoClose', false);
                    }
                    $this->mostraParametros();
                }
              break;
            case 'consulta':
                if ($this->verificaDireitoUsuario('PedParametros', 'C')) {
                    $this->mostraParametros();
                }
              break;
            default:
                if ($this->verificaDireitoUsuario('PedParametros', 'C')) {
                    $this->mostraParametros('');
                }
              break;
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
        $empresas = $this->selecionaEmpresasCombo();
        $this->smarty->assign('filial_ids', $empresas['id']);
        $this->smarty->assign('filial_names', $empresas['text']);

        // BOOLEAN
        $booleanos = $this->selecionaBooleanosCombo();
        $this->smarty->assign('boolean_ids', $booleanos['id']);
        $this->smarty->assign('boolean_names', $booleanos['text']);
        
        // COMBOBOX STATUS PEDIDO
        $pedidos = $this->selecionaSituacaoPedidoCombo();
        $this->smarty->assign('pedido_ids', $pedidos['id']);
        $this->smarty->assign('pedido_names', $pedidos['text']);


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

        if ($this->getLancPedBaixado() == '') {
            $this->smarty->assign('lancPedBaixado', 'N');
        } else {
            $this->smarty->assign('lancPedBaixado', $this->getLancPedBaixado());
        }

        if ($this->getAprovacao() == '') {
            $this->smarty->assign('aprovacao', '');
        } else {
            $aprovacaoTpl = $this->getAprovacao();
            if ($aprovacaoTpl === 'O') {
                $aprovacaoTpl = 'S';
            }
            $this->smarty->assign('aprovacao', $aprovacaoTpl);
        }

        if ($this->getEncomenda() == '') {
            $this->smarty->assign('encomenda', 'N');
        } else {
            $this->smarty->assign('encomenda', $this->getEncomenda());
        }

        if ($this->getFluxoPedido() == '') {
            $this->smarty->assign('fluxoPedido', 'S');
        } else {
            $this->smarty->assign('fluxoPedido', $this->getFluxoPedido());
        }

        if ($this->getFaturaPedido() == '') {
            $this->smarty->assign('faturaPedido', 'N');
        } else {
            $this->smarty->assign('faturaPedido', $this->getFaturaPedido());
        }

        $descontoMaximoNum = 0.0;
        if ($this->getDescontoMaximo() !== '' && $this->getDescontoMaximo() !== null) {
            $descontoMaximoNum = (float) $this->getDescontoMaximo();
        }
        $aprovacaoControle = strtoupper((string) $this->getAprovacao());
        $controleDescontoTpl = ($descontoMaximoNum > 0 || in_array($aprovacaoControle, ['S', 'O'], true)) ? 'S' : 'N';
        $this->smarty->assign('controleDesconto', $controleDescontoTpl);

        $this->smarty->assign('casasDecimais', $this->getCasasDecimaisParam() !== '' && $this->getCasasDecimaisParam() !== null ? $this->getCasasDecimaisParam() : '4');
        $this->smarty->assign('controleVendedor', $this->getControleVendedorParam() !== '' && $this->getControleVendedorParam() !== null ? $this->getControleVendedorParam() : '0');
        $this->smarty->assign('tipoComissao', $this->getTipoComissao() !== '' && $this->getTipoComissao() !== null ? $this->getTipoComissao() : '1');
        
        $this->smarty->display('parametro_cadastro.tpl');
        
    }//fim desenhaCadastroParametros

    /*
    * <b> Listagem de todas as registro cadastrados de tabela banco. </b>
    * @param String $mensagem Mensagem que ira mostrar na tela
    */
    function mostraParametros(){

        $lanc = $this->selectParametrosGeral($this->filtro_empresa);

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);
        $this->smarty->assign('filtro_empresa', $this->filtro_empresa);
        $this->smarty->assign('mod', 'ped');
        $this->smarty->assign('form', 'parametro');
        $this->smarty->assign('SCRIPT_NAME', $_SERVER['SCRIPT_NAME']);

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
