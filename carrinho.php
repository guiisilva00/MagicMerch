<?php require_once __DIR__ . '/config/app.php';
exigirLogin();
$db = criarConexaoBancoDados();
$uid = (int) usuarioAtual()['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remover'])) {
        $db->prepare('DELETE FROM carrinho WHERE usuario_id=? AND produto_id=?')->execute([$uid, (int) $_POST['produto_id']]);
    } else {
        $q = max(1, (int) $_POST['quantidade']);
        $db->prepare('UPDATE carrinho SET quantidade=? WHERE usuario_id=? AND produto_id=?')->execute([$q, $uid, (int) $_POST['produto_id']]);
    }
    redirecionar('carrinho.php');
}
$itens = itensCarrinho($db, $uid);
$subtotal = subtotalCarrinho($itens);
$tituloPagina = 'Carrinho';
require 'includes/header.php'; ?>
<main class="container pagina-conteudo">
    <h1>Carrinho de compras</h1><?php if (!$itens): ?>
        <p class="mensagem-aviso">Seu carrinho está vazio.</p><a class="btn-primary" href="produtos.php">Ver
            produtos</a><?php else: ?>
        <div class="duas-colunas">
            <section><?php foreach ($itens as $i): ?>
                    <form method="post" class="linha-item">
                        <div><strong><?= escapar($i['nome']) ?></strong><br><?= valorMoeda((float) $i['preco']) ?></div><input
                            type="hidden" name="produto_id" value="<?= $i['produto_id'] ?>"><input name="quantidade" type="number"
                            min="1" max="<?= $i['estoque'] ?>" value="<?= $i['quantidade'] ?>"><button>Atualizar</button><button
                            name="remover" value="1">Remover</button>
                    </form><?php endforeach; ?>
            </section>
            <aside class="card-form">
                <h2>Resumo</h2>
                <p>Subtotal: <strong><?= valorMoeda($subtotal) ?></strong></p><a class="btn-primary"
                    href="checkout.php">Finalizar compra</a>
            </aside>
        </div><?php endif; ?>
</main><?php require 'includes/footer.php'; ?>