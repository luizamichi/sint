<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // ID CODIFICADO EM BASE64
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'jornais.php';
	$title = 'Jornais';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$jornal = sqlRead(table: 'JORNAIS', condition: 'ID = ' . (int) base64_decode($id), unique: true);

		if(!empty($jornal)) { // REDIRECIONA PARA O CAMINHO DA IMAGEM
			header('Location: ' . BASE_URL . $jornal['IMAGEM']);
			return true;
		}
	}

	$pages = ceil(sqlLength('JORNAIS') / 24); // QUANTIDADE DE PÁGINAS PARA 24 JORNAIS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$jornais = sqlRead(table: 'JORNAIS', condition: 'ID > 0 ORDER BY EDICAO DESC LIMIT ' . ($page - 1) * 24 . ', 24');

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>
<?php
	if(!empty($jornais)) { // HÁ JORNAIS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($jornais as $jornal) { // PERCORRE A LISTA DE JORNAIS
?>
			<div class="col m4 s6">
				<a href="<?= BASE_URL . $jornal['DOCUMENTO'] ?>">
					<div class="card hoverable small">
						<div class="card-image">
							<img alt="Jornal" loading="lazy" src="<?= BASE_URL . ($jornal['IMAGEM'] ?? 'img/jornal.jpg') ?>"/>
						</div>
						<div class="card-content">
							<span class="black-text card-title"><?= $jornal['TITULO'] ?></span>
							<div class="teal-text"><?= $jornal['EDICAO'] ?>ª edição</div>
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
	else { // AINDA NÃO HÁ JORNAIS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos jornais disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
