<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function escapar(string $valor): string { return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'); }
function usuarioAtual(): ?array { return $_SESSION['usuario'] ?? null; }
function estaLogado(): bool { return usuarioAtual() !== null; }
function eAdministrador(): bool { return estaLogado() && usuarioAtual()['tipo'] === 'administrador'; }
function redirecionar(string $url): never { header('Location: ' . $url); exit; }
function exigirLogin(string $destino = 'login.php'): void { if (!estaLogado()) { $_SESSION['retorno'] = basename($_SERVER['PHP_SELF']); redirecionar($destino); } }
function exigirAdministrador(): void { if (!eAdministrador()) { redirecionar('login.php'); } }
function mensagemFlash(?string $tipo = null, ?string $texto = null): ?array { if ($tipo !== null) { $_SESSION['flash'] = [$tipo, $texto]; return null; } $m = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $m; }
function valorMoeda(float $valor): string { return 'R$ ' . number_format($valor, 2, ',', '.'); }
function quantidadeCarrinho(PDO $db, int $usuarioId): int { $s=$db->prepare('SELECT COALESCE(SUM(quantidade),0) FROM carrinho WHERE usuario_id=?'); $s->execute([$usuarioId]); return (int)$s->fetchColumn(); }
function itensCarrinho(PDO $db, int $usuarioId): array { $s=$db->prepare('SELECT c.produto_id,c.quantidade,p.nome,p.preco,p.estoque,p.imagem FROM carrinho c JOIN produtos p ON p.id=c.produto_id WHERE c.usuario_id=? ORDER BY c.data_adicao DESC'); $s->execute([$usuarioId]); return $s->fetchAll(); }
function subtotalCarrinho(array $itens): float { return array_sum(array_map(fn($i)=>(float)$i['preco']*(int)$i['quantidade'],$itens)); }
function calcularFrete(string $modalidade, string $estado = ''): float { if ($modalidade === 'retirada') return 0; return strtoupper(trim($estado)) === 'SP' || stripos($estado, 'são paulo') !== false ? 10 : 20; }
function statusPedido(): array { return ['aguardando_pagamento'=>'Aguardando pagamento','pagamento_confirmado'=>'Pagamento confirmado','em_producao_separacao'=>'Em produção/separação','enviado'=>'Enviado','concluido'=>'Concluído']; }