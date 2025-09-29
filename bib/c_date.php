<?php
 /*
 * Este arquivo � parte do projeto admTec - Sistema de Administra��o de Servicos.
 * 
 *	Classe destinada ao tratamento de Datas no Sistema
 * 
 * @package   admTec
 * @name      c_date
 * @version   1.0.00
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2013-2016 &copy; 
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva <marcio.sergio@admservice.com.br>
 */

/**
 * Classe principal "CORE class"
 */
Class c_date {

     /**
     * data
     * Data temporaria utiliza na convers�o
     * @var date
     */
    private $data;
    /**
     * hora
     * Hora temporaria utiliza na convers�o
     * @var time
     */
    private $hora;

    /**
     * __construct
     * M�todo construtor da classe
     * 
     * @param  array 
     * @return boolean true sucesso false Erro
     */
function __construct (){
	
} //fim __construct


    /**
     * Converte data do formato Americano para o formato do banco Atual e vise versa,
     * utilizado para formatar par de letra
     * @name convertDateBd
     * @param    date    $postdate  Data no formato dd/mm/YYYY a ser convertida
     * @return   date    retorna a data no formato do banco atual['dd.mm.YYYY','YYYY-mm-dd']
     */
static function convertDateTxt($postdate)
{
if ( ! strstr( $postdate, '/' ) )
        {
                // $data est� no formato ISO (yyyy-mm-dd) e deve ser convertida
                // para dd/mm/yyyy
                sscanf( $postdate, '%d-%d-%d', $y, $m, $d );
                return sprintf( '%d/%d/%d', $d, $m, $y );
        }
        else
        {
                // $data est� no formato brasileiro e deve ser convertida para ISO
                sscanf( $postdate, '%d/%d/%d', $d, $m, $y );
                return sprintf( '%d-%d-%d', $y, $m, $d );
        }
 
        return false;        
   }

        
    /**
     * Converte data do formato Americano para o formato do banco Atual
     *
     * @name convertDateBd
     * @param    date    $postdate  Data no formato dd/mm/YYYY a ser convertida
     * @param    string  $banco banco de dados utilizado pelo sistema
     * @return   date    retorna a data no formato do banco atual['dd.mm.YYYY','YYYY-mm-dd']
     */
static function convertDateBd($postdate, $banco=null)
{
   
    if ($banco == 'interbase'){
        // firebird
	return date("d.m.Y", strtotime($postdate));
    }
    else{
        // Postgresql / Mysql
    	$data = str_replace("/", "-",$postdate); 
	return date("Y-m-d H:i:s", strtotime($data));
    }
}

// converte data para bando de dados sem horario
static function convertDateBdSh($postdate, $banco=null)
{
 if ($banco == 'interbase'){
        // firebird
    return date("d.m.Y", strtotime($postdate));
    }
    else{
        if ($postdate == ''){
            return $postdate;
        }else{
           
            $postdate = str_replace('/', '-',$postdate);
            $data = date('Y-m-d', strtotime($postdate));
         
           
            return $data;
        }
       
    }

}

// m � para m�s, d para dia, h para hora, n para minuto e qualquer outro valor para segundo. 

function DataDif($Data1, $Data2, $Intervalo){
$Q = 1;
switch($Intervalo){
case 'm': $Q *= 30;
case 'd': $Q *= 24;
case 'h': $Q *= 60;
case 'n': $Q *= 60;
}
return intval((strtotime($Data2) - strtotime($Data1)) / $Q);
}

/**
* Retorna o pr�ximo dia
* @param int $dias Quantos dias pra fente,
*
* @example proximoDia() -> Retorna amanh�
* @example proximoDia(2) -> Retorna depois de amanh� (lol)
*
* @return string
*/
public static function diaAnterior($data){

  $temp = explode("/", $data);
  return date("d/m/Y", mktime(0, 0, 0, $temp[1], $temp[0] - 1, $temp[2]));

}

public static function diaSeguinte($data){

    $temp = explode("/", $data);
    return date("d/m/Y", mktime(0, 0, 0, $temp[1], $temp[0] + 1, $temp[2]));
  
  }

//funcao para somar quantidade de dias em uma determinada data
public static function somarDias($data, $dias){

  $temp = explode("/", $data);
  return date("d/m/Y", mktime(0, 0, 0, $temp[1], $temp[0] + $dias, $temp[2]));

}

//funcao para subtrair quantidade de dias em uma determinada data formato de parametro dd/mm/aaaa
public static function subtrairDias($dataIni, $dataFim){
    $dias = 0;
    $partes = explode('/', $dataIni);
    $data1 = mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
    
    $partes2 = explode('/', $dataFim);
    $data2 = mktime(0, 0, 0, $partes2[1], $partes2[0], $partes2[2]);
    
    $diferenca = $data2 - $data1;
    $dias = (int)floor($diferenca / (60*60*24));
    
    return $dias;

}

/**
 * <b> Funcao para retornar as horas incrementado os minutos. Usado: P_distribui_os </b>
 * @param TIME $hora horas atual para adicionar tempo
 * @param INT $min minutos a serem somados
 * @return TIME
 */
public function SomaMinutos($hora,$min) {
   // echo "<BR>FNC somaminuto ".$hora."-".$min."<BR>";
    $minuto = explode(".", $min);
    $horaNova = strtotime("$hora + $minuto[0] minutes");
    $result = date("H:i",$horaNova);
    
    return $result;
}

/**
 * <b> Funcao para retornar Data formatada para banco, apresentação ou null Usado: em todas classes GET </b>
 * @param CHAR $format tipo do formato de retorno, F - formatação para form / B - formatação para banco / NULL - retorna conteudo original
 * @param DATETIME $dateTime  valor original a ser formatado
 * @param BOOLEAN $time se formata com hora ou não.
 * @return DATETIME
 */
public static function formatDateTime($format, $dateTime, $time=null) {
    $returnDate = NULL;
    if (($dateTime !='') and ($dateTime !='0000-00-00') and ($dateTime !='0000-00-00 00:00:00')){
        $aux = strtr($dateTime, "/","-");
        switch ($format) {
                case 'F':
                    if ($time == 'TRUE'){
                        $returnDate = date('d/m/Y H:i', strtotime($aux));}
                    else {    
                        $returnDate = date('d/m/Y', strtotime($aux)); }
                    break;
                case 'B':
                    if ($time == 'TRUE'){
                        $returnDate = c_date::convertDateBd($aux, gerenciadorDB);}
                    else {    
                        $returnDate = c_date::convertDateBdSh($aux, gerenciadorDB);}
                    break;
                default:
                    $returnDate = $aux;
        }
    } else {
        $returnDate = '00-00-00';
    }
    return $returnDate;
}

} // Fim das Definicoes da Classe


?>
