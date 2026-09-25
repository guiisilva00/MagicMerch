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
                <a href="index.php" class="nav__link <?= $paginaNavegacaoAtiva === 'Início' ? 'nav__link--ativo' : '' ?>">Início</a>
                <a href="sobre.php" class="nav__link <?= $paginaNavegacaoAtiva === 'Sobre nós' ? 'nav__link--ativo' : '' ?>">Sobre nós</a>
                <a href="produtos.php" class="nav__link <?= $paginaNavegacaoAtiva === 'Produtos' ? 'nav__link--ativo' : '' ?>">Produtos</a>
                <a href="artistas.php" class="nav__link <?= $paginaNavegacaoAtiva === 'Artistas e bandas' ? 'nav__link--ativo' : '' ?>">Artistas e bandas</a>
                <a href="suporte.php" class="nav__link <?= $paginaNavegacaoAtiva === 'Suporte' ? 'nav__link--ativo' : '' ?>">Suporte</a>
                <a href="colecoes.php" class="nav__link <?= $paginaNavegacaoAtiva === 'Coleções especiais' ? 'nav__link--ativo' : '' ?>">Coleções especiais</a>
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
                        <a href="index.php">Início</a>
                        <a href="sobre.php">Sobre nós</a>
                        <a href="produtos.php">Produtos</a>
                        <a href="artistas.php">Artistas e bandas</a>
                        <a href="suporte.php">Suporte</a>
                        <a href="colecoes.php">Coleções especiais</a>
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
