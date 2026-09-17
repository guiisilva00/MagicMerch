<?php
$imagemHero = obterCaminhoImagem(null, 'hero', 'hero') ?? obterCaminhoImagem(null, 'hero', 'banner');
?>
<section class="hero">
    <div class="container">
        <div class="hero__grid">
            <div class="hero__texto">
                <h1 class="hero__titulo">Merch feito à&nbsp;mão dos seus artistas.</h1>
                <p class="hero__lead">Camisetas, moletons, pôsteres e colecionáveis de edição limitada, organizados
                    por artista e por fandom.</p>
                <a href="produtos.php" class="btn btn--primario">Ver catálogo <?= icone('seta') ?></a>
            </div>
            <div class="hero__campo" aria-hidden="true">
                <?php if ($imagemHero): ?>
                    <img src="<?= escapar($imagemHero) ?>" alt="MagicMerch Banner" class="hero__img">
                <?php else: ?>
                    <span class="hero__campo-marca">MagicMerch</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
