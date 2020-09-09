<?php
	$links = [
		"index" => ["", "class=\"nav-item\"", "class=\"nav-item active\""],
		"banners" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/banner.svg\" alt=\"Banner\">", "<img class=\"branco\" src=\"../imagens/banner.svg\" alt=\"Banner\">"],
		"boletins" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/boletim.svg\" alt=\"Boletim\">", "<img class=\"branco\" src=\"../imagens/boletim.svg\" alt=\"Boletim\">"],
		"convencoes" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/convencao.svg\" alt=\"Convenção\">", "<img class=\"branco\" src=\"../imagens/convencao.svg\" alt=\"Convenção\">"],
		"convenios" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/convenio.svg\" alt=\"Convênio\">", "<img class=\"branco\" src=\"../imagens/convenio.svg\" alt=\"Convênio\">"],
		"diretorios" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/diretorio.svg\" alt=\"Diretório\">", "<img class=\"branco\" src=\"../imagens/diretorio.svg\" alt=\"Diretório\">"],
		"editais" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/edital.svg\" alt=\"Edital\">", "<img class=\"branco\" src=\"../imagens/edital.svg\" alt=\"Edital\">"],
		"eventos" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/evento.svg\" alt=\"Evento\">", "<img class=\"branco\" src=\"../imagens/evento.svg\" alt=\"Evento\">"],
		"financas" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/financas.svg\" alt=\"Finança\">", "<img class=\"branco\" src=\"../imagens/financas.svg\" alt=\"Finança\">"],
		"jornais" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/jornal.svg\" alt=\"Jornal\">", "<img class=\"branco\" src=\"../imagens/jornal.svg\" alt=\"Jornal\">"],
		"juridicos" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/juridico.svg\" alt=\"Jurídico\">", "<img class=\"branco\" src=\"../imagens/juridico.svg\" alt=\"Jurídico\">"],
		"noticias" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/noticia.svg\" alt=\"Notícia\">", "<img class=\"branco\" src=\"../imagens/noticia.svg\" alt=\"Notícia\">"],
		"podcasts" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/podcast.svg\" alt=\"Podcast\">", "<img class=\"branco\" src=\"../imagens/podcast.svg\" alt=\"Podcast\">"],
		"registros" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/registro.svg\" alt=\"Registro\">", "<img class=\"branco\" src=\"../imagens/registro.svg\" alt=\"Registro\">"],
		"tabelas" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/tabela.svg\" alt=\"Tabela\">", "<img class=\"branco\" src=\"../imagens/tabela.svg\" alt=\"Tabela\">"],
		"usuarios" => ["class=\"list-group-item list-group-item-action bg-light\">", "<img src=\"../imagens/usuario.svg\" alt=\"Usuário\">", "<img class=\"branco\" src=\"../imagens/usuario.svg\" alt=\"Usuário\">"]
	];
	$links[$pagina][0] = "class=\"list-group-item list-group-item-action bg-success text-white\">";
	$links[$pagina][1] = $links[$pagina][2];
?>

<div class="d-flex" id="wrapper">
	<!-- Menu Lateral -->
	<div class="bg-light border-right" id="sidebar-wrapper">
		<div class="sidebar-heading">
			<img src="../imagens/logo.svg" alt="Logo" <?php echo "onclick=\"document.getElementById('sidebar-wrapper').style.visibility='hidden'; document.getElementById('sidebar-wrapper').style.width='0%';\""; ?>>
			<strong class="not-selectable">SINTEEMAR</strong>
		</div>

		<div class="list-group list-group-flush">
			<?php
				if($usuarioModelo->getPermissao()->isBanner()) {
					echo "<a href=\"banners.php\" ";
					echo $links["banners"][0];
					echo $links["banners"][1];
					echo "Banners</a>";
				}
				if($usuarioModelo->getPermissao()->isBoletim()) {
					echo "<a href=\"boletins.php\" ";
					echo $links["boletins"][0];
					echo $links["boletins"][1];
					echo "Boletins</a>";
				}
				if($usuarioModelo->getPermissao()->isConvencao()) {
					echo "<a href=\"convencoes.php\" ";
					echo $links["convencoes"][0];
					echo $links["convencoes"][1];
					echo "Convenções</a>";
				}
				if($usuarioModelo->getPermissao()->isConvenio()) {
					echo "<a href=\"convenios.php\" ";
					echo $links["convenios"][0];
					echo $links["convenios"][1];
					echo "Convênios</a>";
				}
				if($usuarioModelo->getPermissao()->isDiretorio()) {
					echo "<a href=\"diretorios.php\" ";
					echo $links["diretorios"][0];
					echo $links["diretorios"][1];
					echo "Diretórios</a>";
				}
				if($usuarioModelo->getPermissao()->isEdital()) {
					echo "<a href=\"editais.php\" ";
					echo $links["editais"][0];
					echo $links["editais"][1];
					echo "Editais</a>";
				}
				if($usuarioModelo->getPermissao()->isEvento()) {
					echo "<a href=\"eventos.php\" ";
					echo $links["eventos"][0];
					echo $links["eventos"][1];
					echo "Eventos</a>";
				}
				if($usuarioModelo->getPermissao()->isFinanca()) {
					echo "<a href=\"financas.php\" ";
					echo $links["financas"][0];
					echo $links["financas"][1];
					echo "Finanças</a>";
				}
				if($usuarioModelo->getPermissao()->isJornal()) {
					echo "<a href=\"jornais.php\" ";
					echo $links["jornais"][0];
					echo $links["jornais"][1];
					echo "Jornais</a>";
				}
				if($usuarioModelo->getPermissao()->isJuridico()) {
					echo "<a href=\"juridicos.php\" ";
					echo $links["juridicos"][0];
					echo $links["juridicos"][1];
					echo "Jurídicos</a>";
				}
				if($usuarioModelo->getPermissao()->isNoticia()) {
					echo "<a href=\"noticias.php\" ";
					echo $links["noticias"][0];
					echo $links["noticias"][1];
					echo "Notícias</a>";
				}
				if($usuarioModelo->getPermissao()->isPodcast()) {
					echo "<a href=\"podcasts.php\" ";
					echo $links["podcasts"][0];
					echo $links["podcasts"][1];
					echo "Podcasts</a>";
				}
				if($usuarioModelo->getPermissao()->isRegistro()) {
					echo "<a href=\"registros.php\" ";
					echo $links["registros"][0];
					echo $links["registros"][1];
					echo "Registros</a>";
				}
				if($usuarioModelo->getPermissao()->isTabela()) {
					echo "<a href=\"tabelas.php\" ";
					echo $links["tabelas"][0];
					echo $links["tabelas"][1];
					echo "Tabelas</a>";
				}
				if($usuarioModelo->getPermissao()->isUsuario()) {
					echo "<a href=\"usuarios.php\" ";
					echo $links["usuarios"][0];
					echo $links["usuarios"][1];
					echo "Usuários</a>";
				}
			?>
		</div>
	</div>

	<!-- Menu Superior -->
	<div id="page-content-wrapper">
		<div class="menu-superior">
			<nav class="navbar navbar-expand-lg navbar-light border-bottom">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarTop">
					<p class="h5 m-1 not-selectable" <?php echo "onclick=\"if(document.getElementById('sidebar-wrapper').style.visibility == 'inherit') { document.body.scrollTop = 0; document.documentElement.scrollTop = 0; } else { document.getElementById('sidebar-wrapper').style.visibility='inherit'; document.getElementById('sidebar-wrapper').style.width=''; }\""; ?>>Bem-vindo <?php echo mb_convert_case($usuarioModelo->getNome(), MB_CASE_TITLE, "UTF-8"); ?>.</p>
					<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
						<li <?php echo $links["index"][1]; ?>>
							<a class="nav-link" href="index.php">Início</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="../controladores/logout.php">Sair</a>
						</li>
					</ul>

					<ul class="navbar-nav ml-auto mt-2 mt-lg-0 mini-menu">
						<?php
							if($usuarioModelo->getPermissao()->isBanner()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=banners.php>Banners</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isBoletim()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=boletins.php>Boletins</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isConvencao()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=convencoes.php>Convenções</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isConvenio()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=convenios.php>Convênios</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isEdital()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=editais.php>Editais</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isDiretorio()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=diretorios.php>Diretórios</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isEvento()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=eventos.php>Eventos</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isFinanca()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=financas.php>Finanças</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isJornal()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=jornais.php>Jornais</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isJuridico()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=juridicos.php>Jurídicos</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isNoticia()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=noticias.php>Notícias</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isPodcast()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=podcasts.php>Podcasts</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isRegistro()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=registros.php>Registros</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isTabela()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=tabelas.php>Tabelas</a>";
								echo "</li>";
							}
							if($usuarioModelo->getPermissao()->isUsuario()) {
								echo "<li class=nav-item>";
								echo "<a class=nav-link href=usuarios.php>Usuários</a>";
								echo "</li>";
							}
						?>
					</ul>
				</div>
			</nav>
		</div>