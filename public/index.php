<?php
session_start();

define('BASE_PATH', dirname(__DIR__));
define('APP', BASE_PATH . '/app');
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

require BASE_PATH . '/config/database.php';
require APP . '/autoload.php';

$uri = trim($_GET['url'] ?? '', '/');
$partes = $uri === '' ? [] : explode('/', $uri);
$rota = $partes[0] ?? 'home';

switch ($rota) {
    case '':
    case 'home':
        (new HomeController())->index();
        break;

    case 'login':
        (new AuthController())->login();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'eventos':
        (new EventoController())->index();
        break;

    case 'conteudo':
        $id = filter_var($partes[1] ?? 0, FILTER_VALIDATE_INT);
        (new HomeController())->conteudo((int) $id);
        break;

    case 'admin':
        $sub = $partes[1] ?? 'conteudos';
        if ($sub === 'conteudos') {
            $acao = $partes[2] ?? 'index';
            $controller = new ConteudoController();
            if ($acao === 'novo') {
                $controller->form();
            } elseif ($acao === 'editar') {
                $controller->form((int) ($partes[3] ?? 0));
            } elseif ($acao === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->salvar();
            } elseif ($acao === 'excluir') {
                $controller->excluir((int) ($partes[3] ?? 0));
            } else {
                $controller->index();
            }
        } elseif ($sub === 'eventos') {
            $acao = $partes[2] ?? 'index';
            $controller = new EventoController();
            if ($acao === 'novo') {
                $controller->form();
            } elseif ($acao === 'editar') {
                $controller->form((int) ($partes[3] ?? 0));
            } elseif ($acao === 'salvar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->salvar();
            } elseif ($acao === 'excluir') {
                $controller->excluir((int) ($partes[3] ?? 0));
            } else {
                $controller->admin();
            }
        } else {
            (new ConteudoController())->index();
        }
        break;

    default:
        (new HomeController())->pagina($rota);
        break;
}
