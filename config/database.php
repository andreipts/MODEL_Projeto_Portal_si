<?php
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    // false = PostgreSQL local | true = PostgreSQL remoto no Aiven (sslmode=require)
    $usarAiven = false;

    if ($usarAiven) {
        $host = 'HOST_DO_AIVEN';
        $port = 'PORTA_DO_AIVEN';
        $dbname = 'portal_si';
        $username = 'avnadmin';
        $password = 'SENHA_DO_POSTGRESQL';
        $sslmode = 'require';
    } else {
        $host = 'localhost';
        $port = '5432';
        $dbname = 'portal_si';
        $username = 'postgres';
        $password = 'postgres';
        $sslmode = null;
    }

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    if ($sslmode) {
        $dsn .= ";sslmode={$sslmode}";
    }

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log('Erro de conexão PDO: ' . $e->getMessage());
        die('Falha crítica na conexão com o banco de dados. Contate o administrador.');
    }
}
