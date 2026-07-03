<?php
/**
 * Fase 2 - Camada de acesso a dados (CRUD) usando PDO.
 *
 * Todas as consultas usam Prepared Statements para evitar SQL Injection.
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Lista todas as tarefas ordenadas da mais recente para a mais antiga.
 *
 * @return array<int, array{id:int, titulo:string, status:int}>
 */
function listarTarefas(): array
{
    $pdo = conectar();
    $stmt = $pdo->query('SELECT id, titulo, status FROM tarefas ORDER BY id DESC');

    return $stmt->fetchAll();
}

/**
 * Cria uma nova tarefa.
 */
function criarTarefa(string $titulo, int $status = 0): void
{
    $titulo = trim($titulo);
    if ($titulo === '') {
        return;
    }

    $pdo = conectar();
    $stmt = $pdo->prepare('INSERT INTO tarefas (titulo, status) VALUES (:titulo, :status)');
    $stmt->execute([
        ':titulo' => $titulo,
        ':status' => $status === 1 ? 1 : 0,
    ]);
}

/**
 * Alterna o status (Pendente <-> Concluída) de uma tarefa.
 */
function alternarStatus(int $id): void
{
    $pdo = conectar();
    $stmt = $pdo->prepare('UPDATE tarefas SET status = NOT status WHERE id = :id');
    $stmt->execute([':id' => $id]);
}

/**
 * Remove uma tarefa pelo id.
 */
function deletarTarefa(int $id): void
{
    $pdo = conectar();
    $stmt = $pdo->prepare('DELETE FROM tarefas WHERE id = :id');
    $stmt->execute([':id' => $id]);
}
