import locale, math
from flask import Blueprint, redirect, render_template, request, session, url_for

from sinteemar.db.database import database
from sinteemar.models.acesso import Acesso

# DATA ACCESS OBJECT
from sinteemar.dao.acesso import acesso_dao
from sinteemar.dao.banner import banner_dao
from sinteemar.dao.boletim import boletim_dao
from sinteemar.dao.convencao import convencao_dao
from sinteemar.dao.convenio import convenio_dao
from sinteemar.dao.diretoria import diretoria_dao
from sinteemar.dao.edital import edital_dao
from sinteemar.dao.estatuto import estatuto_dao
from sinteemar.dao.evento import evento_dao
from sinteemar.dao.financa import financa_dao
from sinteemar.dao.historico import historico_dao
from sinteemar.dao.jornal import jornal_dao
from sinteemar.dao.juridico import juridico_dao
from sinteemar.dao.noticia import noticia_dao
from sinteemar.dao.video import video_dao

website = Blueprint('website', __name__, static_folder='static', template_folder='templates', url_prefix='')

locale.setlocale(locale.LC_TIME, 'pt_BR.utf8')

# REGISTRAR GRUPO DE VISUALIZAÇÕES DO WEBSITE
def configure(app):
	app.register_blueprint(website)

# CADASTRAR ACESSO DO VISITANTE
def inserir_acesso():
	try:
		_ = session['acesso']
		return True
	except KeyError:
		session['acesso'] = request.environ['REMOTE_ADDR']
		acesso_dao.inserir(Acesso(ip=request.environ['REMOTE_ADDR']))

# VALIDAR CONEXÃO COM O BANCO DE DADOS
def validar_conexao():
	if database.cursor:
		return True
	else:
		return False

@website.route('/')
@website.route('/index')
def index():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	colunas = 3
	quantidade = 30
	tuplas = []
	banners = banner_dao.listar_quantidade(5)
	boletins = boletim_dao.listar_quantidade(15)
	tuplas.extend(boletins)
	editais = edital_dao.listar_quantidade(15)
	tuplas.extend(editais)
	eventos = evento_dao.listar_quantidade(5)
	tuplas.extend(eventos)
	jornais = jornal_dao.listar_quantidade(10)
	tuplas.extend(jornais)
	noticias = noticia_dao.listar_quantidade(30)
	tuplas.extend(noticias)
	tuplas.sort(key=lambda tupla: tupla.data, reverse=True)
	return render_template('index.html', banners=banners, colunas=colunas, tuplas=tuplas[:quantidade])

@website.route('/boletins/')
@website.route('/boletins', methods=['GET'])
def boletins():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = boletim_dao.tamanho_ativo(True)
	colunas = 3
	quantidade = 15
	paginas = math.ceil(registros / quantidade)
	if request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = boletim_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = boletim_dao.listar_intervalo(0, quantidade)
	return render_template('boletins.html', colunas=colunas, pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('/convencoes/')
@website.route('/convencoes', methods=['GET'])
def convencoes():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = convencao_dao.tamanho_ativo(True)
	quantidade = 20
	paginas = math.ceil(registros / quantidade)
	if request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = convencao_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = convencao_dao.listar_intervalo(0, quantidade)
	return render_template('convencoes.html', pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('/convenios/')
@website.route('/convenios', methods=['GET'])
def convenios():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = convenio_dao.tamanho_ativo(True)
	quantidade = 15
	paginas = math.ceil(registros / quantidade)
	if request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = convenio_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = convenio_dao.listar_intervalo(0, quantidade)
	return render_template('convenios.html', pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('/diretoria')
@website.route('/diretoria/')
def diretoria():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	tupla = diretoria_dao.procurar_ultimo()
	return render_template('diretoria.html', tupla=tupla)

@website.route('/editais/')
@website.route('/editais', methods=['GET'])
def editais():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = edital_dao.tamanho_ativo(True)
	colunas = 3
	quantidade = 10
	paginas = math.ceil(registros / quantidade)
	if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
		pk = int(request.args.get('id'))
		tupla = edital_dao.procurar_id(pk)
		if not tupla:
			return redirect(url_for('website.editais'))
		return render_template('editais.html', tupla=tupla)
	elif request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = edital_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = edital_dao.listar_intervalo(0, quantidade)
	return render_template('editais.html', colunas=colunas, pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('<path:pagina>')
def erro(pagina: str):
	return render_template('erro.html', pagina=pagina)

@website.route('/estatuto')
@website.route('/estatuto/')
def estatuto():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	tupla = estatuto_dao.procurar_ultimo()
	return render_template('estatuto.html', tupla=tupla)

@website.route('/eventos/')
@website.route('/eventos', methods=['GET'])
def eventos():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = evento_dao.tamanho_ativo(True)
	colunas = 3
	quantidade = 15
	paginas = math.ceil(registros / quantidade)
	if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
		pk = int(request.args.get('id'))
		tupla = evento_dao.procurar_id(pk)
		if not tupla:
			return redirect(url_for('website.eventos'))
		return render_template('eventos.html', tupla=tupla)
	elif request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = evento_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = evento_dao.listar_intervalo(0, quantidade)
	return render_template('eventos.html', colunas=colunas, pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('/financas/')
@website.route('/financas', methods=['GET'])
def financas():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = financa_dao.tamanho_ativo(True)
	quantidade = 33
	paginas = math.ceil(registros / quantidade)
	if request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = financa_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = financa_dao.listar_intervalo(0, quantidade)
	return render_template('financas.html', pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('/historico/')
@website.route('/historico', methods=['GET', 'POST'])
def historico():
	if not validar_conexao():
		return render_template('manutencao.html')
	if request.method == 'POST' and request.form['historico'].isnumeric():
		pk = int(request.form['historico'])
		historico = historico_dao.procurar_id(pk)
		if historico:
			return redirect(url_for('static', filename=historico.documento.caminho))
	else:
		inserir_acesso()
		tuplas = historico_dao.listar()
	return render_template('historico.html', tuplas=tuplas)

@website.route('/jornais/')
@website.route('/jornais', methods=['GET'])
def jornais():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = jornal_dao.tamanho_ativo(True)
	colunas = 4
	quantidade = 20
	paginas = math.ceil(registros / quantidade)
	if request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = jornal_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = jornal_dao.listar_intervalo(0, quantidade)
	return render_template('jornais.html', colunas=colunas, pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('/juridicos/')
@website.route('/juridicos', methods=['GET'])
def juridicos():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = juridico_dao.tamanho_ativo(True)
	quantidade = 15
	paginas = math.ceil(registros / quantidade)
	if request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = juridico_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = juridico_dao.listar_intervalo(0, quantidade)
	return render_template('juridicos.html', pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('/noticias/')
@website.route('/noticias', methods=['GET'])
def noticias():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = noticia_dao.tamanho_ativo(True)
	colunas = 2
	quantidade = 10
	paginas = math.ceil(registros / quantidade)
	if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
		pk = int(request.args.get('id'))
		tupla = noticia_dao.procurar_id(pk)
		if not tupla:
			return redirect(url_for('website.noticias'))
		return render_template('noticias.html', tupla=tupla)
	elif request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = noticia_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = noticia_dao.listar_intervalo(0, quantidade)
	return render_template('noticias.html', colunas=colunas, pagina=pagina, paginas=paginas, tuplas=tuplas)

@website.route('/videos/')
@website.route('/videos', methods=['GET'])
def videos():
	if not validar_conexao():
		return render_template('manutencao.html')
	inserir_acesso()
	pagina = 1
	registros = video_dao.tamanho_ativo(True)
	quantidade = 15
	paginas = math.ceil(registros / quantidade)
	if request.args.get('p') and request.args.get('p').isnumeric():
		pagina = min(int(request.args.get('p')), registros)
		tuplas = video_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
	else:
		tuplas = video_dao.listar_intervalo(0, quantidade)
	return render_template('videos.html', pagina=pagina, paginas=paginas, tuplas=tuplas)
