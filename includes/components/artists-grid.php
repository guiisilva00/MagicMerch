<section class="faixa faixa--pastel">
    <div class="container">
        <h2 class="secao-titulo">Navegue por artista</h2>
        <div class="grade grade--artistas">
            <?php foreach ($artistasDestaque as $artista): ?>
                <?= posterArtista($artista) ?>
            <?php endforeach; ?>
        </div>
        <p class="secao-acao"><a href="artistas.php" class="btn btn--linha">Ver todos os artistas <?= icone('seta') ?></a></p>
    </div>
</section>
