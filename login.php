<?php
require_once __DIR__ . '/config/app.php';
if (isset($_GET['sair'])) {
    session_unset();
    session_destroy();
    session_start();
    mensagemFlash('sucesso', 'Sessão encerrada.');
    redirecionar('login.php');
}
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pdo === null) {
        $erro = 'Não foi possível concluir a operação. Verifique a importação do banco.';
    } else {
        if ($_POST['acao'] === 'entrar') {
            $u = read($pdo, 'usuarios', 'email = ?', [trim($_POST['email'])]);
            if ($u && password_verify($_POST['senha'], $u['senha'])) {
                session_regenerate_id(true);
                $_SESSION['usuario'] = ['id' => $u['id'], 'nome' => $u['nome'], 'email' => $u['email'], 'tipo' => $u['tipo']];
                redirecionar($u['tipo'] === 'administrador' ? 'admin/index.php' : ($_SESSION['retorno'] ?? 'perfil.php'));
            }
            $erro = 'E-mail ou senha inválidos.';
        }
    }
}
$tituloPagina = 'Acesso';
require 'includes/header.php'; ?>
<main class="split">
    <aside class="split__marca">
        <img src="assets/img/logo/logo.svg" alt="MagicMerch">
        <p class="split__frase">Merch feito à mão dos seus artistas.</p>
        <p style="opacity:.85">Entre para acompanhar pedidos, favoritos e o programa de fidelidade.</p>
    </aside>

    <div class="split__form">
        <div class="split__form-inner">
            <h1>Entrar</h1>
            <?php if ($erro): ?><p class="msg msg--erro"><?= escapar($erro) ?></p><?php endif; ?>

            <form method="post" class="stack">
                <input type="hidden" name="acao" value="entrar">
                <label class="campo"><span>E-mail</span><input required type="email" name="email"></label>
                <label class="campo"><span>Senha</span><input required type="password" name="senha"></label>
                <button class="btn btn--primario btn--bloco">Entrar</button>
            </form>

            <div class="mt-4 stack" style="gap: var(--s-2);">
                <p>Não possui uma conta? <a href="cadastro.php" class="btn--texto">Criar conta</a></p>
                <p>Esqueceu ou deseja alterar a senha? <a href="editar-senha.php" class="btn--texto">Editar / Recuperar senha</a></p>
            </div>
        </div>
    </div>
</main>
<?php require 'includes/footer.php'; ?>