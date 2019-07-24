<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'convencoes.php';
	$title = 'Convenções';

	$pages = ceil(sql_length($table='CONVENCOES') / 30); // QUANTIDADE DE PÁGINAS PARA 30 CONVENÇÕES POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$convencoes['VIGENTE'] = sql_read($table='CONVENCOES', $condition='TIPO=1 ORDER BY ID DESC LIMIT ' . ($page - 1) * 15 . ', 15', $unique=false);
	$convencoes['ANTERIOR'] = sql_read($table='CONVENCOES', $condition='TIPO=0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 15 . ', 15', $unique=false);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
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
				<a class="teal-text" href="<?= $website . $convencao['DOCUMENTO'] ?>"><?= $convencao['TITULO'] ?></a>
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
				<a class="teal-text" href="<?= $website . $convencao['DOCUMENTO'] ?>"><?= $convencao['TITULO'] ?></a>
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
	require_once('navegador.php');
	require_once('rodape.php');
?>