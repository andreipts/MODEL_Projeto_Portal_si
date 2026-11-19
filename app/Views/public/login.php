<?php require APP . '/Views/layouts/header.php'; ?>

<section class="login-box">
    <h1>Login</h1>
    <?php if (!empty($aviso)): ?>
        <p class="aviso"><?php echo htmlspecialchars($aviso); ?></p>
    <?php endif; ?>
    <?php if (!empty($erro)): ?>
        <p class="erro"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>
    <form action="<?php echo url('login'); ?>" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>
        <button type="submit">Entrar</button>
    </form>
</section>

<?php require APP . '/Views/layouts/footer.php'; ?>
