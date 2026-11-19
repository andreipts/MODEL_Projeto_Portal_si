<?php
class AuthController
{
    public function login(): void
    {
        $usuarioModel = new Usuario();
        $aviso = '';

        if ($usuarioModel->contar() === 0) {
            $usuarioModel->criarAdminPadrao();
            $aviso = 'Usuário administrador criado: admin@esucri.com.br / 123456';
        }

        $erro = $_GET['erro'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $usuario = $usuarioModel->buscarPorEmail($email);

            if ($usuario && $usuario['ativo'] && password_verify($senha, $usuario['senha_hash'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_perfil'] = $usuario['perfil'];
                $_SESSION['ultimo_acesso'] = time();
                header('Location: ' . url('admin'));
                exit;
            }

            $erro = 'E-mail ou senha inválidos.';
        }

        view('public/login', [
            'tituloPagina' => 'Login',
            'categorias' => (new Categoria())->listarAtivas(),
            'erro' => $erro,
            'aviso' => $aviso,
        ]);
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: ' . url('login'));
        exit;
    }
}
