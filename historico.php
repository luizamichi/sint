<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'historico.php';
	$title = 'Histórico';

	$pages = ceil(sql_length($table='DIRETORIA', $condition='ID < (SELECT COUNT(*) FROM DIRETORIA)')); // QUANTIDADE DE PÁGINAS PARA 1 HISTÓRICO POR PÁGINA
	$page = min($page, $pages); // EVITA O ACESSO À PÁGINAS INEXISTENTES
	$historico = sql_read($table='DIRETORIA', $condition='ID < (SELECT COUNT(*) FROM DIRETORIA) - ' . ($page - 1) . ' ORDER BY ID DESC LIMIT 1', $unique=true);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-1"><?= $title ?></h1>
		</div>
<?php
	if(isset($historico) && !empty($historico)) { // EXIBE O HISTÓRICO SOLICITADO
?>
		<h4 class="center-align"><?= $historico['TITULO'] ?></h4>
<?php
		if($historico['IMAGEM']) { // HISTÓRICO POSSUI UMA IMAGEM CADASTRADA
?>
		<div class="center">
			<img alt="Histórico" src="<?= $website . $historico['IMAGEM'] ?>" width="500"/>
		</div>
<?php
		}
?>
		<div><?= $historico['TEXTO'] ?></div>
<?php
	}
	else { // AINDA NÃO HÁ HISTÓRICO CADASTRADOS
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