# MagicMerch — Estado atual do site

Panorama do que já existe, para apoiar as decisões de front-end. Reflete o código
após o refactor de conformidade (branch `refatoracao-conformidade`).

---

## 1. Páginas da loja (storefront)

| Página | O que faz hoje | Estado |
|---|---|---|
| `index.php` | Home: hero (2 destaques, só HTML/CSS com âncoras) + grade de artistas com contagem de produtos (dados reais do banco). | Funcional. Hero e artistas usam **blocos de cor** (sem imagem). |
| `produtos.php` | Catálogo: filtros (busca, categoria, artista, preço mín/máx, disponibilidade, ordenação) + grade de cards. | Funcional. Cards sem imagem (placeholder "Imagem do produto"). |
| `produto.php` | Detalhe: artista · categoria, preço, descrição, estoque, "Adicionar ao carrinho", "Salvar favorito", avaliações (form + lista). | Funcional. |
| `artistas.php` | Lista de artistas + nº de produtos, link para o catálogo filtrado. | Funcional. Layout básico (reaproveita `.produtos-grid`). |
| `login.php` | Entrar / Cadastrar / "Recuperar senha" (simulada). Logout por `?sair=1`. | Funcional. Recuperação é só uma mensagem. |
| `perfil.php` | Dados pessoais, fidelidade (a cada 10 itens comprados = brinde), endereços (adicionar/excluir), histórico de pedidos. | Funcional. |
| `carrinho.php` | Itens, ajustar quantidade, remover, subtotal, botão para o checkout. | Funcional. |
| `checkout.php` | Modalidade (entrega/retirada), endereço, pagamento (Pix/cartão **simulado**), resumo, confirma pedido e baixa estoque. | Funcional. Sem transação atômica (decisão consciente do refactor). |

## 2. Painel administrativo (`admin/`, visual à parte)

| Página | O que faz | Estado |
|---|---|---|
| `admin/login.php` | Login administrativo separado do login da loja. | Funcional. |
| `admin/index.php` | Visão geral: faturamento, nº de pedidos, itens vendidos. | Funcional. |
| `admin/produtos.php` | CRUD de produtos (formulário + tabela). | Funcional. |
| `admin/estoque.php` | Ajuste de estoque; destaca itens com ≤ 5 unidades. | Funcional. |
| `admin/pedidos.php` | Lista de pedidos; alterar status. | Funcional. |
| `admin/relatorios.php` | Filtro por período, cards de resumo, "mais vendidos". | Funcional. |

## 3. Componentes reutilizados

- `includes/header.php` — faixa de aviso, cabeçalho (logo, navegação, ícones de perfil/carrinho), mensagens flash.
- `includes/footer.php` — rodapé de uma linha.
- `includes/components/hero-banner.php`, `artists-grid.php` — blocos da Home.
- `includes/cabecalho-admin.php`, `rodape-admin.php` — moldura do admin.

## 4. Identidade visual atual

- **Paleta oficial** (agora em `:root` de `assets/css/style.css`):
  `--razzmatazz #e41169`, `--wild-strawberry #f25496`, `--pastel-petal #fac7dc`,
  `--vivid-orchid #ca53ba`, `--raspberry-plum #ac359c`. Rosa/magenta/orquídea.
- **Tipografia:** Playfair Display (títulos) + Inter (texto), via Google Fonts. **Não é fixa** — pode mudar.
- **Estética da loja:** clean, bastante minimalista, muito espaço em branco, cards de borda fina, botões "pill" outline, cabeçalho fixo. Faixa de aviso com gradiente da paleta.
- **Admin:** tema separado (navy `#172033`, fonte Arial) — hoje não conversa visualmente com a loja.
- **Imagens:** produtos não têm imagem (`imagem` NULL nos dados). Hero e artistas usam blocos de cor da paleta como placeholder.
- **Ícones:** caracteres/símbolos (`◯`, `▢`, setas `← → ↗`). Sem biblioteca de ícones.
- **Responsivo:** grades `auto-fit`, quebras em 700px e 1024px; o hero vira coluna única no mobile.

## 5. Dados e limitações

- Banco: importar `MM.sql`. Seeds: 4 artistas (The Beatles, Taylor Swift, BTS, Anime Classics),
  10 produtos **sem imagem**, 2 usuários (admin + cliente `joao@email.com`).
- Pagamento e recuperação de senha são **simulações locais** — sem integração externa.
- **Sem JavaScript.** Toda interação é link/âncora ou formulário POST.
- O texto de marketing que existia nos componentes (nomes como Matuê, Veigh) era fixo e foi
  removido — a Home agora mostra os artistas reais do banco.

## 6. Restrições que qualquer front-end novo precisa respeitar

Do documento "Regras de Desenvolvimento":

1. **Escopo fechado** — só o que está na documentação/regras; não inventar features.
2. **CRUD é a única interface de banco** (`config/crud.php`); nada de camadas novas.
3. **Sem JavaScript** nesta etapa.
4. **Estrutura de arquivos fixa** (páginas na raiz + `admin/` + `includes/` + `config/` + `assets/`).
5. **Paleta oficial obrigatória** e consistência visual entre todas as páginas (inclusive a Home).
6. Stack: PHP + HTML5 + CSS3 + MySQL.

## 7. Espaço aberto para o front-end

- Direção visual **ainda não definida** — a ideia é testar uma pegada mais "street" e
  diferenciada, menos séria/minimalista, mantendo a paleta e o logo.
- Livres para repensar: tipografia, layout dos cards, hero, tratamento de imagem/placeholder,
  densidade, motion (dentro do "sem JS": transições CSS), o visual do admin.
- Presos: as 5 cores, o nome, o logo, a ausência de JS, o conjunto de páginas.
