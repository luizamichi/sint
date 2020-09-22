<?php
	include_once("../modelos/Noticia.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isNoticia() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_SESSION["noticiaInserir"])) {
			$noticiaInserir = unserialize($_SESSION["noticiaInserir"]);
		}
		else {
			$noticiaInserir = new Noticia();
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
	<link type="text/css" rel="stylesheet" href="../css/summernote-lite.min.css" id="summernote-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de inserção de notícia">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "noticias";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Adicionar Notícia</h1>
		<div class="col-sm">
			<form action="../controladores/inserir_noticia.php" method="POST" enctype="multipart/form-data" id="inserir_noticia">
				<div class="row form-group">
					<div class="col-sm">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $noticiaInserir->getTitulo() . "\""; ?> required autofocus>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm">
						<label for="inputSubtitulo">Subtítulo</label>
						<input class="form-control" type="text" id="inputSubtitulo" name="inputSubtitulo" minlength="6" <?php echo "value=\"" . $noticiaInserir->getSubtitulo() . "\""; ?>>
					</div>
				</div>

				<div class="row form-group" style="display: none;">
					<div class="col-sm">
						<textarea class="form-control" id="inputTexto" name="inputTexto" minlength="6" rows="10" required></textarea>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm">
						<label>Texto</label>
						<div id="summernote"><?php echo $noticiaInserir->getTexto(); ?></div>
					</div>
				</div>

				<div class="form-group">
					<label for="inputImagem">Imagem</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputImagem" name="inputImagem" accept="image/x-png,image/jpeg" data-max-size="3072" onchange="readURL(this);">
						<label class="custom-file-label" id="labelImagem" for="inputImagem">Escolher imagem</label>
					</div>
				</div>

				<div class="text-center">
					<img class="rounded img-thumbnail" id="image-preview" width="375" alt="Notícia" src="../imagens/noticia.svg" style="display: none;">
				</div>
				<div class="text-center">
					<p class="text-danger remover" id="limpaImagem" style="display: none;">Remover</p>
				</div>

				<button class="btn btn-success btn-block my-4" type="submit">Cadastrar</button>
				<?php
					if(isset($_SESSION["resposta"])) {
						echo "<p>" . $_SESSION["resposta"] . "</p>";
						unset($_SESSION["resposta"]);
					}
					if(isset($_SESSION["noticiaInserir"])) {
						unset($_SESSION["noticiaInserir"]);
					}
				?>
			</form>
		</div>
	</div>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>

	<!-- Script -->
	<script src="../js/summernote-lite.min.js"></script>
	<script src="../js/summernote-pt-BR.min.js"></script>
	<script>
		$(".custom-file-input").on("change", function() {
			var nomeImagem = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(nomeImagem);
		});

		function readURL(input) {
			if(input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#image-preview").attr("src", e.target.result);
					$("#image-preview").attr("style", "display: initial;");
					$("#limpaImagem").attr("style", "display: ;");
				};
				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#limpaImagem").click(function() {
			$("#inputImagem").val("");
			$("#labelImagem").html("Escolher imagem");
			$("#image-preview").attr("style", "display: none;");
			$("#limpaImagem").attr("style", "display: none;");
		});

		// SumerNote
		$(document).ready(function() {
			$("#summernote").summernote({
				height: 250,
				lang: "pt-BR",
			});

			$("button").click(function() {
				var texto = $("#summernote").summernote("code");
				$("#inputTexto").html(texto);
			});
		});
	</script>
</body>

</html>