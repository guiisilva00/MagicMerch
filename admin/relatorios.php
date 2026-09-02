<?php
$tituloPaginaAdmin = 'Relatórios';
require __DIR__ . '/../includes/cabecalho-admin.php';

$ini = $_GET['inicio'] ?? date('Y-m-01');
$fim = $_GET['fim'] ?? date('Y-m-d');

// Pedidos do período (filtro simples na string $where; agregação feita em PHP).
$pedidos = readAll($pdo, 'pedidos', 'DATE(data_pedido) BETWEEN ? AND ?', [$ini, $fim]);
$faturamento = array_sum(array_column($pedidos, 'valor_total'));
$confirmados = array_sum(array_column($pedidos, 'pagamento_confirmado'));

// Mais vendidos: soma das quantidades por produto entre os pedidos do período.
$pedidosPorId = indexarPorId($pedidos);
$produtosPorId = indexarPorId(readAll($pdo, 'produtos'));
$vendasPorProduto = [];
foreach (readAll($pdo, 'itens_pedido') as $item) {
    if (!isset($pedidosPorId[$item['pedido_id']])) {
        continue;
    }
    $vendasPorProduto[$item['produto_id']] = ($vendasPorProduto[$item['produto_id']] ?? 0) + (int) $item['quantidade'];
}
arsort($vendasPorProduto);
$maisVendidos = array_slice($vendasPorProduto, 0, 10, true);
?>
<h1>Relatórios</h1>
<form method="get" class="form-admin">
    <input type="date" name="inicio" value="<?= escapar($ini) ?>">
    <input type="date" name="fim" value="<?= escapar($fim) ?>">
    <button>Filtrar</button>
</form>
<div class="cards-admin">
    <article><span>Faturamento</span><strong><?= valorMoeda((float) $faturamento) ?></strong></article>
    <article><span>Pedidos</span><strong><?= count($pedidos) ?></strong></article>
    <article><span>Pagamentos confirmados</span><strong><?= (int) $confirmados ?></strong></article>
</div>
<h2>Mais vendidos</h2>
<table>
    <tr><th>Produto</th><th>Unidades</th></tr>
    <?php foreach ($maisVendidos as $produtoId => $qtd): ?>
        <tr>
            <td><?= escapar($produtosPorId[$produtoId]['nome'] ?? '') ?></td>
            <td><?= $qtd ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../includes/rodape-admin.php'; ?>
