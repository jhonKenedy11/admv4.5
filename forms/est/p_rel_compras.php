<?php
/**
 * @package   astec
 * @name      p_rel_compras
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silvao<marcio.sergio@admservice.com.br>
 * @date      27/04/2018
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/est/c_produto.php");
require_once($dir."/../../class/est/c_produto_estoque.php");
require_once($dir."/../../class/crm/c_conta.php");

//Class P_produto
Class p_rel_compras extends c_produto {

    private $m_submenu = null;
    private $m_letra = null;
    private $m_quant = null;
    private $m_fora = null;
    private $m_opcao = null;

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
		        
        $this->m_par = explode("|", $this->m_letra);
        $this->m_quant = $quant;
        $this->m_fora = $fora;

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        if ($this->m_opcao=="pesquisar"):
            $this->smarty->assign('titulo', "Produtos");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
            $this->smarty->assign('disableSort', "[ 5 ]"); 
            $this->smarty->assign('numLine', "25"); 
        else:
            $this->smarty->assign('titulo', "Produtos");
            $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7,8 ]"); 
            $this->smarty->assign('disableSort', "[ 0 ]"); 
            $this->smarty->assign('numLine', "25"); 
        endif;
    
        

        
        
        // include do javascript
        // include ADMjs . "/est/s_est.js";
    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_submenu) {
           case 'consulta':
                // if ($this->verificaDireitoUsuario('EstRelCompras', 'C')) {
                    $classProdutoQtde = new c_produto_estoque();
                    $quant[] = array(
                            'saldo'	=> $classProdutoQtde->select_quantidade_empresa(
                                $this->getId(), $this->m_empresacentrocusto, ''),
                    );
                
                    echo( json_encode( $quant ) );
                //}
                break;
            default:
                //if ($this->verificaDireitoUsuario('EstRelCompras', 'R')) {
                    $this->mostraProduto('');
                //}
        } //switch
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraProduto($mensagem) {

        if ((isset($this->m_letra)) && $this->m_letra != '') {
            $produto = $this->select_produto_letra($this->m_letra);
        }
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('pesquisa', $this->m_pesq);

        $this->smarty->assign('produtoNome', $this->m_par[0]);
        $this->smarty->assign('codFabricante', $this->m_par[2]);
        $this->smarty->assign('localizacao', $this->m_par[3]);
        $this->smarty->assign('quant', $this->m_par[4]);
        

        // tipo de Select
        //**** estoque ****
        $estoque_ids[0] = 'T';
        $estoque_names[0] = 'Todos';
        $estoque_ids[1] = 'S';
        $estoque_names[1] = 'Com Saldo';
        $estoque_ids[2] = 'N';
        $estoque_names[2] = 'Sem Saldo';
        $this->smarty->assign('estoque_ids', $estoque_ids);
        $this->smarty->assign('estoque_names', $estoque_names);
        if ($this->m_par[4] == '') {
            $this->smarty->assign('estoque_id', 'S');
        } else {
            $this->smarty->assign('estoque_id', $this->m_par[4]);
        }
        //****** fim estoque ******


        for ($i = 'A'; $i < 'Z'; $i++) {
            $arrayLetra[$i] = $i;
        }
        $this->smarty->assign('arrayLetra', $arrayLetra);

        // GRUPO
        $consulta = new c_banco();
        $sql = "select grupo id, descricao from est_grupo";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $grupo_ids[0] = '';
        $grupo_names[0] = 'Selecione Grupo';
        for ($i = 0; $i < count($result); $i++) {
            $grupo_ids[$i + 1] = $result[$i]['ID'];
            $grupo_names[$i + 1] = $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);
        if ($this->m_par[1] == "")
            $this->smarty->assign('grupo_id', 'Todos');
        else
            $this->smarty->assign('grupo_id', $this->m_par[1]);

        $resultProduto = [];
        $p = 0;
        $produto = $produto ?? [];
        $classProdutoQtde = new c_produto_estoque();
        for($i=0;$i<count($produto);$i++){
            $produtoQuant = $classProdutoQtde->produtoQtde($produto[$i]['CODIGO'], $this->m_empresacentrocusto) ?? [];
            $produto[$i]['ESTOQUE'] = 0;
            $produto[$i]['RESERVA'] = 0;
            for($q=0;$q<count($produtoQuant);$q++){
                if ($produtoQuant[$q]['STATUS'] == 0):
                        $produto[$i]['ESTOQUE'] = $produtoQuant[$q]['QUANTIDADE'];
                else:    
                        $produto[$i]['RESERVA'] = $produtoQuant[$q]['QUANTIDADE'];
                endif;
            }    
            $resultProduto[$p] = $produto[$i];
            $p++;
        }    
            
       
        $this->smarty->assign('lanc', $resultProduto);

        $this->smarty->display('rel_compras.tpl');
    }


//fim mostraProdutos
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
//$produto = new p_rel_compras(isset($parmPost['id']) ? $parmPost['id'] : '''submenu'], $_POST['letra'], $_POST['quant'], $_POST['acao'], $_REQUEST['pesquisa'], $_POST['opcao'], $_POST['loc'], $_POST['ns'], $_POST['idNF']);
$produto = new p_rel_compras();

$produto->controle();
?>
