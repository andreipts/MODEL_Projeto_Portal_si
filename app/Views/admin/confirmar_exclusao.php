<?php require APP . '/Views/layouts/admin_header.php'; ?>

<h1><?php echo e($tituloPagina ?? 'Confirmar exclusão'); ?></h1>
<p><?php echo e($mensagem ?? 'Confirma a exclusão?'); ?></p>

<?php if (!empty($detalhe)): ?>
    <p><strong><?php echo e($detalhe); ?></strong></p>
<?php endif; ?>

<form action="<?php echo e($acao); ?>" method="POST">
    <input type="hidden" name="id" value="<?php echo (int) ($id ?? 0); ?>">
    <button type="submit" class="excluir">Excluir</button>
    <a class="botao" href="<?php echo e($voltar); ?>">Cancelar</a>
</form>

<?php require APP . '/Views/layouts/admin_footer.php'; ?>
