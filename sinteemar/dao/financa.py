from sinteemar.models.financa import Financa
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class FinancaDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela FINANCAS
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

	def unica(self, tupla: dict) -> Financa|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		financa = Financa()
		financa.id = tupla['ID']
		financa.flag = tupla['PERIODO']
		financa.documento = tupla['DOCUMENTO']
		financa.usuario = usuario
		financa.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		financa.ativo = tupla['ATIVO']
		return self.ativo(financa)

	def varias(self, tuplas: list[dict]) -> list[Financa]:
		financas = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			financa = Financa()
			financa.id = tupla['ID']
			financa.flag = tupla['PERIODO']
			financa.documento = tupla['DOCUMENTO']
			financa.usuario = usuario
			financa.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			financa.ativo = tupla['ATIVO']
			financas.append(financa)
		return self.ativos(financas)

	def ativo(self, financa: Financa) -> Financa|None:
		if isinstance(financa, Financa) and financa.ativo:
			return financa
		return None

	def ativos(self, financas: list[Financa]) -> list[Financa]:
		if financas:
			for financa in financas[:]:
				if not financa.ativo:
					financas.remove(financa)
		return financas

	def inativo(self, financa: Financa) -> Financa|None:
		if isinstance(financa, Financa) and not financa.ativo:
			return financa
		return None

	def inativos(self, financas: list[Financa]) -> list[Financa]:
		if financas:
			for financa in financas[:]:
				if financa.ativo:
					financas.remove(financa)
		return financas

	def procurar_data(self, data: str) -> list[Financa]:
		query = 'SELECT * FROM FINANCAS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_documento(self, documento: str) -> Financa|None:
		query = 'SELECT * FROM FINANCAS WHERE DOCUMENTO=%s'
		self._database.cursor.execute(query, documento)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_documentos(self, documentos: str) -> list[Financa]:
		query = 'SELECT * FROM FINANCAS WHERE DOCUMENTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, documentos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Financa|None:
		query = 'SELECT * FROM FINANCAS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_periodo(self, periodo: str) -> list[Financa]:
		query = 'SELECT * FROM FINANCAS WHERE PERIODO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, periodo)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Financa|None:
		query = 'SELECT * FROM FINANCAS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Financa]:
		query = 'SELECT * FROM FINANCAS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Financa]:
		query = 'SELECT * FROM FINANCAS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Financa]:
		query = 'SELECT * FROM FINANCAS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM FINANCAS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM FINANCAS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM FINANCAS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_periodo(self, periodo: bool) -> str:
		query = 'SELECT COUNT(*) FROM FINANCAS WHERE PERIODO=%s'
		self._database.cursor.execute(query, periodo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM FINANCAS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, financa: Financa) -> int:
		query = 'INSERT INTO FINANCAS (PERIODO, DOCUMENTO, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (financa.flag, financa.documento.arquivo, financa.usuario.id, financa.data, financa.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, financa: Financa) -> int:
		query = 'UPDATE FINANCAS SET PERIODO=%s, DOCUMENTO=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (financa.flag, financa.documento.arquivo, financa.data, financa.ativo, financa.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM FINANCAS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE FINANCAS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE FINANCAS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

financa_dao = FinancaDAO(database)
