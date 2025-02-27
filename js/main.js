document.addEventListener("DOMContentLoaded", function () {
	const $navbarBurgers = Array.prototype.slice.call(
		document.querySelectorAll(".navbar-burger"),
		0
	);
	$navbarBurgers.forEach((el) => {
		el.addEventListener("click", () => {
			const target = el.dataset.target;
			const $target = document.getElementById(target);
			el.classList.toggle("is-active");
			$target.classList.toggle("is-active");
		});
	});
	var collapseButtons = document.querySelectorAll(".collapse-button");
	collapseButtons.forEach(function (button) {
		button.addEventListener("click", function () {
			var targetId = this.getAttribute("data-collapse");
			var target = document.getElementById(targetId);
			if (target) {
				target.classList.toggle("is-hidden");
				var icon = this.querySelector("i");
				icon.classList.toggle("fa-angle-down");
				icon.classList.toggle("fa-angle-up");
			}
		});
	});
	var elFormExterno = document.getElementsByClassName("login-form-externo");
	if (elFormExterno) {
		if (elFormExterno.length > 0) {
			elFormExterno = elFormExterno[0];
		}
		var htmlFormLogin = '<form id="login-form" method="post" accept-charset="utf-8">';
		htmlFormLogin += '		<div class="field">';
		htmlFormLogin += '			<label class="label">Usuario*</label>';
		htmlFormLogin += '			<div class="control">';
		htmlFormLogin += '				<input id="email" class="input bg-success-color-light" type="email" placeholder="Complete su E-mail" required="">';
		htmlFormLogin += "			</div>";
		htmlFormLogin += "		</div>";
		htmlFormLogin += '		<div class="field">';
		htmlFormLogin += '			<label class="label">Contraseña*</label>';
		htmlFormLogin += '			<div class="control">';
		htmlFormLogin += '				<input id="pwd" class="input bg-success-color-light" type="password" placeholder="Complete su contraseña" required="">';
		htmlFormLogin += "			</div>";
		htmlFormLogin += "		</div>";
		htmlFormLogin += '		<div class="control text-center">';
		htmlFormLogin += '			<p><button type="submit" class="button is-success btn-entrar"> <i class="fa-solid fa-paper-plane mr-10"></i> Entrar</button></p>';
		htmlFormLogin += '			<p><a href="https://painel.connectzap.es/login/forgot_password.php" target="_blank" class="button is-link btn-entrar"> <i class="fa-solid fa-key mr-10"></i> Me olvidé</a></p>';
		htmlFormLogin += "		</div>";
		htmlFormLogin += '		<div id="msgSubmitLogin"></div>';
		htmlFormLogin += "	</form>";
		elFormExterno.innerHTML = htmlFormLogin;
	}
	var loginButtons = document.querySelectorAll(".btn-login");
	loginButtons.forEach(function (button) {
		button.addEventListener("click", function () {
			if (elFormExterno) {
				elFormExterno.classList.toggle("is-hidden");
			}
		});
	});
	var formLogin = document.getElementById("login-form");
	formLogin.addEventListener("submit", function (event) {
		event.preventDefault();
		var isValid = !0;
		var msgSubmitLogin = document.getElementById("msgSubmitLogin");
		msgSubmitLogin.style.display = "none";
		var email = document.getElementById("email");
		var senha = document.getElementById("pwd");
		if (email.value.trim() === "") {
			isValid = !1;
		}
		if (senha.value.trim() === "") {
			isValid = !1;
		}
		if (isValid) {
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "https://painel.connectzap.es/login/login.php", !0);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			var data = [];
			data.push(
				encodeURIComponent("email") + "=" + encodeURIComponent(email.value.trim())
			);
			data.push(
				encodeURIComponent("pwd") + "=" + encodeURIComponent(senha.value.trim())
			);
			data.push(encodeURIComponent("send_form") + "=" + encodeURIComponent(""));
			var formStatus = document.createElement("div");
			formStatus.className = "form_status text-center";
			formStatus.style.width = "100%";
			formStatus.innerHTML = '<p><i class="fa fa-spinner fa-spin"></i> Esperar...</p>';
			formLogin.insertBefore(formStatus, formLogin.firstChild);
			xhr.onload = function () {
				var formStatusElements = document.getElementsByClassName("form_status");
				if (formStatusElements.length > 0) {
					formStatusElements[0].style.display = "none";
				}
				if (xhr.status === 200) {
					var data = xhr.responseText;
					//console.log("response data:", data);
					let response = JSON.parse(data);
					//console.log("response.codigo:", response.codigo);
					if (response.codigo && response.codigo !== !1) {
						submitMSGLogin(!0, "¡Éxito!");
						setTimeout(function () {
							if (response.codigo !== !1) {
								window.location.href = "https://painel.connectzap.es/login/redirect.php?redirect=true&iduser=" + response.iduser;
							}
						}, 2000);
					} else {
						console.error("Mensaje de error: " + response.mensagem);
						submitMSGLogin(!1, response.mensagem);
						window.scrollTo(0, 0);
					}
				} else {
					submitMSG(!1, "Ocurrio un error.");
				}
			};
			xhr.send(data.join("&"));
		} else {
			submitMSGLogin(!1, "Por favor llene los campos requeridos.");
		}
	});
	function submitMSGLogin(valid, msg) {
		var msgSubmitLogin = document.getElementById("msgSubmitLogin");
		msgSubmitLogin.style.display = "block";
		msgSubmitLogin.textContent = msg;
		if (valid) {
			msgSubmitLogin.classList.remove("msg-error");
			msgSubmitLogin.classList.add("msg-success");
		} else {
			msgSubmitLogin.classList.remove("msg-success");
			msgSubmitLogin.classList.add("msg-error");
		}
	}
});
