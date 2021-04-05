<?php 
    //print_r($_POST);

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;  

    class Message{
        private $to = null;
        private $subject = null;
        private $message = null;

        public $status = array('codigo_status'=>null, 'descricao_status' => '');

        public function __get($attr){
            return $this->$attr;
        }

        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function isValidMessage(){
            if(empty($this->to) || empty($this->subject) || empty($this->message)){
                return false;
            }
            return true;
        }
    }

    $message = new Message();

    $message -> __set('to', $_POST['para']);
    $message -> __set('subject', $_POST['assunto']);
    $message -> __set('message', $_POST['mensagem']);
/*
    echo '<pre>';
    print_r($message);
    echo '</pre>';
*/
    if(!$message->isValidMessage()){
        echo 'Esta mensagem não é válida.';
        die();
        header('Location: index.php');
    }
    $mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'sendermail.fandb@gmail.com';                     //SMTP username
    $mail->Password   = '!@#$4321';                               //SMTP password
    $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('sendermail.fandb@gmail.com', 'Sender Mail');
    $mail->addAddress($message->__get('to'), 'Receiver Mail');     //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
   // $mail->addReplyTo('info@example.com', 'Information');
   // $mail->addCC('cc@example.com');
   // $mail->addBCC('bcc@example.com');

    //Attachments
  // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
  // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $message->__get('subject');
    $mail->Body    = $message->__get('message');
  //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    $message->status['codigo_status'] = 1;
    $message->status['descricao_status'] = 'Message has been sent';

} catch (Exception $e) {
    $message->status['codigo_status'] = 2;
    $message->status['descricao_status'] = "Message could not be sent. <br/> Details: {$mail->ErrorInfo}";
}

?>
<html>

<head>
    <meta charset="utf-8" />
    <title>App Mail Send</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
		</div>
        <div class="row">
            <div class="col-md-12">
                
                <? if($message->status['codigo_status']==1){?>
                    <div class="container">
                        <h1 class="display-4 text-success"> Sucess! </h1>
                            <p><?= $message->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Back</a>
                    </div>
                <? } ?>

                <? if($message->status['codigo_status']==2){?>
                    <div class="container">
                        <h1 class="display-4 text-danger"> Falied! </h1>
                            <p><?= $message->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Back</a>
                    </div>
                <? } ?>

            </div>
        </div>
    </div>    
</body>
</html>