<?php   
//
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
//
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	die();
}

try{
		
	require_once '_email_faleconosco.php';

	// Email de destino de cópias.
	$emailDestino = "";
	$copiaOculta = "";

	$nomeremetente     	= trim($_POST['txtNome']);
	$empresa     		= trim($_POST['txtEmpresa']);
	$documento     		= trim($_POST['txtDoc']);	
	$telefone          	= trim($_POST['txtTel']);
	$emailremetente    	= trim($_POST['txtEmail']);
	$assunto    		= trim($_POST['txtAssunto']);
	$mensagemremetente  = trim($_POST['txtMensagem']);

	// Montar o corpo do e-mail.
	$mensagem =  "Nome: ".$nomeremetente."<br/>";
	$mensagem .= "Empresa: ".$empresa."<br/>";
	$mensagem .= "Documento: ".$documento."<br/>";	
	$mensagem .= "Telefone: ".$telefone."<br/>";
	$mensagem .= "E-mail: ".$emailremetente."<br/>";
	$mensagem .= "Assunto: ".$assunto."<br/>";
	$mensagem .= "Mensagem: ".$mensagemremetente."<br/>";

	// envio para o remetente (administrador do site)
	$envio = enviarEmail($emailremetente, $assunto, $mensagem, $emailDestino, $copiaOculta);

	if($envio) {
		echo "ok";	
	}
	else{
		echo "Problema para enviar";	
	}

}catch(Exception $ex){
	echo($ex->getMessage());
}
?>
