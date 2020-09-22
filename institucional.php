<?php
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Institucional</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de institucional">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Institucional">
	<meta property="og:url" content="<?php echo $host . "institucional.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Institucional">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "institucional";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Institucional</h1>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item" aria-current="page">
								<a class="text-dark" href="diretoria.php">
									<img alt="Diretoria" src="imagens/diretoria.svg" width="20">
									Diretoria
								</a>
							</li>
						</ol>
					</nav>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item" aria-current="page">
								<a class="text-dark" href="estatuto.php">
									<img alt="Estatuto" src="imagens/estatuto.svg" width="20">
									Estatuto
								</a>
							</li>
						</ol>
					</nav>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item" aria-current="page">
								<a class="text-dark" href="historico.php">
									<img alt="Histórico" src="imagens/historico.svg" width="20">
									Histórico
								</a>
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>