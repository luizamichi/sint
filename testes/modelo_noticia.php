<?php
	/**
	 * Testes unitários do modelo Notícia
	 * @author Luiz Joaquim Aderaldo Amichi <luizamichi@luizamichi.com.br>
	 * @copyright 2020 Luiz Joaquim Aderaldo Amichi
	 */
	include_once("../modelos/Noticia.php");
	include_once("modelo_usuario.php");

	$noticiaModelo = new Noticia();
	$noticiaModelo->setId(1);
	$noticiaModelo->setTitulo("FES entrega ao governo pautas da campanha salarial");
	$noticiaModelo->setSubtitulo("O FES reivindica a manutenção dos direitos de carreira, respeito ao piso salarial regional, auxílios transporte e alimentação e concurso público");
	$noticiaModelo->setTexto("<p>Integrantes do Fórum das Entidades Sindicais (FES) se reuniram nesta terça-feira, 10, com o secretário de Administração e da Previdência, Reinhold Stephanes, para entregar o ofício ao governo com as pautas da campanha salarial de 2020.&nbsp;</p><p>A principal reivindicação é a reposição dos índices inflacionários nos salários dos servidores do Executivo, defasados desde 2017, acumulando perdas de mais de 18%. Os sindicalistas solicitaram composição, em caráter de urgência de uma mesa de negociação para discutir o assunto, tendo em vista que maio, mês da data base, está próximo.&nbsp;</p><p>Outro ponto da pauta discutida na reunião foi a precarização dos serviços públicos e a falta de valorização dos profissionais, com a manutenção dos direitos de carreira, respeito ao piso salarial regional, auxílios transporte e alimentação e concurso público. Os integrantes do FES fizeram questão de salientar que em todas as categorias houve redução de servidores, por conta da falta de reposição de servidores que se aposentaram, faleceram ou pediram exoneração, levando a demanda excessiva de trabalho aos servidores da ativa, o que está gerando inúmeros problemas de saúde entre eles a depressão e até suicídios.</p><p>Para resolver essas questões, o FES também colocou na pauta de reivindicações os problemas pelos quais passa o SAS – Sistema de Assistência à Saúde –, o projeto de saúde do servidor e a regionalização das perícias médicas. O FES também exige que a Comissão de Saúde composta pelo governo e FES, instituída por decreto, seja instaurada imediatamente.&nbsp;</p><p>Sobre a reforma da previdência, o conjunto de sindicalistas também questionou o secretário sobre a regulamentação das mudanças promovidas no final do ano passado, além da regulamentação da lei que promove alterações nas licenças especiais.</p><p><strong>Práticas antissindicais</strong></p><p>Outro assunto que ganhou destaque na reunião com o secretário foi a postura do governador Ratinho Jr. em relação ao decreto antissindical. Durante o processo de mediação promovido pelo Ministério Público do Trabalho (MPT) entre FES/Associações com o governo, o argumento utilizado para elaboração do decreto foi a adequação à Lei Geral de Proteção de Dados (LGPD).&nbsp;</p><p>No entanto, na terça-feira o governador fez postagens em suas mídias sociais comemorando o fato de que “No Paraná, os servidores não serão mais obrigados a pagar sindicato”. A postagem, mentirosa porque jamais os servidores foram obrigados a se filiar ou permanecer filiados, isso mostra que o governador desconhece e desrespeita as atividades das entidades que representam o trabalhador e prezam pelos seus direitos quando não são respeitados. Fica evidente a sua conduta tentando reduzir as receitas dos sindicatos, enfraquecendo as instituições.</p><p>Mas as práticas antissindicais no governo Ratinho Jr. não se resumem ao decreto 3978/2020. Os integrantes do FES relataram também ao secretário de Administração casos de cortes de adicionais nos salários, problemas na liberação para atuação sindical e respeito à autonomia do trabalho dos sindicalistas.</p>");
	$noticiaModelo->hashImagem("fes-marco2020.jpg"); // $noticiaModelo->setImagem("uploads/noticias/fes-marco2020.jpg");
	$noticiaModelo->setUsuario($usuarioModelo);
	$noticiaModelo->setData("2020-03-11 14:00:00");
	$noticiaModelo->setStatus(TRUE);

	// DEBUG MODE
	if((isset($argv[1]) && $argv[0] == "modelo_noticia.php" && $argv[1] == "debug") || (isset($argv[1]) && $argv[1] == "debug-all")) {
		echo $noticiaModelo;
	}
?>