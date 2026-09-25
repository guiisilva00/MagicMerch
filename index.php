<?php
$tituloPagina = 'Início';
$paginaNavegacaoAtiva = 'Início';
require_once 'includes/header.php';

// Artistas com contagem de produtos e produtos em destaque — tudo via CRUD.
$artistasDestaque = [];
$destaques = [];
if ($pdo) {
    $produtos = readAll($pdo, 'produtos');
    $artistasPorId = indexarPorId(readAll($pdo, 'artistas'));
    $totais = contarProdutosPorArtista($produtos);

    foreach ($artistasPorId as $a) {
        $a['total'] = $totais[$a['id']] ?? 0;
        $artistasDestaque[] = $a;
    }
    usort($artistasDestaque, fn($x, $y) => strcmp($x['nome'], $y['nome']));

}
?>
<main>
    <?php require 'includes/components/hero-banner.php'; ?>

    <section class="objetivos" aria-labelledby="objetivos-titulo">
        <div class="container objetivos__grade">
            <header class="objetivos__cabecalho">
                <h2 id="objetivos-titulo" class="objetivos__titulo">Nossos<br>Objetivos</h2>
            </header>

            <div class="objetivos__imagem">
                <img src="assets/img/home/home.webp" alt="Imagem que representa a conexão entre fãs e artistas">
            </div>

            <div class="objetivos__introducao">
                <p>A MagicMerch aproxima fãs e artistas com produtos que celebram a cultura pop, a criatividade e a identidade de cada comunidade.</p>
            </div>

            <ol class="objetivos__lista">
                <li class="objetivos__item">
                    <span class="objetivos__numero">01</span>
                    <div>
                        <h3>Aproximar fãs e artistas</h3>
                        <p>Conectar pessoas aos artistas que fazem parte do seu dia a dia.</p>
                    </div>
                </li>
                <li class="objetivos__item">
                    <span class="objetivos__numero">02</span>
                    <div>
                        <h3>Valorizar identidades</h3>
                        <p>Celebrar o jeito único de cada artista e fandom.</p>
                    </div>
                </li>
                <li class="objetivos__item">
                    <span class="objetivos__numero">03</span>
                    <div>
                        <h3>Comprar com simplicidade</h3>
                        <p>Tornar a descoberta de cada peça clara e agradável.</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    <section class="porque" aria-labelledby="porque-titulo">
        <div class="container porque__grade">
            <header class="porque__cabecalho">
                <h2 id="porque-titulo" class="porque__titulo">Por que<br>nós?</h2>
            </header>

            <div class="porque__imagem">
                <img src="assets/img/home/por-que-nos.jpg" alt="Imagem que representa a conexão entre fãs e artistas">
            </div>

            <p class="porque__introducao">A MagicMerch reúne artistas, fãs e produtos em uma experiência simples, criativa e fácil de explorar.</p>

            <ol class="porque__lista">
                <li class="porque__item">
                    <span class="porque__numero">01</span>
                    <div>
                        <h3>Produtos com identidade</h3>
                        <p>Peças inspiradas em artistas, cultura pop e comunidades que fazem parte dessa experiência.</p>
                    </div>
                </li>
                <li class="porque__item">
                    <span class="porque__numero">02</span>
                    <div>
                        <h3>Experiência simples</h3>
                        <p>Uma navegação clara para encontrar e conhecer os produtos sem complicação.</p>
                    </div>
                </li>
                <li class="porque__item">
                    <span class="porque__numero">03</span>
                    <div>
                        <h3>Feito para fãs</h3>
                        <p>Uma proposta que celebra a conexão entre fãs, artistas e aquilo que eles gostam.</p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    <section class="faixa faixa--pastel">
        <div class="container">
            <h2 class="secao-titulo">Navegue por artista</h2>
            <div class="grade grade--artistas">
                <?php foreach ($artistasDestaque as $artista): ?>
                    <?php
                    $cor = acentoPoster($artista['nome']);
                    $imgUrl = obterCaminhoImagem($artista['imagem'] ?? null, 'artistas', $artista['id'] ?? null);
                    $classeCampo = 'poster__campo' . ($imgUrl ? ' poster__campo--com-imagem' : '');
                    ?>
                    <a class="poster poster--artista poster--c<?= $cor ?>" href="artistas.php?artista=<?= (int) $artista['id'] ?>">
                        <div class="<?= $classeCampo ?>">
                            <?php if ($imgUrl): ?><img src="<?= escapar($imgUrl) ?>" alt="<?= escapar($artista['nome']) ?>" class="poster__img"><?php else: ?><span class="poster__inicial" aria-hidden="true"><?= escapar(inicial($artista['nome'])) ?></span><?php endif; ?>
                            <span class="tag"><?= (int) ($artista['total'] ?? 0) ?> produtos</span>
                        </div>
                        <div class="poster__meta"><span class="poster__meta-desc"><?= escapar($artista['descricao'] ?? '') ?></span></div>
                    </a>
                <?php endforeach; ?>
            </div>
            <p class="secao-acao"><a href="artistas.php" class="btn btn--linha">Ver todos os artistas <?= icone('seta') ?></a></p>
        </div>
    </section>
</main>
<?php require 'includes/footer.php'; ?>
