<?php $tituloPagina = 'Artistas e bandas';
$paginaNavegacaoAtiva = 'Artistas e bandas';
require 'includes/header.php';
$db = criarConexaoBancoDados();
$arts = $db->query('SELECT a.*,COUNT(p.id) total FROM artistas a LEFT JOIN produtos p ON p.artista_id=a.id GROUP BY a.id ORDER BY a.nome')->fetchAll(); ?>
<main class="container pagina-conteudo">
    <h1>Artistas e bandas</h1>
    <section class="produtos-grid"><?php foreach ($arts as $a): ?><a class="produto-card produto-informacoes"
                href="produtos.php?artista=<?= $a['id'] ?>">
                <h2><?= escapar($a['nome']) ?></h2>
                <p><?= escapar($a['descricao']) ?></p>
                <p><?= $a['total'] ?> produtos</p>
            </a><?php endforeach; ?></section>
</main><?php require 'includes/footer.php'; ?>