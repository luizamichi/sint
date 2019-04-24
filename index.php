<?php
	require_once('sgc/dao.php'); // IMPORTA AS FUNÇÕES DE MANIPULAÇÃO DO BANCO DE DADOS

	$name = 'index.php';
	$title = 'Início';

	$banners = (array) sql_read($table='BANNERS', $condition='ID > 0 ORDER BY ID DESC', $unique=false);
	$boletins = (array) sql_read($table='BOLETINS', $condition='ID > 0 ORDER BY ID DESC LIMIT 6', $unique=false);
	$editais = (array) sql_read($table='EDITAIS', $condition='ID > 0 ORDER BY ID DESC LIMIT 6', $unique=false);
	$eventos = (array) sql_read($table='EVENTOS', $condition='ID > 0 ORDER BY ID DESC LIMIT 3', $unique=false);
	$noticias = (array) sql_read($table='NOTICIAS', $condition='STATUS = 1 ORDER BY ID DESC LIMIT 29', $unique=false);
	$podcasts = (array) sql_read($table='PODCASTS', $condition='ID > 0 ORDER BY ID DESC LIMIT 1', $unique=false);

	$noticias_podcasts = array_merge($noticias, $podcasts);
	usort($noticias_podcasts, function($a, $b) { return (($a["DATA"] . " - " . $a["HORA"]) > ($b["DATA"] . " - " . $b["HORA"])) ? -1 : 1; });
	$tuplas = array_merge($noticias_podcasts, $editais, $boletins, $eventos);

	require_once('cabecalho.php'); // INSERE O CABEÇALHO DA PÁGINA
?>

	<div class="container">

<?php
	if(isset($banners) && !empty($banners)) { // HÁ BANNERS CADASTRADOS
?>
		<div class="carousel carousel-slider" data-indicators="true">
<?php
		foreach($banners as $banner) { // PERCORRE A LISTA DE BANNERS CADASTRADOS
?>
			<a class="carousel-item">
				<img alt="Banner" src="<?= $website . $banner['IMAGEM'] ?>"/>
			</a>
<?php
		}
?>
		</div>

<?php
	}
	if(isset($tuplas) && !empty($tuplas)) { // HÁ BOLETINS, EDITAIS, EVENTOS, NOTÍCIAS OU PODCASTS CADASTRADOS
?>
		<div class="row">
<?php
		foreach($tuplas as $tupla) { // PERCORRE A LISTA DE REGISTROS
			if(array_key_exists('STATUS', $tupla)) // SOMENTE AS NOTÍCIAS POSSUEM STATUS
				$tipo = 'noticias';
			elseif(array_key_exists('IMAGENS', $tupla)) // SOMENTE OS EVENTOS POSSUEM DIRETÓRIO DE IMAGENS
				$tipo = 'eventos';
			elseif(array_key_exists('AUDIO', $tupla)) // SOMENTE OS PODCASTS POSSUEM ÁUDIO
				$tipo = 'podcasts';
			elseif(array_key_exists('TEXTO', $tupla)) // SOMENTE OS EDITAIS E NOTÍCIAS (DESCARTADO ANTERIORMENTE) POSSUEM TEXTO
				$tipo = 'editais';
			else
				$tipo = 'boletins';
?>
			<div class="col m4 s12">
				<a href="<?= $website . $tipo ?>.php?id=<?= rtrim(strtr(base64_encode($tupla['ID']), '+/', '-_'), '=') ?>">
					<div class="card small">
<?php
			if(isset($tupla['IMAGEM']) && !empty($tupla['IMAGEM'])) { // INSERE A IMAGEM DE CABEÇALHO DOS BOLETINS, EDITAIS E NOTÍCIAS
?>
						<div class="card-image">
							<img alt="Imagem" src="<?= $website . $tupla['IMAGEM'] ?>"/>
						</div>
<?php
			}
			elseif(isset($tupla['IMAGENS']) && is_dir($tupla['IMAGENS'])) { // INSERE A PRIMEIRA IMAGEM DOS EVENTOS
				$diretorio = scandir($tupla['IMAGENS']);
?>
						<div class="card-image">
							<img alt="Imagem" src="<?= $website . $tupla['IMAGENS'] . array_slice($diretorio, 2)[0] ?>"/>
						</div>
<?php
			}
			else { // INSERE IMAGENS GENÉRICAS PARA AS NOTÍCIAS OU PODCASTS (OU EVENTOS SEM IMAGEM)
?>
						<div class="card-image">
							<img alt="Imagem" src="<?= (strcmp($tipo, 'noticias') == 0 ? 'img/noticia.jpg' : (strcmp($tipo, 'podcasts') == 0 ? 'img/podcast.jpg' : 'img/sinteemar.jpg')) ?>"/>
						</div>
<?php
			}
?>
						<div class="card-content">
							<span class="black-text card-title"><?= $tupla['TITULO'] ?></span>
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
	else { // AINDA NÃO HÁ BOLETINS, EDITAIS, EVENTOS, NOTÍCIAS OU PODCASTS CADASTRADOS
?>
		<h3 class="center-align">Ainda não temos conteúdo disponível :(</h3>
<?php
	}
?>
	</div>
<?php
	require_once('rodape.php');
?>