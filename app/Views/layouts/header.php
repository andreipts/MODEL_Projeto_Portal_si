<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tituloPagina ?? 'Portal SI'); ?></title>
    <link rel="stylesheet" href="<?php echo url('assets/css/style.css'); ?>">
</head>
<body>
    <header class="topo">
        <div class="container topo-inner">
            <a class="logo" href="<?php echo url(); ?>">Portal SI</a>
            <nav>
                <?php foreach (($categorias ?? []) as $item): ?>
                    <?php if ($item['slug'] === 'eventos'): ?>
                        <a href="<?php echo url('eventos'); ?>"><?php echo htmlspecialchars($item['nome']); ?></a>
                    <?php else: ?>
                        <a href="<?php echo url($item['slug']); ?>"><?php echo htmlspecialchars($item['nome']); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (usuarioLogado()): ?>
                    <a href="<?php echo url('admin/conteudos'); ?>">Painel</a>
                    <a href="<?php echo url('logout'); ?>">Sair</a>
                <?php else: ?>
                    <a href="<?php echo url('login'); ?>">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="container">
