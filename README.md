# Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá
Website com sistema de gerenciamento de conteúdo (CMS) desenvolvido para a publicação de registros característicos de um sindicato. Planejado para ter fácil manutenibilidade, o software possui uma estrutura bem simplificada passível de modificações.


### Frameworks
- DataTables 1.10.23 (18 de dezembro de 2020)

- FontAwesome 5.15.2 (13 de janeiro de 2021)

- jQuery 3.5.1 (4 de maio de 2020)

- Materialize 1.0.0 (9 de setembro de 2018)

- Minimal Rich Text Editor 1.0.16 (21 de junho de 2020)


### Softwares
- Apache 2.4.46 (7 de agosto de 2020)

- PHP 7.4.15 (4 de fevereiro de 2021)


### Diretório
No download, você encontrará os seguintes diretórios e arquivos:
```
sinteemar/ 14.529
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
│   ├── jornal.jpg
│   ├── luizamichi.svg 10
│   ├── noticia.jpg
│   ├── podcast.jpg
│   ├── sgc.svg 3
│   ├── sinteemar.jpg
│   ├── sinteemar.svg 7
│   ├── whatsapp.svg 5
│   └── youtube.svg 4
├── js/ 210
│   ├── datatables.min.js 174
│   ├── jquery.mask.min.js 21
│   ├── jquery.min.js 5
│   ├── jquery.richtext.min.js 5
│   └── materialize.min.js 5
├── sgc/ 3.358
│   ├── models/ 906
│   │   ├── arquivos.php 50
│   │   ├── banners.php 45
│   │   ├── boletins.php 50
│   │   ├── convencoes.php 57
│   │   ├── convenios.php 70
│   │   ├── diretoria.php 53
│   │   ├── editais.php 54
│   │   ├── estatuto.php 45
│   │   ├── eventos.php 54
│   │   ├── financas.php 50
│   │   ├── jornais.php 58
│   │   ├── juridicos.php 54
│   │   ├── noticias.php 68
│   │   ├── podcasts.php 58
│   │   ├── usuarios.php 92
│   │   └── videos.php 48
│   ├── crud.php 378
│   ├── dao.php 177
│   ├── index.php 92
│   ├── insertions.sql 890
│   ├── panel.php 771
│   └── persistence.sql 144
├── uploads/ 4
│   └── index.php 4
├── webfontes/ 9.528
│   ├── fa-brands-400.eot
│   ├── fa-brands-400.svg 3.711
│   ├── fa-brands-400.ttf
│   ├── fa-brands-400.woff
│   ├── fa-brands-400.woff2
│   ├── fa-regular-400.eot
│   ├── fa-regular-400.svg 795
│   ├── fa-regular-400.ttf
│   ├── fa-regular-400.woff
│   ├── fa-regular-400.woff2
│   ├── fa-solid-900.eot
│   ├── fa-solid-900.svg 5.022
│   ├── fa-solid-900.ttf
│   ├── fa-solid-900.woff
│   └── fa-solid-900.woff2
├── .htaccess 15
├── boletins.php 66
├── cabecalho.php 90
├── convencoes.php 63
├── convenios.php 85
├── diretoria.php 42
├── editais.php 74
├── estatuto.php 37
├── eventos.php 82
├── financas.php 72
├── historico.php 46
├── index.php 107
├── jornais.php 57
├── juridicos.php 48
├── navegador.php 71
├── noticias.php 82
├── php.ini 3
├── podcasts.php 59
├── README.md 154
├── rodape.php 66
└── videos.php 45
```


### Instalação
Primeiramente importe um novo banco de dados a partir do arquivo `sgc/persistence.sql` e insira os dados do arquivo `sgc/insertions.sql`, caso queira popular o banco, se não é necessário criar usuário para acessar o sistema.
Feito isso, copie todos os arquivos e pastas para o diretório raiz do servidor. Recomenda-se remover os arquivos `sgc/persistence.sql` e `sgc/insertions.sql` após a importação.
Agora, modifique as variáveis `$host` (endereço do servidor), `$user` (usuário do banco de dados), `$passowrd` (senha do banco de dados), `$name` (nome do banco de dados) do arquivo `sgc/dao.php` para os dados referente ao servidor do MySQL.
Abra o arquivo `.htaccess` e altere o nome do host para o vinculado ao servidor em `RewriteRule`.
Pronto, o servidor está configurado e pronto para utilizar o sistema, qualquer erro encontrado, verifique se está utilizando uma versão do PHP superior a 7.


### Versão
1.1


### Direitos Autorais e Licença
Código de autoria do criador Luiz Joaquim Aderaldo Amichi, sob licença de uso do Sindicato dos Trabalhadores em Estabelecimentos de Ensino de Maringá (Sinteemar), totalizando 4.736 linhas de código autoral e 9.793 linhas de código aberto (folhas de estilo em cascata, fontes, imagens e scripts).