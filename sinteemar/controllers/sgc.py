import json, math, os, sys
from bs4 import BeautifulSoup
from datetime import date
from hashlib import md5
from flask import Blueprint, flash, redirect, render_template, request, session, url_for, __version__

from sinteemar.config import BASE_DIR, VERSION
from sinteemar.db.database import database

# MODELOS
from sinteemar.models.banner import Banner
from sinteemar.models.boletim import Boletim
from sinteemar.models.convencao import Convencao
from sinteemar.models.convenio import Convenio
from sinteemar.models.edital import Edital
from sinteemar.models.evento import Evento
from sinteemar.models.financa import Financa
from sinteemar.models.historico import Historico
from sinteemar.models.jornal import Jornal
from sinteemar.models.juridico import Juridico
from sinteemar.models.noticia import Noticia
from sinteemar.models.registro import Registro
from sinteemar.models.usuario import Usuario
from sinteemar.models.video import Video

# DATA ACCESS OBJECT
from sinteemar.dao.acesso import acesso_dao
from sinteemar.dao.arquivo import arquivo_dao
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
from sinteemar.dao.permissao import permissao_dao
from sinteemar.dao.registro import registro_dao
from sinteemar.dao.usuario import usuario_dao
from sinteemar.dao.video import video_dao

sgc = Blueprint('sgc', __name__, static_folder='static', template_folder='templates', url_prefix='/sgc')

# REGISTRAR GRUPO DE VISUALIZAÇÕES DO SGC
def configure(app):
	app.register_blueprint(sgc)

# FAZER UPLOAD DE ARQUIVO (DOCUMENTO, IMAGEM)
def enviar_arquivo(formulario, arquivo):
	if arquivo:
		formulario.save(arquivo.caminho_absoluto)

# LIMPAR CARACTERES HTML
def limpar_html(texto: str):
	return BeautifulSoup(texto, features='html.parser').get_text().strip()

# ESCREVER LOG DE ATIVIDADE
def registrar(descricao: str):
	if not session['permissao']['administrador']:
		registro = Registro(descricao=descricao, ip=request.environ['REMOTE_ADDR'], usuario=Usuario(id=json.loads(session['usuario'])['id']))
		registro_dao.inserir(registro)

# VALIDAR CONEXÃO COM O BANCO DE DADOS
def validar_conexao():
	if database.cursor:
		return True
	return False

# VALIDAR USUÁRIO CONECTADO
def validar_sessao():
	try:
		_ = session['usuario']
		return True
	except KeyError:
		return False

# VALIDAR PERMISSÕES DO USUÁRIO
def validar_permissao(permissao: str):
	if session['permissao'][permissao]:
		return True
	return False

# PÁGINA INICIAL DO SISTEMA DE GERENCIAMENTO DE CONTEÚDO
@sgc.route('/', methods=['GET', 'POST'])
def index():
	if not validar_conexao():
		return render_template('manutencao.html')
	elif validar_sessao():
		try:
			dominio = os.environ['USERDOMAIN']
			processador = os.environ['PROCESSOR_IDENTIFIER']
			so = os.environ['OS']
			usuario = os.environ['USERNAME']
		except Exception:
			dominio = os.environ['NAME']
			processador = 'Não identificado'
			so = 'Não identificado'
			usuario = os.environ['LOGNAME']
		tupla = {'dominio': dominio, 'flask': __version__, 'mysql': usuario_dao.database.version, 'navegador': request.environ['HTTP_USER_AGENT'], 'processador': processador, 'protocolo': request.environ['wsgi.url_scheme'] + ' (' + request.environ['SERVER_PROTOCOL'] + ')', 'python': sys.version, 'servidor': request.environ['SERVER_NAME'] + ' (' + request.environ['HTTP_HOST'] + ')', 'so': so, 'software': request.environ['SERVER_SOFTWARE'], 'usuario': usuario, 'versao': VERSION, 'wsgi': '.'.join(map(str, request.environ['wsgi.version']))}
		return render_template('sgc/index.html', permissao=session['permissao'], tupla=tupla)
	elif request.method == 'POST':
		if login(request.form['login'], request.form['senha']):
			mensagem = 'Bem-vindo, ' + json.loads(session['usuario'])['nome'] + '.'
			flash('primary')
			flash(mensagem)
			return redirect(url_for('sgc.index'))
		else: # NÃO ENCONTROU UM USUÁRIO A PARTIR DO LOGIN E SENHA INFORMADOS
			flash('danger')
			flash('O usuário ou a senha estão incorretos.')
	return render_template('sgc/login.html')

# AUTENTICADOR DE USUÁRIOS
def login(login: str, senha: str):
	usuario = usuario_dao.procurar_login(login)
	if usuario and usuario.senha == md5(str.encode(senha)).hexdigest():
		session['permissao'] = usuario.permissao.dicionario
		session['usuario'] = json.dumps({'id': usuario.id, 'nome': usuario.nome, 'email': usuario.email, 'login': usuario.login, 'senha': usuario.senha, 'permissao': usuario.permissao.string, 'data': usuario.data.strftime('%d/%m/%Y - %H:%M:%S'), 'ativo': usuario.ativo})
		return True
	return False

# DESVINCULAR USUÁRIO DA SESSÃO
@sgc.route('/logout')
def logout():
	session.clear()
	return redirect(url_for('sgc.index'))

# PÁGINA DE SUPORTE
@sgc.route('suporte')
@sgc.route('suporte/')
def suporte():
	if validar_sessao():
		return render_template('sgc/suporte.html', permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA COM DADOS DO USUÁRIO
@sgc.route('/conta')
@sgc.route('/conta/')
def conta():
	if not validar_conexao():
		return render_template('manutencao.html')
	elif validar_sessao():
		return render_template('sgc/conta/index.html', permissao=session['permissao'], tupla=json.loads(session['usuario']))
	return render_template('sgc/login.html')

# PÁGINA DE ALTERAÇÃO DE SENHA DO USUÁRIO
@sgc.route('/conta/alterar/')
@sgc.route('/conta/alterar', methods=['GET', 'POST'])
def alterar_senha():
	if not validar_conexao():
		return render_template('manutencao.html')
	elif validar_sessao():
		if request.method == 'POST':
			usuario = json.loads(session['usuario'])
			senha_anterior = md5(str.encode(request.form['senha-anterior'])).hexdigest()
			nova_senha = md5(str.encode(request.form['nova-senha'])).hexdigest()
			if senha_anterior != usuario['senha']: # INFORMOU UMA SENHA ANTERIOR INVÁLIDA
				flash('danger')
				flash('A senha anterior está incorreta.')
			elif senha_anterior == nova_senha: # INFORMOU UMA NOVA SENHA IGUAL A ANTERIOR
				flash('danger')
				flash('A nova senha informada é igual a anterior.')
			else:
				usuario_dao.alterar_senha(nova_senha, usuario['id'])
				flash('success')
				flash('Sua senha foi alterada.')
		return render_template('sgc/conta/alterar.html', permissao=session['permissao'])
	return render_template('sgc/login.html')

# PÁGINA DE AJUDA
@sgc.route('ajuda')
@sgc.route('ajuda/')
def ajuda():
	if validar_sessao():
		return render_template('sgc/ajuda.html', permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ESTATÍSTICA DE VISITAS DO SITE
@sgc.route('acessos')
@sgc.route('acessos/')
def acessos():
	if not validar_conexao():
		return render_template('manutencao.html')
	elif validar_sessao() and validar_permissao('acessos'):
		diario = acesso_dao.tamanho_data(date.today().strftime('%Y-%m-%d'))
		mensal = acesso_dao.tamanho_data(date.today().strftime('%Y-%m'))
		anual = acesso_dao.tamanho_data(date.today().strftime('%Y'))
		tuplas = acesso_dao.listar_quantidade(15)
		return render_template('sgc/acessos/index.html', anual=anual, diario=diario, mensal=mensal, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE BANNERS
@sgc.route('banners/')
@sgc.route('banners', methods=['GET'])
def banners():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('banners'):
		pagina = 1
		registros = banner_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS BANNERS
			tuplas = banner_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há banners cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS BANNERS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = banner_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM BANNER
			tupla = banner_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.banners'))
			return render_template('sgc/banners/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = banner_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'imagem':
				imagem = request.args.get('pesquisa')
				tuplas = banner_dao.procurar_imagens(imagem)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = banner_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = banner_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum banner.')
		return render_template('sgc/banners/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE BANNER
@sgc.route('/banners/inserir/')
@sgc.route('/banners/inserir', methods=['GET', 'POST'])
def inserir_banner():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('banners'):
		if request.method == 'POST':
			if 'imagem' not in request.files or request.files['imagem'].filename == '': # NÃO ENVIOU UMA IMAGEM
				flash('danger')
				flash('Não foi possível cadastrar o banner. Nenhuma imagem foi enviada.')
				return redirect(request.url)
			else:
				banner = Banner(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
				banner.imagem = request.files['imagem'].filename, True
				enviar_arquivo(request.files['imagem'], banner.imagem)
				banner_dao.inserir(banner)
				flash('success')
				flash('Banner cadastrado com sucesso.')
				registrar('INSERÇÃO: BANNER ' + str(banner_dao.procurar_ultimo().id))
		return render_template('sgc/banners/inserir.html', permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE BANNER
@sgc.route('/banners/alterar', methods=['GET', 'POST'])
def alterar_banner():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('banners'):
		banner = Banner(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			banner = banner_dao.procurar_id(pk)
			if not banner:
				return redirect(url_for('sgc.banners'))
			session['id'] = pk
		elif request.method == 'POST':
			if 'imagem' not in request.files or request.files['imagem'].filename == '': # NÃO ENVIOU UMA IMAGEM
				flash('danger')
				flash('Não foi possível alterar o banner. Nenhuma imagem foi enviada.')
				return redirect(request.url)
			else:
				anterior = banner_dao.procurar_id(session['id'])
				banner.id = anterior.id
				banner.imagem = request.files['imagem'].filename, True
				enviar_arquivo(request.files['imagem'], banner.imagem)
				banner_dao.alterar(banner)
				flash('success')
				flash('Banner alterado com sucesso.')
				registrar('ALTERAÇÃO: BANNER ' + str(banner.id))
		else:
			return redirect(url_for('sgc.banners'))
		return render_template('sgc/banners/alterar.html', banner=banner, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE BANNER
@sgc.route('/banners/remover', methods=['GET'])
def remover_banner():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('banners'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if banner_dao.desativar(pk):
				flash('success')
				flash('Banner removido com sucesso.')
				registrar('REMOÇÃO: BANNER ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o banner.')
		return redirect(url_for('sgc.banners'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE BOLETINS
@sgc.route('boletins/')
@sgc.route('boletins', methods=['GET'])
def boletins():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('boletins'):
		pagina = 1
		registros = boletim_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS BOLETINS
			tuplas = boletim_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há boletins cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS BOLETINS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = boletim_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM BOLETIM
			tupla = boletim_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.boletins'))
			return render_template('sgc/boletins/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = boletim_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = boletim_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'documento':
				documento = request.args.get('pesquisa')
				tuplas = boletim_dao.procurar_documentos(documento)
			elif request.args.get('tipo') == 'imagem':
				imagem = request.args.get('pesquisa')
				tuplas = boletim_dao.procurar_imagens(imagem)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = boletim_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = boletim_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum boletim.')
		return render_template('sgc/boletins/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE BOLETIM
@sgc.route('/boletins/inserir/')
@sgc.route('/boletins/inserir', methods=['GET', 'POST'])
def inserir_boletim():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('boletins'):
		boletim = Boletim(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			mensagem = []
			boletim.titulo = request.form['titulo']
			if not boletim.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if 'imagem' in request.files and request.files['imagem'].filename != '':
				boletim.imagem = request.files['imagem'].filename, True
			if 'documento' not in request.files or request.files['documento'].filename == '': # NÃO ENVIOU UM DOCUMENTO
				mensagem.append('nenhum documento foi enviado')
			if not mensagem:
				boletim.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], boletim.documento)
				enviar_arquivo(request.files['imagem'], boletim.imagem)
				boletim_dao.inserir(boletim)
				flash('success')
				flash('Boletim cadastrado com sucesso.')
				registrar('INSERÇÃO: BOLETIM ' + str(boletim_dao.procurar_ultimo().id))
				boletim = Boletim()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o boletim. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/boletins/inserir.html', boletim=boletim, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE BOLETIM
@sgc.route('/boletins/alterar', methods=['GET', 'POST'])
def alterar_boletim():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('boletins'):
		boletim = Boletim(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			boletim = boletim_dao.procurar_id(pk)
			if not boletim:
				return redirect(url_for('sgc.boletins'))
			session['id'] = pk
		elif request.method == 'POST':
			anterior = boletim_dao.procurar_id(session['id'])
			boletim.id = anterior.id
			boletim.titulo = request.form['titulo']
			if not boletim.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				flash('danger')
				flash('Não foi possível alterar o boletim. O título é inválido.')
				return redirect(request.url)
			if 'documento' in request.files and request.files['documento'].filename != '':
				boletim.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], boletim.documento)
			else:
				boletim.documento = anterior.documento.arquivo
			if 'imagem' in request.files and request.files['imagem'].filename != '':
				boletim.imagem = request.files['imagem'].filename, True
				enviar_arquivo(request.files['imagem'], boletim.imagem)
			elif not request.form['remover-imagem']:
				boletim.imagem = anterior.imagem.arquivo if anterior.imagem else anterior.imagem
			boletim_dao.alterar(boletim)
			flash('success')
			flash('Boletim alterado com sucesso.')
			registrar('ALTERAÇÃO: BOLETIM ' + str(boletim.usuario.id))
		else:
			return redirect(url_for('sgc.boletins'))
		return render_template('sgc/boletins/alterar.html', boletim=boletim, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE BOLETIM
@sgc.route('/boletins/remover', methods=['GET'])
def remover_boletim():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('boletins'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if boletim_dao.desativar(pk):
				flash('success')
				flash('Boletim removido com sucesso.')
				registrar('REMOÇÃO: BOLETIM ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o boletim.')
		return redirect(url_for('sgc.boletins'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE CONVENÇÕES
@sgc.route('convencoes/')
@sgc.route('convencoes', methods=['GET'])
def convencoes():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('convenções'):
		pagina = 1
		registros = convencao_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DAS CONVENÇÕES
			tuplas = convencao_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há convenções cadastradas.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR AS CONVENÇÕES DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = convencao_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UMA CONVENÇÃO
			tupla = convencao_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.convencoes'))
			return render_template('sgc/convencoes/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = convencao_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = convencao_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'documento':
				documento = request.args.get('pesquisa')
				tuplas = convencao_dao.procurar_documentos(documento)
			elif request.args.get('tipo') == 'tipo':
				tipo = request.args.get('pesquisa')
				tuplas = convencao_dao.procurar_tipo(tipo)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = convencao_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = convencao_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrada nenhuma convenção.')
		return render_template('sgc/convencoes/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE CONVENÇÃO
@sgc.route('/convencoes/inserir/')
@sgc.route('/convencoes/inserir', methods=['GET', 'POST'])
def inserir_convencao():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('convenções'):
		convencao = Convencao(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			mensagem = []
			convencao.titulo = request.form['titulo']
			convencao.tipo = int(request.form['tipo'])
			if not convencao.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if 'documento' not in request.files or request.files['documento'].filename == '': # NÃO ENVIOU UM DOCUMENTO
				mensagem.append('nenhum documento foi enviado')
			if not mensagem:
				convencao.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], convencao.documento)
				convencao_dao.inserir(convencao)
				flash('success')
				flash('Convenção cadastrada com sucesso.')
				registrar('INSERÇÃO: CONVENÇÃO ' + str(convencao_dao.procurar_ultimo().id))
				convencao = Convencao()
			else:
				flash('danger')
				flash('Não foi possível cadastrar a convenção. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/convencoes/inserir.html', convencao=convencao, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE CONVENÇÃO
@sgc.route('/convencoes/alterar', methods=['GET', 'POST'])
def alterar_convencao():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('convenções'):
		convencao = Convencao(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			convencao = convencao_dao.procurar_id(pk)
			if not convencao:
				return redirect(url_for('sgc.convencoes'))
			session['id'] = pk
		elif request.method == 'POST':
			anterior = convencao_dao.procurar_id(session['id'])
			convencao.id = anterior.id
			convencao.titulo = request.form['titulo']
			if not convencao.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				flash('danger')
				flash('Não foi possível alterar a convenção. O título é inválido.')
				return redirect(request.url)
			if 'documento' in request.files and request.files['documento'].filename != '':
				convencao.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], convencao.documento)
			else:
				convencao.documento = anterior.documento.arquivo
			convencao.tipo = int(request.form['tipo'])
			convencao_dao.alterar(convencao)
			flash('success')
			flash('Convenção alterada com sucesso.')
			registrar('ALTERAÇÃO: CONVENÇÃO ' + str(convencao.usuario.id))
		else:
			return redirect(url_for('sgc.convencoes'))
		return render_template('sgc/convencoes/alterar.html', permissao=session['permissao'], convencao=convencao)
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE CONVENÇÃO
@sgc.route('/convencoes/remover', methods=['GET'])
def remover_convencao():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('convenções'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if convencao_dao.desativar(pk):
				flash('success')
				flash('Convenção removida com sucesso.')
				registrar('REMOÇÃO: CONVENÇÃO ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover a convenção.')
		return redirect(url_for('sgc.convencoes'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE CONVÊNIOS
@sgc.route('convenios/')
@sgc.route('convenios', methods=['GET'])
def convenios():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('convênios'):
		pagina = 1
		registros = convenio_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS CONVÊNIOS
			tuplas = convenio_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há convênios cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS CONVÊNIOS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = convenio_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM CONVÊNIO
			tupla = convenio_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.convenios'))
			return render_template('sgc/convenios/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = convenio_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'descricao':
				descricao = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_descricao(descricao)
			elif request.args.get('tipo') == 'telefone':
				telefone = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_telefones(telefone)
			elif request.args.get('tipo') == 'celular':
				celular = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_celulares(celular)
			elif request.args.get('tipo') == 'website':
				website = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_websites(website)
			elif request.args.get('tipo') == 'email':
				email = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_emails(email)
			elif request.args.get('tipo') == 'imagem':
				imagem = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_imagens(imagem)
			elif request.args.get('tipo') == 'documento':
				documento = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_documentos(documento)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = convenio_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = convenio_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum convênio.')
		return render_template('sgc/convenios/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE CONVÊNIO
@sgc.route('/convenios/inserir/')
@sgc.route('/convenios/inserir', methods=['GET', 'POST'])
def inserir_convenio():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('convênios'):
		convenio = Convenio(ativo=True)
		if request.method == 'POST':
			mensagem = []
			convenio.titulo = request.form['titulo']
			convenio.descricao = request.form['descricao']
			convenio.cidade = request.form['cidade']
			convenio.telefone = request.form['telefone']
			convenio.celular = request.form['celular']
			convenio.website = request.form['website']
			convenio.email = request.form['email']
			if not convenio.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not convenio.descricao: # INFORMOU UMA DESCRIÇÃO INVÁLIDA
				mensagem.append('descrição inválida')
			if not convenio.cidade: # INFORMOU UMA CIDADE INVÁLIDA
				mensagem.append('cidade inválida')
			if request.form['telefone']:
				if not convenio.telefone: # INFORMOU UM TELEFONE INVÁLIDO
					mensagem.append('telefone inválido')
				elif convenio_dao.procurar_telefone(convenio.telefone): # INFORMOU UM TELEFONE JÁ CADASTRADO
					mensagem.append('telefone pertence a outro convênio')
			if request.form['celular']:
				if not convenio.celular: # INFORMOU UM CELULAR INVÁLIDO
					mensagem.append('celular inválido')
				elif convenio_dao.procurar_celular(convenio.celular): # INFORMOU UM CELULAR JÁ CADASTRADO
					mensagem.append('celular pertence a outro convênio')
			if request.form['website']:
				if not convenio.website: # INFORMOU UM WEBSITE INVÁLIDO
					mensagem.append('website inválido')
				elif convenio_dao.procurar_website(convenio.website): # INFORMOU UM WEBSITE JÁ CADASTRADO
					mensagem.append('website pertence a outro convênio')
			if request.form['email']:
				if not convenio.email: # INFORMOU UM E-MAIL INVÁLIDO
					mensagem.append('e-mail inválido')
				elif convenio_dao.procurar_email(convenio.email): # INFORMOU UM E-MAIL JÁ CADASTRADO
					mensagem.append('e-mail pertence a outro convênio')
			if 'imagem' not in request.files or request.files['imagem'].filename == '': # NÃO ENVIOU UMA IMAGEM
				mensagem.append('nenhuma imagem foi enviada')
			if 'documento' in request.files and request.files['documento'].filename != '':
				convenio.documento = request.files['documento'].filename, True
			if not mensagem:
				convenio.usuario = Usuario(id=json.loads(session['usuario'])['id'])
				enviar_arquivo(request.files['imagem'], convenio.imagem)
				enviar_arquivo(request.files['documento'], convenio.documento)
				convenio_dao.inserir(convenio)
				flash('success')
				flash('Convênio cadastrado com sucesso.')
				registrar('INSERÇÃO: CONVÊNIO ' + str(convenio_dao.procurar_ultimo().id))
				convenio = Convenio()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o convênio. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/convenios/inserir.html', convenio=convenio, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE CONVÊNIO
@sgc.route('/convenios/alterar', methods=['GET', 'POST'])
def alterar_convenio():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('convênios'):
		convenio = Convenio(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			convenio = convenio_dao.procurar_id(pk)
			if not convenio:
				return redirect(url_for('sgc.convenios'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = convenio_dao.procurar_id(session['id'])
			convenio.id = anterior.id
			convenio.titulo = request.form['titulo']
			convenio.descricao = request.form['descricao']
			convenio.cidade = request.form['cidade']
			convenio.telefone = request.form['telefone']
			convenio.celular = request.form['celular']
			convenio.website = request.form['website']
			convenio.email = request.form['email']
			if not convenio.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not convenio.descricao: # INFORMOU UMA DESCRIÇÃO INVÁLIDA
				mensagem.append('descrição inválida')
			if not convenio.cidade: # INFORMOU UMA CIDADE INVÁLIDA
				mensagem.append('cidade inválida')
			if request.form['telefone']:
				if not convenio.telefone: # INFORMOU UM TELEFONE INVÁLIDO
					mensagem.append('telefone inválido')
				elif anterior.telefone != convenio.telefone and convenio_dao.procurar_telefone(convenio.telefone): # INFORMOU UM TELEFONE JÁ CADASTRADO
					mensagem.append('telefone pertence a outro convênio')
			if request.form['celular']:
				if not convenio.celular: # INFORMOU UM CELULAR INVÁLIDO
					mensagem.append('celular inválido')
				elif anterior.celular != convenio.celular and convenio_dao.procurar_celular(convenio.celular): # INFORMOU UM CELULAR JÁ CADASTRADO
					mensagem.append('celular pertence a outro convênio')
			if request.form['website']:
				if not convenio.website: # INFORMOU UM WEBSITE INVÁLIDO
					mensagem.append('website inválido')
				elif anterior.website != convenio.website and convenio_dao.procurar_website(convenio.website): # INFORMOU UM WEBSITE JÁ CADASTRADO
					mensagem.append('website pertence a outro convênio')
			if request.form['email']:
				if not convenio.email: # INFORMOU UM E-MAIL INVÁLIDO
					mensagem.append('e-mail inválido')
				elif anterior.email != convenio.email and convenio_dao.procurar_email(convenio.email): # INFORMOU UM E-MAIL JÁ CADASTRADO
					mensagem.append('e-mail pertence a outro convênio')
			if not mensagem:
				if 'imagem' in request.files and request.files['imagem'].filename != '':
					convenio.imagem = request.files['imagem'].filename, True
					enviar_arquivo(request.files['imagem'], convenio.imagem)
				else:
					convenio.imagem = anterior.imagem.arquivo
				if 'documento' in request.files and request.files['documento'].filename != '':
					convenio.documento = request.files['documento'].filename, True
					enviar_arquivo(request.files['documento'], convenio.documento)
				elif not request.form['remover-documento']:
					convenio.documento = anterior.documento.arquivo if anterior.documento else anterior.documento
				convenio_dao.alterar(convenio)
				flash('success')
				flash('Convênio alterado com sucesso.')
				registrar('ALTERAÇÃO: CONVÊNIO ' + str(convenio.id))
			else:
				convenio.imagem = anterior.imagem.arquivo
				convenio.documento = (anterior.documento.arquivo if anterior.documento else anterior.documento)
				flash('danger')
				flash('Não foi possível alterar o convênio. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.convenios'))
		return render_template('sgc/convenios/alterar.html', convenio=convenio,permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE CONVÊNIO
@sgc.route('/convenios/remover', methods=['GET'])
def remover_convenio():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('convênios'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if convenio_dao.desativar(pk):
				flash('success')
				flash('Convênio removido com sucesso.')
				registrar('REMOÇÃO: CONVÊNIO ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o convênio.')
		return redirect(url_for('sgc.convenios'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE DIRETORIA
@sgc.route('diretoria/')
@sgc.route('diretoria', methods=['GET'])
def diretoria():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('diretoria'):
		tupla = diretoria_dao.procurar_ultimo()
		return render_template('sgc/diretoria/index.html', permissao=session['permissao'], tupla=tupla)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE DIRETORIA
@sgc.route('/diretoria/alterar', methods=['GET', 'POST'])
def alterar_diretoria():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('diretoria'):
		diretoria = diretoria_dao.procurar_ultimo()
		if request.method == 'POST':
			mensagem = []
			diretoria.titulo = request.form['titulo']
			diretoria.texto = request.form['texto']
			if not diretoria.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if len(limpar_html(request.form['texto'])) < 6: # INFORMOU UM TEXTO INVÁLIDO
				mensagem.append('texto inválido')
			if not mensagem:
				if 'imagem' in request.files and request.files['imagem'].filename != '':
					diretoria.imagem = request.files['imagem'].filename, True
					enviar_arquivo(request.files['imagem'], diretoria.imagem)
				elif request.form['remover-imagem']:
					diretoria.imagem = None
				diretoria_dao.alterar(diretoria)
				flash('success')
				flash('Diretoria alterada com sucesso.')
				registrar('ALTERAÇÃO: DIRETORIA ' + str(diretoria.usuario.id))
			else:
				flash('danger')
				flash('Não foi possível alterar a diretoria. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/diretoria/alterar.html', diretoria=diretoria, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE LISTAGEM DE ARQUIVOS DO SISTEMA
@sgc.route('diretorio')
@sgc.route('diretorio/')
def diretorio():
	if validar_sessao():
		tuplas = []
		for diretorio, _, arquivos in os.walk(BASE_DIR):
			tamanho = diretorio.split(os.sep)
			tuplas.append({'nome': tamanho[-1], 'tipo': 'diretorio', 'espaco': 4 * len(tamanho)})
			for nome in arquivos:
				tuplas.append({'nome': nome, 'tipo': 'arquivo', 'espaco': 8 * len(tamanho)})
		return render_template('sgc/diretorio/index.html', permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE EDITAIS
@sgc.route('editais/')
@sgc.route('editais', methods=['GET'])
def editais():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('editais'):
		pagina = 1
		registros = edital_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS EDITAIS
			tuplas = edital_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há editais cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS EDITAIS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = edital_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM EDITAL
			tupla = edital_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.editais'))
			return render_template('sgc/editais/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = edital_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = edital_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'descricao':
				descricao = request.args.get('pesquisa')
				tuplas = edital_dao.procurar_descricao(descricao)
			elif request.args.get('tipo') == 'imagem':
				imagem = request.args.get('pesquisa')
				tuplas = edital_dao.procurar_imagens(imagem)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = edital_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = edital_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum edital.')
		return render_template('sgc/editais/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE EDITAL
@sgc.route('/editais/inserir/')
@sgc.route('/editais/inserir', methods=['GET', 'POST'])
def inserir_edital():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('editais'):
		edital = Edital(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			mensagem = []
			edital.titulo = request.form['titulo']
			edital.descricao = request.form['descricao']
			if not edital.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not edital.descricao: # INFORMOU UMA DESCRIÇÃO INVÁLIDA
				mensagem.append('descrição inválida')
			if 'imagem' not in request.files or request.files['imagem'].filename == '': # NÃO ENVIOU UMA IMAGEM
				mensagem.append('nenhuma imagem foi enviada')
			if not mensagem:
				edital.imagem = request.files['imagem'].filename, True
				enviar_arquivo(request.files['imagem'], edital.imagem)
				edital_dao.inserir(edital)
				flash('success')
				flash('Edital cadastrado com sucesso.')
				registrar('INSERÇÃO: EDITAL ' + str(edital_dao.procurar_ultimo().id))
				edital = Edital()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o edital. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/editais/inserir.html', edital=edital, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE EDITAL
@sgc.route('/editais/alterar', methods=['GET', 'POST'])
def alterar_edital():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('editais'):
		edital = Edital(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			edital = edital_dao.procurar_id(pk)
			if not edital:
				return redirect(url_for('sgc.editais'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = edital_dao.procurar_id(session['id'])
			edital.id = anterior.id
			edital.titulo = request.form['titulo']
			edital.descricao = request.form['descricao']
			if not edital.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not edital.descricao: # INFORMOU UMA DESCRIÇÃO INVÁLIDA
				mensagem.append('descrição inválida')
			if not mensagem:
				if 'imagem' in request.files and request.files['imagem'].filename != '':
					edital.imagem = request.files['imagem'].filename, True
					enviar_arquivo(request.files['imagem'], edital.imagem)
				else:
					edital.imagem = anterior.imagem.arquivo
				edital_dao.alterar(edital)
				flash('success')
				flash('Edital alterado com sucesso.')
				registrar('ALTERAÇÃO: EDITAL ' + str(edital.usuario.id))
			else:
				edital.imagem = anterior.imagem.arquivo
				flash('danger')
				flash('Não foi possível alterar o edital. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.editais'))
		return render_template('sgc/editais/alterar.html', edital=edital, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE EDITAL
@sgc.route('/editais/remover', methods=['GET'])
def remover_edital():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('editais'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if edital_dao.desativar(pk):
				flash('success')
				flash('Edital removido com sucesso.')
				registrar('REMOÇÃO: EDITAL ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o edital.')
		return redirect(url_for('sgc.editais'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE ESTATUTO
@sgc.route('estatuto/')
@sgc.route('estatuto', methods=['GET'])
def estatuto():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('estatuto'):
		tupla = estatuto_dao.procurar_ultimo()
		return render_template('sgc/estatuto/index.html', permissao=session['permissao'], tupla=tupla)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE ESTATUTO
@sgc.route('/estatuto/alterar', methods=['GET', 'POST'])
def alterar_estatuto():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('estatuto'):
		estatuto = estatuto_dao.procurar_ultimo()
		if request.method == 'POST':
			if 'documento' not in request.files or request.files['documento'].filename == '': # NÃO ENVIOU UM DOCUMENTO
				flash('danger')
				flash('Não foi possível alterar o estatuto. Nenhum documento foi enviado.')
			else:
				estatuto.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], estatuto.documento)
				estatuto_dao.alterar(estatuto)
				flash('success')
				flash('Estatuto alterado com sucesso.')
				registrar('ALTERAÇÃO: ESTATUTO ' + str(estatuto.usuario.id))
		return render_template('sgc/estatuto/alterar.html', estatuto=estatuto, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE EVENTOS
@sgc.route('eventos/')
@sgc.route('eventos', methods=['GET'])
def eventos():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('eventos'):
		pagina = 1
		registros = evento_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS EVENTOS
			tuplas = evento_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há eventos cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS EVENTOS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = evento_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM EVENTO
			tupla = evento_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.eventos'))
			return render_template('sgc/eventos/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = evento_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = evento_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'descricao':
				descricao = request.args.get('pesquisa')
				tuplas = evento_dao.procurar_descricao(descricao)
			elif request.args.get('tipo') == 'imagem':
				imagem = request.args.get('pesquisa')
				tuplas = evento_dao.procurar_imagens(imagem)
			elif request.args.get('diretorio'):
				diretorio = request.args.get('pesquisa')
				tuplas = evento_dao.procurar_diretorios(diretorio)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = evento_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = evento_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum evento.')
		return render_template('sgc/eventos/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE EVENTO
@sgc.route('/eventos/inserir/')
@sgc.route('/eventos/inserir', methods=['GET', 'POST'])
def inserir_evento():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('eventos'):
		evento = Evento(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			mensagem = []
			evento.titulo = request.form['titulo']
			evento.descricao = request.form['descricao']
			if not evento.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not evento.descricao: # INFORMOU UMA DESCRIÇÃO INVÁLIDA
				mensagem.append('descrição inválida')
			if 'imagens' not in request.files or request.files['imagens'].filename == '': # NÃO ENVIOU UMA IMAGEM
				mensagem.append('nenhuma imagem foi enviada')
			if not mensagem:
				os.mkdir(os.path.join(BASE_DIR, 'sinteemar', 'static', 'uploads', 'evento', evento.diretorio))
				for arquivo in request.files.getlist('imagens'):
					imagem = evento.imagens_append(arquivo.filename, True)
					enviar_arquivo(arquivo, imagem)
				evento_dao.inserir(evento)
				flash('success')
				flash('Evento cadastrado com sucesso.')
				registrar('INSERÇÃO: EVENTO ' + str(evento_dao.procurar_ultimo().id))
				evento = Evento()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o evento. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/eventos/inserir.html', evento=evento, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE EVENTO
@sgc.route('/eventos/alterar', methods=['GET', 'POST'])
def alterar_evento():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('eventos'):
		evento = Evento(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			evento = evento_dao.procurar_id(pk)
			if not evento:
				return redirect(url_for('sgc.eventos'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = evento_dao.procurar_id(session['id'])
			evento.id = anterior.id
			evento.diretorio = anterior.diretorio
			evento.titulo = request.form['titulo']
			evento.descricao = request.form['descricao']
			if not evento.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not evento.descricao: # INFORMOU UMA DESCRIÇÃO INVÁLIDA
				mensagem.append('descrição inválida')
			if not mensagem:
				if 'imagens' in request.files and request.files['imagens'].filename != '':
					for arquivo in request.files.getlist('imagens'):
						imagem = evento.imagens_append(arquivo.filename, True)
						enviar_arquivo(arquivo, imagem)
				else:
					evento.imagens = anterior.imagens
				evento_dao.alterar(evento)
				flash('success')
				flash('Evento alterado com sucesso.')
				registrar('ALTERAÇÃO: EVENTO ' + str(evento.usuario.id))
				evento = evento_dao.procurar_id(evento.id)
			else:
				evento.imagens = anterior.imagens
				flash('danger')
				flash('Não foi possível alterar o evento. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.eventos'))
		return render_template('sgc/eventos/alterar.html', evento=evento, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE EVENTO
@sgc.route('/eventos/remover', methods=['GET'])
def remover_evento():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('eventos'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if evento_dao.desativar(pk):
				flash('success')
				flash('Evento removido com sucesso.')
				registrar('REMOÇÃO: EVENTO ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o evento.')
		return redirect(url_for('sgc.eventos'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE FINANÇAS
@sgc.route('financas/')
@sgc.route('financas', methods=['GET'])
def financas():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('finanças'):
		pagina = 1
		registros = financa_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DAS FINANÇAS
			tuplas = financa_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há finanças cadastradas.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR AS FINANÇAS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = financa_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UMA FINANÇA
			tupla = financa_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.financas'))
			return render_template('sgc/financas/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = financa_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'periodo':
				periodo = request.args.get('pesquisa')
				tuplas = financa_dao.procurar_periodo(periodo)
			elif request.args.get('tipo') == 'documento':
				documento = request.args.get('pesquisa')
				tuplas = financa_dao.procurar_documentos(documento)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = financa_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = financa_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrada nenhuma finança.')
		return render_template('sgc/financas/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE FINANÇA
@sgc.route('/financas/inserir/')
@sgc.route('/financas/inserir', methods=['GET', 'POST'])
def inserir_financa():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('finanças'):
		financa = Financa(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			financa.flag = request.form['ano'] + request.form['periodo']
			if 'documento' not in request.files or request.files['documento'].filename == '': # NÃO ENVIOU UM DOCUMENTO
				flash('danger')
				flash('Não foi possível cadastrar a finança. Nenhum documento foi enviado.')
			else:
				financa.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], financa.documento)
				financa_dao.inserir(financa)
				flash('success')
				flash('Finança cadastrada com sucesso.')
				registrar('INSERÇÃO: FINANÇA ' + str(financa_dao.procurar_ultimo().id))
				financa = Financa()
		ano_atual = int(date.today().strftime('%Y'))
		ano = [str(x) for x in range(ano_atual - 7, ano_atual + 3)]
		return render_template('sgc/financas/inserir.html', ano=ano, financa=financa, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE FINANÇA
@sgc.route('/financas/alterar', methods=['GET', 'POST'])
def alterar_financa():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('finanças'):
		financa = Financa(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			financa = financa_dao.procurar_id(pk)
			if not financa:
				return redirect(url_for('sgc.financas'))
			session['id'] = pk
		elif request.method == 'POST':
			anterior = financa_dao.procurar_id(session['id'])
			financa.id = anterior.id
			financa.flag = request.form['ano'] + request.form['periodo']
			if 'documento' in request.files and request.files['documento'].filename != '':
				financa.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], financa.documento)
			else:
				financa.documento = anterior.documento.arquivo
			financa_dao.alterar(financa)
			flash('success')
			flash('Finança alterada com sucesso.')
			registrar('ALTERAÇÃO: FINANÇA ' + str(financa.usuario.id))
		else:
			return redirect(url_for('sgc.financas'))
		ano_financa = int(financa.flag[:4])
		ano = [str(x) for x in range(ano_financa - 7, ano_financa + 3)]
		return render_template('sgc/financas/alterar.html', ano=ano, financa=financa, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE FINANÇA
@sgc.route('/financas/remover', methods=['GET'])
def remover_financa():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('finanças'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if financa_dao.desativar(pk):
				flash('success')
				flash('Finança removida com sucesso.')
				registrar('REMOÇÃO: FINANÇA ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover a finança.')
		return redirect(url_for('sgc.financas'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE HISTÓRICOS
@sgc.route('historico/')
@sgc.route('historico', methods=['GET'])
def historico():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('histórico'):
		pagina = 1
		registros = historico_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS HISTÓRICOS
			tuplas = historico_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há históricos cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS HISTÓRICOS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = historico_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM HISTÓRICO
			tupla = historico_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.historico'))
			return render_template('sgc/historico/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = historico_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = historico_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'documento':
				documento = request.args.get('pesquisa')
				tuplas = historico_dao.procurar_documentos(documento)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = historico_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = historico_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum histórico.')
		return render_template('sgc/historico/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE HISTÓRICO
@sgc.route('/historico/inserir/')
@sgc.route('/historico/inserir', methods=['GET', 'POST'])
def inserir_historico():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('histórico'):
		historico = Historico(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			mensagem = []
			historico.titulo = request.form['titulo']
			if not historico.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if 'documento' not in request.files or request.files['documento'].filename == '': # NÃO ENVIOU UM DOCUMENTO
				mensagem.append('nenhum documento foi enviado')
			if not mensagem:
				historico.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], historico.documento)
				historico_dao.inserir(historico)
				flash('success')
				flash('Histórico cadastrado com sucesso.')
				registrar('INSERÇÃO: HISTÓRICO ' + str(historico_dao.procurar_ultimo().id))
				historico = Historico()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o histórico. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/historico/inserir.html', historico=historico, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE HISTÓRICO
@sgc.route('/historico/alterar', methods=['GET', 'POST'])
def alterar_historico():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('histórico'):
		historico = Historico(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			historico = historico_dao.procurar_id(pk)
			if not historico:
				return redirect(url_for('sgc.historico'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = historico_dao.procurar_id(session['id'])
			historico.id = anterior.id
			historico.titulo = request.form['titulo']
			if not historico.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not mensagem:
				if 'documento' in request.files and request.files['documento'].filename != '':
					historico.documento = request.files['documento'].filename, True
					enviar_arquivo(request.files['documento'], historico.documento)
				else:
					historico.documento = anterior.documento.arquivo
				historico_dao.alterar(historico)
				flash('success')
				flash('Histórico alterado com sucesso.')
				registrar('ALTERAÇÃO: HISTÓRICO ' + str(historico.usuario.id))
			else:
				historico.documento = anterior.documento.arquivo
				flash('danger')
				flash('Não foi possível alterar o histórico. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.historico'))
		return render_template('sgc/historico/alterar.html', historico=historico, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE HISTÓRICO
@sgc.route('/historico/remover', methods=['GET'])
def remover_historico():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('histórico'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if historico_dao.desativar(pk):
				flash('success')
				flash('Histórico removido com sucesso.')
				registrar('REMOÇÃO: HISTÓRICO ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o histórico.')
		return redirect(url_for('sgc.historico'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE JORNAIS
@sgc.route('jornais/')
@sgc.route('jornais', methods=['GET'])
def jornais():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('jornais'):
		pagina = 1
		registros = jornal_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS JORNAIS
			tuplas = jornal_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há jornais cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS JORNAIS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = jornal_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM JORNAL
			tupla = jornal_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.jornais'))
			return render_template('sgc/jornais/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = jornal_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = jornal_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'documento':
				documento = request.args.get('pesquisa')
				tuplas = jornal_dao.procurar_documentos(documento)
			elif request.args.get('tipo') == 'imagem':
				imagem = request.args.get('pesquisa')
				tuplas = jornal_dao.procurar_imagens(imagem)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = jornal_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = jornal_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum jornal.')
		return render_template('sgc/jornais/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE JORNAL
@sgc.route('/jornais/inserir/')
@sgc.route('/jornais/inserir', methods=['GET', 'POST'])
def inserir_jornal():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('jornais'):
		jornal = Jornal(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			mensagem = []
			jornal.titulo = request.form['titulo']
			jornal.edicao = int('0' + request.form['edicao'])
			if not jornal.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if jornal_dao.procurar_edicao(jornal.edicao): # INFORMOU UMA EDIÇÃO JÁ CADASTRADA
				mensagem.append('edição pertence a outro jornal')
			if 'imagem' in request.files and request.files['imagem'].filename != '':
				jornal.imagem = request.files['imagem'].filename, True
			if 'documento' not in request.files or request.files['documento'].filename == '': # NÃO ENVIOU UM DOCUMENTO
				mensagem.append('nenhum documento foi enviado')
			if not mensagem:
				jornal.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], jornal.documento)
				enviar_arquivo(request.files['imagem'], jornal.imagem)
				jornal_dao.inserir(jornal)
				flash('success')
				flash('Jornal cadastrado com sucesso.')
				registrar('INSERÇÃO: JORNAL ' + str(jornal_dao.procurar_ultimo().id))
				jornal = Jornal()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o jornal. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/jornais/inserir.html', jornal=jornal, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE JORNAL
@sgc.route('/jornais/alterar', methods=['GET', 'POST'])
def alterar_jornal():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('jornais'):
		jornal = Jornal(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			jornal = jornal_dao.procurar_id(pk)
			if not jornal:
				return redirect(url_for('sgc.jornais'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = jornal_dao.procurar_id(session['id'])
			jornal.id = anterior.id
			jornal.titulo = request.form['titulo']
			jornal.edicao = int('0' + request.form['edicao'])
			if not jornal.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if anterior.edicao != jornal.edicao and jornal_dao.procurar_edicao(jornal.edicao): # INFORMOU UMA EDIÇÃO JÁ CADASTRADA
				mensagem.append('edição pertence a outro jornal')
			if not mensagem:
				if 'documento' in request.files and request.files['documento'].filename != '':
					jornal.documento = request.files['documento'].filename, True
					enviar_arquivo(request.files['documento'], jornal.documento)
				else:
					jornal.documento = anterior.documento.arquivo
				if 'imagem' in request.files and request.files['imagem'].filename != '':
					jornal.imagem = request.files['imagem'].filename, True
					enviar_arquivo(request.files['imagem'], jornal.imagem)
				elif not request.form['remover-imagem']:
					jornal.imagem = anterior.imagem.arquivo if anterior.imagem else anterior.imagem
				jornal_dao.alterar(jornal)
				flash('success')
				flash('Jornal alterado com sucesso.')
				registrar('ALTERAÇÃO: JORNAL ' + str(jornal.usuario.id))
			else:
				jornal.documento = anterior.documento.arquivo
				jornal.imagem = (anterior.imagem.arquivo if anterior.imagem else anterior.imagem)
				flash('danger')
				flash('Não foi possível alterar o jornal. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.jornais'))
		return render_template('sgc/jornais/alterar.html', jornal=jornal, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE JORNAL
@sgc.route('/jornais/remover', methods=['GET'])
def remover_jornal():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('jornais'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if jornal_dao.desativar(pk):
				flash('success')
				flash('Jornal removido com sucesso.')
				registrar('REMOÇÃO: JORNAL ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o jornal.')
		return redirect(url_for('sgc.jornais'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE JURÍDICOS
@sgc.route('juridicos/')
@sgc.route('juridicos', methods=['GET'])
def juridicos():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('jurídicos'):
		pagina = 1
		registros = juridico_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS JURÍDICOS
			tuplas = juridico_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há jurídicos cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS JURÍDICOS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = juridico_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM JURÍDICO
			tupla = juridico_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.juridicos'))
			return render_template('sgc/juridicos/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = juridico_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = juridico_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'descricao':
				descricao = request.args.get('pesquisa')
				tuplas = juridico_dao.procurar_descricao(descricao)
			elif request.args.get('tipo') == 'documento':
				documento = request.args.get('pesquisa')
				tuplas = juridico_dao.procurar_documentos(documento)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = juridico_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = juridico_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum jurídico.')
		return render_template('sgc/juridicos/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE JURÍDICO
@sgc.route('/juridicos/inserir/')
@sgc.route('/juridicos/inserir', methods=['GET', 'POST'])
def inserir_juridico():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('jurídicos'):
		juridico = Juridico(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			mensagem = []
			juridico.titulo = request.form['titulo']
			juridico.descricao = request.form['descricao']
			if not juridico.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if 'documento' not in request.files or request.files['documento'].filename == '': # NÃO ENVIOU UM DOCUMENTO
				mensagem.append('nenhum documento foi enviado')
			if not mensagem:
				juridico.documento = request.files['documento'].filename, True
				enviar_arquivo(request.files['documento'], juridico.documento)
				juridico_dao.inserir(juridico)
				flash('success')
				flash('Jurídico cadastrado com sucesso.')
				registrar('INSERÇÃO: JURÍDICO ' + str(juridico_dao.procurar_ultimo().id))
				juridico = Juridico()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o jurídico. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/juridicos/inserir.html', juridico=juridico, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE JURÍDICO
@sgc.route('/juridicos/alterar', methods=['GET', 'POST'])
def alterar_juridico():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('jurídicos'):
		juridico = Juridico(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			juridico = juridico_dao.procurar_id(pk)
			if not juridico:
				return redirect(url_for('sgc.juridicos'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = juridico_dao.procurar_id(session['id'])
			juridico.id = anterior.id
			juridico.titulo = request.form['titulo']
			juridico.descricao = request.form['descricao']
			if not juridico.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not mensagem:
				if 'documento' in request.files and request.files['documento'].filename != '':
					juridico.documento = request.files['documento'].filename, True
					enviar_arquivo(request.files['documento'], juridico.documento)
				else:
					juridico.documento = anterior.documento.arquivo
				juridico_dao.alterar(juridico)
				flash('success')
				flash('Jurídico alterado com sucesso.')
				registrar('ALTERAÇÃO: JURÍDICO ' + str(juridico.usuario.id))
			else:
				juridico.documento = anterior.documento.arquivo
				flash('danger')
				flash('Não foi possível alterar o jurídico. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.juridicos'))
		return render_template('sgc/juridicos/alterar.html', juridico=juridico, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE JURÍDICO
@sgc.route('/juridicos/remover', methods=['GET'])
def remover_juridico():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('jurídicos'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if juridico_dao.desativar(pk):
				flash('success')
				flash('Jurídico removido com sucesso.')
				registrar('REMOÇÃO: JURÍDICO ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o jurídico.')
		return redirect(url_for('sgc.juridicos'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE NOTÍCIAS
@sgc.route('noticias/')
@sgc.route('noticias', methods=['GET'])
def noticias():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('notícias'):
		pagina = 1
		registros = noticia_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DAS NOTÍCIAS
			tuplas = noticia_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há notícias cadastradas.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS NOTÍCIAS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = noticia_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UMA NOTÍCIA
			tupla = noticia_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.noticias'))
			return render_template('sgc/noticias/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = noticia_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = noticia_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'subtitulo':
				subtitulo = request.args.get('pesquisa')
				tuplas = noticia_dao.procurar_subtitulo(subtitulo)
			elif request.args.get('tipo') == 'texto':
				texto = request.args.get('pesquisa')
				tuplas = noticia_dao.procurar_texto(texto)
			elif request.args.get('tipo') == 'imagem':
				imagem = request.args.get('pesquisa')
				tuplas = noticia_dao.procurar_imagens(imagem)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = noticia_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = noticia_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrada nenhuma notícia.')
		return render_template('sgc/noticias/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE NOTÍCIA
@sgc.route('/noticias/inserir/')
@sgc.route('/noticias/inserir', methods=['GET', 'POST'])
def inserir_noticia():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('notícias'):
		noticia = Noticia(usuario=Usuario(id=json.loads(session['usuario'])['id']), ativo=True)
		if request.method == 'POST':
			mensagem = []
			noticia.titulo = request.form['titulo']
			noticia.subtitulo = request.form['subtitulo']
			noticia.texto = request.form['texto']
			if not noticia.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if len(limpar_html(request.form['texto'])) < 6: # INFORMOU UM TEXTO INVÁLIDO
				mensagem.append('texto inválido')
			if 'imagem' in request.files and request.files['imagem'].filename != '':
				noticia.imagem = request.files['imagem'].filename, True
			if not mensagem:
				enviar_arquivo(request.files['imagem'], noticia.imagem)
				noticia_dao.inserir(noticia)
				flash('success')
				flash('Notícia cadastrada com sucesso.')
				registrar('INSERÇÃO: NOTÍCIA ' + str(noticia_dao.procurar_ultimo().id))
				noticia = Noticia()
			else:
				flash('danger')
				flash('Não foi possível cadastrar a notícia. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/noticias/inserir.html', noticia=noticia, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE NOTÍCIA
@sgc.route('/noticias/alterar', methods=['GET', 'POST'])
def alterar_noticia():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('notícias'):
		noticia = Noticia(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			noticia = noticia_dao.procurar_id(pk)
			if not noticia:
				return redirect(url_for('sgc.noticias'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = noticia_dao.procurar_id(session['id'])
			noticia.id = anterior.id
			noticia.titulo = request.form['titulo']
			noticia.subtitulo = request.form['subtitulo']
			noticia.texto = request.form['texto']
			if not noticia.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if len(limpar_html(request.form['texto'])) < 6: # INFORMOU UM TEXTO INVÁLIDO
				mensagem.append('texto inválido')
			if not mensagem:
				if 'imagem' in request.files and request.files['imagem'].filename != '':
					noticia.imagem = request.files['imagem'].filename, True
					enviar_arquivo(request.files['imagem'], noticia.imagem)
				elif not request.form['remover-imagem']:
					noticia.imagem = anterior.imagem.arquivo if anterior.imagem else anterior.imagem
				noticia_dao.alterar(noticia)
				flash('success')
				flash('Notícia alterada com sucesso.')
				registrar('ALTERAÇÃO: NOTÍCIA ' + str(noticia.usuario.id))
			else:
				noticia.imagem = (anterior.imagem.arquivo if anterior.imagem else anterior.imagem)
				flash('danger')
				flash('Não foi possível alterar a notícia. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.noticias'))
		return render_template('sgc/noticias/alterar.html', noticia=noticia, permissao=session['permissao'])
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE NOTÍCIA
@sgc.route('/noticias/remover', methods=['GET'])
def remover_noticia():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('notícias'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if noticia_dao.desativar(pk):
				flash('success')
				flash('Notícia removida com sucesso.')
				registrar('REMOÇÃO: NOTÍCIA ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover a notícia.')
		return redirect(url_for('sgc.noticias'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE LISTAGEM DE REGISTROS
@sgc.route('registros/')
@sgc.route('registros', methods=['GET'])
def registros():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('registros'):
		pagina = 1
		registros = registro_dao.tamanho()
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS REGISTROS
			tuplas = registro_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não foi realizada nenhuma atividade no sistema.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS REGISTROS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = registro_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = registro_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'descricao':
				descricao = request.args.get('pesquisa')
				tuplas = registro_dao.procurar_descricao(descricao)
			elif request.args.get('tipo') == 'ip':
				ip = request.args.get('pesquisa')
				tuplas = registro_dao.procurar_ip(ip)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = registro_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = registro_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum registro.')
		return render_template('sgc/registros/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE LISTAGEM DE TABELAS
@sgc.route('tabelas')
@sgc.route('tabelas/')
def tabelas():
	if not validar_conexao():
		return render_template('manutencao.html')
	elif validar_sessao() and validar_permissao('tabelas'):
		acessos = acesso_dao.listar()
		arquivos = arquivo_dao.listar()
		banners = banner_dao.listar()
		boletins = boletim_dao.listar()
		convencoes = convencao_dao.listar()
		convenios = convenio_dao.listar()
		diretoria = diretoria_dao.listar()
		editais = edital_dao.listar()
		estatuto = estatuto_dao.listar()
		eventos = evento_dao.listar()
		financas = financa_dao.listar()
		historico = historico_dao.listar()
		jornais = jornal_dao.listar()
		juridicos = juridico_dao.listar()
		noticias = noticia_dao.listar()
		permissoes = permissao_dao.listar()
		registros = registro_dao.listar()
		usuarios = usuario_dao.listar()
		videos = video_dao.listar()
		return render_template('sgc/tabelas/index.html', acessos=acessos, arquivos=arquivos, banners=banners, boletins=boletins, convencoes=convencoes, convenios=convenios, diretoria=diretoria, editais=editais, estatuto=estatuto, eventos=eventos, financas=financas, historico=historico, jornais=jornais, juridicos=juridicos, noticias=noticias, permissao=session['permissao'], permissoes=permissoes, registros=registros, usuarios=usuarios, videos=videos)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE USUÁRIOS
@sgc.route('usuarios/')
@sgc.route('usuarios', methods=['GET'])
def usuarios():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('usuários'):
		pagina = 1
		registros = usuario_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS USUÁRIOS
			tuplas = usuario_dao.listar_intervalo(0, quantidade)
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS USUÁRIOS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = usuario_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM USUÁRIO
			tupla = usuario_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.usuarios'))
			return render_template('sgc/usuarios/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = usuario_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'nome':
				nome = request.args.get('pesquisa')
				tuplas = usuario_dao.procurar_nomes(nome)
			elif request.args.get('tipo') == 'login':
				login = request.args.get('pesquisa')
				tuplas = usuario_dao.procurar_logins(login)
			elif request.args.get('tipo') == 'email':
				email = request.args.get('pesquisa')
				tuplas = usuario_dao.procurar_emails(email)
			elif request.args.get('tipo') == 'data':
				data = request.args.get('pesquisa')
				tuplas = usuario_dao.procurar_data(data)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = usuario_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum usuário.')
		return render_template('sgc/usuarios/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE USUÁRIO
@sgc.route('/usuarios/inserir/')
@sgc.route('/usuarios/inserir', methods=['GET', 'POST'])
def inserir_usuario():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('usuários'):
		usuario = Usuario(ativo=True)
		if request.method == 'POST':
			mensagem = []
			usuario.nome = request.form['nome']
			usuario.email = request.form['email']
			usuario.login = request.form['login']
			usuario.senha = request.form['senha']
			if not usuario.nome: # INFORMOU UM NOME INVÁLIDO
				mensagem.append('nome inválido')
			if not usuario.email: # INFORMOU UM E-MAIL INVÁLIDO
				mensagem.append('e-mail inválido')
			elif usuario_dao.procurar_email(usuario.email): # INFORMOU UM E-MAIL JÁ CADASTRADO
				mensagem.append('e-mail pertence a outro usuário')
			if usuario_dao.procurar_login(usuario.login): # INFORMOU UM LOGIN JÁ CADASTRADO
				mensagem.append('login pertence a outro usuário')
			if not usuario.senha: # INFORMOU UMA SENHA INVÁLIDA
				mensagem.append('senha inválida')
			if 'acessos' in request.form:
				usuario.permissao.acessos = True
			if 'banners' in request.form:
				usuario.permissao.banners = True
			if 'boletins' in request.form:
				usuario.permissao.boletins = True
			if 'convencoes' in request.form:
				usuario.permissao.convencoes = True
			if 'convenios' in request.form:
				usuario.permissao.convenios = True
			if 'diretoria' in request.form:
				usuario.permissao.diretoria = True
			if 'editais' in request.form:
				usuario.permissao.editais = True
			if 'estatuto' in request.form:
				usuario.permissao.estatuto = True
			if 'eventos' in request.form:
				usuario.permissao.eventos = True
			if 'financas' in request.form:
				usuario.permissao.financas = True
			if 'historico' in request.form:
				usuario.permissao.historico = True
			if 'jornais' in request.form:
				usuario.permissao.jornais = True
			if 'juridicos' in request.form:
				usuario.permissao.juridicos = True
			if 'noticias' in request.form:
				usuario.permissao.noticias = True
			if 'registros' in request.form:
				usuario.permissao.registros = True
			if 'usuarios' in request.form:
				usuario.permissao.usuarios = True
			if 'videos' in request.form:
				usuario.permissao.videos = True
			if usuario.permissao.vazio: # NÃO INFORMOU NENHUMA PERMISSÃO
				mensagem.append('nenhuma permissão foi atribuída')
			if not mensagem:
				usuario.senha = usuario.senha, True
				usuario_dao.inserir(usuario)
				flash('success')
				flash('Usuário cadastrado com sucesso.')
				registrar('INSERÇÃO: USUÁRIO ' + str(usuario_dao.procurar_ultimo().id))
				usuario = Usuario()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o usuário. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/usuarios/inserir.html', permissao=session['permissao'], usuario=usuario)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE USUÁRIO
@sgc.route('/usuarios/alterar', methods=['GET', 'POST'])
def alterar_usuario():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('usuários'):
		usuario = Usuario(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			usuario = usuario_dao.procurar_id(pk)
			if not usuario:
				return redirect(url_for('sgc.usuarios'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = usuario_dao.procurar_id(session['id'])
			usuario.id = anterior.id
			usuario.nome = request.form['nome']
			usuario.email = request.form['email']
			usuario.login = request.form['login']
			usuario.senha = request.form['senha'], True
			if not usuario.nome: # INFORMOU UM NOME INVÁLIDO
				mensagem.append('nome inválido')
			if not usuario.email: # INFORMOU UM E-MAIL INVÁLIDO
				mensagem.append('e-mail inválido')
			elif anterior.email != usuario.email and usuario_dao.procurar_email(usuario.email): # INFORMOU UM E-MAIL JÁ CADASTRADO
				mensagem.append('e-mail pertence a outro usuário')
			if not usuario.login: # INFORMOU UM LOGIN INVÁLIDO
				mensagem.append('login inválido')
			elif anterior.login != usuario.login and usuario_dao.procurar_login(usuario.login): # INFORMOU UM LOGIN JÁ CADASTRADO
				mensagem.append('login pertence a outro usuário')
			if not usuario.senha:
				usuario.senha = anterior.senha
			usuario.permissao.id = anterior.permissao.id
			usuario.permissao.admin = anterior.permissao.admin
			if usuario.permissao.admin and not session['permissao']['administrador']: # O USUÁRIO NÃO TEM PERMISSÃO PARA ALTERAR UM ADMINISTRADOR
				flash('danger')
				flash('Não foi possível alterar o usuário. Problema: alteração de administrador bloqueada.')
				return redirect(request.url)
			usuario.permissao.diretorio = anterior.permissao.diretorio
			usuario.permissao.tabelas = anterior.permissao.tabelas
			if 'acessos' in request.form:
				usuario.permissao.acessos = True
			if 'banners' in request.form:
				usuario.permissao.banners = True
			if 'boletins' in request.form:
				usuario.permissao.boletins = True
			if 'convencoes' in request.form:
				usuario.permissao.convencoes = True
			if 'convenios' in request.form:
				usuario.permissao.convenios = True
			if 'diretoria' in request.form:
				usuario.permissao.diretoria = True
			if 'editais' in request.form:
				usuario.permissao.editais = True
			if 'estatuto' in request.form:
				usuario.permissao.estatuto = True
			if 'eventos' in request.form:
				usuario.permissao.eventos = True
			if 'financas' in request.form:
				usuario.permissao.financas = True
			if 'historico' in request.form:
				usuario.permissao.historico = True
			if 'jornais' in request.form:
				usuario.permissao.jornais = True
			if 'juridicos' in request.form:
				usuario.permissao.juridicos = True
			if 'noticias' in request.form:
				usuario.permissao.noticias = True
			if 'registros' in request.form:
				usuario.permissao.registros = True
			if 'usuarios' in request.form:
				usuario.permissao.usuarios = True
			if 'videos' in request.form:
				usuario.permissao.videos = True
			if usuario.permissao.vazio: # NÃO INFORMOU NENHUMA PERMISSÃO
				mensagem.append('nenhuma permissão foi atribuída')
			if not mensagem:
				usuario_dao.alterar(usuario)
				flash('success')
				flash('Usuário alterado com sucesso.')
				registrar('ALTERAÇÃO: USUÁRIO ' + str(usuario.id))
			else:
				flash('danger')
				flash('Não foi possível alterar o usuário. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.usuarios'))
		return render_template('sgc/usuarios/alterar.html', permissao=session['permissao'], usuario=usuario)
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE USUÁRIO
@sgc.route('/usuarios/remover', methods=['GET'])
def remover_usuario():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('usuários'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			usuario_remove = usuario_dao.procurar_id(pk)
			if usuario_remove and not usuario_remove.permissao.admin and usuario_dao.desativar(pk):
				flash('success')
				flash('Usuário removido com sucesso.')
				registrar('REMOÇÃO: USUÁRIO ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o usuário.')
		return redirect(url_for('sgc.usuarios'))
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE GERENCIAMENTO DE VÍDEOS
@sgc.route('videos/')
@sgc.route('videos', methods=['GET'])
def videos():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('vídeos'):
		pagina = 1
		registros = video_dao.tamanho_ativo(True)
		quantidade = 30
		paginas = math.ceil(registros / quantidade)
		if not request.args: # LISTAR A PRIMEIRA PÁGINA DOS VÍDEOS
			tuplas = video_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Ainda não há vídeos cadastrados.')
		elif request.args.get('p') and request.args.get('p').isnumeric(): # LISTAR OS VÍDEOS DE UMA DETERMINADA PÁGINA
			pagina = min(int(request.args.get('p')), registros)
			tuplas = video_dao.listar_intervalo(quantidade * (pagina - 1), quantidade)
		elif request.args.get('id') and request.args.get('id').isnumeric(): # EXIBIR OS DADOS DE UM VÍDEO
			tupla = video_dao.procurar_id(request.args.get('id'))
			if not tupla:
				return redirect(url_for('sgc.videos'))
			return render_template('sgc/videos/index.html', permissao=session['permissao'], tupla=tupla)
		else:
			paginas = 0
			if request.args.get('tipo') == 'id':
				pk = request.args.get('pesquisa')
				tupla = video_dao.procurar_id(pk)
				tuplas = [tupla] if tupla else None
			elif request.args.get('tipo') == 'titulo':
				titulo = request.args.get('pesquisa')
				tuplas = video_dao.procurar_titulos(titulo)
			elif request.args.get('tipo') == 'url':
				url = request.args.get('pesquisa')
				tuplas = video_dao.procurar_urls(url)
			else:
				paginas = math.ceil(registros / quantidade)
				tuplas = video_dao.listar_intervalo(0, quantidade)
			if not tuplas:
				flash('warning')
				flash('Não foi encontrado nenhum vídeo.')
		return render_template('sgc/videos/index.html', pagina=pagina, paginas=paginas, permissao=session['permissao'], tuplas=tuplas)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE CADASTRO DE VÍDEO
@sgc.route('/videos/inserir/')
@sgc.route('/videos/inserir', methods=['GET', 'POST'])
def inserir_video():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('vídeos'):
		video = Video(ativo=True)
		if request.method == 'POST':
			mensagem = []
			video.titulo = request.form['titulo']
			video.url = request.form['url']
			if not video.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not video.url: # INFORMOU UMA URL INVÁLIDO
				mensagem.append('URL inválida')
			elif video_dao.procurar_url(video.url): # INFORMOU UMA URL JÁ CADASTRADA
				mensagem.append('URL pertence a outro vídeo')
			if not mensagem:
				video.usuario = Usuario(id=json.loads(session['usuario'])['id'])
				video_dao.inserir(video)
				flash('success')
				flash('Vídeo cadastrado com sucesso.')
				registrar('INSERÇÃO: VÍDEO ' + str(video_dao.procurar_ultimo().id))
				video = Video()
			else:
				flash('danger')
				flash('Não foi possível cadastrar o vídeo. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		return render_template('sgc/videos/inserir.html', permissao=session['permissao'], video=video)
	else:
		return redirect(url_for('sgc.index'))

# PÁGINA DE ALTERAÇÃO DE VÍDEO
@sgc.route('/videos/alterar', methods=['GET', 'POST'])
def alterar_video():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('vídeos'):
		video = Video(ativo=True)
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			video = video_dao.procurar_id(pk)
			if not video:
				return redirect(url_for('sgc.videos'))
			session['id'] = pk
		elif request.method == 'POST':
			mensagem = []
			anterior = video_dao.procurar_id(session['id'])
			video.id = anterior.id
			video.titulo = request.form['titulo']
			video.url = request.form['url']
			if not video.titulo: # INFORMOU UM TÍTULO INVÁLIDO
				mensagem.append('título inválido')
			if not video.url: # INFORMOU UMA URL INVÁLIDO
				mensagem.append('URL inválida')
			elif anterior.url != video.url and video_dao.procurar_url(video.url): # INFORMOU UMA URL JÁ CADASTRADA
				mensagem.append('URL pertence a outro vídeo')
			if not mensagem:
				video_dao.alterar(video)
				flash('success')
				flash('Vídeo alterado com sucesso.')
				registrar('ALTERAÇÃO: VÍDEO ' + str(video.id))
			else:
				flash('danger')
				flash('Não foi possível alterar o vídeo. ' + ('Problema: ' if len(mensagem) == 1 else 'Problemas: ') + ', '.join(mensagem) + '.')
		else:
			return redirect(url_for('sgc.videos'))
		return render_template('sgc/videos/alterar.html', permissao=session['permissao'], video=video)
	else:
		return redirect(url_for('sgc.index'))

# FUNÇÃO DE REMOÇÃO DE VÍDEO
@sgc.route('/videos/remover', methods=['GET'])
def remover_video():
	if not validar_conexao():
		return render_template('manutencao.html')
	if validar_sessao() and validar_permissao('vídeos'):
		if request.method == 'GET' and request.args.get('id') and request.args.get('id').isnumeric():
			pk = int(request.args.get('id'))
			if video_dao.desativar(pk):
				flash('success')
				flash('Vídeo removido com sucesso.')
				registrar('REMOÇÃO: VÍDEO ' + str(pk))
			else:
				flash('danger')
				flash('Não foi possível remover o vídeo.')
		return redirect(url_for('sgc.videos'))
	else:
		return redirect(url_for('sgc.index'))
