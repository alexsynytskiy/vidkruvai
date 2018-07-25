<?php

use yii\helpers\Html;

$flashes = Yii::$app->session->getAllFlashes();
?>

<?php if ($flashes): ?>
    <div id="landings-flash-messages" class="hidden">
        <?php foreach ($flashes as $status => $flash): ?>
            <?php
            $isSuccess = ($status === 'success');

            $style = $status;
            $title = $isSuccess ? 'Успіх!' : 'Проблема..';
            $icon = $isSuccess ? 'icon-checkmark3' : 'icon-blocked';

            $flash = (is_array($flash)) ? $flash : [$flash];

            foreach ($flash as $subMessage): ?>
                <div class="message" data-title="<?= $title; ?>" data-icon="<?= $icon; ?>" data-style="<?= $style; ?>"
                     data-status="<?= $status; ?>"><?= Html::encode($subMessage); ?></div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>