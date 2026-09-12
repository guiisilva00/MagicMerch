# NIVELAMENTO TÉCNICO DO MAGICMERCH — SYNC MECATRONICS COMO REFERÊNCIA

> **Análise e Diretrizes Técnicas de Complexidade**  
> *Baseado na documentação técnica do SYNC (`sync/README.md`), no `DOCUMENTACAO_ESTADO_ATUAL.md` e no código-fonte real do MagicMerch.*

---

## A. DIAGNÓSTICO GERAL

**Nível do SYNC.** Procedural puro, sem tipagem, 6 funções de CRUD (`create`, `readAll`, `read`, `update`, `delete`, `read_nome_via_ID`), `$pdo->query()` direto para leitura (sem parâmetros — os `WHERE` chegam prontos em string), comparação de senha em texto puro, poucas funções auxiliares (saudação, formatação de nome, redirecionamento por perfil), lógica de filtro concatenando `OR` manualmente. Zero orientação a objetos, zero dependências externas via Composer.

**Nível atual do MagicMerch.** Também procedural puro, também sem OOP, também sem Composer, também com CRUD único e genérico (5 funções + `sanitizeIdentifier`). Nisso os dois projetos **são equivalentes**. A diferença real não está na arquitetura (nenhuma camada nova foi criada — nada de Service/Repository/DAO/ORM, como o próprio `PRODUCT.md` já proíbe), e sim em **três eixos de idioma/estilo PHP** que o MagicMerch usa e o SYNC não usa:

1. **Tipagem estrita.** `config/app.php` abre com `declare(strict_types=1);` e quase toda função tem tipos de parâmetro e retorno (`string`, `bool`, `?array`, `never`). O SYNC não tipa nada. Isso não aumenta a arquitetura, mas exige entender coerção de tipos do PHP 8 — um assunto que não aparece em nenhuma outra parte do projeto.
2. **Idioma funcional com arrow functions e `array_*`.** Isso é o ponto mais visível de desigualdade. Trechos como:
   - `array_filter($pedidos, fn($p) => (int) $p['pagamento_confirmado'] === 1)` (`admin/index.php`)
   - `array_sum(array_map(fn($i) => (float) $i['preco'] * (int) $i['quantidade'], $itens))` (`subtotalCarrinho`)
   - `usort($destaques, fn($x, $y) => (int) $y['vendas'] <=> (int) $x['vendas'])` (`index.php`)
   - `array_values(array_unique(array_column(...)))` (`buscarCategorias`)

   são idiomáticos e curtos, mas pressupõem fluência com closures, `array_column`, `array_map/filter/sum`, spread e o operador nave espacial (`<=>`) — nada disso existe na documentação do SYNC, que descreve funções "lineares", sem "encadeamentos extensos". O SYNC resolveria a mesma coisa com `foreach` e uma variável acumuladora.
3. **`match` expression** (`buscarProdutos`) no lugar do `if/elseif` que o SYNC usaria.

Nenhum desses três pontos é "arquitetura avançada" — são só um português de PHP mais moderno do que o do benchmark. É exatamente o tipo de desigualdade que a documentação de referência pede para nivelar: não vai reprovar o projeto, mas é a parte que você teria mais dificuldade de "explicar e reproduzir do zero" se comparado ao nível demonstrado no SYNC.

**Diferença real (o que não é desigualdade, é domínio).** Carrinho, checkout, estoque, favoritos, avaliações, fidelidade e relatórios não têm equivalente direto no SYNC — e não precisam ter. A forma como foram implementados (POST → validação simples → CRUD → redirecionamento) está no mesmo nível de complexidade do wizard de contratação do SYNC (`contratar.php` → `segundo_contrato.php` → `pagamento.php` → `func/insert.php`). Isso é **compatível**, não é excesso.

**Maior fonte de complexidade "escondida":** a própria regra vinculante do MagicMerch de **nunca fazer JOIN/agregação em SQL** força mais código PHP de cruzamento de dados (`indexarPorId()`, `array_column`, laços aninhados) do que o SYNC precisa — porque o SYNC se permite uma função de exceção (`read_nome_via_ID`) que roda uma consulta pontual fora do CRUD genérico quando só precisa de um campo. Isso é discutido em detalhe na seção **G** como uma inconsistência a registrar, não a corrigir silenciosamente.

---

## B. LISTA DE EXCESSOS (o que está acima do nível do SYNC)

| # | Onde | O que é o excesso | Gravidade |
|---|---|---|---|
| 1 | `config/app.php` (topo) | `declare(strict_types=1)` — tipagem estrita não usada no SYNC | 🟡 |
| 2 | Praticamente todas as funções de `app.php`/`crud.php` | Tipos de parâmetro/retorno em toda função, incluindo o tipo `never` (PHP 8.1) em `redirecionar()` | 🟡 |
| 3 | `subtotalCarrinho`, `quantidadeCarrinho`, `buscarCategorias`, `index.php`, `admin/index.php`, `admin/relatorios.php` (via `readAll` + `array_*`) | Uso pervasivo de `array_map/array_filter/array_sum/array_column/usort` com arrow functions no lugar de `foreach` | 🟠 |
| 4 | `buscarProdutos()` | `match` expression para ordenação (o SYNC usaria `if/elseif`) | 🟢 (trivial, cosmético) |
| 5 | `mensagemFlash(?string $tipo=null, ?string $texto=null): ?array` | Uma função fazendo duas coisas diferentes (setter e getter) dependendo se os argumentos vêm nulos — padrão que o SYNC não usa em nenhum lugar (lá os erros são exibidos inline com `alert()` JS ou `<p class="erro">`) | 🟡 |
| 6 | `includes/poster.php` (`posterProduto`, `posterArtista`) | Funções que fazem `ob_start()`/`ob_get_clean()` para retornar HTML como string — um mini padrão de "componente". O SYNC nunca retorna HTML de uma função; ele só usa `include` de partials (`header.php`, `footer.php`) | 🟡 |
| 7 | `acentoPoster()` | `(int) (crc32($chave) % 5) + 1` — hash determinístico via `crc32` para escolher uma cor entre 5. Funciona, mas é um truque que exige explicar por que um hash de string e por que módulo 5; o SYNC não tem nada parecido a "variação visual determinística" | 🟠 |
| 8 | CSS (`assets/css/style.css`) | Sistema de design com `clamp()`, tipografia fluida, `color-mix()`, variação de composição via `:nth-child(4n+…)`. Isso é 100% front-end, não afeta o back-end nem o PHP, mas é um nível de sofisticação visual claramente acima do que o SYNC descreve (ele usa Bootstrap Icons/FontAwesome prontos e CSS por módulo, sem essa camada de "design tokens") | 🟡 (fora do escopo do nivelamento de PHP, mas registrado por completude) |

Nenhum item da lista é 🔴 (muito acima do SYNC). Não existe, no código real, nenhum ORM, Service, Repository, classe customizada, namespace, autoload via Composer ou chamada de API externa — ou seja, as regras absolutas do `PRODUCT.md` estão sendo respeitadas à risca. O desnivelamento é de **idioma/estilo PHP**, não de **arquitetura**.

---

## C. MATRIZ SYNC × MAGICMERCH

| Área | SYNC | MagicMerch | Diferença | Complexidade | Decisão |
|---|---|---|---|---|---|
| Conexão PDO | `new PDO(...)` direto na página `crud.php`, `die()` em erro | `criarConexaoBancoDados()` isolada em `database.php`, `try/catch` retornando `null` em vez de `die()` | MagicMerch degrada melhor (páginas continuam renderizando com `$pdo === null`) | 🟢 Compatível | **MANTER** |
| CRUD — leitura | `readAll`/`read` sem parâmetros, usam `$pdo->query($sql)` (valores embutidos na string do `$where`) | `readAll`/`read` recebem `$params` e usam `prepare()->execute($params)` sempre | MagicMerch é mais seguro (evita injeção via `$where` dinâmico) | 🟢 Justificado por segurança, não por arquitetura | **MANTER** |
| CRUD — escrita | `create`/`update`/`delete` com prepared statements, sem sanitizar nome de tabela/coluna | Idêntico, mais `sanitizeIdentifier()` (escapa nome com crase) | Camada extra de 3 linhas, custo desprezível | 🟢 Compatível | **MANTER** |
| CRUD — exceções ao padrão genérico | Tem `read_nome_via_ID()`, uma função "fora do padrão" para 1 caso específico | Não existe nenhuma função fora do padrão — tudo passa pelas 5 genéricas | MagicMerch é **mais rígido** que o próprio SYNC nesse ponto (ver seção G) | 🟠 Gera mais código de junção em PHP do que o SYNC precisa | **AVALIAR** (ver seção G) |
| Junções/relacionamentos | Resolvidos em PHP (`read_nome_via_ID`, arrays) | Resolvidos em PHP (`indexarPorId()` + `array_column`) | Mesma filosofia, mas MagicMerch tem mais pontos de junção (produto↔artista, pedido↔usuário, item↔produto) por ser e-commerce | 🟢 Complexidade de domínio, não de implementação | **MANTER** |
| Autenticação | Senha em texto puro (`===`) | `password_hash`/`password_verify` (bcrypt) + `session_regenerate_id(true)` | MagicMerch acima do SYNC, mas por boa prática mínima de segurança, não por sofisticação | 🟢 Justificado | **MANTER** |
| Sessões | `$_SESSION` para dados de usuário e wizard de contratação | `$_SESSION` para usuário logado, retorno pós-login e flash message | Escopo comparável | 🟢 Compatível | **MANTER** |
| Validação de formulário | `empty()`/`isset()` no servidor, alguns `alert()` via JS | `empty()`/`isset()`/`filter_var(FILTER_VALIDATE_EMAIL)` no servidor, sem JS (regra do projeto) | Equivalente, MagicMerch até evita a dependência de JS que o SYNC usa | 🟢 Compatível | **MANTER** |
| Mensagens de erro/sucesso | Inline por página (`alert()`, `<p class="erro">`) | Função genérica `mensagemFlash()` fazendo dupla função (setter/getter) | Introduz um padrão que o SYNC não usa | 🟡 Acima do SYNC | **SIMPLIFICAR** |
| Filtros dinâmicos | `filtroEspecialidade()`/`filtroStatus()` concatenando `OR` manualmente a partir de checkboxes | `buscarProdutos()` monta array de condições e parâmetros, `implode(' AND ', $condicoes)` | Mesma técnica (concatenar strings de `WHERE`), aplicada a mais campos | 🟢 Compatível | **MANTER** |
| Funções auxiliares | Poucas, pontuais, 5–20 linhas | Mais numerosas (auth, formatação, domínio), mas cada uma continua pequena e de responsabilidade única | Quantidade maior por ser e-commerce, não por função individual mais complexa | 🟢 Compatível | **MANTER** |
| Estilo/idioma PHP | `foreach`, `if/else`, sem arrow function, sem tipagem | Arrow functions + `array_map/filter/sum/column` pervasivos, tipagem estrita, `match` | Real desnivelamento de idioma | 🟠 Desnecessariamente complexo (para o nível-alvo) | **SIMPLIFICAR** |
| Carrinho | — (não existe no SYNC) | Tabela própria, CRUD simples (`create`/`update`/`delete` por `usuario_id + produto_id`) | Domínio de e-commerce | 🟢 Complexidade de domínio | **MANTER** |
| Checkout | Wizard de 3 páginas com estado em `$_SESSION['pedido']` | Página única: POST → valida estoque em PHP → `create` pedido → `create` itens → `update` estoque → `delete` carrinho | Checkout do MagicMerch é **mais simples** que o wizard do SYNC (menos páginas, sem estado de sessão multi-etapas) | 🟢 Compatível (até mais simples) | **MANTER** |
| Estoque | — (não existe no SYNC) | Ajuste manual via formulário único (`admin/estoque.php`) | Domínio de e-commerce, implementação mínima (1 `update`) | 🟢 Complexidade de domínio | **MANTER** |
| Favoritos | — (não existe no SYNC) | `read` para checar existência + `create` condicional | Padrão idêntico ao de "verificar duplicidade" que o SYNC usa em `segundo_contrato.php` (checar se data já está agendada) | 🟢 Compatível, tem equivalente direto | **MANTER** |
| Avaliações | — (não existe no SYNC) | `read`/`update`/`create` condicional + `UNIQUE KEY` no banco para impedir duplicidade | Mesma técnica de "existe? atualiza : cria" | 🟢 Compatível | **MANTER** |
| Endereços | — (não existe no SYNC) | CRUD simples por `usuario_id`, sem API de CEP | Domínio de e-commerce, sem integração externa (a integração real está corretamente listada como "planejada, não implementada") | 🟢 Complexidade de domínio | **MANTER** |
| Fidelidade | — (não existe no SYNC) | Cálculo de itens comprados com módulo (`%`) sobre pedidos confirmados | Pequeno trecho de aritmética (4–5 linhas), sem abstração | 🟢 Compatível | **MANTER** |
| Relatórios | — (não existe no SYNC) | Agregação manual em PHP (`arsort`, `array_slice`) porque o CRUD não faz `GROUP BY` | Mais código do que uma query SQL faria, mas é consequência da regra "CRUD sem agregação" | 🟠 Ver seção G | **AVALIAR** |
| Administração | Painel próprio (`admin/adminpage.php`, aprovação de profissionais) | Painel próprio (`admin/`) com dashboard, CRUD de produtos, estoque, pedidos, relatórios | Estrutura equivalente (área separada + `exigirAdministrador()` análogo a checar `categoria === 'admin'`) | 🟢 Compatível | **MANTER** |
| Componentização de views | `include` de `partials/header.php`/`footer.php`, sem função retornando HTML | Funções (`posterProduto`, `posterArtista`) que usam `ob_start()` e retornam string HTML | Introduz um padrão de "componente" que o SYNC não usa | 🟡 Acima do SYNC | **SIMPLIFICAR** |
| Ícones | Biblioteca externa pronta (Bootstrap Icons/FontAwesome) | Função PHP própria (`icone()`) com array de paths SVG | Mais código PHP do lado do MagicMerch, mas evita dependência externa e mantém a paleta de cores exata via `currentColor` | 🟡 Acima do SYNC em volume de código, mas justificável pela regra de paleta obrigatória | **MANTER** |
| JavaScript | Usado para sidebar e auto-submit de filtro | Nenhum (regra do projeto) | MagicMerch é **mais simples** aqui | 🟢 Compatível (até mais simples) | **MANTER** |
| Uploads de arquivo | Validação de MIME, tamanho, `uniqid()` para nome único | Não implementado (planejado) | Sem comparação ainda — não é excesso, é funcionalidade futura | — | **NÃO APLICÁVEL AINDA** |
| Tipagem PHP | Nenhuma | `strict_types`, tipos escalares, `never`, `?array` | Real desnivelamento de idioma | 🟡 Acima do SYNC | **SIMPLIFICAR OU DOCUMENTAR** |

---

## D. ALTERAÇÕES CIRÚRGICAS

### D1 — Arrow functions e `array_*` em cascata

**Estado atual:** funções como `subtotalCarrinho()`, `buscarCategorias()`, `admin/index.php` e `admin/relatorios.php` resolvem somas, filtros e ordenações com `array_map/filter/sum/column/usort` e arrow functions (`fn($x) => ...`).

**Problema:** é o ponto isolado que mais exige um vocabulário de PHP que não aparece em nenhuma outra parte do projeto nem no SYNC. Se alguém pedir para você explicar linha a linha `array_sum(array_map(fn($i) => (float) $i['preco'] * (int) $i['quantidade'], $itens))`, é um salto de nível em relação a `foreach` com acumulador.

**Ajuste:** reescrever os pontos mais críticos (`subtotalCarrinho`, `quantidadeCarrinho`, agregações de `admin/relatorios.php` e `admin/index.php`) como `foreach` simples com variável acumuladora — exatamente como o SYNC resolveria.

**Resultado:** mesmas 4–3 linhas de código, mas usando só `foreach` e `if`, sem `fn()`.

**Impacto:** nenhuma funcionalidade muda. Só o estilo interno da função. Os pontos de entrada (chamadas às funções) continuam idênticos, então nenhuma página precisa ser tocada.

### D2 — `mensagemFlash()` fazendo duas coisas

**Estado atual:** uma única função decide, pelo valor de `$tipo`, se está **gravando** ou **lendo** a mensagem flash.

**Problema:** um leitor (ou você mesmo, daqui a duas semanas) precisa ler o corpo inteiro da função para saber qual dos dois comportamentos está em jogo em cada chamada. O SYNC nunca tem uma função com dois comportamentos distintos escondidos atrás de argumentos opcionais.

**Ajuste:** dividir em duas funções pequenas, cada uma com uma responsabilidade: `definirFlash($tipo, $texto)` e `lerFlash()`.

**Resultado:** duas funções de 2–3 linhas cada, no lugar de uma função de 1 linha com dois comportamentos.

**Impacto:** troca simples de nome nas poucas chamadas existentes (`mensagemFlash('sucesso', ...)` → `definirFlash('sucesso', ...)`, e em `header.php`, `mensagemFlash()` → `lerFlash()`). Não afeta o banco nem a UI.

### D3 — Componentização via `ob_start()` em `includes/poster.php`

**Estado atual:** `posterProduto()` e `posterArtista()` usam buffer de saída para devolver HTML como string, chamado como `<?= posterProduto($produto) ?>`.

**Problema:** é um padrão de "componente que retorna markup" que não existe em nenhum outro lugar do MagicMerch nem no SYNC. O SYNC resolve reuso de HTML só com `include` de arquivos parciais (`partials/header.php`).

**Ajuste:** transformar em um arquivo parcial incluído com `include`, recebendo a variável via `extract()` ou simplesmente definindo `$produto`/`$artista` antes do `include` — do jeito que `includes/header.php` e `includes/cabecalho-admin.php` já fazem.

**Resultado:** mesma reutilização de HTML, mas com a mesma técnica (`include` de parcial) usada no resto do projeto e no SYNC, sem função que "monta string e devolve".

**Impacto:** as três páginas que chamam `posterProduto()`/`posterArtista()` (`produtos.php`, `produto.php`, `carrinho.php`, `index.php`, `artistas.php`) passam a fazer `include 'includes/produto-poster.php'` dentro do `foreach`, no lugar de `<?= posterProduto($produto) ?>`. Puramente mecânico.

### D4 — `acentoPoster()` via `crc32`

**Estado atual:** `(int) (crc32($chave) % 5) + 1`, usando hash da string (nome do produto/artista) para escolher 1 de 5 cores.

**Problema:** é um truque que funciona, mas não é óbvio de explicar ("por que crc32? por que módulo 5?") e não tem nada parecido no SYNC.

**Ajuste:** trocar por `($id % 5) + 1`, usando o `id` numérico do produto/artista (que já está sempre disponível), já que o objetivo é só "distribuir cores de forma consistente e determinística" — o `id` cumpre isso com uma operação muito mais simples de justificar.

**Resultado:** mesma função, uma linha, sem hash.

**Impacto:** muda a cor de alguns cards (não há como preservar exatamente a mesma distribuição visual trocando a fórmula), mas isso é puramente estético e não afeta nenhuma regra de negócio.

### D5 — Regra "zero SQL fora do CRUD genérico" mais rígida que a do próprio SYNC

**Estado atual:** todo relacionamento e toda agregação (produto↔artista, pedido↔usuário, ranking de vendas, faturamento) é resolvido 100% em PHP com `indexarPorId()`/`array_column`/`foreach`, porque nenhuma função além das 5 genéricas pode existir.

**Problema:** o próprio SYNC — que é a referência de simplicidade — se permite `read_nome_via_ID()`, uma função de exceção para resolver 1 join pontual com uma única query. O MagicMerch não se permite nenhuma exceção, o que faz o código de agregação em `admin/relatorios.php` e `perfil.php` ser mais longo do que precisaria em um SQL puro.

**Ajuste (opcional — depende de qual regra você prioriza):** ou (a) manter a regra como está, reconhecendo que essa é uma complexidade de PHP a mais que o projeto aceita deliberadamente em troca de nunca escrever SQL fora do CRUD; ou (b) seguir o exemplo do próprio SYNC e permitir **uma única função de exceção documentada** (ex.: `contarVendasPorProduto($pdo, $where)`) para os 2–3 casos de agregação, mantendo as 5 funções genéricas para tudo o mais.

**Resultado:** se optar por (b), `admin/relatorios.php` e `admin/index.php` ficam mais curtos e mais parecidos com o nível do SYNC.

**Impacto:** essa é a única alteração da lista que exigiria mudar uma regra escrita no `PRODUCT.md` ("Proibido... novas funções de banco"). Por isso ela está marcada como **decisão sua**, não como algo que eu simplifiquei silenciosamente — ver seção G.

---

## E. NOVO PLANO DE IMPLEMENTAÇÃO (revisado e nivelado)

```text
FUNCIONALIDADE: Autenticação de Cliente (Login/Cadastro/Logout)
Arquivo: login.php
Banco: usuarios
CRUD: read(), create()
Funções PHP: password_hash, password_verify, filter_var(FILTER_VALIDATE_EMAIL), session_regenerate_id
Fluxo: Formulário → POST → validação simples (email válido, senha >= 8, email não duplicado) → CRUD → $_SESSION → redirecionamento
Complexidade: Baixa
Decisão: MANTER
Simplificação: Nenhuma. O uso de password_hash é a única prática "acima" do SYNC, e é justificado por segurança mínima, não por sofisticação.
```

```text
FUNCIONALIDADE: Catálogo com Filtros
Arquivo: produtos.php (view) + buscarProdutos()/buscarArtistas()/buscarCategorias() em config/app.php
Banco: produtos, artistas
CRUD: readAll()
Funções PHP: montagem de array de condições/parâmetros + implode, indexarPorId()
Fluxo: GET → montar $filtros → montar WHERE dinâmico → readAll() → junção com artistas em PHP (indexarPorId) → grade de cards
Complexidade: Baixa/Média (equivalente a filtro.php do SYNC)
Decisão: MANTER
Simplificação: trocar sort() por ordenação com foreach se quiser remover o único match; não é obrigatório.
```

```text
FUNCIONALIDADE: Detalhe do Produto (Carrinho, Favoritos, Avaliações)
Arquivo: produto.php
Banco: produtos, carrinho, favoritos, avaliacoes, usuarios
CRUD: read(), readAll(), create(), update()
Funções PHP: indexarPorId() (para nome de quem avaliou)
Fluxo: GET id → buscarProdutoPorId() → POST condicional (carrinho | favorito | avaliação) → read-ou-create/update → redirecionamento
Complexidade: Média (3 ações no mesmo POST, mas cada bloco isolado e curto)
Decisão: MANTER
Simplificação: Nenhuma estrutural. Nenhuma camada nova é criada.
```

```text
FUNCIONALIDADE: Carrinho de Compras
Arquivo: carrinho.php + itensCarrinho()/subtotalCarrinho()/quantidadeCarrinho() em config/app.php
Banco: carrinho, produtos
CRUD: readAll(), update(), delete()
Funções PHP: foreach (recomendado no lugar de array_map/array_sum atuais)
Fluxo: POST (atualizar quantidade | remover) → CRUD → redirecionamento; leitura via readAll() + junção em PHP
Complexidade: Baixa
Decisão: MANTER, com SIMPLIFICAÇÃO DE ESTILO (ver D1)
Simplificação: subtotalCarrinho()/quantidadeCarrinho() reescritas com foreach.
```

```text
FUNCIONALIDADE: Checkout
Arquivo: checkout.php
Banco: pedidos, itens_pedido, produtos, carrinho, enderecos
CRUD: readAll(), read(), create(), update(), delete()
Funções PHP: calcularFrete(), indexarPorId(), subtotalCarrinho()
Fluxo: POST → validar estoque em PHP (foreach) → create(pedido) → create(itens) em loop → update(estoque) em loop → delete(carrinho) → redirecionamento
Complexidade: Média (é o fluxo mais longo do sistema, mas linear, sem transação — decisão consciente já documentada)
Decisão: MANTER
Simplificação: nenhuma. É comparável ao wizard de 3 páginas do SYNC, só que resolvido em uma página só — logo, já está no nível ou abaixo dele.
```

```text
FUNCIONALIDADE: Perfil (Dados, Endereços, Fidelidade, Histórico)
Arquivo: perfil.php
Banco: usuarios, enderecos, pedidos, itens_pedido
CRUD: read(), readAll(), update(), create(), delete()
Funções PHP: aritmética de módulo para fidelidade (documentar com comentário)
Fluxo: POST condicional (dados | endereço | excluir endereço) → CRUD → redirecionamento; leitura agregada de itens comprados via foreach
Complexidade: Média (é a página com mais responsabilidades, mas cada bloco continua curto)
Decisão: MANTER
Simplificação: comentar a conta de fidelidade (`$comprados % 10`) com uma linha explicando a regra, para facilitar defesa acadêmica.
```

```text
FUNCIONALIDADE: Administração — CRUD de Produtos, Estoque, Pedidos, Relatórios
Arquivo: admin/produtos.php, admin/estoque.php, admin/pedidos.php, admin/relatorios.php
Banco: produtos, pedidos, itens_pedido, artistas
CRUD: create(), readAll(), read(), update(), delete()
Funções PHP: indexarPorId(); em relatorios.php, agregação manual (arsort/array_slice) por causa da regra de "sem SQL de agregação"
Fluxo: POST → validação mínima → CRUD → redirecionamento (produtos/estoque/pedidos); GET com filtro de data → readAll() → agregação em PHP (relatórios)
Complexidade: Baixa (produtos/estoque/pedidos) / Média (relatórios, pela agregação manual)
Decisão: MANTER — exceto relatorios.php, que fica marcado como AVALIAR (ver D5/G)
Simplificação: trocar array_sum/array_column por foreach nos cards de admin/index.php.
```

```text
FUNCIONALIDADE: Componentes visuais (Pôster de produto/artista)
Arquivo: includes/poster.php
Banco: —
CRUD: —
Funções PHP: hoje usa ob_start()/ob_get_clean(); proposta: include de parcial
Fluxo: dado um array $produto/$artista → renderizar card
Complexidade: Baixa, mas usando um padrão (função-que-retorna-HTML) que não existe no restante do projeto
Decisão: SIMPLIFICAR (ver D3)
Simplificação: substituir por include de arquivo parcial, no mesmo espírito de includes/header.php.
```

---

## F. NÍVEL DE COMPLEXIDADE ALVO DO MAGICMERCH

Este contrato técnico define o teto de complexidade aceito no projeto a partir de agora.

- **PHP:** procedural, sem classes customizadas, sem namespaces, sem Composer. Tipagem de parâmetro/retorno é **opcional** — pode manter onde já existe, mas não é obrigatório adicionar em código novo. `strict_types` pode continuar declarado, mas nenhuma lógica deve depender dele para "funcionar corretamente" (ou seja, sempre passar os tipos certos, nunca contar com coerção implícita).
- **CRUD:** único e genérico (`create`, `readAll`, `read`, `update`, `delete`, `sanitizeIdentifier`). Nenhuma nova função de banco sem decisão explícita registrada (ver seção G para a única exceção em aberto).
- **Banco de dados:** relacional, com FKs, sem `JOIN`/`GROUP BY` nas queries do CRUD — todo relacionamento resolvido em PHP com `indexarPorId()` e laços simples.
- **Consultas:** sempre via prepared statements com parâmetros (`?`), nunca concatenando valor direto na string SQL.
- **Funções:** 5 a ~25 linhas, responsabilidade única, nomes em português (mantendo o padrão do projeto), preferencialmente `foreach` no lugar de cadeias de `array_map/filter/reduce` com arrow function. Arrow functions e `array_*` combinadores são **permitidos em usos pontuais e óbvios** (ex.: `array_column` simples), mas não em cadeias de duas ou mais funções encadeadas.
- **Validação:** `empty()`, `isset()`, `filter_var()`, `trim()`, checagem de tipo via cast (`(int)`, `(float)`). Sem bibliotecas de validação.
- **Segurança:** `password_hash`/`password_verify`, `htmlspecialchars` na saída, prepared statements sempre. Isso fica **acima do nível do SYNC de propósito** — é a única categoria em que "acima do benchmark" é a decisão correta, porque é segurança básica, não sofisticação de arquitetura.
- **Orientação a objetos:** nula, exceto o uso nativo de `PDO`.
- **APIs:** nenhuma API interna. Integrações externas (CEP, frete, pagamento, e-mail) continuam como "planejadas", não implementadas.
- **JavaScript:** nenhum, por regra do projeto.
- **Abstração aceitável:** `include` de parciais para reuso de HTML (como já é feito em `header.php`/`footer.php`); funções de consulta "de atalho" sobre o CRUD (como `buscarProdutoPorId()`); nada além disso.
- **Lógica a evitar:** funções com mais de uma responsabilidade escondida atrás de argumentos opcionais (tipo `mensagemFlash`); funções que retornam HTML via buffer de saída; qualquer hash/truque matemático sem comentário explicando o motivo; consultas SQL fora das 5 funções genéricas sem registro explícito da exceção.

---

## G. INCONSISTÊNCIA REGISTRADA (não corrigida silenciosamente)

O `PRODUCT.md` do MagicMerch proíbe, de forma vinculante, "novas funções de banco" além das 5 do CRUD genérico. O SYNC — que é a referência de simplicidade usada nesta análise — **não segue essa mesma regra**: ele tem uma sexta função, `read_nome_via_ID()`, criada especificamente para resolver um join pontual com uma única query direta.

Ou seja: a regra que o MagicMerch impôs a si mesmo é, neste ponto específico, **mais rígida** do que a prática do próprio projeto usado como benchmark de simplicidade. Isso é a causa direta de `admin/relatorios.php`, `admin/index.php` e das junções em `perfil.php`/`produto.php` precisaren de mais linhas de PHP (`indexarPorId`, `array_column`, `foreach` de agregação) do que precisariam se uma exceção pontual, no estilo `read_nome_via_ID()`, fosse permitida para os 2–3 casos de agregação/relatório.

Não simplifiquei isso automaticamente porque envolve mudar uma regra explícita e vinculante do `PRODUCT.md`. As duas opções ficam registradas na seção D5 para você decidir: manter a pureza total do CRUD (aceitando o PHP extra) ou abrir uma exceção pontual documentada (reduzindo o PHP extra, ao custo de uma função a mais que "fura" a regra).

---

## H. REGRAS COMPLEMENTARES DE NIVELAMENTO

Esta seção estabelece regras adicionais que devem ser respeitadas durante qualquer futura alteração, refatoração ou implementação no MagicMerch.

O objetivo é impedir que o projeto volte a apresentar diferenças grandes de complexidade entre suas próprias funcionalidades.

---

### H1. CRUD — SEM ALTERAÇÃO ESTRUTURAL

O CRUD atual do MagicMerch deve ser considerado **parte fixa da arquitetura do projeto nesta etapa**.

As funções existentes em `config/crud.php` devem continuar sendo utilizadas como estão, sem criação de um novo sistema de persistência ou alteração estrutural da forma como o projeto acessa o banco.

Não devem ser introduzidos:
* novos CRUDs;
* Repository;
* DAO;
* ORM;
* Query Builder;
* Service Layer para substituir o CRUD;
* novas camadas de acesso ao banco;
* abstrações adicionais de persistência.

Também não deve ser criada uma nova função específica de banco apenas para aproximar o comportamento do MagicMerch ao SYNC.

A diferença existente entre os dois projetos deve ser apenas **registrada e documentada quando relevante**, não corrigida através da criação de novas camadas ou mecanismos nesta etapa.

#### Regra:
> **O objetivo desta etapa é nivelar a complexidade do código existente, não redesenhar a arquitetura de persistência do MagicMerch.**

---

### H2. UNIFORMIDADE DO ESTILO DO CÓDIGO

Depois do nivelamento, o código novo deve seguir o mesmo padrão simples estabelecido para o restante do projeto.

Não introduzir novos recursos ou construções de PHP apenas porque são mais modernos, compactos ou sofisticados.

Evitar, principalmente quando houver uma alternativa procedural mais simples:
* arrow functions (`fn() =>`);
* cadeias extensas de `array_map()`, `array_filter()`, `array_sum()` e funções semelhantes;
* `match`;
* abstrações excessivamente genéricas;
* estruturas difíceis de interpretar;
* funções com múltiplos comportamentos escondidos;
* soluções que dependam de conhecimento avançado de PHP.

Quando houver duas formas equivalentes de resolver um problema, deve-se priorizar a implementação que utilize conceitos que já aparecem de forma clara no projeto.

Por exemplo:
```php
foreach ($itens as $item) {
    $subtotal += $item['preco'] * $item['quantidade'];
}
```
deve ser preferido a uma cadeia mais compacta de `array_map()` + `array_sum()` quando a segunda opção tornar a lógica mais difícil de explicar ou reproduzir.

Isso não significa proibir completamente recursos modernos do PHP.

Significa que **o código novo não deve elevar desnecessariamente o nível técnico do projeto**.

#### Regra:
> **Não introduzir uma construção mais avançada apenas porque ela produz menos linhas de código.**

---

### H3. FACILIDADE DE COMPREENSÃO COMO CRITÉRIO PRINCIPAL

A principal métrica utilizada para avaliar a complexidade do MagicMerch deve ser **a facilidade de compreensão do código**.

Quantidade de linhas, quantidade de funções ou modernidade da sintaxe não devem ser utilizadas isoladamente para determinar se uma implementação é simples ou complexa.

Uma implementação com mais linhas pode ser considerada melhor para o projeto caso seja:
* mais linear;
* mais explícita;
* mais fácil de ler;
* mais fácil de explicar;
* mais fácil de reproduzir;
* mais fácil de modificar;
* mais próxima do estilo utilizado no SYNC.

Da mesma forma, uma implementação menor não deve ser considerada automaticamente mais simples.

Por exemplo:
```php
$total = array_sum(
    array_map(
        fn($item) => $item['preco'] * $item['quantidade'],
        $itens
    )
);
```
pode possuir menos linhas, mas exigir mais conhecimentos para ser compreendida do que:
```php
$total = 0;

foreach ($itens as $item) {
    $total += $item['preco'] * $item['quantidade'];
}
```

Para o objetivo deste projeto, **a segunda abordagem pode ser considerada tecnicamente mais adequada**, mesmo que seja mais extensa.

#### Critério principal:
Ao avaliar qualquer implementação, faça primeiro a seguinte pergunta:
> **"Uma pessoa com o mesmo nível técnico utilizado no SYNC conseguiria entender esta lógica lendo o código de forma linear?"**

Se a resposta for sim, a implementação tende a estar dentro do nível desejado.  
Se a resposta for não, deve-se procurar uma forma mais direta de representar a mesma lógica.

---

### H4. REGRA GERAL PARA FUTURAS IMPLEMENTAÇÕES

Toda nova funcionalidade deverá obedecer simultaneamente aos três princípios desta seção:

1. **Não criar uma nova arquitetura de persistência.**
2. **Não elevar desnecessariamente o nível da linguagem utilizada.**
3. **Priorizar compreensão acima de concisão ou modernidade.**

Portanto:
> **A implementação mais adequada para o MagicMerch não é necessariamente a mais moderna, a mais curta ou a mais sofisticada. É a implementação que resolve o problema de forma clara, direta e compatível com o nível técnico estabelecido pelo projeto.**
