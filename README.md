# Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá
Website desenvolvido utilizando as linguagens CSS, HTML, JavaScript e Python, com os *frameworks* Bootstrap, Flask, Font Awesome, jQuery, Summernote e tablesorter. Planejado para a exibição de dados relacionados ao sindicato, além de possuir um sistema de gerenciamento de conteúdo (CMS) para manipulação dos dados.

### Conteúdo
- [Softwares](#softwares)
- [Diretório](#Diretório)
- [Versão](#versão)
- [Direitos Autorais e Licença](#direitos-autorais-e-licença)

### Softwares
Bootstrap 4.5.3 (13 de outubro de 2020)

Flask 1.1.2 (3 de abril de 2020)

Font Awesome 5.15.1 (5 de outoubro de 2020)

jQuery 3.5.1 (4 de maio de 2019)

Python 3.9.0 (5 de outubro de 2020)

Summernote 0.8.18 (20 de maio de 2020)

tablesorter 2.31.3 (3 de março de 2020)

### Diretório
No download, você encontrará os seguintes diretórios e arquivos:
```
sinteemar/
├── sinteemar/
│   ├── controllers/
│   │   ├── __init__.py
│   │   ├── sgc.py
│   │   └── website.py
│   ├── dao/
│   │   ├── __init__.py
│   │   ├── acesso.py
│   │   ├── arquivo.py
│   │   ├── banner.py
│   │   ├── boletim.py
│   │   ├── convencao.py
│   │   ├── convenio.py
│   │   ├── diretoria.py
│   │   ├── edital.py
│   │   ├── estatuto.py
│   │   ├── evento.py
│   │   ├── financa.py
│   │   ├── historico.py
│   │   ├── jornal.py
│   │   ├── juridico.py
│   │   ├── noticia.py
│   │   ├── permissao.py
│   │   ├── registro.py
│   │   ├── usuario.py
│   │   └── video.py
│   ├── db/
│   │   ├── __init__.py
│   │   ├── database.py
│   │   └── tabelas.sql
│   ├── models/
│   │   ├── __init__.py
│   │   ├── acesso.py
│   │   ├── arquivo.py
│   │   ├── banner.py
│   │   ├── boletim.py
│   │   ├── convencao.py
│   │   ├── convenio.py
│   │   ├── diretoria.py
│   │   ├── edital.py
│   │   ├── estatuto.py
│   │   ├── evento.py
│   │   ├── financa.py
│   │   ├── historico.py
│   │   ├── jornal.py
│   │   ├── juridico.py
│   │   ├── noticia.py
│   │   ├── permissao.py
│   │   ├── registro.py
│   │   ├── usuario.py
│   │   └── video.py
│   ├── static/
│   │   ├── css/
│   │   │   ├── all.min.css
│   │   │   ├── bootstrap.min.css
│   │   │   ├── bootstrap.min.css.map
│   │   │   ├── error-style.css
│   │   │   ├── login-style.css
│   │   │   ├── maintenance-style.css
│   │   │   ├── sgc-style.css
│   │   │   ├── style.css
│   │   │   └── summernote-bs4.min.css
│   │   ├── img/
│   │   │   ├── favicon.ico
│   │   │   ├── logo.svg
│   │   │   ├── luiz_amichi.svg
│   │   │   ├── manutencao.svg
│   │   │   ├── sgc.ico
│   │   │   ├── sgc.png
│   │   │   └── sgc.svg
│   │   ├── js/
│   │   │   ├── bootstrap.bundle.min.js
│   │   │   ├── bootstrap.bundle.min.js.map
│   │   │   ├── jquery.min.js
│   │   │   ├── jquery.min.map
│   │   │   ├── jquery.tablesorter.min.js
│   │   │   ├── script.js
│   │   │   ├── summernote-bs4.min.js
│   │   │   ├── summernote-bs4.min.js.map
│   │   │   └── summernote-pt-BR.min.js
│   │   ├── uploads/
│   │   │   ├── boletim.jpg
│   │   │   ├── jornal.jpg
│   │   │   ├── noticia.jpg
│   │   │   └── upload.txt
│   │   └── webfonts/
│   │       ├── fa-brands-400.eot
│   │       ├── fa-brands-400.svg
│   │       ├── fa-brands-400.ttf
│   │       ├── fa-brands-400.woff
│   │       ├── fa-brands-400.woff2
│   │       ├── fa-regular-400.eot
│   │       ├── fa-regular-400.svg
│   │       ├── fa-regular-400.ttf
│   │       ├── fa-regular-400.woff
│   │       ├── fa-regular-400.woff2
│   │       ├── fa-solid-900.eot
│   │       ├── fa-solid-900.svg
│   │       ├── fa-solid-900.ttf
│   │       ├── fa-solid-900.woff
│   │       ├── fa-solid-900.woff2
│   │       ├── summernote.eot
│   │       ├── summernote.ttf
│   │       ├── summernote.woff
│   │       └── summernote.woff2
│   ├── templates/
│   │   ├── sgc/
│   │   │   ├── acessos/
│   │   │   │   └── index.html
│   │   │   ├── banners/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── boletins/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── conta/
│   │   │   │   ├── alterar.html
│   │   │   │   └── index.html
│   │   │   ├── convencoes/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── convenios/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── diretoria/
│   │   │   │   ├── alterar.html
│   │   │   │   └── index.html
│   │   │   ├── diretorio/
│   │   │   │   └── index.html
│   │   │   ├── editais/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── estatuto/
│   │   │   │   ├── alterar.html
│   │   │   │   └── index.html
│   │   │   ├── eventos/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── financas/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── historico/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── jornais/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── juridicos/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── noticias/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── registros/
│   │   │   │   └── index.html
│   │   │   ├── tabelas/
│   │   │   │   └── index.html
│   │   │   ├── usuarios/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── videos/
│   │   │   │   ├── alterar.html
│   │   │   │   ├── index.html
│   │   │   │   └── inserir.html
│   │   │   ├── ajuda.html
│   │   │   ├── base.html
│   │   │   ├── index.html
│   │   │   ├── login.html
│   │   │   ├── modal.html
│   │   │   ├── navegador.html
│   │   │   └── suporte.html
│   │   ├── base.html
│   │   ├── boletins.html
│   │   ├── convencoes.html
│   │   ├── convenios.html
│   │   ├── diretoria.html
│   │   ├── editais.html
│   │   ├── erro.html
│   │   ├── estatuto.html
│   │   ├── eventos.html
│   │   ├── financas.html
│   │   ├── historico.html
│   │   ├── index.html
│   │   ├── jornais.html
│   │   ├── juridicos.html
│   │   ├── manutencao.html
│   │   ├── midias-sociais.html
│   │   ├── navegador.html
│   │   ├── noticias.html
│   │   └── videos.html
│   ├── __init__.py
│   └── config.py
├── README.md
├── requirements.txt
└── wsgi.py
```

### Versão
0.0

### Direitos Autorais e Licença
Código e documentação de autoria do criador Luiz Joaquim Aderaldo Amichi, sob licença de uso do Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá (Sinteemar).