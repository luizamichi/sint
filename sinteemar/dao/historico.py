from sinteemar.models.historico import Historico
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class HistoricoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela HISTORICO
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

	def unica(self, tupla: dict) -> Historico|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		historico = Historico()
		historico.id = tupla['ID']
		historico.titulo = tupla['TITULO']
		historico.documento = tupla['DOCUMENTO']
		historico.usuario = usuario
		historico.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		historico.ativo = tupla['ATIVO']
		return self.ativo(historico)

	def varias(self, tuplas: list[dict]) -> list[Historico]:
		historicos = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			historico = Historico()
			historico.id = tupla['ID']
			historico.titulo = tupla['TITULO']
			historico.documento = tupla['DOCUMENTO']
			historico.usuario = usuario
			historico.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			historico.ativo = tupla['ATIVO']
			historicos.append(historico)
		return self.ativos(historicos)

	def ativo(self, historico: Historico) -> Historico|None:
		if isinstance(historico, Historico) and historico.ativo:
			return historico
		return None

	def ativos(self, historicos: list[Historico]) -> list[Historico]:
		if historicos:
			for historico in historicos[:]:
				if not historico.ativo:
					historicos.remove(historico)
		return historicos

	def inativo(self, historico: Historico) -> Historico|None:
		if isinstance(historico, Historico) and not historico.ativo:
			return historico
		return None

	def inativos(self, historicos: list[Historico]) -> list[Historico]:
		if historicos:
			for historico in historicos[:]:
				if historico.ativo:
					historicos.remove(historico)
		return historicos

	def procurar_data(self, data: str) -> list[Historico]:
		query = 'SELECT * FROM HISTORICO WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_documento(self, documento: str) -> Historico|None:
		query = 'SELECT * FROM HISTORICO WHERE DOCUMENTO=%s'
		self._database.cursor.execute(query, documento)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_documentos(self, documentos: str) -> list[Historico]:
		query = 'SELECT * FROM HISTORICO WHERE DOCUMENTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, documentos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Historico|None:
		query = 'SELECT * FROM HISTORICO WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulo(self, titulo: str) -> Historico|None:
		query = 'SELECT * FROM HISTORICO WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Historico]:
		query = 'SELECT * FROM HISTORICO WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Historico|None:
		query = 'SELECT * FROM HISTORICO ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Historico]:
		query = 'SELECT * FROM HISTORICO WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Historico]:
		query = 'SELECT * FROM HISTORICO WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Historico]:
		query = 'SELECT * FROM HISTORICO WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM HISTORICO'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM HISTORICO WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM HISTORICO WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM HISTORICO WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, historico: Historico) -> int:
		query = 'INSERT INTO HISTORICO (TITULO, DOCUMENTO, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (historico.titulo, historico.documento.arquivo, historico.usuario.id, historico.data, historico.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, historico: Historico) -> int:
		query = 'UPDATE HISTORICO SET TITULO=%s, DOCUMENTO=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (historico.titulo, historico.documento.arquivo, historico.data, historico.ativo, historico.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM HISTORICO WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE HISTORICO SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE HISTORICO SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

historico_dao = HistoricoDAO(database)
