<?php
require_once __DIR__ . '/../config/app.php';
exigirAdministrador();

$pdo->exec('CREATE TABLE IF NOT EXISTS respostas_suporte (
    chamado_id INT PRIMARY KEY,
    resposta TEXT NOT NULL,
    data_resposta TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)');

if (empty($_SESSION['token_admin_suporte'])) {
    $_SESSION['token_admin_suporte'] = bin2hex(random_bytes(32));
}

$aviso = $_SESSION['aviso_suporte_admin'] ?? '';
unset($_SESSION['aviso_suporte_admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['chamado_id'] ?? 0);
    $resposta = trim((string) ($_POST['resposta'] ?? ''));
    $token = (string) ($_POST['token'] ?? '');

    if (!hash_equals($_SESSION['token_admin_suporte'], $token)) {
        $aviso = 'A sessão expirou. Atualize a página e tente novamente.';
    } elseif ($resposta === '' || mb_strlen($resposta) > 5000) {
        $aviso = 'A resposta deve ter entre 1 e 5.000 caracteres.';
    } else {
        $stmt = $pdo->prepare('INSERT IGNORE INTO respostas_suporte (chamado_id, resposta) SELECT id, ? FROM mensagens_suporte WHERE id = ?');
        $stmt->execute([$resposta, $id]);
        $aviso = $stmt->rowCount() ? 'Chamado respondido.' : 'Esse chamado não existe ou já foi respondido.';
    }

    $_SESSION['aviso_suporte_admin'] = $aviso;
    redirecionar('suporte.php');
}

$tituloPaginaAdmin = 'Suporte';
$subtituloAdmin = 'Chamados aguardando resposta';
require __DIR__ . '/../includes/cabecalho-admin.php';

$chamados = $pdo->query('SELECT m.* FROM mensagens_suporte m LEFT JOIN respostas_suporte r ON r.chamado_id = m.id WHERE r.chamado_id IS NULL ORDER BY m.data_envio DESC')->fetchAll();
?>
<section class="painel-card">
    <div class="painel-card__header">
        <div>
            <h2 class="painel-card__titulo">Chamados recebidos</h2>
            <p class="painel-card__subtitulo"><?= count($chamados) ?> aguardando resposta</p>
        </div>
    </div>

    <?php if ($aviso !== ''): ?><p role="status"><?= escapar($aviso) ?></p><?php endif; ?>

    <?php if (!$chamados): ?>
        <p>Não há chamados aguardando resposta.</p>
    <?php else: ?>
        <?php foreach ($chamados as $chamado): ?>
            <article class="suporte-chamado">
                <h3><?= escapar($chamado['nome']) ?></h3>
                <p><?= escapar($chamado['email']) ?> · <?= escapar(date('d/m/Y H:i', strtotime($chamado['data_envio']))) ?></p>
                <div class="suporte-mensagem"><?= nl2br(escapar($chamado['mensagem'])) ?></div>
                <form method="post" class="form-admin">
                    <input type="hidden" name="token" value="<?= escapar($_SESSION['token_admin_suporte']) ?>">
                    <input type="hidden" name="chamado_id" value="<?= (int) $chamado['id'] ?>">
                    <label for="resposta-<?= (int) $chamado['id'] ?>">Resposta</label>
                    <textarea id="resposta-<?= (int) $chamado['id'] ?>" name="resposta" rows="4" maxlength="5000" required></textarea>
                    <button type="submit">Marcar como respondido</button>
                </form>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<style>
.suporte-chamado { padding: 1rem 0; border-bottom: 1px solid var(--admin-borda); }
.suporte-chamado h3 { margin: 0; }
.suporte-chamado > p { color: var(--admin-mutado); font-size: .85rem; }
.suporte-mensagem { padding: 1rem; background: #f8fafc; border-radius: var(--radius-sm); line-height: 1.6; overflow-wrap: anywhere; }
.suporte-chamado .form-admin { max-width: none; margin: 1rem 0 0; }
.suporte-chamado textarea { width: 100%; resize: vertical; }
</style>
</main>
</div>
</div>
</body>
</html>
