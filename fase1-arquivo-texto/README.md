# Fase 1 — Lista de Tarefas (Arquivo Texto)

Aplicação de Lista de Tarefas em PHP puro, persistindo os dados em um arquivo `.txt` local usando funções nativas do PHP (`fopen`, `fwrite`, `file`, etc).

## Formato de armazenamento

Cada linha do arquivo `data/tarefas.txt` representa uma tarefa, no formato:

```
id;titulo;status
```

Exemplo:

```
1;Estudar PHP;Pendente
2;Fazer compras;Concluida
```

## Como rodar localmente

1. Certifique-se de ter o PHP instalado (versão 7.4 ou superior).
   ```
   php -v
   ```
2. Dentro desta pasta (`fase1-arquivo-texto`), suba o servidor embutido do PHP:
   ```
   php -S localhost:8000
   ```
3. Abra o navegador em: `http://localhost:8000`
4. Garanta que a pasta `data/` tenha permissão de escrita para o PHP (no Linux/Mac: `chmod -R 775 data`).

## Funcionalidades

- **Criar**: formulário no topo da página adiciona uma nova tarefa com status "Pendente".
- **Listar**: todas as tarefas são exibidas em uma lista.
- **Atualizar**: botão "Concluir/Reabrir" alterna o status da tarefa.
- **Deletar**: botão "Excluir" remove a tarefa da lista (com confirmação via JavaScript).

## Estrutura de arquivos

```
fase1-arquivo-texto/
├── data/
│   └── tarefas.txt      # Arquivo de persistência (gerado automaticamente)
├── functions.php        # Funções de CRUD sobre o arquivo texto
├── index.php            # Interface + roteamento das ações
├── style.css             # Estilos básicos
└── README.md
```
