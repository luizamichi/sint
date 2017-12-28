from sinteemar.models.edital import Edital
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class EditalDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela EDITAIS
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

	def unica(self, tupla: dict) -> Edital|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		edital = Edital()
		edital.id = tupla['ID']
		edital.titulo = tupla['TITULO']
		edital.descricao = tupla['DESCRICAO']
		edital.imagem = tupla['IMAGEM']
		edital.usuario = usuario
		edital.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		edital.ativo = tupla['ATIVO']
		return self.ativo(edital)

	def varias(self, tuplas: list[dict]) -> list[Edital]:
		editais = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			edital = Edital()
			edital.id = tupla['ID']
			edital.titulo = tupla['TITULO']
			edital.descricao = tupla['DESCRICAO']
			edital.imagem = tupla['IMAGEM']
			edital.usuario = usuario
			edital.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			edital.ativo = tupla['ATIVO']
			editais.append(edital)
		return self.ativos(editais)

	def ativo(self, edital: Edital) -> Edital|None:
		if isinstance(edital, Edital) and edital.ativo:
			return edital
		return None

	def ativos(self, editais: list[Edital]) -> list[Edital]:
		if editais:
			for edital in editais[:]:
				if not edital.ativo:
					editais.remove(edital)
		return editais

	def inativo(self, edital: Edital) -> Edital|None:
		if isinstance(edital, Edital) and not edital.ativo:
			return edital
		return None

	def inativos(self, editais: list[Edital]) -> list[Edital]:
		if editais:
			for edital in editais[:]:
				if edital.ativo:
					editais.remove(edital)
		return editais

	def procurar_data(self, data: str) -> list[Edital]:
		query = 'SELECT * FROM EDITAIS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_descricao(self, descricao: str) -> list[Edital]:
		query = 'SELECT * FROM EDITAIS WHERE DESCRICAO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, descricao)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_imagem(self, imagem: str) -> Edital|None:
		query = 'SELECT * FROM EDITAIS WHERE IMAGEM=%s'
		self._database.cursor.execute(query, imagem)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagens(self, imagens: str) -> list[Edital]:
		query = 'SELECT * FROM EDITAIS WHERE IMAGEM LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, imagens)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Edital|None:
		query = 'SELECT * FROM EDITAIS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulo(self, titulo: str) -> Edital|None:
		query = 'SELECT * FROM EDITAIS WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Edital]:
		query = 'SELECT * FROM EDITAIS WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Edital|None:
		query = 'SELECT * FROM EDITAIS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Edital]:
		query = 'SELECT * FROM EDITAIS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Edital]:
		query = 'SELECT * FROM EDITAIS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Edital]:
		query = 'SELECT * FROM EDITAIS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM EDITAIS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM EDITAIS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM EDITAIS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM EDITAIS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, edital: Edital) -> int:
		query = 'INSERT INTO EDITAIS (TITULO, DESCRICAO, IMAGEM, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (edital.titulo, edital.descricao, edital.imagem.arquivo, edital.usuario.id, edital.data, edital.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, edital: Edital) -> int:
		query = 'UPDATE EDITAIS SET TITULO=%s, DESCRICAO=%s, IMAGEM=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (edital.titulo, edital.descricao, edital.imagem.arquivo, edital.data, edital.ativo, edital.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM EDITAIS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE EDITAIS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE EDITAIS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

edital_dao = EditalDAO(database)
