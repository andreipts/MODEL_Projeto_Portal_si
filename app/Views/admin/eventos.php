<?php require APP . '/Views/layouts/admin_header.php'; ?>

<h1>Eventos</h1>
<p><a class="botao" href="<?php echo url('admin/eventos/novo'); ?>">Novo evento</a></p>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Início</th>
            <th>Local</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($eventos as $evento): ?>
            <tr>
                <td><?php echo (int) $evento['id']; ?></td>
                <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                <td><?php echo htmlspecialchars(formatarData($evento['data_inicio'], 'd/m/Y H:i')); ?></td>
                <td><?php echo htmlspecialchars($evento['local']); ?></td>
                <td><?php echo htmlspecialchars($evento['status']); ?></td>
                <td>
                    <a href="<?php echo url('admin/eventos/editar/' . $evento['id']); ?>">Editar</a>
                    |
                    <a class="excluir" href="<?php echo url('admin/eventos/excluir/' . $evento['id']); ?>">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require APP . '/Views/layouts/admin_footer.php'; ?>
