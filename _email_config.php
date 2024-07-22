<?php
//
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
//
header('Content-Type: text/html; charset=UTF-8');

//php 8
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './phpmailer-6.9.1/src/Exception.php';
require './phpmailer-6.9.1/src/PHPMailer.php';
require './phpmailer-6.9.1/src/SMTP.php';

/**
 * Envia emails. Para vários destinatários, cópias ou copia ocultas, deve-se passar como parâmetro 
 * um array no qual o índice deve ser o endereço de email e o valor o nome que pode ser vazio. Bem como 
 * para o envio de mais de um anexo, o índice deve ser o endereço do arquivo a ser anexado e o valor o nome 
 * que tbm pode ser vazio. Para o envio unitário somente com o endereço do email, deve ser passado apenas o 
 * endereço do email como string assim como o anexo.
 * Ex.: enviarEmail(email@dominio.com, assunto, mensagem);
 *      enviarEmail(email@dominio.com, assunto, mensagem, anexo)
 * @param mixed $para - email do destinatário, chave deve ser o email e o valor o nome (opcional)
 * @param string $assunto - assunto do email
 * @param string $mensagem - conteúdo do email (texto html ou plano)
 * @param mixed $copia - email que receberá a cópia da mensagem
 * @param mixed $copiaOculta - email que receberá a cópia OCULTA 
 * @return mixed - TRUE ou mensagem do erro
*/
 
function enviarEmail($para, $assunto, $mensagem, $copia = NULL, $copia_oculta = NULL) { 


	//php 7
	//require_once("phpmailer/class.phpmailer.php");
	//require_once("phpmailer/class.smtp.php");


	global $error;

	//$mail = new PHPMailer;
	$mail = new PHPMailer(true);

	try {
    	
		// Define a linguagem para as mensagens de erro
		
		// php 7
		//$mail->setLanguage('br', 'phpmailer/language/'); 

		// php 8
		$mail->setLanguage('br', './phpmailer-6.9.1/language/'); 
    	
		//Server settings
    	//$mail->SMTPDebug = 2;//SMTP::DEBUG_SERVER; //Enable verbose debug output (0 ou 2)

		// @@ se ativar isso não funciona !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!		
    	$mail->isSMTP(); //Send using SMTP
    	
		$mail->Host       = 'smtppro.zoho.com'; //Set the SMTP server to send through
    	$mail->SMTPAuth   = true; //Enable SMTP authentication
    	$mail->Username   = 'jader@connectzap.com.br'; //SMTP username
	    $mail->Password   = '@Connectzap100'; //SMTP password
	    $mail->SMTPSecure = 'ssl'; //Enable implicit TLS encryption (ssl/tls)
	    $mail->Port       = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
	    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	    //$mail->Port = 587;

		//$mail->Host = 'br498.hostgator.com.br';                 // Servidor de emails	
		//$mail->SMTPAuth = true;                                 // Define que tera autenticacao SMTP
		//$mail->Username = 'no-reply@inframicro.net';            // Usuario SMTP
		//$mail->Password = 'Noreply@2017.';                      // Senha do usuario
		//$mail->SMTPSecure = 'ssl';                              // Ativa criptografia tls ou ssl
		//$mail->Port = 465;                                      // Porta de conexao
	

    	//Recipients
	    $mail->setFrom('contato@connectzap.com.br', 'ConnectZap');
    	$mail->addAddress($para);     //Add a recipient
	    //$mail->addAddress('ellen@example.com', 'Ellen'); //Name is optional
    	$mail->addReplyTo('jader@connectzap.com.br', 'Jader');
		//$mail->addReplyTo('alexandre@inframicro.com.br', 'Teste');

		if(!empty($copia)){
	    	$mail->addCC($copia);
		}
		if(!empty($copia_oculta)){
    		$mail->addBCC($copia_oculta);
		}

	    //Attachments
    	#$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
	    #$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    	//Content format
	    $mail->isHTML(true);                                  //Set email format to HTML    	
		$mail->CharSet = 'utf-8';

		// Define a mensagem (Texto e Assunto)
		$mail->Subject = $assunto;
	    $mail->Body    = $mensagem;
    	//$mail->AltBody = 'Texto alternativo do envio do email';

	    $envio = $mail->send();
	    if($envio == true){
    		$error = 'Mensage enviada!';
	    }else{
	    	$error = $mail->ErrorInfo;
    		echo $mail->ErrorInfo;
	    }
		return $envio;
	} 
	catch (Exception $e) {
		echo $e->getMessage();
		$error = "No se puede enviar el mensaje: {$e->getMessage()}"; 
		return false;
	}
}