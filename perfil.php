<?php require_once __DIR__ . '/config/app.php';
exigirLogin();
$u = usuarioAtual();
$uid = (int) $u['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['acao'] === 'dados') {
        update($pdo, 'usuarios', ['nome' => trim($_POST['nome']), 'telefone' => trim($_POST['telefone'])], 'id = ?', [$uid]);
        $_SESSION['usuario']['nome'] = trim($_POST['nome']);
    } elseif ($_POST['acao'] === 'endereco') {
        create($pdo, 'enderecos', [
            'usuario_id' => $uid,
            'apelido' => trim($_POST['apelido']),
            'cep' => trim($_POST['cep']),
            'logradouro' => trim($_POST['logradouro']),
            'numero' => trim($_POST['numero']),
            'complemento' => trim($_POST['complemento']),
            'bairro' => trim($_POST['bairro']),
            'cidade' => trim($_POST['cidade']),
            'estado' => strtoupper(trim($_POST['estado'])),
            'principal' => 0,
        ]);
    } elseif ($_POST['acao'] === 'excluir') {
        delete($pdo, 'enderecos', 'id = ? AND usuario_id = ?', [(int) $_POST['id'], $uid]);
    }
    mensagemFlash('sucesso', 'Dados atualizados.');
    redirecionar('perfil.php');
}
$u = read($pdo, 'usuarios', 'id = ?', [$uid]);
$ends = readAll($pdo, 'enderecos', 'usuario_id = ?', [$uid]);
$pedidos = readAll($pdo, 'pedidos', 'usuario_id = ? ORDER BY data_pedido DESC', [$uid]);

// Itens comprados = soma das quantidades dos pedidos já pagos (join resolvido em PHP).
$pedidosPagos = array_column(array_filter($pedidos, fn($p) => $p['status'] !== 'aguardando_pagamento'), null, 'id');
$comprados = 0;
foreach (readAll($pdo, 'itens_pedido') as $item) {
    if (isset($pedidosPagos[$item['pedido_id']])) {
        $comprados += (int) $item['quantidade'];
    }
}
$tituloPagina = 'Minha conta';
$faltam = $comprados === 0 ? 10 : (10 - $comprados % 10) % 10;
$progresso = $comprados === 0 ? 0 : ($comprados % 10 ?: 10) * 10;
$classeStatus = fn($s) => $s === 'concluido' ? 'tag--ok' : ($s === 'aguardando_pagamento' ? 'tag--pend' : 'tag');
require 'includes/header.php'; ?>
<main class="container pagina">
    <header class="conta__cabecalho">
        <h1>Olá, <?= escapar($u['nome']) ?></h1>
        <p><?= escapar($u['email']) ?> · <a class="btn--texto" href="login.php?sair=1">Sair da conta</a></p>
    </header>

    <section class="conta__secao">
        <div class="fidelidade">
            <p class="fidelidade__num"><?= $comprados ?> <small>itens comprados</small></p>
            <p><?= $faltam === 0 ? 'Você tem um brinde disponível na próxima compra!' : "Faltam $faltam item(ns) para o próximo brinde." ?></p>
            <div class="fidelidade__barra"><span style="width: <?= $progresso ?>%"></span></div>
        </div>
    </section>

    <section class="conta__secao">
        <h2>Pedidos</h2>
        <?php if (!$pedidos): ?>
            <div class="vazio">
                <span class="vazio__inicial" aria-hidden="true">＋</span>
                <p>Você ainda não fez nenhum pedido.</p>
                <a class="btn btn--linha" href="produtos.php">Ver catálogo</a>
            </div>
        <?php else: ?>
            <?php foreach ($pedidos as $p): ?>
                <div class="pedido">
                    <span class="pedido__id">#<?= $p['id'] ?></span>
                    <span class="tag <?= $classeStatus($p['status']) ?>"><?= escapar(statusPedido()[$p['status']]) ?></span>
                    <span class="txt-sec"><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></span>
                    <span class="pedido__total"><?= valorMoeda((float) $p['valor_total']) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section class="conta__secao">
        <h2>Endereços</h2>
        <?php if ($ends): ?>
            <div class="enderecos-grid">
                <?php foreach ($ends as $e): ?>
                    <div class="endereco">
                        <strong><?= escapar($e['apelido']) ?></strong><br>
                        <?= escapar($e['logradouro'] . ', ' . $e['numero']) ?><br>
                        <?= escapar($e['bairro'] . ' · ' . $e['cidade'] . '/' . $e['estado']) ?>
                        <form method="post">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?= $e['id'] ?>">
                            <button class="btn--texto btn--texto--perigo">Excluir</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="msg msg--aviso">Nenhum endereço cadastrado ainda.</p>
        <?php endif; ?>

        <details class="acordeao mt-4">
            <summary>Adicionar endereço</summary>
            <form method="post" class="acordeao__corpo grid-form">
                <input type="hidden" name="acao" value="endereco">
                <input required name="apelido" placeholder="Apelido (casa, trabalho…)">
                <input required name="cep" placeholder="CEP">
                <input required name="logradouro" placeholder="Logradouro">
                <input required name="numero" placeholder="Número">
                <input name="complemento" placeholder="Complemento">
                <input required name="bairro" placeholder="Bairro">
                <input required name="cidade" placeholder="Cidade">
                <input required maxlength="2" name="estado" placeholder="UF">
                <button class="btn btn--linha">Adicionar endereço</button>
            </form>
        </details>
    </section>

    <section class="conta__secao">
        <h2>Dados pessoais</h2>
        <details class="acordeao">
            <summary>Editar nome e telefone</summary>
            <form method="post" class="acordeao__corpo stack">
                <input type="hidden" name="acao" value="dados">
                <label class="campo"><span>Nome</span><input name="nome" value="<?= escapar($u['nome']) ?>"></label>
                <label class="campo"><span>Telefone</span><input name="telefone" value="<?= escapar($u['telefone'] ?? '') ?>"></label>
                <button class="btn btn--primario">Salvar</button>
            </form>
        </details>
    </section>
</main>
<?php require 'includes/footer.php'; ?>