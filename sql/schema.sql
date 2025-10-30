-- schema.sql (MySQL)
CREATE DATABASE IF NOT EXISTS `colecao` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `colecao`;
CREATE TABLE IF NOT EXISTS `colecionaveis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('Vinil','CD','BoxSet','Livreto') NOT NULL DEFAULT 'Vinil',
  `nome` varchar(255) NOT NULL,
  `artista` varchar(255) NOT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `gravadora` varchar(255) DEFAULT NULL,
  `edicao` varchar(100) DEFAULT NULL,
  `ano` year DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `autografado` tinyint(1) DEFAULT 0,
  `certificado` varchar(255) DEFAULT NULL,
  `importado` tinyint(1) DEFAULT 0,
  `pais` varchar(100) DEFAULT NULL,
  `primeira` tinyint(1) DEFAULT 0,
  `lacrado` tinyint(1) DEFAULT 0,
  `encarte` tinyint(1) DEFAULT 0,
  `poster` tinyint(1) DEFAULT 0,
  `obi` tinyint(1) DEFAULT 0,
  `hyper` tinyint(1) DEFAULT 0,
  `anotacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS listas (
  id int NOT NULL AUTO_INCREMENT, nome varchar(255) NOT NULL, descricao text DEFAULT NULL, created_at timestamp DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS lista_items (
  id int NOT NULL AUTO_INCREMENT, lista_id int NOT NULL, item_id int NOT NULL, created_at timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS wishlist (
  id int NOT NULL AUTO_INCREMENT, item_id int NOT NULL, nota varchar(255) DEFAULT NULL, created_at timestamp DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
