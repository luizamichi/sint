from datetime import datetime
from hashlib import md5
from re import search
from sinteemar.models.permissao import Permissao

class Usuario():
	'''
	Atributos:
		id (int): Chave primária
		nome (str): Nome completo
		email (str): Endereço de e-mail
		login (str): Apelido único
		senha (str): Senha alfanumérica
		permissao (Permissao): Permissões
		data (datetime): Data de cadastro do usuário
		status (bool): Inativo (False) ou ativo (True)
	'''
	__slots__ = ['_ativo', '_data', '_email', '_id', '_login', '_nome', '_permissao', '_senha']

	def __init__(self, id: int=0, nome: str='', email: str='', login: str='', senha: str='', permissao: Permissao=Permissao(), data: datetime=datetime.now(), ativo: bool=False):
		self._id = id
		self._nome = nome
		self._email = email
		self._login = login
		self._senha = senha
		self._permissao = permissao
		self._data = data
		self._ativo = ativo

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nNOME: ' + self._nome.title() + '\nE-MAIL: ' + self._email + '\nLOGIN: ' + self._login + '\nSENHA: ' + self._senha + '\nPERMISSÃO: ' + str(self._permissao.id) + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S') + '\nATIVO: ' + str(self._ativo)

	@property
	def id(self) -> int:
		return self._id

	@id.setter
	def id(self, id: int) -> bool:
		if id > 0:
			self._id = id
			return True
		return False

	@property
	def nome(self) -> str:
		return self._nome

	@nome.setter
	def nome(self, nome: str) -> bool:
		nome = nome.strip()
		if len(nome) in range(6, 65):
			self._nome = nome
			return True
		return False

	@property
	def email(self) -> str:
		return self._email

	@email.setter
	def email(self, email: str) -> bool:
		email = email.strip()
		if len(email) in range(6, 65) and search(r'(^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$)', email):
			self._email = email.lower()
			return True
		return False

	@property
	def login(self) -> str:
		return self._login

	@login.setter
	def login(self, login: str) -> bool:
		login = login.strip()
		if len(login) in range(6, 65):
			self._login = login.lower()
			return True
		return False

	@property
	def senha(self) -> str:
		return self._senha

	@senha.setter
	def senha(self, senha: str|tuple[str, bool]) -> bool:
		criptografa = False
		try:
			senha, criptografa = senha
		except ValueError:
			pass
		if len(senha) >= 6:
			if criptografa:
				self._senha = md5(str.encode(senha)).hexdigest()
			else:
				self._senha = senha
			return True
		return False

	@property
	def permissao(self) -> Permissao:
		return self._permissao

	@permissao.setter
	def permissao(self, permissao: Permissao) -> bool:
		self._permissao = permissao
		return True

	@property
	def data(self) -> datetime:
		return self._data

	@data.setter
	def data(self, data: str) -> bool:
		try:
			self._data = datetime(int(data[:4]), int(data[5:7]), int(data[8:10]), int(data[11:13]), int(data[14:16]), int(data[17:19]))
			return True
		except TypeError:
			return False

	@property
	def ativo(self) -> bool:
		return self._ativo

	@ativo.setter
	def ativo(self, ativo: bool) -> bool:
		self._ativo = bool(ativo)
		return True
