from sinteemar.models.diretoria import Diretoria
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class DiretoriaDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela DIRETORIA
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

	def unica(self, tupla: dict) -> Diretoria|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		diretoria = Diretoria()
		diretoria.id = tupla['ID']
		diretoria.titulo = tupla['TITULO']
		diretoria.texto = tupla['TEXTO']
		diretoria.imagem = tupla['IMAGEM']
		diretoria.usuario = usuario
		diretoria.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		return diretoria

	def varias(self, tuplas: list[dict]) -> list[Diretoria]:
		diretorias = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			diretoria = Diretoria()
			diretoria.id = tupla['ID']
			diretoria.titulo = tupla['TITULO']
			diretoria.texto = tupla['TEXTO']
			diretoria.imagem = tupla['IMAGEM']
			diretoria.usuario = usuario
			diretoria.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			diretorias.append(diretoria)
		return diretorias

	def procurar_data(self, data: str) -> list[Diretoria]:
		query = 'SELECT * FROM DIRETORIA WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_imagem(self, imagem: str) -> Diretoria|None:
		query = 'SELECT * FROM DIRETORIA WHERE IMAGEM=%s'
		self._database.cursor.execute(query, imagem)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagens(self, imagens: str) -> list[Diretoria]:
		query = 'SELECT * FROM DIRETORIA WHERE IMAGEM LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, imagens)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Diretoria|None:
		query = 'SELECT * FROM DIRETORIA WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_texto(self, texto: str) -> Diretoria|None:
		query = 'SELECT * FROM DIRETORIA WHERE TEXTO=%s'
		self._database.cursor.execute(query, texto)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_textos(self, textos: str) -> list[Diretoria]:
		query = 'SELECT * FROM DIRETORIA WHERE TEXTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, textos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_titulo(self, titulo: str) -> Diretoria|None:
		query = 'SELECT * FROM DIRETORIA WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Diretoria]:
		query = 'SELECT * FROM DIRETORIA WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Diretoria|None:
		query = 'SELECT * FROM DIRETORIA ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Diretoria]:
		query = 'SELECT * FROM DIRETORIA ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Diretoria]:
		query = 'SELECT * FROM DIRETORIA ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Diretoria]:
		query = 'SELECT * FROM DIRETORIA ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM DIRETORIA'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM DIRETORIA WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM DIRETORIA WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, diretoria: Diretoria) -> int:
		query = 'INSERT INTO DIRETORIA (TITULO, TEXTO, IMAGEM, USUARIO, DATA) VALUES (%s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (diretoria.titulo, diretoria.texto, (diretoria.imagem.arquivo if diretoria.imagem else diretoria.imagem), diretoria.usuario.id, diretoria.data))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, diretoria: Diretoria) -> int:
		query = 'UPDATE DIRETORIA SET TITULO=%s, TEXTO=%s, IMAGEM=%s, DATA=%s WHERE ID=%s'
		self._database.cursor.execute(query, (diretoria.titulo, diretoria.texto, (diretoria.imagem.arquivo if diretoria.imagem else diretoria.imagem), diretoria.data, diretoria.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM DIRETORIA WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

diretoria_dao = DiretoriaDAO(database)
