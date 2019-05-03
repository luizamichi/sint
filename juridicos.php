<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'juridicos.php';
	$title = 'Jurídicos';

	$pages = ceil(sql_length($table='JURIDICOS') / 30); // QUANTIDADE DE PÁGINAS PARA 24 JURÍDICOS POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO À PÁGINAS INEXISTENTES
	$juridicos = sql_read($table='JURIDICOS', $condition='ID > 0 ORDER BY ID DESC LIMIT ' . ($page - 1) * 30 . ', 30', $unique=false);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-1"><?= $title ?></h1>
		</div>

<?php
	if(isset($juridicos) && !empty($juridicos)) { // HÁ JURÍDICOS CADASTRADOS
?>
		<div class="collection">
<?php
		foreach($juridicos as $juridico) { // PERCORRE A LISTA DE JURÍDICOS
?>
			<a class="collection-item" href="<?= $website . $juridico['DOCUMENTO'] ?>">
				<h5 class="black-text"><?= $juridico['TITULO'] ?></h5>
				<p><?= $juridico['TEXTO'] ?: '' ?></p>
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
	require_once('navegador.php');
	require_once('rodape.php');
?>