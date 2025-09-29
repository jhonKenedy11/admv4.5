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
    public function SendMail($host, $remetente, $nomeRemetente, $senha, $mensagem, $assunto, $emailDestinatario, $nomeDestinatario, $emailCC, $nomeCC, $Attachment=null, $Attachment2=null, $param=null ){

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
            $mail->SMTPDebug = SMTP::DEBUG_OFF; //Enable verbose debug output
            $mail->isSMTP();                    //Send using SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port     = 587;                         //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->SMTPAuth = true;             //Enable SMTP authentication
            $mail->Host     = $host;            //Set the SMTP server to send through
            $mail->Username = $remetente;       //SMTP username
            $mail->Password = $senha;
            
            //echo $mail->Password.'</br>';
            //echo $mail->Username.'</br>';
            //echo $mail->Host.'</br></br>';
            //para SSL $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS ex: PORT 465;
            //para SEM SSL $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS ex: PORT 587;

            
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            //$mail->Port       = 587;  

            //Recipients
            if ($remetente != ""){
                $mail->setFrom($remetente, $nomeRemetente);
            }else{
                $msg = 'Email Remetende inválido';
                $sendemail = false;
            }

            if ($emailDestinatario != ""){ 
                //se origem for acompanhamento
                if($param == 'several'){

                    $destis = explode(';',$emailDestinatario);
                    //foreach para adicionar mais de um estinatario
                    foreach($destis as $dest){
                        $destinatario = trim($dest);
                        $mail->addAddress($destinatario);
                    }
                }else{
                    $mail->addAddress($emailDestinatario, $emailDestinatario);     // Add a recipient
                }

            }else{
                $msg .= ' - Email Destinatário inválido';
                $sendemail = false;
            }
            $mail->addCC($remetente, $nomeRemetente);               // CC Remetente

            if ($emailCC != ""){ 
                $mail->addCC($emailCC, $nomeCC);               // Name is optional
            }

            //Attachments
            if($param == 'several'){
                //foreach para incluir mais de um anexo
                foreach($Attachment as $Attach){
                    $montPath = ADMpath . $Attach;
                    $mail->addAttachment($montPath);
                }
            }else{
                $mail->addAttachment($Attachment);   // Optional name
            }


            //Content
            $mail->isHTML(true);                                  // Set email format to HTML / alterado para false 02/07/2019
            $mail->Subject = $assunto;
            //para tratamento de acentos
            $mensagem = mb_convert_encoding($mensagem, 'HTML-ENTITIES', 'UTF-8');


            //logica para converter as imagens base 64
            $pattern = '/<img[^>]+src[\\s=\'"]+([^\'">]+)[\'"]/i';
            preg_match_all($pattern, $mensagem, $matches);
            $images = [];

            // Executar a regex para encontrar todas as tags img
            if (preg_match_all($pattern, $mensagem, $matches)) {
                // Iterar sobre os srcs das imagens encontradas
                foreach ($matches[1] as $imageSrc) {
                    // Obter o nome do arquivo sem o caminho
                    $nomeArquivo = basename($imageSrc);

                    // Obter informações sobre o arquivo
                    $infoArquivo = pathinfo($nomeArquivo);

                    // Adicionar o par src-nome ao array de imagens
                    $images[$imageSrc] = $infoArquivo["filename"];
                }
            }

            // Agora você pode usar os srcs e nomes das imagens como desejar, por exemplo:
            foreach ($images as $imageSrc => $imageName) {
                // Use $imageSrc como o src da imagem e $imageName como o nome da imagem
                // por exemplo, com addEmbeddedImage do phpMailer
                //echo "Imagem encontrada: $imageSrc, Nome da imagem: $imageName\n";
                $retAdd = $mail->addEmbeddedImage($imageSrc, $imageName);
                $mensagem = $this->substituirStringImg($mensagem, $imageSrc, 'cid:'.$imageName);
            }


            $mail->Body = $mensagem;
            $mail->AltBody = $assunto;

            //echo "remetenre: ".$remetente."<br> - destinatario: ".$emailDestinatario . '<br>pw: '. $senha . '<br>';
            if ($sendemail == true) {
                $result = $mail->send();
                return $result;
            } else {
                return $msg;
            }
        // } catch (Exception $e) {
        //     return 'Email Nfe não enviado. Mailer Error: '. $mail->ErrorInfo;
        // }
    }

    public function SendMail2($host, $remetente, $nomeRemetente, $senha, $mensagem, $assunto, $emailDestinatario, $nomeDestinatario, $emailCC, $nomeCC, $Attachment1, $Attachment2 ){

        //Authenticate via POP3.
        //After this you should be allowed to submit messages over SMTP for a few minutes.
        //Only applies if your host supports POP-before-SMTP.
        //$pop = POP3::popBeforeSmtp($host, 110, 30, $remetente, $senha, 1);
        //Create a new PHPMailer instance
        //Passing true to the constructor enables the use of exceptions for error handling
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        //$mail = new PHPMailer\PHPMailer\PHPMailer(false);
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // 2 - Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $host;                                  // Specify main and backup SMTP servers - 'smtp1.example.com;smtp2.example.com'
            $mail->CharSet = "UTF-8";
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $remetente;                         // SMTP username
            $mail->Password = $senha;                             // SMTP password
            $mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
        //  $mail->Port = 25;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom($remetente, $nomeRemetente);
            $mail->addAddress($emailDestinatario, $nomeDestinatario);     // Add a recipient
            //$mail->addAddress($emailCC, $nomeCC);               // Name is optional
        //    $mail->addReplyTo('info@example.com', 'Information');
        //    $mail->addCC('cc@example.com');
        //    $mail->addBCC('bcc@example.com');

            //Attachments
            $mail->addAttachment($Attachment1);         // Add attachments
            $mail->addAttachment($Attachment2);    // Optional name

            //Content
            $mail->isHTML(false);                                  // Set email format to HTML / alterado para false 02/07/2019
            $mail->Subject = $assunto;
            $mail->Body    = $mensagem;
            $mail->AltBody = $assunto;

            $result = $mail->send();
            return $result;
        } catch (Exception $e) {
            return 'Email Nfe não enviado. Mailer Error: '. $mail->ErrorInfo;
        }
    
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


    /**
    * @name sendLogEmail
    * Funcao para envio de log por E-mail
    * @author Jhon K S Meloo
    * @param array $log - Objeto contento as informacoes do log
    * @param string typeError //[CRÍTICO/MÉDIO/BAIXO]
    * @return boolen 
    */
    public function sendLogEmail(array $log){

        /* PADRAO DE ARRAY
        log[
            typeError,
            descriptionError,
            CodigoError,
            Message,
            process,
            modulo,
            extra
        ]
        */

        $session = json_decode(($_SESSION["user_array"]));


        if($log['dateTime'] == "" and $log['dateTime'] == null){
            $log['dateTime']  = date("Y-m-d H:i:s");
        }

        $assunto = "Suporte: " .$session[6] . " - Erro ". $log['typeError'] ." em " . ADMambDesc . " - " . $log['dateTime'] . ".";

        $htmlBody = '<!DOCTYPE html>
                        <html>
                        <head>
                            <style>
                                body { font-family: Arial, sans-serif; line-height: 1.6; }
                                .header { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; }
                                .details { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
                                .highlight { color: #dc3545; font-weight: bold; }
                                .code { background-color: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; }
                            </style>
                        </head>
                        <body>
                            <div class="header">
                                <h2> ERRO NO SISTEMA ERP - ' . $session[5] . '</h2>
                            </div>

                            <p>Olá, equipe de suporte,</p>
                            <p>Um erro foi detectado no sistema ERP ' . $session[6] . '. Seguem os detalhes para análise:</p>

                            <div class="details">
                                <h3>📋 Resumo do Erro</h3>
                                <ul>
                                    <li><strong>Ambiente:</strong> ' . ADMambDesc . ' </li>
                                    <li><strong>Nível do Erro:</strong> <span class="highlight"> '. $log['typeError'] .' </span></li>
                                    <li><strong>Módulo Afetado:</strong> ' . $log["modulo"] . '</li>
                                    <li><strong>Usuário:</strong> '. $session[0] . '</li>
                                    <li><strong>Nome Usuário:</strong> '. $session[1] . '</li>
                                    <li><strong>Data/Hora:</strong> ' . $log['dateTime'] . '</li>
                                </ul>

                                <h3>🔍 Detalhes Técnicos</h3>
                                <ul>
                                    <li><strong>Mensagem de Erro:</strong> ' . $log["descriptionError"] . '</li>
                                    <li><strong>Processo:</strong> ' . $log["process"] . '</li>
                                    <li><strong>Código do Erro:</strong> '. $log["codigoError"] . '</li>
                                    <li><strong>Descrição:</strong> ' . $log["descriptionError"] . '</li>
                                </ul>

                            </div>

                            <p>Atenciosamente,<br>
                            <strong>Sistema ERP '. $session[5] . '</strong></p>

                            <div style="font-size: 12px; color: #666;">
                                <hr>
                                <p>Este é um e-mail automático. Não responda diretamente.<br>
                                Em caso de dúvidas, acesse o <a href="#">Portal de Suporte</a>.</p>
                            </div>
                        </body>
                        </html>';

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //Enable SMTP debugging
            //SMTP::DEBUG_OFF = off (for production use)
            //SMTP::DEBUG_CLIENT = client messages
            //SMTP::DEBUG_SERVER = client and server messages    
            //SMTP::DEBUG_CONNECTION
            $mail->SMTPDebug = SMTP::DEBUG_OFF; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host       = 'mail.admsistema.com.br'; //Set the SMTP server to send through
            $mail->SMTPAuth   = true; //Enable SMTP authentication
            $mail->Username   = 'jhonkenedy@admsistema.com.br'; //SMTP username
            $mail->Password   = 'adm@2025'; //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption  //ENCRYPTION_SMTPS
            $mail->Port       = 587; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('suporteadmsistemas@outlook.com', 'Log para Suporte');
            $mail->addAddress('suporteadmsistemas@outlook.com');     //Add a recipient

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $assunto;
            $mail->Body    = $htmlBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }   

    }

    // Função para substituir uma string dentro da tag img por outra string
    function substituirStringImg($html, $stringAntiga, $stringNova) {
        // Expressão regular para localizar a string dentro da tag img
        $pattern = '/(<img[^>]+)src=[\\s=\'"]+([^\'">]+)[\'"]([^>]*>)/i';

        // Função de callback para substituir a string
        $callback = function($matches) use ($stringAntiga, $stringNova) {
            // Substituir a string antiga pela nova
            $novoSrc = str_replace($stringAntiga, $stringNova, $matches[2]);
            // Retornar a tag img com o novo src
            return $matches[1] . 'src="' . $novoSrc . '"' . $matches[3];
        };

        // Realizar a substituição usando a função preg_replace_callback
        $novoHtml = preg_replace_callback($pattern, $callback, $html);

        // Retornar o HTML com as substituições feitas
        return $novoHtml;
    }
}
?>