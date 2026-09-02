<?php
$tituloPaginaAdmin = 'Gestão de produtos';
require __DIR__ . '/../includes/cabecalho-admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['excluir'])) {
        delete($pdo, 'produtos', 'id = ?', [(int) $_POST['id']]);
    } else {
        $dados = [
            'nome' => trim($_POST['nome']),
            'descricao' => trim($_POST['descricao']),
            'preco' => (float) $_POST['preco'],
            'artista_id' => (int) $_POST['artista_id'],
            'categoria' => trim($_POST['categoria']),
            'estoque' => (int) $_POST['estoque'],
            'imagem' => trim($_POST['imagem']),
            'cor' => trim($_POST['cor']),
            'tamanho' => trim($_POST['tamanho']),
            'destaque' => (int) isset($_POST['destaque']),
        ];
        if ((int) $_POST['id']) {
            update($pdo, 'produtos', $dados, 'id = ?', [(int) $_POST['id']]);
        } else {
            create($pdo, 'produtos', $dados);
        }
    }
    redirecionar('produtos.php');
}

$ed = isset($_GET['editar']) ? read($pdo, 'produtos', 'id = ?', [(int) $_GET['editar']]) : null;
$arts = readAll($pdo, 'artistas', '1 ORDER BY nome');
$prods = readAll($pdo, 'produtos', '1 ORDER BY id DESC');
$artistasPorId = indexarPorId($arts);
?>
<h1>Produtos</h1>
<form method="post" class="form-admin">
    <input type="hidden" name="id" value="<?= $ed['id'] ?? 0 ?>">
    <input required name="nome" placeholder="Nome" value="<?= escapar($ed['nome'] ?? '') ?>">
    <textarea required name="descricao" placeholder="Descrição"><?= escapar($ed['descricao'] ?? '') ?></textarea>
    <input required step="0.01" type="number" name="preco" placeholder="Preço" value="<?= $ed['preco'] ?? '' ?>">
    <select name="artista_id">
        <?php foreach ($arts as $a): ?>
            <option value="<?= $a['id'] ?>" <?= ($ed['artista_id'] ?? 0) == $a['id'] ? 'selected' : '' ?>><?= escapar($a['nome']) ?></option>
        <?php endforeach; ?>
    </select>
    <input required name="categoria" placeholder="Categoria" value="<?= escapar($ed['categoria'] ?? '') ?>">
    <input required type="number" name="estoque" placeholder="Estoque" value="<?= $ed['estoque'] ?? 0 ?>">
    <input name="imagem" placeholder="URL/caminho da imagem" value="<?= escapar($ed['imagem'] ?? '') ?>">
    <input name="cor" placeholder="Cor" value="<?= escapar($ed['cor'] ?? '') ?>">
    <input name="tamanho" placeholder="Tamanho" value="<?= escapar($ed['tamanho'] ?? '') ?>">
    <label><input type="checkbox" name="destaque" <?= !empty($ed['destaque']) ? 'checked' : '' ?>> Destaque</label>
    <button>Salvar produto</button>
</form>
<table>
    <tr><th>Produto</th><th>Artista</th><th>Preço</th><th></th></tr>
    <?php foreach ($prods as $p): ?>
        <tr>
            <td><?= escapar($p['nome']) ?></td>
            <td><?= escapar($artistasPorId[$p['artista_id']]['nome'] ?? '') ?></td>
            <td><?= valorMoeda((float) $p['preco']) ?></td>
            <td>
                <a href="?editar=<?= $p['id'] ?>">Editar</a>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button name="excluir">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/../includes/rodape-admin.php'; ?>
