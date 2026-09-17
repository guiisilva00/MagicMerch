<?php
require_once __DIR__ . '/config/app.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pdo === null) {
        $erro = 'Não foi possível concluir a operação. Verifique a importação do banco.';
    } else {
        if (($_POST['acao'] ?? '') === 'recuperar') {
            $email = trim($_POST['email'] ?? '');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erro = 'Informe um e-mail válido.';
            } else {
                mensagemFlash('sucesso', 'Se o e-mail estiver cadastrado, as instruções de redefinição de senha foram enviadas.');
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
        <p style="opacity:.85">Informe seu e-mail cadastrado para redefinir ou editar sua senha de acesso.</p>
    </aside>

    <div class="split__form">
        <div class="split__form-inner">
            <h1>Editar / Recuperar Senha</h1>
            <?php if ($erro): ?><p class="msg msg--erro"><?= escapar($erro) ?></p><?php endif; ?>

            <form method="post" class="stack">
                <input type="hidden" name="acao" value="recuperar">
                <label class="campo"><span>Seu e-mail cadastrado</span><input required type="email" name="email" value="<?= escapar($_POST['email'] ?? '') ?>"></label>
                <button class="btn btn--primario btn--bloco">Enviar instruções</button>
            </form>

            <div class="mt-4 stack" style="gap: var(--s-2);">
                <p>Lembrou da senha? <a href="login.php" class="btn--texto">Voltar para o login</a></p>
                <p>Ainda não tem conta? <a href="cadastro.php" class="btn--texto">Criar conta</a></p>
            </div>
        </div>
    </div>
</main>
<?php require 'includes/footer.php'; ?>
