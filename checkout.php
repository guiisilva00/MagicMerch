<?php require_once __DIR__ . '/config/app.php';
exigirLogin();
$uid = (int) usuarioAtual()['id'];
$itens = itensCarrinho($pdo, $uid);
if (!$itens) {
    mensagemFlash('erro', 'Seu carrinho está vazio.');
    redirecionar('carrinho.php');
}
$enderecos = readAll($pdo, 'enderecos', 'usuario_id = ? ORDER BY principal DESC', [$uid]);
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modalidade = $_POST['modalidade'];
    $endId = (int) ($_POST['endereco_id'] ?? 0);
    $endereco = null;
    if ($modalidade === 'entrega') {
        $endereco = read($pdo, 'enderecos', 'id = ? AND usuario_id = ?', [$endId, $uid]);
        if (!$endereco)
            $erro = 'Selecione um endereço de entrega.';
    }
    // Confere o estoque em PHP antes de gravar. Sem transação: o CRUD do projeto
    // não expõe controle transacional; adequado ao escopo acadêmico.
    if (!$erro) {
        $produtos = indexarPorId(readAll($pdo, 'produtos'));
        foreach ($itens as $i) {
            if ((int) ($produtos[$i['produto_id']]['estoque'] ?? 0) < (int) $i['quantidade']) {
                $erro = 'Estoque insuficiente para ' . $i['nome'];
                break;
            }
        }
    }
    if (!$erro) {
        $frete = calcularFrete($modalidade, $endereco['estado'] ?? '');
        $sub = subtotalCarrinho($itens);
        $texto = $endereco ? implode(', ', [$endereco['logradouro'], $endereco['numero'], $endereco['bairro'], $endereco['cidade'] . '/' . $endereco['estado']]) : 'Retirada na loja';
        $pedido = (int) create($pdo, 'pedidos', [
            'usuario_id' => $uid,
            'valor_total' => $sub + $frete,
            'status' => 'pagamento_confirmado',
            'modalidade_entrega' => $modalidade,
            'endereco' => $texto,
            'frete' => $frete,
            'forma_pagamento' => $_POST['pagamento'],
            'pagamento_confirmado' => 1,
        ]);
        foreach ($itens as $i) {
            create($pdo, 'itens_pedido', [
                'pedido_id' => $pedido,
                'produto_id' => $i['produto_id'],
                'quantidade' => $i['quantidade'],
                'preco_unitario' => $i['preco'],
            ]);
            $produto = $produtos[$i['produto_id']];
            update($pdo, 'produtos', [
                'estoque' => (int) $produto['estoque'] - (int) $i['quantidade'],
                'vendas' => (int) $produto['vendas'] + (int) $i['quantidade'],
            ], 'id = ?', [$i['produto_id']]);
        }
        delete($pdo, 'carrinho', 'usuario_id = ?', [$uid]);
        mensagemFlash('sucesso', 'Pedido #' . $pedido . ' confirmado com pagamento simulado.');
        redirecionar('perfil.php');
    }
}
$tituloPagina = 'Finalização da compra';
require 'includes/header.php'; ?>
<main class="container pagina">
    <header class="cabecalho-pagina">
        <h1>Finalização da compra</h1>
    </header>

    <?php if ($erro): ?><p class="msg msg--erro"><?= escapar($erro) ?></p><?php endif; ?>

    <form method="post" class="checkout carrinho">
        <div class="stack">
            <fieldset class="grupo">
                <legend class="grupo__titulo">Entrega</legend>
                <label class="campo"><span>Modalidade</span>
                    <select name="modalidade">
                        <option value="entrega">Entrega</option>
                        <option value="retirada">Retirada na loja — frete R$ 0,00</option>
                    </select>
                </label>
                <label class="campo"><span>Endereço</span>
                    <select name="endereco_id">
                        <option value="">Selecione</option>
                        <?php foreach ($enderecos as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= escapar($e['apelido'] . ' — ' . $e['cidade'] . '/' . $e['estado']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <?php if (!$enderecos): ?>
                    <p class="txt-sec">Cadastre um endereço no <a class="btn--texto" href="perfil.php">perfil</a> para receber em casa. Para retirada não é necessário.</p>
                <?php endif; ?>
            </fieldset>

            <fieldset class="grupo">
                <legend class="grupo__titulo">Pagamento</legend>
                <span class="selo"><?= icone('check') ?> Pagamento simulado — nenhuma cobrança real</span>
                <label class="campo"><span>Forma</span>
                    <select name="pagamento">
                        <option value="pix">Pix</option>
                        <option value="cartao">Cartão</option>
                    </select>
                </label>
            </fieldset>

            <button class="btn btn--primario">Confirmar pedido</button>
        </div>

        <aside class="resumo">
            <h2>Resumo</h2>
            <?php foreach ($itens as $i): ?>
                <div class="resumo__linha">
                    <span><?= escapar($i['nome']) ?> × <?= (int) $i['quantidade'] ?></span>
                    <span><?= valorMoeda((float) $i['preco'] * (int) $i['quantidade']) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="resumo__linha"><span>Frete</span><span>conforme a modalidade</span></div>
            <div class="resumo__total"><span>Subtotal</span><b><?= valorMoeda(subtotalCarrinho($itens)) ?></b></div>
        </aside>
    </form>
</main>
<?php require 'includes/footer.php'; ?>