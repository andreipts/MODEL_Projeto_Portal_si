<?php
function valorData($valor): string
{
    if (!$valor) {
        return '';
    }
    $data = date_create($valor);
    return $data ? $data->format('Y-m-d\TH:i') : '';
}
?>
<?php require APP . '/Views/layouts/admin_header.php'; ?>

<h1><?php echo htmlspecialchars($tituloPagina); ?></h1>

<form action="<?php echo url('admin/eventos/salvar'); ?>" method="POST">
    <?php if (!empty($evento['id'])): ?>
        <input type="hidden" name="id" value="<?php echo (int) $evento['id']; ?>">
    <?php endif; ?>

    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo" required value="<?php echo htmlspecialchars($evento['titulo'] ?? ''); ?>">

    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao" rows="6"><?php echo htmlspecialchars($evento['descricao'] ?? ''); ?></textarea>

    <label for="data_inicio">Data de início:</label>
    <input type="datetime-local" name="data_inicio" id="data_inicio" required value="<?php echo htmlspecialchars(valorData($evento['data_inicio'] ?? null)); ?>">

    <label for="data_fim">Data de fim:</label>
    <input type="datetime-local" name="data_fim" id="data_fim" value="<?php echo htmlspecialchars(valorData($evento['data_fim'] ?? null)); ?>">

    <label for="local">Local:</label>
    <input type="text" name="local" id="local" value="<?php echo htmlspecialchars($evento['local'] ?? ''); ?>">

    <label for="link_inscricao">Link de inscrição:</label>
    <input type="url" name="link_inscricao" id="link_inscricao" value="<?php echo htmlspecialchars($evento['link_inscricao'] ?? ''); ?>">

    <label for="status">Status:</label>
    <select name="status" id="status">
        <option value="publicado" <?php echo (($evento['status'] ?? '') === 'publicado') ? 'selected' : ''; ?>>Publicado</option>
        <option value="rascunho" <?php echo (($evento['status'] ?? '') === 'rascunho') ? 'selected' : ''; ?>>Rascunho</option>
    </select>

    <button type="submit">Salvar</button>
</form>

<?php require APP . '/Views/layouts/admin_footer.php'; ?>
