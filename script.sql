-- =====================================================================
-- Fase 2 - Script de criação do banco de dados e da tabela `tarefas`
-- Banco: MySQL / MariaDB
-- =====================================================================

-- Cria o banco de dados (caso ainda não exista) e o seleciona.
CREATE DATABASE IF NOT EXISTS todo_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE todo_app;

-- Tabela principal de tarefas.
CREATE TABLE IF NOT EXISTS tarefas (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    titulo  VARCHAR(255) NOT NULL,
    status  TINYINT(1)   NOT NULL DEFAULT 0,  -- 0 = Pendente, 1 = Concluída
    criado_em TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados de exemplo (opcional).
INSERT INTO tarefas (titulo, status) VALUES
    ('Estudar PHP com PDO', 0),
    ('Ler sobre Prepared Statements', 1);
