<?php
require_once __DIR__ . '/helpers.php';

spl_autoload_register(function ($class) {
    $caminhos = [
        APP . '/Controllers/' . $class . '.php',
        APP . '/Models/' . $class . '.php',
    ];

    foreach ($caminhos as $arquivo) {
        if (file_exists($arquivo)) {
            require_once $arquivo;
            return;
        }
    }
});

function view(string $arquivo, array $dados = []): void
{
    extract($dados);
    $caminho = APP . '/Views/' . $arquivo . '.php';
    if (!file_exists($caminho)) {
        http_response_code(404);
        echo 'View não encontrada.';
        return;
    }
    require $caminho;
}

function url(string $caminho = ''): string
{
    $base = defined('BASE_URL') ? BASE_URL : '';
    $caminho = ltrim($caminho, '/');
    return $caminho === '' ? $base . '/' : $base . '/' . $caminho;
}

function usuarioLogado(): bool
{
    return isset($_SESSION['usuario_id']);
}

function exigirLogin(): void
{
    if (!usuarioLogado()) {
        session_unset();
        session_destroy();
        header('Location: ' . url('login') . '?erro=' . urlencode('Acesso restrito. Efetue login.'));
        exit;
    }

    if (!isset($_SESSION['ultimo_acesso']) || (time() - $_SESSION['ultimo_acesso'] > 1800)) {
        session_regenerate_id(true);
        $_SESSION['ultimo_acesso'] = time();
    }
}
