<?php
	chdir("controladores");
	include_once("inserir_acesso.php");
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Vídeos</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de vídeos">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Vídeos">
	<meta property="og:url" content="<?php echo $host . "videos.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Vídeos">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "videos";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section class="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Vídeos</h1>
				</div>
			</div>

			<div class="row mb-3">
				<div class="embed-responsive embed-responsive-21by9">
					<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/?listType=user_uploads&list=sinteemar&showinfo=1&theme=light" title="YouTube"></iframe>
				</div>
			</div>
			<p class="text-center">Se inscreva em nosso canal do <a class="text-danger font-weight-bold" href="https://www.youtube.com/sinteemar" target="_blank">YouTube</a> e acompanhe nossas reportagens e novidades em vídeo.</p>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>