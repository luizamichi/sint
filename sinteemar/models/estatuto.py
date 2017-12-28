from datetime import datetime
from sinteemar.models.arquivo import Arquivo
from sinteemar.models.usuario import Usuario

class Estatuto():
	'''
	Atributos:
		id (int): Chave primária
		documento (Arquivo): Documento
		usuario (Usuario): Usuário
		data (datetime): Data de cadastro do estatuto
	'''
	__slots__ = ['_data', '_documento', '_id', '_usuario']

	def __init__(self, id: int=0, documento: str='', usuario: Usuario=Usuario(), data: datetime=datetime.now()):
		self._id = id
		self._documento = Arquivo(classe=self.__class__.__name__, nome=documento)
		self._usuario = usuario
		self._data = data

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nDOCUMENTO: ' + self._documento.arquivo + '\nUSUÁRIO: ' + self._usuario.nome.title() + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S')

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
	def documento(self) -> Arquivo:
		return self._documento

	@documento.setter
	def documento(self, documento: str|tuple[str, bool]) -> bool:
		criptografa = False
		try:
			documento, criptografa = documento
		except ValueError:
			pass
		self._documento = Arquivo(classe=self.__class__.__name__, nome=documento, hash=criptografa)
		return True if documento else None

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
