<?php

	// CARREGA TODAS AS CONSTANTES PRÉ-DEFINIDAS
	require_once(__DIR__ . '/sgc/load.php');

	// DESABILITA O ACESSO À PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
	if(count(get_included_files()) <= 1) {
		header('Location: index.php');
		exit;
	}
?>

	<footer class="darken-4 green page-footer">
		<div class="container">
			<div class="row">
				<div class="col l5 s12">
					<h5 class="white-text">QUEM SOMOS?</h5>
					<p class="grey-text text-lighten-4">O Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá é uma instituição com atuação voltada para os interesses dos professores e funcionários de redes de ensino público e privado de Maringá.</p>
					<div class="row">
						<div class="col s2">
							<a href="https://www.facebook.com/sinteemar" target="_blank" title="Siga-nos no Facebook.">
								<img alt="Facebook" loading="lazy" src="<?= BASE_URL ?>img/facebook.svg" width="35"/>
							</a>
						</div>
						<div class="col s2">
							<a href="https://wa.me/554499613561" target="_blank" title="Entre em contato conosco pelo WhatsApp.">
								<img alt="WhatsApp" loading="lazy" src="<?= BASE_URL ?>img/whatsapp.svg" width="35"/>
							</a>
						</div>
						<div class="col s2">
							<a href="https://www.youtube.com/user/sinteemar" target="_blank" title="Acompanhe o nosso canal no YouTube.">
								<img alt="YouTube" loading="lazy" src="<?= BASE_URL ?>img/youtube.svg" width="35"/>
							</a>
						</div>
					</div>
				</div>
				<div class="col l4 offset-l2 s12">
					<h5 class="white-text">CONTATO</h5>
					<ul>
						<li class="grey-text text-lighten-4"><strong>Endereço:</strong> Rua Prof. Itamar Orlando Soares, 357</li>
						<li class="grey-text text-lighten-4"><strong>Bairro:</strong> Jardim Universitário</li>
						<li class="grey-text text-lighten-4"><strong>Cidade:</strong> Maringá, PR</li>
						<li class="grey-text text-lighten-4"><strong>CEP:</strong> 87020-270</li>
						<li class="grey-text text-lighten-4"><strong>Telefone:</strong> (44) 3225-1611</li>
						<li class="grey-text text-lighten-4"><strong>E-mail:</strong> sinteemar@sinteemar.com.br</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
				Copyright &copy; 2017 - <?= date('Y') ?> Sinteemar. Todos os direitos reservados.
				<a class="grey-text right text-lighten-4 tooltipped" data-position="top" data-tooltip="Desenvolvido por Luiz Joaquim Aderaldo Amichi" href="https://luizamichi.com.br" target="_blank">
					<img alt="Luiz Joaquim Aderaldo Amichi" class="mx-2" loading="lazy" src="<?= BASE_URL ?>img/luizamichi.svg" width="30"/>
				</a>
			</div>
		</div>
	</footer>

	<script src="<?= BASE_URL ?>js/jquery.min.js"></script>
	<script src="<?= BASE_URL ?>js/materialize.min.js"></script>
	<script>
		$(document).ready(function() {
			$(".dropdown-trigger").dropdown();
			$(".materialboxed").materialbox();
			$(".sidenav").sidenav();
			$(".modal").modal().modal("open");
			$(".tooltipped").tooltip();
			$(".carousel").carousel({ fullWidth: true });

			setInterval(function() {
				$(".carousel").carousel("next");
			}, 5000);

			$("#button-toggle").click(function() {
				$("#" + $(this).data("id")).toggleClass("flow-text");
				M.toast({ html: "O tamanho da fonte foi alterado" });
			});
		});
	</script>
</body>

</html>
