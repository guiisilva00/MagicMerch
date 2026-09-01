<?php
$tituloPagina = 'Produtos';
$paginaNavegacaoAtiva = 'Produtos';
require_once 'includes/header.php';
require_once 'config/crud.php';

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

try {
    $conexao = criarConexaoBancoDados();
    $produtos = buscarProdutos($conexao, $filtros);
    $artistas = buscarArtistas($conexao);
    $categorias = buscarCategorias($conexao);
} catch (PDOException $erro) {
    $mensagemBancoDados = 'O catálogo ficará disponível depois da importação do banco de dados indicado no README.';
}
?>
<main class="container pagina-conteudo">
    <header class="cabecalho-pagina">
        <p class="rotulo-pagina">Catálogo</p>
        <h1>Produtos</h1>
        <p>Encontre itens artesanais da cultura pop e dos seus fandoms favoritos.</p>
    </header>

    <form class="formulario-filtros" method="get">
        <div class="campo-filtro campo-busca"><label for="busca">Buscar por nome ou palavra-chave</label><input
                id="busca" name="busca" type="search" value="<?php echo escaparHtml($filtros['busca']); ?>"
                placeholder="Ex.: camiseta, caneca ou artista"></div>
        <div class="campo-filtro"><label for="categoria">Categoria</label><select id="categoria" name="categoria">
                <option value="">Todas</option><?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo escaparHtml($categoria); ?>" <?php echo $filtros['categoria'] === $categoria ? 'selected' : ''; ?>><?php echo escaparHtml(ucfirst($categoria)); ?></option><?php endforeach; ?>
            </select></div>
        <div class="campo-filtro"><label for="artista">Artista ou grupo</label><select id="artista" name="artista">
                <option value="">Todos</option><?php foreach ($artistas as $artista): ?>
                    <option value="<?php echo $artista['id']; ?>" <?php echo (string) $filtros['artista'] === (string) $artista['id'] ? 'selected' : ''; ?>><?php echo escaparHtml($artista['nome']); ?></option>
                <?php endforeach; ?>
            </select></div>
        <div class="campo-filtro"><label for="preco_minimo">Preço mínimo</label><input id="preco_minimo"
                name="preco_minimo" type="number" min="0" step="0.01"
                value="<?php echo escaparHtml((string) $filtros['preco_minimo']); ?>"></div>
        <div class="campo-filtro"><label for="preco_maximo">Preço máximo</label><input id="preco_maximo"
                name="preco_maximo" type="number" min="0" step="0.01"
                value="<?php echo escaparHtml((string) $filtros['preco_maximo']); ?>"></div>
        <div class="campo-filtro"><label for="disponibilidade">Disponibilidade</label><select id="disponibilidade"
                name="disponibilidade">
                <option value="">Todos</option>
                <option value="em_estoque" <?php echo $filtros['disponibilidade'] === 'em_estoque' ? 'selected' : ''; ?>>
                    Em estoque</option>
                <option value="esgotados" <?php echo $filtros['disponibilidade'] === 'esgotados' ? 'selected' : ''; ?>>
                    Esgotados</option>
            </select></div>
        <div class="campo-filtro"><label for="ordenacao">Ordenar por</label><select id="ordenacao" name="ordenacao">
                <option value="destaque">Destaque</option>
                <option value="menor_preco" <?php echo $filtros['ordenacao'] === 'menor_preco' ? 'selected' : ''; ?>>Menor
                    preço</option>
                <option value="maior_preco" <?php echo $filtros['ordenacao'] === 'maior_preco' ? 'selected' : ''; ?>>Maior
                    preço</option>
                <option value="alfabetica" <?php echo $filtros['ordenacao'] === 'alfabetica' ? 'selected' : ''; ?>>
                    Alfabética</option>
            </select></div>
        <button class="btn-primary" type="submit">Aplicar filtros</button>
    </form>

    <?php if ($mensagemBancoDados): ?>
        <p class="mensagem-aviso"><?php echo $mensagemBancoDados; ?></p><?php elseif (!$produtos): ?>
        <p class="mensagem-aviso">Nenhum produto foi encontrado para os filtros selecionados.</p><?php else: ?>
        <section class="produtos-grid" aria-label="Produtos encontrados">
            <?php foreach ($produtos as $produto): ?>
                <article class="produto-card"><a href="produto.php?id=<?php echo $produto['id']; ?>" class="produto-link">
                        <div class="produto-imagem"><?php if ($produto['imagem']): ?><img
                                    src="<?php echo escaparHtml($produto['imagem']); ?>"
                                    alt="<?php echo escaparHtml($produto['nome']); ?>"><?php else: ?><span>Imagem do
                                    produto</span><?php endif; ?></div>
                        <div class="produto-informacoes">
                            <p class="produto-artista"><?php echo escaparHtml($produto['nome_artista']); ?></p>
                            <h2><?php echo escaparHtml($produto['nome']); ?></h2>
                            <p class="produto-preco">R$ <?php echo number_format((float) $produto['preco'], 2, ',', '.'); ?></p>
                            <p
                                class="produto-disponibilidade <?php echo $produto['estoque'] > 0 ? 'em-estoque' : 'esgotado'; ?>">
                                <?php echo $produto['estoque'] > 0 ? 'Em estoque' : 'Esgotado'; ?></p>
                        </div>
                    </a></article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>
<?php require_once 'includes/footer.php'; ?>