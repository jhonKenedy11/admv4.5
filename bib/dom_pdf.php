<?php

$dir = dirname(__FILE__);
require_once($dir . "/../../smarty/libs/Smarty.class.php");

// caminhos absolutos para todos os diretorios do Smarty
$smarty = new Smarty;

$smarty->template_dir = $dir . "/../template/cat";
$smarty->compile_dir = $dir . "/../../bianco/smarty/templates_c/";
$smarty->config_dir = $dir . "/../../bianco/smarty/configs/";
$smarty->cache_dir = $dir . "/../../bianco/smarty/cache/";

// reference the Dompdf namespace
require_once("dompdf/src/Autoloader.php");

Dompdf\Autoloader::register();
use Dompdf\Dompdf;
use Dompdf\Options;

//==============================================
$smarty->assign('dataAtual', strftime('%A, %d de %B de %Y', strtotime('today')));
$smarty->assign('pathImagem', $dir . "/../../bianco/images");
$smarty->assign('cssBootstrap', true);
$smarty->assign('dataImp', date("d/m/Y H:i:s"));

$lanc = "";
$lancItem = ""; 
$lancServico = ""; 
$empresa = "";

$smarty->assign('os', $lanc);
$smarty->assign('empresa', $empresa);
$smarty->assign('osItem', $lancItem);
$smarty->assign('osServico', $lancServico);

// pega url imagem p/ converter pdf
$urlImg = "http://localhost/bianco/images/logo.png";

$smarty->assign('urlImg', $urlImg);

$html = $smarty->fetch('os_imprime.tpl');  
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
// conversão PDF
$dompdf = new Dompdf($options);

// instantiate and use the dompdf class
// $dompdf = new Dompdf();
$dompdf->loadHtml('hello world');
// $dompdf->load_html($html);            

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');


//==============================================
// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('teste.pdf',array('Attachment'=>0));

?>