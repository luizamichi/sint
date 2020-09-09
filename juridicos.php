<?php
	chdir("controladores");
	include_once("juridico.php");
	include_once("inserir_acesso.php");
	chdir("..");

	$quantidade = 30;
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
	if(isset($_GET["p"]) && is_numeric($_GET["p"]) && floor($_GET["p"]) > 0) {
		$p = max($_GET["p"], 1);
	}
	else {
		$p = 1;
	}
	$tamanho = $juridicoDAO->tamanhoAtivo();
	$paginas = max(ceil($tamanho / $quantidade), 1);
	$tuplas = $juridicoDAO->listarIntervaloAtivo(($p - 1) * $quantidade, $quantidade);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Jurídicos</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de assuntos jurídicos">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Jurídicos">
	<meta property="og:url" content="<?php echo $host . "juridicos.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Jurídicos">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "juridicos";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Jurídicos</h1>
					<?php if($tamanho == 0) { echo "<p class=\"lead\">AINDA NÃO TEMOS CONTEÚDO DISPONÍVEL :(</p>"; } ?>
				</div>
			</div>

			<div class="row">
				<div class="col-sm text-center">
					<ul class="list-group list-group-flush">
					<?php for($i = 0; $i < ceil(count($tuplas) / 2); $i++) { ?>
						<li class="list-group-item text-left">
							<a class="text-dark" href="<?php echo $tuplas[$i]->getArquivo(); ?>">
								<img class="rounded mx-3 icon" src="imagens/pdf.svg" alt="Jurídico" width="20">
								<h5 style="display: inline;"><?php echo $tuplas[$i]->getTitulo(); ?></h5>
							</a>
							<?php if($tuplas[$i]->getDescricao()) { ?>
								<p class="mx-3 text-justify"><small><?php echo $tuplas[$i]->getDescricao(); ?></small></p>
							<?php } ?>
						</li>
					<?php } ?>
					</ul>
				</div>

				<div class="col-sm text-center">
					<ul class="list-group list-group-flush">
					<?php for($i = ceil(count($tuplas) / 2); $i < ceil(count($tuplas)); $i++) { ?>
						<li class="list-group-item text-left">
							<a class="text-dark" href="<?php echo $tuplas[$i]->getArquivo(); ?>">
								<img class="rounded mx-3 icon" src="imagens/pdf.svg" alt="Jurídico" width="20">
								<h5 style="display: inline;"><?php echo $tuplas[$i]->getTitulo(); ?></h5>
							</a>
							<?php if($tuplas[$i]->getDescricao()) { ?>
								<p class="mx-3 text-justify"><small><?php echo $tuplas[$i]->getDescricao(); ?></small></p>
							<?php } ?>
						</li>
					<?php } ?>
					</ul>
				</div>
			</div>
			<hr>

			<div class="row">
				<div class="col-lg-12 text-justify">
					<p>&emsp;&emsp;Os professores e funcionários que são filiados ao Sinteemar contam com uma equipe de advogados altamente especializados para mover ações trabalhistas individuais ou coletivas.</p>
					<p>&emsp;&emsp;Se você precisa de um advogado para esclarecer dúvida trabalhista, o Sinteemar dispõe de atendimento presencial, feito de segunda a sexta, das 8h30min às 17h30min. Para abrir processos, é preciso marcar horário pelo telefone (44) 3225-1611.</p>
				</div>
			</div>

			<?php include_once("paginacao.php"); ?>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>