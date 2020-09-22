<?php
	include_once("../modelos/Convenio.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isConvenio() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_SESSION["convenioInserir"])) {
			$convenioInserir = unserialize($_SESSION["convenioInserir"]);
		}
		else {
			$convenioInserir = new Convenio();
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
	<meta name="description" content="Página de inserção de convênio">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "convenios";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="col-sm mt-3">Adicionar Convênio</h1>
		<div class="col-sm">
		<form action="../controladores/inserir_convenio.php" method="POST" enctype="multipart/form-data" id="inserir_convenio">
				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $convenioInserir->getTitulo() . "\""; ?> required autofocus>
					</div>
					<div class="col-sm-6 form-right">
						<label for="inputCidade">Cidade</label>
						<input class="form-control" type="text" id="inputCidade" name="inputCidade" minlength="6" maxlength="64" <?php echo "value=\"" . $convenioInserir->getCidade() . "\""; ?> required>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputTelefone">Telefone</label>
						<input class="form-control" type="text" id="inputTelefone" name="inputTelefone" minlength="14" maxlength="14" <?php echo "value=\"" . preg_replace("/^([0-9]{2})([0-9]{4})([0-9]{4})$/", "($1) $2-$3", (string) $convenioInserir->getTelefone()) . "\""; ?>>
					</div>
					<div class="col-sm-6">
						<label for="inputCelular">Celular</label>
						<input class="form-control" type="text" id="inputCelular" name="inputCelular" minlength="15" maxlength="15" <?php echo "value=\"" . preg_replace("/^([0-9]{2})([0-9]{5})([0-9]{4})$/", "($1) $2-$3", (string) $convenioInserir->getCelular()) . "\""; ?>>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputSite">Site</label>
						<input class="form-control" type="url" id="inputSite" name="inputSite" minlength="6" maxlength="64" <?php echo "value=\"" . $convenioInserir->getSite() . "\""; ?>>
					</div>
					<div class="col-sm-6">
						<label for="inputEmail">E-mail</label>
						<input class="form-control" type="email" id="inputEmail" name="inputEmail" minlength="6" maxlength="64" <?php echo "value=\"" . $convenioInserir->getEmail() . "\""; ?>>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm">
						<label for="inputDescricao">Descrição</label>
						<textarea class="form-control" id="inputDescricao" name="inputDescricao" minlength="6" rows="5" required><?php echo $convenioInserir->getDescricao(); ?></textarea>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<label for="inputArquivo">Arquivo</label>
						<div class="custom-file">
							<input class="custom-file-input" type="file" id="inputArquivo" name="inputArquivo" accept="application/pdf" data-max-size="3072" onchange="readDocumentURL(this);">
							<label class="custom-file-label" id="labelArquivo" for="inputArquivo">Escolher arquivo</label>
						</div>
					</div>
					<div class="col-sm-6 form-right">
						<label for="inputImagem">Imagem</label>
						<div class="custom-file">
							<input class="custom-file-input" type="file" id="inputImagem" name="inputImagem" accept="image/x-png,image/jpeg" data-max-size="1024" onchange="readImageURL(this);" required>
							<label class="custom-file-label" for="inputImagem">Escolher imagem</label>
						</div>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm-6">
						<div class="embed-responsive embed-responsive-21by9" id="pdf-div" style="display: none;">
							<iframe class="embed-responsive-item" id="pdf-preview" title="PDF"></iframe>
						</div>
						<div class="text-center">
							<p class="text-danger remover" id="limpaArquivo" style="display: none;">Remover</p>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="text-center">
							<img class="rounded img-thumbnail" id="image-preview" width="375" alt="Convênio" src="../imagens/convenio.svg" style="display: none;">
						</div>
					</div>
				</div>

				<button class="btn btn-success btn-block my-4" type="submit">Cadastrar</button>
				<?php
					if(isset($_SESSION["resposta"])) {
						echo "<p>" . $_SESSION["resposta"] . "</p>";
						unset($_SESSION["resposta"]);
					}
					if(isset($_SESSION["convenioInserir"])) {
						unset($_SESSION["convenioInserir"]);
					}
				?>
			</form>
		</div>
	</div>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>

	<!-- Script -->
	<script>
		document.getElementById("inputTelefone").addEventListener("input", function(e) {
			var x = e.target.value.replace(/\D/g, "").match(/(\d{0,2})(\d{0,4})(\d{0,4})/);
			e.target.value = !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
		});

		document.getElementById("inputCelular").addEventListener("input", function(e) {
			var x = e.target.value.replace(/\D/g, "").match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
			e.target.value = !x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : "");
		});

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
					$("#limpaArquivo").attr("style", "display: ;");
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
			}
		}

		$("#limpaArquivo").click(function() {
			$("#inputArquivo").val("");
			$("#labelArquivo").html("Escolher arquivo");
			$("#pdf-div").attr("style", "display: none;");
			$("#limpaArquivo").attr("style", "display: none;");
		});
	</script>
</body>

</html>