<?php
	$local = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/";
	$server = "https://luizamichi.com.br/sinteemar" . $_SERVER["REQUEST_URI"];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar</title>
	<link type="image/ico" rel="icon" href="favicon.ico">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $local; ?>">
	<meta name="twitter:title" content="Sinteemar">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $local . "card.jpg"; ?>">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:title" content="Sinteemar">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $local . "card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $local . "card.jpg"; ?>">
	<meta property="og:type" content="website">
	<style>
		html, body {
			padding: 0;
			margin: 0;
			overflow: hidden;
		}

		[style*="--aspect-ratio"] > :first-child {
			width: 100%;
		}

		[style*="--aspect-ratio"] > img {
			height: auto;
		}

		@supports (--custom:property) {
			[style*="--aspect-ratio"] {
				position: relative;
			}

			[style*="--aspect-ratio"]::before {
				content: "";
				display: block;
				padding-bottom: calc(100% / (var(--aspect-ratio)));
			}

			[style*="--aspect-ratio"] > :first-child {
				position: absolute;
				top: 0;
				left: 0;
				height: 100%;
			}
		}

		@media (min-width: 1920px) {
			[style*="--aspect-ratio"] {
				height: 100vh;
			}
		}

		@media (max-width: 1920px) {
			[style*="--aspect-ratio"] {
				height: 100vh;
			}
		}
	</style>
</head>

<body>
	<div style="--aspect-ratio: 16/9;">
		<iframe
			title="Sinteemar"
			src="<?php echo $server; ?>"
			width="1600"
			height="900"
			style="border: 0; overflow:hidden; overflow-x:hidden;">
		</iframe>
	</div>
</body>