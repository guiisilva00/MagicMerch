    <footer class="rodape">
        <div class="container rodape__grid">
            <div class="rodape__col">
                <p class="rodape__marca">MagicMerch</p>
                <p>Merch feito à mão dos seus artistas e bandas favoritos. Peças únicas, edições limitadas.</p>
            </div>
            <div class="rodape__col">
                <h3>Navegar</h3>
                <a href="index.php">Início</a>
                <a href="produtos.php">Produtos</a>
                <a href="artistas.php">Artistas e bandas</a>
            </div>
            <div class="rodape__col">
                <h3>Conta</h3>
                <a href="<?= estaLogado() ? 'perfil.php' : 'login.php' ?>"><?= estaLogado() ? 'Minha conta' : 'Entrar' ?></a>
                <a href="carrinho.php">Carrinho</a>
                <p>Pagamento Pix e cartão (simulados neste MVP).</p>
            </div>
        </div>
        <div class="container">
            <p class="rodape__fim">&copy; <?= date('Y') ?> MagicMerch. Projeto acadêmico. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>
