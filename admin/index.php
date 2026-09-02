<?php
$tituloPaginaAdmin = 'Visão geral';
require __DIR__ . '/../includes/cabecalho-admin.php';

$pedidos = readAll($pdo, 'pedidos');
$pedidosPagos = array_filter($pedidos, fn($p) => (int) $p['pagamento_confirmado'] === 1);
$faturamento = array_sum(array_column($pedidosPagos, 'valor_total'));
$itensVendidos = array_sum(array_column(readAll($pdo, 'itens_pedido'), 'quantidade'));
?>
<h1>Visão geral</h1>
<div class="cards-admin">
    <article><span>Faturamento</span><strong><?= valorMoeda((float) $faturamento) ?></strong></article>
    <article><span>Pedidos</span><strong><?= count($pedidosPagos) ?></strong></article>
    <article><span>Itens vendidos</span><strong><?= (int) $itensVendidos ?></strong></article>
</div>
<?php require __DIR__ . '/../includes/rodape-admin.php'; ?>
