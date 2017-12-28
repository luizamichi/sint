from datetime import datetime
from sinteemar.models.arquivo import Arquivo
from sinteemar.models.usuario import Usuario

class Banner():
	'''
	Atributos:
		id (int): Chave primária
		imagem (Arquivo): Imagem
		usuario (Usuario): Usuário
		data (datetime): Data de cadastro do banner
		ativo (bool): Inativo (False) ou ativo (True)
	'''
	__slots__ = ['_ativo', '_data', '_id', '_imagem', '_usuario']

	def __init__(self, id: int=0, imagem: str='', usuario: Usuario=Usuario(), data: datetime=datetime.now(), ativo: bool=False):
		self._id = id
		self._imagem = Arquivo(classe=self.__class__.__name__, nome=imagem)
		self._usuario = usuario
		self._data = data
		self._ativo = ativo

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nIMAGEM: ' + self._imagem.arquivo + '\nUSUÁRIO: ' + self._usuario.nome.title() + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S') + '\nATIVO: ' + str(self._ativo)

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
		return True if imagem else False

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
