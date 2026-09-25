CREATE DATABASE IF NOT EXISTS magicmerch_db CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

USE magicmerch_db;

CREATE TABLE
    usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        telefone VARCHAR(20),
        tipo ENUM ('cliente', 'administrador') NOT NULL DEFAULT 'cliente',
        data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Banco já existente? Rode manualmente antes de usar a página de artistas:
-- ALTER TABLE artistas ADD COLUMN imagem VARCHAR(255), ADD COLUMN imagem_banner VARCHAR(255);
CREATE TABLE
    artistas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(150) NOT NULL UNIQUE,
        descricao TEXT,
        imagem VARCHAR(255),
        imagem_banner VARCHAR(255)
    );

CREATE TABLE
    produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(150) NOT NULL,
        descricao TEXT NOT NULL,
        preco DECIMAL(10, 2) NOT NULL,
        artista_id INT NOT NULL,
        categoria VARCHAR(50) NOT NULL,
        estoque INT NOT NULL DEFAULT 0,
        imagem VARCHAR(255),
        cor VARCHAR(50),
        tamanho VARCHAR(50),
        destaque TINYINT (1) NOT NULL DEFAULT 0,
        vendas INT NOT NULL DEFAULT 0,
        FOREIGN KEY (artista_id) REFERENCES artistas (id)
    );

CREATE TABLE
    enderecos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        apelido VARCHAR(50) NOT NULL DEFAULT 'Endereço',
        cep VARCHAR(9) NOT NULL,
        logradouro VARCHAR(150) NOT NULL,
        numero VARCHAR(20) NOT NULL,
        complemento VARCHAR(100),
        bairro VARCHAR(100) NOT NULL,
        cidade VARCHAR(100) NOT NULL,
        estado CHAR(2) NOT NULL,
        principal TINYINT (1) NOT NULL DEFAULT 0,
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
    );

CREATE TABLE
    favoritos (
        usuario_id INT NOT NULL,
        produto_id INT NOT NULL,
        data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (usuario_id, produto_id),
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE,
        FOREIGN KEY (produto_id) REFERENCES produtos (id) ON DELETE CASCADE
    );

CREATE TABLE
    avaliacoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        produto_id INT NOT NULL,
        nota TINYINT NOT NULL,
        comentario TEXT NOT NULL,
        data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY avaliacao_unica (usuario_id, produto_id),
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE,
        FOREIGN KEY (produto_id) REFERENCES produtos (id) ON DELETE CASCADE
    );

CREATE TABLE
    carrinho (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        produto_id INT NOT NULL,
        quantidade INT NOT NULL DEFAULT 1,
        data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY carrinho_unico (usuario_id, produto_id),
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE,
        FOREIGN KEY (produto_id) REFERENCES produtos (id) ON DELETE CASCADE
    );

CREATE TABLE
    pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NOT NULL,
        valor_total DECIMAL(10, 2) NOT NULL,
        status ENUM (
            'aguardando_pagamento',
            'pagamento_confirmado',
            'em_producao_separacao',
            'enviado',
            'concluido'
        ) NOT NULL DEFAULT 'aguardando_pagamento',
        modalidade_entrega ENUM ('entrega', 'retirada') NOT NULL,
        endereco VARCHAR(255),
        frete DECIMAL(10, 2) NOT NULL DEFAULT 0,
        forma_pagamento ENUM ('pix', 'cartao') NOT NULL,
        pagamento_confirmado TINYINT (1) NOT NULL DEFAULT 1,
        data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
    );

CREATE TABLE
    itens_pedido (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pedido_id INT NOT NULL,
        produto_id INT NOT NULL,
        quantidade INT NOT NULL,
        preco_unitario DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (pedido_id) REFERENCES pedidos (id),
        FOREIGN KEY (produto_id) REFERENCES produtos (id)
    );

INSERT INTO
    usuarios (nome, email, senha, telefone, tipo)
VALUES
    (
        'Administração MagicMerch',
        'admin@magicmerch.local',
        '$2y$10$b606TIJfMqs3I4d.FHClDeyWWRZAeHiMVJEaBLLc25UK7fOEUyLGS',
        '11999990000',
        'administrador'
    ),
    (
        'João Silva',
        'joao@email.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',
        '11999999999',
        'cliente'
    );

INSERT INTO
    artistas (nome, descricao)
VALUES
    ('The Beatles', 'Banda de rock clássica britânica'),
    ('Taylor Swift', 'Cantora de pop americana'),
    ('BTS', 'Grupo de música sul-coreano'),
    (
        'Anime Classics',
        'Personagens de anime populares'
    );

INSERT INTO
    produtos (
        nome,
        descricao,
        preco,
        artista_id,
        categoria,
        estoque,
        imagem,
        cor,
        tamanho,
        destaque
    )
VALUES
    (
        'Camiseta The Beatles - Yellow Submarine',
        'Camiseta de algodão com arte inspirada no álbum Yellow Submarine.',
        59.90,
        1,
        'camiseta',
        15,
        NULL,
        'Amarela',
        'P, M, G',
        1
    ),
    (
        'Moletom The Beatles - Abbey Road',
        'Moletom confortável com estampa inspirada no álbum Abbey Road.',
        89.90,
        1,
        'moletom',
        8,
        NULL,
        'Preto',
        'P, M, G',
        1
    ),
    (
        'Caneca The Beatles - Logo Preto',
        'Caneca de cerâmica para colecionadores.',
        29.90,
        1,
        'caneca',
        25,
        NULL,
        'Branca',
        NULL,
        0
    ),
    (
        'Camiseta Taylor Swift - Lover',
        'Camiseta rosa com arte inspirada no álbum Lover.',
        49.90,
        2,
        'camiseta',
        12,
        NULL,
        'Rosa',
        'P, M, G',
        1
    ),
    (
        'Pôster Taylor Swift - Red',
        'Pôster colorido de 60 por 40 centímetros.',
        35.90,
        2,
        'poster',
        30,
        NULL,
        NULL,
        NULL,
        0
    ),
    (
        'Camiseta BTS - Dynamite',
        'Camiseta preta com logo do BTS.',
        54.90,
        3,
        'camiseta',
        20,
        NULL,
        'Preta',
        'P, M, G',
        1
    ),
    (
        'Boné BTS',
        'Boné de algodão com aplicação frontal.',
        44.90,
        3,
        'acessorio',
        18,
        NULL,
        'Preto',
        'Único',
        0
    ),
    (
        'Figura Naruto - Hokage',
        'Miniatura do Naruto como Hokage.',
        39.90,
        4,
        'acessorio',
        10,
        NULL,
        NULL,
        NULL,
        0
    ),
    (
        'Pôster Demon Slayer',
        'Pôster colorido de 50 por 70 centímetros.',
        32.90,
        4,
        'poster',
        22,
        NULL,
        NULL,
        NULL,
        0
    ),
    (
        'Moletom Anime Mix',
        'Moletom com vários personagens de anime.',
        79.90,
        4,
        'moletom',
        0,
        NULL,
        'Cinza',
        'P, M, G',
        0
    ),
    (
        'Camiseta The Beatles - Revolver',
        'Camiseta de algodão com estampa em preto e branco inspirada na capa do álbum Revolver.',
        64.90,
        1,
        'camiseta',
        18,
        NULL,
        'Branca',
        'P, M, G, GG',
        1
    ),
    (
        'Caneca Taylor Swift - Midnights',
        'Caneca de cerâmica fosca com elementos visuais da era Midnights.',
        34.90,
        2,
        'caneca',
        25,
        NULL,
        'Azul Marinho',
        NULL,
        0
    ),
    (
        'Moletom Taylor Swift - Folklore',
        'Moletom estilo oversized em tom cinza mescla com bordado minimalista Folklore.',
        94.90,
        2,
        'moletom',
        10,
        NULL,
        'Cinza',
        'P, M, G',
        1
    ),
    (
        'Moletom BTS - Love Yourself',
        'Moletom de algodão com capuz e estampa florida inspirada na era Love Yourself.',
        99.90,
        3,
        'moletom',
        14,
        NULL,
        'Rosa Bebê',
        'P, M, G',
        1
    ),
    (
        'Caneca BTS - Butter',
        'Caneca amarela vibrante com logo oficial do single Butter.',
        32.90,
        3,
        'caneca',
        20,
        NULL,
        'Amarela',
        NULL,
        0
    ),
    (
        'Pôster The Beatles - Help!',
        'Pôster impresso em papel couchê de alta gramatura com a pose icônica do filme e álbum Help!.',
        38.90,
        1,
        'poster',
        28,
        NULL,
        NULL,
        NULL,
        0
    ),
    (
        'Camiseta Dragon Ball - Goku Instinto Superior',
        'Camiseta em malha penteada com estampa do Goku em sua forma Instinto Superior.',
        59.90,
        4,
        'camiseta',
        16,
        NULL,
        'Preta',
        'P, M, G, GG',
        1
    ),
    (
        'Caneca One Piece - Chapéu de Palha',
        'Caneca temática de cerâmica com o símbolo da tripulação do Chapéu de Palha.',
        31.90,
        4,
        'caneca',
        22,
        NULL,
        'Branca',
        NULL,
        0
    ),
    (
        'Chaveiro BTS - Logo Purple',
        'Chaveiro em acrílico roxo com acabamento premium e o logo característico do BTS.',
        24.90,
        3,
        'acessorio',
        35,
        NULL,
        'Roxo',
        'Único',
        0
    ),
    (
        'Chaveiro Taylor Swift - 13 Heart',
        'Chaveiro metálico em formato de coração com o número 13 gravado em baixo-relevo.',
        27.90,
        2,
        'acessorio',
        30,
        NULL,
        'Dourado',
        'Único',
        0
    );
INSERT INTO artistas (nome, descricao)
VALUES ('The Weeknd', 'Cantor e compositor canadense de R&B e pop.')
ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);

INSERT INTO produtos
    (nome, descricao, preco, artista_id, categoria, estoque, imagem, cor, tamanho, destaque)
VALUES
    ('Camiseta The Weeknd - After Hours', 'Camiseta inspirada na estetica do album After Hours.', 69.90, (SELECT id FROM artistas WHERE nome = 'The Weeknd'), 'camiseta', 15, NULL, 'Preto', 'P, M, G, GG', 1),
    ('Moletom The Weeknd - Dawn FM', 'Moletom com arte inspirada no album Dawn FM.', 129.90, (SELECT id FROM artistas WHERE nome = 'The Weeknd'), 'moletom', 8, NULL, 'Preto', 'P, M, G, GG', 1),
    ('Caneca The Weeknd - XO', 'Caneca de ceramica para fas do universo XO.', 34.90, (SELECT id FROM artistas WHERE nome = 'The Weeknd'), 'caneca', 20, NULL, 'Preta', NULL, 0),
    ('Poster The Weeknd - Starboy', 'Poster decorativo inspirado no album Starboy.', 39.90, (SELECT id FROM artistas WHERE nome = 'The Weeknd'), 'poster', 12, NULL, NULL, 'A3', 0),
    ('Ecobag The Weeknd - XO', 'Ecobag de tecido com estampa inspirada na marca XO.', 44.90, (SELECT id FROM artistas WHERE nome = 'The Weeknd'), 'ecobag', 10, NULL, 'Preta', 'Unico', 0);

INSERT INTO artistas (nome, descricao)
VALUES ('Stray Kids', 'Grupo sul-coreano de K-pop.')
ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);

INSERT INTO produtos
    (nome, descricao, preco, artista_id, categoria, estoque, imagem, cor, tamanho, destaque)
VALUES
    ('Camiseta Stray Kids - SKZ', 'Camiseta inspirada na identidade visual do Stray Kids.', 69.90, (SELECT id FROM artistas WHERE nome = 'Stray Kids'), 'camiseta', 15, NULL, 'Preta', 'P, M, G, GG', 1),
    ('Moletom Stray Kids - Maniac', 'Moletom com design inspirado na era Maniac.', 129.90, (SELECT id FROM artistas WHERE nome = 'Stray Kids'), 'moletom', 8, NULL, 'Preto', 'P, M, G, GG', 1),
    ('Caneca Stray Kids - Stay', 'Caneca de ceramica para fas do Stray Kids.', 34.90, (SELECT id FROM artistas WHERE nome = 'Stray Kids'), 'caneca', 20, NULL, 'Branca', NULL, 0),
    ('Poster Stray Kids - 5-Star', 'Poster decorativo inspirado no album 5-Star.', 39.90, (SELECT id FROM artistas WHERE nome = 'Stray Kids'), 'poster', 12, NULL, NULL, 'A3', 0),
    ('Ecobag Stray Kids - Stay', 'Ecobag de tecido com estampa inspirada no fandom Stay.', 44.90, (SELECT id FROM artistas WHERE nome = 'Stray Kids'), 'ecobag', 10, NULL, 'Preta', 'Unico', 0);