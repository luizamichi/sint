<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/load.php');

	// VERIFICA SE O USUÁRIO ESTÁ AUTENTICADO
	if(LOGGED_USER) {
		include_once(__DIR__ . '/panel.php');
		return true;
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8"/>
	<meta content="Luiz Joaquim Aderaldo Amichi" name="author"/>
	<meta content="<?= SYSTEM_DESCRIPTION ?>" name="description"/>
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<title><?= SYSTEM_TITLE ?> - Autenticação</title>

	<link href="<?= BASE_URL ?>img/sgc.svg" rel="icon" type="image/svg+xml"/>
	<link href="<?= BASE_URL ?>css/materialize.min.css" rel="stylesheet" type="text/css"/>
</head>

<body class="darken-4 green">
	<div class="container">
		<div class="center">
			<h1 class="white-text"><?= SYSTEM_TITLE ?></h1>
			<img alt="SGC" class="responsive-img" id="image" loading="lazy" src="<?= BASE_URL ?>img/sgc.svg" width="150"/>
			<br/><br/><br/>
		</div>

		<div class="center">
			<form class="col s12" method="post">
				<div class="row">
					<div class="col input-field m6 s12" id="input-email">
						<input autofocus="autofocus" class="validate white-text" id="email" name="email" required="required" type="email"/>
						<label for="email">E-mail</label>
					</div>

					<div class="progress" id="progress" style="display: none;">
						<div class="indeterminate"></div>
					</div>

					<div class="col input-field m6 s12" id="input-password">
					<input class="validate white-text" id="password" name="password" required="required" type="password"/>
						<label for="password">Senha</label>
					</div>
				</div>

				<div class="col input-field m1 s5">
					<button class="btn darken-3 green waves-effect white-text" type="submit">Entrar</button>
				</div>
			</form>
		</div>
	</div>

	<div class="modal">
		<div class="modal-content">
			<h4 class="center-align">Autenticação</h4>
			<p id="message">Resposta inesperada</p>
		</div>
		<div class="modal-footer">
			<button class="btn-flat modal-close waves-effect waves-green" id="button">Fechar</button>
		</div>
	</div>

	<script src="<?= BASE_URL ?>js/jquery.min.js"></script>
	<script src="<?= BASE_URL ?>js/materialize.min.js"></script>
	<script>
		$(document).ready(function() {
			$("form").submit(function(event) {
				event.preventDefault();
				$.ajax({
					type: "post",
					url: "<?= BASE_URL ?>sgc/login.php",
					data: $("form").serialize(),
					dataType: "json",
					beforeSend: function() {
						$("button").attr({"type": "button", "disabled": true});
						$("input").prop("readonly", true);
						$("#input-email, #input-password").hide();
						$("#progress").show();
					},
					success: function(response) {
						$("#message").text(response.message ?? "Você será redirecionado.");
						$("#button").click(function() {
							window.location.reload();
						});
						setTimeout(function() {
							window.location.reload();
						}, 3000);
					},
					error: function(response) {
						$("#message").text(response.responseJSON && response.responseJSON.message);
					},
					complete: function() {
						$("button").attr({"type": "input", "disabled": false});
						$("input").prop("readonly", false);
						$("#progress").hide();
						$("#input-email, #input-password").show();
						$(".modal").modal().modal("open");
					}
				});
			});
		});
	</script>
</body>

</html>
