<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	$id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // ID CODIFICADO EM BASE64
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT)); // NÚMERO DA PÁGINA SOLICITADA

	$name = 'noticias.php';
	$title = 'Notícias';

	if(!empty($id)) { // FOI INFORMADO UM ID NA URL
		$noticia = sqlRead(table: 'NOTICIAS', condition: 'ID = ' . (int) base64_decode($id), unique: true);
		$title = empty($noticia) ? 'Notícias' : 'Notícias - ' . $noticia['TITULO'];
	}
	else {
		$pages = ceil(sqlLength(table: 'NOTICIAS', condition: 'STATUS = 1') / 24); // QUANTIDADE DE PÁGINAS PARA 24 NOTÍCIAS POR PÁGINA
		$page = min($page, $pages); // EVITA O ACESSO ÀS PÁGINAS INEXISTENTES
		$noticias = sqlRead(table: 'NOTICIAS', condition: 'STATUS = 1 ORDER BY DATA DESC, HORA DESC LIMIT ' . ($page - 1) * 24 . ', 24');
	}

	require_once(__DIR__ . '/cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-2"><?= $noticia['TITULO'] ?? $title ?></h1>
		</div>
<?php
	if(isset($noticia) && !empty($noticia)) { // EXIBE A NOTÍCIA SOLICITADA
		if($noticia['IMAGEM']) { // NOTÍCIA POSSUI UMA IMAGEM CADASTRADA
?>
		<div class="center">
			<img alt="Notícia" class="responsive-img" loading="lazy" src="<?= BASE_URL . $noticia['IMAGEM'] ?>" width="500"/>
		</div>
<?php
		}
?>
		<div id="text-content"><?= $noticia['TEXTO'] ?></div>
		<div class="fixed-action-btn">
			<a class="btn-floating btn-large tooltipped" data-id="text-content" data-position="left" data-tooltip="Alterar o tamanho da fonte" id="button-toggle" href="javascript:void(0)">
				<img alt="Alterar o tamanho da fonte" loading="lazy" src="img/fonte.png" style="filter: invert(1); margin: 3px;" width="50"/>
			</a>
		</div>

<?php
	}
	elseif(isset($noticias) && !empty($noticias)) { // HÁ NOTÍCIAS CADASTRADAS
?>
		<div class="row">
<?php
		foreach($noticias as $noticia) { // PERCORRE A LISTA DE NOTÍCIAS
?>
			<div class="col m4 s6">
				<a href="<?= BASE_URL ?>noticias.php?id=<?= rtrim(strtr(base64_encode($noticia['ID']), '+/', '-_'), '=') ?>">
					<div class="card hoverable small">
						<div class="card-content">
							<span class="black-text card-title"><?= $noticia['TITULO'] ?></span>
							<div class="teal-text"><?= substr(strip_tags($noticia['TEXTO']), 0, 420) ?></div>
						</div>
						<div class="black-text card-action">
							<time datetime="<?= substr($noticia['DATA'], 0, 10) . ' ' . substr($noticia['HORA'], 0, 5) ?>"><?= date_format(date_create(substr($noticia['DATA'], 0, 10) . ' ' . $noticia['HORA']), 'd/m/Y - H:i') ?></time>
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
	else {
		if(isset($noticias) && empty($noticias)) { // AINDA NÃO HÁ NOTÍCIAS CADASTRADAS
?>
		<h3 class="center-align">Ainda não temos notícias disponíveis :(</h3>
<?php
		}
		else { // FOI INFORMADO UM ID INVÁLIDO
?>
		<h3 class="center-align">Não foi encontrada a notícia solicitada :(</h3>
<?php
		}
	}
?>
	</div>
<?php
	require_once(__DIR__ . '/navegador.php'); // INSERE O NAVEGADOR DE PÁGINAS
	require_once(__DIR__ . '/rodape.php'); // INSERE O RODAPÉ DA PÁGINA
