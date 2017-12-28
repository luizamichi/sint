import os
from datetime import datetime
from flask import Flask
from sinteemar.controllers import sgc, website

def iniciar() -> Flask:
	app = Flask(__name__)
	app.config.from_pyfile('config.py')
	# Website
	website.configure(app)
	# SGC
	sgc.configure(app)
	return app

def criar() -> bool:
	diretorios = ['sinteemar/static/uploads/banner', 'sinteemar/static/uploads/boletim', 'sinteemar/static/uploads/convencao', 'sinteemar/static/uploads/convenio', 'sinteemar/static/uploads/diretoria', 'sinteemar/static/uploads/edital', 'sinteemar/static/uploads/estatuto', 'sinteemar/static/uploads/evento', 'sinteemar/static/uploads/financa', 'sinteemar/static/uploads/historico', 'sinteemar/static/uploads/jornal', 'sinteemar/static/uploads/juridico', 'sinteemar/static/uploads/noticia']
	for diretorio in diretorios:
		if not os.path.exists(diretorio):
			os.mkdir(diretorio)
	print('Os diretórios foram criados.')
	from sinteemar.db.database import database
	if not database.cursor:
		print('Problema com o banco de dados. Verifique se foi criado e se os dados de acesso no arquivo "config.py" estão corretos.')
		return False
	else:
		data = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
		if not database.query('SELECT * FROM PERMISSOES'):
			database.query('INSERT INTO PERMISSOES(`ID`, `ADMIN`, `ACESSOS`, `BANNERS`, `BOLETINS`, `CONVENCOES`, `CONVENIOS`, `DIRETORIA`, `DIRETORIO`, `EDITAIS`, `ESTATUTO`, `EVENTOS`, `FINANCAS`, `HISTORICO`, `JORNAIS`, `JURIDICOS`, `NOTICIAS`, `REGISTROS`, `TABELAS`, `USUARIOS`, `VIDEOS`) VALUES (1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);')
		if not database.query('SELECT * FROM USUARIOS'):
			database.query('INSERT INTO USUARIOS(`ID`, `NOME`, `EMAIL`, `LOGIN`, `SENHA`, `PERMISSAO`, `DATA`, `ATIVO`) VALUES (1, "Luiz Amichi", "eu@luizamichi.com.br", "luizamichi", MD5("luizamichi"), 1, "' + data + '", 1);')
		if not database.query('SELECT * FROM DIRETORIA'):
			database.query('INSERT INTO DIRETORIA(`ID`, `TITULO`, `TEXTO`, `IMAGEM`, `USUARIO`, `DATA`) VALUES (1, "", "", NULL, 1, "' + data + '");')
		if not database.query('SELECT * FROM ESTATUTO'):
			database.query('INSERT INTO ESTATUTO(`ID`, `DOCUMENTO`, `USUARIO`, `DATA`) VALUES (1, "Enviar documento", 1, "' + data + '");')
	print('Banco de dados configurado.')
	return True

def validar() -> bool:
	diretorios = {'sinteemar/static/uploads': 'Diretório de uploads ausente.', 'sinteemar/static/uploads/banner': 'Diretório de uploads de banners ausente.', 'sinteemar/static/uploads/boletim': 'Diretório de uploads de boletins ausente.', 'sinteemar/static/uploads/convencao': 'Diretório de uploads de convenções ausente.', 'sinteemar/static/uploads/convenio': 'Diretório de uploads de convênios ausente.', 'sinteemar/static/uploads/diretoria': 'Diretório de uploads de diretoria ausente.', 'sinteemar/static/uploads/edital': 'Diretório de uploads de editais ausente.', 'sinteemar/static/uploads/estatuto': 'Diretório de uploads de estatuto ausente.', 'sinteemar/static/uploads/evento': 'Diretório de uploads de eventos ausente.', 'sinteemar/static/uploads/financa': 'Diretório de uploads de finanças ausente.', 'sinteemar/static/uploads/historico': 'Diretório de uploads de histórico ausente.', 'sinteemar/static/uploads/jornal': 'Diretório de uploads de jornais ausente.', 'sinteemar/static/uploads/juridico': 'Diretório de uploads de jurídicos ausente.', 'sinteemar/static/uploads/noticia': 'Diretório de uploads de notícias ausente.'}
	erro_diretorio = False
	for diretorio in diretorios:
		if not os.path.exists(diretorio):
			erro_diretorio = True
			print(diretorios[diretorio])
	if not erro_diretorio:
		print('Não foi encontrado nenhum erro de diretório.')
	from sinteemar.db.database import database
	erro_db = False
	if not database.cursor:
		print('Problema com o banco de dados. Verifique se foi criado e se os dados de acesso no arquivo "config.py" estão corretos.')
		return False
	if not database.query('SELECT * FROM USUARIOS, PERMISSOES WHERE USUARIOS.ID=1 AND PERMISSOES.ID=1 AND PERMISSOES.ADMIN=1;'):
		erro_db = True
		print('Não há conta de administrador registrada no banco de dados.')
	if not database.query('SELECT * FROM DIRETORIA;'):
		erro_db = True
		print('Não há registro de diretoria cadastrado no banco de dados.')
	if not database.query('SELECT * FROM ESTATUTO;'):
		erro_db = True
		print('Não há registro de estatuto cadastrado no banco de dados.')
	if not erro_db:
		print('Não foi encontrado nenhum erro no banco de dados.')
	if erro_diretorio or erro_db:
		return False
	return True
