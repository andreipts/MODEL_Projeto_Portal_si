<?php
class Usuario
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function buscarPorEmail(string $email): ?array
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public function contar(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    }

    public function criarAdminPadrao(): void
    {
        $sql = "INSERT INTO usuarios (nome, email, senha_hash, perfil)
                VALUES (:nome, :email, :senha, :perfil)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => 'Administrador',
            ':email' => 'admin@esucri.com.br',
            ':senha' => password_hash('123456', PASSWORD_DEFAULT),
            ':perfil' => 'admin',
        ]);
    }
}
