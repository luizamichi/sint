<?php
	include_once("../controladores/usuario.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isUsuario() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
			$usuarioAlterar = $usuarioDAO->procurarId($_GET["id"]);
			if(!$usuarioAlterar) {
				header("Location: usuarios.php");
				return FALSE;
			}
			else {
				$usuarioAlterar->limpaSenha();
				$_SESSION["usuarioAlterar"] = serialize($usuarioAlterar);
			}
		}
		else if(isset($_SESSION["usuarioAlterar"])) {
			$usuarioAlterar = unserialize($_SESSION["usuarioAlterar"]);
		}
		else {
			header("Location: usuarios.php");
			return FALSE;
		}
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Sistema de Gerenciamento de Conteúdo</title>
	<link type="image/ico" rel="icon" href="../imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="../css/bootstrap.min.css" id="bootstrap-css">
	<?php if(strcmp($_SESSION["tema"], "Claro") == 0) { ?>
	<link type="text/css" rel="stylesheet" href="../css/sgc.css" id="sgc-css">
	<?php } else { ?>
	<link type="text/css" rel="stylesheet" href="../css/sgc-dark.css" id="sgc-css">
	<?php } ?>
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de alteração de usuário">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "usuarios";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Alterar Usuário</h1>
		<div class="col-sm">
			<form action="../controladores/alterar_usuario.php" method="POST" id="alterar_usuario">
				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputNome">Nome</label>
						<input class="form-control" type="text" id="inputNome" name="inputNome" placeholder="" minlength="6" maxlength="64" <?php echo "value=\"" . mb_convert_case($usuarioAlterar->getNome(), MB_CASE_TITLE, "UTF-8") . "\""; ?> required autofocus>
					</div>
					<div class="col-sm-6 form-right">
						<label for="inputEmail">E-mail</label>
						<input class="form-control" type="email" id="inputEmail" name="inputEmail" placeholder="" minlength="6" maxlength="64" <?php echo "value=\"" . $usuarioAlterar->getEmail() . "\""; ?> required>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputLogin">Login</label>
						<input class="form-control" type="text" id="inputLogin" name="inputLogin" placeholder="" minlength="6" maxlength="64" <?php echo "value=\"" . $usuarioAlterar->getLogin() . "\""; ?> required>
					</div>
					<div class="col-sm-6 form-right">
						<label for="inputSenha">Senha</label>
						<input class="form-control" type="password" id="inputSenha" name="inputSenha" placeholder="" minlength="6" <?php echo "value=\"" . $usuarioAlterar->getSenha() . "\""; ?> required>
						<small id="emailHelp" class="form-text text-muted">* É obrigatório inserir uma nova senha.</small>
					</div>
				</div>

				<label class="col-sm row">Permissões</label>
				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputBanner" name="inputBanner" value="1" <?php echo $usuarioAlterar->getPermissao()->isBanner() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputBanner">Banners</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputBoletim" name="inputBoletim" value="1" <?php echo $usuarioAlterar->getPermissao()->isBoletim() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputBoletim">Boletins</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputConvencao" name="inputConvencao" value="1" <?php echo $usuarioAlterar->getPermissao()->isConvencao() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputConvencao">Convenções</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputConvenio" name="inputConvenio" value="1" <?php echo $usuarioAlterar->getPermissao()->isConvenio() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputConvenio">Convênios</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputEdital" name="inputEdital" value="1" <?php echo $usuarioAlterar->getPermissao()->isEdital() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputEdital">Editais</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputEvento" name="inputEvento" value="1" <?php echo $usuarioAlterar->getPermissao()->isEvento() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputEvento">Eventos</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputFinanca" name="inputFinanca" value="1" <?php echo $usuarioAlterar->getPermissao()->isFinanca() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputFinanca">Finanças</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputJornal" name="inputJornal" value="1" <?php echo $usuarioAlterar->getPermissao()->isJornal() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputJornal">Jornais</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputJuridico" name="inputJuridico" value="1" <?php echo $usuarioAlterar->getPermissao()->isJuridico() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputJuridico">Jurídicos</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputNoticia" name="inputNoticia" value="1" <?php echo $usuarioAlterar->getPermissao()->isNoticia() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputNoticia">Notícias</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputPodcast" name="inputPodcast" value="1" <?php echo $usuarioAlterar->getPermissao()->isPodcast() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputPodcast">Podcasts</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputRegistro" name="inputRegistro" value="1" <?php echo $usuarioAlterar->getPermissao()->isRegistro() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputRegistro">Registros</label>
				</div>

				<div class="form-check form-check-inline mr-3">
					<input class="form-check-input" type="checkbox" id="inputUsuario" name="inputUsuario" value="1" <?php echo $usuarioAlterar->getPermissao()->isUsuario() ? "checked" : ""; ?>>
					<label class="form-check-label" for="inputUsuario">Usuários</label>
				</div>

				<button class="btn btn-success btn-block my-4" type="submit">Alterar</button>
				<?php
					if(isset($_SESSION["resposta"])) {
						echo "<p>" . $_SESSION["resposta"] . "</p>";
						unset($_SESSION["resposta"]);
					}
				?>
			</form>
		</div>
	</div>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>