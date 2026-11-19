<?php
require dirname(__DIR__) . '/config/database.php';

header('Content-Type: text/html; charset=utf-8');

try {
    $pdo = getPDO();
    $versao = $pdo->query('SELECT version()')->fetchColumn();
    $banco = $pdo->query('SELECT current_database()')->fetchColumn();
    $usuario = $pdo->query('SELECT current_user')->fetchColumn();

    echo '<pre>';
    echo "=========================================\n";
    echo "TESTE DE CONEXÃO COM POSTGRESQL\n";
    echo "=========================================\n";
    echo "Grupo: Portal SI\n";
    echo "Servidor: Aiven / Local (PDO)\n";
    echo 'Banco: ' . htmlspecialchars((string) $banco, ENT_QUOTES, 'UTF-8') . "\n";
    echo 'Usuário: ' . htmlspecialchars((string) $usuario, ENT_QUOTES, 'UTF-8') . "\n";
    echo "Status: CONECTADO\n";
    echo "PostgreSQL:\n";
    echo htmlspecialchars((string) $versao, ENT_QUOTES, 'UTF-8') . "\n";
    echo '=========================================';
    echo '</pre>';
} catch (Throwable $e) {
    error_log('Falha no teste de conexão: ' . $e->getMessage());
    echo 'Não foi possível conectar ao banco de dados.';
}
