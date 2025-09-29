<?php
/**
 * @package   astec
 * @name      p_rel_compras
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon K S Mello<jhon.kened11@gmail.com>
 * @date      21/12/2024
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit; endif;

$dir = dirname(__FILE__);

require_once($dir."/../../../smarty/libs/Smarty.class.php");
require_once($dir."/../../class/ped/c_pedido_venda.php");


Class p_pedido_relatorios extends c_pedidoVenda {
    
    private $m_submenu = null;
    private $m_letra   = null;
    private $m_opcao   = null;

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
        $this->smarty->template_dir = ADMraizFonte . "/template/ped";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
		        
        $this->m_par = explode("|", $this->m_letra);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('admClass', ADMclass);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Produtos");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5 ]"); 
        $this->smarty->assign('disableSort', "[ 5 ]"); 
        $this->smarty->assign('numLine', "25"); 


        if($this->m_par[6] == "") {
            $this->smarty->assign('dataIni', date("01/m/Y"));
        } else {
            $this->smarty->assign('dataIni', $this->m_par[6]);
        }
    
        if($this->m_par[7] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        } else{
            $this->smarty->assign('dataFim', $this->m_par[7]);
        } 
    }

    //---------------------------------------------------------------
    function controle() 
    {
        switch ($this->m_submenu) {
           case 'relatorio': 
                $this->smarty->template_dir = ADMraizCliente . "/template/est";
                $this->mostraRelatorio('');
           break;
            default:
                //if ($this->verificaDireitoUsuario('EstRelCompras', 'R')) {
                    $this->mostraRelatorio('');
                //}
        }
    }

    // fim controle
    //---------------------------------------------------------------

    function mostraRelatorio($mensagem) {

        if ((isset($this->m_letra)) && $this->m_letra != '') {
            //$produto = $this->select_produto_letra($this->m_letra);
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
        $classProdutoQtde = new c_produto_estoque();
        for($i=0;$i<count($produto);$i++){
            $produtoQuant = $classProdutoQtde->produtoQtdePeriodo($produto[$i]['CODIGO'], $this->m_empresacentrocusto);
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

        $this->smarty->display('relatorios.tpl');
    }

//fim relatorios
//-------------------------------------------------------------
}

$produto = new p_pedido_relatorios();

$produto->controle();

?>
