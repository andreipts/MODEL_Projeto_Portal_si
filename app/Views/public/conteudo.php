<?php require APP . '/Views/layouts/header.php'; ?>

<?php if (empty($conteudo)): ?>
    <p class="estado-vazio">Conteúdo não encontrado.</p>
    <p><a class="btn-voltar" href="<?php echo url(); ?>">&larr; Voltar para a Home</a></p>
<?php else: ?>
    <?php
    $imagemRelativa = $conteudo['imagem_capa'] ?? '';
    $imagemExiste = $imagemRelativa !== '' && is_file(caminhoUploadAbsoluto($imagemRelativa));
    ?>
    <article class="artigo">
        <span class="card-categoria"><?php echo e($conteudo['categoria'] ?? ''); ?></span>
        <h1><?php echo e($conteudo['titulo']); ?></h1>
        <p class="artigo-meta">
            Por <?php echo e($conteudo['autor_nome'] ?? ''); ?>
            em <?php echo formatarData($conteudo['publicado_em'] ?? ''); ?>
        </p>

        <?php if ($imagemExiste): ?>
            <img class="artigo-imagem"
                 src="<?php echo e(url($imagemRelativa)); ?>"
                 alt="<?php echo e($conteudo['titulo']); ?>">
        <?php endif; ?>

        <div class="artigo-texto">
            <?php echo nl2br(e($conteudo['corpo'] ?: $conteudo['resumo'])); ?>
        </div>

        <?php if (!empty($videoId)): ?>
            <div class="video-responsivo">
                <iframe src="https://www.youtube.com/embed/<?php echo e($videoId); ?>"
                        title="Vídeo do conteúdo"
                        allowfullscreen
                        loading="lazy"></iframe>
            </div>
        <?php endif; ?>

        <a class="btn-voltar" href="<?php echo url(); ?>">&larr; Voltar para a Home</a>
    </article>
<?php endif; ?>

<?php require APP . '/Views/layouts/footer.php'; ?>
