<?php
require_once __DIR__ . '/config/app.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pdo === null) {
        $erro = 'Não foi possível concluir a operação. Verifique a importação do banco.';
    } else {
        if (($_POST['acao'] ?? '') === 'cadastrar') {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $senha = $_POST['senha'] ?? '';
            $telefone = trim($_POST['telefone'] ?? '');

            if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
                $erro = 'Informe nome, e-mail válido e senha com ao menos 8 caracteres.';
            } elseif (read($pdo, 'usuarios', 'email = ?', [$email])) {
                $erro = 'Este e-mail já possui cadastro.';
            } else {
                create($pdo, 'usuarios', [
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => password_hash($senha, PASSWORD_BCRYPT),
                    'telefone' => $telefone,
                ]);
                mensagemFlash('sucesso', 'Cadastro realizado. Faça seu login.');
                redirecionar('login.php');
            }
        }
    }
}
$tituloPagina = 'Cadastro';
require 'includes/header.php'; ?>
<main class="split">
    <aside class="split__marca">
        <img src="assets/img/logo/logo.svg" alt="MagicMerch">
        <p class="split__frase">Junte-se à nossa comunidade.</p>
        <p style="opacity:.85">Crie sua conta para acompanhar pedidos, salvar favoritos e participar do programa de fidelidade.</p>
    </aside>

    <div class="split__form">
        <div class="split__form-inner">
            <h1>Criar conta</h1>
            <?php if ($erro): ?><p class="msg msg--erro"><?= escapar($erro) ?></p><?php endif; ?>

            <form method="post" class="stack">
                <input type="hidden" name="acao" value="cadastrar">
                <label class="campo"><span>Nome completo</span><input required name="nome" value="<?= escapar($_POST['nome'] ?? '') ?>"></label>
                <label class="campo"><span>E-mail</span><input required type="email" name="email" value="<?= escapar($_POST['email'] ?? '') ?>"></label>
                <label class="campo"><span>Telefone (opcional)</span><input name="telefone" value="<?= escapar($_POST['telefone'] ?? '') ?>"></label>
                <label class="campo"><span>Senha (mín. 8 caracteres)</span><input required minlength="8" type="password" name="senha"></label>
                <button class="btn btn--primario btn--bloco">Criar conta</button>
            </form>

            <div class="mt-4 stack" style="gap: var(--s-2);">
                <p>Já tem uma conta? <a href="login.php" class="btn--texto">Fazer login</a></p>
            </div>
        </div>
    </div>
</main>
<?php require 'includes/footer.php'; ?>
