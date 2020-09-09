<?php
	include_once("../controladores/imagem.php");
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
		if($usuarioModelo->getPermissao()->isEvento() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_GET["e"]) && is_numeric($_GET["e"]) && floor($_GET["e"]) > 0) {
			$e = $_GET["e"];
			$imagens = $imagemDAO->procurarEvento($e);
		}
		else {
			header("Location: index.php");
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
	<meta name="description" content="Página de visualização de imagens">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "eventos";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 mb-4 col-sm">Imagens</h1>
		<div class="col-sm">
			<?php foreach($imagens as $tupla) {
				echo "<figure class=\"figure text-center\" style=\"margin: 0;\">";
				echo "<a href=\"../" . $tupla["DIRETORIO"] . "/" . $tupla["IMAGEM"] . "\" title=\"" . $tupla["IMAGEM"] . "\" target=\"_blank\"><img class=\"img-thumbnail img-fluid\" src=\"../" . $tupla["DIRETORIO"] . "/" . $tupla["IMAGEM"] . "\" title=\"" . $tupla["IMAGEM"] . "\" width=\"200\" alt=\"Imagem\"></a>";
				echo "</figure>";
			} ?>
		</div>
	</div>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>