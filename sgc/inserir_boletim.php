<?php
	include_once("../modelos/Boletim.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isBoletim() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_SESSION["boletimInserir"])) {
			$boletimInserir = unserialize($_SESSION["boletimInserir"]);
		}
		else {
			$boletimInserir = new Boletim();
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
	<meta name="description" content="Página de inserção de boletim">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "boletins";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="col-sm mt-3">Adicionar Boletim</h1>
		<div class="col-sm">
			<form action="../controladores/inserir_boletim.php" method="POST" enctype="multipart/form-data" id="inserir_boletim">
				<div class="row form-group">
					<div class="col-sm">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $boletimInserir->getTitulo() . "\""; ?> required autofocus>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputArquivo">Arquivo</label>
						<div class="custom-file">
							<input class="custom-file-input" type="file" id="inputArquivo" name="inputArquivo" accept="application/pdf" data-max-size="3072" onchange="readDocumentURL(this);" required>
							<label class="custom-file-label" for="inputArquivo">Escolher arquivo</label>
						</div>
					</div>
					<div class="col-sm-6 form-right">
						<label for="inputImagem">Imagem</label>
						<div class="custom-file">
							<input class="custom-file-input" type="file" id="inputImagem" name="inputImagem" accept="image/x-png,image/jpeg" data-max-size="1024" onchange="readImageURL(this);">
							<label class="custom-file-label" id="labelImagem" for="inputImagem">Escolher imagem</label>
						</div>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<div class="embed-responsive embed-responsive-21by9" id="pdf-div" style="display: none;">
							<iframe class="embed-responsive-item" id="pdf-preview" title="PDF"></iframe>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="text-center">
							<img class="rounded img-thumbnail" id="image-preview" width="375" alt="Boletim" src="../imagens/boletim.svg" style="display: none;">
						</div>
						<div class="text-center">
							<p class="text-danger remover" id="limpaImagem" style="display: none;">Remover</p>
						</div>
					</div>
				</div>

				<button class="btn btn-success btn-block my-4" type="submit">Cadastrar</button>
				<?php
					if(isset($_SESSION["resposta"])) {
						echo "<p>" . $_SESSION["resposta"] . "</p>";
						unset($_SESSION["resposta"]);
					}
					if(isset($_SESSION["boletimInserir"])) {
						unset($_SESSION["boletimInserir"]);
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
			var nomeArquivo = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(nomeArquivo);
		});

		function readDocumentURL(input) {
			if(input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#pdf-preview").attr("src", e.target.result);
					$("#pdf-preview").attr("style", "display: initial;");
					$("#pdf-div").attr("style", "display: ;");
				};
				reader.readAsDataURL(input.files[0]);
			}
		}

		function readImageURL(input) {
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
	</script>
</body>

</html>