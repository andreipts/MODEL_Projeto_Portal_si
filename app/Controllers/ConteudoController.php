<?php
class ConteudoController
{
    public function index(): void
    {
        exigirLogin();
        view('admin/conteudos', [
            'tituloPagina' => 'Conteúdos',
            'conteudos' => (new Conteudo())->listarTodos(),
            'sucesso' => isset($_GET['sucesso']),
            'erro' => $_GET['erro'] ?? '',
        ]);
    }

    public function form(?int $id = null, array $erros = [], array $dadosForm = []): void
    {
        exigirLogin();
        $conteudo = $dadosForm ?: ($id ? (new Conteudo())->buscarPorId($id) : null);

        if ($id && !$conteudo) {
            header('Location: ' . url('admin/conteudos') . '?erro=' . urlencode('Registro não encontrado'));
            exit;
        }

        view('admin/conteudo_form', [
            'tituloPagina' => $id ? 'Editar conteúdo' : 'Novo conteúdo',
            'conteudo' => $conteudo,
            'categorias' => (new Categoria())->listarAtivas(),
            'erros' => $erros,
        ]);
    }

    public function salvar(): void
    {
        exigirLogin();
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
        $titulo = trim($_POST['titulo'] ?? '');
        $resumo = trim($_POST['resumo'] ?? '');
        $corpo = trim($_POST['corpo'] ?? '');
        $linkYoutube = trim($_POST['link_youtube'] ?? '');
        $categoriaId = (int) ($_POST['categoria_id'] ?? 0);
        $status = $_POST['status'] ?? 'publicado';
        $erros = [];

        if ($titulo === '') {
            $erros[] = 'O campo Título é obrigatório.';
        } elseif (mb_strlen($titulo) > 200) {
            $erros[] = 'O título não pode exceder 200 caracteres.';
        }

        if ($categoriaId <= 0) {
            $erros[] = 'Selecione uma categoria válida.';
        }

        if ($corpo === '') {
            $erros[] = 'O campo Texto/Conteúdo é obrigatório.';
        }

        $model = new Conteudo();
        $atual = $id ? $model->buscarPorId($id) : null;
        if ($id && !$atual) {
            header('Location: ' . url('admin/conteudos') . '?erro=' . urlencode('Registro não encontrado'));
            exit;
        }

        $reexibir = [
            'id' => $id,
            'titulo' => $titulo,
            'resumo' => $resumo,
            'corpo' => $corpo,
            'link_youtube' => $linkYoutube,
            'categoria_id' => $categoriaId,
            'status' => $status,
            'imagem_capa' => $atual['imagem_capa'] ?? null,
        ];

        $caminhoImagem = $atual['imagem_capa'] ?? null;
        if (empty($erros)) {
            $caminhoImagem = processarUploadImagem(
                $_FILES['imagem'] ?? ['error' => UPLOAD_ERR_NO_FILE],
                $erros,
                $caminhoImagem
            );
            $reexibir['imagem_capa'] = $caminhoImagem;
        }

        if (!empty($erros)) {
            $this->form($id, $erros, $reexibir);
            return;
        }

        $dados = [
            ':titulo' => $titulo,
            ':slug' => $this->slug($titulo),
            ':resumo' => $resumo,
            ':corpo' => $corpo,
            ':imagem_capa' => $caminhoImagem,
            ':link_youtube' => $linkYoutube !== '' ? $linkYoutube : null,
            ':categoria_id' => $categoriaId,
            ':status' => $status,
        ];

        try {
            if ($id) {
                $model->atualizar($id, $dados);
            } else {
                $dados[':autor_id'] = (int) $_SESSION['usuario_id'];
                $model->criar($dados);
            }
        } catch (PDOException $e) {
            error_log('Erro ao persistir conteúdo: ' . $e->getMessage());
            $this->form($id, ['Falha ao persistir registro no banco de dados.'], $reexibir);
            return;
        }

        header('Location: ' . url('admin/conteudos') . '?sucesso=1');
        exit;
    }

    public function excluir(int $id): void
    {
        exigirLogin();

        if ($id <= 0) {
            header('Location: ' . url('admin/conteudos') . '?erro=' . urlencode('Identificador inválido'));
            exit;
        }

        $conteudo = (new Conteudo())->buscarPorId($id);
        if (!$conteudo) {
            header('Location: ' . url('admin/conteudos') . '?erro=' . urlencode('Registro inexistente'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            view('admin/confirmar_exclusao', [
                'tituloPagina' => 'Excluir conteúdo',
                'mensagem' => 'Deseja realmente excluir este conteúdo permanentemente?',
                'detalhe' => $conteudo['titulo'] ?? '',
                'acao' => url('admin/conteudos/excluir/' . $id),
                'voltar' => url('admin/conteudos'),
                'id' => $id,
            ]);
            return;
        }

        try {
            $ok = (new Conteudo())->excluir($id);
            if (!$ok) {
                header('Location: ' . url('admin/conteudos') . '?erro=' . urlencode('Registro inexistente'));
                exit;
            }
        } catch (PDOException $e) {
            error_log('Erro ao excluir conteúdo: ' . $e->getMessage());
            header('Location: ' . url('admin/conteudos') . '?erro=' . urlencode('Impossível excluir o registro'));
            exit;
        }

        header('Location: ' . url('admin/conteudos') . '?sucesso=1');
        exit;
    }

    private function slug(string $texto): string
    {
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
        $texto = strtolower(preg_replace('/[^a-z0-9]+/i', '-', (string) $texto));
        return trim($texto, '-') . '-' . time();
    }
}
