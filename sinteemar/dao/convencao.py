from sinteemar.models.convencao import Convencao
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class ConvencaoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela CONVENCOES
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

	def unica(self, tupla: dict) -> Convencao|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		convencao = Convencao()
		convencao.id = tupla['ID']
		convencao.titulo = tupla['TITULO']
		convencao.documento = tupla['DOCUMENTO']
		convencao.tipo = tupla['TIPO']
		convencao.usuario = usuario
		convencao.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		convencao.ativo = tupla['ATIVO']
		return self.ativo(convencao)

	def varias(self, tuplas: list[dict]) -> list[Convencao]:
		convencoes = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			convencao = Convencao()
			convencao.id = tupla['ID']
			convencao.titulo = tupla['TITULO']
			convencao.documento = tupla['DOCUMENTO']
			convencao.tipo = tupla['TIPO']
			convencao.usuario = usuario
			convencao.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			convencao.ativo = tupla['ATIVO']
			convencoes.append(convencao)
		return self.ativos(convencoes)

	def ativo(self, convencao: Convencao) -> Convencao|None:
		if isinstance(convencao, Convencao) and convencao.ativo:
			return convencao
		return None

	def ativos(self, convencoes: list[Convencao]) -> list[Convencao]:
		if convencoes:
			for convencao in convencoes[:]:
				if not convencao.ativo:
					convencoes.remove(convencao)
		return convencoes

	def inativo(self, convencao: Convencao) -> Convencao|None:
		if isinstance(convencao, Convencao) and not convencao.ativo:
			return convencao
		return None

	def inativos(self, convencoes: list[Convencao]) -> list[Convencao]:
		if convencoes:
			for convencao in convencoes[:]:
				if convencao.ativo:
					convencoes.remove(convencao)
		return convencoes

	def procurar_data(self, data: str) -> list[Convencao]:
		query = 'SELECT * FROM CONVENCOES WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_documento(self, documento: str) -> Convencao|None:
		query = 'SELECT * FROM CONVENCOES WHERE DOCUMENTO=%s'
		self._database.cursor.execute(query, documento)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_documentos(self, documentos: str) -> list[Convencao]:
		query = 'SELECT * FROM CONVENCOES WHERE DOCUMENTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, documentos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Convencao|None:
		query = 'SELECT * FROM CONVENCOES WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_tipo(self, tipo: bool) -> list[Convencao]:
		query = 'SELECT * FROM CONVENCOES WHERE TIPO=%s'
		self._database.cursor.execute(query, tipo)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_titulo(self, titulo: str) -> Convencao|None:
		query = 'SELECT * FROM CONVENCOES WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Convencao]:
		query = 'SELECT * FROM CONVENCOES WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Convencao|None:
		query = 'SELECT * FROM CONVENCOES ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Convencao]:
		query = 'SELECT * FROM CONVENCOES WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Convencao]:
		query = 'SELECT * FROM CONVENCOES WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Convencao]:
		query = 'SELECT * FROM CONVENCOES WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM CONVENCOES'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM CONVENCOES WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM CONVENCOES WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_tipo(self, tipo: bool) -> str:
		query = 'SELECT COUNT(*) FROM CONVENCOES WHERE TIPO=%s'
		self._database.cursor.execute(query, tipo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM CONVENCOES WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, convencao: Convencao) -> int:
		query = 'INSERT INTO CONVENCOES (TITULO, DOCUMENTO, TIPO, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (convencao.titulo, convencao.documento.arquivo, convencao.tipo, convencao.usuario.id, convencao.data, convencao.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, convencao: Convencao) -> int:
		query = 'UPDATE CONVENCOES SET TITULO=%s, DOCUMENTO=%s, TIPO=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (convencao.titulo, convencao.documento.arquivo, convencao.tipo, convencao.data, convencao.ativo, convencao.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM CONVENCOES WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE CONVENCOES SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE CONVENCOES SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

convencao_dao = ConvencaoDAO(database)
