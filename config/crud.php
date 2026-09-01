<?php
require_once __DIR__ . '/database.php';

/**
 * Busca artistas cadastrados para preencher filtros e relacionar produtos.
 */
function buscarArtistas(PDO $conexao): array
{
    return $conexao->query('SELECT id, nome FROM artistas ORDER BY nome')->fetchAll();
}

/**
 * Busca as categorias disponíveis sem repetir valores.
 */
function buscarCategorias(PDO $conexao): array
{
    return $conexao->query('SELECT DISTINCT categoria FROM produtos WHERE categoria IS NOT NULL AND categoria <> \'\' ORDER BY categoria')->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Busca produtos aplicando apenas os filtros enviados pelo catálogo.
 */
function buscarProdutos(PDO $conexao, array $filtros = []): array
{
    $sql = 'SELECT produtos.id, produtos.nome, produtos.descricao, produtos.preco, produtos.estoque, produtos.categoria, produtos.imagem, artistas.nome AS nome_artista FROM produtos INNER JOIN artistas ON artistas.id = produtos.artista_id';
    $condicoes = [];
    $parametros = [];

    if (!empty($filtros['busca'])) {
        $condicoes[] = '(produtos.nome LIKE :busca OR produtos.descricao LIKE :busca)';
        $parametros[':busca'] = '%' . $filtros['busca'] . '%';
    }
    if (!empty($filtros['categoria'])) {
        $condicoes[] = 'produtos.categoria = :categoria';
        $parametros[':categoria'] = $filtros['categoria'];
    }
    if (!empty($filtros['cor'])) {
        $condicoes[] = 'produtos.cor = :cor';
        $parametros[':cor'] = $filtros['cor'];
    }
    if (!empty($filtros['tamanho'])) {
        $condicoes[] = 'produtos.tamanho LIKE :tamanho';
        $parametros[':tamanho'] = '%' . $filtros['tamanho'] . '%';
    }
    if (!empty($filtros['artista'])) {
        $condicoes[] = 'produtos.artista_id = :artista';
        $parametros[':artista'] = (int) $filtros['artista'];
    }
    if (($filtros['disponibilidade'] ?? '') === 'em_estoque') {
        $condicoes[] = 'produtos.estoque > 0';
    }
    if (($filtros['disponibilidade'] ?? '') === 'esgotados') {
        $condicoes[] = 'produtos.estoque = 0';
    }
    if (($filtros['preco_minimo'] ?? '') !== '') {
        $condicoes[] = 'produtos.preco >= :preco_minimo';
        $parametros[':preco_minimo'] = (float) $filtros['preco_minimo'];
    }
    if (($filtros['preco_maximo'] ?? '') !== '') {
        $condicoes[] = 'produtos.preco <= :preco_maximo';
        $parametros[':preco_maximo'] = (float) $filtros['preco_maximo'];
    }
    if ($condicoes) {
        $sql .= ' WHERE ' . implode(' AND ', $condicoes);
    }

    $ordenacoes = [
        'menor_preco' => 'produtos.preco ASC',
        'maior_preco' => 'produtos.preco DESC',
        'alfabetica' => 'produtos.nome ASC',
        'destaque' => 'produtos.destaque DESC, produtos.id DESC',
        'mais_vendidos' => 'produtos.vendas DESC, produtos.nome ASC',
    ];
    $sql .= ' ORDER BY ' . ($ordenacoes[$filtros['ordenacao'] ?? 'destaque'] ?? $ordenacoes['destaque']);

    $consulta = $conexao->prepare($sql);
    $consulta->execute($parametros);

    return $consulta->fetchAll();
}

/**
 * Busca um produto específico para a página de detalhes.
 */
function buscarProdutoPorId(PDO $conexao, int $idProduto): ?array
{
    $consulta = $conexao->prepare('SELECT produtos.*, artistas.nome AS nome_artista FROM produtos INNER JOIN artistas ON artistas.id = produtos.artista_id WHERE produtos.id = :id LIMIT 1');
    $consulta->execute([':id' => $idProduto]);
    $produto = $consulta->fetch();

    return $produto ?: null;
}