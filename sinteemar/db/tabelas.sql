CREATE DATABASE IF NOT EXISTS SINTEEMAR CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
USE SINTEEMAR;
SET COLLATION_CONNECTION = 'utf8_general_ci';
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS PERMISSOES (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA À PERMISSÃO
	`ADMIN` TINYINT(1) NOT NULL DEFAULT 0, -- VALIDADOR PARA ADMINISTRADOR
	`ACESSOS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA NA TABELA DE ACESSOS
	`BANNERS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE BANNERS
	`BOLETINS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE BOLETINS
	`CONVENCOES` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE CONVENÇÕES
	`CONVENIOS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE CONVÊNIOS
	`DIRETORIA` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA E ALTERAÇÃO NA TABELA DA DIRETORIA
	`DIRETORIO` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA NO DIRETÓRIO
	`EDITAIS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE EDITAIS
	`ESTATUTO` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA E ALTERAÇÃO TABELA DO ESTATUTO
	`EVENTOS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE EVENTOS
	`FINANCAS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DO FINANÇAS
	`HISTORICO` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE HISTÓRICO
	`JORNAIS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE JORNAIS
	`JURIDICOS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE JURÍDICOS
	`NOTICIAS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE NOTÍCIAS
	`REGISTROS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA NA TABELA DE REGISTROS
	`TABELAS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA NAS TABELAS DO BANCO DE DADOS
	`USUARIOS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE USUÁRIOS
	`VIDEOS` TINYINT(1) NOT NULL DEFAULT 0, -- PERMISSÃO PARA REALIZAR LEITURA, ESCRITA, ALTERAÇÃO E REMOÇÃO NA TABELA DE VÍDEOS
	PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS USUARIOS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO USUÁRIO
	`NOME` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME COMPLETO DO USUÁRIO
	`EMAIL` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- E-MAIL DO USUÁRIO (UNIQUE)
	`LOGIN` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- LOGIN DO USUÁRIO (UNIQUE)
	`SENHA` CHAR(32) CHARACTER SET utf8 NOT NULL, -- SENHA CRIPTOGRAFADA COM MD5
	`PERMISSAO` INT NOT NULL, -- PERMISSÕES PARA CRUD NAS TABELAS DO SISTEMA
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSCRIÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`PERMISSAO`) REFERENCES PERMISSOES(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS ACESSOS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO ACESSO
	`IP` VARCHAR(64) NOT NULL, -- ENDEREÇO DO VISITANTE
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA VISITA
	PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS BANNERS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO BANNER
	`IMAGEM` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DA IMAGEM(.JPG, .JPEG OU .PNG)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O BANNER
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS BOLETINS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO BOLETIM
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DO BOLETIM
	`DOCUMENTO` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DO DOCUMENTO(.DOC, .DOCX OU .PDF)
	`IMAGEM` VARCHAR(64) CHARACTER SET utf8 NULL, -- NOME DA IMAGEM(.JPG, .JPEG OU .PNG)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O BOLETIM
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS CONVENCOES (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA À CONVENÇÃO
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- DESCRIÇÃO DA CONVENÇÃO
	`DOCUMENTO` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DO DOCUMENTO(.DOC, .DOCX OU .PDF)
	`TIPO` TINYINT(1) NOT NULL DEFAULT 1, -- ANTERIOR (0), VIGENTE (1)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR A CONVENÇÃO
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS CONVENIOS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO CONVÊNIO
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- NOME DO CONVÊNIO OU DA EMPRESA
	`DESCRICAO` TEXT CHARACTER SET utf8 NOT NULL, -- DESCRIÇÃO DO CONVÊNIO
	`CIDADE` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- CIDADE DE ATENDIMENTO DA EMPRESA
	`TELEFONE` VARCHAR(16) CHARACTER SET utf8 NULL, -- DDD + TELEFONE (UNIQUE)
	`CELULAR` VARCHAR(16) CHARACTER SET utf8 NULL, -- DDD + CELULAR (UNIQUE)
	`WEBSITE` VARCHAR(64) CHARACTER SET utf8 NULL, -- WEBSITE DA EMPRESA (UNIQUE)
	`EMAIL` VARCHAR(64) CHARACTER SET utf8 NULL, -- E-MAIL DA EMPRESA (UNIQUE)
	`IMAGEM` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DA IMAGEM(.JPG, .JPEG OU .PNG) DO LOGOTIPO DA EMPRESA
	`DOCUMENTO` VARCHAR(64) CHARACTER SET utf8 NULL, -- NOME DO DOCUMENTO(.DOC, .DOCX OU .PDF) COM DETALHES
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O CONVÊNIO
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS DIRETORIA (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA À DIRETORIA
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DA DIRETORIA
	`TEXTO` TEXT CHARACTER SET utf8 NOT NULL, -- TABELA COM OS DIRETORES
	`IMAGEM` VARCHAR(64) CHARACTER SET utf8 NULL, -- NOME DA IMAGEM(.JPG, .JPEG OU .PNG) DOS DIRETORES OU DO LOGOTIPO
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O DIRETORIA
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS EDITAIS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO EDITAL
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DO EDITAL
	`DESCRICAO` TEXT CHARACTER SET utf8 NULL, -- DESCRIÇÃO DO EDITAL
	`IMAGEM` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DA IMAGEM(.JPG, .JPEG OU .PNG)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O EDITAL
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS ESTATUTO (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO ESTATUTO
	`DOCUMENTO` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- ENDEREÇO DO DOCUMENTO(.DOC, .DOCX OU .PDF) COM DETALHES
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR OU ALTERAR O ESTATUTO
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO (ALTERAÇÃO)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS EVENTOS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO EVENTO
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DO EVENTO
	`DESCRICAO` TEXT CHARACTER SET utf8 NULL, -- DESCRIÇÃO DO EVENTO
	`DIRETORIO` VARCHAR(64) CHARACTER SET utf8 NOT NULL UNIQUE, -- ENDEREÇO DO DIRETÓRIO COM AS IMAGENS
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O EVENTO
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS ARQUIVOS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO ARQUIVO
	`IMAGEM` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DA IMAGEM(.JPG, .JPEG OU .PNG)
	`EVENTO` INT NOT NULL, -- EVENTO AO QUAL A IMAGEM PERTENCE
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`EVENTO`) REFERENCES EVENTOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS FINANCAS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA À FINANÇA
	`PERIODO` VARCHAR(8) CHARACTER SET utf8 NOT NULL, -- PERÍODO (SEMANAL, MENSAL, BIMESTRAL, TRIMESTRAL, SEMESTRAL OU ANUAL) DA FINANÇA
	`DOCUMENTO` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DO DOCUMENTO(.DOC, .DOCX OU .PDF)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR A FINANÇA
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS HISTORICO (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO HISTÓRICO
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DO HISTÓRICO
	`DOCUMENTO` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DO DOCUMENTO(.DOC, .DOCX OU .PDF)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O HISTÓRICO
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS JORNAIS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO JORNAL
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DO JORNAL
	`EDICAO` INT NOT NULL, -- NÚMERO DA EDIÇÃO DO JORNAL (UNIQUE)
	`DOCUMENTO` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DO DOCUMENTO(.DOC, .DOCX OU .PDF)
	`IMAGEM` VARCHAR(64) CHARACTER SET utf8 NULL, -- NOME DA IMAGEM(.JPG, .JPEG OU .PNG)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O JORNAL
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS JURIDICOS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO JURÍDICO
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DA PUBLICAÇÃO
	`DESCRICAO` TEXT CHARACTER SET utf8 NULL, -- DESCRIÇÃO DA PUBLICAÇÃO
	`DOCUMENTO` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- NOME DO DOCUMENTO(.DOC, .DOCX OU .PDF)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O JURÍDICO
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS NOTICIAS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA À NOTÍCIA
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DA NOTÍCIA
	`SUBTITULO` TEXT CHARACTER SET utf8 NULL, -- SUBTÍTULO DA NOTÍCIA
	`TEXTO` TEXT CHARACTER SET utf8 NOT NULL, -- TEXTO DA NOTÍCIA
	`IMAGEM` VARCHAR(64) CHARACTER SET utf8 NULL, -- NOME DA IMAGEM(.JPG, .JPEG OU .PNG) DE CABEÇALHO
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR A NOTÍCIA
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS REGISTROS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO REGISTRO
	`DESCRICAO` TEXT CHARACTER SET utf8 NOT NULL, -- DESCRIÇÃO DO REGISTRO
	`IP` VARCHAR(64) NOT NULL, -- ENDEREÇO LÓGICO DO USUÁRIO
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR MODIFICAR O REGISTRO
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS VIDEOS (
	`ID` INT NOT NULL AUTO_INCREMENT, -- CHAVE ÚNICA ATRIBUÍDA AO VÍDEO
	`TITULO` VARCHAR(128) CHARACTER SET utf8 NOT NULL, -- TÍTULO DO VÍDEO
	`URL` VARCHAR(64) CHARACTER SET utf8 NOT NULL, -- ENDEREÇO DO VÍDEO DA PLATAFORMA YOUTUBE (UNIQUE)
	`USUARIO` INT NOT NULL, -- USUÁRIO RESPONSÁVEL POR INSERIR O VÍDEO
	`DATA` DATETIME NOT NULL, -- DATA E HORA DA INSERÇÃO
	`ATIVO` TINYINT(1) NOT NULL DEFAULT 1, -- INATIVO (0), ATIVO (1)
	PRIMARY KEY (`ID`),
	FOREIGN KEY (`USUARIO`) REFERENCES USUARIOS(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;