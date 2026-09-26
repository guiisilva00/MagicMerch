<?php
$tituloPaginaAdmin = 'Dashboard';
$subtituloAdmin = 'Visão geral da sua loja';
$paginaAdminAtiva = 'dashboard';
require __DIR__ . '/../includes/cabecalho-admin.php';

// Carregamento dos dados disponíveis no banco.
$pedidos = readAll($pdo, 'pedidos', '1 ORDER BY data_pedido DESC');
$itensPedido = readAll($pdo, 'itens_pedido');
$produtos = readAll($pdo, 'produtos', '1 ORDER BY vendas DESC, id DESC');
$artistas = indexarPorId(readAll($pdo, 'artistas'));
$usuarios = indexarPorId(readAll($pdo, 'usuarios'));

// 1. CARDS DE RESUMO
$totalVendas = $totalPedidos = $totalProdutosVendidos = $totalProdutosEstoque = 0;
if (!empty($pedidos)) {
    $totalVendas = 0;
    foreach ($pedidos as $ped) {
        if ((int) ($ped['pagamento_confirmado'] ?? 0) === 1) {
            $totalVendas += (float) $ped['valor_total'];
        }
    }
    $totalPedidos = count($pedidos);

    $totalProdutosVendidos = 0;
    foreach ($itensPedido as $item) {
        $totalProdutosVendidos += (int) $item['quantidade'];
    }

    $totalProdutosEstoque = 0;
    foreach ($produtos as $p) {
        $totalProdutosEstoque += (int) $p['estoque'];
    }
}

// 2. PEDIDOS RECENTES
$pedidosTabela = [];
if (!empty($pedidos)) {
    $itensPorPedido = [];
    $produtosPorId = indexarPorId($produtos);
    foreach ($itensPedido as $item) {
        $pId = $item['pedido_id'];
        if (!isset($itensPorPedido[$pId])) {
            $itensPorPedido[$pId] = $produtosPorId[$item['produto_id']]['nome'] ?? 'Produto';
        }
    }

    $statusLegendas = statusPedido();
    foreach (array_slice($pedidos, 0, 5) as $ped) {
        $uId = $ped['usuario_id'];
        $clienteNome = $usuarios[$uId]['nome'] ?? 'Cliente';
        $produtoNome = $itensPorPedido[$ped['id']] ?? 'Merch oficial';
        $dataFmt = !empty($ped['data_pedido']) ? date('d/m/Y', strtotime($ped['data_pedido'])) : date('d/m/Y');
        $pedidosTabela[] = [
            'id' => '#' . $ped['id'],
            'cliente' => $clienteNome,
            'produto' => $produtoNome,
            'data' => $dataFmt,
            'valor' => valorMoeda((float) $ped['valor_total']),
            'status' => $statusLegendas[$ped['status']] ?? ucfirst(str_replace('_', ' ', $ped['status'])),
            'status_slug' => $ped['status']
        ];
    }
}

// 3. ESTOQUE
$estoqueLista = [];
if (!empty($produtos)) {
    $produtosEstoque = $produtos;
    usort($produtosEstoque, fn($a, $b) => (int) $a['estoque'] <=> (int) $b['estoque']);
    foreach (array_slice($produtosEstoque, 0, 4) as $p) {
        $qtd = (int) $p['estoque'];
        $estoqueLista[] = [
            'nome' => $p['nome'],
            'quantidade' => $qtd,
            'baixo' => $qtd <= 5,
            'porcentagem' => min(100, max(8, (int) round(($qtd / 30) * 100)))
        ];
    }
}

// 4. PRODUTOS MAIS VENDIDOS
$maisVendidosLista = [];
if (!empty($produtos)) {
    foreach (array_slice($produtos, 0, 4) as $p) {
        $art = $artistas[$p['artista_id']]['nome'] ?? 'Artista';
        $maisVendidosLista[] = [
            'id' => $p['id'],
            'nome' => $p['nome'],
            'artista' => $art,
            'vendas' => (int) ($p['vendas'] ?? 0),
            'preco' => valorMoeda((float) $p['preco']),
            'imagem' => obterCaminhoImagem($p['imagem'] ?? null, 'produtos', $p['id']),
            'letra' => mb_strtoupper(mb_substr(trim($p['nome']), 0, 1))
        ];
    }
}
?>

<!-- 1. Cards de Resumo -->
<section class="cards-resumo" aria-label="Resumo geral de vendas e estoque">
    <article class="card-resumo">
        <div class="card-resumo__topo">
            <span class="card-resumo__titulo">Vendas</span>
        </div>
        <strong class="card-resumo__valor"><?= valorMoeda($totalVendas ?? 0) ?></strong>
        <span class="card-resumo__auxiliar">Faturamento acumulado</span>
    </article>

    <article class="card-resumo">
        <div class="card-resumo__topo">
            <span class="card-resumo__titulo">Pedidos</span>
        </div>
        <strong class="card-resumo__valor"><?= (int) ($totalPedidos ?? 0) ?></strong>
        <span class="card-resumo__auxiliar">Total de pedidos realizados</span>
    </article>

    <article class="card-resumo">
        <div class="card-resumo__topo">
            <span class="card-resumo__titulo">Produtos vendidos</span>
        </div>
        <strong class="card-resumo__valor"><?= (int) ($totalProdutosVendidos ?? 0) ?></strong>
        <span class="card-resumo__auxiliar">Peças entregues e em envio</span>
    </article>

    <article class="card-resumo">
        <div class="card-resumo__topo">
            <span class="card-resumo__titulo">Produtos em estoque</span>
        </div>
        <strong class="card-resumo__valor"><?= (int) ($totalProdutosEstoque ?? 0) ?></strong>
        <span class="card-resumo__auxiliar">Unidades totais disponíveis</span>
    </article>
</section>

<section class="grid-dashboard grid-dashboard--duas-colunas">
    <!-- Bloco: Pedidos Recentes -->
    <article class="painel-card">
        <header class="painel-card__header">
            <div>
                <h2 class="painel-card__titulo">Pedidos recentes</h2>
                <p class="painel-card__subtitulo">Últimas transações na loja</p>
            </div>
            <a href="pedidos.php" class="painel-card__link">Ver todos</a>
        </header>

        <div class="tabela-container">
            <table class="tabela-pedidos">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Produto</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$pedidosTabela): ?><tr><td colspan="6">Nenhum pedido cadastrado.</td></tr><?php else: ?>
                    <?php foreach ($pedidosTabela as $p): ?>
                        <?php $classeBadge = 'badge--neutro'; $statusSlug = strtolower($p['status_slug']); if (str_contains($statusSlug, 'pago') || str_contains($statusSlug, 'concluido')) $classeBadge = 'badge--sucesso'; elseif (str_contains($statusSlug, 'enviado')) $classeBadge = 'badge--info'; elseif (str_contains($statusSlug, 'producao')) $classeBadge = 'badge--alerta'; ?>
                        <tr><td class="tabela-pedidos__id"><?= escapar($p['id']) ?></td><td class="tabela-pedidos__cliente"><?= escapar($p['cliente']) ?></td><td><?= escapar($p['produto']) ?></td><td class="tabela-pedidos__data"><?= escapar($p['data']) ?></td><td class="tabela-pedidos__valor"><?= escapar($p['valor']) ?></td><td><span class="badge <?= $classeBadge ?>"><?= escapar($p['status']) ?></span></td></tr>
                    <?php endforeach; ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </article>
</section>

<!-- 3. Linha Inferior: Estoque + Produtos Mais Vendidos -->
<section class="grid-dashboard grid-dashboard--duas-colunas">
    <!-- Bloco: Estoque -->
    <article class="painel-card">
        <header class="painel-card__header">
            <div>
                <h2 class="painel-card__titulo">Situação do estoque</h2>
                <p class="painel-card__subtitulo">Monitoramento de produtos disponíveis</p>
            </div>
            <a href="estoque.php" class="painel-card__link">Gerenciar estoque</a>
        </header>

        <div class="lista-estoque">
            <?php if (!$estoqueLista): ?><p>Nenhum produto cadastrado.</p><?php else: ?>
            <?php foreach ($estoqueLista as $item): ?>
                <div class="item-estoque">
                    <div class="item-estoque__info">
                        <span class="item-estoque__nome"><?= escapar($item['nome']) ?></span>
                        <span class="item-estoque__qtd">
                            <?= $item['quantidade'] ?> unidade<?= $item['quantidade'] === 1 ? '' : 's' ?>
                            <?php if ($item['baixo']): ?>
                                <span class="tag-baixo-estoque">Pouco estoque</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="barra-estoque">
                        <div class="barra-estoque__progresso <?= $item['baixo'] ? 'barra-estoque__progresso--alerta' : '' ?>" style="width: <?= $item['porcentagem'] ?>%;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </article>

    <!-- Bloco: Produtos Mais Vendidos -->
    <article class="painel-card">
        <header class="painel-card__header">
            <div>
                <h2 class="painel-card__titulo">Produtos mais vendidos</h2>
                <p class="painel-card__subtitulo">Itens com maior saída na MagicMerch</p>
            </div>
            <a href="produtos.php" class="painel-card__link">Ver catálogo</a>
        </header>

        <div class="grid-mais-vendidos">
            <?php if (!$maisVendidosLista): ?><p>Nenhum produto cadastrado.</p><?php else: ?>
            <?php foreach ($maisVendidosLista as $prod): ?>
                <div class="card-produto-destaque">
                    <div class="card-produto-destaque__midia">
                        <?php if ($prod['imagem']): ?>
                            <img src="<?= str_starts_with($prod['imagem'], 'http') ? escapar($prod['imagem']) : '../' . escapar(ltrim($prod['imagem'], '/')) ?>" alt="<?= escapar($prod['nome']) ?>">
                        <?php else: ?>
                            <div class="produto-placeholder" aria-hidden="true"><?= escapar($prod['letra']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-produto-destaque__detalhes">
                        <strong class="card-produto-destaque__nome" title="<?= escapar($prod['nome']) ?>"><?= escapar($prod['nome']) ?></strong>
                        <span class="card-produto-destaque__artista"><?= escapar($prod['artista']) ?></span>
                        <div class="card-produto-destaque__meta">
                            <span class="card-produto-destaque__preco"><?= escapar($prod['preco']) ?></span>
                            <span class="card-produto-destaque__vendas"><?= $prod['vendas'] ?> vendas</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </article>
</section>

<?php require __DIR__ . '/../includes/rodape-admin.php'; ?>
