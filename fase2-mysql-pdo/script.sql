-- Script de criação do banco de dados e tabela da Fase 2
-- Sistema: Lista de Tarefas (To-Do List)

CREATE DATABASE IF NOT EXISTS todolist_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE todolist_db;

CREATE TABLE IF NOT EXISTS tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    status ENUM('Pendente', 'Concluida') NOT NULL DEFAULT 'Pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dados de exemplo (opcional)
INSERT INTO tarefas (titulo, status) VALUES
    ('Estudar PHP com PDO', 'Pendente'),
    ('Configurar o banco de dados', 'Concluida');
