from sinteemar.models.estatuto import Estatuto
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class EstatutoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela ESTATUTO
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

	def unica(self, tupla: dict) -> Estatuto|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		estatuto = Estatuto()
		estatuto.id = tupla['ID']
		estatuto.documento = tupla['DOCUMENTO']
		estatuto.usuario = usuario
		estatuto.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		return estatuto

	def varias(self, tuplas: list[dict]) -> list[Estatuto]:
		estatutos = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			estatuto = Estatuto()
			estatuto.id = tupla['ID']
			estatuto.documento = tupla['DOCUMENTO']
			estatuto.usuario = usuario
			estatuto.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			estatutos.append(estatuto)
		return estatutos

	def procurar_data(self, data: str) -> list[Estatuto]:
		query = 'SELECT * FROM ESTATUTO WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_documento(self, documento: str) -> Estatuto|None:
		query = 'SELECT * FROM ESTATUTO WHERE DOCUMENTO=%s'
		self._database.cursor.execute(query, documento)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_id(self, id: int) -> Estatuto|None:
		query = 'SELECT * FROM ESTATUTO WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_ultimo(self) -> Estatuto|None:
		query = 'SELECT * FROM ESTATUTO ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Estatuto]:
		query = 'SELECT * FROM ESTATUTO ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Estatuto]:
		query = 'SELECT * FROM ESTATUTO ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Estatuto]:
		query = 'SELECT * FROM ESTATUTO ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM ESTATUTO'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM ESTATUTO WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, estatuto: Estatuto) -> int:
		query = 'INSERT INTO ESTATUTO (DOCUMENTO, USUARIO, DATA) VALUES (%s, %s, %s)'
		self._database.cursor.execute(query, (estatuto.documento.arquivo, estatuto.usuario.id, estatuto.data))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, estatuto: Estatuto) -> int:
		query = 'UPDATE ESTATUTO SET DOCUMENTO=%s, DATA=%s WHERE ID=%s'
		self._database.cursor.execute(query, (estatuto.documento.arquivo, estatuto.data, estatuto.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM ESTATUTO WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

estatuto_dao = EstatutoDAO(database)
