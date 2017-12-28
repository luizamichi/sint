class Permissao():
	'''
	Atributos:
		id (int): Chave primária
		admin (bool): Validador de administrador
		acessos (bool): Booleano se pode realizar LEITURA na tabela ACESSOS
		banners (bool): Booleano se pode realizar CRUD na tabela BANNERS
		boletins (bool): Booleano se pode realizar CRUD na tabela BOLETINS
		convencoes (bool): Booleano se pode realizar CRUD na tabela CONVENCOES
		convenios (bool): Booleano se pode realizar CRUD na tabela CONVENIOS
		diretoria (bool): Booleano se pode realizar LEITURA E ALTERAÇÃO na tabela DIRETORIA
		diretorio (bool): Booleano se pode realizar LEITURA no diretório
		editais (bool): Booleano se pode realizar CRUD na tabela EDITAIS
		estatuto (bool): Booleano se pode realizar LEITURA E ALTERAÇÃO na tabela ESTATUTO
		eventos (bool): Booleano se pode realizar CRUD na tabela EVENTOS
		financas (bool): Booleano se pode realizar CRUD na tabela FINANCAS
		historico (bool): Booleano se pode realizar CRUD na tabela HISTORICO
		jornais (bool): Booleano se pode realizar CRUD na tabela JORNAIS
		juridicos (bool): Booleano se pode realizar CRUD na tabela JURIDICOS
		noticias (bool): Booleano se pode realizar CRUD na tabela NOTICIAS
		registros (bool): Booleano se pode realizar LEITURA na tabela REGISTROS
		tabelas (bool): Booleano se pode realizar LEITURA nas tabelas
		usuarios (bool): Booleano se pode realizar CRUD na tabela USUARIOS
		videos (bool): Booleano se pode realizar CRUD na tabela VIDEOS
	'''
	__slots__ = ['_admin', '_acessos', '_banners', '_boletins', '_convencoes', '_convenios', '_diretoria', '_diretorio', '_editais', '_estatuto', '_eventos', '_financas', '_historico', '_id', '_jornais', '_juridicos', '_noticias', '_registros', '_tabelas', '_usuarios', '_videos']

	def __init__(self, id: int=0, admin: bool=False, acessos: bool=False, banners: bool=False, boletins: bool=False, convencoes: bool=False, convenios: bool=False, diretoria: bool=False, diretorio: bool=False, editais: bool=False, estatuto: bool=False, eventos: bool=False, financas: bool=False, historico: bool=False, jornais: bool=False, juridicos: bool=False, noticias: bool=False, registros: bool=False, tabelas: bool=False, usuarios: bool=False, videos: bool=False):
		self._id = id
		self._admin = admin
		self._acessos = acessos
		self._banners = banners
		self._boletins = boletins
		self._convencoes = convencoes
		self._convenios = convenios
		self._diretoria = diretoria
		self._diretorio = diretorio
		self._editais = editais
		self._estatuto = estatuto
		self._eventos = eventos
		self._financas = financas
		self._historico = historico
		self._jornais = jornais
		self._juridicos = juridicos
		self._noticias = noticias
		self._registros = registros
		self._tabelas = tabelas
		self._usuarios = usuarios
		self._videos = videos

	def __str__(self) -> str:
		return 'ID: ' + str(self._id) + '\nADMIN: ' + str(self._admin) + '\nBANNERS: ' + str(self._banners) + '\nBOLETINS: ' + str(self._boletins) + '\nCONVENÇÕES: ' + str(self._convencoes) + '\nCONVÊNIOS: ' + str(self._convenios) + '\nDIRETORIA: ' + str(self._diretoria) + '\nDIRETÓRIO: ' + str(self._diretorio) + '\nEDITAIS: ' + str(self._editais) + '\nESTATUTO: ' + str(self._estatuto) + '\nEVENTOS: ' + str(self._eventos) + '\nFINANÇAS: ' + str(self._financas) + '\nHISTÓRICO: ' + str(self._historico) + '\nJORNAIS: ' + str(self._jornais) + '\nJURÍDICOS: ' + str(self._juridicos) + '\nNOTÍCIAS: ' + str(self._noticias) + '\nREGISTROS: ' + str(self._registros) + '\nTABELAS: ' + str(self._tabelas) + '\nUSUÁRIOS: ' + str(self._usuarios) + '\nVÍDEOS: ' + str(self._videos)

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
	def admin(self) -> bool:
		return self._admin

	@admin.setter
	def admin(self, admin: bool) -> bool:
		self._admin = bool(admin)
		return True

	@property
	def acessos(self) -> bool:
		return self._acessos

	@acessos.setter
	def acessos(self, acessos: bool) -> bool:
		self._acessos = bool(acessos)
		return True

	@property
	def banners(self) -> bool:
		return self._banners

	@banners.setter
	def banners(self, banners: bool) -> bool:
		self._banners = bool(banners)
		return True

	@property
	def boletins(self) -> bool:
		return self._boletins

	@boletins.setter
	def boletins(self, boletins: bool) -> bool:
		self._boletins = bool(boletins)
		return True

	@property
	def convencoes(self) -> bool:
		return self._convencoes

	@convencoes.setter
	def convencoes(self, convencoes: bool) -> bool:
		self._convencoes = bool(convencoes)
		return True

	@property
	def convenios(self) -> bool:
		return self._convenios

	@convenios.setter
	def convenios(self, convenios: bool) -> bool:
		self._convenios = bool(convenios)
		return True

	@property
	def diretoria(self) -> bool:
		return self._diretoria

	@diretoria.setter
	def diretoria(self, diretoria: bool) -> bool:
		self._diretoria = bool(diretoria)
		return True

	@property
	def diretorio(self) -> bool:
		return self._diretorio

	@diretorio.setter
	def diretorio(self, diretorio: bool) -> bool:
		self._diretorio = bool(diretorio)
		return True

	@property
	def editais(self) -> bool:
		return self._editais

	@editais.setter
	def editais(self, editais: bool) -> bool:
		self._editais = bool(editais)
		return True

	@property
	def estatuto(self) -> bool:
		return self._estatuto

	@estatuto.setter
	def estatuto(self, estatuto: bool) -> bool:
		self._estatuto = bool(estatuto)
		return True

	@property
	def eventos(self) -> bool:
		return self._eventos

	@eventos.setter
	def eventos(self, eventos: bool) -> bool:
		self._eventos = bool(eventos)
		return True

	@property
	def financas(self) -> bool:
		return self._financas

	@financas.setter
	def financas(self, financas: bool) -> bool:
		self._financas = bool(financas)
		return True

	@property
	def historico(self) -> bool:
		return self._historico

	@historico.setter
	def historico(self, historico: bool) -> bool:
		self._historico = bool(historico)
		return True

	@property
	def jornais(self) -> bool:
		return self._jornais

	@jornais.setter
	def jornais(self, jornais: bool) -> bool:
		self._jornais = bool(jornais)
		return True

	@property
	def juridicos(self) -> bool:
		return self._juridicos

	@juridicos.setter
	def juridicos(self, juridicos: bool) -> bool:
		self._juridicos = bool(juridicos)
		return True

	@property
	def noticias(self) -> bool:
		return self._noticias

	@noticias.setter
	def noticias(self, noticias: bool) -> bool:
		self._noticias = bool(noticias)
		return True

	@property
	def registros(self) -> bool:
		return self._registros

	@registros.setter
	def registros(self, registros: bool) -> bool:
		self._registros = bool(registros)
		return True

	@property
	def tabelas(self) -> bool:
		return self._tabelas

	@tabelas.setter
	def tabelas(self, tabelas: bool) -> bool:
		self._tabelas = bool(tabelas)
		return True

	@property
	def usuarios(self) -> bool:
		return self._usuarios

	@usuarios.setter
	def usuarios(self, usuarios: bool) -> bool:
		self._usuarios = bool(usuarios)
		return True

	@property
	def videos(self) -> bool:
		return self._videos

	@videos.setter
	def videos(self, videos: bool) -> bool:
		self._videos = bool(videos)
		return True

	@property
	def dicionario(self) -> dict:
		dicionario = {}
		dicionario['administrador'] = True if self._admin else False
		dicionario['acessos'] = True if self._acessos else False
		dicionario['banners'] = True if self._banners else False
		dicionario['boletins'] = True if self._boletins else False
		dicionario['convenções'] = True if self._convencoes else False
		dicionario['convênios'] = True if self._convenios else False
		dicionario['diretoria'] = True if self._diretoria else False
		dicionario['diretório'] = True if self._diretorio else False
		dicionario['editais'] = True if self._editais else False
		dicionario['estatuto'] = True if self._estatuto else False
		dicionario['eventos'] = True if self._eventos else False
		dicionario['finanças'] = True if self._financas else False
		dicionario['histórico'] = True if self._historico else False
		dicionario['jornais'] = True if self._jornais else False
		dicionario['jurídicos'] = True if self._juridicos else False
		dicionario['notícias'] = True if self._noticias else False
		dicionario['registros'] = True if self._registros else False
		dicionario['tabelas'] = True if self._tabelas else False
		dicionario['usuários'] = True if self._usuarios else False
		dicionario['vídeos'] = True if self._videos else False
		return dicionario

	@property
	def lista(self) -> list:
		lista = []
		if self._admin:
			lista.append('administrador')
		if self._acessos:
			lista.append('acessos')
		if self._banners:
			lista.append('banners')
		if self._boletins:
			lista.append('boletins')
		if self._convencoes:
			lista.append('convenções')
		if self._convenios:
			lista.append('convênios')
		if self._diretoria:
			lista.append('diretoria')
		if self._diretorio:
			lista.append('diretório')
		if self._editais:
			lista.append('editais')
		if self._estatuto:
			lista.append('estatuto')
		if self._eventos:
			lista.append('eventos')
		if self._financas:
			lista.append('finanças')
		if self._historico:
			lista.append('histórico')
		if self._jornais:
			lista.append('jornais')
		if self._juridicos:
			lista.append('jurídicos')
		if self._noticias:
			lista.append('notícias')
		if self._registros:
			lista.append('registros')
		if self._tabelas:
			lista.append('tabelas')
		if self._usuarios:
			lista.append('usuários')
		if self._videos:
			lista.append('vídeos')
		return lista

	@property
	def string(self) -> str:
		return ', '.join(self.lista).upper()

	@property
	def vazio(self) -> bool:
		if self.lista:
			return False
		return True
