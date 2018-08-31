<?php
	if(session_status() !== PHP_SESSION_ACTIVE)
		session_start();
	$logout = filter_input(INPUT_GET, 'logout', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	if(isset($_SESSION['user'])) {
		if(isset($logout) && $logout == 1) {
			session_unset();
			session_destroy();
			echo '<style>html{ background-attachment: fixed; background-color:#00d1b2; background-image:url("../img/sgc.svg"); background-position: center; background-repeat: no-repeat; background-size: 300px 300px; }</style>';
			echo '<script>
				alert("Logout efetuado com sucesso!");
				window.location.href = "index.php";
				</script>';
		}
		else
			echo '<script>window.location.href = "panel.php";</script>';
	}
	elseif(strcmp($_SERVER['REQUEST_METHOD'], 'POST') == 0) {
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING, FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
		include_once('dao.php');
		$user = sql_read($table='USUARIOS', $condition='EMAIL="' . $email . '" AND SENHA="' . md5($password) . '"', $unique=true);
		if(strcmp($user['EMAIL'], $email) == 0 && strcmp($user['SENHA'], md5($password)) == 0) {
			unset($_SESSION['email']);
			$_SESSION['user'] = serialize($user);
			echo '<style>html{ background-attachment: fixed; background-color:#00d1b2; background-image:url("../img/sgc.svg"); background-position: center; background-repeat: no-repeat; background-size: 300px 300px; }</style>';
			echo '<script>
				alert("Login efetuado com sucesso!");
				window.location.href = "panel.php";
				</script>';
			return true;
		}
		else {
			$_SESSION['email'] = $email;
			echo '<style>html{ background-attachment: fixed; background-color:#00d1b2; background-image:url("../img/sgc.svg"); background-position: center; background-repeat: no-repeat; background-size: 300px 300px; }</style>';
			echo '<script>
				alert("Não foi possível entrar. Verifique o login ou a senha!");
				window.location.href = "index.php";
				</script>';
		}
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<link rel="icon" href="../img/sgc.svg"/>
	<link rel="stylesheet" type="text/css" href="../css/bulma.min.css"/>
	<meta charset="utf-8"/>
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Sistema de Gerenciamento de Conteúdo - Login</title>
</head>

<body>
	<section class="hero is-fullheight is-primary">
		<div class="hero-body">
			<div class="container">
				<h1 class="has-text-centered is-1 title">Sistema de Gerenciamento de Conteúdo</h1>
				<div class="columns is-centered">
					<div class="column is-3-widescreen is-4-desktop is-5-tablet">
						<img alt="Sistema de Gerenciamento de Conteúdo" class="has-text-centered mb-5" height="150" src="../img/sgc.svg" width="150"/>
						<form class="box" method="post">
							<div class="field">
								<label class="label" for="email">E-mail</label>
								<div class="control">
									<input <?= isset($_SESSION['email']) ? '' : 'autofocus' ?> class="input" id="email" name="email" required type="email" value="<?= isset($_SESSION['email']) ? $_SESSION['email'] : '' ?>"/>
								</div>
							</div>
							<div class="field">
								<label class="label" for="password">Senha</label>
								<div class="control">
									<input <?= isset($_SESSION['email']) ? 'autofocus' : '' ?> class="input" id="password" name="password" required type="password"/>
								</div>
							</div>
							<div class="field has-text-centered">
								<button class="button is-primary">Entrar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>

</html>