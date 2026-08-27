# MagicMerch - Plataforma E-commerce (Projeto Acadêmico)

**MagicMerch** é um projeto acadêmico de uma plataforma de e-commerce voltada para a venda de produtos artesanais (hand-made) de cultura pop e fandom (cards, camisetas, moletons, acessórios, etc).

## Objetivo do Projeto
O objetivo atual é criar uma estruturação inicial, construindo o esqueleto visual e de diretórios da aplicação web. Trata-se de um projeto acadêmico focado em simplicidade, organização e boa apresentação.

## Tecnologias Utilizadas
Nesta fase inicial, o projeto conta com:
- **HTML5** (Estruturação das páginas)
- **CSS3** (Estilização com paleta de cores: Roxo, Rosa e Branco)
- **PHP** (Estrutura de arquivos e organização para futura integração)
- **MySQL** (Planejado para fases futuras - estruturação inicial de diretórios já feita)

*Nota: JavaScript não faz parte da implementação atual. Nenhuma funcionalidade avançada de backend ou banco de dados foi implementada.*

## Estrutura de Diretórios
A organização do projeto foi pensada para facilitar a manutenção e o entendimento acadêmico:

- `/` (Raiz): Contém as páginas públicas principais acessíveis pelo cliente (Home, Login, Produtos, Carrinho, etc).
- `/admin/`: Páginas do painel administrativo (Dashboard, Pedidos, Estoque, etc).
- `/assets/`: Arquivos estáticos.
  - `/css/`: Estilos da aplicação (público e admin).
  - `/img/`: Imagens (produtos, artistas, etc).
- `/config/`: Arquivos de configuração, como a futura conexão com o banco de dados (`database.php`).
- `/includes/`: Componentes PHP reutilizáveis (ex: `header.php`, `footer.php`).

## O que já foi criado nesta etapa
- Esqueleto de diretórios e pastas.
- Criação de todas as páginas `.php` necessárias para o cliente e para o painel de administração (apenas layout base e placeholders).
- Estruturação base de arquivos CSS com a paleta de cores WeVerse-style (Roxo, Rosa, Branco).
- Organização para recebimento futuro do backend e do banco de dados.

## Próximas Etapas
- Desenvolvimento do Frontend completo (HTML/CSS nas páginas atuais).
- Construção do banco de dados MySQL e tabelas.
- Integração e processamento de dados via PHP (Login, Cadastro, Produtos, Carrinho e Checkout).

```text
MagicMerch/
│
├── README.md                 (Documentação atualizada sobre o projeto e estrutura)
├── index.php                 (1. Home)
├── produtos.php              (2. Catálogo de Produtos)
├── artistas.php              (3. Artistas/Bandas)
├── produto.php               (4. Detalhe do Produto)
├── login.php                 (5. Login / Cadastro)
├── perfil.php                (6. Perfil do Usuário / Minha Conta)
├── carrinho.php              (7. Carrinho de Compras)
├── checkout.php              (8. Checkout / Finalização da Compra)
│
├── admin/                    (9. Painel Administrativo)
│   ├── index.php             (Dashboard)
│   ├── login.php             (Login exclusivo admin)
│   ├── produtos.php          (Gestão de Produtos)
│   ├── estoque.php           (Gestão de Estoque)
│   ├── pedidos.php           (Gestão de Pedidos)
│   └── relatorios.php        (Relatórios de vendas)
│
├── includes/                 (Componentes PHP Reutilizáveis)
│   ├── header.php            (Cabeçalho padrão cliente com menu)
│   └── footer.php            (Rodapé padrão)
│
├── config/                   (Configurações do backend)
│   └── database.php          (Placeholder para conexão futura com MySQL)
│
└── assets/                   (Arquivos estáticos do layout e imagens)
    ├── css/
    │   ├── style.css         (CSS principal - preparado com a paleta de cores documentada)
    │   └── admin.css         (CSS específico para o painel de administração)
    └── img/                  (Imagens)
        ├── produtos/
        └── artistas/
```