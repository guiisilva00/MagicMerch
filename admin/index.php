<?php
$tituloPaginaAdmin = 'Visão geral';
require __DIR__ . '/../includes/cabecalho-admin.php';

$pedidos = readAll($pdo, 'pedidos');
$faturamento = 0.0;
$totalPedidosPagos = 0;
foreach ($pedidos as $p) {
    if ((int) $p['pagamento_confirmado'] === 1) {
        $faturamento += (float) $p['valor_total'];
        $totalPedidosPagos++;
    }
}

$itensVendidos = 0;
foreach (readAll($pdo, 'itens_pedido') as $item) {
    $itensVendidos += (int) $item['quantidade'];
}
?>
<h1>Visão geral</h1>
<div class="cards-admin">
    <article><span>Faturamento</span><strong><?= valorMoeda((float) $faturamento) ?></strong></article>
    <article><span>Pedidos</span><strong><?= $totalPedidosPagos ?></strong></article>
    <article><span>Itens vendidos</span><strong><?= (int) $itensVendidos ?></strong></article>
</div>
<?php require __DIR__ . '/../includes/rodape-admin.php'; ?>
