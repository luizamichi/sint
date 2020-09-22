<?php
	include_once("../controladores/banner.php");
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if(!isset($_SESSION["usuarioModelo"])) {
		header("Location: ../erro.html");
		return FALSE;
	}
	else {
		$usuarioModelo = unserialize($_SESSION["usuarioModelo"]);
		if($usuarioModelo->getPermissao()->isBanner() == FALSE) {
			header("Location: index.php");
			return FALSE;
		}

		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && floor($_GET["id"]) > 0) {
			$bannerAlterar = $bannerDAO->procurarId($_GET["id"]);
			if(!$bannerAlterar) {
				header("Location: banners.php");
				return FALSE;
			}
			else {
				$_SESSION["bannerAlterar"] = serialize($bannerAlterar);
			}
		}
		else if(isset($_SESSION["bannerAlterar"])) {
			$bannerAlterar = unserialize($_SESSION["bannerAlterar"]);
		}
		else {
			header("Location: banners.php");
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
	<meta name="description" content="Página de alteração de banner">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
	<!-- Menu Lateral e Superior -->
	<?php
		$pagina = "banners";
		include_once("menu.php");
	?>

	<!-- Conteúdo da Página -->
	<div class="container-fluid">
		<h1 class="mt-3 col-sm">Alterar Banner</h1>
		<div class="col-sm">
			<form action="../controladores/alterar_banner.php" method="POST" enctype="multipart/form-data" id="alterar_banner">
				<div class="form-group">
					<label for="inputImagem">Imagem</label>
					<div class="custom-file">
						<input class="custom-file-input" type="file" id="inputImagem" name="inputImagem" accept="image/x-png,image/jpeg" data-max-size="1024" onchange="readURL(this);" required>
						<label class="custom-file-label" for="inputImagem"><?php echo substr($bannerAlterar->getImagem(), 16); ?></label>
					</div>
				</div>

				<div class="text-center">
					<img class="rounded img-thumbnail" src="<?php echo "../" . $bannerAlterar->getImagem(); ?>" id="image-preview" width="750" alt="Banner">
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
	</script>
</body>

</html>