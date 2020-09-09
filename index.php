<?php
	chdir("controladores");
	include_once("inserir_acesso.php");
	include_once("banner.php");
	include_once("boletim.php");
	include_once("edital.php");
	include_once("evento.php");
	include_once("jornal.php");
	include_once("noticia.php");
	include_once("podcast.php");

	$i = 3;
	$quantidade = 45;
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";

	$banners = $bannerDAO->listarAtivo(5);
	$boletins = $boletimDAO->listarAtivo(15);
	$editais = $editalDAO->listarAtivo(15);
	$eventos = $eventoDAO->listarAtivo(3);
	$jornais = $jornalDAO->listarAtivo(5);
	$noticias = $noticiaDAO->listarAtivo(30);
	$podcasts = $podcastDAO->listarAtivo(3);
	chdir("..");

	$tuplas = array_merge($boletins, $editais, $eventos, $jornais, $noticias, $podcasts);
	$tamanho = count($tuplas);
	usort($tuplas, "comparar");
	$tuplas = array_slice($tuplas, 0, $quantidade);

	function comparar(mixed $a, mixed $b) {
		return ($a->getData() > $b->getData()) ? -1 : 1;
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Início</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página inicial">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Início">
	<meta property="og:url" content="<?php echo $host . "index.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Início">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "index";
		include_once("cabecalho.php");
	?>

	<!-- Banner -->
	<section id="slideshow">
		<h1 style="display: none;">Banner</h1>
		<div class="container">
			<?php if(count($banners) > 0) { ?>
				<div id="carouselIndicators" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<?php for($i = 0; $i < count($banners); $i++) {
							if($i == 0) { ?>
								<li data-target="#carouselIndicators" data-slide-to="<?php echo $i; ?>" class="active"></li>
							<?php }
							else { ?>
								<li data-target="#carouselIndicators" data-slide-to="<?php echo $i; ?>"></li>
						<?php }} ?>
					</ol>
					<div class="carousel-inner">
						<?php foreach($banners as $i => $banner) {
							if($i == 0) { ?>
								<div class="carousel-item active">
							<?php }
							else { ?>
								<div class="carousel-item">
							<?php } ?>
								<img class="d-block w-100" src="<?php echo $banner->getImagem(); ?>" alt="Banner">
							</div>
						<?php } ?>
					</div>
					<a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Anterior</span>
					</a>
					<a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Próximo</span>
					</a>
				</div>
			<?php } ?>
		</div>
	</section>

	<!-- Boletins, Editais, Jornais e Notícias -->
	<section id="content">
		<div class="container">
			<?php if($tamanho == 0) { ?>
				<div class="row">
					<div class="col-lg-12 text-center mt-4 mb-3">
						<p class="lead">AINDA NÃO TEMOS CONTEÚDO DISPONÍVEL :(</p>
					</div>
				</div>
			<?php } $i = 3; ?>
			<?php foreach($tuplas as $tupla) {
				if($i == 3) { ?>
					<div class="row my-4">
						<div class="col-sm">
							<div class="card-deck">
				<?php $i = 0; }
					$tipo = "";
					if(strcmp(get_class($tupla), "Boletim") == 0 || strcmp(get_class($tupla), "Jornal") == 0) {
						$tipo = $tupla->getArquivo();
					}
					else if(strcmp(get_class($tupla), "Edital") == 0) {
						$tipo = "editais.php?id=" . $tupla->getId();
					}
					else if(strcmp(get_class($tupla), "Evento") == 0) {
						$tipo = "eventos.php?id=" . $tupla->getId();
					}
					else if(strcmp(get_class($tupla), "Noticia") == 0) {
						$tipo = "noticias.php?id=" . $tupla->getId();
					}
					else if(strcmp(get_class($tupla), "Podcast") == 0) {
						$tipo = $tupla->getAudio();
					}
				?>
				<div class="card" onclick="<?php echo "window.location='" . $tipo . "'"; ?>">
					<?php if(!in_array("hashAudio", get_class_methods($tupla)) && $tupla->getImagem()) { ?>
						<img class="card-img-top preview" src="<?php echo $tupla->getImagem(); ?>" alt="<?php echo get_class($tupla); ?>">
					<?php } else {
						if(strcmp(get_class($tupla), "Boletim") == 0) { ?>
							<img class="card-img-top preview" src="uploads/boletim.svg" alt="Boletim">
						<?php } else if(strcmp(get_class($tupla), "Jornal") == 0) { ?>
							<img class="card-img-top preview" src="uploads/jornal.svg" alt="Jornal">
						<?php } else if(strcmp(get_class($tupla), "Noticia") == 0) { ?>
							<img class="card-img-top preview" src="uploads/noticia.svg" alt="Notícia">
						<?php } else if(strcmp(get_class($tupla), "Podcast") == 0) { ?>
							<img class="card-img-top preview" src="uploads/podcast.svg" alt="Podcast">
						<?php } ?>
					<?php } ?>
					<div class="card-body">
						<h5 class="card-title nao-selecionavel"><?php echo $tupla->getTitulo(); ?></h5>
						<div class="card-text">
							<?php if(strcmp(get_class($tupla), "Noticia") == 0) { ?>
								<p class="descricao nao-selecionavel"><?php echo strip_tags($tupla->getTexto()); ?></p>
							<?php } ?>
							<small class="text-muted">
								<time class="nao-selecionavel" datetime=<?php echo $tupla->getData()->format("Y-m-d\Th:i:s") . ">" . $tupla->getData()->format("d/m/Y H\hi"); ?></time>
								<?php if(strcmp(get_class($tupla), "Boletim") == 0) { ?>
									- <strong>Boletim</strong>
								<?php } else if(strcmp(get_class($tupla), "Edital") == 0) { ?>
									- <strong>Edital</strong>
								<?php } else if(strcmp(get_class($tupla), "Evento") == 0) { ?>
									- <strong>Evento</strong>
								<?php } else if(strcmp(get_class($tupla), "Jornal") == 0) { ?>
									- <strong>Jornal</strong>
								<?php } else if(strcmp(get_class($tupla), "Noticia") == 0) { ?>
									- <strong>Notícia</strong>
								<?php } else if(strcmp(get_class($tupla), "Podcast") == 0) { ?>
									- <strong>Podcast</strong>
								<?php } ?>
							</small>
						</div>
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
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>