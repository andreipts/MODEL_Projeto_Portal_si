<?php require APP . '/Views/layouts/header.php'; ?>

<?php if (!empty($naoEncontrado) || empty($categoria)): ?>
    <p class="estado-vazio">Categoria não encontrada.</p>
<?php else: ?>
    <section class="secao">
        <h1><?php echo e($categoria['nome']); ?></h1>
        <?php if (!empty($categoria['descricao'])): ?>
            <p><?php echo e($categoria['descricao']); ?></p>
        <?php endif; ?>
        <p class="contador"><?php echo (int) $total; ?> publicações encontradas</p>

        <?php if (empty($conteudos)): ?>
            <p class="estado-vazio">Ainda não há publicações nesta categoria.</p>
        <?php else: ?>
            <div class="grid-cards">
                <?php foreach ($conteudos as $item): ?>
                    <?php include APP . '/Views/public/card_conteudo.php'; ?>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPaginas > 1): ?>
                <nav class="paginacao">
                    <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                        <a class="<?php echo $p === (int) $paginaAtual ? 'ativo' : ''; ?>"
                           href="<?php echo url($categoria['slug']); ?>?pagina=<?php echo $p; ?>">
                            <?php echo $p; ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php require APP . '/Views/layouts/footer.php'; ?>
