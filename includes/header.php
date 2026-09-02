<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/icones.php';
$tituloPagina = $tituloPagina ?? 'MagicMerch';
$paginaNavegacaoAtiva = $paginaNavegacaoAtiva ?? 'Início';
try {
    $quantidadeItensCarrinho = ($pdo && estaLogado()) ? quantidadeCarrinho($pdo, (int) usuarioAtual()['id']) : 0;
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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700;1,900&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="aviso">Frete especial para SP · Compre 10 e ganhe 1 brinde · Atendimento seg–sex, das 9h às 18h</div>

    <header class="cabecalho">
        <div class="container cabecalho__inner">
            <a class="logo" href="index.php"><img src="assets/img/logo/logo.svg" alt="MagicMerch"></a>

            <nav class="nav" aria-label="Navegação principal">
                <?php foreach ($linksNavegacao as $link): ?>
                    <a href="<?= escapar($link['url']) ?>"
                        class="nav__link <?= $paginaNavegacaoAtiva === $link['rotulo'] ? 'nav__link--ativo' : '' ?>"><?= escapar($link['rotulo']) ?></a>
                <?php endforeach; ?>
            </nav>

            <div class="acoes">
                <a href="<?= estaLogado() ? 'perfil.php' : 'login.php' ?>" class="acao" aria-label="Minha conta"><?= icone('perfil') ?></a>
                <a href="carrinho.php" class="acao" aria-label="Carrinho">
                    <?= icone('sacola') ?>
                    <?php if ($quantidadeItensCarrinho): ?><span class="acao__badge"><?= $quantidadeItensCarrinho ?></span><?php endif; ?>
                </a>
                <details class="menu">
                    <summary class="menu__resumo" aria-label="Menu"><?= icone('menu') ?></summary>
                    <div class="menu__lista">
                        <?php foreach ($linksNavegacao as $link): ?>
                            <a href="<?= escapar($link['url']) ?>"><?= escapar($link['rotulo']) ?></a>
                        <?php endforeach; ?>
                        <a href="<?= estaLogado() ? 'perfil.php' : 'login.php' ?>"><?= estaLogado() ? 'Minha conta' : 'Entrar' ?></a>
                    </div>
                </details>
            </div>
        </div>
    </header>

    <?php if ($flash = mensagemFlash()): ?>
        <div class="container">
            <p class="msg msg--<?= $flash[0] === 'erro' ? 'erro' : 'sucesso' ?>"><?= escapar((string) $flash[1]) ?></p>
        </div>
    <?php endif; ?>
