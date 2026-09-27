<?php
$tituloPagina = 'Sobre Nós';
$paginaNavegacaoAtiva = 'Sobre nós';
require_once 'includes/header.php';
?>
<main class="pagina-sobre">
    <section class="faixa" aria-labelledby="sobre-titulo">
        <div class="container sobre__colunas">
            <div class="sobre__texto">
                <h1 id="sobre-titulo">Nossa<br>História</h1>
                <p class="sobre__legenda"><span>Sobre Nós</span><span class="sobre__traco" aria-hidden="true"></span><span>A História do MagicMerch</span></p>
                <p class="sobre__apresentacao">A MagicMerch aproxima fãs e artistas com produtos que celebram a cultura pop, a criatividade e a identidade de cada comunidade.</p>
            </div>
            <div class="sobre__imagem sobre__imagem--retrato">
                <img src="assets/img/quem_somos/nicki-minaj.jpg" alt="Nicki Minaj em um retrato com tons de rosa" width="1280" height="720">
            </div>
        </div>
    </section>

    <section class="faixa faixa--pastel" aria-labelledby="proposito-titulo">
        <div class="container sobre__colunas">
            <div class="sobre__texto">
                <h2 id="proposito-titulo">Missão &amp;<br>Propósito</h2>
                <p>Conectar fãs aos artistas e comunidades que fazem parte de suas identidades, com produtos criativos e uma experiência de compra simples.</p>
            </div>
            <div class="sobre__imagem">
                <img src="assets/img/quem_somos/images.jpg" alt="Artista de boné preto sentado diante de um piano" width="681" height="450" loading="lazy">
            </div>
        </div>
    </section>

    <section class="faixa" aria-labelledby="historia-titulo">
        <div class="container">
            <div class="sobre__imagem sobre__imagem--larga">
                <img src="assets/img/quem_somos/matue-foto-jp-maia.jpg" alt="Matuê em uma foto com iluminação esverdeada" width="1920" height="1080" loading="lazy">
            </div>
            <div class="sobre__destaque-titulo">
                <h2 id="historia-titulo">O Som, O Estilo, A Comunidade.</h2>
                <span class="sobre__traco" aria-hidden="true"></span>
            </div>
        </div>
    </section>

    <section class="faixa faixa--pastel" aria-labelledby="valores-titulo">
        <div class="container sobre__colunas sobre__colunas--imagem-esquerda">
            <div class="sobre__imagem">
                <img src="assets/img/quem_somos/Brandao-984x553.webp" alt="Brandão com as mãos unidas diante de um fundo claro" width="984" height="553" loading="lazy">
            </div>
            <div class="sobre__texto">
                <h2 id="valores-titulo">Nosso DNA:<br>Conexão e<br>Criatividade</h2>
                <p>Valorizamos a criatividade, a identidade de cada comunidade de fãs e a facilidade para descobrir uma peça que tenha a ver com você.</p>
            </div>
        </div>
    </section>

    <section class="faixa" aria-labelledby="equipe-titulo">
        <div class="container sobre__colunas sobre__final">
            <div>
                <h2 id="equipe-titulo">A Equipe<br>MagicMerch</h2>
            </div>
            <div class="sobre__equipe">
                <?php for ($integrante = 0; $integrante < 4; $integrante++): ?>
                    <article class="sobre__integrante">
                        <div class="sobre__foto" role="img" aria-label="Foto do integrante a preencher">Foto a preencher</div>
                        <div>
                            <h3>Nome a preencher</h3>
                            <p>Função a preencher</p>
                            <p>Descrição a preencher.</p>
                        </div>
                    </article>
                <?php endfor; ?>
            </div>
        </div>
    </section>
</main>
<?php require 'includes/footer.php'; ?>
