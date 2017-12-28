from ipaddress import ip_address
from pymysql import connect, cursors
from sinteemar.config import DATABASE_NAME, DATABASE_PASS, DATABASE_URI, DATABASE_USER

class Database():
	'''
	Atributos:
		host (IPv4Address || IPv6Address): Endereço IP ou domínio do servidor hospedeiro
		porta (int): Porta do serviço MySQL ou MariaDB
		usuario (str): Nome do usuário do SGBD
		senha (str): Senha do usuário do SGBD
		database (str): Nome da base de dados
		conexao (Connection): Conexão entre o MySQL e o Python
	'''
	__slots__ = ['_conexao', '_cursor', '_database', '_host', '_porta', '_senha', '_usuario']

	def __init__(self, host: str='', porta: int=3306, usuario: str='', senha: str='', database: str='', conexao: bool=None):
		self._host = host
		self._porta = porta
		self._usuario = usuario
		self._senha = senha
		self._database = database
		self._conexao = self.conexao = conexao

	def __del__(self):
		if self._conexao:
			self._conexao.close()

	def __str__(self) -> str:
		return 'HOST: ' + str(self._host) + '\nPORTA: ' + str(self._porta) + '\nUSUÁRIO: ' + self._usuario + '\nSENHA: ' + self._senha + '\nBASE DE DADOS: ' + self._database + '\nCONEXÃO: ' + str(self._conexao)

	@property
	def host(self) -> str:
		return self._host

	@host.setter
	def host(self, host: str) -> bool:
		try:
			self._host = ip_address(host)
			return True
		except ValueError:
			return False

	@property
	def porta(self) -> int:
		return self._porta

	@porta.setter
	def porta(self, porta: int) -> bool:
		if porta >= 0:
			self._porta = porta
			return True
		return False

	@property
	def usuario(self) -> str:
		return self._usuario

	@usuario.setter
	def usuario(self, usuario: int) -> bool:
		self._usuario = usuario
		return True

	@property
	def senha(self) -> str:
		return self._senha

	@senha.setter
	def senha(self, senha: str) -> bool:
		self._senha = senha
		return True

	@property
	def database(self) -> str:
		return self._database

	@database.setter
	def database(self, database: str) -> bool:
		self._database = database
		return True

	@property
	def conexao(self) -> any:
		return self._conexao

	@conexao.setter
	def conexao(self, conexao: bool) -> bool:
		if bool(conexao):
			try:
				if self._conexao:
					self._conexao.close()
				self._conexao = connect(host=str(self._host), port=self._porta, user=self._usuario, password=self._senha, db=self._database, charset='utf8mb4', cursorclass=cursors.DictCursor)
				self._cursor = self._conexao.cursor()
				return True
			except:
				self._conexao = None
				return False
		else:
			if self._conexao is not None:
				self._conexao.close()
				self._conexao = None
				return True
			else:
				return False

	@property
	def cursor(self) -> any:
		try:
			if self._conexao:
				self._conexao.ping(reconnect=True)
			return self._cursor
		except:
			self.conexao = True
			if self._conexao:
				return self._cursor
			return None

	def delete(self, tabela: str, condicao: str=None) -> int:
		query = 'DELETE FROM ' + tabela + (' WHERE ' + condicao if condicao else '')
		self._cursor.execute(query)
		self._conexao.commit()
		return self._cursor.rowcount

	def drop(self, tabela: str) -> int:
		query = 'DROP TABLE ' + tabela
		self._cursor.execute(query)
		return self._cursor.rowcount

	def insert(self, valores: tuple | list[tuple], tabela: str, campos: str=None) -> int:
		query = 'INSERT INTO ' + tabela + (' (' + campos + ')' if campos else '') + ' VALUES ' + (str(valores).strip('[]'))
		self._cursor.execute(query)
		self._conexao.commit()
		return self._cursor.rowcount

	def query(self, consulta) -> list:
		self._cursor.execute(consulta)
		if consulta.split()[0] in ['DELETE', 'INSERT', 'UPDATE']:
			self._conexao.commit()
		return self._cursor.fetchall()

	def select(self, campos: str='*', tabelas: str=None, condicao: str=None) -> list:
		query = 'SELECT ' + (campos if tabelas else 'TABLE_NAME') + ' FROM ' + (tabelas if tabelas else 'INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA="' + self._database + '"') + (' WHERE ' + condicao if condicao and tabelas else '')
		self._cursor.execute(query)
		return self._cursor.fetchall()

	def show(self) -> list:
		query = 'SHOW DATABASES'
		self._cursor.execute(query)
		return self._cursor.fetchall()

	def update(self, campos: dict, tabela: str, condicao: str=None) -> int:
		query = 'UPDATE ' + tabela + ' SET ' + ', '.join([atributo + '="' + str(valor) + '"' for atributo, valor in campos.items()]) + (' WHERE ' + condicao if condicao else '')
		self._cursor.execute(query)
		self._conexao.commit()
		return self._cursor.rowcount

	@property
	def version(self) -> str:
		query = 'SELECT VERSION()'
		self._cursor.execute(query)
		return self._cursor.fetchone()['VERSION()']

database = Database(host=DATABASE_URI, usuario=DATABASE_USER, senha=DATABASE_PASS, database=DATABASE_NAME, conexao=True)
