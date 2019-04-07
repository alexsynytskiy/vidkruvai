<?php

$asset = \app\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('/_blocks/_meta-tags', ['asset' => $asset]) ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= $this->render('/_blocks/header') ?>
<?= $content ?>
<?= $this->render('/_blocks/footer') ?>
<?= $this->render('/_blocks/flash-messages'); ?>
<?php $this->endBody() ?>
<div class="load-blocker"></div>
</body>
</html>
<?php $this->endPage() ?>
