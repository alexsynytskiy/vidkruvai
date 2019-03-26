<?php
$msg = $msg ?: \app\components\AppMsg::t('Нічого не найдено');
?>

<div class="empty-block">
    <?= $msg; ?>
</div>