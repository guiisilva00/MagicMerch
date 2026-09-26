<?php require_once __DIR__ . '/config/app.php';
exigirLogin();
$uid = (int) usuarioAtual()['id'];
$tituloPagina = 'Finalização da compra';
$confirmacao = isset($_GET['confirmacao']) && (int) ($_SESSION['pedido_confirmado'] ?? 0) === (int) $_GET['confirmacao'];
if ($confirmacao) {
    $pedidoConfirmado = (int) $_GET['confirmacao'];
    unset($_SESSION['pedido_confirmado']);
    require 'includes/header.php'; ?>
    <main class="container pagina">
        <section class="vazio confirmacao-pedido" aria-labelledby="titulo-confirmacao">
            <span class="vazio__inicial" aria-hidden="true">✓</span>
            <h1 id="titulo-confirmacao">Pedido confirmado!</h1>
            <p>Seu pedido <strong>#<?= $pedidoConfirmado ?></strong> foi registrado com sucesso.</p>
            <a class="btn btn--primario" href="perfil.php?pedido=<?= $pedidoConfirmado ?>">Ver meus pedidos</a>
        </section>
    </main>
    <?php require 'includes/footer.php'; exit;
}

$itens = itensCarrinho($pdo, $uid);
if (!$itens) {
    mensagemFlash('erro', 'Seu carrinho está vazio.');
    redirecionar('carrinho.php');
}
$enderecos = readAll($pdo, 'enderecos', 'usuario_id = ? ORDER BY principal DESC', [$uid]);
$erro = '';
$modalidadeAtual = $_POST['modalidade'] ?? 'entrega';
$enderecoAtual = (int) ($_POST['endereco_id'] ?? ($enderecos[0]['id'] ?? 0));
$pagamentoAtual = $_POST['pagamento'] ?? 'pix';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modalidade = $_POST['modalidade'] ?? '';
    $pagamento = $_POST['pagamento'] ?? '';
    $endId = (int) ($_POST['endereco_id'] ?? 0);
    $endereco = null;
    if (!in_array($modalidade, ['entrega', 'retirada'], true)) {
        $erro = 'Selecione uma modalidade de entrega.';
    } elseif (!in_array($pagamento, ['pix', 'cartao'], true)) {
        $erro = 'Selecione uma forma de pagamento.';
    } elseif ($modalidade === 'entrega') {
        $endereco = read($pdo, 'enderecos', 'id = ? AND usuario_id = ?', [$endId, $uid]);
        if (!$endereco) $erro = 'Selecione um endereço de entrega.';
    }
    if (!$erro && !isset($_POST['atualizar_resumo'])) {
        $produtos = indexarPorId(readAll($pdo, 'produtos'));
        foreach ($itens as $i) {
            if ((int) ($produtos[$i['produto_id']]['estoque'] ?? 0) < (int) $i['quantidade']) {
                $erro = 'Estoque insuficiente para ' . $i['nome'];
                break;
            }
        }
    }
    if (!$erro && !isset($_POST['atualizar_resumo'])) {
        $frete = calcularFrete($modalidade, $endereco['estado'] ?? '');
        $sub = subtotalCarrinho($itens);
        $desconto = $pagamento === 'pix' ? round($sub * 0.05, 2) : 0;
        $texto = $endereco ? implode(', ', [$endereco['logradouro'], $endereco['numero'], $endereco['bairro'], $endereco['cidade'] . '/' . $endereco['estado']]) : 'Retirada na loja';
        $pedido = (int) create($pdo, 'pedidos', [
            'usuario_id' => $uid,
            'valor_total' => $sub - $desconto + $frete,
            'status' => 'pagamento_confirmado',
            'modalidade_entrega' => $modalidade,
            'endereco' => $texto,
            'frete' => $frete,
            'forma_pagamento' => $pagamento,
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
        $_SESSION['pedido_confirmado'] = $pedido;
        redirecionar('checkout.php?confirmacao=' . $pedido);
    }
}
$subtotal = subtotalCarrinho($itens);
$enderecoResumo = null;
if ($modalidadeAtual === 'entrega' && $enderecoAtual) {
    $enderecoResumo = read($pdo, 'enderecos', 'id = ? AND usuario_id = ?', [$enderecoAtual, $uid]);
}
$freteResumo = $modalidadeAtual === 'entrega' && !$enderecoResumo
    ? 0
    : calcularFrete($modalidadeAtual, $enderecoResumo['estado'] ?? '');
$descontoResumo = $pagamentoAtual === 'pix' ? round($subtotal * 0.05, 2) : 0;
require 'includes/header.php'; ?>
<main class="container pagina">
    <header class="cabecalho-pagina"><h1>Finalização da compra</h1></header>
    <?php if ($erro): ?><p class="msg msg--erro"><?= escapar($erro) ?></p><?php endif; ?>
    <form method="post" class="checkout carrinho" id="form-checkout">
        <div class="stack">
            <fieldset class="grupo">
                <legend class="grupo__titulo">Entrega</legend>
                <label class="campo"><span>Modalidade</span>
                    <select name="modalidade" id="modalidade">
                        <option value="entrega" <?= $modalidadeAtual === 'entrega' ? 'selected' : '' ?>>Entrega</option>
                        <option value="retirada" <?= $modalidadeAtual === 'retirada' ? 'selected' : '' ?>>Retirada na loja — frete R$ 0,00</option>
                    </select>
                </label>
                <label class="campo"><span>Endereço</span>
                    <select name="endereco_id" <?= $modalidadeAtual === 'retirada' ? 'disabled' : '' ?>>
                        <option value="">Selecione</option>
                        <?php foreach ($enderecos as $e): ?>
                            <option value="<?= (int) $e['id'] ?>" data-estado="<?= escapar(strtoupper($e['estado'])) ?>" <?= $enderecoAtual === (int) $e['id'] ? 'selected' : '' ?>><?= escapar($e['apelido'] . ' — ' . $e['cidade'] . '/' . $e['estado']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <?php if (!$enderecos): ?><p class="txt-sec">Cadastre um endereço no <a class="btn--texto" href="perfil.php">perfil</a> para receber em casa. Para retirada não é necessário.</p><?php endif; ?>
            </fieldset>
            <fieldset class="grupo">
                <legend class="grupo__titulo">Pagamento</legend>
                <span class="selo"><?= icone('check') ?> Pagamento simulado — nenhuma cobrança real</span>
                <label class="campo"><span>Forma</span>
                    <select name="pagamento" id="pagamento">
                        <option value="pix" <?= $pagamentoAtual === 'pix' ? 'selected' : '' ?>>Pix — 5% de desconto nos produtos</option>
                        <option value="cartao" <?= $pagamentoAtual === 'cartao' ? 'selected' : '' ?>>Cartão</option>
                    </select>
                </label>
            </fieldset>
            <button class="btn btn--linha" name="atualizar_resumo" value="1">Atualizar resumo</button>
            <button class="btn btn--primario">Confirmar pedido</button>
        </div>
        <aside class="resumo">
            <h2>Resumo</h2>
            <?php foreach ($itens as $i): ?>
                <div class="resumo__linha"><span><?= escapar($i['nome']) ?> × <?= (int) $i['quantidade'] ?></span><span><?= valorMoeda((float) $i['preco'] * (int) $i['quantidade']) ?></span></div>
            <?php endforeach; ?>
            <div class="resumo__linha"><span>Produtos</span><span><?= valorMoeda($subtotal) ?></span></div>
            <?php if ($descontoResumo > 0): ?><div class="resumo__linha"><span>Desconto Pix (5%)</span><span>-<?= valorMoeda($descontoResumo) ?></span></div><?php endif; ?>
            <div class="resumo__linha"><span>Frete</span><span><?= $modalidadeAtual === 'entrega' && !$enderecoResumo ? 'Selecione um endereço' : valorMoeda($freteResumo) ?></span></div>
            <div class="resumo__total"><span>Total</span><b><?= valorMoeda($subtotal - $descontoResumo + $freteResumo) ?></b></div>
        </aside>
    </form>
</main>
<?php require 'includes/footer.php'; ?>
