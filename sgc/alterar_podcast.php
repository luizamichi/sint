<?php
	include_once("../controladores/podcast.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isPodcast() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
			$podcastAlterar = $podcastDAO->procurarId($_GET["id"]);
			if(!$podcastAlterar) {
				header("Location: podcasts.php");
				return FALSE;
			}
			else {
				$_SESSION["podcastAlterar"] = serialize($podcastAlterar);
			}
		}
		else if(isset($_SESSION["podcastAlterar"])) {
			$podcastAlterar = unserialize($_SESSION["podcastAlterar"]);
		}
		else {
			header("Location: podcast.php");
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
	<meta name="description" content="Página de alteração de podcast">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "podcasts";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Alterar Podcast</h1>
		<div class="col-sm">
			<form action="../controladores/alterar_podcast.php" method="POST" enctype="multipart/form-data" id="alterar_podcast">
				<div class="row form-group">
					<div class="col-sm">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $podcastAlterar->getTitulo() . "\""; ?> required autofocus>
					</div>
				</div>

				<div class="form-group">
					<label for="inputAudio">Áudio</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputAudio" name="inputAudio" accept="audio/mpeg" data-max-size="102400" onchange="readURL(this);">
						<label class="custom-file-label" for="inputAudio"><?php echo substr($podcastAlterar->getAudio(), 17); ?></label>
					</div>
				</div>

				<div class="text-center">
					<audio controls id="audio">
						<source src="<?php echo "../" . $podcastAlterar->getAudio(); ?>" id="audio-preview" type="audio/mpeg">
					</audio>
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

	<!-- Script -->
	<script>
		$(".custom-file-input").on("change", function() {
			var nomeAudio = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(nomeAudio);
		});

		function readURL(input) {
			if(input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#audio")[0].load();
					$("#audio-preview").attr("src", e.target.result);
					$("#audio").attr("style", "display: initial;");
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>
</body>

</html>