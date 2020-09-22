<?php
	chdir("controladores");
	include_once("convenio.php");
	include_once("inserir_acesso.php");
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
	$tuplas = $convenioDAO->listarAtivo(33);
	chdir("..");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Convênios</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de convênios">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Convênios">
	<meta property="og:url" content="<?php echo $host . "convenios.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Convênios">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "convenios";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Convênios</h1>
					<?php if(empty($tuplas)) { echo "<p class=\"lead\">AINDA NÃO TEMOS CONTEÚDO DISPONÍVEL :(</p>"; } ?>
				</div>
			</div>

			<div class="card-columns mb-3">
				<?php foreach($tuplas as $tupla) { ?>
					<div class="card">
						<img class="card-img-top" src="<?php echo $tupla->getImagem(); ?>" alt="Convênio">
						<div class="card-body">
							<h5 class="card-title"><?php echo $tupla->getTitulo(); ?></h5>
							<div class="card-text">
								<?php if($tupla->getCidade()) { ?>
									<img src="imagens/localizacao.svg" alt="Cidade" width="20" height="20" style="margin-right: 5px;">
									<?php echo $tupla->getCidade(); ?>
									<br>
								<?php } ?>
								<?php if($tupla->getTelefone()) { ?>
									<img src="imagens/telefone.svg" alt="Telefone" width="20" height="20" style="margin-right: 5px;">
									<?php echo preg_replace("/^([0-9]{2})([0-9]{4})([0-9]{4})$/", "($1) $2-$3", $tupla->getTelefone()); ?>
									<br>
								<?php } ?>
								<?php if($tupla->getCelular()) { ?>
									<img src="imagens/celular.svg" alt="Celular" width="20" height="20" style="margin-right: 5px;">
									<?php echo preg_replace("/^([0-9]{2})([0-9]{5})([0-9]{4})$/", "($1) $2-$3", $tupla->getCelular()); ?>
									<br>
								<?php } ?>
								<?php if($tupla->getEmail()) { ?>
									<img src="imagens/email.svg" alt="E-mail" width="20" height="20" style="margin-right: 5px;">
									<?php echo $tupla->getEmail(); ?>
									<br>
								<?php } ?>
								<?php if($tupla->getSite()) { ?>
									<a href="<?php echo $tupla->getSite(); ?>" title="Website" target="_blank"><img class="preto" src="imagens/web.svg" alt="Website" width="20" height="20" style="margin-right: 5px;"></a>
								<?php } ?>
								<?php if($tupla->getArquivo()) { ?>
									<a href="<?php echo $tupla->getArquivo(); ?>" title="Documento com detalhes"><img class="preto" src="imagens/pdf.svg" alt="Documento PDF" width="20" height="20" style="margin-right: 5px;"></a>
								<?php } ?>
								<?php if($tupla->getSite() || $tupla->getArquivo()) { ?>
									<br>
								<?php } ?>
								<p class="text-justify mt-1"><?php echo $tupla->getDescricao(); ?></p>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>