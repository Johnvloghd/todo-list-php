<?php
/**
 * Funções de manipulação da lista de tarefas usando um arquivo texto (.txt)
 * Formato de cada linha do arquivo: id;titulo;status
 * Onde status é "Pendente" ou "Concluida"
 */

define('ARQUIVO_TAREFAS', __DIR__ . '/data/tarefas.txt');

/**
 * Garante que o arquivo de dados exista antes de qualquer operação.
 */
function garantirArquivo(): void
{
    if (!file_exists(ARQUIVO_TAREFAS)) {
        fopen(ARQUIVO_TAREFAS, 'w');
    }
}

/**
 * Lê todas as tarefas do arquivo e retorna um array associativo.
 * Cada tarefa: ['id' => int, 'titulo' => string, 'status' => string]
 */
function listarTarefas(): array
{
    garantirArquivo();
    $tarefas = [];

    $linhas = file(ARQUIVO_TAREFAS, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($linhas === false) {
        return $tarefas;
    }

    foreach ($linhas as $linha) {
        $partes = explode(';', $linha, 3);
        if (count($partes) === 3) {
            $tarefas[] = [
                'id'     => (int) $partes[0],
                'titulo' => $partes[1],
                'status' => $partes[2],
            ];
        }
    }

    return $tarefas;
}

/**
 * Gera o próximo ID disponível com base no maior ID já existente.
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
 * Adiciona uma nova tarefa ao arquivo.
 */
function criarTarefa(string $titulo, string $status = 'Pendente'): void
{
    garantirArquivo();

    $tarefas = listarTarefas();
    $novoId  = proximoId($tarefas);

    // Sanitiza o título para não quebrar o delimitador ";"
    $tituloLimpo = str_replace(';', ',', trim($titulo));

    $linha = $novoId . ';' . $tituloLimpo . ';' . $status . PHP_EOL;

    $handle = fopen(ARQUIVO_TAREFAS, 'a');
    if ($handle) {
        fwrite($handle, $linha);
        fclose($handle);
    }
}

/**
 * Reescreve o arquivo inteiro a partir de um array de tarefas.
 * Usado internamente por atualizarStatus() e deletarTarefa().
 */
function salvarTodasTarefas(array $tarefas): void
{
    $handle = fopen(ARQUIVO_TAREFAS, 'w');
    if ($handle) {
        foreach ($tarefas as $tarefa) {
            $linha = $tarefa['id'] . ';' . $tarefa['titulo'] . ';' . $tarefa['status'] . PHP_EOL;
            fwrite($handle, $linha);
        }
        fclose($handle);
    }
}

/**
 * Atualiza o status de uma tarefa pelo ID (alterna Pendente <-> Concluida).
 */
function atualizarStatus(int $id): void
{
    $tarefas = listarTarefas();

    foreach ($tarefas as &$tarefa) {
        if ($tarefa['id'] === $id) {
            $tarefa['status'] = ($tarefa['status'] === 'Pendente') ? 'Concluida' : 'Pendente';
        }
    }
    unset($tarefa);

    salvarTodasTarefas($tarefas);
}

/**
 * Remove uma tarefa pelo ID.
 */
function deletarTarefa(int $id): void
{
    $tarefas = listarTarefas();

    $tarefasFiltradas = array_filter($tarefas, function ($tarefa) use ($id) {
        return $tarefa['id'] !== $id;
    });

    salvarTodasTarefas(array_values($tarefasFiltradas));
}
