import os, random, string
from datetime import datetime
from sinteemar.models.arquivo import Arquivo
from sinteemar.models.usuario import Usuario

class Evento():
	'''
	Atributos:
		id (int): Chave primária
		titulo (str): Título do evento
		descricao (str): Descrição do evento ocorrido
		diretorio (str): Endereço do diretório com as imagens
		imagens (list): Nomes das imagens
		usuario (Usuario): Usuário
		data (datetime): Data de cadastro do evento
		ativo (bool): Inativo (False) ou ativo (True)
	'''
	__slots__ = ['_ativo', '_data', '_descricao', '_diretorio', '_id', '_imagens', '_titulo', '_usuario']

	def __init__(self, id: int=0, titulo: str='', descricao: str=None, diretorio: str=datetime.now().strftime('%d%m%Y%H%M%S') + ''.join(random.choice(string.ascii_letters) for _ in range(5)), imagens: list=[], usuario: Usuario=Usuario(), data: datetime=datetime.now(), ativo: bool=False):
		self._id = id
		self._titulo = titulo
		self._descricao = descricao
		self._diretorio = diretorio
		self._imagens = imagens
		self._usuario = usuario
		self._data = data
		self._ativo = ativo

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nTÍTULO: ' + self._titulo + '\nDESCRIÇÃO: ' + str(self._descricao) + '\nDIRETÓRIO: ' + self._diretorio + '\nIMAGENS: ' + ', '.join(imagem.arquivo for imagem in self._imagens) + '\nUSUÁRIO: ' + str(self._usuario.id) + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S') + '\nATIVO: ' + str(self._ativo)

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
	def descricao(self) -> str|None:
		return self._descricao

	@descricao.setter
	def descricao(self, descricao: str|None) -> bool:
		if not descricao:
			self._descricao = None
		elif len(descricao.strip()) >= 6:
			self._descricao = descricao.strip()
		else:
			return False
		return True

	@property
	def diretorio(self) -> str:
		return self._diretorio

	@diretorio.setter
	def diretorio(self, diretorio: str|tuple[str, bool]) -> bool:
		if isinstance(diretorio, tuple):
			self._diretorio = datetime.now().strftime('%d%m%Y%H%M%S') + ''.join(random.choice(string.ascii_letters) for _ in range(5))
		else:
			self._diretorio = diretorio.strip()
		return True

	@property
	def imagens(self) -> list:
		return self._imagens

	@imagens.setter
	def imagens(self, imagens: list) -> bool:
		tamanho = len(imagens)
		for x in range(tamanho):
			imagens[x].classe = self.__class__.__name__ + '/' + self._diretorio
		self._imagens = imagens
		return True

	def imagens_append(self, imagem: str, hash: bool=False) -> Arquivo:
		self._imagens.append(Arquivo(classe=self.__class__.__name__ + os.path.sep + self._diretorio, nome=imagem, hash=hash, ativo=True))
		return self._imagens[-1]

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
