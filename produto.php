<?php require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/crud.php';
$db = criarConexaoBancoDados();
$id = (int) ($_GET['id'] ?? 0);
$produto = buscarProdutoPorId($db, $id);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirLogin();
    $uid = (int) usuarioAtual()['id'];
    if (isset($_POST['carrinho'])) {
        $db->prepare('INSERT INTO carrinho(usuario_id,produto_id,quantidade) VALUES(?,?,1) ON DUPLICATE KEY UPDATE quantidade=quantidade+1')->execute([$uid, $id]);
        mensagemFlash('sucesso', 'Produto adicionado ao carrinho.');
    } elseif (isset($_POST['favorito'])) {
        $db->prepare('INSERT IGNORE INTO favoritos(usuario_id,produto_id) VALUES(?,?)')->execute([$uid, $id]);
        mensagemFlash('sucesso', 'Produto salvo nos favoritos.');
    } else {
        $db->prepare('INSERT INTO avaliacoes(usuario_id,produto_id,nota,comentario) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE nota=VALUES(nota),comentario=VALUES(comentario),data_avaliacao=CURRENT_TIMESTAMP')->execute([$uid, $id, (int) $_POST['nota'], trim($_POST['comentario'])]);
        mensagemFlash('sucesso', 'Avaliação publicada.');
    }
    redirecionar('produto.php?id=' . $id);
}
$avs = $produto ? $db->prepare('SELECT a.*,u.nome FROM avaliacoes a JOIN usuarios u ON u.id=a.usuario_id WHERE produto_id=? ORDER BY data_avaliacao DESC') : null;
if ($avs)
    $avs->execute([$id]);
$tituloPagina = 'Produto';
require 'includes/header.php'; ?>
<main class="container pagina-conteudo"><?php if (!$produto): ?>
        <p class="mensagem-aviso">Produto não encontrado.</p><?php else: ?>
        <article class="produto-detalhe">
            <div class="produto-detalhe-imagem"><?php if ($produto['imagem']): ?><img src="<?= escapar($produto['imagem']) ?>"
                        alt=""><?php else: ?><span>Imagem do produto</span><?php endif; ?></div>
            <div class="produto-detalhe-conteudo">
                <p><?= escapar($produto['nome_artista']) ?> · <?= escapar($produto['categoria']) ?></p>
                <h1><?= escapar($produto['nome']) ?></h1>
                <p class="produto-preco"><?= valorMoeda((float) $produto['preco']) ?></p>
                <p><?= nl2br(escapar($produto['descricao'])) ?></p>
                <p><?= $produto['estoque'] > 0 ? $produto['estoque'] . ' unidade(s) em estoque' : 'Esgotado' ?></p>
                <?php if ($produto['estoque'] > 0): ?>
                    <form method="post"><button class="btn-primary" name="carrinho">Adicionar ao carrinho</button></form>
                <?php endif; ?>
                <form method="post"><button name="favorito">Salvar favorito</button></form>
            </div>
        </article>
        <section>
            <h2>Avaliações</h2><?php if (estaLogado()): ?>
                <form method="post" class="card-form"><label>Nota<select name="nota">
                            <option>5</option>
                            <option>4</option>
                            <option>3</option>
                            <option>2</option>
                            <option>1</option>
                        </select></label><label>Comentário<textarea required name="comentario"></textarea></label><button
                        class="btn-primary">Avaliar</button></form><?php endif;
            foreach ($avs->fetchAll() as $a): ?>
                <article class="avaliacao"><strong><?= escapar($a['nome']) ?></strong> — <?= $a['nota'] ?>/5<p>
                        <?= escapar($a['comentario']) ?></p>
                </article><?php endforeach; ?>
        </section><?php endif; ?>
</main><?php require 'includes/footer.php'; ?>