import sys
from sinteemar import iniciar

if len(sys.argv) > 1 and len(sys.argv) < 3:
	for arg in sys.argv:
		if arg.lower() == 'criar':
			from sinteemar import criar
			criar()
		if arg.lower() == 'validar':
			from sinteemar import validar
			validar()
	sys.exit()

app = iniciar()

if __name__ == '__main__':
	app.run()
