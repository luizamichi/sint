<?php
	chdir("controladores");
	include_once("convencao.php");
	include_once("inserir_acesso.php");
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
	$tuplas = $convencaoDAO->listarAtivo(30);
	chdir("..");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Convenções</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de convenções">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Convenções">
	<meta property="og:url" content="<?php echo $host . "convencoes.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Convenções">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "convencoes";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Convenções</h1>
					<?php if(empty($tuplas)) { echo "<p class=\"lead\">AINDA NÃO TEMOS CONTEÚDO DISPONÍVEL :(</p>"; } ?>
				</div>
			</div>

			<div class="row mb-3">
				<?php if(!empty($tuplas)) { ?>
				<div class="col-sm text-center">
					<h3 class="h3">Vigentes</h3>
					<ul class="list-group list-group-flush">
						<?php foreach($tuplas as $tupla) {
							if($tupla->isTipo() == 1) { ?>
							<li class="list-group-item text-left">
								<a class="text-left text-dark" href="<?php echo $tupla->getArquivo(); ?>">
									<img class="rounded ml-3 mr-3 icon" src="imagens/pdf.svg" alt="Convenção" width="20">
									<?php echo $tupla->getTitulo(); ?>
								</a>
							</li>
						<?php }} ?>
					</ul>
				</div>
				<div class="col-sm text-center">
					<h3 class="h3">Anteriores</h3>
					<ul class="list-group list-group-flush">
						<?php foreach($tuplas as $tupla) {
							if($tupla->isTipo() == 0) { ?>
							<li class="list-group-item text-left">
								<a class="text-left text-dark" href="<?php echo $tupla->getArquivo(); ?>">
									<img class="rounded ml-3 mr-3 icon" src="imagens/pdf.svg" alt="Convenção" width="20">
									<?php echo $tupla->getTitulo(); ?>
								</a>
							</li>
						<?php }} ?>
					</ul>
				</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>