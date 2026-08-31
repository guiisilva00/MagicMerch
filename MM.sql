-- MagicMerch - Banco de Dados

CREATE DATABASE IF NOT EXISTS magicmerch_db;
USE magicmerch_db;

-- Tabela de usuários
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  telefone VARCHAR(20),
  data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de artistas
CREATE TABLE artistas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL UNIQUE,
  descricao TEXT
);

-- Tabela de produtos
CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2) NOT NULL,
  artista_id INT NOT NULL,
  categoria VARCHAR(50),
  estoque INT DEFAULT 0,
  FOREIGN KEY (artista_id) REFERENCES artistas(id)
);

-- Tabela de carrinho
CREATE TABLE carrinho (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  produto_id INT NOT NULL,
  quantidade INT DEFAULT 1,
  data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- Tabela de pedidos
CREATE TABLE pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  valor_total DECIMAL(10,2) NOT NULL,
  status VARCHAR(50) DEFAULT 'pendente',
  endereco VARCHAR(255),
  data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de itens do pedido
CREATE TABLE itens_pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  produto_id INT NOT NULL,
  quantidade INT NOT NULL,
  preco_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
  FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- Inserir alguns usuários
INSERT INTO usuarios (nome, email, senha, telefone) VALUES
('João Silva', 'joao@email.com', '123456', '11999999999'),
('Maria Santos', 'maria@email.com', '123456', '11988888888'),
('Pedro Oliveira', 'pedro@email.com', '123456', '11977777777');

-- Inserir artistas
INSERT INTO artistas (nome, descricao) VALUES
('The Beatles', 'Banda de rock clássica britânica'),
('Taylor Swift', 'Cantora de pop americano'),
('K-pop Artists', 'Artistas de música coreana'),
('Anime Classics', 'Personagens de anime famosos');

-- Inserir produtos
INSERT INTO produtos (nome, descricao, preco, artista_id, categoria, estoque) VALUES
('Camiseta The Beatles - Yellow Submarine', 'Camiseta 100% algodão com design clássico', 59.99, 1, 'camiseta', 15),
('Moleton The Beatles - Abbey Road', 'Moleton confortável com estampa do álbum', 89.99, 1, 'moleton', 8),
('Caneca The Beatles - Logo Preto', 'Caneca cerâmica para café/chá', 29.99, 1, 'caneca', 25),
('Camiseta Taylor Swift - Lover', 'Camiseta rosa com arte do álbum Lover', 49.99, 2, 'camiseta', 12),
('Poster Taylor Swift - Red', 'Poster 60x40 cm do álbum Red', 35.99, 2, 'poster', 30),
('Camiseta BTS - Dynamite', 'Camiseta preta com logo do BTS', 54.99, 3, 'camiseta', 20),
('Boné K-pop - BLACKPINK', 'Boné de algodão com logo BLACKPINK', 44.99, 3, 'acessorio', 18),
('Figura Naruto - Hokage', 'Miniatura do Naruto como Hokage', 39.99, 4, 'acessorio', 10),
('Poster Demon Slayer', 'Poster colorido 50x70 cm', 32.99, 4, 'poster', 22),
('Moleton Anime Mix', 'Moleton com vários personagens anime', 79.99, 4, 'moleton', 6);