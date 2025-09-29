<?php

/****************************************************************************
*Cliente...........:
*Contratada........: ADM Service
*Desenvolvedor.....: Lucas Tortola da Silva
*Sistema...........: Sistema de Informacao Gerencial
*Classe............: P_download - baixar arquivo .txt
*Ultima Atualizacao: 21/03/2018
****************************************************************************/


include "../../class/cnv/c_movimento.php";
include_once("../../bib/c_tools.php");

//Class p_download_remessa
Class p_download extends c_movimentos {
        private $m_submenu = null;
	private $m_letra = null;
        private $m_arquivo = null;
        private $m_diretorio = null;
        private $m_par = null;

//---------------------------------------------------------------
//---------------------------------------------------------------
public function p_download($letra, $submenu, $arquivo, $diretorio){

  $this->m_letra = $letra;
  $this->m_submenu = $submenu;
  $this->m_diretorio = $diretorio;
  $this->m_arquivo = $arquivo;
  $this->m_par = explode("|", $this->m_letra);
  
  session_start(); 
  $this->carrregaVarsConfig(0);
  $this->cabecalhoRel();
  $this->from_array($_SESSION['user_array']);
}//fim p_download_remessa($letra, $submenu, $diretorio)


function controle(){
switch ($this->m_submenu){
   
		default:
                    
                    $this->relatorioDownload('');
	
	}

} // fim controle
//---------------------------------------------------------------



//---------------------------------------------------------------
//---------------------------------------------------------------
public function relatorioDownload($mensagem){
include $this->js."/cnv/s_funcionario.js";

?>
<html>
    <head>
        <title>Download Remessa</title>
        <link rel=stylesheet type='text/css' href='../../css/style.css'>
    </head>
<FORM NAME="download" ACTION="<?php echo $_SERVER['SCRIPT_NAME']; ?>" METHOD="GET">
   <input name=submenu       type=hidden value="">
   <input name=arquivo       type=hidden value="">
   <input name=letra         type=hidden value="<?php echo $this->m_letra ?>">
   <input name=diretorio     type=hidden value="<?php echo $this->m_diretorio ?>">
   

<table width="550" border="0" align="center">
    <tr>
        <td width="10" class="marcadortitulo">
            <div align="center">
                <b>::</b>
            </div>
        </td>
        <td width="550" class="TituloPagina"> 
            <b>Lista de <?php echo $this->m_diretorio; ?> dispon&iacute;veis para Download </b>
        </td>
    </tr>
</table>
<br>
   
<table width="550" border="0" align="center">
	<tr>
  	    <td>
                Clique no arquivo para Download</td>
	    <td>
  	  	  <div align="right">
		     <input type="button" name="Submit" value="Pesquisar" class="coresbotao" onClick="javascript:submitConsulta();">
		  </div>
	    </td>
	</tr>
	<tr>
		<td width="550" class="marcadortitulo"> <b>
                    <?php echo $mensagem ?> </b>
                </td>
	</tr>
	<tr>
            <td class="Pesquisa" colspan="4" height="1">
                
            </td>
	</tr>
</table>   

<br>


<!-- Campos Pesquisa -->
<table width="650" border="1" align="center" class="ColunaTitulo">
  
    <tr>
        <td class="ColunaTitulo">
                    Base Fechamento - M&ecirc;s:
                    <input type="text" size="5" name="mesbase" value="<?php 
                            if($dshowini = $this->m_par[0] == "") {echo date("m");}
                            else { echo $this->m_par[0];} ?>">
        </td>
        <td class="ColunaTitulo">
                    Ano:
                    <input type="text" size="5" name="anobase" value="<?php 
                            if($dshowini = $this->m_par[1] == "") {echo date("Y");}
                            else { echo $this->m_par[1];} ?>">
         </td>
	 
	
    </tr>
</table>



<br>
<!-- Resultado Pesquisa -->
<?php
    //variavel para guardar o valor do diretorio selecionado

    $dir = $this->m_diretorio;

    
    
    //caminho do diretorio que vai ser lido
    $path = "../../".$dir."/";
    $diretorio = dir($path);

    echo"<table width=650  border=0 cellpadding=2 cellspacing=2 class=cortabela align=center>";
    
    //while para listar os arquivos do diretorio
    while($arquivo = $diretorio->read()){
            if (($arquivo == '.') or ($arquivo =='..')){

            }else{
                if (isset($this->m_letra)){
                    //explode para consulta
                    $consulta = explode("_", $arquivo);
                    if (($consulta[0] == $this->m_par[1]) && ($consulta[1] == $this->m_par[0]) ){
                        echo "<tr><td class=marcadortitulo>";
                        echo"<a href=../../baixar.php?arquivo=".$dir."/".$arquivo.">".$arquivo."</a>";
                        echo "</td></tr>";
                    }//if                    
                }//if                
            }//else       
     }//fim while
     echo "</html>";
     $diretorio -> close();
?>
</form>
</html>

<?php
} //fim relatorioDownload
//-------------------------------------------------------------
}	//	END OF THE CLASS


// Rotina principal - cria classe
  $download = new p_download($_REQUEST['letra'], $_REQUEST['submenu'], $_GET['arquivo'],$_REQUEST['diretorio']);

  $download->controle();
 
  
?>
