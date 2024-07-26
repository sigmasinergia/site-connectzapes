document.addEventListener('DOMContentLoaded', function () {
	var form = document.getElementById('avaliacao-form');
	form.addEventListener('submit', function (event) {
		event.preventDefault();
		var isValid = !0;
		var msgSubmit = document.getElementById('msgSubmit');
		msgSubmit.style.display = 'none';
		var txtNome = document.getElementById('txtNome');
		if (txtNome.value.trim() === '') {
			isValid = !1
		}
		var txtDoc = document.getElementById('txtDoc');
		if (txtDoc.value.trim() === '') {
			isValid = !1
		}
		var txtEmail = document.getElementById('txtEmail');
		if (txtEmail.value.trim() === '') {
			isValid = !1
		}
		var txtSenha = document.getElementById('txtSenha');
		if (txtSenha.value.trim() === '') {
			isValid = !1
		}
		var txtConfime = document.getElementById('txtConfime');
		if (txtConfime.value.trim() === '') {
			isValid = !1
		}
		if (txtSenha.value.trim() != txtConfime.value.trim()) {
			submitMSG(!1, "Las contraseñas son diferentes");
			isValid = !1
		}
		var country = document.getElementById('country');
		if (country.value.trim() === '') {
			isValid = !1
		}
		var txtTel = document.getElementById('txtTel');
		if (txtTel.value.trim() === '') {
			isValid = !1
		}
		if (isValid) {
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "avaliacao.php", !0);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			var txtNome = document.getElementById('txtNome');
			var txtDoc = document.getElementById('txtDoc');
			var txtEmail = document.getElementById('txtEmail');
			var txtSenha = document.getElementById('txtSenha');
			var country = document.getElementById('country');
			var txtTel = document.getElementById('txtTel');
			var data = [];
			data.push(encodeURIComponent('txtNome') + '=' + encodeURIComponent(txtNome.value.trim()));
			data.push(encodeURIComponent('txtDoc') + '=' + encodeURIComponent(txtDoc.value.trim()));
			data.push(encodeURIComponent('txtEmail') + '=' + encodeURIComponent(txtEmail.value.trim()));
			data.push(encodeURIComponent('txtSenha') + '=' + encodeURIComponent(txtSenha.value.trim()));
			data.push(encodeURIComponent('country') + '=' + encodeURIComponent(country.value.trim()));
			data.push(encodeURIComponent('txtTel') + '=' + encodeURIComponent(txtTel.value.trim()));
			var formStatus = document.createElement('div');
			formStatus.className = 'form_status text-center';
			formStatus.style.width = '100%';
			formStatus.innerHTML = '<p><i class="fa fa-spinner fa-spin"></i> Enviando...</p>';
			form.insertBefore(formStatus, form.firstChild);
			xhr.onload = function () {
				var formStatusElements = document.getElementsByClassName('form_status');
				if (formStatusElements.length > 0) {
					formStatusElements[0].style.display = 'none'
				}
				if (xhr.status === 200) {
					var response = xhr.responseText;
					if (response === "ok") {
						submitMSG(!0, "¡Registro completado exitosamente!");
						txtNome.value = '';
						txtDoc.value = '';
						txtEmail.value = '';
						txtSenha.value = '';
						txtConfime.value = '';
						country.value = '';
						txtTel.value = ''
					} else {
						submitMSG(!1, response)
					}
				} else {
					submitMSG(!1, "Ocurrio un error. Vuelve a intentarlo más tarde.")
				}
			};
			xhr.send(data.join('&'))
		} else {
			submitMSG(!1, "Por favor llene los campos requeridos.")
		}
	});

	function submitMSG(valid, msg) {
		var msgSubmit = document.getElementById('msgSubmit');
		msgSubmit.style.display = 'block';
		msgSubmit.textContent = msg;
		if (valid) {
			msgSubmit.classList.remove('msg-error');
			msgSubmit.classList.add('msg-success')
		} else {
			msgSubmit.classList.remove('msg-success');
			msgSubmit.classList.add('msg-error')
		}
	}

	// Adiciona evento para validar ao sair do campo
	var inputNome = document.getElementById('txtNome');
	inputNome.addEventListener('blur', function () {
		let validate = validarPreenchimento(inputNome, 'Nombre');
		let btnEnviar = document.getElementById('btnEnviar');
		if (validate) {
			// btnEnviar.disabled = false;
		} else {
			// btnEnviar.disabled = true;
		}
	});

	// Adiciona evento para validar ao sair do campo txtEmail
	var inputEmail = document.getElementById('txtEmail');
	inputEmail.addEventListener('blur', function () {
		let validate = validarPreenchimento(inputEmail, 'E-mail');
	});   

	// Adiciona evento para validar ao sair do campo txtSenha
	var inputSenha = document.getElementById('txtSenha');
	inputSenha.addEventListener('blur', function () {
	let validate = validarPreenchimento(inputSenha, 'Contraseña');
	});   

	// Adiciona evento para validar ao sair do campo txtConfime
	var inputConfirmeSenha = document.getElementById('txtConfime');
	inputConfirmeSenha.addEventListener('blur', function () {
	let validate = validarPreenchimento(inputConfirmeSenha, 'confirmar Contraseña');
	});   

	// Adiciona evento para validar ao sair do campo txtTel
	var inputTel = document.getElementById('txtTel');
	inputTel.addEventListener('blur', function () {
		let validate = validarPreenchimento(inputTel, 'Teléfono');
	});  
	


	// Adiciona evento para mascarar ao digitar
	var inputtxtDoc = document.getElementById('txtDoc');
	
	//inputtxtDoc.addEventListener('input', function () {
		//inputtxtDoc.value = mascaraDocumento(inputtxtDoc.value);
	//});

	// Adiciona evento para validar ao sair do campo
	inputtxtDoc.addEventListener('blur', function () {
		let validate = validarDocumento(inputtxtDoc);
		let btnEnviar = document.getElementById('btnEnviar');
	});

});