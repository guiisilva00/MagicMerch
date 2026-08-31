<?php
// Icons as functions to mimic the React components, but returning string HTML
function IconSearch() {
  return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" /></svg>';
}
function IconUser() {
  return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>';
}
function IconBag() {
  return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" /><line x1="3" y1="6" x2="21" y2="6" /><path d="M16 10a4 4 0 0 1-8 0" /></svg>';
}
function IconChevronDown() {
  return '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>';
}
function IconArrowLeft() {
  return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6" /></svg>';
}
function IconArrowRight() {
  return '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6" /></svg>';
}
function IconArrowUpRight() {
  return '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M7 7h10v10" /></svg>';
}

$bagCount = 3;
$activeNav = 'Home'; // Could be dynamic based on current page
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MagicMerch</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <div class="announcement-strip">
        Frete especial para SP  ·  Compre 10 e ganhe 1 brinde  ·  Atendimento seg–sex 9h–18h
    </div>

    <header class="main-header">
        <div class="container header-container">
            <!-- Logo -->
            <div class="logo">
                <a href="index.php">
                    <img src="assets/img/logo/logo.svg" alt="MagicMerch">
                </a>
            </div>

            <!-- Nav -->
            <nav class="main-nav">
                <?php foreach ($navLinks as $link): ?>
                    <a href="<?php echo htmlspecialchars($link['url']); ?>" class="nav-link <?php echo $activeNav === $link['label'] ? 'active' : ''; ?>">
                        <span class="nav-text"><?php echo htmlspecialchars($link['label']); ?></span>
                        <?php if ($link['sub']): ?>
                            <span class="nav-icon-sub"><?php echo IconChevronDown(); ?></span>
                        <?php endif; ?>
                        
                        <?php if ($activeNav === $link['label']): ?>
                            <span class="nav-underline active-underline"></span>
                        <?php else: ?>
                            <span class="nav-underline hover-underline"></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Actions -->
            <div class="header-actions">
                <form action="busca.php" method="GET" class="search-form">
                    <!-- Placeholder for future search implementation -->
                    <button type="submit" class="action-btn" title="Buscar">
                        <?php echo IconSearch(); ?>
                    </button>
                </form>
                
                <a href="login.php" class="action-btn" title="Perfil">
                    <?php echo IconUser(); ?>
                </a>
                
                <a href="carrinho.php" class="action-btn bag-btn" title="Carrinho">
                    <?php echo IconBag(); ?>
                    <?php if ($bagCount > 0): ?>
                        <span class="bag-count"><?php echo $bagCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </header>
