<?php
	include_once("../controladores/convencao.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isConvencao() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
			$convencaoAlterar = $convencaoDAO->procurarId($_GET["id"]);
			if(!$convencaoAlterar) {
				header("Location: convencoes.php");
				return FALSE;
			}
			else {
				$_SESSION["convencaoAlterar"] = serialize($convencaoAlterar);
			}
		}
		else if(isset($_SESSION["convencaoAlterar"])) {
			$convencaoAlterar = unserialize($_SESSION["convencaoAlterar"]);
		}
		else {
			header("Location: convencao.php");
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
	<meta name="description" content="Página de alteração de convenção">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "convencoes";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Alterar Convenção</h1>
		<div class="col-sm">
			<form action="../controladores/alterar_convencao.php" method="POST" enctype="multipart/form-data" id="alterar_convencao">
				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $convencaoAlterar->getTitulo() . "\""; ?> required autofocus>
					</div>
					<div class="col-sm-6 form-right">
						<label for="inputTipo">Tipo</label>
						<select class="custom-select" id="inputTipo" name="inputTipo" required>
							<option value="" disabled>Selecione uma opção</option>
							<option value="1" <?php echo $convencaoAlterar->isTipo() ? "selected" : ""; ?>>Vigente</option>
							<option value="0" <?php echo $convencaoAlterar->isTipo() ? "" : "selected"; ?>>Anterior</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="inputArquivo">Arquivo</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputArquivo" name="inputArquivo" accept="application/pdf" data-max-size="3072" onchange="readURL(this);">
						<label class="custom-file-label" for="inputArquivo"><?php echo substr($convencaoAlterar->getArquivo(), 19); ?></label>
					</div>
				</div>

				<div class="embed-responsive embed-responsive-21by9">
					<iframe class="embed-responsive-item" title="PDF" src="<?php echo "../" . $convencaoAlterar->getArquivo(); ?>" id="pdf-preview"></iframe>
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
			var nomeArquivo = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(nomeArquivo);
		});

		function readURL(input) {
			if(input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$("#pdf-preview").attr("src", e.target.result);
					$("#pdf-preview").attr("style", "display: initial;");
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>
</body>

</html>