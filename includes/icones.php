<?php
/**
 * Ícones em SVG inline (traço único, 20px, herdam a cor via currentColor).
 * Sem biblioteca externa. Uso: <?= icone('sacola') ?>
 */
function icone(string $nome, string $classe = ''): string
{
    $paths = [
        'perfil' => '<circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6"/>',
        'sacola' => '<path d="M5 8h14l-1 12H6L5 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
        'busca' => '<circle cx="11" cy="11" r="6"/><path d="M20 20l-4-4"/>',
        'seta' => '<path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>',
        'coracao' => '<path d="M12 20S4 14.5 4 9a4 4 0 0 1 8-1 4 4 0 0 1 8 1c0 5.5-8 11-8 11z"/>',
        'check' => '<path d="M5 12l5 5 9-11"/>',
        'menu' => '<path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/>',
        'x' => '<path d="M6 6l12 12"/><path d="M18 6L6 18"/>',
    ];
    $d = $paths[$nome] ?? '';
    return '<svg class="icone ' . escapar($classe) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" '
        . 'stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
        . $d . '</svg>';
}
