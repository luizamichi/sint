<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$name = 'estatuto.php';
	$title = 'Estatuto';

	$estatuto = sqlRead(table: 'ESTATUTO', condition: 'ID > 0 ORDER BY ID DESC LIMIT 1', unique: true);

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>

<?php
	if(!empty($estatuto)) { // EXIBE O ÚLTIMO ESTATUTO CADASTRADO
?>
		<div class="center">
			<a href="<?= BASE_URL . $estatuto['DOCUMENTO'] ?>">
				<img alt="Estatuto" class="responsive-img" loading="lazy" src="<?= BASE_URL ?>img/document.svg" width="75"/>
			</a>
		</div>
		<p class="center-align">Clique no documento para visualizar.</p>
<?php
	}
	else { // AINDA NÃO HÁ ESTATUTO CADASTRADO
?>
		<h3 class="center-align">Ainda não temos um estatuto disponível :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
