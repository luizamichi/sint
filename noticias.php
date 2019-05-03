<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$page = max(1, filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT));

	$name = 'noticias.php';
	$title = 'Notícias';

	if(!empty($id)) // FOI INFORMADO UM ID NA URL
		$noticia = sql_read($table='NOTICIAS', $condition='ID=' . (int) base64_decode($id), $unique=true);
	else {
		$pages = ceil(sql_length($table='NOTICIAS', $condition='STATUS = 1') / 24); // QUANTIDADE DE PÁGINAS PARA 24 NOTÍCIAS POR PÁGINA
		$page = min($page, $pages); // EVITA O ACESSO À PÁGINAS INEXISTENTES
		$noticias = sql_read($table='NOTICIAS', $condition='STATUS = 1 ORDER BY ID DESC LIMIT ' . ($page - 1) * 24 . ', 24', $unique=false);
	}

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">
		<div class="col darken-4 green">
			<h1 class="center-align white-text z-depth-1"><?= $noticia['TITULO'] ?? $title ?></h1>
		</div>
<?php
	if(isset($noticia) && !empty($noticia)) { // EXIBE A NOTÍCIA SOLICITADA
		if($noticia['IMAGEM']) { // NOTÍCIA POSSUI UMA IMAGEM CADASTRADA
?>
		<div class="center">
			<img alt="Notícia" class="responsive-img" src="<?= $website . $noticia['IMAGEM'] ?>" width="500"/>
		</div>
<?php
		}
?>
		<div class="flow-text"><?= $noticia['TEXTO'] ?></div>

<?php
	}
	elseif(isset($noticias) && !empty($noticias)) { // HÁ NOTÍCIAS CADASTRADAS
?>
		<div class="row">
<?php
		foreach($noticias as $noticia) { // PERCORRE A LISTA DE NOTÍCIAS
?>
			<div class="col m4 s6">
				<a href="<?= $website ?>noticias.php?id=<?= rtrim(strtr(base64_encode($noticia['ID']), '+/', '-_'), '=') ?>">
					<div class="card small">
						<div class="card-content">
							<span class="black-text card-title"><?= $noticia['TITULO'] ?></span>
							<div class="teal-text"><?= substr(strip_tags($noticia['TEXTO']), 0, 420) ?></div>
						</div>
						<div class="black-text card-action">
							<time datetime="<?= $noticia['DATA'] . ' ' . $noticia['HORA'] ?>"><?= date_format(date_create($noticia['DATA'] . ' ' . $noticia['HORA']), 'd/m/Y - H:i') ?></time>
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
		if(!empty($noticias)) { // AINDA NÃO HÁ NOTÍCIAS CADASTRADAS
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
	require_once('navegador.php');
	require_once('rodape.php');
?>