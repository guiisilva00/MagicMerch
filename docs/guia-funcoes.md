# Guia de funções — MagicMerch

Referência rápida das funções disponíveis. **Todo acesso ao banco passa pelo CRUD**
de `config/crud.php`; as páginas nunca chamam `PDO::prepare`/`PDO::query` diretamente.

## Conexão

`config/database.php`
- `criarConexaoBancoDados(): PDO` — abre a conexão PDO (localhost / root / `magicmerch_db`).
  Chamada uma única vez em `config/app.php`, que expõe a variável `$pdo` para as páginas
  (fica `null` quando o banco ainda não foi importado).

## CRUD (`config/crud.php`)

| Função | Uso |
|---|---|
| `create($pdo, $table, array $data)` | INSERT. Retorna o `lastInsertId`. |
| `readAll($pdo, $table, $where = null, array $params = [])` | SELECT de vários registros. |
| `read($pdo, $table, $where = null, array $params = [])` | SELECT de um registro. |
| `update($pdo, $table, array $data, $where, array $params = [])` | UPDATE. Retorna linhas afetadas. |
| `delete($pdo, $table, $where, array $params = [])` | DELETE. |
| `sanitizeIdentifier($identifier)` | Protege nomes de tabela/coluna com crase. |

Observações:
- `$where` é uma string com placeholders `?` (ex.: `'usuario_id = ? AND produto_id = ?'`).
- Ordenação vai anexada em `$where` (ex.: `'1 ORDER BY nome'`, `'categoria = ? ORDER BY preco ASC'`).
- O CRUD não faz JOIN nem agregação: relacionamentos e somas são resolvidos em PHP,
  normalmente com `indexarPorId()` e as funções de `array_*`.

## Helpers de aplicação (`config/app.php`)

Sessão / usuário: `usuarioAtual()`, `estaLogado()`, `eAdministrador()`, `exigirLogin()`,
`exigirAdministrador()`.

Saída / navegação: `escapar($valor)` (htmlspecialchars), `redirecionar($url)`,
`mensagemFlash($tipo?, $texto?)`, `valorMoeda($valor)`.

Consultas prontas (atalhos finos sobre o CRUD):
- `indexarPorId(array $linhas): array` — devolve `id => linha`.
- `buscarArtistas($pdo)` / `buscarCategorias($pdo)`
- `buscarProdutoPorId($pdo, $id)` — produto + `nome_artista`.
- `buscarProdutos($pdo, array $filtros)` — catálogo com filtros e ordenação.
- `quantidadeCarrinho($pdo, $usuarioId)` / `itensCarrinho($pdo, $usuarioId)` / `subtotalCarrinho($itens)`

Regras de negócio: `calcularFrete($modalidade, $estado)`, `statusPedido()`.

Conteúdo estático: `$linksNavegacao`, `$slidesDestaque`.
