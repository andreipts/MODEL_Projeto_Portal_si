<?php require APP . '/Views/layouts/admin_header.php'; ?>

<div class="header-panel">
    <h1><?php echo e($tituloPagina); ?></h1>
    <a class="botao" href="<?php echo url('admin/conteudos'); ?>">Voltar à Listagem</a>
</div>

<?php if (!empty($erros)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($erros as $erro): ?>
                <li><?php echo e($erro); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo url('admin/conteudos/salvar'); ?>" method="POST" enctype="multipart/form-data">
    <?php if (!empty($conteudo['id'])): ?>
        <input type="hidden" name="id" value="<?php echo (int) $conteudo['id']; ?>">
    <?php endif; ?>

    <label for="titulo">Título do Conteúdo *</label>
    <input type="text" name="titulo" id="titulo" maxlength="200" required value="<?php echo e($conteudo['titulo'] ?? ''); ?>">

    <label for="categoria_id">Categoria Acadêmica *</label>
    <select name="categoria_id" id="categoria_id" required>
        <option value="">Selecione uma categoria...</option>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?php echo (int) $categoria['id']; ?>" <?php echo ((int) ($conteudo['categoria_id'] ?? 0) === (int) $categoria['id']) ? 'selected' : ''; ?>>
                <?php echo e($categoria['nome']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="resumo">Resumo</label>
    <textarea name="resumo" id="resumo" rows="3"><?php echo e($conteudo['resumo'] ?? ''); ?></textarea>

    <label for="corpo">Corpo da Publicação (Texto Integral) *</label>
    <textarea name="corpo" id="corpo" rows="8" required><?php echo e($conteudo['corpo'] ?? ''); ?></textarea>

    <label for="imagem">Imagem Destaque (Capa — máx. 2 MB, JPG/PNG/GIF)</label>
    <?php if (!empty($conteudo['imagem_capa'])): ?>
        <div class="preview-imagem">
            <?php if (is_file(caminhoUploadAbsoluto($conteudo['imagem_capa']))): ?>
                <img class="miniatura-grande" src="<?php echo e(url($conteudo['imagem_capa'])); ?>" alt="Imagem atual">
            <?php endif; ?>
            <p>Deixe vazio para manter a imagem atual.</p>
        </div>
    <?php endif; ?>
    <input type="file" name="imagem" id="imagem" accept="image/jpeg,image/png,image/gif">

    <label for="link_youtube">URL de Vídeo do YouTube</label>
    <input type="url" name="link_youtube" id="link_youtube" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo e($conteudo['link_youtube'] ?? ''); ?>">

    <label for="status">Status</label>
    <select name="status" id="status">
        <option value="publicado" <?php echo (($conteudo['status'] ?? 'publicado') === 'publicado') ? 'selected' : ''; ?>>Publicado</option>
        <option value="rascunho" <?php echo (($conteudo['status'] ?? '') === 'rascunho') ? 'selected' : ''; ?>>Rascunho</option>
    </select>

    <button type="submit">Salvar e Publicar</button>
</form>

<?php require APP . '/Views/layouts/admin_footer.php'; ?>
