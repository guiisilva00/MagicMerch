<?php
$marca = [
    'rosaIntenso' => '#e41169',
    'rosaClaro' => '#f25496',
    'rosaPastel' => '#fac7dc',
    'orquidea' => '#ca53ba',
    'ameixa' => '#ac359c',
];

$coresDestaque = [$marca['rosaIntenso'], $marca['orquidea'], $marca['ameixa'], $marca['rosaClaro'], $marca['rosaIntenso'], $marca['orquidea']];

$linksNavegacao = [
    ['rotulo' => 'Início', 'possuiSubmenu' => false, 'url' => 'index.php'],
    ['rotulo' => 'Produtos', 'possuiSubmenu' => true, 'url' => 'produtos.php'],
    ['rotulo' => 'Artistas e bandas', 'possuiSubmenu' => false, 'url' => 'artistas.php'],
];

$slidesDestaque = [
    ['colecao' => 'Coleção Verão 2025', 'temporada' => 'SS25', 'chamada' => ['NOVO', 'DROP'], 'subtitulo' => 'Exclusivo & artesanal', 'descricao' => 'Peças únicas feitas à mão por artistas independentes. Cada item conta uma história.', 'acao' => 'Ver coleção', 'corDestaque' => $marca['rosaIntenso'], 'paineis' => [
        ['imagem' => 'https://images.unsplash.com/photo-1671549845004-2770b1aa8dc5?w=600&h=900&fit=crop&auto=format', 'textoAlternativo' => 'Modelo da coleção de verão'],
        ['imagem' => 'https://images.unsplash.com/photo-1771012266130-435928e57460?w=500&h=700&fit=crop&auto=format', 'textoAlternativo' => 'Modelo da coleção'],
        ['imagem' => 'https://images.unsplash.com/photo-1576188973526-0e5d7047b0cf?w=400&h=500&fit=crop&auto=format', 'textoAlternativo' => 'Produto da coleção'],
        ['imagem' => 'https://images.unsplash.com/photo-1686491730848-0c86413833e5?w=400&h=560&fit=crop&auto=format', 'textoAlternativo' => 'Visual da coleção'],
    ]],
    ['colecao' => 'Artistas em destaque', 'temporada' => 'LIMITADA', 'chamada' => ['FEITO', 'À MÃO'], 'subtitulo' => 'Edição limitada', 'descricao' => 'Cards colecionáveis, moletons e acessórios exclusivos. Estoque limitado.', 'acao' => 'Explorar artistas', 'corDestaque' => $marca['orquidea'], 'paineis' => [
        ['imagem' => 'https://images.unsplash.com/photo-1590131222139-91ba5992e4ed?w=600&h=900&fit=crop&auto=format', 'textoAlternativo' => 'Modelo com peça artesanal'],
        ['imagem' => 'https://images.unsplash.com/photo-1532332248682-206cc786359f?w=500&h=700&fit=crop&auto=format', 'textoAlternativo' => 'Modelo urbano'],
        ['imagem' => 'https://images.unsplash.com/photo-1696086152504-4843b2106ab4?w=400&h=500&fit=crop&auto=format', 'textoAlternativo' => 'Camiseta da coleção'],
        ['imagem' => 'https://images.unsplash.com/photo-1772450235995-ecd6b9c17aab?w=400&h=560&fit=crop&auto=format', 'textoAlternativo' => 'Visual de campanha'],
    ]],
];

$artistasDestaque = [
    ['nome' => 'Matuê', 'genero' => 'Trap', 'quantidadeItens' => 24, 'imagem' => 'https://images.unsplash.com/photo-1652781335326-b7e64b014c90?w=500&h=640&fit=crop&auto=format'],
    ['nome' => 'Veigh', 'genero' => 'Trap / Hip-Hop', 'quantidadeItens' => 21, 'imagem' => 'https://images.unsplash.com/photo-1770062421988-7929b4748e29?w=500&h=640&fit=crop&auto=format'],
    ['nome' => 'Teto', 'genero' => 'Trap', 'quantidadeItens' => 18, 'imagem' => 'https://images.unsplash.com/photo-1771894431319-7eb49cda5adb?w=500&h=640&fit=crop&auto=format'],
    ['nome' => 'WIU', 'genero' => 'Trap / Rap', 'quantidadeItens' => 16, 'imagem' => 'https://images.unsplash.com/photo-1765064067361-aca81b93b37b?w=500&h=640&fit=crop&auto=format'],
    ['nome' => 'Orochi', 'genero' => 'Trap / Rap', 'quantidadeItens' => 22, 'imagem' => 'https://images.unsplash.com/photo-1748357641562-057e48a8ebd5?w=500&h=640&fit=crop&auto=format'],
    ['nome' => 'KayBlack', 'genero' => 'Trap / Funk', 'quantidadeItens' => 19, 'imagem' => 'https://images.unsplash.com/photo-1577565201041-2659d583bb6f?w=500&h=640&fit=crop&auto=format'],
    ['nome' => 'MC Ryan SP', 'genero' => 'Trap / Funk', 'quantidadeItens' => 15, 'imagem' => 'https://images.unsplash.com/photo-1652781335326-b7e64b014c90?w=500&h=640&fit=crop&auto=format'],
];