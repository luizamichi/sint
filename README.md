# Sinteemar
Website com sistema de gerenciamento de conteúdo (CMS) desenvolvido com as linguagens CSS, HTML, JavaScript, PHP e os *frameworks* Bootstrap, jQuery e SummerNote. Planejado para a publicação de notícias e demais conteúdos característicos de um sindicato.

### Conteúdo
- [Softwares](#softwares)
- [Hardware de Teste](#hardware-de-teste)
- [Sistema Operacional de Teste](#sistema-operacional-de-teste)
- [Navegadores de Teste](#navegadores-de-teste)
- [Diretório](#diretório)
- [Versão](#versão)
- [Direitos Autorais e Licença](#direitos-autorais-e-licença)

### Softwares
Apache 2.4.41 (13 de abril de 2020)

Bootstrap 4.4.1 (28 de novembro de 2019)

jQuery 3.4.1 (1 de maio de 2019)

PHP 7.4.3 (26 de maio de 2020)

### Hardware de Teste
**Dispositivo**: Raspberry Pi 3B+ (14 de março de 2018)

**Disco de Armazenamento**: SanDisk Ultra Micro SDHC 32GB Classe 10 (25 de setembro de 2015)

### Sistema Operacional de Teste
Raspberry Pi OS (27 de maio de 2020)

### Navegadores de Teste
Google Chrome 83.0.4103.116 (22 de junho de 2020)

Microsoft Edge 83.0.478.61 (9 de julho de 2020)

Mozilla Firefox 78.0.2 (9 de julho de 2020)

### Diretório
No download, você encontrará os seguintes diretórios e arquivos:
```
sinteemar/
├── audios/
│   └── sinteemar.mp3
├── cliente/
│   ├── .htaccess
│   ├── card.jpg
│   ├── favicon.ico
│   └── index.php
├── controladores/
│   ├── acesso.php
│   ├── alterar_banner.php
│   ├── alterar_boletim.php
│   ├── alterar_convencao.php
│   ├── alterar_convenio.php
│   ├── alterar_edital.php
│   ├── alterar_evento.php
│   ├── alterar_financa.php
│   ├── alterar_jornal.php
│   ├── alterar_juridico.php
│   ├── alterar_noticia.php
│   ├── alterar_podcast.php
│   ├── alterar_senha.php
│   ├── alterar_tema.php
│   ├── alterar_usuario.php
│   ├── banner.php
│   ├── boletim.php
│   ├── convencao.php
│   ├── convenio.php
│   ├── database.php
│   ├── edital.php
│   ├── evento.php
│   ├── financa.php
│   ├── imagem.php
│   ├── inserir_acesso.php
│   ├── inserir_banner.php
│   ├── inserir_boletim.php
│   ├── inserir_convencao.php
│   ├── inserir_convenio.php
│   ├── inserir_edital.php
│   ├── inserir_evento.php
│   ├── inserir_financa.php
│   ├── inserir_jornal.php
│   ├── inserir_juridico.php
│   ├── inserir_noticia.php
│   ├── inserir_podcast.php
│   ├── inserir_usuario.php
│   ├── jornal.php
│   ├── juridico.php
│   ├── login.php
│   ├── logout.php
│   ├── noticia.php
│   ├── permissao.php
│   ├── podcast.php
│   ├── procurar_banner.php
│   ├── procurar_boletim.php
│   ├── procurar_convencao.php
│   ├── procurar_convenio.php
│   ├── procurar_edital.php
│   ├── procurar_evento.php
│   ├── procurar_financa.php
│   ├── procurar_jornal.php
│   ├── procurar_juridico.php
│   ├── procurar_noticia.php
│   ├── procurar_podcast.php
│   ├── procurar_registro.php
│   ├── procurar_usuario.php
│   ├── registro.php
│   ├── remover_banner.php
│   ├── remover_boletim.php
│   ├── remover_convencao.php
│   ├── remover_convenio.php
│   ├── remover_edital.php
│   ├── remover_evento.php
│   ├── remover_financa.php
│   ├── remover_jornal.php
│   ├── remover_juridico.php
│   ├── remover_noticia.php
│   ├── remover_podcast.php
│   ├── remover_usuario.php
│   ├── renovar_sessao.php
│   └── usuario.php
├── css/
│   ├── font/
│   │   ├── summernote.eot
│   │   ├── summernote.ttf
│   │   ├── summernote.woff
│   │   └── summernote.woff2
│   ├── bootstrap.min.css
│   ├── bootstrap.min.css.map
│   ├── erro.css
│   ├── estilo.css
│   ├── login.css
│   ├── manutencao.css
│   ├── sgc.css
│   ├── sgc-dark.css
│   └── summernote-lite.min.css
├── dao/
│   ├── .htaccess
│   ├── AcessoDAO.php
│   ├── BannerDAO.php
│   ├── BoletimDAO.php
│   ├── ConvencaoDAO.php
│   ├── ConvenioDAO.php
│   ├── EditalDAO.php
│   ├── EventoDAO.php
│   ├── FinancaDAO.php
│   ├── ImagemDAO.php
│   ├── JornalDAO.php
│   ├── JuridicoDAO.php
│   ├── NoticiaDAO.php
│   ├── PermissaoDAO.php
│   ├── PodcastDAO.php
│   ├── RegistroDAO.php
│   └── UsuarioDAO.php
├── database/
│   ├── .htaccess
│   ├── database.sql
│   └── insercoes.sql
├── imagens/
│   ├── alterar.svg
│   ├── ancora.svg
│   ├── aniversariante.svg
│   ├── antigo.svg
│   ├── aposentado.svg
│   ├── artigo.svg
│   ├── banner.svg
│   ├── boletim.svg
│   ├── calendario.svg
│   ├── celular.svg
│   ├── convencao.svg
│   ├── convenio.svg
│   ├── css.svg
│   ├── diretoria.svg
│   ├── diretorio.svg
│   ├── doc.svg
│   ├── documento.svg
│   ├── edital.svg
│   ├── email.svg
│   ├── erro.svg
│   ├── estatuto.svg
│   ├── evento.svg
│   ├── facebook.svg
│   ├── favicon.ico
│   ├── filiacao.svg
│   ├── financas.svg
│   ├── historico.svg
│   ├── html.svg
│   ├── ico.svg
│   ├── imagens.html
│   ├── inicio.svg
│   ├── instagram.svg
│   ├── institucional.svg
│   ├── jornal.svg
│   ├── jpg.svg
│   ├── js.svg
│   ├── juridico.svg
│   ├── localizacao.svg
│   ├── login.svg
│   ├── logo.svg
│   ├── logout.svg
│   ├── luiz_amichi.svg
│   ├── manutencao.svg
│   ├── map.svg
│   ├── mp3.svg
│   ├── noticia.svg
│   ├── pasta.svg
│   ├── pdf.svg
│   ├── php.svg
│   ├── png.svg
│   ├── podcast.svg
│   ├── procurar.svg
│   ├── py.svg
│   ├── registro.svg
│   ├── relogio.svg
│   ├── remover.svg
│   ├── sinteemar.svg
│   ├── sql.svg
│   ├── svg.svg
│   ├── tabela.svg
│   ├── telefone.svg
│   ├── twitter.svg
│   ├── usuario.svg
│   ├── web.svg
│   ├── whatsapp.svg
│   ├── x.svg
│   └── youtube.svg
├── js/
│   ├── bootstrap.bundle.min.js
│   ├── bootstrap.bundle.min.js.map
│   ├── jquery.slim.min.js
│   ├── jquery.slim.min.map
│   ├── summernote-lite.min.js
│   ├── summernote-lite.min.js.map
│   └── summernote-pt-BR.min.js
├── modelos/
│   ├── .htaccess
│   ├── Acesso.php
│   ├── Banner.php
│   ├── Boletim.php
│   ├── Convencao.php
│   ├── Convenio.php
│   ├── Database.php
│   ├── Edital.php
│   ├── Evento.php
│   ├── Financa.php
│   ├── Jornal.php
│   ├── Juridico.php
│   ├── Noticia.php
│   ├── Permissao.php
│   ├── Podcast.php
│   ├── Registro.php
│   └── Usuario.php
├── sgc/
│   ├── alterar_banner.php
│   ├── alterar_boletim.php
│   ├── alterar_convencao.php
│   ├── alterar_convenio.php
│   ├── alterar_edital.php
│   ├── alterar_evento.php
│   ├── alterar_financa.php
│   ├── alterar_jornal.php
│   ├── alterar_juridico.php
│   ├── alterar_noticia.php
│   ├── alterar_podcast.php
│   ├── alterar_senha.php
│   ├── alterar_usuario.php
│   ├── banners.php
│   ├── boletins.php
│   ├── convencoes.php
│   ├── convenios.php
│   ├── diretorios.php
│   ├── editais.php
│   ├── eventos.php
│   ├── financas.php
│   ├── imagens.php
│   ├── index.php
│   ├── inserir_banner.php
│   ├── inserir_boletim.php
│   ├── inserir_convencao.php
│   ├── inserir_convenio.php
│   ├── inserir_edital.php
│   ├── inserir_evento.php
│   ├── inserir_financa.php
│   ├── inserir_jornal.php
│   ├── inserir_juridico.php
│   ├── inserir_noticia.php
│   ├── inserir_podcast.php
│   ├── inserir_usuario.php
│   ├── jornais.php
│   ├── juridicos.php
│   ├── menu.php
│   ├── noticias.php
│   ├── paginacao.php
│   ├── podcasts.php
│   ├── registros.php
│   ├── rodape.html
│   ├── tabelas.php
│   └── usuarios.php
├── testes/
│   ├── .htaccess
│   ├── dao_acesso.php
│   ├── dao_banner.php
│   ├── dao_boletim.php
│   ├── dao_convencao.php
│   ├── dao_convenio.php
│   ├── dao_edital.php
│   ├── dao_evento.php
│   ├── dao_financa.php
│   ├── dao_imagem.php
│   ├── dao_jornal.php
│   ├── dao_juridico.php
│   ├── dao_noticia.php
│   ├── dao_permissao.php
│   ├── dao_podcast.php
│   ├── dao_registro.php
│   ├── dao_usuario.php
│   ├── database.php
│   ├── modelo_acesso.php
│   ├── modelo_banner.php
│   ├── modelo_boletim.php
│   ├── modelo_convencao.php
│   ├── modelo_convenio.php
│   ├── modelo_edital.php
│   ├── modelo_evento.php
│   ├── modelo_financa.php
│   ├── modelo_jornal.php
│   ├── modelo_juridico.php
│   ├── modelo_noticia.php
│   ├── modelo_permissao.php
│   ├── modelo_podcast.php
│   ├── modelo_registro.php
│   ├── modelo_usuario.php
│   └── vetor_imagem.php
├── uploads/
│   ├── banners/
│   │   └── .htaccess
│   ├── boletins/
│   │   └── .htaccess
│   ├── convencoes/
│   │   └── .htaccess
│   ├── convenios/
│   │   └── .htaccess
│   ├── editais/
│   │   └── .htaccess
│   ├── eventos/
│   │   └── .htaccess
│   ├── financas/
│   │   └── .htaccess
│   ├── historico/
│   │   ├── .htaccess
│   │   ├── 1986-1989.pdf
│   │   ├── 1989-1992.pdf
│   │   ├── 1992-1995.pdf
│   │   ├── 1995-1998.pdf
│   │   ├── 1998-2001.pdf
│   │   ├── 2001-2004.pdf
│   │   ├── 2004-2007.pdf
│   │   ├── 2007-2010.pdf
│   │   ├── 2010-2013.pdf
│   │   ├── 2013-2016.pdf
│   │   ├── 2016-2019.pdf
│   │   └── 2019-2022.pdf
│   ├── jornais/
│   │   └── .htaccess
│   ├── juridicos/
│   │   └── .htaccess
│   ├── noticias/
│   │   └── .htaccess
│   ├── podcasts/
│   │   └── .htaccess
│   ├── .htaccess
│   ├── boletim.svg
│   ├── card.jpg
│   ├── card.svg
│   ├── edital.svg
│   ├── jornal.svg
│   ├── noticia.svg
│   └── podcast.svg
├── .htaccess
├── boletins.php
├── cabecalho.php
├── convencoes.php
├── convenios.php
├── diretoria.php
├── editais.php
├── erro.html
├── estatuto.php
├── eventos.php
├── financas.php
├── historico.php
├── index.php
├── institucional.php
├── jornais.php
├── juridicos.php
├── manutencao.html
├── noticias.php
├── paginacao.php
├── php.ini
├── podcasts.php
├── README.md
├── rodape.html
├── sgc.php
└── videos.php
```

### Versão
2.0

### Direitos Autorais e Licença
Código e documentação de autoria do criador Luiz Joaquim Aderaldo Amichi, sob licença de uso do Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá (Sinteemar).