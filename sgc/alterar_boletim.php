<?php
	include_once("../controladores/boletim.php");
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

		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
			$boletimAlterar = $boletimDAO->procurarId($_GET["id"]);
			if(!$boletimAlterar) {
				header("Location: boletins.php");
				return FALSE;
			}
			else {
				$_SESSION["boletimAlterar"] = serialize($boletimAlterar);
			}
		}
		else if(isset($_SESSION["boletimAlterar"])) {
			$boletimAlterar = unserialize($_SESSION["boletimAlterar"]);
		}
		else {
			header("Location: boletins.php");
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
	<meta name="description" content="Página de alteração de boletim">
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
		<h1 class="col-sm mt-3">Alterar Boletim</h1>
		<div class="col-sm">
			<form action="../controladores/alterar_boletim.php" method="POST" enctype="multipart/form-data" id="alterar_boletim">
				<div class="row form-group">
					<div class="col-sm">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $boletimAlterar->getTitulo() . "\""; ?> required autofocus>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputArquivo">Arquivo</label>
						<div class="custom-file">
							<input class="custom-file-input" type="file" id="inputArquivo" name="inputArquivo" accept="application/pdf" data-max-size="3072" onchange="readDocumentURL(this);">
							<label class="custom-file-label" for="inputArquivo"><?php echo substr($boletimAlterar->getArquivo(), 17); ?></label>
						</div>
					</div>
					<div class="col-sm-6 form-right">
						<label for="inputImagem">Imagem</label>
						<div class="custom-file">
							<input class="custom-file-input" type="file" id="inputImagem" name="inputImagem" accept="image/x-png,image/jpeg" data-max-size="1024" onchange="readImageURL(this);">
							<label class="custom-file-label" id="labelImagem" for="inputImagem"><?php echo ($boletimAlterar->getImagem()) ? substr($boletimAlterar->getImagem(), 17) : "Escolher imagem"; ?></label>
						</div>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<div class="embed-responsive embed-responsive-21by9">
							<iframe class="embed-responsive-item" title="PDF" src="<?php echo "../" . $boletimAlterar->getArquivo(); ?>" id="pdf-preview"></iframe>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="text-center">
							<img class="rounded img-thumbnail" src="<?php if($boletimAlterar->getImagem()) { echo "../" . $boletimAlterar->getImagem() . "\""; } else { echo "../imagens/boletim.svg\""; } ?>" id="image-preview" width="375" alt="Boletim" <?php if(!$boletimAlterar->getImagem()) { echo "style=\"display: none;\""; } ?>>
						</div>
						<div class="text-center">
							<p class="text-danger remover" id="limpaImagem" <?php if(!$boletimAlterar->getImagem()) { echo "style=\"display: none;\""; } ?>>Remover</p>
							<input type="hidden" id="inputLimpaImagem" name="inputLimpaImagem">
						</div>
					</div>
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
				};
				reader.readAsDataURL(input.files[0]);
				$("#inputLimpaImagem").val("");
			}
		}

		$("#limpaImagem").click(function() {
			$("#inputImagem").val("");
			$("#labelImagem").html("Escolher imagem");
			$("#image-preview").attr("style", "display: none;");
			$("#limpaImagem").attr("style", "display: none;");
			$("#inputLimpaImagem").val("1");
		});
	</script>
</body>

</html>