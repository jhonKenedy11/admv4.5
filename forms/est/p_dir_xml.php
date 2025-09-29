<?php

/**
 * @package   astec
 * @name      p_dir_xml
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      12/04/2016
 */
// Evita que usuários acesse este arquivo diretamente
if (!defined('ADMpath')): exit;
endif;

$pasta = '/var/www/html/infov3/nfe/saida/';


echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'" enctype="multipart/form-data">';		

// extencoes aceitas

foreach (glob($pasta."*.*") as $arquivo) {
        $name = explode("/", $arquivo);
        echo "<a href='../".ADMversao."/bib/download.php?data=".$arquivo."'>".$name[7]."</a><br>";
}
//echo $dirP;
echo   '</form>';


?>
