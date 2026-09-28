<?php
require_once 'config/app.php';

$tituloPagina = 'Suporte';
$paginaNavegacaoAtiva = 'Suporte';
$erro = '';

if (empty($_SESSION['token_suporte'])) {
    $_SESSION['token_suporte'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $mensagem = trim((string) ($_POST['mensagem'] ?? ''));
    $token = (string) ($_POST['token'] ?? '');

    if (!hash_equals($_SESSION['token_suporte'], $token)) {
        $erro = 'A sessão expirou. Atualize a página e tente novamente.';
    } elseif ($nome === '' || mb_strlen($nome) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190 || $mensagem === '' || mb_strlen($mensagem) > 5000) {
        $erro = 'Confira os dados. A mensagem deve ter até 5.000 caracteres.';
    } elseif (!$pdo) {
        $erro = 'Não foi possível enviar agora. Tente novamente mais tarde.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO mensagens_suporte (nome, email, mensagem) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $email, $mensagem]);
            unset($_SESSION['token_suporte']);
            mensagemFlash('sucesso', 'Mensagem enviada! Nossa equipe recebeu seu contato.');
            redirecionar('suporte.php');
        } catch (PDOException $e) {
            $erro = 'Não foi possível enviar agora. Tente novamente mais tarde.';
        }
    }
}

require_once 'includes/header.php';
?>
<main class="pagina-suporte">
    <section class="suporte-hero">
        <div class="container suporte-hero__inner">
            <div>
                <h1 class="hero__titulo">Fale com<br>a gente.</h1>
                <p class="hero__lead">Conte o que aconteceu ou tire sua dúvida. Nossa equipe vai receber seu chamado.</p>
            </div>
            <div class="suporte-foto"><img src="assets/img/artistas/taylor.jpg" alt="Taylor Swift em uma imagem promocional" loading="lazy"></div>
        </div>
    </section>
    <section class="container suporte-conteudo" aria-labelledby="form-suporte-titulo">
        <div class="suporte-nota">
            <div><h2>Como podemos ajudar?</h2><p>Preencha seus dados e envie sua mensagem. Os campos marcados com * são obrigatórios.</p></div>
        </div>
        <form class="form-card suporte-form" method="post" action="suporte.php">
            <h2 id="form-suporte-titulo">Envie sua mensagem</h2>
            <?php if ($erro !== ''): ?><p class="msg msg--erro" role="alert"><?= escapar($erro) ?></p><?php endif; ?>
            <input type="hidden" name="token" value="<?= escapar($_SESSION['token_suporte']) ?>">
            <label>Nome
                <input type="text" name="nome" autocomplete="name" maxlength="100" required value="<?= escapar((string) ($_POST['nome'] ?? '')) ?>" placeholder="Como podemos chamar você?">
            </label>
            <label>E-mail
                <input type="email" name="email" autocomplete="email" maxlength="190" required value="<?= escapar((string) ($_POST['email'] ?? '')) ?>" placeholder="voce@exemplo.com">
            </label>
            <label>Mensagem
                <textarea name="mensagem" rows="7" maxlength="5000" required placeholder="Escreva sua dúvida ou conte como podemos ajudar..."><?= escapar((string) ($_POST['mensagem'] ?? '')) ?></textarea>
            </label>
            <p class="suporte-privacidade">Seu chamado será recebido pela equipe da loja.</p>
            <button class="btn btn--primario" type="submit">Enviar mensagem <?= icone('seta') ?></button>
        </form>
    </section>
</main>
<?php require 'includes/footer.php'; ?>