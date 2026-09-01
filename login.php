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
    try {
        $db = criarConexaoBancoDados();
        if ($_POST['acao'] === 'entrar') {
            $s = $db->prepare('SELECT * FROM usuarios WHERE email=?');
            $s->execute([trim($_POST['email'])]);
            $u = $s->fetch();
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
            else {
                $s = $db->prepare('INSERT INTO usuarios(nome,email,senha,telefone) VALUES(?,?,?,?)');
                $s->execute([$nome, $email, password_hash($_POST['senha'], PASSWORD_BCRYPT), trim($_POST['telefone'])]);
                mensagemFlash('sucesso', 'Cadastro realizado. Faça seu login.');
                redirecionar('login.php');
            }
        } else {
            mensagemFlash('sucesso', 'Se o e-mail estiver cadastrado, as instruções de recuperação foram simuladas nesta tela.');
            redirecionar('login.php');
        }
    } catch (PDOException $e) {
        $erro = 'Não foi possível concluir a operação. Verifique a importação do banco.';
    }
}
$tituloPagina = 'Acesso';
require 'includes/header.php'; ?>
<main class="container pagina-conteudo">
    <h1>Acesso</h1><?php if ($erro): ?>
        <p class="mensagem-erro"><?= escapar($erro) ?></p><?php endif; ?>
    <div class="duas-colunas">
        <form method="post" class="card-form">
            <h2>Entrar</h2><input type="hidden" name="acao" value="entrar"><label>E-mail<input required type="email"
                    name="email"></label><label>Senha<input required type="password" name="senha"></label><button
                class="btn-primary">Entrar</button>
        </form>
        <form method="post" class="card-form">
            <h2>Cadastre-se</h2><input type="hidden" name="acao" value="cadastrar"><label>Nome<input required
                    name="nome"></label><label>E-mail<input required type="email"
                    name="email"></label><label>Telefone<input name="telefone"></label><label>Senha<input required
                    minlength="8" type="password" name="senha"></label><button class="btn-primary">Criar conta</button>
        </form>
    </div>
    <form method="post" class="card-form">
        <h2>Recuperar senha</h2><input type="hidden" name="acao" value="recuperar"><label>E-mail<input required
                type="email" name="email"></label><button class="btn-primary">Simular recuperação</button>
    </form>
</main><?php require 'includes/footer.php'; ?>