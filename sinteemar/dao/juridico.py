from sinteemar.models.juridico import Juridico
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class JuridicoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela JURIDICOS
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

	def unica(self, tupla: dict) -> Juridico|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		juridico = Juridico()
		juridico.id = tupla['ID']
		juridico.titulo = tupla['TITULO']
		juridico.descricao = tupla['DESCRICAO']
		juridico.documento = tupla['DOCUMENTO']
		juridico.usuario = usuario
		juridico.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		juridico.ativo = tupla['ATIVO']
		return self.ativo(juridico)

	def varias(self, tuplas: list[dict]) -> list[Juridico]:
		juridicos = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			juridico = Juridico()
			juridico.id = tupla['ID']
			juridico.titulo = tupla['TITULO']
			juridico.descricao = tupla['DESCRICAO']
			juridico.documento = tupla['DOCUMENTO']
			juridico.usuario = usuario
			juridico.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			juridico.ativo = tupla['ATIVO']
			juridicos.append(juridico)
		return self.ativos(juridicos)

	def ativo(self, juridico: Juridico) -> Juridico|None:
		if isinstance(juridico, Juridico) and juridico.ativo:
			return juridico
		return None

	def ativos(self, juridicos: list[Juridico]) -> list[Juridico]:
		if juridicos:
			for juridico in juridicos[:]:
				if not juridico.ativo:
					juridicos.remove(juridico)
		return juridicos

	def inativo(self, juridico: Juridico) -> Juridico|None:
		if isinstance(juridico, Juridico) and not juridico.ativo:
			return juridico
		return None

	def inativos(self, juridicos: list[Juridico]) -> list[Juridico]:
		if juridicos:
			for juridico in juridicos[:]:
				if juridico.ativo:
					juridicos.remove(juridico)
		return juridicos

	def procurar_data(self, data: str) -> list[Juridico]:
		query = 'SELECT * FROM JURIDICOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_descricao(self, descricao: str) -> list[Juridico]:
		query = 'SELECT * FROM JURIDICOS WHERE DESCRICAO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, descricao)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_documento(self, documento: str) -> Juridico|None:
		query = 'SELECT * FROM JURIDICOS WHERE DOCUMENTO=%s'
		self._database.cursor.execute(query, documento)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_documentos(self, documento: str) -> list[Juridico]:
		query = 'SELECT * FROM JURIDICOS WHERE DOCUMENTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, documento)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Juridico|None:
		query = 'SELECT * FROM JURIDICOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulo(self, titulo: str) -> Juridico|None:
		query = 'SELECT * FROM JURIDICOS WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Juridico]:
		query = 'SELECT * FROM JURIDICOS WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Juridico|None:
		query = 'SELECT * FROM JURIDICOS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Juridico]:
		query = 'SELECT * FROM JURIDICOS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Juridico]:
		query = 'SELECT * FROM JURIDICOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Juridico]:
		query = 'SELECT * FROM JURIDICOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM JURIDICOS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM JURIDICOS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM JURIDICOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM JURIDICOS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, juridico: Juridico) -> int:
		query = 'INSERT INTO JURIDICOS (TITULO, DESCRICAO, DOCUMENTO, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (juridico.titulo, juridico.descricao, juridico.documento.arquivo, juridico.usuario.id, juridico.data, juridico.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, juridico: Juridico) -> int:
		query = 'UPDATE JURIDICOS SET TITULO=%s, DESCRICAO=%s, DOCUMENTO=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (juridico.titulo, juridico.descricao, juridico.documento.arquivo, juridico.data, juridico.ativo, juridico.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM JURIDICOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE JURIDICOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE JURIDICOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

juridico_dao = JuridicoDAO(database)
