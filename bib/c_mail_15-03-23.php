<?php 
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ERROR);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\POP3;

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/POP3.php';


class admMail{

/**
* @var object
*/
private $mail;

public function __construct(){
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
public function SendMail($host, $remetente, $nomeRemetente, $senha, $mensagem, $assunto, $emailDestinatario, $nomeDestinatario, $emailCC, $nomeCC, $Attachment1, $Attachment2 ){

//Authenticate via POP3.
//After this you should be allowed to submit messages over SMTP for a few minutes.
//Only applies if your host supports POP-before-SMTP.
//$pop = POP3::popBeforeSmtp($host, 110, 30, $remetente, $senha, 1);
//Create a new PHPMailer instance
//Passing true to the constructor enables the use of exceptions for error handling
$sendemail = true;
$msg = 'Email não enviado, ';
$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
//$mail = new PHPMailer\PHPMailer\PHPMailer(false);
//try {

    //Server settings
    //Enable SMTP debugging
    //SMTP::DEBUG_OFF = off (for production use)
    //SMTP::DEBUG_CLIENT = client messages
    //SMTP::DEBUG_SERVER = client and server messages    
    //SMTP::DEBUG_CONNECTION
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    //$mail->SMTPAutoTLS = true;


    $mail->Host       = $host;                          //Set the SMTP server to send through
    $mail->Username   = $remetente;                     //SMTP username
    $mail->Password   = $senha;                         //SMTP password
    $mail->SMTPSecure = 'none';//PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                            //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->isSMTP();                                            //Send using SMTP
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    //$mail->Port       = 587;  

    //Recipients
    if ($remetente != ""){ 
        $mail->setFrom($remetente, $nomeRemetente);
    }else{
        $msg = 'Email Remetende inválido';
        $sendemail = false;
    }
    
    if ($emailDestinatario != ""){ 
        $mail->addAddress($emailDestinatario, $emailDestinatario);     // Add a recipient
    }else{
        $msg .= ' - Email Destinatário inválido';
        $sendemail = false;
    }
    $mail->addCC($remetente, $nomeRemetente);               // CC Remetente

    if ($emailCC != ""){ 
        $mail->addCC($emailCC, $nomeCC);               // Name is optional
    }

    //Attachments
    $mail->addAttachment($Attachment1);         // Add attachments
    $mail->addAttachment($Attachment2);    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML / alterado para false 02/07/2019
    $mail->Subject = $assunto;
    $mail->Body    = $mensagem;
    $mail->AltBody = $assunto;

    // echo "remetenre: ".$remetente." - destinatario: ".$emailDestinatario;
    if ($sendemail == true) {
        $result = $mail->send();
        return $result;
    } else {
        return $msg;
    }
//} catch (Exception $e) {
  //  return 'Email Nfe não enviado. Mailer Error: '. $mail->ErrorInfo;
//}
 
}

public function SendMailOld($host, $remetente, $nomeRemetente, $senha, $mensagem, $assunto, $emailDestinatario, $nomeDestinatario, $emailCC, $nomeCC ){


$mail = new PHPMailer;
    
  $this->mail->Host = $host; 
  $this->mail->IsSMTP();
  $this->mail->IsHTML(TRUE);


  //$this->mail->SMTPAuth = TRUE; 
  $this->mail->Username = $remetente; 
  $this->mail->Password = $senha; 
  $this->mail->Mailer   = "smtp";
  $this->mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
  $this->mail->Port = 587;
  $this->mail->CharSet = "UTF-8";
  $this->mail->SMTPDebug = 0;  
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