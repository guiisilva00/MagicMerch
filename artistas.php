<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/poster.php';

// Upload de banner/ícone do artista pelo admin, direto nesta página.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campo_imagem'])) {
    exigirAdministrador();
    $artistaIdPost = (int) ($_POST['artista_id'] ?? 0);
    $coluna = $_POST['campo_imagem'] === 'banner' ? 'imagem_banner' : 'imagem';
    if ($artistaIdPost && isset($_FILES['nova_imagem']) && $_FILES['nova_imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo(basename($_FILES['nova_imagem']['name']), PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'], true)) {
            $nomeArquivo = 'artista_' . $artistaIdPost . '_' . $coluna . '_' . time() . '.' . $ext;
            $destino = __DIR__ . '/assets/img/artistas/' . $nomeArquivo;
            if (move_uploaded_file($_FILES['nova_imagem']['tmp_name'], $destino)) {
                update($pdo, 'artistas', [$coluna => 'assets/img/artistas/' . $nomeArquivo], 'id = ?', [$artistaIdPost]);
            }
        }
    }
    redirecionar('artistas.php?artista=' . $artistaIdPost);
}

$colecao = array_key_exists('artista', $_GET);
$id = filter_var(is_string($_GET['artista'] ?? null) ? $_GET['artista'] : '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$artista = null;
$arts = $produtos = $categorias = [];
$filtros = [];
$tituloPagina = 'Artistas e bandas';
$paginaNavegacaoAtiva = 'Artistas e bandas';
$ordenacoes = ['destaque' => 'Destaque', 'menor_preco' => 'Menor preço', 'maior_preco' => 'Maior preço', 'alfabetica' => 'Alfabética'];
$disponibilidades = ['' => 'Todos', 'em_estoque' => 'Em estoque', 'esgotados' => 'Esgotados'];

if ($pdo && !$colecao) {
    $arts = buscarArtistas($pdo);
    $totais = contarProdutosPorArtista(readAll($pdo, 'produtos'));
    foreach ($arts as &$a) {
        $a['total'] = $totais[$a['id']] ?? 0;
    }
    unset($a);
} elseif ($pdo && $colecao) {
    $artista = $id ? read($pdo, 'artistas', 'id = ?', [$id]) : null;
    if (!$artista) {
        http_response_code(404);
        $tituloPagina = 'Artista não encontrado';
    } else {
        $tituloPagina = $artista['nome'];
        foreach (['busca', 'categoria', 'preco_minimo', 'preco_maximo', 'disponibilidade', 'ordenacao'] as $campo) {
            $filtros[$campo] = is_string($_GET[$campo] ?? null) ? trim($_GET[$campo]) : '';
        }
        $filtros['artista'] = (string) $id;
        foreach (['preco_minimo', 'preco_maximo'] as $campo) {
            if ($filtros[$campo] !== '' && (!is_numeric($filtros[$campo]) || !is_finite((float) $filtros[$campo]) || (float) $filtros[$campo] < 0)) {
                $filtros[$campo] = '';
            }
        }
        if (!array_key_exists($filtros['ordenacao'], $ordenacoes)) {
            $filtros['ordenacao'] = 'destaque';
        }
        if (!array_key_exists($filtros['disponibilidade'], $disponibilidades)) {
            $filtros['disponibilidade'] = '';
        }
        $itensArtista = readAll($pdo, 'produtos', 'artista_id = ?', [$id]);
        $categorias = array_values(array_unique(array_column($itensArtista, 'categoria')));
        sort($categorias);
        $produtos = buscarProdutos($pdo, $filtros);
        $imagemBanner = obterCaminhoImagem($artista['imagem_banner'] ?? null, 'artistas', $id . '_banner');
        $imagemIcone = obterCaminhoImagem($artista['imagem'] ?? null, 'artistas', $id);
        $corArtista = acentoPoster($artista['nome']);
    }
}
require __DIR__ . '/includes/header.php';
?>
<main class="container pagina pagina-artistas">
    <?php if (!$colecao): ?>
        <header class="cabecalho-pagina">
            <h1>Artistas e bandas</h1>
            <p>Escolha seu artista. Encontre sua próxima peça favorita.</p>
        </header>
        <?php if (!$pdo): ?>
            <p class="msg msg--aviso">Os artistas ficarão disponíveis quando a conexão com o banco de dados for restabelecida.</p>
        <?php elseif (!$arts): ?>
            <div class="vazio"><h2>Nenhum artista cadastrado</h2><p>Assim que houver artistas no catálogo, eles aparecem aqui.</p></div>
        <?php else: ?>
            <div class="grade grade--artistas" aria-label="Todos os artistas">
                <?php foreach ($arts as $a): ?><?= posterArtista($a) ?><?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php elseif (!$pdo): ?>
        <h1>Coleção indisponível</h1>
        <p class="msg msg--aviso">A coleção ficará disponível quando a conexão com o banco de dados for restabelecida.</p>
        <a class="btn btn--linha" href="artistas.php">Ver artistas</a>
    <?php elseif (!$artista): ?>
        <div class="vazio">
            <h1>Artista não encontrado</h1>
            <p>Não encontramos uma coleção para este artista.</p>
            <a class="btn btn--linha" href="artistas.php">Ver todos os artistas</a>
        </div>
    <?php else: ?>
        <a class="btn--texto colecao__voltar" href="artistas.php">← Todos os artistas</a>
        <header class="colecao poster--c<?= $corArtista ?>">
            <div class="colecao__banner" aria-hidden="true">
                <?php if ($imagemBanner): ?>
                    <img src="<?= escapar($imagemBanner) ?>" alt="">
                <?php else: ?>
                    <span class="colecao__marca"><?= escapar($artista['nome']) ?></span>
                <?php endif; ?>
            </div>
            <div class="colecao__avatar" aria-hidden="true">
                <?php if ($imagemIcone): ?>
                    <img src="<?= escapar($imagemIcone) ?>" alt="">
                <?php else: ?>
                    <span><?= escapar(inicial($artista['nome'])) ?></span>
                <?php endif; ?>
            </div>
            <div class="colecao__info">
                <h1><?= escapar($artista['nome']) ?></h1>
                <p><?= nl2br(escapar($artista['descricao'] ?? '')) ?></p>
            </div>
        </header>

        <?php if (eAdministrador()): ?>
            <div class="colecao__admin-fotos">
                <p class="colecao__admin-titulo">Modo administrador — enviar novas fotos</p>
                <form method="post" enctype="multipart/form-data" class="colecao__admin-form">
                    <input type="hidden" name="artista_id" value="<?= (int) $id ?>">
                    <input type="hidden" name="campo_imagem" value="banner">
                    <label>Banner <input type="file" name="nova_imagem" accept="image/*" required></label>
                    <button type="submit" class="btn btn--linha">Enviar banner</button>
                </form>
                <form method="post" enctype="multipart/form-data" class="colecao__admin-form">
                    <input type="hidden" name="artista_id" value="<?= (int) $id ?>">
                    <input type="hidden" name="campo_imagem" value="icone">
                    <label>Ícone <input type="file" name="nova_imagem" accept="image/*" required></label>
                    <button type="submit" class="btn btn--linha">Enviar ícone</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="colecao-toolbar">
            <a class="btn btn--linha" href="#filtros-artista">Filtrar ↓</a>
            <p class="colecao-toolbar__total"><?= count($produtos) ?> <?= count($produtos) === 1 ? 'produto' : 'produtos' ?></p>
            <label class="campo" for="ordenacao-artista">
                <span>Ordenar por</span>
                <select id="ordenacao-artista" name="ordenacao" form="filtros-artista">
                    <?php foreach ($ordenacoes as $valor => $rotulo): ?>
                        <option value="<?= $valor ?>" <?= $filtros['ordenacao'] === $valor ? 'selected' : '' ?>><?= $rotulo ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button class="btn btn--linha" type="submit" form="filtros-artista">Ordenar</button>
        </div>

        <div class="colecao-layout">
            <form id="filtros-artista" class="colecao-filtros" action="artistas.php" method="get" tabindex="-1">
                <input type="hidden" name="artista" value="<?= (int) $id ?>">
                <h2>Filtrar coleção</h2>
                <label class="campo"><span>Buscar nesta coleção</span><input type="search" name="busca" value="<?= escapar($filtros['busca']) ?>"></label>
                <label class="campo">
                    <span>Categoria</span>
                    <select name="categoria">
                        <option value="">Todas</option>
                        <?php if ($filtros['categoria'] !== '' && !in_array($filtros['categoria'], $categorias, true)): ?>
                            <option value="<?= escapar($filtros['categoria']) ?>" selected><?= escapar(ucfirst($filtros['categoria'])) ?></option>
                        <?php endif; ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= escapar($categoria) ?>" <?= $filtros['categoria'] === $categoria ? 'selected' : '' ?>><?= escapar(ucfirst($categoria)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="campo"><span>Preço mínimo</span><input type="number" min="0" step="0.01" name="preco_minimo" value="<?= escapar($filtros['preco_minimo']) ?>"></label>
                <label class="campo"><span>Preço máximo</span><input type="number" min="0" step="0.01" name="preco_maximo" value="<?= escapar($filtros['preco_maximo']) ?>"></label>
                <label class="campo">
                    <span>Disponibilidade</span>
                    <select name="disponibilidade">
                        <?php foreach ($disponibilidades as $valor => $rotulo): ?>
                            <option value="<?= $valor ?>" <?= $filtros['disponibilidade'] === $valor ? 'selected' : '' ?>><?= $rotulo ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button class="btn btn--primario" type="submit">Aplicar filtros</button>
                <a class="btn--texto" href="artistas.php?artista=<?= (int) $id ?>">Limpar filtros</a>
            </form>
            <section class="colecao-produtos" aria-label="Produtos de <?= escapar($artista['nome']) ?>">
                <?php if (!$produtos): ?>
                    <div class="vazio">
                        <h2><?= !$itensArtista ? 'Esta coleção ainda não tem produtos' : 'Nenhum produto encontrado' ?></h2>
                        <p><?= !$itensArtista ? 'Novas peças aparecerão aqui quando forem cadastradas.' : 'Tente alterar ou limpar os filtros desta coleção.' ?></p>
                        <?php if ($itensArtista): ?><a class="btn btn--linha" href="artistas.php?artista=<?= (int) $id ?>">Limpar filtros</a><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="grade">
                        <?php foreach ($produtos as $produto): ?><?= posterProduto($produto) ?><?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
