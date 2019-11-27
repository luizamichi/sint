<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$pages = ceil(sqlLength(table: 'DIRETORIA', condition: 'ID < (SELECT COUNT(*) FROM DIRETORIA)')); // QUANTIDADE DE PÁGINAS PARA 1 HISTÓRICO POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
	$historico = sqlRead(table: 'DIRETORIA', condition: 'ID < (SELECT COUNT(*) FROM DIRETORIA) - ' . ($page - 1) . ' ORDER BY ID DESC LIMIT 1', unique: true);

	$name = 'historico.php';
	$title = empty($historico) ? 'Histórico' : 'Histórico - ' . $historico['TITULO'];

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $title ?></h1>
		</div>
<?php
	if(!empty($historico)) { // EXIBE O HISTÓRICO SOLICITADO
?>
		<h4 class="center-align"><?= $historico['TITULO'] ?></h4>
<?php
		if($historico['IMAGEM']) { // HISTÓRICO POSSUI UMA IMAGEM CADASTRADA
?>
		<div class="center">
			<img alt="Histórico" loading="lazy" src="<?= BASE_URL . $historico['IMAGEM'] ?>" width="500"/>
		</div>
<?php
		}
?>
		<div id="text-content"><?= $historico['TEXTO'] ?></div>
		<div class="fixed-action-btn">
			<a class="btn-floating btn-large tooltipped" data-id="text-content" data-position="left" data-tooltip="Alterar o tamanho da fonte" id="button-toggle" href="javascript:void(0)">
				<img alt="Alterar o tamanho da fonte" loading="lazy" src="img/fonte.png" style="filter: invert(1); margin: 3px;" width="50"/>
			</a>
		</div>
<?php
	}
	else { // AINDA NÃO HÁ HISTÓRICOS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos conteúdo disponível :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
