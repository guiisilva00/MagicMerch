<section class="artists-section">
    <div class="container">

        <!-- Section header -->
        <div class="artists-header">
            <div class="artists-title-block">
                <span class="artists-subtitle" style="color: <?php echo $brand['razzmatazz']; ?>;">
                    — Artistas & Bandas
                </span>
                <h2 class="artists-title">
                    Navegue por artista
                </h2>
            </div>
            
            <div class="artists-nav-buttons">
                <!-- Visuais apenas, o scroll será natural via CSS -->
                <button class="artist-nav-btn active" style="border-color: <?php echo $brand['razzmatazz']; ?>; color: <?php echo $brand['razzmatazz']; ?>;">
                    <?php echo IconArrowLeft(); ?>
                </button>
                <button class="artist-nav-btn active" style="border-color: <?php echo $brand['razzmatazz']; ?>; color: <?php echo $brand['razzmatazz']; ?>;">
                    <?php echo IconArrowRight(); ?>
                </button>
            </div>
        </div>

        <!-- Cards track -->
        <div class="artists-track">
            <?php foreach ($artists as $i => $artist): 
                $accent = $paletteAccents[$i % count($paletteAccents)];
            ?>
                <a href="artista.php?id=<?php echo urlencode($artist['name']); ?>" class="artist-card group">
                    <!-- Photo -->
                    <div class="artist-photo-container">
                        <img src="<?php echo $artist['img']; ?>" alt="<?php echo htmlspecialchars($artist['name']); ?>" class="artist-photo">
                        
                        <div class="artist-photo-wash" style="background-color: <?php echo $accent; ?>;"></div>
                        <div class="artist-photo-fade"></div>
                        
                        <div class="artist-arrow-btn" style="background-color: <?php echo $accent; ?>;">
                            <?php echo IconArrowUpRight(); ?>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="artist-info">
                        <div class="artist-info-top">
                            <div>
                                <p class="artist-name"><?php echo htmlspecialchars($artist['name']); ?></p>
                                <p class="artist-genre"><?php echo htmlspecialchars($artist['genre']); ?></p>
                            </div>
                            <span class="artist-items-count" style="background-color: <?php echo $brand['pastelPetal']; ?>; color: <?php echo $brand['raspberryPlum']; ?>;">
                                <?php echo $artist['items']; ?>
                            </span>
                        </div>
                        <!-- Accent underline -->
                        <div class="artist-underline" style="background-color: <?php echo $accent; ?>;"></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Ver todos -->
        <div class="artists-footer">
            <a href="artistas.php" class="btn-primary" style="border-color: <?php echo $brand['razzmatazz']; ?>; color: <?php echo $brand['razzmatazz']; ?>;">
                Ver todos os artistas
                <span class="btn-icon"><?php echo IconArrowRight(); ?></span>
            </a>
        </div>

    </div>
</section>
