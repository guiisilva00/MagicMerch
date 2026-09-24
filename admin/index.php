<?php
$tituloPaginaAdmin = 'Dashboard';
$subtituloAdmin = 'Visão geral da sua loja';
$paginaAdminAtiva = 'dashboard';
require __DIR__ . '/../includes/cabecalho-admin.php';

// Carregamento de dados (banco ou fallback demonstrativo)
$pedidos = [];
$itensPedido = [];
$produtos = [];
$artistas = [];
$usuarios = [];

if ($pdo !== null) {
    try {
        $pedidos = readAll($pdo, 'pedidos', '1 ORDER BY data_pedido DESC');
        $itensPedido = readAll($pdo, 'itens_pedido');
        $produtos = readAll($pdo, 'produtos', '1 ORDER BY vendas DESC, id DESC');
        $artistas = indexarPorId(readAll($pdo, 'artistas'));
        $usuarios = indexarPorId(readAll($pdo, 'usuarios'));
    } catch (Throwable $e) {
        // Fallback para dados demonstrativos
    }
}

// 1. CARDS DE RESUMO
if (!empty($pedidos)) {
    $pedidosPagos = array_filter($pedidos, fn($p) => (int) ($p['pagamento_confirmado'] ?? 0) === 1);
    $totalVendas = (float) array_sum(array_column($pedidosPagos, 'valor_total'));
    $totalPedidos = count($pedidos);
    $totalProdutosVendidos = (int) array_sum(array_column($itensPedido, 'quantidade'));
    $totalProdutosEstoque = (int) array_sum(array_column($produtos, 'estoque'));
} else {
    $totalVendas = 12480.00;
    $totalPedidos = 128;
    $totalProdutosVendidos = 347;
    $totalProdutosEstoque = 86;
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
} else {
    $pedidosTabela = [
        ['id' => '#1024', 'cliente' => 'João Silva', 'produto' => 'Camiseta BTS', 'data' => '24/09/2026', 'valor' => 'R$ 89,90', 'status' => 'Pago', 'status_slug' => 'pago'],
        ['id' => '#1023', 'cliente' => 'Maria Souza', 'produto' => 'Poster Blackpink', 'data' => '23/09/2026', 'valor' => 'R$ 49,90', 'status' => 'Enviado', 'status_slug' => 'enviado'],
        ['id' => '#1022', 'cliente' => 'Lucas Santos', 'produto' => 'Moletom EXO', 'data' => '23/09/2026', 'valor' => 'R$ 159,90', 'status' => 'Em produção', 'status_slug' => 'em_producao'],
        ['id' => '#1021', 'cliente' => 'Ana Clara', 'produto' => 'Caneca TWICE', 'data' => '22/09/2026', 'valor' => 'R$ 42,00', 'status' => 'Concluído', 'status_slug' => 'concluido'],
        ['id' => '#1020', 'cliente' => 'Pedro Henrique', 'produto' => 'GripTok Stray Kids', 'data' => '21/09/2026', 'valor' => 'R$ 35,00', 'status' => 'Aguardando', 'status_slug' => 'aguardando'],
    ];
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
} else {
    $estoqueLista = [
        ['nome' => 'Camiseta BTS', 'quantidade' => 18, 'baixo' => false, 'porcentagem' => 60],
        ['nome' => 'Moletom EXO', 'quantidade' => 7, 'baixo' => false, 'porcentagem' => 30],
        ['nome' => 'Poster Blackpink', 'quantidade' => 3, 'baixo' => true, 'porcentagem' => 15],
        ['nome' => 'Caneca TWICE', 'quantidade' => 4, 'baixo' => true, 'porcentagem' => 18],
    ];
}

// 4. PRODUTOS MAIS VENDIDOS
$maisVendidosLista = [];
if (!empty($produtos)) {
    foreach (array_slice($produtos, 0, 4) as $p) {
        $art = $artistas[$p['artista_id']]['nome'] ?? 'Artista';
        $maisVendidosLista[] = [
            'nome' => $p['nome'],
            'artista' => $art,
            'vendas' => (int) ($p['vendas'] ?? 0),
            'preco' => valorMoeda((float) $p['preco']),
            'imagem' => $p['imagem'] ?? '',
            'letra' => mb_strtoupper(mb_substr(trim($p['nome']), 0, 1))
        ];
    }
} else {
    $maisVendidosLista = [
        ['nome' => 'Camiseta BTS Butter', 'artista' => 'BTS', 'vendas' => 142, 'preco' => 'R$ 89,90', 'imagem' => '', 'letra' => 'C'],
        ['nome' => 'Moletom Over EXO Love', 'artista' => 'EXO', 'vendas' => 98, 'preco' => 'R$ 159,90', 'imagem' => '', 'letra' => 'M'],
        ['nome' => 'Poster Born Pink Edição', 'artista' => 'Blackpink', 'vendas' => 74, 'preco' => 'R$ 49,90', 'imagem' => '', 'letra' => 'P'],
        ['nome' => 'Caneca Cerâmica TWICE', 'artista' => 'TWICE', 'vendas' => 61, 'preco' => 'R$ 42,00', 'imagem' => '', 'letra' => 'T'],
    ];
}

// 5. DADOS DO GRÁFICO (Jan - Jun)
$mesesGrafico = [
    ['mes' => 'Jan', 'valor' => 'R$ 7.200'],
    ['mes' => 'Fev', 'valor' => 'R$ 8.900'],
    ['mes' => 'Mar', 'valor' => 'R$ 6.800'],
    ['mes' => 'Abr', 'valor' => 'R$ 10.400'],
    ['mes' => 'Mai', 'valor' => 'R$ 11.200'],
    ['mes' => 'Jun', 'valor' => 'R$ 12.480'],
];
?>

<!-- 1. Cards de Resumo -->
<section class="cards-resumo" aria-label="Resumo geral de vendas e estoque">
    <article class="card-resumo">
        <div class="card-resumo__topo">
            <span class="card-resumo__titulo">Vendas</span>
            <span class="card-resumo__tag card-resumo__tag--rosa">+12%</span>
        </div>
        <strong class="card-resumo__valor"><?= valorMoeda($totalVendas) ?></strong>
        <span class="card-resumo__auxiliar">Faturamento acumulado</span>
    </article>

    <article class="card-resumo">
        <div class="card-resumo__topo">
            <span class="card-resumo__titulo">Pedidos</span>
            <span class="card-resumo__tag card-resumo__tag--roxo">+8%</span>
        </div>
        <strong class="card-resumo__valor"><?= (int) $totalPedidos ?></strong>
        <span class="card-resumo__auxiliar">Total de pedidos realizados</span>
    </article>

    <article class="card-resumo">
        <div class="card-resumo__topo">
            <span class="card-resumo__titulo">Produtos vendidos</span>
            <span class="card-resumo__tag card-resumo__tag--rosa">Itens</span>
        </div>
        <strong class="card-resumo__valor"><?= (int) $totalProdutosVendidos ?></strong>
        <span class="card-resumo__auxiliar">Peças entregues e em envio</span>
    </article>

    <article class="card-resumo">
        <div class="card-resumo__topo">
            <span class="card-resumo__titulo">Produtos em estoque</span>
            <span class="card-resumo__tag card-resumo__tag--neutro">Ativos</span>
        </div>
        <strong class="card-resumo__valor"><?= (int) $totalProdutosEstoque ?></strong>
        <span class="card-resumo__auxiliar">Unidades totais disponíveis</span>
    </article>
</section>

<!-- 2. Linha do Meio: Gráfico de Vendas + Pedidos Recentes -->
<section class="grid-dashboard grid-dashboard--duas-colunas">
    <!-- Bloco: Gráfico de Vendas -->
    <article class="painel-card">
        <header class="painel-card__header">
            <div>
                <h2 class="painel-card__titulo">Vendas</h2>
                <p class="painel-card__subtitulo">Evolução do faturamento no período</p>
            </div>
            <div class="painel-card__opcoes">
                <span class="opcao-periodo">Últimos 30 dias</span>
                <span class="opcao-periodo opcao-periodo--ativo">Últimos 6 meses</span>
                <span class="opcao-periodo">Este ano</span>
            </div>
        </header>

        <div class="grafico-vendas-container">
            <svg class="grafico-vendas-svg" viewBox="0 0 520 160" aria-label="Gráfico de evolução de vendas">
                <defs>
                    <linearGradient id="gradVendas" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f25496" stop-opacity="0.35" />
                        <stop offset="100%" stop-color="#fac7dc" stop-opacity="0.02" />
                    </linearGradient>
                </defs>

                <!-- Linhas guia horizontais -->
                <line x1="20" y1="35" x2="500" y2="35" stroke="#f1f5f9" stroke-dasharray="4 4" stroke-width="1.2" />
                <line x1="20" y1="75" x2="500" y2="75" stroke="#f1f5f9" stroke-dasharray="4 4" stroke-width="1.2" />
                <line x1="20" y1="115" x2="500" y2="115" stroke="#f1f5f9" stroke-dasharray="4 4" stroke-width="1.2" />

                <!-- Área sombreada sob a linha de tendência -->
                <polygon points="35,110 125,90 215,105 305,65 395,50 485,25 485,150 35,150" fill="url(#gradVendas)" />

                <!-- Linha de tendência contínua -->
                <polyline points="35,110 125,90 215,105 305,65 395,50 485,25" fill="none" stroke="#e41169" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

                <!-- Pontos de dados com destaque -->
                <circle cx="35" cy="110" r="4.5" fill="#ffffff" stroke="#e41169" stroke-width="2.5" />
                <circle cx="125" cy="90" r="4.5" fill="#ffffff" stroke="#e41169" stroke-width="2.5" />
                <circle cx="215" cy="105" r="4.5" fill="#ffffff" stroke="#e41169" stroke-width="2.5" />
                <circle cx="305" cy="65" r="4.5" fill="#ffffff" stroke="#e41169" stroke-width="2.5" />
                <circle cx="395" cy="50" r="4.5" fill="#ffffff" stroke="#e41169" stroke-width="2.5" />
                <circle cx="485" cy="25" r="5.5" fill="#e41169" stroke="#ffffff" stroke-width="2.5" />
            </svg>

            <!-- Eixo X com os meses e valores correspondentes -->
            <div class="grafico-eixo-x">
                <?php foreach ($mesesGrafico as $m): ?>
                    <div class="grafico-coluna">
                        <span class="grafico-coluna__mes"><?= $m['mes'] ?></span>
                        <span class="grafico-coluna__valor"><?= $m['valor'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </article>

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
                    <?php foreach ($pedidosTabela as $p): ?>
                        <?php
                            $classeBadge = 'badge--neutro';
                            $statusSlug = strtolower($p['status_slug']);
                            if (str_contains($statusSlug, 'pago') || str_contains($statusSlug, 'concluido')) {
                                $classeBadge = 'badge--sucesso';
                            } elseif (str_contains($statusSlug, 'enviado')) {
                                $classeBadge = 'badge--info';
                            } elseif (str_contains($statusSlug, 'producao')) {
                                $classeBadge = 'badge--alerta';
                            }
                        ?>
                        <tr>
                            <td class="tabela-pedidos__id"><?= escapar($p['id']) ?></td>
                            <td class="tabela-pedidos__cliente"><?= escapar($p['cliente']) ?></td>
                            <td><?= escapar($p['produto']) ?></td>
                            <td class="tabela-pedidos__data"><?= escapar($p['data']) ?></td>
                            <td class="tabela-pedidos__valor"><?= escapar($p['valor']) ?></td>
                            <td><span class="badge <?= $classeBadge ?>"><?= escapar($p['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
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
            <?php foreach ($maisVendidosLista as $prod): ?>
                <div class="card-produto-destaque">
                    <div class="card-produto-destaque__midia">
                        <?php if (!empty($prod['imagem']) && file_exists(__DIR__ . '/../' . ltrim($prod['imagem'], '/'))): ?>
                            <img src="../<?= escapar(ltrim($prod['imagem'], '/')) ?>" alt="<?= escapar($prod['nome']) ?>">
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
        </div>
    </article>
</section>

<?php require __DIR__ . '/../includes/rodape-admin.php'; ?>
