<?php
	$links = [
		"index" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"boletins" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"convencoes" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"convenios" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"editais" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"diretoria" => ["class=\"dropdown-item submenu\"", "class=\"dropdown-item list-group-item-dark submenu\""],
		"estatuto" => ["class=\"dropdown-item submenu\"", "class=\"dropdown-item list-group-item-dark submenu\""],
		"eventos" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"financas" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"historico" => ["class=\"dropdown-item submenu\"", "class=\"dropdown-item list-group-item-dark submenu\""],
		"institucional" => ["class=\"nav-item dropdown\"", "class=\"nav-item dropdown active\""],
		"jornais" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"juridicos" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"noticias" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"podcasts" => ["class=\"nav-item\"", "class=\"nav-item active\""],
		"videos" => ["class=\"nav-item\"", "class=\"nav-item active\""],
	];
	if(!isset($pagina)) {
		$pagina = "index";
	}
	$links[$pagina][0] = $links[$pagina][1];
	if(isset($subpagina)) {
		$links[$subpagina][0] = $links[$subpagina][1];
	}
?>

<header>
	<nav class="navbar navbar-expand-lg navbar-dark static-top">
		<div class="container">
			<img src="imagens/logo.svg" alt="Logo" width="35" height="35">
			<a class="navbar-brand" href="index.php">SINTEEMAR</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarResponsive">
				<ul class="navbar-nav ml-auto">
					<li <?php echo $links["index"][0]; ?>>
						<a class="nav-link" href="index.php">Início</a>
					</li>
					<li <?php echo $links["boletins"][0]; ?>>
						<a class="nav-link" href="boletins.php">Boletins</a>
					</li>
					<li <?php echo $links["convencoes"][0]; ?>>
						<a class="nav-link" href="convencoes.php">Convenções</a>
					</li>
					<li <?php echo $links["convenios"][0]; ?>>
						<a class="nav-link" href="convenios.php">Convênios</a>
					</li>
					<li <?php echo $links["editais"][0]; ?>>
						<a class="nav-link" href="editais.php">Editais</a>
					</li>
					<li <?php echo $links["eventos"][0]; ?>>
						<a class="nav-link" href="eventos.php">Eventos</a>
					</li>
					<li <?php echo $links["financas"][0]; ?>>
						<a class="nav-link" href="financas.php">Finanças</a>
					</li>
					<li <?php echo $links["institucional"][0]; ?>>
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Institucional</a>
						<div class="dropdown-menu">
							<a <?php echo $links["diretoria"][0]; ?> href="diretoria.php">Diretoria</a>
							<a <?php echo $links["estatuto"][0]; ?> href="estatuto.php">Estatuto</a>
							<a <?php echo $links["historico"][0]; ?> href="historico.php">Histórico</a>
						</div>
					</li>
					<li <?php echo $links["jornais"][0]; ?>>
						<a class="nav-link" href="jornais.php">Jornais</a>
					</li>
					<li <?php echo $links["juridicos"][0]; ?>>
						<a class="nav-link" href="juridicos.php">Jurídicos</a>
					</li>
					<li <?php echo $links["noticias"][0]; ?>>
						<a class="nav-link" href="noticias.php">Notícias</a>
					</li>
					<li <?php echo $links["podcasts"][0]; ?>>
						<a class="nav-link" href="podcasts.php">Podcasts</a>
					</li>
					<li <?php echo $links["videos"][0]; ?>>
						<a class="nav-link" href="videos.php">Vídeos</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>