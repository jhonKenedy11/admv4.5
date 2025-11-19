<?php

/**
 * @package   astec
 * @name      p_importa
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      12/04/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;
$dir = dirname(__FILE__);
require_once($dir . "/../../class/est/c_ncm.php");
require_once($dir . "/../../bib/reader.php");
include_once($dir . "/../../bib/c_tools.php");
require_once($dir . "/../../../smarty/libs/Smarty.class.php");
require_once($dir . "/../../class/crm/c_conta.php");
require_once($dir."/../../class/fin/c_conta_banco.php");
require_once($dir . "/../../class/est/c_produto.php");
require_once($dir . "/../../class/est/c_produto_estoque.php");
require_once($dir . "/../../class/est/c_nota_fiscal.php");
require_once($dir . "/../../class/est/c_nota_fiscal_produto.php");
require_once($dir . "/../../class/fin/c_extrato.php");
require_once($dir . "/../../class/fin/c_genero.php");
require_once($dir . "/../../class/fin/c_lancamento.php");


//Class P_situacao
Class p_importa extends c_conta {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;
    public $m_name = NULL;
    public $m_tmp = NULL;
    public $m_type = NULL;
    public $m_size = NULL;

    /**
     * <b> Função magica construct </b>
     * @param VARCHAR $submenu
     * @param VARCHAR $letra
     * 
     */
    function __construct() {

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;
    
        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/util";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        // inicializa variaveis de controle
        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->m_submenu = $parmPost['arqImporta'];

        // caminhos absolutos para todos os diretorios biblioteca e sistema
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

               // dados para exportacao e relatorios
               $this->smarty->assign('titulo', "Motivo");
               $this->smarty->assign('colVis', "[ 0, 1, 2]"); 
               $this->smarty->assign('disableSort', "[ 2 ]"); 
               $this->smarty->assign('numLine', "25"); 
       

        
        // include do javascript
        // include ADMjs . "/util/s_util.js";
    }

/**
* <b> É responsavel para indicar para onde o sistema ira executar </b>
* @name controle
* @param VARCHAR submenu 
* @return vazio
*/
    function controle() {
        switch ($this->m_submenu) {
            case 'boletoFinanceiro':
                if ($this->verificaDireitoUsuario('UtilImporta', 'I')) {
                    $this->mostraImporta('');
                    $this-> excelBoletosFinanceiro();
                }
                break;
            case 'extratorepassemkt':
                if ($this->verificaDireitoUsuario('UtilImporta', 'I')) {
                    $this->mostraImporta('');
                    $this->excelExtratoRepasseMkt();
                }
                break;
            case 'financeiro':
                if ($this->verificaDireitoUsuario('UtilImporta', 'I')) {
                    $this->mostraImporta('');
                    $this->excelBoletosConvenio();
                }
                break;
            case 'produtosquant':
                if ($this->verificaDireitoUsuario('UtilImporta', 'I')) {
                    $this->mostraImporta('');
                    $this->excelProdutosQuant();
                }
                break;
            case 'saidaprodutosquant':
                if ($this->verificaDireitoUsuario('UtilImporta', 'I')) {
                    $this->mostraImporta('');
                    $this->excelSaidaProdutosQuant();
                }
                break;
            case 'pessoa':
                if ($this->verificaDireitoUsuario('UtilImporta', 'I')) {
                    $this->mostraImporta('');
                    $this->excelImportaPessoa();
                }    
                break;
            case 'ibpt':
                if ($this->verificaDireitoUsuario('UtilImporta', 'I')) {
                    $this->mostraImporta('');
                    $this->excelUpdateIBPT();
                }    
                break;    
            default:
                if ($this->verificaDireitoUsuario('UtilImporta', 'C')) {
                    $this->mostraImporta('');
                }
        }
    }

    function remove_cnpj($var) {

        $var = str_replace("'", "", $var);
        $var = str_replace(".", "", $var);
        $var = str_replace("/", "", $var);
        $var = str_replace("-", "", $var);

        return $var;
    }

    function remove_acento($str) {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ',"'"); 
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o',''); 
        $str = str_replace($a, $b, $str); 
        return $str; 
  }

    // function remove_acento($var) {
    //     $var = strtoupper($var);
    //     $var = ereg_replace("[áàâãª]", "a", $var);
    //     $var = ereg_replace("[ÂÃÁÀ]", "A", $var);
    //     $var = ereg_replace("[éèê]", "e", $var);
    //     $var = ereg_replace("[óòôõº]", "o", $var);
    //     $var = ereg_replace("[ÕÓÒº]", "O", $var);
    //     $var = ereg_replace("[úùû]", "u", $var);
    //     $var = str_replace("ç", "c", $var);
    //     $var = str_replace("'", "", $var);

    //     return $var;
    // }


/*---------------------------------------------------------------
PLANILHA INCLUIR UMA LINHA EM BRANCO NO FINAL
Formato ATUALIZADO da planilha (20 colunas):
1 - RAZÃO SOCIAL (será usado também como NOMEREDUZIDO)
2 - CNPJ/CPF (detecção automática de pessoa J ou F)
3 - IE/RG 
4 - CEP (usado para buscar código IBGE via API ViaCEP)
5 - ENDEREÇO (se vazio, tenta buscar da API)
6 - NUMERO
7 - COMPLEMENTO
8 - BAIRRO (se vazio, tenta buscar da API)
9 - CIDADE (se vazio, tenta buscar da API)
10 - UF (se vazio, tenta buscar da API)
11 - TELEFONE (ex: (41) 3382-3188)
12 - CELULAR
13 - EMAIL (usado também como EMAIL NFE)
14 - CONTATO (nome do contato)
15 - HOMEPAGE
16 - DATA NASCIMENTO (formato: dd/mm/aaaa ou vazio)
17 - INSCRIÇÃO MUNICIPAL
18 - RESPONSAVEL (nome do vendedor - busca no AMB_USUARIO)
19 - CLASSE (ID ou descrição)
20 - ATIVIDADE (ID ou descrição)
---------------------------------------------------------------*/
    function excelImportaPessoa() {
        $data=new Spreadsheet_Excel_Reader();
        $data->setUTFEncoder('UTF-8');
        $data->setOutputEncoding('UTF-8');
        $data->read($this->m_tmp);

        $erroGeral = 0;
        $table = '';
        $htmlResultados = ''; // HTML dos resultados
    
    // Monta tabela de visualização
    for($r=2; $r<=$data->sheets[0]['numRows']; $r++) {  
        $erro = 0;
        $linha = '';
        
        for($c=1; $c<=20; $c++) {
            $valor = isset($data->sheets[0]['cells'][$r][$c]) ? $data->sheets[0]['cells'][$r][$c] : '';
            
            $tdStyle = "style='border: 1px solid #ddd; padding: 4px;'";
            
            if ($c == 1) { // Razão Social
                $linha .= "<td width='12%' $tdStyle>".htmlspecialchars(substr($valor, 0, 25))."</td>";
                if ($valor == '') { $erro = 1; }
            } else if ($c == 2) { // CNPJ/CPF
                $linha .= "<td width='8%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 3) { // IE/RG
                $linha .= "<td width='7%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 4) { // CEP
                $linha .= "<td width='6%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 5) { // Endereço
                $linha .= "<td width='10%' $tdStyle>".htmlspecialchars(substr($valor, 0, 20))."</td>";
            } else if ($c == 6) { // Número
                $linha .= "<td width='3%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 7) { // Complemento
                $linha .= "<td width='6%' $tdStyle>".htmlspecialchars(substr($valor, 0, 10))."</td>";
            } else if ($c == 8) { // Bairro
                $linha .= "<td width='7%' $tdStyle>".htmlspecialchars(substr($valor, 0, 15))."</td>";
            } else if ($c == 9) { // Cidade
                $linha .= "<td width='8%' $tdStyle>".htmlspecialchars(substr($valor, 0, 15))."</td>";
            } else if ($c == 10) { // UF
                $linha .= "<td width='2%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 11) { // Telefone
                $linha .= "<td width='7%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 12) { // Celular
                $linha .= "<td width='7%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 13) { // Email
                $linha .= "<td width='10%' $tdStyle>".htmlspecialchars(substr($valor, 0, 20))."</td>";
            } else if ($c == 14) { // Contato
                $linha .= "<td width='6%' $tdStyle>".htmlspecialchars(substr($valor, 0, 15))."</td>";
            } else if ($c == 15) { // Homepage
                $linha .= "<td width='7%' $tdStyle>".htmlspecialchars(substr($valor, 0, 15))."</td>";
            } else if ($c == 16) { // Data Nascimento
                $linha .= "<td width='5%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 17) { // Inscrição Municipal
                $linha .= "<td width='6%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 18) { // Responsável
                $linha .= "<td width='7%' $tdStyle>".htmlspecialchars(substr($valor, 0, 15))."</td>";
            } else if ($c == 19) { // Classe
                $linha .= "<td width='4%' $tdStyle>".htmlspecialchars($valor)."</td>";
            } else if ($c == 20) { // Atividade
                $linha .= "<td width='4%' $tdStyle>".htmlspecialchars($valor)."</td>";
            }
        }
        
        if ($erro == 1) {
            $erroGeral = 1;   
            $table .= '<tr bgcolor="#FF0000">'.$linha.'</tr>';
        } else {
            $table .= '<tr>'.$linha.'</tr>';
        }
    }

    if ($erroGeral == 1) {
        // Se houver erro, monta HTML e passa para template
        $htmlResultados = "</BR></BR><h1>ERROR: VERIFIQUE ARQUIVO (LINHAS em VERMELHO)</h1>";
        $htmlResultados .= "<table style='border-collapse: collapse; font-size: 11px;' border='1'>".$table."</table>";
        
        $this->smarty->assign('html_resultado', $htmlResultados);
        $this->smarty->display('importa_resultado.tpl');
        return;
    }

    // Monta HTML da tabela de preview
    $htmlResultados = "<table style='border-collapse: collapse; font-size: 11px;' border='1'>".$table."</table>";
    $htmlResultados .= "<br><div style='font-size: 11px; border: 1px solid #ccc; padding: 10px; margin-top: 10px;'>";

    // Se não houver erro, processa a importação
    $classPessoa = new c_conta;
    $contadorGeral=0;
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                        $razaoSocial = isset($data->sheets[0]['cells'][$i][1]) ? $data->sheets[0]['cells'][$i][1] : '';
            
            if ($razaoSocial != '') {
        $contadorGeral++;
                $msgErro = '';

                // 1. Razão Social (NOME e NOMEREDUZIDO)
                $classPessoa->setNome($this->remove_acento(utf8_encode(substr($razaoSocial, 0, 80))));
                $classPessoa->setNomeReduzido($this->remove_acento(utf8_encode(substr($razaoSocial, 0, 60))));

                // 2. CNPJ/CPF e detecção de tipo de pessoa
                $cnpjCpf = isset($data->sheets[0]['cells'][$i][2]) ? $this->remove_cnpj($data->sheets[0]['cells'][$i][2]) : '';
                $cnpjCpfLen = strlen($cnpjCpf);
                
                if ($cnpjCpfLen == 11) {
                    $classPessoa->setPessoa('F'); // CPF = Pessoa Física
                    $classPessoa->setCnpjCpf($cnpjCpf);
                } else {
                    $classPessoa->setPessoa('J'); // CNPJ = Pessoa Jurídica
                    $classPessoa->setCnpjCpf(str_pad($cnpjCpf, 14, "0", STR_PAD_LEFT));
                }

                // 3. IE/RG
                $ieRg = isset($data->sheets[0]['cells'][$i][3]) ? $data->sheets[0]['cells'][$i][3] : '';
                $classPessoa->setIeRg($this->remove_cnpj($ieRg));

                // 4. CEP - Busca dados pela API ViaCEP
                $cep = isset($data->sheets[0]['cells'][$i][4]) ? $this->remove_cnpj($data->sheets[0]['cells'][$i][4]) : '';
                $dadosCep = false;
                $codMunicipio = '';
                
                if ($cep != '' && strlen($cep) == 8) {
                    $dadosCep = $this->buscaDadosCEP($cep);
                    if ($dadosCep) {
                        $codMunicipio = $dadosCep['ibge'];
                        $msgErro .= " [CEP consultado via API]";
                    }
                }
                
                $classPessoa->setCep($cep);
                $classPessoa->setCodMunicipio($codMunicipio); // Código IBGE do município

                // 5. Endereço (se vazio, tenta pegar da API)
                $endereco = isset($data->sheets[0]['cells'][$i][5]) ? trim($data->sheets[0]['cells'][$i][5]) : '';
                if ($endereco == '' && $dadosCep && $dadosCep['logradouro'] != '') {
                    $endereco = $dadosCep['logradouro'];
                }
                $classPessoa->setEndereco($this->remove_acento(utf8_encode(substr($endereco, 0, 60))));
                
                // 6. Número
                $numero = isset($data->sheets[0]['cells'][$i][6]) ? $data->sheets[0]['cells'][$i][6] : '';
                $classPessoa->setNumero($numero != '' ? $numero : 'S/N');
                
                // 7. Complemento
                $complemento = isset($data->sheets[0]['cells'][$i][7]) ? trim($data->sheets[0]['cells'][$i][7]) : '';
                if ($complemento == '' && $dadosCep && $dadosCep['complemento'] != '') {
                    $complemento = $dadosCep['complemento'];
                }
                $classPessoa->setComplemento($this->remove_acento(utf8_encode(substr($complemento, 0, 15))));
                
                // 8. Bairro (se vazio, tenta pegar da API)
                $bairro = isset($data->sheets[0]['cells'][$i][8]) ? trim($data->sheets[0]['cells'][$i][8]) : '';
                if ($bairro == '' && $dadosCep && $dadosCep['bairro'] != '') {
                    $bairro = $dadosCep['bairro'];
                }
                $classPessoa->setBairro($this->remove_acento(utf8_encode(substr($bairro, 0, 60))));
                
                // 9. Cidade (se vazio, tenta pegar da API)
                $cidade = isset($data->sheets[0]['cells'][$i][9]) ? trim($data->sheets[0]['cells'][$i][9]) : '';
                if ($cidade == '' && $dadosCep && $dadosCep['localidade'] != '') {
                    $cidade = $dadosCep['localidade'];
                }
                $classPessoa->setCidade($this->remove_acento(utf8_encode(substr($cidade, 0, 40))));
                
                // 10. UF (se vazio, tenta pegar da API)
                $uf = isset($data->sheets[0]['cells'][$i][10]) ? strtoupper(trim($data->sheets[0]['cells'][$i][10])) : '';
                if ($uf == '' && $dadosCep && $dadosCep['uf'] != '') {
                    $uf = $dadosCep['uf'];
                }
                $classPessoa->setEstado($uf);

                // 11. Telefone
                $telefone = isset($data->sheets[0]['cells'][$i][11]) ? $data->sheets[0]['cells'][$i][11] : '';
                $classPessoa->setFone($this->remove_acento(utf8_encode(substr($telefone, 0, 15))));

                // 12. Celular
                $celular = isset($data->sheets[0]['cells'][$i][12]) ? $data->sheets[0]['cells'][$i][12] : '';
                $classPessoa->setCelular($this->remove_acento(utf8_encode(substr($celular, 0, 15))));

                // 13. Email (usado também como EMAIL NFE)
                $email = isset($data->sheets[0]['cells'][$i][13]) ? trim($data->sheets[0]['cells'][$i][13]) : '';
                $classPessoa->setEmail($this->remove_acento(utf8_encode($email)));
                $classPessoa->setEmailNfe($this->remove_acento(utf8_encode($email))); // Mesmo email para NFE

                // 14. Contato
                $contato = isset($data->sheets[0]['cells'][$i][14]) ? $data->sheets[0]['cells'][$i][14] : '';
                $classPessoa->setContato($this->remove_acento(utf8_encode(substr($contato, 0, 15))));

                // 15. Homepage
                $homepage = isset($data->sheets[0]['cells'][$i][15]) ? $data->sheets[0]['cells'][$i][15] : '';
                $classPessoa->setHomePage($this->remove_acento(utf8_encode($homepage)));

                // 16. Data Nascimento (formato: dd/mm/aaaa)
                $dataNascimento = isset($data->sheets[0]['cells'][$i][16]) ? trim($data->sheets[0]['cells'][$i][16]) : '';
                if ($dataNascimento != '') {
                    // Converte dd/mm/aaaa para aaaa-mm-dd
                    if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dataNascimento, $matches)) {
                        $classPessoa->setDataNascimento($matches[3].'-'.$matches[2].'-'.$matches[1]);
                    }
                }

                // 17. Inscrição Municipal
                $inscMunicipal = isset($data->sheets[0]['cells'][$i][17]) ? $data->sheets[0]['cells'][$i][17] : '';
                $classPessoa->setIm($this->remove_cnpj($inscMunicipal));

                // 18. Responsável (Vendedor) - Busca no AMB_USUARIO pelo nome
                $nomeVendedor = isset($data->sheets[0]['cells'][$i][18]) ? trim($data->sheets[0]['cells'][$i][18]) : '';
                $idVendedor = $this->buscaVendedorPorNome($nomeVendedor);
                
                if ($idVendedor) {
                    $classPessoa->setRepresentante($idVendedor);
                } else {
                    $classPessoa->setRepresentante($this->m_userid);
                    if ($nomeVendedor != '') {
                        $msgErro .= " [Vendedor '$nomeVendedor' não encontrado, usando usuário logado]";
                    }
                }

                // 19. Classe - Busca por ID ou Descrição
                $classeRef = isset($data->sheets[0]['cells'][$i][19]) ? trim($data->sheets[0]['cells'][$i][19]) : '';
                $idClasse = $this->buscaClassePorIdOuDesc($classeRef);
                if ($idClasse) {
                    $classPessoa->setClasse($idClasse);
                } else {
                    $classPessoa->setClasse('01'); // Classe padrão
                    if ($classeRef != '') {
                        $msgErro .= " [Classe '$classeRef' não encontrada, usando padrão '01']";
                    }
                }

                // 20. Atividade - Busca por ID ou Descrição
                $atividadeRef = isset($data->sheets[0]['cells'][$i][20]) ? trim($data->sheets[0]['cells'][$i][20]) : '';
                $idAtividade = $this->buscaAtividadePorIdOuDesc($atividadeRef);
                if ($idAtividade) {
                    $classPessoa->setAtividade($idAtividade);
                } else {
                    $classPessoa->setAtividade(''); // Atividade vazia se não encontrar
                    if ($atividadeRef != '') {
                        $msgErro .= " [Atividade '$atividadeRef' não encontrada]";
                    }
                }

                // Configurações padrão
                $classPessoa->setObs('Cliente cadastrado através de importação. Data Cadastro: '.date('d/m/Y H:i:s'));
                $classPessoa->setCentroCusto($this->m_empresacentrocusto);
        $classPessoa->setRegimeEspecialST('N');
                $classPessoa->setRegimeEspecialSTMsg('');
        $classPessoa->setRegimeEspecialSTMT('N');
        $classPessoa->setContribuinteICMS('N');
        $classPessoa->setConsumidorFinal('N');
        $classPessoa->setRegimeEspecialSTMTAliq('0');
        $classPessoa->setRegimeEspecialSTAliq('0');
                
        try {
            $classPessoa->incluiConta();
            $htmlResultados .= "<div style='padding: 3px; border-bottom: 1px solid #e0e0e0;'>✅ Cadastro OK ==> " . htmlspecialchars($classPessoa->getNome()) . " - Cidade: " . htmlspecialchars($cidade) . " - Linha: " . $contadorGeral . htmlspecialchars($msgErro) . "</div>";
        } catch (Exception $e) {
            $htmlResultados .= "<div style='padding: 3px; border-bottom: 1px solid #e0e0e0; color: #d9534f;'>❌ ERRO ==> " . htmlspecialchars($classPessoa->getNome()) . " - Cidade: " . htmlspecialchars($cidade) . " - Linha: " . $contadorGeral . " - " . htmlspecialchars($e->getMessage()) . htmlspecialchars($msgErro) . "</div>";
        }    
    }    
        } // for
        
        $htmlResultados .= "</div>"; // Fecha div de resultados
        
        // Passa HTML completo para o template Smarty
        $this->smarty->assign('html_resultado', $htmlResultados);
        $this->smarty->display('importa_resultado.tpl');
    }



    /**
     * Busca dados de endereço pelo CEP via API ViaCEP
     * @param string $cep CEP a ser consultado (apenas números)
     * @return array|false Retorna array com dados ou false se não encontrar
     */
    function buscaDadosCEP($cep) {
    // Remove formatação do CEP
    $cep = preg_replace('/[^0-9]/', '', $cep);
    
    // Valida se tem 8 dígitos
    if (strlen($cep) != 8) {
        return false;
    }
    
    // Consulta API ViaCEP
    $url = "https://viacep.com.br/ws/{$cep}/json/";
    
    try {
        // Usa cURL para fazer a requisição
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout de 5 segundos
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desabilita verificação SSL (caso necessário)
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode == 200 && $response) {
            $dados = json_decode($response, true);
            
            // Verifica se não retornou erro
            if (!isset($dados['erro'])) {
                return array(
                    'cep' => $dados['cep'],
                    'logradouro' => isset($dados['logradouro']) ? $dados['logradouro'] : '',
                    'complemento' => isset($dados['complemento']) ? $dados['complemento'] : '',
                    'bairro' => isset($dados['bairro']) ? $dados['bairro'] : '',
                    'localidade' => isset($dados['localidade']) ? $dados['localidade'] : '',
                    'uf' => isset($dados['uf']) ? $dados['uf'] : '',
                    'ibge' => isset($dados['ibge']) ? $dados['ibge'] : ''
                );
            }
        }
        
        return false;
    } catch (Exception $e) {
        return false;
    }
    }

    /**
     * Busca vendedor no AMB_USUARIO pelo nome
     * @param string $nome Nome ou parte do nome do vendedor
     * @return int|false ID do vendedor ou false se não encontrado
     */
    function buscaVendedorPorNome($nome) {
    if (empty($nome)) {
        return false;
    }
    
    $nome = $this->remove_acento(strtoupper(trim($nome)));
    
    // Busca vendedor comparando sem acentos
    $sql = "SELECT USUARIO, NOME, NOMEREDUZIDO FROM AMB_USUARIO WHERE SITUACAO = 'A'";
    
    $banco = new c_banco;
    $result = $banco->exec_sql($sql);
    $banco->close_connection();
    
    if (is_array($result) && count($result) > 0) {
        // PRIORIDADE 1: Match exato no nome completo ou reduzido
        foreach ($result as $row) {
            $nomeCompleto = $this->remove_acento(strtoupper(trim($row['NOME'])));
            $nomeReduzido = $this->remove_acento(strtoupper(trim($row['NOMEREDUZIDO'])));
            
            if ($nomeCompleto == $nome || $nomeReduzido == $nome) {
                return $row['USUARIO'];
            }
        }
        
        // PRIORIDADE 2: Nome começa com a busca
        foreach ($result as $row) {
            $nomeCompleto = $this->remove_acento(strtoupper(trim($row['NOME'])));
            $nomeReduzido = $this->remove_acento(strtoupper(trim($row['NOMEREDUZIDO'])));
            
            if (strpos($nomeCompleto, $nome) === 0 || strpos($nomeReduzido, $nome) === 0) {
                return $row['USUARIO'];
            }
        }
        
        // PRIORIDADE 3: Busca contida (apenas se nome busca for longo - mais de 5 caracteres)
        if (strlen($nome) > 5) {
            foreach ($result as $row) {
                $nomeCompleto = $this->remove_acento(strtoupper(trim($row['NOME'])));
                $nomeReduzido = $this->remove_acento(strtoupper(trim($row['NOMEREDUZIDO'])));
                
                if (strpos($nomeCompleto, $nome) !== false || strpos($nomeReduzido, $nome) !== false) {
                    return $row['USUARIO'];
                }
            }
        }
    }
    
    return false;
    }

    /**
     * Busca classe por ID ou Descrição
     * @param string $referencia ID ou Descrição da classe
     * @return string|false ID da classe ou false se não encontrado
     */
    function buscaClassePorIdOuDesc($referencia) {
    if (empty($referencia)) {
        return false;
    }
    
    $referencia = $this->remove_acento(strtoupper(trim($referencia)));
    
    // Busca por ID exato ou Descrição (comparando sem acentos)
    $sql = "SELECT CLASSE, DESCRICAO FROM FIN_CLASSE";
    
    $banco = new c_banco;
    $result = $banco->exec_sql($sql);
    $banco->close_connection();
    
    if (is_array($result) && count($result) > 0) {
        // PRIMEIRO: Busca por código exato (prioridade)
        foreach ($result as $row) {
            $classeId = $this->remove_acento(strtoupper(trim($row['CLASSE'])));
            if ($classeId == $referencia) {
                return $row['CLASSE'];
            }
        }
        
        // SEGUNDO: Se não encontrou por código, busca na descrição (apenas se referência for longa)
        if (strlen($referencia) > 3) {
            foreach ($result as $row) {
                $classeDesc = $this->remove_acento(strtoupper(trim($row['DESCRICAO'])));
                if (strpos($classeDesc, $referencia) !== false) {
                    return $row['CLASSE'];
                }
            }
        }
    }
    
    return false;
    }

    /**
     * Busca atividade por ID ou Descrição
     * @param string $referencia ID ou Descrição da atividade
     * @return string|false ID da atividade ou false se não encontrado
     */
    function buscaAtividadePorIdOuDesc($referencia) {
    if (empty($referencia)) {
        return false;
    }
    
    $referencia = $this->remove_acento(strtoupper(trim($referencia)));
    
    // Busca por ID exato ou Descrição (comparando sem acentos)
    $sql = "SELECT ATIVIDADE, DESCRICAO FROM FIN_ATIVIDADE";
    
    $banco = new c_banco;
    $result = $banco->exec_sql($sql);
    $banco->close_connection();
    
    if (is_array($result) && count($result) > 0) {
        // PRIMEIRO: Busca por código exato (prioridade)
        foreach ($result as $row) {
            $atividadeId = $this->remove_acento(strtoupper(trim($row['ATIVIDADE'])));
            if ($atividadeId == $referencia) {
                return $row['ATIVIDADE'];
            }
        }
        
        // SEGUNDO: Se não encontrou por código, busca na descrição (apenas se referência for longa)
        if (strlen($referencia) > 3) {
            foreach ($result as $row) {
                $atividadeDesc = $this->remove_acento(strtoupper(trim($row['DESCRICAO'])));
                if (strpos($atividadeDesc, $referencia) !== false) {
                    return $row['ATIVIDADE'];
                }
            }
        }
    }
    
    return false;
    }

    function excelBoletosFinanceiro() {

    set_time_limit(500);
    $f_name = $_FILES['file']['name'];
    $f_tmp = $_FILES['file']['tmp_name'];
    $f_type = $_FILES['file']['type'];
    
    $data=new Spreadsheet_Excel_Reader();

    $data->setOutputEncoding('UTF-8');
    //$data->read($filename);
    $data->read($this->m_tmp);
   
    $erroGeral = 0;
   
    $table = '';
    for ($r=2; $r<=$data->sheets[0]['numRows']; $r++) {
        $linha = '';
        $erro = 0;
        for ($c=1; $c<=10; $c++) {
            if ($r == 1) {
                $linha .= "<td width= '10%'><b>".strtoupper(utf8_encode($data->sheets[0]['cells'][$r][$c]))."</b></td>";
            } else {
                $linha .= "<td width= '10%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
            }
        } 
    
        if ($erro == 1) {
            $erroGeral = 1;
            $table .= '<tr bgcolor="#FF0000">'.$linha.'</tr>';
        } else {
            $table .= '<tr>'.$linha.'</tr>';
        }
    }
    echo "<table>".$table."</table>";
    
    $classConta = new c_conta();
    $classLanc = new c_lancamento();
    $contadorGeral = 0;

    echo "<br>";

    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        if ($data->sheets[0]['cells'][$i][1] != '') {
            $contadorGeral++;

            // consulta pessoa pelo cnpj
            $cnpj = $this->remove_cnpj($data->sheets[0]['cells'][$i][6]);
            $arrPessoa = $classConta->existeContaCnpj($cnpj, true);
            if (is_array($arrPessoa)){
                
                $classLanc->setPessoa($arrPessoa[0]['CLIENTE']);

                $classLanc->setDocto($data->sheets[0]['cells'][$i][1]);
                $classLanc->setSerie($data->sheets[0]['cells'][$i][2]);
                $classLanc->setOrigem($data->sheets[0]['cells'][$i][3]);  
                $classLanc->setEmissao($data->sheets[0]['cells'][$i][4]);
                $classLanc->setMovimento($data->sheets[0]['cells'][$i][5]);
                $classLanc->setVencimento($data->sheets[0]['cells'][$i][5]);

        
                $classLanc->setOriginal(str_replace('.', ',',$data->sheets[0]['cells'][$i][7]));
                $classLanc->setTotal(str_replace('.', ',',$data->sheets[0]['cells'][$i][7])); //array
                $classLanc->setGenero($data->sheets[0]['cells'][$i][8]); // array
                $classLanc->setCentroCusto($data->sheets[0]['cells'][$i][9]);	// centro custo atual
                $classLanc->setConta($data->sheets[0]['cells'][$i][10]); 
                $classLanc->setModopgto($data->sheets[0]['cells'][$i][11]); // bancario
                $classLanc->setTipodocto($data->sheets[0]['cells'][$i][12]); // boleto
                
                $classLanc->setLancamento(date("d/m/Y")); // data atual
                
                $classLanc->setNumlcto(0);           
                $classLanc->setParcela(1);
                $classLanc->setTipolancamento('R');
                $classLanc->setSitdocto('N'); // normal
                $classLanc->setUsrsitpgto($classLanc->m_userid); //usuario
                
                $classLanc->setMulta(0);
                $classLanc->setJuros(0);
                $classLanc->setAdiantamento(0);
                $classLanc->setDesconto(0);
                $classLanc->setMoeda(0);
                $classLanc->setSitpgto('A'); // aberto
                $classLanc->setObs(''); //array

                try { 
                    $id = $classLanc->incluiLancamento();                                   
                    echo "Cadastro OK ==> Documento " . $data->sheets[0]['cells'][$i][1] . " - Serie: " . $data->sheets[0]['cells'][$i][2]. " - Linha: " . $contadorGeral . " - ok" . "<br>";
                } catch (Exception $e) {
                    echo "ERRO ==> Documento " . $data->sheets[0]['cells'][$i][1] . " - Serie: " . $data->sheets[0]['cells'][$i][2]. " - Linha: " . $contadorGeral . " - ok" . "<br>";
                }
        }   else    {
            echo "ERRO ==> Documento " . $data->sheets[0]['cells'][$i][1] . " - Serie: " . $data->sheets[0]['cells'][$i][2]. " - Linha: " . $contadorGeral .  " Pessoa não localizada, CNPJ: " .$cnpj." <br>";
        }                     
    }
    } // for
    }

    function excelBoletosConvenio() {

        // Set output Encoding.
        // set_time_limit(500);
        // $data = new Spreadsheet_Excel_Reader();
        // $data->setOutputEncoding('CP1251');
        // $data->read($this->m_tmp);

        // cria class
        $classConta = new c_conta();
        $classLanc = new c_lancamento();
        // $classGenero = new c_genero();
        $contadorGeral = 0;
        // $quant = $data->sheets[0]['numRows'];

        $f_name = $_FILES['arq']['name'];
        $f_tmp = $_FILES['arq']['tmp_name'];
        $f_type = $_FILES['arq']['type'];
        if (file_exists($f_tmp)):
            echo "<br>";
            //ABRE O ARQUIVO TXT
            $ponteiro = fopen ($f_tmp,"r");
            $linha = fgets($ponteiro); // cabeçalho

            //LÊ O ARQUIVO ATÉ  CHEGAR AO FIM 
            $linha = fgets($ponteiro);


            while (!feof ($ponteiro)) {
                $linha = str_replace("\n", '', $linha);
                $data = explode(";", $linha);

                if ($contadorGeral ==0){
                    // DADOS CONTA
                    $objContaBanco = new c_contaBanco;
                    $contaBanco = $data[18];
                    $objContaBanco->setId($contaBanco);

                    $conta = $objContaBanco->select_ContaBanco();
                    $codCarteira = str_pad($conta[0]['CARTEIRA'], 3, "0", STR_PAD_LEFT);
                    $numRemessa = $objContaBanco->geraNumeroRemessa($contaBanco, $conta[0]['NUMREMESSA']); // atualizar conta
                }

                $contadorGeral++;
                $msg = '';

                // consulta pessoa pelo cnpj
                $cnpj = str_pad($this->remove_cnpj($data[8]), 14, "0", STR_PAD_LEFT);
                $arrPessoa = $classConta->existeContaCnpj($cnpj, true);
                if (is_array($arrPessoa)){
                    $classLanc->setPessoa($arrPessoa[0]['CLIENTE']);
                }else{
                    $msg = "Pessoa não localizada, CNPJ: ".$cnpj;
                }

                $nn = substr($data[6], 3, 11);

                $classLanc->setCentroCusto($data[17]);

                $classLanc->setPessoa($arrPessoa[0]['CLIENTE']);
                $classLanc->setDocto($data[0]);
                $classLanc->setNossoNumero($nn); // *******
                $classLanc->setDocbancario($data[15]);
                $classLanc->setRemessaArq('BIG');
                $classLanc->setRemessaNum($numRemessa);
                $classLanc->setRemessaData($data[1]);
                $classLanc->setSerie('BIG');
                $classLanc->setParcela(1);
                $classLanc->setTipolancamento('R');
                $classLanc->setSitdocto('N'); // normal
                $classLanc->setUsrsitpgto($classLanc->m_userid); //usuario
                $classLanc->setModopgto('B'); // bancario
                $classLanc->setOrigem('BIG'); // ??/
                $classLanc->setNumlcto(0); // ??/
                $classLanc->setGenero($data[16]); // array
                $classLanc->setCentroCusto($data[17]);	// centro custo atual
                $classLanc->setLancamento(date("d/m/Y"));
                $classLanc->setEmissao(date("d/m/Y"));
                $classLanc->setMulta(0);
                $classLanc->setJuros(0);
                $classLanc->setAdiantamento(0);
                $classLanc->setDesconto(0);
                $classLanc->setMoeda(0);

                $classLanc->setTipodocto('B'); // boleto
                $classLanc->setSitpgto('A'); // aberto
                $classLanc->setConta($data[18]); //
    
                $classLanc->setVencimento($data[14]); //arry
                $classLanc->setMovimento($data[14]);
                $classLanc->setOriginal($data[9], true);
                $classLanc->setTotal($data[9], true); //array
                $classLanc->setObs(''); //array


                $id = $classLanc->incluiLancamento();
                // $classLanc->atualizaRemessa($id, $nn, $nr, $data, $arq){
                $classLanc->atualizaRemessa($id, $nn, $numRemessa, date("Y-m-d"), 'BIG');


                echo "CODIGO:" . $data[0] . "  ---   Valor:" .  "NOSSO NUMERO:" . $nn . "  ---   Valor:" .  $data[9] . " --- Mensagem: ".$msg. "<br>";
             
                //LÊ O ARQUIVO ATÉ  CHEGAR AO FIM 
                $linha = fgets($ponteiro);
    
            }//FECHA WHILE

            //FECHA O PONTEIRO DO ARQUIVO
            fclose ($ponteiro);
            
        endif;



        // for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { // $data->sheets[0]['cells'][$i][1]
        //     if ($data->sheets[0]['cells'][$i][1] > '0'){

        //         $contadorGeral++;
        //         $msg = '';

        //         // consulta pessoa pelo cnpj
        //         $cnpj = str_pad($this->remove_cnpj($data->sheets[0]['cells'][$i][8]), 14, "0", STR_PAD_LEFT);
        //         $arrPessoa = $classConta->existeContaCnpj($cnpj, true);
        //         if (is_array($arrPessoa)){
        //             $classLanc->setPessoa($arrPessoa[0]['CLIENTE']);
        //         }else{
        //             $msg = "Pessoa não localizada, CNPJ: ".$cnpj;
        //         }

        //         // // consulta genero lancamento
        //         // $classGenero->setGenero($data->sheets[0]['cells'][$i][16]);
        //         // $arrGenero = $classGenero->select_genero();
        //         // if (is_array($arrGenero)){
        //         //     $classLanc->setGenero($arrGenero[0]['GENERO']);
        //         //     $classLanc->setTipoLancamento($arrGenero[0]['TIPOLANCAMENTO']);
        //         // }else{
        //         //     $msg .= "Genero não localizado: ".$data->sheets[0]['cells'][$i][7];
        //         // }

        //         $classLanc->setCentroCusto($data->sheets[0]['cells'][$i][17]);

        //         $classLanc->setPessoa($arrPessoa['PESSOA']);
        //         $classLanc->setDocto($data->sheets[0]['cells'][$i][1]);
        //         $classLanc->setNossoNumero($data->sheets[0]['cells'][$i][6]); // *******
        //         $classLanc->setDocbancario($data->sheets[0]['cells'][$i][15]);
        //         $classLanc->setRemessaArq('BIG');
        //         $classLanc->setRemessaNum(99999999);
        //         $classLanc->setRemessaData($data->sheets[0]['cells'][$i][2]);
        //         $classLanc->setSerie('BIG');
        //         $classLanc->setParcela(1);
        //         $classLanc->setTipolancamento('R');
        //         $classLanc->setSitdocto('N'); // normal
        //         $classLanc->setUsrsitpgto($classLanc->m_userid); //usuario
        //         $classLanc->setModopgto('B'); // bancario
        //         $classLanc->setOrigem('BIG'); // ??/
        //         $classLanc->setNumlcto(0); // ??/
        //         $classLanc->setGenero("'".$data->sheets[0]['cells'][$i][16]."'"); // array
        //         $classLanc->setCentroCusto($data->sheets[0]['cells'][$i][17]);	// centro custo atual
        //         $classLanc->setLancamento(date("d/m/Y"));
        //         $classLanc->setEmissao(date("d/m/Y"));
        //         $classLanc->setMulta(0);
        //         $classLanc->setJuros(0);
        //         $classLanc->setAdiantamento(0);
        //         $classLanc->setDesconto(0);
        //         $classLanc->setMoeda(0);

        //         $classLanc->setTipodocto('B'); // boleto
        //         $classLanc->setSitpgto('A'); // aberto
        //         $classLanc->setConta($data->sheets[0]['cells'][$i][18]); //
    
        //         $classLanc->setVencimento($data->sheets[0]['cells'][$i][14]); //arry
        //         $classLanc->setMovimento($data->sheets[0]['cells'][$i][14]);
        //         $classLanc->setOriginal($data->sheets[0]['cells'][$i][9], true);
        //         $classLanc->setTotal($data->sheets[0]['cells'][$i][9], true); //array
        //         $classLanc->setObs(''); //array


        //         $classLanc->incluiLancamento();


        //         echo "CODIGO:" . $data->sheets[0]['cells'][$i][1] . "  ---   Valor:" .  $data->sheets[0]['cells'][$i][9] . " --- Mensagem: ".$msg. "<br>";
        //     } // if
        // } // for

        echo "Total de Lançamentos: " . $contadorGeral;
    }

//fim excelBoletosConvenio

/////////////////////////////////////////////////////
// Importa Produto Quantidade
         /*
         * formato da planilha
         * 1 - CNPJ FORNECEDOR
         * 2 - RAZÃO SOCIAL
         * 3 - CODIGO FARMACIA
         * 4 - CNPJ FARMACIA
         * 5 - VALOR COMPRADO
         * 6 - VALOR ASSOCIADO
         * 7 - GENERO
         * 8 - DATA COMPETENCIA
         * 9 - OBS
         */
/////////////////////////////////////////////////////
    function excelExtratoRepasseMkt() {

        // Set output Encoding.
        set_time_limit(500);
        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding('CP1251');
        $data->read($this->m_tmp);

        // cria class
        $classConta = new c_conta();
        $classExtrato = new c_extrato();
        $classGenero = new c_genero();
        $contadorGeral = 0;
        $quant = $data->sheets[0]['numRows'];
        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { // $data->sheets[0]['cells'][$i][1]
            if ($data->sheets[0]['cells'][$i][6] > '0'){

                $contadorGeral++;
                $msg = '';

                // consulta pessoa pelo cnpj
                $cnpj = str_pad($this->remove_cnpj($data->sheets[0]['cells'][$i][4]), 14, "0", STR_PAD_LEFT);
                $arrPessoa = $classConta->existeContaCnpj($cnpj, true);
                if (is_array($arrPessoa)){
                    $classExtrato->setPessoa($arrPessoa[0]['CLIENTE']);
                }else{
                    $msg = "Pessoa não localizada, CNPJ: ".$cnpj;
                }


                // consulta pessoa fornecedor pelo cnpj
                $cnpjFornecedor = str_pad($this->remove_cnpj($data->sheets[0]['cells'][$i][1]), 14, "0", STR_PAD_LEFT);
                $arrPessoaFornecedor = $classConta->existeContaCnpj($cnpjFornecedor, true);
                if (is_array($arrPessoaFornecedor)){
                    $classExtrato->setPessoaFornecedor($arrPessoaFornecedor[0]['CLIENTE']);
                }else{
                    $msg = "Pessoa Fornecedor não localizada, CNPJ: ".$cnpjFornecedor;
                }

                // consulta genero lancamento
                $classGenero->setGenero($data->sheets[0]['cells'][$i][7]);
                $arrGenero = $classGenero->select_genero();
                if (is_array($arrGenero)){
                    $classExtrato->setGenero($arrGenero[0]['GENERO']);
                    $classExtrato->setTipoLancamento($arrGenero[0]['TIPOLANCAMENTO']);
                }else{
                    $msg = "Genero não localizado: ".$data->sheets[0]['cells'][$i][7];
                }

                $classExtrato->setCentroCusto($this->m_empresacentrocusto);
                $classExtrato->setSituacaoLancamento('A');
                $classExtrato->setLancamento(date('d-m-Y'));
                $classExtrato->setCompetencia($data->sheets[0]['cells'][$i][8]);
                $classExtrato->setValor($data->sheets[0]['cells'][$i][6], true);
                if ($data->sheets[0]['cells'][$i][5] == 0){
                    $classExtrato->setObs($data->sheets[0]['cells'][$i][9]);}
                else {
                    $classExtrato->setObs($data->sheets[0]['cells'][$i][9].'  --  Valor total COMPRADO: '.$data->sheets[0]['cells'][$i][5]);}

                $classExtrato->incluiExtrato();


                echo "CODIGO:" . $data->sheets[0]['cells'][$i][3] . "  ---   Valor:" .  $classExtrato->getValor('F') . " --- Mensagem: ".$msg. "<br>";
            } // if
        } // for
        echo "Total de Lançamentos: " . $contadorGeral;
    }

//fim excelExtratoRepasseMkt

    // Importa Produto Quantidade
    function excelSaidaProdutosQuant() {

        set_time_limit(500);
        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// considera que produto, fornecedor e nf já estão cadastrados.
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
        $quant = $data->sheets[0]['numRows'];
        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { // $data->sheets[0]['cells'][$i][1]
            // testa se é nulo
            if ($data->sheets[0]['cells'][$i][1] != '') {
                $contadorGeral++;
                $classProduto = new c_produto();
                $classNF = new c_nota_fiscal();
                $classNFProduto = new c_nota_fiscal_produto();
                $classProdutoEst = new c_produto_estoque();
                $classNF->setId($data->sheets[0]['cells'][$i][3]);
                $existeNF = $classNF->select_nota_fiscal();
                if (is_array($existeNF)):
                    $classNFProduto->setIdNf($existeNF[0]['ID']);
                    //$classNFProduto->setIdNf($data->sheets[0]['cells'][$i][5]);

                    // produto
                    $classProduto->setId($data->sheets[0]['cells'][$i][1]);
                    $produto = $classProduto->select_produto();
                    $classProduto->setDesc($produto[0]['DESCRICAO']);
                    $classProduto->setUni($produto[0]['UNIDADE']);
                    $classProduto->setCustoCompra($produto[0]['CUSTOCOMPRA']);
                    $classNFProduto->setCodProduto($data->sheets[0]['cells'][$i][1]);

                    //EST_NOTA_FISCAL_PRODUTO
                    $qtde = (int) $data->sheets[0]['cells'][$i][2];
                    $unitario = $classProduto->getCustoCompra('');
                    $total = $qtde * $unitario;
                    $classNFProduto->setDescricao($classProduto->getDesc());
                    $classNFProduto->setUnidade($classProduto->getUni());
                    $classNFProduto->setQuant($qtde);
                    $classNFProduto->setUnitario($unitario);
                    $classNFProduto->setTotal($total);
                    $classNFProduto->setOrigem('0');
                    $classNFProduto->setCfop('5927');
                    $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));
                    $classNFProduto->incluiNotaFiscalProduto();

                    $result = $classProdutoEst->produtoBaixaPerda($data->sheets[0]['cells'][$i][8], $classNFProduto->getCodProduto(), $qtde, $classNFProduto->getIdNf());

                    echo "CODIGO:" . $data->sheets[0]['cells'][$i][1] . " - " .$classProduto->getDesc() . "   ---   Quantidade:" . $data->sheets[0]['cells'][$i][2] . "<br>";
    //                        "LINHA" . $result . "<br>";
                    
                endif;
            } // if
        } // for
        echo "Total de Produtos baixados: " . $contadorGeral . ". - Baixa efetuado com sucesso. ";
    }

//fim produtos

    function excelProdutosQuant() {

        set_time_limit(500);
        $f_name = $_FILES['file']['name'];
        $f_tmp = $_FILES['file']['tmp_name'];
        $f_type = $_FILES['file']['type'];

// considera que produto, fornecedor e nf já estão cadastrados.
// ExcelFile($filename, $encoding);
        $data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
        $data->setOutputEncoding('CP1252');
        $data->read($this->m_tmp);
//error_reporting(E_ALL ^ E_NOTICE);

         /*
         * formato da planilha
         * 1 - codigo
         * 2 - qtde da peça (novo ou usado)
         * 3 - num nf
         * 4 - localizacao
          *5 - fABRICACAO
         * 6 - Num Lote
         * 7 - Validade
          *8 - centro custo
         */
        $contadorGeral = 0;
        $classProdutoEst = new c_produto_estoque();
        $classProduto = new c_produto();
        $classNF = new c_nota_fiscal();
        $classNFProduto = new c_nota_fiscal_produto();

        $banco = new c_banco;
        for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { // $data->sheets[0]['cells'][$i][1]
            // testa se é nulo
            if ($data->sheets[0]['cells'][$i][1] != '') {
                // cadastro produtos nf..
                $qtde = 0;
                $classNF->setId($data->sheets[0]['cells'][$i][3]);
                $existeNF = $classNF->select_nota_fiscal();
                if (is_array($existeNF)):
                    $classNFProduto->setIdNf($existeNF[0]['ID']);
                    //$classNFProduto->setIdNf($data->sheets[0]['cells'][$i][5]);

                    // produto
                    $classProduto->setId($data->sheets[0]['cells'][$i][1]);
                    $produto = $classProduto->select_produto();
                    $classProduto->setDesc($produto[0]['DESCRICAO']);
                    $classProduto->setUni($produto[0]['UNIDADE']);
                    $classProduto->setCustoCompra($produto[0]['CUSTOCOMPRA']);
                    $classNFProduto->setCodProduto($data->sheets[0]['cells'][$i][1]);

                    //EST_NOTA_FISCAL_PRODUTO
                    $qtde = (int) $data->sheets[0]['cells'][$i][2];
                    $unitario = $classProduto->getCustoCompra('');
                    $total = $qtde * $unitario;
                    $classNFProduto->setDescricao($classProduto->getDesc());
                    $classNFProduto->setUnidade($classProduto->getUni());
                    $classNFProduto->setQuant($qtde);
                    $classNFProduto->setUnitario($unitario);
                    $classNFProduto->setTotal($total);
                    $classNFProduto->setOrigem('0');
                    $classNFProduto->setCfop('5927');
                    $classNFProduto->setDataConferencia(date('d-m-Y h:m:s'));
                    $classNFProduto->incluiNotaFiscalProduto();
                
                    // entrada produto estoque
                    $qtde = (int) $data->sheets[0]['cells'][$i][2];
                    for ($l = 0; $l < $qtde; $l++) {
                        //  echo "passou for nfprodutoos".$qtde;
                        $dataFab = $data->sheets[0]['cells'][$i][5];
                        $classProdutoEst->setIdNfEntrada($data->sheets[0]['cells'][$i][3]);
                        $classProdutoEst->setCodProduto($data->sheets[0]['cells'][$i][1]);
                        $classProdutoEst->setCentroCusto($data->sheets[0]['cells'][$i][8]);
                        $classProdutoEst->setUserProduto($this->m_userid);
                        $classProdutoEst->setLocalizacao('');
                        $classProdutoEst->setStatus('0');
                        $classProdutoEst->setAplicado('0');
                        $classProdutoEst->setFabLote($data->sheets[0]['cells'][$i][6]);
                        $classProdutoEst->setDataFabricacao($data->sheets[0]['cells'][$i][5]);
                        $classProdutoEst->setDataValidade($data->sheets[0]['cells'][$i][7]);
                        $sql = "INSERT INTO EST_PRODUTO_ESTOQUE (";
                        $sql .= "IDNFENTRADA, CODPRODUTO, CENTROCUSTO, STATUS, APLICADO, NS,  ";
                        $sql .= "FABLOTE, FABDATAVALIDADE, FABDATAFABRICACAO, LOCALIZACAO, OBS )";
                        $sql .= "values ( ";
                        $sql .= $classProdutoEst->getIdNfEntrada().", '".  $classProdutoEst->getCodProduto()."', ".  $classProdutoEst->getCentroCusto().", ";
                        $sql .= $classProdutoEst->getStatus().", '".  $classProdutoEst->getAplicado()."', '".  $classProdutoEst->getNsEntrada()."', '";
                        $sql .= $classProdutoEst->getFabLote()."', '".  $classProdutoEst->getDataValidade('B')."', '".  $classProdutoEst->getDataFabricacao('B')."', '";
                        $sql .= $classProdutoEst->getLocalizacao()."', '".  $classProdutoEst->getObs()."');";
                        // echo strtoupper($sql) . "<BR>";
                        $resProduto = $banco->exec_sql($sql);
                        $contadorGeral ++;
                    }
                endif;

                echo "CODIGO:" . $data->sheets[0]['cells'][$i][1] .  " - QTDE:" . $qtde . "LINHA" . $i . "<br>";
            } // if

        } // for
        $banco->close_connection();                
        echo "Total de Produtos importado: " . $contadorGeral . ". - Importa&ccedil;&atilde;o efetuado com sucesso. ";
    }

//fim produtos



//---------------------------------------------------------------
//---------------------------------------------------------------
//--------------------excelUpdateIBPT----------------------------
//---------------------------------------------------------------

    function excelUpdateIBPT() {
    //$filename = 'C:\Users\Robotics\Downloads\IBPT.xls';
    $data=new Spreadsheet_Excel_Reader();
    $data->setUTFEncoder('UTF-8');
    $data->setOutputEncoding('UTF-8');
    //$data->read($filename);
    $data->read($this->m_tmp);
   
    $erroGeral = 0;
   
    $table = '';
    for ($r=1; $r<=$data->sheets[0]['numRows']; $r++) {
        $linha = '';
        $erro = 0;
        for ($c=1; $c<=10; $c++) {
            if ($r == 1) {
                $linha .= "<td width= '10%'><b>".strtoupper(utf8_encode($data->sheets[0]['cells'][$r][$c]))."</b></td>";
            } else {
                $linha .= "<td width= '10%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
            }
        } 
    
        if ($erro == 1) {
            $erroGeral = 1;
            $table .= '<tr bgcolor="#FF0000">'.$linha.'</tr>';
        } else {
            $table .= '<tr>'.$linha.'</tr>';
        }
    }
    echo "<table>".$table."</table>";
    
    $classNCM = new c_ncm;
    $contadorGeral=0;
    echo "<br>";
    $banco = new c_banco;
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        if ($data->sheets[0]['cells'][$i][1] != '') {
            $contadorGeral++;

            //$banco = new c_banco;
            $sql = "SELECT * FROM EST_NCM WHERE ";
            $sql .= "NCM = '".$data->sheets[0]['cells'][$i][1]."';";
            $ncm = $banco->exec_sql($sql);
            //$banco->close_connection();  
          
           
            $classNCM->setNCM($data->sheets[0]['cells'][$i][1]);
            $classNCM->setDescricao($this->remove_acento(utf8_encode(substr($data->sheets[0]['cells'][$i][4], 0, 260))));
            $classNCM->setAliqTTNacFederal($data->sheets[0]['cells'][$i][5]);
            $classNCM->setAliqTTImpFederal($data->sheets[0]['cells'][$i][6]);
            $classNCM->setAliqTTEstadual($data->sheets[0]['cells'][$i][7]);
            $classNCM->setAliqTTMunicipal($data->sheets[0]['cells'][$i][8]);
            $classNCM->setVigenciaInicio($data->sheets[0]['cells'][$i][9]);
            $classNCM->setVigenciaFim($data->sheets[0]['cells'][$i][10]);

            if ($ncm > 0) {
                $id = $ncm[0]['ID'];
                $classNCM->setId($id);         
                $classNCM->setAliqIpi($ncm[0]['ALIQIPI']);
                $classNCM->setAliqPisMonofasica($ncm[0]['ALIQPISMONOFASICA']);
                $classNCM->setAliqCofinsMonofasica($ncm[0]['ALIQCOFINSMONOFASICA']);
            } else {
                $classNCM->setAliqIpi(0);
                $classNCM->setAliqPisMonofasica(0);
                $classNCM->setAliqCofinsMonofasica(0);
            }

            try {
                
                if ($ncm > 0) {
                    $classNCM->alteraNCM();
                }else{
                    $classNCM->incluiNCM();   
                }
                
//                echo "Cadastro OK ==> " . $classNCM->getNCM() . " - Descricao: " . $classNCM->getDescricao() . " - Linha: " . $contadorGeral . " - ok" . "<br>";
            } catch (Exception $e) {
                echo "ERRO ==> " . $classNCM->getNCM() . " - Descricao: " . $classNCM->getDescricao() . " - Linha: " . $contadorGeral . " - ok" . "<br>";
            }
        }
    } // for
    $banco->close_connection();       

    }

//---------------------------------------------------------------
//---------------------------------------------------------------
    function mostraImporta($mensagem) {


        $this->smarty->assign('pathImagem', $this->img);
        $this->smarty->assign('mensagem', $mensagem);
        $this->smarty->assign('letra', $this->m_letra);
        $this->smarty->assign('subMenu', $this->m_submenu);


        // arquivos importar
        $consulta = new c_banco();
        // $arqImporta_ids[0] = "pessoasmaxi";
        // $arqImporta_names[0] = "Cadastro Pessoas";
        // $arqImporta_ids[1] = "Cadastro Produtos";
        // $arqImporta_names[1] = "Cadastro Produtos";
       // $arqImporta_ids[0] = "produtosquant";
        // $arqImporta_names[0] = "Entrada Produtos Estoque";
        // $arqImporta_ids[1] = "saidaprodutosquant";
        // $arqImporta_names[1] = "Saída Produtos Estoque";
        // $arqImporta_ids[2] = "extratorepassemkt";
        // $arqImporta_names[2] = "Repasse MKT";
        $arqImporta_ids[3] = "pessoa";
        $arqImporta_names[3] = "Importa Pessoa";
        //$arqImporta_ids[4] = "financeiro";
        //$arqImporta_names[4] = "Importa Boleto BIG";
        //$arqImporta_ids[5] = "ibpt";
        //$arqImporta_names[5] = "Atualiza IBPT";
        //$arqImporta_ids[6] = "boletoFinanceiro";
        //$arqImporta_names[6] = "Importar Boleto";
        
        $this->smarty->assign('arqImporta_ids', $arqImporta_ids);
        $this->smarty->assign('arqImporta_names', $arqImporta_names);

        $this->smarty->assign('arq_id', 0);

        $this->smarty->display('importa_mostra.tpl');
    }

//fim mostraimportas
//-------------------------------------------------------------
}

//	END OF THE CLASS
// Rotina principal - cria classe
$importa = new p_importa();

        if (isset($_FILES['arq'])):
            $importa->m_name = $_FILES['arq']['name'];
        else:
            $importa->m_name = '';
        endif;
        if (isset($_FILES['arq'])):
            $importa->m_tmp = $_FILES['arq']['tmp_name'];
        else:
            $importa->m_tmp = '';
        endif;

        if (isset($_FILES['arq'])):
            $importa->m_type = $_FILES['arq']['type'];
        else:
            $importa->m_type = '';
        endif;
        if (isset($_FILES['arq'])):
            $importa->m_size = $_FILES['arq']['size'];
        else:
            $importa->m_size = '';
        endif;
        

$importa->controle();
?>
