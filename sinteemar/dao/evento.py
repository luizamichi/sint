from sinteemar.models.evento import Evento
from sinteemar.db.database import database, Database
from sinteemar.dao.arquivo import arquivo_dao
from sinteemar.dao.usuario import usuario_dao

class EventoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela EVENTOS
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

	def unica(self, tupla: dict) -> Evento|None:
		if not tupla:
			return None
		imagens = arquivo_dao.procurar_evento(tupla['ID'])
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		evento = Evento()
		evento.id = tupla['ID']
		evento.titulo = tupla['TITULO']
		evento.descricao = tupla['DESCRICAO']
		evento.diretorio = tupla['DIRETORIO']
		evento.imagens = imagens
		evento.usuario = usuario
		evento.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		evento.ativo = tupla['ATIVO']
		return self.ativo(evento)

	def varias(self, tuplas: list[dict]) -> list[Evento]:
		eventos = []
		for tupla in tuplas:
			imagens = arquivo_dao.procurar_evento(tupla['ID'])
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			evento = Evento()
			evento.id = tupla['ID']
			evento.titulo = tupla['TITULO']
			evento.descricao = tupla['DESCRICAO']
			evento.diretorio = tupla['DIRETORIO']
			evento.imagens = imagens
			evento.usuario = usuario
			evento.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			evento.ativo = tupla['ATIVO']
			eventos.append(evento)
		return self.ativos(eventos)

	def ativo(self, evento: Evento) -> Evento|None:
		if isinstance(evento, Evento) and evento.ativo:
			return evento
		return None

	def ativos(self, eventos: list[Evento]) -> list[Evento]:
		if eventos:
			for evento in eventos[:]:
				if not evento.ativo:
					eventos.remove(evento)
		return eventos

	def inativo(self, evento: Evento) -> Evento|None:
		if isinstance(evento, Evento) and not evento.ativo:
			return evento
		return None

	def procurar_data(self, data: str) -> list[Evento]:
		query = 'SELECT * FROM EVENTOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Evento|None:
		query = 'SELECT * FROM EVENTOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_descricao(self, descricao: str) -> list[Evento]:
		query = 'SELECT * FROM EVENTOS WHERE DESCRICAO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, descricao)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_diretorio(self, diretorio: str) -> Evento|None:
		query = 'SELECT * FROM EVENTOS WHERE DIRETORIO=%s'
		self._database.cursor.execute(query, diretorio)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_diretorios(self, diretorios: str) -> list[Evento]:
		query = 'SELECT * FROM EVENTOS WHERE DIRETORIO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, diretorios)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_imagens(self, imagens: str) -> list[Evento]:
		query = 'SELECT * FROM EVENTOS WHERE ID=%s'
		imagens = arquivo_dao.procurar_imagens(imagens)
		tuplas, pk = [], []
		for imagem in imagens:
			if imagem.evento not in pk:
				pk.append(imagem.evento)
				self._database.cursor.execute(query, imagem.evento)
				tupla = self.unica(self._database.cursor.fetchone())
				if tupla:
					tuplas.append(tupla)
		return tuplas

	def procurar_titulo(self, titulo: str) -> Evento|None:
		query = 'SELECT * FROM EVENTOS WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Evento]:
		query = 'SELECT * FROM EVENTOS WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Evento|None:
		query = 'SELECT * FROM EVENTOS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Evento]:
		query = 'SELECT * FROM EVENTOS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Evento]:
		query = 'SELECT * FROM EVENTOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Evento]:
		query = 'SELECT * FROM EVENTOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM EVENTOS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM EVENTOS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM EVENTOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM EVENTOS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, evento: Evento) -> int:
		query = 'INSERT INTO EVENTOS (TITULO, DESCRICAO, DIRETORIO, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (evento.titulo, evento.descricao, evento.diretorio, evento.usuario.id, evento.data, evento.ativo))
		self._database.conexao.commit()
		evento.id = self.procurar_ultimo().id
		for imagem in evento.imagens:
			imagem.evento = evento.id
			arquivo_dao.inserir(imagem)
		return self._database.cursor.rowcount

	def alterar(self, evento: Evento) -> int:
		query = 'UPDATE EVENTOS SET TITULO=%s, DESCRICAO=%s, DIRETORIO=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (evento.titulo, evento.descricao, evento.diretorio, evento.data, evento.ativo, evento.id))
		self._database.conexao.commit()
		for imagem in evento.imagens:
			if imagem.id == 0:
				imagem.evento = evento.id
				arquivo_dao.inserir(imagem)
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM EVENTOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE EVENTOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE EVENTOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		arquivo_dao.desativar(id)
		return self._database.cursor.rowcount

evento_dao = EventoDAO(database)
