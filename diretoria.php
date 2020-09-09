<?php
	chdir("controladores");
	include_once("inserir_acesso.php");
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Diretoria</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de diretores">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Diretoria">
	<meta property="og:url" content="<?php echo $host . "diretoria.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Diretoria">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "institucional";
		$subpagina = "diretoria";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Diretoria</h1>
					<p class="lead">Educação em Resistência (Gestão 2022 - 2023)</p>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 text-center">
					<table class="table text-justify">
						<thead></thead>
						<tbody>
							<tr>
								<th class="text-center" scope="col" colspan="3">Diretoria Executiva</th>
							</tr>
							<tr>
								<td>Presidente</td>
								<td>Marisa Morales Penati</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Vice-Presidente</td>
								<td>Nelson Martins Garcia</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Secretária Geral</td>
								<td>Rosinete Gonçalves Mariucci</td>
								<td>CCB/DBC</td>
							</tr>
							<tr>
								<td>Secretário de Finanças</td>
								<td>José Carlos da Costa</td>
								<td>DSM/APO</td>
							</tr>
							<tr>
								<td>Secretária de Gestão</td>
								<td>Sonia Aparecida Leonel</td>
								<td>CCS/COD</td>
							</tr>
							<tr>
								<td>Vice-Secretário de Gestão</td>
								<td>Amâncio Correa Maciel</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Secretário de Comunicação</td>
								<td>Luís Cláudio da Silva</td>
								<td>GRE/TV UEM</td>
							</tr>
							<tr>
								<td>Vice-Secretária de Comunicação</td>
								<td>Cláudia Aparecida Barbosa</td>
								<td>DHE</td>
							</tr>
							<tr>
								<td>Secretária de Assistência Jurídica</td>
								<td>Giseli Barbosa Volpato</td>
								<td>DRH-CAS</td>
							</tr>
							<tr>
								<td>Secretário de Formação Sindical</td>
								<td>Cesar Pereira</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Vice-Secretário de Formação Sindical</td>
								<td>Gilson Pereira Garcia</td>
								<td>RU</td>
							</tr>
							<tr>
								<td>Secretária de Aposentados</td>
								<td>Marlene Aparecida Gobbi</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Vice-Secretária de Aposentados</td>
								<td>Tereza de Souza Faria da Costa</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Secretária da Mulher, LGBT e Div. Racial</td>
								<td>Jeanete Alves Bezerra</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Vice-Secretária da Mulher, LGBT e Div. Racial</td>
								<td>Edna Barbosa Bergstron</td>
								<td>HUM/DAI</td>
							</tr>
							<tr>
								<td>Secretária de Saúde do Trabalhador</td>
								<td>Nilcéia Cristina Melo Cassarotti</td>
								<td>DEE-ATN</td>
							</tr>
							<tr>
								<td>Vice-Secretária de Saúde do Trabalhador</td>
								<td>Marilda Pereira</td>
								<td>DAI-API</td>
							</tr>
							<tr>
								<td>Secretária de Pessoal Docente</td>
								<td>Isabel Cristina Rodrigues</td>
								<td>CCH/DHI</td>
							</tr>
							<tr>
								<td>Vice-Secretário de Pessoal Docente</td>
								<td>Mário Camargo Pego</td>
								<td>CCH/DCS</td>
							</tr>
							<tr>
								<th class="text-center" scope="col" colspan="3">Conselho Fiscal</th>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Almir de Lima</td>
								<td>DSM/VIG</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Cleuza Gomes de Oliveira</td>
								<td>PEN/CAP</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>João Regassini</td>
								<td>DSM/VIG</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Valdecir Pereira da Silva</td>
								<td>GRE/PRO</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Sonia Mara Rita da Conceição da Silva</td>
								<td>APS</td>
							</tr>
							<tr>
								<th class="text-center" scope="col" colspan="3">Conselho de Representantes</th>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Agnaldo Ferreira</td>
								<td>HUM</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Antonio José de Souza</td>
								<td>DSM</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Arivaldo de Jesus Vicente</td>
								<td>CCS/COD</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Célio Aparecido Passolongo</td>
								<td>FEI</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Claudiney Cardoso Barbosa</td>
								<td>DSM</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Cleber Carneiro de Souza</td>
								<td>DGE/ECM</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Cristiano Domingues de Almeida</td>
								<td>CRV</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Derlande Matias da Silva</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Enéias Ramos de Oliveira</td>
								<td>PEC/MUDI</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Evaldeir Nicolau de Medeiros</td>
								<td>CAU</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Francisco Calixto da Silva</td>
								<td>PQE</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Inês Rodrigues de Souza</td>
								<td>CRC</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Luís Fernandes Barbosa</td>
								<td>CRN</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Marcos Alberto Trombelli</td>
								<td>DFT</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Maria de Fátima Melo</td>
								<td>HUM</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Ronaldo Cardoso de Oliveira</td>
								<td>CRN</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Solange Maria Ferraz Cotrinho</td>
								<td>CRG</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Sonia Regina Luciano</td>
								<td>DHI</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Tania de Fátima Calvi Tait</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Vilma Pereira de Melo</td>
								<td>DAG</td>
							</tr>
							<tr>
								<td>Titular</td>
								<td>Wanderley Gomes de Souza</td>
								<td>CAU</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Alice Machado Fraga</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Angela Maria Janunzzi</td>
								<td>DBI</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Celia Regina Alves de Morais</td>
								<td>HUM</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Clotilde Lopes da Silva</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Ednaldo Cândido da Silva</td>
								<td>PAD/IPU</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Eloisa Hundsdorfer</td>
								<td>CCS/COD</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Gilberto Mitsuaki Omori</td>
								<td>DSI/OFI</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Ivonei Luiz Freire</td>
								<td>CAR</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Jeferson Saucedo Sales</td>
								<td>DSM</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Jocelito Ernani Grandis</td>
								<td>PQE</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>José Isnar Guimarães</td>
								<td>DSM/VIG</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>José Luiz Roncaglia</td>
								<td>PQE</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>José Sebastião Pedroso</td>
								<td>HUM</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Juvenilia Cavalheiro</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Maria Helena Couto Fernandes</td>
								<td>CRG</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Maria Paula da Silva Prado</td>
								<td>APS</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Mauro de Azevedo Ribeiro</td>
								<td>CAU</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Robson Paulo Fumagalli Paiva</td>
								<td>CRN</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Rodrigo Pinheiro da Silva</td>
								<td>CAR</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Rosimar Gomes</td>
								<td>FEI</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Samuel Veríssimo</td>
								<td>NUPELIA</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Ulysses de Freitas</td>
								<td>CAU</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Valdevino Pedro Barbosa</td>
								<td>PQE</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Valdir Noveli</td>
								<td>HUM</td>
							</tr>
							<tr>
								<td>Suplente</td>
								<td>Zilda de Carvalho Rodrigues</td>
								<td>APS</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>