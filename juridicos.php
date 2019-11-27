<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'juridicos.php';
	$title = 'Jurídicos';

	$pages = ceil(sqlLength('JURIDICOS') / 30); // QUANTIDADE DE PÁGINAS PARA 30 JURÍDICOS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$juridicos = sqlRead(table: 'JURIDICOS', condition: 'ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 30 . ', 30');

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>

<?php
	if(!empty($juridicos)) { // HÁ JURÍDICOS CADASTRADOS
?>
		<div class="collection">
<?php
		foreach($juridicos as $juridico) { // PERCORRE A LISTA DE JURÍDICOS
?>
			<a class="collection-item" href="<?= BASE_URL . $juridico['DOCUMENTO'] ?>">
				<h5 class="black-text"><?= $juridico['TITULO'] ?></h5>
				<p><?= nl2br($juridico['TEXTO']) ?: '' ?></p>
			</a>
<?php
		}
?>
		</div>
<?php
	}
	else { // AINDA NÃO HÁ JURÍDICOS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos conteúdo disponível :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
