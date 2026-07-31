---
name: testing-todo-list
description: Test the PHP To-Do List app end-to-end (Fase 1 arquivo .txt e Fase 2 MySQL/PDO). Use when verifying CRUD, persistence, or security (SQL Injection/XSS) changes.
---

# Testing — To-Do List PHP

App com CRUD em duas fases de persistência. Ambas usam a mesma API de funções
(`listarTarefas`, `criarTarefa`, `alternarStatus`, `deletarTarefa`) e o mesmo
CSS (`assets/style.css`).

## Setup local

1. Instalar (se necessário): `sudo apt-get install -y php-cli php-mysql mariadb-server mariadb-client`.
2. Iniciar o banco: `sudo service mariadb start` (pode não estar rodando após reboot; snapshots não persistem processos).
3. Carregar o schema da Fase 2: `sudo mysql < script.sql` (cria banco `todo_app` + tabela `tarefas` com 2 seeds).
4. Usuário de banco para o app via TCP: por padrão o `root` do MariaDB usa auth `unix_socket` e **não** conecta por 127.0.0.1. Crie um usuário dedicado:
   ```sql
   CREATE USER IF NOT EXISTS 'todo'@'127.0.0.1' IDENTIFIED BY 'todo';
   GRANT ALL PRIVILEGES ON todo_app.* TO 'todo'@'127.0.0.1'; FLUSH PRIVILEGES;
   ```
5. Subir o servidor a partir da raiz do repo, passando as credenciais por env var (config.php as lê):
   ```bash
   DB_USER=todo DB_PASS=todo php -S 0.0.0.0:8000
   ```

## URLs

- Fase 1 (arquivo): http://localhost:8000/fase1-arquivo/
- Fase 2 (MySQL+PDO): http://localhost:8000/fase2-banco/

## UI

- Campo de texto + botão **Adicionar** cria tarefa.
- Botão redondo (check) à esquerda alterna Pendente↔Concluída.
- Botão **×** à direita deleta.
- Resumo mostra "X de N tarefa(s) concluída(s)". Fase 2 ordena por `id DESC` (nova tarefa no topo).

## Verificações de persistência

- Fase 1: `cat fase1-arquivo/dados/tarefas.txt` — formato `id;titulo;status` (status 0/1); `;` no título vira `\semicolon`.
- Fase 2: `sudo mysql -e "SELECT id,titulo,status FROM todo_app.tarefas ORDER BY id DESC;"`.

## Testes de segurança (Fase 2)

- SQL Injection: criar tarefa com título `Teste"; DROP TABLE tarefas; --`. Esperado: vira uma tarefa literal, tabela `tarefas` continua existindo (Prepared Statements, `ATTR_EMULATE_PREPARES=false`).
- XSS: criar tarefa `<b>xss</b>`. Esperado: aparece como texto literal (não em negrito) — `htmlspecialchars()`.

## Dicas

- Se a Fase 2 mostrar banner "não foi possível conectar", provavelmente o MariaDB não está rodando ou o usuário/senha do banco não batem — revise passos 2-5.
- Estado limpo da Fase 1: `rm -f fase1-arquivo/dados/tarefas.txt`. Reset da Fase 2: `sudo mysql -e 'DROP DATABASE todo_app;' && sudo mysql < script.sql`.

## Devin Secrets Needed

- Nenhum secret para o teste local (o banco usa credenciais locais `todo`/`todo`).
- Para push/criar repo na conta do usuário: `GITHUB_PAT_JOHNVLOGHD` (PAT com escopo `repo`).
