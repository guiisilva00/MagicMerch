<?php require_once __DIR__ . '/config/app.php';
exigirLogin();
$uid = (int) usuarioAtual()['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = (int) $_POST['produto_id'];
    if (isset($_POST['remover'])) {
        delete($pdo, 'carrinho', 'usuario_id = ? AND produto_id = ?', [$uid, $pid]);
    } else {
        $q = max(1, (int) $_POST['quantidade']);
        update($pdo, 'carrinho', ['quantidade' => $q], 'usuario_id = ? AND produto_id = ?', [$uid, $pid]);
    }
    redirecionar('carrinho.php');
}
$itens = itensCarrinho($pdo, $uid);
$subtotal = subtotalCarrinho($itens);
$tituloPagina = 'Carrinho';
require 'includes/header.php'; ?>
<main class="container pagina">
    <header class="cabecalho-pagina">
        <h1>Carrinho de compras</h1>
    </header>

    <?php if (!$itens): ?>
        <div class="vazio">
            <span class="vazio__inicial" aria-hidden="true">▢</span>
            <h2>Seu carrinho está vazio</h2>
            <p>Explore o catálogo e volte para finalizar a compra.</p>
            <a class="btn btn--primario" href="produtos.php">Ver produtos</a>
        </div>
    <?php else: ?>
        <div class="carrinho">
            <section>
                <?php foreach ($itens as $i): ?>
                    <form method="post" class="item">
                        <span class="mini-poster poster--c<?= acentoPoster($i['nome']) ?>" aria-hidden="true"><?= escapar(inicial($i['nome'])) ?></span>
                        <input type="hidden" name="produto_id" value="<?= $i['produto_id'] ?>">
                        <div class="item__info">
                            <p class="item__nome"><?= escapar($i['nome']) ?></p>
                            <p class="item__artista"><?= valorMoeda((float) $i['preco']) ?> a unidade</p>
                            <div class="item__qtd">
                                <input name="quantidade" type="number" min="1" max="<?= (int) $i['estoque'] ?>" value="<?= (int) $i['quantidade'] ?>">
                                <button class="btn--texto">Atualizar</button>
                                <button class="btn--texto btn--texto--perigo" name="remover" value="1">Remover</button>
                            </div>
                        </div>
                        <span class="item__preco"><?= valorMoeda((float) $i['preco'] * (int) $i['quantidade']) ?></span>
                    </form>
                <?php endforeach; ?>
            </section>

            <aside class="resumo">
                <h2>Resumo</h2>
                <div class="resumo__linha"><span>Itens</span><span><?= array_sum(array_column($itens, 'quantidade')) ?></span></div>
                <div class="resumo__linha"><span>Frete</span><span>calculado no checkout</span></div>
                <div class="resumo__total"><span>Subtotal</span><b><?= valorMoeda($subtotal) ?></b></div>
                <a class="btn btn--primario btn--bloco" href="checkout.php">Finalizar compra</a>
            </aside>
        </div>

        <div class="barra-total">
            <span>Subtotal <b><?= valorMoeda($subtotal) ?></b></span>
            <a class="btn btn--primario" href="checkout.php">Finalizar</a>
        </div>
    <?php endif; ?>
</main>
<?php require 'includes/footer.php'; ?>