<?php
	chdir("controladores");
	include_once("financa.php");
	include_once("inserir_acesso.php");
	chdir("..");

	$quantidade = 33;
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
	if(isset($_GET["p"]) && is_numeric($_GET["p"]) && floor($_GET["p"]) > 0) {
		$p = max($_GET["p"], 1);
	}
	else {
		$p = 1;
	}
	$tamanho = $financaDAO->tamanhoAtivo();
	$paginas = max(ceil($tamanho / $quantidade), 1);
	$tuplas = $financaDAO->listarIntervaloAtivo(($p - 1) * $quantidade, $quantidade);
	usort($tuplas, "comparar");

	function comparar(Financa $a, Financa $b) {
		return ($a->getFlag() > $b->getFlag()) ? -1 : 1;
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Finanças</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de finanças">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Finanças">
	<meta property="og:url" content="<?php echo $host . "financas.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Finanças">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "financas";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Finanças</h1>
					<?php if($tamanho == 0) { echo "<p class=\"lead\">AINDA NÃO TEMOS CONTEÚDO DISPONÍVEL :(</p>"; } ?>
				</div>
			</div>

			<div class="row mb-3">
				<div class="col-sm text-center">
					<ul class="financa">
						<?php foreach($tuplas as $tupla) { ?>
							<li>
								<a class="text-dark" href="<?php echo $tupla->getArquivo(); ?>">
									<img class="mx-3 icon" src="imagens/pdf.svg" alt="Finança" width="20">
									<?php echo (in_array(substr($tupla->getFlag(), 4), ["03.1", "06.1", "09.1", "12.1"]) ? "<strong>" . $tupla->getPeriodo() . "</strong>" : $tupla->getPeriodo()); ?>
								</a>
								<hr>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>

			<?php include_once("paginacao.php"); ?>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>