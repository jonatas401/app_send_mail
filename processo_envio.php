<?php

//print_r($_POST);

    require "./bibliotecas/PHPMailer/Exception.php";
    require "./bibliotecas/PHPMailer/OAuth.php";
    require "./bibliotecas/PHPMailer/PHPMailer.php";
    require "./bibliotecas/PHPMailer/POP3.php";
    require "./bibliotecas/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);
    //classe do objeto da mensagem a ser enviada 
    class Mensagem {

        private $destino = null;
        private $assunto = null;
        private $conteudo = null;

        public function __get($attr){
            return $this->$attr;
        }
        public function __set($attr, $valor){
            return $this->$attr = $valor;
        }
        public function mensagemValida(){
            if(empty($this->destino) || empty($this->assunto) || empty($this->conteudo)){
                return false;
            }
            return true;
        }

}
$mensagem = new Mensagem();
//pegando as informações da mensagem pela variavel globla $_POST e mandando pro objeto tratar
$mensagem->__set('destino',$_POST['destino']);
$mensagem->__set('assunto',$_POST['assunto']);
$mensagem->__set('conteudo',$_POST['conteudo']);

if(!$mensagem->mensagemValida()){
    echo 'algo de errado, preencha os dados e tente novamante';   
    header("location: index.html?error");
}

$mail = new PHPMailer(true);

try {
    //Server settings / consigurações do servidor de email
    $mail->SMTPDebug = false;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com'; //servidor smtp para envio do email // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'exemplo@teste.com'; // colocar seu email                  // SMTP username
    $mail->Password = 'senha';   //colocar sua senha do email para autenticação                        // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom($mensagem->__get("destino"), 'nome do remetente');
    $mail->addAddress($mensagem->__get("destino"), 'nome do destinatario');     // Add a recipient             // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $mensagem->__get("assunto");
    $mail->Body    = $mensagem->__get("conteudo");
    $mail->AltBody = "é necessario usar um client que suporte HTML para ver essa mensagem completamente";

    $mail->send();
    $sucesso = true;
    
} catch (Exception $e) {
    echo 'Não foi possivel enviar o E-mail tente novamnete mais tarde'.'<br>';
    echo 'Detalhes do erro: ' . $mail->ErrorInfo;
    $sucesso = false;
}

?>

<html>
    <head>
    <meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    </head>
    <body>
    <? if($sucesso){ ?>
    <div class="container">  

			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
            </div>
            
            <h1 class="text-seccess">enviado com sucesso</h1>
    </div>
            <? }?>
            <a class="btn btn-success" style="margin-left: 250px;" href="index.html">voltar</a>
    </body>
  
</html>