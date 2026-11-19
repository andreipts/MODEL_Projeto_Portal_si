<?php
class HomeController
{
    public function index(): void
    {
        $categoriaModel = new Categoria();
        $conteudoModel = new Conteudo();
        $eventoModel = new Evento();

        view('public/home', [
            'tituloPagina' => 'Portal SI',
            'categorias' => $categoriaModel->listarAtivas(),
            'categoriasChips' => $categoriaModel->listarComTotal(),
            'conteudos' => $conteudoModel->listarRecentes(6),
            'eventos' => $eventoModel->listarPublicados(),
        ]);
    }

    public function pagina(string $slug): void
    {
        $categoriaModel = new Categoria();
        $conteudoModel = new Conteudo();
        $categoria = $categoriaModel->buscarPorSlug($slug);
        $categorias = $categoriaModel->listarAtivas();

        if (!$categoria) {
            http_response_code(404);
            view('public/pagina', [
                'tituloPagina' => 'Página não encontrada',
                'categorias' => $categorias,
                'categoria' => null,
                'conteudos' => [],
                'naoEncontrado' => true,
                'total' => 0,
                'paginaAtual' => 1,
                'totalPaginas' => 0,
            ]);
            return;
        }

        $porPagina = 6;
        $pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?: 1;
        if ($pagina < 1) {
            $pagina = 1;
        }

        $total = $conteudoModel->contarPorCategoria((int) $categoria['id']);
        $paginacao = calcularPaginacao($total, (int) $pagina, $porPagina);
        $conteudos = $conteudoModel->listarPaginados(
            (int) $categoria['id'],
            $porPagina,
            $paginacao['offset']
        );

        view('public/pagina', [
            'tituloPagina' => $categoria['nome'],
            'categorias' => $categorias,
            'categoria' => $categoria,
            'conteudos' => $conteudos,
            'naoEncontrado' => false,
            'total' => $total,
            'paginaAtual' => $paginacao['paginaAtual'],
            'totalPaginas' => $paginacao['totalPaginas'],
        ]);
    }

    public function conteudo(int $id): void
    {
        $categorias = (new Categoria())->listarAtivas();

        if ($id <= 0) {
            header('Location: ' . url());
            exit;
        }

        $conteudo = (new Conteudo())->buscarPorId($id);
        if (!$conteudo || ($conteudo['status'] ?? '') !== 'publicado') {
            http_response_code(404);
            view('public/conteudo', [
                'tituloPagina' => 'Conteúdo não encontrado',
                'categorias' => $categorias,
                'conteudo' => null,
                'videoId' => null,
            ]);
            return;
        }

        view('public/conteudo', [
            'tituloPagina' => $conteudo['titulo'],
            'categorias' => $categorias,
            'conteudo' => $conteudo,
            'videoId' => extrairIdYoutube($conteudo['link_youtube'] ?? null),
        ]);
    }
}
