from datetime import date, datetime
from sinteemar.models.arquivo import Arquivo
from sinteemar.models.usuario import Usuario

class Financa():
	'''
	Atributos:
		id (int): Chave primária
		periodo (str): Período (semanal, mensal, bimestral, trimestral, semestral ou anual)
		flag (str): Valor utilizado para ordenação por período
		documento (Arquivo): Documento
		usuario (Usuario): Usuário
		data (datetime): Data de cadastro da finança
		ativo (bool): Inativo (False) ou ativo (True)
	'''
	__slots__ = ['_ativo', '_data', '_documento', '_flag', '_id', '_periodo', '_usuario']

	def __init__(self, id: int=0, periodo: str='', flag: str=datetime.now().strftime('%Y%m'), documento: str='', usuario: Usuario=Usuario(), data: datetime=datetime.now(), ativo: bool=False):
		self._id = id
		self._periodo = periodo
		self._flag = flag
		self._documento = Arquivo(classe=self.__class__.__name__, nome=documento)
		self._usuario = usuario
		self._data = data
		self._ativo = ativo

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nPERÍODO: ' + self._periodo + '\nFLAG: ' + self._flag + '\nDOCUMENTO: ' + self._documento.arquivo + '\nUSUÁRIO: ' + self._usuario.nome.title() + '\nDATA: ' + self._data.strftime('%d/%m/%Y - %H:%M:%S') + '\nATIVO: ' + str(self._ativo)

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
	def periodo(self) -> str:
		return self._periodo

	@periodo.setter
	def periodo(self, periodo: str) -> bool:
		self._periodo = periodo.strip()
		flag = {'Jan': '01', 'Fev': '02', 'Mar': '03', 'Abr': '04', 'Mai': '05', 'Jun': '06', 'Jul': '07', 'Ago': '08', 'Set': '09', 'Out': '10', 'Nov': '11', 'Dez': '12', '1º ': '03.1', '2º ': '06.1', '3º ': '09.1', '4º ': '12.1'}
		self._flag = periodo[-4:] + flag[periodo[:3]]
		return True

	@property
	def flag(self) -> str:
		return self._flag

	@flag.setter
	def flag(self, flag: str) -> bool:
		periodos = {'01': 'Janeiro', '02': 'Fevereiro', '03': 'Março', '04': 'Abril', '05': 'Maio', '06': 'Junho', '07': 'Julho', '08': 'Agosto', '09': 'Setembro', '10': 'Outubro', '11': 'Novembro', '12': 'Dezembro', '03.1': '1º Trimestre', '06.1': '2º Trimestre', '09.1': '3º Trimestre', '12.1': '4º Trimestre'}
		ano_atual = int(date.today().strftime('%Y'))
		anos = [str(x) for x in range(ano_atual - 7, ano_atual + 3)]
		meses = periodos.keys()
		if flag[4:] in meses and flag[:4] in anos:
			self._flag = flag
			self._periodo = periodos[flag[4:]] + ' de ' + flag[:4]
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

	@property
	def ativo(self) -> bool:
		return self._ativo

	@ativo.setter
	def ativo(self, ativo: bool) -> bool:
		self._ativo = bool(ativo)
		return True
