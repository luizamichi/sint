from datetime import datetime
from urllib.parse import urlparse
from sinteemar.models.usuario import Usuario

class Video():
	'''
	Atributos:
		id (int): Chave primária
		titulo (str): Título ou descrição
		url (str): Endereço web
		usuario (Usuario): Usuário
		data (datetime): Data de cadastro do vídeo
		ativo (bool): Inativo (False) ou ativo (True)
	'''
	__slots__ = ['_ativo', '_data', '_id', '_titulo', '_url', '_usuario']

	def __init__(self, id: int=0, titulo: str='', url: str='', usuario: Usuario=Usuario(), data: datetime=datetime.now(), ativo: bool=False):
		self._id = id
		self._titulo = titulo
		self._url = url
		self._usuario = usuario
		self._data = data
		self._ativo = ativo

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nTÍTULO: ' + self._titulo + '\nURL: ' + self._url.geturl() + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S') + '\nATIVO: ' + str(self._ativo)

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
	def url(self) -> str:
		return self._url.geturl() if self._url else ''

	@url.setter
	def url(self, url: str) -> bool:
		url = url.strip()
		if len(url) in range(6, 65):
			try:
				self._url = urlparse(url)
				return True
			except:
				return False
		return False

	@property
	def youtube(self) -> str:
		if self._url.geturl().find('watch?v=') > 0:
			return self._url.geturl().split('watch?v=')[1]
		elif self._url.geturl().find('youtu.be/') > 0:
			return self._url.geturl().split('youtu.be/')[1]
		return ''

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
