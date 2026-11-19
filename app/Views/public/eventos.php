<?php require APP . '/Views/layouts/header.php'; ?>

<h1>Eventos</h1>

<?php if (empty($eventos)): ?>
    <p>Nenhum evento publicado ainda.</p>
<?php else: ?>
    <?php foreach ($eventos as $evento): ?>
        <article class="card">
            <h2><?php echo htmlspecialchars($evento['titulo']); ?></h2>
            <p><strong>Início:</strong> <?php echo htmlspecialchars(formatarData($evento['data_inicio'], 'd/m/Y H:i')); ?></p>
            <?php if (!empty($evento['data_fim'])): ?>
                <p><strong>Fim:</strong> <?php echo htmlspecialchars(formatarData($evento['data_fim'], 'd/m/Y H:i')); ?></p>
            <?php endif; ?>
            <p><strong>Local:</strong> <?php echo htmlspecialchars($evento['local']); ?></p>
            <p><?php echo nl2br(htmlspecialchars($evento['descricao'])); ?></p>
            <?php if (!empty($evento['link_inscricao'])): ?>
                <p><a href="<?php echo htmlspecialchars($evento['link_inscricao']); ?>" target="_blank">Inscreva-se</a></p>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
<?php endif; ?>

<?php require APP . '/Views/layouts/footer.php'; ?>
