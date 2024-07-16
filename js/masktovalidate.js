function mascaraDocumento(v) {
	// Remove tudo o que não é dígito
	v = v.replace(/\D/g, "");

	if (v.length <= 9) { // RG
		// Coloca um ponto entre o segundo e o terceiro dígitos
		v = v.replace(/(\d{2})(\d)/, "$1.$2");

		// Coloca um ponto entre o quinto e o sexto dígitos
		v = v.replace(/(\d{3})(\d)/, "$1.$2");

		// Coloca um hífen entre o oitavo e o nono dígitos
		v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
	} else if (v.length <= 11) { // CPF
		// Coloca um ponto entre o terceiro e o quarto dígitos
		v = v.replace(/(\d{3})(\d)/, "$1.$2");

		// Coloca um ponto entre o terceiro e o quarto dígitos
		// de novo (para o segundo bloco de números)
		v = v.replace(/(\d{3})(\d)/, "$1.$2");

		// Coloca um hífen entre o terceiro e o quarto dígitos
		v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

	} else { // CNPJ
		// Coloca ponto entre o segundo e o terceiro dígitos
		v = v.replace(/^(\d{2})(\d)/, "$1.$2");

		// Coloca ponto entre o quinto e o sexto dígitos
		v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");

		// Coloca uma barra entre o oitavo e o nono dígitos
		v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");

		// Coloca um hífen depois do bloco de quatro dígitos
		v = v.replace(/(\d{4})(\d)/, "$1-$2");
	}

	return v;
}

function validarCPF(cpf) {
	if (/^(\d)\1{10}$/.test(cpf)) return false;

	var soma = 0;
	for (var i = 0; i < 9; i++) {
		soma += parseInt(cpf.charAt(i)) * (10 - i);
	}
	var resto = 11 - (soma % 11);
	if (resto === 10 || resto === 11) resto = 0;
	if (resto !== parseInt(cpf.charAt(9))) return false;

	soma = 0;
	for (var i = 0; i < 10; i++) {
		soma += parseInt(cpf.charAt(i)) * (11 - i);
	}
	resto = 11 - (soma % 11);
	if (resto === 10 || resto === 11) resto = 0;
	if (resto !== parseInt(cpf.charAt(10))) return false;

	return true;
}

function validarCNPJ(cnpj) {
	if (/^(\d)\1{13}$/.test(cnpj)) return false;

	var tamanho = cnpj.length - 2;
	var numeros = cnpj.substring(0, tamanho);
	var digitos = cnpj.substring(tamanho);
	var soma = 0;
	var pos = tamanho - 7;

	for (var i = tamanho; i >= 1; i--) {
		soma += numeros.charAt(tamanho - i) * pos--;
		if (pos < 2) pos = 9;
	}
	var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	if (resultado !== parseInt(digitos.charAt(0))) return false;

	tamanho++;
	numeros = cnpj.substring(0, tamanho);
	soma = 0;
	pos = tamanho - 7;

	for (var i = tamanho; i >= 1; i--) {
		soma += numeros.charAt(tamanho - i) * pos--;
		if (pos < 2) pos = 9;
	}
	resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	if (resultado !== parseInt(digitos.charAt(1))) return false;

	return true;
}

function exibirErro(mensagem, input) {
	var msgDiv = document.getElementById("msg");
	msgDiv.innerHTML = mensagem;
	msgDiv.style.color = "red";
	input.style.border = "solid 1px red";
}

function exibirSucesso(input) {
	var msgDiv = document.getElementById("msg");
	msgDiv.innerHTML = "";
	input.style.border = "";
}

function validarPreenchimento(input, nome) {
	var regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s.'-]+$/;
	if (input.value.trim() === "") {
		exibirErro(nome + " ¡no puede estar vacío!", input);
		return false;
	} 
	//else if (!regex.test(input.value)) {
	//	exibirErro(nome + " inválido! Utilize apenas letras, espaços, pontos, apóstrofos e hífens.", input);
	//	return false;
	//}
	exibirSucesso(input);
	return true;
}


function validarDocumento(input) {
	documento = input.value.replace(/\D/g, "");

	if (input.value.trim() === "") {
		//linha abaixo era CNPJ/CPF
		exibirErro("¡El documento no puede estar vacío!", input);
		return false;
	} else if (documento.length <= 11) {
		// Validação de CPF
		if (!validarCPF(documento)) {
			exibirErro("¡Documento no válido!", input);
			return false;
		}
	} else if (documento.length >= 14) {
		// Validação de CNPJ
		if (!validarCNPJ(documento)) {
			exibirErro("¡Documento no válido!", input);
			return false;
		}
	} else {
		exibirErro("¡Documento no válido!", input);
		return false;
	}

	exibirSucesso(input);
	return true;
}
