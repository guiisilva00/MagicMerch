<?php require_once __DIR__ . '/config/app.php';
exigirLogin();
$db = criarConexaoBancoDados();
$u = usuarioAtual();
$uid = (int) $u['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['acao'] === 'dados') {
        $db->prepare('UPDATE usuarios SET nome=?,telefone=? WHERE id=?')->execute([trim($_POST['nome']), trim($_POST['telefone']), $uid]);
        $_SESSION['usuario']['nome'] = trim($_POST['nome']);
    } elseif ($_POST['acao'] === 'endereco') {
        $db->prepare('INSERT INTO enderecos(usuario_id,apelido,cep,logradouro,numero,complemento,bairro,cidade,estado,principal) VALUES(?,?,?,?,?,?,?,?,?,0)')->execute([$uid, trim($_POST['apelido']), trim($_POST['cep']), trim($_POST['logradouro']), trim($_POST['numero']), trim($_POST['complemento']), trim($_POST['bairro']), trim($_POST['cidade']), strtoupper(trim($_POST['estado']))]);
    } elseif ($_POST['acao'] === 'excluir') {
        $db->prepare('DELETE FROM enderecos WHERE id=? AND usuario_id=?')->execute([(int) $_POST['id'], $uid]);
    }
    mensagemFlash('sucesso', 'Dados atualizados.');
    redirecionar('perfil.php');
}
$s = $db->prepare('SELECT * FROM usuarios WHERE id=?');
$s->execute([$uid]);
$u = $s->fetch();
$s = $db->prepare('SELECT * FROM enderecos WHERE usuario_id=?');
$s->execute([$uid]);
$ends = $s->fetchAll();
$s = $db->prepare('SELECT * FROM pedidos WHERE usuario_id=? ORDER BY data_pedido DESC');
$s->execute([$uid]);
$pedidos = $s->fetchAll();
$s = $db->prepare('SELECT COALESCE(SUM(i.quantidade),0) FROM itens_pedido i JOIN pedidos p ON p.id=i.pedido_id WHERE p.usuario_id=? AND p.status<>"aguardando_pagamento"');
$s->execute([$uid]);
$comprados = (int) $s->fetchColumn();
$tituloPagina = 'Minha conta';
require 'includes/header.php'; ?>
<main class="container pagina-conteudo">
    <h1>Minha conta</h1>
    <div class="duas-colunas">
        <form method="post" class="card-form">
            <h2>Dados pessoais</h2><input type="hidden" name="acao" value="dados"><label>Nome<input name="nome"
                    value="<?= escapar($u['nome']) ?>"></label><label>Telefone<input name="telefone"
                    value="<?= escapar($u['telefone'] ?? '') ?>"></label>
            <p><?= escapar($u['email']) ?></p><button class="btn-primary">Salvar</button>
        </form>
        <section class="card-form">
            <h2>Fidelidade</h2>
            <p><?= $comprados ?> itens comprados. Faltam <?= 10 - ($comprados % 10 ?: 10) ?> item(ns) para o próximo brinde.</p>
        </section>
    </div>
    <section>
        <h2>Endereços</h2><?php foreach ($ends as $e): ?>
            <form method="post" class="linha-item">
                <?= escapar($e['apelido'] . ' — ' . $e['logradouro'] . ', ' . $e['numero'] . ' · ' . $e['cidade'] . '/' . $e['estado']) ?><input
                    type="hidden" name="acao" value="excluir"><input type="hidden" name="id"
                    value="<?= $e['id'] ?>"><button>Excluir</button></form><?php endforeach; ?>
        <form method="post" class="card-form grid-form"><input type="hidden" name="acao" value="endereco"><input
                required name="apelido" placeholder="Apelido"><input required name="cep" placeholder="CEP"><input
                required name="logradouro" placeholder="Logradouro"><input required name="numero"
                placeholder="Número"><input name="complemento" placeholder="Complemento"><input required name="bairro"
                placeholder="Bairro"><input required name="cidade" placeholder="Cidade"><input required maxlength="2"
                name="estado" placeholder="UF"><button class="btn-primary">Adicionar endereço</button></form>
    </section>
    <section>
        <h2>Pedidos</h2><?php foreach ($pedidos as $p): ?>
            <p class="linha-item">#<?= $p['id'] ?> — <?= valorMoeda((float) $p['valor_total']) ?> —
                <?= escapar(statusPedido()[$p['status']]) ?></p><?php endforeach; ?>
    </section>
    <p><a href="login.php?sair=1">Sair da conta</a></p>
</main><?php require 'includes/footer.php'; ?>