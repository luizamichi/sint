<?php
	if(session_status() != PHP_SESSION_ACTIVE) { // ACESSO SOMENTE COM UMA SESSÃO CRIADA COM OS DADOS DO USUÁRIO
		session_name('SINTEEMAR');
		session_start();
	}

	// HTML QUE DEFINE O PLANO DE FUNDO
	$background = '<style>html { background-attachment: fixed; background-color: #1b5e20; background-image: url("../img/sgc.svg"); background-position: center; background-repeat: no-repeat; background-size: 300px 300px; }</style>';

	$logout = filter_input(INPUT_GET, 'logout', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$renew = filter_input(INPUT_GET, 'renew', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	if(isset($_SESSION['user'])) {
		$user = unserialize($_SESSION['user']);

		if($renew == 1) {
			include_once('dao.php');

			$email = $user['EMAIL'];
			$password = $user['SENHA'];

			$user = sql_read($table='USUARIOS', $condition='EMAIL="' . $email . '" AND SENHA="' . $password . '"', $unique=true);
			if(isset($user['EMAIL'], $user['SENHA']) && strcmp($user['EMAIL'], $email) == 0 && strcmp($user['SENHA'], $password) == 0) {
				$_SESSION['user'] = serialize($user);
			}
			else {
				$logout = 1;
			}
		}

		if($logout == 1) {
			session_unset();
			session_destroy();

			echo $background;
			echo '<script>
				alert("Você foi desconectado!");
				window.location.href = "index.php";
				</script>';
		}

		else {
			echo '<script>window.location.href = "panel.php";</script>';
		}

		return true;
	}

	elseif(strcmp($_SERVER['REQUEST_METHOD'], 'POST') == 0) {
		include_once('dao.php');

		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING, FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

		$user = sql_read($table='USUARIOS', $condition='EMAIL="' . anti_injection($email) . '" AND SENHA="' . md5($password) . '"', $unique=true);
		if(isset($user['EMAIL'], $user['SENHA']) && strcmp($user['EMAIL'], $email) == 0 && strcmp($user['SENHA'], md5($password)) == 0) {
			unset($_SESSION['email']);
			$_SESSION['user'] = serialize($user);
			echo $background;
			echo '<script>
				alert("Autenticação efetuada com sucesso!");
				window.location.href = "panel.php";
				</script>';
		}

		else {
			$_SESSION['email'] = $email;
			echo $background;
			echo '<script>
				alert("Não foi possível autenticar-se. Verifique o login e a senha!");
				window.location.href = "index.php";
				</script>';
		}

		return true;
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8"/>
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Sistema de Gerenciamento de Conteúdo - Autenticação</title>

	<link rel="icon" href="../img/sgc.svg"/>
	<link rel="stylesheet" type="text/css" href="../css/materialize.min.css"/>
</head>

<body class="darken-4 green">
	<div class="container">
		<div class="center">
			<h1 class="white-text">Sistema de Gerenciamento de Conteúdo</h1>
			<img alt="SGC" class="responsive-img" src="../img/sgc.svg" width="150"/>
			<br/><br/><br/>
		</div>
		<div class="center">
			<form class="col s12" method="post">
				<div class="row">
					<div class="col input-field m6 s12">
						<input <?= isset($_SESSION['email']) ? '' : 'autofocus="autofocus"' ?> class="validate white-text" id="email" name="email" required="required" type="email" value="<?= $_SESSION['email'] ?? '' ?>"/>
						<label for="email">E-mail</label>
					</div>
					<div class="col input-field m6 s12">
					<input <?= isset($_SESSION['email']) ? 'autofocus="autofocus"' : '' ?> class="validate white-text" id="password" name="password" required="required" type="password"/>
						<label for="password">Senha</label>
					</div>
				</div>
				<div class="col input-field m1 s5">
					<button class="btn darken-3 green waves-effect white-text" type="submit">Entrar</button>
				</div>
			</form>
		</div>
	</div>

	<script src="../js/jquery.min.js"></script>
	<script src="../js/materialize.min.js"></script>
	<script>
		$(document).ready(function() {
			$("form").submit(function() {
				$("button").attr("type", "button");
				$("input").prop("readonly", true);
			});
		});
	</script>
</body>

</html>