<?php
/**
 * Funções de manipulação da lista de tarefas usando MySQL/MariaDB via PDO.
 * Todas as consultas utilizam Prepared Statements para evitar SQL Injection.
 */

require_once __DIR__ . '/config.php';

/**
 * Retorna todas as tarefas cadastradas, ordenadas pela mais recente.
 */
function listarTarefas(): array
{
    $pdo = conectar();

    $stmt = $pdo->prepare('SELECT id, titulo, status FROM tarefas ORDER BY id DESC');
    $stmt->execute();

    return $stmt->fetchAll();
}

/**
 * Insere uma nova tarefa no banco de dados.
 */
function criarTarefa(string $titulo, string $status = 'Pendente'): void
{
    $pdo = conectar();

    $stmt = $pdo->prepare('INSERT INTO tarefas (titulo, status) VALUES (:titulo, :status)');
    $stmt->execute([
        ':titulo' => trim($titulo),
        ':status' => $status,
    ]);
}

/**
 * Alterna o status de uma tarefa (Pendente <-> Concluida) pelo ID.
 */
function atualizarStatus(int $id): void
{
    $pdo = conectar();

    // Busca o status atual usando prepared statement
    $stmtBusca = $pdo->prepare('SELECT status FROM tarefas WHERE id = :id');
    $stmtBusca->execute([':id' => $id]);
    $tarefa = $stmtBusca->fetch();

    if (!$tarefa) {
        return;
    }

    $novoStatus = ($tarefa['status'] === 'Pendente') ? 'Concluida' : 'Pendente';

    $stmtAtualiza = $pdo->prepare('UPDATE tarefas SET status = :status WHERE id = :id');
    $stmtAtualiza->execute([
        ':status' => $novoStatus,
        ':id'     => $id,
    ]);
}

/**
 * Remove uma tarefa do banco de dados pelo ID.
 */
function deletarTarefa(int $id): void
{
    $pdo = conectar();

    $stmt = $pdo->prepare('DELETE FROM tarefas WHERE id = :id');
    $stmt->execute([':id' => $id]);
}
