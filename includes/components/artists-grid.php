<section class="artists-section">
    <div class="container">
        <div class="artists-header">
            <div class="artists-title-block"><span class="artists-subtitle"
                    style="color: <?php echo $marca['rosaIntenso']; ?>;">— Artistas & bandas</span>
                <h2 class="artists-title">Navegue por artista</h2>
            </div>
            <div class="artists-nav-buttons" aria-hidden="true"><button class="artist-nav-btn active" type="button"
                    style="border-color: <?php echo $marca['rosaIntenso']; ?>; color: <?php echo $marca['rosaIntenso']; ?>;"><?php echo iconeSetaEsquerda(); ?></button><button
                    class="artist-nav-btn active" type="button"
                    style="border-color: <?php echo $marca['rosaIntenso']; ?>; color: <?php echo $marca['rosaIntenso']; ?>;"><?php echo iconeSetaDireita(); ?></button>
            </div>
        </div>
        <div class="artists-track">
            <?php foreach ($artistasDestaque as $indiceArtista => $artista): ?>
                <?php $corDestaque = $coresDestaque[$indiceArtista % count($coresDestaque)]; ?>
                <a href="artistas.php?artista=<?php echo urlencode($artista['nome']); ?>" class="artist-card">
                    <div class="artist-photo-container"><img src="<?php echo escaparHtml($artista['imagem']); ?>"
                            alt="<?php echo escaparHtml($artista['nome']); ?>" class="artist-photo">
                        <div class="artist-photo-wash" style="background-color: <?php echo $corDestaque; ?>;"></div>
                        <div class="artist-photo-fade"></div>
                        <div class="artist-arrow-btn" style="background-color: <?php echo $corDestaque; ?>;">
                            <?php echo iconeSetaDiagonal(); ?></div>
                    </div>
                    <div class="artist-info">
                        <div class="artist-info-top">
                            <div>
                                <p class="artist-name"><?php echo escaparHtml($artista['nome']); ?></p>
                                <p class="artist-genre"><?php echo escaparHtml($artista['genero']); ?></p>
                            </div><span class="artist-items-count"
                                style="background-color: <?php echo $marca['rosaPastel']; ?>; color: <?php echo $marca['ameixa']; ?>;"><?php echo $artista['quantidadeItens']; ?></span>
                        </div>
                        <div class="artist-underline" style="background-color: <?php echo $corDestaque; ?>;"></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="artists-footer"><a href="artistas.php" class="btn-primary"
                style="border-color: <?php echo $marca['rosaIntenso']; ?>; color: <?php echo $marca['rosaIntenso']; ?>;">Ver
                todos os artistas <span class="btn-icon"><?php echo iconeSetaDireita(); ?></span></a></div>
    </div>
</section>