<?php
require_once __DIR__ . '/../config/app.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($pdo === null) {
        $erro = 'Banco de dados indisponível.';
    } else {
        $u = read($pdo, 'usuarios', 'email = ? AND tipo = ?', [trim($_POST['email']), 'administrador']);
        if ($u && password_verify($_POST['senha'], $u['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id' => $u['id'], 'nome' => $u['nome'], 'email' => $u['email'], 'tipo' => $u['tipo']];
            redirecionar('index.php');
        }
        $erro = 'Credenciais administrativas inválidas.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <title>Administração MagicMerch</title>
</head>
<body class="pagina-admin">
    <main class="conteudo-admin">
        <form method="post" class="form-admin">
            <h1>Acesso administrativo</h1>
            <?php if ($erro): ?><p class="erro"><?= escapar($erro) ?></p><?php endif; ?>
            <label>E-mail<input type="email" name="email" required></label>
            <label>Senha<input type="password" name="senha" required></label>
            <button>Entrar</button>
            <p>Demo: admin@magicmerch.local</p>
        </form>
    </main>
</body>
</html>
