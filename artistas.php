<?php
$tituloPagina = 'Artistas e bandas';
$paginaNavegacaoAtiva = 'Artistas e bandas';
require 'includes/header.php';
require_once 'includes/poster.php';

$produtos = $pdo ? readAll($pdo, 'produtos') : [];
$arts = $pdo ? readAll($pdo, 'artistas', '1 ORDER BY nome') : [];
foreach ($arts as &$a) {
    $a['total'] = count(array_filter($produtos, fn($p) => $p['artista_id'] == $a['id']));
}
unset($a);
?>
<main class="container pagina">
    <header class="cabecalho-pagina">
        <h1>Artistas e bandas</h1>
        <p>O MagicMerch é organizado em torno de quem você acompanha. Escolha um artista e veja tudo dele.</p>
    </header>

    <?php if (!$arts): ?>
        <div class="vazio">
            <span class="vazio__inicial" aria-hidden="true">♪</span>
            <h2>Nenhum artista cadastrado</h2>
            <p>Assim que houver artistas no catálogo, eles aparecem aqui.</p>
        </div>
    <?php else: ?>
        <div class="grade grade--artistas">
            <?php foreach ($arts as $a): ?><?= posterArtista($a) ?><?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php require 'includes/footer.php'; ?>
