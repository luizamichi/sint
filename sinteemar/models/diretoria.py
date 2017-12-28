from datetime import datetime
from sinteemar.models.arquivo import Arquivo
from sinteemar.models.usuario import Usuario

class Diretoria():
	'''
	Atributos:
		id (int): Chave primária
		titulo (str): Nome da chapa
		texto (str): Texto com os diretores da gestão
		imagem (Arquivo): Imagem
		usuario (Usuario): Usuário
		data (datetime): Data de cadastro da diretoria
	'''
	__slots__ = ['_data', '_id', '_imagem', '_texto', '_titulo', '_usuario']

	def __init__(self, id: int=0, titulo: str='', texto: str='', imagem: str=None, usuario: Usuario=Usuario(), data: datetime=datetime.now()):
		self._id = id
		self._titulo = titulo
		self._texto = texto
		self._imagem = Arquivo(classe=self.__class__.__name__, nome=imagem) if imagem else None
		self._usuario = usuario
		self._data = data

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nTÍTULO: ' + self._titulo + '\nTEXTO: ' + self._texto + '\nIMAGEM: ' + (self._imagem.arquivo if self._imagem else str(self._imagem)) + '\nUSUÁRIO: ' + self._usuario.nome.title() + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S')

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
	def texto(self) -> str:
		return self._texto

	@texto.setter
	def texto(self, texto: str) -> bool:
		texto = texto.strip()
		if len(texto) >= 6:
			self._texto = texto
			return True
		return False

	@property
	def imagem(self) -> Arquivo|None:
		return self._imagem

	@imagem.setter
	def imagem(self, imagem: str|tuple[str, bool]|None) -> bool:
		if not imagem:
			self._imagem = None
		else:
			criptografa = False
			try:
				imagem, criptografa = imagem
			except ValueError:
				pass
			self._imagem = Arquivo(classe=self.__class__.__name__, nome=imagem, hash=criptografa)
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
