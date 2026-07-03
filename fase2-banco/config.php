<?php
/**
 * Fase 2 - Configuração e conexão com o banco de dados via PDO.
 *
 * As credenciais podem ser definidas por variáveis de ambiente
 * (útil em produção) ou pelos valores padrão abaixo (ambiente local).
 */

declare(strict_types=1);

$config = [
    'host'  => getenv('DB_HOST') ?: '127.0.0.1',
    'port'  => getenv('DB_PORT') ?: '3306',
    'nome'  => getenv('DB_NAME') ?: 'todo_app',
    'user'  => getenv('DB_USER') ?: 'root',
    'senha' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
];

/**
 * Cria e retorna uma conexão PDO reutilizável (singleton).
 */
function conectar(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    global $config;

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['port'],
        $config['nome'],
        $config['charset']
    );

    $opcoes = [
        // Lança exceções em erros — essencial para tratamento seguro.
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Retorna arrays associativos por padrão.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Usa prepared statements reais no servidor (não emulados).
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $config['user'], $config['senha'], $opcoes);

    return $pdo;
}
