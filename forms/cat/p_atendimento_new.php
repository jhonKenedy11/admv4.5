<?php

/**
 * @package   astec
 * @name      p_atendimento_new_venda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Maárcio Sérgio da Silva<marcio.sergio@admservice.com.br>
 * @date      29/06/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/cat/c_atendimento.php");
require_once($dir . "/../../class/cat/c_atendimento_new_tools.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");

//Class P_situacao
class p_atendimento_new extends c_atendimento
{

    private $m_submenu      = NULL;
    private $m_letra        = NULL;
    private $m_pesq         = NULL;
    private $m_par          = NULL;
    private $m_vlrVisita    = NULL;
    private $m_vlrDesconto  = NULL;
    private $m_situacoesAtendimento  = NULL;
    public $smarty          = NULL;
    public $m_letra_peca    = NULL;
    public $m_letra_servico = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct()
    {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet  = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        //$parmSession = filter_input_array(INPUT_SESSION, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // ajax
        $this->ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;

        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/cat";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_idOs = (isset($parmGet['idOs']) ? $parmGet['idOs'] : (isset($parmPost['idOs']) ? $parmPost['idOs'] : ''));
        $this->m_idImg = (isset($parmGet['idImg']) ? $parmGet['idImg'] : (isset($parmPost['idImg']) ? $parmPost['idImg'] : ''));
        $this->m_table = (isset($parmGet['table']) ? $parmGet['table'] : (isset($parmPost['table']) ? $parmPost['table'] : ''));
        $this->m_table_id = (isset($parmGet['table_id']) ? $parmGet['table_id'] : (isset($parmPost['table_id']) ? $parmPost['table_id'] : ''));
        $this->m_path = (isset($parmGet['path']) ? $parmGet['path'] : (isset($parmPost['path']) ? $parmPost['path'] : ''));
        $this->m_pesq = $parmPost['pesq'];

        $this->m_vlrVisita   = $parmPost['valorVisita'];
        $this->m_vlrDesconto = $parmPost['valorDesconto'];

        $this->m_letra = $parmPost['letra'];
        $this->m_letra_peca    = $parmPost['letra_peca'];
        $this->m_letra_servico = $parmPost['letra_servico'];
        $this->m_situacoesAtendimento = $parmPost['situacoesAtendimento'];

        $this->m_par = explode("|", $this->m_letra);
        $this->m_par_peca = explode("|", $this->m_letra_peca);
        $this->m_par_servico = explode("|", $this->m_letra_servico);

        $this->setEquipeId(isset($parmPost['equipeId']) ? $parmPost['equipeId'] : '');
        $this->setUsuarioEquipe(isset($parmPost['usuario_equipe']) ? $parmPost['usuario_equipe'] : []);

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);
        $this->smarty->assign('pathSweet',  ADMhttpCliente . '/../sweetalert2');


        // dados para exportacao e relatorios
        $this->smarty->assign('titulo', "Ordem de Serviços");
        $this->smarty->assign('colVis', "[ 0,1,2,3,4,5,6,7 ]");
        $this->smarty->assign('disableSort', "[ 7 ]");
        $this->smarty->assign('numLine', "25");

        // metodo SET dos dados do FORM para o TABLE
        $this->setId(isset($parmGet['id']) ? $parmGet['id'] : (isset($parmPost['id']) ? $parmPost['id'] : ''));
        $this->setAtendimento(isset($parmPost['atendimento']) ? $parmPost['atendimento'] : '');
        $this->setCliente(isset($parmPost['pessoa']) ? $parmPost['pessoa'] : '');
        $this->setContato(isset($parmPost['contato']) ? $parmPost['contato'] : '');
        $this->setDataAberturaEnd(isset($parmPost['dataAbertura']) ? $parmPost['dataAbertura'] : '');
        $this->setDataFechamentoEnd(isset($parmPost['dataFechamentoEnd']) ? $parmPost['dataFechamentoEnd'] : '');
        $this->setUsrAbertura(isset($parmPost['usrAbertura']) ? $parmPost['usrAbertura'] : '');
        $this->setPrioridade(isset($parmPost['prioridade']) ? $parmPost['prioridade'] : '');
        $this->setPrazoEntrega(isset($parmPost['prazoEntrega']) ? $parmPost['prazoEntrega'] : '');
        $this->setDescEquipamento(isset($parmPost['descEquipamento']) ? $parmPost['descEquipamento'] : '');
        $this->setKmEntrada(isset($parmPost['kmEntrada']) ? $parmPost['kmEntrada'] : '');
        $this->setObs(isset($parmPost['obs']) ? $parmPost['obs'] : '');
        $this->setObsServicos(isset($parmPost['obsServicos']) ? $parmPost['obsServicos'] : '');
        $this->setSolucao(isset($parmPost['solucao']) ? $parmPost['solucao'] : '');
        $this->setValorPecas(isset($parmPost['valorPecas']) ? $parmPost['valorPecas'] : 0);
        $this->setValorServicos(isset($parmPost['valorServicos']) ? $parmPost['valorServicos'] : 0);
        $this->setValorVisita(isset($parmPost['valorVisita']) ? $parmPost['valorVisita'] : 0);
        $this->setValorDesconto(isset($parmPost['valorDesconto']) ? $parmPost['valorDesconto'] : 0);
        $this->setValorTotal(isset($parmPost['valorTotal']) ? $parmPost['valorTotal'] : 0);
        $this->setTipoCobranca(isset($parmPost['tipoCobranca']) ? $parmPost['tipoCobranca'] : '');
        $this->setCondPgto(isset($parmPost['condPgto']) ? $parmPost['condPgto'] : '');
        $this->setConta(isset($parmPost['conta']) ? $parmPost['conta'] : '');
        $this->setGenero(isset($parmPost['genero']) ? $parmPost['genero'] : '');
        $this->setCentroCusto(isset($parmPost['centroCusto']) ? $parmPost['centroCusto'] : $this->m_empresacentrocusto);
        $this->setSituacao(isset($parmPost['situacao']) ? $parmPost['situacao'] : '');
        $this->setCatEquipamentoId(isset($parmPost['catEquipamentoId']) ? $parmPost['catEquipamentoId'] : '');
        $this->setCatTipoId(isset($parmPost['catTipoId']) ? $parmPost['catTipoId'] : '');

        //=========================PECAS==================================
        $this->setIdPecas(isset($parmPost['idPecas']) ? $parmPost['idPecas'] : '');
        $this->setIdAtendimentoPecas(isset($parmPost['idAtendimentoPecas']) ? $parmPost['idAtendimentoPecas'] : '');
        $this->setCodProduto(isset($parmPost['codProduto']) ? $parmPost['codProduto'] : '');
        $this->setCodProdutoNota(isset($parmPost['codProdutoNota']) ? $parmPost['codProdutoNota'] : '');
        $this->setQuantidadePecas(isset($parmPost['quantidadePecas']) ? $parmPost['quantidadePecas'] : '0,00');
        $this->setUnidadePecas(isset($parmPost['uniProduto']) ? $parmPost['uniProduto'] : '');
        $this->setVlrUnitarioPecas(isset($parmPost['vlrUnitarioPecas']) ? $parmPost['vlrUnitarioPecas'] : '');
        $this->setDescricaoPecas(isset($parmPost['descProduto']) ? $parmPost['descProduto'] : '');
        $this->setVlrCustoPecas(isset($parmPost['vlrCustoPecas']) ? $parmPost['vlrCustoPecas'] : '');
        $this->setDescontoPecas(isset($parmPost['vlrDescontoPecas']) ? $parmPost['vlrDescontoPecas'] : '');
        $this->setPercDescontoPecas(isset($parmPost['percDescontoPecas']) ? $parmPost['percDescontoPecas'] : '');
        $this->setAcrescimoPecas(isset($parmPost['acrescimoPecas']) ? $parmPost['acrescimoPecas'] : '');
        $this->setTotalPecas(isset($parmPost['totalPecas']) ? $parmPost['totalPecas'] : '');

        //==========================SERVICOS=======================
        $this->setIdServico(isset($parmPost['idServicos']) ? $parmPost['idServicos'] : '');
        $this->setIdAtendimentoServico(isset($parmPost['idAtendimentoServicos']) ? $parmPost['idAtendimentoServicos'] : '');
        $this->setIdUser(isset($parmPost['idUser']) ? $parmPost['idUser'] : '');
        $this->setDataServico(isset($parmPost['dataServico']) ? $parmPost['dataServico'] : '');
        $this->setHoraIniServico(isset($parmPost['horaIni']) ? $parmPost['horaIni'] : '');
        $this->setHoraFimServico(isset($parmPost['horaFim']) ? $parmPost['horaFim'] : '');
        $this->setQuantidadeServico(isset($parmPost['quantidadeServico']) ? $parmPost['quantidadeServico'] : 0);
        $this->setUnidadeServico(isset($parmPost['unidadeServico']) ? $parmPost['unidadeServico'] : '');
        $this->setVlrUnitarioServico(isset($parmPost['vlrUnitarioServico']) ? $parmPost['vlrUnitarioServico'] : '');
        $this->setHoraTotalServico(isset($parmPost['horaTotalServico']) ? $parmPost['horaTotalServico'] : '');
        $this->setCustoUser(isset($parmPost['custoUser']) ? $parmPost['custoUser'] : '');
        $this->setDescricaoServico(isset($parmPost['descricaoServico']) ? $parmPost['descricaoServico'] : '');
        $this->setTotalServico(isset($parmPost['totalServico']) ? $parmPost['totalServico'] : 0);
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
            case 'cadastrar':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    $this->buscaParametros();
                    $this->desenhaCadastroAtendimento();
                }
                break;
            case 'alterar':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'A')) {
                    $this->buscaAtendimento();
                    $testeSit = $this->getSituacao();
                    if ($this->getId() > 0) {
                        $this->desenhaCadastroAtendimento();
                    } else {
                        $this->mostraAtendimento('Atendimento não pode ser alterado.');
                    }
                }
                break;
            case 'inclui': // CONCLUIR
                if ($this->verificaDireitoUsuario('CatAtendimento', 'A')) {
                    if ($this->getId() > 0) {
                        $this->alteraAtendimento();
                        $result = $this->getValorTotal();
                        $this->updateField("VALORTOTAL", $result, "CAT_ATENDIMENTO");
                        $this->mostraAtendimento('Registro Salvo - ' . $this->getId());
                    } else {
                        $idInsert = $this->incluiAtendimento();
                        if (is_numeric($idInsert))
                            $this->mostraAtendimento('Registro Salvo- ' . $idInsert);
                        else
                            $this->mostraAtendimento($idInsert);
                    }
                }
                break;
            case 'altera': // CONCLUIR
                if ($this->verificaDireitoUsuario('CatAtendimento', 'A')) {
                    $this->alteraAtendimento();

                    // Atualiza equipe se necessário
                    if ($_POST['atualiza_equipe'] == 'true') {
                        $equipeId = isset($_POST['equipeId']) ? $_POST['equipeId'] : null;
                        $usuarioEquipe = isset($_POST['usuario_equipe']) ? $_POST['usuario_equipe'] : [];
                        $msgEquipe = $this->atualizaEquipeAtendimento($this->getId(), $equipeId, $usuarioEquipe);

                        if ($msgEquipe != '') {
                            $this->desenhaCadastroAtendimento($msgEquipe, 'erro');
                            return;
                        }
                    }

                    $result = $this->getValorTotal();
                    $this->updateField("VALORTOTAL", $result, "CAT_ATENDIMENTO");
                    $this->mostraAtendimento('Registro Salvo.');
                }
                break;
            case 'digita': //VOLTAR
                if ($this->verificaDireitoUsuario('CatAtendimento', 'C')) {
                    $this->mostraAtendimento('');
                }
                break;
            case 'cancela': // CANCELA
                if ($this->verificaDireitoUsuario('CatAtendimento', 'E')) {
                    // 8 - CAT_SITUACAO  = CANCELADO
                    $this->updateField("CAT_SITUACAO_ID", 8, "CAT_ATENDIMENTO");
                    $this->mostraAtendimento('Atendimento Cacelado');
                }
                break;
            case 'recalcularDesconto':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    $idAtendimento = $this->getId();
                    $novoDescontoAtendimento = $this->getValorDesconto();
                    $objatendimentoTools = new c_atendimentoTools();
                    $msg = $objatendimentoTools->recalcularDescontoPecas($idAtendimento, $novoDescontoAtendimento);
                    $this->desenhaCadastroAtendimento($msg);
                }
                break;
            case 'duplicaOs':
                if ($this->verificaDireitoUsuario('CatAtendimento', 'I')) {
                    $idAntigo = $this->getId();
                    $idGerado = $this->duplicaOs();
                    $this->setId($idGerado);
                    // $this->atualizarField('PEDIDO', $idGerado);                   
                    $this->duplicaPecas($idGerado, $idAntigo);
                    $this->duplicaServicos($idGerado, $idAntigo);
                    $this->buscaAtendimento();
                    $this->m_submenu = 'alterar';
                    $this->desenhaCadastroAtendimento("Duplicação OS realizado com sucesso");
                }
                break;
            case 'cadastrarImagem':
                if ($this->verificaDireitoUsuario('EstItemEstoque', 'I')) {
                    $this->setId($this->m_idOs);
                    $this->cadastraImagemOS();
                }
                break;
            case 'excluiImagem':
                $tipoMsg = 'erro';
                $errMSG = $this->excluiImagemOS($this->m_idImg);
                if ($errMSG == ''):
                    unlink($this->m_path);
                    $tipoMsg = 'sucesso';
                endif;
                $this->cadastraImagemOS($errMSG, $tipoMsg);
                break;
            case 'estornaOs':

                $errMSG = $this->estornaOs($this->getId());
                if ($errMSG == true) {
                    $mensagem = 'Ordem de serviço estornada!';
                    $tipoMsg = 'sucesso';
                } else {
                    $tipoMsg = 'error';
                    $mensagem = 'Ocorreu um erro ao estorna OS, entre em contato com o suporte!';
                }
                $this->mostraAtendimento($tipoMsg, $mensagem);
                break;
            case 'salvarImagem':
                $imgFile = $_FILES['user_image']['name'];
                $tmp_dir = $_FILES['user_image']['tmp_name'];
                $imgSize = $_FILES['user_image']['size'];

                if ($imgSize > 0):

                    if (empty($imgFile) and (is_file($this->m_tmp))):
                        $errMSG = "Selecione uma imagem.";
                    else:
                        $upload_dir = "/ged/cat_atendimento/" . $this->getId() . "/"; // upload directory

                        if (!file_exists(ADMraizCliente . $upload_dir)) {
                            mkdir(ADMraizCliente . $upload_dir, 0777, true);
                        }

                        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

                        // valid image extensions
                        $valid_extensions = array('jpeg', 'jpg'); // valid extensions

                        if ($this->select_os_imagem())
                            $idImagem = $this->gravaImagemOS($this->getId(), $upload_dir . $imgFile, 'N');
                        else
                            $idImagem = $this->gravaImagemOS($this->getId(), $upload_dir . $imgFile, 'S');

                        $tipoMsg = "sucesso";
                        // allow valid image file formats
                        if (in_array($imgExt, $valid_extensions)):
                            // Check file size '2MB'
                            if ($imgSize < 2000000):
                                try {
                                    if (!move_uploaded_file($tmp_dir, ADMraizCliente . $upload_dir . $imgFile)):
                                        //throw new RuntimeException('Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.');                                                    
                                        $errMSG = "Desculpe, erro ao mover o arquivo para o diretório local.";
                                        $tipoMsg = "erro";
                                    endif;
                                } catch (Error $e) {
                                    throw new Exception($e->getMessage() . "Imagem não salva ");
                                }

                            else:
                                $errMSG = "Desculpe, seu arquivo é muito grande, tamanho máximo 2MB.";
                                $tipoMsg = "erro";
                            endif;

                        else:
                            $errMSG = "Desculpe, inserir somente arquivo JPG, JPEG são permitidos.";
                            $tipoMsg = "erro";
                        endif;
                    endif;

                    if ($tipoMsg != "sucesso"):
                        $this->excluiImagemOS($idImagem);
                    endif;
                else:
                    $errMSG = "Imagem não foi salva.";
                    $tipoMsg = "erro";
                endif;
                //$this->setId($this->m_idOs);
                $this->cadastraImagemOS($errMSG, $tipoMsg);

                break;
            default:
                if ($this->verificaDireitoUsuario('CatAtendimento', 'C')) {
                    $this->mostraAtendimento('');
                }
        }
    }

    function desenhaCadastroAtendimento($mensagem = NULL, $tipoMsg = NULL)
    {


        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('pesq', $this->m_pesq);

        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);

        $this->smarty->assign('id', $this->getId());
        $this->smarty->assign('pessoa', $this->getCliente());
        if ($this->getCliente() != ''):
            $this->setClienteNome();
            $this->smarty->assign('nome', $this->getClienteNome());
        endif;
        $this->smarty->assign('contato', $this->getContato());
        $this->smarty->assign('atendimento', $this->getAtendimento());
        $this->smarty->assign('situacao', $this->getSituacao());
        $this->smarty->assign('pedido_id', $this->getPedidoId());

        if ($this->getDataAberturaEnd('F') == '') {
            $this->smarty->assign('dataAbertura', date("d/m/Y"));
        } else {
            $this->smarty->assign('dataAbertura', $this->getDataAberturaEnd('F'));
        }
        // CADASTRA PEÇA 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_PECA"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_PECA"] == "true"):
            $ajax_request = 'true';

            $objatendimentoTools = new c_atendimento_new_tools();
            // VERIFICA SE NAO TEM ID PECA
            if (empty($this->m_par_peca[13])) {
                if (empty($this->m_par_peca[0])) {
                    if ($this->getDataAberturaEnd() == '') {
                        $this->setDataAberturaEnd(date("d/m/Y"));
                    }
                    $this->setCentroCusto($this->m_empresacentrocusto);
                    $idAtendimento = $this->incluiAtendimento();
                    $this->setId($idAtendimento);
                    $this->smarty->assign('id', $this->getId());
                } else {
                    $idAtendimento = $this->getId();
                }
                $objatendimentoTools->incluiPecasAtendimentoControle($this->m_letra_peca, $idAtendimento);
            } else {
                $objatendimentoTools->alteraPecasAtendimentoControle($this->m_letra_peca);
            }
            $result = $this->select_atendimento_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("VALORTOTAL", $this->getValorTotal(), "CAT_ATENDIMENTO");

            $lancPesq = $this->select_pecas_atendimento();
            $this->smarty->assign('lancPesq', $lancPesq);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;
        // EXCLUI PEÇA
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_PECA"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_PECA"] == "true"):
            $ajax_request = 'true';

            $objatendimentoTools = new c_atendimento_new_tools();
            $objatendimentoTools->excluiPecasAtendimento($this->m_letra_peca);
            $result = $this->select_atendimento_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("VALORTOTAL", $this->getValorTotal(), "CAT_ATENDIMENTO");

            $lancPesq = $this->select_pecas_atendimento();
            $this->smarty->assign('lancPesq', $lancPesq);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;

        // CADASTRA SERVIÇO 
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_SERVICO"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_CADASTRA_SERVICO"] == "true"):
            $ajax_request = 'true';

            $objatendimentoTools = new c_atendimento_new_tools();
            // VERIFICA SE NAO TEM ID SERVICO
            if (empty($this->m_par_servico[10])) {
                // VERIFICA SE NAO TEM ID ATENDIMENTO
                if (empty($this->m_par_servico[0])) {
                    $this->setCentroCusto($this->m_empresacentrocusto);
                    $idAtendimento = $this->incluiAtendimento();
                    $this->setId($idAtendimento);
                    $this->smarty->assign('id', $this->getId());
                } else {
                    $idAtendimento = $this->getId();
                }
                $objatendimentoTools->incluiServicoAtendimentoControle($this->m_letra_servico, $idAtendimento);
            } else {
                $objatendimentoTools->alteraServicoAtendimentoControle($this->m_letra_servico);
            }
            $result = $this->select_atendimento_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("VALORTOTAL", $this->getValorTotal(), "CAT_ATENDIMENTO");

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;
        // EXCLUI SERVIÇO
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_SERVICO"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_EXCLUI_SERVICO"] == "true"):
            $ajax_request = 'true';

            $objatendimentoTools = new c_atendimento_new_tools();
            $objatendimentoTools->excluiServicoAtendimento($this->m_letra_servico);
            $result = $this->select_atendimento_total_geral();
            $res = round($result, 2, PHP_ROUND_HALF_EVEN);
            $this->setValorTotal($res, 'B');
            $this->updateField("VALORTOTAL", $this->getValorTotal(), "CAT_ATENDIMENTO");

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);
        endif;

        //BUSCA PRODUTO OS
        $ajax_request = @($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PROD"] == "true");
        if ($_SERVER["HTTP_AJAX_REQUEST_BUSCA_PROD"] == "true"):
            $ajax_request = 'true';

            $prod = new c_produto();
            $codFab = $this->m_par_peca[14];
            $resultProd = $prod->select_produto_cod_fabricante($codFab);

            if ($resultProd[0]['ORIGEM'] == 'EQUIVALENTE') {
                $codFab  = $resultProd[0]['CODFABRICANTE'];
                $codNota = $resultProd[0]['CODEQUIVALENTE'];
                $cod     = $resultProd[0]['CODIGO'];
                $descPrd = $resultProd[0]['DESCRICAO'];
                $uniProd = $resultProd[0]['UNIDADE'];
            } else {
                $codFab  = $resultProd[0]['CODFABRICANTE'];
                $codNota = $resultProd[0]['CODFABRICANTE'];
                $cod     = $resultProd[0]['CODIGO'];
                $descPrd = $resultProd[0]['DESCRICAO'];
                $uniProd = $resultProd[0]['UNIDADE'];
            }


            if ($resultProd !== null) {
                $this->smarty->assign('prodExiste', 'yes');
                $this->smarty->assign('codProduto', $cod);
                $this->smarty->assign('codFabricante', "'$codFab'");
                $this->smarty->assign('codProdutoNota', "'$codNota'");
                $this->smarty->assign('descProduto', $descPrd);
                $this->smarty->assign('uniProduto', "'$uniProd'");

                $this->setVlrUnitarioPecas($resultProd[0]['VENDA']);
                $this->smarty->assign('vlrUnitarioPecas', $this->getVlrUnitarioPecas('F'));
            } else {
                $this->smarty->assign('prodExiste', 'no');
            }
        else:
            $ajax_request = 'false';
            $this->smarty->assign('ajax', $ajax_request);

        endif;


        $this->smarty->assign('dataFechamentoEnd', $this->getDataFechamentoEnd('F'));
        $this->smarty->assign('prazoEntrega', $this->getPrazoEntrega('F'));
        $this->smarty->assign('condPgto', $this->getCondPgto());
        $this->smarty->assign('obs', $this->getObs());
        $this->smarty->assign('obsServicos', $this->getObsServicos());

        $this->smarty->assign('catEquipamentoId', $this->getCatEquipamentoId());
        $this->smarty->assign('descEquipamento', $this->getDescEquipamento());


        if ($this->getId() != ''):

            $lancPesq = $this->select_pecas_atendimento();
            $this->smarty->assign('lancPesq', $lancPesq);

            $lancItens = $this->select_servicos_atendimento();
            $this->smarty->assign('lancItens', $lancItens);

            $consulta = new c_banco;
            $consulta->setTab("CAT_ATENDIMENTO");
            $vlrPecas = $consulta->getField("VALORPECAS", "ID=" . $this->getId());
            $consulta->close_connection();
            $vlrPecas = number_format($vlrPecas, 2, ',', '.');
            $this->smarty->assign('valorPecas', $vlrPecas);

            $consulta = new c_banco;
            $consulta->setTab("CAT_ATENDIMENTO");
            $vlrServicos = $consulta->getField("VALORSERVICOS", "ID=" . $this->getId());
            $consulta->close_connection();
            $vlrServicos = number_format($vlrServicos, 2, ',', '.');
            $this->smarty->assign('valorServicos', $vlrServicos);

            $consulta = new c_banco;
            $consulta->setTab("CAT_ATENDIMENTO");
            $vlrVisita = $consulta->getField("VALORVISITA", "ID=" . $this->getId());
            $consulta->close_connection();
            $vlrVisita = number_format($vlrVisita, 2, ',', '.');
            $this->smarty->assign('valorVisita', $vlrVisita);

            $consulta = new c_banco;
            $consulta->setTab("CAT_ATENDIMENTO");
            $vlrDesconto = $consulta->getField("VALORDESCONTO", "ID=" . $this->getId());
            $consulta->close_connection();
            $vlrDesconto = number_format($vlrDesconto, 2, ',', '.');
            $this->smarty->assign('valorDesconto', $vlrDesconto);

            $consulta = new c_banco;
            $consulta->setTab("CAT_ATENDIMENTO");
            $vlrTotal = $consulta->getField("VALORTOTAL", "ID=" . $this->getId());
            $consulta->close_connection();
            $vlrTotal = number_format($vlrTotal, 2, ',', '.');
            $this->smarty->assign('valorTotal', $vlrTotal);

        else: {
                $this->smarty->assign('totalatendimento', '0');
            }
        endif;

        // COMBOBOX ATENDENTE
        $consulta = new c_banco();
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
        $sql .= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' )";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $usrAbertura_ids[$i + 1] = $result[$i]['USUARIO'];
            $usrAbertura_names[$i] = $result[$i]['NOME'];
        }
        $this->smarty->assign('usrAbertura_ids',   $usrAbertura_ids);
        $this->smarty->assign('usrAbertura_names', $usrAbertura_names);
        if ($this->getUsrAbertura() == '') {
            $this->setUsrAbertura($this->m_userid);
        }
        $this->smarty->assign('usrAbertura', $this->getUsrAbertura());

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT ID , DESCRICAO FROM CAT_SITUACAO ";
        $sql .= "WHERE ATIVO = '1'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacao_ids[$i] = $result[$i]['ID'];
            $situacao_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacao_ids',   $situacao_ids);
        $this->smarty->assign('situacao_names', $situacao_names);
        if ($this->getSituacao() == '') {
            $this->smarty->assign('situacao', 2);
        } else {
            $this->smarty->assign('situacao', $this->getSituacao());
        }

        // COMBOBOX TIPO_CAT
        $consulta = new c_banco();
        $sql = "SELECT ID, DESCRICAO FROM CAT_TIPO ";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $catTipoId_ids[$i + 1] = $result[$i]['ID'];
            $catTipoId_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('catTipoId_ids',   $catTipoId_ids);
        $this->smarty->assign('catTipoId_names', $catTipoId_names);
        $this->smarty->assign('catTipoId', $this->getCatTipoId());

        // COMBOBOX COND PAGAMENTO
        $consulta = new c_banco();
        $sql = "SELECT * FROM fat_cond_pgto;";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $condPgto_ids[0] = 0;
        $condPgto_names[0] = 'Condição Pagamento';
        for ($i = 0; $i < count($result); $i++) {
            $condPgto_ids[$i + 1] = $result[$i]['ID'];
            $condPgto_names[$i + 1] = $result[$i]['DESCRICAO'];
        }

        $this->smarty->assign('condPgto_ids', $condPgto_ids);
        $this->smarty->assign('condPgto_names', $condPgto_names);
        $this->smarty->assign('condPgto_id', $this->getCondPgto());

        // COMBOBOX EQUIPE
        $consulta = new c_banco();
        $sql = "SELECT ID, DESCRICAO FROM AMB_EQUIPE ORDER BY DESCRICAO";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        $equipe_ids = [];
        $equipe_names = [];

        if (!empty($result)) {
            foreach ($result as $row) {
                $equipe_ids[] = $row['ID'];
                $equipe_names[] = $row['DESCRICAO'];
            }
        }

        $this->smarty->assign('equipe_ids', $equipe_ids);
        $this->smarty->assign('equipe_names', $equipe_names);
        $this->smarty->assign('equipeId', $this->getEquipeId());

        // COMBOBOX USUÁRIO EQUIPE
        $consulta = new c_banco();
        $sql = "SELECT USUARIO AS ID, NOME AS DESCRICAO FROM AMB_USUARIO WHERE SITUACAO = 'A' ORDER BY NOME";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;

        $usuario_equipe_ids = [];
        $usuario_equipe_names = [];

        if (!empty($result)) {
            foreach ($result as $row) {
                $usuario_equipe_ids[] = $row['ID'];
                $usuario_equipe_names[] = $row['DESCRICAO'];
            }
        }

        $this->smarty->assign('usuario_equipe_ids', $usuario_equipe_ids);
        $this->smarty->assign('usuario_equipe_names', $usuario_equipe_names);
        $this->smarty->assign('usuario_equipe', $this->getUsuarioEquipe());

        $this->smarty->display('atendimento_new_cadastro.tpl');
    }

    //fim desenhaCadgrupo
    //---------------------------------------------------------------
    //---------------------------------------------------------------
    function mostraAtendimento($tipoMsg = NULL, $mensagem = NULL)
    {

        $cliente = '';
        if ($this->m_letra != ''):
            $lanc = $this->select_atendimento_letra($this->m_letra, $this->m_situacoesAtendimento);
        endif;

        if ($this->m_par[0] == "") $this->smarty->assign('dataIni', date("01/m/Y"));
        else $this->smarty->assign('dataIni', $this->m_par[0]);

        if ($this->m_par[1] == "") {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
            $data = date("d/m/Y", mktime(0, 0, 0, $mes + 1, 0, $ano));
            $this->smarty->assign('dataFim', $data);
        } else $this->smarty->assign('dataFim', $this->m_par[1]);

        // pessoa
        if ($this->m_par[2] == "") $this->smarty->assign('pessoa', "");
        else {
            $this->setCliente($this->m_par[2]);
            $this->setClienteNome();
            $this->smarty->assign('pessoa', $this->m_par[2]);
            $this->smarty->assign('nome', $this->getClienteNome());
        }

        //$this->smarty->assign('numAtendimento', $this->m_par[3]);

        // COMBOBOX SITUACAO
        $consulta = new c_banco();
        $sql = "SELECT ID , DESCRICAO FROM CAT_SITUACAO ";
        $sql .= "WHERE ATIVO = '1'";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $situacaoAtendimento_ids[$i] = $result[$i]['ID'];
            $situacaoAtendimento_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('situacaoAtendimento_ids',   $situacaoAtendimento_ids);
        $this->smarty->assign('situacaoAtendimento_names', $situacaoAtendimento_names);
        if ($this->m_situacoesAtendimento == '') {
            $this->smarty->assign('situacaoAtendimento_id', 2);
        } else {
            $parSit = explode("|", $this->m_situacoesAtendimento);
            $this->smarty->assign('situacaoAtendimento_id', $parSit);
        }

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('lanc', $lanc);

        $this->smarty->display('atendimento_new_mostra.tpl');
    }

    function cadastraImagemOS($mensagem = NULL, $tipoMsg = null)
    {

        $lanc = $this->select_os_imagem($this->getId());

        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('pathJs', ADMhttpBib . '/js');
        $this->smarty->assign('subMenu', $this->m_submenu);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('tipoMsg', $tipoMsg);
        $this->smarty->assign('id', $this->getId());

        $this->smarty->assign('idOs', $this->m_idOs);

        $this->smarty->assign('titulo', "'" . $this->m_titulo . "'");

        $this->smarty->assign('lanc', $lanc);

        $this->smarty->display('os_imagem_mostra.tpl');
    }
}
//Fim class p_atendimento_new

function formataBdVlr($vlr)
{
    //formatação vlr 
    if (strlen($vlr) > 6) {
        $number = explode(",", ($vlr));
        $newNumber = str_replace('.', '', $vlr);
        $vlrBd = $newNumber . "." . $number[1];
    } else {
        $vlrBd = str_replace(',', '.', $vlr);
    }
    return $vlrBd;
}
function setTotalGeral($vlrPecas, $vlrServicos, $vlrVisita, $vlrDesconto)
{
    $pecas    = formataBdVlr($vlrPecas);
    $servicos = formataBdVlr($vlrServicos);
    $visita =   formataBdVlr($vlrVisita);
    $desconto = formataBdVlr($vlrDesconto);

    $totalGeral = (($pecas + $servicos + $visita) - $desconto);

    return $totalGeral;
}
// Rotina principal - cria classe
$atendimento = new p_atendimento_new();

$atendimento->controle();
