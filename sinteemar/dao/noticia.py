from sinteemar.models.noticia import Noticia
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class NoticiaDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela NOTICIAS
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

	def unica(self, tupla: dict) -> Noticia|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		noticia = Noticia()
		noticia.id = tupla['ID']
		noticia.titulo = tupla['TITULO']
		noticia.subtitulo = tupla['SUBTITULO']
		noticia.texto = tupla['TEXTO']
		noticia.imagem = tupla['IMAGEM']
		noticia.usuario = usuario
		noticia.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		noticia.ativo = tupla['ATIVO']
		return self.ativo(noticia)

	def varias(self, tuplas: list[dict]) -> list[Noticia]:
		noticias = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			noticia = Noticia()
			noticia.id = tupla['ID']
			noticia.titulo = tupla['TITULO']
			noticia.subtitulo = tupla['SUBTITULO']
			noticia.texto = tupla['TEXTO']
			noticia.imagem = tupla['IMAGEM']
			noticia.usuario = usuario
			noticia.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			noticia.ativo = tupla['ATIVO']
			noticias.append(noticia)
		return self.ativos(noticias)

	def ativo(self, noticia: Noticia) -> Noticia|None:
		if isinstance(noticia, Noticia) and noticia.ativo:
			return noticia
		return None

	def ativos(self, noticias: list[Noticia]) -> list[Noticia]:
		if noticias:
			for noticia in noticias[:]:
				if not noticia.ativo:
					noticias.remove(noticia)
		return noticias

	def inativo(self, noticia: Noticia) -> Noticia|None:
		if isinstance(noticia, Noticia) and not noticia.ativo:
			return noticia
		return None

	def inativos(self, noticias: list[Noticia]) -> list[Noticia]:
		if noticias:
			for noticia in noticias[:]:
				if noticia.ativo:
					noticias.remove(noticia)
		return noticias

	def procurar_data(self, data: str) -> list[Noticia]:
		query = 'SELECT * FROM NOTICIAS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Noticia|None:
		query = 'SELECT * FROM NOTICIAS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagem(self, imagem: str) -> Noticia|None:
		query = 'SELECT * FROM NOTICIAS WHERE IMAGEM=%s'
		self._database.cursor.execute(query, imagem)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagens(self, imagens: str) -> list[Noticia]:
		query = 'SELECT * FROM NOTICIAS WHERE IMAGEM LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, imagens)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_subtitulo(self, subtitulo: str) -> list[Noticia]:
		query = 'SELECT * FROM NOTICIAS WHERE SUBTITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, subtitulo)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_texto(self, texto: str) -> list[Noticia]:
		query = 'SELECT * FROM NOTICIAS WHERE TEXTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, texto)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_titulo(self, titulo: str) -> Noticia|None:
		query = 'SELECT * FROM NOTICIAS WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Noticia]:
		query = 'SELECT * FROM NOTICIAS WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Noticia|None:
		query = 'SELECT * FROM NOTICIAS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Noticia]:
		query = 'SELECT * FROM NOTICIAS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Noticia]:
		query = 'SELECT * FROM NOTICIAS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Noticia]:
		query = 'SELECT * FROM NOTICIAS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM NOTICIAS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM NOTICIAS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM NOTICIAS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM NOTICIAS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, noticia: Noticia) -> int:
		query = 'INSERT INTO NOTICIAS (TITULO, SUBTITULO, TEXTO, IMAGEM, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (noticia.titulo, noticia.subtitulo, noticia.texto, (noticia.imagem.arquivo if noticia.imagem else noticia.imagem), noticia.usuario.id, noticia.data, noticia.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, noticia: Noticia) -> int:
		query = 'UPDATE NOTICIAS SET TITULO=%s, SUBTITULO=%s, TEXTO=%s, IMAGEM=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (noticia.titulo, noticia.subtitulo, noticia.texto, (noticia.imagem.arquivo if noticia.imagem else noticia.imagem), noticia.data, noticia.ativo, noticia.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM NOTICIAS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE NOTICIAS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE NOTICIAS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

noticia_dao = NoticiaDAO(database)
