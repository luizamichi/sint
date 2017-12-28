from datetime import datetime
from ipaddress import ip_address

class Acesso():
	'''
	Atributos:
		id (int): Chave primária
		ip (IPv4Address or IPv6Address): Endereço IP do visitante
		data (datetime): Data do acesso na ordem ano-mês-dia hora:minuto:segundo
	'''
	__slots__ = ['_data', '_id', '_ip']

	def __init__(self, id: int=0, ip: str='', data: datetime=datetime.now()):
		self._id = id
		self._ip = ip
		self._data = data

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nIP: ' + str(self._ip) + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S')

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
	def ip(self) -> str:
		return self._ip

	@ip.setter
	def ip(self, ip: str) -> bool:
		try:
			self._ip = ip_address(ip)
			return True
		except ValueError:
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
