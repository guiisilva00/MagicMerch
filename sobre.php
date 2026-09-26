<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre nós | MagicMerch</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/sobre.css">
</head>
<body>
<header class="cabecalho">
    <div class="container cabecalho-conteudo">
        <a class="logo" href="index.php">MagicMerch</a>
        <nav class="navegacao" aria-label="Navegação principal">
            <a href="index.php">Início</a>
            <a href="sobre.php" aria-current="page">Sobre nós</a>
            <a href="produtos.php">Produtos</a>
            <a href="artistas.php">Artistas e bandas</a>
            <a href="#contato">Contato</a>
        </nav>
    </div>
</header>
<main>
    <section class="secao" aria-labelledby="historia-titulo">
        <div class="container colunas">
            <div class="historia-texto">
                <h1 id="historia-titulo">Nossa<br>História</h1>
                <div class="linha" aria-hidden="true"></div>
                <p class="historia-legenda">A história da MagicMerch</p>
            </div>
            <img class="foto historia-foto" src="assets/img/quem_somos/nicki-minaj.jpg" alt="Nicki Minaj com roupa e véu rosa" width="320" height="340">
        </div>
    </section>
    <section class="secao rosa" aria-labelledby="missao-titulo">
        <div class="container colunas">
            <div class="missao-texto">
                <h2 id="missao-titulo">Missão &amp;<br>Propósito</h2>
                <div class="linha" aria-hidden="true"></div>
                <p>Conectar fãs aos artistas, universos e comunidades que fazem parte de suas identidades, por meio de produtos criativos e de uma experiência acessível.</p>
            </div>
            <img class="foto retrato" src="assets/img/quem_somos/images.jpg" alt="Artista de boné preto sentado em frente a um piano" width="320" height="310">
        </div>
    </section>
    <section class="secao" aria-labelledby="comunidade-titulo">
        <div class="container">
            <img class="foto destaque-foto" src="assets/img/quem_somos/matue-foto-jp-maia.jpg" alt="Matuê em uma composição horizontal com iluminação verde" width="1040" height="300">
            <div class="destaque-legenda">
                <h2 id="comunidade-titulo">O Som, O Estilo, A Comunidade.</h2>
                <div class="linha" aria-hidden="true"></div>
            </div>
        </div>
    </section>
    <section class="secao rosa" aria-labelledby="dna-titulo">
        <div class="container colunas">
            <img class="foto retrato" src="assets/img/quem_somos/Brandao-984x553.webp" alt="Brandão com as mãos unidas diante de um fundo claro" width="320" height="310">
            <div class="dna-texto">
                <h2 id="dna-titulo">Nosso DNA:<br>Feito para<br>quem é fã.</h2>
                <p>Mais que uma referência estampada, um jeito de mostrar o que inspira você e de se reconhecer em uma comunidade.</p>
                <div class="linha" aria-hidden="true"></div>
            </div>
        </div>
    </section>
    <section class="secao" aria-labelledby="equipe-titulo">
        <div class="container colunas equipe-conteudo">
            <h2 id="equipe-titulo">A Equipe<br>MagicMerch</h2>
            <div class="equipe-grade">
                <?php for ($integrante = 0; $integrante < 4; $integrante++): ?>
                    <article class="integrante" aria-label="Integrante a preencher">
                        <div class="equipe-foto" role="img" aria-label="Foto do integrante a preencher">Foto<br>a preencher</div>
                        <div class="integrante-texto">
                            <h3>Nome a preencher</h3>
                            <p class="funcao">Função a preencher</p>
                            <p>Descrição a preencher.</p>
                        </div>
                    </article>
                <?php endfor; ?>
            </div>
        </div>
    </section>
</main>
<footer class="rodape" id="contato">
    <div class="container">
        <div class="rodape-conteudo">
            <a class="logo" href="index.php">MagicMerch</a>
            <nav class="rodape-links" aria-label="Navegação do rodapé">
                <a href="sobre.php">SOBRE</a>
                <a href="produtos.php">LOJA</a>
                <a href="#contato">CONTATO</a>
            </nav>
        </div>
        <p class="copyright">&copy; <?= date('Y') ?> MagicMerch. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>
