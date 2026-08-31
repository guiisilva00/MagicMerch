<?php
$brand = [
    'razzmatazz' => '#e41169',
    'wildStrawberry' => '#f25496',
    'pastelPetal' => '#fac7dc',
    'vividOrchid' => '#ca53ba',
    'raspberryPlum' => '#ac359c',
];

$paletteAccents = [
    $brand['razzmatazz'],
    $brand['vividOrchid'],
    $brand['raspberryPlum'],
    $brand['wildStrawberry'],
    $brand['razzmatazz'],
    $brand['vividOrchid'],
];

$navLinks = [
    ['label' => 'Home', 'sub' => false, 'url' => 'index.php'],
    ['label' => 'Produtos', 'sub' => true, 'url' => '#'],
    ['label' => 'Artistas e Bandas', 'sub' => false, 'url' => '#'],
    ['label' => 'Outlet HM', 'sub' => false, 'url' => '#'],
    ['label' => 'Infantil', 'sub' => false, 'url' => '#'],
    ['label' => 'Para empresas', 'sub' => false, 'url' => '#'],
];

$heroSlides = [
    [
        'collection' => 'Coleção Verão 2025',
        'season' => 'SS25',
        'headline' => ['NOVO', 'DROP'],
        'subheading' => 'Exclusivo & Artesanal',
        'description' => 'Peças únicas feitas à mão por artistas independentes. Cada item conta uma história.',
        'cta' => 'Ver coleção',
        'accentColor' => $brand['razzmatazz'],
        'panels' => [
            ['img' => 'https://images.unsplash.com/photo-1671549845004-2770b1aa8dc5?w=600&h=900&fit=crop&auto=format', 'alt' => 'Modelo coleção verão'],
            ['img' => 'https://images.unsplash.com/photo-1771012266130-435928e57460?w=500&h=700&fit=crop&auto=format', 'alt' => 'Modelo coleção'],
            ['img' => 'https://images.unsplash.com/photo-1576188973526-0e5d7047b0cf?w=400&h=500&fit=crop&auto=format', 'alt' => 'Produto da coleção'],
            ['img' => 'https://images.unsplash.com/photo-1686491730848-0c86413833e5?w=400&h=560&fit=crop&auto=format', 'alt' => 'Look da coleção'],
        ],
    ],
    [
        'collection' => 'Artistas em Destaque',
        'season' => 'LIMITED',
        'headline' => ['HAND', 'MADE'],
        'subheading' => 'Edição Limitada',
        'description' => 'Cards colecionáveis, moletons e acessórios exclusivos. Estoque limitado.',
        'cta' => 'Explorar artistas',
        'accentColor' => $brand['vividOrchid'],
        'panels' => [
            ['img' => 'https://images.unsplash.com/photo-1590131222139-91ba5992e4ed?w=600&h=900&fit=crop&auto=format', 'alt' => 'Modelo hand made'],
            ['img' => 'https://images.unsplash.com/photo-1532332248682-206cc786359f?w=500&h=700&fit=crop&auto=format', 'alt' => 'Modelo urbano'],
            ['img' => 'https://images.unsplash.com/photo-1696086152504-4843b2106ab4?w=400&h=500&fit=crop&auto=format', 'alt' => 'Camiseta coleção'],
            ['img' => 'https://images.unsplash.com/photo-1772450235995-ecd6b9c17aab?w=400&h=560&fit=crop&auto=format', 'alt' => 'Look de campanha'],
        ],
    ],
];

$artists = [
    [
        'name' => 'The Weeknd',
        'genre' => 'R&B / Pop',
        'items' => 24,
        'img' => 'https://images.unsplash.com/photo-1652781335326-b7e64b014c90?w=500&h=640&fit=crop&auto=format',
    ],
    [
        'name' => 'Billie Eilish',
        'genre' => 'Alt Pop',
        'items' => 18,
        'img' => 'https://images.unsplash.com/photo-1770062421988-7929b4748e29?w=500&h=640&fit=crop&auto=format',
    ],
    [
        'name' => 'Arctic Monkeys',
        'genre' => 'Indie Rock',
        'items' => 31,
        'img' => 'https://images.unsplash.com/photo-1771894431319-7eb49cda5adb?w=500&h=640&fit=crop&auto=format',
    ],
    [
        'name' => 'Doja Cat',
        'genre' => 'Pop / Hip-Hop',
        'items' => 15,
        'img' => 'https://images.unsplash.com/photo-1765064067361-aca81b93b37b?w=500&h=640&fit=crop&auto=format',
    ],
    [
        'name' => 'Radiohead',
        'genre' => 'Alt Rock',
        'items' => 22,
        'img' => 'https://images.unsplash.com/photo-1748357641562-057e48a8ebd5?w=500&h=640&fit=crop&auto=format',
    ],
    [
        'name' => 'Dua Lipa',
        'genre' => 'Pop / Dance',
        'items' => 19,
        'img' => 'https://images.unsplash.com/photo-1577565201041-2659d583bb6f?w=500&h=640&fit=crop&auto=format',
    ],
];
?>
