from sinteemar.models.acesso import Acesso
from sinteemar.db.database import database, Database

class AcessoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela ACESSOS
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

	def unica(self, tupla: dict) -> Acesso|None:
		if not tupla:
			return None
		acesso = Acesso()
		acesso.id = tupla['ID']
		acesso.ip = tupla['IP']
		acesso.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		return acesso

	def varias(self, tuplas: list) -> list[Acesso]:
		acessos = []
		for tupla in tuplas:
			acesso = Acesso()
			acesso.id = tupla['ID']
			acesso.ip = tupla['IP']
			acesso.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			acessos.append(acesso)
		return acessos

	def procurar_data(self, data: str) -> list[Acesso]:
		query = 'SELECT * FROM ACESSOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Acesso|None:
		query = 'SELECT * FROM ACESSOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_ip(self, ip: str) -> list[Acesso]:
		query = 'SELECT * FROM ACESSOS WHERE IP LIKE CONCAT(\'%%\',%s,\'%%\') ORDER BY ID DESC'
		self._database.cursor.execute(query, ip)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Acesso|None:
		query = 'SELECT * FROM ACESSOS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Acesso]:
		query = 'SELECT * FROM ACESSOS ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Acesso]:
		query = 'SELECT * FROM ACESSOS ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Acesso]:
		query = 'SELECT * FROM ACESSOS ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM ACESSOS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM ACESSOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ip(self, ip: str) -> str:
		query = 'SELECT COUNT(*) FROM ACESSOS WHERE IP=%s'
		self._database.cursor.execute(query, ip)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, acesso: Acesso) -> int:
		query = 'INSERT INTO ACESSOS (IP, DATA) VALUES (%s, %s)'
		self._database.cursor.execute(query, (acesso.ip, acesso.data))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, acesso: Acesso) -> int:
		query = 'UPDATE ACESSOS SET IP=%s, DATA=%s WHERE ID=%s'
		self._database.cursor.execute(query, (acesso.ip, acesso.data, acesso.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM ACESSOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

acesso_dao = AcessoDAO(database)
