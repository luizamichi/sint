from sinteemar.models.registro import Registro
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class RegistroDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela REGISTROS
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

	def unica(self, tupla: dict) -> Registro|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		registro = Registro()
		registro.id = tupla['ID']
		registro.descricao = tupla['DESCRICAO']
		registro.ip = tupla['IP']
		registro.usuario = usuario
		registro.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		return registro

	def varias(self, tuplas: list[dict]) -> list[Registro]:
		registros = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			registro = Registro()
			registro.id = tupla['ID']
			registro.descricao = tupla['DESCRICAO']
			registro.ip = tupla['IP']
			registro.usuario = usuario
			registro.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			registros.append(registro)
		return registros

	def procurar_data(self, data: str) -> list[Registro]:
		query = 'SELECT * FROM REGISTROS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_descricao(self, descricao: str) -> list[Registro]:
		query = 'SELECT * FROM REGISTROS WHERE DESCRICAO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, descricao)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ip(self, ip: str) -> list[Registro]:
		query = 'SELECT * FROM REGISTROS WHERE IP=%s'
		self._database.cursor.execute(query, ip)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Registro|None:
		query = 'SELECT * FROM REGISTROS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_ultimo(self) -> Registro|None:
		query = 'SELECT * FROM REGISTROS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Registro]:
		query = 'SELECT * FROM REGISTROS ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Registro]:
		query = 'SELECT * FROM REGISTROS ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Registro]:
		query = 'SELECT * FROM REGISTROS ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM REGISTROS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM REGISTROS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ip(self, ip: str) -> str:
		query = 'SELECT COUNT(*) FROM REGISTROS WHERE IP=%s'
		self._database.cursor.execute(query, ip)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM REGISTROS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, registro: Registro) -> int:
		query = 'INSERT INTO REGISTROS (IP, DESCRICAO, USUARIO, DATA) VALUES (%s, %s, %s, %s)'
		self._database.cursor.execute(query, (registro.ip, registro.descricao, registro.usuario.id, registro.data))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, registro: Registro) -> int:
		query = 'UPDATE REGISTROS SET IP=%s, DESCRICAO=%s, DATA=%s WHERE ID=%s'
		self._database.cursor.execute(query, (registro.ip, registro.descricao, registro.data, registro.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM REGISTROS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

registro_dao = RegistroDAO(database)
