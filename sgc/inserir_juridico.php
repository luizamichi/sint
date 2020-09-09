<?php
	include_once("../modelos/Juridico.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isJuridico() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_SESSION["juridicoInserir"])) {
			$juridicoInserir = unserialize($_SESSION["juridicoInserir"]);
		}
		else {
			$juridicoInserir = new Juridico();
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
	<meta name="description" content="Página de inserção de jurídico">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "juridicos";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Adicionar Jurídico</h1>
		<div class="col-sm">
			<form action="../controladores/inserir_juridico.php" method="POST" enctype="multipart/form-data" id="inserir_juridico">
				<div class="row form-group">
					<div class="col-sm">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $juridicoInserir->getTitulo() . "\""; ?> required autofocus>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm">
						<label for="inputDescricao">Descrição</label>
						<textarea class="form-control" id="inputDescricao" name="inputDescricao" minlength="6" rows="5"><?php echo $juridicoInserir->getDescricao(); ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="inputArquivo">Arquivo</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputArquivo" name="inputArquivo" accept="application/pdf" data-max-size="3072" onchange="readURL(this);" required>
						<label class="custom-file-label" for="inputArquivo">Escolher arquivo</label>
					</div>
				</div>

				<div class="embed-responsive embed-responsive-21by9" id="pdf-div" style="display: none;">
					<iframe class="embed-responsive-item" id="pdf-preview" title="PDF"></iframe>
				</div>

				<button class="btn btn-success btn-block my-4" type="submit">Cadastrar</button>
				<?php
					if(isset($_SESSION["resposta"])) {
						echo "<p>" . $_SESSION["resposta"] . "</p>";
						unset($_SESSION["resposta"]);
					}
					if(isset($_SESSION["juridicoInserir"])) {
						unset($_SESSION["juridicoInserir"]);
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
					$("#pdf-div").attr("style", "display: ;");
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
	</script>
</body>

</html>