<?php
$tituloPagina = 'Coleções Especiais';
$paginaNavegacaoAtiva = 'Coleções especiais';
require_once 'includes/header.php';
?>

<main class="container pagina">
    <header class="cabecalho-pagina">
        <h1>Coleções Especiais</h1>
        <p>Confira nossas coleções temáticas, drops limitados e novas peças exclusivas.</p>
    </header>

    <!-- ===================================================================
         1. BANNER PRINCIPAL DESTAQUE
         Troque a foto no atributo src da tag <img> abaixo
    ==================================================================== -->
    <section style="margin-bottom: var(--s-8);">
        <div class="poster poster--c1">
            <div class="poster__campo" style="aspect-ratio: 16 / 7; min-height: 240px;">
                <!-- FOTO DO BANNER -->
                <img src="assets/img/hero/hero.webp" alt="Banner Coleção Especial" class="poster__img">
                <span class="tag" style="position: absolute; top: var(--s-3); left: var(--s-3);">Lançamento</span>
            </div>
            <div class="poster__meta">
                <span class="poster__meta-nome">Coleção Especial de Verão</span>
                <span class="poster__meta-desc">Peças limitadas feitas com acabamento artesanal e estampas exclusivas.</span>
                <p style="margin-top: var(--s-3);">
                    <a href="produtos.php" class="btn btn--primario">Ver produtos da coleção</a>
                </p>
            </div>
        </div>
    </section>

    <!-- ===================================================================
         2. CARDS DAS COLEÇÕES (Áreas para fotos dos drops)
         Troque as fotos no src de cada card abaixo
    ==================================================================== -->
    <section style="margin-bottom: var(--s-8);">
        <h2 style="font-family: 'Playfair Display', serif; font-size: var(--step-3); margin-bottom: var(--s-4);">Nossos Drops</h2>

        <div class="grade">
            <!-- Card Coleção 1 -->
            <div class="poster poster--c2">
                <div class="poster__campo">
                    <!-- FOTO COLEÇÃO 1 -->
                    <img src="assets/img/produtos/1.jpg" alt="Drop 01" class="poster__img">
                    <span class="tag">Novo</span>
                </div>
                <div class="poster__meta">
                    <span class="poster__meta-nome">Drop 01 · Pop & Fandom</span>
                    <span class="poster__meta-desc">Camisetas leves e confortáveis inspiradas na cultura pop.</span>
                    <p style="margin-top: var(--s-3);"><a href="produtos.php" class="btn btn--linha">Ver coleção</a></p>
                </div>
            </div>

            <!-- Card Coleção 2 -->
            <div class="poster poster--c3">
                <div class="poster__campo">
                    <!-- FOTO COLEÇÃO 2 -->
                    <img src="assets/img/produtos/2.jpg" alt="Drop 02" class="poster__img">
                    <span class="tag">Edição Limitada</span>
                </div>
                <div class="poster__meta">
                    <span class="poster__meta-nome">Drop 02 · Rock Legends</span>
                    <span class="poster__meta-desc">Moletons e casacos com estampas vintage dos maiores clássicos.</span>
                    <p style="margin-top: var(--s-3);"><a href="produtos.php" class="btn btn--linha">Ver coleção</a></p>
                </div>
            </div>

            <!-- Card Coleção 3 -->
            <div class="poster poster--c4">
                <div class="poster__campo">
                    <!-- FOTO COLEÇÃO 3 -->
                    <img src="assets/img/produtos/3.jpg" alt="Drop 03" class="poster__img">
                    <span class="tag">Colecionável</span>
                </div>
                <div class="poster__meta">
                    <span class="poster__meta-nome">Drop 03 · Galeria Visual</span>
                    <span class="poster__meta-desc">Pôsteres impressos em alta gramatura com artes autênticas.</span>
                    <p style="margin-top: var(--s-3);"><a href="produtos.php" class="btn btn--linha">Ver coleção</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================================================
         3. GALERIA DE FOTOS (Espaço simples para fotos de ensaio e modelos)
         Troque as fotos no src de cada imagem abaixo
    ==================================================================== -->
    <section style="margin-bottom: var(--s-8);">
        <h2 style="font-family: 'Playfair Display', serif; font-size: var(--step-3); margin-bottom: var(--s-4);">Galeria de Fotos</h2>

        <div class="grade">
            <!-- Foto 1 -->
            <div class="poster">
                <div class="poster__campo" style="aspect-ratio: 1 / 1;">
                    <!-- FOTO 1 -->
                    <img src="assets/img/home/por-que-nos.jpg" alt="Foto Galeria 1" class="poster__img">
                </div>
                <div class="poster__meta">
                    <span class="poster__meta-nome">Detalhes das Estampas</span>
                </div>
            </div>

            <!-- Foto 2 -->
            <div class="poster">
                <div class="poster__campo" style="aspect-ratio: 1 / 1;">
                    <!-- FOTO 2 -->
                    <img src="assets/img/home/home.webp" alt="Foto Galeria 2" class="poster__img">
                </div>
                <div class="poster__meta">
                    <span class="poster__meta-nome">Lookbook Urbano</span>
                </div>
            </div>

            <!-- Foto 3 -->
            <div class="poster">
                <div class="poster__campo" style="aspect-ratio: 1 / 1;">
                    <!-- FOTO 3 -->
                    <img src="assets/img/produtos/14.jpg" alt="Foto Galeria 3" class="poster__img">
                </div>
                <div class="poster__meta">
                    <span class="poster__meta-nome">Produção & Tecido</span>
                </div>
            </div>

            <!-- Foto 4 -->
            <div class="poster">
                <div class="poster__campo" style="aspect-ratio: 1 / 1;">
                    <!-- FOTO 4 -->
                    <img src="assets/img/produtos/17.jpg" alt="Foto Galeria 4" class="poster__img">
                </div>
                <div class="poster__meta">
                    <span class="poster__meta-nome">Acessórios Exclusivos</span>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require 'includes/footer.php'; ?>
