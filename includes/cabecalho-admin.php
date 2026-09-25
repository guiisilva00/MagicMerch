<?php
require_once __DIR__ . '/../config/app.php';
exigirAdministrador();

$tituloPaginaAdmin = $tituloPaginaAdmin ?? 'Dashboard';
$subtituloAdmin = $subtituloAdmin ?? ($tituloPaginaAdmin === 'Dashboard' ? 'Visão geral da sua loja' : 'Painel de gerenciamento');

$paginaAtiva = basename($_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '');
$isDashboard = ($paginaAdminAtiva ?? '') === 'dashboard' || in_array($paginaAtiva, ['index.php', 'admin', '']);

$usuario = usuarioAtual();
$nomeAdmin = $usuario['nome'] ?? 'Administrador';
$primeiraLetra = mb_strtoupper(mb_substr(trim($nomeAdmin), 0, 1));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= escapar($tituloPaginaAdmin) ?> | MagicMerch</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="pagina-admin">
    <div class="layout-admin">
        <aside class="sidebar-admin">
            <div class="sidebar-admin__brand">
                <a href="index.php" class="sidebar-admin__logo-link">
                    <img src="../assets/img/logo/logo.svg" alt="MagicMerch" class="sidebar-admin__logo">
                </a>
            </div>

            <nav class="sidebar-admin__nav" aria-label="Menu administrativo">
                <a href="index.php" class="sidebar-admin__link <?= $isDashboard ? 'sidebar-admin__link--ativo' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-admin__icon"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="produtos.php" class="sidebar-admin__link <?= $paginaAtiva === 'produtos.php' ? 'sidebar-admin__link--ativo' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-admin__icon"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    <span>Produtos</span>
                </a>
                <a href="estoque.php" class="sidebar-admin__link <?= $paginaAtiva === 'estoque.php' ? 'sidebar-admin__link--ativo' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-admin__icon"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                    <span>Estoque</span>
                </a>
                <a href="pedidos.php" class="sidebar-admin__link <?= $paginaAtiva === 'pedidos.php' ? 'sidebar-admin__link--ativo' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-admin__icon"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    <span>Pedidos</span>
                </a>
                <a href="relatorios.php" class="sidebar-admin__link <?= $paginaAtiva === 'relatorios.php' ? 'sidebar-admin__link--ativo' : '' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-admin__icon"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    <span>Relatórios</span>
                </a>
            </nav>

            <div class="sidebar-admin__footer">
                <a href="../perfil.php" class="sidebar-admin__link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-admin__icon"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span>Ver loja / Conta</span>
                </a>
                <a href="../login.php?sair=1" class="sidebar-admin__link sidebar-admin__link--sair">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sidebar-admin__icon"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Sair</span>
                </a>
            </div>
        </aside>

        <div class="painel-admin">
            <header class="topbar-admin">
                <div class="topbar-admin__titulos">
                    <h1 class="topbar-admin__titulo"><?= escapar($tituloPaginaAdmin) ?></h1>
                    <p class="topbar-admin__subtitulo"><?= escapar($subtituloAdmin) ?></p>
                </div>
                <div class="topbar-admin__usuario">
                    <div class="admin-avatar" aria-hidden="true"><?= escapar($primeiraLetra) ?></div>
                    <span class="admin-usuario__nome"><?= escapar($nomeAdmin) ?></span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="admin-usuario__seta"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </header>

            <main class="conteudo-admin">
