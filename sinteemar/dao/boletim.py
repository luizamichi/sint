from sinteemar.models.boletim import Boletim
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class BoletimDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela BOLETINS
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

	def unica(self, tupla: dict) -> Boletim|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		boletim = Boletim()
		boletim.id = tupla['ID']
		boletim.titulo = tupla['TITULO']
		boletim.documento = tupla['DOCUMENTO']
		boletim.imagem = tupla['IMAGEM']
		boletim.usuario = usuario
		boletim.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		boletim.ativo = tupla['ATIVO']
		return self.ativo(boletim)

	def varias(self, tuplas: list[dict]) -> list[Boletim]:
		boletins = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			boletim = Boletim()
			boletim.id = tupla['ID']
			boletim.titulo = tupla['TITULO']
			boletim.documento = tupla['DOCUMENTO']
			boletim.imagem = tupla['IMAGEM']
			boletim.usuario = usuario
			boletim.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			boletim.ativo = tupla['ATIVO']
			boletins.append(boletim)
		return self.ativos(boletins)

	def ativo(self, boletim: Boletim) -> Boletim|None:
		if isinstance(boletim, Boletim) and boletim.ativo:
			return boletim
		return None

	def ativos(self, boletins: list[Boletim]) -> list[Boletim]:
		if boletins:
			for boletim in boletins[:]:
				if not boletim.ativo:
					boletins.remove(boletim)
		return boletins

	def inativo(self, boletim: Boletim) -> Boletim|None:
		if isinstance(boletim, Boletim) and not boletim.ativo:
			return boletim
		return None

	def inativos(self, boletins: list[Boletim]) -> list[Boletim]:
		if boletins:
			for boletim in boletins[:]:
				if boletim.ativo:
					boletins.remove(boletim)
		return boletins

	def procurar_data(self, data: str) -> list[Boletim]:
		query = 'SELECT * FROM BOLETINS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_documento(self, documento: str) -> Boletim|None:
		query = 'SELECT * FROM BOLETINS WHERE DOCUMENTO=%s'
		self._database.cursor.execute(query, documento)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_documentos(self, documentos: str) -> list[Boletim]:
		query = 'SELECT * FROM BOLETINS WHERE DOCUMENTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, documentos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_imagem(self, imagem: str) -> Boletim|None:
		query = 'SELECT * FROM BOLETINS WHERE IMAGEM=%s'
		self._database.cursor.execute(query, imagem)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagens(self, imagens: str) -> list[Boletim]:
		query = 'SELECT * FROM BOLETINS WHERE IMAGEM LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, imagens)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Boletim|None:
		query = 'SELECT * FROM BOLETINS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulo(self, titulo: str) -> Boletim|None:
		query = 'SELECT * FROM BOLETINS WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Boletim]:
		query = 'SELECT * FROM BOLETINS WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Boletim|None:
		query = 'SELECT * FROM BOLETINS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Boletim]:
		query = 'SELECT * FROM BOLETINS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Boletim]:
		query = 'SELECT * FROM BOLETINS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Boletim]:
		query = 'SELECT * FROM BOLETINS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM BOLETINS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM BOLETINS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM BOLETINS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM BOLETINS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, boletim: Boletim) -> int:
		query = 'INSERT INTO BOLETINS (TITULO, DOCUMENTO, IMAGEM, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (boletim.titulo, boletim.documento.arquivo, (boletim.imagem.arquivo if boletim.imagem else boletim.imagem), boletim.usuario.id, boletim.data, boletim.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, boletim: Boletim) -> int:
		query = 'UPDATE BOLETINS SET TITULO=%s, DOCUMENTO=%s, IMAGEM=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (boletim.titulo, boletim.documento.arquivo, (boletim.imagem.arquivo if boletim.imagem else boletim.imagem), boletim.data, boletim.ativo, boletim.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM BOLETINS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE BOLETINS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE BOLETINS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

boletim_dao = BoletimDAO(database)
