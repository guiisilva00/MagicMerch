<?php
$tituloPaginaAdmin = 'Gestão de estoque';
require __DIR__ . '/../includes/cabecalho-admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    update($pdo, 'produtos', ['estoque' => max(0, (int) $_POST['estoque'])], 'id = ?', [(int) $_POST['id']]);
    redirecionar('estoque.php');
}
$ps = readAll($pdo, 'produtos', '1 ORDER BY estoque, nome');
?>
<h1>Estoque</h1>
<p>Itens com até 5 unidades estão destacados.</p>
<table>
    <tr><th>Produto</th><th>Disponível</th><th>Ajuste</th></tr>
    <?php foreach ($ps as $p): ?>
        <tr class="<?= $p['estoque'] <= 5 ? 'baixo-estoque' : '' ?>">
            <td><?= escapar($p['nome']) ?></td>
            <td><?= $p['estoque'] ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <input type="number" min="0" name="estoque" value="<?= $p['estoque'] ?>">
                    <button>Salvar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../includes/rodape-admin.php'; ?>
