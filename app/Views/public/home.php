<?php require APP . '/Views/layouts/header.php'; ?>

<section class="hero">
    <h1>Portal de Comunicação — Sistemas de Informação</h1>
    <p>Notícias, eventos, ações sociais e projetos de extensão do curso.</p>
</section>

<section class="secao">
    <h2>Últimas Publicações</h2>
    <?php if (empty($conteudos)): ?>
        <p class="estado-vazio">Nenhum conteúdo publicado até o momento.</p>
    <?php else: ?>
        <div class="grid-cards">
            <?php foreach ($conteudos as $item): ?>
                <?php include APP . '/Views/public/card_conteudo.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="secao">
    <h2>Navegue por Categoria</h2>
    <div class="lista-categorias">
        <?php foreach (($categoriasChips ?? []) as $cat): ?>
            <?php
            $href = ($cat['slug'] === 'eventos') ? url('eventos') : url($cat['slug']);
            ?>
            <a class="chip-categoria" href="<?php echo $href; ?>">
                <?php echo e($cat['nome']); ?>
                <span class="chip-total"><?php echo (int) $cat['total']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="secao">
    <h2>Próximos eventos</h2>
    <?php if (empty($eventos)): ?>
        <p class="estado-vazio">Nenhum evento cadastrado.</p>
    <?php else: ?>
        <?php foreach ($eventos as $evento): ?>
            <article class="card">
                <h3><?php echo e($evento['titulo']); ?></h3>
                <p><strong>Data:</strong> <?php echo e(formatarData($evento['data_inicio'], 'd/m/Y H:i')); ?></p>
                <p><strong>Local:</strong> <?php echo e($evento['local']); ?></p>
                <p><?php echo nl2br(e($evento['descricao'])); ?></p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php require APP . '/Views/layouts/footer.php'; ?>
