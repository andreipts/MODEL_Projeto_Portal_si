<?php
class Evento
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function listarPublicados(): array
    {
        $sql = "SELECT * FROM eventos
                WHERE status = 'publicado'
                ORDER BY data_inicio DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT e.*, u.nome AS autor
                FROM eventos e
                LEFT JOIN usuarios u ON e.autor_id = u.id
                ORDER BY e.data_inicio DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM eventos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $evento = $stmt->fetch();
        return $evento ?: null;
    }

    public function criar(array $dados): string
    {
        $sql = "INSERT INTO eventos (titulo, descricao, data_inicio, data_fim, local, link_inscricao, autor_id, status)
                VALUES (:titulo, :descricao, :data_inicio, :data_fim, :local, :link_inscricao, :autor_id, :status)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($dados);
        return $this->pdo->lastInsertId();
    }

    public function atualizar(int $id, array $dados): void
    {
        $sql = "UPDATE eventos
                SET titulo = :titulo, descricao = :descricao, data_inicio = :data_inicio,
                    data_fim = :data_fim, local = :local, link_inscricao = :link_inscricao, status = :status
                WHERE id = :id";
        $dados[':id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($dados);
    }

    public function excluir(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM eventos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
