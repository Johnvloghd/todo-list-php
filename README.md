# Lista de Tarefas (To-Do List) — PHP

Projeto de CRUD em PHP dividido em duas fases, para exercitar diferentes formas de persistência de dados.

## Estrutura do projeto

```
todo-list-php/
├── fase1-arquivo-texto/     # Fase 1: persistência em arquivo .txt
│   ├── data/tarefas.txt
│   ├── functions.php
│   ├── index.php
│   ├── style.css
│   └── README.md
│
└── fase2-mysql-pdo/          # Fase 2: persistência em MySQL via PDO
    ├── config.php
    ├── functions.php
    ├── index.php
    ├── script.sql
    ├── style.css
    └── README.md
```

## Fase 1 — Arquivo Texto

Toda a persistência é feita em um arquivo `data/tarefas.txt`, usando funções nativas do PHP (`fopen`, `fwrite`, `file`). Cada linha do arquivo representa uma tarefa no formato `id;titulo;status`.

➡️ Veja instruções detalhadas em [`fase1-arquivo-texto/README.md`](fase1-arquivo-texto/README.md).

## Fase 2 — MySQL + PDO

A mesma aplicação, migrada para usar um banco de dados MySQL/MariaDB através da extensão **PDO**, com **Prepared Statements** em todas as consultas SQL para evitar SQL Injection.

➡️ Veja instruções detalhadas em [`fase2-mysql-pdo/README.md`](fase2-mysql-pdo/README.md).
O script `script.sql` contém o `CREATE TABLE` da tabela `tarefas`.

## Operações CRUD (ambas as fases)

- **Criar**: adiciona uma nova tarefa com título e status "Pendente".
- **Listar**: exibe todas as tarefas cadastradas.
- **Atualizar**: alterna o status da tarefa entre "Pendente" e "Concluída".
- **Deletar**: remove uma tarefa da lista.

## Requisitos gerais

- PHP 7.4 ou superior.
- Para a Fase 2: MySQL/MariaDB com a extensão `pdo_mysql` habilitada.
