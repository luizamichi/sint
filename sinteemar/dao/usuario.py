from sinteemar.models.usuario import Permissao, Usuario
from sinteemar.db.database import database, Database
from sinteemar.dao.permissao import permissao_dao

class UsuarioDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela USUARIOS
	'''
	__slots__ = ['_database']

	def __init__(self, database: Database=Database()):
		self._database = database

	def __str__(self) -> str:
		return 'SGBD: ' + ('CONECTADO' if self._database.conexao else 'DESCONECTADO')

	@property
	def database(self) -> Database:
		return self._database

	@database.setter
	def database(self, database: Database) -> bool:
		if database.conexao:
			self._database = database
			return True
		return False

	def unica(self, tupla: dict) -> Usuario|None:
		if not tupla:
			return None
		permissao = permissao_dao.procurar_id(tupla['PERMISSAO'])
		usuario = Usuario()
		usuario.id = tupla['ID']
		usuario.nome = tupla['NOME']
		usuario.email = tupla['EMAIL']
		usuario.login = tupla['LOGIN']
		usuario.senha = tupla['SENHA']
		usuario.permissao = permissao
		usuario.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		usuario.ativo = tupla['ATIVO']
		return self.ativo(usuario)

	def varias(self, tuplas: list[dict]) -> list[Usuario]:
		usuarios = []
		for tupla in tuplas:
			permissao = permissao_dao.procurar_id(tupla['PERMISSAO'])
			usuario = Usuario()
			usuario.id = tupla['ID']
			usuario.nome = tupla['NOME']
			usuario.email = tupla['EMAIL']
			usuario.login = tupla['LOGIN']
			usuario.senha = tupla['SENHA']
			usuario.permissao = permissao
			usuario.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			usuario.ativo = tupla['ATIVO']
			usuarios.append(usuario)
		return self.ativos(usuarios)

	def ativo(self, usuario: Usuario) -> Usuario|None:
		if isinstance(usuario, Usuario) and usuario.ativo:
			return usuario
		return None

	def ativos(self, usuarios: list[Usuario]) -> list[Usuario]:
		if usuarios:
			for usuario in usuarios[:]:
				if not usuario.ativo:
					usuarios.remove(usuario)
		return usuarios

	def inativo(self, usuario: Usuario) -> Usuario|None:
		if isinstance(usuario, Usuario) and not usuario.ativo:
			return usuario
		return None

	def inativos(self, usuarios: list[Usuario]) -> list[Usuario]:
		if usuarios:
			for usuario in usuarios[:]:
				if usuario.ativo:
					usuarios.remove(usuario)
		return usuarios

	def procurar_data(self, data: str) -> list[Usuario]:
		query = 'SELECT * FROM USUARIOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_email(self, email: str) -> Usuario|None:
		query = 'SELECT * FROM USUARIOS WHERE EMAIL=%s'
		self._database.cursor.execute(query, email)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_emails(self, emails: str) -> list[Usuario]:
		query = 'SELECT * FROM USUARIOS WHERE EMAIL LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, emails)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Usuario|None:
		query = 'SELECT * FROM USUARIOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_login(self, login: str) -> Usuario|None:
		query = 'SELECT * FROM USUARIOS WHERE LOGIN=%s'
		self._database.cursor.execute(query, login)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_logins(self, logins: str) -> list[Usuario]:
		query = 'SELECT * FROM USUARIOS WHERE LOGIN LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, logins)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_nome(self, nome: str) -> Usuario|None:
		query = 'SELECT * FROM USUARIOS WHERE NOME=%s'
		self._database.cursor.execute(query, nome)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_nomes(self, nomes: str) -> list[Usuario]:
		query = 'SELECT * FROM USUARIOS WHERE NOME LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, nomes)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Usuario|None:
		query = 'SELECT * FROM USUARIOS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Usuario]:
		query = 'SELECT * FROM USUARIOS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Usuario]:
		query = 'SELECT * FROM USUARIOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Usuario]:
		query = 'SELECT * FROM USUARIOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM USUARIOS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM USUARIOS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM USUARIOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, usuario: Usuario) -> int:
		query = 'INSERT INTO USUARIOS (ID, NOME, EMAIL, LOGIN, SENHA, PERMISSAO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)'
		permissao_dao.inserir(usuario.permissao)
		usuario.permissao = permissao_dao.procurar_ultimo()
		self._database.cursor.execute(query, (usuario.id, usuario.nome, usuario.email, usuario.login, usuario.senha, usuario.permissao.id, usuario.data, usuario.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, usuario: Usuario) -> int:
		query = 'UPDATE USUARIOS SET NOME=%s, EMAIL=%s, LOGIN=%s, SENHA=%s, PERMISSAO=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		permissao_dao.alterar(usuario.permissao)
		self._database.cursor.execute(query, (usuario.nome, usuario.email, usuario.login, usuario.senha, usuario.permissao.id, usuario.data, usuario.ativo, usuario.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar_senha(self, senha: str, id: int) -> int:
		query = 'UPDATE USUARIOS SET SENHA=%s WHERE ID=%s'
		self._database.cursor.execute(query, (senha, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM USUARIOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE USUARIOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE USUARIOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

usuario_dao = UsuarioDAO(database)
