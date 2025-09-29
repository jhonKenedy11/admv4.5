<?php

// Define o tempo máximo de execução em 0 para as conexões lentas
set_time_limit(0);
$imagem = $_GET['data'];
$name = explode("/", $imagem);
$tamanho = filesize($imagem);
$extensao = substr($imagem, -3);

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Expires: 0');
    header("Content-Length: " . $tamanho);
    header("Content-Disposition: attachment; filename=" . $name[7]);
    header("Content-Transfer-Encoding: binary");
    ob_end_clean(); //essas duas linhas antes do readfile
    flush();
    readfile($imagem);
?>