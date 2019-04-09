<?php
	if(count(get_included_files()) <= 1) { // DESABILITA O ACESSO A PÁGINA, PERMITE APENAS POR MEIO DE INCLUSÃO
		header('Location: index.php');
		return false;
	}

	$website = isset($website) ? $website : ''; // VARIÁVEL OBTIDA NA INCLUSÃO
?>
	<footer class="darken-4 green page-footer">
		<div class="container">
			<div class="row">
				<div class="col l5 s12">
					<h5 class="white-text">QUEM SOMOS?</h5>
					<p class="grey-text text-lighten-4">O Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá é uma instituição com atuação voltada para os interesses dos professores e funcionários de redes de ensino público e privado de Maringá.</p>
					<div class="row">
						<div class="col s2">
							<a href="https://www.facebook.com/sinteemar" target="_blank" title="Siga-nos no Facebook."><img src="<?= $website ?>img/facebook.svg" alt="Facebook" width="35"/></a>
						</div>
						<div class="col s2">
							<a href="https://wa.me/554499613561" target="_blank" title="Entre em contato conosco pelo WhatsApp."><img src="<?= $website ?>img/whatsapp.svg" alt="WhatsApp" width="35"/></a>
						</div>
						<div class="col s2">
							<a href="https://www.youtube.com/user/sinteemar" target="_blank" title="Acompanhe o nosso canal no YouTube."><img src="<?= $website ?>img/youtube.svg" alt="YouTube" width="35"/></a>
						</div>
					</div>
				</div>
				<div class="col l4 offset-l2 s12">
					<h5 class="white-text">CONTATO</h5>
					<ul>
						<li class="grey-text text-lighten-4"><b>Endereço:</b> Rua Prof. Itamar Orlando Soares, 357</li>
						<li class="grey-text text-lighten-4"><b>Bairro:</b> Jardim Universitário</li>
						<li class="grey-text text-lighten-4"><b>Cidade:</b> Maringá, PR</li>
						<li class="grey-text text-lighten-4"><b>CEP:</b> 87020-270</li>
						<li class="grey-text text-lighten-4"><b>Telefone:</b> (44) 3225-1611</li>
						<li class="grey-text text-lighten-4"><b>E-mail:</b> sinteemar@sinteemar.com.br</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="footer-copyright">
			<div class="container">
				Copyright &copy; 2017 - <?= date("Y") ?> Sinteemar. Todos os direitos reservados.
				<a class="grey-text right text-lighten-4" href="https://luizamichi.com.br" target="_blank" title="Desenvolvido por Luiz Joaquim Aderaldo Amichi">
					<img alt="Luiz Joaquim Aderaldo Amichi" class="mx-2" src="<?= $website ?>img/luizamichi.svg" width="30"/>
				</a>
			</div>
		</div>
	</footer>

	<script src="<?= $website ?>js/jquery.min.js"></script>
	<script src="<?= $website ?>js/materialize.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".dropdown-trigger").dropdown();
			$(".materialboxed").materialbox();
			$(".sidenav").sidenav();
			$(".carousel").carousel({ fullWidth: true });

			setInterval(function() {
				$(".carousel").carousel("next");
			}, 5000);
		});
	</script>
</body>

</html>