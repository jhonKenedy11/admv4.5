<?php
/**
 * @package   astec
 * @name      p_inventario
 * @version   3.0.00
 * @copyright 2020
 * @link      http://www.admservice.com.br/
 * @author    Tony Hashimoto 
 * @date      13/05/2020
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_inventario.php");
require_once($dir."/../../class/est/c_produto.php");
require_once($dir."/../../class/est/c_produto_estoque.php");
require_once($dir."/../../class/est/c_nota_fiscal.php");
require_once($dir."/../../class/est/c_nota_fiscal_produto.php");
require_once($dir."/../../class/crm/c_conta.php");
require_once($dir."/../../class/est/c_estoque_rel.php");
require_once($dir."/../../class/est/c_inventario_tools.php");

//Class p_inventario
Class p_inventario extends c_inventario {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_opcao = null;

    private $m_par_pesq = null;
    private $m_dadosInventario = null;
    private $m_dadosInventarioSaida = null;
    private $m_dadosInventarioEnt = null;

    private $m_tela = null;
    private $m_btnGerarInventario = null;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  
        
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
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
     
        $this->m_pesq=(isset($parmGet['pesq']) ? $parmGet['pesq'] : (isset($parmPost['pesq']) ? $parmPost['pesq'] : ''));
        $this->m_grupo=(isset($parmGet['grupoSelected']) ? $parmGet['grupoSelected'] : (isset($parmPost['grupoSelected']) ? $parmPost['grupoSelected'] : ''));
        
        $this->m_dadosInventario=(isset($parmGet['dadosInventario']) ? $parmGet['dadosInventario'] : (isset($parmPost['dadosInventario']) ? $parmPost['dadosInventario'] : ''));
        $this->m_dadosInventarioSaida=(isset($parmGet['dadosInventarioSaida']) ? $parmGet['dadosInventarioSaida'] : (isset($parmPost['dadosInventarioSaida']) ? $parmPost['dadosInventarioSaida'] : ''));
        $this->m_dadosInventarioEnt=(isset($parmGet['dadosInventarioEnt']) ? $parmGet['dadosInventarioEnt'] : (isset($parmPost['dadosInventarioEnt']) ? $parmPost['dadosInventarioEnt'] : ''));
        $this->m_tela=(isset($parmGet['tela']) ? $parmGet['tela'] : (isset($parmPost['tela']) ? $parmPost['tela'] : ''));
        $this->m_btnGerarInventario=(isset($parmGet['gerarInventario']) ? $parmGet['gerarInventario'] : (isset($parmPost['gerarInventario']) ? $parmPost['gerarInventario'] : 'false'));
        $this->m_par = explode("|", $this->m_letra);
        $this->m_par_pesq = explode("|", $this->m_pesq);
        $this->data_ini = (isset($parmGet['dataIni']) ? $parmGet['dataIni'] : (isset($parmPost['dataIni']) ? $parmPost['dataIni'] : ''));
        $this->data_fim = (isset($parmGet['dataFim']) ? $parmGet['dataFim'] : (isset($parmPost['dataFim']) ? $parmPost['dataFim'] : ''));
        $this->filtros_modal = (isset($parmGet['filtros']) ? $parmGet['filtros'] : (isset($parmPost['filtros'])? $parmPost['filtros'] : ''));
        $this->id = (isset($parmGet['id']) ? $parmGet['id'] : (isset($parmPost['id']) ? $parmPost['id'] : ''));
        $this->itens = (isset($parmGet['itens']) ? $parmGet['itens'] : (isset($parmPost['itens']) ? $parmPost['itens'] : ''));

        $this->m_idImagem = $_REQUEST['idimg'];
        $this->m_destaque = $_REQUEST['destaque']; 
        $this->m_titulo   = (isset($parmGet['tituloImg']) ? $parmGet['tituloImg'] : (isset($parmPost['tituloImg']) ? $parmPost['tituloImg'] : ''));;  

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        if ($this->m_tela == "inventarioProduto"):
            $this->smarty->assign('titulo', "Inventario");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4 ]"); 
            $this->smarty->assign('disableSort', "[ 0,1,2,3,4 ]"); 
            $this->smarty->assign('numLine', "600"); 
        elseif ($this->m_tela == "inventarioProdutoCadastro"):
            $this->smarty->assign('titulo', "Inventario");
            $this->smarty->assign('colVis', "[ 0,1,2,3]"); 
            $this->smarty->assign('disableSort', "[ 0,1,2,3 ]"); 
            $this->smarty->assign('numLine', "600"); 
        else:
            $this->smarty->assign('titulo', "Inventario");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('numLine', "600"); 
        endif;

        $this->setId($this->id);
        $this->setIdInventarioProduto(isset($parmPost['idInventarioProduto']) ? $parmPost['idInventarioProduto'] : '');
        $this->setReferencia(isset($parmPost['referencia']) ? $parmPost['referencia'] : '');
        $this->setCodigoProduto(isset($parmPost['codProduto']) ? $parmPost['codProduto'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto_id']) ? $parmPost['centroCusto_id'] : $this->m_empresacentrocusto);
        $this->setQuantidade(isset($parmPost['quantidade']) ? $parmPost['quantidade'] : '');
        $this->setQuantAnterior(isset($parmPost['quantAnterior']) ? $parmPost['quantAnterior'] : '');
        $this->setPrecoCustoNovo(isset($parmPost['precoCustoNovo']) ? $parmPost['precoCustoNovo'] : '');
        $this->setPrecoCusto(isset($parmPost['precoCusto']) ? $parmPost['precoCusto'] : '');
        $this->setUsuario(isset($parmPost['usuario']) ? $parmPost['usuario'] : '');
        $this->setStatus(isset($parmPost['status']) ? $parmPost['status'] : '');
    
            
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
            case 'destaqueImagem':
                $tipoMsg = 'sucesso';
                $this->destaqueImagemProdutoNao($this->getIdInventarioProduto());
                $errMSG = $this->destaqueImagemProduto($this->m_idImagem, $this->m_destaque);
                if ($errMSG != ''){
                    $tipoMsg = 'erro';
                }
                $this->cadastraImagemInventario($errMSG, $tipoMsg);
            break;
            case 'excluiImagem':
                $tipoMsg = 'erro';
                $errMSG = $this->excluiImagemProduto($this->m_idImagem);
                if ($errMSG == ''){
                    unlink('images/doc/inv/'.$this->getId().'/'.$this->m_idImagem.'.jpg');
                    $tipoMsg = 'sucesso';
                }
                $this->cadastraImagemInventario($errMSG, $tipoMsg);
            break;
            case 'salvarImagem':
                if($this->select_inventario_produto_imagem()){
                    $idImagem = $this->gravaImagemProduto($this->getIdInventarioProduto(), 'INV', 'N');
                }else{
                    $idImagem = $this->gravaImagemProduto($this->getIdInventarioProduto(), 'INV', 'S');
                }
            //$tipoMsg = "sucesso";
            if ($idImagem > 0){

                $imgFile = $_FILES['user_image']['name'];
                $tmp_dir = $_FILES['user_image']['tmp_name'];
                $imgSize = $_FILES['user_image']['size'];

                if(empty($imgFile) and (is_file($this->m_tmp))){
                        $errMSG = "Selecione uma imagem.";
                }else{
                        $upload_dir = ADMraizCliente . "/images/doc/inv/".$this->getIdInventarioProduto()."/"; // upload directory
                        
                        if (!file_exists($upload_dir)){
                            mkdir($upload_dir, 0777, true);
                        }
                       

                        $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension

                        // valid image extensions
                        $valid_extensions = array('jpeg', 'jpg'); // valid extensions

                        // rename uploading image
                        $anunciopic = $idImagem.".jpg";

                        $tipoMsg = "sucesso";
                        // allow valid image file formats
                        if(in_array($imgExt, $valid_extensions)){
                                // Check file size '2MB'
                                if($imgSize < 2000000){
                                    try {
                                        if (!move_uploaded_file($tmp_dir,$upload_dir.$anunciopic)){
                                            //throw new RuntimeException('Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.');                                                    
                                            $errMSG = "Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.";
                                            $tipoMsg = "erro";
                                        }
                                    } catch (Error $e) {
                                        throw new Exception($e->getMessage()."Imagem não salva " );

                                    }                
                                }else{
                                        $errMSG = "Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.";
                                        $tipoMsg = "erro";
                                }
                        
                        }else{
                                $errMSG = "Desculpe, inserir somente arquivo JPG, JPEG são permitidos.";		
                                $tipoMsg = "erro";
                        }
                }
            
                if ($tipoMsg != "sucesso"){
                    $this->excluiImagemProduto($idImagem);
                }
            }else{
                    $errMSG = "Imagem não foi salva.";
                    $tipoMsg = "erro";
            }
            
            $this->cadastraImagemInventario($errMSG, $tipoMsg);
            
            break;
            case 'cadastrarImagem':
			if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')){
				$this->cadastraImagemInventario();
			}
			break;
            case 'inclui':
               $idGerado = $this->incluiInventario();
               $this->setId($idGerado); 
               $this->mostraInventarioProduto('');
            break;
            case 'alterar':
                $this->mostraInventarioProduto('');
            break;
            case 'alteraProdInventario':
                $this->alteraInventarioProduto($this->m_dadosInventario);
                $this->m_submenu = 'alterar';
                $this->mostraInventarioProduto('Produto(s) alterado.', 'sucesso');
            break;
            case 'excluiProdInventario':
                $this->excluirInventarioProduto($this->getIdInventarioProduto());
                $this->m_submenu = 'alterar';
                $this->mostraInventarioProduto('Produto(s) excluido.', 'sucesso');
            break;
            case 'pesquisaItensInventarioModal':
                $this->retornaPesquisaItensInventarioModal($this->filtros_modal);
                break;
            case 'incluiItensInventarioModal':
                $this->incluiItensInventarioModal($this->id, $this->itens);
                break;
            case 'excluirInventario':
                $msg = $this->excluirInventarioCompleto($this->id);
                $tipoMsg = ($msg == '') ? 'sucesso' : 'erro';
                $this->mostraInventario($msg, $tipoMsg);
                break;
            case 'gerarInventarioAjax':
                $centroCusto = $this->m_empresacentrocusto;
                $inventarioToolsObj = new c_inventario_tools();
                $msg = '';
                $idNfEntrada = null;
                $idNfSaida = null;
                $status = 'error';
                $tipoMsg = 'erro';

                if($this->m_dadosInventarioEnt != ''){
                    $idNfEntrada = $inventarioToolsObj->insereNfInventario($this->m_dadosInventarioEnt, $this->getId(), 0, $centroCusto);
                    $msg .= " Id Nf Ent - ".$idNfEntrada;
                }
                if($this->m_dadosInventarioSaida != ''){
                    $idNfSaida = $inventarioToolsObj->insereNfInventario($this->m_dadosInventarioSaida, $this->getId(), 1, $centroCusto);
                    $msg .= " Id Nf Sai - ".$idNfSaida;
                }
                if($idNfEntrada !== null || $idNfSaida !== null){
                    $this->alteraStatusInventario('B',$this->getId());
                    $tipoMsg = 'sucesso';
                    $mensagem = 'Inventário Gerado. '.$msg;
                } else {
                    $mensagem = 'Nenhuma nota fiscal foi gerada. Verifique os dados do inventário.';
                }
                
                $this->mostraInventarioProduto($mensagem, $tipoMsg);
                exit; 
            default:
                $this->mostraInventario('');
               
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------

    function mostraInventario($mensagem, $tipoMsg = NULL) {
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('centroCusto_id', $this->m_empresacentrocusto);
        $this->smarty->assign('centroCusto_nome', $this->m_empresafantasia);
         
    if($this->data_ini == ""){ 
        $this->smarty->assign('dataIni', date("01/m/Y"));
        $this->data_ini = date("01/m/Y");
    } else {
        $this->smarty->assign('dataIni', $this->data_ini);
    }
    
    if($this->data_fim == "") {
    	$dia = date("d");
    	$mes = date("m");
    	$ano = date("Y");
    	$this->data_fim = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
    	$this->smarty->assign('dataFim', $this->data_fim);
    } else {
        $this->smarty->assign('dataFim', $this->data_fim);
    }

        // Se não houver filtro, mostrar apenas inventários em aberto
        if ($this->m_letra == '' || $this->m_letra == null) {
            $this->m_letra = $this->data_ini.'|'.$this->data_fim.'|A'; // status 'A' = Aberto
        }
        
        if($this->m_letra != ''){
            $lanc = $this->select_inventario_letra($this->m_letra);
        }
        
        $this->smarty->assign('lanc', $lanc);

        //Status
        $status_ids[0] = 'A';
        $status_names[0] = 'Aberto';
        $status_ids[1] = 'B';
        $status_names[1] = 'Baixado';
        $this->smarty->assign('status_ids',   $status_ids);
        $this->smarty->assign('status_names', $status_names);  
        if($this->getStatus() != ''){
            $this->smarty->assign('status_id', $this->getStatus());
        } 

        // CENTRO DE CUSTO
        $sql = "select CENTROCUSTO AS id, descricao from FIN_CENTRO_CUSTO";
        $this->comboSql($sql, $this->m_empresacentrocusto, $ccusto_id, $ccusto_ids, $ccusto_names);
        $this->smarty->assign('centroCusto_ids',   $ccusto_ids);
        $this->smarty->assign('centroCusto_names', $ccusto_names);

        // GRUPO       
        $sql = "select grupo id, descricao from est_grupo";
        $this->comboSql($sql, $this->m_par[1], $grupo_id, $grupo_ids, $grupo_names);

        // Adiciona a opção vazia na primeira posição
        array_unshift($grupo_ids, '');
        array_unshift($grupo_names, '');

        // Atribui ao Smarty
        $this->smarty->assign('grupo_id', $grupo_id);
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);


        $this->smarty->display('inventario_mostra.tpl');
    }

    function mostraInventarioProduto($mensagem, $tipoMsg = NULL) {
        
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('id', $this->getId());

        $this->m_tela == '' ? $this->m_tela = 'inventarioProduto' : $this->m_tela;
        $this->m_btnGerarInventario == '' ? $this->m_btnGerarInventario = 'false' :$this->m_btnGerarInventario;
        $this->smarty->assign('tela', $this->m_tela);

        if($this->getId() != ''){
            //Verifica statusInventario
            $con = new c_banco();
            $con->setTab("EST_INVENTARIO");
            $result = $con->getField("STATUS", "ID = ".$this->getId());
            $con->close_connection();
            if($result == 'B'){
                $this->m_btnGerarInventario = 'disabled';
            }
            
            // Buscar dados do inventário para pegar a referência
            $dadosInventario = $this->select_inventario();
            if($dadosInventario && count($dadosInventario) > 0) {
                $this->smarty->assign('referencia', $dadosInventario[0]['REFERENCIA']);
                $this->smarty->assign('status', $dadosInventario[0]['STATUS']);
            } else {
                $this->smarty->assign('referencia', '');
                $this->smarty->assign('status', '');
            }
        }

        $this->smarty->assign('btnAddInventario', false);
        $this->smarty->assign('gerarInventario', $this->m_btnGerarInventario);


        if($this->getId() != ''){
            $produto = $this->select_inventario_produto_letra() ?? [];
        }
        if(is_array($produto)){
            $this->smarty->assign('btnAddInventario', true);
        }

        $resultProduto = [];
        $p = 0;
        $classProdutoQtde = new c_produto_estoque();
        for($i=0;$i<count($produto);$i++){
            //CODPRODUTO PARA MAXI E HIPER, OUTRAS CODIGO
           // $produtoQuant = $classProdutoQtde->produtoQtde($produto[$i]['CODIGO'], $this->m_empresacentrocusto);
            $produtoQuant = $classProdutoQtde->produtoQtdeInventario($produto[$i]['CODPRODUTO'], $this->m_empresacentrocusto);
            $produto[$i]['ESTOQUE'] = 0;
            $produto[$i]['RESERVA'] = 0;
            for($q=0;$q<count($produtoQuant ?? []);$q++){
                if ($produtoQuant[$q]['STATUS'] == 0):
                        $produto[$i]['ESTOQUE'] = $produtoQuant[$q]['QUANTIDADE'];
                else:    
                        $produto[$i]['RESERVA'] = $produtoQuant[$q]['QUANTIDADE'];
                endif;
                //$produto[$i]['CENTROCUSTO'] = $produtoQuant[$q]['CCUSTO'];
            }    
            $resultProduto[$p] = $produto[$i];
            $p++;
        }
        
        $this->smarty->assign('lancProduto', $resultProduto);

        //Status
        $status_ids[0] = 'A';
        $status_names[0] = 'Aberto';
        $status_ids[1] = 'B';
        $status_names[1] = 'Baixado';
        $this->smarty->assign('status_ids',   $status_ids);
        $this->smarty->assign('status_names', $status_names);   

        // CENTRO DE CUSTO
        $sql = "select CENTROCUSTO AS id, descricao from FIN_CENTRO_CUSTO";
        $this->comboSql($sql, $this->m_empresacentrocusto, $ccusto_id, $ccusto_ids, $ccusto_names);
        $this->smarty->assign('centroCusto_ids',   $ccusto_ids);
        $this->smarty->assign('centroCusto_names', $ccusto_names);

        // GRUPO       
        $sql = "select grupo id, descricao from est_grupo";
        $this->comboSql($sql, $this->m_par[1], $grupo_id, $grupo_ids, $grupo_names);
        array_unshift($grupo_ids, '');
        array_unshift($grupo_names, 'Selecione uma opcao');
        $this->smarty->assign('grupo_id', $grupo_id);
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);


        $this->smarty->display('inventario_produto_mostra.tpl');
    }


    function cadastraImagemInventario($mensagem = NULL, $tipoMsg = null){

        $lanc = $this->select_inventario_produto_imagem($this->getIdInventarioProduto());

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathJs', ADMhttpBib.'/js');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());

        if($this->getId() != ''){
            //Verifica statusInventario
            $con = new c_banco();
            $con->setTab("EST_INVENTARIO");
            $result = $con->getField("STATUS", "ID = ".$this->getId());
            $con->close_connection();
            if($result == 'B'){
                $btnDisabled = 'disabled';
            }
        }

        $this->smarty->assign('btnDisabled', $btnDisabled);

        $this->smarty->assign('idInventarioProduto', $this->getIdInventarioProduto());

        $this->smarty->assign('titulo', "'".$this->m_titulo."'");

        $this->smarty->assign('lanc', $lanc);
        
        $this->smarty->display('inventario_imagem_mostra.tpl');    
        
    }    

//fim mostraBaixaEstoques
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$consultas = new p_inventario();

$consultas->controle();
?>
