<?php
$textoCard = $item['resumo'] ?: $item['corpo'];
$imagemRelativa = $item['imagem_capa'] ?? '';
$imagemExiste = $imagemRelativa !== '' && is_file(caminhoUploadAbsoluto($imagemRelativa));
?>
<article class="card-conteudo">
    <?php if ($imagemExiste): ?>
        <img class="card-imagem"
             src="<?php echo e(url($imagemRelativa)); ?>"
             alt="<?php echo e($item['titulo']); ?>">
    <?php else: ?>
        <div class="card-imagem card-sem-imagem">Sem imagem</div>
    <?php endif; ?>
    <div class="card-corpo">
        <span class="card-categoria"><?php echo e($item['categoria'] ?? 'Geral'); ?></span>
        <h3 class="card-titulo">
            <a href="<?php echo url('conteudo/' . (int) $item['id']); ?>">
                <?php echo e($item['titulo']); ?>
            </a>
        </h3>
        <p class="card-resumo"><?php echo e(resumirTexto($textoCard)); ?></p>
        <div class="card-rodape">
            <span><?php echo e($item['autor_nome'] ?? ''); ?></span>
            <time datetime="<?php echo e($item['publicado_em'] ?? ''); ?>">
                <?php echo formatarData($item['publicado_em'] ?? ''); ?>
            </time>
        </div>
    </div>
</article>
