<?php if ($flash = getFlash('alert')) : ?>
    <div class="alert alert-<?= $flash['type']; ?> " role="alert">
        <div><?= $flash['message']; ?></div>
    </div>
<?php endif; ?>