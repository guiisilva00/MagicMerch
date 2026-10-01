# MagicMerch

Loja virtual de produtos relacionados a artistas e à cultura pop, desenvolvida como projeto acadêmico com PHP, MySQL, HTML e CSS. O sistema inclui uma área para clientes e um painel administrativo.

## Requisitos

- XAMPP (Apache e MySQL)
- PHP com PDO e suporte a MySQL
- Navegador web

## Instalação local

1. Coloque a pasta do projeto em `C:\xampp\htdocs\MagicMerch`.
2. Inicie Apache e MySQL pelo painel do XAMPP.
3. Abra o phpMyAdmin em `http://localhost/phpmyadmin` e importe o arquivo `MM.sql`. O script cria e popula o banco `magicmerch_db`.
4. Confira as credenciais em `config/database.php`. A configuração padrão usa `localhost`, banco `magicmerch_db`, usuário `root` e senha vazia, como na instalação padrão do XAMPP.
5. Acesse `http://localhost/MagicMerch/`.

## Acesso de demonstração

- Painel administrativo: `admin@magicmerch.local`
- Senha: `MagicMerch123!`
- Para testar como cliente, crie uma conta pela página de cadastro.

## Funcionalidades

- Catálogo com busca, filtros e ordenação; páginas de produto e artista.
- Cadastro, login, perfil e gerenciamento de endereços.
- Favoritos, avaliações e carrinho associado à conta.
- Checkout com entrega ou retirada, cálculo de frete e registro de pedidos.
- Área do cliente para acompanhar pedidos e fidelidade.
- Painel administrativo para consultar pedidos e gerenciar produtos, estoque e suporte.

## Estrutura principal

- `index.php`, `produtos.php`, `artistas.php` e demais páginas na raiz: experiência de compra.
- `admin/`: painel administrativo.
- `includes/`: cabeçalho, rodapé e componentes compartilhados.
- `config/`: conexão com banco, CRUD e funções comuns.
- `assets/`: folhas de estilo e imagens.
- `MM.sql`: estrutura e dados iniciais do banco.

## Observações

O pagamento e a recuperação de senha são simulações locais; o projeto não se conecta a gateways de pagamento nem envia e-mails. Os dados de exemplo são fictícios. A configuração padrão é voltada ao ambiente local do XAMPP e deve ser ajustada antes de qualquer hospedagem pública.
