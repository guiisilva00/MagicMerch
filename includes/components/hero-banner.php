<section class="hero-section">
    <div class="hero-slider">
        <?php foreach ($heroSlides as $index => $slide): ?>
            <div class="hero-slide" id="slide-<?php echo $index; ?>">
                
                <!-- Col 1 — Editorial info -->
                <div class="hero-col hero-col-info">
                    <div class="hero-bg-tint" style="background-color: <?php echo $slide['accentColor']; ?>;"></div>
                    <div class="hero-info-top">
                        <span class="hero-season">
                            <?php echo $slide['season']; ?> — <?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?>/<?php echo str_pad(count($heroSlides), 2, '0', STR_PAD_LEFT); ?>
                        </span>
                        <span class="hero-collection-label" style="background-color: <?php echo $brand['pastelPetal']; ?>; color: <?php echo $brand['raspberryPlum']; ?>;">
                            <?php echo htmlspecialchars($slide['collection']); ?>
                        </span>
                        <p class="hero-desc">
                            <?php echo htmlspecialchars($slide['description']); ?>
                        </p>
                    </div>
                    <div class="hero-info-bottom">
                        <button class="hero-cta" style="color: <?php echo $slide['accentColor']; ?>;">
                            <?php echo htmlspecialchars($slide['cta']); ?>
                            <span class="hero-cta-line" style="background-color: <?php echo $slide['accentColor']; ?>;"></span>
                        </button>
                    </div>
                </div>

                <!-- Col 2 — Tall model -->
                <div class="hero-col hero-col-tall">
                    <img src="<?php echo $slide['panels'][0]['img']; ?>" alt="<?php echo htmlspecialchars($slide['panels'][0]['alt']); ?>">
                    <div class="hero-gradient" style="background: linear-gradient(to top, <?php echo $slide['accentColor']; ?>, transparent);"></div>
                </div>

                <!-- Col 3 — Model top + product bottom -->
                <div class="hero-col hero-col-split">
                    <div class="hero-split-top">
                        <img src="<?php echo $slide['panels'][1]['img']; ?>" alt="<?php echo htmlspecialchars($slide['panels'][1]['alt']); ?>">
                    </div>
                    <div class="hero-split-bottom">
                        <img src="<?php echo $slide['panels'][2]['img']; ?>" alt="<?php echo htmlspecialchars($slide['panels'][2]['alt']); ?>">
                    </div>
                </div>

                <!-- Col 4 — Right model -->
                <div class="hero-col hero-col-right">
                    <img src="<?php echo $slide['panels'][3]['img']; ?>" alt="<?php echo htmlspecialchars($slide['panels'][3]['alt']); ?>">
                </div>

                <!-- Col 5 — Typography + dots -->
                <div class="hero-col hero-col-typo">
                    <div class="hero-typo-tint" style="background-color: <?php echo $slide['accentColor']; ?>;"></div>
                    <div class="hero-typo-content">
                        <?php foreach ($slide['headline'] as $i => $word): ?>
                            <div class="hero-headline" style="color: <?php echo $i === 1 ? $slide['accentColor'] : '#111'; ?>;">
                                <?php echo htmlspecialchars($word); ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="hero-subheading" style="color: <?php echo $brand['wildStrawberry']; ?>;">
                            <?php echo htmlspecialchars($slide['subheading']); ?>
                        </div>
                    </div>

                    <!-- Vertical dot nav -->
                    <div class="hero-dots">
                        <?php foreach ($heroSlides as $dotIndex => $s): ?>
                            <a href="#slide-<?php echo $dotIndex; ?>" class="hero-dot <?php echo $index === $dotIndex ? 'active' : ''; ?>" style="<?php echo $index === $dotIndex ? 'background-color: ' . $s['accentColor'] . ';' : ''; ?>"></a>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

    <!-- Arrows visual only -->
    <a href="#slide-0" class="hero-arrow arrow-left">
        <?php echo IconArrowLeft(); ?>
    </a>
    <a href="#slide-1" class="hero-arrow arrow-right">
        <?php echo IconArrowRight(); ?>
    </a>
</section>
