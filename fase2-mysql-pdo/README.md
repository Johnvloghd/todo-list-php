# Fase 2 — Lista de Tarefas (MySQL + PDO)

Versão da aplicação migrada para persistência em banco de dados relacional (MySQL/MariaDB), utilizando a extensão **PDO** com **Prepared Statements** em todas as consultas.

## Pré-requisitos

- PHP 7.4+ com a extensão `pdo_mysql` habilitada.
- MySQL ou MariaDB instalado e em execução.

## Como rodar localmente

1. **Crie o banco de dados e a tabela** executando o script SQL fornecido:
   ```
   mysql -u root -p < script.sql
   ```
   Isso criará o banco `todolist_db` e a tabela `tarefas` (com dois registros de exemplo).

2. **Configure a conexão** no arquivo `config.php`, ajustando usuário, senha e host conforme seu ambiente:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'todolist_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. **Suba o servidor embutido do PHP** dentro desta pasta (`fase2-mysql-pdo`):
   ```
   php -S localhost:8000
   ```

4. Abra o navegador em: `http://localhost:8000`

## Estrutura da tabela `tarefas`

| Coluna     | Tipo                          | Descrição                     |
|------------|-------------------------------|--------------------------------|
| id         | INT (PK, AUTO_INCREMENT)      | Identificador único            |
| titulo     | VARCHAR(150)                  | Título da tarefa               |
| status     | ENUM('Pendente','Concluida')  | Status da tarefa               |
| criado_em  | TIMESTAMP                     | Data de criação (automática)   |

## Segurança

Todas as operações (`SELECT`, `INSERT`, `UPDATE`, `DELETE`) usam **Prepared Statements** via PDO com parâmetros nomeados (`:titulo`, `:id`, `:status`), prevenindo ataques de **SQL Injection**.

## Funcionalidades

- **Criar**: insere uma nova tarefa com status "Pendente".
- **Listar**: consulta e exibe todas as tarefas do banco.
- **Atualizar**: alterna o status da tarefa (Pendente/Concluída).
- **Deletar**: remove a tarefa do banco de dados.

## Estrutura de arquivos

```
fase2-mysql-pdo/
├── config.php       # Conexão PDO com o banco
├── functions.php     # Funções de CRUD com Prepared Statements
├── index.php         # Interface + roteamento das ações
├── script.sql        # Script de criação do banco/tabela
├── style.css          # Estilos básicos
└── README.md
```
