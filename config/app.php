<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/crud.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Conexão única da requisição. Fica null quando o banco não foi importado.
try {
    $pdo = criarConexaoBancoDados();
} catch (PDOException $e) {
    $pdo = null;
}

// ----------------------------------------------------------------------------
// Helpers gerais
// ----------------------------------------------------------------------------
function escapar(string $valor): string { return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'); }
function usuarioAtual(): ?array { return $_SESSION['usuario'] ?? null; }
function estaLogado(): bool { return usuarioAtual() !== null; }
function eAdministrador(): bool { return estaLogado() && usuarioAtual()['tipo'] === 'administrador'; }
function redirecionar(string $url): never { header('Location: ' . $url); exit; }
function exigirLogin(string $destino = 'login.php'): void { if (!estaLogado()) { $_SESSION['retorno'] = basename($_SERVER['PHP_SELF']); redirecionar($destino); } }
function exigirAdministrador(): void { if (!eAdministrador()) { redirecionar('login.php'); } }
function mensagemFlash(?string $tipo = null, ?string $texto = null): ?array { if ($tipo !== null) { $_SESSION['flash'] = [$tipo, $texto]; return null; } $m = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $m; }
function valorMoeda(float $valor): string { return 'R$ ' . number_format($valor, 2, ',', '.'); }

// Cor do pôster (1..5) derivada de um texto estável (nome do artista, categoria...).
function acentoPoster(string $chave): int { return (int) (crc32($chave) % 5) + 1; }

// Primeira letra visível de um nome, em maiúscula, para a inicial-fantasma dos pôsteres.
function inicial(string $nome): string { return mb_strtoupper(mb_substr(trim($nome), 0, 1)); }

// ----------------------------------------------------------------------------
// Consultas de leitura (atalhos finos sobre o CRUD)
// ----------------------------------------------------------------------------

// Índice id => linha para resolver "joins" em PHP.
function indexarPorId(array $linhas): array
{
    $indice = [];
    foreach ($linhas as $linha) {
        $indice[$linha['id']] = $linha;
    }
    return $indice;
}

function quantidadeCarrinho(PDO $pdo, int $usuarioId): int
{
    $itens = readAll($pdo, 'carrinho', 'usuario_id = ?', [$usuarioId]);
    return (int) array_sum(array_column($itens, 'quantidade'));
}

function itensCarrinho(PDO $pdo, int $usuarioId): array
{
    $linhas = readAll($pdo, 'carrinho', 'usuario_id = ? ORDER BY data_adicao DESC', [$usuarioId]);
    $produtos = indexarPorId(readAll($pdo, 'produtos'));

    $itens = [];
    foreach ($linhas as $linha) {
        $produto = $produtos[$linha['produto_id']] ?? null;
        if (!$produto) {
            continue;
        }
        $itens[] = [
            'produto_id' => $linha['produto_id'],
            'quantidade' => (int) $linha['quantidade'],
            'nome' => $produto['nome'],
            'preco' => $produto['preco'],
            'estoque' => (int) $produto['estoque'],
            'imagem' => $produto['imagem'],
        ];
    }
    return $itens;
}

function subtotalCarrinho(array $itens): float
{
    return array_sum(array_map(fn($i) => (float) $i['preco'] * (int) $i['quantidade'], $itens));
}

function buscarArtistas(PDO $pdo): array
{
    return readAll($pdo, 'artistas', '1 ORDER BY nome');
}

// Categorias distintas derivadas dos produtos (não há tabela própria).
function buscarCategorias(PDO $pdo): array
{
    $categorias = array_values(array_unique(array_column(readAll($pdo, 'produtos'), 'categoria')));
    sort($categorias);
    return $categorias;
}

function buscarProdutoPorId(PDO $pdo, int $id): ?array
{
    $produto = read($pdo, 'produtos', 'id = ?', [$id]);
    if (!$produto) {
        return null;
    }
    $artista = read($pdo, 'artistas', 'id = ?', [$produto['artista_id']]);
    $produto['nome_artista'] = $artista['nome'] ?? '';
    return $produto;
}

function buscarProdutos(PDO $pdo, array $filtros): array
{
    $condicoes = [];
    $parametros = [];

    if ($filtros['busca'] !== '') {
        $condicoes[] = '(nome LIKE ? OR descricao LIKE ?)';
        $parametros[] = '%' . $filtros['busca'] . '%';
        $parametros[] = '%' . $filtros['busca'] . '%';
    }
    if ($filtros['categoria'] !== '') {
        $condicoes[] = 'categoria = ?';
        $parametros[] = $filtros['categoria'];
    }
    if ($filtros['artista'] !== '') {
        $condicoes[] = 'artista_id = ?';
        $parametros[] = (int) $filtros['artista'];
    }
    if ($filtros['preco_minimo'] !== '') {
        $condicoes[] = 'preco >= ?';
        $parametros[] = (float) $filtros['preco_minimo'];
    }
    if ($filtros['preco_maximo'] !== '') {
        $condicoes[] = 'preco <= ?';
        $parametros[] = (float) $filtros['preco_maximo'];
    }
    if ($filtros['disponibilidade'] === 'em_estoque') {
        $condicoes[] = 'estoque > 0';
    } elseif ($filtros['disponibilidade'] === 'esgotados') {
        $condicoes[] = 'estoque = 0';
    }

    $ordenacao = match ($filtros['ordenacao']) {
        'menor_preco' => 'preco ASC',
        'maior_preco' => 'preco DESC',
        'alfabetica' => 'nome ASC',
        default => 'destaque DESC, vendas DESC',
    };

    $where = ($condicoes ? implode(' AND ', $condicoes) : '1') . ' ORDER BY ' . $ordenacao;
    $produtos = readAll($pdo, 'produtos', $where, $parametros);

    $artistas = indexarPorId(readAll($pdo, 'artistas'));
    foreach ($produtos as &$produto) {
        $produto['nome_artista'] = $artistas[$produto['artista_id']]['nome'] ?? '';
    }
    return $produtos;
}

// ----------------------------------------------------------------------------
// Regras de negócio simples
// ----------------------------------------------------------------------------
function calcularFrete(string $modalidade, string $estado = ''): float { if ($modalidade === 'retirada') return 0; return strtoupper(trim($estado)) === 'SP' || stripos($estado, 'são paulo') !== false ? 10 : 20; }
function statusPedido(): array { return ['aguardando_pagamento'=>'Aguardando pagamento','pagamento_confirmado'=>'Pagamento confirmado','em_producao_separacao'=>'Em produção/separação','enviado'=>'Enviado','concluido'=>'Concluído']; }

// ----------------------------------------------------------------------------
// Conteúdo estático das páginas institucionais
// ----------------------------------------------------------------------------
$linksNavegacao = [
    ['rotulo' => 'Início', 'possuiSubmenu' => false, 'url' => 'index.php'],
    ['rotulo' => 'Produtos', 'possuiSubmenu' => true, 'url' => 'produtos.php'],
    ['rotulo' => 'Artistas e bandas', 'possuiSubmenu' => false, 'url' => 'artistas.php'],
];

$slidesDestaque = [
    [
        'colecao' => 'Coleção Verão 2025',
        'temporada' => 'SS25',
        'chamada' => ['NOVO', 'DROP'],
        'subtitulo' => 'Exclusivo & artesanal',
        'descricao' => 'Peças únicas feitas à mão por artistas independentes. Cada item conta uma história.',
        'acao' => 'Ver coleção',
        'paineis' => ['Coleção de verão', 'Peça artesanal', 'Produto da coleção', 'Visual da coleção'],
    ],
    [
        'colecao' => 'Artistas em destaque',
        'temporada' => 'LIMITADA',
        'chamada' => ['FEITO', 'À MÃO'],
        'subtitulo' => 'Edição limitada',
        'descricao' => 'Cards colecionáveis, moletons e acessórios exclusivos. Estoque limitado.',
        'acao' => 'Explorar artistas',
        'paineis' => ['Peça artesanal', 'Visual urbano', 'Camiseta da coleção', 'Visual de campanha'],
    ],
];
