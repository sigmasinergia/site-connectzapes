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

require_once "./config.php";
require_once './phpmailer-6.9.1/src/Exception.php';
require_once './phpmailer-6.9.1/src/PHPMailer.php';
require_once './phpmailer-6.9.1/src/SMTP.php';

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

function enviarEmail($strName, $strEmail, $strSenha, $strToken)
{
	//
	$avaliacaoEmail = '
	<table class="HtmlMerger_47f499a2dfec4319a2707c8384330a69" style="width: 100%;" align="center" valign="middle">
	<tbody>
	<tr class="emc-nl-table__header"><!-- HEADER -->
	<td class="HtmlMerger_dc52a063cda0438bb0b7699577330a4c" style="text-align: center;" bgcolor="#43324d">
		<br><br>
	</td>
	</tr>
	</tbody>
	<tbody>
	<tr>
	<td class="HtmlMerger_dc52a063cda0438bb0b7699577330a4c" align="center" valign="middle">
	<h1 class="emc-nl-typo emc-nl-typo--heading-xl"><strong>Obrigado por</strong></h1>
	<br />
	<p class="emc-nl-typo emc-nl-typo--size-md" style="margin: 0px 64px; line-height: 1.5;">escolher o <strong>Connect Zap</strong>&nbsp;como seu gestor de servi&ccedil;os de envio de menssagem.</p>
	<br />
	<p class="emc-nl-typo emc-nl-typo--size-md" style="margin: 0px 64px; line-height: 1.5;">Ao longo dos pr&oacute;ximos <strong>30 dias</strong>, voc&ecirc; poder&aacute; experimentar todas as funcionalidades para ver se o programa satisfaz &agrave;s suas necessidades. Depois disso você pode cancelar a qualquer momento.</p>
	<br />
	</td>
	</tr>
	</tbody>
	<tbody>
	<tr>
	<td class="HtmlMerger_dc52a063cda0438bb0b7699577330a4c" align="center" valign="middle">
	<h2 class="emc-nl-typo emc-nl-typo--heading-xl"><strong>Token de acesso para API:</strong></h2>
	<strong>' . $strToken . '</strong>
	<br />
	<br />
	</td>
	</tr>
	<tr>
	<td class="HtmlMerger_dc52a063cda0438bb0b7699577330a4c" align="center" valign="middle">
	<h2 class="emc-nl-typo emc-nl-typo--heading-xl"><strong>Login de acesso ao painel:</strong></h2>
	<strong>Login de acesso: </strong>' . $strEmail . '
	<br/>
	<strong>Senha: </strong>' . $strSenha . '
	<br/>
	<br/>
	<div style="height: 42px; width: 180px; display: block; background-color: #008000; border-radius: 21px;"><a class="HtmlMerger_64efe3a0dbb94dfcbbe533513e0d32ce" style="text-decoration: none; height: 42px; font-weight: 400; color: #ffffff; display: block; line-height: 43px;" href="https://painel.connectzap.es/login/" target="_blank"><strong>Acessar Painel</strong> </a></div>
	<br />
	<br />
	<div style="height: 42px; width: 180px; display: block; background-color: #008000; border-radius: 21px;"><a class="HtmlMerger_64efe3a0dbb94dfcbbe533513e0d32ce" style="text-decoration: none; height: 42px; font-weight: 400; color: #ffffff; display: block; line-height: 43px;" href="https://painel.connectzap.es/tokens/manual.pdf" target="_blank"><strong>Manual Resumido</strong> </a></div>
	<br />
	<br />
	<div style="height: 42px; width: 180px; display: block; background-color: #008000; border-radius: 21px;"><a class="HtmlMerger_64efe3a0dbb94dfcbbe533513e0d32ce" style="text-decoration: none; height: 42px; font-weight: 400; color: #ffffff; display: block; line-height: 43px;" href="https://documenter.getpostman.com/view/25310515/2s8ZDeSyEm" target="_blank"><strong>Documentação da API</strong> </a></div>
	<br />
	<br />
	</td>
	</tr>
	<tr>
	<td class="HtmlMerger_dc52a063cda0438bb0b7699577330a4c" align="center" valign="middle">
	<div style="height: 42px; width: 180px; display: block; background-color: #43324d; border-radius: 21px;"><a class="HtmlMerger_64efe3a0dbb94dfcbbe533513e0d32ce" style="text-decoration: none; height: 42px; font-weight: 400; color: #ffffff; display: block; line-height: 43px;" href="https://api.whatsapp.com/send?phone=5521981587295"><strong>Solicitar suporte</strong> </a></div>
	</td>
	</tr>
	<tr>
	<td class="HtmlMerger_dc52a063cda0438bb0b7699577330a4c" align="center" valign="middle">
	<div style="height: 42px; width: 180px; display: block;"></div>
	</td>
	</tr>
	</tbody>
	</table>
	';
	//
	//-----------------------------------------------------------------------------------------------//
	//
	$assinaturaEmail = '
	<div >
	   <table cellpadding="0" cellspacing="0" width="100%" style="max-width:600px">
		  <tbody>
			 <tr>
				<td style="padding:12px 0">
				   <table cellpadding="0" cellspacing="0" style="width:100%">
					  <tbody>
						 <tr>
							<td class="DISPLAYPICTURE" style="width:200px" width="200">
							   <div style="margin:0 auto;line-height:0;text-align:center;width:160px;height:160px;border-radius:50%;background-color:#009688"> <img width="130px" style="max-width:130px;height:auto;border:3px solid #fff;border-radius:50%;margin-top:13px" src="https://www.connectzap.com.br/img/logo-connectzap.webp"> </div>
							</td>
							<td>
							   <table cellpadding="0" cellspacing="0" style="width:100%">
								  <tbody>
									 <tr>
										<td style="border-bottom:1px solid #a1a4aa;padding-bottom:0">
										   <p style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;font-size:20px;color:#009688;font-weight:700;text-transform:uppercase;margin:0">Jader Berto</p>
										   <p style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;font-size:14px;margin:5px 0 0">Atendimento ao Cliente <span class="seperator">-</span> Connect Zap</p>
										   <span style="position:relative;bottom:-15px;min-height:5px;max-height:5px;width:100px;display:inline-block;background-color:#009688">&nbsp;</span>
										</td>
									 </tr>
									 <tr>
										<td valign="top" style="line-height:1.6;padding-top:16px">
										   <table cellpadding="0" cellspacing="0" style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;font-size:14px;width:100%">
											  <tbody>
												 <tr class="PHONENUMBER">
													<td><b style="color:#009688">Contato</b></td>
													<td style="width:30px;text-align:center">:</td>
													<td>+55 (21) 98158-7295</td>
												 </tr>
												 <tr class="EMAIL">
													<td><b style="color:#009688">Email</b></td>
													<td style="width:30px;text-align:center">:</td>
													<td><a href="mailto:jader@connectzap.com.br" style="text-decoration:none">jader@connectzap.com.br</a></td>
												 </tr>
												 <tr class="WEBSITE">
													<td><b style="color:#009688">Website</b></td>
													<td style="width:30px;text-align:center">:</td>
													<td><a href="www.connectzap.com.br" style="text-decoration:none">www.connectzap.com.br</a></td>
												 </tr>
											  </tbody>
										   </table>
										</td>
									 </tr>
									 <tr>
										<td style="padding-top:8px">
										   <p style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;font-size:14px;margin:0;padding-top:6px;height:30px">
										   <a class="FACEBOOKURL" href="https://www.facebook.com/Connectzap" target="_blank" style="display:inline-block;line-height:0;margin-right:8px"><img alt="Facebook" width="24" style="max-width:24px;height:auto;border:0" src="https://js.zohocdn.com/zmail/toolkit/assets/f365fd888609adb4592a.png"></a>
										   <a class="LINKEDINURL" href="https://www.linkedin.com/company/connectzap" target="_blank" style="display:inline-block;line-height:0;margin-right:8px"><img alt="LinkedIn" width="24" style="max-width:24px;height:auto;border:0" src="https://js.zohocdn.com/zmail/toolkit/assets/44994ddd001121ef78ab.png"></a>
										   <a class="YOUTUBEURL" href="https://www.youtube.com/@Connectzap" target="_blank" style="display:inline-block;line-height:0;margin-right:8px"><img alt="YouTube" width="24" style="max-width:24px;height:auto;border:0" src="https://js.zohocdn.com/zmail/toolkit/assets/bfa7da0565ea269e27e3.png"></a>
										   <a class="INSTAGRAMURL" href="https://www.instagram.com/connectzap" target="_blank" style="display:inline-block;line-height:0;margin-right:8px"><img alt="Instagram" width="24" style="max-width:24px;height:auto;border:0" src="https://js.zohocdn.com/zmail/toolkit/assets/3581a585b3c1ed74caa7.png"></a>
										   </p>
										</td>
									 </tr>
								  </tbody>
							   </table>
							</td>
						 </tr>
					  </tbody>
				   </table>
				</td>
			 </tr>
			 <tr class="DISCLAIMER">
				<td style="border-top:1px solid #a1a4aa;padding-top:8px">
				   <p style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,sans-serif;font-size:12px;color:grey;margin:0;line-height:1.5">O conteúdo deste e-mail é confidencial e destina-se apenas ao destinatário especificado na mensagem. É estritamente proibido compartilhar qualquer parte desta mensagem com terceiros, sem o consentimento por escrito do remetente. Se você recebeu esta mensagem por engano, responda a esta mensagem e prossiga com sua exclusão, para que possamos garantir que tal erro não ocorra no futuro.</p>
				</td>
			 </tr>
		  </tbody>
	   </table>
	</div>
	';
	//
	global $error;
	//
	//$mail = new PHPMailer;
	$mail = new PHPMailer(true);
	//
	try {
		//
		// Configurações do servidor
		//To load the French version
		$mail->setLanguage('br', './phpmailer-6.9.1/language/');
		$mail->isSMTP(); // Define que a mensagem será SMTP
		// 1 = Erros e mensagens
		// 2 = Apenas mensagens
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true; //Habilita a autenticação SMTP
		$mail->Username = 'jader@connectzap.com.br';
		$mail->Password = '@Connectzap100';
		// Criptografia do envio SSL também é aceito
		$mail->SMTPSecure = 'ssl';
		// Informações específicadas pelo Google
		$mail->Host = "smtppro.zoho.com"; // Endereço do servidor SMTP, não altere esse campo.
		$mail->Port = 465;
		// Define o remetente
		//$mail->setFrom('noreply@connectzap.com.br', 'Connect Zap');
		$mail->setFrom('contato@connectzap.com.br', 'Connect Zap');
		// Define o destinatário
		$mail->addAddress($strEmail, $strName); //E-mail e nome de quem irá receber a mensagem
		//
		#$mail->addBCC('contato@connectzap.com.br', 'Token de teste');  //E-mail que irá receber a mensagem com  cópia oculta
		//
		/*
		Utilizando "para/cc/cco" no PHPMailer:
		Para adicionar destinatários ("para"), use $mail->AddAddress():
		$mail->AddAddress('pessoaA@dominio.com', 'Pessoa A');
		$mail->AddAddress('pessoaB@dominio.com', 'Pessoa B');
		...
		Para adicionar "com cópia" ("cc"), use $mail->AddCC():
		$mail->AddCC('pessoaC@dominio.com', 'Pessoa C');
		$mail->AddCC('pessoaD@dominio.com', 'Pessoa D');
		...
		Para adicionar "com cópia oculta" ("cco"), use $mail->AddBCC():
		$mail->AddBCC('pessoaE@dominio.com', 'Pessoa E');
		$mail->AddBCC('pessoaF@dominio.com', 'Pessoa F');
		...
		*/
		// Conteúdo da mensagem
		$mail->isHTML(true); // Seta o formato do e-mail para aceitar conteúdo HTML
		$mail->CharSet = 'UTF-8'; // Charset da mensagem
		$mail->Subject = 'Token de teste Connect Zap'; // Assunto da mensagem
		//
		$mailContent = $avaliacaoEmail;
		$mailContent .= $assinaturaEmail;
		//
		$mail->Body = $mailContent;
		//$mail->AltBody = 'Este é o cortpo da mensagem para clientes de e-mail que não reconhecem HTML';
		// Enviar
		$envio = $mail->send();
		//Limpa os destinatários
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		//
		if ($envio == true) {
			$error = 'Mensage enviada!';
		} else {
			$error = $mail->ErrorInfo;
			echo $mail->ErrorInfo;
		}
		return $envio;
	} catch (Exception $e) {
		echo $e->getMessage();
		$error = "No se puede enviar el mensaje: {$e->getMessage()}";
		return false;
	}
}
//
function WhatsSendText($nome, $celular, $email, $senha, $newtoken)
{
	//$url = 'https://n8n.connectzap.com.br/webhook/sendText';
	$url = APIURL . "/sistema/sendText";

	$corpo = 'Gracias por elegir *Connect Zap* como su administrador de servicios de mensajería.
	
	Durante los próximos *30 días*, podrás probar todas las funciones para ver si el programa satisface tus necesidades. Después de eso puedes cancelar en cualquier momento.
	
	*token de acceso API:* ' . $newtoken . '
	
	*Inicio de sesión de acceso al panel:*
	
	*Acceder iniciar sesión:* ' . $email . '
	*Contraseña:* ' . $senha . '
	
	*Panel de acceso:* https://painel.connectzap.es/login/
	
	*Pedir soporte:* https://api.whatsapp.com/send?phone=5521981587295
	
	*Manual resumido:* https://painel.connectzap.es/tokens/manual.pdf
	
	*Documentación técnica API:* https://documenter.getpostman.com/view/25310515/2s8ZDeSyEm
	';
	//
	$options = [
			"SessionName" => APITOKEN,
			"phonefull" => $celular,
			"msg" => "Olá *" . $nome . "*, " . $corpo
	];
	//
	$jsonData = json_encode($options);
	//
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $jsonData,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	));

	$response = curl_exec($curl);
	$info = curl_getinfo($curl);
	$httpCode = $info["http_code"];
	//
	if (curl_errno($curl)) {
		// Houve um erro na execução da requisição cURL
		$error_msg = curl_error($curl);
		curl_close($curl);
		return false;
		//
	}
	//
	curl_close($curl);
	//
	if ((int) $httpCode === 200 || (int) $httpCode === 201 || (int) $httpCode === 202) {
		//
		return true;
		//
	} else {
		//
		return false;
		//
	}
	//
}
