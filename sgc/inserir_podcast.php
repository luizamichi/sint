<?php
	include_once("../modelos/Podcast.php");
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

		if(isset($_SESSION["podcastInserir"])) {
			$podcastInserir = unserialize($_SESSION["podcastInserir"]);
		}
		else {
			$podcastInserir = new Podcast();
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
	<meta name="description" content="Página de inserção de podcast">
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
		<h1 class="mt-3 col-sm">Adicionar Podcast</h1>
		<div class="col-sm">
			<form action="../controladores/inserir_podcast.php" method="POST" enctype="multipart/form-data" id="inserir_podcast">
				<div class="row form-group">
					<div class="col-sm">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $podcastInserir->getTitulo() . "\""; ?> required autofocus>
					</div>
				</div>

				<div class="form-group">
					<label for="inputAudio">Áudio</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputAudio" name="inputAudio" accept="audio/mpeg" data-max-size="102400" onchange="readURL(this);" required>
						<label class="custom-file-label" for="inputAudio">Escolher áudio</label>
					</div>
				</div>

				<div class="text-center">
					<audio controls id="audio" style="display: none;">
						<source id="audio-preview" src="../audios/sinteemar.mp3" type="audio/mpeg">
					</audio>
				</div>

				<button class="btn btn-success btn-block my-4" type="submit">Cadastrar</button>
				<?php
					if(isset($_SESSION["resposta"])) {
						echo "<p>" . $_SESSION["resposta"] . "</p>";
						unset($_SESSION["resposta"]);
					}
					if(isset($_SESSION["podcastInserir"])) {
						unset($_SESSION["podcastInserir"]);
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