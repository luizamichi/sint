from datetime import datetime
from ipaddress import ip_address
from sinteemar.models.usuario import Usuario

class Registro():
	'''
	Atributos:
		id (int): Chave primária
		descricao (str): Descrição da atividade realizada
		ip (str): Endereço IP do usuário
		usuario (Usuario): Usuário
		data (datetime): Data do registro
	'''
	__slots__ = ['_data', '_descricao', '_id', '_ip', '_usuario']

	def __init__(self, id: int=0, descricao: str='', ip: str='', usuario: Usuario=Usuario(), data: datetime=datetime.now()):
		self._id = id
		self._descricao = descricao
		self._ip = ip
		self._usuario = usuario
		self._data = data

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nDESCRIÇÃO: ' + self._descricao + '\nIP: ' + str(self._ip) + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S')

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
	def descricao(self) -> str:
		return self._descricao

	@descricao.setter
	def descricao(self, descricao: str) -> bool:
		descricao = descricao.strip()
		if len(descricao) >= 6:
			self._descricao = descricao.upper()
			return True
		return False

	@property
	def ip(self) -> str:
		return str(self._ip)

	@ip.setter
	def ip(self, ip: str) -> bool:
		try:
			self._ip = ip_address(ip.strip())
			return True
		except ValueError:
			return False

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
