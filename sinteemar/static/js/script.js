$(function () {
	console.log("%cSistema de Gerenciamento de Conteúdo", "font-size: 25px");
	console.log("%cCopyright © " + (new Date).getFullYear() + " Luiz Joaquim Aderaldo Amichi. Todos os direitos reservados.", "font-size: 15px");

	// Menu toggle
	$("#menu-toggle").click(function (evento) {
		evento.preventDefault();
		$("#wrapper").toggleClass("toggled");
		$(this).children("i").toggleClass("fa-align-left fa-align-right");
	});
	$(document).ready(function () {
		if ($(window).width() < 768) {
			$("#menu-toggle").children("i").removeClass("fa-align-left").addClass("fa-align-right");
		}
	});
	$(window).resize(function () {
		if ($(this).width() < 768) {
			if (!$("#menu-toggle").hasClass("toggled")) {
				$("#menu-toggle").children("i").removeClass("fa-align-left").addClass("fa-align-right");
			}
		}
		else {
			$("#menu-toggle").children("i").removeClass("fa-align-right").addClass("fa-align-left");
		}
	});

	// Tooltip personalizado
	$("[data-toggle='tooltip']").tooltip();

	// Ordenação de tabelas
	$("table").tablesorter();

	// Tipo de pesquisa
	$("#input-tipo").change(function () {
		if ($(this).val() == "id") {
			$("#input-pesquisa").prop("type", "number");
			$("#input-pesquisa").prop("min", "1");
		}
		else if ($(this).val() == "data") {
			$("#input-pesquisa").prop("type", "date");
			$("#input-pesquisa").removeAttr("list min");
		}
		else if ($(this).val() == "periodo") {
			$("#input-pesquisa").prop("type", "text");
			$("#input-pesquisa").attr("list", "periodos");
			$("#input-pesquisa").removeAttr("min");
		}
		else if ($(this).val() == "tipo") {
			$("#input-pesquisa").prop("type", "text");
			$("#input-pesquisa").attr("list", "tipos");
			$("#input-pesquisa").removeAttr("min");
		}
		else if (["celular", "cidade", "descricao", "diretorio", "documento", "email", "imagem", "ip", "login", "nome", "subtitulo", "telefone", "texto", "titulo", "url", "website"].includes($(this).val())) {
			$("#input-pesquisa").prop("type", "text");
			$("#input-pesquisa").removeAttr("list min");
		}
	});

	// Remoção de registro
	$("a[id*='remover?id=']").click(function () {
		$("#remover-registro").attr("href", $(this).attr("id"));
	});

	// Nome do arquivo no input[type="file"]
	$("input[type='file']").change(function () {
		var nome = $(this).val().split("\\").pop();
		var quantidade = $(this)[0].files.length;
		if (quantidade > 1) {
			$(this).siblings(".custom-file-label").addClass("selected").html(quantidade + " imagens selecionadas");
			$("#visualizador-de-imagens").empty();
		}
		else
			$(this).siblings(".custom-file-label").addClass("selected").html(nome);
	});

	// Pré-visualização de documento
	$("input[name='documento']").change(function () {
		if ($(this).prop("files")) {
			var leitor = new FileReader();
			leitor.onload = function (evento) {
				$("#visualizador-de-documento").attr("src", evento.target.result);
				$("#visualizador-de-documento").parent().removeAttr("style");
				$("#remove-documento").parent().removeAttr("style");
			};
			leitor.readAsDataURL($(this).prop("files")[0]);
		}
	});

	// Pré-visualização de imagem
	$("input[name='imagem']").change(function () {
		if ($(this).prop("files")) {
			var leitor = new FileReader();
			leitor.onload = function (evento) {
				$("#visualizador-de-imagem").attr("src", evento.target.result);
				$("#visualizador-de-imagem").parent().removeAttr("style");
			};
			leitor.readAsDataURL($(this).prop("files")[0]);
		}
	});

	// Pré-visualização de imagens
	$("input[name='imagens']").change(function () {
		if ($(this).prop("files")) {
			for (i = 0; i < $(this).prop("files").length; i++) {
				var leitor = new FileReader();
				leitor.onload = function (evento) {
					$("#visualizador-de-imagens").append($($.parseHTML("<img>")).attr({ "src": evento.target.result, "class": "rounded img-thumbnail m-1", "width": "225px" }))
				};
				leitor.readAsDataURL($(this).prop("files")[i]);
			}
		}
	});

	// Pré-visualização de URL
	$("input[name='url']").keyup(function () {
		if ($(this).val().match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g)) {
			var string = $(this).val().split("watch?v=");
			string = string.length === 1 ? $(this).val().split("youtu.be/") : string;
			$("#visualizador-de-url").attr("src", "https://www.youtube.com/embed/" + string[1]);
			$("#visualizador-de-url").parent().removeAttr("style");
		}
	});

	// Validação de número de telefone
	$("input[name='telefone']").keyup(function () {
		var x = $(this).val().replace(/\D/g, "").match(/(\d{0,2})(\d{0,4})(\d{0,4})/);
		$(this).val(!x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : ""));
	});

	// Validação de número de celular
	$("input[name='celular']").keyup(function () {
		var x = $(this).val().replace(/\D/g, "").match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
		$(this).val(!x[2] ? x[1] : "(" + x[1] + ") " + x[2] + (x[3] ? "-" + x[3] : ""));
	});

	// Remover documento do input e da pré-visualização
	$("#remove-documento").click(function () {
		$("input[name='documento']").val("");
		$("label[for='input-documento'].custom-file-label").html("Escolher documento");
		$("#visualizador-de-documento").parent().attr("style", "display: none;");
		$("#remove-documento").parent().attr("style", "display: none;");
		$("input[name='remover-documento']").val(true);
	});

	// Remover imagem do input e da pré-visualização
	$("#remove-imagem").click(function () {
		$("input[name='imagem']").val("");
		$("label[for='input-imagem'].custom-file-label").html("Escolher imagem");
		$("#visualizador-de-imagem").parent().attr("style", "display: none;");
		$("input[name='remover-imagem']").val(true);
	});

	// Limpar os nomes dos arquivos nos input[type="file"]
	// Limpar os input com valor definido (checkbox)
	// Limpar campos de texto nos textarea
	// Limpar as pré-visualizações dos arquivos
	$("button[type='reset']").click(function () {
		$("input[type='file']").val("");
		$("label[for='input-imagem'].custom-file-label").html("Escolher imagem");
		$("#visualizador-de-imagem").parent().attr("style", "display: none;");
		$("label[for='input-imagens'].custom-file-label").html("Escolher imagens");
		$("#visualizador-de-imagens").attr("style", "display: none;");
		$("#remove-documento").parent().attr("style", "display: none;");
		$("label[for='input-documento'].custom-file-label").html("Escolher documento");
		$("#visualizador-de-documento").parent().attr("style", "display: none;");
		$("#visualizador-de-url").parent().attr("style", "display: none;");
		$("input").removeAttr("checked value");
		$("textarea").html("");
		if ($("#summernote").length)
			$("#summernote").summernote("reset");
	});

	// Redirecionar a tela para a tabela selecionada
	$("#input-tabela").change(function () {
		window.location.hash = $(this).val();
	});
});