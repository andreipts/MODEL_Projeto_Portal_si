<?php require APP . '/Views/layouts/admin_header.php'; ?>

<div class="header-panel">
    <h1>Gestão de Conteúdos</h1>
    <a class="botao" href="<?php echo url('admin/conteudos/novo'); ?>">+ Novo Conteúdo</a>
</div>

<?php if (!empty($sucesso)): ?>
    <div class="alert alert-success">Operação realizada com sucesso!</div>
<?php elseif (!empty($erro)): ?>
    <div class="alert alert-danger"><?php echo e($erro); ?></div>
<?php endif; ?>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Capa</th>
                <th>Título</th>
                <th>Categoria</th>
                <th>Autor</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($conteudos)): ?>
                <tr>
                    <td colspan="8" class="celula-vazia">Nenhum registro encontrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($conteudos as $item): ?>
                    <?php
                    $imagemRelativa = $item['imagem_capa'] ?? '';
                    $imagemExiste = $imagemRelativa !== '' && is_file(caminhoUploadAbsoluto($imagemRelativa));
                    ?>
                    <tr>
                        <td><?php echo (int) $item['id']; ?></td>
                        <td>
                            <?php if ($imagemExiste): ?>
                                <img class="miniatura" src="<?php echo e(url($imagemRelativa)); ?>" alt="Miniatura">
                            <?php else: ?>
                                <span class="sem-foto">Sem foto</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo e($item['titulo']); ?></strong></td>
                        <td><?php echo e($item['categoria'] ?? ''); ?></td>
                        <td><?php echo e($item['autor_nome'] ?? ''); ?></td>
                        <td><?php echo e(formatarData($item['publicado_em'] ?? '', 'd/m/Y H:i')); ?></td>
                        <td><?php echo e($item['status']); ?></td>
                        <td>
                            <a href="<?php echo url('admin/conteudos/editar/' . $item['id']); ?>">Editar</a>
                            |
                            <a class="excluir" href="<?php echo url('admin/conteudos/excluir/' . $item['id']); ?>">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require APP . '/Views/layouts/admin_footer.php'; ?>
