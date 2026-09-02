<?php require_once __DIR__ . '/config/app.php';
$id = (int) ($_GET['id'] ?? 0);
$produto = $pdo ? buscarProdutoPorId($pdo, $id) : null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirLogin();
    $uid = (int) usuarioAtual()['id'];
    if (isset($_POST['carrinho'])) {
        $item = read($pdo, 'carrinho', 'usuario_id = ? AND produto_id = ?', [$uid, $id]);
        if ($item) {
            update($pdo, 'carrinho', ['quantidade' => (int) $item['quantidade'] + 1], 'usuario_id = ? AND produto_id = ?', [$uid, $id]);
        } else {
            create($pdo, 'carrinho', ['usuario_id' => $uid, 'produto_id' => $id, 'quantidade' => 1]);
        }
        mensagemFlash('sucesso', 'Produto adicionado ao carrinho.');
    } elseif (isset($_POST['favorito'])) {
        if (!read($pdo, 'favoritos', 'usuario_id = ? AND produto_id = ?', [$uid, $id])) {
            create($pdo, 'favoritos', ['usuario_id' => $uid, 'produto_id' => $id]);
        }
        mensagemFlash('sucesso', 'Produto salvo nos favoritos.');
    } else {
        $dados = ['nota' => (int) $_POST['nota'], 'comentario' => trim($_POST['comentario'])];
        if (read($pdo, 'avaliacoes', 'usuario_id = ? AND produto_id = ?', [$uid, $id])) {
            update($pdo, 'avaliacoes', $dados, 'usuario_id = ? AND produto_id = ?', [$uid, $id]);
        } else {
            create($pdo, 'avaliacoes', $dados + ['usuario_id' => $uid, 'produto_id' => $id]);
        }
        mensagemFlash('sucesso', 'Avaliação publicada.');
    }
    redirecionar('produto.php?id=' . $id);
}
// Avaliações do produto com o nome de quem avaliou (join resolvido em PHP).
$avaliacoes = [];
if ($produto) {
    $usuarios = indexarPorId(readAll($pdo, 'usuarios'));
    foreach (readAll($pdo, 'avaliacoes', 'produto_id = ? ORDER BY data_avaliacao DESC', [$id]) as $a) {
        $a['nome'] = $usuarios[$a['usuario_id']]['nome'] ?? '';
        $avaliacoes[] = $a;
    }
}
$tituloPagina = $produto ? $produto['nome'] : 'Produto';
$mediaNota = $avaliacoes ? round(array_sum(array_column($avaliacoes, 'nota')) / count($avaliacoes), 1) : null;
$corProduto = $produto ? acentoPoster($produto['categoria'] ?? $produto['nome']) : 1;
require 'includes/header.php'; ?>
<main class="container pagina">
    <?php if (!$produto): ?>
        <div class="vazio">
            <span class="vazio__inicial" aria-hidden="true">!</span>
            <h1>Produto não encontrado</h1>
            <p>Esse item pode ter saído do catálogo.</p>
            <a class="btn btn--linha" href="produtos.php">Voltar ao catálogo</a>
        </div>
    <?php else: ?>
        <article class="detalhe">
            <div class="poster poster--detalhe poster--c<?= $corProduto ?>">
                <div class="poster__campo">
                    <span class="poster__inicial" aria-hidden="true"><?= escapar(inicial($produto['nome_artista'] ?: $produto['nome'])) ?></span>
                    <span class="tag"><?= escapar($produto['categoria']) ?></span>
                    <span class="poster__nome"><?= escapar($produto['nome']) ?></span>
                    <?php if ($produto['estoque'] <= 0): ?><span class="poster__esgotado">Esgotado</span><?php endif; ?>
                </div>
            </div>

            <div class="detalhe__info">
                <p class="detalhe__artista"><?= escapar($produto['nome_artista']) ?></p>
                <h1 class="detalhe__nome"><?= escapar($produto['nome']) ?></h1>
                <p class="detalhe__preco"><?= valorMoeda((float) $produto['preco']) ?></p>

                <p>
                    <?php if ($produto['estoque'] > 0): ?>
                        <span class="tag tag--ok"><?= (int) $produto['estoque'] ?> em estoque</span>
                    <?php else: ?>
                        <span class="tag tag--fim">Esgotado</span>
                    <?php endif; ?>
                    <?php if ($produto['cor'] || $produto['tamanho']): ?>
                        <span class="tag tag--vazio"><?= escapar(trim(($produto['cor'] ?? '') . ' · ' . ($produto['tamanho'] ?? ''), ' ·')) ?></span>
                    <?php endif; ?>
                </p>

                <div class="detalhe__acoes">
                    <?php if ($produto['estoque'] > 0): ?>
                        <form method="post"><button class="btn btn--primario" name="carrinho"><?= icone('sacola') ?> Adicionar ao carrinho</button></form>
                    <?php endif; ?>
                    <form method="post"><button class="btn--texto" name="favorito"><?= icone('coracao') ?> Salvar favorito</button></form>
                </div>

                <p class="detalhe__desc"><?= nl2br(escapar($produto['descricao'])) ?></p>
            </div>
        </article>

        <section class="avaliacoes">
            <h2 class="secao-titulo">
                Avaliações
                <?php if ($mediaNota !== null): ?><span class="tag"><?= $mediaNota ?> / 5 · <?= count($avaliacoes) ?></span><?php endif; ?>
            </h2>

            <?php if (estaLogado()): ?>
                <form method="post" class="form-card mb-6">
                    <label>Nota
                        <select name="nota">
                            <option>5</option><option>4</option><option>3</option><option>2</option><option>1</option>
                        </select>
                    </label>
                    <label>Comentário<textarea required name="comentario" placeholder="O que você achou?"></textarea></label>
                    <button class="btn btn--primario">Publicar avaliação</button>
                </form>
            <?php else: ?>
                <p class="msg msg--aviso"><a href="login.php">Entre</a> para avaliar este produto.</p>
            <?php endif; ?>

            <?php if (!$avaliacoes): ?>
                <p class="detalhe__desc">Ainda não há avaliações. Seja a primeira pessoa a avaliar.</p>
            <?php else: ?>
                <?php foreach ($avaliacoes as $a): ?>
                    <article class="avaliacao">
                        <p><strong><?= escapar($a['nome']) ?></strong> <span class="nota" aria-label="<?= (int) $a['nota'] ?> de 5"><?= str_repeat('★', (int) $a['nota']) . str_repeat('☆', 5 - (int) $a['nota']) ?></span></p>
                        <p><?= escapar($a['comentario']) ?></p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>
<?php require 'includes/footer.php'; ?>