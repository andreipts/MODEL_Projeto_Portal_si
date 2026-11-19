<?php
class EventoController
{
    public function index(): void
    {
        view('public/eventos', [
            'tituloPagina' => 'Eventos',
            'categorias' => (new Categoria())->listarAtivas(),
            'eventos' => (new Evento())->listarPublicados(),
        ]);
    }

    public function admin(): void
    {
        exigirLogin();
        view('admin/eventos', [
            'tituloPagina' => 'Eventos',
            'eventos' => (new Evento())->listarTodos(),
        ]);
    }

    public function form(?int $id = null): void
    {
        exigirLogin();
        $evento = $id ? (new Evento())->buscarPorId($id) : null;
        view('admin/evento_form', [
            'tituloPagina' => $evento ? 'Editar evento' : 'Novo evento',
            'evento' => $evento,
        ]);
    }

    public function salvar(): void
    {
        exigirLogin();
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;
        $dados = [
            ':titulo' => $_POST['titulo'] ?? '',
            ':descricao' => $_POST['descricao'] ?? '',
            ':data_inicio' => $_POST['data_inicio'] ?? null,
            ':data_fim' => $_POST['data_fim'] !== '' ? $_POST['data_fim'] : null,
            ':local' => $_POST['local'] ?? '',
            ':link_inscricao' => $_POST['link_inscricao'] ?? '',
            ':status' => $_POST['status'] ?? 'publicado',
        ];

        $model = new Evento();
        if ($id) {
            $model->atualizar($id, $dados);
        } else {
            $dados[':autor_id'] = $_SESSION['usuario_id'];
            $model->criar($dados);
        }

        header('Location: ' . url('admin/eventos'));
        exit;
    }

    public function excluir(int $id): void
    {
        exigirLogin();

        if ($id <= 0) {
            header('Location: ' . url('admin/eventos'));
            exit;
        }

        $evento = (new Evento())->buscarPorId($id);
        if (!$evento) {
            header('Location: ' . url('admin/eventos'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            view('admin/confirmar_exclusao', [
                'tituloPagina' => 'Excluir evento',
                'mensagem' => 'Excluir este evento?',
                'detalhe' => $evento['titulo'] ?? '',
                'acao' => url('admin/eventos/excluir/' . $id),
                'voltar' => url('admin/eventos'),
                'id' => $id,
            ]);
            return;
        }

        (new Evento())->excluir($id);
        header('Location: ' . url('admin/eventos'));
        exit;
    }
}
