<?php
	include_once("../controladores/edital.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isEdital() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
			$editalAlterar = $editalDAO->procurarId($_GET["id"]);
			if(!$editalAlterar) {
				header("Location: editais.php");
				return FALSE;
			}
			else {
				$_SESSION["editalAlterar"] = serialize($editalAlterar);
			}
		}
		else if(isset($_SESSION["editalAlterar"])) {
			$editalAlterar = unserialize($_SESSION["editalAlterar"]);
		}
		else {
			header("Location: edital.php");
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
	<link type="text/css" rel="stylesheet" href="../css/summernote-lite.min.css" id="summernote-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de alteração de edital">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "editais";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Alterar Edital</h1>
		<div class="col-sm">
			<form action="../controladores/alterar_edital.php" method="POST" enctype="multipart/form-data" id="alterar_edital">
				<div class="row form-group">
					<div class="col-sm">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $editalAlterar->getTitulo() . "\""; ?> required autofocus>
					</div>
				</div>

				<div class="row form-group" style="display: none;">
					<div class="col-sm">
						<textarea class="form-control" id="inputDescricao" name="inputDescricao" minlength="6" rows="10"></textarea>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm">
						<label>Descrição</label>
						<div id="summernote"><?php echo $editalAlterar->getDescricao(); ?></div>
					</div>
				</div>

				<div class="form-group">
					<label for="inputImagem">Imagem</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputImagem" name="inputImagem" accept="image/x-png,image/jpeg" data-max-size="3072" onchange="readURL(this);">
						<label class="custom-file-label" for="inputImagem"><?php echo substr($editalAlterar->getImagem(), 16); ?></label>
					</div>
				</div>

				<div class="text-center">
					<img class="rounded img-thumbnail" src="<?php echo "../" . $editalAlterar->getImagem(); ?>" id="image-preview" width="375" alt="Edital">
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
				};
				reader.readAsDataURL(input.files[0]);
			}
		}

		// SumerNote
		$(document).ready(function() {
			$("#summernote").summernote({
				height: 250,
				lang: "pt-BR",
			});

			$("button").click(function() {
				var descricao = $("#summernote").summernote("code");
				$("#inputDescricao").html(descricao);
			});
		});
	</script>
</body>

</html>