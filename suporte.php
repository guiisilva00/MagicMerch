<?php
require_once 'config/app.php';

$tituloPagina = 'Suporte';
$paginaNavegacaoAtiva = '';
$erroEnvio = '';
$tabelaSuporteDisponivel = $pdo !== null;

if ($pdo !== null) {
    try {
        $pdo->exec('CREATE TABLE IF NOT EXISTS mensagens_suporte (id INT AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(100) NOT NULL, email VARCHAR(190) NOT NULL, mensagem TEXT NOT NULL, data_envio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)');
    } catch (PDOException $e) {
        $tabelaSuporteDisponivel = false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string) ($_POST['nome'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $mensagem = trim((string) ($_POST['mensagem'] ?? ''));
    $token = (string) ($_POST['token'] ?? '');

    if (!isset($_SESSION['token_suporte']) || !hash_equals($_SESSION['token_suporte'], $token)) {
        $erroEnvio = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } elseif ($nome === '' || mb_strlen($nome) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190 || $mensagem === '' || mb_strlen($mensagem) > 5000) {
        $erroEnvio = 'Confira seus dados. A mensagem deve ter até 5.000 caracteres.';
    } elseif (!$tabelaSuporteDisponivel) {
        $erroEnvio = 'Não foi possível enviar agora. Tente novamente mais tarde.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO mensagens_suporte (nome, email, mensagem) VALUES (:nome, :email, :mensagem)');
            $stmt->execute(['nome' => $nome, 'email' => $email, 'mensagem' => $mensagem]);
            unset($_SESSION['token_suporte']);
            mensagemFlash('sucesso', 'Mensagem enviada! Nossa equipe recebeu seu contato.');
            redirecionar('suporte.php');
        } catch (PDOException $e) {
            $erroEnvio = 'Não foi possível enviar agora. Tente novamente mais tarde.';
        }
    }
}

if (empty($_SESSION['token_suporte'])) {
    $_SESSION['token_suporte'] = bin2hex(random_bytes(32));
}
require_once 'includes/header.php';
?>
<main class="pagina-suporte">
    <section class="suporte-hero">
        <div class="container suporte-hero__inner">
            <div>
                <h1 class="hero__titulo">Fale com<br>a gente.</h1>
                <p class="hero__lead">Conte o que aconteceu ou tire sua dúvida. Nossa equipe vai entrar em contato pelo e-mail informado.</p>
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
            <?php if ($erroEnvio !== ''): ?><p class="msg msg--erro" role="alert"><?= escapar($erroEnvio) ?></p><?php endif; ?>
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
            <p class="suporte-privacidade">Usaremos seus dados somente para responder ao seu contato.</p>
            <button class="btn btn--primario" type="submit">Enviar mensagem <?= icone('seta') ?></button>
        </form>
    </section>
</main>
<?php require 'includes/footer.php'; ?>
