<?php
require_once __DIR__ . '/config/app.php';

$paginaNavegacaoAtiva = 'Coleções especiais';
require_once __DIR__ . '/includes/header.php';
?>

<main class="container pagina">
    <header class="cabecalho-pagina">
        <h1>Coleções Especiais</h1>
        <p>Explore nossos drops exclusivos e edições limitadas.</p>
    </header>

    <div class="vazio">
        <h2>Em breve novidades</h2>
        <p>Estamos preparando coleções temáticas e exclusivas para você.</p>
        <a href="produtos.php" class="btn btn--primario">Explorar produtos</a>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
