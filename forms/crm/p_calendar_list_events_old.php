<?php
/*
FullCalendar Interaction Plugin v4.1.0
Docs & License: https://fullcalendar.io/
(c) 2019 Adam Shaw

* @author Jhon Kenedy - jhon.kened11@hotmail.com
* @pagina desenvolvida usando FullCalendar,
*/

    $dir = (__DIR__);

    //include_once($dir . "/../../class/crm/c_contas_acompanhamento.php");
    include_once($dir . "/../../class/crm/c_calendar_conexao.php");
    include_once($dir . "/../../class/crm/c_contas_acompanhamento.php");

    //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
    $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

    $objAcomp = new c_contas_acompanhamento();
    $vendedor = $parmGet['vendedor'];

    $dataIni = $parmGet["primeiroDia"];
    //$dataIni = "2023-04-01";
    //$dataFim = "2023-04-14";
    $dataFim = $parmGet["ultimoDia"];
    //Consulta os acompanhamentos
    $query_events = "SELECT fca.PEDIDO_ID as id, CONCAT(fc.NOMEREDUZIDO) as title, fca.DATEINSERT as start, fca.DATEINSERT as end, fca.RESULTADO as resultado, ";
    $query_events .= "fca.ATIVIDADE as atividade_id, fca.ID as id_reg, fca.LIGARDIA AS ligar_dia, fca.`STATUS` as status_ped, fca.DATA as evento_realizado, ";
    $query_events .= "fc.NOME AS cliente_nome, fc.FONE AS cliente_telefone, fc.CELULAR as cliente_celular, fc.EMAIL as cliente_email, au.NOME as name_vendedor ";
    $query_events .= "FROM FIN_CLIENTE_ACOMP fca "; 
    $query_events .= "INNER JOIN FIN_CLIENTE fc ON fca.PESSOA = fc.CLIENTE ";
    $query_events .= "INNER JOIN AMB_USUARIO au ON fca.USRVENDEDOR = au.USUARIO ";
    $query_events .= "WHERE fca.USRVENDEDOR = ". $vendedor . " and fca.DATEINSERT BETWEEN '". $dataIni. "' and '". $dataFim ."' and fca.ATIVIDADE <> 999;";
    
    $sql = strtoupper($query_events);

    $resultado_events = $conn->prepare($query_events);
    $resultado_events->execute();

    $eventos = [];

    while($row_events = $resultado_events->fetch(PDO::FETCH_ASSOC)){

        $id               = $row_events['id'];
        $title            = utf8_encode($row_events['title']); 
        $start            = $row_events['start'];
        $end              = $row_events['end'];
        $resultado        =  utf8_encode($row_events['resultado']);
        $atividade_id     = $row_events['atividade_id'];
        $id_reg           = $row_events['id_reg'];
        $cliente_nome     = utf8_encode($row_events['cliente_nome']);
        $cliente_telefone = $row_events['cliente_telefone'];
        $cliente_celular  = $row_events['cliente_celular'];
        $cliente_email    = utf8_encode($row_events['cliente_email']);
        $name_vendedor    = utf8_encode($row_events['name_vendedor']);
        

        switch($row_events['atividade_id']){
            case '1': //ABERTO
                $color = '#6d8a66'; //verde
                break;
            case '2': //ANDAMENTO
                $color = '#518ee8'; //azul
                break;
            case '3': //CONCLUIDO
                $color = '#e85151'; //vermelho
                break;
        } //Fim switch
    
        $eventos[] = [
            'id' => $id, 
            'title' => $title, 
            'color' => $color, 
            'start' => $start, 
            'end' => $end,
            'atividade_id' => $atividade_id,
            'id_reg' => $id_reg,
            'ligar_dia' => $ligar_dia,
            'resultado' => $resultado,
            'cliente_nome' => $cliente_nome,
            'cliente_telefone' => $cliente_telefone,
            'cliente_celular' => $cliente_celular,
            'cliente_email' => $cliente_email,
            'name_vendedor' => $name_vendedor,
            'evento_realizado' => $data_evento_realizado,
            'evento_realizado_hora' => $hora_evento_realizado,
            'data_ligar_dia' => $data_ligar_dia,
            'hora_ligar_dia' => $hora_ligar_dia,
            ];
    }


$json = json_encode($eventos);

// Testa se o JSON gerado é válido
$result = json_decode($json);

if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
    // O JSON é inválido, há um erro
    $eventos = json_last_error_msg();
    header('Content-Encoding: gzip');
    echo gzencode(json_encode($eventos), 9);
}else{
    header('Content-Encoding: gzip');
    echo gzencode(json_encode($eventos), 9);
}

exit;
?>  