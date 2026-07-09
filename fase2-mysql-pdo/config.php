<?php
/**
 * Configuração da conexão com o banco de dados MySQL/MariaDB usando PDO.
 * Ajuste as constantes abaixo conforme o seu ambiente.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'todolist_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Cria e retorna uma conexão PDO com o banco de dados.
 * Lança uma exceção em caso de falha na conexão.
 */
function conectar(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    $opcoes = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $opcoes);
    } catch (PDOException $e) {
        die('Erro na conexão com o banco de dados: ' . $e->getMessage());
    }
}
