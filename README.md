# Sistema de Lista de Tarefas (To-Do List) em PHP

Aplicação de Lista de Tarefas (CRUD) desenvolvida em PHP, dividida em duas
fases de persistência de dados:

- **Fase 1** — armazenamento em **arquivo de texto** (`.txt`), usando funções
  nativas de manipulação de arquivos do PHP.
- **Fase 2** — armazenamento em **banco de dados relacional** (MySQL/MariaDB)
  usando **PDO** com **Prepared Statements**.

Ambas as fases implementam as quatro operações básicas:
**Criar, Listar, Atualizar (status) e Deletar**.

## Estrutura do projeto

```
todo-list-php/
├── assets/
│   └── style.css            # Estilo compartilhado pelas duas fases
├── fase1-arquivo/           # FASE 1 — persistência em arquivo .txt
│   ├── index.php            # Interface + controlador
│   ├── funcoes.php          # CRUD sobre o arquivo de texto
│   └── dados/
│       └── tarefas.txt      # Gerado automaticamente em tempo de execução
├── fase2-banco/             # FASE 2 — persistência em MySQL via PDO
│   ├── index.php            # Interface + controlador
│   ├── funcoes.php          # CRUD com Prepared Statements
│   └── config.php           # Conexão PDO (credenciais)
├── script.sql               # CREATE DATABASE / CREATE TABLE da Fase 2
└── README.md
```

## Pré-requisitos

- **PHP 8.0+** (`php -v`) — o servidor embutido do PHP já é suficiente.
- Para a Fase 2: **MySQL** ou **MariaDB** e a extensão **PDO MySQL**
  (`pdo_mysql`). Verifique com `php -m | grep pdo_mysql`.

## Como rodar

### Fase 1 — Arquivo de texto

Não precisa de banco de dados. A partir da raiz do projeto:

```bash
php -S localhost:8000
```

Depois abra no navegador:

```
http://localhost:8000/fase1-arquivo/
```

As tarefas serão gravadas em `fase1-arquivo/dados/tarefas.txt`, no formato
`id;titulo;status` (uma tarefa por linha, `status` = `0` Pendente / `1` Concluída).

> A pasta `dados/` precisa ter permissão de escrita para o PHP. O arquivo
> `tarefas.txt` é criado automaticamente na primeira execução.

### Fase 2 — MySQL + PDO

1. **Crie o banco e a tabela** executando o `script.sql`:

   ```bash
   mysql -u root -p < script.sql
   ```

   Isso cria o banco `todo_app` e a tabela `tarefas`.

2. **Configure as credenciais** em `fase2-banco/config.php` (ou via variáveis
   de ambiente `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`).
   Os valores padrão são: host `127.0.0.1`, banco `todo_app`, usuário `root`,
   senha vazia.

3. **Inicie o servidor** a partir da raiz do projeto:

   ```bash
   php -S localhost:8000
   ```

4. Abra no navegador:

   ```
   http://localhost:8000/fase2-banco/
   ```

## Modelagem do banco (Fase 2)

Tabela `tarefas`:

| Coluna      | Tipo           | Descrição                          |
|-------------|----------------|------------------------------------|
| `id`        | INT (PK, AI)   | Identificador único                |
| `titulo`    | VARCHAR(255)   | Descrição da tarefa                |
| `status`    | TINYINT(1)     | `0` = Pendente, `1` = Concluída    |
| `criado_em` | TIMESTAMP      | Data/hora de criação (automática)  |

## Segurança

- **Fase 2** usa exclusivamente **Prepared Statements** (PDO), protegendo
  contra **SQL Injection**.
- Toda saída para HTML é escapada com `htmlspecialchars()`, prevenindo **XSS**.
- Ações que alteram dados usam `POST` + redirecionamento (padrão
  *Post/Redirect/Get*), evitando reenvio de formulário ao atualizar a página.
