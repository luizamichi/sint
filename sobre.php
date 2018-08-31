<?php
	$name = 'sobre.php';
	$title = 'Sobre nós';
	require_once('cabecalho.php');
?>

	<div class="container is-fluid">
		<section class="section">
			<div class="has-background-success has-text-centered my-5 px-3 py-3">
				<h1 class="has-text-white is-1 title"><?= $title ?></h1>
			</div>
			<div class="container content mb-5">
				<p class="is-size-5">O Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá é uma instituição com atuação voltada para os interesses dos professores e funcionários de redes de ensino público e privado de Maringá.</p>
				<div class="columns is-multiline mb">
					<div class="column is-half-desktop is-half-tablet">
						<p class="is-size-5"><strong>Endereço:</strong> Rua Professor Itamar Orlando Soares, 357</p>
						<p class="is-size-5"><strong>Bairro:</strong> Jardim Universitário</p>
						<p class="is-size-5"><strong>Cidade:</strong> Maringá, PR</p>
						<p class="is-size-5"><strong>CEP:</strong> 87.020-270</p>
						<p class="is-size-5"><strong>Telefone:</strong> (44) 3225-1611</p>
						<p class="is-size-5"><strong>E-mail:</strong> sinteemar@sinteemar.com.br</p>
					</div>
					<div class="column is-half-desktop is-half-tablet">
						<figure class="image is-16by9">
							<iframe allowfullscreen class="has-ratio" height="360" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14645.990771935869!2d-51.9424432!3d-23.4063822!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xc5f633ccfa0bdc40!2sSind%20dos%20Trab%20Em%20Estab%20de%20Ensino%20de%20Ensino%20de%20Maring%C3%A1!5e0!3m2!1spt-BR!2sbr!4v1596048009976!5m2!1spt-BR!2sbr" width="640"></iframe>
						</figure>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php
	require_once('rodape.php');
?>