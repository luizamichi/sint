<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // ID CODIFICADO EM BASE64
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'convencoes.php';
	$title = 'Convenções';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$convencao = sqlRead(table: 'CONVENCOES', condition: 'ID = ' . (int) base64_decode($id), unique: true);

		if(!empty($convencao)) { // REDIRECIONA PARA O CAMINHO DO DOCUMENTO
			header('Location: ' . BASE_URL . $convencao['DOCUMENTO']);
			return true;
		}
	}

	$pages = ceil(sqlLength('CONVENCOES') / 30); // QUANTIDADE DE PÁGINAS PARA 30 CONVENÇÕES POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$convencoes['VIGENTE'] = sqlRead(table: 'CONVENCOES', condition: 'TIPO = 1 ORDER BY ID DESC LIMIT ' . ($page - 1) * 15 . ', 15');
	$convencoes['ANTERIOR'] = sqlRead(table: 'CONVENCOES', condition: 'TIPO = 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 15 . ', 15');

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>

<?php
	if(!empty($convencoes['VIGENTE'])) { // HÁ CONVENÇÕES VIGENTES CADASTRADAS
?>
		<h3 class="center-align">Vigentes</h3>
		<ul class="collection">
<?php
		foreach($convencoes['VIGENTE'] as $convencao) { // PERCORRE A LISTA DE CONVENÇÕES VIGENTES CADASTRADAS
?>
			<li class="collection-item">
				<a class="teal-text" href="<?= BASE_URL . $convencao['DOCUMENTO'] ?>"><?= $convencao['TITULO'] ?></a>
			</li>
<?php
		}
	}
	if(!empty($convencoes['ANTERIOR'])) { // HÁ CONVENÇÕES ANTERIORES CADASTRADAS
?>
		</ul>

		<h3 class="center-align">Anteriores</h3>
		<ul class="collection">
<?php
		foreach($convencoes['ANTERIOR'] as $convencao) { // PERCORRE A LISTA DE CONVENÇÕES ANTERIORES CADASTRADAS
?>
			<li class="collection-item">
				<a class="teal-text" href="<?= BASE_URL . $convencao['DOCUMENTO'] ?>"><?= $convencao['TITULO'] ?></a>
			</li>
<?php
		}
?>
		</ul>
<?php
	}
	else { // AINDA NÃO HÁ CONVENÇÕES CADASTRADAS
?>
		<h3 class="center-align">Ainda não temos convenções disponíveis :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
