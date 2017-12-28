from sinteemar.models.video import Video
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class VideoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela VIDEOS
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

	def unica(self, tupla: dict) -> Video|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		video = Video()
		video.id = tupla['ID']
		video.titulo = tupla['TITULO']
		video.url = tupla['URL']
		video.usuario = usuario
		video.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		video.ativo = tupla['ATIVO']
		return self.ativo(video)

	def varias(self, tuplas: list[dict]) -> list[Video]:
		videos = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			video = Video()
			video.id = tupla['ID']
			video.titulo = tupla['TITULO']
			video.url = tupla['URL']
			video.usuario = usuario
			video.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			video.ativo = tupla['ATIVO']
			videos.append(video)
		return self.ativos(videos)

	def ativo(self, video: Video) -> Video|None:
		if isinstance(video, Video) and video.ativo:
			return video
		return None

	def ativos(self, videos: list[Video]) -> list[Video]:
		if videos:
			for video in videos:
				if not video.ativo:
					videos.remove(video)
		return videos

	def inativo(self, video: Video) -> Video|None:
		if isinstance(video, Video) and not video.ativo:
			return video
		return None

	def inativos(self, videos: list[Video]) -> list[Video]:
		if videos:
			for video in videos:
				if video.ativo:
					videos.remove(video)
		return videos

	def procurar_data(self, data: str) -> list[Video]:
		query = 'SELECT * FROM VIDEOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Video|None:
		query = 'SELECT * FROM VIDEOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulo(self, titulo: str) -> Video|None:
		query = 'SELECT * FROM VIDEOS WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Video]:
		query = 'SELECT * FROM VIDEOS WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_url(self, url: str) -> Video|None:
		query = 'SELECT * FROM VIDEOS WHERE URL=%s'
		self._database.cursor.execute(query, url)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_urls(self, urls: str) -> list[Video]:
		query = 'SELECT * FROM VIDEOS WHERE URL LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, urls)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Video|None:
		query = 'SELECT * FROM VIDEOS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Video]:
		query = 'SELECT * FROM VIDEOS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Video]:
		query = 'SELECT * FROM VIDEOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Video]:
		query = 'SELECT * FROM VIDEOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM VIDEOS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM VIDEOS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM VIDEOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM VIDEOS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, video: Video) -> int:
		query = 'INSERT INTO VIDEOS (TITULO, URL, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (video.titulo, video.url, video.usuario.id, video.data, video.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, video: Video) -> int:
		query = 'UPDATE VIDEOS SET TITULO=%s, URL=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (video.titulo, video.url, video.data, video.ativo, video.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM VIDEOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE VIDEOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE VIDEOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

video_dao = VideoDAO(database)
