<?php
	chdir("controladores");
	include_once("inserir_acesso.php");
	$host = isset($_SERVER["REQUEST_SCHEME"]) && isset($_SERVER["HTTP_HOST"]) ? $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/sinteemar/" : "https://sinteemar.com.br/";

	if(isset($_POST["inputDiretoria"])) {
		switch($_POST["inputDiretoria"]) {
			case "2019-2022":
				header("Location: uploads/historico/2019-2022.pdf");
				break;
			case "2016-2019":
				header("Location: uploads/historico/2016-2019.pdf");
				break;
			case "2013-2016":
				header("Location: uploads/historico/2013-2016.pdf");
				break;
			case "2010-2013":
				header("Location: uploads/historico/2010-2013.pdf");
				break;
			case "2007-2010":
				header("Location: uploads/historico/2007-2010.pdf");
				break;
			case "2004-2007":
				header("Location: uploads/historico/2004-2007.pdf");
				break;
			case "2001-2004":
				header("Location: uploads/historico/2001-2004.pdf");
				break;
			case "1998-2001":
				header("Location: uploads/historico/1998-2001.pdf");
				break;
			case "1995-1998":
				header("Location: uploads/historico/1995-1998.pdf");
				break;
			case "1992-1995":
				header("Location: uploads/historico/1992-1995.pdf");
				break;
			case "1989-1992":
				header("Location: uploads/historico/1989-1992.pdf");
				break;
			case "1986-1989":
				header("Location: uploads/historico/1986-1989.pdf");
				break;
			default:
				header("Location: historico.php");
				break;
		}
		return TRUE;
	}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<title>Sinteemar - Histórico</title>
	<link type="image/ico" rel="icon" href="imagens/favicon.ico" id="favicon-ico">
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" id="bootstrap-css">
	<link type="text/css" rel="stylesheet" href="css/estilo.css" id="estilo-css">
	<meta charset="utf-8">
	<meta name="author" content="Luiz Joaquim Aderaldo Amichi">
	<meta name="description" content="Página de histórico">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="theme-color" content="#007358">
	<meta property="og:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="og:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:image:secure_url" content="<?php echo $host . "uploads/card.jpg"; ?>">
	<meta property="og:locale" content="pt_BR">
	<meta property="og:site_name" content="Sinteemar">
	<meta property="og:type" content="website">
	<meta property="og:title" content="Sinteemar - Histórico">
	<meta property="og:url" content="<?php echo $host . "historico.php"; ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:domain" content="<?php echo $host; ?>">
	<meta name="twitter:title" content="Sinteemar - Histórico">
	<meta name="twitter:description" content="Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá">
	<meta property="twitter:image" content="<?php echo $host . "uploads/card.jpg"; ?>">
</head>

<body>
	<!-- Cabeçalho -->
	<?php
		$pagina = "institucional";
		$subpagina = "historico";
		include_once("cabecalho.php");
	?>

	<!-- Conteúdo da Página -->
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 text-center">
					<h1 class="my-3">Histórico</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 text-justify">
					<p>&emsp;&emsp;Com o golpe militar de 31 de março de 1964, implantou-se no país um regime de exceção que iria durar por mais de duas décadas. O regime militar estendeu seu controle a todas as instâncias do poder e da sociedade comprometendo as práticas democráticas e o sistema representativo Nacional.</p>
					<p>&emsp;&emsp;Na educação, o Acordo MEC/USAID resultou na implantação da reforma universitária, a qual promoveria o desenvolvimento tecnológico do país, priorizando a produção do saber científico e tecnológico, o qual seria o pilar mestre do novo modelo desenvolvimentalista brasileiro. A ditadura sob a égide da ideologia da segurança nacional abafou a produção do saber crítico, mola propulsora das mudanças sociais. A repressão se instalou no país criando a censura à imprensa e a suspensão dos direitos e garantias individuais. Nas Universidades foram instaladas as Assessorias de informação e Segurança. Os membros das comunidades universitárias passaram a viver um período, onde as articulações políticas são extremamente difíceis. O autoritarismo, igualmente se reflete na UEM. Docentes da UEM inconformados reuniam-se fora do Campus iniciando-se as articulações para criar uma entidade representativa e de resistência na luta pela democratização da Universidade, e na ampliação dos direitos políticos e trabalhistas de seus membros. Fundou-se então em 02 de dezembro de 1978, a ADUEM, Associação dos Docentes da Universidade Estadual de Maringá. Em seguida, surge a AFUEM Associação dos Funcionários da Universidade Estadual de Maringá.</p>
					<p>&emsp;&emsp;Estas entidades atuavam no campo político, porém encontravam dificuldades para atuarem nas questões trabalhistas. Iniciou-se então a articulação de uma entidade sindical, que representasse os servidores da UEM. A legislação vigente na época exigia que se criasse uma Associação Profissional, como primeiro passo para a criação de um sindicato.</p>
					<p>&emsp;&emsp;No dia 26 de maio de 1984, no colégio Gastão Vidigal, em Maringá, aconteceu a Assembléia de Fundação da Associação Profissional dos Trabalhadores em Estabelecimentos de Ensino de Maringá e região. Foi aprovado por unanimidade o estatuto da entidade e eleita a primeira diretoria.</p>
					<p>&emsp;&emsp;Em 22 de novembro de 1984 foi registrado a Associação Profissional dos Trabalhadores em Estabelecimentos de Ensino de Maringá. O presidente Eleutério Vaselai convoca nova Assembléia para o dia 11 de dezembro do mesmo ano, tendo como ponto de pauta a transformação da Associação em Sindicato. Nesse mesmo dia foi eleita a primeira diretoria provisória do Sindicato.</p>
					<p>&emsp;&emsp;No dia 09 de julho de 1985, o Sindicato é reconhecido pelo Ministério do Trabalho, sob o número MTB – 24290- 014.944/84. A primeira diretoria eleita assumiu para um mandato de três anos no dia 10 de julho de 1986 até 10 de julho de 1989.</p>
				</div>
			</div>
			<hr>
			<div class="row justify-content-center">
				<form class="form-inline text-center justify-content-center" action="historico.php" method="POST" id="procurar_diretoria">
					<div class="form-group mx-sm-2 mt-3 mb-4">
						<select class="custom-select" id="inputDiretoria" name="inputDiretoria">
							<option value="2019-2022" selected>Educação em Resistência (2019 - 2022)</option>
							<option value="2016-2019">Educação em Resistência (2016 - 2019)</option>
							<option value="2013-2016">Unidade e Compromisso (2013 - 2016)</option>
							<option value="2010-2013">Integração e Luta (2010 - 2013)</option>
							<option value="2007-2010">Integração e Luta (2007 - 2010)</option>
							<option value="2004-2007">Gestão 2004 - 2007</option>
							<option value="2001-2004">MUDUP (2001 - 2004)</option>
							<option value="1998-2001">Gestão 1998 - 2001</option>
							<option value="1995-1998">Unidade e Luta (1995 - 1998)</option>
							<option value="1992-1995">Gestão 1992 - 1995</option>
							<option value="1989-1992">Gestão 1989 - 1992</option>
							<option value="1986-1989">Gestão 1986 - 1989</option>
						</select>
					</div>
					<div class="form-group mx-sm-2 mt-3 mb-4">
						<button class="btn bg-green text-white" type="submit">Procurar</button>
					</div>
				</form>
			</div>
		</div>
	</section>

	<!-- Rodapé -->
	<?php include_once("rodape.html"); ?>
</body>

</html>