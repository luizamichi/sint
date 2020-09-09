<?php
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(isset($_SESSION["usuarioModelo"])) {
		header("Location: sgc/index.php");
		return TRUE;
	}
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Autenticação</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/login.css" id="login-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Sistema de autenticação dos usuários">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Autenticação">
	<meta property="og:url" content="<?php echo $host . "sgc.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Autenticação">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body class="text-center">
	<form class="form-signin" action="controladores/login.php" method="POST" id="login">
		<img class="mb-4" src="imagens/login.svg" alt="Login" width="72" height="72">
		<h3 class="h3 mb-3 font-weight-normal">Sistema de Gerenciamento de Conteúdo</h3>
		<input class="form-control" type="text" id="inputLogin" name="inputLogin" placeholder="Login" required autofocus>
		<input class="form-control" type="password" id="inputSenha" name="inputSenha" placeholder="Senha" required>
		<button class="btn btn-lg btn-success btn-block" type="submit">Entrar</button>
		<p class="mt-5 mb-3 text-muted">Copyright &copy; 2017 - 2020 <a class="text-muted" href="https://luizamichi.com.br">Luiz Joaquim Aderaldo Amichi</a>.</p>
	</form>
</body>

</html>