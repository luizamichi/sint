<?php
	include_once("../controladores/evento.php");
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

		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
			$eventoAlterar = $eventoDAO->procurarId($_GET["id"]);
			if(!$eventoAlterar) {
				header("Location: eventos.php");
				return FALSE;
			}
			else {
				$_SESSION["eventoAlterar"] = serialize($eventoAlterar);
			}
		}
		else if(isset($_SESSION["eventoAlterar"])) {
			$eventoAlterar = unserialize($_SESSION["eventoAlterar"]);
		}
		else {
			header("Location: evento.php");
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
	<meta name="description" content="Página de alteração de evento">
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
		<h1 class="mt-3 col-sm">Alterar Evento</h1>
		<div class="col-sm">
			<form action="../controladores/alterar_evento.php" method="POST" enctype="multipart/form-data" id="alterar_evento">
				<div class="row form-group">
					<div class="col-sm">
						<label for="inputTitulo">Título</label>
						<input class="form-control" type="text" id="inputTitulo" name="inputTitulo" minlength="6" maxlength="128" <?php echo "value=\"" . $eventoAlterar->getTitulo() . "\""; ?> required autofocus>
					</div>
				</div>

				<div class="row form-group">
					<div class="col-sm">
						<label for="inputDescricao">Descrição</label>
						<textarea class="form-control" id="inputDescricao" name="inputDescricao" minlength="6" rows="5"><?php echo $eventoAlterar->getDescricao(); ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="inputImagens">Imagens</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputImagens" name="inputImagens[]" accept="image/x-png,image/jpeg" onchange="readURL(this);" multiple>
						<label class="custom-file-label" for="inputImagens"><?php echo count($eventoAlterar->getImagens()) . (count($eventoAlterar->getImagens()) > 1 ? " imagens enviadas" : " imagem enviada"); ?></label>
					</div>
				</div>

				<div class="text-center" id="image-preview"></div>

				<div class="text-center" id="images-uploaded">
					<?php foreach($eventoAlterar->getImagens() as $imagem) { ?>
						<img class="rounded img-thumbnail m-1" src="<?php echo "../" . $eventoAlterar->getDiretorio() . "/" . $imagem["IMAGEM"]; ?>" width="225" alt="Evento">
					<?php } ?>
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
			readURL(this, "#image-preview");
			var nomeImagem = $(this)[0].files.length;
			if(nomeImagem == 1) {
				nomeImagem += " nova imagem selecionada";
			}
			else {
				nomeImagem += " novas imagens selecionadas";
			}
			$("#image-preview").empty();
			$("#images-uploaded").prepend("<hr>");
			$(this).siblings(".custom-file-label").addClass("selected").html(nomeImagem);
		});

		function readURL(input, placeToInsertImagePreview) {
			if(input.files && input.files[0]) {
				for(i = 0; i < input.files.length; i++) {
					var reader = new FileReader();
					reader.onload = function(e) {
						$($.parseHTML("<img>")).attr({"src": e.target.result, "class": "rounded img-thumbnail m-1", "width": "225", "alt": "Evento"}).appendTo(placeToInsertImagePreview);
					}
					reader.readAsDataURL(input.files[i]);
				}
			}
		};
	</script>
</body>

</html>