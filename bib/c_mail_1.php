<?php 
include_once($dir . "/../../bib/class.phpmailer.php");

class admMail{

/**
* @var object
*/
private $mail;

public function __construct(){
$this->mail = new PHPMailer;
}


/**
* Funcao para envio de E-mail
* @param string $remetente - E-mail do Remetente
* @param string $nomeRemetente - Nome do Remetente
* @param string $mensagem - Mensagem a ser enviada
* @param string $assunto - Assunto do E-mail
* @param string $emailDestinatario - E-mail do destinatario
* @param string $nomeDestinatario - Nome do Destinatario
* @param string $emailCC - E-mail do destinatario Copia
* @param string $nomeCC - Nome do Destinatario Copia
* @return boolen 
*/
public function SendMail($host, $remetente, $nomeRemetente, $senha, $mensagem, $assunto, $emailDestinatario, $nomeDestinatario, $emailCC, $nomeCC ){


$mail = new PHPMailer;

/*
$mail->IsSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail.admservice.com.br;exodo.dadobrasil.com.br';  // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'tecnica@admservice.com.br';                            // SMTP username
$mail->Password = 'adm123';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
$this->mail->Port = 587;

$mail->From = 'tecnica@admservice.com.br';
$mail->FromName = 'admService TI';
//$mail->AddAddress('marcio.sergio@admservice.com.br');  // Add a recipient
$mail->AddAddress($emailDestinatario, utf8_decode($nomeDestinatario));               // Name is optional
//$mail->AddReplyTo('marcio.sergio@admservice.com.br', 'Information');
//$mail->AddCC('marcio.sergio@admservice.com.br');
//$mail->AddBCC('marcio.sergio@admservice.com.br');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->AddAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->AddAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->IsHTML(true);                                  // Set email format to HTML

$mail->Subject = $assunto;
$mail->Body    = $mensagem;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   return false; 
   exit;
}

return true; 
*/    
    
  $this->mail->Host = $host; 
  $this->mail->IsSMTP();
  $this->mail->IsHTML(TRUE);


  $this->mail->SMTPAuth = TRUE; 
  $this->mail->Username = $remetente; 
  $this->mail->Password = $senha; 
  $this->mail->Mailer   = "smtp";
  $this->mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
  $this->mail->Port = 587;
  $this->mail->CharSet = "UTF-8";
  $this->mail->SMTPDebug = 2;  
  $this->mail->Debugoutput = 'html';
//  $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
  
  $this->mail->From     = $remetente;
  $this->mail->FromName = utf8_decode($nomeRemetente); 
  $this->mail->Body     = $mensagem;
  $this->mail->Subject  = $assunto; 
  $this->mail->AddAddress($emailDestinatario, utf8_decode($nomeDestinatario));
  if ($emailCC != ""){ 
  	  $this->mail->AddReplyTo($emailCC, utf8_decode($nomeCC));
  }
    //Attachments
    //$this->addAttachment($Attachment1);         // Add attachments
    //$this->addAttachment($Attachment2);    // Optional name  
  //print_r($this->mail);
  if ($emailDestinatario != ''){
    $status = $this->mail->Send();  
    if(!$status){ 
        return false;}
    else
        return true; }
  else  
        return false;

 
}
}
?>