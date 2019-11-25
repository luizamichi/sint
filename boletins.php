<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // ID CODIFICADO EM BASE64
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'boletins.php';
	$title = 'Boletins';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$boletim = sqlRead(table: 'BOLETINS', condition: 'ID = ' . (int) base64_decode($id), unique: true);

		if(!empty($boletim)) { // REDIRECIONA PARA O CAMINHO DA IMAGEM
			header('Location: ' . BASE_URL . $boletim['IMAGEM']);
			return true;
		}
	}

	$pages = ceil(sqlLength('BOLETINS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 BOLETINS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$boletins = sqlRead(table: 'BOLETINS', condition: 'ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24');

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>
<?php
	if(!empty($boletins)) { // HÁ BOLETINS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($boletins as $boletim) { // PERCORRE A LISTA DE BOLETINS
?>
			<div class="col m4 s6">
				<a href="<?= BASE_URL . $boletim['IMAGEM'] ?>">
					<div class="card hoverable small">
						<div class="card-image">
							<img alt="Boletim" loading="lazy" src="<?= BASE_URL . $boletim['IMAGEM'] ?>"/>
						</div>
						<div class="card-content">
							<span class="black-text card-title"><?= $boletim['TITULO'] ?></span>
						</div>
					</div>
				</a>
			</div>

<?php
		}
?>
		</div>
<?php
	}
	else { // AINDA NÃO HÁ BOLETINS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos boletins disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
