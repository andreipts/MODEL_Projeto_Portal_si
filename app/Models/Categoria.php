<?php
class Categoria
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function listarAtivas(): array
    {
        $sql = "SELECT * FROM categorias WHERE ativo = true ORDER BY ordem, nome";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function listarComTotal(): array
    {
        $sql = "SELECT cat.id, cat.nome, cat.slug, cat.descricao, cat.ordem, cat.ativo,
                       COUNT(c.id) AS total
                FROM categorias cat
                LEFT JOIN conteudos c ON c.categoria_id = cat.id AND c.status = 'publicado'
                WHERE cat.ativo = true
                GROUP BY cat.id, cat.nome, cat.slug, cat.descricao, cat.ordem, cat.ativo
                ORDER BY cat.ordem, cat.nome";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $categoria = $stmt->fetch();
        return $categoria ?: null;
    }

    public function buscarPorSlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE slug = :slug LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        $categoria = $stmt->fetch();
        return $categoria ?: null;
    }
}
