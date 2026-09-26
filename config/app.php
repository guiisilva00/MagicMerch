<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/crud.php';

if (ob_get_level() === 0) {
    ob_start();
}

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
function escapar(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}

function usuarioAtual(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function estaLogado(): bool
{
    return usuarioAtual() !== null;
}

function eAdministrador(): bool
{
    return estaLogado() && usuarioAtual()['tipo'] === 'administrador';
}

function redirecionar(string $url): never {
    if (!headers_sent()) {
        header('Location: ' . $url);
    } else {
        echo '<meta http-equiv="refresh" content="0;url=' . escapar($url) . '">';
    }
    exit;
}
function exigirLogin(string $destino = 'login.php'): void
{
    if (estaLogado()) {
        return;
    }

    $_SESSION['retorno'] = basename($_SERVER['PHP_SELF']);
    redirecionar($destino);
}

function exigirAdministrador(): void
{
    if (!eAdministrador()) {
        redirecionar('login.php');
    }
}

function mensagemFlash(?string $tipo = null, ?string $texto = null): ?array
{
    if ($tipo !== null) {
        $_SESSION['flash'] = [$tipo, $texto];
        return null;
    }

    $mensagem = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $mensagem;
}

function valorMoeda(float $valor): string
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// Cor do pôster (1..5) derivada de um texto estável (nome do artista, categoria...).
function acentoPoster(string $chave): int
{
    return (int) (crc32($chave) % 5) + 1;
}

// Primeira letra visível de um nome, em maiúscula, para a inicial-fantasma dos pôsteres.
function inicial(string $nome): string
{
    return mb_strtoupper(mb_substr(trim($nome), 0, 1));
}

/**
 * Identifica se um produto é uma peça de vestuário/roupa (camiseta, moletom, etc.)
 */
function eRoupa(?array $produto): bool
{
    if (!$produto) {
        return false;
    }
    $categoria = mb_strtolower(trim($produto['categoria'] ?? ''));
    $nome = mb_strtolower(trim($produto['nome'] ?? ''));

    $categoriasRoupa = ['camiseta', 'camisetas', 'moletom', 'moletons', 'roupa', 'roupas', 'vestuario', 'vestuário'];
    if (in_array($categoria, $categoriasRoupa, true)) {
        return true;
    }

    if (str_starts_with($categoria, 'camis') || str_starts_with($categoria, 'molet')) {
        return true;
    }

    if (str_contains($nome, 'camiseta') || str_contains($nome, 'moletom') || str_contains($nome, 'camisa') || str_contains($nome, 'blusa')) {
        return true;
    }

    return false;
}

/**
 * Localiza e resolve o caminho da imagem de um item (produto, artista, hero).
 * Suporta URLs completas, caminhos relativos e busca por arquivo em disco por ID/slug.
 *
 * Uma foto aparece automaticamente de duas formas: (1) o upload do admin já grava
 * o caminho certo na coluna `imagem`, ou (2) para os dados de seed (sem `imagem`
 * no banco), basta o arquivo se chamar `{id}.{extensão}` dentro de
 * `assets/img/{produtos|artistas}/` — sem precisar editar nada em `MM.sql`.
 */
function obterCaminhoImagem(?string $imagem, string $pasta = 'produtos', $identificador = null): ?string
{
    if ($imagem !== null && trim($imagem) !== '') {
        $caminho = trim($imagem);
        $caminhoJaCompleto = preg_match('#^https?://#i', $caminho)
            || str_starts_with($caminho, '/')
            || str_starts_with($caminho, 'assets/');
        if ($caminhoJaCompleto) {
            return $caminho;
        }

        $caminhoRelativo = 'assets/img/' . trim($pasta, '/') . '/' . ltrim($caminho, '/');
        if (file_exists(__DIR__ . '/../' . $caminhoRelativo)) {
            return $caminhoRelativo;
        }
        return $caminho;
    }

    if ($identificador === null || $identificador === '') {
        return null;
    }

    $base = 'assets/img/' . trim($pasta, '/') . '/' . $identificador;
    foreach (['jpg', 'jpeg', 'png', 'webp', 'svg', 'jfif'] as $extensao) {
        $caminho = $base . '.' . $extensao;
        if (file_exists(__DIR__ . '/../' . $caminho)) {
            return $caminho;
        }
    }

    return null;
}

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

// Total de produtos por artista_id, usado na Home e na lista de artistas.
function contarProdutosPorArtista(array $produtos): array
{
    $totais = [];
    foreach ($produtos as $produto) {
        $chave = $produto['artista_id'];
        $totais[$chave] = ($totais[$chave] ?? 0) + 1;
    }
    return $totais;
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

    $ordenacoes = [
        'menor_preco' => 'preco ASC',
        'maior_preco' => 'preco DESC',
        'alfabetica' => 'nome ASC',
    ];
    $ordenacao = $ordenacoes[$filtros['ordenacao']] ?? 'destaque DESC, vendas DESC';

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
function calcularFrete(string $modalidade, string $estado = ''): float
{
    if ($modalidade === 'retirada') {
        return 0;
    }

    $estadoNormalizado = strtoupper(trim($estado));
    $ehSaoPaulo = $estadoNormalizado === 'SP' || stripos($estado, 'são paulo') !== false;
    return $ehSaoPaulo ? 10 : 20;
}

function statusPedido(): array
{
    return [
        'aguardando_pagamento' => 'Aguardando pagamento',
        'pagamento_confirmado' => 'Pagamento confirmado',
        'em_producao_separacao' => 'Em produção/separação',
        'enviado' => 'Enviado',
        'concluido' => 'Concluído',
    ];
}
