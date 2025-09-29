<?php
/* * **************************************************************************
 * Cliente...........:
 * Contratada........: Infosystem
 * Desenvolvedor.....: Marcio Sergio da Silva
 * Sistema...........: Sistema de Informacao Gerencial
 * Classe............: p_os_baixa - cadastro de os_baixas PAGES
 * Ultima Atualizacao: 05/08/04
 * ************************************************************************** */

require_once ("../../bib/reader.php");
include "../../bib/c_tools.php";
include "../../bib/c_date.php";
include "../../class/pss/c_pessoa.php";
include "../../class/cat/c_ordem_servico.php";
include "../../class/cat/c_contrato.php";
include "../../class/cat/c_correios.php";
include "../../class/est/c_produto.php";
include "../../class/est/c_inventario.php";
include "../../class/est/c_nota_fiscal.php";
include "../../class/est/c_nota_fiscal_produto.php";
include "../../class/est/c_nota_fiscal_produto_os.php";
include "../../class/est/c_user_produto.php";

//Class p_os_baixa
Class p_os_backlog extends c_pessoa {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    private $m_data = NULL;
    public $m_name = NULL;
    public $m_tmp = NULL;
    public $m_type = NULL;
    public $m_size = NULL;

//---------------------------------------------------------------
//---------------------------------------------------------------
    function p_os_backlog($submenu, $data, $tipo, $letra) {


        $this->m_submenu = $submenu;
        $this->m_letra = $letra;
        $this->m_data = $data;
        $this->m_tipo = $tipo;


        session_start();
        $this->carrregaVarsConfig(0);
        $this->cabecalho();
        $this->from_array($_SESSION['user_array']);
        $this->empresa($this->m_empresanome, $this->m_usernome);

//  include $this->forms."/p_menu.php";
//         echo "<pre>";
//         print_r($_SESSION['user_array']);
//         echo "</pre>";
    }

    //---------------------------------------------------------------
    //Atualiza localiza��o documento para Faturado
    // tipo = true (where numchamadosolicitante
    // tipo = false (where numatendimento
    //---------------------------------------------------------------
    public function atualizaInstalacao($tipo, $rat, $quant, $total, $msg, $dataAtualizacao) {
        $sql = "UPDATE cat_atendimento set QTDEEQUIPAMENTO= " . $quant . ", situacao='2', faturamento='D', DATAENTREGADOC = '" . $dataAtualizacao . "', totalat=" . $total;
        $sql .= " WHERE (numchamadosolicitante = '" . $rat . "')";

        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        $os = new c_ordemServico();
        if ($tipo == true) {
            $os->setNumChamadoSolicitante($rat);
            $ordemServico = $os->select_ordemServicoSolicitante();
        } else {
            $os->setId($id);
            $ordemServico = $os->select_ordemServico();
        }
        $os->setId($ordemServico[0]['ID']);
        $os->setUserLogin($this->m_userid);

        $os->lancaAcompanhamento($msg . $dataAtualizacao, 'A');
    }

// Atualiza Localiza��o Documento
    //---------------------------------------------------------------
    //Atualiza localiza��o documento para Faturado
    // tipo = true (where numchamadosolicitante
    // tipo = false (where numatendimento
    //---------------------------------------------------------------

    public function atualizaLocDoc($tipo, $rat, $id, $total, $msg, $dataAtualizacao, $numPedidoCompra) {
        $sql = "UPDATE cat_atendimento set faturamento='D', DATAENTREGADOC = '" . $dataAtualizacao . "', NUMNFTERCERIZADO='" . $numPedidoCompra . "'";
        if ($tipo == true) {
            $sql .= " WHERE (numchamadosolicitante = '" . $rat . "')";
        } else {
            $sql .= " WHERE (id = " . $id . ")";
        }
        echo strtoupper($sql);
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();

        $os = new c_ordemServico();
        if ($tipo == true) {
            $os->setNumChamadoSolicitante($rat);
            $ordemServico = $os->select_ordemServicoSolicitante();
        } else {
            $os->setId($id);
            $ordemServico = $os->select_ordemServico();
        }
        if ($ordemServico[0]['ID'] != '') {
            $os->setId($ordemServico[0]['ID']);
            $os->setUserLogin($this->m_userid);

            $os->lancaAcompanhamento($msg . $dataAtualizacao, 'A');
        }
    }

// Atualiza Localiza��o Documento
    //---------------------------------------------------------------
    // tipo = true (where numchamadosolicitante
    // tipo = false (where numatendimento
    //---------------------------------------------------------------
    public function baixa_os($tipo, $rat, $id, $total, $msg, $dataAtualizacao, $faturamento) {
        $sql = "UPDATE cat_atendimento set situacao='2', faturamento='" . $faturamento . "', DATAENTREGADOC = '" . $dataAtualizacao . "', totalat=" . $total;
        if ($tipo == true) {
            $sql .= " WHERE (numchamadosolicitante = '" . $rat . "')";
        } else {
            $sql .= " WHERE (id = " . $id . ")";
        }

        //$sql = "UPDATE cat_atendimento set situacao='2', faturamento='D', totalat=".$total." WHERE (numchamadosolicitante = ".$rat.")";
        //ECHO $sql;
        //echo $this->m_userid;

        $banco = new c_banco;
        $banco->exec_sql($sql);
        //echo 'passou';
        $banco->close_connection();

        // lan�a acompanhamento
        $os = new c_ordemServico();
        if ($tipo == true) {
            $os->setNumChamadoSolicitante($rat);
            $ordemServico = $os->select_ordemServicoSolicitante();
        } else {
            $os->setId($id);
            $ordemServico = $os->select_ordemServico();
        }

        $os->setId($ordemServico[0]['ID']);
        $os->setNumAtendimento($ordemServico[0]['NUMATENDIMENTO']);
        $os->setUsrAbertura($ordemServico[0]['USRABERTURA']);
        $os->setCliente($ordemServico[0]['CLIENTE']);
        $os->setClienteNome();
        $os->setContato($ordemServico[0]['CONTATO']);
        $os->setFone($ordemServico[0]['FONE']);
        $os->setSetor($ordemServico[0]['SETOR']);
        $os->setNumChamadoSolicitante($ordemServico[0]['NUMCHAMADOSOLICITANTE']);
        $os->setSolicitante($ordemServico[0]['SOLICITANTE']);
        $os->setSolicitanteNome();
        $os->setSituacao($ordemServico[0]['SITUACAO']);
        $os->setSituacaoAnt($ordemServico[0]['SITUACAO']);
        $os->setDataAberAtend($ordemServico[0]['DATAABERATEND']);
        $os->setHoraAberAtend($ordemServico[0]['HORAABERATEND']);
        $os->setDataFechAtend($ordemServico[0]['DATAFECHATEND']);
        $os->setHoraFechAtend($ordemServico[0]['HORAFECHATEND']);
        $os->setSLAAtend($ordemServico[0]['SLAATEND']);
        $os->setSLASituacao($ordemServico[0]['SLASITUACAO']);
        $os->setLocalAtendimento($ordemServico[0]['LOCALATENDIMENTO']);
        $os->setTipoAtendimento($ordemServico[0]['TIPOATENDIMENTO']);
        $os->setTipoIntervencao($ordemServico[0]['TIPOINTERVENCAO']);
        $os->setSenha($ordemServico[0]['SENHA']);
        $os->setDescEquipamento($ordemServico[0]['DESCEQUIPAMENTO']);
        $os->setQtdeEquipamento($ordemServico[0]['QTDEQUIPAMENTO']);
        $os->setSerieEquipamento($ordemServico[0]['SERIEEQUIPAMENTO']);
        $os->setBackup($ordemServico[0]['BACKUP']);
        $os->setPrioridade($ordemServico[0]['PRIORIDADE']);
        $os->setTecnicoResp($ordemServico[0]['TECNICORESP']);
        $os->setUsrAberturaNome();
        $os->setFaturamento($ordemServico[0]['FATURAMENTO']);
        $os->setObsServico($ordemServico[0]['OBSSERVICO']);
        $os->setObs($ordemServico[0]['OBS']);
        $os->setSolucao($ordemServico[0]['SOLUCAO']);
        $os->setCentroCusto($ordemServico[0]['CENTROCUSTO']);
        $os->setTotalAt($ordemServico[0]['TOTALAT']);


        $parametros = new c_banco;
        $parametros->setTab("CAT_PARAMETROS");
        $os->setCustoKmHr($parametros->getParametros("KMRODADO"));
        $parametros->close_connection();
        $os->setLocalAtendimentoHr("R");
        $os->setDataHorario(date("Y-m-d"));
        $os->setHoraIni(date("H:i:s"));
        $os->setHoraFim(date("H:i:s"));
        $os->setCodUser($this->m_userid);
        $os->setCustoUsr($this->m_usercusto);
        $os->setDescServico($msg . $dataAtualizacao);
        $os->setAtendimentoHr("A");
        $os->setOrigem('');
        $os->setDestino('');
        $os->setSituacaoHr($os->getSituacao());
        $os->setSLASituacaoHr($os->getSLASituacao());
        $os->setDataIniSituacaoHr(date("Y-m-d H:i:s"));
        $os->setDataFimSituacaoHr('');

        $os->setUserLogin($this->m_userid);

//		$os->incluiHorario();	
        $os->lancaAcompanhamento($msg . $dataAtualizacao, 'A');

        return $banco->resultado;
    }

//fim select_pessoa
//---------------------------------------------------------------
//---------------------------------------------------------------
    public function select_clienteExcel() {


        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
//$data->read('../../ratprintcom.xls');
        $this->m_arquivo = '../../rat.xls';
//echo "arquivo: ".$this->m_arquivo;
//$data->read($this->m_arquivo);
//echo " nome: ".$this->m_tmp;
        $data->read($this->m_tmp);
        $this->m_data = c_date::convertDateBd($this->m_data, $this->m_banco);

//error_reporting(E_ALL ^ E_NOTICE);

        for ($i = 5; $i <= $data->sheets[0]['numRows']; $i++) {
            if (is_numeric($data->sheets[0]['cells'][$i][3])) {
                $total = $data->sheets[0]['cells'][$i][5] +
                        $data->sheets[0]['cells'][$i][6] +
                        $data->sheets[0]['cells'][$i][7] +
                        $data->sheets[0]['cells'][$i][8] +
                        $data->sheets[0]['cells'][$i][9] +
                        $data->sheets[0]['cells'][$i][10] +
                        $data->sheets[0]['cells'][$i][11] +
                        $data->sheets[0]['cells'][$i][12] +
                        $data->sheets[0]['cells'][$i][13] +
                        $data->sheets[0]['cells'][$i][14] +
                        $data->sheets[0]['cells'][$i][15] +
                        $data->sheets[0]['cells'][$i][16] +
                        $data->sheets[0]['cells'][$i][17] +
                        $data->sheets[0]['cells'][$i][18] +
                        $data->sheets[0]['cells'][$i][19];


                $status = $this->baixa_os(true, $data->sheets[0]['cells'][$i][3], 0, $total, "Enviado planilha para Liberacao dia: ", $this->m_data, 'D'); // cel X


                echo "<br>";

                echo $data->sheets[0]['cells'][$i][3] . " - " . $data->sheets[0]['cells'][$i][24] . " - " . $i . " - " . $status;
            } else {
                echo $data->sheets[0]['cells'][$i][3] . " - " . $data->sheets[0]['cells'][$i][24] . " - " . $i;
                break;
            }
        }
    }

//fim select
//---------------------------------------------------------------
// valor - coluna 3
// num chamado solicitante = coluna 4
// data = coluna 5
//---------------------------------------------------------------
    public function select_clienteExcelScp() {


        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);

//error_reporting(E_ALL ^ E_NOTICE);

        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
            if (is_numeric($data->sheets[0]['cells'][$i][4])) {
                $total = $data->sheets[0]['cells'][$i][3];

                //echo "passou ".substr($data->sheets[0]['cells'][$i][5], 0, 10);
                $status = $this->baixa_os(true, $data->sheets[0]['cells'][$i][4], 0, $total, "Submetido Sistema SCP dia: ", substr($data->sheets[0]['cells'][$i][5], 0, 10), 'A'); // cel X


                echo "<br>";

                echo $data->sheets[0]['cells'][$i][4] . " - " . $i . " - Ok";
            } else {
                echo $data->sheets[0]['cells'][$i][4] . " - " . $i;
                break;
            }
        } // for
    }

//fim select scp
//---------------------------------------------------------------
// num chamado solicitante = coluna 1
// data entrega doc = data digitada
//---------------------------------------------------------------

    public function select_clienteExcelFat() {



        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);
        $numPedidoCompra = $data->sheets[0]['cells'][1][1];
//error_reporting(E_ALL ^ E_NOTICE);

        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
            if (is_numeric($data->sheets[0]['cells'][$i][2])) {

                //echo "passou ".substr($data->sheets[0]['cells'][$i][5], 0, 10);
                $status = $this->atualizaLocDoc(true, $data->sheets[0]['cells'][$i][2], 0, $total, "Emissão NF dia: ", $this->m_data, $numPedidoCompra); // cel X


                echo "<br>";

                echo $data->sheets[0]['cells'][$i][2] . " - " . $i . " ---- " . $status;
            } else {
                echo $data->sheets[0]['cells'][$i][2] . " - " . $i;
                break;
            }
        } // for
    }

//fim select fat
//---------------------------------------------------------------
// PLANILHA INCLUIR UMA LINHA EM BRANCO NO FINAL
//---------------------------------------------------------------
    public function select_posInstalacao() {


        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);

//error_reporting(E_ALL ^ E_NOTICE);

        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
            if (is_numeric($data->sheets[0]['cells'][$i][4])) {

                $banco = new c_banco;

                $sql = "SELECT * FROM cat_atendimento A INNER JOIN FIN_CLIENTE C ON A.CLIENTE = C.CLIENTE WHERE C.NOMEREDUZIDO = 'ITAU AG: " . $data->sheets[0]['cells'][$i][4] . "'";
                $os = $banco->exec_sql($sql);
                $banco->close_connection();
                $chamado = $os[0]['NUMCHAMADOSOLICITANTE'];

                $total = $data->sheets[0]['cells'][$i][6];

                //echo "passou ".substr($data->sheets[0]['cells'][$i][5], 0, 10);
                $status = $this->atualizaInstalacao(true, $chamado, $data->sheets[0]['cells'][$i][3], $total, "Emissão NF dia: ", $this->m_data); // cel X



                echo "<br>";

                echo $chamado . " - " . $i . " - " . $status;
            } else {
                echo $chamado . " - " . $i;
                break;
            }
        } // for
    }

//fim positivo instalacao

    /**
     * <b> Funcao para remover os acentos da importacao. </b>
     * @name removeAcentos
     * @param STRING $string
     * @param BOOLEAN $slug FALSE
     * @return STRING
     */
    function removeAcentos($string, $slug = false) {
        $conversao = array('á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'é' => 'e',
            'ê' => 'e', 'í' => 'i', 'ï' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', "ö" => "o",
            'ú' => 'u', 'ü' => 'u', 'ç' => 'c', 'ñ' => 'n', 'Á' => 'A', 'À' => 'A', 'Ã' => 'A',
            'Â' => 'A', 'É' => 'E', 'Ê' => 'E', 'Í' => 'I', 'Ï' => 'I', "Ö" => "O", 'Ó' => 'O',
            'Ô' => 'O', 'Õ' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ç' => 'C', 'N' => 'Ñ');
        return strtr($string, $conversao);
    }

// removeAcentos

    /**
     * Importacao de planilha no formato padrao dos correios. Atualizando o cliente, Ordem de servico
     * e incluindo um registro no CAT_CORREIOS vinculado a ordem de servico
     * @name select_importa_excel_correios
     */
    public function select_importa_excel_correios() {
        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

        $data = new Spreadsheet_Excel_Reader();
        // Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);
        //error_reporting(E_ALL ^ E_NOTICE);
        $contadorGeral = 0;
        $CorreiosOBJ = new c_correios();
        $PessoaOBJ = new c_pessoa();
        $OrdemServicoOBJ = new c_ordemServico();

        // Agencia de correios que a empresa faz expedicao, cadastrado nos parametros
        $parametros = new c_banco;
        $parametros->setTab("CAT_PARAMETROS");
        $agenciaCorreios = $parametros->getParametros("AGENCIACORREIOS");
        $parametros->close_connection();

        // inicio na linha 2 (para deixar cabecalho)
        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
            // Coluna de observacao sera usada para ordem de servico
            if ($data->sheets[0]['cells'][$i][11] != '') {
                // Verificar se ja existe cadastro do cliente
                $arrPessoa = $PessoaOBJ->select_pessoa_nome($data->sheets[0]['cells'][$i][16]);
                if (!is_array($arrPessoa)) {
                    // cadastrar o cliente
                    $PessoaOBJ->setNome($data->sheets[0]['cells'][$i][16]);                         //NOME DO CLIENTE
                    $PessoaOBJ->setNomeReduzido(substr($data->sheets[0]['cells'][$i][16], 0, 15));  // NOME DO CLIENTE
                    $PessoaOBJ->setEndereco(addslashes($data->sheets[0]['cells'][$i][17]));         // ENDEREÇO
                    if (is_numeric($data->sheets[0]['cells'][$i][18])) {
                        $PessoaOBJ->setNumero($data->sheets[0]['cells'][$i][18]);                   //NUMERO
                    } else {
                        $PessoaOBJ->setNumero('0');
                    }
                    $PessoaOBJ->setComplemento($data->sheets[0]['cells'][$i][19]);                  //COMPLEMENTO
                    $PessoaOBJ->setBairro(addslashes($data->sheets[0]['cells'][$i][20]));           //BAIRRO
                    $PessoaOBJ->setCidade(addslashes($data->sheets[0]['cells'][$i][21]));           //CIDADE
                    $PessoaOBJ->setEstado($data->sheets[0]['cells'][$i][22]);                       //ESTADO
                    $PessoaOBJ->setCep($data->sheets[0]['cells'][$i][23]);                          //CEP
                    $PessoaOBJ->setAtividade('AT');                                                 // AT-ASSISTENCIA TECNICA
                    $PessoaOBJ->setClasse('01');                                                    // 01 - ATIVO
                    $PessoaOBJ->setPessoa('F');                                                     //  F - FISICO
                    $PessoaOBJ->setCentroCusto($this->m_empresacentrocusto);                        //  CENTRO CUSTO
                    $PessoaOBJ->setVendedor($this->m_userid);                                       //  USUARIO LOGADO
                    $PessoaOBJ->setObs('Cadastro feito por importacao.');                           // OBSERVACAO
                    $idPessoa = $PessoaOBJ->incluiPessoa(); // Inclusao no BD - retorna o ID cadastrado
                } else {
                    $idPessoa = $arrPessoa[0]['CLIENTE'];
                }


                //verificar os dados da ordem de servico de acordo com o numero do parceiro
                $arrOrdemService = $OrdemServicoOBJ->select_ordemServicoSolicitante_nf($data->sheets[0]['cells'][$i][11]); // Observacoes
                if (is_array($arrOrdemService)) {
                    // Sets dos objetos da classe de acordo com o BD
                    $OrdemServicoOBJ->setId($arrOrdemService[0]['ID']);
                    $OrdemServicoOBJ->buscaCadastroOrdemServico();
                    $OrdemServicoOBJ->setCliente($idPessoa); // alteracao do cliente 
                    $OrdemServicoOBJ->alteraOrdemServico(); // alteracao no banco de dados

                    $CorreiosOBJ->setIdOs($arrOrdemService[0]['ID']);
                    $arrCorreios = $CorreiosOBJ->select_correios_id();
                    $CorreiosOBJ->setNumObjeto($data->sheets[0]['cells'][$i][10]);
                    // Caso nao existir registro do numero de objeto e IDOS
                    if (!is_array($CorreiosOBJ->select_correios_num_objeto())) {
                        if (count($arrCorreios) == '') {
                            $CorreiosOBJ->setNumObjeto($data->sheets[0]['cells'][$i][10]);          // Numero do objeto
                            $CorreiosOBJ->setTentativa('1');                                        // 1 tentativa
                            $CorreiosOBJ->setContratoCorreios($data->sheets[0]['cells'][$i][3]);    // numero do contrato do correios
                            $CorreiosOBJ->setCartaoPostagem($data->sheets[0]['cells'][$i][4]);      // numero do cartao de postagem
                            $CorreiosOBJ->setDataPostagem($data->sheets[0]['cells'][$i][2]);        // Data de postagem
                            $CorreiosOBJ->setServicoContratado($data->sheets[0]['cells'][$i][9]);   // nome do servico contratado
                            $CorreiosOBJ->setNome($data->sheets[0]['cells'][$i][16]);               // nome do cliente
                            $CorreiosOBJ->setCpf($arrOrdemService[0]['CPFCLIENTE']);                // cpf do cliente
                            $CorreiosOBJ->setAgenciaPostagem($agenciaCorreios);                     // Nome da agencia de expedicao em parametros
                            $CorreiosOBJ->setNumPlp($data->sheets[0]['cells'][$i][1]);              // Numero do PLP correios
                            $CorreiosOBJ->setPeso($data->sheets[0]['cells'][$i][12]);               // peso do equipamento
                            $CorreiosOBJ->setAltura($data->sheets[0]['cells'][$i][13]);             // altura do equipamento
                            $CorreiosOBJ->setLargura($data->sheets[0]['cells'][$i][14]);            // largura do equipamento
                            $CorreiosOBJ->setComprimento($data->sheets[0]['cells'][$i][15]);        // comprimento do equipamento
                            $CorreiosOBJ->setServicoAdcionais($data->sheets[0]['cells'][$i][25]);   // servicos adicionais contratado do correios
                            $CorreiosOBJ->setData(date("d/m/Y"));                                   // data da importacao para o sistema
                            $CorreiosOBJ->setUsuario($this->m_userid);                              // usuario que fez importacao
                            $CorreiosOBJ->incluiCorreios();                                         // Inclusao na tabela
                            $contadorGeral++;                                                       // Contador vai somar 1 para mensagem final
                            echo 'SUCESSO - Registro inserido! Linha' . $i . "<br>";
                        } else {
                            // Calculo da tentativa #####
                            $CorreiosOBJ->setNumObjeto($data->sheets[0]['cells'][$i][10]);          // Numero do objeto
                            $CorreiosOBJ->setTentativa(count($arrCorreios)+1);                      // Calcula a tentativa de expedicao
                            $CorreiosOBJ->setContratoCorreios($data->sheets[0]['cells'][$i][3]);    // numero do contrato do correios
                            $CorreiosOBJ->setCartaoPostagem($data->sheets[0]['cells'][$i][4]);      // numero do cartao de postagem
                            $CorreiosOBJ->setDataPostagem($data->sheets[0]['cells'][$i][2]);        // Data de postagem
                            $CorreiosOBJ->setServicoContratado($data->sheets[0]['cells'][$i][9]);   // nome do servico contratado
                            $CorreiosOBJ->setNome($data->sheets[0]['cells'][$i][16]);               // nome do cliente
                            $CorreiosOBJ->setCpf($arrOrdemService[0]['CPFCLIENTE']);                // cpf do cliente
                            $CorreiosOBJ->setAgenciaPostagem($agenciaCorreios);                     // Nome da agencia de expedicao em parametros
                            $CorreiosOBJ->setNumPlp($data->sheets[0]['cells'][$i][1]);              // Numero do PLP correios
                            $CorreiosOBJ->setPeso($data->sheets[0]['cells'][$i][12]);               // peso do equipamento
                            $CorreiosOBJ->setAltura($data->sheets[0]['cells'][$i][13]);             // altura do equipamento
                            $CorreiosOBJ->setLargura($data->sheets[0]['cells'][$i][14]);            // largura do equipamento
                            $CorreiosOBJ->setComprimento($data->sheets[0]['cells'][$i][15]);        // comprimento do equipamento
                            $CorreiosOBJ->setServicoAdcionais($data->sheets[0]['cells'][$i][25]);   // servicos adicionais contratado do correios
                            $CorreiosOBJ->setData(date("d/m/Y"));                                   // data da importacao para o sistema
                            $CorreiosOBJ->setUsuario($this->m_userid);                              // usuario que fez importacao
                            $CorreiosOBJ->incluiCorreios();                                         // Inclusao na tabela
                            $contadorGeral++;                                                       // Contador vai somar 1 para mensagem final
                            echo 'SUCESSO - Registro inserido! Existe registro da expedição para a ordem de serviço: ' . $arrOrdemService[0]['NUMCHAMADOSOLICITANTE'] . ' - Linha: ' . $i . '.<br>';
                        }// if count($arrCorreios)
                    }else{
                        echo 'ERRO - Existe registro da expedição para a ordem de serviço: ' . $arrOrdemService[0]['NUMCHAMADOSOLICITANTE'] . ' - Objeto: '.$data->sheets[0]['cells'][$i][10].' - Linha: ' . $i . '.<br>';
                    } // if !is_array($CorreiosOBJ->select_correios_num_objeto())
                } else {
                    echo 'ERRO - Ordem de Serviço não encontrada!! Linha: ' . $i . "<br>";
                }// is_array
            } else {
                echo 'ERRO - Observação sem Ordem de serviço!! Linha: ' . $i . "<br>";
            }
            echo "<hr>";
        }// for
        echo "Total de registros importado: " . $contadorGeral . ". - Importa&ccedil;&atilde;o efetuado com sucesso. ";
    }

    public function select_importa_excel_atendimento() {


        /**
         *  Cidade sendo cadastrado como cliente
         * 1 - numero do chamado do Parceiro
         * 2 - Data de abertura do sistema SMC (não utilizado no cadastro)
         * 3 - Data de Abertura do Parceiro
         * 4 - Código da Situação
         * 5 - Qtde de Dias (não utilizado no cadastro)
         * 6 - Código do Projeto 
         * 7 - Estado
         * 8 - cidade
         * 9
         * 10
         * 
         */
        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);
//error_reporting(E_ALL ^ E_NOTICE);
        $contadorGeral = 0;
        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

            /* cadastro cliente */

            $classCliente = new c_pessoa();
            $atend = new c_ordemServico;
            $classProjeto = new c_contrato();

            $reg_pessoa = $classCliente->select_pessoa_nome($data->sheets[0]['cells'][$i][8]);
            if ($reg_pessoa[0]['CLIENTE'] == '') {
                $classCliente->setNome($data->sheets[0]['cells'][$i][8]);
                $classCliente->setNomeReduzido(substr($data->sheets[0]['cells'][$i][8], 0, 15));
                $classCliente->setEndereco($data->sheets[0]['cells'][$i][8]);
                $classCliente->setCidade($data->sheets[0]['cells'][$i][8]);
                $classCliente->setEstado($data->sheets[0]['cells'][$i][7]);
                $classCliente->setCep('80000000');
                $classCliente->setPessoa('J');
                $classCliente->setClasse('01');
                $classCliente->setObs('Cliente cadastrado atraves de importacao.');
                $classCliente->setCentroCusto($this->m_empresacentrocusto);
                $classCliente->setVendedor($this->m_userid);
                $atend->setCliente($classCliente->incluiPessoa());
            } else {
                $atend->setCliente($reg_pessoa[0]['CLIENTE']);
            }//IF

            $atend->setTipoAtendimento($data->sheets[0]['cells'][$i][6]);

            $atend->setSituacao($data->sheets[0]['cells'][$i][4]);
            $atend->setLocalAtendimento('O');
            $atend->setTipoIntervencao('C');
            $atend->setSolicitante(177821);
            $atend->setNumChamadoSolicitante($data->sheets[0]['cells'][$i][1]);
            $atend->setDataAberAtend($data->sheets[0]['cells'][$i][3]);
            $atend->setHoraAberAtend(date("H:m:s"));
            $atend->setUsrAbertura($this->m_userid);
            $atend->setDescEquipamento($data->sheets[0]['cells'][$i][6]);
            $atend->setSerieEquipamento('');
            $atend->setCentroCusto($this->m_empresacentrocusto);
            $atend->setObsServico('Ordem de servico cadastrado por meio de importacao');
            $atend->incluiOrdemServico();
            $contadorGeral ++;
            echo "Chamado: " . $data->sheets[0]['cells'][$i][1] . " - Linha: " . $i . " - OK." . "<BR>";
        }
        echo "Total de Chamados importado: " . $contadorGeral . ". - Importa&ccedil;&atilde;o efetuado com sucesso. ";
    }

    public function select_importa_excel_inventario() {

        set_time_limit(500);
        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);
//error_reporting(E_ALL ^ E_NOTICE);

        /*
         * formato da planilha
         * 1 - codfabricante
         * 2 - descricao do produto
         * 3 - num nf
         * 4 - qtde da peça (novo ou usado)
         * 5 - projeto
         * 6 - localizacao
         * 7 - tecnico
         * 8 - Aplicado
         */
        $contadorGeral = 0;
        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { // $data->sheets[0]['cells'][$i][1]
            $classProduto = new c_produto();
            $classNF = new c_nota_fiscal();
            $classNFProduto = new c_nota_fiscal_produto();
            $classNfProdutoOs = new c_nota_fiscal_produto_os();
            $classNF->setNumero($data->sheets[0]['cells'][$i][3]);
            $existeNF = $classNF->existeNotaFiscalNum();
            if ($existeNF[0]['NUMERO'] == '') {
                $classNF->setModelo('55');
                $classNF->setSerie('2');
                $classNF->setNumero($data->sheets[0]['cells'][$i][3]);
                $classNF->setPessoa($data->sheets[0]['cells'][$i][10]); //positivo
                $classNF->setEmissao($data->sheets[0]['cells'][$i][11].' 18:00:00');
                $classNF->setNatOperacao($data->sheets[0]['cells'][$i][13]);
                $classNF->setTipo(0);
                $classNF->setSituacao('B');
                $classNF->setDataSaidaEntrada($data->sheets[0]['cells'][$i][12].' 18:00:00');
                $classNF->setFormaEmissao('1');
                $classNF->setFinalidadeEmissao('1');
                $classNF->setCentroCusto($this->m_empresacentrocusto);
                $classNF->setGenero($data->sheets[0]['cells'][$i][19]);
                $classNF->setTotalnf('1');
                $classNF->setObs('Nota fiscal cadastrada por meio de importação');
                $classNFProduto->setIdNf($classNF->incluiNotaFiscal());
            } else {
                $classNFProduto->setIdNf($existeNF[0]['ID']);
            }// FIM DADOS NOTA FISCAL
            // se não existe o produto cadastro, se existe atualiza a localização.
            $classProduto->setCodFabricante($data->sheets[0]['cells'][$i][1]);
            $produto = $classProduto->select_produto_fabricante();
            if ($produto[0]['CODIGO'] == '') {
                $classProduto->setDesc($data->sheets[0]['cells'][$i][2]);
                $classProduto->setUni('UN');
                $classProduto->setCodFabricante($data->sheets[0]['cells'][$i][1]);
                $classProduto->setFabricante($data->sheets[0]['cells'][$i][10]); //positivo
                $classProduto->setOrigem($data->sheets[0]['cells'][$i][15]);
                $classProduto->setTribIcms($data->sheets[0]['cells'][$i][16]);
                $classProduto->setObs('Produto cadastrado atraves de importacao.');
                $classNFProduto->setCodProduto($classProduto->incluiProduto());
            } else {
                //$classProduto->setLocalizacao($data->sheets[0]['cells'][$i][6]);
                //  $classProduto->setId($produto[0]['CODIGO']);
                // $classProduto->alteraProdutoLocalizacao();
                $classNFProduto->setCodProduto($produto[0]['CODIGO']);
            }//FIM DADOS DO PRODUTO
            /*
              //contrato
              $classProjeto = new c_contrato();
              $projeto = $classProjeto->select_projeto_importacao_desc($data->sheets[0]['cells'][$i][5]);
              if ($projeto[0]['NRCONTRATO'] == '') {
              $ultimoContrato = $classProjeto->select_max_nrcontrato();
              $classProjeto->setnrcontrato($i);
              $classProjeto->setdescricao($data->sheets[0]['cells'][$i][5]);
              $classProjeto->setcliente(177821);
              $classProjeto->settipoContrato('1');
              $classProjeto->setsituacao('A');
              $classProjeto->setObs('CONTRATO CADASTRADO POR IMPORTACAO');
              $classNFProduto->setProjeto($classProjeto->incluiProjeto());
              } else {
              $classNFProduto->setProjeto($projeto[0]['NRCONTRATO']);
              }// FIM CONTRATO */

            //EST_NOTA_FISCAL_PRODUTO
            $classNFProduto->setProjeto($data->sheets[0]['cells'][$i][5]); // PROJETO
            $classNFProduto->setDescricao(addslashes($data->sheets[0]['cells'][$i][2]));
            $classNFProduto->setUnidade('un');
            $classNFProduto->setQuant($data->sheets[0]['cells'][$i][4]);
            $classNFProduto->setUnitario($data->sheets[0]['cells'][$i][17]);
            $classNFProduto->setTotal($data->sheets[0]['cells'][$i][17]);
            $classNFProduto->setOrigem('1');
            $classNFProduto->setCfop('0');
            $classNFProduto->setDataConferencia($data->sheets[0]['cells'][$i][13].' 18:00:00');
            $classNFProduto->incluiNotaFiscalProduto();
            //ORDEM DE SERVIÇO
            $OrdemServisoOBJ = new c_ordemServico();
            $OrdemServisoOBJ->setNumChamadoSolicitante($data->sheets[0]['cells'][$i][9]);
            $ArrOS = $OrdemServisoOBJ->select_ordemServicoSolicitante();
            if (is_array($ArrOS)){
                $classNfProdutoOs->setDoc($ArrOS[0]['ID']);
            }
            $qtde = (int) $data->sheets[0]['cells'][$i][4];
            for ($l = 0; $l < $qtde; $l++) {
                //  echo "passou for nfprodutoos".$qtde;
                $classNfProdutoOs->setIdNfEntrada($classNFProduto->getIdNf());
                $classNfProdutoOs->setCodProduto($classNFProduto->getCodProduto());
                $classNfProdutoOs->setProjeto($data->sheets[0]['cells'][$i][5]);
                $classNfProdutoOs->setCentroCusto($this->m_empresacentrocusto);
                $classNfProdutoOs->setUserProduto($data->sheets[0]['cells'][$i][7]);
                $classNfProdutoOs->setLocalizacao($data->sheets[0]['cells'][$i][6]);
                $classNfProdutoOs->setAplicado($data->sheets[0]['cells'][$i][8]);
                $classNfProdutoOs->incluiNFProdutoOs();
                $contadorGeral ++;
            }

            echo "CODIGO:" . $data->sheets[0]['cells'][$i][1] . " - NF:" . $data->sheets[0]['cells'][$i][3] . " - QTDE:" . $data->sheets[0]['cells'][$i][4] . "LINHA" . $i;
            if (is_array($ArrOS)){
                echo ' Ordem de servico: '.$data->sheets[0]['cells'][$i][9]."<BR>";
            }else{
                echo ' Ordem de servico: Nao foi localizado Ordem de Servico!! - '.$data->sheets[0]['cells'][$i][9].'<BR>';
            }
            echo "CODIGO:" . $data->sheets[0]['cells'][$i][1] . " - NF:" . $data->sheets[0]['cells'][$i][3] . " - QTDE:" . $data->sheets[0]['cells'][$i][4] . "LINHA" . $i . "<br>";
        } // for
        echo "Total de Produtos importado: " . $contadorGeral . ". - Importa&ccedil;&atilde;o efetuado com sucesso. ";
    }

//fim positivo instalacao
    public function select_importa_excel_produto_lote() {

        set_time_limit(1000);
        ini_set('max_execution_time', 0);

        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);
//error_reporting(E_ALL ^ E_NOTICE);

        /*
         * formato da planilha
         * 1 - codfabricante
         * 2 - descricao do produto
         * 3 - unidade
         * 4 - qtde da peça (novo ou usado)
         * 5 - num nf
         * 6 - localizacao
         * 7 - Num Lote
         * 8 - Validade
         * 9 - Origem
         * 10 - Sit Trib
         * 11 - fabricante
         * 12 - valor ultima compra
         */
        $contadorGeral = 0;
        $codProduto = "";
        $classProduto = new c_produto();
        $classNfProdutoOs = new c_nota_fiscal_produto_os();

        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { // $data->sheets[0]['cells'][$i][1]
            if ($data->sheets[0]['cells'][$i][2] != '') {

                // se não existe o produto cadastro, se existe atualiza a localização.
                $classProduto->setCodFabricante($data->sheets[0]['cells'][$i][1]);
                $produto = $classProduto->select_produto_fabricante();
                if ($produto[0]['CODIGO'] == '') {
                    $classProduto->setDesc($data->sheets[0]['cells'][$i][2]);
                    $classProduto->setUni($data->sheets[0]['cells'][$i][3]); 
                    $classProduto->setCodFabricante($data->sheets[0]['cells'][$i][1]);
                    $classProduto->setLocalizacao($data->sheets[0]['cells'][$i][6]);
                    $classProduto->setFabricante($data->sheets[0]['cells'][$i][11]); 
                    $classProduto->setOrigem($data->sheets[0]['cells'][$i][9]); 
                    $classProduto->setTribIcms($data->sheets[0]['cells'][$i][10]); 
                    $classProduto->setCustoCompra($data->sheets[0]['cells'][$i][12]); 
                    $classProduto->setDataCadastro(date("d-m-Y"));

                    $classProduto->setObs('Produto cadastrado atraves de importacao.');
                    $codProduto = $classProduto->incluiProduto();
                } else {
                    //$classProduto->setLocalizacao($data->sheets[0]['cells'][$i][6]);
                    //  $classProduto->setId($produto[0]['CODIGO']);
                    // $classProduto->alteraProdutoLocalizacao();
                    $codProduto = $produto[0]['CODIGO'];
                }//FIM DADOS DO PRODUTO
            }//FIM PESQUISA PRODUTO
                
        echo "passou3 ".$codProduto." ".$data->sheets[0]['cells'][$i][1];  
            // nf produto os
            $qtde = (int) $data->sheets[0]['cells'][$i][4];
            for ($l = 0; $l < $qtde; $l++) {
            //    echo "passou for nfprodutoos".$qtde;
                $classNfProdutoOs->setCodProduto($data->sheets[0]['cells'][$i][1]);
                $classNfProdutoOs->setIdNfEntrada($data->sheets[0]['cells'][$i][5]);
                $classNfProdutoOs->setCodProduto($codProduto);
                $classNfProdutoOs->setProjeto(0);
                $classNfProdutoOs->setCentroCusto($this->m_empresacentrocusto);
                $classNfProdutoOs->setUserProduto(0);
                $classNfProdutoOs->setLocalizacao($data->sheets[0]['cells'][$i][6]);
                $classNfProdutoOs->setAplicado("");
                $classNfProdutoOs->setLote($data->sheets[0]['cells'][$i][7]);
                $classNfProdutoOs->setDataValidade($data->sheets[0]['cells'][$i][15].'/'.$data->sheets[0]['cells'][$i][16].'/'.$data->sheets[0]['cells'][$i][14]);
                $classNfProdutoOs->set($data->sheets[0]['cells'][$i][15].'/'.$data->sheets[0]['cells'][$i][16].'/'.$data->sheets[0]['cells'][$i][14]);
                $classNfProdutoOs->incluiNFProdutoOs();
                //echo " produto";
                $contadorGeral ++;
            }

            echo "CODIGO:" . $data->sheets[0]['cells'][$i][1] . " - NF:" . $data->sheets[0]['cells'][$i][3] . " - QTDE:" . $data->sheets[0]['cells'][$i][4] . "LINHA" . $i;
        } // for
        echo "Total de Produtos importado: " . $contadorGeral . ". - Importa&ccedil;&atilde;o efetuado com sucesso. ";
    }

//fim produto lote

////---------------------------------------------------------------
// PLANILHA INCLUIR UMA LINHA EM BRANCO NO FINAL
//---------------------------------------------------------------
    public function select_importa_excel() {


        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);
//error_reporting(E_ALL ^ E_NOTICE);

        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
            //$descricao = str_replace("'","",$data->sheets[0]['cells'][$i][2]);
            //$descricao = str_replace($what, $by, $descricao);

            $produto = new c_produto;
            $produto->setDesc($data->sheets[0]['cells'][$i][2]);
            $produto->setUni('PC');
            $produto->setFabricante('177821');
            $produto->setCodFabricante('000000000000000000' . $data->sheets[0]['cells'][$i][1]);
            $produto->setOrigem('0');
            $produto->setTribIcms('41');

            $produto->incluiProduto();



            echo $data->sheets[0]['cells'][$i][2] . " - " . $i . " - ok" . "<br>";
        } // for
    }

//---------------------------------------------------------------
// PLANILHA INCLUIR UMA LINHA EM BRANCO NO FINAL
//---------------------------------------------------------------
    public function select_importa_excel_ufCliente() {


        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);
//error_reporting(E_ALL ^ E_NOTICE);

        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
            //$descricao = str_replace("'","",$data->sheets[0]['cells'][$i][2]);
            //$descricao = str_replace($what, $by, $descricao);

            $classCliente = new c_pessoa();
            $classCliente->setNome($data->sheets[0]['cells'][$i][2]);
            $classCliente->setNomeReduzido(substr($data->sheets[0]['cells'][$i][2], 0, 15));
            $classCliente->setEndereco($data->sheets[0]['cells'][$i][2]);
            $classCliente->setCidade($data->sheets[0]['cells'][$i][2]);
            $classCliente->setEstado($data->sheets[0]['cells'][$i][1]);
            $classCliente->setCep('80000000');
            $classCliente->setPessoa('J');
            $classCliente->setClasse('01');
            $classCliente->setObs('Cliente cadastrado atraves de importacao.');
            $classCliente->setCentroCusto($this->m_empresacentrocusto);
            $classCliente->setVendedor($this->m_userid);
            $classCliente->incluiPessoa();


            echo "Cadastro OK UF: " . $classCliente->getEstado() . " - Cidade: " . $classCliente->getCidade() . " - Linha: " . $i . " - ok" . "<br>";
        } // for
    }

    public function select_user_produto() {


        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);
//error_reporting(E_ALL ^ E_NOTICE);

        for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
            $classNF = new c_nota_fiscal();
            $classUserProduto = new c_user_produto();
            $classProduto = new c_produto();
            for ($l = 1; $l <= $data->sheets[0]['cells'][$i][4]; $l++) { // qtde
                $classUserProduto->setCodUser(1050); // colocar o cod produto NA MÃO
                $classUserProduto->setDataEntrega(date("d/m/Y H:m:s"));
                $classUserProduto->setUserEntrega($this->m_userid);
                $classUserProduto->setQuantidade(1);
                //$classUserProduto->setAplicado('S'); // caso o produto esteja aplicado
                // se não existe o produto cadastro, se existe atualiza a localização.
                $classProduto->setCodFabricante($data->sheets[0]['cells'][$i][1]);
                $produto = $classProduto->select_produto_fabricante();
                if (!$produto[0]['CODIGO'] == '') {
                    $classUserProduto->setCodProduto($produto[0]['CODIGO']);
                    $classUserProduto->setDesc($produto[0]['DESCRICAO']);
                    $classUserProduto->setUnidade($produto[0]['UNIDADE']);
                }//FIM DADOS DO PRODUTO
                $classUserProduto->incluiProdutoUser();
                echo "Cadastro OK cod: " . $data->sheets[0]['cells'][$i][1] . " - desc: " . $data->sheets[0]['cells'][$i][2] . " - Linha: " . $i . " - ok" . "<br>";
            }
        } // for
    }

//fim positivo instalacao
//---------------------------------------------------------------
//---------------------------------------------------------------
    function controle() {
        switch ($this->m_tipo) {
            case 'catcorreios':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_importa_excel_correios();
                }

                break;
            case 'posrat':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_clienteExcel();
                }

                break;
            case 'posratscp':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_clienteExcelScp();
                }

                break;
            case 'posratfat':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_clienteExcelFat();
                }

                break;
            case 'excelimport':
                $this->select_importa_excel();
                break;
            case 'posins':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_posInstalacao();
                }
            case 'userproduto':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_user_produto();
                }

                break;
            case 'excelimportAtend':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_importa_excel_atendimento();
                }
                break;
            case 'excelimportInv':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_importa_excel_inventario();
                }
                break;
            case 'excelimportLote':
                //if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_importa_excel_produto_lote();
                //}
                break;
            case 'excelimportUFCliente':
                if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->select_importa_excel_ufCliente();
                }
                break;

            default:
                //if ($this->verificaDireitoUsuario('CatAtualizaTotal', 'C')) {
                    $this->mostraos_baixa('');
                //}
        }
    }

// fim controle
//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraos_baixa($mensagem) {
        include $this->js . "/cat/s_ordem_servico.js";

        $regConsulta = new c_tools;
        ?>

        <form name = "os_total" method="post" action=<?php echo $_SERVER['SCRIPT_NAME']; ?> enctype="multipart/form-data">
            <input name=opcao type=hidden value=""> 
            <input	name=submenu type=hidden value=""> 
            <input name=id type=hidden value="">
            <input name=letra type=hidden value="">

            <table width="100%" border="0" align="center">
                <tr>
                    <td width="10" class="MarcadorTitulo">
                        <div align="center"><b>::</b></div>
                    </td>
                    <td width="550" class="TituloPagina"><b>Atualiza valor total O.S. </b></td>
                </tr>
            </table>
            <br>
            <table width="100%" border="0" align="center">
                <tr>
                    <td class=ColunaTitulo>Tipo Planilha</td>
                    <td class=ColunaTitulo>
                        <select name="tipo">
        <?php
        $arrayTipo[0]['ID'] = "posrat";
        $arrayTipo[0]['DESCRICAO'] = "Positivo RAT Planilha";
        $arrayTipo[1]['ID'] = "posratscp";
        $arrayTipo[1]['DESCRICAO'] = "Positivo RAT SCP";
        $arrayTipo[2]['ID'] = "posratfat";
        $arrayTipo[2]['DESCRICAO'] = "Positivo RAT Faturado";
        $arrayTipo[3]['ID'] = "posins";
        $arrayTipo[3]['DESCRICAO'] = "Positivo Instala&ccedil;&atilde;o";
        $arrayTipo[4]['ID'] = "userproduto";
        $arrayTipo[4]['DESCRICAO'] = "Importa Produto Tecnico";
        $arrayTipo[5]['ID'] = "catcorreios";
        $arrayTipo[5]['DESCRICAO'] = "Importa EXCEL Correios";
        $arrayTipo[6]['ID'] = "excelimport";
        $arrayTipo[6]['DESCRICAO'] = "Importa EXCEL Produto";
        $arrayTipo[7]['ID'] = "excelimportAtend";
        $arrayTipo[7]['DESCRICAO'] = "Importa EXCEL Atendimento";
        $arrayTipo[8]['ID'] = "excelimportInv";
        $arrayTipo[8]['DESCRICAO'] = "Importa EXCEL Inventario";
        $arrayTipo[9]['ID'] = "excelimportLote";
        $arrayTipo[9]['DESCRICAO'] = "Importa EXCEL Produto Lote";
        $arrayTipo[10]['ID'] = "excelimportUFCliente";
        $arrayTipo[10]['DESCRICAO'] = "Importa EXCEL UF Cliente";

        $select = $this->m_tipo;
        $regConsulta->comboArray($arrayTipo, "", $select);
        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class=ColunaTitulo>Digite a data da Planilha</td>
                    <td class=ColunaTitulo>
                        <input type="text" name="data" size="20" value=<?php echo $this->m_data; ?>>
                        Formato YYYY-MM-DD, n&atilde;o utilizar na op&ccedil;&atilde;o Positivo RAT SCP
                    </td>
                </tr>
                <tr>
                    <td class=ColunaTitulo>Selecione a Planilha</td>
                    <td class=ColunaTitulo><input type="file" name="arq" size="100" >
                    </td>
                </tr>
                <tr>
                    <td class=ColunaTitulo>Clique no bot&atilde;o ao lado para ATUALIZAR</td>
                    <td class=ColunaTitulo>
                        <input type="button" name="Submit" value="ATUALIZAR" class="CoresBotao" onClick="javascript:submitOsTotal();">
                    </td>
                </tr>
                <br>
                <br>
                <th><h3><b> &lowast; &lowast; &nbsp; Padr&otilde;es das Planilhas </b></h3></th>
                <tr class="DestacaLinha">        
                    <td class=ColunaTitulo>Positivo rat SCP</td>
                    <td class=ColunaTitulo>
                        3&deg; Coluna - Valor | 
                        4&deg; Coluna - Numero da rat | 
                        5&deg; Coluna - Data Formato YYYY-mm-dd H:m:s | <b>Obs.: Deixar primeira linha em branco(cabe&ccedil;alho) e n&atilde;o Digitar data no Formul&aacute;rio</b>.
                    </td>
                </tr>
                <tr class="DestacaLinha">        
                    <td class=ColunaTitulo>Positivo rat Faturado</td>
                    <td class=ColunaTitulo>
                        2&deg; Coluna - Numero da rat | 
                        Obs.: Colocar na linha: 1, coluna: 1, da Planilha. ~> <b>N&uacute;mero do Pedido de Compra e Digitar a Data Formato YYYY-mm-dd no Formul&aacute;rio</b>.
                    </td>
                </tr>
            </table>
            <br>
            <br>
        </FORM>
        <?php
    }

//fim mostraos_baixas
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$os_backlog = new p_os_backlog($_POST['submenu'], $_POST['data'], $_POST['tipo'], $_POST['letra']);

if (isset($_FILES['arq'])) {
    $os_backlog->m_name = $_FILES['arq']['name'];
} else {
    $os_backlog->m_name = '';
};
if (isset($_FILES['arq'])) {
    $os_backlog->m_tmp = $_FILES['arq']['tmp_name'];
} else {
    $os_backlog->m_tmp = '';
};
if (isset($_FILES['arq'])) {
    $os_backlog->m_type = $_FILES['arq']['type'];
} else {
    $os_backlog->m_type = '';
};
if (isset($_FILES['arq'])) {
    $os_backlog->m_size = $_FILES['arq']['size'];
} else {
    $os_backlog->m_size = '';
};

$os_backlog->controle();
?>