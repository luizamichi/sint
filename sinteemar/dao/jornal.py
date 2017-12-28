from sinteemar.models.jornal import Jornal
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class JornalDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela JORNAIS
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

	def unica(self, tupla: dict) -> Jornal|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		jornal = Jornal()
		jornal.id = tupla['ID']
		jornal.titulo = tupla['TITULO']
		jornal.edicao = tupla['EDICAO']
		jornal.documento = tupla['DOCUMENTO']
		if tupla['IMAGEM']:
			jornal.imagem = tupla['IMAGEM']
		jornal.usuario = usuario
		jornal.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		jornal.ativo = tupla['ATIVO']
		return self.ativo(jornal)

	def varias(self, tuplas: list[dict]) -> list[Jornal]:
		jornais = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			jornal = Jornal()
			jornal.id = tupla['ID']
			jornal.titulo = tupla['TITULO']
			jornal.edicao = tupla['EDICAO']
			jornal.documento = tupla['DOCUMENTO']
			if tupla['IMAGEM']:
				jornal.imagem = tupla['IMAGEM']
			jornal.usuario = usuario
			jornal.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			jornal.ativo = tupla['ATIVO']
			jornais.append(jornal)
		return self.ativos(jornais)

	def ativo(self, jornal: Jornal) -> Jornal|None:
		if isinstance(jornal, Jornal) and jornal.ativo:
			return jornal
		return None

	def ativos(self, jornais: list[Jornal]) -> list[Jornal]:
		if jornais:
			for jornal in jornais[:]:
				if not jornal.ativo:
					jornais.remove(jornal)
		return jornais

	def inativo(self, jornal: Jornal) -> Jornal|None:
		if isinstance(jornal, Jornal) and not jornal.ativo:
			return jornal
		return None

	def inativos(self, jornais: list[Jornal]) -> list[Jornal]:
		if jornais:
			for jornal in jornais[:]:
				if jornal.ativo:
					jornais.remove(jornal)
		return jornais

	def procurar_data(self, data: str) -> list[Jornal]:
		query = 'SELECT * FROM JORNAIS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_documento(self, documento: str) -> Jornal|None:
		query = 'SELECT * FROM JORNAIS WHERE DOCUMENTO=%s'
		self._database.cursor.execute(query, documento)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_documentos(self, documentos: str) -> list[Jornal]:
		query = 'SELECT * FROM JORNAIS WHERE DOCUMENTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, documentos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_edicao(self, edicao: str) -> Jornal|None:
		query = 'SELECT * FROM JORNAIS WHERE EDICAO=%s'
		self._database.cursor.execute(query, edicao)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagem(self, imagem: str) -> Jornal|None:
		query = 'SELECT * FROM JORNAIS WHERE IMAGEM=%s'
		self._database.cursor.execute(query, imagem)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagens(self, imagens: str) -> list[Jornal]:
		query = 'SELECT * FROM JORNAIS WHERE IMAGEM LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, imagens)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Jornal|None:
		query = 'SELECT * FROM JORNAIS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulo(self, titulo: str) -> Jornal|None:
		query = 'SELECT * FROM JORNAIS WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Jornal]:
		query = 'SELECT * FROM JORNAIS WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Jornal|None:
		query = 'SELECT * FROM JORNAIS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Jornal]:
		query = 'SELECT * FROM JORNAIS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Jornal]:
		query = 'SELECT * FROM JORNAIS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Jornal]:
		query = 'SELECT * FROM JORNAIS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM JORNAIS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM JORNAIS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM JORNAIS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM JORNAIS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, jornal: Jornal) -> int:
		query = 'INSERT INTO JORNAIS (TITULO, EDICAO, DOCUMENTO, IMAGEM, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (jornal.titulo, jornal.edicao, jornal.documento.arquivo, (jornal.imagem.arquivo if jornal.imagem else jornal.imagem), jornal.usuario.id, jornal.data, jornal.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, jornal: Jornal) -> int:
		query = 'UPDATE JORNAIS SET TITULO=%s, EDICAO=%s, DOCUMENTO=%s, IMAGEM=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (jornal.titulo, jornal.edicao, jornal.documento.arquivo, (jornal.imagem.arquivo if jornal.imagem else jornal.imagem), jornal.data, jornal.ativo, jornal.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM JORNAIS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE JORNAIS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE JORNAIS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

jornal_dao = JornalDAO(database)
