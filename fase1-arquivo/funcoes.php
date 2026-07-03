<?php
/**
 * Fase 1 - Persistência em arquivo de texto (.txt)
 *
 * Formato de armazenamento: uma tarefa por linha, campos separados por ";"
 *   id;titulo;status
 * Onde status é "0" (Pendente) ou "1" (Concluída).
 * O título tem os caracteres ";" e quebras de linha escapados para não
 * corromper o formato do arquivo.
 */

declare(strict_types=1);

const ARQUIVO_TAREFAS = __DIR__ . '/dados/tarefas.txt';
const SEPARADOR = ';';

/**
 * Garante que o arquivo (e a pasta) de dados exista.
 */
function garantirArmazenamento(): void
{
    $pasta = dirname(ARQUIVO_TAREFAS);
    if (!is_dir($pasta)) {
        mkdir($pasta, 0775, true);
    }
    if (!file_exists(ARQUIVO_TAREFAS)) {
        touch(ARQUIVO_TAREFAS);
    }
}

/**
 * Escapa um título para gravação segura em uma única linha.
 */
function escapar(string $texto): string
{
    return str_replace(
        [SEPARADOR, "\r", "\n"],
        ['\\semicolon', ' ', ' '],
        $texto
    );
}

/**
 * Reverte o escape aplicado por escapar().
 */
function desescapar(string $texto): string
{
    return str_replace('\\semicolon', SEPARADOR, $texto);
}

/**
 * Lê todas as tarefas do arquivo.
 *
 * @return array<int, array{id:int, titulo:string, status:int}>
 */
function listarTarefas(): array
{
    garantirArmazenamento();

    $tarefas = [];
    $linhas = file(ARQUIVO_TAREFAS, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($linhas === false) {
        return $tarefas;
    }

    foreach ($linhas as $linha) {
        $partes = explode(SEPARADOR, $linha, 3);
        if (count($partes) < 3) {
            continue;
        }
        $tarefas[] = [
            'id'     => (int) $partes[0],
            'titulo' => desescapar($partes[1]),
            'status' => (int) $partes[2],
        ];
    }

    return $tarefas;
}

/**
 * Grava a lista completa de tarefas de volta no arquivo (sobrescreve).
 *
 * @param array<int, array{id:int, titulo:string, status:int}> $tarefas
 */
function salvarTarefas(array $tarefas): void
{
    garantirArmazenamento();

    $conteudo = '';
    foreach ($tarefas as $tarefa) {
        $conteudo .= implode(SEPARADOR, [
            (int) $tarefa['id'],
            escapar((string) $tarefa['titulo']),
            (int) $tarefa['status'],
        ]) . PHP_EOL;
    }

    // LOCK_EX evita corrupção em gravações concorrentes.
    file_put_contents(ARQUIVO_TAREFAS, $conteudo, LOCK_EX);
}

/**
 * Calcula o próximo id disponível.
 *
 * @param array<int, array{id:int, titulo:string, status:int}> $tarefas
 */
function proximoId(array $tarefas): int
{
    $maior = 0;
    foreach ($tarefas as $tarefa) {
        if ($tarefa['id'] > $maior) {
            $maior = $tarefa['id'];
        }
    }
    return $maior + 1;
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

    $tarefas = listarTarefas();
    $tarefas[] = [
        'id'     => proximoId($tarefas),
        'titulo' => $titulo,
        'status' => $status === 1 ? 1 : 0,
    ];
    salvarTarefas($tarefas);
}

/**
 * Alterna o status (Pendente <-> Concluída) de uma tarefa.
 */
function alternarStatus(int $id): void
{
    $tarefas = listarTarefas();
    foreach ($tarefas as &$tarefa) {
        if ($tarefa['id'] === $id) {
            $tarefa['status'] = $tarefa['status'] === 1 ? 0 : 1;
            break;
        }
    }
    unset($tarefa);
    salvarTarefas($tarefas);
}

/**
 * Remove uma tarefa pelo id.
 */
function deletarTarefa(int $id): void
{
    $tarefas = listarTarefas();
    $tarefas = array_values(array_filter(
        $tarefas,
        static fn(array $t): bool => $t['id'] !== $id
    ));
    salvarTarefas($tarefas);
}
