# DOCUMENTAÇÃO DO ESTADO ATUAL — MAGICMERCH

> **Manual Pessoal de Orientação e Panorama Geral do Projeto**  
> *Versão do Documento: 1.0 — Estado Real do Código (Setembro/2026)*

---

# 1. IDENTIFICAÇÃO DO PROJETO

* **Nome do Projeto:** MagicMerch
* **Tipo de Projeto:** Aplicação Web E-commerce (MVP Acadêmico)
* **Objetivo:** Servir como trabalho acadêmico de e-commerce e protótipo funcional para venda de merchandising (camisetas, moletons, canecas, pôsteres, acessórios e colecionáveis) de artistas, bandas e cultura pop.
* **Público / Segmento:** Fãs de música, bandas, cultura pop e animes interessados em itens artesanais, feitos à mão ou edições limitadas.
* **Tecnologias Utilizadas Atualmente:**
  * **Linguagem Server-Side:** PHP 8+ (Estrutural e Procedural com helpers).
  * **Banco de Dados:** MySQL / MariaDB (acesso via PDO em `config/database.php`).
  * **Markup e Estilização:** HTML5 semântico e CSS3 Vanilla (Design System customizado em `assets/css/style.css` e `assets/css/admin.css`).
  * **Servidor Local:** XAMPP (Apache + MySQL).
  * **Tipografia Externa:** Google Fonts (*Playfair Display* e *Inter* no storefront).
  * **Vetores:** SVG nativo (`assets/img/logo/logo.svg`).
* **Tecnologias Planejadas para Fases Futuras:**
  * **JavaScript (Vanilla/ES6):** Para validações no cliente, modais dinâmicos, carrinho lateral instantâneo e transições sem reload.
  * **Bibliotecas de Ícones:** Integração de pacote de ícones SVG/Font (ex.: Lucide ou FontAwesome).
  * **APIs de Terceiros:** Gateway de pagamento real (Stripe/MercadoPago), consulta de CEP (ViaCEP) e cálculo automatizado de frete (Correios/Melhor Envio).
  * **Serviço de E-mail (SMTP):** Para envio real de e-mails de confirmação e recuperação de senha.
* **Restrições Importantes do Projeto (Regras Vinculantes):**
  1. **Projeto Acadêmico de Escopo Fechado:** Não implementar recursos fora das especificações/documentação sem solicitação explícita.
  2. **Ausência de JavaScript no Estado Atual:** Todas as interações dependem exclusivamente de formulários HTML (POST/GET), links e âncoras. Transições são estritamente CSS.
  3. **CRUD Único de Persistência:** Todo o acesso ao banco de dados deve obrigatoriamente utilizar o conjunto de funções em `config/crud.php` (`create`, `readAll`, `read`, `update`, `delete`, `sanitizeIdentifier`). Proibido instanciar `PDO::prepare`/`query` diretamente nas páginas ou criar ORMs/DAOs/Services.
  4. **Estrutura de Arquivos Fixa:** Diretórios e páginas raízes pré-definidos (`index.php`, `produtos.php`, `produto.php`, `artistas.php`, `login.php`, `perfil.php`, `carrinho.php`, `checkout.php`, além das pastas `admin/`, `includes/`, `config/` e `assets/`).
  5. **Paleta de Cores Obrigatória:** Uso rigoroso das variáveis `:root` definidas na marca.
  6. **Simulação Local:** Recursos de pagamento e recuperação de senha funcionam de forma simulada/mockada no backend local.

---

# 2. ESTADO ATUAL DO PROJETO

## Resumo Geral do Status

* **Implementado e Funcional:**
  * Navegação completa da loja (Home, Catálogo, Detalhe do Produto, Lista de Artistas).
  * Filtros dinâmicos no catálogo (busca textual, categoria, artista, faixa de preço, disponibilidade e ordenações variadas).
  * Fluxo de autenticação de clientes (login, cadastro com validação de e-mail único, logout por query string e controle de sessão).
  * Painel de perfil do cliente (exibição de dados, sistema de fidelidade automático a cada 10 itens comprados, cadastro e exclusão de múltiplos endereços, histórico detalhado de pedidos).
  * Carrinho de compras com persistência no MySQL (adicionar item, alterar quantidade, remover item, cálculo automático de subtotal).
  * Checkout funcional com escolha de modalidade (entrega com frete fixo ou retirada na loja), seleção de endereço cadastrado, escolha de pagamento (Pix/Cartão simulados), confirmação de pedido, baixa automática no estoque de produtos e limpeza do carrinho.
  * Painel administrativo separado (`/admin/`) funcional com controle de acesso para administradores:
    * Login admin dedicado (`admin/login.php`).
    * Dashboard de visão geral com métricas (faturamento total, número de pedidos, itens vendidos) e atalhos (`admin/index.php`).
    * CRUD completo de produtos com formulário e listagem (`admin/produtos.php`).
    * Gestão e ajuste manual de estoque com destaque para itens críticos <= 5 unidades (`admin/estoque.php`).
    * Gerenciamento de status de pedidos (`admin/pedidos.php`).
    * Relatorios de vendas por período e ranking de mais vendidos (`admin/relatorios.php`).
* **Estrutura Preparada / Simulação:**
  * **Recuperação de Senha (`login.php`):** Formulário existente, porém apenas exibe uma mensagem simulada ("Se o e-mail estiver cadastrado, você receberá as instruções"), sem envio real de e-mail.
  * **Processamento de Pagamento (`checkout.php`):** Registra a opção (Pix ou Cartão) e marca o pagamento como confirmado automaticamente (`pagamento_confirmado = 1`), sem integração com gateway financeiro.
  * **Imagens de Produtos:** A coluna `imagem` existe na tabela `produtos`, porém todos os registros de teste possuem valor `NULL`. A interface possui fallback preparado exibindo pôsteres visuais gerados via CSS e iniciais do artista/produto.
* **Parcialmente Desenvolvido:**
  * **Gestão de Endereços:** Funcional para criação e deleção de endereços vinculados ao usuário, porém sem integração com API de CEP para autopreenchimento de logradouro/bairro/cidade.
  * **Ícones e Elementos Visuais:** Utilização de caracteres/símbolos Unicode (`◯`, `▢`, `←`, `→`, `↗`) e SVGs inline como placeholders estruturados (`includes/icones.php`), aguardando padronização visual final.
* **Não Implementado / Planejado:**
  * Interatividade dinâmica via JavaScript (carrinho lateral, envio de formulários via AJAX, modais de confirmação).
  * Upload direto de arquivos de imagem no formulário administrativo de produtos.
  * Integração com APIs externas de e-commerce (Correios, gateway de pagamento, emissão de nota fiscal).

---

# 3. ESTRUTURA COMPLETA DE DIRETÓRIOS E ARQUIVOS

```text
MagicMerch/
├── admin/
│   ├── estoque.php
│   ├── index.php
│   ├── login.php
│   ├── pedidos.php
│   ├── produtos.php
│   └── relatorios.php
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   └── style.css
│   └── img/
│       └── logo/
│           └── logo.svg
├── config/
│   ├── app.php
│   ├── crud.php
│   └── database.php
├── docs/
│   ├── estado-atual.md
│   └── guia-funcoes.md
├── includes/
│   ├── components/
│   │   ├── artists-grid.php
│   │   └── hero-banner.php
│   ├── cabecalho-admin.php
│   ├── footer.php
│   ├── header.php
│   ├── icones.php
│   ├── poster.php
│   └── rodape-admin.php
├── sync/
│   └── README.md                       # Documentação técnica do sistema de referência SYNC
├── .agents/
├── .claude/
├── .codex/
├── .gitignore
├── .impeccable/
├── DOCUMENTACAO_ESTADO_ATUAL.md (ESTE ARQUIVO)
├── NIVELAMENTO_TECNICO.md (Manual e Contrato de Nivelamento Técnico)
├── MM.sql
├── PRODUCT.md
├── README.md
├── artistas.php
├── carrinho.php
├── checkout.php
├── index.php
├── login.php
├── perfil.php
├── produto.php
├── produtos.php
└── skills-lock.json
```

---

## Detalhamento dos Arquivos

### Arquivos da Raiz (Páginas Públicas e Raiz do Projeto)

1. **`index.php`**
   * **Localização:** Raiz (`/index.php`)
   * **Finalidade:** Página inicial da loja (Home).
   * **Estado:** **Implementado**
   * **Conteúdo:** Banner Hero com carrossel/cards estáticos (`includes/components/hero-banner.php`), grade de artistas dinâmicos com contagem de produtos (`includes/components/artists-grid.php`) e destaques da loja.
   * **Dependências:** `config/app.php`, `includes/header.php`, `includes/footer.php`, `includes/components/hero-banner.php`, `includes/components/artists-grid.php`.

2. **`produtos.php`**
   * **Localização:** Raiz (`/produtos.php`)
   * **Finalidade:** Catálogo geral de produtos com sistema de filtros e busca.
   * **Estado:** **Implementado**
   * **Conteúdo:** Formulário lateral/superior de filtros (busca por termo, artista, categoria, faixa de preço, estoque e ordenação) e grade de cards de produtos.
   * **Dependências:** `config/app.php`, `includes/header.php`, `includes/footer.php`, `includes/poster.php`.

3. **`produto.php`**
   * **Localização:** Raiz (`/produto.php`)
   * **Finalidade:** Página de detalhes de um produto específico.
   * **Estado:** **Implementado**
   * **Conteúdo:** Informações completas do produto (nome, artista, categoria, preço, estoque, opções de cor/tamanho), formulário para adicionar ao carrinho, botão de favoritar e seção de avaliações (formulário de nova avaliação + lista de comentários existentes).
   * **Dependências:** `config/app.php`, `includes/header.php`, `includes/footer.php`, `includes/poster.php`.

4. **`artistas.php`**
   * **Localização:** Raiz (`/artistas.php`)
   * **Finalidade:** Listagem de todos os artistas e bandas cadastrados.
   * **Estado:** **Implementado**
   * **Conteúdo:** Grade de artistas com nome, descrição, quantidade de produtos vinculados e link direto para o catálogo filtrado pelo artista.
   * **Dependências:** `config/app.php`, `includes/header.php`, `includes/footer.php`.

5. **`login.php`**
   * **Localização:** Raiz (`/login.php`)
   * **Finalidade:** Central de acesso do cliente (Login, Cadastro e Simulação de Recuperação de Senha).
   * **Estado:** **Implementado** (Recuperação de senha simulada)
   * **Conteúdo:** Formulários em abas/seções para autenticação de usuário existente, cadastro de nova conta cliente e formulário de recuperação de senha. Suporta encerramento de sessão via `?sair=1`.
   * **Dependências:** `config/app.php`, `includes/header.php`, `includes/footer.php`.

6. **`perfil.php`**
   * **Localização:** Raiz (`/perfil.php`)
   * **Finalidade:** Área logada da conta do cliente ("Minha Conta").
   * **Estado:** **Implementado**
   * **Conteúdo:** Resumo dos dados do usuário, barra/card de fidelidade (recompensa por itens comprados), gerenciamento de endereços de entrega (adicionar/excluir) e histórico completo de pedidos realizados com status e itens.
   * **Dependências:** `config/app.php`, `includes/header.php`, `includes/footer.php`.

7. **`carrinho.php`**
   * **Localização:** Raiz (`/carrinho.php`)
   * **Finalidade:** Gestão do carrinho de compras do usuário.
   * **Estado:** **Implementado**
   * **Conteúdo:** Tabela/lista de itens adicionados ao carrinho, controle de quantidade por formulário, remoção de itens, cálculo do subtotal e botão de prosseguir para o checkout.
   * **Dependências:** `config/app.php`, `includes/header.php`, `includes/footer.php`, `includes/poster.php`.

8. **`checkout.php`**
   * **Localização:** Raiz (`/checkout.php`)
   * **Finalidade:** Finalização do pedido e pagamento.
   * **Estado:** **Implementado** (Pagamento simulado)
   * **Conteúdo:** Seleção da modalidade (entrega com frete fixo calculando por estado ou retirada), seleção do endereço de entrega, escolha da forma de pagamento (Pix ou Cartão), resumo final do pedido e processamento que cria o registro em `pedidos` / `itens_pedido`, deduz o estoque em `produtos` e limpa a tabela `carrinho`.
   * **Dependências:** `config/app.php`, `includes/header.php`, `includes/footer.php`.

---

### Diretório `admin/` (Painel Administrativo)

9. **`admin/login.php`**
   * **Localização:** `/admin/login.php`
   * **Finalidade:** Autenticação exclusiva de administradores.
   * **Estado:** **Implementado**
   * **Conteúdo:** Formulário de login separado que valida se o usuário possui `tipo = 'administrador'`.
   * **Dependências:** `config/app.php`, `includes/cabecalho-admin.php`, `includes/rodape-admin.php`.

10. **`admin/index.php`**
    * **Localização:** `/admin/index.php`
    * **Finalidade:** Dashboard / Visão geral administrativa.
    * **Estado:** **Implementado**
    * **Conteúdo:** Indicadores principais (faturamento total acumulado, total de pedidos realizados, volume de itens vendidos) e atalhos de navegação interna do admin.
    * **Dependências:** `config/app.php`, `includes/cabecalho-admin.php`, `includes/rodape-admin.php`.

11. **`admin/produtos.php`**
    * **Localização:** `/admin/produtos.php`
    * **Finalidade:** CRUD (Gerenciamento) de produtos.
    * **Estado:** **Implementado**
    * **Conteúdo:** Formulário para inclusão/edição de produtos (nome, descrição, preço, artista, categoria, estoque, atributos de cor/tamanho, destaque) e tabela de listagem com ações.
    * **Dependências:** `config/app.php`, `includes/cabecalho-admin.php`, `includes/rodape-admin.php`.

12. **`admin/estoque.php`**
    * **Localização:** `/admin/estoque.php`
    * **Finalidade:** Controle rápido e reabastecimento de estoque.
    * **Estado:** **Implementado**
    * **Conteúdo:** Tabela concentrada em quantidades de estoque, destaque visual automático para produtos com baixo estoque (<= 5 unidades) e formulário para atualização rápida da quantidade.
    * **Dependências:** `config/app.php`, `includes/cabecalho-admin.php`, `includes/rodape-admin.php`.

13. **`admin/pedidos.php`**
    * **Localização:** `/admin/pedidos.php`
    * **Finalidade:** Gestão dos pedidos efetuados na loja.
    * **Estado:** **Implementado**
    * **Conteúdo:** Listagem de todos os pedidos dos clientes, exibição dos itens e dados do comprador, e formulário para alteração do status do pedido (`aguardando_pagamento`, `pagamento_confirmado`, `em_producao_separacao`, `enviado`, `concluido`).
    * **Dependências:** `config/app.php`, `includes/cabecalho-admin.php`, `includes/rodape-admin.php`.

14. **`admin/relatorios.php`**
    * **Localização:** `/admin/relatorios.php`
    * **Finalidade:** Relatórios gerenciais e de vendas.
    * **Estado:** **Implementado**
    * **Conteúdo:** Filtro de relatórios por período, métricas de vendas consolidadas e ranking dos produtos mais vendidos.
    * **Dependências:** `config/app.php`, `includes/cabecalho-admin.php`, `includes/rodape-admin.php`.

---

### Diretório `config/` (Configurações e Backend)

15. **`config/database.php`**
    * **Localização:** `/config/database.php`
    * **Finalidade:** Conexão direta com o banco de dados via PDO (instancia $pdo ou encerra a execução com die() em erro).
    * **Estado:** **Implementado**
    * **Conteúdo:** Instanciação direta do PDO com DSN (`mysql:host=localhost;dbname=magicmerch_db;charset=utf8mb4`), usuário `root` e senha vazia, configurado com `ERRMODE_EXCEPTION` e `FETCH_ASSOC`.
    * **Dependências:** Nenhuma.

16. **`config/crud.php`**
    * **Localização:** `/config/crud.php`
    * **Finalidade:** Camada única e obrigatória de abstração de dados (CRUD).
    * **Estado:** **Implementado**
    * **Conteúdo:** Funções genéricas `create()`, `readAll()`, `read()`, `update()`, `delete()` e `sanitizeIdentifier()`.
    * **Dependências:** PDO.

17. **`config/app.php`**
    * **Localização:** `/config/app.php`
    * **Finalidade:** Bootstrap da aplicação, inicialização de sessão, inclusão da variável global `$pdo`, helpers de autenticação/navegação/formatos e funções de consulta de domínio.
    * **Estado:** **Implementado**
    * **Conteúdo:**
      * Inicialização de `session_start()`.
      * Inclusão do `$pdo` já conectado via `require_once 'database.php'`.
      * Helpers gerais (`escapar`, `usuarioAtual`, `estaLogado`, `eAdministrador`, `redirecionar`, `exigirLogin`, `exigirAdministrador`, `definirFlash`, `lerFlash`, `valorMoeda`).
      * Helpers visuais de pôster (`acentoPoster`, `inicial`).
      * Consultas reutilizáveis de domínio sobre o CRUD (`indexarPorId`, `quantidadeCarrinho`, `itensCarrinho`, `subtotalCarrinho`, `buscarArtistas`, `buscarCategorias`, `buscarProdutoPorId`, `buscarProdutos`).
      * Regras de negócio (`calcularFrete`, `statusPedido`).
      * Arrays estáticos de navegação (`$linksNavegacao`) e destaques (`$slidesDestaque`).
    * **Dependências:** `config/database.php`, `config/crud.php`.

---

### Diretório `includes/` (Componentes e Fragmentos Reutilizáveis)

18. **`includes/header.php`**
    * **Localização:** `/includes/header.php`
    * **Finalidade:** Topo padrão da loja pública.
    * **Estado:** **Implementado**
    * **Conteúdo:** Barra superior de avisos, logo SVG, menu de navegação dinâmico, ícones de perfil/carrinho (com contador de itens) e renderização de mensagens *flash*.
    * **Dependências:** `config/app.php`, `includes/icones.php`.

19. **`includes/footer.php`**
    * **Localização:** `/includes/footer.php`
    * **Finalidade:** Rodapé padrão da loja pública.
    * **Estado:** **Implementado**
    * **Conteúdo:** Faixa de direitos autorais e encerramento da estrutura HTML.
    * **Dependências:** Nenhuma.

20. **`includes/cabecalho-admin.php`**
    * **Localização:** `/includes/cabecalho-admin.php`
    * **Finalidade:** Topo do painel administrativo.
    * **Estado:** **Implementado**
    * **Conteúdo:** Barra de navegação do admin com atalhos para Dashboard, Produtos, Estoque, Pedidos, Relatórios, link para retornar à loja e ação de sair.
    * **Dependências:** `config/app.php`.

21. **`includes/rodape-admin.php`**
    * **Localização:** `/includes/rodape-admin.php`
    * **Finalidade:** Rodapé do painel administrativo.
    * **Estado:** **Implementado**
    * **Conteúdo:** Encerramento das tags da estrutura administrativa.
    * **Dependências:** Nenhuma.

22. **`includes/icones.php`**
    * **Localização:** `/includes/icones.php`
    * **Finalidade:** Helper de renderização de ícones.
    * **Estado:** **Implementado**
    * **Conteúdo:** Função `icone($nome)` que retorna caracteres Unicode estilizados ou pequenos trechos SVG inline para simular ícones de sacola, perfil, busca, etc.
    * **Dependências:** Nenhuma.

23. **`includes/poster.php`**
    * **Localização:** `/includes/poster.php`
    * **Finalidade:** Renderizador de placeholder visual para produtos sem foto.
    * **Estado:** **Implementado**
    * **Conteúdo:** Função `renderizarPosterProduto($produto)` que gera um bloco estilizado em CSS utilizando as cores da paleta e as iniciais do artista/produto quando `imagem` for `NULL`.
    * **Dependências:** `config/app.php`.

24. **`includes/components/hero-banner.php`**
    * **Localização:** `/includes/components/hero-banner.php`
    * **Finalidade:** Componente visual de destaque da Home.
    * **Estado:** **Implementado**
    * **Conteúdo:** Banner principal com chamadas de coleções, subtítulos e cartões visuais estáticos baseados em `$slidesDestaque`.
    * **Dependências:** `$slidesDestaque` em `config/app.php`.

25. **`includes/components/artists-grid.php`**
    * **Localização:** `/includes/components/artists-grid.php`
    * **Finalidade:** Componente da Home para exibição de artistas.
    * **Estado:** **Implementado**
    * **Conteúdo:** Renderizador da grade de artistas da Home apresentando o nome, inicial e total de produtos vinculados.
    * **Dependências:** `config/app.php`.

---

### Diretório `assets/` (Estilos, Imagens e Recursos Estáticos)

26. **`assets/css/style.css`**
    * **Localização:** `/assets/css/style.css`
    * **Finalidade:** Folha de estilos principal da loja pública.
    * **Estado:** **Implementado**
    * **Conteúdo:** Definições `:root` com as 5 cores oficiais da marca, tipografia Google Fonts (*Playfair Display* e *Inter*), resets CSS, estilos de componentes (botões pill, cards, cabeçalho fixo, formulários, tabelas, posters visuais) e regras de responsividade (`@media`).
    * **Dependências:** Nenhuma.

27. **`assets/css/admin.css`**
    * **Localização:** `/assets/css/admin.css`
    * **Finalidade:** Folha de estilos exclusiva do painel administrativo.
    * **Estado:** **Implementado**
    * **Conteúdo:** Estilização própria para o admin (tema em tons navy `#172033` e tipografia Arial), tabelas densas, formulários de edição, cards de métricas e status badges.
    * **Dependências:** Nenhuma.

28. **`assets/img/logo/logo.svg`**
    * **Localização:** `/assets/img/logo/logo.svg`
    * **Finalidade:** Logotipo vetorial oficial do MagicMerch.
    * **Estado:** **Implementado**
    * **Conteúdo:** Arquivo SVG contendo o design oficial do logotipo da marca.
    * **Dependências:** Nenhuma.

---

### Arquivos de Banco de Dados e Documentação

29. **`MM.sql`**
    * **Localização:** Raiz (`/MM.sql`)
    * **Finalidade:** Script de criação do banco de dados e inserção de dados de teste (*seeds*).
    * **Estado:** **Implementado**
    * **Conteúdo:** Criação do banco `magicmerch_db` (utf8mb4), 9 tabelas relacionais (`usuarios`, `artistas`, `produtos`, `enderecos`, `favoritos`, `avaliacoes`, `carrinho`, `pedidos`, `itens_pedido`) e inserts iniciais de teste (2 usuários, 4 artistas e 10 produtos).
    * **Dependências:** MySQL.

30. **`PRODUCT.md`**
    * **Localização:** Raiz (`/PRODUCT.md`)
    * **Finalidade:** Documento de visão do produto, público-alvo, diretrizes e restrições técnicas.
    * **Estado:** **Implementado**
    * **Conteúdo:** Texto em markdown descrevendo o propósito do e-commerce acadêmico.

31. **`README.md`**
    * **Localização:** Raiz (`/README.md`)
    * **Finalidade:** Instruções rápidas de instalação e uso local.
    * **Estado:** **Implementado**
    * **Conteúdo:** Passo a passo para rodar no XAMPP, dados de acesso admin de teste e visão geral dos recursos.

32. **`docs/estado-atual.md`**
    * **Localização:** `/docs/estado-atual.md`
    * **Finalidade:** Documentação legada curta referente ao panorama do front-end pós-refatoração.
    * **Estado:** **Implementado** (Mantido no repositório)

33. **`docs/guia-funcoes.md`**
    * **Localização:** `/docs/guia-funcoes.md`
    * **Finalidade:** Guia rápido de referência das funções PHP do sistema.
    * **Estado:** **Implementado** (Mantido no repositório)

34. **`NIVELAMENTO_TECNICO.md`**
    * **Localização:** Raiz (`/NIVELAMENTO_TECNICO.md`)
    * **Finalidade:** Documento e contrato de nivelamento técnico de complexidade do MagicMerch tendo o projeto SYNC Mecatronics como referência.
    * **Estado:** **Implementado**
    * **Conteúdo:** Diagnóstico geral, matriz comparativa SYNC × MagicMerch, lista de excessos, plano de refatorações cirúrgicas (D1 a D5), teto de complexidade (F) e regras complementares de nivelamento (H1 a H4).

35. **`sync/README.md`**
    * **Localização:** `/sync/README.md`
    * **Finalidade:** Documentação técnica oficial do sistema SYNC Mecatronics (sistema de referência).
    * **Estado:** **Implementado** (Fonte primária de benchmark)
    * **Conteúdo:** Visão geral, arquitetura monolítica procedural, CRUD de 6 funções, fluxos de contratação e avaliação de simplicidade.

---

# 4. PÁGINAS DO CLIENTE

| Página | Arquivo | Objetivo da Página | Elementos Existentes | Funcionalidades Existentes | Componentes Reutilizados | CSS Utilizado | Estado Atual |
|---|---|---|---|---|---|---|---|
| **Início (Home)** | `index.php` | Apresentar a marca, coleções em destaque e bandas/artistas principais. | Hero banner com chamadas, grade de artistas com total de produtos, seção de benefícios. | Navegação por âncoras, links diretos para o catálogo filtrado por artista. | `header.php`, `footer.php`, `hero-banner.php`, `artists-grid.php` | `assets/css/style.css` | ✅ Implementado |
| **Produtos (Catálogo)** | `produtos.php` | Listar e filtrar todo o catálogo de produtos da loja. | Barra/Formulário de filtros (busca, categoria, artista, faixa de preço, estoque, ordenação), grade de produtos. | Filtro dinâmico via parâmetros `GET`, ordenação por preço/nome/destaque, exibição de poster visual caso sem foto. | `header.php`, `footer.php`, `poster.php` | `assets/css/style.css` | ✅ Implementado |
| **Detalhes do Produto** | `produto.php` | Exibir informações completas de um item e permitir adição ao carrinho / avaliação. | Banner do produto (poster visual), especificações (cor/tamanho), estoque, formulário de quantidade, botão de favoritos, lista e formulário de avaliações. | Adicionar item ao carrinho (POST), alternar favorito, enviar nota (1 a 5) e comentário de avaliação (salva no banco se logado). | `header.php`, `footer.php`, `poster.php` | `assets/css/style.css` | ✅ Implementado |
| **Artistas e Bandas** | `artistas.php` | Exibir todos os artistas cadastrados na plataforma. | Grade de cards de artistas com inicial, nome, descrição e contagem de itens. | Link direto para o catálogo com pré-filtro do artista selecionado. | `header.php`, `footer.php` | `assets/css/style.css` | ✅ Implementado |
| **Login / Acesso** | `login.php` | Central de autenticação e cadastro de novos clientes. | Formulários de login, cadastro de novos usuários e simulação de recuperação de senha. | Autenticação via e-mail/senha, verificação de e-mail duplicado, hashing de senha (`password_hash`), logout via `?sair=1`. | `header.php`, `footer.php` | `assets/css/style.css` | 🟡 Parcial (Senha simulada) |
| **Minha Conta** | `perfil.php` | Exibir o painel do cliente logado. | Cartão de perfil, widget de programa de fidelidade (brinde a cada 10 itens), formulário de novos endereços, lista de endereços, lista de pedidos anteriores. | Adicionar/Excluir endereços, visualizar histórico de compras com detalhamento de itens e status. | `header.php`, `footer.php` | `assets/css/style.css` | ✅ Implementado |
| **Carrinho** | `carrinho.php` | Exibir os itens selecionados para compra e subtotais. | Tabela/Lista de produtos no carrinho, controles de quantidade, botões de exclusão, resumo de valores. | Atualizar quantidade via POST, remover item, calcular subtotal acumulado em PHP, redirecionar para o checkout. | `header.php`, `footer.php`, `poster.php` | `assets/css/style.css` | ✅ Implementado |
| **Checkout** | `checkout.php` | Finalizar a compra, selecionar frete, endereço e forma de pagamento. | Seleção de modalidade (entrega/retirada), seletor de endereço, seleção de pagamento (Pix/Cartão), resumo de custos. | Cálculo de frete (R$ 10 para SP / R$ 20 outros estados / R$ 0 retirada), criação de registro na tabela `pedidos` e `itens_pedido`, baixa automática do estoque, limpeza do carrinho. | `header.php`, `footer.php` | `assets/css/style.css` | 🟡 Parcial (Pagamento simulado) |

---

# 5. PAINEL ADMINISTRATIVO

Todas as páginas administrativas estão localizadas dentro do diretório `/admin/` e possuem restrição de acesso por sessão (`exigirAdministrador()`).

| Página | Arquivo | Função da Página | Elementos Presentes | Funcionalidades Atualmentes | Funcionalidades Planejadas / Faltantes | Estado de Implementação |
|---|---|---|---|---|---|---|
| **Login Admin** | `admin/login.php` | Autenticar administradores da plataforma. | Formulário de login com e-mail e senha. | Validação de credenciais e checagem de perfil `tipo = 'administrador'`. | Recuperação de senha administrativa dedicada. | ✅ Implementado |
| **Dashboard** | `admin/index.php` | Visão geral do desempenho da loja. | Cards de métricas (faturamento total, nº de pedidos, itens vendidos) e atalhos rápidos. | Consulta agregada de dados reais no MySQL para exibição de indicadores operacionais. | Gráficos dinâmicos de vendas por período. | ✅ Implementado |
| **CRUD de Produtos** | `admin/produtos.php` | Gerenciar o catálogo de produtos (cadastrar, editar, listar). | Formulário de criação/edição e tabela de listagem de produtos com ações. | Inclusão de produtos no BD, alteração de dados (nome, preço, artista, estoque, destaques), desativação/exclusão. | Upload físico de imagens via formulário multipart. | 🟡 Parcial (Upload de imagem não integrado) |
| **Ajuste de Estoque** | `admin/estoque.php` | Gerenciamento focado do inventário. | Tabela de produtos com coluna de estoque destacada e formulário de atualização rápida. | Destaque visual automático para produtos com baixo estoque (<= 5 unidades), atualização instantânea da quantidade. | Histórico/Log de movimentações de estoque. | ✅ Implementado |
| **Gestão de Pedidos** | `admin/pedidos.php` | Acompanhar e atualizar pedidos dos clientes. | Lista completa de pedidos com dados do cliente, modalidade, valor total e seletor de status. | Alteração do status do pedido (`aguardando_pagamento` -> `concluido`) refletindo no perfil do cliente. | Emissão de etiqueta de envio ou código de rastreio. | ✅ Implementado |
| **Relatórios** | `admin/relatorios.php` | Analisar vendas e desempenho por período. | Filtro por datas, resumos de faturamento e tabela dos itens mais vendidos. | Filtragem de pedidos finalizados por intervalo de datas e ordenação de produtos por volume de vendas. | Exportação de dados para CSV/PDF. | ✅ Implementado |

---

# 6. COMPONENTES REUTILIZÁVEIS

1. **`includes/header.php`**
   * **Responsabilidade:** Renderizar a barra superior de anúncios, a logomarca oficial, o menu principal da loja, as ações do usuário (Login/Perfil) e o ícone do carrinho com a contagem de itens em tempo real. Exibe mensagens de notificação (*flash messages*).
   * **Uso:** Todas as páginas da área pública.
2. **`includes/footer.php`**
   * **Responsabilidade:** Renderizar a barra inferior com os direitos autorais e fechar as estruturas das tags `</body></html>`.
   * **Uso:** Todas as páginas da área pública.
3. **`includes/cabecalho-admin.php`**
   * **Responsabilidade:** Prover o menu superior unificado do painel administrativo, exibindo o usuário conectado e os links de navegação interna.
   * **Uso:** Todas as páginas dentro de `/admin/`.
4. **`includes/rodape-admin.php`**
   * **Responsabilidade:** Fechar as estruturas de tags HTML das páginas administrativas.
   * **Uso:** Todas as páginas dentro de `/admin/`.
5. **`includes/components/hero-banner.php`**
   * **Responsabilidade:** Exibir os blocos visuais de chamada principal da Home com base nas informações do array `$slidesDestaque`.
   * **Uso:** `index.php`.
6. **`includes/components/artists-grid.php`**
   * **Responsabilidade:** Renderizar a grade de artistas com iniciais em formato de pôster e contadores dinâmicos de produtos.
   * **Uso:** `index.php`.
7. **`includes/poster.php`**
   * **Responsabilidade:** Função `renderizarPosterProduto()` para gerar elementos gráficos via CSS substituindo capas/fotos de produtos ausentes (`imagem` `NULL`).
   * **Uso:** `produtos.php`, `produto.php`, `carrinho.php`.
8. **`includes/icones.php`**
   * **Responsabilidade:** Centralizar a renderização de símbolos e ícones SVG/Unicode através da função `icone($nome)`.
   * **Uso:** `header.php` e componentes diversos.

---

# 7. CSS E IDENTIDADE VISUAL

## Arquivos CSS Existentes
* **`assets/css/style.css`:** CSS da loja pública.
* **`assets/css/admin.css`:** CSS exclusivo da área administrativa.

## Paleta de Cores Oficial (Definida em `:root` de `assets/css/style.css`)
```css
:root {
  --razzmatazz: #e41169ff;
  --wild-strawberry: #f25496ff;
  --pastel-petal: #fac7dcff;
  --vivid-orchid: #ca53baff;
  --raspberry-plum: #ac359cff;
}
```

## Diretriz Visual e Tipografia
* **Loja Pública:**
  * **Tipografia:** Google Fonts — *Playfair Display* (Títulos/Headings) + *Inter* (Corpo, UI e botões).
  * **Estética:** Clean, minimalista, uso abundante de espaços em branco, cards com bordas suaves e finas, botões com cantos arredondados no estilo *pill* (outline/solid).
  * **Imagens / Placeholders:** Como os produtos não possuem arquivos de imagem vinculados no banco, o sistema utiliza blocos de cores gerados dinamicamente via CSS com base nas variáveis da paleta e a inicial do nome.
* **Painel Administrativo:**
  * **Estilo Separado:** Tema escuro utilitário em tom azul-marinho (`#172033`), tipografia legível *Arial / sans-serif*, tabelas de alta densidade e destaques em verde/amarelo para status de pedidos e alertas de estoque.
* **Comportamento Responsivo:**
  * Layout flexível com CSS Grid e Flexbox.
  * Grades configuradas com `repeat(auto-fit, minmax(..., 1fr))`.
  * Breakpoints principais em `700px` (mobile/tablet) e `1024px` (desktop), convertendo o hero e formulários para coluna única em telas menores.

---

# 8. PHP E ESTRUTURA DE BACKEND

## Arquitetura PHP Existente

O backend do MagicMerch é **estrutural e procedural**, focado em simplicidade para escopo acadêmico:

1. **Bootstrap e Helpers (`config/app.php`):**
   * Gerencia sessões PHP (`$_SESSION`).
   * Estabelece a conexão global `$pdo`.
   * Fornece funções auxiliares de sanitização (`escapar()`), redirecionamento (`redirecionar()`), mensagens temporárias (`definirFlash()`, `lerFlash()`) e formatação monetária em Reais (`valorMoeda()`).
   * Contém a lógica de verificação de autenticação (`estaLogado()`, `eAdministrador()`, `exigirLogin()`, `exigirAdministrador()`).
2. **Camada Única de Dados (`config/crud.php`):**
   * Todas as operações de leitura e escrita no banco de dados passam exclusivamente por 5 funções genéricas que utilizam PDO Prepared Statements:
     * `create($pdo, $table, $data)`
     * `readAll($pdo, $table, $where, $params)`
     * `read($pdo, $table, $where, $params)`
     * `update($pdo, $table, $data, $where, $params)`
     * `delete($pdo, $table, $where, $params)`
     * `sanitizeIdentifier($identifier)`
   * **Sem JOINs SQL complexos:** O sistema lê dados das tabelas primárias via CRUD e realiza o cruzamento de dados (relacionamentos) em nível de PHP usando a função `indexarPorId()` e manipulação de arrays.
3. **Funções de Domínio:**
   * Montagem flexível de filtros no catálogo (`buscarProdutos()`).
   * Cálculo de subtotal e contagem de itens do carrinho (`quantidadeCarrinho()`, `subtotalCarrinho()`).
   * Regra de frete estática (`calcularFrete()`).

## Diferenciação Clara: PHP Estrutural vs. Backend Não Implementado

* **PHP Estrutural/Organizacional EXISTENTE:** Processamento nativo de formulários POST, gerenciamento de sessões, renderização condicional de componentes, consultas CRUD e regras de negócio calculadas no servidor.
* **Backend Funcional NÃO IMPLEMENTADO:** Não existem serviços de background, envio real de e-mails (SMTP), APIs REST/JSON expostas, chamadas cURL para gateways externos ou uploads dinâmicos de mídia.

---

# 9. BANCO DE DADOS

## Estado Real do Banco de Dados

* **Conexão PDO:** Definida em `config/database.php` (Conecta ao MySQL em `localhost` no banco `magicmerch_db` com usuário `root` sem senha).
* **Script SQL de Origem:** Arquivo `MM.sql` localizado na raiz.
* **Tabelas Existentes no Banco (9 Tabelas):**
  1. `usuarios`: Cadastro de clientes e administradores (`id`, `nome`, `email`, `senha`, `telefone`, `tipo`, `data_cadastro`).
  2. `artistas`: Lista de artistas e bandas (`id`, `nome`, `descricao`).
  3. `produtos`: Itens do catálogo (`id`, `nome`, `descricao`, `preco`, `artista_id`, `categoria`, `estoque`, `imagem`, `cor`, `tamanho`, `destaque`, `vendas`).
  4. `enderecos`: Endereços dos clientes (`id`, `usuario_id`, `apelido`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `principal`).
  5. `favoritos`: Relação de produtos salvos (`usuario_id`, `produto_id`, `data_adicao`).
  6. `avaliacoes`: Notas e depoimentos (`id`, `usuario_id`, `produto_id`, `nota`, `comentario`, `data_avaliacao`).
  7. `carrinho`: Itens no carrinho do usuário (`id`, `usuario_id`, `produto_id`, `quantidade`, `data_adicao`).
  8. `pedidos`: Histórico de pedidos finalizados (`id`, `usuario_id`, `valor_total`, `status`, `modalidade_entrega`, `endereco`, `frete`, `forma_pagamento`, `pagamento_confirmado`, `data_pedido`).
  9. `itens_pedido`: Produtos vinculados a um pedido (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`).
* **Dados Iniciais (*Seeds* em `MM.sql`):**
  * **2 Usuários:** 1 Administrador (`admin@magicmerch.local`) e 1 Cliente de teste (`joao@email.com`).
  * **4 Artistas:** *The Beatles*, *Taylor Swift*, *BTS*, *Anime Classics*.
  * **10 Produtos:** Camisetas, moletons, canecas, pôsteres e acessórios (Todos com `imagem` = `NULL`).
* **Dados AINDA NÃO Integrados:**
  * Transações financeiras de gateways de pagamento.
  * Código de rastreamento de entregas.
  * Caminhos e arquivos físicos de imagens de produtos.

---

# 10. FUNCIONALIDADES

| Funcionalidade | Estado | Local | Observação |
|---|---|---|---|
| **Navegação na Home** | ✅ Implementada | `index.php` | Exibe destaques e artistas com contagem real de produtos. |
| **Catálogo de Produtos** | ✅ Implementada | `produtos.php` | Funcional com ordenação por preço, nome e destaques. |
| **Filtros de Busca** | ✅ Implementada | `produtos.php` | Filtra por termo, categoria, artista, preço e estoque. |
| **Página de Detalhes** | ✅ Implementada | `produto.php` | Exibe especificações do item, avaliações e estoque. |
| **Avaliações de Produtos** | ✅ Implementada | `produto.php` | Permite envio de nota (1 a 5) e comentário salva em `avaliacoes`. |
| **Sistema de Favoritos** | ✅ Implementada | `produto.php` | Salva e remove itens da tabela `favoritos`. |
| **Cadastro de Clientes** | ✅ Implementada | `login.php` | Criação de conta cliente com senha criptografada. |
| **Login de Clientes** | ✅ Implementada | `login.php` | Autenticação com sessão `$_SESSION['usuario']`. |
| **Recuperação de Senha** | 🔵 Estrutura Preparada | `login.php` | Formulário presente; apenas exibe mensagem fictícia. |
| **Carrinho de Compras** | ✅ Implementada | `carrinho.php` | Adiciona, ajusta quantidades, remove e calcula subtotal. |
| **Cálculo de Frete** | 🟡 Parcial | `config/app.php` / `checkout.php` | Frete estático por estado (R$ 10 SP / R$ 20 Outros / R$ 0 Retirada). |
| **Gestão de Endereços** | ✅ Implementada | `perfil.php` | Permite adicionar e deletar endereços do cliente. |
| **Checkout / Compra** | ✅ Implementada | `checkout.php` | Cria pedido no banco, gera itens, reduz estoque e esvazia carrinho. |
| **Pagamento (Pix / Cartão)**| 🔵 Estrutura Preparada | `checkout.php` | Registra a opção escolhida, mas aprova o pagamento de forma simulada. |
| **Programa de Fidelidade** | ✅ Implementada | `perfil.php` | Lógica em PHP que concede brinde a cada 10 itens comprados. |
| **Histórico de Pedidos** | ✅ Implementada | `perfil.php` | Exibe pedidos e status atualizados do cliente logado. |
| **Login Administrativo** | ✅ Implementada | `admin/login.php` | Restringe o painel apenas para contas do tipo `administrador`. |
| **Dashboard Admin** | ✅ Implementada | `admin/index.php` | Exibe métricas consolidadas de faturamento e vendas. |
| **CRUD de Produtos** | 🟡 Parcial | `admin/produtos.php` | Gerencia texto/preço/estoque; upload de foto não integrado. |
| **Gestão de Estoque** | ✅ Implementada | `admin/estoque.php` | Atualização rápida de estoque com alerta para unidades <= 5. |
| **Gestão de Pedidos Admin**| ✅ Implementada | `admin/pedidos.php` | Permite alterar o status dos pedidos dos clientes. |
| **Relatórios de Vendas** | ✅ Implementada | `admin/relatorios.php` | Filtra vendas por intervalo de datas e ranking de mais vendidos. |

---

# 11. REGRAS DE NEGÓCIO

## Regras Refletidas no Código (Implementadas)

1. **Restrição de Acesso Administrativo:** Somente usuários com `tipo = 'administrador'` na tabela `usuarios` conseguem acessar as páginas dentro da pasta `/admin/`.
2. **Uso Exclusivo do CRUD:** Todas as operações com o MySQL passam pelas funções genéricas de `config/crud.php`.
3. **Persistência do Carrinho:** O carrinho é vinculado ao ID do usuário logado na tabela `carrinho`, garantindo que os itens permaneçam salvos entre sessões.
4. **Baixa Automática de Estoque:** Ao concluir um pedido no `checkout.php`, a quantidade comprada de cada item é imediatamente subtraída da coluna `estoque` na tabela `produtos`.
5. **Cálculo de Frete Simulado:**
   * Modalidade Retirada: R$ 0,00.
   * Entrega para o estado de SP: R$ 10,00.
   * Entrega para outros estados: R$ 20,00.
6. **Programa de Fidelidade:** A cada 10 itens comprados acumulados no histórico de pedidos concluídos do cliente, o sistema contabiliza 1 brinde no painel `perfil.php`.
7. **Avaliação Única por Produto:** O banco possui restrição `UNIQUE KEY(usuario_id, produto_id)` na tabela `avaliacoes`, impedindo que o mesmo usuário avalie o mesmo produto múltiplas vezes.

## Regras Definidas no Planejamento (Ainda NÃO Implementadas)

1. **Upload Físico de Imagens:** Regra de processar e armazenar arquivos de imagem de produtos em pasta do servidor.
2. **Integração Real de Frete:** Cálculo do valor do frete e prazo de entrega via API dos Correios com base nas dimensões do produto e CEP.
3. **Gateway de Pagamento Real:** Aprovação condicional do pedido mediante retorno de webhook financeiro (Pix/Cartão).
4. **Recuperação de Senha Segura:** Geração de token temporário com validade e envio por e-mail.

---

# 12. FLUXOS DO SISTEMA

## 1. Navegação do Cliente (Loja Pública)
* **Fluxo Real:**
  `Home (index.php)` -> `Catálogo (produtos.php)` -> `Detalhes do Produto (produto.php)` -> `Adicionar ao Carrinho` -> `Carrinho (carrinho.php)` -> `Checkout (checkout.php)` -> `Confirmação & Pedido registrado` -> `Histórico no Perfil (perfil.php)`.

## 2. Autenticação e Perfil
* **Fluxo Real:**
  `Acesso (login.php)` -> `Preencher Login ou Cadastro` -> `Redirecionamento para a página anterior ou perfil.php` -> `Gerenciar Endereços / Ver Fidelidade`.
* **Etapa Simulada:** Opção "Esqueci minha senha" exibe a mensagem de sucesso sem enviar mensagem real.

## 3. Checkout e Pagamento
* **Fluxo Real:**
  `Carrinho` -> `Verificar Sessão Logada` -> `Escolher Endereço` -> `Escolher Frete/Retirada` -> `Selecionar Pix/Cartão` -> `Gravar Pedido no MySQL` -> `Deduzir Estoque` -> `Limpar Carrinho`.
* **Etapa Simulada:** A confirmação do pagamento é instantânea (`pagamento_confirmado = 1`), ignorando o fluxo real de análise de crédito.

## 4. Fluxo Administrativo
* **Fluxo Real:**
  `Login Admin (admin/login.php)` -> `Dashboard (admin/index.php)` -> `Gerenciar Produtos / Estoque / Pedidos / Relatórios` -> `Atualizar Status do Pedido` -> (Status refletido no perfil do cliente).

---

# 13. O QUE JÁ FOI FEITO

## O QUE JÁ TEMOS

* **Estrutura de Arquivos e Organização do Projeto:** Diretórios organizados conforme as restrições acadêmicas (`admin/`, `assets/`, `config/`, `includes/`, `docs/`).
* **Banco de Dados Relacional:** Script `MM.sql` estruturado com 9 tabelas relacionais com chaves estrangeiras e integridade referencial, preenchido com dados iniciais de teste.
* **Camada de Abstração de Dados:** Funções PDO genéricas em `config/crud.php`.
* **Bootstrap e Utilities:** `config/app.php` completo com suporte a sessão, validação de permissões, sanitização e manipulação de arrays para junção de tabelas.
* **Design System & Estilização CSS:** `assets/css/style.css` estruturado com variáveis CSS (:root) para a paleta oficial da marca e tipografia Google Fonts, responsivo para desktop e mobile.
* **Componentes visuais de Fallback:** Gerador de pôsteres visuais via CSS para contornar a ausência de fotos de produtos.
* **Loja Pública Completa (8 Páginas):** Home, Catálogo com filtros avançados, Detalhes do Produto, Lista de Artistas, Autenticação, Perfil do Usuário, Carrinho e Checkout.
* **Painel Administrativo Completo (6 Páginas):** Login seguro, Dashboard de estatísticas, CRUD de produtos, Controle de estoque, Alteração de status de pedidos e Relatórios financeiros por período.

---

# 14. O QUE AINDA FALTA

## O QUE AINDA FALTA (Organizado por Área)

### Frontend & Experiência Visual
* [ ] Substituição dos placeholders de cores por imagens reais dos produtos e banners das coleções.
* [ ] Padronização de um pacote de ícones vetoriais (SVG) substituindo os caracteres Unicode atuais.
* [ ] Definição e polimento final da direção visual da marca.

### PHP & Backend
* [ ] Manipulação e upload físico de arquivos de imagem no formulário de produtos (`admin/produtos.php`).
* [ ] Implementação de envio de e-mails via SMTP para notificação de novos pedidos e recuperação de senha.
* [ ] Adição de validações adicionais nos formulários (ex.: máscara de telefone e CEP).

### Banco de Dados
* [ ] Inserção dos caminhos das imagens reais dos produtos na coluna `imagem` da tabela `produtos`.

### Cliente
* [ ] Validação/Autopreenchimento de endereço através da consulta de CEP (API ViaCEP).
* [ ] Exibição visual do comprovante ou chave Pix copiável no checkout.

### Admin
* [ ] Upload visual de arquivos de imagem ao cadastrar/editar produtos.
* [ ] Exportação dos relatórios de vendas para formatos CSV ou PDF.

---

# 15. PLANEJAMENTO FUTURO

## MVP (Escopo Atual — Concluído no Código)
* Estrutura básica funcional em PHP procedural + MySQL.
* Catálogo completo, carrinho persistente, checkout funcional com simulação local, histórico de pedidos, programa de fidelidade simples e painel administrativo completo para controle operacional.

## Próxima Fase (Melhorias Recomendadas)
* Integração de JavaScript Vanilla para feedback visual dinâmico (validação de formulários no cliente, atualizações do carrinho via AJAX sem recarregar a página).
* Upload de imagens reais de produtos via admin.
* Integração da API ViaCEP no cadastro de endereços.

## Ideias Futuras (Expansões Fora do Escopo Acadêmico Atual)
* Integração com gateways de pagamento reais (MercadoPago / Stripe).
* Integração com webservices de cálculo de frete (Correios / Melhor Envio).
* Notificações via WhatsApp/E-mail sobre mudanças no status do pedido.
* Cadastro de cupom de desconto na tabela do banco de dados.

---

# 16. REFERÊNCIA RÁPIDA

## MAPA RÁPIDO DO PROJETO

```text
MAGICMERCH (RAIZ)
│
├── 🌐 PÁGINAS PÚBLICAS (CLIENTE)
│   ├── index.php             ➔ Home (Destaques, Hero e Grade de Artistas)
│   ├── produtos.php          ➔ Catálogo (Busca, Filtros, Ordenação)
│   ├── produto.php           ➔ Detalhes do Produto (Especificações, Avaliações, Favoritos)
│   ├── artistas.php          ➔ Lista de Artistas e Bandas
│   ├── login.php             ➔ Login, Cadastro e Recuperação de Senha (Simulada)
│   ├── perfil.php            ➔ Minha Conta (Endereços, Fidelidade, Histórico de Pedidos)
│   ├── carrinho.php          ➔ Carrinho de Compras (Ajustar quantidades, Subtotal)
│   └── checkout.php          ➔ Finalização da Compra (Frete, Endereço, Pagamento Simulado)
│
├── 🔐 PAINEL ADMINISTRATIVO (/admin/)
│   ├── admin/login.php       ➔ Autenticação de Administrador
│   ├── admin/index.php       ➔ Dashboard (Faturamento, Pedidos, Vendas)
│   ├── admin/produtos.php    ➔ CRUD de Produtos
│   ├── admin/estoque.php     ➔ Gestão e Alertas de Estoque (<= 5 un)
│   ├── admin/pedidos.php     ➔ Acompanhamento e Atualização de Status de Pedidos
│   └── admin/relatorios.php  ➔ Relatórios Gerenciais e Ranking de Vendas
│
├── ⚙️ ESTRUTURA E CONFIGURAÇÕES
│   ├── config/
│   │   ├── app.php           ➔ Bootstrap, Sessão, Helpers e Consultas de Domínio
│   │   ├── crud.php          ➔ Funções Únicas de Acesso ao BD (create/readAll/read/update/delete)
│   │   └── database.php      ➔ Conexão PDO com MySQL (magicmerch_db)
│   ├── includes/
│   │   ├── components/       ➔ Componentes da Home (hero-banner.php, artists-grid.php)
│   │   ├── header.php        ➔ Topo da Loja com Menu e Mensagens Flash
│   │   ├── footer.php        ➔ Rodapé da Loja
│   │   ├── cabecalho-admin.php➔ Topo do Painel Administrativo
│   │   ├── rodape-admin.php  ➔ Rodapé do Painel Administrativo
│   │   ├── icones.php        ➔ Helper de Ícones Unicode/SVG
│   │   └── poster.php        ➔ Renderizador de Pôster Visual (Fallback de Foto)
│   ├── assets/
│   │   ├── css/
│   │   │   ├── style.css     ➔ Estilos da Loja Pública (Variáveis :root da Paleta Oficial)
│   │   │   └── admin.css     ➔ Estilos do Painel Administrativo
│   │   └── img/
│   │       └── logo/logo.svg ➔ Logotipo Vetorial Oficial
│   └── MM.sql                ➔ Script de Criação do Banco de Dados e Inserts de Teste
```

---
*Fim da Documentação do Estado Atual.*
