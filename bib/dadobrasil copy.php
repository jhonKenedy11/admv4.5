<?php
// require_once "Mail.php";

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/POP3.php';

$from = "Remetente <smtp@admservice.com.br>";
//$to = "Destinatario <silas_i@msn.com>"; // <<<<<< ------- ALTERE
$to = "Destinatario <marcio.sergio@admservice.com.br>"; // <<<<<< ------- ALTERE ESTE EMAIL PARA O SEU E CARREGUE A PAGINA NO NAVEGADOR
$subject = "Teste!";
$body = "Hi,\n\nHow are you?";
$host = "mail.admservice.com.br";
$username = "marcio.sergio@admservice.com.br";
$password = "mss=2021@novo";
$headers = array ('From' => $from,
'To' => $to,
'Subject' => $subject);
$smtp = Mail::factory('smtp',
array ('host' => $host,
'auth' => true,
'username' => $username,
'password' => $password));
$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail)) {
echo("<p>" . $mail->getMessage() . "</p>");
} else {
echo("<p>Message successfully sent!</p>");
}
?>