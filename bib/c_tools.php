<?php

/**
 * @package   astec
 * @name      c_tools
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
//include "c_database.php";
Class c_tools {


    public function makeTimeStamp($year = "", $month = "", $day = "") {
        if (empty($year))
            $year = strftime("%Y");
        if (empty($month))
            $month = strftime("%m");
        if (empty($day))
            $day = strftime("%d");

        return mktime(0, 0, 0, $month, $day, $year);
    }

    public function comboArray($resulta, $primeiraOpcao, $id) {
        $result = $resulta;
        $teste_result = is_array($result);
        if ($teste_result) {
            if (isset($primeiraOpcao)) {
                echo "<option value=''>" . $primeiraOpcao . " </option>";
            }
            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]['ID'] == $id) {
                    echo "<option SELECTED value=" . $result[$i]['ID'] . ">" . $result[$i]['DESCRICAO'] . " </option>";
                } else {
                    echo "<option value=" . $result[$i]['ID'] . ">" . $result[$i]['DESCRICAO'] . " </option>";
                }
            } // for
        } // if
    }

// comboArray

    public function combo($sql, $primeiraOpcao, $id) {

// busca dados para mostrar na consulta
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
//  echo ("String exec_sql: ".$sql."<br>");
        $consulta->close_connection();
        $result = $consulta->resultado;

// monta select
        $teste_result = is_array($result);
        if ($teste_result) {
            if (isset($primeiraOpcao)) {
                echo "<option value=''>" . $primeiraOpcao . " </option>";
            }
            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]['ID'] == $id) {
                    echo "<option SELECTED value=" . $result[$i]['ID'] . ">" . $result[$i]['DESCRICAO'] . " </option>";
                } else {
                    echo "<option value=" . $result[$i]['ID'] . ">" . $result[$i]['DESCRICAO'] . " </option>";
                }
            } // for
        } // if
    }

// combo

    public function comboSelectMulti($sql, $opcao, $iniArray) {

// multi op��es
//$par = explode("|", $opcao);
        $output = array_slice($opcao, $iniArray); //Extrai uma parcela de um array
        // busca dados para motrar na consulta
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
//  echo ("String exec_sql: ".$sql."<br>");
        $consulta->close_connection();
        $result = $consulta->resultado;

// monta select
        $teste_result = is_array($result);
        if ($teste_result) {
//    echo "<option value=''>".print_r($this->m_letra)." </option>";
            for ($i = 0; $i < count($result); $i++) {
                $p = array_search($result[$i]['ID'], $output);
                if ($p !== false) {
                    echo "<option SELECTED value=" . $result[$i]['ID'] . ">" . $result[$i]['DESCRICAO'] . " </option>";
                } else {
                    echo "<option value=" . $result[$i]['ID'] . ">" . $result[$i]['DESCRICAO'] . " </option>";
                }
            } // for
        } // if
    }

// comboSelectOpcao

    public function comboTabela($sql, $primeiraOpcao, $id) {

// busca dados para motrar na consulta
        echo ("String exec_sql: " . $sql . "<br>");
        $consulta = new c_banco();
        $consulta->exec_sql($sql);
        echo ("<br>" . "String ID: " . $id . "<br>");
        $consulta->close_connection();
        $result = $consulta->resultado;

// monta select
        $teste_result = is_array($result);
        if ($teste_result) {
            if (isset($primeiraOpcao)) {
                echo "<option value=''>" . $primeiraOpcao . " </option>";
            }
            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]['ID'] == $id) {
                    echo "<option SELECTED value=" . $result[$i]['ID'] . ">" . $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'] . " </option>";
                } else {
                    echo "<option value=" . $result[$i]['ID'] . ">" . $result[$i]['ID'] . " - " . $result[$i]['DESCRICAO'] . " </option>";
                }
            } // for
        } // if
    }

// combo

    public function setConsulta($consulta) {

// busca dados para motrar na consulta
        $array = $_SESSION['user_array'];
        $array[9] = $consulta; // nome do formul�rio para consulta
        $_SESSION['user_array'] = $array;
    }

// setConsulta
//----------------------------------------------------------------------------------------------------------------
// formata numero para gravar no bando.
//----------------------------------------------------------------------------------------------------------------
    static function moedaBd($get_valor) {
        if ($get_valor != null) {
            $replace = str_replace('.', '', $get_valor);
            $replace = str_replace(',', '.', $replace);
            return $replace;
        } else {
            return 0;
        }
    }
    
    /**
     * Funcao para retirar todos os espaços da string e aspas
     * @param String $valor
     * @return String valor formatado
     */
    public static function LimpaCamposGeral($valor) {
        $valor = addslashes($valor);
        $valor = trim($valor);
        //$valor = str_replace(' ', '', $valor);
        return $valor;
        
    }


    /**
     * Retorna o(s) numDig Dígitos de Controle Módulo 11 do
     * dado, limitando o Valor de Multiplicação em limMult,
     * multiplicando a soma por 10, se indicado:
     *
     *    Números Comuns:   numDig:   limMult:   x10:
     *      CPF                2         12      true
     *      CNPJ               2          9      true
     *      PIS,C/C,Age        1          9      true
     *      RG SSP-SP          1          9      false
     *
     * @version                 V5.0 - Mai/2001~Out/2015
     * @author                  CJDinfo
     * @param  string  $dado    String dado contendo o número (sem o DV)
     * @param  int     $numDig  Número de dígitos a calcular
     * @param  int     $limMult Limite de multiplicação 
     * @param  boolean $x10     Se true multiplica soma por 10
     * @return string           Dígitos calculados
     */
    public function calculaDigitoMod11($numDado, $numDig, $limMult, $x10){

      if(!$x10) $numDig = 1;
      $dado = $numDado;
      for($n=1; $n<=$numDig; $n++){
        $soma = 0;
        $mult = 2;
        for($i=strlen($dado) - 1; $i>=0; $i--){
          $sub = $mult * intval(substr($dado, $i ,1));
          $soma += $sub;
          if(++$mult > $limMult):
              $mult = 2;
          endif;
        }
        if($x10){
          $dig = fmod(fmod(($soma * 10), 11), 10);
        } else {
          $resto = fmod($soma, 11);
          if(($resto == 0) or ($resto == 1)):
              $dig = "1";
          else:
              $dig = 11-$resto;
          endif;
          //if($dig == 10) $dig = "X";
        }
        $dado .= strval($dig);
      }
      return substr($dado, strlen($dado)-$numDig);
    }

    public static function buscaConfig($empresaid){

        // configura JSON com dados acesso - CONFIG NF-e 
        if ($empresaid == 1) {
            $confPar = explode("|", ADMnfeConfig01);
        } else
        if ($empresaid == 2) {
            $confPar = explode("|", ADMnfeConfig02);
        } else
        if ($empresaid == 3) {
            $confPar = explode("|", ADMnfeConfig03);
        }
        if ($empresaid == 4) {
            $confPar = explode("|", ADMnfeConfig04);
        } 
        if ($empresaid == 5) {
            $confPar = explode("|", ADMnfeConfig05);
        }

        // $config = [
        //   "atualizacao" => date('Y-m-d H:i:s'),
        //   "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
        //   "razaosocial" => $confPar[2],
        //   "siglaUF" => $confPar[3],
        //   "cnpj" => $confPar[4],
        //   "ie" => '',
        //   //"schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
        //   "versao" => '3.00',
        //   //"tokenIBPT" => $confPar[7]
        // ];
        
        $config = [
           "atualizacao" => date('Y-m-d H:i:s'),
           "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
           "razaosocial" => $confPar[2],
           "siglaUF" => $confPar[3],
           "cnpj" => $confPar[4],
           "ie" => '',
           "schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
           "versao" => $confPar[6],
           //"tokenIBPT" => $confPar[7]
        ];

        $configJson = json_encode($config);
        return $configJson;

    }

    public function buscaConfigMdfe($empresaid){

        // configura JSON com dados acesso - CONFIG NF-e 
        if ($empresaid == 1) {
            $confPar = explode("|", ADMnfeConfig01);
        } else
        if ($empresaid == 2) {
            $confPar = explode("|", ADMnfeConfig02);
        } else
        if ($empresaid == 3) {
            $confPar = explode("|", ADMnfeConfig03);
        }
        if ($empresaid == 4) {
            $confPar = explode("|", ADMnfeConfig04);
        } 
        if ($empresaid == 5) {
            $confPar = explode("|", ADMnfeConfig05);
        }

        $config = [
          "atualizacao" => date('Y-m-d H:i:s'),
          "tpAmb" => intval($confPar[1]), // Se deixar o tpAmb como 2 você emitirá a nota em ambiente de homologação(teste) e as notas fiscais aqui não tem valor fiscal
          "razaosocial" => $confPar[2],
          "siglaUF" => $confPar[3],
          "cnpj" => $confPar[4],
          "ie" => '',
          //"schemes" => $confPar[5], //PL_009_V4 - 4.0,PL_008i2 - 3.10
          "versao" => '3.00',
          //"tokenIBPT" => $confPar[7]
        ];
        
        $configJson = json_encode($config);
        return $configJson;

    }

        /**
     * Busca certificado da empresa
     * @author Jhon Kenedy <jhon.kened11@gmail.com>
     * @access public
     * @param string/int id centro de custo
     * @return string  file in string
     */

    public static function buscaCertificado($empresaid){

        // leitura do certirficado digital
        if ($empresaid == 1) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT.ADMnfeCert01);
        } else
        if ($empresaid == 2) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT.ADMnfeCert02);
        } else
        if ($empresaid == 3) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT.ADMnfeCert03);
        }        
        if ($empresaid == 4) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT.ADMnfeCert04);
        }
        if ($empresaid == 5) {
            $certificadoDigital = file_get_contents(BASE_DIR_CERT.ADMnfeCert05);
        }

        return $certificadoDigital;

    }

            /**
     * Busca senha do certificado da empresa
     * @author Jhon Kenedy <jhon.kened11@gmail.com>
     * @access public
     * @param string/int id centro de custo
     * @return string string
     */

    public static function buscaCertificadoSenha($empresaid){
        if ($empresaid == 1) {
            return ADMnfeSenha01;
        } else
        if ($empresaid == 2) {
            return ADMnfeSenha02;
        } else
        if ($empresaid == 3) {
            return ADMnfeSenha03;
        }        
        if ($empresaid == 4) {
            return ADMnfeSenha04;
        }
    }

    function limparCaracteresEspeciais($string) {
        // Remover caracteres especiais e espaços
        $string = preg_replace('/[^a-zA-Z0-9]/', '', $string);
        return $string;
    }

    static public function stringToDouble($value)
    {
        // Remove os pontos usados como separadores de milhar
        $value = str_replace('.', '', $value);
        // Substitui a vírgula decimal por um ponto
        $value = str_replace(',', '.', $value);
        // Converte para tipo float (double)
        return (float) $value;
    }
} // c_tools


// ################## Classe responsavel por validar cpf ou cnpj ##################
// ################################################################################


class ValidaCPFCNPJ {

    /**
     * Configura o valor (Construtor)
     * 
     * Remove caracteres inválidos do CPF ou CNPJ
     * 
     * @param string $valor - O CPF ou CNPJ
     */
    function __construct($valor = null) {
        // Deixa apenas números no valor
        $this->valor = preg_replace('/[^0-9]/', '', $valor);

        // Garante que o valor é uma string
        $this->valor = (string) $this->valor;
    }

    /**
     * Verifica se é CPF ou CNPJ
     * 
     * Se for CPF tem 11 caracteres, CNPJ tem 14
     * 
     * @access protected
     * @return string CPF, CNPJ ou false
     */
    protected function verifica_cpf_cnpj() {
        // Verifica CPF
        if (strlen($this->valor) === 11) {
            return 'CPF';
        }
        // Verifica CNPJ
        elseif (strlen($this->valor) === 14) {
            return 'CNPJ';
        }
        // Não retorna nada
        else {
            return false;
        }
    }

    /**
     * Verifica se todos os números são iguais
     * 	 * 
     * @access protected
     * @return bool true para todos iguais, false para números que podem ser válidos
     */
    protected function verifica_igualdade() {
        // Todos os caracteres em um array
        $caracteres = str_split($this->valor);

        // Considera que todos os números são iguais
        $todos_iguais = true;

        // Primeiro caractere
        $last_val = $caracteres[0];

        // Verifica todos os caracteres para detectar diferença
        foreach ($caracteres as $val) {

            // Se o último valor for diferente do anterior, já temos
            // um número diferente no CPF ou CNPJ
            if ($last_val != $val) {
                $todos_iguais = false;
            }

            // Grava o último número checado
            $last_val = $val;
        }

        // Retorna true para todos os números iguais
        // ou falso para todos os números diferentes
        return $todos_iguais;
    }

    /**
     * Multiplica dígitos vezes posições
     *
     * @access protected
     * @param  string    $digitos      Os digitos desejados
     * @param  int       $posicoes     A posição que vai iniciar a regressão
     * @param  int       $soma_digitos A soma das multiplicações entre posições e dígitos
     * @return int                     Os dígitos enviados concatenados com o último dígito
     */
    protected function calc_digitos_posicoes($digitos, $posicoes = 10, $soma_digitos = 0) {
        // Faz a soma dos dígitos com a posição
        // Ex. para 10 posições:
        //   0    2    5    4    6    2    8    8   4
        // x10   x9   x8   x7   x6   x5   x4   x3  x2
        //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
        for ($i = 0; $i < strlen($digitos); $i++) {
            // Preenche a soma com o dígito vezes a posição
            $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );

            // Subtrai 1 da posição
            $posicoes--;

            // Parte específica para CNPJ
            // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
            if ($posicoes < 2) {
                // Retorno a posição para 9
                $posicoes = 9;
            }
        }

        // Captura o resto da divisão entre $soma_digitos dividido por 11
        // Ex.: 196 % 11 = 9
        $soma_digitos = $soma_digitos % 11;

        // Verifica se $soma_digitos é menor que 2
        if ($soma_digitos < 2) {
            // $soma_digitos agora será zero
            $soma_digitos = 0;
        } else {
            // Se for maior que 2, o resultado é 11 menos $soma_digitos
            // Ex.: 11 - 9 = 2
            // Nosso dígito procurado é 2
            $soma_digitos = 11 - $soma_digitos;
        }

        // Concatena mais um dígito aos primeiro nove dígitos
        // Ex.: 025462884 + 2 = 0254628842
        $cpf = $digitos . $soma_digitos;

        // Retorna
        return $cpf;
    }

    /**
     * Valida CPF
     *
     * @author                Luiz Otávio Miranda <contato@todoespacoonline.com/w>
     * @access protected
     * @param  string    $cpf O CPF com ou sem pontos e traço
     * @return bool           True para CPF correto - False para CPF incorreto
     */
    protected function valida_cpf() {
        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($this->valor, 0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = $this->calc_digitos_posicoes($digitos);

        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        $novo_cpf = $this->calc_digitos_posicoes($novo_cpf, 11);

        // Verifica se todos os números são iguais
        if ($this->verifica_igualdade()) {
            return false;
        }

        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        if ($novo_cpf === $this->valor) {
            // CPF válido
            return true;
        } else {
            // CPF inválido
            return false;
        }
    }

    /**
     * Valida CNPJ
     *
     * @author                  Luiz Otávio Miranda <contato@todoespacoonline.com/w>
     * @access protected
     * @param  string     $cnpj
     * @return bool             true para CNPJ correto
     */
    protected function valida_cnpj() {
        // O valor original
        $cnpj_original = $this->valor;

        // Captura os primeiros 12 números do CNPJ
        $primeiros_numeros_cnpj = substr($this->valor, 0, 12);

        // Faz o primeiro cálculo
        $primeiro_calculo = $this->calc_digitos_posicoes($primeiros_numeros_cnpj, 5);

        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $segundo_calculo = $this->calc_digitos_posicoes($primeiro_calculo, 6);

        // Concatena o segundo dígito ao CNPJ
        $cnpj = $segundo_calculo;

        // Verifica se todos os números são iguais
        if ($this->verifica_igualdade()) {
            return false;
        }

        // Verifica se o CNPJ gerado é idêntico ao enviado
        if ($cnpj === $cnpj_original) {
            return true;
        }
    }

    /**
     * Valida
     * 
     * Valida o CPF ou CNPJ
     * 
     * @access public
     * @return bool      True para válido, false para inválido
     */
    public function valida() {
        // Valida CPF
        if ($this->verifica_cpf_cnpj() === 'CPF') {
            // Retorna true para cpf válido
            return $this->valida_cpf();
        }
        // Valida CNPJ
        elseif ($this->verifica_cpf_cnpj() === 'CNPJ') {
            // Retorna true para CNPJ válido
            return $this->valida_cnpj();
        }
        // Não retorna nada
        else {
            return false;
        }
    }

    /**
     * Formata CPF ou CNPJ
     *
     * @access public
     * @return string  CPF ou CNPJ formatado
     */
    public function formata() {
        // O valor formatado
        $formatado = false;

        // Valida CPF
        if ($this->verifica_cpf_cnpj() === 'CPF') {
            // Verifica se o CPF é válido
            if ($this->valida_cpf()) {
                // Formata o CPF ###.###.###-##
                $formatado = substr($this->valor, 0, 3) . '.';
                $formatado .= substr($this->valor, 3, 3) . '.';
                $formatado .= substr($this->valor, 6, 3) . '-';
                $formatado .= substr($this->valor, 9, 2) . '';
            }
        }
        // Valida CNPJ
        elseif ($this->verifica_cpf_cnpj() === 'CNPJ') {
            // Verifica se o CPF é válido
            if ($this->valida_cnpj()) {
                // Formata o CNPJ ##.###.###/####-##
                $formatado = substr($this->valor, 0, 2) . '.';
                $formatado .= substr($this->valor, 2, 3) . '.';
                $formatado .= substr($this->valor, 5, 3) . '/';
                $formatado .= substr($this->valor, 8, 4) . '-';
                $formatado .= substr($this->valor, 12, 14) . '';
            }
        }

        // Retorna o valor 
        return $formatado;
    }

}

?>
