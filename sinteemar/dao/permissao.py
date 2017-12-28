from sinteemar.models.permissao import Permissao
from sinteemar.db.database import database, Database

class PermissaoDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela USUARIOS
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

	def unica(self, tupla: dict) -> Permissao|None:
		if not tupla:
			return None
		permissao = Permissao()
		permissao.id = tupla['ID']
		permissao.admin = tupla['ADMIN']
		permissao.banners = tupla['BANNERS']
		permissao.acessos = tupla['ACESSOS']
		permissao.boletins = tupla['BOLETINS']
		permissao.convencoes = tupla['CONVENCOES']
		permissao.convenios = tupla['CONVENIOS']
		permissao.diretoria = tupla['DIRETORIA']
		permissao.diretorio = tupla['DIRETORIO']
		permissao.editais = tupla['EDITAIS']
		permissao.estatuto = tupla['ESTATUTO']
		permissao.eventos = tupla['EVENTOS']
		permissao.financas = tupla['FINANCAS']
		permissao.historico = tupla['HISTORICO']
		permissao.jornais = tupla['JORNAIS']
		permissao.juridicos = tupla['JURIDICOS']
		permissao.noticias = tupla['NOTICIAS']
		permissao.registros = tupla['REGISTROS']
		permissao.tabelas = tupla['TABELAS']
		permissao.usuarios = tupla['USUARIOS']
		permissao.videos = tupla['VIDEOS']
		return permissao

	def varias(self, tuplas: list[dict]) -> list[Permissao]:
		permissoes = []
		for tupla in tuplas:
			permissao = Permissao()
			permissao.id = tupla['ID']
			permissao.admin = tupla['ADMIN']
			permissao.banners = tupla['BANNERS']
			permissao.acessos = tupla['ACESSOS']
			permissao.boletins = tupla['BOLETINS']
			permissao.convencoes = tupla['CONVENCOES']
			permissao.convenios = tupla['CONVENIOS']
			permissao.diretoria = tupla['DIRETORIA']
			permissao.diretorio = tupla['DIRETORIO']
			permissao.editais = tupla['EDITAIS']
			permissao.estatuto = tupla['ESTATUTO']
			permissao.eventos = tupla['EVENTOS']
			permissao.financas = tupla['FINANCAS']
			permissao.historico = tupla['HISTORICO']
			permissao.jornais = tupla['JORNAIS']
			permissao.juridicos = tupla['JURIDICOS']
			permissao.noticias = tupla['NOTICIAS']
			permissao.registros = tupla['REGISTROS']
			permissao.tabelas = tupla['TABELAS']
			permissao.usuarios = tupla['USUARIOS']
			permissao.videos = tupla['VIDEOS']
			permissoes.append(permissao)
		return permissoes

	def procurar_id(self, id: int) -> Permissao|None:
		query = 'SELECT * FROM PERMISSOES WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_admin(self, admin: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE ADMIN=%s'
		self._database.cursor.execute(query, admin)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_acessos(self, acesso: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE ACESSO=%s'
		self._database.cursor.execute(query, acesso)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_banners(self, banner: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE BANNERS=%s'
		self._database.cursor.execute(query, banner)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_boletins(self, boletim: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE BOLETINS=%s'
		self._database.cursor.execute(query, boletim)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_convencoes(self, convencao: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE CONVENCOES=%s'
		self._database.cursor.execute(query, convencao)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_convenios(self, convenio: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE CONVENIO=%s'
		self._database.cursor.execute(query, convenio)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_diretoria(self, diretoria: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE DIRETORIA=%s'
		self._database.cursor.execute(query, diretoria)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_diretorio(self, diretorio: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE DIRETORIO=%s'
		self._database.cursor.execute(query, diretorio)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_editais(self, edital: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE EDITAIS=%s'
		self._database.cursor.execute(query, edital)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_estatuto(self, estatuto: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE ESTATUTO=%s'
		self._database.cursor.execute(query, estatuto)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_eventos(self, evento: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE EVENTOS=%s'
		self._database.cursor.execute(query, evento)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_financas(self, financa: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE FINANCAS=%s'
		self._database.cursor.execute(query, financa)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_historico(self, historico: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE HISTORICO=%s'
		self._database.cursor.execute(query, historico)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_jornais(self, jornal: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE JORNAIS=%s'
		self._database.cursor.execute(query, jornal)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_juridicos(self, juridico: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE JURIDICOS=%s'
		self._database.cursor.execute(query, juridico)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_noticias(self, noticia: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE NOTICIAS=%s'
		self._database.cursor.execute(query, noticia)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_registros(self, registro: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE REGISTROS=%s'
		self._database.cursor.execute(query, registro)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_tabelas(self, tabela: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE TABELAS=%s'
		self._database.cursor.execute(query, tabela)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_usuarios(self, usuario: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE USUARIOS=%s'
		self._database.cursor.execute(query, usuario)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_videos(self, video: bool) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES WHERE VIDEOS=%s'
		self._database.cursor.execute(query, video)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Permissao|None:
		query = 'SELECT * FROM PERMISSOES ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Permissao]:
		query = 'SELECT * FROM PERMISSOES ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def inserir(self, permissao: Permissao) -> int:
		query = 'INSERT INTO PERMISSOES (ADMIN, ACESSOS, BANNERS, BOLETINS, CONVENCOES, CONVENIOS, DIRETORIA, DIRETORIO, EDITAIS, ESTATUTO, EVENTOS, FINANCAS, HISTORICO, JORNAIS, JURIDICOS, NOTICIAS, REGISTROS, TABELAS, USUARIOS, VIDEOS) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (permissao.admin, permissao.acessos, permissao.banners, permissao.boletins, permissao.convencoes, permissao.convenios, permissao.diretoria, permissao.diretorio, permissao.editais, permissao.estatuto, permissao.eventos, permissao.financas, permissao.historico, permissao.jornais, permissao.juridicos, permissao.noticias, permissao.registros, permissao.tabelas, permissao.usuarios, permissao.videos))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, permissao: Permissao) -> int:
		query = 'UPDATE PERMISSOES SET ADMIN=%s, ACESSOS=%s, BANNERS=%s, BOLETINS=%s, CONVENCOES=%s, CONVENIOS=%s, DIRETORIA=%s, DIRETORIO=%s, EDITAIS=%s, ESTATUTO=%s, EVENTOS=%s, FINANCAS=%s, HISTORICO=%s, JORNAIS=%s, JURIDICOS=%s, NOTICIAS=%s, REGISTROS=%s, TABELAS=%s, USUARIOS=%s, VIDEOS=%s WHERE ID=%s'
		self._database.cursor.execute(query, (permissao.admin, permissao.acessos, permissao.banners, permissao.boletins, permissao.convencoes, permissao.convenios, permissao.diretoria, permissao.diretorio, permissao.editais, permissao.estatuto, permissao.eventos, permissao.financas, permissao.historico, permissao.jornais, permissao.juridicos, permissao.noticias, permissao.registros, permissao.tabelas, permissao.usuarios, permissao.videos, permissao.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM PERMISSOES WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

permissao_dao = PermissaoDAO(database)
