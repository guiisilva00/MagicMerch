<?php
require_once __DIR__ . '/config/app.php';

$erro = '';
$etapa = 'email'; // etapa atual do formulário: 'email' ou 'senha'
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pdo === null) {
        $erro = 'Não foi possível concluir a operação. Verifique a importação do banco.';
    } else {
        $acao = $_POST['acao'] ?? '';
        $email = trim($_POST['email'] ?? '');

        if ($acao === 'verificar_email') {
            if (!read($pdo, 'usuarios', 'email = ?', [$email])) {
                $erro = 'E-mail não encontrado.';
            } else {
                $etapa = 'senha';
            }
        } elseif ($acao === 'alterar_senha') {
            $etapa = 'senha';
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmarSenha = $_POST['confirmar_senha'] ?? '';

            if (!read($pdo, 'usuarios', 'email = ?', [$email])) {
                $erro = 'E-mail não encontrado.';
                $etapa = 'email';
            } elseif (strlen($novaSenha) < 8) {
                $erro = 'A nova senha deve ter ao menos 8 caracteres.';
            } elseif ($novaSenha !== $confirmarSenha) {
                $erro = 'As senhas não coincidem.';
            } else {
                update($pdo, 'usuarios', ['senha' => password_hash($novaSenha, PASSWORD_BCRYPT)], 'email = ?', [$email]);
                mensagemFlash('sucesso', 'Senha alterada com sucesso. Faça login com a nova senha.');
                redirecionar('login.php');
            }
        }
    }
}
$tituloPagina = 'Editar Senha';
require 'includes/header.php'; ?>
<main class="split">
    <aside class="split__marca">
        <img src="assets/img/logo/logo.svg" alt="MagicMerch">
        <p class="split__frase">Recuperação de acesso.</p>
        <p style="opacity:.85">Informe seu e-mail cadastrado para definir uma nova senha.</p>
    </aside>

    <div class="split__form">
        <div class="split__form-inner">
            <h1>Editar senha</h1>
            <?php if ($erro): ?><p class="msg msg--erro"><?= escapar($erro) ?></p><?php endif; ?>

            <?php if ($etapa === 'email'): ?>
                <form method="post" class="stack">
                    <input type="hidden" name="acao" value="verificar_email">
                    <label class="campo"><span>Seu e-mail cadastrado</span><input required type="email" name="email" value="<?= escapar($email) ?>"></label>
                    <button class="btn btn--primario btn--bloco">Continuar</button>
                </form>
            <?php else: ?>
                <form method="post" class="stack">
                    <input type="hidden" name="acao" value="alterar_senha">
                    <input type="hidden" name="email" value="<?= escapar($email) ?>">
                    <p>E-mail: <strong><?= escapar($email) ?></strong></p>
                    <label class="campo"><span>Nova senha (mín. 8 caracteres)</span><input required minlength="8" type="password" name="nova_senha"></label>
                    <label class="campo"><span>Confirmar nova senha</span><input required minlength="8" type="password" name="confirmar_senha"></label>
                    <button class="btn btn--primario btn--bloco">Alterar senha</button>
                </form>
            <?php endif; ?>

            <div class="mt-4 stack" style="gap: var(--s-2);">
                <p>Lembrou da senha? <a href="login.php" class="btn--texto">Voltar para o login</a></p>
                <p>Ainda não tem conta? <a href="cadastro.php" class="btn--texto">Criar conta</a></p>
            </div>
        </div>
    </div>
</main>
<?php require 'includes/footer.php'; ?>
