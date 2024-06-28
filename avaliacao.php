<?php
//
ini_set("display_errors", 1);
ini_set("log_errors", 1);
ini_set("error_log", dirname(__FILE__) . "/error_log.txt");
error_reporting(E_ALL);
//
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	//
	$mensagem = "Ocorreu um erro ao efetuar cadastro.";
	print $mensagem;
	exit;
	//
}
//
require_once './_email_avaliacao.php';
require_once './inc/Bcrypt.php';
require_once './inc/GenerateRandomStrings.php';
require_once './inc/Functions.php';
require_once './config.php';
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
//
function gerarNovaFatura($data_fatura_anterior) {
	// Converte a data da fatura anterior para o formato timestamp
	$timestamp_fatura_anterior = strtotime($data_fatura_anterior);

	// Adiciona mais 10 dias para obter a data de vencimento da nova fatura
	$data_vencimento_nova = date('Y-m-d', strtotime('+30 days', $timestamp_fatura_anterior));

	// Verifica se o dia da nova fatura é maior que o último dia do mês correspondente
	$ultimo_dia_mes = date('t', strtotime($data_vencimento_nova));
	if (date('d', strtotime($data_vencimento_nova)) > $ultimo_dia_mes) {
		// Se sim, ajusta a data para o último dia do mês correspondente
		$data_vencimento_nova = date('Y-m-', strtotime($data_vencimento_nova)) . $ultimo_dia_mes;
	}

	return $data_vencimento_nova;
}
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
//
$perfil = 'usr';
$active = true;
//
$datainicial = date('Y-m-d');
//$datafinal = date("Y-m-d", strtotime($datainicial . " +30 day"));
$datafinal = gerarNovaFatura($datainicial);
//
$created = date('Y-m-d H:i:s');
$modified = date('Y-m-d H:i:s');
//
//-------------------------------------------------------------------------------------------------------------------------------------------------------------
//
try {
	//
	$strName = trim($_POST['txtNome']);
	$strDocumento = trim($_POST['txtDoc']);
	$strEmail = trim($_POST['txtEmail']);
	$strSenha = trim($_POST['txtSenha']);
	$strPais = trim($_POST['country']);
	$srTelefone = trim($_POST['txtTel']);
	$phoneFull = $strPais.$srTelefone;
	$newtoken = generate_string('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 20);
	$hashedPassword = Bcrypt::hashPassword($strSenha);
	//
	// Montar o corpo do e-mail.
	$mensagem = "Nome: ".$strName."\r\n";
	$mensagem .= "Documento: ".$strDocumento."\r\n";
	$mensagem .= "E-mail: ".$strEmail."\r\n";
	$mensagem .= "Senha: ".$strSenha."\r\n";
	$mensagem .= "Hash: ".$hashedPassword."\r\n";
	$mensagem .= "País: ".$strPais."\r\n";
	$mensagem .= "Telefone: ".$phoneFull."\r\n";
	$mensagem .= "Token: ".$newtoken."\r\n";
	$mensagem .= "\r\n";
	//
	/*
	$file = "./email-logs.txt";
	file_put_contents($file, $mensagem, FILE_APPEND);
	*/
	//
	$Data = Data::conectar(Conexao::conectar());
	//
	$createCpfcnpj = $Data->getCountUsuariosCpfcnpj($strDocumento);
	//
	if ($createCpfcnpj >= 1) {
		//
		$mensagem = "Ocorreu um erro ao efetuar cadastro, usuário já se encontra cadastado.";
		print $mensagem;
		exit;
		//
	}
	//
	$createEmail = $Data->getCountUsuariosEmail($strEmail);
	//
	if ($createEmail >= 1) {
		//
		$mensagem = "Ocorreu um erro ao efetuar cadastro, e-mail já se encontra cadastado.";
		print $mensagem;
		exit;
		//
	}
	//
	$cnpj = preg_replace('/[^0-9]/', '', $strDocumento);
	//
	if (strlen($cnpj) > 11) {
		//
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://www.receitaws.com.br/v1/cnpj/' . $cnpj,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_POSTFIELDS => '{}',
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
			//
			$createTokenTeste = $Data->createTokenTeste($newtoken, $strName, $strDocumento, $strEmail, $strSenha, $hashedPassword, formatarTelefone($phoneFull), $perfil, $active, $datainicial, $datafinal, $created, $modified);
			//
		}
		//
		curl_close($curl);
		//
		if ((int) $httpCode === 200 || (int) $httpCode === 201 || (int) $httpCode === 202) {
				$data = json_decode($response, false);
				$cep = preg_replace('/[^0-9]/', '', $data->cep);
				$cep_formatado = substr_replace($cep, '-', 5, 0);
				$createTokenTeste = $Data->createTokenTesteCnpj($newtoken, $data->nome, $strDocumento, $cep_formatado, $data->uf, $data->municipio, $data->logradouro, $data->numero, $data->bairro, $data->complemento, $strEmail, $strSenha, $hashedPassword, formatarTelefone($phoneFull), $perfil, $active, $datainicial, $datafinal, $created, $modified);
				//
		} else {
			//
			$createTokenTeste = $Data->createTokenTeste($newtoken, $strName, $strDocumento, $strEmail, $strSenha, $hashedPassword, formatarTelefone($phoneFull), $perfil, $active, $datainicial, $datafinal, $created, $modified);
			//
		}
		//
		//
	} else {
		//
		$createTokenTeste = $Data->createTokenTeste($newtoken, $strName, $strDocumento, $strEmail, $strSenha, $hashedPassword, formatarTelefone($phoneFull), $perfil, $active, $datainicial, $datafinal, $created, $modified);
		//
	}
	if ($createTokenTeste) {
		//
		$envioEmail = enviarEmail($strName, $strEmail, $strSenha, $newtoken);
		$enviawhats = WhatsSendText($strName, formatarTelefone($phoneFull), $strEmail, $strSenha, $newtoken);
		//
		$mensagem = "ok";
		print $mensagem;
		exit; 
		//
	} else {
		//
		$mensagem = "Ocorreu um erro ao efetuar cadastro.";
		print $mensagem;
		exit;
		//
	}
	//
}catch(Exception $ex) {
	//
	$mensagem = $ex->getMessage();
	print $mensagem;
	exit;
	//
}
//
?>