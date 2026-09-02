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
    ob_start(); ?>
    <a class="poster poster--c<?= $cor ?>" href="produto.php?id=<?= (int) $p['id'] ?>">
        <div class="poster__campo">
            <span class="poster__inicial" aria-hidden="true"><?= escapar(inicial($p['nome_artista'] ?: $p['nome'])) ?></span>
            <?php if (!empty($p['categoria'])): ?><span class="tag"><?= escapar($p['categoria']) ?></span><?php endif; ?>
            <span class="poster__nome"><?= escapar($p['nome']) ?></span>
            <span class="poster__campo-preco">R$ <?= number_format((float) $p['preco'], 2, ',', '.') ?></span>
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
    ob_start(); ?>
    <a class="poster poster--artista poster--c<?= $cor ?>" href="produtos.php?artista=<?= (int) $a['id'] ?>">
        <div class="poster__campo">
            <span class="poster__inicial" aria-hidden="true"><?= escapar(inicial($a['nome'])) ?></span>
            <span class="tag"><?= (int) ($a['total'] ?? 0) ?> produtos</span>
            <span class="poster__nome"><?= escapar($a['nome']) ?></span>
        </div>
        <div class="poster__meta">
            <span class="poster__meta-desc"><?= escapar($a['descricao'] ?? '') ?></span>
        </div>
    </a>
    <?php
    return ob_get_clean();
}
