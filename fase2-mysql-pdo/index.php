<?php
require_once __DIR__ . '/functions.php';

// Processa as ações enviadas via formulário (Criar, Atualizar, Deletar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'criar' && !empty(trim($_POST['titulo'] ?? ''))) {
        criarTarefa($_POST['titulo']);
    }

    if ($acao === 'atualizar' && isset($_POST['id'])) {
        atualizarStatus((int) $_POST['id']);
    }

    if ($acao === 'deletar' && isset($_POST['id'])) {
        deletarTarefa((int) $_POST['id']);
    }

    // Evita reenvio do formulário ao atualizar a página (Post/Redirect/Get)
    header('Location: index.php');
    exit;
}

$tarefas = listarTarefas();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Tarefas - Fase 2 (MySQL + PDO)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>📋 Lista de Tarefas</h1>
        <p class="subtitulo">Fase 2 &mdash; Persistência em MySQL/MariaDB via PDO</p>

        <form action="index.php" method="POST" class="form-nova-tarefa">
            <input type="hidden" name="acao" value="criar">
            <input type="text" name="titulo" placeholder="Digite uma nova tarefa..." required maxlength="150">
            <button type="submit">Adicionar</button>
        </form>

        <?php if (empty($tarefas)): ?>
            <p class="vazio">Nenhuma tarefa cadastrada ainda.</p>
        <?php else: ?>
            <ul class="lista-tarefas">
                <?php foreach ($tarefas as $tarefa): ?>
                    <li class="<?= $tarefa['status'] === 'Concluida' ? 'concluida' : '' ?>">
                        <span class="titulo-tarefa">
                            #<?= (int) $tarefa['id'] ?> - <?= htmlspecialchars($tarefa['titulo']) ?>
                        </span>

                        <span class="status-badge <?= $tarefa['status'] === 'Concluida' ? 'badge-concluida' : 'badge-pendente' ?>">
                            <?= $tarefa['status'] === 'Concluida' ? 'Concluída' : 'Pendente' ?>
                        </span>

                        <span class="acoes">
                            <form action="index.php" method="POST" class="form-inline">
                                <input type="hidden" name="acao" value="atualizar">
                                <input type="hidden" name="id" value="<?= (int) $tarefa['id'] ?>">
                                <button type="submit" class="btn-status">
                                    <?= $tarefa['status'] === 'Concluida' ? 'Reabrir' : 'Concluir' ?>
                                </button>
                            </form>

                            <form action="index.php" method="POST" class="form-inline" onsubmit="return confirm('Deseja realmente excluir esta tarefa?');">
                                <input type="hidden" name="acao" value="deletar">
                                <input type="hidden" name="id" value="<?= (int) $tarefa['id'] ?>">
                                <button type="submit" class="btn-excluir">Excluir</button>
                            </form>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
