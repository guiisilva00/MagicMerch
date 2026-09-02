<?php
$tituloPagina = 'Início';
$paginaNavegacaoAtiva = 'Início';
require_once 'includes/header.php';
require_once 'includes/poster.php';

// Artistas com contagem de produtos e produtos em destaque — tudo via CRUD.
$artistasDestaque = [];
$destaques = [];
if ($pdo) {
    $produtos = readAll($pdo, 'produtos');
    $artistasPorId = indexarPorId(readAll($pdo, 'artistas'));

    foreach ($artistasPorId as $a) {
        $a['total'] = count(array_filter($produtos, fn($p) => $p['artista_id'] == $a['id']));
        $artistasDestaque[] = $a;
    }
    usort($artistasDestaque, fn($x, $y) => strcmp($x['nome'], $y['nome']));

    $destaques = array_filter($produtos, fn($p) => (int) $p['destaque'] === 1);
    usort($destaques, fn($x, $y) => (int) $y['vendas'] <=> (int) $x['vendas']);
    $destaques = array_slice($destaques, 0, 4);
    foreach ($destaques as &$d) {
        $d['nome_artista'] = $artistasPorId[$d['artista_id']]['nome'] ?? '';
    }
    unset($d);
}
?>
<main>
    <?php require 'includes/components/hero-banner.php'; ?>

    <?php if ($destaques): ?>
        <section class="secao container pagina">
            <h2 class="secao-titulo">Em destaque</h2>
            <div class="grade">
                <?php foreach ($destaques as $produto): ?><?= posterProduto($produto) ?><?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php require 'includes/components/artists-grid.php'; ?>
</main>
<?php require 'includes/footer.php'; ?>
