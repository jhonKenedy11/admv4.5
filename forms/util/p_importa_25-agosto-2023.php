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
formato da planilha
1 - CODIGO ANTERIOR
2 - RAZÃO SOCIAL
3 - CNPJ 
4 - IE 
5 - ENDEREÇO
6 - NUMERO
7 - BAIRRO
8 - CIDADE
9 - UF
10 - CEP
11 - DATACADASTRO
---------------------------------------------------------------*/
public function excelImportaPessoa() {
 //$filename = 'C:\Users\Robotics\Downloads\Empresas_Convenio_1.xls';
$data=new Spreadsheet_Excel_Reader();
$data->setUTFEncoder('UTF-8');
$data->setOutputEncoding('UTF-8');
//$data->read($filename);
$data->read($this->m_tmp);

$erroGeral = 0;

$table = '';
for($r=2; $r<=$data->sheets[0]['numRows']; $r++)
{  
   for($c=1; $c<=$data->sheets[0]['numCols']; $c++)
   {            

      //if (isset($data->sheets[0]['cells'][$r][$c])) 
      {
        if ( $c == 1 ) {
            $linha .= "<td width= '4%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
        } else if ( $c == 2) {
            $nomeCliente = $data->sheets[0]['cells'][$r][$c];
            $linha .= "<td width= '18%'>".utf8_encode(substr($nomeCliente, 0, 30))."</td>";
        } else if ( $c == 3) {
            $linha .= "<td width= '10%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
        } else if ( $c == 4) {
            $linha .= "<td width= '7%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
        } else if ( $c == 5) {
            $endereco = $data->sheets[0]['cells'][$r][$c];
            if ($endereco == '') {
                $erro = 1;
            }
            $linha .= "<td width= '15%'>".utf8_encode(substr($endereco, 0, 20))."</td>";
        } else if ( $c == 6) {
            $linha .= "<td width= '3%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
        } else if ( $c == 7) {
            $linha .= "<td width= '8%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
        } else if ( $c == 8) {
            $linha .= "<td width= '8%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
        } else if ( $c == 9) {
            $linha .= "<td width= '2%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
        } else if ( $c == 10) {
            $linha .= "<td width= '8%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";
        } else if ( $c == 11) {
            $linha .= "<td width= '8%'>".utf8_encode($data->sheets[0]['cells'][$r][$c])."</td>";            
        } 
      }
   }
   if ($erro == 1) {
    $erroGeral = 1;   
    $table .= '<tr bgcolor="#FF0000">'.$linha.'</tr>';
   } else {
    $table .= '<tr>'.$linha.'</tr>';
   }
   $linha = ''; 
   $erro = 0;
}


if ($erroGeral == 1) {

  echo "</BR></BR><h1>ERROR: VERIFIQUE ARQUIVO (LINHAS em VERMELHO)</h1>";
  echo "<table>".$table."</table>";
} else {

echo "<table>".$table."</table>";

$classPessoa = new c_conta;
$contadorGeral=0;
echo "<br>";
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
    if ($data->sheets[0]['cells'][$i][1] != '') {
        $contadorGeral++;

        $classPessoa->setNome($this->remove_acento(utf8_encode(substr($data->sheets[0]['cells'][$i][2], 0, 50))));
        $classPessoa->setNomeReduzido($this->remove_acento(utf8_encode(substr($data->sheets[0]['cells'][$i][2], 0, 20))));
        $classPessoa->setCnpjCpf(str_pad($this->remove_cnpj($data->sheets[0]['cells'][$i][3]), 14, "0", STR_PAD_LEFT));
        $classPessoa->setIeRg($this->remove_cnpj($data->sheets[0]['cells'][$i][4]));
        $classPessoa->setEndereco($this->remove_acento(utf8_encode(substr($data->sheets[0]['cells'][$i][5], 0, 60))));
        $classPessoa->setNumero($data->sheets[0]['cells'][$i][6]);
        $classPessoa->setBairro($this->remove_acento(utf8_encode(substr($data->sheets[0]['cells'][$i][7],0,20))));
        $classPessoa->setCidade($this->remove_acento(utf8_encode(substr($data->sheets[0]['cells'][$i][8],0,40))));
        $classPessoa->setEstado($data->sheets[0]['cells'][$i][9]);
        $classPessoa->setCep($this->remove_cnpj($data->sheets[0]['cells'][$i][10]));
        $classPessoa->setPessoa('J');
        $classPessoa->setClasse('01');
        $classPessoa->setObs('Cliente cadastrado atraves de importacao. Data Cadastro:'.$data->sheets[0]['cells'][$i][9]);
        $classPessoa->setCentroCusto($this->m_empresacentrocusto);
        $classPessoa->setRepresentante($this->m_userid);
        $classPessoa->setRegimeEspecialST('N');
        $classPessoa->setRegimeEspecialSTMsg('  ');
        $classPessoa->setRegimeEspecialSTMT('N');
        $classPessoa->setContribuinteICMS('N');
        $classPessoa->setConsumidorFinal('N');
        $classPessoa->setRegimeEspecialSTMTAliq('0');
        $classPessoa->setRegimeEspecialSTAliq('0');
        try {
            $classPessoa->incluiConta();
            echo "Cadastro OK ==> " . $classPessoa->getNome() . " - Cidade: " . $classPessoa->getCidade() . " - Linha: " . $contadorGeral . " - ok" . "<br>";
        } catch (Exception $e) {
            echo "ERRO ==> " . $classPessoa->getNome() . " - Cidade: " . $classPessoa->getCidade() . " - Linha: " . $contadorGeral . " - ok" . "<br>";          
        }    
    }    
} // for

}


/*
// CADASTRO DE CLIENTE
// ExcelFile($filename, $encoding);
    // Set output Encoding.
    set_time_limit(500);
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    //$data->read($this->m_tmp);

    $ponteiro = fopen($this->m_tmp, 'r');
    while (!feof ($ponteiro)) {
        $linha = fgets($ponteiro);
        echo $linha."<br>";
    }//FECHA WHILE

    fclose($ponteiro);


    $classPessoa = new c_conta;
    $contadorGeral=0;
    echo "<br>";
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        if ($data->sheets[0]['cells'][$i][1] != '') {
            $contadorGeral++;

            $classPessoa->setNome($this->remove_acento(substr($data->sheets[0]['cells'][$i][2], 0, 50)));
            $classPessoa->setNomeReduzido($this->remove_acento(substr($data->sheets[0]['cells'][$i][2], 0, 20)));
            $classPessoa->setCnpjCpf(str_pad($this->remove_cnpj($data->sheets[0]['cells'][$i][3]), 14, "0", STR_PAD_LEFT));
            $classPessoa->setIeRg($this->remove_cnpj($data->sheets[0]['cells'][$i][4]));
            $classPessoa->setEndereco($this->remove_acento($data->sheets[0]['cells'][$i][5]));
            $classPessoa->setNumero($data->sheets[0]['cells'][$i][6]);
            $classPessoa->setBairro($this->remove_acento($data->sheets[0]['cells'][$i][7]));
            $classPessoa->setCidade($this->remove_acento($data->sheets[0]['cells'][$i][8]));
            $classPessoa->setEstado($data->sheets[0]['cells'][$i][9]);
            $classPessoa->setCep($this->remove_cnpj($data->sheets[0]['cells'][$i][10]));
            $classPessoa->setPessoa('J');
            $classPessoa->setClasse('01');
            $classPessoa->setObs('Cliente cadastrado atraves de importacao. Data Cadastro:'.$data->sheets[0]['cells'][$i][9]);
            $classPessoa->setCentroCusto($this->m_empresacentrocusto);
            $classPessoa->setRepresentante($this->m_userid);
            $classPessoa->incluiConta();

            echo "Cadastro OK ==> " . $classPessoa->getNome() . " - Cidade: " . $classPessoa->getCidade() . " - Linha: " . $contadorGeral . " - ok" . "<br>";
        }    
    } // for
    */
}


/////////////////////////////////////////////////////
// Importa Produto Quantidade
         /*
         * formato da planilha
         * 0 - NUM DOC
         * 1 - SERIE
         * 2 - ORIGEM
         * 3 - DATA DOC
         * 4 - DATA VENCIMENTO
         * 5 - CNPJ SACADO
         * 6 - VALOR
         * 7 - GENERO
         * 8 - CENTRO CUSTO
         * 9 - CONTA
         */
/////////////////////////////////////////////////////
    public function excelBoletosFinanceiro() {
    
    $data=new Spreadsheet_Excel_Reader();
    $data->setUTFEncoder('UTF-8');
    $data->setOutputEncoding('UTF-8');
    //$data->read($filename);
    $data->read($this->m_tmp);
   
    $erroGeral = 0;
   
    $table = '';
    for ($r=1; $r<=$data->sheets[0]['numRows']; $r++) {
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
        $linha = '';
        $erro = 0;
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
            $cnpj = str_pad($this->remove_cnpj($data->sheets[0]['cells'][$i][6]), 14, "0", STR_PAD_LEFT);
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

//fim excelBoletosFinanceiro

/////////////////////////////////////////////////////
// Importa Produto Quantidade
         /*
         * formato da planilha
         * 0 - NUMERO DOC
         * 1 - DATA DOC
         * 2 - AGENCIA
         * 3 - COD. CEDENTE
         * 4 - CONTA CORRENTE
         * 5 - CNPJ CEDENTE
         * 6 - NOSSO NUMERO
         * 7 - DATA PROCESSAMENTO
         * 8 - CNPJ SACADO
         * 9 - VALOR
         * 10 - $ JUROS
         * 11 - MULTA
         * 12 - % DESCONTO
         * 13 - VALOR DESCONTO
         * 14 - DATA VENCIMENTO
         * 15 - LINHA DIGITAVEL
         * 16 - GENERO
         * 17 - CENTRO CUSTO
         * 18 - CONTA
         */
/////////////////////////////////////////////////////
    public function excelBoletosConvenio() {

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

        echo "Total de Labçamentos: " . $contadorGeral;
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
    public function excelExtratoRepasseMkt() {

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
        echo "Total de Labçamentos: " . $contadorGeral;
    }

//fim excelExtratoRepasseMkt

    // Importa Produto Quantidade
    public function excelSaidaProdutosQuant() {

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

    public function excelProdutosQuant() {

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

public function excelUpdateIBPT() {
    //$filename = 'C:\Users\Robotics\Downloads\IBPT.xls';
    $data=new Spreadsheet_Excel_Reader();
    $data->setUTFEncoder('UTF-8');
    $data->setOutputEncoding('UTF-8');
    //$data->read($filename);
    $data->read($this->m_tmp);
   
    $erroGeral = 0;
   
    $table = '';
    for ($r=1; $r<=$data->sheets[0]['numRows']; $r++) {
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
        $linha = '';
        $erro = 0;
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
        $arqImporta_ids[0] = "produtosquant";
        $arqImporta_names[0] = "Entrada Produtos Estoque";
        $arqImporta_ids[1] = "saidaprodutosquant";
        $arqImporta_names[1] = "Saída Produtos Estoque";
        $arqImporta_ids[2] = "extratorepassemkt";
        $arqImporta_names[2] = "Repasse MKT";
        $arqImporta_ids[3] = "pessoa";
        $arqImporta_names[3] = "Importa Pessoa";
        $arqImporta_ids[4] = "financeiro";
        $arqImporta_names[4] = "Importa Boleto BIG";
        $arqImporta_ids[5] = "ibpt";
        $arqImporta_names[5] = "Atualiza IBPT";
        $arqImporta_ids[6] = "boletoFinanceiro";
        $arqImporta_names[6] = "Importar Boleto";
        
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
