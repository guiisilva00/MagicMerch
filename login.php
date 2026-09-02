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
        } elseif ($_POST['acao'] === 'cadastrar') {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($_POST['senha']) < 8)
                $erro = 'Informe nome, e-mail válido e senha com ao menos 8 caracteres.';
            elseif (read($pdo, 'usuarios', 'email = ?', [$email]))
                $erro = 'Este e-mail já possui cadastro.';
            else {
                create($pdo, 'usuarios', [
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => password_hash($_POST['senha'], PASSWORD_BCRYPT),
                    'telefone' => trim($_POST['telefone']),
                ]);
                mensagemFlash('sucesso', 'Cadastro realizado. Faça seu login.');
                redirecionar('login.php');
            }
        } else {
            mensagemFlash('sucesso', 'Se o e-mail estiver cadastrado, as instruções de recuperação foram simuladas nesta tela.');
            redirecionar('login.php');
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

            <details class="acordeao">
                <summary>Criar uma conta</summary>
                <form method="post" class="acordeao__corpo stack">
                    <input type="hidden" name="acao" value="cadastrar">
                    <label class="campo"><span>Nome</span><input required name="nome"></label>
                    <label class="campo"><span>E-mail</span><input required type="email" name="email"></label>
                    <label class="campo"><span>Telefone</span><input name="telefone"></label>
                    <label class="campo"><span>Senha (mín. 8 caracteres)</span><input required minlength="8" type="password" name="senha"></label>
                    <button class="btn btn--linha btn--bloco">Criar conta</button>
                </form>
            </details>

            <details class="acordeao">
                <summary>Esqueci minha senha</summary>
                <form method="post" class="acordeao__corpo stack">
                    <input type="hidden" name="acao" value="recuperar">
                    <label class="campo"><span>E-mail</span><input required type="email" name="email"></label>
                    <button class="btn btn--texto">Simular recuperação</button>
                </form>
            </details>
        </div>
    </div>
</main>
<?php require 'includes/footer.php'; ?>