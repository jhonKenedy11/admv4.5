<?php


if (!defined('ADMpath')): exit;
endif;
$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/est/c_tabela_preco_item.php");
include_once(ADMraizFonte . "/bib/reader.php");


Class p_tabela_preco_item extends c_tabela_preco_item {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    
    function __construct() {
        
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        session_start();
        c_user::from_array($_SESSION['user_array']);

        $this->smarty = new Smarty;

        $this->smarty->template_dir = ADMraizFonte . "/template/est";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : '';
        $this->m_letra = isset($parmPost['letra']) ? $parmPost['letra'] : '';

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');

        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Classe");
        $this->smarty->assign('colVis', "[ 0, 1, 2 ]"); 
        $this->smarty->assign('disableSort', "[ 2 ]"); 
        $this->smarty->assign('numLine', "25"); 
        
        $this->import_preview = isset($parmPost['import_preview']) ? $parmPost['import_preview'] : array();
        $this->codigo_override = isset($parmPost['codigo_override']) ? $parmPost['codigo_override'] : array();
        $this->codigo_fabricante_override = isset($parmPost['codigo_fabricante_override']) ? $parmPost['codigo_fabricante_override'] : array();
        $this->descricao_override = isset($parmPost['descricao_override']) ? $parmPost['descricao_override'] : array();
        $this->grupo_override = isset($parmPost['grupo_override']) ? $parmPost['grupo_override'] : array();
        $this->marca_override = isset($parmPost['marca_override']) ? $parmPost['marca_override'] : array();
        $this->precobase_override = isset($parmPost['precobase_override']) ? $parmPost['precobase_override'] : array();
        $this->margem_override = isset($parmPost['margem_override']) ? $parmPost['margem_override'] : array();

        $this->setId(isset($parmPost['id']) ? $parmPost['id'] : '');
        $this->setGrupo(isset($parmPost['grupo']) ? $parmPost['grupo'] : '');
        $this->setCodigo(isset($parmPost['codigo_produto']) ? $parmPost['codigo_produto'] : '');
        $this->setPrecoBase(isset($parmPost['precobase']) ? $parmPost['precobase'] : '');
        $this->setMargem(isset($parmPost['margem']) ? $parmPost['margem'] : '');
        $this->setPrecoFinal(isset($parmPost['precofinal']) ? $parmPost['precofinal'] : '');
        $this->setDescricao(isset($parmPost['descricao']) ? $parmPost['descricao'] : '');
        $this->setMarca(isset($parmPost['marca']) ? $parmPost['marca'] : '');
        $this->setCodFabricante(isset($parmPost['codigo_fabricante']) ? $parmPost['codigo_fabricante'] : '');
        $this->setIdTabelaPreco(isset($parmPost['id_tabela_preco']) ? $parmPost['id_tabela_preco'] : '');
        $this->setPrecoBaseAnterior(isset($parmPost['precobase_anterior']) ? $parmPost['precobase_anterior'] : '');
    }

    function controle() {
        switch ($this->m_submenu) { 
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'I')) {
                    $this->desenharCadastroTabelaPrecoItem();
                }
                break;
            case 'importar':
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'I')) {
                    $this->desenharImportacaoTabelaPrecoItem();
                }
                break;
            case 'processar_import':
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'I')) {
                    try {
                        $data = $this->verificarArquivoImportacao($_FILES['arquivo_excel']);
                        $preview = $this->montarPreviewImportacao($data);

                        $this->smarty->assign('preview', $preview);
                        $this->smarty->assign('id_tabela_preco', $this->getIdTabelaPreco());
                        list($grupo_ids, $grupo_names) = $this->combosGrupo();
                        list($marca_ids, $marca_names) = $this->combosMarca();
                        $this->smarty->assign('grupo_ids', $grupo_ids);
                        $this->smarty->assign('grupo_names', $grupo_names);
                        $this->smarty->assign('marca_ids', $marca_ids);
                        $this->smarty->assign('marca_names', $marca_names);
                        $this->smarty->display('tabela_preco_item_import_preview.tpl');
                    } catch (Exception $e) {
                        $mensagem = "Erro ao importar: " . $e->getMessage();
                        $this->desenharImportacaoTabelaPrecoItem($mensagem);
                        throw $e;
                    }
                }
                break;
            case 'confirmar_import':
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'I')) {
                    try {
                        foreach ($this->codigo_fabricante_override as $i => $codigo_fabricante_override) {

                            $this->setCodFabricante($codigo_fabricante_override);
                            $this->setCodigo($this->codigo_override[$i] ?? null);
                            $this->setDescricao($this->descricao_override[$i] ?? '');
                            $this->setMarca($this->marca_override[$i] ?? '');
                            $this->setGrupo($this->grupo_override[$i] ?? '');
                            $this->setPrecoBase((double) $this->precobase_override[$i] ?? '');
                            $this->setMargem((double) $this->margem_override[$i] ?? '');
                            
                            if($this->getMargem() !== '' ){
                                $pb = (double) $this->getPrecoBase('B');
                                $mg = (double) $this->getMargem('B');
                                $precofinal = $pb * (1 + $mg / 100);
                                $this->setPrecoFinal($precofinal);
                            } else {
                                $pb = (double) $this->getPrecoBase('B');
                                $mg = (double) $this->getMargem('B');
                                $precofinal = $pb * (1 + $mg / 100);
                                $this->setPrecoFinal($precofinal);
                            }

                            $item = $this->select_tabela_preco_item_by_codigo();

                            if ($item !== null) {
                                $this->setId($item[0]['ID']);
                                $this->setPrecoBaseAnterior($item[0]['PRECOBASE']);
                                $this->alterar_tabela_preco_item();
                            } else {
                                $this->incluir_tabela_preco_item();
                            }
                    }
                    } catch (Exception $e) {
                        $mensagem = "Erro ao importar: " . $e->getMessage();
                        $this->desenharImportacaoTabelaPrecoItem($mensagem);
                        throw $e;
                    }
                    $mensagem = "Importação concluída.";
                    $this->mostrarTabelaPrecoItem($mensagem);
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'A')) {
                    $this->desenharCadastroTabelaPrecoItem();
                }
                break;
            case 'altera':
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'A')) {
                    $this->alterar_tabela_preco_item();
                    $this->mostrarTabelaPrecoItem('Registro salvo.');
                }
                break;
            case 'exclui':
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'E')) {
                    $this->excluir_tabela_preco_item();
                    $this->mostrarTabelaPrecoItem('Registro excluido.');
                }
                break;
            case 'inclui':
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'I')) {
                    $this->incluir_tabela_preco_item();
                    $this->mostrarTabelaPrecoItem('Registro salvo.');
                }
                break;
            default:
                if ($this->verificaDireitoUsuario('TABELAPRECO', 'C')) {
                    $this->mostrarTabelaPrecoItem('');
                }
                break;
        }
    }


    function desenharCadastroTabelaPrecoItem($mensagem = NULL, $tipoMsg = null) {
        if ($this->getId() !== '' && $this->getId() !== null) {
            $lanc = $this->buscar_tabela_preco_item();
        }

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('opcao', $this->m_opcao);
        $this->smarty->assign('pesquisa', $this->m_pesq);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id_tabela_preco', $this->getIdTabelaPreco());
        $this->smarty->assign('id', $this->getId());

        //grupo
        list($grupo_ids, $grupo_names) = $this->combosGrupo();
        $this->smarty->assign('grupo', $this->getGrupo());
        $this->smarty->assign('grupo_ids', $grupo_ids);
        $this->smarty->assign('grupo_names', $grupo_names);

        //marca
        list($marca_ids, $marca_names) = $this->combosMarca();
        $this->smarty->assign('marca', $this->getMarca());
        $this->smarty->assign('marca_ids', $marca_ids);
        $this->smarty->assign('marca_names', $marca_names);

        //tabela preco
        list($tabela_ids, $tabela_names) = $this->combosTabelaPreco();
        $this->smarty->assign('tabela', $this->getIdTabelaPreco());
        $this->smarty->assign('tabela_ids', $tabela_ids);
        $this->smarty->assign('tabela_names', $tabela_names);

        $this->smarty->assign('codigo_produto', $this->getCodigo()); 
        $this->smarty->assign('codigo_fabricante', $this->getCodFabricante()); 
        $this->smarty->assign('descricao', $this->getDescricao());
        $this->smarty->assign('precofinal', $this->getPrecoFinal('F'));
        $this->smarty->assign('margem',  $this->getMargem('F'));
        $this->smarty->assign('precobase', $this->getPrecoBase('F'));
        $this->smarty->assign('precobase_anterior', $this->getPrecoBaseAnterior('F'));
        
        $this->smarty->display('tabela_preco_item_cadastro.tpl');
    }

    function desenharImportacaoTabelaPrecoItem($mensagem = NULL) {
        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('id_tabela_preco', $this->getIdTabelaPreco());
        $this->smarty->display('tabela_preco_item_import.tpl');
    }


    function mostrarTabelaPrecoItem($mensagem) {

        $itens = $this->select_tabela_preco_item_geral();

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('id_tabela_preco', $this->getIdTabelaPreco());
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $itens);

        $this->smarty->display('tabela_preco_item_mostra.tpl');
    }


}

$tabela_preco_item = new p_tabela_preco_item();

$tabela_preco_item->controle();
?>
