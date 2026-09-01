<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/config.php';
$tituloPagina = $tituloPagina ?? 'MagicMerch';
$paginaNavegacaoAtiva = $paginaNavegacaoAtiva ?? 'Início';
function escaparHtml(string $texto): string
{
    return escapar($texto);
}
try {
    $quantidadeItensCarrinho = estaLogado() ? quantidadeCarrinho(criarConexaoBancoDados(), (int) usuarioAtual()['id']) : 0;
} catch (Throwable $e) {
    $quantidadeItensCarrinho = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar($tituloPagina) ?> | MagicMerch</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="announcement-strip">Frete especial para SP · Compre 10 e ganhe 1 brinde · Atendimento seg–sex, das 9h às
        18h</div>
    <header class="main-header">
        <div class="container header-container"><a class="logo" href="index.php"><img src="assets/img/logo/logo.svg"
                    alt="MagicMerch"></a>
            <nav class="main-nav" aria-label="Navegação principal"><?php foreach ($linksNavegacao as $link): ?><a
                        href="<?= escapar($link['url']) ?>"
                        class="nav-link <?= $paginaNavegacaoAtiva === $link['rotulo'] ? 'active' : '' ?>"><?= escapar($link['rotulo']) ?></a><?php endforeach; ?>
            </nav>
            <div class="header-actions"><a href="<?= estaLogado() ? 'perfil.php' : 'login.php' ?>" class="action-btn"
                    aria-label="Perfil">◯</a><a href="carrinho.php" class="action-btn bag-btn"
                    aria-label="Carrinho">▢<?php if ($quantidadeItensCarrinho): ?><span
                            class="bag-count"><?= $quantidadeItensCarrinho ?></span><?php endif; ?></a></div>
        </div>
    </header>
    <?php if ($flash = mensagemFlash()): ?>
        <div class="container">
            <p class="mensagem-<?= escapar($flash[0]) ?>"><?= escapar((string) $flash[1]) ?></p>
        </div><?php endif; ?>