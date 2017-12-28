import os, random, string
from datetime import datetime
from sinteemar.config import BASE_DIR

class Arquivo():
	'''
	Atributos:
		id (int): Chave primária
		arquivo (str): Nome + extensão do arquivo
		caminho (str): Endereço relativo do arquivo
		caminho_absoluto (str): Endereço absoluto do arquivo
		classe (str): Classe referenciando o arquivo
		extensao (str): Extensão do arquivo (DOC, DOCX, JPG, JPEG, PDF, PNG)
		evento (int): Chave estrangeira do evento
		diretorio (str): Nome do diretório onde se encontra a imagem
		nome (str): Nome do arquivo
		ativo (bool): Inativo (False) ou ativo (True)
	'''
	__slots__ = ['_ativo', '_classe', '_diretorio', '_evento', '_extensao', '_id', '_nome']

	def __init__(self, id: int=0, classe: str='', nome: str='', evento: int=None, hash: bool=False, ativo: bool=False):
		self._id = id
		self._classe = classe.lower()
		self._diretorio = os.path.join(BASE_DIR, 'sinteemar', 'static', 'uploads', self._classe)
		self._extensao = nome.split('.')[-1]
		self._nome = nome[:-len(self._extensao) - 1] if not hash else datetime.now().strftime('%Y%m%d%H%M%S') + ''.join(random.choice(string.ascii_letters) for _ in range(5))
		self._evento = evento
		self._ativo = ativo

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nCLASSE: ' + self._classe + '\nDIRETÓRIO: ' + self._diretorio + '\nEXTENSÃO: ' + self._extensao + '\nNOME: ' + self._nome + '\nATIVO: ' + str(self._ativo)

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
	def arquivo(self) -> str:
		return self._nome + '.' + self._extensao

	@property
	def caminho_absoluto(self) -> str:
		return self._diretorio + os.path.sep + self._nome + '.' + self._extensao

	@property
	def caminho(self) -> str:
		return 'uploads/' + self._classe + '/' + self._nome + '.' + self._extensao

	@property
	def classe(self) -> str:
		return self._classe

	@classe.setter
	def classe(self, classe: str) -> bool:
		self._classe = classe.lower()
		classe = self._classe.split('/')
		if len(classe) == 2:
			self._diretorio = os.path.join(BASE_DIR, 'sinteemar', 'static', 'uploads', classe[0], classe[1])
		else:
			self._diretorio = os.path.join(BASE_DIR, 'sinteemar', 'static', 'uploads', self._classe)
		return True

	@property
	def diretorio(self) -> str:
		return self._diretorio

	@diretorio.setter
	def diretorio(self, diretorio: str) -> bool:
		self._diretorio = diretorio.lower()
		self._classe = os.path.split(self.diretorio)[1]
		return True

	@property
	def extensao(self) -> str:
		return self._extensao

	@extensao.setter
	def extensao(self, extensao: str) -> bool:
		self._extensao = extensao.lower()

	@property
	def nome(self) -> str:
		return self._nome

	@nome.setter
	def nome(self, nome: str) -> bool:
		self._extensao = nome.split('.')[-1]
		self._nome = nome[:-len(self._extensao) - 1]
		return True

	@property
	def evento(self) -> int:
		return self._evento

	@evento.setter
	def evento(self, evento: int) -> bool:
		if evento > 0:
			self._evento = evento
			return True
		return False

	@property
	def ativo(self) -> bool:
		return self._ativo

	@ativo.setter
	def ativo(self, ativo: bool) -> bool:
		self._ativo = bool(ativo)
		return True
