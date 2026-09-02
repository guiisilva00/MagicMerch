<?php
$tituloPaginaAdmin = 'Gestão de pedidos';
require __DIR__ . '/../includes/cabecalho-admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    update($pdo, 'pedidos', ['status' => $_POST['status']], 'id = ?', [(int) $_POST['id']]);
    redirecionar('pedidos.php');
}
$ps = readAll($pdo, 'pedidos', '1 ORDER BY data_pedido DESC');
$clientes = indexarPorId(readAll($pdo, 'usuarios'));
?>
<h1>Pedidos</h1>
<table>
    <tr><th>#</th><th>Cliente</th><th>Entrega/frete</th><th>Total</th><th>Status</th></tr>
    <?php foreach ($ps as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= escapar($clientes[$p['usuario_id']]['nome'] ?? '') ?></td>
            <td><?= escapar($p['modalidade_entrega']) ?> · <?= valorMoeda((float) $p['frete']) ?></td>
            <td><?= valorMoeda((float) $p['valor_total']) ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <select name="status">
                        <?php foreach (statusPedido() as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $k === $p['status'] ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button>Atualizar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../includes/rodape-admin.php'; ?>
