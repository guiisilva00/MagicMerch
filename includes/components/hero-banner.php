<section class="hero-section">
    <div class="hero-slider">
        <?php foreach ($slidesDestaque as $indiceSlide => $slideDestaque): ?>
            <div class="hero-slide" id="slide-<?php echo $indiceSlide; ?>">
                <div class="hero-col hero-col-info">
                    <div class="hero-bg-tint" style="background-color: <?php echo $slideDestaque['corDestaque']; ?>;"></div>
                    <div class="hero-info-top">
                        <span class="hero-season"><?php echo escaparHtml($slideDestaque['temporada']); ?> — <?php echo str_pad($indiceSlide + 1, 2, '0', STR_PAD_LEFT); ?>/<?php echo str_pad(count($slidesDestaque), 2, '0', STR_PAD_LEFT); ?></span>
                        <span class="hero-collection-label" style="background-color: <?php echo $marca['rosaPastel']; ?>; color: <?php echo $marca['ameixa']; ?>;"><?php echo escaparHtml($slideDestaque['colecao']); ?></span>
                        <p class="hero-desc"><?php echo escaparHtml($slideDestaque['descricao']); ?></p>
                    </div>
                    <div class="hero-info-bottom"><button class="hero-cta" type="button" style="color: <?php echo $slideDestaque['corDestaque']; ?>;"><?php echo escaparHtml($slideDestaque['acao']); ?><span class="hero-cta-line" style="background-color: <?php echo $slideDestaque['corDestaque']; ?>;"></span></button></div>
                </div>
                <div class="hero-col hero-col-tall"><img src="<?php echo escaparHtml($slideDestaque['paineis'][0]['imagem']); ?>" alt="<?php echo escaparHtml($slideDestaque['paineis'][0]['textoAlternativo']); ?>"><div class="hero-gradient" style="background: linear-gradient(to top, <?php echo $slideDestaque['corDestaque']; ?>, transparent);"></div></div>
                <div class="hero-col hero-col-split"><div class="hero-split-top"><img src="<?php echo escaparHtml($slideDestaque['paineis'][1]['imagem']); ?>" alt="<?php echo escaparHtml($slideDestaque['paineis'][1]['textoAlternativo']); ?>"></div><div class="hero-split-bottom"><img src="<?php echo escaparHtml($slideDestaque['paineis'][2]['imagem']); ?>" alt="<?php echo escaparHtml($slideDestaque['paineis'][2]['textoAlternativo']); ?>"></div></div>
                <div class="hero-col hero-col-right"><img src="<?php echo escaparHtml($slideDestaque['paineis'][3]['imagem']); ?>" alt="<?php echo escaparHtml($slideDestaque['paineis'][3]['textoAlternativo']); ?>"></div>
                <div class="hero-col hero-col-typo">
                    <div class="hero-typo-tint" style="background-color: <?php echo $slideDestaque['corDestaque']; ?>;"></div>
                    <div class="hero-typo-content">
                        <?php foreach ($slideDestaque['chamada'] as $indicePalavra => $palavra): ?><div class="hero-headline" style="color: <?php echo $indicePalavra === 1 ? $slideDestaque['corDestaque'] : '#111'; ?>;"><?php echo escaparHtml($palavra); ?></div><?php endforeach; ?>
                        <div class="hero-subheading" style="color: <?php echo $marca['rosaClaro']; ?>;"><?php echo escaparHtml($slideDestaque['subtitulo']); ?></div>
                    </div>
                    <nav class="hero-dots" aria-label="Navegação entre destaques">
                        <?php foreach ($slidesDestaque as $indicePonto => $outroSlide): ?><a href="#slide-<?php echo $indicePonto; ?>" class="hero-dot <?php echo $indiceSlide === $indicePonto ? 'active' : ''; ?>" aria-label="Exibir destaque <?php echo $indicePonto + 1; ?>" style="<?php echo $indiceSlide === $indicePonto ? 'background-color: ' . $outroSlide['corDestaque'] . ';' : ''; ?>"></a><?php endforeach; ?>
                    </nav>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="#slide-0" class="hero-arrow arrow-left" aria-label="Exibir destaque anterior"><?php echo iconeSetaEsquerda(); ?></a>
    <a href="#slide-1" class="hero-arrow arrow-right" aria-label="Exibir próximo destaque"><?php echo iconeSetaDireita(); ?></a>
</section>