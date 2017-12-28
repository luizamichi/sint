from sinteemar.models.banner import Banner
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class BannerDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela BANNERS
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

	def unica(self, tupla: dict) -> Banner|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		banner = Banner()
		banner.id = tupla['ID']
		banner.imagem = tupla['IMAGEM']
		banner.usuario = usuario
		banner.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		banner.ativo = tupla['ATIVO']
		return self.ativo(banner)

	def varias(self, tuplas: list[dict]) -> list[Banner]:
		banners = []
		for tupla in tuplas:
			banners.append(self.unica(tupla))
		return self.ativos(banners)

	def ativo(self, banner: Banner) -> Banner|None:
		if isinstance(banner, Banner) and banner.ativo:
			return banner
		return None

	def ativos(self, banners: list[Banner]) -> list[Banner]:
		if banners:
			for banner in banners[:]:
				if not banner.ativo:
					banners.remove(banner)
		return banners

	def inativo(self, banner: Banner) -> Banner|None:
		if isinstance(banner, Banner) and not banner.ativo:
			return banner
		return None

	def inativos(self, banners: list[Banner]) -> list[Banner]:
		if banners:
			for banner in banners[:]:
				if banner.ativo:
					banners.remove(banner)
		return banners

	def procurar_data(self, data: str) -> list[Banner]:
		query = 'SELECT * FROM BANNERS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_imagem(self, imagem: str) -> Banner|None:
		query = 'SELECT * FROM BANNERS WHERE IMAGEM=%s'
		self._database.cursor.execute(query, imagem)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagens(self, imagens: str) -> list[Banner]:
		query = 'SELECT * FROM BANNERS WHERE IMAGEM LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, imagens)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Banner|None:
		query = 'SELECT * FROM BANNERS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_ultimo(self) -> Banner|None:
		query = 'SELECT * FROM BANNERS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Banner]:
		query = 'SELECT * FROM BANNERS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Banner]:
		query = 'SELECT * FROM BANNERS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Banner]:
		query = 'SELECT * FROM BANNERS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM BANNERS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM BANNERS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM BANNERS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM BANNERS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, banner: Banner) -> int:
		query = 'INSERT INTO BANNERS (IMAGEM, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s)'
		self._database.cursor.execute(query, (banner.imagem.arquivo, banner.usuario.id, banner.data, banner.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, banner: Banner) -> int:
		query = 'UPDATE BANNERS SET IMAGEM=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (banner.imagem.arquivo, banner.data, banner.ativo, banner.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM BANNERS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE BANNERS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE BANNERS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

banner_dao = BannerDAO(database)
