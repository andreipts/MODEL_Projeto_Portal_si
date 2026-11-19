<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina ?? 'Painel'); ?> | Admin</title>
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
</head>
<body>
    <header class="topo topo-admin">
        <div class="container topo-inner">
            <a class="logo" href="<?php echo url('admin/conteudos'); ?>">Painel Admin</a>
            <nav>
                <a href="<?php echo url(); ?>">Site</a>
                <a href="<?php echo url('admin/conteudos'); ?>">Conteúdos</a>
                <a href="<?php echo url('admin/eventos'); ?>">Eventos</a>
                <a href="<?php echo url('logout'); ?>">Sair (<?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? ''); ?>)</a>
            </nav>
        </div>
    </header>
    <main class="container">
