from datetime import datetime
from re import search
from urllib.parse import urlparse
from sinteemar.models.arquivo import Arquivo
from sinteemar.models.usuario import Usuario

class Convenio():
	'''
	Atributos:
		id (int): Chave primária
		titulo (str): Nome da empresa ou do convênio
		descricao (str): Descrição detalhando o convênio
		cidade (str): Cidade de atendimento do convênio
		telefone (str): Número do telefone fixo
		celular (str): Número do telefone celular
		website (str): Endereço web
		email (str): Edereço de e-mail
		imagem (Arquivo): Imagem
		documento (Arquivo): Documento
		usuario (Usuario): Usuário
		data (datetime): Data de cadastro do convênio
		ativo (bool): Inativo (False) ou ativo (True)
	'''
	__slots__ = ['_ativo', '_celular', '_cidade', '_data', '_descricao', '_documento', '_email', '_id', '_imagem', '_telefone', '_titulo', '_usuario', '_website']

	def __init__(self, id: int=0, titulo: str='', descricao: str='', cidade: str='', telefone: str=None, celular: str=None, website: str=None, email: str=None, imagem: str='', documento: str=None, usuario: Usuario=Usuario(), data: datetime=datetime.now(), ativo: bool=False):
		self._id = id
		self._titulo = titulo
		self._descricao = descricao
		self._cidade = cidade
		self._telefone = telefone
		self._celular = celular
		self._website = website
		self._email = email
		self._imagem = Arquivo(classe=self.__class__.__name__, nome=imagem)
		self._documento = Arquivo(classe=self.__class__.__name__, nome=documento) if documento else None
		self._usuario = usuario
		self._data = data
		self._ativo = ativo

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nTÍTULO: ' + self._titulo + '\nDESCRIÇÃO: ' + self._descricao + '\nCIDADE: ' + self._cidade + '\nTELEFONE: ' + str(self._telefone) + '\nCELULAR: ' + str(self._celular) + '\nWEBSITE: ' + (self._website.geturl() if self._website else str(self._website)) + '\nE-MAIL: ' + str(self._email) + '\nIMAGEM: ' + self._imagem.arquivo + '\nDOCUMENTO: ' + (self._documento.arquivo if self._documento else str(self._documento)) + '\nUSUÁRIO: ' + self._usuario.nome.title() + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S') + '\nATIVO: ' + str(self._ativo)

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
	def titulo(self) -> str:
		return self._titulo

	@titulo.setter
	def titulo(self, titulo: str) -> bool:
		titulo = titulo.strip()
		if len(titulo) in range(6, 129):
			self._titulo = titulo
			return True
		return False

	@property
	def descricao(self) -> str:
		return self._descricao

	@descricao.setter
	def descricao(self, descricao: str) -> bool:
		descricao = descricao.strip()
		if len(descricao) >= 6:
			self._descricao = descricao
			return True
		return False

	@property
	def cidade(self) -> str:
		return self._cidade

	@cidade.setter
	def cidade(self, cidade: str) -> bool:
		cidade = cidade.strip()
		if len(cidade) in range(6, 65):
			self._cidade = cidade
			return True
		return False

	@property
	def telefone(self) -> str|None:
		return self._telefone

	@telefone.setter
	def telefone(self, telefone: str|None) -> bool:
		if not telefone:
			self._telefone = None
		elif search(r'(\(?\d{2}\)?\s)?(\d{4}\-\d{4})', telefone.strip()):
			self._telefone = telefone.strip()
		else:
			return False
		return True

	@property
	def celular(self) -> str|None:
		return self._celular

	@celular.setter
	def celular(self, celular: str|None) -> bool:
		if not celular:
			self._celular = None
		elif search(r'(\(?\d{2}\)?\s)?(\d{4,5}\-\d{4})', celular.strip()):
			self._celular = celular.strip()
		else:
			return False
		return True

	@property
	def website(self) -> str|None:
		return self._website.geturl() if self._website else self._website

	@website.setter
	def website(self, website: str|None) -> bool:
		if not website:
			self._website = None
		elif len(website.strip()) in range(6, 65):
			try:
				self._website = urlparse(website.strip())
			except:
				return False
		return True

	@property
	def email(self) -> str|None:
		return self._email

	@email.setter
	def email(self, email: str|None) -> bool:
		if not email:
			self._email = None
		elif len(email.strip()) in range(6, 65) and search(r'(^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$)', email.strip()):
			self._email = email.strip().lower()
		else:
			return False
		return True

	@property
	def imagem(self) -> Arquivo:
		return self._imagem

	@imagem.setter
	def imagem(self, imagem: str|tuple[str, bool]) -> bool:
		criptografa = False
		try:
			imagem, criptografa = imagem
		except ValueError:
			pass
		self._imagem = Arquivo(classe=self.__class__.__name__, nome=imagem, hash=criptografa)
		return True if imagem else None

	@property
	def documento(self) -> Arquivo|None:
		return self._documento

	@documento.setter
	def documento(self, documento: str|tuple[str, bool]|None) -> bool:
		if not documento:
			self._documento = None
		else:
			criptografa = False
			try:
				documento, criptografa = documento
			except ValueError:
				pass
			self._documento = Arquivo(classe=self.__class__.__name__, nome=documento, hash=criptografa)
		return True

	@property
	def usuario(self) -> Usuario:
		return self._usuario

	@usuario.setter
	def usuario(self, usuario: Usuario) -> bool:
		if isinstance(usuario, Usuario):
			self._usuario = usuario
			return True
		return False

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
