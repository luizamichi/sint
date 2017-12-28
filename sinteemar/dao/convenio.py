from sinteemar.models.convenio import Convenio
from sinteemar.db.database import database, Database
from sinteemar.dao.usuario import usuario_dao

class ConvenioDAO():
	'''
	Atributos:
		database (Database): Banco de dados onde está localizada a tabela CONVENIOS
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

	def unica(self, tupla: dict) -> Convenio|None:
		if not tupla:
			return None
		usuario = usuario_dao.procurar_id(tupla['USUARIO'])
		convenio = Convenio()
		convenio.id = tupla['ID']
		convenio.titulo = tupla['TITULO']
		convenio.descricao = tupla['DESCRICAO']
		convenio.cidade = tupla['CIDADE']
		convenio.telefone = tupla['TELEFONE']
		convenio.celular = tupla['CELULAR']
		convenio.website = tupla['WEBSITE']
		convenio.email = tupla['EMAIL']
		convenio.imagem = tupla['IMAGEM']
		convenio.documento = tupla['DOCUMENTO']
		convenio.usuario = usuario
		convenio.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
		convenio.ativo = tupla['ATIVO']
		return self.ativo(convenio)

	def varias(self, tuplas: list[dict]) -> list[Convenio]:
		convenios = []
		for tupla in tuplas:
			usuario = usuario_dao.procurar_id(tupla['USUARIO'])
			convenio = Convenio()
			convenio.id = tupla['ID']
			convenio.titulo = tupla['TITULO']
			convenio.descricao = tupla['DESCRICAO']
			convenio.cidade = tupla['CIDADE']
			convenio.telefone = tupla['TELEFONE']
			convenio.celular = tupla['CELULAR']
			convenio.website = tupla['WEBSITE']
			convenio.email = tupla['EMAIL']
			convenio.imagem = tupla['IMAGEM']
			convenio.documento = tupla['DOCUMENTO']
			convenio.usuario = usuario
			convenio.data = tupla['DATA'].strftime('%Y-%m-%d %H:%M:%S')
			convenio.ativo = tupla['ATIVO']
			convenios.append(convenio)
		return self.ativos(convenios)

	def ativo(self, convenio: Convenio) -> Convenio|None:
		if isinstance(convenio, Convenio) and convenio.ativo:
			return convenio
		return None

	def ativos(self, convenios: list[Convenio]) -> list[Convenio]:
		if convenios:
			for convenio in convenios[:]:
				if not convenio.ativo:
					convenios.remove(convenio)
		return convenios

	def inativo(self, convenio: Convenio) -> Convenio|None:
		if isinstance(convenio, Convenio) and not convenio.ativo:
			return convenio
		return None

	def inativos(self, convenios: list[Convenio]) -> list[Convenio]:
		if convenios:
			for convenio in convenios[:]:
				if convenio.ativo:
					convenios.remove(convenio)
		return convenios

	def procurar_celular(self, celular: str) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE CELULAR=%s'
		self._database.cursor.execute(query, celular)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_celulares(self, celulares: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE CELULAR LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, celulares)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_cidade(self, cidade: str) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE CIDADE=%s'
		self._database.cursor.execute(query, cidade)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_cidades(self, cidades: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE CIDADE LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, cidades)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_data(self, data: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_descricao(self, descricao: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE DESCRICAO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, descricao)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_documento(self, documento: str) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE DOCUMENTO=%s'
		self._database.cursor.execute(query, documento)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_documentos(self, documentos: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE DOCUMENTO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, documentos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_email(self, email: str) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE EMAIL=%s'
		self._database.cursor.execute(query, email)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_emails(self, emails: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE EMAIL LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, emails)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_imagem(self, imagem: str) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE IMAGEM=%s'
		self._database.cursor.execute(query, imagem)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_imagens(self, imagens: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE IMAGEM LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, imagens)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_id(self, id: int) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_telefone(self, telefone: str) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE TELEFONE=%s'
		self._database.cursor.execute(query, telefone)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_telefones(self, telefones: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE TELEFONE LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, telefones)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_titulo(self, titulo: str) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE TITULO=%s'
		self._database.cursor.execute(query, titulo)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_titulos(self, titulos: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE TITULO LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, titulos)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_website(self, website: str) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS WHERE WEBSITE=%s'
		self._database.cursor.execute(query, website)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def procurar_websites(self, websites: str) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE WEBSITE LIKE CONCAT(\'%%\',%s,\'%%\')'
		self._database.cursor.execute(query, websites)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def procurar_ultimo(self) -> Convenio|None:
		query = 'SELECT * FROM CONVENIOS ORDER BY ID DESC LIMIT 1'
		self._database.cursor.execute(query)
		tupla = self._database.cursor.fetchone()
		return self.unica(tupla)

	def listar(self) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE ATIVO=1 ORDER BY ID DESC'
		self._database.cursor.execute(query)
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_intervalo(self, inicio: int, fim: int) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s, %s'
		self._database.cursor.execute(query, (max(0, inicio), max(0, fim)))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def listar_quantidade(self, quantidade: int) -> list[Convenio]:
		query = 'SELECT * FROM CONVENIOS WHERE ATIVO=1 ORDER BY ID DESC LIMIT %s'
		self._database.cursor.execute(query, max(0, quantidade))
		tuplas = self._database.cursor.fetchall()
		return self.varias(tuplas)

	def tamanho(self) -> str:
		query = 'SELECT COUNT(*) FROM CONVENIOS'
		self._database.cursor.execute(query)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_ativo(self, ativo: int) -> str:
		query = 'SELECT COUNT(*) FROM CONVENIOS WHERE ATIVO=%s'
		self._database.cursor.execute(query, ativo)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_data(self, data: str) -> str:
		query = 'SELECT COUNT(*) FROM CONVENIOS WHERE DATA LIKE CONCAT(%s,\'%%\')'
		self._database.cursor.execute(query, data)
		return self._database.cursor.fetchone()['COUNT(*)']

	def tamanho_usuario(self, usuario: int) -> str:
		query = 'SELECT COUNT(*) FROM CONVENIOS WHERE USUARIO=%s'
		self._database.cursor.execute(query, usuario)
		return self._database.cursor.fetchone()['COUNT(*)']

	def inserir(self, convenio: Convenio) -> int:
		query = 'INSERT INTO CONVENIOS (TITULO, DESCRICAO, CIDADE, TELEFONE, CELULAR, WEBSITE, EMAIL, IMAGEM, DOCUMENTO, USUARIO, DATA, ATIVO) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)'
		self._database.cursor.execute(query, (convenio.titulo, convenio.descricao, convenio.cidade, convenio.telefone, convenio.celular, convenio.website, convenio.email, convenio.imagem.arquivo, (convenio.documento.arquivo if convenio.documento else convenio.documento), convenio.usuario.id, convenio.data, convenio.ativo))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def alterar(self, convenio: Convenio) -> int:
		query = 'UPDATE CONVENIOS SET TITULO=%s, DESCRICAO=%s, CIDADE=%s, TELEFONE=%s, CELULAR=%s, WEBSITE=%s, EMAIL=%s, IMAGEM=%s, DOCUMENTO=%s, DATA=%s, ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (convenio.titulo, convenio.descricao, convenio.cidade, convenio.telefone, convenio.celular, convenio.website, convenio.email, convenio.imagem.arquivo, (convenio.documento.arquivo if convenio.documento else convenio.documento), convenio.data, convenio.ativo, convenio.id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def remover(self, id: int) -> int:
		query = 'DELETE FROM CONVENIOS WHERE ID=%s'
		self._database.cursor.execute(query, id)
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def ativar(self, id: int) -> int:
		query = 'UPDATE CONVENIOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (True, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

	def desativar(self, id: int) -> int:
		query = 'UPDATE CONVENIOS SET ATIVO=%s WHERE ID=%s'
		self._database.cursor.execute(query, (False, id))
		self._database.conexao.commit()
		return self._database.cursor.rowcount

convenio_dao = ConvenioDAO(database)
