# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Usuário principal: **fã de artista/banda/cultura pop comprando merch**. Chega ao site
para navegar o catálogo por artista, ver um produto e comprar itens (camisetas,
moletons, canecas, pôsteres, acessórios, colecionáveis). Cria conta, mantém carrinho,
favoritos e histórico de pedidos.

Usuário secundário: **administrador da loja**, que gerencia produtos, estoque, pedidos
e relatórios pelo painel `admin/`.

## Product Purpose

Loja virtual (e-commerce) que vende merchandising de artistas e bandas. É um **MVP
acadêmico** — o objetivo é cumprir os requisitos do trabalho com código simples,
organizado e apresentável, e ao mesmo tempo servir de protótipo para testar a ideia
do negócio. Sucesso = requisitos atendidos + interface boa + código compreensível.

## Positioning

Catálogo organizado **por artista/banda**, com itens descritos como artesanais /
feitos à mão por artistas independentes e peças colecionáveis de edição limitada.
Programa de fidelidade simples (brinde a cada 10 itens comprados).

## Operating Context

- Roda localmente em XAMPP (Apache + MySQL); banco importado de `MM.sql`.
- Fluxos do cliente: catálogo com filtros → detalhe do produto → carrinho → checkout
  (entrega ou retirada; pagamento Pix/cartão simulado; frete fixo) → pedido no perfil.
- Fluxos do admin: login próprio → visão geral, CRUD de produtos, ajuste de estoque,
  mudança de status de pedidos, relatório por período.

## Capabilities and Constraints

Restrições **duráveis e vinculantes** (documento "Regras de Desenvolvimento do MagicMerch"):

- Stack: **PHP + HTML5 + CSS3 + MySQL**. **Sem JavaScript** nesta etapa — toda interação
  é link/âncora ou formulário POST; motion só via CSS.
- Todo acesso a banco passa pelo **CRUD** de `config/crud.php`
  (`create/readAll/read/update/delete/sanitizeIdentifier`). Proibido criar ORM,
  Query Builder, Repository, Service, DAO, novas camadas de persistência ou novas
  funções de banco.
- **Escopo fechado**: só implementar o que está na documentação/regras ou o que for
  explicitamente solicitado. Não inventar features comuns de e-commerce.
- **Estrutura de arquivos fixa**: páginas na raiz (`index`, `produtos`, `artistas`,
  `produto`, `login`, `perfil`, `carrinho`, `checkout`) + `admin/` + `includes/` +
  `config/` + `assets/css/{style.css,admin.css}` + `assets/img/`.
- Prioridade sempre: simplicidade → organização → legibilidade → funcionalidade.
- Bootstrap único em `config/app.php` (conexão `$pdo`, helpers, conteúdo estático).

Simulações (não são integrações reais): pagamento e recuperação de senha.

Terminologia do domínio: artista, produto, categoria, carrinho, pedido, fidelidade,
modalidade de entrega (entrega/retirada).

## Brand Commitments

- **Nome:** MagicMerch.
- **Paleta oficial (vinculante):** `--razzmatazz #e41169`, `--wild-strawberry #f25496`,
  `--pastel-petal #fac7dc`, `--vivid-orchid #ca53ba`, `--raspberry-plum #ac359c`.
- **Logo:** `assets/img/logo/logo.svg`.
- **Não vinculante / em aberto:** tipografia (hoje Playfair Display + Inter, pode mudar)
  e a direção visual. O time quer **testar uma pegada mais "street" e diferenciada**,
  menos séria/minimalista — a definição da identidade visual ainda não foi feita e será
  decidida no trabalho de design, não aqui.

## Evidence on Hand

- Dados de exemplo em `MM.sql` (4 artistas: The Beatles, Taylor Swift, BTS, Anime
  Classics; 10 produtos **sem imagem**; usuário admin e cliente de teste). São dados
  fictícios de trabalho acadêmico.
- `docs/estado-atual.md` — inventário do que já está construído.
- **Não há** clientes reais, depoimentos, métricas de venda, imprensa ou fotos de
  produto. Trabalho futuro não deve inventar nenhum desses.

## Product Principles

1. Fazer só o necessário, bem feito — nada além do escopo.
2. Reutilizar antes de criar (funções, componentes, classes CSS, páginas existentes).
3. O CRUD existente é o limite da interação com o banco.
4. Toda página deve parecer continuação natural da Home — um só sistema visual.
5. O código precisa ser compreensível e apresentável por um estudante.

## Accessibility & Inclusion

Nenhum requisito específico foi estabelecido. Manter o básico: HTML semântico,
`alt` em imagens, contraste adequado da paleta, navegação por teclado nos formulários.
