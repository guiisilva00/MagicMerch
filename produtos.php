<?php
$tituloPagina = 'Produtos';
$paginaNavegacaoAtiva = 'Produtos';
require_once 'includes/header.php';

$filtros = [
    'busca' => trim($_GET['busca'] ?? ''),
    'categoria' => $_GET['categoria'] ?? '',
    'artista' => $_GET['artista'] ?? '',
    'preco_minimo' => $_GET['preco_minimo'] ?? '',
    'preco_maximo' => $_GET['preco_maximo'] ?? '',
    'disponibilidade' => $_GET['disponibilidade'] ?? '',
    'ordenacao' => $_GET['ordenacao'] ?? 'destaque',
];
$produtos = [];
$artistas = [];
$categorias = [];
$mensagemBancoDados = '';

if ($pdo === null) {
    $mensagemBancoDados = 'O catálogo ficará disponível depois da importação do banco de dados indicado no README.';
} else {
    $produtos = buscarProdutos($pdo, $filtros);
    $artistas = buscarArtistas($pdo);
    $categorias = buscarCategorias($pdo);
}
?>
<main class="container pagina">
    <header class="cabecalho-pagina">
        <h1>Produtos</h1>
        <p>Itens artesanais da cultura pop e dos seus fandoms favoritos.</p>
    </header>

    <details class="filtros-mobile" open>
        <summary><?= icone('busca') ?> Filtrar e ordenar</summary>
        <form class="barra-filtros" method="get">
            <div class="barra-filtros__linha">
                <label class="campo campo--busca">
                    <span>Buscar</span>
                    <input name="busca" type="search" value="<?= escapar($filtros['busca']) ?>"
                        placeholder="camiseta, caneca, artista…">
                </label>
            </div>
            <div class="barra-filtros__linha">
                <label class="campo">
                    <span>Categoria</span>
                    <select name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= escapar($c) ?>" <?= $filtros['categoria'] === $c ? 'selected' : '' ?>><?= escapar(ucfirst($c)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="campo">
                    <span>Artista ou grupo</span>
                    <select name="artista">
                        <option value="">Todos</option>
                        <?php foreach ($artistas as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= (string) $filtros['artista'] === (string) $a['id'] ? 'selected' : '' ?>><?= escapar($a['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="campo">
                    <span>Ordenar por</span>
                    <select name="ordenacao">
                        <option value="destaque">Destaque</option>
                        <option value="menor_preco" <?= $filtros['ordenacao'] === 'menor_preco' ? 'selected' : '' ?>>Menor preço</option>
                        <option value="maior_preco" <?= $filtros['ordenacao'] === 'maior_preco' ? 'selected' : '' ?>>Maior preço</option>
                        <option value="alfabetica" <?= $filtros['ordenacao'] === 'alfabetica' ? 'selected' : '' ?>>Alfabética</option>
                    </select>
                </label>
                <label class="campo">
                    <span>Preço mínimo</span>
                    <input name="preco_minimo" type="number" min="0" step="0.01" value="<?= escapar((string) $filtros['preco_minimo']) ?>">
                </label>
                <label class="campo">
                    <span>Preço máximo</span>
                    <input name="preco_maximo" type="number" min="0" step="0.01" value="<?= escapar((string) $filtros['preco_maximo']) ?>">
                </label>
                <label class="campo">
                    <span>Disponibilidade</span>
                    <select name="disponibilidade">
                        <option value="">Todos</option>
                        <option value="em_estoque" <?= $filtros['disponibilidade'] === 'em_estoque' ? 'selected' : '' ?>>Em estoque</option>
                        <option value="esgotados" <?= $filtros['disponibilidade'] === 'esgotados' ? 'selected' : '' ?>>Esgotados</option>
                    </select>
                </label>
            </div>
            <button class="btn btn--linha" type="submit">Aplicar filtros</button>
        </form>
    </details>

    <?php if ($mensagemBancoDados): ?>
        <p class="msg msg--aviso"><?= escapar($mensagemBancoDados) ?></p>
    <?php elseif (!$produtos): ?>
        <div class="vazio">
            <span class="vazio__inicial" aria-hidden="true">?</span>
            <h2>Nada por aqui</h2>
            <p>Nenhum produto corresponde a esses filtros. Tente ampliar a busca.</p>
            <a class="btn btn--linha" href="produtos.php">Limpar filtros</a>
        </div>
    <?php else: ?>
        <div class="grade" aria-label="Produtos encontrados">
            <?php foreach ($produtos as $produto): ?>
                <?php
                $cor = acentoPoster($produto['categoria'] ?? $produto['nome']);
                $esgotado = (int) $produto['estoque'] <= 0;
                $imgUrl = obterCaminhoImagem($produto['imagem'] ?? null, 'produtos', $produto['id']);
                $classeCampo = 'poster__campo' . ($imgUrl ? ' poster__campo--com-imagem' : '');
                ?>
                <a class="poster poster--c<?= $cor ?>" href="produto.php?id=<?= (int) $produto['id'] ?>">
                    <div class="<?= $classeCampo ?>">
                        <?php if ($imgUrl): ?><img src="<?= escapar($imgUrl) ?>" alt="<?= escapar($produto['nome']) ?>" class="poster__img"><?php else: ?><span class="poster__inicial" aria-hidden="true"><?= escapar(inicial($produto['nome_artista'] ?: $produto['nome'])) ?></span><span class="poster__nome"><?= escapar($produto['nome']) ?></span><?php endif; ?>
                        <?php if ($esgotado): ?><span class="poster__esgotado">Esgotado</span><?php endif; ?>
                    </div>
                    <div class="poster__meta"><span class="poster__meta-artista"><?= escapar($produto['nome_artista'] ?? '') ?></span><span class="poster__meta-nome"><?= escapar($produto['nome']) ?></span><span class="poster__meta-preco">R$ <?= number_format((float) $produto['preco'], 2, ',', '.') ?></span></div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php require 'includes/footer.php'; ?>
