<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$name = 'diretoria.php';
	$title = 'Diretoria';

	$diretoria = sqlRead(table: 'DIRETORIA', condition: 'ID > 0 ORDER BY ID DESC LIMIT 1', unique: true);

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>
<?php
	if(!empty($diretoria)) { // EXIBE A ÚNICA DIRETORIA CADASTRADA
?>
		<h4 class="center-align"><?= $diretoria['TITULO'] ?></h4>
<?php
		if($diretoria['IMAGEM']) { // DIRETORIA POSSUI UMA IMAGEM CADASTRADA
?>
		<div class="center">
			<img alt="Diretoria" loading="lazy" class="responsive-img" src="<?= BASE_URL . $diretoria['IMAGEM'] ?>" width="500"/>
		</div>

<?php
		}
?>
		<div id="text-content"><?= $diretoria['TEXTO'] ?></div>
		<div class="fixed-action-btn">
			<a class="btn-floating btn-large tooltipped" data-id="text-content" data-position="left" data-tooltip="Alterar o tamanho da fonte" id="button-toggle" href="javascript:void(0)">
				<img alt="Alterar o tamanho da fonte" loading="lazy" src="img/fonte.png" style="filter: invert(1); margin: 3px;" width="50"/>
			</a>
		</div>
<?php
	}
	else { // AINDA NÃO HÁ DIRETORIA CADASTRADA
?>
		<h3 class="center-align">Ainda não temos uma diretoria disponível :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
