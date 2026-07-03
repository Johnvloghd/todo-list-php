<?php
/**
 * Fase 1 - Interface e controlador (persistência em arquivo .txt)
 */

declare(strict_types=1);

require __DIR__ . '/funcoes.php';

// Processa as ações (POST) usando o padrão Post/Redirect/Get.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    switch ($acao) {
        case 'criar':
            criarTarefa((string) ($_POST['titulo'] ?? ''));
            break;
        case 'alternar':
            alternarStatus((int) ($_POST['id'] ?? 0));
            break;
        case 'deletar':
            deletarTarefa((int) ($_POST['id'] ?? 0));
            break;
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$tarefas = listarTarefas();
$total = count($tarefas);
$concluidas = count(array_filter($tarefas, static fn(array $t): bool => $t['status'] === 1));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas — Fase 1 (Arquivo)</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <main class="container">
        <header class="cabecalho">
            <h1>Lista de Tarefas</h1>
            <span class="badge">Fase 1 · Arquivo .txt</span>
        </header>

        <form class="form-nova" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <input type="hidden" name="acao" value="criar">
            <input
                type="text"
                name="titulo"
                placeholder="O que precisa ser feito?"
                autocomplete="off"
                required
                autofocus
            >
            <button type="submit" class="btn btn-primario">Adicionar</button>
        </form>

        <?php if ($total > 0): ?>
            <p class="resumo"><?= $concluidas ?> de <?= $total ?> tarefa(s) concluída(s)</p>
        <?php endif; ?>

        <ul class="lista">
            <?php if ($total === 0): ?>
                <li class="vazio">Nenhuma tarefa cadastrada ainda.</li>
            <?php endif; ?>

            <?php foreach ($tarefas as $tarefa): ?>
                <li class="item <?= $tarefa['status'] === 1 ? 'concluida' : '' ?>">
                    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="form-inline">
                        <input type="hidden" name="acao" value="alternar">
                        <input type="hidden" name="id" value="<?= (int) $tarefa['id'] ?>">
                        <button type="submit" class="check" title="Alternar status" aria-label="Alternar status">
                            <?= $tarefa['status'] === 1 ? '&#10003;' : '' ?>
                        </button>
                    </form>

                    <span class="titulo"><?= htmlspecialchars($tarefa['titulo']) ?></span>

                    <span class="status-tag <?= $tarefa['status'] === 1 ? 'ok' : 'pend' ?>">
                        <?= $tarefa['status'] === 1 ? 'Concluída' : 'Pendente' ?>
                    </span>

                    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="form-inline">
                        <input type="hidden" name="acao" value="deletar">
                        <input type="hidden" name="id" value="<?= (int) $tarefa['id'] ?>">
                        <button type="submit" class="btn btn-perigo" title="Remover" aria-label="Remover">&times;</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>
