# Portal de Comunicação SI

Estrutura Mini-MVC em PHP nativo, conforme a Aula 06 — Portal de Comunicação do Curso de Sistemas de Informação (ESUCRI).

## Como usar

1. Copie a pasta `portal-si` para `C:\xampp\htdocs\portal-si`.
2. No pgAdmin, crie o banco `portal_si`.
3. Execute `database/schema.sql` e depois `database/seed.sql`.
4. Acesse: `http://localhost/portal-si/public/`
5. Login: abra `http://localhost/portal-si/public/login`
   - No primeiro acesso o sistema cria o admin: `admin@esucri.com.br` / `123456`
6. Teste de conexão PDO: `http://localhost/portal-si/public/teste-conexao.php`
7. Para PostgreSQL no Aiven, edite `config/database.php`, defina `$usarAiven = true` e preencha host, porta, banco, usuário e senha (com `sslmode=require`).

A pasta `public/` é a raiz web (Front Controller). Arquivos de `app/` e `config/` não devem ser acessados diretamente.

## Estrutura

```
portal-si/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Views/
│       ├── admin/
│       ├── layouts/
│       └── public/
├── config/
│   └── database.php
├── database/
│   ├── schema.sql
│   └── seed.sql
├── docs/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── uploads/
│   ├── .htaccess
│   └── index.php
└── README.md
```
