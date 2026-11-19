# Documentação do Portal SI

## Requisitos
- PHP 8+
- Apache (XAMPP/Laragon) com `mod_rewrite`
- PostgreSQL 16
- Banco `portal_si`

## Camadas MVC
- **Model:** classes em `app/Models` (acesso PDO).
- **View:** templates em `app/Views`.
- **Controller:** regras de fluxo em `app/Controllers`.
- **Front Controller:** `public/index.php` recebe todas as rotas.

## Rotas principais
- `/` — Home
- `/sobre-o-curso` — página institucional (slug da categoria)
- `/eventos` — agenda pública
- `/login` e `/logout` — autenticação
- `/admin/conteudos` — CRUD de conteúdos
- `/admin/eventos` — CRUD de eventos
