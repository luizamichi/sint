# Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá
Website com sistema de gerenciamento de conteúdo (CMS) desenvolvido para a publicação de registros característicos de um sindicato.
Planejado para ter fácil manutenibilidade, o software possui uma estrutura bem simplificada passível de modificações, podendo se adaptar à diferentes propósitos (o código-fonte é genérico e de possível utilização em outros projetos).


### Frameworks
- DataTables 1.11.4 (21 de janeiro de 2022)

- FontAwesome 5.15.4 (4 de agosto de 2021)

- jQuery 3.6.0 (2 de março de 2021)

- Materialize 1.0.0 (9 de setembro de 2018)

- Minimal Rich Text Editor 1.0.16 (21 de junho de 2020)


### Softwares
- Apache 2.4.52 (20 de dezembro de 2021)

- PHP 8.1.2 (20 de janeiro de 2022)


### Diretório
No download, você encontrará os seguintes diretórios e arquivos:
```
sinteemar/ 15.469
├── css/ 110
│   ├── dark.css 81
│   ├── datatables.min.css 6
│   ├── fontawesome.min.css 6
│   ├── materialize.min.css 11
│   └── richtext.min.css 6
├── img/ 47
│   ├── datatables/
│   │   ├── sort_asc.png
│   │   ├── sort_asc_disabled.png
│   │   ├── sort_both.png
│   │   ├── sort_desc.png
│   │   └── sort_desc_disabled.png
│   ├── models/
│   │   ├── arquivo.png
│   │   ├── aviso.png
│   │   ├── banner.png
│   │   ├── boletim.png
│   │   ├── convencao.png
│   │   ├── convenio.png
│   │   ├── diretoria.png
│   │   ├── edital.png
│   │   ├── estatuto.png
│   │   ├── evento.png
│   │   ├── financa.png
│   │   ├── jornal.png
│   │   ├── juridico.png
│   │   ├── noticia.png
│   │   ├── podcast.png
│   │   ├── usuario.png
│   │   └── video.png
│   ├── ajuda.png
│   ├── card.png
│   ├── document.svg 8
│   ├── facebook.svg 5
│   ├── fonte.png
│   ├── jornal.jpg
│   ├── luizamichi.svg 11
│   ├── noticia.jpg
│   ├── podcast.jpg
│   ├── sgc.svg 4
│   ├── sinteemar.jpg
│   ├── sinteemar.svg 8
│   ├── usuario.png
│   ├── whatsapp.svg 6
│   └── youtube.svg 5
├── js/ 218
│   ├── datatables.min.js 178
│   ├── jquery.mask.min.js 22
│   ├── jquery.min.js 6
│   ├── jquery.richtext.min.js 6
│   └── materialize.min.js 6
├── sgc/ 3.975
│   ├── models/ 1.092
│   │   ├── arquivos.php 57
│   │   ├── avisos.php 63
│   │   ├── banners.php 56
│   │   ├── boletins.php 57
│   │   ├── convencoes.php 64
│   │   ├── convenios.php 82
│   │   ├── diretoria.php 60
│   │   ├── editais.php 61
│   │   ├── estatuto.php 52
│   │   ├── eventos.php 61
│   │   ├── financas.php 57
│   │   ├── jornais.php 65
│   │   ├── juridicos.php 61
│   │   ├── noticias.php 75
│   │   ├── podcasts.php 65
│   │   ├── usuarios.php 101
│   │   └── videos.php 55
│   ├── crud.php 498
│   ├── dao.php 174
│   ├── index.php 113
│   ├── insertions.sql 994
│   ├── load.php 139
│   ├── login.php 70
│   ├── panel.php 711
│   └── persistence.sql 184
├── uploads/ 7
│   ├── index.php 5
│   └── video.vtt 2
└── webfonts/ 9.555
│   ├── fa-brands-400.eot
│   ├── fa-brands-400.svg 3.718
│   ├── fa-brands-400.ttf
│   ├── fa-brands-400.woff
│   ├── fa-brands-400.woff2
│   ├── fa-regular-400.eot
│   ├── fa-regular-400.svg 802
│   ├── fa-regular-400.ttf
│   ├── fa-regular-400.woff
│   ├── fa-regular-400.woff2
│   ├── fa-solid-900.eot
│   ├── fa-solid-900.svg 5.035
│   ├── fa-solid-900.ttf
│   ├── fa-solid-900.woff
│   └── fa-solid-900.woff2
├── .htaccess 16
├── boletins.php 68
├── cabecalho.php 94
├── convencoes.php 76
├── convenios.php 93
├── diretoria.php 49
├── editais.php 84
├── estatuto.php 39
├── eventos.php 92
├── favicon.ico
├── financas.php 84
├── historico.php 53
├── index.php 131
├── jornais.php 69
├── juridicos.php 50
├── navegador.php 92
├── noticias.php 91
├── php.ini 4
├── podcasts.php 62
├── README.md 166
├── rodape.php 84
└── videos.php 60
```


### Instalação
Primeiramente importe um novo banco de dados a partir do arquivo `sgc/persistence.sql` e insira os dados do arquivo `sgc/insertions.sql`, caso queira popular o banco, caso contrário é necessário criar um usuário para acessar o sistema (tabela de usuários).
Feito isso, copie todos os arquivos e pastas para o diretório raiz do servidor. Recomenda-se remover os arquivos `sgc/persistence.sql`, `sgc/insertions.sql` e `README.md` após a importação.
Agora, modifique as constantes `MYSQL_HOST` (endereço do servidor), `MYSQL_USER` (usuário do banco de dados), `MYSQL_PASSWORD` (senha do banco de dados), `MYSQL_SCHEMA` (nome do banco de dados) do arquivo `sgc/load.php` para os dados referente ao servidor do MySQL ou MariaDB.
Abra o arquivo `.htaccess` e altere o nome do host para o vinculado ao servidor em `RewriteRule`.
Pronto, o servidor está configurado e pronto para utilizar o sistema. Qualquer erro encontrado, verifique se está utilizando uma versão do PHP igual ou superior a 8.1.
As demais configurações possíveis do sistema estão disponíveis no arquivo `sgc/load.php`.


### Versão
1.2.1


### Direitos Autorais e Licença
Código de autoria do criador Luiz Joaquim Aderaldo Amichi, sob licença de uso do Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá (Sinteemar), totalizando 4.626 linhas de código autoral, 994 linhas com dados de backup e 9.849 linhas de código aberto (folhas de estilo em cascata, fontes, imagens e scripts).
