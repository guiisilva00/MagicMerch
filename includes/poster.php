<?php
require_once __DIR__ . '/icones.php';

/**
 * Pôster de produto — usado no catálogo, na Home e nos relacionados.
 * $p precisa de: id, nome, preco, estoque, categoria, nome_artista.
 */
function posterProduto(array $p): string
{
    $cor = acentoPoster($p['categoria'] ?? $p['nome']);
    $esgotado = (int) $p['estoque'] <= 0;
    $imgUrl = obterCaminhoImagem($p['imagem'] ?? null, 'produtos');
    $classeCampo = 'poster__campo' . ($imgUrl ? ' poster__campo--com-imagem' : '');
    ob_start(); ?>
    <a class="poster poster--c<?= $cor ?>" href="produto.php?id=<?= (int) $p['id'] ?>">
        <div class="<?= $classeCampo ?>">
            <?php if ($imgUrl): ?>
                <img src="<?= escapar($imgUrl) ?>" alt="<?= escapar($p['nome']) ?>" class="poster__img">
            <?php else: ?>
                <span class="poster__inicial" aria-hidden="true"><?= escapar(inicial($p['nome_artista'] ?: $p['nome'])) ?></span>
                <span class="poster__nome"><?= escapar($p['nome']) ?></span>
            <?php endif; ?>
            <?php if ($esgotado): ?><span class="poster__esgotado">Esgotado</span><?php endif; ?>
        </div>
        <div class="poster__meta">
            <span class="poster__meta-artista"><?= escapar($p['nome_artista'] ?? '') ?></span>
            <span class="poster__meta-nome"><?= escapar($p['nome']) ?></span>
            <span class="poster__meta-preco">R$ <?= number_format((float) $p['preco'], 2, ',', '.') ?></span>
        </div>
    </a>
    <?php
    return ob_get_clean();
}

/**
 * Pôster de artista — usado na Home e na página de artistas.
 * $a precisa de: id, nome, descricao, total.
 */
function posterArtista(array $a): string
{
    $cor = acentoPoster($a['nome']);
    $imgUrl = obterCaminhoImagem($a['imagem'] ?? null, 'artistas', $a['id'] ?? null);
    $classeCampo = 'poster__campo' . ($imgUrl ? ' poster__campo--com-imagem' : '');
    ob_start(); ?>
    <a class="poster poster--artista poster--c<?= $cor ?>" href="artistas.php?artista=<?= (int) $a['id'] ?>">
        <div class="<?= $classeCampo ?>">
            <?php if ($imgUrl): ?>
                <img src="<?= escapar($imgUrl) ?>" alt="<?= escapar($a['nome']) ?>" class="poster__img">
            <?php else: ?>
                <span class="poster__inicial" aria-hidden="true"><?= escapar(inicial($a['nome'])) ?></span>
            <?php endif; ?>
            <span class="tag"><?= (int) ($a['total'] ?? 0) ?> produtos</span>
        </div>
        <div class="poster__meta">
            <span class="poster__meta-desc"><?= escapar($a['descricao'] ?? '') ?></span>
        </div>
    </a>
    <?php
    return ob_get_clean();
}
