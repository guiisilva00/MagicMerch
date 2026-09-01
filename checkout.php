<?php require_once __DIR__ . '/config/app.php';
exigirLogin();
$db = criarConexaoBancoDados();
$uid = (int) usuarioAtual()['id'];
$itens = itensCarrinho($db, $uid);
if (!$itens) {
    mensagemFlash('erro', 'Seu carrinho está vazio.');
    redirecionar('carrinho.php');
}
$enderecos = $db->prepare('SELECT * FROM enderecos WHERE usuario_id=? ORDER BY principal DESC');
$enderecos->execute([$uid]);
$enderecos = $enderecos->fetchAll();
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modalidade = $_POST['modalidade'];
    $endId = (int) ($_POST['endereco_id'] ?? 0);
    $endereco = null;
    if ($modalidade === 'entrega') {
        $s = $db->prepare('SELECT * FROM enderecos WHERE id=? AND usuario_id=?');
        $s->execute([$endId, $uid]);
        $endereco = $s->fetch();
        if (!$endereco)
            $erro = 'Selecione um endereço de entrega.';
    }
    if (!$erro) {
        $frete = calcularFrete($modalidade, $endereco['estado'] ?? '');
        $sub = subtotalCarrinho($itens);
        try {
            $db->beginTransaction();
            foreach ($itens as $i) {
                $s = $db->prepare('SELECT estoque FROM produtos WHERE id=? FOR UPDATE');
                $s->execute([$i['produto_id']]);
                if ((int) $s->fetchColumn() < (int) $i['quantidade'])
                    throw new RuntimeException('Estoque insuficiente para ' . $i['nome']);
            }
            $texto = $endereco ? implode(', ', [$endereco['logradouro'], $endereco['numero'], $endereco['bairro'], $endereco['cidade'] . '/' . $endereco['estado']]) : 'Retirada na loja';
            $s = $db->prepare('INSERT INTO pedidos(usuario_id,valor_total,status,modalidade_entrega,endereco,frete,forma_pagamento,pagamento_confirmado) VALUES(?,?,"pagamento_confirmado",?,?,?,?,1)');
            $s->execute([$uid, $sub + $frete, $modalidade, $texto, $frete, $_POST['pagamento']]);
            $pedido = (int) $db->lastInsertId();
            foreach ($itens as $i) {
                $db->prepare('INSERT INTO itens_pedido(pedido_id,produto_id,quantidade,preco_unitario) VALUES(?,?,?,?)')->execute([$pedido, $i['produto_id'], $i['quantidade'], $i['preco']]);
                $db->prepare('UPDATE produtos SET estoque=estoque-?,vendas=vendas+? WHERE id=?')->execute([$i['quantidade'], $i['quantidade'], $i['produto_id']]);
            }
            $db->prepare('DELETE FROM carrinho WHERE usuario_id=?')->execute([$uid]);
            $db->commit();
            mensagemFlash('sucesso', 'Pedido #' . $pedido . ' confirmado com pagamento simulado.');
            redirecionar('perfil.php');
        } catch (Throwable $e) {
            if ($db->inTransaction())
                $db->rollBack();
            $erro = $e->getMessage();
        }
    }
}
$tituloPagina = 'Finalização da compra';
require 'includes/header.php'; ?>
<main class="container pagina-conteudo">
    <h1>Finalização da compra</h1><?php if ($erro): ?>
        <p class="mensagem-erro"><?= escapar($erro) ?></p><?php endif; ?>
    <form method="post" class="duas-colunas">
        <section class="card-form">
            <h2>Entrega e pagamento</h2><label>Modalidade<select name="modalidade">
                    <option value="entrega">Entrega</option>
                    <option value="retirada">Retirada na loja — R$ 0,00</option>
                </select></label><label>Endereço<select name="endereco_id">
                    <option value="">Selecione</option><?php foreach ($enderecos as $e): ?>
                        <option value="<?= $e['id'] ?>"><?= escapar($e['apelido'] . ' — ' . $e['cidade'] . '/' . $e['estado']) ?>
                        </option><?php endforeach; ?>
                </select></label><?php if (!$enderecos): ?>
                <p>Cadastre um endereço no <a href="perfil.php">perfil</a> para receber em casa.</p>
            <?php endif; ?><label>Pagamento<select name="pagamento">
                    <option value="pix">Pix (simulado)</option>
                    <option value="cartao">Cartão (simulado)</option>
                </select></label><button class="btn-primary">Confirmar pedido</button>
        </section>
        <aside class="card-form">
            <h2>Resumo</h2><?php foreach ($itens as $i): ?>
                <p><?= escapar($i['nome']) ?> × <?= $i['quantidade'] ?></p><?php endforeach; ?>
            <p>Subtotal: <?= valorMoeda(subtotalCarrinho($itens)) ?></p>
            <p>Frete será calculado conforme a modalidade.</p>
        </aside>
    </form>
</main><?php require 'includes/footer.php'; ?>