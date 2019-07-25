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

- PHP 7.4.27 (16 de dezembro de 2021)


### Diretório
No download, você encontrará os seguintes diretórios e arquivos:
```
sinteemar/ 15.117
├── css/ 25
│   ├── datatables.min.css 5
│   ├── fontawesome.min.css 5
│   ├── materialize.min.css 10
│   └── richtext.min.css 5
├── img/ 40
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
│   ├── document.svg 7
│   ├── facebook.svg 4
│   ├── fonte.png
│   ├── jornal.jpg
│   ├── luizamichi.svg 10
│   ├── noticia.jpg
│   ├── podcast.jpg
│   ├── sgc.svg 3
│   ├── sinteemar.jpg
│   ├── sinteemar.svg 7
│   ├── usuario.png
│   ├── whatsapp.svg 5
│   └── youtube.svg 4
├── js/ 210
│   ├── datatables.min.js 174
│   ├── jquery.mask.min.js 21
│   ├── jquery.min.js 5
│   ├── jquery.richtext.min.js 5
│   └── materialize.min.js 5
├── sgc/ 3.843
│   ├── models/ 1.075
│   │   ├── arquivos.php 56
│   │   ├── avisos.php 62
│   │   ├── banners.php 55
│   │   ├── boletins.php 56
│   │   ├── convencoes.php 63
│   │   ├── convenios.php 81
│   │   ├── diretoria.php 59
│   │   ├── editais.php 60
│   │   ├── estatuto.php 51
│   │   ├── eventos.php 60
│   │   ├── financas.php 56
│   │   ├── jornais.php 64
│   │   ├── juridicos.php 60
│   │   ├── noticias.php 74
│   │   ├── podcasts.php 64
│   │   ├── usuarios.php 100
│   │   └── videos.php 54
│   ├── crud.php 524
│   ├── dao.php 192
│   ├── index.php 129
│   ├── insertions.sql 925
│   ├── panel.php 826
│   └── persistence.sql 172
├── uploads/ 5
├── ├── index.php 4
│   └── video.vtt 1
├── webfontes/ 9.552
│   ├── fa-brands-400.eot
│   ├── fa-brands-400.svg 3.717
│   ├── fa-brands-400.ttf
│   ├── fa-brands-400.woff
│   ├── fa-brands-400.woff2
│   ├── fa-regular-400.eot
│   ├── fa-regular-400.svg 801
│   ├── fa-regular-400.ttf
│   ├── fa-regular-400.woff
│   ├── fa-regular-400.woff2
│   ├── fa-solid-900.eot
│   ├── fa-solid-900.svg 5.034
│   ├── fa-solid-900.ttf
│   ├── fa-solid-900.woff
│   └── fa-solid-900.woff2
├── .htaccess 15
├── boletins.php 66
├── cabecalho.php 95
├── convencoes.php 63
├── convenios.php 91
├── diretoria.php 47
├── editais.php 81
├── estatuto.php 37
├── eventos.php 89
├── financas.php 72
├── historico.php 51
├── index.php 127
├── jornais.php 57
├── juridicos.php 48
├── navegador.php 71
├── noticias.php 89
├── php.ini 3
├── podcasts.php 59
├── README.md 160
├── rodape.php 73
└── videos.php 48
```


### Instalação
Primeiramente importe um novo banco de dados a partir do arquivo `sgc/persistence.sql` e insira os dados do arquivo `sgc/insertions.sql`, caso queira popular o banco, se não é necessário criar um usuário para acessar o sistema.
Feito isso, copie todos os arquivos e pastas para o diretório raiz do servidor. Recomenda-se remover os arquivos `sgc/persistence.sql`, `sgc/insertions.sql` e `README.md` após a importação.
Agora, modifique as variáveis `$host` (endereço do servidor), `$user` (usuário do banco de dados), `$passowrd` (senha do banco de dados), `$schema` (nome do banco de dados) do arquivo `sgc/dao.php` para os dados referente ao servidor do MySQL.
Abra o arquivo `.htaccess` e altere o nome do host para o vinculado ao servidor em `RewriteRule`.
Pronto, o servidor está configurado e pronto para utilizar o sistema. Qualquer erro encontrado, verifique se está utilizando uma versão do PHP superior a 7.4.


### Versão
1.2.0


### Direitos Autorais e Licença
Código de autoria do criador Luiz Joaquim Aderaldo Amichi, sob licença de uso do Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá (Sinteemar), totalizando 4.382 linhas de código autoral, 925 linhas com dados de backup e 9.810 linhas de código aberto (folhas de estilo em cascata, fontes, imagens e scripts).