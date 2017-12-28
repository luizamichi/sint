from sinteemar.models.arquivo import Arquivo
from sinteemar.db.database import database, Database

class ArquivoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela ARQUIVOS
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

	def unica(self, tupla: dict) -> Arquivo|None:
		if not tupla:
			return None
		arquivo = Arquivo()
		arquivo.id = tupla['ID']
		arquivo.nome = tupla['IMAGEM']
		arquivo.evento = tupla['EVENTO']
		arquivo.ativo = tupla['ATIVO']
		return self.ativo(arquivo)

	def varias(self, tuplas: list[dict]) -> list[Arquivo]:
		arquivos = []
		for tupla in tuplas:
			arquivo = Arquivo()
			arquivo.id = tupla['ID']
			arquivo.nome = tupla['IMAGEM']
			arquivo.evento = tupla['EVENTO']
			arquivo.ativo = tupla['ATIVO']
			arquivos.append(arquivo)
		return self.ativos(arquivos)

	def ativo(self, arquivo: Arquivo) -> Arquivo|None:
		if isinstance(arquivo, Arquivo) and arquivo.ativo:
			return arquivo
		return None

	def ativos(self, arquivos: list) -> list[Arquivo]:
		if arquivos:
			for arquivo in arquivos[:]:
				if not arquivo.ativo:
					arquivos.remove(arquivo)
		return arquivos

	def inativo(self, arquivo: Arquivo) -> Arquivo|None:
		if isinstance(arquivo, Arquivo) and not arquivo.ativo:
			return arquivo
		return None

	def inativos(self, arquivos: list[Arquivo]) -> list[Arquivo]:
		if arquivos:
			for arquivo in arquivos[:]:
				if arquivo.ativo:
					arquivos.remove(arquivo)
		return arquivos

	def procurar_evento(self, evento: int) -> list[Arquivo]:
		query = 'SELECT * FROM ARQUIVOS WHERE EVENTO=%s'
		self._database.cursor.execute(query, evento)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_eventos(self, eventos: str) -> list[Arquivo]:
		query = 'SELECT * FROM ARQUIVOS WHERE EVENTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, eventos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Arquivo|None:
		query = 'SELECT * FROM ARQUIVOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagem(self, imagem: str) -> Arquivo|None:
		query = 'SELECT * FROM ARQUIVOS WHERE IMAGEM=%s'
		self._database.cursor.execute(query, imagem)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagens(self, imagens: str) -> list[Arquivo]:
		query = 'SELECT * FROM ARQUIVOS WHERE IMAGEM LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, imagens)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Arquivo|None:
		query = 'SELECT * FROM ARQUIVOS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Arquivo]:
		query = 'SELECT * FROM ARQUIVOS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Arquivo]:
		query = 'SELECT * FROM ARQUIVOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Arquivo]:
		query = 'SELECT * FROM ARQUIVOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM ARQUIVOS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_evento(self, evento: int) -> str:
		query = 'SELECT COUNT(*) FROM ARQUIVOS WHERE EVENTO=%s'
		self._database.cursor.execute(query, evento)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, arquivo: Arquivo) -> int:
		query = 'INSERT INTO ARQUIVOS (IMAGEM, EVENTO, ATIVO) VALUES (%s, %s, %s)'
		self._database.cursor.execute(query, (arquivo.arquivo, arquivo.evento, arquivo.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, arquivo: Arquivo) -> int:
		query = 'UPDATE ARQUIVOS SET IMAGEM=%s, EVENTO=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (arquivo.arquivo, arquivo.evento, arquivo.ativo, arquivo.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM ARQUIVOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE ARQUIVOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE ARQUIVOS SET ATIVO=%s WHERE EVENTO=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

arquivo_dao = ArquivoDAO(database)
