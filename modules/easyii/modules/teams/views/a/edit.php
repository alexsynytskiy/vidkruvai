<?php
$this->title = Yii::t('easyii', 'Редактировать команду');

?>
<?= $this->render('_menu') ?>

<div class="edit-form">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>