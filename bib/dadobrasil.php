<?php
// require_once "Mail.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\POP3;

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/POP3.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
//$mail = new PHPMailer\PHPMailer\PHPMailer(false);
try {
    //Server settings
    $mail->SMTPDebug = 0;                                 // 2 - Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    // $mail->Host = 'smtplw.com.br';                                  // Specify main and backup SMTP servers - 'smtp1.example.com;smtp2.example.com'
    // $mail->Username = 'transporte@lajesivemar.com.br';                         // SMTP username
    // $mail->Password = 'ivemar2017';                             // SMTP password
    // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    // $mail->Port = 587;                                    // TCP port to connect to
    // $remetente = "transporte@lajesivemar.com.br";
    // $nomeRemetente = "Lajes Ivemar";
    $mail->Host = 'smtp.gmail.com.br';                                  // Specify main and backup SMTP servers - 'smtp1.example.com;smtp2.example.com'
    $mail->Username = 'marciosergio8@gmail.com';                         // SMTP username
    $mail->Password = 'mss=2015#-';                             // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to
    $remetente = "marciosergio8@gmail.com";
    $nomeRemetente = "Marcio Gmail";

    $mail->SMTPOptions = array( 'ssl' => array( 
        'verify_peer' => false, 
        'verify_peer_name' => false, 
        'allow_self_signed' => true ) );

    //  $mail->Port = 25;                                    // TCP port to connect to
    $nomeDestinatario = "Marcio Sergio da Silva";
    $emailDestinatario = "marcio.sergio@admsistema.com.br";
    $body = "Hi,\n\nHow are you?";

    //Recipients
    $mail->setFrom($remetente, $nomeRemetente);
    $mail->addAddress($emailDestinatario, $nomeDestinatario);     // Add a recipient
    //Attachments
    // $mail->addAttachment($Attachment1);         // Add attachments
    // $mail->addAttachment($Attachment2);    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML / alterado para false 02/07/2019
    $mail->Subject = "teste Subject";
    $mail->Body    = $body;
    $mail->AltBody = "teste Body";

    $result = $mail->send();
    echo $result;
} catch (Exception $e) {
    echo 'Email Nfe não enviado. Mailer Error: '. $mail->ErrorInfo;
}
 



// $from = "Remetente <smtp@admservice.com.br>";
// //$to = "Destinatario <silas_i@msn.com>"; // <<<<<< ------- ALTERE
// $to = "Destinatario <marcio.sergio@admservice.com.br>"; // <<<<<< ------- ALTERE ESTE EMAIL PARA O SEU E CARREGUE A PAGINA NO NAVEGADOR
// $subject = "Teste!";
// $body = "Hi,\n\nHow are you?";
// $host = "mail.admservice.com.br";
// $username = "marcio.sergio@admservice.com.br";
// $password = "mss=2021@novo";
// $headers = array ('From' => $from,
// 'To' => $to,
// 'Subject' => $subject);
// $smtp = Mail::factory('smtp',
// array ('host' => $host,
// 'auth' => true,
// 'username' => $username,
// 'password' => $password));
// $mail = $smtp->send($to, $headers, $body);
// if (PEAR::isError($mail)) {
// echo("<p>" . $mail->getMessage() . "</p>");
// } else {
// echo("<p>Message successfully sent!</p>");
// }
?>