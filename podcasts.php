<?php
	chdir("controladores");
	include_once("podcast.php");
	include_once("inserir_acesso.php");
	$i = 3;
	chdir("..");

	$quantidade = 15;
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
	if(isset($_GET["p"]) && is_numeric($_GET["p"]) && floor($_GET["p"]) > 0) {
		$p = max($_GET["p"], 1);
	}
	else {
		$p = 1;
	}
	$tamanho = $podcastDAO->tamanhoAtivo();
	$paginas = max(ceil($tamanho / $quantidade), 1);
	$tuplas = $podcastDAO->listarIntervaloAtivo(($p - 1) * $quantidade, $quantidade);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Podcasts</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de podcasts">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Podcasts">
	<meta property="og:url" content="<?php echo $host . "podcasts.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Podcasts">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "podcasts";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Podcasts</h1>
					<?php if($tamanho == 0) { echo "<p class=\"lead\">AINDA NÃO TEMOS CONTEÚDO DISPONÍVEL :(</p>"; } ?>
				</div>
			</div>

			<?php foreach($tuplas as $tupla) {
				if($i == 3) { ?>
					<div class="row mb-4">
						<div class="col-sm">
							<div class="card-deck">
				<?php $i = 0; } ?>
				<div class="card">
					<div class="card-body">
						<h5 class="card-text"><?php echo $tupla->getTitulo(); ?></h5>
						<p class="card-text"><audio controls style="width: 100%;"><source src="<?php echo $tupla->getAudio(); ?>" type="audio/mpeg"></audio></p>
						<p class="card-text"><small class="text-muted"><?php echo $tupla->getData()->format("d/m/Y H\hi"); ?></small> <a class="badge badge-green" download href="<?php echo $tupla->getAudio(); ?>">DOWNLOAD</a></p>
					</div>
				</div>
			<?php $i++;
				if($i == 3) { ?>
							</div>
						</div>
					</div>
			<?php }}
				if($i != 3) { ?>
							</div>
						</div>
					</div>
			<?php } ?>

			<?php include_once("paginacao.php"); ?>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>