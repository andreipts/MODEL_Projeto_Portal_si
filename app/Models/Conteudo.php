<?php
class Conteudo
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function listarPublicados(?int $categoriaId = null): array
    {
        $sql = "SELECT c.*, cat.nome AS categoria, cat.slug AS categoria_slug, u.nome AS autor_nome
                FROM conteudos c
                INNER JOIN categorias cat ON c.categoria_id = cat.id
                INNER JOIN usuarios u ON c.autor_id = u.id
                WHERE c.status = 'publicado'";
        $params = [];

        if ($categoriaId) {
            $sql .= " AND c.categoria_id = :categoria_id";
            $params[':categoria_id'] = $categoriaId;
        }

        $sql .= " ORDER BY c.publicado_em DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function listarRecentes(int $limite = 6): array
    {
        $sql = "SELECT c.id, c.titulo, c.resumo, c.corpo, c.imagem_capa, c.link_youtube, c.publicado_em,
                       cat.id AS categoria_id, cat.nome AS categoria, cat.slug AS categoria_slug,
                       u.nome AS autor_nome
                FROM conteudos c
                INNER JOIN categorias cat ON c.categoria_id = cat.id
                INNER JOIN usuarios u ON c.autor_id = u.id
                WHERE c.status = 'publicado'
                ORDER BY c.publicado_em DESC
                LIMIT :limite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function contarPorCategoria(int $categoriaId): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*) FROM conteudos WHERE categoria_id = :id AND status = 'publicado'"
        );
        $stmt->execute([':id' => $categoriaId]);
        return (int) $stmt->fetchColumn();
    }

    public function listarPaginados(int $categoriaId, int $limite, int $offset): array
    {
        $sql = "SELECT c.id, c.titulo, c.resumo, c.corpo, c.imagem_capa, c.link_youtube, c.publicado_em,
                       cat.nome AS categoria, cat.slug AS categoria_slug, u.nome AS autor_nome
                FROM conteudos c
                INNER JOIN categorias cat ON c.categoria_id = cat.id
                INNER JOIN usuarios u ON c.autor_id = u.id
                WHERE c.categoria_id = :categoria AND c.status = 'publicado'
                ORDER BY c.publicado_em DESC
                LIMIT :limite OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':categoria', $categoriaId, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT c.*, cat.nome AS categoria, u.nome AS autor_nome
                FROM conteudos c
                INNER JOIN categorias cat ON c.categoria_id = cat.id
                INNER JOIN usuarios u ON c.autor_id = u.id
                ORDER BY c.publicado_em DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT c.*, cat.nome AS categoria, cat.slug AS categoria_slug, u.nome AS autor_nome
             FROM conteudos c
             INNER JOIN categorias cat ON c.categoria_id = cat.id
             INNER JOIN usuarios u ON c.autor_id = u.id
             WHERE c.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $conteudo = $stmt->fetch();
        return $conteudo ?: null;
    }

    public function criar(array $dados): string
    {
        $sql = "INSERT INTO conteudos (titulo, slug, resumo, corpo, imagem_capa, link_youtube, categoria_id, autor_id, status)
                VALUES (:titulo, :slug, :resumo, :corpo, :imagem_capa, :link_youtube, :categoria_id, :autor_id, :status)
                RETURNING id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($dados);
        return (string) $stmt->fetchColumn();
    }

    public function atualizar(int $id, array $dados): void
    {
        $sql = "UPDATE conteudos
                SET titulo = :titulo, slug = :slug, resumo = :resumo, corpo = :corpo,
                    imagem_capa = :imagem_capa, link_youtube = :link_youtube,
                    categoria_id = :categoria_id, status = :status
                WHERE id = :id";
        $dados[':id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($dados);
    }

    public function excluir(int $id): bool
    {
        $registro = $this->buscarPorId($id);
        if (!$registro) {
            return false;
        }

        removerArquivoImagem($registro['imagem_capa'] ?? null);
        $stmt = $this->pdo->prepare("DELETE FROM conteudos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return true;
    }
}
